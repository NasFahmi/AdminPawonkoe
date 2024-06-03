<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreHutangRequest;
use App\Http\Requests\UpdateHutangRequest;
use Illuminate\Http\Request;
use App\Models\CicilanHutang;

class HutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $searchTerm = request('search');

        $data = Hutang::with('hutang_cicilan')->where('nama', 'like', "%$searchTerm%")
            ->paginate(10);
        // $jumlahHutangBelumDibayar = Hutang::with('hutang_cicilan')->get();
        // dd($jumlahHutangBelumDibayar);
        // dd($data);

        return view('pages.hutang.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.hutang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'jumlahHutang' => 'required|numeric|min:1',
            'nominal' => 'required|numeric|min:1',
            'tenggat_waktu' => 'required',
            'status_cicilan' => 'required|in:0,1',
        ]);
        if ($request->nominal > $request->jumlahHutang) {
            return back()->withErrors(['nominal' => 'Nominal cicilan awal tidak boleh lebih dari jumlah hutang.'])->withInput();
        }

        try {
            DB::beginTransaction();
            $tanggalLunas = $request->tanggal_lunas;
            if (isset($request->tanggal_lunas)) {
                $tanggalLunas = Carbon::parse($validatedData['tanggal_lunas'], 'Asia/Jakarta')->format('Y-m-d');
            }
            $tenggatWaktu = Carbon::parse($validatedData['tenggat_waktu'], 'Asia/Jakarta')->format('Y-m-d');

            // Membuat data hutang
            $hutang = Hutang::create([
                'nama' => $validatedData['nama'],
                'catatan' => $validatedData['catatan'],
                'status' => $validatedData['status_cicilan'], // Sesuaikan nama kolom jika berbeda
                'jumlah_hutang' => $validatedData['jumlahHutang'],
                'tanggal_lunas' => $tanggalLunas,
            ]);

            // Membuat data cicilan
            CicilanHutang::create([
                'hutangId' => $hutang->id,
                'nominal' => $validatedData['nominal'],
                'tanggal' => $tenggatWaktu,
                'status' => $validatedData['status_cicilan'], // Sesuaikan nama kolom jika berbeda
            ]);



            DB::commit();

            return redirect()->route('hutang.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // dd($id);
        $hutangData = Hutang::with('hutang_cicilan')->findOrFail($id);
        // dd($hutangData);
        return view('pages.hutang.detail', compact('hutangData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hutang $hutang)
    {
        dd('edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHutangRequest $request, Hutang $hutang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hutang $hutang)
    {
        //
    }
}
