<?php

namespace App\Console\Commands;

use App\Models\FinancePayment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCommissionReminder extends Command
{
    // Nama command yang akan dipanggil
    protected $signature = 'finance:send-reminder';

    protected $description = 'Kirim email reminder ke Finance untuk komisi vendor yang mendekati tenggat waktu';

    public function handle()
    {
        // Cari tagihan yang statusnya belum dibayar dan tenggat waktunya H-3 dari sekarang
        $payments = FinancePayment::with('project.vendor')
            ->where('status_pembayaran', 'unpaid')
            ->whereDate('tenggat_waktu', Carbon::now()->addDays(3)->toDateString())
            ->get();

        foreach ($payments as $payment) {
            $vendorName = $payment->project->vendor->nama_vendor;
            $amount = number_format($payment->jumlah_komisi, 2, ',', '.');

            // Kirim email peringatan ke departemen finance internal
            Mail::raw("Reminder: Tagihan komisi untuk vendor {$vendorName} sebesar Rp{$amount} akan jatuh tempo pada {$payment->tenggat_waktu}. Segera lakukan pembayaran.", function ($message) use ($vendorName) {
                $message->to('finance@perusahaananda.com')
                    ->subject("URGENT: Reminder Jatuh Tempo Komisi - {$vendorName}");
            });
        }

        $this->info('Reminder komisi berhasil dikirim!');
    }
}
