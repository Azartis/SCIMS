<?php

use App\Models\SeniorCitizen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('barangay modal filters by search name', function () {
    now()->setTestNow('2026-02-26');
    $barangay = \App\Constants\Barangay::list()[0];

    $withName = SeniorCitizen::factory()->create([
        'barangay' => $barangay,
        'firstname' => 'John',
        'lastname' => 'Doe',
        'date_of_birth' => '1950-05-15',
    ]);
    $withName->calculateAge();
    $withName->save();

    $otherName = SeniorCitizen::factory()->create([
        'barangay' => $barangay,
        'firstname' => 'Jane',
        'lastname' => 'Smith',
        'date_of_birth' => '1955-03-10',
    ]);
    $otherName->calculateAge();
    $otherName->save();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.barangay', [
        'barangay' => $barangay,
        'search' => 'John'
    ]));

    $response->assertStatus(200);
    $response->assertSee('John');
    $response->assertDontSee('Jane');
});

it('barangay modal filters by sex', function () {
    now()->setTestNow('2026-02-26');
    $barangay = \App\Constants\Barangay::list()[0];

    $male = SeniorCitizen::factory()->create([
        'barangay' => $barangay,
        'firstname' => 'John',
        'sex' => 'Male',
        'date_of_birth' => '1950-05-15',
    ]);
    $male->calculateAge();
    $male->save();

    $female = SeniorCitizen::factory()->create([
        'barangay' => $barangay,
        'firstname' => 'Jane',
        'sex' => 'Female',
        'date_of_birth' => '1955-03-10',
    ]);
    $female->calculateAge();
    $female->save();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.barangay', [
        'barangay' => $barangay,
        'sex' => 'Male'
    ]));

    $response->assertStatus(200);
    $response->assertSee('John');
    // Jane (female) should not appear in the table results
    $response->assertDontSee('Jane');
});

it('barangay modal filters by age range', function () {
    now()->setTestNow('2026-02-26');
    $barangay = \App\Constants\Barangay::list()[0];

    $younger = SeniorCitizen::factory()->create([
        'barangay' => $barangay,
        'date_of_birth' => '1965-05-15', // age 60
    ]);
    $younger->calculateAge();
    $younger->save();

    $older = SeniorCitizen::factory()->create([
        'barangay' => $barangay,
        'date_of_birth' => '1940-03-10', // age 85
    ]);
    $older->calculateAge();
    $older->save();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.barangay', [
        'barangay' => $barangay,
        'age_range' => '80+'
    ]));

    $response->assertStatus(200);
    $response->assertSee((string) $older->age);
});

it('barangay modal filters by exact age', function () {
    now()->setTestNow('2026-02-26');
    $barangay = \App\Constants\Barangay::list()[0];

    $sen = SeniorCitizen::factory()->create([
        'barangay' => $barangay,
        'date_of_birth' => '1950-05-15', // age 75
    ]);
    $sen->calculateAge();
    $sen->save();

    $other = SeniorCitizen::factory()->create([
        'barangay' => $barangay,
        'date_of_birth' => '1940-03-10', // age 85
    ]);
    $other->calculateAge();
    $other->save();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.barangay', [
        'barangay' => $barangay,
        'age_range' => (string)$sen->age,
    ]));

    $response->assertStatus(200);
    $response->assertSee((string) $sen->age);
    $response->assertDontSee((string) $other->age);
});

it('health modal filters by search name', function () {
    now()->setTestNow('2026-02-26');

    $withName = SeniorCitizen::factory()->create([
        'with_disability' => true,
        'firstname' => 'John',
        'lastname' => 'Doe',
        'date_of_birth' => '1950-05-15',
    ]);
    $withName->calculateAge();
    $withName->save();

    $otherName = SeniorCitizen::factory()->create([
        'with_disability' => true,
        'firstname' => 'Jane',
        'lastname' => 'Smith',
        'date_of_birth' => '1955-03-10',
    ]);
    $otherName->calculateAge();
    $otherName->save();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.health', [
        'condition' => 'with_disability',
        'search' => 'John'
    ]));

    $response->assertStatus(200);
    $response->assertSee('John');
    $response->assertDontSee('Jane');
});

it('health modal filters by age range', function () {
    now()->setTestNow('2026-02-26');

    $younger = SeniorCitizen::factory()->create([
        'with_disability' => true,
        'date_of_birth' => '1965-05-15', // age 60
    ]);
    $younger->calculateAge();
    $younger->save();

    $older = SeniorCitizen::factory()->create([
        'with_disability' => true,
        'date_of_birth' => '1940-03-10', // age 85
    ]);
    $older->calculateAge();
    $older->save();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.health', [
        'condition' => 'with_disability',
        'age_range' => '80+'
    ]));

    $response->assertStatus(200);
    $response->assertSee((string) $older->age);
});

it('health modal filters by exact age', function () {
    now()->setTestNow('2026-02-26');

    $sen = SeniorCitizen::factory()->create([
        'with_disability' => true,
        'date_of_birth' => '1950-05-15', // age 75
    ]);
    $sen->calculateAge();
    $sen->save();

    $other = SeniorCitizen::factory()->create([
        'with_disability' => true,
        'date_of_birth' => '1940-03-10', // age 85
    ]);
    $other->calculateAge();
    $other->save();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.health', [
        'condition' => 'with_disability',
        'age_range' => (string)$sen->age,
    ]));

    $response->assertStatus(200);
    $response->assertSee((string) $sen->age);
    $response->assertDontSee((string) $other->age);
});

it('deceased report filters by barangay', function () {
    now()->setTestNow('2026-02-26');
    $barangay = \App\Constants\Barangay::list()[0];
    $otherBarangay = \App\Constants\Barangay::list()[1];

    $inBarangay = SeniorCitizen::factory()->create([
        'deleted_at' => now(),
        'barangay' => $barangay,
        'firstname' => 'John',
        'lastname' => 'Doe',
        'date_of_birth' => '1950-05-15',
    ]);
    $inBarangay->calculateAge();
    $inBarangay->save();

    $otherBarangayRecord = SeniorCitizen::factory()->create([
        'deleted_at' => now(),
        'barangay' => $otherBarangay,
        'firstname' => 'Jane',
        'lastname' => 'Smith',
        'date_of_birth' => '1955-03-10',
    ]);
    $otherBarangayRecord->calculateAge();
    $otherBarangayRecord->save();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.deceased', [
        'barangay' => $barangay
    ]));

    $response->assertStatus(200);
    $response->assertSee('John');
    $response->assertDontSee('Jane');
});
