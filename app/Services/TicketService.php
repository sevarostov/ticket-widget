<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketService
{
	/**
	 * Получить список тикетов с пагинацией
	 */
	public function getTickets(int $perPage = 10): LengthAwarePaginator {
		return Ticket::with('customer')
			->orderBy('created_at', 'desc')
			->getQuery()
			->paginate($perPage)
			->withQueryString();
	}

	/**
	 * Получить тикет по ID
	 */
	public function getTicketById(int $id): ?Ticket {

		return Ticket::with('customer')->find($id);
	}

	/**
	 * @param Ticket $ticket
	 * @param string $status
	 *
	 * @return Ticket|null
	 */
	public function updateTicketStatus(Ticket $ticket, string $status): Ticket|null {
		$validStatuses = [
			Ticket::STATUS_NEW,
			Ticket::STATUS_IN_PROGRESS,
			Ticket::STATUS_PROCESSED
		];

		if (!in_array($status, $validStatuses)) {
			return null;
		}

		$ticket->status = $status;
		$ticket->save();

		return $ticket;
	}
}
