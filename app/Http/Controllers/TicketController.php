<?php

namespace App\Http\Controllers;

use App\Repositories\TicketRepository;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
	public function __construct(public readonly TicketRepository $ticketRepository) {}

	/**
	 *
	 * Cписок тикетов
	 *
	 * @param Request $request
	 *
	 * @return View
	 */
	public function index(Request $request): View
	{
		return view('ticket.index', ['tickets' => $this->ticketRepository->getListBy($request, 10)]);
	}

	/**
	 * Показать детали тикета
	 */
	public function show(int $id): View|RedirectResponse
	{
		if (!$ticket = $this->ticketRepository->getTicketById($id)) {
			return redirect()->back()->with('error', 'Элемент не найден');
		}

		return view('ticket.show', ['ticket' => $ticket]);
	}

	/**
	 * Обновить статус тикета
	 */
	public function updateStatus(Request $request, int $id): RedirectResponse
	{
		if (!$ticket = $this->ticketRepository->getTicketById($id)) {
			return redirect()->back()->with('error', 'Элемент не найден');
		}

		$status = $request->input('status');
		if (new TicketService()->updateTicketStatus($ticket, $status)) {
			return redirect()->back()->with('success', 'Статус тикета обновлён');
		}

		return redirect()->back()->with('error', 'Не удалось обновить статус');
	}
}
