<?php
session_start();
require_once __DIR__ . '/../../../Controller/ReportController.php';

if (!isset($_SESSION['user_id'])) {
    // Not logged in; return empty list safely
    exit;
}
$userId = $_SESSION['user_id'];

// Inputs
$type    = $_GET['type']    ?? '';
$status  = $_GET['status']  ?? '';
$search  = $_GET['search']  ?? '';
// Support both param names: selected (JS) and selectedId (legacy)
$selectedId = $_GET['selected'] ?? ($_GET['selectedId'] ?? null);

$reportController = new ReportController();
$reports = $reportController->getReportsByReporter($userId);

foreach ($reports as $r) {

    // Filter by type
    if ($type !== '' && $r->getReportType() != $type) {
        continue;
    }

    // Filter by status
    if ($status !== '' && $r->getReportStatus() != $status) {
        continue;
    }

    // Filter by search text (in reason or type)
    if ($search !== '') {
        $inReason = stripos($r->getReportReason(), $search) !== false;
        $inType   = stripos($r->getReportType(), $search) !== false;

        if (!$inReason && !$inType) {
            continue;
        }
    }

    // Active (orange) item
    $active = ($selectedId == $r->getReportId()) ? 'active' : '';

    // Output HTML for each result
    echo '
        <a href="?reportId=' . $r->getReportId() . '" 
           class="list-group-item list-group-item-action ' . $active . '">
           
            <strong>' . htmlspecialchars($r->getReportType()) . '</strong><br>
            <small>' . htmlspecialchars($r->getReportDate()) . '</small><br>
            <span class="badge badge-custom">' . htmlspecialchars($r->getReportStatus()) . '</span>
        </a>
    ';
}
?>
