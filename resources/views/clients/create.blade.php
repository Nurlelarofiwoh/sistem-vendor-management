@extends('layouts.app')

@section('content')
    @php
        // 1. Ambil semua vendor aktif dan ekstrak kotanya
        $allVendors = \App\Models\Vendor::where('status_aktif', true)
            ->get()
            ->map(function ($v) {
                $parts = explode(',', $v->alamat ?? '');
                return [
                    'id' => $v->id,
                    'nama' => $v->nama_vendor,
                    'kategori' => $v->kategori_jasa,
                    'kota' => trim(end($parts)),
                    'rating' => $v->rating,
                    'harga' => $v->harga,
                    'link' => $v->link_portofolio,
                ];
            });

        // 2. Buat daftar Kota unik untuk dropdown Lokasi
        $lokasiTersedia = $allVendors->pluck('kota')->unique()->filter()->sort()->values();
    @endphp

    <div class="max-w-6xl mx-auto" x-data="{
        lokasiPilihan: '',
        kategoriBaru: '',
        listKebutuhan: [],
        budget: '',
        dataVendor: {{ Js::from($allVendors) }},
    
        tambahKategori() {
            if (this.kategoriBaru && !this.listKebutuhan.some(k => k.kategori === this.kategoriBaru)) {
                this.listKebutuhan.push({ kategori: this.kategoriBaru, detail: {} });
                this.kategoriBaru = '';
            }
        },
        hapusKategori(index) {
            this.listKebutuhan.splice(index, 1);
        },
    
        // FUNGSI LIVE PREVIEW: Mencari vendor yang cocok dengan Lokasi & Kategori terpilih
        get vendorTersedia() {
            if (!this.lokasiPilihan || this.listKebutuhan.length === 0) return [];
            let kategoriDiminta = this.listKebutuhan.map(k => k.kategori);
    
            return this.dataVendor.filter(v => {
                const matchLocation = v.kota.toLowerCase().includes(this.lokasiPilihan.toLowerCase());
                const matchCategory = kategoriDiminta.includes(v.kategori);
                const matchBudget = !this.budget || Number(v.harga) <= Number(this.budget);
                return matchLocation && matchCategory && matchBudget;
            });
        },
    
        get kompilasiKebutuhan() {
            let hasil = '';
            this.listKebutuhan.forEach((item) => {
                hasil += item.kategori + '\n----------------------------------\n';
                for (let key in item.detail) {
                    if (item.detail[key] && item.detail[key].trim() !== '') {
                        let label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        hasil += label + ' : ' + item.detail[key] + '\n';
                    }
                }
                hasil += '\n';
            });
            return hasil.trim();
        }
    }">

        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('clients.index') }}" class="text-[#8B5A2B] hover:text-[#4A3018]">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-3xl font-bold text-[#4A3018]">Input Kebutuhan Klien Fleksibel</h2>
                <p class="text-[#8B5A2B] mt-1">Sistem Cerdas: Lokasi -> Kategori -> Live Preview Vendor.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white border border-[#F5EBE1] shadow-md rounded-2xl p-8 h-fit">
                <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <h3 class="font-bold text-lg text-[#4A3018] border-b pb-2 mb-4">1. Tentukan Lokasi & Biodata Klien</h3>

                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl mb-6">
                        <label class="block mb-2 text-sm font-black text-blue-900">📍 Lokasi Acara (Kota)</label>
                        <select name="tempat_acara" x-model="lokasiPilihan" required
                            class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:ring-blue-500 font-bold text-blue-800">
                            <option value="">-- Pilih Kota Tempat Acara --</option>
                            @foreach ($lokasiTersedia as $lokasi)
                                <option value="{{ $lokasi }}">{{ $lokasi }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-blue-700 mt-1">*Hanya kota dengan vendor aktif yang ditampilkan di sini.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div><label class="block mb-1 text-sm font-bold text-[#4A3018]">Nama Klien</label><input
                                type="text" name="nama_klien" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg"></div>
                        <div><label class="block mb-1 text-sm font-bold text-[#4A3018]">Instansi/Perusahaan</label><input
                                type="text" name="instansi" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg"></div>
                        <div><label class="block mb-1 text-sm font-bold text-[#4A3018]">Email</label><input type="email"
                                name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg"></div>
                        <div><label class="block mb-1 text-sm font-bold text-[#4A3018]">No. Telepon / WA</label><input
                                type="text" name="no_telepon" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg"></div>
                        <div class="md:col-span-2"><label class="block mb-1 text-sm font-bold text-[#4A3018]">Tanggal
                                Acara</label><input type="date" name="tanggal_acara" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg"></div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Budget Klien (Rp)</label>
                            <input type="number" name="budget" x-model="budget" placeholder="Contoh: 50000000" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#8B5A2B]">
                        </div>
                    </div>

                    <h3 class="font-bold text-lg text-[#4A3018] border-b pb-2 mt-8 mb-4" x-show="lokasiPilihan !== ''">2.
                        Pilih Kategori & Spesifikasi</h3>

                    <div class="flex gap-4 mb-6" x-show="lokasiPilihan !== ''">
                        <select x-model="kategoriBaru"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#8B5A2B] font-bold">
                            <option value="">-- Tambah Kategori Jasa --</option>
                            <option value="Venue">Venue</option>
                            <option value="Catering">Catering</option>
                            <option value="Dekorasi">Dekorasi</option>
                            <option value="Dokumentasi">Dokumentasi</option>
                            <option value="Sound System">Sound System</option>
                            <option value="Entertainment">Entertainment</option>
                            <option value="Attire">Attire</option>
                            <option value="Makeup Artist">Makeup Artist</option>
                        </select>
                        <button type="button" @click="tambahKategori()"
                            class="px-6 py-3 bg-[#4A3018] text-white font-bold rounded-lg hover:bg-[#8B5A2B] shadow-sm">
                            + Tambah
                        </button>
                    </div>

                    <template x-for="(item, index) in listKebutuhan" :key="index">
                        <div class="p-5 bg-[#F9F6F0] border border-[#D4A373] rounded-lg mb-4 relative shadow-sm">
                            <button type="button" @click="hapusKategori(index)"
                                class="absolute top-4 right-4 text-red-500 font-bold hover:text-red-700 bg-red-100 px-2 py-1 text-xs rounded">❌
                                Hapus</button>
                            <h4 class="font-black text-[#8B5A2B] mb-3 text-lg uppercase" x-text="item.kategori"></h4>

                            <div class="grid grid-cols-2 gap-3">
                                <template x-if="item.kategori === 'Venue'">
                                    <div class="col-span-2 grid grid-cols-2 gap-3">
                                        <div><label class="text-xs font-bold text-gray-700">Kapasitas</label><select
                                                x-model="item.detail.kapasitas"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>
                                                    < 500 Pax</option>
                                                <option>500 - 1000 Pax</option>
                                                <option>> 1000 Pax</option>
                                            </select></div>
                                        <div><label class="text-xs font-bold text-gray-700">Tipe Venue</label><select
                                                x-model="item.detail.tipe_venue"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>Indoor (Gedung)</option>
                                                <option>Outdoor (Taman)</option>
                                                <option>Semi-Outdoor</option>
                                            </select></div>
                                    </div>
                                </template>

                                <template x-if="item.kategori === 'Catering'">
                                    <div class="col-span-2 grid grid-cols-2 gap-3">
                                        <div><label class="text-xs font-bold text-gray-700">Porsi</label><select
                                                x-model="item.detail.jumlah_porsi"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>
                                                    < 500 Porsi</option>
                                                <option>500 - 1000 Porsi</option>
                                                <option>> 1000 Porsi</option>
                                            </select></div>
                                        <div><label class="text-xs font-bold text-gray-700">Penyajian</label><select
                                                x-model="item.detail.jenis_penyajian"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>Prasmanan (Buffet)</option>
                                                <option>Stall (Gubukan)</option>
                                                <option>Mix (Prasmanan & Stall)</option>
                                            </select></div>
                                        <div><label class="text-xs font-bold text-gray-700">Sertifikasi
                                                Halal</label><select x-model="item.detail.sertifikasi"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Wajib Halal</option>
                                                <option>Non-Halal / Bebas</option>
                                            </select></div>
                                    </div>
                                </template>

                                <template x-if="item.kategori === 'Dekorasi'">
                                    <div class="col-span-2 grid grid-cols-2 gap-3">
                                        <div><label class="text-xs font-bold text-gray-700">Tema</label><select
                                                x-model="item.detail.tema_dekorasi"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>Modern Minimalis</option>
                                                <option>Tradisional Klasik</option>
                                                <option>Rustic Garden</option>
                                                <option>Glamour Luxury</option>
                                            </select></div>
                                        <div><label class="text-xs font-bold text-gray-700">Bunga</label><select
                                                x-model="item.detail.jenis_bunga"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>Bunga Asli (Fresh)</option>
                                                <option>Bunga Artificial</option>
                                                <option>Mix Asli & Artificial</option>
                                            </select></div>
                                    </div>
                                </template>

                                <template x-if="item.kategori === 'Dokumentasi'">
                                    <div class="col-span-2 grid grid-cols-2 gap-3">
                                        <div><label class="text-xs font-bold text-gray-700">Output Utama</label><select
                                                x-model="item.detail.output_hasil"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>Foto Saja</option>
                                                <option>Video / Cinematic Saja</option>
                                                <option>Foto & Video Lengkap</option>
                                            </select></div>
                                        <div><label class="text-xs font-bold text-gray-700">Sesi Prewedding</label><select
                                                x-model="item.detail.sesi_prewedding"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>Wajib Include</option>
                                                <option>Tidak Perlu</option>
                                            </select></div>
                                    </div>
                                </template>

                                <template x-if="item.kategori === 'Sound System'">
                                    <div class="col-span-2 grid grid-cols-2 gap-3">
                                        <div><label class="text-xs font-bold text-gray-700">Kapasitas Daya</label><select
                                                x-model="item.detail.total_daya"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>
                                                    < 5.000 Watt</option>
                                                <option>5.000 - 10.000 Watt</option>
                                                <option>> 10.000 Watt</option>
                                            </select></div>
                                        <div><label class="text-xs font-bold text-gray-700">Genset</label><select
                                                x-model="item.detail.genset"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>Wajib Bawa Genset</option>
                                            </select></div>
                                    </div>
                                </template>

                                <template x-if="item.kategori === 'Entertainment'">
                                    <div class="col-span-2 grid grid-cols-2 gap-3">
                                        <div><label class="text-xs font-bold text-gray-700">Format
                                                Penampilan</label><select x-model="item.detail.format_penampilan"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>Solo Keyboard / Piano</option>
                                                <option>Akustik (2-3 Orang)</option>
                                                <option>Full Band</option>
                                                <option>Tradisional (Gamelan/Kecapi)</option>
                                            </select></div>
                                    </div>
                                </template>

                                <template x-if="item.kategori === 'Attire' || item.kategori === 'Makeup Artist'">
                                    <div class="col-span-2 grid grid-cols-2 gap-3">
                                        <div><label class="text-xs font-bold text-gray-700">Look / Gaya</label><select
                                                x-model="item.detail.look_gaya"
                                                class="w-full border-gray-300 rounded text-sm">
                                                <option value="">Bebas</option>
                                                <option>Tradisional Adat</option>
                                                <option>Modern Internasional</option>
                                                <option>Hijab / Syar'i</option>
                                            </select></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <textarea name="kebutuhan_klien" :value="kompilasiKebutuhan" class="hidden" required></textarea>

                    <div class="pt-4 border-t border-[#F5EBE1]">
                        <button type="submit" x-bind:disabled="!lokasiPilihan || listKebutuhan.length === 0"
                            class="w-full py-4 bg-[#8B5A2B] text-white font-black rounded-xl hover:bg-[#4A3018] shadow-md text-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                            💾 Simpan & Cetak Rekomendasi PDF
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-span-1">
                <div
                    class="sticky top-6 bg-[#4A3018] border border-[#8B5A2B] shadow-xl rounded-2xl p-6 text-white min-h-[400px]">
                    <h3 class="font-black text-xl border-b border-[#8B5A2B] pb-3 mb-4 flex items-center gap-2">
                        <span>📡</span> Live Preview Vendor
                    </h3>

                    <div x-show="!lokasiPilihan" class="text-center text-[#D4A373] mt-10">
                        <div class="text-4xl mb-2">📍</div>
                        <p class="font-bold text-sm">Pilih Lokasi Acara terlebih dahulu untuk melihat vendor yang tersedia.
                        </p>
                    </div>

                    <div x-show="lokasiPilihan !== '' && listKebutuhan.length === 0"
                        class="text-center text-[#D4A373] mt-10">
                        <div class="text-4xl mb-2">🏷️</div>
                        <p class="font-bold text-sm">Lokasi terpilih: <span class="text-white"
                                x-text="lokasiPilihan"></span>.</p>
                        <p class="text-xs mt-1">Silakan tambah kategori jasa yang dicari.</p>
                    </div>

                    <div x-show="vendorTersedia.length > 0"
                        class="space-y-4 max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                        <p class="text-xs text-[#D4A373] font-bold mb-2">Ditemukan <span x-text="vendorTersedia.length"
                                class="text-white bg-[#8B5A2B] px-2 py-0.5 rounded"></span> vendor di <span
                                x-text="lokasiPilihan"></span>:</p>

                        <template x-for="vendor in vendorTersedia" :key="vendor.id">
                            <div class="bg-white text-gray-800 p-3 rounded-lg shadow-sm border-l-4 border-[#D4A373]">
                                <div class="flex justify-between items-start">
                                    <p class="font-bold text-sm text-[#4A3018]" x-text="vendor.nama"></p>
                                    <span class="text-[10px] font-black bg-[#F5EBE1] text-[#8B5A2B] px-2 py-0.5 rounded"
                                        x-text="vendor.kategori"></span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">⭐ <span x-text="vendor.rating"></span>/5.0 | 💰
                                    <span x-text="vendor.harga ? 'Rp ' + Number(vendor.harga).toLocaleString('id-ID') : '-'"></span>
                                </p>
                            </div>
                        </template>
                    </div>

                    <div x-show="lokasiPilihan !== '' && listKebutuhan.length > 0 && vendorTersedia.length === 0"
                        class="text-center text-red-300 mt-10 p-4 border border-red-400 border-dashed rounded-lg bg-red-900 bg-opacity-20">
                        <p class="font-bold text-sm">⚠️ Vendor Tidak Ditemukan</p>
                        <p class="text-xs mt-2">Tidak ada vendor untuk kategori tersebut di kota <span
                                x-text="lokasiPilihan"></span>.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #8B5A2B;
            border-radius: 4px;
        }
    </style>
@endsection
