@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-[#4A3018]">Dashboard Finance & Invoice</h2>
        <p class="text-[#8B5A2B] mt-1">Sistem manajemen penagihan klien dan pembayaran komisi tanpa pencatatan nominal manual.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-8">

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-[#F5EBE1]">
            <div class="flex items-center gap-3 border-b pb-3 mb-5">
                <div>
                    <h3 class="font-bold text-xl text-[#4A3018]">Drop Dokumen Invoice</h3>
                    <p class="text-xs text-gray-500">Unggah file Invoice tagihan untuk event baru</p>
                </div>
            </div>

            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                @forelse($projects->where('status_pembayaran', 'menunggu_invoice') as $project)
                    <div
                        class="p-4 border-l-4 border-blue-500 bg-blue-50 rounded-r-xl shadow-sm hover:shadow-md transition-shadow">
                        <p class="font-black text-blue-900 text-lg">{{ $project->nama_proyek }}</p>
                        <p class="text-xs font-bold text-blue-700 mt-1 mb-3">Klien:
                            {{ $project->client->nama_klien ?? '-' }}</p>

                        <form action="{{ route('finance.uploadInvoice', $project->id) }}" method="POST"
                            enctype="multipart/form-data" class="flex flex-col gap-3">
                            @csrf
                            <div class="relative">
                                <input type="file" name="file_invoice" accept=".pdf,.jpg,.jpeg,.png" required
                                    class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer border border-blue-200 rounded-lg bg-white">
                            </div>
                            <button type="submit"
                                class="w-full py-2.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm uppercase tracking-wide cursor-pointer">
                                Terbitkan Invoice ke Klien
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-xl">
                        <p class="text-gray-500 font-bold">Semua Invoice Baru Sudah Diterbitkan</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-[#F5EBE1]">
            <div class="flex items-center gap-3 border-b pb-3 mb-5">
                <div>
                    <h3 class="font-bold text-xl text-[#4A3018]">Pantau Pembayaran & Validasi</h3>
                    <p class="text-xs text-gray-500">Revisi invoice atau periksa bukti pembayaran Klien</p>
                </div>
            </div>

            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                @forelse($projects->whereIn('status_pembayaran', ['menunggu_pembayaran', 'menunggu_validasi']) as $project)
                    <div
                        class="p-4 border-l-4 border-yellow-500 bg-yellow-50 rounded-r-xl shadow-sm hover:shadow-md transition-shadow">
                        <p class="font-black text-yellow-900 text-lg">{{ $project->nama_proyek }}</p>
                        <p class="text-xs font-bold text-yellow-700 mt-1 mb-3">Klien:
                            {{ $project->client->nama_klien ?? '-' }}</p>

                        <div class="p-3 bg-white border border-yellow-200 rounded-lg mb-3">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-[10px] font-bold text-gray-600">File Invoice Saat Ini:</p>
                                <a href="{{ asset('storage/' . $project->file_invoice) }}" target="_blank"
                                    class="text-[10px] font-black text-blue-600 hover:underline">Lihat Invoice</a>
                            </div>
                            <form action="{{ route('finance.uploadInvoice', $project->id) }}" method="POST"
                                enctype="multipart/form-data" class="flex items-center gap-2">
                                @csrf
                                <input type="file" name="file_invoice" accept=".pdf,.jpg,.jpeg,.png" required
                                    class="block w-full text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:font-bold file:bg-gray-200 file:text-gray-700 cursor-pointer">
                                <button type="submit"
                                    class="px-3 py-1 bg-gray-600 text-white text-[10px] font-bold rounded hover:bg-gray-700 whitespace-nowrap uppercase cursor-pointer">Revisi</button>
                            </form>
                        </div>

                        @if ($project->status_pembayaran === 'menunggu_validasi')
                            <div class="border-t border-yellow-200 pt-3">
                                <a href="{{ asset('storage/' . $project->file_bukti_tf) }}" target="_blank"
                                    class="inline-block mb-3 px-3 py-1.5 bg-yellow-200 text-yellow-800 text-[11px] font-black rounded hover:bg-yellow-300 shadow-sm text-center">
                                    Cek File Bukti Klien
                                </a>
                                <div class="grid grid-cols-2 gap-2">
                                    <form action="{{ route('finance.tolakBukti', $project->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            onclick="return confirm('Tolak bukti transfer ini? Status akan dikembalikan untuk revisi.')"
                                            class="w-full py-2 bg-white text-red-600 border border-red-300 text-[10px] font-bold rounded-lg hover:bg-red-50 uppercase tracking-wide cursor-pointer">
                                            Tolak & Revisi
                                        </button>
                                    </form>
                                    <form action="{{ route('finance.validasiBukti', $project->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            onclick="return confirm('Bukti sah? Status akan menjadi Lunas.')"
                                            class="w-full py-2 bg-green-600 text-white text-[10px] font-bold rounded-lg hover:bg-green-700 shadow-sm uppercase tracking-wide cursor-pointer">
                                            Validasi Lunas
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="border-t border-yellow-200 pt-3 text-center">
                                <span class="text-[10px] font-bold text-yellow-600 italic">Menunggu CS/Klien upload bukti transfer...</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded-xl">
                        <p class="text-gray-500 font-bold">Belum Ada Transaksi Berjalan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-2xl p-6">
        <div class="flex items-center gap-3 border-b pb-3 mb-5">
            <div>
                <h3 class="font-bold text-xl text-[#4A3018]">Riwayat Event Lunas</h3>
                <p class="text-xs text-gray-500">Arsip dokumen invoice dan bukti transfer yang telah disahkan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b border-gray-200">
                        <th class="px-6 py-4 font-bold text-sm">Nama Proyek</th>
                        <th class="px-6 py-4 font-bold text-sm">Informasi Klien</th>
                        <th class="px-6 py-4 font-bold text-sm text-center">Status</th>
                        <th class="px-6 py-4 font-bold text-sm text-center">Arsip Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($projects->where('status_pembayaran', 'lunas') as $project)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-black text-[#4A3018]">{{ $project->nama_proyek }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <p class="font-bold">{{ $project->client->nama_klien ?? '-' }}</p>
                                <p class="text-xs">No. Telepon: {{ $project->client->no_telepon ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1 text-[11px] font-black text-green-700 bg-green-100 border border-green-200 rounded-full">LUNAS Penuh</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if ($project->file_invoice)
                                        <a href="{{ asset('storage/' . $project->file_invoice) }}" target="_blank"
                                            title="Lihat Invoice Klien"
                                            class="p-2 bg-blue-50 text-blue-600 rounded border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors">
                                            Invoice
                                        </a>
                                    @endif
                                    @if ($project->file_bukti_tf)
                                        <a href="{{ asset('storage/' . $project->file_bukti_tf) }}" target="_blank"
                                            title="Lihat Bukti Transfer"
                                            class="p-2 bg-yellow-50 text-yellow-600 rounded border border-yellow-200 hover:bg-yellow-600 hover:text-white transition-colors">
                                            Bukti
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">Belum ada riwayat transaksi lunas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endsection
