@extends('layout.admin_pages')
@section('title', 'Admin Transaksi')
@section('content')
    <div class="container  px-6 pb-6 mx-auto">
        <h1 class="text-2xl my-6 font-semibold text-gray-700 ">Edit Transaksi</h1>
        {{-- <p>{{$dataTransaksi}}</p> --}}
        <div class="bg-white px-8 py-8 shadow-lg rounded-3xl">
            <form action="{{ route('transaksis.update', $dataTransaksi->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="left">
                        <div class="max-w-lg">
                            <div class="flex justify-start items-s  tart flex-col gap-3">

                                <div class="w-full">
                                    <label for="product" class="text-sm font-medium text-gray-800">Product</label>
                                    {{-- {{$data}} --}}

                                    <select id="product" name="product" disabled
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        @foreach ($data as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $product->id == $dataTransaksi->product_id ? 'selected' : '' }}>
                                                {{ $product->nama_product }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="product" value="{{ $dataTransaksi->product_id }}">

                                </div>
                                <div class="w-full">
                                    <label for="methode_pembayaran"
                                        class="block mb-2 text-sm font-medium  text-gray-800 ">Methode Pembayaran</label>
                                    {{-- <p>{{$dataTransaksi->methode_pembayaran_id}}</p> --}}
                                    <select id="methode_pembayaran" name="methode_pembayaran" disabled
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="1"
                                            {{ $dataTransaksi->methode_pembayaran_id == 1 ? 'selected' : '' }}>Transfer
                                        </option>
                                        <option value="2"
                                            {{ $dataTransaksi->methode_pembayaran_id == 2 ? 'selected' : '' }}>Shopee
                                        </option>
                                        <option value="3"
                                            {{ $dataTransaksi->methode_pembayaran_id == 3 ? 'selected' : '' }}>Offline
                                        </option>
                                        <option value="4"
                                            {{ $dataTransaksi->methode_pembayaran_id == 4 ? 'selected' : '' }}>Lainnya
                                        </option>
                                    </select>

                                </div>
                                <div class="w-full">
                                    <label for="jumlah"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah</label>
                                    <input type="number" id="jumlah" name="jumlah" readonly
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="0" required value="{{ $dataTransaksi->jumlah }}">

                                </div>
                                <div class="w-full">
                                    <p class="text-sm font-medium text-gray-800">Total Harga</p>
                                    <div class="relative mb-6">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <p>Rp.</p>
                                        </div>
                                        <input type="text" name="total" id="total-harga" readonly
                                            class="max-w-4xl bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="0"
                                            value="{{ number_format($dataTransaksi->total_harga, 0, ',', '.') }}">
                                    </div>
                                </div>
                                <div class="w-full">
                                    <p>Status</p>
                                    @if ($dataTransaksi->is_complete == true)
                                        <div class="flex items-center mb-4">
                                            <input checked id="radio-btn-1" type="radio" value="1" name="is_complete"
                                                class="w-4 h-4  text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ">
                                            <label for="radio-btn-1"
                                                class="ms-2 text-sm font-medium text-gray-900 ">Selesai</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="radio-btn-2" type="radio" value="0" name="is_complete"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ">
                                            <label for="radio-btn-2" class="ms-2 text-sm font-medium text-gray-900 ">Belum
                                                Selesai</label>
                                        </div>
                                    @elseif ($dataTransaksi->is_complete == false)
                                        <div class="flex items-center mb-4">
                                            <input id="radio-btn-1" type="radio" value="1" name="is_complete"
                                                class="w-4 h-4  text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ">
                                            <label for="radio-btn-1"
                                                class="ms-2 text-sm font-medium text-gray-900 ">Selesai</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input checked id="radio-btn-2" type="radio" value="0" name="is_complete"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ">
                                            <label for="radio-btn-2" class="ms-2 text-sm font-medium text-gray-900 ">Belum
                                                Selesai</label>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="right">
                        <div id="default-carousel" class="relative w-full " data-carousel="slide">
                            <!-- Carousel wrapper -->
                            <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                                @if ($datafotoProduct->fotos->count() > 1)
                                    <!-- Gunakan carousel jika ada lebih dari satu foto -->
                                    @foreach ($datafotoProduct->fotos as $index => $foto)
                                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                            <img src="{{ asset('storage/' . $foto->foto) }}"
                                                class="absolute z-10 block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 object-cover"
                                                alt="...">
                                        </div>
                                    @endforeach
                                @elseif ($datafotoProduct->fotos->count() === 1)
                                    <!-- Tampilkan gambar langsung jika hanya ada satu foto -->
                                    <div class="block w-full h-full">
                                        <img src="{{ asset('storage/' . $datafotoProduct->fotos[0]->foto) }}"
                                            class="w-full h-full object-cover" alt="...">
                                    </div>
                                @else
                                    <!-- Tambahkan placeholder atau pesan jika tidak ada foto -->
                                    <div class="flex items-center justify-center w-full h-full">
                                        <span class="text-gray-500">No photos available</span>
                                    </div>
                                @endif
                            </div>



                            <!-- Slider controls -->
                            @if ($datafotoProduct->fotos->count() > 1)
                                <button type="button"
                                    class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                                    data-carousel-prev>
                                    <span
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M5 1 1 5l4 4" />
                                        </svg>
                                        <span class="sr-only">Previous</span>
                                    </span>
                                </button>

                                <button type="button"
                                    class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                                    data-carousel-next>
                                    <span
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 9 4-4-4-4" />
                                        </svg>
                                        <span class="sr-only">Next</span>
                                    </span>
                                </button>
                            @endif
                        </div>

                    </div>

                </div>
        </div>
        <div class="flex justify-center items-center mt-8">
            <button type="submit"
                class="bg-green-400 text-gray-100 px-4 py-2 w-full lg:w-fit rounded-lg hover:bg-green-500 duration-300">Simpan</button>
        </div>
        </form>
    </div>
    </div>
    <script>
        /* Tanpa Rupiah */
        let total_harga = document.getElementById('total-harga');
        total_harga.addEventListener('keyup', function(e) {
            total_harga.value = formatRupiah(this.value);
        });


        /* Fungsi */
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    </script>
@endsection
