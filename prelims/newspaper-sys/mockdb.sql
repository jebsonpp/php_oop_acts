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
-- Database: `mockdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `imageName` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`article_id`, `title`, `content`, `imageName`, `author_id`, `is_active`, `created_at`, `category_id`) VALUES
(24, 'lebron james', 'bronny james', 'a70eb10863658276af5d1b459f9e87070ca58208.PNG', 1, 1, '2025-09-20 04:39:38', NULL),
(25, 'zombie', 'ufo', 'b31562cf81f86c1f47607656fccd54bd1833a3b6.PNG', 1, 1, '2025-09-20 04:45:44', 4),
(26, 'lebron James', 'Bronny James lakers', '0d9ce9161ee4fb04037df6346d83c2ec8704c78a.PNG', 1, 1, '2025-09-20 05:49:46', 5);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `created_at`) VALUES
(1, 'News Reports', '2025-09-20 02:54:35'),
(2, 'Editorials', '2025-09-20 02:54:35'),
(3, 'Opinion', '2025-09-20 02:54:35'),
(4, 'Features', '2025-09-20 02:54:35'),
(5, 'Sports', '2025-09-20 02:54:35'),
(6, 'friends', '2025-09-20 04:53:39'),
(7, 'Shocking News', '2025-09-20 06:00:05'),
(8, 'shocking newsa', '2025-09-20 06:00:10'),
(9, 'Shocking news', '2025-09-20 06:00:19');

-- --------------------------------------------------------

--
-- Table structure for table `edit_requests`
--

CREATE TABLE `edit_requests` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `requester_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'Your article titled \"asdas\" was deleted by an admin.', 0, '2025-09-19 11:48:21'),
(2, 1, 'Your article titled \"ddd\" was deleted by an admin.', 0, '2025-09-19 11:53:19'),
(3, 1, 'Your article titled \"asdfasdf\" was deleted by an admin.', 0, '2025-09-19 12:14:28'),
(4, 1, 'Your article titled \"asdfasdfasdfasdf\" was deleted by an admin.', 0, '2025-09-20 10:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `school_publication_users`
--

CREATE TABLE `school_publication_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_publication_users`
--

INSERT INTO `school_publication_users` (`user_id`, `username`, `email`, `password`, `is_admin`, `created_at`) VALUES
(1, 'jave123', '123@gmail.com', '$2y$10$DBfhDCKbr5TVwsrKziB7OeSKfOAhqg6xVw7vmKOhfL319WIejHpv.', 0, '2025-09-19 00:11:36'),
(2, 'jave1234', '1234@gmail.com', '$2y$10$fAd6sGgZ2w9UqXrDp6jeQ.5CYiKPD2eWGpBgGr5/x7kzY4NDpInk2', 1, '2025-09-19 00:31:05'),
(3, 'javeson', '111@gmail.com', '$2y$10$/8/IApSqGZZlJrdXF9/mNuIcqA7C24G0EcMnkO.r/aBPt4xl/Yge6', 1, '2025-09-20 03:21:48'),
(4, 'javesonadmin', 'admin@gmail.com', '$2y$10$A017YqoxVkqYZN2cRMTF1u11I80RGLSiAPl8Bo23ClZqj0CzwLNNu', 1, '2025-09-20 03:44:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`article_id`),
  ADD KEY `fk_articles_author` (`author_id`),
  ADD KEY `fk_articles_category` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `edit_requests`
--
ALTER TABLE `edit_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `school_publication_users`
--
ALTER TABLE `school_publication_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `edit_requests`
--
ALTER TABLE `edit_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `school_publication_users`
--
ALTER TABLE `school_publication_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_articles_author` FOREIGN KEY (`author_id`) REFERENCES `school_publication_users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_articles_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
