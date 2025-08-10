# ChatBotSysNotif (SysNotif-SGhandi)

A Laravel 10 application to manage clients and invoices, send automated unpaid-invoice reminders by email, and provide an authenticated dashboard. Supports classic email/password auth and optional Google Sign-In.

---

## Table of Contents
- [Features](#features)
- [Prerequisites](#prerequisites)
- [Quick Start](#quick-start)
- [Environment Configuration](#environment-configuration)
- [Build Frontend Assets](#build-frontend-assets)
- [Run the App](#run-the-app)
- [Email & Scheduler](#email--scheduler)
- [Routes & Usage](#routes--usage)
- [Troubleshooting](#troubleshooting)

---

## Features
- Authentication: register/login with email & password; optional Google login (Socialite)
- Dashboard with protected access
- Client management (`ClientsTest`)
- Invoice management (`FacturesTest`) with per-invoice email notification
- Automated hourly email reminders for overdue unpaid invoices

---

## Prerequisites
Install these before continuing:
- PHP 8.1+
- Composer
- Node.js 18+ with npm
- A database (MySQL recommended). SQLite/PostgreSQL can work but examples below use MySQL

Optional but recommended for local email testing:
- Mailtrap (or another SMTP testing service)

---

## Quick Start
1) Install dependencies
```bash
composer install
npm install
```

2) Create and configure env file
```bash
copy .env.example .env   # Windows
# cp .env.example .env   # macOS/Linux
php artisan key:generate
```
Edit `.env` with your DB and mail settings (see below).

3) Migrate default Laravel tables
```bash
php artisan migrate
```

4) Create business tables (clients & invoices)
- Run the SQL in the [Database Setup](#database-setup) section to create `ClientsTest` and `FacturesTest`.

5) Run the app
```bash
npm run dev
php artisan serve
```
Open http://127.0.0.1:8000

---

## Environment Configuration
Update the following in your `.env`:

Basic app
```env
APP_NAME="SysNotif-SGhandi"
APP_ENV=local
APP_KEY=base64:generated-by-key-generate
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
```

Database (MySQL example)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

Mail (SMTP example)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="SysNotif"
```

Queues (safe default for local)
```env
QUEUE_CONNECTION=sync
```

Optional: Google Sign-In
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
# Ensure your Google OAuth redirect URL matches exactly:
# http://127.0.0.1:8000/auth/google/callback-url
```

---

## Build Frontend Assets
- Development (auto refresh):
```bash
npm run dev
```
- Production build:
```bash
npm run build
```

The app also serves static assets from `public/admin_assets/*`.

---

## Run the App
```bash
php artisan serve
```
Visit http://127.0.0.1:8000

- Create an account at `/register`, then login at `/login`
- Or use Google Sign-In if configured

---

## Email & Scheduler
Send a single invoice notification manually
```bash
php artisan route:list | findstr factures
# Find a facture id, then:
php artisan tinker   # optional to explore
# or trigger via browser: POST /factures/{id}/notify (from UI buttons)
```

Run overdue unpaid invoices reminder manually
```bash
php artisan alerts:impayes
```

Automate hourly reminders (scheduler)
- The app schedules `alerts:impayes` hourly. To run the scheduler:
  - Quick dev method:
    ```bash
    php artisan schedule:work
    ```
  - Production/CI: run every minute via cron (Linux/macOS):
    ```
    * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
    ```
  - Windows: create a Task Scheduler task to run `php artisan schedule:run` every minute

Email tips
- For local, use Mailtrap or set `MAIL_MAILER=log` to log emails instead of sending
- Queues default to `sync` in this guide; no separate worker needed locally

---

## Routes & Usage
Authentication
- GET `/register` → create user (sends welcome email)
- GET `/login` → login form
- GET `/logout` → logout (requires auth)
- GET `/auth/google` → redirect to Google (optional)
- GET `/auth/google/callback-url` → Google OAuth callback

App
- GET `/` → dashboard (requires auth)
- Clients (requires auth):
  - GET `/clients` (list), `/clients/create`, POST `/clients`, GET `/clients/{CodeTiers}`, etc.
- Invoices:
  - Resource routes under `/factures` (list, show, create, edit, delete)
  - POST `/factures/{id}/notify` → send email for a single invoice
- Chatbot endpoint (requires auth): POST `/chat/query`

---

## Troubleshooting
- Composer/npm not found: ensure they’re installed and on PATH
- DB errors: verify `.env` DB settings; confirm `ClientsTest` and `FacturesTest` exist
- Email not sending: use Mailtrap or set `MAIL_MAILER=log` to inspect output
- Port in use: `php artisan serve --port=8080`
- Asset issues: re-run `npm install` and `npm run dev`
- Social login: ensure Google credentials and redirect URL match exactly

---

If you need help, open an issue or start a discussion on the repository.
