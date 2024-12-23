@extends('layout.admin_pages')
@section('title', 'Modal')
@section('content')
    <div class="container px-6 pb-6 mx-auto">
        <h1 class="text-2xl my-6 font-semibold text-gray-700">Modal</h1>
        <div class="bg-white px-8 py-8 shadow-lg rounded-3xl">
            <form action="{{ route('modal.store') }}" method="post">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="left">
                        <div class="max-w-lg">
                            <div class="flex justify-start items-start flex-col gap-3">
                                <div class="w-full">
                                    <label for="jenis"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis</label>
                                    <select id="jenis" name="jenis"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        @foreach ($data as $items)
                                            <option value="{{ $items->id }}">{{ $items->jenis_modal }}</option>
                                        @endforeach
                                    </select>
                                    @error('jenis')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full">
                                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                                    <input type="text" placeholder="Nama" name="nama" value="{{ old('nama') }}"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                                    @error('nama')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <label for=""
                                        class="block pt-2 text-sm font-medium text-gray-800">Tanggal</label>
                                    <div class="relative max-w-lg mt-2">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="datepicker-format" datepicker datepicker-format="yyyy-mm-dd"
                                            type="text" name="tanggal" value="{{ old('tanggal') }}" required
                                            class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Select date">
                                    </div>
                                    @error('tanggal')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="right">
                        <div class="max-w-lg">
                            <div class="flex justify-start items-start flex-col gap-3">
                                <div class="w-full">
                                    <label for="Penyedia"
                                        class="block mb-2 text-sm font-medium text-gray-700">Penyedia</label>
                                    <input type="text" placeholder="Penyedia" name="penyedia"
                                        value="{{ old('penyedia') }}"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                                    @error('penyedia')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full">
                                    <label for="jumlah"
                                        class="block mb-2 text-sm font-medium text-gray-700">Jumlah</label>
                                    <input type="number" placeholder="Jumlah" name="jumlah" id="jumlah" min="0"
                                        value="{{ old('jumlah') }}"  oninput="this.value = this.value.replace(/^0+(?!$)/, '')" 
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                                    @error('jumlah')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full">
                                    <label for="nominal"
                                        class="block mb-2 text-sm font-medium text-gray-700">Nominal</label>
                                    <input type="number" min="0" placeholder="Nominal" name="nominal"
                                        value="{{ old('nominal') }}" min="0"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        oninput="this.value = this.value.replace(/^0+(?!$)/, '')" />
                                    @error('nominal')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center items-center mt-8">
                    <button type="submit"
                        class="bg-green-400 text-gray-100 px-4 py-2 w-full lg:w-fit rounded-lg hover:bg-green-500 duration-300">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('jenis').addEventListener('change', function() {
            var jenis = this.value;
            // console.log(jenis);
            var jumlah = document.getElementById('jumlah');
            // console.log(jumlah);

            // Ganti 'specificValue' dengan nilai yang Anda inginkan untuk memicu kondisi
            if (jenis == 2) {
                jumlah.value = 1;
                jumlah.readOnly = true;
            } else {
                jumlah.readOnly = false;
                jumlah.value = '';
            }
        });
    </script>

@endsection
