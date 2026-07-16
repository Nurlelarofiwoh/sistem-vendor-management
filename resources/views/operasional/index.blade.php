@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-[#4A3018]">Monitoring Operasional & Logistik</h2>
        <p class="text-[#8B5A2B] mt-1">Validasi kesiapan logistik vendor sebelum event resmi berjalan.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <!-- PANEL 1: VALIDASI LOGISTIK -->
        <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl p-6">
            <h3 class="mb-4 text-xl font-bold text-[#4A3018] border-b pb-2">Validasi Logistik Proyek</h3>
            <div class="space-y-4">
                @forelse($projects as $project)
                    <div class="p-5 border border-gray-200 rounded-xl bg-[#FAFAFA] shadow-sm hover:border-[#D4A373] transition-colors"
                         x-data="{ modalTolak: false }">

                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-black text-lg text-[#8B5A2B]">{{ $project->nama_proyek }}</h4>

                            @php
                                $statusBadge = match($project->status_proyek) {
                                    'Menunggu Logistik' => ['label' => 'Menunggu Validasi', 'class' => 'text-yellow-700 bg-yellow-100 border-yellow-200'],
                                    'Berjalan'          => ['label' => 'Berjalan', 'class' => 'text-blue-700 bg-blue-100 border-blue-200'],
                                    'Ditolak Logistik'  => ['label' => 'Ditolak', 'class' => 'text-red-700 bg-red-100 border-red-200'],
                                    'Finish Event'      => ['label' => 'Selesai', 'class' => 'text-green-700 bg-green-100 border-green-200'],
                                    'Transaksi Komplit' => ['label' => 'Komplit', 'class' => 'text-purple-700 bg-purple-100 border-purple-200'],
                                    default             => ['label' => $project->status_proyek, 'class' => 'text-gray-700 bg-gray-100 border-gray-200'],
                                };
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-black border rounded-full uppercase tracking-wider {{ $statusBadge['class'] }}">
                                {{ $statusBadge['label'] }}
                            </span>
                        </div>

                        <div class="text-sm text-gray-700 mb-3 space-y-1">
                            <p><span class="font-bold text-gray-500 w-24 inline-block">Klien</span>: {{ $project->client->nama_klien ?? '-' }}</p>
                            <p><span class="font-bold text-gray-500 w-24 inline-block">Tgl Acara</span>:
                                {{ $project->client && $project->client->tanggal_acara ? \Carbon\Carbon::parse($project->client->tanggal_acara)->translatedFormat('d M Y') : '-' }}
                            </p>
                        </div>

                        <div class="p-3 bg-white border border-gray-200 rounded-lg mb-4">
                            <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-2 border-b pb-1">Daftar Vendor Terikat:</p>
                            <ul class="list-disc list-inside text-xs text-gray-800 space-y-1 pl-1">
                                @if ($project->client && $project->client->vendors->count() > 0)
                                    @foreach ($project->client->vendors as $v)
                                        <li><strong class="text-[#4A3018]">{{ $v->nama_vendor }}</strong> <span class="text-gray-500">({{ $v->kategori_jasa }})</span></li>
                                    @endforeach
                                @else
                                    <li class="text-red-500 italic list-none">Belum ada vendor terikat</li>
                                @endif
                            </ul>
                        </div>

                        <!-- TOMBOL AKSI BERDASARKAN STATUS -->
                        @if ($project->status_proyek === 'Menunggu Logistik')
                            <div class="grid grid-cols-2 gap-3">
                                <!-- TOMBOL ACC -->
                                <form action="{{ route('operasional.update', $project->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status_proyek" value="Berjalan">
                                    <button type="submit"
                                        onclick="return confirm('ACC logistik? Event akan resmi berjalan dan semua divisi mendapat notifikasi.')"
                                        class="w-full flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors uppercase tracking-wide">
                                        ✅ ACC Logistik
                                    </button>
                                </form>

                                <!-- TOMBOL TOLAK (buka modal) -->
                                <button type="button" @click="modalTolak = true"
                                    class="w-full flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition-colors uppercase tracking-wide">
                                    ❌ Tolak
                                </button>
                            </div>

                            <!-- MODAL TOLAK LOGISTIK -->
                            <div x-show="modalTolak"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm"
                                style="display:none;" x-transition>
                                <div class="bg-white p-6 rounded-2xl w-11/12 md:w-1/2 shadow-2xl relative">
                                    <h3 class="text-xl font-bold text-red-700 mb-2">Tolak Logistik — {{ $project->nama_proyek }}</h3>
                                    <p class="text-sm text-gray-500 mb-4">Isi alasan penolakan dengan jelas agar Partnership dapat melakukan perbaikan.</p>

                                    <form action="{{ route('operasional.update', $project->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status_proyek" value="Ditolak Logistik">

                                        <label class="block text-sm font-bold text-gray-700 mb-1">Catatan / Alasan Penolakan <span class="text-red-500">*</span></label>
                                        <textarea name="catatan_operasional" rows="4" required minlength="10"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400 focus:border-red-400 outline-none resize-none"
                                            placeholder="Contoh: Vendor belum menyerahkan dokumen teknis, jadwal konflik dengan event lain, dsb..."></textarea>

                                        <div class="flex gap-3 mt-4">
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

                        @elseif ($project->status_proyek === 'Ditolak Logistik')
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm font-bold text-red-700 mb-1">❌ Logistik Ditolak</p>
                                @if ($project->catatan_operasional)
                                    <p class="text-xs text-red-600 italic">Catatan: {{ $project->catatan_operasional }}</p>
                                @endif
                            </div>

                        @elseif (in_array($project->status_proyek, ['Berjalan', 'Technical Meeting', 'Terverifikasi', 'Perlu Invoice', 'Invoice Tersedia', 'Menunggu Verifikasi', 'Gagal Verifikasi']))
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-center">
                                <p class="text-sm font-bold text-blue-700">✅ Logistik Tervalidasi — Event Berjalan</p>
                                <p class="text-xs text-blue-600 mt-1">Status terkini: <strong>{{ $project->status_proyek }}</strong></p>
                            </div>

                        @elseif ($project->status_proyek === 'Finish Event')
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-center">
                                <p class="text-sm font-bold text-green-700 mb-1">🎉 Event Selesai & Tervalidasi</p>
                                <p class="text-xs text-green-600">E-Survey telah dikirim ke Klien. Tagihan komisi dikirim ke Vendor.</p>
                            </div>

                        @elseif ($project->status_proyek === 'Transaksi Komplit')
                            <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg text-center">
                                <p class="text-sm font-bold text-purple-700">🏆 Transaksi Komplit</p>
                                <p class="text-xs text-purple-600 mt-1">Semua siklus finansial telah ditutup.</p>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-xl">
                        <p class="text-gray-500 font-bold">Tidak ada proyek yang perlu divalidasi.</p>
                        <p class="text-xs text-gray-400 mt-1">Proyek akan muncul setelah Partnership meng-ACC vendor.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- PANEL 2: JADWAL TM -->
        <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl p-6 h-fit">
            <h3 class="mb-4 text-xl font-bold text-[#4A3018] border-b pb-2">Jadwal Technical Meeting (TM)</h3>
            <div class="space-y-4">
                @forelse($meetings as $tm)
                    <div class="p-4 border-l-4 border-[#8B5A2B] rounded-r-lg bg-[#F5EBE1] bg-opacity-30 hover:bg-opacity-60 transition-colors">
                        <p class="text-sm font-bold text-gray-800">
                            {{ \Carbon\Carbon::parse($tm->jadwal_tm)->translatedFormat('d F Y - H:i') }} WIB
                        </p>
                        <p class="font-black text-[#4A3018] mt-1 text-lg">
                            {{ $tm->project->nama_proyek ?? 'Proyek Tidak Ditemukan' }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1"><span class="font-bold text-gray-500">Lokasi:</span> {{ $tm->lokasi }}</p>
                        <p class="text-xs text-gray-500 mt-2 italic bg-white p-2 rounded border border-gray-200">
                            Agenda: "{{ $tm->agenda }}"
                        </p>
                    </div>
                @empty
                    <div class="text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-xl">
                        <p class="text-gray-500 font-bold">Jadwal TM Kosong.</p>
                        <p class="text-xs text-gray-400 mt-1">Belum ada Technical Meeting yang dijadwalkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
