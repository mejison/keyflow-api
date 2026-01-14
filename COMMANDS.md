# Корисні команди Laravel

## Загальні команди

### Запуск сервера
```bash
php artisan serve
# або з конкретним портом
php artisan serve --port=8080
```

### Перегляд маршрутів
```bash
php artisan route:list
# або тільки API маршрути
php artisan route:list --path=api
```

### Очистка кешу
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Оптимізація для продакшн
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## База даних

### Міграції
```bash
# Запустити міграції
php artisan migrate

# Відкотити останню міграцію
php artisan migrate:rollback

# Відкотити всі міграції
php artisan migrate:reset

# Оновити БД (rollback + migrate)
php artisan migrate:refresh

# Оновити БД з seed
php artisan migrate:refresh --seed

# Створити нову міграцію
php artisan make:migration create_posts_table
```

### Seeders
```bash
# Запустити всі seeders
php artisan db:seed

# Запустити конкретний seeder
php artisan db:seed --class=UserSeeder

# Створити новий seeder
php artisan make:seeder UserSeeder
```

## Генерація коду

### Контролери
```bash
# API контролер
php artisan make:controller Api/PostController

# Resource контролер
php artisan make:controller PostController --resource

# API resource контролер
php artisan make:controller Api/PostController --api
```

### Моделі
```bash
# Проста модель
php artisan make:model Post

# Модель з міграцією
php artisan make:model Post -m

# Модель з міграцією, контролером і factory
php artisan make:model Post -mcf

# Модель з усім (migration, controller, factory, seeder, policy, resource)
php artisan make:model Post --all
```

### Resources
```bash
# API Resource
php artisan make:resource PostResource

# Resource Collection
php artisan make:resource PostCollection
```

### Requests
```bash
php artisan make:request StorePostRequest
```

### Middleware
```bash
php artisan make:middleware CheckApiToken
```

## Tinker

```bash
# Відкрити інтерактивну консоль
php artisan tinker

# Виконати код безпосередньо
php artisan tinker --execute="App\Models\User::count()"
```

### Приклади в Tinker
```php
// Створити користувача
User::create(['name' => 'John', 'email' => 'john@example.com', 'password' => bcrypt('password')]);

// Знайти користувача
User::find(1);

// Отримати всіх користувачів
User::all();

// Оновити користувача
User::find(1)->update(['name' => 'Jane']);

// Видалити користувача
User::find(1)->delete();
```

## Тестування

```bash
# Запустити всі тести
php artisan test

# Запустити конкретний тест
php artisan test --filter=UserTest

# Тести з покриттям
php artisan test --coverage
```

## Інші корисні команди

### Створення символічного посилання для storage
```bash
php artisan storage:link
```

### Перегляд налаштувань
```bash
php artisan config:show database
```

### Інформація про середовище
```bash
php artisan about
```

### Створення резервної копії БД (потрібен пакет)
```bash
php artisan backup:run
```
