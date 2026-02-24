-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2026 at 04:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `habibi_institute`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `username`, `action`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 'admin', 'LOGIN', 'Successful login from IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-22 11:56:07'),
(2, 'admin', 'UPDATE_SETTINGS', 'Updated website settings', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-22 14:16:32'),
(3, 'admin', 'LOGOUT', 'User logged out from IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-22 14:16:53'),
(4, 'admin', 'LOGIN', 'Successful login from IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-22 14:17:09'),
(5, 'admin', 'ADD_SLIDE', 'Added new slide: HABIBI INSTITUTE OF HIGHER EDUCATION', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-22 14:30:32'),
(6, 'admin', 'DELETE_SLIDE', 'Deleted carousel slide ID: 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-22 14:41:26'),
(7, 'admin', 'ADD_SLIDE', 'Added new slide: HABIBI INSTITUTE OF HIGHER EDUCATION', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-22 14:44:24');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `role` enum('super_admin','admin','editor') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `full_name`, `password`, `email`, `created_at`, `last_login`, `last_login_ip`, `status`, `role`) VALUES
(1, 'admin', 'Super Administrator', '$2y$10$YourHashedPasswordHere', 'admin@habibiinstitute.edu', '2026-02-22 10:33:34', '2026-02-22 14:17:09', '::1', 'active', 'admin'),
(2, 'editor', 'Content Editor', '$2y$10$AnotherHashedPassword', 'editor@habibiinstitute.edu', '2026-02-22 11:11:08', NULL, NULL, 'active', 'editor');

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `qualification` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `status` enum('pending','contacted','confirmed','rejected') DEFAULT 'pending',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carousel_slides`
--

CREATE TABLE `carousel_slides` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `button1_text` varchar(50) DEFAULT NULL,
  `button1_link` varchar(255) DEFAULT NULL,
  `button2_text` varchar(50) DEFAULT NULL,
  `button2_link` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carousel_slides`
--

INSERT INTO `carousel_slides` (`id`, `title`, `subtitle`, `image_path`, `button1_text`, `button1_link`, `button2_text`, `button2_link`, `status`, `sort_order`, `created_at`) VALUES
(2, 'HABIBI INSTITUTE OF HIGHER EDUCATION', 'Affiliated to MJPRU | NCTE | AICTE | JPCI | BCI | BTE Lucknow\r\n\r\n🎓 Admission Open 2026-27\r\n\r\n💊 Best Pharmacy College for D.Pharm', 'assets/uploads/carousel/slide_1771771464_3817.png', '👉 Register Now & Secure Your Future', '', '', '', 1, 0, '2026-02-22 14:44:24');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `eligibility` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `curriculum` text DEFAULT NULL,
  `career_opportunities` text DEFAULT NULL,
  `fee_structure` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `slug`, `duration`, `eligibility`, `description`, `curriculum`, `career_opportunities`, `fee_structure`, `image_path`, `status`, `created_at`) VALUES
(1, 'B.A. (Bachelor of Arts)', 'ba', '3 Years', '10+2 in any stream', 'Bachelor of Arts program with multiple specializations including Hindi, English, Urdu, Sociology, Geography, and Home Science.', NULL, NULL, NULL, NULL, 1, '2026-02-22 10:33:34'),
(2, 'B.Sc. (Bachelor of Science)', 'bsc', '3 Years', '10+2 with Science', 'Bachelor of Science program offering specializations in Zoology, Botany, Chemistry, Physics, and Mathematics.', NULL, NULL, NULL, NULL, 1, '2026-02-22 10:33:34'),
(3, 'D.Pharma (Diploma in Pharmacy)', 'd-pharma', '2 Years', '10+2 with Science', 'Comprehensive diploma program in pharmaceutical sciences.', NULL, NULL, NULL, NULL, 1, '2026-02-22 10:33:34'),
(4, 'B.Ed (Bachelor of Education)', 'bed', '2 Years', 'Graduation in any stream', 'Professional degree program for aspiring teachers.', NULL, NULL, NULL, NULL, 1, '2026-02-22 10:33:34'),
(5, 'D.El.Ed (BTC)', 'deled', '2 Years', 'Graduation', 'Diploma in Elementary Education with focus on primary teaching.', NULL, NULL, NULL, NULL, 1, '2026-02-22 10:33:34'),
(6, 'LLB (Bachelor of Laws)', 'llb', '3 Years', 'Graduation in any stream', 'Professional law degree program.', NULL, NULL, NULL, NULL, 1, '2026-02-22 10:33:34'),
(7, 'B.Com (Bachelor of Commerce)', 'bcom', '3 Years', '10+2 in Commerce', 'Comprehensive commerce education with specialization options.', NULL, NULL, NULL, NULL, 1, '2026-02-22 10:33:34');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_replies`
--

CREATE TABLE `message_replies` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `website_settings`
--

CREATE TABLE `website_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','image') DEFAULT 'text',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `website_settings`
--

INSERT INTO `website_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `updated_at`) VALUES
(1, 'college_name', 'HABIBI INSTITUTE OF HIGHER EDUCATION', 'text', '2026-02-22 10:33:35'),
(2, 'college_affiliation', 'Affiliated to MJPRU, NCTE, AICTE, JPCI, BCI, BTE Lucknow', 'text', '2026-02-22 10:33:35'),
(3, 'admission_status', 'ADMISSION OPEN 2026-27', 'text', '2026-02-22 10:33:35'),
(4, 'phone_1', '9720229697', 'text', '2026-02-22 10:33:35'),
(5, 'phone_2', '9410066786', 'text', '2026-02-22 10:33:35'),
(6, 'phone_3', '9756666480', 'text', '2026-02-22 10:33:35'),
(7, 'email', 'institute.habibi@gmail.com', 'text', '2026-02-22 10:33:35'),
(8, 'address', '2 km. Dingerpur Kundarki Road, Vill. Guiller, Tehsil Bilari, Distt. Moradabad-244301 (U.P.)', 'textarea', '2026-02-22 10:33:35'),
(9, 'mission_text', 'To deliver high-quality education across diverse fields. To create a supportive and inspiring environment for holistic student growth. To ensure our graduates excel academically, socially and professionally.', 'textarea', '2026-02-22 10:33:35'),
(10, 'whatsapp_number', '9720229697', 'text', '2026-02-22 10:33:35'),
(11, 'college_tagline', 'Excellence in Higher Education', 'text', '2026-02-22 11:38:42'),
(12, 'logo_path', 'assets/img/logo.jpeg', 'text', '2026-02-22 14:16:32'),
(13, 'map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3490.0!2d78.0!3d28.0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjjCsDAwJzAwLjAiTiA3OMKwMDAnMDAuMCJF!5e0!3m2!1sen!2sin!4v1234567890', 'text', '2026-02-22 11:38:42'),
(14, 'working_hours', 'Mon-Fri: 9:00 AM - 5:00 PM, Sat: 9:00 AM - 2:00 PM', 'text', '2026-02-22 11:38:42'),
(15, 'social_facebook', '', 'text', '2026-02-22 11:38:42'),
(16, 'social_twitter', '', 'text', '2026-02-22 11:38:42'),
(17, 'social_instagram', '', 'text', '2026-02-22 11:38:42'),
(18, 'social_linkedin', '', 'text', '2026-02-22 11:38:42'),
(19, 'social_youtube', '', 'text', '2026-02-22 11:38:42'),
(20, 'meta_title', 'Habibi Institute of Higher Education - Best College in Moradabad', 'text', '2026-02-22 11:38:42'),
(21, 'meta_description', 'Habibi Institute of Higher Education offers B.A., B.Sc., D.Pharma, LLB, B.Ed courses. Affiliated to MJPRU, AICTE, BCI. Apply for admission 2026-27.', 'textarea', '2026-02-22 11:38:42'),
(22, 'meta_keywords', 'college, higher education, Moradabad, MJPRU, pharmacy college, law college, B.Ed college', 'text', '2026-02-22 11:38:42'),
(23, 'google_analytics', '', 'text', '2026-02-22 11:38:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `carousel_slides`
--
ALTER TABLE `carousel_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_replies`
--
ALTER TABLE `message_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `website_settings`
--
ALTER TABLE `website_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carousel_slides`
--
ALTER TABLE `carousel_slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_replies`
--
ALTER TABLE `message_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `website_settings`
--
ALTER TABLE `website_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admissions`
--
ALTER TABLE `admissions`
  ADD CONSTRAINT `admissions_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `message_replies`
--
ALTER TABLE `message_replies`
  ADD CONSTRAINT `message_replies_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `contact_messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_replies_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;