<?php

namespace Tests\Unit\Controllers\Api;

use Tests\TestCase;

class TicketControllerTest extends TestCase
{
	public function testStatisticsEndpointReturnsSuccessfulResponse(): void
	{
		$response = $this->get('/api/tickets/statistics');
		$response->assertStatus(200);
		$response->assertJsonStructure(['data' => [
			'period',
			'total',
			'statistics'
		]]);
	}

}
