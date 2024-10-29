@extends('layout.admin_pages')
@section('title', 'Create Product')
@section('content')
    <div class="container  px-6 pb-6 mx-auto">
        <h1 class="text-2xl my-6 font-semibold text-gray-700 ">Create Product</h1>
        <div class="bg-white  px-8 py-8 shadow-lg rounded-3xl">

            <form action="{{ route('products.store') }}" method="post" class="" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 ">
                    <div class="left">
                        <div class="">
                            <label class="block text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-400">Nama Product</span>
                            </label>
                            <input type="text" placeholder="nama product" name="nama_product"
                                value="{{ old('nama_product') }}"
                                class="input input-bordered input-info bg-slate-50 w-full max-w-4xl duration-50 " />
                            @error('nama_product')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="">
                            <label class="block text-sm mt-3 mb-1">
                                <span class="text-gray-700 dark:text-gray-400">Harga</span>
                            </label>
                            <input type="number" placeholder="Harga" name="harga" value="{{ old('harga') }}" min="0"
                                class="input input-bordered input-info w-full bg-slate-50  duration-50 " />
                            @error('harga')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror

                        </div>
                        <div class="">
                            <label class="block text-sm mb-1 mt-3">
                                <span class="text-gray-700 dark:text-gray-400">Deskripsi</span>
                            </label>
                            <textarea class="textarea textarea-info w-full max-w-4xl bg-slate-50" placeholder="Deskripsi Product" name="deskripsi"
                                rows="4">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>


                    </div>
                    <div class="right">
                        <div class="">
                            <label class="block text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-400 ">Link Shopee</span>
                            </label>
                            <input type="text" placeholder="Link Shopee" name="link_shopee"
                                value="{{ old('link_shopee') }}"
                                class="input input-bordered input-info w-full max-w-4xl duration-50 bg-slate-50 " />
                            @error('link_shopee')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="">
                            <label class="block text-sm mb-1 mt-3">
                                <span class="text-gray-700 dark:text-gray-400">Stok</span>
                            </label>
                            <input type="number" placeholder="Jumlah Stok" name="stok" value="{{ old('stok') }}" min="1" pattern="[1-9][0-9]*"
                                class="input input-bordered input-info w-full max-w-4xl duration-50 bg-slate-50 " />
                            @error('stok')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="">
                            <label class="block text-sm mb-1 mt-3">
                                <span class="text-gray-700 dark:text-gray-400">Spesifikasi Product</span>
                            </label>
                            <textarea class="textarea textarea-info w-full max-w-4xl bg-slate-50" placeholder="Spesifikasi Product"
                                name="spesifikasi_product" rows="4">{{ old('spesifikasi_product') }}</textarea>
                            @error('spesifikasi_product')
                                <small class="error" style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="">
                            <label class="block text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-400">Varian Product</span>
                            </label>
                        </div>
                        <div id="form-container">
                            <!-- Formulir input awal -->
                            <div class="form-group flex justify-center items-center gap-2">

                            </div>
                        </div>

                        <button type="button" onclick="addInput()" class="text-green-400">Tambah Varian</button>
                    </div>
                </div>
                <div class=" mt-4">
                    <label for="images" class="text-gray-700 font-semibold text-left  mb-2">Pilih Gambar</label>
                    <input type="file" class="border border-gray-300 px-4 py-2 w-full" id="images" name="images[]"
                        multiple>

                    @error('image')
                        <small class="error" style="color: red">{{ $message }}</small>
                    @enderror
                    @error('image.*')
                        <small class="error" style="color: red">{{ $message }}</small>
                    @enderror

                </div>

                {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            
                        </ul>
                    </div>
                @endif --}}

                <div class="flex justify-center items-center mt-3">
                    <button type="submit" id="submitbtn" disabled
                        class="text-center focus:outline-none text-white w-full md:w-fit bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-3 me-2 mb duration-300 whitespace-nowrap">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script>
        let submitbtn = document.getElementById('submitbtn');
        FilePond.registerPlugin(FilePondPluginImagePreview);
        // Register the plugin
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        // Get a reference to the file input element
        FilePond.registerPlugin(FilePondPluginFileValidateSize);
        const inputElement = document.getElementById("images");
        console.log(inputElement);
        // Create a FilePond instance
        const pond = FilePond.create(inputElement, {
            acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
            allowImagePreview: true,
            maxFileSize: '2MB',
            allowMultiple: true,
        });

        FilePond.setOptions({
            required: true,
            onprocessfile: (error, file) => {
                if (!error) {
                    submitbtn.removeAttribute("disabled")
                }
            },
            server: {
                process: {
                    url: '{{ route('upload.temporary') }}',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    }
                },
                revert: {
                    url: '{{ route('delete.temporary') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    }
                }
            },
        });

        let variantcount = 1;

        function addInput() {
            var formContainer = document.getElementById('form-container');
            var newFormGroup = document.createElement('div');
            newFormGroup.className = 'form-group';
            newFormGroup.innerHTML = ' <div class="form-group flex justify-center items-center gap-2">' +
                '<input type="text" name="varian[' + variantcount + ']" placeholder="Varian ' + variantcount + '" ' +
                'class="input input-bordered input-info w-full max-w-md duration-50 bg-slate-50  mb-3" />' +
                '<div class="w-8 h-8 cursor-pointer" onclick="removeInput(this)" >' +
                '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                '<g id="SVGRepo_bgCarrier" stroke-width="0"></g>' +
                '<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>' +
                '<g id="SVGRepo_iconCarrier"> <circle cx="12" cy="12" r="10" stroke="#e21818" stroke-width="1.5"></circle>' +
                '<path d="M15 12H9" stroke="#e21818" stroke-width="1.5" stroke-linecap="round"></path> </g></svg>' +
                '</div>' +
                '</div>';
            formContainer.appendChild(newFormGroup);
            variantcount++;
        }

        function removeInput(element) {
            var formGroup = element.parentElement;
            formGroup.parentNode.removeChild(formGroup);
        }
    </script>

@endsection
