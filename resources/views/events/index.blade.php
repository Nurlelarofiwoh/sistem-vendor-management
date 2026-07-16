@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-[#4A3018]">Management Event</h2>
        <p class="text-[#8B5A2B] mt-1 font-medium">Pantau dan kelola seluruh alur event dari validasi pembayaran hingga penutupan.</p>
    </div>

    <div x-data="{ activeFilter: 'all' }">
        {{-- LEGEND ALUR STATUS --}}
        <div class="mb-6 p-4 bg-[#FDF8F3] border border-[#F5EBE1] rounded-xl">
            <div class="flex justify-between items-center mb-2">
                <p class="text-xs font-bold text-[#8B5A2B] uppercase tracking-wide">Filter Status Event (Klik untuk memfilter):</p>
                <button @click="activeFilter = 'all'" 
                    class="text-xs font-bold text-[#8B5A2B] hover:text-[#4A3018] underline uppercase tracking-wide"
                    x-show="activeFilter !== 'all'">
                    Reset Filter (Tampilkan Semua)
                </button>
            </div>
            <div class="flex flex-wrap gap-2 text-[10px] font-bold">
                <button type="button" @click="activeFilter = 'all'"
                    :class="activeFilter === 'all' ? 'bg-[#8B5A2B] text-white border-[#8B5A2B]' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border-gray-200'"
                    class="px-3 py-1.5 rounded-full border transition-all cursor-pointer uppercase tracking-wider">
                    Semua
                </button>
                @foreach ([
                    'Berjalan'           => 'border-blue-200 active:bg-blue-600',
                    'Perlu Invoice'      => 'border-orange-200 active:bg-orange-600',
                    'Invoice Tersedia'   => 'border-sky-200 active:bg-sky-600',
                    'Menunggu Verifikasi'=> 'border-yellow-200 active:bg-yellow-600',
                    'Gagal Verifikasi'   => 'border-red-200 active:bg-red-600',
                    'Terverifikasi'      => 'border-teal-200 active:bg-teal-600',
                    'Technical Meeting'  => 'border-indigo-200 active:bg-indigo-600',
                    'Finish Event'       => 'border-green-200 active:bg-green-600',
                    'Transaksi Komplit'  => 'border-purple-200 active:bg-purple-600',
                ] as $statusVal => $btnStyles)
                    @php
                        $badgeColors = match($statusVal) {
                            'Berjalan'            => ['bg-blue-100 text-blue-700', 'bg-blue-600 text-white'],
                            'Perlu Invoice'       => ['bg-orange-100 text-orange-700', 'bg-orange-600 text-white'],
                            'Invoice Tersedia'    => ['bg-sky-100 text-sky-700', 'bg-sky-600 text-white'],
                            'Menunggu Verifikasi' => ['bg-yellow-100 text-yellow-700', 'bg-yellow-600 text-white'],
                            'Gagal Verifikasi'    => ['bg-red-100 text-red-700', 'bg-red-600 text-white'],
                            'Terverifikasi'       => ['bg-teal-100 text-teal-700', 'bg-teal-600 text-white'],
                            'Technical Meeting'   => ['bg-indigo-100 text-indigo-700', 'bg-indigo-600 text-white'],
                            'Finish Event'        => ['bg-green-100 text-green-700', 'bg-green-600 text-white'],
                            'Transaksi Komplit'   => ['bg-purple-100 text-purple-700', 'bg-purple-600 text-white'],
                            default               => ['bg-gray-100 text-gray-700', 'bg-gray-600 text-white'],
                        };
                    @endphp
                    <button type="button" @click="activeFilter = '{{ $statusVal }}'"
                        :class="activeFilter === '{{ $statusVal }}' ? '{{ $badgeColors[1] }} border-transparent' : '{{ $badgeColors[0] }} {{ $btnStyles }}'"
                        class="px-3 py-1.5 rounded-full border transition-all cursor-pointer uppercase tracking-wider">
                        {{ $statusVal }}
                    </button>
                @endforeach
            </div>
        </div>

    {{-- DAFTAR EVENT --}}
    <div class="space-y-5">
        @forelse($events as $event)
            <div class="event-card bg-white border border-[#F5EBE1] rounded-2xl shadow-sm overflow-hidden" 
                 x-show="activeFilter === 'all' || activeFilter === '{{ $event->status_proyek }}'"
                 x-data="{ modalTolak: false, modalBukti: false }"
                 data-status="{{ $event->status_proyek }}">

                {{-- HEADER EVENT --}}
                <div class="flex items-center justify-between px-6 py-4 bg-[#FDF8F3] border-b border-[#F5EBE1]">
                    <div>
                        <h3 class="text-lg font-black text-[#4A3018]">{{ $event->nama_proyek }}</h3>
                        <p class="text-sm text-[#8B5A2B] font-medium">
                            Klien: <strong>{{ $event->client->nama_klien ?? '-' }}</strong>
                            @if ($event->client && $event->client->tanggal_acara)
                                &nbsp;·&nbsp; 📅 {{ \Carbon\Carbon::parse($event->client->tanggal_acara)->translatedFormat('d M Y') }}
                            @endif
                            @if ($event->client && $event->client->tempat_acara)
                                &nbsp;·&nbsp; 📍 {{ $event->client->tempat_acara }}
                            @endif
                        </p>
                    </div>

                    @php
                        $badge = match($event->status_proyek) {
                            'Berjalan'            => ['Berjalan', 'bg-blue-100 text-blue-700 border-blue-200'],
                            'Perlu Invoice'       => ['Perlu Invoice', 'bg-orange-100 text-orange-700 border-orange-200'],
                            'Invoice Tersedia'    => ['Invoice Tersedia', 'bg-sky-100 text-sky-700 border-sky-200'],
                            'Menunggu Verifikasi' => ['Menunggu Verifikasi', 'bg-yellow-100 text-yellow-700 border-yellow-200'],
                            'Gagal Verifikasi'    => ['Gagal Verifikasi', 'bg-red-100 text-red-700 border-red-200'],
                            'Terverifikasi'       => ['Terverifikasi', 'bg-teal-100 text-teal-700 border-teal-200'],
                            'Technical Meeting'   => ['Technical Meeting', 'bg-indigo-100 text-indigo-700 border-indigo-200'],
                            'Finish Event'        => ['Finish Event', 'bg-green-100 text-green-700 border-green-200'],
                            'Transaksi Komplit'   => ['Transaksi Komplit', 'bg-purple-100 text-purple-700 border-purple-200'],
                            default               => [$event->status_proyek, 'bg-gray-100 text-gray-600 border-gray-200'],
                        };
                    @endphp
                    <span class="px-4 py-1.5 text-[11px] font-black border rounded-full uppercase tracking-wide {{ $badge[1] }}">
                        {{ $badge[0] }}
                    </span>
                </div>

                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- KOLOM KIRI: INFO VENDOR + CATATAN --}}
                        <div class="md:col-span-1">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-2">Vendor Terikat</p>
                            @if ($event->client && $event->client->vendors->count() > 0)
                                <ul class="space-y-1">
                                    @foreach ($event->client->vendors as $v)
                                        <li class="text-sm text-gray-700">
                                            <span class="font-bold text-[#4A3018]">{{ $v->nama_vendor }}</span>
                                            <span class="text-gray-400 text-xs"> · {{ $v->kategori_jasa }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-400 italic">Belum ada vendor</p>
                            @endif

                            @if ($event->nominal_invoice)
                                <div class="mt-3 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                    <p class="text-[10px] font-bold text-orange-600 uppercase mb-1">Nominal Invoice Diajukan:</p>
                                    <p class="text-sm font-black text-orange-700">Rp {{ number_format($event->nominal_invoice, 0, ',', '.') }}</p>
                                </div>
                            @endif

                            @if ($event->catatan_finance)
                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-[10px] font-bold text-red-600 uppercase mb-1">Catatan Finance:</p>
                                    <p class="text-xs text-red-700">{{ $event->catatan_finance }}</p>
                                </div>
                            @endif

                            @if ($event->tanggal_komisi_jatuh_tempo && !$event->tanggal_komisi_dibayar)
                                @php
                                    $hariSisa = now()->diffInDays($event->tanggal_komisi_jatuh_tempo, false);
                                @endphp
                                <div class="mt-3 p-3 {{ $hariSisa < 0 ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200' }} border rounded-lg">
                                    <p class="text-[10px] font-bold {{ $hariSisa < 0 ? 'text-red-600' : 'text-yellow-700' }} uppercase mb-1">Komisi Vendor</p>
                                    <p class="text-xs {{ $hariSisa < 0 ? 'text-red-700' : 'text-yellow-800' }}">
                                        @if ($hariSisa < 0)
                                            ⚠️ Terlambat {{ abs($hariSisa) }} hari!
                                        @else
                                            Jatuh tempo: {{ $event->tanggal_komisi_jatuh_tempo->translatedFormat('d M Y') }} ({{ $hariSisa }} hari lagi)
                                        @endif
                                    </p>
                                </div>
                            @elseif ($event->tanggal_komisi_dibayar)
                                <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-[10px] font-bold text-green-600 uppercase mb-1">Komisi Vendor</p>
                                    <p class="text-xs text-green-700">✅ Dibayar {{ $event->tanggal_komisi_dibayar->translatedFormat('d M Y') }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- KOLOM TENGAH: DOKUMEN --}}
                        <div class="md:col-span-1">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-2">Dokumen</p>
                            <div class="space-y-2">
                                @if ($event->invoice_path)
                                    <a href="{{ Storage::url($event->invoice_path) }}" target="_blank"
                                        class="flex items-center gap-2 text-xs font-bold text-blue-700 hover:text-blue-900 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                        📄 Lihat Invoice
                                    </a>
                                @else
                                    <p class="text-xs text-gray-400 italic p-2 bg-gray-50 border border-dashed border-gray-200 rounded-lg">Invoice belum tersedia</p>
                                @endif

                                @if ($event->bukti_pembayaran_path)
                                    <a href="{{ Storage::url($event->bukti_pembayaran_path) }}" target="_blank"
                                        class="flex items-center gap-2 text-xs font-bold text-green-700 hover:text-green-900 p-2 bg-green-50 border border-green-200 rounded-lg">
                                        🧾 Lihat Bukti Transfer
                                    </a>
                                @else
                                    <p class="text-xs text-gray-400 italic p-2 bg-gray-50 border border-dashed border-gray-200 rounded-lg">Bukti transfer belum diupload</p>
                                @endif
                            </div>
                        </div>

                        {{-- KOLOM KANAN: TOMBOL AKSI (Per Role & Status) --}}
                        <div class="md:col-span-1">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-2">Aksi</p>
                            <div class="space-y-2">

                                {{-- === ADMIN CS === --}}
                                @hasrole('admin_cs')
                                    @if ($event->status_proyek === 'Berjalan')
                                        <form action="{{ route('events.updateStatus', $event->id) }}" method="POST" class="space-y-2">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="aksi" value="minta_invoice">
                                            
                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Nominal Invoice (Rp):</label>
                                                <input type="number" name="nominal_invoice" min="0" required
                                                    value="{{ $event->client->budget ?? '' }}"
                                                    placeholder="Contoh: 15000000"
                                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-orange-500 focus:border-orange-500 outline-none">
                                                <p class="text-[10px] text-gray-400 mt-0.5">Budget awal: Rp {{ $event->client->budget ? number_format($event->client->budget, 0, ',', '.') : '-' }}</p>
                                            </div>

                                            <button type="submit"
                                                onclick="return confirm('Minta Finance menerbitkan invoice untuk event ini?')"
                                                class="w-full px-5 py-3.5 text-sm font-black text-white bg-[#4A3018] hover:bg-[#8B5A2B] rounded-xl transition-all shadow-md hover:shadow-lg uppercase tracking-wider text-center cursor-pointer">
                                                Ajukan Invoice ke Finance
                                            </button>
                                        </form>
                                    @elseif ($event->status_proyek === 'Invoice Tersedia')
                                        <p class="text-xs font-bold text-sky-700 bg-sky-50 border border-sky-200 rounded-lg p-3">
                                            Invoice sudah tersedia. Minta klien transfer dan upload buktinya di bawah.
                                        </p>
                                        <button type="button" @click="modalBukti = true"
                                            class="w-full px-4 py-2.5 text-xs font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors uppercase tracking-wide cursor-pointer">
                                            Upload Bukti Pembayaran Klien
                                        </button>
                                    @elseif ($event->status_proyek === 'Gagal Verifikasi')
                                        <p class="text-xs text-red-600 bg-red-50 border border-red-200 rounded-lg p-3 mb-2">
                                            Pembayaran ditolak Finance. Upload ulang bukti yang valid.
                                        </p>
                                        <button type="button" @click="modalBukti = true"
                                            class="w-full px-4 py-2.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors uppercase tracking-wide cursor-pointer">
                                            Upload Ulang Bukti Transfer
                                        </button>
                                    @endif
                                @endhasrole

                                {{-- === FINANCE === --}}
                                @hasrole('finance')
                                    @if ($event->status_proyek === 'Perlu Invoice')
                                        <form action="{{ route('events.updateStatus', $event->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="aksi" value="upload_invoice">
                                            <label class="block text-xs font-bold text-gray-600 mb-1">Upload File Invoice (PDF/JPG):</label>
                                            <input type="file" name="invoice_file" accept=".pdf,.jpg,.jpeg,.png" required
                                                class="w-full text-xs border border-gray-300 rounded-lg p-2 mb-2 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:bg-[#8B5A2B] file:text-white file:text-xs file:font-bold cursor-pointer">
                                            <button type="submit"
                                                class="w-full px-4 py-2.5 text-xs font-bold text-white bg-[#8B5A2B] hover:bg-[#4A3018] rounded-lg transition-colors uppercase tracking-wide cursor-pointer">
                                                Upload Invoice
                                            </button>
                                        </form>
                                    @elseif ($event->status_proyek === 'Menunggu Verifikasi')
                                        <form action="{{ route('events.updateStatus', $event->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="aksi" value="verifikasi_pembayaran">
                                            <input type="hidden" name="keputusan" value="terima">
                                            <button type="submit"
                                                onclick="return confirm('Verifikasi pembayaran sebagai VALID? Partnership akan mendapat notifikasi TM, Finance untuk bayar fee vendor.')"
                                                class="w-full px-4 py-2.5 text-xs font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors uppercase tracking-wide cursor-pointer">
                                                Verifikasi — Pembayaran Valid
                                            </button>
                                        </form>
                                        <button type="button" @click="modalTolak = true"
                                            class="w-full px-4 py-2.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors uppercase tracking-wide cursor-pointer">
                                            Tolak Pembayaran
                                        </button>
                                    @elseif ($event->status_proyek === 'Finish Event')
                                        <form action="{{ route('events.updateStatus', $event->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="aksi" value="transaksi_komplit">
                                            <button type="submit"
                                                onclick="return confirm('Konfirmasi komisi vendor sudah diterima? Siklus event akan ditutup permanen.')"
                                                class="w-full px-5 py-3.5 text-sm font-black text-white bg-green-600 hover:bg-green-700 rounded-xl transition-all shadow-md hover:shadow-lg uppercase tracking-wider text-center cursor-pointer">
                                                Payment Komplit
                                            </button>
                                        </form>
                                    @endif
                                @endhasrole

                                {{-- === PARTNERSHIP === --}}
                                @hasrole('partnership')
                                    @if ($event->status_proyek === 'Terverifikasi')
                                        <a href="{{ route('technical-meetings.create') }}"
                                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors uppercase tracking-wide text-center">
                                            Buat Jadwal Technical Meeting
                                        </a>
                                    @elseif ($event->status_proyek === 'Technical Meeting')
                                        <div class="p-3 bg-indigo-50 border border-indigo-200 rounded-lg text-center">
                                            <p class="text-xs font-bold text-indigo-700">TM Sedang Berlangsung</p>
                                            <a href="{{ route('technical-meetings.index') }}" class="text-xs text-indigo-500 hover:underline">Kelola Jadwal TM →</a>
                                        </div>
                                    @endif
                                @endhasrole

                                {{-- === MANAGER OPERASIONAL === --}}
                                @hasrole('manager_operasional')
                                    @if ($event->status_proyek === 'Technical Meeting')
                                        <form action="{{ route('events.updateStatus', $event->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="aksi" value="finish_event">
                                            <button type="submit"
                                                onclick="return confirm('Tandai event SELESAI? E-Survey akan dikirim ke klien dan tagihan komisi ke vendor secara otomatis.')"
                                                class="w-full px-5 py-3.5 text-sm font-black text-white bg-green-600 hover:bg-green-700 rounded-xl transition-all shadow-md hover:shadow-lg uppercase tracking-wider text-center cursor-pointer">
                                                Event Selesai
                                            </button>
                                        </form>
                                    @endif
                                @endhasrole

                                {{-- STATUS FINAL: tidak ada aksi --}}
                                @if ($event->status_proyek === 'Transaksi Komplit')
                                    <div class="p-3 bg-purple-50 border border-purple-200 rounded-lg text-center">
                                        <p class="text-xs font-bold text-purple-700">Siklus Event Selesai Sempurna</p>
                                    </div>
                                @endif

                                {{-- Jika tidak ada aksi relevan untuk role ini pada status ini --}}
                                @php
                                    $hasAction = false;
                                    if (auth()->user()->hasRole('admin_cs') && in_array($event->status_proyek, ['Berjalan', 'Invoice Tersedia', 'Gagal Verifikasi'])) {
                                        $hasAction = true;
                                    }
                                    if (auth()->user()->hasRole('finance') && in_array($event->status_proyek, ['Perlu Invoice', 'Menunggu Verifikasi', 'Finish Event'])) {
                                        $hasAction = true;
                                    }
                                    if (auth()->user()->hasRole('partnership') && in_array($event->status_proyek, ['Terverifikasi', 'Technical Meeting'])) {
                                        $hasAction = true;
                                    }
                                    if (auth()->user()->hasRole('manager_operasional') && $event->status_proyek === 'Technical Meeting') {
                                        $hasAction = true;
                                    }
                                    if ($event->status_proyek === 'Transaksi Komplit') {
                                        $hasAction = true;
                                    }
                                @endphp
                                @if (!$hasAction)
                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-center">
                                        <p class="text-xs text-gray-400 font-medium">Tidak ada aksi di tahap ini</p>
                                        <p class="text-[10px] text-gray-300">Menunggu divisi lain menyelesaikan tugasnya</p>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL: UPLOAD BUKTI TRANSFER (Admin CS) --}}
                <div x-show="modalBukti"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm"
                    style="display:none;" x-transition>
                    <div class="bg-white p-6 rounded-2xl w-11/12 md:w-1/2 shadow-2xl">
                        <h3 class="text-xl font-bold text-[#4A3018] mb-2">Upload Bukti Pembayaran — {{ $event->nama_proyek }}</h3>
                        <p class="text-sm text-gray-500 mb-4">Upload bukti transfer dari klien. Finance akan memverifikasi setelahnya.</p>

                        <form action="{{ route('events.updateStatus', $event->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PATCH')
                            <input type="hidden" name="aksi" value="upload_bukti">

                            <label class="block text-sm font-bold text-gray-700 mb-1">File Bukti Transfer (PDF/JPG/PNG) <span class="text-red-500">*</span></label>
                            <input type="file" name="bukti_file" accept=".pdf,.jpg,.jpeg,.png" required
                                class="w-full text-sm border border-gray-300 rounded-lg p-2 mb-4 file:mr-3 file:py-1.5 file:px-4 file:rounded file:border-0 file:bg-[#8B5A2B] file:text-white file:font-bold cursor-pointer">

                            <div class="flex gap-3">
                                <button type="submit"
                                    class="flex-1 px-4 py-2.5 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                                    ⬆️ Upload Sekarang
                                </button>
                                <button type="button" @click="modalBukti = false"
                                    class="flex-1 px-4 py-2.5 text-sm font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- MODAL: TOLAK PEMBAYARAN (Finance) --}}
                <div x-show="modalTolak"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm"
                    style="display:none;" x-transition>
                    <div class="bg-white p-6 rounded-2xl w-11/12 md:w-1/2 shadow-2xl">
                        <h3 class="text-xl font-bold text-red-700 mb-2">Tolak Pembayaran — {{ $event->nama_proyek }}</h3>
                        <p class="text-sm text-gray-500 mb-4">Isi alasan penolakan agar Admin CS bisa menginformasikan ke klien.</p>

                        <form action="{{ route('events.updateStatus', $event->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="aksi" value="verifikasi_pembayaran">
                            <input type="hidden" name="keputusan" value="tolak">

                            <label class="block text-sm font-bold text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea name="catatan" rows="4" required minlength="5"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400 outline-none resize-none mb-4"
                                placeholder="Contoh: Bukti transfer buram, nominal tidak sesuai, nama pengirim berbeda, dsb..."></textarea>

                            <div class="flex gap-3">
                                <button type="submit"
                                    class="flex-1 px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                    Konfirmasi Tolak
                                </button>
                                <button type="button" @click="modalTolak = false"
                                    class="flex-1 px-4 py-2.5 text-sm font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="text-center py-16 bg-white border border-dashed border-[#D4A373] rounded-2xl">
                <p class="text-gray-500 font-bold text-lg">Belum Ada Event Aktif</p>
                <p class="text-sm text-gray-400 mt-1">Event akan muncul setelah Manager Operasional memvalidasi logistik.</p>
            </div>
        @endforelse

        {{-- Pesan jika filter aktif tidak menemukan event --}}
        <div x-show="activeFilter !== 'all' && !Array.from(document.querySelectorAll('.event-card')).some(el => el.getAttribute('data-status') === activeFilter)" 
             class="text-center py-16 bg-white border border-dashed border-[#D4A373] rounded-2xl"
             style="display: none;">
            <p class="text-gray-500 font-bold text-lg">Tidak Ada Event</p>
            <p class="text-sm text-gray-400 mt-1">Tidak ada event aktif dengan status "<span class="font-bold" x-text="activeFilter"></span>".</p>
        </div>
    </div>
</div>
@endsection
