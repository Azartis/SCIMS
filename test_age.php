<?php
require 'vendor/autoload.php';
use App\Models\SeniorCitizen;
use Illuminate\Foundation\Application;
use Carbon\Carbon;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

now()->setTestNow('2026-02-25');
$senior = SeniorCitizen::factory()->create(['date_of_birth' => '1950-05-15']);
$senior->calculateAge();
$senior->save();

echo 'Date of birth: ' . $senior->date_of_birth . PHP_EOL;
echo 'Age from accessor: ' . $senior->age . PHP_EOL;
echo 'Age from database: ' . $senior->getAttributes()['age'] . PHP_EOL;
echo 'Age type: ' . gettype($senior->age) . PHP_EOL;