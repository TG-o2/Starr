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
    
    // Validate required fields
    if (empty($title) || empty($content) || empty($category)) {
        $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Please fill in all required fields!
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
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .article-card {
            background: white;
            border-left: 4px solid #004085;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .article-card img {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
        }
        .article-card h5 {
            color: #004085;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .comments-container {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            border-left: 4px solid #004085;
            box-shadow: 0 4px 12px rgba(0, 64, 133, 0.15);
            margin-top: 20px;
            padding: 30px;
            border-radius: 8px;
        }
        .comments-container h5 {
            color: #004085;
            font-weight: 600;
        }
        .comments-preview-header {
            padding: 20px 0 0 0;
        }
        .comments-preview {
            border: 2px solid #004085 !important;
            color: #004085 !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .comments-preview:hover {
            background-color: #004085 !important;
            color: white !important;
        }
        .comment {
            background: white;
            padding: 16px;
            border-radius: 8px;
            border-left: 3px solid #ffc107;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            margin-bottom: 16px;
        }
        .comment strong {
            color: #004085;
        }
        .comment div:last-child {
            color: #333;
            line-height: 1.6;
            margin-top: 8px;
        }
        .comment-form input {
            border: 2px solid #004085 !important;
            font-size: 16px;
        }
        .comment-form button {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .comments-close {
            font-size: 20px;
            cursor: pointer;
            color: #999;
        }
        .comments-close:hover {
            color: #004085 !important;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .action-buttons {
            margin-top: 10px;
        }
        .update-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin-top: 20px;
        }
        .comment-actions {
            margin-top: 10px;
            display: flex;
            gap: 5px;
        }
        .edit-comment-form {
            background: #f8f9fa;
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
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
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
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
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
                                               value="<?php echo htmlspecialchars($editNews['title']); ?>" required>
                                    </div>
                                    <div class="form-group required">
                                        <label for="editArticleCategory">Category</label>
                                        <select class="form-control" id="editArticleCategory" name="category" required>
                                            <option value="">Select a category</option>
                                            <option value="community" <?php echo ($editNews['category'] == 'community') ? 'selected' : ''; ?>>Community</option>
                                            <option value="education" <?php echo ($editNews['category'] == 'education') ? 'selected' : ''; ?>>Education</option>
                                            <option value="events" <?php echo ($editNews['category'] == 'events') ? 'selected' : ''; ?>>Events</option>
                                            <option value="sports" <?php echo ($editNews['category'] == 'sports') ? 'selected' : ''; ?>>Sports</option>
                                            <option value="technology" <?php echo ($editNews['category'] == 'technology') ? 'selected' : ''; ?>>Technology</option>
                                            <option value="health" <?php echo ($editNews['category'] == 'health') ? 'selected' : ''; ?>>Health</option>
                                            <option value="business" <?php echo ($editNews['category'] == 'business') ? 'selected' : ''; ?>>Business</option>
                                        </select>
                                    </div>
                                    <div class="form-group required">
                                        <label for="editArticleContent">Content</label>
                                        <textarea class="form-control" id="editArticleContent" name="content" rows="5" required><?php echo htmlspecialchars($editNews['content']); ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-warning">Update Article</button>
                                    <a href="backoffice.php" class="btn btn-secondary">Cancel</a>
                                </form>
                            <?php else: ?>
                                <!-- Add Form -->
                                <form method="POST" enctype="multipart/form-data" id="addArticleForm">
                                    <div class="form-group">
                                        <label for="articleImage">Article Image</label>
                                        <input class="form-control" type="file" id="articleImage" name="image">
                                    </div>
                                    <div class="form-group required">
                                        <label for="articleTitle">Title</label>
                                        <input type="text" class="form-control" id="articleTitle" name="title" placeholder="Enter article title" required>
                                    </div>
                                    <div class="form-group required">
                                        <label for="articleCategory">Category</label>
                                        <select class="form-control" id="articleCategory" name="category" required>
                                            <option value="">Select a category</option>
                                            <option value="community">Community</option>
                                            <option value="education">Education</option>
                                            <option value="events">Events</option>
                                            <option value="sports">Sports</option>
                                            <option value="technology">Technology</option>
                                            <option value="health">Health</option>
                                            <option value="business">Business</option>
                                        </select>
                                    </div>
                                    <div class="form-group required">
                                        <label for="articleContent">Content</label>
                                        <textarea class="form-control" id="articleContent" name="content" rows="5" placeholder="Enter article content" required></textarea>
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
                                                    <input type="text" class="form-control comment-input" name="comment_content" placeholder="Write a comment..." required>
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
                                                        <textarea class="form-control" id="edit_comment_content" name="comment_content" rows="3" required></textarea>
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
                        <span>Copyright &copy; Your Website 2024</span>
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
        });
    </script>
</body>
</html>