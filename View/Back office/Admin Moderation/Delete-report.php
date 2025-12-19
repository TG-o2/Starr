<?php
require_once __DIR__ . '/../../../Controller/ReportController.php';

if (isset($_GET['id'])) {
    $controller = new ReportController();
    $controller->deleteReport($_GET['id']);
}

// Redirect back to dashboard after deleting
header("Location: Review-list.php?deleted=1");
exit();
