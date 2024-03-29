@extends('layout.admin_pages')
@section('title', $data->nama_product)
@section('content')
<div class="container  px-6 pb-6 mx-auto">
    <h1 class="text-2xl my-6 font-semibold text-gray-700 ">Edit Product</h1>
    <div class="bg-white flex justify-center items-center px-8 py-8 shadow-lg rounded-3xl">

    <form action="{{route('products.update',$data->id)}}" method="POST" enctype="multipart/form-data">
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
                        <textarea class="textarea textarea-info w-full max-w-4xl bg-slate-50"
                            placeholder="Deskripsi Product" name="deskripsi"
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
                        <input type="text" placeholder="Jumlah Stok" name="stok" value="{{ old('stok', $data->stok) }}"
                            class="input input-bordered input-info w-full max-w-4xl duration-50 bg-slate-50 mb-3" />
                    </div>
                    <div class="">
                        <label class="block text-sm mb-1">
                            <span class="text-gray-700 dark:text-gray-400">Spesifikasi Product</span>
                        </label>
                        <textarea class="textarea textarea-info w-full max-w-4xl bg-slate-50"
                            placeholder="Spesifikasi Product" name="spesifikasi_product"
                            rows="4">{{ old('spesifikasi_product', $data->spesifikasi_product) }}</textarea>
                    </div>
                   
                    <!-- Prefill Varians input fields -->
                    <div id="form-container">
                        @foreach ($data->varians as $index => $varian)
                        <div class="form-group flex justify-center items-center gap-2">
                            <input type="text" name="varian[{{ $index }}]" id="varian{{ $index }}"
                                placeholder="Varian {{ $index }}"
                                value="{{ old('varian['.$index.']', $varian->jenis_varian) }}"
                                class="input input-bordered input-info w-full max-w-4xl duration-50 bg-slate-50 mb-3" />
                            <!-- ... add remove button here ... -->
                            <div class="w-8 h-8 cursor-pointer" onclick="removeInput(this)">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <circle cx="12" cy="12" r="10" stroke="#e21818" stroke-width="1.5"></circle>
                                        <path d="M15 12H9" stroke="#e21818" stroke-width="1.5" stroke-linecap="round">
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
            <div class="w-full">
                <p class="text-start font-semibold mb-1">Gambar Saat Ini</p>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($data->fotos as $foto)
                        <img src="{{asset('storage/images/'.$foto->foto)}}" alt="" srcset="" class="w-40 h-40 object-cover rounded-lg">
                    @endforeach
                </div>
            </div>
            <div class="flex justify-center items-center flex-col mt-4">
                <div class="mb-4 w-full">
                    <label for="gambar" class="block text-gray-700 font-semibold  mb-2">Pilih Gambar</label>
                    {{-- <input type="file" name="gambar" id="gambar" multiple class="border rounded-md px-4 py-2 w-full">
                    --}}
                                            
                    <div class="flex items-center justify-center w-full relative">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-52 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG</p>
                            </div>
                            <input id="dropzone-file" type="file" class="absolute w-full h-full border opacity-0"  name="image[]" multiple onchange="previewImages()"/>
                        </label>
                    </div> 

                </div>
                <p class="text-sm italic">notes: mengedit gambar akan mengubah semua gambar product, jadi jika ingin mengubah 1 gambar product, harap mengupload keseluruhan product yang benar</p>
                <!-- Image Preview -->
                <div id="imagePreviews" class="w-full rounded-lg  grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 ">
                  
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
                <button type="submit"
                    class="text-center focus:outline-none text-white w-full md:w-fit bg-green-700 hover:text-gray-100 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-3 me-2 mb duration-300 whitespace-nowrap">Submit Edit</button>
                <a href="{{ route('products.index') }}"
                    class="inline-block px-4 py-2 bg-red-600 text-white hover:bg-red-700 hover:text-gray-100 rounded-md font-medium cursor-pointer duration-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<script src="{{asset('js/dropzone.js')}}"></script>
<script>
    let variantcount = {{ count($data->varians) }} + 1;

function addInput() {
    var formContainer = document.getElementById('form-container');
    var newFormGroup = document.createElement('div');
    newFormGroup.className = 'form-group';
    newFormGroup.innerHTML = ' <div class="form-group flex justify-center items-center gap-2">' +
        '<input type="text" name="varian[' + variantcount + ']" id="varian' + variantcount + '" ' +
        'placeholder="Varian ' + variantcount + '" ' +
        'class="input input-bordered input-info w-full max-w-4xl duration-50 bg-slate-50  mb-3" />' +
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
    var formGroup = element.closest('.form-group');
    formGroup.parentNode.removeChild(formGroup);
}
</script>

@endsection