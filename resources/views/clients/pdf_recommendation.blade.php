<!DOCTYPE html>
<html>

<head>
    <title>Rekomendasi Vendor</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 13px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #8B5A2B;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #4A3018;
            margin-bottom: 4px;
            font-size: 20px;
        }

        .header p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }

        .client-info {
            background-color: #F5EBE1;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .client-info strong {
            color: #4A3018;
        }

        .tier-badge {
            display: inline-block;
            padding: 2px 9px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .tier-1 {
            background-color: #4A3018;
            color: #FFD700;
        }

        .tier-2 {
            background-color: #8B5A2B;
            color: #ffffff;
        }

        .tier-3 {
            background-color: #D4A373;
            color: #4A3018;
        }

        .kebutuhan-box {
            font-family: DejaVu Sans Mono, monospace;
            font-size: 11px;
            background: #fff;
            padding: 8px 10px;
            border-radius: 4px;
            margin-top: 5px;
            border: 1px solid #e0d5c8;
            white-space: pre-wrap;
        }

        .section-title {
            color: #4A3018;
            text-align: center;
            font-size: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .kategori-title {
            color: #fff;
            background-color: #8B5A2B;
            font-size: 13px;
            font-weight: bold;
            padding: 5px 12px;
            border-radius: 4px;
            margin-top: 22px;
            margin-bottom: 10px;
        }

        .vendor-card {
            border: 1px solid #ddd;
            padding: 12px 15px;
            margin-bottom: 12px;
            border-radius: 5px;
            page-break-inside: avoid;
            background-color: #fefefe;
        }

        .vendor-card.challenger {
            border-left: 4px solid #D4A373;
            background-color: #FFFDF9;
        }

        .vendor-header {
            border-bottom: 1px solid #edd9c0;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .vendor-title {
            color: #4A3018;
            font-size: 15px;
            font-weight: bold;
            margin: 0 0 3px 0;
        }

        .vendor-meta {
            font-size: 11px;
            color: #666;
        }

        .badge {
            background-color: #D4A373;
            color: white;
            padding: 2px 7px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: bold;
        }

        .slot-label {
            font-size: 10px;
            font-weight: bold;
            padding: 1px 6px;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }

        .slot-proven {
            background-color: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #A5D6A7;
        }

        .slot-challenger {
            background-color: #FFF3E0;
            color: #E65100;
            border: 1px solid #FFCC80;
        }

        .rating {
            color: #d97706;
            font-weight: bold;
            font-size: 12px;
        }

        .warning-tag {
            color: #dc2626;
            font-weight: bold;
            font-size: 11px;
        }

        .detail-table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .detail-table td {
            padding: 3px 4px;
            vertical-align: top;
        }

        .detail-table .label {
            width: 140px;
            font-weight: bold;
            color: #555;
        }

        .porto-box {
            background-color: #FDF8F3;
            border: 1px solid #D4A373;
            border-radius: 4px;
            padding: 5px 10px;
            margin-top: 8px;
            font-size: 11px;
        }

        .porto-label {
            font-weight: bold;
            color: #4A3018;
            display: block;
            margin-bottom: 2px;
        }

        .porto-url {
            color: #8B5A2B;
            word-break: break-all;
        }

        .spesifikasi-box {
            background: #f7f7f7;
            padding: 8px 10px;
            border: 1px dashed #ccc;
            font-size: 11px;
            margin-top: 8px;
            border-radius: 4px;
        }

        .spesifikasi-box strong {
            color: #4A3018;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
            font-size: 13px;
            border: 2px dashed #ddd;
            border-radius: 6px;
            margin-top: 15px;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            color: #888;
            margin-top: 30px;
            border-top: 1px solid #e0d5c8;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>PT Liza Makmur Mandiri</h1>
        <p>Dokumen Rekomendasi Vendor Acara &mdash; Vendor Management System</p>
    </div>

    <div class="client-info">
        <strong>Informasi Klien:</strong><br>
        Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $client->nama_klien }}<br>
        Instansi &nbsp;&nbsp;&nbsp;&nbsp;: {{ $client->instansi }}<br>
        Kontak &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $client->no_telepon }} | {{ $client->email }}<br>
        Tanggal Acara: {{ \Carbon\Carbon::parse($client->tanggal_acara)->translatedFormat('d F Y') }}<br>
        Tempat Acara : {{ $client->tempat_acara }}<br>
        @if($client->budget)
        Anggaran &nbsp;&nbsp;&nbsp;: Rp {{ number_format($client->budget, 0, ',', '.') }}
        &nbsp;&nbsp;
        @php
            $tierClass = 'tier-' . ($tierInfo['tier'] ?? 2);
            $tierLabel = 'Tier ' . ($tierInfo['tier'] ?? 2) . ' — ' . ($tierInfo['label'] ?? 'Regular');
        @endphp
        <span class="tier-badge {{ $tierClass }}">{{ $tierLabel }}</span><br>
        @endif
        <br>
        <strong>Detail Kebutuhan Klien:</strong>
        <div class="kebutuhan-box">{{ $client->kebutuhan_klien }}</div>
    </div>

    <p class="section-title">Top-3 Curated Shortlist &mdash; Rekomendasi Vendor Berdasarkan Kebutuhan &amp; Lokasi</p>

    @if (!empty($rekomendasiPerKategori))
        @foreach ($rekomendasiPerKategori as $kategori => $vendors)
            <div class="kategori-title">
                Kategori: {{ $kategori }} &nbsp;&mdash;&nbsp; Top-3 Curated Shortlist
            </div>

            @foreach ($vendors as $index => $vendor)
                @php $isChallenger = is_null($vendor->rating); @endphp
                <div class="vendor-card {{ $isChallenger ? 'challenger' : '' }}">
                    <div class="vendor-header">
                        <p class="vendor-title">#{{ $index + 1 }} &mdash; {{ $vendor->nama_vendor }}</p>
                        <p class="vendor-meta">
                            <span class="badge">{{ $vendor->kategori_jasa }}</span>
                            &nbsp;&nbsp;
                            @if ($isChallenger)
                                <span class="slot-label slot-challenger">Exclusive New Partner</span>
                            @else
                                <span class="slot-label slot-proven">Proven Vendor</span>
                                &nbsp;&nbsp;
                                <span class="rating">&#9733; {{ number_format($vendor->rating, 1) }} / 5.0</span>
                            @endif
                            @if (isset($vendor->warning_status))
                                &nbsp;&nbsp;<span class="warning-tag">&#9888; {{ $vendor->warning_status }}</span>
                            @endif
                        </p>
                    </div>

                    <table class="detail-table">
                        <tr>
                            <td class="label">Estimasi Harga</td>
                            <td>
                                : {{ $vendor->harga ? 'Rp ' . number_format($vendor->harga, 0, ',', '.') : 'Menyesuaikan Kebutuhan' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Kontak Vendor</td>
                            <td>: {{ $vendor->no_telepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Alamat / Wilayah</td>
                            <td>: {{ $vendor->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Email</td>
                            <td>: {{ $vendor->email ?? '-' }}</td>
                        </tr>
                    </table>

                    {{-- Link Portofolio: ditampilkan sebagai plain text URL agar terbaca di PDF --}}
                    @if ($vendor->link_portofolio)
                        <div class="porto-box">
                            <span class="porto-label">&#128279; Tautan Portofolio Vendor:</span>
                            <span class="porto-url">{{ $vendor->link_portofolio }}</span>
                        </div>
                    @endif

                    {{-- Detail Spesifikasi / Kapasitas Teknis --}}
                    @if ($vendor->detail_spesifikasi && count(array_filter((array) $vendor->detail_spesifikasi)) > 0)
                        <div class="spesifikasi-box">
                            <strong>Kapasitas &amp; Fasilitas Vendor:</strong><br>
                            @foreach ((array) $vendor->detail_spesifikasi as $key => $val)
                                @if (!empty($val))
                                    &bull; {{ ucwords(str_replace('_', ' ', $key)) }}: {{ $val }}<br>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        @endforeach
    @else
        <div class="empty-state">
            <p><strong>Tidak ada rekomendasi vendor yang tersedia.</strong></p>
            <p>Pastikan vendor aktif telah terdaftar di sistem dengan lokasi yang sesuai.</p>
        </div>
    @endif

    <div class="footer">
        Dokumen ini dicetak secara otomatis oleh sistem pada {{ now()->translatedFormat('d F Y, H:i') }} WIB.
    </div>

</body>

</html>
