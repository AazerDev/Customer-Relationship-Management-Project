<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // ========================
            // Task Management
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

            // ========================
            // User Settings
            // ========================
            'settings.view',
            'settings.create',
            'settings.edit',
            'settings.delete',
            'settings.custom-field',

            // ========================
            // Profile
            // ========================
            'profile.update',
            'profile.password',

            // ========================
            // System Settings
            // ========================
            'system-settings.view',   // getSingleSettings
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
            // Roles & Permissions
            // ========================
            'roles.view',
            'roles.create',
            'roles.delete',
            'permission-groups.view',
            'permission-groups.create',
            'permission-groups.edit',
            'permission-groups.delete',

        ];

        if (Schema::hasTable('permissions')) {
            $isEmptyType = Permission::count() === 0;
            if ($isEmptyType) {
                foreach ($permissions as $permission) {
                    Permission::findOrCreate($permission, 'sanctum');
                }
            }
        }
        if (Schema::hasTable('permission_groups')) {
            $isEmptyPermissionGroups = PermissionGroup::count() === 0;
            if ($isEmptyPermissionGroups) {
                PermissionGroup::create(['name' => 'Default Group', 'permissions' => json_encode($permissions), 'type' => 'default']);
            }
        }
    }
}
