<?php

namespace App\Http\Controllers;

use App\Models\Modal;
use App\Http\Requests\StoreModalRequest;
use App\Http\Requests\UpdateModalRequest;
use App\Models\JenisModal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ModalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Modal::with(['jenis_modal'])->get();

        return view('pages.modal.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = JenisModal::all();
        //dd($data);
        return view('pages.modal.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'jenis' => 'required',
            'nama' => 'required',
            'nominal' => 'required|numeric',
            'penyedia' => 'required',
            'jumlah' => 'required|numeric',
            'tanggal' => 'required',
        ], [
            'jenis.required' => 'Jenis harus diisi.',
            'nama.required' => 'Nama harus diisi.',
            'nominal.required' => 'Nominal harus diisi.',
            'Penyedia.required' => 'penyedia harus diisi.',
            'jumlah.required' => 'jumlah harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
        ]);

        $dateTime = Carbon::parse($validatedData['tanggal'], 'Asia/Jakarta');
        $tanggal = $dateTime->format('Y-m-d');
        // $jenis =  $validatedData['jenis'];

        $modal = Modal::create([
            'jenis_modal_id' => $validatedData['jenis'],
            'nama' => $validatedData['nama'],
            'nominal' => $validatedData['nominal'],
            'penyedia' => $validatedData['penyedia'],
            'jumlah' => $validatedData['jumlah'],
            'tanggal' => $tanggal
        ]);
        return redirect()->route('modal.index')->with('success', 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Modal $modal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modal $modal)
    {
        $data = Modal::with('jenis_modal')->findOrFail($modal->id);
        return view('pages.modal.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Modal $modal)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'jenis' => 'required',
            'nama' => 'required',
            'nominal' => 'required|numeric',
            'penyedia' => 'required',
            'jumlah' => 'required|numeric',
            'tanggal' => 'required',
        ], [
            'jenis.required' => 'Jenis harus diisi.',
            'nama.required' => 'Nama harus diisi.',
            'nominal.required' => 'Nominal harus diisi.',
            'penyedia.required' => 'Penyedia harus diisi.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
        ]);

        // Parse and format the date
        $dateTime = Carbon::parse($validatedData['tanggal'], 'Asia/Jakarta');
        $tanggal = $dateTime->format('Y-m-d');

        // Update the Modal instance with the validated data
        $modal->update([
            'jenis_modal_id' => $validatedData['jenis'],
            'nama' => $validatedData['nama'],
            'nominal' => $validatedData['nominal'],
            'penyedia' => $validatedData['penyedia'],
            'jumlah' => $validatedData['jumlah'],
            'tanggal' => $tanggal,
        ]);

        // Redirect back to the index page with a success message
        return redirect()->route('modal.index')->with('success', 'Data Berhasil Diupdate');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modal $modal)
    {
        //
    }
}
