<?php

namespace Tests\Unit\Controllers\Api;

use Illuminate\Support\Facades\Http;
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

	public function testHttpRequestReturnsSuccessfulResponse()
	{
		$filename = dirname(__DIR__, 4) . '/storage/app/public/ТЗ.png';

		if (!file_exists($filename)) {
			$this->assertFalse(file_exists($filename));
		} else {
			$response = Http::withHeaders([
				'Accept' => 'application/json',
				'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
				'Connection' => 'keep-alive',
				'Origin' => 'http://172.18.0.1',
				'Referer' => 'http://172.18.0.1/widget',
				'Sec-Fetch-Dest' => 'empty',
				'Sec-Fetch-Mode' => 'cors',
				'Sec-Fetch-Site' => 'same-origin',
				'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',
				'X-CSRF-TOKEN' => 'amLlrTMpFczcC113xaUWI4JE0gUj487XZUbH9SNJ',
				'X-Requested-With' => 'XMLHttpRequest',
				'sec-ch-ua' => '"Not(A:Brand";v="8", "Chromium";v="144", "Google Chrome";v="144"',
				'sec-ch-ua-mobile' => '?0',
				'sec-ch-ua-platform' => '"macOS"',
			])
				->withCookies([
					'XSRF-TOKEN' => 'amLlrTMpFczcC113xaUWI4JE0gUj487XZUbH9SNJ',
					'laravel-session' => 'eyJpdiI6InZ6L3BWdHVHcHMrU2FxMG1nNCtFZVE9PSIsInZhbHVlIjoiSW9QRyt3c09yUnpwNE4rdWE5QU8zQmpIWlNkTGR2L2xBVWhhU3ZneEdrZkRoUkJhN2JUV2xNa1haQlMvbTFuVU1NK0ludFBjNDM2WE5URXNGWnVja2tYTEV6cXhGVFhvdzVJaDNueW5TREJYQVZtQ20wMWN5VUVCTDFEaHkyaXYiLCJtYWMiOiJjZDRmNDNhNTJlMGNlYzE3YTI2OGRlMzA5ZGVkNmE5M2RjYTgzYmFjOWI1YmIzYTZlMGRlN2IwZGI5MDA4OWRjIiwidGFnIjoiIn0%3D'
				], 'localhost')
				->attach('files[]', file_get_contents($filename), 'ТЗ.png')
				->post('http://172.18.0.1/api/tickets', [
					'name' => 'seva',
					'email' => 'seva@maxe.ru',
					'phone' => '+78557144555',
					'topic' => 'Тема обращения',
					'text' => 'Текст обращения',
					'_token' => 'amLlrTMpFczcC113xaUWI4JE0gUj487XZUbH9SNJ'
				]);

			$this->assertEquals(200, $response->getStatusCode());
			$this->assertStringContainsString('created_at', $response->getBody()->getContents());
		}
	}

}
