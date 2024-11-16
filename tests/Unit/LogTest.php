<?php

namespace Tests\Unit;

use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase; // Change this line to extend Laravel's TestCase
use Spatie\Activitylog\Models\Activity;

class LogTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Refresh database and run all seeders
        $this->artisan('db:seed');
    }
    public function test_user_can_serching_log_activity_using_event(): void
    {
        // Authenticate the user
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);


        Activity::create([
            'log_name' => 'Produksi',
            'description' => 'User Pawonkoe created a new product',
            'event' => 'created product',
            'causer_id' => Auth::user()->id,
            'subject_type' => 'App\Models\Produksi',
            'subject_id' => 1,
        ]);

        Activity::create([
            'log_name' => 'Produksi',
            'description' => 'User Pawonkoe updated a product',
            'event' => 'updated product',
            'causer_id' => Auth::user()->id,
            'subject_type' => 'App\Models\Produksi',
            'subject_id' => 2,
        ]);

        $response = $this->get(route('log-activities.index', ['search' => 'created']));

        // Assert the response contains the expected data
        $response->assertStatus(200);
        $response->assertSee('User Pawonkoe created a new product');
        $response->assertDontSee('User Pawonkoe updated a product');

    }
    public function test_user_can_serching_log_activity_using_description(): void
    {
        // Authenticate the user
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);


        Activity::create([
            'log_name' => 'Produksi',
            'description' => 'User Pawonkoe created a new product',
            'event' => 'created product',
            'causer_id' => Auth::user()->id,
            'subject_type' => 'App\Models\Produksi',
            'subject_id' => 1,
        ]);

        Activity::create([
            'log_name' => 'Produksi',
            'description' => 'User Pawonkoe updated a product',
            'event' => 'updated product',
            'causer_id' => Auth::user()->id,
            'subject_type' => 'App\Models\Produksi',
            'subject_id' => 2,
        ]);

        $response = $this->get(route('log-activities.index', ['search' => 'User Pawonkoe created a new product']));

        // Assert the response contains the expected data
        $response->assertStatus(200);
        $response->assertSee('User Pawonkoe created a new product');
        $response->assertDontSee('User Pawonkoe updated a product');
    }
}
