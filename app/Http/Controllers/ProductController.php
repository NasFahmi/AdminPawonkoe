<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Varian;
use App\Models\Product;
use App\Models\BeratJenis;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\ProductCreated;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Imagick\Driver;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::with(['fotos', 'varians',])
            ->search(request('search'))
            ->where('tersedia', 1)
            ->paginate(12);

        $totalProduct = Product::where('tersedia', 1)->sum('tersedia');
        return view('pages.admin.product.index', compact('data', 'totalProduct'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all()); 
        $validator = Validator::make($request->all(), [
            'nama_product' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
            'link_shopee' => 'required',
            'stok' => 'required',
            'spesifikasi_product' => 'required',
            'image' => 'required',
            'image.*' => 'mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama_product.required' => 'Nama produk wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'link_shopee.required' => 'Link Shopee wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'spesifikasi_product.required' => 'Spesifikasi produk wajib diisi.',
            'image.required' => 'Setiap Produk harus memiliki foto.',
            'image.*.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'image.*.max' => 'Ukuran gambar tidak boleh lebih dari 2 MB.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $namaProduct = $data['nama_product'];
        $slug = Str::of($namaProduct)->slug('-')->__toString();
        // dd($slug);
        // dd($data);
        try {
            DB::beginTransaction();
            $product = Product::create([
                'nama_product' => $data['nama_product'],
                'slug'=> $slug,
                'harga' => $data['harga'],
                'deskripsi' => $data['deskripsi'],
                'link_shopee' => $data['link_shopee'],
                'stok' => $data['stok'],
                'spesifikasi_product' => $data['spesifikasi_product'],
                'tersedia' => '1',
            ]);
            $productID = $product->id;

            event(new ProductCreated($product, $productID));

            if (isset ($data['varian'])) {

                $varians = $data['varian'];

                foreach ($varians as $varian) {
                    Varian::create([
                        'jenis_varian' => $varian,
                        'product_id' => $productID,
                    ]);
                }
            }


            // Proses setiap file yang diunggah
            $images = [];
            foreach ($request->file('image') as $file) {
                $img = $file->store("public/images");
                //dd($img); //public/images/1K2vsWy2RnLA1wsc1ivlOEWIeEkFP6z8AjkIpTcy.jpg
                $imageName = basename($img);
                // dd($imageName);
                $images[] = $imageName;
            }

            // Simpan informasi gambar ke dalam tabel Foto
            foreach ($images as $image) {
                Foto::create([
                    'foto' => $image,
                    'product_id' => $productID
                ]);
            }

            DB::commit();

            $request->session()->forget(['product_data', 'varian', 'image_data']);
            return redirect()->route('products.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Exception $e) {
            // Jika ada kesalahan, rollback transaksi
            DB::rollBack();
            throw $e;
            // dd('gagal ');
            // Handle kesalahan sesuai kebutuhan Anda, misalnya:
            return redirect()->back()->with('error', 'Gagal menyimpan data Product.');
        }

    }


    public function show($id)
    {
        $data = Product::with(['fotos', 'varians'])->findOrFail($id);
        $berat_jenis = $data->beratJenis;
        return view('pages.admin.product.detail', compact('data', 'berat_jenis'));
    }

    public function edit($id)
    {
        $data = Product::with(['fotos', 'varians'])->findOrFail($id);
        $images = Foto::where('product_id', $id)->get()->map(function ($image) {
            return [
                'source' => $image->foto,
                'options' => [
                    'type' => 'local'
                ]
            ];
        })->toArray();
        // dd($data);
        return view('pages.admin.product.edit', compact('data','images'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_product' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
            'link_shopee' => 'required',
            'stok' => 'required',
            'spesifikasi_product' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg|max:2048', // Allow empty image updates
        ]);

        try {
            DB::beginTransaction();

            // Find the product by ID
            $product = Product::findOrFail($id);

            // Update the product data
            $product->update([
                'nama_product' => $request->nama_product,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'link_shopee' => $request->link_shopee,
                'stok' => $request->stok,
                'spesifikasi_product' => $request->spesifikasi_product,
                'tersedia' => '1',
            ]);
            event(new ProductCreated($product, $id));
            // $product->beratJenis()->sync($beratJenisIds);
            if (isset ($request->varian)) {
                // Update or create varians records
                $product->varians()->delete(); // Delete existing varians
                foreach ($request->varian as $varian) {
                    $product->varians()->create(['jenis_varian' => $varian]);
                }
            }

            // Handle image updates
            if ($request->hasFile('image')) {
                $this->validate($request, [
                    'image.*' => 'image|mimes:jpeg,png,jpg|max:2048',
                ]);

                // Delete existing images
                foreach ($product->fotos as $foto) {
                    Storage::delete($foto->foto);
                    $foto->delete();
                }

                // Process each uploaded file
                foreach ($request->file('image') as $file) {
                    $img = $file->store("images");
                    // Create new image record
                    Foto::create([
                        'foto' => $img,
                        'product_id' => $product->id,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product has been updated successfully');
        } catch (\Exception $e) {
            // If there is an error, rollback the transaction
            DB::rollBack();
            throw $e;
            // Handle the error as needed
            // return redirect()->back()->with('error', 'Failed to update product data.');
        }
    }


    public function destroy(Product $product)
    {
        try {
            // Find the product with its related photos and variants
            $data = Product::with(['fotos', 'varians'])->findOrFail($product->id);

            // Update the 'tersedia' column to false
            $data->update(['tersedia' => false]);

            return redirect()->route('products.index')->with('success', 'Product has been deleted successfully');
        } catch (\Exception $e) {
            // Handle any exceptions that may occur
            return redirect()->route('products.index')->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
}
