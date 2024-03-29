<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>CETAK DATA TRANSAKSI</title>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto my-8 p-8 bg-white shadow-lg">
        <p class="text-center text-2xl font-bold">Laporan Transaksi</p>
        <table class="table-auto w-full mt-4 border-collapse border border-gray-700">
            <thead>
                <tr>
                    <th class="border border-gray-700">No.</th>
                    <th class="border border-gray-700">Product</th>
                    <th class="border border-gray-700">Tanggal</th>
                    <th class="border border-gray-700">Jumlah Pesanan</th>
                    <th class="border border-gray-700">Total Harga </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalHarga = 0; // Inisialisasi total harga
                @endphp

                @foreach ($data as $transaction)
                    <tr>
                        <td class="border text-center border-gray-700">{{ $loop->iteration }}</td>
                        <td class="border pl-3 border-gray-700">{{ $transaction->products->nama_product }}</td>
                        <td class="border text-center border-gray-700">{{ $transaction->tanggal }}</td>
                        <td class="border text-center border-gray-700">{{ $transaction->jumlah }}</td>
                        <td class="border text-center border-gray-700">Rp. {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                        @php
                            $totalHarga += $transaction->total_harga; // Tambahkan total harga transaksi ke totalHarga
                        @endphp
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="border text-center border-gray-700 font-bold">Total Pendapatan</td>
                    <td class="border text-center border-gray-700 font-bold">
                        Rp. {{ number_format($totalHarga, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <script type="text/javascript">
        window.print();
    </script>
</body>

</html>
