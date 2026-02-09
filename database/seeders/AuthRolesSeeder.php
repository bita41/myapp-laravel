<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRolesSeeder extends Seeder
{
    /**
     * Seed auth/roles tables: languages, users_roles, users_roles_modules, users, users_permissions.
     */
    public function run(): void
    {
        $this->seedLanguages();
        $this->seedUsersRoles();
        $this->seedUsersRolesModules();
        $this->seedUsers();
        $this->seedUsersPermissions();
    }

    private function seedLanguages(): void
    {
        if (DB::table('languages')->exists()) {
            return;
        }
        DB::table('languages')->insert([
            ['language_id' => 1, 'name' => 'Romana', 'code' => 'ro', 'file' => 'romanian'],
            ['language_id' => 2, 'name' => 'Engleza', 'code' => 'en', 'file' => 'english'],
        ]);
    }

    private function seedUsersRoles(): void
    {
        if (DB::table('users_roles')->exists()) {
            return;
        }
        DB::table('users_roles')->insert([
            [
                'role_id' => 1,
                'role_name' => 'Administrator',
                'role_description' => 'Administrator - It cannot be deleted',
                'disable_delete' => 1,
                'record_status_code' => 'update',
                'record_insert_user_id' => 1,
                'record_insert_date' => now(),
                'record_update_user_id' => null,
                'record_update_date' => null,
                'record_delete_user_id' => null,
                'record_delete_date' => null,
            ],
        ]);
    }

    private function seedUsersRolesModules(): void
    {
        if (DB::table('users_roles_modules')->exists()) {
            return;
        }
        DB::table('users_roles_modules')->insert([
            ['module_id' => 1, 'module_parent_id' => null, 'module_name' => 'customers', 'position' => 10],
            ['module_id' => 2, 'module_parent_id' => null, 'module_name' => 'projects', 'position' => 20],
            ['module_id' => 3, 'module_parent_id' => null, 'module_name' => 'tasks', 'position' => 30],
            ['module_id' => 4, 'module_parent_id' => 3, 'module_name' => 'tasks_tab_1', 'position' => 10],
        ]);
    }

    private function seedUsers(): void
    {
        if (DB::table('users')->where('email', 'atuleader@gmail.com')->exists()) {
            return;
        }
        $password = Hash::make('password');
        DB::table('users')->insert([
            [
                'email' => 'atuleader@gmail.com',
                'password' => $password,
                'displayname' => 'Daniel Deftu',
                'phone' => null,
                'role_id' => 1,
                'language_id' => 1,
                'img' => null,
                'url' => 'daniel-deftu',
                'lastlogin' => null,
                'active' => 1,
                'record_status_code' => null,
                'record_insert_user_id' => null,
                'record_insert_date' => null,
                'record_update_user_id' => null,
                'record_update_date' => null,
                'record_delete_user_id' => null,
                'record_delete_date' => null,
                'record_lastlogin_date' => null,
            ],
        ]);
    }

    private function seedUsersPermissions(): void
    {
        if (DB::table('users_permissions')->exists()) {
            return;
        }
        DB::table('users_permissions')->insert([
            ['permission_id' => 1, 'role_id' => 1, 'module_id' => 1, 'permission_r' => 1, 'permission_w' => 1, 'permission_a' => 1, 'permission_d' => 1],
            ['permission_id' => 2, 'role_id' => 1, 'module_id' => 2, 'permission_r' => 1, 'permission_w' => 1, 'permission_a' => 1, 'permission_d' => 1],
            ['permission_id' => 3, 'role_id' => 1, 'module_id' => 3, 'permission_r' => 1, 'permission_w' => 1, 'permission_a' => 1, 'permission_d' => 1],
            ['permission_id' => 4, 'role_id' => 1, 'module_id' => 4, 'permission_r' => 1, 'permission_w' => 1, 'permission_a' => 1, 'permission_d' => 1],
        ]);
    }
}
