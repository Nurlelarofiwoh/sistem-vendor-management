@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-4xl mx-auto" x-data="{ kategori: '{{ old('kategori_jasa', $vendor->kategori_jasa) }}' }">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-[#4A3018]">Edit Data Vendor</h2>
                <p class="text-[#8B5A2B] mt-1">Perbarui profil, alamat, rating, dan spesifikasi detail mitra vendor.</p>
            </div>
            <a href="{{ route('vendors.index') }}"
                class="px-4 py-2 text-sm font-bold text-[#8B5A2B] bg-white border border-[#8B5A2B] rounded-lg hover:bg-[#F5EBE1] transition-colors uppercase tracking-wide">
                Kembali
            </a>
        </div>

        <form action="{{ route('vendors.update', $vendor->id) }}" method="POST" id="formEditVendor"
            @submit.prevent="
                Swal.fire({
                    title: 'Simpan Perubahan?',
                    text: 'Apakah Anda yakin ingin memperbarui rincian data data vendor ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#8B5A2B',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Perbarui',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $el.submit();
                    }
                })
            "
            class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl p-8">
            @csrf
            @method('PUT')

            <h3 class="font-bold text-lg text-[#4A3018] border-b pb-2 mb-4">Informasi Dasar</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold text-[#4A3018] mb-2">Kategori Jasa</label>
                    <select name="kategori_jasa" x-model="kategori" required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
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
                    <label class="block text-sm font-bold text-[#4A3018] mb-2">Nama Vendor</label>
                    <input type="text" name="nama_vendor" value="{{ old('nama_vendor', $vendor->nama_vendor) }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#4A3018] mb-2">Email Vendor</label>
                    <input type="email" name="email" value="{{ old('email', $vendor->email) }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#4A3018] mb-2">No. HP / WhatsApp</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $vendor->no_telepon) }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#4A3018] mb-2">Harga Pasti (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga', $vendor->harga) }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#4A3018] mb-2">Link Portofolio (IG/Web)</label>
                    <input type="url" name="link_portofolio"
                        value="{{ old('link_portofolio', $vendor->link_portofolio) }}"
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-[#4A3018] mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">{{ old('alamat', $vendor->alamat) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#4A3018] mb-2">Rating (0.0 - 5.0)</label>
                    <input type="number" step="0.1" min="0" max="5" name="rating"
                        value="{{ old('rating', $vendor->rating) }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#4A3018] mb-2">Status Vendor</label>
                    <select name="status_aktif"
                        class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                        <option value="1" {{ $vendor->status_aktif ? 'selected' : '' }}>Aktif (Tersedia)</option>
                        <option value="0" {{ !$vendor->status_aktif ? 'selected' : '' }}>Non-Aktif (Di-suspend)
                        </option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#4A3018] mb-2">Batas Masa Kontrak</label>
                <input type="date" name="tanggal_kontrak_habis"
                    value="{{ old('tanggal_kontrak_habis', $vendor->tanggal_kontrak_habis ? \Carbon\Carbon::parse($vendor->tanggal_kontrak_habis)->format('Y-m-d') : '') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                <p class="text-[10px] text-gray-500 mt-1 italic">*Kosongkan jika kontrak berlaku selamanya</p>
            </div>

            <div x-show="kategori !== ''" x-transition>
                <h3 class="font-bold text-lg text-[#4A3018] border-b pb-2 mb-4" x-text="'Spesifikasi ' + kategori"></h3>

                <div x-show="kategori === 'Venue'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Kapasitas</label><input type="text"
                            name="detail[kapasitas]" value="{{ $vendor->detail_spesifikasi['kapasitas'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Luas Lokasi</label><input type="text"
                            name="detail[luas_lokasi]" value="{{ $vendor->detail_spesifikasi['luas_lokasi'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Tipe Venue (Indoor/Outdoor)</label><input
                            type="text" name="detail[tipe_venue]"
                            value="{{ $vendor->detail_spesifikasi['tipe_venue'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Spesifikasi Bangunan</label><input
                            type="text" name="detail[spesifikasi]"
                            value="{{ $vendor->detail_spesifikasi['spesifikasi'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div class="col-span-2"><label class="text-xs font-bold text-gray-600 uppercase">Fasilitas
                            Include</label><input type="text" name="detail[fasilitas_include]"
                            value="{{ $vendor->detail_spesifikasi['fasilitas_include'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                </div>

                <div x-show="kategori === 'Catering'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Penyajian (Prasmanan/Stall)</label><input
                            type="text" name="detail[jenis_penyajian]"
                            value="{{ $vendor->detail_spesifikasi['jenis_penyajian'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Min-Max Pemesanan</label><input
                            type="text" name="detail[min_max_pesanan]"
                            value="{{ $vendor->detail_spesifikasi['min_max_pesanan'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Sertifikasi</label>
                        <select name="detail[sertifikasi]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option {{ ($vendor->detail_spesifikasi['sertifikasi'] ?? '') == 'Halal' ? 'selected' : '' }}>
                                Halal</option>
                            <option
                                {{ ($vendor->detail_spesifikasi['sertifikasi'] ?? '') == 'Non-Halal' ? 'selected' : '' }}>
                                Non-Halal</option>
                            <option {{ ($vendor->detail_spesifikasi['sertifikasi'] ?? '') == 'Mix' ? 'selected' : '' }}>Mix
                            </option>
                        </select>
                    </div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Test Food</label>
                        <select name="detail[test_food]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option {{ ($vendor->detail_spesifikasi['test_food'] ?? '') == 'Gratis' ? 'selected' : '' }}>
                                Gratis</option>
                            <option {{ ($vendor->detail_spesifikasi['test_food'] ?? '') == 'Berbayar' ? 'selected' : '' }}>
                                Berbayar</option>
                        </select>
                    </div>
                </div>

                <div x-show="kategori === 'Dekorasi'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Tema Dekorasi</label><input
                            type="text" name="detail[tema]" value="{{ $vendor->detail_spesifikasi['tema'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Jenis Bunga</label>
                        <select name="detail[jenis_bunga]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option {{ ($vendor->detail_spesifikasi['jenis_bunga'] ?? '') == 'Mix' ? 'selected' : '' }}>Mix
                            </option>
                            <option {{ ($vendor->detail_spesifikasi['jenis_bunga'] ?? '') == 'Fresh' ? 'selected' : '' }}>
                                Fresh</option>
                            <option
                                {{ ($vendor->detail_spesifikasi['jenis_bunga'] ?? '') == 'Artificial' ? 'selected' : '' }}>
                                Artificial</option>
                        </select>
                    </div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Ukuran Panggung</label><input
                            type="text" name="detail[ukuran_panggung]"
                            value="{{ $vendor->detail_spesifikasi['ukuran_panggung'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Kebutuhan Loading</label><input
                            type="text" name="detail[kebutuhan_loading]"
                            value="{{ $vendor->detail_spesifikasi['kebutuhan_loading'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                </div>

                <div x-show="kategori === 'Dokumentasi'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Jumlah Kru</label><input type="number"
                            name="detail[jumlah_kru]" value="{{ $vendor->detail_spesifikasi['jumlah_kru'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Durasi Max Liputan</label><input
                            type="text" name="detail[durasi_liputan]"
                            value="{{ $vendor->detail_spesifikasi['durasi_liputan'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Output Hasil</label><input
                            type="text" name="detail[output]"
                            value="{{ $vendor->detail_spesifikasi['output'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Include Pre-Wedd?</label>
                        <select name="detail[pre_wedd]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option {{ ($vendor->detail_spesifikasi['pre_wedd'] ?? '') == 'Ya' ? 'selected' : '' }}>Ya
                            </option>
                            <option {{ ($vendor->detail_spesifikasi['pre_wedd'] ?? '') == 'Tidak' ? 'selected' : '' }}>
                                Tidak</option>
                        </select>
                    </div>
                </div>

                <div x-show="kategori === 'Sound System'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Total Daya</label><input type="text"
                            name="detail[total_daya]" value="{{ $vendor->detail_spesifikasi['total_daya'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Include Genset?</label>
                        <select name="detail[genset]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option {{ ($vendor->detail_spesifikasi['genset'] ?? '') == 'Ya (Free)' ? 'selected' : '' }}>Ya
                                (Free)</option>
                            <option {{ ($vendor->detail_spesifikasi['genset'] ?? '') == 'Ya (Charge)' ? 'selected' : '' }}>
                                Ya (Charge)</option>
                            <option {{ ($vendor->detail_spesifikasi['genset'] ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak
                            </option>
                        </select>
                    </div>
                    <div class="col-span-2"><label class="text-xs font-bold text-gray-600 uppercase">Kelengkapan
                            Alat</label><input type="text" name="detail[kelengkapan]"
                            value="{{ $vendor->detail_spesifikasi['kelengkapan'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                </div>

                <div x-show="kategori === 'Entertainment'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Format</label><input type="text"
                            name="detail[format]" value="{{ $vendor->detail_spesifikasi['format'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Jumlah Personil</label><input
                            type="number" name="detail[jumlah_personil]"
                            value="{{ $vendor->detail_spesifikasi['jumlah_personil'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Durasi Tampil Max</label><input
                            type="text" name="detail[durasi]"
                            value="{{ $vendor->detail_spesifikasi['durasi'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Sound Include?</label>
                        <select name="detail[sound_include]"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]">
                            <option
                                {{ ($vendor->detail_spesifikasi['sound_include'] ?? '') == 'Include' ? 'selected' : '' }}>
                                Include</option>
                            <option
                                {{ ($vendor->detail_spesifikasi['sound_include'] ?? '') == 'Exclude' ? 'selected' : '' }}>
                                Exclude</option>
                        </select>
                    </div>
                </div>

                <div x-show="kategori === 'Attire'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Gaya Busana</label><input type="text"
                            name="detail[gaya_busana]" value="{{ $vendor->detail_spesifikasi['gaya_busana'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Rentang Ukuran</label><input
                            type="text" name="detail[rentang_ukuran]"
                            value="{{ $vendor->detail_spesifikasi['rentang_ukuran'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Max Kuota Fitting</label><input
                            type="text" name="detail[kuota_fitting]"
                            value="{{ $vendor->detail_spesifikasi['kuota_fitting'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Layanan Tambahan</label><input
                            type="text" name="detail[layanan_tambahan]"
                            value="{{ $vendor->detail_spesifikasi['layanan_tambahan'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                </div>

                <div x-show="kategori === 'Makeup Artist'" class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Spesialisasi Look</label><input
                            type="text" name="detail[spesialisasi_look]"
                            value="{{ $vendor->detail_spesifikasi['spesialisasi_look'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Brand Kosmetik</label><input
                            type="text" name="detail[brand_kosmetik]"
                            value="{{ $vendor->detail_spesifikasi['brand_kosmetik'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Layanan Include</label><input
                            type="text" name="detail[layanan_include]"
                            value="{{ $vendor->detail_spesifikasi['layanan_include'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                    <div><label class="text-xs font-bold text-gray-600 uppercase">Kapasitas Rias / Hari</label><input
                            type="text" name="detail[kapasitas_rias]"
                            value="{{ $vendor->detail_spesifikasi['kapasitas_rias'] ?? '' }}"
                            class="w-full border-gray-300 rounded focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"></div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 text-white font-bold bg-[#8B5A2B] rounded-lg hover:bg-[#4A3018] shadow-sm transition-colors uppercase tracking-wide text-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
