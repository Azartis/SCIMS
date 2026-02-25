<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // ensure test user exists (avoid duplicates on repeated seeding)
        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        // optionally wipe existing seniors so repeated seeding is idempotent
        // deleting triggers cascade on family_members
        \App\Models\SeniorCitizen::query()->delete();

        // generate a bunch of senior citizens for testing
        \App\Models\SeniorCitizen::factory()->count(500)->create();
    }
}
