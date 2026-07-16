@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div x-data="{ modalTM: false }">
        <div class="mb-8 border-b border-[#F5EBE1] pb-4">
            <h2 class="text-3xl font-bold text-[#4A3018]">Dashboard Analytics</h2>
            <p class="text-[#8B5A2B] mt-1 font-medium">Ringkasan sistem & statistik VMS</p>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">

            @hasrole('admin_cs')
                <div class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Klien</p>
                    <p class="text-4xl font-black text-[#4A3018] mt-2">{{ $cs_totalKlien }}</p>
                </div>
                <div class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Event</p>
                    <p class="text-4xl font-black text-[#4A3018] mt-2">{{ $cs_totalEvent }}</p>
                </div>

                <div
                    class="col-span-1 md:col-span-2 lg:col-span-4 bg-white p-6 rounded-2xl border border-[#F5EBE1] shadow-sm mt-2">
                    <h3 class="font-bold text-[#4A3018] text-sm mb-4 uppercase tracking-wide">Tren Klien Masuk (6 Bulan
                        Terakhir)</h3>
                    <div class="relative h-64 w-full"><canvas id="csChart"></canvas></div>
                </div>
            @endhasrole

            @hasrole('partnership')
                <div class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Vendor</p>
                    <p class="text-3xl font-bold text-[#4A3018] mt-2">{{ $sp_totalVendor }}</p>
                </div>
                <div class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Klien</p>
                    <p class="text-3xl font-bold text-[#4A3018] mt-2">{{ $sp_totalKlien }}</p>
                </div>
                <div class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Event</p>
                    <p class="text-3xl font-bold text-[#4A3018] mt-2">{{ $sp_totalEvent }}</p>
                </div>
                <div class="p-6 bg-red-50 border border-red-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-bold text-red-700 uppercase tracking-wide">Kontrak Mau Habis</p>
                    <p class="text-3xl font-black text-red-700 mt-2">{{ $sp_kontrakHabis }}</p>
                </div>
                <a href="{{ route('vendor.approval.index') }}" class="p-6 bg-orange-50 border border-orange-200 rounded-2xl shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <p class="text-sm font-bold text-orange-700 uppercase tracking-wide">Pengajuan Baru</p>
                        <p class="text-3xl font-black text-orange-800 mt-2" id="dashboardPendingCount">{{ $sp_vendorPending }}</p>
                    </div>
                    <span class="text-[11px] font-bold text-orange-600 hover:underline mt-2 inline-block">Tinjau Pengajuan →</span>
                </a>

                {{-- TABEL: PENGAJUAN VENDOR BARU --}}
                <div class="col-span-1 md:col-span-2 lg:col-span-4 bg-white p-6 rounded-2xl border border-orange-100 shadow-sm">
                    <h3 class="font-bold text-orange-700 text-sm mb-4 uppercase tracking-wide flex items-center gap-2">
                        Pengajuan Vendor Baru (Menunggu Review)
                        <span class="ml-auto text-[10px] font-bold text-white bg-orange-500 px-2 py-0.5 rounded-full" id="dashboardPendingBadgeMini">{{ $sp_pengajuanBaru->count() }} item</span>
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-2 px-3 text-gray-500 font-bold uppercase">Vendor</th>
                                    <th class="text-left py-2 px-3 text-gray-500 font-bold uppercase">Kategori</th>
                                    <th class="text-left py-2 px-3 text-gray-500 font-bold uppercase">Alamat/Daerah</th>
                                    <th class="text-center py-2 px-3 text-gray-500 font-bold uppercase">Tanggal Daftar</th>
                                    <th class="text-center py-2 px-3 text-gray-500 font-bold uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="dashboardPendingTableBody">
                                @forelse ($sp_pengajuanBaru as $p)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2.5 px-3 font-bold text-[#4A3018]">{{ $p->nama_vendor }}</td>
                                        <td class="py-2.5 px-3 text-gray-500">{{ $p->kategori_jasa }}</td>
                                        <td class="py-2.5 px-3 text-gray-500">{{ $p->alamat }}</td>
                                        <td class="py-2.5 px-3 text-center text-gray-500">{{ $p->created_at->format('d M Y') }}</td>
                                        <td class="py-2.5 px-3 text-center">
                                            <a href="{{ route('vendor.approval.index') }}" class="px-3 py-1 bg-orange-500 text-white text-[10px] font-bold rounded hover:bg-orange-600 transition-colors uppercase">Tinjau</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-6 text-gray-400">Tidak ada pengajuan vendor baru yang pending.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 lg:col-span-4 bg-white p-6 rounded-2xl border border-[#F5EBE1] shadow-sm mt-2">
                    <h3 class="font-bold text-[#4A3018] text-sm mb-4 uppercase tracking-wide">Tren Pertumbuhan Vendor & Event (6 Bulan)</h3>
                    <div class="relative h-64 w-full"><canvas id="spChart"></canvas></div>
                </div>

                <div class="col-span-1 md:col-span-2 lg:col-span-4 bg-white p-6 rounded-2xl border border-[#F5EBE1] shadow-sm">
                    <h3 class="font-bold text-[#4A3018] text-sm mb-4 uppercase tracking-wide">Grafik Rating Vendor (Berdasarkan Kepuasan Klien)</h3>
                    <div class="relative h-64 w-full"><canvas id="spRatingChart"></canvas></div>
                </div>

                {{-- TABEL: RATING VENDOR --}}
                <div class="col-span-1 md:col-span-2 lg:col-span-4 bg-white p-6 rounded-2xl border border-[#F5EBE1] shadow-sm">
                    <h3 class="font-bold text-[#4A3018] text-sm mb-4 uppercase tracking-wide flex items-center gap-2">
                        Rating Vendor (Berdasarkan Penilaian Klien & Ketepatan Komisi)
                        <span class="text-[10px] text-gray-400 font-normal ml-auto" id="lastRatingUpdate">Terakhir diperbarui: {{ now()->format('H:i:s') }}</span>
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-2 px-3 text-gray-500 font-bold uppercase">#</th>
                                    <th class="text-left py-2 px-3 text-gray-500 font-bold uppercase">Vendor</th>
                                    <th class="text-left py-2 px-3 text-gray-500 font-bold uppercase">Kategori</th>
                                    <th class="text-center py-2 px-3 text-gray-500 font-bold uppercase">Rating</th>
                                    <th class="text-center py-2 px-3 text-gray-500 font-bold uppercase">Total Review</th>
                                    <th class="text-center py-2 px-3 text-gray-500 font-bold uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="vendorRatingTableBody">
                                @forelse ($sp_topVendor as $i => $vendor)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2.5 px-3 font-bold text-gray-400">{{ $i + 1 }}</td>
                                        <td class="py-2.5 px-3 font-bold text-[#4A3018]">{{ $vendor->nama_vendor }}</td>
                                        <td class="py-2.5 px-3 text-gray-500">{{ $vendor->kategori_jasa }}</td>
                                        <td class="py-2.5 px-3 text-center">
                                            @php
                                                $stars = round($vendor->rating);
                                                $color = $vendor->rating >= 4 ? 'text-yellow-500' : ($vendor->rating >= 3 ? 'text-orange-400' : 'text-red-400');
                                            @endphp
                                            <span class="font-black {{ $color }}">{{ $vendor->rating }}</span>
                                            <span class="text-yellow-400 ml-1">{{ str_repeat('★', $stars) }}{{ str_repeat('☆', 5 - $stars) }}</span>
                                        </td>
                                        <td class="py-2.5 px-3 text-center text-gray-500">{{ $vendor->total_review }}x</td>
                                        <td class="py-2.5 px-3 text-center">
                                            @if ($vendor->status_aktif)
                                                <span class="px-2 py-0.5 bg-green-100 text-green-700 font-bold rounded-full">Aktif</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-red-100 text-red-700 font-bold rounded-full">Non-aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-6 text-gray-400">Belum ada data rating vendor.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL: VENDOR RATING RENDAH (BUTUH TINDAK LANJUT) --}}
                @if($sp_lowRatingVendors->count() > 0)
                <div class="col-span-1 md:col-span-2 lg:col-span-4 bg-white p-6 rounded-2xl border border-red-200 shadow-sm">
                    <h3 class="font-bold text-red-700 text-sm mb-1 uppercase tracking-wide flex items-center gap-2">
                        ⚠️ Vendor Butuh Tindak Lanjut (Rating &lt; 2.0)
                        <span class="ml-auto text-[10px] font-bold text-white bg-red-600 px-2 py-0.5 rounded-full">{{ $sp_lowRatingVendors->count() }} vendor</span>
                    </h3>
                    <p class="text-xs text-red-500 mb-4">Vendor berikut memiliki rating sangat rendah dan perlu dievaluasi atau dinonaktifkan segera.</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="border-b border-red-100">
                                    <th class="text-left py-2 px-3 text-red-600 font-bold uppercase">#</th>
                                    <th class="text-left py-2 px-3 text-red-600 font-bold uppercase">Nama Vendor</th>
                                    <th class="text-left py-2 px-3 text-red-600 font-bold uppercase">Kategori</th>
                                    <th class="text-center py-2 px-3 text-red-600 font-bold uppercase">Rating</th>
                                    <th class="text-center py-2 px-3 text-red-600 font-bold uppercase">Total Review</th>
                                    <th class="text-center py-2 px-3 text-red-600 font-bold uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-red-50">
                                @foreach ($sp_lowRatingVendors as $i => $lv)
                                    <tr class="hover:bg-red-50 transition-colors">
                                        <td class="py-2.5 px-3 font-bold text-red-400">{{ $i + 1 }}</td>
                                        <td class="py-2.5 px-3 font-bold text-[#4A3018]">{{ $lv->nama_vendor }}</td>
                                        <td class="py-2.5 px-3 text-gray-500">{{ $lv->kategori_jasa }}</td>
                                        <td class="py-2.5 px-3 text-center">
                                            <span class="font-black text-red-600">{{ number_format($lv->rating, 1) }}</span>
                                            <span class="text-red-300 ml-1">{{ str_repeat('★', round($lv->rating)) }}{{ str_repeat('☆', 5 - round($lv->rating)) }}</span>
                                        </td>
                                        <td class="py-2.5 px-3 text-center text-gray-500">{{ $lv->total_review }}x</td>
                                        <td class="py-2.5 px-3 text-center">
                                            <a href="{{ route('vendors.edit', $lv) }}" class="px-3 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded hover:bg-red-200 transition-colors uppercase">Tinjau</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            @endhasrole


            @hasrole('finance')
                <div class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Menunggu Invoice</p>
                    <p class="text-3xl font-bold text-[#4A3018] mt-2">{{ $fin_butuhInvoice }}</p>
                </div>
                <div class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Invoice Terbit</p>
                    <p class="text-3xl font-bold text-[#4A3018] mt-2">{{ $fin_jumlahInvoice }}</p>
                </div>
                <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-bold text-yellow-700 uppercase tracking-wide">Belum Lunas</p>
                    <p class="text-3xl font-black text-yellow-800 mt-2">{{ $fin_komisiPending }}</p>
                </div>
                <div class="p-6 bg-green-50 border border-green-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-bold text-green-700 uppercase tracking-wide">Event Lunas Penuh</p>
                    <p class="text-3xl font-black text-green-800 mt-2">{{ $fin_komisiLunas }}</p>
                </div>

                <div class="col-span-1 md:col-span-2 lg:col-span-4 bg-white p-6 rounded-2xl border border-[#F5EBE1] shadow-sm mt-2">
                    <h3 class="font-bold text-[#4A3018] text-sm mb-4 uppercase tracking-wide">Grafik Pendapatan Tahunan (12 Bulan)</h3>
                    <div class="relative h-72 w-full"><canvas id="financeChart"></canvas></div>
                </div>

                {{-- TABEL: VENDOR BELUM BAYAR KOMISI --}}
                <div class="col-span-1 md:col-span-2 lg:col-span-4 bg-white p-6 rounded-2xl border border-red-100 shadow-sm">
                    <h3 class="font-bold text-red-700 text-sm mb-4 uppercase tracking-wide flex items-center gap-2">
                        Vendor Belum Bayar Komisi (Monitoring Harian)
                        <span class="ml-auto text-[10px] font-bold text-white bg-red-500 px-2 py-0.5 rounded-full">{{ $fin_vendorBelumBayar->count() }} item</span>
                    </h3>
                    @if ($fin_vendorBelumBayar->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-2 px-3 text-gray-500 font-bold uppercase tracking-wide">Vendor</th>
                                        <th class="text-left py-2 px-3 text-gray-500 font-bold uppercase tracking-wide">Event</th>
                                        <th class="text-left py-2 px-3 text-gray-500 font-bold uppercase tracking-wide">Jatuh Tempo</th>
                                        <th class="text-center py-2 px-3 text-gray-500 font-bold uppercase tracking-wide">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($fin_vendorBelumBayar as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-2.5 px-3 font-bold text-[#4A3018]">{{ $item['nama_vendor'] }}</td>
                                            <td class="py-2.5 px-3 text-gray-600">{{ $item['nama_proyek'] }}</td>
                                            <td class="py-2.5 px-3 text-gray-600">{{ $item['jatuh_tempo']->translatedFormat('d M Y') }}</td>
                                            <td class="py-2.5 px-3 text-center">
                                                @if ($item['sudah_jatuh'])
                                                    <span class="px-2 py-1 bg-red-100 text-red-700 font-bold rounded-full">Terlambat {{ $item['hari_telat'] }} hari</span>
                                                @else
                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 font-bold rounded-full">Belum jatuh tempo</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-green-600 font-bold text-center py-4">✓ Semua komisi vendor sudah dibayar lunas!</p>
                    @endif
                </div>
            @endhasrole


            @hasrole('manager_comercial')
                <div
                    class="col-span-1 lg:col-span-2 p-6 bg-red-50 border border-red-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-bold text-red-700 uppercase tracking-wide">Butuh Disposisi Segera</p>
                    <p class="text-4xl font-black text-red-700 mt-2">{{ $mc_butuhDisposisi }} <span
                            class="text-sm font-medium text-red-500">Dokumen</span></p>
                </div>
                <div
                    class="col-span-1 lg:col-span-2 p-6 bg-green-50 border border-green-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-bold text-green-700 uppercase tracking-wide">Total Event Lunas</p>
                    <p class="text-4xl font-black text-green-800 mt-2">{{ $mc_pendapatan }} <span
                            class="text-sm font-medium text-green-600">Proyek</span></p>
                </div>

                <div class="col-span-full mt-4">
                    <h3 class="font-black text-[#4A3018] text-xl border-b border-[#D4A373] pb-2 mb-4">Analitik Komersial (6
                        Bulan Terakhir)</h3>
                </div>

                <div class="col-span-1 md:col-span-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-5 rounded-2xl border border-[#F5EBE1] shadow-sm">
                        <p class="font-bold text-[#4A3018] text-sm mb-3">Klien Baru</p>
                        <div class="relative h-48 w-full"><canvas id="klienChartComm"></canvas></div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-[#F5EBE1] shadow-sm">
                        <p class="font-bold text-[#4A3018] text-sm mb-3">Vendor Baru</p>
                        <div class="relative h-48 w-full"><canvas id="vendorChartComm"></canvas></div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-[#F5EBE1] shadow-sm">
                        <p class="font-bold text-[#4A3018] text-sm mb-3">Event Berjalan</p>
                        <div class="relative h-48 w-full"><canvas id="eventChartComm"></canvas></div>
                    </div>
                </div>
            @endhasrole

            @hasrole('manager_operasional')
                <div
                    class="p-6 bg-[#FDF8F3] border-2 border-[#D4A373] rounded-2xl shadow-sm text-center relative overflow-hidden flex flex-col justify-center items-center hover:bg-[#F5EBE1] transition-colors">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest z-10">Klien Masuk Hari Ini</p>
                    <p class="text-5xl font-black text-[#8B5A2B] mt-2 z-10">{{ $mo_klienHariIni ?? 0 }}</p>
                    <p class="text-[10px] mt-2 text-gray-400 font-bold uppercase tracking-wide z-10">Kinerja Tim CS</p>
                </div>

                <div
                    class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm flex flex-col justify-center items-center hover:shadow-md transition-shadow">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Total Klien Aktif</p>
                    <p class="text-4xl font-bold text-[#4A3018] mt-2">{{ $mo_jumlahKlien }}</p>
                </div>

                <div
                    class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm flex flex-col justify-center items-center hover:shadow-md transition-shadow">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Total Event</p>
                    <p class="text-4xl font-bold text-[#4A3018] mt-2">{{ $mo_jumlahEvent }}</p>
                </div>

                <div class="p-6 bg-[#4A3018] rounded-2xl shadow-sm cursor-pointer hover:bg-[#8B5A2B] transition-colors text-center flex flex-col justify-center items-center group"
                    @click="modalTM = true">
                    <p class="text-xs font-bold text-[#F5EBE1] uppercase tracking-widest">Jadwal TM Aktif</p>
                    <p class="text-4xl font-black text-white mt-2">{{ $mo_jumlahTM }}</p>
                    <p class="text-[10px] mt-2 text-[#D4A373] font-semibold group-hover:text-white transition-colors">Lihat
                        Detail Jadwal &rarr;</p>
                </div>

                <div
                    class="col-span-1 md:col-span-2 lg:col-span-4 bg-white p-6 rounded-2xl border border-[#F5EBE1] shadow-sm mt-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-[#4A3018] text-sm uppercase tracking-wide">Tren Pertumbuhan Klien (6 Bulan
                            Terakhir)</h3>
                        <span class="text-[10px] font-bold text-white bg-green-500 px-2 py-1 rounded">Live Data</span>
                    </div>
                    <div class="relative h-72 w-full"><canvas id="opChart"></canvas></div>
                </div>

                <div x-show="modalTM"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm"
                    style="display: none;" x-transition>
                    <div class="bg-white p-6 rounded-2xl w-11/12 md:w-1/2 max-h-[80vh] overflow-y-auto shadow-2xl relative">
                        <div
                            class="sticky top-0 bg-white flex justify-between items-center pb-4 mb-4 border-b border-gray-100 z-10">
                            <h3 class="text-xl font-bold text-[#4A3018]">Detail Jadwal Technical Meeting</h3>
                            <button @click="modalTM = false"
                                class="text-gray-400 hover:text-red-500 font-bold text-3xl transition-colors">&times;</button>
                        </div>
                        <ul class="space-y-4">
                            @forelse($mo_listTM as $tm)
                                <li
                                    class="p-5 border border-[#F5EBE1] rounded-xl bg-[#FAFAFA] hover:shadow-md transition-shadow">
                                    <span class="font-black text-lg text-blue-700 block mb-1">
                                        📅 {{ \Carbon\Carbon::parse($tm->jadwal_tm)->translatedFormat('d F Y - H:i') }} WIB
                                    </span>
                                    <div class="text-sm text-gray-700 mt-3 space-y-2">
                                        <p><strong
                                                class="text-gray-400 uppercase tracking-wide text-[10px] block">Proyek</strong>
                                            <span
                                                class="font-bold text-[#4A3018]">{{ $tm->project->nama_proyek ?? '-' }}</span>
                                        </p>
                                        <p><strong
                                                class="text-gray-400 uppercase tracking-wide text-[10px] block">Klien</strong>
                                            {{ $tm->project->client->nama_klien ?? '-' }}</p>
                                        <p><strong
                                                class="text-gray-400 uppercase tracking-wide text-[10px] block">Lokasi</strong>
                                            📍 {{ $tm->lokasi }}</p>
                                    </div>
                                </li>
                            @empty
                                <li
                                    class="text-center text-gray-400 py-10 border border-dashed border-gray-200 rounded-xl font-medium">
                                    Belum ada jadwal TM yang terdaftar saat ini.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            @endhasrole
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data Sumbu X (6 Bulan Terakhir)
            const labelBulan = @json($chartLabels ?? []);

            // Data Y (6 Bulan Terakhir)
            const dataKlien = @json($chartDataKlien ?? []);
            const dataVendor = @json($chartDataVendor ?? []);
            const dataEvent = @json($chartDataEvent ?? []);

            // Opsi Dasar untuk Semua Grafik Bar
            const commonBarOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#9CA3AF'
                        },
                        grid: {
                            borderDash: [2, 2]
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#6B7280'
                        }
                    }
                }
            };

            // 1. GRAFIK ADMIN CS (Bar Chart)
            if (document.getElementById('csChart')) {
                new Chart(document.getElementById('csChart'), {
                    type: 'bar',
                    data: {
                        labels: labelBulan,
                        datasets: [{
                            label: 'Klien Baru',
                            data: dataKlien,
                            backgroundColor: '#8B5A2B',
                            borderRadius: 6,
                            barThickness: 20
                        }]
                    },
                    options: commonBarOptions
                });
            }

            // 2. GRAFIK STRATEGIC PARTNERSHIP (Multiple Line/Bar Chart)
            if (document.getElementById('spChart')) {
                new Chart(document.getElementById('spChart'), {
                    type: 'bar',
                    data: {
                        labels: labelBulan,
                        datasets: [{
                                label: 'Pertumbuhan Vendor',
                                data: dataVendor,
                                backgroundColor: '#0284C7',
                                borderRadius: 4
                            },
                            {
                                label: 'Pertumbuhan Event',
                                data: dataEvent,
                                backgroundColor: '#16A34A',
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // 2b. GRAFIK RATING VENDOR (Bar Chart)
            if (document.getElementById('spRatingChart')) {
                const vendorNames = @json($sp_topVendor->pluck('nama_vendor') ?? []);
                const vendorRatings = @json($sp_topVendor->pluck('rating') ?? []);

                window.spRatingChartInstance = new Chart(document.getElementById('spRatingChart'), {
                    type: 'bar',
                    data: {
                        labels: vendorNames,
                        datasets: [{
                            label: 'Rating',
                            data: vendorRatings,
                            backgroundColor: '#D4A373',
                            borderColor: '#8B5A2B',
                            borderWidth: 1.5,
                            borderRadius: 6,
                            barThickness: 25
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                min: 0,
                                max: 5,
                                ticks: {
                                    stepSize: 1,
                                    color: '#9CA3AF'
                                },
                                grid: {
                                    borderDash: [2, 2]
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    },
                                    color: '#6B7280'
                                }
                            }
                        }
                    }
                });
            }

            // 3. GRAFIK FINANCE (Line Chart 12 Bulan)
            if (document.getElementById('financeChart')) {
                const label12Bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov',
                    'Des'
                ];
                const dataPendapatan = @json($grafik_pendapatan ?? array_fill(0, 12, 0));

                new Chart(document.getElementById('financeChart'), {
                    type: 'line',
                    data: {
                        labels: label12Bulan,
                        datasets: [{
                            label: 'Pendapatan Rp',
                            data: dataPendapatan,
                            borderColor: '#16A34A',
                            backgroundColor: 'rgba(22, 163, 74, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#16A34A',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + (value / 1000000) + ' Jt';
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // 4. GRAFIK MANAGER OPERASIONAL (Line Chart Halus / Area)
            if (document.getElementById('opChart')) {
                new Chart(document.getElementById('opChart'), {
                    type: 'line',
                    data: {
                        labels: labelBulan,
                        datasets: [{
                            label: 'Total Klien',
                            data: dataKlien,
                            borderColor: '#8B5A2B',
                            backgroundColor: 'rgba(139, 90, 43, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#8B5A2B',
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [2, 2]
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // 5. GRAFIK MANAGER COMERCIAL (3 Grafik Batang)
            function buatGrafikComm(canvasId, dataArray, warnaBatang, labelNama) {
                const ctx = document.getElementById(canvasId);
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labelBulan,
                            datasets: [{
                                label: labelNama,
                                data: dataArray,
                                backgroundColor: warnaBatang,
                                borderRadius: 4,
                                barThickness: 15
                            }]
                        },
                        options: commonBarOptions
                    });
                }
            }
            buatGrafikComm('klienChartComm', dataKlien, '#8B5A2B', 'Klien Baru');
            buatGrafikComm('vendorChartComm', dataVendor, '#0284C7', 'Vendor Baru');
            buatGrafikComm('eventChartComm', dataEvent, '#16A34A', 'Event Berjalan');

            // JS Polling untuk dashboard Partnership (Pending count, rating, dan chart) - FASE 4 & 6
            if (document.getElementById('vendorRatingTableBody') || document.getElementById('dashboardPendingCount')) {
                
                function refreshPartnershipDashboardData() {
                    // 1. Polling Rating Vendor (Tabel & Chart)
                    if (document.getElementById('vendorRatingTableBody')) {
                        fetch('/api/dashboard/vendor-ratings')
                            .then(response => response.json())
                            .then(vendors => {
                                const tableBody = document.getElementById('vendorRatingTableBody');
                                const lastUpdateSpan = document.getElementById('lastRatingUpdate');
                                
                                if (tableBody) {
                                    if (vendors.length > 0) {
                                        let html = '';
                                        vendors.forEach((vendor, index) => {
                                            const rating = parseFloat(vendor.rating);
                                            const stars = Math.round(rating);
                                            const color = rating >= 4 ? 'text-yellow-500' : (rating >= 3 ? 'text-orange-400' : 'text-red-400');
                                            const starStr = '★'.repeat(stars) + '☆'.repeat(5 - stars);
                                            const statusBadge = vendor.status_aktif 
                                                ? '<span class="px-2 py-0.5 bg-green-100 text-green-700 font-bold rounded-full">Aktif</span>'
                                                : '<span class="px-2 py-0.5 bg-red-100 text-red-700 font-bold rounded-full">Non-aktif</span>';
                                                
                                            html += `<tr class="hover:bg-gray-50">
                                                <td class="py-2.5 px-3 font-bold text-gray-400">${index + 1}</td>
                                                <td class="py-2.5 px-3 font-bold text-[#4A3018]">${vendor.nama_vendor}</td>
                                                <td class="py-2.5 px-3 text-gray-500">${vendor.kategori_jasa}</td>
                                                <td class="py-2.5 px-3 text-center">
                                                    <span class="font-black ${color}">${rating.toFixed(1)}</span>
                                                    <span class="text-yellow-400 ml-1">${starStr}</span>
                                                </td>
                                                <td class="py-2.5 px-3 text-center text-gray-500">${vendor.total_review || 0}x</td>
                                                <td class="py-2.5 px-3 text-center">${statusBadge}</td>
                                            </tr>`;
                                        });
                                        tableBody.innerHTML = html;
                                    } else {
                                        tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-6 text-gray-400">Belum ada data rating vendor.</td></tr>';
                                    }
                                }
                                
                                if (lastUpdateSpan) {
                                    const now = new Date();
                                    const timeStr = now.toTimeString().split(' ')[0];
                                    lastUpdateSpan.textContent = 'Terakhir diperbarui: ' + timeStr;
                                }

                                // Update Chart.js if exists
                                if (window.spRatingChartInstance) {
                                    window.spRatingChartInstance.data.labels = vendors.map(v => v.nama_vendor);
                                    window.spRatingChartInstance.data.datasets[0].data = vendors.map(v => parseFloat(v.rating));
                                    window.spRatingChartInstance.update();
                                }
                            })
                            .catch(err => console.error('Error polling vendor ratings:', err));
                    }

                    // 2. Polling Vendor Pending Baru (Fase 4 widgets)
                    fetch('/api/vendor-pending-count')
                        .then(response => response.json())
                        .then(data => {
                            const countCard = document.getElementById('dashboardPendingCount');
                            if (countCard) {
                                countCard.textContent = data.count;
                            }
                        })
                        .catch(err => console.error('Error polling pending count:', err));
                }

                // Jalankan polling setiap 30 detik
                setInterval(refreshPartnershipDashboardData, 30000);
            }
        });
    </script>
@endsection
