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



        // JANGAN DIUBAH LURR
        $data = Rekap::all();
        $dataDanaMasuk = $data->where('tipe_transaksi', 'masuk');
        $dataDanaKeluar = $data->where('tipe_transaksi', 'keluar');
        $jumlahUangMasuk = $dataDanaMasuk->sum('jumlah');
        $jumlahUangKeluar = $dataDanaKeluar->sum('jumlah');
        $saldoAkhir = $jumlahUangMasuk - $jumlahUangKeluar;
        $jumlahUangMasukFormatted = 'Rp ' . number_format($jumlahUangMasuk, 0, ',', '.');
        $jumlahUangKeluarFormatted = 'Rp ' . number_format($jumlahUangKeluar, 0, ',', '.');
        $saldoAkhirFormatted = 'Rp ' . number_format($saldoAkhir, 0, ',', '.');

        // dd($dataDanaMasuk);
        /**
         * @var 
         * "id" => 1
            "tanggal_transaksi" => "2024-09-04"
            "sumber" => "Transaksi"
            "jumlah" => "2462.00"
            "keterangan" => "Transaksi Produk awd"
            "id_tabel_asal" => 1
            "tipe_transaksi" => "masuk"
            "created_at" => null
            "updated_at" => null
         */
        // dd($dataDanaKeluar);
        /**
         * "id" => 4
            "tanggal_transaksi" => "2024-10-21"
            "sumber" => "Hutang"
            "jumlah" => "123.00"
            "keterangan" => "Pembayaran Hutang ke pawonkoe"
            "id_tabel_asal" => 1
            "tipe_transaksi" => "keluar"
            "created_at" => null
            "updated_at" => null
         */

        //chart pendapatan 
        $daftarBulan = $this->daftarBulan;
        return view('pages.rekap.index', compact(

            'currentYear',
            'daftarBulan',


            'jumlahUangMasukFormatted',
            'jumlahUangKeluarFormatted',
            'saldoAkhirFormatted'
        ));
    }

    public function filterRekap(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');

        // Kondisi: Jika hanya filter berdasarkan tahun (bulan = '-')
        if ($bulan == '-') {
            // Array untuk menampung saldo per bulan (Januari-Desember)
            $saldoPerBulan = [];

            for ($i = 1; $i <= 12; $i++) {
                // Filtering data per bulan
                $dataBulan = Rekap::whereYear('tanggal_transaksi', $tahun)
                    ->whereMonth('tanggal_transaksi', $i)
                    ->get();

                // Menghitung total uang masuk dan keluar untuk bulan tersebut
                $dataDanaMasuk = $dataBulan->where('tipe_transaksi', 'masuk')->sum('jumlah');
                $dataDanaKeluar = $dataBulan->where('tipe_transaksi', 'keluar')->sum('jumlah');

                // Hitung saldo untuk bulan tersebut (dana masuk - dana keluar)
                $saldoBulan = $dataDanaMasuk - $dataDanaKeluar;

                // Simpan saldo bulan tersebut ke array
                $saldoPerBulan[] = $saldoBulan;
            }

            // Response JSON saldo per bulan dari Januari sampai Desember
            return response()->json([
                'saldoAkhir' => $saldoPerBulan, // Misalnya: [10000, 5000, 0, 2000, ...] untuk Januari-Desember
                'date' => $this->daftarBulan,
            ]);
        }

        // Kondisi: Jika ada input bulan (filter berdasarkan tahun dan bulan)
        else {
            // Array untuk menampung saldo harian dan hari dalam bulan tersebut
            $saldoPerHari = [];
            $hariPerBulan = [];

            // Mendapatkan jumlah hari dalam bulan tersebut
            $jumlahHari = Carbon::create($tahun, $bulan)->daysInMonth;

            for ($i = 1; $i <= $jumlahHari; $i++) {
                // Filtering data per hari
                $dataHari = Rekap::whereYear('tanggal_transaksi', $tahun)
                    ->whereMonth('tanggal_transaksi', $bulan)
                    ->whereDay('tanggal_transaksi', $i)
                    ->get();

                // Menghitung total uang masuk dan keluar untuk hari tersebut
                $dataDanaMasuk = $dataHari->where('tipe_transaksi', 'masuk')->sum('jumlah');
                $dataDanaKeluar = $dataHari->where('tipe_transaksi', 'keluar')->sum('jumlah');

                // Hitung saldo untuk hari tersebut (dana masuk - dana keluar)
                $saldoHari = $dataDanaMasuk - $dataDanaKeluar;

                // Simpan saldo dan hari tersebut ke array
                $saldoPerHari[] = $saldoHari;

                // Menyimpan hari dalam format 'YYYY-MM-DD'
                $hariPerBulan[] = Carbon::create($tahun, $bulan, $i)->toDateString();
            }

            // Response JSON saldo per hari dan tanggal dalam bulan tersebut
            return response()->json([
                'saldoAkhir' => $saldoPerHari, // Misalnya: [1000, 0, 5000, -2000, ...] untuk tanggal 1-30/31
                'date' => $hariPerBulan,       // Misalnya: ['2024-10-01', '2024-10-02', ...]
            ]);
        }

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