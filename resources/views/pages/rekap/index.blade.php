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
                        Jumlah Uang Masuk
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
                        Jumlah Uang Keluar
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
                        Pendapatan 30 Hari Terakhir
                    </h5>
                </div>

                {{-- <div iv class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between"> --}}
                <div class="flex justify-between items-center">
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
                </div>

            </div>
            <div id="area-chart"></div>
        </div>
    </div>
    </div>
    <script>
        let chartyear = document.getElementById('chartyear');
        let judulchart = document.getElementById('judul-chart')
        let pilihanchart = document.getElementById('pilihan-chart')

        let options = {
            chart: {
                height: "149%",
                maxWidth: "100%",
                type: "area",
                fontFamily: "Inter, sans-serif",
                dropShadow: {
                    enabled: false,
                },
                toolbar: {
                    show: false,
                },
            },
            tooltip: {
                enabled: true,
                x: {
                    show: false,
                },
            },
            fill: {
                type: "gradient",
                gradient: {
                    opacityFrom: 0.55,
                    opacityTo: 0,
                    shade: "#1C64F2",
                    gradientToColors: ["#1C64F2"],
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                width: 6,
            },
            grid: {
                show: false,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: 0
                },
            },
            series: [{
                name: "Pendapatan",
                data: [],
                color: "#1A56DB",
            }, ],
            xaxis: {
                categories: [],
                labels: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                show: false,
            },
        }

        var chart = new ApexCharts(document.getElementById("area-chart"), options);
        chart.render();

        chartyear.addEventListener('click', function() {
            // Refresh the window with the #chartYear fragment
            location.href = location.href.split('#')[0] + '#chartyear';

            judulchart.innerText = 'Pendapatan 1 Tahun Terakhir'; // Ganti dengan judul yang diinginkan
            pilihanchart.innerText = '1 Tahun';
            fetch('{{ route('chart.1year') }}', {
                    headers: {
                        'Accept': 'application/json',
                        "X-Requested-With": "XMLHttpRequest",
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json(); // Assuming the response is JSON
                    } else {
                        console.error('Failed to check #chartyear');
                    }
                })
                .then(dataFecthing => {
                    console.log(dataFecthing);
                    var dataPenjualanSatuTahun = dataFecthing.data.data_penjualan;
                    var dataBulanSatuTahun = dataFecthing.data.bulan;
                    console.log(dataPenjualanSatuTahun);
                    console.log(dataBulanSatuTahun);
                    // Get the ApexCharts instance
                    chart.updateOptions({
                        xaxis: {
                            categories: dataBulanSatuTahun
                        },
                        series: [{
                            data: dataPenjualanSatuTahun
                        }],
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });


        var dataPenjualan = @json($dataPenjualanFormatted);
        var tanggalPenjualan = @json($tanggalPenjualanFormatted);
        chart.updateOptions({
            xaxis: {
                categories: tanggalPenjualan
            },
            series: [{
                data: dataPenjualan
            }],
        });
    </script>
    <script src="{{ asset('js/chart.js') }}"></script>
@endsection
