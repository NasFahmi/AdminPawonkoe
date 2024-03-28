<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Foto;
use App\Models\Product;
use App\Models\Preorder;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexDashboard()
    {
        $data = Transaksi::with(['pembelis', 'products', 'methode_pembayaran', 'preorders'])->get();
        $totalPendapatan = Transaksi::where('is_complete', 1)->sum('total_harga');
        $totalProductTerjual = Transaksi::where('is_complete', 1)->sum('jumlah');
        $totalPreorder = Transaksi::where('is_complete', 1)->sum('is_Preorder');
        $dataJumlahOrder = $data->count();

        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $startDateTest = '2023-12-01';
        $endDateTest = '2024-12-30';

        $namaPembeli = $data->where('is_Preorder', 1)
            ->whereNotNull('pembelis.nama')
            ->sortByDesc('created_at')
            ->pluck('pembelis.nama');

        // dd($namaPembeli);

        $topSalesProducts = Transaksi::where('is_complete', 1)
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->select('product_id', DB::raw('SUM(jumlah) as totalJumlah'))
            ->orderByDesc('totalJumlah')
            ->with('products')
            ->limit(5)
            ->get();

        $preorderRecently = Preorder::whereHas('transaksis', function ($query) {
            $query->where('is_preorder', 1)->where('is_complete', 0);
        })
            ->latest()
            ->limit(3)
            ->get();
        $productRecently = Product::with('fotos', 'transaksis')
            ->where('tersedia', 1)
            ->latest()->limit(5)->get();

        $foto = Foto::join('transaksis', 'fotos.product_id', '=', 'transaksis.product_id')
            ->where('transaksis.is_complete', 0)
            ->select('fotos.product_id', DB::raw('MAX(fotos.id) as id'), DB::raw('MAX(fotos.foto) as foto'))
            ->groupBy('fotos.product_id')
            ->get();

        // Mengambil data total penjualan
        $dataPenjualan = Transaksi::where('is_complete', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->selectRaw('tanggal, sum(total_harga) as total_penjualan')
            ->groupBy('tanggal')
            ->pluck('total_penjualan', 'tanggal');
        $dataPenjualanFormatted = array_values($dataPenjualan->toArray());
        // dd($dataPenjualanFormatted);

        // Mendapatkan daftar tanggal
        $tanggalPenjualan = array_keys($dataPenjualan->toArray());



        // // Format the dates as desired (e.g., "2023-12-03 10:39:37" will be converted to "3 December 2023")
        $tanggalPenjualanFormatted = collect($tanggalPenjualan)->map(function ($tanggal) {
            // Menggunakan Carbon untuk memanipulasi format tanggal
            $carbonDate = Carbon::parse($tanggal);

            // Set lokal untuk format bulan dalam bahasa Indonesia
            $carbonDate->setLocale(App::getLocale());

            // Format tanggal dengan nama bulan
            return $carbonDate->formatLocalized('%d %B %Y'); // Sesuaikan format sesuai kebutuhan
        });

        // dd($tanggalPenjualanFormatted);
        // Print the results

        // dd($salesData);
        return view('pages.dashboard', compact(
            'data',
            'totalPendapatan',
            'totalProductTerjual',
            'totalPreorder',
            'dataJumlahOrder',
            'topSalesProducts',
            'preorderRecently',
            'namaPembeli',
            'foto',
            'productRecently',
            'dataPenjualanFormatted',
            'tanggalPenjualanFormatted'
        ));
    }
    public function chart()
    {
        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dataPenjualan = Transaksi::where('is_complete', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as month, sum(total_harga) as total_penjualan')
            ->groupBy('month')
            ->pluck('total_penjualan', 'month')
            ->toArray();

        // Create an array to map numeric months to Indonesian month names
        $monthNames = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        // Initialize the array with 0 values for each month as strings
        $dataPenjualanWithMonthNames = array_fill_keys(array_values($monthNames), "0");

        // Replace numeric months with Indonesian month names and update values
        foreach ($dataPenjualan as $month => $value) {
            $monthNumber = substr($month, 5, 2); // Extract the month part
            $monthName = $monthNames[$monthNumber] ?? $monthNumber; // Get the Indonesian month name or use the numeric month
            $dataPenjualanWithMonthNames[$monthName] = (string) $value;
        }

        // Extract only the values
        $dataPenjualanValues = array_values($dataPenjualanWithMonthNames);

        // Extract only the keys (months)
        $dataBulan = array_keys($dataPenjualanWithMonthNames);

        return response()->json(
            [
                'success' => true,
                'data' => [
                    'data_penjualan' => $dataPenjualanValues,
                    'bulan' => $dataBulan,
                    'start_date' => $startDate,
                    'endDay' => $endDate,
                    'tahun' => Carbon::now()->year, // Adding the current year to the response
                ],
            ],
            200
        );
    }
}
