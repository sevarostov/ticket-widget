<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketStatisticsResource;
use App\Services\TicketService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
	public function __construct(public readonly TicketService $ticketService) {}

	/**
	 * @param StoreTicketRequest $request
	 *
	 * @return TicketResource|JsonResponse
	 */
	public function store(StoreTicketRequest $request): TicketResource|JsonResponse
	{
		try {
			return new TicketResource($this->ticketService->createTicket($request->validated()));
		} catch (Exception $e) {
			//@todo log $e->getMessage()
			return response()->json(['error' => 'Error had occured'], 500);
		}
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
