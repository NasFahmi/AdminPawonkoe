<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use Illuminate\Http\Request;
use App\Models\CicilanHutang;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCicilanHutangRequest;
use App\Http\Requests\UpdateCicilanHutangRequest;

class CicilanHutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $hutangData = Hutang::with('hutang_cicilan')->findOrFail($id);
        return view('pages.cicilan.create', compact('hutangData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $hutangData = Hutang::with('hutang_cicilan')->findOrFail($id);

        // Memeriksa apakah nominal cicilan lebih besar dari sisa hutang
        $sisaHutang = $hutangData->jumlah_hutang - $hutangData->hutang_cicilan->sum('nominal');
        if ($request->nominal > $sisaHutang) {
            return back()->withErrors(['nominal' => 'Nominal cicilan awal tidak boleh lebih dari sisa hutang. Maksimal adalah ' . $sisaHutang])->withInput();
        }

        try {
            DB::beginTransaction();

            // Menambahkan cicilan hutang baru
            CicilanHutang::create([
                'nominal' => $request->nominal,
                'hutangId' => $id,
            ]);

            // Menghitung ulang total cicilan setelah penambahan cicilan baru
            $totalCicilan = $hutangData->hutang_cicilan->sum('nominal') + $request->nominal;

            if ($totalCicilan >= $hutangData->jumlah_hutang) {
                // Ketika hutang lunas
                $DateNow = \Carbon\Carbon::now();
                $hutangData->update([
                    'status' => '1',
                    'tanggal_lunas' => $DateNow,
                    'tenggat_waktu' => null,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data Tidak Berhasil Disimpan');
        }
    }

}
