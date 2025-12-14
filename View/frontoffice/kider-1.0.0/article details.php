<?php if (isset($_GET['debug'])): ?>
<div style="background:#f0f0f0;padding:20px;margin:20px;border-left:5px solid #10a37f;">
    <strong>AI Summary Debug Info:</strong><br>
    Source: <strong><?= $aiSource ?></strong><br>
    Real AI used: <strong><?= $isRealAI ? 'Yes (Hugging Face)' : 'No (PHP fallback)' ?></strong><br>
    Cache file: <?= realpath($cacheFile) ?><br>
    Cache age: <?= file_exists($cacheFile) ? round((time()-filemtime($cacheFile))/3600,1) . ' hours' : 'none' ?><br>
    <pre><?= htmlspecialchars($aiSummary) ?></pre>
</div>
<?php endif; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($article) ? htmlspecialchars($article['title']) . ' - Starr' : 'Article - Starr'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0d6efd;
            --primary-light: #3b82f6;
            --primary-dark: #0b5ed7;
            --secondary: #ff6b6b;
            --accent: #ffc107;
            --light: #f8f9fa;
            --dark: #343a40;
            --text: #495057;
            --border-radius: 10px;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #ffffff;
            color: var(--text);
            line-height: 1.6;
        }
        
        /* Header Navigation Style */
        .article-header-nav {
            background: white;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .main-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .nav-brand i {
            color: var(--secondary);
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            font-size: 0.95rem;
            padding: 8px 0;
            position: relative;
            transition: var(--transition);
        }
        
        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary);
        }
        
        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--primary);
            border-radius: 2px;
        }
        
        .nav-cta {
            background: var(--primary);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav-cta:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
        }
        
        /* Article Header */
        .article-header {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(255, 107, 107, 0.05) 100%);
            padding: 80px 0 60px;
            position: relative;
            overflow: hidden;
        }
        
        .article-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(13, 110, 253, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }
        
        .article-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 107, 107, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(-30%, 30%);
        }
        
        .header-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .article-category {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
        }
        
        .article-title {
            font-size: 3rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 25px;
            line-height: 1.2;
            font-family: 'Playfair Display', serif;
        }
        
        .article-excerpt {
            font-size: 1.2rem;
            color: var(--text);
            margin-bottom: 30px;
            max-width: 600px;
        }
        
        .article-meta {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text);
            font-size: 0.95rem;
        }
        
        .meta-item i {
            color: var(--primary);
            font-size: 1rem;
        }
        
        .author-box {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            max-width: 400px;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .author-info h4 {
            font-size: 1rem;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .author-info p {
            font-size: 0.9rem;
            color: var(--text);
            margin: 0;
            opacity: 0.8;
        }
        
        /* Hero Image */
        .hero-section {
            max-width: 900px;
            margin: -30px auto 50px;
            position: relative;
        }
        
        .hero-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: var(--border-radius);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .image-overlay {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px 25px;
            border-radius: var(--border-radius);
            max-width: 400px;
        }
        
        .image-caption {
            font-size: 0.9rem;
            color: var(--dark);
            margin: 0;
            font-weight: 500;
        }
        
        /* Main Content */
        .content-wrapper {
            max-width: 800px;
            margin: 0 auto 80px;
            padding: 0 20px;
        }
        
        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text);
        }
        
        .article-content p {
            margin-bottom: 1.8rem;
        }
        
        .article-content h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin: 50px 0 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light);
        }
        
        .article-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
            margin: 40px 0 20px;
        }
        
        .article-content blockquote {
            border-left: 4px solid var(--primary);
            padding: 25px 30px;
            margin: 40px 0;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(255, 107, 107, 0.05) 100%);
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            font-style: italic;
            color: var(--dark);
            font-size: 1.2rem;
            line-height: 1.7;
        }
        
        /* AI Summary Box - REMOVED REGENERATE BUTTON */
        .ai-summary-box {
            background: linear-gradient(135deg, #f0f7ff 0%, #e3f2fd 100%);
            border-radius: var(--border-radius);
            padding: 30px;
            margin: 40px 0;
            border: 2px solid #10a37f30;
            box-shadow: 0 10px 30px rgba(16, 163, 127, 0.1);
            position: relative;
            overflow: hidden;
        }

        .ai-summary-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #10a37f, #0d8b6a);
        }

        .ai-icon {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(16, 163, 127, 0.2);
        }

        .ai-summary-content {
            background: white;
            border-radius: 10px;
            padding: 20px;
            line-height: 1.6;
            border: 1px solid #e3f2fd;
            margin-top: 20px;
        }

        .ai-summary-content p {
            margin-bottom: 15px;
        }

        .ai-disclaimer {
            border-top: 1px solid #10a37f20;
            padding-top: 15px;
            margin-top: 20px;
        }
        
        .highlight-box {
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            margin: 40px 0;
            border: 2px solid var(--light);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .highlight-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary), var(--secondary));
        }
        
        .highlight-box h4 {
            color: var(--dark);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .highlight-box ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .highlight-box li {
            margin-bottom: 10px;
            color: var(--text);
        }
        
        /* Action Buttons - REMOVED REGENERATE BUTTON */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin: 40px 0;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 28px;
            border-radius: var(--border-radius);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
        }
        
        .btn-secondary {
            background: var(--secondary);
            color: white;
        }
        
        .btn-secondary:hover {
            background: #ff5252;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 107, 0.2);
        }
        
        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        .btn-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        
        /* Share Section */
        .share-section {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 30px;
            margin: 50px 0;
            text-align: center;
        }
        
        .share-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 20px;
        }
        
        .share-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .share-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
            font-size: 1.1rem;
            cursor: pointer;
            border: none;
        }
        
        .share-btn:hover {
            transform: translateY(-3px) scale(1.1);
        }
        
        .share-btn.facebook { background: #1877f2; }
        .share-btn.twitter { background: #1da1f2; }
        .share-btn.linkedin { background: #0077b5; }
        .share-btn.whatsapp { background: #25d366; }
        .share-btn.email { background: #ea4335; }
        .share-btn.instagram { background: #e4405f; }
        .share-btn.email:hover { background: #d32f2f; }
        .share-btn.instagram:hover { background: #c13584; }
        
        /* Related Articles */
        .related-section {
            max-width: 1200px;
            margin: 0 auto 80px;
            padding: 0 20px;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
        }
        
        .section-subtitle {
            color: var(--text);
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }
        
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .related-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            border: 1px solid var(--light);
        }
        
        .related-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }
        
        .related-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .related-content {
            padding: 25px;
        }
        
        .related-category {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .related-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .related-excerpt {
            color: var(--text);
            font-size: 0.95rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .read-more {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }
        
        .read-more:hover {
            color: var(--primary-dark);
            gap: 12px;
        }
        
        /* Footer */
        .article-footer {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d3748 100%);
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }
        
        .footer-logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            display: inline-block;
            text-decoration: none;
        }
        
        .footer-text {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
            font-size: 0.95rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .footer-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }
        
        .copyright {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Back to Top */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.3);
            transition: var(--transition);
            opacity: 0;
            visibility: hidden;
            z-index: 1000;
        }
        
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(13, 110, 253, 0.4);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .article-title {
                font-size: 2.5rem;
            }
            
            .hero-image {
                height: 400px;
            }
        }
        
        @media (max-width: 768px) {
            .main-nav {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
            }
            
            .article-title {
                font-size: 2rem;
            }
            
            .article-excerpt {
                font-size: 1.1rem;
            }
            
            .article-meta {
                flex-direction: column;
                gap: 15px;
            }
            
            .hero-image {
                height: 300px;
            }
            
            .image-overlay {
                position: relative;
                bottom: 0;
                left: 0;
                width: 100%;
                max-width: none;
                margin-top: 20px;
            }
            
            .related-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .share-buttons {
                gap: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .article-title {
                font-size: 1.8rem;
            }
            
            .article-header {
                padding: 60px 0 40px;
            }
            
            .hero-image {
                height: 250px;
            }
            
            .article-content h2 {
                font-size: 1.7rem;
            }
            
            .article-content h3 {
                font-size: 1.4rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
            }
            
            .share-btn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        /* Recommendation Match Score */
        .match-score {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
        }

        /* Recommendation card enhancements */
        .related-card:hover .match-score {
            transform: scale(1.05);
            transition: var(--transition);
        }
        
        /* Loading spinner */
        .ai-loading {
            text-align: center;
            padding: 20px;
        }
        
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #10a37f;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* AI Summary Toggle */
        .ai-toggle-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }
        
        .ai-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .toggle-switch {
            width: 50px;
            height: 25px;
            background: #ddd;
            border-radius: 25px;
            position: relative;
            transition: var(--transition);
        }
        
        .toggle-switch.active {
            background: #10a37f;
        }
        
        .toggle-knob {
            width: 21px;
            height: 21px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: var(--transition);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .toggle-switch.active .toggle-knob {
            left: 27px;
        }
        
        /* Listen to article button */
        .listen-container {
            display: flex;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .btn-outline-success {
            border-color: #198754;
            color: #198754;
        }
        
        .btn-outline-success:hover {
            background-color: #198754;
            color: white;
        }
    </style>
</head>
<body>
    <?php
    require_once __DIR__ . '/../../../Controller/Config.php';
    require_once __DIR__ . '/../../../Controller/newsController.php';
    require_once __DIR__ . '/../../../Controller/commentController.php';
    
    $articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if (!$articleId) {
        die('<div style="max-width: 800px; margin: 100px auto; text-align: center; padding: 20px;">
            <h2 style="color: var(--primary); margin-bottom: 20px;">Article Not Found</h2>
            <p style="margin-bottom: 30px; color: var(--text);">The requested article could not be found.</p>
            <a href="gestionnews.php" class="btn-primary" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 28px; background: var(--primary); color: white; border-radius: var(--border-radius); text-decoration: none; font-weight: 600;">
                <i class="fas fa-newspaper"></i>
                Return to News
            </a>
        </div>');
    }
    
    $newsController = new NewsController();
    $article = $newsController->getNewsById($articleId);
    
    if (!$article) {
        die('<div style="max-width: 800px; margin: 100px auto; text-align: center; padding: 20px;">
            <h2 style="color: var(--primary); margin-bottom: 20px;">Article Not Found</h2>
            <p style="margin-bottom: 30px; color: var(--text);">The article you are looking for does not exist or has been removed.</p>
            <a href="gestionnews.php" class="btn-primary" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 28px; background: var(--primary); color: white; border-radius: var(--border-radius); text-decoration: none; font-weight: 600;">
                <i class="fas fa-newspaper"></i>
                Browse All Articles
            </a>
        </div>');
    }
    
// Check for existing AI summary from gestionnews.php (from localStorage)
// This will be handled by JavaScript to load from localStorage
    
    // ========== GET EXISTING AI SUMMARY FROM LOCALSTORAGE ==========
    // We'll let JavaScript handle this since summaries are stored client-side
    
    // ========== RECOMMENDATION SYSTEM ==========
    
    // Get all articles for recommendations
    $allNews = $newsController->getAllNews();
    
    // Create a recommendation system
    function getArticleRecommendations($currentArticle, $allArticles, $limit = 3) {
        $recommendations = [];
        $currentId = $currentArticle['newsid'];
        $currentContent = strtolower(strip_tags($currentArticle['content']));
        $currentTitle = strtolower($currentArticle['title']);
        
        // Get current article keywords (simple method)
        $currentWords = array_filter(
            str_word_count($currentContent, 1),
            function($word) {
                $stopWords = ['the', 'and', 'for', 'that', 'with', 'this', 'are', 'from', 'have', 'was'];
                return !in_array($word, $stopWords) && strlen($word) > 3;
            }
        );
        
        $currentKeywords = array_slice(array_unique($currentWords), 0, 10);
        
        foreach ($allArticles as $article) {
            if ($article['newsid'] == $currentId) continue;
            
            $score = 0;
            $articleContent = strtolower(strip_tags($article['content']));
            $articleTitle = strtolower($article['title']);
            
            // 1. Category match (40 points)
            if ($article['category'] == $currentArticle['category']) {
                $score += 40;
            }
            
            // 2. Keyword matching in content (30 points)
            foreach ($currentKeywords as $keyword) {
                if (strpos($articleContent, $keyword) !== false) {
                    $score += 3;
                }
                if (strpos($articleTitle, $keyword) !== false) {
                    $score += 5;
                }
            }
            
            // 3. Title similarity (20 points)
            similar_text($currentTitle, $articleTitle, $titleSimilarity);
            $score += $titleSimilarity * 0.2;
            
            // 4. Content length similarity (10 points)
            $currentLength = strlen($currentContent);
            $articleLength = strlen($articleContent);
            $lengthRatio = min($currentLength, $articleLength) / max($currentLength, $articleLength);
            $score += $lengthRatio * 10;
            
            // 5. Recency bonus (newer articles get bonus)
            $currentDate = new DateTime($currentArticle['published_date']);
            $articleDate = new DateTime($article['published_date']);
            $daysDiff = abs($currentDate->diff($articleDate)->days);
            if ($daysDiff < 30) {
                $score += (30 - $daysDiff) * 0.5;
            }
            
            if ($score > 0) {
                $recommendations[] = [
                    'article' => $article,
                    'score' => $score
                ];
            }
        }
        
        // Sort by score (highest first)
        usort($recommendations, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        // Return top recommendations
        return array_slice($recommendations, 0, $limit);
    }
    
    // Get recommendations
    $recommendations = getArticleRecommendations($article, $allNews, 4);
    $recommendedArticles = array_column($recommendations, 'article');
    
    // Format dates
    $publishedDate = new DateTime($article['published_date']);
    $updatedDate = new DateTime($article['updated_date']);
    $isUpdated = $publishedDate->format('Y-m-d') != $updatedDate->format('Y-m-d');
    
    // Estimate reading time
    $wordCount = str_word_count(strip_tags($article['content']));
    $readingTime = ceil($wordCount / 200);
    
    // Create excerpt from content
    $excerpt = strip_tags($article['content']);
    $excerpt = strlen($excerpt) > 200 ? substr($excerpt, 0, 200) . '...' : $excerpt;
    ?>
    
    
    
    <!-- Article Header -->
    <section class="article-header">
        <div class="nav-container">
            <div class="header-content fade-in">
                <span class="article-category">
                    <i class="fas fa-tag"></i>
                    <?php echo htmlspecialchars(ucfirst($article['category'])); ?>
                </span>
                
                <h1 class="article-title">
                    <?php echo htmlspecialchars($article['title']); ?>
                </h1>
                
                <div class="article-meta">
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        Published: <?php echo $publishedDate->format('F j, Y'); ?>
                    </div>
                    
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <?php echo $readingTime; ?> min read
                    </div>
                    
                    <?php if ($isUpdated): ?>
                    <div class="meta-item">
                        <i class="fas fa-edit"></i>
                        Updated: <?php echo $updatedDate->format('M j, Y'); ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="author-box fade-in">
                    <div class="author-avatar">
                        <?php echo substr('Starr', 0, 1); ?>
                    </div>
                    <div class="author-info">
                        <h4>Starr Kindergarten Staff</h4>
                        <p>Educational Content Specialist</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Hero Image -->
    <?php if (!empty($article['image'])): ?>
    <div class="nav-container">
        <div class="hero-section fade-in">
            <img src="../../../uploads/news/<?php echo htmlspecialchars($article['image']); ?>" 
                 alt="<?php echo htmlspecialchars($article['title']); ?>"
                 class="hero-image"
                 onerror="this.src='../kider-1.0.0/img/carousel-1.jpg'">
            
            <div class="image-overlay">
                <p class="image-caption">
                    <i class="fas fa-image me-2"></i>
                    Featured image for "<?php echo htmlspecialchars($article['title']); ?>"
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Article Content</h2>
            <div class="ai-toggle-container">
                <div class="ai-toggle" id="aiToggle">
                    <span class="toggle-label">AI Summary</span>
                    <div class="toggle-switch active" id="toggleSwitch">
                        <div class="toggle-knob"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="article-content fade-in">
            <?php 
            echo nl2br(htmlspecialchars($article['content']));
            ?>
        </div>
        
        <!-- AI Summary Section - Load from localStorage (from gestionnews.php) -->
        <div class="ai-summary-box fade-in" id="aiSummaryBox">
            <div class="d-flex align-items-center mb-3">
                <div class="ai-icon me-3">
                    <i class="fas fa-robot" style="font-size: 1.5rem; color: #10a37f;"></i>
                </div>
                <div>
                    <h4 class="mb-0"><i class="fas fa-brain me-2" style="color: #10a37f;"></i>AI Summary</h4>
                    <small class="text-success fw-bold">
                        <i class="fas fa-brain"></i>
                        Generated locally in your browser
                    </small>
                </div>
            </div>
            
            <div class="ai-summary-content" id="aiSummaryContent">
                <div class="text-center py-4">
                    <div class="spinner mb-3"></div>
                    <p class="text-muted">Loading AI summary...</p>
                    <small class="d-block mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        AI summaries are generated in the news list page (gestionnews.php)
                    </small>
                </div>
            </div>
            
            <div class="ai-disclaimer mt-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    This summary was generated locally in your browser using extractive algorithms.
                    <br>
                    <i class="fas fa-sync-alt me-1"></i>
                    To regenerate or create new summaries, visit the news list page.
                </small>
            </div>
        </div>
        
        
        <!-- Listen to article -->
        <div class="listen-container mt-4 mb-5">
            <button id="listenBtn" class="btn btn-outline-success rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11.536 14.01A8.473 8.473 0 0 0 14.026 8a8.473 8.473 0 0 0-2.49-6.01l-.708.707A7.476 7.476 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303l.708.707z"/>
                    <path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.483 5.483 0 0 1 11.025 8a5.483 5.483 0 0 1-1.61 3.89l.706.706z"/>
                    <path d="M8.707 11.182A4.486 4.486 0 0 0 10.025 8a4.486 4.486 0 0 0-1.318-3.182L8 5.525A3.489 3.489 0 0 1 9.025 8 3.49 3.49 0 0 1 8 10.475l.707.707z"/>
                    <path d="M6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06z"/>
                </svg>
                <span id="listenText">Listen to this article</span>
            </button>
            <small class="text-muted ms-3" id="voiceStatus"></small>
        </div>
        
        <!-- Share Section -->
        <div class="share-section fade-in">
            <h4 class="share-title">Share This Article</h4>
            <div class="share-buttons">
                <button class="share-btn facebook" title="Share on Facebook">
                    <i class="fab fa-facebook-f"></i>
                </button>
                <button class="share-btn twitter" title="Share on Twitter">
                    <i class="fab fa-twitter"></i>
                </button>
                <button class="share-btn linkedin" title="Share on LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                </button>
                <button class="share-btn whatsapp" title="Share on WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </button>
                <button class="share-btn email" title="Share via Email">
                    <i class="fas fa-envelope"></i>
                </button>
                <button class="share-btn instagram" title="Share on Instagram">
                    <i class="fab fa-instagram"></i>
                </button>
            </div>
        </div>
        
        <!-- Action Buttons - NO REGENERATE BUTTON -->
        <div class="action-buttons fade-in">
            <a href="gestionnews.php" class="btn btn-primary">
                <i class="fas fa-newspaper"></i>
                View All Articles
            </a>
            
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="fas fa-print"></i>
                Print Article
            </button>
            
            <a href="../kider-1.0.0/index.php" class="btn btn-outline">
                <i class="fas fa-home"></i>
                Back to Home
            </a>
        </div>
    </main>
    
    <!-- Recommended Articles -->
    <?php if (!empty($recommendedArticles)): ?>
    <section class="related-section">
        <div class="section-header fade-in">
            <h2 class="section-title">Recommended For You</h2>
            <p class="section-subtitle">Articles you might like based on this content</p>
        </div>
        
        <div class="related-grid">
            <?php foreach ($recommendedArticles as $related): ?>
            <div class="related-card fade-in">
                <?php if (!empty($related['image'])): ?>
                <img src="../../../uploads/news/<?php echo htmlspecialchars($related['image']); ?>" 
                     alt="<?php echo htmlspecialchars($related['title']); ?>"
                     class="related-image"
                     onerror="this.src='../kider-1.0.0/img/carousel-1.jpg'">
                <?php else: ?>
                <img src="../kider-1.0.0/img/carousel-1.jpg" 
                     alt="Default article image"
                     class="related-image">
                <?php endif; ?>
                
                <div class="related-content">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="related-category">
                            <?php echo htmlspecialchars(ucfirst($related['category'])); ?>
                        </span>
                        
                        <?php 
                        $articleScore = 0;
                        foreach ($recommendations as $rec) {
                            if ($rec['article']['newsid'] == $related['newsid']) {
                                $articleScore = (int)$rec['score'];
                                break;
                            }
                        }
                        
                        if ($articleScore > 0): 
                        ?>
                        <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">
                            <i class="fas fa-star me-1"></i>
                            <?php echo $articleScore; ?>% match
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="related-title">
                        <a href="article-details.php?id=<?php echo $related['newsid']; ?>" class="text-decoration-none text-dark">
                            <?php echo htmlspecialchars($related['title']); ?>
                        </a>
                    </h3>
                    
                    <p class="related-excerpt">
                        <?php 
                        $relatedExcerpt = strip_tags($related['content']);
                        echo strlen($relatedExcerpt) > 120 ? substr($relatedExcerpt, 0, 120) . '...' : $relatedExcerpt;
                        ?>
                    </p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="article details.php?id=<?php echo $related['newsid']; ?>" class="read-more">
                            Read Article
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        
                        <small class="text-muted">
                            <?php 
                            $pubDate = new DateTime($related['published_date']);
                            echo $pubDate->format('M j, Y');
                            ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Footer -->
    <footer class="article-footer">
        <div class="footer-content">
            <a href="../kider-1.0.0/index.php" class="footer-logo">
                <i class="fas fa-graduation-cap"></i>
                Starr Kindergarten
            </a>
            
            <p class="footer-text">
                Making a brighter future for your child. Quality education in a nurturing environment 
                where children learn, grow, and thrive.
            </p>
            
            <div class="footer-actions">
                <a href="gestionnews.php" class="btn btn-primary">
                    <i class="fas fa-newspaper"></i>
                    View All News
                </a>
                
                <a href="../kider-1.0.0/contact.html" class="btn btn-outline" style="color: white; border-color: rgba(255, 255, 255, 0.3);">
                    <i class="fas fa-phone"></i>
                    Contact Us
                </a>
            </div>
            
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> Starr Kindergarten. All rights reserved.
            </div>
        </div>
    </footer>
    
    <!-- Back to Top -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </a>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== LOAD AI SUMMARY FROM LOCALSTORAGE ==========
        // Load summary that was generated in gestionnews.php
        const articleId = <?php echo $articleId; ?>;
        const aiSummaryContent = document.getElementById('aiSummaryContent');
        
        function loadAISummary() {
            try {
                // Get all saved summaries from localStorage
                const summaries = JSON.parse(localStorage.getItem('article_summaries') || '{}');
                
                if (summaries[articleId]) {
                    const summaryData = summaries[articleId];
                    aiSummaryContent.innerHTML = `
                        <div class="ai-summary-loaded">
                            ${summaryData.content}
                            <div class="mt-2 text-muted small">
                                <i class="fas fa-calendar me-1"></i>
                                Generated: ${new Date(summaryData.timestamp).toLocaleDateString()}
                                <br>
                                <i class="fas fa-ruler me-1"></i>
                                Length: ${summaryData.length}
                            </div>
                        </div>
                    `;
                } else {
                    aiSummaryContent.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-robot fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No AI summary found for this article</p>
                            <small class="d-block mt-2">
                                <i class="fas fa-arrow-right me-1"></i>
                                Go to <a href="gestionnews.php" class="text-decoration-none">News List</a> to generate an AI summary
                            </small>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading AI summary:', error);
                aiSummaryContent.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle text-warning fa-2x mb-3"></i>
                        <p class="text-muted">Unable to load AI summary</p>
                    </div>
                `;
            }
        }
        
        // Load the summary when page loads
        loadAISummary();
        
        // AI Summary Toggle
        const aiToggle = document.getElementById('aiToggle');
        const toggleSwitch = document.getElementById('toggleSwitch');
        const aiSummaryBox = document.getElementById('aiSummaryBox');
        
        if (aiToggle && aiSummaryBox) {
            aiToggle.addEventListener('click', function() {
                toggleSwitch.classList.toggle('active');
                
                if (toggleSwitch.classList.contains('active')) {
                    aiSummaryBox.style.display = 'block';
                    // Save preference to localStorage
                    localStorage.setItem('showAISummary', 'true');
                } else {
                    aiSummaryBox.style.display = 'none';
                    localStorage.setItem('showAISummary', 'false');
                }
            });
            
        // Load saved preference
            const showAISummary = localStorage.getItem('showAISummary');
            if (showAISummary === 'false') {
                toggleSwitch.classList.remove('active');
                aiSummaryBox.style.display = 'none';
            }
        }
        
        // Share buttons functionality
        const shareButtons = document.querySelectorAll('.share-btn');
        const articleUrl = window.location.href;
        const articleTitle = document.querySelector('.article-title').textContent;
        
        shareButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const icon = this.querySelector('i');
                const platform = icon.className;
                
                let shareUrl = '';
                
                if (platform.includes('facebook-f')) {
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(articleUrl)}&quote=${encodeURIComponent(articleTitle)}`;
                } 
                else if (platform.includes('twitter')) {
                    shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(articleUrl)}&text=${encodeURIComponent(articleTitle)}&hashtags=StarrKindergarten`;
                }
                else if (platform.includes('linkedin-in')) {
                    shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(articleUrl)}`;
                }
                else if (platform.includes('whatsapp')) {
                    shareUrl = `https://wa.me/?text=${encodeURIComponent(articleTitle + ' ' + articleUrl)}`;
                }
                else if (platform.includes('envelope')) {
                    window.location.href = `mailto:?subject=${encodeURIComponent(articleTitle)}&body=${encodeURIComponent('Check out this article: ' + articleUrl)}`;
                    return;
                }
                else if (platform.includes('instagram')) {
                    const tempInput = document.createElement('input');
                    tempInput.value = articleUrl;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);
                    
                    alert('Article link copied to clipboard! You can now paste it in your Instagram story or post.');
                    return;
                }
                
                if (shareUrl) {
                    window.open(shareUrl, '_blank', 'width=600,height=400,left=100,top=100');
                }
            });
        });
        
        // Back to top functionality
        const backToTop = document.getElementById('backToTop');
        
        if (backToTop) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTop.classList.add('visible');
                } else {
                    backToTop.classList.remove('visible');
                }
            });
            
            backToTop.addEventListener('click', (e) => {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') {
                    e.preventDefault();
                    return;
                }
                
                const targetElement = document.querySelector(href);
                if (targetElement) {
                    e.preventDefault();
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.related-card, .highlight-box, .share-section, .ai-summary-box').forEach(el => {
            observer.observe(el);
        });
        
        // Make article titles in recommendations clickable
        document.querySelectorAll('.related-title a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
        
        // Make entire recommendation cards clickable
        document.querySelectorAll('.related-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('a') && !e.target.closest('button')) {
                    const link = this.querySelector('.read-more');
                    if (link) {
                        window.location.href = link.href;
                    }
                }
            });
        });
        
        // Text-to-Speech functionality
        const listenBtn = document.getElementById('listenBtn');
        const listenText = document.getElementById('listenText');
        const voiceStatus = document.getElementById('voiceStatus');
        let utterance;
        
        // Get article content for TTS
        const articleContent = document.querySelector('.article-content').innerText;
        
        listenBtn.addEventListener('click', () => {
            if ('speechSynthesis' in window) {
                // Cancel any ongoing speech
                speechSynthesis.cancel();
                
                if (listenText.textContent.includes('Pause')) {
                    speechSynthesis.pause();
                    listenText.innerHTML = '▶ Resume listening';
                    return;
                }
                if (listenText.textContent.includes('Resume')) {
                    speechSynthesis.resume();
                    listenText.innerHTML = '⏸ Pause';
                    return;
                }
                
                utterance = new SpeechSynthesisUtterance(articleContent);
                
                // Choose the best available voice
                const voices = speechSynthesis.getVoices();
                const preferred = voices.find(v => 
                    v.name.includes('Google') || v.name.includes('Microsoft') || v.name.includes('Zira') || v.name.includes('Aria')
                ) || voices[0];
                
                utterance.voice = preferred;
                utterance.rate = 0.9;
                utterance.pitch = 1;
                utterance.lang = 'en-US'; // Change to 'fr-FR' for French
                
                utterance.onstart = () => {
                    listenText.innerHTML = '⏸ Pause listening';
                    voiceStatus.textContent = `🔊 ${preferred?.name || 'Reading aloud...'}`;
                };
                
                utterance.onend = () => {
                    listenText.innerHTML = '🔊 Listen to this article';
                    voiceStatus.textContent = '';
                };
                
                speechSynthesis.speak(utterance);
            } else {
                alert('Sorry, your browser doesn\'t support text-to-speech.');
            }
        });
    });
    </script>
</body>
</html>