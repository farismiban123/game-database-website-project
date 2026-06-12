-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2026 at 05:21 AM
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
-- Database: `game_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `completed_quest`
--

CREATE TABLE `completed_quest` (
  `completed_quest_id` int(11) NOT NULL,
  `quest_id` int(11) DEFAULT NULL,
  `player_id` int(11) DEFAULT NULL,
  `date_completed` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `completed_quest`
--

INSERT INTO `completed_quest` (`completed_quest_id`, `quest_id`, `player_id`, `date_completed`) VALUES
(4, 1, 17, '2026-06-08'),
(5, 1, 18, '2026-06-08'),
(6, 1, 19, '2026-06-12');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `player_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `item_id`, `player_id`) VALUES
(8, 7, 17),
(11, 7, 19),
(12, 9, 17);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_description` text NOT NULL,
  `item_category` varchar(100) NOT NULL,
  `item_rarity` varchar(50) NOT NULL DEFAULT 'Common'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_description`, `item_category`, `item_rarity`) VALUES
(2, 'Iron Sword', 'A magnificent iron sword', 'Weapon', 'Common'),
(7, 'Dragon Staff', 'aetsaetse', 'Staff', 'Legendary'),
(8, 'Iron Chestplate', 'Just some basic iron chestplate.', 'Armor', 'Uncommon'),
(9, 'Healing Potion', 'Just a small healing potion', 'Potion', 'Common');

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE `player` (
  `player_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `player_level` int(11) NOT NULL,
  `date_joined` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`player_id`, `username`, `player_level`, `date_joined`) VALUES
(17, 'rangga', 9999, '2026-06-08'),
(18, 'bob', 8, '2026-06-08'),
(19, 'baswedan', 10, '2026-06-12');

-- --------------------------------------------------------

--
-- Table structure for table `quest`
--

CREATE TABLE `quest` (
  `quest_id` int(11) NOT NULL,
  `quest_name` varchar(100) NOT NULL,
  `quest_description` text DEFAULT NULL,
  `quest_reward` varchar(100) DEFAULT NULL,
  `quest_difficulty` varchar(50) DEFAULT 'Easy'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quest`
--

INSERT INTO `quest` (`quest_id`, `quest_name`, `quest_description`, `quest_reward`, `quest_difficulty`) VALUES
(1, 'Slaying the Dragon', 'Defeat the evil and monstruous dragon and reap the rewards!asdadasdasdasdasd', '100000 gold', 'Easy'),
(2, 'Save the Princess', 'We have to save the princess before she falls of the tower!', 'Increased Relationship', 'Medium');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `completed_quest`
--
ALTER TABLE `completed_quest`
  ADD PRIMARY KEY (`completed_quest_id`),
  ADD KEY `quest_id` (`quest_id`),
  ADD KEY `player_id` (`player_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `player_id` (`player_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`player_id`);

--
-- Indexes for table `quest`
--
ALTER TABLE `quest`
  ADD PRIMARY KEY (`quest_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `completed_quest`
--
ALTER TABLE `completed_quest`
  MODIFY `completed_quest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `player`
--
ALTER TABLE `player`
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `quest`
--
ALTER TABLE `quest`
  MODIFY `quest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `completed_quest`
--
ALTER TABLE `completed_quest`
  ADD CONSTRAINT `completed_quest_ibfk_1` FOREIGN KEY (`quest_id`) REFERENCES `quest` (`quest_id`),
  ADD CONSTRAINT `completed_quest_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `player` (`player_id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `player` (`player_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
