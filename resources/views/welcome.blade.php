<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Management System (VMS) - Event & Vendor Management</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }

        .bg-pattern {
            background-image: radial-gradient(#D4A373 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>

<body
    class="bg-[#FDFDFC] text-[#1b1b18] flex items-center justify-center min-h-screen relative overflow-hidden bg-pattern">

    <div
        class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] bg-[#F5EBE1] rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse">
    </div>
    <div
        class="absolute bottom-[-10%] right-[-10%] w-[35vw] h-[35vw] bg-[#D4A373] rounded-full mix-blend-multiply filter blur-3xl opacity-30">
    </div>

    <main class="w-full max-w-5xl p-6 lg:p-8 relative z-10">
        <div
            class="flex flex-col lg:flex-row items-center bg-white shadow-2xl rounded-2xl overflow-hidden border border-[#F5EBE1]">

            <div class="w-full lg:w-1/2 p-8 lg:p-14 flex flex-col justify-center bg-white z-20">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-[#8B5A2B] rounded-xl flex items-center justify-center shadow-md rotate-3">
                        <span class="text-white font-black text-2xl tracking-tighter">VMS</span>
                    </div>
                    <h1 class="text-3xl font-black text-[#4A3018] tracking-tight">Login</h1>
                </div>

                <h2 class="text-4xl lg:text-5xl font-black text-[#4A3018] mb-4 leading-tight">
                    Vendor Management <br><span class="text-[#8B5A2B]">System.</span>
                </h2>
                <p class="text-gray-600 mb-10 text-lg leading-relaxed font-medium">
                    Portal operasional terpadu untuk mengelola basis data klien, merekomendasikan vendor terbaik, dan
                    melacak seluruh siklus kontrak secara real-time.
                </p>

                @if (Route::has('login'))
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="w-full sm:w-auto text-center px-8 py-4 bg-[#8B5A2B] hover:bg-[#4A3018] text-white font-bold rounded-xl shadow-[0_10px_20px_rgba(139,90,43,0.2)] transition-all transform hover:-translate-y-1 uppercase tracking-wider text-sm flex justify-center items-center gap-2">
                                Masuk ke Dashboard
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="w-full sm:w-auto text-center px-8 py-4 bg-[#4A3018] hover:bg-[#2A1B0E] text-white font-bold rounded-xl shadow-[0_10px_20px_rgba(74,48,24,0.3)] transition-all transform hover:-translate-y-1 uppercase tracking-wider text-sm">
                                Login Karyawan
                            </a>


                        @endauth
                    </div>
                @endif

                <div
                    class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-between text-xs font-semibold text-gray-400">
                    <p>&copy; {{ date('Y') }} PT Vendor Management System.</p>
                    <p class="flex items-center gap-1">Akses Terenkripsi <svg class="w-4 h-4 text-green-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg></p>
                </div>
            </div>

            <div
                class="hidden lg:flex w-1/2 bg-[#F5EBE1] relative items-center justify-center overflow-hidden min-h-[600px] border-l border-[#EBD5C1]">
                <div
                    class="relative z-10 w-64 h-64 bg-white/50 backdrop-blur-md rounded-full shadow-2xl flex items-center justify-center border-4 border-white">
                    <span class="text-[#8B5A2B] font-black text-6xl tracking-tighter">VM<span
                            class="text-[#4A3018]">S</span></span>
                </div>

                <div
                    class="absolute top-20 right-20 w-32 h-32 bg-[#D4A373] rounded-2xl rotate-12 opacity-40 mix-blend-multiply">
                </div>
                <div class="absolute bottom-20 left-20 w-40 h-40 bg-white rounded-full opacity-60"></div>
            </div>

        </div>
    </main>

</body>

</html>
