<?php

namespace App\Http\Controllers;

use App\Models\Client;

class ManagerOperasional extends Controller
{
    public function dashboard()
    {
        // Mengambil data jumlah klien per bulan pada tahun ini
        $clients = Client::whereYear('created_at', date('Y'))->get();

        // Menyiapkan array untuk 12 bulan (Jan - Des) agar grafik rapi
        $dataBulan = array_fill(1, 12, 0);
        foreach ($clients as $data) {
            if ($data->created_at) {
                $bulan = (int) $data->created_at->format('n');
                $dataBulan[$bulan]++;
            }
        }

        $chartData = array_values($dataBulan);

        return view('manager.dashboard', compact('chartData'));
    }
}
