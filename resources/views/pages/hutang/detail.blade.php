@extends('layout.admin_pages')
@section('title', 'Admin Hutang ')
@section('content')
    <div class="container px-6 pb-6 mx-auto">
        <h1 class="my-6 text-2xl font-semibold text-gray-700">Detail Hutang {{ $hutangData->nama }}</h1>
        <div class="px-8 py-8 bg-white shadow-lg rounded-3xl">

            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                <div class="left">
                    <div class="max-w-lg">
                        <div class="flex flex-col items-start justify-start gap-3">
                            <div class="w-full">
                                <h1 class="block pt-2 text-normal font-medium text-gray-800">Nama</h1>
                                <p class="text-sm text-gray-700">{{ $hutangData->nama }}</p>
                            </div>
                            <div class="w-full">
                                <h1 class="block pt-2 text-normal font-medium text-gray-800 ">Jumlah Hutang</h1>
                                <p class="text-sm text-gray-700 font-medium">Rp.
                                    {{ number_format($hutangData->jumlah_hutang, 0, ',', '.') }}</p>

                            </div>
                            <div class="w-full">
                                <h1 class="block pt-2 text-normal font-medium text-gray-800">Catatan</h1>
                                @if (isset($hutangData->catatan))
                                    <p class="text-sm text-gray-700">{{ $hutangData->catatan }}</p>
                                @else
                                    <p class="text-sm text-gray-700">-</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right">
                    <div class="w-full">
                        <h1 class="block pt-2 text-normal font-medium text-gray-800">Status</h1>
                        @if ($hutangData->status == 1)
                            <span
                                class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded ">Selesai</span>
                        @else
                            <span
                                class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Belum
                                Selesai</span>
                        @endif
                    </div>
                    <div class="w-full">
                        <h1 class="block pt-2 text-normal font-medium text-gray-800">Tanggal Lunas</h1>
                        @if (isset($hutangData->tanggal_lunas))
                            <p class="text-sm text-gray-700">
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $hutangData->tanggal_lunas)->format('d-m-Y H:i:s') }}
                            </p>
                        @else
                            <p class="text-sm text-gray-700">-</p>
                        @endif
                    </div>
                    <div class="w-full">
                        <h1 class="block pt-2 text-normal font-medium text-gray-800">Sisa Hutang</h1>
                        @if ($hutangData->status == 0)
                            <p class="text-sm text-red-400 font-semibold">Rp.
                                {{ number_format($hutangData->remaining_debt, 0, ',', '.') }}</p>
                        @else
                            <p class="text-sm text-gray-700">Tidak ada sisa hutang</p>
                        @endif
                    </div>

                </div>
            </div>

            @if ($hutangData->status == 1)
                <p class="text-center text-sm font-medium">Tidak ada data cicilan</p>
            @else
                <div class="w-full flex justify-end">

                    <a href="{{ route('cicilan.create', $hutangData->id) }}"
                        class="flex items-center justify-center w-full gap-1 px-4 py-2 bg-green-200 md:w-fit rounded-3xl ">
                        <div class="w-4 h-4">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M6 12H18M12 6V18" stroke="#0284c7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </g>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-green-600">Tambah Cicilan</span>
                    </a>
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
            @endif
        </div>
    </div>
@endsection
