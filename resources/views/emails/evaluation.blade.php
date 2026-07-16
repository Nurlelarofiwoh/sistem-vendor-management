<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-w-xl;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #8B5A2B;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Halo, {{ $project->client->nama_klien ?? 'Klien' }}!</h2>
        <p>Terima kasih telah mempercayakan penyelenggaraan <strong>{{ $project->nama_proyek }}</strong> kepada PT Liza Makmur Mandiri.</p>
        <p>Agar kami dapat terus meningkatkan kualitas layanan, kami sangat menghargai jika Anda bersedia meluangkan
            waktu 1 menit untuk memberikan penilaian (rating) kepada vendor-vendor yang terlibat dalam acara Anda.</p>

        <a href="{{ url('/evaluasi/' . $token) }}" class="btn">Berikan Penilaian Sekarang</a>

        <p style="margin-top: 30px; font-size: 12px; color: #777;">Tautan ini dibuat khusus untuk Anda dan tidak perlu
            melakukan login. Jika tombol tidak berfungsi, salin dan tempel URL berikut di browser Anda: <br>
            {{ url('/evaluasi/' . $token) }}</p>
    </div>
</body>

</html>
