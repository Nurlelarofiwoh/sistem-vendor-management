<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class DummyVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar kategori diambil secara dinamis dari data yang sudah ada, atau manual jika kosong
        $kategoriTersedia = Vendor::select('kategori_jasa')->distinct()->pluck('kategori_jasa')->toArray();

        if (empty($kategoriTersedia)) {
            $kategoriTersedia = [
                'Venue', 'Catering', 'Dekorasi', 'Dokumentasi', 'Sound System',
                'Entertainment', 'Attire', 'Makeup Artist',
            ];
        }

        $dummyVendors = [];
        $counter = 1;

        foreach ($kategoriTersedia as $kategori) {

            // --- TIER 1 (Premium, Budget > 150M) ---

            // 1. Tier 1 - Proven
            $dummyVendors[] = [
                'nama_vendor' => "Premium Proven {$kategori} ".$counter++,
                'kategori_jasa' => $kategori,
                'email' => "premium.proven.{$counter}@example.com",
                'no_telepon' => '08110000'.rand(1000, 9999),
                'alamat' => 'Jakarta Pusat',
                'harga' => 160000000, // 160 Juta

                'rating' => 4.8,
                'is_new_vendor' => false,
                'status_aktif' => true,
                'status_approval' => 'Approved',
                'skor_finansial' => 3, // Aman
                'status_kemitraan' => 'Active',
            ];

            // 2. Tier 1 - Challenger
            $dummyVendors[] = [
                'nama_vendor' => "Premium Challenger {$kategori} ".$counter++,
                'kategori_jasa' => $kategori,
                'email' => "premium.challenger.{$counter}@example.com",
                'no_telepon' => '08110000'.rand(1000, 9999),
                'alamat' => 'Jakarta Selatan',
                'harga' => 155000000, // 155 Juta (Termurah di Tier 1 tanpa rating)

                'rating' => null, // Belum ada rating
                'is_new_vendor' => true,
                'status_aktif' => true,
                'status_approval' => 'Approved',
                'skor_finansial' => 3,
                'status_kemitraan' => 'Active',
            ];

            // --- TIER 2 (Regular, Budget 25M - 150M) ---

            // 3. Tier 2 - Proven
            $dummyVendors[] = [
                'nama_vendor' => "Regular Proven {$kategori} ".$counter++,
                'kategori_jasa' => $kategori,
                'email' => "regular.proven.{$counter}@example.com",
                'no_telepon' => '08120000'.rand(1000, 9999),
                'alamat' => 'Jakarta Barat',
                'harga' => 100000000, // 100 Juta

                'rating' => 4.5,
                'is_new_vendor' => false,
                'status_aktif' => true,
                'status_approval' => 'Approved',
                'skor_finansial' => 3,
                'status_kemitraan' => 'Active',
            ];

            // 4. Tier 2 - Challenger
            $dummyVendors[] = [
                'nama_vendor' => "Regular Challenger {$kategori} ".$counter++,
                'kategori_jasa' => $kategori,
                'email' => "regular.challenger.{$counter}@example.com",
                'no_telepon' => '08120000'.rand(1000, 9999),
                'alamat' => 'Tangerang',
                'harga' => 95000000, // 95 Juta (Termurah di Tier 2 tanpa rating)

                'rating' => null, // Belum ada rating
                'is_new_vendor' => true,
                'status_aktif' => true,
                'status_approval' => 'Approved',
                'skor_finansial' => 3,
                'status_kemitraan' => 'Active',
            ];

            // --- TIER 3 (Standard, Budget < 25M) ---

            // 5. Tier 3 - Proven
            $dummyVendors[] = [
                'nama_vendor' => "Standard Proven {$kategori} ".$counter++,
                'kategori_jasa' => $kategori,
                'email' => "standard.proven.{$counter}@example.com",
                'no_telepon' => '08130000'.rand(1000, 9999),
                'alamat' => 'Depok',
                'harga' => 200000000, // Wait, it needs to be 20 Juta!

                'rating' => 4.2,
                'is_new_vendor' => false,
                'status_aktif' => true,
                'status_approval' => 'Approved',
                'skor_finansial' => 3,
                'status_kemitraan' => 'Active',
            ];

            // 6. Tier 3 - Challenger
            $dummyVendors[] = [
                'nama_vendor' => "Standard Challenger {$kategori} ".$counter++,
                'kategori_jasa' => $kategori,
                'email' => "standard.challenger.{$counter}@example.com",
                'no_telepon' => '08130000'.rand(1000, 9999),
                'alamat' => 'Bekasi',
                'harga' => 150000000, // Wait, it needs to be 15 Juta!

                'rating' => null, // Belum ada rating
                'is_new_vendor' => true,
                'status_aktif' => true,
                'status_approval' => 'Approved',
                'skor_finansial' => 3,
                'status_kemitraan' => 'Active',
            ];
        }

        // Insert ke database (karena unique email constraint, kita gunakan insertOrIgnore)
        foreach ($dummyVendors as $vendor) {
            // Fix tier 3 harga values (I mistakenly put 200 Juta and 150 Juta in array structure above)
            if (str_contains($vendor['nama_vendor'], 'Standard Proven')) {
                $vendor['harga'] = 2000000; // 2 Juta (wait, 20 Juta is 20,000,000)
                $vendor['harga'] = 20000000; // 20 Juta
            }
            if (str_contains($vendor['nama_vendor'], 'Standard Challenger')) {
                $vendor['harga'] = 15000000; // 15 Juta
            }

            Vendor::firstOrCreate(
                ['email' => $vendor['email']],
                $vendor
            );
        }

        $this->command->info('Berhasil menambahkan '.count($dummyVendors).' dummy vendor untuk semua kategori & tier!');
    }
}
