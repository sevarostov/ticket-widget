<?php

namespace Tests\Unit\Services;

use App\Models\Customer;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class TicketServiceTest extends TestCase
{
	protected TicketService $service;
	protected array $validTicketData;

	protected function setUp(): void
	{
		parent::setUp();
		$this->service = new TicketService();

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

	public function testUpdateTicketStatusReturnsUpdatedTicketForValidStatus(): void
	{
		$ticket = Ticket::first();
		$prevStatus = ($ticket->status);
		$newStatuses = array_filter(array_keys(Ticket::getStatuses()), fn($item) => $item != $ticket->status);
		$newStatus = array_shift($newStatuses);

		$result = $this->service->updateTicketStatus($ticket, $newStatus);
		$currentStatus = ($ticket->status);
		$this->assertInstanceOf(Ticket::class, $result);
		$this->assertEquals($newStatus, $result->status);

		$this->assertTrue($prevStatus != $currentStatus);
	}

	public function testUpdateTicketStatusReturnsNullForInvalidStatus(): void
	{
		$ticket = Ticket::first();
		$invalidStatus = 'invalid_status';

		$result = $this->service->updateTicketStatus($ticket, $invalidStatus);
		$this->assertNull($result);
	}

	public function testCreateTicketCreatesNewTicketWithNewCustomer(): void
	{
		DB::shouldReceive('transaction')->andReturnUsing(function ($callback) {
			return $callback();
		});

		$result = $this->service->createTicket($this->validTicketData);

		$this->assertInstanceOf(Ticket::class, $result);
		$this->assertNotNull($result->id);
		$this->assertNotNull($result->customer_id);

		$customer = $result->customer;
		$this->assertInstanceOf(Customer::class, $customer);
		$this->assertEquals($this->validTicketData['name'], $customer->name);
		$this->assertEquals($this->validTicketData['email'], $customer->email);

		$this->assertEquals($this->validTicketData['topic'], $result->topic);
		$this->assertEquals($this->validTicketData['text'], $result->text);
	}

}
