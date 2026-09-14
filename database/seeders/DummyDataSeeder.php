<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\FinancePayment;
use App\Models\Project;
use App\Models\TechnicalMeeting;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Dapatkan atau buat Vendor sampel untuk pengikatan data
        $vendor1 = Vendor::firstOrCreate(
            ['email' => 'hello@mahligaidekor.test'],
            [
                'nama_vendor' => 'Mahligai Dekorasi & Catering',
                'kategori_jasa' => 'Katering & Konsumsi',
                'no_telepon' => '081234567890',
                'rating' => 4.9,
                'status_aktif' => true,
                'status_approval' => 'Approved',
            ]
        );

        $vendor2 = Vendor::where('email', 'harmoni.fotovideo@gmail.com')->first() ?? $vendor1;
        $vendor3 = Vendor::where('email', 'kasih.katering@premium.co.id')->first() ?? $vendor1;
        $vendor4 = Vendor::where('email', 'aura.dekor@nusantara.id')->first() ?? $vendor1;

        // 2. KLIEN 1: Resepsi Pernikahan Dinda & Dimas (Status: berjalan)
        $client1 = Client::firstOrCreate(
            ['email' => 'budi.family@email.test'],
            [
                'nama_klien' => 'Keluarga Bapak Budi',
                'instansi' => 'Personal',
                'no_telepon' => '089988776655',
                'kebutuhan_klien' => 'Paket dekorasi pelaminan elegan, tenda roder VIP transparan, dan katering prasmanan 1000 pax.',
                'tanggal_acara' => Carbon::now()->addDays(20),
                'tempat_acara' => 'Chakra Venue, BSD',
                'budget' => 150000000,
                'vendor_terpilih_id' => $vendor1->id,
                'is_vendor_acc' => true,
            ]
        );
        $client1->vendors()->syncWithoutDetaching([
            $vendor1->id => ['jumlah_komisi' => 15000000, 'status_komisi' => 'Pending'],
        ]);

        $project1 = Project::firstOrCreate(
            ['client_id' => $client1->id],
            [
                'nama_proyek' => 'Resepsi Pernikahan Dinda & Dimas',
                'status_proyek' => 'berjalan',
                'nominal_invoice' => 150000000,
                'tanggal_komisi_jatuh_tempo' => Carbon::now()->addDays(7)->toDateString(),
            ]
        );

        TechnicalMeeting::firstOrCreate(
            ['project_id' => $project1->id],
            [
                'jadwal_tm' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
                'lokasi' => 'Chakra Venue BSD, Hall Utama',
                'agenda' => 'Final Briefing Vendor, Layout Pelaminan, dan Run Down Acara',
                'status_tm' => 'Terjadwal',
            ]
        );

        if (FinancePayment::where('project_id', $project1->id)->count() === 0) {
            FinancePayment::create([
                'project_id' => $project1->id,
                'jumlah_komisi' => 15000000,
                'status_pembayaran' => 'unpaid',
                'tenggat_waktu' => Carbon::now()->addDays(7)->toDateString(),
            ]);
        }

        // 3. KLIEN 2: Wedding Reception Anisa & Reza (Status: Perlu Invoice)
        $client2 = Client::firstOrCreate(
            ['email' => 'anisa.reza@wedding.test'],
            [
                'nama_klien' => 'Ibu Anisa Sastro',
                'instansi' => 'Personal',
                'no_telepon' => '081299887766',
                'kebutuhan_klien' => 'Paket dokumentasi foto & video cinematic, katering 500 pax, serta makeup artist.',
                'tanggal_acara' => Carbon::now()->addDays(10),
                'tempat_acara' => 'Ballroom Hotel Mulia, Jakarta',
                'budget' => 200000000,
                'vendor_terpilih_id' => $vendor2->id,
                'is_vendor_acc' => true,
            ]
        );
        $client2->vendors()->syncWithoutDetaching([
            $vendor2->id => ['jumlah_komisi' => 20000000, 'status_komisi' => 'Pending'],
        ]);

        $project2 = Project::firstOrCreate(
            ['client_id' => $client2->id],
            [
                'nama_proyek' => 'Wedding Reception Anisa & Reza',
                'status_proyek' => 'Perlu Invoice',
                'nominal_invoice' => 200000000,
                'tanggal_komisi_jatuh_tempo' => Carbon::now()->addDays(14)->toDateString(),
            ]
        );

        TechnicalMeeting::firstOrCreate(
            ['project_id' => $project2->id],
            [
                'jadwal_tm' => Carbon::now()->addDays(3)->format('Y-m-d H:i:s'),
                'lokasi' => 'Meeting Room Hotel Mulia',
                'agenda' => 'Koordinasi Tim Liputan & Check List Peralatan Foto/Video',
                'status_tm' => 'Terjadwal',
            ]
        );

        // 4. KLIEN 3: Royal Wedding Hendra & Rina (Status: Finish Event)
        $client3 = Client::firstOrCreate(
            ['email' => 'dr.hendra@hospital.test'],
            [
                'nama_klien' => 'Dr. Hendra & Rina',
                'instansi' => 'RS Siloam',
                'no_telepon' => '081377665544',
                'kebutuhan_klien' => 'Dekorasi megah adat Jawa Modern & Katering Internasional 1200 pax.',
                'tanggal_acara' => Carbon::now()->subDays(15),
                'tempat_acara' => 'Gran Melia Jakarta Ballroom',
                'budget' => 350000000,
                'vendor_terpilih_id' => $vendor3->id,
                'is_vendor_acc' => true,
            ]
        );
        $client3->vendors()->syncWithoutDetaching([
            $vendor3->id => ['jumlah_komisi' => 35000000, 'status_komisi' => 'Belum Dibayar'],
        ]);

        $project3 = Project::firstOrCreate(
            ['client_id' => $client3->id],
            [
                'nama_proyek' => 'Royal Wedding Dr. Hendra & Rina',
                'status_proyek' => 'Finish Event',
                'nominal_invoice' => 350000000,
                'tanggal_komisi_jatuh_tempo' => Carbon::now()->subDays(5)->toDateString(),
                'tanggal_finish_event' => Carbon::now()->subDays(15)->toDateString(),
            ]
        );

        if (FinancePayment::where('project_id', $project3->id)->count() === 0) {
            FinancePayment::create([
                'project_id' => $project3->id,
                'jumlah_komisi' => 35000000,
                'status_pembayaran' => 'unpaid',
                'tenggat_waktu' => Carbon::now()->subDays(5)->toDateString(),
            ]);
        }

        // 5. KLIEN 4: Intimate Wedding Fitri & Galih (Status: Transaksi Komplit)
        $client4 = Client::firstOrCreate(
            ['email' => 'galih.fitri@email.test'],
            [
                'nama_klien' => 'Bapak Agus & Fitri',
                'instansi' => 'Personal',
                'no_telepon' => '081544332211',
                'kebutuhan_klien' => 'Paket intimate wedding 300 pax, live music acoustics, dan foto outdoor.',
                'tanggal_acara' => Carbon::now()->subDays(30),
                'tempat_acara' => 'Chakra Venue Garden',
                'budget' => 80000000,
                'vendor_terpilih_id' => $vendor4->id,
                'is_vendor_acc' => true,
            ]
        );
        $client4->vendors()->syncWithoutDetaching([
            $vendor4->id => ['jumlah_komisi' => 8000000, 'status_komisi' => 'Lunas'],
        ]);

        $project4 = Project::firstOrCreate(
            ['client_id' => $client4->id],
            [
                'nama_proyek' => 'Intimate Wedding Fitri & Galih',
                'status_proyek' => 'Transaksi Komplit',
                'nominal_invoice' => 80000000,
                'tanggal_komisi_jatuh_tempo' => Carbon::now()->subDays(10)->toDateString(),
                'tanggal_komisi_dibayar' => Carbon::now()->subDays(3)->toDateString(),
                'tanggal_finish_event' => Carbon::now()->subDays(30)->toDateString(),
            ]
        );

        if (FinancePayment::where('project_id', $project4->id)->count() === 0) {
            FinancePayment::create([
                'project_id' => $project4->id,
                'jumlah_komisi' => 8000000,
                'status_pembayaran' => 'paid',
                'tenggat_waktu' => Carbon::now()->subDays(10)->toDateString(),
                'bukti_bayar' => 'dummy/struk-lunas-fitrigalih.pdf',
            ]);
        }
    }
}
