<!DOCTYPE html>
<html>

<head>
    <title>Undangan Technical Meeting</title>
</head>

<body style="font-family: sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #4A3018;">Undangan Technical Meeting (TM)</h2>
    <p>Halo, Anda dijadwalkan untuk mengikuti rapat koordinasi dengan rincian berikut:</p>
    <ul>
        <li><strong>Proyek:</strong> {{ $meeting->project->nama_proyek }}</li>
        <li><strong>Jadwal:</strong> {{ \Carbon\Carbon::parse($meeting->jadwal_tm)->format('d F Y, H:i') }} WIB</li>
        <li><strong>Lokasi / Tautan:</strong> {{ $meeting->lokasi }}</li>
        <li><strong>Agenda:</strong> {{ $meeting->agenda }}</li>
    </ul>
    <p>Mohon hadir tepat waktu. Terima kasih atas kerja samanya.</p>
    <br>
    <p><em>Salam hangat,<br>Tim PT Liza Makmur Mandiri</em></p>
</body>

</html>
