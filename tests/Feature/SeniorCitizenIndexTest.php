<?php

use App\Models\SeniorCitizen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows sex filter with only male and female and displays social pension column', function () {
    $user = User::factory()->create();

    // create two seniors to display
    $male = SeniorCitizen::factory()->create(['sex' => 'Male', 'social_pension' => true]);
    $female = SeniorCitizen::factory()->create(['sex' => 'Female', 'social_pension' => false]);

    $response = $this->actingAs($user)->get(route('senior-citizens.index'));
    $response->assertStatus(200);

    // navigation links present
    $response->assertSee('Senior Citizens');
    $response->assertSee('Archive');

    // summary cards have been removed to avoid redundancy
    $response->assertDontSee('Total Active');

    // check select options
    $response->assertSee('<option value="Male"', false);
    $response->assertSee('<option value="Female"', false);
    $response->assertDontSee('<option value="Other"', false);

    // table headers should include column for social pension
    $response->assertSee('SocPen');

    // each row for pensioner should display the SocPen badge
    $response->assertSeeInOrder([
        $male->osca_id,
        'SocPen',
    ], false);
    // non-pensioner should still render in table (but we don't expect the badge)
    $response->assertSee($female->osca_id);
});

it('formats names lastname, firstname and middle name', function () {
    $user = User::factory()->create();

    // create record with explicit name pieces
    $senior = SeniorCitizen::factory()->create([
        'lastname' => 'Calupas',
        'firstname' => 'Ace',
        'middlename' => 'Ziegfred',
    ]);
    $senior->calculateAge();
    $senior->save();

    $response = $this->actingAs($user)->get(route('senior-citizens.index'));
    $response->assertStatus(200);
    $response->assertSee('Calupas, Ace Ziegfred');
});

it('displays only numeric age without yrs or mos', function () {
    $user = User::factory()->create();

    now()->setTestNow('2026-02-25');
    $senior = SeniorCitizen::factory()->create(['date_of_birth' => '1950-05-15']);
    // calculateAge will store the computed age (for backwards compatibility)
    $senior->calculateAge();
    $senior->save();

    // sanity check that the accessor now returns a positive integer
    expect($senior->age)->toBeGreaterThan(0);
    $expectedAge = $senior->age; // 75 in this scenario

    $response = $this->actingAs($user)->get(route('senior-citizens.index'));
    $response->assertStatus(200);
    $response->assertSee((string) $expectedAge);
    $response->assertDontSee('yrs');
});

it('can filter by social pension flag on index', function () {
    $user = User::factory()->create();

    $pension = SeniorCitizen::factory()->create(['social_pension' => true]);
    $nopension = SeniorCitizen::factory()->create(['social_pension' => false]);

    $response = $this->actingAs($user)->get(route('senior-citizens.index', ['social_pension' => '1']));
    $response->assertStatus(200);
    $response->assertSee($pension->osca_id);
    $response->assertDontSee($nopension->osca_id);
});

it('can filter by age_range on index', function () {
    $user = User::factory()->create();

    // set dates such that ages fall into different buckets
    now()->setTestNow('2026-01-01');
    $young = SeniorCitizen::factory()->create(['date_of_birth' => '1968-01-01']); // 58 -> not included
    $mid = SeniorCitizen::factory()->create(['date_of_birth' => '1955-01-01']); // 71 -> 70-79
    $old = SeniorCitizen::factory()->create(['date_of_birth' => '1935-01-01']); // 91 -> 80+

    $mid->calculateAge(); $mid->save();
    $old->calculateAge(); $old->save();

    $response = $this->actingAs($user)->get(route('senior-citizens.index', ['age_range' => '70-79']));
    $response->assertSee($mid->osca_id);
    $response->assertDontSee($old->osca_id);
    $response->assertDontSee($young->osca_id);
});

it('treats numeric search as exact age when no age filter is selected', function () {
    $user = User::factory()->create();

    now()->setTestNow('2026-01-01');
    $exact = SeniorCitizen::factory()->create(['date_of_birth' => '1955-01-01']); // 71
    $other = SeniorCitizen::factory()->create(['date_of_birth' => '1951-01-01']); // 75

    $exact->calculateAge(); $exact->save();
    $other->calculateAge(); $other->save();

    $response = $this->actingAs($user)->get(route('senior-citizens.index', ['search' => '71']));
    $response->assertStatus(200);
    $response->assertSee($exact->osca_id);
    $response->assertDontSee($other->osca_id);
});

it('shows age value on the show page', function () {
    $user = User::factory()->create();

    now()->setTestNow('2026-02-25');
    // create a senior with predictable name so we can assert formatting
    $senior = SeniorCitizen::factory()->create([
        'date_of_birth' => '1950-05-15',
        'lastname' => 'Calupas',
        'firstname' => 'Ace',
        'middlename' => 'Ziegfred',
    ]);
    $senior->calculateAge();
    $senior->save();

    $response = $this->actingAs($user)->get(route('senior-citizens.show', $senior));
    $response->assertStatus(200);
    // formatted name should appear in header
    $response->assertSee('Calupas, Ace Ziegfred');
    // should display correct non-zero age value
    $response->assertSee((string) $senior->age);
    expect($senior->age)->toBeGreaterThan(0);
});

it('archive list shows numeric age without yrs or mos', function () {
    $user = User::factory()->create();

    now()->setTestNow('2026-02-25');
    $senior = SeniorCitizen::factory()->create(['date_of_birth' => '1950-05-15']);
    $senior->calculateAge();
    $senior->save();
    $senior->delete();

    $response = $this->actingAs($user)->get(route('senior-citizens.archive'));
    $response->assertStatus(200);
    $response->assertSee((string) $senior->age);
    $response->assertDontSee('yrs');
    expect($senior->age)->toBeGreaterThan(0);
});

it('saves death info when archiving a senior citizen', function () {
    $user = User::factory()->create();

    now()->setTestNow('2026-02-25');
    $senior = SeniorCitizen::factory()->create([
        'date_of_birth' => '1950-05-15',
    ]);
    $senior->calculateAge();
    $senior->save();

    $response = $this->actingAs($user)->delete(route('senior-citizens.destroy', $senior), [
        'date_of_death' => '2026-02-24',
        'cause_of_death' => 'Natural causes',
        'death_certificate_number' => 'DC123456',
    ]);
    $response->assertRedirect(route('senior-citizens.index'));

    $senior->refresh();
    expect($senior->trashed())->toBeTrue();
    expect($senior->date_of_death?->format('Y-m-d'))->toBe('2026-02-24');
    expect($senior->cause_of_death)->toBe('Natural causes');
    expect($senior->death_certificate_number)->toBe('DC123456');

    // archived list should display the death details (formatted)
    $response = $this->actingAs($user)->get(route('senior-citizens.archive'));
    $response->assertSee('Feb 24, 2026');
    $response->assertSee('Natural causes');
    $response->assertSee('DC123456');
});