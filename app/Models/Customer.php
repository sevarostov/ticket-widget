<?php

namespace App\Models;

use Carbon\Carbon;
use EloquentTypeHinting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin EloquentTypeHinting
 *
 * @property int $id Уникальный идентификатор
 * @property string $name Имя
 * @property string|null $phone Номер телефона
 * @property string|null $email Адрес электронной почты
 * @property Carbon|null $created_at Дата создания записи
 * @property Carbon|null $updated_at Дата последнего обновления записи
 *
 * @property-read Collection|Ticket[] $tickets Коллекция тикетов
 */
class Customer extends Model
{
	use HasFactory;
	protected $fillable = ['name', 'phone', 'email'];

	/**
	 * @return HasMany
	 */
	public function tickets(): HasMany
	{
		return $this->hasMany(Ticket::class, 'customer_id');
	}
}
