# COLLEGE EVENT MANAGEMENT SYSTEM
## PHP & MySQL Web Application — IP Final Project

**Student:** Drusti Bari  
**Course:** BCA  
**Project Type:** Individual/Team academic web application  
**Technology:** PHP, MySQL, HTML, CSS

## 1. Introduction
The College Event Management System is a web-based application designed to help college organizers maintain events and participant registrations from one central dashboard. It reduces the need for scattered manual records and makes event information easier to update and review.

## 2. Objectives
- Maintain a structured record of college events.
- Register participants for selected events.
- Provide create, read, update and delete (CRUD) operations.
- Validate user input and protect pages behind login.
- Store information in a relational MySQL database.
- Display basic event and registration statistics.

## 3. Software and Hardware Requirements
**Software:** Windows, XAMPP, Apache, PHP 8+, MySQL/MariaDB, phpMyAdmin, VS Code and a web browser.  
**Hardware:** A computer capable of running XAMPP, with sufficient storage for the project files.

## 4. Modules
1. **Authentication:** Login/logout, session management, password verification and a login cookie.
2. **Dashboard:** Summary counts for events, registrations, unique participants and upcoming events.
3. **Event Management:** Add, view, search, edit and delete event records.
4. **Registration Management:** Add, view, search, edit and delete participant registrations.
5. **Database:** Stores users, events and registrations with primary and foreign keys.

## 5. Database Design
- **users:** id, name, email, password_hash, role, created_at.
- **events:** id, title, category, description, event_date, venue, capacity, organizer, created_by, created_at.
- **registrations:** id, event_id, participant_name, email, phone, notes, registered_at.

One event can have many registrations. The `registrations.event_id` foreign key links each participant to an event. Deleting an event deletes its related registrations through `ON DELETE CASCADE`.

## 6. CRUD Operations
- **Create:** `INSERT` statements create an event or participant registration.
- **Read:** `SELECT` statements list records, calculate dashboard counts and search data.
- **Update:** `UPDATE` statements edit event or registration information.
- **Delete:** `DELETE` statements remove selected records. Prepared statements are used for database operations that accept user input.

## 7. PHP Concepts Applied
- PHP variables, arrays, conditional statements, loops and functions.
- Form submission using `GET` and `POST`.
- Server-side required-field, email, date and capacity validation.
- Sessions to maintain authenticated login state.
- Cookies to store the last-login time for demonstration.
- Database connectivity through MySQLi.
- Prepared statements and output escaping to reduce SQL injection and HTML injection risks.
- File/folder handling through separate reusable PHP files (`config.php`, shared header/footer and feature pages).

## 8. Testing Plan
| Test | Action | Expected result |
|---|---|---|
| Login with correct credentials | Enter demo email and password | Dashboard opens |
| Login with incorrect credentials | Enter invalid password | Error is displayed |
| Open dashboard without login | Visit dashboard URL directly | Redirects to login |
| Add event | Submit valid event details | Event is stored and listed |
| Validate event form | Leave required field empty | Validation message appears |
| Search events | Search by title/category/venue | Matching records appear |
| Edit event | Change an event field | Updated value appears |
| Delete event | Confirm deletion | Event and related registrations are removed |
| Add registration | Choose event and valid participant details | Registration is stored |
| Invalid email | Enter malformed email | Registration is rejected |
| Capacity check | Register beyond capacity | New registration is blocked |
| Edit/delete registration | Perform action in registration list | Record is updated/removed |

**Important:** Run these tests on your local XAMPP installation and record the actual results/screenshots. Do not mark a test as passed until it has been executed.

## 9. Installation and Execution
1. Copy the project folder to `C:\xampp\htdocs`.
2. Start Apache and MySQL from XAMPP.
3. Import `database.sql` through phpMyAdmin.
4. Check the database credentials in `config.php`.
5. Visit `http://localhost/College_Event_Management_System/`.
6. Sign in using the demo credentials shown on the login page.

## 10. Conclusion
The project demonstrates how PHP and MySQL can be combined to build a basic college event administration application. It provides event and registration CRUD, validation, login sessions, cookie handling and a relational database design. Further improvements could include student self-registration, event posters, email notifications, attendance tracking, reports and role-based permissions.

## 11. Screenshot Evidence
Insert screenshots from your own running project:
1. Login page
2. Dashboard
3. Events list
4. Create/edit event form
5. Registrations list
6. Registration form
7. Database tables in phpMyAdmin
