<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Rekap;
use Illuminate\Http\Request;
use App\Models\BebanKewajiban;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreBebanKewajibanRequest;
use App\Http\Requests\UpdateBebanKewajibanRequest;

class BebanKewajibanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $searchTerm = request('search');

        $data = BebanKewajiban::where('jenis', 'like', "%$searchTerm%")
            ->orWhere('nama', 'like', "%$searchTerm%")
            ->paginate(10);

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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'jenis' => 'required',
            'nama' => 'required|min:3|max:20|regex:/^[a-zA-Z\s]*$/',
            'nominal' => 'required|numeric|min:1|min_digits:4|max_digits:13|regex:/^[1-9][0-9]*$/',
            'tanggal' => 'required|date_format:Y-m-d',
        ], [
            'jenis.required' => 'Jenis harus diisi.',
            'nama.required' => 'Nama harus diisi.',
            'nominal.required' => 'Nominal harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'nama.min' => 'Nama minimal 3 karakter.',
            'nama.max' => 'Nama maksimal 20 karakter.',
            'nama.regex' => 'Nama harus berupa huruf.',
            'nominal.regex' => 'Nominal harus berupa angka.',
            'tanggal.date_format' => 'Format tanggal harus YYYY-MM-DD.',
            'nominal.numeric' => 'Nominal harus berupa angka.',
            'nominal.min' => 'Nominal harus lebih besar dari 0.',
            'nominal.min_digits' => 'Nominal minimal 4 digit.',
            'nominal.max_digits' => 'Nominal maksimal 13 digit.',
        ]);

        try {
            DB::beginTransaction();
            // dd($validatedData);
            $dateTime = Carbon::parse($validatedData['tanggal'], 'Asia/Jakarta');
            $tanggal = $dateTime->format('Y-m-d');

            $bebanKewajiban = BebanKewajiban::create([
                'jenis' => $validatedData['jenis'],
                'nama' => $validatedData['nama'],
                'nominal' => $validatedData['nominal'],
                'tanggal' => $tanggal
            ]);

            Rekap::insert([
                    'tanggal_transaksi' => $tanggal,
                    'sumber' => 'Beban dan Kewajiban',
                    'jumlah' => $validatedData['nominal'],
                    'keterangan' => 'Pembayaran ' . $validatedData['jenis']. ' untuk ' . $validatedData['nama'],
                    'id_tabel_asal' => $bebanKewajiban->id,
                    'tipe_transaksi' => 'Keluar'
                ]);
    
            activity()
                ->causedBy(auth()->user())
                ->performedOn($bebanKewajiban)
                ->event('add_beban_kewajiban')
                ->withProperties(['id' => $bebanKewajiban->id])
                ->log('User ' . auth()->user()->nama . ' add a new beban kewajiban');

            DB::commit();
            return redirect()->route('beban-kewajibans.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
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
        $data = BebanKewajiban::findorFail($bebanKewajiban->id);
        return view('pages.beban-kewajiban.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BebanKewajiban $bebanKewajiban)
    {
        $validatedData = $request->validate([
            'jenis' => 'required',
            'nama' => 'required|min:3|max:20|regex:/^[a-zA-Z\s]*$/',
            'nominal' => 'required|numeric|min:1|min_digits:4|max_digits:13|regex:/^[1-9][0-9]*$/',
            'tanggal' => 'required|date_format:Y-m-d',
        ], [
            'jenis.required' => 'Jenis harus diisi.',
            'nama.required' => 'Nama harus diisi.',
            'nominal.required' => 'Nominal harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'nama.min' => 'Nama minimal 3 karakter.',
            'nama.max' => 'Nama maksimal 20 karakter.',
            'nama.regex' => 'Nama harus berupa huruf.',
            'nominal.regex' => 'Nominal harus berupa angka.',
            'tanggal.date_format' => 'Format tanggal harus YYYY-MM-DD.',
            'nominal.numeric' => 'Nominal harus berupa angka.',
            'nominal.min' => 'Nominal harus lebih besar dari 0.',
            'nominal.min_digits' => 'Nominal minimal 4 digit.',
            'nominal.max_digits' => 'Nominal maksimal 13 digit.',
        ]);
        try {
            DB::beginTransaction();
            $data = BebanKewajiban::findorFail($bebanKewajiban->id);
            $dateTime = Carbon::parse($validatedData['tanggal'], 'Asia/Jakarta');
            $tanggal = $dateTime->format('Y-m-d');
            $data->update([
                'jenis' => $validatedData['jenis'],
                'nama' => $validatedData['nama'],
                'nominal' => $validatedData['nominal'],
                'tanggal' => $tanggal,
            ]);

            $rekap = Rekap::where('id_tabel_asal', $bebanKewajiban->id)->first();

            $rekap->update([
                'tanggal_transaksi' => $tanggal,
                'sumber' => 'Beban dan Kewajiban',
                'jumlah' => $validatedData['nominal'],
                'keterangan' => 'Pembayaran ' . $validatedData['jenis']. ' untuk ' . $validatedData['nama'],
                'id_tabel_asal' => $bebanKewajiban->id,
                'tipe_transaksi' => 'Keluar'
            ]);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($data)
                ->event('update_beban_kewajiban')
                ->withProperties(['id' => $bebanKewajiban->id])
                ->log('User ' . auth()->user()->nama . ' update a beban kewajiban');

            DB::commit();
            return redirect()->route('beban-kewajibans.index')->with('success', 'Data Berhasil Diupdate');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat megnupdate data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BebanKewajiban $bebanKewajiban)
    {
        try {
            DB::beginTransaction();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($bebanKewajiban)
                ->event('delete_beban_kewajiban')
                ->withProperties(['id' => $bebanKewajiban])
                ->log('User ' . auth()->user()->nama . ' delete a beban kewajiban');

            Rekap::where('id_tabel_asal', $bebanKewajiban->id)->delete();
            $bebanKewajiban->delete();

            DB::commit();
            return redirect()->route('beban-kewajibans.index')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat delete data');
        }
    }
}
