<?php
session_start();

require_once __DIR__ . '/../../../Controller/ReportController.php';
require_once __DIR__ . '/../../../Model/Report.php';

$reportController = new ReportController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type = $_POST['reportType'];

    $userId    = $_POST['reported-user-id'] ?? null;
    $postId    = $_POST['reported-post-id'] ?? null;
    $commentId = $_POST['reported-comment-id'] ?? null;
    $lessonId  = $_POST['reported-lesson-id'] ?? null;

    $valid = false;

    switch ($type) {
        case "user":
            $valid = !empty($userId);   
            break;

        case "post":
            $valid = !empty($postId);   
            break;

        case "comment":
            $valid = !empty($commentId);
            break;

        case "lesson":
            $valid = !empty($lessonId);
            break;
    }

    if (!$valid) {
        die("Invalid report submission: ID does not match report type.");
    }

    $report = new Report();
    $report->setReportType($type);
    $report->setReportReason($_POST['report-reason']);
    $report->setReportDescription($_POST['report-description']);
    $report->setReportDate(date('Y-m-d H:i:s'));
    $report->setReporterId("0047");

   
    $report->setReportedUserId($userId);
    $report->setReportedPostId($postId);
    $report->setReportedCommentId($commentId);
    $report->setReportedLessonId($lessonId);

    $report->setReportStatus('Pending');

    $reportController->addReport($report);

    echo "Report submitted successfully!";
}
?>
