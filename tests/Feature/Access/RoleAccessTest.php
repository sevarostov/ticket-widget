<?php

namespace Feature\Access;

use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->seed(UserSeeder::class);

		$this->ticket = Ticket::first();
	}

	/**
	 * @param string $email
	 *
	 * @return User
	 */
	protected function loginUserByEmail(string $email): User
	{
		$user = User::where('email', $email)->first();
		$this->actingAs($user);
		return $user;
	}

	public function testAdminCanAccessAllTicketRoutes(): void
	{
		$this->loginUserByEmail('admin@example.com');

		$response = $this->get(route('ticket.index'));
		$response->assertStatus(200);

		if ($this->ticket) {
			$response = $this->get(route('ticket.show', ['id' => $this->ticket]));
			$response->assertStatus(200);

			$response = $this->post(route('ticket.updateStatus', ['id' => 1]), [
				'status' => 'processed'
			]);
			$response->assertStatus(302);
		}
	}

	public function testAdminHasAllRoles(): void
	{
		$admin = User::where('email', 'admin@example.com')->first();

		$this->assertTrue($admin->hasRole('admin'));
		$this->assertTrue($admin->hasRole('manager'));
		$this->assertTrue($admin->hasRole('hr'));
	}

	public function testManagerCanViewTicketsButNotUpdateStatus(): void
	{
		$this->loginUserByEmail('manager@example.com');

		$response = $this->get(route('ticket.index'));
		$response->assertStatus(200);

		if ($this->ticket) {
			$response = $this->get(route('ticket.show', ['id' => $this->ticket->id]));
			$response->assertStatus(200);

			$response = $this->post(route('ticket.updateStatus', ['id' => $this->ticket->id]), [
				'status' => 'processed'
			]);
			$response->assertRedirect();
			$response->assertSessionHas('error', 'Недостаточно прав для совершения действия');
		}

	}

	public function testHrCannotAccessTicketRoutes(): void
	{
		$this->loginUserByEmail('hr@example.com');

		$response = $this->get(route('ticket.index'));
		$response->assertRedirect();
		$response->assertSessionHas('error', 'Недостаточно прав для совершения действия');

		if ($this->ticket) {
			$response = $this->get(route('ticket.show', ['id' => $this->ticket->id]));
			$response->assertRedirect();
			$response->assertSessionHas('error', 'Недостаточно прав для совершения действия');

			$response = $this->post(route('ticket.updateStatus', ['id' => $this->ticket->id]), [
				'status' => 'processed'
			]);
			$response->assertRedirect();
			$response->assertSessionHas('error', 'Недостаточно прав для совершения действия');
		}

	}

	public function testHrCanAccessHome(): void
	{
		$this->loginUserByEmail('hr@example.com');

		$response = $this->get(route('home'));
		$response->assertStatus(200);
	}

}
