<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\BebanKewajiban;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreBebanKewajibanRequest;
use App\Http\Requests\UpdateBebanKewajibanRequest;

class BebanKewajibanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.beban-kewajiban.index');
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
        if ($request->session()->has('user_id')) {
            dd($request->all());
        }else{
            dd($request->all());
        }
        //     $validator = $request->validate([
        //         'jenis' => 'required',
        //         'nama' => 'required',
        //        'nominal' => 'required',
        //         'tanggal' => 'required',
        //     ]);

        //     dd($validator);
        //    if($validator) {
        //     return  redirect()->back()->withErrors($validator)->withInput();
        //    }

        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data = $request->all();
            // dd($data);
            $dataTanggal = $request->tanggal;
            $dateTime = Carbon::parse($dataTanggal, 'Asia/Jakarta'); // Ganti 'Asia/Jakarta' sesuai dengan timezone yang sesuai
            $tanggal = $dateTime->format('Y-m-d');
            // dd($tanggal);

            BebanKewajiban::create([
                'jenis' => $data['jenis'],
                'nama' => $data['nama'],
                'nominal' => $data['nominal'],
                'tanggal' => $tanggal
            ]);
            DB::commit();
            return redirect()->route('beban-kewajiban.index')->with('success', 'Data Berhasilahkan');
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
