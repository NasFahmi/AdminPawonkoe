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
            ->limit(5)
            ->get();

        $productRecently = Product::with('fotos', 'transaksis')
            ->where('tersedia', operator: 1)
            ->latest()->limit(5)->get();

        $productZeroStok = Product::with('fotos')->get();

        $foto = Foto::join('transaksis', 'fotos.product_id', '=', 'transaksis.product_id')
            ->where('transaksis.is_complete', 0)
            ->select('fotos.product_id', DB::raw('MAX(fotos.id) as id'), DB::raw('MAX(fotos.foto) as foto'))
            ->groupBy('fotos.product_id')
            ->get();


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
            'productZeroStok'
        ));
    }
    
}
