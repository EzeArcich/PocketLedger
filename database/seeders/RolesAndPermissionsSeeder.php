<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            'expenses.view',
            'expenses.create',
            'expenses.update',
            'expenses.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        $adminRole->syncPermissions(Permission::all());

        $userRole->syncPermissions([
            'expenses.view',
            'expenses.create',
            'expenses.update',
            'expenses.delete',
        ]);

        $adminUser = User::where('email', 'admin@pocketledger.test')->first();
        $regularUser = User::where('email', 'user@pocketledger.test')->first();

        if ($adminUser) {
            $adminUser->assignRole($adminRole);
        }

        if ($regularUser) {
            $regularUser->assignRole($userRole);
        }
    }
}
