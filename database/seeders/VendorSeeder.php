<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan data vendor
        Vendor::query()->delete();

        // 2. Dummy Spesifikasi JSON berdasarkan Kategori
        $spesifikasi = [
            'Venue' => ['kapasitas' => '1000 Pax', 'tipe_venue' => 'Indoor & Outdoor', 'fasilitas_include' => 'MC, Sound System Standard'],
            'Catering' => ['jenis_penyajian' => 'Prasmanan & Stall', 'sertifikasi' => 'Halal', 'test_food' => 'Gratis'],
            'Dekorasi' => ['tema' => 'Modern Rustic & Classic Traditional', 'jenis_bunga' => 'Mix', 'ukuran_panggung' => '10 x 4 Meter'],
            'Dokumentasi' => ['jumlah_kru' => '4', 'durasi_liputan' => '8 Jam Kerja', 'output' => 'Album Kolase & Cinematic Video'],
            'Sound System' => ['total_daya' => '10.000 Watt', 'genset' => 'Ya (Free)', 'kelengkapan' => 'Mic Wireless, Mixer Digital'],
            'Entertainment' => ['format' => 'Grup Band & Akustik', 'jumlah_personil' => '5', 'durasi' => '2 x 45 Menit'],
            'Attire' => ['gaya_busana' => 'Tradisional & Modern International', 'kuota_fitting' => '3 Kali Fitting'],
            'Makeup Artist' => ['spesialisasi_look' => 'Flawless & Traditional Adat', 'brand_kosmetik' => 'Premium Brands', 'layanan_include' => 'Hairdo/Hijabdo'],
        ];

        // 3. Mapping Link Portofolio Berdasarkan Kategori Jasa
        $portofolioLinks = [
            'Venue' => 'https://drive.google.com/file/d/1ZA8DOv8qXRAkiNP8uZfANsqS-3ELtVYg/view?usp=sharing',
            'Attire' => 'https://drive.google.com/file/d/16IcNGbIvnw7piSIw6s2BlBSN1bO3rEty/view?usp=sharing',
            'Makeup Artist' => 'https://drive.google.com/file/d/1d6BCLkISf453qX8SNY7d7MSWU__YYBKu/view?usp=drive_link',
            'Catering' => 'https://drive.google.com/file/d/1qXURNFxvlkijUa_Kbb4ODFeJq7D_iMTE/view?usp=drive_link',
            'Dekorasi' => 'https://drive.google.com/file/d/1CmySJDs8fqadG31Fmq_YDaiI97dQri41/view?usp=drive_link',
            'Sound System' => 'https://drive.google.com/file/d/1elUmr9Mmfn9Q4ITedkm7hXEZVpO7IBNm/view?usp=drive_link',
            'Entertainment' => 'https://drive.google.com/file/d/15V1799K_5libhgQ29ezjsrfpXJCw0OXr/view?usp=drive_link',
            'Dokumentasi' => 'https://drive.google.com/file/d/1NFkC2J21BoVr9pj3yTW35BFYGdXcUjVM/view?usp=drive_link',
        ];

        // 4. Daftar 200 Vendor — 10 kelompok wilayah se-Jabodetabek (masing-masing 20 vendor)
        $vendors = [

            // ═══════════════════════════════════════════════
            // KELOMPOK 1: TANGERANG SELATAN (20 Vendor)
            // ═══════════════════════════════════════════════
            ['nama' => 'Swasana Venue',    'kat' => 'Venue',         'hp' => '08111000001', 'alamat' => 'Pamulang, Tangerang Selatan'],
            ['nama' => 'Millenial Space',  'kat' => 'Venue',         'hp' => '08111000002', 'alamat' => 'Bintaro, Tangerang Selatan'],
            ['nama' => 'Chakra Venue',     'kat' => 'Venue',         'hp' => '08111000003', 'alamat' => 'BSD, Tangerang Selatan'],
            ['nama' => 'Five Star',        'kat' => 'Catering',      'hp' => '08111000004', 'alamat' => 'Ciputat, Tangerang Selatan'],
            ['nama' => 'Oliv WO',          'kat' => 'Catering',      'hp' => '08111000005', 'alamat' => 'BSD, Tangerang Selatan'],
            ['nama' => 'Puspita Sawargi',  'kat' => 'Catering',      'hp' => '08111000006', 'alamat' => 'Pondok Aren, Tangerang Selatan'],
            ['nama' => 'Perniq 21',        'kat' => 'Dekorasi',      'hp' => '08111000007', 'alamat' => 'Pamulang, Tangerang Selatan'],
            ['nama' => 'Adhyakti Decor',   'kat' => 'Dekorasi',      'hp' => '08111000008', 'alamat' => 'Bintaro, Tangerang Selatan'],
            ['nama' => 'Vica Decor',       'kat' => 'Dekorasi',      'hp' => '08111000009', 'alamat' => 'Ciputat, Tangerang Selatan'],
            ['nama' => 'Saksee Photo',     'kat' => 'Dokumentasi',   'hp' => '08111000010', 'alamat' => 'BSD, Tangerang Selatan'],
            ['nama' => 'Dibalik Layar',    'kat' => 'Dokumentasi',   'hp' => '08111000011', 'alamat' => 'Pamulang, Tangerang Selatan'],
            ['nama' => 'Syahira Sound',    'kat' => 'Sound System',  'hp' => '08111000012', 'alamat' => 'Bintaro, Tangerang Selatan'],
            ['nama' => 'DSS Sound',        'kat' => 'Sound System',  'hp' => '08111000013', 'alamat' => 'Pondok Aren, Tangerang Selatan'],
            ['nama' => 'BZ Entertainment', 'kat' => 'Entertainment', 'hp' => '08111000014', 'alamat' => 'Ciputat, Tangerang Selatan'],
            ['nama' => 'Akustik Senja',    'kat' => 'Entertainment', 'hp' => '08111000015', 'alamat' => 'BSD, Tangerang Selatan'],
            ['nama' => 'Serenity Attire',  'kat' => 'Attire',        'hp' => '08111000016', 'alamat' => 'Pamulang, Tangerang Selatan'],
            ['nama' => 'Bantu Manten',     'kat' => 'Attire',        'hp' => '08111000017', 'alamat' => 'Bintaro, Tangerang Selatan'],
            ['nama' => 'Glowy MUA',        'kat' => 'Makeup Artist', 'hp' => '08111000018', 'alamat' => 'BSD, Tangerang Selatan'],
            ['nama' => 'Amarta MUA',       'kat' => 'Makeup Artist', 'hp' => '08111000019', 'alamat' => 'Ciputat, Tangerang Selatan'],
            ['nama' => 'Flawless Sarah',   'kat' => 'Makeup Artist', 'hp' => '08111000020', 'alamat' => 'Pondok Aren, Tangerang Selatan'],

            // ═══════════════════════════════════════════════
            // KELOMPOK 2: TANGERANG (20 Vendor)
            // ═══════════════════════════════════════════════
            ['nama' => 'The Agathon',      'kat' => 'Venue',         'hp' => '08222000001', 'alamat' => 'Gading Serpong, Tangerang'],
            ['nama' => 'Grand Soll',       'kat' => 'Venue',         'hp' => '08222000002', 'alamat' => 'Karawaci, Tangerang'],
            ['nama' => 'Spring Club',      'kat' => 'Venue',         'hp' => '08222000003', 'alamat' => 'Gading Serpong, Tangerang'],
            ['nama' => 'Duta Catering',    'kat' => 'Catering',      'hp' => '08222000004', 'alamat' => 'Cikokol, Tangerang'],
            ['nama' => 'Mutiara Rasa',     'kat' => 'Catering',      'hp' => '08222000005', 'alamat' => 'Batuceper, Tangerang'],
            ['nama' => 'Berkah Catering',  'kat' => 'Catering',      'hp' => '08222000006', 'alamat' => 'Karawaci, Tangerang'],
            ['nama' => 'Rich Art Decor',   'kat' => 'Dekorasi',      'hp' => '08222000007', 'alamat' => 'Gading Serpong, Tangerang'],
            ['nama' => 'Azalia Decor',     'kat' => 'Dekorasi',      'hp' => '08222000008', 'alamat' => 'Cikokol, Tangerang'],
            ['nama' => 'Royal Decor',      'kat' => 'Dekorasi',      'hp' => '08222000009', 'alamat' => 'Batuceper, Tangerang'],
            ['nama' => 'Imagenic',         'kat' => 'Dokumentasi',   'hp' => '08222000010', 'alamat' => 'Karawaci, Tangerang'],
            ['nama' => 'The Potomoto',     'kat' => 'Dokumentasi',   'hp' => '08222000011', 'alamat' => 'Gading Serpong, Tangerang'],
            ['nama' => 'Sinar Mutiara',    'kat' => 'Sound System',  'hp' => '08222000012', 'alamat' => 'Cikokol, Tangerang'],
            ['nama' => 'Bintang Sound',    'kat' => 'Sound System',  'hp' => '08222000013', 'alamat' => 'Karawaci, Tangerang'],
            ['nama' => 'Deo Band',         'kat' => 'Entertainment', 'hp' => '08222000014', 'alamat' => 'Batuceper, Tangerang'],
            ['nama' => 'Visi Musik',       'kat' => 'Entertainment', 'hp' => '08222000015', 'alamat' => 'Gading Serpong, Tangerang'],
            ['nama' => 'Ivory Bridal',     'kat' => 'Attire',        'hp' => '08222000016', 'alamat' => 'Karawaci, Tangerang'],
            ['nama' => 'Alissha Bride',    'kat' => 'Attire',        'hp' => '08222000017', 'alamat' => 'Cikokol, Tangerang'],
            ['nama' => 'Dinda Makeup',     'kat' => 'Makeup Artist', 'hp' => '08222000018', 'alamat' => 'Batuceper, Tangerang'],
            ['nama' => 'Ayu MUA',          'kat' => 'Makeup Artist', 'hp' => '08222000019', 'alamat' => 'Gading Serpong, Tangerang'],
            ['nama' => 'Mawar Makeup',     'kat' => 'Makeup Artist', 'hp' => '08222000020', 'alamat' => 'Karawaci, Tangerang'],

            // ═══════════════════════════════════════════════
            // KELOMPOK 3: JAKARTA SELATAN (20 Vendor)
            // ═══════════════════════════════════════════════
            ['nama' => 'BRP Smesco',       'kat' => 'Venue',         'hp' => '08333000001', 'alamat' => 'Pancoran, Jakarta Selatan'],
            ['nama' => 'Balai Sudirman',   'kat' => 'Venue',         'hp' => '08333000002', 'alamat' => 'Tebet, Jakarta Selatan'],
            ['nama' => 'Patra Jasa',       'kat' => 'Venue',         'hp' => '08333000003', 'alamat' => 'Kuningan, Jakarta Selatan'],
            ['nama' => 'Alfabet Catering', 'kat' => 'Catering',      'hp' => '08333000004', 'alamat' => 'Kemang, Jakarta Selatan'],
            ['nama' => 'Sonokembang',      'kat' => 'Catering',      'hp' => '08333000005', 'alamat' => 'Tebet, Jakarta Selatan'],
            ['nama' => 'Akasya Catering',  'kat' => 'Catering',      'hp' => '08333000006', 'alamat' => 'Pondok Indah, Jakarta Selatan'],
            ['nama' => 'Rumah Kampung',    'kat' => 'Dekorasi',      'hp' => '08333000007', 'alamat' => 'Pancoran, Jakarta Selatan'],
            ['nama' => 'Ebimo Decor',      'kat' => 'Dekorasi',      'hp' => '08333000008', 'alamat' => 'Kuningan, Jakarta Selatan'],
            ['nama' => 'Lotus Design',     'kat' => 'Dekorasi',      'hp' => '08333000009', 'alamat' => 'Kemang, Jakarta Selatan'],
            ['nama' => 'Alienco Photo',    'kat' => 'Dokumentasi',   'hp' => '08333000010', 'alamat' => 'Tebet, Jakarta Selatan'],
            ['nama' => 'King Foto',        'kat' => 'Dokumentasi',   'hp' => '08333000011', 'alamat' => 'Blok M, Jakarta Selatan'],
            ['nama' => 'Sumber Ria',       'kat' => 'Sound System',  'hp' => '08333000012', 'alamat' => 'Pancoran, Jakarta Selatan'],
            ['nama' => 'Pro Sound',        'kat' => 'Sound System',  'hp' => '08333000013', 'alamat' => 'Pondok Indah, Jakarta Selatan'],
            ['nama' => 'Taman Musik',      'kat' => 'Entertainment', 'hp' => '08333000014', 'alamat' => 'Kemang, Jakarta Selatan'],
            ['nama' => 'Jikustik Band',    'kat' => 'Entertainment', 'hp' => '08333000015', 'alamat' => 'Tebet, Jakarta Selatan'],
            ['nama' => 'Sanggar Liza',     'kat' => 'Attire',        'hp' => '08333000016', 'alamat' => 'Kuningan, Jakarta Selatan'],
            ['nama' => 'Biyan Bridal',     'kat' => 'Attire',        'hp' => '08333000017', 'alamat' => 'Pancoran, Jakarta Selatan'],
            ['nama' => 'Bennu Sorumba',    'kat' => 'Makeup Artist', 'hp' => '08333000018', 'alamat' => 'Blok M, Jakarta Selatan'],
            ['nama' => 'Bubah Alfian',     'kat' => 'Makeup Artist', 'hp' => '08333000019', 'alamat' => 'Kemang, Jakarta Selatan'],
            ['nama' => 'Marlene MUA',      'kat' => 'Makeup Artist', 'hp' => '08333000020', 'alamat' => 'Pondok Indah, Jakarta Selatan'],

            // ═══════════════════════════════════════════════
            // KELOMPOK 4: JAKARTA PUSAT (20 Vendor)
            // ═══════════════════════════════════════════════
            ['nama' => 'Thamrin Nine',     'kat' => 'Venue',         'hp' => '08444000001', 'alamat' => 'Sudirman, Jakarta Pusat'],
            ['nama' => 'Kempinski',        'kat' => 'Venue',         'hp' => '08444000002', 'alamat' => 'Bundaran HI, Jakarta Pusat'],
            ['nama' => 'Shangri-La',       'kat' => 'Venue',         'hp' => '08444000003', 'alamat' => 'Tanah Abang, Jakarta Pusat'],
            ['nama' => 'Tumpeng Mini',     'kat' => 'Catering',      'hp' => '08444000004', 'alamat' => 'Cempaka Putih, Jakarta Pusat'],
            ['nama' => 'DTC Catering',     'kat' => 'Catering',      'hp' => '08444000005', 'alamat' => 'Menteng, Jakarta Pusat'],
            ['nama' => 'Njonja Rasa',      'kat' => 'Catering',      'hp' => '08444000006', 'alamat' => 'Senen, Jakarta Pusat'],
            ['nama' => 'Suryo Decor',      'kat' => 'Dekorasi',      'hp' => '08444000007', 'alamat' => 'Sudirman, Jakarta Pusat'],
            ['nama' => 'Stupa Caspea',     'kat' => 'Dekorasi',      'hp' => '08444000008', 'alamat' => 'Kemayoran, Jakarta Pusat'],
            ['nama' => 'Grasida Decor',    'kat' => 'Dekorasi',      'hp' => '08444000009', 'alamat' => 'Menteng, Jakarta Pusat'],
            ['nama' => 'Axioo Photo',      'kat' => 'Dokumentasi',   'hp' => '08444000010', 'alamat' => 'Bundaran HI, Jakarta Pusat'],
            ['nama' => 'PPF Photo',        'kat' => 'Dokumentasi',   'hp' => '08444000011', 'alamat' => 'Cempaka Putih, Jakarta Pusat'],
            ['nama' => 'V8 Sound',         'kat' => 'Sound System',  'hp' => '08444000012', 'alamat' => 'Tanah Abang, Jakarta Pusat'],
            ['nama' => 'Melodia',          'kat' => 'Sound System',  'hp' => '08444000013', 'alamat' => 'Senen, Jakarta Pusat'],
            ['nama' => 'Naff Ent',         'kat' => 'Entertainment', 'hp' => '08444000014', 'alamat' => 'Kemayoran, Jakarta Pusat'],
            ['nama' => 'Symphony Band',    'kat' => 'Entertainment', 'hp' => '08444000015', 'alamat' => 'Menteng, Jakarta Pusat'],
            ['nama' => 'Vera Kebaya',      'kat' => 'Attire',        'hp' => '08444000016', 'alamat' => 'Sudirman, Jakarta Pusat'],
            ['nama' => 'Anne Avantie',     'kat' => 'Attire',        'hp' => '08444000017', 'alamat' => 'Bundaran HI, Jakarta Pusat'],
            ['nama' => 'Irwan Riady',      'kat' => 'Makeup Artist', 'hp' => '08444000018', 'alamat' => 'Cempaka Putih, Jakarta Pusat'],
            ['nama' => 'Anpa Suha',        'kat' => 'Makeup Artist', 'hp' => '08444000019', 'alamat' => 'Tanah Abang, Jakarta Pusat'],
            ['nama' => 'Ryan Ogilvy',      'kat' => 'Makeup Artist', 'hp' => '08444000020', 'alamat' => 'Menteng, Jakarta Pusat'],

            // ═══════════════════════════════════════════════
            // KELOMPOK 5: BEKASI (20 Vendor)
            // ═══════════════════════════════════════════════
            ['nama' => 'Grand Cempaka',    'kat' => 'Venue',         'hp' => '08555000001', 'alamat' => 'Bekasi Barat, Bekasi'],
            ['nama' => 'Aston Priority',   'kat' => 'Venue',         'hp' => '08555000002', 'alamat' => 'Bekasi Selatan, Bekasi'],
            ['nama' => 'Graha Mitra',      'kat' => 'Venue',         'hp' => '08555000003', 'alamat' => 'Cikarang, Bekasi'],
            ['nama' => 'Dapur Sultana',    'kat' => 'Catering',      'hp' => '08555000004', 'alamat' => 'Bekasi Utara, Bekasi'],
            ['nama' => 'Citra Rasa',       'kat' => 'Catering',      'hp' => '08555000005', 'alamat' => 'Pondok Gede, Bekasi'],
            ['nama' => 'Saribumi Catering', 'kat' => 'Catering',      'hp' => '08555000006', 'alamat' => 'Cikarang, Bekasi'],
            ['nama' => 'Prasetya Decor',   'kat' => 'Dekorasi',      'hp' => '08555000007', 'alamat' => 'Bekasi Barat, Bekasi'],
            ['nama' => 'Elegan Florist',   'kat' => 'Dekorasi',      'hp' => '08555000008', 'alamat' => 'Bekasi Selatan, Bekasi'],
            ['nama' => 'Harapan Decor',    'kat' => 'Dekorasi',      'hp' => '08555000009', 'alamat' => 'Pondok Gede, Bekasi'],
            ['nama' => 'Cahaya Foto',      'kat' => 'Dokumentasi',   'hp' => '08555000010', 'alamat' => 'Bekasi Utara, Bekasi'],
            ['nama' => 'Cikarang Film',    'kat' => 'Dokumentasi',   'hp' => '08555000011', 'alamat' => 'Cikarang, Bekasi'],
            ['nama' => 'Retro Sound',      'kat' => 'Sound System',  'hp' => '08555000012', 'alamat' => 'Bekasi Barat, Bekasi'],
            ['nama' => 'Mega Audio',       'kat' => 'Sound System',  'hp' => '08555000013', 'alamat' => 'Bekasi Selatan, Bekasi'],
            ['nama' => 'Nirwana Band',     'kat' => 'Entertainment', 'hp' => '08555000014', 'alamat' => 'Pondok Gede, Bekasi'],
            ['nama' => 'Galaxy Musik',     'kat' => 'Entertainment', 'hp' => '08555000015', 'alamat' => 'Cikarang, Bekasi'],
            ['nama' => 'Permata Bride',    'kat' => 'Attire',        'hp' => '08555000016', 'alamat' => 'Bekasi Utara, Bekasi'],
            ['nama' => 'Mahkota Busana',   'kat' => 'Attire',        'hp' => '08555000017', 'alamat' => 'Bekasi Barat, Bekasi'],
            ['nama' => 'Cantika MUA',      'kat' => 'Makeup Artist', 'hp' => '08555000018', 'alamat' => 'Bekasi Selatan, Bekasi'],
            ['nama' => 'Kirana Beauty',    'kat' => 'Makeup Artist', 'hp' => '08555000019', 'alamat' => 'Pondok Gede, Bekasi'],
            ['nama' => 'Puspawangi MUA',   'kat' => 'Makeup Artist', 'hp' => '08555000020', 'alamat' => 'Cikarang, Bekasi'],

            // ═══════════════════════════════════════════════
            // KELOMPOK 6: DEPOK (20 Vendor)
            // ═══════════════════════════════════════════════
            ['nama' => 'Bumi Sawangan',    'kat' => 'Venue',         'hp' => '08666000001', 'alamat' => 'Sawangan, Depok'],
            ['nama' => 'Depok Garden',     'kat' => 'Venue',         'hp' => '08666000002', 'alamat' => 'Pancoran Mas, Depok'],
            ['nama' => 'Cinere Palace',    'kat' => 'Venue',         'hp' => '08666000003', 'alamat' => 'Cinere, Depok'],
            ['nama' => 'Pesona Nusantara', 'kat' => 'Catering',      'hp' => '08666000004', 'alamat' => 'Beji, Depok'],
            ['nama' => 'Bunda Sari',       'kat' => 'Catering',      'hp' => '08666000005', 'alamat' => 'Sawangan, Depok'],
            ['nama' => 'Depok Catering',   'kat' => 'Catering',      'hp' => '08666000006', 'alamat' => 'Sukmajaya, Depok'],
            ['nama' => 'Bunga Rampai',     'kat' => 'Dekorasi',      'hp' => '08666000007', 'alamat' => 'Pancoran Mas, Depok'],
            ['nama' => 'Pesona Decor',     'kat' => 'Dekorasi',      'hp' => '08666000008', 'alamat' => 'Cinere, Depok'],
            ['nama' => 'Anggrek Design',   'kat' => 'Dekorasi',      'hp' => '08666000009', 'alamat' => 'Beji, Depok'],
            ['nama' => 'Ceria Studio',     'kat' => 'Dokumentasi',   'hp' => '08666000010', 'alamat' => 'Sawangan, Depok'],
            ['nama' => 'Lensaku Photo',    'kat' => 'Dokumentasi',   'hp' => '08666000011', 'alamat' => 'Sukmajaya, Depok'],
            ['nama' => 'Andalas Audio',    'kat' => 'Sound System',  'hp' => '08666000012', 'alamat' => 'Pancoran Mas, Depok'],
            ['nama' => 'Depok Sound Pro',  'kat' => 'Sound System',  'hp' => '08666000013', 'alamat' => 'Cinere, Depok'],
            ['nama' => 'Calung Nusantara', 'kat' => 'Entertainment', 'hp' => '08666000014', 'alamat' => 'Beji, Depok'],
            ['nama' => 'Akord Depok',      'kat' => 'Entertainment', 'hp' => '08666000015', 'alamat' => 'Sawangan, Depok'],
            ['nama' => 'Keris Busana',     'kat' => 'Attire',        'hp' => '08666000016', 'alamat' => 'Sukmajaya, Depok'],
            ['nama' => 'Adat Nusantara',   'kat' => 'Attire',        'hp' => '08666000017', 'alamat' => 'Pancoran Mas, Depok'],
            ['nama' => 'Srikandi MUA',     'kat' => 'Makeup Artist', 'hp' => '08666000018', 'alamat' => 'Cinere, Depok'],
            ['nama' => 'Arina Beauty',     'kat' => 'Makeup Artist', 'hp' => '08666000019', 'alamat' => 'Beji, Depok'],
            ['nama' => 'Ratna MUA',        'kat' => 'Makeup Artist', 'hp' => '08666000020', 'alamat' => 'Sawangan, Depok'],

            // ═══════════════════════════════════════════════
            // KELOMPOK 7: BOGOR (20 Vendor)
            // ═══════════════════════════════════════════════
            ['nama' => 'Villa Sentul',     'kat' => 'Venue',         'hp' => '08777000001', 'alamat' => 'Sentul, Bogor'],
            ['nama' => 'Bogor Ballroom',   'kat' => 'Venue',         'hp' => '08777000002', 'alamat' => 'Bogor Tengah, Bogor'],
            ['nama' => 'Puncak Resort',    'kat' => 'Venue',         'hp' => '08777000003', 'alamat' => 'Cisarua, Bogor'],
            ['nama' => 'Warung Nasi Ibu',  'kat' => 'Catering',      'hp' => '08777000004', 'alamat' => 'Cibinong, Bogor'],
            ['nama' => 'Sari Catering',    'kat' => 'Catering',      'hp' => '08777000005', 'alamat' => 'Bogor Utara, Bogor'],
            ['nama' => 'Lezat Nusantara',  'kat' => 'Catering',      'hp' => '08777000006', 'alamat' => 'Sentul, Bogor'],
            ['nama' => 'Puncak Decor',     'kat' => 'Dekorasi',      'hp' => '08777000007', 'alamat' => 'Cisarua, Bogor'],
            ['nama' => 'Bogor Florist',    'kat' => 'Dekorasi',      'hp' => '08777000008', 'alamat' => 'Bogor Tengah, Bogor'],
            ['nama' => 'Cibinong Decor',   'kat' => 'Dekorasi',      'hp' => '08777000009', 'alamat' => 'Cibinong, Bogor'],
            ['nama' => 'Sentul Cinema',    'kat' => 'Dokumentasi',   'hp' => '08777000010', 'alamat' => 'Sentul, Bogor'],
            ['nama' => 'Gunung Foto',      'kat' => 'Dokumentasi',   'hp' => '08777000011', 'alamat' => 'Bogor Utara, Bogor'],
            ['nama' => 'Puncak Audio',     'kat' => 'Sound System',  'hp' => '08777000012', 'alamat' => 'Cisarua, Bogor'],
            ['nama' => 'Bogor Sound',      'kat' => 'Sound System',  'hp' => '08777000013', 'alamat' => 'Cibinong, Bogor'],
            ['nama' => 'Bambu Band',       'kat' => 'Entertainment', 'hp' => '08777000014', 'alamat' => 'Sentul, Bogor'],
            ['nama' => 'Sunda Akustik',    'kat' => 'Entertainment', 'hp' => '08777000015', 'alamat' => 'Bogor Tengah, Bogor'],
            ['nama' => 'Batik Bogor',      'kat' => 'Attire',        'hp' => '08777000016', 'alamat' => 'Bogor Utara, Bogor'],
            ['nama' => 'Parahyangan Bride', 'kat' => 'Attire',        'hp' => '08777000017', 'alamat' => 'Cisarua, Bogor'],
            ['nama' => 'Dewi MUA',         'kat' => 'Makeup Artist', 'hp' => '08777000018', 'alamat' => 'Cibinong, Bogor'],
            ['nama' => 'Asri Beauty',      'kat' => 'Makeup Artist', 'hp' => '08777000019', 'alamat' => 'Sentul, Bogor'],
            ['nama' => 'Melati MUA',       'kat' => 'Makeup Artist', 'hp' => '08777000020', 'alamat' => 'Bogor Tengah, Bogor'],

            // ═══════════════════════════════════════════════
            // KELOMPOK 8: JAKARTA TIMUR (20 Vendor)
            // ═══════════════════════════════════════════════
            ['nama' => 'Grand Cibubur',    'kat' => 'Venue',         'hp' => '08888000001', 'alamat' => 'Cibubur, Jakarta Timur'],
            ['nama' => 'Klender Hall',     'kat' => 'Venue',         'hp' => '08888000002', 'alamat' => 'Duren Sawit, Jakarta Timur'],
            ['nama' => 'Jatinegara Garden', 'kat' => 'Venue',         'hp' => '08888000003', 'alamat' => 'Jatinegara, Jakarta Timur'],
            ['nama' => 'Pulo Mas Catering', 'kat' => 'Catering',      'hp' => '08888000004', 'alamat' => 'Pulo Gadung, Jakarta Timur'],
            ['nama' => 'Cawang Kitchen',   'kat' => 'Catering',      'hp' => '08888000005', 'alamat' => 'Kramatjati, Jakarta Timur'],
            ['nama' => 'Pondok Bambu Kat', 'kat' => 'Catering',      'hp' => '08888000006', 'alamat' => 'Duren Sawit, Jakarta Timur'],
            ['nama' => 'Cibubur Decor',    'kat' => 'Dekorasi',      'hp' => '08888000007', 'alamat' => 'Cibubur, Jakarta Timur'],
            ['nama' => 'Timur Florist',    'kat' => 'Dekorasi',      'hp' => '08888000008', 'alamat' => 'Jatinegara, Jakarta Timur'],
            ['nama' => 'Mawar Putih Decor', 'kat' => 'Dekorasi',      'hp' => '08888000009', 'alamat' => 'Pulo Gadung, Jakarta Timur'],
            ['nama' => 'Cibubur Studio',   'kat' => 'Dokumentasi',   'hp' => '08888000010', 'alamat' => 'Cibubur, Jakarta Timur'],
            ['nama' => 'Timur Lens',       'kat' => 'Dokumentasi',   'hp' => '08888000011', 'alamat' => 'Kramatjati, Jakarta Timur'],
            ['nama' => 'Duren Sound',      'kat' => 'Sound System',  'hp' => '08888000012', 'alamat' => 'Duren Sawit, Jakarta Timur'],
            ['nama' => 'Jatinegara Audio', 'kat' => 'Sound System',  'hp' => '08888000013', 'alamat' => 'Jatinegara, Jakarta Timur'],
            ['nama' => 'Cibubur Live',     'kat' => 'Entertainment', 'hp' => '08888000014', 'alamat' => 'Cibubur, Jakarta Timur'],
            ['nama' => 'Timur Stage',      'kat' => 'Entertainment', 'hp' => '08888000015', 'alamat' => 'Pulo Gadung, Jakarta Timur'],
            ['nama' => 'Cawang Bride',     'kat' => 'Attire',        'hp' => '08888000016', 'alamat' => 'Kramatjati, Jakarta Timur'],
            ['nama' => 'Timur Kebaya',     'kat' => 'Attire',        'hp' => '08888000017', 'alamat' => 'Duren Sawit, Jakarta Timur'],
            ['nama' => 'Jasmine MUA',      'kat' => 'Makeup Artist', 'hp' => '08888000018', 'alamat' => 'Cibubur, Jakarta Timur'],
            ['nama' => 'Puri Beauty',      'kat' => 'Makeup Artist', 'hp' => '08888000019', 'alamat' => 'Jatinegara, Jakarta Timur'],
            ['nama' => 'Orchid MUA',       'kat' => 'Makeup Artist', 'hp' => '08888000020', 'alamat' => 'Kramatjati, Jakarta Timur'],

            // ═══════════════════════════════════════════════
            // KELOMPOK 9: JAKARTA BARAT (20 Vendor)
            // ═══════════════════════════════════════════════
            ['nama' => 'Gajah Mada Hall',  'kat' => 'Venue',         'hp' => '08999000001', 'alamat' => 'Grogol, Jakarta Barat'],
            ['nama' => 'Ketapang Ballroom', 'kat' => 'Venue',         'hp' => '08999000002', 'alamat' => 'Kota, Jakarta Barat'],
            ['nama' => 'Puri Kembangan',   'kat' => 'Venue',         'hp' => '08999000003', 'alamat' => 'Kembangan, Jakarta Barat'],
            ['nama' => 'Cengkareng Kat',   'kat' => 'Catering',      'hp' => '08999000004', 'alamat' => 'Cengkareng, Jakarta Barat'],
            ['nama' => 'Barat Masak',      'kat' => 'Catering',      'hp' => '08999000005', 'alamat' => 'Grogol, Jakarta Barat'],
            ['nama' => 'Kebon Jeruk Kat',  'kat' => 'Catering',      'hp' => '08999000006', 'alamat' => 'Kebon Jeruk, Jakarta Barat'],
            ['nama' => 'Kembangan Decor',  'kat' => 'Dekorasi',      'hp' => '08999000007', 'alamat' => 'Kembangan, Jakarta Barat'],
            ['nama' => 'Grogol Florist',   'kat' => 'Dekorasi',      'hp' => '08999000008', 'alamat' => 'Grogol, Jakarta Barat'],
            ['nama' => 'Barat Design',     'kat' => 'Dekorasi',      'hp' => '08999000009', 'alamat' => 'Kota, Jakarta Barat'],
            ['nama' => 'Kota Tua Studio',  'kat' => 'Dokumentasi',   'hp' => '08999000010', 'alamat' => 'Kota, Jakarta Barat'],
            ['nama' => 'Barat Lens',       'kat' => 'Dokumentasi',   'hp' => '08999000011', 'alamat' => 'Cengkareng, Jakarta Barat'],
            ['nama' => 'Grogol Sound',     'kat' => 'Sound System',  'hp' => '08999000012', 'alamat' => 'Grogol, Jakarta Barat'],
            ['nama' => 'Kebon Jeruk Audio', 'kat' => 'Sound System',  'hp' => '08999000013', 'alamat' => 'Kebon Jeruk, Jakarta Barat'],
            ['nama' => 'Kembangan Live',   'kat' => 'Entertainment', 'hp' => '08999000014', 'alamat' => 'Kembangan, Jakarta Barat'],
            ['nama' => 'Barat Stage',      'kat' => 'Entertainment', 'hp' => '08999000015', 'alamat' => 'Grogol, Jakarta Barat'],
            ['nama' => 'Cengkareng Bride', 'kat' => 'Attire',        'hp' => '08999000016', 'alamat' => 'Cengkareng, Jakarta Barat'],
            ['nama' => 'Barat Kebaya',     'kat' => 'Attire',        'hp' => '08999000017', 'alamat' => 'Kebon Jeruk, Jakarta Barat'],
            ['nama' => 'Kota MUA',         'kat' => 'Makeup Artist', 'hp' => '08999000018', 'alamat' => 'Kota, Jakarta Barat'],
            ['nama' => 'Lavender Beauty',  'kat' => 'Makeup Artist', 'hp' => '08999000019', 'alamat' => 'Kembangan, Jakarta Barat'],
            ['nama' => 'Majestic MUA',     'kat' => 'Makeup Artist', 'hp' => '08999000020', 'alamat' => 'Cengkareng, Jakarta Barat'],

            // ═══════════════════════════════════════════════
            // KELOMPOK 10: JAKARTA UTARA (20 Vendor)
            // ═══════════════════════════════════════════════
            ['nama' => 'Ancol Ballroom',   'kat' => 'Venue',         'hp' => '08100000001', 'alamat' => 'Ancol, Jakarta Utara'],
            ['nama' => 'Pluit Convention', 'kat' => 'Venue',         'hp' => '08100000002', 'alamat' => 'Pluit, Jakarta Utara'],
            ['nama' => 'Sunter Garden',    'kat' => 'Venue',         'hp' => '08100000003', 'alamat' => 'Sunter, Jakarta Utara'],
            ['nama' => 'Kelapa Gading Kat', 'kat' => 'Catering',      'hp' => '08100000004', 'alamat' => 'Kelapa Gading, Jakarta Utara'],
            ['nama' => 'Utara Kitchen',    'kat' => 'Catering',      'hp' => '08100000005', 'alamat' => 'Pluit, Jakarta Utara'],
            ['nama' => 'Sunter Catering',  'kat' => 'Catering',      'hp' => '08100000006', 'alamat' => 'Sunter, Jakarta Utara'],
            ['nama' => 'Ancol Decor',      'kat' => 'Dekorasi',      'hp' => '08100000007', 'alamat' => 'Ancol, Jakarta Utara'],
            ['nama' => 'Gading Florist',   'kat' => 'Dekorasi',      'hp' => '08100000008', 'alamat' => 'Kelapa Gading, Jakarta Utara'],
            ['nama' => 'Pluit Design',     'kat' => 'Dekorasi',      'hp' => '08100000009', 'alamat' => 'Pluit, Jakarta Utara'],
            ['nama' => 'Ancol Studio',     'kat' => 'Dokumentasi',   'hp' => '08100000010', 'alamat' => 'Ancol, Jakarta Utara'],
            ['nama' => 'Gading Lens',      'kat' => 'Dokumentasi',   'hp' => '08100000011', 'alamat' => 'Kelapa Gading, Jakarta Utara'],
            ['nama' => 'Sunter Sound',     'kat' => 'Sound System',  'hp' => '08100000012', 'alamat' => 'Sunter, Jakarta Utara'],
            ['nama' => 'Utara Audio',      'kat' => 'Sound System',  'hp' => '08100000013', 'alamat' => 'Pluit, Jakarta Utara'],
            ['nama' => 'Ancol Live',       'kat' => 'Entertainment', 'hp' => '08100000014', 'alamat' => 'Ancol, Jakarta Utara'],
            ['nama' => 'Gading Stage',     'kat' => 'Entertainment', 'hp' => '08100000015', 'alamat' => 'Kelapa Gading, Jakarta Utara'],
            ['nama' => 'Pluit Bride',      'kat' => 'Attire',        'hp' => '08100000016', 'alamat' => 'Pluit, Jakarta Utara'],
            ['nama' => 'Utara Kebaya',     'kat' => 'Attire',        'hp' => '08100000017', 'alamat' => 'Sunter, Jakarta Utara'],
            ['nama' => 'Ancol MUA',        'kat' => 'Makeup Artist', 'hp' => '08100000018', 'alamat' => 'Ancol, Jakarta Utara'],
            ['nama' => 'Gading Beauty',    'kat' => 'Makeup Artist', 'hp' => '08100000019', 'alamat' => 'Kelapa Gading, Jakarta Utara'],
            ['nama' => 'Pluit MUA',        'kat' => 'Makeup Artist', 'hp' => '08100000020', 'alamat' => 'Pluit, Jakarta Utara'],
        ];

        // 5. Dummy Harga Pasti
        $dummyHarga = [15000000, 20000000, 25000000, 30000000, 35000000, 40000000, 50000000, 75000000];

        // 6. Distribusi status_approval yang realistis (Dihitung per daerah untuk sinkronisasi dropdown):
        //    Dalam 1 grup/daerah berisi 20 vendor:
        //    - 12 vendor (60%) -> Approved
        //    - 4 vendor (20%) -> Ditinjau
        //    - 4 vendor (20%) -> Pending
        $totalVendors = count($vendors);
        $countApproved = 0;
        $countDitinjau = 0;
        $countPending = 0;

        // Pastikan direktori penyimpanan proposal ada
        Storage::disk('local')->makeDirectory('proposals');

        // 7. Eksekusi ke Database
        foreach ($vendors as $index => $v) {
            $username = strtolower(str_replace([' ', '-', "'"], '', $v['nama']));

            $indexDalamGrup = $index % 20; // 0 sampai 19

            $statusApproval = match (true) {
                $indexDalamGrup < 12 => 'Approved',
                $indexDalamGrup < 16 => 'Ditinjau',
                default => 'Pending',
            };

            if ($statusApproval === 'Approved') {
                $countApproved++;
            } elseif ($statusApproval === 'Ditinjau') {
                $countDitinjau++;
            } else {
                $countPending++;
            }

            // Vendor Approved mendapatkan cold-start rating 4.5 sesuai spesifikasi
            $rating = $statusApproval === 'Approved' ? 4.5 : 0.0;
            $statusAktif = $statusApproval === 'Approved';

            // Generate dummy PDF proposal untuk vendor Pending & Ditinjau
            $proposalPath = null;

            if ($statusApproval !== 'Approved') {
                $slug = Str::slug($v['nama']);
                $filename = 'proposals/proposal-'.$slug.'.pdf';
                $namaVendor = $v['nama'];
                $kategori = $v['kat'];
                $alamat = $v['alamat'];

                // Buat PDF minimal valid (1 halaman kosong berisi teks identitas vendor)
                $pdfContent = "%PDF-1.4\n"
                    ."1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n"
                    ."2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n"
                    ."3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842]\n"
                    ."/Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>\nendobj\n"
                    ."4 0 obj\n<< /Length 200 >>\nstream\n"
                    ."BT\n/F1 14 Tf\n50 780 Td\n"
                    ."(PROPOSAL KERJA SAMA VENDOR) Tj\n"
                    ."/F1 11 Tf\n0 -30 Td\n"
                    ."(Nama Vendor : {$namaVendor}) Tj\n"
                    ."0 -20 Td\n(Kategori Jasa : {$kategori}) Tj\n"
                    ."0 -20 Td\n(Alamat : {$alamat}) Tj\n"
                    ."0 -20 Td\n(Status : Pengajuan Kerjasama) Tj\n"
                    ."ET\n"
                    ."endstream\nendobj\n"
                    ."5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n"
                    ."xref\n0 6\n0000000000 65535 f\n"
                    ."0000000009 00000 n\n"
                    ."0000000058 00000 n\n"
                    ."0000000115 00000 n\n"
                    ."0000000266 00000 n\n"
                    ."0000000516 00000 n\n"
                    ."trailer\n<< /Size 6 /Root 1 0 R >>\n"
                    ."startxref\n596\n%%EOF";

                Storage::disk('local')->put($filename, $pdfContent);
                $proposalPath = $filename;
            }

            Vendor::create([
                'nama_vendor' => $v['nama'],
                'kategori_jasa' => $v['kat'],
                'email' => $username.'@example.com',
                'no_telepon' => $v['hp'],
                'alamat' => $v['alamat'],
                'harga' => $dummyHarga[array_rand($dummyHarga)],
                'link_portofolio' => $portofolioLinks[$v['kat']],
                'proposal_file' => $proposalPath,
                'detail_spesifikasi' => $spesifikasi[$v['kat']],
                'rating' => $rating,
                'status_aktif' => $statusAktif,
                'status_approval' => $statusApproval,
                'tanggal_kontrak_habis' => Carbon::now()->addDays(rand(-15, 180)),
            ]);
        }

        $this->command->info('VendorSeeder: '.$totalVendors.' vendor dummy berhasil di-seed (200 vendor se-Jabodetabek).');
        $this->command->table(
            ['Status', 'Jumlah', 'Rating'],
            [
                ['Approved', $countApproved, '4.5'],
                ['Ditinjau', $countDitinjau, '0.0'],
                ['Pending', $countPending, '0.0'],
            ]
        );
    }
}
