@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-[#4A3018]">Daftar Permintaan Klien</h2>
            <p class="text-[#8B5A2B] mt-1">Pantau status pengajuan dan validasi vendor untuk setiap klien.</p>
        </div>

        @hasrole('admin_cs')
            <a href="{{ route('clients.create') }}"
                class="px-5 py-2.5 bg-[#8B5A2B] text-[#F5EBE1] font-semibold rounded-lg hover:bg-[#4A3018] transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Input Permintaan Baru
            </a>
        @endhasrole
    </div>

    @if (session('success'))
        <div class="p-4 mb-6 text-sm font-bold text-green-700 bg-green-100 border border-green-200 rounded-lg shadow-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F5EBE1] text-[#4A3018] border-b border-[#D4A373]">
                        <th class="px-6 py-4 font-bold text-sm tracking-wide">Klien & Kontak</th>
                        <th class="px-6 py-4 font-bold text-sm tracking-wide">Jadwal & Lokasi</th>
                        <th class="px-6 py-4 font-bold text-sm tracking-wide w-1/4">Ringkasan Kebutuhan</th>
                        <th class="px-6 py-4 font-bold text-sm tracking-wide text-center">Status Event</th>
                        <th class="px-6 py-4 font-bold text-sm tracking-wide text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F5EBE1]">
                    @forelse($clients as $client)
                        <tr class="hover:bg-[#FAFAFA] transition-colors group">

                            <td class="px-6 py-4">
                                <p class="font-black text-[#4A3018] text-base">{{ $client->nama_klien }}</p>
                                <p class="text-xs font-semibold text-gray-500 mb-1">{{ $client->instansi }}</p>
                                <div class="flex flex-col gap-0.5 mt-2 text-[11px] text-gray-500">
                                    <span class="flex items-center gap-1">📞 {{ $client->no_telepon }}</span>
                                    <span class="flex items-center gap-1">✉️ {{ $client->email }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="flex items-start gap-2 mb-1">
                                    <span class="text-[#8B5A2B]">📅</span>
                                    <p class="font-bold text-[#8B5A2B]">
                                        {{ $client->tanggal_acara ? \Carbon\Carbon::parse($client->tanggal_acara)->translatedFormat('d M Y') : '-' }}
                                    </p>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="text-gray-400">📍</span>
                                    <p class="text-xs font-medium text-gray-500">{{ $client->tempat_acara ?? '-' }}</p>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <p
                                    class="text-xs text-gray-600 leading-relaxed bg-gray-50 p-2 border border-gray-100 rounded">
                                    {{-- Mengubah format enter menjadi koma agar rapi di tabel --}}
                                    {{ \Illuminate\Support\Str::limit(str_replace("\n", ' • ', $client->kebutuhan_klien), 80) }}
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @php
                                    // Ambil data proyek untuk mengecek apakah acara sudah ditutup
                                    $proyek = \App\Models\Project::where('client_id', $client->id)->first();
                                @endphp

                                @if ($proyek && in_array($proyek->status_proyek, ['Finish Event', 'selesai', 'Transaksi Komplit']))
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-700 text-xs font-black rounded-full border border-gray-300 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Event Selesai
                                    </span>
                                @elseif ($client->is_vendor_acc && $client->vendors->count() > 0)
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-black rounded-full border border-green-200 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Event
                                        Berjalan
                                    </span>
                                @elseif($client->vendors->count() > 0)
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-black rounded-full border border-yellow-200 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Menunggu
                                        ACC
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-black rounded-full border border-blue-200 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Pemilihan Vendor
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center space-y-2">
                                <a href="{{ route('clients.show', $client->id) }}"
                                    class="block w-full px-4 py-2 bg-[#8B5A2B] text-white text-[11px] font-bold tracking-wide rounded-lg hover:bg-[#4A3018] shadow-sm transition-all transform group-hover:scale-105">
                                    🎯 BUKA PANEL VALIDASI
                                </a>

                                @hasrole('admin_cs')
                                    <a href="{{ route('clients.edit', $client->id) }}"
                                        class="block w-full px-4 py-1.5 bg-white text-[#8B5A2B] border border-[#8B5A2B] text-[11px] font-bold rounded-lg hover:bg-[#F5EBE1] transition-colors">
                                        ✏️ Edit Permintaan
                                    </a>
                                @endhasrole
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="font-bold text-gray-500">Belum Ada Permintaan Klien</p>
                                    <p class="text-sm mt-1">Data klien yang diinput oleh Admin CS akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
