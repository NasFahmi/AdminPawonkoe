<!DOCTYPE html>
<html lang="en" data-theme="light" class="scroll-smooth focus:scroll-auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">

    @extends('partials.head')
</head>
<body>
    <main class="mx-auto scroll-smooth overflow-hidden">
        @yield('content')
    </main>
</body>
</html>