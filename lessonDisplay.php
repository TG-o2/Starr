<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Lessons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #858796;
            --success: #1cc88a;
            --danger: #e74a3b;
            --warning: #f6c23e;
            --info: #36b9cc;
            --light: #f8f9fc;
            --dark: #2e3338;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #e9ecef 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--primary) 0%, #224abe 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
            margin-bottom: 50px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .header h1 i {
            font-size: 3rem;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.95;
            margin: 0;
        }

        /* Container Styles */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Lessons Grid */
        .lessons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        /* Lesson Card */
        .lesson-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .lesson-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        /* Lesson Image */
        .lesson-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--primary) 0%, #224abe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .lesson-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Lesson Info */
        .lesson-info {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .lesson-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .lesson-meta {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .badge-custom {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-age {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-duration {
            background: #fff3e0;
            color: #f57c00;
        }

        .lesson-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 15px;
            flex: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .lesson-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }

        .btn-lesson {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .btn-view {
            background: var(--primary);
            color: white;
        }

        .btn-view:hover {
            background: #3d5cc3;
            transform: scale(1.02);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-state p {
            font-size: 1.1rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.8rem;
            }

            .lessons-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1><i class="fas fa-graduation-cap"></i> Available Lessons</h1>
        <p>Choose a lesson to start learning today!</p>
    </div>

    <!-- Lessons Container -->
    <div class="container">
        <?php if (!empty($lessons)): ?>
            <div class="lessons-grid">
                <?php foreach ($lessons as $lesson): ?>
                    <div class="lesson-card">
                        <div class="lesson-image">
                            <?php if (!empty($lesson['image'])): ?>
                                <img src="<?php echo htmlspecialchars($lesson['image']); ?>" alt="<?php echo htmlspecialchars($lesson['title']); ?>">
                            <?php else: ?>
                                <i class="fas fa-book"></i>
                            <?php endif; ?>
                        </div>
                        <div class="lesson-info">
                            <h3 class="lesson-title"><?php echo htmlspecialchars($lesson['title']); ?></h3>
                            <div class="lesson-meta">
                                <span class="badge-custom badge-age">
                                    <i class="fas fa-child"></i> <?php echo htmlspecialchars($lesson['ageRange']); ?>
                                </span>
                                <span class="badge-custom badge-duration">
                                    <i class="fas fa-clock"></i> <?php echo htmlspecialchars($lesson['duration']); ?> min
                                </span>
                            </div>
                            <p class="lesson-description"><?php echo htmlspecialchars($lesson['description']); ?></p>
                            <div class="lesson-actions">
                                <a href="/lessons_project/views/front/lessonDetails_direct.php?lessonId=<?php echo htmlspecialchars($lesson['lessonId']); ?>" class="btn-lesson btn-view">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No lessons available yet. Please check back soon!</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
           

    </style>
</head>
<body>
    
   