<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Форма обратной связи</title>
	<link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="feedback-form">
	<h1 class="form-title">Форма обратной связи</h1>
	
	<div id="errorContainer" class="alert alert-danger d-none" role="alert"></div>
	<div id="successContainer" class="alert alert-success d-none" role="alert"></div>
	
	<form id="feedbackForm">
		<div class="form-group">
			<label for="name">Ваше имя *</label>
			<input type="text" id="name" name="name" placeholder="Введите ваше имя">
			<div class="error-message" id="nameError">Пожалуйста, введите имя</div>
		</div>
		
		<div class="form-group">
			<label for="email">Email *</label>
			<input type="email" id="email" name="email" placeholder="example@domain.com">
			<div class="error-message" id="emailError">Пожалуйста, введите корректный email</div>
		</div>
		
		<div class="form-group">
			<label for="phone">Телефон *</label>
			<input type="tel" id="phone" name="phone" placeholder="+79991234567">
			<div class="error-message" id="phoneError">Пожалуйста, введите корректный номер телефона</div>
		</div>
		
		<div class="form-group">
			<label for="topic">Тема обращения *</label>
			<input type="text" id="topic" name="topic" placeholder="Кратко опишите проблему">
			<div class="error-message" id="topicError">Пожалуйста, укажите тему обращения</div>
		</div>
		
		<div class="form-group">
			<label for="text">Описание проблемы *</label>
			<textarea id="text" name="text" placeholder="Подробно опишите вашу проблему..."></textarea>
			<div class="error-message" id="textError">Описание должно содержать не менее 10 символов</div>
		</div>
		
		<div class="form-group">
			<label>Прикрепите файлы</label>
			<div class="file-upload-container">
				<button type="button" id="browseFiles" class="btn btn-sm">Выбрать файлы</button>
				<input type="file"
				       id="fileInput"
				       name="files[]"
				       multiple
				       accept="image/jpeg,image/png,application/pdf,text/plain,.doc,.docx,.xls,.xlsx"
				       style="display: none;"
				       max="10">
				
				<div id="fileList" class="file-list"></div>
				
				<div id="fileError" class="error-message" style="display: none; color: #e74c3c; margin-top: 5px;"></div>
			</div>
		</div>
		
		<button type="submit" class="btn" id="submitBtn">Отправить обращение</button>
		
		<div class="success-message" id="successMessage">
			Спасибо! Ваше обращение успешно отправлено. Мы свяжемся с вами в ближайшее время.
		</div>
	</form>
</div>

@vite(['resources/js/widget.js'])
