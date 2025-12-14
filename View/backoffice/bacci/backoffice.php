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

// ============================================
// HANDLE ALL COMMENT OPERATIONS IN ONE PLACE
// ============================================

// Handle ADD comment operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    $news_id = $_POST['news_id'] ?? '';
    $comment_content = trim($_POST['comment_content'] ?? '');

    if (!empty($news_id) && !empty($comment_content) && ctype_digit($news_id) && (int)$news_id > 0) {
        $comment = new Comments();
        $comment->setNewsId((int)$news_id);
        $comment->setContent($comment_content);
        $comment->setCreatedAt(date('Y-m-d H:i:s'));

        $result = $commentController->addComment($comment);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Comment added successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding comment!']);
        }
        exit;
    }
}

// Handle UPDATE comment operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_comment'])) {
    $comment_id = $_POST['comment_id'] ?? '';
    $comment_content = trim($_POST['comment_content'] ?? '');
    
    if (!empty($comment_id) && !empty($comment_content) && ctype_digit($comment_id)) {
        $result = $commentController->updateComment($comment_id, $comment_content);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Comment updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating comment!']);
        }
        exit;
    }
}

// Handle DELETE comment operation (via POST for AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'] ?? '';
    
    if (!empty($comment_id) && ctype_digit($comment_id)) {
        $result = $commentController->deleteComment($comment_id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Comment deleted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting comment!']);
        }
        exit;
    }
}

// Handle GET DELETE comment operation (for direct links)
if (isset($_GET['delete_comment_id'])) {
    $result = $commentController->deleteComment($_GET['delete_comment_id']);
    
    if ($result) {
        $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Comment deleted successfully!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
    } else {
        $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error deleting comment!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
    }
    echo '<script>setTimeout(function(){ window.location.href = "backoffice.php"; }, 1000);</script>';
}

// ============================================
// FETCH COMMENTS FOR MODAL
// ============================================
if (isset($_POST['action']) && $_POST['action'] === 'fetch_comments' && !empty($_POST['news_id'])) {
    $news_id = (int)$_POST['news_id'];
    $comments = $commentController->getCommentsByNewsId($news_id);
    $current_news_id = $news_id;

    if (empty($comments)) {
        echo '<div class="text-center py-5 text-muted">
                <i class="fas fa-comment-slash fa-3x mb-3 opacity-50"></i>
                <p class="text-muted">No comments yet. Be the first to comment!</p>
              </div>';
        
        // Add comment form
        echo '
        <div class="comment-form mt-4 p-4 rounded-lg" style="background: var(--bg-secondary);">
            <h6 class="mb-3" style="color: var(--text-primary); font-weight: 600;">
                <i class="fas fa-plus-circle me-2" style="color: var(--primary-color);"></i>
                Add a Comment
            </h6>
            <form id="addCommentForm" data-news-id="' . $news_id . '">
                <div class="form-group">
                    <textarea class="modern-form-control" name="comment_content" rows="3" placeholder="Share your thoughts..." required style="resize: none; min-height: 100px;"></textarea>
                </div>
                <button type="submit" class="btn-modern btn-modern-primary">
                    <i class="fas fa-paper-plane me-2"></i>
                    Post Comment
                </button>
            </form>
        </div>';
        exit;
    }

    echo '<div class="comments-list">';
    foreach ($comments as $c) {
        $commentId = $c['id'] ?? $c['comment_id'] ?? $c['commentid'] ?? $c['CommentId'] ?? 0;
        $content   = htmlspecialchars($c['content'] ?? '');
        $date      = !empty($c['created_at']) ? (new DateTime($c['created_at']))->format('M j, Y \a\t g:i A') : 'Unknown date';
        
        echo '
        <div class="comment-item mb-4 p-4 rounded-lg" style="background: var(--bg-primary); border-left: 4px solid var(--primary-color);" data-comment-id="' . $commentId . '">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center">
                    <div class="user-avatar me-3" style="width: 40px; height: 40px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user" style="color: var(--primary-color);"></i>
                    </div>
                    <div>
                        <strong style="color: var(--text-primary); font-weight: 600;">User #' . $commentId . '</strong>
                        <div class="text-muted small mt-1">
                            <i class="far fa-clock me-1"></i>' . $date . '
                        </div>
                    </div>
                </div>
                <div class="comment-actions">
                    <button class="btn btn-sm btn-outline-primary edit-comment-btn me-2" data-comment-id="' . $commentId . '" data-content="' . htmlspecialchars($content, ENT_QUOTES) . '">
                        <i class="fas fa-edit fa-xs"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-comment-btn" data-comment-id="' . $commentId . '">
                        <i class="fas fa-trash fa-xs"></i>
                    </button>
                </div>
            </div>
            
            <div class="comment-content ps-4" id="comment-content-' . $commentId . '" style="color: var(--text-primary); line-height: 1.6; position: relative;">
                <div class="comment-text">' . nl2br($content) . '</div>
            </div>
            
            <!-- Edit Form (Hidden by default) -->
            <div class="edit-comment-form mt-3 d-none" id="edit-form-' . $commentId . '">
                <textarea class="modern-form-control mb-2" id="edit-text-' . $commentId . '" rows="3" style="resize: none; min-height: 100px;">' . htmlspecialchars($content) . '</textarea>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary save-edit-btn" data-comment-id="' . $commentId . '">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                    <button class="btn btn-sm btn-outline-secondary cancel-edit-btn" data-comment-id="' . $commentId . '">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                </div>
            </div>
        </div>';
    }
    echo '</div>';
    
    // Add comment form
    echo '
    <div class="comment-form mt-4 p-4 rounded-lg" style="background: var(--bg-secondary); border-top: 1px solid var(--border-color);">
        <h6 class="mb-3" style="color: var(--text-primary); font-weight: 600;">
            <i class="fas fa-plus-circle me-2" style="color: var(--primary-color);"></i>
            Add a Comment
        </h6>
        <form id="addCommentForm" data-news-id="' . $news_id . '">
            <div class="form-group">
                <textarea class="modern-form-control" name="comment_content" rows="3" placeholder="Share your thoughts..." required style="resize: none; min-height: 100px;"></textarea>
            </div>
            <button type="submit" class="btn-modern btn-modern-primary">
                <i class="fas fa-paper-plane me-2"></i>
                Post Comment
            </button>
        </form>
    </div>';
    exit;
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

// Statistics
$totalArticles = count($allNews);
$totalComments = 0;
foreach ($allNews as $news) {
    $comments = $commentController->getCommentsByNewsId($news['newsid']);
    $totalComments += count($comments);
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
    <title>News Management Dashboard</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: <?php echo $primary_color; ?>;
            --primary-light: <?php echo $primary_color; ?>15;
            --secondary-color: <?php echo $secondary_color; ?>;
            --font-family: <?php echo $font_family; ?>;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius-lg: 16px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.dark-theme {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #1e293b;
            --border-color: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* Modern Card Design */
        .modern-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .modern-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
        }

        .modern-card-header {
            background: linear-gradient(135deg, var(--primary-light) 0%, transparent 100%);
            border-bottom: 1px solid var(--border-color);
            padding: 1.75rem 2rem;
        }

        .modern-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .modern-card-title i {
            color: var(--primary-color);
        }

        .modern-card-body {
            padding: 2rem;
        }

        /* Form Improvements */
        .form-group.required label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }

        .form-group.required label:after {
            content: " *";
            color: #ef4444;
        }

        .modern-form-control {
            background: var(--bg-primary);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1rem 1.25rem;
            font-size: 1rem;
            color: var(--text-primary);
            transition: var(--transition);
        }

        .modern-form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary-color) 20%, transparent);
            background: var(--bg-primary);
            transform: translateY(-1px);
        }

        .modern-form-control::placeholder {
            color: var(--text-muted);
        }

        .form-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }

        /* Button Improvements */
        .btn-modern {
            padding: 0.875rem 1.75rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            border: 2px solid transparent;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 80%, #000) 100%);
            color: white;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px color-mix(in srgb, var(--primary-color) 30%, transparent);
            color: white;
        }

        .btn-modern-primary:active {
            transform: translateY(0);
        }

        .btn-modern-outline {
            background: transparent;
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .btn-modern-outline:hover {
            background: var(--bg-secondary);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Article List Improvements */
        .article-grid {
            display: grid;
            gap: 2rem;
        }

        .article-item {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: var(--transition);
        }

        .article-item:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .article-header {
            padding: 1.75rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .article-image {
            width: 120px;
            height: 100px;
            object-fit: cover;
            border-radius: var(--radius-md);
            flex-shrink: 0;
            transition: var(--transition);
        }

        .article-item:hover .article-image {
            transform: scale(1.05);
        }

        .article-content {
            flex: 1;
            min-width: 0;
        }

        .article-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.75rem 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .article-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .article-date {
            font-size: 0.875rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .article-category {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.375rem 1rem;
            background: var(--primary-light);
            color: var(--primary-color);
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .article-excerpt {
            font-size: 1rem;
            color: var(--text-secondary);
            line-height: 1.7;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .article-footer {
            padding: 1.5rem 1.75rem;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .article-actions {
            display: flex;
            gap: 1rem;
        }

        .article-comments {
            font-size: 0.875rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .article-comments i {
            color: var(--primary-color);
        }

        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--bg-card) 0%, color-mix(in srgb, var(--bg-card) 80%, transparent) 100%);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 2rem;
            text-align: center;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            transition: var(--transition);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-icon i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        /* Alert Improvements */
        .modern-alert {
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            backdrop-filter: blur(10px);
        }

        .modern-alert i {
            font-size: 1.5rem;
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        .modern-alert-content {
            flex: 1;
        }

        .modern-alert-title {
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            font-size: 1.125rem;
        }

        .modern-alert-message {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        /* Tabs Navigation */
        .modern-tabs {
            display: flex;
            gap: 0.5rem;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 1px;
            margin-bottom: 3rem;
        }

        .modern-tab {
            padding: 1rem 2rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-md) var(--radius-md) 0 0;
            transition: var(--transition);
            border: 2px solid transparent;
            border-bottom: none;
            background: transparent;
            position: relative;
        }

        .modern-tab:hover {
            color: var(--primary-color);
            background: var(--primary-light);
        }

        .modern-tab.active {
            color: var(--primary-color);
            border-color: var(--border-color);
            border-bottom-color: var(--bg-card);
            background: var(--bg-card);
            position: relative;
            bottom: -2px;
        }

        .modern-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px 3px 0 0;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state-icon {
            width: 96px;
            height: 96px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            transition: var(--transition);
        }

        .empty-state-icon i {
            font-size: 2.5rem;
            color: var(--primary-color);
        }

        .empty-state-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 1rem 0;
        }

        .empty-state-description {
            color: var(--text-secondary);
            max-width: 500px;
            margin: 0 auto 2rem;
            line-height: 1.7;
            font-size: 1.125rem;
        }

        /* Comments Modal - Enhanced Design */
        .comments-modal .modal-content {
            border-radius: var(--radius-lg);
            border: none;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .comments-modal .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 2rem;
            color: white;
        }

        .comments-modal .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .comments-modal .modal-body {
            padding: 0;
            max-height: 70vh;
            overflow-y: auto;
        }

        .comments-container {
            padding: 2rem;
        }

        .comment-item {
            background: var(--bg-primary);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .comment-item:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-sm);
            transform: translateY(-2px);
        }

        .comment-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
            border-radius: 4px 0 0 4px;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-light), color-mix(in srgb, var(--primary-light) 50%, transparent));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--primary-color);
            flex-shrink: 0;
        }

        .comment-info {
            flex: 1;
            margin-left: 1rem;
        }

        .comment-author {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1.125rem;
            margin: 0 0 0.25rem 0;
        }

        .comment-date {
            font-size: 0.875rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .comment-actions {
            display: flex;
            gap: 0.75rem;
        }

        .comment-actions .btn {
            padding: 0.5rem;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .comment-actions .btn:hover {
            transform: scale(1.1);
        }

        .comment-content {
            color: var(--text-primary);
            line-height: 1.7;
            margin-bottom: 0;
            padding-left: 1rem;
            position: relative;
        }

        .comment-content::before {
            content: '"';
            position: absolute;
            left: 0;
            top: 0;
            font-size: 2rem;
            color: var(--primary-light);
            line-height: 1;
        }

        .comment-form {
            background: var(--bg-secondary);
            border-radius: var(--radius-md);
            padding: 2rem;
            margin-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .comment-form h6 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .edit-comment-form {
            background: var(--bg-secondary);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            margin-top: 1rem;
            border: 1px solid var(--border-color);
        }

        .comment-text {
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* Empty Comments State */
        .empty-comments {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-comments i {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-comments p {
            color: var(--text-secondary);
            font-size: 1.125rem;
        }

        /* Loading Spinner */
        .loading-spinner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
        }

        .loading-spinner .spinner {
            width: 48px;
            height: 48px;
            border: 3px solid var(--border-color);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive Improvements */
        @media (max-width: 768px) {
            .article-header {
                flex-direction: column;
            }
            
            .article-image {
                width: 100%;
                height: 200px;
            }
            
            .article-meta {
                flex-wrap: wrap;
            }
            
            .article-footer {
                flex-direction: column;
                gap: 1.5rem;
                align-items: stretch;
            }
            
            .article-actions {
                justify-content: center;
            }
            
            .modern-tabs {
                flex-direction: column;
            }
            
            .modern-tab {
                text-align: center;
            }
            
            .comment-header {
                flex-direction: column;
                gap: 1rem;
            }
            
            .comment-info {
                margin-left: 0;
            }
            
            .comment-actions {
                align-self: flex-end;
            }
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
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .customize-btn:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 12px 40px rgba(0,0,0,0.3);
        }

        /* Chrome-Style Customization Modal */
        .chrome-customize-modal .modal-content {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            border: none;
        }

        .chrome-customize-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 40px;
            color: white;
            text-align: center;
        }

        .chrome-customize-title {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 12px 0;
        }

        .chrome-customize-subtitle {
            font-size: 18px;
            opacity: 0.9;
            margin: 0;
            font-weight: 400;
        }

        .chrome-customize-body {
            padding: 0;
            max-height: 65vh;
            overflow-y: auto;
        }

        .chrome-customize-section {
            padding: 36px;
            border-bottom: 1px solid var(--border-color);
        }

        .chrome-customize-section:last-child {
            border-bottom: none;
        }

        .chrome-section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .chrome-section-title i {
            color: var(--primary-color);
        }

        .chrome-theme-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
        }

        .chrome-theme-option {
            border: 2px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--surface-color);
            position: relative;
            overflow: hidden;
        }

        .chrome-theme-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        }

        .chrome-theme-option.active {
            border-color: var(--primary-color);
            background: var(--surface-color);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }

        .chrome-theme-option.active::before {
            content: '✓';
            position: absolute;
            top: 16px;
            right: 16px;
            width: 28px;
            height: 28px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
        }

        .chrome-theme-preview {
            width: 100%;
            height: 120px;
            border-radius: 16px;
            margin-bottom: 20px;
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
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
        }

        .chrome-theme-description {
            font-size: 15px;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .chrome-color-picker {
            display: flex;
            gap: 24px;
            align-items: center;
            margin-bottom: 28px;
        }

        .chrome-color-input {
            width: 80px;
            height: 50px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .chrome-color-input:hover {
            transform: scale(1.05);
        }

        .chrome-color-label {
            flex: 1;
        }

        .chrome-color-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .chrome-color-description {
            font-size: 15px;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .chrome-font-select {
            width: 100%;
            padding: 18px;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            font-size: 16px;
            background: var(--background-color);
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chrome-font-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(26, 115, 232, 0.2);
            transform: translateY(-2px);
        }

        .chrome-customize-footer {
            padding: 28px 36px;
            background: var(--surface-color);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chrome-btn {
            padding: 14px 36px;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chrome-btn-secondary {
            background: transparent;
            border: 2px solid var(--border-color);
            color: var(--text-primary);
        }

        .chrome-btn-secondary:hover {
            background: var(--surface-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .chrome-btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 6px 25px rgba(26, 115, 232, 0.3);
        }

        .chrome-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(26, 115, 232, 0.4);
        }

        .chrome-preview-card {
            background: var(--surface-color);
            border-radius: 16px;
            padding: 24px;
            border: 2px solid var(--border-color);
            margin-top: 20px;
        }

        .chrome-preview-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 12px;
        }

        .chrome-preview-text {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
        
        /* Success/Error Messages */
        .alert-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Badge for comment count */
        .comment-badge {
            background: var(--primary-color);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
            margin-left: 0.5rem;
        }
    </style>
</head>

<body id="page-top" class="theme-transition <?php echo $theme === 'dark' || ($theme === 'auto' && isset($_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME']) && $_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME'] === 'dark') ? 'dark-theme' : ''; ?>">
    <!-- Customization Button -->
    <button class="customize-btn" data-toggle="modal" data-target="#chromeCustomizeModal">
        <i class="fas fa-palette"></i>
    </button>

    <!-- Alert Toast Container -->
    <div id="alertToastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

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

                <div class="container-fluid px-4">
                    <!-- Page Header -->
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div>
                            <h1 class="h3 mb-2" style="color: var(--text-primary); font-weight: 800;">News Management Dashboard</h1>
                            <p class="text-muted" style="color: var(--text-secondary); font-size: 1.125rem;">Manage your articles, comments, and platform settings</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn-modern btn-modern-outline" data-toggle="modal" data-target="#chromeCustomizeModal">
                                <i class="fas fa-palette"></i>
                                Customize
                            </button>
                            <button class="btn-modern btn-modern-primary" data-toggle="modal" data-target="#helpModal">
                                <i class="fas fa-question-circle"></i>
                                Help
                            </button>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <h3 class="stat-number"><?php echo $totalArticles; ?></h3>
                            <p class="stat-label">Total Articles</p>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h3 class="stat-number"><?php echo $totalComments; ?></h3>
                            <p class="stat-label">Total Comments</p>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="stat-number"><?php echo $totalArticles > 0 ? ceil($totalComments / $totalArticles) : 0; ?></h3>
                            <p class="stat-label">Avg. Comments</p>
                        </div>
                    </div>

                    <?php if (isset($alert_message)): ?>
                        <?php 
                        $alert_class = strpos($alert_message, 'alert-success') !== false ? 'success' : 'danger';
                        $alert_icon = $alert_class === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
                        ?>
                        <div class="modern-alert" style="background: <?php echo $alert_class === 'success' ? 'var(--primary-light)' : 'color-mix(in srgb, #ef4444 10%, transparent)'; ?>; 
                            border-color: <?php echo $alert_class === 'success' ? 'var(--primary-color)' : '#ef4444'; ?>;">
                            <i class="<?php echo $alert_icon; ?>" style="color: <?php echo $alert_class === 'success' ? 'var(--primary-color)' : '#ef4444'; ?>;"></i>
                            <div class="modern-alert-content">
                                <h4 class="modern-alert-title" style="color: <?php echo $alert_class === 'success' ? 'var(--primary-color)' : '#ef4444'; ?>;">
                                    <?php echo $alert_class === 'success' ? 'Success!' : 'Error!'; ?>
                                </h4>
                                <p class="modern-alert-message" style="color: var(--text-primary);">
                                    <?php echo strip_tags(explode('<button', $alert_message)[0]); ?>
                                </p>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true" style="color: var(--text-muted);">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Tabs Navigation -->
                    <div class="modern-tabs">
                        <a href="#articles-form" class="modern-tab active" data-tab="form">
                            <i class="fas fa-<?php echo $editNews ? 'edit' : 'plus-circle'; ?> me-2"></i>
                            <?php echo $editNews ? 'Edit Article' : 'New Article'; ?>
                        </a>
                        <a href="#articles-list" class="modern-tab" data-tab="list">
                            <i class="fas fa-list-ul me-2"></i>
                            All Articles
                            <span class="comment-badge"><?php echo $totalArticles; ?></span>
                        </a>
                    </div>

                    <!-- Add/Update Article Form -->
                    <div id="articles-form" class="tab-content">
                        <div class="modern-card">
                            <div class="modern-card-header">
                                <h2 class="modern-card-title">
                                    <i class="fas fa-<?php echo $editNews ? 'edit' : 'plus-circle'; ?>"></i>
                                    <?php echo $editNews ? 'Update Article' : 'Create New Article'; ?>
                                </h2>
                                <?php if ($editNews): ?>
                                    <p class="text-muted mb-0" style="color: var(--text-secondary);">Editing: <?php echo htmlspecialchars($editNews['title']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="modern-card-body">
                                <?php if ($editNews): ?>
                                    <!-- Update Form -->
                                    <form method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="update_id" value="<?php echo $editNews['newsid']; ?>">
                                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($editNews['image']); ?>">
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group required mb-4">
                                                    <label for="editArticleImage">Article Image</label>
                                                    <?php if (!empty($editNews['image'])): ?>
                                                        <div class="mb-3">
                                                            <div class="image-preview" style="width: 100%; height: 200px; overflow: hidden; border-radius: var(--radius-md);">
                                                                <img src="/Cosmosweb/uploads/news/<?php echo htmlspecialchars($editNews['image']); ?>" 
                                                                     alt="Current image" 
                                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                                            </div>
                                                            <p class="text-muted small mt-2" style="color: var(--text-secondary);">Current image</p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="file-upload">
                                                        <input class="modern-form-control" type="file" id="editArticleImage" name="image">
                                                        <div class="form-text">Choose a new image or keep current</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group required mb-4">
                                                    <label for="editArticleTitle">Title</label>
                                                    <input type="text" class="modern-form-control" id="editArticleTitle" name="title" 
                                                           value="<?php echo htmlspecialchars($editNews['title']); ?>" 
                                                           placeholder="Enter article title">
                                                    <div class="form-text">Make it descriptive and engaging</div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group required mb-4">
                                                            <label for="editArticleCategory">Category</label>
                                                            <select class="modern-form-control" id="editArticleCategory" name="category">
                                                                <option value="">Select a category</option>
                                                                <option value="community" <?php echo ($editNews['category'] == 'community') ? 'selected' : ''; ?>>Community</option>
                                                                <option value="education" <?php echo ($editNews['category'] == 'education') ? 'selected' : ''; ?>>Education</option>
                                                                <option value="events" <?php echo ($editNews['category'] == 'events') ? 'selected' : ''; ?>>Events</option>
                                                                <option value="sports" <?php echo ($editNews['category'] == 'sports') ? 'selected' : ''; ?>>Sports</option>
                                                                <option value="technology" <?php echo ($editNews['category'] == 'technology') ? 'selected' : ''; ?>>Technology</option>
                                                                <option value="health" <?php echo ($editNews['category'] == 'health') ? 'selected' : ''; ?>>Health</option>
                                                                <option value="business" <?php echo ($editNews['category'] == 'business') ? 'selected' : ''; ?>>Business</option>
                                                            </select>
                                                            <div class="form-text">Choose the most relevant category</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group required mb-4">
                                                    <label for="editArticleContent">Content</label>
                                                    <textarea class="modern-form-control" id="editArticleContent" name="content" rows="6" 
                                                              placeholder="Write your article content here..."><?php echo htmlspecialchars($editNews['content']); ?></textarea>
                                                    <div class="form-text">Minimum 10 characters. Use Markdown for formatting.</div>
                                                </div>
                                                
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn-modern btn-modern-primary">
                                                        <i class="fas fa-save me-2"></i>
                                                        Update Article
                                                    </button>
                                                    <a href="backoffice.php" class="btn-modern btn-modern-outline">
                                                        <i class="fas fa-times me-2"></i>
                                                        Cancel
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <!-- Add Form -->
                                    <form method="POST" enctype="multipart/form-data" id="addArticleForm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group required mb-4">
                                                    <label for="articleImage">Article Image</label>
                                                    <div class="file-upload-area" style="border: 2px dashed var(--border-color); border-radius: var(--radius-md); padding: 2rem; text-align: center; cursor: pointer;"
                                                         onclick="document.getElementById('articleImage').click()">
                                                        <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: var(--text-muted);"></i>
                                                        <p class="mb-2" style="color: var(--text-primary); font-weight: 500;">Click to upload image</p>
                                                        <p class="small mb-0" style="color: var(--text-secondary);">JPG, PNG or GIF • Max 5MB</p>
                                                        <input class="d-none" type="file" id="articleImage" name="image" accept="image/*">
                                                    </div>
                                                    <div class="image-preview mt-3 d-none" id="imagePreview"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group required mb-4">
                                                    <label for="articleTitle">Title</label>
                                                    <input type="text" class="modern-form-control" id="articleTitle" name="title" 
                                                           placeholder="Enter a compelling title">
                                                    <div class="form-text">Minimum 3 characters. Make it catchy!</div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group required mb-4">
                                                            <label for="articleCategory">Category</label>
                                                            <select class="modern-form-control" id="articleCategory" name="category">
                                                                <option value="">Select a category</option>
                                                                <option value="community">Community</option>
                                                                <option value="education">Education</option>
                                                                <option value="events">Events</option>
                                                                <option value="sports">Sports</option>
                                                                <option value="technology">Technology</option>
                                                                <option value="health">Health</option>
                                                                <option value="business">Business</option>
                                                            </select>
                                                            <div class="form-text">Choose where this article belongs</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group required mb-4">
                                                    <label for="articleContent">Content</label>
                                                    <textarea class="modern-form-control" id="articleContent" name="content" rows="6" 
                                                              placeholder="Write your amazing content here..."></textarea>
                                                    <div class="form-text">Minimum 10 characters. Support for rich text coming soon!</div>
                                                </div>
                                                
                                                <button type="submit" class="btn-modern btn-modern-primary" id="addArticleBtn">
                                                    <i class="fas fa-plus-circle me-2"></i>
                                                    Publish Article
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Articles List -->
                    <div id="articles-list" class="tab-content d-none">
                        <div class="modern-card">
                            <div class="modern-card-header">
                                <h2 class="modern-card-title">
                                    <i class="fas fa-newspaper"></i>
                                    All Articles
                                </h2>
                                <p class="text-muted mb-0" style="color: var(--text-secondary);"><?php echo $totalArticles; ?> articles • <?php echo $totalComments; ?> comments</p>
                            </div>
                            <div class="modern-card-body">
                                <?php if (!empty($allNews)): ?>
                                    <div class="article-grid">
                                        <?php foreach ($allNews as $news): ?>
                                            <?php
                                            $comments = $commentController->getCommentsByNewsId($news['newsid']);
                                            $comments_count = count($comments);
                                            ?>
                                            
                                            <div class="article-item">
                                                <div class="article-header">
                                                    <?php if (!empty($news['image'])): ?>
                                                        <img src="/Cosmosweb/uploads/news/<?php echo htmlspecialchars($news['image']); ?>" 
                                                             alt="<?php echo htmlspecialchars($news['title']); ?>" 
                                                             class="article-image">
                                                    <?php else: ?>
                                                        <div class="article-image" style="background: var(--primary-light); display: flex; align-items: center; justify-content: center;">
                                                            <i class="fas fa-newspaper" style="color: var(--primary-color); font-size: 2rem;"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="article-content">
                                                        <h3 class="article-title"><?php echo htmlspecialchars($news['title']); ?></h3>
                                                        
                                                        <div class="article-meta">
                                                            <span class="article-date">
                                                                <i class="far fa-calendar me-1"></i>
                                                                <?php echo (new DateTime($news['published_date']))->format('M j, Y'); ?>
                                                            </span>
                                                            <?php if (!empty($news['category'])): ?>
                                                                <span class="article-category"><?php echo ucfirst($news['category']); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                        <p class="article-excerpt">
                                                            <?php echo strip_tags(substr($news['content'], 0, 150)); ?>...
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <div class="article-footer">
                                                    <div class="article-comments">
                                                        <i class="fas fa-comments"></i>
                                                        <span><?php echo $comments_count; ?> comment<?php echo $comments_count != 1 ? 's' : ''; ?></span>
                                                    </div>
                                                    
                                                    <div class="article-actions">
                                                        <a href="backoffice.php?edit_id=<?php echo $news['newsid']; ?>" 
                                                           class="btn-modern btn-modern-outline btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>
                                                        <button class="btn-modern btn-modern-outline btn-sm view-comments-btn"
                                                                data-article-id="<?php echo $news['newsid']; ?>"
                                                                data-title="<?php echo htmlspecialchars(addslashes($news['title'])); ?>"
                                                                data-toggle="modal" data-target="#commentsModal">
                                                            <i class="fas fa-comments"></i>
                                                            Comments
                                                            <?php if ($comments_count > 0): ?>
                                                                <span class="comment-badge"><?php echo $comments_count; ?></span>
                                                            <?php endif; ?>
                                                        </button>
                                                        <a href="backoffice.php?delete_id=<?php echo $news['newsid']; ?>" 
                                                           class="btn-modern btn-modern-outline btn-sm text-danger"
                                                           onclick="return confirm('Are you sure you want to delete this article? This action cannot be undone.')">
                                                            <i class="fas fa-trash"></i>
                                                            Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                        <h3 class="empty-state-title">No Articles Yet</h3>
                                        <p class="empty-state-description">
                                            You haven't created any articles yet. Start by clicking "New Article" above to create your first piece of content.
                                        </p>
                                        <button class="btn-modern btn-modern-primary" onclick="switchTab('form')">
                                            <i class="fas fa-plus-circle me-2"></i>
                                            Create First Article
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span style="color: var(--text-secondary); font-weight: 500;">Copyright &copy; <?php echo $site_name; ?> <?php echo date('Y'); ?></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Comments Modal -->
    <div class="modal fade comments-modal" id="commentsModal" tabindex="-1" role="dialog" aria-labelledby="commentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentsModalLabel">
                        <i class="fas fa-comments me-2"></i>
                        Comments
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="commentsModalBody">
                    <!-- Comments will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="modal-header" style="border-bottom-color: var(--border-color);">
                    <h5 class="modal-title" id="helpModalLabel" style="color: var(--text-primary);">
                        <i class="fas fa-question-circle me-2"></i>
                        Help & Tips
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: var(--text-muted);">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: var(--text-secondary);">
                    <h6 style="color: var(--text-primary); margin-bottom: 1rem;">Quick Tips</h6>
                    <ul style="padding-left: 1.5rem;">
                        <li>Use descriptive titles that capture attention</li>
                        <li>Always add a relevant image for better engagement</li>
                        <li>Categorize articles properly for organization</li>
                        <li>Use the preview feature before publishing</li>
                        <li>Regularly engage with comments</li>
                    </ul>
                </div>
            </div>
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
        // Tab switching
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            document.querySelectorAll('.modern-tab').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabName = this.dataset.tab;
                    document.querySelectorAll('.tab-content').forEach(t => t.classList.add('d-none'));
                    document.querySelectorAll('.modern-tab').forEach(t => t.classList.remove('active'));
                    document.getElementById('articles-' + tabName).classList.remove('d-none');
                    this.classList.add('active');
                });
            });

            // Image preview for new article
            const articleImage = document.getElementById('articleImage');
            const imagePreview = document.getElementById('imagePreview');
            if (articleImage && imagePreview) {
                articleImage.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.innerHTML = `
                                <div style="border: 2px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
                                    <img src="${e.target.result}" alt="Preview" style="width: 100%; height: 200px; object-fit: cover;">
                                </div>
                                <p class="small mt-2" style="color: var(--text-secondary);">Image preview</p>
                            `;
                            imagePreview.classList.remove('d-none');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Load comments when modal is shown
            $('#commentsModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const articleId = button.data('article-id');
                const articleTitle = button.data('title');
                
                // Update modal title
                $('#commentsModalLabel').html(`
                    <i class="fas fa-comments me-2"></i>
                    Comments: ${articleTitle}
                `);
                
                // Show loading
                $('#commentsModalBody').html(`
                    <div class="loading-spinner">
                        <div class="spinner"></div>
                        <p style="color: var(--text-secondary);">Loading comments...</p>
                    </div>
                `);
                
                // Fetch comments
                fetchComments(articleId);
            });

            // Function to fetch comments
            function fetchComments(articleId) {
                const formData = new FormData();
                formData.append('action', 'fetch_comments');
                formData.append('news_id', articleId);
                
                fetch('backoffice.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(html => {
                    $('#commentsModalBody').html(html);
                    
                    // Attach event listeners for comment actions
                    attachCommentListeners(articleId);
                })
                .catch(error => {
                    console.error('Error loading comments:', error);
                    $('#commentsModalBody').html(`
                        <div class="empty-comments">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Failed to load comments. Please try again.</p>
                        </div>
                    `);
                });
            }

            // Function to attach event listeners for comment actions
            function attachCommentListeners(articleId) {
                // Add comment form submission
                $('#addCommentForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const commentContent = form.find('textarea[name="comment_content"]').val().trim();
                    
                    if (!commentContent) {
                        showToast('error', 'Please enter a comment.');
                        return;
                    }
                    
                    const formData = new FormData(this);
                    formData.append('add_comment', '1');
                    formData.append('news_id', form.data('news-id'));
                    
                    // Show loading
                    const submitBtn = form.find('button[type="submit"]');
                    const originalText = submitBtn.html();
                    submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Posting...').prop('disabled', true);
                    
                    fetch('backoffice.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message);
                            form.trigger('reset');
                            fetchComments(articleId);
                        } else {
                            showToast('error', data.message);
                        }
                    })
                    .catch(() => {
                        showToast('error', 'Failed to add comment. Please try again.');
                    })
                    .finally(() => {
                        submitBtn.html(originalText).prop('disabled', false);
                    });
                });

                // Edit comment button
                $('.edit-comment-btn').off('click').on('click', function() {
                    const commentId = $(this).data('comment-id');
                    const content = $(this).data('content');
                    
                    // Hide content and show edit form
                    $('#comment-content-' + commentId).addClass('d-none');
                    $('#edit-form-' + commentId).removeClass('d-none');
                    
                    // Focus on textarea
                    $('#edit-text-' + commentId).focus();
                });

                // Cancel edit button
                $('.cancel-edit-btn').off('click').on('click', function() {
                    const commentId = $(this).data('comment-id');
                    
                    // Show content and hide edit form
                    $('#comment-content-' + commentId).removeClass('d-none');
                    $('#edit-form-' + commentId).addClass('d-none');
                });

                // Save edit button
                $('.save-edit-btn').off('click').on('click', function() {
                    const commentId = $(this).data('comment-id');
                    const newContent = $('#edit-text-' + commentId).val().trim();
                    
                    if (!newContent) {
                        showToast('error', 'Comment cannot be empty.');
                        return;
                    }
                    
                    const btn = $(this);
                    const originalText = btn.html();
                    btn.html('<i class="fas fa-spinner fa-spin me-1"></i>Saving...').prop('disabled', true);
                    
                    const formData = new FormData();
                    formData.append('update_comment', '1');
                    formData.append('comment_id', commentId);
                    formData.append('comment_content', newContent);
                    
                    fetch('backoffice.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message);
                            fetchComments(articleId);
                        } else {
                            showToast('error', data.message);
                            btn.html(originalText).prop('disabled', false);
                        }
                    })
                    .catch(() => {
                        showToast('error', 'Failed to update comment.');
                        btn.html(originalText).prop('disabled', false);
                    });
                });

                // Delete comment button
                $('.delete-comment-btn').off('click').on('click', function() {
                    const commentId = $(this).data('comment-id');
                    
                    if (!confirm('Are you sure you want to delete this comment? This action cannot be undone.')) {
                        return;
                    }
                    
                    const btn = $(this);
                    const originalText = btn.html();
                    btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
                    
                    const formData = new FormData();
                    formData.append('delete_comment', '1');
                    formData.append('comment_id', commentId);
                    
                    fetch('backoffice.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message);
                            fetchComments(articleId);
                        } else {
                            showToast('error', data.message);
                            btn.html(originalText).prop('disabled', false);
                        }
                    })
                    .catch(() => {
                        showToast('error', 'Failed to delete comment.');
                        btn.html(originalText).prop('disabled', false);
                    });
                });
            }

            // Function to show toast notifications
            function showToast(type, message) {
                const toastId = 'toast-' + Date.now();
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                const color = type === 'success' ? 'var(--primary-color)' : '#ef4444';
                
                const toastHtml = `
                    <div id="${toastId}" class="alert-toast">
                        <div class="alert alert-${type} alert-dismissible fade show" role="alert" 
                             style="border-radius: var(--radius-md); border: none; box-shadow: var(--shadow-md);">
                            <div class="d-flex align-items-center">
                                <i class="fas ${icon} me-3" style="font-size: 1.5rem; color: ${color};"></i>
                                <div style="flex: 1;">
                                    <strong style="color: var(--text-primary);">${type === 'success' ? 'Success!' : 'Error!'}</strong>
                                    <div style="color: var(--text-secondary);">${message}</div>
                                </div>
                                <button type="button" class="close ml-3" onclick="document.getElementById('${toastId}').remove()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#alertToastContainer').append(toastHtml);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    const toast = document.getElementById(toastId);
                    if (toast) toast.remove();
                }, 5000);
            }

            // Auto-hide alerts
            setTimeout(function() {
                $('.modern-alert').fadeOut();
            }, 5000);

            // Chrome-Style Customization Functionality
            document.querySelectorAll('.chrome-theme-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.chrome-theme-option').forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    const theme = this.dataset.theme;
                    document.getElementById('selectedTheme').value = theme;
                    
                    // Apply theme preview
                    applyTheme(theme);
                });
            });

            function applyTheme(theme) {
                const body = document.body;
                
                if (theme === 'dark') {
                    body.classList.add('dark-theme');
                } else if (theme === 'light') {
                    body.classList.remove('dark-theme');
                } else if (theme === 'auto') {
                    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        body.classList.add('dark-theme');
                    } else {
                        body.classList.remove('dark-theme');
                    }
                }
                
                // Update CSS variables for colors
                updateCSSVariables();
            }

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
            
            // Add switch tab function
            window.switchTab = function(tabName) {
                document.querySelectorAll('.tab-content').forEach(t => t.classList.add('d-none'));
                document.querySelectorAll('.modern-tab').forEach(t => t.classList.remove('active'));
                document.getElementById('articles-' + tabName).classList.remove('d-none');
                document.querySelector(`.modern-tab[data-tab="${tabName}"]`).classList.add('active');
            };
        });
    </script>
</body>
</html>