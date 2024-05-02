<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Piutang;
use App\Models\NotaPiutang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProdukPiutang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StorePiutangRequest;
use App\Http\Requests\UpdatePiutangRequest;
use App\Models\PiutangProdukPiutang;

class PiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Piutang::with(['piutang_produk_piutangs.produk_piutangs'])->paginate(10);
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
        $validatedData = $request->validate([
            'nama_toko' => 'required|string',
            'sewa_titip' => 'required|numeric',
            'tanggal_disetorkan' => 'required|date_format:m/d/Y',
            'catatan' => 'nullable|string',
            'product.*.product' => 'required|string',
            'product.*.quantity' => 'required|numeric',
            'product.*.price' => 'required|numeric',
            'image.*' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Adjust max file size as needed

        ], [

            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute must be a string.',
            'numeric' => 'The :attribute must be a number.',
            'date_format' => 'The :attribute must be in the format mm/dd/yyyy.',
            'image' => 'The :attribute must be an image.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'max' => 'The :attribute may not be greater than :max kilobytes.',
        ]);

        try {
            DB::beginTransaction();
            $data = $request->all();
            // dd($data['nama_toko']);
            $dateTime = Carbon::parse($data['tanggal_disetorkan'], 'Asia/Jakarta');
            $tanggal = $dateTime->format('Y-m-d');
            // 1. store piutang
            // Piutang
            $piutang = Piutang::create([
                'nama_toko' => $data['nama_toko'],
                'sewa_titip' => $data['sewa_titip'],
                'tanggal_disetorkan' => $tanggal,
                'catatan' => $data['catatan'],
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
            
                $foldername = $data['nama_toko'] . '_' . $tanggal;
                $folderPath = 'public/images/piutang/' . $foldername;
            
                if (!Storage::exists($folderPath)) {
                    Storage::makeDirectory($folderPath, 0755, true); // Recursive directory creation
                }
            
                foreach ($images as $image) {
                    $nameResource = Str::random(10);
                    $extension = $image->getClientOriginalExtension();
                    $name = $nameResource . '.' . $extension;
            
                    $image->storeAs($folderPath, $name);
            
                    NotaPiutang::create([
                        'piutang_id' => $piutang->id,
                        'foto' => $folderPath . '/' . $name, // Concatenate folder path and file name
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

            $piutang->penghasilan = $total - $data['sewa_titip'];
            $piutang->save();
            DB::commit();
            return redirect()->route('piutang.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
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
