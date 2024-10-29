-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2024 at 04:11 PM
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
-- Database: `app`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Category` varchar(100) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` int(11) NOT NULL,
  `OrderDate` date NOT NULL,
  `ItemStatus` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `Name`, `Category`, `Quantity`, `Price`, `OrderDate`, `ItemStatus`) VALUES
(13, 'Urea', 'Fertilizer', 1, 20, '2024-10-20', 'Sold'),
(14, 'Urea', 'Fertilizer', 40, 20, '2024-10-20', 'Sold'),
(15, 'Thunderbird', 'Animal Feeds', 1, 45, '2024-10-20', 'Sold'),
(16, 'Thunderbird', 'Animal Feeds', 1, 45, '2024-10-20', 'Sold'),
(17, 'Thunderbird', 'Animal Feeds', 123, 321, '2024-10-20', 'Expired'),
(18, 'Thunderbird', 'Animal Feeds', 1, 321, '2024-10-20', 'Sold'),
(21, 'Thunderbird', 'Animal Feeds', 10, 45, '2024-10-20', 'Removed'),
(22, 'Enerton', 'Animal Feeds', 10, 50, '2024-10-20', 'Removed'),
(23, 'Target', 'Pesticide', 11, 250, '2024-10-20', 'Sold'),
(24, 'Target', 'Pesticide', 11, 250, '2024-10-20', 'Sold'),
(25, 'Target', 'Pesticide', 15, 250, '2024-10-23', 'Sold'),
(26, 'Target', 'Pesticide', 21, 250, '2024-10-23', 'Sold'),
(27, 'Dog Food', 'Animal Feeds', 10, 150, '2024-10-27', 'Removed'),
(28, 'BMeg', 'Animal Feeds', 10, 100, '2024-10-27', 'Expired'),
(29, 'Okra', 'Seeds', 15, 55, '2024-10-27', 'Expired'),
(30, 'Cat Food', 'Animal Feeds', 10, 100, '2024-10-27', 'Removed'),
(31, 'Okra', 'Seeds', 15, 55, '2024-10-27', 'Removed'),
(32, 'Pigrolac', 'Animal Feeds', 15, 60, '2024-10-27', 'Removed'),
(33, 'Eggplant', 'Seeds', 21, 20, '2024-10-27', 'Sold'),
(34, 'Eggplant', 'Seeds', 10, 20, '2024-10-27', 'Sold'),
(35, 'Eggplant', 'Seeds', 14, 20, '2024-10-27', 'Sold'),
(36, 'Target', 'Pesticide', 1, 250, '2024-10-27', 'Sold'),
(37, 'Target', 'Pesticide', 1, 250, '2024-10-27', 'Sold'),
(38, 'Target', 'Pesticide', 10, 250, '2024-10-27', 'Sold'),
(39, 'Target', 'Pesticide', 14, 250, '2024-10-27', 'Sold'),
(40, 'Urea', 'Fertilizer', 10, 300, '2024-10-29', 'Restocked');

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `ID` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Age` varchar(200) NOT NULL,
  `Address` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`ID`, `Name`, `Age`, `Address`) VALUES
(4, '123', '123', '123'),
(5, '13', '', '123');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `ID` int(11) NOT NULL,
  `Category` varchar(100) NOT NULL,
  `Name` varchar(250) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Stock` int(11) NOT NULL,
  `Price` float NOT NULL,
  `ExpirationDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`ID`, `Category`, `Name`, `Status`, `Stock`, `Price`, `ExpirationDate`) VALUES
(16, 'Pesticide', 'Target', '', 1110, 250, '2025-01-20'),
(17, 'Fertilizer', 'Urea', '', 110, 300, '2025-05-20'),
(20, 'Pesticide', 'Noemi', '', 5, 350, '2024-11-06'),
(27, 'Seeds', 'Okra', '', 10, 55, '2024-11-07'),
(29, 'Animal Feeds', 'Propel', '', 10, 150, '2024-11-07'),
(31, 'Animal Feeds', 'Jet Star', '', 70, 100, '2024-11-09'),
(34, 'Pesticide', 'Target', '', 9, 250, '2024-11-08'),
(37, 'Animal Feeds', 'Enerton', '', 10, 200, '2024-11-08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Username` varchar(10) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Username`, `Email`, `Password`) VALUES
(1, 'Jorge', 'gmail', '$2y$10$hE9qgJ8UOAC44K5TT7FaYeAEC6Dxn6sEKDC7m72GsRs9eZ01LYYIe'),
(2, 'Allen', 'email', '$2y$10$7oXWIMHf8ea/HuP9WJFNNufPQ1STpBTcIwIGBBJinMarsjpbqlFDi'),
(3, 'Marco', 'asda@gmail.com', '$2y$10$GFQq10L3UvqepTkK.J1.R.Q.pqholTVDaFDPLdnhunnyEO5R.5KMK'),
(4, 'Rafael ', '123@gmail.com', '$2y$10$sEStdMriblUT7141yx3TqOPC0lBe17YhVLKg9j2BbcISQSzIwt1U2'),
(5, 'Gab', 'quaileggplus@gmail.com', '$2y$10$hqLBxdkSDdseZsUvKb3KqOcroR6cb68cyQHpfOfp/w2xfiRwXy0zi'),
(6, 'Argel', 'nigga@gmail.com', '$2y$10$7g0kRzIQsHW7iLuG7ZEqSuserGWecSDBpxyuOEFsUsJEI1PYAg9G6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
