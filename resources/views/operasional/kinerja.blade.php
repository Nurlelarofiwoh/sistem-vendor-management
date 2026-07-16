@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-[#4A3018]">Log & Monitoring Kinerja CS</h2>
        <p class="text-[#8B5A2B] mt-1">Pantau aktivitas input data harian dari staf Customer Service.</p>
    </div>

    <div class="mb-6 p-6 bg-white border border-[#F5EBE1] shadow-sm rounded-xl flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 font-semibold">Total Aktivitas CS Hari Ini</p>
            <p class="text-3xl font-bold text-[#4A3018]">{{ $totalInputHariIni }} Tindakan</p>
        </div>
        <div class="p-4 bg-[#F5EBE1] text-[#8B5A2B] rounded-full">📊</div>
    </div>

    <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl p-6">
        <h3 class="mb-6 text-lg font-bold text-[#4A3018] border-b pb-2">Rekaman Aktivitas (Waktu Real-Time)</h3>

        <div class="relative border-l-2 border-[#D4A373] ml-3 space-y-8">
            @forelse($logs as $log)
                <div class="relative pl-6">
                    <div class="absolute -left-[9px] top-1 w-4 h-4 bg-[#8B5A2B] rounded-full border-4 border-white shadow">
                    </div>

                    <div class="flex items-center gap-3 mb-1">
                        <span class="text-xs font-bold text-white bg-[#8B5A2B] px-2 py-1 rounded">{{ $log->aksi }}</span>
                        <span class="text-sm font-semibold text-gray-600">⌚
                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y - H:i:s') }} WIB</span>
                    </div>
                    <p class="text-[#4A3018] font-medium">{{ $log->deskripsi }}</p>
                    <p class="text-xs text-gray-400 mt-1">Dilakukan oleh: {{ $log->user_name }}</p>
                </div>
            @empty
                <div class="pl-6 text-gray-500">Belum ada rekaman aktivitas CS.</div>
            @endforelse
        </div>
    </div>
@endsection
