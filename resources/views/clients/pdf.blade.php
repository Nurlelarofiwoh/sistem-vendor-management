<!DOCTYPE html>
<html>

<head>
    <title>Nota Kesepakatan Proyek</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-b: 2px solid #4A3018;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #4A3018;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #8B5A2B;
            margin-top: 20px;
            border-bottom: 1px solid #F5EBE1;
            padding-bottom: 5px;
        }

        table {
            w-full;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table td {
            padding: 8px 0;
            vertical-align: top;
        }

        table td.label {
            width: 30%;
            font-weight: bold;
            color: #555;
        }

        .footer {
            margin-top: 5px;
            text-align: center;
            font-size: 12px;
            color: #aaa;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="title">EVENT ORGANIZER</div>
        <div style="font-size: 12px; color: #666;">Nota Kesepakatan Rekomendasi Vendor & Klien</div>
    </div>

    <p>Dokumen ini menerbitkan rincian kesepakatan penugasan kerja sama vendor yang divalidasi oleh sistem VMS.</p>

    <div class="section-title">Detail Proyek Acara</div>
    <table style="width: 100%;">
        <tr>
            <td class="label">Nama Proyek</td>
            <td>: {{ $project->nama_proyek }}</td>
        </tr>
        <tr>
            <td class="label">Status Awal</td>
            <td>: Sedang Berjalan (Persiapan)</td>
        </tr>
        <tr>
            <td class="label">Tanggal Cetak</td>
            <td>: {{ date('d F Y') }}</td>
        </tr>
    </table>

    <div class="section-title">Data Profil Klien</div>
    <table style="width: 100%;">
        <tr>
            <td class="label">Nama Klien</td>
            <td>: {{ $client->nama_klien }}</td>
        </tr>
        <tr>
            <td class="label">Instansi</td>
            <td>: {{ $client->instansi }}</td>
        </tr>
        <tr>
            <td class="label">No. Telepon</td>
            <td>: {{ $client->no_telepon }}</td>
        </tr>
        <tr>
            <td class="label">Email Klien</td>
            <td>: {{ $client->email }}</td>
        </tr>
        <tr>
            <td class="label">Kebutuhan Utama</td>
            <td>: {{ $client->kebutuhan_klien }}</td>
        </tr>
    </table>

    <div class="section-title">Vendor yang Ditugaskan (Rekomendasi)</div>
    <table style="width: 100%;">
        <tr>
            <td class="label">Nama Perusahaan/Vendor</td>
            <td>: {{ $project->vendor->nama_vendor }}</td>
        </tr>
        <tr>
            <td class="label">Kategori Jasa</td>
            <td>: {{ $project->vendor->kategori_jasa }}</td>
        </tr>
        <tr>
            <td class="label">Kualitas Mitra</td>
            <td>: ⭐ {{ number_format($project->vendor->rating, 1) }} / 5.0</td>
        </tr>
    </table>

    <div class="footer">
        Sistem Informasi VMS &copy; {{ date('Y') }} - Dokumen Sah Komputerisasi
    </div>

</body>

</html>
