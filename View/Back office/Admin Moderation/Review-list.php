<?php
require_once __DIR__ . '/../../../Controller/ReportController.php';
require_once __DIR__ . '/../../../Model/Report.php';


require_once __DIR__ . '/../../../Controller/ResponseController.php';
require_once __DIR__ . '/../../../Model/Response.php';

$adminId = "4"; 

$reportController = new ReportController();
$responseController = new ResponseController();
$search = $_GET['search'] ?? null;
$status = $_GET['status'] ?? null;
$reportId = $_GET['reportId'] ?? null;

// If a specific reportId is provided, fetch just that report; otherwise use filterReports
if (!empty($reportId)) {
    $single = $reportController->getReportById($reportId);
    if ($single) {
        // Convert Report object to associative array expected by the template
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
            'severity' => $single->getSeverity(),
            // template checks this; default to false when not available
            'isDangerZone' => false
        ]];
    } else {
        $reports = [];
    }
} else {
    $reports = $reportController->filterReports($search, $status, null);
}

// Filter medium/high severity reports first
$severityReports = array_filter($reports, function($r){
    return in_array(strtolower($r['severity']), ['warning', 'critical']);
});

// Now remove duplicates per entity, keeping highest severity
$seen = [];
$uniqueSeverityReports = [];

foreach ($severityReports as $sr) {
    $entityKey = null;

    if (!empty($sr['reportedPostId'])) $entityKey = 'post_'.$sr['reportedPostId'];
    elseif (!empty($sr['reportedUserId'])) $entityKey = 'user_'.$sr['reportedUserId'];
    elseif (!empty($sr['reportedCommentId'])) $entityKey = 'comment_'.$sr['reportedCommentId'];
    elseif (!empty($sr['reportedLessonId'])) $entityKey = 'lesson_'.$sr['reportedLessonId'];

    if (!$entityKey) continue;

    if (!isset($seen[$entityKey])) {
        $seen[$entityKey] = strtolower($sr['severity']);
        $uniqueSeverityReports[] = $sr;
    } else {
        $currentSeverity = $seen[$entityKey];
        $newSeverity = strtolower($sr['severity']);
        if ($newSeverity === 'critical' && $currentSeverity !== 'critical') {
            foreach ($uniqueSeverityReports as $k => $r) {
                if (
                    (!empty($r['reportedPostId']) && $r['reportedPostId'] === $sr['reportedPostId']) ||
                    (!empty($r['reportedUserId']) && $r['reportedUserId'] === $sr['reportedUserId']) ||
                    (!empty($r['reportedCommentId']) && $r['reportedCommentId'] === $sr['reportedCommentId']) ||
                    (!empty($r['reportedLessonId']) && $r['reportedLessonId'] === $sr['reportedLessonId'])
                ) {
                    $uniqueSeverityReports[$k] = $sr;
                    $seen[$entityKey] = 'critical';
                    break;
                }
            }
        }
    }
}

// Now $severityReports is safe and contains only unique medium/high severity reports
$severityReports = $uniqueSeverityReports;
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
    /* Search / filter bar */
    .filter-bar { display:flex; gap:12px; align-items:center; margin-bottom:18px; }
    .filter-bar .form-control, .filter-bar .form-select { min-width:0; }
    .filter-card { background:#f8f9fc; padding:12px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.04); }
    .filter-count { font-weight:600; color:#6c757d; }
    /* Need help modal */
    .help-modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:1050; }
    .help-modal.show { display:flex; }
    .help-modal-content { background:#fff; padding:20px; border-radius:8px; max-width:520px; width:90%; box-shadow:0 8px 24px rgba(0,0,0,0.2); }
    .help-modal-content h5 { margin-top:0; }
    .help-modal-close { background:transparent; border:0; font-size:20px; line-height:1; position:absolute; right:16px; top:12px; }
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
            <?php
            // Flash alert to show result of admin actions (result=success|error)
            if (isset($_GET['result'])):
                $res = $_GET['result'];
                $msg = ($res === 'success') ? 'Action completed successfully.' : 'There was an error processing the action.';
                $alertClass = ($res === 'success') ? 'alert-success' : 'alert-danger';
            ?>
                <div id="actionFlash" class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert" style="margin-top:8px;">
                    <?= htmlspecialchars($msg) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Report List</h6>
                            <button id="needHelpBtn" class="btn btn-sm btn-outline-info" type="button">Need help?</button>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                               

                                <div class="filter-card mb-3">
                                    <form method="get" class="filter-bar" id="filter-form">
                                        <input id="report-search" name="search" value="<?= isset(
                                            $search) ? htmlspecialchars($search) : '' ?>" type="search" class="form-control" placeholder="Search reports..." />
                                        <input id="report-id" name="reportId" value="<?= isset($reportId) ? htmlspecialchars($reportId) : '' ?>" type="text" class="form-control" placeholder="Report ID" />
                                        <select id="report-status-filter" name="status" class="form-control" aria-label="Filter by status">
                                            <option value="">All statuses</option>
                                            <option <?= ($status === 'Pending') ? 'selected' : '' ?>>Pending</option>
                                            <option <?= ($status === 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                                            <option <?= ($status === 'Completed') ? 'selected' : '' ?>>Completed</option>
                                            <option <?= ($status === 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                                            <option <?= ($status === 'Need Info') ? 'selected' : '' ?>>Need Info</option>
                                            <option <?= ($status === 'Escalated') ? 'selected' : '' ?>>Escalated</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Apply</button>
                                        <button id="clear-filters" class="btn btn-light" type="button">Clear</button>
                                        <div class="ms-auto filter-count" id="filter-count">&nbsp;</div>
                                    </form>
                                </div>

                                <div class="list-group" id="review-list">
                                    <?php if (!empty($reports)): ?>
                                        <?php foreach ($reports as $r): ?>
                                            

                                                <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 border-left-danger shadow-sm"
                                                    data-status="<?= htmlspecialchars($r['reportStatus']) ?>"
                                                    data-reporter="<?= htmlspecialchars($r['reporterId']) ?>"
                                                    data-reason="<?= htmlspecialchars($r['reportReason']) ?>"
                                                    data-reportid="<?= htmlspecialchars($r['reportId']) ?>">
                                                    
                                                <div class="d-flex w-100 justify-content-between align-items-center">
                                                    <h6 class="mb-1 text-danger font-weight-bold">
                                                        <i class="fas fa-user-shield mr-1"></i>
                                                        Report by: <span class="text-dark">@<?= $r['reporterId'] ?></span>
                                                    </h6>
                                                    <?php if ($r['isDangerZone'] ?? false): ?>
                                                        <span class="badge badge-danger p-2 mt-1">
                                                            <i class="fas fa-exclamation-triangle"></i> Danger Zone
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php
                                                        $statusClass = match($r['reportStatus']) {
                                                            "pending" => "badge-secondary",
                                                            "Pending" => "badge-secondary",
                                                            "reviewed" => "badge-success",
                                                            "in progress" => "badge-primary",
                                                            "need info" => "badge-primary",
                                                            default => "badge-danger",
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
                                                            // If ANY previous response has allowed user replies, keep the flag
                                                            foreach ($latestResponse as $respItem) {
                                                                if ((int)$respItem->getAllowUserReply() === 1) {
                                                                    $allowUserReply = 1;
                                                                    break;
                                                                }
                                                            }
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
                                                            'reportId'     => $r['reportId'],
                                                            'responderId' => $adminId,
                                                            'message'      => $defaultMessage,
                                                            'created_at'   => date('Y-m-d H:i:s'),
                                                        ]);
                                                    } elseif (method_exists($responseController, 'createResponse')) {
                                                        $responseController->createResponse($r['reportId'], $adminId, $defaultMessage);
                                                    } elseif (method_exists($responseController, 'add')) {
                                                        $responseController->add([
                                                            'reportId'     => $r['reportId'],
                                                            'responderId' => $adminId,
                                                            'message'      => $defaultMessage,
                                                            'created_at'   => date('Y-m-d H:i:s'),
                                                        ]);
                                                    } else {
                                                        $db = Config::getConnexion();
                                                        $stmt = $db->prepare($sql = "INSERT INTO responses 
                            (reportId, responderId, responseText, responseDate, status, actionTaken, allowUserReply)
                            VALUES 
                            (:reportId, :responderId, :responseText, :responseDate, :status, :actionTaken, :allowUserReply)");
                                                        $stmt->execute([
                                                            ':reportId'       => $r['reportId'],
                                                            ':responderId'    => $adminId,
                                                            ':responseText'   => $defaultMessage,
                                                            ':responseDate'   => date('Y-m-d H:i:s'),
                                                            ':status'         => 'In Progress',
                                                            ':actionTaken'    => 'None',
                                                            ':allowUserReply' => 0
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
    <!-- Severity Sidebar -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Severity Alerts</h6>
        </div>
        <div class="card-body" id="severity-list">
            <?php if (!empty($severityReports)): ?>
    <?php foreach ($severityReports as $sr): ?>
        <?php 
            $isHigh = strtolower($sr['severity']) === 'critical';
            $borderColor = $isHigh ? 'border-left-danger' : 'border-left-warning';
            $bgColor = $isHigh ? 'bg-danger text-white' : 'bg-warning text-dark';
            $icon = $isHigh ? 'fas fa-exclamation-triangle' : 'fas fa-exclamation-circle';
            $sevLabel = $isHigh ? 'High' : 'Medium';

            // Icon color override
            $iconColor = $isHigh ? '#dc3545' : '#fdc314ff'; // red or orange
        ?>
        <div class="card mb-3 <?= $borderColor ?> shadow-sm" style="border-left-width: 6px;">
            <div class="card-body d-flex align-items-start">
                <div class="me-3">
                    <i class="<?= $icon ?> fa-2x" style="color: <?= $iconColor ?>;"></i>
                </div>
                <div>
                    <h6 class="mb-1"><?= $sevLabel ?> Severity</h6>
                    <p class="small mb-1"><strong>Report ID:</strong> <?= $sr['reportId'] ?></p>
                    <p class="small mb-1"><strong>Reason:</strong> <?= $sr['reportReason'] ?></p>
                    <p class="small mb-0"><strong>Date:</strong> <?= $sr['reportDate'] ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

        </div>
    </div>
</div>


            </div>
        </div>

        <!-- Need Help Modal -->
        <div id="needHelpModal" class="help-modal" role="dialog" aria-hidden="true">
            <div class="help-modal-content position-relative">
                <button class="help-modal-close" id="needHelpClose" aria-label="Close">&times;</button>
                <h5>Need help reviewing reports?</h5>
                <p class="small mb-2">Quick tips to find and handle reports:</p>
                <ul class="small mb-2">
                    <li><strong>Report ID:</strong> Use the <em>Report ID</em> field for exact lookups (e.g. <code>101</code>).</li>
                    <li><strong>Main Search:</strong> Use the <em>Search reports...</em> box for reporter IDs, keywords, or parts of descriptions.</li>
                    <li><strong>Status Filter:</strong> Narrow results by <em>Pending</em>, <em>In Progress</em>, or <em>Completed</em>.</li>
                    <li><strong>Severity Sidebar:</strong> Check the right column to prioritize Medium/High severity reports.</li>
                </ul>
                <p class="small mb-2">Handling actions:</p>
                <ul class="small mb-0">
                    <li><strong>Approve:</strong> Mark the report as resolved and close it.</li>
                    <li><strong>Dismiss:</strong> Reject the report without recording an admin response.</li>
                    <li><strong>Handle report:</strong> Open the moderation form to add an admin message, choose an action, and optionally allow the user to reply.</li>
                </ul>
                <p class="small mt-2 text-muted">After you submit an action you will return to this list and see a brief confirmation message at the top.</p>
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

<script>
    (function () {
        const search = document.getElementById('report-search');
        const status = document.getElementById('report-status-filter');
        const clearBtn = document.getElementById('clear-filters');
        const reportIdInput = document.getElementById('report-id');
        const list = document.getElementById('review-list');
        const countEl = document.getElementById('filter-count');

        function normalize(s){ return (s||'').toString().toLowerCase(); }

        function updateCount(visible, total){
            countEl.textContent = visible + ' / ' + total + ' shown';
        }

        function filter() {
            const q = normalize(search.value);
            const st = normalize(status.value);
            const items = Array.from(list.querySelectorAll('.list-group-item'));
            let visible = 0;
            items.forEach(item => {
                const itemStatus = normalize(item.dataset.status);
                const reporter = normalize(item.dataset.reporter);
                const reason = normalize(item.dataset.reason);
                const id = normalize(item.dataset.reportid);

                let matches = true;
                if (st && itemStatus !== st) matches = false;
                if (q) {
                    if (!(reporter.includes(q) || reason.includes(q) || id.includes(q) || item.textContent.toLowerCase().includes(q))) {
                        matches = false;
                    }
                }

                // If reportId filter field is set, require it to match (contains)
                const repFilter = normalize(reportIdInput ? reportIdInput.value : '');
                if (repFilter) {
                    if (!id.includes(repFilter)) {
                        matches = false;
                    }
                }

                if (matches) {
                    item.style.display = '';
                    visible++;
                } else {
                    item.style.display = 'none';
                }
            });
            updateCount(visible, items.length);
        }

        if (search) search.addEventListener('input', filter);
        if (status) status.addEventListener('change', filter);
        if (clearBtn) clearBtn.addEventListener('click', function(e){
            e.preventDefault();
            search.value = '';
            status.value = '';
            if (reportIdInput) reportIdInput.value = '';
            filter();
        });

        // initial run
        document.addEventListener('DOMContentLoaded', function(){ filter(); });
        filter();
    })();
</script>

<script>
    // Help modal toggle
    (function(){
        var btn = document.getElementById('needHelpBtn');
        var modal = document.getElementById('needHelpModal');
        var closeBtn = document.getElementById('needHelpClose');
        if (!btn || !modal) return;
        btn.addEventListener('click', function(){
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
        });
        function hide(){ modal.classList.remove('show'); modal.setAttribute('aria-hidden','true'); }
        closeBtn && closeBtn.addEventListener('click', hide);
        modal.addEventListener('click', function(e){ if (e.target === modal) hide(); });
        document.addEventListener('keydown', function(e){ if (e.key === 'Escape') hide(); });
    })();
</script>

<script>
    // Auto-hide the action flash after 5 seconds (graceful fallback if bootstrap not available)
    (function(){
        var flash = document.getElementById('actionFlash');
        if (!flash) return;
        setTimeout(function(){
            try {
                if (window.jQuery && typeof jQuery === 'function') {
                    jQuery('#actionFlash').alert('close');
                } else {
                    flash.classList.remove('show');
                    flash.style.display = 'none';
                }
            } catch (e) {
                flash.style.display = 'none';
            }
        }, 5000);
    })();
</script>




</body>
</html>
