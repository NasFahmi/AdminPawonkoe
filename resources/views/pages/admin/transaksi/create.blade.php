@extends('components.layouts.admin_pages')
@section('title', 'Admin Transaksi')
@section('content')
    <div class="container  px-6 pb-6 mx-auto">
        <h1 class="text-2xl my-6 font-semibold text-gray-700 ">Tambah Transaksi</h1>
        <div class="bg-white px-8 py-8 shadow-lg rounded-3xl">
            <form action="{{ route('transaksis.store') }}" method="post">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="left">
                        <div class="max-w-lg">
                            <div class="flex justify-start items-start flex-col gap-3">
                                <div class="w-full">
                                    <label for="" class="text-sm font-medium text-gray-800">Tanggal</label>
                                    <div class="relative max-w-lg">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input datepicker type="text" name="tanggal" value="{{ old('tanggal') }}"
                                            class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Select date">

                                    </div>
                                    @error('tanggal')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <label for="product" class="text-sm font-medium text-gray-800">Product</label>
                                    {{-- {{$data}} --}}
                                    <select id="product" name="product"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        @foreach ($data->where('tersedia', 1) as $product)
                                            <option value="{{ $product->id }}">{{ $product->nama_product }}</option>
                                        @endforeach


                                    </select>
                                </div>
                                <div class="w-full">
                                    <label for="methode_pembayaran"
                                        class="block mb-2 text-sm font-medium  text-gray-800 ">Methode Pembayaran</label>
                                    <select id="methode_pembayaran" name="methode_pembayaran"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="1">Transfer</option>
                                        <option value="2">Shopee</option>
                                        <option value="3">Offline</option>
                                        <option value="4">Lainnya</option>
                                    </select>
                                </div>

                            </div>
                            <div class="w-full">
                                <p class="mb-1">Status</p>
                                <div class="flex items-center mb-4">
                                    <input id="radio-btn-1" type="radio" value="1" name="is_complete"
                                        class="w-4 h-4  text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ">
                                    <label for="radio-btn-1" class="ms-2 text-sm font-medium text-gray-900 ">Selesai</label>
                                </div>
                                <div class="flex items-center">
                                    <input checked id="radio-btn-2" type="radio" value="0" name="is_complete"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ">
                                    <label for="radio-btn-2" class="ms-2 text-sm font-medium text-gray-900 ">Belum
                                        Selesai</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="w-full">
                            <label for="jumlah"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah</label>
                            <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}"
                                class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="0">
                            @error('jumlah')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="w-full">
                            <p class="text-sm font-medium text-gray-800 mb-1">Total Harga</p>

                            <div class="relative ">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <p>Rp.</p>
                                </div>
                                <input type="text" name="total" id="total-harga" readonly value="{{ old('total') }}"
                                    class="max-w-4xl bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="0">

                            </div>
                            @error('total')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="w-full">
                            <label for="" class="text-sm font-medium text-gray-800">Keterangan</label>
                            <textarea
                                class="w-full max-w-4xl bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Keterangan" name="keterangan" rows="5"></textarea>
                        </div>


                    </div>
                </div>
                <div class="flex justify-center items-center mt-8">
                    <button type="submit"
                        class="bg-green-400 text-gray-100 px-4 py-2 w-full lg:w-fit rounded-lg hover:bg-green-500 duration-300">Submit</button>
                </div>
        </div>

        </form>
    </div>
    </div>
    <script>
        /* Tanpa Rupiah */
        // let total_harga = document.getElementById('total-harga');


        document.addEventListener('DOMContentLoaded', function() {
            let jumlahInput = document.getElementById('jumlah');
            let totalHargaInput = document.getElementById('total-harga');
            let productSelectedInput = document.getElementById('product');
            let productData = {!! json_encode($data) !!};
            let productDataHistory = {!! json_encode($data) !!};
            console.log(productDataHistory)
            console.log(productData)
            // Function to format the total price with Rupiah
            totalHargaInput.addEventListener('keyup', function(e) {
                totalHargaInput.value = formatRupiah(this.value);
            });
            // Function to calculate and update the total price
            function updateTotalHarga() {
                let jumlah = jumlahInput.value;
                let selectedProductId = productSelectedInput.value;
                console.log(selectedProductId)

                // Find the selected product by ID
                let selectedProduct = productData.find(product => product.id == selectedProductId);

                if (selectedProduct) {
                    let hargaPerItem;

                    // Check if there is a corresponding history product
                    let historyProduct = productDataHistory.find(history => history.product_id ==
                        selectedProductId);
                    // print($historyProduct)

                    if (historyProduct && historyProduct.harga != selectedProduct.harga) {
                        // If there is a history product and the price is different, use the history price
                        hargaPerItem = historyProduct.harga;
                    } else {
                        // Otherwise, use the current product price
                        hargaPerItem = selectedProduct.harga;
                    }

                    // Ensure jumlah is not negative
                    if (jumlah < 0) {
                        jumlah = 0;
                        jumlahInput.value = 0; // Set the input value to 0 if negative
                    }

                    let totalHarga = jumlah * hargaPerItem;
                    totalHargaInput.value = formatTotalHarga(totalHarga);
                } else {
                    // Handle if the product is not found
                    console.error('Product not found');
                }
            }

            function formatTotalHarga(totalHarga) {
                return formatRupiah(totalHarga.toString());
            }

            // Attach the 'input' event listener to the jumlahInput
            jumlahInput.addEventListener('input', updateTotalHarga);
            // formatRupiah(updateTotalHarga,'Rp')
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
