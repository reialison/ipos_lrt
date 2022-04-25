/*
MySQL Backup
Source Server Version: 5.5.5
Source Database: max_main
Date: 7/27/2021 10:20:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `araneta`
-- ----------------------------
DROP TABLE IF EXISTS `araneta`;
CREATE TABLE `araneta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lessee_name` varchar(20) DEFAULT NULL,
  `lessee_no` varchar(20) DEFAULT NULL,
  `space_code` varchar(20) DEFAULT '',
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `ayala`
-- ----------------------------
DROP TABLE IF EXISTS `ayala`;
CREATE TABLE `ayala` (
  `id` int(11) NOT NULL,
  `contract_no` varchar(150) DEFAULT NULL,
  `store_name` varchar(150) DEFAULT NULL,
  `xxx_no` varchar(150) DEFAULT NULL,
  `dbf_tenant_name` varchar(150) DEFAULT NULL,
  `dbf_path` varchar(150) DEFAULT NULL,
  `text_file_path` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `branch_details`
-- ----------------------------
DROP TABLE IF EXISTS `branch_details`;
CREATE TABLE `branch_details` (
  `branch_id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `branch_details_copy`
-- ----------------------------
DROP TABLE IF EXISTS `branch_details_copy`;
CREATE TABLE `branch_details_copy` (
  `branch_id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `branch_menus`
-- ----------------------------
DROP TABLE IF EXISTS `branch_menus`;
CREATE TABLE `branch_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `menu_code` varchar(15) DEFAULT NULL,
  `menu_name` varchar(25) DEFAULT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `cashout_details`
-- ----------------------------
DROP TABLE IF EXISTS `cashout_details`;
CREATE TABLE `cashout_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cashout_detail_id` int(11) DEFAULT NULL,
  `cashout_id` int(11) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `denomination` varchar(150) DEFAULT '0',
  `reference` varchar(150) DEFAULT NULL,
  `total` double DEFAULT '0',
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `cashout_entries`
-- ----------------------------
DROP TABLE IF EXISTS `cashout_entries`;
CREATE TABLE `cashout_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cashout_id` int(11) NOT NULL,
  `user_id` varchar(45) NOT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `drawer_amount` varchar(255) DEFAULT NULL,
  `count_amount` double DEFAULT NULL,
  `trans_date` datetime NOT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `categories`
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `cat_id` (`cat_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `categories0629`
-- ----------------------------
DROP TABLE IF EXISTS `categories0629`;
CREATE TABLE `categories0629` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `categories_1`
-- ----------------------------
DROP TABLE IF EXISTS `categories_1`;
CREATE TABLE `categories_1` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `charges`
-- ----------------------------
DROP TABLE IF EXISTS `charges`;
CREATE TABLE `charges` (
  `charge_id` int(11) NOT NULL AUTO_INCREMENT,
  `charge_code` varchar(22) DEFAULT NULL,
  `charge_name` varchar(55) DEFAULT NULL,
  `charge_amount` double DEFAULT NULL,
  `absolute` tinyint(1) DEFAULT '0',
  `no_tax` tinyint(1) DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`charge_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `charges_19`
-- ----------------------------
DROP TABLE IF EXISTS `charges_19`;
CREATE TABLE `charges_19` (
  `charge_id` int(11) NOT NULL AUTO_INCREMENT,
  `charge_code` varchar(22) DEFAULT NULL,
  `charge_name` varchar(55) DEFAULT NULL,
  `charge_amount` double DEFAULT NULL,
  `absolute` tinyint(1) DEFAULT '0',
  `no_tax` tinyint(1) DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`charge_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ci_sessions`
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` longtext NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `company`
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) DEFAULT NULL,
  `contact_no` varchar(55) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `tin` varchar(100) DEFAULT NULL,
  `fiscal_year` int(11) DEFAULT NULL,
  `theme` varchar(55) DEFAULT 'blue',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `conversations`
-- ----------------------------
DROP TABLE IF EXISTS `conversations`;
CREATE TABLE `conversations` (
  `con_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_a` int(11) DEFAULT NULL,
  `user_b` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`con_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `conversation_messages`
-- ----------------------------
DROP TABLE IF EXISTS `conversation_messages`;
CREATE TABLE `conversation_messages` (
  `con_msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `con_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `msg` longtext,
  `file` longblob,
  `datetime` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`con_msg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `coupons`
-- ----------------------------
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `coupon_id` int(10) NOT NULL AUTO_INCREMENT,
  `card_no` varchar(100) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `expiration` date DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `currencies`
-- ----------------------------
DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(22) DEFAULT NULL,
  `currency_desc` varchar(55) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `currency_details`
-- ----------------------------
DROP TABLE IF EXISTS `currency_details`;
CREATE TABLE `currency_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_id` varchar(45) NOT NULL,
  `desc` varchar(60) NOT NULL,
  `value` double NOT NULL,
  `img` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `customers`
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `reg_date` datetime DEFAULT NULL,
  PRIMARY KEY (`cust_id`),
  KEY `cust_id` (`cust_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `customers_bank`
-- ----------------------------
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
  `remarks` longtext,
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `customer_address`
-- ----------------------------
DROP TABLE IF EXISTS `customer_address`;
CREATE TABLE `customer_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` int(11) NOT NULL,
  `street_no` varchar(55) NOT NULL,
  `street_address` varchar(55) NOT NULL,
  `city` varchar(55) NOT NULL,
  `region` varchar(55) NOT NULL,
  `zip` varchar(55) NOT NULL,
  `base_location` varchar(100) NOT NULL,
  PRIMARY KEY (`id`,`cust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `denominations`
-- ----------------------------
DROP TABLE IF EXISTS `denominations`;
CREATE TABLE `denominations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc` varchar(60) NOT NULL,
  `value` double NOT NULL,
  `img` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `discount_requests`
-- ----------------------------
DROP TABLE IF EXISTS `discount_requests`;
CREATE TABLE `discount_requests` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_code` varchar(255) DEFAULT NULL,
  `menu_name` varchar(255) DEFAULT NULL,
  `menu_id` varchar(255) DEFAULT NULL,
  `menu_qty` double(255,0) DEFAULT NULL,
  `menu_srp` double(255,0) DEFAULT NULL,
  `menu_total` double(255,0) DEFAULT NULL,
  `approval_code` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `inactive` int(1) DEFAULT NULL,
  `requested_user_id` varchar(11) DEFAULT NULL,
  `requested_by` varchar(255) DEFAULT NULL,
  `request_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `granted_user_id` int(11) DEFAULT NULL,
  `granted_date` datetime DEFAULT NULL,
  `discount_rate` double(255,0) DEFAULT NULL,
  `discount_absolute` double(255,0) DEFAULT NULL,
  PRIMARY KEY (`request_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `dtr_scheduler`
-- ----------------------------
DROP TABLE IF EXISTS `dtr_scheduler`;
CREATE TABLE `dtr_scheduler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `dtr_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `dtr_shifts`
-- ----------------------------
DROP TABLE IF EXISTS `dtr_shifts`;
CREATE TABLE `dtr_shifts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `timein_grace_period` time DEFAULT NULL,
  PRIMARY KEY (`id`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `eton`
-- ----------------------------
DROP TABLE IF EXISTS `eton`;
CREATE TABLE `eton` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_code` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `expenses_details`
-- ----------------------------
DROP TABLE IF EXISTS `expenses_details`;
CREATE TABLE `expenses_details` (
  `expenses_detail_id` int(100) NOT NULL AUTO_INCREMENT,
  `expenses_id` int(100) DEFAULT NULL,
  `expenses_item_id` int(100) DEFAULT NULL,
  `expenses_qty` int(100) DEFAULT NULL,
  `price` int(100) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`expenses_detail_id`),
  KEY `expenses_detail_id` (`expenses_detail_id`) USING BTREE,
  KEY `expenses_id` (`expenses_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `expenses_entry`
-- ----------------------------
DROP TABLE IF EXISTS `expenses_entry`;
CREATE TABLE `expenses_entry` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(10) DEFAULT NULL,
  `trans_ref` varchar(100) DEFAULT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `supplier_id` int(10) DEFAULT NULL,
  `unit_price` int(10) DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `sync_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `type_id` (`type_id`) USING BTREE,
  KEY `inactive` (`inactive`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `expenses_items`
-- ----------------------------
DROP TABLE IF EXISTS `expenses_items`;
CREATE TABLE `expenses_items` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `expenses_code` varchar(255) DEFAULT NULL,
  `expenses_name` varchar(255) DEFAULT NULL,
  `expenses_desc` varchar(255) DEFAULT NULL,
  `expenses_unit` varchar(255) DEFAULT NULL,
  `expenses_price` int(100) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `added_by` int(10) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_code` (`expenses_code`) USING BTREE,
  KEY `inactive` (`inactive`) USING BTREE,
  KEY `date_added` (`date_added`) USING BTREE,
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `gifted`
-- ----------------------------
DROP TABLE IF EXISTS `gifted`;
CREATE TABLE `gifted` (
  `gifted_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_no` varchar(255) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `inactive` tinyint(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`gifted_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `gift_away`
-- ----------------------------
DROP TABLE IF EXISTS `gift_away`;
CREATE TABLE `gift_away` (
  `ga_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_no` varchar(255) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `inactive` tinyint(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ga_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `gift_cards`
-- ----------------------------
DROP TABLE IF EXISTS `gift_cards`;
CREATE TABLE `gift_cards` (
  `gc_id` int(10) NOT NULL AUTO_INCREMENT,
  `card_no` varchar(100) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`gc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `images`
-- ----------------------------
DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `img_id` int(11) NOT NULL AUTO_INCREMENT,
  `img_file_name` longtext,
  `img_path` longtext,
  `img_ref_id` int(11) DEFAULT NULL,
  `img_tbl` varchar(50) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`img_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `items`
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `inactive` int(11) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  `brand` varchar(55) DEFAULT NULL,
  `costing` double DEFAULT '0',
  PRIMARY KEY (`item_id`),
  KEY `item_id` (`item_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `items0411`
-- ----------------------------
DROP TABLE IF EXISTS `items0411`;
CREATE TABLE `items0411` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `update_date` datetime DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  `costing` double DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `item_moves`
-- ----------------------------
DROP TABLE IF EXISTS `item_moves`;
CREATE TABLE `item_moves` (
  `move_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cost` double DEFAULT '0',
  PRIMARY KEY (`move_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `item_types`
-- ----------------------------
DROP TABLE IF EXISTS `item_types`;
CREATE TABLE `item_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `lima`
-- ----------------------------
DROP TABLE IF EXISTS `lima`;
CREATE TABLE `lima` (
  `id` int(11) NOT NULL DEFAULT '0',
  `tenant_id` int(50) DEFAULT NULL,
  `tenant_key` varchar(50) DEFAULT NULL,
  `terminal_no` int(4) unsigned zerofill DEFAULT NULL,
  `document_id` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `locations`
-- ----------------------------
DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `loc_id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_code` varchar(22) DEFAULT NULL,
  `loc_name` varchar(55) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`loc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `logs`
-- ----------------------------
DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` longtext,
  `reference` longtext,
  `datetime` datetime DEFAULT NULL,
  `type` varchar(11) DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `loyalty_cards`
-- ----------------------------
DROP TABLE IF EXISTS `loyalty_cards`;
CREATE TABLE `loyalty_cards` (
  `card_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `points` double(10,0) DEFAULT '0',
  `reg_user_id` int(11) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `master_logs`
-- ----------------------------
DROP TABLE IF EXISTS `master_logs`;
CREATE TABLE `master_logs` (
  `master_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) DEFAULT NULL,
  `src_id` text,
  `transaction` varchar(250) DEFAULT NULL,
  `type` varchar(250) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_automated` tinyint(1) DEFAULT '0',
  `migrate_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` varchar(250) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `record_count` int(11) DEFAULT NULL,
  `master_sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`master_id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `master_logs_before09212018`
-- ----------------------------
DROP TABLE IF EXISTS `master_logs_before09212018`;
CREATE TABLE `master_logs_before09212018` (
  `master_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) DEFAULT NULL,
  `src_id` text,
  `transaction` varchar(250) DEFAULT NULL,
  `type` varchar(250) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_automated` tinyint(1) DEFAULT '0',
  `migrate_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` varchar(250) DEFAULT NULL,
  `branch_code` varchar(250) DEFAULT NULL,
  `record_count` int(11) DEFAULT NULL,
  `master_sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `megamall`
-- ----------------------------
DROP TABLE IF EXISTS `megamall`;
CREATE TABLE `megamall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `br_code` varchar(20) DEFAULT NULL,
  `tenant_no` varchar(20) DEFAULT NULL,
  `class_code` varchar(20) DEFAULT '',
  `trade_code` varchar(20) DEFAULT NULL,
  `outlet_no` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `megaworld`
-- ----------------------------
DROP TABLE IF EXISTS `megaworld`;
CREATE TABLE `megaworld` (
  `id` int(11) NOT NULL DEFAULT '0',
  `tenant_code` varchar(50) DEFAULT NULL,
  `sales_type` varchar(20) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `menus`
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_code` varchar(100) DEFAULT NULL,
  `menu_barcode` varchar(255) DEFAULT NULL,
  `menu_name` varchar(255) DEFAULT NULL,
  `menu_short_desc` varchar(255) DEFAULT NULL,
  `menu_cat_id` int(11) NOT NULL,
  `menu_sub_cat_id` int(11) DEFAULT NULL,
  `menu_sched_id` int(11) DEFAULT '0',
  `cost` double DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `no_tax` int(1) DEFAULT '0',
  `free` int(1) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  `costing` double DEFAULT '0',
  `menu_sub_id` varchar(255) DEFAULT NULL,
  `miaa_cat` varchar(255) DEFAULT NULL,
  `reorder_qty` int(11) DEFAULT '0',
  `brand` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`menu_id`),
  KEY `menu_id` (`menu_id`) USING BTREE,
  KEY `menu_cat_id` (`menu_cat_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `menus234`
-- ----------------------------
DROP TABLE IF EXISTS `menus234`;
CREATE TABLE `menus234` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_code` varchar(100) DEFAULT NULL,
  `menu_barcode` varchar(255) DEFAULT NULL,
  `menu_name` varchar(255) DEFAULT NULL,
  `menu_short_desc` varchar(255) DEFAULT NULL,
  `menu_cat_id` int(11) NOT NULL,
  `menu_sub_cat_id` int(11) DEFAULT NULL,
  `menu_sched_id` int(11) DEFAULT '0',
  `cost` double DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `no_tax` int(1) DEFAULT '0',
  `free` int(1) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  `costing` double DEFAULT '0',
  `menu_sub_id` varchar(255) DEFAULT NULL,
  `miaa_cat` varchar(255) DEFAULT NULL,
  `reorder_qty` int(11) DEFAULT '0',
  PRIMARY KEY (`menu_id`),
  KEY `menu_id` (`menu_id`) USING BTREE,
  KEY `menu_cat_id` (`menu_cat_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=279 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `menus_copy`
-- ----------------------------
DROP TABLE IF EXISTS `menus_copy`;
CREATE TABLE `menus_copy` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_code` varchar(100) DEFAULT NULL,
  `menu_barcode` varchar(255) DEFAULT NULL,
  `menu_name` varchar(255) DEFAULT NULL,
  `menu_short_desc` varchar(255) DEFAULT NULL,
  `menu_cat_id` int(11) NOT NULL,
  `menu_sub_cat_id` int(11) DEFAULT NULL,
  `menu_sched_id` int(11) DEFAULT '0',
  `cost` double DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `no_tax` int(1) DEFAULT '0',
  `free` int(1) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `costing` double DEFAULT '0',
  `menu_sub_id` varchar(255) DEFAULT NULL,
  `miaa_cat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `menu_categories`
-- ----------------------------
DROP TABLE IF EXISTS `menu_categories`;
CREATE TABLE `menu_categories` (
  `menu_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_cat_name` varchar(150) NOT NULL,
  `menu_sched_id` int(11) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  `arrangement` int(11) DEFAULT '0',
  PRIMARY KEY (`menu_cat_id`),
  KEY `menu_cat_id` (`menu_cat_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `menu_modifiers`
-- ----------------------------
DROP TABLE IF EXISTS `menu_modifiers`;
CREATE TABLE `menu_modifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `mod_group_id` int(11) NOT NULL,
  `master_id` int(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `menu_moves`
-- ----------------------------
DROP TABLE IF EXISTS `menu_moves`;
CREATE TABLE `menu_moves` (
  `move_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`move_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `menu_recipe`
-- ----------------------------
DROP TABLE IF EXISTS `menu_recipe`;
CREATE TABLE `menu_recipe` (
  `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `master_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `uom` varchar(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` double NOT NULL,
  PRIMARY KEY (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `menu_schedules`
-- ----------------------------
DROP TABLE IF EXISTS `menu_schedules`;
CREATE TABLE `menu_schedules` (
  `menu_sched_id` int(11) NOT NULL AUTO_INCREMENT,
  `desc` varchar(150) NOT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`menu_sched_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `menu_schedule_details`
-- ----------------------------
DROP TABLE IF EXISTS `menu_schedule_details`;
CREATE TABLE `menu_schedule_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_sched_id` int(11) NOT NULL,
  `day` varchar(22) NOT NULL,
  `time_on` time NOT NULL,
  `time_off` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `menu_subcategories`
-- ----------------------------
DROP TABLE IF EXISTS `menu_subcategories`;
CREATE TABLE `menu_subcategories` (
  `menu_sub_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_sub_cat_name` varchar(150) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`menu_sub_cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `menu_subcategory`
-- ----------------------------
DROP TABLE IF EXISTS `menu_subcategory`;
CREATE TABLE `menu_subcategory` (
  `menu_sub_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_sub_name` varchar(150) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `category_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`menu_sub_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `miaa`
-- ----------------------------
DROP TABLE IF EXISTS `miaa`;
CREATE TABLE `miaa` (
  `id` int(11) NOT NULL DEFAULT '0',
  `tenant_code` varchar(50) DEFAULT NULL,
  `sales_type` varchar(20) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `miaa_cat_details`
-- ----------------------------
DROP TABLE IF EXISTS `miaa_cat_details`;
CREATE TABLE `miaa_cat_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `miaa_cat` varchar(255) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `modifiers`
-- ----------------------------
DROP TABLE IF EXISTS `modifiers`;
CREATE TABLE `modifiers` (
  `mod_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `cost` double(11,0) DEFAULT '0',
  `has_recipe` int(1) DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `mod_sub_cat_id` int(11) DEFAULT '0',
  `mod_code` varchar(255) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `modifiers_copy`
-- ----------------------------
DROP TABLE IF EXISTS `modifiers_copy`;
CREATE TABLE `modifiers_copy` (
  `mod_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `cost` double(11,0) DEFAULT '0',
  `has_recipe` int(1) DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `modifier_groups`
-- ----------------------------
DROP TABLE IF EXISTS `modifier_groups`;
CREATE TABLE `modifier_groups` (
  `mod_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `mandatory` int(1) DEFAULT '0',
  `multiple` int(10) DEFAULT '0',
  `master_id` int(100) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `min_no` int(100) DEFAULT NULL,
  PRIMARY KEY (`mod_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `modifier_group_details`
-- ----------------------------
DROP TABLE IF EXISTS `modifier_group_details`;
CREATE TABLE `modifier_group_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_group_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `master_id` int(100) DEFAULT NULL,
  `default` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `modifier_recipe`
-- ----------------------------
DROP TABLE IF EXISTS `modifier_recipe`;
CREATE TABLE `modifier_recipe` (
  `mod_recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_id` int(11) NOT NULL,
  `master_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `uom` varchar(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` double NOT NULL,
  PRIMARY KEY (`mod_recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ortigas`
-- ----------------------------
DROP TABLE IF EXISTS `ortigas`;
CREATE TABLE `ortigas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_code` varchar(10) DEFAULT NULL,
  `sales_type` varchar(5) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `ortigas_read_details`
-- ----------------------------
DROP TABLE IF EXISTS `ortigas_read_details`;
CREATE TABLE `ortigas_read_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zread_id` int(11) DEFAULT NULL,
  `read_date` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `old_total` double DEFAULT NULL,
  `grand_total` double DEFAULT NULL COMMENT 'GT for ZRead only',
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `scope_from` datetime DEFAULT NULL,
  `scope_to` datetime DEFAULT NULL,
  `no_tax` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `promo_discounts`
-- ----------------------------
DROP TABLE IF EXISTS `promo_discounts`;
CREATE TABLE `promo_discounts` (
  `promo_id` int(11) NOT NULL AUTO_INCREMENT,
  `promo_code` varchar(22) DEFAULT NULL,
  `promo_name` varchar(55) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `absolute` tinyint(4) DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  PRIMARY KEY (`promo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `promo_discount_items`
-- ----------------------------
DROP TABLE IF EXISTS `promo_discount_items`;
CREATE TABLE `promo_discount_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `promo_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `promo_discount_schedule`
-- ----------------------------
DROP TABLE IF EXISTS `promo_discount_schedule`;
CREATE TABLE `promo_discount_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promo_id` int(11) NOT NULL,
  `day` varchar(22) NOT NULL,
  `time_on` time NOT NULL,
  `time_off` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `promo_free`
-- ----------------------------
DROP TABLE IF EXISTS `promo_free`;
CREATE TABLE `promo_free` (
  `pf_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `has_menu_id` varchar(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sched_id` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`pf_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `promo_free_menus`
-- ----------------------------
DROP TABLE IF EXISTS `promo_free_menus`;
CREATE TABLE `promo_free_menus` (
  `pf_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `pf_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  PRIMARY KEY (`pf_menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `read_details`
-- ----------------------------
DROP TABLE IF EXISTS `read_details`;
CREATE TABLE `read_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `ctr` int(11) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `reasons`
-- ----------------------------
DROP TABLE IF EXISTS `reasons`;
CREATE TABLE `reasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `ref_name` varchar(150) DEFAULT NULL,
  `reason` longtext,
  `trans_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `receipt_discounts`
-- ----------------------------
DROP TABLE IF EXISTS `receipt_discounts`;
CREATE TABLE `receipt_discounts` (
  `disc_id` int(11) NOT NULL AUTO_INCREMENT,
  `disc_code` varchar(22) DEFAULT NULL,
  `disc_name` varchar(100) DEFAULT NULL,
  `disc_rate` double DEFAULT NULL,
  `no_tax` int(1) DEFAULT '0',
  `fix` int(1) DEFAULT '0',
  `inactive` int(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`disc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `restaurant_branch_tables`
-- ----------------------------
DROP TABLE IF EXISTS `restaurant_branch_tables`;
CREATE TABLE `restaurant_branch_tables` (
  `tbl_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `top` int(11) DEFAULT '0',
  `left` int(11) DEFAULT '0',
  PRIMARY KEY (`tbl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `rob_files`
-- ----------------------------
DROP TABLE IF EXISTS `rob_files`;
CREATE TABLE `rob_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(55) DEFAULT NULL,
  `file` varchar(150) DEFAULT NULL,
  `print` double DEFAULT '0',
  `inactive` tinyint(4) DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `rockwell`
-- ----------------------------
DROP TABLE IF EXISTS `rockwell`;
CREATE TABLE `rockwell` (
  `id` int(11) NOT NULL DEFAULT '0',
  `stall_code` varchar(50) DEFAULT NULL,
  `sales_dep` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `sales_orders`
-- ----------------------------
DROP TABLE IF EXISTS `sales_orders`;
CREATE TABLE `sales_orders` (
  `sales_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `reference` varchar(150) DEFAULT NULL,
  `trans_ref` int(11) DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `debtor_no` int(11) DEFAULT NULL,
  `debtor_name` varchar(150) DEFAULT NULL,
  `branch_code` int(11) DEFAULT NULL,
  `payment_term_id` int(11) DEFAULT NULL,
  `sales_type` int(11) DEFAULT NULL,
  `salesman` int(11) DEFAULT NULL,
  `from_loc` varchar(150) DEFAULT NULL,
  `del_date` date DEFAULT NULL,
  `deliver_to` varchar(250) DEFAULT NULL,
  `delivery_address` varchar(250) DEFAULT NULL,
  `contact_phone` varchar(55) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `ord_date` date DEFAULT NULL,
  `cust_ref` varchar(150) DEFAULT NULL,
  `comments` varchar(250) DEFAULT NULL,
  `ship_via` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `total_amount` double DEFAULT '0',
  `total_tax` double DEFAULT '0',
  `total_disc` double DEFAULT '0',
  `total_qty` double DEFAULT NULL,
  `total_sent` double DEFAULT '0',
  `ship_cost` double DEFAULT '0',
  `print` int(11) DEFAULT '0',
  `invoiced` tinyint(1) DEFAULT '0',
  `create_date` datetime DEFAULT NULL,
  `allocated_amount` double DEFAULT '0',
  `return_amount` double DEFAULT '0',
  `dimension_id` int(11) DEFAULT NULL,
  `dimension2_id` int(11) DEFAULT NULL,
  `store_trans_ref` varchar(100) DEFAULT NULL,
  `request_name` varchar(100) DEFAULT NULL,
  `request_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`sales_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `sales_order_details`
-- ----------------------------
DROP TABLE IF EXISTS `sales_order_details`;
CREATE TABLE `sales_order_details` (
  `sales_order_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_order_id` int(11) DEFAULT NULL,
  `sales_kit` tinyint(1) DEFAULT '0',
  `item_code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `serial` varchar(250) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `tax_type` varchar(50) DEFAULT NULL,
  `tax_rate` double DEFAULT '0',
  `discount` double DEFAULT '0',
  `subtotal` double DEFAULT NULL,
  `qty_sent` double DEFAULT '0',
  `delivery_date` date DEFAULT NULL,
  PRIMARY KEY (`sales_order_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_of_receipt_print` int(11) DEFAULT NULL,
  `no_of_order_slip_print` int(11) DEFAULT NULL,
  `controls` varchar(500) DEFAULT NULL,
  `local_tax` double(5,0) DEFAULT '0',
  `kitchen_printer_name` varchar(150) DEFAULT NULL,
  `kitchen_beverage_printer_name` varchar(150) DEFAULT NULL,
  `kitchen_printer_name_no` int(11) DEFAULT '0',
  `kitchen_beverage_printer_name_no` int(11) DEFAULT '0',
  `open_drawer_printer` varchar(150) DEFAULT NULL,
  `loyalty_for_amount` double DEFAULT NULL,
  `loyalty_to_points` double DEFAULT NULL,
  `backup_path` varchar(255) DEFAULT NULL,
  `neg_inv` tinyint(4) DEFAULT '0',
  `img_vid` tinyint(4) DEFAULT NULL,
  `show_image` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `shangrila`
-- ----------------------------
DROP TABLE IF EXISTS `shangrila`;
CREATE TABLE `shangrila` (
  `id` int(11) NOT NULL DEFAULT '0',
  `tenant_code` varchar(50) DEFAULT NULL,
  `sales_dep` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `shifts`
-- ----------------------------
DROP TABLE IF EXISTS `shifts`;
CREATE TABLE `shifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime DEFAULT NULL,
  `xread_id` int(11) DEFAULT NULL,
  `cashout_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `shift_entries`
-- ----------------------------
DROP TABLE IF EXISTS `shift_entries`;
CREATE TABLE `shift_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `user_id` varchar(45) NOT NULL,
  `trans_date` datetime NOT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `memo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `stalucia`
-- ----------------------------
DROP TABLE IF EXISTS `stalucia`;
CREATE TABLE `stalucia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `store_order_details`
-- ----------------------------
DROP TABLE IF EXISTS `store_order_details`;
CREATE TABLE `store_order_details` (
  `detail_id` int(100) NOT NULL AUTO_INCREMENT,
  `store_order_id` int(100) DEFAULT NULL,
  `item_id` int(100) DEFAULT NULL,
  `uom` varchar(12) DEFAULT NULL,
  `qty` int(100) DEFAULT NULL,
  `price` int(100) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `detail_id` (`detail_id`) USING BTREE,
  KEY `store_order_id` (`store_order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `store_order_entry`
-- ----------------------------
DROP TABLE IF EXISTS `store_order_entry`;
CREATE TABLE `store_order_entry` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(10) DEFAULT NULL,
  `branch_code` varchar(55) DEFAULT NULL,
  `trans_ref` varchar(100) DEFAULT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `supplier_id` int(10) DEFAULT NULL,
  `unit_price` int(10) DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `sync_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `type_id` (`type_id`) USING BTREE,
  KEY `inactive` (`inactive`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `subcategories`
-- ----------------------------
DROP TABLE IF EXISTS `subcategories`;
CREATE TABLE `subcategories` (
  `sub_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sub_cat_id`),
  KEY `sub_cat_id` (`sub_cat_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `suppliers`
-- ----------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `inactive` varchar(255) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `supplier_code` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `sync_logs`
-- ----------------------------
DROP TABLE IF EXISTS `sync_logs`;
CREATE TABLE `sync_logs` (
  `sync_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction` varchar(250) DEFAULT NULL,
  `type` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `migrate_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `src_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_automated` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`sync_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `sync_logs_copy`
-- ----------------------------
DROP TABLE IF EXISTS `sync_logs_copy`;
CREATE TABLE `sync_logs_copy` (
  `sync_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction` varchar(250) DEFAULT NULL,
  `type` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `migrate_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `src_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_automated` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`sync_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `sync_types`
-- ----------------------------
DROP TABLE IF EXISTS `sync_types`;
CREATE TABLE `sync_types` (
  `sync_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `sync_type` varchar(100) NOT NULL,
  PRIMARY KEY (`sync_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `tables`
-- ----------------------------
DROP TABLE IF EXISTS `tables`;
CREATE TABLE `tables` (
  `tbl_id` int(11) NOT NULL AUTO_INCREMENT,
  `capacity` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `top` int(11) DEFAULT '0',
  `left` int(11) DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  `trans_type` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`tbl_id`),
  KEY `tbl_id` (`tbl_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `tablesold`
-- ----------------------------
DROP TABLE IF EXISTS `tablesold`;
CREATE TABLE `tablesold` (
  `tbl_id` int(11) NOT NULL AUTO_INCREMENT,
  `capacity` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `top` int(11) DEFAULT '0',
  `left` int(11) DEFAULT '0',
  PRIMARY KEY (`tbl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `table_activity`
-- ----------------------------
DROP TABLE IF EXISTS `table_activity`;
CREATE TABLE `table_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tbl_id` int(11) DEFAULT NULL,
  `pc_id` int(11) DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `tax_rates`
-- ----------------------------
DROP TABLE IF EXISTS `tax_rates`;
CREATE TABLE `tax_rates` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `terminals`
-- ----------------------------
DROP TABLE IF EXISTS `terminals`;
CREATE TABLE `terminals` (
  `terminal_id` int(11) NOT NULL AUTO_INCREMENT,
  `terminal_code` varchar(60) NOT NULL,
  `branch_code` varchar(55) DEFAULT NULL,
  `terminal_name` varchar(120) DEFAULT NULL,
  `ip` varchar(60) DEFAULT NULL,
  `comp_name` varchar(60) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`terminal_id`),
  KEY `terminal_id` (`terminal_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `total_charges`
-- ----------------------------
DROP TABLE IF EXISTS `total_charges`;
CREATE TABLE `total_charges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `read_date` date DEFAULT NULL,
  `total_charges` double DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `transfer_split`
-- ----------------------------
DROP TABLE IF EXISTS `transfer_split`;
CREATE TABLE `transfer_split` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `details` varchar(255) DEFAULT NULL,
  `type` varchar(55) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `menus` varchar(255) DEFAULT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `trans_adjustments`
-- ----------------------------
DROP TABLE IF EXISTS `trans_adjustments`;
CREATE TABLE `trans_adjustments` (
  `adjustment_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`adjustment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_adjustment_details`
-- ----------------------------
DROP TABLE IF EXISTS `trans_adjustment_details`;
CREATE TABLE `trans_adjustment_details` (
  `adjustment_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `adjustment_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `case` int(1) DEFAULT '0',
  `pack` int(1) DEFAULT '0',
  `from_loc` int(11) DEFAULT NULL,
  `to_loc` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`adjustment_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_receivings`
-- ----------------------------
DROP TABLE IF EXISTS `trans_receivings`;
CREATE TABLE `trans_receivings` (
  `receiving_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `delivered_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`receiving_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_receiving_details`
-- ----------------------------
DROP TABLE IF EXISTS `trans_receiving_details`;
CREATE TABLE `trans_receiving_details` (
  `receiving_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `receiving_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `case` int(1) DEFAULT '0',
  `pack` int(1) DEFAULT '0',
  `price` double DEFAULT NULL,
  `uom` varchar(50) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`receiving_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_receiving_menu`
-- ----------------------------
DROP TABLE IF EXISTS `trans_receiving_menu`;
CREATE TABLE `trans_receiving_menu` (
  `receiving_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`receiving_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_receiving_menu_details`
-- ----------------------------
DROP TABLE IF EXISTS `trans_receiving_menu_details`;
CREATE TABLE `trans_receiving_menu_details` (
  `receiving_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `receiving_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `uom` varchar(50) DEFAULT NULL,
  `case` int(1) DEFAULT '0',
  `pack` int(1) DEFAULT '0',
  `price` double DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`receiving_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_refs`
-- ----------------------------
DROP TABLE IF EXISTS `trans_refs`;
CREATE TABLE `trans_refs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(55) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_sales`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales`;
CREATE TABLE `trans_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `total_gross` double DEFAULT '0',
  `total_discount` double DEFAULT '0',
  `total_charges` double DEFAULT '0',
  `zero_rated` double DEFAULT '0',
  `no_tax` double DEFAULT '0',
  `tax` double DEFAULT '0',
  `local_tax` double DEFAULT '0',
  `branch_code` varchar(120) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  `tin` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_id` (`sales_id`) USING BTREE,
  KEY `customer_id` (`customer_id`) USING BTREE,
  KEY `terminal_id` (`terminal_id`) USING BTREE,
  KEY `datetime` (`datetime`) USING BTREE,
  KEY `type_id` (`type_id`) USING BTREE,
  KEY `trans_ref` (`trans_ref`) USING BTREE,
  KEY `inactive` (`inactive`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_sales_charges`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales_charges`;
CREATE TABLE `trans_sales_charges` (
  `sales_charge_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `charge_id` int(11) DEFAULT NULL,
  `charge_code` varchar(55) DEFAULT NULL,
  `charge_name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `absolute` tinyint(1) DEFAULT '0',
  `amount` double DEFAULT '0',
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_charge_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_sales_discounts`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales_discounts`;
CREATE TABLE `trans_sales_discounts` (
  `sales_disc_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_disc_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_sales_items`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales_items`;
CREATE TABLE `trans_sales_items` (
  `sales_item_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  `nocharge` int(11) DEFAULT '0',
  `item_name` varchar(255) DEFAULT NULL,
  `is_takeout` int(11) DEFAULT '0',
  PRIMARY KEY (`sales_item_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_sales_local_tax`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales_local_tax`;
CREATE TABLE `trans_sales_local_tax` (
  `sales_local_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_local_tax_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `trans_sales_loyalty_points`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales_loyalty_points`;
CREATE TABLE `trans_sales_loyalty_points` (
  `loyalty_point_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL,
  `code` varchar(150) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `points` double DEFAULT '0',
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`loyalty_point_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `trans_sales_menus`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales_menus`;
CREATE TABLE `trans_sales_menus` (
  `sales_menu_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  `nocharge` int(11) DEFAULT '0',
  `menu_name` varchar(255) DEFAULT NULL,
  `free_reason` varchar(255) DEFAULT NULL,
  `is_takeout` int(11) DEFAULT '0',
  PRIMARY KEY (`sales_menu_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_sales_menu_modifiers`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales_menu_modifiers`;
CREATE TABLE `trans_sales_menu_modifiers` (
  `sales_mod_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `menu_name` varchar(255) DEFAULT NULL,
  `mod_group_name` varchar(255) DEFAULT NULL,
  `mod_name` varchar(255) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_mod_id`),
  KEY `sales_id` (`sales_id`) USING BTREE,
  KEY `menu_id` (`menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_sales_no_tax`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales_no_tax`;
CREATE TABLE `trans_sales_no_tax` (
  `sales_no_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_no_tax_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_sales_payments`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales_payments`;
CREATE TABLE `trans_sales_payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`payment_id`),
  KEY `sales_id` (`sales_id`) USING BTREE,
  KEY `payment_type` (`payment_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_sales_tax`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales_tax`;
CREATE TABLE `trans_sales_tax` (
  `sales_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_tax_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_sales_zero_rated`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales_zero_rated`;
CREATE TABLE `trans_sales_zero_rated` (
  `sales_zero_rated_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `pos_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `card_no` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sales_zero_rated_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_spoilage`
-- ----------------------------
DROP TABLE IF EXISTS `trans_spoilage`;
CREATE TABLE `trans_spoilage` (
  `spoil_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`spoil_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_spoilage_details`
-- ----------------------------
DROP TABLE IF EXISTS `trans_spoilage_details`;
CREATE TABLE `trans_spoilage_details` (
  `spoil_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `spoil_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `uom` varchar(0) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`spoil_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_types`
-- ----------------------------
DROP TABLE IF EXISTS `trans_types`;
CREATE TABLE `trans_types` (
  `type_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `next_ref` varchar(45) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Table structure for `trans_voids`
-- ----------------------------
DROP TABLE IF EXISTS `trans_voids`;
CREATE TABLE `trans_voids` (
  `void_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_type` int(11) DEFAULT NULL,
  `trans_id` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`void_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `uom`
-- ----------------------------
DROP TABLE IF EXISTS `uom`;
CREATE TABLE `uom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(22) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `num` double DEFAULT '0',
  `to` varchar(22) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `updates`
-- ----------------------------
DROP TABLE IF EXISTS `updates`;
CREATE TABLE `updates` (
  `ctr` int(11) NOT NULL AUTO_INCREMENT,
  `query` longtext,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ctr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `branch_code` varchar(255) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`username`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `waiter_id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `users_copy`
-- ----------------------------
DROP TABLE IF EXISTS `users_copy`;
CREATE TABLE `users_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `branch_code` varchar(255) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_roles`
-- ----------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `access` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `vistamall`
-- ----------------------------
DROP TABLE IF EXISTS `vistamall`;
CREATE TABLE `vistamall` (
  `id` int(11) NOT NULL DEFAULT '0',
  `stall_code` varchar(50) DEFAULT NULL,
  `sales_dep` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  View definition for `view_charges_pershift`
-- ----------------------------
DROP VIEW IF EXISTS `view_charges_pershift`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_charges_pershift` AS select sum(`trans_sales_charges`.`amount`) AS `total_charges`,`trans_sales`.`datetime` AS `datetime`,`trans_sales`.`shift_id` AS `shift_id` from (`trans_sales_charges` left join `trans_sales` on((`trans_sales_charges`.`sales_id` = `trans_sales`.`sales_id`))) where ((`trans_sales`.`datetime` <= '2018-09-21 07:00:00') and (`trans_sales`.`type_id` = '10') and (`trans_sales`.`inactive` = 0) and (`trans_sales`.`trans_ref` is not null) and (not(`trans_sales`.`sales_id` in (select `trans_sales_payments`.`sales_id` from `trans_sales_payments` where (`trans_sales_payments`.`payment_type` = 'chit'))))) group by `trans_sales`.`shift_id`;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `araneta` VALUES ('1','HAPCHAN','30436','141040','C:/ARANETA');
INSERT INTO `ayala` VALUES ('1','6000000002487','MO\' COOKIES','AYA','MO ATC','C:/AYALA/','C:/AYALA/');
INSERT INTO `branch_details` VALUES ('1','1','MAX','MAX','MAX','','','UGF Activity Center, Alabang Town Center, Access Road, Ayala Alabang, Muntinlupa',NULL,'PHP','layout.jpg','0','008-821-864-006','20102213145819891','0','FP102020-53B0270046-00006','ZGST9800200200105','','','06:00:00','23:45:00','1234','190.125.220.1','mag15836hap','maghapex','43A0085434442014110212','      THANK YOU COME AGAIN.         THIS SERVES AS YOUR OFFICIAL RECEIPT','');
INSERT INTO `charges` VALUES ('1','SCHG','Service Charge','9','0','1','0','75'), ('2','DCHG','Delivery Charge','5','0','1','0','75'), ('3','PCHG','Packaging Charge','5','0','0','0','75');
INSERT INTO `charges_19` VALUES ('1','SCHG','Service Charge','9','0','1','1'), ('2','DCHG','Delivery Charge','5','0','1','1'), ('3','PCHG','Packaging Charge','5','0','0','1'), ('4','DF3-6KM','DELIVERY FEE 3-6 KM','149','1','0','0'), ('5','DF6-12KM','DELIVERY FEE 6-12 KM','249','1','0','0'), ('6','DF12-19KM','DELIVERY FEE 12-19 KM','299','1','0','0'), ('7','DF19-25KM','DELIVERY FEE 19-25 KM','399','1','0','0'), ('8','DF0-3KM','DELIVERY FEE 0-3 KM','99','1','0','0'), ('9','DF10','DELIVERY FEE 10','10','1','0','0'), ('10','DF100','DELIVERY FEE 100','100','1','0','0'), ('11','DF1111','DELIVERY FEE 11.11','11','1','0','0'), ('12','MGD0-3KM','MOGO DELIVERY 0-3KM','0','1','0','0'), ('13','MGD3-6KM','MOGO DELIVERY 3-6KM','140','1','0','0'), ('14','MGD6-12KM','MOGO DELIVERY 6-12KM','230','1','0','0'), ('15','MGD12-19KM','MOGO DELIVERY 12-19KM','270','1','0','0'), ('16','MGD19-25KM','MOGO DELIVERY 19-25KM','360','1','0','0'), ('17','(N) DF 0-3 KM','DELIVERY FEE','99','0','0','0'), ('18','(N) DF 3-6 KM','DELIVERY FEE','129','0','0','0'), ('19','(N) DF 6-12 KM','DELIVERY FEE','179','0','0','0'), ('20','(N) DF 12-19 KM','DELIVERY FEE','239','0','0','0'), ('21','(N) DF 19-25 KM','DELIVERY FEE','299','1','0','0');
INSERT INTO `conversations` VALUES ('1','1','2','2015-05-06 10:57:25','0'), ('3','1','3','2015-05-06 12:28:55','0');
INSERT INTO `conversation_messages` VALUES ('1','1','1','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus dictum sapien eget nunc viverra, nec consequat lectus hendrerit. Etiam vel ante gravida, pellentesque ex nec, pretium neque. Pellentesque finibus purus diam, ac condimentum augue fermentum et. Ut neque nisi, hendrerit id laoreet fermentum, condimentum at dolor. Pellentesque aliquet tellus quis ullamcorper maximus. Donec finibus lectus sem, id pulvinar lectus pulvinar ut. ',NULL,'2015-05-06 10:57:25','0'), ('3','3','1','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus dictum sapien eget nunc viverra, nec consequat lectus hendrerit. Etiam vel ante gravida, pellentesque ex nec, pretium neque. Pellentesque finibus purus diam, ac condimentum augue fermentum et. Ut neque nisi, hendrerit id laoreet fermentum, condimentum at dolor. Pellentesque aliquet tellus quis ullamcorper maximus. Donec finibus lectus sem, id pulvinar lectus pul',NULL,'2015-05-06 12:28:55','0'), ('4','3','1','tristique, odio id scelerisque sollicitudin, diam massa lobortis enim, in faucibus nisi leo at dui. Proin ornare eleifend risus, ut condimentum metus porttitor non. Donec',NULL,'2015-05-06 12:34:46','0'), ('5','1','1','asdas asd ',NULL,'2015-05-06 12:40:24','0'), ('6','1','1','asda dsa asd asd ',NULL,'2015-05-06 12:47:25','0'), ('7','3','1',' asd asd asd ',NULL,'2015-05-06 12:47:58','0'), ('8','3','1',' asd  asd asd asd asd ',NULL,'2015-05-06 12:48:08','0'), ('9','3','1',' asd asd ',NULL,'2015-05-06 12:48:41','0'), ('10','3','1',' asd asd ',NULL,'2015-05-06 12:49:17','0'), ('11','3','1',' asd asd  asd asd ',NULL,'2015-05-06 12:49:25','0'), ('12','3','1',' asd asd  asd asd  asd ',NULL,'2015-05-06 12:49:38','0'), ('13','3','1','asd  sa s a',NULL,'2015-05-06 12:49:45','0'), ('14','3','1',' asd asd ',NULL,'2015-05-06 12:50:16','0'), ('15','3','1','asd asd asd ',NULL,'2015-05-06 12:50:54','0'), ('16','3','1','asd asd asd ',NULL,'2015-05-06 12:52:55','0'), ('17','3','3','asd asd a dsa asd ',NULL,'2015-05-06 12:53:10','0'), ('18','3','1',' asd asd asd ',NULL,'2015-05-06 12:53:41','0'), ('19','3','3','da sda sd asd ',NULL,'2015-05-06 12:54:41','0'), ('20','3','1','asd asd asd 1 123 123 asd asd ',NULL,'2015-05-06 12:55:39','0'), ('21','3','1','12 asd asd asd asd ',NULL,'2015-05-06 12:56:41','0'), ('22','3','1','asd asd asd asd ',NULL,'2015-05-06 13:07:57','0'), ('23','3','1','asd asd asd asd ',NULL,'2015-05-06 13:07:58','0'), ('24','3','1','asd asd asd ',NULL,'2015-05-06 13:13:55','0'), ('25','3','1','sd asd asd ',NULL,'2015-05-06 13:14:13','0'), ('26','3','1','asd asd asd  asd ',NULL,'2015-05-06 13:14:31','0'), ('27','3','1','sad asd asd 1  asd asd ',NULL,'2015-05-06 13:14:48','0'), ('28','1','1','a sd asd asd ',NULL,'2015-05-06 13:23:07','0'), ('29','1','1','sdas  asd asd asd ',NULL,'2015-05-06 13:23:11','0'), ('30','1','1',' asd 213 sd asd ',NULL,'2015-05-06 13:23:16','0'), ('31','3','1',' asd 12 asd asd ',NULL,'2015-05-06 13:23:20','0'), ('32','3','1',' 3 qwe asd asd 13 123 ',NULL,'2015-05-06 13:23:25','0'), ('33','3','1',' asd asd 123 123 123 ',NULL,'2015-05-06 13:23:35','0'), ('34','1','1',' 123 12 3asd asd 123 ',NULL,'2015-05-06 13:24:05','0'), ('35','3','1','123 123 asd as 123 ',NULL,'2015-05-06 13:24:09','0'), ('36','3','1','13 12 asd 12 3123 asd ',NULL,'2015-05-06 13:25:35','0'), ('37','1','1',' 123 123 ad 123 12 3asd ',NULL,'2015-05-06 13:25:56','0');
INSERT INTO `currencies` VALUES ('1','PHP','Philippine Peso','0'), ('2','USD','US Dollars','0'), ('3','YEN','Japanese Yen','0');
INSERT INTO `customers` VALUES ('2','09173242668','gayap@gmail.com','Gladys','','Ayap','',NULL,'1','1','1',NULL,'1','0',NULL), ('3','09171843232','amanda@gmail.com','amanda','','schoof','',NULL,'1','1','1',NULL,'1','0',NULL), ('4','09178550946','fmiras@gmail.com','feli','','miras','',NULL,'1','1','1',NULL,'1','0',NULL);
INSERT INTO `denominations` VALUES ('1','One Thousand','1000',NULL), ('2','Five Hundreds','500',NULL), ('3','Two Hundreds','200',NULL), ('4','One Hundreds','100',NULL), ('5','Fifty','50',NULL), ('6','Twenty','20',NULL), ('7','Ten','10',NULL), ('8','Five','5',NULL), ('9','One','1',NULL), ('10','Twenty Five Cents','0.25',NULL), ('11','Ten Cents','0.1',NULL);
INSERT INTO `discount_requests` VALUES ('1','MOMENTS','3 Chiz Ensaymada w/Raclet','2','1','310','310','ABCDE123456','1',NULL,'1','Jessie Alison','2020-02-24 15:22:29',NULL,NULL,'10','0'), ('2','MOMENTS','Add Brown Rice','12','1','30','30',NULL,'0',NULL,'1','Jessie Alison','2020-02-24 16:07:46',NULL,NULL,'10','0'), ('3','MOMENTS','Daing na Bangsilog','11','1','265','265',NULL,'0',NULL,'1','Jessie Alison','2020-02-24 16:13:00',NULL,NULL,'10','0'), ('4','MOMENTS',NULL,NULL,NULL,NULL,'0',NULL,'0',NULL,'1','Jessie Alison','2020-02-24 16:19:11',NULL,NULL,NULL,NULL), ('5','MOMENTS','3 Chiz Ensaymada w/Raclet','2','2','310','620',NULL,'0',NULL,'1','Jessie Alison','2020-02-24 16:25:56',NULL,NULL,'10','0');
INSERT INTO `dtr_scheduler` VALUES ('1','1','2014-10-28','2'), ('3','3','2014-10-28','2'), ('4','4','2014-10-28','2'), ('5','5','2014-10-28','2'), ('14','6','2014-10-28','2'), ('15','1','2014-10-29','2'), ('16','3','2014-10-29','2'), ('17','4','2014-10-29','2'), ('18','5','2014-10-29','2'), ('19','6','2014-10-29','2'), ('22','1','2014-10-30','3'), ('23','3','2014-10-30','3'), ('24','5','2014-10-30','5'), ('25','1','2014-10-31','2'), ('26','3','2014-10-31','5'), ('27','4','2014-10-31','4'), ('28','5','2014-10-31','5'), ('29','6','2014-10-31','4'), ('30','1','2014-11-01','2'), ('31','3','2014-11-01','5'), ('32','4','2014-11-01','4'), ('33','5','2014-11-01','5'), ('34','6','2014-11-01','4'), ('35','1','2014-11-02','2'), ('36','4','2014-11-02','6'), ('37','6','2014-11-02','6'), ('38','5','2014-11-12','2'), ('39','6','2014-11-12','2'), ('40','5','2014-11-13','2'), ('41','6','2014-11-13','2'), ('42','5','2014-11-14','2'), ('43','6','2014-11-14','2'), ('44','16','2014-11-24','7'), ('45','17','2014-11-24','7'), ('46','18','2014-11-24','7'), ('47','16','2014-11-25','7'), ('48','17','2014-11-25','7'), ('49','18','2014-11-25','7'), ('50','16','2014-11-26','7'), ('51','17','2014-11-26','7'), ('52','18','2014-11-26','7'), ('53','16','2014-11-27','7'), ('54','17','2014-11-27','7'), ('55','18','2014-11-27','7'), ('56','16','2014-11-28','7'), ('57','17','2014-11-28','7'), ('58','18','2014-11-28','7'), ('59','16','2014-11-29','7'), ('60','17','2014-11-29','7'), ('61','18','2014-11-29','7'), ('62','16','2014-11-30','7'), ('63','17','2014-11-30','7'), ('64','18','2014-11-30','7'), ('65','19','2014-11-24','7'), ('66','19','2014-11-25','7'), ('67','19','2014-11-26','7'), ('68','19','2014-11-27','7'), ('69','19','2014-11-28','7'), ('70','19','2014-11-29','7'), ('71','19','2014-11-30','7');
INSERT INTO `dtr_shifts` VALUES ('1','RESTDAY','Rest Day','00:00:00','00:00:00','00:00:00','00:00:00','0','0','0','00:00:00',NULL), ('2','Shift1','restday again','07:00:00','11:00:00','12:00:00','16:00:00','1','9','0','00:00:00','00:30:00'), ('3','6PM7AM','6PM to 7AM','18:00:00','00:00:00','00:00:00','07:00:00','0','13','0','00:00:00','00:00:00'), ('4','7AM7PM','7AM to 7PM','07:00:00','00:00:00','00:00:00','19:00:00','0','12','0','00:15:00','01:00:00'), ('5','7PM7AM','7PM to 7AM','19:00:00','00:00:00','00:00:00','07:00:00','0','-12','0','00:15:00','01:00:00'), ('6','7AM4PM','7AM to 4PM','07:00:00','00:00:00','00:00:00','16:00:00','0','9','0','00:15:00','01:00:00'), ('7','9AM10PM','9AM10PM','09:00:00','00:00:00','00:00:00','22:00:00','1','13','0','00:00:00','00:00:00');
INSERT INTO `eton` VALUES ('1','ABCD1234','C:/ETON');
INSERT INTO `gift_cards` VALUES ('1','123456789000','199','0',NULL,NULL), ('2','789111222000','599','0',NULL,NULL);
INSERT INTO `item_types` VALUES ('1','Not For Resale'), ('2','For Resale');
INSERT INTO `locations` VALUES ('1','313','warehouse','0','301','95','2021-07-27 09:41:27');
INSERT INTO `logs` VALUES ('1','1','Jessie R. Alison  Logged In.',NULL,'2021-07-27 09:38:19','login',NULL,'9'), ('2','1','Jessie R. Alison  Started Shift.','1','2021-07-27 09:41:09','Shift',NULL,'9'), ('3','1','Jessie R. Alison  Cash in 5000',NULL,'2021-07-27 09:41:10','Drawer',NULL,'9'), ('4','1','Jessie R. Alison  Added New Sales Order #1','1','2021-07-27 09:41:17','Sales Order',NULL,'9'), ('5','1','Jessie R. Alison  Added Payment 1095.00 on Sales Order #1','1','2021-07-27 09:41:20','Sales Order',NULL,'9'), ('6','1','Jessie R. Alison  Settled Payment on Sales Order #1 Reference #00000001','1','2021-07-27 09:41:20','Sales Order',NULL,'9'), ('7','1','Jessie R. Alison  Printed Receipt on Sales Order #1 Reference #00000001','1','2021-07-27 09:41:25','Sales Order',NULL,'20'), ('8','1','Jessie R. Alison  Added New Sales Order #2','2','2021-07-27 09:41:55','Sales Order',NULL,'20'), ('9','1','Jessie R. Alison  Added Payment 1095.00 on Sales Order #2','2','2021-07-27 09:41:58','Sales Order',NULL,'20'), ('10','1','Jessie R. Alison  Settled Payment on Sales Order #2 Reference #00000002','2','2021-07-27 09:41:58','Sales Order',NULL,'20');
INSERT INTO `master_logs` VALUES ('1','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:22:28',NULL,NULL,NULL,'0'), ('2','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:22:28',NULL,NULL,NULL,'0'), ('3','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:23:23',NULL,NULL,NULL,'0'), ('4','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:23:23',NULL,NULL,NULL,'0'), ('5','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:23:52',NULL,NULL,NULL,'0'), ('6','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:23:53',NULL,NULL,NULL,'0'), ('7','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:25:58',NULL,NULL,NULL,'0'), ('8','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:25:58',NULL,NULL,NULL,'0'), ('9','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:36:34',NULL,NULL,NULL,'0'), ('10','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:38:43',NULL,NULL,NULL,'0'), ('11','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:38:45',NULL,NULL,NULL,'0'), ('12','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:39:20',NULL,NULL,NULL,'0'), ('13','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:40:00',NULL,NULL,NULL,'0'), ('14','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:40:14',NULL,NULL,NULL,'0'), ('15','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:40:15',NULL,NULL,NULL,'0'), ('16','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:40:32',NULL,NULL,NULL,'0'), ('17','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:40:37',NULL,NULL,NULL,'0'), ('18','1','[{\"branch_code\":\"MO-ALABANG\",\"mod_id\":\"21\"}]','modifiers','download','1','0','2021-07-23 15:42:08',NULL,NULL,NULL,'0'), ('19','1','[{\"branch_code\":\"MO-ALABANG\",\"mod_id\":\"21\"}]','modifiers','download','1','0','2021-07-23 15:42:08',NULL,NULL,NULL,'0'), ('20','1','[{\"branch_code\":\"MO-ALABANG\",\"mod_id\":\"21\"}]','modifiers','download_update','1','0','2021-07-23 15:42:08',NULL,NULL,NULL,'0'), ('21','1','[{\"branch_code\":\"MO-ALABANG\",\"mod_id\":\"21\"}]','modifiers','download_update','1','0','2021-07-23 15:42:08',NULL,NULL,NULL,'0'), ('22','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:42:08',NULL,NULL,NULL,'0'), ('23','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:42:08',NULL,NULL,NULL,'0'), ('24','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:43:19',NULL,NULL,NULL,'0'), ('25','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:43:19',NULL,NULL,NULL,'0'), ('26','1','[]','modifier_recipe','download_update','1','0','2021-07-23 15:45:38',NULL,NULL,NULL,'0'), ('27','1','[]','modifier_recipe','download_update','1','0','2021-07-23 15:45:38',NULL,NULL,NULL,'0'), ('28','1','[{\"branch_code\":\"MO-ALABANG\",\"mod_id\":\"21\"}]','modifiers','download_update','1','0','2021-07-23 15:45:38',NULL,NULL,NULL,'0'), ('29','1','[{\"branch_code\":\"MO-ALABANG\",\"mod_id\":\"21\"}]','modifiers','download_update','1','0','2021-07-23 15:45:38',NULL,NULL,NULL,'0'), ('30','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:45:38',NULL,NULL,NULL,'0'), ('31','1',NULL,'master_logs','finish_download','1','0','2021-07-23 15:45:38',NULL,NULL,NULL,'0'), ('32','1','{\"sales_id\":[\"1\"]}','trans_sales','add',NULL,'0','2021-07-26 12:16:08',NULL,NULL,NULL,'0'), ('33','1','[{\"sales_menu_id\":\"1\"}]','trans_sales_menus','add',NULL,'0','2021-07-26 12:16:08',NULL,NULL,NULL,'0'), ('34','1','[{\"sales_no_tax_id\":\"1\",\"sales_id\":\"1\"}]','trans_sales_no_tax','add',NULL,'0','2021-07-26 12:16:08',NULL,NULL,NULL,'0'), ('35','1','[{\"payment_id\":\"1\",\"sales_id\":\"1\"}]','trans_sales_payments','add',NULL,'0','2021-07-26 12:16:09',NULL,NULL,NULL,'0'), ('36','1','[{\"sales_tax_id\":\"1\",\"sales_id\":\"1\"}]','trans_sales_tax','add',NULL,'0','2021-07-26 12:16:09',NULL,NULL,NULL,'0'), ('37','1','[{\"sales_zero_rated_id\":\"1\",\"sales_id\":\"1\"}]','trans_sales_zero_rated','add',NULL,'0','2021-07-26 12:16:09',NULL,NULL,NULL,'0'), ('38','1','[{\"id\":\"1\"}]','trans_refs','add',NULL,'0','2021-07-26 12:16:09',NULL,NULL,NULL,'0'), ('39','1','[{\"sales_id\":\"1\"}]','trans_sales','update',NULL,'0','2021-07-26 12:16:09',NULL,NULL,NULL,'0'), ('40','1','{\"sales_id\":[]}','trans_sales_charges','update',NULL,'0','2021-07-26 12:16:09',NULL,NULL,NULL,'0'), ('41','1','{\"sales_id\":[]}','trans_sales_discounts','update',NULL,'0','2021-07-26 12:16:09',NULL,NULL,NULL,'0'), ('42','1','{\"sales_id\":[\"1\"]}','trans_sales_menus','update',NULL,'0','2021-07-26 12:16:10',NULL,NULL,NULL,'0'), ('43','1','{\"sales_id\":[\"1\"]}','trans_sales_no_tax','update',NULL,'0','2021-07-26 12:16:10',NULL,NULL,NULL,'0'), ('44','1','{\"sales_id\":[\"1\"]}','trans_sales_payments','update',NULL,'0','2021-07-26 12:16:10',NULL,NULL,NULL,'0'), ('45','1','{\"sales_id\":[\"1\"]}','trans_sales_tax','update',NULL,'0','2021-07-26 12:16:10',NULL,NULL,NULL,'0'), ('46','1','{\"sales_id\":[\"1\"]}','trans_sales_zero_rated','update',NULL,'0','2021-07-26 12:16:11',NULL,NULL,NULL,'0'), ('47','1','[{\"loc_id\":\"1\"}]','locations','update',NULL,'0','2021-07-26 12:16:11',NULL,NULL,NULL,'0'), ('48','1','[{\"terminal_id\":\"1\"}]','terminals','update',NULL,'0','2021-07-26 12:16:11',NULL,NULL,NULL,'0'), ('49','1','[{\"menu_cat_id\":\"1\"},{\"menu_cat_id\":\"2\"},{\"menu_cat_id\":\"3\"},{\"menu_cat_id\":\"4\"}]','menu_categories','upload','1','0','2021-07-26 12:17:41',NULL,NULL,NULL,'0'), ('50','1','[{\"menu_cat_id\":\"1\"},{\"menu_cat_id\":\"2\"},{\"menu_cat_id\":\"3\"},{\"menu_cat_id\":\"4\"}]','menu_categories','upload','1','0','2021-07-26 12:17:41',NULL,NULL,NULL,'0'), ('51','1','[{\"branch_code\":\"MAX\",\"menu_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"3\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"4\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"3\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"4\"}]','menu_categories','download_update','1','0','2021-07-26 12:17:41',NULL,NULL,NULL,'0'), ('52','1','[{\"branch_code\":\"MAX\",\"menu_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"3\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"4\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"3\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"4\"}]','menu_categories','download_update','1','0','2021-07-26 12:17:41',NULL,NULL,NULL,'0'), ('53','1','[{\"menu_sub_cat_id\":\"1\"},{\"menu_sub_cat_id\":\"2\"}]','menu_subcategories','upload','1','0','2021-07-26 12:17:41',NULL,NULL,NULL,'0'), ('54','1','[{\"menu_sub_cat_id\":\"1\"},{\"menu_sub_cat_id\":\"2\"}]','menu_subcategories','upload','1','0','2021-07-26 12:17:41',NULL,NULL,NULL,'0'), ('55','1','[{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"2\"}]','menu_subcategories','download_update','1','0','2021-07-26 12:17:42',NULL,NULL,NULL,'0'), ('56','1','[{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"2\"}]','menu_subcategories','download_update','1','0','2021-07-26 12:17:42',NULL,NULL,NULL,'0'), ('57','1','[{\"menu_id\":\"1\"},{\"menu_id\":\"2\"},{\"menu_id\":\"3\"},{\"menu_id\":\"4\"},{\"menu_id\":\"5\"},{\"menu_id\":\"6\"},{\"menu_id\":\"7\"},{\"menu_id\":\"8\"},{\"menu_id\":\"9\"},{\"menu_id\":\"10\"}]','menus','upload','1','0','2021-07-26 12:18:32',NULL,NULL,NULL,'0'), ('58','1','[{\"menu_id\":\"1\"},{\"menu_id\":\"2\"},{\"menu_id\":\"3\"},{\"menu_id\":\"4\"},{\"menu_id\":\"5\"},{\"menu_id\":\"6\"},{\"menu_id\":\"7\"},{\"menu_id\":\"8\"},{\"menu_id\":\"9\"},{\"menu_id\":\"10\"}]','menus','upload','1','0','2021-07-26 12:18:32',NULL,NULL,NULL,'0'), ('59','1',NULL,'master_logs','finish_download','1','0','2021-07-26 12:18:32',NULL,NULL,NULL,'0'), ('60','1',NULL,'master_logs','finish_download','1','0','2021-07-26 12:18:33',NULL,NULL,NULL,'0'), ('61','1','[{\"menu_cat_id\":\"1\"},{\"menu_cat_id\":\"2\"},{\"menu_cat_id\":\"3\"},{\"menu_cat_id\":\"4\"}]','menu_categories','upload','1','0','2021-07-26 12:20:42',NULL,NULL,NULL,'0'), ('62','1','[{\"menu_cat_id\":\"1\"},{\"menu_cat_id\":\"2\"},{\"menu_cat_id\":\"3\"},{\"menu_cat_id\":\"4\"}]','menu_categories','upload','1','0','2021-07-26 12:20:42',NULL,NULL,NULL,'0'), ('63','1','[{\"branch_code\":\"MAX\",\"menu_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"3\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"4\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"3\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"4\"}]','menu_categories','download_update','1','0','2021-07-26 12:20:42',NULL,NULL,NULL,'0'), ('64','1','[{\"branch_code\":\"MAX\",\"menu_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"3\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"4\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"3\"},{\"branch_code\":\"MAX\",\"menu_cat_id\":\"4\"}]','menu_categories','download_update','1','0','2021-07-26 12:20:42',NULL,NULL,NULL,'0'), ('65','1','[{\"menu_sub_cat_id\":\"1\"},{\"menu_sub_cat_id\":\"2\"}]','menu_subcategories','upload','1','0','2021-07-26 12:20:43',NULL,NULL,NULL,'0'), ('66','1','[{\"menu_sub_cat_id\":\"1\"},{\"menu_sub_cat_id\":\"2\"}]','menu_subcategories','upload','1','0','2021-07-26 12:20:43',NULL,NULL,NULL,'0'), ('67','1','[{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"2\"}]','menu_subcategories','download_update','1','0','2021-07-26 12:20:43',NULL,NULL,NULL,'0'), ('68','1','[{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"2\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"1\"},{\"branch_code\":\"MAX\",\"menu_sub_cat_id\":\"2\"}]','menu_subcategories','download_update','1','0','2021-07-26 12:20:43',NULL,NULL,NULL,'0'), ('69','1',NULL,'master_logs','finish_download','1','0','2021-07-26 12:20:43',NULL,NULL,NULL,'0'), ('70','1',NULL,'master_logs','finish_download','1','0','2021-07-26 12:20:43',NULL,NULL,NULL,'0'), ('71','1','[{\"disc_id\":\"1\"},{\"disc_id\":\"2\"},{\"disc_id\":\"3\"}]','receipt_discounts','upload','1','0','2021-07-26 12:23:35',NULL,NULL,NULL,'0'), ('72','1','[{\"disc_id\":\"1\"},{\"disc_id\":\"2\"},{\"disc_id\":\"3\"}]','receipt_discounts','upload','1','0','2021-07-26 12:23:35',NULL,NULL,NULL,'0'), ('73','1','[{\"branch_code\":\"MAX\",\"disc_id\":\"1\"},{\"branch_code\":\"MAX\",\"disc_id\":\"2\"},{\"branch_code\":\"MAX\",\"disc_id\":\"3\"}]','receipt_discounts','download_update','1','0','2021-07-26 12:23:35',NULL,NULL,NULL,'0'), ('74','1','[{\"branch_code\":\"MAX\",\"disc_id\":\"1\"},{\"branch_code\":\"MAX\",\"disc_id\":\"2\"},{\"branch_code\":\"MAX\",\"disc_id\":\"3\"},{\"branch_code\":\"MAX\",\"disc_id\":\"1\"},{\"branch_code\":\"MAX\",\"disc_id\":\"2\"},{\"branch_code\":\"MAX\",\"disc_id\":\"3\"}]','receipt_discounts','download_update','1','0','2021-07-26 12:23:35',NULL,NULL,NULL,'0'), ('75','1','[{\"charge_id\":\"1\"},{\"charge_id\":\"2\"},{\"charge_id\":\"3\"}]','charges','upload','1','0','2021-07-26 12:23:35',NULL,NULL,NULL,'0'), ('76','1','[{\"charge_id\":\"1\"},{\"charge_id\":\"2\"},{\"charge_id\":\"3\"}]','charges','upload','1','0','2021-07-26 12:23:35',NULL,NULL,NULL,'0'), ('77','1',NULL,'master_logs','finish_download','1','0','2021-07-26 12:23:35',NULL,NULL,NULL,'0'), ('78','1',NULL,'master_logs','finish_download','1','0','2021-07-26 12:23:35',NULL,NULL,NULL,'0'), ('79','1','{\"sales_id\":[\"1\"]}','trans_sales','add',NULL,'0','2021-07-27 09:41:24',NULL,NULL,NULL,'0'), ('80','1','[{\"sales_menu_id\":\"1\"},{\"sales_menu_id\":\"2\"},{\"sales_menu_id\":\"3\"}]','trans_sales_menus','add',NULL,'0','2021-07-27 09:41:24',NULL,NULL,NULL,'0'), ('81','1','[{\"sales_no_tax_id\":\"1\",\"sales_id\":\"1\"}]','trans_sales_no_tax','add',NULL,'0','2021-07-27 09:41:24',NULL,NULL,NULL,'0'), ('82','1','[{\"payment_id\":\"1\",\"sales_id\":\"1\"}]','trans_sales_payments','add',NULL,'0','2021-07-27 09:41:25',NULL,NULL,NULL,'0'), ('83','1','[{\"sales_tax_id\":\"1\",\"sales_id\":\"1\"}]','trans_sales_tax','add',NULL,'0','2021-07-27 09:41:25',NULL,NULL,NULL,'0'), ('84','1','[{\"sales_zero_rated_id\":\"1\",\"sales_id\":\"1\"}]','trans_sales_zero_rated','add',NULL,'0','2021-07-27 09:41:25',NULL,NULL,NULL,'0'), ('85','1','[{\"id\":\"1\"}]','trans_refs','add',NULL,'0','2021-07-27 09:41:25',NULL,NULL,NULL,'0'), ('86','1','[{\"res_id\":\"1\",\"branch_code\":\"MAX\",\"branch_name\":\"MAX\",\"branch_desc\":\"MAX\",\"contact_no\":\"\",\"delivery_no\":\"\",\"address\":\"UGF Activity Center, Alabang Town Center, Access Road, Ayala Alabang, Muntinlupa\",\"base_location\":null,\"currency\":\"PHP\",\"image\":\"layout.jpg\",\"inactive\":\"0\",\"tin\":\"008-821-864-006\",\"machine_no\":\"20102213145819891\",\"bir\":\"0\",\"permit_no\":\"FP102020-53B0270046-00006\",\"serial\":\"ZGST9800200200105\",\"email\":\"\",\"website\":\"\",\"store_open\":\"06:00:00\",\"store_close\":\"23:45:00\",\"accrdn\":\"43A0085434442014110212\",\"rec_footer\":\"      THANK YOU COME AGAIN.         THIS SERVES AS YOUR OFFICIAL RECEIPT\"}]','branch_details','migrate',NULL,'0','2021-07-27 09:41:26',NULL,NULL,NULL,'0'), ('87','1','[{\"sales_id\":\"1\"}]','trans_sales','update',NULL,'0','2021-07-27 09:41:26',NULL,NULL,NULL,'0'), ('88','1','{\"sales_id\":[]}','trans_sales_charges','update',NULL,'0','2021-07-27 09:41:26',NULL,NULL,NULL,'0'), ('89','1','{\"sales_id\":[]}','trans_sales_discounts','update',NULL,'0','2021-07-27 09:41:26',NULL,NULL,NULL,'0'), ('90','1','{\"sales_id\":[\"1\",\"1\",\"1\"]}','trans_sales_menus','update',NULL,'0','2021-07-27 09:41:26',NULL,NULL,NULL,'0'), ('91','1','{\"sales_id\":[\"1\"]}','trans_sales_no_tax','update',NULL,'0','2021-07-27 09:41:26',NULL,NULL,NULL,'0'), ('92','1','{\"sales_id\":[\"1\"]}','trans_sales_payments','update',NULL,'0','2021-07-27 09:41:26',NULL,NULL,NULL,'0'), ('93','1','{\"sales_id\":[\"1\"]}','trans_sales_tax','update',NULL,'0','2021-07-27 09:41:26',NULL,NULL,NULL,'0'), ('94','1','{\"sales_id\":[\"1\"]}','trans_sales_zero_rated','update',NULL,'0','2021-07-27 09:41:27',NULL,NULL,NULL,'0'), ('95','1','[{\"loc_id\":\"1\"}]','locations','update',NULL,'0','2021-07-27 09:41:27',NULL,NULL,NULL,'0'), ('96','1','[{\"terminal_id\":\"1\"}]','terminals','update',NULL,'0','2021-07-27 09:41:27',NULL,NULL,NULL,'0'), ('97','1',NULL,'master_logs','finish',NULL,'0','2021-07-27 09:41:28',NULL,NULL,NULL,'0'), ('98','1','{\"sales_id\":[\"2\"]}','trans_sales','add',NULL,'0','2021-07-27 09:42:01',NULL,NULL,NULL,'0'), ('99','1','[{\"id\":\"2\"}]','trans_refs','add',NULL,'0','2021-07-27 09:42:01',NULL,NULL,NULL,'0'), ('100','1','[{\"sales_id\":\"2\"}]','trans_sales','update',NULL,'0','2021-07-27 09:42:01',NULL,NULL,NULL,'0');
INSERT INTO `master_logs` VALUES ('101','1','{\"sales_id\":[]}','trans_sales_charges','update',NULL,'0','2021-07-27 09:42:02',NULL,NULL,NULL,'0'), ('102','1','{\"sales_id\":[]}','trans_sales_discounts','update',NULL,'0','2021-07-27 09:42:02',NULL,NULL,NULL,'0'), ('103','1','{\"sales_id\":[\"1\",\"1\",\"1\",\"2\",\"2\",\"2\"]}','trans_sales_menus','update',NULL,'0','2021-07-27 09:42:02',NULL,NULL,NULL,'0'), ('104','1','{\"sales_id\":[\"1\",\"2\"]}','trans_sales_no_tax','update',NULL,'0','2021-07-27 09:42:02',NULL,NULL,NULL,'0'), ('105','1','{\"sales_id\":[\"1\",\"2\"]}','trans_sales_payments','update',NULL,'0','2021-07-27 09:42:02',NULL,NULL,NULL,'0'), ('106','1','{\"sales_id\":[\"2\"]}','trans_sales_tax','update',NULL,'0','2021-07-27 09:42:03',NULL,NULL,NULL,'0'), ('107','1','{\"sales_id\":[\"1\",\"2\"]}','trans_sales_zero_rated','update',NULL,'0','2021-07-27 09:42:03',NULL,NULL,NULL,'0'), ('108','1',NULL,'master_logs','finish',NULL,'0','2021-07-27 09:42:03',NULL,NULL,NULL,'0');
INSERT INTO `megamall` VALUES ('1','30','110000055','3','SAP','2');
INSERT INTO `megaworld` VALUES ('1','TCLVOR11','01','C:/MEGAWORLD/');
INSERT INTO `menus` VALUES ('1','M01','M01','Max Chicken','Max Chicken','1','1','0','350',NULL,NULL,'0','0','0','57','0',NULL,NULL,'0','max'), ('2','Y01','Y01','Chicken Pizza','Chicken Pizza','1','1','0','380',NULL,NULL,'0','0','0','57','0',NULL,NULL,'0','yellowcab'), ('3','J01','J01','JJ Orange Juice','JJ Orange Juice','4','2','0','120',NULL,NULL,'0','0','0','57','0',NULL,NULL,'0','jambajuice'), ('4','M02','M02','Lemon Juice','Lemon Juice','4','2','0','90',NULL,NULL,'0','0','0','57','0',NULL,NULL,'0','max'), ('5','P01','P01','Iced tea','Iced tea','2','2','0','95',NULL,NULL,'0','0','0','57','0',NULL,NULL,'0','pancakehouse'), ('6','M03','M03','Fried Rice','Fried Rice','3','1','0','120',NULL,NULL,'0','0','0','57','0',NULL,NULL,'0','max'), ('7','P02','P02','Plain Rice','Plain Rice','3','1','0','95',NULL,NULL,'0','0','0','57','0',NULL,NULL,'0','pancakehouse'), ('8','P03','P03','Honey Chicken','Honey Chicken','1','1','0','365',NULL,NULL,'0','0','0','57','0',NULL,NULL,'0','pancakehouse'), ('9','J02','J02','JJ Carrot Juice','JJ Carrot Juice','4','2','0','95',NULL,NULL,'0','0','0','57','0',NULL,NULL,'0','jambajuice'), ('10','M05','M05','Coke in Can','Coke in Can','2','2','0','80',NULL,NULL,'0','0','0','57','0',NULL,NULL,'0','max');
INSERT INTO `menus234` VALUES ('1','52020','52020','LO The Chocolate Chip','LO The Chocolate Chip','1','1','0','67.5',NULL,NULL,'0',NULL,'0',NULL,NULL,'1',NULL,'0'), ('2','52021','52021','LO South Cotabato Chocolate Fudge','LO South Cotabato Chocolate Fudge','1','1','0','80',NULL,NULL,'0',NULL,'0',NULL,NULL,'1',NULL,'0'), ('3','52022','52022','LO Dark Chocolate Macadamia','LO Dark Chocolate Macadamia','1','1','0','95',NULL,NULL,'0',NULL,'0',NULL,NULL,'1',NULL,'0'), ('4','52023','52023','LO White Chocolate Walnut','LO White Chocolate Walnut','1','1','0','80',NULL,NULL,'0',NULL,'0',NULL,NULL,'1',NULL,'0'), ('5','52024','52024','LO Nutella Crinkle','LO Nutella Crinkle','1','1','0','80',NULL,NULL,'0',NULL,'0',NULL,NULL,'1',NULL,'0'), ('6','52520','52520','LO Dark Chocolate Macademia','LO Dark Chocolate Macademia','1','1','0','85',NULL,NULL,'0',NULL,'0',NULL,NULL,'1',NULL,'0'), ('7','525201','525201','LO Triple Choco Mallow','LO Triple Choco Mallow','1','1','0','80',NULL,NULL,'0',NULL,'0',NULL,NULL,'1',NULL,'0'), ('8','525202','525202','LO Walnut Oatmeal','LO Walnut Oatmeal','1','1','0','80',NULL,NULL,'0',NULL,'0',NULL,NULL,'1',NULL,'0'), ('9','525203','525203','LO Naked Oatmeal','LO Naked Oatmeal','1','1','0','67.5',NULL,NULL,'0',NULL,'0',NULL,NULL,'1',NULL,'0'), ('10','525204','525204','LO Dark Chocolate Oatmeal','LO Dark Chocolate Oatmeal','1','1','0','80',NULL,NULL,'0',NULL,'0',NULL,NULL,'1',NULL,'0'), ('12','10410007','10410007','PALM PER HALF DOZEN','PALM PER HALF DOZEN','1','1','0','865',NULL,NULL,'0',NULL,'0',NULL,NULL,'2',NULL,'0'), ('13','10410015','10410015','PALM BOX OF 4','PALM BOX OF 4','1','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'2',NULL,'0'), ('14','10410016','10410016','PALM BOX OF 8','PALM BOX OF 8','1','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'2',NULL,'0'), ('17','10410031','10410031','POUCH','POUCH','1','1','0','5',NULL,NULL,'0',NULL,'0',NULL,NULL,'3',NULL,'0'), ('18','10410032','10410032','BOX OF 4 PALM','BOX OF 4 PALM','1','1','0','15',NULL,NULL,'0',NULL,'0',NULL,NULL,'3',NULL,'0'), ('19','10410033','10410033','BOX OF 8 PALM','BOX OF 8 PALM','1','1','0','20',NULL,NULL,'0',NULL,'0',NULL,NULL,'3',NULL,'0'), ('21','10410041','10410041','RIBBON','RIBBON','1','1','0','15',NULL,NULL,'0',NULL,'0',NULL,NULL,'3',NULL,'0'), ('22','PALMCHOCCHIP','PALMCHOCCHIP','PALM-SIZED CHOCOLATE CHIP COOKIE FREE','PALM-SIZED CHOCOLATE CHIP COOKIE FREE','1','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'4',NULL,'0'), ('23','MOPROMO','MOPROMO','FREE PALM CHOCO CHIP','FREE PALM CHOCO CHIP','1','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'5',NULL,'0'), ('24','MOPROMO2','MOPROMO2','FREE PALM NAKED OATMEAL','FREE PALM NAKED OATMEAL','1','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'5',NULL,'0'), ('25','MOPROMO3','MOPROMO3','GRANDPARENTS DAY MOFO','GRANDPARENTS DAY MOFO','1','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'5',NULL,'0'), ('26','MOPROMO4','MOPROMO4','GRANDPARENTS DAY HOTLINE','GRANDPARENTS DAY HOTLINE','1','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'5',NULL,'0'), ('27','10410042','10410042','NAKED OATMEAL','NAKED OATMEAL','1','1','0','135',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('28','10410043','10410043','DARK CHOCOLATE OATMEAL','DARK CHOCOLATE OATMEAL','1','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('29','10410044','10410044','WALNUT OATMEAL','WALNUT OATMEAL','1','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('33','10410048','10410048','DARK CHOCOLATE OATMEAL','DARK CHOCOLATE OATMEAL','1','1','0','145',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('34','10410049','10410049','NAKED OATMEAL','NAKED OATMEAL','1','1','0','120',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('35','10410050','10410050','WALNUT OATMEAL','WALNUT OATMEAL','1','1','0','145',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('45','10410035','10410035','CHOCOLATE CHIP COOKIE','CHOCOLATE CHIP COOKIE','1','1','0','135',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('46','10410036','10410036','WHITE CHOCOLATE WALNUT','WHITE CHOCOLATE WALNUT','1','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('47','10410037','10410037','DARK CHOCOLATE MACADAMIA','DARK CHOCOLATE MACADAMIA','1','1','0','190',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('48','10410038','10410038','SOUTH COTABATO CHOCOLATE FUDGE','SOUTH COTABATO CHOCOLATE FUDGE','1','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('49','10410039','10410039','TRIPLE CHOCO MALLOW','TRIPLE CHOCO MALLOW','1','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('50','10410040','10410040','NUTELLA CRINKLE','NUTELLA CRINKLE','1','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('57','10410001','10410001','CHOCOLATE CHIP COOKIE','CHOCOLATE CHIP COOKIE','1','1','0','120',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('58','10410002','10410002','WHITE CHOCOLATE WALNUT','WHITE CHOCOLATE WALNUT','1','1','0','145',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('59','10410003','10410003','DARK CHOCOLATE MACADAMIA','DARK CHOCOLATE MACADAMIA','1','1','0','170',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('60','10410004','10410004','SOUTH COTABATO CHOCOLATE FUDGE','SOUTH COTABATO CHOCOLATE FUDGE','1','1','0','145',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('61','10410005','10410005','TRIPLE CHOCO MALLOW','TRIPLE CHOCO MALLOW','1','1','0','145',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('62','10410006','10410006','NUTELLA CRINKLE','NUTELLA CRINKLE','1','1','0','145',NULL,NULL,'0',NULL,'0',NULL,NULL,'6',NULL,'0'), ('63','61520','61520','DELIVERY FEE  0-3 KM','DELIVERY FEE  0-3 KM','2','1','0','99',NULL,NULL,'0',NULL,'0',NULL,NULL,'7',NULL,'0'), ('64','615201','615201','DELIVERY FEE 3-6 KM','DELIVERY FEE 3-6 KM','2','1','0','149',NULL,NULL,'0',NULL,'0',NULL,NULL,'7',NULL,'0'), ('65','615202','615202','DELIVERY FEE 6-12 KM','DELIVERY FEE 6-12 KM','2','1','0','249',NULL,NULL,'0',NULL,'0',NULL,NULL,'7',NULL,'0'), ('66','615203','615203','DELIVERY FEE 12-19 KM','DELIVERY FEE 12-19 KM','2','1','0','299',NULL,NULL,'0',NULL,'0',NULL,NULL,'7',NULL,'0'), ('67','615204','615204','DELIVERY FEE 19-25 KM','DELIVERY FEE 19-25 KM','2','1','0','399',NULL,NULL,'0',NULL,'0',NULL,NULL,'7',NULL,'0'), ('68','5182020','5182020','DELIVERY FEE 100','DELIVERY FEE 100','2','1','0','100',NULL,NULL,'0',NULL,'0',NULL,NULL,'7',NULL,'0'), ('69','51822','51822','DELIVERY FEE 10','DELIVERY FEE 10','2','1','0','10',NULL,NULL,'0',NULL,'0',NULL,NULL,'7',NULL,'0'), ('70','70520','70520','MoGo Delivery 0-3km','MoGo Delivery 0-3km','2','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'8',NULL,'0'), ('71','705201','705201','MoGo Delivery 3-6km','MoGo Delivery 3-6km','2','1','0','140',NULL,NULL,'0',NULL,'0',NULL,NULL,'8',NULL,'0'), ('72','705202','705202','MoGo Delivery 6-12km','MoGo Delivery 6-12km','2','1','0','230',NULL,NULL,'0',NULL,'0',NULL,NULL,'8',NULL,'0'), ('73','705203','705203','MoGo Delivery 12-19km','MoGo Delivery 12-19km','2','1','0','270',NULL,NULL,'0',NULL,'0',NULL,NULL,'8',NULL,'0'), ('74','705204','705204','MoGo Delivery 19-25km','MoGo Delivery 19-25km','2','1','0','360',NULL,NULL,'0',NULL,'0',NULL,NULL,'8',NULL,'0'), ('75','EC000437','EC000437','(EC)PALM PER HALF DOZEN','(EC)PALM PER HALF DOZEN','3','1','0','955',NULL,NULL,'0',NULL,'0',NULL,NULL,'9',NULL,'0'), ('76','EC000444','EC000444','(EC)PALM BOX OF 4','(EC)PALM BOX OF 4','3','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'9',NULL,'0'), ('77','EC000445','EC000445','(EC)PALM BOX OF 8','(EC)PALM BOX OF 8','3','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'9',NULL,'0'), ('85','EC000431','EC000431','(EC)CHOCOLATE CHIP COOKIE','(EC)CHOCOLATE CHIP COOKIE','3','1','0','135',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('86','EC000432','EC000432','(EC)WHITE CHOCOLATE WALNUT','(EC)WHITE CHOCOLATE WALNUT','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('87','EC000433','EC000433','(EC)DARK CHOCOLATE MACADAMIA','(EC)DARK CHOCOLATE MACADAMIA','3','1','0','190',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('88','EC000434','EC000434','(EC)SOUTH COTABATO CHOCOLATE FUDGE','(EC)SOUTH COTABATO CHOCOLATE FUDGE','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('89','EC000435','EC000435','(EC)TRIPLE CHOCO MALLOW','(EC)TRIPLE CHOCO MALLOW','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('90','EC000436','EC000436','(EC)NUTELLA CRINKLE','(EC)NUTELLA CRINKLE','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('91','EC000458','EC000458','(EC)NAKED OATMEAL','(EC)NAKED OATMEAL','3','1','0','135',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('92','EC000459','EC000459','(EC)DARK CHOCOLATE OATMEAL','(EC)DARK CHOCOLATE OATMEAL','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('93','EC000460','EC000460','(EC)WALNUT OATMEAL','(EC)WALNUT OATMEAL','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('97','EC000464','EC000464','(EC)DARK CHOCOLATE OATMEAL','(EC)DARK CHOCOLATE OATMEAL','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('98','EC000465','EC000465','(EC)NAKED OATMEAL','(EC)NAKED OATMEAL','3','1','0','135',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('99','EC000466','EC000466','(EC)WALNUT OATMEAL','(EC)WALNUT OATMEAL','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('109','EC000476','EC000476','(EC)South Cotabato Chocolate Fudge','(EC)South Cotabato Chocolate Fudge','3','1','0','190',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('110','EC000477','EC000477','(EC)Dark Chocolate Macadamia','(EC)Dark Chocolate Macadamia','3','1','0','190',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('112','EC000451','EC000451','(EC)Triple Choco Mallow','(EC)Triple Choco Mallow','3','1','0','135',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('113','EC000452','EC000452','(EC)Nutella Crinkle','(EC)Nutella Crinkle','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('114','EC000453','EC000453','(EC)Naked Oatmeal','(EC)Naked Oatmeal','3','1','0','190',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('115','EC000454','EC000454','(EC)Walnut Oatmeal','(EC)Walnut Oatmeal','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('116','EC000455','EC000455','(EC)Dark Chocolate Oatmeal','(EC)Dark Chocolate Oatmeal','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('117','EC000456','EC000456','(EC)Mo Pre-Assorted Box of 4 Palms','(EC)Mo Pre-Assorted Box of 4 Palms','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'10',NULL,'0'), ('119','516211','516211','(EC)RIBBON','(EC)RIBBON','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'11',NULL,'0'), ('120','516212','516212','(EC)POUCH','(EC)POUCH','3','1','0','190',NULL,NULL,'0',NULL,'0',NULL,NULL,'11',NULL,'0'), ('121','516213','516213','(EC)BOX OF 4 PALM','(EC)BOX OF 4 PALM','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'11',NULL,'0'), ('122','516214','516214','(EC)BOX OF 8 PALM','(EC)BOX OF 8 PALM','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'11',NULL,'0'), ('124','516216','516216','(GF)PALM PER HALF DOZEN','(GF)PALM PER HALF DOZEN','3','1','0','135',NULL,NULL,'0',NULL,'0',NULL,NULL,'11',NULL,'0'), ('125','516217','516217','(GF)PALM BOX OF 4','(GF)PALM BOX OF 4','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'11',NULL,'0'), ('126','516218','516218','(GF)PALM BOX OF 8','(GF)PALM BOX OF 8','3','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'11',NULL,'0'), ('128','516220','516220','(GF)CHOCOLATE CHIP COOKIE','(GF)CHOCOLATE CHIP COOKIE','3','1','0','275',NULL,NULL,'0',NULL,'0',NULL,NULL,'11',NULL,'0'), ('129','EC000457','EC000457','(GF)WHITE CHOCOLATE WALNUT','(GF)WHITE CHOCOLATE WALNUT','3','1','0','15',NULL,NULL,'0',NULL,'0',NULL,NULL,'12',NULL,'0'), ('130','EC000447','EC000447','(GF)DARK CHOCOLATE MACADAMIA','(GF)DARK CHOCOLATE MACADAMIA','3','1','0','5',NULL,NULL,'0',NULL,'0',NULL,NULL,'12',NULL,'0'), ('131','EC000448','EC000448','(GF)SOUTH COTABATO CHOCOLATE FUDGE','(GF)SOUTH COTABATO CHOCOLATE FUDGE','3','1','0','15',NULL,NULL,'0',NULL,'0',NULL,NULL,'12',NULL,'0'), ('132','EC000449','EC000449','(GF)TRIPLE CHOCO MALLOW','(GF)TRIPLE CHOCO MALLOW','3','1','0','20',NULL,NULL,'0',NULL,'0',NULL,NULL,'12',NULL,'0'), ('133','EC000450','EC000450','(GF)NUTELLA CRINKLE','(GF)NUTELLA CRINKLE','3','1','0','10',NULL,NULL,'0',NULL,'0',NULL,NULL,'12',NULL,'0'), ('140','GF10410082','GF10410082','(GF)CHOCOLATE CHIP COOKIE','(GF)CHOCOLATE CHIP COOKIE','4','1','0','190',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('141','GF10410083','GF10410083','(GF)WHITE CHOCOLATE WALNUT','(GF)WHITE CHOCOLATE WALNUT','4','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('142','GF10410084','GF10410084','(GF)DARK CHOCOLATE MACADAMIA','(GF)DARK CHOCOLATE MACADAMIA','4','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('143','GF10410085','GF10410085','(GF)SOUTH COTABATO CHOCOLATE FUDGE','(GF)SOUTH COTABATO CHOCOLATE FUDGE','4','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('144','GF10410067','GF10410067','(GF)TRIPLE CHOCO MALLOW','(GF)TRIPLE CHOCO MALLOW','4','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('145','GF10410068','GF10410068','(GF)NUTELLA CRINKLE','(GF)NUTELLA CRINKLE','4','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('146','GF10410069','GF10410069','(GF)NAKED OATMEAL','(GF)NAKED OATMEAL','4','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('147','GF10410070','GF10410070','(GF)DARK CHOCOLATE OATMEAL','(GF)DARK CHOCOLATE OATMEAL','4','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('148','GF10410071','GF10410071','(GF)WALNUT OATMEAL','(GF)WALNUT OATMEAL','4','1','0','0',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('152','GF10410062','GF10410062','(GF)DARK CHOCOLATE OATMEAL','(GF)DARK CHOCOLATE OATMEAL','4','1','0','190',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('153','GF10410063','GF10410063','(GF)NAKED OATMEAL','(GF)NAKED OATMEAL','4','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('154','GF10410064','GF10410064','(GF)WALNUT OATMEAL','(GF)WALNUT OATMEAL','4','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0');
INSERT INTO `menus234` VALUES ('155','GF10410065','GF10410065','(GF)RIBBON','(GF)RIBBON','4','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('156','GF10410087','GF10410087','(GF)POUCH','(GF)POUCH','4','1','0','135',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('157','GF10410088','GF10410088','(GF)BOX OF 4 PALM','(GF)BOX OF 4 PALM','4','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('158','GF10410089','GF10410089','(GF)BOX OF 8 PALM','(GF)BOX OF 8 PALM','4','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('163','GF10410094','GF10410094','(FP)PALM PER HALF DOZEN','(FP)PALM PER HALF DOZEN','4','1','0','135',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('164','GF10410095','GF10410095','(FP)POUCH','(FP)POUCH','4','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'14',NULL,'0'), ('175','GF10410076','GF10410076','(FP)DARK CHOCOLATE OATMEAL','(FP)DARK CHOCOLATE OATMEAL','4','1','0','5',NULL,NULL,'0',NULL,'0',NULL,NULL,'15',NULL,'0'), ('176','GF10410077','GF10410077','(FP)NAKED OATMEAL','(FP)NAKED OATMEAL','4','1','0','15',NULL,NULL,'0',NULL,'0',NULL,NULL,'15',NULL,'0'), ('177','GF10410078','GF10410078','(FP)WALNUT OATMEAL','(FP)WALNUT OATMEAL','4','1','0','20',NULL,NULL,'0',NULL,'0',NULL,NULL,'15',NULL,'0'), ('178','GF10410079','GF10410079','(FP)CHOCOLATE CHIP COOKIE','(FP)CHOCOLATE CHIP COOKIE','4','1','0','10',NULL,NULL,'0',NULL,'0',NULL,NULL,'15',NULL,'0'), ('181','10410075','10410075','(FP)SOUTH COTABATO CHOCOLATE FUDGE','(FP)SOUTH COTABATO CHOCOLATE FUDGE','5','1','0','275',NULL,NULL,'0',NULL,'0',NULL,NULL,'16',NULL,'0'), ('182','MO10410066','MO10410066','(FP)TRIPLE CHOCO MALLOW','(FP)TRIPLE CHOCO MALLOW','5','1','0','955',NULL,NULL,'0',NULL,'0',NULL,NULL,'16',NULL,'0'), ('183','10410076','10410076','(FP)NUTELLA CRINKLE','(FP)NUTELLA CRINKLE','5','1','0','5',NULL,NULL,'0',NULL,'0',NULL,NULL,'17',NULL,'0'), ('190','10410089','10410089','(FP)CHOCOLATE CHIP COOKIE','(FP)CHOCOLATE CHIP COOKIE','5','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'18',NULL,'0'), ('194','10410093','10410093','(FP)TRIPLE CHOCO MALLOW','(FP)TRIPLE CHOCO MALLOW','5','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'18',NULL,'0'), ('195','10410094','10410094','(FP)NUTELLA CRINKLE','(FP)NUTELLA CRINKLE','5','1','0','135',NULL,NULL,'0',NULL,'0',NULL,NULL,'18',NULL,'0'), ('196','10410095','10410095','MoGo Gelivery Fee','MoGo Gelivery Fee','5','1','0','160',NULL,NULL,'0',NULL,'0',NULL,NULL,'18',NULL,'0'), ('248','10410096','10410096','Box of 4','Box of 4','5','1','0','0',NULL,NULL,'0','0','0',NULL,'0','16',NULL,'0'), ('249','10410097','10410097','Box of 8','Box of 8','4','1','0','0',NULL,NULL,'0','0','0',NULL,'0','13',NULL,'0'), ('250','10410098','10410098','4 Palms','4 Palms','1','1','0','545',NULL,NULL,'0','0','0',NULL,'0','2',NULL,'0'), ('251','EC000451','EC000451','Box of 4','Box of 4','3','1','0','0',NULL,NULL,'0','0','0',NULL,'0','9',NULL,'0'), ('252','EC000452','EC000452','Box of 8','Box of 8','3','1','0','0',NULL,NULL,'0','0','0',NULL,'0','9',NULL,'0'), ('253',' 10410100',' 10410100','Box of 4','Box of 4','4','1','0','0',NULL,NULL,'0','0','0',NULL,'0','13',NULL,'0'), ('254','10410097','10410097','Box of 8','Box of 8','5','1','0','0',NULL,NULL,'0','0','0',NULL,'0','16',NULL,'0'), ('255','PS000001','PS0000001','CHOCOLATE HAZELNUT CRUNCH','CHOCOLATE HAZELNUT CRUNCH\r\n','8','1','0','185',NULL,NULL,'0','0','0',NULL,'0','20',NULL,'0'), ('256','PS[000002]','PS0000002','PEANUT BUTTER CHUNK','PEANUT BUTTER CHUNK\r\n','8','1','0','185',NULL,NULL,'0','0','0',NULL,'0','20',NULL,'0'), ('257','PS000003','PS0000003','DARK CHOCO ORANGE FUDGE','DARK CHOCO ORANGE FUDGE\r\n','8','1','0','185',NULL,NULL,'0','0','0',NULL,'0','20',NULL,'0'), ('258','B00001','B00001','THE CHOCOLATE CHIP','THE CHOCOLATE CHIP\r\n','9','1','0','150',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('259','B00002','B00002','SOUTH COTABATO CHOCOLATE FUDGE','SOUTH COTABATO CHOCOLATE FUDGE\r\n','9','1','0','175',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('260','B00003','B00003','DARK CHOCOLATE MACADAMIA','DARK CHOCOLATE MACADAMIA\r\n','9','1','0','205',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('261','B00004','B00004','WHITE CHOCOLATE WALNUT','WHITE CHOCOLATE WALNUT\r\n','9','1','0','175',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('262','B00005','B00005','TRIPLE CHOCO MALLOW','TRIPLE CHOCO MALLOW\r\n','9','1','0','175',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('263','B00006','B00006','NUTELLA CRINKLE','NUTELLA CRINKLE\r\n','9','1','0','175',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('264','B00007','B00007','NAKED OATMEAL','NAKED OATMEAL\r\n','9','1','0','150',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('265','B00008','B00008','DARK CHOCOLATE OATMEAL','DARK CHOCOLATE OATMEAL\r\n','9','1','0','175',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('266','B00009','B00009','WALNUT OATMEAL','WALNUT OATMEAL\r\n','9','1','0','175',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('267','B00010','B00010','CHOCOLATE HAZELNUT CRUNCH','CHOCOLATE HAZELNUT CRUNCH\r\n','9','1','0','200',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('268','B00011','B00011','PEANUT BUTTER CHUNK','PEANUT BUTTER CHUNK\r\n','9','1','0','200',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('269','B00012','B00012','DARK CHOCO ORANGE FUDGE','DARK CHOCO ORANGE FUDGE\r\n','9','1','0','200',NULL,NULL,'0','0','0',NULL,'0','21',NULL,'0'), ('270','B00013','B00013','CLASSIC','CLASSIC\r\n','9','1','0','530',NULL,NULL,'0','0','0',NULL,'0','22',NULL,'0'), ('271','B00014','B00014','ASSORTED','ASSORTED\r\n','9','1','0','580',NULL,NULL,'0','0','0',NULL,'0','22',NULL,'0'), ('272','B00015','B00015','SUPERCHIP','SUPERCHIP\r\n','9','1','0','680',NULL,NULL,'0','0','0',NULL,'0','22',NULL,'0'), ('273','ADD00001','ADD00001','BIRTHDAY COOKIE','BIRTHDAY COOKIE','10','1','0','88',NULL,NULL,'0','0','0',NULL,'0',NULL,NULL,'0'), ('274','code1','code1','Crumb Size','Crumb Size','3','1','0','275',NULL,NULL,'0','0','0',NULL,'0','9',NULL,'0'), ('275','code2','code2','GF CRUMB SIZE','GF CRUMB SIZE','4','1','0','275',NULL,NULL,'0','0','0',NULL,'0','13',NULL,'0'), ('276','CODE3','CODE3','FP CRUMB SIZE','FP CRUMB SIZE','5','1','0','275',NULL,NULL,'0','0','0',NULL,'0','16',NULL,'0'), ('278','code5','code5','EC  CRUMB BOXES','EC  CRUMB BOXES','3','1','0','10',NULL,NULL,'0','0','0',NULL,'0','12',NULL,'0');
INSERT INTO `menu_categories` VALUES ('1','Chickens','0',NULL,'0','0','0'), ('2','Drinks','0',NULL,'0','0','0'), ('3','Extras','0',NULL,'0','0','0'), ('4','Juice','0',NULL,'0','0','0');
INSERT INTO `menu_subcategories` VALUES ('1','FOOD',NULL,'0','0'), ('2','BEVERAGES',NULL,'0','0');
INSERT INTO `miaa` VALUES ('1','T3VMMARE','09','C:/MIAA/');
INSERT INTO `promo_free` VALUES ('1','Free Pork Siomai D','Free Pork Siomai D','34','1000','1','0');
INSERT INTO `promo_free_menus` VALUES ('2','1','12','1');
INSERT INTO `receipt_discounts` VALUES ('1','SNDISC','Senior Citizen Discount','20','1','0','0',NULL,'2021-07-26 12:23:17','0'), ('2','PWDISC','Person WIth Disability','20','1','0','0',NULL,'2021-07-26 12:23:18','0'), ('3','DIPLOMAT','DIPLOMAT','0','1','0','0',NULL,'2021-07-26 12:23:20','0');
INSERT INTO `settings` VALUES ('1','1','0','3=>counter,6=>takeout,8=>food panda,18=>grabfood,20=>pickaroo','0','','','0','0','','100','10','','1','0','0');
INSERT INTO `shangrila` VALUES ('1','VIA_MARE','AYL','C:/Shangrila/');
INSERT INTO `shifts` VALUES ('1','1','1','2021-07-27 09:41:09',NULL,NULL,NULL,'1',NULL,'10','2021-07-27 09:41:09');
INSERT INTO `shift_entries` VALUES ('1','1','1','5000','1','2021-07-27 09:41:09',NULL,'11','2021-07-27 09:41:09',NULL);
INSERT INTO `stalucia` VALUES ('1','123');
INSERT INTO `sync_logs` VALUES ('1','trans_sales','add','1','2021-07-27 09:41:21','0',NULL,'0'), ('2','trans_sales_menus','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('3','trans_sales_no_tax','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('4','trans_sales_payments','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('5','trans_sales_tax','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('6','trans_sales_zero_rated','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('7','trans_refs','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('8','users','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('9','logs','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('10','shifts','add','1','2021-07-27 09:41:23',NULL,NULL,'0'), ('11','shift_entries','add','1','2021-07-27 09:41:23',NULL,NULL,'0'), ('12','sync_logs','finish','1','2021-07-27 09:41:23',NULL,NULL,'0'), ('13','trans_sales','add','1','2021-07-27 09:41:58','0',NULL,'0'), ('14','trans_sales_menus','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('15','trans_sales_no_tax','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('16','trans_sales_payments','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('17','trans_sales_tax','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('18','trans_sales_zero_rated','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('19','trans_refs','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('20','logs','add','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('21','trans_sales','update','1','2021-07-27 09:42:00','0',NULL,'0'), ('22','trans_sales_charges','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('23','trans_sales_menus','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('24','trans_sales_no_tax','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('25','trans_sales_payments','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('26','trans_sales_tax','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('27','trans_sales_zero_rated','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('28','sync_logs','finish','1','2021-07-27 09:42:01',NULL,NULL,'0');
INSERT INTO `sync_logs_copy` VALUES ('1','trans_sales','add','1','2020-03-11 10:21:32','0','1','0'), ('2','trans_sales_charges','add','1','2020-03-11 10:21:32',NULL,'1','0'), ('3','trans_sales_menus','add','1','2020-03-11 10:21:33',NULL,'1','0'), ('4','trans_sales_no_tax','add','1','2020-03-11 10:21:33',NULL,'1','0'), ('5','trans_sales_payments','add','1','2020-03-11 10:21:33',NULL,'1','0'), ('6','trans_sales_tax','add','1','2020-03-11 10:21:33',NULL,'1','0'), ('7','trans_sales_zero_rated','add','1','2020-03-11 10:21:33',NULL,'1','0'), ('8','trans_refs','add','1','2020-03-11 10:21:34',NULL,'1','0'), ('9','users','add','1','2020-03-11 10:21:34',NULL,'1','0'), ('10','logs','add','1','2020-03-11 10:21:34',NULL,'1','0'), ('11','shifts','add','1','2020-03-11 10:21:34',NULL,'1','0'), ('12','shift_entries','add','1','2020-03-11 10:21:35',NULL,'1','0'), ('13','sync_logs','finish','1','2020-03-11 10:21:35',NULL,'1','0'), ('14','sync_logs','finish','1','2020-03-11 10:21:38',NULL,'1','0'), ('15','trans_sales','add','1','2020-03-11 10:22:50','0','1','0'), ('16','trans_sales_charges','add','1','2020-03-11 10:22:50',NULL,'1','0'), ('17','trans_sales_menus','add','1','2020-03-11 10:22:51',NULL,'1','0'), ('18','trans_sales_no_tax','add','1','2020-03-11 10:22:51',NULL,'1','0'), ('19','trans_sales_payments','add','1','2020-03-11 10:22:51',NULL,'1','0'), ('20','trans_sales_tax','add','1','2020-03-11 10:22:51',NULL,'1','0'), ('21','trans_sales_zero_rated','add','1','2020-03-11 10:22:51',NULL,'1','0'), ('22','trans_refs','add','1','2020-03-11 10:22:51',NULL,'1','0'), ('23','logs','add','1','2020-03-11 10:22:51',NULL,'1','0'), ('24','trans_sales','update','1','2020-03-11 10:22:52','0','1','0'), ('25','trans_sales_charges','update','1','2020-03-11 10:22:52',NULL,'1','0'), ('26','trans_sales_menus','update','1','2020-03-11 10:22:52',NULL,'1','0'), ('27','trans_sales_no_tax','update','1','2020-03-11 10:22:52',NULL,'1','0'), ('28','trans_sales_payments','update','1','2020-03-11 10:22:52',NULL,'1','0'), ('29','trans_sales_tax','update','1','2020-03-11 10:22:53',NULL,'1','0'), ('30','trans_sales_zero_rated','update','1','2020-03-11 10:22:53',NULL,'1','0'), ('31','sync_logs','finish','1','2020-03-11 10:22:53',NULL,'1','0'), ('32','read_details','add','1','2020-03-11 10:35:34',NULL,'1','0'), ('33','logs','add','1','2020-03-11 10:35:34',NULL,'1','0'), ('34','cashout_details','add','1','2020-03-11 10:35:34',NULL,'1','0'), ('35','cashout_entries','add','1','2020-03-11 10:35:34',NULL,'1','0'), ('36','trans_sales','update','1','2020-03-11 10:35:34','0','1','0'), ('37','trans_sales_charges','update','1','2020-03-11 10:35:35',NULL,'1','0'), ('38','trans_sales_menus','update','1','2020-03-11 10:35:35',NULL,'1','0'), ('39','trans_sales_no_tax','update','1','2020-03-11 10:35:35',NULL,'1','0'), ('40','trans_sales_payments','update','1','2020-03-11 10:35:35',NULL,'1','0'), ('41','trans_sales_tax','update','1','2020-03-11 10:35:35',NULL,'1','0'), ('42','trans_sales_zero_rated','update','1','2020-03-11 10:35:35',NULL,'1','0'), ('43','cashout_details','update','1','2020-03-11 10:35:36',NULL,'1','0'), ('44','cashout_entries','update','1','2020-03-11 10:35:36',NULL,'1','0'), ('45','shifts','update','1','2020-03-11 10:35:36',NULL,'1','0'), ('46','sync_logs','finish','1','2020-03-11 10:35:36',NULL,'1','0');
INSERT INTO `sync_types` VALUES ('1','local to main'), ('2','main to local');
INSERT INTO `tax_rates` VALUES ('1','VAT','12','0');
INSERT INTO `terminals` VALUES ('1','T00001','ELRGB','Terminal 1','192.168.254.101','TERMINAL1','2014-09-11 12:45:45',NULL,'0','21','96','2021-07-27 09:41:27');
INSERT INTO `trans_refs` VALUES ('1','10','00000001','1',NULL,NULL,'7','2021-07-27 09:41:25','85'), ('2','10','00000002','1',NULL,NULL,'19','2021-07-27 09:42:01','99');
INSERT INTO `trans_sales` VALUES ('1','1',NULL,'10','00000001',NULL,'takeout','1','1','1',NULL,'1095','1095',NULL,NULL,'0','2021-07-27 09:41:12','2021-07-27 09:41:25','1',NULL,NULL,'1','0',NULL,'0','0',NULL,'1','21','1095','0','0','0','0','117.32142857143','0','MO-ALABANG','87',NULL,NULL), ('2','2',NULL,'10','00000002',NULL,'takeout','1','1','1',NULL,'1095','1095',NULL,NULL,'0','2021-07-27 09:41:51','2021-07-27 09:41:58','1',NULL,NULL,'0','0',NULL,'0','0',NULL,'0','21','1095','0','0','0','0','117.32142857143','0','MAX','100',NULL,NULL);
INSERT INTO `trans_sales_menus` VALUES ('1','1','0','2','380','1','0','0',NULL,'1',NULL,NULL,'23','2021-07-27 09:42:02','103','0','Chicken Pizza',NULL,'0'), ('2','1','1','8','365','1','0','0',NULL,'1',NULL,NULL,'23','2021-07-27 09:42:02','103','0','Honey Chicken',NULL,'0'), ('3','1','2','1','350','1','0','0',NULL,'1',NULL,NULL,'23','2021-07-27 09:42:02','103','0','Max Chicken',NULL,'0'), ('4','2','0','2','380','1','0','0',NULL,'1',NULL,NULL,'23','2021-07-27 09:42:02','103','0','Chicken Pizza',NULL,'0'), ('5','2','1','8','365','1','0','0',NULL,'1',NULL,NULL,'23','2021-07-27 09:42:02','103','0','Honey Chicken',NULL,'0'), ('6','2','2','1','350','1','0','0',NULL,'1',NULL,NULL,'23','2021-07-27 09:42:02','103','0','Max Chicken',NULL,'0');
INSERT INTO `trans_sales_no_tax` VALUES ('1','1','0',NULL,'24','2021-07-27 09:42:02','104'), ('2','2','0',NULL,'24','2021-07-27 09:42:02','104');
INSERT INTO `trans_sales_payments` VALUES ('1','1','cash','1095','1095',NULL,NULL,NULL,NULL,'1','2021-07-27 09:41:20',NULL,'25','105'), ('2','2','cash','1095','1095',NULL,NULL,NULL,NULL,'1','2021-07-27 09:41:58',NULL,'25','105');
INSERT INTO `trans_sales_tax` VALUES ('1','1','VAT','12','117.32142857143',NULL,'5','2021-07-27 09:41:26','93'), ('2','2','VAT','12','117.32142857143',NULL,'26','2021-07-27 09:42:02','106');
INSERT INTO `trans_sales_zero_rated` VALUES ('1','1','0',NULL,'27','2021-07-27 09:42:03','107',NULL,NULL), ('2','2','0',NULL,'27','2021-07-27 09:42:03','107',NULL,NULL);
INSERT INTO `uom` VALUES ('1','ml','Mililiter','0',NULL,'0'), ('2','gm','Gram','0',NULL,'0'), ('3','pc','Piece','0',NULL,'0'), ('4','can','Can','0','0','0'), ('5','btl','Bottle','0','0','0'), ('6','kilo','Kilo','0','0','0'), ('7','pack','Pack','0','0','0'), ('8','Serving','serving','0','0','0'), ('9','tali','tali','0',NULL,'0'), ('10','jar','jar','0',NULL,'0'), ('11','sack','sack','0',NULL,'0'), ('12','tray','tray','0',NULL,'0'), ('13','case','case','0',NULL,'0'), ('14','roll','roll','0',NULL,'0'), ('15','gal','gal','0',NULL,'0'), ('16','box','box','0',NULL,'0');
INSERT INTO `users` VALUES ('1','admin','5f4dcc3b5aa765d61d8327deb882cf99','4c68cea7e58591b579fd074bcdaff740','Jessie','R.','Alison','','1','j.alison@pointonesolutions.com.ph','male','2014-06-16 14:41:31','0',NULL,'2021-01-13 10:44:35','MO-ALABANG','11'), ('65','TJH','81dc9bdb52d04dc20036dbd8313ed055','287f5779654e866161247055b8e0e14f','TJ','','Hernandez','','2','','male','2020-10-22 13:17:45','0',NULL,'2021-01-13 10:44:35','MO-ALABANG','11'), ('66','Jose','81dc9bdb52d04dc20036dbd8313ed055','c4b23d8601989c66d8811714a4da0536','Jose','','Abena','','2','','male','2020-10-22 13:19:19','0',NULL,'2021-01-13 10:44:35','MO-ALABANG','11'), ('67','Bads','7e3b7a5bafcb0fa8e8dfe3ea6aca9186','1b648b198d84b9e6a42db93141c87596','Real John','','Bongolo','','3','','male','2020-10-22 13:19:54','0',NULL,'2021-01-13 10:44:35','MO-ALABANG','11'), ('68','Acel','38b8e8fe30cd2f6f7e79f6be6905fabb','346829a0bcdcc55b3efaf5fa7a57e6a1','Maricel','','Salvador','','3','','female','2020-10-22 13:20:24','0',NULL,'2021-01-13 10:44:35','MO-ALABANG','11'), ('69','Jason','c20ad4d76fe97759aa27a0c99bff6710','91b3261d1d4ae00b5bb6c2d1cbda52cc','Jason','','Pasuquin','','3','','male','2020-10-22 15:27:41','0',NULL,'2021-01-13 10:44:35','MO-ALABANG','11'), ('70','jgg','4da1a768c0823181364fbfe594be2629','4da1a768c0823181364fbfe594be2629','Jayson','Garfin','Grefalda','','1','jayson.grefalda@momentgroup.ph','male','2020-12-18 20:04:25','0',NULL,'2021-07-26 10:18:02','MO-ALABANG','11');
INSERT INTO `user_roles` VALUES ('1','Administrator ','System Administrator','all'), ('2','Manager','Manager','all'), ('3','Employee','Employee','cashier'), ('4','OIC','Officer In Charge',NULL);
INSERT INTO `vistamall` VALUES ('1','12345678','00','C:/VISTAMALL');
