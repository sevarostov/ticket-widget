<?php

namespace App\Http\Resources;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
	schema: "TicketResource",
	title: "Ticket Resource",
	description: "Представление тикета в API‑ответе",
	properties: [
		new OA\Property(
			property: "id",
			description: "Уникальный идентификатор тикета",
			type: "integer",
			format: "int64",
			example: 8488
		),
		new OA\Property(
			property: "customer",
			ref: "#/components/schemas/CustomerResource",
			description: "Данные клиента, создавшего тикет"
		),
		new OA\Property(
			property: "topic",
			description: "Тема тикета",
			type: "string",
			example: "Тема обращения"
		),
		new OA\Property(
			property: "text",
			description: "Текст сообщения клиента",
			type: "string",
			example: "Текст обращения"
		),
		new OA\Property(
			property: "status",
			description: "Статус обработки тикета",
			type: "string",
			example: null,
			nullable: true,
			enum: ["new", "in_progress", "processed"]
		),
		new OA\Property(
			property: "date_responded_at",
			description: "Дата и время ответа менеджера",
			type: "string",
			format: "date-time",
			example: null,
			nullable: true
		),
		new OA\Property(
			property: "created_at",
			description: "Дата и время создания тикета",
			type: "string",
			format: "date-time",
			example: "2026-03-13 11:30:54"
		),
		new OA\Property(
			property: "updated_at",
			description: "Дата и время последнего обновления тикета",
			type: "string",
			format: "date-time",
			example: "2026-03-13 11:30:54"
		)
	]
)]
class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
		/** @var Ticket $ticket */
		$ticket = $this->resource;

		return [
			'id' => $ticket->id,
			'customer' => new CustomerResource($ticket->customer),
			'topic' => $ticket->topic,
			'text' => $ticket->text,
			'status' => $ticket->status,
			'date_responded_at' => $ticket->date_responded_at?->toDateTimeString(),
			'created_at' => $ticket->created_at->toDateTimeString(),
			'updated_at' => $ticket->updated_at->toDateTimeString(),
		];
    }
}
