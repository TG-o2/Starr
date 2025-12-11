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



    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

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
        body { 
    background-color: #FDFAF5; 
    font-family: 'Nunito', sans-serif; 
}

.report-container { max-width: 720px; margin: 50px auto; }
.report-card { background: #fff; border-radius: 15px; padding: 30px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
.status-badge { font-weight: 600; padding: 6px 14px; border-radius: 12px; font-size: 0.9rem; }
.needs-clarification { background-color: #fd7e14; color: #fff; }

 .chat-bubble { padding: 12px 18px; border-radius: 16px; margin: 12px 0; position: relative; display: block; max-width: 78%; clear: both; box-shadow: 0 4px 14px rgba(0,0,0,0.08); animation: fadeInUp 300ms ease; }
 .chat-bubble.admin { background: linear-gradient(135deg, #ffe0b2, #ffd79a); text-align: left; float: left; }
 .chat-bubble.user { background: linear-gradient(135deg, #d1ecf1, #c7e6ec); text-align: left; float: right; }
 .chat-bubble .sender-row { display:flex; align-items:center; gap:8px; margin-bottom:6px; }
 .chat-bubble .avatar { width:28px; height:28px; border-radius:50%; background:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:700; color:#333; box-shadow:0 1px 4px rgba(0,0,0,0.1); }
 .chat-bubble.admin .avatar { background:#fff3e0; }
 .chat-bubble.user .avatar { background:#e0f7fa; }
            .chat-bubble .timestamp { font-size:0.78rem; color:#666; margin-top:6px; }
            .chat-bubble .meta-row { display:flex; align-items:center; gap:8px; justify-content:space-between; }
            .chat-bubble .status-badges { display:flex; gap:6px; font-size:0.75rem; }
            .badge-delivered { background:#e9ecef; color:#495057; padding:4px 8px; border-radius:10px; }
            .badge-read { background:#cfe9cf; color:#2b7a2b; padding:4px 8px; border-radius:10px; }
 .chat-bubble:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(0,0,0,0.12); transition: all 160ms ease; }
 .chat-tail-left::after { content:""; position:absolute; left:-6px; bottom:8px; width:12px; height:12px; background:inherit; transform: rotate(45deg); border-bottom-left-radius:3px; }
 .chat-tail-right::after { content:""; position:absolute; right:-6px; bottom:8px; width:12px; height:12px; background:inherit; transform: rotate(45deg); border-bottom-right-radius:3px; }
 @keyframes fadeInUp { from { opacity:0; transform: translateY(6px); } to { opacity:1; transform: translateY(0); } }

/* Dark theme accents (lighter marine blue) */
.theme-dark { background:#0f1f3a; color:#e6edf7; }
.theme-dark body { background:#0f1f3a; }
.theme-dark .report-card { background:#14294d; color:#e6edf7; box-shadow:0 8px 20px rgba(0,0,0,0.35); }
.theme-dark .list-group-item { background:#102540; color:#e6edf7; border-color:#1e3a5f; }
.theme-dark .list-group-item.active { background-color:#3b82f6 !important; border-color:#3b82f6 !important; color:#f8fbff !important; }
.theme-dark .chat-bubble.admin { background: linear-gradient(135deg, #3b82f6, #2563eb); color:#eaf2ff; }
.theme-dark .chat-bubble.user { background: linear-gradient(135deg, #1fb6ff, #3a7bd5); color:#f0f6ff; }
.theme-dark .chat-bubble .timestamp { color:#cbd5e1; }
.theme-dark .badge-delivered { background:#1e3a5f; color:#e6edf7; }
.theme-dark .badge-read { background:#34d399; color:#0f1f3a; }
.theme-dark .navbar, .theme-dark .bg-white { background:#102540 !important; }
.theme-dark .navbar .nav-link, .theme-dark h1, .theme-dark h4, .theme-dark p { color:#e6edf7 !important; }
.theme-dark .form-control { background:#0f1f3a; color:#e6edf7; border-color:#1e3a5f; }
.theme-dark .btn-send { background:#3b82f6; border:none; color:#f8fbff; }
.theme-dark .btn-send:hover { background:#2563eb; }

.reply-section { margin-top: 25px; }
.reply-section textarea { width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #ccc; resize: none; }
.reply-section input[type="file"] { margin-top: 10px; }
.btn-send { background-color: #FEA116; color: #fff; font-weight: 600; border-radius: 12px; margin-top: 10px; }
.btn-send:hover { background-color: #e28e0e; }

.badge-custom {
    background-color: #FEA116;
    color: #fff;
}

.list-group-item.active {
    background-color: #FEA116 !important;
    border-color: #FEA116 !important;
    color: white !important;
}

.list-group-item.active:hover {
    background-color: #e28e0e !important;
}

.unread-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #dc3545;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
}

.upload-progress {
    display: none;
    margin-top: 10px;
}

.preview-image {
    max-width: 150px;
    max-height: 150px;
    margin-top: 10px;
    border-radius: 8px;
    border: 2px solid #ddd;
}

.chat-bubble img {
    max-width: 100%;
    border-radius: 8px;
    margin-top: 8px;
    cursor: pointer;
}

.sending-indicator {
    color: #888;
    font-size: 0.85rem;
    font-style: italic;
    display: none;
}

    h1 { text-align: center; margin-bottom: 30px; color: #333; }  
    h5 { margin-top: 15px; }  
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
                    <div class="d-flex align-items-center gap-2">
                        <button id="themeToggle" class="btn btn-sm btn-outline-secondary">Toggle theme</button>
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
        <option value="Approved">Approved</option>
        <option value="Need Info">Need Info</option>
        <option value="Rejected">Rejected</option>
    </select>
    </div>


        <!-- LEFT SIDEBAR — LIST OF USER REPORTS -->
        <div class="col-md-4">
            <h4>Your Reports</h4>
            <div class="list-group">

                <?php foreach ($reports as $r): ?>
                    <a href="?reportId=<?= $r->getReportId() ?>" 
                        class="list-group-item list-group-item-action <?= 
                            ($selectedReport && $selectedReport->getReportId() == $r->getReportId()) ? 'active' : '' 
                        ?>"
                        style="position: relative;"
                        data-report-id="<?= $r->getReportId() ?>">
                            
                            <strong><?= htmlspecialchars($r->getReportType()) ?></strong><br>
                            <small><?= htmlspecialchars($r->getReportDate()) ?></small><br>
                            <span class="badge badge-custom"><?= htmlspecialchars($r->getReportStatus()) ?></span>
                            <span class="unread-badge" data-unread="<?= $r->getReportId() ?>" style="display: none;">0</span>
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

                    <div id="messagesContainer" style="overflow: visible;">
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

                        <div class="chat-bubble <?= $cssClass ?> <?= $isAdmin ? 'chat-tail-left' : 'chat-tail-right' ?>">
                            <div class="sender-row">
                                <div class="avatar" aria-hidden="true"><?= $isAdmin ? 'A' : substr('You',0,1) ?></div>
                                <strong><?= $sender ?></strong>
                            </div>
                            <p><?= htmlspecialchars($response->getResponseText()) ?></p>
                            <?php 
                            $attachment = $response->getActionTaken();
                            if (!empty($attachment) && $attachment !== 'None') {
                                $paths = [];
                                if (strpos($attachment, 'uploads/') !== false) {
                                    $paths = [$attachment];
                                } else {
                                    $decoded = json_decode($attachment, true);
                                    if (is_array($decoded)) $paths = $decoded;
                                }
                                foreach ($paths as $p) {
                                    echo '<br><img class="preview-image" src="../../../'.htmlspecialchars($p).'" alt="Attachment" onclick="showLightbox(\'../../../'.htmlspecialchars($p).'\')" style="max-width:200px;border-radius:8px;margin-top:8px;">';
                                }
                            }
                            ?>
                                <div class="meta-row">
                                    <div class="timestamp">
                                        <?= htmlspecialchars(date('M j, g:i A', strtotime($response->getResponseDate()))) ?>
                                    </div>
                                    <div class="status-badges">
                                        <span class="badge-delivered">Delivered</span>
                                        <?php if (strtolower($response->getStatus()) === 'read'): ?>
                                            <span class="badge-read">Read</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                        </div>

                    <?php endforeach; ?>

                    <?php else: ?>
                        <p>No responses yet.</p>
                    <?php endif; ?>
                    <div style="clear: both;"></div>
                    </div>


                    <!-- Reply box -->
                    <?php if ($allowReply): ?>
                        <form class="reply-section" id="replyForm" enctype="multipart/form-data">
                            <input type="hidden" name="reportId" value="<?= $selectedReport->getReportId() ?>">

                            <textarea class="form-control" id="messageText" name="user_reply" rows="4"
                                placeholder="Write your reply..." required></textarea>

                            <div class="mb-3">
                                <label class="form-label">Upload screenshots (optional)</label>
                                <input class="form-control" type="file" id="fileInput" name="attachment[]" accept="image/*" multiple>
                                <div id="previewList" class="d-flex flex-wrap gap-2"></div>
                            </div>

                            <div class="upload-progress">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         role="progressbar" 
                                         id="uploadProgressBar" 
                                         style="width: 0%">0%</div>
                                </div>
                            </div>

                            <span class="sending-indicator" id="sendingIndicator">
                                <i class="fas fa-spinner fa-spin"></i> Sending...
                            </span>

                            <button type="submit" id="sendButton" class="btn btn-send px-4 py-2">
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
    const currentReportId = <?= json_encode($selectedReport ? $selectedReport->getReportId() : null) ?>;
    const adminId = <?= json_encode($adminId) ?>;

    // Load unread message counts
    function loadUnreadCounts() {
        $.ajax({
            url: 'get-unread-count.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    Object.keys(res.unreadCounts).forEach(function(reportId) {
                        const count = res.unreadCounts[reportId];
                        const badge = $('[data-unread="' + reportId + '"]');
                        if (count > 0) {
                            badge.text(count).show();
                        } else {
                            badge.hide();
                        }
                    });
                }
            }
        });
    }

    // Mark current conversation as read
    if (currentReportId) {
        $.post('mark-messages-read.php', { reportId: currentReportId });
    }

    // File preview
    // Draft autosave
    const draftKey = 'report_draft_' + (currentReportId || 'none');
    const savedDraft = localStorage.getItem(draftKey);
    if (savedDraft) {
        $('#messageText').val(savedDraft);
    }
    $('#messageText').on('input', function(){
        localStorage.setItem(draftKey, $(this).val());
    });

    // File preview (multiple)
    $('#fileInput').on('change', function(e) {
        const files = Array.from(e.target.files || []);
        const list = $('#previewList');
        list.empty();
        files.forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const img = $('<img>').addClass('preview-image').attr('src', ev.target.result).css({display:'inline-block'});
                    img.on('click', function(){
                        $('#lightboxImg').attr('src', ev.target.result);
                        $('#lightboxModal').show();
                    });
                    list.append(img);
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // AJAX form submission
    $('#replyForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageText = $('#messageText').val().trim();
        
        if (!messageText) return;
        
        $('#sendButton').prop('disabled', true);
        $('#sendingIndicator').show();
        $('.upload-progress').show();
        
        $.ajax({
            url: 'Send-user-reply-ajax.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        $('#uploadProgressBar').css('width', percent + '%').text(percent + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(res) {
                if (res.success) {
                    // Append new message to chat
                    let attachmentsHtml = '';
                    if (res.attachments && Array.isArray(res.attachments)) {
                        res.attachments.forEach(function(path) {
                            attachmentsHtml += '<br><img class="preview-image" src="../../../' + path + '" alt="Attachment" style="max-width: 200px; border-radius: 8px; margin-top: 8px;">';
                        });
                    }

                    const newMessage = `
                        <div class="chat-bubble user chat-tail-right">
                            <div class="sender-row"><div class="avatar">Y</div><strong>${res.sender}</strong></div>
                            <p>${res.message}</p>
                            ${attachmentsHtml}
                            <div class="meta-row"><div class="timestamp">${res.time}</div><div class="status-badges"><span class="badge-delivered">Delivered</span></div></div>
                        </div>
                        <div style="clear: both;"></div>
                    `;

                    $('#messagesContainer').append(newMessage);

                    // Reset form
                    $('#messageText').val('');
                    $('#fileInput').val('');
                    $('#previewList').empty();
                    localStorage.removeItem(draftKey);

                    // Scroll to bottom of container
                    $('#messagesContainer').animate({ scrollTop: $('#messagesContainer')[0].scrollHeight }, 300);
                } else {
                    alert('Error: ' + (res.error || 'Failed to send message'));
                }
            },
            error: function() {
                alert('Network error. Please try again.');
            },
            complete: function() {
                $('#sendButton').prop('disabled', false);
                $('#sendingIndicator').hide();
                $('.upload-progress').hide();
                $('#uploadProgressBar').css('width', '0%').text('0%');
            }
        });
    });

    // Filter/search functionality
    function loadReports() {
        $.ajax({
            url: "search-report.php",
            method: "GET",
            data: {
                type: $("#filterType").val(),
                status: $("#filterStatus").val(),
                search: $("#searchText").val(),
                selected: currentReportId
            },
            success: function(data) {
                $(".list-group").html(data);
                loadUnreadCounts(); // Refresh badges after list update
            }
        });
    }

    $("#filterType, #filterStatus").on("change", loadReports);
    $("#searchText").on("input", loadReports);

    // Initial loads
    loadReports();
    loadUnreadCounts();
    
    // Auto-refresh unread counts every 10 seconds
    setInterval(loadUnreadCounts, 10000);

        // Theme toggle
        const root = $('body');
        const themeKey = 'messages_theme';
        const savedTheme = localStorage.getItem(themeKey);
        if (savedTheme === 'dark') root.addClass('theme-dark');
        $('#themeToggle').on('click', function(){
            root.toggleClass('theme-dark');
            localStorage.setItem(themeKey, root.hasClass('theme-dark') ? 'dark' : 'light');
        });

        // Typing indicator (frontend-only visual)
        let typingTimeout;
        $('#messageText').on('input', function(){
            clearTimeout(typingTimeout);
            showTyping(true);
            typingTimeout = setTimeout(function(){ showTyping(false); }, 1200);
        });
        function showTyping(show){
            if (show) {
                if (!$('#typingDots').length) {
                    $('#messagesContainer').append('<div id="typingDots" class="chat-bubble admin chat-tail-left"><div class="sender-row"><div class="avatar">A</div><strong>Admin</strong></div><div class="typing dots"><span class="dot">.</span><span class="dot">.</span><span class="dot">.</span></div></div>');
                }
            } else {
                $('#typingDots').remove();
            }
        }
    });
    </script>

    <!-- Simple Lightbox Modal -->
    <div id="lightboxModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); align-items:center; justify-content:center; z-index:2000;">
        <img id="lightboxImg" src="" style="max-width:90%; max-height:90%; border-radius:8px;">
    </div>
    <script>
    function showLightbox(src){
        $('#lightboxImg').attr('src', src);
        $('#lightboxModal').css('display','flex');
    }
    $('#lightboxModal').on('click', function(e){
        if (e.target.id === 'lightboxModal') {
            $('#lightboxModal').hide();
        }
    });
    </script>

</body>

</html>