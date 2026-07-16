<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-[#4A3018] mb-1.5">Email Akses</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username"
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#8B5A2B] focus:ring focus:ring-[#8B5A2B] focus:ring-opacity-20 transition-colors">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-sm font-bold text-[#4A3018] mb-1.5">Kata Sandi</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#8B5A2B] focus:ring focus:ring-[#8B5A2B] focus:ring-opacity-20 transition-colors">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-[#8B5A2B] shadow-sm focus:ring-[#8B5A2B]">
                <span class="ms-2 text-sm font-semibold text-gray-600">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-[#8B5A2B] hover:text-[#4A3018] transition-colors"
                    href="{{ route('password.request') }}">
                    Lupa Sandi?
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit"
                class="w-full px-4 py-3.5 bg-[#8B5A2B] text-white font-bold rounded-xl hover:bg-[#4A3018] shadow-md transition-all transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                Log Masuk Ke Sistem
            </button>
        </div>
    </form>
</x-guest-layout>
