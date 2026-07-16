<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;

class RejectedVendorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create('id_ID');

        $rejectedVendors = [
            [
                'nama' => 'Berkah Rasa Catering',
                'kategori' => 'Catering',
                'catatan' => 'Dokumen legalitas profil perusahaan dan NIB tidak dilampirkan. Selain itu, draf MoU penawaran harga belum mencantumkan kesepakatan persentase marketing fee yang sesuai dengan standar margin House of Liza. Silakan ajukan ulang dengan dokumen yang lengkap.',
            ],
            [
                'nama' => 'Aesthetic Florist & Decor',
                'kategori' => 'Dekorasi',
                'catatan' => 'Kualitas dan detail finishing pada portofolio dekorasi pelaminan yang dilampirkan belum memenuhi standar estetika premium House of Liza. Gaya dekorasi tidak sejalan dengan visi modern-tradisional yang menjadi target pasar kita.',
            ],
            [
                'nama' => 'LensStory Pictures',
                'kategori' => 'Dokumentasi',
                'catatan' => 'PIC vendor sangat sulit dihubungi dan slow response saat tim Strategic Partnership meminta klarifikasi detail paket. Komunikasi yang tidak responsif berisiko tinggi mengganggu kelancaran koordinasi saat Technical Meeting (TM) maupun eksekusi di hari H.',
            ],
            [
                'nama' => 'Flawless by Rina',
                'kategori' => 'Makeup Artist',
                'catatan' => 'Harga paket dasar yang ditawarkan terlalu tinggi dan berada jauh di atas harga rata-rata mitra MUA kita saat ini. Paket ini akan sulit direkomendasikan karena tidak sesuai dengan mayoritas batasan budget klien all-in kita.',
            ],
            [
                'nama' => 'Graha Pesona Melati',
                'kategori' => 'Venue',
                'catatan' => 'Lokasi venue berada di luar jangkauan radius operasional utama kita. Berdasarkan peninjauan tim lapangan, akses loading dock sangat sempit sehingga akan sangat menghambat kelancaran bongkar muat logistik oleh tim Backroom.',
            ],
        ];

        foreach ($rejectedVendors as $rv) {
            Vendor::create([
                'nama_vendor' => $rv['nama'],
                'kategori_jasa' => $rv['kategori'],
                'no_telepon' => $faker->phoneNumber(),
                'email' => strtolower(str_replace(' ', '', $rv['nama'])).'@example.com',
                'alamat' => $faker->address(),
                'harga' => $faker->randomElement([15000000, 20000000, 25000000, 30000000]),
                'link_portofolio' => 'https://drive.google.com/file/d/1qXURNFxvlkijUa_Kbb4ODFeJq7D_iMTE/view',
                'rating' => 0.0,
                'status_aktif' => false,
                'status_approval' => 'Ditolak',
                'catatan_tolak' => $rv['catatan'],
                'alasan_penolakan' => $rv['catatan'],
                'tanggal_ditolak' => now(),
                'is_new_vendor' => true,
            ]);
        }
    }
}
