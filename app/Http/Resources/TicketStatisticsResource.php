<?php

namespace App\Http\Resources;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketStatisticsResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$period = $request->input('period', 'day');

		$count = Ticket::forPeriod($period)->count();

		return [
			'period' => $period,
			'date' => Ticket::getDatePeriod($period),
			'total' => $count,
			'statistics' => Ticket::calculateStatistics(Ticket::forPeriod($period)->get()),
		];
	}
}
