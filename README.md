# College Event Management System (IP Final Project)

A PHP + MySQL web application for managing college events and participant registrations.

## Features
- Login/logout using PHP sessions and hashed-password verification
- Secure prepared statements for database reads and writes
- Dashboard statistics for events, registrations, participants and upcoming events
- Event CRUD: create, list, search, update and delete
- Registration CRUD: add participants, list/search, edit and delete
- Server-side validation, HTML escaping, date/capacity checks
- Optional event poster upload (JPG/PNG/WEBP, 2 MB limit) to demonstrate file handling
- Cookie set at login to demonstrate cookie handling
- Responsive dashboard UI and sample data

## Requirements
- XAMPP (Apache + MySQL/MariaDB)
- PHP 8.0+ recommended
- Modern browser

## Setup on Windows (XAMPP)
1. Extract this folder into `C:\\xampp\\htdocs\\College_Event_Management_System`.
2. Open XAMPP Control Panel and start **Apache** and **MySQL**.
3. Visit `http://localhost/phpmyadmin`.
4. Choose **Import**, select `database.sql`, then click **Import/Go**.
5. Open `config.php`. The default XAMPP values are `root` username and blank password. If your MySQL has a password, update `$db_pass`.
6. Open `http://localhost/College_Event_Management_System/`.
7. Demo credentials: `admin@college.edu` / `Admin@123`.

## If demo login doesn't work
If the SQL file was imported before a password-hash correction, import it into a fresh database or reset the account password with a PHP `password_hash()` value generated locally. Confirm the email in the `users` table is `admin@college.edu`.

## Submission checklist
- [x] PHP source code
- [x] MySQL database SQL file
- [x] Project report
- [ ] Add your own screenshots after running the app in XAMPP
- [ ] Demonstrate the working project to your faculty

## Academic note
This is a local classroom demo. Before public deployment, add CSRF protection, stronger authorization controls, secure HTTPS cookies and production database credentials.
