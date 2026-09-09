@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-[#4A3018]">Data Vendor Terdaftar</h2>
            <p class="text-[#8B5A2B] mt-1">Kelola informasi dan status kontrak seluruh vendor mitra VMS.</p>
        </div>
    </div>

    <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl overflow-hidden">

        {{-- Form Filter --}}
        <form method="GET" action="{{ route('vendors.index') }}" class="p-6 bg-gray-50 border-b border-[#F5EBE1] flex flex-wrap items-end gap-4">
            <div class="w-full sm:w-auto flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kategori Jasa</label>
                <select name="kategori" class="w-full px-3 py-2 bg-white border border-[#D4A373] rounded-lg text-sm focus:ring-[#8B5A2B] focus:border-[#8B5A2B] text-[#4A3018] font-medium">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-auto flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Wilayah / Daerah</label>
                <select name="daerah" class="w-full px-3 py-2 bg-white border border-[#D4A373] rounded-lg text-sm focus:ring-[#8B5A2B] focus:border-[#8B5A2B] text-[#4A3018] font-medium">
                    <option value="">Semua Wilayah</option>
                    @foreach($daerahList as $daerah)
                        <option value="{{ $daerah }}" {{ request('daerah') == $daerah ? 'selected' : '' }}>{{ $daerah }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-auto flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Jenis Vendor</label>
                <select name="vendor_baru" class="w-full px-3 py-2 bg-white border border-[#D4A373] rounded-lg text-sm focus:ring-[#8B5A2B] focus:border-[#8B5A2B] text-[#4A3018] font-medium">
                    <option value="">Semua Vendor</option>
                    <option value="ya" {{ request('vendor_baru') == 'ya' ? 'selected' : '' }}>Vendor Baru</option>
                    <option value="tidak" {{ request('vendor_baru') == 'tidak' ? 'selected' : '' }}>Vendor Lama / Berpengalaman</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-[#8B5A2B] text-white text-sm font-bold rounded-lg hover:bg-[#4A3018] transition-colors shadow-sm">
                    Filter
                </button>
                @if(request()->anyFilled(['kategori', 'daerah', 'vendor_baru']))
                    <a href="{{ route('vendors.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-100 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-max">
                <thead>
                    <tr class="bg-[#F5EBE1] text-[#4A3018] border-b border-[#D4A373]">
                        <th class="px-6 py-4 font-semibold text-sm tracking-wide">Nama Vendor</th>
                        <th class="px-6 py-4 font-semibold text-sm tracking-wide">Kategori &amp; Harga Pasti</th>
                        <th class="px-6 py-4 font-semibold text-sm tracking-wide w-1/3">Kontak &amp; Alamat</th>
                        <th class="px-6 py-4 font-semibold text-sm tracking-wide text-center">Status Kontrak</th>
                        <th class="px-6 py-4 font-semibold text-sm tracking-wide text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F5EBE1]">
                    @forelse($vendors as $vendor)
                        <tr class="hover:bg-[#FAFAFA] transition-colors group">

                            <td class="px-6 py-4">
                                <p class="font-bold text-lg text-[#4A3018]">{{ $vendor->nama_vendor }}</p>
                                @php
                                    $isNew     = is_null($vendor->rating);
                                    $r         = $isNew ? 0 : (float) $vendor->rating;
                                    $ratingColor = $isNew
                                        ? 'text-gray-400'
                                        : ($r >= 4.5 ? 'text-yellow-600' : ($r >= 3.5 ? 'text-orange-500' : ($r >= 2.0 ? 'text-red-400' : 'text-red-600')));
                                    $stars = $isNew
                                        ? '☆☆☆☆☆'
                                        : (str_repeat('★', round($r)) . str_repeat('☆', 5 - round($r)));
                                @endphp
                                <p class="text-xs font-bold {{ $ratingColor }} mt-1 flex items-center gap-1.5 flex-wrap">
                                    @if ($isNew)
                                        <span class="text-gray-400 italic font-normal">Belum Dinilai</span>
                                    @else
                                        {{ $stars }} {{ number_format($r, 1) }} / 5.0
                                        @if($vendor->total_review > 0)
                                            <span class="text-gray-400 font-normal">({{ $vendor->total_review }} ulasan)</span>
                                        @endif
                                    @endif
                                    @if($vendor->is_new_vendor)
                                        <span class="px-1.5 py-0.5 text-[10px] font-bold text-blue-700 bg-blue-100 border border-blue-200 rounded">Baru</span>
                                    @else
                                        <span class="px-1.5 py-0.5 text-[10px] font-bold text-emerald-700 bg-emerald-100 border border-emerald-200 rounded">Berpengalaman</span>
                                    @endif
                                </p>
                                @if ($vendor->link_portofolio)
                                    <a href="{{ $vendor->link_portofolio }}" target="_blank"
                                        class="inline-block mt-2 text-[11px] font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                        🔗 Lihat Portofolio
                                    </a>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <span
                                    class="inline-block px-3 py-1 text-xs font-bold text-[#8B5A2B] bg-[#F5EBE1] rounded-md border border-[#D4A373]">
                                    {{ strtoupper($vendor->kategori_jasa) }}
                                </span>
                                <p class="text-sm font-semibold text-gray-700 mt-2">
                                    Rp
                                    {{ $vendor->harga ? number_format($vendor->harga, 0, ',', '.') : 'Harga Belum Diatur' }}
                                </p>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600 space-y-1">
                                <p><span class="font-semibold text-gray-500 w-16 inline-block">Telp/WA</span>:
                                    {{ $vendor->no_telepon }}</p>
                                <p><span class="font-semibold text-gray-500 w-16 inline-block">Email</span>:
                                    {{ $vendor->email }}</p>
                                <p
                                    class="text-xs text-gray-500 mt-2 leading-relaxed bg-gray-50 p-2 rounded border border-gray-100">
                                    {{ \Illuminate\Support\Str::limit($vendor->alamat, 60) ?? 'Alamat belum diisi' }}
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if ($vendor->status_aktif)
                                    <span
                                        class="px-3 py-1 text-[11px] font-bold text-green-700 bg-green-100 border border-green-200 rounded-full mb-2 inline-block">Aktif
                                        Beroperasi</span>
                                @else
                                    <span
                                        class="px-3 py-1 text-[11px] font-bold text-red-700 bg-red-100 border border-red-200 rounded-full mb-2 inline-block">Nonaktif</span>
                                @endif

                                <div class="mt-1 mb-2">
                                    @php
                                        $sk = $vendor->status_kemitraan ?? 'Vendor Baru';
                                        $skConfig = match($sk) {
                                            'Preferred' => ['class' => 'text-indigo-700 bg-indigo-50 border-indigo-200'],
                                            'Under Review' => ['class' => 'text-red-700 bg-red-50 border-red-200'],
                                            default => ['class' => 'text-gray-700 bg-gray-50 border-gray-200'],
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 text-[10px] font-bold border rounded {{ $skConfig['class'] }}">
                                        {{ $sk }}
                                    </span>
                                </div>

                                <div class="mt-2">
                                    @php
                                        $sf = $vendor->skor_finansial ?? 3;
                                        $sfConfig = match($sf) {
                                            1 => ['label' => 'Sengketa', 'class' => 'text-red-700 bg-red-50 border-red-200'],
                                            2 => ['label' => 'Warning',  'class' => 'text-amber-700 bg-amber-50 border-amber-200'],
                                            default => ['label' => 'Lancar', 'class' => 'text-green-700 bg-green-50 border-green-200'],
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 text-[10px] font-bold border rounded {{ $sfConfig['class'] }}">
                                        Komisi: {{ $sfConfig['label'] }}
                                    </span>
                                </div>

                                <div class="mt-1">
                                    @if ($vendor->tanggal_kontrak_habis)
                                        @php
                                            $sisaHari = \Carbon\Carbon::now()->diffInDays(
                                                \Carbon\Carbon::parse($vendor->tanggal_kontrak_habis),
                                                false,
                                            );
                                        @endphp

                                        @if ($sisaHari < 0)
                                            <p
                                                class="text-[10px] font-black text-red-600 bg-red-50 p-1 rounded border border-red-100 uppercase tracking-wide">
                                                KONTRAK HABIS</p>
                                        @elseif($sisaHari <= 30)
                                            <p
                                                class="text-[10px] font-black text-orange-600 bg-orange-50 p-1 rounded border border-orange-100 uppercase tracking-wide">
                                                SISA KONTRAK: {{ round($sisaHari) }} HARI</p>
                                        @else
                                            <p class="text-[10px] font-bold text-gray-500">Batas
                                                Kontrak:<br>{{ \Carbon\Carbon::parse($vendor->tanggal_kontrak_habis)->format('d/m/Y') }}
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-[10px] text-gray-400 italic">Kontrak tak terbatas</p>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center space-y-2">
                                <a href="{{ route('vendors.show', $vendor->id) }}"
                                    class="block w-full px-3 py-2 text-[11px] font-bold text-white bg-[#8B5A2B] rounded shadow-sm hover:bg-[#4A3018] transition-colors uppercase tracking-wide">
                                    Detail &amp; Dokumen
                                </a>

                                @hasrole('partnership')
                                    <a href="{{ route('vendors.edit', $vendor->id) }}"
                                        class="block w-full px-3 py-1.5 text-[11px] font-bold text-[#8B5A2B] bg-white border border-[#8B5A2B] rounded hover:bg-[#F5EBE1] transition-colors uppercase tracking-wide">
                                        Edit Data
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form id="form-hapus-{{ $vendor->id }}"
                                        action="{{ route('vendors.destroy', $vendor->id) }}"
                                        method="POST" class="block w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="konfirmasiHapus({{ $vendor->id }}, '{{ addslashes($vendor->nama_vendor) }}')"
                                            class="w-full px-3 py-1.5 text-[11px] font-bold text-red-600 bg-white border border-red-400 rounded hover:bg-red-50 transition-colors uppercase tracking-wide">
                                            Hapus
                                        </button>
                                    </form>
                                @endhasrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Belum ada data vendor yang didaftarkan ke sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($vendors instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $vendors->hasPages())
            <div class="p-6 bg-gray-50 border-t border-[#F5EBE1] pagination-wrapper">
                {{ $vendors->links() }}
            </div>
        @endif
    </div>

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

    {{-- SweetAlert2: Pop-up Gagal dari session flash error --}}
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonColor: '#8B5A2B',
                    confirmButtonText: 'Tutup',
                });
            });
        </script>
    @endif

    <script>
        /**
         * Menampilkan konfirmasi SweetAlert2 sebelum menghapus vendor.
         * Jika dikonfirmasi, form DELETE akan di-submit.
         *
         * @param {number} vendorId - ID vendor yang akan dihapus
         * @param {string} namaVendor - Nama vendor untuk ditampilkan di dialog
         */
        function konfirmasiHapus(vendorId, namaVendor) {
            Swal.fire({
                title: 'Hapus Vendor?',
                html: `Anda akan menghapus vendor <strong>${namaVendor}</strong> secara permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-hapus-' + vendorId).submit();
                }
            });
        }
    </script>
@endsection
