@extends('layout.admin_pages')
@section('title', 'Log Activities')
@section('content')
    <div class="container px-6 pb-6 mx-auto ">
        <p class="text-2xl my-6 font-semibold text-gray-700">Log Activities</p>
        {{-- <p>{{$data}}</p> --}}
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
                                placeholder="Cari Activitas atau Deskripsi">
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
            </div>
        </div>
        <div class="bg-white p-8 shadow-lg rounded-3xl max-w-screen-xl  lg:w-full">
            <div class="overflow-x-auto ">
                <table class=" text-sm text-left table-auto w-full">
                    <thead class="text-xs text-gray-700  bg-gray-100  ">
                        <tr class="">
                            <th scope="col" class="w-1/4 px-4 py-2 whitespace-nowrap">
                                Activity
                            </th>
                            <th scope="col" class=" px-4 py-2 whitespace-nowrap">
                                Actor
                            </th>
                            <th scope="col" class=" px-4 py-2 whitespace-nowrap">
                                Description
                            </th>
                            <th scope="col" class=" px-4 py-2 whitespace-nowrap">
                                DateTime
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < count($data); $i++)
                            <tr
                                class="px-4 py-2 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-">
                                <th scope="row" class="font-medium pl-3 lg:whitespace-nowrap text-sm">
                                    <span class="text-sm">
                                        {{ $data[$i]->event }}
                                    </span>
                                </th>

                                <td cope="row" class="w-10 h-16 px-4 py-2 lg:whitespace-nowrap">
                                    <span>{{ $actor[$i] }}</span>
                                </td>

                                <td cope="row" class="w-10 h-16 px-4 py-2 lg:whitespace-nowrap">
                                    <span>{{ $data[$i]->description }}</span>
                                </td>

                                <td cope="row" class="w-10 h-16 px-4 py-2 lg:whitespace-nowrap">
                                    <span>
                                        {{-- {{ \Carbon\Carbon::parse($data[$i]->c, 'Europe/Lisbon')->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY HH:mm:ss') }} --}}
                                        {{ $datetime[$i]}}
                                    </span>
                                </td>
                            </tr>
                        @endfor


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
