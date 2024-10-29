<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->numberBetween(1000, 100000),
            'interest_rate' => $this->faker->randomFloat(2, 1, 10), // e.g., 5.5%
            'duration' => $this->faker->numberBetween(6, 60), // duration in months
            'lender_id' => User::factory(),
            'borrower_id' => User::factory(),
        ];
    }
}
