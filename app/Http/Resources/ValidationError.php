<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
	schema: "ValidationError",
	title: "Validation Error",
	description: "Ошибка валидации данных — содержит общее сообщение и детали по каждому полю"
)]
class ValidationError extends JsonResource
{
	#[OA\Property(
		property: "message",
		description: "Общее сообщение об ошибке валидации. Может содержать краткое описание проблемы и указание на количество дополнительных ошибок",
		type: "string",
		example: "Имя обязательно для заполнения (and 4 more errors)"
	)]
	public string $message;

	#[OA\Property(
		property: "errors",
		description: "Объект с детальной информацией об ошибках валидации по каждому полю. Ключи — названия полей, значения — массивы сообщений об ошибках",
		type: "object",
		additionalProperties: new OA\AdditionalProperties(
			description: "Массив сообщений об ошибках для конкретного поля",
			type: "array",
			items: new OA\Items()
		)
	)]
	public array $errors;
}
