@extends('components.layouts.admin_pages')
@section('title', 'Admin Transaksi')
@section('content')
    <div class="container  px-6 pb-6 mx-auto">
        <div class="flex gap-4">
            <a href="{{ route('transaksis.index') }}" class="flex items-center text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <h1 class="text-2xl my-6 font-semibold text-gray-700 ">Detail Transaksi</h1>
        </div>
        {{-- <p>{{$data}}</p> --}}
        <div class="grid grid-cols-1 gap-8">
            <div class="col-span-1 ">
                <div class="bg-white rounded-3xl p-8">
                    <div class="mb-2">
                        <p class="text-sm text-gray-400">Pendapatan</p>
                        <div class="flex gap-4 justify-start items-center">
                            <div class="flex justify-start items-center gap-2">
                                <div class="w-8 h-8 flex justify-center items-center">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M11.25 7.84748C10.3141 8.10339 9.75 8.82154 9.75 9.5C9.75 10.1785 10.3141 10.8966 11.25 11.1525V7.84748Z"
                                                fill="#1C274C"></path>
                                            <path
                                                d="M12.75 12.8475V16.1525C13.6859 15.8966 14.25 15.1785 14.25 14.5C14.25 13.8215 13.6859 13.1034 12.75 12.8475Z"
                                                fill="#1C274C"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM12 5.25C12.4142 5.25 12.75 5.58579 12.75 6V6.31673C14.3804 6.60867 15.75 7.83361 15.75 9.5C15.75 9.91421 15.4142 10.25 15 10.25C14.5858 10.25 14.25 9.91421 14.25 9.5C14.25 8.82154 13.6859 8.10339 12.75 7.84748V11.3167C14.3804 11.6087 15.75 12.8336 15.75 14.5C15.75 16.1664 14.3804 17.3913 12.75 17.6833V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V17.6833C9.61957 17.3913 8.25 16.1664 8.25 14.5C8.25 14.0858 8.58579 13.75 9 13.75C9.41421 13.75 9.75 14.0858 9.75 14.5C9.75 15.1785 10.3141 15.8966 11.25 16.1525V12.6833C9.61957 12.3913 8.25 11.1664 8.25 9.5C8.25 7.83361 9.61957 6.60867 11.25 6.31673V6C11.25 5.58579 11.5858 5.25 12 5.25Z"
                                                fill="#1C274C"></path>
                                        </g>
                                    </svg>
                                </div>
                                <h1 class="text-2xl text-gray-800 font-semibold">Rp.
                                    {{ number_format($data->total_harga, 0, ',', '.') }}</h1>
                            </div>
                            @if ($data->is_complete == true)
                                <div
                                    class="bg-green-200 px-4 py-2 w-fit h-fit rounded-3xl flex justify-center items-center">
                                    <span class="text-green-500 font-semibold">Selesai</span>
                                </div>
                            @elseif ($data->is_complete == false)
                                <div
                                    class="bg-red-200 px-4 py-2 w-fit h-fit rounded-3xl flex justify-center items-center whitespace-nowrap">
                                    <span class="text-red-500 font-semibold whitespace-nowrap">Belum Selesai</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2 mt-1">
                        <p class="text-sm text-gray-400">Tanggal</p>
                        <div class="flex justify-start items-center gap-2">
                            <div class="w-6 h-6 flex justify-center items-center">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12 7V12L14.5 10.5M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                            stroke="#1e293b" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </g>
                                </svg>
                            </div>
                            <h1 class="text-2xl text-gray-800 font-semibold">
                                {{ \Carbon\Carbon::parse($data->tanggal)->format('d F Y') }}
                            </h1>
                        </div>
                    </div>
                    <hr>
                    <p class="text-sm text-gray-400 mt-1">Product </p>
                    <div class="flex flex-col gap-2 mt-1">
                        <div class="grid grid-cols-5 justify-start items-start">
                            <p class="text-base col-span-2 text-gray-500">Nama Product</p>
                            @foreach ($data->history_product_transaksis as $history_product )
                            <p class="text-base col-span-3 text-gray-800 font-medium">{{ $history_product->history_product->nama_product }}
                            @endforeach
                            </p>
                        </div>
                        <div class="grid grid-cols-5 justify-start items-start">
                            <p class="text-base col-span-2 text-gray-500">Methode Pembayaran</p>
                            <p class="text-base col-span-3 text-gray-800 font-medium">
                                {{ $data->methode_pembayaran->methode_pembayaran }}</p>
                        </div>
                        <div class="grid grid-cols-5 justify-start items-start">
                            <p class="text-base col-span-2 text-gray-500">Harga Product</p>
                            @foreach ($data->history_product_transaksis as $history_product )
                                    <p class="text-base col-span-3 text-gray-800 font-medium">Rp.
                                        {{ number_format($history_product->history_product->harga, 0, ',', '.') }}</p>
                            @endforeach
                            
                        </div>
                        <div class="grid grid-cols-5 justify-start items-start">
                            <p class="text-base col-span-2 text-gray-500">Jumlah</p>
                            <p class="text-base col-span-3 text-gray-800 font-medium">{{ $data->jumlah }}</p>
                        </div>
                        <div class="grid grid-cols-5 justify-start items-start">
                            <p class="text-base col-span-2 text-gray-500">Total Harga</p>
                            <p class="text-base col-span-3 text-gray-800 font-medium">Rp.
                                {{ number_format($data->total_harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="grid grid-cols-5 justify-start items-start">
                            <p class="text-base col-span-2 text-gray-500">Keterangan</p>
                            <p class="text-base col-span-3 text-gray-800 font-medium">{{ $data->keterangan }}</p>
                        </div>
                        <div class="grid grid-cols-5 justify-start items-start">
                            <p class="text-base col-span-2 text-gray-500">Status</p>
                            @if ($data->is_complete == true)
                                <p class="text-base col-span-3 text-green-400 font-medium">Selesai</p>
                            @elseif ($data->is_complete == false)
                                <p class="text-base col-span-3 text-red-400 font-medium">Belum Selesai</p>
                            @endif
                        </div>
                        {{-- <div class="grid grid-cols-5 justify-start items-start">
                            <p class="text-base col-span-2 text-gray-500">Transaksi Preorder?</p>
                            @if ($data->is_Preorder == true)
                            <p class="text-base col-span-3 text-gray-800 font-medium">Ya</p>
                            @elseif ($data->is_Preorder == false)
                            <p class="text-base col-span-3 text-gray-800 font-medium">Tidak</p>
                            @endif

                        </div> --}}
                    </div>
                </div>
            </div>
            @if ($data->is_Preorder == true && $data->Preorder_id != null)
                <div class="col-span-2 flex flex-col gap-8">
                    <div class="bg-white rounded-3xl p-8">
                        <p class="text-base text-gray-800 font-medium mb-2">Informasi Pembeli</p>
                        <hr>
                        <div class="flex flex-col gap-2 mt-1">
                            <div class="grid grid-cols-6 justify-start items-start">
                                <p class="text-base col-span-2 text-gray-500">Nama</p>
                                <p class="text-base col-span-4 text-gray-800 font-medium">{{ $data->pembelis->nama }}</p>
                            </div>
                            <div class="grid grid-cols-6 justify-start items-start">
                                <p class="text-base col-span-2 text-gray-500">Email</p>
                                <p class="text-base col-span-4 text-gray-800 font-medium">{{ $data->pembelis->email }}</p>
                            </div>
                            <div class="grid grid-cols-6 justify-start items-start">
                                <p class="text-base col-span-2 text-gray-500">Alamat</p>
                                <p class="text-base col-span-4 text-gray-800 font-medium">{{ $data->pembelis->alamat }}</p>
                            </div>
                            <div class="grid grid-cols-6 justify-start items-start">
                                <p class="text-base col-span-2 text-gray-500">No Hp</p>
                                <p class="text-base col-span-4 text-gray-800 font-medium">{{ $data->pembelis->no_hp }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-3xl p-8">
                        <p class="text-base text-gray-800 font-medium mb-2">Informasi Preorder</p>
                        <hr>
                        <div class="grid grid-cols-2 justify-start items-start">
                                <p class="text-sm col-span-1 text-gray-500 whitespace-nowrap ">Tanggal Pembayaran DP</p>
                                <p class="text-base col-span-1 text-gray-800 font-medium">
                                    {{ \Carbon\Carbon::parse($data->preorders->tanggal_pembayaran_down_payment)->format('d F Y') }}
                                </p>
                            </div>
                        <div class="flex flex-col gap-2 mt-1">
                            <div class="grid grid-cols-2 justify-start items-start">
                                <p class="text-sm col-span-1 text-gray-500">DP</p>
                                <p class="text-base col-span-1 text-gray-800 font-medium">Rp.
                                    {{ number_format($data->preorders->down_payment, 0, ',', '.') }} </p>
                            </div>
                            
                            @if ($data->is_complete == 1)
                                 <div class="grid grid-cols-2 justify-start items-start">
                                <p class="text-sm col-span-1 text-gray-500 whitespace-nowrap">Kekurangan Harga</p>
                                <p class="text-base col-span-1 text-green-500    font-medium">Lunas</p>
                            </div>
                            @elseif ($data->is_complete == 0)
                            <div class="grid grid-cols-2 justify-start items-start">
                                <p class="text-sm col-span-1 text-gray-500 whitespace-nowrap">Kekurangan Harga</p>
                                <p class="text-base col-span-1 text-red-400 font-medium">Rp.
                                    {{ number_format($data->total_harga - $data->preorders->down_payment, 0, ',', '.') }}
                                </p>
                            </div>
                            @endif
                            

                        </div>
                    </div>
            @endif
        </div>
    </div>



    </div>
@endsection
