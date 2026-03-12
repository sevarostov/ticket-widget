<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Ticket;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class TicketService
{
	/**
	 * Получить список тикетов с пагинацией
	 */
	public function getTickets(int $perPage = 10): LengthAwarePaginator
	{
		return Ticket::with('customer')
			->orderBy('created_at', 'desc')
			->paginate($perPage)
			->withQueryString();
	}

	/**
	 * Получить тикет по ID
	 */
	public function getTicketById(int $id): ?Ticket
	{

		return Ticket::with('customer')->find($id);
	}

	/**
	 * @param Ticket $ticket
	 * @param string $status
	 *
	 * @return Ticket|null
	 */
	public function updateTicketStatus(Ticket $ticket, string $status): Ticket|null
	{
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

	/**
	 * @param array $data
	 *
	 * @return Ticket|null
	 */
	public function createTicket(array $data): ?Ticket
	{
		try {
			return DB::transaction(function () use ($data): Ticket {

				$email = Str::trim(Str::replace([' '], '', $data['email']));
				$phone = Str::trim(Str::replace([' '], '', $data['phone']));

				$existingTicket = Ticket::whereHas('customer', function ($query) use ($phone, $email) {
					$query->where('phone', $phone)
						->orWhere('email', $email);
				})->whereDate('created_at', now()->toDateString())->first();

				if ($existingTicket) {
					throw new Exception('Too many requests', 429);
				}

				$customer = Customer::where(['phone' => $phone])->first()
					?? Customer::where(['email' => $email])->first();

				if (!$customer) {
					$customer = Customer::create([
						'email' => $email,
						'phone' => $phone,
						'name' => $data['name'],
					]);
					$customer->save();
				}

				$ticket = Ticket::create([
					'customer_id' => $customer->id,
					'topic' => $data['topic'],
					'text' => $data['text']
				]);

				if (isset($data['file'])) {
					$ticket->addMedia($data['file'])->toMediaCollection('files');
				}

				return $ticket;

			}, 5);
		} catch (Throwable $exception) {
			//@todo log error
			return null;
		}
	}
}
