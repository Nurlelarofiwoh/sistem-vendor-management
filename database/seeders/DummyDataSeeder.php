<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\FinancePayment;
use App\Models\Project;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Vendor (Penyedia Jasa Pernikahan)
        $vendor = Vendor::create([
            'nama_vendor' => 'Mahligai Dekorasi & Catering',
            'kategori_jasa' => 'Katering & Konsumsi',
            'email' => 'hello@mahligaidekor.test',
            'no_telepon' => '081234567890',
            'rating' => 4.9,
            'status_aktif' => true,
        ]);

        // 2. Data Klien (Calon Pengantin/Keluarga)
        $client = Client::create([
            'nama_klien' => 'Keluarga Bapak Budi',
            'instansi' => 'Personal',
            'email' => 'budi.family@email.test',
            'no_telepon' => '089988776655',
            'kebutuhan_klien' => 'Kebutuhan paket dekorasi pelaminan elegan, tenda roder VIP transparan, dan katering prasmanan untuk 1000 tamu undangan.',
        ]);

        // 3. Data Proyek (Resepsi Pernikahan)
        $project = Project::create([
            'client_id' => $client->id,
            'vendor_id' => $vendor->id,
            'nama_proyek' => 'Resepsi Pernikahan Dinda & Dimas',
            'status_proyek' => 'berjalan',
        ]);

        // 4. Data Keuangan (Tagihan DP & Pelunasan Komisi)
        FinancePayment::create([
            'project_id' => $project->id,
            'jumlah_komisi' => 15000000, // 15 Juta
            'status_pembayaran' => 'pending',
            'tenggat_waktu' => Carbon::now()->addDays(7)->toDateString(), // Tenggat waktu minggu depan
        ]);

        FinancePayment::create([
            'project_id' => $project->id,
            'jumlah_komisi' => 35000000, // 35 Juta
            'status_pembayaran' => 'paid',
            'tenggat_waktu' => Carbon::now()->subDays(2)->toDateString(), // Sudah lewat 2 hari yang lalu
            'bukti_bayar' => 'dummy/struk-lunas-wedding.pdf',
        ]);
    }
}
