<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Daftarkan bisnis Anda sebagai mitra vendor profesional PT Liza Makmur Mandiri. Bergabunglah dengan jaringan vendor terpercaya se-Jabodetabek.">
    <title>Registrasi Mitra Vendor — PT Liza Makmur Mandiri</title>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            /* ── Palet Sistem Utama PT Liza Makmur Mandiri ── */
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

        /* ── HERO HEADER ── */
        .hero {
            background: var(--brand-gradient);
            padding: 3rem 1.5rem 5.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='40' cy='40' r='28'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 60px;
            background: #f7f1ea;
            clip-path: ellipse(55% 100% at 50% 100%);
        }

        /* Nama Perusahaan di Hero */
        .hero-company {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(212,163,115,0.4);
            color: #D4A373;
            padding: 0.4rem 1.2rem;
            border-radius: 100px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            padding: 0.35rem 1rem;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .hero h1 {
            font-size: clamp(1.8rem, 4vw, 2.75rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 0.75rem;
        }

        .hero p {
            color: rgba(255,255,255,0.8);
            font-size: 1.05rem;
            max-width: 520px;
            margin: 0 auto;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            margin-top: 2rem;
        }

        .stat-item { text-align: center; }

        .stat-item .number {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
        }

        .stat-item .label {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.65);
            font-weight: 500;
        }

        /* ── CARD CONTAINER ── */
        .page-wrapper {
            max-width: 840px;
            margin: -3rem auto 3rem;
            padding: 0 1rem;
            position: relative;
            z-index: 10;
            width: 100%;
        }

        .card {
            background: var(--surface);
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(74,48,24,0.14), 0 4px 16px rgba(74,48,24,0.07);
            overflow: hidden;
            border: 1px solid var(--brand-cream);
        }

        /* ── SUCCESS BANNER ── */
        .success-banner {
            background: linear-gradient(135deg, #059669, #10b981);
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #fff;
        }

        .success-icon {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.5rem;
        }

        .success-banner h2 { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.2rem; }
        .success-banner p  { font-size: 0.9rem; opacity: 0.85; }

        /* ── CARD TOP BORDER (aksen brand) ── */
        .card-top-bar {
            height: 4px;
            background: var(--brand-gradient);
        }

        /* ── FORM HEADER ── */
        .form-header {
            padding: 2rem 2.5rem 1.5rem;
            border-bottom: 1px solid var(--brand-cream);
            background: var(--surface-alt);
        }

        .form-header-inner {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .form-header-icon {
            width: 44px;
            height: 44px;
            background: var(--brand-cream);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .form-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--brand-primary);
        }

        .form-header p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.2rem;
        }

        .step-indicators {
            display: flex;
            gap: 0.4rem;
            margin-top: 1rem;
        }

        .step-dot {
            height: 4px;
            border-radius: 2px;
            flex: 1;
        }

        .step-dot.active   { background: var(--brand-secondary); }
        .step-dot.inactive { background: var(--border); }

        /* ── FORM BODY ── */
        .form-body { padding: 2rem 2.5rem; }

        .section-title {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--brand-secondary);
            margin-bottom: 1rem;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title:first-child { margin-top: 0; }

        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--brand-cream);
        }

        .form-grid { display: grid; gap: 1.25rem; }

        .form-grid.cols-2 { grid-template-columns: 1fr 1fr; }

        @media (max-width: 640px) {
            .form-grid.cols-2 { grid-template-columns: 1fr; }
            .form-body { padding: 1.5rem; }
            .form-header { padding: 1.5rem 1.5rem 1rem; }
            .hero-stats { gap: 1.5rem; }
            .form-footer { padding: 1.25rem 1.5rem 1.5rem; flex-direction: column; align-items: stretch; }
            .btn-submit { justify-content: center; }
        }

        .form-group { display: flex; flex-direction: column; gap: 0.4rem; }

        label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--brand-primary);
        }

        label .required {
            color: var(--error);
            margin-left: 2px;
        }

        label .optional {
            font-weight: 400;
            color: var(--text-muted);
            font-size: 0.78rem;
            margin-left: 4px;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="url"],
        select,
        textarea {
            padding: 0.65rem 0.9rem;
            border: 1.5px solid var(--border);
            border-radius: 0.6rem;
            font-family: inherit;
            font-size: 0.9rem;
            color: var(--brand-primary);
            background: var(--surface);
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
            appearance: none;
        }

        input::placeholder, textarea::placeholder {
            color: var(--text-muted);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--brand-secondary);
            box-shadow: 0 0 0 3px rgba(139,90,43,0.12);
        }

        input.is-invalid, select.is-invalid, textarea.is-invalid {
            border-color: var(--error);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.08);
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%238B5A2B' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.9rem center;
            padding-right: 2.5rem;
        }

        .field-hint {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .invalid-feedback {
            font-size: 0.78rem;
            color: var(--error);
            font-weight: 500;
        }

        /* ── FILE UPLOAD ── */
        .file-upload-area {
            border: 2px dashed var(--brand-cream);
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            background: var(--surface-alt);
        }

        .file-upload-area:hover {
            border-color: var(--brand-secondary);
            background: rgba(139,90,43,0.04);
        }

        .file-upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            padding: 0;
            border: none;
        }

        .file-upload-icon  { font-size: 2rem; margin-bottom: 0.5rem; }
        .file-upload-label { font-size: 0.9rem; font-weight: 600; color: var(--brand-primary); }
        .file-upload-hint  { font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem; }

        /* ── SUBMIT BUTTON ── */
        .form-footer {
            padding: 1.5rem 2.5rem 2rem;
            border-top: 1px solid var(--brand-cream);
            background: var(--surface-alt);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .disclaimer {
            font-size: 0.78rem;
            color: var(--text-muted);
            max-width: 360px;
            line-height: 1.5;
        }

        .disclaimer strong { color: var(--brand-secondary); }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--brand-gradient);
            color: #fff;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 700;
            padding: 0.8rem 2.25rem;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
            box-shadow: 0 4px 16px rgba(74,48,24,0.3);
            white-space: nowrap;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(74,48,24,0.4);
        }

        .btn-submit:active { transform: translateY(0); }
        .btn-submit .icon  { font-size: 1.1rem; }

        /* ── PAGE FOOTER ── */
        .page-footer {
            text-align: center;
            padding: 1.5rem;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: auto;
            border-top: 1px solid #ede0d2;
        }

        .page-footer a {
            color: var(--brand-secondary);
            text-decoration: none;
        }
        .help-icon {
            display: inline-flex;
            align-items: center;
            font-size: 0.72rem;
            color: var(--brand-secondary);
            background: var(--brand-cream);
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
            cursor: pointer;
            border: 1px solid var(--brand-accent);
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .help-icon:hover {
            background: var(--brand-secondary);
            color: #fff;
        }
    </style>
</head>
<body>

{{-- ── HERO HEADER ── --}}
<header class="hero">
    <div class="hero-company">🏢 PT Liza Makmur Mandiri</div>
    <div class="hero-badge">🤝 Program Kemitraan Vendor</div>
    <h1>Daftar Sebagai<br>Mitra Vendor Resmi</h1>
    <p>Bergabung dan raih peluang kolaborasi event profesional bersama tim Strategic Partnership PT Liza Makmur Mandiri.</p>
    <div class="hero-stats">
        <div class="stat-item">
            <div class="number">200+</div>
            <div class="label">Mitra Vendor</div>
        </div>
        <div class="stat-item">
            <div class="number">10 Kota</div>
            <div class="label">Se-Jabodetabek</div>
        </div>
        <div class="stat-item">
            <div class="number">100%</div>
            <div class="label">Event Sukses</div>
        </div>
    </div>
</header>

{{-- ── CARD ── --}}
<div class="page-wrapper">
    <div class="card">
        <div class="card-top-bar"></div>

        {{-- Header Form --}}
            <div class="form-header">
                <div class="form-header-inner">
                    <div class="form-header-icon">📋</div>
                    <div>
                        <h2>Formulir Registrasi Mitra Vendor</h2>
                        <p>Lengkapi data di bawah ini. Kolom bertanda <span style="color:#dc2626;">*</span> wajib diisi.</p>
                    </div>
                </div>
                <div class="step-indicators">
                    <div class="step-dot active"></div>
                    <div class="step-dot active"></div>
                    <div class="step-dot inactive"></div>
                </div>
            </div>

            {{-- Form Utama --}}
            <form id="vendorRegisterForm" action="{{ route('vendor.register.store') }}" method="POST" enctype="multipart/form-data" class="form-body">
                @csrf

                {{-- ── BAGIAN 1: INFORMASI DASAR ── --}}
                <div class="section-title">📋 Informasi Dasar</div>

                <div class="form-grid cols-2">
                    {{-- Nama Vendor --}}
                    <div class="form-group">
                        <label for="nama_vendor">
                            Nama Bisnis / Vendor <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="nama_vendor"
                            name="nama_vendor"
                            value="{{ old('nama_vendor') }}"
                            placeholder="Contoh: Swasana Venue & Catering"
                            class="{{ $errors->has('nama_vendor') ? 'is-invalid' : '' }}"
                            maxlength="255"
                            required
                        >
                        @error('nama_vendor')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kategori Jasa --}}
                    <div class="form-group">
                        <label for="kategori_jasa">
                            Kategori Jasa <span class="required">*</span>
                        </label>
                        <select
                            id="kategori_jasa"
                            name="kategori_jasa"
                            class="{{ $errors->has('kategori_jasa') ? 'is-invalid' : '' }}"
                            required
                        >
                            <option value="" disabled {{ old('kategori_jasa') ? '' : 'selected' }}>— Pilih kategori —</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat }}" {{ old('kategori_jasa') === $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_jasa')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- ── BAGIAN 2: KONTAK & LOKASI ── --}}
                <div class="section-title" style="margin-top:1.75rem;">📞 Kontak & Lokasi</div>

                <div class="form-grid cols-2">
                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">
                            Email Bisnis <span class="required">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="vendor@bisnis.com"
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                            maxlength="255"
                            required
                        >
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- No. Telepon --}}
                    <div class="form-group">
                        <label for="no_telepon">
                            Nomor WhatsApp / Telepon <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="no_telepon"
                            name="no_telepon"
                            value="{{ old('no_telepon') }}"
                            placeholder="08123456789"
                            class="{{ $errors->has('no_telepon') ? 'is-invalid' : '' }}"
                            maxlength="20"
                            required
                        >
                        @error('no_telepon')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="form-group" style="margin-top:1rem;">
                    <label for="alamat">
                        Alamat Operasional <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="alamat"
                        name="alamat"
                        value="{{ old('alamat') }}"
                        placeholder="Contoh: Jl. BSD Raya No. 10, Serpong, Tangerang Selatan"
                        class="{{ $errors->has('alamat') ? 'is-invalid' : '' }}"
                        maxlength="500"
                        required
                    >
                    @error('alamat')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ── BAGIAN 3: PENAWARAN & DOKUMEN ── --}}
                <div class="section-title" style="margin-top:1.75rem;">💰 Penawaran & Dokumen</div>

                <div class="form-grid cols-2">
                    {{-- Estimasi Harga --}}
                    <div class="form-group">
                        <label for="harga">
                            Estimasi Harga Paket (Rp) <span class="required">*</span>
                        </label>
                        <input
                            type="number"
                            id="harga"
                            name="harga"
                            value="{{ old('harga') }}"
                            placeholder="25000000"
                            class="{{ $errors->has('harga') ? 'is-invalid' : '' }}"
                            min="0"
                            required
                        >
                        <span class="field-hint">Contoh: 25000000 untuk Rp 25.000.000</span>
                        @error('harga')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Link Portofolio --}}
                    <div class="form-group">
                        <label for="link_portofolio" style="display: flex; align-items: center; justify-content: space-between;">
                            <span>Link Portofolio <span class="optional">(opsional)</span></span>
                            <span class="help-icon" onclick="showHelpModal('portofolio')">❓ Bantuan</span>
                        </label>
                        <input
                            type="url"
                            id="link_portofolio"
                            name="link_portofolio"
                            value="{{ old('link_portofolio') }}"
                            placeholder="https://instagram.com/vendor_anda"
                            class="{{ $errors->has('link_portofolio') ? 'is-invalid' : '' }}"
                            maxlength="255"
                        >
                        <span class="field-hint">Instagram, website, atau Google Drive</span>
                        @error('link_portofolio')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Upload Proposal --}}
                <div class="form-group" style="margin-top:1rem;">
                    <label for="proposal_file" style="display: flex; align-items: center; justify-content: space-between;">
                        <span>File Proposal <span class="required">*</span></span>
                        <span class="help-icon" onclick="showHelpModal('proposal')">❓ Bantuan</span>
                    </label>
                    <div class="file-upload-area {{ $errors->has('proposal_file') ? 'is-invalid' : '' }}">
                        <input
                            type="file"
                            id="proposal_file"
                            name="proposal_file"
                            accept=".pdf"
                            required
                        >
                        <div class="file-upload-icon">📄</div>
                        <div class="file-upload-label" id="fileLabel">Klik untuk upload atau seret file ke sini</div>
                        <div class="file-upload-hint">Format PDF · Maksimal 5MB</div>
                    </div>
                    @error('proposal_file')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

            </form>

            {{-- Footer Form --}}
            <div class="form-footer">
                <p class="disclaimer">
                    <strong>🔒 Data Anda aman.</strong> Informasi hanya digunakan untuk proses verifikasi mitra vendor PT Liza Makmur Mandiri dan tidak dibagikan ke pihak ketiga.
                </p>
                <button type="submit" form="vendorRegisterForm" class="btn-submit" id="btnKirim">
                    <span class="icon">📨</span>
                    Kirim Pendaftaran
                </button>
            </div>

    </div>
</div>

<footer class="page-footer">
    © {{ date('Y') }} <strong>PT Liza Makmur Mandiri</strong> · Sistem Informasi Strategic Partnership ·
    <a href="{{ route('login') }}">Login Internal</a>
</footer>

<script>
    // Tampilkan notifikasi error dari session jika ada (misal error 419 session expired)
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonColor: '#8B5A2B'
        });
    @endif

    // Tampilkan nama file yang dipilih pada file upload area dan validasi ukuran
    const proposalFileInput = document.getElementById('proposal_file');
    if (proposalFileInput) {
        proposalFileInput.addEventListener('change', function () {
            const file = this.files[0];
            const label = document.getElementById('fileLabel');
            if (file) {
                // Validasi format PDF
                if (file.type !== 'application/pdf') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format File Salah',
                        text: 'Proposal harus berformat PDF.',
                        confirmButtonColor: '#8B5A2B'
                    });
                    this.value = '';
                    if (label) {
                        label.textContent = 'Klik untuk upload atau seret file ke sini';
                    }
                    return;
                }
                
                // Validasi ukuran 5MB (5 * 1024 * 1024)
                if (file.size > 5242880) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar',
                        text: 'Ukuran file proposal maksimal adalah 5MB.',
                        confirmButtonColor: '#8B5A2B'
                    });
                    this.value = '';
                    if (label) {
                        label.textContent = 'Klik untuk upload atau seret file ke sini';
                    }
                    return;
                }

                if (label) {
                    label.textContent = '✅ ' + file.name;
                }
            }
        });
    }

    // Prevent double submit safely using setTimeout and check proposal file is not empty
    const registerForm = document.getElementById('vendorRegisterForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const proposalFile = document.getElementById('proposal_file');
            if (!proposalFile || !proposalFile.files || proposalFile.files.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Proposal Wajib Diunggah',
                    text: 'Silakan lampirkan file proposal bisnis Anda sebelum mengirim pendaftaran.',
                    confirmButtonColor: '#8B5A2B'
                });
                return false;
            }

            const btn = document.getElementById('btnKirim');
            if (btn) {
                setTimeout(() => {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="icon">⏳</span> Mengirim...';
                }, 10);
            }
        });
    }

    function showHelpModal(type) {
        if (type === 'portofolio') {
            Swal.fire({
                title: 'Panduan Link Portofolio',
                html: `
                    <div style="text-align: left; font-size: 0.9rem; line-height: 1.6; color: #4A3018;">
                        <p>Portofolio membantu tim penilai melihat kualitas pekerjaan Anda secara langsung. Anda bisa mengisi kolom ini dengan:</p>
                        <ul style="margin-left: 1.5rem; margin-top: 0.5rem; list-style-type: disc;">
                            <li><strong>Link Instagram bisnis Anda:</strong> contoh: <code>https://instagram.com/nama_vendor</code></li>
                            <li><strong>Link Google Drive:</strong> Simpan foto/video hasil kerja Anda ke dalam folder Google Drive, lalu salin link berstatus <em>"Siapa saja yang memiliki link dapat melihat"</em>.</li>
                            <li><strong>Website portofolio resmi:</strong> contoh: <code>https://www.nama-vendor.com</code></li>
                        </ul>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Saya Mengerti',
                confirmButtonColor: '#8B5A2B'
            });
        } else if (type === 'proposal') {
            Swal.fire({
                title: 'Panduan File Proposal PDF',
                html: `
                    <div style="text-align: left; font-size: 0.9rem; line-height: 1.6; color: #4A3018;">
                        <p>Dokumen proposal wajib dikirimkan dalam format file <strong>PDF</strong> dengan batas ukuran maksimal 5MB. Jika Anda belum memiliki PDF:</p>
                        <ul style="margin-left: 1.5rem; margin-top: 0.5rem; list-style-type: disc;">
                            <li>Gunakan aplikasi pemindai dokumen gratis di HP (misalnya CamScanner atau Adobe Scan) untuk mengambil foto berkas proposal Anda dan menyimpannya sebagai file <strong>PDF</strong>.</li>
                            <li>Buka aplikasi Microsoft Word / Google Docs di komputer/HP Anda, masukkan foto penawaran Anda ke dokumen tersebut, lalu klik <strong>Simpan Sebagai PDF (Save as PDF)</strong>.</li>
                        </ul>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Saya Mengerti',
                confirmButtonColor: '#8B5A2B'
            });
        }
    }
</script>

</body>
</html>
