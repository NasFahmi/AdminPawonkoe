@extends('layout.admin_pages')
@section('title', 'Admin Transaksi')
@section('content')
    <div class="container  px-6 pb-6 mx-auto">
        <div class="flex gap-4">
            <a href="{{ route('preorders.index') }}" class="flex items-center text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <h1 class="text-2xl my-6 font-semibold text-gray-700 ">Edit PreOrder</h1>
        </div>
        {{-- <p>{{$dataTransaksi}}</p> --}}
        <div class="bg-white px-8 py-8 shadow-lg rounded-3xl">
            <form action="{{ route('preorders.update', $dataTransaksi->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="left">
                        <div class="max-w-lg">
                            <label for="" class="">Product</label>
                            <div class="flex justify-start items-start flex-col gap-3">

                                <div class="w-full">
                                    <label for="product" class="text-sm font-medium text-gray-800">Product</label>
                                    {{-- {{$data}} --}}
                                    <select id="product" name="product" disabled selected
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        @foreach ($data as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $product->id == $dataTransaksi->product_id ? 'selected' : '' }}>
                                                {{ $product->nama_product }}</option>
                                        @endforeach

                                    </select>

                                    <input type="hidden" name="product_id" value="{{ $dataTransaksi->product_id }}">
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
                                    <input type="number" id="jumlah" name="jumlah" readonly oninput="this.value = this.value.replace(/^0+(?!$)/, '')"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="0" required value="{{ $dataTransaksi->jumlah }}">

                                </div>
                                @error('jumlah')
                                    <small class="error" style="color: red">{{ $message }}</small>
                                @enderror
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
                                    @error('total')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
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

                                {{-- Preorder --}}
                                {{-- <p>{{$dataTransaksi}}</p> --}}

                                {{-- <div class="w-full">
                                    <label for="is_dp"
                                        class="block text-sm font-medium  text-gray-800 ">Apakah DP?</label>
                                    <select id="is_dp" name="is_dp" 
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        
                                        <option value="1" {{ $dataTransaksi->preorders->is_DP == 1 ? 'selected' : '' }} >Ya</option>
                                        <option value="0" {{ $dataTransaksi->preorders->is_DP == 0 ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                </div> --}}

                                {{-- <div class="w-full" id="tanggal_dp_container">
                                    <label for="" class="text-sm font-medium text-gray-800">Tanggal Pembayaran DP</label>
                                    <div class="relative max-w-lg">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input datepicker type="text" name="tanggal"
                                        value="{{{ \Carbon\Carbon::parse($dataTransaksi->preorders->tanggal_pembayaran_down_payment)->format('m/d/Y') }}}}"
                                            class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Select date">
                                    </div>
                                </div> --}}

                                <div class="w-full" id="jumlah_dp_container">
                                    <p class="text-sm font-medium text-gray-800">Jumlah Pelunasan</p>
                                    <div class="relative ">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <p>Rp.</p>
                                        </div>
                                        <input type="text" name="jumlah_dp" id="jumlah_dp" readonly
                                            value="{{ $dataTransaksi->preorders->down_payment }}"
                                            class="max-w-4xl bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="0">
                                    </div>
                                    <p class="text-xs italic">Nilai di atas adalah (DP). Jika ingin
                                        melunaskan, edit status menjadi selesai</p>
                                </div>
                                @error('jumlah_dp')
                                    <small class="error" style="color: red">{{ $message }}</small>
                                @enderror

                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="max-w-lg">
                            <Label>Pembeli</Label>
                            <div class="flex justify-start items-start flex-col gap-3">
                                <div class="w-full">
                                    <label for="nama" class="text-sm font-medium text-gray-800">Nama</label>
                                    <input type="text" id="nama" name="nama" readonly
                                        class="  w-full max-w-4xl bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Nama Pembeli" value="{{ $dataTransaksi->pembelis->nama }}">
                                    @error('nama')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <label for="email" class="text-sm font-medium text-gray-800">Email</label>
                                    <input type="email" id="email" name="email" readonly disabled
                                        class="  w-full max-w-4xl bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="pembeli@pembeli.com" value="{{ $dataTransaksi->pembelis->email }}">
                                    @error('email')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <label for="alamat" class="text-sm font-medium text-gray-800">Alamat</label>
                                    <input type="text" id="alamat" name="alamat" readonly disabled
                                        class="  w-full max-w-4xl bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Masukkan alamat Anda"
                                        value="{{ $dataTransaksi->pembelis->alamat }}">
                                    @error('alamat')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <label for="telepon" class="text-sm font-medium text-gray-800">Telepon /
                                        WhatsApp</label>
                                    <input type="tel" id="telepon" name="telepon" readonly 
                                        class="  w-full max-w-4xl bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="081234567890" value="{{ $dataTransaksi->pembelis->no_hp }}">
                                    @error('telepon')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <label for="" class="text-sm font-medium text-gray-800">Keterangan</label>
                                    <textarea readonly 
                                        class="w-full max-w-4xl bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Keterangan" name="keterangan" rows="5">{{ $dataTransaksi->keterangan }}</textarea>
                                </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            let isCompleteRadios = document.getElementsByName('is_complete');
            let jumlahDpInput = document.getElementById('jumlah_dp');
            let totalHargaInput = document.getElementById('total-harga');
            let is_dp = document.getElementById('is_dp');
            let teleponInput = document.getElementById('telepon');

            teleponInput.addEventListener('input', function() {
                let maxLength = 12;
                let enteredValue = this.value;

                if (enteredValue.length > maxLength) {
                    this.value = enteredValue.slice(0, maxLength);
                }
            });
            
            jumlahDpInput.addEventListener('keyup', function(e) {
                jumlahDpInput.value = formatRupiah(this.value);
            });

            totalHargaInput.addEventListener('keyup', function(e) {
                totalHargaInput.value = formatRupiah(this.value);
            });

            isCompleteRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.value === '1') {
                        // When "Selesai" is selected, set jumlah_dp to total_harga
                        jumlahDpInput.value = totalHargaInput.value;
                    } else {
                        // When "Belum Selesai" is selected, reset jumlah_dp
                        jumlahDpInput.value = formatRupiah(0);
                    }
                });
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

            is_dp.addEventListener('change', function() {
                console.log(this.value);
                let tanggalContainer = document.getElementById('tanggal_dp_container');
                let jumlahContainer = document.getElementById('jumlah_dp_container');

                if (this.value === '1') {
                    tanggalContainer.style.display = 'block';
                    jumlahContainer.style.display = 'block';
                } else {
                    tanggalContainer.style.display = 'none';
                    jumlahContainer.style.display = 'none';
                }
            });
        });
    </script>

@endsection
