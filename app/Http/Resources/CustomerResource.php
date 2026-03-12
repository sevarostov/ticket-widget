<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
