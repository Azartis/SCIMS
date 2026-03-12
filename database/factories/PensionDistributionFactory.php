<?php

namespace Database\Factories;

use App\Models\PensionDistribution;
use App\Models\SeniorCitizen;
use Illuminate\Database\Eloquent\Factories\Factory;

class PensionDistributionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PensionDistribution::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'senior_citizen_id' => SeniorCitizen::factory(),
            'disbursement_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'amount' => $this->faker->randomElement([500, 1000, 1500, 2000]),
            'status' => $this->faker->randomElement(['claimed', 'unclaimed']),
            'authorized_rep_name' => null,
            'authorized_rep_relationship' => null,
            'authorized_rep_contact' => null,
            'claimed_at' => null,
            'notes' => null,
        ];
    }

    /**
     * Indicate that the distribution was claimed.
     */
    public function claimed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'claimed',
                'claimed_at' => $this->faker->dateTime(),
            ];
        });
    }

    /**
     * Indicate that the distribution was claimed by a representative.
     */
    public function claimedByRepresentative()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'claimed',
                'authorized_rep_name' => $this->faker->name(),
                'authorized_rep_relationship' => $this->faker->randomElement(['Son', 'Daughter', 'Sibling', 'Spouse']),
                'authorized_rep_contact' => $this->faker->phoneNumber(),
                'claimed_at' => $this->faker->dateTime(),
            ];
        });
    }

    /**
     * Indicate that the distribution is unclaimed.
     */
    public function unclaimed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'unclaimed',
                'claimed_at' => null,
            ];
        });
    }
}
