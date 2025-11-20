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
    .comments-container {
      background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
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
        <a href="#" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Join Us<i class="fa fa-arrow-right ms-3"></i></a>
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
            $result = $newsController->updateNews($update_id, $title, $content, $category, $image);
            if ($result) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Article updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                echo '<script>setTimeout(function(){ window.location.href = "gestionnews.php"; }, 1000);</script>';
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating article!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
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
        $allNews = $newsController->getAllNews();

        $editNews = null;
        if (isset($_GET['edit_id'])) {
            $editNews = $newsController->getNewsById($_GET['edit_id']);
        }
        ?>

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
                      <?php foreach ($comments as $comment): ?>
                        <div class="comment-item card mb-3" data-comment-id="<?php echo $comment['id']; ?>">
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                              <h6 class="card-subtitle mb-1 text-primary">Anonymous</h6>
                              <div class="btn-group btn-group-sm">
                                <a href="#" class="btn btn-outline-secondary edit-comment" data-comment-id="<?php echo $comment['id']; ?>" data-comment-text="<?php echo htmlspecialchars($comment['content']); ?>" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="#" class="btn btn-outline-danger delete-comment" data-comment-id="<?php echo $comment['id']; ?>" title="Delete"><i class="fas fa-trash"></i></a>
                              </div>
                            </div>
                            <p class="card-text mb-0 comment-content"><?php echo htmlspecialchars($comment['content']); ?></p>
                            <small class="text-muted"><?php $cd = new DateTime($comment['created_at']); echo $cd->format('M j, Y g:i A'); ?></small>
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
 <script>
 // Single script for comments interactions (no AJAX)
 document.addEventListener('DOMContentLoaded', () => {
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
     const form = document.querySelector('.comment-form');
     if (!form) return;
     form.querySelector('.comment-input').value = editBtn.getAttribute('data-comment-text').trim();
     let hidden = form.querySelector('input[name="comment_id"]');
     if (!hidden) {
     hidden = document.createElement('input'); hidden.type='hidden'; hidden.name='comment_id'; form.appendChild(hidden);
     }
     hidden.value = editBtn.getAttribute('data-comment-id');
     form.action = 'updatecomment.php';
     form.querySelector('.comment-submit').innerHTML = '<i class="fas fa-edit me-1"></i> Update';
     let cancelBtn = form.querySelector('.comment-cancel');
     if (!cancelBtn) {
     cancelBtn = document.createElement('button'); cancelBtn.type='button'; cancelBtn.className='btn btn-secondary comment-cancel ms-2'; cancelBtn.textContent='Cancel'; form.querySelector('.input-group').appendChild(cancelBtn);
     } else { cancelBtn.classList.remove('d-none'); }
     form.scrollIntoView({ behavior:'smooth' });
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
   });
 });
 </script>
            

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
                            <p class="text-muted small">Current image</p>
                        </div>
                    <?php endif; ?>
                    <input class="form-control" type="file" id="editArticleImage" name="image">
                    <div class="form-text">Leave empty to keep current image</div>
                </div>
                <div class="mb-3">
                    <label for="editArticleTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="editArticleTitle" name="title" 
                           value="<?php echo htmlspecialchars($editNews['title']); ?>" required>
                </div>
                <div class="mb-3">
                <label for="editArticleCategory" class="form-label">Category</label>
                <select class="form-select" id="editArticleCategory" name="category" required>
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
                
                <div class="mb-3">
                    <label for="editArticleContent" class="form-label">Content</label>
                    <textarea class="form-control" id="editArticleContent" name="content" rows="5" required><?php echo htmlspecialchars($editNews['content']); ?></textarea>
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
                </div>
                <div class="mb-3">
                    <label for="articleTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="articleTitle" name="title" placeholder="Enter article title" required>
                </div>
                <div class="mb-3">
                <label for="articleCategory" class="form-label">Category</label>
                <select class="form-select" id="articleCategory" name="category" required>
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
                
                <div class="mb-3">
                    <label for="articleContent" class="form-label">Content</label>
                    <textarea class="form-control" id="articleContent" name="content" rows="5" placeholder="Enter article content" required></textarea>
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
            // Original simple toggle only (no edit/delete logic)
            (function(){
                document.addEventListener('click', function(e){
                    if(e.target.matches('.comments-preview') || e.target.closest('.comments-preview')){
                        e.preventDefault();
                        var preview = e.target.closest('.comments-preview');
                        var container = preview.closest('.comments-container');
                        var list = container.querySelector('.comments-list');
                        var header = container.querySelector('.comments-preview-header');
                        list.classList.toggle('d-none');
                        header.style.display = list.classList.contains('d-none') ? 'block' : 'none';
                        return;
                    }
                    if(e.target.matches('.comments-close') || e.target.closest('.comments-close')){
                        e.preventDefault();
                        var closeBtn = e.target.closest('.comments-close');
                        var container = closeBtn.closest('.comments-container');
                        var list = container.querySelector('.comments-list');
                        var header = container.querySelector('.comments-preview-header');
                        list.classList.add('d-none');
                        header.style.display = 'block';
                        return;
                    }
                }, false);
            })();
        </script>
</body>
</html>


