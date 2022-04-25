/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50525
Source Host           : localhost:3306
Source Database       : bon_main

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2017-10-17 09:29:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `migration_logs`
-- ----------------------------

---- START CHANGES FOR MAIN -----

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
  `terminal_id` varchar NULL,
  `branch_code` varchar(250) NULL,
  `record_count` int(11) NULL
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

ALTER TABLE master_logs add `master_sync_id` int(11) null;


-- DINE MASTER ADDED 

ALTER TABLE trans_sales add terminal_id int(11) null;
ALTER TABLE trans_sales add branch_code varchar(250) null;

ALTER TABLE trans_sales_charges add terminal_id int(11) null;
ALTER TABLE trans_sales_charges add branch_code varchar(250) null;

ALTER TABLE trans_sales_discounts add terminal_id int(11) null;
ALTER TABLE trans_sales_discounts add branch_code varchar(250) null;

ALTER TABLE trans_sales_items add terminal_id int(11) null;
ALTER TABLE trans_sales_items add branch_code varchar(250) null;

ALTER TABLE trans_sales_local_tax add terminal_id int(11) null;
ALTER TABLE trans_sales_local_tax add branch_code varchar(250) null;

ALTER TABLE trans_sales_loyalty_points add terminal_id int(11) null;
ALTER TABLE trans_sales_loyalty_points add branch_code varchar(250) null;

ALTER TABLE trans_sales_menu_modifiers add terminal_id int(11) null;
ALTER TABLE trans_sales_menu_modifiers add branch_code varchar(250) null;


ALTER TABLE trans_sales_menus add terminal_id int(11) null;
ALTER TABLE trans_sales_menus add branch_code varchar(250) null;

ALTER TABLE trans_sales_no_tax add terminal_id int(11) null;
ALTER TABLE trans_sales_no_tax add branch_code varchar(250) null;

ALTER TABLE trans_sales_payments add terminal_id int(11) null;
ALTER TABLE trans_sales_payments add branch_code varchar(250) null;


ALTER TABLE trans_sales_tax add terminal_id int(11) null;
ALTER TABLE trans_sales_tax add branch_code varchar(250) null;

ALTER TABLE trans_sales_zero_rated add terminal_id int(11) null;
ALTER TABLE trans_sales_zero_rated add branch_code varchar(250) null;

ALTER TABLE trans_spoilage add terminal_id int(11) null;
ALTER TABLE trans_spoilage add branch_code varchar(250) null;


ALTER TABLE trans_spoilage_details add terminal_id int(11) null;
ALTER TABLE trans_spoilage_details add branch_code varchar(250) null;


ALTER TABLE trans_voids add terminal_id int(11) null;
ALTER TABLE trans_voids add branch_code varchar(250) null;


ALTER TABLE trans_refs add terminal_id int(11) null;
ALTER TABLE trans_refs add branch_code varchar(250) null;


ALTER TABLE users add terminal_id int(11) null;
ALTER TABLE users add branch_code varchar(250) null;

ALTER TABLE users add `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE users add master_id int(11) null; 
--- MASTER TABLES UPDATED
-- ALTER TABLE coupons add master_id int(11) null;

-- ----------------------------
-- Records of migration_logs
-- ----------------------------

---- END CHANGES FOR MAIN -----
