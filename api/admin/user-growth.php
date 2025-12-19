<?php
header('Content-Type: application/json');

require '../../config/config.php';

try {
    $db = Config::getConnexion();

    // Get total user count
    $totalUsers = (int)$db->query("SELECT COUNT(*) FROM user")->fetchColumn();

    // Since user table doesn't have created_at, simulate growth over last 30 days
    // Distribute users evenly across days as a visual representation
    $userGrowth = [];
    $usersPerDay = max(1, floor($totalUsers / 30));
    $remainder = $totalUsers % 30;

    for ($i = 29; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $count = $usersPerDay;
        
        // Add remainder to more recent days
        if ($i < $remainder) {
            $count++;
        }
        
        $userGrowth[] = [
            'date' => $date,
            'new_users' => $count
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $userGrowth,
        'note' => 'Simulated data: user table lacks timestamp. Shows distribution of ' . $totalUsers . ' users over 30 days.'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
