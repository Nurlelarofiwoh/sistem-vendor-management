<x-guest-layout>
    <div class="mb-6 text-sm text-gray-600 font-medium leading-relaxed">
        {{ __('Terima kasih telah mendaftar! Sebelum memulai, dapatkah Anda memverifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan ke email Anda? Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan email yang lain.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-bold text-sm text-green-600 bg-green-50 p-4 rounded-xl border border-green-200">
            {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
        </div>
    @endif

    <div class="mt-4 flex flex-col items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full">
            @csrf
            <button type="submit"
                class="w-full px-4 py-3.5 bg-[#8B5A2B] text-white font-bold rounded-xl hover:bg-[#4A3018] shadow-md transition-all transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit"
                class="w-full px-4 py-3.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 shadow-sm transition-colors uppercase tracking-wide text-sm">
                Log Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
