@extends('layouts.app')

@section('content')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-[#4A3018]">Kalender Operasional Event</h2>
                <p class="text-[#8B5A2B] mt-1 font-medium">Pemantauan jadwal acara dan alokasi vendor untuk Divisi Strategic
                    Partnership.</p>
            </div>
            <a href="{{ route('events.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors uppercase tracking-wide text-xs">
                Kembali ke List
            </a>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-[#F5EBE1] shadow-sm">
            <div id='calendar'></div>
        </div>
    </div>

    <div id="eventModal"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform"
            id="modalContent">
            <div class="bg-[#4A3018] p-4 text-center">
                <h3 id="modalTitle" class="text-lg font-black text-white uppercase tracking-wider">Nama Event</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">Tanggal Pelaksanaan</p>
                    <p id="modalDate"
                        class="text-sm font-semibold text-gray-800 bg-gray-50 p-2 rounded border border-gray-100"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">Lokasi Utama</p>
                    <p id="modalLocation"
                        class="text-sm font-semibold text-gray-800 bg-gray-50 p-2 rounded border border-gray-100"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">Vendor Terlibat</p>
                    <p id="modalVendor"
                        class="text-xs font-semibold text-[#8B5A2B] bg-[#F5EBE1] p-3 rounded-lg border border-[#D4A373] leading-relaxed">
                    </p>
                </div>
            </div>
            <div class="p-4 border-t border-gray-100">
                <button onclick="closeModal()"
                    class="w-full py-2 bg-gray-200 text-gray-800 font-bold rounded-lg hover:bg-gray-300 text-xs uppercase tracking-wider transition-colors">Tutup
                    Jendela</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var eventData = @json($formattedEvents); // Data dari Controller

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'standard',
                displayEventTime: false,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: eventData,
                eventClick: function(info) {
                    // Mencegah link default
                    info.jsEvent.preventDefault();

                    // Mengisi data ke Modal
                    document.getElementById('modalTitle').innerText = info.event.title;
                    document.getElementById('modalDate').innerText = info.event.start
                        .toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    document.getElementById('modalLocation').innerText = info.event.extendedProps
                        .location || 'Tidak ada info lokasi';
                    document.getElementById('modalVendor').innerText = info.event.extendedProps
                        .description || 'Belum ada vendor';

                    // Menampilkan Modal
                    var modal = document.getElementById('eventModal');
                    var modalContent = document.getElementById('modalContent');
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        modalContent.classList.remove('scale-95');
                        modalContent.classList.add('scale-100');
                    }, 10);
                }
            });

            calendar.render();
        });

        function closeModal() {
            var modal = document.getElementById('eventModal');
            var modalContent = document.getElementById('modalContent');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }
    </script>

    <style>
        /* Styling khusus agar kalender cocok dengan tema VMS */
        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: #F5EBE1;
        }

        .fc-col-header-cell {
            background-color: #FAFAFA;
            padding: 8px 0;
            color: #4A3018;
            font-size: 0.875rem;
            text-transform: uppercase;
        }

        .fc-button-primary {
            background-color: #8B5A2B !important;
            border-color: #8B5A2B !important;
            text-transform: uppercase;
            font-size: 12px !important;
            font-weight: bold !important;
        }

        .fc-button-primary:hover {
            background-color: #4A3018 !important;
            border-color: #4A3018 !important;
        }

        .fc-button-active {
            background-color: #4A3018 !important;
        }

        .fc-daygrid-day-number {
            color: #4A3018;
            font-weight: bold;
            font-size: 14px;
            padding: 4px 8px;
        }

        .fc-event {
            cursor: pointer;
            padding: 2px 4px;
            border-radius: 4px;
            border: none;
            font-size: 11px;
            font-weight: bold;
        }

        .fc-day-today {
            background-color: #FDF8F3 !important;
        }
    </style>
@endsection
