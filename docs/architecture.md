# Architecture Overview

Starr follows a lightweight MVC structure.

## Main layers
- `Controller/` contains the request handlers and form actions.
- `Model/` contains database-backed domain logic.
- `View/` contains front office and back office templates.
- `config/` contains shared database and mail configuration.

## Key flows
- A browser request reaches a controller or view.
- Controllers call models for data access.
- Models use `Config::getConnexion()` for database access.
- Views render the data returned by controllers and models.

## Core domains
- Users and authentication
- Lessons and questions
- Posts, comments, and messages
- News content
- Reports and moderation responses
- Points, transactions, and badges

## Notes
- Keep sensitive values out of the repository.
- Add new feature documentation here when the project grows.