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
            VendorSeeder::class,            // Memasukkan 200+ vendor Jabodetabek dari database vendor utama
            ExperiencedVendorSeeder::class, // Vendor berpengalaman dengan rating variatif
            RejectedVendorSeeder::class,    // Vendor ditolak
            DummyDataSeeder::class,         // Memasukkan data klien, event/proyek berjalan, & tagihan komisi
        ]);
    }
}
