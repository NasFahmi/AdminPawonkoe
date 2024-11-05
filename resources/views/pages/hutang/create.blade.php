@extends('layout.admin_pages')
@section('title', 'Admin Hutang')
@section('content')
    <div class="container px-6 pb-6 mx-auto">
        <h1 class="my-6 text-2xl font-semibold text-gray-700">Tambah Hutang</h1>
        <div class="px-8 py-8 bg-white shadow-lg rounded-3xl">
            <form action="{{ route('hutang.store') }}" method="post">
                @csrf
                <h1 class="mb-2 text-lg font-medium">Informasi Hutang</h1>
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                    <div class="left">
                        <div class="max-w-lg">
                            <div class="flex flex-col items-start justify-start gap-3">
                                <div class="w-full">
                                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                                    <input type="text" placeholder="nama" name="nama" value="{{ old('nama') }}"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 " />
                                    @error('nama')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full">
                                    <label for="message"
                                        class="block mb-2 text-sm font-medium text-gray-900">Catatan</label>
                                    <textarea id="message" rows="4"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        placeholder="Catatan" name="catatan">{{ old('catatan') }}</textarea>
                                    @error('catatan')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                                <input type="hidden" name="status" value="{{ $status }}">

                                <div id="cicilanAwal" class="{{ $status == 0 ? '' : 'hidden' }} w-full">
                                    <h1 class="text-lg font-medium text-gray-800">Cicilan Hutang Awal</h1>
                                    <p class="text-gray-700 mb-2 font-normal text-xs">Isi nominal dengan jumlah cicilan
                                        pertama Anda.</p>
                                    <div class="w-full">
                                        <label for="nominal"
                                            class="block mb-2 text-sm font-medium text-gray-700">Nominal</label>
                                        <input type="number" placeholder="Nominal" name="nominal"
                                            value="{{ old('nominal') }}" min="0"
                                            oninput="this.value = this.value.replace(/^0+(?!$)/, '')"
                                            class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                                        @error('nominal')
                                            <small class="error" style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="right">
                        <div class="w-full">
                            <label for="jumlahHutang" class="block mb-2 text-sm font-medium text-gray-700">Jumlah
                                Hutang</label>
                            <input type="number" placeholder="Jumlah Hutang" name="jumlahHutang" min="0"
                                value="{{ old('jumlahHutang') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 " />
                            @error('jumlahHutang')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="w-full " id="tanggalLunas">
                            <label for="" class="block pt-2 text-sm font-medium text-gray-800">Tanggal Lunas</label>
                            <div class="relative max-w-lg mt-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input id="datepicker-format" datepicker datepicker-format="yyyy-mm-dd" type="text"
                                    name="tanggal_lunas" value="{{ old('tanggal_lunas') }}"
                                    class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select date">
                            </div>
                            <p class="text-xs italic">*lewati jika hutang belum lunas</p>
                            @error('tanggal_lunas')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="w-full " id="tenggatWaktu">
                            <label for="" class="block pt-2 text-sm font-medium text-gray-800">Tenggat
                                Waktu</label>
                            <div class="relative max-w-lg mt-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input id="datepicker-format" datepicker datepicker-format="yyyy-mm-dd" type="text"
                                    name="tenggat_waktu" value="{{ old('tenggat_waktu') }}"
                                    class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select date">
                            </div>
                            @error('tenggat_waktu')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                            <p class="text-xs italic">*lewati jika hutang sudah lunas</p>
                        </div>

                    </div>
                </div>

                <div class="flex items-center justify-center mt-8">
                    <button type="submit"
                        class="w-full px-4 py-2 text-gray-100 duration-300 bg-green-400 rounded-lg lg:w-fit hover:bg-green-500">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get elements
            const tanggalLunasField = document.getElementById('tanggalLunas');
            const tenggatWaktuField = document.getElementById('tenggatWaktu');
            const cicilanAwalField = document.getElementById('cicilanAwal');

            // Function to get URL parameters
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                const results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }

            // Get status from URL
            const status = getUrlParameter('status');

            // Function to toggle field visibility
            function toggleFieldsVisibility(isStatus0) {
                if (isStatus0) {
                    // If status=0 (hutang belum lunas)
                    tanggalLunasField.style.display = 'none';
                    tenggatWaktuField.style.display = 'block';
                    cicilanAwalField.classList.remove('hidden');
                } else {
                    // If status=1 (hutang sudah lunas)
                    tanggalLunasField.style.display = 'block';
                    tenggatWaktuField.style.display = 'none';
                    cicilanAwalField.classList.add('hidden');
                }
            }

            toggleFieldsVisibility(status === '0');

           
            const statusRadios = document.querySelectorAll('input[name="status"]');
            statusRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    toggleFieldsVisibility(this.value === '0');
                });
            });
        });
    </script>

@endsection
