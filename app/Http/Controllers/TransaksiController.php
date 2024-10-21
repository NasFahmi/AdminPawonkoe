<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Rekap;
use App\Models\Pembeli;
use App\Models\Product;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\HistoryProduct;
use App\Events\TransaksiSelesai;
use Illuminate\Support\Facades\DB;
use App\Models\HistoryProductTransaksi;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // TransaksiController.php
    public function index()
    {
        //! add realasi transkasi dan history product transaksi .
        //! with history product transakski otomatis dapat id History product
        //! find history procut berdsarkan id historu product dari tabel historu product transaksi
        //! simpan di variabel dan return view di product nama dan harga

        $data = Transaksi::with(['pembelis', 'history_product_transaksis.history_product', 'methode_pembayaran'])
            ->search(request('search'))
            ->paginate(10);
        return view('pages.admin.transaksi.index', compact('data'));
    }

    public function cetakTransaksi()
    {
        $data = Transaksi::with(['pembelis', 'products', 'methode_pembayaran'])
            ->where('is_complete', 1)
            ->get();
        return view('pages.admin.transaksi.cetak', compact('data'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $data = Product::get();
        // $dataHistory = HistoryProduct::get();
        // dd($data);
        return view('pages.admin.transaksi.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'product' => 'required',
            'methode_pembayaran' => 'required',
            'jumlah' => 'required',
            // 'total' => 'required',
            'is_complete' => 'required',
        ]);

        if ($validator->fails()) {
            // dd($validator->errors() ); // Mencetak pesan kesalahan
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // dd($request->all());
        try {
            DB::beginTransaction();
            $data = $request->all();
            // dd($data);
            $dataTanggal = $request->tanggal;
            $dateTime = Carbon::parse($dataTanggal, 'Asia/Jakarta'); // Ganti 'Asia/Jakarta' sesuai dengan timezone yang sesuai
            // $tanggal = $dateTime->format('Y-m-d');
            // dd($tanggal);
            $totalharga = $request->total;
            $totalHargaTanpaTitik = str_replace(".", "", $totalharga);

            $dataKeterangan = null;
            if (isset($data['keterangan'])) {
                $dataKeterangan = $data['keterangan'];
            }

            // dd($totalharga);
            $transaksi = Transaksi::create([
                "tanggal" => $dataTanggal,
                "product_id" => $data['product'],
                "methode_pembayaran_id" => $data['methode_pembayaran'],
                "jumlah" => $data['jumlah'],
                "total_harga" => $totalHargaTanpaTitik,
                "keterangan" => $dataKeterangan,
                "is_Preorder" => '0',
                "Preorder_id" => null,
                "is_complete" => $data['is_complete'],
            ]);
            //! add data di tabel transakai history product
            //! id transakasi dari $trasnkasi->id
            //! id history product dapat dari HistoryProduct::where(product_id == data['product'])
            $historyProduct = HistoryProduct::where('product_id', $data['product'])->get()->last();
            // dd($historyProduct->id);
            HistoryProductTransaksi::create([
                "transaksi_id" => $transaksi->id,
                "history_product_id" => $historyProduct->id,
            ]);

            if ($transaksi->is_complete == 1) {
                event(new TransaksiSelesai($transaksi->id));
            }

            activity()
                ->causedBy(auth()->user())
                ->performedOn($transaksi)
                ->event('add_transaksi')
                ->withProperties(['id' => $transaksi->id])
                ->log('User ' . auth()->user()->nama . ' add a transaksi');

            $product = Product::findOrFail($data['product']);
            $nama_product = $product->nama_product;
            if ($transaksi->is_complete == 1) {
                Rekap::insert([
                    'tanggal_transaksi' => $dataTanggal,
                    'sumber' => 'Transaksi',
                    'jumlah' => $totalHargaTanpaTitik,
                    'keterangan' => 'Transaksi Produk ' . $nama_product,
                    'id_tabel_asal' => $transaksi->id,
                    'tipe_transaksi' => 'Masuk'
                ]);
            }
            DB::commit();
            return redirect()->route('transaksis.index')->with('success', 'Transaksi has been created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', 'Failed to create transaksi data.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //! with history product transakski otomatis dapat id History product
        //! find history procut berdsarkan id historu product dari tabel historu product transaksi
        //! simpan di variabel dan return view di product nama dan harga

        $data = Transaksi::with(['pembelis', 'history_product_transaksis.history_product', 'methode_pembayaran'])
            ->findOrFail($transaksi->id);
        // dd($data);
        // dd($data->methode_pembayaran->methode_pembayaran);

        // dd($data); // Uncomment this line for debugging
        return view('pages.admin.transaksi.detail', compact('data'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //! with history product transakski otomatis dapat id History product
        //! find history procut berdsarkan id historu product dari tabel historu product transaksi
        //! simpan di variabel dan return view di product nama dan harga

        $dataTransaksi = Transaksi::with(['products', 'methode_pembayaran', 'preorders'])
            ->findOrFail($transaksi->id);
        // dd($dataTransaksi);
        $data = Product::get();


        $productId = $transaksi->product_id;
        // dd($productId);

        $datafotoProduct = Product::with('fotos')->findOrFail($productId);
        // dd($datafotoProduct);


        return view('pages.admin.transaksi.edit', compact('data', 'dataTransaksi', 'datafotoProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {

        try {
            DB::beginTransaction();
            $dataInput = $request->all();
            // dd($request->all());
            // Update Pembeli information


            $dataTransaksi = [
                "is_Preorder" => '0',
                "Preorder_id" => null,
                "is_complete" => $dataInput['is_complete'],
            ];

            $transaksi->update($dataTransaksi);

            if ($transaksi->is_complete == 1) {
                event(new TransaksiSelesai($transaksi->id));
            }

            $product = Product::findOrFail($dataInput['product']);
            $nama_product = $product->nama_product;
            if ($transaksi->is_complete == 1) {
                Rekap::insert([
                    'tanggal_transaksi' => $transaksi->tanggal,
                    'sumber' => 'Transaksi',
                    'jumlah' => $transaksi->total_harga,
                    'keterangan' => 'Transaksi Produk ' . $nama_product,
                    'id_tabel_asal' => $transaksi->id,
                    'tipe_transaksi' => 'Masuk'
                ]);
            }

            activity()
                ->causedBy(auth()->user())
                ->performedOn($transaksi)
                ->event('update_transaksi')
                ->withProperties(['id' => $transaksi->id])
                ->log('User ' . auth()->user()->nama . ' update a transaksi');

            DB::commit();

            return redirect()->route('transaksis.index')->with('success', 'Transaksi has been updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', 'Failed to update transaksi data.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        try {
            DB::beginTransaction();
            activity()
                ->causedBy(auth()->user())
                ->performedOn($transaksi)
                ->event('delete_transaksi')
                ->withProperties(['data' => $transaksi])
                ->log('User ' . auth()->user()->nama . ' delete a transaksi');

            $transaksi->delete();

            Rekap::where('id_tabel_asal', $transaksi->id)->delete();


            DB::commit();

            return redirect()->route('transaksis.index')->with('success', 'Transaksi has been deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', 'Failed to delete transaksi data.');
        }
    }

    public function cetakForm()
    {
        return view('pages.admin.transaksi.cetak-transaksi-form');
    }
}
