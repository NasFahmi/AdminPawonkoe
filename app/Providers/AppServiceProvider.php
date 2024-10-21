<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use App\Models\Rekap;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Dapatkan daftar tahun dari transaksi
            $daftarTahun = Rekap::selectRaw('YEAR(tanggal_transaksi) as year')
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->get()
                ->pluck('year');
            
            // Berbagi variabel daftarTahun ke semua view
            $view->with('daftarTahun', $daftarTahun);
        });
    }
}
