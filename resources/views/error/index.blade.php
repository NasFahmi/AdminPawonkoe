@extends('layout.admin_pages')
@section('title', 'Errors')
@section('content')
    <div class="w-full h-full flex justify-center items-center flex-col bg-white  border rounded-lg shadow-md ">
        <h1 class="text-3xl mb-6 font-bold text-gray-800">Oops! Ada Kesalahan</h1>
        <p class="text-lg text-gray-700">{{ $exception }}</p>
    </div>

@endsection
