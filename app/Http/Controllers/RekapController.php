<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Modal;
use App\Models\Rekap;
use App\Models\Hutang;
use App\Models\Piutang;
use App\Models\Produksi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\BebanKewajiban;
use Illuminate\Support\Facades\App;

class RekapController extends Controller
{

    protected $daftarBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];
    //
    public function index()
    {
        $data = Rekap::all();
        $jumlahUangMasuk = $data->where('tipe_transaksi', 'masuk')->sum('jumlah');
        $jumlahUangKeluar = $data->where('tipe_transaksi', 'keluar')->sum('jumlah');
        $saldoAkhir = $jumlahUangMasuk - $jumlahUangKeluar;
        $jumlahUangMasukFormatted = 'Rp ' . number_format($jumlahUangMasuk, 0, ',', '.');
        $jumlahUangKeluarFormatted = 'Rp ' . number_format($jumlahUangKeluar, 0, ',', '.');
        $saldoAkhirFormatted = 'Rp ' . number_format($saldoAkhir, 0, ',', '.');
        
        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

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
         return $carbonDate->translatedFormat('d F Y'); // Sesuaikan format sesuai kebutuhan
     });
        return view('pages.rekap.index', compact( 'dataPenjualanFormatted',
        'tanggalPenjualanFormatted',
        'jumlahUangMasukFormatted', 'jumlahUangKeluarFormatted','saldoAkhirFormatted' ));
    }

    public function show()
{
    $searchTerm = request('search');

    // Mencari data berdasarkan tipe transaksi 'masuk'
    $query = Rekap::where('tipe_transaksi', 'masuk');
    $type = 'masuk';
    $month = null;
    $year = null;
    // Jika ada istilah pencarian, tambahkan filter untuk produk
    if ($searchTerm) {
        $query->where('sumber', 'like', "%$searchTerm%");
    }
    
    
    // Ambil hasil paginasi
    $data = $query->paginate(10);
    return view('pages.rekap.detail', [
        'data' => $data,
        'type' => $type,
        'month' => $month,
        'daftarBulan' => $this->daftarBulan,
        'year' => $year,
        
    ]);
}

public function filter($type, $year = null,$month = null)
{
    $searchTerm = request('search');

    $query = Rekap::where('tipe_transaksi', $type);

    
    $query->when($month, function ($q) use ($month) {
        return $q->whereMonth('tanggal_transaksi', $month);
    });
    
    if (!is_null($year) && $year !== 'semua') {
        $query->whereYear('tanggal_transaksi', $year);
    }

    // Jika ada istilah pencarian, tambahkan filter
    if ($searchTerm) {
        $query->where('sumber', 'like', "%$searchTerm%");
    }
    
    $totalUang = $query->sum('jumlah');
    $totalUangFormatted = 'Rp ' . number_format($totalUang, 0, ',', '.');
    // Ambil hasil query
    $data = $query->paginate(10);
    return view('pages.rekap.detail', [
        'data' => $data,
        'type' => $type,
        'month' => $month,
        'daftarBulan' => $this->daftarBulan,
        'totalUangFormatted' => $totalUangFormatted,
        'year' => $year,
        
    ]);

}


    public function cetak(){
        
        $data = Rekap::all();

        $totalMasuk = $data->where('tipe_transaksi', 'masuk')->sum('jumlah');
    $totalKeluar = $data->where('tipe_transaksi', 'keluar')->sum('jumlah');
    $saldoAkhir = $totalMasuk - $totalKeluar;
        return view('pages.rekap.cetak-rekap', compact('data','totalMasuk','totalKeluar','saldoAkhir'));
    }
}
