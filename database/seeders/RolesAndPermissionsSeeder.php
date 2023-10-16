<?php

namespace Database\Seeders;

use App\Models\Permissions;
use App\Models\Roles;
use App\Models\RolesPermissions;
use App\Models\Tickets;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Editor User',
            'email' => 'editor-ts@yopmail.com',
            'password' => Hash::make('12341234'),
        ]);

        $viewer = User::create([
            'name' => 'Viewer User',
            'email' => 'viewer-ts@yopmail.com',
            'password' => Hash::make('12341234'),
        ]);

        Tickets::create([
            'title' => 'T1',
            'description' => 'T1 Desc',
            'status' => 'Pending',
            'user_id' => 2
        ]);

        Tickets::create([
            'title' => 'T2',
            'description' => 'T2 Desc',
            'status' => 'Pending',
            'user_id' => 2
        ]);

        Roles::create(['name' => 'editor']);
        Roles::create(['name' => 'viewer']);

        Permissions::create(['name' => 'assign tickets']);
        Permissions::create(['name' => 'view tickets']);

        RolesPermissions::create(['user_id' => 1, 'role_id' => 1, 'permission_id' => 1]);
        RolesPermissions::create(['user_id' => 2, 'role_id' => 2, 'permission_id' => 2]);
    }
}
