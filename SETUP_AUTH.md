# Налаштування Аутентифікації

## Встановлені Пакети

- **Laravel Sanctum** - для API токенів
- **Laravel Socialite** - для OAuth аутентифікації (Google, GitHub)

## Міграції Виконано ✅

Всі необхідні таблиці створено:
- `personal_access_tokens` - для Sanctum токенів
- `users` - оновлено з полями `google_id` та `github_id`

## API Endpoints

### Базова Аутентифікація
- `POST /api/v1/auth/register` - Реєстрація
- `POST /api/v1/auth/login` - Вхід
- `POST /api/v1/auth/logout` - Вихід (потрібен токен)
- `GET /api/v1/auth/me` - Отримати поточного користувача (потрібен токен)

### Відновлення Паролю
- `POST /api/v1/auth/forgot-password` - Надіслати лінк для скидання
- `POST /api/v1/auth/reset-password` - Скинути пароль з токеном

### OAuth (Google & GitHub)
- `GET /api/v1/auth/social/google` - Перенаправлення на Google OAuth
- `GET /api/v1/auth/social/google/callback` - Callback від Google (автоматично перенаправляє на frontend з токеном)
- `GET /api/v1/auth/social/github` - Перенаправлення на GitHub OAuth
- `GET /api/v1/auth/social/github/callback` - Callback від GitHub (автоматично перенаправляє на frontend з токеном)

**Важливо:** OAuth callback тепер перенаправляє на frontend з токеном у URL параметрах:
- Успіх: `{FRONTEND_URL}/auth/callback/{provider}?token={access_token}`
- Помилка: `{FRONTEND_URL}/auth/callback/{provider}?error={error_message}`

## Налаштування OAuth

### Важливо: Frontend URL

Додайте URL вашого frontend у `.env`:
```env
FRONTEND_URL=http://localhost:5173
```

OAuth callback автоматично перенаправить користувача на цю адресу після авторизації.

### 1. Google OAuth

1. Перейдіть на https://console.cloud.google.com/
2. Створіть новий проект
3. Увімкніть Google+ API
4. Створіть OAuth 2.0 credentials
5. Додайте redirect URI: `http://localhost:8000/api/v1/auth/social/google/callback`
6. Додайте в `.env`:
```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URL=http://localhost:8000/api/v1/auth/social/google/callback
```

### 2. GitHub OAuth

1. Перейдіть на https://github.com/settings/developers
2. Клікніть "New OAuth App"
3. Заповніть:
   - Homepage URL: `http://localhost:8000`
   - Callback URL: `http://localhost:8000/api/v1/auth/social/github/callback`
4. Додайте в `.env`:
```env
GITHUB_CLIENT_ID=your-client-id
GITHUB_CLIENT_SECRET=your-client-secret
GITHUB_REDIRECT_URL=http://localhost:8000/api/v1/auth/social/github/callback
```

## Тестування API

### Реєстрація
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Вхід
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### Отримати Користувача (з токеном)
```bash
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Детальна Документація

Перегляньте [AUTH_API.md](AUTH_API.md) для повної документації API з усіма endpoints, прикладами запитів та відповідей.

## Що Було Зроблено

✅ Видалено зайві методи з routes/api.php
✅ Створено AuthController з методами:
   - register (реєстрація)
   - login (авторизація)
   - logout (вихід)
   - me (поточний користувач)
   - forgotPassword (запит на відновлення)
   - resetPassword (відновлення паролю)

✅ Створено SocialAuthController для OAuth:
   - Google authentication
   - GitHub authentication

✅ Оновлено User модель:
   - Додано HasApiTokens trait
   - Додано google_id та github_id в fillable

✅ Встановлено пакети:
   - laravel/sanctum
   - laravel/socialite

✅ Виконано міграції
✅ Налаштовано routes
✅ Додано конфігурацію для Google та GitHub в config/services.php
