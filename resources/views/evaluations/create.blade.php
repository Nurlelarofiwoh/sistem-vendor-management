<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Survey & Evaluasi Layanan - PT Liza Makmur Mandiri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#FDF8F3] min-h-screen text-[#4A3018] py-12 px-4 sm:px-6">

    <div class="max-w-2xl w-full mx-auto">
        
        {{-- HEADER --}}
        <div class="text-center mb-10">
            <span class="px-4 py-1.5 bg-[#8B5A2B] text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm">
                PT Liza Makmur Mandiri
            </span>
            <h1 class="text-3xl font-black mt-4 text-[#4A3018] tracking-tight">E-Survey Kepuasan Klien</h1>
            <p class="text-sm text-[#8B5A2B] mt-2 font-medium">
                Evaluasi Penilaian Event: <span class="font-bold underline">{{ $project->nama_proyek }}</span>
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-[#F5EBE1] overflow-hidden">
            <div class="p-6 sm:p-10">

                @if(isset($sudahDiisi) && $sudahDiisi)
                    <div class="mb-6 p-4 bg-amber-50 border border-amber-300 rounded-2xl flex items-start gap-3">
                        <span class="text-amber-500 text-xl mt-0.5">ℹ️</span>
                        <div>
                            <p class="font-bold text-amber-800 text-sm">Form ini sudah pernah diisi</p>
                            <p class="text-amber-700 text-xs mt-1">Evaluasi untuk event ini telah dikumpulkan sebelumnya. Halaman ini ditampilkan kembali untuk keperluan presentasi/demo. Tombol kirim tidak akan memproses ulang data.</p>
                        </div>
                    </div>
                @endif

                <p class="text-sm text-gray-600 mb-8 leading-relaxed">
                    Halo <strong>{{ $project->client->nama_klien ?? 'Klien' }}</strong>,<br>
                    Terima kasih telah mempercayakan acara Anda kepada tim PT Liza Makmur Mandiri. Mohon luangkan waktu 2 menit untuk mengisi kuesioner penilaian singkat berikut guna meningkatkan mutu pelayanan kami.
                </p>


                <form action="{{ route('evaluation.submit', $project->evaluation_token) }}" method="POST"
                    id="surveyForm" onsubmit="return validateSurvey(event)">
                    @csrf

                    @if ($project->client->vendors->count() > 0)
                        <div class="space-y-8">
                            @foreach ($project->client->vendors as $vendor)
                                <div class="p-6 bg-[#FAFAFA] rounded-2xl border border-gray-200 shadow-sm transition-all hover:border-[#D4A373] vendor-card"
                                     x-data="{ q1: 0, q2: 0, q3: 0, get score() { return (this.q1 && this.q2 && this.q3) ? Math.round(((parseInt(this.q1) + parseInt(this.q2) + parseInt(this.q3)) / 3) * 10) / 10 : 0 } }">
                                    
                                    {{-- Info Vendor --}}
                                    <div class="flex justify-between items-start border-b border-gray-200 pb-3 mb-4">
                                        <div>
                                            <h3 class="font-bold text-[#4A3018] text-lg">{{ $vendor->nama_vendor }}</h3>
                                            <span class="text-[10px] font-black text-gray-400 bg-gray-100 px-2.5 py-1 rounded-md uppercase tracking-wider">
                                                {{ $vendor->kategori_jasa }}
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[10px] font-bold text-gray-400 block uppercase">Rating Terhitung</span>
                                            <div class="flex items-center gap-1 mt-0.5 justify-end">
                                                <span class="text-sm font-black text-yellow-600" x-text="score > 0 ? score : '-'"></span>
                                                <span class="text-yellow-400">★</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Hidden Input untuk backend --}}
                                    <input type="hidden" name="ratings[{{ $vendor->id }}]" id="rating-{{ $vendor->id }}" :value="Math.max(1, Math.round(score))" required>

                                    {{-- Pertanyaan 1 (Pilihan Ganda) --}}
                                    <div class="mb-5">
                                        <p class="text-xs font-bold text-gray-700 mb-2.5">1. Bagaimana tingkat kepuasan pelayanan vendor?</p>
                                        <div class="grid grid-cols-5 gap-2">
                                            @foreach([1 => 'Buruk', 2 => 'Cukup', 3 => 'Puas', 4 => 'Sangat Puas', 5 => 'Luar Biasa'] as $val => $text)
                                                <label class="flex flex-col items-center justify-center p-2 border border-gray-200 rounded-xl cursor-pointer hover:bg-[#F5EBE1] hover:border-[#8B5A2B] transition-all text-center">
                                                    <input type="radio" name="q1[{{ $vendor->id }}]" value="{{ $val }}" @change="q1 = $event.target.value" class="sr-only" required>
                                                    <span class="text-xs font-bold text-[#8B5A2B]">{{ $val }}</span>
                                                    <span class="text-[8px] text-gray-400 uppercase font-semibold mt-0.5">{{ $text }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Pertanyaan 2 (Pilihan Ganda) --}}
                                    <div class="mb-5">
                                        <p class="text-xs font-bold text-gray-700 mb-2.5">2. Bagaimana ketepatan waktu persiapan vendor?</p>
                                        <div class="grid grid-cols-4 gap-2">
                                            @foreach([1 => 'Terlambat', 3 => 'Cukup', 4 => 'Tepat', 5 => 'Cepat'] as $val => $text)
                                                <label class="flex flex-col items-center justify-center p-2 border border-gray-200 rounded-xl cursor-pointer hover:bg-[#F5EBE1] hover:border-[#8B5A2B] transition-all text-center">
                                                    <input type="radio" name="q2[{{ $vendor->id }}]" value="{{ $val }}" @change="q2 = $event.target.value" class="sr-only" required>
                                                    <span class="text-xs font-bold text-[#8B5A2B]">{{ $val }}</span>
                                                    <span class="text-[8px] text-gray-400 uppercase font-semibold mt-0.5">{{ $text }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Pertanyaan 3 (Pilihan Ganda) --}}
                                    <div>
                                        <p class="text-xs font-bold text-gray-700 mb-2.5">3. Apakah fasilitas/logistik vendor sesuai kontrak?</p>
                                        <div class="grid grid-cols-4 gap-2">
                                            @foreach([1 => 'Kecewa', 3 => 'Kurang', 4 => 'Sesuai', 5 => 'Sempurna'] as $val => $text)
                                                <label class="flex flex-col items-center justify-center p-2 border border-gray-200 rounded-xl cursor-pointer hover:bg-[#F5EBE1] hover:border-[#8B5A2B] transition-all text-center">
                                                    <input type="radio" name="q3[{{ $vendor->id }}]" value="{{ $val }}" @change="q3 = $event.target.value" class="sr-only" required>
                                                    <span class="text-xs font-bold text-[#8B5A2B]">{{ $val }}</span>
                                                    <span class="text-[8px] text-gray-400 uppercase font-semibold mt-0.5">{{ $text }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Kolom Tanya Jawab / Ulasan khusus Vendor ini --}}
                                    <div class="mt-5 pt-5 border-t border-gray-200">
                                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                            4. Ulasan & Komentar untuk {{ $vendor->nama_vendor }}:
                                        </label>
                                        <textarea name="komentar[{{ $vendor->id }}]" rows="3"
                                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#8B5A2B] focus:border-[#8B5A2B] outline-none resize-none placeholder-gray-400"
                                            placeholder="Tulis kritik, saran, atau komentar Anda mengenai vendor ini..."></textarea>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center text-red-500 bg-red-50 rounded-xl">
                            Tidak ada vendor terdaftar untuk dievaluasi.
                        </div>
                    @endif

                    {{-- (Kolom komentar umum dihapus karena sudah spesifik per vendor) --}}

                    <div class="mt-8">
                        <button type="submit" id="btnSubmit"
                            class="w-full py-4 bg-[#8B5A2B] text-white font-bold rounded-xl text-sm uppercase tracking-wider shadow-md hover:bg-[#4A3018] hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            Kirim Penilaian Survey
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-[#FDF8F3] py-4 text-center border-t border-[#F5EBE1]">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                    Dilindungi & Terenkripsi oleh PT Liza Makmur Mandiri System
                </p>
            </div>
        </div>
    </div>

    <script>
        // JS helper to handle active radio selection visual feedback
        document.addEventListener('change', function(e) {
            if (e.target.type === 'radio') {
                const name = e.target.name;
                const radios = document.querySelectorAll(`input[name="${name}"]`);
                radios.forEach(r => {
                    const label = r.closest('label');
                    if (r.checked) {
                        label.classList.remove('border-gray-200');
                        label.classList.add('border-[#8B5A2B]', 'bg-[#FDF8F3]', 'ring-1', 'ring-[#8B5A2B]');
                    } else {
                        label.classList.remove('border-[#8B5A2B]', 'bg-[#FDF8F3]', 'ring-1', 'ring-[#8B5A2B]');
                        label.classList.add('border-gray-200');
                    }
                });
            }
        });

        function validateSurvey(event) {
            const hiddenInputs = document.querySelectorAll('input[type="hidden"][name^="ratings"]');
            let allRated = true;

            hiddenInputs.forEach(input => {
                if (parseInt(input.value) === 0 || isNaN(parseInt(input.value))) {
                    allRated = false;
                }
            });

            if (!allRated) {
                alert('Mohon jawab seluruh pertanyaan pilihan ganda untuk semua vendor sebelum mengirimkan survey.');
                event.preventDefault();
                return false;
            }

            const btn = document.getElementById('btnSubmit');
            btn.innerHTML = "Mengirim Survey...";
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            return true;
        }
    </script>
</body>

</html>
