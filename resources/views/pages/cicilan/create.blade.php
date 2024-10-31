@extends('layout.admin_pages')
@section('title', 'Admin Hutang ')
@section('content')
    <div class="container px-6 pb-6 mx-auto">
        <h1 class="my-6 text-2xl font-semibold text-gray-700">Tambah Cicilan Hutang {{ $hutangData->nama }}</h1>
        <div class="px-8 py-8 bg-white shadow-lg rounded-3xl">

            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                <form action="{{ route('cicilan.store', $hutangData->id) }}" method="post">
                    @csrf
                    <div class="w-full">
                        <label for="nominal" class="block mb-2 text-sm font-medium text-gray-700">Nominal</label>
                        <input type="number" placeholder="Nominal Cicilan" name="nominal" value="{{ old('nominal') }}" oninput="this.value = this.value.replace(/^0+(?!$)/, '')"
                            class="bg-gray-50 border max-w-4xl border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 " />
                        @error('nominal')
                            <small class="error" style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="w-full flex mt-3 justify-start items-center">
                        <a href="{{ route('hutang.detail', $hutangData->id) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 ">Back</a>
                        <button type="submit"
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 ">Create</button>


                    </div>
                </form>

            </div>
            <table class="w-full mt-8 text-sm text-left table-auto ">
                <thead class="text-xs text-gray-700 bg-gray-100 ">
                    <tr class="">
                        <th scope="col" class="px-4 py-2 whitespace-nowrap">
                            Nominal
                        </th>
                        <th scope="col" class="px-4 py-2 whitespace-nowrap">
                            Tanggal
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hutangData->hutang_cicilan as $items)
                        <tr
                            class="px-4 py-2 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-">
                            <th scope="row" class="w-10 h-16 px-4 py-2 lg:whitespace-nowrap">
                                <span class="text-sm font-normal">
                                    Rp. {{ number_format($items->nominal, 0, ',', '.') }}

                                </span>
                            </th>

                            <td cope="row" class="w-10 h-16 px-4 py-2 lg:whitespace-nowrap">
                                <span>
                                    {{ \Carbon\Carbon::parse($items->created_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm:ss') }}

                                </span>
                            </td>


                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    @endsection
