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
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $uangMasukTransaksi = Transaksi::where('is_complete', 1)
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('total_harga');

        $uangMasukModal = Modal::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('nominal');

        $jumlahUangMasuk = $uangMasukTransaksi + $uangMasukModal;

        $uangKeluarHutang = Hutang::whereMonth('tanggal_lunas', $currentMonth)
            ->whereYear('tanggal_lunas', $currentYear)
            ->sum('jumlah_hutang');

        $uangKeluarBebanKewajiban = BebanKewajiban::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('nominal');

        $jumlahUangKeluar = $uangKeluarHutang + $uangKeluarBebanKewajiban;

        $saldoAkhir = $jumlahUangMasuk - $jumlahUangKeluar;

        $jumlahUangMasukFormatted = 'Rp ' . number_format($jumlahUangMasuk, 0, ',', '.');
        $jumlahUangKeluarFormatted = 'Rp ' . number_format($jumlahUangKeluar, 0, ',', '.');
        $saldoAkhirFormatted = 'Rp ' . number_format($saldoAkhir, 0, ',', '.');

        $currentMonthInIndo = Carbon::now()->locale('id')->translatedFormat('F');

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $allDates = [];

        $currentDate = $startOfMonth->copy();
        while ($currentDate->lte($endOfMonth)) {
            $allDates[] = $currentDate->copy()->format('Y-m-d');
            $currentDate->addDay();
        }

        $dataPenjualan = Transaksi::where('is_complete', 1)
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->orderBy('tanggal', 'asc')
            ->selectRaw('tanggal, sum(total_harga) as total_penjualan')
            ->groupBy('tanggal')
            ->pluck('total_penjualan', 'tanggal');

        $dataModal = Modal::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->selectRaw('tanggal, sum(nominal) as total_modal')
            ->groupBy('tanggal')
            ->pluck('total_modal', 'tanggal');

        $dataHutang = Hutang::whereMonth('tanggal_lunas', $currentMonth)
            ->whereYear('tanggal_lunas', $currentYear)
            ->selectRaw('tanggal_lunas as tanggal, sum(jumlah_hutang) as total_hutang')
            ->groupBy('tanggal')
            ->pluck('total_hutang', 'tanggal');

        $dataBebanKewajiban = BebanKewajiban::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->selectRaw('tanggal, sum(nominal) as total_beban_kewajiban')
            ->groupBy('tanggal')
            ->pluck('total_beban_kewajiban', 'tanggal');

        // Menggabungkan hasil ke dalam array untuk kalkulasi
        $dataHasil = [];

        // Mengisi data hasil berdasarkan semua tanggal di bulan ini
        foreach ($allDates as $date) {
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

        return view('pages.rekap.index', compact(
            'currentMonthInIndo',
            'currentYear',
            'tanggalFormatted',
            'dataSaldoFormatted',
            'jumlahUangMasukFormatted',
            'jumlahUangKeluarFormatted',
            'saldoAkhirFormatted'
        ));
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

    public function filter($type, $year = null, $month = null)
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
    public function chart()
    {
        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dataPenjualan = Transaksi::where('is_complete', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as month, sum(total_harga) as total_penjualan')
            ->groupBy('month')
            ->orderBy('month', 'asc') // Ganti 'tanggal' dengan 'month' untuk ORDER BY
            ->pluck('total_penjualan', 'month')
            ->toArray();

        // Buat array untuk memetakan bulan numerik ke nama bulan dalam bahasa Indonesia
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

        // Inisialisasi array dengan nilai 0 untuk setiap bulan
        $dataPenjualanWithMonthNames = array_fill_keys(array_values($monthNames), "0");

        // Gantikan bulan numerik dengan nama bulan dalam bahasa Indonesia dan perbarui nilainya
        foreach ($dataPenjualan as $month => $value) {
            $monthNumber = substr($month, 5, 2); // Ambil bagian bulan
            $monthName = $monthNames[$monthNumber] ?? $monthNumber; // Dapatkan nama bulan Indonesia atau gunakan bulan numerik
            $dataPenjualanWithMonthNames[$monthName] = (string) $value;
        }

        // Ekstrak hanya nilainya
        $dataPenjualanValues = array_values($dataPenjualanWithMonthNames);

        // Ekstrak hanya kunci (bulan)
        $dataBulan = array_keys($dataPenjualanWithMonthNames);

        return response()->json(
            [
                'success' => true,
                'data' => [
                    'data_penjualan' => $dataPenjualanValues,
                    'bulan' => $dataBulan,
                    'start_date' => $startDate,
                    'endDay' => $endDate,
                    'tahun' => Carbon::now()->year, // Tambahkan tahun saat ini ke respons
                ],
            ],
            200
        );
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