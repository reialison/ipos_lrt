/*
MySQL Backup
Database: ipos_burrow
Backup Time: 2019-04-02 16:06:21
*/

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `ipos_burrow`.`araneta`;
DROP TABLE IF EXISTS `ipos_burrow`.`ayala`;
DROP TABLE IF EXISTS `ipos_burrow`.`branch_details`;
DROP TABLE IF EXISTS `ipos_burrow`.`branch_menus`;
DROP TABLE IF EXISTS `ipos_burrow`.`cashout_details`;
DROP TABLE IF EXISTS `ipos_burrow`.`cashout_entries`;
DROP TABLE IF EXISTS `ipos_burrow`.`categories`;
DROP TABLE IF EXISTS `ipos_burrow`.`charges`;
DROP TABLE IF EXISTS `ipos_burrow`.`ci_sessions`;
DROP TABLE IF EXISTS `ipos_burrow`.`company`;
DROP TABLE IF EXISTS `ipos_burrow`.`conversation_messages`;
DROP TABLE IF EXISTS `ipos_burrow`.`conversations`;
DROP TABLE IF EXISTS `ipos_burrow`.`coupons`;
DROP TABLE IF EXISTS `ipos_burrow`.`currencies`;
DROP TABLE IF EXISTS `ipos_burrow`.`currency_details`;
DROP TABLE IF EXISTS `ipos_burrow`.`customer_address`;
DROP TABLE IF EXISTS `ipos_burrow`.`customers`;
DROP TABLE IF EXISTS `ipos_burrow`.`customers_bank`;
DROP TABLE IF EXISTS `ipos_burrow`.`denominations`;
DROP TABLE IF EXISTS `ipos_burrow`.`dtr_scheduler`;
DROP TABLE IF EXISTS `ipos_burrow`.`dtr_shifts`;
DROP TABLE IF EXISTS `ipos_burrow`.`eton`;
DROP TABLE IF EXISTS `ipos_burrow`.`gift_cards`;
DROP TABLE IF EXISTS `ipos_burrow`.`images`;
DROP TABLE IF EXISTS `ipos_burrow`.`item_moves0627`;
DROP TABLE IF EXISTS `ipos_burrow`.`item_moves`;
DROP TABLE IF EXISTS `ipos_burrow`.`item_types`;
DROP TABLE IF EXISTS `ipos_burrow`.`items`;
DROP TABLE IF EXISTS `ipos_burrow`.`locations`;
DROP TABLE IF EXISTS `ipos_burrow`.`logs`;
DROP TABLE IF EXISTS `ipos_burrow`.`loyalty_cards`;
DROP TABLE IF EXISTS `ipos_burrow`.`master_logs`;
DROP TABLE IF EXISTS `ipos_burrow`.`megamall`;
DROP TABLE IF EXISTS `ipos_burrow`.`megaworld`;
DROP TABLE IF EXISTS `ipos_burrow`.`menu_categories`;
DROP TABLE IF EXISTS `ipos_burrow`.`menu_modifiers`;
DROP TABLE IF EXISTS `ipos_burrow`.`menu_moves`;
DROP TABLE IF EXISTS `ipos_burrow`.`menu_recipe`;
DROP TABLE IF EXISTS `ipos_burrow`.`menu_schedule_details`;
DROP TABLE IF EXISTS `ipos_burrow`.`menu_schedules`;
DROP TABLE IF EXISTS `ipos_burrow`.`menu_subcategories`;
DROP TABLE IF EXISTS `ipos_burrow`.`menu_subcategory`;
DROP TABLE IF EXISTS `ipos_burrow`.`menus`;
DROP TABLE IF EXISTS `ipos_burrow`.`miaa`;
DROP TABLE IF EXISTS `ipos_burrow`.`modifier_group_details`;
DROP TABLE IF EXISTS `ipos_burrow`.`modifier_groups`;
DROP TABLE IF EXISTS `ipos_burrow`.`modifier_recipe`;
DROP TABLE IF EXISTS `ipos_burrow`.`modifiers`;
DROP TABLE IF EXISTS `ipos_burrow`.`ortigas`;
DROP TABLE IF EXISTS `ipos_burrow`.`ortigas_read_details`;
DROP TABLE IF EXISTS `ipos_burrow`.`promo_discount_items`;
DROP TABLE IF EXISTS `ipos_burrow`.`promo_discount_schedule`;
DROP TABLE IF EXISTS `ipos_burrow`.`promo_discounts`;
DROP TABLE IF EXISTS `ipos_burrow`.`promo_free`;
DROP TABLE IF EXISTS `ipos_burrow`.`promo_free_menus`;
DROP TABLE IF EXISTS `ipos_burrow`.`read_details`;
DROP TABLE IF EXISTS `ipos_burrow`.`reasons`;
DROP TABLE IF EXISTS `ipos_burrow`.`receipt_discounts`;
DROP TABLE IF EXISTS `ipos_burrow`.`restaurant_branch_tables`;
DROP TABLE IF EXISTS `ipos_burrow`.`rob_files`;
DROP TABLE IF EXISTS `ipos_burrow`.`settings`;
DROP TABLE IF EXISTS `ipos_burrow`.`shift_entries`;
DROP TABLE IF EXISTS `ipos_burrow`.`shifts`;
DROP TABLE IF EXISTS `ipos_burrow`.`stalucia`;
DROP TABLE IF EXISTS `ipos_burrow`.`subcategories`;
DROP TABLE IF EXISTS `ipos_burrow`.`suppliers`;
DROP TABLE IF EXISTS `ipos_burrow`.`sync_logs`;
DROP TABLE IF EXISTS `ipos_burrow`.`sync_types`;
DROP TABLE IF EXISTS `ipos_burrow`.`table_activity`;
DROP TABLE IF EXISTS `ipos_burrow`.`tables`;
DROP TABLE IF EXISTS `ipos_burrow`.`tablesold`;
DROP TABLE IF EXISTS `ipos_burrow`.`tax_rates`;
DROP TABLE IF EXISTS `ipos_burrow`.`terminals`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_adjustment_details`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_adjustments`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_receiving_details`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_receiving_menu`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_receiving_menu_details`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_receivings`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_refs`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales_charges`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales_discounts`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales_items`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales_local_tax`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales_loyalty_points`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales_menu_modifiers`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales_menus`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales_no_tax`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales_payments`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales_tax`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_sales_zero_rated`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_spoilage`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_spoilage_details`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_types`;
DROP TABLE IF EXISTS `ipos_burrow`.`trans_voids`;
DROP TABLE IF EXISTS `ipos_burrow`.`transfer_split`;
DROP TABLE IF EXISTS `ipos_burrow`.`uom`;
DROP TABLE IF EXISTS `ipos_burrow`.`updates`;
DROP TABLE IF EXISTS `ipos_burrow`.`user_roles`;
DROP TABLE IF EXISTS `ipos_burrow`.`users`;
DROP TABLE IF EXISTS `ipos_burrow`.`vistamall`;
CREATE TABLE `araneta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lessee_name` varchar(20) DEFAULT NULL,
  `lessee_no` varchar(20) DEFAULT NULL,
  `space_code` varchar(20) DEFAULT '',
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
CREATE TABLE `branch_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `menu_code` varchar(15) DEFAULT NULL,
  `menu_name` varchar(25) DEFAULT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
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
CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` longtext NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
CREATE TABLE `conversation_messages` (
  `con_msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `con_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `msg` longtext,
  `file` longblob,
  `datetime` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`con_msg_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `conversations` (
  `con_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_a` int(11) DEFAULT NULL,
  `user_b` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`con_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `coupons` (
  `coupon_id` int(10) NOT NULL AUTO_INCREMENT,
  `card_no` varchar(100) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `expiration` date DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
CREATE TABLE `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(22) DEFAULT NULL,
  `currency_desc` varchar(55) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
CREATE TABLE `currency_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_id` varchar(45) NOT NULL,
  `desc` varchar(60) NOT NULL,
  `value` double NOT NULL,
  `img` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
  PRIMARY KEY (`cust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
CREATE TABLE `denominations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc` varchar(60) NOT NULL,
  `value` double NOT NULL,
  `img` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `dtr_scheduler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `dtr_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
CREATE TABLE `eton` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_code` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
CREATE TABLE `gift_cards` (
  `gc_id` int(10) NOT NULL AUTO_INCREMENT,
  `card_no` varchar(100) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`gc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
CREATE TABLE `item_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `locations` (
  `loc_id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_code` varchar(22) DEFAULT NULL,
  `loc_name` varchar(55) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`loc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` longtext,
  `reference` longtext,
  `datetime` datetime DEFAULT NULL,
  `type` varchar(11) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
  PRIMARY KEY (`master_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `megamall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `br_code` varchar(20) DEFAULT NULL,
  `tenant_no` varchar(20) DEFAULT NULL,
  `class_code` varchar(20) DEFAULT '',
  `trade_code` varchar(20) DEFAULT NULL,
  `outlet_no` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `megaworld` (
  `id` int(11) NOT NULL DEFAULT '0',
  `tenant_code` varchar(50) DEFAULT NULL,
  `sales_type` varchar(20) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `menu_categories` (
  `menu_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_cat_name` varchar(150) NOT NULL,
  `menu_sched_id` int(11) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `master_id` int(11) NOT NULL,
  `arrangement` int(11) DEFAULT '0',
  PRIMARY KEY (`menu_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `menu_modifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `mod_group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `menu_recipe` (
  `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `master_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `uom` varchar(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` double NOT NULL,
  PRIMARY KEY (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `menu_schedule_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_sched_id` int(11) NOT NULL,
  `day` varchar(22) NOT NULL,
  `time_on` time NOT NULL,
  `time_off` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `menu_schedules` (
  `menu_sched_id` int(11) NOT NULL AUTO_INCREMENT,
  `desc` varchar(150) NOT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `master_id` int(11) NOT NULL,
  PRIMARY KEY (`menu_sched_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `menu_subcategories` (
  `menu_sub_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_sub_cat_name` varchar(150) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `master_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`menu_sub_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `menu_subcategory` (
  `menu_sub_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_sub_name` varchar(150) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`menu_sub_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `miaa` (
  `id` int(11) NOT NULL DEFAULT '0',
  `tenant_code` varchar(50) DEFAULT NULL,
  `sales_type` varchar(20) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `modifier_group_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_group_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `default` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `modifier_groups` (
  `mod_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `mandatory` int(1) DEFAULT '0',
  `multiple` int(10) DEFAULT '0',
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`mod_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
CREATE TABLE `modifiers` (
  `mod_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `cost` double(11,0) DEFAULT '0',
  `has_recipe` int(1) DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `ortigas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_code` varchar(10) DEFAULT NULL,
  `sales_type` varchar(5) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
CREATE TABLE `promo_discount_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `promo_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `promo_discount_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promo_id` int(11) NOT NULL,
  `day` varchar(22) NOT NULL,
  `time_on` time NOT NULL,
  `time_off` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
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
CREATE TABLE `promo_free` (
  `pf_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `has_menu_id` varchar(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sched_id` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`pf_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `promo_free_menus` (
  `pf_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `pf_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  PRIMARY KEY (`pf_menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
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
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_of_receipt_print` int(11) DEFAULT NULL,
  `no_of_order_slip_print` int(11) DEFAULT NULL,
  `controls` varchar(150) DEFAULT NULL,
  `local_tax` double(5,0) DEFAULT '0',
  `kitchen_printer_name` varchar(150) DEFAULT NULL,
  `kitchen_beverage_printer_name` varchar(150) DEFAULT NULL,
  `kitchen_printer_name_no` int(11) DEFAULT '0',
  `kitchen_beverage_printer_name_no` int(11) DEFAULT '0',
  `open_drawer_printer` varchar(150) DEFAULT NULL,
  `loyalty_for_amount` double DEFAULT '0',
  `loyalty_to_points` double DEFAULT '0',
  `backup_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `shift_entries` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `user_id` varchar(45) NOT NULL,
  `trans_date` datetime NOT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `stalucia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `subcategories` (
  `sub_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  PRIMARY KEY (`sub_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
CREATE TABLE `sync_logs` (
  `sync_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction` varchar(250) DEFAULT NULL,
  `type` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `migrate_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `src_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_automated` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`sync_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `sync_types` (
  `sync_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `sync_type` varchar(100) NOT NULL,
  PRIMARY KEY (`sync_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `table_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tbl_id` int(11) DEFAULT NULL,
  `pc_id` int(11) DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
  PRIMARY KEY (`tbl_id`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=latin1;
CREATE TABLE `tablesold` (
  `tbl_id` int(11) NOT NULL AUTO_INCREMENT,
  `capacity` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `top` int(11) DEFAULT '0',
  `left` int(11) DEFAULT '0',
  PRIMARY KEY (`tbl_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `tax_rates` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`terminal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
  PRIMARY KEY (`receiving_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `trans_refs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(55) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`sales_charge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`sales_disc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`sales_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `trans_sales_local_tax` (
  `sales_local_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_local_tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
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
  PRIMARY KEY (`sales_mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`sales_menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `trans_sales_no_tax` (
  `sales_no_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_no_tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `trans_sales_tax` (
  `sales_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `trans_sales_zero_rated` (
  `sales_zero_rated_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_zero_rated_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
CREATE TABLE `trans_types` (
  `type_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `next_ref` varchar(45) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
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
CREATE TABLE `uom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(22) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `num` double DEFAULT '0',
  `to` varchar(22) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
CREATE TABLE `updates` (
  `ctr` int(11) NOT NULL AUTO_INCREMENT,
  `query` longtext,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ctr`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
CREATE TABLE `user_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `access` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`id`,`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
CREATE TABLE `vistamall` (
  `id` int(11) NOT NULL DEFAULT '0',
  `stall_code` varchar(50) DEFAULT NULL,
  `sales_dep` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
BEGIN;
LOCK TABLES `ipos_burrow`.`araneta` WRITE;
DELETE FROM `ipos_burrow`.`araneta`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`ayala` WRITE;
DELETE FROM `ipos_burrow`.`ayala`;
INSERT INTO `ipos_burrow`.`ayala` (`id`,`contract_no`,`store_name`,`xxx_no`,`dbf_tenant_name`,`dbf_path`,`text_file_path`) VALUES (1, '6000000004041', 'Hapchan Expres', 'AYA', '2XU - G3', 'C:/AYALA/', 'C:/AYALA/');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`branch_details` WRITE;
DELETE FROM `ipos_burrow`.`branch_details`;
INSERT INTO `ipos_burrow`.`branch_details` (`branch_id`,`res_id`,`branch_code`,`branch_name`,`branch_desc`,`contact_no`,`delivery_no`,`address`,`base_location`,`currency`,`image`,`inactive`,`tin`,`machine_no`,`bir`,`permit_no`,`serial`,`email`,`website`,`store_open`,`store_close`,`rob_tenant_code`,`rob_path`,`rob_username`,`rob_password`,`accrdn`,`rec_footer`,`pos_footer`) VALUES (1, 1, 'BNB', 'BURROW', 'BURROW', '', '', 'ANTIPOLO RIZAL', NULL, 'PHP', 'layout.png', 0, '', '', '0', '', '', '', '', '05:00:00', '23:45:00', '1234', '190.125.220.1', 'mag15836hap', 'maghapex', '43A0085434442014110212', 'NOT AN OFFICIAL RECEIPT.', '');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`branch_menus` WRITE;
DELETE FROM `ipos_burrow`.`branch_menus`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`cashout_details` WRITE;
DELETE FROM `ipos_burrow`.`cashout_details`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`cashout_entries` WRITE;
DELETE FROM `ipos_burrow`.`cashout_entries`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`categories` WRITE;
DELETE FROM `ipos_burrow`.`categories`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`charges` WRITE;
DELETE FROM `ipos_burrow`.`charges`;
INSERT INTO `ipos_burrow`.`charges` (`charge_id`,`charge_code`,`charge_name`,`charge_amount`,`absolute`,`no_tax`,`inactive`) VALUES (1, 'SCHG', 'Service Charge', 10, 0, 1, 0),(2, 'DCHG', 'Delivery Charge', 8, 0, 1, 0),(3, 'HANCHG', 'Handling Charge', 8, 0, 0, 0);
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`ci_sessions` WRITE;
DELETE FROM `ipos_burrow`.`ci_sessions`;
INSERT INTO `ipos_burrow`.`ci_sessions` (`session_id`,`ip_address`,`user_agent`,`last_activity`,`user_data`) VALUES ('792f4916c5a6c7b6b8d4485a5db85ae7', '::1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36', 1554186662, 'a:2:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"fname\";s:5:\"admin\";s:5:\"lname\";s:5:\"admin\";s:5:\"mname\";s:5:\"admin\";s:6:\"suffix\";s:3:\"Jr.\";s:9:\"full_name\";s:21:\"admin admin admin Jr.\";s:7:\"role_id\";s:1:\"1\";s:4:\"role\";s:14:\"Administrator \";s:6:\"access\";s:3:\"all\";s:3:\"img\";s:36:\"http://localhost/ipos/img/avatar.jpg\";}}'),('ef675b907a3ad1e2f19bac9f3f0cac50', '::1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36', 1554186662, 'a:2:{s:9:\"user_data\";s:0:\"\";s:11:\"site_alerts\";a:1:{i:0;a:2:{s:4:\"text\";s:41:\"You need to start a shift before selling.\";s:4:\"type\";s:5:\"error\";}}}');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`company` WRITE;
DELETE FROM `ipos_burrow`.`company`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`conversation_messages` WRITE;
DELETE FROM `ipos_burrow`.`conversation_messages`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`conversations` WRITE;
DELETE FROM `ipos_burrow`.`conversations`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`coupons` WRITE;
DELETE FROM `ipos_burrow`.`coupons`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`currencies` WRITE;
DELETE FROM `ipos_burrow`.`currencies`;
INSERT INTO `ipos_burrow`.`currencies` (`id`,`currency`,`currency_desc`,`inactive`) VALUES (1, 'PHP', 'Philippine Peso', 0),(2, 'USD', 'US Dollars', 0),(3, 'YEN', 'Japanese Yen', 0);
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`currency_details` WRITE;
DELETE FROM `ipos_burrow`.`currency_details`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`customer_address` WRITE;
DELETE FROM `ipos_burrow`.`customer_address`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`customers` WRITE;
DELETE FROM `ipos_burrow`.`customers`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`customers_bank` WRITE;
DELETE FROM `ipos_burrow`.`customers_bank`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`denominations` WRITE;
DELETE FROM `ipos_burrow`.`denominations`;
INSERT INTO `ipos_burrow`.`denominations` (`id`,`desc`,`value`,`img`) VALUES (1, 'One Thousand', 1000, NULL),(2, 'Five Hundreds', 500, NULL),(3, 'Two Hundreds', 200, NULL),(4, 'One Hundreds', 100, NULL),(5, 'Fifty', 50, NULL),(6, 'Twenty', 20, NULL),(7, 'Ten', 10, NULL),(8, 'Five', 5, NULL),(9, 'One', 1, NULL),(10, 'Twenty Five Cents', 0.25, NULL),(11, 'Ten Cents', 0.1, NULL);
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`dtr_scheduler` WRITE;
DELETE FROM `ipos_burrow`.`dtr_scheduler`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`dtr_shifts` WRITE;
DELETE FROM `ipos_burrow`.`dtr_shifts`;
INSERT INTO `ipos_burrow`.`dtr_shifts` (`id`,`code`,`description`,`time_in`,`break_out`,`break_in`,`time_out`,`break_hours`,`work_hours`,`inactive`,`grace_period`,`timein_grace_period`) VALUES (1, 'RESTDAY', 'Rest Day', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, '00:00:00', NULL),(2, 'Shift1', 'restday again', '07:00:00', '11:00:00', '12:00:00', '16:00:00', 1, 9, 0, '00:00:00', '00:30:00'),(3, '6PM7AM', '6PM to 7AM', '18:00:00', '00:00:00', '00:00:00', '07:00:00', 0, 13, 0, '00:00:00', '00:00:00'),(4, '7AM7PM', '7AM to 7PM', '07:00:00', '00:00:00', '00:00:00', '19:00:00', 0, 12, 0, '00:15:00', '01:00:00'),(5, '7PM7AM', '7PM to 7AM', '19:00:00', '00:00:00', '00:00:00', '07:00:00', 0, -12, 0, '00:15:00', '01:00:00'),(6, '7AM4PM', '7AM to 4PM', '07:00:00', '00:00:00', '00:00:00', '16:00:00', 0, 9, 0, '00:15:00', '01:00:00'),(7, '9AM10PM', '9AM10PM', '09:00:00', '00:00:00', '00:00:00', '22:00:00', 1, 13, 0, '00:00:00', '00:00:00');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`eton` WRITE;
DELETE FROM `ipos_burrow`.`eton`;
INSERT INTO `ipos_burrow`.`eton` (`id`,`tenant_code`,`file_path`) VALUES (1, 'ABCD1234', 'C:/ETON');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`gift_cards` WRITE;
DELETE FROM `ipos_burrow`.`gift_cards`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`images` WRITE;
DELETE FROM `ipos_burrow`.`images`;
INSERT INTO `ipos_burrow`.`images` (`img_id`,`img_file_name`,`img_path`,`img_ref_id`,`img_tbl`,`img_blob`,`datetime`,`disabled`) VALUES (1, '6.png', 'uploads/users/6.png', 6, 'users', NULL, '2018-12-21 09:39:17', 0),(2, '3.png', 'uploads/users/3.png', 3, 'users', NULL, '2018-12-21 09:44:40', 0),(3, '4.png', 'uploads/users/4.png', 4, 'users', NULL, '2018-12-21 09:46:36', 0);
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`item_moves0627` WRITE;
DELETE FROM `ipos_burrow`.`item_moves0627`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`item_moves` WRITE;
DELETE FROM `ipos_burrow`.`item_moves`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`item_types` WRITE;
DELETE FROM `ipos_burrow`.`item_types`;
INSERT INTO `ipos_burrow`.`item_types` (`id`,`type`) VALUES (1, 'Not For Resale'),(2, 'For Resale');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`items` WRITE;
DELETE FROM `ipos_burrow`.`items`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`locations` WRITE;
DELETE FROM `ipos_burrow`.`locations`;
INSERT INTO `ipos_burrow`.`locations` (`loc_id`,`loc_code`,`loc_name`,`inactive`,`sync_id`,`datetime`) VALUES (1, 'WHS001', 'WAREHOUSE', 0, 65, '2019-03-20 16:28:57'),(2, '002', 'KITCHEN', 0, 65, '2019-03-20 16:28:57'),(3, '003', 'DINING AND BAR', 0, 65, '2019-03-20 16:28:57');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`logs` WRITE;
DELETE FROM `ipos_burrow`.`logs`;
INSERT INTO `ipos_burrow`.`logs` (`id`,`user_id`,`action`,`reference`,`datetime`,`type`,`sync_id`) VALUES (1, 1, 'admin admin admin Jr. Logged In.', NULL, '2019-04-02 14:31:06', 'login', NULL);
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`loyalty_cards` WRITE;
DELETE FROM `ipos_burrow`.`loyalty_cards`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`master_logs` WRITE;
DELETE FROM `ipos_burrow`.`master_logs`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`megamall` WRITE;
DELETE FROM `ipos_burrow`.`megamall`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`megaworld` WRITE;
DELETE FROM `ipos_burrow`.`megaworld`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`menu_categories` WRITE;
DELETE FROM `ipos_burrow`.`menu_categories`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`menu_modifiers` WRITE;
DELETE FROM `ipos_burrow`.`menu_modifiers`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`menu_moves` WRITE;
DELETE FROM `ipos_burrow`.`menu_moves`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`menu_recipe` WRITE;
DELETE FROM `ipos_burrow`.`menu_recipe`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`menu_schedule_details` WRITE;
DELETE FROM `ipos_burrow`.`menu_schedule_details`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`menu_schedules` WRITE;
DELETE FROM `ipos_burrow`.`menu_schedules`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`menu_subcategories` WRITE;
DELETE FROM `ipos_burrow`.`menu_subcategories`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`menu_subcategory` WRITE;
DELETE FROM `ipos_burrow`.`menu_subcategory`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`menus` WRITE;
DELETE FROM `ipos_burrow`.`menus`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`miaa` WRITE;
DELETE FROM `ipos_burrow`.`miaa`;
INSERT INTO `ipos_burrow`.`miaa` (`id`,`tenant_code`,`sales_type`,`file_path`) VALUES (1, 'T3VMMARE', '09', 'C:/MIAA/');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`modifier_group_details` WRITE;
DELETE FROM `ipos_burrow`.`modifier_group_details`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`modifier_groups` WRITE;
DELETE FROM `ipos_burrow`.`modifier_groups`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`modifier_recipe` WRITE;
DELETE FROM `ipos_burrow`.`modifier_recipe`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`modifiers` WRITE;
DELETE FROM `ipos_burrow`.`modifiers`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`ortigas` WRITE;
DELETE FROM `ipos_burrow`.`ortigas`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`ortigas_read_details` WRITE;
DELETE FROM `ipos_burrow`.`ortigas_read_details`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`promo_discount_items` WRITE;
DELETE FROM `ipos_burrow`.`promo_discount_items`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`promo_discount_schedule` WRITE;
DELETE FROM `ipos_burrow`.`promo_discount_schedule`;
INSERT INTO `ipos_burrow`.`promo_discount_schedule` (`id`,`promo_id`,`day`,`time_on`,`time_off`) VALUES (1, 1, 'mon', '11:00:00', '17:00:00'),(6, 1, 'tue', '11:00:00', '17:00:00'),(7, 1, 'wed', '11:00:00', '17:00:00'),(8, 1, 'thu', '11:00:00', '17:00:00'),(10, 1, 'fri', '11:00:00', '17:00:00');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`promo_discounts` WRITE;
DELETE FROM `ipos_burrow`.`promo_discounts`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`promo_free` WRITE;
DELETE FROM `ipos_burrow`.`promo_free`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`promo_free_menus` WRITE;
DELETE FROM `ipos_burrow`.`promo_free_menus`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`read_details` WRITE;
DELETE FROM `ipos_burrow`.`read_details`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`reasons` WRITE;
DELETE FROM `ipos_burrow`.`reasons`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`receipt_discounts` WRITE;
DELETE FROM `ipos_burrow`.`receipt_discounts`;
INSERT INTO `ipos_burrow`.`receipt_discounts` (`disc_id`,`disc_code`,`disc_name`,`disc_rate`,`no_tax`,`fix`,`inactive`,`sync_id`,`datetime`) VALUES (1, 'SNDISC', 'Senior Citizen\'s Discount (SC)', 20, 1, 0, 0, NULL, '2019-03-20 16:29:03'),(2, 'PWDISC', 'Persons with Disability (PWD) Discount', 20, 1, 0, 0, NULL, '2019-03-20 16:29:03');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`restaurant_branch_tables` WRITE;
DELETE FROM `ipos_burrow`.`restaurant_branch_tables`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`rob_files` WRITE;
DELETE FROM `ipos_burrow`.`rob_files`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`settings` WRITE;
DELETE FROM `ipos_burrow`.`settings`;
INSERT INTO `ipos_burrow`.`settings` (`id`,`no_of_receipt_print`,`no_of_order_slip_print`,`controls`,`local_tax`,`kitchen_printer_name`,`kitchen_beverage_printer_name`,`kitchen_printer_name_no`,`kitchen_beverage_printer_name_no`,`open_drawer_printer`,`loyalty_for_amount`,`loyalty_to_points`,`backup_path`) VALUES (1, 1, 0, '1=>dine in,4=>retail,6=>takeout', 0, 'KITCHEN', 'KITCHEN', 1, 1, 'CASH DRAWER', 100, 10, NULL);
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`shift_entries` WRITE;
DELETE FROM `ipos_burrow`.`shift_entries`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`shifts` WRITE;
DELETE FROM `ipos_burrow`.`shifts`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`stalucia` WRITE;
DELETE FROM `ipos_burrow`.`stalucia`;
INSERT INTO `ipos_burrow`.`stalucia` (`id`,`tenant_code`) VALUES (1, '123');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`subcategories` WRITE;
DELETE FROM `ipos_burrow`.`subcategories`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`suppliers` WRITE;
DELETE FROM `ipos_burrow`.`suppliers`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`sync_logs` WRITE;
DELETE FROM `ipos_burrow`.`sync_logs`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`sync_types` WRITE;
DELETE FROM `ipos_burrow`.`sync_types`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`table_activity` WRITE;
DELETE FROM `ipos_burrow`.`table_activity`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`tables` WRITE;
DELETE FROM `ipos_burrow`.`tables`;
INSERT INTO `ipos_burrow`.`tables` (`tbl_id`,`capacity`,`status`,`name`,`top`,`left`,`inactive`,`sync_id`,`datetime`) VALUES (118, 2, NULL, 'TBL1', 47, 82, 1, 49, '2019-04-01 09:43:03'),(119, 2, NULL, 'TBL2', 48, 158, 1, 49, '2019-04-01 09:43:03'),(120, 3, NULL, 'TBL3', 48, 288, 1, 49, '2019-04-01 09:43:03'),(121, 2, NULL, 'TBL4', 49, 364, 1, 49, '2019-04-01 09:43:03'),(122, 2, NULL, 'TBL5', 47, 496, 1, 49, '2019-04-01 09:43:03'),(123, 2, NULL, 'TBL6', 47, 570, 1, 49, '2019-04-01 09:43:03'),(124, 4, NULL, 'TBL7', 454, 96, 1, 49, '2019-04-01 09:43:03'),(125, 4, NULL, 'TBL8', 454, 262, 1, 49, '2019-04-01 09:43:03'),(126, 4, NULL, 'TBL9', 454, 432, 1, 49, '2019-04-01 09:43:03'),(127, 4, NULL, 'TBL10', 252, 295, 1, 49, '2019-04-01 09:43:03'),(128, 2, NULL, 'TBL11', 197, 499, 1, 49, '2019-04-01 09:43:03'),(129, 4, NULL, 'TBL12', 307, 500, 1, 49, '2019-04-01 09:43:03'),(130, 5, NULL, 'TBL13', 184, 669, 1, 49, '2019-04-01 09:43:03'),(131, 5, NULL, 'TBL14', 313, 667, 1, 49, '2019-04-01 09:43:03'),(132, 6, NULL, 'A', 55, 142, 0, 49, '2019-04-01 09:43:03'),(133, 8, NULL, 'B', 56, 262, 0, 49, '2019-04-01 09:43:03'),(134, 6, NULL, 'C', 56, 378, 0, 49, '2019-04-01 09:43:03'),(135, 6, NULL, 'D', 55, 496, 0, 49, '2019-04-01 09:43:03'),(136, 14, NULL, 'E1', 56, 627, 0, 49, '2019-04-01 09:43:03'),(137, 14, NULL, 'E2', 57, 746, 0, 49, '2019-04-01 09:43:03'),(138, 14, NULL, 'F1', 166, 196, 0, 49, '2019-04-01 09:43:03'),(139, 14, NULL, 'F2', 168, 317, 0, 49, '2019-04-01 09:43:03'),(140, 8, NULL, 'G', 170, 432, 0, 49, '2019-04-01 09:43:03'),(141, 6, NULL, 'H', 171, 552, 0, 49, '2019-04-01 09:43:03'),(142, 4, NULL, 'I', 168, 681, 0, 49, '2019-04-01 09:43:03'),(143, 6, NULL, 'J', 380, 247, 0, 49, '2019-04-01 09:43:03'),(144, 6, NULL, 'K', 384, 361, 0, 49, '2019-04-01 09:43:03'),(145, 6, NULL, 'L', 380, 484, 0, 49, '2019-04-01 09:43:03'),(146, 6, NULL, 'M', 380, 615, 0, 49, '2019-04-01 09:43:03');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`tablesold` WRITE;
DELETE FROM `ipos_burrow`.`tablesold`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`tax_rates` WRITE;
DELETE FROM `ipos_burrow`.`tax_rates`;
INSERT INTO `ipos_burrow`.`tax_rates` (`tax_id`,`name`,`rate`,`inactive`) VALUES (1, 'VAT', 12, 0);
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`terminals` WRITE;
DELETE FROM `ipos_burrow`.`terminals`;
INSERT INTO `ipos_burrow`.`terminals` (`terminal_id`,`terminal_code`,`branch_code`,`terminal_name`,`ip`,`comp_name`,`reg_date`,`update_date`,`inactive`,`sync_id`,`datetime`) VALUES (1, 'T00001', 'POINTONE0001', 'Terminal 1', '', 'TERMINAL1', '2014-09-11 12:45:45', NULL, 0, 67, '2019-04-01 09:43:04');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_adjustment_details` WRITE;
DELETE FROM `ipos_burrow`.`trans_adjustment_details`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_adjustments` WRITE;
DELETE FROM `ipos_burrow`.`trans_adjustments`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_receiving_details` WRITE;
DELETE FROM `ipos_burrow`.`trans_receiving_details`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_receiving_menu` WRITE;
DELETE FROM `ipos_burrow`.`trans_receiving_menu`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_receiving_menu_details` WRITE;
DELETE FROM `ipos_burrow`.`trans_receiving_menu_details`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_receivings` WRITE;
DELETE FROM `ipos_burrow`.`trans_receivings`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_refs` WRITE;
DELETE FROM `ipos_burrow`.`trans_refs`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales_charges` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales_charges`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales_discounts` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales_discounts`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales_items` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales_items`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales_local_tax` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales_local_tax`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales_loyalty_points` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales_loyalty_points`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales_menu_modifiers` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales_menu_modifiers`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales_menus` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales_menus`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales_no_tax` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales_no_tax`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales_payments` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales_payments`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales_tax` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales_tax`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_sales_zero_rated` WRITE;
DELETE FROM `ipos_burrow`.`trans_sales_zero_rated`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_spoilage` WRITE;
DELETE FROM `ipos_burrow`.`trans_spoilage`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_spoilage_details` WRITE;
DELETE FROM `ipos_burrow`.`trans_spoilage_details`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_types` WRITE;
DELETE FROM `ipos_burrow`.`trans_types`;
INSERT INTO `ipos_burrow`.`trans_types` (`type_id`,`name`,`next_ref`,`sync_id`) VALUES (10, 'sales', '00000001', NULL),(20, 'receivings', 'R000001', NULL),(30, 'adjustment', 'A000001', NULL),(11, 'sales void', 'V000001', NULL),(40, 'customer deposit', 'C000001', NULL),(50, 'loyalty card', '00000001', NULL),(35, 'spoilage', 'S000001', NULL);
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`trans_voids` WRITE;
DELETE FROM `ipos_burrow`.`trans_voids`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`transfer_split` WRITE;
DELETE FROM `ipos_burrow`.`transfer_split`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`uom` WRITE;
DELETE FROM `ipos_burrow`.`uom`;
INSERT INTO `ipos_burrow`.`uom` (`id`,`code`,`name`,`num`,`to`,`inactive`) VALUES (1, 'ml', 'mililiters', 0, NULL, 0),(2, 'g', 'grams', 0, NULL, 0),(3, 'oz', 'ounce', 0, NULL, 0),(4, 'lbs', 'pounds', 0, NULL, 0),(5, 'pcs', 'pieces', 0, NULL, 0),(6, 'slcs', 'slices', 0, NULL, 0),(7, 'pck', 'pack', 0, NULL, 0),(8, 'pumps', 'pumps', 0, NULL, 0),(9, 'scoops', 'scoops', 0, NULL, 0),(10, 'ptn', 'portion', 0, '0', 0),(11, 'bags', 'bags', 0, '0', 0);
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`updates` WRITE;
DELETE FROM `ipos_burrow`.`updates`;
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`user_roles` WRITE;
DELETE FROM `ipos_burrow`.`user_roles`;
INSERT INTO `ipos_burrow`.`user_roles` (`id`,`role`,`description`,`access`) VALUES (1, 'Administrator ', 'System Administrator', 'all'),(2, 'Manager', 'Manager', 'dashboard,items,items,list,gcategories,gsubcategories,inq,inv_move,item_inv,glocations,trans,adjustment,receiving,spoilage,report,inv_rep,gsuppliers,guom,menus,charges,menucat,denomination,receiving_inq,menulist,mods,modgrps,modslist,menusubcat,menusched,pos_promos,coupons,gift_cards,grecdiscs,gtaxrates,tblmng,trans,receiving,customers,reps,act_logs,daily_be,monthly_be,drawer_count,act_receipts_all,hourly_rep,menu_history_rep,menu_sales_rep,rep_history,act_receipts,setup,control,roles,user'),(3, 'Employee', 'Employee', 'general_settings,grecdiscs'),(4, 'OIC', 'Officer In Charge', NULL);
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`users` WRITE;
DELETE FROM `ipos_burrow`.`users`;
INSERT INTO `ipos_burrow`.`users` (`id`,`username`,`password`,`pin`,`fname`,`mname`,`lname`,`suffix`,`role`,`email`,`gender`,`reg_date`,`inactive`,`sync_id`,`datetime`) VALUES (1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', '00001', 'admin', 'admin', 'admin', 'Jr.', 1, '', 'male', '2014-06-16 14:41:31', 0, NULL, '2019-04-02 14:31:37');
UNLOCK TABLES;
COMMIT;
BEGIN;
LOCK TABLES `ipos_burrow`.`vistamall` WRITE;
DELETE FROM `ipos_burrow`.`vistamall`;
UNLOCK TABLES;
COMMIT;
