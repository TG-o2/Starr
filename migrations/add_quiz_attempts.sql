-- Create Quiz Attempts Table
-- This tracks student quiz attempts and scores (one per student per lesson)
CREATE TABLE IF NOT EXISTS quiz_attempts (
    attemptId INT PRIMARY KEY AUTO_INCREMENT,
    lessonId INT NOT NULL,
    studentId VARCHAR(100) NOT NULL DEFAULT 'guest',
    score INT NOT NULL DEFAULT 0,
    totalQuestions INT NOT NULL DEFAULT 0,
    scorePercentage INT NOT NULL DEFAULT 0,
    attemptedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_attempt (lessonId, studentId),
    FOREIGN KEY (lessonId) REFERENCES lessons(lessonId) ON DELETE CASCADE
);
