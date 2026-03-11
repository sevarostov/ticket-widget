<?php

namespace App\Models;

use EloquentTypeHinting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin EloquentTypeHinting
 *
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
		return $this->hasMany(Ticket::class);
	}
}
