<?php

use App\Models\SeniorCitizen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays numeric age in barangay report modal', function () {
    now()->setTestNow('2026-02-25');
    $barangay = \App\Constants\Barangay::list()[0];

    $senior = SeniorCitizen::factory()->create([
        'barangay' => $barangay,
        'date_of_birth' => '1950-05-15',
    ]);
    $senior->calculateAge();
    $senior->save();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.barangay', ['barangay' => $barangay]));
    $response->assertStatus(200);
    $response->assertSee((string) $senior->age);
    // the old exact_age format uses "yrs"/"mos"; ensure those substrings are absent
    $response->assertDontSee('yrs');
    $response->assertDontSee('mos');
});

it('displays numeric age in health report modal', function () {
    now()->setTestNow('2026-02-25');
    $senior = SeniorCitizen::factory()->create([
        'with_disability' => true,
        'date_of_birth' => '1950-05-15',
    ]);
    $senior->calculateAge();
    $senior->save();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.health', ['condition' => 'with_disability']));
    $response->assertStatus(200);
    $response->assertSee((string) $senior->age);
    $response->assertDontSee('yrs');
    $response->assertDontSee('mos');
});

it('displays numeric age in deceased/archived report', function () {
    now()->setTestNow('2026-02-25');
    $senior = SeniorCitizen::factory()->create([
        'deleted_at' => now(),
        'date_of_birth' => '1950-05-15',
    ]);
    $senior->calculateAge();
    $senior->save();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.deceased'));
    $response->assertStatus(200);
    $response->assertSee((string) $senior->age);
    $response->assertDontSee('yrs');
    $response->assertDontSee('mos');
});
