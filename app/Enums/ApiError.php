<?php

namespace App\Enums;

/**
 * Ошибки, которые могут возникнуть при работе с API
 */
enum ApiError: int {
	/**
	 * Отсутствие ошибки
	 */
	case NoError = 200;

	/**
	 * Неизвестная ошибка
	 */
	case RuntimeError = 400;

	/**
	 * Ошибка валидации данных
	 */
	case ValidationError = 422;

	/**
	 * Одна заявка за сутки уже подана
	 */
	case TodayTicketAlreadyExists = 429;

	/**
	 * Получить описание ошибки
	 *
	 * @return string
	 */
	public function getDescription(): string {
		return match ($this) {
			self::NoError => "",
			self::RuntimeError => "Произошла ошибка. Пожалуйста, попробуйте позже или свяжитесь с Службой поддержки",
			self::TodayTicketAlreadyExists => "Возможно подать одну заявку в сутки",
		};
	}
}
