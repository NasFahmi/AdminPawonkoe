<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function indexImage()
    {
        $data = Product::with('fotos')
            ->latest('created_at')
            ->limit(5)
            ->where('tersedia', 1)
            ->get();

        $images = [];
        foreach ($data as $product) {
            if ($product->fotos->isNotEmpty()) {
                $path = $product->fotos->first()->foto;
                $images[] = ['image' => $path];
            }
        }

        return response()->json($images);
    }



    public function katalog()
    {
        $products = Product::with(['fotos', 'varians'])
            ->where('tersedia', 1)
            ->get();

        $responseData = [];

        foreach ($products as $product) {
            $responseData[] = [
                'title' => $product->nama_product,
                'description' => $product->deskripsi,
                'price' => $product->harga,
                'slug' => $product->slug,
                'image' => $product->fotos->isNotEmpty() ? $product->fotos->first()->foto : null,
            ];
        }

        return response()->json($responseData);
    }

    public function productSearch($search)
    {
        $data = Product::with(['fotos', 'varians',])
            ->where('tersedia', 1)
            ->where('nama', $search)
            ->get();
        return response()->json($data);
    }

    public function detailProduct($slug)
    {
        $product = Product::with(['fotos', 'varians'])
            ->where('slug', $slug)
            ->firstOrFail();
        $images = [];

        foreach ($product->fotos as $foto) {
            $images[] = $foto->foto;
        }

        return response()->json(
            [
                'title' => $product->nama_product,
                'description' => $product->deskripsi,
                'price' => $product->harga,
                'spesification' => $product->spesifikasi_product,
                'link' => $product->link_shopee,
                'image' => $images,
                'varian' =>$product->varians
            ]
        );
    }
}
