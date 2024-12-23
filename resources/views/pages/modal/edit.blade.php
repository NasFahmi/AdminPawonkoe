@extends('layout.admin_pages')
@section('title', 'modal')
@section('content')
    <div class="container px-6 pb-6 mx-auto">
        <h1 class="text-2xl my-6 font-semibold text-gray-700">Edit modal</h1>
        <div class="bg-white px-8 py-8 shadow-lg rounded-3xl">
            <form action="{{ route('modal.update', $data->id) }}" method="post">
                @method('PATCH')
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="left">
                        <div class="max-w-lg">
                            <div class="flex justify-start items-start flex-col gap-3">
                                <div class="w-full">
                                    <label for="jenis" class="block mb-2 text-sm font-medium text-gray-700">Jenis</label>
                                    <select id="jenis" name="jenis"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        @foreach ($dataJenis as $items)
                                            <option value="{{ $items->id }}"
                                                {{ $items->id == $data->jenis_modal_id ? 'selected' : '' }}>
                                                {{ $items->jenis_modal }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full ">
                                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                                    <input type="text" placeholder="Nama" name="nama"
                                        value="{{ old('nama', $data->nama) }}"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                                    @error('nama')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full">
                                    <label for="tanggal"
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
                                            type="text" name="tanggal" value="{{ $data->tanggal }}"
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
                                    <label for="penyedia"
                                        class="block mb-2 text-sm font-medium text-gray-700">Penyedia</label>
                                    <input type="text" placeholder="Penyedia" name="penyedia"
                                        value="{{ old('penyedia', $data->penyedia) }}"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                                    @error('penyedia')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full">
                                    <label for="jumlah"
                                        class="block mb-2 text-sm font-medium text-gray-700">Jumlah</label>
                                    <input type="text" placeholder="Jumlah" name="jumlah" id="jumlah" min="0" 
                                        value="{{ old('jumlah', $data->jumlah) }}" oninput="this.value = this.value.replace(/^0+(?!$)/, '')" 
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                                    @error('jumlah')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full">
                                    <label for="nominal"
                                        class="block mb-2 text-sm font-medium text-gray-700">Nominal</label>
                                    <input type="number" placeholder="Nominal" name="nominal" min="0"
                                        value="{{ old('jumlah', $data->nominal) }}"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
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
        // Function to handle the enable/disable of jumlah input based on jenis
        function toggleJumlahInput() {
            var jenis = document.getElementById('jenis').value;
            var jumlah = document.getElementById('jumlah');

            if (jenis == 2) { // Assuming '2' is the ID for "finansial"
                jumlah.value = 1;
                jumlah.disabled = true;
            } else {
                jumlah.disabled = false;
                jumlah.value = '';
            }
        }

        // Initial check when the page loads
        window.onload = function() {
            toggleJumlahInput();
        };

        // Listen for changes in the jenis select
        document.getElementById('jenis').addEventListener('change', toggleJumlahInput);
    </script>
    </script>
@endsection
