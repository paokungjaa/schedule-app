-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 05:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meeting_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `availabilities`
--

CREATE TABLE `availabilities` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `availabilities`
--

INSERT INTO `availabilities` (`id`, `meeting_id`, `user_id`, `start_time`, `end_time`) VALUES
(17, 2, 4, '2025-05-16 14:00:00', '2025-05-16 15:00:00'),
(18, 2, 5, '2025-05-16 13:30:00', '2025-05-16 14:30:00'),
(404, 1, 4, '2025-05-15 03:00:00', '2025-05-15 04:00:00'),
(405, 1, 1, '2025-05-19 06:00:00', '2025-05-19 06:59:59'),
(406, 1, 1, '2025-05-19 07:00:00', '2025-05-19 07:59:59'),
(407, 1, 1, '2025-05-14 12:00:00', '2025-05-14 12:59:59'),
(408, 1, 1, '2025-05-13 10:00:00', '2025-05-13 11:00:00'),
(409, 1, 1, '2025-05-14 10:00:00', '2025-05-14 11:00:00'),
(410, 1, 1, '2025-05-14 11:00:00', '2025-05-14 12:00:00'),
(411, 1, 1, '2025-05-13 17:00:00', '2025-05-13 18:00:00'),
(412, 1, 1, '2025-05-14 02:00:00', '2025-05-14 03:00:00'),
(413, 1, 1, '2025-05-15 02:00:00', '2025-05-15 03:00:00'),
(414, 1, 1, '2025-05-15 03:00:00', '2025-05-15 04:00:00'),
(415, 1, 1, '2025-05-14 03:00:00', '2025-05-14 04:00:00'),
(416, 1, 1, '2025-05-14 05:00:00', '2025-05-14 06:00:00'),
(417, 1, 1, '2025-05-14 06:00:00', '2025-05-14 07:00:00'),
(418, 1, 1, '2025-05-15 01:00:00', '2025-05-15 02:00:00'),
(419, 1, 1, '2025-05-14 19:00:00', '2025-05-14 20:00:00'),
(420, 1, 1, '2025-05-15 00:00:00', '2025-05-15 01:00:00'),
(421, 1, 1, '2025-05-15 07:00:00', '2025-05-15 08:00:00'),
(422, 1, 1, '2025-05-13 23:00:00', '2025-05-14 00:00:00'),
(423, 1, 1, '2025-05-13 07:00:00', '2025-05-13 08:00:00'),
(424, 1, 1, '2025-05-13 11:00:00', '2025-05-13 12:00:00'),
(425, 1, 1, '2025-05-13 18:00:00', '2025-05-13 19:00:00'),
(426, 1, 1, '2025-05-15 08:00:00', '2025-05-15 09:00:00'),
(427, 1, 4, '2025-05-15 08:00:00', '2025-05-15 09:00:00'),
(428, 1, 4, '2025-05-15 07:00:00', '2025-05-15 08:00:00'),
(429, 4, 1, '2025-05-13 02:00:00', '2025-05-13 03:00:00'),
(430, 4, 1, '2025-05-13 03:00:00', '2025-05-13 04:00:00'),
(431, 4, 1, '2025-05-13 04:00:00', '2025-05-13 05:00:00'),
(432, 8, 7, '2025-05-13 10:00:00', '2025-05-13 11:00:00'),
(433, 8, 7, '2025-05-13 12:00:00', '2025-05-13 13:00:00'),
(434, 8, 7, '2025-05-13 14:00:00', '2025-05-13 15:00:00'),
(435, 2, 1, '2025-05-13 04:00:00', '2025-05-13 12:00:00'),
(436, 10, 2, '2025-05-13 04:00:00', '2025-05-13 07:00:00'),
(437, 10, 1, '2025-05-13 04:00:00', '2025-05-13 12:00:00'),
(439, 12, 4, '2025-05-13 01:00:00', '2025-05-13 02:00:00'),
(440, 12, 2, '2025-05-13 01:00:00', '2025-05-13 05:00:00'),
(441, 12, 2, '2025-05-14 01:00:00', '2025-05-14 02:00:00'),
(442, 12, 2, '2025-05-14 03:00:00', '2025-05-14 04:00:00'),
(443, 12, 4, '2025-05-13 01:00:00', '2025-05-12 02:00:00'),
(445, 12, 4, '2025-05-14 01:00:00', '2025-05-14 02:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `datetime` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `owner_id`, `title`, `description`, `datetime`, `created_at`) VALUES
(1, 1, 'Project', 'Project2', '2025-05-12 16:18:00', '2025-05-10 09:19:16'),
(2, 1, 'Project Sync', 'Weekly project sync-up meeting', '2025-05-20 10:00:00', '2025-05-12 11:33:32'),
(3, 2, 'Team Standup', 'Daily standup for team', '2025-05-13 09:00:00', '2025-05-12 11:33:32'),
(4, 1, 'TestMeeting', 'Jub Jub', '2025-05-18 10:00:00', '2025-05-12 15:05:09'),
(5, 1, 'TestMeeting2', '', '0000-00-00 00:00:00', '2025-05-12 15:24:30'),
(6, 1, 'TestMeeting3', '', '0000-00-00 00:00:00', '2025-05-12 15:25:45'),
(7, 1, 'TestMeeting3', '', '0000-00-00 00:00:00', '2025-05-12 15:26:22'),
(8, 7, 'TestMeeting', 'Meet', '2025-05-12 10:00:00', '2025-05-12 15:35:12'),
(9, 7, 'TestMeeting5', 'Meeting5', '2025-05-12 01:40:00', '2025-05-12 15:37:34'),
(10, 1, 'baba', 'baba', '2025-05-14 00:44:00', '2025-05-12 15:43:30'),
(11, 1, 'Haha', 'haha', '2025-05-14 01:49:00', '2025-05-12 15:46:24'),
(12, 1, 'jaja', 'jaja', '2025-04-21 01:47:00', '2025-05-12 15:47:38');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_invitees`
--

CREATE TABLE `meeting_invitees` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','accepted','declined') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_invitees`
--

INSERT INTO `meeting_invitees` (`id`, `meeting_id`, `user_id`, `status`) VALUES
(2, 1, 2, 'declined'),
(3, 1, 4, 'accepted'),
(4, 2, 1, 'accepted'),
(5, 4, 5, 'pending'),
(6, 4, 6, 'pending'),
(7, 4, 2, 'declined'),
(8, 8, 6, 'pending'),
(9, 8, 1, 'declined'),
(10, 8, 2, 'declined'),
(11, 9, 1, 'accepted'),
(12, 9, 2, 'declined'),
(13, 10, 5, 'pending'),
(14, 10, 6, 'pending'),
(15, 10, 2, 'accepted'),
(16, 10, 7, 'pending'),
(17, 11, 2, 'declined'),
(18, 12, 2, 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'testuser', 'test@example.com', '$2y$10$8d/VeyFA4QsFGen2rVrH6.mxrml0ofkYjhHaPUYw2jrUuls5eN6VK'),
(2, 'testuser2', 'test2@example.com', '$2y$10$F9X.9ldgjm0GrGwhvBhVIu.7Iyp5FxKaJyoOrTjaQ83jz1XfjJyMC'),
(4, 'alice', 'alice@example.com', 'hashedpassword1'),
(5, 'bob', 'bob@example.com', 'hashedpassword2'),
(6, 'charlie', 'charlie@example.com', 'hashedpassword3'),
(7, 'testuser4', 'testuser4@example.com', '$2y$10$duAaR75s.MI0M1GgRcQwcuMbCORx0OFKGE/mTFI0HNZ0AcVIy/03S');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `availabilities`
--
ALTER TABLE `availabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `meeting_invitees`
--
ALTER TABLE `meeting_invitees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_id` (`meeting_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `availabilities`
--
ALTER TABLE `availabilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=446;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `meeting_invitees`
--
ALTER TABLE `meeting_invitees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `availabilities`
--
ALTER TABLE `availabilities`
  ADD CONSTRAINT `availabilities_ibfk_1` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `availabilities_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `meetings`
--
ALTER TABLE `meetings`
  ADD CONSTRAINT `meetings_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `meeting_invitees`
--
ALTER TABLE `meeting_invitees`
  ADD CONSTRAINT `meeting_invitees_ibfk_1` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meeting_invitees_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
