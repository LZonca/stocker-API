<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginWithCorrectArgumentsShouldLogin(): void
    {
        // Create a user to authenticate with
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456789'),
        ]);

        // Authenticate as the created user
        $this->actingAs($user);

        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => '123456789'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'token'
        ]);
    }

    public function testLoginWithArgumentsShouldNotLogin(): void
    {
        // Create a user to authenticate with
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456789'),
        ]);

        // Authenticate as the created user
        $this->actingAs($user);

        $response = $this->post('/login');

        $response->assertStatus(404);
    }

    public function testLoginWithWrongArgumentsShouldNotLogin(): void
    {
        // Create a user to authenticate with
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456789'),
        ]);

        // Authenticate as the created user
        $this->actingAs($user);

        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(404);
    }

}
