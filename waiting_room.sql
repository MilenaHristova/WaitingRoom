-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2022 at 09:55 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `waiting_room`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `text` varchar(300) NOT NULL,
  `author_id` int(11) NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `moderator_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(400) NOT NULL,
  `url` varchar(400) DEFAULT NULL,
  `meeting_password` varchar(100) DEFAULT NULL,
  `break_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `creator_id`, `moderator_id`, `name`, `description`, `url`, `meeting_password`, `break_until`) VALUES
(1, 1, 1, 'Защита на проекти 1-ва група', 'ееееееееее\r\nееееееееее\r\nееееееееее', '', '', NULL),
(2, 1, 1, 'Устен изпит Кн2', 'е', '', '', NULL),
(3, 1, 1, 'Писмен изпит ИС', '', '', '', NULL),
(7, 5, 5, 'Стая 1', 'Описание', 'link', 'password', NULL),
(8, 5, 5, 'Test staq', 'op', 'link', 'passw', NULL),
(9, 5, 5, 'staq 2', 'op', 'l', 'p', NULL),
(11, 5, 5, 'test 3', 'op', 'link', 'p', NULL),
(35, 5, 5, 'st 3', '', '', '', '2022-02-01 23:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `room_student`
--

CREATE TABLE `room_student` (
  `room_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `time` datetime DEFAULT NULL,
  `waiting` tinyint(1) NOT NULL DEFAULT 1,
  `team` int(11) DEFAULT NULL,
  `is_next` tinyint(1) NOT NULL DEFAULT 0,
  `is_temp` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_student`
--

INSERT INTO `room_student` (`room_id`, `student_id`, `time`, `waiting`, `team`, `is_next`, `is_temp`) VALUES
(7, 6, NULL, 0, NULL, 0, 0),
(7, 7, NULL, 0, NULL, 0, 0),
(7, 8, NULL, 0, NULL, 0, 0),
(7, 9, NULL, 0, NULL, 0, 0),
(8, 6, NULL, 0, NULL, 0, 0),
(8, 7, NULL, 0, NULL, 0, 0),
(8, 8, NULL, 0, NULL, 0, 0),
(8, 9, NULL, 0, NULL, 0, 0),
(9, 6, NULL, 0, NULL, 0, 0),
(9, 7, NULL, 0, NULL, 0, 0),
(9, 8, NULL, 0, NULL, 0, 0),
(9, 9, NULL, 0, NULL, 1, 0),
(35, 6, '2022-01-28 12:45:00', 0, 1, 1, 0),
(35, 7, '2022-01-29 12:45:00', 0, 1, 1, 0),
(35, 8, '2022-01-30 12:45:00', 0, 1, 1, 0),
(35, 9, '2022-01-31 12:45:00', 0, 2, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `faculty_number` int(5) NOT NULL,
  `name` varchar(64) NOT NULL,
  `role` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `faculty_number`, `name`, `role`, `username`, `password`) VALUES
(1, 89999, 'Петър Петров', 1, '', 'passwd'),
(4, 81761, 'milena hr', 1, 'milena', '$2y$10$t/FfsQxbNquPBYGbsG5NLOH3f56s4W9HChLklF5W91.92Y2MYirte'),
(5, 0, 'Милен Петров', 2, 'teacher', '$2y$10$lyIYNsp6TJhW5neRwRkt0.T4sZhdxFCLHStgOY0HWxjFkRqd/qLoy'),
(6, 81111, 'Студент Едно', 1, 'student1', '$2y$10$S1xKJO7bGSlU8RQjjBmt2ex4Rme9ba41HcvNwnbPPoLBcaiky2wEy'),
(7, 81222, 'Студент Две', 1, 'student2', '$2y$10$2BdT28fJmqcDDUvxkF9XHewBeyfz6Q.YrU2aNOboR5skYoWfp33fS'),
(8, 81333, 'Студент Три', 1, 'student3', '$2y$10$kJqWLlsaPPFlVga1kU3NKefj2u8Pf5HLHB2Qnm8LGMBQuWvZFSC2u'),
(9, 81444, 'Студент 4', 1, 'student4', '$2y$10$G.hrMEkSs2X.rSesANPQoeJmuh7sHZVdIknMCWDgQVpmqCJ01p8nq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `moderator_id` (`moderator_id`);

--
-- Indexes for table `room_student`
--
ALTER TABLE `room_student`
  ADD KEY `room_id` (`room_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `rooms_ibfk_2` FOREIGN KEY (`moderator_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `room_student`
--
ALTER TABLE `room_student`
  ADD CONSTRAINT `room_student_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`),
  ADD CONSTRAINT `room_student_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
