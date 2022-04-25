-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 22, 2018 at 09:46 AM
-- Server version: 5.6.40
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pointone_ipos_hq`
--

-- --------------------------------------------------------

--
-- Table structure for table `araneta`
--

DROP TABLE IF EXISTS `araneta`;
CREATE TABLE `araneta` (
  `id` int(11) NOT NULL,
  `lessee_name` varchar(20) DEFAULT NULL,
  `lessee_no` varchar(20) DEFAULT NULL,
  `space_code` varchar(20) DEFAULT '',
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `ayala`
--

DROP TABLE IF EXISTS `ayala`;
CREATE TABLE `ayala` (
  `id` int(11) NOT NULL,
  `contract_no` varchar(150) DEFAULT NULL,
  `store_name` varchar(150) DEFAULT NULL,
  `xxx_no` varchar(150) DEFAULT NULL,
  `dbf_tenant_name` varchar(150) DEFAULT NULL,
  `dbf_path` varchar(150) DEFAULT NULL,
  `text_file_path` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `batch_etl`
--

DROP TABLE IF EXISTS `batch_etl`;
CREATE TABLE `batch_etl` (
  `id` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `branch_details`
--

DROP TABLE IF EXISTS `branch_details`;
CREATE TABLE `branch_details` (
  `branch_id` int(11) NOT NULL,
  `res_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_name` varchar(55) DEFAULT NULL,
  `branch_desc` varchar(150) DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `delivery_no` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `base_location` varchar(100) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT '0',
  `tin` varchar(255) DEFAULT NULL,
  `machine_no` varchar(255) DEFAULT NULL,
  `bir` varchar(255) DEFAULT NULL,
  `permit_no` varchar(255) DEFAULT NULL,
  `serial` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `store_open` time DEFAULT NULL,
  `store_close` time DEFAULT NULL,
  `rob_tenant_code` varchar(150) DEFAULT NULL,
  `rob_path` varchar(150) DEFAULT NULL,
  `rob_username` varchar(150) DEFAULT NULL,
  `rob_password` varchar(150) DEFAULT NULL,
  `accrdn` varchar(150) DEFAULT NULL,
  `rec_footer` varchar(255) DEFAULT NULL,
  `pos_footer` varchar(255) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `branch_menus`
--

DROP TABLE IF EXISTS `branch_menus`;
CREATE TABLE `branch_menus` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `menu_code` varchar(15) DEFAULT NULL,
  `menu_name` varchar(25) DEFAULT NULL,
  `reg_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `cashout_details`
--

DROP TABLE IF EXISTS `cashout_details`;
CREATE TABLE `cashout_details` (
  `id` int(11) NOT NULL,
  `cashout_detail_id` int(11) DEFAULT NULL,
  `cashout_id` int(11) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `denomination` varchar(150) DEFAULT '0',
  `reference` varchar(150) DEFAULT NULL,
  `total` double DEFAULT '0',
  `pos_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `cashout_entries`
--

DROP TABLE IF EXISTS `cashout_entries`;
CREATE TABLE `cashout_entries` (
  `id` int(11) NOT NULL,
  `cashout_id` int(11) NOT NULL,
  `user_id` varchar(45) NOT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `drawer_amount` varchar(255) DEFAULT NULL,
  `count_amount` double DEFAULT NULL,
  `trans_date` datetime NOT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `sysid` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `charges`
--

DROP TABLE IF EXISTS `charges`;
CREATE TABLE `charges` (
  `charge_id` int(11) NOT NULL,
  `charge_code` varchar(22) DEFAULT NULL,
  `charge_name` varchar(55) DEFAULT NULL,
  `charge_amount` double DEFAULT NULL,
  `absolute` tinyint(1) DEFAULT '0',
  `no_tax` tinyint(1) DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `charges`
--

INSERT INTO `charges` (`charge_id`, `charge_code`, `charge_name`, `charge_amount`, `absolute`, `no_tax`, `inactive`) VALUES
(1, 'SCHG', 'Service Charge', 10, 0, 1, 0),
(2, 'DCHG', 'Delivery Charge', 5, 0, 1, 0),
(3, 'HFHG', 'Handling Fee', 10, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('1c351fcc7bac4482db3786f25b0a2375', '::1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36', 1534726389, 'a:2:{s:9:\"user_data\";s:0:\"\";s:11:\"site_alerts\";a:1:{i:0;a:2:{s:4:\"text\";s:41:\"You need to start a shift before selling.\";s:4:\"type\";s:5:\"error\";}}}'),
('ab7d574fc10cb42b413c1b1cf7afdee6', '::1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36', 1534726389, 'a:2:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"fname\";s:3:\"Rey\";s:5:\"lname\";s:6:\"Tejada\";s:5:\"mname\";s:6:\"Coloma\";s:6:\"suffix\";s:3:\"Jr.\";s:9:\"full_name\";s:21:\"Rey Coloma Tejada Jr.\";s:7:\"role_id\";s:1:\"1\";s:4:\"role\";s:14:\"Administrator \";s:6:\"access\";s:3:\"all\";s:3:\"img\";s:39:\"http://localhost/ipos_hq/img/avatar.jpg\";}}');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(55) DEFAULT NULL,
  `contact_no` varchar(55) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `tin` varchar(100) DEFAULT NULL,
  `fiscal_year` int(11) DEFAULT NULL,
  `theme` varchar(55) DEFAULT 'blue'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
CREATE TABLE `conversations` (
  `con_id` int(11) NOT NULL,
  `user_a` int(11) DEFAULT NULL,
  `user_b` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`con_id`, `user_a`, `user_b`, `datetime`, `inactive`) VALUES
(1, 1, 2, '2015-05-06 10:57:25', 0),
(3, 1, 3, '2015-05-06 12:28:55', 0);

-- --------------------------------------------------------

--
-- Table structure for table `conversation_messages`
--

DROP TABLE IF EXISTS `conversation_messages`;
CREATE TABLE `conversation_messages` (
  `con_msg_id` int(11) NOT NULL,
  `con_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `msg` longtext,
  `file` longblob,
  `datetime` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conversation_messages`
--

INSERT INTO `conversation_messages` (`con_msg_id`, `con_id`, `user_id`, `msg`, `file`, `datetime`, `inactive`) VALUES
(1, 1, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus dictum sapien eget nunc viverra, nec consequat lectus hendrerit. Etiam vel ante gravida, pellentesque ex nec, pretium neque. Pellentesque finibus purus diam, ac condimentum augue fermentum et. Ut neque nisi, hendrerit id laoreet fermentum, condimentum at dolor. Pellentesque aliquet tellus quis ullamcorper maximus. Donec finibus lectus sem, id pulvinar lectus pulvinar ut. ', NULL, '2015-05-06 10:57:25', 0),
(3, 3, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus dictum sapien eget nunc viverra, nec consequat lectus hendrerit. Etiam vel ante gravida, pellentesque ex nec, pretium neque. Pellentesque finibus purus diam, ac condimentum augue fermentum et. Ut neque nisi, hendrerit id laoreet fermentum, condimentum at dolor. Pellentesque aliquet tellus quis ullamcorper maximus. Donec finibus lectus sem, id pulvinar lectus pul', NULL, '2015-05-06 12:28:55', 0),
(4, 3, 1, 'tristique, odio id scelerisque sollicitudin, diam massa lobortis enim, in faucibus nisi leo at dui. Proin ornare eleifend risus, ut condimentum metus porttitor non. Donec', NULL, '2015-05-06 12:34:46', 0),
(5, 1, 1, 'asdas asd ', NULL, '2015-05-06 12:40:24', 0),
(6, 1, 1, 'asda dsa asd asd ', NULL, '2015-05-06 12:47:25', 0),
(7, 3, 1, ' asd asd asd ', NULL, '2015-05-06 12:47:58', 0),
(8, 3, 1, ' asd  asd asd asd asd ', NULL, '2015-05-06 12:48:08', 0),
(9, 3, 1, ' asd asd ', NULL, '2015-05-06 12:48:41', 0),
(10, 3, 1, ' asd asd ', NULL, '2015-05-06 12:49:17', 0),
(11, 3, 1, ' asd asd  asd asd ', NULL, '2015-05-06 12:49:25', 0),
(12, 3, 1, ' asd asd  asd asd  asd ', NULL, '2015-05-06 12:49:38', 0),
(13, 3, 1, 'asd  sa s a', NULL, '2015-05-06 12:49:45', 0),
(14, 3, 1, ' asd asd ', NULL, '2015-05-06 12:50:16', 0),
(15, 3, 1, 'asd asd asd ', NULL, '2015-05-06 12:50:54', 0),
(16, 3, 1, 'asd asd asd ', NULL, '2015-05-06 12:52:55', 0),
(17, 3, 3, 'asd asd a dsa asd ', NULL, '2015-05-06 12:53:10', 0),
(18, 3, 1, ' asd asd asd ', NULL, '2015-05-06 12:53:41', 0),
(19, 3, 3, 'da sda sd asd ', NULL, '2015-05-06 12:54:41', 0),
(20, 3, 1, 'asd asd asd 1 123 123 asd asd ', NULL, '2015-05-06 12:55:39', 0),
(21, 3, 1, '12 asd asd asd asd ', NULL, '2015-05-06 12:56:41', 0),
(22, 3, 1, 'asd asd asd asd ', NULL, '2015-05-06 13:07:57', 0),
(23, 3, 1, 'asd asd asd asd ', NULL, '2015-05-06 13:07:58', 0),
(24, 3, 1, 'asd asd asd ', NULL, '2015-05-06 13:13:55', 0),
(25, 3, 1, 'sd asd asd ', NULL, '2015-05-06 13:14:13', 0),
(26, 3, 1, 'asd asd asd  asd ', NULL, '2015-05-06 13:14:31', 0),
(27, 3, 1, 'sad asd asd 1  asd asd ', NULL, '2015-05-06 13:14:48', 0),
(28, 1, 1, 'a sd asd asd ', NULL, '2015-05-06 13:23:07', 0),
(29, 1, 1, 'sdas  asd asd asd ', NULL, '2015-05-06 13:23:11', 0),
(30, 1, 1, ' asd 213 sd asd ', NULL, '2015-05-06 13:23:16', 0),
(31, 3, 1, ' asd 12 asd asd ', NULL, '2015-05-06 13:23:20', 0),
(32, 3, 1, ' 3 qwe asd asd 13 123 ', NULL, '2015-05-06 13:23:25', 0),
(33, 3, 1, ' asd asd 123 123 123 ', NULL, '2015-05-06 13:23:35', 0),
(34, 1, 1, ' 123 12 3asd asd 123 ', NULL, '2015-05-06 13:24:05', 0),
(35, 3, 1, '123 123 asd as 123 ', NULL, '2015-05-06 13:24:09', 0),
(36, 3, 1, '13 12 asd 12 3123 asd ', NULL, '2015-05-06 13:25:35', 0),
(37, 1, 1, ' 123 123 ad 123 12 3asd ', NULL, '2015-05-06 13:25:56', 0);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `coupon_id` int(10) NOT NULL,
  `card_no` varchar(100) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `expiration` date DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `currency` varchar(22) DEFAULT NULL,
  `currency_desc` varchar(55) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currency`, `currency_desc`, `inactive`) VALUES
(1, 'PHP', 'Philippine Peso', 0),
(2, 'USD', 'US Dollars', 0),
(3, 'YEN', 'Japanese Yen', 0);

-- --------------------------------------------------------

--
-- Table structure for table `currency_details`
--

DROP TABLE IF EXISTS `currency_details`;
CREATE TABLE `currency_details` (
  `id` int(11) NOT NULL,
  `currency_id` varchar(45) NOT NULL,
  `desc` varchar(60) NOT NULL,
  `value` double NOT NULL,
  `img` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `cust_id` int(11) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `fname` varchar(55) DEFAULT NULL,
  `mname` varchar(55) DEFAULT NULL,
  `lname` varchar(55) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `tax_exempt` tinyint(1) DEFAULT NULL,
  `street_no` varchar(55) DEFAULT NULL,
  `street_address` varchar(55) DEFAULT NULL,
  `city` varchar(55) DEFAULT NULL,
  `region` varchar(55) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  `reg_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_bank`
--

DROP TABLE IF EXISTS `customers_bank`;
CREATE TABLE `customers_bank` (
  `bank_id` int(11) NOT NULL,
  `payment` tinyint(4) DEFAULT '0',
  `cust_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `amount_type` varchar(11) DEFAULT NULL,
  `card_no` varchar(50) DEFAULT NULL,
  `card_type` varchar(50) DEFAULT NULL,
  `approval_code` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(40) DEFAULT NULL,
  `remarks` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `customer_address`
--

DROP TABLE IF EXISTS `customer_address`;
CREATE TABLE `customer_address` (
  `id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `street_no` varchar(55) NOT NULL,
  `street_address` varchar(55) NOT NULL,
  `city` varchar(55) NOT NULL,
  `region` varchar(55) NOT NULL,
  `zip` varchar(55) NOT NULL,
  `base_location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `denominations`
--

DROP TABLE IF EXISTS `denominations`;
CREATE TABLE `denominations` (
  `id` int(11) NOT NULL,
  `desc` varchar(60) NOT NULL,
  `value` double NOT NULL,
  `img` longblob
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `denominations`
--

INSERT INTO `denominations` (`id`, `desc`, `value`, `img`) VALUES
(1, 'One Thousand', 1000, NULL),
(2, 'Five Hundreds', 500, NULL),
(3, 'Two Hundreds', 200, NULL),
(4, 'One Hundreds', 100, NULL),
(5, 'Fifty', 50, NULL),
(6, 'Twenty', 20, NULL),
(7, 'Ten', 10, NULL),
(8, 'Five', 5, NULL),
(9, 'One', 1, NULL),
(10, 'Twenty Five Cents', 0.25, NULL),
(11, 'Ten Cents', 0.1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dtr_scheduler`
--

DROP TABLE IF EXISTS `dtr_scheduler`;
CREATE TABLE `dtr_scheduler` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `dtr_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dtr_shifts`
--

DROP TABLE IF EXISTS `dtr_shifts`;
CREATE TABLE `dtr_shifts` (
  `id` int(10) NOT NULL,
  `code` varchar(35) NOT NULL DEFAULT '',
  `description` varchar(50) DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `break_out` time DEFAULT NULL,
  `break_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `break_hours` double DEFAULT NULL,
  `work_hours` double DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `grace_period` time DEFAULT NULL,
  `timein_grace_period` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `eton`
--

DROP TABLE IF EXISTS `eton`;
CREATE TABLE `eton` (
  `id` int(11) NOT NULL,
  `tenant_code` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gift_cards`
--

DROP TABLE IF EXISTS `gift_cards`;
CREATE TABLE `gift_cards` (
  `gc_id` int(10) NOT NULL,
  `card_no` varchar(100) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `img_id` int(11) NOT NULL,
  `img_file_name` longtext,
  `img_path` longtext,
  `img_ref_id` int(11) DEFAULT NULL,
  `img_tbl` varchar(50) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `sysid` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `cat_id` int(11) NOT NULL,
  `subcat_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `uom` varchar(22) NOT NULL,
  `cost` double NOT NULL DEFAULT '0',
  `type` int(11) DEFAULT '1',
  `no_per_pack` double DEFAULT '0',
  `no_per_pack_uom` varchar(50) DEFAULT NULL,
  `no_per_case` double(255,0) DEFAULT '0',
  `reorder_qty` double DEFAULT '0',
  `max_qty` double DEFAULT '0',
  `memo` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `inactive` int(11) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_moves`
--

DROP TABLE IF EXISTS `item_moves`;
CREATE TABLE `item_moves` (
  `move_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `trans_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(55) DEFAULT NULL,
  `loc_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `uom` varchar(10) DEFAULT NULL,
  `case_qty` double DEFAULT NULL,
  `pack_qty` double DEFAULT NULL,
  `curr_item_qty` double DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `inactive` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_serials`
--

DROP TABLE IF EXISTS `item_serials`;
CREATE TABLE `item_serials` (
  `id` int(11) NOT NULL,
  `item_code` varchar(255) NOT NULL DEFAULT '',
  `serial_no` varchar(255) NOT NULL DEFAULT '',
  `trans_date` date DEFAULT NULL,
  `batch_no` varchar(255) DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `is_used` tinyint(4) DEFAULT '0',
  `person_id` int(255) DEFAULT '0',
  `reference` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_types`
--

DROP TABLE IF EXISTS `item_types`;
CREATE TABLE `item_types` (
  `id` int(11) NOT NULL,
  `type` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_types`
--

INSERT INTO `item_types` (`id`, `type`) VALUES
(1, 'Not For Resale'),
(2, 'For Resale');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `loc_id` int(11) NOT NULL,
  `loc_code` varchar(22) DEFAULT NULL,
  `loc_name` varchar(55) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `terminal_id` varchar(120) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` longtext,
  `reference` longtext,
  `type` varchar(11) DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action`, `reference`, `type`, `pos_id`, `datetime`, `sync_id`) VALUES
(1, 1, 'Rey Coloma Tejada Jr. Logged In.', NULL, 'login', NULL, '2018-08-20 08:53:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_cards`
--

DROP TABLE IF EXISTS `loyalty_cards`;
CREATE TABLE `loyalty_cards` (
  `card_id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `points` double(10,0) DEFAULT '0',
  `reg_user_id` int(11) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `master_logs`
--

DROP TABLE IF EXISTS `master_logs`;
CREATE TABLE `master_logs` (
  `master_id` int(11) NOT NULL,
  `terminal_id` varchar(250) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `src_id` text,
  `transaction` varchar(250) DEFAULT NULL,
  `type` varchar(250) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_automated` tinyint(1) DEFAULT '0',
  `migrate_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `record_count` int(11) DEFAULT NULL,
  `sender_ip_address` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `megamall`
--

DROP TABLE IF EXISTS `megamall`;
CREATE TABLE `megamall` (
  `id` int(11) NOT NULL,
  `br_code` varchar(20) DEFAULT NULL,
  `tenant_no` varchar(20) DEFAULT NULL,
  `class_code` varchar(20) DEFAULT '',
  `trade_code` varchar(20) DEFAULT NULL,
  `outlet_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `sysid` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `branch_code` varchar(120) NOT NULL,
  `terminal_id` varchar(120) NOT NULL,
  `menu_code` varchar(100) DEFAULT NULL,
  `menu_barcode` varchar(255) DEFAULT NULL,
  `menu_name` varchar(255) DEFAULT NULL,
  `menu_short_desc` varchar(255) DEFAULT NULL,
  `menu_cat_id` int(11) NOT NULL,
  `menu_sub_cat_id` int(11) DEFAULT NULL,
  `menu_sched_id` int(11) DEFAULT '0',
  `cost` double DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `no_tax` int(1) DEFAULT '0',
  `free` int(1) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `costing` double DEFAULT '0',
  `menu_sub_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

DROP TABLE IF EXISTS `menu_categories`;
CREATE TABLE `menu_categories` (
  `sysid` int(11) NOT NULL,
  `menu_cat_id` int(11) NOT NULL,
  `menu_cat_name` varchar(150) NOT NULL,
  `menu_sched_id` int(11) DEFAULT NULL,
  `branch_code` varchar(120) DEFAULT NULL,
  `terminal_id` varchar(120) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  `arrangement` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_modifiers`
--

DROP TABLE IF EXISTS `menu_modifiers`;
CREATE TABLE `menu_modifiers` (
  `sysid` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `mod_group_id` int(11) NOT NULL,
  `branch_code` varchar(120) DEFAULT NULL,
  `terminal_id` varchar(120) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_moves`
--

DROP TABLE IF EXISTS `menu_moves`;
CREATE TABLE `menu_moves` (
  `move_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `trans_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(55) DEFAULT NULL,
  `loc_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `uom` varchar(10) DEFAULT NULL,
  `case_qty` double DEFAULT NULL,
  `pack_qty` double DEFAULT NULL,
  `curr_item_qty` double DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_recipe`
--

DROP TABLE IF EXISTS `menu_recipe`;
CREATE TABLE `menu_recipe` (
  `sysid` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `uom` varchar(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` double NOT NULL,
  `branch_code` varchar(120) DEFAULT NULL,
  `terminal_id` varchar(120) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_schedules`
--

DROP TABLE IF EXISTS `menu_schedules`;
CREATE TABLE `menu_schedules` (
  `sysid` int(11) NOT NULL,
  `menu_sched_id` int(11) NOT NULL,
  `desc` varchar(150) NOT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `branch_code` varchar(120) DEFAULT NULL,
  `terminal_id` varchar(120) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_schedule_details`
--

DROP TABLE IF EXISTS `menu_schedule_details`;
CREATE TABLE `menu_schedule_details` (
  `id` int(11) NOT NULL,
  `menu_sched_id` int(11) NOT NULL,
  `day` varchar(22) NOT NULL,
  `time_on` time NOT NULL,
  `time_off` time NOT NULL,
  `branch_code` varchar(120) DEFAULT NULL,
  `terminal_id` varchar(120) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_subcategories`
--

DROP TABLE IF EXISTS `menu_subcategories`;
CREATE TABLE `menu_subcategories` (
  `sysid` int(11) NOT NULL,
  `menu_sub_cat_id` int(11) NOT NULL,
  `menu_sub_cat_name` varchar(150) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `branch_code` varchar(120) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_subcategory`
--

DROP TABLE IF EXISTS `menu_subcategory`;
CREATE TABLE `menu_subcategory` (
  `sysid` int(11) NOT NULL,
  `menu_sub_id` int(11) NOT NULL,
  `menu_sub_name` varchar(150) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `category_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `branch_code` varchar(120) DEFAULT NULL,
  `terminal_id` varchar(120) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `modifiers`
--

DROP TABLE IF EXISTS `modifiers`;
CREATE TABLE `modifiers` (
  `sysid` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `cost` double(11,0) DEFAULT '0',
  `has_recipe` int(1) DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `inactive` int(1) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `modifier_groups`
--

DROP TABLE IF EXISTS `modifier_groups`;
CREATE TABLE `modifier_groups` (
  `sysid` int(11) NOT NULL,
  `mod_group_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `mandatory` int(1) DEFAULT '0',
  `multiple` int(10) DEFAULT '0',
  `terminal_id` varchar(120) DEFAULT NULL,
  `branch_code` varchar(120) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `modifier_group_details`
--

DROP TABLE IF EXISTS `modifier_group_details`;
CREATE TABLE `modifier_group_details` (
  `sysid` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `mod_group_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `terminal_id` varchar(120) DEFAULT NULL,
  `branch_code` varchar(120) DEFAULT NULL,
  `default` tinyint(1) DEFAULT '0',
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `modifier_recipe`
--

DROP TABLE IF EXISTS `modifier_recipe`;
CREATE TABLE `modifier_recipe` (
  `sysid` int(11) NOT NULL,
  `mod_recipe_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `uom` varchar(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` double NOT NULL,
  `branch_code` varchar(120) DEFAULT NULL,
  `terminal_id` varchar(120) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ortigas`
--

DROP TABLE IF EXISTS `ortigas`;
CREATE TABLE `ortigas` (
  `id` int(11) NOT NULL,
  `tenant_code` varchar(10) DEFAULT NULL,
  `sales_type` varchar(5) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `ortigas_read_details`
--

DROP TABLE IF EXISTS `ortigas_read_details`;
CREATE TABLE `ortigas_read_details` (
  `id` int(11) NOT NULL,
  `zread_id` int(11) DEFAULT NULL,
  `read_date` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `old_total` double DEFAULT NULL,
  `grand_total` double DEFAULT NULL COMMENT 'GT for ZRead only',
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `scope_from` datetime DEFAULT NULL,
  `scope_to` datetime DEFAULT NULL,
  `no_tax` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `promo_discounts`
--

DROP TABLE IF EXISTS `promo_discounts`;
CREATE TABLE `promo_discounts` (
  `promo_id` int(11) NOT NULL,
  `promo_code` varchar(22) DEFAULT NULL,
  `promo_name` varchar(55) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `absolute` tinyint(4) DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `promo_discount_items`
--

DROP TABLE IF EXISTS `promo_discount_items`;
CREATE TABLE `promo_discount_items` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `promo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `promo_discount_schedule`
--

DROP TABLE IF EXISTS `promo_discount_schedule`;
CREATE TABLE `promo_discount_schedule` (
  `id` int(11) NOT NULL,
  `promo_id` int(11) NOT NULL,
  `day` varchar(22) NOT NULL,
  `time_on` time NOT NULL,
  `time_off` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `promo_free`
--

DROP TABLE IF EXISTS `promo_free`;
CREATE TABLE `promo_free` (
  `pf_id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `has_menu_id` varchar(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sched_id` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `promo_free_menus`
--

DROP TABLE IF EXISTS `promo_free_menus`;
CREATE TABLE `promo_free_menus` (
  `pf_menu_id` int(11) NOT NULL,
  `pf_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `read_details`
--

DROP TABLE IF EXISTS `read_details`;
CREATE TABLE `read_details` (
  `id` int(11) NOT NULL,
  `read_id` int(11) DEFAULT NULL,
  `read_type` tinyint(2) NOT NULL,
  `read_date` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `old_total` double DEFAULT NULL,
  `grand_total` double DEFAULT NULL COMMENT 'GT for ZRead only',
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `scope_from` datetime DEFAULT NULL,
  `scope_to` datetime DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `ctr` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reasons`
--

DROP TABLE IF EXISTS `reasons`;
CREATE TABLE `reasons` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `ref_name` varchar(150) DEFAULT NULL,
  `reason` longtext,
  `trans_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `pos_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_discounts`
--

DROP TABLE IF EXISTS `receipt_discounts`;
CREATE TABLE `receipt_discounts` (
  `disc_id` int(11) NOT NULL,
  `disc_code` varchar(22) DEFAULT NULL,
  `disc_name` varchar(100) DEFAULT NULL,
  `disc_rate` double DEFAULT NULL,
  `no_tax` int(1) DEFAULT '0',
  `fix` int(1) DEFAULT '0',
  `inactive` int(1) DEFAULT '0',
  `branch_code` varchar(250) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_branch_tables`
--

DROP TABLE IF EXISTS `restaurant_branch_tables`;
CREATE TABLE `restaurant_branch_tables` (
  `tbl_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `top` int(11) DEFAULT '0',
  `left` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `rob_files`
--

DROP TABLE IF EXISTS `rob_files`;
CREATE TABLE `rob_files` (
  `id` int(11) NOT NULL,
  `code` varchar(55) DEFAULT NULL,
  `file` varchar(150) DEFAULT NULL,
  `print` double DEFAULT '0',
  `inactive` tinyint(4) DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `no_of_receipt_print` int(11) DEFAULT NULL,
  `no_of_order_slip_print` int(11) DEFAULT NULL,
  `controls` varchar(150) DEFAULT NULL,
  `local_tax` double(5,0) DEFAULT '0',
  `kitchen_printer_name` varchar(150) DEFAULT NULL,
  `kitchen_beverage_printer_name` varchar(150) DEFAULT NULL,
  `kitchen_printer_name_no` int(11) DEFAULT '0',
  `kitchen_beverage_printer_name_no` int(11) DEFAULT '0',
  `open_drawer_printer` varchar(150) DEFAULT NULL,
  `loyalty_for_amount` double DEFAULT NULL,
  `loyalty_to_points` double DEFAULT NULL,
  `backup_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `no_of_receipt_print`, `no_of_order_slip_print`, `controls`, `local_tax`, `kitchen_printer_name`, `kitchen_beverage_printer_name`, `kitchen_printer_name_no`, `kitchen_beverage_printer_name_no`, `open_drawer_printer`, `loyalty_for_amount`, `loyalty_to_points`, `backup_path`) VALUES
(1, 1, 0, '1=>dine in,2=>delivery,5=>pickup,6=>takeout,8=>food panda', 0, '', '', 1, 0, 'CASH DRAWER', 100, 10, 'D:/dine/backup');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
CREATE TABLE `shifts` (
  `id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime DEFAULT NULL,
  `xread_id` int(11) DEFAULT NULL,
  `cashout_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shift_entries`
--

DROP TABLE IF EXISTS `shift_entries`;
CREATE TABLE `shift_entries` (
  `id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `user_id` varchar(45) NOT NULL,
  `trans_date` datetime NOT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stalucia`
--

DROP TABLE IF EXISTS `stalucia`;
CREATE TABLE `stalucia` (
  `id` int(11) NOT NULL,
  `tenant_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
CREATE TABLE `subcategories` (
  `sysid` int(11) NOT NULL,
  `sub_cat_id` int(11) NOT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `inactive` varchar(255) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `supplier_code` varchar(255) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sync_logs`
--

DROP TABLE IF EXISTS `sync_logs`;
CREATE TABLE `sync_logs` (
  `sync_id` int(11) NOT NULL,
  `transaction` varchar(250) DEFAULT NULL,
  `type` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `migrate_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `src_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_automated` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sync_types`
--

DROP TABLE IF EXISTS `sync_types`;
CREATE TABLE `sync_types` (
  `sync_type_id` int(11) NOT NULL,
  `sync_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sync_types`
--

INSERT INTO `sync_types` (`sync_type_id`, `sync_type`) VALUES
(1, 'local to main'),
(2, 'main to local');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
CREATE TABLE `tables` (
  `tbl_id` int(11) NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `top` int(11) DEFAULT '0',
  `left` int(11) DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0',
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `terminal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tablesold`
--

DROP TABLE IF EXISTS `tablesold`;
CREATE TABLE `tablesold` (
  `tbl_id` int(11) NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `top` int(11) DEFAULT '0',
  `left` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `table_activity`
--

DROP TABLE IF EXISTS `table_activity`;
CREATE TABLE `table_activity` (
  `id` int(11) NOT NULL,
  `tbl_id` int(11) DEFAULT NULL,
  `pc_id` int(11) DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

DROP TABLE IF EXISTS `tax_rates`;
CREATE TABLE `tax_rates` (
  `tax_id` int(11) NOT NULL,
  `name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tax_rates`
--

INSERT INTO `tax_rates` (`tax_id`, `name`, `rate`, `inactive`) VALUES
(1, 'VAT', 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `terminals`
--

DROP TABLE IF EXISTS `terminals`;
CREATE TABLE `terminals` (
  `terminal_id` int(11) NOT NULL,
  `terminal_code` varchar(60) NOT NULL,
  `branch_code` varchar(55) DEFAULT NULL,
  `terminal_name` varchar(120) DEFAULT NULL,
  `ip` varchar(60) DEFAULT NULL,
  `comp_name` varchar(60) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT '0',
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `trans_adjustments`
--

DROP TABLE IF EXISTS `trans_adjustments`;
CREATE TABLE `trans_adjustments` (
  `adjustment_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_adjustment_details`
--

DROP TABLE IF EXISTS `trans_adjustment_details`;
CREATE TABLE `trans_adjustment_details` (
  `adjustment_detail_id` int(11) NOT NULL,
  `adjustment_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `case` int(1) DEFAULT '0',
  `pack` int(1) DEFAULT '0',
  `from_loc` int(11) DEFAULT NULL,
  `to_loc` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_receivings`
--

DROP TABLE IF EXISTS `trans_receivings`;
CREATE TABLE `trans_receivings` (
  `receiving_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_receiving_details`
--

DROP TABLE IF EXISTS `trans_receiving_details`;
CREATE TABLE `trans_receiving_details` (
  `receiving_detail_id` int(11) NOT NULL,
  `receiving_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `case` int(1) DEFAULT '0',
  `pack` int(1) DEFAULT '0',
  `price` double DEFAULT NULL,
  `uom` varchar(50) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_receiving_menu`
--

DROP TABLE IF EXISTS `trans_receiving_menu`;
CREATE TABLE `trans_receiving_menu` (
  `receiving_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `trans_date` datetime DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT '0',
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_receiving_menu_details`
--

DROP TABLE IF EXISTS `trans_receiving_menu_details`;
CREATE TABLE `trans_receiving_menu_details` (
  `receiving_detail_id` int(11) DEFAULT NULL,
  `receiving_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `uom` varchar(50) DEFAULT NULL,
  `case` int(1) DEFAULT '0',
  `pack` int(1) DEFAULT '0',
  `price` double DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_refs`
--

DROP TABLE IF EXISTS `trans_refs`;
CREATE TABLE `trans_refs` (
  `id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(55) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales`
--

DROP TABLE IF EXISTS `trans_sales`;
CREATE TABLE `trans_sales` (
  `id` int(11) NOT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `sales_id` int(11) NOT NULL,
  `mobile_sales_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(55) DEFAULT NULL,
  `void_ref` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `total_paid` double DEFAULT '0',
  `memo` varchar(255) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `guest` double(11,0) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `paid` int(1) DEFAULT '0',
  `reason` varchar(255) DEFAULT NULL,
  `void_user_id` int(11) DEFAULT NULL,
  `printed` tinyint(1) DEFAULT '0',
  `inactive` tinyint(4) DEFAULT '0',
  `waiter_id` int(11) DEFAULT NULL,
  `split` int(11) DEFAULT '0',
  `serve_no` int(11) DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `billed` int(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales_charges`
--

DROP TABLE IF EXISTS `trans_sales_charges`;
CREATE TABLE `trans_sales_charges` (
  `sales_charge_id` int(11) NOT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `charge_id` int(11) DEFAULT NULL,
  `charge_code` varchar(55) DEFAULT NULL,
  `charge_name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `absolute` tinyint(1) DEFAULT '0',
  `amount` double DEFAULT '0',
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales_discounts`
--

DROP TABLE IF EXISTS `trans_sales_discounts`;
CREATE TABLE `trans_sales_discounts` (
  `sales_disc_id` int(11) NOT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `disc_id` int(11) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `disc_code` varchar(55) DEFAULT NULL,
  `disc_rate` double DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `bday` datetime DEFAULT NULL,
  `code` varchar(55) DEFAULT NULL,
  `guest` int(11) DEFAULT NULL,
  `items` varchar(55) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `no_tax` tinyint(4) DEFAULT '0',
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales_items`
--

DROP TABLE IF EXISTS `trans_sales_items`;
CREATE TABLE `trans_sales_items` (
  `sales_item_id` int(11) NOT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `line_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `no_tax` int(1) DEFAULT '0',
  `remarks` varchar(150) DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `nocharge` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales_local_tax`
--

DROP TABLE IF EXISTS `trans_sales_local_tax`;
CREATE TABLE `trans_sales_local_tax` (
  `sales_local_tax_id` int(11) NOT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales_loyalty_points`
--

DROP TABLE IF EXISTS `trans_sales_loyalty_points`;
CREATE TABLE `trans_sales_loyalty_points` (
  `loyalty_point_id` int(11) NOT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL,
  `code` varchar(150) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `points` double DEFAULT '0',
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales_menus`
--

DROP TABLE IF EXISTS `trans_sales_menus`;
CREATE TABLE `trans_sales_menus` (
  `sales_menu_id` int(11) NOT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `line_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `no_tax` int(1) DEFAULT '0',
  `remarks` varchar(150) DEFAULT NULL,
  `kitchen_slip_printed` tinyint(1) DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `free_user_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `nocharge` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales_menu_modifiers`
--

DROP TABLE IF EXISTS `trans_sales_menu_modifiers`;
CREATE TABLE `trans_sales_menu_modifiers` (
  `sales_mod_id` int(11) NOT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `mod_group_id` int(11) DEFAULT NULL,
  `line_id` int(11) DEFAULT NULL,
  `mod_id` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `kitchen_slip_printed` tinyint(1) DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales_no_tax`
--

DROP TABLE IF EXISTS `trans_sales_no_tax`;
CREATE TABLE `trans_sales_no_tax` (
  `sales_no_tax_id` int(11) NOT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales_payments`
--

DROP TABLE IF EXISTS `trans_sales_payments`;
CREATE TABLE `trans_sales_payments` (
  `payment_id` int(11) NOT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `to_pay` double DEFAULT NULL,
  `reference` varchar(55) DEFAULT NULL,
  `card_type` varchar(55) DEFAULT NULL,
  `card_number` varchar(30) DEFAULT NULL,
  `approval_code` varchar(15) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales_tax`
--

DROP TABLE IF EXISTS `trans_sales_tax`;
CREATE TABLE `trans_sales_tax` (
  `sales_tax_id` int(11) NOT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_sales_zero_rated`
--

DROP TABLE IF EXISTS `trans_sales_zero_rated`;
CREATE TABLE `trans_sales_zero_rated` (
  `sales_zero_rated_id` int(11) NOT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_spoilage`
--

DROP TABLE IF EXISTS `trans_spoilage`;
CREATE TABLE `trans_spoilage` (
  `spoil_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_spoilage_details`
--

DROP TABLE IF EXISTS `trans_spoilage_details`;
CREATE TABLE `trans_spoilage_details` (
  `spoil_detail_id` int(11) NOT NULL,
  `spoil_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `uom` varchar(0) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans_types`
--

DROP TABLE IF EXISTS `trans_types`;
CREATE TABLE `trans_types` (
  `type_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `next_ref` varchar(45) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `trans_types`
--

INSERT INTO `trans_types` (`type_id`, `name`, `next_ref`, `sync_id`, `master_id`) VALUES
(10, 'sales', '00000001', 85, NULL),
(20, 'receivings', 'R000001', NULL, NULL),
(30, 'adjustment', 'A000001', NULL, NULL),
(11, 'sales void', 'V000001', 44, NULL),
(40, 'customer deposit', 'C000001', 139, NULL),
(50, 'loyalty card', '00000002', 33, NULL),
(55, 'menu receiving', 'RM00001', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trans_voids`
--

DROP TABLE IF EXISTS `trans_voids`;
CREATE TABLE `trans_voids` (
  `void_id` int(11) NOT NULL,
  `trans_type` int(11) DEFAULT NULL,
  `trans_id` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uom`
--

DROP TABLE IF EXISTS `uom`;
CREATE TABLE `uom` (
  `id` int(11) NOT NULL,
  `code` varchar(22) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `num` double DEFAULT '0',
  `to` varchar(22) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uom`
--

INSERT INTO `uom` (`id`, `code`, `name`, `num`, `to`, `inactive`) VALUES
(1, 'ml', 'Mililiter', 0, NULL, 0),
(2, 'gm', 'Gram', 0, NULL, 0),
(3, 'pc', 'Piece', 0, NULL, 0),
(4, 'can', 'Can', 0, '0', 0),
(5, 'bottle', 'Bottle', 0, '0', 0),
(6, 'kilo', 'Kilo', 0, '0', 0),
(7, 'pack', 'Pack', 0, '0', 0),
(8, 'Serving', 'serving', 0, '0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

DROP TABLE IF EXISTS `updates`;
CREATE TABLE `updates` (
  `ctr` int(11) NOT NULL,
  `query` longtext,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pin` varchar(55) DEFAULT NULL,
  `fname` varchar(55) DEFAULT NULL,
  `mname` varchar(55) DEFAULT NULL,
  `lname` varchar(55) DEFAULT NULL,
  `suffix` varchar(55) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `gender` varchar(11) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  `terminal_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `role` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `access` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `role`, `description`, `access`) VALUES
(1, 'Administrator ', 'System Administrator', 'all'),
(2, 'Manager', 'Manager', 'cashier,customers,gift_cards,trans,receiving,adjustment,items,list,item_inv,menu,menulist,menucat,menusched,mods,modslist,modgrps,dtr,shifts,scheduler,general_settings,gcategories,gsubcategories,guom,promos,gsuppliers,gcustomers,gtaxrates,grecdiscs,gterminals,gcurrencies,greferences,glocations,tblmng,setup,send_to_rob,control,user'),
(3, 'Employee', 'Employee', 'cashier'),
(4, 'OIC', 'Officer In Charge', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vistamall`
--

DROP TABLE IF EXISTS `vistamall`;
CREATE TABLE `vistamall` (
  `id` int(11) NOT NULL DEFAULT '0',
  `stall_code` varchar(50) DEFAULT NULL,
  `sales_dep` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `araneta`
--
ALTER TABLE `araneta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ayala`
--
ALTER TABLE `ayala`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batch_etl`
--
ALTER TABLE `batch_etl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_details`
--
ALTER TABLE `branch_details`
  ADD PRIMARY KEY (`branch_id`),
  ADD UNIQUE KEY `branch_code` (`branch_code`);

--
-- Indexes for table `branch_menus`
--
ALTER TABLE `branch_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashout_details`
--
ALTER TABLE `cashout_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashout_entries`
--
ALTER TABLE `cashout_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `charges`
--
ALTER TABLE `charges`
  ADD PRIMARY KEY (`charge_id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`con_id`);

--
-- Indexes for table `conversation_messages`
--
ALTER TABLE `conversation_messages`
  ADD PRIMARY KEY (`con_msg_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency_details`
--
ALTER TABLE `currency_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`cust_id`);

--
-- Indexes for table `customers_bank`
--
ALTER TABLE `customers_bank`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD PRIMARY KEY (`id`,`cust_id`);

--
-- Indexes for table `denominations`
--
ALTER TABLE `denominations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dtr_scheduler`
--
ALTER TABLE `dtr_scheduler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dtr_shifts`
--
ALTER TABLE `dtr_shifts`
  ADD PRIMARY KEY (`id`,`code`);

--
-- Indexes for table `eton`
--
ALTER TABLE `eton`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_cards`
--
ALTER TABLE `gift_cards`
  ADD PRIMARY KEY (`gc_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`img_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `item_moves`
--
ALTER TABLE `item_moves`
  ADD KEY `move_id` (`move_id`),
  ADD KEY `branch_code` (`branch_code`);

--
-- Indexes for table `item_serials`
--
ALTER TABLE `item_serials`
  ADD PRIMARY KEY (`id`,`item_code`,`serial_no`);

--
-- Indexes for table `item_types`
--
ALTER TABLE `item_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
-- ALTER TABLE `locations`
--   ADD PRIMARY KEY (`loc_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loyalty_cards`
--
ALTER TABLE `loyalty_cards`
  ADD PRIMARY KEY (`card_id`);

--
-- Indexes for table `master_logs`
--
ALTER TABLE `master_logs`
  ADD PRIMARY KEY (`master_id`);

--
-- Indexes for table `megamall`
--
ALTER TABLE `megamall`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `menu_modifiers`
--
ALTER TABLE `menu_modifiers`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `menu_moves`
--
ALTER TABLE `menu_moves`
  ADD KEY `move_id` (`move_id`),
  ADD KEY `branch_code` (`branch_code`);

--
-- Indexes for table `menu_recipe`
--
ALTER TABLE `menu_recipe`
  ADD PRIMARY KEY (`sysid`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `branch_code` (`branch_code`);

--
-- Indexes for table `menu_schedules`
--
ALTER TABLE `menu_schedules`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `menu_schedule_details`
--
ALTER TABLE `menu_schedule_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_subcategories`
--
ALTER TABLE `menu_subcategories`
  ADD PRIMARY KEY (`sysid`),
  ADD KEY `menu_sub_cat_id` (`menu_sub_cat_id`),
  ADD KEY `branch_code` (`branch_code`);

--
-- Indexes for table `menu_subcategory`
--
ALTER TABLE `menu_subcategory`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `modifiers`
--
ALTER TABLE `modifiers`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `modifier_groups`
--
ALTER TABLE `modifier_groups`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `modifier_group_details`
--
ALTER TABLE `modifier_group_details`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `modifier_recipe`
--
ALTER TABLE `modifier_recipe`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `ortigas`
--
ALTER TABLE `ortigas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ortigas_read_details`
--
ALTER TABLE `ortigas_read_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promo_discounts`
--
ALTER TABLE `promo_discounts`
  ADD PRIMARY KEY (`promo_id`);

--
-- Indexes for table `promo_discount_items`
--
ALTER TABLE `promo_discount_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promo_discount_schedule`
--
ALTER TABLE `promo_discount_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promo_free`
--
ALTER TABLE `promo_free`
  ADD PRIMARY KEY (`pf_id`);

--
-- Indexes for table `promo_free_menus`
--
ALTER TABLE `promo_free_menus`
  ADD PRIMARY KEY (`pf_menu_id`);

--
-- Indexes for table `read_details`
--
ALTER TABLE `read_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reasons`
--
ALTER TABLE `reasons`
  ADD KEY `id` (`id`),
  ADD KEY `branch_code` (`branch_code`);

--
-- Indexes for table `restaurant_branch_tables`
--
ALTER TABLE `restaurant_branch_tables`
  ADD PRIMARY KEY (`tbl_id`);

--
-- Indexes for table `rob_files`
--
ALTER TABLE `rob_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shift_entries`
--
ALTER TABLE `shift_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stalucia`
--
ALTER TABLE `stalucia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `suppliers`
--
-- ALTER TABLE `suppliers`
--   ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `sync_logs`
--
ALTER TABLE `sync_logs`
  ADD PRIMARY KEY (`sync_id`);

--
-- Indexes for table `sync_types`
--
ALTER TABLE `sync_types`
  ADD PRIMARY KEY (`sync_type_id`);

--
-- Indexes for table `tablesold`
--
ALTER TABLE `tablesold`
  ADD PRIMARY KEY (`tbl_id`);

--
-- Indexes for table `table_activity`
--
ALTER TABLE `table_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`tax_id`);

--
-- Indexes for table `terminals`
--
-- ALTER TABLE `terminals`
--   ADD PRIMARY KEY (`terminal_id`);

--
-- Indexes for table `trans_adjustments`
--
ALTER TABLE `trans_adjustments`
  ADD PRIMARY KEY (`adjustment_id`);

--
-- Indexes for table `trans_adjustment_details`
--
ALTER TABLE `trans_adjustment_details`
  ADD PRIMARY KEY (`adjustment_detail_id`);

--
-- Indexes for table `trans_receivings`
--
-- ALTER TABLE `trans_receivings`
--   ADD PRIMARY KEY (`receiving_id`);

--
-- Indexes for table `trans_receiving_details`
--
-- ALTER TABLE `trans_receiving_details`
--   ADD PRIMARY KEY (`receiving_detail_id`);

--
-- Indexes for table `trans_sales`
--
ALTER TABLE `trans_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_code` (`branch_code`),
  ADD KEY `sales_id` (`sales_id`);

--
-- Indexes for table `trans_sales_charges`
--
ALTER TABLE `trans_sales_charges`
  ADD KEY `sales_id` (`sales_id`),
  ADD KEY `charge_id` (`charge_id`);

--
-- Indexes for table `trans_sales_discounts`
--
ALTER TABLE `trans_sales_discounts`
  ADD KEY `sales_id` (`sales_id`),
  ADD KEY `disc_id` (`disc_id`),
  ADD KEY `disc_code` (`disc_code`),
  ADD KEY `branch_code` (`branch_code`);

--
-- Indexes for table `trans_sales_items`
--
ALTER TABLE `trans_sales_items`
  ADD KEY `sales_id` (`sales_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `branch_code` (`branch_code`);

--
-- Indexes for table `trans_sales_menus`
--
ALTER TABLE `trans_sales_menus`
  ADD KEY `sales_id` (`sales_id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `branch_code` (`branch_code`);

--
-- Indexes for table `trans_spoilage`
--
ALTER TABLE `trans_spoilage`
  ADD PRIMARY KEY (`spoil_id`);

--
-- Indexes for table `trans_spoilage_details`
--
ALTER TABLE `trans_spoilage_details`
  ADD PRIMARY KEY (`spoil_detail_id`);

--
-- Indexes for table `trans_types`
--
ALTER TABLE `trans_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `trans_voids`
--
ALTER TABLE `trans_voids`
  ADD PRIMARY KEY (`void_id`);

--
-- Indexes for table `uom`
--
ALTER TABLE `uom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`ctr`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vistamall`
--
ALTER TABLE `vistamall`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `araneta`
--
ALTER TABLE `araneta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `batch_etl`
--
ALTER TABLE `batch_etl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branch_details`
--
ALTER TABLE `branch_details`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branch_menus`
--
ALTER TABLE `branch_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cashout_details`
--
ALTER TABLE `cashout_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cashout_entries`
--
ALTER TABLE `cashout_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `charges`
--
ALTER TABLE `charges`
  MODIFY `charge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `con_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `conversation_messages`
--
ALTER TABLE `conversation_messages`
  MODIFY `con_msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `currency_details`
--
ALTER TABLE `currency_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `cust_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_address`
--
ALTER TABLE `customer_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `denominations`
--
ALTER TABLE `denominations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `dtr_scheduler`
--
ALTER TABLE `dtr_scheduler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dtr_shifts`
--
ALTER TABLE `dtr_shifts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eton`
--
ALTER TABLE `eton`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gift_cards`
--
ALTER TABLE `gift_cards`
  MODIFY `gc_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_serials`
--
ALTER TABLE `item_serials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_types`
--
ALTER TABLE `item_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `locations`
--
-- ALTER TABLE `locations`
--   MODIFY `loc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loyalty_cards`
--
ALTER TABLE `loyalty_cards`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_logs`
--
ALTER TABLE `master_logs`
  MODIFY `master_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `megamall`
--
ALTER TABLE `megamall`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_modifiers`
--
ALTER TABLE `menu_modifiers`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_recipe`
--
ALTER TABLE `menu_recipe`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_schedules`
--
ALTER TABLE `menu_schedules`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_schedule_details`
--
ALTER TABLE `menu_schedule_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_subcategories`
--
ALTER TABLE `menu_subcategories`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_subcategory`
--
ALTER TABLE `menu_subcategory`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modifiers`
--
ALTER TABLE `modifiers`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modifier_groups`
--
ALTER TABLE `modifier_groups`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modifier_group_details`
--
ALTER TABLE `modifier_group_details`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modifier_recipe`
--
ALTER TABLE `modifier_recipe`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ortigas`
--
ALTER TABLE `ortigas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ortigas_read_details`
--
ALTER TABLE `ortigas_read_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo_discounts`
--
ALTER TABLE `promo_discounts`
  MODIFY `promo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo_discount_items`
--
ALTER TABLE `promo_discount_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo_discount_schedule`
--
ALTER TABLE `promo_discount_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo_free`
--
ALTER TABLE `promo_free`
  MODIFY `pf_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo_free_menus`
--
ALTER TABLE `promo_free_menus`
  MODIFY `pf_menu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `read_details`
--
ALTER TABLE `read_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurant_branch_tables`
--
ALTER TABLE `restaurant_branch_tables`
  MODIFY `tbl_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rob_files`
--
ALTER TABLE `rob_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shift_entries`
--
ALTER TABLE `shift_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stalucia`
--
ALTER TABLE `stalucia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
-- ALTER TABLE `suppliers`
--   MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sync_logs`
--
ALTER TABLE `sync_logs`
  MODIFY `sync_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sync_types`
--
ALTER TABLE `sync_types`
  MODIFY `sync_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tablesold`
--
ALTER TABLE `tablesold`
  MODIFY `tbl_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_activity`
--
ALTER TABLE `table_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `tax_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `terminals`
--
-- ALTER TABLE `terminals`
--   MODIFY `terminal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_adjustments`
--
ALTER TABLE `trans_adjustments`
  MODIFY `adjustment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_adjustment_details`
--
ALTER TABLE `trans_adjustment_details`
  MODIFY `adjustment_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_receivings`
--
-- ALTER TABLE `trans_receivings`
--   MODIFY `receiving_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_receiving_details`
--
-- ALTER TABLE `trans_receiving_details`
--   MODIFY `receiving_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_sales`
--
ALTER TABLE `trans_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_spoilage`
--
ALTER TABLE `trans_spoilage`
  MODIFY `spoil_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_spoilage_details`
--
ALTER TABLE `trans_spoilage_details`
  MODIFY `spoil_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_voids`
--
ALTER TABLE `trans_voids`
  MODIFY `void_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uom`
--
ALTER TABLE `uom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `ctr` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `users` (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `terminal_id`, `branch_code`, `datetime`, `master_id`, `sync_id`) VALUES
(1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', '00001', 'Admin', 'I', 'Strator', NULL, 1, 'admin@test.com', 'F', NULL, 0, NULL, NULL, '2018-08-03 02:35:33', NULL, NULL);
ALTER TABLE `branch_details` ADD UNIQUE(`branch_code`);

ALTER TABLE `menus` ADD `miaa_cat` VARCHAR(250) NOT NULL AFTER `costing`;

ALTER TABLE  `locations` CHANGE  `loc_id`  `loc_id` INT( 11 ) NOT NULL;

ALTER TABLE  `suppliers` CHANGE  `supplier_id`  `supplier_id` INT( 11 ) NOT NULL;
ALTER TABLE  `terminals` CHANGE  `terminal_id`  `terminal_id` INT( 11 ) NOT NULL;

  ALTER TABLE items add `brand` varchar(55) NULL;
  ALTER TABLE items add `costing` double NULL DEFAULT 0;
  ALTER TABLE item_moves add `cost` double NULL DEFAULT 0;

  ALTER TABLE `items` ADD `date_effective` DATE NULL AFTER `type`;
ALTER TABLE `menus` ADD `date_effective` DATE NULL AFTER `inactive`;


CREATE TABLE IF NOT EXISTS `item_pricing_history` (
`history_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `cost` double DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item_pricing_history`
--
ALTER TABLE `item_pricing_history`
 ADD PRIMARY KEY (`history_id`), ADD KEY `item_id` (`item_id`) USING BTREE, ADD KEY `branch_code` (`branch_code`) USING BTREE, ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item_pricing_history`
--
ALTER TABLE `item_pricing_history`
MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE IF NOT EXISTS `menu_pricing_history` (
`history_id` int(11) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `cost` double DEFAULT NULL,
  `selling` double DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu_pricing_history`
--
ALTER TABLE `menu_pricing_history`
 ADD PRIMARY KEY (`history_id`), ADD KEY `menu_id` (`menu_id`) USING BTREE, ADD KEY `branch_code` (`branch_code`) USING BTREE, ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu_pricing_history`
--
ALTER TABLE `menu_pricing_history`
MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `suppliers` ADD `supplier_code` varchar(55);


ALTER TABLE `menu_moves` ADD `master_id` int(11) NOT NULL AFTER `inactive`;
ALTER TABLE `menu_categories` ADD `master_id` int(11) NOT NULL AFTER `inactive`;
ALTER TABLE `menu_categories` ADD `master_id` int(11) NOT NULL AFTER `inactive`;
