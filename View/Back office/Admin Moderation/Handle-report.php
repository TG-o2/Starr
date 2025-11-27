<?php


require_once __DIR__ . '/../../../Controller/ReportController.php';
require_once __DIR__ . '/../../../Model/Report.php';


require_once __DIR__ . '/../../../Controller/ResponseController.php';
require_once __DIR__ . '/../../../Model/Response.php';


// safe session start
if (session_status() === PHP_SESSION_NONE) session_start();
$adminId ="3";

$reportController = new ReportController();
$responseController = new ResponseController();

$reportId = $_GET['reportId'] ?? null;
if (!$reportId) {
    echo "No report specified.";
    exit;
}

// Get report info
$report = $reportController->getReportById($reportId);
if (!$report) {
    echo "Report not found.";
    exit;
}


$existingResponse = $responseController->getResponsesByReportId($reportId);


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Handle Report #<?= htmlspecialchars($reportId) ?></title>
  <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>
</head>
<body id="page-top">
<div id="wrapper">
  <!-- paste your exact sidebar HTML here (kept minimal for brevity) -->
  <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- ... your sidebar links ... -->
  </ul>

  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <!-- Topbar -->
      <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
        <h5 class="m-0 font-weight-bold text-primary">Report Handler</h5>
      </nav>

      <!-- Page Content -->
      <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Handle Report</h1>

        <div class="card shadow mb-4 border-left-danger">
          <div class="card-header py-3 bg-light">
            <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-flag mr-2"></i> Report Details</h6>
          </div>

          <div class="card-body">
            <div class="row mb-4">
              <div class="col-md-6">
                <p><strong>Report ID:</strong> #<?= htmlspecialchars($report->getReportId()) ?></p>
                <p><strong>Reported User:</strong> <span class="text-danger">@<?= htmlspecialchars($report->getReportedUserId()) ?></span></p>
                <p><strong>Reported User ID:</strong> <?= htmlspecialchars($report->getReportedUserId()) ?></p>
                <p><strong>Reporter:</strong> @<?= htmlspecialchars($report->getReporterId()) ?></p>
                <p><strong>Reporter ID:</strong> <?= htmlspecialchars($report->getReporterId()) ?></p>
              </div>
              <div class="col-md-6">
                <p><strong>Post ID:</strong> <?= htmlspecialchars($report->getReportedPostId()) ?></p>
                <p><strong>Date Submitted:</strong> <?= htmlspecialchars($report->getReportDate()) ?></p>
                <p><strong>Reason:</strong> <?= htmlspecialchars($report->getReportReason()) ?></p>
                <span class="badge badge-<?= ($report->getReportStatus() === 'Pending' ? 'danger' : ($report->getReportStatus() === 'In Progress' ? 'primary':'secondary')) ?> p-2 mt-2"><?= htmlspecialchars($report->getReportStatus()) ?></span>
              </div>
            </div>

            <hr>
            <h6 class="font-weight-bold text-primary mb-2"><i class="fas fa-comment-dots mr-1"></i> Reported Content</h6>
            <div class="border p-3 rounded bg-light mb-4">
              <p class="text-muted mb-0"><?= nl2br(htmlspecialchars($report->getReportDescription() ?? $report->getReportReason())) ?></p>
            </div>

            <hr>
            <h6 class="font-weight-bold text-primary mb-2"><i class="fas fa-info-circle mr-1"></i> Current Response</h6>
            <?php if ($existingResponse): ?>
            <div class="border p-3 rounded bg-white mb-3">
              <p class="mb-1"><strong>Action:</strong> <?= htmlspecialchars($existingResponse[0]->getActionTaken()) ?></p>
              <p class="mb-1"><strong>Message:</strong> <?= nl2br(htmlspecialchars($existingResponse[0]->getResponseText())) ?></p>
              <p class="mb-1"><strong>Date:</strong> <?= htmlspecialchars($existingResponse[0]->getResponseDate()) ?></p>
            </div>
          <?php else: ?>
              <p class="text-muted">No response has been recorded yet for this report.</p>
            <?php endif; ?>

            <hr>

            <div class="d-flex justify-content-between align-items-center">
              <div>
                <button class="btn btn-secondary btn-sm mr-2" onclick="window.location.href='../../Front%20office/Reports/Messages.php?reportId=<?= urlencode($reportId) ?>'">
                  Check responses
                </button>
              </div>

              <div class="d-flex">
                <button class="btn btn-danger btn-sm mr-2" data-action="reject" onclick="openActionModal('reject')">
                  Reject
                </button>

                <button class="btn btn-success btn-sm mr-2" data-action="approve" onclick="openActionModal('approve')">
                  Approve
                </button>

                <button class="btn btn-primary btn-sm mr-2" data-action="escalate" onclick="openActionModal('escalate')">
                  Escalate
                </button>

                <button class="btn btn-warning btn-sm" data-action="request_info" onclick="openActionModal('request_info')">
                  Request more info
                </button>
              </div>
            </div>


          </div>
        </div>
      </div>

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto"><div class="copyright text-center my-auto">Copyright © Starr 2025</div></div>
      </footer>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="actionForm" method="post" action="Process-report-action.php">
      <input type="hidden" name="reportId" value="<?= htmlspecialchars($reportId) ?>">
      <input type="hidden" name="action" id="actionInput" value="">
      <input type="hidden" name="adminId" value="<?= htmlspecialchars($adminId) ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="actionModalLabel">Take Action</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="adminMessage">Message to user (optional)</label>
            <textarea class="form-control" id="adminMessage" name="adminMessage" rows="4" placeholder="Optional message to user (will appear in Messages)"></textarea>
          </div>
          <div class="form-group">
            <label>Confirm action</label>
            <p id="actionConfirmText" class="mb-0 font-weight-bold"></p>
            <small class="form-text text-muted">You may leave message empty and still apply the action.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Apply Action</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
function openActionModal(action) {
    const labels = {
        reject: 'Reject (User does NOT reply)',
        approve: 'Approve (User does NOT reply)',
        escalate: 'Escalate (User can reply)',
        request_info: 'Request more info (User can reply)'
    };
    document.getElementById('actionModalLabel').textContent = labels[action] || 'Take Action';
    document.getElementById('actionInput').value = action;
    document.getElementById('actionConfirmText').textContent = labels[action] + ' for report #' + <?= json_encode($reportId) ?>;
    $('#actionModal').modal('show');
}
</script>

</body>
</html>
