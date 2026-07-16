<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-bold text-[#4A3018] mb-1.5">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                autocomplete="name"
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#8B5A2B] focus:ring focus:ring-[#8B5A2B] focus:ring-opacity-20 transition-colors">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-[#4A3018] mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                autocomplete="username"
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#8B5A2B] focus:ring focus:ring-[#8B5A2B] focus:ring-opacity-20 transition-colors">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-sm font-bold text-[#4A3018] mb-1.5">Kata Sandi</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#8B5A2B] focus:ring focus:ring-[#8B5A2B] focus:ring-opacity-20 transition-colors">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-[#4A3018] mb-1.5">Konfirmasi Kata
                Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password"
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#8B5A2B] focus:ring focus:ring-[#8B5A2B] focus:ring-opacity-20 transition-colors">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4 flex flex-col gap-3">
            <button type="submit"
                class="w-full px-4 py-3.5 bg-[#4A3018] text-white font-bold rounded-xl hover:bg-[#2A1B0E] shadow-md transition-all transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                Daftar Akun Baru
            </button>
            <a class="text-center text-sm font-bold text-[#8B5A2B] hover:text-[#4A3018] transition-colors"
                href="{{ route('login') }}">
                Sudah memiliki akun? Log masuk
            </a>
        </div>
    </form>
</x-guest-layout>
