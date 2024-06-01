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
                            </div>
                        </div>
                    </div>

                    <div class="right">
                        <div class="w-full">
                            <label for="jumlahHutang" class="block mb-2 text-sm font-medium text-gray-700">Jumlah
                                Hutang</label>
                            <input type="number" placeholder="Jumlah Hutang" name="jumlahHutang"
                                value="{{ old('jumlahHutang') }}"
                                class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 " />
                            @error('jumlahHutang')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="w-full mt-1">
                            <label for="" class="block pt-2 text-sm font-medium text-gray-800">Tanggal Lunas</label>
                            <div class="relative max-w-lg mt-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input datepicker type="text" name="tanggal_lunas" value="{{ old('tanggal_lunas') }}"
                                    class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select date">
                            </div>
                            @error('tanggal_lunas')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <h1 class="mt-5 mb-2 text-lg font-medium">Cicilan Hutang Awal</h1>
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                    <div class="left">
                        <div class="w-full">
                            <label for="nominal" class="block mb-2 text-sm font-medium text-gray-700">Nominal</label>
                            <input type="number" placeholder="Nominal" name="nominal" value="{{ old('nominal') }}"
                                class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 " />
                            @error('nominal')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="w-full mt-1">
                            <label for="" class="block pt-2 text-sm font-medium text-gray-800">Tenggat Waktu</label>
                            <div class="relative max-w-lg mt-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input datepicker type="text" name="tenggat_waktu" value="{{ old('tenggat_waktu') }}"
                                    class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select date">
                            </div>
                            @error('tanggal')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="right">
                        <!-- Radio buttons -->
                        <div class="w-full">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                            <div class="flex items-center mb-4">
                                <input id="default-radio-1" type="radio" value="1" name="status_cicilan"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2 ">
                                <label for="default-radio-1"
                                    class="text-sm font-medium text-gray-900 ms-2 dark:text-gray-300">Selesai</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="default-radio-2" type="radio" value="0" name="status_cicilan"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2 ">
                                <label for="default-radio-2"
                                    class="text-sm font-medium text-gray-900 ms-2 dark:text-gray-300">Belum Selesai</label>
                            </div>
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
@endsection
