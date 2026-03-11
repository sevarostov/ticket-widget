<?php

namespace App\Enums;

/**
 * Enum для ролей пользователей в системе.
 */
enum Role: string
{
	case ADMIN = 'admin';
	case MANAGER = 'manager';

	/**
	 * Получить все значения enum в виде массива.
	 *
	 * @return array<string>
	 */
	public static function values(): array
	{
		return array_map(fn ($case) => $case->value, self::cases());
	}

	/**
	 * Получить все случаи enum в виде массива с метками.
	 *
	 * @return array<string, string>
	 */
	public static function labels(): array
	{
		return [
			self::ADMIN->value => 'Администратор',
			self::MANAGER->value => 'Менеджер',
		];
	}

	/**
	 * Проверить, существует ли значение в enum.
	 *
	 * @param string $value
	 * @return bool
	 */
	public static function hasValue(string $value): bool
	{
		return in_array($value, self::values());
	}
}
