<?php

use App\Models\SeniorCitizen;
use App\Models\PensionDistribution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows social pension recipients on spisc page', function () {
    $user = User::factory()->create();

    // create some senior citizens
    SeniorCitizen::factory()->create([
        'lastname' => 'Garcia',
        'firstname' => 'Maria',
        'middlename' => 'Lopez',
        'extension_name' => 'III',
        'date_of_birth' => '1950-05-01',
        'address' => '123 Sample St',
        'social_pension' => true,
    ]);

    SeniorCitizen::factory()->create([
        'lastname' => 'Reyes',
        'firstname' => 'Juan',
        'social_pension' => false,
    ]);

    $response = $this->actingAs($user)->get(route('spisc.index'));
    $response->assertStatus(200);

    // should see the record with social pension
    $response->assertSee('Garcia');
    $response->assertSee('Maria');
    $response->assertSee('III');
    $response->assertSee('123 Sample St');

    // should not see the non-pensioner
    $response->assertDontSee('Reyes');
    // should include global add distribution button
    $response->assertSee('Add Distribution');
});

it('can filter by search name', function () {
    $user = User::factory()->create();

    SeniorCitizen::factory()->create([
        'social_pension' => true,
        'lastname' => 'Doe',
        'firstname' => 'John',
    ]);

    $response = $this->actingAs($user)->get('/spisc?search=John');
    $response->assertStatus(200);
    $response->assertSeeText('Doe');
});

it('sorts results ascending or descending', function () {
    $user = User::factory()->create();

    // create two seniors with different last names
    SeniorCitizen::factory()->create(['social_pension' => true, 'lastname' => 'Alpha', 'firstname' => 'A']);
    SeniorCitizen::factory()->create(['social_pension' => true, 'lastname' => 'Omega', 'firstname' => 'O']);

    $asc = $this->actingAs($user)->get('/spisc?sort=asc');
    $desc = $this->actingAs($user)->get('/spisc?sort=desc');

    $asc->assertSeeInOrder(['Alpha', 'Omega']);
    $desc->assertSeeInOrder(['Omega', 'Alpha']);
});

it('can filter by barangay', function () {
    $user = User::factory()->create();

    SeniorCitizen::factory()->create([
        'social_pension' => true,
        'barangay' => 'Alegre',
    ]);

    $response = $this->actingAs($user)->get('/spisc?barangay=Alegre');
    $response->assertStatus(200);
});

it('can filter by claimed personal status', function () {
    $user = User::factory()->create();

    $senior = SeniorCitizen::factory()->create(['social_pension' => true]);
    PensionDistribution::factory()->create([
        'senior_citizen_id' => $senior->id,
        'status' => 'claimed',
        'authorized_rep_name' => null,
    ]);

    $response = $this->actingAs($user)->get('/spisc?status=claimed_personal');
    $response->assertStatus(200);
});

it('can filter by claimed representative status', function () {
    $user = User::factory()->create();

    $senior = SeniorCitizen::factory()->create(['social_pension' => true]);
    PensionDistribution::factory()->create([
        'senior_citizen_id' => $senior->id,
        'status' => 'claimed',
        'authorized_rep_name' => 'Jane Doe',
    ]);

    $response = $this->actingAs($user)->get('/spisc?status=claimed_representative');
    $response->assertStatus(200);
});

it('can filter by unclaimed status', function () {
    $user = User::factory()->create();

    $senior = SeniorCitizen::factory()->create(['social_pension' => true]);
    PensionDistribution::factory()->create([
        'senior_citizen_id' => $senior->id,
        'status' => 'unclaimed',
    ]);

    $response = $this->actingAs($user)->get('/spisc?status=unclaimed');
    $response->assertStatus(200);
});

it('can update status to claimed personal', function () {
    $user = User::factory()->create();

    $senior = SeniorCitizen::factory()->create(['social_pension' => true]);
    $distribution = PensionDistribution::factory()->create([
        'senior_citizen_id' => $senior->id,
        'status' => 'unclaimed',
    ]);

    $this->actingAs($user)->post("/spisc/{$senior->id}/update-status", [
        'distribution_id' => $distribution->id,
        'status' => 'claimed_personal',
    ]);

    expect($distribution->fresh()->status)->toBe('claimed');
    expect($distribution->fresh()->authorized_rep_name)->toBeNull();
});

it('can update status to claimed representative', function () {
    $user = User::factory()->create();

    $senior = SeniorCitizen::factory()->create(['social_pension' => true]);
    $distribution = PensionDistribution::factory()->create([
        'senior_citizen_id' => $senior->id,
        'status' => 'unclaimed',
    ]);

    $this->actingAs($user)->post("/spisc/{$senior->id}/update-status", [
        'distribution_id' => $distribution->id,
        'status' => 'claimed_representative',
        'authorized_rep_name' => 'Jane Doe',
        'authorized_rep_relationship' => 'Daughter',
        'authorized_rep_contact' => '09123456789',
    ]);

    expect($distribution->fresh()->status)->toBe('claimed');
    expect($distribution->fresh()->authorized_rep_name)->toBe('Jane Doe');
    expect($distribution->fresh()->authorized_rep_relationship)->toBe('Daughter');
    expect($distribution->fresh()->authorized_rep_contact)->toBe('09123456789');
});

it('can update status to unclaimed', function () {
    $user = User::factory()->create();

    $senior = SeniorCitizen::factory()->create(['social_pension' => true]);
    $distribution = PensionDistribution::factory()->create([
        'senior_citizen_id' => $senior->id,
        'status' => 'claimed',
        'authorized_rep_name' => 'Jane Doe',
        'claimed_at' => now(),
    ]);

    $this->actingAs($user)->post("/spisc/{$senior->id}/update-status", [
        'distribution_id' => $distribution->id,
        'status' => 'unclaimed',
    ]);

    expect($distribution->fresh()->status)->toBe('unclaimed');
    expect($distribution->fresh()->authorized_rep_name)->toBeNull();
    expect($distribution->fresh()->claimed_at)->toBeNull();
});