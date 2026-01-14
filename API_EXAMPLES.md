# Приклади використання API

## Health Check
```bash
curl http://localhost:8000/api/health
```

## Отримати всіх користувачів
```bash
curl http://localhost:8000/api/v1/users
```

## Отримати користувача за ID
```bash
curl http://localhost:8000/api/v1/users/1
```

## Створити нового користувача
```bash
curl -X POST http://localhost:8000/api/v1/users \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }'
```

## Оновити користувача
```bash
curl -X PUT http://localhost:8000/api/v1/users/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Jane Doe",
    "email": "jane@example.com"
  }'
```

## Видалити користувача
```bash
curl -X DELETE http://localhost:8000/api/v1/users/1 \
  -H "Accept: application/json"
```

## Тестування з HTTPie (якщо встановлено)

### Health Check
```bash
http GET http://localhost:8000/api/health
```

### Створити користувача
```bash
http POST http://localhost:8000/api/v1/users \
  name="John Doe" \
  email="john@example.com" \
  password="password123"
```

### Отримати користувачів
```bash
http GET http://localhost:8000/api/v1/users
```

## Тестування з Postman

Імпортуйте ці endpoint'и в Postman:

1. Health Check: `GET http://localhost:8000/api/health`
2. Get Users: `GET http://localhost:8000/api/v1/users`
3. Get User: `GET http://localhost:8000/api/v1/users/{id}`
4. Create User: `POST http://localhost:8000/api/v1/users`
5. Update User: `PUT http://localhost:8000/api/v1/users/{id}`
6. Delete User: `DELETE http://localhost:8000/api/v1/users/{id}`
