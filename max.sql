/*
MySQL Backup
Source Server Version: 5.5.5
Source Database: max
Date: 7/27/2021 10:21:01
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
  `cashout_id` int(11) NOT NULL,
  `type` varchar(30) DEFAULT NULL,
  `denomination` varchar(150) DEFAULT '0',
  `reference` varchar(150) DEFAULT NULL,
  `total` double DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `cashout_entries`
-- ----------------------------
DROP TABLE IF EXISTS `cashout_entries`;
CREATE TABLE `cashout_entries` (
  `cashout_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(45) NOT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `drawer_amount` varchar(255) DEFAULT NULL,
  `count_amount` double DEFAULT NULL,
  `trans_date` datetime NOT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cashout_id`)
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
  PRIMARY KEY (`cat_id`),
  KEY `cat_id` (`cat_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  PRIMARY KEY (`charge_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

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
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(40) DEFAULT NULL,
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
  `remarks` longtext,
  `sync_id` int(11) DEFAULT NULL,
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
  PRIMARY KEY (`expenses_detail_id`)
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
  PRIMARY KEY (`id`)
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
  `date_added` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_code` (`expenses_code`) USING BTREE
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
  PRIMARY KEY (`gc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1050 DEFAULT CHARSET=utf8;

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
  `img_blob` longblob,
  `datetime` datetime DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`img_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

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
  `update_date` datetime DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  `brand` varchar(55) DEFAULT NULL,
  `costing` double DEFAULT '0',
  PRIMARY KEY (`item_id`),
  KEY `item_id` (`item_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `cost` double DEFAULT '0',
  PRIMARY KEY (`move_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `item_moves0627`
-- ----------------------------
DROP TABLE IF EXISTS `item_moves0627`;
CREATE TABLE `item_moves0627` (
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
  PRIMARY KEY (`move_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

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
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

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
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `master_id` int(11) NOT NULL,
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
--  Table structure for `menu_categories`
-- ----------------------------
DROP TABLE IF EXISTS `menu_categories`;
CREATE TABLE `menu_categories` (
  `menu_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_cat_name` varchar(150) NOT NULL,
  `menu_sched_id` int(11) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `master_id` int(11) NOT NULL,
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
  PRIMARY KEY (`move_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `menu_recipe`
-- ----------------------------
DROP TABLE IF EXISTS `menu_recipe`;
CREATE TABLE `menu_recipe` (
  `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
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
  `master_id` int(11) NOT NULL,
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
  PRIMARY KEY (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `modifier_groups`
-- ----------------------------
DROP TABLE IF EXISTS `modifier_groups`;
CREATE TABLE `modifier_groups` (
  `mod_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `mandatory` int(1) DEFAULT '0',
  `multiple` int(10) DEFAULT '0',
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
  `master_id` int(11) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `promo_discount_items`
-- ----------------------------
DROP TABLE IF EXISTS `promo_discount_items`;
CREATE TABLE `promo_discount_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `promo_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

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
  `read_type` tinyint(2) NOT NULL,
  `read_date` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `old_total` double DEFAULT NULL,
  `grand_total` double DEFAULT NULL COMMENT 'GT for ZRead only',
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `scope_from` datetime DEFAULT NULL,
  `scope_to` datetime DEFAULT NULL,
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
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

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
  `approve_status` int(10) DEFAULT NULL,
  `approve_by` varchar(255) DEFAULT NULL,
  `approve_date` datetime DEFAULT NULL,
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
  `loyalty_for_amount` double DEFAULT '0',
  `loyalty_to_points` double DEFAULT '0',
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
  `shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime DEFAULT NULL,
  `xread_id` int(11) DEFAULT NULL,
  `cashout_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`shift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `shift_entries`
-- ----------------------------
DROP TABLE IF EXISTS `shift_entries`;
CREATE TABLE `shift_entries` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `user_id` varchar(45) NOT NULL,
  `trans_date` datetime NOT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `memo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`entry_id`)
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
--  Table structure for `temp_sales`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales`;
CREATE TABLE `temp_sales` (
  `sales_id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile_sales_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(55) DEFAULT NULL,
  `void_ref` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `customer_id` varchar(11) DEFAULT NULL,
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
  `billed` int(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `total_gross` double DEFAULT '0',
  `total_discount` double DEFAULT '0',
  `total_charges` double DEFAULT '0',
  `zero_rated` double DEFAULT '0',
  `no_tax` double DEFAULT '0',
  `tax` double DEFAULT '0',
  `local_tax` double DEFAULT '0',
  `tin` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sales_id`),
  KEY `sales_id` (`sales_id`) USING BTREE,
  KEY `customer_id` (`customer_id`) USING BTREE,
  KEY `terminal_id` (`terminal_id`) USING BTREE,
  KEY `datetime` (`datetime`) USING BTREE,
  KEY `type_id` (`type_id`) USING BTREE,
  KEY `trans_ref` (`trans_ref`) USING BTREE,
  KEY `inactive` (`inactive`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `temp_sales_charges`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales_charges`;
CREATE TABLE `temp_sales_charges` (
  `sales_charge_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `charge_id` int(11) DEFAULT NULL,
  `charge_code` varchar(55) DEFAULT NULL,
  `charge_name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `absolute` tinyint(1) DEFAULT '0',
  `amount` double DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_charge_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `temp_sales_discounts`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales_discounts`;
CREATE TABLE `temp_sales_discounts` (
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_disc_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `temp_sales_items`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales_items`;
CREATE TABLE `temp_sales_items` (
  `sales_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `line_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `no_tax` int(1) DEFAULT '0',
  `remarks` varchar(150) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nocharge` int(11) DEFAULT '0',
  `item_name` varchar(255) DEFAULT NULL,
  `is_takeout` int(11) DEFAULT '0',
  PRIMARY KEY (`sales_item_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `temp_sales_local_tax`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales_local_tax`;
CREATE TABLE `temp_sales_local_tax` (
  `sales_local_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_local_tax_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `temp_sales_loyalty_points`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales_loyalty_points`;
CREATE TABLE `temp_sales_loyalty_points` (
  `loyalty_point_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL,
  `code` varchar(150) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `points` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`loyalty_point_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `temp_sales_menus`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales_menus`;
CREATE TABLE `temp_sales_menus` (
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
  `free_user_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nocharge` int(11) DEFAULT '0',
  `menu_name` varchar(255) DEFAULT NULL,
  `free_reason` varchar(255) DEFAULT NULL,
  `is_takeout` int(11) DEFAULT '0',
  PRIMARY KEY (`sales_menu_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `temp_sales_menu_modifiers`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales_menu_modifiers`;
CREATE TABLE `temp_sales_menu_modifiers` (
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `menu_name` varchar(255) DEFAULT NULL,
  `mod_group_name` varchar(255) DEFAULT NULL,
  `mod_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sales_mod_id`),
  KEY `sales_id` (`sales_id`) USING BTREE,
  KEY `menu_id` (`menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `temp_sales_no_tax`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales_no_tax`;
CREATE TABLE `temp_sales_no_tax` (
  `sales_no_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_no_tax_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `temp_sales_payments`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales_payments`;
CREATE TABLE `temp_sales_payments` (
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
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `sales_id` (`sales_id`) USING BTREE,
  KEY `payment_type` (`payment_type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `temp_sales_tax`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales_tax`;
CREATE TABLE `temp_sales_tax` (
  `sales_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_tax_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `temp_sales_zero_rated`
-- ----------------------------
DROP TABLE IF EXISTS `temp_sales_zero_rated`;
CREATE TABLE `temp_sales_zero_rated` (
  `sales_zero_rated_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) DEFAULT NULL,
  `card_no` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sales_zero_rated_id`),
  KEY `sales_id` (`sales_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

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
  `user_id` int(11) DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
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
  `delivered_by` varchar(255) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL,
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
  `uom` varchar(50) DEFAULT NULL,
  `case` int(1) DEFAULT '0',
  `pack` int(1) DEFAULT '0',
  `price` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_sales`
-- ----------------------------
DROP TABLE IF EXISTS `trans_sales`;
CREATE TABLE `trans_sales` (
  `sales_id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile_sales_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(55) DEFAULT NULL,
  `void_ref` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `customer_id` varchar(11) DEFAULT NULL,
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
  `billed` int(4) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `total_gross` double DEFAULT '0',
  `total_discount` double DEFAULT '0',
  `total_charges` double DEFAULT '0',
  `zero_rated` double DEFAULT '0',
  `no_tax` double DEFAULT '0',
  `tax` double DEFAULT '0',
  `local_tax` double DEFAULT '0',
  `tin` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sales_id`),
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `amount` double DEFAULT NULL,
  `points` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `free_user_id` int(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `menu_name` varchar(255) DEFAULT NULL,
  `mod_group_name` varchar(255) DEFAULT NULL,
  `mod_name` varchar(255) DEFAULT NULL,
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `sync_id` int(11) DEFAULT NULL,
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  PRIMARY KEY (`id`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `users_copy1`
-- ----------------------------
DROP TABLE IF EXISTS `users_copy1`;
CREATE TABLE `users_copy1` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

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
--  Records 
-- ----------------------------
INSERT INTO `araneta` VALUES ('1','HAPCHAN','30436','141040\r','C:/ARANETA');
INSERT INTO `ayala` VALUES ('1','6000000002487','MO\' COOKIES','AYA','MO ATC','C:/AYALA/','C:/AYALA/');
INSERT INTO `branch_details` VALUES ('1','1','MAX','MAX','MAX','','','UGF Activity Center, Alabang Town Center, Access Road, Ayala Alabang, Muntinlupa',NULL,'PHP','layout.png','0','008-821-864-006','20102213145819891','0','FP102020-53B0270046-00006','ZGST9800200200105','','','06:00:00','23:45:00','1234','190.125.220.1','mag15836hap','maghapex','43A0085434442014110212','      THANK YOU COME AGAIN.         THIS SERVES AS YOUR OFFICIAL RECEIPT','');
INSERT INTO `charges` VALUES ('1','SCHG','Service Charge','9','0','1','0'), ('2','DCHG','Delivery Charge','5','0','1','0'), ('3','PCHG','Packaging Charge','5','0','0','0');
INSERT INTO `ci_sessions` VALUES ('04476d78b11c40fc4e30ee3eff707bc5','0.0.0.0','0','1627350084',''), ('0d32111083f0526aa5f08208007e0637','0.0.0.0','0','1627350121',''), ('4e2663850fdd5268d3464769deba2a5e','0.0.0.0','0','1627350081',''), ('62c3454c5ec8f291293cb081e09880c0','::1','Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.164 Safari/537.36','1627349895','a:2:{s:9:\"user_data\";s:0:\"\";s:11:\"site_alerts\";a:1:{i:0;a:2:{s:4:\"text\";s:41:\"You need to start a shift before selling.\";s:4:\"type\";s:5:\"error\";}}}'), ('68d338e9f1ccd914cbff8bd8c9290174','0.0.0.0','0','1627350083',''), ('7bc15a74fbba6e8991aa44ae941e6cec','0.0.0.0','0','1627350081',''), ('8bbc8447affcd0af505bf72f2e99a182','0.0.0.0','0','1627350118',''), ('a1d80a8badeeae5cfa6dab414d622188','0.0.0.0','0','1627350118',''), ('db291045602d06f7adedf5002f6f8b72','::1','Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.164 Safari/537.36','1627349895','a:4:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"fname\";s:6:\"Jessie\";s:5:\"lname\";s:6:\"Alison\";s:5:\"mname\";s:2:\"R.\";s:6:\"suffix\";s:0:\"\";s:9:\"full_name\";s:17:\"Jessie R. Alison \";s:7:\"role_id\";s:1:\"1\";s:4:\"role\";s:14:\"Administrator \";s:6:\"access\";s:3:\"all\";s:3:\"img\";s:40:\"http://localhost/ipos_max/img/avatar.jpg\";}s:9:\"site_load\";i:100;s:14:\"site_load_text\";N;}'), ('f5f2aa8cb47dde9904caea986dbc1521','0.0.0.0','0','1627350121','');
INSERT INTO `conversations` VALUES ('1','1','2','2015-05-06 10:57:25','0'), ('3','1','3','2015-05-06 12:28:55','0');
INSERT INTO `conversation_messages` VALUES ('1','1','1','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus dictum sapien eget nunc viverra, nec consequat lectus hendrerit. Etiam vel ante gravida, pellentesque ex nec, pretium neque. Pellentesque finibus purus diam, ac condimentum augue fermentum et. Ut neque nisi, hendrerit id laoreet fermentum, condimentum at dolor. Pellentesque aliquet tellus quis ullamcorper maximus. Donec finibus lectus sem, id pulvinar lectus pulvinar ut. ',NULL,'2015-05-06 10:57:25','0'), ('3','3','1','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus dictum sapien eget nunc viverra, nec consequat lectus hendrerit. Etiam vel ante gravida, pellentesque ex nec, pretium neque. Pellentesque finibus purus diam, ac condimentum augue fermentum et. Ut neque nisi, hendrerit id laoreet fermentum, condimentum at dolor. Pellentesque aliquet tellus quis ullamcorper maximus. Donec finibus lectus sem, id pulvinar lectus pul',NULL,'2015-05-06 12:28:55','0'), ('4','3','1','tristique, odio id scelerisque sollicitudin, diam massa lobortis enim, in faucibus nisi leo at dui. Proin ornare eleifend risus, ut condimentum metus porttitor non. Donec',NULL,'2015-05-06 12:34:46','0'), ('5','1','1','asdas asd ',NULL,'2015-05-06 12:40:24','0'), ('6','1','1','asda dsa asd asd ',NULL,'2015-05-06 12:47:25','0'), ('7','3','1',' asd asd asd ',NULL,'2015-05-06 12:47:58','0'), ('8','3','1',' asd  asd asd asd asd ',NULL,'2015-05-06 12:48:08','0'), ('9','3','1',' asd asd ',NULL,'2015-05-06 12:48:41','0'), ('10','3','1',' asd asd ',NULL,'2015-05-06 12:49:17','0'), ('11','3','1',' asd asd  asd asd ',NULL,'2015-05-06 12:49:25','0'), ('12','3','1',' asd asd  asd asd  asd ',NULL,'2015-05-06 12:49:38','0'), ('13','3','1','asd  sa s a',NULL,'2015-05-06 12:49:45','0'), ('14','3','1',' asd asd ',NULL,'2015-05-06 12:50:16','0'), ('15','3','1','asd asd asd ',NULL,'2015-05-06 12:50:54','0'), ('16','3','1','asd asd asd ',NULL,'2015-05-06 12:52:55','0'), ('17','3','3','asd asd a dsa asd ',NULL,'2015-05-06 12:53:10','0'), ('18','3','1',' asd asd asd ',NULL,'2015-05-06 12:53:41','0'), ('19','3','3','da sda sd asd ',NULL,'2015-05-06 12:54:41','0'), ('20','3','1','asd asd asd 1 123 123 asd asd ',NULL,'2015-05-06 12:55:39','0'), ('21','3','1','12 asd asd asd asd ',NULL,'2015-05-06 12:56:41','0'), ('22','3','1','asd asd asd asd ',NULL,'2015-05-06 13:07:57','0'), ('23','3','1','asd asd asd asd ',NULL,'2015-05-06 13:07:58','0'), ('24','3','1','asd asd asd ',NULL,'2015-05-06 13:13:55','0'), ('25','3','1','sd asd asd ',NULL,'2015-05-06 13:14:13','0'), ('26','3','1','asd asd asd  asd ',NULL,'2015-05-06 13:14:31','0'), ('27','3','1','sad asd asd 1  asd asd ',NULL,'2015-05-06 13:14:48','0'), ('28','1','1','a sd asd asd ',NULL,'2015-05-06 13:23:07','0'), ('29','1','1','sdas  asd asd asd ',NULL,'2015-05-06 13:23:11','0'), ('30','1','1',' asd 213 sd asd ',NULL,'2015-05-06 13:23:16','0'), ('31','3','1',' asd 12 asd asd ',NULL,'2015-05-06 13:23:20','0'), ('32','3','1',' 3 qwe asd asd 13 123 ',NULL,'2015-05-06 13:23:25','0'), ('33','3','1',' asd asd 123 123 123 ',NULL,'2015-05-06 13:23:35','0'), ('34','1','1',' 123 12 3asd asd 123 ',NULL,'2015-05-06 13:24:05','0'), ('35','3','1','123 123 asd as 123 ',NULL,'2015-05-06 13:24:09','0'), ('36','3','1','13 12 asd 12 3123 asd ',NULL,'2015-05-06 13:25:35','0'), ('37','1','1',' 123 123 ad 123 12 3asd ',NULL,'2015-05-06 13:25:56','0');
INSERT INTO `currencies` VALUES ('1','PHP','Philippine Peso','0'), ('2','USD','US Dollars','0'), ('3','YEN','Japanese Yen','0');
INSERT INTO `customers` VALUES ('1','2346','5765','rey','c','tejada','',NULL,'656','565','5665','7','7868','0',NULL), ('2','09173242668','gayap@gmail.com','Gladys','','Ayap','',NULL,'1','1','1',NULL,'1','0','2019-12-24 16:41:49'), ('3','09171843232','amanda@gmail.com','amanda','','schoof','',NULL,'1','1','1',NULL,'1','0','2019-12-24 16:48:57'), ('4','09178550946','fmiras@gmail.com','feli','','miras','',NULL,'1','1','1',NULL,'1','0','2019-12-27 19:59:20');
INSERT INTO `denominations` VALUES ('1','One Thousand','1000',NULL), ('2','Five Hundreds','500',NULL), ('3','Two Hundreds','200',NULL), ('4','One Hundreds','100',NULL), ('5','Fifty','50',NULL), ('6','Twenty','20',NULL), ('7','Ten','10',NULL), ('8','Five','5',NULL), ('9','One','1',NULL), ('10','Twenty Five Cents','0.25',NULL), ('11','Ten Cents','0.1',NULL);
INSERT INTO `dtr_scheduler` VALUES ('1','1','2014-10-28','2'), ('3','3','2014-10-28','2'), ('4','4','2014-10-28','2'), ('5','5','2014-10-28','2'), ('14','6','2014-10-28','2'), ('15','1','2014-10-29','2'), ('16','3','2014-10-29','2'), ('17','4','2014-10-29','2'), ('18','5','2014-10-29','2'), ('19','6','2014-10-29','2'), ('22','1','2014-10-30','3'), ('23','3','2014-10-30','3'), ('24','5','2014-10-30','5'), ('25','1','2014-10-31','2'), ('26','3','2014-10-31','5'), ('27','4','2014-10-31','4'), ('28','5','2014-10-31','5'), ('29','6','2014-10-31','4'), ('30','1','2014-11-01','2'), ('31','3','2014-11-01','5'), ('32','4','2014-11-01','4'), ('33','5','2014-11-01','5'), ('34','6','2014-11-01','4'), ('35','1','2014-11-02','2'), ('36','4','2014-11-02','6'), ('37','6','2014-11-02','6'), ('38','5','2014-11-12','2'), ('39','6','2014-11-12','2'), ('40','5','2014-11-13','2'), ('41','6','2014-11-13','2'), ('42','5','2014-11-14','2'), ('43','6','2014-11-14','2'), ('44','16','2014-11-24','7'), ('45','17','2014-11-24','7'), ('46','18','2014-11-24','7'), ('47','16','2014-11-25','7'), ('48','17','2014-11-25','7'), ('49','18','2014-11-25','7'), ('50','16','2014-11-26','7'), ('51','17','2014-11-26','7'), ('52','18','2014-11-26','7'), ('53','16','2014-11-27','7'), ('54','17','2014-11-27','7'), ('55','18','2014-11-27','7'), ('56','16','2014-11-28','7'), ('57','17','2014-11-28','7'), ('58','18','2014-11-28','7'), ('59','16','2014-11-29','7'), ('60','17','2014-11-29','7'), ('61','18','2014-11-29','7'), ('62','16','2014-11-30','7'), ('63','17','2014-11-30','7'), ('64','18','2014-11-30','7'), ('65','19','2014-11-24','7'), ('66','19','2014-11-25','7'), ('67','19','2014-11-26','7'), ('68','19','2014-11-27','7'), ('69','19','2014-11-28','7'), ('70','19','2014-11-29','7'), ('71','19','2014-11-30','7');
INSERT INTO `dtr_shifts` VALUES ('1','RESTDAY','Rest Day','00:00:00','00:00:00','00:00:00','00:00:00','0','0','0','00:00:00',NULL), ('2','Shift1','restday again','07:00:00','11:00:00','12:00:00','16:00:00','1','9','0','00:00:00','00:30:00'), ('3','6PM7AM','6PM to 7AM','18:00:00','00:00:00','00:00:00','07:00:00','0','13','0','00:00:00','00:00:00'), ('4','7AM7PM','7AM to 7PM','07:00:00','00:00:00','00:00:00','19:00:00','0','12','0','00:15:00','01:00:00'), ('5','7PM7AM','7PM to 7AM','19:00:00','00:00:00','00:00:00','07:00:00','0','-12','0','00:15:00','01:00:00'), ('6','7AM4PM','7AM to 4PM','07:00:00','00:00:00','00:00:00','16:00:00','0','9','0','00:15:00','01:00:00'), ('7','9AM10PM','9AM10PM','09:00:00','00:00:00','00:00:00','22:00:00','1','13','0','00:00:00','00:00:00');
INSERT INTO `eton` VALUES ('1','ABCD1234','C:/ETON');
INSERT INTO `gift_cards` VALUES ('1','110628','1000','1',NULL), ('2','1234','500','1',NULL), ('3','12345','2000','1',NULL), ('4','012','500','0',NULL), ('5','0001','1000','0',NULL), ('6','00031','500','0',NULL), ('7','8564','1000','0',NULL), ('8','2019-11-0036','1000','1',NULL), ('9','2019-11-0037','1000','1',NULL), ('10','2019-11-0038','1000','0',NULL), ('11','2019-11-0039','1000','0',NULL), ('12','2019-11-0034','1000','1',NULL), ('13','2019-11-0033','1000','1',NULL), ('14','0032','1000','1',NULL), ('15','2000-5-00005','500','1',NULL), ('16','2000-5-00001','500','0',NULL), ('17','2000-5-00002','500','0',NULL), ('18','2000-5-00003','500','0',NULL), ('19','2000-5-00004','500','0',NULL), ('20','2000-5-00005','500','0',NULL), ('21','2000-5-00006','500','0',NULL), ('22','2000-5-00007','500','0',NULL), ('23','2000-5-00008','500','0',NULL), ('24','2000-5-00009','500','0',NULL), ('25','2000-5-00010','500','0',NULL), ('26','2000-5-00011','500','0',NULL), ('27','2000-5-00012','500','0',NULL), ('28','2000-5-00013','500','0',NULL), ('29','2000-5-00014','500','0',NULL), ('30','2000-5-00015','500','0',NULL), ('31','2000-5-00016','500','0',NULL), ('32','2000-5-00017','500','0',NULL), ('33','2000-5-00018','500','0',NULL), ('34','2000-5-00019','500','0',NULL), ('35','2000-5-00020','500','0',NULL), ('36','2000-5-00021','500','0',NULL), ('37','2000-5-00022','500','0',NULL), ('38','2000-5-00023','500','0',NULL), ('39','2000-5-00024','500','0',NULL), ('40','2000-5-00025','500','0',NULL), ('41','2000-5-00026','500','0',NULL), ('42','2000-5-00027','500','0',NULL), ('43','2000-5-00028','500','0',NULL), ('44','2000-5-00029','500','0',NULL), ('45','2000-5-00030','500','0',NULL), ('46','2000-5-00031','500','1',NULL), ('47','2000-5-00032','500','0',NULL), ('48','2000-5-00033','500','0',NULL), ('49','2000-5-00034','500','0',NULL), ('50','2000-5-00035','500','0',NULL), ('51','2000-5-00036','500','0',NULL), ('52','2000-5-00037','500','0',NULL), ('53','2000-5-00038','500','0',NULL), ('54','2000-5-00039','500','0',NULL), ('55','2000-5-00040','500','0',NULL), ('56','2000-5-00041','500','0',NULL), ('57','2000-5-00042','500','0',NULL), ('58','2000-5-00043','500','0',NULL), ('59','2000-5-00044','500','0',NULL), ('60','2000-5-00045','500','0',NULL), ('61','2000-5-00046','500','0',NULL), ('62','2000-5-00047','500','0',NULL), ('63','2000-5-00048','500','0',NULL), ('64','2000-5-00049','500','0',NULL), ('65','2000-5-00050','500','0',NULL), ('66','2000-5-00051','500','0',NULL), ('67','2000-5-00052','500','0',NULL), ('68','2000-5-00053','500','0',NULL), ('69','2000-5-00054','500','0',NULL), ('70','2000-5-00055','500','0',NULL), ('71','2000-5-00056','500','0',NULL), ('72','2000-5-00057','500','0',NULL), ('73','2000-5-00058','500','0',NULL), ('74','2000-5-00059','500','0',NULL), ('75','2000-5-00060','500','0',NULL), ('76','2000-5-00061','500','0',NULL), ('77','2000-5-00062','500','0',NULL), ('78','2000-5-00063','500','0',NULL), ('79','2000-5-00064','500','0',NULL), ('80','2000-5-00065','500','0',NULL), ('81','2000-5-00066','500','0',NULL), ('82','2000-5-00067','500','0',NULL), ('83','2000-5-00068','500','0',NULL), ('84','2000-5-00069','500','0',NULL), ('85','2000-5-00070','500','0',NULL), ('86','2000-5-00071','500','0',NULL), ('87','2000-5-00072','500','0',NULL), ('88','2000-5-00073','500','0',NULL), ('89','2000-5-00074','500','0',NULL), ('90','2000-5-00075','500','0',NULL), ('91','2000-5-00076','500','0',NULL), ('92','2000-5-00077','500','0',NULL), ('93','2000-5-00078','500','0',NULL), ('94','2000-5-00079','500','0',NULL), ('95','2000-5-00080','500','0',NULL), ('96','2000-5-00081','500','0',NULL), ('97','2000-5-00082','500','0',NULL), ('98','2000-5-00083','500','0',NULL), ('99','2000-5-00084','500','1',NULL), ('100','2000-5-00085','500','1',NULL);
INSERT INTO `gift_cards` VALUES ('101','2000-5-00086','500','1',NULL), ('102','2000-5-00087','500','1',NULL), ('103','2000-5-00088','500','1',NULL), ('104','2000-5-00089','500','1',NULL), ('105','2000-5-00090','500','1',NULL), ('106','2000-5-00091','500','1',NULL), ('107','2000-5-00092','500','0',NULL), ('108','2000-5-00093','500','0',NULL), ('109','2000-5-00094','500','1',NULL), ('110','2000-5-00095','500','1',NULL), ('111','2000-5-00096','500','0',NULL), ('112','2000-5-00097','500','0',NULL), ('113','2000-5-00098','500','0',NULL), ('114','2000-5-00099','500','0',NULL), ('115','2000-5-00100','500','0',NULL), ('116','2000-5-00101','500','0',NULL), ('117','2000-5-00102','500','0',NULL), ('118','2000-5-00103','500','0',NULL), ('119','2000-5-00104','500','0',NULL), ('120','2000-5-00105','500','0',NULL), ('121','2000-5-00106','500','0',NULL), ('122','2000-5-00107','500','0',NULL), ('123','2000-5-00108','500','0',NULL), ('124','2000-5-00109','500','0',NULL), ('125','2000-5-00110','500','0',NULL), ('126','2000-5-00111','500','0',NULL), ('127','2000-5-00112','500','0',NULL), ('128','2000-5-00113','500','0',NULL), ('129','2000-5-00114','500','0',NULL), ('130','2000-5-00115','500','0',NULL), ('131','2000-5-00116','500','0',NULL), ('132','2000-5-00117','500','0',NULL), ('133','2000-5-00118','500','0',NULL), ('134','2000-5-00119','500','0',NULL), ('135','2000-5-00120','500','0',NULL), ('136','2000-5-00121','500','0',NULL), ('137','2000-5-00122','500','0',NULL), ('138','2000-5-00123','500','0',NULL), ('139','2000-5-00124','500','0',NULL), ('140','2000-5-00125','500','0',NULL), ('141','2000-5-00126','500','0',NULL), ('142','2000-5-00127','500','0',NULL), ('143','2000-5-00128','500','0',NULL), ('144','2000-5-00129','500','0',NULL), ('145','2000-5-00130','500','0',NULL), ('146','2000-5-00131','500','0',NULL), ('147','2000-5-00132','500','0',NULL), ('148','2000-5-00133','500','0',NULL), ('149','2000-5-00134','500','0',NULL), ('150','2000-5-00135','500','0',NULL), ('151','2000-5-00136','500','0',NULL), ('152','2000-5-00137','500','0',NULL), ('153','2000-5-00138','500','0',NULL), ('154','2000-5-00139','500','0',NULL), ('155','2000-5-00140','500','0',NULL), ('156','2000-5-00141','500','0',NULL), ('157','2000-5-00142','500','0',NULL), ('158','2000-5-00143','500','0',NULL), ('159','2000-5-00144','500','0',NULL), ('160','2000-5-00145','500','0',NULL), ('161','2000-5-00146','500','0',NULL), ('162','2000-5-00147','500','0',NULL), ('163','2000-5-00148','500','0',NULL), ('164','2000-5-00149','500','0',NULL), ('165','2000-5-00150','500','0',NULL), ('166','2000-5-00151','500','0',NULL), ('167','2000-5-00152','500','0',NULL), ('168','2000-5-00153','500','0',NULL), ('169','2000-5-00154','500','0',NULL), ('170','2000-5-00155','500','0',NULL), ('171','2000-5-00156','500','0',NULL), ('172','2000-5-00157','500','0',NULL), ('173','2000-5-00158','500','0',NULL), ('174','2000-5-00159','500','0',NULL), ('175','2000-5-00160','500','0',NULL), ('176','2000-5-00161','500','0',NULL), ('177','2000-5-00162','500','0',NULL), ('178','2000-5-00163','500','0',NULL), ('179','2000-5-00164','500','0',NULL), ('180','2000-5-00165','500','0',NULL), ('181','2000-5-00166','500','0',NULL), ('182','2000-5-00167','500','0',NULL), ('183','2000-5-00168','500','0',NULL), ('184','2000-5-00169','500','0',NULL), ('185','2000-5-00170','500','0',NULL), ('186','2000-5-00171','500','0',NULL), ('187','2000-5-00172','500','0',NULL), ('188','2000-5-00173','500','0',NULL), ('189','2000-5-00174','500','0',NULL), ('190','2000-5-00175','500','0',NULL), ('191','2000-5-00176','500','0',NULL), ('192','2000-5-00177','500','0',NULL), ('193','2000-5-00178','500','0',NULL), ('194','2000-5-00179','500','0',NULL), ('195','2000-5-00180','500','0',NULL), ('196','2000-5-00181','500','0',NULL), ('197','2000-5-00182','500','0',NULL), ('198','2000-5-00183','500','0',NULL), ('199','2000-5-00184','500','0',NULL), ('200','2000-5-00185','500','0',NULL);
INSERT INTO `gift_cards` VALUES ('201','2000-5-00186','500','0',NULL), ('202','2000-5-00187','500','0',NULL), ('203','2000-5-00188','500','0',NULL), ('204','2000-5-00189','500','0',NULL), ('205','2000-5-00190','500','0',NULL), ('206','2000-5-00191','500','0',NULL), ('207','2000-5-00192','500','0',NULL), ('208','2000-5-00193','500','0',NULL), ('209','2000-5-00194','500','0',NULL), ('210','2000-5-00195','500','0',NULL), ('211','2000-5-00196','500','0',NULL), ('212','2000-5-00197','500','0',NULL), ('213','2000-5-00198','500','0',NULL), ('214','2000-5-00199','500','0',NULL), ('215','2000-5-00200','500','0',NULL), ('216','2000-5-00201','500','0',NULL), ('217','2000-5-00202','500','0',NULL), ('218','2000-5-00203','500','0',NULL), ('219','2000-5-00204','500','0',NULL), ('220','2000-5-00205','500','0',NULL), ('221','2000-5-00206','500','0',NULL), ('222','2000-5-00207','500','0',NULL), ('223','2000-5-00208','500','0',NULL), ('224','2000-5-00209','500','0',NULL), ('225','2000-5-00210','500','0',NULL), ('226','2000-5-00211','500','0',NULL), ('227','2000-5-00212','500','0',NULL), ('228','2000-5-00213','500','0',NULL), ('229','2000-5-00214','500','0',NULL), ('230','2000-5-00215','500','0',NULL), ('231','2000-5-00216','500','0',NULL), ('232','2000-5-00217','500','0',NULL), ('233','2000-5-00218','500','0',NULL), ('234','2000-5-00219','500','0',NULL), ('235','2000-5-00220','500','0',NULL), ('236','2000-5-00221','500','0',NULL), ('237','2000-5-00222','500','0',NULL), ('238','2000-5-00223','500','0',NULL), ('239','2000-5-00224','500','0',NULL), ('240','2000-5-00225','500','0',NULL), ('241','2000-5-00226','500','0',NULL), ('242','2000-5-00227','500','0',NULL), ('243','2000-5-00228','500','0',NULL), ('244','2000-5-00229','500','0',NULL), ('245','2000-5-00230','500','0',NULL), ('246','2000-5-00231','500','0',NULL), ('247','2000-5-00232','500','0',NULL), ('248','2000-5-00233','500','0',NULL), ('249','2000-5-00234','500','0',NULL), ('250','2000-5-00235','500','0',NULL), ('251','2000-5-00236','500','0',NULL), ('252','2000-5-00237','500','0',NULL), ('253','2000-5-00238','500','0',NULL), ('254','2000-5-00239','500','0',NULL), ('255','2000-5-00240','500','0',NULL), ('256','2000-5-00241','500','0',NULL), ('257','2000-5-00242','500','0',NULL), ('258','2000-5-00243','500','0',NULL), ('259','2000-5-00244','500','0',NULL), ('260','2000-5-00245','500','0',NULL), ('261','2000-5-00246','500','0',NULL), ('262','2000-5-00247','500','0',NULL), ('263','2000-5-00248','500','0',NULL), ('264','2000-5-00249','500','0',NULL), ('265','2000-5-00250','500','0',NULL), ('266','2000-5-00251','500','0',NULL), ('267','2000-5-00252','500','0',NULL), ('268','2000-5-00253','500','0',NULL), ('269','2000-5-00254','500','0',NULL), ('270','2000-5-00255','500','0',NULL), ('271','2000-5-00256','500','0',NULL), ('272','2000-5-00257','500','0',NULL), ('273','2000-5-00258','500','0',NULL), ('274','2000-5-00259','500','0',NULL), ('275','2000-5-00260','500','0',NULL), ('276','2000-5-00261','500','0',NULL), ('277','2000-5-00262','500','0',NULL), ('278','2000-5-00263','500','0',NULL), ('279','2000-5-00264','500','0',NULL), ('280','2000-5-00265','500','0',NULL), ('281','2000-5-00266','500','0',NULL), ('282','2000-5-00267','500','0',NULL), ('283','2000-5-00268','500','0',NULL), ('284','2000-5-00269','500','0',NULL), ('285','2000-5-00270','500','0',NULL), ('286','2000-5-00271','500','0',NULL), ('287','2000-5-00272','500','0',NULL), ('288','2000-5-00273','500','0',NULL), ('289','2000-5-00274','500','0',NULL), ('290','2000-5-00275','500','0',NULL), ('291','2000-5-00276','500','0',NULL), ('292','2000-5-00277','500','0',NULL), ('293','2000-5-00278','500','0',NULL), ('294','2000-5-00279','500','0',NULL), ('295','2000-5-00280','500','0',NULL), ('296','2000-5-00281','500','0',NULL), ('297','2000-5-00282','500','0',NULL), ('298','2000-5-00283','500','0',NULL), ('299','2000-5-00284','500','0',NULL), ('300','2000-5-00285','500','0',NULL);
INSERT INTO `gift_cards` VALUES ('301','2000-5-00286','500','0',NULL), ('302','2000-5-00287','500','0',NULL), ('303','2000-5-00288','500','0',NULL), ('304','2000-5-00289','500','0',NULL), ('305','2000-5-00290','500','0',NULL), ('306','2000-5-00291','500','1',NULL), ('307','2000-5-00292','500','1',NULL), ('308','2000-5-00293','500','1',NULL), ('309','2000-5-00294','500','1',NULL), ('310','2000-5-00295','500','1',NULL), ('311','2000-5-00296','500','1',NULL), ('312','2000-5-00297','500','1',NULL), ('313','2000-5-00298','500','1',NULL), ('314','2000-5-00299','500','1',NULL), ('315','2000-5-00300','500','1',NULL), ('316','2000-5-00301','500','0',NULL), ('317','2000-5-00302','500','0',NULL), ('318','2000-5-00303','500','0',NULL), ('319','2000-5-00304','500','0',NULL), ('320','2000-5-00305','500','0',NULL), ('321','2000-5-00306','500','0',NULL), ('322','2000-5-00307','500','0',NULL), ('323','2000-5-00308','500','0',NULL), ('324','2000-5-00309','500','0',NULL), ('325','2000-5-00310','500','0',NULL), ('326','2000-5-00311','500','0',NULL), ('327','2000-5-00312','500','0',NULL), ('328','2000-5-00313','500','1',NULL), ('329','2000-5-00314','500','0',NULL), ('330','2000-5-00315','500','0',NULL), ('331','2000-5-00316','500','0',NULL), ('332','2000-5-00317','500','0',NULL), ('333','2000-5-00318','500','0',NULL), ('334','2000-5-00319','500','0',NULL), ('335','2000-5-00320','500','1',NULL), ('336','2000-5-00321','500','0',NULL), ('337','2000-5-00322','500','0',NULL), ('338','2000-5-00323','500','0',NULL), ('339','2000-5-00324','500','0',NULL), ('340','2000-5-00325','500','0',NULL), ('341','2000-5-00326','500','0',NULL), ('342','2000-5-00327','500','0',NULL), ('343','2000-5-00328','500','0',NULL), ('344','2000-5-00329','500','0',NULL), ('345','2000-5-00330','500','0',NULL), ('346','2000-5-00331','500','0',NULL), ('347','2000-5-00332','500','0',NULL), ('348','2000-5-00333','500','0',NULL), ('349','2000-5-00334','500','0',NULL), ('350','2000-5-00335','500','0',NULL), ('351','2000-5-00336','500','0',NULL), ('352','2000-5-00337','500','0',NULL), ('353','2000-5-00338','500','0',NULL), ('354','2000-5-00339','500','0',NULL), ('355','2000-5-00340','500','0',NULL), ('356','2000-5-00341','500','0',NULL), ('357','2000-5-00342','500','0',NULL), ('358','2000-5-00343','500','0',NULL), ('359','2000-5-00344','500','0',NULL), ('360','2000-5-00345','500','0',NULL), ('361','2000-5-00346','500','0',NULL), ('362','2000-5-00347','500','0',NULL), ('363','2000-5-00348','500','0',NULL), ('364','2000-5-00349','500','0',NULL), ('365','2000-5-00350','500','0',NULL), ('366','2000-5-00351','500','0',NULL), ('367','2000-5-00352','500','0',NULL), ('368','2000-5-00353','500','0',NULL), ('369','2000-5-00354','500','0',NULL), ('370','2000-5-00355','500','0',NULL), ('371','2000-5-00356','500','0',NULL), ('372','2000-5-00357','500','0',NULL), ('373','2000-5-00358','500','0',NULL), ('374','2000-5-00359','500','0',NULL), ('375','2000-5-00360','500','0',NULL), ('376','2000-5-00361','500','0',NULL), ('377','2000-5-00362','500','0',NULL), ('378','2000-5-00363','500','0',NULL), ('379','2000-5-00364','500','0',NULL), ('380','2000-5-00365','500','0',NULL), ('381','2000-5-00366','500','0',NULL), ('382','2000-5-00367','500','1',NULL), ('383','2000-5-00368','500','1',NULL), ('384','2000-5-00369','500','1',NULL), ('385','2000-5-00370','500','0',NULL), ('386','2000-5-00371','500','1',NULL), ('387','2000-5-00372','500','1',NULL), ('388','2000-5-00373','500','1',NULL), ('389','2000-5-00374','500','1',NULL), ('390','2000-5-00375','500','1',NULL), ('391','2000-5-00376','500','1',NULL), ('392','2000-5-00377','500','1',NULL), ('393','2000-5-00378','500','0',NULL), ('394','2000-5-00379','500','0',NULL), ('395','2000-5-00380','500','1',NULL), ('396','2000-5-00381','500','0',NULL), ('397','2000-5-00382','500','0',NULL), ('398','2000-5-00383','500','0',NULL), ('399','2000-5-00384','500','0',NULL), ('400','2000-5-00385','500','0',NULL);
INSERT INTO `gift_cards` VALUES ('401','2000-5-00386','500','0',NULL), ('402','2000-5-00387','500','0',NULL), ('403','2000-5-00388','500','0',NULL), ('404','2000-5-00389','500','0',NULL), ('405','2000-5-00390','500','0',NULL), ('406','2000-5-00391','500','0',NULL), ('407','2000-5-00392','500','0',NULL), ('408','2000-5-00393','500','0',NULL), ('409','2000-5-00394','500','0',NULL), ('410','2000-5-00395','500','0',NULL), ('411','2000-5-00396','500','0',NULL), ('412','2000-5-00397','500','0',NULL), ('413','2000-5-00398','500','0',NULL), ('414','2000-5-00399','500','0',NULL), ('415','2000-5-00400','500','0',NULL), ('416','2000-5-00401','500','0',NULL), ('417','2000-5-00402','500','0',NULL), ('418','2000-5-00403','500','0',NULL), ('419','2000-5-00404','500','0',NULL), ('420','2000-5-00405','500','0',NULL), ('421','2000-5-00406','500','0',NULL), ('422','2000-5-00407','500','0',NULL), ('423','2000-5-00408','500','0',NULL), ('424','2000-5-00409','500','0',NULL), ('425','2000-5-00410','500','0',NULL), ('426','2000-5-00411','500','0',NULL), ('427','2000-5-00412','500','0',NULL), ('428','2000-5-00413','500','0',NULL), ('429','2000-5-00414','500','0',NULL), ('430','2000-5-00415','500','0',NULL), ('431','2000-5-00416','500','0',NULL), ('432','2000-5-00417','500','0',NULL), ('433','2000-5-00418','500','0',NULL), ('434','2000-5-00419','500','0',NULL), ('435','2000-5-00420','500','0',NULL), ('436','2000-5-00421','500','0',NULL), ('437','2000-5-00422','500','0',NULL), ('438','2000-5-00423','500','0',NULL), ('439','2000-5-00424','500','0',NULL), ('440','2000-5-00425','500','0',NULL), ('441','2000-5-00426','500','0',NULL), ('442','2000-5-00427','500','0',NULL), ('443','2000-5-00428','500','0',NULL), ('444','2000-5-00429','500','0',NULL), ('445','2000-5-00430','500','0',NULL), ('446','2000-5-00431','500','0',NULL), ('447','2000-5-00432','500','0',NULL), ('448','2000-5-00433','500','0',NULL), ('449','2000-5-00434','500','0',NULL), ('450','2000-5-00435','500','0',NULL), ('451','2000-5-00436','500','0',NULL), ('452','2000-5-00437','500','0',NULL), ('453','2000-5-00438','500','0',NULL), ('454','2000-5-00439','500','0',NULL), ('455','2000-5-00440','500','0',NULL), ('456','2000-5-00441','500','0',NULL), ('457','2000-5-00442','500','0',NULL), ('458','2000-5-00443','500','0',NULL), ('459','2000-5-00444','500','0',NULL), ('460','2000-5-00445','500','0',NULL), ('461','2000-5-00446','500','0',NULL), ('462','2000-5-00447','500','0',NULL), ('463','2000-5-00448','500','0',NULL), ('464','2000-5-00449','500','0',NULL), ('465','2000-5-00450','500','0',NULL), ('466','2000-5-00451','500','0',NULL), ('467','2000-5-00452','500','0',NULL), ('468','2000-5-00453','500','0',NULL), ('469','2000-5-00454','500','0',NULL), ('470','2000-5-00455','500','0',NULL), ('471','2000-5-00456','500','0',NULL), ('472','2000-5-00457','500','0',NULL), ('473','2000-5-00458','500','0',NULL), ('474','2000-5-00459','500','0',NULL), ('475','2000-5-00460','500','0',NULL), ('476','2000-5-00461','500','0',NULL), ('477','2000-5-00462','500','0',NULL), ('478','2000-5-00463','500','0',NULL), ('479','2000-5-00464','500','0',NULL), ('480','2000-5-00465','500','0',NULL), ('481','2000-5-00466','500','0',NULL), ('482','2000-5-00467','500','0',NULL), ('483','2000-5-00468','500','0',NULL), ('484','2000-5-00469','500','0',NULL), ('485','2000-5-00470','500','0',NULL), ('486','2000-5-00471','500','0',NULL), ('487','2000-5-00472','500','0',NULL), ('488','2000-5-00473','500','0',NULL), ('489','2000-5-00474','500','0',NULL), ('490','2000-5-00475','500','0',NULL), ('491','2000-5-00476','500','0',NULL), ('492','2000-5-00477','500','0',NULL), ('493','2000-5-00478','500','0',NULL), ('494','2000-5-00479','500','0',NULL), ('495','2000-5-00480','500','0',NULL), ('496','2000-5-00481','500','0',NULL), ('497','2000-5-00482','500','0',NULL), ('498','2000-5-00483','500','0',NULL), ('499','2000-5-00484','500','0',NULL), ('500','2000-5-00485','500','0',NULL);
INSERT INTO `gift_cards` VALUES ('501','2000-5-00486','500','0',NULL), ('502','2000-5-00487','500','0',NULL), ('503','2000-5-00488','500','0',NULL), ('504','2000-5-00489','500','0',NULL), ('505','2000-5-00490','500','0',NULL), ('506','2000-5-00491','500','0',NULL), ('507','2000-5-00492','500','0',NULL), ('508','2000-5-00493','500','0',NULL), ('509','2000-5-00494','500','0',NULL), ('510','2000-5-00495','500','0',NULL), ('511','2000-5-00496','500','0',NULL), ('512','2000-5-00497','500','0',NULL), ('513','2000-5-00498','500','0',NULL), ('514','2000-5-00499','500','0',NULL), ('515','2000-5-00500','500','0',NULL), ('516','2019-11-0001','1000','0',NULL), ('517','2019-11-0002','1000','1',NULL), ('518','2019-11-0003','1000','0',NULL), ('519','2019-11-0004','1000','0',NULL), ('520','2019-11-0005','1000','0',NULL), ('521','2019-11-0006','1000','0',NULL), ('522','2019-11-0007','1000','1',NULL), ('523','2019-11-0008','1000','0',NULL), ('524','2019-11-0009','1000','0',NULL), ('525','2019-11-0010','1000','0',NULL), ('526','2019-11-0011','1000','0',NULL), ('527','2019-11-0012','1000','0',NULL), ('528','2019-11-0013','1000','0',NULL), ('529','2019-11-0014','1000','0',NULL), ('530','2019-11-0015','1000','0',NULL), ('531','2019-11-0016','1000','0',NULL), ('532','2019-11-0017','1000','0',NULL), ('533','2019-11-0018','1000','0',NULL), ('534','2019-11-0019','1000','0',NULL), ('535','2019-11-0020','1000','0',NULL), ('536','2019-11-0021','1000','0',NULL), ('537','2019-11-0022','1000','0',NULL), ('538','2019-11-0023','1000','0',NULL), ('539','2019-11-0024','1000','0',NULL), ('540','2019-11-0025','1000','0',NULL), ('541','2019-11-0026','1000','0',NULL), ('542','2019-11-0027','1000','0',NULL), ('543','2019-11-0028','1000','0',NULL), ('544','2019-11-0029','1000','0',NULL), ('545','2019-11-0030','1000','1',NULL), ('546','2019-11-0031','1000','0',NULL), ('547','2019-11-0032','1000','0',NULL), ('548','2019-11-0033','1000','0',NULL), ('549','2019-11-0034','1000','0',NULL), ('550','2019-11-0035','1000','0',NULL), ('551','2019-11-0036','1000','0',NULL), ('552','2019-11-0037','1000','0',NULL), ('553','2019-11-0038','1000','0',NULL), ('554','2019-11-0039','1000','0',NULL), ('555','2019-11-0040','1000','0',NULL), ('556','2019-11-0041','1000','0',NULL), ('557','2019-11-0042','1000','1',NULL), ('558','2019-11-0043','1000','0',NULL), ('559','2019-11-0044','1000','0',NULL), ('560','2019-11-0045','1000','0',NULL), ('561','2019-11-0046','1000','0',NULL), ('562','2019-11-0047','1000','1',NULL), ('563','2019-11-0048','1000','0',NULL), ('564','2019-11-0049','1000','0',NULL), ('565','2019-11-0050','1000','0',NULL), ('566','2019-11-0051','1000','0',NULL), ('567','2019-11-0052','1000','0',NULL), ('568','2019-11-0053','1000','0',NULL), ('569','2019-11-0054','1000','0',NULL), ('570','2019-11-0055','1000','0',NULL), ('571','2019-11-0056','1000','0',NULL), ('572','2019-11-0057','1000','0',NULL), ('573','2019-11-0058','1000','0',NULL), ('574','2019-11-0059','1000','0',NULL), ('575','2019-11-0060','1000','0',NULL), ('576','2019-11-0061','1000','0',NULL), ('577','2019-11-0062','1000','0',NULL), ('578','2019-11-0063','1000','0',NULL), ('579','2019-11-0064','1000','0',NULL), ('580','2019-11-0065','1000','0',NULL), ('581','2019-11-0066','1000','0',NULL), ('582','2019-11-0067','1000','0',NULL), ('583','2019-11-0068','1000','0',NULL), ('584','2019-11-0069','1000','0',NULL), ('585','2019-11-0070','1000','0',NULL), ('586','2019-11-0071','1000','0',NULL), ('587','2019-11-0072','1000','0',NULL), ('588','2019-11-0073','1000','0',NULL), ('589','2019-11-0074','1000','0',NULL), ('590','2019-11-0075','1000','0',NULL), ('591','2019-11-0076','1000','0',NULL), ('592','2019-11-0077','1000','0',NULL), ('593','2019-11-0078','1000','0',NULL), ('594','2019-11-0079','1000','0',NULL), ('595','2019-11-0080','1000','0',NULL), ('596','2019-11-0081','1000','0',NULL), ('597','2019-11-0082','1000','0',NULL), ('598','2019-11-0083','1000','0',NULL), ('599','2019-11-0084','1000','0',NULL), ('600','2019-11-0085','1000','0',NULL);
INSERT INTO `gift_cards` VALUES ('601','2019-11-0086','1000','0',NULL), ('602','2019-11-0087','1000','0',NULL), ('603','2019-11-0088','1000','0',NULL), ('604','2019-11-0089','1000','0',NULL), ('605','2019-11-0090','1000','0',NULL), ('606','2019-11-0091','1000','0',NULL), ('607','2019-11-0092','1000','0',NULL), ('608','2019-11-0093','1000','0',NULL), ('609','2019-11-0094','1000','0',NULL), ('610','2019-11-0095','1000','0',NULL), ('611','2019-11-0096','1000','0',NULL), ('612','2019-11-0097','1000','0',NULL), ('613','2019-11-0098','1000','0',NULL), ('614','2019-11-0099','1000','0',NULL), ('615','2019-11-0100','1000','0',NULL), ('616','2019-11-0101','1000','0',NULL), ('617','2019-11-0102','1000','0',NULL), ('618','2019-11-0103','1000','0',NULL), ('619','2019-11-0104','1000','0',NULL), ('620','2019-11-0105','1000','0',NULL), ('621','2019-11-0106','1000','0',NULL), ('622','2019-11-0107','1000','0',NULL), ('623','2019-11-0108','1000','0',NULL), ('624','2019-11-0109','1000','0',NULL), ('625','2019-11-0110','1000','0',NULL), ('626','2019-11-0111','1000','0',NULL), ('627','2019-11-0112','1000','0',NULL), ('628','2019-11-0113','1000','0',NULL), ('629','2019-11-0114','1000','0',NULL), ('630','2019-11-0115','1000','0',NULL), ('631','2019-11-0116','1000','0',NULL), ('632','2019-11-0117','1000','0',NULL), ('633','2019-11-0118','1000','0',NULL), ('634','2019-11-0119','1000','0',NULL), ('635','2019-11-0120','1000','0',NULL), ('636','2019-11-0121','1000','0',NULL), ('637','2019-11-0122','1000','0',NULL), ('638','2019-11-0123','1000','0',NULL), ('639','2019-11-0124','1000','0',NULL), ('640','2019-11-0125','1000','0',NULL), ('641','2019-11-0126','1000','0',NULL), ('642','2019-11-0127','1000','0',NULL), ('643','2019-11-0128','1000','0',NULL), ('644','2019-11-0129','1000','0',NULL), ('645','2019-11-0130','1000','0',NULL), ('646','2019-11-0131','1000','0',NULL), ('647','2019-11-0132','1000','0',NULL), ('648','2019-11-0133','1000','0',NULL), ('649','2019-11-0134','1000','0',NULL), ('650','2019-11-0135','1000','0',NULL), ('651','2019-11-0136','1000','0',NULL), ('652','2019-11-0137','1000','0',NULL), ('653','2019-11-0138','1000','0',NULL), ('654','2019-11-0139','1000','0',NULL), ('655','2019-11-0140','1000','0',NULL), ('656','2019-11-0141','1000','0',NULL), ('657','2019-11-0142','1000','0',NULL), ('658','2019-11-0143','1000','0',NULL), ('659','2019-11-0144','1000','0',NULL), ('660','2019-11-0145','1000','0',NULL), ('661','2019-11-0146','1000','0',NULL), ('662','2019-11-0147','1000','0',NULL), ('663','2019-11-0148','1000','0',NULL), ('664','2019-11-0149','1000','0',NULL), ('665','2019-11-0150','1000','0',NULL), ('666','2019-11-0151','1000','0',NULL), ('667','2019-11-0152','1000','0',NULL), ('668','2019-11-0153','1000','0',NULL), ('669','2019-11-0154','1000','0',NULL), ('670','2019-11-0155','1000','0',NULL), ('671','2019-11-0156','1000','0',NULL), ('672','2019-11-0157','1000','0',NULL), ('673','2019-11-0158','1000','0',NULL), ('674','2019-11-0159','1000','0',NULL), ('675','2019-11-0160','1000','0',NULL), ('676','2019-11-0161','1000','0',NULL), ('677','2019-11-0162','1000','0',NULL), ('678','2019-11-0163','1000','0',NULL), ('679','2019-11-0164','1000','0',NULL), ('680','2019-11-0165','1000','0',NULL), ('681','2019-11-0166','1000','0',NULL), ('682','2019-11-0167','1000','0',NULL), ('683','2019-11-0168','1000','0',NULL), ('684','2019-11-0169','1000','0',NULL), ('685','2019-11-0170','1000','0',NULL), ('686','2019-11-0171','1000','0',NULL), ('687','2019-11-0172','1000','0',NULL), ('688','2019-11-0173','1000','0',NULL), ('689','2019-11-0174','1000','0',NULL), ('690','2019-11-0175','1000','0',NULL), ('691','2019-11-0176','1000','0',NULL), ('692','2019-11-0177','1000','0',NULL), ('693','2019-11-0178','1000','0',NULL), ('694','2019-11-0179','1000','0',NULL), ('695','2019-11-0180','1000','0',NULL), ('696','2019-11-0181','1000','0',NULL), ('697','2019-11-0182','1000','0',NULL), ('698','2019-11-0183','1000','0',NULL), ('699','2019-11-0184','1000','0',NULL), ('700','2019-11-0185','1000','0',NULL);
INSERT INTO `gift_cards` VALUES ('701','2019-11-0186','1000','0',NULL), ('702','2019-11-0187','1000','0',NULL), ('703','2019-11-0188','1000','0',NULL), ('704','2019-11-0189','1000','0',NULL), ('705','2019-11-0190','1000','0',NULL), ('706','2019-11-0191','1000','0',NULL), ('707','2019-11-0192','1000','0',NULL), ('708','2019-11-0193','1000','0',NULL), ('709','2019-11-0194','1000','0',NULL), ('710','2019-11-0195','1000','0',NULL), ('711','2019-11-0196','1000','0',NULL), ('712','2019-11-0197','1000','0',NULL), ('713','2019-11-0198','1000','0',NULL), ('714','2019-11-0199','1000','0',NULL), ('715','2019-11-0200','1000','0',NULL), ('716','2019-11-0201','1000','0',NULL), ('717','2019-11-0202','1000','0',NULL), ('718','2019-11-0203','1000','0',NULL), ('719','2019-11-0204','1000','0',NULL), ('720','2019-11-0205','1000','0',NULL), ('721','2019-11-0206','1000','0',NULL), ('722','2019-11-0207','1000','0',NULL), ('723','2019-11-0208','1000','0',NULL), ('724','2019-11-0209','1000','0',NULL), ('725','2019-11-0210','1000','0',NULL), ('726','2019-11-0211','1000','0',NULL), ('727','2019-11-0212','1000','0',NULL), ('728','2019-11-0213','1000','0',NULL), ('729','2019-11-0214','1000','0',NULL), ('730','2019-11-0215','1000','0',NULL), ('731','2019-11-0216','1000','0',NULL), ('732','2019-11-0217','1000','0',NULL), ('733','2019-11-0218','1000','0',NULL), ('734','2019-11-0219','1000','0',NULL), ('735','2019-11-0220','1000','0',NULL), ('736','2019-11-0221','1000','0',NULL), ('737','2019-11-0222','1000','0',NULL), ('738','2019-11-0223','1000','0',NULL), ('739','2019-11-0224','1000','0',NULL), ('740','2019-11-0225','1000','0',NULL), ('741','2019-11-0226','1000','0',NULL), ('742','2019-11-0227','1000','0',NULL), ('743','2019-11-0228','1000','0',NULL), ('744','2019-11-0229','1000','0',NULL), ('745','2019-11-0230','1000','0',NULL), ('746','2019-11-0231','1000','0',NULL), ('747','2019-11-0232','1000','0',NULL), ('748','2019-11-0233','1000','0',NULL), ('749','2019-11-0234','1000','0',NULL), ('750','2019-11-0235','1000','0',NULL), ('751','2019-11-0236','1000','0',NULL), ('752','2019-11-0237','1000','0',NULL), ('753','2019-11-0238','1000','0',NULL), ('754','2019-11-0239','1000','0',NULL), ('755','2019-11-0240','1000','0',NULL), ('756','2019-11-0241','1000','0',NULL), ('757','2019-11-0242','1000','0',NULL), ('758','2019-11-0243','1000','0',NULL), ('759','2019-11-0244','1000','0',NULL), ('760','2019-11-0245','1000','0',NULL), ('761','2019-11-0246','1000','0',NULL), ('762','2019-11-0247','1000','0',NULL), ('763','2019-11-0248','1000','0',NULL), ('764','2019-11-0249','1000','0',NULL), ('765','2019-11-0250','1000','0',NULL), ('766','2019-11-0251','1000','0',NULL), ('767','2019-11-0252','1000','0',NULL), ('768','2019-11-0253','1000','0',NULL), ('769','2019-11-0254','1000','0',NULL), ('770','2019-11-0255','1000','0',NULL), ('771','2019-11-0256','1000','0',NULL), ('772','2019-11-0257','1000','0',NULL), ('773','2019-11-0258','1000','0',NULL), ('774','2019-11-0259','1000','0',NULL), ('775','2019-11-0260','1000','0',NULL), ('776','2019-11-0261','1000','0',NULL), ('777','2019-11-0262','1000','0',NULL), ('778','2019-11-0263','1000','0',NULL), ('779','2019-11-0264','1000','0',NULL), ('780','2019-11-0265','1000','0',NULL), ('781','2019-11-0266','1000','0',NULL), ('782','2019-11-0267','1000','0',NULL), ('783','2019-11-0268','1000','0',NULL), ('784','2019-11-0269','1000','0',NULL), ('785','2019-11-0270','1000','0',NULL), ('786','2019-11-0271','1000','0',NULL), ('787','2019-11-0272','1000','0',NULL), ('788','2019-11-0273','1000','0',NULL), ('789','2019-11-0274','1000','0',NULL), ('790','2019-11-0275','1000','0',NULL), ('791','2019-11-0276','1000','0',NULL), ('792','2019-11-0277','1000','0',NULL), ('793','2019-11-0278','1000','0',NULL), ('794','2019-11-0279','1000','0',NULL), ('795','2019-11-0280','1000','0',NULL), ('796','2019-11-0281','1000','0',NULL), ('797','2019-11-0282','1000','0',NULL), ('798','2019-11-0283','1000','0',NULL), ('799','2019-11-0284','1000','0',NULL), ('800','2019-11-0285','1000','0',NULL);
INSERT INTO `gift_cards` VALUES ('801','2019-11-0286','1000','0',NULL), ('802','2019-11-0287','1000','0',NULL), ('803','2019-11-0288','1000','0',NULL), ('804','2019-11-0289','1000','0',NULL), ('805','2019-11-0290','1000','0',NULL), ('806','2019-11-0291','1000','0',NULL), ('807','2019-11-0292','1000','0',NULL), ('808','2019-11-0293','1000','0',NULL), ('809','2019-11-0294','1000','0',NULL), ('810','2019-11-0295','1000','0',NULL), ('811','2019-11-0296','1000','0',NULL), ('812','2019-11-0297','1000','0',NULL), ('813','2019-11-0298','1000','0',NULL), ('814','2019-11-0299','1000','0',NULL), ('815','2019-11-0300','1000','0',NULL), ('816','2019-11-0301','1000','0',NULL), ('817','2019-11-0302','1000','0',NULL), ('818','2019-11-0303','1000','0',NULL), ('819','2019-11-0304','1000','0',NULL), ('820','2019-11-0305','1000','0',NULL), ('821','2019-11-0306','1000','0',NULL), ('822','2019-11-0307','1000','0',NULL), ('823','2019-11-0308','1000','0',NULL), ('824','2019-11-0309','1000','0',NULL), ('825','2019-11-0310','1000','0',NULL), ('826','2019-11-0311','1000','0',NULL), ('827','2019-11-0312','1000','0',NULL), ('828','2019-11-0313','1000','0',NULL), ('829','2019-11-0314','1000','0',NULL), ('830','2019-11-0315','1000','0',NULL), ('831','2019-11-0316','1000','0',NULL), ('832','2019-11-0317','1000','0',NULL), ('833','2019-11-0318','1000','0',NULL), ('834','2019-11-0319','1000','0',NULL), ('835','2019-11-0320','1000','0',NULL), ('836','2019-11-0321','1000','0',NULL), ('837','2019-11-0322','1000','0',NULL), ('838','2019-11-0323','1000','0',NULL), ('839','2019-11-0324','1000','0',NULL), ('840','2019-11-0325','1000','0',NULL), ('841','2019-11-0326','1000','0',NULL), ('842','2019-11-0327','1000','0',NULL), ('843','2019-11-0328','1000','0',NULL), ('844','2019-11-0329','1000','0',NULL), ('845','2019-11-0330','1000','0',NULL), ('846','2019-11-0331','1000','0',NULL), ('847','2019-11-0332','1000','0',NULL), ('848','2019-11-0333','1000','0',NULL), ('849','2019-11-0334','1000','0',NULL), ('850','2019-11-0335','1000','0',NULL), ('851','2019-11-0336','1000','0',NULL), ('852','2019-11-0337','1000','0',NULL), ('853','2019-11-0338','1000','0',NULL), ('854','2019-11-0339','1000','0',NULL), ('855','2019-11-0340','1000','0',NULL), ('856','2019-11-0341','1000','0',NULL), ('857','2019-11-0342','1000','0',NULL), ('858','2019-11-0343','1000','0',NULL), ('859','2019-11-0344','1000','0',NULL), ('860','2019-11-0345','1000','0',NULL), ('861','2019-11-0346','1000','0',NULL), ('862','2019-11-0347','1000','0',NULL), ('863','2019-11-0348','1000','0',NULL), ('864','2019-11-0349','1000','0',NULL), ('865','2019-11-0350','1000','0',NULL), ('866','2019-11-0351','1000','0',NULL), ('867','2019-11-0352','1000','0',NULL), ('868','2019-11-0353','1000','0',NULL), ('869','2019-11-0354','1000','0',NULL), ('870','2019-11-0355','1000','0',NULL), ('871','2019-11-0356','1000','0',NULL), ('872','2019-11-0357','1000','0',NULL), ('873','2019-11-0358','1000','0',NULL), ('874','2019-11-0359','1000','0',NULL), ('875','2019-11-0360','1000','0',NULL), ('876','2019-11-0361','1000','0',NULL), ('877','2019-11-0362','1000','0',NULL), ('878','2019-11-0363','1000','0',NULL), ('879','2019-11-0364','1000','0',NULL), ('880','2019-11-0365','1000','0',NULL), ('881','2019-11-0366','1000','0',NULL), ('882','2019-11-0367','1000','0',NULL), ('883','2019-11-0368','1000','0',NULL), ('884','2019-11-0369','1000','0',NULL), ('885','2019-11-0370','1000','0',NULL), ('886','2019-11-0371','1000','0',NULL), ('887','2019-11-0372','1000','0',NULL), ('888','2019-11-0373','1000','0',NULL), ('889','2019-11-0374','1000','0',NULL), ('890','2019-11-0375','1000','0',NULL), ('891','2019-11-0376','1000','0',NULL), ('892','2019-11-0377','1000','0',NULL), ('893','2019-11-0378','1000','0',NULL), ('894','2019-11-0379','1000','0',NULL), ('895','2019-11-0380','1000','0',NULL), ('896','2019-11-0381','1000','0',NULL), ('897','2019-11-0382','1000','0',NULL), ('898','2019-11-0383','1000','0',NULL), ('899','2019-11-0384','1000','0',NULL), ('900','2019-11-0385','1000','0',NULL);
INSERT INTO `gift_cards` VALUES ('901','2019-11-0386','1000','0',NULL), ('902','2019-11-0387','1000','0',NULL), ('903','2019-11-0388','1000','0',NULL), ('904','2019-11-0389','1000','0',NULL), ('905','2019-11-0390','1000','0',NULL), ('906','2019-11-0391','1000','0',NULL), ('907','2019-11-0392','1000','0',NULL), ('908','2019-11-0393','1000','0',NULL), ('909','2019-11-0394','1000','0',NULL), ('910','2019-11-0395','1000','0',NULL), ('911','2019-11-0396','1000','0',NULL), ('912','2019-11-0397','1000','0',NULL), ('913','2019-11-0398','1000','0',NULL), ('914','2019-11-0399','1000','0',NULL), ('915','2019-11-0400','1000','0',NULL), ('916','2019-11-0401','1000','0',NULL), ('917','2019-11-0402','1000','0',NULL), ('918','2019-11-0403','1000','0',NULL), ('919','2019-11-0404','1000','0',NULL), ('920','2019-11-0405','1000','0',NULL), ('921','2019-11-0406','1000','0',NULL), ('922','2019-11-0407','1000','0',NULL), ('923','2019-11-0408','1000','0',NULL), ('924','2019-11-0409','1000','0',NULL), ('925','2019-11-0410','1000','0',NULL), ('926','2019-11-0411','1000','0',NULL), ('927','2019-11-0412','1000','0',NULL), ('928','2019-11-0413','1000','0',NULL), ('929','2019-11-0414','1000','0',NULL), ('930','2019-11-0415','1000','0',NULL), ('931','2019-11-0416','1000','0',NULL), ('932','2019-11-0417','1000','0',NULL), ('933','2019-11-0418','1000','0',NULL), ('934','2019-11-0419','1000','0',NULL), ('935','2019-11-0420','1000','0',NULL), ('936','2019-11-0421','1000','0',NULL), ('937','2019-11-0422','1000','0',NULL), ('938','2019-11-0423','1000','0',NULL), ('939','2019-11-0424','1000','0',NULL), ('940','2019-11-0425','1000','0',NULL), ('941','2019-11-0426','1000','0',NULL), ('942','2019-11-0427','1000','0',NULL), ('943','2019-11-0428','1000','0',NULL), ('944','2019-11-0429','1000','0',NULL), ('945','2019-11-0430','1000','0',NULL), ('946','2019-11-0431','1000','0',NULL), ('947','2019-11-0432','1000','0',NULL), ('948','2019-11-0433','1000','0',NULL), ('949','2019-11-0434','1000','0',NULL), ('950','2019-11-0435','1000','0',NULL), ('951','2019-11-0436','1000','0',NULL), ('952','2019-11-0437','1000','0',NULL), ('953','2019-11-0438','1000','0',NULL), ('954','2019-11-0439','1000','0',NULL), ('955','2019-11-0440','1000','0',NULL), ('956','2019-11-0441','1000','0',NULL), ('957','2019-11-0442','1000','0',NULL), ('958','2019-11-0443','1000','0',NULL), ('959','2019-11-0444','1000','0',NULL), ('960','2019-11-0445','1000','0',NULL), ('961','2019-11-0446','1000','0',NULL), ('962','2019-11-0447','1000','0',NULL), ('963','2019-11-0448','1000','0',NULL), ('964','2019-11-0449','1000','0',NULL), ('965','2019-11-0450','1000','0',NULL), ('966','2019-11-0451','1000','0',NULL), ('967','2019-11-0452','1000','0',NULL), ('968','2019-11-0453','1000','0',NULL), ('969','2019-11-0454','1000','0',NULL), ('970','2019-11-0455','1000','0',NULL), ('971','2019-11-0456','1000','0',NULL), ('972','2019-11-0457','1000','0',NULL), ('973','2019-11-0458','1000','0',NULL), ('974','2019-11-0459','1000','0',NULL), ('975','2019-11-0460','1000','0',NULL), ('976','2019-11-0461','1000','0',NULL), ('977','2019-11-0462','1000','0',NULL), ('978','2019-11-0463','1000','0',NULL), ('979','2019-11-0464','1000','0',NULL), ('980','2019-11-0465','1000','0',NULL), ('981','2019-11-0466','1000','0',NULL), ('982','2019-11-0467','1000','0',NULL), ('983','2019-11-0468','1000','0',NULL), ('984','2019-11-0469','1000','0',NULL), ('985','2019-11-0470','1000','0',NULL), ('986','2019-11-0471','1000','0',NULL), ('987','2019-11-0472','1000','0',NULL), ('988','2019-11-0473','1000','0',NULL), ('989','2019-11-0474','1000','0',NULL), ('990','2019-11-0475','1000','0',NULL), ('991','2019-11-0476','1000','0',NULL), ('992','2019-11-0477','1000','0',NULL), ('993','2019-11-0478','1000','0',NULL), ('994','2019-11-0479','1000','0',NULL), ('995','2019-11-0480','1000','0',NULL), ('996','2019-11-0481','1000','0',NULL), ('997','2019-11-0482','1000','0',NULL), ('998','2019-11-0483','1000','0',NULL), ('999','2019-11-0484','1000','0',NULL), ('1000','2019-11-0485','1000','0',NULL);
INSERT INTO `gift_cards` VALUES ('1001','2019-11-0486','1000','0',NULL), ('1002','2019-11-0487','1000','0',NULL), ('1003','2019-11-0488','1000','0',NULL), ('1004','2019-11-0489','1000','0',NULL), ('1005','2019-11-0490','1000','0',NULL), ('1006','2019-11-0491','1000','0',NULL), ('1007','2019-11-0492','1000','0',NULL), ('1008','2019-11-0493','1000','0',NULL), ('1009','2019-11-0494','1000','0',NULL), ('1010','2019-11-0495','1000','0',NULL), ('1011','2019-11-0496','1000','0',NULL), ('1012','2019-11-0497','1000','0',NULL), ('1013','2019-11-0498','1000','0',NULL), ('1014','2019-11-0499','1000','0',NULL), ('1015','2019-11-0500','1000','0',NULL), ('1016','200-5-00129','500','0',NULL), ('1017','2000-5-00130','500','0',NULL), ('1018','2000-5-00125','0','0',NULL), ('1019','2000-5-00125','500','0',NULL), ('1020','2000-5-00128','500','0',NULL), ('1021','2000-5-00123','0','0',NULL), ('1022','2000-5-00123','500','0',NULL), ('1023','2000-5-00124','500','0',NULL), ('1024','2000-5-00031','500','0',NULL), ('1025','2000-5-00373','500','1',NULL), ('1026','2000-5-00374','500','1',NULL), ('1027','2000-5-00371','500','1',NULL), ('1028','000342','500','1',NULL), ('1029','000341','500','1',NULL), ('1030','T1011','500','1',NULL), ('1031','T1010','1000','1',NULL), ('1032','10001','1000','1',NULL), ('1033','10002','1000','1',NULL), ('1034','5001','500','1',NULL), ('1035','041112','500','1',NULL), ('1036','1000','1000','1',NULL), ('1037','999','500','1',NULL), ('1038','888','500','1',NULL), ('1039','P500 GIFT CERTIFICATE','500','1',NULL), ('1040','038194','500','1',NULL), ('1041','038186','500','1',NULL), ('1042','038186','500','1',NULL), ('1043','038186','500','1',NULL), ('1044','038194','500','1',NULL), ('1045','036285','500','1',NULL), ('1046','036284','500','1',NULL), ('1047','036283','500','1',NULL), ('1048','041112','500','1',NULL), ('1049','035167','500','1',NULL);
INSERT INTO `images` VALUES ('20','slider2.jpg','uploads/splash/slider2.jpg',NULL,'splash_images',NULL,NULL,'0'), ('21','slider3.jpg','uploads/splash/slider3.jpg',NULL,'splash_images',NULL,NULL,'0'), ('22','idlebackground.jpg','uploads/splash/idlebackground.jpg',NULL,'background_images',NULL,NULL,'0'), ('23','thankyoubackground.jpg','uploads/splash/thankyoubackground.jpg',NULL,'endtrans_images',NULL,NULL,'0'), ('25','slidernew.jpg','uploads/splash/slidernew.jpg',NULL,'splash_images',NULL,NULL,'0'), ('27','slider4.jpg','uploads/splash/slider4.jpg',NULL,'splash_images',NULL,NULL,'0'), ('28','slider5.jpg','uploads/splash/slider5.jpg',NULL,'splash_images',NULL,NULL,'0');
INSERT INTO `item_types` VALUES ('1','Not For Resale'), ('2','For Resale');
INSERT INTO `locations` VALUES ('1','313','warehouse','0','3079','2019-11-06 14:39:10');
INSERT INTO `logs` VALUES ('1','1','Jessie R. Alison  Logged In.',NULL,'2021-07-27 09:38:19','login','9'), ('2','1','Jessie R. Alison  Started Shift.','1','2021-07-27 09:41:09','Shift','9'), ('3','1','Jessie R. Alison  Cash in 5000',NULL,'2021-07-27 09:41:10','Drawer','9'), ('4','1','Jessie R. Alison  Added New Sales Order #1','1','2021-07-27 09:41:17','Sales Order','9'), ('5','1','Jessie R. Alison  Added Payment 1095.00 on Sales Order #1','1','2021-07-27 09:41:20','Sales Order','9'), ('6','1','Jessie R. Alison  Settled Payment on Sales Order #1 Reference #00000001','1','2021-07-27 09:41:20','Sales Order','9'), ('7','1','Jessie R. Alison  Printed Receipt on Sales Order #1 Reference #00000001','1','2021-07-27 09:41:25','Sales Order','20'), ('8','1','Jessie R. Alison  Added New Sales Order #2','2','2021-07-27 09:41:55','Sales Order','20'), ('9','1','Jessie R. Alison  Added Payment 1095.00 on Sales Order #2','2','2021-07-27 09:41:58','Sales Order','20'), ('10','1','Jessie R. Alison  Settled Payment on Sales Order #2 Reference #00000002','2','2021-07-27 09:41:58','Sales Order','20'), ('11','1','Jessie R. Alison  Printed Receipt on Sales Order #2 Reference #00000002','2','2021-07-27 09:42:00','Sales Order',NULL);
INSERT INTO `loyalty_cards` VALUES ('1','00000001','1','0','4','2018-02-09 14:42:24','0',NULL);
INSERT INTO `megamall` VALUES ('1','30','110000055','3','SAP','2');
INSERT INTO `megaworld` VALUES ('1','TCLVOR11','01','C:/MEGAWORLD/');
INSERT INTO `menus` VALUES ('1','M01','M01','Max Chicken','Max Chicken','1','1','0','350','2021-07-26 10:23:51',NULL,'0','0','0','0','0',NULL,NULL,'0','max'), ('2','Y01','Y01','Chicken Pizza','Chicken Pizza','1','1','0','380','2021-07-26 10:24:47',NULL,'0','0','0','0','0',NULL,NULL,'0','yellowcab'), ('3','J01','J01','JJ Orange Juice','JJ Orange Juice','4','2','0','120','2021-07-26 10:26:41',NULL,'0','0','0','0','0',NULL,NULL,'0','jambajuice'), ('4','M02','M02','Lemon Juice','Lemon Juice','4','2','0','90','2021-07-26 10:27:19',NULL,'0','0','0','0','0',NULL,NULL,'0','max'), ('5','P01','P01','Iced tea','Iced tea','2','2','0','95','2021-07-26 10:28:27',NULL,'0','0','0','0','0',NULL,NULL,'0','pancakehouse'), ('6','M03','M03','Fried Rice','Fried Rice','3','1','0','120','2021-07-26 10:33:00',NULL,'0','0','0','0','0',NULL,NULL,'0','max'), ('7','P02','P02','Plain Rice','Plain Rice','3','1','0','95','2021-07-26 10:34:26',NULL,'0','0','0','0','0',NULL,NULL,'0','pancakehouse'), ('8','P03','P03','Honey Chicken','Honey Chicken','1','1','0','365','2021-07-26 10:35:35',NULL,'0','0','0','0','0',NULL,NULL,'0','pancakehouse'), ('9','J02','J02','JJ Carrot Juice','JJ Carrot Juice','4','2','0','95','2021-07-26 10:36:18',NULL,'0','0','0','0','0',NULL,NULL,'0','jambajuice'), ('10','M05','M05','Coke in Can','Coke in Can','2','2','0','80','2021-07-26 11:14:23',NULL,'0','0','0','0','0',NULL,NULL,'0','max');
INSERT INTO `menu_categories` VALUES ('1','Chickens','0','2021-07-26 10:22:57','0','0','0'), ('2','Drinks','0','2021-07-26 10:25:06','0','0','0'), ('3','Extras','0','2021-07-26 10:25:17','0','0','0'), ('4','Juice','0','2021-07-26 10:25:44','0','0','0');
INSERT INTO `menu_subcategories` VALUES ('1','FOOD',NULL,'0',NULL), ('2','BEVERAGES',NULL,'0',NULL);
INSERT INTO `miaa` VALUES ('1','T3VMMARE','09','C:/MIAA/');
INSERT INTO `promo_discounts` VALUES ('1','20HAPPYHOUR','20 % HAPPY HOUR','20','0','2017-03-21 14:55:57','2020-11-24 17:21:20','1');
INSERT INTO `promo_discount_items` VALUES ('2','20','1');
INSERT INTO `promo_discount_schedule` VALUES ('1','1','mon','15:00:00','18:00:00'), ('8','1','tue','15:00:00','18:00:00'), ('9','1','wed','15:00:00','18:00:00'), ('10','1','thu','15:00:00','18:00:00'), ('11','1','fri','15:00:00','18:00:00'), ('12','1','sat','15:00:00','18:00:00'), ('13','1','sun','15:00:00','18:00:00');
INSERT INTO `promo_free` VALUES ('1','Free Pork Siomai D','Free Pork Siomai D','34','1000','1','0');
INSERT INTO `promo_free_menus` VALUES ('2','1','12','1');
INSERT INTO `receipt_discounts` VALUES ('1','SNDISC','Senior Citizen Discount','20','1','0','0','12','2021-07-26 12:23:17'), ('2','PWDISC','Person WIth Disability','20','1','0','0','12','2021-07-26 12:23:18'), ('3','DIPLOMAT','DIPLOMAT','0','1','0','0','12','2021-07-26 12:23:20');
INSERT INTO `settings` VALUES ('1','1','0','3=>counter,6=>takeout,8=>food panda,18=>grabfood,20=>pickaroo','0','','','0','0','','100','10','','1','0','0');
INSERT INTO `shangrila` VALUES ('1','VIA_MARE','AYL','C:/Shangrila/');
INSERT INTO `shifts` VALUES ('1','1','2021-07-27 09:41:09',NULL,NULL,NULL,'1','10','2021-07-27 09:41:23');
INSERT INTO `shift_entries` VALUES ('1','1','5000','1','2021-07-27 09:41:09','11','2021-07-27 09:41:23',NULL);
INSERT INTO `stalucia` VALUES ('1','123');
INSERT INTO `sync_logs` VALUES ('1','trans_sales','add','1','2021-07-27 09:41:21','0',NULL,'0'), ('2','trans_sales_menus','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('3','trans_sales_no_tax','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('4','trans_sales_payments','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('5','trans_sales_tax','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('6','trans_sales_zero_rated','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('7','trans_refs','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('8','users','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('9','logs','add','1','2021-07-27 09:41:22',NULL,NULL,'0'), ('10','shifts','add','1','2021-07-27 09:41:23',NULL,NULL,'0'), ('11','shift_entries','add','1','2021-07-27 09:41:23',NULL,NULL,'0'), ('12','sync_logs','finish','1','2021-07-27 09:41:23',NULL,NULL,'0'), ('13','trans_sales','add','1','2021-07-27 09:41:58','0',NULL,'0'), ('14','trans_sales_menus','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('15','trans_sales_no_tax','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('16','trans_sales_payments','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('17','trans_sales_tax','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('18','trans_sales_zero_rated','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('19','trans_refs','add','1','2021-07-27 09:41:59',NULL,NULL,'0'), ('20','logs','add','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('21','trans_sales','update','1','2021-07-27 09:42:00','0',NULL,'0'), ('22','trans_sales_charges','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('23','trans_sales_menus','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('24','trans_sales_no_tax','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('25','trans_sales_payments','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('26','trans_sales_tax','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('27','trans_sales_zero_rated','update','1','2021-07-27 09:42:00',NULL,NULL,'0'), ('28','sync_logs','finish','1','2021-07-27 09:42:01',NULL,NULL,'0');
INSERT INTO `sync_types` VALUES ('1','local to main'), ('2','main to local');
INSERT INTO `tax_rates` VALUES ('1','VAT','12','0');
INSERT INTO `temp_sales` VALUES ('1',NULL,'10',NULL,NULL,'counter','1','1','1',NULL,'380','0',NULL,NULL,'0','2021-07-26 11:02:15','2021-07-26 11:03:02','0',NULL,NULL,'0','0',NULL,'0','0','0',NULL,'380','0','0','0','0','40.714285714286','0',NULL,'');
INSERT INTO `temp_sales_menus` VALUES ('4','1','0','2','380','1','0','0',NULL,'0',NULL,NULL,'2021-07-26 11:03:02','0','Chicken Pizza',NULL,'0');
INSERT INTO `temp_sales_no_tax` VALUES ('3','1','0',NULL,'2021-07-26 11:03:02');
INSERT INTO `temp_sales_tax` VALUES ('3','1','VAT','12','40.714285714286',NULL,'2021-07-26 11:03:02');
INSERT INTO `temp_sales_zero_rated` VALUES ('3','1','0',NULL,'2021-07-26 11:03:02',NULL,NULL);
INSERT INTO `terminals` VALUES ('1','T00001','ELRGB','Terminal 1','192.168.254.101','TERMINAL1','2014-09-11 12:45:45',NULL,'0','21','2018-10-04 12:18:46');
INSERT INTO `trans_refs` VALUES ('1','10','00000001','1',NULL,'7','2021-07-27 09:41:22'), ('2','10','00000002','1',NULL,'19','2021-07-27 09:41:59');
INSERT INTO `trans_sales` VALUES ('1',NULL,'10','00000001',NULL,'takeout','1','1','1',NULL,'1095','1095',NULL,NULL,'0','2021-07-27 09:41:12','2021-07-27 09:41:25','1',NULL,NULL,'1','0',NULL,'0','0','1','21','1095','0','0','0','0','117.32142857143','0',NULL,''), ('2',NULL,'10','00000002',NULL,'takeout','1','1','1',NULL,'1095','1095',NULL,NULL,'0','2021-07-27 09:41:51','2021-07-27 09:42:00','1',NULL,NULL,'1','0',NULL,'0','0','1','21','1095','0','0','0','0','117.32142857143','0',NULL,'');
INSERT INTO `trans_sales_menus` VALUES ('1','1','0','2','380','1','0','0',NULL,'1',NULL,'23','2021-07-27 09:42:00','0','Chicken Pizza',NULL,'0'), ('2','1','1','8','365','1','0','0',NULL,'1',NULL,'23','2021-07-27 09:42:00','0','Honey Chicken',NULL,'0'), ('3','1','2','1','350','1','0','0',NULL,'1',NULL,'23','2021-07-27 09:42:00','0','Max Chicken',NULL,'0'), ('4','2','0','2','380','1','0','0',NULL,'1',NULL,'23','2021-07-27 09:42:00','0','Chicken Pizza',NULL,'0'), ('5','2','1','8','365','1','0','0',NULL,'1',NULL,'23','2021-07-27 09:42:00','0','Honey Chicken',NULL,'0'), ('6','2','2','1','350','1','0','0',NULL,'1',NULL,'23','2021-07-27 09:42:00','0','Max Chicken',NULL,'0');
INSERT INTO `trans_sales_no_tax` VALUES ('1','1','0','24','2021-07-27 09:42:00'), ('2','2','0','24','2021-07-27 09:42:00');
INSERT INTO `trans_sales_payments` VALUES ('1','1','cash','1095','1095',NULL,NULL,NULL,NULL,'1','2021-07-27 09:41:20','25'), ('2','2','cash','1095','1095',NULL,NULL,NULL,NULL,'1','2021-07-27 09:41:58','25');
INSERT INTO `trans_sales_tax` VALUES ('1','1','VAT','12','117.32142857143','5','2021-07-27 09:41:22'), ('2','2','VAT','12','117.32142857143','26','2021-07-27 09:42:00');
INSERT INTO `trans_sales_zero_rated` VALUES ('1','1','0','27','2021-07-27 09:42:00',NULL,NULL), ('2','2','0','27','2021-07-27 09:42:00',NULL,NULL);
INSERT INTO `trans_types` VALUES ('10','sales','00000003',NULL), ('20','receivings','R000001',NULL), ('30','adjustment','A000001',NULL), ('11','sales void','V000001',NULL), ('40','customer deposit','C000001',NULL), ('50','loyalty card','00000002',NULL), ('35','spoilage','S000001',NULL), ('55','menu receiving','RM000001',NULL);
INSERT INTO `uom` VALUES ('1','ml','Mililiter','0',NULL,'0'), ('2','gm','Gram','0',NULL,'0'), ('3','pc','Piece','0',NULL,'0'), ('4','can','Can','0','0','0'), ('5','btl','Bottle','0','0','0'), ('6','kilo','Kilo','0','0','0'), ('7','pack','Pack','0','0','0'), ('8','Serving','serving','0','0','0'), ('9','tali','tali','0',NULL,'0'), ('10','jar','jar','0',NULL,'0'), ('11','sack','sack','0',NULL,'0'), ('12','tray','tray','0',NULL,'0'), ('13','case','case','0',NULL,'0'), ('14','roll','roll','0',NULL,'0'), ('15','gal','gal','0',NULL,'0'), ('16','box','box','0',NULL,'0');
INSERT INTO `users` VALUES ('1','admin','5f4dcc3b5aa765d61d8327deb882cf99','4c68cea7e58591b579fd074bcdaff740','Jessie','R.','Alison','','1','j.alison@pointonesolutions.com.ph','male','2014-06-16 14:41:31','0','8','2021-07-27 09:41:22'), ('65','TJH','81dc9bdb52d04dc20036dbd8313ed055','287f5779654e866161247055b8e0e14f','TJ','','Hernandez','','2','','male','2020-10-22 13:17:45','0','8','2021-07-27 09:41:22'), ('66','Jose','81dc9bdb52d04dc20036dbd8313ed055','c4b23d8601989c66d8811714a4da0536','Jose','','Abena','','2','','male','2020-10-22 13:19:19','0','8','2021-07-27 09:41:22'), ('67','Bads','7e3b7a5bafcb0fa8e8dfe3ea6aca9186','1b648b198d84b9e6a42db93141c87596','Real John','','Bongolo','','3','','male','2020-10-22 13:19:54','0','8','2021-07-27 09:41:22'), ('68','Acel','38b8e8fe30cd2f6f7e79f6be6905fabb','346829a0bcdcc55b3efaf5fa7a57e6a1','Maricel','','Salvador','','3','','female','2020-10-22 13:20:24','0','8','2021-07-27 09:41:22'), ('69','Jason','c20ad4d76fe97759aa27a0c99bff6710','91b3261d1d4ae00b5bb6c2d1cbda52cc','Jason','','Pasuquin','','3','','male','2020-10-22 15:27:41','0','8','2021-07-27 09:41:22'), ('70','jgg','4da1a768c0823181364fbfe594be2629','4da1a768c0823181364fbfe594be2629','Jayson','Garfin','Grefalda','','1','jayson.grefalda@momentgroup.ph','male','2020-12-18 20:04:25','0','8','2021-07-27 09:41:22');
INSERT INTO `user_roles` VALUES ('1','Administrator ','System Administrator','all'), ('2','Manager','Manager','all'), ('3','Employee','Employee','general_settings,grecdiscs'), ('4','OIC','Officer In Charge','items,trans,receiving,adjustment,spoilage,inq,item_inv,inv_move,items,list,gcategories,gsubcategories,glocations,gsuppliers,guom'), ('5','jaypee','','items,trans,receiving,adjustment,spoilage,inq,item_inv,inv_move,items,list,gcategories,gsubcategories,glocations,gsuppliers,guom,menus,menulist,menucat,menusubcat,menusched,mods,modslist,modgrps,pos_promos,promos,gift_cards,coupons,charges,grecdiscs,gtaxrates,tblmng,denomination');
INSERT INTO `vistamall` VALUES ('1','12345678','00','C:/VISTAMALL');
