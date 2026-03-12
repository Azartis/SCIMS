<?php

use App\Models\SeniorCitizen;
use App\Models\PensionDistribution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows deceased status filter on spisc and returns correct results', function () {
    // setup three recipients: alive, dead, and also dead but not pensioner
    $alive = SeniorCitizen::factory()->create(['social_pension' => true]);
    PensionDistribution::factory()->create(['senior_citizen_id' => $alive->id]);

    $dead = SeniorCitizen::factory()->create(['social_pension' => true, 'date_of_death' => now()->subDays(10)->format('Y-m-d')]);
    PensionDistribution::factory()->create(['senior_citizen_id' => $dead->id]);

    $other = SeniorCitizen::factory()->create(['social_pension' => false, 'date_of_death' => now()->subDays(5)->format('Y-m-d')]);

    $user = User::factory()->create();

    // no filter returns both alive and dead but not the non-pensioner
    $response = $this->actingAs($user)->get(route('spisc.index'));
    $response->assertStatus(200);
    $response->assertSee((string) $alive->lastname);
    $response->assertSee((string) $dead->lastname);
    $response->assertDontSee((string) $other->lastname);

    // filter alive only
    $response = $this->actingAs($user)->get(route('spisc.index', ['deceased' => 'alive']));
    $response->assertStatus(200);
    $response->assertSee((string) $alive->lastname);
    $response->assertDontSee((string) $dead->lastname);

    // filter deceased only
    $response = $this->actingAs($user)->get(route('spisc.index', ['deceased' => 'only']));
    $response->assertStatus(200);
    $response->assertSee((string) $dead->lastname);
    $response->assertDontSee((string) $alive->lastname);
});
