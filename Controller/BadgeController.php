<?php

require_once __DIR__ . '/../Model/Badge.php';
require_once __DIR__ . '/../Model/StarrPoints.php';
require_once __DIR__ . '/../config/config.php';

class BadgeController {
    private $badge_model;
    private $points_model;
    private $conn;

    public function __construct($db) {
        $this->badge_model = new Badge($db);
        $this->points_model = new StarrPoints($db);
        $this->conn = $db;
    }

    // Get all badges
    public function getAllBadges() {
        $badges = $this->badge_model->getAllBadges();
        return [
            'success' => true,
            'data' => $badges,
            'count' => count($badges)
        ];
    }

    // Get user's earned badges
    public function getUserBadges($starr_id) {
        $badges = $this->badge_model->getUserBadges($starr_id);
        return [
            'success' => true,
            'data' => $badges,
            'count' => count($badges)
        ];
    }

    // Get user's current tier based on points
    public function getUserTier($starr_id) {
        $user_data = $this->points_model->getByStarrId($starr_id);
        
        if (!$user_data) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        $current_badge = $this->badge_model->getAchievableBadges($user_data['total_points']);
        $next_badge = $this->badge_model->getNextBadge($user_data['total_points']);
        
        return [
            'success' => true,
            'total_points' => $user_data['total_points'],
            'current_badge' => $current_badge,
            'next_badge' => $next_badge,
            'progress_to_next' => $next_badge ? 
                round((($user_data['total_points'] - ($current_badge['min_points'] ?? 0)) / 
                       ($next_badge['min_points'] - ($current_badge['min_points'] ?? 0))) * 100) : 100
        ];
    }

    // Award badges to user based on points (called after points update)
    public function checkAndAwardBadges($starr_id) {
        $user_data = $this->points_model->getByStarrId($starr_id);
        
        if (!$user_data) {
            return ['success' => false, 'message' => 'User not found'];
        }

        $all_badges = $this->badge_model->getAllBadges();
        $awarded_count = 0;

        foreach ($all_badges as $badge) {
            if ($user_data['total_points'] >= $badge['min_points']) {
                if ($this->badge_model->awardBadge($starr_id, $badge['badge_id'])) {
                    $awarded_count++;
                }
            }
        }

        return [
            'success' => true,
            'badges_awarded' => $awarded_count,
            'message' => $awarded_count > 0 ? "Awarded {$awarded_count} badge(s)!" : "No new badges earned"
        ];
    }

    // Get leaderboard with badges
    public function getLeaderboardWithBadges($limit = 10) {
        $query = "SELECT sp.starr_id, sp.total_points, sp.login_streak, 
                         b.badge_name, b.icon, b.color, b.tier_level
                  FROM STARR_POINTS sp
                  LEFT JOIN (
                      SELECT ub.starr_id, bd.badge_name, bd.icon, bd.color, bd.tier_level
                      FROM USER_BADGES ub
                      INNER JOIN BADGE_DEFINITIONS bd ON ub.badge_id = bd.badge_id
                      WHERE ub.is_active = 1
                      ORDER BY bd.min_points DESC
                  ) b ON sp.starr_id = b.starr_id
                  ORDER BY sp.total_points DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Error fetching leaderboard'
        ];
    }
}
?>
