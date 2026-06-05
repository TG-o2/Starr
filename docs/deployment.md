# Deployment Guide

## Local setup
1. Install XAMPP or another PHP and MySQL stack.
2. Copy the repository into your web root.
3. Create a local `.env` file from `.env.example`.
4. Import `database/schema.sql` into MySQL.
5. Start Apache and MySQL.

## Environment variables
- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `GMAIL_USER`
- `GMAIL_APP_PASSWORD`
- `APP_URL`

## Production reminders
- Never commit real secrets.
- Disable debug output.
- Keep logs outside version control.