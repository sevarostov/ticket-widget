<?php

namespace Tests\Unit\Models;

use App\Models\Ticket;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class TicketTest extends TestCase
{
	public function testMediaIsLinkedToTicket()
	{
		$uploadedFile = new UploadedFile(
			dirname(__DIR__, 3) . '/storage/app/public/ТЗ.png',
			'ТЗ.png',
			'image/png',
			null,
			true,
		);

		$media = Ticket::first()->addMedia($uploadedFile)->toMediaCollection('attachments');
		$this->assertInstanceOf(Media::class, $media);
	}
}
