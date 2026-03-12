<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Форма обратной связи</title>
	<style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .feedback-form {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .form-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        input, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        .invalid {
            border-color: #e74c3c;
        }

        .btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #5a6fd8;
        }

        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            margin-top: 20px;
            display: none;
        }

        #errorContainer {
            white-space: pre-wrap;
            margin-bottom: 20px;
        }
	</style>
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

<script>
	let allSelectedFiles = [];
	
	document.addEventListener('DOMContentLoaded', function () {
		const browseButton = document.getElementById('browseFiles');
		const fileInput = document.getElementById('fileInput');
		const fileList = document.getElementById('fileList');
		const fileError = document.getElementById('fileError');
		
		browseButton.addEventListener('click', function () {
			fileInput.click();
		});
		
		fileInput.addEventListener('change', function (event) {
			const newlySelectedFiles = Array.from(event.target.files);
			
			if (newlySelectedFiles.length === 0) return;
			
			const totalFilesCount = allSelectedFiles.length + newlySelectedFiles.length;
			
			if (totalFilesCount > 10) {
				fileError.textContent = 'Можно выбрать не более 10 файлов';
				fileError.style.display = 'block';
				return;
			}
			
			allSelectedFiles = [...allSelectedFiles, ...newlySelectedFiles];
			fileInput.value = '';
			
			displaySelectedFiles(allSelectedFiles);
			fileError.style.display = 'none';
		});
		
		/**
		 * Отображает список выбранных файлов
		 * @param {File[]} files — массив выбранных файлов
		 */
		function displaySelectedFiles(files) {
			fileList.innerHTML = '';
			
			if (files.length === 0) {
				fileList.innerHTML = '<p class="no-files">Файлы не выбраны</p>';
				return;
			}
			
			const fileListElement = document.createElement('ul');
			fileListElement.className = 'selected-files-list';
			
			files.forEach((file, index) => {
				
				const listItem = document.createElement('li');
				listItem.className = 'file-item';
				
				const fileSize = formatFileSize(file.size);
				
				listItem.innerHTML = `
                <span class="file-name">${file.name}</span>
                <span class="file-size">(${fileSize})</span>
                <button type="button"
                        class="remove-file-btn"
                        data-index="${index}">
                    ×
                </button>
            `;
				
				fileListElement.appendChild(listItem);
			});
			
			fileList.appendChild(fileListElement);
		}
		
		/**
		 * Форматирует размер файла в читаемый вид (KB, MB)
		 * @param {number} bytes — размер файла в байтах
		 * @return {string} — форматированный размер
		 */
		function formatFileSize(bytes) {
			if (bytes === 0) return '0 Bytes';
			
			const k = 1024;
			const sizes = ['Bytes', 'KB', 'MB', 'GB'];
			const i = Math.floor(Math.log(bytes) / Math.log(k));
			
			return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
		}
		
		fileList.addEventListener('click', function (event) {
			if (event.target.classList.contains('remove-file-btn')) {
				const index = parseInt(event.target.getAttribute('data-index'), 10);
				removeFile(index);
			}
		});
		
		/**
		 * Удаляет файл из списка
		 * @param {number} index — индекс файла для удаления
		 */
		function removeFile(index) {
			allSelectedFiles.splice(index, 1);
			displaySelectedFiles(allSelectedFiles);
		}
	});
	
	document.getElementById('feedbackForm').addEventListener('submit', function (e) {
		e.preventDefault();
		
		clearErrorMessages();
		const form = e.target;
		const submitBtn = form.querySelector('button[type="submit"]');
		const errorContainer = document.getElementById('errorContainer');
		const successContainer = document.getElementById('successContainer');
		const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
		submitBtn.disabled = true;
		submitBtn.textContent = 'Отправка...';
		errorContainer.classList.add('d-none');
		successContainer.classList.add('d-none');
		
		const xhr = new XMLHttpRequest();
		xhr.open('POST', '/api/tickets', true);
		
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xhr.setRequestHeader('Accept', 'application/json');
		xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
		
		const formData = new FormData();
		
		new FormData(form).forEach((value, key) => {
			if (key !== 'files[]') {
				formData.append(key, value);
			}
		});
		
		formData.append('_token', csrfToken);
		
		allSelectedFiles.forEach(file => {
			formData.append('files[]', file);
		});
		
		xhr.onload = function () {
			
			if (xhr.status >= 200 && xhr.status < 300) {
				try {
					const response = JSON.parse(xhr.responseText);
					
					if (response.success || response.data.id) {
						successContainer.textContent =
							'Обращение успешно отправлено! Номер заявки: ' + (response.data.id || 'не присвоен');
						successContainer.classList.remove('d-none');
						form.reset();
						clearErrorMessages();
					} else {
						errorContainer.textContent =
							response.message || 'Произошла непредвиденная ошибка.';
						errorContainer.classList.remove('d-none');
						successContainer.classList.add('d-none');
					}
				} catch (err) {
					console.error('Ошибка парсинга JSON:', err);
					errorContainer.textContent = 'Ошибка обработки ответа сервера.';
					errorContainer.classList.remove('d-none');
					successContainer.classList.add('d-none');
				}
			} else {
				clearErrorMessages();
				try {
					const response = JSON.parse(xhr.responseText);
					let errorMessage = '';
					
					if (xhr.status === 422 && response.errors) {
						errorMessage = Object.values(response.errors)
							.flat()
							.join('\n');
						displayValidationErrors(response.errors);
					} else if (response.message) {
						errorMessage = response.message;
					} else {
						errorMessage = 'Произошла ошибка при отправке формы.';
					}
					
					errorContainer.textContent = errorMessage;
					errorContainer.classList.remove('d-none');
					successContainer.classList.add('d-none');
				} catch (err) {
					
					errorContainer.textContent =
						'Произошла ошибка при отправке формы. Проверьте подключение к интернету.';
					errorContainer.classList.remove('d-none');
					successContainer.classList.add('d-none');
				}
			}
		};
		
		xhr.onerror = function () {
			errorContainer.textContent =
				'Ошибка сети. Проверьте подключение к интернету и попробуйте снова.';
			errorContainer.classList.remove('d-none');
			successContainer.classList.add('d-none');
		};
		
		xhr.onloadend = function () {
			submitBtn.disabled = false;
			submitBtn.textContent = 'Отправить обращение';
		};
		
		xhr.send(formData);
	});
	
	function displayValidationErrors(errors) {
		clearErrorMessages();
		
		Object.keys(errors).forEach(field => {
			const errorMessage = errors[field][0];
			const fieldElement = document.querySelector(`[name="${field}"]`);
			
			if (fieldElement) {
				const errorElement = document.createElement('div');
				errorElement.style.color = 'red';
				errorElement.style.fontSize = '14px';
				errorElement.style.marginTop = '5px';
				errorElement.classList.add('validation-error');
				errorElement.textContent = errorMessage;
				
				fieldElement.classList.add('invalid');
				fieldElement.after(errorElement);
			}
		});
	}
	
	function clearErrorMessages() {
		document.querySelectorAll('.error-message').forEach(el => el.remove());
		document.querySelectorAll('.validation-error').forEach(el => el.remove());
		document.querySelectorAll('input, textarea, select').forEach(el => {
			el.classList.remove('invalid');
		});
	}
</script>
