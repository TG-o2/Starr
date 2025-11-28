<?php
// Include your controllers at the very top
require_once __DIR__ . '/../../../Controller/Config.php';
require_once __DIR__ . '/../../../Controller/newsController.php';
require_once __DIR__ . '/../../../Controller/commentController.php';

$newsController = new NewsController();
$commentController = new CommentController();

// Handle DELETE operation for articles
if (isset($_GET['delete_id'])) {
    $result = $newsController->deleteNews($_GET['delete_id']);
    if ($result) {
        $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Article deleted successfully!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
        echo '<script>setTimeout(function(){ window.location.href = "backoffice.php"; }, 1000);</script>';
    } else {
        $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error deleting article!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
    }
}

// Handle UPDATE operation for articles
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $image = $_POST['current_image'];
    
    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../../uploads/news/';
        if (!is_dir($uploadDir)) { 
            mkdir($uploadDir, 0777, true); 
        }
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) { 
            $image = $imageName; 
        }
    }
    
    $result = $newsController->updateNews($update_id, $title, $content, $category, $image);
    
    if ($result) {
        $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Article updated successfully!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
        echo '<script>setTimeout(function(){ window.location.href = "backoffice.php"; }, 1000);</script>';
    } else {
        $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error updating article!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
    }
}

// Handle form submission for adding new article
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && !isset($_POST['update_id'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = trim($_POST['category']);
    $image = null;
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../../uploads/news/';
        if (!is_dir($uploadDir)) { 
            mkdir($uploadDir, 0777, true); 
        }
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) { 
            $image = $imageName; 
        }
    }
    
    // Validate required fields (image mandatory)
    if (empty($title) || empty($content) || empty($category) || empty($image)) {
        $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Please fill in all required fields and choose an image!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
    } else {
        // Create news object and add to database
        $news = new News();
        $news->setTitle($title);
        $news->setContent($content);
        $now = date('Y-m-d H:i:s');
        $news->setPublished_date($now);
        $news->setUpdated_date($now);
        $news->setImage($image);
        $news->setStatus('published');
        $news->setTeacherid(1);
        $news->setCategory($category);
        
        $result = $newsController->addNews($news);
        
        if ($result) {
            $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Article added successfully!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            echo '<script>setTimeout(function(){ window.location.href = "backoffice.php"; }, 1000);</script>';
        } else {
            $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error adding article! Please try again.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        }
    }
}

// Handle ADD comment operation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_id = $_POST['news_id'] ?? '';
    $comment_content = $_POST['comment_content'] ?? '';

    if (!empty($news_id) && !empty($comment_content) && ctype_digit($news_id) && (int)$news_id > 0) {
        $comment = new Comments();
        $comment->setNewsId((int)$news_id);
        $comment->setContent($comment_content);
        $comment->setCreatedAt(date('Y-m-d H:i:s'));

        $commentController = new CommentController();
        $result = $commentController->addComment($comment);
    }
}
// Handle UPDATE comment operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_comment_id'])) {
    $comment_id = $_POST['update_comment_id'];
    $comment_content = trim($_POST['comment_content']);
    
    if (!empty($comment_content)) {
        $result = $commentController->updateComment($comment_id, $comment_content);
        
        if ($result) {
            $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Comment updated successfully!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            echo '<script>setTimeout(function(){ window.location.href = "backoffice.php"; }, 1000);</script>';
        } else {
            $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error updating comment!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        }
    }
}

// Handle DELETE comment operation
if (isset($_GET['delete_comment_id'])) {
    $result = $commentController->deleteComment($_GET['delete_comment_id']);
    
    if ($result) {
        $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Comment deleted successfully!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
        echo '<script>setTimeout(function(){ window.location.href = "backoffice.php"; }, 1000);</script>';
    } else {
        $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error deleting comment!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
    }
}

// Handle Platform Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_platform_settings'])) {
    $settings = [
        'site_name' => $_POST['site_name'],
        'theme' => $_POST['theme'],
        'primary_color' => $_POST['primary_color'],
        'secondary_color' => $_POST['secondary_color'],
        'font_family' => $_POST['font_family']
    ];
    
    // Save settings to database or file
    file_put_contents(__DIR__ . '/platform_settings.json', json_encode($settings));
    
    $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Platform settings updated successfully!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';
}

// Load platform settings with proper error handling
$settings_file = __DIR__ . '/platform_settings.json';
$platform_settings = [
    'site_name' => 'News Platform',
    'theme' => 'light',
    'primary_color' => '#1a73e8',
    'secondary_color' => '#34a853',
    'font_family' => 'Roboto, Arial, sans-serif'
];

if (file_exists($settings_file)) {
    $saved_settings = json_decode(file_get_contents($settings_file), true);
    if ($saved_settings) {
        $platform_settings = array_merge($platform_settings, $saved_settings);
    }
}

// Ensure all required keys exist with safe array access
$site_name = $platform_settings['site_name'] ?? 'News Platform';
$theme = $platform_settings['theme'] ?? 'light';
$primary_color = $platform_settings['primary_color'] ?? '#1a73e8';
$secondary_color = $platform_settings['secondary_color'] ?? '#34a853';
$font_family = $platform_settings['font_family'] ?? 'Roboto, Arial, sans-serif';

// Auto theme detection for initial page load
if ($theme === 'auto') {
    // Check if user has a system preference for dark mode
    if (isset($_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME'])) {
        $theme = $_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME'] === 'dark' ? 'dark' : 'light';
    }
}

// Get all news articles
$allNews = $newsController->getAllNews();

// Get article for editing if edit_id is set
$editNews = null;
if (isset($_GET['edit_id'])) {
    $editNews = $newsController->getNewsById($_GET['edit_id']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SB Admin 2 - Articles Management</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: <?php echo $primary_color; ?>;
            --secondary-color: <?php echo $secondary_color; ?>;
            --font-family: <?php echo $font_family; ?>;
            --background-color: #ffffff;
            --surface-color: #f8f9fa;
            --text-primary: #202124;
            --text-secondary: #5f6368;
            --border-color: #dadce0;
            --shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            --sidebar-bg: #4e73df;
            --sidebar-text: #ffffff;
            --topbar-bg: #ffffff;
            --topbar-text: #5a5c69;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--background-color);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .dark-theme {
            --primary-color: #8ab4f8;
            --secondary-color: #81c995;
            --background-color: #202124;
            --surface-color: #292a2d;
            --text-primary: #e8eaed;
            --text-secondary: #9aa0a6;
            --border-color: #3c4043;
            --shadow: 0 1px 3px rgba(0,0,0,0.3), 0 1px 2px rgba(0,0,0,0.4);
            --sidebar-bg: #1a1d23;
            --sidebar-text: #e8eaed;
            --topbar-bg: #292a2d;
            --topbar-text: #e8eaed;
        }

        /* Apply theme to ALL elements */
        .card, .alert, .article-card, .comments-container, .update-form, .edit-comment-form,
        .sidebar, .topbar, .navbar, .modal-content, .dropdown-menu {
            background-color: var(--surface-color) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        .card-header, .modal-header, .modal-footer {
            background-color: var(--surface-color) !important;
            border-color: var(--border-color) !important;
        }

        .form-control, .form-select {
            background-color: var(--background-color) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        .text-gray-800, .text-gray-600, .text-muted {
            color: var(--text-secondary) !important;
        }

        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn-outline-primary {
            color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color) !important;
            color: white !important;
        }

        /* Sidebar theming */
        .sidebar {
            background-color: var(--sidebar-bg) !important;
        }

        .sidebar .nav-item .nav-link {
            color: var(--sidebar-text) !important;
        }

        .sidebar .nav-item .nav-link i {
            color: var(--sidebar-text) !important;
        }

        .sidebar .sidebar-brand .sidebar-brand-text {
            color: var(--sidebar-text) !important;
        }

        .sidebar .sidebar-brand .sidebar-brand-icon {
            color: var(--sidebar-text) !important;
        }

        /* Topbar theming */
        .navbar-light {
            background-color: var(--topbar-bg) !important;
        }

        .navbar-light .navbar-nav .nav-link {
            color: var(--topbar-text) !important;
        }

        .navbar-light .form-control {
            background-color: var(--background-color) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        .navbar-light .input-group-text {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: white !important;
        }

        /* Dropdown theming */
        .dropdown-menu {
            background-color: var(--surface-color) !important;
            border-color: var(--border-color) !important;
        }

        .dropdown-item {
            color: var(--text-primary) !important;
        }

        .dropdown-item:hover {
            background-color: var(--background-color) !important;
            color: var(--text-primary) !important;
        }

        /* Footer theming */
        .bg-white {
            background-color: var(--surface-color) !important;
        }

        /* Customization Button */
        .customize-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .customize-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(0,0,0,0.2);
        }

        /* Chrome-Style Customization Modal */
        .chrome-customize-modal .modal-content {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 16px 40px rgba(0,0,0,0.12);
            border: none;
        }

        .chrome-customize-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 32px;
            color: white;
            text-align: center;
        }

        .chrome-customize-title {
            font-size: 28px;
            font-weight: 400;
            margin: 0 0 8px 0;
        }

        .chrome-customize-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin: 0;
        }

        .chrome-customize-body {
            padding: 0;
            max-height: 65vh;
            overflow-y: auto;
        }

        .chrome-customize-section {
            padding: 32px;
            border-bottom: 1px solid var(--border-color);
        }

        .chrome-customize-section:last-child {
            border-bottom: none;
        }

        .chrome-section-title {
            font-size: 18px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chrome-section-title i {
            color: var(--primary-color);
        }

        .chrome-theme-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .chrome-theme-option {
            border: 2px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--surface-color);
            position: relative;
            overflow: hidden;
        }

        .chrome-theme-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .chrome-theme-option.active {
            border-color: var(--primary-color);
            background: var(--surface-color);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .chrome-theme-option.active::before {
            content: '✓';
            position: absolute;
            top: 12px;
            right: 12px;
            width: 24px;
            height: 24px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }

        .chrome-theme-preview {
            width: 100%;
            height: 100px;
            border-radius: 12px;
            margin-bottom: 16px;
            position: relative;
            overflow: hidden;
        }

        .light-preview {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 1px solid #e8eaed;
        }

        .dark-preview {
            background: linear-gradient(135deg, #202124 0%, #3c4043 100%);
        }

        .auto-preview {
            background: linear-gradient(135deg, #ffffff 0%, #202124 100%);
        }

        .chrome-theme-name {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .chrome-theme-description {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .chrome-color-picker {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-bottom: 20px;
        }

        .chrome-color-input {
            width: 80px;
            height: 50px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .chrome-color-label {
            flex: 1;
        }

        .chrome-color-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .chrome-color-description {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .chrome-font-select {
            width: 100%;
            padding: 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 16px;
            background: var(--background-color);
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .chrome-font-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        }

        .chrome-customize-footer {
            padding: 24px 32px;
            background: var(--surface-color);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chrome-btn {
            padding: 12px 32px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chrome-btn-secondary {
            background: transparent;
            border: 2px solid var(--border-color);
            color: var(--text-primary);
        }

        .chrome-btn-secondary:hover {
            background: var(--surface-color);
            border-color: var(--primary-color);
        }

        .chrome-btn-primary {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 15px rgba(26, 115, 232, 0.3);
        }

        .chrome-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 115, 232, 0.4);
        }

        .chrome-preview-card {
            background: var(--surface-color);
            border-radius: 12px;
            padding: 20px;
            border: 2px solid var(--border-color);
            margin-top: 16px;
        }

        .chrome-preview-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .chrome-preview-text {
            color: var(--text-secondary);
            line-height: 1.5;
        }

        /* Animation for theme changes */
        .theme-transition * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* Your existing styles */
        .article-card {
            background: var(--surface-color);
            border-left: 4px solid var(--primary-color);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }
        .article-card img {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
        }
        .article-card h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 8px;
        }
        .comments-container {
            background: linear-gradient(135deg, var(--surface-color) 0%, var(--background-color) 100%);
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 64, 133, 0.15);
            margin-top: 20px;
            padding: 30px;
            border-radius: 8px;
        }
        .comments-container h5 {
            color: var(--primary-color);
            font-weight: 600;
        }
        .comments-preview-header {
            padding: 20px 0 0 0;
        }
        .comments-preview {
            border: 2px solid var(--primary-color) !important;
            color: var(--primary-color) !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .comments-preview:hover {
            background-color: var(--primary-color) !important;
            color: white !important;
        }
        .comment {
            background: var(--background-color);
            padding: 16px;
            border-radius: 8px;
            border-left: 3px solid var(--secondary-color);
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            margin-bottom: 16px;
        }
        .comment strong {
            color: var(--primary-color);
        }
        .comment div:last-child {
            color: var(--text-primary);
            line-height: 1.6;
            margin-top: 8px;
        }
        .comment-form input {
            border: 2px solid var(--primary-color) !important;
            font-size: 16px;
        }
        .comment-form button {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .comments-close {
            font-size: 20px;
            cursor: pointer;
            color: var(--text-secondary);
        }
        .comments-close:hover {
            color: var(--primary-color) !important;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .action-buttons {
            margin-top: 10px;
        }
        .update-form {
            background: var(--surface-color);
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid var(--secondary-color);
            margin-top: 20px;
        }
        .comment-actions {
            margin-top: 10px;
            display: flex;
            gap: 5px;
        }
        .edit-comment-form {
            background: var(--surface-color);
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            margin: 10px 0;
        }
        .comment-item {
            transition: all 0.3s ease;
        }
        .comment-item:hover {
            transform: translateX(5px);
        }
        .form-group.required label:after {
            content: " *";
            color: red;
        }
        
    </style>
    <style>
    .is-valid {
        border: 2px solid #28a745 !important;
    }

    .is-invalid {
        border: 2px solid #dc3545 !important;
    }

    .error-msg {
        color: #dc3545;
        font-size: 0.9rem;
        display: none;
    }
</style>

</head>

<body id="page-top" class="theme-transition <?php echo $theme === 'dark' || ($theme === 'auto' && isset($_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME']) && $_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME'] === 'dark') ? 'dark-theme' : ''; ?>">
    <!-- Customization Button -->
    <button class="customize-btn" data-toggle="modal" data-target="#chromeCustomizeModal">
        <i class="fas fa-palette"></i>
    </button>

    <!-- Chrome-Style Customization Modal -->
    <div class="modal fade chrome-customize-modal" id="chromeCustomizeModal" tabindex="-1" role="dialog" aria-labelledby="chromeCustomizeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="chrome-customize-header">
                    <h2 class="chrome-customize-title">Customize Your Platform</h2>
                    <p class="chrome-customize-subtitle">Make it yours with beautiful themes and colors</p>
                </div>
                <form method="POST" id="chromeCustomizeForm">
                    <input type="hidden" name="save_platform_settings" value="1">
                    <div class="chrome-customize-body">
                        <!-- Appearance Section -->
                        <div class="chrome-customize-section">
                            <h3 class="chrome-section-title">
                                <i class="fas fa-paint-brush"></i>
                                Appearance
                            </h3>
                            
                            <div class="chrome-theme-options">
                                <div class="chrome-theme-option <?php echo $theme === 'light' ? 'active' : ''; ?>" data-theme="light">
                                    <div class="chrome-theme-preview light-preview"></div>
                                    <div class="chrome-theme-name">Light Theme</div>
                                    <div class="chrome-theme-description">Clean, bright interface perfect for daytime use</div>
                                </div>
                                
                                <div class="chrome-theme-option <?php echo $theme === 'dark' ? 'active' : ''; ?>" data-theme="dark">
                                    <div class="chrome-theme-preview dark-preview"></div>
                                    <div class="chrome-theme-name">Dark Theme</div>
                                    <div class="chrome-theme-description">Easy on the eyes, perfect for low-light environments</div>
                                </div>
                                
                                <div class="chrome-theme-option <?php echo $theme === 'auto' ? 'active' : ''; ?>" data-theme="auto">
                                    <div class="chrome-theme-preview auto-preview"></div>
                                    <div class="chrome-theme-name">Auto Theme</div>
                                    <div class="chrome-theme-description">Automatically switches based on your system preference</div>
                                </div>
                            </div>
                            <input type="hidden" name="theme" id="selectedTheme" value="<?php echo $theme; ?>">
                        </div>

                        <!-- Colors Section -->
                        <div class="chrome-customize-section">
                            <h3 class="chrome-section-title">
                                <i class="fas fa-fill-drip"></i>
                                Colors
                            </h3>
                            
                            <div class="chrome-color-picker">
                                <div class="chrome-color-label">
                                    <div class="chrome-color-title">Primary Color</div>
                                    <div class="chrome-color-description">Main brand color used for buttons, links, and highlights</div>
                                </div>
                                <input type="color" class="chrome-color-input" name="primary_color" value="<?php echo $primary_color; ?>">
                            </div>
                            
                            <div class="chrome-color-picker">
                                <div class="chrome-color-label">
                                    <div class="chrome-color-title">Secondary Color</div>
                                    <div class="chrome-color-description">Accent color used for highlights, badges, and secondary elements</div>
                                </div>
                                <input type="color" class="chrome-color-input" name="secondary_color" value="<?php echo $secondary_color; ?>">
                            </div>

                            <div class="chrome-preview-card">
                                <div class="chrome-preview-title">Preview</div>
                                <div class="chrome-preview-text">This is how your platform will look with the selected colors. The changes will apply to the entire interface.</div>
                            </div>
                        </div>

                        <!-- Fonts Section -->
                        <div class="chrome-customize-section">
                            <h3 class="chrome-section-title">
                                <i class="fas fa-font"></i>
                                Typography
                            </h3>
                            
                            <div class="chrome-color-picker">
                                <div class="chrome-color-label">
                                    <div class="chrome-color-title">Font Family</div>
                                    <div class="chrome-color-description">Choose the font that best represents your brand's personality</div>
                                </div>
                                <select class="chrome-font-select" name="font_family">
                                    <option value="Roboto, Arial, sans-serif" <?php echo $font_family == 'Roboto, Arial, sans-serif' ? 'selected' : ''; ?>>Roboto - Modern & Clean</option>
                                    <option value="'Google Sans', Arial, sans-serif" <?php echo $font_family == "'Google Sans', Arial, sans-serif" ? 'selected' : ''; ?>>Google Sans - Professional</option>
                                    <option value="Arial, sans-serif" <?php echo $font_family == 'Arial, sans-serif' ? 'selected' : ''; ?>>Arial - Classic & Readable</option>
                                    <option value="'Helvetica Neue', Helvetica, sans-serif" <?php echo $font_family == "'Helvetica Neue', Helvetica, sans-serif" ? 'selected' : ''; ?>>Helvetica - Elegant</option>
                                    <option value="'Segoe UI', Tahoma, sans-serif" <?php echo $font_family == "'Segoe UI', Tahoma, sans-serif" ? 'selected' : ''; ?>>Segoe UI - Modern Windows</option>
                                </select>
                            </div>
                        </div>

                        <!-- Branding Section -->
                        <div class="chrome-customize-section">
                            <h3 class="chrome-section-title">
                                <i class="fas fa-tag"></i>
                                Branding
                            </h3>
                            
                            <div class="chrome-color-picker">
                                <div class="chrome-color-label">
                                    <div class="chrome-color-title">Site Name</div>
                                    <div class="chrome-color-description">The name that will be displayed throughout your platform</div>
                                </div>
                                <input type="text" class="chrome-font-select" name="site_name" value="<?php echo $site_name; ?>" placeholder="Enter your site name">
                            </div>
                        </div>
                    </div>
                    <div class="chrome-customize-footer">
                        <button type="button" class="chrome-btn chrome-btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i>
                            Cancel
                        </button>
                        <button type="submit" class="chrome-btn chrome-btn-primary">
                            <i class="fas fa-save"></i>
                            Apply Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3"><?php echo $site_name; ?> <sup>Admin</sup></div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">
                Interface
            </div>
            <li class="nav-item active">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>News Management</span>
                </a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin User</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Articles Management</h1>
                    
                    <?php if (isset($alert_message)) echo $alert_message; ?>

                    <!-- Rest of your articles management code remains exactly the same -->
                    <!-- Add/Update Article Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <?php echo $editNews ? 'Update Article' : 'Add New Article'; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if ($editNews): ?>
                                <!-- Update Form -->
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="update_id" value="<?php echo $editNews['newsid']; ?>">
                                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($editNews['image']); ?>">
                                    
                                    <div class="form-group">
                                        <label for="editArticleImage">Article Image</label>
                                        <?php if (!empty($editNews['image'])): ?>
                                            <div class="mb-2">
                                                <img src="/Cosmosweb/uploads/news/<?php echo htmlspecialchars($editNews['image']); ?>" alt="Current image" style="max-width: 200px; height: auto;" class="img-thumbnail">
                                                <p class="text-muted small">Current image</p>
                                            </div>
                                        <?php endif; ?>
                                        <input class="form-control" type="file" id="editArticleImage" name="image">
                                        <div class="form-text">Leave empty to keep current image</div>
                                    </div>
                                    <div class="form-group required">
                                        <label for="editArticleTitle">Title</label>
                                        <input type="text" class="form-control" id="editArticleTitle" name="title" 
                                               value="<?php echo htmlspecialchars($editNews['title']); ?>" >
                                        <small class="form-text text-muted">Enter a descriptive title (min 3 characters).</small>
                                        <small id="editArticleTitleError" class="error-msg">Title must be at least 3 characters.</small>
                                    </div>
                                    <div class="form-group required">
                                        <label for="editArticleCategory">Category</label>
                                        <select class="form-control" id="editArticleCategory" name="category" >
                                            <option value="">Select a category</option>
                                            <option value="community" <?php echo ($editNews['category'] == 'community') ? 'selected' : ''; ?>>Community</option>
                                            <option value="education" <?php echo ($editNews['category'] == 'education') ? 'selected' : ''; ?>>Education</option>
                                            <option value="events" <?php echo ($editNews['category'] == 'events') ? 'selected' : ''; ?>>Events</option>
                                            <option value="sports" <?php echo ($editNews['category'] == 'sports') ? 'selected' : ''; ?>>Sports</option>
                                            <option value="technology" <?php echo ($editNews['category'] == 'technology') ? 'selected' : ''; ?>>Technology</option>
                                            <option value="health" <?php echo ($editNews['category'] == 'health') ? 'selected' : ''; ?>>Health</option>
                                            <option value="business" <?php echo ($editNews['category'] == 'business') ? 'selected' : ''; ?>>Business</option>
                                        </select>
                                        <small class="form-text text-muted">Choose the most relevant category.</small>
                                        <small id="editArticleCategoryError" class="error-msg">Please select a category.</small>
                                    </div>
                                    <div class="form-group required">
                                        <label for="editArticleContent">Content</label>
                                        <textarea class="form-control" id="editArticleContent" name="content" rows="5" required><?php echo htmlspecialchars($editNews['content']); ?></textarea>
                                        <small class="form-text text-muted">Write the article content (min 10 characters).</small>
                                        <small id="editArticleContentError" class="error-msg">Content must be at least 10 characters.</small>
                                    </div>
                                    <button type="submit" class="btn btn-warning">Update Article</button>
                                    <a href="backoffice.php" class="btn btn-secondary">Cancel</a>
                                </form>
                            <?php else: ?>
                                <!-- Add Form -->
                                <form method="POST" enctype="multipart/form-data" id="addArticleForm">
                                    <div class="form-group required">
                                        <label for="articleImage">Article Image</label>
                                        <input class="form-control" type="file" id="articleImage" name="image">
                                        <small class="form-text text-muted">Select a clear image (JPG or PNG recommended).</small>
                                        <small id="articleImageError" class="error-msg">Please choose an image.</small>
                                    </div>
                                    <div class="form-group required">
                                        <label for="articleTitle">Title</label>
                                        <input type="text" class="form-control" id="articleTitle" name="title" placeholder="Enter article title" >
                                        <small class="form-text text-muted">Enter a descriptive title (min 3 characters).</small>
                                        <small id="articleTitleError" class="error-msg">Title must be at least 3 characters.</small>
                                    </div>
                                    <div class="form-group required">
                                        <label for="articleCategory">Category</label>
                                        <select class="form-control" id="articleCategory" name="category" >
                                            <option value="">Select a category</option>
                                            <option value="community">Community</option>
                                            <option value="education">Education</option>
                                            <option value="events">Events</option>
                                            <option value="sports">Sports</option>
                                            <option value="technology">Technology</option>
                                            <option value="health">Health</option>
                                            <option value="business">Business</option>
                                        </select>
                                        <small class="form-text text-muted">Choose the most relevant category.</small>
                                        <small id="articleCategoryError" class="error-msg">Please select a category.</small>
                                    </div>
                                    <div class="form-group required">
                                        <label for="articleContent">Content</label>
                                        <textarea class="form-control" id="articleContent" name="content" rows="5" placeholder="Enter article content" ></textarea>
                                        <small class="form-text text-muted">Write the article content (min 10 characters).</small>
                                        <small id="articleContentError" class="error-msg">Content must be at least 10 characters.</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="addArticleBtn">Add Article</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Articles List -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Articles List</h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($allNews)): ?>
                                <?php foreach ($allNews as $news): ?>
                                    <?php
                                    $comments = $commentController->getCommentsByNewsId($news['newsid']);
                                    $comments_count = count($comments);
                                    $preview_text = 'No comments yet';
                                    if ($comments_count > 0) {
                                        $preview_text = '';
                                        $preview_comments = array_slice($comments, 0, 2);
                                        foreach ($preview_comments as $comment) {
                                            $comment_text = htmlspecialchars($comment['content']);
                                            if (strlen($comment_text) > 50) { 
                                                $comment_text = substr($comment_text, 0, 50) . '...'; 
                                            }
                                            $preview_text .= '• ' . $comment_text . '<br>';
                                        }
                                        if ($comments_count > 2) { 
                                            $preview_text .= 'and ' . ($comments_count - 2) . ' more comments...'; 
                                        }
                                    }
                                    ?>
                                    
                                    <!-- Article Card -->
                                    <div class="article-card d-flex gap-3 align-items-start">
                                        <?php if (!empty($news['image'])): ?>
                                            <img src="/Cosmosweb/uploads/news/<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>">
                                        <?php else: ?>
                                            <img src="../kider-1.0.0/img/carousel-1.jpg" alt="Default news image">
                                        <?php endif; ?>
                                        <div class="flex-grow-1">
                                            <h5><?php echo htmlspecialchars($news['title']); ?></h5>
                                            <p class="text-muted small mb-2">
                                                <?php 
                                                $date = new DateTime($news['published_date']); 
                                                echo $date->format('M j, Y'); 
                                                ?>
                                                <?php if (!empty($news['category'])): ?>
                                                    • <span class="badge bg-primary"><?php echo htmlspecialchars(ucfirst($news['category'])); ?></span>
                                                <?php endif; ?>
                                            </p>
                                            <p class="mb-3">
                                                <?php 
                                                $content = strip_tags($news['content']); 
                                                echo strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content; 
                                                ?>
                                            </p>
                                            <div class="action-buttons">
                                                <a href="backoffice.php?edit_id=<?php echo $news['newsid']; ?>" class="btn btn-warning btn-sm me-2">
                                                    <i class="fas fa-edit"></i> Modify
                                                </a>
                                                <a href="backoffice.php?delete_id=<?php echo $news['newsid']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this article? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Comments Section -->
                                    <div class="comments-container" data-article-id="<?php echo $news['newsid']; ?>">
                                        <div class="comments-list" data-comments-for="<?php echo $news['newsid']; ?>">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Comments (<?php echo $comments_count; ?>)</h5>
                                                <a href="#" class="comments-close text-decoration-none" data-close-for="<?php echo $news['newsid']; ?>">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                            <div class="comments-content mb-4">
                                                <?php if ($comments_count === 0): ?>
                                                    <div class="no-comments text-muted mb-4 text-center py-3">
                                                        <i class="fas fa-comment-slash fa-2x mb-2"></i>
                                                        <p class="mb-0">No comments yet. Be the first to comment!</p>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="comments-scrollable" style="max-height:400px;overflow-y:auto;">
                                                        <?php foreach ($comments as $comment): ?>
                                                            <div class="comment-item card mb-3" data-comment-id="<?php echo $comment['id']; ?>">
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                                        <h6 class="card-subtitle mb-1 text-primary">
                                                                            Admin
                                                                        </h6>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <button class="btn btn-outline-secondary edit-comment-btn" 
                                                                                    data-comment-id="<?php echo $comment['id']; ?>" 
                                                                                    data-comment-content="<?php echo htmlspecialchars($comment['content']); ?>"
                                                                                    title="Edit Comment">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                            <a href="backoffice.php?delete_comment_id=<?php echo $comment['id']; ?>" 
                                                                               class="btn btn-outline-danger" 
                                                                               onclick="return confirm('Are you sure you want to delete this comment?')"
                                                                               title="Delete Comment">
                                                                                <i class="fas fa-trash"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <p class="card-text mb-0 comment-content"><?php echo htmlspecialchars($comment['content']); ?></p>
                                                                    <small class="text-muted">
                                                                        <?php 
                                                                        $cd = new DateTime($comment['created_at']); 
                                                                        echo $cd->format('M j, Y g:i A'); 
                                                                        ?>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Add Comment Form -->
                                            <form class="comment-form" method="POST">
                                                <input type="hidden" name="news_id" value="<?php echo $news['newsid']; ?>">
                                                <div class="input-group input-group-lg">
                                                    <input type="text" class="form-control comment-input" name="comment_content" placeholder="Write a comment..." >
                                                    <button class="btn btn-primary comment-submit" type="submit">
                                                        <i class="fas fa-paper-plane me-1"></i> Post Comment
                                                    </button>
                                                </div>
                                            </form>
                                            
                                            <!-- Edit Comment Form (Hidden by default) -->
                                            <div class="edit-comment-form d-none">
                                                <form method="POST">
                                                    <input type="hidden" name="update_comment_id" id="edit_comment_id">
                                                    <div class="form-group">
                                                        <label for="edit_comment_content">Edit Comment</label>
                                                        <textarea class="form-control" id="edit_comment_content" name="comment_content" rows="3" ></textarea>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fas fa-save me-1"></i> Update Comment
                                                        </button>
                                                        <button type="button" class="btn btn-secondary btn-sm cancel-edit">
                                                            <i class="fas fa-times me-1"></i> Cancel
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="comments-preview-header">
                                            <a href="#" class="comments-preview text-decoration-none btn btn-outline-primary btn-lg w-100" data-preview-for="<?php echo $news['newsid']; ?>">
                                                <i class="fas fa-eye me-2"></i>View comments (<?php echo $comments_count; ?>) — 
                                                <span class="preview-text"><?php echo $preview_text; ?></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No articles found. Add your first article above!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <?php echo $site_name; ?> <?php echo date('Y'); ?></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize comments as hidden
            document.querySelectorAll('.comments-list').forEach(function(list) {
                list.classList.add('d-none');
            });
            
            // Toggle comments when preview clicked
            document.addEventListener('click', function(e) {
                if (e.target.closest('.comments-preview')) {
                    e.preventDefault();
                    const preview = e.target.closest('.comments-preview');
                    const articleId = preview.getAttribute('data-preview-for');
                    const container = document.querySelector(`.comments-container[data-article-id="${articleId}"]`);
                    const list = container.querySelector('.comments-list');
                    const header = container.querySelector('.comments-preview-header');
                    
                    list.classList.toggle('d-none');
                    header.style.display = list.classList.contains('d-none') ? 'block' : 'none';
                }

                // Close comments
                if (e.target.closest('.comments-close')) {
                    e.preventDefault();
                    const closeBtn = e.target.closest('.comments-close');
                    const articleId = closeBtn.getAttribute('data-close-for');
                    const container = document.querySelector(`.comments-container[data-article-id="${articleId}"]`);
                    const list = container.querySelector('.comments-list');
                    const header = container.querySelector('.comments-preview-header');
                    
                    list.classList.add('d-none');
                    header.style.display = 'block';
                }

                // Edit comment button
                if (e.target.closest('.edit-comment-btn')) {
                    e.preventDefault();
                    const editBtn = e.target.closest('.edit-comment-btn');
                    const commentId = editBtn.getAttribute('data-comment-id');
                    const commentContent = editBtn.getAttribute('data-comment-content');
                    const container = editBtn.closest('.comments-container');
                    const editForm = container.querySelector('.edit-comment-form');
                    
                    // Hide all other edit forms
                    document.querySelectorAll('.edit-comment-form').forEach(form => {
                        form.classList.add('d-none');
                    });
                    
                    // Show and populate edit form
                    editForm.classList.remove('d-none');
                    editForm.querySelector('#edit_comment_id').value = commentId;
                    editForm.querySelector('#edit_comment_content').value = commentContent;
                    
                    // Scroll to edit form
                    editForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                // Cancel edit
                if (e.target.closest('.cancel-edit')) {
                    e.preventDefault();
                    const cancelBtn = e.target.closest('.cancel-edit');
                    const editForm = cancelBtn.closest('.edit-comment-form');
                    editForm.classList.add('d-none');
                }
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Chrome-Style Customization Functionality
            // Theme selection
            document.querySelectorAll('.chrome-theme-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.chrome-theme-option').forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    const theme = this.dataset.theme;
                    document.getElementById('selectedTheme').value = theme;
                    
                    // Apply theme preview immediately
                    applyTheme(theme);
                });
            });

            // Function to apply theme
            function applyTheme(theme) {
                const body = document.body;
                
                if (theme === 'dark') {
                    body.classList.add('dark-theme');
                    updateNavbarForDarkTheme();
                } else if (theme === 'light') {
                    body.classList.remove('dark-theme');
                    updateNavbarForLightTheme();
                } else if (theme === 'auto') {
                    // Auto theme - detect system preference
                    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        body.classList.add('dark-theme');
                        updateNavbarForDarkTheme();
                    } else {
                        body.classList.remove('dark-theme');
                        updateNavbarForLightTheme();
                    }
                }
                
                // Update CSS variables for colors
                updateCSSVariables();
            }

            // Function to update navbar for dark theme
            function updateNavbarForDarkTheme() {
                const sidebar = document.querySelector('.sidebar');
                const topbar = document.querySelector('.navbar-light');
                
                if (sidebar) {
                    sidebar.classList.remove('bg-gradient-primary');
                    sidebar.classList.add('bg-dark');
                }
                
                if (topbar) {
                    topbar.classList.remove('bg-white');
                    topbar.classList.add('bg-dark');
                }
            }

            // Function to update navbar for light theme
            function updateNavbarForLightTheme() {
                const sidebar = document.querySelector('.sidebar');
                const topbar = document.querySelector('.navbar-light');
                
                if (sidebar) {
                    sidebar.classList.add('bg-gradient-primary');
                    sidebar.classList.remove('bg-dark');
                }
                
                if (topbar) {
                    topbar.classList.add('bg-white');
                    topbar.classList.remove('bg-dark');
                }
            }

            // Function to update CSS variables when colors change
            function updateCSSVariables() {
                const primaryColor = document.querySelector('input[name="primary_color"]').value;
                const secondaryColor = document.querySelector('input[name="secondary_color"]').value;
                
                document.documentElement.style.setProperty('--primary-color', primaryColor);
                document.documentElement.style.setProperty('--secondary-color', secondaryColor);
            }

            // Color picker live preview
            document.querySelectorAll('.chrome-color-input').forEach(input => {
                input.addEventListener('input', function() {
                    updateCSSVariables();
                });
            });

            // Apply current theme on page load
            const currentTheme = '<?php echo $theme; ?>';
            applyTheme(currentTheme);

            // Listen for system theme changes when auto theme is selected
            if (window.matchMedia) {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                mediaQuery.addEventListener('change', function(e) {
                    const currentThemeSetting = document.getElementById('selectedTheme').value;
                    if (currentThemeSetting === 'auto') {
                        applyTheme('auto');
                    }
                });
            }
        });
    </script>
    <script>
    // Validation helpers
    function markValidity(input, ok) {
        if (!input) return false;
        const msg = document.getElementById(input.id + 'Error');
        input.classList.toggle('is-valid', !!ok);
        input.classList.toggle('is-invalid', !ok);
        if (msg) msg.style.display = ok ? 'none' : 'block';
        return !!ok;
    }

    function validateMinLength(input, min) {
        if (!input) return false;
        const val = (input.value || '').trim();
        return markValidity(input, val.length >= min);
    }

    function validateSelectNotEmpty(selectEl) {
        if (!selectEl) return false;
        const ok = (selectEl.value || '').trim() !== '';
        return markValidity(selectEl, ok);
    }

    function attachFieldValidation(input, min, evt) {
        if (!input) return;
        input.addEventListener(evt || 'input', () => validateMinLength(input, min));
    }

    function attachSelectValidation(selectEl) {
        if (!selectEl) return;
        selectEl.addEventListener('change', () => validateSelectNotEmpty(selectEl));
    }

    // Attach to Add form fields
    const addTitle = document.getElementById('articleTitle');
    const addCategory = document.getElementById('articleCategory');
    const addContent = document.getElementById('articleContent');
    const addImage = document.getElementById('articleImage');
    attachFieldValidation(addTitle, 3);
    attachSelectValidation(addCategory);
    attachFieldValidation(addContent, 10);
    if (addImage) {
        addImage.addEventListener('change', () => {
            const ok = addImage.files && addImage.files.length > 0;
            markValidity(addImage, ok);
        });
    }

    // Attach to Edit form fields
    const editTitle = document.getElementById('editArticleTitle');
    const editCategory = document.getElementById('editArticleCategory');
    const editContent = document.getElementById('editArticleContent');
    attachFieldValidation(editTitle, 3);
    attachSelectValidation(editCategory);
    attachFieldValidation(editContent, 10);

    // Attach submit handlers for both forms if present
    const addForm = document.getElementById('addArticleForm');
    const editForm = document.querySelector('form input[name="update_id"]') ? document.querySelector('form input[name="update_id"]').closest('form') : null;

    function validateForm(isEdit) {
        if (isEdit) {
            const okT = validateMinLength(editTitle, 3);
            const okC = validateSelectNotEmpty(editCategory);
            const okB = validateMinLength(editContent, 10);
            return okT && okC && okB;
        } else {
            const okT = validateMinLength(addTitle, 3);
            const okC = validateSelectNotEmpty(addCategory);
            const okB = validateMinLength(addContent, 10);
            const okImg = addImage ? markValidity(addImage, addImage.files && addImage.files.length > 0) : true;
            return okT && okC && okB && okImg;
        }
    }

    if (addForm) {
        addForm.addEventListener('submit', (e) => {
            if (!validateForm(false)) {
                e.preventDefault();
                alert('Please correct highlighted inputs');
            }
        });
    }

    if (editForm) {
        editForm.addEventListener('submit', (e) => {
            if (!validateForm(true)) {
                e.preventDefault();
                alert('Please correct highlighted inputs');
            }
        });
    }
    </script>

</body>
</html>