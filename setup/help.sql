-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 22, 2019 at 05:17 AM
-- Server version: 5.5.25a
-- PHP Version: 5.6.40

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ipos2`
--

-- --------------------------------------------------------

--
-- Table structure for table `help`
--

DROP TABLE IF EXISTS `help`;
CREATE TABLE IF NOT EXISTS `help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `description` text,
  `path` text,
  `enabled` tinyint(1) DEFAULT '0',
  `inactive` int(11) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=6 ;

--
-- Dumping data for table `help`
--

INSERT INTO `help` (`id`, `name`, `description`, `path`, `enabled`, `inactive`) VALUES
(1, 'Introduction to Sales Cashier ', 'Introduction to Sales Cashier ', 'help/introduction to sales cashier .mp4', 0, 0),
(2, 'Apply discount', 'Apply discount', 'help/Apply discount.mp4', 1, 0),
(3, 'How to cancel and void transactions', 'How to cancel and void transactions', 'help/how to cancelled and void transactions.mp4', 1, 0),
(4, 'Apply Additional Charges', 'apply additional charges', 'help/apply additional charges.mp4', 1, 0),
(5, 'How to start shift.mp4', 'start shift', 'help/start shift.mp4', 1, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
