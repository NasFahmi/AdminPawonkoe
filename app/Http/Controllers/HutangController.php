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
        // dd($request->all());
        //selesai
        // "nama" => "Ariq"
        // "catatan" => "awdwad"
        // "status" => "1"
        // "jumlahHutang" => "200000"
        // "tanggal_lunas" => "06/14/2024"
        // "tenggat_waktu" => null
        // "nominal" => null
        //belum selesai
        // "nama" => "Ariq"
        // "catatan" => "ad"
        // "status" => "0"
        // "jumlahHutang" => "200000"
        // "tanggal_lunas" => null
        // "tenggat_waktu" => "06/14/2024"
        // "nominal" => "100000"
        // Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'jumlahHutang' => 'required|numeric|min:1',
            'tanggal_lunas' => 'nullable',
            'tenggat_waktu' => 'nullable',
            'nominal' => 'nullable|numeric|min:1',
            'status' => 'required|in:0,1',
        ]);
        if ($request->nominal > $request->jumlahHutang) {
            return back()->withErrors(['nominal' => 'Nominal cicilan awal tidak boleh lebih dari jumlah hutang.'])->withInput();
        }

        try {
            DB::beginTransaction();
            // lunas
            if ($validatedData['tanggal_lunas'] != null) {

                $tanggalLunas = Carbon::parse($validatedData['tanggal_lunas'], 'Asia/Jakarta')->format('Y-m-d');
                // Membuat data hutang
                Hutang::create([
                    'nama' => $validatedData['nama'],
                    'catatan' => $validatedData['catatan'],
                    'status' => $validatedData['status'], // Sesuaikan nama kolom jika berbeda
                    'jumlah_hutang' => $validatedData['jumlahHutang'],
                    'tenggat_waktu' => null,
                    'tanggal_lunas' => $tanggalLunas,
                ]);
            }



            if ($validatedData['tenggat_waktu'] != null) {
                $tenggatWaktu = Carbon::parse($validatedData['tenggat_waktu'], 'Asia/Jakarta')->format('Y-m-d');
                // Membuat data cicilan
                $hutang = Hutang::create([
                    'nama' => $validatedData['nama'],
                    'catatan' => $validatedData['catatan'],
                    'status' => $validatedData['status'], // Sesuaikan nama kolom jika berbeda
                    'jumlah_hutang' => $validatedData['jumlahHutang'],
                    'tenggat_waktu' => $tenggatWaktu,
                    'tanggal_lunas' => null,
                ]);
                CicilanHutang::create([
                    'hutangId' => $hutang->id,
                    'nominal' => $validatedData['nominal'],
                ]);
            }




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
        return view('pages.hutang.edit', compact('hutang'));
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
