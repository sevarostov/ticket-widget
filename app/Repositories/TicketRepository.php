<?php

namespace App\Repositories;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class TicketRepository {
	/**
	 * Получить список тикетов с пагинацией и фильтрацией
	 *
	 * @param Request $request
	 * @param int $perPage
	 *
	 * @return LengthAwarePaginator
	 */
	public function getListBy(Request $request, int $perPage = 10): LengthAwarePaginator
	{
		$query = Ticket::with('customer')
			->orderBy('created_at', 'desc');

		if ($request->has('date_from') && !empty($request->date_from)) {
			$query->whereDate('created_at', '>=', $request->date_from);
		}

		if ($request->has('date_to') && !empty($request->date_to)) {
			$query->whereDate('created_at', '<=', $request->date_to);
		}

		if ($request->has('status') && !empty($request->status)) {
			$query->where('status', $request->status);
		}

		if ($request->has('customer_email') && !empty($request->customer_email)) {
			$query->whereHas('customer', function ($q) use ($request) {
				$q->where('email', 'like', '%' . $request->customer_email . '%');
			});
		}

		if ($request->has('customer_phone') && !empty($request->customer_phone)) {
			$query->whereHas('customer', function ($q) use ($request) {
				$q->where('phone', 'like', '%' . $request->customer_phone . '%');
			});
		}

		return $query->paginate($perPage)->withQueryString();
	}

	/**
	 * Получить тикет по ID
	 */
	public function getTicketById(int $id): ?Ticket
	{
		return Ticket::with('customer')->find($id);
	}
}
