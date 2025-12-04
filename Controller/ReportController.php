<?php
session_start();

// Include 
require_once 'config.php';
require_once 'Report.php';
require_once 'Controller.php';

// testing data
$_SESSION['user_id'] = 1;  // pretend reporter user
$_POST['reportType'] = 'user';
$_POST['reportedUserId'] = 2;
$_POST['reportedPostId'] = null;
$_POST['reportedCommentId'] = null;
$_POST['reportedLessonId'] = null;
$_POST['reportReason'] = 'spam';
$_POST['reportDescription'] = 'This user is posting spam links.';

// Create Report object
$report = new Report();
$report->setReporterId($_SESSION['user_id']);
$report->setReportType($_POST['reportType']);
$report->setReportedUserId($_POST['reportedUserId'] ?? null);
$report->setReportedPostId($_POST['reportedPostId'] ?? null);
$report->setReportedCommentId($_POST['reportedCommentId'] ?? null);
$report->setReportedLessonId($_POST['reportedLessonId'] ?? null);
$report->setReportReason($_POST['reportReason']);
$report->setReportDescription($_POST['reportDescription']);
$report->setReportDate(date('Y-m-d H:i:s'));

// Display report info
echo "<h2>Report Info</h2>";
echo "Reporter ID: " . $report->getReporterId() . "<br>";
echo "Report Type: " . $report->getReportType() . "<br>";
echo "Reported User ID: " . $report->getReportedUserId() . "<br>";
echo "Reported Post ID: " . $report->getReportedPostId() . "<br>";
echo "Reported Comment ID: " . $report->getReportedCommentId() . "<br>";
echo "Reported Lesson ID: " . $report->getReportedLessonId() . "<br>";
echo "Report Reason: " . $report->getReportReason() . "<br>";
echo "Report Description: " . $report->getReportDescription() . "<br>";
echo "Report Date: " . $report->getReportDate() . "<br>";

// Or, if you just want to see everything quickly:
echo "<pre>";
var_dump($report);
echo "</pre>";
?>
