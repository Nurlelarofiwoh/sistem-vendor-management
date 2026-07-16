<!DOCTYPE html>
<html>

<head>
    <title>E-Survey Kepuasan Klien</title>
</head>

<body style="font-family: sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #4A3018;">Terima Kasih atas Kepercayaan Anda!</h2>
    <p>Halo, <strong>{{ $clientName }}</strong>,</p>
    <p>Event/Proyek <strong>{{ $projectName }}</strong> telah sukses dilaksanakan. Kami dari Vendor Management System
        sangat berterima
        kasih atas kerja sama yang luar biasa ini.</p>

    <p>Untuk membantu kami terus meningkatkan kualitas pelayanan, kami mohon kesediaan waktu Anda (sekitar 2 menit)
        untuk mengisi E-Survey singkat melalui tautan di bawah ini:</p>

    <p style="margin: 25px 0;">
        <a href="{{ $surveyLink }}"
            style="background-color: #8B5A2B; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">
            Isi E-Survey Sekarang</a>
    </p>

    <p>Atau Anda dapat menyalin tautan berikut ke peramban (browser) Anda:<br>
        <a href="{{ $surveyLink }}" style="color: #8B5A2B;">{{ $surveyLink }}</a>
    </p>

    <br>
    <p><em>Salam hangat,<br>Tim PT Liza Makmur Mandiri</em></p>
</body>

</html>
