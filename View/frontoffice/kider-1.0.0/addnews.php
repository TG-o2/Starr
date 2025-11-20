<?php
require_once __DIR__ . '/../../../Controller/Config.php';
require_once __DIR__ . '/../../../Model/News.php';
require_once __DIR__ . '/../../../Controller/newsController.php';


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category = $_POST['category'] ?? '';
    
    // Handle file upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/news/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
    // Store the path relative to your current location
    $image = 'uploads/news/' . $imageName;
}
    }
    
    // Validate required fields
    if (!empty($title) && !empty($content)) {
        // Create News object - using your original News class structure
        $news = new News();
        $news->setTitle($title);
        $news->setContent($content);
        $news->setPublished_date(date('Y-m-d H:i:s'));
        $news->setImage($image);
        $news->setStatus('published'); // Default status
        $news->setTeacherid(1); // You might want to get this from session
        $news->setCategory($category); // Default category
        
        // Add to database
        $newsController = new NewsController();
        $result = $newsController->addNews($news);
        
        if ($result) {
            // Redirect to news page with success message
            header('Location: gestionnews.php?success=1');
            exit();
        } else {
            header('Location: gestionnews.php?error=1');
            exit();
        }
    } else {
        header('Location: gestionnews.php?error=2');
        exit();
    }
} else {
    // If not POST request, redirect to news page
    header('Location: gestionnews.php');
    exit();
}