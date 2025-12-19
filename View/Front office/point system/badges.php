<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../Controller/StarrPointsController.php';

$db = Config::getConnexion();
$points_controller = new StarrPointsController($db);

// Resolve Starr ID from session first, then GET fallback
$starr_id = null;
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $starr_id = (int)$_SESSION['user_id'];
} elseif (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) { // legacy fallback
    $starr_id = (int)$_SESSION['id'];
} elseif (isset($_GET['starr_id']) && is_numeric($_GET['starr_id'])) {
    $starr_id = (int)$_GET['starr_id'];
}

// Get user points (default to 0 if no record)
$user_points = 0;
if ($starr_id !== null) {
    $user_points_result = $points_controller->getById($starr_id);
    $user_points = ($user_points_result['success'] && isset($user_points_result['data']['total_points']))
        ? (int)$user_points_result['data']['total_points']
        : 0;
}

// Define badge tiers (hardcoded since tables don't exist)
$all_badges = [
    ['badge_id' => 1, 'tier_level' => 'Bronze',   'min_points' => 0,    'icon' => 'fas fa-medal', 'color' => '#CD7F32', 'description' => 'Welcome to Starr! Start your journey.'],
    ['badge_id' => 2, 'tier_level' => 'Silver',   'min_points' => 250,  'icon' => 'fas fa-medal', 'color' => '#C0C0C0', 'description' => 'Making progress! Keep going.'],
    ['badge_id' => 3, 'tier_level' => 'Gold',     'min_points' => 750,  'icon' => 'fas fa-medal', 'color' => '#FFD700', 'description' => 'You\'re a star! Excellent work.'],
    ['badge_id' => 4, 'tier_level' => 'Platinum', 'min_points' => 1500, 'icon' => 'fas fa-crown', 'color' => '#E5E4E2', 'description' => 'Top performer! Amazing dedication.'],
    ['badge_id' => 5, 'tier_level' => 'Diamond',  'min_points' => 3000, 'icon' => 'fas fa-gem',   'color' => '#B9F2FF', 'description' => 'Elite status achieved!'],
];

// Calculate current tier and next badge
$current_badge = null;
$next_badge = null;
foreach ($all_badges as $badge) {
    if ($user_points >= $badge['min_points']) {
        $current_badge = $badge;
    } else if ($next_badge === null) {
        $next_badge = $badge;
        break;
    }
}

$progress_to_next = 100;
if ($next_badge && $current_badge) {
    $den = max(1, ($next_badge['min_points'] - $current_badge['min_points']));
    $progress_to_next = (int)round((($user_points - $current_badge['min_points']) / $den) * 100);
}
// Clamp to 0-100
$progress_to_next = max(0, min(100, $progress_to_next));

// Get top performers
$leaderboard_result = $points_controller->getLeaderboard(5);
$leaderboard = $leaderboard_result['success'] ? $leaderboard_result['data'] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badge System - Starr</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 40px 20px; }
        .main-container { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); max-width: 1200px; margin: 0 auto; }
        
        .badge-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 20px; margin: 30px 0; }
        .badge-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
        }
        .badge-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .badge-card.earned { background: linear-gradient(135deg, rgba(255,215,0,0.1) 0%, rgba(255,215,0,0.05) 100%); border-color: #FFD700; }
        .badge-card.locked { opacity: 0.5; background: #f0f0f0; }
        
        .badge-icon { font-size: 3rem; margin: 10px 0; display: block; }
        .badge-name { font-weight: bold; font-size: 0.95rem; margin: 10px 0; }
        .badge-points { font-size: 0.85rem; color: #666; }
        .earned-badge::after { content: "✓"; position: absolute; top: 8px; right: 8px; background: #28a745; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        
        .user-header { margin-bottom: 40px; border-bottom: 3px solid #667eea; padding-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .user-title { color: #667eea; margin-bottom: 0; }
        
        .nav-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
        .nav-buttons a { padding: 8px 16px; font-size: 0.9rem; }
        
        .progress-section { background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .progress-section h5 { color: #667eea; margin-bottom: 15px; }
        
        .tier-display { text-align: center; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; margin-bottom: 30px; }
        .tier-display .current-rank { font-size: 2rem; font-weight: bold; margin: 10px 0; }
        .tier-display .points-info { font-size: 1.1rem; }
        
        .leaderboard-item { display: flex; align-items: center; padding: 12px 0; border-bottom: 1px solid #eee; }
        .leaderboard-item:last-child { border-bottom: none; }
        .leaderboard-rank { font-weight: bold; color: #667eea; min-width: 30px; }
        .leaderboard-badge { font-size: 1.5rem; margin: 0 12px; }
        .leaderboard-info { flex: 1; }
        .leaderboard-points { font-weight: bold; color: #764ba2; }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header with Navigation -->
        <div class="user-header">
            <div>
                <h1 class="user-title"><i class="fas fa-medal"></i> Badge System</h1>
                <p class="text-muted mb-0">View badges, progress, and top performers</p>
            </div>
            <div class="nav-buttons">
                <a href="my-points.php" class="btn btn-outline-secondary btn-sm">My Points</a>
                <a href="../index.html" class="btn btn-outline-secondary btn-sm">Home</a>
            </div>
        </div>

        <!-- Current Tier Display -->
        <div class="tier-display">
            <div class="current-rank">
                <?php if ($current_badge): ?>
                    <i class="<?php echo $current_badge['icon']; ?>"></i>
                    <?php echo htmlspecialchars($current_badge['tier_level']); ?>
                <?php else: ?>
                    <i class="fas fa-star-half-alt"></i> No Badge Yet
                <?php endif; ?>
            </div>
            <div class="points-info">
                <?php echo number_format($user_points); ?> Points
            </div>
        </div>

        <!-- Progress to Next Badge -->
        <?php if ($next_badge): ?>
        <div class="progress-section">
            <h5>Progress to Next Tier</h5>
            <p class="text-muted mb-2">
                <?php echo $next_badge['tier_level']; ?> Badge 
                (<?php echo number_format($next_badge['min_points']); ?> points needed)
            </p>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar bg-success" style="width: <?php echo $progress_to_next; ?>%">
                    <?php echo $progress_to_next; ?>%
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- All Badges Display -->
        <h3 style="color: #667eea; margin-top: 40px; margin-bottom: 20px;">All Badges</h3>
        <div class="badge-grid">
            <?php foreach ($all_badges as $badge): ?>
                <?php 
                    $is_earned = $user_points >= $badge['min_points'];
                    $earned_class = $is_earned ? 'earned earned-badge' : 'locked';
                ?>
                <div class="badge-card <?php echo $earned_class; ?>" title="<?php echo htmlspecialchars($badge['description']); ?>">
                    <i class="badge-icon <?php echo $badge['icon']; ?>" style="color: <?php echo $badge['color']; ?>;"></i>
                    <div class="badge-name"><?php echo htmlspecialchars($badge['tier_level']); ?></div>
                    <div class="badge-points"><?php echo number_format($badge['min_points']); ?> pts</div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Top Users Leaderboard -->
        <div style="margin-top: 50px;">
            <h3 style="color: #667eea; margin-bottom: 20px;">🏆 Top Performers</h3>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                <?php if (!empty($leaderboard)): ?>
                    <?php 
                    $rank = 1; 
                    foreach ($leaderboard as $user): 
                        // Determine badge for this user
                        $user_badge = null;
                        foreach (array_reverse($all_badges) as $badge) {
                            if ($user['total_points'] >= $badge['min_points']) {
                                $user_badge = $badge;
                                break;
                            }
                        }
                    ?>
                    <div class="leaderboard-item">
                        <span class="leaderboard-rank">#<?php echo $rank++; ?></span>
                        <?php if ($user_badge): ?>
                            <span class="leaderboard-badge">
                                <i class="<?php echo $user_badge['icon']; ?>" style="color: <?php echo $user_badge['color']; ?>;"></i>
                            </span>
                        <?php endif; ?>
                        <div class="leaderboard-info">
                            <strong>User #<?php echo htmlspecialchars($user['starr_id']); ?></strong>
                            <?php if ($user_badge): ?>
                                <span style="color: #666; font-size: 0.9rem;">- <?php echo htmlspecialchars($user_badge['tier_level']); ?></span>
                            <?php endif; ?>
                        </div>
                        <span class="leaderboard-points"><?php echo number_format($user['total_points']); ?> pts</span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No leaderboard data available.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Usage Info -->
        <div style="background: #e7f3ff; border-left: 4px solid #667eea; padding: 15px; margin-top: 40px; border-radius: 5px;">
            <strong>💡 How it works:</strong> Earn points through activities and automatically unlock badges as you reach each tier. 
        
        </div>
    </div>
</body>
</html>
