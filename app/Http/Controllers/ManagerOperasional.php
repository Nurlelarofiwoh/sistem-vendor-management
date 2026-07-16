<?php

namespace App\Http\Controllers;

use App\Models\Client;

class ManagerOperasional extends Controller
{
    public function dashboard()
    {
        // Mengambil data jumlah klien per bulan pada tahun ini
        $grafikKlien = Client::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Menyiapkan array untuk 12 bulan (Jan - Des) agar grafik rapi
        $dataBulan = array_fill(1, 12, 0);
        foreach ($grafikKlien as $data) {
            $dataBulan[$data->bulan] = $data->jumlah;
        }

        $chartData = array_values($dataBulan);

        return view('manager.dashboard', compact('chartData'));
    }
}
