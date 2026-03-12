<?php

use App\Models\SeniorCitizen;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('exports csv with numeric age and issuance date instead of contact number', function () {
    // fix the current time so age calculation is deterministic
    now()->setTestNow('2026-02-25 12:00:00');

    $senior = SeniorCitizen::factory()->create([
        'date_of_birth' => '1950-05-15',
        // created_at becomes the issuance date for export
        'created_at' => '2026-02-10 08:00:00',
    ]);

    // ensure the age column is computed and stored
    $senior->calculateAge();
    $senior->save();
    expect($senior->age)->toBeGreaterThan(0); // verify computation worked correctly

    // need an authenticated user to access protected export route
    $user = \App\Models\User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.export'));

    $response->assertStatus(200);
    // stream may include charset suffix depending on environment
    $response->assertHeaderContains('Content-Type', 'text/csv');

    // get the streamed content and split into lines
    $content = $response->streamedContent();
    $lines = array_filter(explode("\n", trim($content)));

    // header adjustments (last/first/middle split, DOB formatted)
    $header = str_getcsv($lines[0]);
    expect($header)->toContain('LAST NAME');
    expect($header)->toContain('FIRST NAME');
    expect($header)->toContain('MIDDLE NAME');
    expect($header)->toContain('DATE OF BIRTH (MM-DD-YYYY)');
    expect($header)->toContain('DATE OF ISSUANCE (MM-DD)');
    expect($header)->toContain('SC SOCIAL');
    expect($header)->not->toContain('Contact Number');

    // second row data positions changed
    $data = str_getcsv($lines[1]);
    // columns: 0 last,1 first,2 middle,3 address,4 dob,5 age,6 sex,7 issuance,8 osca_id
    expect($data[5])->toBe((string) $senior->age);
    expect($data[7])->toBe($senior->issuance_date);

    // verify DOB format
    expect($data[4])->toBe($senior->date_of_birth->format('m-d-Y'));
});

it('also applies the same column rules to barangay export', function () {
    now()->setTestNow('2026-02-25 12:00:00');
    $senior = SeniorCitizen::factory()->create([
        'date_of_birth' => '1950-05-15',
        'created_at' => '2026-02-10 08:00:00',
        'barangay' => \App\Constants\Barangay::list()[0],
    ]);
    $senior->calculateAge();
    $senior->save();

    $user = \App\Models\User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.barangay.export', ['barangay' => $senior->barangay]));
    $response->assertStatus(200);
    $response->assertHeaderContains('Content-Type', 'text/csv');
    $lines = array_filter(explode("\n", trim($response->streamedContent())));
    $header = str_getcsv($lines[0]);
    expect($header)->toContain('LAST NAME');
    expect($header)->toContain('FIRST NAME');
    expect($header)->toContain('MIDDLE NAME');
    expect($header)->toContain('DATE OF BIRTH (MM-DD-YYYY)');
    expect($header)->toContain('DATE OF ISSUANCE (MM-DD)');
    expect($header)->toContain('SC SOCIAL');
    expect($header)->not->toContain('Contact Number');

    // data row should have same number of columns as header
    $data = str_getcsv($lines[1]);
    expect(count($data))->toBe(count($header));
});

it('also applies the same column rules to health export', function () {
    now()->setTestNow('2026-02-25 12:00:00');
    $senior = SeniorCitizen::factory()->create([
        'date_of_birth' => '1950-05-15',
        'created_at' => '2026-02-10 08:00:00',
        'with_disability' => true,
    ]);
    $senior->calculateAge();
    $senior->save();

    $user = \App\Models\User::factory()->create();
    $response = $this->actingAs($user)->get(route('reports.health.export', ['condition' => 'with_disability']));
    $response->assertStatus(200);
    $response->assertHeaderContains('Content-Type', 'text/csv');
    $lines = array_filter(explode("\n", trim($response->streamedContent())));
    $header = str_getcsv($lines[0]);
    expect($header)->toContain('LAST NAME');
    expect($header)->toContain('FIRST NAME');
    expect($header)->toContain('MIDDLE NAME');
    expect($header)->toContain('DATE OF BIRTH (MM-DD-YYYY)');
    expect($header)->toContain('DATE OF ISSUANCE (MM-DD)');
    expect($header)->toContain('SC SOCIAL');
    expect($header)->not->toContain('Contact Number');

    $data = str_getcsv($lines[1]);
    expect(count($data))->toBe(count($header));
});