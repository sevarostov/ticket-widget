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
use OpenApi\Attributes as OA;

#[OA\Info(
	version: "1.0.0",
	description: "API для работы с тикетами поддержки",
	title: "Ticket API",
	contact: new OA\Contact(
		email: "api@example.com"
	)
)]
class TicketController extends Controller
{
	public function __construct(public readonly TicketService $ticketService) {}

	#[OA\Post(
		path: "/api/tickets",
		description: "Создаёт новый тикет на основе переданных данных клиента",
		summary: "Создание нового тикета",
		tags: ["Tickets"],
		responses: [
			new OA\Response(
				response: 201,
				description: "Тикет успешно создан",
				content: new OA\JsonContent(
					properties: [
						new OA\Property(
							property: "data",
							ref: "#/components/schemas/TicketResource"
						)
					]
				)
			),
			new OA\Response(
				response: 422,
				description: "Ошибка валидации данных",
				content: new OA\JsonContent(
					ref: "#/components/schemas/ValidationError"
				)
			)
		]
	)]
	#[OA\RequestBody(
		required: true,
		content: [
			new OA\MediaType(
				mediaType: "multipart/form-data",
				schema: new OA\Schema(
					required: ["name", "email", "phone", "topic", "text"],
					properties: [
						new OA\Property(property: "name", description: "Имя клиента", type: "string", example: "seva"),
						new OA\Property(property: "email", description: "Email клиента", type: "string", format: "email", example: "enter@mail.com"),
						new OA\Property(property: "phone", description: "Телефон клиента", type: "string", example: "+79981378544"),
						new OA\Property(property: "topic", description: "Тема обращения", type: "string", example: "Тема обращения"),
						new OA\Property(property: "text", description: "Текст обращения", type: "string", example: "Текст обращения"),
						new OA\Property(property: "_token", description: "CSRF‑токен", type: "string")
					],
					type: "object"
				)
			)
		]
	)]
	public function store(StoreTicketRequest $request): TicketResource|Exception
	{
		if (($response = $this->ticketService->createTicket($request->validated()))
			instanceof (Ticket::class)) {
			return new TicketResource($response);
		}
		return $response;
	}

	#[OA\Get(
		path: "/api/tickets/statistics",
		description: "Возвращает статистику по тикетам за указанный период",
		summary: "Статистика по тикетам",
		tags: ["Statistics"],
		parameters: [
			new OA\Parameter(
				name: "period",
				description: "Период для статистики",
				in: "query",
				required: false,
				schema: new OA\Schema(
					type: "string",
					default: "day",
					enum: ["day", "week", "month"]
				)
			)
		],
		responses: [
			new OA\Response(
				response: 200,
				description: "Статистика успешно получена",
				content: new OA\JsonContent(
					properties: [
						new OA\Property(
							property: "data",
							ref: "#/components/schemas/TicketStatisticsResource"
						)
					]
				)
			)
		]
	)]
	public function statistics(Request $request): TicketStatisticsResource
	{
		return new TicketStatisticsResource($request);
	}
}
