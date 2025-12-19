<?php
class BadgeHelper {
    // Approved thresholds
    private static array $tiers = [
        ['name' => 'Diamond',  'min' => 3000, 'icon' => 'fas fa-gem',   'color' => '#B9F2FF'],
        ['name' => 'Platinum', 'min' => 1500, 'icon' => 'fas fa-crown', 'color' => '#E5E4E2'],
        ['name' => 'Gold',     'min' => 750,  'icon' => 'fas fa-medal', 'color' => '#FFD700'],
        ['name' => 'Silver',   'min' => 250,  'icon' => 'fas fa-medal', 'color' => '#C0C0C0'],
        ['name' => 'Bronze',   'min' => 0,    'icon' => 'fas fa-medal', 'color' => '#CD7F32'],
    ];

    public static function getBadgeForPoints(int $points): array {
        foreach (self::$tiers as $tier) {
            if ($points >= $tier['min']) {
                return $tier;
            }
        }
        return ['name' => 'Bronze', 'min' => 0, 'icon' => 'fas fa-medal', 'color' => '#CD7F32'];
    }

    public static function renderBadge(int $points, string $extraClass = ''): string {
        $tier = self::getBadgeForPoints($points);
        $name = htmlspecialchars($tier['name']);
        $icon = htmlspecialchars($tier['icon']);
        $color = htmlspecialchars($tier['color']);
        $cls = trim('badge-tier ' . $extraClass);
        return '<span class="' . $cls . '" title="' . $name . ' badge">'
             . '<i class="' . $icon . '" style="color:' . $color . ';"></i>'
             . '<span class="badge-tier-label">' . $name . '</span>'
             . '</span>';
    }
}
?>