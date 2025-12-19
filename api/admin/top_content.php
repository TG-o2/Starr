<?php
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $db = Config::getConnexion();
    // Ensure tracking table
    $db->exec("CREATE TABLE IF NOT EXISTS content_views (
        id INT AUTO_INCREMENT PRIMARY KEY,
        content_type VARCHAR(32) NOT NULL,
        content_id INT NOT NULL,
        user_id INT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_type_id (content_type, content_id),
        INDEX idx_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Top news by views (last 30 days)
    $news = [];
    $sqlNews = "SELECT n.newsid, n.title, COUNT(v.id) AS views
                FROM content_views v
                JOIN news n ON n.newsid = v.content_id
                WHERE v.content_type = 'news' AND v.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY n.newsid, n.title
                ORDER BY views DESC
                LIMIT 10";
    $stmtNews = $db->query($sqlNews);
    $news = $stmtNews->fetchAll(PDO::FETCH_ASSOC);

    // Top lessons by views (last 30 days) — if lessons table is unknown, return id+count only
    $lessons = [];
    $sqlLessons = "SELECT v.content_id AS lessonId, COUNT(v.id) AS views
                   FROM content_views v
                   WHERE v.content_type = 'lesson' AND v.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                   GROUP BY v.content_id
                   ORDER BY views DESC
                   LIMIT 10";
    $stmtLessons = $db->query($sqlLessons);
    $lessons = $stmtLessons->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['ok' => true, 'news' => $news, 'lessons' => $lessons]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
