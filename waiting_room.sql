-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2022 at 05:43 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

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
(9, 85, 'Съобщение до всички!', 6, 'Студент 1', 0, '2022-02-14 06:42:34'),
(10, 85, 'Съобщение до преподавател', 7, 'Студент 2', 1, '2022-02-14 06:43:04');

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
  `type_id` int(11) DEFAULT NULL,
  `url` varchar(400) DEFAULT NULL,
  `meeting_password` varchar(100) DEFAULT NULL,
  `break_until` datetime DEFAULT NULL,
  `avg_time` int(11) DEFAULT NULL,
  `passed_people` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `creator_id`, `moderator_id`, `name`, `description`, `type_id`, `url`, `meeting_password`, `break_until`, `avg_time`, `passed_people`) VALUES
(84, 20, 20, 'Устен изпит', 'Стая създадена от преподавател 2;  създадена без списък и без тип', NULL, '', '', NULL, NULL, 0),
(85, 21, 6, 'Представяне на проекти', 'Стая създадена от преподавател 1; Модератор - Студент Едно; Списък - fn2.csv; Тип - Защита на проект (~ 15 минути);', 1, 'https://bbb.fmi.uni-sofia.bg/', '', NULL, 15, 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_student`
--

CREATE TABLE `room_student` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `time` datetime DEFAULT NULL,
  `in_time` datetime DEFAULT NULL,
  `out_time` datetime DEFAULT NULL,
  `waiting` tinyint(1) NOT NULL DEFAULT 1,
  `team` int(11) DEFAULT NULL,
  `in_room` tinyint(1) NOT NULL DEFAULT 0,
  `is_next` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_student`
--

INSERT INTO `room_student` (`id`, `room_id`, `student_id`, `time`, `in_time`, `out_time`, `waiting`, `team`, `in_room`, `is_next`) VALUES
(93, 85, 6, '2022-02-15 12:45:00', '2022-02-14 18:40:31', NULL, 0, 1, 1, 0),
(94, 85, 7, '2022-02-15 13:00:00', NULL, NULL, 1, 2, 0, 1),
(95, 85, 8, '2022-02-15 13:00:00', NULL, NULL, 1, 2, 0, 1),
(96, 85, 9, '2022-02-15 13:15:00', NULL, NULL, 1, 3, 0, 0),
(97, 85, 10, '2022-02-15 13:15:00', NULL, NULL, 1, 3, 0, 0),
(98, 85, 11, '2022-02-15 13:30:00', NULL, NULL, 1, 4, 0, 0),
(99, 85, 12, '2022-02-15 13:45:00', NULL, NULL, 1, 5, 0, 0),
(100, 85, 13, '2022-02-15 14:00:00', NULL, NULL, 1, 6, 0, 0),
(101, 85, 14, '2022-02-15 14:15:00', NULL, NULL, 1, 7, 0, 0),
(102, 85, 15, '2022-02-15 14:30:00', NULL, NULL, 1, 8, 0, 0),
(103, 85, 16, '2022-02-15 14:45:00', NULL, NULL, 1, 9, 0, 0),
(104, 85, 17, '2022-02-15 15:00:00', NULL, NULL, 1, 10, 0, 0),
(105, 85, 18, '2022-02-15 15:15:00', NULL, NULL, 1, 11, 0, 0),
(106, 85, 19, '2022-02-15 15:30:00', NULL, NULL, 1, 12, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_type`
--

CREATE TABLE `room_type` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `avg_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_type`
--

INSERT INTO `room_type` (`id`, `name`, `avg_time`) VALUES
(1, 'Защита на проект', 15),
(5, 'Представяне на реферат', 8),
(6, 'Нанасяне на оценка', 5);

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
(6, 81111, 'Студент 1', 1, 'student1', '$2y$10$S1xKJO7bGSlU8RQjjBmt2ex4Rme9ba41HcvNwnbPPoLBcaiky2wEy'),
(7, 81222, 'Студент 2', 1, 'student2', '$2y$10$2BdT28fJmqcDDUvxkF9XHewBeyfz6Q.YrU2aNOboR5skYoWfp33fS'),
(8, 81333, 'Студент 3', 1, 'student3', '$2y$10$kJqWLlsaPPFlVga1kU3NKefj2u8Pf5HLHB2Qnm8LGMBQuWvZFSC2u'),
(9, 81444, 'Студент 4', 1, 'student4', '$2y$10$G.hrMEkSs2X.rSesANPQoeJmuh7sHZVdIknMCWDgQVpmqCJ01p8nq'),
(10, 81555, 'Студент 5', 1, 'student5', '$2y$10$Qi/BurBzNr60JvlxQxP.s.sCsNm.8ltLtcAXcEIZDn78Yuh88t2g6'),
(11, 81666, 'Студент 6', 1, 'student6', '$2y$10$GQY4h5qz/.uEc4EuOdNfIez39TXQsz.hyyjZW5xVdzdm9zQAfIKym'),
(12, 81777, 'Студент 7', 1, 'student7', '$2y$10$w0pc82Ie7nLaGcvicX7P.e3KZkCrudquu4I1hTHI.frrSJFWaaWz2'),
(13, 81888, 'Студент 8', 1, 'student8', '$2y$10$vNBDgV9xNQPJxMYtSok.xeca.yvKLxFfJQ/kREbX.cwOKXswApFYS'),
(14, 81999, 'Студент 9', 1, 'student9', '$2y$10$gtTnj8/pcQVUi9UWY9l/Ouv7J3qim4NHwtsFHkYBk1xV2ibQZR8ie'),
(15, 88111, 'Студент 10', 1, 'studen10', '$2y$10$Qtf1/6UkSqPbDiHjGHDoX.2/UN.Be9RD9sG.X2rWBwftDsXtonrZu'),
(16, 88222, 'Студент 11', 1, 'student11', '$2y$10$sOgMcp7Dr1NmARneIgjH2eRWb.FOCOnQ4EgStCkOQ0IBOm6./xb/S'),
(17, 88333, 'Студент 12', 1, 'student12', '$2y$10$d9oIvQc8rWbYBLFjRbV9EeYKQGrsdUr42qKroHYRsIhsJrVLNnplS'),
(18, 88444, 'Студент 13', 1, 'student13', '$2y$10$gNc4eGU7OZWmxoBIE34kjO8NBu9yvL4U5qhRzVJDA3B6FKtNtkz9m'),
(19, 88555, 'Студент 14', 1, 'student14', '$2y$10$R7k2WkqFDqitOvrSyq08IOwcszcl9z8QNWcD8JeD/kPR7UYssVTn6'),
(20, 0, 'Преподавател 2', 2, 'teacher2', '$2y$10$vJlfdGZoaFWjrypD6Wmdhehd2Ug0UuwFU.O7QTM/enQ7teIAUDOe2'),
(21, 0, 'Преподавател 1', 2, 'teacher1', '$2y$10$wXYUbNojlTFDEQzYsJXNgu.JcmLA8e4XiyPitjAhO0pfmtViL1osG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`),
  ADD KEY `room_id_foreign_key` (`room_id`),
  ADD KEY `author_id_foreign_key` (`author_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `moderator_id` (`moderator_id`),
  ADD KEY `rooms_ibfk_3` (`type_id`);

--
-- Indexes for table `room_student`
--
ALTER TABLE `room_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `room_type`
--
ALTER TABLE `room_type`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `room_student`
--
ALTER TABLE `room_student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `room_type`
--
ALTER TABLE `room_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `author_id_foreign_key` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `room_id_foreign_key` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `rooms_ibfk_2` FOREIGN KEY (`moderator_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `rooms_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `room_type` (`id`);

--
-- Constraints for table `room_student`
--
ALTER TABLE `room_student`
  ADD CONSTRAINT `room_student_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_student_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
