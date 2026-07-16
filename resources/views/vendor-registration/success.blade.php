<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Sukses — PT Liza Makmur Mandiri</title>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand-primary:   #4A3018;
            --brand-secondary: #8B5A2B;
            --brand-accent:    #D4A373;
            --brand-cream:     #F5EBE1;
            --brand-gradient:  linear-gradient(135deg, #4A3018 0%, #8B5A2B 55%, #D4A373 100%);

            --surface:         #ffffff;
            --surface-alt:     #FAFAFA;
            --border:          #e8ddd2;
            --text-primary:    #4A3018;
            --text-secondary:  #8B5A2B;
            --text-muted:      #a89070;
            --success:         #059669;
            --error:           #dc2626;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f7f1ea;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hero {
            background: var(--brand-gradient);
            padding: 3rem 1.5rem 5.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-company {
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--brand-accent);
            margin-bottom: 0.5rem;
        }

        .hero h1 {
            color: #ffffff;
            font-size: 2.25rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .page-wrapper {
            max-width: 680px;
            width: 100%;
            margin: -4rem auto 3rem;
            padding: 0 1.5rem;
            position: relative;
            z-index: 10;
        }

        .card {
            background: var(--surface);
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 10px 30px rgba(74, 48, 24, 0.08);
            overflow: hidden;
        }

        .card-top-bar {
            height: 6px;
            background: var(--brand-accent);
        }

        .success-content {
            padding: 3.5rem 2.5rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #ecfdf5;
            border: 2px solid #a7f3d0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--success);
            box-shadow: 0 8px 24px rgba(5, 150, 105, 0.1);
        }

        .success-content h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--brand-primary);
            margin-bottom: 0.5rem;
        }

        .success-content p {
            font-size: 0.95rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 1.5rem;
            max-width: 500px;
        }

        .link-share-container {
            width: 100%;
            background: var(--surface-alt);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .link-share-container label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--brand-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .link-input-group {
            display: flex;
            gap: 0.5rem;
        }

        .link-input-group input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.85rem;
            color: var(--brand-primary);
            background: #ffffff;
            outline: none;
            font-family: monospace;
        }

        .btn-copy {
            background: var(--brand-secondary);
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.25rem;
            font-size: 0.85rem;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-copy:hover {
            background: var(--brand-primary);
        }

        .btn-again {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: var(--brand-primary);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.9rem;
            padding: 0.9rem 2.5rem;
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(74, 48, 24, 0.15);
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-again:hover {
            background: var(--brand-secondary);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 48, 24, 0.25);
        }

        .page-footer {
            margin-top: auto;
            padding: 2.5rem 1.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            border-top: 1px solid var(--border);
            background: #ffffff;
        }

        .page-footer a {
            color: var(--brand-secondary);
            text-decoration: none;
            font-weight: 600;
        }

        .page-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <header class="hero">
        <div class="hero-company">🏢 PT Liza Makmur Mandiri</div>
        <h1>Pendaftaran Berhasil</h1>
    </header>

    <div class="page-wrapper">
        <div class="card">
            <div class="card-top-bar"></div>

            <div class="success-content">
                <div class="success-icon">✓</div>
                <h2>Pengajuan Dikirimkan!</h2>
                <p>
                    {{ session('success') ?? 'Pendaftaran Anda berhasil dikirim! Tim kami akan meninjau dan menghubungi Anda dalam 3-5 hari kerja.' }}
                </p>

                <div class="link-share-container">
                    <label for="formUrl">Bagikan / Simpan Link Pendaftaran Mitra Vendor:</label>
                    <div class="link-input-group">
                        <input type="text" readonly id="formUrl" value="{{ route('vendor.register.show') }}">
                        <button class="btn-copy" onclick="copyFormLink()">
                            <span>📋</span> Salin Link
                        </button>
                    </div>
                </div>

                <a href="{{ route('vendor.register.show') }}" class="btn-again">
                    📝 Isi Form Kembali
                </a>
            </div>
        </div>
    </div>

    <footer class="page-footer">
        © {{ date('Y') }} <strong>PT Liza Makmur Mandiri</strong> · Sistem Informasi Strategic Partnership ·
        <a href="{{ route('login') }}">Login Internal</a>
    </footer>

    <script>
        function copyFormLink() {
            const copyText = document.getElementById("formUrl");
            
            // Salin ke clipboard
            navigator.clipboard.writeText(copyText.value).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Link Berhasil Disalin!',
                    text: 'Tautan formulir pendaftaran vendor telah disalin ke papan klip Anda.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }).catch(err => {
                // Fallback copy
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                
                Swal.fire({
                    icon: 'success',
                    title: 'Link Berhasil Disalin!',
                    text: 'Tautan formulir pendaftaran vendor telah disalin ke papan klip Anda.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        }
    </script>
</body>
</html>
