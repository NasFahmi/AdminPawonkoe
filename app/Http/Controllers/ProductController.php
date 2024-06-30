<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Varian;
use App\Models\Product;
use App\Models\BeratJenis;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\ProductCreated;
use App\Models\TemporaryImage;
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
            'images' => 'required',
            'images.*' => 'required'
        ], [
            'nama_product.required' => 'Nama produk wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'link_shopee.required' => 'Link Shopee wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'spesifikasi_product.required' => 'Spesifikasi produk wajib diisi.',
            'images.required' => 'Setiap Produk harus memiliki foto.',
        ]);
        // dd($request->all());



        // dd($slug);
        // dd($data);
        try {
            DB::beginTransaction();
            // get data from images
            $dataAllImage = $request->images; // Mendapatkan array dari request
            $decodedImages = [];
            foreach ($dataAllImage as $image) {
                $decodedImages[] = json_decode($image, true); // Mendekodekan string JSON menjadi array PHP dan menambahkannya ke dalam array $decodedImages
            }
            $dataImages = call_user_func_array('array_merge', $decodedImages);
            // filter data to name
            if ($validator->fails()) {
                //delete data temporary images
                foreach ($dataImages as $temporaryImage) {
                    $tempImage = TemporaryImage::where('folder', $temporaryImage)->first(); //get single data temp image
                    // Delete files from storage
                    Storage::deleteDirectory('public/images/tmp/' . $tempImage->folder);

                    // Delete record from the database
                    $tempImage->delete();
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->all();
            $namaProduct = $data['nama_product'];
            $slug = Str::of($namaProduct)->slug('-')->__toString();

            $product = Product::create([
                'nama_product' => $data['nama_product'],
                'slug' => $slug,
                'harga' => $data['harga'],
                'deskripsi' => $data['deskripsi'],
                'link_shopee' => $data['link_shopee'],
                'stok' => $data['stok'],
                'spesifikasi_product' => $data['spesifikasi_product'],
                'tersedia' => '1',
            ]);
            $productID = $product->id;

            event(new ProductCreated($product, $productID));

            if (isset($data['varian'])) {

                $varians = $data['varian'];

                foreach ($varians as $varian) {
                    Varian::create([
                        'jenis_varian' => $varian,
                        'product_id' => $productID,
                    ]);
                }
            }

            foreach ($dataImages as $image) {
                $imageTemp = TemporaryImage::where('folder', $image)->first(); //get single data temp image
                $extensionTemp = pathinfo($imageTemp->file, PATHINFO_EXTENSION); // Mendapatkan ekstensi file
                $folderNameTemp = $imageTemp->folder;
                $fileNameTemp = $imageTemp->file;
                $fileNameProductImage =  Str::random(20) . '.' . $extensionTemp;
                // dd($fileNameProductImage); //GdomcXRDdftRq30MjJPz.jpeg
                // copy file image from storage\app\public\images\tmp\image-660a77aaf10368.27307606\WhatsApp Image 2024-03-18 at 9.29.38 PM.jpeg to storage\app\public\images\GdomcXRDdftRq30MjJPz.jpeg
                // Membuat folder produk jika belum ada
                $slugFolderPath = 'public/images/product/' . $slug;
                if (!Storage::exists($slugFolderPath)) {
                    Storage::makeDirectory($slugFolderPath);
                    // Mengatur izin folder
                    $folderPermissions = 0755; // Atur izin sesuai kebutuhan Anda
                    chmod(storage_path('app/' . $slugFolderPath), $folderPermissions);
                }
                // copy file image dari storage\app\public\images\tmp\image-660a77aaf10368.27307606\WhatsApp Image 2024-03-18 at 9.29.38 PM.jpeg ke storage\app\public\images\GdomcXRDdftRq30MjJPz.jpeg
                $sourcesPath = 'public/images/tmp/' . $folderNameTemp . '/' . $fileNameTemp;
                $destinationPath = 'public/images/product/' . $slug . '/' . $fileNameProductImage;
                Storage::copy($sourcesPath, $destinationPath);
                Foto::updateOrInsert([ //! hanya bekerja di store, namun tidak bekerja di update
                    'foto' => '/storage/images/product/' . $slug . '/' . $fileNameProductImage,
                    'product_id' => $productID,
                ]);
                // dd($isertimagedb);
                $imageTemp->delete();
                Storage::deleteDirectory('public/images/tmp/' . $folderNameTemp);
            }

            activity()
                ->causedBy(auth()->user())
                ->performedOn($product)
                ->event('add_product')
                ->withProperties(['id' => $productID])
                ->log('User ' . auth()->user()->nama . ' add a new product ');


            DB::commit();

            $request->session()->forget(['product_data', 'varian', 'image_data']);
            return redirect()->route('products.index')->with('success', 'Data Berhasil Disimpan');
        } catch (\Exception $e) {
            // Jika ada kesalahan, rollback transaksi
            DB::rollBack();
            // throw $e;
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
        return view('pages.admin.product.edit', compact('data', 'images'));
    }


    public function update(Request $request, $id)
    {
        // dd($request->all()); 
        $validator = Validator::make($request->all(), [
            'nama_product' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
            'link_shopee' => 'required',
            'stok' => 'required',
            'spesifikasi_product' => 'required',
            'images' => 'required',
            'images.*' => 'required'
        ], [
            'nama_product.required' => 'Nama produk wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'link_shopee.required' => 'Link Shopee wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'spesifikasi_product.required' => 'Spesifikasi produk wajib diisi.',
            'images.required' => 'Setiap Produk harus memiliki foto.',
        ]);


        try {
            DB::beginTransaction();
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first())->withInput();
            }
            $product = Product::findOrFail($id);
            // Mendapatkan semua data gambar dari request
            $dataAllImage = $request->images;

            // Membagi gambar baru dan gambar lama berdasarkan format JSON
            $newPhotos = array_filter($dataAllImage, function ($item) {
                return preg_match('/^\[".*"\]$/', $item);
            }); //! new photos was upload direcly into database
            $oldPhotos = array_filter($dataAllImage, function ($item) {
                return !preg_match('/^\[".*"\]$/', $item);
            }); //!old photos

            $decodedImages = [];
            $newdataImages = [];
            if (isset($newPhotos)) {
                $decodedImages = [];
                foreach ($newPhotos as $image) {
                    $decodedImages[] = json_decode($image, true);
                }
                $newdataImages = call_user_func_array('array_merge', $decodedImages);

                // Ubah setiap nama file menjadi path yang diinginkan
                $productSlug = $product->slug;
                $newdataImages = array_map(function ($filename) use ($productSlug) {
                    return '/storage/images/product/' . $productSlug . '/' . ltrim($filename, '/');
                }, $newdataImages);
            }

            $combinedImage = array_merge($newdataImages, $oldPhotos);
            // Find the product by ID
            // dd($combinedImage);

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
            if (isset($request->varian)) {
                // Update or create varians records
                $product->varians()->delete(); // Delete existing varians
                foreach ($request->varian as $varian) {
                    $product->varians()->create(['jenis_varian' => $varian]);
                }
            }

            if (isset($combinedImage)) {
                $allOldPhotos = Foto::where('product_id', (int)$id)->pluck('foto')->toArray();

                $photosToDelete = array_diff($allOldPhotos, $combinedImage); //array

                if (!empty($photosToDelete)) {
                    foreach ($photosToDelete as $photo) {
                        Foto::where('foto', $photo)->delete();
                        Storage::delete(str_replace('/storage', '/public', $photo));
                    }
                }
                // dd('end foreach');
            }

            activity()
                ->causedBy(auth()->user())
                ->performedOn($product)
                ->withProperties(['id' => $product->id])
                ->event('update_product')
                ->log('User ' . auth()->user()->nama . ' edit a product ');

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
            activity()
                ->causedBy(auth()->user())
                ->performedOn($data)
                ->event('delete_product')
                ->withProperties(['data' => $data])
                ->log('User ' . auth()->user()->nama . ' delete a product ');

            // Update the 'tersedia' column to false
            $data->update(['tersedia' => false]);

            return redirect()->route('products.index')->with('success', 'Product has been deleted successfully');
        } catch (\Exception $e) {
            // Handle any exceptions that may occur
            return redirect()->route('products.index')->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
}
