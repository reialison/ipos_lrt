DROP TABLE IF EXISTS `sync_types`;
CREATE TABLE IF NOT EXISTS `sync_types` (
  `sync_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `sync_type` varchar(100) NOT NULL,
  PRIMARY KEY (`sync_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sync_types`
--

INSERT INTO `sync_types` (`sync_type_id`, `sync_type`) VALUES
(1, 'local to main'),
(2, 'main to local');
SET FOREIGN_KEY_CHECKS=1;

DROP TABLE IF EXISTS `menu_subcategory`;
CREATE TABLE `menu_subcategory` (
  `menu_sub_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_sub_name` varchar(150) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`menu_sub_id`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `sync_logs`;
CREATE TABLE IF NOT EXISTS `sync_logs` (
  `sync_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction` varchar(250) DEFAULT NULL,
  `type` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `migrate_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `src_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_automated` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`sync_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
SET FOREIGN_KEY_CHECKS=1;

ALTER TABLE trans_adjustment_details add sync_id int(11) null;
ALTER TABLE trans_adjustments add sync_id int(11) null;
ALTER TABLE trans_receiving_details add sync_id int(11) null;
ALTER TABLE trans_receivings add sync_id int(11) null;
ALTER TABLE trans_refs add sync_id int(11) null;
ALTER TABLE trans_sales add sync_id int(11) null;
ALTER TABLE trans_sales_charges add sync_id int(11) null;
ALTER TABLE trans_sales_discounts add sync_id int(11) null;
ALTER TABLE trans_sales_items add sync_id int(11) null;
ALTER TABLE trans_sales_local_tax add sync_id int(11) null;
ALTER TABLE trans_sales_loyalty_points add sync_id int(11) null;
ALTER TABLE trans_sales_menu_modifiers add sync_id int(11) null;
ALTER TABLE trans_sales_menus add sync_id int(11) null;
ALTER TABLE trans_sales_no_tax add sync_id int(11) null;
ALTER TABLE trans_sales_payments add sync_id int(11) null;
ALTER TABLE trans_sales_tax add sync_id int(11) null;
ALTER TABLE trans_sales_zero_rated add sync_id int(11) null;  
ALTER TABLE trans_spoilage add sync_id int(11) null;  
ALTER TABLE trans_spoilage_details add sync_id int(11) null;  
ALTER TABLE trans_types add sync_id int(11) null; 
ALTER TABLE trans_voids add sync_id int(11) null; 
ALTER TABLE gift_cards add sync_id int(11) null; 
ALTER TABLE table_activity add sync_id int(11) null; 
ALTER TABLE loyalty_cards add sync_id int(11) null; 
ALTER TABLE item_moves add sync_id int(11) null; 
ALTER TABLE read_details add sync_id int(11) null; 
ALTER TABLE receipt_discounts add sync_id int(11) null; 
ALTER TABLE coupons add sync_id int(11) null; 
ALTER TABLE reasons add sync_id int(11) null; 

ALTER TABLE customers_bank add sync_id int(11) null; 
ALTER TABLE logs add sync_id int(11) null; 
ALTER TABLE users add sync_id int(11) null; 
ALTER TABLE tables add sync_id int(11) null; 
ALTER TABLE receipt_discounts add sync_id int(11) null; 
ALTER TABLE trans_sales add branch_code varchar(120) null; 
ALTER TABLE users add branch_code varchar(120) null; 

/**ON MAIN**/
ALTER TABLE customers_bank add   `type_id` int(11) DEFAULT NULL;
ALTER TABLE customers_bank add  `trans_ref` varchar(40) DEFAULT NULL;
ALTER TABLE customers_bank add   `remarks` longtext;


ALTER TABLE trans_sales_charges add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_sales_discounts add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_sales_items add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_sales_local_tax add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_sales_loyalty_points add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_sales_menu_modifiers add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_sales_menus add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_sales_no_tax add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
-- ALTER TABLE trans_sales_payments add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_sales_tax add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_sales_zero_rated add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_spoilage add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_spoilage_details add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_voids add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_refs add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE users add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP; 
ALTER TABLE tables add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP; 
ALTER TABLE receipt_discounts add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP; 


ALTER TABLE  `items` CHANGE  `update_date`  `update_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE categories add `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP; 
ALTER TABLE subcategories add `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP; 
-- ALTER TABLE modifiers add `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP; 


DROP TABLE IF EXISTS `master_logs`;
CREATE TABLE `master_logs` (
  `master_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) DEFAULT NULL,
  `src_id` text DEFAULT NULL,
  `transaction` varchar(250) DEFAULT NULL,
  `type` varchar(250) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_automated` tinyint(1) DEFAULT 0,
  `migrate_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terminal_id` varchar(250) NULL,
  `branch_code` varchar(250) NULL,
  `record_count` int(11) NULL,
  PRIMARY KEY (`master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE trans_adjustment_details add master_id int(11) null;
ALTER TABLE trans_adjustments add master_id int(11) null;
ALTER TABLE trans_receiving_details add master_id int(11) null;
ALTER TABLE trans_receivings add master_id int(11) null;
ALTER TABLE trans_refs add master_id int(11) null;
ALTER TABLE trans_sales add master_id int(11) null;
ALTER TABLE trans_sales_charges add master_id int(11) null;
ALTER TABLE trans_sales_discounts add master_id int(11) null;
ALTER TABLE trans_sales_items add master_id int(11) null;
ALTER TABLE trans_sales_local_tax add master_id int(11) null;
ALTER TABLE trans_sales_loyalty_points add master_id int(11) null;
ALTER TABLE trans_sales_menu_modifiers add master_id int(11) null;
ALTER TABLE trans_sales_menus add master_id int(11) null;
ALTER TABLE trans_sales_no_tax add master_id int(11) null;
ALTER TABLE trans_sales_payments add master_id int(11) null;
ALTER TABLE trans_sales_tax add master_id int(11) null;
ALTER TABLE trans_sales_zero_rated add master_id int(11) null;  
ALTER TABLE trans_spoilage add master_id int(11) null;  
ALTER TABLE trans_spoilage_details add master_id int(11) null;  
ALTER TABLE trans_types add master_id int(11) null; 
ALTER TABLE trans_voids add master_id int(11) null; 
ALTER TABLE gift_cards add master_id int(11) null; 
ALTER TABLE table_activity add master_id int(11) null; 
ALTER TABLE loyalty_cards add master_id int(11) null; 
ALTER TABLE menus add master_id int(11) null; 
ALTER TABLE menu_categories add master_id int(11) null; 
ALTER TABLE menu_modifiers add master_id int(11) null; 
ALTER TABLE menu_subcategories add master_id int(11) null; 
ALTER TABLE modifiers add master_id int(11) null; 
ALTER TABLE modifier_groups add master_id int(11) null; 
ALTER TABLE modifier_group_details add master_id int(11) null; 
ALTER TABLE users add master_id int(11) null; 
ALTER TABLE tables add master_id int(11) null; 
ALTER TABLE receipt_discounts add master_id int(11) null; 
ALTER TABLE coupons add master_id int(11) null; 

ALTER TABLE items add master_id int(11) null; 
ALTER TABLE categories add master_id int(11) null; 
ALTER TABLE subcategories add master_id int(11) null; 

ALTER TABLE menu_categories add master_id int(11) null; 
ALTER TABLE menu_modifiers add master_id int(11) null; 
ALTER TABLE menu_recipe add master_id int(11) null; 
ALTER TABLE menu_schedules add master_id int(11) null; 
ALTER TABLE menu_schedule_details add master_id int(11) null; 
ALTER TABLE menu_subcategories add master_id int(11) null; 
ALTER TABLE menu_subcategory add master_id int(11) null; 
ALTER TABLE modifier_groups add master_id int(11) null; 
ALTER TABLE modifier_group_details add master_id int(11) null; 
ALTER TABLE item_moves add master_id int(11) null; 
ALTER TABLE reasons add master_id int(11) null; 


ALTER TABLE master_logs add `master_sync_id` int(11) null;





ALTER TABLE cashout_entries add sync_id int(11) null; 
ALTER TABLE cashout_details add sync_id int(11) null; 
ALTER TABLE shifts add sync_id int(11) null; 
ALTER TABLE shift_entries add sync_id int(11) null; 

ALTER TABLE cashout_entries add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP; 
ALTER TABLE cashout_details add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP; 
ALTER TABLE shifts add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP; 
ALTER TABLE shift_entries add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE item_moves add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


ALTER TABLE `menu_categories` ADD `arrangement`  int NULL DEFAULT 0;
INSERT INTO `trans_types` (type_id,name,next_ref) values(55,'menu receiving', 'RM00001');
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
  PRIMARY KEY (`move_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

ALTER TABLE menu_moves add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE menu_moves add sync_id int(11) null;
ALTER TABLE menu_moves add master_id int(11) null;

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
  PRIMARY KEY (`receiving_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ALTER TABLE trans_receiving_menu add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_receiving_menu add sync_id int(11) null;
ALTER TABLE trans_receiving_menu add master_id int(11) null;

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
  PRIMARY KEY (`receiving_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

ALTER TABLE trans_receiving_menu_details add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE trans_receiving_menu_details add sync_id int(11) null;
ALTER TABLE trans_receiving_menu_details add master_id int(11) null;

ALTER TABLE trans_receiving_details add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
-- ALTER TABLE trans_receivings add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


ALTER TABLE `trans_sales_menus` ADD COLUMN `nocharge`  int NULL DEFAULT 0;
ALTER TABLE `trans_sales_items` ADD COLUMN `nocharge`  int NULL DEFAULT 0;
ALTER TABLE `menu_schedules` add master_id int(11) null;
ALTER TABLE `menu_recipe` add master_id int(11) null;
ALTER TABLE `reasons` add master_id int(11) null;
ALTER TABLE `modifier_recipe` add master_id int(11) null;
ALTER TABLE `menu_subcategory` add master_id int(11) null;

ALTER TABLE reasons add `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP; 

ALTER TABLE locations add sync_id int(11) null; 
ALTER TABLE locations add master_id int(11) null; 
ALTER TABLE locations add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


ALTER TABLE suppliers add sync_id int(11) null; 
ALTER TABLE suppliers add master_id int(11) null; 
ALTER TABLE suppliers add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


ALTER TABLE terminals add sync_id int(11) null; 
ALTER TABLE terminals add master_id int(11) null; 
ALTER TABLE terminals add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- 09262018
  ALTER TABLE items add `brand` varchar(55) NULL;
  ALTER TABLE items add `costing` double NULL DEFAULT 0;
  ALTER TABLE item_moves add `cost` double NULL DEFAULT 0;


DROP TABLE IF EXISTS `transfer_split`;
CREATE TABLE `transfer_split` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `details` varchar(255) DEFAULT NULL,
  `type` varchar(55) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `menus` varchar(255) DEFAULT NULL,
  `sales_id` int(11) DEFAULT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE transfer_split add sync_id int(11) null;
ALTER TABLE transfer_split add master_id int(11) null;
ALTER TABLE `items` ADD `date_effective` DATE NULL AFTER `type`;
ALTER TABLE `menus` ADD `date_effective` DATE NULL AFTER `inactive`;

ALTER TABLE `menus` ADD `master_id` int(11) NULL DEFAULT NULL AFTER `inactive`;
ALTER TABLE `menu_categories` ADD `master_id` int(11) NULL DEFAULT NULL AFTER `inactive`;
ALTER TABLE `menu_schedules` ADD `master_id` int(11) NULL DEFAULT NULL AFTER `inactive`;
ALTER TABLE `menu_recipe` ADD `master_id` int(11) NULL DEFAULT NULL AFTER `menu_id`;
ALTER TABLE `modifier_recipe` ADD `master_id` int(11) NULL DEFAULT NULL AFTER `mod_id`;
ALTER TABLE `modifier_groups` ADD `master_id` int(11) NULL DEFAULT NULL AFTER `inactive`;
ALTER TABLE `modifier_group_details` ADD `master_id` int(11) NULL DEFAULT NULL AFTER `mod_id`;
ALTER TABLE `modifiers` ADD `master_id` int(11) NULL DEFAULT NULL AFTER `inactive`;
ALTER TABLE `menu_modifiers` ADD `master_id` int(11) NULL DEFAULT NULL  AFTER `mod_group_id`;
