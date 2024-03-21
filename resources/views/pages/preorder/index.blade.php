@extends('layout.admin_pages')
@section('title', 'Admin Preorder')
@section('content')
    <div class="container px-6 pb-6 mx-auto ">
        <p class="text-2xl my-6 font-semibold text-gray-700">Transaksi Preorder</p>

        <div
            class="bg-white w-full px-8 py-4 shadow-md rounded-3xl mb-4 flex justify-start items-center max-w-screen-xl lg:w-full">
            <div class="flex justify-start items-start md:items-center flex-col gap-4 w-full lg:flex-row ">
                <form class="flex items-center w-full lg:w-1/2" action="" method="GET">
                    <label for="simple-search" class="sr-only">Search</label>
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
                            <input type="text" id="simple-search" name="search"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Cari Product atau Pembeli">
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

                <a href="{{ route('preorders.create') }}"
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
                    <span class="font-semibold text-sky-600 text-sm">Tambah Preorder</span>
                </a>

            </div>
        </div>

        <div class="grid grid-cols-3 gap-8">
            <div class="flex justify-start items-start flex-col col-span-2 gap-4">
                {{-- card --}}
                {{-- {{$data->id}} --}}
                @foreach ($data as $preorder)
                    <div class="w-full bg-white flex items-start justify-between p-8 rounded-3xl shadow-lg">
                        <div class="kiri">
                            <p class="text-xl font-medium text-gray-800 mb-1">{{ $preorder->pembelis->nama }}</p>

                            <div class="grid grid-cols-2 gap-4 mb-1">
                                <p class=" text-gray-400">Product</p>
                                <p class="font-medium text-gray-800 whitespace-nowrap">: {{ $preorder->products->nama_product }}</p>

                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-1">
                                <p class=" text-gray-400">Jumlah</p>
                                <p class="font-medium text-gray-800">: {{ $preorder->jumlah }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-1">
                                <p class=" text-gray-400">Total Harga</p>
                                <p class="font-medium text-gray-800">:
                                    {{ number_format($preorder->total_harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-1">
                                <p class=" text-gray-400">Down Payment</p>
                                <p class="font-medium text-gray-800">:
                                    {{ number_format($preorder->preorders->down_payment, 0, ',', '.') }}</p>
                            </div>

                        </div>
                        <div class="kanan flex justify-center items-center gap-4">
                            <a href="{{ route('preorders.detail', $preorder->id) }}"
                                class="flex justify-center items-center gap-1 cursor-pointer ">
                                <div class="w-4 h-4 ">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
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
                                <p class="text-sm text-sky-500 font-medium ">Details</p>
                            </a>
                            @if (auth()->check() &&
                                    (auth()->user()->hasRole('superadmin') ||
                                        auth()->user()->can('edit-preorder')))
                                @if ($preorder->is_complete == 0)
                                    <a href="{{ route('preorders.edit', $preorder->id) }}"
                                        class="flex justify-center items-center gap-1 cursor-pointer">
                                        <div class="w-4 h-4 ">
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                        <p class="text-sm text-lime-500 font-medium">Edit</p>
                                    </a>
                                @endif
                            @endif

                            <div>
                                @if ($preorder->is_complete == true)
                                    <p class="text-md col-span-3 text-green-400 font-medium whitespace-nowrap">Selesai</p>
                                @elseif ($preorder->is_complete == false)
                                    <p class="text-md col-span-3 text-red-400 font-medium whitespace-nowrap">Belum Selesai</p>
                                @endif
                            </div>

                            <div id="popup-modal" tabindex="-1"
                                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative p-4 w-full max-w-md max-h-full">
                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                        <button type="button"
                                            class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                            data-modal-hide="popup-modal">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                        <div class="p-4 md:p-5 text-center">
                                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                                Are you sure you want
                                                to delete this PreOrder?</h3>
                                            <button data-modal-hide="popup-modal" type="submit" id="submitDeleted"
                                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                                                Yes, I'm sure
                                            </button>
                                            <button data-modal-hide="popup-modal" type="button"
                                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No,
                                                Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>

                        </div>
                    </div>
                    {{-- <p>{{$preorder->id}}</p> --}}
                @endforeach



            </div>
            {{-- informasi --}}
            <div class= "h-fit bg-white col-span-1 rounded-3xl shadow-lg p-8">
                <p class="text-gray-500 mb-2 font-medium">Informasi</p>
                <div class="mb-2">
                    <p class="text-gray-600 text-sm">Preorder Belum Selesai</p>
                    <p class="text-gray-800 text-xl font-medium ">{{ $totalPreorder }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600 text-sm">Total Saldo Preorder Terbayar</p>
                    <p class="text-gray-800 text-xl font-medium ">Rp.{{ number_format($totalDP, 0, ',', '.') }}</p>
                    <p class="text-xs italic text-gray-400">(Total dari Preorder yang Sudah Membayar DP)</p>
                </div>
                <div class="">
                    <p class="text-gray-600 text-sm">Total Saldo Prorder Belum Terbayar</p>
                    <p class="text-gray-800 text-xl font-medium ">Rp.{{ number_format($totalDPBelumLunas, 0, ',', '.') }}
                    </p>
                    <p class="text-xs italic text-gray-400">(Total Saldo yang Masih Dibutuhkan dari Transaksi Preorder yang
                        Belum Lunas)</p>
                </div>
            </div>
            
        </div>
        @if ($totalPreorder >= 7)
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
    <script>
        let submitDelete = document.getElementById('submitDeleted')
        submitDelete.addEventListener('click', function() {
            return true;
        })
    </script>
@endsection
