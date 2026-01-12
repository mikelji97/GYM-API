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
    public function test_can_update_session(): void  
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $session = Session::factory()->create(); 
        $newGymClass = \App\Models\GymClass::factory()->create();

        $updatedData = [  
            'gym_class_id' => $newGymClass->id,
            'date' => '2026-03-20',
            'start_time' => '14:00:00',
            'end_time' => '15:30:00',
            'room' => 'Sala 2',
            'max_capacity' => 25,
        ];

        $response = $this->putJson("/api/sessions/{$session->id}", $updatedData); 

        $response->assertStatus(200)  
            ->assertJson([
                'data' => $updatedData
            ]);

        $this->assertDatabaseHas('gym_sessions', $updatedData);
    }
    public function test_can_delete_session(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $session = Session::factory()->create();

        $response = $this->deleteJson("/api/sessions/{$session->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('gym_sessions', ['id' => $session->id]);
    }

    public function test_available_session(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        Session::factory()->create(['max_capacity' => 10, 'current_bookings' => 5]);
        Session::factory()->create(['max_capacity' => 10, 'current_bookings' => 10]);
        Session::factory()->create(['max_capacity' => 20, 'current_bookings' => 15]);
        Session::factory()->create(['max_capacity' => 50, 'current_bookings' => 5]);
        Session::factory()->create(['max_capacity' => 20, 'current_bookings' => 25]);

        $response = $this->getJson('/api/sessions/available');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}