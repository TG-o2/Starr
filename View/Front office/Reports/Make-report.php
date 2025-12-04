<?php
session_start();

require_once __DIR__ . '/../../../Controller/ReportController.php';
require_once __DIR__ . '/../../../Model/Report.php';
require_once __DIR__ . '/../../../config/config.php'; // for DB connection

$reportController = new ReportController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ------------------
    // Get form data
    // ------------------
    $type = $_POST['reportType'];

    $userId    = $_POST['reported-user-id'] ?? null;
    $postId    = $_POST['reported-post-id'] ?? null;
    $commentId = $_POST['reported-comment-id'] ?? null;
    $lessonId  = $_POST['reported-lesson-id'] ?? null;

    // ------------------
    // Validate report type
    // ------------------
    $valid = false;
    switch ($type) {
        case "user":    $valid = !empty($userId); break;
        case "post":    $valid = !empty($postId); break;
        case "comment": $valid = !empty($commentId); break;
        case "lesson":  $valid = !empty($lessonId); break;
    }

    if (!$valid) {
        die("Invalid report submission: ID does not match report type.");
    }

    // ------------------
    // Create Report object
    // ------------------
    $report = new Report();
    $report->setReportType($type);
    $report->setReportReason($_POST['report-reason']);
    $report->setReportDescription($_POST['report-description']);
    $report->setReportDate(date('Y-m-d H:i:s'));
    $report->setReporterId("5"); // static for now
    $report->setReportedUserId($userId);
    $report->setReportedPostId($postId);
    $report->setReportedCommentId($commentId);
    $report->setReportedLessonId($lessonId);
    $report->setReportStatus('Pending');

    // ------------------
    // Save Report
    // ------------------
    $newReportId = $reportController->addReport($report);

    if (!$newReportId) {
        die("Failed to create report.");
    }

    // ------------------
    // AUTO-SEVERITY LOGIC
    // ------------------
    $db = Config::getConnexion();

    // Determine target column and value
    switch ($type) {
    case "user":
        $targetField = "reportedUserId";
        $targetValue = $userId;
        break;
    case "post":
        $targetField = "reportedPostId";
        $targetValue = $postId;
        break;
    case "comment":
        $targetField = "reportedCommentId";
        $targetValue = $commentId;
        break;
    case "lesson":
        $targetField = "reportedLessonId";
        $targetValue = $lessonId;
        break;
}


    // Count existing reports for this target
    $countStmt = $db->prepare("SELECT COUNT(*) FROM Report WHERE $targetField = ?");
    $countStmt->execute([$targetValue]);
    $reportCount = $reportController->countReportsForEntity(
    $userId,
    $postId,
    $commentId,
    $lessonId,
    $type
);

    // Severity thresholds
    $warningLimit = 3;
    $criticalLimit = 5;
    $severity = 'normal';

    if ($reportCount >= $criticalLimit) {
        $severity = 'critical';
    } elseif ($reportCount >= $warningLimit) {
        $severity = 'warning';
    }
    // Update the report
$reportController->updateReportSeverity($newReportId, $severity);

    // Update report with severity
    $updateStmt = $db->prepare("UPDATE Report SET severity = ? WHERE reportId = ?");
    $updateStmt->execute([$severity, $newReportId]);

    echo "Report submitted successfully! Severity: $severity";
}
?>
