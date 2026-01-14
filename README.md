# KeyFlow API

Базовий Laravel API проект з RESTful архітектурою.

## Вимоги

- PHP 8.4+
- Composer
- SQLite (або інша БД)

## Встановлення

1. Клонуйте репозиторій
2. Встановіть залежності:
```bash
composer install
```

3. Налаштуйте файл `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

4. Запустіть міграції:
```bash
php artisan migrate
```

## Запуск сервера

```bash
php artisan serve
```

API буде доступне за адресою: `http://localhost:8000`

## API Endpoints

### Health Check
```
GET /api/health
```
Перевірка стану API.

### Users (CRUD)

#### Отримати всіх користувачів
```
GET /api/v1/users
```

#### Отримати користувача за ID
```
GET /api/v1/users/{id}
```

#### Створити користувача
```
POST /api/v1/users
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
}
```

#### Оновити користувача
```
PUT /api/v1/users/{id}
Content-Type: application/json

{
    "name": "Jane Doe",
    "email": "jane@example.com"
}
```

#### Видалити користувача
```
DELETE /api/v1/users/{id}
```

## Структура проекту

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           ├── BaseController.php    # Базовий контролер з методами для відповідей
│   │           └── UserController.php    # CRUD операції для користувачів
│   └── Models/
│       └── User.php
├── routes/
│   ├── api.php                          # API маршрути
│   └── web.php
├── config/
│   └── cors.php                         # Налаштування CORS
└── database/
    └── migrations/
```

## Формат відповідей

### Success Response
```json
{
    "success": true,
    "message": "Success message",
    "data": {}
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {}
}
```

## Тестування

Запустити тести:
```bash
php artisan test
```

## Розробка

### Створення нового контролера
```bash
php artisan make:controller Api/YourController
```

### Створення моделі
```bash
php artisan make:model YourModel -m
```

### Створення міграції
```bash
php artisan make:migration create_your_table
```

## CORS

CORS налаштовано для роботи з будь-яких джерел. Для production середовища рекомендується обмежити `allowed_origins` в `config/cors.php`.

## Ліцензія

Open-source проект на основі Laravel framework ([MIT license](https://opensource.org/licenses/MIT)).
