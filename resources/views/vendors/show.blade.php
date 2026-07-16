@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-6xl mx-auto" x-data="{ activeTab: 'mou' }">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() == route('vendor.approval.index') ? route('vendor.approval.index') : route('vendors.index') }}" class="text-[#8B5A2B] hover:text-[#4A3018] transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-3xl font-bold text-[#4A3018]">Detail Profil Vendor</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Kelola data profil, berkas pendaftaran, dan persetujuan MoU</p>
                </div>
            </div>

            @hasrole('partnership')
                <div class="flex gap-2">
                    <a href="{{ route('vendors.edit', $vendor->id) }}"
                        class="px-5 py-2 text-sm font-bold text-blue-600 bg-white border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors uppercase tracking-wide">
                        Edit Data Vendor
                    </a>
                </div>
            @endhasrole
        </div>

        @if (session('success'))
            <div
                class="p-4 mb-6 text-sm font-bold text-green-700 bg-green-100 border border-green-200 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="p-4 mb-6 text-sm font-bold text-red-700 bg-red-100 border border-red-200 rounded-lg shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            {{-- Kolom Kiri: Profil Utama & Spesifikasi --}}
            <div class="space-y-6 col-span-1">
                <div class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm h-fit">
                    <div class="flex items-center justify-between mb-4 border-b pb-2">
                        <h3 class="text-xl font-bold text-[#4A3018]">Profil Utama</h3>
                        {{-- Status Approval Badge --}}
                        @if($vendor->status_approval === 'Pending')
                            <span class="px-2 py-0.5 text-[9px] font-black text-yellow-700 bg-yellow-100 border border-yellow-300 rounded-full uppercase tracking-wider">Pending</span>
                        @elseif($vendor->status_approval === 'Ditinjau')
                            <span class="px-2 py-0.5 text-[9px] font-black text-blue-700 bg-blue-100 border border-blue-300 rounded-full uppercase tracking-wider">Ditinjau</span>
                        @elseif($vendor->status_approval === 'Approved')
                            @if($vendor->status_aktif)
                                <span class="px-2 py-0.5 text-[9px] font-black text-green-700 bg-green-100 border border-green-300 rounded-full uppercase tracking-wider">🟢 Aktif</span>
                            @else
                                <span class="px-2 py-0.5 text-[9px] font-black text-amber-700 bg-amber-100 border border-amber-300 rounded-full uppercase tracking-wider">Approved (MOU Pending)</span>
                            @endif
                        @elseif($vendor->status_approval === 'Ditolak')
                            <span class="px-2 py-0.5 text-[9px] font-black text-red-700 bg-red-100 border border-red-300 rounded-full uppercase tracking-wider">Ditolak</span>
                        @endif
                    </div>

                    <div class="space-y-4 text-sm">
                        <p><span class="block font-semibold text-gray-400 text-xs uppercase tracking-wide">Nama Vendor</span>
                            <span class="text-lg font-bold text-[#8B5A2B]">{{ $vendor->nama_vendor }}</span>
                        </p>
                        <p><span class="block font-semibold text-gray-400 text-xs uppercase tracking-wide">Kategori Jasa</span>
                            <span
                                class="inline-block mt-1 px-2.5 py-1 bg-[#F5EBE1] text-[#8B5A2B] font-bold rounded text-xs border border-[#D4A373]">{{ strtoupper($vendor->kategori_jasa) }}</span>
                        </p>
                        <p><span class="block font-semibold text-gray-400 text-xs uppercase tracking-wide">Harga Paket</span>
                            <span class="text-base font-bold text-gray-800">Rp
                                {{ $vendor->harga ? number_format($vendor->harga, 0, ',', '.') : 'Belum Diatur' }}</span>
                        </p>
                        <p><span class="block font-semibold text-gray-400 text-xs uppercase tracking-wide">Email Kantor</span>
                            <span class="text-gray-700 font-medium">{{ $vendor->email }}</span>
                        </p>
                        <p><span class="block font-semibold text-gray-400 text-xs uppercase tracking-wide">No. Telepon / WA</span>
                            <span class="text-gray-700 font-medium">{{ $vendor->no_telepon }}</span>
                        </p>
                        <p><span class="block font-semibold text-gray-400 text-xs uppercase tracking-wide">Alamat Operasional</span>
                            <span
                                class="text-gray-700 font-medium leading-relaxed block bg-gray-50 p-2.5 border border-gray-100 rounded-lg text-xs">{{ $vendor->alamat ?? '-' }}</span>
                        </p>
                        <p><span class="block font-semibold text-gray-400 text-xs uppercase tracking-wide">Kualitas Penilaian</span>
                            <span class="font-bold text-yellow-600">⭐ Rating {{ number_format($vendor->rating, 1) }} / 5.0</span>
                        </p>
                        @hasrole('manager_comercial')
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <span class="block font-semibold text-gray-400 text-xs uppercase tracking-wide mb-2">Koreksi Rating Manual</span>
                                <form action="{{ route('vendors.overrideRating', $vendor->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" step="0.1" min="1.0" max="5.0" name="rating" value="{{ number_format($vendor->rating, 1) }}"
                                        class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-[#8B5A2B] focus:border-[#8B5A2B] outline-none font-semibold text-gray-800">
                                    <button type="submit" class="px-3 py-1 text-xs font-bold text-white bg-[#8B5A2B] rounded hover:bg-[#4A3018] transition-colors">
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        @endhasrole
                        @if ($vendor->link_portofolio)
                            <div class="pt-2">
                                <a href="{{ $vendor->link_portofolio }}" target="_blank"
                                    class="block w-full text-center px-4 py-2 bg-blue-50 text-blue-600 font-bold border border-blue-200 rounded hover:bg-blue-600 hover:text-white transition-colors uppercase tracking-wide text-xs shadow-sm">
                                    Buka Link Portofolio
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($vendor->detail_spesifikasi)
                    <div class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm h-fit">
                        <h3 class="mb-4 text-lg font-bold text-[#4A3018] border-b pb-2">Spesifikasi Teknis</h3>
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($vendor->detail_spesifikasi as $key => $value)
                                    @if (!empty($value))
                                        <tr>
                                            <td class="py-2.5 pr-2 font-semibold text-gray-500 capitalize w-2/5 text-xs">
                                                {{ str_replace('_', ' ', $key) }}
                                            </td>
                                            <td class="py-2.5 text-[#4A3018] font-bold text-xs">
                                                : {{ $value }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Kolom Kanan: Tab Menu (MoU & Approval, Dokumen) --}}
            <div class="col-span-2 space-y-6">
                {{-- Tab Headers --}}
                <div class="flex border-b border-gray-200 bg-white p-2 rounded-xl border">
                    <button @click="activeTab = 'mou'"
                            :class="activeTab === 'mou' ? 'bg-[#8B5A2B] text-white' : 'text-[#8B5A2B] hover:bg-orange-50'"
                            class="flex-1 py-2.5 px-4 text-xs font-bold rounded-lg transition-colors uppercase tracking-wider flex items-center justify-center gap-2">
                        🤝 Alur Approval & MoU
                    </button>
                    <button @click="activeTab = 'dokumen'"
                            :class="activeTab === 'dokumen' ? 'bg-[#8B5A2B] text-white' : 'text-[#8B5A2B] hover:bg-orange-50'"
                            class="flex-1 py-2.5 px-4 text-xs font-bold rounded-lg transition-colors uppercase tracking-wider flex items-center justify-center gap-2">
                        📄 Arsip Dokumen Legalitas
                    </button>
                </div>

                {{-- Tab 1: Alur Approval & MoU --}}
                <div x-show="activeTab === 'mou'" class="space-y-6">
                    <div class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm">
                        <h3 class="mb-4 text-lg font-bold text-[#4A3018] border-b pb-2">Status Alur Kemitraan</h3>
                        
                        {{-- Logika Tampilan Berdasarkan Status Approval Vendor --}}
                        @if($vendor->status_approval === 'Pending')
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl mb-4">
                                <p class="text-sm font-bold text-yellow-800">⏳ Berkas Sedang Menunggu Peninjauan</p>
                                <p class="text-xs text-yellow-700 mt-1">Divisi Strategic Partnership perlu meninjau berkas pendaftaran awal vendor sebelum dapat melangkah ke pembuatan MoU.</p>
                            </div>

                            @hasrole('partnership')
                                <form action="{{ route('vendor.approval.ditinjau', $vendor) }}" method="POST" class="mt-4">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-md text-xs uppercase tracking-wider">
                                        🔍 Tandai Ditinjau &amp; Mulai Pengecekan Berkas
                                    </button>
                                </form>
                            @endhasrole

                        @elseif($vendor->status_approval === 'Ditinjau')
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl mb-4">
                                <p class="text-sm font-bold text-blue-800">🔍 Pengecekan Berkas Berlangsung</p>
                                <p class="text-xs text-blue-700 mt-1">Berkas sedang dicek kelengkapannya oleh Strategic Partnership. Jika semua berkas sudah lengkap, tandai sebagai Approved.</p>
                            </div>

                            @hasrole('partnership')
                                <form action="{{ route('vendor.approval.approveBerkas', $vendor) }}" method="POST" class="mt-4">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors shadow-md text-xs uppercase tracking-wider"
                                            onclick="return confirm('Nyatakan berkas vendor {{ $vendor->nama_vendor }} lengkap dan setujui pendaftaran?')">
                                        ✅ Berkas Lengkap (Setujui / Approved)
                                    </button>
                                </form>
                            @endhasrole

                        @elseif($vendor->status_approval === 'Approved' && !$vendor->status_aktif)
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl mb-6">
                                <p class="text-sm font-bold text-amber-800">✍️ Pendaftaran Disetujui — Menunggu Pengajuan MoU</p>
                                <p class="text-xs text-amber-700 mt-1">Pendaftaran vendor telah disetujui (Approved). Untuk mengaktifkan status kemitraan vendor, Strategic Partnership harus mengunggah berkas MoU untuk disetujui oleh Manager Commercial.</p>
                            </div>

                            {{-- Form Upload MoU untuk Partnership --}}
                            @hasrole('partnership')
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mb-6">
                                    <h4 class="font-bold text-[#4A3018] text-sm mb-3">Form Pengajuan MoU Kemitraan</h4>
                                    <form action="{{ route('documents.store', $vendor->id) }}" method="POST" enctype="multipart/form-data"
                                        id="formUploadDocShow"
                                        @submit.prevent="
                                            Swal.fire({
                                                title: 'Kirim MoU?',
                                                text: 'MoU yang Anda unggah akan otomatis dikirim ke halaman disposisi Manager Commercial untuk ditinjau.',
                                                icon: 'info',
                                                showCancelButton: true,
                                                confirmButtonColor: '#8B5A2B',
                                                cancelButtonColor: '#6B7280',
                                                confirmButtonText: 'Ya, Kirim MoU',
                                                cancelButtonText: 'Batal'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $el.submit();
                                                }
                                            })
                                        ">
                                        @csrf
                                        <input type="hidden" name="jenis_dokumen" value="mou">
                                        
                                        <div class="mb-4">
                                            <label class="block mb-1.5 text-xs font-bold text-gray-500 uppercase">File Berkas MoU (Format PDF, maks. 5MB) <span class="text-red-500">*</span></label>
                                            <input type="file" name="file" accept=".pdf" required
                                                class="w-full px-3 py-2 border rounded-lg border-gray-300 bg-white text-sm">
                                        </div>

                                        <button type="submit"
                                            class="w-full py-2.5 bg-[#8B5A2B] text-white font-bold rounded-lg hover:bg-[#4A3018] shadow-sm transition-colors text-xs uppercase tracking-wide">
                                            📤 Upload &amp; Kirim Disposisi MoU
                                        </button>
                                    </form>
                                </div>
                            @endhasrole

                        @elseif($vendor->status_approval === 'Approved' && $vendor->status_aktif)
                            <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                                <p class="text-sm font-bold text-green-800">🟢 Kemitraan Vendor Aktif</p>
                                <p class="text-xs text-green-700 mt-1">Vendor telah berstatus aktif di sistem. MoU telah disetujui oleh Manager Commercial dan vendor siap ditugaskan pada event.</p>
                            </div>

                        @elseif($vendor->status_approval === 'Ditolak')
                            <div class="p-4 bg-red-50 border border-red-200 rounded-xl mb-4">
                                <p class="text-sm font-bold text-red-800">❌ Pengajuan Kemitraan Ditolak</p>
                                <p class="text-xs text-red-700 mt-1">MoU atau Berkas vendor ditolak oleh Manager Commercial.</p>
                                @if($vendor->catatan_tolak)
                                    <div class="mt-2.5 p-3 bg-white border border-red-100 rounded-lg text-xs text-red-700 font-medium">
                                        <strong>Catatan Penolakan:</strong><br>
                                        {{ $vendor->catatan_tolak }}
                                    </div>
                                @endif
                            </div>

                            @hasrole('partnership')
                                <form action="{{ route('vendor.approval.resetPending', $vendor) }}" method="POST" class="mt-4">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-bold rounded-xl transition-colors shadow-md text-xs uppercase tracking-wider"
                                            onclick="return confirm('Kembalikan status vendor ke Pending untuk melakukan perbaikan?')">
                                        🔄 Ajukan Peninjauan Ulang (Reset ke Pending)
                                    </button>
                                </form>
                            @endhasrole
                        @endif

                    </div>
                </div>

                {{-- Tab 2: Arsip Dokumen Legalitas --}}
                <div x-show="activeTab === 'dokumen'" class="space-y-6" style="display: none;">
                    <div class="p-6 bg-white border border-[#F5EBE1] rounded-2xl shadow-sm">
                        <h3 class="mb-4 text-lg font-bold text-[#4A3018] border-b pb-2">Arsip Dokumen Legalitas</h3>
                        
                        {{-- Dokumen Pendaftaran Awal (Proposal) --}}
                        @if($vendor->proposal_file)
                            <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 bg-red-50 text-red-600 rounded-lg">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-700">Proposal Pendaftaran Kemitraan</p>
                                        <p class="text-xs text-gray-400">Diunggah saat registrasi online</p>
                                    </div>
                                </div>
                                <a href="{{ route('vendor.proposal.download', $vendor) }}"
                                   class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-100 shadow-sm transition-colors uppercase tracking-wider">
                                    📥 Download Proposal
                                </a>
                            </div>
                        @endif

                        {{-- Arsip Unggahan Dokumen Tambahan --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-full">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-[#D4A373]">
                                        <th class="px-4 py-3 font-semibold text-gray-500 text-xs uppercase">Jenis Dokumen</th>
                                        <th class="px-4 py-3 font-semibold text-gray-500 text-xs uppercase">Status Approval</th>
                                        <th class="px-4 py-3 font-semibold text-gray-500 text-xs uppercase">Waktu Unggah</th>
                                        <th class="px-4 py-3 font-semibold text-center text-gray-500 text-xs uppercase">Berkas</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-xs">
                                    @forelse($vendor->documents as $doc)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3.5 font-bold uppercase text-[#4A3018]">
                                                {{ $doc->jenis_dokumen }}</td>
                                            <td class="px-4 py-3.5">
                                                @if ($doc->status_approval == 'pending')
                                                    <span
                                                        class="px-2.5 py-1 text-[10px] font-bold text-yellow-700 bg-yellow-50 rounded-full border border-yellow-200 uppercase tracking-wide">Menunggu</span>
                                                @elseif($doc->status_approval == 'approved')
                                                    <span
                                                        class="px-2.5 py-1 text-[10px] font-bold text-green-700 bg-green-50 rounded-full border border-green-200 uppercase tracking-wide">Disetujui</span>
                                                @else
                                                    <span
                                                        class="px-2.5 py-1 text-[10px] font-bold text-red-700 bg-red-50 rounded-full border border-red-200 uppercase tracking-wide">Ditolak</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3.5 text-gray-500">
                                                {{ $doc->created_at->translatedFormat('d M Y, H:i') }} WIB
                                            </td>
                                            <td class="px-4 py-3.5 text-center">
                                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                                    class="inline-block px-3 py-1 bg-gray-100 text-gray-700 font-bold border border-gray-300 rounded hover:bg-gray-200 transition-colors text-[10px] uppercase tracking-wide">
                                                    Lihat PDF
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-10 text-center text-gray-400 italic">
                                                Belum ada berkas dokumen legalitas (MOU/Kontrak) yang terdaftar untuk vendor
                                                ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
