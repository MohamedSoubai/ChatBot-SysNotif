# SysNotif-SGhandi

Welcome to the SysNotif-SGhandi project! This guide will help you set up and run the project on your computer, even if you have no coding experience.

---

## Table of Contents
- [Prerequisites](#prerequisites)
- [Setting Up the Project](#setting-up-the-project)
- [Configuration](#configuration)
- [Running the Project](#running-the-project)
- [Troubleshooting](#troubleshooting)
- [Additional Resources](#additional-resources)

---

## Prerequisites
Before you begin, make sure you have the following software installed:

1. **PHP (version 8.1 or higher)**
   - [Download PHP](https://www.php.net/downloads.php)
2. **Composer** (dependency manager for PHP)
   - [Download Composer](https://getcomposer.org/download/)
3. **Node.js and npm** (for frontend assets)
   - [Download Node.js (includes npm)](https://nodejs.org/)
4. **A Database** (MySQL, PostgreSQL, SQLite, or SQL Server)
   - For beginners, [XAMPP](https://www.apachefriends.org/index.html) is an easy way to get MySQL and PHP together.

---

## Setting Up the Project

1. **Download or Clone the Project**
   - Download the ZIP from GitHub and extract it, or use:
     ```
     git clone <repository-url>
     ```
   - Open a terminal/command prompt and navigate to the project folder.

2. **Install PHP Dependencies**
   - Run:
     ```
     composer install
     ```

3. **Install Node.js Dependencies**
   - Run:
     ```
     npm install
     ```

4. **Set Up Environment Variables**
   - Copy the example environment file:
     ```
     copy .env.example .env
     ```
     (On Mac/Linux, use `cp .env.example .env`)

5. **Generate Application Key**
   - Run:
     ```
     php artisan key:generate
     ```

---

## Configuration

### 1. Database Setup
- Edit the `.env` file in the project root.
- Set these variables to match your database:
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=your_database_name
  DB_USERNAME=your_database_user
  DB_PASSWORD=your_database_password
  ```
- Create the database in your database server (e.g., MySQL Workbench, phpMyAdmin, or command line).

### 2. Mail Setup (for sending emails)
- In the `.env` file, set up your mail provider:
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.example.com
  MAIL_PORT=587
  MAIL_USERNAME=your_email@example.com
  MAIL_PASSWORD=your_email_password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=your_email@example.com
  MAIL_FROM_NAME="Your Name or App Name"
  ```
- For testing, you can use [Mailtrap](https://mailtrap.io/) or similar services.

### 3. Google Login (Optional)
- If you want to enable Google login, set these in your `.env`:
  ```
  GOOGLE_CLIENT_ID=your_google_client_id
  GOOGLE_CLIENT_SECRET=your_google_client_secret
  ```
- [How to get Google OAuth credentials](https://developers.google.com/identity/sign-in/web/sign-in)

---

## Running the Project

1. **Run Database Migrations**
   - This creates the necessary tables in your database:
     ```
     php artisan migrate
     ```

2. **(Optional) Seed the Database**
   - To add sample data (if available):
     ```
     php artisan db:seed
     ```

3. **Build Frontend Assets**
   - For development (auto-reloads on changes):
     ```
     npm run dev
     ```
   - For production (optimized build):
     ```
     npm run build
     ```

4. **Start the Laravel Server**
   - Run:
     ```
     php artisan serve
     ```
   - The app will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Troubleshooting

- **Composer or npm not found?**
  - Make sure they are installed and added to your system PATH.
- **Database connection errors?**
  - Double-check your `.env` database settings and ensure the database exists.
- **Permission errors?**
  - On Mac/Linux, you may need to run `chmod -R 775 storage bootstrap/cache`.
- **Emails not sending?**
  - Check your mail settings in `.env` and use a test service like Mailtrap.
- **Port already in use?**
  - Try `php artisan serve --port=8080` to use a different port.
- **Other issues?**
  - Try running `composer install` and `npm install` again.
  - Check the [Laravel documentation](https://laravel.com/docs/10.x) for more help.

---

## Additional Resources
- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Composer Documentation](https://getcomposer.org/doc/)
- [Node.js Documentation](https://nodejs.org/en/docs/)
- [Mailtrap (for email testing)](https://mailtrap.io/)

---

If you have any questions or need help, feel free to open an issue on the repository!
