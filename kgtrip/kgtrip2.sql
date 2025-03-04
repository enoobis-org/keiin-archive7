-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 04, 2025 at 05:21 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kgtrip`
--

-- --------------------------------------------------------

--
-- Table structure for table `icountries`
--

DROP TABLE IF EXISTS `icountries`;
CREATE TABLE IF NOT EXISTS `icountries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  `img1` text COLLATE utf8mb4_general_ci NOT NULL,
  `img2` text COLLATE utf8mb4_general_ci NOT NULL,
  `img3` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `icountries`
--

INSERT INTO `icountries` (`id`, `name`, `img1`, `img2`, `img3`) VALUES
(1, 'Кыргызстан', 'assets\\files\\kg1.png', 'assets\\files\\kg2.png', 'assets\\files\\kg3.png'),
(2, 'Казахстан', 'assets\\files\\kz1.png', 'assets\\files\\kz2.png', 'assets\\files\\kz3.png'),
(3, 'Узбекистан', 'assets\\files\\uz1.png', 'assets\\files\\uz2.png', 'assets\\files\\uz3.png'),
(4, 'Туркменистан', 'assets\\files\\tm1.png', 'assets\\files\\tm2.png', 'assets\\files\\tm3.png');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `img` text COLLATE utf8mb4_general_ci NOT NULL,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `img`, `name`) VALUES
(1, 'assets\\files\\burana.jpg', 'Бурана'),
(2, 'assets\\files\\issikkyl.jpg', 'Иссык-Куль'),
(3, 'assets\\files\\ala-kyl.jpg', 'Ала-Куль');

-- --------------------------------------------------------

--
-- Table structure for table `ncountries`
--

DROP TABLE IF EXISTS `ncountries`;
CREATE TABLE IF NOT EXISTS `ncountries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ncountries`
--

INSERT INTO `ncountries` (`id`, `name`) VALUES
(1, 'Кыргызстан'),
(2, 'Казахстан'),
(3, 'Узбекистан'),
(4, 'Туркменистан');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
CREATE TABLE IF NOT EXISTS `packages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `img` text COLLATE utf8mb4_general_ci NOT NULL,
  `price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `img`, `price`) VALUES
(1, 'assets\\files\\ala-kyl2.jpg', 1200),
(2, 'assets\\files\\alaacha2.jpg', 2000),
(3, 'assets\\files\\sari-chelek2.jpg', 1500),
(4, 'assets\\files\\issikkyl2.jpeg', 3000),
(6, 'assets\\files\\uran-house.jpg', 51);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `img` text COLLATE utf8mb4_general_ci NOT NULL,
  `srv` text COLLATE utf8mb4_general_ci NOT NULL,
  `txt` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `img`, `srv`, `txt`) VALUES
(1, 'assets\\files\\1a.jpg', 'Услуги авиаперелетов', 'Прибытие и отправление'),
(2, 'assets\\files\\2a.jpg', 'Услуги питания 2', 'Кейтеринг'),
(3, 'assets\\files\\3a.jpg', 'Транспортные услуги', 'Автобусы/Маршрутки'),
(4, 'assets\\files\\4a.jpg', 'Отельные услуги', 'Регистрация заезда/выезда');

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

DROP TABLE IF EXISTS `slider`;
CREATE TABLE IF NOT EXISTS `slider` (
  `id` int NOT NULL AUTO_INCREMENT,
  `img` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`id`, `img`) VALUES
(1, 'assets/files/tash-rabat.jpg'),
(2, 'assets/files/sari-chelek.jpg'),
(3, 'assets/files/burana.jpg'),
(4, 'assets/files/dgeti-oguz.jpg'),
(5, 'assets/files/ala-kyl.jpg'),
(6, 'assets/files/alaacha.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` text COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `password`) VALUES
(1, 'admin', '12345');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
