<?php

namespace App\Http\Controllers;

use App\Models\Rekap;
use App\Models\Hutang;
use Illuminate\Http\Request;
use App\Models\CicilanHutang;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreHutangRequest;
use App\Http\Requests\UpdateHutangRequest;

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
            'jumlahHutang' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
            'tanggal_lunas' => 'nullable',
            'tenggat_waktu' => 'nullable',
            'nominal' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
            'status' => 'required|in:0,1',
        ]);

        if ($request->nominal > $request->jumlahHutang) {
            return back()->withErrors(['nominal' => 'Nominal cicilan awal tidak boleh lebih dari jumlah hutang.'])->withInput();
        }

        try {
            DB::beginTransaction();

            // Initialize $hutang
            $hutang = null;

            // lunas
            if ($validatedData['tanggal_lunas'] != null) {
                // Membuat data hutang
                $hutang = Hutang::create([
                    'nama' => $validatedData['nama'],
                    'catatan' => $validatedData['catatan'],
                    'status' => $validatedData['status'],
                    'jumlah_hutang' => $validatedData['jumlahHutang'],
                    'tenggat_waktu' => null,
                    'tanggal_lunas' => $validatedData['tanggal_lunas'],
                ]);
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($hutang)
                    ->event('add_hutang')
                    ->withProperties(['id' => $hutang->id])
                    ->log('User ' . auth()->user()->nama . ' add a new hutang');
            }

            // Check if tenggat_waktu is provided
            if ($validatedData['tenggat_waktu'] != null) {
                // Membuat data cicilan
                $hutang = Hutang::create([
                    'nama' => $validatedData['nama'],
                    'catatan' => $validatedData['catatan'],
                    'status' => $validatedData['status'],
                    'jumlah_hutang' => $validatedData['jumlahHutang'],
                    'tenggat_waktu' => $validatedData['tenggat_waktu'],
                    'tanggal_lunas' => null,
                ]);
                if (isset($request->nominal)) {
                    CicilanHutang::create([
                        'hutangId' => $hutang->id,
                        'nominal' => $validatedData['nominal'],
                    ]);
                }
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($hutang)
                    ->event('add_hutang')
                    ->withProperties(['id' => $hutang->id])
                    ->log('User ' . auth()->user()->nama . ' add a new hutang');
            }

            if ($hutang) { // Ensure $hutang has been initialized
                $tanggal = Carbon::parse($hutang->created_at, 'Asia/Jakarta')->format('Y-m-d');

                if ($validatedData['status'] == 1) {
                    Rekap::insert([
                        'tanggal_transaksi' => $tanggal,
                        'sumber' => 'Hutang',
                        'jumlah' => $hutang->jumlah_hutang,
                        'keterangan' => 'Pembayaran Hutang ke ' . $hutang->nama,
                        'id_tabel_asal' => $hutang->id,
                        'tipe_transaksi' => 'Keluar'
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('hutang.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
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
    public function update(Request $request, Hutang $hutang)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'jumlahHutang' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
            'tanggal_lunas' => 'nullable|date',
            'tenggat_waktu' => 'nullable|date',
            'status' => 'required|in:0,1',
        ]);

        try {
            DB::beginTransaction();

            // Validasi tanggal_lunas harus lebih besar atau sama dengan created_at
            if ($validatedData['status'] == 1 && isset($validatedData['tanggal_lunas'])) {
                $tanggalLunas = Carbon::parse($validatedData['tanggal_lunas']);
                $createdAt = Carbon::parse($hutang->created_at);

                if ($tanggalLunas->lt($createdAt)) {
                    throw ValidationException::withMessages([
                        'tanggal_lunas' => 'Tanggal lunas tidak boleh lebih awal dari tanggal pembuatan (' . $createdAt->format('Y-m-d') . ').',
                    ]);
                }
            }

            // Lanjutkan proses update berdasarkan status
            if ($validatedData['status'] == 1) {
                $hutang->update([
                    'nama' => $validatedData['nama'],
                    'catatan' => $validatedData['catatan'],
                    'jumlah_hutang' => $validatedData['jumlahHutang'],
                    'status' => $validatedData['status'],
                    'tanggal_lunas' => $validatedData['tanggal_lunas'],
                    'tenggat_waktu' => null,
                ]);

                $cicilan = CicilanHutang::where('hutangId', $hutang->id)->get();
                $totalNominalHutang = $cicilan->sum('nominal');
                CicilanHutang::create([
                    'hutangId' => $hutang->id,
                    'nominal' => $hutang->jumlah_hutang - $totalNominalHutang,
                ]);
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($hutang)
                    ->event('edit_hutang')
                    ->withProperties(['id' => $hutang->id])
                    ->log('User ' . auth()->user()->nama . ' update a hutang');
            } elseif ($validatedData['status'] == 0) {
                $hutang->update([
                    'nama' => $validatedData['nama'],
                    'catatan' => $validatedData['catatan'],
                    'jumlah_hutang' => $validatedData['jumlahHutang'],
                    'status' => $validatedData['status'],
                    'tanggal_lunas' => null,
                    'tenggat_waktu' => $validatedData['tenggat_waktu'],
                ]);

                CicilanHutang::where('hutangId', $hutang->id)->delete();
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($hutang)
                    ->event('edit_hutang')
                    ->withProperties(['id' => $hutang->id])
                    ->log('User ' . auth()->user()->nama . ' update a hutang');
            }

            // Simpan rekap jika status selesai
            $tanggal = Carbon::parse($hutang->created_at)->format('Y-m-d');
            if ($validatedData['status'] == 1) {
                Rekap::insert([
                    'tanggal_transaksi' => $tanggal,
                    'sumber' => 'Hutang',
                    'jumlah' => $hutang->jumlah_hutang,
                    'keterangan' => 'Pembayaran Hutang ke ' . $hutang->nama,
                    'id_tabel_asal' => $hutang->id,
                    'tipe_transaksi' => 'Keluar'
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hutang = Hutang::find($id);
        try {
            DB::beginTransaction();

            Rekap::where('id_tabel_asal', $hutang->id)->delete();
            $hutang->delete();
            activity()
                ->causedBy(auth()->user())
                ->performedOn($hutang)
                ->event('delete_hutang')
                ->withProperties(['id' => $hutang->id])
                ->log('User ' . auth()->user()->nama . ' delete a hutang');
            DB::commit();
            return redirect()->route('hutang.index')->with('success', 'Data Berhasil Didelete');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
        }
    }
}
