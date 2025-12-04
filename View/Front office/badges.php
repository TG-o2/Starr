<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Controller/BadgeController.php';
require_once __DIR__ . '/../../Controller/StarrPointsController.php';

$db = Config::getConnexion();
$badge_controller = new BadgeController($db);
$points_controller = new StarrPointsController($db);

// Get user ID from URL parameter
$starr_id = isset($_GET['starr_id']) ? (int)$_GET['starr_id'] : 1;

// Get all badges and user info
$all_badges = $badge_controller->getAllBadges();
$user_tier = $badge_controller->getUserTier($starr_id);
$user_badges = $badge_controller->getUserBadges($starr_id);
$leaderboard = $badge_controller->getLeaderboardWithBadges(5);
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
        .container { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        
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
        
        .user-header { margin-bottom: 40px; border-bottom: 3px solid #667eea; padding-bottom: 20px; }
        .user-title { color: #667eea; margin-bottom: 15px; }
        
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
    <div class="container">
        <!-- Header -->
        <div class="user-header">
            <h1 class="user-title"><i class="fas fa-user-circle"></i> Badge System</h1>
            <p class="text-muted">View badges, progress, and top performers</p>
        </div>

        <?php if ($user_tier['success']): ?>
        <!-- Current Tier Display -->
        <div class="tier-display">
            <div class="current-rank">
                <?php if ($user_tier['current_badge']): ?>
                    <i class="<?php echo $user_tier['current_badge']['icon']; ?>"></i>
                    <?php echo htmlspecialchars($user_tier['current_badge']['tier_level']); ?>
                <?php else: ?>
                    <i class="fas fa-star-half-alt"></i> No Badge Yet
                <?php endif; ?>
            </div>
            <div class="points-info">
                <?php echo htmlspecialchars($user_tier['total_points']); ?> Points
            </div>
        </div>

        <!-- Progress to Next Badge -->
        <?php if ($user_tier['next_badge']): ?>
        <div class="progress-section">
            <h5>Progress to Next Tier</h5>
            <p class="text-muted mb-2">
                <?php echo $user_tier['next_badge']['tier_level']; ?> Badge 
                (<?php echo $user_tier['next_badge']['min_points']; ?> points needed)
            </p>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar bg-success" style="width: <?php echo $user_tier['progress_to_next']; ?>%">
                    <?php echo $user_tier['progress_to_next']; ?>%
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <!-- All Badges Display -->
        <h3 style="color: #667eea; margin-top: 40px; margin-bottom: 20px;">All Badges</h3>
        <div class="badge-grid">
            <?php foreach ($all_badges['data'] as $badge): ?>
                <?php 
                    $is_earned = in_array($badge['badge_id'], array_column($user_badges['data'], 'badge_id'));
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
                <?php if ($leaderboard['success']): ?>
                    <?php $rank = 1; ?>
                    <?php foreach ($leaderboard['data'] as $user): ?>
                    <div class="leaderboard-item">
                        <span class="leaderboard-rank">#<?php echo $rank++; ?></span>
                        <?php if ($user['icon']): ?>
                            <span class="leaderboard-badge">
                                <i class="<?php echo $user['icon']; ?>" style="color: <?php echo $user['color']; ?>;"></i>
                            </span>
                        <?php endif; ?>
                        <div class="leaderboard-info">
                            <strong>User #<?php echo htmlspecialchars($user['starr_id']); ?></strong>
                            <?php if ($user['badge_name']): ?>
                                <span style="color: #666; font-size: 0.9rem;">- <?php echo htmlspecialchars($user['badge_name']); ?></span>
                            <?php endif; ?>
                        </div>
                        <span class="leaderboard-points"><?php echo number_format($user['total_points']); ?> pts</span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Usage Info -->
        <div style="background: #e7f3ff; border-left: 4px solid #667eea; padding: 15px; margin-top: 40px; border-radius: 5px;">
            <strong>💡 How it works:</strong> Earn points through activities and automatically unlock badges as you reach each tier. 
            View <code>?starr_id=2</code> in the URL to check different users' badges.
        </div>
    </div>
</body>
</html>
