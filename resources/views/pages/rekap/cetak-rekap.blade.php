
<!DOCTYPE html>
<html :class="{ 'theme-dark': light }" data-theme="light" x-data="data()" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cetak Rekapan</title>
    <style>
        .cover-image {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
    </style>

    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    @include('partials.link')
    @extends('partials.head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet" />

    <style>
        /* Add this in your CSS or within a style tag in your HTML */
        .costumscroll::-webkit-scrollbar {
            width: 8px;
            /* Set the width of the scrollbar */
        }

        .costumscroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            /* Set the color of the thumb (the draggable part) */
            border-radius: 8px;
            /* Set the border-radius of the thumb */
        }

        .costumscroll::-webkit-scrollbar-track {
            background-color: #f1f1f1;
            /* Set the color of the track (the non-draggable part) */
        }
    </style>
</head>

<body>
    
 



<div class="container mx-auto my-8 p-8 bg-white shadow-lg">
    <p class="text-center text-2xl font-bold">Laporan Rekapan Keuangan</p>
    
    <!-- Kategori Uang Masuk -->
    <h2 class="text-xl font-semibold mt-6 mb-4">Uang Masuk</h2>
    <table class="table-auto w-full mt-2 border-collapse border border-gray-700">
        <thead>
            <tr>
                <th class="border border-gray-700">No.</th>
                <th class="border border-gray-700">Tanggal</th>
                <th class="border border-gray-700">Sumber</th>
                <th class="border border-gray-700">Jumlah</th>
                <th class="border border-gray-700">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $masukCounter = 1; @endphp
            @foreach ($data as $transaction)
                @if ($transaction->tipe_transaksi == 'masuk')
                    <tr class="bg-green-100">
                        <td class="border text-center border-gray-700">{{ $masukCounter++ }}</td> <!-- Menggunakan counter -->
                        <td class="border pl-3 border-gray-700">{{ \Carbon\Carbon::parse($transaction->tanggal)->locale('ID')->isoFormat('D MMMM YYYY') }}</td>
                        <td class="border text-center border-gray-700">{{ $transaction->sumber }}</td>
                        <td class="border text-center border-gray-700">Rp. {{ number_format($transaction->jumlah, 0, ',', '.') }}</td>
                        <td class="border text-center border-gray-700">{{ $transaction->keterangan }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <!-- Kategori Uang Keluar -->
    <h2 class="text-xl font-semibold mt-6 mb-4">Uang Keluar</h2>
    <table class="table-auto w-full mt-2 border-collapse border border-gray-700">
        <thead>
            <tr>
                <th class="border border-gray-700">No.</th>
                <th class="border border-gray-700">Tanggal</th>
                <th class="border border-gray-700">Sumber</th>
                <th class="border border-gray-700">Jumlah</th>
                <th class="border border-gray-700">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $keluarCounter = 1; @endphp
            @foreach ($data as $transaction)
                @if ($transaction->tipe_transaksi == 'keluar')
                    <tr class="bg-red-100">
                        <td class="border text-center border-gray-700">{{ $keluarCounter++ }}</td> <!-- Menggunakan counter -->
                        <td class="border pl-3 border-gray-700">{{ \Carbon\Carbon::parse($transaction->tanggal)->locale('ID')->isoFormat('D MMMM YYYY') }}</td>
                        <td class="border text-center border-gray-700">{{ $transaction->sumber }}</td>
                        <td class="border text-center border-gray-700">Rp. {{ number_format($transaction->jumlah, 0, ',', '.') }}</td>
                        <td class="border text-center border-gray-700">{{ $transaction->keterangan }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <!-- Menampilkan Total dan Saldo -->
    <div class="mt-4">
        <p class="text-lg font-semibold">Total Uang Masuk: <span class="text-green-600">Rp. {{ number_format($totalMasuk, 0, ',', '.') }}</span></p>
        <p class="text-lg font-semibold">Total Uang Keluar: <span class="text-red-600">Rp. {{ number_format($totalKeluar, 0, ',', '.') }}</span></p>
        <p class="text-lg font-semibold">Sisa Saldo Akhir: <span class="text-blue-600">Rp. {{ number_format($saldoAkhir, 0, ',', '.') }}</span></p>
    </div>
</div>

<script type="text/javascript">
    window.print();
</script>
@extends('partials.link')
</body>

</html>