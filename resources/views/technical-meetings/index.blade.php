@extends('layouts.app')

@php
    $activeTab = request('tab') ?? session('active_tab') ?? 'aktif';
@endphp

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-[#4A3018]">Jadwal Technical Meeting</h2>
            <p class="text-[#8B5A2B] mt-1 text-sm">Pantau dan kelola jadwal pertemuan antara Klien, Vendor, dan Tim Operasional.</p>
        </div>

        <a href="{{ route('technical-meetings.create') }}"
            class="px-5 py-2.5 bg-[#8B5A2B] text-white font-bold rounded-xl hover:bg-[#4A3018] transition-all shadow-md hover:shadow-lg flex items-center gap-2 transform hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Buat Jadwal Baru
        </a>
    </div>

    @if (session('success'))
        <div
            class="p-4 mb-6 text-sm font-bold text-green-700 bg-green-50 border border-green-200 rounded-xl shadow-sm flex items-center gap-2">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif

    <div class="flex border-b border-[#F5EBE1] mb-6 space-x-8">
        <button onclick="switchTab('aktif')" id="tab-aktif"
            class="pb-3 text-sm {{ $activeTab === 'aktif' ? 'font-black text-[#8B5A2B] border-b-2 border-[#8B5A2B]' : 'font-bold text-gray-400 border-b-2 border-transparent hover:text-[#8B5A2B]' }} uppercase tracking-wide transition-colors">
            Jadwal Aktif ({{ $meetings->count() }})
        </button>
        <button onclick="switchTab('riwayat')" id="tab-riwayat"
            class="pb-3 text-sm {{ $activeTab === 'riwayat' ? 'font-black text-[#8B5A2B] border-b-2 border-[#8B5A2B]' : 'font-bold text-gray-400 border-b-2 border-transparent hover:text-[#8B5A2B]' }} uppercase tracking-wide transition-colors">
            Riwayat Selesai ({{ $historyMeetings->count() }})
        </button>
    </div>

    <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-2xl overflow-hidden">

        <div id="content-aktif" class="overflow-x-auto {{ $activeTab === 'aktif' ? 'block' : 'hidden' }}">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#FDF8F3] text-[#4A3018] border-b border-[#D4A373]">
                        <th class="px-6 py-4 font-black text-xs uppercase tracking-wider w-1/3">Proyek & Klien</th>
                        <th class="px-6 py-4 font-black text-xs uppercase tracking-wider w-1/4">Jadwal & Lokasi</th>
                        <th class="px-6 py-4 font-black text-xs uppercase tracking-wider">Agenda Pertemuan</th>
                        <th class="px-6 py-4 font-black text-xs uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F5EBE1]">
                    @forelse($meetings as $tm)
                        <tr class="hover:bg-[#FAFAFA] transition-colors group">
                            <td class="px-6 py-5">
                                <p class="font-black text-[#4A3018] text-base mb-1">{{ $tm->project->nama_proyek }}</p>
                                <div class="space-y-1">
                                    <p class="text-[11px] text-gray-500 uppercase tracking-wide">
                                        <span class="font-bold text-gray-400 w-14 inline-block">Klien</span>:
                                        <span
                                            class="font-semibold text-[#8B5A2B]">{{ $tm->project->client->nama_klien ?? '-' }}</span>
                                    </p>
                                    <p class="text-[11px] text-gray-500 uppercase tracking-wide leading-relaxed">
                                        <span class="font-bold text-gray-400 w-14 inline-block">Vendor</span>:
                                        <span class="font-semibold text-gray-700">
                                            {{ $tm->project->client->vendors->pluck('nama_vendor')->join(', ') ?: 'Belum ada vendor' }}
                                        </span>
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-start gap-2 mb-1.5">
                                    <div>
                                        <p class="font-bold text-blue-700 text-sm">
                                            {{ \Carbon\Carbon::parse($tm->jadwal_tm)->translatedFormat('d F Y') }}
                                        </p>
                                        <p class="text-xs font-semibold text-blue-500">
                                            Pukul {{ \Carbon\Carbon::parse($tm->jadwal_tm)->format('H:i') }} WIB
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2 mt-2">
                                    <p class="text-xs font-medium text-gray-600">Lokasi: {{ $tm->lokasi }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p
                                    class="text-xs text-gray-600 leading-relaxed bg-gray-50 p-2.5 border border-gray-100 rounded-lg">
                                    {{ $tm->agenda }}
                                </p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <form action="{{ route('technical-meetings.mark-done', $tm->id) }}" method="POST"
                                        class="w-full">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                            class="w-full px-4 py-2 text-[11px] font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition-all cursor-pointer"
                                            onclick="localStorage.setItem('tm_active_tab', 'riwayat'); return confirm('Tandai Technical Meeting ini telah selesai dilaksanakan?')">
                                            Selesai
                                        </button>
                                    </form>

                                    <div class="flex gap-2 w-full">
                                        <a href="{{ route('technical-meetings.edit', $tm->id) }}"
                                            class="flex-1 px-2 py-1.5 text-[10px] font-bold text-white bg-amber-500 rounded-lg hover:bg-amber-600 shadow-sm transition-all text-center">
                                            Edit
                                        </a>
                                        <form action="{{ route('technical-meetings.destroy', $tm->id) }}" method="POST"
                                            class="flex-1">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="w-full px-2 py-1.5 text-[10px] font-bold text-white bg-red-500 rounded-lg hover:bg-red-600 shadow-sm transition-all cursor-pointer"
                                                onclick="return confirm('Batalkan dan hapus jadwal ini?')">
                                                Batal
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <p class="font-bold text-gray-500 text-base">Belum Ada Jadwal TM Aktif</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="content-riwayat" class="overflow-x-auto {{ $activeTab === 'riwayat' ? 'block' : 'hidden' }}">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 border-b border-gray-200">
                        <th class="px-6 py-4 font-black text-xs uppercase tracking-wider w-1/3">Proyek & Klien</th>
                        <th class="px-6 py-4 font-black text-xs uppercase tracking-wider w-1/4">Jadwal Terlaksana</th>
                        <th class="px-6 py-4 font-black text-xs uppercase tracking-wider">Agenda Pembahasan</th>
                        <th class="px-6 py-4 font-black text-xs uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-gray-50/50">
                    @forelse($historyMeetings as $tm)
                        <tr class="hover:bg-white transition-colors group">
                            <td class="px-6 py-5">
                                <p class="font-bold text-gray-700 text-sm mb-1">{{ $tm->project->nama_proyek }}</p>
                                <p class="text-[10px] text-gray-500 uppercase">
                                    Klien: {{ $tm->project->client->nama_klien ?? '-' }}
                                </p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-start gap-2 mb-1.5 opacity-70">
                                    <div>
                                        <p class="font-bold text-gray-600 text-sm">
                                            {{ \Carbon\Carbon::parse($tm->jadwal_tm)->translatedFormat('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-xs text-gray-500 line-clamp-2">
                                    {{ $tm->agenda }}
                                </p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span
                                    class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black rounded-full border border-green-200">
                                    Telah Selesai
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <p class="font-bold text-gray-500 text-base">Belum Ada Riwayat TM</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            const savedTab = tabParam || '{{ $activeTab }}' || localStorage.getItem('tm_active_tab') || 'aktif';
            switchTab(savedTab, true);
        });

        function switchTab(tab, save = true) {
            if (save) {
                localStorage.setItem('tm_active_tab', tab);
            }
            // Setup Element
            const contentAktif = document.getElementById('content-aktif');
            const contentRiwayat = document.getElementById('content-riwayat');
            const btnAktif = document.getElementById('tab-aktif');
            const btnRiwayat = document.getElementById('tab-riwayat');

            // Reset Style
            btnAktif.className =
                "pb-3 text-sm font-bold text-gray-400 border-b-2 border-transparent hover:text-[#8B5A2B] uppercase tracking-wide transition-colors";
            btnRiwayat.className =
                "pb-3 text-sm font-bold text-gray-400 border-b-2 border-transparent hover:text-[#8B5A2B] uppercase tracking-wide transition-colors";

            if (tab === 'aktif') {
                contentAktif.classList.remove('hidden');
                contentAktif.classList.add('block');
                contentRiwayat.classList.remove('block');
                contentRiwayat.classList.add('hidden');

                // Active Style
                btnAktif.className =
                    "pb-3 text-sm font-black text-[#8B5A2B] border-b-2 border-[#8B5A2B] uppercase tracking-wide transition-colors";
            } else {
                contentRiwayat.classList.remove('hidden');
                contentRiwayat.classList.add('block');
                contentAktif.classList.remove('block');
                contentAktif.classList.add('hidden');

                // Active Style
                btnRiwayat.className =
                    "pb-3 text-sm font-black text-[#8B5A2B] border-b-2 border-[#8B5A2B] uppercase tracking-wide transition-colors";
            }
        }
    </script>
@endsection
