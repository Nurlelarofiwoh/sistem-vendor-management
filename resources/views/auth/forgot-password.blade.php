<x-guest-layout>
    <div class="mb-6 text-sm text-gray-600 font-medium leading-relaxed">
        {{ __('Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan tautan reset kata sandi yang memungkinkan Anda memilih kata sandi baru.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-[#4A3018] mb-1.5">Email Terdaftar</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#8B5A2B] focus:ring focus:ring-[#8B5A2B] focus:ring-opacity-20 transition-colors">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2 flex flex-col gap-3">
            <button type="submit" class="w-full px-4 py-3.5 bg-[#8B5A2B] text-white font-bold rounded-xl hover:bg-[#4A3018] shadow-md transition-all transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                Kirim Tautan Reset Sandi
            </button>
            <a href="{{ route('login') }}" class="text-center text-sm font-bold text-gray-500 hover:text-[#4A3018] transition-colors">
                Kembali ke Halaman Login
            </a>
        </div>
    </form>
</x-guest-layout><x-guest-layout>
    <div class="mb-6 text-sm text-gray-600 font-medium leading-relaxed">
        {{ __('Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan tautan reset kata sandi yang memungkinkan Anda memilih kata sandi baru.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-[#4A3018] mb-1.5">Email Terdaftar</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#8B5A2B] focus:ring focus:ring-[#8B5A2B] focus:ring-opacity-20 transition-colors">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2 flex flex-col gap-3">
            <button type="submit" class="w-full px-4 py-3.5 bg-[#8B5A2B] text-white font-bold rounded-xl hover:bg-[#4A3018] shadow-md transition-all transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                Kirim Tautan Reset Sandi
            </button>
            <a href="{{ route('login') }}" class="text-center text-sm font-bold text-gray-500 hover:text-[#4A3018] transition-colors">
                Kembali ke Halaman Login
            </a>
        </div>
    </form>
</x-guest-layout>