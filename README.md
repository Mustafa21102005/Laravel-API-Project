# Laravel HMVC Authentication API

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8+-blue)
![Architecture](https://img.shields.io/badge/Architecture-HMVC-orange)
![API](https://img.shields.io/badge/API-REST-green)
![License](https://img.shields.io/badge/License-MIT-lightgrey)

A modular **Laravel API** implementing authentication and account verification using an **HMVC architecture**. The project focuses on clean modular design, event-driven workflows, and external service integration.

---

# Overview

This project demonstrates how to structure a scalable Laravel backend using **HMVC modules** while implementing a real-world authentication system.

Key design goals:

- Modular backend architecture
- Event-driven verification workflows
- Clean API structure
- External service integration
- Reusable middleware and helpers

---

# Features

## Authentication

- JWT based authentication
- Login using **email or phone**
- Secure password hashing
- Token refresh
- Logout endpoint

## Account Verification

- Email verification via **activation code**
- Phone verification via **SMS code**
- Resend activation codes
- Activation endpoints

## Event Driven System

- Email verification handled via **Events & Listeners**
- SMS verification triggered by listeners
- Decoupled communication logic

## Middleware

- Email verification checks
- Phone verification checks
- Localization middleware

## API Utilities

- Global API response helper
- Form Request validation
- Structured error responses

---

# Tech Stack

| Technology | Usage               |
| ---------- | ------------------- |
| Laravel 12 | Backend framework   |
| PHP 8+     | Language            |
| JWT Auth   | Authentication      |
| Twilio     | SMS verification    |
| Mailpit    | Local email testing |
| MySQL      | Database            |
| Postman    | API testing         |

---

# API Endpoints

## Authentication

| Method | Endpoint           | Description               |
| ------ | ------------------ | ------------------------- |
| POST   | /api/auth/register | Register user             |
| POST   | /api/auth/login    | Login with email or phone |
| POST   | /api/auth/logout   | Logout                    |
| POST   | /api/auth/refresh  | Refresh token             |
| POST   | /api/auth/me       | Authenticated user        |

---

## Verification

| Method | Endpoint                         | Description            |
| ------ | -------------------------------- | ---------------------- |
| POST   | /api/auth/activate-account       | Activate account       |
| POST   | /api/auth/resend-activation-code | Resend activation code |

---

# Installation

### 1. Clone repository

```bash
git clone https://github.com/Mustafa21102005/Laravel-API-Project.git
```

### 2. Install dependencies

```bash
composer install
```

### 3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database

Update `.env`:

```
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Start server

```bash
php artisan serve
```

---

# Environment Configuration

## Mail (Mailpit example)

```
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
```

## Twilio

```
TWILIO_SID=
TWILIO_TOKEN=
TWILIO_PHONE=
```

---

# Testing

Recommended API testing tools:

- Postman

---

# Learning Goals

This project was built to explore:

- HMVC architecture in Laravel
- Modular backend design
- Event-driven programming
- Third-party integrations
- Authentication workflows
