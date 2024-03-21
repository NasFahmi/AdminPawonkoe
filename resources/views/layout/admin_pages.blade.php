<!DOCTYPE html>
<html :class="{ 'theme-dark': light }" data-theme="light" x-data="data()" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    <style>
        .cover-image {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
    </style>
    
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    @include('partials.link')
    @extends('partials.head')
    <style>
        /* Add this in your CSS or within a style tag in your HTML */
        .costumscroll::-webkit-scrollbar {
            width: 8px;
            /* Set the width of the scrollbar */
        }

        .costumscroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            /* Set the color of the thumb (the draggable part) */
            border-radius: 8px;
            /* Set the border-radius of the thumb */
        }

        .costumscroll::-webkit-scrollbar-track {
            background-color: #f1f1f1;
            /* Set the color of the track (the non-draggable part) */
        }
    </style>
</head>

<body>
    <div class="flex  h-screen w-screen bg-gray-100 " :class="{ 'overflow-hidden': isSideMenuOpen }">
        @include('partials.sidenav')
        <div class="flex flex-col flex-1 w-full">
            @include('partials.header')
            <main class="h-full overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
    @extends('partials.link')
</body>

</html>
