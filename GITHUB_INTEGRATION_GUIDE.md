# Education Corner - GitHub Integration Guide

## Overview
This document outlines the integration of advanced features from the GitHub `education-corner` branch into your local Starr project.

## What Has Been Created

### 1. Enhanced Controllers

#### LessonController_NEW.php
**Key Features:**
- **Type Declarations**: All methods have strict typing for better code quality
- **Comprehensive File Validation**: 
  - MIME type checking (image/jpeg, image/png, image/gif for images)
  - File size limit (5MB)
  - Automatic filename generation with timestamps
- **Pagination Support**: Built-in pagination with configurable page size
- **Featured Lessons**: `getFeaturedLessons()` method to show highlighted content
- **Advanced Filtering**: Support for category, difficulty, and search filters
- **Quiz Management**: `quiz()` method handles quiz display and submission
- **Score Calculation**: Automatic score computation with time-out handling

**New Methods:**
- `list($page, $filters)`: Paginated lesson listing with filters
- `quiz($lessonId)`: Quiz interface with timer support
- `handleQuizSubmission()`: Process quiz answers and calculate scores
- `handleFileUpload()`: Secure file upload with validation
- `computeAverageAge()`: Parse age range strings (e.g., "5-18")

#### QuestionController_NEW.php
**Key Features:**
- **Type Declarations**: Strict typing throughout
- **Enhanced Validation**: Comprehensive input validation for all fields
- **Difficulty Levels**: Support for easy/medium/hard classifications
- **Time Limits**: Per-question time limit support (0-600 seconds)
- **Explanations**: Optional explanations for each question
- **Points System**: Flexible point allocation (1-100 per question)

**New Methods:**
- `list($page, $filters)`: Paginated question listing
- `validateQuestionData()`: Comprehensive input validation
- `getByLesson()`: Fetch all questions for a lesson

### 2. Enhanced Models

#### Lesson_NEW.php
**New Methods:**
- `getFeaturedLessons($limit)`: Get featured lessons
- `getPopularLessons($limit)`: Get most active lessons
- `getRelatedLessons($id, $limit)`: Get lessons in same category/difficulty
- `getCategories()`: Get all available categories
- `countAll($filters)`: Count with filters
- `saveLessonMeta()`: Update lesson metadata

**Enhanced Features:**
- Support for all new database columns (category, difficulty, is_published, etc.)
- Filtering by category, difficulty, published status
- Search functionality in title and description

#### Question_NEW.php
**New Methods:**
- `getAll($filters, $limit, $offset)`: Paginated query with filters
- `getQuestionsWithTimeLimit()`: Get all timed questions
- `getDifficultySummary()`: Statistics by difficulty level
- `countAll($filters)`: Count with filters

**Enhanced Features:**
- Support for difficulty, time_limit, and explanation fields
- Cross-table joins with lesson names
- Comprehensive filtering

#### QuizAttemptModel_NEW.php (NEW)
**Purpose**: Track student quiz attempts and performance

**Key Methods:**
- `recordAttempt()`: Save quiz submission
- `getStudentAttempts()`: Get student's quiz history
- `getLessonAttempts()`: Get all attempts for a lesson
- `getStudentBestAttempt()`: Get highest score for student/lesson
- `getStudentAverageScore()`: Calculate average performance
- `getLessonStatistics()`: Class-wide performance metrics
- `deleteAttempt()`: Remove specific attempt

**Data Structure:**
- Stores: score, total_questions, time_taken, timed_out status, answers (JSON)
- Calculates: percentage scores, average times, timeout counts

### 3. Database Schema Enhancements

The migration script (already executed) added:

**Lessons Table:**
- `category` VARCHAR(100) - Lesson category (General, Science, Math, etc.)
- `difficulty` ENUM('beginner','intermediate','advanced') - Difficulty level
- `average_age` INT - Average age for lesson
- `content` LONGTEXT - Full lesson content
- `thumbnail_url` VARCHAR(255) - Lesson thumbnail image
- `video_url` VARCHAR(255) - Optional embedded video
- `is_published` BOOLEAN - Publication status
- `is_featured` BOOLEAN - Feature flag
- `quiz_time_limit` INT - Quiz timeout (seconds)
- `created_by` INT - Creator user ID
- `updated_by` INT - Last editor user ID
- `updated_at` TIMESTAMP - Last modification time

**Questions Table:**
- `time_limit` INT - Per-question timeout (seconds)
- `difficulty` ENUM('easy','medium','hard') - Question difficulty
- `explanation` LONGTEXT - Answer explanation

**New Table: quiz_attempts**
- `attempt_id` INT PRIMARY KEY
- `lesson_id` INT - References lesson
- `student_id` INT - References user
- `score` INT - Points earned
- `total_questions` INT - Number of questions
- `time_taken` INT - Seconds spent
- `timed_out` BOOLEAN - Whether quiz expired
- `answers` JSON - User responses
- `completed_at` TIMESTAMP

## Integration Steps

### Step 1: Backup Current Files
```bash
# In Controller directory
copy LessonController.php LessonController_BACKUP.php
copy QuestionController.php QuestionController_BACKUP.php

# In Model directory
copy Lesson.php Lesson_BACKUP.php
copy Question.php Question_BACKUP.php
```

### Step 2: Replace Files
```bash
# Replace with new versions
move LessonController_NEW.php LessonController.php
move QuestionController_NEW.php QuestionController.php
move Lesson_NEW.php Lesson.php
move Question_NEW.php Question.php

# Add new model
copy QuizAttemptModel_NEW.php QuizAttemptModel.php
```

### Step 3: Update View Files
The following view files need to be updated to use new features:

**Back Office:**
- `lessonList.php` - Add pagination and filters (already has search/filter UI)
- `lessonAdd.php` - Add category, difficulty, thumbnail fields
- `lessonEdit.php` - Add new metadata fields
- `questionList.php` - Add pagination (already has filters)
- `questionAdd.php` - Add time_limit, difficulty, explanation fields
- `questionEdit.php` - Add new fields

**Front Office:**
- `lessonDisplay.php` - Show featured lessons, categories
- `lessonDetails.php` - Display full content with metadata
- `lessonQuiz.php` - NEW - Quiz interface with timer

### Step 4: Create Quiz Views
Create the following new view file:

**File**: `View/Front office/Education Corner/lessonQuiz.php`
This should display:
- Quiz title from lesson
- Timer countdown (if quiz_time_limit set)
- Questions with options
- Submit button
- Results display with score percentage
- Explanation after submission

### Step 5: Verify Database
```php
// Quick verification script
$pdo = Config::getConnexion();

// Check new columns exist
$stmt = $pdo->query("DESCRIBE lessons");
$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo "Lesson columns: " . implode(", ", $columns) . "\n";

// Check quiz_attempts table
$stmt = $pdo->query("SHOW TABLES LIKE 'quiz_attempts'");
$table = $stmt->fetch();
echo "Quiz attempts table exists: " . ($table ? "YES" : "NO") . "\n";
```

## Key Differences from Your Previous Version

| Feature | Before | After |
|---------|--------|-------|
| File Validation | None | MIME type + size check |
| Pagination | Manual | Built-in support |
| Categories | String field | Distinct values query |
| Difficulty Levels | Basic | 3-level system with validation |
| Time Limits | N/A | Per-quiz and per-question |
| Quiz Scoring | N/A | Automatic score calculation |
| Attempt Tracking | N/A | Full history with statistics |
| Type Safety | Loose | Strict typing (declare) |
| Featured Lessons | N/A | Featured flag + methods |
| Age Range | String | Computed average |
| Explanations | N/A | Per-question explanations |

## Usage Examples

### 1. Display Featured Lessons
```php
$lessonController = new LessonController();
$featured = $lessonController->model->getFeaturedLessons(3);

foreach ($featured as $lesson) {
    echo $lesson['title'] . " ({$lesson['category']})";
}
```

### 2. List Questions with Pagination
```php
$questionController = new QuestionController();
$page = $_GET['page'] ?? 1;
$questionController->list($page, ['lessonId' => $lessonId]);
```

### 3. Record Quiz Attempt
```php
$quizModel = new QuizAttemptModel();
$attemptId = $quizModel->recordAttempt(
    $lessonId,
    $studentId,
    $score,
    $totalQuestions,
    $timeTaken,
    $timedOut,
    $answers
);

// Get statistics
$stats = $quizModel->getLessonStatistics($lessonId);
echo "Average score: " . round($stats['avg_score']) . "%";
```

### 4. Validate Question Data
```php
try {
    $questionController->validateQuestionData($postData);
    // Data is valid
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## File Locations

```
Controller/
  ├── LessonController.php (UPDATED)
  ├── QuestionController.php (UPDATED)
  ├── MailHelper.php (unchanged)
  └── ...

Model/
  ├── Lesson.php (UPDATED)
  ├── Question.php (UPDATED)
  ├── QuizAttemptModel.php (NEW)
  └── ...

View/Front office/Education Corner/
  ├── lessonDisplay.php (needs update)
  ├── lessonDetails.php (needs update)
  ├── lessonQuiz.php (NEW - to create)
  ├── questionList.php (already has filters)
  └── ...

View/Back office/Education Corner/
  ├── lessonList.php (already has filters, verify pagination)
  ├── lessonAdd.php (needs category, difficulty, thumbnail)
  ├── lessonEdit.php (needs metadata fields)
  ├── questionList.php (already has filters)
  ├── questionAdd.php (needs time_limit, difficulty, explanation)
  └── questionEdit.php (needs new fields)
```

## Performance Optimizations

The database migration created indexes for:
- `idx_lesson_published` - Speeds up published lesson queries
- `idx_lesson_featured` - Speeds up featured lesson queries
- `idx_lesson_category` - Speeds up category filtering
- `idx_lesson_difficulty` - Speeds up difficulty filtering
- `idx_question_difficulty` - Speeds up question difficulty queries

## Security Considerations

1. **File Upload Validation**:
   - MIME type checking prevents script uploads
   - Size limit (5MB) prevents storage abuse
   - Unique filenames prevent collisions

2. **Type Declarations**:
   - Strict typing prevents type confusion attacks
   - Better IDE support and static analysis

3. **SQL Injection Prevention**:
   - All queries use prepared statements
   - Parameter binding throughout

4. **Input Validation**:
   - Comprehensive validation in controllers
   - Range checking for numeric fields
   - Enum validation for known values

## Testing Checklist

- [ ] Create new lesson with image upload
- [ ] Verify image displays correctly
- [ ] Create lesson with category and difficulty
- [ ] Test featured lessons display
- [ ] Create questions with time limits
- [ ] Test quiz submission and scoring
- [ ] Verify pagination works
- [ ] Test search and filters
- [ ] Check quiz attempt tracking
- [ ] Verify statistics calculations

## Troubleshooting

**Problem**: Images not uploading
**Solution**: Ensure upload directory exists and is writable: `View/Front office/assets/uploads/lessons/`

**Problem**: Quiz not timing out
**Solution**: Verify `quiz_time_limit` is set to > 0 when creating lesson

**Problem**: Questions not showing difficulty
**Solution**: Ensure Question table has `difficulty` column from migration

**Problem**: "Class not found" errors
**Solution**: Verify all require_once statements at top of controllers include correct paths

## Next Steps

1. Replace the old files with new versions
2. Create/update view files
3. Test all functionality
4. Monitor database performance
5. Consider implementing:
   - Progress tracking per student
   - Lesson recommendations
   - Certification system
   - Leaderboards

