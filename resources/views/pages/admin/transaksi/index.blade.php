@extends('layout.admin_pages')
@section('title', 'Admin Transaksi')
@section('content')
    <div class="container px-6 pb-6 mx-auto ">
        <p class="my-6 text-2xl font-semibold text-gray-700">Transaksi</p>
        {{-- <p>{{$data}}</p> --}}
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
                                placeholder="Cari Product Atau Tanggal">
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
                    <a href="{{ route('transaksis.create') }}"
                        class="flex items-center justify-center w-full gap-1 px-4 py-2 bg-sky-200 md:w-fit rounded-3xl ">
                        <div class="w-4 h-4">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M6 12H18M12 6V18" stroke="#0284c7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </g>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-sky-600">Tambah Transaksi</span>
                    </a>
                    @if (auth()->check() && (auth()->user()->hasRole('superadmin') || auth()->user()->can('cetak-transaksi')))
                        <a href="{{ route('cetak.transaksi') }}"
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
                            <span class="text-sm font-semibold text-orange-600">Cetak Transaksi</span>
                        </a>
                    @endif

                </div>

            </div>

        </div>
        <div class="max-w-screen-xl p-8 bg-white shadow-lg rounded-3xl lg:w-full">
            <div class="overflow-x-auto ">
                <table class="w-full text-sm text-left table-auto ">
                    <thead class="text-xs text-gray-700 bg-gray-100 ">
                        <tr class="">
                            <th scope="col" class="w-1/4 px-4 py-2 whitespace-nowrap">
                                Product
                            </th>
                            <th scope="col" class="px-4 py-2 whitespace-nowrap">
                                Harga
                            </th>
                            <th scope="col" class="px-4 py-2 whitespace-nowrap">
                                Tanggal
                            </th>
                            <th scope="col" class="px-4 py-2 whitespace-nowrap">
                                Status
                            </th>
                            <th class="px-4 py-2 whitespace-nowrap">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $items)
                            <tr
                                class="px-4 py-2 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-">
                                @foreach ($items->history_product_transaksis as $history_product)
                                    <th scope="row" class="pl-3 text-sm font-medium lg:whitespace-nowrap">
                                        <span class="text-sm">
                                            {{ $history_product->history_product->nama_product }}

                                        </span>
                                    </th>

                                    <td cope="row" class="w-10 h-16 px-4 py-2 lg:whitespace-nowrap">
                                        <span>Rp.
                                            {{ number_format($history_product->history_product->harga, 0, ',', '.') }}</span>
                                    </td>
                                @endforeach


                                <td cope="row" class="w-10 h-16 px-4 py-2 lg:whitespace-nowrap">
                                    <span>{{ $items->tanggal }}</span>
                                </td>
                                <td cope="row" class="w-10 h-16 px-4 py-2 lg:whitespace-nowrap">
                                    @if ($items->is_complete == true)
                                        <div
                                            class="flex items-center justify-center px-4 py-2 bg-green-200 w-fit h-fit rounded-3xl">
                                            <span class="font-semibold text-green-500">Selesai</span>
                                        </div>
                                    @elseif ($items->is_complete == false)
                                        <div
                                            class="flex items-center justify-center px-4 py-2 bg-red-200 w-fit h-fit rounded-3xl whitespace-nowrap">
                                            <span class="font-semibold text-red-500 whitespace-nowrap">Belum Selesai</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="w-10 h-16 px-4 py-2 lg:whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <div class="w-6 h-6 cursor-pointer"
                                            data-dropdown-toggle="dropdown{{ $loop->iteration }}">
                                            <svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"
                                                transform="rotate(90)">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <g id="Kebab-Menu" stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd">
                                                        <rect id="Container" x="0" y="0" width="24" height="24">
                                                        </rect>
                                                        <path
                                                            d="M12,6 C12.5522847,6 13,5.55228475 13,5 C13,4.44771525 12.5522847,4 12,4 C11.4477153,4 11,4.44771525 11,5 C11,5.55228475 11.4477153,6 12,6 Z"
                                                            id="shape-03" stroke="#94a3b8" stroke-width="2"
                                                            stroke-linecap="round" stroke-dasharray="0,0"> </path>
                                                        <path
                                                            d="M12,13 C12.5522847,13 13,12.5522847 13,12 C13,11.4477153 12.5522847,11 12,11 C11.4477153,11 11,11.4477153 11,12 C11,12.5522847 11.4477153,13 12,13 Z"
                                                            id="shape-03" stroke="#94a3b8" stroke-width="2"
                                                            stroke-linecap="round" stroke-dasharray="0,0"> </path>
                                                        <path
                                                            d="M12,20 C12.5522847,20 13,19.5522847 13,19 C13,18.4477153 12.5522847,18 12,18 C11.4477153,18 11,18.4477153 11,19 C11,19.5522847 11.4477153,20 12,20 Z"
                                                            id="shape-03" stroke="#94a3b8" stroke-width="2"
                                                            stroke-linecap="round" stroke-dasharray="0,0"> </path>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                    </div>
                                    <div id="dropdown{{ $loop->iteration }}"
                                        class="z-10 hidden bg-white divide-y divide-gray-100 shadow rounded-3xl w-44 dark:bg-gray-700">
                                        <ul class="text-sm text-gray-700 dark:text-gray-200 rounded-3xl"
                                            aria-labelledby="dropdownDefaultButton">
                                            <li>
                                                <a href="{{ route('transaksis.detail', $items->id) }}"
                                                    class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                                    <div class="flex items-center justify-start gap-2">
                                                        <div class="w-4 h-4 ">
                                                            <svg viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                                    stroke-linejoin="round"></g>
                                                                <g id="SVGRepo_iconCarrier">
                                                                    <path
                                                                        d="M9.75 12C9.75 10.7574 10.7574 9.75 12 9.75C13.2426 9.75 14.25 10.7574 14.25 12C14.25 13.2426 13.2426 14.25 12 14.25C10.7574 14.25 9.75 13.2426 9.75 12Z"
                                                                        fill="#0ea5e9"></path>
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M2 12C2 13.6394 2.42496 14.1915 3.27489 15.2957C4.97196 17.5004 7.81811 20 12 20C16.1819 20 19.028 17.5004 20.7251 15.2957C21.575 14.1915 22 13.6394 22 12C22 10.3606 21.575 9.80853 20.7251 8.70433C19.028 6.49956 16.1819 4 12 4C7.81811 4 4.97196 6.49956 3.27489 8.70433C2.42496 9.80853 2 10.3606 2 12ZM12 8.25C9.92893 8.25 8.25 9.92893 8.25 12C8.25 14.0711 9.92893 15.75 12 15.75C14.0711 15.75 15.75 14.0711 15.75 12C15.75 9.92893 14.0711 8.25 12 8.25Z"
                                                                        fill="#0ea5e9"></path>
                                                                </g>
                                                            </svg>
                                                        </div>
                                                        <span class="font-semibold text-sky-400 ">Details</span>
                                                    </div>
                                                </a>
                                            </li>

                                            @if ($items->is_complete == 0)
                                                <li>
                                                    @if (auth()->check() && (auth()->user()->hasRole('superadmin') || auth()->user()->can('edit-transaksi')))
                                                        <a href="{{ route('transaksis.edit', $items->id) }}"
                                                            class="block px-4 py-2 hover:bg-gray-100 bg-green-50">
                                                            <div class="flex items-center justify-start gap-2">
                                                                <div class="w-4 h-4 ">
                                                                    <svg viewBox="0 0 24 24" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                                        <g id="SVGRepo_tracerCarrier"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></g>
                                                                        <g id="SVGRepo_iconCarrier">
                                                                            <path
                                                                                d="M11.4001 18.1612L11.4001 18.1612L18.796 10.7653C17.7894 10.3464 16.5972 9.6582 15.4697 8.53068C14.342 7.40298 13.6537 6.21058 13.2348 5.2039L5.83882 12.5999L5.83879 12.5999C5.26166 13.1771 4.97307 13.4657 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L7.47918 20.5844C8.25351 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5343 19.0269 10.823 18.7383 11.4001 18.1612Z"
                                                                                fill="#4ade80"></path>
                                                                            <path
                                                                                d="M20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178L14.3999 4.03882C14.4121 4.0755 14.4246 4.11268 14.4377 4.15035C14.7628 5.0875 15.3763 6.31601 16.5303 7.47002C17.6843 8.62403 18.9128 9.23749 19.85 9.56262C19.8875 9.57563 19.9245 9.58817 19.961 9.60026L20.8482 8.71306Z"
                                                                                fill="#4ade80"></path>
                                                                        </g>
                                                                    </svg>
                                                                </div>
                                                                <span class="font-semibold text-green-400">Edit</span>
                                                            </div>
                                                        </a>
                                                    @endif
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="flex flex-col items-center justify-center mt-4">
                    <div class="flex items-center space-x-4">
                        {{ $data->links('pagination::tailwind') }}
                    </div>
                    <div class="mt-2 text-sm text-gray-700">
                        Page {{ $data->currentPage() }} of {{ $data->lastPage() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
