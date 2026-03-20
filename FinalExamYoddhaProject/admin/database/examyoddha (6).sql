-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2026 at 11:28 AM
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
-- Database: `examyoddha`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` int(11) NOT NULL,
  `a_name` varchar(255) NOT NULL,
  `a_email` varchar(255) NOT NULL,
  `a_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_id`, `a_name`, `a_email`, `a_password`) VALUES
(1, 'Admin', 'admin@gmail.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `answer_details`
--

CREATE TABLE `answer_details` (
  `id` int(11) NOT NULL,
  `result_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `user_answer` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answer_details`
--

INSERT INTO `answer_details` (`id`, `result_id`, `question_id`, `user_answer`, `is_correct`) VALUES
(1, 0, 3, 'Mitochondria', 0),
(2, 0, 1, 'Ribosome', 0),
(3, 0, 2, 'Endoplasmic Reticulum', 1),
(4, 0, 1, 'Ribosome', 0),
(5, 0, 3, 'Ribosome', 1),
(6, 0, 2, 'Mitochondria', 0),
(7, 0, 1, 'Mitochondria', 0),
(8, 0, 3, 'Ribosome', 1),
(9, 0, 2, 'Endoplasmic Reticulum', 1),
(10, 0, 3, 'Endoplasmic Reticulum', 0),
(11, 0, 1, 'Nucleus', 1),
(12, 0, 2, 'Endoplasmic Reticulum', 1),
(13, 0, 1, 'Nucleus', 1),
(14, 0, 3, '', 0),
(15, 0, 2, 'Ribosome', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `u_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `budget` decimal(10,2) NOT NULL,
  `requirements` text NOT NULL,
  `deadline_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `b_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `service_id`, `u_id`, `full_name`, `email`, `phone`, `start_date`, `budget`, `requirements`, `deadline_date`, `created_at`, `b_status`) VALUES
(4, 3, 2, 'Manjunatha H S', 'manjukarthik915@gmail.com', '0829636671', '2025-05-15', 4255.00, 'drtgyuijokl;tfrcghujkl;', '2025-05-21', '2025-05-08 23:54:59', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `educators`
--

CREATE TABLE `educators` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `qualification` varchar(100) NOT NULL,
  `experience` int(11) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `joining_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `educators`
--

INSERT INTO `educators` (`id`, `name`, `email`, `phone`, `qualification`, `experience`, `specialization`, `address`, `joining_date`, `status`, `created_at`, `updated_at`, `image`, `password`) VALUES
(2, 'John', 'john@gmail.com', '9875632140', 'MCA,B ed', 5, 'Science', '73/2, 2nd Floor, Muniveerappa Layout\r\nShampura, Kaval Bairasandra', '2025-02-11', 1, '2025-05-20 11:09:20', '2025-05-20 11:09:20', '1747739360_accessories.jpg', 'e10adc3949ba59abbe56e057f20f883e'),
(3, 'Winston', 'winston@gmail.com', '9874123560', 'MSc', 7, 'Mathematics', '73/2, 2nd Floor, Muniveerappa Layout\r\nShampura, Kaval Bairasandra', '2025-05-08', 1, '2025-05-20 11:10:26', '2025-05-20 11:10:26', '1747739426_doc2.jpg', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `exam_code` varchar(50) NOT NULL,
  `test_name_id` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `total_marks` int(11) NOT NULL,
  `pass_marks` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `educator_id` int(11) DEFAULT NULL,
  `exam_type` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `exam_name`, `exam_code`, `test_name_id`, `duration`, `total_marks`, `pass_marks`, `description`, `instructions`, `educator_id`, `exam_type`, `status`, `created_at`, `updated_at`, `image`) VALUES
(1, 'JEE Mains Thermodynamics', 'JEE-THERMO-2025', 1, 25, 50, 25, 'This test is designed to help students prepare for JEE Mains focusing on the Thermodynamics chapter. It includes a mixture of conceptual and numerical questions to strengthen fundamentals.', 'Do not refresh the page during the exam.\r\n\r\nEach question carries equal marks.\r\n\r\nNo negative marking.\r\n\r\nOnce submitted, answers cannot be changed.', 2, 'Mock', 1, '2025-05-21 04:20:05', '2025-05-21 04:20:05', '1747801205_accessories.jpg'),
(2, 'NEET Biology Mock Test 1', 'NEET-BIO-MT1', 2, 5, 25, 10, 'This Biology mock test covers topics from Cell Structure, Reproduction, and Genetics. Designed to help NEET aspirants evaluate their preparation with high-quality MCQs.', '- Total 10 questions. Each correct answer gives 2.5 marks. No negative marking.\r\n- Do not refresh the page during the exam.\r\n- Ensure a stable internet connection.', 2, 'Mock', 1, '2025-05-21 04:25:43', '2025-05-21 04:39:25', '1747801543_Adventure.png'),
(3, 'SSC CGL Quantitative Aptitude Practice Test', 'SSC-QA-PT-2025', 3, 10, 30, 10, 'Practice test for SSC CGL aspirants focused on Quantitative Aptitude. Questions cover topics like Algebra, Mensuration, Number Systems, and DI. Ideal for time-bound practice.', '- Use of calculator or rough sheets is allowed.\r\n- Submit before the timer ends to avoid auto-submit.', 3, 'Mock', 1, '2025-05-21 04:26:56', '2025-05-21 04:45:41', '1747801819_watch.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_number` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `options` text NOT NULL,
  `correct_answer` int(11) NOT NULL,
  `marks` int(11) NOT NULL,
  `difficulty` enum('easy','intermediate','hard') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `exam_id`, `question_number`, `question_text`, `options`, `correct_answer`, `marks`, `difficulty`, `created_at`) VALUES
(1, 2, 1, 'if educator choose easy he have to prepeare entire qp for easy , and for intermediate and for hard like he have to preperae 3 qps', '[\"fgthjk\",\"fthjukl\",\"fghjk\",\"rdtfghj\"]', 3, 5, 'easy', '2025-05-21 04:56:11'),
(2, 2, 2, 'Which of the following is the powerhouse of the cell?', '[\"Nucleus\",\"Ribosome\",\"Mitochondria\",\"Endoplasmic Reticulum\"]', 3, 5, 'easy', '2025-05-21 05:19:15');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_number` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`options`)),
  `correct_answer` int(11) NOT NULL,
  `marks` int(11) NOT NULL,
  `difficulty` enum('Easy','Intermediate','Hard') NOT NULL,
  `question_type` varchar(50) NOT NULL DEFAULT 'Multiple Choice'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `exam_id`, `question_number`, `question_text`, `options`, `correct_answer`, `marks`, `difficulty`, `question_type`) VALUES
(1, 2, 1, 'Endoplasmic Reticulum', '[\"Nucleus\",\"Ribosome\",\"Mitochondria\",\"Endoplasmic Reticulum\"]', 0, 5, 'Easy', 'Multiple Choice'),
(2, 2, 2, 'Which of the following is the powerhouse of the cell?', '[\"Nucleus\",\"Ribosome\",\"Mitochondria\",\"Endoplasmic Reticulum\"]', 3, 5, 'Easy', 'Multiple Choice'),
(3, 2, 2, 'Which of the following is the powerhouse of the cell?', '[\"Nucleus\",\"Mitochondria\",\"Ribosome\",\"Endoplasmic Reticulum\"]', 2, 5, 'Easy', 'Multiple Choice');

-- --------------------------------------------------------

--
-- Table structure for table `test_names`
--

CREATE TABLE `test_names` (
  `id` int(11) NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_names`
--

INSERT INTO `test_names` (`id`, `test_name`, `category`, `subject`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'JEE Advanced Mock Test 1', '', 'Physics', 'This is a sample mock test covering key topics in JEE Advanced Physics including Thermodynamics, Kinematics, and Electrodynamics.', 1, '2025-05-20 11:51:50', '2025-05-20 11:52:10'),
(2, 'NEET Biology Practice Set 1', '', 'Biology', 'Includes questions on Cell Structure, Human Physiology, and Genetics.', 1, '2025-05-20 11:53:20', '2025-05-20 11:53:20'),
(3, 'KCET Chemistry Test - Organic Basics', '', 'Chemistry', 'Organic and Inorganic chemistry questions', 1, '2025-05-20 11:56:30', '2025-05-20 11:56:30');

-- --------------------------------------------------------

--
-- Table structure for table `test_results`
--

CREATE TABLE `test_results` (
  `result_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `educator_id` int(11) DEFAULT NULL,
  `total_questions` int(11) NOT NULL,
  `correct_answers` int(11) NOT NULL,
  `wrong_answers` int(11) NOT NULL,
  `skipped_questions` int(11) NOT NULL,
  `score` float NOT NULL,
  `percentage` float NOT NULL,
  `status` varchar(10) NOT NULL,
  `attempted_on` datetime DEFAULT current_timestamp(),
  `time_taken` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `pass_status` varchar(20) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `completion_time` datetime DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `difficulty` varchar(50) NOT NULL,
  `total_marks` int(11) NOT NULL,
  `obtained_marks` int(20) NOT NULL,
  `incorrect_answers` int(20) NOT NULL,
  `unattempted` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL,
  `u_name` varchar(255) NOT NULL,
  `u_email` varchar(255) NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `u_phone` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `u_name`, `u_email`, `u_password`, `u_phone`) VALUES
(1, 'Alex', 'user@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 9784653121),
(3, 'Shruthi', 'shru9@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1234567890),
(4, 'nayan', 'nayan@gmail.com', 'a45958517604f5cd90d6ee51ad9cfdb6', 7896541230);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `answer_details`
--
ALTER TABLE `answer_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `educators`
--
ALTER TABLE `educators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exam_code` (`exam_code`),
  ADD KEY `test_name_id` (`test_name_id`),
  ADD KEY `educator_id` (`educator_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `test_names`
--
ALTER TABLE `test_names`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`result_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `answer_details`
--
ALTER TABLE `answer_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `educators`
--
ALTER TABLE `educators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `test_names`
--
ALTER TABLE `test_names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `test_results`
--
ALTER TABLE `test_results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
