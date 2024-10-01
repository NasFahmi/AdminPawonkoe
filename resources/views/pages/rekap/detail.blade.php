@extends('layout.admin_pages')
@section('title', 'Rekap Keuangan')
@section('content')
    <div class="container px-6 pb-6 mx-auto ">
        <div class="flex gap-4">
            <a href="{{ route('rekap.index') }}" class="flex items-center text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <h1 class="text-base md:text-2xl my-6 font-semibold text-gray-700 ">Rekap Keuangan</h1>
        </div>


        <!-- component -->
        <div
            class="flex items-center justify-start w-full max-w-screen-xl px-8 py-4 mb-4 bg-white shadow-md rounded-3xl lg:w-full">
            <div class="flex flex-col items-start justify-start w-full gap-4 md:items-center lg:flex-row ">
                <form class="flex items-center w-full lg:w-1/2" action="" method="GET">
                    <label for="default-search" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 flex items-center pointer-events-none start-0 ps-3">
                            <svg class="w-5 h-5 text-gray-500" aria-hidden="true" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M12 19L12 11" stroke="#6b7280" stroke-width="4" stroke-linecap="round"></path>
                                    <path d="M7 19L7 15" stroke="#6b7280" stroke-width="4" stroke-linecap="round"></path>
                                    <path d="M17 19V6" stroke="#6b7280" stroke-width="4" stroke-linecap="round"></path>
                                </g>
                            </svg>
                        </div>
                        <form action="" method="GET">
                            <input type="search" id="default-search" name="search"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Cari Sumber">
                    </div>
                    <button type="submit"
                        class="p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Search</span>
                    </button>
                </form>
                </form>
                <div class="flex flex-col items-center justify-center w-full gap-4 md:flex-row md:w-fit ">
                    <a href="{{ route('rekap.filter', 'masuk') }}"
                        class="flex items-center justify-center w-full gap-1 px-4 py-2 md:w-fit rounded-3xl {{ isset($type) && $type == 'masuk' ? 'border-2 border-green-600' : 'bg-green-300' }} 
                        hover:bg-green-600 hover:text-white hover:border-green-600 transition-colors duration-300">
                        <span class="text-sm font-semibold  {{ isset($type) && $type == 'masuk' ? 'text-green-600' : '' }} hover:text-white">Uang Masuk</span>
                    </a>

                    <a href="{{ route('rekap.filter', 'keluar') }}"
                        class="flex items-center justify-center w-full gap-1 px-4 py-2 md:w-fit rounded-3xl {{ isset($type) && $type == 'keluar' ? 'border-2 border-red-600' : 'bg-red-300'}}
                                hover:bg-red-600 hover:text-white hover:border-red-600 transition-colors duration-300">
                        <span class="text-sm font-semibold {{ isset($type) && $type == 'keluar' ? 'text-red-600' : '' }} hover:text-white">Uang Keluar</span>
                    </a>

                    @if (auth()->check() && (auth()->user()->hasRole('superadmin')))
                        <a href="{{ route('cetak.rekap') }}"
                            class="flex items-center justify-center w-full gap-1 px-4 py-2 bg-orange-100 md:w-fit rounded-3xl ">
                            <div class="w-4 h-4">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M7 17H5C3.89543 17 3 16.1046 3 15V11C3 9.34315 4.34315 8 6 8H7M7 17V14H17V17M7 17V18C7 19.1046 7.89543 20 9 20H15C16.1046 20 17 19.1046 17 18V17M17 17H19C20.1046 17 21 16.1046 21 15V11C21 9.34315 19.6569 8 18 8H17M7 8V6C7 4.89543 7.89543 4 9 4H15C16.1046 4 17 4.89543 17 6V8M7 8H17M15 11H17"
                                            stroke="#d97706" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                    </g>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-orange-600">Cetak Rekapan</span>
                        </a>
                    @endif

                    <div class="flex items-center justify-center ">
                        <div class="w-6 h-6 cursor-pointer" data-dropdown-toggle="dropdown">
                            <button>Filter</button>
                        </div>
                    </div>
                    <div id="dropdown"
                        class="z-10 hidden bg-white divide-y divide-gray-100 shadow rounded-3xl w-44 dark:bg-gray-700">
                        <ul class="text-sm text-gray-700 dark:text-gray-200 rounded-3xl"
                            aria-labelledby="dropdownDefaultButton">
                            <li>
                                <a href="#"
                                class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">Januari</span>
                                </a>
                                <a class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">Februari</span>
                                </a>
                                <a class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">Maret</span>
                                </a>
                                <a class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">April</span>
                                </a>
                                <a class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">Mei</span>
                                </a>
                                <a class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">Juni</span>
                                </a>
                                <a class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">Juli</span>
                                </a>
                                <a class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">Agustus</span>
                                </a>
                                <a class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">September</span>
                                </a>
                                <a class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">Oktober</span>
                                </a>
                                <a class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">November</span>
                                </a>
                                <a class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                    <span class="font-semibold text-sky-400 ">Desember</span>
                                </a>
                            </li>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 shadow-lg rounded-3xl max-w-screen-xl  lg:w-full">
            <div class="overflow-x-auto ">
                <table class=" text-sm text-left table-auto w-full">
                    <thead class="text-xs text-gray-700  bg-gray-100  ">
                        <tr class="">
                            <th scope="col" class="w-1/4 px-4 py-2 whitespace-nowrap">
                                Tanggal
                            </th>
                            <th scope="col" class=" px-4 py-2 whitespace-nowrap">
                                Sumber
                            </th>
                            <th scope="col" class=" px-4 py-2 whitespace-nowrap">
                                Jumlah
                            </th>
                            <th scope="col" class=" px-4 py-2 whitespace-nowrap">
                                Keterangan
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $items)
                            <tr
                                class="px-4 py-2 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-">
                               
                                    
                                <th scope="row" class=" font-medium pl-3  lg:whitespace-nowrap  text-sm">
                                    <span class="text-sm">
                                        {{ \Carbon\Carbon::parse($items->tanggal)->locale('ID')->isoFormat('D MMMM YYYY') }} 
                                    </span>
                                    </th>
                                    

                                <td cope="row" class="w-10 h-16   px-4 py-2 lg:whitespace-nowrap">
                                    <span>{{ $items->sumber }}</span> 
                                </td>


                                <td cope="row" class="w-10 h-16  px-4 py-2 lg:whitespace-nowrap">
                                     <span>Rp. {{ number_format($items->jumlah, 0, ',', '.') }}
                                    </span> 
                                </td>

                                <td cope="row" class="w-10 h-16 px-4 py-2 lg:whitespace-nowrap">
                                    <span>
                                        {{ $items->keterangan }}
                                    </span>
                                </td>
                            </tr>
                          
                        @endforeach

                    </tbody>
                </table>
                {{-- @if ($data->lastPage() > 1)
    <div class="mt-4 flex flex-col items-center justify-center">
        <div class="flex items-center space-x-4">
            {{ $data->links('pagination::tailwind') }} <!-- Menampilkan navigasi paginasi -->
        </div>
        <div class="mt-2 text-sm text-gray-700">
            Page {{ $data->currentPage() }} of {{ $data->lastPage() }} <!-- Menampilkan info halaman -->
        </div>
    </div>
@endif --}}

            </div>
        </div>
    </div>
@endsection
