<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function testUserTriesToGetAllowedRessourceShouldSucceed()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/users/' . $user->id . '/groups');

        $response->assertStatus(200);
    }

    public function testUserTriesToGetNotAllowedRessourceShouldFail()
    {
        $user = \App\Models\User::factory()->create();
        \App\Models\User::factory()->count(2)->create();

        $response = $this->actingAs($user)->get('/users/2/groups');

        $response->assertStatus(403);
    }
}
