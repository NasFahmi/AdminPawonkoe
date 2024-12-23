<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Rekap;
use App\Models\Piutang;
use App\Models\NotaPiutang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProdukPiutang;
use Illuminate\Support\Facades\DB;
use App\Models\PiutangProdukPiutang;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StorePiutangRequest;
use App\Http\Requests\UpdatePiutangRequest;

class PiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Piutang::with(['piutang_produk_piutangs.produk_piutangs'])
            ->search(request('search'))
            ->paginate(10);
        // foreach ($data as $items) {
        //     foreach ($items->piutang_produk_piutangs as $products ) {
        //         foreach ($products->produk_piutangs as $product) {
        //             dd($product->nama_produk);
        //         }
        //     }
        // }
        return view('pages.piutang.index', compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // dd('awdw');
        return view('pages.piutang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validasi input data
        $validatedData = $request->validate([
            'nama_toko' => 'required|string',
            'sewa_titip' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
            'tanggal_disetorkan' => 'required|',
            'catatan' => 'nullable|string',
            'product.*.product' => 'required|string',
            'product.*.quantity' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
            'product.*.price' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
            'image.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute must be a string.',
            'numeric' => 'The :attribute must be a number.',
            'date_format' => 'The :attribute must be in the format.',
            'image' => 'The :attribute must be an image.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'max' => 'The :attribute may not be greater than :max kilobytes.',
        ]);
        try {
            DB::beginTransaction();
            // $data = $request->all();
            // dd($data['nama_toko']);
            // Tambahkan pengecekan apakah ada product yang dikirim
            $productData = $request->input('product');
            if (empty($productData) || count($productData) == 0) {
                return redirect()->back()->withErrors(['product' => 'At least one product is required.'])->withInput();
            }

            $dateTime = Carbon::parse($validatedData['tanggal_disetorkan'], 'Asia/Jakarta');
            $tanggal = $dateTime->format('Y-m-d');
            // 1. store piutang
            // Piutang
            $piutang = Piutang::create([
                'nama_toko' => $validatedData['nama_toko'],
                'sewa_titip' => $validatedData['sewa_titip'],
                'tanggal_disetorkan' => $validatedData['tanggal_disetorkan'],
                'catatan' => $validatedData['catatan'],
            ]);
            // 2. store piutang product piutang

            // piutang product piutang
            $piutangProductPiutang = PiutangProdukPiutang::create([
                'piutang_id' => $piutang->id,
            ]);

            // 3. store image
            if ($request->hasFile('image')) {
                $images = $request->file('image'); // not empty
                // dd($images); // Check if $images is not empty

                $foldername = $validatedData['nama_toko'] . '_' . $tanggal;
                $folderPath = 'public/images/piutang/' . $foldername;

                if (!Storage::exists($folderPath)) {
                    Storage::makeDirectory($folderPath); // Recursive directory creation
                    $folderPermissions = 0755; // Atur izin sesuai kebutuhan Anda
                    chmod(storage_path('app/' . $folderPath), $folderPermissions);
                }

                foreach ($images as $image) {
                    $nameResource = Str::random(10);
                    $extension = $image->getClientOriginalExtension();
                    $name = $nameResource . '.' . $extension;

                    $image->storeAs($folderPath, $name);

                    NotaPiutang::create([
                        'piutang_id' => $piutang->id,
                        'foto' => 'storage/images/piutang/' . $foldername . '/' . $name, // Concatenate folder path and file name
                    ]);
                }
            }

            // 4. store semua product kedalam database
            $productData = $request->input('product');

            foreach ($productData as $product) {
                ProdukPiutang::create([
                    'produk_piutang_id' => $piutangProductPiutang->id,
                    'nama_produk' => $product['product'],
                    'jumlah' => $product['quantity'],
                    'harga' => $product['price'],
                    'total' => $product['quantity'] * $product['price'],
                ]);
            }
            // dd($data);

            // calculate total didalam tabel piutang product piutang
            $productPiutang = ProdukPiutang::where('produk_piutang_id', $piutangProductPiutang->id)->get(); //result array
            $total = 0;
            foreach ($productPiutang as $product) {
                // Add the total of each product to the total sum
                $total += $product->total;
            }
            $piutangProductPiutang->total = $total;
            $piutangProductPiutang->save();

            $piutang->penghasilan = $total - $validatedData['sewa_titip'];
            $piutang->save();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($piutang)
                ->event('add_piutang')
                ->withProperties(['id' => $piutang->id])
                ->log('User ' . auth()->user()->nama . ' add a piutang');
            DB::commit();
            return redirect()->route('piutang.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            // return redirect()->back()->with('error', 'Gagal menyimpan.');
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
    public function edit($id)
    {
        $piutang = Piutang::with('piutang_produk_piutangs', 'notas')->findorFail($id);
        $data = ProdukPiutang::where('produk_piutang_id', $piutang->id)->get();
        $dataNota = NotaPiutang::where('piutang_id', $piutang->id)->get();
        return view('pages.piutang.edit', compact('piutang', 'data', 'dataNota'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tanggal_lunas' => 'required',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Adjust max file size as needed
        ], [
            'required' => 'The :attribute field is required.',
            'image' => 'The :attribute must be an image.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'max' => 'The :attribute may not be greater than :max kilobytes.',
        ]);

        try {
            DB::beginTransaction();
            $data = $request->all();
            // dd($validatedData);
            // dd($data);
            $tanggal = $validatedData['tanggal_lunas'];
            // $tanggal = $dateTime->format('Y-m-d');

            // 1. Update piutang
            $piutang = Piutang::findOrFail($id);
            $piutang->update([
                'is_complete' => $data['is_complete'],
                'tanggal_lunas' => $tanggal,
            ]);

            // 3. Update image
            if ($request->hasFile('image')) {
                $images = $request->file('image'); // not empty
                $foldername = $piutang->nama_toko . '_' . $tanggal;
                $folderPath = 'public/images/piutang/' . $foldername;

                if (!Storage::exists($folderPath)) {
                    Storage::makeDirectory($folderPath); // Recursive directory creation
                    $folderPermissions = 0755; // Atur izin sesuai kebutuhan Anda
                    chmod(storage_path('app/' . $folderPath), $folderPermissions);
                }

                foreach ($images as $image) {
                    $nameResource = Str::random(10);
                    $extension = $image->getClientOriginalExtension();
                    $name = $nameResource . '.' . $extension;

                    $image->storeAs($folderPath, $name);

                    NotaPiutang::updateOrCreate([
                        'piutang_id' => $piutang->id,
                    ], [
                        'foto' => 'storage/' . $folderPath . '/' . $name, // Concatenate folder path and file name
                    ]);
                }
            }



            if ($data['is_complete'] == 1) {
                Rekap::insert([
                    'tanggal_transaksi' => $tanggal,
                    'sumber' => 'Piutang',
                    'jumlah' => $piutang->penghasilan,
                    'keterangan' => 'Piutang di ' . $piutang->nama_toko,
                    'id_tabel_asal' => $piutang->id,
                    'tipe_transaksi' => 'Masuk'
                ]);
            }

            activity()
                ->causedBy(auth()->user())
                ->performedOn($piutang)
                ->event('update_piutang')
                ->withProperties(['id' => $piutang->id])
                ->log('User ' . auth()->user()->nama . ' update a piutang');

            DB::commit();
            return redirect()->route('piutang.index')->with('success', 'Data Berhasil Diupdate');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            // return redirect()->back()->with('error', 'Gagal menyimpan.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $piutang = Piutang::findOrFail($id);
        $piutang->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($piutang)
            ->event('delete_piutang')
            ->withProperties(['id' => $piutang->id])
            ->log('User ' . auth()->user()->nama . ' deleted a piutang');


        Rekap::where('id_tabel_asal', $piutang->id)->delete();

        return redirect()->route('piutang.index')->with('success', 'Data Berhasil Dihapus');
    }
}
