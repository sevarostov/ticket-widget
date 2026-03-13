## Технические решения 

1. Архитектурные решения
   MVC + слои:

Контроллеры — только маршрутизация и передача данных.

Сервисы (App\Services) — бизнес‑логика (создание тикета, смена статуса, расчёт статистики).

Репозитории (App\Repositories) — работа с БД (фильтрация, пагинация).

Ресурсы (App\Http\Resources) — форматирование API‑ответов.

SOLID/DRY/KISS:

Единый сервис для тикетов (TicketService) с методами: createTicket(), updateStatus(), getStatistics().

Репозиторий TicketRepository с методами фильтрации (filterByDate(), filterByStatus()).

Переиспользуемые FormRequest для валидации.

PSR‑12:

Соблюдение стандарта кодирования PHP (именование, отступы, документация).

2. Технологический стек
   Laravel 12 — фреймворк для MVC, роутинга, API, Blade‑шаблонов.

PHP 8.4 — версия с поддержкой enum, readonly‑классов, улучшений производительности.

MySQL — основная БД (миграции, связи сущностей).

Spatie\laravel‑permission — управление ролями (роль «manager»).

Spatie\laravel‑medialibrary — загрузка и хранение файлов (связь с Ticket).

Carbon — работа с датами (периоды «день/неделя/месяц»).

Docker Compose — локальное окружение (PHP‑FPM, MySQL, Nginx).

PhpUnit — тестирование (юнит, фича).

Darkaonline/l5-swagger  Swagger/OpenAPI 3.0 — документация API  [GET /api/documentation]  

3. Модели и связи
   
User (users):

Поля: name, email, password.

Роль: «manager» (через spatie/laravel‑permission).

Customer (customers):

Поля: name, phone (E.164), email.

Уникальность: (phone, email) для ограничения создания тикетов в день.

Ticket (tickets):

Связи: customer (ManyToOne), files (через medialibrary).

Поля: topic, text, status (enum), date_responded_at.

File — через Media модели (medialibrary), привязка к Ticket.

4. Валидация и безопасность
   
FormRequest:

StoreTicketRequest — валидация телефона (регулярное выражение для E.164), email, обязательных полей.

UpdateTicketStatusRequest — проверка статуса (new/in_progress/processed).

Ограничение создания тикетов в день:

В сервисе TicketService проверка: поиск тикета за последние 24 ч по phone или email.

Возврат ошибки 429 (Too Many Requests), если лимит превышен.

CSRF/CORS:

Защита форм (CSRF‑токен в виджете).

CORS‑политика для API (домены виджета).

5. API
   Маршруты:

POST /api/tickets → TicketController@store (создание тикета).

GET /api/tickets/statistics?period=day → TicketController@statistics (статистика).

Ресурсы:

TicketResource — ответ для тикета (включая customer, files).

TicketStatisticsResource — структура статистики (по периодам, статусам).

Элокуент‑скоупы:

forPeriod($period) в модели Ticket — фильтрация по периодам (Carbon).

6. Админ‑панель (Blade)
   Маршруты:

/admin/tickets — список тикетов (с фильтрацией).

/admin/tickets/{id} — детальная страница (тикет + файлы).

Фильтрация:

Форма с полями: дата (от/до), статус, email, phone.

Передача параметров в репозиторий (TicketRepository@filter()).

Смена статуса:

Submit form запрос на PATCH /api/tickets/{id}/status.

Обновление в реальном времени (без перезагрузки страницы).

7. Виджет (Blade + AJAX)
   Маршрут: /widget → WidgetController@show.

Форма:

Blade‑шаблон с полями: имя, телефон, email, тема, текст, файлы.

Стилизация (CSS/Tailwind) для встраивания в `<iframe>`.

AJAX:

Отправка через fetch или Axios на /api/tickets.

Обработка ошибок (422, 429) и успех (201) с отображением сообщений.

8. Инфраструктура
   Миграции:

Создание таблиц users, customers, tickets, media (medialibrary).

Индексы для phone, email в customers.

Фабрики и сидеры:

UserFactory → пользователь с ролью «manager».

CustomerFactory, TicketFactory → тестовые данные (5–10 тикетов).

Docker Compose:

Контейнеры: app (PHP‑FPM), db (MySQL), nginx.

volumes для кода и БД.

README.md:

Инструкция: установка, запуск (docker-compose up), миграции, сиды.
- Поля "Created_at" заполняются в таблице tickets периодами ("день", "неделя", "месяц"), чтобы иметь возможность тестировать api statistics endpoint
- Транзакция базы данных используется для ограничения создания не более одного тикета в день с одного номера/электронной почты.

Примеры API‑запросов (curl), виджета (`<iframe>`), тестовые учётные данные.

9. Тестирование
   Юнит‑тесты:

Сервис TicketService (создание, лимит, статистика).

Репозиторий TicketRepository (фильтрация).

Фича‑тесты:

API‑эндпоинты (POST /api/tickets, GET /api/tickets/statistics).

Админ‑маршруты (/admin/tickets).

10. Документация API
    Swagger YAML:

Описание всех эндпоинтов (/api/tickets, /api/tickets/statistics).

Схемы ресурсов (TicketResource, TicketStatisticsResource).

Коды ответов, примеры запросов/ответов.

Swagger UI:

Встроенный интерфейс (пакет darkaonline/l5-swagger) для интерактивной документации.


Spatie/laravel‑permission: решение для ролей, интеграция с Laravel Auth.

Spatie/laravel‑medialibrary: упрощает работу с файлами (загрузка, хранение, связь с моделями).
