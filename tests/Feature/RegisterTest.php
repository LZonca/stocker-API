<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterWithCorrectArguments()
    {
        $response = $this->post('/register', [
            'name' => 'Mike hock',
            'email' => 'mh@test.com',
            'password' => '123456789',
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'user' => [
                'name',
                'email',
            ],
            'token',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Mike hock',
            'email' => 'mh@test.com',
        ]);
    }

    public function testRegisterWithWrongArguments()
    {
        $response = $this->post('/register', [
            'name' => 'Mike hock',
            'email' => 'email',
            'password' => '123456789',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('users', [
            'name' => 'Mike hock',
            'email' => 'mh@test.com',
        ]);
    }
}
