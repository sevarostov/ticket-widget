<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;
#[OA\Schema(
	schema: "CustomerResource",
	title: "Customer Resource",
	description: "Представление данных клиента в API‑ответе",
	properties: [
		new OA\Property(
			property: "id",
			description: "Уникальный идентификатор клиента",
			type: "integer",
			format: "int64",
			example: 837
		),
		new OA\Property(
			property: "name",
			description: "Имя клиента",
			type: "string",
			example: "seva"
		),
		new OA\Property(
			property: "phone",
			description: "Номер телефона клиента в формате E.164",
			type: "string",
			example: "+79981378544"
		),
		new OA\Property(
			property: "email",
			description: "Email клиента",
			type: "string",
			format: "email",
			example: "enter@mail.com"
		),
		new OA\Property(
			property: "created_at",
			description: "Дата и время создания записи о клиенте",
			type: "string",
			format: "date-time",
			example: "2026-03-13 11:30:54"
		),
		new OA\Property(
			property: "updated_at",
			description: "Дата и время последнего обновления записи о клиенте",
			type: "string",
			format: "date-time",
			example: "2026-03-13 11:30:54"
		)
	]
)]
class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
		/** @var Customer $ticket */
		$customer = $this->resource;

		return [
			'id' => $customer->id,
			'name' => $customer->name,
			'phone' => $customer->phone,
			'email' => $customer->email,
			'created_at' => $customer->created_at->toDateTimeString(),
			'updated_at' => $customer->updated_at->toDateTimeString(),
		];
    }
}
