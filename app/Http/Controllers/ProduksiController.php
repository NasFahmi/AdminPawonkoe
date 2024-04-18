<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProduksiRequest;
use App\Http\Requests\UpdateProduksiRequest;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Produksi::paginate(10);
        return view('pages.produksi.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.produksi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'produk' => 'required',
            'volume' => 'required',
            'jumlah' => 'required',
            'tanggal' => 'required',
        ], [
            'produk.required' => 'Produk harus diisi.',
            'volume.required' => 'Volume harus diisi.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
        ]);

        try {
            DB::beginTransaction();

            $dateTime = Carbon::parse($validatedData['tanggal'], 'Asia/Jakarta');
            $tanggal = $dateTime->format('Y-m-d');

            Produksi::create([
                'produk' => $validatedData['produk'],
                'volume' => $validatedData['volume'],
                'jumlah' => $validatedData['jumlah'],
                'tanggal' => $tanggal
            ]);

            DB::commit();
            return redirect()->route('produksi.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->with('error', 'Gagal menyimpan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Produksi $produksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produksi $produksi)
    {
        $data = Produksi::findorFail($produksi->id);
        return view('pages.produksi.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produksi $produksi)
    {
        $validatedData = $request->validate([
            'produk' => 'required',
            'volume' => 'required',
            'jumlah' => 'required',
            'tanggal' => 'required',
        ], [
            'produk.required' => 'Produk harus diisi.',
            'volume.required' => 'Volume harus diisi.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
        ]);
        try {
            DB::beginTransaction();
            $data = produksi::findorFail($produksi->id);
            $dateTime = Carbon::parse($validatedData['tanggal'], 'Asia/Jakarta');
            $tanggal = $dateTime->format('Y-m-d');
            $data->update([
                'produk' => $validatedData['produk'],
                'volume' => $validatedData['volume'],
                'jumlah' => $validatedData['jumlah'],
                'tanggal' => $tanggal
            ]);


            DB::commit();
            return redirect()->route('produksi.index')->with('success', 'Data Berhasil Diupdate');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat megnupdate data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produksi $produksi)
    {
        try {
            DB::beginTransaction();
            $produksi->delete();

            DB::commit();
            return redirect()->route('beban-kewajibans.index')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat delete data');
        }
    }
}
