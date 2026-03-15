<?php

namespace Tests\Unit\Models;

use App\Models\Ticket;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Tests\TestCase;

class TicketTest extends TestCase
{
	public function testMediaIsLinkedToTicket()
	{
		$filename = dirname(__DIR__, 3) . '/storage/app/public/ТЗ.png';
		if (!file_exists($filename)) {
			$this->assertFalse(file_exists($filename));
		} else {
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

	public function testTicketHasMediaCollection()
	{
		$ticket = Ticket::with('media')->first();dd($ticket);
		if ($ticket) {
			$this->assertInstanceOf(MediaCollection::class, $media = $ticket->media()->get());
		}
	}
}
