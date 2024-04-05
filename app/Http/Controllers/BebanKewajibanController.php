<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\BebanKewajiban;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreBebanKewajibanRequest;
use App\Http\Requests\UpdateBebanKewajibanRequest;
use Illuminate\Support\Facades\Validator;

class BebanKewajibanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = BebanKewajiban::all();
        return view('pages.beban-kewajiban.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.beban-kewajiban.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBebanKewajibanRequest $request)
    {
        $validatedData = $request->validate([
            'jenis' => 'required',
            'nama' => 'required',
            'nominal' => 'required',
            'tanggal' => 'required',
        ], [
            'jenis.required' => 'Jenis harus diisi.',
            'nama.required' => 'Nama harus diisi.',
            'nominal.required' => 'Nominal harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
        ]);

        try {
            DB::beginTransaction();

            $dateTime = Carbon::parse($validatedData['tanggal'], 'Asia/Jakarta');
            $tanggal = $dateTime->format('Y-m-d');

            BebanKewajiban::create([
                'jenis' => $validatedData['jenis'],
                'nama' => $validatedData['nama'],
                'nominal' => $validatedData['nominal'],
                'tanggal' => $tanggal
            ]);

            DB::commit();
            return redirect()->route('beban-kewajibans.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', 'Gagal menyimpan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BebanKewajiban $bebanKewajiban)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BebanKewajiban $bebanKewajiban)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBebanKewajibanRequest $request, BebanKewajiban $bebanKewajiban)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BebanKewajiban $bebanKewajiban)
    {
        //
    }
}
