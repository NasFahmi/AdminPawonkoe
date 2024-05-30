<!-- Desktop sidebar -->
<aside class="z-20 hidden w-64 overflow-y-auto bg-white md:block flex-shrink-0 relative">
    <div class="py-4 text-gray-500">
        <div class="flex justify-start items-center">
            <a href="{{ route('admin.dashboard') }}" class="ml-6">
                <img src="{{ asset('assets/images/logo.png') }}" alt="" class="w-10 h-auto">
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-800">PAWONKOE</h1>
            </div>
        </div>
        <ul class="mt-6">
            <li class="relative px-6 py-3 group">
                @if (Request::is('admin/dashboard*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                        aria-hidden="true"></span>
                @endif
                <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/dashboard*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                    href="{{ route('admin.dashboard') }}">
                    <img class="w-5 h-5
                    @if (Request::is('admin/dashboard*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                        src="{{ asset('assets/icon/home.png') }}" alt="" srcset="">
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>

        </ul>

        <ul>
            <li class="relative px-6 py-3 group">
                @if (Request::is('admin/transaksi*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                        aria-hidden="true"></span>
                @endif
                <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/transaksi*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                    href="{{ route('transaksis.index') }}">
                    <img class="w-5 h-5
                    @if (Request::is('admin/transaksi*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                        src="{{ asset('assets/icon/clipboard-list-check.png') }}" alt="" srcset="">
                    <span class="ml-4">Transaksi</span>
                </a>
            </li>
            <li class="relative px-6 py-3 group">
                @if (Request::is('admin/product*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                        aria-hidden="true"></span>
                @endif
                <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/product*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                    href="{{ route('products.index') }}">
                    <img class="w-5 h-5
                    @if (Request::is('admin/product*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                        src="{{ asset('assets/icon/box-open.png') }}" alt="" srcset="">
                    <span class="ml-4">Produk</span>
                </a>
            </li>

            <li class="relative px-6 py-3 group">
                @if (Request::is('admin/preorder*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                        aria-hidden="true"></span>
                @endif
                <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/preorder*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                    href="{{ route('preorders.index') }}">
                    <img class="w-5 h-5
                    @if (Request::is('admin/preorder*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                        src="{{ asset('assets/icon/shipping-timed.png') }}" alt="" srcset="">
                    <span class="ml-4">Preorder</span>
                </a>
            </li>

            @if (auth()->check() && auth()->user()->hasRole('superadmin'))

                <li class="relative px-6 py-3 group">
                    @if (Request::is('admin/piutang*'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif
                    <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/piutang*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                        href="{{ route('piutang.index') }}">
                        <img class="w-5 h-5
                    @if (Request::is('admin/piutang*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                            src="{{ asset('assets/icon/sack-dollar.png') }}" alt="" srcset="">
                        <span class="ml-4">Piutang</span>
                    </a>
                </li>
            @endif

            @if (auth()->check() && auth()->user()->hasRole('superadmin'))

                <li class="relative px-6 py-3 group">
                    @if (Request::is('admin/hutang*'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif
                    <a class="inline-flex items-center w-full text-sm font-semibold 
            @if (Request::is('admin/hutang*')) text-gray-800
            @else
                text-gray-500 @endif
            transition-colors duration-150 group-hover:text-gray-800"
                        href="{{ route('hutang.index') }}">
                        <img class="w-5 h-5
                @if (Request::is('admin/hutang*')) opacity-100
                @else
                    opacity-60 @endif
                group-hover:opacity-100
                "
                            src="{{ asset('assets/icon/business-credit-report.png') }}" alt="" srcset="">
                        <span class="ml-4">Hutang</span>
                    </a>
                </li>
            @endif


            @if (auth()->check() && auth()->user()->hasRole('superadmin'))

                <li class="relative px-6 py-3 group">
                    @if (Request::is('admin/beban-kewajiban*'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif
                    <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/beban-kewajiban*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                        href="{{ route('beban-kewajibans.index') }}">
                        <img class="w-5 h-5
                    @if (Request::is('admin/beban-kewajiban*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                            src="{{ asset('assets/icon/calculator-bill.png') }}" alt="" srcset="">
                        <span class="ml-4">Beban & Kewajiban</span>
                    </a>
                </li>


            @endif
            @if (auth()->check() && auth()->user()->hasRole('superadmin'))

                <li class="relative px-6 py-3 group">
                    @if (Request::is('admin/modal*'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif
                    <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/modal*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                        href="{{ route('modal.index') }}">
                        <img class="w-5 h-5
                    @if (Request::is('admin/modal*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                            src="{{ asset('assets/icon/deposit.png') }}" alt="" srcset="">
                        <span class="ml-4">Modal</span>
                    </a>
                </li>


            @endif

            <li class="relative px-6 py-3 group">
                @if (Request::is('admin/produksi*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                        aria-hidden="true"></span>
                @endif
                <a class="inline-flex items-center w-full text-sm font-semibold 
            @if (Request::is('admin/produksi*')) text-gray-800
            @else
                text-gray-500 @endif
            transition-colors duration-150 group-hover:text-gray-800"
                    href="{{ route('produksi.index') }}">
                    <img class="w-5 h-5
                @if (Request::is('admin/produksi*')) opacity-100
                @else
                    opacity-60 @endif
                group-hover:opacity-100
                "
                        src="{{ asset('assets/icon/microwave.png') }}" alt="" srcset="">
                    <span class="ml-4">Produksi</span>
                </a>
            </li>

            @if (auth()->check() && auth()->user()->hasRole('superadmin'))

                <li class="relative px-6 py-3 group">
                    @if (Request::is('admin/log-activities*'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif
                    <a class="inline-flex items-center w-full text-sm font-semibold 
            @if (Request::is('admin/log-activities*')) text-gray-800
            @else
                text-gray-500 @endif
            transition-colors duration-150 group-hover:text-gray-800"
                        href="{{ route('log-activities.index') }}">
                        <img class="w-5 h-5
                @if (Request::is('admin/beban-kewajiban*')) opacity-100
                @else
                    opacity-60 @endif
                group-hover:opacity-100
                "
                            src="{{ asset('assets/icon/wall-clock.png') }}" alt="" srcset="">
                        <span class="ml-4">Log Aktivitas</span>
                    </a>
                </li>


            @endif

        </ul>

    </div>
    <div class="absolute bottom-1 w-full flex justify-center items-center mb-4">
        <p class="text-gray-700">{{ env('APP_VERSION') }}</p>
    </div>
</aside>
<!-- Mobile sidebar -->
<!-- Backdrop -->
<div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>
<aside class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white md:hidden"
    x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 transform -translate-x-20" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 transform -translate-x-20" @click.away="closeSideMenu"
    @keydown.escape="closeSideMenu">
    <div class="py-4 text-gray-500">
        <div class="flex justify-start items-center">
            <a href="" class="ml-6">
                <img src="{{ asset('assets/images/logo.png') }}" alt="">
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">PawonKOE</h1>
            </div>
        </div>
        <ul class="mt-6">
            <li class="relative px-6 py-3 group">
                @if (Request::is('admin/dashboard*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                        aria-hidden="true"></span>
                @endif
                <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/dashboard*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                    href="{{ route('admin.dashboard') }}">
                    <img class="w-5 h-5
                    @if (Request::is('admin/dashboard*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                        src="{{ asset('assets/icon/home.png') }}" alt="" srcset="">
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>

        </ul>

        <ul>
            <li class="relative px-6 py-3 group">
                @if (Request::is('admin/transaksi*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                        aria-hidden="true"></span>
                @endif
                <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/transaksi*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                    href="{{ route('transaksis.index') }}">
                    <img class="w-5 h-5
                    @if (Request::is('admin/transaksi*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                        src="{{ asset('assets/icon/clipboard-list-check.png') }}" alt="" srcset="">
                    <span class="ml-4">Transaksi</span>
                </a>
            </li>
            <li class="relative px-6 py-3 group">
                @if (Request::is('admin/product*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                        aria-hidden="true"></span>
                @endif
                <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/product*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                    href="{{ route('products.index') }}">
                    <img class="w-5 h-5
                    @if (Request::is('admin/product*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                        src="{{ asset('assets/icon/box-open.png') }}" alt="" srcset="">
                    <span class="ml-4">Produk</span>
                </a>
            </li>

            <li class="relative px-6 py-3 group">
                @if (Request::is('admin/preorder*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                        aria-hidden="true"></span>
                @endif
                <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/preorder*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                    href="{{ route('preorders.index') }}">
                    <img class="w-5 h-5
                    @if (Request::is('admin/preorder*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                        src="{{ asset('assets/icon/shipping-timed.png') }}" alt="" srcset="">
                    <span class="ml-4">Preorder</span>
                </a>
            </li>

            @if (auth()->check() && auth()->user()->hasRole('superadmin'))

                <li class="relative px-6 py-3 group">
                    @if (Request::is('admin/piutang*'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif
                    <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/piutang*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                        href="{{ route('piutang.index') }}">
                        <img class="w-5 h-5
                    @if (Request::is('admin/piutang*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                            src="{{ asset('assets/icon/sack-dollar.png') }}" alt="" srcset="">
                        <span class="ml-4">Piutang</span>
                    </a>
                </li>
            @endif

            @if (auth()->check() && auth()->user()->hasRole('superadmin'))

                <li class="relative px-6 py-3 group">
                    @if (Request::is('admin/hutang*'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif
                    <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/hutang*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                        href="{{ route('hutang.index') }}">
                        <img class="w-5 h-5
                    @if (Request::is('admin/hutang*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                            src="{{ asset('assets/icon/business-credit-report.png') }}" alt=""
                            srcset="">
                        <span class="ml-4">Hutang</span>
                    </a>
                </li>
            @endif




            @if (auth()->check() && auth()->user()->hasRole('superadmin'))

                <li class="relative px-6 py-3 group">
                    @if (Request::is('admin/beban-kewajiban*'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif
                    <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/beban-kewajiban*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                        href="{{ route('beban-kewajibans.index') }}">
                        <img class="w-5 h-5
                    @if (Request::is('admin/beban-kewajiban*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                            src="{{ asset('assets/icon/calculator-bill.png') }}" alt="" srcset="">
                        <span class="ml-4">Beban & Kewajiban</span>
                    </a>
                </li>


            @endif

@if (auth()->check() && auth()->user()->hasRole('superadmin'))

                <li class="relative px-6 py-3 group">
                    @if (Request::is('admin/modal*'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif
                    <a class="inline-flex items-center w-full text-sm font-semibold 
                @if (Request::is('admin/modal*')) text-gray-800
                @else
                    text-gray-500 @endif
                transition-colors duration-150 group-hover:text-gray-800"
                        href="{{ route('modal.index') }}">
                        <img class="w-5 h-5
                    @if (Request::is('admin/modal*')) opacity-100
                    @else
                        opacity-60 @endif
                    group-hover:opacity-100
                    "
                            src="{{ asset('assets/icon/deposit.png') }}" alt="" srcset="">
                        <span class="ml-4">Modal</span>
                    </a>
                </li>


            @endif

            <li class="relative px-6 py-3 group">
                @if (Request::is('admin/produksi*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                        aria-hidden="true"></span>
                @endif
                <a class="inline-flex items-center w-full text-sm font-semibold 
            @if (Request::is('admin/produksi*')) text-gray-800
            @else
                text-gray-500 @endif
            transition-colors duration-150 group-hover:text-gray-800"
                    href="{{ route('produksi.index') }}">
                    <img class="w-5 h-5
                @if (Request::is('admin/produksi*')) opacity-100
                @else
                    opacity-60 @endif
                group-hover:opacity-100
                "
                        src="{{ asset('assets/icon/microwave.png') }}" alt="" srcset="">
                    <span class="ml-4">Produksi</span>
                </a>
            </li>


            @if (auth()->check() && auth()->user()->hasRole('superadmin'))

                <li class="relative px-6 py-3 group">
                    @if (Request::is('admin/log-activities*'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif
                    <a class="inline-flex items-center w-full text-sm font-semibold 
            @if (Request::is('admin/log-activities*')) text-gray-800
            @else
                text-gray-500 @endif
            transition-colors duration-150 group-hover:text-gray-800"
                        href="{{ route('log-activities.index') }}">
                        <img class="w-5 h-5
                @if (Request::is('admin/beban-kewajiban*')) opacity-100
                @else
                    opacity-60 @endif
                group-hover:opacity-100
                "
                            src="{{ asset('assets/icon/wall-clock.png') }}" alt="" srcset="">
                        <span class="ml-4">Log Aktivitas</span>
                    </a>
                </li>


            @endif

        </ul>
    </div>
    <div class="absolute bottom-1 w-full flex justify-center items-center mb-4">
        <p class="text-gray-700">{{ env('APP_VERSION') }}</p>
    </div>
</aside>
