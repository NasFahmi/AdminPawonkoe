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
            // lunas
            if ($validatedData['tanggal_lunas'] != null) {

                // $tanggalLunas = Carbon::parse($validatedData['tanggal_lunas'], 'Asia/Jakarta')->format('Y-m-d');
                // Membuat data hutang
                $hutang = Hutang::create([
                    'nama' => $validatedData['nama'],
                    'catatan' => $validatedData['catatan'],
                    'status' => $validatedData['status'], // Sesuaikan nama kolom jika berbeda
                    'jumlah_hutang' => $validatedData['jumlahHutang'],
                    'tenggat_waktu' => null,
                    'tanggal_lunas' => $validatedData['tanggal_lunas'],
                ]);
            }



            if ($validatedData['tenggat_waktu'] != null) {
                // $tenggatWaktu = Carbon::parse($validatedData['tenggat_waktu'], 'Asia/Jakarta')->format('Y-m-d');
                // Membuat data cicilan
                $hutang = Hutang::create([
                    'nama' => $validatedData['nama'],
                    'catatan' => $validatedData['catatan'],
                    'status' => $validatedData['status'], // Sesuaikan nama kolom jika berbeda
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
            }
            $tanggal = Carbon::parse($hutang->created_at, 'Asia/Jakarta')->format('Y-m-d');

            // dd($tanggal);
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
            DB::commit();
            return redirect()->route('hutang.index')->with('success', 'Data Berhasil Didelete');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
        }
    }
}
