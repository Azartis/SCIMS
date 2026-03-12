<?php

namespace Database\Factories;

use App\Models\SeniorCitizen;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class SeniorCitizenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SeniorCitizen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $first = $this->faker->firstName;
        $last  = $this->faker->lastName;
        $dob   = $this->faker->dateTimeBetween('-95 years', '-60 years');
        $age   = Carbon::parse($dob)->diffInYears(now());
        $sex   = $this->faker->randomElement(['Male', 'Female', 'Other']);

        // prepare optional death data
        $deathDateObj = $this->faker->optional(0.1)->dateTimeBetween('-1 years', 'now');

        return [
            'firstname' => $first,
            'middlename' => $this->faker->optional()->firstName,
            'lastname' => $last,
            'extension_name' => $this->faker->optional()->randomElement(['Jr.', 'Sr.', 'III']),
            'fullname' => trim("{$first} {$this->faker->optional()->firstName} {$last}"),
            'date_of_birth' => $dob->format('Y-m-d'),
            'age' => $age,
            'place_of_birth' => $this->faker->city,
            'sex' => $sex,
            'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widowed', 'Separated', 'Divorced']),
            'citizenship' => 'Filipino',
            'religion' => $this->faker->randomElement(['Catholic', 'Protestant', 'None', 'Other']),
            'educational_attainment' => $this->faker->randomElement(['Elementary', 'High School', 'College', 'Vocational', 'None']),
            'address' => $this->faker->streetAddress,
            'barangay' => $this->faker->randomElement(\App\Constants\Barangay::list()),
            'contact_number' => $this->faker->optional()->phoneNumber,
            'osca_id' => strtoupper('OSC' . $this->faker->unique()->numberBetween(100000, 999999)), 
            'with_disability' => $this->faker->boolean(10),
            'bedridden' => $this->faker->boolean(5),
            'with_assistive_device' => $this->faker->boolean(5),
            'with_critical_illness' => $this->faker->boolean(5),
            'philhealth_member' => $this->faker->boolean(50),
            'philhealth_id' => $this->faker->optional()->bothify('PHIL####'),
            'is_pensioner' => $this->faker->boolean(30),
            'pension_type' => $this->faker->randomElement(['SSS', 'GSIS', 'Others']),
            'monthly_pension_amount' => $this->faker->randomFloat(2, 0, 5000),
            'other_income_source' => $this->faker->optional()->word,
            'total_monthly_income' => $this->faker->randomFloat(2, 0, 10000),
            'is_indigent' => $this->faker->boolean(20),
            'sss' => $this->faker->boolean(20),
            'gsis' => $this->faker->boolean(20),
            'pvao' => $this->faker->boolean(5),
            'family_pension' => $this->faker->boolean(5),
            'brgy_official' => $this->faker->boolean(2),
            'waitlist' => $this->faker->boolean(5),
            'social_pension' => $this->faker->boolean(5),
            'remarks' => $this->faker->optional()->sentence,
            // optionally include death info for archived fixtures
            'date_of_death' => $deathDateObj ? $deathDateObj->format('Y-m-d') : null,
            'cause_of_death' => $this->faker->optional(0.1)->sentence,
            'death_certificate_number' => $this->faker->optional(0.1)->bothify('DC#####'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (SeniorCitizen $sc) {
            $sc->calculateAge();
            $sc->save();
        });
    }
}
