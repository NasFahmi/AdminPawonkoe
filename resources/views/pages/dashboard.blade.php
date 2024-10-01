@extends('layout.admin_pages')
@section('title', 'Admin Pawonkoe')
@section('content')
    <div class="container px-6 pb-6 mx-auto grid">

        <!-- Modal untuk Login Success -->
        <div id="loginSuccessModal"
            class="fixed inset-x-0 top-0 transform -translate-y-full flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <svg class="mx-auto mb-4 text-green-500 w-12 h-12" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h3 class="mb-2 text-lg font-normal text-gray-700">Login berhasil!</h3>
            </div>
        </div>

        <h2 class="my-6 text-2xl font-semibold text-gray-700 ">
            Dashboard
        </h2>
        <!-- Cards -->
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-md ">
                <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full ">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-xs font-medium text-gray-600 ">
                        Jumlah Transaksi
                    </p>
                    <p class="text-lg font-semibold text-gray-700 ">
                        {{ $dataJumlahOrder }}
                    </p>
                </div>
            </div>
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-md ">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full ">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-xs font-medium text-gray-600 ">
                        Jumlah Pendapatan
                    </p>
                    <p class="text-lg font-semibold text-gray-700 ">
                        {{-- {{ number_format($items->harga_rendah, 0, ',', '.') }} --}}
                        Rp. {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-md ">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-xs font-medium text-gray-600 ">
                        Jumlah Produk Terjual
                    </p>
                    <p class="text-lg font-semibold text-gray-700 ">
                        {{ $totalProductTerjual }}
                    </p>
                </div>
            </div>
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-md ">
                <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-xs font-medium text-gray-600 ">
                        Jumlah Preorder Selesai
                    </p>
                    <p class="text-lg font-semibold text-gray-700 ">
                        {{ $totalPreorder }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 md:mt-8">
            <div class="bg-white p-4 rounded-lg w-full h-fit mt-8 md:mt-0 shadow-sm">
                <h1 class="text-gray-800 font-semibold mb-4">Produk Terbaru</h1>
                <div class="overflow-y-auto costumscroll  rounded-lg max-h-96">
                    @foreach ($productRecently as $item)
                        <div class="bg-blue-100 rounded-md p-4 mb-4">
                            <div class="flex justify-start items-center gap-4 mb-2">
                                <div class="rounded-full w-16 h-16 bg-cover bg-no-repeat bg-center"
                                    style="background-image: url('{{ asset( $item->fotos->first()->foto) }}')">
                                </div>
                                <div class="">
                                    <h1 class="text-gray-700 text-lg md:text-base lg:text-lg font-semibold">
                                        {{ $item->nama_product }}</h1>
                                    @if ($item->stok <= 0)
                                        <p class="text-gray-700 text-sm" style="color : red">Stok Sedang Kosong</p>
                                    @else
                                        <p class="text-gray-700 text-sm">Stok : {{ $item->stok }}</p>
                                    @endif
                                    <p class="text-gray-700">Harga : {{ $item->harga }}</p>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg w-full h-fit mt-8 md:mt-0 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-gray-800 font-semibold ">5 Produk Penjualan Teratas</h1>
                    <h1 class="text-gray-800 font-semibold ">30 Hari Terakhir</h1>
                    {{-- <a href="" class="text-sm hover:underline"><span>See Details</span></a> --}}
                </div>
                <div class="">
                    <div class="relative overflow-auto max-h-96 costumscroll">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Peringkat
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Produk
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Terjual
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($topSalesProducts as $product)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $loop->iteration }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $product->products->nama_product }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $product->totalJumlah }}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
           
                
                <div class="bg-white p-4 rounded-lg w-full h-fit mt-8 md:mt-0 shadow-md">
                    <h1 class="text-gray-800 font-semibold mb-4">5 Preorder Belum Selesai Terbaru</h1>
                    <div class="overflow-y-auto costumscroll  rounded-lg max-h-80">
                        {{-- card --}}
                        @foreach ($preorderRecently as $index => $preorder)
                            <div class="bg-teal-100 rounded-md p-4 mb-4">
                                <div class="flex justify-start items-center gap-4 mb-2">
                                    <div class="w-10 h-10 ">
                                        @foreach ($foto->where('product_id', $preorder->transaksis->product_id) as $item)
                                            <div class="rounded-full w-10 h-10 bg-no-repeat bg-center"
                                                style="background-image: url('{{ asset('storage/' . $item->first()->foto) }}');
                                            background-size: cover;">
                                            </div>
                                            {{-- <img src="{{ asset('storage/' . $item->foto) }}" alt="" srcset=""
                                                class="rounded-full"> --}}
                                        @endforeach
                                    </div>
                                    <div class="">
                                        <h1 class="text-gray-800 text-lg font-semibold"></h1>
                                        <p class="text-gray-700">Total Order : {{ $preorder->transaksis->jumlah ?? 0 }}</p>
                                    </div>
                                </div>

                                @if (isset($namaPembeli[$index]))
                                    <p class="text-gray-700 font-medium">Nama : {{ $namaPembeli[$index] }}</p>
                                @else
                                    <p class="text-gray-700 font-medium">Nama : Not Available</p>
                                @endif
                                <p class="text-gray-500 text-sm mb-4">Keterangan : {{ $preorder->transaksis->keterangan }}</p>
                                <p class="text-sm text-right">{{ $preorder->created_at }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
        
                <div class="bg-white p-4 rounded-lg w-full h-fit mt-8 md:mt-0 shadow-md">
                    <h1 class="text-gray-800 font-semibold mb-4">Produk Terbaru</h1>
                    <div class="overflow-y-auto costumscroll  rounded-lg max-h-80">
                        {{-- card --}}
                        @foreach ($productRecently as $item)
                            <div class="bg-blue-100 rounded-md p-4 mb-4">
                                <div class="flex justify-start items-center gap-4 mb-2">
                                    <div class="rounded-full w-16 h-16 bg-cover bg-no-repeat bg-center"
                                        style="background-image: url('{{ asset( $item->fotos->first()->foto) }}')">
                                    </div>
                                    <div class="">
                                        <h1 class="text-gray-700 text-lg md:text-base lg:text-lg font-semibold">
                                            {{ $item->nama_product }}</h1>
                                        @if ($item->stok <= 0)
                                            <p class="text-gray-700 text-sm" style="color : red">Stok Sedang Kosong</p>
                                        @else
                                            <p class="text-gray-700 text-sm">Stok : {{ $item->stok }}</p>
                                        @endif
                                        <p class="text-gray-700">Harga : {{ $item->harga }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            <div class="bg-white p-4 rounded-lg w-full h-fit mt-8 md:mt-0 shadow-md">
                <h1 class="text-gray-800 font-semibold mb-4">Produk Stok Kosong</h1>
                <div class="overflow-y-auto costumscroll  rounded-lg max-h-80">
                    {{-- card --}}
                    @foreach ($productZeroStok as $item)
                    @if ($item->stok <= 0)
                        <div class="bg-blue-100 rounded-md p-4 mb-4">
                            <div class="flex justify-start items-center gap-4 mb-2">
                                <div class="rounded-full w-16 h-16 bg-cover bg-no-repeat bg-center"
                                    style="background-image: url('{{ asset( $item->fotos->first()->foto) }}')">
                                </div>
                                <div class="">
                                    <h1 class="text-gray-700 text-lg md:text-base lg:text-lg font-semibold">
                                        {{ $item->nama_product }}</h1>
                                        <p class="text-gray-700 text-sm" style="color : red">Stok Sedang Kosong</p>
                                    
                                        <p class="text-gray-700">Harga : {{ $item->harga }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                    @endforeach
                </div>
            </div>


        </div>
        <script>
            function showLoginSuccessModal() {
                var modal = document.getElementById('loginSuccessModal');
                modal.style.display = 'flex';
                setTimeout(function() {
                    hideLoginSuccessModal();
                }, 2000);
            }

            // Fungsi untuk menyembunyikan modal login berhasil
            function hideLoginSuccessModal() {
                var modal = document.getElementById('loginSuccessModal');
                modal.style.display = 'none';
            }

            // Setelah halaman selesai dimuat, tampilkan pop-up
            document.addEventListener("DOMContentLoaded", function() {
                @if (session('success'))
                    showLoginSuccessModal();

                    var css = document.createElement('style');
                    css.innerHTML = `
                    #loginSuccessModal {
                        animation: slideFromTop 0.5s forwards;
                    }

                    @keyframes slideFromTop {
                        from {
                            transform: translateY(-100%);
                        }
                        to {
                            transform: translateY(50%);
                        }
                    }
                `;
                    document.head.appendChild(css);
                @endif
            });
        
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
