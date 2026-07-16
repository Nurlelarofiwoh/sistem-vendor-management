<x-guest-layout>
    <div class="mb-6 text-sm text-gray-600 font-medium leading-relaxed">
        {{ __('Area ini merupakan area aman pada sistem. Silakan konfirmasi kata sandi Anda sebelum melanjutkan.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <label for="password" class="block text-sm font-bold text-[#4A3018] mb-1.5">Kata Sandi Akses</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" 
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#8B5A2B] focus:ring focus:ring-[#8B5A2B] focus:ring-opacity-20 transition-colors">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit" class="w-full px-4 py-3.5 bg-[#4A3018] text-white font-bold rounded-xl hover:bg-[#2A1B0E] shadow-md transition-all transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                Konfirmasi Identitas
            </button>
        </div>
    </form>
</x-guest-layout>