@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            border-color: #D1D5DB;
            border-radius: 0.5rem;
            height: 42px;
            padding-top: 6px;
            font-size: 0.875rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #8B5A2B !important;
            color: white;
        }
    </style>

    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-[#4A3018]">Panel Validasi Vendor & Logistik</h2>
            <div class="flex gap-2">
                <a href="{{ route('clients.recommendation.pdf', $client->id) }}"
                    class="px-4 py-2 bg-[#F5EBE1] text-[#8B5A2B] border border-[#8B5A2B] font-bold rounded-lg hover:bg-[#D4A373] hover:text-white transition-colors uppercase tracking-wide text-xs flex items-center shadow-sm">
                    Unduh PDF Rekomendasi
                </a>
                <a href="{{ route('clients.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors uppercase tracking-wide text-xs flex items-center shadow-sm">
                    Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div
                class="p-4 mb-6 text-sm font-bold text-green-700 bg-green-100 border border-green-200 rounded-lg shadow-sm">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 mb-6 text-sm font-bold text-red-700 bg-red-100 border border-red-200 rounded-lg shadow-sm">
                ❌ Gagal: {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="col-span-1 space-y-6 h-fit">
                <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl p-6">
                    <h3 class="font-bold text-[#4A3018] border-b pb-2 mb-4 text-xl">Data Permintaan Klien</h3>
                    <div class="space-y-3 text-sm">
                        <p><strong class="block text-gray-400 text-xs uppercase tracking-wide">Nama Klien</strong>
                            <span class="text-lg font-bold text-[#8B5A2B]">{{ $client->nama_klien }}</span>
                        </p>
                        <p><strong class="block text-gray-400 text-xs uppercase tracking-wide">Instansi</strong>
                            <span class="font-medium text-gray-700">{{ $client->instansi }}</span>
                        </p>
                        <p><strong class="block text-gray-400 text-xs uppercase tracking-wide">Kontak</strong>
                            <span class="font-medium text-gray-700">{{ $client->no_telepon }}</span>
                        </p>
                        <p><strong class="block text-gray-400 text-xs uppercase tracking-wide">Budget Event</strong>
                            <span class="font-bold text-green-700">Rp
                                {{ $client->budget ? number_format($client->budget, 0, ',', '.') : 'Belum Diatur' }}</span>
                        </p>
                        <p><strong class="block text-gray-400 text-xs uppercase tracking-wide">Pelaksanaan</strong>
                            <span
                                class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($client->tanggal_acara)->translatedFormat('d F Y') }}</span>
                        </p>
                        <p><strong class="block text-gray-400 text-xs uppercase tracking-wide">Lokasi Utama</strong>
                            <span class="font-medium text-gray-700">{{ $client->tempat_acara }}</span>
                        </p>
                    </div>

                    <h4 class="font-bold text-[#8B5A2B] mt-6 mb-2 border-t border-[#F5EBE1] pt-4">Rincian Kebutuhan:</h4>
                    <div
                        class="bg-gray-50 p-3 rounded text-xs font-mono border border-gray-200 whitespace-pre-wrap text-gray-700 max-h-64 overflow-y-auto">
                        {{ $client->kebutuhan_klien }}
                    </div>
                </div>
            </div>

            <div class="col-span-2 space-y-6">

                <div class="bg-[#FAFAFA] border-2 border-[#D4A373] shadow-md rounded-xl p-6">
                    <h3 class="font-black text-[#4A3018] mb-4 text-lg uppercase tracking-wide">Alur Validasi Eksekusi</h3>

                    @hasrole('admin_cs')
                        @if ($client->vendors->count() == 0)
                            <form action="{{ route('clients.pilihVendor', $client->id) }}" method="POST"
                                x-data="{ vendors: [{ id: Date.now() }] }">
                                @csrf
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg mb-4">
                                    <p class="text-sm text-blue-800 font-bold mb-1 uppercase tracking-wide">Pengajuan Vendor
                                        Final</p>
                                    <p class="text-xs text-blue-600 mb-4">Pilih vendor yang telah disepakati bersama klien.</p>

                                    <template x-for="(v, index) in vendors" :key="v.id">
                                        <div class="flex gap-2 mb-3">
                                            <div class="flex-1" x-init="setTimeout(() => { $($el.querySelector('select')).select2({ placeholder: '-- Ketik nama vendor atau daerah --', width: '100%' }); }, 100)">
                                                <select name="vendor_ids[]" class="w-full text-sm" required>
                                                    <option value=""></option>
                                                    @foreach (\App\Models\Vendor::getAvailableForClient($client) as $vnd)
                                                        <option value="{{ $vnd->id }}">[{{ $vnd->kategori_jasa }}] -
                                                            {{ $vnd->nama_vendor }} (Rp {{ number_format($vnd->harga, 0, ',', '.') }} · {{ $vnd->alamat }}){{ $vnd->warning_status ? ' [' . $vnd->warning_status . ']' : '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="button" @click="vendors.splice(index, 1)" x-show="vendors.length > 1"
                                                class="px-3 py-2 bg-red-100 text-red-600 rounded font-bold hover:bg-red-200 text-sm transition-colors">X</button>
                                        </div>
                                    </template>

                                    <button type="button" @click="vendors.push({id: Date.now()})"
                                        class="text-xs font-bold text-blue-700 bg-white border border-blue-300 px-3 py-1.5 rounded hover:bg-blue-100 transition-colors uppercase tracking-wide">
                                        + Tambah Vendor Lain
                                    </button>
                                </div>

                                <button type="submit"
                                    class="w-full bg-[#8B5A2B] text-white font-bold py-3 rounded-lg hover:bg-[#4A3018] shadow-md transition-colors uppercase tracking-wide text-sm">
                                    Ajukan ke Divisi Operasional
                                </button>
                            </form>
                        @elseif($client->vendors->count() > 0 && !$client->is_vendor_acc)
                            @if ($client->catatan_operasional)
                                <div class="bg-red-50 p-5 rounded-lg border border-red-200 mb-6">
                                    <p class="text-sm font-bold text-red-800 mb-1 uppercase tracking-wide">❌ Pengajuan Vendor Ditolak</p>
                                    <p class="text-xs text-red-700 font-medium">Alasan: {{ $client->catatan_operasional }}</p>
                                    <p class="text-[11px] text-gray-500 mt-2">Silakan pilih ulang/sesuaikan vendor di bawah dan ajukan kembali.</p>
                                </div>

                                <form action="{{ route('clients.pilihVendor', $client->id) }}" method="POST"
                                    x-data="{ vendors: [
                                        @foreach($client->vendors as $v)
                                            { id: {{ $v->id }}, val: '{{ $v->id }}' },
                                        @endforeach
                                    ] }">
                                    @csrf
                                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg mb-4">
                                        <p class="text-sm text-blue-800 font-bold mb-1 uppercase tracking-wide">Pilih Ulang Vendor</p>
                                        <p class="text-xs text-blue-600 mb-4">Ubah vendor agar sesuai dengan catatan penolakan.</p>

                                        <template x-for="(v, index) in vendors" :key="v.id">
                                            <div class="flex gap-2 mb-3">
                                                <div class="flex-1" x-init="setTimeout(() => { 
                                                    let sel = $($el.querySelector('select'));
                                                    sel.select2({ placeholder: '-- Ketik nama vendor atau daerah --', width: '100%' });
                                                    if(v.val) { sel.val(v.val).trigger('change'); }
                                                }, 100)">
                                                    <select name="vendor_ids[]" class="w-full text-sm" required>
                                                        <option value=""></option>
                                                        @foreach (\App\Models\Vendor::getAvailableForClient($client) as $vnd)
                                                            <option value="{{ $vnd->id }}">[{{ $vnd->kategori_jasa }}] -
                                                                {{ $vnd->nama_vendor }} (Rp {{ number_format($vnd->harga, 0, ',', '.') }} · {{ $vnd->alamat }}){{ $vnd->warning_status ? ' [' . $vnd->warning_status . ']' : '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="button" @click="vendors.splice(index, 1)" x-show="vendors.length > 1"
                                                    class="px-3 py-2 bg-red-100 text-red-600 rounded font-bold hover:bg-red-200 text-sm transition-colors">X</button>
                                            </div>
                                        </template>

                                        <button type="button" @click="vendors.push({id: Date.now(), val: ''})"
                                            class="text-xs font-bold text-blue-700 bg-white border border-blue-300 px-3 py-1.5 rounded hover:bg-blue-100 transition-colors uppercase tracking-wide">
                                            + Tambah Vendor Lain
                                        </button>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-[#8B5A2B] text-white font-bold py-3 rounded-lg hover:bg-[#4A3018] shadow-md transition-colors uppercase tracking-wide text-sm">
                                        Ajukan Ulang ke Divisi Operasional
                                    </button>
                                </form>
                            @else
                                <div class="bg-yellow-50 p-5 rounded-lg border border-yellow-200">
                                    <p class="text-sm font-bold text-yellow-800 mb-2 uppercase tracking-wide">Menunggu Validasi
                                        Operasional</p>
                                    <p class="text-xs text-yellow-700 mb-2">Vendor yang Anda ajukan sedang dicek ketersediaan
                                        logistiknya:</p>
                                    <ul class="list-disc list-inside text-xs text-yellow-800 ml-2 space-y-1">
                                        @foreach ($client->vendors as $v)
                                            <li><strong class="text-[#4A3018]">{{ $v->kategori_jasa }}</strong> -
                                                {{ $v->nama_vendor }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @else
                            <div class="bg-green-50 p-5 rounded-lg border border-green-200 flex items-center gap-4">
                                <div class="text-3xl">✓</div>
                                <div>
                                    <p class="text-sm font-bold text-green-800 uppercase tracking-wide">Logistik Terkonfirmasi &
                                        Di-ACC</p>
                                    <p class="text-xs text-green-700 mt-1">Sistem operasional dan finance telah berjalan.</p>
                                </div>
                            </div>
                        @endif
                    @endhasrole

                    @hasrole('manager_operasional')
                        @if ($client->vendors->count() == 0)
                            <div class="p-4 bg-gray-50 border border-dashed border-gray-300 rounded-lg text-center">
                                <p class="text-sm text-gray-500 font-bold uppercase tracking-wide">Menunggu Admin CS...</p>
                                <p class="text-xs text-gray-400 mt-1">Admin CS sedang berdiskusi dengan klien untuk
                                    memfinalisasi vendor.</p>
                            </div>
                        @elseif($client->vendors->count() > 0 && !$client->is_vendor_acc)
                            <div class="bg-blue-50 p-5 border border-blue-200 rounded-lg shadow-sm" x-data="{ mode: 'select' }">
                                <div class="mb-4">
                                    <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-2">Validasi Logistik & Vendor dari CS:</p>
                                    <p class="text-xs text-blue-700 mb-4 font-medium">Lakukan pengecekan ketersediaan jadwal/fasilitas vendor di bawah. Jika tidak tersedia, Anda wajib menggantinya sebelum memberikan ACC, atau klik Tolak disertai catatan alasan.</p>

                                    @if ($client->catatan_operasional)
                                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700">
                                            <strong>Catatan Penolakan Terakhir:</strong> {{ $client->catatan_operasional }}
                                        </div>
                                    @endif

                                    <!-- FORM ACC / EDIT VENDOR -->
                                    <div x-show="mode === 'select'">
                                        <form action="{{ route('clients.accVendor', $client->id) }}" method="POST">
                                            @csrf
                                            <div class="space-y-3 mb-4">
                                                @foreach ($client->vendors as $selectedVendor)
                                                    <div class="w-full bg-white p-3 rounded-lg border border-blue-100 shadow-sm">
                                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">{{ $selectedVendor->kategori_jasa }}</label>
                                                        <select name="vendor_ids[]" class="w-full text-sm select2-operasional" required>
                                                            <option value=""></option>
                                                            @foreach (\App\Models\Vendor::getAvailableForClient($client) as $vnd)
                                                                <option value="{{ $vnd->id }}"
                                                                    {{ $vnd->id == $selectedVendor->id ? 'selected' : '' }}>
                                                                    [{{ $vnd->kategori_jasa }}] - {{ $vnd->nama_vendor }} (Rp {{ number_format($vnd->harga, 0, ',', '.') }} · {{ $vnd->alamat }}){{ $vnd->warning_status ? ' [' . $vnd->warning_status . ']' : '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="space-y-3 pt-4 border-t border-blue-200 mb-4">
                                                <label class="block text-xs font-bold text-blue-800 uppercase tracking-wide">Beri Nama Event/Proyek (Untuk Logistik & Finance):</label>
                                                <input type="text" name="nama_proyek" placeholder="Contoh: Wedding Budi & Ani - Bintaro" required
                                                    class="w-full px-4 py-2 border border-blue-300 rounded-lg text-sm focus:ring-blue-500">
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <button type="submit"
                                                    onclick="return confirm('Apakah semua fasilitas logistik telah dipastikan tersedia?')"
                                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-sm transition-colors text-xs uppercase tracking-wide">
                                                    ✅ ACC Logistik & Terbitkan
                                                </button>
                                                <button type="button" @click="mode = 'reject'"
                                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg shadow-sm transition-colors text-xs uppercase tracking-wide">
                                                    ❌ Tolak Pengajuan
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- FORM TOLAK DENGAN CATATAN -->
                                    <div x-show="mode === 'reject'" style="display: none;">
                                        <form action="{{ route('clients.rejectVendor', $client->id) }}" method="POST">
                                            @csrf
                                            <div class="p-4 bg-white border border-red-200 rounded-lg mb-4">
                                                <h4 class="text-sm font-bold text-red-700 mb-2 uppercase tracking-wide">Alasan Penolakan Logistik/Vendor</h4>
                                                <p class="text-xs text-gray-500 mb-3">Tuliskan catatan alasan mengapa pengajuan vendor ini tidak disetujui agar CS dapat menyesuaikan.</p>
                                                
                                                <textarea name="catatan_operasional" rows="4" required minlength="5"
                                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400 outline-none resize-none"
                                                    placeholder="Tulis alasan penolakan..."></textarea>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <button type="submit"
                                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg shadow-sm transition-colors text-xs uppercase tracking-wide">
                                                    Konfirmasi Tolak
                                                </button>
                                                <button type="button" @click="mode = 'select'"
                                                    class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 rounded-lg shadow-sm transition-colors text-xs uppercase tracking-wide">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            @php
                                // Ambil data proyek untuk mengecek status
                                $proyek = \App\Models\Project::where('client_id', $client->id)->first();
                            @endphp

                            @if ($proyek && in_array($proyek->status_proyek, ['Finish Event', 'selesai', 'Transaksi Komplit']))
                                <div
                                    class="bg-gray-100 p-5 rounded-lg border border-gray-300 flex items-center gap-4 shadow-sm">
                                    <div class="text-3xl text-gray-500">✓</div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 uppercase tracking-wide">Event Telah Selesai
                                        </p>
                                        <p class="text-xs text-gray-600 mt-1">Acara telah usai dan tautan E-Survey telah
                                            dikirimkan ke email Klien.</p>
                                    </div>
                                </div>
                            @else
                                <div class="bg-green-50 p-5 rounded-lg border border-green-200 shadow-sm">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="text-3xl">✓</div>
                                        <div>
                                            <p class="text-sm font-bold text-green-800 uppercase tracking-wide">Logistik
                                                Tervalidasi (Event Aktif)</p>
                                            <p class="text-xs text-green-700 mt-1">Acara sedang berjalan. Pantau eksekusi di
                                                lapangan.</p>
                                        </div>
                                    </div>

                                    @if ($proyek)
                                        <form action="{{ route('projects.complete', $proyek->id) }}" method="POST"
                                            class="border-t border-green-200 pt-4 mt-2">
                                            @csrf @method('PUT')
                                            <button type="submit"
                                                onclick="return confirm('Apakah acara benar-benar sudah selesai dilaksanakan? Sistem akan langsung mengirimkan email E-Survey secara otomatis ke Klien.')"
                                                class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 shadow-md transition-colors text-xs uppercase tracking-wide flex items-center justify-center gap-2 cursor-pointer">
                                                Selesaikan Event & Kirim E-Survey
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        @endif
                    @endhasrole

                    @hasrole('partnership')
                        @if ($client->vendors->count() > 0 && $client->is_vendor_acc)
                            <div class="bg-green-50 p-5 rounded-lg border border-green-200">
                                <p class="text-sm font-black text-green-800 mb-2 uppercase tracking-wide">Event Telah Berjalan
                                </p>
                                <p class="text-xs text-green-700 mb-2">Vendor yang telah disetujui oleh Div. Operasional:</p>
                                <ul class="list-disc list-inside text-xs text-green-800 ml-2 mb-3 space-y-1">
                                    @foreach ($client->vendors as $v)
                                        <li><strong class="text-[#4A3018]">{{ $v->kategori_jasa }}</strong> -
                                            {{ $v->nama_vendor }}</li>
                                    @endforeach
                                </ul>
                                <p class="text-[11px] text-green-600 italic font-medium">Anda dapat mengatur jadwal Technical
                                    Meeting (TM) di menu khusus.</p>
                            </div>
                        @else
                            <div class="p-4 bg-gray-50 border border-dashed border-gray-300 rounded-lg text-center">
                                <p class="text-sm text-gray-500 font-bold uppercase tracking-wide">Proses Pra-Event</p>
                                <p class="text-xs text-gray-400 mt-1">Sistem sedang menunggu pemilihan vendor oleh CS dan
                                    validasi logistik oleh Divisi Operasional.</p>
                            </div>
                        @endif
                    @endhasrole

                </div>

                <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl p-6">
                    <h3 class="font-black text-[#4A3018] mb-4 text-lg border-b pb-2 uppercase tracking-wide">Rekomendasi
                        Sistem Berdasarkan Lokasi</h3>

                    @forelse($rekomendasiPerKategori as $kategori => $vendors)
                        <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
                            <h4
                                class="font-bold text-white bg-[#8B5A2B] inline-block px-3 py-1 rounded mb-3 text-xs shadow-sm uppercase tracking-wide">
                                Kategori: {{ $kategori }}
                            </h4>
                            <div class="grid grid-cols-1 gap-2">
                                @forelse ($vendors as $index => $v)
                                    <div
                                        class="flex items-center justify-between border border-gray-200 p-2.5 rounded bg-white hover:border-[#D4A373] transition-colors">
                                        <div>
                                            <p class="font-bold text-[#4A3018] text-sm">#{{ $index + 1 }}.
                                                {{ $v->nama_vendor }}</p>
                                            <p class="text-[11px] text-green-700 font-bold mt-0.5">Rp
                                                {{ $v->harga ? number_format($v->harga, 0, ',', '.') : 'Harga Belum Diatur' }}
                                            </p>
                                            @if(isset($v->warning_status))
                                                <span class="inline-block px-2 py-0.5 text-[9px] font-bold text-white bg-red-600 rounded mt-1">
                                                    ⚠️ {{ $v->warning_status }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-yellow-600">Rating:
                                                {{ number_format($v->rating, 1) }} / 5.0</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-red-500 italic font-medium">Tidak ada vendor di wilayah ini
                                        untuk kategori tersebut.</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500 italic text-sm font-medium">Sistem tidak menemukan rekomendasi vendor
                                di wilayah yang dipilih klien.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 untuk form milik Operasional
            $('.select2-operasional').select2({
                placeholder: "-- Ketik nama vendor atau daerah untuk mengganti --",
                width: '100%'
            });
        });
    </script>
@endsection
