-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 23, 2024 at 08:27 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_farming`
--

-- --------------------------------------------------------

--
-- Table structure for table `sensor_data`
--

CREATE TABLE `sensor_data` (
  `id` int NOT NULL,
  `temperature` float NOT NULL,
  `humidity` float NOT NULL,
  `soil_moisture` float NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensor_data`
--

INSERT INTO `sensor_data` (`id`, `temperature`, `humidity`, `soil_moisture`, `timestamp`) VALUES
(1, 24, 40, 14.4, '2024-12-03 04:15:48'),
(2, 24, 40, 14.4, '2024-12-03 04:15:52'),
(3, 24, 40, 14.4, '2024-12-03 04:15:56'),
(4, 24, 40, 14.4, '2024-12-03 04:16:00'),
(5, 24, 40, 14.4, '2024-12-03 04:16:05'),
(6, 24, 40, 14.4, '2024-12-03 04:16:09'),
(7, 24, 40, 14.4, '2024-12-03 04:16:13'),
(8, 24, 40, 14.4, '2024-12-03 04:16:17'),
(9, 68.4, 82.5, 4.7, '2024-12-03 04:27:04'),
(10, 68.4, 82.5, 100, '2024-12-03 04:27:08'),
(11, 68.4, 82.5, 100, '2024-12-03 04:27:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
