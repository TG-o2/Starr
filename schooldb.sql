-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 15 déc. 2025 à 00:37
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `schooldb`
--

-- --------------------------------------------------------

--
-- Structure de la table `lessons`
--

CREATE TABLE `lessons` (
  `lessonId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `ageRange` varchar(50) DEFAULT '5-18',
  `average_age` int(11) DEFAULT 12,
  `description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `category` varchar(100) DEFAULT 'General',
  `difficulty` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `duration` int(11) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `quiz_time_limit` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT 1,
  `updated_by` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lessons`
--

INSERT INTO `lessons` (`lessonId`, `title`, `ageRange`, `average_age`, `description`, `content`, `category`, `difficulty`, `duration`, `is_published`, `is_featured`, `thumbnail_url`, `video_url`, `quiz_time_limit`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Xx', '5-18', 12, 'ZZZ', '', 'General', 'beginner', 1, 0, 0, NULL, NULL, NULL, '2025-12-14 19:31:26', '2025-12-14 19:31:26', 1, 1),
(2, 'Xxx', '5-18', 12, 'ZZZ', '', 'General', 'beginner', 1, 0, 0, NULL, NULL, NULL, '2025-12-14 21:14:35', '2025-12-14 21:14:35', 1, 1),
(3, 'Xttxx', '5-18', 12, 'ZZZ', '', 'General', 'beginner', 1, 0, 0, NULL, NULL, NULL, '2025-12-14 21:17:25', '2025-12-14 21:17:25', 1, 1),
(4, 'qqq', '5-18', 12, 'sqd', '', 'General', 'beginner', 1, 0, 0, NULL, NULL, NULL, '2025-12-14 21:24:18', '2025-12-14 21:24:18', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `lesson_ratings`
--

CREATE TABLE `lesson_ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','true_false','short_answer') NOT NULL,
  `points` int(11) DEFAULT 1,
  `order_index` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `question_options`
--

CREATE TABLE `question_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `order_index` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `score` decimal(5,2) DEFAULT 0.00,
  `max_score` int(11) DEFAULT 0,
  `time_taken` int(11) DEFAULT 0,
  `is_timeout` tinyint(1) DEFAULT 0,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quiz_attempt_answers`
--

CREATE TABLE `quiz_attempt_answers` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `points_earned` decimal(5,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_lesson_progress`
--

CREATE TABLE `user_lesson_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `progress_percentage` int(11) DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lessonId`),
  ADD KEY `idx_created_by` (`created_by`),
  ADD KEY `idx_updated_by` (`updated_by`);

--
-- Index pour la table `lesson_ratings`
--
ALTER TABLE `lesson_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_user_lesson_rating` (`user_id`,`lesson_id`),
  ADD KEY `idx_lesson_id` (`lesson_id`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lesson_id` (`lesson_id`);

--
-- Index pour la table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_question_id` (`question_id`);

--
-- Index pour la table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student_lesson` (`student_id`,`lesson_id`),
  ADD KEY `idx_lesson_id` (`lesson_id`);

--
-- Index pour la table `quiz_attempt_answers`
--
ALTER TABLE `quiz_attempt_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_attempt_id` (`attempt_id`),
  ADD KEY `idx_question_id` (`question_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `user_lesson_progress`
--
ALTER TABLE `user_lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_user_lesson` (`user_id`,`lesson_id`),
  ADD KEY `idx_lesson_id` (`lesson_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `lessonId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `lesson_ratings`
--
ALTER TABLE `lesson_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `quiz_attempt_answers`
--
ALTER TABLE `quiz_attempt_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_lesson_progress`
--
ALTER TABLE `user_lesson_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `lesson_ratings`
--
ALTER TABLE `lesson_ratings`
  ADD CONSTRAINT `fk_rating_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lessonId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_question_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lessonId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `question_options`
--
ALTER TABLE `question_options`
  ADD CONSTRAINT `fk_option_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `fk_attempt_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lessonId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `quiz_attempt_answers`
--
ALTER TABLE `quiz_attempt_answers`
  ADD CONSTRAINT `fk_answer_attempt` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_lesson_progress`
--
ALTER TABLE `user_lesson_progress`
  ADD CONSTRAINT `fk_progress_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lessonId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
