<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use App\Models\RoleGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Exception;

class PermissionController extends Controller
{

    /**
     * Get default permissions
     */
    function permissionsDefault()
    {
        $permissions = Permission::all();
        return apiSuccess($permissions, 'Default permissions fetched successfully');
    }

    /**
     * Get permission groups with role groups
     */
    function permissionGroups(Request $request)
    {
        try {
            $user = auth()->user();

            $search = trim($request->query('search'));
            $paginateParam = $request->disable_page_param;

            $permissionGroupsQuery = PermissionGroup::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })->orderBy('id', 'desc');

            // Apply condition based on user_type
            if ($user && $user->user_type == 'super_admin') {
                $permissionGroupsQuery->where(['type' => 'package']);
            }

            $transformGroup = function ($group) {
                $roleGroup = RoleGroup::where('assign_group_id', $group->id)->first();
                return [
                    'id'          => $group->id,
                    'name'        => $group->name,
                    'price'       => $group->price,
                    'duration'    => $group->duration,
                    'permissions' => $group->permissions ? json_decode($group->permissions) : [],
                    'role_id'     => $roleGroup ? $roleGroup->role_id : null
                ];
            };

            if ($paginateParam && $paginateParam == 1) {
                $permissionGroups = $permissionGroupsQuery->get()->map($transformGroup);
            } else {
                $permissionGroups = $permissionGroupsQuery->paginate($request->limit ?? 10)->through($transformGroup);
            }

            return apiSuccess($permissionGroups, 'Permission Groups fetched successfully', 200);
        } catch (Exception $e) {
            return apiError('Failed to fetch permission groups', 500, $e->getMessage());
        }
    }

    /**
     * Store (package)=> permission group and role and role group
     */
    function permissionGroupStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'duration'    => 'required|integer',
            'permissions' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return apiError('Validation failed', 422, $validator->errors());
        }

        try {
            DB::beginTransaction();

            // Check duplicate package name
            $existingGroup = PermissionGroup::where('name', $request->name)
                ->when($request->id, fn($q) => $q->where('id', '<>', $request->id))
                ->first();
            if ($existingGroup) {
                return apiError('Permission group name must be unique', 422);
            }

            // Validate permissions
            $systemPermissions = Permission::pluck('name')->toArray();
            if ($request->permissions) {
                $invalidPermissions = array_diff($request->permissions, $systemPermissions);
                if ($invalidPermissions) {
                    return apiError('Invalid permissions detected', 422, [
                        'invalid_permissions' => array_values($invalidPermissions)
                    ]);
                }
            }

            $data = $request->only(['name', 'price', 'duration']);
            $data['permissions'] = $request->permissions ? json_encode($request->permissions) : null;
            $data['creator_id']  = auth()->id();
            $data['type']        = auth()->user()->user_type == 'super_admin' ? 'package' : null;

            $permissionGroup = PermissionGroup::updateOrCreate(
                ['id' => $request->id],
                $data
            );

            // --- Handle Spatie Role auto-link ---
            $role = Role::firstOrCreate(
                ['name' => $permissionGroup->name, 'guard_name' => 'sanctum']
            );
            $role->syncPermissions($request->permissions ?? []);

            // --- Keep RoleGroup table in sync (if you still use it) ---
            RoleGroup::updateOrCreate(
                ['assign_group_id' => $permissionGroup->id],
                [
                    'role_name'       => $permissionGroup->name,
                    'role_id'         => $role->id,
                    'assign_group_id' => $permissionGroup->id,
                    'creator_id'      => auth()->id(),
                    'type'            => auth()->user()->user_type == 'super_admin' ? 'package' : null
                ]
            );

            DB::commit();
            $response = $permissionGroup->toArray();
            $response['role_id'] = $role->id;

            return apiSuccess(
                $response,
                $request->id ? 'Package updated successfully' : 'Package created successfully',
                200
            );
        } catch (Exception $e) {
            DB::rollBack();
            return apiError('Failed to store package', 500, $e->getMessage());
        }
    }

    /**
     * Edit permission group
     */
    function permissionGroupEdit($id)
    {
        try {
            $permissionGroup = PermissionGroup::where('id', $id)->first();
            if (!$permissionGroup) {
                return apiError('Permission group not found', 404);
            }
            $permissionGroup->permissions = $permissionGroup->permissions ? json_decode($permissionGroup->permissions, true) : [];
            $roleGroup = RoleGroup::where('assign_group_id', $permissionGroup->id)->first();
            $permissionGroup->role_id = $roleGroup ? $roleGroup->role_id : null;

            return apiSuccess($permissionGroup, 'Permission group fetched successfully.', 200);
        } catch (Exception $e) {
            return apiError('Failed to fetch permission group', 500, $e->getMessage());
        }
    }

    /**
     * Delete permission group
     */
    function permissionGroupDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permission_group_id' => 'required|exists:permission_groups,id'
        ]);

        if ($validator->fails()) {
            return apiError('Validation failed', 422, $validator->errors());
        }

        try {
            DB::beginTransaction();

            $permissionGroup = PermissionGroup::where('id', $request->permission_group_id)->first();

            if ($permissionGroup->type == 'default') {
                DB::rollBack();
                return apiError('Unable to delete', 422, 'The system default permission group cannot be deleted.');
            }

            // Validate if assigned to role/user
            $roleGroup = RoleGroup::where('assign_group_id', $request->permission_group_id)->first();
            if ($roleGroup) {
                $userWithRole = User::where('role_id', $roleGroup->role_id)->first();
                if ($userWithRole) {
                    DB::rollBack();
                    return apiError('Unable to delete', 422, 'This package is assigned to an existing user and cannot be deleted.');
                }

                // Delete Spatie Role
                $spatieRole = Role::where('id', $roleGroup->role_id)->first();
                if ($spatieRole) {
                    $spatieRole->delete();
                }
                $roleGroup->delete();
            }

            $permissionGroup->delete();

            DB::commit();
            return apiSuccess('Permission Group deleted successfully', 200);
        } catch (Exception $e) {
            DB::rollBack();
            return apiError('Failed to delete permission group', 500, $e->getMessage());
        }
    }

    /**
     * Get only all users of system of company specific excluding (super admin and admin) with filters
     */
    public function allUsers(Request $request)
    {
        // Validate query parameters
        $validated = $request->validate([
            'searchValue' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
            'paginate' => 'required|in:true,false',
        ]);

        // Get query parameters
        $searchValue = $request->query('searchValue', '');
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 15);

        // Build query
        $query = User::where('user_type', 'user');

        // Apply search filter
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%');
            });
        }
        $query->where('type', null);
        if ($validated['paginate'] == 'false') {
            $users = $query->get();
        } else {
            $users = $query->paginate($limit, ['*'], 'page', $page);
        }
        return apiSuccess($users, 'Users retrieved successfully', 200);
    }

    /**
     * Store user
     */
    public function userStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $request->id,
            'role_id'  => 'required|exists:roles,id',
            'password' => $request->id ? 'nullable|min:6' : 'required|min:6',
        ]);

        if ($validator->fails()) {
            return apiError('Validation failed', 422, $validator->errors());
        }

        try {
            DB::beginTransaction();

            $role = Role::find($request->role_id);
            if (!$role) {
                return apiError('Invalid role', 422, 'Role not found');
            }

            $data = $request->only(['name', 'email']);
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }
            $data['role_id'] = $role->id;
            $data['company_id'] = auth()->user()->company_id;

            $user = User::updateOrCreate(
                ['id' => $request->id],
                $data
            );

            // Assign role via Spatie
            $user->syncRoles([$role->id]);

            // Generate reference number only for new user
            if (!$request->id && empty($user->reference_num)) {
                $userRefNum = 'usr-' . rand(100, 999) . $user->id;
                $user->update(['reference_num' => $userRefNum]);
            }

            DB::commit();

            return apiSuccess(
                $user,
                $request->id ? 'User updated successfully' : 'User created successfully',
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return apiError('Failed to save user', 500, $e->getMessage());
        }
    }

    /**
     * Enable/Disable all users against specific company (excluding super admin and admin)
     */
    function enableDisableUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return apiError('Validation failed', 422, $validator->errors());
        }

        try {
            $user = User::where(['id'=>$request->user_id, 'user_type'=>'user'])->first();
            //Validate if default user
            if ($user->type == 'default') {
                return apiError('Unable to delete', 422, 'System default user cannot be updated');
            }
            $successMessage = null;
            if ($user->is_active == 0) {
                $user->update(['is_active' => 1]);
                $successMessage = 'User enabled successfully.';
            } else {
                $user->update(['is_active' => 0]);
                $successMessage = 'User disabled successfully.';
            }
            return apiSuccess($user, $successMessage, 200);
        } catch (Exception $e) {
            return apiError('Failed to update user status', 500, $e->getMessage());
        }
    }
}
