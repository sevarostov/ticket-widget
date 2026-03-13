<?php

namespace App\Http\Resources;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
	schema: "TicketStatisticsResource",
	title: "Ticket Statistics Resource",
	description: "Статистика по тикетам за указанный период",
	properties: [
		new OA\Property(
			property: "period",
			description: "Период для статистики",
			type: "string",
			example: "week",
			enum: ["day", "week", "month"]
		),
		new OA\Property(
			property: "date",
			description: "Диапазон дат для выбранного периода",
			type: "string",
			example: "2026-03-09 00:00:00 - 2026-03-15 23:59:59"
		),
		new OA\Property(
			property: "total",
			description: "Общее количество тикетов за период",
			type: "integer",
			example: 8143
		),
		new OA\Property(
			property: "statistics",
			description: "Детальная статистика по статусам и ответам",
			properties: [
				new OA\Property(
					property: "status",
					description: "Распределение тикетов по статусам",
					properties: [
						new OA\Property(
							property: "new",
							description: "Количество тикетов со статусом 'new'",
							type: "integer",
							example: 2721
						),
						new OA\Property(
							property: "in_progress",
							description: "Количество тикетов со статусом 'in_progress'",
							type: "integer",
							example: 2715
						),
						new OA\Property(
							property: "processed",
							description: "Количество тикетов со статусом 'processed'",
							type: "integer",
							example: 2707
						)
					],
					type: "object"
				),
				new OA\Property(
					property: "date_responded_at",
					description: "Распределение по наличию ответа",
					properties: [
						new OA\Property(
							property: "yes",
							description: "Количество тикетов с ответом",
							type: "integer",
							example: 2424
						),
						new OA\Property(
							property: "no",
							description: "Количество тикетов без ответа",
							type: "integer",
							example: 5719
						)
					],
					type: "object"
				)
			],
			type: "object"
		),
		new OA\Property(
			property: "info",
			description: "Информация о доступных периодах",
			type: "string",
			example: "Available periods are 'day', 'week', 'month'"
		)
	]
)]
class TicketStatisticsResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$period = in_array($period = $request->input('period', 'day'), ['day', 'week', 'month'])
			? $period
			: 'day';

		$count = Ticket::forPeriod($period)->count();

		return [
			'period' => $period,
			'date' => Ticket::getDatePeriod($period),
			'total' => $count,
			'statistics' => Ticket::calculateStatistics(Ticket::forPeriod($period)->get()),
			'info' => "Available periods are 'day', 'week', 'month'",
		];
	}
}
