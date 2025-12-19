# View Files Implementation Checklist

This document guides you through updating the view files to use the new enhanced controller features.

## Back Office Views

### ✅ lessonList.php (Already Updated with Filters)
**Status**: Filters UI already added
**What's Done**:
- Search functionality ✓
- Age range filter ✓
- Duration filter ✓
- Reset button ✓

**Still Needed**:
- [ ] Add pagination links at bottom
- [ ] Show page number
- [ ] Link to next/previous pages

**Example Code to Add**:
```php
// After the lessons table
<div class="pagination mt-4">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>" class="btn btn-secondary">← Previous</a>
    <?php endif; ?>
    
    <span class="mx-3">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
    
    <?php if ($page < $totalPages): ?>
        <a href="?page=<?php echo $page + 1; ?>" class="btn btn-secondary">Next →</a>
    <?php endif; ?>
</div>
```

---

### 🔴 lessonAdd.php (Needs Update)
**Status**: Needs new fields for category, difficulty, thumbnail
**Required Changes**:

Add to the form:
```html
<!-- Category Selection -->
<div class="form-group">
    <label for="category">Category:</label>
    <select name="category" id="category" class="form-control" required>
        <option value="">Select a category</option>
        <option value="General">General</option>
        <option value="Science">Science</option>
        <option value="Math">Math</option>
        <option value="Language">Language</option>
        <option value="Arts">Arts</option>
        <option value="Other">Other</option>
    </select>
</div>

<!-- Difficulty Level -->
<div class="form-group">
    <label for="difficulty">Difficulty Level:</label>
    <select name="difficulty" id="difficulty" class="form-control" required>
        <option value="beginner">Beginner</option>
        <option value="intermediate">Intermediate</option>
        <option value="advanced">Advanced</option>
    </select>
</div>

<!-- Thumbnail Image -->
<div class="form-group">
    <label for="image">Lesson Thumbnail:</label>
    <input type="file" name="image" id="image" class="form-control-file" accept="image/jpeg,image/png,image/gif">
    <small class="form-text text-muted">Max size: 5MB. Allowed: JPEG, PNG, GIF</small>
</div>

<!-- Quiz Time Limit -->
<div class="form-group">
    <label for="quiz_time_limit">Quiz Time Limit (minutes):</label>
    <input type="number" name="quiz_time_limit" id="quiz_time_limit" 
           class="form-control" value="30" min="0" max="600">
    <small class="form-text text-muted">0 = No time limit</small>
</div>

<!-- Publication Status -->
<div class="form-check">
    <input type="checkbox" name="is_published" id="is_published" class="form-check-input">
    <label class="form-check-label" for="is_published">Publish this lesson</label>
</div>

<!-- Featured Status -->
<div class="form-check">
    <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input">
    <label class="form-check-label" for="is_featured">Mark as featured</label>
</div>
```

**PHP Code Update**:
```php
<?php
require_once '../../Controller/LessonController.php';

$lessonController = new LessonController();
$categories = $lessonController->model->getCategories();

// Get error/success from session
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
```

---

### 🔴 lessonEdit.php (Needs Update)
**Status**: Similar updates as lessonAdd.php
**Required Changes**:

Add same fields as lessonAdd.php but with values from `$lesson`:

```html
<!-- Category Selection - with current value -->
<select name="category" class="form-control" required>
    <option value="General" <?php echo ($lesson['category'] == 'General') ? 'selected' : ''; ?>>General</option>
    <option value="Science" <?php echo ($lesson['category'] == 'Science') ? 'selected' : ''; ?>>Science</option>
    <option value="Math" <?php echo ($lesson['category'] == 'Math') ? 'selected' : ''; ?>>Math</option>
    <!-- etc -->
</select>

<!-- Difficulty - with current value -->
<select name="difficulty" class="form-control" required>
    <option value="beginner" <?php echo ($lesson['difficulty'] == 'beginner') ? 'selected' : ''; ?>>Beginner</option>
    <option value="intermediate" <?php echo ($lesson['difficulty'] == 'intermediate') ? 'selected' : ''; ?>>Intermediate</option>
    <option value="advanced" <?php echo ($lesson['difficulty'] == 'advanced') ? 'selected' : ''; ?>>Advanced</option>
</select>

<!-- Current thumbnail preview -->
<?php if (!empty($lesson['thumbnail_url'])): ?>
    <div class="form-group">
        <label>Current Thumbnail:</label>
        <img src="../assets/uploads/lessons/<?php echo urlencode($lesson['thumbnail_url']); ?>" 
             alt="Thumbnail" style="max-width: 200px;">
    </div>
<?php endif; ?>

<!-- Checkboxes with current state -->
<div class="form-check">
    <input type="checkbox" name="is_published" class="form-check-input" 
           <?php echo $lesson['is_published'] ? 'checked' : ''; ?>>
    <label class="form-check-label">Publish</label>
</div>

<div class="form-check">
    <input type="checkbox" name="is_featured" class="form-check-input" 
           <?php echo $lesson['is_featured'] ? 'checked' : ''; ?>>
    <label class="form-check-label">Featured</label>
</div>
```

---

### ✅ questionList.php (Already Updated with Filters)
**Status**: Filters UI already added
**What's Done**:
- Search by question text ✓
- Filter by lesson ✓
- Filter by points ✓
- Reset button ✓

**Still Needed**:
- [ ] Add pagination
- [ ] Link to edit/delete each question
- [ ] Sort by difficulty if filters show difficulty column

**Example Code**:
```php
<!-- Add Difficulty column to table if available -->
<th>Difficulty</th>

<?php foreach ($questions as $q): ?>
    <tr>
        <!-- ... existing columns ... -->
        <td><?php echo ucfirst($q['difficulty']); ?></td>
        <td>
            <a href="questionEdit.php?id=<?php echo $q['questionId']; ?>" class="btn btn-sm btn-primary">Edit</a>
            <a href="questionDelete.php?id=<?php echo $q['questionId']; ?>" class="btn btn-sm btn-danger" 
               onclick="return confirm('Delete this question?')">Delete</a>
        </td>
    </tr>
<?php endforeach; ?>
```

---

### 🔴 questionAdd.php (Needs Update)
**Status**: Needs new fields for time_limit, difficulty, explanation
**Required Changes**:

```html
<!-- Difficulty Selection -->
<div class="form-group">
    <label for="difficulty">Question Difficulty:</label>
    <select name="difficulty" id="difficulty" class="form-control" required>
        <option value="easy">Easy</option>
        <option value="medium">Medium</option>
        <option value="hard">Hard</option>
    </select>
</div>

<!-- Time Limit (seconds) -->
<div class="form-group">
    <label for="time_limit">Time Limit (seconds):</label>
    <input type="number" name="time_limit" id="time_limit" class="form-control" 
           value="0" min="0" max="600">
    <small class="form-text text-muted">0 = No time limit. Max 600 seconds (10 min)</small>
</div>

<!-- Points -->
<div class="form-group">
    <label for="points">Points:</label>
    <input type="number" name="points" id="points" class="form-control" 
           value="5" min="1" max="100" required>
    <small class="form-text text-muted">Points awarded for correct answer (1-100)</small>
</div>

<!-- Explanation (Optional) -->
<div class="form-group">
    <label for="explanation">Explanation (Optional):</label>
    <textarea name="explanation" id="explanation" class="form-control" rows="3" 
              placeholder="Explain why this is the correct answer"></textarea>
    <small class="form-text text-muted">Shown to students after they answer</small>
</div>
```

---

### 🔴 questionEdit.php (Needs Update)
**Status**: Similar to questionAdd.php with existing values

```php
<?php
// At top of file, get the question
$question = $questionModel->getById($questionId);

// Then display form with values:
?>

<select name="difficulty" class="form-control" required>
    <option value="easy" <?php echo ($question['difficulty'] == 'easy') ? 'selected' : ''; ?>>Easy</option>
    <option value="medium" <?php echo ($question['difficulty'] == 'medium') ? 'selected' : ''; ?>>Medium</option>
    <option value="hard" <?php echo ($question['difficulty'] == 'hard') ? 'selected' : ''; ?>>Hard</option>
</select>

<input type="number" name="time_limit" class="form-control" 
       value="<?php echo $question['time_limit']; ?>" min="0" max="600">

<input type="number" name="points" class="form-control" 
       value="<?php echo $question['points']; ?>" min="1" max="100" required>

<textarea name="explanation" class="form-control" rows="3"><?php 
    echo htmlspecialchars($question['explanation']); 
?></textarea>
```

---

## Front Office Views

### 🔴 lessonDisplay.php (Needs Update)
**Status**: Add featured lessons section
**Required Changes**:

```php
<?php
require_once '../../Controller/LessonController.php';
$lessonController = new LessonController();

// Get featured lessons
$featuredLessons = $lessonController->model->getFeaturedLessons(3);
$categories = $lessonController->model->getCategories();
?>

<!-- Add Featured Section -->
<?php if (!empty($featuredLessons)): ?>
<div class="featured-lessons section my-5">
    <h2 class="mb-4">Featured Lessons</h2>
    <div class="row">
        <?php foreach ($featuredLessons as $lesson): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <?php if (!empty($lesson['thumbnail_url'])): ?>
                        <img src="../assets/uploads/lessons/<?php echo urlencode($lesson['thumbnail_url']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($lesson['title']); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($lesson['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($lesson['description'], 0, 100)); ?>...</p>
                        <span class="badge badge-info"><?php echo htmlspecialchars($lesson['category']); ?></span>
                        <span class="badge badge-warning"><?php echo ucfirst($lesson['difficulty']); ?></span>
                        <a href="lessonDetails.php?id=<?php echo $lesson['lessonId']; ?>" 
                           class="btn btn-primary mt-3 btn-sm">Learn More</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Category Filter Section -->
<div class="lesson-filters my-4">
    <h4>Filter by Category</h4>
    <div class="btn-group" role="group">
        <a href="lessonDisplay.php" class="btn btn-outline-secondary">All</a>
        <?php foreach ($categories as $cat): ?>
            <a href="?category=<?php echo urlencode($cat); ?>" class="btn btn-outline-secondary">
                <?php echo htmlspecialchars($cat); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
```

---

### 🔴 lessonDetails.php (Needs Update)
**Status**: Show full content and metadata
**Required Changes**:

```php
<?php
require_once '../../Controller/LessonController.php';
$controller = new LessonController();
$lesson = $controller->model->getById($lessonId);

if (!$lesson) {
    header('HTTP/1.0 404 Not Found');
    echo "Lesson not found";
    exit;
}
?>

<div class="lesson-details">
    <!-- Lesson Header -->
    <?php if (!empty($lesson['thumbnail_url'])): ?>
        <img src="../assets/uploads/lessons/<?php echo urlencode($lesson['thumbnail_url']); ?>" 
             class="img-fluid mb-4" alt="<?php echo htmlspecialchars($lesson['title']); ?>">
    <?php endif; ?>
    
    <h1><?php echo htmlspecialchars($lesson['title']); ?></h1>
    
    <!-- Metadata -->
    <div class="lesson-meta mb-4">
        <span class="badge badge-primary"><?php echo htmlspecialchars($lesson['category']); ?></span>
        <span class="badge badge-warning"><?php echo ucfirst($lesson['difficulty']); ?></span>
        <span class="badge badge-info">Ages <?php echo htmlspecialchars($lesson['ageRange']); ?></span>
        <span class="badge badge-secondary"><?php echo intval($lesson['duration']); ?> mins</span>
    </div>
    
    <!-- Description -->
    <div class="lesson-description mb-4">
        <h4>Overview</h4>
        <p><?php echo htmlspecialchars($lesson['description']); ?></p>
    </div>
    
    <!-- Full Content -->
    <?php if (!empty($lesson['content'])): ?>
    <div class="lesson-content mb-4">
        <h4>Content</h4>
        <?php echo $lesson['content']; // Note: Use proper HTML purification in production ?>
    </div>
    <?php endif; ?>
    
    <!-- Video (if available) -->
    <?php if (!empty($lesson['video_url'])): ?>
    <div class="lesson-video mb-4">
        <h4>Video</h4>
        <iframe width="100%" height="400" src="<?php echo htmlspecialchars($lesson['video_url']); ?>" 
                frameborder="0" allowfullscreen></iframe>
    </div>
    <?php endif; ?>
    
    <!-- Quiz Link -->
    <div class="lesson-quiz mb-4">
        <h4>Assessment</h4>
        <p>Test your knowledge with a quiz based on this lesson.</p>
        <a href="lessonQuiz.php?id=<?php echo $lesson['lessonId']; ?>" class="btn btn-primary">
            Take Quiz (<?php echo count($questions); ?> questions)
        </a>
    </div>
</div>
```

---

### 🟠 lessonQuiz.php (NEW FILE - Must Create)
**Status**: Needs to be created
**Location**: `View/Front office/Education Corner/lessonQuiz.php`

**Code Template**:

```php
<?php
require_once '../../../Controller/LessonController.php';
require_once '../../../Model/QuizAttemptModel.php';

$lessonId = (int)($_GET['id'] ?? $_POST['lessonId'] ?? 0);

if ($lessonId <= 0) {
    header('HTTP/1.0 404 Not Found');
    echo "Lesson not found";
    exit;
}

$controller = new LessonController();
$lesson = $controller->model->getById($lessonId);

if (!$lesson) {
    header('HTTP/1.0 404 Not Found');
    echo "Lesson not found";
    exit;
}

// For this implementation, we'll use the controller's quiz method
// which handles both display and submission
$controller->quiz($lessonId);
?>
```

This will be displayed by the controller method. You can customize the view by modifying the controller's quiz method to use a separate template file.

---

## Summary of Changes Needed

### Back Office (Admin Panel)
- [ ] `lessonAdd.php` - Add 6 new fields (category, difficulty, thumbnail, quiz_time_limit, is_published, is_featured)
- [ ] `lessonEdit.php` - Add same 6 fields with existing values
- [ ] `lessonList.php` - Add pagination (filters already done)
- [ ] `questionAdd.php` - Add 3 new fields (difficulty, time_limit, explanation, points)
- [ ] `questionEdit.php` - Add same 3 fields with existing values

### Front Office (Student View)
- [ ] `lessonDisplay.php` - Add featured section, category filters, thumbnails
- [ ] `lessonDetails.php` - Show full content with metadata
- [ ] `lessonQuiz.php` - Create new file for quiz interface

---

## Estimated Implementation Time
- Per file: 15-20 minutes
- Total: 2-3 hours for all views
- Testing: 1 hour

---

## Testing Checklist
- [ ] Create lesson with all new fields
- [ ] Upload thumbnail image (verify it displays)
- [ ] Verify featured lessons appear on homepage
- [ ] Test category filtering
- [ ] Create question with time limit
- [ ] Take quiz and verify timer works
- [ ] Check quiz results display score
- [ ] Verify attempt is recorded in database

