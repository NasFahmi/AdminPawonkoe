@extends('components.layouts.admin_pages')
@section('title', $data->nama_product)
@section('content')
    <div class="container  px-6 pb-6 mx-auto">
        <h1 class="text-2xl my-6 font-semibold text-gray-700 ">Edit Product</h1>
        <div class="bg-white  px-8 py-8 shadow-lg rounded-3xl">

            <form action="{{ route('products.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @method('patch')
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="left">
                        <div class="">
                            <label class="block text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-400">Nama Product</span>
                            </label>
                            <input type="text" placeholder="nama product" name="nama_product"
                                value="{{ old('nama_product', $data->nama_product) }}"
                                class="input input-bordered input-info bg-slate-50 w-full max-w-4xl duration-50 mb-3" />
                        </div>
                        <div class="">
                            <label class="block text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-400">Harga</span>
                            </label>
                            <div>
                                <input type="number" placeholder="Harga" name="harga"
                                    value="{{ old('harga', $data->harga) }}"
                                    class="input input-bordered input-info w-full bg-slate-50  duration-50 " />
                            </div>
                        </div>
                        <div class="">
                            <label class="block text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-400">Deskripsi</span>
                            </label>
                            <textarea class="textarea textarea-info w-full max-w-4xl bg-slate-50" placeholder="Deskripsi Product" name="deskripsi"
                                rows="4">{{ old('deskripsi', $data->deskripsi) }}</textarea>
                        </div>


                    </div>
                    <div class="right">
                        <div class="">
                            <label class="block text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-400 ">Link Shopee</span>
                            </label>
                            <input type="text" placeholder="Link Shopee" name="link_shopee"
                                value="{{ old('link_shopee', $data->link_shopee) }}"
                                class="input input-bordered input-info w-full max-w-4xl duration-50 bg-slate-50  mb-3" />
                        </div>
                        <div class="">
                            <label class="block text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-400">Stok</span>
                            </label>
                            <input type="text" placeholder="Jumlah Stok" name="stok"
                                value="{{ old('stok', $data->stok) }}"
                                class="input input-bordered input-info w-full max-w-4xl duration-50 bg-slate-50 mb-3" />
                        </div>
                        <div class="">
                            <label class="block text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-400">Spesifikasi Product</span>
                            </label>
                            <textarea class="textarea textarea-info w-full max-w-4xl bg-slate-50" placeholder="Spesifikasi Product"
                                name="spesifikasi_product" rows="4">{{ old('spesifikasi_product', $data->spesifikasi_product) }}</textarea>
                        </div>

                        <!-- Prefill Varians input fields -->
                        <div id="form-container">
                            @foreach ($data->varians as $index => $varian)
                                <div class="form-group flex justify-center items-center gap-2">
                                    <input type="text" name="varian[{{ $index }}]" id="varian{{ $index }}"
                                        placeholder="Varian {{ $index }}"
                                        value="{{ old('varian[' . $index . ']', $varian->jenis_varian) }}"
                                        class="input input-bordered input-info w-full max-w-4xl duration-50 bg-slate-50 mb-3" />
                                    <!-- ... add remove button here ... -->
                                    <div class="w-8 h-8 cursor-pointer" onclick="removeInput(this)">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <circle cx="12" cy="12" r="10" stroke="#e21818"
                                                    stroke-width="1.5"></circle>
                                                <path d="M15 12H9" stroke="#e21818" stroke-width="1.5"
                                                    stroke-linecap="round">
                                                </path>
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addInput()" class="text-green-400">Tambah Varian</button>
                    </div>
                </div>
                <div class="flex justify-center items-center flex-col mt-4">
                    <div class="mb-4 w-full">
                        <label for="images" class="text-gray-700 font-semibold text-left  mb-2">Pilih Gambar</label>
                        <input type="file" class="border border-gray-300 px-4 py-2 w-full" id="images" name="images[]"
                            multiple>

                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex justify-center items-center mt-3">
                    <button type="submit" id="submitbtn"
                        class="text-center focus:outline-none text-white w-full md:w-fit bg-green-700 hover:text-gray-100 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-3 me-2 mb duration-300 whitespace-nowrap">Submit
                        Edit</button>
                    <a href="{{ route('products.index') }}"
                        class="inline-block px-4 py-2 bg-red-600 text-white hover:bg-red-700 hover:text-gray-100 rounded-md font-medium cursor-pointer duration-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script>
        let images = @json($images);
        
        let submitbtn = document.getElementById('submitbtn');
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginFileValidateSize);
        const inputElement = document.getElementById("images");

        const pond = FilePond.create(inputElement, {
            acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
            allowImagePreview: true,
            maxFileSize: '2MB',
            allowMultiple: true,
            // files: [{
            //         source: 'http://127.0.0.1:8000/storage/images/RTfaZNir7B6AHKJmKL8S.jpeg',
            //         options: {
            //             type: 'local',
            //         },
            //     },
            //     {
            //         source: 'http://127.0.0.1:8000/storage/images/RTfaZNir7B6AHKJmKL8S.jpeg',
            //         options: {
            //             type: 'local',
            //         },
            //     }
            // ],

        });

        FilePond.setOptions({
            required: true,
            // onload: (source, load, error, progress, abort, headers) => {
            //     console.log(source)
            //     const myRequest = new Request(source);
            //     fetch(myRequest).then((res) => {
            //         return res.blob();
            //     }).then(load);
            //     console.log(myRequest);
            // },
            onprocessfile: (error, file) => {
                if (!error) {
                    submitbtn.removeAttribute("disabled");
                    // Tambahan untuk update file di server (contoh)
                    const formData = new FormData();
                    formData.append('file', file.file);

                }
            },
            server: {
                process: {
                    url: '{{ route('upload.directtoDB',$data->id) }}',
                    method:'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    }
                },

                load: (source, load, error, progress, abort, headers) => {
                  
                    var request = new Request(source);
                    fetch(request).then(function(response) {

                        response.blob().then(function(myBlob) {

                            load(myBlob)
                        });
                    });
                },
            },
            files: images,
        });
    </script>

@endsection
