@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold text-[#4A3018]">Pengajuan Vendor</h2>
        <p class="text-[#8B5A2B] mt-1">Alur Disposisi & Approval Berjenjang Vendor Baru</p>
    </div>
    @hasrole('partnership')
        <a href="{{ route('vendors.create') }}"
           class="flex items-center gap-2 px-5 py-2.5 bg-[#8B5A2B] hover:bg-[#4A3018] text-white text-sm font-bold rounded-xl shadow-md transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Vendor Baru
        </a>
    @endhasrole
</div>

{{-- Status Badge Summary (4 kartu) --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    {{-- Pending --}}
    <a href="{{ route('vendor.approval.index', ['status' => 'Pending']) }}"
       class="p-5 bg-white border rounded-2xl shadow-sm hover:shadow-md transition-shadow block {{ $statusFilter === 'Pending' ? 'border-yellow-500 ring-2 ring-yellow-400' : 'border-[#F5EBE1]' }}">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-yellow-50 text-yellow-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-[#4A3018]">{{ $counts['Pending'] }}</div>
                <div class="text-xs font-semibold text-gray-500">Menunggu Tinjauan</div>
            </div>
        </div>
    </a>

    {{-- Ditinjau --}}
    <a href="{{ route('vendor.approval.index', ['status' => 'Ditinjau']) }}"
       class="p-5 bg-white border rounded-2xl shadow-sm hover:shadow-md transition-shadow block {{ $statusFilter === 'Ditinjau' ? 'border-blue-500 ring-2 ring-blue-400' : 'border-[#F5EBE1]' }}">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-[#4A3018]">{{ $counts['Ditinjau'] }}</div>
                <div class="text-xs font-semibold text-gray-500">Sedang Ditinjau SP</div>
            </div>
        </div>
    </a>

    {{-- Approved --}}
    <a href="{{ route('vendor.approval.index', ['status' => 'Approved']) }}"
       class="p-5 bg-white border rounded-2xl shadow-sm hover:shadow-md transition-shadow block {{ $statusFilter === 'Approved' ? 'border-green-500 ring-2 ring-green-400' : 'border-[#F5EBE1]' }}">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-green-50 text-green-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-[#4A3018]">{{ $counts['Approved'] }}</div>
                <div class="text-xs font-semibold text-gray-500">Approved MoU</div>
            </div>
        </div>
    </a>

    {{-- Ditolak --}}
    <a href="{{ route('vendor.approval.index', ['status' => 'Ditolak']) }}"
       class="p-5 bg-white border rounded-2xl shadow-sm hover:shadow-md transition-shadow block {{ $statusFilter === 'Ditolak' ? 'border-red-500 ring-2 ring-red-400' : 'border-[#F5EBE1]' }}">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-red-50 text-red-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-[#4A3018]">{{ $counts['Ditolak'] }}</div>
                <div class="text-xs font-semibold text-gray-500">Ditolak Manager</div>
            </div>
        </div>
    </a>
</div>

{{-- Filter Tabs & Table Container --}}
<div class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl overflow-hidden">
    {{-- Tab Filter --}}
    <div class="p-4 bg-gray-50 border-b border-[#F5EBE1] flex flex-wrap gap-2">
        <a href="{{ route('vendor.approval.index') }}"
           class="px-4 py-2 text-xs font-bold rounded-lg border transition-colors {{ $statusFilter === 'semua' ? 'bg-[#8B5A2B] text-white border-[#8B5A2B]' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Semua
        </a>
        <a href="{{ route('vendor.approval.index', ['status' => 'Pending']) }}"
           class="px-4 py-2 text-xs font-bold rounded-lg border transition-colors {{ $statusFilter === 'Pending' ? 'bg-yellow-600 text-white border-yellow-600' : 'bg-white text-yellow-600 border-yellow-300 hover:bg-yellow-50' }}">
            Pending
            @if($counts['Pending'] > 0)
                <span class="ml-1 px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-[10px]">{{ $counts['Pending'] }}</span>
            @endif
        </a>
        <a href="{{ route('vendor.approval.index', ['status' => 'Ditinjau']) }}"
           class="px-4 py-2 text-xs font-bold rounded-lg border transition-colors {{ $statusFilter === 'Ditinjau' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-blue-600 border-blue-300 hover:bg-blue-50' }}">
            Ditinjau
            @if($counts['Ditinjau'] > 0)
                <span class="ml-1 px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full text-[10px]">{{ $counts['Ditinjau'] }}</span>
            @endif
        </a>
        <a href="{{ route('vendor.approval.index', ['status' => 'Approved']) }}"
           class="px-4 py-2 text-xs font-bold rounded-lg border transition-colors {{ $statusFilter === 'Approved' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-green-600 border-green-300 hover:bg-green-50' }}">
            Approved
        </a>
        <a href="{{ route('vendor.approval.index', ['status' => 'Ditolak']) }}"
           class="px-4 py-2 text-xs font-bold rounded-lg border transition-colors {{ $statusFilter === 'Ditolak' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-red-600 border-red-300 hover:bg-red-50' }}">
            Ditolak
            @if($counts['Ditolak'] > 0)
                <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-700 rounded-full text-[10px]">{{ $counts['Ditolak'] }}</span>
            @endif
        </a>
    </div>

    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse min-w-max">
            <thead>
                <tr class="bg-[#F5EBE1] text-[#4A3018] border-b border-[#D4A373]">
                    <th class="px-6 py-4 font-semibold text-sm tracking-wide">Nama Vendor</th>
                    <th class="px-6 py-4 font-semibold text-sm tracking-wide">Kategori</th>
                    <th class="px-6 py-4 font-semibold text-sm tracking-wide">Kontak &amp; Alamat</th>
                    <th class="px-6 py-4 font-semibold text-sm tracking-wide text-center">Estimasi Harga</th>
                    <th class="px-6 py-4 font-semibold text-sm tracking-wide text-center">Status</th>
                    <th class="px-6 py-4 font-semibold text-sm tracking-wide text-center">Terdaftar</th>
                    <th class="px-6 py-4 font-semibold text-sm tracking-wide text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F5EBE1]">
                @forelse($vendors as $vendor)
                <tr class="hover:bg-[#FAFAFA] transition-colors" x-data="{ showTolakModal: false, showCatatan: false }">

                    {{-- Nama Vendor, Email, Portofolio --}}
                    <td class="px-6 py-4">
                        <p class="font-bold text-base text-[#4A3018]">{{ $vendor->nama_vendor }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $vendor->email }}</p>
                        @if($vendor->link_portofolio)
                            <a href="{{ $vendor->link_portofolio }}" target="_blank"
                               class="inline-flex items-center gap-1 mt-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Lihat Portofolio
                            </a>
                        @endif
                    </td>

                    {{-- Kategori --}}
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 text-xs font-bold text-[#8B5A2B] bg-[#F5EBE1] rounded-md border border-[#D4A373]">
                            {{ strtoupper($vendor->kategori_jasa) }}
                        </span>
                    </td>

                    {{-- Kontak & Alamat --}}
                    <td class="px-6 py-4 text-sm text-gray-600 space-y-1">
                        <p><span class="font-semibold text-gray-500 w-16 inline-block">Telp/WA</span>: {{ $vendor->no_telepon }}</p>
                        <p class="text-xs text-gray-500 leading-relaxed bg-gray-50 p-2 rounded border border-gray-100 max-w-[200px]">
                            {{ \Illuminate\Support\Str::limit($vendor->alamat, 70) }}
                        </p>
                    </td>

                    {{-- Estimasi Harga --}}
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold text-[#4A3018] text-sm">
                            Rp {{ number_format($vendor->harga ?? 0, 0, ',', '.') }}
                        </span>
                    </td>

                    {{-- Badge Status --}}
                    <td class="px-6 py-4 text-center">
                        @if($vendor->status_approval === 'Pending')
                            <span class="px-3 py-1 text-[11px] font-bold text-yellow-700 bg-yellow-100 border border-yellow-200 rounded-full">
                                ⏳ Pending
                            </span>
                        @elseif($vendor->status_approval === 'Ditinjau')
                            <span class="px-3 py-1 text-[11px] font-bold text-blue-700 bg-blue-100 border border-blue-200 rounded-full">
                                🔍 Ditinjau
                            </span>
                            <p class="text-[10px] text-gray-400 mt-1">Menunggu keputusan<br>Manager Commercial</p>
                        @elseif($vendor->status_approval === 'Approved')
                            <span class="px-3 py-1 text-[11px] font-bold text-green-700 bg-green-100 border border-green-200 rounded-full">
                                ✅ Approved
                            </span>
                            <p class="text-[10px] text-green-600 font-semibold mt-1">Aktif di Data Vendor</p>
                            @if($vendor->rating > 0)
                                <div class="text-xs font-bold text-yellow-600 mt-1">⭐ {{ number_format($vendor->rating, 1) }} / 5.0</div>
                            @endif
                        @elseif($vendor->status_approval === 'Ditolak')
                            <span class="px-3 py-1 text-[11px] font-bold text-red-700 bg-red-100 border border-red-200 rounded-full">
                                ❌ Ditolak
                            </span>
                            @if($vendor->tanggal_ditolak)
                                <p class="text-[10px] text-gray-400 mt-1">{{ $vendor->tanggal_ditolak->format('d M Y') }}</p>
                            @endif
                            {{-- Tombol Lihat Catatan => Modal Popup --}}
                            @if($vendor->catatan_tolak)
                                <button
                                    @click="$dispatch('open-catatan-modal', { catatan: {{ json_encode($vendor->catatan_tolak) }}, nama: {{ json_encode($vendor->nama_vendor) }} })"
                                    class="mt-1 text-[10px] text-red-500 hover:text-red-700 hover:underline font-semibold bg-red-50 border border-red-200 rounded px-2 py-0.5 transition-colors">
                                    📋 Lihat Catatan
                                </button>
                            @endif
                        @endif
                    </td>

                    {{-- Tanggal Terdaftar --}}
                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                        {{ $vendor->created_at->format('d M Y') }}
                    </td>

                    {{-- Kolom Aksi --}}
                    <td class="px-6 py-4">
                        <div class="flex flex-col items-center gap-2 min-w-[140px]">

                            {{-- Download Proposal PDF (selalu tampil jika ada file) --}}
                            @if($vendor->proposal_file)
                                <a href="{{ route('vendor.proposal.download', $vendor) }}"
                                   class="w-full flex items-center justify-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 text-[11px] font-bold rounded-lg border border-gray-300 hover:bg-gray-200 transition-colors"
                                   title="Unduh Berkas Proposal PDF">
                                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Unduh Proposal
                                </a>
                            @else
                                <span class="w-full text-center text-[11px] text-gray-400 italic py-1">Tidak ada proposal</span>
                            @endif

                            {{-- [DIVISI SP / PARTNERSHIP] Aksi Kemitraan --}}
                            @hasrole('partnership')
                                <a href="{{ route('vendors.show', $vendor) }}"
                                   class="w-full text-center px-3 py-1.5 bg-[#8B5A2B] hover:bg-[#4A3018] text-white text-[11px] font-bold rounded-lg transition-colors shadow-sm">
                                    👁 Detail Profil
                                </a>

                                @if($vendor->status_approval === 'Pending')
                                    <form action="{{ route('vendor.approval.ditinjau', $vendor) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="w-full px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold rounded-lg transition-colors shadow-sm"
                                                onclick="return confirm('Tandai vendor {{ addslashes($vendor->nama_vendor) }} sebagai Ditinjau?')">
                                            🔍 Tandai Ditinjau
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('vendors.edit', $vendor) }}"
                                   class="w-full text-center px-3 py-1.5 bg-blue-50 border border-blue-300 text-blue-700 text-[11px] font-bold rounded-lg hover:bg-blue-100 transition-colors">
                                    ✏️ Edit
                                </a>

                                <form action="{{ route('vendors.destroy', $vendor) }}" method="POST" class="w-full"
                                      onsubmit="return confirm('Hapus vendor {{ addslashes($vendor->nama_vendor) }}? Tindakan ini tidak bisa dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-3 py-1.5 bg-red-50 border border-red-300 text-red-700 text-[11px] font-bold rounded-lg hover:bg-red-100 transition-colors">
                                        🗑 Hapus
                                    </button>
                                </form>
                            @endhasrole

                            {{-- [MANAGER COMMERCIAL] Detail Profil (semua status) --}}
                            @if(auth()->user()->hasRole('manager_comercial'))
                                <a href="{{ route('vendors.show', $vendor) }}"
                                   class="w-full text-center px-3 py-1.5 bg-[#F5EBE1] border border-[#D4A373] text-[#8B5A2B] text-[11px] font-bold rounded-lg hover:bg-[#D4A373] transition-colors">
                                    👁 Detail Profil
                                </a>
                            @endif

                            {{-- [MANAGER COMMERCIAL] Approved → info saja --}}
                            @if(auth()->user()->hasRole('manager_comercial') && $vendor->status_approval === 'Approved')
                                <div class="w-full text-center px-2 py-1.5 bg-green-50 border border-green-200 text-green-700 text-[10px] font-semibold rounded-lg">
                                    MoU sudah disetujui
                                </div>
                            @endif

                            {{-- [MANAGER COMMERCIAL] Ditolak → info saja --}}
                            @if(auth()->user()->hasRole('manager_comercial') && $vendor->status_approval === 'Ditolak')
                                <div class="w-full text-center px-2 py-1.5 bg-red-50 border border-red-200 text-red-600 text-[10px] font-semibold rounded-lg">
                                    MoU sudah ditolak
                                </div>
                            @endif

                        </div>

                        {{-- Modal Tolak MoU (AlpineJS) --}}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <svg class="w-12 h-12 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="font-semibold text-gray-500">Tidak ada pengajuan vendor dengan status <strong>{{ $statusFilter }}</strong>.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($vendors->hasPages())
        <div class="p-6 bg-gray-50 border-t border-[#F5EBE1] pagination-wrapper">
            {{ $vendors->links() }}
        </div>
    @endif
</div>

{{-- ============================================================ --}}
{{-- MODAL POPUP: CATATAN PENOLAKAN VENDOR --}}
{{-- ============================================================ --}}
<div
    x-data="{ show: false, catatan: '', namaVendor: '' }"
    x-on:open-catatan-modal.window="catatan = $event.detail.catatan; namaVendor = $event.detail.nama; show = true"
    x-show="show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;"
    @click.self="show = false"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    {{-- Modal Card --}}
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white rounded-2xl shadow-2xl border border-red-100 max-w-md w-full z-10"
        @click.stop
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-red-100 bg-red-50 rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 border border-red-200 rounded-full flex items-center justify-center text-sm">
                    ❌
                </div>
                <div>
                    <h3 class="font-bold text-sm text-red-800">Catatan Penolakan</h3>
                    <p class="text-[11px] text-red-500 font-medium" x-text="namaVendor"></p>
                </div>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Alasan Penolakan dari Manager Commercial:</p>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-sm text-red-800 leading-relaxed" x-text="catatan"></p>
            </div>
            <p class="text-[11px] text-gray-400 mt-3 text-center">Vendor dapat memperbaiki persyaratan dan mendaftar kembali.</p>
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-5">
            <button @click="show = false"
                class="w-full py-2.5 bg-[#4A3018] text-white text-sm font-bold rounded-xl hover:bg-[#2A1B0E] transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection
