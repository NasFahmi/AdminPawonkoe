<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Piutang;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePiutangRequest;
use App\Http\Requests\UpdatePiutangRequest;

class PiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.piutang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.piutang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePiutangRequest $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'nominal' => 'required',
            'tanggal' => 'required',
        ], [
           
            'nama.required' => 'Nama harus diisi.',
            'nominal.required' => 'Nominal harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
        ]);

        try {
            DB::beginTransaction();
            $data = $request->all();
            $dateTime = Carbon::parse($validatedData['tanggal'], 'Asia/Jakarta');
            $tanggal = $dateTime->format('Y-m-d');

            $dataCatatan = null;
            if (isset($data['catatan'])) {
                $dataCatatan = $data['catatan'];
            }
            Piutang::create([
                'nama' => $validatedData['nama'],
                'nominal' => $validatedData['nominal'],
                'catatan' => $dataCatatan,
                'tanggal_disetorkan' => $tanggal,
                'is_complete' => $data['is_complete'],
                'bukti_nota' => $data['image[]'],
            ]);

            DB::commit();
            return redirect()->route('piutang.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', 'Gagal menyimpan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Piutang $piutang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Piutang $piutang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePiutangRequest $request, Piutang $piutang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Piutang $piutang)
    {
        //
    }
}
