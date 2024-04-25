<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function testUserTriesToGetAllowedGroupRessourceShouldSucceed()
    {
        $user = \App\Models\User::factory()->create();
        $group = \App\Models\Groupe::factory()->create();

        // Add the user to the group
        $group->members()->attach($user->id);

        $response = $this->actingAs($user)->get('/groups/' . $group->id );

        $response->assertStatus(200);
    }

    public function testUserTriesToGetNotAllowedGroupRessourceShouldFail()
    {
        $user = \App\Models\User::factory()->create();
        $group = \App\Models\Groupe::factory()->create();

        $response = $this->actingAs($user)->get('/groups/' . $group->id );

        $response->assertStatus(403);
    }

    public function testUserOwnsGroupShouldSucceed()
    {
        $user = \App\Models\User::factory()->create();
        $group = \App\Models\Groupe::factory()->create(['proprietaire_id' => $user->id]);

        $group->members()->attach($user->id);

        $response = $this->actingAs($user)->delete('/groups/' . $group->id );

        $response->assertStatus(204);
    }

    public function testUserOwnsGroupShouldFail()
    {
        $user = \App\Models\User::factory()->create();
        $user2 = \App\Models\User::factory()->create();
        $group = \App\Models\Groupe::factory()->create(['proprietaire_id' => $user->id]);

        $group->members()->attach($user->id);
        $group->members()->attach($user2->id);

        $response = $this->actingAs($user2)->delete('/groups/' . $group->id );

        $response->assertStatus(403);
    }
}
