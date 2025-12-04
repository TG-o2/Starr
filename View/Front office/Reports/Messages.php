<?php
session_start();
require_once __DIR__ . '/../../../Controller/ReportController.php';
require_once __DIR__ . '/../../../Controller/ResponseController.php';
require_once __DIR__ . '/../../../Model/Report.php';
require_once __DIR__ . '/../../../Model/Response.php';

// Use session user id if available, fallback to 10 for dev/testing
$userId = $_SESSION['id'] ?? 5;
$adminId = $_SESSION['admin_id'] ?? 4;

$reportController = new ReportController();
$responseController = new ResponseController();

// Fetch all reports for the user
$reports = $reportController->getReportsByReporter($userId);

// Determine selected report
$selectedReportId = isset($_GET['reportId']) ? intval($_GET['reportId']) : null;
$selectedReport = null;

if ($selectedReportId) {
    foreach ($reports as $r) {
        if ($r->getReportId() == $selectedReportId) {
            $selectedReport = $r;
            break;
        }
    }
}

// Default: select the latest report if none selected
if (!$selectedReport && !empty($reports)) {
    $selectedReport = $reports[0];
}

$responses = [];
if ($selectedReport) {
    $responses = $responseController->getResponsesByReportId($selectedReport->getReportId());
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Messages</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="img/favicon.ico" rel="icon">

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/Front office/css/style.css" rel="stylesheet">

    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- library scripts moved to end of body for better loading -->

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Handlee&amp;family=Nunito:wght@400;600;700;800&amp;display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet (use Front office copies) -->
    <link href="https://fonts.googleapis.com/css2?family=Coming+Soon&display=swap" rel="stylesheet">


    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>  
        body { background-color: #FDFAF5; font-family: 'Nunito', sans-serif; }  


    .report-container { max-width: 720px; margin: 50px auto; }  
    .report-card { background: #fff; border-radius: 15px; padding: 30px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); }  
    .status-badge { font-weight: 600; padding: 6px 14px; border-radius: 12px; font-size: 0.9rem; }  
    .needs-clarification { background-color: #fd7e14; color: #fff; }  

    .chat-bubble { padding: 12px 18px; border-radius: 20px; margin: 12px 0; position: relative; display: inline-block; max-width: 80%; }  
    .chat-bubble.admin { background-color: #ffe0b2; text-align: left; }  
    .chat-bubble.user { background-color: #d1ecf1; text-align: right; margin-left: auto; }  

    .reply-section { margin-top: 25px; }  
    .reply-section textarea { width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #ccc; resize: none; }  
    .reply-section input[type="file"] { margin-top: 10px; }  
    .btn-send { background-color: #FEA116; color: #fff; font-weight: 600; border-radius: 12px; margin-top: 10px; }  
    .btn-send:hover { background-color: #e28e0e; }  
    .badge-custom {
    background-color: #31ace5ff; /* your orange */
    color: #ffffffff;               /* text color */
}

    h1 { text-align: center; margin-bottom: 30px; color: #333; }  
    h5 { margin-top: 15px; }  
    <style type="text/css" id="operaUserStyle"></style>
</style>

</head>

<body>
    <div  class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <!-- Spinner Start 
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        -->


        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a class="navbar-brand">
                <h1 class="m-0 text-primary"> <img src="../logo.jpeg" alt="Starr Logo" style="height: 60px; vertical-align: middle; margin-right: 8px;">
            Starr</h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.html" class="nav-item nav-link">Home</a>
                    <a href="about.html" class="nav-item nav-link">About Us</a>
                    <a href="classes.html" class="nav-item nav-link">Classes</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Report</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 m-0">
                            <a href="Make-report.html" class="dropdown-item">Make a report</a>
                            <a href="Messages.php" class="dropdown-item active">Check response</a>
                        </div>
                    </div>
                    <a href="contact.html" class="nav-item nav-link">Contact Us</a>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->




        <!-- Messages start -->
       <div class="container report-container">

    <div class="row">
    <div class="mb-3 d-flex gap-2">
    <input type="text" id="searchText" class="form-control" placeholder="Search reports...">
    <select id="filterType" class="form-control">
        <option value="">All Types</option>
        <option value="post">Post</option>
        <option value="user">User</option>
        <option value="comment">Comment</option>
        <option value="lesson">Lesson</option>
        <!-- Add any other report types your system uses -->
    </select>

    <select id="filterStatus" class="form-control">
        <option value="">All Statuses</option>
        <option value="Pending">Pending</option>
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
        <option value="Need Info">Need Info</option>
        <!-- Add other statuses your system uses -->
    </select>
    </div>


        <!-- LEFT SIDEBAR — LIST OF USER REPORTS -->
        <div class="col-md-4">
            <h4>Your Reports</h4>
            <div class="list-group">

                <?php foreach ($reports as $r): ?>
                    <a href="?reportId=<?= $r->getReportId() ?>" 
                       class="list-group-item list-group-item-action
                        <?= ($selectedReport && $selectedReport->getReportId() == $r->getReportId()) ? 'active' : '' ?>
                        
                        <strong><?= htmlspecialchars($r->getReportType()) ?></strong><br>
                        <small><?= htmlspecialchars($r->getReportDate()) ?></small><br>
                        <span class="badge badge-custom"><?= htmlspecialchars($r->getReportStatus()) ?></span>
                    </a>
                <?php endforeach; ?>

            </div>
        </div>


        <!-- MAIN PANEL — SELECTED REPORT + MESSAGES -->
        <div class="col-md-8">

            <?php if (!$selectedReport): ?>
                <p><?= $error ?></p>

            <?php else: ?>

                <div class="report-card">

                    <h4>Report Details</h4>
                    <p><strong>Type:</strong> <?= htmlspecialchars($selectedReport->getReportType()) ?></p>
                    <p><strong>Reason:</strong> <?= htmlspecialchars($selectedReport->getReportReason()) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($selectedReport->getReportStatus()) ?></p>

                    <hr>

                    <h4>Messages</h4>

                    <?php
                    $allowReply = false;

                    if ($responses && count($responses) > 0):
                        foreach ($responses as $response):

                            if ($response->getAllowUserReply() == 1) {
                                $allowReply = true;
                            }

                            $senderId = $response->getResponderId();
                            $isAdmin = ($senderId == $adminId);

                            $cssClass = $isAdmin ? 'admin' : 'user';
                            $sender = $isAdmin ? 'Admin' : 'You';
                    ?>

                        <div class="chat-bubble <?= $cssClass ?>">
                            <strong><?= $sender ?>:</strong>
                            <p><?= htmlspecialchars($response->getResponseText()) ?></p>
                        </div>

                    <?php endforeach; ?>

                    <?php else: ?>
                        <p>No responses yet.</p>
                    <?php endif; ?>


                    <!-- Reply box -->
                    <?php if ($allowReply): ?>
                        <form class="reply-section" action="Send-user-reply.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="reportId" value="<?= $selectedReport->getReportId() ?>">

                            <textarea class="form-control" name="user_reply" rows="4"
                                placeholder="Write your reply..." required></textarea>

                            <div class="mb-3">
                                <label class="form-label">Upload screenshot (optional)</label>
                                <input class="form-control" type="file" name="attachment" accept="image/*">
                            </div>

                            <button type="submit" class="btn btn-send px-4 py-2">
                                <i class="fas fa-paper-plane me-2"></i>Send Reply
                            </button>
                        </form>

                    <?php else: ?>
                        <p style="color:#888; margin-top:20px;">The admin has not allowed replies for this report.</p>
                    <?php endif; ?>

                </div>

            <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                       
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Quick Links</h3>
                        <a class="btn btn-link text-white-50" href="">About Us</a>
                        <a class="btn btn-link text-white-50" href="">Contact Us</a>
                        <a class="btn btn-link text-white-50" href="">Our Services</a>
                        <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                    
                </div>
            </div>
           
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../assets/js/main.js"></script>
    <script>
$(document).ready(function() {
    function loadReports() {
        $.ajax({
            url: "search-report.php",
            method: "GET",
            data: {
                type: $("#filterType").val(),
                status: $("#filterStatus").val(),
                search: $("#searchText").val()
            },
            success: function(data) {
                $(".list-group").html(data); // update only the sidebar
            }
        });
    }

    // trigger on filter change or search input
    $("#filterType, #filterStatus").on("change", loadReports);
    $("#searchText").on("input", loadReports);

    // load initially
    loadReports();
});
                        
</script>

</body>

</html>