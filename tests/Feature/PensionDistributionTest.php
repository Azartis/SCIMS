<?php

use App\Models\PensionDistribution;
use App\Models\SeniorCitizen;
use Illuminate\Support\Carbon;

it('can record and claim a distribution with an authorized representative', function () {
    // create a senior with no death date so eligibility check passes
    $senior = SeniorCitizen::factory()->create(['social_pension' => true, 'date_of_death' => null]);

    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('pension-distributions.store'), [
        'senior_citizen_id' => $senior->id,
        'disbursement_date' => now()->toDateString(),
        'amount' => 1000,
    ]);

    // after creating via modal, return to SPISC listing
    $response->assertRedirect(route('spisc.index'));

    $dist = PensionDistribution::first();
    expect($dist)->not->toBeNull();
    expect($dist->status)->toBe('unclaimed');

    $claimResponse = $this->post(route('pension-distributions.claim', $dist), [
        'authorized_rep_name' => 'Juan dela Cruz',
        'authorized_rep_relationship' => 'Son',
        'authorized_rep_contact' => '09171234567',
    ]);

    $claimResponse->assertRedirect();
    $dist->refresh();
    expect($dist->status)->toBe('claimed');
    expect($dist->authorized_rep_name)->toBe('Juan dela Cruz');
    expect($dist->claimed_at)->not->toBeNull();
});

it('applies deceased eligibility rule for quarter', function () {
    $now = Carbon::now();
    $q = (int) ceil($now->month / 3);
    $quarterStart = Carbon::create($now->year, ($q - 1) * 3 + 1, 1)->startOfDay();

    // senior died before quarter start -> not eligible
    $senA = SeniorCitizen::factory()->create(['social_pension' => true, 'date_of_death' => $quarterStart->copy()->subDay()->toDateString()]);
    $distA = PensionDistribution::create([
        'senior_citizen_id' => $senA->id,
        'disbursement_date' => $quarterStart->copy()->addDays(10)->toDateString(),
        'amount' => 1000,
        'status' => 'unclaimed',
    ]);
    expect($distA->isSeniorEligible())->toBe(false);

    // senior died on or after quarter start -> eligible
    $senB = SeniorCitizen::factory()->create(['social_pension' => true, 'date_of_death' => $quarterStart->copy()->toDateString()]);
    $distB = PensionDistribution::create([
        'senior_citizen_id' => $senB->id,
        'disbursement_date' => $quarterStart->copy()->addDays(5)->toDateString(),
        'amount' => 1000,
        'status' => 'unclaimed',
    ]);
    expect($distB->isSeniorEligible())->toBe(true);
});

it('prevents storing distributions for seniors who died before the quarter', function () {
    $now = Carbon::now();
    $q = (int) ceil($now->month / 3);
    $quarterStart = Carbon::create($now->year, ($q - 1) * 3 + 1, 1)->startOfDay();

    $sen = SeniorCitizen::factory()->create([
        'social_pension' => true,
        'date_of_death' => $quarterStart->copy()->subDays(5)->toDateString(),
    ]);

    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('pension-distributions.store'), [
        'senior_citizen_ids' => [$sen->id],
        'disbursement_date' => $quarterStart->copy()->addDays(10)->toDateString(),
        'amount' => 500,
    ]);

    $response->assertSessionHasErrors('senior_citizen_ids');
    expect(PensionDistribution::count())->toBe(0);
});

it('rejects batch create when at least one recipient is ineligible due to death', function () {
    $now = Carbon::now();
    $q = (int) ceil($now->month / 3);
    $quarterStart = Carbon::create($now->year, ($q - 1) * 3 + 1, 1)->startOfDay();

    $alive = SeniorCitizen::factory()->create(['social_pension' => true, 'date_of_death' => null]);
    $dead = SeniorCitizen::factory()->create(['social_pension' => true, 'date_of_death' => $quarterStart->copy()->subDays(3)->toDateString()]);

    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('pension-distributions.store'), [
        'senior_citizen_ids' => [$alive->id, $dead->id],
        'disbursement_date' => $quarterStart->copy()->addDays(5)->toDateString(),
        'amount' => 500,
    ]);

    $response->assertSessionHasErrors('senior_citizen_ids');
    expect(PensionDistribution::count())->toBe(0);
});
