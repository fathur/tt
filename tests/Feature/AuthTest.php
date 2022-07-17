<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_with_unregistered_email()
    {
        $response = $this->post('/api/register', [
            'name'  => 'User One',
            'email' => 'user1@trutrip.com',
            'password' => 'abc123TYU@!'
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.token', fn ($token) => strlen($token) >= 1);
    }

    public function test_register_with_registered_email()
    {
        $user = User::factory()->create([
            'email' => 'user2@trutrip.com'
        ]);

        $response = $this->post('/api/register', [
            'name'  => 'User One',
            'email' => 'user2@trutrip.com',
            'password' => 'abc123TYU!@'
        ]);

        $response->assertStatus(422);
    }

    public function test_register_with_weak_password()
    {
        $response = $this->post('/api/register', [
            'name'  => 'User One',
            'email' => 'user1@trutrip.com',
            'password' => '12345'
        ]);

        $response->assertStatus(422);
    }

    public function test_login_with_correct_crendetials()
    {
        $user = User::factory()->create([
            'email' => 'user3@trutrip.com',
            'password' => bcrypt('abc123ABC@#!')
        ]);

        $response = $this->post('/api/login', [
            'email' => 'user3@trutrip.com',
            'password' => 'abc123ABC@#!'
        ]);

        $response->assertStatus(200);
    }

    public function test_login_with_wrong_email()
    {
        $user = User::factory()->create([
            'email' => 'user3@trutrip.com',
            'password' => bcrypt('abc123ABC@#!')
        ]);

        $response = $this->post('/api/login', [
            'email' => 'user4@trutrip.com',
            'password' => 'abc123ABC@#!'
        ]);

        $response->assertStatus(422);
    }

    public function test_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'user3@trutrip.com',
            'password' => bcrypt('abc123ABC@#!')
        ]);

        $response = $this->post('/api/login', [
            'email' => 'user3@trutrip.com',
            'password' => 'aabc123ABC@#!'
        ]);

        $response->assertStatus(422);
    }
}
