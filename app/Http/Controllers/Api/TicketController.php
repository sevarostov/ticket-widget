<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketStatisticsResource;
use App\Models\Ticket;
use App\Services\TicketService;
use Exception;
use Illuminate\Http\Request;

class TicketController extends Controller
{
	public function __construct(public readonly TicketService $ticketService) {}

	/**
	 * @param StoreTicketRequest $request
	 *
	 * @return TicketResource|Exception
	 */
	public function store(StoreTicketRequest $request): TicketResource|Exception
	{
		if (($response = $this->ticketService->createTicket($request->validated()))
			instanceof (Ticket::class)) {
			return new TicketResource($response);
		}
		return $response;
	}

	/**
	 * @param Request $request
	 *
	 * @return TicketStatisticsResource
	 */
	public function statistics(Request $request): TicketStatisticsResource
	{
		return new TicketStatisticsResource($request);
	}
}
