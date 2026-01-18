<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;

class UserTest extends TestCase
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

    public function test_list_users(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        User::factory()->count(5)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data');
    }

    public function test_show_user(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }
    public function test_cannot_show_other_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->getJson("/api/users/{$otherUser->id}");

        $response->assertStatus(403);
    }

    public function test_admin_show_any_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $targetUser = User::factory()->create();

        Passport::actingAs($admin);

        $response = $this->getJson("/api/users/{$targetUser->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_update_own_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com'
        ]);

        Passport::actingAs($user);

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'New Name',
            'email' => 'new@example.com'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com'
        ]);
    }

    public function test_cannot_update_other_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create(['name' => 'Original Name']);

        Passport::actingAs($user);

        $response = $this->putJson("/api/users/{$otherUser->id}", [
            'name' => 'Hacked Name'
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $otherUser->id,
            'name' => 'Original Name'
        ]);
    }

    public function test_admin_delete_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $userToDelete = User::factory()->create();

        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/users/{$userToDelete->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id
        ]);
    }

    public function test_user_cannot_delete_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/users/{$otherUser->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $otherUser->id
        ]);
    }           

    public function test_user_can_see_own_stats(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->getJson("/api/users/{$user->id}/stats");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_bookings',
                    'confirmed',
                    'cancelled',
                    'attended',
                    'no_show'
                ]
            ]);
    }

    public function test_user_cannot_see_other_stats(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->getJson("/api/users/{$user2->id}/stats");

        $response->assertStatus(403);
    }
}
