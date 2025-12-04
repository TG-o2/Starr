<?php
session_start();
require_once __DIR__ . '/../../../Controller/ReportController.php';

$userId = $_SESSION['id'] ?? 5;

$type = $_GET['type'] ?? '';
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

$reportController = new ReportController();
$reports = $reportController->getReportsByReporter($userId);

foreach ($reports as $r) {
    // Filter by type
    if ($type && $r->getReportType() != $type) continue;
    
    // Filter by status
    if ($status && $r->getReportStatus() != $status) continue;
    
    // Filter by search text (in reason or type)
    if ($search && stripos($r->getReportReason(), $search) === false 
                && stripos($r->getReportType(), $search) === false) continue;

    $active = (isset($_GET['selectedId']) && $_GET['selectedId'] == $r->getReportId()) ? 'active' : '';
    echo '<a href="?reportId=' . $r->getReportId() . '" class="list-group-item list-group-item-action ' . $active . '">
            <strong>' . htmlspecialchars($r->getReportType()) . '</strong><br>
            <small>' . htmlspecialchars($r->getReportDate()) . '</small><br>
            <span class="badge badge-custom">' . htmlspecialchars($r->getReportStatus()) . '</span>
          </a>';
}
?>
