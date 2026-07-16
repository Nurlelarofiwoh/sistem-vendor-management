<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder Role dan User kita di sini
        $this->call([
            RoleAndUserSeeder::class,
            DummyDataSeeder::class,   // Memasukkan data vendor & klien pernikahan
            RejectedVendorSeeder::class,
            ExperiencedVendorSeeder::class, // Vendor berpengalaman dengan rating variatif
        ]);
    }
}
