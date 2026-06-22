-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 22, 2026 lúc 04:27 PM
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
(2, 1, 2, 9.2, 'Excellent work on the proposal!', '2026-06-15 20:12:41'),
(5, 6, 4, 9.2, 'good', '2026-06-18 03:28:59'),
(6, 7, 6, 6.5, 'ổn', '2026-06-18 07:58:39'),
(7, 8, 6, 8, '', '2026-06-19 07:26:33');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `faculty` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lecturers`
--

INSERT INTO `lecturers` (`id`, `user_id`, `full_name`, `expertise`, `quota`, `created_at`, `faculty`) VALUES
(1, NULL, NULL, NULL, 8, '2026-05-11 13:03:58', 'Khoa Kinh tế'),
(2, 4, 'Bui Van A', 'Data science', 8, '2026-05-11 13:27:21', 'Khoa CNTT'),
(3, 6, 'Tran Van Đạt', 'Database', 8, '2026-05-13 15:27:14', 'Khoa Kinh tế'),
(4, 10, 'Le Van C', 'Cybersecurity', 8, '2026-06-15 20:12:41', 'Khoa CNTT'),
(6, 14, 'Truong Cong Doan', 'AI', 8, '2026-06-18 06:47:16', 'Khoa Ngôn ngữ'),
(7, 17, 'Hao Huc', '', 8, '2026-06-18 19:22:50', 'Khoa Kinh tế'),
(8, 18, 'Lê ÂBC', '', 8, '2026-06-18 19:27:18', 'Khoa CNTT'),
(9, 23, 'Võ văn Toàn', '', 8, '2026-06-21 14:18:56', 'Khoa Kinh tế');

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
(5, 'proposal', '2026-07-10 01:49:00', 1, '2026-06-18 18:49:21'),
(6, 'midterm', '2026-07-03 01:57:00', 1, '2026-06-18 18:57:43');

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
(2, 6, 'Đỗ hà đã nộp bài cho cột mốc: final', '', 1, '2026-05-13 15:56:59'),
(3, 5, 'Bài nộp cột mốc \"final\" của bạn đã được chấm điểm.', 'score', 1, '2026-05-13 15:57:57'),
(4, 8, 'BÃ i ná»™p cá»™t má»‘c \"final\" cá»§a báº¡n cáº§n chá»‰nh sá»­a láº¡i âœï¸.', '', 0, '2026-06-18 02:10:41'),
(5, 9, 'BÃ i ná»™p cá»™t má»‘c \"final\" cá»§a báº¡n cáº§n chá»‰nh sá»­a láº¡i âœï¸.', '', 0, '2026-06-18 02:10:49'),
(6, 5, 'BÃ i ná»™p cá»™t má»‘c \"final\" cá»§a báº¡n cáº§n chá»‰nh sá»­a láº¡i âœï¸.', '', 0, '2026-06-18 02:32:40'),
(7, 5, 'BÃ i ná»™p cá»™t má»‘c \"final\" cá»§a báº¡n cáº§n chá»‰nh sá»­a láº¡i âœï¸.', '', 0, '2026-06-18 02:32:49'),
(9, 5, 'BÃ i ná»™p cá»™t má»‘c \"final\" cá»§a báº¡n Ä‘Ã£ Ä‘Æ°á»£c xÃ¡c nháº­n âœ“.', '', 0, '2026-06-18 02:40:52'),
(11, 8, 'BÃ i ná»™p cá»™t má»‘c \"final\" cá»§a báº¡n Ä‘Ã£ Ä‘Æ°á»£c xÃ¡c nháº­n âœ“.', '', 0, '2026-06-18 02:40:57'),
(15, 2, 'Sinh viên 23070606 vừa đề xuất đề tài mới: aloooooooo', 'system', 1, '2026-06-18 04:01:25'),
(16, 2, '23070606 vừa đăng ký đề tài: Nghiên cứu hành vi  người dùng', '', 1, '2026-06-18 04:12:00'),
(18, 12, 'Yêu cầu đăng ký đề tài \"Nghiên cứu hành vi  người dùng\" của bạn đã được DUYỆT.', '', 0, '2026-06-18 04:24:47'),
(19, 11, 'Yêu cầu đăng ký đề tài \"E-Commerce Recommendation\" của bạn đã được DUYỆT.', '', 0, '2026-06-18 04:25:30'),
(20, 2, 'Linh Nguyễn đã nộp bài cho cột mốc: final', '', 0, '2026-06-18 07:35:27'),
(21, 10, 'Linh Nguyễn đã nộp bài cho cột mốc: final', '', 0, '2026-06-18 07:35:27'),
(22, 12, 'Bài nộp cột mốc \"final\" của bạn đã được chấm điểm.', 'score', 0, '2026-06-18 07:58:39'),
(23, 2, 'Linh Nguyễn đã nộp bài cho cột mốc: midterm', '', 0, '2026-06-19 07:24:31'),
(24, 14, 'Linh Nguyễn đã nộp bài cho cột mốc: midterm', '', 1, '2026-06-19 07:24:31'),
(25, 12, 'Bài nộp cột mốc \"midterm\" của bạn đã được chấm điểm.', 'score', 0, '2026-06-19 07:26:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `score_edit_requests`
--

CREATE TABLE `score_edit_requests` (
  `id` int(11) NOT NULL,
  `score_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','resolved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'summer 2026', '2026-07-01', '2026-09-01', '2026-05-11 13:49:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `faculty` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `student_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `students`
--

INSERT INTO `students` (`id`, `user_id`, `full_name`, `faculty`, `created_at`, `student_code`) VALUES
(1, 3, 'bui lin', 'Khoa Ngôn ngữ', '2026-05-10 16:40:31', NULL),
(2, 5, 'Đỗ hà', 'Khoa CNTT', '2026-05-11 15:48:04', NULL),
(4, 8, 'Tran Duy', 'Khoa CNTT', '2026-05-14 09:25:42', NULL),
(5, 9, 'Linh Nguyễn', 'Khoa Kinh tế', '2026-05-19 01:58:41', NULL),
(6, 11, 'Nguyen Thi E', 'Khoa CNTT', '2026-06-15 20:12:41', NULL),
(7, 12, 'Linh Nguyễn', 'Khoa Ngôn ngữ', '2026-06-18 03:50:42', NULL),
(8, 15, 'Ho Minh Hoang', 'Khoa Ngôn ngữ', '2026-06-18 18:24:15', NULL),
(9, 19, 'Bi', 'Khoa Kinh tế', '2026-06-19 12:45:29', NULL),
(10, 20, 'ABC', 'Khoa CNTT', '2026-06-19 18:56:43', NULL),
(11, 21, 'fueiw', 'Khoa CNTT', '2026-06-19 20:09:00', NULL),
(12, 22, '434', 'Khoa Ngôn ngữ', '2026-06-19 20:25:48', NULL),
(13, 24, 'Lung Linh', 'Khoa Kinh tế', '2026-06-21 16:33:45', '23232323');

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
  `status` enum('submitted','reviewed','late','revision_required') DEFAULT 'submitted',
  `attempt` int(11) DEFAULT 1,
  `submitted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `submissions`
--

INSERT INTO `submissions` (`id`, `student_id`, `topic_id`, `milestone_id`, `file_path`, `status`, `attempt`, `submitted_at`) VALUES
(1, 1, NULL, 2, '1778684705_Linh_Bùi Thị Ngọc Linh_Topic1.pdf', 'submitted', 1, '2026-05-13 22:05:05'),
(6, 6, 16, 2, '1781753280_6a3365c048b08.docx', 'submitted', 1, '2026-06-18 10:28:00'),
(7, 7, 19, 2, '1781768127_6a339fbf01b5f.pdf', 'submitted', 1, '2026-06-18 14:35:27'),
(8, 7, 19, 6, '1781853871_6a34eeaf4127e.docx', 'submitted', 1, '2026-06-19 14:24:31');

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
-- Cấu trúc bảng cho bảng `supervision_assignments`
--

CREATE TABLE `supervision_assignments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `supervision_assignments`
--

INSERT INTO `supervision_assignments` (`id`, `student_id`, `lecturer_id`, `semester_id`, `assigned_by`, `assigned_at`) VALUES
(1, 11, 4, 1, 2, '2026-06-20 03:23:35'),
(2, 12, 6, 1, 2, '2026-06-20 03:26:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `status` enum('success','failed') DEFAULT 'success'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_id`, `action`, `description`, `created_at`, `ip_address`, `user_agent`, `status`) VALUES
(1, 3, 'User logged out', NULL, '2026-05-10 16:57:50', NULL, NULL, 'success'),
(2, 2, 'User logged in', NULL, '2026-05-10 17:01:00', NULL, NULL, 'success'),
(3, 2, 'User logged out', NULL, '2026-05-10 17:12:36', NULL, NULL, 'success'),
(4, 3, 'User logged in', NULL, '2026-05-11 00:20:57', NULL, NULL, 'success'),
(5, 3, 'User logged out', NULL, '2026-05-11 00:21:43', NULL, NULL, 'success'),
(6, 2, 'User logged in', NULL, '2026-05-11 00:22:18', NULL, NULL, 'success'),
(7, 2, 'User logged out', NULL, '2026-05-11 00:23:19', NULL, NULL, 'success'),
(8, 3, 'User logged in', NULL, '2026-05-11 00:25:18', NULL, NULL, 'success'),
(9, 3, 'User logged out', NULL, '2026-05-11 00:54:53', NULL, NULL, 'success'),
(10, 2, 'User logged in', NULL, '2026-05-11 05:11:28', NULL, NULL, 'success'),
(11, 2, 'User logged out', NULL, '2026-05-11 05:17:14', NULL, NULL, 'success'),
(12, 2, 'User logged in', NULL, '2026-05-11 05:38:07', NULL, NULL, 'success'),
(13, 2, 'User logged out', NULL, '2026-05-11 06:26:23', NULL, NULL, 'success'),
(14, 3, 'User logged in', NULL, '2026-05-11 06:31:08', NULL, NULL, 'success'),
(15, 3, 'User logged out', NULL, '2026-05-11 06:31:50', NULL, NULL, 'success'),
(16, 2, 'User logged in', NULL, '2026-05-11 06:32:14', NULL, NULL, 'success'),
(17, 2, 'User logged out', NULL, '2026-05-11 08:21:48', NULL, NULL, 'success'),
(18, 3, 'User logged in', NULL, '2026-05-11 08:32:24', NULL, NULL, 'success'),
(19, 3, 'User logged out', NULL, '2026-05-11 09:09:58', NULL, NULL, 'success'),
(20, 3, 'User logged in', NULL, '2026-05-11 12:24:46', NULL, NULL, 'success'),
(21, 3, 'User logged out', NULL, '2026-05-11 12:44:37', NULL, NULL, 'success'),
(22, 2, 'User logged in', NULL, '2026-05-11 12:44:58', NULL, NULL, 'success'),
(23, 2, 'logout', 'User logged out', '2026-06-16 07:39:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(24, 11, 'login', 'User logged in', '2026-06-16 07:39:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(25, 11, 'logout', 'User logged out', '2026-06-16 07:41:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(26, 10, 'login', 'User logged in', '2026-06-16 07:41:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(27, 10, 'logout', 'User logged out', '2026-06-16 10:03:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(28, 2, 'login', 'User logged in', '2026-06-16 10:07:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(29, 2, 'logout', 'User logged out', '2026-06-16 10:08:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(30, 2, 'login', 'User logged in', '2026-06-16 11:46:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(31, 2, 'logout', 'User logged out', '2026-06-16 13:43:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(32, 2, 'login', 'User logged in', '2026-06-18 01:51:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(33, 2, 'update_topic_status', 'Updated status of topic ID: 17 to rejected', '2026-06-18 01:59:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(34, 2, 'update_topic_status', 'Updated status of topic ID: 12 to approved', '2026-06-18 01:59:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(35, 2, 'update_assignment', 'Updated assignment ID: 6', '2026-06-18 02:06:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(36, 2, 'update_assignment', 'Updated assignment ID: 4', '2026-06-18 02:07:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(37, 2, 'delete_submission', 'Deleted submission ID: 5', '2026-06-18 02:10:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(38, 2, 'update_submission_status', 'Updated submission ID: 3 status to revision_required', '2026-06-18 02:10:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(39, 2, 'update_submission_status', 'Updated submission ID: 4 status to revision_required', '2026-06-18 02:10:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(40, 2, 'update_score', 'Updated Score ID: 2', '2026-06-18 02:12:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(41, 2, 'logout', 'User logged out', '2026-06-18 02:25:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(42, 10, 'login', 'User logged in', '2026-06-18 02:29:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(43, 10, 'update_submission_status', 'Updated submission ID: 2 status to revision_required', '2026-06-18 02:32:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(44, 10, 'update_submission_status', 'Updated submission ID: 2 status to revision_required', '2026-06-18 02:32:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(45, 10, 'logout', 'User logged out', '2026-06-18 02:33:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(46, 10, 'login', 'User logged in', '2026-06-18 02:34:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(47, 10, 'logout', 'User logged out', '2026-06-18 02:34:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(48, 11, 'login', 'User logged in', '2026-06-18 02:34:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(49, 11, 'create_submission', 'Student ID: 6 submitted for Milestone ID: 2', '2026-06-18 02:38:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(50, 11, 'logout', 'User logged out', '2026-06-18 02:38:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(51, 10, 'login', 'User logged in', '2026-06-18 02:39:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(52, 10, 'logout', 'User logged out', '2026-06-18 02:39:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(53, 2, 'login', 'User logged in', '2026-06-18 02:39:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(54, 2, 'update_submission_status', 'Updated submission ID: 2 status to submitted', '2026-06-18 02:40:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(55, 2, 'update_submission_status', 'Updated submission ID: 4 status to submitted', '2026-06-18 02:40:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(56, 2, 'update_submission_status', 'Updated submission ID: 3 status to submitted', '2026-06-18 02:40:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(57, 2, 'delete_topic', 'Deleted topic ID: 12', '2026-06-18 02:41:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(58, 2, 'delete_topic', 'Deleted topic ID: 11', '2026-06-18 02:41:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(59, 2, 'logout', 'User logged out', '2026-06-18 02:42:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(60, 11, 'login', 'User logged in', '2026-06-18 02:42:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(61, 11, 'logout', 'User logged out', '2026-06-18 02:48:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(62, 2, 'login', 'User logged in', '2026-06-18 02:48:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(63, 2, 'update_topic', 'Updated topic ID: 16', '2026-06-18 02:48:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(64, 2, 'assign_topic', 'Assigned Topic to Lecturer ID: 4', '2026-06-18 02:49:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(65, 2, 'logout', 'User logged out', '2026-06-18 02:49:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(66, 10, 'login', 'User logged in', '2026-06-18 02:49:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(67, 10, 'create_score', 'Scored Submission ID: 6', '2026-06-18 02:54:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(68, 10, 'update_score', 'Updated Score ID: 4', '2026-06-18 03:12:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(69, 10, 'update_submission_status', 'Updated submission ID: 6 status to revision_required', '2026-06-18 03:13:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(70, 10, 'logout', 'User logged out', '2026-06-18 03:13:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(71, 11, 'login', 'User logged in', '2026-06-18 03:14:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(72, 11, 'logout', 'User logged out', '2026-06-18 03:25:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(73, 10, 'login', 'User logged in', '2026-06-18 03:26:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(74, 10, 'delete_score', 'Deleted Score ID: 4', '2026-06-18 03:26:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(75, 10, 'logout', 'User logged out', '2026-06-18 03:26:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(76, 11, 'login', 'User logged in', '2026-06-18 03:26:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(77, 11, 'update_submission', 'Student ID: 6 updated submission ID: 6', '2026-06-18 03:28:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(78, 11, 'logout', 'User logged out', '2026-06-18 03:28:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(79, 10, 'login', 'User logged in', '2026-06-18 03:28:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(80, 10, 'create_score', 'Scored Submission ID: 6', '2026-06-18 03:28:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(81, 10, 'logout', 'User logged out', '2026-06-18 03:29:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(82, 10, 'login', 'User logged in', '2026-06-18 03:29:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(83, 10, 'logout', 'User logged out', '2026-06-18 03:36:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(84, 11, 'login', 'User logged in', '2026-06-18 03:36:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(85, 11, 'logout', 'User logged out', '2026-06-18 03:50:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(86, 12, 'login', 'User logged in', '2026-06-18 03:51:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(87, 12, 'create_topic', 'Created topic: Nghiên cứu hành vi  người dùng', '2026-06-18 03:52:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(88, 12, 'logout', 'User logged out', '2026-06-18 03:53:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(89, 10, 'login', 'User logged in', '2026-06-18 03:53:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(90, 10, 'logout', 'User logged out', '2026-06-18 03:53:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(91, 12, 'login', 'User logged in', '2026-06-18 03:53:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(92, 12, 'logout', 'User logged out', '2026-06-18 03:53:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(93, 2, 'login', 'User logged in', '2026-06-18 03:53:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(94, 2, 'update_topic_status', 'Updated status of topic ID: 19 to approved', '2026-06-18 03:54:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(95, 2, 'logout', 'User logged out', '2026-06-18 03:55:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(96, 12, 'login', 'User logged in', '2026-06-18 03:55:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(97, 12, 'create_topic', 'Created topic: aloooooooo', '2026-06-18 04:01:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(98, 12, 'register_topic', 'Student ID: 7 registered Topic ID: 19', '2026-06-18 04:12:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(99, 12, 'logout', 'User logged out', '2026-06-18 04:19:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(100, 10, 'login', 'User logged in', '2026-06-18 04:20:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(101, 10, 'logout', 'User logged out', '2026-06-18 04:23:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(102, 2, 'login', 'User logged in', '2026-06-18 04:23:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(103, 2, 'update_topic_status', 'Updated status of topic ID: 20 to rejected', '2026-06-18 04:24:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(104, 2, 'update_registration_status', 'Updated registration ID: 6 status to approved', '2026-06-18 04:24:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(105, 2, 'update_assignment', 'Updated assignment ID: 10', '2026-06-18 04:25:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(106, 2, 'update_registration_status', 'Updated registration ID: 5 status to approved', '2026-06-18 04:25:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(107, 2, 'assign_topic', 'Assigned Topic to Lecturer ID: 4', '2026-06-18 04:25:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(108, 2, 'logout', 'User logged out', '2026-06-18 04:26:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(109, 12, 'login', 'User logged in', '2026-06-18 04:26:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(110, 12, 'logout', 'User logged out', '2026-06-18 04:26:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(111, 10, 'login', 'User logged in', '2026-06-18 04:26:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(112, 10, 'logout', 'User logged out', '2026-06-18 04:27:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(113, 2, 'login', 'User logged in', '2026-06-18 04:41:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(114, 2, 'delete_submission', 'Deleted submission ID: 4', '2026-06-18 06:39:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(115, 2, 'delete_submission', 'Deleted submission ID: 3', '2026-06-18 06:39:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(116, 2, 'logout', 'User logged out', '2026-06-18 06:48:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(117, 14, 'login', 'User logged in', '2026-06-18 06:49:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(118, 14, 'logout', 'User logged out', '2026-06-18 06:50:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(119, 12, 'login', 'User logged in', '2026-06-18 06:50:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(120, 12, 'create_submission', 'Student ID: 7 submitted for Milestone ID: 2', '2026-06-18 07:35:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(121, 12, 'logout', 'User logged out', '2026-06-18 07:35:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(122, 14, 'login', 'User logged in', '2026-06-18 07:36:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(123, 14, 'logout', 'User logged out', '2026-06-18 07:36:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(124, 2, 'login', 'User logged in', '2026-06-18 07:36:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(125, 2, 'update_assignment', 'Updated assignment ID: 11', '2026-06-18 07:39:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(126, 2, 'delete_assignment', 'Deleted assignment ID: 4', '2026-06-18 07:40:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(127, 2, 'update_topic_status', 'Updated status of topic ID: 17 to approved', '2026-06-18 07:56:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(128, 2, 'delete_topic', 'Deleted topic ID: 9', '2026-06-18 07:56:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(129, 2, 'logout', 'User logged out', '2026-06-18 07:56:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(130, 10, 'login', 'User logged in', '2026-06-18 07:57:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(131, 10, 'logout', 'User logged out', '2026-06-18 07:57:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(132, 14, 'login', 'User logged in', '2026-06-18 07:57:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(133, 14, 'create_score', 'Scored Submission ID: 7', '2026-06-18 07:58:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(134, 14, 'logout', 'User logged out', '2026-06-18 07:59:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(135, 15, 'login', 'User logged in', '2026-06-18 18:25:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(136, 15, 'logout', 'User logged out', '2026-06-18 18:37:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(137, 2, 'login', 'User logged in', '2026-06-18 18:38:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(138, 2, 'logout', 'User logged out', '2026-06-18 18:38:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(139, 15, 'login', 'User logged in', '2026-06-18 18:39:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(140, 15, 'logout', 'User logged out', '2026-06-18 18:53:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(141, 2, 'login', 'User logged in', '2026-06-18 18:53:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(142, 2, 'logout', 'User logged out', '2026-06-18 19:06:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(143, 17, 'login', 'User logged in', '2026-06-18 19:22:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(144, 17, 'logout', 'User logged out', '2026-06-18 19:26:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(145, 18, 'login', 'User logged in', '2026-06-18 19:27:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(146, 18, 'logout', 'User logged out', '2026-06-18 19:35:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(147, 12, 'login', 'User logged in', '2026-06-18 19:36:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(148, 12, 'create_topic', 'Created topic: ffhfvkc', '2026-06-18 20:17:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(149, 12, 'create_topic', 'Created topic: vdslvnlskdvs', '2026-06-18 20:19:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(150, 12, 'logout', 'User logged out', '2026-06-18 20:25:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(151, 2, 'login', 'User logged in', '2026-06-18 20:27:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(152, 2, 'delete_topic', 'Deleted topic ID: 20', '2026-06-18 20:27:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(153, 2, 'update_topic_status', 'Updated status of topic ID: 21 to rejected', '2026-06-18 20:36:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(154, 2, 'update_topic_status', 'Updated status of topic ID: 21 to approved', '2026-06-18 20:39:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(155, 2, 'update_topic_status', 'Updated status of topic ID: 21 to rejected', '2026-06-18 20:39:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(156, 2, 'logout', 'User logged out', '2026-06-19 06:30:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(157, 12, 'login', 'User logged in', '2026-06-19 06:30:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(158, 12, 'create_topic', 'Created topic: ànhakfncikdbcsd', '2026-06-19 06:31:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(159, 12, 'logout', 'User logged out', '2026-06-19 06:45:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(160, 2, 'login', 'User logged in', '2026-06-19 06:45:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(161, 2, 'update_topic_status', 'Updated status of topic ID: 22 to approved', '2026-06-19 06:46:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(162, 2, 'update_topic_status', 'Updated status of topic ID: 23 to approved', '2026-06-19 06:46:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(163, 2, 'logout', 'User logged out', '2026-06-19 06:46:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(164, 12, 'login', 'User logged in', '2026-06-19 06:46:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(165, 12, 'create_submission', 'Student ID: 7 submitted for Milestone ID: 6', '2026-06-19 07:24:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(166, 12, 'logout', 'User logged out', '2026-06-19 07:25:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(167, 14, 'login', 'User logged in', '2026-06-19 07:25:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(168, 14, 'create_score', 'Scored Submission ID: 8', '2026-06-19 07:26:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(169, 14, 'logout', 'User logged out', '2026-06-19 10:46:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(170, 12, 'login', 'User logged in', '2026-06-19 10:46:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(171, 12, 'logout', 'User logged out', '2026-06-19 10:55:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(172, 14, 'login', 'User logged in', '2026-06-19 11:08:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(173, 14, 'logout', 'User logged out', '2026-06-19 11:08:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(174, 2, 'login', 'User logged in', '2026-06-19 11:08:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(175, 2, 'logout', 'User logged out', '2026-06-19 11:08:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(176, 19, 'login', 'User logged in', '2026-06-19 12:46:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(177, 19, 'create_topic', 'Created topic: ikgviwfe', '2026-06-19 12:48:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(178, 19, 'logout', 'User logged out', '2026-06-19 13:15:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(179, 12, 'login', 'User logged in', '2026-06-19 14:59:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(180, 12, 'logout', 'User logged out', '2026-06-19 15:00:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(181, 14, 'login', 'User logged in', '2026-06-19 18:55:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(182, 14, 'logout', 'User logged out', '2026-06-19 18:56:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(183, 20, 'login', 'User logged in', '2026-06-19 18:56:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(184, 20, 'create_topic', 'Created topic: abc', '2026-06-19 18:57:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(185, 20, 'create_topic', 'Created topic: akvd', '2026-06-19 18:57:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(186, 20, 'logout', 'User logged out', '2026-06-19 18:57:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(187, 14, 'login', 'User logged in', '2026-06-19 18:57:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(188, 14, 'logout', 'User logged out', '2026-06-19 20:08:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(189, 21, 'login', 'User logged in', '2026-06-19 20:09:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(190, 21, 'create_topic', 'Created topic: mss', '2026-06-19 20:09:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(191, 21, 'logout', 'User logged out', '2026-06-19 20:10:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(192, 2, 'login', 'User logged in', '2026-06-19 20:11:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(193, 2, 'logout', 'User logged out', '2026-06-19 20:11:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(194, 21, 'login', 'User logged in', '2026-06-19 20:12:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(195, 21, 'logout', 'User logged out', '2026-06-19 20:19:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(196, 2, 'login', 'User logged in', '2026-06-19 20:19:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(197, 2, 'create_supervision', 'Assigned student 11 to lecturer 4 for semester 1', '2026-06-19 20:23:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(198, 2, 'logout', 'User logged out', '2026-06-19 20:23:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(199, 21, 'login', 'User logged in', '2026-06-19 20:23:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(200, 21, 'create_topic', 'Created topic: hvvv', '2026-06-19 20:24:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(201, 21, 'logout', 'User logged out', '2026-06-19 20:24:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(202, 22, 'login', 'User logged in', '2026-06-19 20:26:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(203, 22, 'logout', 'User logged out', '2026-06-19 20:26:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(204, 2, 'login', 'User logged in', '2026-06-19 20:26:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(205, 2, 'create_supervision', 'Assigned student 12 to lecturer 6 for semester 1', '2026-06-19 20:26:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(206, 2, 'logout', 'User logged out', '2026-06-19 20:27:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(207, 14, 'login', 'User logged in', '2026-06-19 20:27:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(208, 14, 'logout', 'User logged out', '2026-06-19 20:27:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(209, 22, 'login', 'User logged in', '2026-06-19 20:28:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(210, 22, 'create_topic', 'Created topic: dfgdgdf', '2026-06-19 20:28:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(211, 22, 'create_topic', 'Created topic: e', '2026-06-19 20:28:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(212, 22, 'logout', 'User logged out', '2026-06-19 20:28:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(213, 14, 'login', 'User logged in', '2026-06-19 20:28:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(214, 14, 'update_topic_status', 'Updated status of topic ID: 30 to approved', '2026-06-19 20:33:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(215, 14, 'logout', 'User logged out', '2026-06-19 20:33:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(216, 22, 'login', 'User logged in', '2026-06-19 20:34:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(217, 22, 'update_topic', 'Updated topic ID: 29', '2026-06-19 20:46:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(218, 22, 'delete_topic', 'Deleted topic ID: 29', '2026-06-19 20:46:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(219, 22, 'logout', 'User logged out', '2026-06-19 20:47:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(220, 14, 'login', 'User logged in', '2026-06-19 20:47:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(221, 14, 'logout', 'User logged out', '2026-06-19 20:51:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(222, 22, 'login', 'User logged in', '2026-06-19 20:51:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(223, 22, 'propose_topic', 'Student ID: 12 proposed Topic ID: 30', '2026-06-19 20:51:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(224, 22, 'logout', 'User logged out', '2026-06-19 21:59:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(225, 14, 'login', 'User logged in', '2026-06-19 21:59:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(226, 12, 'login', 'User logged in', '2026-06-20 10:59:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(227, 12, 'logout', 'User logged out', '2026-06-20 11:02:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(228, 2, 'login', 'User logged in', '2026-06-20 14:27:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(229, 23, 'login', 'User logged in', '2026-06-21 14:20:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(230, 23, 'logout', 'User logged out', '2026-06-21 14:27:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(231, 14, 'login', 'User logged in', '2026-06-21 16:01:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(232, 14, 'logout', 'User logged out', '2026-06-21 16:01:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(233, 23, 'login', 'User logged in', '2026-06-21 16:29:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(234, 23, 'logout', 'User logged out', '2026-06-21 16:32:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(235, 24, 'login', 'User logged in', '2026-06-21 16:33:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(236, 24, 'logout', 'User logged out', '2026-06-21 17:16:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(237, 2, 'login', 'User logged in', '2026-06-21 17:17:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(238, 2, 'logout', 'User logged out', '2026-06-21 17:18:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(239, 24, 'login', 'User logged in', '2026-06-21 17:19:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(240, 24, 'logout', 'User logged out', '2026-06-21 18:30:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(241, 2, 'login', 'User logged in', '2026-06-21 19:58:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(242, 2, 'logout', 'User logged out', '2026-06-21 20:17:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success'),
(243, 14, 'login', 'User logged in', '2026-06-21 20:17:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success');

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
(13, 'Blockchain Voting System', 'A secure voting system using smart contracts', 'blockchain, solidity', 1, 4, 'approved', '2026-06-15 20:12:41'),
(15, 'IoT Smart Home', 'Smart home automation using Raspberry Pi', 'iot, raspberry pi', 1, 10, 'approved', '2026-06-15 20:12:41'),
(16, 'Library Management System', 'A system to manage library books and loans', 'php, mysql', 1, 6, 'approved', '2026-06-15 20:12:41'),
(17, 'Cybersecurity Audit Tool', 'Automated vulnerability scanning tool', 'security, python', 1, 10, 'approved', '2026-06-15 20:12:41'),
(19, 'Nghiên cứu hành vi  người dùng', 'aaaaaaaaaaaaaaaaa', 'AI', 1, 12, 'approved', '2026-06-18 03:52:13'),
(21, 'ffhfvkc', 'khcgcg', 'KT', 1, 12, 'rejected', '2026-06-18 20:17:35'),
(22, 'vdslvnlskdvs', 'dvsv', 'd', 1, 12, 'approved', '2026-06-18 20:19:44'),
(23, 'ànhakfncikdbcsd', 'nvklsdnvkn', 'K', 1, 12, 'approved', '2026-06-19 06:31:01'),
(24, 'ikgviwfe', 'daiabfa', '', 1, 19, 'pending', '2026-06-19 12:48:13'),
(25, 'abc', 'abc', '', 1, 20, 'pending', '2026-06-19 18:57:17'),
(26, 'akvd', 'dcsc', '', 1, 20, 'pending', '2026-06-19 18:57:35'),
(27, 'mss', 'scsc', '', 1, 21, 'pending', '2026-06-19 20:09:49'),
(28, 'hvvv', 'vvvvvvvvvv', '', 1, 21, 'pending', '2026-06-19 20:24:08'),
(30, 'e', '6', '', 1, 22, 'approved', '2026-06-19 20:28:32');

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
(7, 13, 2, '2026-06-15 20:12:41'),
(9, 15, 4, '2026-06-15 20:12:41'),
(10, 16, 4, '2026-06-18 02:49:16'),
(11, 19, 6, '2026-06-18 04:25:51');

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
  `status` enum('pending','approved','rejected','registered') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `topic_registrations`
--

INSERT INTO `topic_registrations` (`id`, `student_id`, `topic_id`, `semester_id`, `description`, `keywords`, `desired_lecturer_id`, `status`, `created_at`) VALUES
(1, 1, 11, 1, NULL, 'machine learning, AI', NULL, 'registered', '2026-05-14 09:28:51'),
(2, 2, 10, 1, NULL, 'deep learning', NULL, 'approved', '2026-05-14 09:28:51'),
(3, 4, 9, 2, NULL, 'web development', NULL, 'approved', '2026-05-14 09:28:51'),
(4, 5, 13, 1, 'Interested in blockchain', 'blockchain', 2, 'approved', '2026-06-15 20:12:41'),
(5, 6, 14, 2, 'ML is cool', 'ml', 3, 'approved', '2026-06-15 20:12:41'),
(6, 7, 19, 1, NULL, '', NULL, 'approved', '2026-06-18 04:12:00'),
(7, 12, 30, 1, NULL, '', 6, 'registered', '2026-06-19 20:51:52');

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
(9, 'nguyenlinh230809@gmail.com', '$2y$10$tf6z0BSK7nYVTW8dwToYse3eBrNTCHvf7HpPBAVCeC0MrWpMotIlC', 'student', 'active', '2026-05-19 01:58:41'),
(10, 'lecturer3@vnu.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecturer', 'active', '2026-06-15 20:12:41'),
(11, 'student5@vnu.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active', '2026-06-15 20:12:41'),
(12, '23070606@vnu.edu.vn', '$2y$10$clru5UcE6byhU610t9W5CepnkyEILKZxdXT.OQURnzyzlTYQaWUou', 'student', 'active', '2026-06-18 03:50:42'),
(14, 'cdoan1@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecturer', 'active', '2026-06-18 06:47:16'),
(15, '2372817@vnu.edu.vn', '$2y$10$VLdXrY3w/L00.nRTeFeOrengDMgj9OP6bOkIi6NQNsGk49liMEYvy', 'student', 'active', '2026-06-18 18:24:15'),
(17, 'kcjdn88@gmail.com', '$2y$10$qCnLPSbW15bOxERCCnLZ0ufM5sGxKiz4TSzBtWocUw74T5PwB6m/6', 'lecturer', 'active', '2026-06-18 19:22:50'),
(18, '342341@gmail.com', '$2y$10$v88cF72KLz7rsEhcfiWMke.IOdQJW/Myyq6ASqyBqjn5jOLhSdZSy', 'lecturer', 'active', '2026-06-18 19:27:18'),
(19, 'cdkns@gmail.com', '$2y$10$HKbUeBxAlgZbxbG3FwggSu0555gyENDI5wMU8KU7SqO0EDAjKD12e', 'student', 'active', '2026-06-19 12:45:29'),
(20, 'abc@gmail.com', '$2y$10$4MYa.4cnkGAt8YGg2RvPOuKwD85O8LNnQmErU7mA7DvoMFj/pvLAK', 'student', 'active', '2026-06-19 18:56:43'),
(21, 'befw@gmail.com', '$2y$10$9B/4zMK34a4kTcngom5j9u/ZRKsyutkGY/LnTvra4VQfECSPYNWGq', 'student', 'active', '2026-06-19 20:09:00'),
(22, '34@gmail.com', '$2y$10$gZRTa2XVFuxSwRlxbNlJTOMEyjDst9ihQ49KURUggXhheh2./ioBC', 'student', 'active', '2026-06-19 20:25:48'),
(23, 'linh@mail.com', '$2y$10$XK5AQB8H68xoNWc0TJVbeu8hqkkyZdYx17LrM/33Z9IXm/ogYL2iO', 'lecturer', 'active', '2026-06-21 14:18:56'),
(24, 'loo@gmail.com', '$2y$10$Ny3HS9sez50xZvGf1YTHDuO0RlwgG8muN3glBIalMwGB2silUyHau', 'student', 'active', '2026-06-21 16:33:45');

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
-- Chỉ mục cho bảng `score_edit_requests`
--
ALTER TABLE `score_edit_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `score_id` (`score_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

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
  ADD UNIQUE KEY `user_id` (`user_id`);

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
-- Chỉ mục cho bảng `supervision_assignments`
--
ALTER TABLE `supervision_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_student_semester` (`student_id`,`semester_id`),
  ADD KEY `lecturer_id` (`lecturer_id`),
  ADD KEY `semester_id` (`semester_id`),
  ADD KEY `assigned_by` (`assigned_by`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `milestones`
--
ALTER TABLE `milestones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `score_edit_requests`
--
ALTER TABLE `score_edit_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `supervision_assignments`
--
ALTER TABLE `supervision_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT cho bảng `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT cho bảng `topic_assignments`
--
ALTER TABLE `topic_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `topic_registrations`
--
ALTER TABLE `topic_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- Các ràng buộc cho bảng `score_edit_requests`
--
ALTER TABLE `score_edit_requests`
  ADD CONSTRAINT `score_edit_requests_ibfk_1` FOREIGN KEY (`score_id`) REFERENCES `evaluation_scores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `score_edit_requests_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE CASCADE;

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
-- Các ràng buộc cho bảng `supervision_assignments`
--
ALTER TABLE `supervision_assignments`
  ADD CONSTRAINT `supervision_assignments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supervision_assignments_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supervision_assignments_ibfk_3` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supervision_assignments_ibfk_4` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
