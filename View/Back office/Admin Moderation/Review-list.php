<?php
require_once __DIR__ . '/../../../Controller/ReportController.php';
require_once __DIR__ . '/../../../Model/Report.php';


require_once __DIR__ . '/../../../Controller/ResponseController.php';
require_once __DIR__ . '/../../../Model/Response.php';



$reportController = new ReportController();
$reports = $reportController->getAllReports();

$responseController = new ResponseController();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$adminId= "3";


?>


<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>View Report</title>


<!-- Custom fonts -->
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

<!-- Custom styles -->
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<style>
    /* Fix typo */
    .chart-area { height: auto !important; min-height: 60px; }
    #review-list { max-height: none; overflow: visible; }
</style>

<script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>


</head>
<body id="page-top">

<div id="wrapper">


<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../Admin Dashboard/Dashboard.html">
        <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laugh-wink"></i></div>
        <div class="sidebar-brand-text mx-3">Admin Starr<sup>*</sup></div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item active"><a class="nav-link"><span>Report list</span></a></li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Work</div>
    <li class="nav-item"><a class="nav-link" href="View-profiles.html"><i class="fas fa-fw fa-user"></i><span>View Profiles</span></a></li>
    <li class="nav-item"><a class="nav-link" href="Handle-report.php"><i class="fa-solid fa-flag"></i><span>Handle Reports</span></a></li>
    <li class="nav-item"><a class="nav-link" href="../Admin Dashboard/Dashboard.html"><i class="fas fa-fw fa-tachometer-alt"></i><span>Admin Dashboard</span></a></li>
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
            <ul class="navbar-nav ml-auto"><div class="topbar-divider d-none d-sm-block"></div></ul>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Report List</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <div class="list-group" id="review-list">
                                    <?php if (!empty($reports)): ?>
                                        <?php foreach ($reports as $r): ?>
                                            

                                            <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 border-left-danger shadow-sm">
                                                <div class="d-flex w-100 justify-content-between align-items-center">
                                                    <h6 class="mb-1 text-danger font-weight-bold">
                                                        <i class="fas fa-user-shield mr-1"></i>
                                                        Report by: <span class="text-dark">@<?= $r['reporterId'] ?></span>
                                                    </h6>
                                                    <?php
                                                        $statusClass = match($r['reportStatus']) {
                                                            "Pending" => "badge-danger",
                                                            "Completed" => "badge-success",
                                                            "In Progress" => "badge-primary",
                                                            default => "badge-secondary",
                                                        };
                                                    ?>
                                                    <span class="badge <?= $statusClass ?> p-2"><?= $r['reportStatus'] ?></span>
                                                </div>

                                                <?php if ($r['reportedUserId']): ?>
                                                    <p class="small mb-1"><strong>Reported User:</strong> <?= $r['reportedUserId'] ?></p>
                                                <?php endif; ?>
                                                <?php if ($r['reportedPostId']): ?>
                                                    <p class="small mb-1"><strong>Reported Post ID:</strong> <?= $r['reportedPostId'] ?></p>
                                                <?php endif; ?>
                                                <?php if ($r['reportedCommentId']): ?>
                                                    <p class="small mb-1"><strong>Reported Comment ID:</strong> <?= $r['reportedCommentId'] ?></p>
                                                <?php endif; ?>
                                                <?php if ($r['reportedLessonId']): ?>
                                                    <p class="small mb-1"><strong>Reported Lesson ID:</strong> <?= $r['reportedLessonId'] ?></p>
                                                <?php endif; ?>

                                                <p class="small mb-1"><strong>Reason:</strong> <?= $r['reportReason'] ?></p>
                                                <p class="small mb-1"><strong>Date:</strong> <?= $r['reportDate'] ?></p>
                                                <?php if ($r['reportDescription']): ?>
                                                    <p class="small text-muted mb-2"><strong>Description:</strong> <?= $r['reportDescription'] ?></p>
                                                <?php endif; ?>
                                                <?php if ($r['evidencePath']): ?>
                                                    <p class="small mb-2"><strong>Evidence:</strong> <a href="<?= $r['evidencePath'] ?>" target="_blank">View File</a></p>
                                                <?php endif; ?>

                                                <div class="mt-2 btn-row">
                                                    <?php if ($r['reportStatus'] === 'Pending'): ?>
                                                        <button type="button" class="btn btn-sm btn-success mr-2" onclick="window.location.href='Update-report.php?id=<?= $r['reportId'] ?>'">
                                                            <i class="fas fa-check mr-1"></i>Approve
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-secondary" onclick="window.location.href='Delete-report.php?id=<?= $r['reportId'] ?>'">
                                                        <i class="fas fa-times mr-1"></i>Dismiss
                                                    </button>
                                                    <?php
                                                        $latestResponse = $responseController->getResponsesByReportId($r['reportId']);
                                                        $allowUserReply = 0;

                                                        if (!empty($latestResponse)) {
                                                            // Assume last response in array is the latest
                                                            $lastResp = end($latestResponse);
                                                            $allowUserReply = $lastResp->getAllowUserReply();
                                                        }

                                                        // Show handle button if report in progress OR user is allowed to reply
                                                        if ($r['reportStatus'] === 'In Progress' || $allowUserReply == 1): ?>
                                                            <a class="btn btn-sm btn-primary ml-2" href="Handle-report.php?reportId=<?= $r['reportId'] ?>">Handle report -></a>
                                                        <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php
                                            // Ensure a response record exists once the report is In Progress
                                            if ($r['reportStatus'] === 'In Progress') {
                                                // Try to find an existing response for this report.
                                                // I assume ResponseController has a method like getByReportId($reportId).
                                                // If your controller uses a different method name, replace it accordingly.
                                                $existingResponse = null;
                                                if (method_exists($responseController, 'getByReportId')) {
                                                    $existingResponse = $responseController->getByReportId($r['reportId']);
                                                } elseif (method_exists($responseController, 'getResponseByReportId')) {
                                                    $existingResponse = $responseController->getResponseByReportId($r['reportId']);
                                                }

                                                // If no response found, create a default one.
                                                if (empty($existingResponse)) {
                                                    // Default message — change it as you like
                                                    $defaultMessage = 'Report has been picked up and is being handled by admin.';

                                                    // Try several common controller create method names. Replace with whatever you have.
                                                    if (method_exists($responseController, 'create')) {
                                                        $responseController->create([
                                                            'reportId'    => $r['reportId'],
                                                            'responderId' => $adminId,
                                                            'message'     => $defaultMessage,
                                                            'created_at'  => date('Y-m-d H:i:s'),
                                                        ]);
                                                    } elseif (method_exists($responseController, 'createResponse')) {
                                                        $responseController->createResponse($r['reportId'], $adminId, $defaultMessage);
                                                    } elseif (method_exists($responseController, 'add')) {
                                                        $responseController->add([
                                                            'reportId'    => $r['reportId'],
                                                            'responderId' => $adminId,
                                                            'message'     => $defaultMessage,
                                                            'created_at'  => date('Y-m-d H:i:s'),
                                                        ]);
                                                    } else {
                                                        $db = Config::getConnexion();
                                                        $stmt = $db->prepare("
                                                            INSERT INTO responses (reportID, reporter_id, response_text, response_date, report_status, action_taken)
                                                            VALUES (:reportID, :reporter_id, :response_text, :response_date, :report_status, :action_taken)
                                                        ");

                                                        $stmt->execute([
                                                            ':reportID'      => $r['reportId'],
                                                            ':reporter_id'   => $adminId,
                                                            ':response_text' => $defaultMessage,
                                                            ':response_date' => date('Y-m-d H:i:s'),
                                                            ':report_status' => 'In Progress',
                                                            ':action_taken'  => 'None'
                                                        ]);

                                                    }

                                                    // Optionally refresh $existingResponse if you plan to display it right away.
                                                    if (method_exists($responseController, 'getByReportId')) {
                                                        $existingResponse = $responseController->getByReportId($r['reportId']);
                                                    } elseif (method_exists($responseController, 'getResponseByReportId')) {
                                                        $existingResponse = $responseController->getResponseByReportId($r['reportId']);
                                                    }
                                                }
                                            }
                                            ?>

                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted">No reports available.</p>
                                    <?php endif; ?>
                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <!-- Optional right column -->
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">Copyright © Starr 2025</div>
            </div>
        </footer>
    </div>
</div>


</div>

<!-- Scroll to Top -->

<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

<!-- Logout Modal omitted -->

<!-- Scripts -->

<script src="../assets/vendor/jquery/jquery.min.js"></script>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<script src="../assets/js/sb-admin-2.min.js"></script>

<script src="../assets/vendor/chart.js/Chart.min.js"></script>

</body>
</html>
