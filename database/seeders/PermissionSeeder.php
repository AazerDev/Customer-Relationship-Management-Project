<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use App\Models\PermissionGroup;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
             // ========================
            // Dashboard
            // ========================
            'dashboard.view',

            // ========================
            // Profile
            // ========================
            'profile.update',
            'profile.password',

            // ========================
            // System Settings
            // ========================
            'system-settings.view',   // getSingleSettings
            'system-settings.create',
            'system-settings.edit',   // storeOrUpdateSettings

            // ========================
            // Leads
            // ========================
            'lead.view',
            'lead.create',
            'lead.edit',
            'lead.delete',
            'lead.update-last-contacted',
            'lead.pipeline-board',

            // ========================
            // Clients
            // ========================
            'client.view',
            'client.create',
            'client.edit',
            'client.delete',

            // ========================
            // Meetings
            // ========================
            'meeting.view',
            'meeting.create',
            'meeting.edit',
            'meeting.delete',

            // ========================
            // Tasks
            // ========================
            'task.view',
            'task.create',
            'task.edit',
            'task.delete',

            // ========================
            // Company Management
            // ========================
            'company.view',
            'company.create',
            'company.edit',
            'company.delete',
            'company.check-subdomain',
            'company.activate',
            'company.update-status',

            // ========================
            // User Management
            // ========================
            'users.view',
            'users.create',
            'users.edit',

            // ========================
            // Roles & Permission Groups
            // ========================
            'roles.view',
            'roles.create',
            'roles.delete',
            'permission-groups.view',
            'permission-groups.create',
            'permission-groups.edit',
            'permission-groups.delete',

            // ========================
            // Settings
            // ========================
            'settings.view',
            'settings.create',
            'settings.edit',
            'settings.delete',
            'settings.custom-field',
        ];


         if(Schema::hasTable('permissions')){
            $isEmptyType = Permission::count() === 0;
            if($isEmptyType){
                foreach($permissions as $permission){
                    Permission::findOrCreate($permission , 'sanctum');
                }
            }
        }
        if(Schema::hasTable('permission_groups')){
            $isEmptyPermissionGroups = PermissionGroup::count() === 0;
            if($isEmptyPermissionGroups){
                PermissionGroup::create(['name' => 'Default Group' , 'permissions' => json_encode($permissions) , 'type' => 'default']);
            }
        }
    }
}
