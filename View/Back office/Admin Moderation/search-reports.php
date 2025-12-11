<?php
require_once __DIR__ . '/../../../Controller/ReportController.php';

$reportController = new ReportController();


$search = $_GET['q'] ?? "";

// If the query looks like a reportId (numeric), try exact lookup first
if (strlen(trim($search)) > 0 && ctype_digit(trim($search))) {
    $single = $reportController->getReportById($search);
    if ($single) {
        $reports = [[
            'reportId' => $single->getReportId(),
            'reportType' => $single->getReportType(),
            'reportedUserId' => $single->getReportedUserId(),
            'reportedPostId' => $single->getReportedPostId(),
            'reportedCommentId' => $single->getReportedCommentId(),
            'reportedLessonId' => $single->getReportedLessonId(),
            'reportReason' => $single->getReportReason(),
            'reportDescription' => $single->getReportDescription(),
            'reportDate' => $single->getReportDate(),
            'reporterId' => $single->getReporterId(),
            'reportStatus' => $single->getReportStatus(),
            'evidencePath' => $single->getEvidencePath(),
            'severity' => $single->getSeverity()
        ]];
    } else {
        $reports = [];
    }
} else {
    $reports = $reportController->filterReports($search, null);
}

// Return HTML (not JSON) to directly inject into the DOM
foreach ($reports as $r): ?>

<div class="list-group-item list-group-item-action flex-column align-items-start mb-3 border-left-danger shadow-sm">
    <div class="d-flex w-100 justify-content-between align-items-center">
        <h6 class="mb-1 text-danger font-weight-bold">
            <i class="fas fa-user-shield mr-1"></i>
            Report by: @<?= $r['reporterId'] ?>
        </h6>
        <span class="badge badge-secondary"><?= $r['reportStatus'] ?></span>
    </div>

    <p class="small mb-1"><strong>Reason:</strong> <?= $r['reportReason'] ?></p>
    <p class="small mb-1"><strong>Date:</strong> <?= $r['reportDate'] ?></p>

    <a class="btn btn-sm btn-primary mt-2" href="Handle-report.php?reportId=<?= $r['reportId'] ?>">
        Handle report →
    </a>
</div>

<?php endforeach;

if (empty($reports)) {
    echo "<p class='text-muted'>No matching reports.</p>";
}
?>
