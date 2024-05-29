<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
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

        $data = Hutang::where('nama', 'like', "%$searchTerm%")
            ->paginate(10);

        return view('pages.hutang.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.hutang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHutangRequest $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Hutang $hutang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hutang $hutang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHutangRequest $request, Hutang $hutang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hutang $hutang)
    {
        //
    }
}
