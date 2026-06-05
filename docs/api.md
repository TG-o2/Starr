# API Reference

The project exposes a small set of PHP endpoints under `api/`.

## Existing endpoints
- `api/track_view.php` records content views.
- `api/admin/metrics.php` returns admin metrics.
- `api/admin/top_content.php` returns popular content.
- `api/admin/badge-distribution.php` summarizes badge tiers.
- `api/admin/recent-activity.php` shows recent actions.
- `api/admin/quick-stats.php` provides dashboard statistics.

## Common payload patterns
- JSON responses for dashboard and analytics endpoints.
- Form submissions for controller-driven CRUD actions.
- Database lookups through shared config and models.

## Maintenance notes
- Keep response formats stable once front-end code depends on them.
- Document any new endpoint here together with its request and response shape.