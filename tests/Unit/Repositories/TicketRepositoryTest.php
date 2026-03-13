<?php

namespace Tests\Unit\Repositories;

use App\Models\Customer;
use App\Models\Ticket;
use App\Repositories\TicketRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Tests\TestCase;

class TicketRepositoryTest extends TestCase
{
	protected TicketRepository $repository;
	protected array $validTicketData;

	protected function setUp(): void
	{
		parent::setUp();
		$this->repository = new TicketRepository();

		$this->validTicketData = [
			'name' => 'Ивана Иванова',
			'phone' => $phone = '+7 (933) 123-45-67',
			'email' => $email = 'ivanova@example.com',
			'topic' => 'Проблема с доступом',
			'text' => 'Не могу войти в аккаунт...',
		];

		$email = Str::trim(Str::replace([' '], '', $email));
		$phone = Str::trim(Str::replace([' '], '', $phone));

		Customer::factory()
			->count(1)
			->has(Ticket::factory()->count(10))
			->create();

		$customer = Customer::where(['phone' => $phone])->first()
			?? Customer::where(['email' => $email])->first();

		if ($customer) {
			Ticket::where(['customer_id' => $customer->id])->delete();
			Customer::where(['email' => $email])->delete();
		}
	}

	public function testGetTicketsReturnsPaginator(): void
	{
		$result = $this->repository->getListBy(new Request([]));
		$this->assertInstanceOf(LengthAwarePaginator::class, $result);
		$this->assertEquals(10, $result->perPage());

		foreach ($result->items() as $ticket) {
			$this->assertInstanceOf(Ticket::class, $ticket);
			$this->assertNotNull($ticket->customer);
		}
	}

	public function testGetTicketByIdReturnsTicketIfExists(): void
	{
		/** @var Customer $customer */
		$customer = Customer::factory()->create();
		/** @var Ticket $ticket */
		$ticket = Ticket::factory()->create(['customer_id' => $customer->id]);

		$result = $this->repository->getTicketById($ticket->id);
		$this->assertInstanceOf(Ticket::class, $result);
		$this->assertEquals($ticket->id, $result->id);
		$this->assertNotNull($result->customer);
	}

	public function testGetTicketByIdReturnsNullWhenNotFound(): void
	{
		$result = $this->repository->getTicketById(999999);
		$this->assertNull($result);
	}

}
