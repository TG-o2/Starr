<?php
header('Content-Type: application/json');

require '../../config/config.php';

try {
    $db = Config::getConnexion();

    // Get open reports with reporter and reported user info
    $reports = $db->query("
        SELECT 
            r.reportId,
            LPAD(r.reportId, 3, '0') as report_id_padded,
            r.reportReason,
            r.reportDescription,
            r.severity,
            r.reportDate,
            r.reportType,
            ru.fname as reporter_name,
            COALESCE(mu.fname, 'Unknown') as reported_user_name
        FROM report r
        LEFT JOIN user ru ON r.reporterId = ru.user_id
        LEFT JOIN user mu ON r.reportedUserId = mu.user_id
        WHERE r.reportStatus = 'pending'
        ORDER BY 
            CASE 
                WHEN r.severity = 'critical' THEN 1
                WHEN r.severity = 'warning' THEN 2
                WHEN r.severity = 'normal' THEN 3
                ELSE 4
            END ASC,
            r.reportDate DESC
        LIMIT 15
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Add severity badge color
    foreach ($reports as &$report) {
        switch ($report['severity']) {
            case 'critical':
                $report['badge_color'] = 'danger';
                $report['badge_icon'] = 'exclamation-circle';
                break;
            case 'warning':
                $report['badge_color'] = 'warning';
                $report['badge_icon'] = 'exclamation-triangle';
                break;
            default:
                $report['badge_color'] = 'info';
                $report['badge_icon'] = 'flag';
        }
    }

    echo json_encode([
        'success' => true,
        'data' => $reports,
        'count' => count($reports)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
