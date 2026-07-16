@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('technical-meetings.index') }}" class="text-[#8B5A2B] hover:text-[#4A3018] transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-3xl font-bold text-[#4A3018]">Reschedule Jadwal TM</h2>
            </div>
        </div>

        <div class="bg-white border border-[#F5EBE1] shadow-md rounded-2xl p-8">
            
            <form action="{{ route('technical-meetings.update', $meeting->id) }}" method="POST" id="tmForm" onsubmit="disableButton()" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block mb-2 text-sm font-bold text-[#4A3018]">Pilih Proyek</label>
                    <select name="project_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#D4A373] focus:border-[#D4A373] bg-gray-50 text-gray-800">
                        @foreach ($projects as $proj)
                            <option value="{{ $proj->id }}" {{ $meeting->project_id == $proj->id ? 'selected' : '' }}>
                                {{ $proj->nama_proyek }} (Klien: {{ $proj->client->nama_klien ?? '-' }} | Vendor: {{ $proj->client->vendors->pluck('nama_vendor')->join(', ') ?: 'Belum ada vendor' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-[#4A3018]">Tanggal & Jam Baru (WIB)</label>
                        <input type="datetime-local" name="jadwal_tm"
                            value="{{ date('Y-m-d\TH:i', strtotime($meeting->jadwal_tm)) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#D4A373] focus:border-[#D4A373] bg-gray-50">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-[#4A3018]">Lokasi Pertemuan</label>
                        <input type="text" name="lokasi" value="{{ $meeting->lokasi }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#D4A373] focus:border-[#D4A373] bg-gray-50">
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-[#4A3018]">Agenda Pembahasan</label>
                    <textarea name="agenda" rows="3" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#D4A373] focus:border-[#D4A373] bg-gray-50 resize-none">{{ $meeting->agenda }}</textarea>
                </div>

                <div class="flex justify-end pt-4 mt-6 border-t border-[#F5EBE1]">
                    <button type="submit" id="btnSubmit"
                        class="px-6 py-3 bg-[#8B5A2B] text-white font-bold rounded-xl shadow-sm hover:bg-[#4A3018] transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function disableButton() {
            const btn = document.getElementById('btnSubmit');
            btn.disabled = true; // Kunci tombol
            btn.innerHTML = 'Menyimpan Perubahan... ⏳'; // Indikator loading
            btn.classList.add('opacity-70', 'cursor-not-allowed'); // Efek visual redup
        }
    </script>
@endsection