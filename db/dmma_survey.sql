-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 02:01 PM
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
-- Database: `dmma_survey`
--

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` varchar(50) NOT NULL DEFAULT 'rating',
  `question_choices` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `survey_id`, `question_text`, `question_type`, `question_choices`) VALUES
(2, 1, 'What was your transaction in the Registrar\'s Office today?', 'thumbs', NULL),
(7, 1, 'Were you able to claim your requested document at the given time of release?', 'rating', '[\"zczxcxc\"]'),
(19, 1, 'Was the waiting time a reasonable amount for your transaction?', 'thumbs', NULL),
(20, 1, 'How would you rate the helpfulness and friendliness of the Registrar personnel during your transaction?', 'emotion', NULL),
(21, 1, 'How would you rate your overall experience with the Registrar\'s Office today?', 'emotion', NULL),
(22, 1, 'What is something you would like to share or suggest to help us improve our service?', 'text', NULL),
(26, 1, 'dfsfsdf', 'dropdown', '[\"dsfsdfsdf\",\"sdfdsfsdf\"]');

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE `responses` (
  `response_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `response` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `responses`
--

INSERT INTO `responses` (`response_id`, `survey_id`, `user_id`, `response`, `created_at`, `updated_at`) VALUES
(76, 1, NULL, '{\"2\":\"2\",\"7\":\"4\"}', '2024-12-23 05:49:26', '2024-12-23 05:49:26'),
(77, 1, NULL, '{\"2\":\"1\",\"7\":\"3\"}', '2024-12-23 05:54:27', '2024-12-23 05:54:27'),
(78, 1, NULL, '{\"2\":\"1\",\"7\":\"1\"}', '2024-12-23 09:15:36', '2024-12-23 09:15:36'),
(79, 1, NULL, '{\"2\":\"1\",\"7\":\"2\"}', '2024-12-26 02:46:13', '2024-12-26 02:46:13'),
(80, 1, NULL, '{\"2\":\"1\",\"7\":\"1\"}', '2024-12-26 06:37:41', '2024-12-26 06:37:41'),
(81, 1, NULL, '{\"2\":\"1\",\"7\":\"3\"}', '2025-01-14 07:20:43', '2025-01-14 07:20:43'),
(82, 1, NULL, '{\"2\":\"2\",\"7\":\"3\"}', '2025-01-16 07:25:25', '2025-01-16 07:25:25'),
(83, 1, NULL, '{\"2\":\"1\",\"7\":\"1\"}', '2025-01-16 07:33:58', '2025-01-16 07:33:58');

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `survey_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive','trashed') NOT NULL DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`survey_id`, `title`, `description`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Student Evaluation of Teaching Effectiveness', 'Designed to collect student feedback on teaching methods, classroom engagement, and course materials. The goal is to help faculty members refine their teaching strategies to better meet student learning needs. tt', '2024-10-02 07:48:02', '2024-10-02 07:48:02', 'active'),
(2, 'Test', 'Test', '2024-10-02 07:49:43', '2024-10-02 07:49:43', 'trashed'),
(3, 'Test', 'Test', '2024-10-02 08:24:06', '2024-10-02 08:24:06', 'trashed'),
(4, 'Student Services Satisfaction Survey', 'This survey focuses on evaluating the satisfaction levels with various student services, such as library access, counseling, health services, and extracurricular activities, to ensure that the school meets student needs outside the classroom.', '2024-10-02 09:05:14', '2024-10-02 09:05:14', 'active'),
(5, 'ss', 'sdssd', '2024-10-14 08:33:50', '2024-10-14 08:33:50', 'trashed'),
(6, 'fbdfb', 'fgbfbf', '2024-10-28 08:55:38', '2024-10-28 08:55:38', 'trashed'),
(7, 'nkn', 'nkknk', '2024-10-28 09:02:48', '2024-10-28 09:02:48', 'trashed'),
(8, 'o;;yy', 'yuuuuuuuuuuuuuuuuuuuuuuuuuuuhjh g f hdfh dfh fgh dh dfh df hdfh df hdf hdfh ', '2024-10-28 09:08:28', '2024-10-28 09:08:28', 'trashed'),
(9, 'hjhj', 'jghjghj', '2024-10-29 01:12:34', '2024-10-29 01:12:34', 'trashed'),
(10, 'dsfdsf', 'dsfsdf', '2024-10-29 01:15:53', '2024-10-29 01:15:53', 'trashed'),
(11, 'dfgfdg', 'dfgdfgdfg', '2024-10-29 01:16:38', '2024-10-29 01:16:38', 'trashed'),
(12, 'kljljkl', 'jkljkljkl', '2024-10-29 01:17:33', '2024-10-29 01:17:33', 'trashed'),
(13, 'hjkhjk', 'jhkjhkhj', '2024-10-29 01:17:40', '2024-10-29 01:17:40', 'trashed'),
(14, ',,;,', 'kpk;;', '2024-10-29 01:18:36', '2024-10-29 01:18:36', 'trashed'),
(15, '[[p;l\'l;\'', ';l\'l;\';l\'', '2024-10-29 01:21:25', '2024-10-29 01:21:25', 'trashed'),
(16, '65656', '565656', '2024-10-29 01:21:37', '2024-10-29 01:21:37', 'trashed'),
(17, 'tyujghjghj', 'ghjghjghj', '2024-10-29 01:22:07', '2024-10-29 01:22:07', 'trashed'),
(18, 'jhkj', 'jhkjhkhj', '2024-10-29 01:25:13', '2024-10-29 01:25:13', 'trashed'),
(19, 'sdfgdfsg', 'dfgdfgdfg', '2024-10-29 01:28:45', '2024-10-29 01:28:45', 'trashed'),
(20, 'sadas', 'dasdasd', '2024-10-29 01:30:27', '2024-10-29 01:30:27', 'trashed'),
(21, 'dfgdf', 'gdfgdfg', '2024-10-29 01:31:10', '2024-10-29 01:31:10', 'trashed'),
(22, 'jhgjghj', 'ghjghjgh', '2024-10-29 01:33:25', '2024-10-29 01:33:25', 'trashed'),
(23, 'hjhgj', 'ghjghj', '2024-10-29 01:41:52', '2024-10-29 01:41:52', 'trashed'),
(24, 'dfsdfsdf', 'sdfsdfsdf', '2024-10-29 01:47:15', '2024-10-29 01:47:15', 'trashed'),
(25, '[object HTMLInputElement]', '[object HTMLTextAreaElement]', '2024-10-29 02:35:13', '2024-10-29 02:35:13', 'trashed'),
(26, '[object HTMLInputElement]', '[object HTMLTextAreaElement]', '2024-10-29 02:35:16', '2024-10-29 02:35:16', 'trashed'),
(27, '[object HTMLInputElement]', '[object HTMLTextAreaElement]', '2024-10-29 02:35:18', '2024-10-29 02:35:18', 'trashed'),
(28, '[object HTMLInputElement]', '[object HTMLTextAreaElement]', '2024-10-29 02:35:53', '2024-10-29 02:35:53', 'trashed'),
(29, 'njkljkl', 'jkljkljkl', '2024-10-29 02:37:47', '2024-10-29 02:37:47', 'trashed'),
(30, 'kjlkjl', 'jkljkljkl', '2024-10-29 02:46:29', '2024-10-29 02:46:29', 'trashed'),
(31, 'dsfsdf', 'dsfsdfsd', '2024-11-11 07:22:59', '2024-11-11 07:22:59', 'trashed'),
(32, 'kl,jhkhj', '', '2024-11-11 08:15:01', '2024-11-11 08:15:01', 'trashed'),
(33, 'jhkjk', '', '2024-11-11 08:15:04', '2024-11-11 08:15:04', 'trashed'),
(34, 'jkjkj', '', '2024-11-11 08:15:07', '2024-11-11 08:15:07', 'trashed'),
(35, 'jkjkj', '', '2024-11-11 08:15:09', '2024-11-11 08:15:09', 'trashed'),
(36, 'kjkjkjkhhhhhhhh', '', '2024-11-11 08:15:13', '2024-11-11 08:15:13', 'trashed'),
(37, 'jhkhjkhjkjhkhj', '', '2024-11-11 08:15:17', '2024-11-11 08:15:17', 'trashed'),
(38, 'jhkhjkhjkjhkhjk', '', '2024-11-11 08:15:20', '2024-11-11 08:15:20', 'trashed'),
(39, 'hjkjhkjhkjhkhjk', '', '2024-11-11 08:15:24', '2024-11-11 08:15:24', 'trashed'),
(40, 'jhkhjkhjkjhkh', '', '2024-11-11 08:15:28', '2024-11-11 08:15:28', 'trashed'),
(41, 'hjkhjkhjkhjkhj', '', '2024-11-11 08:15:31', '2024-11-11 08:15:31', 'trashed'),
(42, 'kjhkhjkjhkjh', '', '2024-11-11 08:15:35', '2024-11-11 08:15:35', 'trashed'),
(43, 'n,,n', 'n,n,', '2025-01-20 07:42:04', '2025-01-20 07:42:04', 'trashed'),
(44, 'dsfsdf', 'sdfsdfsd', '2025-01-20 08:13:24', '2025-01-20 08:13:24', 'trashed'),
(45, 'Test', 'sdfsdf', '2025-01-20 08:14:11', '2025-01-20 08:14:11', 'trashed'),
(46, '/kl;kl', ';kl;kl;', '2025-01-20 08:29:02', '2025-01-20 08:29:02', 'trashed'),
(47, 'sdsdsdsd', 'sdsdsdsdsd', '2025-01-20 08:49:53', '2025-01-20 08:49:53', 'trashed'),
(48, 'test', 'tttttt', '2025-01-20 08:51:23', '2025-01-20 08:51:23', 'trashed'),
(49, 'asdasd', 'asdsadsad', '2025-01-20 08:56:28', '2025-01-20 08:56:28', 'trashed'),
(50, 'hgfhfg', 'fghfghfgh', '2025-01-21 07:16:42', '2025-01-21 07:16:42', 'trashed'),
(51, 'bvnvb', 'nvbnvbnv', '2025-01-21 07:51:44', '2025-01-21 07:51:44', 'trashed'),
(52, 'dsfsdfs', 'sdfsdfsdf', '2025-01-21 08:44:34', '2025-01-21 08:44:34', 'trashed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `survey_id` (`survey_id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`survey_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`survey_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
