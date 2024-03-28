@extends('layout.pages')
@section('title', 'Login')
@section('content')
    <div class="flex items-center min-h-screen p-6 bg-slate-400">
        <div class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl pt-10 md:pt-0">
            <h1 class="text-center md:text-start mb-4 md:ml-10 mt-5 text-xl font-semibold text-slate-400 ">PAWONKOE</h1>
            <div class="flex flex-col overflow-y-auto md:flex-row">
                <div class="h-32 md:h-auto md:w-1/2">
                    <img aria-hidden="true" class="object-contain w-full h-full "
                        src="{{ Vite::asset('resources/images/login-ilustration.svg') }}" alt="Office" />
                </div>
                <div class="flex mb-14 items-center justify-center p-6 sm:p-12 md:w-1/2">
                    <div class="w-full">
                        <h1 class="mb-4 text-xl font-semibold text-gray-700">
                            Login
                        </h1>
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            @if ($errors->has('login'))
                                <p class="text-red-500 text-sm italic">{{ $errors->first('login') }}</p>
                            @endif
                            <label class="block text-sm">
                                <span class="text-gray-700 ">Username</span><span class="text-red-600 ">*</span>
                                <input name="nama" type="text"
                                    class="block w-full mt-1 text-sm border-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue form-input rounded-md "
                                    placeholder="Username" value="{{ old('nama') }}" />
                                @error('nama')
                                    <p class="text-red-500 text-sm italic">{{ $message }}</p>
                                @enderror
                            </label>
                            <label class="block mt-4 text-sm">
                                <span class="text-gray-700 ">Password</span><span class="text-red-600 ">*</span>
                                <input name="password"
                                    class="block w-full mt-1 text-sm border-gray-200 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue form-input rounded-md"
                                    placeholder="***************" type="password" value="{{ old('password') }}" />
                                @error('password')
                                    <p class="text-red-500 text-sm italic">{{ $message }}</p>
                                @enderror
                            </label>

                            <button type="submit"
                                class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-[#db9436] border border-transparent rounded-lg active:bg-blue-600 focus:outline-none focus:shadow-outline-purple">
                                Log in
                            </button>
                            @if (session()->has('login'))
                                <div id="loginErrorMessage" class="mt-5 text-red-400 font-semibold text-md text-center">
                                    {{ session('login') }}
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        setInterval(function() {
            var loginErrorMessage = document.getElementById('loginErrorMessage');
            if (loginErrorMessage) {
                // Do something if login error message is present
                console.log('Login error message found.');
            }
        }, 1000); // Check every 1 second
    </script>
@endpush
