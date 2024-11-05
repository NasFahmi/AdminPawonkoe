<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RekapTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function setUp(): void
    {
        parent::setUp();
        
        // $this->withoutExceptionHandling();

        Artisan::call('migrate --seed');
    }

    public function test_make_rekap_from_transaksi_successfully(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_make_rekap_from_piutang_successfully(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_make_rekap_from_modal_successfully(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_make_rekap_from_hutang_successfully(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_make_rekap_from_bebanKewajiban_successfully(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_see_rekap_income_kategori_by_month(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_see_rekap_income_kategori_by_year(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_see_rekap_income_kategori_by_year_and_month(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_see_rekap_expenses_kategori_by_month(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_see_rekap_expenses_kategori_by_year(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_see_rekap_expenses_kategori_by_year_and_month(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_see_rekap_total_income(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_see_rekap_total_expenses(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_see_rekap_final_balance(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_print_rekap(): void
    {
        $this->withoutExceptionHandling();
    }

    public function test_finding_rekap_by_sumber(): void
    {
        $this->withoutExceptionHandling();
    }
}
