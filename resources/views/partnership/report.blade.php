@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-black text-[#4A3018] tracking-tight">Laporan Rekapitulasi Terpadu</h2>
                <p class="text-[#8B5A2B] mt-1 font-medium">Rekapan seluruh vendor aktif, data klien, dan event yang sedang
                    berjalan.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-[#F5EBE1] shadow-sm text-center">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total Vendor Aktif</p>
                <p class="text-3xl font-black text-[#8B5A2B]">{{ $totalVendor }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-[#F5EBE1] shadow-sm text-center">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total Klien Terdaftar</p>
                <p class="text-3xl font-black text-[#8B5A2B]">{{ $totalKlien }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-[#F5EBE1] shadow-sm text-center">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Event Sedang Berjalan</p>
                <p class="text-3xl font-black text-green-700">{{ $eventBerjalan }}</p>
            </div>
        </div>

        {{-- PEMILIHAN DATA YANG INGIN DI-PDF --}}
        <div class="bg-white border border-[#F5EBE1] rounded-2xl shadow-sm p-6 mb-6" x-data="{ selected: 'semua' }">
            <h3 class="text-sm font-bold text-[#4A3018] uppercase tracking-wide mb-4">📄 Pilih Data untuk Diekspor ke PDF</h3>
            <p class="text-xs text-gray-500 mb-5">Pilih data mana yang ingin Anda sertakan dalam laporan PDF. Klik tombol "Unduh PDF" setelah memilih.</p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                {{-- Opsi: Semua Data --}}
                <label class="cursor-pointer">
                    <input type="radio" x-model="selected" value="semua" class="sr-only peer">
                    <div class="p-4 rounded-xl border-2 border-[#F5EBE1] text-center transition-all peer-checked:border-[#8B5A2B] peer-checked:bg-[#FDF8F3] hover:border-[#D4A373]">
                        <div class="text-2xl mb-2">📋</div>
                        <p class="text-xs font-bold text-[#4A3018]">Semua Data</p>
                        <p class="text-[10px] text-gray-400 mt-1">Vendor + Klien + Event</p>
                    </div>
                </label>

                {{-- Opsi: Data Vendor Saja --}}
                <label class="cursor-pointer">
                    <input type="radio" x-model="selected" value="vendor" class="sr-only peer">
                    <div class="p-4 rounded-xl border-2 border-[#F5EBE1] text-center transition-all peer-checked:border-[#8B5A2B] peer-checked:bg-[#FDF8F3] hover:border-[#D4A373]">
                        <div class="text-2xl mb-2">🤝</div>
                        <p class="text-xs font-bold text-[#4A3018]">Data Vendor</p>
                        <p class="text-[10px] text-gray-400 mt-1">Rekap mitra vendor aktif</p>
                    </div>
                </label>

                {{-- Opsi: Data Klien Saja --}}
                <label class="cursor-pointer">
                    <input type="radio" x-model="selected" value="klien" class="sr-only peer">
                    <div class="p-4 rounded-xl border-2 border-[#F5EBE1] text-center transition-all peer-checked:border-[#8B5A2B] peer-checked:bg-[#FDF8F3] hover:border-[#D4A373]">
                        <div class="text-2xl mb-2">👤</div>
                        <p class="text-xs font-bold text-[#4A3018]">Data Klien</p>
                        <p class="text-[10px] text-gray-400 mt-1">Daftar seluruh klien</p>
                    </div>
                </label>

                {{-- Opsi: Data Event Saja --}}
                <label class="cursor-pointer">
                    <input type="radio" x-model="selected" value="event" class="sr-only peer">
                    <div class="p-4 rounded-xl border-2 border-[#F5EBE1] text-center transition-all peer-checked:border-[#8B5A2B] peer-checked:bg-[#FDF8F3] hover:border-[#D4A373]">
                        <div class="text-2xl mb-2">📅</div>
                        <p class="text-xs font-bold text-[#4A3018]">Data Event</p>
                        <p class="text-[10px] text-gray-400 mt-1">Status kinerja event</p>
                    </div>
                </label>
            </div>

            {{-- Tombol Download --}}
            <a :href="'{{ route('partnership.report.download') }}?data=' + selected"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#4A3018] text-white font-bold rounded-xl shadow-md hover:bg-[#2A1B0E] transition-transform hover:-translate-y-1 uppercase tracking-wide text-sm">
                📄 Unduh Sebagai PDF
            </a>

            <p class="text-[11px] text-gray-400 mt-3">
                Laporan ini dapat langsung dicetak atau diberikan kepada Manager Commercial.
            </p>
        </div>
    </div>
@endsection
