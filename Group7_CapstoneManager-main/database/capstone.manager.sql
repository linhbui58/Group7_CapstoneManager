-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 03, 2026 lúc 11:04 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `capstone_manager`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `evaluation_scores`
--

CREATE TABLE `evaluation_scores` (
  `id` int(11) NOT NULL,
  `submission_id` int(11) DEFAULT NULL,
  `lecturer_id` int(11) DEFAULT NULL,
  `score` float DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `graded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `evaluation_scores`
--

INSERT INTO `evaluation_scores` (`id`, `submission_id`, `lecturer_id`, `score`, `feedback`, `graded_at`) VALUES
(1, 2, 3, 8, 'tốt', '2026-05-13 15:57:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lecturers`
--

CREATE TABLE `lecturers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `expertise` text DEFAULT NULL,
  `quota` int(11) DEFAULT 8,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lecturers`
--

INSERT INTO `lecturers` (`id`, `user_id`, `full_name`, `expertise`, `quota`, `created_at`) VALUES
(1, NULL, NULL, NULL, 8, '2026-05-11 13:03:58'),
(2, 4, 'Bui Van A', 'Data science', 8, '2026-05-11 13:27:21'),
(3, 6, 'Tran Van Đạt', 'Database', 8, '2026-05-13 15:27:14');

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `lecturer_workload`
-- (See below for the actual view)
--
CREATE TABLE `lecturer_workload` (
`id` int(11)
,`full_name` varchar(150)
,`total_students` bigint(21)
);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `milestones`
--

CREATE TABLE `milestones` (
  `id` int(11) NOT NULL,
  `title` enum('proposal','midterm','final') DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `milestones`
--

INSERT INTO `milestones` (`id`, `title`, `deadline`, `semester_id`, `created_at`) VALUES
(2, 'final', '2026-07-22 06:00:00', 1, '2026-05-13 14:57:09'),
(3, 'final', '2026-05-15 19:00:00', 2, '2026-05-13 15:37:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `type` enum('approval','deadline','score','system') DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `content`, `type`, `is_read`, `created_at`) VALUES
(1, 2, 'Đỗ hà đã nộp bài cho cột mốc: final', '', 0, '2026-05-13 15:56:59'),
(2, 6, 'Đỗ hà đã nộp bài cho cột mốc: final', '', 1, '2026-05-13 15:56:59'),
(3, 5, 'Bài nộp cột mốc \"final\" của bạn đã được chấm điểm.', 'score', 1, '2026-05-13 15:57:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `semesters`
--

CREATE TABLE `semesters` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `semesters`
--

INSERT INTO `semesters` (`id`, `name`, `start_date`, `end_date`, `created_at`) VALUES
(1, 'summer 2026', '2026-07-01', '2026-08-29', '2026-05-11 13:49:59'),
(2, 'kì 2-2026', '2026-02-13', '2026-05-27', '2026-05-13 15:36:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `student_code` varchar(30) DEFAULT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `faculty` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_code`, `full_name`, `phone`, `faculty`, `created_at`) VALUES
(1, 3, '23070525', 'bui lin', '0933582005', NULL, '2026-05-10 16:40:31'),
(2, 5, '23070535', 'Đỗ hà', '0399959822', NULL, '2026-05-11 15:48:04'),
(4, 8, '23070635', 'Tran Duy', '0979836542', NULL, '2026-05-14 09:25:42'),
(5, 9, '23070606', 'Linh Nguyễn', '0336923736', NULL, '2026-05-19 01:58:41');

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `student_progress`
-- (See below for the actual view)
--
CREATE TABLE `student_progress` (
`id` int(11)
,`full_name` varchar(150)
,`total_submissions` bigint(21)
,`average_score` double
);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `milestone_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('submitted','late','revision_required') DEFAULT 'submitted',
  `attempt` int(11) DEFAULT 1,
  `submitted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `submissions`
--

INSERT INTO `submissions` (`id`, `student_id`, `topic_id`, `milestone_id`, `file_path`, `status`, `attempt`, `submitted_at`) VALUES
(1, 1, NULL, 2, '1778684705_Linh_Bùi Thị Ngọc Linh_Topic1.pdf', 'submitted', 1, '2026-05-13 22:05:05'),
(2, 2, 10, 3, '1778687819_tong-hop-kien-thuc-co-ban-toan-9.pdf', 'submitted', 1, '2026-05-13 22:56:59');

--
-- Bẫy `submissions`
--
DELIMITER $$
CREATE TRIGGER `check_submission_deadline` BEFORE INSERT ON `submissions` FOR EACH ROW BEGIN

    DECLARE dline DATETIME;

    SELECT deadline
    INTO dline
    FROM milestones
    WHERE id = NEW.milestone_id;

    IF NOW() > dline THEN

        SET NEW.status = 'late';

    ELSE

        SET NEW.status = 'submitted';

    END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `increase_submission_attempt` BEFORE INSERT ON `submissions` FOR EACH ROW BEGIN

    DECLARE last_attempt INT;

    SELECT IFNULL(MAX(attempt),0)
    INTO last_attempt
    FROM submissions
    WHERE student_id = NEW.student_id
    AND milestone_id = NEW.milestone_id;

    SET NEW.attempt = last_attempt + 1;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_id`, `action`, `created_at`) VALUES
(1, 3, 'User logged out', '2026-05-10 16:57:50'),
(2, 2, 'User logged in', '2026-05-10 17:01:00'),
(3, 2, 'User logged out', '2026-05-10 17:12:36'),
(4, 3, 'User logged in', '2026-05-11 00:20:57'),
(5, 3, 'User logged out', '2026-05-11 00:21:43'),
(6, 2, 'User logged in', '2026-05-11 00:22:18'),
(7, 2, 'User logged out', '2026-05-11 00:23:19'),
(8, 3, 'User logged in', '2026-05-11 00:25:18'),
(9, 3, 'User logged out', '2026-05-11 00:54:53'),
(10, 2, 'User logged in', '2026-05-11 05:11:28'),
(11, 2, 'User logged out', '2026-05-11 05:17:14'),
(12, 2, 'User logged in', '2026-05-11 05:38:07'),
(13, 2, 'User logged out', '2026-05-11 06:26:23'),
(14, 3, 'User logged in', '2026-05-11 06:31:08'),
(15, 3, 'User logged out', '2026-05-11 06:31:50'),
(16, 2, 'User logged in', '2026-05-11 06:32:14'),
(17, 2, 'User logged out', '2026-05-11 08:21:48'),
(18, 3, 'User logged in', '2026-05-11 08:32:24'),
(19, 3, 'User logged out', '2026-05-11 09:09:58'),
(20, 3, 'User logged in', '2026-05-11 12:24:46'),
(21, 3, 'User logged out', '2026-05-11 12:44:37'),
(22, 2, 'User logged in', '2026-05-11 12:44:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` enum('draft','pending','approved','rejected') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `topics`
--

INSERT INTO `topics` (`id`, `title`, `description`, `keywords`, `semester_id`, `created_by`, `status`, `created_at`) VALUES
(9, 'fjdf', '53r6t7ynfd', 'ỷiwkbf', 1, 3, 'approved', '2026-05-12 08:49:26'),
(10, 'AI', 'et7 sc', 'fecyvdbk', 2, 2, 'approved', '2026-05-13 15:54:15'),
(11, 'ABC', 'web2', 'web 2', 2, 8, 'pending', '2026-05-14 09:26:43'),
(12, 'Thương ơi', 'nghiên cứu tình cảm THương và Kiên', 'AI', 2, 9, 'pending', '2026-05-21 09:48:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `topic_assignments`
--

CREATE TABLE `topic_assignments` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `lecturer_id` int(11) DEFAULT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `topic_assignments`
--

INSERT INTO `topic_assignments` (`id`, `topic_id`, `lecturer_id`, `assigned_at`) VALUES
(4, 9, 2, '2026-05-13 15:13:24'),
(6, 10, 3, '2026-05-13 15:54:47');

--
-- Bẫy `topic_assignments`
--
DELIMITER $$
CREATE TRIGGER `check_lecturer_quota` BEFORE INSERT ON `topic_assignments` FOR EACH ROW BEGIN

    DECLARE total_students INT;

    SELECT COUNT(*)
    INTO total_students
    FROM topic_assignments ta
    JOIN topic_registrations tr
    ON ta.topic_id = tr.topic_id
    WHERE ta.lecturer_id = NEW.lecturer_id
    AND tr.status = 'approved';

    IF total_students >= 8 THEN

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT =
        'Lecturer quota exceeded (max 8 students)';

    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `topic_registrations`
--

CREATE TABLE `topic_registrations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `desired_lecturer_id` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `topic_registrations`
--

INSERT INTO `topic_registrations` (`id`, `student_id`, `topic_id`, `semester_id`, `description`, `keywords`, `desired_lecturer_id`, `status`, `created_at`) VALUES
(1, 1, 11, 1, NULL, 'machine learning, AI', NULL, 'pending', '2026-05-14 09:28:51'),
(2, 2, 10, 1, NULL, 'deep learning', NULL, 'approved', '2026-05-14 09:28:51'),
(3, 4, 9, 2, NULL, 'web development', NULL, 'approved', '2026-05-14 09:28:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student','lecturer') NOT NULL DEFAULT 'student',
  `status` enum('active','locked') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(2, 'admin@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', '2026-05-10 16:29:00'),
(3, '23070525@vnu.edu.vn', '$2y$10$coadLKAOGDy45KxPO/GODeGq9xpf6hr6oQPZZKoGjuS3XPRMhdCLO', 'student', 'active', '2026-05-10 16:40:31'),
(4, 'A@vnu.edu.vn', '$2y$10$.r4iF.81hfGrSP.VmbqE2u5f4yHqpF0wRRhLDMA4WjpK8QMzaGByK', 'lecturer', 'active', '2026-05-11 13:27:21'),
(5, 'doha@vnu.edu.vn', '$2y$10$hTP36dVqufJKN0a9zXXUSOvZMnbYwv67ovsRttk0SZ6skD7voW.vW', 'student', 'active', '2026-05-11 15:48:04'),
(6, 'dat@vnu.edu.vn', '$2y$10$mWHI55kgf83PUODywK8qz.XNoWfI59wQCcK68FB4/m.NaQzaKWJdm', 'lecturer', 'active', '2026-05-13 15:27:14'),
(8, 'duy@vnu.edu.vn', '$2y$10$T1dLcUkDgg24gd82AgZzyuxtnu1SbTBMapkTh5iJ5kr0E3R6dMc/.', 'student', 'active', '2026-05-14 09:25:42'),
(9, 'nguyenlinh230809@gmail.com', '$2y$10$tf6z0BSK7nYVTW8dwToYse3eBrNTCHvf7HpPBAVCeC0MrWpMotIlC', 'student', 'active', '2026-05-19 01:58:41');

--
-- Bẫy `users`
--
DELIMITER $$
CREATE TRIGGER `prevent_admin_delete` BEFORE DELETE ON `users` FOR EACH ROW BEGIN

    IF OLD.role = 'admin' THEN

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT =
        'Cannot delete admin account';

    END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_admin_lock` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN

    IF OLD.role = 'admin'
    AND NEW.status = 'locked' THEN

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT =
        'Cannot lock admin account';

    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc cho view `lecturer_workload`
--
DROP TABLE IF EXISTS `lecturer_workload`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lecturer_workload`  AS SELECT `l`.`id` AS `id`, `l`.`full_name` AS `full_name`, count(`tr`.`id`) AS `total_students` FROM ((`lecturers` `l` left join `topic_assignments` `ta` on(`l`.`id` = `ta`.`lecturer_id`)) left join `topic_registrations` `tr` on(`ta`.`topic_id` = `tr`.`topic_id` and `tr`.`status` = 'approved')) GROUP BY `l`.`id` ;

-- --------------------------------------------------------

--
-- Cấu trúc cho view `student_progress`
--
DROP TABLE IF EXISTS `student_progress`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_progress`  AS SELECT `s`.`id` AS `id`, `s`.`full_name` AS `full_name`, count(`sub`.`id`) AS `total_submissions`, avg(`es`.`score`) AS `average_score` FROM ((`students` `s` left join `submissions` `sub` on(`s`.`id` = `sub`.`student_id`)) left join `evaluation_scores` `es` on(`sub`.`id` = `es`.`submission_id`)) GROUP BY `s`.`id` ;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `evaluation_scores`
--
ALTER TABLE `evaluation_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `submission_id` (`submission_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Chỉ mục cho bảng `lecturers`
--
ALTER TABLE `lecturers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `milestones`
--
ALTER TABLE `milestones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifications_user` (`user_id`);

--
-- Chỉ mục cho bảng `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `student_code` (`student_code`);

--
-- Chỉ mục cho bảng `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `milestone_id` (`milestone_id`),
  ADD KEY `idx_submission_student` (`student_id`),
  ADD KEY `idx_submissions_student` (`student_id`);

--
-- Chỉ mục cho bảng `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`,`semester_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_topic_semester` (`semester_id`),
  ADD KEY `idx_topics_title` (`title`);

--
-- Chỉ mục cho bảng `topic_assignments`
--
ALTER TABLE `topic_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `topic_id` (`topic_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Chỉ mục cho bảng `topic_registrations`
--
ALTER TABLE `topic_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_semester` (`student_id`,`semester_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `evaluation_scores`
--
ALTER TABLE `evaluation_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `milestones`
--
ALTER TABLE `milestones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `topic_assignments`
--
ALTER TABLE `topic_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `topic_registrations`
--
ALTER TABLE `topic_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `evaluation_scores`
--
ALTER TABLE `evaluation_scores`
  ADD CONSTRAINT `evaluation_scores_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluation_scores_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lecturers`
--
ALTER TABLE `lecturers`
  ADD CONSTRAINT `lecturers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `milestones`
--
ALTER TABLE `milestones`
  ADD CONSTRAINT `milestones_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submissions_ibfk_3` FOREIGN KEY (`milestone_id`) REFERENCES `milestones` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topics_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `topic_assignments`
--
ALTER TABLE `topic_assignments`
  ADD CONSTRAINT `topic_assignments_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topic_assignments_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;