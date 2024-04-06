@extends('layout.admin_pages')
@section('title', 'Admin Piutang')
@section('content')
    <div class="container px-6 pb-6 mx-auto">
        <h1 class="text-2xl my-6 font-semibold text-gray-700">Tambah Piutang</h1>
        <div class="bg-white px-8 py-8 shadow-lg rounded-3xl">
            <form action="{{ route('piutang.store') }}" method="post">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="left">
                        <div class="max-w-lg">
                            <div class="flex justify-start items-start flex-col gap-3">
                                <div class="w-full">
                                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                                    <input type="text" placeholder="Nama" name="nama" value="{{ old('nama') }}"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 " />
                                    @error('nama')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full">
                                    <label for="nominal"
                                        class="block mb-2 text-sm font-medium text-gray-700">Nominal</label>
                                    <input type="text" placeholder="Nominal" name="nominal" value="{{ old('nominal') }}"
                                        class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 " />
                                    @error('nominal')
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
                                        <input datepicker type="text" name="tanggal" value="{{ old('tanggal') }}"
                                            class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Select date">
                                    </div>
                                    @error('tanggal')
                                        <small class="error" style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="w-full">
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
                                </div>

                                <div class="w-full">
                                    <label for="photos" class="block mb-2 text-sm font-medium text-gray-800">Pilih Gambar</label>
                                    <input type="file" class="border border-gray-300 px-4 py-2 w-full" id="photos"
                                        name="photos[]" multiple required>
                                </div>
                                <div id="previewContainer" class="flex flex-wrap mb-4">
                                    <!-- Image preview will be appended here -->
                                </div>

                            </div>
                        </div>
                    </div>




                    <div class="right">
                        <div class="w-full">
                            <label for="" class="text-sm font-medium text-gray-800 ">Catatan</label>
                            <textarea
                                class="w-full max-w-4xl h-52 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Catatan" name="catatan" rows="5"></textarea>
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
        const inputElement = document.getElementById("photos");

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
    </script>
@endsection
