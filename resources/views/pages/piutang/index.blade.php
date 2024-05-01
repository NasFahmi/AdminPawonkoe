@extends('components.layouts.admin_pages')
@section('title', 'Admin Piutang')
@section('content')
    <div class="container px-6 pb-6 mx-auto ">
        <p class="text-2xl my-6 font-semibold text-gray-700">Piutang</p>

        <div
            class="bg-white w-full px-8 py-4 shadow-md rounded-3xl mb-4 flex justify-start items-center max-w-screen-xl lg:w-full">
            <div class="flex justify-start items-start md:items-center flex-col gap-4 w-full lg:flex-row ">
                <form class="flex items-center w-full lg:w-1/2" action="" method="GET">
                    <label for="default-search" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
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
                                placeholder="Cari Jenis Atau Nama">
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
                <div class="flex justify-center items-center flex-col md:flex-row gap-4 w-full md:w-fit ">
                    <a href="{{ route('piutang.create') }}"
                        class="bg-sky-200 px-4 w-full md:w-fit py-2 rounded-3xl flex justify-center items-center gap-1 ">
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
                        <span class="font-semibold text-sky-600 text-sm">Tambah Piutang</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="bg-white p-8 shadow-lg rounded-3xl max-w-screen-xl  lg:w-full">
            <div class="overflow-x-auto ">
                <table class=" text-sm text-left table-auto w-full">
                    <thead class="text-xs text-gray-700  bg-gray-100  ">
                        <tr class="">
                            <th scope="col" class="w-1/4 px-4 py-2 whitespace-nowrap">
                                Nama Toko
                            </th>
                            <th scope="col" class=" px-4 py-2 whitespace-nowrap">
                                Product
                            </th>
                            <th scope="col" class=" px-4 py-2 whitespace-nowrap">
                                Tanggal Disetorkan
                            </th>
                            <th scope="col" class=" px-4 py-2 whitespace-nowrap">
                                Status
                            </th>
                            <th class="whitespace-nowrap px-4 py-2">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $items)
                            <tr
                                class="px-4 py-2 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-">
                                <th scope="row" class=" font-medium pl-3  lg:whitespace-nowrap  text-sm">
                                    <span class="text-sm">
                                        {{ $items->nama_toko }}
                                    </span>
                                </th>

                                <td cope="row" class="w-10 h-16   px-4 py-2 lg:whitespace-nowrap">
                                    @foreach ($piutang->piutang_produk_piutangs->produk_piutangs as $produk)
                                        {{ $produk->nama_produk }}
                                    @endforeach
                                </td>

                                <td cope="row" class="w-10 h-16 px-4 py-2 lg:whitespace-nowrap">
                                    <span>
                                        {{ \Carbon\Carbon::parse($items->tanggal_disetorkan)->locale('ID')->isoFormat('D MMMM YYYY') }}
                                    </span>
                                </td>

                                <td cope="row" class="w-10 h-16  px-4 py-2 lg:whitespace-nowrap">
                                    <span>{{ $items->status }}</span>
                                </td>

                                <td class="w-10 h-16  px-4 py-2  lg:whitespace-nowrap">
                                    <div class="flex justify-center items-center">
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
                                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-3xl shadow w-44 dark:bg-gray-700">
                                        <ul class="text-sm text-gray-700 dark:text-gray-200 rounded-3xl"
                                            aria-labelledby="dropdownDefaultButton">
                                            <li>
                                                @if (auth()->check() && auth()->user()->hasRole('superadmin'))
                                                    <a href="{{ route('beban-kewajibans.edit', $items->id) }}"
                                                        class="block px-4 py-2 hover:bg-gray-100 bg-green-50">
                                                        <div class="flex justify-start items-center gap-2">
                                                            <div class="w-4 h-4 ">
                                                                <svg viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
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

                                            <li>
                                                <a href="{{ route('beban-kewajibans.destroy', $items->id) }}"
                                                    class="block px-4 py-2 hover:bg-sky-100 bg-sky-50">
                                                    <div class="flex justify-start items-center gap-2">
                                                        <div class="w-4 h-4 ">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-trash"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"
                                                                    fill="red" />
                                                                <path
                                                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"
                                                                    fill="red" />
                                                            </svg>
                                                        </div>
                                                        <span class="font-semibold text-red-400 ">Hapus</span>
                                                    </div>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                @if ($data->lastPage() > 1)
                    <div class="mt-4 flex flex-col items-center justify-center">
                        <div class="flex items-center space-x-4">
                            {{ $data->links('pagination::tailwind') }}
                        </div>
                        <div class="mt-2 text-sm text-gray-700">
                            Page {{ $data->currentPage() }} of {{ $data->lastPage() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
