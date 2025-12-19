<?php
header('Content-Type: application/json');

require '../../config/config.php';

try {
    $db = Config::getConnexion();

    // Get badge distribution - count users at each badge tier
    $badgeStats = $db->query("
        SELECT 
            CASE 
                WHEN sp.total_points >= 3000 THEN 'Diamond'
                WHEN sp.total_points >= 1500 THEN 'Platinum'
                WHEN sp.total_points >= 750 THEN 'Gold'
                WHEN sp.total_points >= 250 THEN 'Silver'
                ELSE 'Bronze'
            END as badge_tier,
            COUNT(*) as user_count
        FROM STARR_POINTS sp
        GROUP BY badge_tier
        ORDER BY 
            CASE 
                WHEN sp.total_points >= 3000 THEN 5
                WHEN sp.total_points >= 1500 THEN 4
                WHEN sp.total_points >= 750 THEN 3
                WHEN sp.total_points >= 250 THEN 2
                ELSE 1
            END DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $badgeStats
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
