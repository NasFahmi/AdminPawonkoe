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
    public function create(Request $request)
{
    $request->validate([
        'status' => 'required|string', // Validasi bahwa status harus ada dan berupa boolean
    ]);
    // Dapatkan status dari query string
    $status = $request->query('status','Belum selesai');
    // dd($status);
    // Tampilkan view dengan status
    return view('pages.hutang.create', compact('status'));
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            
            DB::beginTransaction();

            $status = $request->input('status');
            // dd(vars: $request->all());
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
                'catatan' => 'nullable|string',
                'jumlahHutang' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
                'tanggal_lunas' => $status == 1 ? 'required|date' : 'nullable',
                'tenggat_waktu' => $status == 0 ? 'required|date' : 'nullable',
                'nominal' => $status == 1 ? 'nullable' : 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
                'status' => 'required|in:1,0',
            ],
            [
                'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
                'jumlahHutang.regex' => 'Jumlah hutang harus berupa angka.',
                'nominal.regex' => 'Nominal cicilan awal harus berupa angka.',
                'tanggal_lunas.date' => 'Tanggal lunas harus berupa tanggal dengan format YYYY-MM-DD.',
                'tenggat_waktu.date' => 'Tenggat waktu harus berupa tanggal dengan format YYYY-MM-DD.',
            ]);
            // dd($status);
    
            if ($request->nominal > $request->jumlahHutang) {
                return back()->withErrors(['nominal' => 'Nominal cicilan awal tidak boleh lebih dari jumlah hutang.'])->withInput();
            }

            // lunas
            if ($status == 1) {
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

            if ($status == 0) {
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
        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            throw $e;
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
        // dd($hutang);
        $cicilanHutang = CicilanHutang::where('hutangId', $hutang->id)->get();
        // dd($cicilanHutang);
        return view('pages.hutang.edit', compact('hutang', 'cicilanHutang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hutang $hutang)
    {
        $status = $request->input('status');
        // dd($request->all());
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'catatan' => 'nullable|string',
            'status' => 'required|in:1,0',
            'jumlahHutang' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
            'tenggat_waktu' =>  $status == 0 ? 'required|date' : 'nullable',
            'nominal' =>  $status == 1 ? 'nullable':'numeric|min:1|regex:/^[1-9][0-9]*$/',
            'tanggal_lunas' =>   $status == 1 ? 'required|date' : 'nullable',
            
        ],
        [
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
            'jumlahHutang.regex' => 'Jumlah hutang harus berupa angka.',
            'nominal.regex' => 'Nominal cicilan awal harus berupa angka.',
            'tanggal_lunas.date' => 'Tanggal lunas harus berupa tanggal dengan format YYYY-MM-DD.',
            'tenggat_waktu.date' => 'Tenggat waktu harus berupa tanggal dengan format YYYY-MM-DD.',
        ]);


        try {
            DB::beginTransaction();
            // dd($validatedData);
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
            if ($validatedData['status'] == 0) {
                $hutang->update([
                    'nama' => $validatedData['nama'],
                    'catatan' => $validatedData['catatan'],
                    'jumlah_hutang' => $validatedData['jumlahHutang'],
                    'status' => $validatedData['status'],
                    'tanggal_lunas' => null,
                    'tenggat_waktu' => $validatedData['tenggat_waktu'],
                ]);

                // $cicilan = CicilanHutang::where('hutangId', $hutang->id)->get();
                // $totalNominalHutang = $cicilan->sum('nominal');
                // Find the CicilanHutang record by ID; throw an error if not found
                $idCH = CicilanHutang::where('hutangId', $hutang->id);

                // Update the 'nominal' field with the validated data
                $idCH->update([
                    'nominal' => $validatedData['nominal'],
                ]);

            } elseif ($validatedData['status'] == 1) {
                $hutang->update([
                    'nama' => $validatedData['nama'],
                    'catatan' => $validatedData['catatan'],
                    'jumlah_hutang' => $validatedData['jumlahHutang'],
                    'status' => $validatedData['status'],
                    'tanggal_lunas' => $validatedData['tanggal_lunas'],
                    'tenggat_waktu' => null,
                ]);
                CicilanHutang::where('hutangId', $hutang->id)->delete();
            }
            DB::commit();

            $id = $request->id;
            // dd($request->id);
            // Simpan rekap jika status selesai
            $tanggal = Carbon::parse($hutang->created_at)->format('Y-m-d');
            if ($validatedData['status'] == 1) {
                Rekap::insert([
                    'tanggal_transaksi' => $tanggal,
                    'sumber' => 'Hutang',
                    'jumlah' => $validatedData['jumlahHutang'],
                    'keterangan' => 'Pembayaran Hutang ke ' . $validatedData['nama'],
                    'id_tabel_asal' => $id,
                    'tipe_transaksi' => 'Keluar'
                ]);
            }


            return redirect()->route('hutang.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Exception $e) {
            // throw $e;
            DB::rollBack();
            // dd($e->getMessage());
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
        } catch (\Exception $e) {
            // throw $th;
            dd($e->getMessage());
            DB::rollBack();
        }
    }
}