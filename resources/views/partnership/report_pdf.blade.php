<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1 {
            font-size: 22px;
            color: #4A3018;
            margin-bottom: 5px;
            text-transform: uppercase;
            text-align: center;
            border-bottom: 2px solid #8B5A2B;
            padding-bottom: 10px;
        }

        p.subtitle {
            color: #777;
            font-size: 11px;
            text-align: center;
            margin-top: 5px;
            margin-bottom: 25px;
        }

        /* CSS Untuk Kotak Dashboard (Cards) */
        .dashboard-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px;
            margin-bottom: 25px;
        }

        .card {
            background-color: #FDF8F3;
            border: 1px solid #D4A373;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
        }

        .card-title {
            font-size: 11px;
            color: #8B5A2B;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .card-value {
            font-size: 36px;
            color: #4A3018;
            font-weight: bold;
            margin: 0;
        }

        /* CSS Untuk Tabel Summary */
        h2 {
            font-size: 13px;
            color: #4A3018;
            margin-top: 18px;
            margin-bottom: 8px;
            border-left: 4px solid #8B5A2B;
            padding-left: 8px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 11px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #E5E7EB;
            padding: 8px 12px;
            text-align: left;
        }

        .summary-table th {
            background-color: #4A3018;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        .summary-table tr:nth-child(even) {
            background-color: #FAFAFA;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
            font-weight: bold;
            color: #8B5A2B;
        }

        .section-badge {
            display: inline-block;
            background-color: #F5EBE1;
            color: #8B5A2B;
            font-size: 10px;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 999px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Link portofolio yang bisa diklik */
        a.porto-link {
            color: #8B5A2B;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <h1>Laporan Rekapitulasi VMS</h1>
    <p class="subtitle">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB | Divisi: Strategic Partnership
        @if($dataFilter !== 'semua')
            | Menampilkan: Data {{ ucfirst($dataFilter) }}
        @endif
    </p>

    {{-- Dashboard Summary Cards --}}
    @if($dataFilter === 'semua')
    <table class="dashboard-grid">
        <tr>
            <td class="card" style="width: 33%;">
                <div class="card-title">Total Mitra Vendor</div>
                <div class="card-value">{{ $totalVendor }}</div>
            </td>
            <td class="card" style="width: 33%;">
                <div class="card-title">Total Klien Terdaftar</div>
                <div class="card-value">{{ $totalKlien }}</div>
            </td>
            <td class="card" style="width: 33%;">
                <div class="card-title">Event Berjalan</div>
                <div class="card-value">{{ $eventBerjalan }}</div>
            </td>
        </tr>
    </table>
    @endif

    {{-- =============================== --}}
    {{-- BAGIAN DATA VENDOR --}}
    {{-- =============================== --}}
    @if($dataFilter === 'semua' || $dataFilter === 'vendor')
        <h2>Grafik Tabular: Sebaran Vendor per Kategori Jasa</h2>
        <table class="summary-table">
            <thead>
                <tr>
                    <th style="width: 8%;" class="text-center">No</th>
                    <th style="width: 52%;">Kategori Jasa Vendor</th>
                    <th style="width: 20%;" class="text-center">Jumlah Mitra</th>
                    <th style="width: 20%;" class="text-center">Rata-rata Rating</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapVendor as $index => $rv)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ strtoupper($rv->kategori_jasa) }}</td>
                        <td class="text-center text-right">{{ $rv->total }} Mitra</td>
                        <td class="text-center">⭐ {{ number_format($rv->avg_rating, 1) }} / 5.0</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data vendor.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    {{-- =============================== --}}
    {{-- BAGIAN DATA KLIEN --}}
    {{-- =============================== --}}
    @if($dataFilter === 'semua' || $dataFilter === 'klien')
        <h2>Daftar Klien Terdaftar</h2>
        <table class="summary-table">
            <thead>
                <tr>
                    <th style="width: 8%;" class="text-center">No</th>
                    <th style="width: 40%;">Nama Klien</th>
                    <th style="width: 25%;">Instansi / Perusahaan</th>
                    <th style="width: 20%;" class="text-center">No. Telepon</th>
                    <th style="width: 15%;" class="text-center">Terdaftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapKlien as $index => $klien)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $klien->nama_klien }}</td>
                        <td>{{ $klien->instansi ?? '-' }}</td>
                        <td class="text-center">{{ $klien->no_telepon ?? '-' }}</td>
                        <td class="text-center">{{ $klien->created_at ? $klien->created_at->format('d M Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data klien.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    {{-- =============================== --}}
    {{-- BAGIAN DATA EVENT --}}
    {{-- =============================== --}}
    @if($dataFilter === 'semua' || $dataFilter === 'event')
        <h2>Grafik Tabular: Status Kinerja Seluruh Event</h2>
        <table class="summary-table">
            <thead>
                <tr>
                    <th style="width: 10%;" class="text-center">No</th>
                    <th style="width: 60%;">Tahapan Status Event</th>
                    <th style="width: 30%;" class="text-center">Jumlah Event</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapEvent as $index => $re)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ strtoupper($re->status_proyek ?? 'Menunggu Logistik') }}</td>
                        <td class="text-center text-right">{{ $re->total }} Proyek</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Belum ada data event.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

</body>

</html>
