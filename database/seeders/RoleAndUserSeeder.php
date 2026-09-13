<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache Spatie Permission agar tidak error saat di-seed ulang
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. MEMBUAT ROLES (idempotent)
        $rolePartnership = Role::firstOrCreate(['name' => 'partnership']);
        $roleAdminCS = Role::firstOrCreate(['name' => 'admin_cs']);
        $roleOps = Role::firstOrCreate(['name' => 'manager_operasional']);
        $roleFinance = Role::firstOrCreate(['name' => 'finance']);
        $roleManager = Role::firstOrCreate(['name' => 'manager_comercial']);

        // 2. MEMBUAT USERS & MENYAMBUNGKAN KE ROLE (idempotent)

        // Akun Divisi Partnership
        $partnership = User::firstOrCreate(
            ['email' => 'partnership@vms.test'],
            ['name' => 'Divisi Partnership', 'password' => Hash::make('password123')]
        );
        $partnership->syncRoles($rolePartnership);

        // Akun Admin CS
        $adminCS = User::firstOrCreate(
            ['email' => 'cs@vms.test'],
            ['name' => 'Admin CS', 'password' => Hash::make('password123')]
        );
        $adminCS->syncRoles($roleAdminCS);

        // Akun Manager Operasional
        $managerOps = User::firstOrCreate(
            ['email' => 'ops@vms.test'],
            ['name' => 'Manager Operasional', 'password' => Hash::make('password123')]
        );
        $managerOps->syncRoles($roleOps);

        // Akun Finance
        $finance = User::firstOrCreate(
            ['email' => 'finance@vms.test'],
            ['name' => 'Tim Finance', 'password' => Hash::make('password123')]
        );
        $finance->syncRoles($roleFinance);

        // Akun Manager Comercial & Financial
        $managerComercial = User::firstOrCreate(
            ['email' => 'manager@vms.test'],
            ['name' => 'Manager Comercial', 'password' => Hash::make('password123')]
        );
        $managerComercial->syncRoles($roleManager);
    }
}
