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

        // 1. MEMBUAT ROLES
        $rolePartnership = Role::create(['name' => 'partnership']);
        $roleAdminCS = Role::create(['name' => 'admin_cs']);
        $roleOps = Role::create(['name' => 'manager_operasional']);
        $roleFinance = Role::create(['name' => 'finance']);
        $roleManager = Role::create(['name' => 'manager_comercial']);

        // 2. MEMBUAT USERS & MENYAMBUNGKAN KE ROLE

        // Akun Divisi Partnership
        $partnership = User::create([
            'name' => 'Divisi Partnership',
            'email' => 'partnership@vms.test',
            'password' => Hash::make('password123'),
        ]);
        $partnership->assignRole($rolePartnership);

        // Akun Admin CS
        $adminCS = User::create([
            'name' => 'Admin CS',
            'email' => 'cs@vms.test',
            'password' => Hash::make('password123'),
        ]);
        $adminCS->assignRole($roleAdminCS);

        // Akun Manager Operasional
        $managerOps = User::create([
            'name' => 'Manager Operasional',
            'email' => 'ops@vms.test',
            'password' => Hash::make('password123'),
        ]);
        $managerOps->assignRole($roleOps);

        // Akun Finance
        $finance = User::create([
            'name' => 'Tim Finance',
            'email' => 'finance@vms.test',
            'password' => Hash::make('password123'),
        ]);
        $finance->assignRole($roleFinance);

        // Akun Manager Comercial & Financial
        $managerComercial = User::create([
            'name' => 'Manager Comercial',
            'email' => 'manager@vms.test',
            'password' => Hash::make('password123'),
        ]);
        $managerComercial->assignRole($roleManager);
    }
}
