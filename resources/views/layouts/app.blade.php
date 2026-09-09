<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'VMS') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="font-sans antialiased bg-[#FAFAFA] text-[#4A3018]" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 transition-transform duration-300 ease-in-out transform bg-[#4A3018] shadow-xl lg:translate-x-0 lg:static lg:inset-0 text-[#F5EBE1]">
            <div class="flex items-center justify-center h-20 border-b border-[#8B5A2B]">
                <h1 class="text-2xl font-bold text-[#D4A373]">VMS</h1>
            </div>

            <nav class="p-4 space-y-2 overflow-y-auto h-[calc(100vh-5rem)] pb-10">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('dashboard') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                    <span>Dashboard</span>
                </a>

                @hasrole('admin_cs')
                    <a href="{{ route('clients.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('clients.*') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Data Klien</span>
                    </a>
                    <a href="{{ route('vendors.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('vendors.index') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Data Vendor</span>
                    </a>
                    <a href="{{ route('events.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('events.index') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Management Event</span>
                    </a>
                @endhasrole

                @hasrole('partnership')
                    <a href="{{ route('vendors.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('vendors.index') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Data Vendor</span>
                    </a>
                    <a href="{{ route('vendor.approval.index') }}"
                        class="flex items-center justify-between p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('vendor.approval.*') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Pengajuan Vendor</span>
                        @if(isset($spVendorPending) && $spVendorPending > 0)
                            <span id="sidebarPendingBadge" class="bg-red-500 text-white font-bold text-[10px] px-2 py-0.5 rounded-full min-w-[20px] text-center">
                                {{ $spVendorPending }}
                            </span>
                        @else
                            <span id="sidebarPendingBadge" class="bg-red-500 text-white font-bold text-[10px] px-2 py-0.5 rounded-full min-w-[20px] text-center hidden">
                                0
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('events.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('events.index') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Management Event</span>
                    </a>
                    <a href="{{ route('technical-meetings.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('technical-meetings.*') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Penjadwalan TM</span>
                    </a>
                    <a href="{{ route('events.calendar') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('events.calendar') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Event (Kalender)</span>
                    </a>
                    <a href="{{ route('clients.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('clients.*') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Data Klien</span>
                    </a>
                    <a href="{{ route('partnership.report.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('partnership.report.*') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Laporan Terpadu</span>
                    </a>
                    <a href="{{ route('reviews.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('reviews.index') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Ulasan & Survey</span>
                    </a>
                @endhasrole
                @hasrole('finance')
                    <a href="{{ route('finance.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('finance.*') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Finance & Invoice</span>
                    </a>
                    <a href="{{ route('events.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('events.index') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Management Event</span>
                    </a>
                @endhasrole

                @hasrole('manager_comercial')
                    <a href="{{ route('approvals.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('approvals.*') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Persetujuan Kontrak</span>
                    </a>
                    <a href="{{ route('vendors.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('vendors.*') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Daftar Vendor</span>
                    </a>
                    <a href="{{ route('manager.logs') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('manager.logs') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Log Aktivitas CS</span>
                    </a>
                    <a href="{{ route('reviews.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('reviews.index') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Ulasan & Survey</span>
                    </a>
                @endhasrole

                @hasrole('manager_operasional')
                    <a href="{{ route('operasional.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('operasional.*') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Validasi Logistik</span>
                    </a>
                    <a href="{{ route('events.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('events.index') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Management Event</span>
                    </a>
                    <a href="{{ route('vendors.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('vendors.*') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Vendor </span>
                    </a>
                    <a href="{{ route('clients.index') }}"
                        class="flex items-center p-3 transition-colors rounded-lg hover:bg-[#8B5A2B] {{ request()->routeIs('clients.*') ? 'bg-[#8B5A2B] text-white font-bold' : '' }}">
                        <span>Klien </span>
                    </a>
                @endhasrole
            </nav>
        </aside>

        <div class="flex flex-col flex-1 w-full overflow-hidden">
            <header class="flex items-center justify-between h-20 px-6 bg-white border-b shadow-sm border-[#F5EBE1]">
                <button @click="sidebarOpen = !sidebarOpen" class="text-[#8B5A2B] focus:outline-none lg:hidden">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex items-center ml-auto space-x-4">
                    <div class="relative" x-data="{ openNotif: false }">
                        <button @click="openNotif = !openNotif"
                            class="relative p-2 text-[#8B5A2B] hover:bg-[#F5EBE1] rounded-full transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span
                                    class="absolute top-0 right-0 w-4 h-4 text-[10px] font-bold text-white bg-red-600 border-2 border-white rounded-full flex items-center justify-center">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>

                        <div x-show="openNotif" @click.away="openNotif = false"
                            class="absolute right-0 w-80 mt-2 bg-white border border-[#F5EBE1] rounded-xl shadow-lg overflow-hidden z-50"
                            style="display: none;">
                            <div class="p-3 bg-[#F5EBE1] border-b border-[#D4A373] flex justify-between items-center">
                                <span class="font-bold text-[#4A3018]">Pemberitahuan</span>
                                <a href="{{ route('notifications.readAll') }}"
                                    class="text-xs text-[#8B5A2B] hover:underline">Tandai dibaca</a>
                            </div>
                            <ul class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notif)
                                    <li class="p-3 border-b hover:bg-gray-50">
                                        <p class="text-sm font-semibold text-[#4A3018]">
                                            {{ $notif->data['pesan'] ?? 'Notifikasi Baru' }}</p>
                                        <span
                                            class="text-xs text-gray-500">{{ isset($notif->data['jadwal']) ? \Carbon\Carbon::parse($notif->data['jadwal'])->format('d M H:i') : $notif->created_at->diffForHumans() }}</span>
                                    </li>
                                @empty
                                    <li class="p-4 text-center text-sm text-gray-500">Tidak ada notifikasi baru.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <span
                        class="font-medium text-[#4A3018] border-l pl-4 border-[#F5EBE1]">{{ Auth::user()->name ?? 'User' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm font-bold text-[#F5EBE1] bg-[#8B5A2B] rounded-full hover:bg-[#4A3018] transition-colors shadow-sm">Logout</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#FAFAFA] p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: {!! json_encode(session('success')) !!},
                confirmButtonColor: '#8B5A2B'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Peringatan!',
                html: '{!! implode('<br>', $errors->all()) !!}',
                confirmButtonColor: '#8B5A2B'
            });
        @endif
    </script>
    @if(auth()->check() && auth()->user()->hasRole('partnership'))
    <script>
        function checkPendingVendorCount() {
            fetch('/api/vendor-pending-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('sidebarPendingBadge');
                    if (badge) {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    }
                })
                .catch(err => console.error('Error fetching pending vendor count:', err));
        }
        // Poll every 60 seconds
        setInterval(checkPendingVendorCount, 60000);
    </script>
    @endif
    @yield('script')
</body>

</html>
