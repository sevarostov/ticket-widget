<?php

namespace App\Http\Controllers;

use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
	public function __construct(public readonly TicketService $ticketService) {}

	/**
	 * Показать список тикетов
	 */
	public function index(): View
	{
		return view('ticket.index', ['tickets' => $this->ticketService->getTickets(10)]);
	}

	/**
	 * Показать детали тикета
	 */
	public function show(int $id): View|RedirectResponse
	{
		if (!$ticket = $this->ticketService->getTicketById($id)) {
			return redirect()->back()->with('error', 'Элемент не найден');
		}

		return view('ticket.show', ['ticket' => $ticket]);
	}

	/**
	 * Обновить статус тикета
	 */
	public function updateStatus(Request $request, int $id): RedirectResponse
	{
		$status = $request->input('status');

		if (!$ticket = $this->ticketService->getTicketById($id)) {
			return redirect()->back()->with('error', 'Элемент не найден');
		}

		if ($this->ticketService->updateTicketStatus($ticket, $status)) {
			return redirect()->back()->with('success', 'Статус тикета обновлён');
		}

		return redirect()->back()->with('error', 'Не удалось обновить статус');
	}
}
