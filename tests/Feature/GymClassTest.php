<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\GymClass;
use Laravel\Passport\Passport;

class GymClassTest extends TestCase
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

    public function test_can_list_gym_classes(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        GymClass::factory()->count(3)->create();

        $response = $this->getJson('/api/gym-classes');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_can_create_gym_class(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'Yoga',
            'description' => 'Relaxing yoga class',
            'duration' => 60,
            'max_capacity' => 20,
        ];

        $response = $this->postJson('/api/gym-classes', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => $data
                 ]);

        $this->assertDatabaseHas('gym_classes', $data);
    }
}
