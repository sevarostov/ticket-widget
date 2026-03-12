<?php

namespace App\Http\Resources;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
