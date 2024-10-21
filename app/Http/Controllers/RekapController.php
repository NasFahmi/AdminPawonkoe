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
    public function index()
    {
        // Mendapatkan bulan dan tahun saat ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Total uang masuk dari transaksi
        $uangMasukTransaksi = Transaksi::where('is_complete', 1)
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('total_harga');

        // Mengambil data modal
        $uangMasukModal = Modal::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('nominal');

        // Total uang masuk
        $jumlahUangMasuk = $uangMasukTransaksi + $uangMasukModal;

        // Total uang keluar dari hutang yang dibayar
        $uangKeluarHutang = Hutang::whereMonth('tanggal_lunas', $currentMonth)
            ->whereYear('tanggal_lunas', $currentYear)
            ->sum('jumlah_hutang');

        // Mengambil data beban kewajiban
        $uangKeluarBebanKewajiban = BebanKewajiban::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('nominal');

        // Total uang keluar
        $jumlahUangKeluar = $uangKeluarHutang + $uangKeluarBebanKewajiban;

        // Menghitung saldo akhir
        $saldoAkhir = $jumlahUangMasuk - $jumlahUangKeluar;

        // Format uang
        $jumlahUangMasukFormatted = 'Rp ' . number_format($jumlahUangMasuk, 0, ',', '.');
        $jumlahUangKeluarFormatted = 'Rp ' . number_format($jumlahUangKeluar, 0, ',', '.');
        $saldoAkhirFormatted = 'Rp ' . number_format($saldoAkhir, 0, ',', '.');

        // Mendapatkan nama bulan dalam bahasa Indonesia
        $currentMonthInIndo = Carbon::now()->locale('id')->translatedFormat('F');

        // Membuat daftar lengkap tanggal dari awal hingga akhir bulan
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $allDates = [];

        // Loop untuk membuat daftar tanggal di bulan ini
        $currentDate = $startOfMonth->copy();
        while ($currentDate->lte($endOfMonth)) {
            $allDates[] = $currentDate->copy()->format('Y-m-d');
            $currentDate->addDay();
        }

        // Mengambil data penjualan yang complete untuk bulan ini
        $dataPenjualan = Transaksi::where('is_complete', 1)
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->orderBy('tanggal', 'asc')
            ->selectRaw('tanggal, sum(total_harga) as total_penjualan')
            ->groupBy('tanggal')
            ->pluck('total_penjualan', 'tanggal');

        // Format data penjualan
        $dataPenjualanFormatted = [];
        $tanggalPenjualanFormatted = []; // Deklarasi variabel tanggalPenjualanFormatted

        foreach ($dataPenjualan as $tanggal => $total) {
            $dataPenjualanFormatted[$tanggal] = [
                'tanggal' => Carbon::parse($tanggal)->translatedFormat('d M'),
                'total_penjualan' => 'Rp ' . number_format($total, 0, ',', '.')
            ];
            $tanggalPenjualanFormatted[] = Carbon::parse($tanggal)->translatedFormat('d M'); // Mengisi array tanggalPenjualanFormatted
        }

        // Mengambil data modal
        $dataModal = Modal::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->selectRaw('tanggal, sum(nominal) as total_modal')
            ->groupBy('tanggal')
            ->pluck('total_modal', 'tanggal');

        // Mengambil data hutang yang dibayar
        $dataHutang = Hutang::whereMonth('tanggal_lunas', $currentMonth)
            ->whereYear('tanggal_lunas', $currentYear)
            ->selectRaw('tanggal_lunas as tanggal, sum(jumlah_hutang) as total_hutang')
            ->groupBy('tanggal')
            ->pluck('total_hutang', 'tanggal');

        // Mengambil data beban kewajiban
        $dataBebanKewajiban = BebanKewajiban::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->selectRaw('tanggal, sum(nominal) as total_beban_kewajiban')
            ->groupBy('tanggal')
            ->pluck('total_beban_kewajiban', 'tanggal');

        // Menggabungkan hasil ke dalam array untuk kalkulasi
        $allDates = array_merge($dataPenjualan->keys()->toArray(), $dataModal->keys()->toArray(), $dataHutang->keys()->toArray(), $dataBebanKewajiban->keys()->toArray());
        $uniqueDates = array_unique($allDates);

        $dataHasil = [];

        // Mengisi data hasil
        foreach ($uniqueDates as $date) {
            $totalMasuk = ($dataPenjualan[$date] ?? 0) + ($dataModal[$date] ?? 0);
            $totalKeluar = ($dataHutang[$date] ?? 0) + ($dataBebanKewajiban[$date] ?? 0);
            $saldo = $totalMasuk - $totalKeluar;

            $dataHasil[$date] = [
                'total_masuk' => $totalMasuk,
                'total_keluar' => $totalKeluar,
                'saldo' => $saldo
            ];
        }

        // Mengambil data untuk grafik
        $dataSaldoFormatted = [];
        $tanggalFormatted = [];

        foreach ($dataHasil as $tanggal => $result) {
            $tanggalFormatted[] = Carbon::parse($tanggal)->translatedFormat('d M');
            $dataSaldoFormatted[] = $result['saldo'];
        }

        // Mengirim data ke view
        return view('pages.rekap.index', compact(
            'currentMonthInIndo',
            'currentYear',
            'dataPenjualanFormatted',
            'tanggalPenjualanFormatted', // Mengirimkan variabel tanggalPenjualanFormatted
            'tanggalFormatted',
            'dataSaldoFormatted',
            'jumlahUangMasukFormatted',
            'jumlahUangKeluarFormatted',
            'saldoAkhirFormatted'
        ));
    }

    public function show()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $searchTerm = request('search');

        // Mencari data berdasarkan tipe transaksi 'masuk'
        $query = Rekap::where('tipe_transaksi', 'masuk');
        $type = 'masuk';
        $month = null;
        // Jika ada istilah pencarian, tambahkan filter untuk produk
        if ($searchTerm) {
            $query->where('sumber', 'like', "%$searchTerm%");
        }
        // Ambil hasil paginasi
        $data = $query->paginate(10);
        return view('pages.rekap.detail', compact('data', 'type', 'month'));
    }

    public function filter($type, $month = null)
    {
        $searchTerm = request('search');

        // Membangun query berdasarkan tipe transaksi
        $query = Rekap::where('tipe_transaksi', $type);

        if ($month) {
            $query = Rekap::where('tipe_transaksi', $type)
                ->whereMonth('tanggal_transaksi', $month);
            // dd($query , $month);

        }
        // Jika ada istilah pencarian, tambahkan filter
        if ($searchTerm) {
            $query->where('sumber', 'like', "%$searchTerm%");
        }

        // Ambil hasil query
        $data = $query->paginate(10);

        return view('pages.rekap.detail', compact('data', 'type', 'month'));
    }

    public function cetak()
    {
        $data = Rekap::all();
        $totalMasuk = $data->where('tipe_transaksi', 'masuk')->sum('jumlah');
        $totalKeluar = $data->where('tipe_transaksi', 'keluar')->sum('jumlah');
        $saldoAkhir = $totalMasuk - $totalKeluar;
        return view('pages.rekap.cetak-rekap', compact('data', 'totalMasuk', 'totalKeluar', 'saldoAkhir'));
    }
}