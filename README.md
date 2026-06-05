# Starr

## Description
Starr is a PHP MVC platform for children, students, teachers, parents, and administrators.
It combines lessons, quizzes, posts, comments, reports, news, points, and badges.
The goal is to provide a safe educational community with moderation tools.
Teachers can publish content and quizzes for learning activities.
Students can participate through lessons, posts, and quiz attempts.
Parents and admins can monitor activity and review reports.

## Technologies Used
* Frontend: HTML5, CSS3, JavaScript (ES6+)
* Backend: PHP 8+ (native, without framework)
* Database: MySQL (via phpMyAdmin / XAMPP)

## Prerequisites
* XAMPP / WAMP / MAMP (PHP 8+ + MySQL)
* Modern web browser (Chrome, Firefox, Edge)

## Installation
Estimated setup time: about 5 to 10 minutes if XAMPP and MySQL are already installed.
1. Clone the repository into your web server folder, for example `c:\xampp\htdocs\Starr`.
2. Copy the project to the root folder of XAMPP, WAMP, or MAMP.
3. Import the database into phpMyAdmin or MySQL.

```bash
# Copy the project to the root folder of XAMPP / WAMP / MAMP
cp -r . /xampp/htdocs/project-name

# Import the database into phpMyAdmin:
mysql -u root -p database_name

# Or use the built-in PHP server:
php -S localhost:8000
```

4. Copy `.env.example` to `.env` and fill in your local values if needed.
5. Keep real secrets out of the repository and use `.env` only on your machine.
6. Start Apache and MySQL in XAMPP.
7. Open the project in your browser and verify the front office and back office pages load.

## Core Features
* User accounts with roles and profile data
* Lessons and quiz questions
* Posts, messages, and comments
* Reports and moderation responses
* News publishing and classroom content
* Points, transactions, and badge tracking

## Repository Files
* `.gitignore` excludes local dependencies, build outputs, logs, and real environment files.
* `.env.example` documents the environment variables without exposing secrets.
* `docs/` contains technical documentation, deployment notes, API notes, and database references.
* `demo/` is reserved for screenshots, GIFs, or video links.
* `database/schema.sql` creates the database structure and includes seed data for testing.

## Documentation
* [Architecture overview](docs/architecture.md)
* [API reference](docs/api.md)
* [Database notes](docs/database.md)
* [Deployment guide](docs/deployment.md)

## Notes
* Do not commit real `.env` files, API keys, passwords, or generated logs.
* Keep `demo/` for presentation assets only.
