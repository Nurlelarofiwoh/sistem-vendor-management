@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-[#4A3018]">Approval Dokumen & Kontrak</h2>
            <p class="text-[#8B5A2B] mt-1">Review dan berikan persetujuan untuk MOU/Kontrak Vendor.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 border border-green-200 rounded-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-[#F5EBE1] shadow-sm rounded-xl overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#F5EBE1] text-[#4A3018] border-b border-[#D4A373]">
                    <th class="px-6 py-4 font-semibold">Nama Vendor</th>
                    <th class="px-6 py-4 font-semibold">Jenis Dokumen</th>
                    <th class="px-6 py-4 font-semibold text-center">Lihat File</th>
                    <th class="px-6 py-4 font-semibold text-center">Status Saat Ini</th>
                    <th class="px-6 py-4 font-semibold text-center">Aksi (Approval)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F5EBE1]">
                @forelse($documents as $doc)
                    <tr class="hover:bg-[#FAFAFA] transition-colors">
                        <td class="px-6 py-4 font-bold text-[#4A3018]">
                            @if($doc->vendor)
                                <a href="{{ route('vendors.show', $doc->vendor->id) }}" class="text-[#8B5A2B] hover:underline">{{ $doc->vendor->nama_vendor }}</a>
                            @else
                                Vendor Terhapus
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-600 uppercase">{{ $doc->jenis_dokumen }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                class="text-sm font-bold text-blue-600 hover:underline">📄 Buka PDF</a>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($doc->status_approval == 'pending')
                                <span
                                    class="px-3 py-1 text-xs font-bold text-yellow-700 bg-yellow-100 rounded-full">Menunggu</span>
                            @elseif($doc->status_approval == 'approved')
                                <span
                                    class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">Disetujui</span>
                            @else
                                <span
                                    class="px-3 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($doc->status_approval == 'pending')
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('approvals.update', $doc->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status_approval" value="approved">
                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm">Setujui</button>
                                    </form>
                                    <form action="{{ route('approvals.update', $doc->id) }}" method="POST" class="form-tolak-mou">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status_approval" value="rejected">
                                        <input type="hidden" name="catatan_tolak" class="input-catatan-tolak" value="">
                                        <button type="button" onclick="confirmTolakMoU(this)"
                                            class="px-3 py-1.5 text-xs font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-sm">Tolak</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs italic text-gray-400">Telah diproses</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada dokumen yang diunggah oleh
                            Partnership.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script>
        $('#approval').addClass('active');

        function confirmTolakMoU(button) {
            Swal.fire({
                title: 'Tolak MoU?',
                text: 'Masukkan alasan penolakan MoU vendor ini:',
                input: 'textarea',
                inputPlaceholder: 'Tulis alasan di sini...',
                inputAttributes: {
                    'aria-label': 'Tulis alasan di sini'
                },
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                preConfirm: (textareaValue) => {
                    if (!textareaValue || textareaValue.trim().length < 5) {
                        Swal.showValidationMessage('Alasan penolakan minimal 5 karakter');
                    }
                    return textareaValue.trim();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = button.closest('form');
                    form.querySelector('.input-catatan-tolak').value = result.value;
                    form.submit();
                }
            });
        }
    </script>
@endsection
