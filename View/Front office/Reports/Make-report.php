<?php
session_start();

require_once __DIR__ . '/../../../Controller/ReportController.php';
require_once __DIR__ . '/../../../Model/Report.php';



$reportController = new ReportController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report = new Report();
    $report->setReportType($_POST['reportType']);
    $report->setReportReason($_POST['report-reason']);
    $report->setReportDescription($_POST['report-description']);
    $report->setReportDate(date('Y-m-d H:i:s'));
    //$report->setReporterId($_SESSION['user_id']); 
    $report->setReporterId("0047"); 



    // Optional: set defaults for other fields 
    $report->setReportStatus('Pending');
    $report->setReportedUserId($_POST['reported-user-id'] ?? null);
    $report->setReportedPostId($_POST['reported-post-id'] ?? null);

    // Save report
    $reportController->addReport($report);

    echo "Report submitted successfully!";
}
?>
