<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\{User, Trip};

class TripTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_trip_with_authorized_user()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->post('/api/trips', [
           'title' => 'Jalan-jalan ke Karimun',
           'origin' => 'Mampang Prapatan, Jakarta Selatan, DKI Jakarta, Indonesia',
           'destination' => 'Karimun Jawa, Jepara, Jawa Tengah, Indonesia',
           'description' => 'Trip liburan kantor bersama ke tempat yang indah'
        ]);

        $response->assertStatus(201);
        
    }

    public function test_create_trip_with_unauthenticated_user()
    {
        $response = $this->post('/api/trips', [
           'title' => 'Jalan-jalan ke Karimun',
           'origin' => 'Mampang Prapatan, Jakarta Selatan, DKI Jakarta, Indonesia',
           'destination' => 'Karimun Jawa, Jepara, Jawa Tengah, Indonesia',
           'description' => 'Trip liburan kantor bersama ke tempat yang indah'
        ]);

        $response->assertStatus(401);
        
    }

    public function test_view_trip_with_authorized_user()
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();

        $token = auth()->login($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->get("/api/trips/{$trip->id}");
 
        $response->assertStatus(200);
            
    }

    public function test_view_trip_with_unauthorized_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $trip = Trip::factory()->for($user1)->create();

        $token = auth()->login($user2);


        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->get("/api/trips/{$trip->id}");
 
        $response->assertStatus(403);
    }

    public function test_view_trip_with_unauthenticated_user()
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();

        $response = $this->get("/api/trips/{$trip->id}");
 
        $response->assertStatus(401);
    }

    public function test_update_trip_with_authenticated_user()
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();

        $token = auth()->login($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->put("/api/trips/{$trip->id}", [
            'title' => 'Updated title',
            'origin' => 'Updated origin',
            'destination' => 'Updated destination',
            'description' => 'Updated description',
        ]);
 
        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Updated title')
            ->assertJsonPath('data.origin', 'Updated origin')
            ->assertJsonPath('data.destination', 'Updated destination')
            ->assertJsonPath('data.description', 'Updated description');
    }

    public function test_update_trip_with_unauthorized_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $trip = Trip::factory()->for($user1)->create();

        $token = auth()->login($user2);


        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->put("/api/trips/{$trip->id}", [
            'title' => 'Updated title',
            'origin' => 'Updated origin',
            'destination' => 'Updated destination',
            'description' => 'Updated description',
        ]);
 
        $response->assertStatus(403);
    }

    public function test_update_trip_with_unauthenticated_user()
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();

        $response = $this->put("/api/trips/{$trip->id}", [
            'title' => 'Updated title',
            'origin' => 'Updated origin',
            'destination' => 'Updated destination',
            'description' => 'Updated description',
        ]);
 
        $response->assertStatus(401);
    }

    public function test_delete_trip_with_authenticated_user()
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();

        $token = auth()->login($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->delete("/api/trips/{$trip->id}");

        $response->assertStatus(204);

    }

    public function test_delete_trip_with_unauthorized_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $trip = Trip::factory()->for($user1)->create();

        $token = auth()->login($user2);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->delete("/api/trips/{$trip->id}");

        $response->assertStatus(403);
    }

    public function test_delete_trip_with_unauthenticated_user()
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();

        $response = $this->delete("/api/trips/{$trip->id}");

        $response->assertStatus(401);
    }
}
