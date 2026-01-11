<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Session;
use Laravel\Passport\Passport;

class SessionTest extends TestCase
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

    public function test_can_list_sessions(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        Session::factory()->count(4)->create();

        $response = $this->getJson('/api/sessions');

        $response->assertStatus(200)
                 ->assertJsonCount(4, 'data');
    }

    public function test_can_show_session(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $session = Session::factory()->create();

        $response = $this->getJson("/api/sessions/{$session->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $session->id,
                         'gym_class_id' => $session->gym_class_id,
                         'date' => $session->date,
                         'start_time' => $session->start_time,
                         'end_time' => $session->end_time,
                         'room' => $session->room,
                         'max_capacity' => $session->max_capacity,
                         'current_bookings' => $session->current_bookings,
                     ]
                 ]);
    }

    public function test_can_create_session(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $gymClass = \App\Models\GymClass::factory()->create();

        $data = [
            'gym_class_id' => $gymClass->id,
            'date' => '2026-02-15',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'room' => 'Sala 1',
            'max_capacity' => 20,
        ];

        $response = $this->postJson('/api/sessions', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => $data
                 ]);

        $this->assertDatabaseHas('gym_sessions', $data);
    }
}
