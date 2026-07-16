@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-4xl mx-auto" x-data="{ kategori: '' }">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-[#4A3018]">Tambah Vendor Baru</h2>
                <p class="text-[#8B5A2B] mt-1">Daftarkan mitra vendor baru beserta rincian spesifikasi jasanya.</p>
            </div>
            <a href="{{ route('vendors.index') }}"
                class="px-4 py-2 text-sm font-bold text-[#8B5A2B] bg-white border border-[#8B5A2B] rounded-lg hover:bg-gray-50 transition-colors uppercase tracking-wide">
                Kembali
            </a>
        </div>

        <form action="{{ route('vendors.store') }}" method="POST" id="formCreateVendor"
            @submit.prevent="
                Swal.fire({
                    title: 'Konfirmasi Pendaftaran',
                    text: 'Apakah Anda yakin seluruh data dasar dan spesifikasi vendor ini sudah benar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#8B5A2B',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Simpan Vendor',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $el.submit();
                    }
                })
            "
            class="bg-white shadow-sm rounded-xl p-8 border border-[#F5EBE1]">
            @csrf

            <h3 class="font-bold text-lg text-[#4A3018] border-b pb-2 mb-4">Informasi Dasar</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold mb-2 text-[#4A3018]">Kategori Jasa</label>
                    <select name="kategori_jasa" x-model="kategori" required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Venue">Venue</option>
                        <option value="Catering">Catering</option>
                        <option value="Dekorasi">Dekorasi</option>
                        <option value="Dokumentasi">Dokumentasi</option>
                        <option value="Sound System">Sound System</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Attire">Attire</option>
                        <option value="Makeup Artist">Makeup Artist</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-[#4A3018]">Nama Vendor</label>
                    <input type="text" name="nama_vendor" required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-[#4A3018]">Email</label>
                    <input type="email" name="email" required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-[#4A3018]">Kontak / No HP</label>
                    <input type="text" name="no_telepon" required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-[#4A3018]">Harga Pasti (Rp)</label>
                    <input type="number" name="harga" placeholder="Contoh: 25000000" required min="1"
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-[#4A3018]">Link Portofolio (IG/Web) <span class="text-red-500">*</span></label>
                    <input type="url" name="link_portofolio" placeholder="https://..." required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold mb-2 text-[#4A3018]">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" placeholder="Tuliskan nama jalan dan sertakan wilayah kota di ujung alamat..."
                        required class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></textarea>
                </div>
            </div>

            <div x-show="kategori !== ''" x-transition>
                <h3 class="font-bold text-lg text-[#4A3018] border-b pb-2 mb-4" x-text="'Spesifikasi ' + kategori"></h3>

                <div x-show="kategori === 'Venue'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Kapasitas</label><input type="text"
                            name="detail[kapasitas]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Luas Lokasi</label><input type="text"
                            name="detail[luas_lokasi]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Tipe Venue (Indoor/Outdoor)</label><input
                            type="text" name="detail[tipe_venue]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Spesifikasi Bangunan</label><input
                            type="text" name="detail[spesifikasi]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div class="col-span-2"><label class="text-xs font-bold text-gray-600 uppercase">Fasilitas
                            Include</label><input type="text" name="detail[fasilitas_include]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                </div>

                <div x-show="kategori === 'Catering'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Penyajian (Prasmanan/Stall)</label><input
                            type="text" name="detail[jenis_penyajian]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Min-Max Pemesanan</label><input
                            type="text" name="detail[min_max_pesanan]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Sertifikasi</label>
                        <select name="detail[sertifikasi]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option>Halal</option>
                            <option>Non-Halal</option>
                            <option>Mix</option>
                        </select>
                    </div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Test Food</label>
                        <select name="detail[test_food]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option>Gratis</option>
                            <option>Berbayar</option>
                        </select>
                    </div>
                </div>

                <div x-show="kategori === 'Dekorasi'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Tema Dekorasi</label><input
                            type="text" name="detail[tema]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Jenis Bunga</label>
                        <select name="detail[jenis_bunga]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option>Mix</option>
                            <option>Fresh</option>
                            <option>Artificial</option>
                        </select>
                    </div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Ukuran Panggung</label><input
                            type="text" name="detail[ukuran_panggung]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Kebutuhan Loading</label><input
                            type="text" name="detail[kebutuhan_loading]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                </div>

                <div x-show="kategori === 'Dokumentasi'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Jumlah Kru</label><input type="number"
                            name="detail[jumlah_kru]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Durasi Max Liputan</label><input
                            type="text" name="detail[durasi_liputan]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Output Hasil (Album/Cetak)</label><input
                            type="text" name="detail[output]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Include Pre-Wedd?</label>
                        <select name="detail[pre_wedd]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option>Ya</option>
                            <option>Tidak</option>
                        </select>
                    </div>
                </div>

                <div x-show="kategori === 'Sound System'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Total Daya (Watt/KVA)</label><input
                            type="text" name="detail[total_daya]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Include Genset?</label>
                        <select name="detail[genset]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option>Ya (Free)</option>
                            <option>Ya (Charge)</option>
                            <option>Tidak</option>
                        </select>
                    </div>
                    <div class="col-span-2"><label class="text-xs font-bold text-gray-600 uppercase">Kelengkapan Alat
                            (Mic, Mix dll)</label><input type="text" name="detail[kelengkapan]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                </div>

                <div x-show="kategori === 'Entertainment'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Format
                            (Soloist/Akustik/Band)</label><input type="text" name="detail[format]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Jumlah Personil</label><input
                            type="number" name="detail[jumlah_personil]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Durasi Tampil Max</label><input
                            type="text" name="detail[durasi]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Sound Include?</label>
                        <select name="detail[sound_include]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option>Include</option>
                            <option>Exclude</option>
                        </select>
                    </div>
                </div>

                <div x-show="kategori === 'Attire'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Gaya Busana</label><input type="text"
                            name="detail[gaya_busana]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Rentang Ukuran</label><input
                            type="text" name="detail[rentang_ukuran]" placeholder="S, M, L, XL"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Max Kuota Fitting</label><input
                            type="text" name="detail[kuota_fitting]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Layanan Tambahan</label><input
                            type="text" name="detail[layanan_tambahan]" placeholder="Laundry, Vermak"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                </div>

                <div x-show="kategori === 'Makeup Artist'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Spesialisasi Look</label><input
                            type="text" name="detail[spesialisasi_look]" placeholder="Flawless, Bold, Adat"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Brand Kosmetik</label><input
                            type="text" name="detail[brand_kosmetik]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Layanan Include</label><input
                            type="text" name="detail[layanan_include]" placeholder="Hairdo, Hijabdo, Test Makeup"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Kapasitas Rias / Hari</label><input
                            type="text" name="detail[kapasitas_rias]" placeholder="Contoh: 3 Pax"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 text-white font-bold bg-[#8B5A2B] rounded-lg hover:bg-[#4A3018] shadow-sm transition-colors uppercase tracking-wide text-xs">
                    Simpan Vendor
                </button>
            </div>
        </form>
    </div>

    {{-- SweetAlert2: Pop-up Gagal jika ada error validasi dari server --}}
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const errorMessages = [
                    @foreach($errors->all() as $error)
                        '{{ addslashes($error) }}',
                    @endforeach
                ];
                Swal.fire({
                    title: 'Gagal Menyimpan!',
                    html: '<ul style="text-align:left;padding-left:1.2rem;margin:0">' + errorMessages.map(m => `<li>${m}</li>`).join('') + '</ul>',
                    icon: 'error',
                    confirmButtonColor: '#8B5A2B',
                    confirmButtonText: 'Perbaiki Data',
                });
            });
        </script>
    @endif

    {{-- SweetAlert2: Pop-up Berhasil dari session flash --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Berhasil!',
                    text: {!! json_encode(session('success')) !!},
                    icon: 'success',
                    confirmButtonColor: '#8B5A2B',
                    confirmButtonText: 'OK',
                    timer: 4000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif
@endsection
