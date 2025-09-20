-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2025 at 08:42 AM
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
-- Database: `attendance-system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` enum('Present','Absent') DEFAULT 'Present',
  `is_late` tinyint(1) DEFAULT 0,
  `date` date NOT NULL,
  `time_in` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `status`, `is_late`, `date`, `time_in`) VALUES
(1, 1, 'Present', 0, '2025-09-05', '08:05:00'),
(2, 1, 'Present', 1, '2025-09-05', '09:15:00'),
(3, 2, 'Absent', 0, '2025-09-05', '00:00:00'),
(4, 4, 'Present', 1, '2025-09-05', '01:30:43'),
(5, 4, 'Present', 0, '2025-09-05', '01:30:47'),
(6, 4, 'Present', 0, '2025-09-05', '01:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `created_at`) VALUES
(1, 'BS Computer Science', '2025-09-04 16:46:08'),
(2, 'BS Information Technology', '2025-09-04 16:46:08'),
(3, 'BS Information Systems', '2025-09-04 16:46:08'),
(5, 'BS Civil Engineering', '2025-09-20 06:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `excuse_letters`
--

CREATE TABLE `excuse_letters` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `program` varchar(100) NOT NULL,
  `letter_text` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `excuse_letters`
--

INSERT INTO `excuse_letters` (`id`, `student_id`, `program`, `letter_text`, `attachment`, `status`, `created_at`) VALUES
(1, 7, '1', 'excuse letter', NULL, 'Approved', '2025-09-20 06:13:00'),
(2, 7, '1', 'Absent ako for tommorow kasi may dental', NULL, 'Approved', '2025-09-20 06:19:36'),
(3, 8, '5', 'My ojt is requesting me to stay for the week', NULL, 'Approved', '2025-09-20 06:22:29'),
(4, 7, '1', '12312', NULL, 'Pending', '2025-09-20 06:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `year_level` enum('1','2','3','4') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `course_id`, `year_level`) VALUES
(1, 2, 1, '1'),
(2, 3, 2, '2'),
(4, 9, 1, '1'),
(5, 4, 2, '1'),
(6, 9, 3, '1'),
(7, 15, 1, '1'),
(8, 16, 5, '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `full_name`, `created_at`) VALUES
(1, 'admin1', '$2y$10$ztLdMcS3/2zpx4izJmGdZON8nYyFQpP95D8DCHiBOAoq1jZpWt94y', 'admin', 'Super Admin', '2025-09-04 16:46:08'),
(2, 'stud1', '$2y$10$ryZK92Y8SBNG2x5hAqnzG.4cOAcjEpo9r2wWQLeL22F9epzjW3YHu', 'student', 'Juan Dela Cruz', '2025-09-04 16:46:08'),
(3, 'stud2', '$2y$10$ryZK92Y8SBNG2x5hAqnzG.4cOAcjEpo9r2wWQLeL22F9epzjW3YHu', 'student', 'Maria Santos', '2025-09-04 16:46:08'),
(4, 'javeuser1', '$2y$10$l/c2cx8pRd0zc.YcnkVJPuWxMl/Oz/84PRDwzwlWr.cxizyxwIuuK', 'student', 'Javeson', '2025-09-04 16:55:46'),
(9, 'javestudent123', '$2y$10$RnUJXHKmtJRXfiHuL4tNx.Td8VhiI5XoDIv40NuYCmTP1FBl9Lrlq', 'student', 'Javeson Paccial', '2025-09-04 16:58:53'),
(10, 'Javeadmin123', '$2y$10$1qtlBhJ5P0rjKkFPDCxMl.tmDuVjnDYtegst68./3OjJav.7VSqQq', 'admin', 'Javeson Paccial', '2025-09-04 17:02:17'),
(11, 'javestudent2', '$2y$10$74aEKiCzWp95PgwmkREqkunczxWuPHrrDFFfBcBx1ta9eu45zyC8S', 'student', 'Javeson Paccial', '2025-09-04 17:09:37'),
(12, 'javeadmin2', '$2y$10$.j0gvUpbEUd7G3E6gXIVauk3N2GhG1exjrl/y4UeFcJsC1ZD1y8OG', 'admin', 'Javeson Paccial', '2025-09-04 17:32:20'),
(13, 'javeson1', '$2y$10$gmyt2bro7RmwO9WYKMtdPui2kuQTLPlbFkOHeuGWa6WJA/OyIy4/6', 'student', 'Javeson Paccial', '2025-09-20 02:21:50'),
(14, 'javeson2', '$2y$10$YSg.Mt9NyCj.HsvQDtryb.FjW/3PlpYalGisBkpM8hBaaOlwsSycy', 'admin', 'Javeson Paccial admin', '2025-09-20 02:22:06'),
(15, 'javeson3', '$2y$10$7zs1QMwGwvj8tBV1UkoBweKNMxu9UdKYpW.0rW8r71fIDuQNg6HIS', 'student', 'Javeson Paccial Student', '2025-09-20 02:24:34'),
(16, 'javeson', '$2y$10$zowcGx1GpQREtKiMl9Vu/ef566gKyrs9iPgEtoUebDg6Cl6.QrvFe', 'student', 'Javeson Paccial Civil Eng', '2025-09-20 06:21:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `excuse_letters`
--
ALTER TABLE `excuse_letters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `excuse_letters`
--
ALTER TABLE `excuse_letters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `excuse_letters`
--
ALTER TABLE `excuse_letters`
  ADD CONSTRAINT `excuse_letters_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
