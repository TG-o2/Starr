# Database Notes

The canonical schema is defined in `database/schema.sql`.

## Core tables
- `user`
- `lessons`
- `questions`
- `news`
- `posts`
- `comments`
- `messages`
- `report`
- `responses`
- `STARR_POINTS`
- `POINT_TRANSACTIONS`
- `BADGE_DEFINITIONS`
- `USER_BADGES`
- `points_history`
- `quiz_attempts`
- `content_views`

## Seed data
The schema file includes sample rows so the app can be demonstrated quickly after import.

## Notes
- Keep schema changes versioned in `database/schema.sql`.
- If you add migrations later, document the process here.