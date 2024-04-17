@extends('layout.admin_pages')
@section('title', 'Admin Product')
@section('content')
    @if ($data->isEmpty())
        <div class="container  px-6 pb-6 mx-auto">
            <h1 class="text-2xl my-6 font-semibold text-gray-700 ">Product</h1>
            <div class="flex justify-center items-center gap-2 md:gap-4 flex-col-reverse md:flex-row mb-4">
                <form class="w-full" method="GET" action="">
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        {{-- @foreach ($data as $product) --}}
                        <!-- Tampilkan informasi produk -->
                        <form action="" method="GET">
                            <input type="search" id="default-search" name="search"
                                class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Search Product... ">
                            <button type="submit"
                                class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 duration-300">Search</button>
                        </form>
                        {{-- @endforeach --}}
                    </div>
                </form>
                <a href="{{ route('products.create') }}"
                    class="text-center focus:outline-none text-white w-full md:w-fit bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-3 me-2 mb duration-300 whitespace-nowrap">
                    Add Product
                </a>
            </div>
            <p>Tidak ada Product</p>
        @else
            <div class="container  px-6 pb-6 mx-auto">
                <h1 class="text-2xl my-6 font-semibold text-gray-700 ">Product</h1>
                <div class="flex justify-center items-center gap-2 md:gap-4 flex-col-reverse md:flex-row mb-4">
                    <form class="w-full" method="GET" action="">
                        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            {{-- @foreach ($data as $product) --}}
                            <!-- Tampilkan informasi produk -->
                            <form action="" method="GET">
                                <input type="search" id="default-search" name="search"
                                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search Product... ">
                                <button type="submit"
                                    class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 duration-300">Search</button>
                            </form>
                            {{-- @endforeach --}}
                        </div>
                    </form>

                    <a href="{{ route('products.create') }}"
                        class="text-center focus:outline-none text-white w-full md:w-fit bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-3 me-2 mb duration-300 whitespace-nowrap">
                        Add Product
                    </a>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 justify-center items-center gap-4">
                    @foreach ($data as $items)
                        @if ($items->tersedia == 1)
                            <div class="card card-compact w-full md:w-60 h-96  bg-base-100 shadow-xl">
                                <figure class="w-full h-96">
                                    <img src="{{ asset($items->fotos->first()->foto) }}" alt="PRODUCT"
                                        class="h-auto w-full" />
                                </figure>
                                <div class="card-body">
                                    <h2 class="card-title line-clamp-1">{{ $items->nama_product }}</h2>
                                    <p class="line-clamp-2">{{ $items->deskripsi }}</p>
                                    <div
                                        class="flex justify-between items-start md:items-center flex-col md:flex-row md:mt-4">
                                        <p class="text-start text-lg font-semibold mb-2 md:mb-0">Rp.
                                            {{ number_format($items->harga, 0, ',', '.') }}</p>
                                        <div class="card-actions justify-end w-full md:w-auto">
                                            <a href="{{ route('products.detail', $items->id) }}"
                                                class="btn btn-primary w-full md:w-auto">Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @if ($totalProduct > 12)
            <div class="mt-4 flex flex-col items-center justify-center">
                <div class="flex items-center space-x-4">
                    {{ $data->links('pagination::tailwind') }}
                </div>
                <div class="mt-2 text-sm text-gray-700">
                    Page {{ $data->currentPage() }} of {{ $data->lastPage() }}
                </div>
            </div>
            @endif
    @endif
@endsection
