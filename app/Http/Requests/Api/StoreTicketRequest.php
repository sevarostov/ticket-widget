<?php

namespace App\Http\Requests\Api;

use App\Helpers\RegexPatten;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'name' => 'required|string|max:255',
			'phone' => 'required|string|regex:' . RegexPatten::PHONE_REGEX,
			'email' => 'required|email',
			'topic' => 'required|string|max:255',
			'text' => 'required|string',
			'files' => 'nullable|array|max:10',
			'files.*' => 'file|max:10240',
		];
	}

	/**
	 * Custom validation messages.
	 *
	 * @return array<string, string>
	 */
	public function messages(): array
	{
		return [
			'name.required' => 'Имя обязательно для заполнения',
			'name.max' => 'Имя не должно превышать 255 символов',
			'phone.required' => 'Номер телефона обязателен',
			'phone.regex' => 'Номер телефона должен быть в международном формате (E.164)',
			'email.required' => 'Email обязателен для заполнения',
			'email.email' => 'Email должен быть корректным',
			'topic.required' => 'Тема обращения обязательна',
			'topic.max' => 'Тема не должна превышать 255 символов',
			'text.required' => 'Текст обращения обязателен',
			'files.array' => 'Файлы должны быть представлены в виде массива',
			'files.max' => 'Можно загрузить не более 10 файлов',
			'files.*.file' => 'Каждый файл должен быть валидным файлом',
			'files.*.max' => 'Размер каждого файла не должен превышать 10 МБ',
		];
	}
}
