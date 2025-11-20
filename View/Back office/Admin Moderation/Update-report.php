<?php
require_once __DIR__ . '/../../../Controller/ReportController.php';
require_once __DIR__ . '/../../../Model/Report.php';

if (isset($_GET['id'])) {
    $controller = new ReportController();
    $report = $controller->getReportById($_GET['id']); 
    $report->setReportStatus("In Progress");
    $controller->updateReport($report, $_GET['id']);
}

header("Location: Review-list.php?updated=in-progress");
exit();
