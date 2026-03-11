<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
		return [
			'topic' => fake()->sentence(3),
			'text' => fake()->paragraph(3),
			'status' => fake()->randomElement(['new', 'in_progress', 'processed']),
			'date_responded_at' => fake()->optional(0.3)->dateTimeThisMonth(), // 30 % вероятность не‑null
		];
    }
}
