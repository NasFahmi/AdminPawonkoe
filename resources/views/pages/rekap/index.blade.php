@extends('layout.admin_pages')
@section('title', 'Rekap Keuangan')
@section('content')
    <div class="container px-6 pb-6 mx-auto ">
        <p class="text-2xl my-6 font-semibold text-gray-700">Rekap Keuangan</p>
        {{-- <p>{{$data}}</p> --}}


        {{-- cards --}}
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4 ">

            {{-- card --}}
            <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                <img width="40px" src="{{ asset('assets/icon/receive-money.png') }}" alt="">
                <div class="p-3">
                    <p class="mb-2 text-xs font-medium text-gray-600 ">
                        Total Uang Masuk
                    </p>
                    <p class="text-lg font-semibold text-gray-700 ">
                        {{ $jumlahUangMasukFormatted }}
                    </p>
                </div>
            </div>

            {{-- card --}}
            <div class="flex items-center p-4 bg-white rounded-lg shadow-md ">
                <img width="40px" src="{{ asset('assets/icon/send-money.png') }}" alt="">
                <div class="p-3">
                    <p class="mb-2 text-xs font-medium text-gray-600 ">
                        Total Uang Keluar
                    </p>
                    <p class="text-lg font-semibold text-gray-700 ">
                        {{ $jumlahUangKeluarFormatted }}
                    </p>
                </div>
            </div>

            {{-- card --}}
            <div class="flex items-center p-4 bg-white rounded-lg shadow-md ">
                <img width="40px" src="{{ asset('assets/icon/dollar.png') }}" alt="">
                <div class="p-3">
                    <p class="mb-2 text-xs font-medium text-gray-600 ">
                        Saldo Akhir
                    </p>
                    <p class="text-lg font-semibold text-gray-700 ">
                        {{ $saldoAkhirFormatted }}
                    </p>
                </div>
            </div>


            {{-- card --}}
            <div class="flex items-center p-4 bg-blue-100 rounded-lg shadow-md hover:bg-white">
                <div class="justify-center p-3">
                    <a class="inline-flex items-center w-full text-sm font-semibold group
                @if (Request::is('admin/rekap-keuangan/detail*')) text-blue-800
                @else
                    text-blue-500 @endif
                transition-colors duration-150 hover:text-blue-800"
                        href="{{ route('rekap.detail') }}">

                        <img width="40px"
                            class="@if (Request::is('admin/rekap-keuangan/detail*')) opacity-100
        @else
            opacity-60 @endif 
        transition-opacity duration-150 group-hover:opacity-100"
                            src="{{ asset('assets/icon/mouse-clicker.png') }}" alt="">

                        <span class="ml-4 text-lg">Cek Detail</span>
                    </a>

                </div>
            </div>
        </div>



        {{-- chart --}}
        <div class=" w-full h-fit bg-white rounded-lg shadow-md  p-4 md:p-6 col-span-1 lg:col-span-2">
            <div class="flex justify-between">
                <div>
                    <h5 id="judul-chart" class="leading-none text-xl font-semibold text-gray-900 dark:text-white pb-2">
                        Pendapatan
                    </h5>
                </div>

                {{-- <div iv class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between"> --}}
                {{-- <div class="flex justify-between items-center">
                    <!-- Button -->
                    <button id="" data-dropdown-toggle="lastDaysdropdown" data-dropdown-placement="bottom"
                        class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                        type="button">
                        <span id="pilihan-chart">
                            1 Bulan
                        </span>

                        <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="lastDaysdropdown"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">

                            <li>
                                <a href=""
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    1 Bulan</a>
                            </li>
                            <li>
                                <a href="#chartyear" id="chartyear"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    1 Tahun Terakhir</a>
                            </li>
                        </ul>
                    </div>
                </div> --}}
                <div class="flex justify-between items-center">
                    <select id="tahun" class="form-select mr-2">
                        @for ($year = 2020; $year <= now()->year; $year++)
                            <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>

                    <select id="bulan" class="form-select mr-2">
                        <option value="-">-</option>
                        @foreach ($daftarBulan as $key => $bulan)
                            <option value="{{ $key }}">
                                {{ $bulan }}
                            </option>
                        @endforeach
                    </select>

                    <button id="filter-chart" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
                </div>



            </div>
            <div id="area-chart"></div>
        </div>
    </div>
    </div>
    <script>
        let options = {
    series: [{
        name: 'Saldo Akhir',
        data: []
    }],
    chart: {
        type: 'bar',
        height: 500
    },
    plotOptions: {
        bar: {
            colors: {
                ranges: [{
                    from: -100,
                    to: -46,
                    color: '#F15B46'
                }, {
                    from: -45,
                    to: 0,
                    color: '#FEB019'
                }]
            },
            columnWidth: '80%',
        }
    },
    dataLabels: {
        enabled: false,
    },
    yaxis: {
        title: {
            text: 'Jumlah (Rp)',
        },
        tickAmount: 4,
        labels: {
            formatter: function(value) {
                return 'Rp ' + value.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        }
    },
    xaxis: {
        categories: [],
        labels: {
            rotate: -90
        }
    }
};

var chart = new ApexCharts(document.getElementById("area-chart"), options);
chart.render();

document.getElementById('filter-chart').addEventListener('click', function() {
    var tahun = document.getElementById('tahun').value;
    var bulan = document.getElementById('bulan').value;

    fetch('{{ route('chart.filter') }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            "X-Requested-With": "XMLHttpRequest",
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            tahun: tahun,
            bulan: bulan
        })
    })
    .then(response => response.json())
    .then(data => {
        // For monthly data, data.date is an object
        // For daily data, data.date is an array
        const categories = Array.isArray(data.date) ? data.date : Object.values(data.date);
        
        chart.updateOptions({
            series: [{
                name: 'Saldo Akhir',
                data: data.saldoAkhir
            }],
            xaxis: {
                categories: categories,
                labels: {
                    rotate: -90,
                    formatter: function(value) {
                        // Format daily dates to show only the day
                        if (bulan !== '-') {
                            return value.split('-')[2]; // Returns only the day part
                        }
                        return value;
                    }
                }
            }
        });
    })
    .catch(error => console.error('Error:', error));
});
    </script>
@endsection
