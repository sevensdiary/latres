-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2026 at 05:38 PM
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
-- Database: `latres`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `labID` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` enum('08:00','10:30','13:00','15:30') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `labID`, `tanggal`, `jam`, `created_at`) VALUES
(1, 6, 2, '2026-05-14', '', '2026-05-13 14:17:41'),
(2, 6, 2, '2026-05-14', '10:30', '2026-05-13 14:20:34'),
(3, 6, 2, '2026-05-14', '15:30', '2026-05-13 14:29:44'),
(6, 9, 2, '2026-05-14', '13:00', '2026-05-13 15:28:38'),
(7, 9, 2, '2026-05-22', '13:00', '2026-05-13 15:31:03'),
(8, 9, 2, '2026-05-15', '10:30', '2026-05-13 15:32:13');

-- --------------------------------------------------------

--
-- Table structure for table `laboratories`
--

CREATE TABLE `laboratories` (
  `labID` int(11) NOT NULL,
  `nama_lab` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laboratories`
--

INSERT INTO `laboratories` (`labID`, `nama_lab`, `created_at`) VALUES
(1, 'Lab Basis Data', '2026-05-13 00:07:55'),
(2, 'Lab AI', '2026-05-13 00:07:55'),
(3, 'Lab Jaringan', '2026-05-13 00:07:55'),
(4, 'Lab Multimedia', '2026-05-13 00:07:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'jul', 'prazztiwi@gmail.com', '$2y$10$cNw8Uki981trs0dPZO040OZ2fNHRllmzs1pz8ZM63OXkcX1Pt32Qe', '2026-05-13 10:03:21'),
(2, 'vantelaz', 'julia@gmail.com', '$2y$10$uqCeQ52lcljUAOOdtMysauCTILEcJtKi.fFxkOTSgb351mWjVoANG', '2026-05-13 10:11:56'),
(3, 'disela', 'jul@gmail.com', '$2y$10$sJ1P7JAfOtTn//nY6t1M7.TkMh.SvGHfjCCHar0lMwFk5MB7ypweK', '2026-05-13 10:15:47'),
(4, 'julijul', 'juls@gmail.com', '$2y$10$8IuvyhS6i.Q8ksdX9PykA.UXKm7jyPY0iqjKD4C9gt5ZkE7UMV66K', '2026-05-13 10:16:33'),
(5, 'juleey', 'julii@gmail.com', '$2y$10$4kCbpMpAZWGIlqH5CoWeROGK27kEQdo6cTc8ynmPTPPbGxunv2huK', '2026-05-13 10:22:42'),
(6, 'diselaretakmu', 'juliay@gmail.com', '$2y$10$GGURuySQPKSG/O/9xHs6ieD1pXinBiFH9noWUZX.FxyQGbWp.wraO', '2026-05-13 11:07:53'),
(7, 'diselaretakmuu', 'juliayy@gmail.com', '$2y$10$RWRqvdKqNSfSaFhrg7MEn.th9O8pPsaNVywkN9eCAxQa0Iq76dy0q', '2026-05-13 11:09:43'),
(8, 'lia', 'lia@gmail.com', '$2y$10$jzChGsg0yb/LiWD0FInYyexSR/7OROb07zMHi6AUkrLOr0P88G8ie', '2026-05-13 14:59:37'),
(9, 'yaya', 'yaya@gmail.com', '$2y$10$KbM3DwZ7WyCRwNUsAb20S.aidcw9P4rFQsZH2Wggu2qN8BBzwAzxO', '2026-05-13 15:12:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `labID` (`labID`);

--
-- Indexes for table `laboratories`
--
ALTER TABLE `laboratories`
  ADD PRIMARY KEY (`labID`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `laboratories`
--
ALTER TABLE `laboratories`
  MODIFY `labID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`labID`) REFERENCES `laboratories` (`labID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
