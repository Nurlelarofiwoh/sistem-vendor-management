@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-[#4A3018]">Dashboard Manager Operasional</h2>
        <p class="text-[#8B5A2B]">Analisis Kinerja dan Pertumbuhan Klien Tahun {{ date('Y') }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-[#F5EBE1]">
        <h3 class="font-bold text-xl text-[#4A3018] mb-4">Grafik Pertumbuhan Klien Per Bulan</h3>
        <div id="clientChart" class="w-full h-80"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var options = {
            series: [{
                name: 'Jumlah Klien Masuk',
                data: @json($chartData)
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            colors: ['#8B5A2B'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%'
                }
            },
            dataLabels: {
                enabled: true
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            }
        };

        var chart = new ApexCharts(document.querySelector("#clientChart"), options);
        chart.render();
    </script>
@endsection
