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
        $searchTerm = request('search');

        $data = Produksi::where('produk', 'like', "%$searchTerm%")
            ->paginate(10);
        return view('pages.produksi.index', compact('data'));
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
            'produk' => 'required|string|max:20|min:3',
            'volume' => 'required|numeric|min:1',  // Ensure volume is numeric
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required',
        ], [
            'produk.required' => 'Produk harus diisi.',
            'volume.required' => 'Volume harus diisi.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'produk.min' => 'Produk minimal 3 karakter.',
            'produk.max' => 'Produk maksimal 20 karakter.',
            'volume.numeric' => 'Volume harus berupa angka.',
            'volume.min' => 'Volume minimal 1.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 1.',
        ]);

        try {
            DB::beginTransaction();
            // dd($validatedData);
            $dateTime = Carbon::parse($validatedData['tanggal'], 'Asia/Jakarta');
            $tanggal = $dateTime->format('Y-m-d');

            $produksi = Produksi::create([
                'produk' => $validatedData['produk'],
                'volume' => (float) $validatedData['volume'],  // Cast volume to float
                'jumlah' => $validatedData['jumlah'],
                'tanggal' => $tanggal
            ]);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($produksi)
                ->event('add_produksi')
                ->withProperties(['id' => $produksi->id])
                ->log('User ' . auth()->user()->nama . ' add a produksi');

            DB::commit();
            return redirect()->route('produksi.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->with('error', 'Gagal menyimpan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produksi $produksi)
    {
        $data = Produksi::findorFail($produksi->id);
        return view('pages.produksi.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produksi $produksi)
    {
        $validatedData = $request->validate([
            'produk' => 'required|string|max:20|min:4',
            'volume' => 'required|numeric|min:1',  // Ensure volume is numeric
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required',
        ], [
            'produk.required' => 'Produk harus diisi.',
            'volume.required' => 'Volume harus diisi.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'produk.min' => 'Produk minimal 4 karakter.',
            'produk.max' => 'Produk maksimal 20 karakter.',
            'volume.numeric' => 'Volume harus berupa angka.',
            'volume.min' => 'Volume minimal 1.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 1.',
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

            activity()
                ->causedBy(auth()->user())
                ->performedOn($data)
                ->withProperties(['id' => $data->id])
                ->event('update_produksi')
                ->log('User ' . auth()->user()->nama . ' update a produksi');

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

            activity()
                ->causedBy(auth()->user())
                ->performedOn($produksi)
                ->withProperties(['data' => $produksi])
                ->event('delete_produksi')
                ->log('User ' . auth()->user()->nama . ' delete a produksi');

            $produksi->delete();

            DB::commit();
            return redirect()->route('produksi.index')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat delete data');
        }
    }
}
