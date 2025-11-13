News — simple starter pages

Files:
- `list-news.html` — a simple list of articles with links.
- `news-details.html` — an article details page that reads `?id=` and shows placeholder content.
- `css/styles.css` — minimal styling.

How to use:
1. Open `News/list-news.html` in a browser (double-click or open from your editor).
2. Click any article title to open `news-details.html?id=1` (client-only demo).

Next steps:
- Replace the client-side `articles` map with real data fetches from an API.
- Add templating or a backend to render article content server-side.
Integration notes (front-office / MVC)
- The pages in this folder are now integrated visually with the Kider front-office template (header, footer, fonts, and styles).
- To follow MVC: move the HTML in `list-news.html` and `news-details.html` into view templates (for example `views/list.html`, `views/details.html`) and implement controllers in `controllers/` to provide article data (from `models/` or an API).

Recommended next steps for the team/GitHub dashboard
- Add GitHub tasks for: converting pages to templates, implementing a tiny Express server (or static site generator), and wiring an articles data source (JSON or DB).
- If you want, I can scaffold a minimal Node/Express app that serves the views and provides a JSON API for articles.
