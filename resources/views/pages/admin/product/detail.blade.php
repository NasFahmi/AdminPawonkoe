@extends('layout.admin_pages')
@section('title', $data->nama_product)
@section('content')
    <div class=" px-4 md:px-20 w-full  justify-center items-center flex-col bg-white py-10">
        <div class="flex justify-center items-start gap-10 flex-col md:flex-row">
            <div id="default-carousel" class="relative w-full md:w-1/2" data-carousel="slide">
                <!-- Carousel wrapper -->
                <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                    @if ($data->fotos->count() > 1)
                        <!-- Gunakan carousel jika ada lebih dari satu foto -->
                        @foreach ($data->fotos as $index => $foto)
                            <div class="hidden duration-700 ease-in-out bg-cover" data-carousel-item>
                                <img src="{{ asset('storage/' . $foto->foto) }}"
                                    class="absolute image-full z-10 block -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 cover-image"
                                    alt="...">
                            </div>
                        @endforeach
                    @elseif ($data->fotos->count() === 1)
                        <!-- Tampilkan gambar langsung jika hanya ada satu foto -->
                        <div class="block w-full h-full">
                            <img src="{{ asset('storage/' . $data->fotos[0]->foto) }}" class="w-full h-full object-cover"
                                alt="...">
                        </div>
                    @else
                        <!-- Tambahkan placeholder atau pesan jika tidak ada foto -->
                        <div class="flex items-center justify-center w-full h-full">
                            <span class="text-gray-500">No photos available</span>
                        </div>
                    @endif
                </div>



                <!-- Slider controls -->
                @if ($data->fotos->count() > 1)
                    <button type="button"
                        class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                        data-carousel-prev>
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 1 1 5l4 4" />
                            </svg>
                            <span class="sr-only">Previous</span>
                        </span>
                    </button>

                    <button type="button"
                        class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                        data-carousel-next>
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="sr-only">Next</span>
                        </span>
                    </button>
                @endif
            </div>



            <div class="w-full md:w-1/2">
                <h1 class="text-3xl font-semibold mb-2">{{ $data->nama_product }}</h1>
                <h1 class="text-2xl font-semibold mb-1">Rp. {{ number_format($data->harga, 0, ',', '.') }}</h1>
                <p class="text-lg">{{ $data->deskripsi }}</p>

                <h1 class="font-semibold">Varian : </h1>
                @foreach ($data->varians as $varian)
                    <li class="text-lg">{{ $varian->jenis_varian }}</li>
                @endforeach
                @if ($data->stok <= 0)
                    <h1 class="font-semibold" style="color: red">Stok: Sedang Kosong</h1>
                @else
                    <h1 class="font-semibold">Stok: {{ $data->stok }}</h1>
                @endif

                <div class="mt-2">
                    <h1 class="text-2xl font-medium">Product Spesifikasi</h1>
                    <p class="text-lg">{!! nl2br(e($data->spesifikasi_product)) !!}</p>
                </div>


                <a href="{{ $data->link_shopee }}"
                    class="flex items-center justify-center w-full md:w-32 px-4 py-2 mt-4 text-sm font-medium text-white transition-colors duration-150 bg-orange-500 border border-transparent rounded-lg active:bg-orange-600 hover:bg-orange-700">
                    <img src="{{ asset('assets/images/shopee.png') }}" alt="Shopee Logo" class="w-8 h-8 mr-2">
                    <span>Shopee</span>
                </a>
            </div>
        </div>
        <div class="flex justify-center md:justify-start gap-4 mb-20 items-center mt-4 ">
            @if (auth()->check() &&
                    (auth()->user()->hasRole('superadmin') ||
                        auth()->user()->can('edit-product')))
                <a href="{{ route('products.edit', $data->id) }}"
                    class="flex items-center justify-center w-32 h-12 px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700">
                    <span>Edit</span>
                </a>
                <form action="{{ route('products.destroy', $data->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal"
                        class="flex items-center justify-center w-32 h-12 px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-700 hover:bg-red-800">
                        <span>Hapus</span>
                    </button>
            @endif
            <div id="popup-modal" tabindex="-1"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <button type="button"
                            class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="popup-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <div class="p-4 md:p-5 text-center">
                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want
                                to delete this product?</h3>
                            <button data-modal-hide="popup-modal" type="submit" id="submitDeleted"
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                                Yes, I'm sure
                            </button>
                            <button data-modal-hide="popup-modal" type="button"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No,
                                cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            </form>
        </div>
    </div>
    <script>
        let submitDelete = document.getElementById('submitDeleted')
        submitDelete.addEventListener('click', function() {
            return true;
        })
    </script>
@endsection
