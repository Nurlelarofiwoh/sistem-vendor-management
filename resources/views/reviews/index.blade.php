@extends('layouts.app')

@section('title', 'Ulasan & E-Survey Klien')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#4A3018] flex items-center gap-2">
            ⭐ Hasil E-Survey Klien
        </h1>
        <p class="text-gray-600 mt-1">Daftar ulasan dan rating layanan vendor dari klien</p>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[#4A3018] text-sm font-semibold uppercase tracking-wider">
                        <th class="p-4 px-6">Vendor</th>
                        <th class="p-4">Kontak</th>
                        <th class="p-4">Project / Event</th>
                        <th class="p-4 text-center">Rating</th>
                        <th class="p-4">Komentar / Saran Klien</th>
                        <th class="p-4 px-6 text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 px-6 align-top">
                            <div class="font-bold text-[#4A3018]">{{ $review->vendor->nama_vendor ?? '-' }}</div>
                        </td>
                        <td class="p-4 align-top">
                            @if($review->vendor)
                                <div class="text-sm space-y-1">
                                    @if($review->vendor->no_telepon)
                                        <div class="flex items-center text-gray-700 hover:text-[#8B5A2B]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                              <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            </svg>
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $review->vendor->no_telepon) }}" target="_blank" class="hover:underline">{{ $review->vendor->no_telepon }}</a>
                                        </div>
                                    @endif
                                    @if($review->vendor->email)
                                        <div class="flex items-center text-gray-700 hover:text-[#8B5A2B]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                            </svg>
                                            <a href="mailto:{{ $review->vendor->email }}" class="hover:underline">{{ $review->vendor->email }}</a>
                                        </div>
                                    @endif
                                    @if(!$review->vendor->no_telepon && !$review->vendor->email)
                                        <span class="text-gray-400 italic">Tidak ada kontak</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="p-4 align-top text-gray-700">
                            {{ $review->project->nama_proyek ?? '-' }}
                        </td>
                        <td class="p-4 align-top text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-bold text-sm">
                                ⭐ {{ $review->score }}
                            </span>
                        </td>
                        <td class="p-4 align-top">
                            @if($review->comment)
                                <div class="text-gray-600 text-sm whitespace-pre-wrap">{{ $review->comment }}</div>
                            @else
                                <span class="text-gray-400 italic text-sm">Tidak ada komentar</span>
                            @endif
                        </td>
                        <td class="p-4 px-6 align-top text-right text-sm text-gray-500 whitespace-nowrap">
                            {{ $review->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                Belum ada data ulasan dari klien.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
