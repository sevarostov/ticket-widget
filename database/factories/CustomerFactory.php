<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
			'name' => fake()->name(),
			'phone' => fake()->optional(0.8)->regexify('^\+79\d{9}$'),
			'email' => fake()->optional(0.8)->safeEmail(),
		];
    }
}
