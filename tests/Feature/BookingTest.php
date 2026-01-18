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
}