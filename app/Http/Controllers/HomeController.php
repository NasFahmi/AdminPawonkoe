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
        $image = [];
        foreach ($data as $product) {
            if ($product->fotos->isNotEmpty()) {
                $path = $product->fotos->first()->foto; // Assuming 'url' is the attribute containing the image URL
                $image[] = "/storage/images/".$path;
            }
        }

        return response()->json($image);
    }


    public function katalog()
    {
        $data = Product::with(['fotos', 'varians',])
            ->where('tersedia', 1)
            ->paginate(20);
        return response()->json($data);
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
        $data = Product::with(['fotos', 'varians'])->findOrFail($slug);
        return response()->json($data);
    }
}
