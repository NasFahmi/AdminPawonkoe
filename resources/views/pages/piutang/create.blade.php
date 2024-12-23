@extends('layout.admin_pages')
@section('title', 'Admin Piutang')
@section('content')
    <div class="container px-6 pb-6 mx-auto">
        <h1 class="text-2xl my-6 font-semibold text-gray-700">Tambah Piutang</h1>
        <div class="bg-white px-8 py-8 shadow-lg rounded-3xl">
            <form action="{{ route('piutang.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="left">
                        <div class="max-w-lg">
                            <div class="flex justify-start items-start flex-col gap-3">
                                <div class="w-full">
                                    <label for="nama_toko" class="block mb-2 text-sm font-medium text-gray-700">Nama
                                        Toko</label>
                                    <input type="text" placeholder="Nama Toko" name="nama_toko"
                                        value="{{ old('nama_toko') }}"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 " />
                                    @error('nama_toko')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full">
                                    <label for="sewa_titip" class="block mb-2 text-sm font-medium text-gray-700">Sewa
                                        Titip</label>
                                    <input type="number" min="0" placeholder="Sewa Titip" name="sewa_titip"
                                        value="{{ old('sewa_titip') }}" oninput="this.value = this.value.replace(/^0+(?!$)/, '')"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 " />
                                    @error('sewa_titip')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full ">
                                    <label for="" class="block mb-2 text-sm font-medium text-gray-800">Tanggal
                                        Setor</label>
                                    <div class="relative max-w-lg ">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="datepicker-format" datepicker datepicker-format="yyyy-mm-dd"  type="text" name="tanggal_disetorkan"
                                            value="{{ old('tanggal_disetorkan') }}"
                                            class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Select date">
                                    </div>
                                    @error('tanggal_disetorkan')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- <div class="w-full">
                                    <p class="block mb-2 text-sm font-medium text-gray-800">Status</p>
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
                                </div> --}}

                                <div class="w-full">
                                    <label for="" class="text-sm font-medium text-gray-800 ">Catatan</label>
                                    <textarea
                                        class="w-full max-w-4xl h-52 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Catatan" name="catatan" rows="5"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="right overflow-y-auto h-[484px]">
                        <div class="">
                            <label class="block text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-400">Product</span>
                            </label>
                        </div>
                        <div id="form-container">
                            <!-- Formulir input awal yang tidak bisa dihapus -->
                            <div class="form-group flex flex-col justify-center items-center gap-2">
                                <input type="text" name="product[0][product]" placeholder="Product"
                                    class="bg-gray-50 mb-2 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                                <input type="number" min="1" name="product[0][quantity]" placeholder="Quantity" oninput="this.value = this.value.replace(/^0+(?!$)/, '')"
                                    class="bg-gray-50 mb-2 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                                <input type="number" min="0" name="product[0][price]" placeholder="Price" oninput="this.value = this.value.replace(/^0+(?!$)/, '')"
                                    class="bg-gray-50 mb-2 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                            </div>
                        </div>

                        <button type="button" onclick="addInput()" class="text-green-400">Tambah Product</button>
                    </div>
                </div>

                <div class="flex justify-center items-center flex-col mt-4">
                    <div class="mb-4 w-full">
                        <label for="gambar" class="block text-gray-700 font-semibold  mb-2">Pilih Gambar Nota</label>
                        <div class="flex items-center justify-center w-full relative">
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-52 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                            class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">JPG, PNG, JPEG</p>
                                </div>
                                <input id="dropzone-file" type="file" value="{{ old('image') }}"
                                    class="absolute w-full h-full border opacity-0" name="image[]" multiple
                                    onchange="previewImages()" />
                            </label>

                        </div>
                        @error('image')
                            <small class="error" style="color: red">{{ $message }}</small>
                        @enderror
                        @error('image.*')
                            <small class="error" style="color: red">{{ $message }}</small>
                        @enderror

                    </div>
                    <!-- Image Preview -->
                    <div id="imagePreviews" class="w-full rounded-lg  grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 ">
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
        let product = 1; // Mulai dari 1 karena 0 sudah digunakan oleh form pertama

        function addInput() {
            var formContainer = document.getElementById('form-container');
            var newFormGroup = document.createElement('div');
            newFormGroup.className = 'form-group';
            newFormGroup.innerHTML = '<div class="form-group flex-cols mb-4 justify-center items-center">' +
                '<input type="text" name="product[' + product + '][product]" placeholder="Product" ' +
                'class="bg-gray-50 mb-2 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />' +
                '<input oninput="this.value = this.value.replace(/^0+(?!$)/, '')" type="number" name="product[' + product + '][quantity]" placeholder="Quantity" ' +
                'class="bg-gray-50 mb-2 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />' +
                '<input oninput="this.value = this.value.replace(/^0+(?!$)/, '')" type="number" name="product[' + product + '][price]" placeholder="Price" ' +
                'class="bg-gray-50 mb-2 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />' +
                '<div class="cursor-pointer" onclick="removeInput(this)">' +
                '<p class="text-red-400 text-sm">delete product</p>' +
                '</div>' +
                '</div>';
            formContainer.appendChild(newFormGroup);
            product++;
        }

        function removeInput(element) {
            var formGroup = element.parentElement;
            formGroup.parentNode.removeChild(formGroup);
        }

        function previewImages() {
            var previewContainer = document.getElementById('imagePreviews');
            previewContainer.innerHTML = ''; // Bersihkan konten sebelum menambahkan gambar baru

            var files = document.getElementById('dropzone-file').files;
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('w-full', 'h-full', 'object-cover');
                    previewContainer.appendChild(img);
                }

                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
