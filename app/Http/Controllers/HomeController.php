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
            ->limit(3)
            ->where('tersedia',1)
            ->get();

        return response()->json($data);
    }
    

    public function katalog(){
        $data = Product::with(['fotos', 'varians',])
            ->where('tersedia', 1)
            ->paginate(20);
        return response()->json($data);
    }
    public function productSearch($search){
        $data = Product::with(['fotos', 'varians',])
        ->where('tersedia', 1)
        ->where('nama',$search)
        ->get();
        return response()->json($data);
    }

    public function detailProduct($slug){
        $data = Product::with(['fotos', 'varians'])->findOrFail($slug);
        return response()->json($data);
    }
    
}
