<?php

namespace App\Models;

use EloquentTypeHinting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @method void prepareToAttachMedia(Media $media, FileAdder $fileAdder)
 * @mixin EloquentTypeHinting
 *
 * Локальные скоупы
 * @method static Builder|static scopeForPeriod() Получение данных за  период
 */
class Ticket extends Model implements HasMedia
{
	use HasFactory;
	protected $fillable = [
		'customer_id', 'topic', 'text', 'status', 'date_responded_at'
	];

	public function customer() {
		return $this->belongsTo(Customer::class);
	}


	/**
	 * @param Builder $query
	 * @param string $period
	 *
	 * @return Builder
	 */
	public function scopeForPeriod(Builder $query, string $period): Builder {

		$now = now();
		return match ($period) {
			'week' => $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]),
			'month' => $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year),
			default => $query->whereDate('created_at', $now->toDateString()),
		};
	}

	public function media(): MorphMany {
		// TODO: Implement media() method.
	}

	public function addMedia(string|UploadedFile $file): FileAdder {
		// TODO: Implement addMedia() method.
	}

	public function copyMedia(string|UploadedFile $file): FileAdder {
		// TODO: Implement copyMedia() method.
	}

	public function hasMedia(string $collectionName = ''): bool {
		// TODO: Implement hasMedia() method.
	}

	public function getMedia(string $collectionName = 'default', callable|array $filters = []): Collection {
		// TODO: Implement getMedia() method.
	}

	public function clearMediaCollection(string $collectionName = 'default'): HasMedia {
		// TODO: Implement clearMediaCollection() method.
	}

	public function clearMediaCollectionExcept(string $collectionName = 'default', array|Collection $excludedMedia = []): HasMedia {
		// TODO: Implement clearMediaCollectionExcept() method.
	}

	public function shouldDeletePreservingMedia(): bool {
		// TODO: Implement shouldDeletePreservingMedia() method.
	}

	public function loadMedia(string $collectionName) {
		// TODO: Implement loadMedia() method.
	}

	public function addMediaConversion(string $name): Conversion {
		// TODO: Implement addMediaConversion() method.
	}

	public function registerMediaConversions(?Media $media = null): void {
		// TODO: Implement registerMediaConversions() method.
	}

	public function registerMediaCollections(): void {
		// TODO: Implement registerMediaCollections() method.
	}

	public function registerAllMediaConversions(): void {
		// TODO: Implement registerAllMediaConversions() method.
	}

	public function getMediaCollection(string $collectionName = 'default'): ?MediaCollection {
		// TODO: Implement getMediaCollection() method.
	}

	public function getMediaModel(): string {
		// TODO: Implement getMediaModel() method.
	}

	public function __call($method, $parameters) {
		// TODO: Implement @method void prepareToAttachMedia(Media $media, FileAdder $fileAdder)
	}
}
