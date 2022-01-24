-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2022 at 04:36 PM
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

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `faculty_number` int(5) NOT NULL,
  `name` varchar(64) NOT NULL,
  `role` int(11) NOT NULL,
  `password` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL PRIMARY KEY,
  `text` varchar(300) NOT NULL,
  `author_id` int(11) NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
    FOREIGN KEY(author_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `moderator_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(400) NOT NULL,
  `url` varchar(400) NOT NULL,
  `meeting_password` varchar(100) NOT NULL,
    FOREIGN KEY(creator_id) REFERENCES users(user_id),
    FOREIGN KEY(moderator_id) REFERENCES users(user_id)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `room_student`
--

CREATE TABLE `room_student` (
  `room_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `place` int(11) NOT NULL,
    FOREIGN KEY(room_id) REFERENCES rooms(room_id),
    FOREIGN KEY(student_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--



--
-- Dumping data for table `users`
--

INSERT INTO `users` (`faculty_number`, `name`, `role`, `password`) VALUES
(89999, 'Петър Петров', 1, 'passwd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--

--
-- Indexes for table `room`
--

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
