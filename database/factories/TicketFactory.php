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
		$now = now();
		$randomInt = random_int(0, 2);
		$periods = ['day', 'week', 'month'];
		$period = $periods[$randomInt];
		$createdAt = match ($period) {
			'week' => $now->startOfWeek()->addDay()->toDate(),
			'month' => $now->startOfMonth()->addDay()->toDate(),
			default => $now->startOfDay()->addHour()->toDate(),
		};
		
		return [
			'topic' => fake()->sentence(3),
			'text' => fake()->paragraph(3),
			'status' => fake()->randomElement(array_keys(Ticket::getStatuses())),
			'date_responded_at' => fake()->optional(0.3)->dateTimeThisMonth(),
			'created_at' => $createdAt
		];
	}
}
