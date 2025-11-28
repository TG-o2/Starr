<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News — List</title>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="../kider-1.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="../kider-1.0.0/css/style.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
  <style>
    .page-header::before,
    .page-header::after {
      display: none !important;
    }
    
    .edit-comment-form {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    border-left: 3px solid #ffc107;
}
    .comments-container {
      border-left: 5px solid #0d6efd !important;
      box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;
    }
    .comments-container h5 {
      color: #0d6efd;
      font-weight: 600;
    }
    .comments-preview-header {
      padding: 20px 0 0 0;
    }
    .comments-preview {
      border: 2px solid #0d6efd !important;
      color: #0d6efd !important;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .comments-preview:hover {
      background-color: #0d6efd !important;
      color: white !important;
    }
    .comment {
      background: white;
      padding: 16px;
      border-radius: 8px;
      border-left: 3px solid #ffc107;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .comment strong {
      color: #0d6efd;
    }
    .comment div:last-child {
      color: #333;
      line-height: 1.6;
    }
    .comment-form input {
      border: 2px solid #0d6efd !important;
      font-size: 16px;
    }
    .comment-form button {
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    .comments-close {
      font-size: 20px;
      cursor: pointer;
    }
    .comments-close:hover {
      color: #0d6efd !important;
    }
    .action-buttons {
      position: absolute;
      top: 10px;
      right: 10px;
      display: flex;
      gap: 5px;
    }
    .action-btn {
      opacity: 0.7;
      transition: opacity 0.3s ease;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .action-btn:hover {
      opacity: 1;
    }
    .article-container {
      position: relative;
    }

    /* Instagram-style Notification Bell */
    .notification-bell {
      position: relative;
      background: none;
      border: none;
      font-size: 1.5rem;
      color: #6c757d;
      cursor: pointer;
      padding: 8px 12px;
      border-radius: 50%;
      transition: all 0.3s ease;
      margin-left: 15px;
    }

    .notification-bell:hover {
      background: #f8f9fa;
      color: #0d6efd;
    }

    .notification-bell.has-notifications {
      color: #0d6efd;
    }

    .notification-bell.has-notifications::after {
      content: '';
      position: absolute;
      top: 8px;
      right: 8px;
      width: 8px;
      height: 8px;
      background: #dc3545;
      border-radius: 50%;
      border: 2px solid white;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }

    /* Dropdown Notifications */
    .notifications-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      width: 400px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      border: 1px solid #e9ecef;
      z-index: 1000;
      display: none;
      margin-top: 10px;
    }

    .notifications-dropdown.show {
      display: block;
      animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .notifications-header {
      padding: 15px 20px;
      border-bottom: 1px solid #e9ecef;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .notifications-header h5 {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
    }

    .notifications-list {
      max-height: 400px;
      overflow-y: auto;
    }

    .notification-item {
      padding: 15px 20px;
      border-bottom: 1px solid #f8f9fa;
      cursor: pointer;
      transition: background 0.2s ease;
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }

    .notification-item:hover {
      background: #f8f9fa;
    }

    .notification-item:last-child {
      border-bottom: none;
    }

    .notification-item.unread {
      background: #f0f7ff;
    }

    .notification-icon {
      width: 36px;
      height: 36px;
      background: #0d6efd;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 14px;
      flex-shrink: 0;
    }

    .notification-icon.new-article {
      background: #28a745;
    }

    .notification-icon.update {
      background: #ffc107;
    }

    .notification-content {
      flex: 1;
    }

    .notification-content h6 {
      margin: 0 0 4px 0;
      font-size: 14px;
      font-weight: 600;
      color: #2c3e50;
    }

    .notification-content p {
      margin: 0;
      font-size: 13px;
      color: #6c757d;
      line-height: 1.4;
    }

    .notification-time {
      font-size: 11px;
      color: #adb5bd;
      margin-top: 4px;
    }

    .notifications-footer {
      padding: 12px 20px;
      border-top: 1px solid #e9ecef;
      text-align: center;
    }

    .notifications-footer a {
      color: #0d6efd;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
    }

    .notifications-footer a:hover {
      text-decoration: underline;
    }

    .empty-notifications {
      padding: 40px 20px;
      text-align: center;
      color: #6c757d;
    }

    .empty-notifications i {
      font-size: 2rem;
      margin-bottom: 10px;
      color: #dee2e6;
    }

    .notification-badge {
      position: absolute;
      top: 5px;
      right: 5px;
      background: #dc3545;
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      font-size: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }

    @keyframes shake {
      0%, 100% { transform: rotate(0); }
      25% { transform: rotate(-15deg); }
      75% { transform: rotate(15deg); }
    }

    /* Validation Styles */
    .is-valid {
      border-color: #198754 !important;
    }

    .is-invalid {
      border-color: #dc3545 !important;
    }

    .error-msg {
      color: #dc3545;
      font-size: 0.875em;
      margin-top: 0.25rem;
    }

    /* Reply Form Styles */
    .reply-form-container {
      margin-top: 10px;
      padding-left: 20px;
      border-left: 3px solid #e9ecef;
    }

    .reply-form {
      margin-top: 10px;
    }

    .reply-input {
      border: 1px solid #0d6efd !important;
      border-radius: 20px !important;
    }

    .cancel-reply {
      border-radius: 20px !important;
    }
    /* Make search input look better and more responsive */
.col-lg-4 .form-control[type="search"] {
    border-radius: 50px;
    padding-left: 1rem;
    border: 2px solid #0d6efd;
}

.col-lg-4 .btn-primary {
    border-radius: 50px;
    font-weight: 600;
}

/* Highlight matched text (optional cool effect) */
.article-container.search-highlight {
    animation: highlight 1s ease;
}

@keyframes highlight {
    0% { background-color: #fff3cd; }
    100% { background-color: transparent; }
}
  </style>
</head>
<body>
  <div class="container-xxl bg-white p-0">
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
      <h1 class="m-0 text-primary">
        <img src="../kider-1.0.0/img/starr.jpg" alt="Starr Logo" style="height: 45px; vertical-align: middle; margin-right: 8px;">
        Starr
      </h1>
      <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav mx-auto">
          <a href="../kider-1.0.0/index.php" class="nav-item nav-link">Home</a>
          <a href="../kider-1.0.0/gestionnews.php" class="nav-item nav-link">News</a>
          <a href="../kider-1.0.0/classes.html" class="nav-item nav-link">Classes</a>
          <a href="../kider-1.0.0/contact.html" class="nav-item nav-link">Contact</a>
        </div>
        <div class="d-flex align-items-center">
          <!-- Notification Bell -->
          <div class="notification-wrapper position-relative">
            <button class="notification-bell" id="notificationBell">
              <i class="fas fa-bell"></i>
            </button>
            <div class="notifications-dropdown" id="notificationsDropdown">
              <div class="notifications-header">
                <h5>Notifications</h5>
                <button class="btn btn-sm btn-outline-secondary" id="markAllRead">
                  <small>Tout marquer comme lu</small>
                </button>
              </div>
              <div class="notifications-list" id="notificationsList">
                <!-- Notifications will be loaded here -->
              </div>
              <div class="notifications-footer">
                <a href="#" id="viewAllNotifications">Voir toutes les notifications</a>
              </div>
            </div>
          </div>
          <a href="#" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Join Us<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
      </div>
    </nav>

    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5">
      <div class="owl-carousel header-carousel position-relative">
        <div class="owl-carousel-item position-relative">
          <img class="img-fluid" src="../kider-1.0.0/img/carousel-1.jpg" alt="" style="width: 100%; height: 500px; object-fit: cover;">
          <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(0, 0, 0, .2);">
            <div class="container">
              <div class="row justify-content-start">
                <div class="col-10 col-lg-8">
                  <h1 class="display-2 text-dark animated slideInDown mb-4">Latest News & Updates</h1>
                  <p class="fs-5 fw-medium text-dark mb-4 pb-2">Stay informed with the latest articles, events, and community updates from our school.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <main class="container">
      <?php
      require_once __DIR__ . '/../../../Controller/Config.php';
      require_once __DIR__ . '/../../../Controller/newsController.php';
      require_once __DIR__ . '/../../../Controller/commentController.php';
      $newsController = new NewsController();
      if (isset($_GET['delete_id'])) {
        $newsController = new NewsController();
        $result = $newsController->deleteNews($_GET['delete_id']);
        if ($result) {
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Article deleted successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
          echo '<script>setTimeout(function(){ window.location.href = "gestionnews.php"; }, 1000);</script>';
        } else {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting article!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
      }
      // Restore update article logic
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
        $newsController = new NewsController();
        $update_id = $_POST['update_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category = $_POST['category'];
        $image = $_POST['current_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
          $uploadDir = __DIR__ . '/../../../uploads/news/';
          if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
          $imageName = time() . '_' . basename($_FILES['image']['name']);
          $imagePath = $uploadDir . $imageName;
          if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) { $image = $imageName; }
        }
        // Enforce image mandatory on update as well (must have existing or new)
        if (empty($image)) {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Please choose an image before updating this article.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
          $result = $newsController->updateNews($update_id, $title, $content, $category, $image);
          if ($result) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Article updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            echo '<script>setTimeout(function(){ window.location.href = "gestionnews.php"; }, 1000);</script>';
          } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating article!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
          }
        }
      }
      // Restore add new article logic
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && !isset($_POST['update_id'])) {
        $newsController = new NewsController();
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category = $_POST['category'];
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
          $uploadDir = __DIR__ . '/../../../uploads/news/';
          if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
          $imageName = time() . '_' . basename($_FILES['image']['name']);
          $imagePath = $uploadDir . $imageName;
          if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) { $image = $imageName; }
        }
        // Enforce server-side: image is required for add (front office)
        if (!$image) {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Please choose an image to add an article.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
          $news = new News();
          $news->setTitle($title);
          $news->setContent($content);
          $now = date('Y-m-d H:i:s');
          $news->setPublished_date($now);
          $news->setUpdated_date($now); // ensure not null
          $news->setImage($image);
          $news->setStatus('published');
          $news->setTeacherid(1);
          $news->setCategory($category);
          $result = $newsController->addNews($news);
          if ($result) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Article added successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            echo '<script>setTimeout(function(){ window.location.href = "gestionnews.php"; }, 1000);</script>';
          } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding article!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
          }
        }
      }
      $allNews = $newsController->getAllNews();

      $editNews = null;
      if (isset($_GET['edit_id'])) {
        $editNews = $newsController->getNewsById($_GET['edit_id']);
      }
      ?>

      <?php // Flash banner for comment operations via GET params ?>
        <?php if (isset($_GET['comment_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          Comment posted successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
        <?php if (isset($_GET['reply_success'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            Reply posted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
      <?php if (isset($_GET['comment_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          Unable to process your comment. Please try again.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="row g-4">
        <div class="col-lg-8">
          <h2 class="section-title mb-5"><span>Latest</span> News</h2>
          <div class="row g-4">
            <?php
            // Collect unique categories once for sidebar
            $allCategories = [];
            foreach ($allNews as $n) {
              if (!empty($n['category']) && !in_array($n['category'], $allCategories)) {
                $allCategories[] = $n['category'];
              }
            }
            if (empty($allCategories)) { $allCategories = ['community','education','events']; }
            ?>
            <?php if (!empty($allNews)): ?>
              <?php foreach ($allNews as $news): ?>
                <?php
                $commentController = new CommentController();
                $comments = $commentController->getCommentsByNewsId($news['newsid']);
                $comments_count = count($comments);
                $preview_text = 'No comments yet';
                if ($comments_count > 0) {
                  $preview_text = '';
                  $preview_comments = array_slice($comments, 0, 2);
                  foreach ($preview_comments as $comment) {
                    $comment_text = htmlspecialchars($comment['content']);
                    if (strlen($comment_text) > 50) { $comment_text = substr($comment_text, 0, 50) . '...'; }
                    $preview_text .= '• ' . $comment_text . '<br>';
                  }
                  if ($comments_count > 2) { $preview_text .= 'and ' . ($comments_count - 2) . ' more comments...'; }
                }
                ?>
                <div class="col-12">
                  <div class="article-container">
                    <article class="bg-white rounded p-4 shadow-sm d-flex gap-3 align-items-start border-start border-4 border-primary">
                      <?php if (!empty($news['image'])): ?>
                        <img src="/Cosmosweb/uploads/news/<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" class="img-fluid rounded" style="width: 240px; height: 160px; object-fit: cover;">
                      <?php else: ?>
                        <img src="../kider-1.0.0/img/carousel-1.jpg" alt="Default news image" class="img-fluid rounded" style="width: 240px; height: 160px; object-fit: cover;">
                      <?php endif; ?>
                      <div class="flex-grow-1">
                        <h3 class="mb-1">
                          <a class="text-decoration-none text-dark" href="#">
                            <?php echo htmlspecialchars($news['title']); ?>
                          </a>
                        </h3>
                        <p class="meta text-muted small mb-2">
                          <?php $date = new DateTime($news['published_date']); echo $date->format('M j, Y'); ?>
                          <?php if (!empty($news['category'])): ?>
                            • <span class="badge bg-primary"><?php echo htmlspecialchars(ucfirst($news['category'])); ?></span>
                          <?php endif; ?>
                        </p>
                        <p class="mb-0">
                          <?php $content = strip_tags($news['content']); echo strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content; ?>
                        </p>
                      </div>
                    </article>
                    <div class="action-buttons mb-3">
                      <a href="gestionnews.php?edit_id=<?php echo $news['newsid']; ?>" class="btn btn-warning btn-sm" title="Edit Article"><i class="fas fa-edit"></i></a>
                      <a href="gestionnews.php?delete_id=<?php echo $news['newsid']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this article?')" title="Delete Article"><i class="fas fa-trash"></i></a>
                    </div>
                  </div>
                </div>
                <div class="col-12 mt-2">
                  <div class="bg-light rounded p-5 comments-container border-start border-4 border-primary" data-article-id="<?php echo $news['newsid']; ?>">
                    <div class="comments-list d-none" data-comments-for="<?php echo $news['newsid']; ?>">
                      <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Comments (<?php echo $comments_count; ?>)</h5>
                        <a href="#" class="comments-close text-decoration-none small text-muted" data-close-for="<?php echo $news['newsid']; ?>"><i class="fas fa-times"></i></a>
                      </div>
                      <div class="comments-content mb-4">
                        <?php if ($comments_count === 0): ?>
                          <div class="no-comments text-muted mb-4 text-center py-3">
                            <i class="fas fa-comment-slash fa-2x mb-2"></i>
                            <p class="mb-0">No comments yet. Be the first to comment!</p>
                          </div>
                        <?php else: ?>
                          <div class="comments-scrollable" style="max-height:400px;overflow-y:auto;">
                            <?php
                              // Group replies by parent comment via content marker REPLY_TO:{id}|text
                              $parents = [];
                              $replies = [];
                              foreach ($comments as $c) {
                                if (strpos($c['content'], 'REPLY_TO:') === 0) {
                                  // parse parent id
                                  $pipePos = strpos($c['content'], '|');
                                  $parentId = 0;
                                  $replyText = $c['content'];
                                  if ($pipePos !== false) {
                                    $meta = substr($c['content'], 0, $pipePos);
                                    $replyText = substr($c['content'], $pipePos + 1);
                                    $parts = explode(':', $meta);
                                    if (count($parts) === 2) { $parentId = (int)$parts[1]; }
                                  }
                                  $c['content'] = $replyText; // strip marker for display
                                  $replies[$parentId][] = $c;
                                } else {
                                  $parents[] = $c;
                                }
                              }
                            ?>
                            <?php foreach ($parents as $comment): ?>
                              <div class="comment-item card mb-3" data-comment-id="<?php echo $comment['id']; ?>">
                                <div class="card-body">
                                  <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-subtitle mb-1 text-primary">Anonymous</h6>
                                    <div class="btn-group btn-group-sm">
                                      <a href="#" class="btn btn-outline-secondary edit-comment" data-comment-id="<?php echo $comment['id']; ?>" data-comment-text="<?php echo htmlspecialchars($comment['content']); ?>" title="Edit"><i class="fas fa-edit"></i></a>
                                      <a href="#" class="btn btn-outline-danger delete-comment" data-comment-id="<?php echo $comment['id']; ?>" title="Delete"><i class="fas fa-trash"></i></a>
                                      <a href="#" class="btn btn-outline-primary reply-comment" data-comment-id="<?php echo $comment['id']; ?>" title="Reply"><i class="fas fa-reply"></i></a>
                                    </div>
                                  </div>
                                  <p class="card-text mb-0 comment-content"><?php echo htmlspecialchars($comment['content']); ?></p>
                                  <small class="text-muted"><?php $cd = new DateTime($comment['created_at']); echo $cd->format('M j, Y g:i A'); ?></small>
                                  <?php if (!empty($replies[$comment['id']])): ?>
                                    <div class="mt-3 ms-4">
                                      <?php foreach ($replies[$comment['id']] as $rc): ?>
                                        <div class="card mb-2 border-primary" data-comment-id="<?php echo $rc['id']; ?>">
                                          <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                              <h6 class="card-subtitle mb-1 text-primary">Anonymous replied</h6>
                                              <div class="btn-group btn-group-sm">
                                                <a href="#" class="btn btn-outline-secondary edit-comment" data-comment-id="<?php echo $rc['id']; ?>" data-comment-text="<?php echo htmlspecialchars($rc['content']); ?>" title="Edit"><i class="fas fa-edit"></i></a>
                                                <a href="#" class="btn btn-outline-danger delete-comment" data-comment-id="<?php echo $rc['id']; ?>" title="Delete"><i class="fas fa-trash"></i></a>
                                              </div>
                                            </div>
                                            <p class="mb-0"><?php echo htmlspecialchars($rc['content']); ?></p>
                                            <small class="text-muted"><?php $rd = new DateTime($rc['created_at']); echo $rd->format('M j, Y g:i A'); ?></small>
                                          </div>
                                        </div>
                                      <?php endforeach; ?>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>
                            <?php endforeach; ?>
                          </div>
                        <?php endif; ?>
                      </div>
                      <form class="comment-form" method="POST" action="addcomments.php">
                        <input type="hidden" name="news_id" value="<?php echo $news['newsid']; ?>">
                        <div class="input-group input-group-lg">
                          <input type="text" class="form-control comment-input" name="comment_content" placeholder="Write a comment...">
                          <button class="btn btn-primary comment-submit" type="submit"><i class="fas fa-paper-plane me-1"></i> Post</button>
                        </div>
                      </form>
                    </div>
                    <div class="comments-preview-header">
                      <a href="#" class="comments-preview text-decoration-none btn btn-outline-primary btn-lg w-100" data-preview-for="<?php echo $news['newsid']; ?>">
                        <i class="fas fa-eye me-2"></i>View comments (<?php echo $comments_count; ?>) — <span class="preview-text"><?php echo $preview_text; ?></span>
                      </a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="col-12">
                <div class="bg-white rounded p-4 text-center">
                  <p class="text-muted mb-0">No news articles found. Be the first to add one!</p>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="bg-white rounded p-4 shadow-sm mb-4">
            <h5 class="mb-3">Search</h5>
            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Search articles" aria-label="Search">
              <button class="btn btn-primary" type="submit">Search</button>
            </form>
          </div>
          <div class="bg-white rounded p-4 shadow-sm">
            <h5 class="mb-3">Categories</h5>
            <ul class="list-unstyled mb-0 small">
              <?php foreach ($allCategories as $cat): ?>
                <li><a href="#" class="text-decoration-none"><?php echo htmlspecialchars(ucfirst($cat)); ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>

      <?php if ($editNews): ?>
        <div class="row mt-5">
          <div class="col-12">
            <div class="bg-white rounded p-4 shadow-sm">
              <h2 class="section-title mb-4">Update Article</h2>
              <form action="gestionnews.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_id" value="<?php echo $editNews['newsid']; ?>">
                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($editNews['image']); ?>">
                
                <div class="mb-3">
                  <label for="editArticleImage" class="form-label">Article Image</label>
                  <?php if (!empty($editNews['image'])): ?>
                    <div class="mb-2">
                      <img src="<?php echo htmlspecialchars($editNews['image']); ?>" alt="Current image" style="max-width: 200px; height: auto;" class="img-thumbnail">
                      <p class="text-muted small mb-0">Existing image above. You must keep it or choose a new one.</p>
                    </div>
                  <?php endif; ?>
                  <input class="form-control" type="file" id="editArticleImage" name="image">
                  <small id="editArticleImageHelp" class="form-text text-muted">An image is mandatory. Select a new one if replacing.</small>
                  <small id="editArticleImageError" class="text-danger d-none">Image required: keep existing or choose a file.</small>
                </div>
                <div class="mb-3">
                  <label for="editArticleTitle" class="form-label">Title</label>
                  <input type="text" class="form-control" id="editArticleTitle" name="title" 
                         value="<?php echo htmlspecialchars($editNews['title']); ?>">
                  <small id="editArticleTitleHelp" class="form-text text-muted">Enter a clear, descriptive title (min 3 characters).</small>
                  <small id="editArticleTitleError" class="text-danger d-none">Title must be at least 3 characters.</small>
                </div>
                <div class="mb-3">
                  <label for="editArticleCategory" class="form-label">Category</label>
                  <select class="form-select" id="editArticleCategory" name="category">
                    <option value="">Select a category</option>
                    <option value="community" <?php echo ($editNews['category'] == 'community') ? 'selected' : ''; ?>>Community</option>
                    <option value="education" <?php echo ($editNews['category'] == 'education') ? 'selected' : ''; ?>>Education</option>
                    <option value="events" <?php echo ($editNews['category'] == 'events') ? 'selected' : ''; ?>>Events</option>
                    <option value="sports" <?php echo ($editNews['category'] == 'sports') ? 'selected' : ''; ?>>Sports</option>
                    <option value="technology" <?php echo ($editNews['category'] == 'technology') ? 'selected' : ''; ?>>Technology</option>
                    <option value="health" <?php echo ($editNews['category'] == 'health') ? 'selected' : ''; ?>>Health</option>
                    <option value="business" <?php echo ($editNews['category'] == 'business') ? 'selected' : ''; ?>>Business</option>
                  </select>
                  <small id="editArticleCategoryHelp" class="form-text text-muted">Choose the most relevant category.</small>
                  <small id="editArticleCategoryError" class="text-danger d-none">Please select a category.</small>
                </div>
                
                <div class="mb-3">
                  <label for="editArticleContent" class="form-label">Content</label>
                  <textarea class="form-control" id="editArticleContent" name="content" rows="5"><?php echo htmlspecialchars($editNews['content']); ?></textarea>
                  <small id="editArticleContentHelp" class="form-text text-muted">Write the full article content (min 20 characters).</small>
                  <small id="editArticleContentError" class="text-danger d-none">Content must be at least 20 characters.</small>
                </div>
                <button type="submit" class="btn btn-warning">Update Article</button>
                <a href="gestionnews.php" class="btn btn-secondary">Cancel</a>
              </form>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="row mt-5">
          <div class="col-12">
            <div class="bg-white rounded p-4 shadow-sm">
              <h2 class="section-title mb-4">Add New Article</h2>
              <form action="gestionnews.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                  <label for="articleImage" class="form-label">Article Image</label>
                  <input class="form-control" type="file" id="articleImage" name="image">
                  <small class="form-text text-muted">An image is required for new articles.</small>
                  <small id="articleImageError" class="text-danger d-none">Please choose an image.</small>
                </div>
                <div class="mb-3">
                  <label for="articleTitle" class="form-label">Title</label>
                  <input type="text" class="form-control" id="articleTitle" name="title" placeholder="Enter article title">
                  <small id="articleTitleHelp" class="form-text text-muted">Enter a clear, descriptive title (min 3 characters).</small>
                  <small id="articleTitleError" class="text-danger d-none">Title must be at least 3 characters.</small>
                </div>
                <div class="mb-3">
                  <label for="articleCategory" class="form-label">Category</label>
                  <select class="form-select" id="articleCategory" name="category">
                    <option value="">Select a category</option>
                    <option value="community">Community</option>
                    <option value="education">Education</option>
                    <option value="events">Events</option>
                    <option value="sports">Sports</option>
                    <option value="technology">Technology</option>
                    <option value="health">Health</option>
                    <option value="business">Business</option>
                  </select>
                  <small id="articleCategoryHelp" class="form-text text-muted">Choose the most relevant category.</small>
                  <small id="articleCategoryError" class="text-danger d-none">Please select a category.</small>
                </div>
                
                <div class="mb-3">
                  <label for="articleContent" class="form-label">Content</label>
                  <textarea class="form-control" id="articleContent" name="content" rows="5" placeholder="Enter article content"></textarea>
                  <small id="articleContentHelp" class="form-text text-muted">Write the full article content (min 20 characters).</small>
                  <small id="articleContentError" class="text-danger d-none">Content must be at least 20 characters.</small>
                </div>
                <button type="submit" class="btn btn-primary">Add Article</button>
              </form>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </main>

    <footer class="container-xxl py-5">
      <div class="container">
        <div class="text-center">
          <p class="mb-0">&copy; 2025 News — Integrated with Kider template</p>
        </div>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../kider-1.0.0/js/main.js"></script>
  
  <script>
// Instagram-style Notification System with Facebook-style Reactions
class NotificationSystem {
    constructor() {
        this.notifications = JSON.parse(localStorage.getItem('articleNotifications') || '[]');
        this.bell = document.getElementById('notificationBell');
        this.dropdown = document.getElementById('notificationsDropdown');
        this.list = document.getElementById('notificationsList');
        this.init();
    }

    init() {
        this.renderNotifications();
        this.setupEventListeners();
        this.updateBellBadge();
        this.setupFacebookReactions();
    }

    setupEventListeners() {
        // Toggle dropdown
        this.bell.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleDropdown();
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            this.hideDropdown();
        });

        // Mark all as read
        document.getElementById('markAllRead').addEventListener('click', (e) => {
            e.stopPropagation();
            this.markAllAsRead();
        });

        // View all notifications
        document.getElementById('viewAllNotifications').addEventListener('click', (e) => {
            e.preventDefault();
            this.viewAllNotifications();
        });

        // Listen for comment submissions
        this.setupCommentListeners();
    }

    setupFacebookReactions() {
        // Add Facebook-style reaction buttons to articles
        document.querySelectorAll('.article-container').forEach(article => {
            this.addFacebookReactionButton(article, 'article');
        });

        // Add Facebook-style reaction buttons to comments
        document.querySelectorAll('.comment-item').forEach(comment => {
            this.addFacebookReactionButton(comment, 'comment');
        });

        // Listen for new comments being added
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) {
                        if (node.classList && node.classList.contains('comment-item')) {
                            this.addFacebookReactionButton(node, 'comment');
                        }
                    }
                });
            });
        });

        // Observe comments container for new comments
        document.querySelectorAll('.comments-content').forEach(container => {
            observer.observe(container, { childList: true, subtree: true });
        });
    }

    addFacebookReactionButton(container, type) {
        const articleId = container.closest('[data-article-id]')?.getAttribute('data-article-id');
        const commentId = container.getAttribute('data-comment-id');
        
        // Check if reaction button already exists
        if (container.querySelector('.fb-reaction-container')) return;

        const reactionContainer = document.createElement('div');
        reactionContainer.className = 'fb-reaction-container';
        reactionContainer.style.cssText = `
            position: relative;
            display: inline-block;
            margin-top: 8px;
        `;

        // Main reaction button (Like button)
        const likeButton = document.createElement('button');
        likeButton.className = 'btn btn-sm fb-like-btn';
        likeButton.style.cssText = `
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 18px;
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 600;
            color: #65676b;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        `;
        likeButton.innerHTML = `
            <i class="fas fa-thumbs-up" style="font-size: 16px;"></i>
            <span>Like</span>
        `;

        // Reaction hover panel (Facebook-style)
        const reactionPanel = document.createElement('div');
        reactionPanel.className = 'fb-reaction-panel';
        reactionPanel.style.cssText = `
            position: absolute;
            bottom: 100%;
            left: 0;
            background: white;
            border-radius: 28px;
            padding: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border: 1px solid #e4e6ea;
            display: none;
            gap: 4px;
            z-index: 1000;
            margin-bottom: 10px;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            transform: scale(0.8);
            opacity: 0;
        `;

        const reactions = [
            { emoji: '👍', label: 'Like', color: '#1877f2', class: 'like' },
            { emoji: '❤️', label: 'Love', color: '#f33e58', class: 'love' },
            { emoji: '😂', label: 'Haha', color: '#f7b125', class: 'haha' },
            { emoji: '😮', label: 'Wow', color: '#f7b125', class: 'wow' },
            { emoji: '😢', label: 'Sad', color: '#f7b125', class: 'sad' },
            { emoji: '😡', label: 'Angry', color: '#e4715a', class: 'angry' }
        ];

        reactions.forEach(reaction => {
            const reactionBtn = document.createElement('button');
            reactionBtn.className = `fb-reaction-btn fb-${reaction.class}`;
            reactionBtn.style.cssText = `
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                transition: all 0.2s ease;
                transform: scale(1);
                border-radius: 50%;
                padding: 4px;
            `;
            reactionBtn.innerHTML = reaction.emoji;
            reactionBtn.title = reaction.label;

            // Hover effects
            reactionBtn.addEventListener('mouseenter', () => {
                reactionBtn.style.transform = 'scale(1.4) translateY(-8px)';
                reactionBtn.style.transition = 'all 0.2s ease';
            });

            reactionBtn.addEventListener('mouseleave', () => {
                reactionBtn.style.transform = 'scale(1)';
            });

            reactionBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                // Update main button
                likeButton.innerHTML = `
                    <span style="font-size: 16px;">${reaction.emoji}</span>
                    <span style="color: ${reaction.color};">${reaction.label}</span>
                `;
                likeButton.style.borderColor = reaction.color;
                likeButton.style.background = `${reaction.color}15`;

                // Hide panel
                this.hideReactionPanel(reactionPanel);

                // Create notification
                if (type === 'article') {
                    this.addReactionNotification('article', articleId, reaction.emoji, reaction.label);
                } else if (type === 'comment') {
                    this.addReactionNotification('comment', articleId, reaction.emoji, reaction.label, commentId);
                }
            });

            reactionPanel.appendChild(reactionBtn);
        });

        // Hover events for main button
        let hoverTimeout;
        likeButton.addEventListener('mouseenter', () => {
            clearTimeout(hoverTimeout);
            hoverTimeout = setTimeout(() => {
                this.showReactionPanel(reactionPanel);
            }, 300);
        });

        likeButton.addEventListener('mouseleave', () => {
            clearTimeout(hoverTimeout);
            setTimeout(() => {
                if (!reactionPanel.matches(':hover')) {
                    this.hideReactionPanel(reactionPanel);
                }
            }, 200);
        });

        reactionPanel.addEventListener('mouseleave', () => {
            this.hideReactionPanel(reactionPanel);
        });

        // Click event for main button (simple like)
        likeButton.addEventListener('click', (e) => {
            if (!reactionPanel.style.display || reactionPanel.style.display === 'none') {
                // Simple like click
                e.preventDefault();
                e.stopPropagation();
                
                const reaction = reactions[0]; // Like reaction
                likeButton.innerHTML = `
                    <span style="font-size: 16px;">${reaction.emoji}</span>
                    <span style="color: ${reaction.color};">${reaction.label}</span>
                `;
                likeButton.style.borderColor = reaction.color;
                likeButton.style.background = `${reaction.color}15`;

                // Create notification
                if (type === 'article') {
                    this.addReactionNotification('article', articleId, reaction.emoji, reaction.label);
                } else if (type === 'comment') {
                    this.addReactionNotification('comment', articleId, reaction.emoji, reaction.label, commentId);
                }
            }
        });

        // Assemble the reaction container
        reactionContainer.appendChild(likeButton);
        reactionContainer.appendChild(reactionPanel);

        // Add to container
        if (type === 'article') {
            const articleContent = container.querySelector('.flex-grow-1');
            if (articleContent) {
                const existingContainer = articleContent.querySelector('.fb-reaction-container');
                if (!existingContainer) {
                    articleContent.appendChild(reactionContainer);
                }
            }
        } else if (type === 'comment') {
            const cardBody = container.querySelector('.card-body');
            if (cardBody) {
                const existingContainer = cardBody.querySelector('.fb-reaction-container');
                if (!existingContainer) {
                    cardBody.appendChild(reactionContainer);
                }
            }
        }
    }

    showReactionPanel(panel) {
        panel.style.display = 'flex';
        setTimeout(() => {
            panel.style.opacity = '1';
            panel.style.transform = 'scale(1)';
        }, 10);
    }

    hideReactionPanel(panel) {
        panel.style.opacity = '0';
        panel.style.transform = 'scale(0.8)';
        setTimeout(() => {
            panel.style.display = 'none';
        }, 200);
    }

    addReactionNotification(type, articleId, emoji, reaction, commentId = null) {
        const articleTitle = this.getArticleTitle(articleId);
        let title = '';
        let content = '';

        if (type === 'article') {
            title = 'Article Reaction';
            content = `You reacted ${emoji} ${reaction} to "${articleTitle}"`;
        } else if (type === 'comment') {
            const commentText = this.getCommentText(commentId);
            title = 'Comment Reaction';
            content = `You reacted ${emoji} ${reaction} to a comment on "${articleTitle}"`;
        }

        this.addNotification('reaction', title, content, articleId);
    }

    getCommentText(commentId) {
        const comment = document.querySelector(`[data-comment-id="${commentId}"]`);
        if (comment) {
            const content = comment.querySelector('.comment-content');
            return content ? content.textContent.trim() : 'comment';
        }
        return 'comment';
    }

    setupCommentListeners() {
        // Listen for comment form submissions
        document.addEventListener('submit', (e) => {
            if (e.target.classList.contains('reply-form')) {
                const form = e.target;
                const replyInput = form.querySelector('.reply-input');
                const replyTo = form.querySelector('input[name="reply_to"]').value;
                
                if (replyInput.value.trim()) {
                    // Store reply data for notification after submission
                    const commentItem = document.querySelector(`[data-comment-id="${replyTo}"]`);
                    if (commentItem) {
                        const commentAuthor = commentItem.querySelector('.card-subtitle').textContent;
                        const commentContent = commentItem.querySelector('.comment-content').textContent;
                        const articleTitle = commentItem.closest('[data-article-id]').querySelector('h3').textContent;
                        
                        sessionStorage.setItem('newReplyData', JSON.stringify({
                            articleTitle: articleTitle,
                            commentAuthor: commentAuthor,
                            commentContent: commentContent.length > 50 ? commentContent.substring(0, 50) + '...' : commentContent,
                            replyText: replyInput.value.trim().length > 50 ? replyInput.value.trim().substring(0, 50) + '...' : replyInput.value.trim()
                        }));
                    }
                }
            }
            
            if (e.target.classList.contains('comment-form')) {
                const form = e.target;
                const commentInput = form.querySelector('.comment-input');
                const newsId = form.querySelector('input[name="news_id"]').value;
                
                if (commentInput.value.trim()) {
                    // Store comment data for notification after submission
                    sessionStorage.setItem('newCommentData', JSON.stringify({
                        newsId: newsId,
                        commentText: commentInput.value.trim()
                    }));
                }
            }
        });
    }

    toggleDropdown() {
        this.dropdown.classList.toggle('show');
        if (this.dropdown.classList.contains('show')) {
            this.markAllAsRead();
        }
    }

    hideDropdown() {
        this.dropdown.classList.remove('show');
    }

    addNotification(type, title, content = '', articleId = null) {
        const notification = {
            id: Date.now(),
            type: type, // 'new-article', 'update', 'comment', 'reaction'
            title: title,
            content: content,
            articleId: articleId,
            time: new Date().toISOString(),
            read: false
        };

        this.notifications.unshift(notification);
        
        // Keep only last 50 notifications
        if (this.notifications.length > 50) {
            this.notifications = this.notifications.slice(0, 50);
        }

        this.saveNotifications();
        this.renderNotifications();
        this.updateBellBadge();
        
        // Show pulse animation
        this.showBellPulse();
    }

    addReplyNotification(articleTitle, commentAuthor, commentContent, replyText) {
        const title = 'New Reply';
        const content = `You replied to ${commentAuthor}'s comment on "${articleTitle}": "${replyText}"`;
        
        this.addNotification('comment', title, content);
    }

    addCommentNotification(articleId, commentText) {
        const articleTitle = this.getArticleTitle(articleId);
        const previewText = commentText.length > 50 ? commentText.substring(0, 50) + '...' : commentText;
        
        this.addNotification(
            'comment', 
            'New Comment', 
            `You added a comment on "${articleTitle}": "${previewText}"`,
            articleId
        );
    }

    getArticleTitle(articleId) {
        // Try to find the article title from the page
        const articleContainer = document.querySelector(`.article-container [data-article-id="${articleId}"]`);
        if (articleContainer) {
            const titleElement = articleContainer.querySelector('h3');
            if (titleElement) {
                return titleElement.textContent.trim();
            }
        }
        return 'Article';
    }

    showBellPulse() {
        this.bell.classList.add('has-notifications');
        
        // Add shake animation
        this.bell.style.animation = 'shake 0.5s ease';
        setTimeout(() => {
            this.bell.style.animation = '';
        }, 500);
    }

    markAllAsRead() {
        this.notifications.forEach(notification => {
            notification.read = true;
        });
        this.saveNotifications();
        this.renderNotifications();
        this.updateBellBadge();
    }

    markAsRead(notificationId) {
        const notification = this.notifications.find(n => n.id === notificationId);
        if (notification) {
            notification.read = true;
            this.saveNotifications();
            this.renderNotifications();
            this.updateBellBadge();
        }
    }

    clearAllNotifications() {
        this.notifications = [];
        this.saveNotifications();
        this.renderNotifications();
        this.updateBellBadge();
    }

    updateBellBadge() {
        const unreadCount = this.notifications.filter(n => !n.read).length;
        
        if (unreadCount > 0) {
            this.bell.classList.add('has-notifications');
            
            // Update or create badge
            let badge = this.bell.querySelector('.notification-badge');
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'notification-badge';
                this.bell.appendChild(badge);
            }
            badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
        } else {
            this.bell.classList.remove('has-notifications');
            const badge = this.bell.querySelector('.notification-badge');
            if (badge) {
                badge.remove();
            }
        }
    }

    renderNotifications() {
        if (this.notifications.length === 0) {
            this.list.innerHTML = `
                <div class="empty-notifications">
                    <i class="fas fa-bell-slash"></i>
                    <p>No notifications</p>
                </div>
            `;
            return;
        }

        this.list.innerHTML = this.notifications.slice(0, 10).map(notification => `
            <div class="notification-item ${notification.read ? '' : 'unread'}" 
                 data-id="${notification.id}"
                 data-article-id="${notification.articleId}">
                <div class="notification-icon ${notification.type}">
                    <i class="fas ${
                      notification.type === 'new-article' ? 'fa-plus' :
                      notification.type === 'update' ? 'fa-edit' :
                      notification.type === 'reaction' ? 'fa-heart' :
                      'fa-comment'
                    }"></i>
                </div>
                <div class="notification-content">
                    <h6>${notification.title}</h6>
                    <p>${notification.content || this.getDefaultContent(notification.type)}</p>
                    <div class="notification-time">${this.formatTime(notification.time)}</div>
                </div>
            </div>
        `).join('');

        // Add click listeners to notification items
        this.list.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const notificationId = parseInt(item.dataset.id);
                const articleId = item.dataset.articleId;
                
                this.markAsRead(notificationId);
                this.viewArticle(articleId);
                this.hideDropdown();
            });
        });
    }

    getDefaultContent(type) {
        const contents = {
            'new-article': 'A new article has been published',
            'update': 'An article has been updated',
            'comment': 'Someone commented on an article',
            'reaction': 'Someone reacted to your content'
        };
        return contents[type] || 'New notification';
    }

    formatTime(timeString) {
        const time = new Date(timeString);
        const now = new Date();
        const diff = now - time;
        
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);
        
        if (minutes < 1) return 'Just now';
        if (minutes < 60) return `${minutes} min ago`;
        if (hours < 24) return `${hours} hours ago`;
        if (days < 7) return `${days} days ago`;
        
        return time.toLocaleDateString();
    }

    viewArticle(articleId) {
        if (articleId) {
            // Scroll to specific article
            const article = document.querySelector(`[data-article-id="${articleId}"]`);
            if (article) {
                article.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Highlight effect
                article.style.backgroundColor = '#f0f7ff';
                article.style.transition = 'background-color 0.3s ease';
                
                setTimeout(() => {
                    article.style.backgroundColor = '';
                }, 2000);
            }
        } else {
            // Scroll to first article
            const firstArticle = document.querySelector('.article-container');
            if (firstArticle) {
                firstArticle.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    viewAllNotifications() {
        this.createNotificationsPage();
        this.hideDropdown();
    }

    createNotificationsPage() {
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'notifications-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        `;

        // Create notifications container
        const container = document.createElement('div');
        container.className = 'notifications-page';
        container.style.cssText = `
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease;
        `;

        // Header
        const header = document.createElement('div');
        header.style.cssText = `
            padding: 20px 24px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        `;
        header.innerHTML = `
            <h4 class="mb-0" style="font-weight: 600;">All Notifications</h4>
            <div>
                <button class="btn btn-sm btn-outline-secondary me-2" id="pageMarkAllRead">
                    Mark all as read
                </button>
                <button class="btn btn-sm btn-outline-danger me-2" id="pageClearAll">
                    Clear all
                </button>
                <button class="btn btn-sm btn-outline-secondary" id="closeNotificationsPage">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        // Notifications list
        const notificationsList = document.createElement('div');
        notificationsList.style.cssText = `
            max-height: 60vh;
            overflow-y: auto;
            padding: 0;
        `;

        // Render all notifications
        if (this.notifications.length === 0) {
            notificationsList.innerHTML = `
                <div class="empty-notifications-page" style="padding: 60px 20px; text-align: center; color: #6c757d;">
                    <i class="fas fa-bell-slash" style="font-size: 3rem; margin-bottom: 15px; color: #dee2e6;"></i>
                    <h5>No notifications yet</h5>
                    <p class="mb-0">When you have notifications, they'll appear here.</p>
                </div>
            `;
        } else {
            notificationsList.innerHTML = this.notifications.map(notification => `
                <div class="notification-page-item ${notification.read ? '' : 'unread'}" 
                     data-id="${notification.id}"
                     data-article-id="${notification.articleId}"
                     style="padding: 16px 24px; border-bottom: 1px solid #f8f9fa; cursor: pointer; transition: background 0.2s ease; display: flex; align-items: flex-start; gap: 12px; ${!notification.read ? 'background: #f0f7ff;' : ''}">
                    <div class="notification-icon ${notification.type}" style="width: 40px; height: 40px; background: ${this.getNotificationColor(notification.type)}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; flex-shrink: 0;">
                        <i class="fas ${
                          notification.type === 'new-article' ? 'fa-plus' :
                          notification.type === 'update' ? 'fa-edit' :
                          notification.type === 'reaction' ? 'fa-heart' :
                          'fa-comment'
                        }"></i>
                    </div>
                    <div class="notification-content" style="flex: 1;">
                        <h6 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 600; color: #2c3e50;">${notification.title}</h6>
                        <p style="margin: 0; font-size: 14px; color: #6c757d; line-height: 1.4;">${notification.content || this.getDefaultContent(notification.type)}</p>
                        <div class="notification-time" style="font-size: 12px; color: #adb5bd; margin-top: 6px;">${this.formatTime(notification.time)}</div>
                    </div>
                </div>
            `).join('');
        }

        // Footer
        const footer = document.createElement('div');
        footer.style.cssText = `
            padding: 16px 24px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            background: #f8f9fa;
        `;
        footer.innerHTML = `
            <small class="text-muted">Showing ${this.notifications.length} notification${this.notifications.length !== 1 ? 's' : ''}</small>
        `;

        // Assemble the page
        container.appendChild(header);
        container.appendChild(notificationsList);
        container.appendChild(footer);
        overlay.appendChild(container);
        document.body.appendChild(overlay);

        // Add event listeners
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                this.closeNotificationsPage();
            }
        });

        document.getElementById('closeNotificationsPage').addEventListener('click', () => {
            this.closeNotificationsPage();
        });

        document.getElementById('pageMarkAllRead').addEventListener('click', () => {
            this.markAllAsRead();
            this.closeNotificationsPage();
        });

        document.getElementById('pageClearAll').addEventListener('click', () => {
            if (confirm('Are you sure you want to clear all notifications?')) {
                this.clearAllNotifications();
                this.closeNotificationsPage();
            }
        });

        // Add click listeners to notification items
        notificationsList.querySelectorAll('.notification-page-item').forEach(item => {
            item.addEventListener('click', (e) => {
                const notificationId = parseInt(item.dataset.id);
                const articleId = item.dataset.articleId;
                
                this.markAsRead(notificationId);
                this.viewArticle(articleId);
                this.closeNotificationsPage();
            });
        });

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideUp {
                from { 
                    opacity: 0;
                    transform: translateY(30px);
                }
                to { 
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .notification-page-item:hover {
                background: #f8f9fa !important;
            }
            .notification-page-item.unread:hover {
                background: #e3f2fd !important;
            }
        `;
        document.head.appendChild(style);
    }

    getNotificationColor(type) {
        const colors = {
            'new-article': '#28a745',
            'update': '#ffc107',
            'comment': '#0d6efd',
            'reaction': '#e83e8c'
        };
        return colors[type] || '#6c757d';
    }

    closeNotificationsPage() {
        const overlay = document.querySelector('.notifications-overlay');
        if (overlay) {
            overlay.remove();
        }
        // Remove injected styles
        const styles = document.querySelectorAll('style');
        styles.forEach(style => {
            if (style.textContent.includes('notifications-overlay') || 
                style.textContent.includes('notification-page-item')) {
                style.remove();
            }
        });
    }

    saveNotifications() {
        localStorage.setItem('articleNotifications', JSON.stringify(this.notifications));
    }
}

// Initialize notification system
const notificationSystem = new NotificationSystem();

// Listen for form submissions
document.addEventListener('DOMContentLoaded', function() {
    // Add form submission
    const addForm = document.querySelector('form[action="gestionnews.php"]');
    if (addForm && !addForm.querySelector('input[name="update_id"]')) {
        addForm.addEventListener('submit', function(e) {
            const title = document.getElementById('articleTitle').value;
            // Store for notification after page reload
            sessionStorage.setItem('newArticleTitle', title);
        });
    }

    // Update form submission
    const editForm = document.querySelector('form input[name="update_id"]') ? 
                    document.querySelector('form input[name="update_id"]').closest('form') : null;
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const title = document.getElementById('editArticleTitle').value;
            sessionStorage.setItem('updatedArticleTitle', title);
        });
    }

    // Check for new notifications after page load
    setTimeout(() => {
        if (sessionStorage.getItem('newArticleTitle')) {
            const title = sessionStorage.getItem('newArticleTitle');
            notificationSystem.addNotification('new-article', 'New Article', title);
            sessionStorage.removeItem('newArticleTitle');
        }

        if (sessionStorage.getItem('updatedArticleTitle')) {
            const title = sessionStorage.getItem('updatedArticleTitle');
            notificationSystem.addNotification('update', 'Article Updated', title);
            sessionStorage.removeItem('updatedArticleTitle');
        }

        if (sessionStorage.getItem('newCommentData')) {
            const { newsId, commentText } = JSON.parse(sessionStorage.getItem('newCommentData'));
            notificationSystem.addCommentNotification(newsId, commentText);
            sessionStorage.removeItem('newCommentData');
        }

        // Handle reply notifications
        if (sessionStorage.getItem('newReplyData')) {
            const { articleTitle, commentAuthor, commentContent, replyText } = JSON.parse(sessionStorage.getItem('newReplyData'));
            notificationSystem.addReplyNotification(articleTitle, commentAuthor, commentContent, replyText);
            sessionStorage.removeItem('newReplyData');
        }
    }, 1000);

    // Add clear notifications button functionality
    const clearButton = document.createElement('button');
    clearButton.className = 'btn btn-sm btn-outline-danger ms-2';
    clearButton.innerHTML = '<small>Clear All</small>';
    clearButton.addEventListener('click', (e) => {
        e.stopPropagation();
        if (confirm('Are you sure you want to clear all notifications?')) {
            notificationSystem.clearAllNotifications();
        }
    });
    
    // Add clear button to notifications header
    const notificationsHeader = document.querySelector('.notifications-header');
    if (notificationsHeader) {
        notificationsHeader.appendChild(clearButton);
    }

    // Comments interactions
    document.addEventListener('click', (e) => {
        const previewBtn = e.target.closest('.comments-preview');
        if (previewBtn) {
            e.preventDefault();
            const id = previewBtn.getAttribute('data-preview-for');
            const list = document.querySelector(`.comments-list[data-comments-for='${id}']`);
            if (list) {
                list.classList.remove('d-none');
                previewBtn.parentElement.classList.add('d-none');
            }
            return;
        }

        const closeBtn = e.target.closest('.comments-close');
        if (closeBtn) {
            e.preventDefault();
            const id = closeBtn.getAttribute('data-close-for');
            const list = document.querySelector(`.comments-list[data-comments-for='${id}']`);
            if (list) {
                list.classList.add('d-none');
                const previewHeader = document.querySelector('.comments-preview-header');
                if (previewHeader) previewHeader.classList.remove('d-none');
            }
            return;
        }

                const editBtn = e.target.closest('.edit-comment');
        if (editBtn) {
            e.preventDefault();
            const commentId = editBtn.getAttribute('data-comment-id');
            const commentText = editBtn.getAttribute('data-comment-text').trim();
            const commentItem = editBtn.closest('.comment-item');
            const commentContent = commentItem.querySelector('.comment-content');
            
            // Create inline edit form
            const editForm = document.createElement('div');
            editForm.className = 'edit-comment-form mt-2';
            editForm.innerHTML = `
                <form class="d-flex gap-2" method="POST" action="updatecomment.php">
                    <input type="hidden" name="comment_id" value="${commentId}">
                    <input type="text" class="form-control form-control-sm" name="comment_content" value="${commentText.replace(/"/g, '&quot;')}" required>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-check"></i>
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm cancel-edit">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
            `;
            
            // Hide the original comment content and show edit form
            commentContent.style.display = 'none';
            commentContent.parentNode.insertBefore(editForm, commentContent.nextSibling);
            
            // Cancel edit functionality
            editForm.querySelector('.cancel-edit').addEventListener('click', function() {
                editForm.remove();
                commentContent.style.display = 'block';
            });
            
            return;
        }

        const cancelBtn = e.target.closest('.comment-cancel');
        if (cancelBtn) {
            e.preventDefault();
            const form = cancelBtn.closest('.comment-form');
            form.querySelector('.comment-input').value='';
            const hidden = form.querySelector('input[name="comment_id"]'); if (hidden) hidden.remove();
            form.action='addcomments.php';
            form.querySelector('.comment-submit').innerHTML='<i class="fas fa-paper-plane me-1"></i> Post';
            cancelBtn.classList.add('d-none');
            return;
        }

        const delBtn = e.target.closest('.delete-comment');
        if (delBtn) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete this comment?')) return;
            let form = document.getElementById('delete-comment-form');
            if (!form) {
                form = document.createElement('form'); form.method='POST'; form.action='deletecomment.php'; form.id='delete-comment-form'; form.style.display='none';
                const input = document.createElement('input'); input.type='hidden'; input.name='comment_id'; form.appendChild(input); document.body.appendChild(form);
            }
            form.querySelector('input[name="comment_id"]').value = delBtn.getAttribute('data-comment-id');
            form.submit();
            return;
        }

        // Enhanced reply functionality
        const replyBtn = e.target.closest('.reply-comment');
        if (replyBtn) {
            e.preventDefault();
            const commentId = replyBtn.getAttribute('data-comment-id');
            const commentItem = replyBtn.closest('.comment-item');
            
            // Remove any existing reply forms in this comment section
            commentItem.querySelectorAll('.reply-form-container').forEach(form => form.remove());
            
            // Create reply form
            const replyForm = document.createElement('div');
            replyForm.className = 'reply-form-container mt-3';
            replyForm.innerHTML = `
                <form class="reply-form" method="POST" action="addcomments.php">
                    <input type="hidden" name="news_id" value="${commentItem.closest('[data-article-id]').getAttribute('data-article-id')}">
                    <input type="hidden" name="reply_to" value="${commentId}">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control reply-input" name="comment_content" placeholder="Write a reply...">
                        <button class="btn btn-primary reply-submit" type="submit">
                            <i class="fas fa-paper-plane me-1"></i> Reply
                        </button>
                        <button class="btn btn-secondary cancel-reply" type="button">
                            <i class="fas fa-times me-1"></i> Cancel
                        </button>
                    </div>
                </form>
            `;
            
            // Insert the reply form after the comment content
            const cardBody = commentItem.querySelector('.card-body');
            const repliesContainer = cardBody.querySelector('.mt-3.ms-4');
            
            if (repliesContainer) {
                // Insert before the replies container
                cardBody.insertBefore(replyForm, repliesContainer);
            } else {
                // Create a replies container and insert the form
                const newRepliesContainer = document.createElement('div');
                newRepliesContainer.className = 'mt-3 ms-4';
                cardBody.appendChild(newRepliesContainer);
                cardBody.insertBefore(replyForm, newRepliesContainer);
            }
            
            // Focus on the reply input
            replyForm.querySelector('.reply-input').focus();
            
            // Add cancel functionality
            replyForm.querySelector('.cancel-reply').addEventListener('click', function() {
                replyForm.remove();
            });
            // Save reply for notification after submit
replyForm.querySelector('.reply-form').addEventListener('submit', function() {
    const replyText = this.querySelector('.reply-input').value.trim();
    if (!replyText) return;

    const commentItem = this.closest('.comment-item');
    const articleTitle = commentItem.closest('[data-article-id]')
        .querySelector('h3 a')?.textContent.trim() || 'an article';

    // Get the original comment author's name (even if it's "Anonymous" for now)
    const authorElement = commentItem.querySelector('.card-subtitle');
    const commentAuthor = authorElement 
        ? authorElement.textContent.trim().replace(/ replied$/i, '').trim() 
        : 'someone';

    const originalComment = commentItem.querySelector('.comment-content')?.textContent || '';

    sessionStorage.setItem('newReplyData', JSON.stringify({
        articleTitle: articleTitle,
        commentAuthor: commentAuthor,
        commentPreview: originalComment.substring(0, 60) + (originalComment.length > 60 ? '...' : ''),
        replyText: replyText.substring(0, 100) + (replyText.length > 100 ? '...' : '')
    }));
});
            
            return;
        }
    });
});
  </script>
  <script>
// === INSTANT SEARCH FUNCTIONALITY ===
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.col-lg-4 input[type="search"]');
    const searchForm = searchInput.closest('form');
    const articleContainers = document.querySelectorAll('.article-container');
    const noResultsMessage = `<div class="col-12"><div class="bg-white rounded p-5 text-center shadow-sm"><p class="text-muted mb-0"><i class="fas fa-search fa-2x mb-3"></i><br>No articles found matching your search.</p></div></div>`;

    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        const query = this.value.trim().toLowerCase();
        let visibleCount = 0;

        articleContainers.forEach(container => {
            const title = container.querySelector('h3 a')?.textContent.toLowerCase() || '';
            const content = container.querySelector('p.mb-0')?.textContent.toLowerCase() || '';
            const categoryBadge = container.querySelector('.badge')?.textContent.toLowerCase() || '';

            const matches = title.includes(query) || content.includes(query) || categoryBadge.includes(query);

            if (matches || query === '') {
                container.closest('.col-12').style.display = '';
                visibleCount++;
            } else {
                container.closest('.col-12').style.display = 'none';
            }
        });

        // Optional: Show "No results" message
        let noResults = document.querySelector('.no-search-results');
        if (query !== '' && visibleCount === 0) {
            if (!noResults) {
                const resultsRow = document.querySelector('.row.g-4 > .col-lg-8 > .row.g-4');
                if (resultsRow) {
                    resultsRow.insertAdjacentHTML('afterend', `<div class="no-search-results row g-4 mt-4">${noResultsMessage}</div>`);
                }
            }
        } else {
            if (noResults) noResults.remove();
        }
    });

    // Prevent form submit from reloading page
    searchForm.addEventListener('submit', function (e) {
        e.preventDefault();
        searchInput.focus();
    });
});
</script>
<script>
// INSTANT SEARCH — Shows only matching articles with full comments & buttons
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.querySelector('input[type="search"]');
  const articleBlocks = document.querySelectorAll('.article-block');

  searchInput.addEventListener('input', function () {
    const query = this.value.trim().toLowerCase();
    let visible = 0;

    articleBlocks.forEach(block => {
      const title = block.dataset.title || '';
      const content = block.dataset.content || '';
      const category = block.dataset.category || '';

      if (query === '' || title.includes(query) || content.includes(query) || category.includes(query)) {
        block.style.display = '';
        visible++;
      } else {
        block.style.display = 'none';
      }
    });

    // Optional: show "No results" if nothing matches
    let noResult = document.getElementById('noResultsMsg');
    if (query && visible === 0) {
      if (!noResult) {
        document.querySelector('.col-lg-8').insertAdjacentHTML('beforeend', 
          '<div id="noResultsMsg" class="text-center py-5"><h4>No articles found</h4><p class="text-muted">Try different keywords.</p></div>'
        );
      }
    } else if (noResult) {
      noResult.remove();
    }
  });

  // Prevent form submit reload
  searchInput.closest('form').addEventListener('submit', e => e.preventDefault());
});
</script>
</body>
</html>