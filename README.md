# Starr

## Description
Starr is a PHP MVC web platform for children, teachers, parents, and administrators. It includes lessons, quizzes, posts, comments, reports, news, and a points/badges system.

## Requirements
- PHP 7.4 or higher
- Apache or another web server
- MySQL or MariaDB
- XAMPP is recommended for local development

## Repository files
- `.gitignore` excludes local dependencies, build outputs, logs, and real environment files.
- `.env.example` documents the environment variables without exposing secrets.
- `docs/` contains technical documentation, deployment notes, API notes, and database references.
- `demo/` is reserved for screenshots, GIFs, or video links.
- `database/schema.sql` creates the database structure and includes seed data for testing.

## Installation
1. Clone the repository into your web root.
2. Create a local `.env` file from `.env.example` if you want to store environment values outside code.
3. Import `database/schema.sql` into MySQL.
4. Update `.env` if your local database or mail credentials differ.
5. Start Apache and MySQL, then open the project in your browser.

## Running locally
- Front office: open the main project URL in your browser.
- Back office: use the admin views under `View/Back office/`.
- If you add new documentation, place it in `docs/`.

## Documentation
- [Architecture overview](docs/architecture.md)
- [API reference](docs/api.md)
- [Database notes](docs/database.md)
- [Deployment guide](docs/deployment.md)

## Notes
- Do not commit real `.env` files, API keys, passwords, or generated logs.
- Keep `demo/` for presentation assets only.
