<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Hutang;
use App\Models\CicilanHutang;
use App\Models\Rekap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;

class HutangTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase, WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        
        // $this->withoutExceptionHandling();
        $this->artisan('db:seed');
    }

    
}