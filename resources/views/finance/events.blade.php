@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-[#4A3018]">Management Event (Drop Invoice)</h2>
        <p class="text-[#8B5A2B] mt-1">Pantau event yang sedang berjalan dan terbitkan invoice tagihan komisi ke vendor.</p>
    </div>

    @if (session('success'))
        <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 border border-green-200 rounded-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#F5EBE1] text-[#4A3018] border-b border-[#D4A373]">
                    <th class="px-6 py-4 font-semibold">Nama Proyek & Klien</th>
                    <th class="px-6 py-4 font-semibold">Vendor Rekanan</th>
                    <th class="px-6 py-4 font-semibold w-1/3 text-center">Status Invoice & Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F5EBE1]">
                @forelse($projects as $project)
                    <tr class="hover:bg-[#FAFAFA]">
                        <td class="px-6 py-4">
                            <p class="font-bold text-[#4A3018]">{{ $project->nama_proyek }}</p>
                            <p class="text-sm text-gray-500">Klien: {{ $project->client->nama_klien ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-700">{{ $project->vendor->nama_vendor ?? '-' }}</p>
                            <span
                                class="px-2 py-1 text-[10px] font-bold text-[#8B5A2B] bg-[#F5EBE1] rounded-full uppercase">{{ $project->vendor->kategori_jasa ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($project->financePayment)
                                <div
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg text-green-700 font-semibold text-sm">
                                    <span>✅ Invoice Telah Diterbitkan</span>
                                </div>
                            @else
                                <form action="{{ route('finance.storeInvoice', $project->id) }}" method="POST"
                                    class="flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="jumlah_komisi" placeholder="Nominal Rp" required
                                        class="w-1/2 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-[#D4A373]">
                                    <input type="date" name="tenggat_waktu" required
                                        class="w-1/2 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-[#D4A373]"
                                        title="Tenggat Waktu Bayar">
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-bold text-white bg-[#8B5A2B] rounded-lg hover:bg-[#4A3018] shadow-sm whitespace-nowrap">
                                        Drop Invoice
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">Belum ada proyek/event yang
                            terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
