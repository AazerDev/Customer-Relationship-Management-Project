<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\Company;
use App\Http\Requests\CompanyRequest;
use App\Models\RoleGroup;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CompanyController extends Controller
{
    /**
     * Display a listing companies for super admin
     */
    public function index(Request $request): JsonResponse
    {
        $query = Company::with('roleGroup.permissionGroup', 'user');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('subdomain', 'like', "%{$search}%")
                    ->orWhere('admin_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->per_page ?? 10;
        $companies = $query->orderByDesc('created_at')->paginate($perPage);
        return apiSuccess($companies, 'Companies retrieved successfully');
    }

    /**
     * Store or update company & admin & give role to admin
     */
    public function storeOrUpdateWithAdmin(CompanyRequest $request, $id = null): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $suggestedSubdomains = $this->generateSuggestedSubdomains($data['company_name']);

            // --- Handle logo upload ---
            if ($request->hasFile('logo')) {
                if (!empty($id)) {
                    $oldCompany = Company::find($id);
                    if ($oldCompany && $oldCompany->logo) {
                        FileUploadHelper::deleteFile($oldCompany->logo);
                    }
                }
                $data['logo'] = FileUploadHelper::uploadFile($request->file('logo'), 'companies');
            }


            // --- Validate Role ---
            $role = Role::find($data['role_id']);
            if (!$role) {
                DB::rollBack();
                return apiError('Invalid role', 422, 'Role not found');
            }

            $rGroup = RoleGroup::where(['role_id' => $role->id])->with('permissionGroup')->first();
            if ($rGroup) {
                $data['package_start_at'] = now();
                $data['package_end_at'] = now()->addDays($rGroup->permissionGroup->duration);
            }


            // --- Company create or update ---
            if ($id) {
                $company = Company::findOrFail($id);
                $company->update($data);
                $message = 'Company updated successfully';
                $statusCode = 200;
            } else {
                $data['onboarded_at'] = now();
                $data['activated_at'] = now();

                if (Company::where('subdomain', $data['subdomain'])->exists()) {
                    return apiError('Subdomain is already taken', 422, [
                        'suggested_subdomains' => $suggestedSubdomains
                    ]);
                }

                $company = Company::create($data);
                $message = 'Company onboarded successfully';
                $statusCode = 201;
            }

            // --- Create or Update Admin User ---
            $userData = [
                'name'       => $data['name'],
                'email'      => $data['admin_email'],
                'role_id'    => $role->id,
                'company_id' => $company->id,
                'user_type'  => 'admin',
            ];

            if (!empty($data['password'])) {
                $userData['password'] = bcrypt($data['password']);
            }

            $user = User::updateOrCreate(
                ['email' => $data['admin_email']], // update if email exists
                $userData
            );

            // Assign role via Spatie
            $user->syncRoles([$role->id]);

            // Generate reference number if missing
            if (empty($user->reference_num)) {
                $userRefNum = 'usr-' . rand(100, 999) . $user->id;
                $user->update(['reference_num' => $userRefNum]);
            }

            DB::commit();

            return apiSuccess(
                [
                    'company' => array_merge($company->toArray(), [
                        'suggested_subdomains' => $suggestedSubdomains
                    ]),
                    'admin_user' => $user,
                    'permissionGroup' => $rGroup?->permissionGroup,
                ],
                $message,
                $statusCode
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return apiError('Failed to onboard company with admin', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified company
     */
    public function show($id): JsonResponse
    {
        $company = Company::with('roleGroup.permissionGroup')->findOrFail($id);
        return apiSuccess($company, 'Company retrieved successfully');
    }

    /**
     * Remove the specified company
     */
    public function destroy($id): JsonResponse
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return apiSuccess(null, 'Company deleted successfully');
    }

    /**
     * Check Subdomain
     */
    public function checkSubdomain(Request $request): JsonResponse
    {
        $request->validate([
            'subdomain' => 'required|string|regex:/^[a-zA-Z0-9-]+$/'
        ]);

        $exists = Company::where('subdomain', $request->subdomain)->exists();
        $suggested = $this->generateSuggestedSubdomains($request->subdomain);

        return apiSuccess([
            'available' => !$exists,
            'suggested_subdomains' => $suggested
        ], 'Subdomain checked successfully');
    }

    /**
     * Update company and user account status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $company = Company::findOrFail($id);

        $updateData = ['status' => $request->status];
        if ($request->status === 'active') {
            $updateData['activated_at'] = now();
        }

        $company->update($updateData);

        $isActive = $request->status === 'active' ? 1 : 0;
        User::where(['company_id' => $company->id, 'email' => $company->admin_email])->update(['is_active' => $isActive]);

        $message = $isActive == 1 ? 'Company and admin Activated successfully' : 'Company and admin Deactivated successfully';
        return apiSuccess($company, $message);
    }


    private function generateSuggestedSubdomains(string $companyName): array
    {
        $base = Str::slug($companyName);
        $suggestions = [];

        $suggestions[] = $base;

        $suffixes = ['inc', 'co', 'corp', 'llc', 'tech', 'ai', 'io'];
        foreach ($suffixes as $suffix) {
            $suggestions[] = "{$base}-{$suffix}";
        }

        $words = explode('-', $base);
        if (count($words) > 1) {
            $short = implode('', array_map(fn($w) => substr($w, 0, 1), $words));
            $suggestions[] = $short;
        }

        $suggestions = array_unique($suggestions);
        $availableSuggestions = [];

        foreach ($suggestions as $suggestion) {
            if (!Company::where('subdomain', $suggestion)->exists()) {
                $availableSuggestions[] = $suggestion;
            }
        }

        return array_slice($availableSuggestions, 0, 5);
    }
}
