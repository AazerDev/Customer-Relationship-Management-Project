<?php

namespace Database\Seeders;

use App\Models\RoleGroup;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('users')) {
            $isEmpty = User::count() === 0;
            if ($isEmpty) {
                User::create([
                    'name'        => 'Super Admin',
                    'email'       => 'admin@gmail.com',
                    'password'    => bcrypt('12345'),
                    'type'        => 'default',
                    'is_active'   => 1,
                    'user_type'   => 'super_admin' 
                ]);
                $latestUser = User::orderBy('id', 'desc')->first();
                $user = User::find($latestUser->id);
                $defaultRoleGroup = RoleGroup::where('type', 'default')->first();
                $roleId = $defaultRoleGroup ? $defaultRoleGroup->role_id : '';
                $role = $roleId ? Role::where('id', $roleId)->first() : '';
                if ($role) {
                    $user->syncRoles($role);
                    $userRefNum = rand(100, 999);
                    $userRefNum = 'usr-' . $userRefNum . $user->id;
                    $user->update(['role_id' => $role->id, 'reference_num' => $userRefNum]);
                }
            }
        }
    }
}
