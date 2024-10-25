-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 10:34 AM
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
-- Database: `uts`
--

-- --------------------------------------------------------

--
-- Table structure for table `todo_lists`
--

CREATE TABLE `todo_lists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_done` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `todo_lists`
--

INSERT INTO `todo_lists` (`id`, `user_id`, `title`, `description`, `created_at`, `is_done`) VALUES
(5, 6, 'kerja', '', '2024-10-16 14:00:23', 1),
(7, 6, 'makan malam', '', '2024-10-16 14:57:26', 0),
(8, 6, 'minum', '', '2024-10-17 02:19:37', 0),
(10, 9, 'makan', '', '2024-10-17 02:47:06', 0),
(11, 9, 'minum', '', '2024-10-17 02:47:15', 1),
(12, 9, 'mandi', '', '2024-10-17 02:47:19', 0),
(13, 9, 'makan malam', 'pizza with pineapple', '2024-10-17 03:15:05', 0),
(14, 9, 'minum', 'beer', '2024-10-17 03:29:30', 0),
(15, 8, 'Menguasai Dunia', 'Progres menguasai dunia, melalui lubang cacing', '2024-10-17 14:14:57', 1),
(16, 10, 'makan ', 'pizza with pineapple', '2024-10-18 03:13:44', 1),
(17, 10, 'minum', 'sprite with bittermelon', '2024-10-18 03:14:10', 1),
(18, 10, 'conquer the world', 'for my beloved', '2024-10-18 03:14:39', 0),
(19, 10, 'watching some stuff', '', '2024-10-18 03:15:04', 1),
(20, 10, 'doing some stuff ', '', '2024-10-18 03:15:22', 1),
(21, 10, 'conquer the demon inside my body', '', '2024-10-18 03:15:52', 1),
(22, 10, 'befriends with demon lord', '', '2024-10-18 03:16:20', 0),
(23, 10, 'go to another world', 'this world is boring as f', '2024-10-18 03:16:45', 0),
(24, 10, 'conquer the other world', '', '2024-10-18 03:17:31', 0),
(25, 10, 'commit mass ******', '', '2024-10-18 03:17:48', 1),
(26, 10, 'commit mass ******', 'im joking ', '2024-10-18 05:30:21', 1),
(27, 10, 'A', 'makan\r\n', '2024-10-22 14:07:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `hobbies` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `address`, `age`, `dob`, `gender`, `hobbies`, `photo`) VALUES
(6, 'puan', 'puanmaharani758@gmail.com', '$2y$10$cEhIecbZFfYPihNlJQW6Iu1M.9vhpi2tks71CLDxL.HFV/AEiv6YK', '', 19, '0000-00-00', 'Male', '', 'uploads/Screenshot 2024-02-21 185721.png'),
(7, 'can', 'gara@gmail.com', '$2y$10$1ZZXz3LrYRNebULKu3LygOCs.7GpPkFAMeLVjqPtOOp1l7E3bK3rO', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Jeferson', 'jasonjeferson81@gmail.com', '$2y$10$QiYAS/kR75zc3fmSlGnrBe/CSBnICt2Y4s5UnVohEZe7S4VEEDqdC', 'Alicante.56 No.57', 19, '2005-07-15', 'Male', 'Games', 'uploads/Emu-Otori-Smile.png'),
(9, 'C', 's@gmail.com', '$2y$10$9EU/ofgr6ejdJXqCx7TSwuqkL3HPpEH50bdPBTDyTNcpBMEc88EEq', '', 100, '2024-01-01', 'Male', '', 'uploads/Screenshot 2024-04-16 113930.png'),
(10, 'coba', 'cobacoba@gmail.com', '$2y$10$xRhTGVk3TB4wtjJvYZRY8OcbZEd0T.ltdeRLucs1/mveNJBlEFAbm', 'in this world', 10000, '2024-01-01', 'Male', 'doing some stuff', 'uploads/Screenshot 2024-04-16 113930.png'),
(11, 'a', 'a@gmail.com', '$2y$10$jnT1Eh2TcQ8nKrtDDxU2JOgLA0SHH7GRQ87My68wHx4KhYttpKrii', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `todo_lists`
--
ALTER TABLE `todo_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `todo_lists`
--
ALTER TABLE `todo_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `todo_lists`
--
ALTER TABLE `todo_lists`
  ADD CONSTRAINT `todo_lists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
