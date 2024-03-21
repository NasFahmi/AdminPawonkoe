<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function landingpage()
    {
        $data = Product::with('fotos')
            ->latest('created_at')
            ->limit(3)
            ->get();

        return view('pages.enduser.landing_page', compact('data'));
    }
    

    public function katalog(){
        $data = Product::with(['fotos', 'varians',])
            ->search(request('search'))
            ->where('tersedia', 1)
            ->paginate(20);
        return view('pages.enduser.katalog',compact('data'));
    }

    public function detailProduct($id){
        $data = Product::with(['fotos', 'varians'])->findOrFail($id);
        return view('pages.enduser.detailproduct',compact('data'));
    }
    
}
