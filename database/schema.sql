CREATE DATABASE IF NOT EXISTS `Starr` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `Starr`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `USER_BADGES`;
DROP TABLE IF EXISTS `BADGE_DEFINITIONS`;
DROP TABLE IF EXISTS `POINT_TRANSACTIONS`;
DROP TABLE IF EXISTS `points_history`;
DROP TABLE IF EXISTS `STARR_POINTS`;
DROP TABLE IF EXISTS `responses`;
DROP TABLE IF EXISTS `report`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `questions`;
DROP TABLE IF EXISTS `lessons`;
DROP TABLE IF EXISTS `news`;
DROP TABLE IF EXISTS `quiz_attempts`;
DROP TABLE IF EXISTS `content_views`;
DROP TABLE IF EXISTS `user`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `user` (
  `user_id` INT AUTO_INCREMENT PRIMARY KEY,
  `password` VARCHAR(255) NOT NULL,
  `fname` VARCHAR(100) NOT NULL,
  `lname` VARCHAR(100) NOT NULL,
  `DOB` DATE DEFAULT NULL,
  `profilePicture` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `role` ENUM('student','teacher','parent','admin') NOT NULL DEFAULT 'student',
  `avatar` VARCHAR(255) DEFAULT NULL,
  `verified` TINYINT(1) NOT NULL DEFAULT 0,
  `is_banned` TINYINT(1) NOT NULL DEFAULT 0,
  `is_approved` TINYINT(1) NOT NULL DEFAULT 0,
  `verification_token` VARCHAR(255) DEFAULT NULL,
  `approval_token` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lessons` (
  `lessonId` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `content` LONGTEXT DEFAULT NULL,
  `ageRange` VARCHAR(50) DEFAULT NULL,
  `average_age` INT DEFAULT 12,
  `category` VARCHAR(100) DEFAULT 'General',
  `difficulty` ENUM('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  `duration` INT DEFAULT 0,
  `image` VARCHAR(255) DEFAULT NULL,
  `thumbnail_url` VARCHAR(255) DEFAULT NULL,
  `video_url` VARCHAR(255) DEFAULT NULL,
  `is_published` TINYINT(1) NOT NULL DEFAULT 0,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `quiz_time_limit` INT DEFAULT 30,
  `created_by` INT DEFAULT NULL,
  `updated_by` INT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_lessons_created_by` FOREIGN KEY (`created_by`) REFERENCES `user`(`user_id`) ON DELETE SET NULL,
  CONSTRAINT `fk_lessons_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `user`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `questions` (
  `questionId` INT AUTO_INCREMENT PRIMARY KEY,
  `lessonId` INT NOT NULL,
  `question` TEXT NOT NULL,
  `optionA` VARCHAR(255) NOT NULL,
  `optionB` VARCHAR(255) NOT NULL,
  `optionC` VARCHAR(255) NOT NULL,
  `optionD` VARCHAR(255) NOT NULL,
  `goodAnswer` VARCHAR(255) NOT NULL,
  `points` INT NOT NULL DEFAULT 5,
  `difficulty` ENUM('easy','medium','hard') NOT NULL DEFAULT 'easy',
  `time_limit` INT NOT NULL DEFAULT 0,
  `explanation` LONGTEXT DEFAULT NULL,
  CONSTRAINT `fk_questions_lesson` FOREIGN KEY (`lessonId`) REFERENCES `lessons`(`lessonId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `news` (
  `newsid` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `published_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` DATETIME NULL DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'published',
  `teacherid` INT DEFAULT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  CONSTRAINT `fk_news_teacher` FOREIGN KEY (`teacherid`) REFERENCES `user`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `posts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `subjects` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `number_messages` INT NOT NULL DEFAULT 0,
  `user_id` INT NOT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `view_count` INT NOT NULL DEFAULT 0,
  `like_count` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `comments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `news_id` INT DEFAULT NULL,
  `post_id` INT DEFAULT NULL,
  `user_id` INT DEFAULT NULL,
  `content` TEXT NOT NULL,
  `number_replies` INT NOT NULL DEFAULT 0,
  `like_count` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_comments_news` FOREIGN KEY (`news_id`) REFERENCES `news`(`newsid`) ON DELETE CASCADE,
  CONSTRAINT `fk_comments_post` FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `post_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `number_replies` INT NOT NULL DEFAULT 0,
  `user_id` INT NOT NULL,
  `like_count` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_messages_post` FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_messages_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `report` (
  `reportId` INT AUTO_INCREMENT PRIMARY KEY,
  `reportType` VARCHAR(50) NOT NULL,
  `reportedUserId` INT DEFAULT NULL,
  `reportedPostId` INT DEFAULT NULL,
  `reportedCommentId` INT DEFAULT NULL,
  `reportedLessonId` INT DEFAULT NULL,
  `reportReason` VARCHAR(255) NOT NULL,
  `reportDescription` TEXT DEFAULT NULL,
  `reportDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reporterId` INT NOT NULL,
  `reportStatus` VARCHAR(50) NOT NULL DEFAULT 'open',
  `evidencePath` VARCHAR(255) DEFAULT NULL,
  `severity` VARCHAR(20) NOT NULL DEFAULT 'medium',
  CONSTRAINT `fk_report_reporter` FOREIGN KEY (`reporterId`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `responses` (
  `responseId` INT AUTO_INCREMENT PRIMARY KEY,
  `reportId` INT NOT NULL,
  `responderId` INT NOT NULL,
  `responseText` TEXT NOT NULL,
  `responseDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `actionTaken` VARCHAR(255) DEFAULT NULL,
  `allowUserReply` TINYINT(1) NOT NULL DEFAULT 0,
  CONSTRAINT `fk_responses_report` FOREIGN KEY (`reportId`) REFERENCES `report`(`reportId`) ON DELETE CASCADE,
  CONSTRAINT `fk_responses_responder` FOREIGN KEY (`responderId`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `STARR_POINTS` (
  `starr_id` INT PRIMARY KEY,
  `total_points` INT NOT NULL DEFAULT 0,
  `last_login_date` DATETIME DEFAULT NULL,
  `login_streak` INT NOT NULL DEFAULT 0,
  CONSTRAINT `fk_starr_points_user` FOREIGN KEY (`starr_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `points_history` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `source_type` VARCHAR(100) NOT NULL,
  `source_id` INT DEFAULT NULL,
  `points_change` INT NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_points_history_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `POINT_TRANSACTIONS` (
  `transaction_id` INT AUTO_INCREMENT PRIMARY KEY,
  `starr_id` INT NOT NULL,
  `points_change` INT NOT NULL,
  `reason` VARCHAR(255) NOT NULL,
  `created_by` INT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_point_transactions_user` FOREIGN KEY (`starr_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_point_transactions_creator` FOREIGN KEY (`created_by`) REFERENCES `user`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `BADGE_DEFINITIONS` (
  `badge_id` INT AUTO_INCREMENT PRIMARY KEY,
  `badge_name` VARCHAR(100) NOT NULL,
  `tier_level` INT NOT NULL,
  `min_points` INT NOT NULL,
  `icon` VARCHAR(255) DEFAULT NULL,
  `color` VARCHAR(50) DEFAULT NULL,
  `description` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `USER_BADGES` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `starr_id` INT NOT NULL,
  `badge_id` INT NOT NULL,
  `earned_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  UNIQUE KEY `uq_user_badge` (`starr_id`, `badge_id`),
  CONSTRAINT `fk_user_badges_user` FOREIGN KEY (`starr_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_badges_badge` FOREIGN KEY (`badge_id`) REFERENCES `BADGE_DEFINITIONS`(`badge_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `quiz_attempts` (
  `attempt_id` INT AUTO_INCREMENT PRIMARY KEY,
  `lesson_id` INT NOT NULL,
  `student_id` INT DEFAULT NULL,
  `score` INT NOT NULL DEFAULT 0,
  `total_questions` INT NOT NULL DEFAULT 0,
  `time_taken` INT NOT NULL DEFAULT 0,
  `timed_out` TINYINT(1) NOT NULL DEFAULT 0,
  `answers` LONGTEXT DEFAULT NULL,
  `completed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_quiz_attempts_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`lessonId`) ON DELETE CASCADE,
  CONSTRAINT `fk_quiz_attempts_student` FOREIGN KEY (`student_id`) REFERENCES `user`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `content_views` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `content_type` VARCHAR(50) NOT NULL,
  `content_id` INT NOT NULL,
  `user_id` INT DEFAULT NULL,
  `viewed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_content_views_type_id` (`content_type`, `content_id`),
  CONSTRAINT `fk_content_views_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user` (`user_id`, `password`, `fname`, `lname`, `DOB`, `profilePicture`, `description`, `username`, `email`, `role`, `avatar`, `verified`, `is_banned`, `is_approved`, `verification_token`, `approval_token`) VALUES
(1, 'password_hash_placeholder', 'Admin', 'User', '1990-01-01', NULL, 'Site administrator', 'admin', 'admin@starr.local', 'admin', NULL, 1, 0, 1, NULL, NULL),
(2, 'password_hash_placeholder', 'Teacher', 'User', '1992-05-15', NULL, 'Example teacher account', 'teacher', 'teacher@starr.local', 'teacher', NULL, 1, 0, 1, NULL, NULL),
(3, 'password_hash_placeholder', 'Student', 'User', '2012-09-10', NULL, 'Example student account', 'student', 'student@starr.local', 'student', NULL, 1, 0, 1, NULL, NULL);
(2339, 'placeholder_password', 'Test', 'User', '2000-01-01', NULL, NULL, 'testuser2339', 'testuser2339@starr.local', 'student', NULL, 1, 0, 1, NULL, NULL);

INSERT INTO `STARR_POINTS` (`starr_id`, `total_points`, `last_login_date`, `login_streak`) VALUES
(1, 120, NOW(), 4),
(2, 220, NOW(), 9),
(3, 45, NOW(), 2);

INSERT INTO `BADGE_DEFINITIONS` (`badge_name`, `tier_level`, `min_points`, `icon`, `color`, `description`) VALUES
('Starter', 1, 0, 'badge-starter', '#6c757d', 'First steps on Starr'),
('Explorer', 2, 100, 'badge-explorer', '#198754', 'Active learner'),
('Champion', 3, 200, 'badge-champion', '#0d6efd', 'Top contributor');

INSERT INTO `USER_BADGES` (`starr_id`, `badge_id`, `earned_date`, `is_active`) VALUES
(1, 2, NOW(), 1),
(2, 3, NOW(), 1),
(3, 1, NOW(), 1);

INSERT INTO `lessons` (`lessonId`, `title`, `description`, `content`, `ageRange`, `average_age`, `category`, `difficulty`, `duration`, `image`, `thumbnail_url`, `video_url`, `is_published`, `is_featured`, `quiz_time_limit`, `created_by`, `updated_by`, `created_at`) VALUES
(1, 'Introduction to Safety', 'A short lesson about online safety.', 'Be careful with personal information and suspicious links.', '8-12', 10, 'Safety', 'beginner', 15, NULL, NULL, NULL, 1, 1, 30, 2, 2, NOW());

INSERT INTO `questions` (`questionId`, `lessonId`, `question`, `optionA`, `optionB`, `optionC`, `optionD`, `goodAnswer`, `points`, `difficulty`, `time_limit`, `explanation`) VALUES
(1, 1, 'What should you avoid sharing online?', 'Favorite color', 'Home address', 'School subject', 'Pet name', 'Home address', 5, 'easy', 30, 'Personal details should stay private.'),
(2, 1, 'What should you do with strange links?', 'Click them immediately', 'Ignore and report them', 'Share them with friends', 'Open them later', 'Ignore and report them', 5, 'easy', 30, 'Suspicious links should be reported.'),
(3, 1, 'Who can help if something looks unsafe?', 'No one', 'A trusted adult', 'Only a stranger', 'Nobody', 'A trusted adult', 5, 'easy', 30, 'Trusted adults can help you stay safe online.');

INSERT INTO `news` (`newsid`, `title`, `content`, `published_date`, `updated_date`, `status`, `teacherid`, `category`, `image`) VALUES
(1, 'Welcome to Starr', 'A sample news item for the demo database.', NOW(), NOW(), 'published', 2, 'General', NULL);

INSERT INTO `posts` (`id`, `subjects`, `content`, `number_messages`, `user_id`, `category`, `view_count`, `like_count`, `created_at`) VALUES
(1, 'Introduce yourself', 'This is a starter community post for testing.', 1, 3, 'Community', 12, 3, NOW());

INSERT INTO `comments` (`id`, `news_id`, `post_id`, `user_id`, `content`, `number_replies`, `like_count`, `created_at`) VALUES
(1, 1, NULL, 3, 'Great to see the project live!', 0, 2, NOW()),
(2, NULL, 1, 2, 'Welcome everyone to the community post.', 0, 1, NOW());

INSERT INTO `messages` (`id`, `post_id`, `content`, `number_replies`, `user_id`, `like_count`, `created_at`) VALUES
(1, 1, 'Thanks for joining the discussion.', 0, 2, 1, NOW());

INSERT INTO `report` (`reportId`, `reportType`, `reportedUserId`, `reportedPostId`, `reportedCommentId`, `reportedLessonId`, `reportReason`, `reportDescription`, `reportDate`, `reporterId`, `reportStatus`, `evidencePath`, `severity`) VALUES
(1, 'post', NULL, 1, NULL, NULL, 'Inappropriate content', 'Sample moderation report used as seed data.', NOW(), 3, 'open', NULL, 'medium');

INSERT INTO `responses` (`responseId`, `reportId`, `responderId`, `responseText`, `responseDate`, `status`, `actionTaken`, `allowUserReply`) VALUES
(1, 1, 1, 'We reviewed this report and will monitor the post.', NOW(), 'reviewed', 'Picked Up', 1);

INSERT INTO `points_history` (`id`, `user_id`, `source_type`, `source_id`, `points_change`, `description`, `created_at`) VALUES
(1, 3, 'lesson', 1, 10, 'Completed the sample lesson.', NOW()),
(2, 3, 'post', 1, 5, 'Participated in the community post.', NOW());

INSERT INTO `POINT_TRANSACTIONS` (`transaction_id`, `starr_id`, `points_change`, `reason`, `created_by`, `created_at`) VALUES
(1, 3, 10, 'Lesson completion bonus', 1, NOW()),
(2, 2, 20, 'Helpful community contribution', 1, NOW());

INSERT INTO `quiz_attempts` (`attempt_id`, `lesson_id`, `student_id`, `score`, `total_questions`, `time_taken`, `timed_out`, `answers`, `completed_at`) VALUES
(1, 1, 3, 2, 3, 85, 0, '{"1":"Home address","2":"Ignore and report them","3":"A trusted adult"}', NOW());

INSERT INTO `content_views` (`id`, `content_type`, `content_id`, `user_id`, `viewed_at`) VALUES
(1, 'lesson', 1, 3, NOW()),
(2, 'news', 1, 3, NOW());
