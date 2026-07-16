<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExperiencedVendorSeeder extends Seeder
{
    /**
     * Daftar email unik untuk vendor berpengalaman.
     * Gunakan updateOrCreate agar data selalu sinkron saat seeder dijalankan ulang.
     */
    public function run(): void
    {
        // Hapus dulu jika ada dari seeder sebelumnya agar tidak ada duplikasi
        $emailList = [
            'aura.dekor@nusantara.id',
            'harmoni.fotovideo@gmail.com',
            'kasih.katering@premium.co.id',
            'seraphina.bridal@boutique.id',
            'melodia.band@entertainment.id',
            'flores.wo@garden.id',
            'bintang.makeup@studio.id',
            'persada.sound@pro.id',
            'gemilang.tenda@venue.id',
            'andika.transport@vip.id',
            'cahaya.fireworks@show.id',
            'mentari.bakery@cake.id',
        ];
        Vendor::whereIn('email', $emailList)->delete();

        $vendors = [
            // ===========================
            // VENDOR TOP PERFORMER (Rating 4.6 - 4.9)
            // ===========================
            [
                'nama_vendor'           => 'Aura Dekorasi Nusantara',
                'kategori_jasa'         => 'Dekorasi & Pelaminan',
                'email'                 => 'aura.dekor@nusantara.id',
                'no_telepon'            => '081234100001',
                'alamat'                => 'Jl. Raya Sudirman No. 45, Jakarta Selatan',
                'harga'                 => 18000000,
                'rating'                => 4.9,
                'total_review'          => 87,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => 'https://instagram.com/auradekor_nusantara',
                'tanggal_kontrak_habis' => Carbon::now()->addMonths(14),
                'created_at'            => Carbon::now()->subMonths(24),
                'updated_at'            => Carbon::now()->subDays(3),
            ],
            [
                'nama_vendor'           => 'Harmoni Foto & Video Wedding',
                'kategori_jasa'         => 'Foto & Videografi',
                'email'                 => 'harmoni.fotovideo@gmail.com',
                'no_telepon'            => '082234100002',
                'alamat'                => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
                'harga'                 => 9500000,
                'rating'                => 4.8,
                'total_review'          => 63,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => 'https://harmoni-fotovideo.com/portofolio',
                'tanggal_kontrak_habis' => Carbon::now()->addMonths(18),
                'created_at'            => Carbon::now()->subMonths(18),
                'updated_at'            => Carbon::now()->subDays(7),
            ],
            [
                'nama_vendor'           => 'Kasih Katering Premium',
                'kategori_jasa'         => 'Katering & Konsumsi',
                'email'                 => 'kasih.katering@premium.co.id',
                'no_telepon'            => '083334100003',
                'alamat'                => 'Jl. Puri Indah Raya No. 8, Jakarta Barat',
                'harga'                 => 75000,
                'rating'                => 4.7,
                'total_review'          => 142,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => 'https://kasihkatering.id/menu',
                'tanggal_kontrak_habis' => Carbon::now()->addMonths(10),
                'created_at'            => Carbon::now()->subMonths(30),
                'updated_at'            => Carbon::now()->subDays(1),
            ],
            [
                'nama_vendor'           => 'Seraphina Bridal Boutique',
                'kategori_jasa'         => 'Gaun & Busana Pengantin',
                'email'                 => 'seraphina.bridal@boutique.id',
                'no_telepon'            => '085534100004',
                'alamat'                => 'Jl. Wahid Hasyim No. 77, Jakarta Pusat',
                'harga'                 => 12000000,
                'rating'                => 4.6,
                'total_review'          => 51,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => 'https://seraphina-bridal.com/koleksi',
                'tanggal_kontrak_habis' => Carbon::now()->addMonths(22),
                'created_at'            => Carbon::now()->subMonths(15),
                'updated_at'            => Carbon::now()->subDays(5),
            ],
            // ===========================
            // VENDOR BAIK (Rating 3.5 - 4.4)
            // ===========================
            [
                'nama_vendor'           => 'Melodia Entertainment Band',
                'kategori_jasa'         => 'Musik & Hiburan',
                'email'                 => 'melodia.band@entertainment.id',
                'no_telepon'            => '087634100005',
                'alamat'                => 'Jl. Fatmawati No. 33, Jakarta Selatan',
                'harga'                 => 8000000,
                'rating'                => 4.2,
                'total_review'          => 39,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => 'https://youtube.com/@melodia_band',
                'tanggal_kontrak_habis' => Carbon::now()->addMonths(8),
                'created_at'            => Carbon::now()->subMonths(12),
                'updated_at'            => Carbon::now()->subDays(10),
            ],
            [
                'nama_vendor'           => 'Flores Garden Wedding Organizer',
                'kategori_jasa'         => 'Wedding Organizer',
                'email'                 => 'flores.wo@garden.id',
                'no_telepon'            => '088834100006',
                'alamat'                => 'Jl. Teuku Cik Ditiro No. 15, Bandung',
                'harga'                 => 25000000,
                'rating'                => 4.0,
                'total_review'          => 28,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => 'https://floresweddingorganizer.com',
                'tanggal_kontrak_habis' => Carbon::now()->addDays(25),
                'created_at'            => Carbon::now()->subMonths(9),
                'updated_at'            => Carbon::now()->subDays(2),
            ],
            [
                'nama_vendor'           => 'Bintang Rias & Makeup Studio',
                'kategori_jasa'         => 'Makeup & Rias Pengantin',
                'email'                 => 'bintang.makeup@studio.id',
                'no_telepon'            => '089934100007',
                'alamat'                => 'Jl. Pangeran Jayakarta No. 22, Jakarta',
                'harga'                 => 4500000,
                'rating'                => 3.8,
                'total_review'          => 44,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => 'https://instagram.com/bintang_makeup_studio',
                'tanggal_kontrak_habis' => Carbon::now()->addMonths(6),
                'created_at'            => Carbon::now()->subMonths(11),
                'updated_at'            => Carbon::now()->subDays(4),
            ],
            [
                'nama_vendor'           => 'Persada Sound System Pro',
                'kategori_jasa'         => 'Sound System & Lighting',
                'email'                 => 'persada.sound@pro.id',
                'no_telepon'            => '081134100008',
                'alamat'                => 'Jl. Manggarai Raya No. 5, Jakarta Selatan',
                'harga'                 => 7500000,
                'rating'                => 3.5,
                'total_review'          => 22,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => 'https://persada-sound.co.id',
                'tanggal_kontrak_habis' => Carbon::now()->addMonths(4),
                'created_at'            => Carbon::now()->subMonths(7),
                'updated_at'            => Carbon::now()->subDays(6),
            ],
            // ===========================
            // VENDOR PERLU PERHATIAN (Rating 2.0 - 3.4)
            // ===========================
            [
                'nama_vendor'           => 'Gemilang Tenda & Venue Outdoor',
                'kategori_jasa'         => 'Tenda & Venue',
                'email'                 => 'gemilang.tenda@venue.id',
                'no_telepon'            => '082234100009',
                'alamat'                => 'Jl. Lenteng Agung No. 18, Jakarta Selatan',
                'harga'                 => 15000000,
                'rating'                => 3.0,
                'total_review'          => 17,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => 'https://gemilang-tenda.id',
                'tanggal_kontrak_habis' => Carbon::now()->addMonths(3),
                'created_at'            => Carbon::now()->subMonths(6),
                'updated_at'            => Carbon::now()->subDays(8),
            ],
            [
                'nama_vendor'           => 'Andika Transport & Shuttle VIP',
                'kategori_jasa'         => 'Transportasi & Shuttle',
                'email'                 => 'andika.transport@vip.id',
                'no_telepon'            => '083334100010',
                'alamat'                => 'Jl. Ciledug Raya No. 44, Tangerang',
                'harga'                 => 3500000,
                'rating'                => 2.5,
                'total_review'          => 12,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => null,
                'tanggal_kontrak_habis' => Carbon::now()->addMonths(5),
                'created_at'            => Carbon::now()->subMonths(5),
                'updated_at'            => Carbon::now()->subDays(15),
            ],
            // ===========================
            // VENDOR BERMASALAH / PERLU TINDAK LANJUT (Rating < 2.0)
            // ===========================
            [
                'nama_vendor'           => 'Cahaya Malam Fireworks Show',
                'kategori_jasa'         => 'Fireworks & Kembang Api',
                'email'                 => 'cahaya.fireworks@show.id',
                'no_telepon'            => '085534100011',
                'alamat'                => 'Jl. Daan Mogot No. 56, Jakarta Barat',
                'harga'                 => 6000000,
                'rating'                => 1.8,
                'total_review'          => 9,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => 'https://cahayafireworks.id',
                'tanggal_kontrak_habis' => Carbon::now()->addMonths(2),
                'created_at'            => Carbon::now()->subMonths(8),
                'updated_at'            => Carbon::now()->subDays(20),
            ],
            [
                'nama_vendor'           => 'Mentari Bakery & Wedding Cake',
                'kategori_jasa'         => 'Wedding Cake & Kue',
                'email'                 => 'mentari.bakery@cake.id',
                'no_telepon'            => '087634100012',
                'alamat'                => 'Jl. Pasar Minggu No. 99, Jakarta Selatan',
                'harga'                 => 2800000,
                'rating'                => 1.5,
                'total_review'          => 14,
                'status_aktif'          => true,
                'status_approval'       => 'Approved',
                'is_new_vendor'         => false,
                'link_portofolio'       => 'https://instagram.com/mentari_weddingcake',
                'tanggal_kontrak_habis' => Carbon::now()->addMonths(1),
                'created_at'            => Carbon::now()->subMonths(10),
                'updated_at'            => Carbon::now()->subDays(12),
            ],
        ];

        foreach ($vendors as $data) {
            Vendor::create($data);
        }

        $this->command->info('✅ ExperiencedVendorSeeder: ' . count($vendors) . ' vendor berpengalaman berhasil ditambahkan.');
    }
}
