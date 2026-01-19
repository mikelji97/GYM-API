<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Session;
use App\Models\Booking;
use Laravel\Passport\Passport;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Passport::loadKeysFrom(storage_path());

        \DB::table('oauth_clients')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'owner_type' => null,
            'owner_id' => null,
            'name' => 'Test Personal Access Client',
            'secret' => null,
            'provider' => 'users',
            'redirect_uris' => json_encode(['http://localhost']),
            'grant_types' => json_encode(['personal_access']),
            'revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_list_bookings(): void
    {
        $maria = User::factory()->create();
        $pedro = User::factory()->create();
        Passport::actingAs($maria);

        $yogaSession = Session::factory()->create();
        Booking::factory()->create(['user_id' => $maria->id, 'session_id' => $yogaSession->id]);
        Booking::factory()->create(['user_id' => $pedro->id]);

        $response = $this->getJson('/api/bookings');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_admin_list_all_bookings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Passport::actingAs($admin);

        Booking::factory()->count(5)->create();

        $response = $this->getJson('/api/bookings');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }
    public function test_user_only__own_bookings(): void
{
    $mikel = User::factory()->create();
    $jesus = User::factory()->create();

    Passport::actingAs($mikel);

    Booking::factory()->count(2)->create([
        'user_id' => $mikel->id,
    ]);

    Booking::factory()->create([
        'user_id' => $jesus->id,
    ]);

    $response = $this->getJson('/api/bookings/my-bookings');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
}
public function test_my_bookings_requires_authentication(): void
{
    $response = $this->getJson('/api/bookings/my-bookings');

    $response->assertStatus(401);
}
public function test_my_bookings_empty(): void
{
    $mikel = User::factory()->create();
    Passport::actingAs($mikel);

    $response = $this->getJson('/api/bookings/my-bookings');

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');
}
   public function test_can_create_booking(): void
    {
        $mikel = User::factory()->create();
        Passport::actingAs($mikel);

        $session = Session::factory()->create([
            'max_capacity' => 14,
            'current_bookings' => 6,
        ]);

        Booking::factory()->create([
            'user_id' => $mikel->id,
            'session_id' => $session->id,
        ]);

        $response = $this->postJson('/api/bookings', [
            'session_id' => $session->id,
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_duplicate_booking(): void
    {
        $laura = User::factory()->create();
        Passport::actingAs($laura);

        $pilatesSession = Session::factory()->create([
            'max_capacity' => 20,
            'current_bookings' => 5,
        ]);

        $response = $this->postJson('/api/bookings', [
            'session_id' => $pilatesSession->id,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $laura->id,
            'session_id' => $pilatesSession->id,
            'status' => 'confirmed',
        ]);
    }
    
    public function test_cannot_book_full_session(): void
    {
        $ana = User::factory()->create();
        Passport::actingAs($ana);

        $spinningSession = Session::factory()->create([
            'max_capacity' => 10,
            'current_bookings' => 10,
        ]);

        $response = $this->postJson('/api/bookings', [
            'session_id' => $spinningSession->id,
        ]);

        $response->assertStatus(422);
    }

}