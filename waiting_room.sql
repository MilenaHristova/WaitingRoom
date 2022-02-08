-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2022 at 02:22 PM
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
  `room_id` int(11) NOT NULL,
  `text` varchar(300) NOT NULL,
  `author_id` int(11) NOT NULL,
  `author_name` varchar(64) NOT NULL,
  `send_to` int(11) NOT NULL COMMENT '0 for everyone, 1 for admin only ',
  `time` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `room_id`, `text`, `author_id`, `author_name`, `send_to`, `time`) VALUES
(26, 35, 'това се вижда само от преподаватели', 5, 'Милен Петров', 1, '2022-02-07 10:15:24'),
(27, 35, 'това се вижда от всички', 5, 'Милен Петров', 0, '2022-02-07 10:15:37'),
(28, 35, 'това се вижда само от преподаватели и Студент 1', 6, 'Студент Едно', 1, '2022-02-07 10:18:01'),
(29, 35, 'това се вижда от всички', 6, 'Студент Едно', 0, '2022-02-07 10:18:27'),
(30, 35, 'hey', 7, 'Студент Две', 0, '2022-02-07 10:19:33');

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
  `type` enum('project_defense','referat','mark','') DEFAULT NULL,
  `url` varchar(400) DEFAULT NULL,
  `meeting_password` varchar(100) DEFAULT NULL,
  `break_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `creator_id`, `moderator_id`, `name`, `description`, `type`, `url`, `meeting_password`, `break_until`) VALUES
(1, 1, 1, 'Защита на проекти 1-ва група', 'ееееееееее\r\nееееееееее\r\nееееееееее', NULL, '', '', NULL),
(2, 1, 1, 'Устен изпит Кн2', 'е', NULL, '', '', NULL),
(3, 1, 1, 'Писмен изпит ИС', '', NULL, '', '', NULL),
(7, 5, 5, 'Стая 1', 'Описание', NULL, 'link', 'password', NULL),
(8, 5, 5, 'Test staq', 'op', NULL, 'link', 'passw', NULL),
(9, 5, 5, 'staq 2', 'op', NULL, 'l', 'p', NULL),
(11, 5, 5, 'test 3', 'op', NULL, 'link', 'p', NULL),
(35, 5, 5, 'st 3', '', NULL, '', '', '2022-02-04 15:25:00'),
(36, 5, 5, 'test', 'opisanie', NULL, '', '', NULL),
(37, 5, 5, 'test', 'opisanie', NULL, '', '', NULL),
(38, 5, 5, 'test2 ', 'opisanie', NULL, '', '', '2022-02-04 19:21:00'),
(39, 5, 5, 'test3', 'op', 'project_defense', '', '', NULL);

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
(35, 9, '2022-01-31 12:45:00', 0, 2, 0, 1),
(37, 6, '2022-01-28 12:45:00', 0, 1, 1, 0),
(37, 7, '2022-01-29 12:45:00', 0, 1, 1, 0),
(37, 8, '2022-01-30 12:45:00', 0, 1, 1, 0),
(37, 9, '2022-01-31 12:45:00', 1, 2, 0, 0),
(37, 10, '2022-02-01 12:45:00', 1, 3, 0, 0),
(37, 11, '2022-02-02 12:45:00', 1, 4, 0, 0),
(37, 12, '2022-02-03 12:45:00', 1, 5, 0, 0),
(37, 13, '2022-02-04 12:45:00', 1, 6, 0, 0),
(37, 14, '2022-02-05 12:45:00', 1, 7, 0, 0),
(37, 15, '2022-02-06 12:45:00', 1, 8, 0, 0),
(38, 6, '2022-01-28 12:45:00', 0, 1, 1, 0),
(38, 7, '2022-01-29 12:45:00', 1, 2, 0, 0),
(38, 8, '2022-01-30 12:45:00', 1, 3, 0, 0),
(38, 9, '2022-01-31 12:45:00', 1, 4, 0, 0),
(38, 10, '2022-02-01 12:45:00', 1, 5, 0, 0),
(38, 11, '2022-02-02 12:45:00', 1, 6, 0, 0),
(38, 12, '2022-02-03 12:45:00', 1, 7, 0, 0),
(38, 13, '2022-02-04 12:45:00', 1, 8, 0, 0),
(38, 14, '2022-02-05 12:45:00', 1, 9, 0, 0),
(38, 15, '2022-02-06 12:45:00', 1, 10, 0, 0),
(38, 16, '2022-02-07 12:45:00', 1, 11, 0, 0),
(38, 17, '2022-02-08 12:45:00', 1, 12, 0, 0),
(38, 18, '2022-02-09 12:45:00', 1, 13, 0, 0),
(38, 19, '2022-02-10 12:45:00', 1, 14, 0, 0);

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
(9, 81444, 'Студент 4', 1, 'student4', '$2y$10$G.hrMEkSs2X.rSesANPQoeJmuh7sHZVdIknMCWDgQVpmqCJ01p8nq'),
(10, 81555, 'st 5', 1, 'student5', '$2y$10$Qi/BurBzNr60JvlxQxP.s.sCsNm.8ltLtcAXcEIZDn78Yuh88t2g6'),
(11, 81666, 'st 6', 1, 'student6', '$2y$10$GQY4h5qz/.uEc4EuOdNfIez39TXQsz.hyyjZW5xVdzdm9zQAfIKym'),
(12, 81777, 'st 7', 1, 'student7', '$2y$10$w0pc82Ie7nLaGcvicX7P.e3KZkCrudquu4I1hTHI.frrSJFWaaWz2'),
(13, 81888, 'st 8', 1, 'student8', '$2y$10$vNBDgV9xNQPJxMYtSok.xeca.yvKLxFfJQ/kREbX.cwOKXswApFYS'),
(14, 81999, 'st 9', 1, 'student9', '$2y$10$gtTnj8/pcQVUi9UWY9l/Ouv7J3qim4NHwtsFHkYBk1xV2ibQZR8ie'),
(15, 88111, 'st 10', 1, 'studen10', '$2y$10$Qtf1/6UkSqPbDiHjGHDoX.2/UN.Be9RD9sG.X2rWBwftDsXtonrZu'),
(16, 88222, 'st 11', 1, 'student11', '$2y$10$sOgMcp7Dr1NmARneIgjH2eRWb.FOCOnQ4EgStCkOQ0IBOm6./xb/S'),
(17, 88333, 'st 12', 1, 'student12', '$2y$10$d9oIvQc8rWbYBLFjRbV9EeYKQGrsdUr42qKroHYRsIhsJrVLNnplS'),
(18, 88444, 'st 13', 1, 'student13', '$2y$10$gNc4eGU7OZWmxoBIE34kjO8NBu9yvL4U5qhRzVJDA3B6FKtNtkz9m'),
(19, 88555, 'st 14', 1, 'student14', '$2y$10$R7k2WkqFDqitOvrSyq08IOwcszcl9z8QNWcD8JeD/kPR7UYssVTn6');

--
-- Indexes for dumped tables
--

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
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

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
