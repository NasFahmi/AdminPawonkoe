<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Modal;
use App\Models\Rekap;
use App\Models\JenisModal;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreModalRequest;
use App\Http\Requests\UpdateModalRequest;

class ModalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $searchTerm = request('search');

        $data = Modal::with(['jenis_modal'])
            ->whereHas('jenis_modal', function ($query) use ($searchTerm) {
                $query->where('jenis_modal', 'like', "%$searchTerm%");
            })
            ->orWhere('nama', 'like', "%$searchTerm%")
            ->paginate(10);


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
        // dd($request->all());
        $validatedData = $request->validate([
            'jenis' => 'required',
            'nama' => 'required|min:3|max:20|regex:/^[a-zA-Z\s]*$/',
            'nominal' => 'required|numeric|min:1|min_digits:4|max_digits:13|regex:/^[1-9][0-9]*$/',
            'penyedia' => 'required|min:3|max:20|regex:/^[a-zA-Z\s]*$/',
            'jumlah' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
            'tanggal' => 'required|date|date_format:Y-m-d',
        ], [
            'jenis.required' => 'Jenis harus diisi.',
            'nama.required' => 'Nama harus diisi.',
            'nominal.required' => 'Nominal harus diisi.',
            'Penyedia.required' => 'penyedia harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'nama.min' => 'Nama minimal 3 karakter.',
            'nama.max' => 'Nama maksimal 20 karakter.',
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
            'nominal.min' => 'Nominal minimal 1.',
            'nominal.regex' => 'Nominal hanya boleh mengandung angka.',
            'nominal.min_digits' => 'Nominal minimal 4 digit.',
            'nominal.max_digits' => 'Nominal maksimal 13 digit.',
            'penyedia.min' => 'Penyedia minimal 3 karakter.',
            'penyedia.max' => 'Penyedia maksimal 20 karakter.',
            'penyedia.regex' => 'Penyedia hanya boleh mengandung huruf dan spasi.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'jumlah.regex' => 'Jumlah hanya boleh mengandung angka.',
            'tanggal.date_format' => 'Format tanggal harus YYYY-MM-DD.',
        ]);

        $dateTime = Carbon::parse($validatedData['tanggal'], 'Asia/Jakarta');
        $tanggal = $dateTime->format('Y-m-d');
        // $jenis =  $validatedData['jenis'];

        $modal = Modal::create([
            'jenis_modal_id' => $validatedData['jenis'],
            'nama' => $validatedData['nama'],
            'nominal' => $validatedData['nominal'],
            'penyedia' => $validatedData['penyedia'],
            'jumlah' => $request->jumlah,
            'tanggal' => $tanggal
        ]);
        activity()
            ->causedBy(auth()->user())
            ->performedOn($modal)
            ->event('add_modal')
            ->withProperties(['id' => $modal->id])
            ->log('User ' . auth()->user()->nama . ' add a modal ');
        Rekap::insert([
            'tanggal_transaksi' => $tanggal,
            'sumber' => 'Modal',
            'jumlah' => $validatedData['nominal'],
            'keterangan' => 'Modal ' . $validatedData['nama'] . ' dari ' . $validatedData['penyedia'],
            'id_tabel_asal' => $modal->id,
            'tipe_transaksi' => 'Masuk'
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
        $dataJenis = JenisModal::all();
        return view('pages.modal.edit', compact('data', 'dataJenis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Modal $modal)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'jenis' => 'required',
            'nama' => 'required|min:3|max:20|regex:/^[a-zA-Z\s]*$/',
            'nominal' => 'required|numeric|min:1|min_digits:4|max_digits:13|regex:/^[1-9][0-9]*$/',
            'penyedia' => 'required|min:3|max:20|regex:/^[a-zA-Z\s]*$/',
            'jumlah' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
            'tanggal' => 'required|date|date_format:Y-m-d',
        ], [
            'jenis.required' => 'Jenis harus diisi.',
            'nama.required' => 'Nama harus diisi.',
            'nominal.required' => 'Nominal harus diisi.',
            'Penyedia.required' => 'penyedia harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'nama.min' => 'Nama minimal 3 karakter.',
            'nama.max' => 'Nama maksimal 20 karakter.',
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
            'nominal.min' => 'Nominal minimal 1.',
            'nominal.regex' => 'Nominal hanya boleh mengandung angka.',
            'nominal.min_digits' => 'Nominal minimal 4 digit.',
            'nominal.max_digits' => 'Nominal maksimal 13 digit.',
            'penyedia.min' => 'Penyedia minimal 3 karakter.',
            'penyedia.max' => 'Penyedia maksimal 20 karakter.',
            'penyedia.regex' => 'Penyedia hanya boleh mengandung huruf dan spasi.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'jumlah.regex' => 'Jumlah hanya boleh mengandung angka.',
            'tanggal.date_format' => 'Format tanggal harus YYYY-MM-DD.',
        ]);
        try {
            DB::beginTransaction();
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
    
            $rekap = Rekap::where('id_tabel_asal', $modal->id)->first();
    
            $rekap->update([
                'tanggal_transaksi' => $tanggal,
                'sumber' => 'Modal',
                'jumlah' => $validatedData['nominal'],
                'keterangan' => 'Modal ' . $validatedData['nama'] . ' dari ' . $validatedData['penyedia'],
                'id_tabel_asal' => $modal->id,
                'tipe_transaksi' => 'Masuk'
            ]);
    
            activity()
                ->causedBy(auth()->user())
                ->performedOn($modal)
                ->event('edit_modal')
                ->withProperties(['id' => $modal->id])
                ->log('User ' . auth()->user()->nama . ' edit a modal ');
            // Redirect back to the index page with a success message
            DB::commit();
            return redirect()->route('modal.index')->with('success', 'Data Berhasil Diupdate');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();
            // throw $th;

        }
        // Parse and format the date
      
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modal $modal)
    {
        // Delete the specified modal
        Rekap::where('id_tabel_asal', $modal->id)->delete();

        $modal->delete();
        activity()
            ->causedBy(auth()->user())
            ->performedOn($modal)
            ->event('delete_modal')
            ->withProperties(['id' => $modal->id])
            ->log('User ' . auth()->user()->nama . ' delete a modal ');
        // Redirect back to the index page with a success message
        return redirect()->route('modal.index')->with('success', 'Data Berhasil Dihapus');
    }
}
