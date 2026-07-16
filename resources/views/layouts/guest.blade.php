<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Vendor Management System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    class="text-[#1b1b18] antialiased bg-[#FDFDFC] bg-pattern relative overflow-hidden min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0">

    <div
        class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] bg-[#F5EBE1] rounded-full mix-blend-multiply filter blur-3xl opacity-70">
    </div>
    <div
        class="absolute bottom-[-10%] right-[-10%] w-[35vw] h-[35vw] bg-[#D4A373] rounded-full mix-blend-multiply filter blur-3xl opacity-30">
    </div>

    <div class="z-10 flex flex-col items-center mb-6">
        <a href="/">
            <div
                class="w-16 h-16 bg-[#8B5A2B] rounded-2xl flex items-center justify-center shadow-lg rotate-3 mb-4 hover:rotate-0 transition-transform">
                <span class="text-white font-black text-3xl tracking-tighter">VM</span>
            </div>
        </a>
        <h1 class="text-2xl font-black text-[#4A3018] tracking-tight">Vendor Management System</h1>
    </div>

    <div
        class="w-full sm:max-w-md mt-2 px-8 py-10 bg-white shadow-[0_15px_40px_rgba(74,48,24,0.1)] border border-[#F5EBE1] overflow-hidden sm:rounded-2xl z-10">
        {{ $slot }}
    </div>
</body>

</html>
