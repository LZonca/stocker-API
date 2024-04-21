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

        $response = $this->actingAs($user)->get('/user/groups');

        $response->assertStatus(200);
    }

/*    public function testUserTriesToGetNotAllowedRessourceShouldFail() // Test is outdated from the API changes
    {
        $user = \App\Models\User::factory()->create();
        $user2 = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/user/groups');

        $response->assertStatus(403);
    }*/
}
