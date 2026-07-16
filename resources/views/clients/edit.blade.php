@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto" x-data="{
        listKebutuhan: [],
        kategoriBaru: '',
    
        init() {
            let rawData = {{ Js::from($client->kebutuhan_klien) }};
            if (rawData) {
                let blocks = rawData.split('\n\n');
                blocks.forEach(block => {
                    let lines = block.split('\n');
                    if (lines.length >= 2 && lines[1].includes('---')) {
                        let cat = lines[0].trim();
                        let det = {};
                        for (let i = 2; i < lines.length; i++) {
                            if (lines[i].includes(' : ')) {
                                let parts = lines[i].split(' : ');
                                let key = parts[0].trim().toLowerCase().replace(/ /g, '_');
                                det[key] = parts.slice(1).join(' : ').trim();
                            }
                        }
                        this.listKebutuhan.push({ kategori: cat, detail: det });
                    }
                });
            }
        },
    
        tambahKategori() {
            if (this.kategoriBaru && !this.listKebutuhan.some(k => k.kategori === this.kategoriBaru)) {
                this.listKebutuhan.push({ kategori: this.kategoriBaru, detail: {} });
                this.kategoriBaru = '';
            }
        },
    
        hapusKategori(index) {
            this.listKebutuhan.splice(index, 1);
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
                <h2 class="text-3xl font-bold text-[#4A3018]">Edit Data Klien</h2>
                <p class="text-[#8B5A2B] mt-1">Perbarui biodata klien dan spesifikasi vendor yang dicari.</p>
            </div>
        </div>

        <div class="bg-white border border-[#F5EBE1] shadow-md rounded-2xl p-8">
            <form action="{{ route('clients.update', $client->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <h3 class="font-bold text-lg text-[#4A3018] border-b pb-2 mb-4">Biodata Klien</h3>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-[#4A3018]">Nama PIC / Klien</label>
                        <input type="text" name="nama_klien" value="{{ $client->nama_klien }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-[#4A3018]">Instansi / Perusahaan</label>
                        <input type="text" name="instansi" value="{{ $client->instansi }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-[#4A3018]">Email</label>
                        <input type="email" name="email" value="{{ $client->email }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-[#4A3018]">No. Telepon / WA</label>
                        <input type="text" name="no_telepon" value="{{ $client->no_telepon }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-[#4A3018]">Tanggal Acara</label>
                        <input type="date" name="tanggal_acara" value="{{ $client->tanggal_acara }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-[#4A3018]">Tempat Pelaksanaan (Kota)</label>
                        <input type="text" name="tempat_acara" value="{{ $client->tempat_acara }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <h3 class="font-bold text-lg text-[#4A3018] border-b pb-2 mt-8 mb-4">Spesifikasi Vendor</h3>

                <div class="flex gap-4 mb-6">
                    <select x-model="kategoriBaru" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg">
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
                        class="px-6 py-3 bg-[#4A3018] text-white font-bold rounded-lg hover:bg-[#8B5A2B] transition-colors">
                        + Tambah Kategori
                    </button>
                </div>

                <template x-for="(item, index) in listKebutuhan" :key="index">
                    <div class="p-6 bg-gray-50 border border-[#D4A373] rounded-lg mb-6 relative shadow-sm">
                        <button type="button" @click="hapusKategori(index)"
                            class="absolute top-4 right-4 text-red-500 font-bold hover:text-red-700 bg-red-100 px-3 py-1 rounded">
                            ❌ Hapus
                        </button>

                        <h4 class="font-black text-[#8B5A2B] mb-4 text-xl border-b border-gray-300 pb-2 uppercase tracking-wide"
                            x-text="item.kategori"></h4>

                        <div class="grid grid-cols-2 gap-4">

                            <template x-if="item.kategori === 'Venue'">
                                <div class="col-span-2 grid grid-cols-2 gap-4">
                                    <div><label class="text-sm font-bold text-gray-700">Kapasitas</label><input
                                            type="text" x-model="item.detail.kapasitas"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                    <div><label class="text-sm font-bold text-gray-700">Tipe (Indoor/Outdoor)</label><input
                                            type="text" x-model="item.detail.tipe_venue"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                    <div class="col-span-2"><label class="text-sm font-bold text-gray-700">Fasilitas Wajib
                                            (MC/Sound dll)</label><input type="text"
                                            x-model="item.detail.fasilitas_wajib"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                </div>
                            </template>

                            <template x-if="item.kategori === 'Catering'">
                                <div class="col-span-2 grid grid-cols-2 gap-4">
                                    <div><label class="text-sm font-bold text-gray-700">Jumlah Porsi (Pax)</label><input
                                            type="text" x-model="item.detail.jumlah_porsi"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                    <div><label class="text-sm font-bold text-gray-700">Penyajian</label><input
                                            type="text" x-model="item.detail.jenis_penyajian"
                                            placeholder="Prasmanan/Stall" class="w-full border-gray-300 rounded text-sm">
                                    </div>
                                    <div><label class="text-sm font-bold text-gray-700">Sertifikasi Wajib</label><select
                                            x-model="item.detail.sertifikasi"
                                            class="w-full border-gray-300 rounded text-sm">
                                            <option value="">Bebas</option>
                                            <option>Halal</option>
                                            <option>Non-Halal</option>
                                        </select></div>
                                    <div><label class="text-sm font-bold text-gray-700">Butuh Test Food?</label><select
                                            x-model="item.detail.test_food"
                                            class="w-full border-gray-300 rounded text-sm">
                                            <option value="">Bebas</option>
                                            <option>Ya, Wajib</option>
                                        </select></div>
                                </div>
                            </template>

                            <template x-if="item.kategori === 'Dekorasi'">
                                <div class="col-span-2 grid grid-cols-2 gap-4">
                                    <div class="col-span-2"><label class="text-sm font-bold text-gray-700">Tema Dekorasi
                                            Impian</label><input type="text" x-model="item.detail.tema_dekorasi"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                    <div><label class="text-sm font-bold text-gray-700">Jenis Bunga</label><select
                                            x-model="item.detail.jenis_bunga"
                                            class="w-full border-gray-300 rounded text-sm">
                                            <option value="">Bebas</option>
                                            <option>Fresh (Asli)</option>
                                            <option>Artificial (Palsu)</option>
                                        </select></div>
                                    <div><label class="text-sm font-bold text-gray-700">Ukuran Panggung
                                            (Estimasi)</label><input type="text" x-model="item.detail.ukuran_panggung"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                </div>
                            </template>

                            <template x-if="item.kategori === 'Dokumentasi'">
                                <div class="col-span-2 grid grid-cols-2 gap-4">
                                    <div><label class="text-sm font-bold text-gray-700">Jumlah Kru Diminta</label><input
                                            type="number" x-model="item.detail.jumlah_kru"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                    <div><label class="text-sm font-bold text-gray-700">Output Hasil</label><input
                                            type="text" x-model="item.detail.output_hasil"
                                            placeholder="Album/Cinematic" class="w-full border-gray-300 rounded text-sm">
                                    </div>
                                </div>
                            </template>

                            <template x-if="item.kategori === 'Sound System'">
                                <div class="col-span-2 grid grid-cols-2 gap-4">
                                    <div><label class="text-sm font-bold text-gray-700">Total Daya Dibutuhkan</label><input
                                            type="text" x-model="item.detail.total_daya"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                    <div><label class="text-sm font-bold text-gray-700">Wajib Ada Genset?</label><select
                                            x-model="item.detail.wajib_genset"
                                            class="w-full border-gray-300 rounded text-sm">
                                            <option value="">Bebas</option>
                                            <option>Ya</option>
                                            <option>Tidak</option>
                                        </select></div>
                                </div>
                            </template>

                            <template x-if="item.kategori === 'Entertainment'">
                                <div class="col-span-2 grid grid-cols-2 gap-4">
                                    <div><label class="text-sm font-bold text-gray-700">Format Penampilan</label><input
                                            type="text" x-model="item.detail.format_penampilan"
                                            placeholder="Band/Akustik/Solo"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                    <div><label class="text-sm font-bold text-gray-700">Jumlah Personil</label><input
                                            type="number" x-model="item.detail.jumlah_personil"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                </div>
                            </template>

                            <template x-if="item.kategori === 'Attire'">
                                <div class="col-span-2 grid grid-cols-2 gap-4">
                                    <div><label class="text-sm font-bold text-gray-700">Gaya Busana
                                            (Adat/Modern)</label><input type="text" x-model="item.detail.gaya_busana"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                    <div><label class="text-sm font-bold text-gray-700">Kebutuhan Ukuran</label><input
                                            type="text" x-model="item.detail.ukuran"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                </div>
                            </template>

                            <template x-if="item.kategori === 'Makeup Artist'">
                                <div class="col-span-2 grid grid-cols-2 gap-4">
                                    <div><label class="text-sm font-bold text-gray-700">Look Makeup</label><input
                                            type="text" x-model="item.detail.look_makeup" placeholder="Flawless/Bold"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                    <div><label class="text-sm font-bold text-gray-700">Kapasitas Rias</label><input
                                            type="text" x-model="item.detail.kapasitas_rias"
                                            class="w-full border-gray-300 rounded text-sm"></div>
                                </div>
                            </template>

                        </div>
                    </div>
                </template>

                <div>
                    <label class="block mb-2 text-sm font-bold text-[#4A3018]">Rangkuman Kebutuhan Sistem
                        (Otomatis)</label>
                    <textarea name="kebutuhan_klien" :value="kompilasiKebutuhan" rows="6" required readonly
                        class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 font-mono text-sm resize-none focus:outline-none"></textarea>
                </div>

                <div class="flex justify-end pt-4 mt-6 border-t border-[#F5EBE1]">
                    <button type="submit"
                        class="px-6 py-3 bg-[#8B5A2B] text-white font-bold rounded-lg hover:bg-[#4A3018] shadow-md transition-colors">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
