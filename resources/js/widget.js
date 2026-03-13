let allSelectedFiles = [];
const fileList = document.getElementById('fileList');

document.addEventListener('DOMContentLoaded', function () {
	const browseButton = document.getElementById('browseFiles');
	const fileInput = document.getElementById('fileInput');
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
		clearErrorMessages();
		if (xhr.status >= 200 && xhr.status < 300) {
			try {
				const response = JSON.parse(xhr.responseText);
				
				if (response.success || response.data && response.data.id) {
					successContainer.textContent =
						'Обращение успешно отправлено! Номер заявки: ' + (response.data.id || 'не присвоен');
					successContainer.classList.remove('d-none');
					errorContainer.classList.add('d-none');
					form.reset();
					clearAllSelectedFiles();
				} else {
					errorContainer.textContent =
						response.error || 'Произошла непредвиденная ошибка.';
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
 * Очищает все выбранные файлы с анимацией и обновлением интерфейса
 */
function clearAllSelectedFiles() {
	
	const fileList = document.getElementById('fileList');
	fileList.style.opacity = '0';
	
	setTimeout(() => {
		allSelectedFiles = [];
		displaySelectedFiles([]);
		fileList.style.opacity = '1';
	}, 300);
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
