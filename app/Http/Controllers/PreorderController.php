<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Pembeli;
use App\Models\Product;
use App\Models\Preorder;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\HistoryProduct;
use App\Events\TransaksiSelesai;
use Illuminate\Support\Facades\DB;
use App\Models\HistoryProductTransaksi;
use App\Http\Requests\StorePreorderRequest;
use App\Http\Requests\UpdatePreorderRequest;

class PreorderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Transaksi::with(['pembelis', 'products', 'methode_pembayaran', 'preorders'])
            ->where('is_Preorder', 1)
            ->search(request('search'))
            ->paginate(7);
        $totalPreorder = Transaksi::where('is_Preorder', 1)
            ->where('is_complete', 0)
            ->sum('is_Preorder');
        // dd($data);
        $totalDP = Preorder::whereIn('id', function ($query) {
            $query->select('Preorder_id')
                ->from('transaksis')
                ->where('is_complete', 0);
        })
            ->sum('down_payment');

        $totalHargaBelumLunas = Transaksi::where('is_complete', 0)
            ->where('is_Preorder', 1)
            ->sum('total_harga');

        $totalDPBelumLunas = $totalHargaBelumLunas - $totalDP;
        // dd($data);

        return view('pages.admin.preorder.index', compact('data', 'totalPreorder', 'totalDP', 'totalDPBelumLunas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Product::get();
        $dataHistory = HistoryProduct::get();
        return view('pages.admin.preorder.create', compact('data', 'dataHistory'));
    }


    public function show($id)
    {

        $data = Transaksi::with(['pembelis', 'products', 'methode_pembayaran', 'preorders'])
            ->findOrFail($id);
        // dd($data->methode_pembayaran->methode_pembayaran);

        // dd($data); // Uncomment this line for debugging
        return view('pages.admin.preorder.detail', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'tanggal' => 'required',
            'jumlah' => 'required',
            'total' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'telepon' => 'required|digits:12',
            'tanggal_dp' => 'required',
            'jumlah_dp' => 'required'
            // bisa iya bisa tidak jika iya ada tanggal_dp dan jumlah_dp
            // opsional
            // tanggal_dp
            // jumlah_dp
        ], [
            'telepon.digits' => 'Nomor telepon harus terdiri dari 12 digit.',
        ]);

        try {
            DB::beginTransaction();
            $data = $request->all();

            $dataTanggal = $request->tanggal;
            $dateTime = DateTime::createFromFormat('d/m/Y', $dataTanggal);
            $tanggal = $dateTime->format('Y-m-d');

            $totalharga = $request->total;
            $totalHargaTanpaTitik = str_replace(".", "", $totalharga);

            $jumlahDP = $request->jumlah_dp;
            $jumlahDPTanpaTitik = $jumlahDP ? str_replace(".", "", $jumlahDP) : 0;

            $dataTanggalDP = $request->tanggal_dp;
            $dateTimeTanggalDp = DateTime::createFromFormat('d/m/Y', strval($dataTanggalDP));
            $tanggalDP = $dateTimeTanggalDp->format('Y-m-d');


            $dataPembeli = Pembeli::create([
                "nama" => $data['nama'],
                "email" => $data['email'],
                'alamat' => $data['alamat'],
                "no_hp" => $data['telepon'],
            ]);
            $idPembeli = $dataPembeli->id;

            $dataPreorder = Preorder::create([
                'is_DP' => '1',
                'down_payment' => $jumlahDPTanpaTitik,
                'tanggal_pembayaran_down_payment' => $tanggalDP,
            ]);
            $idPreorder = $dataPreorder->id;

            $transaksi = Transaksi::create([
                "tanggal" => $tanggal,
                "pembeli_id" => $idPembeli,
                "product_id" => $data['product'],
                "methode_pembayaran_id" => $data['methode_pembayaran'],
                "jumlah" => $data['jumlah'],
                "total_harga" => $totalHargaTanpaTitik,
                "keterangan" => $data['keterangan'],
                "is_Preorder" => '1',
                "Preorder_id" => $idPreorder,
                "is_complete" => '0',
            ]);
            $historyProduct = HistoryProduct::where('product_id',$data['product'])->get()->last();
            // dd($historyProduct->id);
            HistoryProductTransaksi::create([
                "transaksi_id"=>$transaksi->id,
                "history_product_id"=> $historyProduct->id,
            ]);
            
            DB::commit();
            return redirect()->route('preorders.index')->with('success', 'Transaksi has been created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', 'Failed to create transaksi data.');

        }

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($preorder)
    {
        // dd($preorder);
        $data = Product::get();
        $dataTransaksi = Transaksi::with(['pembelis', 'products', 'methode_pembayaran', 'preorders'])
            ->findOrFail($preorder);
        return view('pages.admin.preorder.edit', compact('data', 'dataTransaksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'is_complete' => 'required',
            'jumlah_dp' => 'required',
            'telepon' => 'required|digits:12',
        ], [
            'telepon.digits' => 'Nomor telepon harus terdiri dari 12 digit.',
        ]);
        try {
            DB::beginTransaction();
            $dataInput = $request->all();
            $preorder = Transaksi::with(['pembelis', 'products', 'methode_pembayaran', 'preorders'])
                ->findOrFail($id);
            $dataPembeli = [
                "no_hp" => $dataInput['telepon'],
            ];

            $preorder->pembelis->update($dataPembeli);
            $totalharga = $request->input('total');
            $totalHargaTanpaTitik = str_replace(".", "", $totalharga);
            //    Belum selesai
            if ($dataInput['is_complete'] == 0) {
                $dataPreorder = [
                    "keterangan" => $dataInput['keterangan'],
                ];
                $preorder->update($dataPreorder);

            } else {
                // sudah selesai
                $dataPreorder = [
                    'is_complete' => '1',
                    "keterangan" => $dataInput['keterangan'],
                    "is_Preorder" => '1',
                    'jumlah_dp' => $dataInput['jumlah_dp'],
                ];
                $preorder->update($dataPreorder);
                event(new TransaksiSelesai($id));

            }
            DB::commit();
            return redirect()->route('preorders.index')->with('success', 'Preorder has been updated successfully');

        } catch (\Throwable $th) {

            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', 'Failed to update transaksi data.');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($preorder)
    {
        try {

            DB::beginTransaction();
            $dataTransaksi = Transaksi::with(['pembelis', 'products', 'methode_pembayaran', 'preorders'])
                ->findOrFail($preorder);

            $dataPembeli = $dataTransaksi->pembelis;

            $dataTransaksi->delete();
            $dataPembeli->delete();

            DB::commit();

            return redirect()->route('preorders.index')->with('success', 'Transaksi has been deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', 'Failed to delete transaksi data.');
        }
    }
}
