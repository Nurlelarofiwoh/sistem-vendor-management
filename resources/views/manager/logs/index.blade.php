@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-[#4A3018]">Log Aktivitas CS (Real-Time)</h2>
            <p class="text-[#8B5A2B] mt-1">Pemantauan rekam jejak aktivitas operasional Admin CS.</p>
        </div>
    </div>

    <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#F5EBE1] text-[#4A3018] border-b border-[#D4A373]">
                    <th class="px-6 py-4 font-semibold">Waktu (WIB)</th>
                    <th class="px-6 py-4 font-semibold">User</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                    <th class="px-6 py-4 font-semibold">Deskripsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F5EBE1]">
                @forelse($logs as $log)
                    <tr class="hover:bg-[#FAFAFA] transition-colors">
                        <td class="px-6 py-4 text-xs font-semibold text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 font-bold text-[#4A3018] whitespace-nowrap">
                            {{ $log->user_name }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 text-xs font-bold text-[#8B5A2B] bg-[#F5EBE1] rounded border border-[#D4A373] whitespace-nowrap">
                                {{ $log->aksi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $log->deskripsi }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">Belum ada rekam log aktivitas CS saat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
@endsection
