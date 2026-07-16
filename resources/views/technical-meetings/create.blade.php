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
                <h2 class="text-3xl font-bold text-[#4A3018]">Buat Jadwal TM</h2>
            </div>
        </div>

        <div class="bg-white border border-[#F5EBE1] shadow-md rounded-2xl p-8">

            <div
                class="p-4 mb-6 bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-xl flex items-start gap-3 shadow-sm">
                <span class="text-xl">💡</span>
                <p><strong>Sistem Terotomatisasi:</strong> Undangan Technical Meeting akan langsung dikirimkan ke alamat
                    Email Klien dan Email Vendor sesaat setelah Anda menekan tombol simpan.</p>
            </div>

            <form action="{{ route('technical-meetings.store') }}" method="POST" id="tmForm" onsubmit="disableButton()"
                class="space-y-6">
                @csrf

                <div>
                    <label class="block mb-2 text-sm font-bold text-[#4A3018]">Pilih Proyek (Klien & Vendor)</label>

                    <select name="project_id" id="project_select" required onchange="tampilkanEmail()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#D4A373] focus:border-[#D4A373] bg-gray-50 text-gray-800">
                        <option value="" disabled selected>-- Pilih Proyek yang Berjalan --</option>
                        @foreach ($projects as $proj)
                            @php
                                // Mengambil data email, filter() digunakan untuk membuang vendor yang tidak punya email
                                $emailKlien = $proj->client->email ?? 'Tidak ada email terdaftar';
                                $emailVendors =
                                    $proj->client->vendors->pluck('email')->filter()->join(', ') ?:
                                    'Tidak ada email terdaftar';
                            @endphp

                            <option value="{{ $proj->id }}" data-email-klien="{{ $emailKlien }}"
                                data-email-vendor="{{ $emailVendors }}">
                                {{ $proj->nama_proyek }} (Klien: {{ $proj->client->nama_klien ?? '-' }})
                            </option>
                        @endforeach
                    </select>

                    <div id="infoPenerima"
                        class="hidden mt-3 p-4 bg-[#FDF8F3] border border-[#D4A373] rounded-lg shadow-inner text-xs text-gray-700">
                        <p class="font-bold text-[#8B5A2B] tracking-wide uppercase mb-2 border-b border-[#D4A373] pb-1">🎯
                            Target Penerima Email Undangan:</p>
                        <div class="space-y-1.5">
                            <p>👤 <strong class="text-gray-500 uppercase text-[10px] w-14 inline-block">Klien:</strong>
                                <span id="textEmailKlien" class="font-semibold text-blue-600"></span></p>
                            <p>🏢 <strong class="text-gray-500 uppercase text-[10px] w-14 inline-block">Vendor:</strong>
                                <span id="textEmailVendor" class="font-semibold text-blue-600"></span></p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-[#4A3018]">Tanggal & Jam (WIB)</label>
                        <input type="datetime-local" name="jadwal_tm" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#D4A373] focus:border-[#D4A373] bg-gray-50">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-[#4A3018]">Lokasi Pertemuan</label>
                        <input type="text" name="lokasi" required placeholder="Contoh: Gedung ABC / Zoom Link"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#D4A373] focus:border-[#D4A373] bg-gray-50">
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-[#4A3018]">Agenda Pembahasan</label>
                    <textarea name="agenda" rows="3" required placeholder="Contoh: Survei ukuran tenda roder dan tes food tasting"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#D4A373] focus:border-[#D4A373] bg-gray-50 resize-none"></textarea>
                </div>

                <div class="flex justify-end pt-4 mt-6 border-t border-[#F5EBE1]">
                    <button type="submit" id="btnSubmit"
                        class="px-6 py-3 bg-[#8B5A2B] text-white font-bold rounded-xl shadow-sm hover:bg-[#4A3018] transition-all">
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi 1: Menampilkan Email saat dropdown dipilih
        function tampilkanEmail() {
            const select = document.getElementById('project_select');
            const selectedOption = select.options[select.selectedIndex];

            // Jika yang dipilih bukan placeholder kosong
            if (selectedOption.value !== "") {
                // Munculkan kotaknya
                document.getElementById('infoPenerima').classList.remove('hidden');

                // Isi teksnya sesuai data yang disisipkan tadi
                document.getElementById('textEmailKlien').innerText = selectedOption.getAttribute('data-email-klien');
                document.getElementById('textEmailVendor').innerText = selectedOption.getAttribute('data-email-vendor');
            }
        }

        // Fungsi 2: Mencegah Double Submit
        function disableButton() {
            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.innerHTML = 'Menyimpan Jadwal... ⏳';
            btn.classList.add('opacity-70', 'cursor-not-allowed');
        }
    </script>
@endsection
