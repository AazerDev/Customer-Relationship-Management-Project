<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use App\Models\RoleGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Schema::hasTable('roles')){
            $isEmptyRoles = Role::count() === 0;
            if($isEmptyRoles){
                Role::findOrCreate('Super Admin' , 'sanctum');
            }
        }

        if(Schema::hasTable('role_groups')){
            $isEmptyRoleGroups = RoleGroup::count() === 0;
            if($isEmptyRoleGroups){
                $role = Role::where('name' , 'Super Admin')->first();
                $permissionGroup = PermissionGroup::where('type','default')->first();
                $permissionGroupPermissions = $permissionGroup ? json_decode($permissionGroup->permissions) : '';
                if($role && $permissionGroup && $permissionGroupPermissions){
                    $role->syncPermissions($permissionGroupPermissions);
                    RoleGroup::create(['role_name' => $role->name , 'role_id' => $role->id , 'assign_group_id' => $permissionGroup->id , 'type' => 'default']);
                }
            }
        }
    }
}
