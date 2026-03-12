<?php

use App\Models\SeniorCitizen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('orders the masterlist by name according to sort parameter', function () {
    $user = User::factory()->create();

    SeniorCitizen::factory()->create(['lastname' => 'Zulu', 'firstname' => 'Zed']);
    SeniorCitizen::factory()->create(['lastname' => 'Alpha', 'firstname' => 'Aaron']);

    $asc = $this->actingAs($user)->get('/senior-citizens?sort=asc');
    $desc = $this->actingAs($user)->get('/senior-citizens?sort=desc');

    $asc->assertSeeInOrder(['Alpha', 'Zulu']);
    $desc->assertSeeInOrder(['Zulu', 'Alpha']);
});