<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 📘 Blog Management System API

Проект представляет собой RESTful API для управления блогом с ролями пользователей, авторизацией, загрузкой изображений и документацией через Swagger UI. Реализован с использованием Laravel, Docker, Sanctum, Swagger/OpenAPI, MySQL.

---

## 🔧 Стек технологий

- **PHP 8.2**
- **Laravel 12**
- **MySQL 8**
- **Docker + Docker Compose**
- **Nginx**
- **Laravel Sanctum** — авторизация
- **Spatie Laravel-Permission** — управление ролями и правами
- **Swagger UI** — документация API

---

## 🚀 Быстрый запуск

**1. Клонируй репозиторий:**
```bash
git clone https://github.com/your-username/blog-management-system.git
cd blog-management-system
```

**2. Скопируйте файл конфигурации::**
```bash
cp .env.example .env
```

**3. Запусти контейнеры:**
```bash
docker-compose up -d
```

**4. Установи зависимости и создай таблицы:**
```bash
docker-compose exec app composer install
docker-compose exec app php artisan migrate --seed
```

**5. Сгенерируй Swagger-документацию:**
```bash
docker-compose exec app php artisan l5-swagger:generate
```

## 📂 Структура
```bash
blog-management-system/
│   docker/
│   ├── mysql/
│   ├── nginx/
│   ├── php/
│   src/
│   ├── app/
│   │   ├── Http/Controllers/Api/
│   │   ├── Http/Controllers/Swagger/
│   │   ├── Http/Middleware/
│   │   ├── Http/Requests/
│   │   ├── Http/Resources/
│   │   ├── Models/
│   │   ├── Providers/
│   │   ├── Traits/HasFeaturedImage.php
│   │   └── ...
│   ├── database/
│   │   ├── migrations/
│   │   ├── seeders/
│   ├── routes/
│   │   └── api.php
│   ├── storage/
│   │   └── api-docs/api-docs.json  <-- для Swagger
│   ├── tests/
│   │    ├── Feature/
│   │    └── Unit/
│   ├── vendor/
│   └── docker-compose.yaml
│   ...
```


## 🔐 Аутентификация
**Используется Laravel Sanctum. Для доступа к защищённым маршрутам необходимо передавать Bearer Token**

## 👤 Роли и права
**Реализовано 4 роли:**

- **admin**
- **editor**
- **author**
- **reader**


**Права:**
```bash
// Управление постами
'manage_posts',        // Полное управление всеми постами
'create_posts',        // Создание постов
'edit_posts',          // Редактирование постов
'delete_posts',        // Удаление постов
'publish_posts',       // Публикация постов
'view_all_posts',      // Просмотр всех постов (включая черновики)

// Управление категориями
'manage_categories',   // Полное управление категориями
'create_categories',   // Создание категорий
'edit_categories',     // Редактирование категорий
'delete_categories',   // Удаление категорий

// Управление тегами
'manage_tags',         // Полное управление тегами
'create_tags',         // Создание тегов
'edit_tags',           // Редактирование тегов
'delete_tags',         // Удаление тегов

// Управление пользователями
'manage_users',        // Полное управление пользователями
'view_users',          // Просмотр списка пользователей
'edit_users',          // Редактирование пользователей
'delete_users',        // Удаление пользователей
'assign_roles',        // Назначение ролей
// Управление системой
'access_admin_panel',  // Доступ к админ панели
'view_analytics',      // Просмотр аналитики
```


## 📚 Функции API
- **Регистрация, вход, выход, профиль**
- **CRUD для постов, категорий и тегов**
- **Загрузка изображения к посту**
- **Публикация/черновик постов**
- **Фильтрация и пагинация**
- **Проверка ролей и прав доступа**


## 🧪 Тесты
Запуск:
```bash
docker-compose exec app php artisan test
```
**Покрытие:**
- **Регистрация / вход**
- **CRUD постов**
- **Роль admin, reader и т.п.**
- **Проверка авторизации**


## 📖 Swagger UI
**Открыть в браузере:**
🔗 http://localhost:8082

**Файл:** src/storage/api-docs/api-docs.json

## 🐳 Docker
**Контейнеры:**

- **app — PHP-FPM**
- **webserver — Nginx**
- **db — MySQL 8.0**
- **phpmyadmin — веб-интерфейс**
- **swagger-ui — документация**
- **redis — кэш (опционально)**

## ✅ Выполнено

- **Аутентификация с Sanctum**
- **CRUD постов с категориями и тегами**
- **Загрузка изображений**
- **Роли и права Spatie**
- **Swagger документация**
- **PHPUnit тесты**
- **Docker + MySQL + Nginx + Redis**


