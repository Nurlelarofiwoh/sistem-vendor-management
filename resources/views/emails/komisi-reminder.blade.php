<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan Komisi — PT Liza Makmur Mandiri</title>
    <style>
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f4f4f4; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #fff; }
        .header {
            background: linear-gradient(135deg, #1e40af, #7c3aed);
            padding: 2rem;
            text-align: center;
            color: #fff;
        }
        .header h1 { font-size: 1.5rem; margin: 0 0 0.25rem; }
        .header p { margin: 0; opacity: 0.8; font-size: 0.9rem; }
        .urgency-banner {
            text-align: center;
            padding: 0.65rem;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        .urgency-1 { background: #dbeafe; color: #1e40af; }
        .urgency-2 { background: #fef3c7; color: #92400e; }
        .urgency-3 { background: #fee2e2; color: #991b1b; }
        .content { padding: 2rem; }
        .greeting { font-size: 1rem; color: #0f172a; margin-bottom: 1rem; }
        .info-box {
            background: #f8fafc;
            border-left: 4px solid #1e40af;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            margin: 1.25rem 0;
        }
        .info-box .label { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
        .info-box .value { font-size: 1rem; font-weight: 700; color: #0f172a; margin-top: 0.2rem; }
        .deadline-box {
            background: linear-gradient(135deg, #1e40af, #7c3aed);
            border-radius: 0.75rem;
            padding: 1.25rem;
            text-align: center;
            color: #fff;
            margin: 1.5rem 0;
        }
        .deadline-box .days { font-size: 2.5rem; font-weight: 800; line-height: 1; }
        .deadline-box .label { font-size: 0.85rem; opacity: 0.8; margin-top: 0.25rem; }
        .steps { margin: 1.25rem 0; }
        .step {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            align-items: flex-start;
        }
        .step-num {
            width: 24px; height: 24px;
            background: #1e40af; color: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
            flex-shrink: 0;
        }
        .step-text { font-size: 0.9rem; color: #374151; padding-top: 2px; }
        .footer {
            background: #f8fafc;
            padding: 1.5rem 2rem;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        .footer p { font-size: 0.78rem; color: #94a3b8; margin: 0.25rem 0; }
        .footer .company { font-weight: 700; color: #64748b; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <h1>💼 PT Liza Makmur Mandiri</h1>
        <p>Sistem Informasi Strategic Partnership</p>
    </div>

    {{-- Urgency Banner --}}
    @if($reminderKe === 1)
        <div class="urgency-banner urgency-1">📋 PENGINGAT PERTAMA — Tagihan Komisi</div>
    @elseif($reminderKe === 2)
        <div class="urgency-banner urgency-2">⚠️ PENGINGAT KEDUA — Harap Segera Tindak Lanjuti</div>
    @else
        <div class="urgency-banner urgency-3">🚨 PERINGATAN TERAKHIR — Batas Waktu Hampir Habis</div>
    @endif

    {{-- Content --}}
    <div class="content">
        <p class="greeting">
            Yth. <strong>{{ $vendor->nama_vendor }}</strong>,
        </p>

        <p style="color:#374151;font-size:0.95rem;line-height:1.7;">
            Kami menginformasikan bahwa Event <strong>{{ $project->nama_proyek }}</strong> telah
            dinyatakan selesai. Berdasarkan perjanjian kerjasama, Anda memiliki kewajiban untuk
            melunasi komisi kepada PT Liza Makmur Mandiri.
        </p>

        <div class="info-box">
            <div class="label">Nama Event</div>
            <div class="value">{{ $project->nama_proyek }}</div>
        </div>

        @if($sisaHari > 0)
        <div class="deadline-box">
            <div class="days">{{ $sisaHari }} Hari</div>
            <div class="label">Sisa waktu pelunasan komisi Anda</div>
        </div>
        @else
        <div class="deadline-box" style="background: linear-gradient(135deg, #dc2626, #991b1b);">
            <div class="days">⏰</div>
            <div class="label">Batas waktu telah habis. Mohon hubungi kami segera.</div>
        </div>
        @endif

        <div class="steps">
            <p style="font-weight:700;color:#0f172a;margin-bottom:0.75rem;">Langkah pelunasan:</p>
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text">Siapkan bukti transfer pembayaran komisi sesuai nominal yang tertera di kontrak.</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text">Kirimkan bukti transfer ke email <strong>finance@lizamakmurmandiri.co.id</strong> dengan subject: <em>Komisi - {{ $project->nama_proyek }}</em></div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text">Konfirmasi via WhatsApp ke tim Finance kami agar segera diproses.</div>
            </div>
        </div>

        <p style="font-size:0.85rem;color:#64748b;margin-top:1.5rem;">
            Apabila Anda sudah melunasi komisi dan email ini masih diterima, mohon abaikan.
            Untuk pertanyaan, hubungi kami di <strong>finance@lizamakmurmandiri.co.id</strong>.
        </p>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p class="company">PT Liza Makmur Mandiri</p>
        <p>Email ini dikirim otomatis oleh sistem. Harap tidak membalas email ini.</p>
        <p>© {{ date('Y') }} Sistem Informasi Strategic Partnership</p>
    </div>

</div>
</body>
</html>
