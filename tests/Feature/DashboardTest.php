<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows main sections as cards on dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertStatus(200);

    // each card title should be visible
    $response->assertSee('Masterlist');
    $response->assertSee('SPISC');
    $response->assertSee('Reports');
    $response->assertSee('Change History');
    $response->assertSee('Quick Stats');

    // links should point to the correct routes
    $response->assertSee(route('senior-citizens.index'));
    $response->assertSee(route('spisc.index'));
    $response->assertSee(route('reports.index'));
    $response->assertSee(route('history'));
});
