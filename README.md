## Demo project for collecting and processing feedback tickets from the site through a universal widget, API to save tickets, admin panel to manage tickets, built with Laravel 12, PHP 8,4 and Mysql 9 using Docker compose

## Technical Requirements

[PHP 8.4](https://www.php.net/releases/8.4/en.php)
[Composer (System Requirements)](https://getcomposer.org/doc/00-intro.md#system-requirements)
[Laravel 12.11.2](https://laravel.com/docs/12.x)
[MySQL 9.1.0](https://hub.docker.com/r/mysql/mysql-server#!)
[Testing: PHPUnit](https://docs.phpunit.de/)
[Containerization: Docker 24.* + Docker Compose 2.*](https://www.docker.com)
[laravel/ui](https://github.com/laravel/ui)

## Installation

git clone https://github.com/sevarostov/ticket-widget.git

#### Copy file `.env.example` to `.env`
```
cp .env.example .env
```

#### Make Composer install the project's dependencies into vendor/ directory

```
composer install
```

## Generate key
```
php artisan key:generate
```

## Build the project

```
docker build -t php:latest --file ./docker/php/Dockerfile --target php ./docker
```

## Docker compose:
```
docker compose up -d
docker compose down
```

## Create database schema

```
docker exec -i php php artisan migrate
```

## Seed fixures data
````
docker exec php php artisan db:seed
````
This cmd creates and saves to db:

- app users:

**login**:`admin@example.com`
**password**:`admin_pwd`

**login**:`manager@example.com`
**password**:`manager_pwd`

- customers and tickets. 

## Secured area (needs login with credentials above):
[GET /ticket] ticket list with filters

[GET /ticket/{id}] ticket's details with ability of downloading files


## Media upload
`chmod -R 775 storage/app/public`
`php artisan storage:link`

## UI
`npm install && npm run dev`

## Run tests

```
docker exec php vendor/bin/phpunit
```

## Embed widget in html code
`<iframe src="http://localhost/widget"></iframe>`


## API Request and Response examples

[POST /api/tickets]
<h3> Request:</h3>
<pre>
curl --location 'http://localhost/api/tickets' \
--header 'Accept: application/json' \
--header 'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7' \
--header 'Connection: keep-alive' \
--header 'Origin: http://localhost' \
--header 'Referer: http://localhost/widget' \
--header 'Sec-Fetch-Dest: empty' \
--header 'Sec-Fetch-Mode: cors' \
--header 'Sec-Fetch-Site: same-origin' \
--header 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36' \
--header 'X-CSRF-TOKEN: oYQuStKOFxflvOoqGtXalMBtUHHwF9ZLVkIEbmch' \
--header 'X-Requested-With: XMLHttpRequest' \
--header 'sec-ch-ua: "Not(A:Brand";v="8", "Chromium";v="144", "Google Chrome";v="144"' \
--header 'sec-ch-ua-mobile: ?0' \
--header 'sec-ch-ua-platform: "macOS"' \
--header 'Cookie: XSRF-TOKEN=eyJpdiI6IjNJS0lRaWhTM2o0MTFUQlRQdVVWSEE9PSIsInZhbHVlIjoiNzdBRVFLR1B1M3RiZVA5bmNoWW9rZXVWdXFBUGtOWk5NNHY2ZnNnVEk0V1pEcXFLTVNpMVlDd0d5VGlodXc3SWI1dGRmRm5ZZkRXZWUyR1kyTElLWDhZU0hiaytxSExrYytwSG9sZ0F6V3hpcDBZSHdubDV2UTYxbURCelNINi8iLCJtYWMiOiI1ZDM1NDZlYjNkOTY5MjA1NzA1Yjg0ZTlkNDBkNDhhNThlNTgyODMzOTc3NTgyNjI4OGU0ZDI0MWU5YTNmNTdjIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6InZ6L3BWdHVHcHMrU2FxMG1nNCtFZVE9PSIsInZhbHVlIjoiSW9QRyt3c09yUnpwNE4rdWE5QU8zQmpIWlNkTGR2L2xBVWhhU3ZneEdrZkRoUkJhN2JUV2xNa1haQlMvbTFuVU1NK0ludFBjNDM2WE5URXNGWnVja2tYTEV6cXhGVFhvdzVJaDNueW5TREJYQVZtQ20wMWN5VUVCTDFEaHkyaXYiLCJtYWMiOiJjZDRmNDNhNTJlMGNlYzE3YTI2OGRlMzA5ZGVkNmE5M2RjYTgzYmFjOWI1YmIzYTZlMGRlN2IwZGI5MDA4OWRjIiwidGFnIjoiIn0%3D' \
--form 'name="seva"' \
--form 'email="enter@mail.com"' \
--form 'phone="+79981378544"' \
--form 'topic="Тема обращения"' \
--form 'text="Текст обращения"' \
--form '_token="oYQuStKOFxflvOoqGtXalMBtUHHwF9ZLVkIEbmch"'
</pre>
<h3>Response:</h3>
<pre>
{
  "data": {
    "id": 8488,
    "customer": {
      "id": 837,
      "name": "seva",
      "phone": "+79981378544",
      "email": "enter@mail.com",
      "created_at": "2026-03-13 11:30:54",
      "updated_at": "2026-03-13 11:30:54"
    },
    "topic": "Тема обращения",
    "text": "Текст обращения",
    "status": null,
    "date_responded_at": null,
    "created_at": "2026-03-13 11:30:54",
    "updated_at": "2026-03-13 11:30:54"
  }
}
</pre>


[GET /api/tickets/statistics]
<h3> Request:</h3>
<pre>
curl --location 'http://localhost/api/tickets/statistics?period=week' \
--header 'Accept: application/json'
</pre>
<h3>Response:</h3>
<pre>
{
  "data": {
    "period": "week",
    "date": "2026-03-09 00:00:00 - 2026-03-15 23:59:59",
    "total": 8143,
    "statistics": {
      "statistics": {
        "status": {
          "new": 2721,
          "in_progress": 2715,
          "processed": 2707
        },
        "date_responded_at": {
          "yes": 2424,
          "no": 5719
        }
      }
    },
    "info": "Available periods are 'day', 'week', 'month'"
  }
}
</pre>
