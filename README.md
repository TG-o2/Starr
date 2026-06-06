# Starr

> A safe, role-based educational platform for children, students, teachers, parents, and administrators — built with PHP MVC, MySQL, and Bootstrap.

---

## Table of Contents

- [Description](#description)
- [Screenshots](#screenshots)
- [Technologies Used](#technologies-used)
- [Project Structure](#project-structure)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Default Test Accounts](#default-test-accounts)
- [Environment Variables](#environment-variables)
- [Core Features](#core-features)
- [Database](#database)
- [Notes](#notes)
- [License](#license)

---

## Description

Starr is a PHP MVC web platform designed for educational communities. It supports four user roles — student, teacher, parent, and admin — each with tailored access and capabilities.

The platform combines lessons, quizzes, community posts, comments, news, moderation reports, and a points/badge reward system. The goal is to create a safe, engaging learning environment with built-in content moderation tools.

- **Students** complete lessons, take quizzes, earn points, and participate in community posts.
- **Teachers** publish lessons, quiz questions, and news articles.
- **Parents** monitor student activity and review content.
- **Admins** manage users, moderate reports, and oversee the entire platform.

---

## Screenshots

> Screenshots and demo assets are located in the `demo/` folder.

---

## Technologies Used

| Layer | Stack |
|---|---|
| Frontend | HTML5, CSS3, JavaScript (ES6+), Bootstrap 5 |
| Backend | PHP 8+ (native MVC, no framework) |
| Database | MySQL 8+ via phpMyAdmin / XAMPP |
| Icons | Font Awesome 6 |

---

## Project Structure

```
Starr/
├── config/
│   └── config.php              # Database connection (PDO)
├── Controller/
│   ├── StarrPointsController.php
│   ├── PointTransactionController.php
│   └── ...                     # One controller per feature
├── Model/
│   ├── StarrPoints.php
│   ├── PointTransaction.php
│   └── ...                     # One model per table
├── View/
│   ├── Front office/           # Student/parent/teacher views
│   │   ├── index.php
│   │   ├── point system/
│   │   └── ...
│   └── Back office/            # Admin views
├── database/
│   └── schema.sql              # Full schema + seed data
├── docs/                       # Technical documentation
├── demo/                       # Screenshots and demo assets
├── .env.example                # Environment variable template
├── .gitignore
└── README.md
```

---

## Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or WAMP / MAMP) with PHP 8+ and MySQL
- Modern web browser (Chrome, Firefox, or Edge)
- Git (optional, for cloning)

Estimated setup time: **5–10 minutes** if XAMPP and MySQL are already installed.

---

## Installation

**1. Clone the repository into your XAMPP web root:**

```bash
git clone https://github.com/TG-o2/Starr.git C:/xampp/htdocs/Starr
```

Or download the ZIP and extract it to `C:\xampp\htdocs\Starr`.

**2. Start Apache and MySQL in XAMPP.**

**3. Create and import the database:**

Option A — via phpMyAdmin (recommended):
- Open http://localhost/phpmyadmin
- Create a new database named `Starr`
- Go to the **Import** tab
- Select `database/schema.sql`
- Click **Go**

Option B — via MySQL CLI:
```bash
mysql -u root -p Starr < database/schema.sql
```

**4. Configure the database connection:**

Copy `.env.example` to `.env` and fill in your credentials (or edit `config/config.php` directly if not using `.env`).

**5. Open the app in your browser:**

```
http://localhost/Starr/View/Front%20office/index.php
```

> If XAMPP is running on a non-default port, append it: `http://localhost:8080/Starr/...`

---

## Default Test Accounts

These accounts are included in the seed data (`schema.sql`):

| Role | Email | Password |
|---|---|---|
| Admin | admin@starr.local | placeholder (update after import) |
| Teacher | teacher@starr.local | placeholder (update after import) |
| Student | student@starr.local | placeholder (update after import) |

> **Important:** Update all passwords immediately after setup. The seed passwords are placeholders and are not secure.

---

## Environment Variables

Copy `.env.example` and rename it to `.env`:

```env
DB_HOST=localhost
DB_NAME=Starr
DB_USER=root
DB_PASS=
DB_PORT=3306
```

> Never commit your real `.env` file. It is listed in `.gitignore`.

---

## Core Features

- **User roles** — student, teacher, parent, admin with role-based access control
- **Lessons** — teacher-created content with difficulty levels, categories, and age ranges
- **Quizzes** — per-lesson questions with scoring, time limits, and explanations
- **Posts & Comments** — community discussion forum with like and reply counts
- **News** — teacher-published articles with categories and comments
- **Reports & Moderation** — user reporting system with admin response workflow
- **Points System** — earn points through lessons, quizzes, and participation
- **Badges** — tiered badge definitions awarded based on point thresholds
- **Transaction History** — full log of point changes per user
- **Login Streaks** — daily login tracking with streak counters

---

## Database

The full schema is in `database/schema.sql`. It includes:

- All table definitions with foreign key constraints
- Seed data for users, lessons, questions, news, posts, points, and badges

Key tables:

| Table | Purpose |
|---|---|
| `user` | All user accounts and roles |
| `lessons` | Lesson content and metadata |
| `questions` | Quiz questions per lesson |
| `STARR_POINTS` | Points and login streak per user |
| `POINT_TRANSACTIONS` | Full history of point changes |
| `BADGE_DEFINITIONS` | Badge tiers and thresholds |
| `USER_BADGES` | Badges earned by each user |
| `posts` / `comments` / `messages` | Community content |
| `report` / `responses` | Moderation workflow |

> If you encounter a foreign key constraint error during testing, make sure the referenced user exists in the `user` table before inserting into `STARR_POINTS` or any related table.

---

## Notes

- Do not commit real `.env` files, API keys, passwords, or generated logs.
- The `demo/` folder is for screenshots and presentation assets only.
- If you hit foreign key errors after resetting the database, re-import `schema.sql` from scratch rather than manually deleting rows — the insertion order matters.
- The `docs/` folder contains architecture, API, database, and deployment references.

---

## License

This project is for educational purposes. All rights reserved © 2025 Starr.
