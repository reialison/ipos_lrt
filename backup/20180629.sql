#
# TABLE STRUCTURE FOR: araneta
#

DROP TABLE IF EXISTS araneta;

CREATE TABLE `araneta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lessee_name` varchar(20) DEFAULT NULL,
  `lessee_no` varchar(20) DEFAULT NULL,
  `space_code` varchar(20) DEFAULT '',
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

INSERT INTO araneta (`id`, `lessee_name`, `lessee_no`, `space_code`, `file_path`) VALUES (1, 'HAPCHAN', '30436', '141040\r', 'C:/ARANETA');


#
# TABLE STRUCTURE FOR: ayala
#

DROP TABLE IF EXISTS ayala;

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

INSERT INTO ayala (`id`, `contract_no`, `store_name`, `xxx_no`, `dbf_tenant_name`, `dbf_path`, `text_file_path`) VALUES (1, '6000000000025', 'BARCINO UNIT 20', 'AYA', 'BARCINO UNIT 20', 'C:/AYALA/', 'C:/AYALA/');


#
# TABLE STRUCTURE FOR: branch_details
#

DROP TABLE IF EXISTS branch_details;

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

INSERT INTO branch_details (`branch_id`, `res_id`, `branch_code`, `branch_name`, `branch_desc`, `contact_no`, `delivery_no`, `address`, `base_location`, `currency`, `image`, `inactive`, `tin`, `machine_no`, `bir`, `permit_no`, `serial`, `email`, `website`, `store_open`, `store_close`, `rob_tenant_code`, `rob_path`, `rob_username`, `rob_password`, `accrdn`, `rec_footer`, `pos_footer`) VALUES (1, 1, 'JEDPOS', 'Barcino Wine Resto Bar', 'Tarraco Group Inc.', '(02) 821 0917', '', 'Bldg. 7 Unit No. 5 Molito Lifestyle Extension, Ayala Alabang NCR, Fourth District City of Muntinlupa.', NULL, 'PHP', 'layout.png', 0, '006-884-753-009', '17091911074538050', '0', 'FP092017-53B-0137842-00009', 'P1BRCN003', '', '', '09:00:00', '06:30:00', '1234', '190.125.220.1', 'mag15836hap', 'maghapex', '43A0085434442014110212', 'This serves as your Official Receipt.<br>Thank you and Please come again.', '');


#
# TABLE STRUCTURE FOR: branch_menus
#

DROP TABLE IF EXISTS branch_menus;

CREATE TABLE `branch_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `menu_code` varchar(15) DEFAULT NULL,
  `menu_name` varchar(25) DEFAULT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

#
# TABLE STRUCTURE FOR: cashout_details
#

DROP TABLE IF EXISTS cashout_details;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

INSERT INTO cashout_details (`id`, `cashout_id`, `type`, `denomination`, `reference`, `total`, `sync_id`, `datetime`) VALUES (1, 1, 'credit', NULL, '123', '845', NULL, '2018-06-29 11:06:02');


#
# TABLE STRUCTURE FOR: cashout_entries
#

DROP TABLE IF EXISTS cashout_entries;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO cashout_entries (`cashout_id`, `user_id`, `terminal_id`, `drawer_amount`, `count_amount`, `trans_date`, `sync_id`, `datetime`) VALUES (1, '1', 1, '3963.26', '845', '2018-06-29 11:06:02', NULL, '2018-06-29 11:06:02');


#
# TABLE STRUCTURE FOR: categories
#

DROP TABLE IF EXISTS categories;

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (1, 'SUBCAT-DAIRY', 'DAIRY PRODUCTS', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (2, 'SUBCAT-MEAT', 'FRESH AND PROCESSED MEAT PRODUCTS', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (3, 'SUBCAT-FATS', 'OIL AND NUTS', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (4, 'SUBCAT-FSEA', 'SEAFOODS', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (5, 'SUBCAT-FVEG', 'VEGETABLES AND FRUITS', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (6, 'SUBCAT-ALC', 'ALCOHOLIC BEVERAGES', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (7, 'SUBCAT-NONALC', 'NON-ALCOHOLIC BEVERAGES', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (8, 'SUBCAT-BAKE', 'BAKERY PRODUCTS', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (9, 'SUBCAT-DRY', 'DRY GOODS', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (10, 'SUBCAT-CONDI', 'CONDIMENTS, SAUCES, AND SPICES', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (11, 'SUBCAT-MIX', 'MIXED INGREDIENTS', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (12, 'SUBCAT-SUBRECIP', 'SUBRECIPES', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (13, 'SUBCAT-OTH', 'OTHERS', NULL, 0);
INSERT INTO categories (`cat_id`, `code`, `name`, `image`, `inactive`) VALUES (14, 'SUBCAT-QUESOS', 'QUESOS', NULL, 0);


#
# TABLE STRUCTURE FOR: charges
#

DROP TABLE IF EXISTS charges;

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

INSERT INTO charges (`charge_id`, `charge_code`, `charge_name`, `charge_amount`, `absolute`, `no_tax`, `inactive`) VALUES (1, 'SCHG', 'Service Charge', '10', 0, 1, 0);
INSERT INTO charges (`charge_id`, `charge_code`, `charge_name`, `charge_amount`, `absolute`, `no_tax`, `inactive`) VALUES (2, 'DCHG', 'Delivery Charge', '5', 0, 1, 0);
INSERT INTO charges (`charge_id`, `charge_code`, `charge_name`, `charge_amount`, `absolute`, `no_tax`, `inactive`) VALUES (3, 'ELECTRICITY CHARGE', 'ELECTRICITY CHARGE', '500', 0, 0, 0);


#
# TABLE STRUCTURE FOR: company
#

DROP TABLE IF EXISTS company;

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

#
# TABLE STRUCTURE FOR: coupons
#

DROP TABLE IF EXISTS coupons;

CREATE TABLE `coupons` (
  `coupon_id` int(10) NOT NULL AUTO_INCREMENT,
  `card_no` varchar(100) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `expiration` date DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

#
# TABLE STRUCTURE FOR: currencies
#

DROP TABLE IF EXISTS currencies;

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(22) DEFAULT NULL,
  `currency_desc` varchar(55) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO currencies (`id`, `currency`, `currency_desc`, `inactive`) VALUES (1, 'PHP', 'Philippine Peso', 0);
INSERT INTO currencies (`id`, `currency`, `currency_desc`, `inactive`) VALUES (2, 'USD', 'US Dollars', 0);
INSERT INTO currencies (`id`, `currency`, `currency_desc`, `inactive`) VALUES (3, 'YEN', 'Japanese Yen', 0);


#
# TABLE STRUCTURE FOR: currency_details
#

DROP TABLE IF EXISTS currency_details;

CREATE TABLE `currency_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_id` varchar(45) NOT NULL,
  `desc` varchar(60) NOT NULL,
  `value` double NOT NULL,
  `img` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

#
# TABLE STRUCTURE FOR: customer_address
#

DROP TABLE IF EXISTS customer_address;

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

#
# TABLE STRUCTURE FOR: customers
#

DROP TABLE IF EXISTS customers;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO customers (`cust_id`, `phone`, `email`, `fname`, `mname`, `lname`, `suffix`, `tax_exempt`, `street_no`, `street_address`, `city`, `region`, `zip`, `inactive`, `reg_date`) VALUES (1, '2346', '5765', 'Rey', 'C', 'Tejada', '', NULL, '656', '565', '5665', '7', '7868', 0, NULL);
INSERT INTO customers (`cust_id`, `phone`, `email`, `fname`, `mname`, `lname`, `suffix`, `tax_exempt`, `street_no`, `street_address`, `city`, `region`, `zip`, `inactive`, `reg_date`) VALUES (2, '0123456789', '1234.tarraco@gmail.com', 'Martin ', '', 'Lorenzo', '', NULL, '', '', 'nivauqe8qve', NULL, '1772', 0, '2017-08-07 22:50:24');


#
# TABLE STRUCTURE FOR: customers_bank
#

DROP TABLE IF EXISTS customers_bank;

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

#
# TABLE STRUCTURE FOR: denominations
#

DROP TABLE IF EXISTS denominations;

CREATE TABLE `denominations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc` varchar(60) NOT NULL,
  `value` double NOT NULL,
  `img` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

INSERT INTO denominations (`id`, `desc`, `value`, `img`) VALUES (1, 'One Thousand', '1000', NULL);
INSERT INTO denominations (`id`, `desc`, `value`, `img`) VALUES (2, 'Five Hundreds', '500', NULL);
INSERT INTO denominations (`id`, `desc`, `value`, `img`) VALUES (3, 'Two Hundreds', '200', NULL);
INSERT INTO denominations (`id`, `desc`, `value`, `img`) VALUES (4, 'One Hundreds', '100', NULL);
INSERT INTO denominations (`id`, `desc`, `value`, `img`) VALUES (5, 'Fifty', '50', NULL);
INSERT INTO denominations (`id`, `desc`, `value`, `img`) VALUES (6, 'Twenty', '20', NULL);
INSERT INTO denominations (`id`, `desc`, `value`, `img`) VALUES (7, 'Ten', '10', NULL);
INSERT INTO denominations (`id`, `desc`, `value`, `img`) VALUES (8, 'Five', '5', NULL);
INSERT INTO denominations (`id`, `desc`, `value`, `img`) VALUES (9, 'One', '1', NULL);
INSERT INTO denominations (`id`, `desc`, `value`, `img`) VALUES (10, 'Twenty Five Cents', '0.25', NULL);
INSERT INTO denominations (`id`, `desc`, `value`, `img`) VALUES (11, 'Ten Cents', '0.1', NULL);


#
# TABLE STRUCTURE FOR: dtr_scheduler
#

DROP TABLE IF EXISTS dtr_scheduler;

CREATE TABLE `dtr_scheduler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `dtr_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (1, 1, '2014-10-28', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (3, 3, '2014-10-28', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (4, 4, '2014-10-28', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (5, 5, '2014-10-28', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (14, 6, '2014-10-28', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (15, 1, '2014-10-29', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (16, 3, '2014-10-29', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (17, 4, '2014-10-29', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (18, 5, '2014-10-29', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (19, 6, '2014-10-29', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (22, 1, '2014-10-30', 3);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (23, 3, '2014-10-30', 3);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (24, 5, '2014-10-30', 5);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (25, 1, '2014-10-31', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (26, 3, '2014-10-31', 5);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (27, 4, '2014-10-31', 4);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (28, 5, '2014-10-31', 5);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (29, 6, '2014-10-31', 4);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (30, 1, '2014-11-01', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (31, 3, '2014-11-01', 5);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (32, 4, '2014-11-01', 4);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (33, 5, '2014-11-01', 5);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (34, 6, '2014-11-01', 4);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (35, 1, '2014-11-02', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (36, 4, '2014-11-02', 6);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (37, 6, '2014-11-02', 6);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (38, 5, '2014-11-12', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (39, 6, '2014-11-12', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (40, 5, '2014-11-13', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (41, 6, '2014-11-13', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (42, 5, '2014-11-14', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (43, 6, '2014-11-14', 2);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (44, 16, '2014-11-24', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (45, 17, '2014-11-24', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (46, 18, '2014-11-24', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (47, 16, '2014-11-25', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (48, 17, '2014-11-25', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (49, 18, '2014-11-25', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (50, 16, '2014-11-26', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (51, 17, '2014-11-26', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (52, 18, '2014-11-26', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (53, 16, '2014-11-27', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (54, 17, '2014-11-27', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (55, 18, '2014-11-27', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (56, 16, '2014-11-28', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (57, 17, '2014-11-28', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (58, 18, '2014-11-28', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (59, 16, '2014-11-29', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (60, 17, '2014-11-29', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (61, 18, '2014-11-29', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (62, 16, '2014-11-30', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (63, 17, '2014-11-30', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (64, 18, '2014-11-30', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (65, 19, '2014-11-24', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (66, 19, '2014-11-25', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (67, 19, '2014-11-26', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (68, 19, '2014-11-27', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (69, 19, '2014-11-28', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (70, 19, '2014-11-29', 7);
INSERT INTO dtr_scheduler (`id`, `user_id`, `date`, `dtr_id`) VALUES (71, 19, '2014-11-30', 7);


#
# TABLE STRUCTURE FOR: dtr_shifts
#

DROP TABLE IF EXISTS dtr_shifts;

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

INSERT INTO dtr_shifts (`id`, `code`, `description`, `time_in`, `break_out`, `break_in`, `time_out`, `break_hours`, `work_hours`, `inactive`, `grace_period`, `timein_grace_period`) VALUES (1, 'RESTDAY', 'Rest Day', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '0', '0', 0, '00:00:00', NULL);
INSERT INTO dtr_shifts (`id`, `code`, `description`, `time_in`, `break_out`, `break_in`, `time_out`, `break_hours`, `work_hours`, `inactive`, `grace_period`, `timein_grace_period`) VALUES (2, 'Shift1', 'restday again', '07:00:00', '11:00:00', '12:00:00', '16:00:00', '1', '9', 0, '00:00:00', '00:30:00');
INSERT INTO dtr_shifts (`id`, `code`, `description`, `time_in`, `break_out`, `break_in`, `time_out`, `break_hours`, `work_hours`, `inactive`, `grace_period`, `timein_grace_period`) VALUES (3, '6PM7AM', '6PM to 7AM', '18:00:00', '00:00:00', '00:00:00', '07:00:00', '0', '13', 0, '00:00:00', '00:00:00');
INSERT INTO dtr_shifts (`id`, `code`, `description`, `time_in`, `break_out`, `break_in`, `time_out`, `break_hours`, `work_hours`, `inactive`, `grace_period`, `timein_grace_period`) VALUES (4, '7AM7PM', '7AM to 7PM', '07:00:00', '00:00:00', '00:00:00', '19:00:00', '0', '12', 0, '00:15:00', '01:00:00');
INSERT INTO dtr_shifts (`id`, `code`, `description`, `time_in`, `break_out`, `break_in`, `time_out`, `break_hours`, `work_hours`, `inactive`, `grace_period`, `timein_grace_period`) VALUES (5, '7PM7AM', '7PM to 7AM', '19:00:00', '00:00:00', '00:00:00', '07:00:00', '0', '-12', 0, '00:15:00', '01:00:00');
INSERT INTO dtr_shifts (`id`, `code`, `description`, `time_in`, `break_out`, `break_in`, `time_out`, `break_hours`, `work_hours`, `inactive`, `grace_period`, `timein_grace_period`) VALUES (6, '7AM4PM', '7AM to 4PM', '07:00:00', '00:00:00', '00:00:00', '16:00:00', '0', '9', 0, '00:15:00', '01:00:00');
INSERT INTO dtr_shifts (`id`, `code`, `description`, `time_in`, `break_out`, `break_in`, `time_out`, `break_hours`, `work_hours`, `inactive`, `grace_period`, `timein_grace_period`) VALUES (7, '9AM10PM', '9AM10PM', '09:00:00', '00:00:00', '00:00:00', '22:00:00', '1', '13', 0, '00:00:00', '00:00:00');


#
# TABLE STRUCTURE FOR: eton
#

DROP TABLE IF EXISTS eton;

CREATE TABLE `eton` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_code` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO eton (`id`, `tenant_code`, `file_path`) VALUES (1, 'ABCD1234', 'C:/ETON');


#
# TABLE STRUCTURE FOR: gift_cards
#

DROP TABLE IF EXISTS gift_cards;

CREATE TABLE `gift_cards` (
  `gc_id` int(10) NOT NULL AUTO_INCREMENT,
  `card_no` varchar(100) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0',
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`gc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO gift_cards (`gc_id`, `card_no`, `amount`, `inactive`, `sync_id`) VALUES (1, '110628', '1000', 1, NULL);
INSERT INTO gift_cards (`gc_id`, `card_no`, `amount`, `inactive`, `sync_id`) VALUES (2, '1123', '5000', 1, NULL);
INSERT INTO gift_cards (`gc_id`, `card_no`, `amount`, `inactive`, `sync_id`) VALUES (3, '4545', '1000', 0, NULL);


#
# TABLE STRUCTURE FOR: images
#

DROP TABLE IF EXISTS images;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

INSERT INTO images (`img_id`, `img_file_name`, `img_path`, `img_ref_id`, `img_tbl`, `img_blob`, `datetime`, `disabled`) VALUES (8, 'barcino1.png', 'uploads/splash/barcino1.png', NULL, 'splash_images', NULL, NULL, 0);


#
# TABLE STRUCTURE FOR: item_moves
#

DROP TABLE IF EXISTS item_moves;

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
  PRIMARY KEY (`move_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: item_types
#

DROP TABLE IF EXISTS item_types;

CREATE TABLE `item_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO item_types (`id`, `type`) VALUES (1, 'Not For Resale');
INSERT INTO item_types (`id`, `type`) VALUES (2, 'For Resale');


#
# TABLE STRUCTURE FOR: items
#

DROP TABLE IF EXISTS items;

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
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=912 DEFAULT CHARSET=latin1;

INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (1, '', 'SUBCAT-DAIRY-CHZ-MANCHEGO', 'Manchego Sheep Scured', 'Manchego Sheep Scured', 1, 1, 0, 'gm', '1.41', 2, '0', 'gm', '0', '0', '0', NULL, NULL, '2018-06-18 14:24:18', 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (2, '', 'SUBCAT-MEAT-PROC-CHORIZOP', 'Chorizo Picante', 'Chorizo Picante', 2, 2, 0, 'gm', '0.51', 2, '0', 'gm', '0', '0', '0', NULL, NULL, '2018-06-21 10:41:55', 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (3, '', 'SUBCAT-FATS-OIL-POMACEOLI', 'Pomace Olive Oil 5L', 'Pomace Olive Oil 5L', 3, 3, 0, 'ml', '0.12', 2, '0', 'ml', '0', '0', '0', NULL, NULL, '2018-06-21 10:42:16', 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (4, NULL, 'SUBCAT-MEAT-PROC-CHISTORR', 'Chistorra Sausage ', 'Chistorra Sausage ', 2, 2, NULL, 'gm', '0.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (5, NULL, 'SUBCAT-FSEA-SEA-TIGERPRAW', 'Tiger Prawns ', 'Tiger Prawns ', 4, 4, NULL, 'gm', '0.77', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (6, NULL, 'SUBCAT-FVEG-VEG-GARLIC', 'Garlic ', 'Garlic ', 5, 5, NULL, 'gm', '0.15', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (7, NULL, 'SUBCAT-FVEG-VEG-CHILLIPEP', 'Chilli pepper', 'Chilli pepper', 5, 5, NULL, 'gm', '0.24', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (8, NULL, 'SUBCAT-ALC-WINE-PLUVIUMWH', 'Pluvium White Wine', 'Pluvium White Wine', 6, 6, NULL, 'ml', '0.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (9, NULL, 'SUBCAT-FVEG-VEG-POTATO', 'Potato', 'Potato', 5, 5, NULL, 'gm', '0.09', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (10, NULL, 'SUBCAT-MEAT-POUL-EGG', 'Egg ', 'Egg ', 2, 7, NULL, 'pc', '6.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (11, NULL, 'SUBCAT-BAKE-BAK-BAGUETTE', 'Baguette', 'Baguette', 8, 8, NULL, 'gm', '0.12', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (12, NULL, 'SUBCAT-FVEG-FRU-TOMATO', 'Tomato', 'Tomato', 5, 9, NULL, 'gm', '0.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (13, NULL, 'SUBCAT-FVEG-FRU-LEMON', 'Lemon', 'Lemon', 5, 9, NULL, 'gm', '0.2', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (14, NULL, 'SUBCAT-DRY-JAPANESERICE', 'Japanese Rice', 'Japanese Rice', 9, 0, NULL, 'gm', '0.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (15, NULL, 'SUBCAT-CONDI-SPI-CARMENSI', 'Carmensita Paellero ', 'Carmensita Paellero ', 10, 10, NULL, 'gm', '1.49', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (16, NULL, 'SUBCAT-DRY-SALTIODIZED', 'Iodized Salt', 'Iodized Salt', 9, 0, NULL, 'gm', '0.02', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (17, NULL, 'SUBCAT-CONDI-SPI-BLACKPEP', 'Black Pepper', 'Black Pepper', 10, 10, NULL, 'gm', '0.51', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (18, NULL, 'SUBCAT-CONDI-SPI-WHITEPEP', 'White Pepper', 'White Pepper', 10, 10, NULL, 'gm', '0.51', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (19, NULL, 'SUBCAT-FVEG-VEG-ONION', 'Onion', 'Onion', 5, 5, NULL, 'gm', '0.06', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (20, NULL, 'SUBCAT-FVEG-VEG-PARSLEY', 'Parsley', 'Parsley', 5, 5, NULL, 'gm', '0.25', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (21, NULL, 'SUBCAT-CONDI-SPI-PAPRIKA', 'Paprika', 'Paprika', 10, 10, NULL, 'gm', '0.42', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (22, NULL, 'SUBCAT-FATS-OIL-EXTRAVIRG', 'Extra Virgin Olive Oil', 'Extra Virgin Olive Oil', 3, 3, NULL, 'ml', '0.26', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (23, NULL, 'SUBCAT-DRY-FLOUR', 'Flour', 'Flour', 9, 0, NULL, 'gm', '0.04', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (24, NULL, 'SUBCAT-FSEA-SEA-CLAMS', 'Clams', 'Clams', 4, 4, NULL, 'gm', '0.15', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (25, NULL, 'SUBCAT-FSEA-SEA-MUSSELS', 'Mussels', 'Mussels', 4, 4, NULL, 'gm', '0.1', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (26, NULL, 'SUBCAT-FSEA-SEA-SQUIDTUBE', 'Squid Tube', 'Squid Tube', 4, 4, NULL, 'gm', '0.17', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (27, NULL, 'SUBCAT-MIX-SAU-TOMATOSAUC', 'Tomato Sauce', 'Tomato Sauce', 11, 11, NULL, 'ml', '0.06', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (28, NULL, 'SUBCAT-DAIRY-BUT-BUTTERAN', 'Butter', 'Butter', 1, 12, NULL, 'gm', '0.31', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (29, NULL, 'SUBCAT-FATS-NUTS-ROASTEDA', 'Roasted almonds', 'Roasted almonds', 3, 13, NULL, 'gm', '0.75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (30, NULL, 'SUBCAT-FVEG-VEG-REDBELLPE', 'Red bell pepper', 'Red bell pepper', 5, 5, NULL, 'gm', '0.2', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (31, NULL, 'SUBCAT-OTH-TAPWATER', 'Tap water', 'Tap water', 13, 0, NULL, 'ml', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (32, NULL, 'SUBCAT-DAIRY-MILK-FRESHMI', 'Fresh milk', 'Fresh milk', 1, 14, NULL, 'ml', '0.05', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (33, NULL, 'SUBCAT-DRY-SUGARWHITE', 'Sugar', 'Sugar', 9, 0, NULL, 'gm', '0.05', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (34, NULL, 'SUBCAT-CONDI-SAU-BALSAMIC', 'Balsamic vinegar', 'Balsamic vinegar', 10, 15, NULL, 'ml', '0.23', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (35, NULL, 'SUBCAT-ALC-WINE-PLUVIUMRE', 'Pluvium red wine ', 'Pluvium red wine ', 6, 6, NULL, 'ml', '0.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (36, NULL, 'SUBCAT-CONDI-SAU-REDWINEV', 'Red Wine Vinegar', 'Red Wine Vinegar', 10, 15, NULL, 'ml', '0.09', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (37, NULL, 'SUBCAT-FVEG-VEG-LETTUCELO', 'Lettuce Lolo rosa Red', 'Lettuce Lolo rosa Red', 5, 5, NULL, 'gm', '0.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (38, NULL, 'SUBCAT-FVEG-VEG-LETTUCELO', 'Lettuce Lolo rosa Green', 'Lettuce Lolo rosa Green', 5, 5, NULL, 'gm', '0.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (39, NULL, 'SUBCAT-DRY-JAPANESEBREADC', 'Japanese Bread Crumbs', 'Japanese Bread Crumbs', 9, 0, NULL, 'gm', '0.08', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (40, NULL, 'SUBCAT-FVEG-VEG-BELLPEPPE', 'Green bell pepper', 'Green bell pepper', 5, 5, NULL, 'gm', '0.2', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (41, NULL, 'SUBCAT-MEAT-PROC-MORCILLA', 'Morcilla de Arroz', 'Morcilla de Arroz', 2, 2, NULL, 'gm', '0.6', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (42, NULL, 'SUBCAT-FVEG-FRU-GRAPES', 'Grapes ', 'Grapes ', 5, 9, NULL, 'gm', '0.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (43, NULL, 'SUBCAT-DAIRY-CHZ-CAPRICHO', 'Capricho De Cabra (Vega mancha)', 'Capricho De Cabra (Vega mancha)', 1, 1, NULL, 'gm', '0.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (44, NULL, 'SUBCAT-DRY-DICEDTOMATO', 'Diced Tomato 2.55k/can', 'Diced Tomato 2.55k/can', 9, 0, NULL, 'gm', '0.06', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (45, NULL, 'SUBCAT-CONDI-SPI-CHICKENP', 'Chicken Powder', 'Chicken Powder', 10, 10, NULL, 'gm', '0.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (46, NULL, 'SUBCAT-CONDI-SPI-STARANIS', 'Star anis', 'Star anis', 10, 10, NULL, 'gm', '0.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (47, NULL, 'SUBCAT-DAIRY-CREAM-ALLPUR', 'All purpose Cream (240ml)', 'All purpose Cream (240ml)', 1, 16, NULL, 'ml', '0.15', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (48, NULL, 'SUBCAT-FATS-NUTS-ALMONDS', 'Almonds', 'Almonds', 3, 13, NULL, 'gm', '1.12', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (49, NULL, 'SUBCAT-FATS-NUTS-WALNUTS', 'Wallnuts ', 'Wallnuts ', 3, 13, NULL, 'gm', '0.45', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (50, NULL, 'SUBCAT-CONDI-SPI-THYMEDRI', 'Thyme Dried', 'Thyme Dried', 10, 10, NULL, 'gm', '4.6', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (51, NULL, 'SUBCAT-MIX-SAU-CHOCOGANAC', 'Choco Ganache', 'Choco Ganache', 11, 11, NULL, 'gm', '0.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (52, NULL, 'SUBCAT-FVEG-FRU-MELOCOTON', 'Melocoton (720ml)', 'Melocoton (720ml)', 5, 9, NULL, 'gm', '0.23', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (53, NULL, 'SUBCAT-DAIRY-CREAM-ICECRE', 'Ice cream Vanilla', 'Ice cream Vanilla', 1, 16, NULL, 'ml', '0.06', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (54, NULL, 'SUBCAT-DAIRY-CREAM-WHIPPE', 'Whipped Cream (250 grms)', 'Whipped Cream (250 grms)', 1, 16, NULL, 'gm', '0.95', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (55, NULL, 'SUBCAT-FVEG-VEG-MINT', 'Mint Leaves', 'Mint Leaves', 5, 5, NULL, 'gm', '0.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (56, NULL, 'SUBCAT-CONDI-SPI-OREGANOP', 'Oregano Powder', 'Oregano Powder', 10, 10, NULL, 'gm', '9.06', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (57, NULL, 'SUBCAT-DRY-CORNFLOUR', 'Corn flour', 'Corn flour', 9, 0, NULL, 'gm', '0.08', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (58, NULL, 'SUBCAT-CONDI-SPI-CINNAMON', 'Cinnamon powder', 'Cinnamon powder', 10, 10, NULL, 'gm', '0.29', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (59, NULL, 'SUBCAT-FSEA-FISH-BLUEMARL', 'Blue marlin', 'Blue marlin', 4, 17, NULL, 'gm', '0.58', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (60, NULL, 'SUBCAT-FVEG-VEG-BAYLEAF', 'Bay leaf', 'Bay leaf', 5, 5, NULL, 'gm', '0.8', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (61, NULL, 'SUBCAT-CONDI-SPI-CUMINPOW', 'Cumin powder', 'Cumin powder', 10, 10, NULL, 'gm', '0.41', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (62, NULL, 'SUBCAT-FVEG-VEG-EGGPLANT', 'Eggplant', 'Eggplant', 5, 5, NULL, 'gm', '0.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (63, NULL, 'SUBCAT-FVEG-VEG-GREENBELL', 'Green bell pepper', 'Green bell pepper', 5, 5, NULL, 'gm', '0.2', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (64, NULL, 'SUBCAT-CONDI-SPI-ROSEMARY', 'Rosemary whole', 'Rosemary whole', 10, 10, NULL, 'gm', '4.34', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (65, NULL, 'SUBCAT-DAIRY-CHZ-SENORIOC', 'Senorio Curado-TGT', 'Senorio Curado-TGT', 1, 1, NULL, 'gm', '1.19', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (66, NULL, 'SUBCAT-DAIRY-CHZ-MURCIAAL', 'Murcia Al Vino Goat Cheese', 'Murcia Al Vino Goat Cheese', 1, 1, NULL, 'gm', '1.47', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (67, NULL, 'SUBCAT-DAIRY-CHZ-QUESOALP', 'Queso Al Pimenton Large', 'Queso Al Pimenton Large', 1, 1, NULL, 'gm', '1.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (68, NULL, 'SUBCAT-DAIRY-CHZ-RONKARIB', 'Valdeon Replacement (Ronkari Blue Cheese)', 'Valdeon Replacement (Ronkari Blue Cheese)', 1, 1, NULL, 'gm', '1.08', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (69, NULL, 'SUBCAT-DAIRY-CHZ-CAMEMBER', 'Camembert', 'Camembert', 1, 1, NULL, 'gm', '0.84', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (70, NULL, 'SUBCAT-DRY-SKYFLAKES', 'Crackers (skyflakes)', 'Crackers (skyflakes)', 9, 0, NULL, 'gm', '0.23', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (71, NULL, 'SUBCAT-FATS-MAYO-REG', 'Mayonaise (lady choice)', 'Mayonaise (lady choice)', 3, 18, NULL, 'ml', '0.17', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (72, NULL, 'SUBCAT-CONDI-SAU-TABASCO', 'Tabasco', 'Tabasco', 10, 15, NULL, 'ml', '1.42', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (73, NULL, 'SUBCAT-MEAT-PROC-CHORIZOI', 'Chorizo iberico cular ', 'Chorizo iberico cular ', 2, 2, NULL, 'gm', '1.17', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (74, NULL, 'SUBCAT-MEAT-PROC-JAMONSER', 'Jamon serrano', 'Jamon serrano', 2, 2, NULL, 'gm', '1.04', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (75, NULL, 'SUBCAT-MEAT-PROC-VIGILANT', 'Vigilante sardines', 'Vigilante sardines', 2, 2, NULL, 'gm', '0.56', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (76, NULL, 'SUBCAT-MEAT-PROC-CHORIZOB', 'Chorizo bilbao', 'Chorizo bilbao', 2, 2, NULL, 'gm', '0.41', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (77, NULL, 'SUBCAT-MEAT-PROC-SALCHICH', 'Salchichon iberico cular', 'Salchichon iberico cular', 2, 2, NULL, 'gm', '1.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (78, NULL, 'SUBCAT-MEAT-PROC-FUETESPE', 'Fuet espetec extra 180g', 'Fuet espetec extra 180g', 2, 2, NULL, 'gm', '1', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (79, NULL, 'SUBCAT-MEAT-PROC-JAMONIBE', 'Jamon iberico cebo', 'Jamon iberico cebo', 2, 2, NULL, 'gm', '2.41', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (80, NULL, 'SUBCAT-FVEG-VEG-GREENOLIV', 'Karina anchoa', 'Karina anchoa', 5, 5, NULL, 'gm', '0.23', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (81, NULL, 'SUBCAT-FSEA-FISH-SALMON', 'Smoke salmon', 'Smoke salmon', 4, 17, NULL, 'gm', '0.78', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (82, NULL, 'SUBCAT-MEAT-PROC-KANISTIC', 'Kani stick', 'Kani stick', 2, 2, NULL, 'gm', '0.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (83, NULL, 'SUBCAT-FVEG-VEG-BLACKOLIV', 'Black olives pitted 6/935grams', 'Black olives pitted 6/935grams', 5, 5, NULL, 'gm', '0.13', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (84, NULL, 'SUBCAT-FVEG-VEG-LETTUCERO', 'Romaine Lettuce', 'Romaine Lettuce', 5, 5, NULL, 'gm', '0.2', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (85, NULL, 'SUBCAT-FVEG-VEG-CARROTS', 'Carrot', 'Carrot', 5, 5, NULL, 'gm', '0.06', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (86, NULL, 'SUBCAT-MEAT-PROC-CENTURYT', 'Century tuna', 'Century tuna', 2, 2, NULL, 'gm', '0.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (87, NULL, 'SUBCAT-FVEG-VEG-GREENPEAS', 'Green peas', 'Green peas', 5, 5, NULL, 'gm', '0.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (88, NULL, 'SUBCAT-DRY-STRAWBERRYJAM', 'Strawberry Jam (clara ole)', 'Strawberry Jam (clara ole)', 9, 0, NULL, 'gm', '0.38', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (89, NULL, 'SUBCAT-FVEG-FRU-MANGO', 'Ripe mango', 'Ripe mango', 5, 9, NULL, 'gm', '0.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (90, NULL, 'SUBCAT-MEAT-POUL-CHICKENB', 'Chicken Breast', 'Chicken Breast', 2, 7, NULL, 'gm', '0.26', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (91, NULL, 'SUBCAT-FVEG-FRU-ORANGE', 'Orange', 'Orange', 5, 9, NULL, 'pc', '30', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (92, NULL, 'SUBCAT-DRY-HONEY', 'Honey 1L (palawan)', 'Honey 1L (palawan)', 9, 0, NULL, 'ml', '0.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (93, NULL, 'SUBCAT-CONDI-SAU-MUSTARD', 'Mustard 200g', 'Mustard 200g', 10, 15, NULL, 'gm', '0.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (94, NULL, 'SUBCAT-MEAT-BEEF-BEEFSHAN', 'Beef shank', 'Beef shank', 2, 19, NULL, 'gm', '0.26', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (95, NULL, 'SUBCAT-FVEG-VEG-CABBAGE', 'Cabbage', 'Cabbage', 5, 5, NULL, 'gm', '0.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (96, NULL, 'SUBCAT-CONDI-SPI-BEEFCUBE', 'Beef cubes', 'Beef cubes', 10, 10, NULL, 'pc', '4.39', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (97, NULL, 'SUBCAT-CONDI-SPI-WHOLEPEP', 'Whole pepper corn (spices)', 'Whole pepper corn (spices)', 10, 10, NULL, 'gm', '2.44', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (98, NULL, 'SUBCAT-FVEG-VEG-BAGUIOBEA', 'Baguio beans ', 'Baguio beans ', 5, 5, NULL, 'gm', '0.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (99, NULL, 'SUBCAT-FVEG-VEG-ONIONLEEK', 'onion leeks', 'onion leeks', 5, 5, NULL, 'gm', '0.12', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (100, NULL, 'SUBCAT-FSEA-FISH-MAYAMAYA', 'Maya Maya', 'Maya Maya', 4, 17, NULL, 'gm', '0.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (101, NULL, 'SUBCAT-ALC-LIQ-GRANMATADO', 'Gran Matador 700ml', 'Gran Matador 700ml', 6, 20, NULL, 'ml', '0.1', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (102, NULL, 'SUBCAT-MEAT-POUL-CHICKENT', 'Chicken thigh', 'Chicken thigh', 2, 7, NULL, 'gm', '0.2', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (103, NULL, 'SUBCAT-CONDI-SPI-CUMINGRO', 'Ground cumins', 'Ground cumins', 10, 10, NULL, 'gm', '0.58', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (104, NULL, 'SUBCAT-MEAT-PORK-DICEDPOR', 'Diced pork (menudo)', 'Diced pork (menudo)', 2, 21, NULL, 'gm', '0.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (105, NULL, 'SUBCAT-OTH-BAMBOOSTICK', 'Bamboo stick', 'Bamboo stick', 13, 0, NULL, 'pc', '0.27', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (106, NULL, 'SUBCAT-CONDI-SPI-BLACKPEP', 'Black pepper whole', 'Black pepper whole', 10, 10, NULL, 'gm', '2.44', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (107, NULL, 'SUBCAT-FSEA-SEA-BABYSQUID', 'Baby squid ', 'Baby squid ', 4, 4, NULL, 'gm', '0.39', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (108, NULL, 'SUBCAT-MEAT-PORK-PORKLIEM', 'Pork liempo', 'Pork liempo', 2, 21, NULL, 'gm', '0.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (109, NULL, 'SUBCAT-MEAT-BEEF-BEEFSTRI', 'Beef striploin', 'Beef striploin', 2, 19, NULL, 'gm', '0.95', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (110, NULL, 'SUBCAT-MEAT-PROC-BUTIFFAR', 'Butiffara', 'Butiffara', 2, 2, NULL, 'gm', '0.6', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (111, NULL, 'SUBCAT-FSEA-SEA-SQUIDINK', 'Squid ink 320ml', 'Squid ink 320ml', 4, 4, NULL, 'ml', '0.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (112, NULL, 'SUBCAT-FSEA-SEA-FRESHSQUI', 'Fresh Squid ', 'Fresh Squid ', 4, 4, NULL, 'gm', '0.39', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (113, NULL, 'SUBCAT-DRY-WHITERICE', 'White Rice', 'White Rice', 9, 0, NULL, 'gm', '0.06', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (114, NULL, 'SUBCAT-DAIRY-CHZ-QUICKMEL', 'Quickmelt cheese ', 'Quickmelt cheese ', 1, 1, NULL, 'gm', '0.34', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (115, NULL, 'SUBCAT-MEAT-BEEF-GROUNDBE', 'Ground beef ', 'Ground beef ', 2, 19, NULL, 'gm', '0.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (116, NULL, 'SUBCAT-BAKE-PASTA-CANNELO', 'canneloni roll pasta (baronia)', 'canneloni roll pasta (baronia)', 8, 22, NULL, 'gm', '0.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (117, NULL, 'SUBCAT-MEAT-PORK-PORKRIB', 'Pork Rib', 'Pork Rib', 2, 21, NULL, 'gm', '0.26', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (118, NULL, 'SUBCAT-FVEG-VEG-SHITAKEMU', 'Shitake mushroom', 'Shitake mushroom', 5, 5, NULL, 'gm', '0.71', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (119, NULL, 'SUBCAT-OTH-TOOTHPICK', 'Toothpick', 'Toothpick', 13, 0, NULL, 'pc', '0.27', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (120, NULL, 'SUBCAT-CONDI-SPI-SHRIMPCU', 'Shrimp cubes', 'Shrimp cubes', 10, 10, NULL, 'pc', '5.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (121, NULL, 'SUBCAT-DRY-FINEBREADCRUMB', 'Fine bread crumbs', 'Fine bread crumbs', 9, 0, NULL, 'gm', '0.08', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (122, NULL, 'SUBCAT-CONDI-SPI-PORKCUBE', 'Pork cubes', 'Pork cubes', 10, 10, NULL, 'pc', '4.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (123, NULL, 'SUBCAT-FATS-OIL-VIRGINOIL', 'Vigin oil 1L', 'Vigin oil 1L', 3, 3, NULL, 'ml', '0.23', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (124, NULL, 'SUBCAT-FSEA-SEA-OCTOPUS', 'Octopus', 'Octopus', 4, 4, NULL, 'gm', '0.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (125, NULL, 'SUBCAT-FVEG-VEG-MOLINERAL', 'Molinera Lentils', 'Molinera Lentils', 5, 5, NULL, 'gm', '0.08', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (126, NULL, 'SUBCAT-MEAT-PORK-PORKTEND', 'Pork tenderloin', 'Pork tenderloin', 2, 21, NULL, 'gm', '0.25', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (127, NULL, 'SUBCAT-MEAT-PORK-GROUNDPO', 'Ground Pork ', 'Ground Pork ', 2, 21, NULL, 'gm', '0.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (128, NULL, 'SUBCAT-CONDI-SPI-CAYENNEP', 'Cayenne powder', 'Cayenne powder', 10, 10, NULL, 'gm', '0.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (129, NULL, 'SUBCAT-FVEG-FRU-BANANA', 'Banana ', 'Banana ', 5, 9, NULL, 'pc', '6', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (130, NULL, 'SUBCAT-FVEG-FRU-RAISINS', 'Raisin 200grams', 'Raisin 200grams', 5, 9, NULL, 'gm', '0.23', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (131, NULL, 'SUBCAT-ALC-LIQ-PRIMERABRA', 'Primera brandy', 'Primera brandy', 6, 20, NULL, 'ml', '0.1', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (132, NULL, 'SUBCAT-MEAT-LAMB-LAMBLEG', 'Lamb leg', 'Lamb leg', 2, 23, NULL, 'gm', '0.37', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (133, NULL, 'SUBCAT-BAKE-PASTA-SPAGHET', 'Spaghetti (san remo)', 'Spaghetti (san remo)', 8, 22, NULL, 'gm', '0.06', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (134, NULL, 'SUBCAT-FATS-NUTS-CASHEW25', 'Cashew 250g', 'Cashew 250g', 3, 13, NULL, 'gm', '0.78', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (135, NULL, 'SUBCAT-BAKE-PASTA-PENNEPA', 'Penne ', 'Penne ', 8, 22, NULL, 'gm', '0.06', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (136, NULL, 'SUBCAT-MEAT-PROC-BACON', 'Bacon ', 'Bacon ', 2, 2, NULL, 'gm', '0.64', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (137, NULL, 'SUBCAT-FSEA-FISH-SALMONFR', 'Salmon fresh ', 'Salmon fresh ', 4, 17, NULL, 'gm', '0.49', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (138, NULL, 'SUBCAT-FVEG-VEG-ZUCHINI', 'Zucchini', 'Zucchini', 5, 5, NULL, 'gm', '0.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (139, NULL, 'SUBCAT-FVEG-VEG-ASPARAGUS', 'Asparagus', 'Asparagus', 5, 5, NULL, 'gm', '0.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (140, NULL, 'SUBCAT-FVEG-VEG-SQUASH', 'Squash', 'Squash', 5, 5, NULL, 'gm', '0.04', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (141, NULL, 'SUBCAT-FSEA-FISH-TUNALOIN', 'Tuna loin', 'Tuna loin', 4, 17, NULL, 'gm', '0.45', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (142, NULL, 'SUBCAT-MEAT-BEEF-RIBEYE25', 'Rib-eye 250g', 'Rib-eye 250g', 2, 19, NULL, 'gm', '1.1', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (143, NULL, 'SUBCAT-MEAT-BEEF-PREMIUMR', 'Premium rib eye 500grams', 'Premium rib eye 500grams', 2, 19, NULL, 'gm', '1', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (144, NULL, 'SUBCAT-CONDI-SAU-OYSTERSA', 'Oyster sauce ', 'Oyster sauce ', 10, 15, NULL, 'ml', '0.15', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (145, NULL, 'SUBCAT-CONDI-SAU-WORCESTE', 'Worcestershire sauce ', 'Worcestershire sauce ', 10, 15, NULL, 'ml', '0.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (146, NULL, 'SUBCAT-CONDI-SAU-LIQUIDSE', 'liquid seasoning', 'liquid seasoning', 10, 15, NULL, 'ml', '0.19', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (147, NULL, 'SUBCAT-MEAT-BEEF-BEEFTEND', 'Beef tenderloin ', 'Beef tenderloin ', 2, 19, NULL, 'gm', '0.92', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (148, NULL, 'SUBCAT-FSEA-FISH-SEADELIG', 'Sea delight', 'Sea delight', 4, 17, NULL, 'gm', '1.08', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (149, NULL, 'SUBCAT-FVEG-VEG-REDCHILI', 'Red chili (serpis)', 'Red chili (serpis)', 5, 5, NULL, 'gm', '0.24', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (150, NULL, 'SUBCAT-FVEG-VEG-WHITEBEAN', 'White beans (fabada beans)', 'White beans (fabada beans)', 5, 5, NULL, 'gm', '0.09', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (151, NULL, 'SUBCAT-FVEG-VEG-SQUASHPUM', 'Pumpkin ', 'Pumpkin ', 5, 5, NULL, 'gm', '0.04', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (152, NULL, 'SUBCAT-MIX-SAU-MAYANDICAD', 'Mayan dicaden chocolate', 'Mayan dicaden chocolate', 11, 11, NULL, 'gm', '0.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (153, NULL, 'SUBCAT-FVEG-VEG-GREENBEAN', 'Green beans', 'Green beans', 5, 5, NULL, 'gm', '0.12', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (154, NULL, 'SUBCAT-BAKE-BAK-PUFFPASTR', 'Puff pastry (baronia)', 'Puff pastry (baronia)', 8, 8, NULL, 'gm', '0.7', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (155, NULL, 'SUBCAT-DRY-CORNSTARCH', 'Corn starch', 'Corn starch', 9, 0, NULL, 'gm', '0.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (156, NULL, 'SUBCAT-FVEG-FRU-CALAMANSI', 'Calamansi ', 'Calamansi ', 5, 9, NULL, 'gm', '0.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (157, NULL, 'SUBCAT-MEAT-OX-OXTRIPE', 'Ox tripe', 'Ox tripe', 2, 24, NULL, 'gm', '0.24', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (158, NULL, 'SUBCAT-MEAT-OX-OXFEET', 'Ox feet ', 'Ox feet ', 2, 24, NULL, 'gm', '0.19', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (159, NULL, 'SUBCAT-MEAT-OX-OXFACE', 'Ox face', 'Ox face', 2, 24, NULL, 'gm', '0.2', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (160, NULL, 'SUBCAT-MIX-SAU-TOMATOPAST', 'Tomato paste', 'Tomato paste', 11, 11, NULL, 'gm', '0.13', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (161, NULL, 'SUBCAT-FVEG-VEG-CHICKPEAS', 'Molinera chick peas ', 'Molinera chick peas ', 5, 5, NULL, 'gm', '0.08', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (162, NULL, 'SUBCAT-MEAT-BEEF-BEEFBRIS', 'Beef brisket', 'Beef brisket', 2, 19, NULL, 'gm', '0.25', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (163, NULL, 'SUBCAT-MEAT-PROC-JAMON', 'Jamon serrano trimmings', 'Jamon serrano trimmings', 2, 2, NULL, 'gm', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (164, NULL, 'SUBCAT-MEAT-PORK-PORKFEET', 'Pork feet', 'Pork feet', 2, 21, NULL, 'gm', '0.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (165, NULL, 'SUBCAT-MIX-SAU-VEGETABLES', 'Vegetable stock', 'Vegetable stock', 11, 11, NULL, 'gm', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (166, NULL, 'SUBCAT-MIX-SAU-CHICKENSTO', 'Chicken stock', 'Chicken stock', 11, 11, NULL, 'ml', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (167, NULL, 'SUBCAT-CONDI-SPI-VANILLAE', 'Vanilla extract', 'Vanilla extract', 10, 10, NULL, 'ml', '1.51', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (168, NULL, 'SUBCAT-CONDI-SAU-CHILISAU', 'Chili sauce', 'Chili sauce', 10, 15, NULL, 'gm', '0.8', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (169, NULL, 'SUBCAT-DRY-BREADSTICK', 'Bread stick', 'Bread stick', 9, 0, NULL, 'gm', '0.19', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (170, NULL, 'SUBCAT-FVEG-FRU-DRIEDMANG', 'Dried Mango (7d cebu)', 'Dried Mango (7d cebu)', 5, 9, NULL, 'gm', '0.27', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (171, NULL, 'SUBCAT-ALC-WINE-PEDROXIME', 'Pedro ximenez nectar', 'Pedro ximenez nectar', 6, 6, NULL, 'ml', '0.84', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (172, NULL, 'SUBCAT-FSEA-SEA-CUTTLEFIS', 'CuttleFish', 'CuttleFish', 4, 4, NULL, 'gm', '0.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (173, NULL, 'SUBCAT-FSEA-SEA-SCALLOP', 'Scallop', 'Scallop', 4, 4, NULL, 'gm', '0.55', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (174, NULL, 'SUBCAT-FVEG-FRU-CHERRYTOM', 'Cherry tomato', 'Cherry tomato', 5, 9, NULL, 'gm', '0.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (175, NULL, 'SUBCAT-FVEG-VEG-BASIL', 'Basil', 'Basil', 5, 5, NULL, 'gm', '0.05', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (176, NULL, 'SUBCAT-FVEG-FRU-APPLERED', 'Apple ', 'Apple ', 5, 9, NULL, 'pc', '25', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (177, NULL, 'SUBCAT-FVEG-FRU-JOLLYMARA', 'Jolly Maraschino cherries w/ stem  72oz', 'Jolly Maraschino cherries w/ stem  72oz', 5, 9, NULL, 'gm', '0.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (178, NULL, 'SUBCAT-DRY-SALTROCK', 'Rock salt', 'Rock salt', 9, 0, NULL, 'gm', '0.02', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (179, NULL, 'SUBCAT-MEAT-PROC-LACON(SM', 'Lacon (smoked ham)', 'Lacon (smoked ham)', 2, 2, NULL, 'gm', '0.63', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (180, NULL, 'SUBCAT-CONDI-SPI-WHITEPEP', 'White pepper whole', 'White pepper whole', 10, 17, NULL, 'gm', '2.44', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (181, NULL, 'SUBCAT-FVEG-VEG-GHERKINS', 'Gherkins (barcino)', 'Gherkins (barcino)', 5, 5, NULL, 'gm', '0.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (182, NULL, 'SUBCAT-CONDI-SAU-WASABI', 'keiseki wasabi ', 'keiseki wasabi ', 10, 15, NULL, 'gm', '0.31', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (183, NULL, 'SUBCAT-MEAT-PORK-PORKSFAT', 'Pork\'s fat', 'Pork\'s fat', 2, 21, NULL, 'gm', '0.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (184, NULL, 'SUBCAT-MEAT-POUL-QUAILEGG', 'Quail egg', 'Quail egg', 2, 7, NULL, 'pc', '2', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (185, NULL, 'SUBCAT-FVEG-VEG-SPRINGONI', 'Spring onion', 'Spring onion', 5, 5, NULL, 'gm', '0.25', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (186, NULL, 'SUBCAT-MIX-SAU-MEATSTOCK', 'Meat stock', 'Meat stock', 11, 11, NULL, 'ml', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (187, NULL, 'SUBCAT-MIX-SAU-FISHSTOCK', 'Chicken stock', 'Chicken stock', 11, 11, NULL, 'ml', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (188, NULL, 'SUBCAT-ALC-WINE-BaigorriC', 'BaigorriCrianza', 'BaigorriCrianza', 6, 25, NULL, 'bottle', '837.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (189, NULL, 'SUBCAT-ALC-WINE-BaigorriR', 'BaigorriReserva', 'BaigorriReserva', 6, 25, NULL, 'bottle', '1341.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (190, NULL, 'SUBCAT-ALC-WINE-ArrabalCa', 'ArrabalCabernet', 'ArrabalCabernet', 6, 25, NULL, 'bottle', '282.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (191, NULL, 'SUBCAT-ALC-WINE-VBianchiC', 'VBianchiCabernet', 'VBianchiCabernet', 6, 25, NULL, 'bottle', '426.78', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (192, NULL, 'SUBCAT-ALC-WINE-NewAgeWhi', 'NewAgeWhite', 'NewAgeWhite', 6, 25, NULL, 'bottle', '321.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (193, NULL, 'SUBCAT-ALC-WINE-ElsaChard', 'ElsaChardonay', 'ElsaChardonay', 6, 25, NULL, 'bottle', '338', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (194, NULL, 'SUBCAT-ALC-WINE-ArabalChe', 'ArabalCheninSem', 'ArabalCheninSem', 6, 25, NULL, 'bottle', '282.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (195, NULL, 'SUBCAT-ALC-WINE-VBianchiS', 'VBianchiSauvignon', 'VBianchiSauvignon', 6, 25, NULL, 'bottle', '374', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (196, NULL, 'SUBCAT-ALC-WINE-AltosCuco', 'AltosCucoCoupage', 'AltosCucoCoupage', 6, 25, NULL, 'bottle', '291.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (197, NULL, 'SUBCAT-ALC-WINE-AltosCuco', 'AltosCucoJoven', 'AltosCucoJoven', 6, 25, NULL, 'bottle', '327', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (198, NULL, 'SUBCAT-ALC-WINE-AltosCuco', 'AltosCucoViognier', 'AltosCucoViognier', 6, 25, NULL, 'bottle', '333.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (199, NULL, 'SUBCAT-ALC-WINE-DominoEsp', 'DominoEspinalTinto', 'DominoEspinalTinto', 6, 25, NULL, 'bottle', '280', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (200, NULL, 'SUBCAT-ALC-WINE-CastanoTi', 'CastanoTintoMonastrel', 'CastanoTintoMonastrel', 6, 25, NULL, 'bottle', '291.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (201, NULL, 'SUBCAT-ALC-WINE-CastanoCe', 'CastanoCepasViejas', 'CastanoCepasViejas', 6, 25, NULL, 'bottle', '891', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (202, NULL, 'SUBCAT-ALC-WINE-CastanoDu', 'CastanoDulce', 'CastanoDulce', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (203, NULL, 'SUBCAT-ALC-WINE-Hecula', 'Hecula', 'Hecula', 6, 25, NULL, 'bottle', '417.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (204, NULL, 'SUBCAT-ALC-WINE-DominoEsp', 'DominoEspinalBlanco', 'DominoEspinalBlanco', 6, 25, NULL, 'bottle', '250', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (205, NULL, 'SUBCAT-ALC-WINE-DominoEsp', 'DominoEspinalRose', 'DominoEspinalRose', 6, 25, NULL, 'bottle', '280', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (206, NULL, 'SUBCAT-ALC-WINE-Montepulc', 'MontepulcianoD\'Abruzzo', 'MontepulcianoD\'Abruzzo', 6, 25, NULL, 'bottle', '374', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (207, NULL, 'SUBCAT-ALC-WINE-RossoDiPu', 'RossoDiPuglia', 'RossoDiPuglia', 6, 25, NULL, 'bottle', '374', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (208, NULL, 'SUBCAT-ALC-WINE-SyrahDeSi', 'SyrahDeSicilia', 'SyrahDeSicilia', 6, 25, NULL, 'bottle', '333.92', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (209, NULL, 'SUBCAT-ALC-WINE-NeroDiAvo', 'NeroDiAvola', 'NeroDiAvola', 6, 25, NULL, 'bottle', '374', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (210, NULL, 'SUBCAT-ALC-WINE-DelPalioS', 'DelPalioSangiovese', 'DelPalioSangiovese', 6, 25, NULL, 'bottle', '562', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (211, NULL, 'SUBCAT-ALC-WINE-DelPalioP', 'DelPalioPrimitivo', 'DelPalioPrimitivo', 6, 25, NULL, 'bottle', '562', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (212, NULL, 'SUBCAT-ALC-WINE-DelPalioM', 'DelPalioMerlot', 'DelPalioMerlot', 6, 25, NULL, 'bottle', '562', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (213, NULL, 'SUBCAT-ALC-WINE-MestaTint', 'MestaTintotempranillo', 'MestaTintotempranillo', 6, 25, NULL, 'bottle', '291.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (214, NULL, 'SUBCAT-ALC-WINE-MestaSele', 'MestaSeleccion', 'MestaSeleccion', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (215, NULL, 'SUBCAT-ALC-WINE-FontalRob', 'FontalRoble', 'FontalRoble', 6, 25, NULL, 'bottle', '417.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (216, NULL, 'SUBCAT-ALC-WINE-FontalCri', 'FontalCrianza', 'FontalCrianza', 6, 25, NULL, 'bottle', '609', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (217, NULL, 'SUBCAT-ALC-WINE-Esenciade', 'EsenciadeFontana', 'EsenciadeFontana', 6, 25, NULL, 'bottle', '703', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (218, NULL, 'SUBCAT-ALC-WINE-Quercus', 'Quercus', 'Quercus', 6, 25, NULL, 'bottle', '2348', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (219, NULL, 'SUBCAT-ALC-WINE-Dueto', 'Dueto', 'Dueto', 6, 25, NULL, 'bottle', '2348', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (220, NULL, 'SUBCAT-ALC-WINE-MestaBlan', 'MestaBlanco', 'MestaBlanco', 6, 25, NULL, 'bottle', '291.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (221, NULL, 'SUBCAT-ALC-WINE-FontalBla', 'FontalBlanco', 'FontalBlanco', 6, 25, NULL, 'bottle', '501.78', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (222, NULL, 'SUBCAT-ALC-WINE-CondedeSi', 'CondedeSiruelaJoven', 'CondedeSiruelaJoven', 6, 25, NULL, 'bottle', '333.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (223, NULL, 'SUBCAT-ALC-WINE-CondedeSi', 'CondedeSiruelaRoble', 'CondedeSiruelaRoble', 6, 25, NULL, 'bottle', '458', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (224, NULL, 'SUBCAT-ALC-WINE-CondedSir', 'CondedSiruelaCrianza', 'CondedSiruelaCrianza', 6, 25, NULL, 'bottle', '792.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (225, NULL, 'SUBCAT-ALC-WINE-CondedSir', 'CondedSiruelaReserva', 'CondedSiruelaReserva', 6, 25, NULL, 'bottle', '1257.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (226, NULL, 'SUBCAT-ALC-WINE-CondedeSi', 'CondedeSiruelaElite', 'CondedeSiruelaElite', 6, 25, NULL, 'bottle', '1878', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (227, NULL, 'SUBCAT-ALC-WINE-MuruveJov', 'MuruveJoven', 'MuruveJoven', 6, 25, NULL, 'bottle', '374', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (228, NULL, 'SUBCAT-ALC-WINE-MuruveRob', 'MuruveRoble', 'MuruveRoble', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (229, NULL, 'SUBCAT-ALC-WINE-MuruveCri', 'MuruveCrianza', 'MuruveCrianza', 6, 25, NULL, 'bottle', '543.75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (230, NULL, 'SUBCAT-ALC-WINE-MuruveRes', 'MuruveReserva', 'MuruveReserva', 6, 25, NULL, 'bottle', '844', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (231, NULL, 'SUBCAT-ALC-WINE-MuruveEli', 'MuruveElite', 'MuruveElite', 6, 25, NULL, 'bottle', '1457', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (232, NULL, 'SUBCAT-ALC-WINE-MariadeMo', 'MariadeMolinaverdejo', 'MariadeMolinaverdejo', 6, 25, NULL, 'bottle', '333.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (233, NULL, 'SUBCAT-ALC-WINE-MariadeMo', 'MariadeMolinaRueda', 'MariadeMolinaRueda', 6, 25, NULL, 'bottle', '291.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (234, NULL, 'SUBCAT-ALC-WINE-IracheCas', 'IracheCastilloJoven', 'IracheCastilloJoven', 6, 25, NULL, 'bottle', '333.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (235, NULL, 'SUBCAT-ALC-WINE-IracheCri', 'IracheCrianza', 'IracheCrianza', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (236, NULL, 'SUBCAT-ALC-WINE-IracheRes', 'IracheReseva', 'IracheReseva', 6, 25, NULL, 'bottle', '618.75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (237, NULL, 'SUBCAT-ALC-WINE-IracheGra', 'IracheGranReserva', 'IracheGranReserva', 6, 25, NULL, 'bottle', '841', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (238, NULL, 'SUBCAT-ALC-WINE-PortalTin', 'PortalTintoSCrianza', 'PortalTintoSCrianza', 6, 25, NULL, 'bottle', '693', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (239, NULL, 'SUBCAT-ALC-WINE-SacraNatu', 'SacraNaturaOrganic', 'SacraNaturaOrganic', 6, 25, NULL, 'bottle', '834', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (240, NULL, 'SUBCAT-ALC-WINE-L\'AviAruf', 'L\'AviArufi', 'L\'AviArufi', 6, 25, NULL, 'bottle', '1311', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (241, NULL, 'SUBCAT-ALC-WINE-MatherTer', 'MatherTeresina', 'MatherTeresina', 6, 25, NULL, 'bottle', '1737', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (242, NULL, 'SUBCAT-ALC-WINE-RaigdeRai', 'RaigdeRaimBlanco', 'RaigdeRaimBlanco', 6, 25, NULL, 'bottle', '418', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (243, NULL, 'SUBCAT-ALC-WINE-PortalBla', 'PortalBlanco', 'PortalBlanco', 6, 25, NULL, 'bottle', '517', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (244, NULL, 'SUBCAT-ALC-WINE-DVEmeritv', 'DVEmeritvs', 'DVEmeritvs', 6, 25, NULL, 'bottle', '3130', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (245, NULL, 'SUBCAT-ALC-WINE-CondedeSa', 'CondedeSanCristobal', 'CondedeSanCristobal', 6, 25, NULL, 'bottle', '1405', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (246, NULL, 'SUBCAT-ALC-WINE-PagoVendi', 'PagoVendimia', 'PagoVendimia', 6, 25, NULL, 'bottle', '534.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (247, NULL, 'SUBCAT-ALC-WINE-PenafielJ', 'PenafielJovenroble', 'PenafielJovenroble', 6, 25, NULL, 'bottle', '459.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (248, NULL, 'SUBCAT-ALC-WINE-PenafielC', 'PenafielCrianza', 'PenafielCrianza', 6, 25, NULL, 'bottle', '964.88', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (249, NULL, 'SUBCAT-ALC-WINE-PenafielR', 'PenafielReserva', 'PenafielReserva', 6, 25, NULL, 'bottle', '1622', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (250, NULL, 'SUBCAT-ALC-WINE-PagosPena', 'PagosPenafielVendimia', 'PagosPenafielVendimia', 6, 25, NULL, 'bottle', '2233', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (251, NULL, 'SUBCAT-ALC-WINE-PoratlVin', 'PoratlVintagePort', 'PoratlVintagePort', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (252, NULL, 'SUBCAT-ALC-WINE-Duradero', 'Duradero', 'Duradero', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (253, NULL, 'SUBCAT-ALC-WINE-Villamura', 'VillamuraBardolino', 'VillamuraBardolino', 6, 25, NULL, 'bottle', '371.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (254, NULL, 'SUBCAT-ALC-WINE-Villamura', 'VillamuraValpolicella', 'VillamuraValpolicella', 6, 25, NULL, 'bottle', '405.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (255, NULL, 'SUBCAT-ALC-WINE-CasaAntig', 'CasaAntiguaCabernet', 'CasaAntiguaCabernet', 6, 25, NULL, 'bottle', '318', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (256, NULL, 'SUBCAT-ALC-WINE-CasaAntig', 'CasaAntiguaCarmenere', 'CasaAntiguaCarmenere', 6, 25, NULL, 'bottle', '283.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (257, NULL, 'SUBCAT-ALC-WINE-ArteNoble', 'ArteNobleCarmenere', 'ArteNobleCarmenere', 6, 25, NULL, 'bottle', '389', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (258, NULL, 'SUBCAT-ALC-WINE-PuertoVie', 'PuertoViejoMalbec', 'PuertoViejoMalbec', 6, 25, NULL, 'bottle', '447', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (259, NULL, 'SUBCAT-ALC-WINE-PuertoVie', 'PuertoViejoCarmenere', 'PuertoViejoCarmenere', 6, 25, NULL, 'bottle', '447', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (260, NULL, 'SUBCAT-ALC-WINE-PuertoVej', 'PuertoVejoSyrah', 'PuertoVejoSyrah', 6, 25, NULL, 'bottle', '447', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (261, NULL, 'SUBCAT-ALC-WINE-ArteNoble', 'ArteNobleChardonay', 'ArteNobleChardonay', 6, 25, NULL, 'bottle', '389', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (262, NULL, 'SUBCAT-ALC-WINE-Puertovie', 'Puertoviejochardonay', 'Puertoviejochardonay', 6, 25, NULL, 'bottle', '447', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (263, NULL, 'SUBCAT-ALC-WINE-PuertoVie', 'PuertoViejoSauvignon', 'PuertoViejoSauvignon', 6, 25, NULL, 'bottle', '447', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (264, NULL, 'SUBCAT-ALC-WINE-ToroPiedr', 'ToroPiedraLateharv', 'ToroPiedraLateharv', 6, 25, NULL, 'bottle', '353', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (265, NULL, 'SUBCAT-ALC-WINE-AngusCabS', 'AngusCabSauv', 'AngusCabSauv', 6, 25, NULL, 'bottle', '440.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (266, NULL, 'SUBCAT-ALC-WINE-AngusMalb', 'AngusMalbec', 'AngusMalbec', 6, 25, NULL, 'bottle', '440.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (267, NULL, 'SUBCAT-ALC-WINE-AngusSyra', 'AngusSyrah', 'AngusSyrah', 6, 25, NULL, 'bottle', '440.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (268, NULL, 'SUBCAT-ALC-WINE-AngusCent', 'AngusCentCabSauv', 'AngusCentCabSauv', 6, 25, NULL, 'bottle', '569.64', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (269, NULL, 'SUBCAT-ALC-WINE-AngusCent', 'AngusCentMalbec', 'AngusCentMalbec', 6, 25, NULL, 'bottle', '569.64', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (270, NULL, 'SUBCAT-ALC-WINE-AngusChar', 'AngusCharOak', 'AngusCharOak', 6, 25, NULL, 'bottle', '440.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (271, NULL, 'SUBCAT-ALC-WINE-AngusCent', 'AngusCentChar', 'AngusCentChar', 6, 25, NULL, 'bottle', '569.64', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (272, NULL, 'SUBCAT-ALC-WINE-BDLGranRe', 'BDLGranReserva', 'BDLGranReserva', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (273, NULL, 'SUBCAT-ALC-WINE-BDLReserv', 'BDLReserva', 'BDLReserva', 6, 25, NULL, 'bottle', '844.64', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (274, NULL, 'SUBCAT-ALC-WINE-FincaMona', 'FincaMonasterio', 'FincaMonasterio', 6, 25, NULL, 'bottle', '1441.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (275, NULL, 'SUBCAT-ALC-BEER-Especial1', 'Especial1906', 'Especial1906', 6, 26, NULL, 'bottle', '54.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (276, NULL, 'SUBCAT-ALC-BEER-EstrellaG', 'EstrellaGalicia', 'EstrellaGalicia', 6, 26, NULL, 'bottle', '52.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (277, NULL, 'SUBCAT-ALC-BEER-EstrellaG', 'EstrellaGaliciaLight', 'EstrellaGaliciaLight', 6, 26, NULL, 'bottle', '52.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (278, NULL, 'SUBCAT-ALC-BEER-EstrellaG', 'EstrellaGaliciaRiver', 'EstrellaGaliciaRiver', 6, 26, NULL, 'bottle', '54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (279, NULL, 'SUBCAT-ALC-BEER-Heineken', 'Heineken', 'Heineken', 6, 26, NULL, 'bottle', '58.04', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (280, NULL, 'SUBCAT-ALC-BEER-LereleSan', 'LereleSangria', 'LereleSangria', 6, 26, NULL, 'bottle', '150.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (281, NULL, 'SUBCAT-ALC-BEER-LerelaTin', 'LerelaTintoVerano', 'LerelaTintoVerano', 6, 26, NULL, 'bottle', '67.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (282, NULL, 'SUBCAT-ALC-BEER-SMBpremiu', 'SMBpremium', 'SMBpremium', 6, 26, NULL, 'bottle', '37.91', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (283, NULL, 'SUBCAT-ALC-BEER-MaelocDul', 'MaelocDulceSw20cl', 'MaelocDulceSw20cl', 6, 26, NULL, 'bottle', '71.42', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (284, NULL, 'SUBCAT-ALC-BEER-MaelocMor', 'MaelocMoraBb20cl', 'MaelocMoraBb20cl', 6, 26, NULL, 'bottle', '75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (285, NULL, 'SUBCAT-ALC-BEER-MaelocFre', 'MaelocFresaSb20cl', 'MaelocFresaSb20cl', 6, 26, NULL, 'bottle', '71.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (286, NULL, 'SUBCAT-ALC-BEER-MaelocPer', 'MaelocPeraPear20cl', 'MaelocPeraPear20cl', 6, 26, NULL, 'bottle', '71.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (287, NULL, 'SUBCAT-ALC-BEER-MaelocDul', 'MaelocDulceSw33cl', 'MaelocDulceSw33cl', 6, 26, NULL, 'bottle', '75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (288, NULL, 'SUBCAT-ALC-BEER-MaelocSec', 'MaelocSeca33cl', 'MaelocSeca33cl', 6, 26, NULL, 'bottle', '75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (289, NULL, 'SUBCAT-ALC-BEER-MaelocPer', 'MaelocPeraPear33cl', 'MaelocPeraPear33cl', 6, 26, NULL, 'bottle', '75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (290, NULL, 'SUBCAT-ALC-BEER-MaelocMor', 'MaelocMoraBb33cl', 'MaelocMoraBb33cl', 6, 26, NULL, 'bottle', '75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (291, NULL, 'SUBCAT-ALC-BEER-MaelocFre', 'MaelocFresaSb33cl', 'MaelocFresaSb33cl', 6, 26, NULL, 'bottle', '75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (292, NULL, 'SUBCAT-ALC-BEER-MaelocSid', 'MaelocSidra70cl', 'MaelocSidra70cl', 6, 26, NULL, 'bottle', '191.97', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (293, NULL, 'SUBCAT-ALC-BEER-LaTitaTin', 'LaTitaTintoVerano', 'LaTitaTintoVerano', 6, 26, NULL, 'bottle', '72', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (294, NULL, 'SUBCAT-ALC-BEER-EstrellaG', 'EstrellaGaliciaPilsen', 'EstrellaGaliciaPilsen', 6, 26, NULL, 'bottle', '54.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (295, NULL, 'SUBCAT-ALC-BEER-RedVintag', 'RedVintage', 'RedVintage', 6, 26, NULL, 'bottle', '57.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (296, NULL, 'SUBCAT-ALC-BEER-BlackCoup', 'BlackCoupage', 'BlackCoupage', 6, 26, NULL, 'bottle', '54.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (297, NULL, 'SUBCAT-ALC-BEER-EgRegCan5', 'EgRegCan50cl', 'EgRegCan50cl', 6, 26, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (298, NULL, 'SUBCAT-ALC-WINE-HonoroVer', 'HonoroVeraMonastrell', 'HonoroVeraMonastrell', 6, 25, NULL, 'bottle', '372.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (299, NULL, 'SUBCAT-ALC-WINE-ComolocoM', 'ComolocoMonastrel', 'ComolocoMonastrel', 6, 25, NULL, 'bottle', '372.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (300, NULL, 'SUBCAT-ALC-WINE-HonoroVer', 'HonoroVeraGarnacha', 'HonoroVeraGarnacha', 6, 25, NULL, 'bottle', '436.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (301, NULL, 'SUBCAT-ALC-WINE-JuanGil12', 'JuanGil12', 'JuanGil12', 6, 25, NULL, 'bottle', '755.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (302, NULL, 'SUBCAT-ALC-WINE-Alaya2013', 'Alaya2013', 'Alaya2013', 6, 25, NULL, 'bottle', '1176.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (303, NULL, 'SUBCAT-ALC-WINE-Clio2013', 'Clio2013', 'Clio2013', 6, 25, NULL, 'bottle', '1745.54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (304, NULL, 'SUBCAT-ALC-WINE-Melior', 'Melior', 'Melior', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (305, NULL, 'SUBCAT-ALC-WINE-Matarrome', 'MatarromeraCrianza', 'MatarromeraCrianza', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (306, NULL, 'SUBCAT-ALC-WINE-Matarrome', 'MatarromeraReserva', 'MatarromeraReserva', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (307, NULL, 'SUBCAT-ALC-WINE-Matarrome', 'MatarromeraGranReserva', 'MatarromeraGranReserva', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (308, NULL, 'SUBCAT-ALC-WINE-MeliorVer', 'MeliorVerdejo', 'MeliorVerdejo', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (309, NULL, 'SUBCAT-ALC-WINE-BarondeFi', 'BarondeFilarCrianza', 'BarondeFilarCrianza', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (310, NULL, 'SUBCAT-ALC-WINE-BarondeFi', 'BarondeFilarReserva', 'BarondeFilarReserva', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (311, NULL, 'SUBCAT-ALC-WINE-BarondeFi', 'BarondeFilarRoble', 'BarondeFilarRoble', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (312, NULL, 'SUBCAT-ALC-WINE-BarondeFi', 'BarondeFilarVerdejo', 'BarondeFilarVerdejo', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (313, NULL, 'SUBCAT-ALC-WINE-BorsaoTJo', 'BorsaoTJoven', 'BorsaoTJoven', 6, 25, NULL, 'bottle', '226.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (314, NULL, 'SUBCAT-ALC-WINE-BorsaoCri', 'BorsaoCrianza', 'BorsaoCrianza', 6, 25, NULL, 'bottle', '451.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (315, NULL, 'SUBCAT-ALC-WINE-BorsaoJov', 'BorsaoJovenSeleccion', 'BorsaoJovenSeleccion', 6, 25, NULL, 'bottle', '352.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (316, NULL, 'SUBCAT-ALC-WINE-BorsaoBol', 'BorsaoBole', 'BorsaoBole', 6, 25, NULL, 'bottle', '531.25', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (317, NULL, 'SUBCAT-ALC-WINE-BorsaoTre', 'BorsaoTresPicos', 'BorsaoTresPicos', 6, 25, NULL, 'bottle', '755.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (318, NULL, 'SUBCAT-ALC-WINE-BorsaoBla', 'BorsaoBlanco', 'BorsaoBlanco', 6, 25, NULL, 'bottle', '226.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (319, NULL, 'SUBCAT-ALC-WINE-BorsaoRos', 'BorsaoRosado', 'BorsaoRosado', 6, 25, NULL, 'bottle', '226.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (320, NULL, 'SUBCAT-ALC-WINE-CapeHeigh', 'CapeHeightsCheninBlanc', 'CapeHeightsCheninBlanc', 6, 25, NULL, 'bottle', '322.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (321, NULL, 'SUBCAT-ALC-WINE-CapeHeigh', 'CapeHeightsShiraz', 'CapeHeightsShiraz', 6, 25, NULL, 'bottle', '322.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (322, NULL, 'SUBCAT-ALC-WINE-CapeHeigh', 'CapeHeightsMerlot', 'CapeHeightsMerlot', 6, 25, NULL, 'bottle', '322.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (323, NULL, 'SUBCAT-ALC-WINE-Percheron', 'PercheronGrenacheRose', 'PercheronGrenacheRose', 6, 25, NULL, 'bottle', '322.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (324, NULL, 'SUBCAT-ALC-WINE-Percheron', 'PercheronShiraz', 'PercheronShiraz', 6, 25, NULL, 'bottle', '322.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (325, NULL, 'SUBCAT-ALC-WINE-Percheron', 'PercheronOldVine', 'PercheronOldVine', 6, 25, NULL, 'bottle', '322.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (326, NULL, 'SUBCAT-ALC-WINE-PrimeCuts', 'PrimeCutsWhite', 'PrimeCutsWhite', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (327, NULL, 'SUBCAT-ALC-WINE-PrimeCuts', 'PrimeCutsRed', 'PrimeCutsRed', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (328, NULL, 'SUBCAT-ALC-WINE-Percheron', 'PercheronChen/Viog', 'PercheronChen/Viog', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (329, NULL, 'SUBCAT-ALC-WINE-CapeHeigh', 'CapeHeightsCabernet', 'CapeHeightsCabernet', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (330, NULL, 'SUBCAT-ALC-WINE-MokoBlack', 'MokoBlackPinotNoir', 'MokoBlackPinotNoir', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (331, NULL, 'SUBCAT-ALC-WINE-SoldiersB', 'SoldiersBlockMalbec', 'SoldiersBlockMalbec', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (332, NULL, 'SUBCAT-ALC-WINE-SoldiersB', 'SoldiersBlockShiraz', 'SoldiersBlockShiraz', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (333, NULL, 'SUBCAT-ALC-WINE-Listening', 'ListeningStationMalbec', 'ListeningStationMalbec', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (334, NULL, 'SUBCAT-ALC-WINE-CepagesCi', 'CepagesCinsaultGrenache', 'CepagesCinsaultGrenache', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (335, NULL, 'SUBCAT-ALC-WINE-ThreeFren', 'ThreeFrenchHensMerlot', 'ThreeFrenchHensMerlot', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (336, NULL, 'SUBCAT-ALC-WINE-USCabaret', 'USCabaretFrankNo.2Avi', 'USCabaretFrankNo.2Avi', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (337, NULL, 'SUBCAT-ALC-WINE-CloudFact', 'CloudFactoryPinotNoir', 'CloudFactoryPinotNoir', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (338, NULL, 'SUBCAT-ALC-WINE-MarktreeC', 'MarktreeCabernet/Merlot', 'MarktreeCabernet/Merlot', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (339, NULL, 'SUBCAT-ALC-WINE-MokoBlack', 'MokoBlackSauvignonBlan', 'MokoBlackSauvignonBlan', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (340, NULL, 'SUBCAT-ALC-WINE-CloudFact', 'CloudFactorySauvignonB', 'CloudFactorySauvignonB', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (341, NULL, 'SUBCAT-ALC-WINE-SoldiersB', 'SoldiersBlockChardonnay', 'SoldiersBlockChardonnay', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (342, NULL, 'SUBCAT-ALC-WINE-Listening', 'ListeningStationChardon', 'ListeningStationChardon', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (343, NULL, 'SUBCAT-ALC-WINE-CepagesOu', 'CepagesOubGrosMan/Colo', 'CepagesOubGrosMan/Colo', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (344, NULL, 'SUBCAT-ALC-WINE-CepagesOu', 'CepagesOubMars/Rous', 'CepagesOubMars/Rous', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (345, NULL, 'SUBCAT-ALC-WINE-ThreeFren', 'ThreeFrenchHensSauvign', 'ThreeFrenchHensSauvign', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (346, NULL, 'SUBCAT-NONALC-COFFEE-Cafe', 'CafeconLeche', 'CafeconLeche', 7, 27, NULL, 'cup', '33', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (347, NULL, 'SUBCAT-NONALC-COFFEE-Cafe', 'CafeconHielo', 'CafeconHielo', 7, 27, NULL, 'cup', '29.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (348, NULL, 'SUBCAT-NONALC-COFFEE-*Esp', '*Espresso*', '*Espresso*', 7, 27, NULL, 'cup', '29.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (349, NULL, 'SUBCAT-NONALC-COFFEE-Barc', 'BarcinoFrappe', 'BarcinoFrappe', 7, 27, NULL, 'cup', '34.15', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (350, NULL, 'SUBCAT-NONALC-COFFEE-Capp', 'Cappucino', 'Cappucino', 7, 27, NULL, 'cup', '34.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (351, NULL, 'SUBCAT-NONALC-COFFEE-DECA', 'DECAFCappucino', 'DECAFCappucino', 7, 27, NULL, 'cup', '34.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (352, NULL, 'SUBCAT-NONALC-COFFEE-DECA', 'DECAFCafecnleche', 'DECAFCafecnleche', 7, 27, NULL, 'cup', '33', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (353, NULL, 'SUBCAT-NONALC-COFFEE-DECA', 'DECAFEspresso', 'DECAFEspresso', 7, 27, NULL, 'cup', '29.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (354, NULL, 'SUBCAT-NONALC-COFFEE-Doub', 'DoubleEspresso', 'DoubleEspresso', 7, 27, NULL, 'cup', '58.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (355, NULL, 'SUBCAT-NONALC-COFFEE-Cafe', 'CafeSolo', 'CafeSolo', 7, 27, NULL, 'cup', '29.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (356, NULL, 'SUBCAT-NONALC-COFFEE-Cafe', 'CafeCortado', 'CafeCortado', 7, 27, NULL, 'cup', '30.38', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (357, NULL, 'SUBCAT-NONALC-COFFEE-Amer', 'Americano', 'Americano', 7, 27, NULL, 'cup', '29.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (358, NULL, 'SUBCAT-NONALC-COFFEE-Cafe', 'CafeBombon', 'CafeBombon', 7, 27, NULL, 'cup', '34.04', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (359, NULL, 'SUBCAT-NONALC-COFFEE-Cara', 'CarajilloSoberano', 'CarajilloSoberano', 7, 27, NULL, 'cup', '80.7', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (360, NULL, 'SUBCAT-NONALC-COFFEE-Cara', 'CarajilloWhisky', 'CarajilloWhisky', 7, 27, NULL, 'cup', '65.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (361, NULL, 'SUBCAT-NONALC-COFFEE-Cara', 'CarajilloRum', 'CarajilloRum', 7, 27, NULL, 'cup', '44.51', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (362, NULL, 'SUBCAT-NONALC-COFFEE-DECA', 'DECAFAmericano', 'DECAFAmericano', 7, 27, NULL, 'cup', '29.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (363, NULL, 'SUBCAT-NONALC-COFFEE-Cafe', 'CafeIrlandes', 'CafeIrlandes', 7, 27, NULL, 'cup', '75.15', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (364, NULL, 'SUBCAT-NONALC-COFFEE-Doub', 'DoubleEspressoDecaf', 'DoubleEspressoDecaf', 7, 27, NULL, 'cup', '58.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (365, NULL, 'SUBCAT-ALC-WINE-ClassicaC', 'ClassicaCabernetSauv', 'ClassicaCabernetSauv', 6, 25, NULL, 'bottle', '224.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (366, NULL, 'SUBCAT-ALC-WINE-FamigliaS', 'FamigliaSyrah', 'FamigliaSyrah', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (367, NULL, 'SUBCAT-ALC-WINE-FamigliaC', 'FamigliaCarmenere', 'FamigliaCarmenere', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (368, NULL, 'SUBCAT-ALC-WINE-FamigliaC', 'FamigliaCabernet', 'FamigliaCabernet', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (369, NULL, 'SUBCAT-ALC-WINE-ClassicoC', 'ClassicoChardonnay', 'ClassicoChardonnay', 6, 25, NULL, 'bottle', '224.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (370, NULL, 'SUBCAT-ALC-WINE-FamigliaS', 'FamigliaSauvignonBlanco', 'FamigliaSauvignonBlanco', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (371, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaGALA', 'ValformosaGALA', 6, 25, NULL, 'bottle', '1528', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (372, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaCARLA', 'ValformosaCARLA', 6, 25, NULL, 'bottle', '987', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (373, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaERIC', 'ValformosaERIC', 6, 25, NULL, 'bottle', '705', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (374, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaCHANTAL', 'ValformosaCHANTAL', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (375, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaBrutNature', 'ValformosaBrutNature', 6, 25, NULL, 'bottle', '588.4', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (376, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaBrut', 'ValformosaBrut', 6, 25, NULL, 'bottle', '664.29', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (377, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaSemi-Sec', 'ValformosaSemi-Sec', 6, 25, NULL, 'bottle', '589.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (378, NULL, 'SUBCAT-ALC-WINE-DomPotier', 'DomPotierCavaBrut', 'DomPotierCavaBrut', 6, 25, NULL, 'bottle', '645.54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (379, NULL, 'SUBCAT-ALC-WINE-DomPotier', 'DomPotierCavaDemiSec', 'DomPotierCavaDemiSec', 6, 25, NULL, 'bottle', '645.54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (380, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaBrutMagnum', 'ValformosaBrutMagnum', 6, 25, NULL, 'bottle', '1963', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (381, NULL, 'SUBCAT-ALC-WINE-MVSABrutN', 'MVSABrutNature', 'MVSABrutNature', 6, 25, NULL, 'bottle', '580.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (382, NULL, 'SUBCAT-ALC-WINE-MVSABrut', 'MVSABrut', 'MVSABrut', 6, 25, NULL, 'bottle', '580.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (383, NULL, 'SUBCAT-ALC-WINE-MVSASemi-', 'MVSASemi-Seco', 'MVSASemi-Seco', 6, 25, NULL, 'bottle', '580.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (384, NULL, 'SUBCAT-ALC-WINE-MVSABrutR', 'MVSABrutReserva', 'MVSABrutReserva', 6, 25, NULL, 'bottle', '709.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (385, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaBrutRose', 'ValformosaBrutRose', 6, 25, NULL, 'bottle', '664.29', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (386, NULL, 'SUBCAT-ALC-WINE-GonAlbert', 'GonAlbertdVilarnau', 'GonAlbertdVilarnau', 6, 25, NULL, 'bottle', '2321.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (387, NULL, 'SUBCAT-ALC-WINE-GonVilarn', 'GonVilarnauBrutNature', 'GonVilarnauBrutNature', 6, 25, NULL, 'bottle', '691.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (388, NULL, 'SUBCAT-ALC-WINE-GonVilarn', 'GonVilarnauBrut', 'GonVilarnauBrut', 6, 25, NULL, 'bottle', '691.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (389, NULL, 'SUBCAT-ALC-WINE-GonJeanPe', 'GonJeanPericoBrut', 'GonJeanPericoBrut', 6, 25, NULL, 'bottle', '620.54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (390, NULL, 'SUBCAT-ALC-WINE-GonJeanPe', 'GonJeanPericoDemisec', 'GonJeanPericoDemisec', 6, 25, NULL, 'bottle', '620.54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (391, NULL, 'SUBCAT-ALC-WINE-GonVilarn', 'GonVilarnauBMagnum', 'GonVilarnauBMagnum', 6, 25, NULL, 'bottle', '1875', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (392, NULL, 'SUBCAT-ALC-WINE-GonVilarn', 'GonVilarnauDemiSec', 'GonVilarnauDemiSec', 6, 25, NULL, 'bottle', '691.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (393, NULL, 'SUBCAT-ALC-WINE-GonVilarn', 'GonVilarnauRose', 'GonVilarnauRose', 6, 25, NULL, 'bottle', '675.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (394, NULL, 'SUBCAT-ALC-WINE-Mistingue', 'MistinguettCavaBrut', 'MistinguettCavaBrut', 6, 25, NULL, 'bottle', '721.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (395, NULL, 'SUBCAT-ALC-WINE-VinodeMis', 'VinodeMisa', 'VinodeMisa', 6, 25, NULL, 'bottle', '246.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (396, NULL, 'SUBCAT-ALC-WINE-Champagne', 'ChampagneCollet', 'ChampagneCollet', 6, 25, NULL, 'bottle', '1897.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (397, NULL, 'SUBCAT-ALC-WINE-Caipirina', 'Caipirina', 'Caipirina', 6, 25, NULL, 'glass', '27.19', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (398, NULL, 'SUBCAT-ALC-WINE-CubaLibre', 'CubaLibre', 'CubaLibre', 6, 25, NULL, 'glass', '34.33', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (399, NULL, 'SUBCAT-ALC-WINE-PinaColad', 'PinaColada', 'PinaColada', 6, 25, NULL, 'glass', '46.1', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (400, NULL, 'SUBCAT-ALC-WINE-Daiquiri', 'Daiquiri', 'Daiquiri', 6, 25, NULL, 'glass', '27.77', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (401, NULL, 'SUBCAT-ALC-WINE-WhiteRuss', 'WhiteRussian', 'WhiteRussian', 6, 25, NULL, 'glass', '55.56', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (402, NULL, 'SUBCAT-ALC-WINE-LongIslan', 'LongIslandIcedTea', 'LongIslandIcedTea', 6, 25, NULL, 'glass', '31.56', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (403, NULL, 'SUBCAT-ALC-WINE-DryMartin', 'DryMartini', 'DryMartini', 6, 25, NULL, 'glass', '33.92', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (404, NULL, 'SUBCAT-ALC-WINE-MintJulep', 'MintJulep', 'MintJulep', 6, 25, NULL, 'glass', '73.84', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (405, NULL, 'SUBCAT-ALC-WINE-EspressoM', 'EspressoMartini', 'EspressoMartini', 6, 25, NULL, 'glass', '67.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (406, NULL, 'SUBCAT-ALC-WINE-BalckRuss', 'BalckRussian', 'BalckRussian', 6, 25, NULL, 'glass', '54.83', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (407, NULL, 'SUBCAT-ALC-WINE-WhiteLady', 'WhiteLady', 'WhiteLady', 6, 25, NULL, 'glass', '46.41', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (408, NULL, 'SUBCAT-ALC-WINE-GinTonic', 'GinTonic', 'GinTonic', 6, 25, NULL, 'glass', '67.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (409, NULL, 'SUBCAT-ALC-WINE-TequilaSu', 'TequilaSunrise', 'TequilaSunrise', 6, 25, NULL, 'glass', '91.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (410, NULL, 'SUBCAT-ALC-WINE-Margarita', 'Margarita', 'Margarita', 6, 25, NULL, 'glass', '70.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (411, NULL, 'SUBCAT-ALC-WINE-FrozenMar', 'FrozenMargarita', 'FrozenMargarita', 6, 25, NULL, 'glass', '69.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (412, NULL, 'SUBCAT-ALC-WINE-Cosmopoli', 'Cosmopolitan', 'Cosmopolitan', 6, 25, NULL, 'glass', '56.47', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (413, NULL, 'SUBCAT-ALC-WINE-DryMartin', 'DryMartiniPremiumClass', 'DryMartiniPremiumClass', 6, 25, NULL, 'glass', '168.23', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (414, NULL, 'SUBCAT-ALC-WINE-LondonN1d', 'LondonN1drygin', 'LondonN1drygin', 6, 25, NULL, 'glass', '186.26', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (415, NULL, 'SUBCAT-ALC-WINE-SangriaBl', 'SangriaBlancaPitcher', 'SangriaBlancaPitcher', 6, 25, NULL, 'pitcher', '167.19', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (416, NULL, 'SUBCAT-ALC-WINE-SangriaRe', 'SangriaRedPitcher', 'SangriaRedPitcher', 6, 25, NULL, 'pitcher', '201.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (417, NULL, 'SUBCAT-ALC-WINE-SangriaGl', 'SangriaGlass', 'SangriaGlass', 6, 25, NULL, 'glass', '59.34', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (418, NULL, 'SUBCAT-ALC-WINE-Kalimotxo', 'Kalimotxo', 'Kalimotxo', 6, 25, NULL, 'glass', '159.63', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (419, NULL, 'SUBCAT-ALC-WINE-Tiojito', 'Tiojito', 'Tiojito', 6, 25, NULL, 'glass', '69.62', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (420, NULL, 'SUBCAT-ALC-WINE-Martinez', 'Martinez', 'Martinez', 6, 25, NULL, 'glass', '41.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (421, NULL, 'SUBCAT-ALC-WINE-Mimosa', 'Mimosa', 'Mimosa', 6, 25, NULL, 'glass', '112.05', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (422, NULL, 'SUBCAT-ALC-WINE-Hendricks', 'HendricksCucumber', 'HendricksCucumber', 6, 25, NULL, 'glass', '145.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (423, NULL, 'SUBCAT-ALC-WINE-LadySour', 'LadySour', 'LadySour', 6, 25, NULL, 'glass', '32.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (424, NULL, 'SUBCAT-ALC-WINE-SanFranci', 'SanFrancisco', 'SanFrancisco', 6, 25, NULL, 'glass', '65.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (425, NULL, 'SUBCAT-ALC-WINE-OldFashio', 'OldFashioned', 'OldFashioned', 6, 25, NULL, 'glass', '104.44', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (426, NULL, 'SUBCAT-ALC-WINE-ChivasCok', 'ChivasCoke', 'ChivasCoke', 6, 25, NULL, 'glass', '93.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (427, NULL, 'SUBCAT-ALC-WINE-AbsolutBl', 'AbsolutBlueTonic', 'AbsolutBlueTonic', 6, 25, NULL, 'glass', '76.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (428, NULL, 'SUBCAT-ALC-WINE-Greygoose', 'GreygooseTonic', 'GreygooseTonic', 6, 25, NULL, 'glass', '96.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (429, NULL, 'SUBCAT-ALC-WINE-LycheeMar', 'LycheeMartini', 'LycheeMartini', 6, 25, NULL, 'glass', '26.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (430, NULL, 'SUBCAT-ALC-WINE-SangriaBl', 'SangriaBlanca', 'SangriaBlanca', 6, 25, NULL, 'glass', '70.31', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (431, NULL, 'SUBCAT-ALC-WINE--CampariO', '-CampariOrange', '-CampariOrange', 6, 25, NULL, 'glass', '96.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (432, NULL, 'SUBCAT-ALC-WINE-AbsoluteB', 'AbsoluteBlueSprite', 'AbsoluteBlueSprite', 6, 25, NULL, 'glass', '57.41', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (433, NULL, 'SUBCAT-ALC-WINE-Greygoose', 'Greygoosesprite', 'Greygoosesprite', 6, 25, NULL, 'glass', '77.23', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (434, NULL, 'SUBCAT-ALC-WINE-AbsoluteK', 'AbsoluteKurantTonic', 'AbsoluteKurantTonic', 6, 25, NULL, 'glass', '76.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (435, NULL, 'SUBCAT-ALC-WINE-Strawberr', 'StrawberryMojito', 'StrawberryMojito', 6, 25, NULL, 'glass', '75.71', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (436, NULL, 'SUBCAT-ALC-WINE--CampariS', '-CampariSoda', '-CampariSoda', 6, 25, NULL, 'glass', '68.01', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (437, NULL, 'SUBCAT-ALC-WINE-Mojito', 'Mojito', 'Mojito', 6, 25, NULL, 'glass', '48.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (438, NULL, 'SUBCAT-ALC-WINE--JackDani', '-JackDanielsCoke', '-JackDanielsCoke', 6, 25, NULL, 'glass', '87.59', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (439, NULL, 'SUBCAT-ALC-WINE--Hendrick', '-HendricksGinTonic', '-HendricksGinTonic', 6, 25, NULL, 'glass', '157.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (440, NULL, 'SUBCAT-ALC-WINE-SweetMart', 'SweetMartini', 'SweetMartini', 6, 25, NULL, 'glass', '42.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (441, NULL, 'SUBCAT-ALC-WINE-AbsolutKu', 'AbsolutKurantSprite', 'AbsolutKurantSprite', 6, 25, NULL, 'glass', '57.41', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (442, NULL, 'SUBCAT-ALC-WINE-STRAWBERR', 'STRAWBERRYMOJINO', 'STRAWBERRYMOJINO', 6, 25, NULL, 'glass', '124.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (443, NULL, 'SUBCAT-ALC-WINE-PEARMARGA', 'PEARMARGARITA', 'PEARMARGARITA', 6, 25, NULL, 'glass', '114.34', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (444, NULL, 'SUBCAT-ALC-WINE-MAELOC\'SB', 'MAELOC\'SBRUMBLE', 'MAELOC\'SBRUMBLE', 6, 25, NULL, 'glass', '130.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (445, NULL, 'SUBCAT-ALC-WINE-HARDCIDER', 'HARDCIDERSANGRIA', 'HARDCIDERSANGRIA', 6, 25, NULL, 'glass', '129.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (446, NULL, 'SUBCAT-ALC-WINE-LONDONCOL', 'LONDONCOLLINS', 'LONDONCOLLINS', 6, 25, NULL, 'glass', '204.35', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (447, NULL, 'SUBCAT-ALC-WINE-LONDONBRO', 'LONDONBRONX', 'LONDONBRONX', 6, 25, NULL, 'glass', '143.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (448, NULL, 'SUBCAT-ALC-WINE-GIMLET', 'GIMLET', 'GIMLET', 6, 25, NULL, 'glass', '150.63', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (449, NULL, 'SUBCAT-ALC-WINE--BacardiC', '-BacardiCoke', '-BacardiCoke', 6, 25, NULL, 'glass', '36.16', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (450, NULL, 'SUBCAT-ALC-WINE--Capecod', '-Capecod', '-Capecod', 6, 25, NULL, 'glass', '42.64', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (451, NULL, 'SUBCAT-ALC-WINE--Smirnoff', '-SmirnoffTonic', '-SmirnoffTonic', 6, 25, NULL, 'glass', '69.64', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (452, NULL, 'SUBCAT-ALC-WINE--Smirnoff', '-SmirnoffSprite', '-SmirnoffSprite', 6, 25, NULL, 'glass', '50.45', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (453, NULL, 'SUBCAT-ALC-WINE-FirstPres', 'FirstPressCabSauv', 'FirstPressCabSauv', 6, 25, NULL, 'bottle', '1195.54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (454, NULL, 'SUBCAT-ALC-WINE-ElvisKing', 'ElvisKingCabSauv', 'ElvisKingCabSauv', 6, 25, NULL, 'bottle', '751.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (455, NULL, 'SUBCAT-ALC-WINE-StoneBarn', 'StoneBarnZinfandel', 'StoneBarnZinfandel', 6, 25, NULL, 'bottle', '550', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (456, NULL, 'SUBCAT-ALC-WINE-Woodhaven', 'WoodhavenMerlot', 'WoodhavenMerlot', 6, 25, NULL, 'bottle', '524.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (457, NULL, 'SUBCAT-ALC-WINE-Woodhaven', 'WoodhavenZinfandel', 'WoodhavenZinfandel', 6, 25, NULL, 'bottle', '525.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (458, NULL, 'SUBCAT-ALC-WINE-Woodhaven', 'WoodhavenCabSauv', 'WoodhavenCabSauv', 6, 25, NULL, 'bottle', '524.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (459, NULL, 'SUBCAT-ALC-WINE-FirstPres', 'FirstPressChar', 'FirstPressChar', 6, 25, NULL, 'bottle', '1060.71', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (460, NULL, 'SUBCAT-ALC-WINE-DominoMos', 'DominoMoscato', 'DominoMoscato', 6, 25, NULL, 'bottle', '588.39', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (461, NULL, 'SUBCAT-ALC-WINE-DominoPin', 'DominoPinGrigio', 'DominoPinGrigio', 6, 25, NULL, 'bottle', '588.39', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (462, NULL, 'SUBCAT-ALC-WINE-StoneBarn', 'StoneBarnWZinfandel', 'StoneBarnWZinfandel', 6, 25, NULL, 'bottle', '550', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (463, NULL, 'SUBCAT-ALC-WINE-GonTioPep', 'GonTioPepe', 'GonTioPepe', 6, 25, NULL, 'bottle', '586.6', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (464, NULL, 'SUBCAT-ALC-WINE-GonNectar', 'GonNectarPX', 'GonNectarPX', 6, 25, NULL, 'bottle', '602.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (465, NULL, 'SUBCAT-ALC-WINE-CremaCatl', 'CremaCatlnShot', 'CremaCatlnShot', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (466, NULL, 'SUBCAT-ALC-WINE-Apostoles', 'ApostolesCortado375ml', 'ApostolesCortado375ml', 6, 25, NULL, 'bottle', '1303.57', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (467, NULL, 'SUBCAT-ALC-WINE-QuenzaLic', 'QuenzaLicorCafe', 'QuenzaLicorCafe', 6, 25, NULL, 'bottle', '903', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (468, NULL, 'SUBCAT-ALC-WINE-QuenzaLic', 'QuenzaLicordeHierbas', 'QuenzaLicordeHierbas', 6, 25, NULL, 'bottle', '848.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (469, NULL, 'SUBCAT-ALC-WINE-QuenzaAgu', 'QuenzaAguardiente', 'QuenzaAguardiente', 6, 25, NULL, 'bottle', '903', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (470, NULL, 'SUBCAT-ALC-WINE-QuenzaCre', 'QuenzaCremaOrujo', 'QuenzaCremaOrujo', 6, 25, NULL, 'bottle', '864', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (471, NULL, 'SUBCAT-ALC-WINE-TawnyPort', 'TawnyPortoglass', 'TawnyPortoglass', 6, 25, NULL, 'glass', '281.25', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (472, NULL, 'SUBCAT-ALC-WINE-RubyPorto', 'RubyPortoglass', 'RubyPortoglass', 6, 25, NULL, 'glass', '281.25', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (473, NULL, 'SUBCAT-ALC-WINE-DinastiaV', 'DinastiaVivancoWhite', 'DinastiaVivancoWhite', 6, 25, NULL, 'bottle', '448.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (474, NULL, 'SUBCAT-ALC-WINE-DinastiaV', 'DinastiaVivancoCrianza', 'DinastiaVivancoCrianza', 6, 25, NULL, 'bottle', '669.64', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (475, NULL, 'SUBCAT-ALC-WINE-DinastiaV', 'DinastiaVivancoReserva', 'DinastiaVivancoReserva', 6, 25, NULL, 'bottle', '977.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (476, NULL, 'SUBCAT-NONALC-TEA-Unli-Ic', 'Unli-IcedTea', 'Unli-IcedTea', 7, 28, NULL, 'glass', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (477, NULL, 'SUBCAT-NONALC-TEA-Unli-Ic', 'Unli-IcedTea/C/RW/S', 'Unli-IcedTea/C/RW/S', 7, 28, NULL, 'glass', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (478, NULL, 'SUBCAT-ALC-WINE-ElCotoCri', 'ElCotoCrianza', 'ElCotoCrianza', 6, 25, NULL, 'bottle', '527.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (479, NULL, 'SUBCAT-ALC-WINE-ElCotoIma', 'ElCotoImazReserva', 'ElCotoImazReserva', 6, 25, NULL, 'bottle', '844.64', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (480, NULL, 'SUBCAT-ALC-WINE-ElCotoIma', 'ElCotoImazGranReserva', 'ElCotoImazGranReserva', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (481, NULL, 'SUBCAT-ALC-WINE-ElCotoBla', 'ElCotoBlanco', 'ElCotoBlanco', 6, 25, NULL, 'bottle', '719.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (482, NULL, 'SUBCAT-ALC-WINE-ElCotoRos', 'ElCotoRosado', 'ElCotoRosado', 6, 25, NULL, 'bottle', '372.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (483, NULL, 'SUBCAT-ALC-WINE-UtielRequ', 'UtielRequenaValConde', 'UtielRequenaValConde', 6, 25, NULL, 'bottle', '266.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (484, NULL, 'SUBCAT-ALC-WINE-MuseumRes', 'MuseumReserva', 'MuseumReserva', 6, 25, NULL, 'bottle', '982.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (485, NULL, 'SUBCAT-ALC-WINE-VineaCria', 'VineaCrianza', 'VineaCrianza', 6, 25, NULL, 'bottle', '699.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (486, NULL, 'SUBCAT-ALC-WINE-VineaRosa', 'VineaRosado', 'VineaRosado', 6, 25, NULL, 'bottle', '491.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (487, NULL, 'SUBCAT-ALC-WINE-AltozanoT', 'AltozanoTempranillo', 'AltozanoTempranillo', 6, 25, NULL, 'bottle', '284.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (488, NULL, 'SUBCAT-ALC-WINE-AltozanoS', 'AltozanoShiraz', 'AltozanoShiraz', 6, 25, NULL, 'bottle', '284.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (489, NULL, 'SUBCAT-ALC-WINE-AltozanoC', 'AltozanoCabernet', 'AltozanoCabernet', 6, 25, NULL, 'bottle', '284.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (490, NULL, 'SUBCAT-ALC-WINE-BeroniaCr', 'BeroniaCrianza', 'BeroniaCrianza', 6, 25, NULL, 'bottle', '474.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (491, NULL, 'SUBCAT-ALC-WINE-BeroniaRe', 'BeroniaReserva', 'BeroniaReserva', 6, 25, NULL, 'bottle', '721.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (492, NULL, 'SUBCAT-ALC-WINE-BeroniaGr', 'BeroniaGranReserva', 'BeroniaGranReserva', 6, 25, NULL, 'bottle', '1271.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (493, NULL, 'SUBCAT-ALC-WINE-BeroniaTe', 'BeroniaTempranillo', 'BeroniaTempranillo', 6, 25, NULL, 'bottle', '625.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (494, NULL, 'SUBCAT-ALC-WINE-BeroniaMa', 'BeroniaMazuela', 'BeroniaMazuela', 6, 25, NULL, 'bottle', '709.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (495, NULL, 'SUBCAT-ALC-WINE-BeroniaGr', 'BeroniaGraciano', 'BeroniaGraciano', 6, 25, NULL, 'bottle', '721.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (496, NULL, 'SUBCAT-ALC-WINE-BeroniaII', 'BeroniaIIIac', 'BeroniaIIIac', 6, 25, NULL, 'bottle', '2656.25', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (497, NULL, 'SUBCAT-ALC-WINE-FincaCons', 'FincaConstancia', 'FincaConstancia', 6, 25, NULL, 'bottle', '474.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (498, NULL, 'SUBCAT-ALC-WINE-FincaParc', 'FincaParcela', 'FincaParcela', 6, 25, NULL, 'bottle', '474.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (499, NULL, 'SUBCAT-ALC-WINE-Altosdela', 'AltosdelaFinca', 'AltosdelaFinca', 6, 25, NULL, 'bottle', '1066.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (500, NULL, 'SUBCAT-ALC-WINE-FincaMonc', 'FincaMoncloa', 'FincaMoncloa', 6, 25, NULL, 'bottle', '870.54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (501, NULL, 'SUBCAT-ALC-WINE-VDVTinto', 'VDVTinto', 'VDVTinto', 6, 25, NULL, 'bottle', '321.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (502, NULL, 'SUBCAT-ALC-WINE-VDVCrianz', 'VDVCrianza', 'VDVCrianza', 6, 25, NULL, 'bottle', '436.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (503, NULL, 'SUBCAT-ALC-WINE-VDVCabern', 'VDVCabernet', 'VDVCabernet', 6, 25, NULL, 'bottle', '721.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (504, NULL, 'SUBCAT-ALC-WINE-VDVMerlot', 'VDVMerlotColeccion', 'VDVMerlotColeccion', 6, 25, NULL, 'bottle', '705.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (505, NULL, 'SUBCAT-ALC-WINE-LaMiranda', 'LaMirandaSecastilla', 'LaMirandaSecastilla', 6, 25, NULL, 'bottle', '482.15', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (506, NULL, 'SUBCAT-ALC-WINE-Secastill', 'SecastillaColeccion', 'SecastillaColeccion', 6, 25, NULL, 'bottle', '1531', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (507, NULL, 'SUBCAT-ALC-WINE-BeroniaRe', 'BeroniaReserva1.5L', 'BeroniaReserva1.5L', 6, 25, NULL, 'bottle', '1897.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (508, NULL, 'SUBCAT-ALC-WINE-BeroniaRe', 'BeroniaReserva3L', 'BeroniaReserva3L', 6, 25, NULL, 'bottle', '4380', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (509, NULL, 'SUBCAT-ALC-WINE-VDVGranVO', 'VDVGranVOS', 'VDVGranVOS', 6, 25, NULL, 'bottle', '862.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (510, NULL, 'SUBCAT-ALC-WINE-Blecua200', 'Blecua2004', 'Blecua2004', 6, 25, NULL, 'bottle', '1718.75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (511, NULL, 'SUBCAT-ALC-WINE-BeroniaVi', 'BeroniaVinasViejas', 'BeroniaVinasViejas', 6, 25, NULL, 'bottle', '732.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (512, NULL, 'SUBCAT-ALC-WINE-BERONIACR', 'BERONIACRIANZA1.5L', 'BERONIACRIANZA1.5L', 6, 25, NULL, 'bottle', '1473.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (513, NULL, 'SUBCAT-ALC-WINE-AltozanoT', 'AltozanoTempSS', 'AltozanoTempSS', 6, 25, NULL, 'bottle', '284.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (514, NULL, 'SUBCAT-ALC-WINE-VDVLucesT', 'VDVLucesTinto', 'VDVLucesTinto', 6, 25, NULL, 'bottle', '266.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (515, NULL, 'SUBCAT-ALC-WINE-AltozanoB', 'AltozanoBlanco', 'AltozanoBlanco', 6, 25, NULL, 'bottle', '284.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (516, NULL, 'SUBCAT-ALC-WINE-AltozanoR', 'AltozanoRose', 'AltozanoRose', 6, 25, NULL, 'bottle', '284.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (517, NULL, 'SUBCAT-ALC-WINE-Beroniade', 'BeroniadeViura', 'BeroniadeViura', 6, 25, NULL, 'bottle', '470.54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (518, NULL, 'SUBCAT-ALC-WINE-BeroniaRo', 'BeroniaRose', 'BeroniaRose', 6, 25, NULL, 'bottle', '474.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (519, NULL, 'SUBCAT-ALC-WINE-VDVChardo', 'VDVChardonnaycollection', 'VDVChardonnaycollection', 6, 25, NULL, 'bottle', '436.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (520, NULL, 'SUBCAT-ALC-WINE-VDVMacabe', 'VDVMacabeoChardonnay', 'VDVMacabeoChardonnay', 6, 25, NULL, 'bottle', '322.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (521, NULL, 'SUBCAT-ALC-WINE-VDVGewurz', 'VDVGewurztraminer', 'VDVGewurztraminer', 6, 25, NULL, 'bottle', '777', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (522, NULL, 'SUBCAT-ALC-WINE-VDVRiesli', 'VDVRiesling', 'VDVRiesling', 6, 25, NULL, 'bottle', '436.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (523, NULL, 'SUBCAT-ALC-WINE-LaMiranda', 'LaMirandaGarnacha', 'LaMirandaGarnacha', 6, 25, NULL, 'bottle', '524.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (524, NULL, 'SUBCAT-ALC-WINE-Fragantia', 'FragantiaMoscato', 'FragantiaMoscato', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (525, NULL, 'SUBCAT-ALC-WINE-Fragantia', 'FragantiaSyrahRose', 'FragantiaSyrahRose', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (526, NULL, 'SUBCAT-ALC-WINE-VDVClario', 'VDVClarion', 'VDVClarion', 6, 25, NULL, 'bottle', '948.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (527, NULL, 'SUBCAT-ALC-WINE-AltozanoB', 'AltozanoBlancoSS', 'AltozanoBlancoSS', 6, 25, NULL, 'bottle', '284.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (528, NULL, 'SUBCAT-ALC-WINE-VDVLucesB', 'VDVLucesBlanco', 'VDVLucesBlanco', 6, 25, NULL, 'bottle', '296.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (529, NULL, 'SUBCAT-ALC-WINE-BeroniaVe', 'BeroniaVerdejo', 'BeroniaVerdejo', 6, 25, NULL, 'bottle', '446.42', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (530, NULL, 'SUBCAT-ALC-WINE-HerasCord', 'HerasCordonVendimia', 'HerasCordonVendimia', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (531, NULL, 'SUBCAT-ALC-WINE-HerasCord', 'HerasCordonReserva2010', 'HerasCordonReserva2010', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (532, NULL, 'SUBCAT-ALC-WINE-BlancodeB', 'BlancodeBlancos', 'BlancodeBlancos', 6, 25, NULL, 'bottle', '895', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (533, NULL, 'SUBCAT-ALC-WINE-PontedaBo', 'PontedaBogaGodello', 'PontedaBogaGodello', 6, 25, NULL, 'bottle', '675.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (534, NULL, 'SUBCAT-ALC-WINE-PontedaBo', 'PontedaBogaAlbarino', 'PontedaBogaAlbarino', 6, 25, NULL, 'bottle', '675.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (535, NULL, 'SUBCAT-ALC-WINE-LereleTin', 'LereleTintodeVerano', 'LereleTintodeVerano', 6, 25, NULL, 'bottle', '67.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (536, NULL, 'SUBCAT-ALC-WINE-PontedaBo', 'PontedaBogaMencia', 'PontedaBogaMencia', 6, 25, NULL, 'bottle', '603.57', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (537, NULL, 'SUBCAT-ALC-WINE-PonteLoma', 'PonteLomaTinto', 'PonteLomaTinto', 6, 25, NULL, 'bottle', '150.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (538, NULL, 'SUBCAT-NONALC-BEV-MangoJu', 'MangoJuice', 'MangoJuice', 7, 29, NULL, 'glass', '36.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (539, NULL, 'SUBCAT-NONALC-BEV-LemonJu', 'LemonJuice', 'LemonJuice', 7, 29, NULL, 'glass', '16.51', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (540, NULL, 'SUBCAT-NONALC-BEV-OrangeJ', 'OrangeJuice', 'OrangeJuice', 7, 29, NULL, 'glass', '50.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (541, NULL, 'SUBCAT-NONALC-BEV-Calaman', 'CalamansiJuice', 'CalamansiJuice', 7, 29, NULL, 'glass', '11.47', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (542, NULL, 'SUBCAT-NONALC-BEV-AppleJu', 'AppleJuice', 'AppleJuice', 7, 29, NULL, 'glass', '25.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (543, NULL, 'SUBCAT-NONALC-BEV-MelonJu', 'MelonJuice', 'MelonJuice', 7, 29, NULL, 'glass', '24.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (544, NULL, 'SUBCAT-NONALC-BEV-Waterme', 'WatermelonJuice', 'WatermelonJuice', 7, 29, NULL, 'glass', '21.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (545, NULL, 'SUBCAT-NONALC-BEV-MangoSh', 'MangoShake', 'MangoShake', 7, 29, NULL, 'glass', '36.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (546, NULL, 'SUBCAT-NONALC-BEV-LemonSh', 'LemonShake', 'LemonShake', 7, 29, NULL, 'glass', '16.51', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (547, NULL, 'SUBCAT-NONALC-BEV-OrangeS', 'OrangeShake', 'OrangeShake', 7, 29, NULL, 'glass', '50.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (548, NULL, 'SUBCAT-NONALC-BEV-Calaman', 'CalamansiShake', 'CalamansiShake', 7, 29, NULL, 'glass', '11.47', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (549, NULL, 'SUBCAT-NONALC-BEV-AppleSh', 'AppleShake', 'AppleShake', 7, 29, NULL, 'glass', '25.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (550, NULL, 'SUBCAT-NONALC-BEV-MelonSh', 'MelonShake', 'MelonShake', 7, 29, NULL, 'glass', '24.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (551, NULL, 'SUBCAT-NONALC-BEV-Waterme', 'WatermelonShake', 'WatermelonShake', 7, 29, NULL, 'glass', '21.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (552, NULL, 'SUBCAT-NONALC-BEV-Pineapp', 'PineappleJuice', 'PineappleJuice', 7, 29, NULL, 'glass', '21.38', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (553, NULL, 'SUBCAT-ALC-WINE-LanCrianz', 'LanCrianza75cl', 'LanCrianza75cl', 6, 25, NULL, 'bottle', '691.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (554, NULL, 'SUBCAT-ALC-WINE-LanCrianz', 'LanCrianza50cl', 'LanCrianza50cl', 6, 25, NULL, 'bottle', '513.39', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (555, NULL, 'SUBCAT-ALC-WINE-LanReserv', 'LanReserva75cl', 'LanReserva75cl', 6, 25, NULL, 'bottle', '949.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (556, NULL, 'SUBCAT-ALC-WINE-LanReserv', 'LanReserva50cl', 'LanReserva50cl', 6, 25, NULL, 'bottle', '635.71', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (557, NULL, 'SUBCAT-ALC-WINE-LanGranRe', 'LanGranReserva', 'LanGranReserva', 6, 25, NULL, 'bottle', '1406.25', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (558, NULL, 'SUBCAT-ALC-WINE-LanD12', 'LanD12', 'LanD12', 6, 25, NULL, 'bottle', '1176.84', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (559, NULL, 'SUBCAT-ALC-WINE-LanEdicio', 'LanEdicionLimitada', 'LanEdicionLimitada', 6, 25, NULL, 'bottle', '3750', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (560, NULL, 'SUBCAT-ALC-WINE-LanReserv', 'LanReserva1.5', 'LanReserva1.5', 6, 25, NULL, 'bottle', '2453.57', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (561, NULL, 'SUBCAT-ALC-WINE-Marquesde', 'MarquesdeBurgosCrianza', 'MarquesdeBurgosCrianza', 6, 25, NULL, 'bottle', '1191.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (562, NULL, 'SUBCAT-ALC-WINE-Marquesde', 'MarquesdeBurgosRoble', 'MarquesdeBurgosRoble', 6, 25, NULL, 'bottle', '870.54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (563, NULL, 'SUBCAT-ALC-WINE-LANCrianz', 'LANCrianza1.5L', 'LANCrianza1.5L', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (564, NULL, 'SUBCAT-ALC-WINE-LanZoe', 'LanZoe', 'LanZoe', 6, 25, NULL, 'bottle', '600', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (565, NULL, 'SUBCAT-ALC-WINE-Duquesade', 'DuquesadeValladolid', 'DuquesadeValladolid', 6, 25, NULL, 'bottle', '512.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (566, NULL, 'SUBCAT-ALC-LIQ-LepantoBra', 'LepantoBrandy', 'LepantoBrandy', 6, 30, NULL, 'bottle', '2039.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (567, NULL, 'SUBCAT-ALC-LIQ-SoberanoBr', 'SoberanoBrandy', 'SoberanoBrandy', 6, 30, NULL, 'bottle', '946.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (568, NULL, 'SUBCAT-ALC-LIQ-LondonGin', 'LondonGin', 'LondonGin', 6, 30, NULL, 'bottle', '2049.1', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (569, NULL, 'SUBCAT-ALC-LIQ-NavaraPach', 'NavaraPacharanMonjes', 'NavaraPacharanMonjes', 6, 30, NULL, 'bottle', '1175', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (570, NULL, 'SUBCAT-ALC-LIQ-Lakan', 'Lakan', 'Lakan', 6, 30, NULL, 'bottle', '2187.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (571, NULL, 'SUBCAT-ALC-LIQ-VCremaCata', 'VCremaCatalana', 'VCremaCatalana', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (572, NULL, 'SUBCAT-ALC-LIQ-GreygooseV', 'GreygooseVodkaBtl', 'GreygooseVodkaBtl', 6, 30, NULL, 'bottle', '736.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (573, NULL, 'SUBCAT-ALC-LIQ-JackDaniel', 'JackDanielsBtl', 'JackDanielsBtl', 6, 30, NULL, 'bottle', '866.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (574, NULL, 'SUBCAT-ALC-LIQ-TequilaRos', 'TequilaRoseBtl', 'TequilaRoseBtl', 6, 30, NULL, 'bottle', '458.33', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (575, NULL, 'SUBCAT-ALC-LIQ-BombayGin', 'BombayGin', 'BombayGin', 6, 30, NULL, 'bottle', '341.52', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (576, NULL, 'SUBCAT-ALC-LIQ-ChivasRega', 'ChivasRegal', 'ChivasRegal', 6, 30, NULL, 'bottle', '937.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (577, NULL, 'SUBCAT-ALC-LIQ-AbsolutVod', 'AbsolutVodkaKURANT', 'AbsolutVodkaKURANT', 6, 30, NULL, 'bottle', '892.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (578, NULL, 'SUBCAT-ALC-LIQ-JoseCuervo', 'JoseCuervoBtl', 'JoseCuervoBtl', 6, 30, NULL, 'bottle', '651.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (579, NULL, 'SUBCAT-ALC-LIQ-JohnnyWalk', 'JohnnyWalkerBtl', 'JohnnyWalkerBtl', 6, 30, NULL, 'bottle', '875', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (580, NULL, 'SUBCAT-ALC-LIQ-AbsoluteBl', 'AbsoluteBlueBtl', 'AbsoluteBlueBtl', 6, 30, NULL, 'bottle', '651.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (581, NULL, 'SUBCAT-ALC-LIQ-LondonGin4', 'LondonGin4.5L', 'LondonGin4.5L', 6, 30, NULL, 'bottle', '13839.29', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (582, NULL, 'SUBCAT-ALC-LIQ-PatronTequ', 'PatronTequilaBtl', 'PatronTequilaBtl', 6, 30, NULL, 'bottle', '1875', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (583, NULL, 'SUBCAT-ALC-LIQ-HavanaBlan', 'HavanaBlanco', 'HavanaBlanco', 6, 30, NULL, 'bottle', '760', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (584, NULL, 'SUBCAT-ALC-LIQ-BaileysBot', 'BaileysBot', 'BaileysBot', 6, 30, NULL, 'bottle', '714.29', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (585, NULL, 'SUBCAT-ALC-LIQ-MartinCoda', 'MartinCodaxAguardiente', 'MartinCodaxAguardiente', 6, 30, NULL, 'bottle', '1857', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (586, NULL, 'SUBCAT-ALC-LIQ-DruideVodk', 'DruideVodka', 'DruideVodka', 6, 30, NULL, 'bottle', '1752.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (587, NULL, 'SUBCAT-ALC-LIQ-MartiniRos', 'MartiniRosso', 'MartiniRosso', 6, 30, NULL, 'bottle', '450', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (588, NULL, 'SUBCAT-ALC-LIQ-MartiniBia', 'MartiniBianco', 'MartiniBianco', 6, 30, NULL, 'bottle', '401.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (589, NULL, 'SUBCAT-ALC-LIQ-Kahlua', 'Kahlua', 'Kahlua', 6, 30, NULL, 'bottle', '545.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (590, NULL, 'SUBCAT-ALC-LIQ-VDVNomad', 'VDVNomad', 'VDVNomad', 6, 30, NULL, 'bottle', '1513.39', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (591, NULL, 'SUBCAT-ALC-LIQ--Campari', '-Campari', '-Campari', 6, 30, NULL, 'bottle', '580.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (592, NULL, 'SUBCAT-ALC-LIQ--Hendricks', '-Hendricks', '-Hendricks', 6, 30, NULL, 'bottle', '1500', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (593, NULL, 'SUBCAT-ALC-LIQ--Bacardi', '-Bacardi', '-Bacardi', 6, 30, NULL, 'bottle', '223.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (594, NULL, 'SUBCAT-ALC-LIQ--Smirnoff', '-Smirnoff', '-Smirnoff', 6, 30, NULL, 'bottle', '401.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (595, NULL, 'SUBCAT-ALC-LIQ-VermutRojo', 'VermutRojo', 'VermutRojo', 6, 30, NULL, 'bottle', '417.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (596, NULL, 'SUBCAT-ALC-LIQ-VermutBlan', 'VermutBlanco', 'VermutBlanco', 6, 30, NULL, 'bottle', '417.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (597, NULL, 'SUBCAT-ALC-LIQ-LeonorPalo', 'LeonorPaloCortado', 'LeonorPaloCortado', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (598, NULL, 'SUBCAT-ALC-LIQ-AlfonsoOlo', 'AlfonsoOloroso', 'AlfonsoOloroso', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (599, NULL, 'SUBCAT-ALC-LIQ-Solera1847', 'Solera1847Cream', 'Solera1847Cream', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (600, NULL, 'SUBCAT-ALC-LIQ-ApostolesP', 'ApostolesPalo750ml', 'ApostolesPalo750ml', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (601, NULL, 'SUBCAT-ALC-LIQ-MatusalemC', 'MatusalemCream', 'MatusalemCream', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (602, NULL, 'SUBCAT-ALC-LIQ-NoePedroXi', 'NoePedroXimenez', 'NoePedroXimenez', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (603, NULL, 'SUBCAT-ALC-LIQ-GreygooseS', 'GreygooseShot', 'GreygooseShot', 6, 30, NULL, 'bottle', '58.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (604, NULL, 'SUBCAT-ALC-LIQ-LondonGynS', 'LondonGynShot', 'LondonGynShot', 6, 30, NULL, 'bottle', '163.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (605, NULL, 'SUBCAT-ALC-LIQ-Jhonnywalk', 'JhonnywalkerShot', 'JhonnywalkerShot', 6, 30, NULL, 'bottle', '52.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (606, NULL, 'SUBCAT-ALC-LIQ-LepantoSho', 'LepantoShot', 'LepantoShot', 6, 30, NULL, 'bottle', '203.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (607, NULL, 'SUBCAT-ALC-LIQ-AbsolutBlu', 'AbsolutBlueShot', 'AbsolutBlueShot', 6, 30, NULL, 'bottle', '39.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (608, NULL, 'SUBCAT-ALC-LIQ-HavanaShot', 'HavanaShot', 'HavanaShot', 6, 30, NULL, 'bottle', '45.6', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (609, NULL, 'SUBCAT-ALC-LIQ-ChivasShot', 'ChivasShot', 'ChivasShot', 6, 30, NULL, 'bottle', '75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (610, NULL, 'SUBCAT-ALC-LIQ-PatxaranSh', 'PatxaranShot', 'PatxaranShot', 6, 30, NULL, 'bottle', '94', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (611, NULL, 'SUBCAT-ALC-LIQ-AbsolutKur', 'AbsolutKurantShot', 'AbsolutKurantShot', 6, 30, NULL, 'bottle', '53.57', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (612, NULL, 'SUBCAT-ALC-LIQ-MartiniBia', 'MartiniBiancoShot', 'MartiniBiancoShot', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (613, NULL, 'SUBCAT-ALC-LIQ-SoberanoSh', 'SoberanoShot', 'SoberanoShot', 6, 30, NULL, 'bottle', '75.71', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (614, NULL, 'SUBCAT-ALC-LIQ-QuenzaLico', 'QuenzaLicorCafe', 'QuenzaLicorCafe', 6, 30, NULL, 'bottle', '903', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (615, NULL, 'SUBCAT-ALC-LIQ-MoscatelSh', 'MoscatelShot', 'MoscatelShot', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (616, NULL, 'SUBCAT-ALC-LIQ-QuenzaLico', 'QuenzaLicorHierbas', 'QuenzaLicorHierbas', 6, 30, NULL, 'bottle', '101.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (617, NULL, 'SUBCAT-ALC-LIQ-PatronShot', 'PatronShot', 'PatronShot', 6, 30, NULL, 'bottle', '150', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (618, NULL, 'SUBCAT-ALC-LIQ-NectarPXSh', 'NectarPXShot', 'NectarPXShot', 6, 30, NULL, 'bottle', '48.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (619, NULL, 'SUBCAT-ALC-LIQ-TequilaRos', 'TequilaRoseShot', 'TequilaRoseShot', 6, 30, NULL, 'bottle', '39.29', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (620, NULL, 'SUBCAT-ALC-LIQ-JackDaniel', 'JackDanielsShot', 'JackDanielsShot', 6, 30, NULL, 'bottle', '69.29', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (621, NULL, 'SUBCAT-ALC-LIQ-MartiniRos', 'MartiniRossoShot', 'MartiniRossoShot', 6, 30, NULL, 'bottle', '27', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (622, NULL, 'SUBCAT-ALC-LIQ-BombayShot', 'BombayShot', 'BombayShot', 6, 30, NULL, 'bottle', '27.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (623, NULL, 'SUBCAT-ALC-LIQ-QuenzaCrem', 'QuenzaCremaOrujo', 'QuenzaCremaOrujo', 6, 30, NULL, 'bottle', '864', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (624, NULL, 'SUBCAT-ALC-LIQ-CramberryS', 'CramberryShot', 'CramberryShot', 6, 30, NULL, 'bottle', '7.87', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (625, NULL, 'SUBCAT-ALC-LIQ-QuenzaAgua', 'QuenzaAguardiente', 'QuenzaAguardiente', 6, 30, NULL, 'bottle', '903', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (626, NULL, 'SUBCAT-ALC-LIQ-Baileyssho', 'Baileysshot', 'Baileysshot', 6, 30, NULL, 'bottle', '57.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (627, NULL, 'SUBCAT-ALC-LIQ-TanduaySho', 'TanduayShot', 'TanduayShot', 6, 30, NULL, 'bottle', '13.78', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (628, NULL, 'SUBCAT-ALC-LIQ-DruideVodk', 'DruideVodkaShot', 'DruideVodkaShot', 6, 30, NULL, 'bottle', '140.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (629, NULL, 'SUBCAT-ALC-LIQ-CuervoShot', 'CuervoShot', 'CuervoShot', 6, 30, NULL, 'bottle', '39.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (630, NULL, 'SUBCAT-ALC-LIQ-KahluaShot', 'KahluaShot', 'KahluaShot', 6, 30, NULL, 'bottle', '43.62', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (631, NULL, 'SUBCAT-ALC-LIQ--CampariSh', '-CampariShot', '-CampariShot', 6, 30, NULL, 'bottle', '46.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (632, NULL, 'SUBCAT-ALC-LIQ--Hendricks', '-HendricksShot', '-HendricksShot', 6, 30, NULL, 'bottle', '120', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (633, NULL, 'SUBCAT-ALC-LIQ--BacardiSh', '-BacardiShot', '-BacardiShot', 6, 30, NULL, 'bottle', '17.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (634, NULL, 'SUBCAT-ALC-LIQ--SmirnoffS', '-SmirnoffShot', '-SmirnoffShot', 6, 30, NULL, 'bottle', '32.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (635, NULL, 'SUBCAT-ALC-LIQ-LakanShot', 'LakanShot', 'LakanShot', 6, 30, NULL, 'bottle', '109.38', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (636, NULL, 'SUBCAT-ALC-LIQ-LeonorPalo', 'LeonorPaloCortadoShot', 'LeonorPaloCortadoShot', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (637, NULL, 'SUBCAT-ALC-LIQ-AlfonsoOlo', 'AlfonsoOlorosoShot', 'AlfonsoOlorosoShot', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (638, NULL, 'SUBCAT-ALC-LIQ-Solera1847', 'Solera1847CreamShot', 'Solera1847CreamShot', 6, 30, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (639, NULL, 'SUBCAT-ALC-WINE-ElCamino', 'ElCamino', 'ElCamino', 6, 25, NULL, 'bottle', '451.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (640, NULL, 'SUBCAT-ALC-WINE-CuatroPas', 'CuatroPasos', 'CuatroPasos', 6, 25, NULL, 'bottle', '446.49', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (641, NULL, 'SUBCAT-ALC-WINE-Pizarrasd', 'PizarrasdeOtero', 'PizarrasdeOtero', 6, 25, NULL, 'bottle', '361.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (642, NULL, 'SUBCAT-ALC-WINE-MartinCod', 'MartinCodaxAlbarino', 'MartinCodaxAlbarino', 6, 25, NULL, 'bottle', '758.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (643, NULL, 'SUBCAT-ALC-WINE-BurgansAl', 'BurgansAlbarino', 'BurgansAlbarino', 6, 25, NULL, 'bottle', '589.29', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (644, NULL, 'SUBCAT-ALC-WINE-MaraMarti', 'MaraMartinGodello', 'MaraMartinGodello', 6, 25, NULL, 'bottle', '500.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (645, NULL, 'SUBCAT-ALC-WINE-LiasAlbar', 'LiasAlbarino', 'LiasAlbarino', 6, 25, NULL, 'bottle', '1098.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (646, NULL, 'SUBCAT-ALC-WINE-CuatroPas', 'CuatroPasosRosado', 'CuatroPasosRosado', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (647, NULL, 'SUBCAT-ALC-WINE-MarietaAl', 'MarietaAlbarinosemiseco', 'MarietaAlbarinosemiseco', 6, 25, NULL, 'bottle', '493.75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (648, NULL, 'SUBCAT-ALC-WINE-FataMorga', 'FataMorganaMerlot', 'FataMorganaMerlot', 6, 25, NULL, 'bottle', '2464.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (649, NULL, 'SUBCAT-ALC-WINE-IsolaTemp', 'IsolaTemp/Syrah', 'IsolaTemp/Syrah', 6, 25, NULL, 'bottle', '366.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (650, NULL, 'SUBCAT-ALC-WINE-MontReaga', 'MontReagaElDeseo', 'MontReagaElDeseo', 6, 25, NULL, 'bottle', '839.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (651, NULL, 'SUBCAT-ALC-WINE-MontReaga', 'MontReagaTempoCab', 'MontReagaTempoCab', 6, 25, NULL, 'bottle', '507.15', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (652, NULL, 'SUBCAT-ALC-WINE-IsolaVerd', 'IsolaVerdejoMoscatel', 'IsolaVerdejoMoscatel', 6, 25, NULL, 'bottle', '366.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (653, NULL, 'SUBCAT-ALC-WINE-P&LGarnac', 'P&LGarnachaTemp', 'P&LGarnachaTemp', 6, 25, NULL, 'bottle', '588.39', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (654, NULL, 'SUBCAT-ALC-WINE-Paco&Lola', 'Paco&LolaAlbarino', 'Paco&LolaAlbarino', 6, 25, NULL, 'bottle', '660.71', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (655, NULL, 'SUBCAT-ALC-WINE-LoloAlbar', 'LoloAlbarino', 'LoloAlbarino', 6, 25, NULL, 'bottle', '582.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (656, NULL, 'SUBCAT-ALC-WINE-PagoTinto', 'PagoTintoJoven2016', 'PagoTintoJoven2016', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (657, NULL, 'SUBCAT-ALC-WINE-PagoTinto', 'PagoTintoCrianza2012', 'PagoTintoCrianza2012', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (658, NULL, 'SUBCAT-ALC-WINE-SanMauric', 'SanMauricioTinto', 'SanMauricioTinto', 6, 25, NULL, 'bottle', '250', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (659, NULL, 'SUBCAT-ALC-WINE-RamonRoqu', 'RamonRoquetaGarnacha', 'RamonRoquetaGarnacha', 6, 25, NULL, 'bottle', '294.64', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (660, NULL, 'SUBCAT-ALC-WINE-RamonRoqu', 'RamonRoquetaCrianza', 'RamonRoquetaCrianza', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (661, NULL, 'SUBCAT-ALC-WINE-RamonRoqu', 'RamonRoquetatempranillo', 'RamonRoquetatempranillo', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (662, NULL, 'SUBCAT-ALC-WINE-RamonRoqu', 'RamonRoquetaReserva', 'RamonRoquetaReserva', 6, 25, NULL, 'bottle', '333.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (663, NULL, 'SUBCAT-ALC-WINE-AbadalCab', 'AbadalCabernetFranc', 'AbadalCabernetFranc', 6, 25, NULL, 'bottle', '486.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (664, NULL, 'SUBCAT-ALC-WINE-AbadalCri', 'AbadalCrianza', 'AbadalCrianza', 6, 25, NULL, 'bottle', '624.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (665, NULL, 'SUBCAT-ALC-WINE-AbadalMer', 'AbadalMerlot', 'AbadalMerlot', 6, 25, NULL, 'bottle', '607.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (666, NULL, 'SUBCAT-ALC-WINE-AbadalRes', 'AbadalReserva', 'AbadalReserva', 6, 25, NULL, 'bottle', '727.7', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (667, NULL, 'SUBCAT-ALC-WINE-AbadalRes', 'AbadalReserva3.9', 'AbadalReserva3.9', 6, 25, NULL, 'bottle', '1135.71', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (668, NULL, 'SUBCAT-ALC-WINE-SanMauric', 'SanMauricioBlanco', 'SanMauricioBlanco', 6, 25, NULL, 'bottle', '280', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (669, NULL, 'SUBCAT-ALC-WINE-SanMauric', 'SanMauricioRosado', 'SanMauricioRosado', 6, 25, NULL, 'bottle', '280', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (670, NULL, 'SUBCAT-ALC-WINE-RamonRoqu', 'RamonRoquetaBlanco', 'RamonRoquetaBlanco', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (671, NULL, 'SUBCAT-ALC-WINE-AbadalBla', 'AbadalBlanco', 'AbadalBlanco', 6, 25, NULL, 'bottle', '486.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (672, NULL, 'SUBCAT-ALC-WINE-AbadalPic', 'AbadalPicapoll', 'AbadalPicapoll', 6, 25, NULL, 'bottle', '608', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (673, NULL, 'SUBCAT-ALC-WINE-SyneraRos', 'SyneraRosado', 'SyneraRosado', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (674, NULL, 'SUBCAT-ALC-WINE-Robertson', 'RobertsonRubyPort', 'RobertsonRubyPort', 6, 25, NULL, 'bottle', '937.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (675, NULL, 'SUBCAT-ALC-WINE-Robertson', 'RobertsonTawnyPort', 'RobertsonTawnyPort', 6, 25, NULL, 'bottle', '937.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (676, NULL, 'SUBCAT-ALC-WINE-Robertson', 'RobertsonWhitePort', 'RobertsonWhitePort', 6, 25, NULL, 'bottle', '885.71', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (677, NULL, 'SUBCAT-ALC-WINE-RubyReser', 'RubyReservePort', 'RubyReservePort', 6, 25, NULL, 'bottle', '1205.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (678, NULL, 'SUBCAT-ALC-WINE-Privateer', 'PrivateerReservePort', 'PrivateerReservePort', 6, 25, NULL, 'bottle', '1177.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (679, NULL, 'SUBCAT-ALC-WINE-10yearsOl', '10yearsOldPort', '10yearsOldPort', 6, 25, NULL, 'bottle', '2298.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (680, NULL, 'SUBCAT-ALC-WINE-20yearsOl', '20yearsOldPort', '20yearsOldPort', 6, 25, NULL, 'bottle', '3567.85', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (681, NULL, 'SUBCAT-ALC-WINE-Robertson', 'RobertsonLBVPort', 'RobertsonLBVPort', 6, 25, NULL, 'bottle', '1397.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (682, NULL, 'SUBCAT-ALC-WINE-Herdadedo', 'HerdadedoMonteRed', 'HerdadedoMonteRed', 6, 25, NULL, 'bottle', '551.78', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (683, NULL, 'SUBCAT-ALC-WINE-QuintaAze', 'QuintaAzevedoVerde', 'QuintaAzevedoVerde', 6, 25, NULL, 'bottle', '567.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (684, NULL, 'SUBCAT-ALC-WINE-MorgadioA', 'MorgadioAlvarinho', 'MorgadioAlvarinho', 6, 25, NULL, 'bottle', '1245.54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (685, NULL, 'SUBCAT-ALC-WINE-Herdadedo', 'HerdadedoMonteWhite', 'HerdadedoMonteWhite', 6, 25, NULL, 'bottle', '551.78', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (686, NULL, 'SUBCAT-ALC-WINE-OsaadoShy', 'OsaadoShyraz', 'OsaadoShyraz', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (687, NULL, 'SUBCAT-ALC-WINE-PasoSelec', 'PasoSelectedRed', 'PasoSelectedRed', 6, 25, NULL, 'bottle', '479.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (688, NULL, 'SUBCAT-ALC-WINE-PortilloM', 'PortilloMalbec', 'PortilloMalbec', 6, 25, NULL, 'bottle', '448.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (689, NULL, 'SUBCAT-ALC-WINE-Salentein', 'SalenteinReservaMalbec', 'SalenteinReservaMalbec', 6, 25, NULL, 'bottle', '797.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (690, NULL, 'SUBCAT-ALC-WINE-CaliaAlta', 'CaliaAltaMalbec', 'CaliaAltaMalbec', 6, 25, NULL, 'bottle', '324', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (691, NULL, 'SUBCAT-ALC-WINE-Salentein', 'SalenteinResPinotNoir', 'SalenteinResPinotNoir', 6, 25, NULL, 'bottle', '950.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (692, NULL, 'SUBCAT-ALC-WINE-OsaadoCha', 'OsaadoChardonnay', 'OsaadoChardonnay', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (693, NULL, 'SUBCAT-ALC-WINE-PasoSelec', 'PasoSelectedWhite', 'PasoSelectedWhite', 6, 25, NULL, 'bottle', '392.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (694, NULL, 'SUBCAT-ALC-WINE-PortilloS', 'PortilloSauvignonBlanc', 'PortilloSauvignonBlanc', 6, 25, NULL, 'bottle', '406.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (695, NULL, 'SUBCAT-ALC-WINE-Salentein', 'SalenteinReservaChar', 'SalenteinReservaChar', 6, 25, NULL, 'bottle', '702.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (696, NULL, 'SUBCAT-ALC-WINE-CaliaAlta', 'CaliaAltaChardonnay', 'CaliaAltaChardonnay', 6, 25, NULL, 'bottle', '280', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (697, NULL, 'SUBCAT-NONALC-BEV-CokeLig', 'CokeLight330ml', 'CokeLight330ml', 7, 29, NULL, 'can', '18.97', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (698, NULL, 'SUBCAT-NONALC-BEV-CokeReg', 'CokeRegular330ml', 'CokeRegular330ml', 7, 29, NULL, 'can', '18.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (699, NULL, 'SUBCAT-NONALC-BEV-CokeZer', 'CokeZero330ml', 'CokeZero330ml', 7, 29, NULL, 'can', '18.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (700, NULL, 'SUBCAT-NONALC-BEV-Royal33', 'Royal330ml', 'Royal330ml', 7, 29, NULL, 'can', '18.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (701, NULL, 'SUBCAT-NONALC-BEV-SpriteR', 'SpriteRegular330ml', 'SpriteRegular330ml', 7, 29, NULL, 'can', '18.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (702, NULL, 'SUBCAT-NONALC-BEV-SpriteZ', 'SpriteZero330ml', 'SpriteZero330ml', 7, 29, NULL, 'can', '18.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (703, NULL, 'SUBCAT-NONALC-BEV-Schwepp', 'SchweppesTonic', 'SchweppesTonic', 7, 29, NULL, 'can', '21.58', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (704, NULL, 'SUBCAT-NONALC-BEV-SMMiner', 'SMMineral', 'SMMineral', 7, 29, NULL, 'can', '9.3', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (705, NULL, 'SUBCAT-NONALC-BEV-LiptonI', 'LiptonIceTea', 'LiptonIceTea', 7, 29, NULL, 'can', '14.23', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (706, NULL, 'SUBCAT-NONALC-BEV-Cabreir', 'Cabreiroa33cl', 'Cabreiroa33cl', 7, 29, NULL, 'can', '22.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (707, NULL, 'SUBCAT-NONALC-BEV-Cabreir', 'Cabreiroa1L', 'Cabreiroa1L', 7, 29, NULL, 'can', '49.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (708, NULL, 'SUBCAT-NONALC-BEV-Cabreir', 'Cabreiroa50cl', 'Cabreiroa50cl', 7, 29, NULL, 'can', '43.75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (709, NULL, 'SUBCAT-NONALC-BEV-Cabreir', 'Cabreiroa75cl', 'Cabreiroa75cl', 7, 29, NULL, 'can', '74.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (710, NULL, 'SUBCAT-NONALC-BEV-METonic', 'METonicWater', 'METonicWater', 7, 29, NULL, 'can', '37.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (711, NULL, 'SUBCAT-ALC-WINE-Chatagnau', 'Chatagnau2010', 'Chatagnau2010', 6, 25, NULL, 'bottle', '341.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (712, NULL, 'SUBCAT-ALC-WINE-Bauvallon', 'Bauvallon2009', 'Bauvallon2009', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (713, NULL, 'SUBCAT-ALC-WINE-HautLaval', 'HautLavallade', 'HautLavallade', 6, 25, NULL, 'bottle', '982.14', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (714, NULL, 'SUBCAT-ALC-WINE-LaCroixde', 'LaCroixdeGalian2012', 'LaCroixdeGalian2012', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (715, NULL, 'SUBCAT-ALC-WINE-ChateauMo', 'ChateauMoulin', 'ChateauMoulin', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (716, NULL, 'SUBCAT-NONALC-BEV-Cabreir', 'CabreiroaCG1L', 'CabreiroaCG1L', 7, 29, NULL, 'bottle', '64.29', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (717, NULL, 'SUBCAT-NONALC-BEV-MagmaCG', 'MagmaCG75cl', 'MagmaCG75cl', 7, 29, NULL, 'bottle', '74.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (718, NULL, 'SUBCAT-NONALC-BEV-Perrier', 'Perrier', 'Perrier', 7, 29, NULL, 'bottle', '47.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (719, NULL, 'SUBCAT-NONALC-BEV-Wilkins', 'Wilkinsdistilledwater', 'Wilkinsdistilledwater', 7, 29, NULL, 'bottle', '13.58', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (720, NULL, 'SUBCAT-ALC-WINE-Terrapura', 'TerrapuraCabernet', 'TerrapuraCabernet', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (721, NULL, 'SUBCAT-ALC-WINE-Terrapura', 'TerrapuraCarmenere', 'TerrapuraCarmenere', 6, 25, NULL, 'bottle', '352.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (722, NULL, 'SUBCAT-ALC-WINE-Terrapura', 'TerrapuraMerlot', 'TerrapuraMerlot', 6, 25, NULL, 'bottle', '352.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (723, NULL, 'SUBCAT-ALC-WINE-SanticoCa', 'SanticoCab.Sauv.', 'SanticoCab.Sauv.', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (724, NULL, 'SUBCAT-ALC-WINE-Terrapura', 'TerrapuraChardonnay', 'TerrapuraChardonnay', 6, 25, NULL, 'bottle', '352.68', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (725, NULL, 'SUBCAT-ALC-WINE-Terrapura', 'TerrapuraSauvignon', 'TerrapuraSauvignon', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (726, NULL, 'SUBCAT-ALC-WINE-SanticoSa', 'SanticoSauv.Blanc', 'SanticoSauv.Blanc', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (727, NULL, 'SUBCAT-ALC-WINE-NeiranoBa', 'NeiranoBaroloDOCG', 'NeiranoBaroloDOCG', 6, 25, NULL, 'bottle', '1279.46', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (728, NULL, 'SUBCAT-ALC-WINE-ChiantiCl', 'ChiantiClassicoPrestige', 'ChiantiClassicoPrestige', 6, 25, NULL, 'bottle', '587.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (729, NULL, 'SUBCAT-ALC-WINE-ChiantiPr', 'ChiantiPrestigeDOCG', 'ChiantiPrestigeDOCG', 6, 25, NULL, 'bottle', '433.04', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (730, NULL, 'SUBCAT-ALC-WINE-ChiantiRe', 'ChiantiReservaDOCG', 'ChiantiReservaDOCG', 6, 25, NULL, 'bottle', '833.04', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (731, NULL, 'SUBCAT-ALC-WINE-StMartinR', 'StMartinRose', 'StMartinRose', 6, 25, NULL, 'bottle', '299.1', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (732, NULL, 'SUBCAT-ALC-WINE-StMartinW', 'StMartinWhite', 'StMartinWhite', 6, 25, NULL, 'bottle', '299.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (733, NULL, 'SUBCAT-ALC-WINE-Languedoc', 'LanguedocRose', 'LanguedocRose', 6, 25, NULL, 'bottle', '441.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (734, NULL, 'SUBCAT-ALC-WINE-Languedoc', 'LanguedocBlanc', 'LanguedocBlanc', 6, 25, NULL, 'bottle', '441.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (735, NULL, 'SUBCAT-ALC-WINE-DuoMythiq', 'DuoMythiqueWhite', 'DuoMythiqueWhite', 6, 25, NULL, 'bottle', '392.85', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (736, NULL, 'SUBCAT-ALC-WINE-LaCuveeMy', 'LaCuveeMythiqueW', 'LaCuveeMythiqueW', 6, 25, NULL, 'bottle', '446.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (737, NULL, 'SUBCAT-ALC-WINE-LesFruite', 'LesFruitesWhite', 'LesFruitesWhite', 6, 25, NULL, 'bottle', '299.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (738, NULL, 'SUBCAT-ALC-WINE-StMartinR', 'StMartinR', 'StMartinR', 6, 25, NULL, 'bottle', '299.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (739, NULL, 'SUBCAT-ALC-WINE-LaCuveeMy', 'LaCuveeMythiqueR', 'LaCuveeMythiqueR', 6, 25, NULL, 'bottle', '446.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (740, NULL, 'SUBCAT-ALC-WINE-Languedoc', 'LanguedocMythiqueR', 'LanguedocMythiqueR', 6, 25, NULL, 'bottle', '441.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (741, NULL, 'SUBCAT-ALC-WINE-DuoMythiq', 'DuoMythiqueR', 'DuoMythiqueR', 6, 25, NULL, 'bottle', '385.71', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (742, NULL, 'SUBCAT-ALC-WINE-Closeried', 'CloseriedesDominicans', 'CloseriedesDominicans', 6, 25, NULL, 'bottle', '339.29', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (743, NULL, 'SUBCAT-ALC-WINE-Chatelain', 'ChatelainDesRoches', 'ChatelainDesRoches', 6, 25, NULL, 'bottle', '339.28', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (744, NULL, 'SUBCAT-ALC-WINE-Coutelour', 'CoutelourLaRoumarine', 'CoutelourLaRoumarine', 6, 25, NULL, 'bottle', '352.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (745, NULL, 'SUBCAT-ALC-WINE-LesFruite', 'LesFruitesRed', 'LesFruitesRed', 6, 25, NULL, 'bottle', '299.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (746, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaCabernet', 'ValformosaCabernet', 6, 25, NULL, 'bottle', '562', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (747, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaCrianza', 'ValformosaCrianza', 6, 25, NULL, 'bottle', '421', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (748, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaReserva', 'ValformosaReserva', 6, 25, NULL, 'bottle', '517', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (749, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaReservaMag', 'ValformosaReservaMag', 6, 25, NULL, 'bottle', '2115', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (750, NULL, 'SUBCAT-ALC-WINE-Valformos', 'ValformosaGranReserva', 'ValformosaGranReserva', 6, 25, NULL, 'bottle', '776', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (751, NULL, 'SUBCAT-ALC-WINE-PrimumJov', 'PrimumJoven', 'PrimumJoven', 6, 25, NULL, 'bottle', '333.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (752, NULL, 'SUBCAT-ALC-WINE-PrimumCri', 'PrimumCrianza', 'PrimumCrianza', 6, 25, NULL, 'bottle', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (753, NULL, 'SUBCAT-ALC-WINE-PrimumRes', 'PrimumReserva', 'PrimumReserva', 6, 25, NULL, 'bottle', '627.67', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (754, NULL, 'SUBCAT-ALC-WINE-LaVinaTin', 'LaVinaTinto', 'LaVinaTinto', 6, 25, NULL, 'bottle', '246.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (755, NULL, 'SUBCAT-ALC-WINE-DVLaSalaT', 'DVLaSalaTinto', 'DVLaSalaTinto', 6, 25, NULL, 'bottle', '322.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (756, NULL, 'SUBCAT-ALC-WINE-DVMasiaFr', 'DVMasiaFreyeTinto', 'DVMasiaFreyeTinto', 6, 25, NULL, 'bottle', '448.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (757, NULL, 'SUBCAT-ALC-WINE-Claudia', 'Claudia', 'Claudia', 6, 25, NULL, 'bottle', '375.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (758, NULL, 'SUBCAT-ALC-WINE-LaVinaSem', 'LaVinaSemiSweet', 'LaVinaSemiSweet', 6, 25, NULL, 'bottle', '246.43', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (759, NULL, 'SUBCAT-ALC-WINE-GemmaMerl', 'GemmaMerlotRosado', 'GemmaMerlotRosado', 6, 25, NULL, 'bottle', '374', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (760, NULL, 'SUBCAT-ALC-WINE-MasiaFrey', 'MasiaFreyeWhite', 'MasiaFreyeWhite', 6, 25, NULL, 'bottle', '441.96', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (761, NULL, 'SUBCAT-ALC-WINE-LaVinaBla', 'LaVinaBlanco', 'LaVinaBlanco', 6, 25, NULL, 'bottle', '236.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (762, NULL, 'SUBCAT-ALC-WINE-DVMasiaFr', 'DVMasiaFreyeRosado', 'DVMasiaFreyeRosado', 6, 25, NULL, 'bottle', '448', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (763, NULL, 'SUBCAT-ALC-WINE-DVMasiaFr', 'DVMasiaFreyeBlanco', 'DVMasiaFreyeBlanco', 6, 25, NULL, 'bottle', '448', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (764, NULL, 'SUBCAT-ALC-WINE-DVLaSalaR', 'DVLaSalaRosado', 'DVLaSalaRosado', 6, 25, NULL, 'bottle', '312.5', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (765, NULL, 'SUBCAT-ALC-WINE-DVLaSalaw', 'DVLaSalawhite', 'DVLaSalawhite', 6, 25, NULL, 'bottle', '322.32', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (766, NULL, 'SUBCAT-ALC-WINE-SandaraWi', 'SandaraWineMojito', 'SandaraWineMojito', 6, 25, NULL, 'bottle', '300', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (767, NULL, 'SUBCAT-ALC-WINE-SandaraRe', 'SandaraRed', 'SandaraRed', 6, 25, NULL, 'bottle', '266.08', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (768, NULL, 'SUBCAT-ALC-WINE-SandaraRo', 'SandaraRose', 'SandaraRose', 6, 25, NULL, 'bottle', '266.07', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (769, NULL, 'SUBCAT-ALC-WINE-SandaraWh', 'SandaraWhite', 'SandaraWhite', 6, 25, NULL, 'bottle', '266.08', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (770, NULL, 'SUBCAT-ALC-WINE-BaronVall', 'BaronVallsCab.', 'BaronVallsCab.', 6, 25, NULL, 'bottle', '276.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (771, NULL, 'SUBCAT-ALC-WINE-CastilloR', 'CastilloRedPremium', 'CastilloRedPremium', 6, 25, NULL, 'bottle', '276.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (772, NULL, 'SUBCAT-ALC-WINE-DolmoTemp', 'DolmoTempranillo', 'DolmoTempranillo', 6, 25, NULL, 'bottle', '417.86', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (773, NULL, 'SUBCAT-ALC-WINE-W.I.T.Dav', 'W.I.T.DavidBowieShiraz', 'W.I.T.DavidBowieShiraz', 6, 25, NULL, 'bottle', '524.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (774, NULL, 'SUBCAT-ALC-WINE-W.I.T.Goe', 'W.I.T.GoergeClooneyCab', 'W.I.T.GoergeClooneyCab', 6, 25, NULL, 'bottle', '350', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (775, NULL, 'SUBCAT-ALC-WINE-W.I.T.P.B', 'W.I.T.P.BrosnanTemp', 'W.I.T.P.BrosnanTemp', 6, 25, NULL, 'bottle', '520.33', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (776, NULL, 'SUBCAT-ALC-WINE-CastillaW', 'CastillaWhitePremium', 'CastillaWhitePremium', 6, 25, NULL, 'bottle', '276.79', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (777, NULL, 'SUBCAT-ALC-WINE-NeblaVerd', 'NeblaVerdejoRueda', 'NeblaVerdejoRueda', 6, 25, NULL, 'bottle', '360', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (778, NULL, 'SUBCAT-ALC-WINE-W.I.T.Col', 'W.I.T.ColdplayRosado', 'W.I.T.ColdplayRosado', 6, 25, NULL, 'bottle', '524.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (779, NULL, 'SUBCAT-ALC-WINE-W.I.T.P.C', 'W.I.T.P.CruzSauv.Blanc', 'W.I.T.P.CruzSauv.Blanc', 6, 25, NULL, 'bottle', '524.11', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (780, NULL, 'SUBCAT-ALC-WINE-LaLocomot', 'LaLocomotoraCrianza', 'LaLocomotoraCrianza', 6, 25, NULL, 'bottle', '740.18', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (781, NULL, 'SUBCAT-ALC-WINE-ElHombreB', 'ElHombreBala', 'ElHombreBala', 6, 25, NULL, 'bottle', '1100.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (782, NULL, 'SUBCAT-ALC-WINE-VetalasVa', 'VetalasVacas', 'VetalasVacas', 6, 25, NULL, 'bottle', '891.97', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (783, NULL, 'SUBCAT-ALC-WINE-ElPerroVe', 'ElPerroVerde', 'ElPerroVerde', 6, 25, NULL, 'bottle', '645.54', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (784, NULL, 'SUBCAT-ALC-WINE-L\'Equilib', 'L\'EquilibristaBlanco', 'L\'EquilibristaBlanco', 6, 25, NULL, 'bottle', '834.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (785, NULL, 'SUBCAT-ALC-WINE-Sospechos', 'SospechosoRose', 'SospechosoRose', 6, 25, NULL, 'bottle', '569.64', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (786, NULL, 'SUBCAT-ALC-WINE-MATSUelPI', 'MATSUelPICARO', 'MATSUelPICARO', 6, 25, NULL, 'bottle', '448.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (787, NULL, 'SUBCAT-ALC-WINE-PROJECTGA', 'PROJECTGARSALVAJEMON', 'PROJECTGARSALVAJEMON', 6, 25, NULL, 'bottle', '500', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (788, NULL, 'SUBCAT-ALC-WINE-TRESalCUA', 'TRESalCUADRADO', 'TRESalCUADRADO', 6, 25, NULL, 'bottle', '589.29', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (789, NULL, 'SUBCAT-ALC-WINE-SEISalREV', 'SEISalREVES', 'SEISalREVES', 6, 25, NULL, 'bottle', '656.25', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (790, NULL, 'SUBCAT-ALC-WINE-PROJECTGA', 'PROJECTGAROLVIDADAARAG', 'PROJECTGAROLVIDADAARAG', 6, 25, NULL, 'bottle', '736.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (791, NULL, 'SUBCAT-ALC-WINE-MATSUelRE', 'MATSUelRECIO', 'MATSUelRECIO', 6, 25, NULL, 'bottle', '834.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (792, NULL, 'SUBCAT-ALC-WINE-PROJECTGA', 'PROJECTGARFOSCA', 'PROJECTGARFOSCA', 6, 25, NULL, 'bottle', '825.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (793, NULL, 'SUBCAT-ALC-WINE-NUMERONUE', 'NUMERONUEVETEMPCAB', 'NUMERONUEVETEMPCAB', 6, 25, NULL, 'bottle', '825.89', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (794, NULL, 'SUBCAT-ALC-WINE-MATSUELVI', 'MATSUELVIEJO', 'MATSUELVIEJO', 6, 25, NULL, 'bottle', '2011.61', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (795, NULL, 'SUBCAT-ALC-WINE-HerdadeVi', 'HerdadeVinhaDoMonte', 'HerdadeVinhaDoMonte', 6, 25, NULL, 'glass', '137.95', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (796, NULL, 'SUBCAT-ALC-WINE-VDVCabSau', 'VDVCabSauvGlass', 'VDVCabSauvGlass', 6, 25, NULL, 'glass', '180.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (797, NULL, 'SUBCAT-ALC-WINE-AltozanoS', 'AltozanoShirazGlass', 'AltozanoShirazGlass', 6, 25, NULL, 'glass', '71.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (798, NULL, 'SUBCAT-ALC-WINE-St.Martin', 'St.MartinGlass', 'St.MartinGlass', 6, 25, NULL, 'glass', '74.78', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (799, NULL, 'SUBCAT-ALC-WINE-SandaraMo', 'SandaraMojito', 'SandaraMojito', 6, 25, NULL, 'glass', '75', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (800, NULL, 'SUBCAT-ALC-WINE-SandaraTi', 'SandaraTinto', 'SandaraTinto', 6, 25, NULL, 'glass', '66.52', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (801, NULL, 'SUBCAT-ALC-WINE-SandaraBl', 'SandaraBlanco', 'SandaraBlanco', 6, 25, NULL, 'glass', '66.52', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (802, NULL, 'SUBCAT-ALC-WINE-SandaraRo', 'SandaraRosado', 'SandaraRosado', 6, 25, NULL, 'glass', '66.52', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (803, NULL, 'SUBCAT-ALC-WINE-IsolaWhit', 'IsolaWhiteGlass', 'IsolaWhiteGlass', 6, 25, NULL, 'glass', '91.52', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (804, NULL, 'SUBCAT-ALC-WINE-AltozanoR', 'AltozanoRosadoGlass', 'AltozanoRosadoGlass', 6, 25, NULL, 'glass', '71.21', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (805, NULL, 'SUBCAT-ALC-WINE-ELCOTOROS', 'ELCOTOROSE', 'ELCOTOROSE', 6, 25, NULL, 'glass', '93.08', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (806, NULL, 'SUBCAT-ALC-WINE-BORSAOSEL', 'BORSAOSELECCIONROSADO', 'BORSAOSELECCIONROSADO', 6, 25, NULL, 'glass', '56.7', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (807, NULL, 'SUBCAT-ALC-WINE-RAMONROQU', 'RAMONROQUETARESERVA', 'RAMONROQUETARESERVA', 6, 25, NULL, 'bottle', '333.93', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (808, NULL, 'SUBCAT-ALC-WINE-CANEPACLA', 'CANEPACLASS.CAB/SAU', 'CANEPACLASS.CAB/SAU', 6, 25, NULL, 'glass', '56.03', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (809, NULL, 'SUBCAT-ALC-WINE-ALTOZANOB', 'ALTOZANOBLANCO', 'ALTOZANOBLANCO', 6, 25, NULL, 'bottle', '284.82', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (810, NULL, 'SUBCAT-ALC-WINE-PONTEALBA', 'PONTEALBARINO', 'PONTEALBARINO', 6, 25, NULL, 'glass', '168.97', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (811, NULL, 'SUBCAT-ALC-WINE-VDVMACABE', 'VDVMACABEOGLASS', 'VDVMACABEOGLASS', 6, 25, NULL, 'glass', '80.58', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (812, NULL, 'SUBCAT-ALC-WINE-Stonebarn', 'StonebarnZinfandelglass', 'StonebarnZinfandelglass', 6, 25, NULL, 'glass', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (813, NULL, 'SUBCAT-ALC-WINE-VDVTintog', 'VDVTintoglass', 'VDVTintoglass', 6, 25, NULL, 'glass', '80.36', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (814, NULL, 'SUBCAT-ALC-WINE-MasiaLaSa', 'MasiaLaSalaRedglass', 'MasiaLaSalaRedglass', 6, 25, NULL, 'glass', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (815, NULL, 'SUBCAT-ALC-WINE-MaraMarti', 'MaraMartinGodelloglass', 'MaraMartinGodelloglass', 6, 25, NULL, 'glass', '125.22', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (816, NULL, 'SUBCAT-ALC-WINE-VIVANCOBL', 'VIVANCOBLANCO', 'VIVANCOBLANCO', 6, 25, NULL, 'glass', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (817, NULL, 'SUBCAT-ALC-WINE-Terrapura', 'TerrapuraChardonnayglas', 'TerrapuraChardonnayglas', 6, 25, NULL, 'glass', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (818, NULL, 'SUBCAT-ALC-WINE-Fragantia', 'FragantiaRoseglass', 'FragantiaRoseglass', 6, 25, NULL, 'glass', '93.97', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (819, NULL, 'SUBCAT-ALC-WINE-MasiaFrey', 'MasiaFreyeRosadoglass', 'MasiaFreyeRosadoglass', 6, 25, NULL, 'glass', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (820, NULL, 'SUBCAT-ALC-WINE-VDVCabSau', 'VDVCabSauvignon', 'VDVCabSauvignon', 6, 25, NULL, 'glass', '0', 1, '0', NULL, '0', '0', '0', NULL, NULL, NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (821, '', 'SUBCAT-FVEG', 'Cucumber', '', 5, 5, 0, 'kilo', '100', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 11:54:18', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (822, '', 'SUBCAT-MEJILLONES', 'Sub_Mejillones Rellenos', '', 12, 31, 0, 'Serving', '43.01', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:25:10', '2017-04-05 12:33:48', 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (823, '', 'SUBCAT-PISTOMACHE', 'Sub_Pisto Machego', '', 12, 31, 0, 'Serving', '35.4', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:33:09', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (824, '', 'SUBCAT-AJO-BLANCO', 'Sub_Ajo Blanco Malagueno', '', 12, 31, 0, 'Serving', '70.6', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:35:08', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (825, '', 'SUBCAT-PESCADITO', 'Sub_Pescadito Adodo Marinado', '', 12, 31, 0, 'Serving', '162.75', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:42:10', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (826, '', 'SUBCAT-EMPANADILLAS', 'Sub_Empanadillas Atun', '', 12, 31, 0, 'Serving', '101.45', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:43:11', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (827, '', 'SUBCAT-BUNUELOS', 'Sub_Bunuelos De Anis', '', 12, 31, 0, 'Serving', '8.78', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:44:04', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (828, '', 'SUBCAT-COCIDO', 'Sub_Cocido Madrileno', '', 12, 31, 0, 'Serving', '66.73', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:51:49', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (829, '', 'SUBCAT-PARILLADA', 'Sub_Parillada De Carnes', '', 12, 31, 0, 'Serving', '241.43', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:53:10', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (830, '', 'SUBCAT-POLLO-EMPANADO-QUE', 'Sub_Pollo Empanado Queso', '', 12, 31, 0, 'Serving', '144.11', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:54:15', '2017-04-05 16:19:54', 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (831, '', 'SUBCAT-SOPA-PESCADO', 'Sub_Sopa Pescado', '', 12, 31, 0, 'Serving', '35.4', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:55:01', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (832, '', 'SUBCAT-ALBONDIGAS', 'Sub_Albondigas De La Abuela', '', 12, 31, 0, 'Serving', '110.22', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:55:50', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (833, '', 'SUBCAT-LENTEJAS', 'Sub_Lentejas De La Abuela', '', 12, 31, 0, 'Serving', '106.44', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 12:56:46', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (834, '', 'SUBCAT-OVEJO-ANEJO', 'Oveja Anejo DO', '', 9, 1, 0, 'kilo', '1688', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 13:06:38', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (835, '', 'SUBCAT-OVEJA-GRAN', 'Oveja Gran Reserva', '', 9, 1, 0, 'kilo', '1665', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 13:12:24', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (836, '', 'SUBCAT-OVEJA-TIERNO', 'Oveja Tierno', '', 9, 1, 0, 'kilo', '1225.89', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 13:13:39', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (837, '', 'SUBCAT-OVEJA CURADO', 'Oveja Curado', '', 9, 1, 0, 'kilo', '1575', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 13:22:55', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (838, '', 'SUBCAT-MURCIA-Al', 'Murcia Al Vino L', '', 9, 1, 0, 'kilo', '1466.96', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 13:23:52', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (839, '', 'SUBCAT-IBERICO-SEMICURADO', 'Iberico Semicurado', '', 9, 1, 0, 'kilo', '1116.96', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 13:25:47', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (840, '', 'SUBCAT-OVEJA-ARTESANO', 'Oveja Semicurado Artesano', '', 9, 1, 0, 'kilo', '1406.25', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 13:27:16', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (841, '', 'SUBCAT-IDIAZABAL', 'Idiazabal', '', 9, 1, 0, 'kilo', '1424.11', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 13:28:13', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (842, '', 'SUBCAT-SERPIS-RED-OLIVES', 'Serpis Red Pepper Olives', '', 13, 1, 0, 'pack', '45', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 13:55:13', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (843, '', 'SUBCAT-SERPIS-ANCHOVY', 'Serpis Anchovy Olives', '', 13, 1, 0, 'pack', '40.18', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 13:57:35', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (844, '', 'SUBCAT-PARMIGGIANO', 'Parmiggiano', '', 14, 1, 0, 'kilo', '1315.04', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:07:49', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (845, '', 'SUBCAT-OVEJO-CURADO-KIT', 'Oveja Curado - kit', '', 14, 1, 0, 'kilo', '1486.61', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:09:15', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (846, '', 'SUBCAT-QUESO-Al-PIM-KIT', 'Queso Al Pim - kit', '', 14, 1, 0, 'kilo', '1602', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:11:14', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (847, '', 'SUBCAT-MURCIAL-Al-VIN-KIT', 'Murcial Al Vin - kit', '', 14, 1, 0, 'kilo', '1643', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:12:38', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (848, '', 'SUBCAT-VALDEON-LARGE-KIT', 'Valdeon Large - kit', '', 14, 1, 0, 'kilo', '1366.07', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:13:39', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (849, '', 'SUBCAT-TGT-PIMENTON1KG', 'TGT Pimenton 1kg', '', 14, 1, 0, 'kilo', '1245.54', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:15:00', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (850, '', 'SUBCAT-TGT-MANCHEGO-SEMI', 'TGT Manchego Semicurado', '', 14, 1, 0, 'kilo', '1084.82', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:16:33', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (851, '', 'SUBCAT-TGT-MURCIA-Al-VINO', 'TGT Murcia Al Vino', '', 14, 1, 0, 'kilo', '1366.07', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:17:33', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (852, '', 'SUBCAT-GOAT-CHEESE', 'Goat Cheese', '', 14, 1, 0, 'kilo', '150', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:18:21', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (853, '', 'SUBCAT-TGT-ARBIDEY-PIEZA', 'TGT Arbidey Pieza', '', 14, 1, 0, 'kilo', '783.93', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:19:27', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (854, '', 'SUBCAT-TGT-RONKARI-BLUE-C', 'TGT Ronkari Blue Cheese', '', 14, 1, 0, 'kilo', '1620', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:20:23', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (855, '', 'SUBCAT-TGT-HISPANICO', 'TGT Hispanico', '', 14, 1, 0, 'kilo', '1366.07', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:21:15', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (856, '', 'SUBCAT-TGT-MANCHEGO-CURAD', 'TGT Manchego Curado', '', 14, 1, 0, 'kilo', '1185.71', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:22:15', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (857, '', 'SUBCAT-TGT-IBERICO-SEMI', 'TGT Iberico Semicurado', '', 14, 1, 0, 'kilo', '964.29', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:27:13', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (858, '', 'SUBCAT-QUICKMELT-CHEESE', 'Quickmelt Cheese', '', 14, 1, 0, 'kilo', '489.31', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:28:01', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (859, '', 'SUBCAT-NEW-ZEALAND-MUSSEL', 'New Zealand Mussels', '', 4, 4, 0, 'kilo', '100', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:31:21', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (860, '', 'SUBCAT-SALCHICHON-IBERICO', 'Nico Salchichon Iberico', '', 9, 32, 0, 'pack', '140.63', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 14:39:54', '2017-04-05 14:43:24', 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (861, '', 'SUBCAT-POTATO-CHIPS', 'Potato Chips', '', 9, 13, 0, 'pack', '21.67', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 14:44:28', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (862, '', 'SUBCAT-NICO-CHORIZO-IBERI', 'NICO Chorizo Iberico', '', 9, 32, 0, 'pack', '139.46', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 14:46:15', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (863, '', 'SUBCAT-NICO-JAMON-SERRANO', 'NICO Jamon Serrano', '', 9, 32, 0, 'pack', '158.75', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 14:47:15', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (864, '', 'SUBCAT-NICO-CEBO-IBERICO', 'NICO Cebo Iberico', '', 9, 32, 0, 'pack', '333.48', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 14:48:20', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (865, '', 'SUBCAT-NICO-PALTE-IBERICO', 'NICO Paleta Iberico', '', 9, 32, 0, 'pack', '299.82', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 14:49:17', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (866, '', 'SUBCAT-CHOCO-UNSWEETEND', 'Choco Unsweetend', '', 1, 16, 0, 'kilo', '358.48', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:53:39', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (867, '', 'SUBCAT-CHOCO-BAR-PREMIUM', 'Choco Bar Premium', '', 1, 14, 0, 'kilo', '204.91', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:54:57', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (868, '', 'SUBCAT-POWDERED-SUGAR', 'Powdered Sugar', '', 1, 14, 0, 'kilo', '72.44', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 14:55:55', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (869, '', 'SUBCAT-LIME', 'Lime', '', 5, 9, 0, 'kilo', '150', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 15:04:48', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (870, '', 'SUBCAT-WATERMELON', 'Watermelon', '', 5, 9, 0, 'kilo', '60', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 15:07:02', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (871, '', 'SUBCAT-WILD-MUSH-BIG', 'Wild mushrooms Big', '', 1, 12, 0, 'can', '323.21', 1, '0', 'can', '0', '0', '0', NULL, '2017-04-05 15:23:38', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (872, '', 'SUBCAT-WILD-MUSH-SMALL', 'Wild Mushrooms Small', '', 1, 12, 0, 'can', '602.68', 1, '0', 'can', '0', '0', '0', NULL, '2017-04-05 15:37:17', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (873, '', 'SUBCAT-SALSA-BRAVA-320G', 'Salsa Brava 320G', '', 1, 12, 0, 'bottle', '176', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 15:38:24', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (874, '', 'SUBCAT-SALSA-MOJO-PICON-2', 'Salsa Mojo Picon 295G', '', 1, 12, 0, 'bottle', '176', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 15:39:37', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (875, '', 'SUBCAT-SALSA-MOJO-VERDE-3', 'Salsa Mojo Verde 310G', '', 1, 12, 0, 'bottle', '176', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 15:40:36', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (876, '', 'SUBCAT-SALSA-CHIMICHURRI-', 'Salsa Chimichurri 320G', '', 1, 12, 0, 'bottle', '176', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 15:41:34', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (877, '', 'SUBCAT-PERDINA-LENTILS', 'Pardina Lentils', '', 1, 12, 0, 'can', '141.07', 1, '0', 'can', '0', '0', '0', NULL, '2017-04-05 15:42:21', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (878, '', 'SUBCAT-WHITE-ASPARRAGUS-N', 'White Asparragus Navarra', '', 1, 12, 0, 'can', '474.11', 1, '0', 'can', '0', '0', '0', NULL, '2017-04-05 15:43:25', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (879, '', 'SUBCAT-SHORT-ASPARRAGUS', 'Short Asparragus', '', 1, 12, 0, 'can', '100.89', 1, '0', 'can', '0', '0', '0', NULL, '2017-04-05 15:44:51', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (880, '', 'SUBCAT-PEDROSILLANO-CHICK', 'Pedrosillano Chickpeas', '', 1, 12, 0, 'can', '141.07', 1, '0', 'can', '0', '0', '0', NULL, '2017-04-05 15:45:59', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (881, '', 'SUBCAT-LO-BUENO-ESCU-SURI', 'Lo Bueno Esculas Surimi', '', 2, 2, 0, 'pack', '120.54', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 15:49:17', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (882, '', 'SUBCAT-ANGEL-HAIR-PASTA-5', 'Angel Hair Pasta 500G', '', 9, 29, 0, 'pack', '41.9', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 15:59:24', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (883, '', 'SUBCAT-OLIVES-STUFF-ANCH-', 'Olives Stuff Anch 150G', '', 9, 29, 0, 'can', '68.75', 1, '0', 'can', '0', '0', '0', NULL, '2017-04-05 16:00:27', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (884, '', 'SUBCAT-CHERRY-CAN', 'Cherry Can 730G', '', 9, 29, 0, 'can', '211', 1, '0', 'can', '0', '0', '0', NULL, '2017-04-05 16:01:27', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (885, '', 'SUBCAT-STRAWBERRY-CAN', 'Strawberry Canned', '', 9, 29, 0, 'can', '140', 1, '0', 'can', '0', '0', '0', NULL, '2017-04-05 16:02:20', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (886, '', 'SUBCAT-ATCHUETE', 'Atchuete', '', 10, 15, 0, 'kilo', '165.18', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 16:04:38', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (887, '', 'SUBCAT-LONGANIZA-PAYES-EX', 'Longaniza Payes Extra', '', 2, 2, 0, 'pack', '432', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 16:06:33', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (888, '', 'SUBCAT-FUET-EXTRA PCK', 'Fuet Extra Pack/180G', '', 2, 2, 0, 'pack', '180.8', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 16:07:31', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (889, '', 'SUBCAT-SALCHICHON-IGV-VIC', 'Salchichon IGV Vic', '', 2, 2, 0, 'pack', '530.36', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 16:08:30', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (890, '', 'SUB-EVOO-ARBEQUINA-500ML', 'EVOO Arbequina 500ML', 'EVOO Arbequina 500ML', 10, 15, 0, 'bottle', '225', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:11:53', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (891, '', 'SUBCAT-EXTRA-VIRGIN-OIL-1', 'Extra Virgin Oil 1L', 'Extra Virgin Oil 1L', 10, 10, 0, 'bottle', '262.5', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:13:00', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (892, '', 'SUBCAT-POLLO-EMPANADO', 'Pollo Emanado', '', 12, 31, 0, 'Serving', '144.11', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 16:20:53', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (893, '', 'SUBCAT-POLLO-Al-AJILLO', 'Pollo Al Ajillo', '', 12, 31, 0, 'Serving', '111.09', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 16:21:42', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (894, '', 'SUBCAT-SOPA-DE-PESCADO', 'Sopa De Pescado', '', 12, 31, 0, 'Serving', '94.03', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 16:26:13', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (895, '', 'SUBCAT-FILIPINOS-DARK-CHO', 'Filipinos Dark Chocolate', '', 1, 29, 0, 'pack', '67', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 16:27:52', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (896, '', 'SUBCAT-FILIPINOS-WHITE-CH', 'Filipinos White Chocolate', 'Filipinos White Chocolate', 9, 29, 0, 'pack', '56', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 16:28:56', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (897, '', 'SUBCAT-EXCALIVADA-DE-ATUN', 'Escalivada De Atun', '', 12, 31, 0, 'Serving', '275', 1, '0', 'Serving', '0', '0', '0', NULL, '2017-04-05 16:29:59', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (898, '', 'SUBCAT-QUESO-PIMENTON-KIT', 'Queso Pimenton - kit', '', 10, 15, 0, 'kilo', '1602', 1, '0', 'kilo', '0', '0', '0', NULL, '2017-04-05 16:38:17', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (899, '', 'SUBCAT-TUNE-PATE', 'Tuna Pate', '', 7, 29, 0, 'bottle', '127', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:42:53', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (900, '', 'SUBCAT-CALLOS-MADRID-STYL', 'Callos Madrid Style', 'Callos Madrid Style', 7, 29, 0, 'bottle', '240', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:43:53', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (901, '', 'SUBCAT-MEDITERRANEAN-SALS', 'Mediterranean Salsa', 'Mediterranean Salsa', 7, 29, 0, 'bottle', '122', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:44:54', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (902, '', 'SUBCAT-HOT-BANDERILLAS', 'Hot Banderillas', 'Hot Banderillas', 7, 29, 0, 'bottle', '138', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:45:40', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (903, '', 'SUBCAT-RED-BEANS', 'Red Beans', '', 7, 29, 0, 'bottle', '128', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:47:12', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (904, '', 'SUBCAT-GHERKINS', 'Gherkins', '', 7, 29, 0, 'bottle', '131', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:48:00', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (905, '', 'SUBCAT-IBERIKA-PORK-LIVER', 'Iberika Pork Liver Pate', '', 7, 29, 0, 'bottle', '104', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:49:00', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (906, '', 'SUBCAT-FINE-HERBS-PATE', 'Fine Herbs Pate', '', 7, 29, 0, 'bottle', '102', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:49:43', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (907, '', 'SUBCAT-BEAN-STEW-CHORIZO', 'Bean Stew with Chorizo', 'Bean Stew with Chorizo', 7, 29, 0, 'bottle', '168', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:52:40', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (908, '', 'SUBCAT-MEAT-BALL-PEAS', 'Meat Ball with Peas', 'Meat Ball with Peas', 7, 29, 0, 'bottle', '126.78', 1, '0', 'bottle', '0', '0', '0', NULL, '2017-04-05 16:53:33', NULL, 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (909, '', 'SUBCAT-B18', 'B-18 (Disposable Plastic Container)', 'B-18 (Disposable Plastic Container)', 13, 0, 0, 'pack', '180.4', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 16:58:40', '2017-04-05 17:01:08', 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (910, '', 'SUBCAT-R750', 'R-750 (Disposable Plastic Container)', '', 13, 0, 0, 'pack', '286.4', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 17:00:27', '2017-04-05 17:01:33', 0);
INSERT INTO items (`item_id`, `barcode`, `code`, `name`, `desc`, `cat_id`, `subcat_id`, `supplier_id`, `uom`, `cost`, `type`, `no_per_pack`, `no_per_pack_uom`, `no_per_case`, `reorder_qty`, `max_qty`, `memo`, `reg_date`, `update_date`, `inactive`) VALUES (911, '', 'SUBCAT-U-1000', 'U-1000 (Disposable Plastic Container)', 'U-1000 (Disposable Plastic Container)', 13, 0, 0, 'pack', '294.8', 1, '0', 'pack', '0', '0', '0', NULL, '2017-04-05 17:03:00', NULL, 0);


#
# TABLE STRUCTURE FOR: locations
#

DROP TABLE IF EXISTS locations;

CREATE TABLE `locations` (
  `loc_id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_code` varchar(22) DEFAULT NULL,
  `loc_name` varchar(55) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`loc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO locations (`loc_id`, `loc_code`, `loc_name`, `inactive`) VALUES (1, 'Ayala30th', 'Ayala 30th', 0);


#
# TABLE STRUCTURE FOR: loyalty_cards
#

DROP TABLE IF EXISTS loyalty_cards;

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

INSERT INTO loyalty_cards (`card_id`, `code`, `cust_id`, `points`, `reg_user_id`, `reg_date`, `inactive`, `sync_id`) VALUES (1, '00000001', 2, '0', 55, '2017-08-07 22:52:31', 0, NULL);


#
# TABLE STRUCTURE FOR: megamall
#

DROP TABLE IF EXISTS megamall;

CREATE TABLE `megamall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `br_code` varchar(20) DEFAULT NULL,
  `tenant_no` varchar(20) DEFAULT NULL,
  `class_code` varchar(20) DEFAULT '',
  `trade_code` varchar(20) DEFAULT NULL,
  `outlet_no` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

#
# TABLE STRUCTURE FOR: megaworld
#

DROP TABLE IF EXISTS megaworld;

CREATE TABLE `megaworld` (
  `id` int(11) NOT NULL DEFAULT '0',
  `tenant_code` varchar(50) DEFAULT NULL,
  `sales_type` varchar(20) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

INSERT INTO megaworld (`id`, `tenant_code`, `sales_type`, `file_path`) VALUES (1, 'code1', '10', 'C:MEGAWORLD');


#
# TABLE STRUCTURE FOR: menu_categories
#

DROP TABLE IF EXISTS menu_categories;

CREATE TABLE `menu_categories` (
  `menu_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_cat_name` varchar(150) NOT NULL,
  `menu_sched_id` int(11) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  `arrangement` int(11) DEFAULT '0',
  PRIMARY KEY (`menu_cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;

INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (39, 'DIMSUM', 1, '2016-12-19 07:46:41', 0, 2);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (40, 'CONGEE', 1, '2016-12-19 07:46:41', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (41, 'NOODLE SOUP', 1, '2016-12-19 07:46:41', 0, 6);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (42, 'FRESH VEGETABLES', 1, '2016-12-19 07:46:41', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (43, 'SOUP', 1, '2016-12-19 07:46:41', 0, 1);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (44, 'CHINESE CLASSICS', 1, '2016-12-19 07:46:41', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (45, 'RICE / NOODLE', 1, '2016-12-19 07:46:41', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (46, 'RICE TOPPINGS', 1, '2016-12-19 07:46:41', 1, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (47, 'DESSERT', 1, '2016-12-19 07:46:41', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (48, 'DRINKS', 1, '2016-12-19 07:46:41', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (49, 'ROASTING', 1, '2016-12-19 07:46:41', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (50, 'OTHERS', 1, '2016-12-19 07:46:41', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (51, 'FREE ITEMS', 1, '2016-12-19 07:46:41', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (52, 'PROMOS', 1, '2016-12-19 07:46:41', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (53, 'EXTRA CHARGE', 1, '2016-12-19 07:46:41', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (54, 'SET MEAL(6PAX)', 1, '2016-12-19 07:46:41', 1, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (55, 'SET MEAL(12PAX)', 1, '2016-12-19 07:46:41', 1, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (56, 'SET MENU 6 PAX', 1, '2016-12-19 07:46:41', 1, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (57, 'SET MENU (10pax)', 1, '2016-12-19 07:46:41', 1, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (58, 'PARTY TRAYS', 0, '2017-07-20 14:39:50', 1, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (59, 'HOT POT', 0, '2017-07-20 15:01:05', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (60, 'PACK MEAL', 0, '2017-07-20 15:10:49', 1, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (61, 'CHINESE CLASSIC 2017', 0, '2017-09-15 15:09:46', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (62, 'SET MENU 6 PAX', 0, '2017-09-16 16:50:12', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (63, 'SET MENU 10 PAX', 0, '2017-09-16 16:50:23', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (64, 'PARTY TRAYS 2017', 1, '2017-09-26 17:34:58', 0, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (65, 'PACKED MEALS 2017', 1, '2017-09-26 17:35:50', 1, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (66, 'APPETIZERS', 0, '2018-03-25 15:25:50', 1, 0);
INSERT INTO menu_categories (`menu_cat_id`, `menu_cat_name`, `menu_sched_id`, `reg_date`, `inactive`, `arrangement`) VALUES (67, 'BFF MEALS', 0, '2018-03-25 15:30:34', 0, 0);


#
# TABLE STRUCTURE FOR: menu_modifiers
#

DROP TABLE IF EXISTS menu_modifiers;

CREATE TABLE `menu_modifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `mod_group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

INSERT INTO menu_modifiers (`id`, `menu_id`, `mod_group_id`) VALUES (1, 525, 1);
INSERT INTO menu_modifiers (`id`, `menu_id`, `mod_group_id`) VALUES (2, 527, 2);
INSERT INTO menu_modifiers (`id`, `menu_id`, `mod_group_id`) VALUES (3, 526, 3);
INSERT INTO menu_modifiers (`id`, `menu_id`, `mod_group_id`) VALUES (4, 528, 4);
INSERT INTO menu_modifiers (`id`, `menu_id`, `mod_group_id`) VALUES (5, 529, 5);
INSERT INTO menu_modifiers (`id`, `menu_id`, `mod_group_id`) VALUES (6, 530, 6);
INSERT INTO menu_modifiers (`id`, `menu_id`, `mod_group_id`) VALUES (7, 531, 7);
INSERT INTO menu_modifiers (`id`, `menu_id`, `mod_group_id`) VALUES (8, 532, 8);
INSERT INTO menu_modifiers (`id`, `menu_id`, `mod_group_id`) VALUES (9, 1099, 1);
INSERT INTO menu_modifiers (`id`, `menu_id`, `mod_group_id`) VALUES (10, 1100, 3);


#
# TABLE STRUCTURE FOR: menu_moves
#

DROP TABLE IF EXISTS menu_moves;

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

#
# TABLE STRUCTURE FOR: menu_recipe
#

DROP TABLE IF EXISTS menu_recipe;

CREATE TABLE `menu_recipe` (
  `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `uom` varchar(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` double NOT NULL,
  PRIMARY KEY (`recipe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO menu_recipe (`recipe_id`, `menu_id`, `item_id`, `uom`, `qty`, `cost`) VALUES (1, 9, 822, 'Serving', 0, '43.01');
INSERT INTO menu_recipe (`recipe_id`, `menu_id`, `item_id`, `uom`, `qty`, `cost`) VALUES (2, 6, 5, 'gm', 150, '0.77');


#
# TABLE STRUCTURE FOR: menu_schedule_details
#

DROP TABLE IF EXISTS menu_schedule_details;

CREATE TABLE `menu_schedule_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_sched_id` int(11) NOT NULL,
  `day` varchar(22) NOT NULL,
  `time_on` time NOT NULL,
  `time_off` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

INSERT INTO menu_schedule_details (`id`, `menu_sched_id`, `day`, `time_on`, `time_off`) VALUES (8, 1, 'mon', '10:00:00', '03:00:00');
INSERT INTO menu_schedule_details (`id`, `menu_sched_id`, `day`, `time_on`, `time_off`) VALUES (10, 1, 'tue', '10:00:00', '03:00:00');
INSERT INTO menu_schedule_details (`id`, `menu_sched_id`, `day`, `time_on`, `time_off`) VALUES (11, 1, 'wed', '10:00:00', '03:00:00');
INSERT INTO menu_schedule_details (`id`, `menu_sched_id`, `day`, `time_on`, `time_off`) VALUES (12, 1, 'thu', '10:00:00', '03:00:00');
INSERT INTO menu_schedule_details (`id`, `menu_sched_id`, `day`, `time_on`, `time_off`) VALUES (13, 1, 'fri', '10:00:00', '03:00:00');
INSERT INTO menu_schedule_details (`id`, `menu_sched_id`, `day`, `time_on`, `time_off`) VALUES (14, 1, 'sat', '10:00:00', '03:00:00');
INSERT INTO menu_schedule_details (`id`, `menu_sched_id`, `day`, `time_on`, `time_off`) VALUES (15, 1, 'sun', '10:00:00', '03:00:00');


#
# TABLE STRUCTURE FOR: menu_schedules
#

DROP TABLE IF EXISTS menu_schedules;

CREATE TABLE `menu_schedules` (
  `menu_sched_id` int(11) NOT NULL AUTO_INCREMENT,
  `desc` varchar(150) NOT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`menu_sched_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO menu_schedules (`menu_sched_id`, `desc`, `inactive`) VALUES (1, 'Regular Schedule', 0);


#
# TABLE STRUCTURE FOR: menu_subcategories
#

DROP TABLE IF EXISTS menu_subcategories;

CREATE TABLE `menu_subcategories` (
  `menu_sub_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_sub_cat_name` varchar(150) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`menu_sub_cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO menu_subcategories (`menu_sub_cat_id`, `menu_sub_cat_name`, `reg_date`, `inactive`) VALUES (1, 'FOOD', '2016-12-19 07:46:41', 1);
INSERT INTO menu_subcategories (`menu_sub_cat_id`, `menu_sub_cat_name`, `reg_date`, `inactive`) VALUES (2, 'BEVERAGES', '2016-12-19 07:46:41', 0);
INSERT INTO menu_subcategories (`menu_sub_cat_id`, `menu_sub_cat_name`, `reg_date`, `inactive`) VALUES (3, 'NON FOOD', '2016-12-19 07:46:41', 0);


#
# TABLE STRUCTURE FOR: menus
#

DROP TABLE IF EXISTS menus;

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
  `costing` double DEFAULT '0',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1364 DEFAULT CHARSET=latin1;

INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (916, '1001', '1001', 'SIO', 'Shrimp Siomai', 39, 1, 1, '115', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (917, '1002', '1002', 'HAK', 'HAKAW', 39, 2, 1, '125', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (918, '1004', '1004', 'SP TAUSI', 'SPARERIBS WITH TAUSI', 39, 1, 1, '100', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (919, '1003', '1003', 'CF', 'Chicken Feet', 39, 1, 1, '105', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (920, '1005', '1005', 'BF DIM', 'BEEF BALL ', 39, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (921, '1006', '1006', 'Shark\'s Fin', 'SHARK\'S FIN DUMPLING', 39, 1, 1, '100', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (922, '1007', '1007', 'CUCHAY STEAMED', 'CU-CHAY DUMPLING STEAM', 39, 1, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (923, '1009', '1009', 'MACHANG', 'MACHANG', 39, 1, 1, '130', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (924, '1010', '1010', 'A PAO', 'ASADO SIOPAO', 39, 1, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (925, '1011', '1011', 'BOLA PAO', 'BOLA-BOLA SIOPAO', 39, 1, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (926, '1012', '1012', 'J PAO', 'JUMBO SIOPAO', 39, 1, 1, '108', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (927, '1013', '1013', 'Radish Cake', 'RADISH CAKE', 39, 1, 1, '90', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (928, '1014', '1014', 'CUAPAO STEAMED', 'CUA PAO STEAM', 39, 1, 1, '60', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (929, '1015', '1015', 'SPRROLL', 'SPRING ROLL', 39, 1, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (930, '1016', '1016', 'QE SIO', 'QUAIL EGG SIOMAI', 39, 1, 1, '90', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (931, '1019', '1019', 'JAP SIO', 'JAPANESE SIOMAI', 39, 1, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (932, '1022', '1022', 'KIKIAM', 'KIKIAM', 39, 1, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (933, '1023', '1023', 'FRDCUCHAY', 'FRIED CUCHAY', 39, 1, 1, '95', '2016-12-19 07:46:41', NULL, 0, 1, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (934, '2001', '2001', 'FISH CON', 'SLICED FISH CONGEE', 40, 1, 1, '205', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (935, '2002', '2002', 'PORK CE-R', 'PORK WITH  CENTURY EGG CONGEE-REGULAR', 40, 1, 1, '140', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (936, '2003', '2003', 'PORK CE-S', 'PORK WITH  CENTURY EGG CONGEE-SMALL', 40, 1, 1, '90', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (937, '2004', '2004', 'BOLA CON-R', 'BOLA-BOLA CONGEE-REG', 40, 1, 1, '140', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (938, '2005', '2005', 'BOLA CON-S', 'BOLA-BOLA CONGEE-SMALL', 40, 1, 1, '90', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (939, '2006', '2006', 'BF CON-R', 'SLICED BEEF CONGEE-R', 40, 1, 1, '130', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (940, '2007', '2007', 'BF CON-S', 'SLICED BEEF CONGEE-SMALL', 40, 1, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (941, '2008', '2008', 'PLAIN CON', 'PLAIN CONGEE', 40, 1, 1, '60', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (942, '2009', '2009', 'Century egg', 'CENTURY EGG', 40, 1, 1, '55', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (943, '2010', '2010', 'FRESH EGG', 'FRESH EGG', 40, 1, 1, '25', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (944, '3001', '3001', 'WANTON NOD-S', 'WANTON NOODLE SMALL', 41, 1, 1, '100', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (945, '300', '300', 'BF BRIS NOD-S', 'BEEF BRISKET NOODLE SNACK', 41, 1, 1, '100', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (946, '3002', '3002', 'BF BRIS NOD-R', 'BEEF BRISKET NOODLE REGULAR', 41, 1, 1, '190', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (947, '3003', '3003', 'ASADO NOD-R', 'ASADO NOODLE REGULAR', 41, 1, 1, '175', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (948, '3004', '3004', 'ANY 2 KIND COMBI', 'ANY 2 KIND COMBI', 41, 1, 1, '170', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (949, '3005', '3005', 'WANTONSOUP', 'WANTON SOUP', 41, 1, 1, '165', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (950, '3006', '3006', 'PLAIN NOD', 'PNOODLESOUP', 41, 1, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (951, '3007', '3007', 'ADDSEAWEEDS', 'ADDITIONAL SEAWEEDS', 41, 1, 1, '40', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (952, '3008', '3008', 'WANTONNOD-R', 'WANTON NOODLE REGULAR', 41, 1, 1, '175', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (953, '3010', '3010', 'ASADO NOD-S', 'ROASTED PORK ASADO NOODLE', 41, 1, 1, '105', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (954, '501', '501', 'BROC', 'BROCOLLI FLOWER W/ GARLIC', 42, 1, 1, '220', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (955, '502', '502', 'KAYLAN', 'KAYLAN W/ GARLIC', 42, 1, 1, '180', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (956, '503', '503', 'T PECHAY', 'TAIWAN PECHAY W/ GARLIC', 42, 1, 1, '180', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (957, '5001', '5001', 'HOTOTAY-S', 'HO TO TAY SOUP SMALL', 43, 1, 1, '265', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (958, '5002', '5002', 'POLONCHAY-S', 'POLONCHAY SEAFOOD SOUP SMALL', 43, 1, 1, '260', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (959, '5003', '5003', 'CORNCRABSOUP-S', 'SWEET CORN W/ CRAB MEAT SOUP SMALL', 43, 1, 1, '180', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (960, '6011', '6011', 'HOTOTAY-R', 'HO TO TAY SOUP REGULAR', 43, 1, 1, '385', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (961, '5005', '5005', 'POLONCHAY-R', 'POLONCHAY SEAFOOD SOUP REGULAR', 43, 1, 1, '415', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (962, NULL, NULL, 'NIDO CORN SOUP-R', 'NIDO WITH SWEET CORN SOUP-R', 43, 1, 1, '285', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (963, NULL, NULL, 'NIDO CORN SOUP-S', 'NIDO WITH SWEET CORN SOUP-S', 43, 1, 1, '180', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (964, '5006', '5006', 'CORNCRABSOUP-R', 'SWEET CORN W/ CRAB MEAT SOUP REGULAR', 43, 1, 1, '270', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (965, '6001', '6001', 'SSP', 'SWEET AND SOUR PORK', 44, 1, 1, '305', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (966, '6002', '6002', 'SPSP', 'FRIED SPARERIBS WITH SALT N\' PEPPER', 44, 1, 1, '327', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (967, '6003', '6003', 'SP OK', 'FRIED SPARERIBS WITH O.K. SAUCE', 44, 1, 1, '345', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (968, '6004', '6004', 'L SHANG', 'LUMPIA SHANGHAI', 44, 1, 1, '280', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (969, '6005', '6005', 'F CHIX', 'HAP CHAN CRISPY FRIED CHICKEN-WHOLE', 44, 1, 1, '438', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (970, '6006', '6006', 'L CHIX', 'FRIED CHICKEN FILLET WITH LEMON SAUCE', 44, 1, 1, '310', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (971, '6007', '6007', 'SS CHIX ', 'SWEET AND SOUR CHICKEN FILLET', 44, 1, 1, '290', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (972, '6008', '6008', 'B. CHIX', 'FRIED BUTTERED CHICKEN', 44, 1, 1, '295', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (973, '6009', '6009', 'BF BROC', 'SAUTEED BEEF WITH BROCCOLI FLOWER', 44, 1, 1, '280', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (974, '6010', '6010', 'CHOPSUEY', 'CHOP SUEY GUISADO', 44, 1, 1, '285', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (975, '6011', '6011', 'FFG', 'STEAMED FISH FILLET WITH GARLIC', 44, 1, 1, '450', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (976, '6012', '6012', 'SSFF', 'SWEET AND SOUR FISH FILLET', 44, 1, 1, '425', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (977, '6013', '6013', 'FFSP', 'FRIED FISH FILLET WITH SALT N\' PEPPER', 44, 1, 1, '425', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (978, '6014', '6014', 'Camaron', 'FRIED SHRIMP (CAMARON REBUSADO)', 44, 1, 1, '410', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (979, '6015', '6015', 'SQSP', 'FRIED SQUID WITH SALT N\' PEPPER', 44, 1, 1, '300', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (980, '6016', '6016', 'RAINBOWCHIX', 'RAINBOW SLICED CHICKEN', 44, 1, 1, '260', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (981, '6017', '6017', 'CHIX SHRIMP SALAD', 'CHIX SHRIMP SALAD', 61, 1, 1, '515', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (982, '6018', '6018', 'SP PLUM', 'SPARERIBS W/ PLUM SAUCE', 44, 1, 1, '285', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (983, '6019', '6019', 'MM TAUFU', 'MIXED MEAT W/ TAUFU', 44, 1, 1, '250', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (984, '7001', '7001', 'YCFR', 'YANG CHOW FRIED RICE', 45, 1, 1, '290', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (985, '7002', '7002', 'SALTED RICE', 'DICED CHICKEN WITH SALTED FISH FRIED RICE', 45, 1, 1, '225', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (986, '7003', '7003', 'GARLIC RICE', 'GARLIC FRIED RICE', 45, 1, 1, '170', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (987, '7004', '7004', 'BF HOFAN', 'SLICED BEEF FRIED HOFAN', 45, 1, 1, '195', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (988, '7005', '7005', 'MMC', 'MIXED MEAT CANTON', 45, 1, 1, '240', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (989, '7006', '7006', 'SF CANTON', 'SEAFOOD CANTON', 45, 1, 1, '310', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (990, '7007', '7007', 'BIHON G', 'PANCIT BIHON GUISADO', 45, 1, 1, '250', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (991, '7008', '7008', 'BDAY NOD', 'BIRTHDAY NOODLE', 45, 1, 1, '270', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (992, '7009', '7009', 'P. RICE', 'PLAIN RICE', 45, 1, 1, '40', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (993, '8001', '8001', 'SSP RT', 'SWEET & SOUR PORK TOPPINGS', 46, 1, 1, '145', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (994, '8002', '8002', 'FF CORN RT', 'FISH FILLET W/ CORN SAUCE TOPPINGS', 46, 1, 1, '145', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (995, '8003', '8003', 'BF BRISKET RT', 'BEEF BRISKET W/ VEGETABLES', 46, 1, 1, '145', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (996, '8004', '8004', 'ASADO W/ EGG RT', 'ASADO W/ FRIED EGG', 46, 1, 1, '145', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (997, '8005', '8005', 'CHOPSUEY RT', 'CHOP SEUY GUISADO', 46, 1, 1, '145', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (998, '8006', '8006', 'SPSP  RT', 'SALT & PEPPER SPARERIBS', 46, 1, 1, '145', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (999, '8007', '8007', 'B CHIX RT', 'BUTTER CHICKEN', 61, 1, 1, '145', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1000, '8008', '8008', 'ASADO W/ CHIX RT', 'ASADO W/ CHICKEN', 46, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1001, '8009', '8009', '2 CS W/ LIEMPO RT', 'TWO-KIND CHINESE SAUSAGE W/ LIEMPO', 46, 1, 1, '125', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1002, '8010', '8010', 'F SLICED BF', 'CRISPY FRIED SLICED BEEF', 46, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1003, '8011', '8011', 'CHIX MUSH', 'CHINESE SAUSAGE W/ CHICKEN & MUSHROOM', 61, 1, 1, '145', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1004, '8012', '8012', 'SP w/ CF Top', 'SPARERIBS W/ CHICKEN FEET', 46, 1, 1, '125', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1005, '9001', '9001', 'SPCL DESSERT', 'HAP CHAN SPECIAL DESSERT', 47, 2, 1, '85', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1006, '9002', '9002', 'MANGO SAGO', 'MANGO SAGO', 47, 2, 1, '85', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1007, '9003', '9003', 'TAHO', 'CHILLED TAHO', 47, 2, 1, '40', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1008, '9004', '9004', 'TAHO SAGO', 'CHILLED TAHO W/ SAGO', 47, 2, 1, '55', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1009, '9005', '9005', 'TAHO MANGO', 'CHILLED TAHO W/ MANGO', 47, 2, 1, '70', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1010, '9006', '9006', 'TAHO MANGO SAGO', 'CHILLED TAHO W/ MANGO & SAGO', 47, 2, 1, '75', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1011, '9007', '9007', 'BUTCHI', 'BUTCHI', 47, 1, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1012, '9008', '9008', 'A JELLY', 'ALMOND JELLY', 47, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1013, '9009', '9009', 'BG W/ SAGO', 'BLACK GULAMAN W/ SAGO', 47, 2, 1, '70', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1014, '9010', '9010', 'BWG', 'BLACK & WHITE GULAMAN', 47, 2, 1, '75', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1015, '9011', '9011', 'A W/ LYCHEE', 'ALMOND JELLY W/ LYCHEE', 47, 2, 1, '85', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1016, '9012', '9012', 'T CAKES', 'THOUSAND CAKES', 47, 1, 1, '85', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1017, '9014', '9014', 'BICHUALMD', 'BICHU ALA MODE', 47, 1, 1, '75', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1018, '9015', '9015', 'TSTDCUAPAO', 'TOASTED CUAPAO ALA MODE', 47, 1, 1, '70', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1019, '9016', '9016', 'CHILLDTAHOAMD', 'CHILLED TAHO ALA MODE', 47, 2, 1, '70', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1020, '10001', '10001', 'HOTLemsoda', 'Hong Kong Hot Lemon Soda', 48, 2, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1021, '10002', '10002', 'HKHOTTEA', 'Hong Kong Hot Lemon Tea', 48, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1022, '10003', '10003', 'HOTMT', 'Hong Kong Hot Milk Tea', 48, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1023, '10004', '10004', 'HK ILT', 'Hong Kong Iced Lemon Tea', 48, 2, 1, '85', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1024, '10005', '10005', 'HK LEMONADE', 'Hong Kong Iced Lemonade', 48, 2, 1, '75', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1025, '10006', '10006', 'ICED LEMONSODA', 'ICED LEMONSODA', 48, 2, 1, '85', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1026, '10007', '10007', 'HK IMT', 'Hong Kong Iced Milk Tea', 48, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1027, '10008', '10008', 'ICJ', 'Iced Calamansi Juice', 48, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1028, '10009', '10009', 'SOYA', 'Soya Milk', 48, 2, 1, '55', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1029, '10010', '10010', 'SOFTDRKINCN', 'Soft drink In can', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1030, '10011', '10011', 'BOT. WATER', 'Mineral Water', 48, 2, 1, '45', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1031, '10012', '10012', 'SMB PILSEN', 'San Miguel Beer Pale Pilsen', 48, 2, 1, '110', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1032, '10013', '10013', 'SMB LIGHT', 'San Miguel Beer Light', 48, 2, 1, '110', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1033, '10014', '10014', 'Mango Shake', 'Ripe Mango Shake', 48, 2, 1, '75', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1034, '10015', '10015', 'WATER MELON SHAKE', 'Watermelon Shake', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1035, '10016', '10016', 'PAS', 'Pineapple Shake', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1036, '10017', '10017', '4S', 'Four Season Shake', 48, 2, 1, '145', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1037, '10018', '10018', 'ICECRMCOF', 'ICE CREAM COFFEE', 48, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1038, '10019', '10019', 'ICECRMMLK', 'ICE CREAM MILK TEA', 48, 2, 1, '75', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1039, '10020', '10020', 'Old StrawSoya Shake', 'OLD STRAWBERRY SOYA MILK SHAKE', 48, 2, 1, '60', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1040, '10021', '10021', 'OLD ubesoya shake', 'OLD UBE SOYA MILK SHAKE', 48, 2, 1, '60', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1041, '10022', '10022', 'old vanillasoya shk', 'old VANILLA SOYA MILK SHAKE', 48, 2, 1, '60', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1042, '10023', '10023', 'old Mango Soya shake', 'OLD MANGO SOYA MILK SHAKE', 48, 2, 1, '60', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1043, '10024', '10024', 'OLD CHOCOSOYA', 'OLD CHOCOLATE SOYA MILK SHAKE', 48, 2, 1, '60', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1044, '10071', '10071', 'CU-CHAY FRIED', 'CU-CHAY DUMPLING FRIED', 39, 1, 1, '95', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1045, '10081', '10081', 'SD STEAMED', 'SHRIMP DUMPLING STEAM', 39, 1, 1, '80', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1046, '10141', '10141', 'CUAPAO FRIED', 'CUA PAO FRIED', 39, 1, 1, '60', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1047, '10171', '10171', 'BEANCURD FR', 'BEANCURD ROLL FRIED', 39, 1, 1, '90', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1048, '30011', '30011', 'WANTON NOD-R', 'WANTON NOODLE REGULAR', 41, 1, 1, '175', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1049, '401', '401', 'PORK ASADO', 'Roasted Pork Asado', 49, 1, 1, '225', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1050, '402', '402', 'JELLYFISH W/ CE', 'Jellyfish with Century Egg', 49, 1, 1, '320', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1051, '403', '403', 'SOYED TOFU', 'Soyed Tofu', 49, 1, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1052, '404', '404', 'L KAWALI', 'Lechon Kawali', 49, 1, 1, '360', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1053, '1113', '1113', 'BICHU', 'FRIED BICHU', 47, 1, 1, '50', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1054, '1211', '1211', 'MINERAL', 'MINERAL WATER', 48, 2, 1, '45', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1055, '100141', '100141', 'GMS', 'Green Mango Shake', 48, 2, 1, '75', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1056, 'SFTCN00001', 'SFTCN00001', 'P REG', 'PEPSI REGULAR', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1057, 'SFTCN00002', 'SFTCN00002', 'P LIGHT', 'PEPSI LIGHT', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1058, 'SFTCN00003', 'SFTCN00003', 'P MAX', 'PEPSI MAX', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1059, 'SFTCN00004', 'SFTCN00004', 'MUG', 'MUG', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1060, 'SFTCN00005', 'SFTCN00005', '7UP', '7UP', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1061, 'SFTCN00006', 'SFTCN00006', 'M DEW', 'MOUNTAIN DEW', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1062, 'UMB001', 'UMB001', 'Umbrella', 'Umbrella', 50, 3, 1, '250', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1063, 'sauce001', 'sauce001', 'chilisauce', 'chili sauce', 50, 1, 1, '150', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1064, 'FR001', 'FR001', 'Lumpia', 'Lumpiang Shanghai', 51, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1065, 'ICED 001', 'ICED 001', 'IT REG', 'ICED TEA REGULAR', 48, 2, 1, '50', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1066, 'ICED 003', 'ICED 003', 'BIT', 'BOTTOMLESS ICED TEA', 48, 2, 1, '90', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1067, 'KRO0001', 'KRO0001', 'KROPEK', 'KROPEK', 44, 1, 1, '65', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1068, 'PIT001', 'PIT001', 'PIT ', 'ICED TEA PITCHER', 48, 2, 1, '260', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1069, 'LEM003', 'LEM003', 'Lem Glass', 'REGULAR LEMONADE', 48, 2, 1, '50', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1070, 'LEM0004', 'LEM0004', 'PLEM', 'LEMONADE PITCHER', 48, 2, 1, '260', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1071, 'RT00000010', 'RT00000010', 'CSW/CHIXMUSH', 'CHINESE SAUSAGE W/ CHICKEN AND mUSHROOM', 46, 1, 1, '105', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1072, '15201401', '15201401', 'Original Tikoy', 'Original Tikoy', 52, 1, 1, '135', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1073, '15201402', '15201402', 'Ube Tikoy', 'Ube Tikoy', 52, 1, 1, '140', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1074, '15201403', '15201403', 'Mango Tikoy', 'Mango Tikoy', 52, 1, 1, '140', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1075, '202014', '202014', 'FREE Eco Bag', 'FREE Eco Bag', 52, 3, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1076, '202014', '202014', 'Eco Bag', 'Eco Bag', 52, 3, 1, '30', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1077, '12', '12', 'HC Chili Sauce', 'HC Chili Sauce', 50, 1, 1, '150', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1078, 'incan001', 'incan001', 'Mirinda', 'Mirinda', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1079, 'LEMPIT001', 'LEMPIT001', 'Pink PIT', 'PINK LEMONADE PITCHER', 48, 2, 1, '200', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1080, 'ROAST001', 'ROAST001', 'SOYED CHIX', 'Soyed Chicken', 49, 1, 1, '200', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1081, '0000le', '0000le', 'B.LEMONADE', 'BOTTOMLESS LEMONADE', 48, 2, 1, '90', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1082, 'others001', 'others001', 'Kropek', 'Kropek', 50, 1, 1, '65', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1083, '1', '1', 'Plastic Glass', 'Plastic Glass', 53, 3, 1, '4', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1084, '2', '2', 'CP 1000', 'CP 1000 w/ cover', 53, 3, 1, '10', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1085, '3', '3', 'CP 750', 'CP 750 w/ cover', 53, 3, 1, '8', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1086, '4', '4', 'CC 520', 'CC 520 w/ Lid', 53, 3, 1, '8', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1087, '5', '5', 'CC 390', 'CC 390 w/ Lid', 53, 3, 1, '7', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1088, '6', '6', 'Hamburger Box', 'Hamburger Box', 53, 3, 1, '8', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1089, '7', '7', 'Lunch Box', 'Lunch Box', 53, 3, 1, '9', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1090, '8', '8', 'Jumbo Box', 'Jumbo Box', 53, 3, 1, '10', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1091, '9', '9', 'CC 750', 'CC 750', 53, 3, 1, '10', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1092, '10', '10', 'HC Pouch Small', 'HC Pouch Small', 53, 3, 1, '5', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1093, '11', '11', 'HC Pouch Medium', 'HC Pouch Medium', 53, 3, 1, '6', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1094, '12', '12', 'HC Hand Bag', 'HC Hand Bag', 53, 3, 1, '20', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1095, '1115', '1115', 'Masachi', 'Masachi', 47, 1, 1, '50', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1096, '1115', '1115', 'F.NOD w/ MM', 'F.NOD w/ MM', 45, 1, 1, '320', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1097, '12715', '12715', 'CORKAGE ', 'CORKAGE (DRINKS)', 53, 3, 1, '200', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1098, '1', '1', 'P.J (Reg)', 'PINEAPPLE JUICE (REG)', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1099, 'SET MENU A 6PAX', 'SET MENU B 6PAX', 'SET B(6PAX)', 'SET B GOOD FOR 6', 54, 1, 1, '2240', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1100, 'SET MENU A 6 PA', 'SET MENU A 6 PA', 'SET A(6PAX)', 'SET A GOOD FOR 6', 54, 1, 1, '1950', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1101, 'SET MENU A 10 P', 'SET MENU A 10 P', 'SET A(12PAX)', 'SET A GOOD FOR 12', 55, 1, 1, '5300', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1102, 'SET MENU B 10 P', 'SET MENU B 10 P', 'SET B(12PAX)', 'SET B GOOD FOR 12', 55, 1, 1, '4800', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1103, '6', '6', 'CORN CRAB', 'CORN CRAB SOUP', 56, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1104, '6', '6', 'RPA', 'ROASTED PORK ASADO', 56, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1105, '6', '6', 'YC', 'YANG CHOW', 56, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1106, '6', '6', 'LS', 'LUMPIA SHANGHAI', 56, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1107, '6', '6', 'L.CHIX', 'LEMON CHICKEN', 56, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1108, '6', '6', 'CTMS', 'TAHO MANGO SAGO', 56, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1109, '6', '6', 'LECHON MACAU', 'LECHON MACAU', 56, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1110, '10', '10', 'L.SHANG', 'LUMPIA SHANGHAI', 57, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1111, '10', '10', 'LEMON CHICKEN', 'LEMON CHICKEN', 57, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1112, '10', '10', 'STEAMED FISH', 'STEAMED FISH', 57, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1113, '10', '10', 'YANG CHOW', 'YANG CHOW', 57, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1114, '10', '10', 'BUTTER CHICKEN', 'BUTTER CHICKEN', 57, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1115, '10', '10', 'CAMARON', 'CAMARON REBUSADO', 57, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1116, '10', '10', 'LECHON KAWALI', 'LECHON KAWALI', 57, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1117, '10', '10', 'SP W/ OK SAUCE', 'SP W/ OK SAUCE', 57, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1118, '10', '10', 'SWEET N\' SOUR FISH', 'SWEET N\' SOUR FISH', 57, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1119, '10', '10', 'SQUID SALT N\' PEPPER', 'SQUID SALT N\' PEPPER', 57, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1120, '6', '6', 'PINK LEM (FREE)', 'FREE PINK LEMONADE', 56, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1121, '6', '6', 'CUCUMBER (FREE)', 'FREE CUCUMBER', 56, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1122, '6', '6', 'DALANDAN (FREE)', 'FREE DALANDAN ', 56, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1123, '6', '6', 'AJ W/ LYCHEE', 'AJ W/ LYCHEE', 56, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1124, 'ice cream', 'ice cream', 'bichu a la mode', 'bichu a la mode', 47, 2, 1, '75', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1125, 'ice cream', 'ice cream', 'cuapao a la mode', 'toasted cuapao a la mode', 47, 2, 1, '70', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1126, 'ice cream', 'ice cream', 'taho a la mode', 'chilled taho a la mode', 47, 2, 1, '70', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1127, 'milk shake', 'milk shake', 'ice cream coffee', 'ice cream coffee', 48, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1128, 'milk shake', 'milk shake', 'ice cream milk tea', 'ice cream milk tea', 48, 2, 1, '75', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1129, 'milk shake', 'milk shake', 'strawberry shake', 'strawberry soya milk shake', 48, 2, 1, '60', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1130, 'milk shake', 'milk shake', 'vanilla soya shake', 'vanilla soya milk shake', 48, 2, 1, '60', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1131, 'milk shake', 'milk shake', 'mango milk shake', 'mango soya milk shake', 48, 2, 1, '60', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1132, 'milk shake', 'milk shake', 'whip ube soya shake', 'ube soya milk shake with whip', 48, 2, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1133, 'milk shake', 'milk shake', 'choco soyamilk shake', 'chocolate soya milk shake', 48, 2, 1, '60', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1134, 'SUNDAE', 'SUNDAE', 'Choco sundae', 'chocolate sundae', 47, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1135, 'SUNDAE', 'SUNDAE', 'Vanilla sundae', 'vanilla sundae', 47, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1136, 'SUNDAE', 'SUNDAE', 'Straw Sundae', 'Strawberry Sundae', 47, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1137, 'SUNDAE', 'SUNDAE', 'Ube Sundae', 'Ube Sundae', 47, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1138, 'SUNDAE', 'SUNDAE', 'Mango Sundae', 'Mango Sundae', 47, 2, 1, '65', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1139, 'SPLIT', 'SPLIT', 'Banana Split', 'Banana Split', 47, 2, 1, '135', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1140, 'Milk Shake', 'Milk Shake', 'Choco Milk Shake', 'Chocolate Milk Shake ', 48, 2, 1, '85', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1141, 'milk shake', 'milk shake', 'Mango Milk Shake', 'Mango Milk Shake', 48, 2, 1, '85', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1142, 'Milk Shake', 'Milk Shake', 'Vanilla milk shake', 'vanilla milk shake', 48, 2, 1, '85', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1143, 'Milk Shake', 'Milk Shake', 'Ube milk shake', 'ube milk shake', 48, 2, 1, '85', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1144, 'milk shake', 'milk shake', 'Straw milk shake', 'starw berry milk shake', 48, 2, 1, '85', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1145, 'BOTTOMLESS', 'BOTTOMLESS', 'B. PJ', 'bOTTOMLESS PINEAPPLE JUICE', 48, 2, 1, '75', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1146, 'PITCHER', 'PITCHER', 'PJ PITCHER', 'Pineapple pitcher', 48, 2, 1, '200', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1147, 'MILK TEA', 'MILK TEA', 'ICE CREAM TEA', 'ICE CREAM MILK TEA', 52, 2, 1, '75', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1148, 'MILK TEA', 'MILK TEA', 'ICE CREAM TEA FREE', 'ICE CREAM MILK TEA', 52, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1149, 'free', 'free', 'free IT', 'FREE ICED TEA RT', 46, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1150, 'FREE', 'FREE', 'free LEM', 'FREE LEMONADE', 46, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1151, 'free', 'free', 'free PJ', 'FREE PINEAPPLE JUICE', 46, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1152, 'FREE', 'FREE', 'free CUCUMBER', 'FREE CUCUMBER', 46, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1153, 'FREE', 'FREE', 'free PINK', 'FREE PINK LEMONADE', 46, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1154, 'FREE', 'FREE', 'free DALANDAN', 'FREE DALANDAN', 46, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1155, 'FREE P.APPLE P ', 'FREE P.APPLE P ', 'FREE PJ PIT', 'FREE PINEAPPLE PITCHER', 57, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1156, 'FREE LEMONADE', 'FREE LEMONADE', 'FREE LEM PIT', 'FREE LEMONADE PITCHER', 57, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1157, '00002FNSF', '00002FNSF', 'FRIED NOD W/ SEAFOOD', 'FRIED NOODLES W/ SEAFOOD', 45, 1, 1, '320', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1158, 'FREE', 'FREE', 'FREE IT PIT', 'FREE ICED TEA PITCHER', 57, 2, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1159, 'classics', 'classics', 'SP FRUIT', 'Spareribs with fruit cocktail and salad sauce', 44, 1, 1, '430', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1160, 'classics', 'classics', 'Honey Chix', 'Braised Chicken with sesame seeds and Honey Sauce', 44, 1, 1, '385', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1161, 'classics', 'classics', 'BF SCRAMBLED', 'Beef With Scrambled Egg', 44, 1, 1, '253', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1162, 'DRINKS', 'DRINKS', 'OJ', 'ORANGE JUICE', 48, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1163, 'cc001', 'cc001', '3 kind Assd. C cuts', '3 kind Assorted Cold Cuts', 49, 1, 1, '240', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1164, 'cc002', 'cc002', '5 kind Assd. C cuts', '5 kind Assorted Cold Cuts', 49, 1, 1, '430', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1165, 'NOOD001', 'NOOD001', '(P)WANTON NOD-S', 'WANTON NOODLE SMALL PROMO', 41, 1, 1, '72', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1166, 'NOOD002', 'NOOD002', '(P) BF BRIS NOD-S', 'BEEF BRISKET NOODLE SMALL PROMO', 41, 1, 1, '72', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1167, 'NOOD003', 'NOOD003', '(P)ASADO NOD-S', 'ASADO NOODLE SMALL PROMO', 41, 1, 1, '72', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1168, 'SOUP001', 'SOUP001', '(P)HOTOTAY-S', 'HOTOTAY SOUP PROMO', 43, 1, 1, '207', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1169, 'SOUP002', 'SOUP002', '(P)POLONCHAY -S', 'POLONCHAY SMALL PROMO', 43, 1, 1, '207', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1170, 'SOUP003', 'SOUP003', '(P)CORN CRAB -S', 'CORN AND CRAB SMALL PROMO', 43, 1, 1, '180', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1171, 'CON001', 'CON001', '(P)PORK CE-S', 'PORK WITH CENTURY EGG CONGEE SMALL PROMO', 40, 1, 1, '68', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1172, 'CON002', 'CON002', '(P)BOLA CONGEE-S', 'BOLA-BOLA CONGEE SMALL PROMO', 40, 1, 1, '64', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1173, 'CON003', 'CON003', '(P)BF CONGEE-S', 'SLICED BEEF CONGEE SMALL PROMO', 40, 1, 1, '64', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1174, '90415', '90415', 'MOON CAKE/BOX', 'MOON CAKE', 52, 1, 1, '390', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1175, '90415', '90415', 'MOON CAKE/PC', 'MOON CAKE / PIECE', 52, 1, 1, '100', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1176, 'PROMO01', 'PROMO01', 'BDAY NOD free', 'FREE BIRTHDAY NOODLE', 52, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1177, 'PROMO002', 'PROMO002', 'CHEF PAO (P)', 'CHEF PAO PROMO', 52, 3, 1, '180', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1178, 'PROMO003', 'PROMO003', 'CHEF PAO', 'CHEF PAO', 52, 3, 1, '350', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1179, '9017', '9017', 'mango  sago alamode', 'dessert', 47, 1, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1180, '4', '4', 'Free 1/4 tikoy', 'Free 1/4 tikoy', 52, 1, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1181, '2', '2', 'YC HALF', 'YANG CHOW HALF', 45, 1, 1, '150', '2016-12-19 07:46:41', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1182, '1', '1', 'TOMATO CFUD', 'TOMATO SEAFOOD CANTON', 45, 1, 1, '280', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1183, '3', '3', 'PATATIM', 'PATATIM W/O CUAPAO', 44, 1, 1, '610', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1184, '4', '4', 'PATATIM W/ CUAPAO', 'PATATIM W/ CUAPAO', 44, 1, 1, '680', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1185, '5', '5', 'BF RADISH', 'BEEF BRISKET W/ RADISH', 44, 1, 1, '330', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1186, '6', '6', 'HOT SOUR SMALL', 'HOT AND SOUR SOUP SMALL', 43, 1, 1, '230', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1187, '7', '7', 'HOT SOUR REG', 'HOT AND SOUR SOUP REGULAR', 43, 1, 1, '355', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1189, '9', '9', 'NIDO QUAIL -R', 'NIDO QUAIL -R', 43, 1, 1, '285', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1190, '10', '10', 'CHOCO SOYA', 'CHOCO SOYA', 47, 2, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1191, '11', '11', 'STRAW SOYA', 'STRAW SOYA', 47, 2, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1192, '12', '12', 'VANILLA SOYA', 'VANILLA SOYA', 47, 2, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1193, '13', '13', 'CHOCO BANANA PAR', 'CHOCO BANANA PARFAIT', 47, 2, 1, '140', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1194, '14', '14', 'LEMON MANGO PAR', 'LEMON MANGO PARFAIT', 47, 2, 1, '150', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1195, '15', '15', 'MANGO BANANA PAR', 'MANGO MANGO PARFAIT', 47, 2, 1, '160', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1196, '16', '16', 'free umbrella', 'free umbrella', 52, 3, 1, '0', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1197, '18', '18', 'BARKADA A', 'BARKADA BUNDLE SET A', 52, 1, 1, '575', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1198, '19', '19', 'BARKADA B', 'BARKADA BUNDLE SET B', 52, 1, 1, '575', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1199, '20', '20', 'BARKADA C', 'BARKADA BUNDLE SET C', 52, 1, 1, '575', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1200, '221', '221', 'BARKADA D', 'BARKADA BUNDLE SET D', 52, 1, 1, '575', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1201, '222', '222', 'BARKADA E', 'BARKADA BUNDLE SET E', 52, 1, 1, '675', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1202, '223', '223', 'UPGRADE YANGCHOW', 'BARKADA SET UPGRADED YANG CHOW RICE', 52, 1, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1203, '224', '224', 'UPGRADE PIT', 'BARKADA SET UPGRADED PIT', 52, 2, 1, '80', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1204, '225', '225', 'whip vanilla soya', 'vanilla soya shake with whip', 48, 2, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1205, 'milkshake', 'milkshake', 'whip chocosoya shake', 'choco soya milk shake with whip', 48, 2, 1, '95', '2016-12-19 07:46:41', NULL, 0, NULL, 0, NULL);
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1206, '1', '1', 'PATA HALF CUAP', 'PATATIM HALF WITH CUAPAO', 44, 1, 0, '300', '2017-07-20 14:03:18', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1207, '1', '1', 'PATA HALF W/O CUAP', 'PATATIM HALF W/O CUAPAO', 44, 1, 0, '285', '2017-07-20 14:03:47', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1208, '1', '1', 'BF CHINESE', 'BEEF CHINESE STYLE', 44, 1, 0, '367', '2017-07-20 14:04:31', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1209, '1', '1', 'BF GINGER', 'BEEF W/ GINGER AND ONION', 61, 1, 0, '345', '2017-07-20 14:04:54', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1210, '1', '1', 'BF PEPPER', 'BEEF W/ BLACK PEPPER SAUCE', 44, 1, 0, '378', '2017-07-20 14:05:21', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1211, '1', '1', 'BF TOMATO', 'BEEF W/ TOMATO STYLE', 44, 1, 0, '270', '2017-07-20 14:06:27', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1212, '1', '1', 'BF MANGO', 'BEEF W/ MANGO', 44, 1, 0, '315', '2017-07-20 14:07:46', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1213, '1', '1', 'CHIX SHRIMP SALAD', 'CHICKEN SHRIMP SALAD', 44, 1, 0, '515', '2017-07-20 14:08:23', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1214, '1', '1', 'SPICY SUAHE', 'SPICY SUAHE SALT & PEPPER', 44, 1, 0, '300', '2017-07-20 14:09:01', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1215, '8010', '8010', 'F SLICED BF', 'CRISPY FRIED SLICED BEEF', 46, 1, 1, '0', '2017-07-20 14:17:27', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1216, '1', '1', 'SSP TRAY', 'SWEET AND SOUR PORK', 58, 1, 0, '950', '2017-07-20 14:42:22', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1217, '1', '1', 'LUMP SHANG TRAY', 'LUMPIA SHANGHAI', 58, 1, 0, '799', '2017-07-20 14:42:50', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1218, '1', '1', 'SPSP TRAY', 'SPARERIBS W/ SALT & PEPPER', 58, 1, 0, '955', '2017-07-20 14:43:25', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1219, '1', '1', 'SS CHIX TRAY', 'SWEET & SOUR CHICKEN FILLET', 58, 1, 0, '840', '2017-07-20 14:43:51', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1220, '1', '1', 'BUT CHIX TRAY', 'FRIED BUTTERED CHICKEN', 58, 1, 0, '965', '2017-07-20 14:44:24', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1221, '1', '1', 'BF BROC TRAY', 'BEEF W/ BROCCOLI FLOWER', 58, 1, 0, '930', '2017-07-20 14:45:08', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1222, '1', '1', 'CHOP SUEY TRAY', 'CHOP SUEY GUISADO', 58, 1, 0, '960', '2017-07-20 14:45:33', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1223, '1', '1', 'SSFF TRAY', 'SWEET AND SOUR FISH FILLET', 58, 1, 0, '1085', '2017-07-20 14:45:56', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1224, '1', '1', 'FFSP TRAY', 'FISH FILLET W/ SALT AND PEPPER', 58, 1, 0, '1070', '2017-07-20 14:46:50', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1225, '1', '1', 'CFUD CANTON TRAY', 'SEAFOOD CANTON TRAY', 58, 1, 0, '760', '2017-07-20 14:47:51', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1226, '1', '1', 'BIHON TRAY', 'BIHON GUISADO', 58, 1, 0, '675', '2017-07-20 14:48:24', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1227, '1', '1', 'BDAY NOODLE TRAY', 'BIRTHDAY NOODLE', 58, 1, 0, '715', '2017-07-20 14:48:43', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1228, '1', '1', 'MIX MEAT TRAY', 'MIXED MEAT CANTON', 58, 1, 0, '640', '2017-07-20 14:49:06', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1229, '1', '1', 'YC TRAY', 'YANG CHOW FRIED RICE', 58, 1, 0, '725', '2017-07-20 14:49:25', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1230, '1', '1', 'SALTED RICE TRAY', 'DICED CHICKEN W/ SALTED FISH RICE', 58, 1, 0, '615', '2017-07-20 14:50:40', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1231, '1', '1', 'FF BROC', 'FISH FILLET W/ BROCCOLI WITH FLOWER', 44, 1, 0, '435', '2017-07-20 14:52:59', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1232, '1', '1', 'FREE LUMPIA SHANG', 'FREE 4 EVRY 1,500 TRANSACTION IN EAST WEST', 52, 1, 0, '0', '2017-07-20 14:53:31', NULL, 0, 1, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1233, '1', '1', 'FREE YC', 'FREE 4 EVRY 1,500 TRANSACTION USING SM RWR CARD', 52, 1, 0, '0', '2017-07-20 14:54:20', NULL, 0, 1, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1234, '1', '1', 'SHRIMP BROC', 'SHRIMP BROCCOLI ', 44, 1, 1, '470', '2017-07-20 14:54:44', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1235, '1', '1', 'SET MEAL A(10pax)', 'SET A', 57, 1, 0, '5000', '2017-07-20 14:59:01', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1236, '1', '1', 'SET MEAL B(10pax)', 'SET B', 57, 1, 0, '5700', '2017-07-20 14:59:16', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1237, '1', '1', 'FISH TOFU', 'FISH FILLET TOFU', 59, 1, 0, '330', '2017-07-20 15:01:43', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1238, '1', '1', 'LECHON KAWALI HP', 'LECHON KAWALI', 59, 1, 0, '275', '2017-07-20 15:02:12', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1239, '1', '1', 'TOFU ASADO HP', 'TOFU ASADO', 59, 1, 0, '230', '2017-07-20 15:02:51', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1240, '1', '1', 'SEAFOOD TOFU HP', 'SEAFOOD TOFU ', 59, 1, 0, '395', '2017-07-20 15:03:09', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1241, '1', '1', 'MINCED PORK HP', 'MINCED PORK W/ EGGPLANT', 59, 1, 0, '275', '2017-07-20 15:03:30', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1242, '1', '1', 'CORKAGE FOOD', 'CORKAGE FOOD', 53, 3, 0, '200', '2017-07-20 15:06:14', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1243, '1', '1', 'ATSARA', 'ATSARA', 50, 1, 0, '30', '2017-07-20 15:10:30', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1244, '1', '1', 'MEAL 1', 'MEAL 1', 60, 1, 0, '170', '2017-07-20 15:11:18', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1245, '1', '1', 'MEAL 2', 'MEAL 2', 60, 1, 0, '170', '2017-07-20 15:11:28', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1246, '1', '1', 'MEAL 3', 'MEAL 3', 60, 1, 0, '175', '2017-07-20 15:11:38', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1247, '1', '1', 'MEAL 4', 'MEAL 4', 60, 1, 0, '195', '2017-07-20 15:11:47', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1248, '20', '20', 'p.soup', 'plain soup', 41, 1, 0, '20', '2017-07-21 17:22:45', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1249, '1', '1', 'Yang chow', 'free yang chow for sm advantage card user', 51, 1, 1, '0', '2017-07-23 11:02:00', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1250, '1', '1', 'free spring roll', 'free spring roll on tues', 52, 1, 1, '0', '2017-08-08 18:53:23', NULL, 0, 1, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1251, '1', '1', 'PCEC 100', 'PCEC P100 FOR WED', 52, 1, 1, '100', '2017-08-09 20:55:18', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1252, '1', '1', 'BOLA CON 100', 'BOLA CON P100 FOR WED', 52, 1, 1, '100', '2017-08-09 20:56:13', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1253, '1', '1', 'BEEF CON 100', 'BEEF CON P100 FOR WED', 52, 1, 1, '100', '2017-08-09 20:56:34', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1254, '1', '1', '50% MORE NOD (BWN)', '50% MORE NOODLES FOR BWN MONDAY', 52, 1, 1, '0', '2017-08-09 20:57:39', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1255, '1', '1', 'free sharksfin', 'free sharksfin thursday', 52, 1, 1, '0', '2017-08-09 20:58:46', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1256, '1', '1', 'Nido corn 200', 'nido corn 200 for friday', 52, 1, 1, '200', '2017-08-09 20:59:25', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1257, '1', '1', 'free s&s chicken ', 'free sweet n sour chicken 200', 52, 1, 1, '0', '2017-08-09 21:01:12', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1258, '1', '1', 'addtl evap', 'additional evap milk', 53, 2, 1, '10', '2017-08-12 12:00:38', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1259, '1', '1', 'addtl lemon', 'additional lemon', 53, 2, 1, '10', '2017-08-12 12:02:22', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1260, '1', '1', 'Pitcher iced tea', 'iced tea pitcher', 48, 2, 1, '200', '2017-08-14 15:21:21', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1261, '1', '1', 'ube sundae', 'ube sundae', 47, 2, 1, '65', '2017-08-19 13:22:17', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1262, '1', '1', 'ube soya', 'ube soya', 47, 2, 1, '95', '2017-08-19 13:22:37', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1263, '1', '1', 'PIT', 'iced tea pitcher', 48, 2, 1, '260', '2017-08-20 17:14:03', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1264, '1', '1', 'Set A 6pax', 'Set A 6pax', 54, 1, 1, '1900', '2017-08-20 17:21:37', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1265, 'SET MENU A 6PAX', 'SET MENU B 6PAX', 'SET B(6PAX)', 'SET B GOOD FOR 6', 54, 1, 1, '2200', '2017-08-20 17:22:28', NULL, 0, 0, 1, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1266, '1', '1', 'Set B 6pax', 'Set B 6pax', 54, 1, 1, '2200', '2017-08-20 17:24:15', NULL, 1, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1267, 'mc', 'mc', 'mooncakepc', 'moon cake pc ', 52, 1, 0, '100', '2017-08-25 13:18:30', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1268, '1', '1', 'gc 500', 'gc 500', 50, 3, 1, '500', '2017-08-30 14:23:20', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1269, 'SYRUP', 'SYRUP', 'ADDSYRUP', 'ADD SYRUP', 48, 2, 0, '10', '2017-09-02 15:57:30', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1270, '1', '1', 'PATA W/ CUAPAO', 'PATATIM WITH CUAPAO', 61, 1, 0, '680', '2017-08-11 11:09:44', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1271, '1', '1', 'PATA W/O CUAPAO', 'PATATIM WITHOUT CUAPAO', 61, 1, 0, '610', '2017-08-11 11:10:05', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1272, '1', '1', 'SSP', 'SWEET AND SOUR PORK', 61, 1, 0, '305', '2017-08-11 11:10:25', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1273, '1', '1', 'SPSP', 'SPARERIBS WITH SALT AND PEPPER', 61, 1, 0, '327', '2017-08-11 11:11:00', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1274, '1', '1', 'SP W/ OK SAUCE', 'SPARERIBS WITH O.K SAUCE', 61, 1, 0, '342', '2017-08-11 11:11:43', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1275, '1', '1', 'MINCED W/ LETTUCE', 'MINCED PORK WITH LETTUCE', 61, 1, 0, '320', '2017-08-11 11:12:21', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1276, '1', '1', 'L.SHANGHAI', 'LUMPIA SHANGHAI', 61, 1, 0, '280', '2017-08-11 11:12:42', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1277, '1', '1', 'SP W/ FRUIT COCKTAIL', 'FRIED SPARERIBS WITH FRUIT COCKTAIL', 61, 1, 0, '430', '2017-08-11 11:13:59', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1278, '1', '1', 'FRIED CHIX WHOLE', 'HAP CHAN CRISPY FRIED CHICKEN-WHOLE', 61, 1, 0, '438', '2017-08-11 11:14:33', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1279, '1', '1', 'CHIX LEMON', 'CHIX LEMON', 61, 1, 0, '310', '2017-08-11 11:17:20', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1280, '1', '1', 'SS CHIX FILLET', 'SWEET AND SOUR CHICKEN FILLET', 61, 1, 0, '290', '2017-08-11 11:18:01', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1281, '1', '1', 'FRIED BUTT CHIX', 'FRIED BUTTERED CHICKEN', 61, 1, 0, '295', '2017-08-11 11:18:31', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1282, '1', '1', 'CHIX CASHEW NUTS', 'CHIX CASHEW NUTS', 61, 1, 0, '308', '2017-08-11 11:19:48', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1283, '1', '1', 'SLICED CHIX W/ MUSH', 'SLICED CHICKEN WITH TWO KIND MUSHROOM', 61, 1, 0, '278', '2017-08-11 11:20:30', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1284, '1', '1', 'CHIX W/ HONEY SAUCE', 'CHIX W/ HONEY SAUCE', 61, 1, 0, '385', '2017-08-11 11:21:07', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1285, '1', '1', 'BF CHINESE STYLE', 'BEEF STEAK WITH CHINESE STYLE', 61, 1, 0, '367', '2017-08-11 11:21:44', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1286, '1', '1', 'BF W/ BROC', 'SAUTEED BEEF WITH BROCCOLI FLOWER', 61, 1, 0, '280', '2017-08-11 11:22:25', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1287, '1', '1', 'BF W/ AMPLAYA', 'BRAISED BEEF WITH AMPALAYA', 61, 1, 0, '265', '2017-08-11 11:22:50', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1288, '1', '1', 'BF W/ MANGO', 'SLICED BEEF STEAK W/ MANGO', 61, 1, 0, '315', '2017-08-11 11:23:13', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1289, '1', '1', 'BF W/ SCRAMBLED EGG', 'BEEF W/ SCRAMBLED EGG', 61, 1, 0, '253', '2017-08-11 11:23:37', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1290, '1', '1', 'BF PEPPER SAUCE', 'BEEF STEAK IN BLACK PEPPER SAUCE', 61, 1, 0, '278', '2017-08-11 11:24:14', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1291, '1', '1', 'LO HAN CHAI', 'LO HAN CHAI', 61, 1, 0, '250', '2017-08-11 11:24:29', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1292, '1', '1', 'CHOP SUEY GUISADO', 'CHOP SUEY GUISADO', 61, 1, 0, '285', '2017-08-11 11:24:46', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1293, '1', '1', 'STUFFED EGGPLANT', 'CRISPY FRIED STUFFED EGGPLANT', 61, 1, 0, '275', '2017-08-11 11:25:34', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1294, '1', '1', 'STEAMED FISH FILLET', 'STEAMED FISH FILLET WITH GARLIC', 61, 1, 0, '450', '2017-08-11 11:26:39', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1295, '1', '1', 'SS FISH FILLET', 'SWEET AND SOUR FISH FILLET', 61, 1, 0, '425', '2017-08-11 11:27:16', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1296, '1', '1', 'FISH W/ SP', 'FISH FILLET WITH SALT AND PEPPER', 61, 1, 0, '425', '2017-08-11 11:27:54', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1297, '1', '1', 'FISH W/ BROC', 'FISH FILLET WITH BROCCOLI FLOWER', 61, 1, 0, '435', '2017-08-11 11:28:57', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1298, '1', '1', 'FRIED SHRIMP BALL', 'CRISPY FRIED SHRIMP BALL', 61, 1, 0, '420', '2017-08-11 11:31:44', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1299, '1', '1', 'HOT SHRIMP SALAD', 'HOT SHRIMP SALAD', 61, 1, 0, '495', '2017-08-11 11:32:08', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1300, '1', '1', 'CAMARON', 'FRIED SHRIMP (CAMARON REBUSADO)', 61, 1, 0, '410', '2017-08-11 11:33:59', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1301, '1', '1', 'SHRIMP W/ BROC', 'SHRIMP W/ BROCCOLI FLOWER', 61, 1, 0, '470', '2017-08-11 11:34:27', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1302, '1', '1', 'SHRIMP W/ EGG', 'SHRIMP WITH SCRAMBLED EGG', 61, 1, 0, '415', '2017-08-11 11:35:08', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1303, '1', '1', 'SQUID W/ PEPPER', 'SQUID WITH SALT AND PEPPER', 61, 1, 0, '300', '2017-08-11 11:35:30', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1304, '1', '1', 'SQUID W/ BROC', 'SQUID WITH BROCCOLI FLOWER', 61, 1, 0, '360', '2017-08-11 11:35:51', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1305, '1', '1', 'SET A 10 PAX', 'SET A 10 PAX', 63, 1, 0, '5000', '2017-09-16 16:51:56', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1306, '1', '1', 'SET B 10 PAX', 'SET B 10 PAX', 63, 1, 0, '5700', '2017-09-16 16:52:18', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1307, '1', '1', 'SET A 6 PAX', 'SET A 6 PAX', 62, 1, 0, '1950', '2017-09-16 16:52:55', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1308, '1', '1', 'SET B 6 PAX', 'SET B 6 PAX', 62, 1, 0, '2240', '2017-09-16 16:53:21', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1309, '1', '1', 'YANG CHOW TRAY', 'YANG CHOW TRAY', 64, 1, 1, '890', '2017-09-26 17:39:24', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1310, '1', '1', 'BEEF RICE TRAY', 'BEEF RICE TRAY', 64, 1, 1, '670', '2017-09-26 17:45:59', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1311, '1', '1', 'SALTED RICE TRAY', 'SALTED RICE TRAY', 64, 1, 1, '630', '2017-09-26 17:47:27', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1312, '1', '1', 'SEAFOOD CANTON TRAY', 'SEAFOOD CANTON TRAY', 64, 1, 1, '915', '2017-09-26 17:48:00', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1313, '1', '1', 'BIHON TRAY', 'BIHON TRAY', 64, 1, 1, '770', '2017-09-26 17:48:50', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1314, '1', '1', 'BDAY NOD TRAY', 'BIRTHDAY NOODLE TRAY', 64, 1, 1, '825', '2017-09-26 17:51:11', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1315, '1', '1', 'MIX MEAT CANTON TRAY', 'MIX MEAT CANTON TRAY', 64, 1, 1, '725', '2017-09-26 17:51:47', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1316, '1', '1', 'SSP TRAY', 'SWEETNSOUR PORK TRAY', 64, 1, 1, '950', '2017-09-26 17:52:59', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1317, '1', '1', 'SSFF TRAY', 'SWEET N SOUR FISH TRAY', 64, 1, 1, '1580', '2017-09-26 17:55:50', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1318, '1', '1', 'SS CHIX TRAY', 'SWEET N SOUR CHICKEN TRAY', 64, 1, 1, '880', '2017-09-26 17:56:23', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1319, '1', '1', 'SPARERIBS SP TRAY', 'SPARERIBS SALT N PEPPER TRAY', 64, 1, 1, '1200', '2017-09-26 17:57:33', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1320, '1', '1', 'FISH SP TRAY', 'FISH FILLET SALT N PEPPER TRAY', 64, 1, 1, '1550', '2017-09-26 17:58:23', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1321, '1', '1', 'LUMPIA SHANG TRAY', 'LUMPIANG SHANGHAI  TRAY', 64, 1, 1, '880', '2017-09-26 17:58:58', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1322, '1', '1', 'BUT CHIX TRAY', 'BUTTERED CHICKEN TRAY', 64, 1, 1, '1070', '2017-09-26 17:59:37', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1323, '1', '1', 'CHOPSUEY TRAY', 'CHOPSUEY TRAY', 64, 1, 1, '1100', '2017-09-26 18:01:16', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1324, '1', '1', 'BEEF BROC TRAY', 'BEEF BROCCOLI TRAY', 64, 1, 1, '1095', '2017-09-26 18:01:40', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1325, '2', '2', 'MEAL 1 ', 'MEAL 1 ', 65, 1, 1, '220', '2017-09-26 18:02:36', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1326, '2', '2', 'MEAL 2', 'MEAL 2', 65, 1, 1, '210', '2017-09-26 18:02:51', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1327, '2', '2', 'MEAL 3', 'MEAL 3', 65, 1, 1, '215', '2017-09-26 18:03:05', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1328, '2', '2', 'MEAL 4', 'MEAL 4', 65, 1, 1, '245', '2017-09-26 18:03:15', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1329, NULL, NULL, NULL, NULL, 0, NULL, 0, '0', NULL, NULL, 0, NULL, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1330, 'APP0001', 'APP0001', 'MEAT BALL', 'MEAT BALL', 66, 1, 0, '175', '2018-03-25 15:26:58', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1331, 'APP0002', 'APP0002', 'WANTON', 'WANTON', 66, 1, 0, '180', '2018-03-25 15:28:22', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1332, 'APP0003', 'APP0003', 'BF BRISKET', 'BEEF BRISKET', 66, 1, 0, '225', '2018-03-25 15:29:51', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1333, 'BFF0001', 'BFF0001', 'BFF SET 1', 'BFF SET 1', 67, 1, 0, '599', '2018-03-25 15:31:10', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1334, 'BFF0002', 'BFF0002', 'BFF SET 2', 'BFF SET2', 67, 1, 0, '649', '2018-03-25 15:31:28', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1335, 'BFF0003', 'BFF0003', 'BFF SET 3', 'BFF SET3', 67, 1, 0, '699', '2018-03-25 15:31:43', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1336, 'BFF0004', 'BFF0004', 'BFF SET4', 'BFF SET4', 67, 1, 0, '749', '2018-03-25 15:32:09', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1337, 'BFF0005', 'BFF0005', 'BFF SET 5', 'BFF SET5', 67, 1, 0, '649', '2018-03-25 15:32:26', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1338, 'BFF0006', 'BFF0006', 'UPGRADE YC', 'UPGRADE YC', 67, 1, 0, '100', '2018-03-25 15:32:58', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1339, 'BFF0007', 'BFF0007', 'UPGRADE PIT', 'UPGRADE PIT', 67, 2, 0, '100', '2018-03-25 15:33:19', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1340, 'CC01234', 'CC01234', 'SEAFOOD PINEAPPLE', 'SEAFOOD PINEAPPLE', 61, 1, 0, '375', '2018-03-25 15:35:11', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1341, 'CC01235', 'CC01235', 'SHRIMP SWEET CHILI ', 'SHRIMP SWEET CHILI ', 61, 1, 0, '445', '2018-03-25 15:36:26', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1342, 'N001', 'N001', 'MEATBALL NOD -S', 'MEATBALL NOD -S', 41, 1, 0, '110', '2018-03-25 15:37:28', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1343, 'N002', 'N002', 'MEATBALL NOD -R', 'MEATBALL NOD -R', 41, 1, 0, '190', '2018-03-25 15:37:39', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1344, 'SS001', 'SS001', 'FISH LIP -S', 'FISH LIP -S', 43, 1, 1, '190', '2018-03-25 15:40:12', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1345, 'SS002', 'SS002', 'FISH LIP -R', 'FISH LIP -R', 43, 1, 1, '350', '2018-03-25 15:40:22', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1346, '8', '8', 'NIDO QUAIL -S', 'NIDO QUAIL -S', 43, 1, 1, '180', '2018-03-25 15:41:05', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1347, 'SS003', 'SS003', '8 TREASURE -S', '8 TREASURE -S', 43, 1, 1, '265', '2018-03-25 15:42:13', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1348, 'SS004', 'SS004', '8 TREASURE -R', '8 TREASURE -R', 43, 1, 1, '395', '2018-03-25 15:42:27', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1349, 'SS005', 'SS005', 'MINCED BEEF SOUP -S', 'MINCED BEEF SOUP -S', 43, 1, 1, '180', '2018-03-25 15:42:51', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1350, 'SS005', 'SS005', 'MINCED BEEF SOUP -R', 'MINCED BEEF SOUP -R', 43, 1, 1, '340', '2018-03-25 15:43:04', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1351, 'HP001', 'HP001', 'SOTANGHON VEG HP', 'SOTANGHON VEGGIE HP', 59, 1, 0, '245', '2018-03-25 15:45:02', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1352, 'HP002', 'HP002', 'CHIX SALTY FISH HP', 'CHIX SALTY FISH HP', 59, 1, 0, '235', '2018-03-25 15:45:39', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1353, 'HP003', 'HP003', 'BF RADISH HP', 'BF RADISH HP', 59, 1, 0, '330', '2018-03-25 15:46:16', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1354, 'HP004', 'HP004', 'SPECIAL HP', 'SPECIAL HP', 59, 1, 0, '355', '2018-03-25 15:46:33', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1355, 'CC00099', 'CC00099', 'BEEF TOMATO', 'BEEF TOMATO', 61, 1, 0, '270', '2018-03-25 15:48:56', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1356, 'RN001', 'RN001', 'BIHON SG', 'BIHON SG', 45, 1, 0, '250', '2018-03-25 15:50:54', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1357, 'RN002', 'RN002', 'BEEF RICE', 'BEEF RICE', 45, 1, 0, '230', '2018-03-25 15:51:17', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1358, 'DR001', 'DR001', 'LEMON PITCHER', 'LEMON PITCHER', 48, 2, 0, '260', '2018-03-25 17:53:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1359, 'RN00010', 'RN00010', 'YC HALF', 'YANGCHOW HALF', 45, 1, 1, '150', '2018-03-25 19:14:34', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1360, 'DR00099', 'DR00099', 'IT GLASS', 'ICED TEA GLASS', 48, 2, 0, '50', '2018-03-25 20:59:38', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1361, 'DR000100', 'DR000100', 'M JUICE', 'MANGO JUICE', 48, 2, 0, '75', '2018-03-25 21:08:14', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1362, '8', '8', 'NIDO SMALL', 'NIDO SOUP SMALL', 43, 1, 1, '180', '2016-12-19 07:46:41', NULL, 0, 0, 0, '0');
INSERT INTO menus (`menu_id`, `menu_code`, `menu_barcode`, `menu_name`, `menu_short_desc`, `menu_cat_id`, `menu_sub_cat_id`, `menu_sched_id`, `cost`, `reg_date`, `update_date`, `no_tax`, `free`, `inactive`, `costing`) VALUES (1363, '1111', '2222', 'asdfasdfsdf', 'adsfdsaf', 43, 2, 1, '100', '2018-06-21 10:51:59', NULL, 0, 0, 0, '0');


#
# TABLE STRUCTURE FOR: modifier_group_details
#

DROP TABLE IF EXISTS modifier_group_details;

CREATE TABLE `modifier_group_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_group_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `default` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: modifier_groups
#

DROP TABLE IF EXISTS modifier_groups;

CREATE TABLE `modifier_groups` (
  `mod_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `mandatory` int(1) DEFAULT '0',
  `multiple` int(10) DEFAULT '0',
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`mod_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: modifier_recipe
#

DROP TABLE IF EXISTS modifier_recipe;

CREATE TABLE `modifier_recipe` (
  `mod_recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `uom` varchar(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` double NOT NULL,
  PRIMARY KEY (`mod_recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: modifiers
#

DROP TABLE IF EXISTS modifiers;

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

#
# TABLE STRUCTURE FOR: ortigas
#

DROP TABLE IF EXISTS ortigas;

CREATE TABLE `ortigas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_code` varchar(10) DEFAULT NULL,
  `sales_type` varchar(5) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

INSERT INTO ortigas (`id`, `tenant_code`, `sales_type`) VALUES (1, 'code1', '03');


#
# TABLE STRUCTURE FOR: ortigas_read_details
#

DROP TABLE IF EXISTS ortigas_read_details;

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

#
# TABLE STRUCTURE FOR: promo_discount_items
#

DROP TABLE IF EXISTS promo_discount_items;

CREATE TABLE `promo_discount_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `promo_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: promo_discount_schedule
#

DROP TABLE IF EXISTS promo_discount_schedule;

CREATE TABLE `promo_discount_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promo_id` int(11) NOT NULL,
  `day` varchar(22) NOT NULL,
  `time_on` time NOT NULL,
  `time_off` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

INSERT INTO promo_discount_schedule (`id`, `promo_id`, `day`, `time_on`, `time_off`) VALUES (1, 1, 'mon', '15:00:00', '18:00:00');
INSERT INTO promo_discount_schedule (`id`, `promo_id`, `day`, `time_on`, `time_off`) VALUES (8, 1, 'tue', '15:00:00', '18:00:00');
INSERT INTO promo_discount_schedule (`id`, `promo_id`, `day`, `time_on`, `time_off`) VALUES (9, 1, 'wed', '15:00:00', '18:00:00');
INSERT INTO promo_discount_schedule (`id`, `promo_id`, `day`, `time_on`, `time_off`) VALUES (10, 1, 'thu', '15:00:00', '18:00:00');
INSERT INTO promo_discount_schedule (`id`, `promo_id`, `day`, `time_on`, `time_off`) VALUES (11, 1, 'fri', '15:00:00', '18:00:00');
INSERT INTO promo_discount_schedule (`id`, `promo_id`, `day`, `time_on`, `time_off`) VALUES (12, 1, 'sat', '15:00:00', '18:00:00');
INSERT INTO promo_discount_schedule (`id`, `promo_id`, `day`, `time_on`, `time_off`) VALUES (13, 1, 'sun', '15:00:00', '18:00:00');


#
# TABLE STRUCTURE FOR: promo_discounts
#

DROP TABLE IF EXISTS promo_discounts;

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

INSERT INTO promo_discounts (`promo_id`, `promo_code`, `promo_name`, `value`, `absolute`, `reg_date`, `update_date`, `inactive`) VALUES (1, '20HAPPYHOUR', '20 % HAPPY HOUR', '20', 0, '2017-03-21 14:55:57', '2017-03-21 14:55:57', 0);


#
# TABLE STRUCTURE FOR: promo_free
#

DROP TABLE IF EXISTS promo_free;

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

INSERT INTO promo_free (`pf_id`, `name`, `description`, `has_menu_id`, `amount`, `sched_id`, `inactive`) VALUES (1, 'Free Pork Siomai D', 'Free Pork Siomai D', '34', '1000', 1, 0);


#
# TABLE STRUCTURE FOR: promo_free_menus
#

DROP TABLE IF EXISTS promo_free_menus;

CREATE TABLE `promo_free_menus` (
  `pf_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `pf_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  PRIMARY KEY (`pf_menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO promo_free_menus (`pf_menu_id`, `pf_id`, `menu_id`, `qty`) VALUES (2, 1, 12, 1);


#
# TABLE STRUCTURE FOR: read_details
#

DROP TABLE IF EXISTS read_details;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO read_details (`id`, `read_type`, `read_date`, `user_id`, `old_total`, `grand_total`, `reg_date`, `scope_from`, `scope_to`, `ctr`, `sync_id`) VALUES (1, 1, '2018-06-25', 1, NULL, NULL, '2018-06-29 11:06:12', '2018-06-25 09:27:45', '2018-06-29 11:06:11', 0, NULL);


#
# TABLE STRUCTURE FOR: reasons
#

DROP TABLE IF EXISTS reasons;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

#
# TABLE STRUCTURE FOR: receipt_discounts
#

DROP TABLE IF EXISTS receipt_discounts;

CREATE TABLE `receipt_discounts` (
  `disc_id` int(11) NOT NULL AUTO_INCREMENT,
  `disc_code` varchar(22) DEFAULT NULL,
  `disc_name` varchar(100) DEFAULT NULL,
  `disc_rate` double DEFAULT NULL,
  `no_tax` int(1) DEFAULT '0',
  `fix` int(1) DEFAULT '0',
  `inactive` int(1) DEFAULT '0',
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`disc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (1, 'SNDISC', 'Senior Citizen Discount', '20', 1, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (2, 'PWDISC', 'Person WIth Disability', '20', 1, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (3, 'REG15DISC', '15 Percent DIscount', '15', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (4, 'REG100DISC', '100 Percent Discount', '100', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (5, 'REG10DISC', '10 Percent Discount', '10', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (6, 'REG20DISC', '20 Percent Discount', '20', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (7, 'CDV', '15 % cdv Take Out', '15', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (8, '50HH', '50% HAPPY HOUR', '50', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (9, 'REG30DISC', '30 Percent Discount', '30', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (10, 'REG70DISC', '70 Percent Discount', '70', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (11, 'REG25DISC', '25  CDVBDAYMONTH', '25', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (12, 'REG60DISC', '60 Percent Discount', '60', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (13, '20HH', '20% HAPPY HOUR', '20', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (14, '10clubdevino', '10 Club de Vino', '10', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (15, '20Clubdevino', '20 Club de Vino', '20', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (16, '50%HH', '50%HAPPY HOUR', '50', 0, 0, 1, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (17, '20HH', '20%HAPPYHOUR', '20', 0, 0, 1, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (18, '15CDVTAKEOUT', '15%CDVTAKEOUT', '15', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (19, 'LaCamara10PercentDisco', 'La Camara 10%', '10', 0, 0, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (20, 'TasteofCoastalTicket', 'Taste of Coastal Ticket', '2300', 0, 1, 0, '2018-04-24 01:07:02', 2);
INSERT INTO receipt_discounts (`disc_id`, `disc_code`, `disc_name`, `disc_rate`, `no_tax`, `fix`, `inactive`, `datetime`, `sync_id`) VALUES (21, '100percent', '100 percent discount', '100', 0, 0, 0, '2018-04-24 01:07:02', 2);


#
# TABLE STRUCTURE FOR: restaurant_branch_tables
#

DROP TABLE IF EXISTS restaurant_branch_tables;

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

#
# TABLE STRUCTURE FOR: rob_files
#

DROP TABLE IF EXISTS rob_files;

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

#
# TABLE STRUCTURE FOR: settings
#

DROP TABLE IF EXISTS settings;

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

INSERT INTO settings (`id`, `no_of_receipt_print`, `no_of_order_slip_print`, `controls`, `local_tax`, `kitchen_printer_name`, `kitchen_beverage_printer_name`, `kitchen_printer_name_no`, `kitchen_beverage_printer_name_no`, `open_drawer_printer`, `loyalty_for_amount`, `loyalty_to_points`, `backup_path`) VALUES (1, 1, 0, '1=>dine in,2=>delivery,4=>retail,5=>pickup,6=>takeout,8=>food panda', '0', '', '', 1, 0, 'CASH DRAWER', '100', '10', '');


#
# TABLE STRUCTURE FOR: shift_entries
#

DROP TABLE IF EXISTS shift_entries;

CREATE TABLE `shift_entries` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `user_id` varchar(45) NOT NULL,
  `trans_date` datetime NOT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO shift_entries (`entry_id`, `shift_id`, `amount`, `user_id`, `trans_date`, `sync_id`, `datetime`) VALUES (1, 1, '100', '1', '2018-06-25 09:27:45', NULL, '2018-06-25 09:27:45');


#
# TABLE STRUCTURE FOR: shifts
#

DROP TABLE IF EXISTS shifts;

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

INSERT INTO shifts (`shift_id`, `user_id`, `check_in`, `check_out`, `xread_id`, `cashout_id`, `terminal_id`, `sync_id`, `datetime`) VALUES (1, 1, '2018-06-25 09:27:45', '2018-06-29 11:06:11', 1, 1, 1, NULL, '2018-06-29 11:06:12');


#
# TABLE STRUCTURE FOR: stalucia
#

DROP TABLE IF EXISTS stalucia;

CREATE TABLE `stalucia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

INSERT INTO stalucia (`id`, `tenant_code`) VALUES (1, '123');


#
# TABLE STRUCTURE FOR: subcategories
#

DROP TABLE IF EXISTS subcategories;

CREATE TABLE `subcategories` (
  `sub_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  PRIMARY KEY (`sub_cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (1, 1, 'SUBCAT-DAIRY-CHZ', 'SUBCAT-DAIRY-CHZ', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (2, 2, 'SUBCAT-MEAT-PROC', 'SUBCAT-MEAT-PROC', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (3, 3, 'SUBCAT-FATS-OIL', 'SUBCAT-FATS-OIL', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (4, 4, 'SUBCAT-FSEA-SEA', 'SUBCAT-FSEA-SEA', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (5, 5, 'SUBCAT-FVEG-VEG', 'SUBCAT-FVEG-VEG', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (6, 6, 'SUBCAT-ALC-WINE', 'SUBCAT-ALC-WINE', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (7, 2, 'SUBCAT-MEAT-POUL', 'SUBCAT-MEAT-POUL', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (8, 8, 'SUBCAT-BAKE-BAK', 'SUBCAT-BAKE-BAK', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (9, 5, 'SUBCAT-FVEG-FRU', 'SUBCAT-FVEG-FRU', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (10, 10, 'SUBCAT-CONDI-SPI', 'SUBCAT-CONDI-SPI', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (11, 11, 'SUBCAT-MIX-SAU', 'SUBCAT-MIX-SAU', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (12, 1, 'SUBCAT-DAIRY-BUT', 'SUBCAT-DAIRY-BUT', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (13, 3, 'SUBCAT-FATS-NUTS', 'SUBCAT-FATS-NUTS', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (14, 1, 'SUBCAT-DAIRY-MILK', 'SUBCAT-DAIRY-MILK', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (15, 10, 'SUBCAT-CONDI-SAU', 'SUBCAT-CONDI-SAU', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (16, 1, 'SUBCAT-DAIRY-CREAM', 'SUBCAT-DAIRY-CREAM', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (17, 4, 'SUBCAT-FSEA-FISH', 'SUBCAT-FSEA-FISH', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (18, 3, 'SUBCAT-FATS-MAYO', 'SUBCAT-FATS-MAYO', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (19, 2, 'SUBCAT-MEAT-BEEF', 'SUBCAT-MEAT-BEEF', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (20, 6, 'SUBCAT-ALC-LIQ', 'SUBCAT-ALC-LIQ', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (21, 2, 'SUBCAT-MEAT-PORK', 'SUBCAT-MEAT-PORK', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (22, 8, 'SUBCAT-BAKE-PASTA', 'SUBCAT-BAKE-PASTA', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (23, 2, 'SUBCAT-MEAT-LAMB', 'SUBCAT-MEAT-LAMB', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (24, 2, 'SUBCAT-MEAT-OX', 'SUBCAT-MEAT-OX', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (25, 6, 'SUBCAT-ALC-WINE', 'SUBCAT-ALC-WINE', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (26, 6, 'SUBCAT-ALC-BEER', 'SUBCAT-ALC-BEER', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (27, 7, 'SUBCAT-NONALC-COFFEE', 'SUBCAT-NONALC-COFFEE', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (28, 7, 'SUBCAT-NONALC-TEA', 'SUBCAT-NONALC-TEA', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (29, 7, 'SUBCAT-NONALC-BEV', 'SUBCAT-NONALC-BEV', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (30, 6, 'SUBCAT-ALC-LIQ', 'SUBCAT-ALC-LIQ', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (31, 12, 'NUEVOS PLATOS', 'NUEVOS PLATOS', NULL, 0);
INSERT INTO subcategories (`sub_cat_id`, `cat_id`, `code`, `name`, `image`, `inactive`) VALUES (32, 9, 'NICO-CHORIZO', 'NICO-CHORIZO', NULL, 0);


#
# TABLE STRUCTURE FOR: suppliers
#

DROP TABLE IF EXISTS suppliers;

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `inactive` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO suppliers (`supplier_id`, `name`, `address`, `contact_no`, `reg_date`, `memo`, `inactive`) VALUES (1, 'Supplier 1', 'Cubao, Q.C.', '02 999 99 99', '2016-03-11 10:51:33', 'chinese Supplier', '1');
INSERT INTO suppliers (`supplier_id`, `name`, `address`, `contact_no`, `reg_date`, `memo`, `inactive`) VALUES (2, 'TGI Warehouse', 'First Lucky', '8094209', '2017-03-31 09:39:32', 'Cleaning materials and store supplies', '0');
INSERT INTO suppliers (`supplier_id`, `name`, `address`, `contact_no`, `reg_date`, `memo`, `inactive`) VALUES (3, 'Barcino Corporation', 'Unit B1 #700 Lerma St Brgy. Old Zaniga Mandaluyong City 1636', '6549235', '2017-03-31 09:41:24', 'Wines supplier/ cheese and cold cuts', '0');
INSERT INTO suppliers (`supplier_id`, `name`, `address`, `contact_no`, `reg_date`, `memo`, `inactive`) VALUES (4, 'Amari Trading', '15420 E. Rodriguez Ave., San Agustin Village Moonwalk, Paranaque City 1700', '8359699', '2017-03-31 13:30:48', 'Beer Supplier', '0');
INSERT INTO suppliers (`supplier_id`, `name`, `address`, `contact_no`, `reg_date`, `memo`, `inactive`) VALUES (5, 'warehouse food', 'jentec', '09169152127', '2017-04-02 11:30:33', 'food', '0');
INSERT INTO suppliers (`supplier_id`, `name`, `address`, `contact_no`, `reg_date`, `memo`, `inactive`) VALUES (6, 'fresh market', 'cubao', '09169152127', '2017-04-02 11:31:59', 'vegtables', '0');


#
# TABLE STRUCTURE FOR: sync_logs
#

DROP TABLE IF EXISTS sync_logs;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO sync_logs (`sync_id`, `transaction`, `type`, `status`, `migrate_date`, `src_id`, `user_id`, `is_automated`) VALUES (1, 'logs', 'add', 1, '2018-06-21 10:42:02', NULL, NULL, 0);


#
# TABLE STRUCTURE FOR: sync_types
#

DROP TABLE IF EXISTS sync_types;

CREATE TABLE `sync_types` (
  `sync_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `sync_type` varchar(100) NOT NULL,
  PRIMARY KEY (`sync_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO sync_types (`sync_type_id`, `sync_type`) VALUES (1, 'local to main');
INSERT INTO sync_types (`sync_type_id`, `sync_type`) VALUES (2, 'main to local');


#
# TABLE STRUCTURE FOR: table_activity
#

DROP TABLE IF EXISTS table_activity;

CREATE TABLE `table_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tbl_id` int(11) DEFAULT NULL,
  `pc_id` int(11) DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

#
# TABLE STRUCTURE FOR: tables
#

DROP TABLE IF EXISTS tables;

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
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=latin1;

INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (92, 4, NULL, 'Tbl 1', 38, 838, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (93, 4, NULL, 'Tbl 3', 38, 758, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (94, 4, NULL, 'Tbl 2', 136, 837, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (95, 4, NULL, 'Tbl 5', 136, 757, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (96, 4, NULL, 'Tbl 6', 38, 682, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (97, 4, NULL, 'Tbl 7', 38, 602, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (98, 4, NULL, 'TBL 8', 38, 525, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (99, 4, NULL, 'Tbl 9', 38, 446, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (100, 4, NULL, 'Tbl 11', 42, 267, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (101, 4, NULL, 'Tbl 12', 38, 191, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (102, 4, NULL, 'Tbl 14', 38, 111, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (103, 4, NULL, 'Tbl 19', 128, 109, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (104, 4, NULL, 'Tbl 17', 128, 187, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (105, 4, NULL, 'Tbl 15', 128, 265, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (106, 4, NULL, 'Tbl 20', 175, 109, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (107, 4, NULL, 'Tbl 18', 175, 187, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (108, 4, NULL, 'Tbl 16', 175, 265, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (109, 4, NULL, 'Tbl 23', 236, 111, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (110, 4, NULL, 'Tbl 22', 236, 191, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (111, 4, NULL, 'Tbl 21', 236, 268, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (112, 4, NULL, 'Tbl 24', 350, 230, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (113, 4, NULL, 'AL 4', 46, 12, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (114, 4, NULL, 'AL 3', 136, 13, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (115, 4, NULL, 'AL 2', 236, 12, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (116, 4, NULL, 'AL 1', 398, 13, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (117, 4, NULL, 'Tbl 10', 234, 385, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (118, 2, NULL, 'A1', 49, 327, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (119, 4, NULL, 'A4', 23, 398, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (120, 2, NULL, 'A2', 48, 467, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (121, 2, NULL, 'A3', 50, 620, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (122, 4, NULL, 'A5', 22, 544, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (123, 4, NULL, 'A6', 21, 707, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (124, 4, NULL, 'V1', 149, 737, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (125, 4, NULL, 'V2', 258, 736, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (126, 4, NULL, 'V3', 377, 734, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (127, 6, NULL, 'T12', 252, 570, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (128, 2, NULL, 'T11', 154, 393, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (129, 2, NULL, 'T9', 121, 310, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (130, 2, NULL, 'T10', 218, 310, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (131, 2, NULL, 'T8', 235, 155, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (132, 6, NULL, 'T7', 313, 149, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (133, 6, NULL, 'T6', 400, 149, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (134, 2, NULL, 'T1', 45, 161, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (135, 4, NULL, 'T2', 44, 56, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (136, 6, NULL, 'T3', 148, 38, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (137, 4, NULL, 'T4', 256, 37, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (138, 6, NULL, 'T5', 373, 34, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (139, 4, NULL, 'T2', 46, 53, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (140, 6, NULL, 'T3', 149, 38, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (143, 2, NULL, '4A', 210, 37, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (146, 6, NULL, 'T5', 375, 35, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (147, 2, NULL, '4B', 254, 38, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (148, 2, NULL, '4C', 300, 36, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (149, 2, NULL, 'T1', 45, 166, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (150, 2, NULL, 'T10', 106, 190, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (151, 2, NULL, 'T9', 159, 155, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (152, 2, NULL, 'T8', 232, 152, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (157, 2, NULL, '7B', 285, 168, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (158, 2, NULL, '7A', 321, 123, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (159, 2, NULL, '6C', 364, 160, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (160, 2, NULL, '6B', 396, 121, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (161, 2, NULL, '6A', 442, 151, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (162, 2, NULL, 'T11', 220, 311, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (163, 6, NULL, 'T12', 256, 571, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (164, 4, NULL, 'V1', 152, 737, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (165, 4, NULL, 'V2', 255, 733, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (166, 4, NULL, 'V3', 379, 732, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (167, 4, NULL, 'A6', 20, 710, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (168, 2, NULL, 'A3', 52, 618, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (169, 4, NULL, 'A5', 22, 543, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (170, 2, NULL, 'A2', 50, 463, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (171, 4, NULL, 'A4', 21, 396, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (172, 2, NULL, 'A1', 51, 327, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (173, 5, NULL, 'TBL1', 150, 787, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (174, 5, NULL, 'TBL2', 218, 791, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (175, 5, NULL, 'TBL3', 313, 789, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (176, 9, NULL, 'D1', 29, 232, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (177, 4, NULL, 'D2', 109, 254, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (178, 4, NULL, 'D3', 156, 253, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (179, 4, NULL, 'D4', 206, 254, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (180, 4, NULL, 'D5', 303, 259, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (182, 4, NULL, 'A1', 41, 336, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (183, 4, NULL, 'A2', 94, 336, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (184, 4, NULL, 'A3', 148, 335, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (185, 4, NULL, 'A4', 217, 337, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (186, 4, NULL, 'A5', 271, 336, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (187, 4, NULL, 'A6', 323, 337, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (188, 6, NULL, 'A7', 375, 237, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (189, 2, NULL, 'M1', 266, 611, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (190, 2, NULL, 'M2', 265, 741, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (191, 2, NULL, 'M3', 266, 861, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (192, 2, NULL, 'M4', 193, 864, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (193, 2, NULL, 'M5', 109, 728, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (194, 2, NULL, 'M6', 83, 860, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (195, 2, NULL, 'M7', 26, 861, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (196, 9, NULL, 'VIP1', 202, 578, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (197, 2, NULL, 'VIP2', 138, 534, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (198, 2, NULL, 'VIP3', 140, 620, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (199, 2, NULL, 'VIP4', 80, 530, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (200, 2, NULL, 'VIP5', 80, 617, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (201, 9, NULL, 'VIP6', 26, 578, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (202, 1, NULL, 'B8', 328, 68, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (203, 1, NULL, 'B7', 285, 69, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (204, 1, NULL, 'B1', 37, 70, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (205, 1, NULL, 'B2', 80, 69, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (206, 1, NULL, 'B3', 120, 70, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (207, 1, NULL, 'B4', 159, 70, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (208, 1, NULL, 'B5', 202, 69, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (209, 1, NULL, 'B6', 245, 68, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (210, 6, NULL, 'A8', 372, 69, 1, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (211, 1, NULL, 'B1', 36, 69, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (212, 1, NULL, 'B2', 79, 69, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (213, 1, NULL, 'B3', 120, 70, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (214, 1, NULL, 'B4', 162, 69, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (215, 1, NULL, 'B5', 202, 69, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (216, 1, NULL, 'B6', 241, 67, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (217, 1, NULL, 'B7', 285, 69, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (218, 1, NULL, 'B8', 325, 69, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (219, 9, NULL, 'D1', 30, 232, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (220, 4, NULL, 'D2', 107, 255, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (221, 4, NULL, 'D3', 157, 255, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (222, 4, NULL, 'D4', 208, 254, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (223, 8, NULL, 'D5', 304, 261, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (224, 6, NULL, 'A9', 375, 69, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (225, 4, NULL, 'A8', 371, 236, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (226, 4, NULL, 'A1', 25, 336, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (227, 4, NULL, 'A2', 80, 336, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (228, 4, NULL, 'A3', 131, 337, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (229, 4, NULL, 'A4', 183, 336, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (230, 4, NULL, 'A5', 234, 338, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (231, 4, NULL, 'A6', 286, 337, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (232, 4, NULL, 'A7', 340, 337, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (233, 2, NULL, 'M1', 266, 613, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (234, 2, NULL, 'M2', 266, 742, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (235, 1, NULL, 'M3', 266, 861, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (236, 2, NULL, 'M4', 194, 862, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (237, 2, NULL, 'M5', 110, 726, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (238, 2, NULL, 'M6', 85, 861, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (239, 2, NULL, 'M7', 27, 860, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (240, 9, NULL, 'VIP1', 203, 579, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (241, 2, NULL, 'VIP2', 138, 533, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (242, 2, NULL, 'VIP3', 137, 620, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (243, 2, NULL, 'VIP4', 80, 534, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (244, 2, NULL, 'VIP5', 79, 618, 0, 1, '2018-04-24 01:26:00');
INSERT INTO tables (`tbl_id`, `capacity`, `status`, `name`, `top`, `left`, `inactive`, `sync_id`, `datetime`) VALUES (245, 9, NULL, 'VIP6', 25, 574, 0, 1, '2018-04-24 01:26:00');


#
# TABLE STRUCTURE FOR: tax_rates
#

DROP TABLE IF EXISTS tax_rates;

CREATE TABLE `tax_rates` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO tax_rates (`tax_id`, `name`, `rate`, `inactive`) VALUES (1, 'VAT', '12', 0);


#
# TABLE STRUCTURE FOR: terminals
#

DROP TABLE IF EXISTS terminals;

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
  PRIMARY KEY (`terminal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

INSERT INTO terminals (`terminal_id`, `terminal_code`, `branch_code`, `terminal_name`, `ip`, `comp_name`, `reg_date`, `update_date`, `inactive`) VALUES (1, 'T00001', 'ELRGB', 'Terminal 1', '192.168.254.101', 'TERMINAL1', '2014-09-11 12:45:45', NULL, 0);


#
# TABLE STRUCTURE FOR: trans_adjustment_details
#

DROP TABLE IF EXISTS trans_adjustment_details;

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
  PRIMARY KEY (`adjustment_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: trans_adjustments
#

DROP TABLE IF EXISTS trans_adjustments;

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

#
# TABLE STRUCTURE FOR: trans_receiving_details
#

DROP TABLE IF EXISTS trans_receiving_details;

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
  PRIMARY KEY (`receiving_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: trans_receiving_menu
#

DROP TABLE IF EXISTS trans_receiving_menu;

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
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`receiving_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: trans_receiving_menu_details
#

DROP TABLE IF EXISTS trans_receiving_menu_details;

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

#
# TABLE STRUCTURE FOR: trans_receivings
#

DROP TABLE IF EXISTS trans_receivings;

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

#
# TABLE STRUCTURE FOR: trans_refs
#

DROP TABLE IF EXISTS trans_refs;

CREATE TABLE `trans_refs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(55) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO trans_refs (`id`, `type_id`, `trans_ref`, `user_id`, `inactive`, `sync_id`, `datetime`) VALUES (1, 10, '00000001', 1, NULL, NULL, '2018-06-25 09:39:46');
INSERT INTO trans_refs (`id`, `type_id`, `trans_ref`, `user_id`, `inactive`, `sync_id`, `datetime`) VALUES (2, 10, '00000002', 1, NULL, NULL, '2018-06-25 09:41:47');
INSERT INTO trans_refs (`id`, `type_id`, `trans_ref`, `user_id`, `inactive`, `sync_id`, `datetime`) VALUES (3, 10, '00000003', 1, NULL, NULL, '2018-06-25 09:42:54');
INSERT INTO trans_refs (`id`, `type_id`, `trans_ref`, `user_id`, `inactive`, `sync_id`, `datetime`) VALUES (4, 10, '00000004', 1, NULL, NULL, '2018-06-25 09:43:20');


#
# TABLE STRUCTURE FOR: trans_sales
#

DROP TABLE IF EXISTS trans_sales;

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
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO trans_sales (`sales_id`, `mobile_sales_id`, `type_id`, `trans_ref`, `void_ref`, `type`, `user_id`, `shift_id`, `terminal_id`, `customer_id`, `total_amount`, `total_paid`, `memo`, `table_id`, `guest`, `datetime`, `update_date`, `paid`, `reason`, `void_user_id`, `printed`, `inactive`, `waiter_id`, `split`, `serve_no`, `billed`, `sync_id`) VALUES (1, NULL, 10, '00000004', NULL, 'dinein', 1, 1, 1, NULL, '550.08928571429', '550.09', NULL, 226, '5', '2018-06-25 09:28:20', '2018-06-25 09:43:20', 1, NULL, NULL, 1, 0, NULL, 0, 0, 1, NULL);
INSERT INTO trans_sales (`sales_id`, `mobile_sales_id`, `type_id`, `trans_ref`, `void_ref`, `type`, `user_id`, `shift_id`, `terminal_id`, `customer_id`, `total_amount`, `total_paid`, `memo`, `table_id`, `guest`, `datetime`, `update_date`, `paid`, `reason`, `void_user_id`, `printed`, `inactive`, `waiter_id`, `split`, `serve_no`, `billed`, `sync_id`) VALUES (2, NULL, 10, '00000001', NULL, 'delivery', 1, 1, 1, '1', '893.16964285714', '893.17', NULL, NULL, '0', '2018-06-25 09:36:59', '2018-06-25 09:39:51', 1, NULL, NULL, 1, 0, NULL, 0, 0, 1, NULL);
INSERT INTO trans_sales (`sales_id`, `mobile_sales_id`, `type_id`, `trans_ref`, `void_ref`, `type`, `user_id`, `shift_id`, `terminal_id`, `customer_id`, `total_amount`, `total_paid`, `memo`, `table_id`, `guest`, `datetime`, `update_date`, `paid`, `reason`, `void_user_id`, `printed`, `inactive`, `waiter_id`, `split`, `serve_no`, `billed`, `sync_id`) VALUES (3, NULL, 10, '00000002', NULL, 'takeout', 1, 1, 1, NULL, '845', '845', NULL, NULL, '0', '2018-06-25 09:40:04', '2018-06-25 09:41:47', 1, NULL, NULL, 1, 0, NULL, 0, 0, 1, NULL);
INSERT INTO trans_sales (`sales_id`, `mobile_sales_id`, `type_id`, `trans_ref`, `void_ref`, `type`, `user_id`, `shift_id`, `terminal_id`, `customer_id`, `total_amount`, `total_paid`, `memo`, `table_id`, `guest`, `datetime`, `update_date`, `paid`, `reason`, `void_user_id`, `printed`, `inactive`, `waiter_id`, `split`, `serve_no`, `billed`, `sync_id`) VALUES (4, NULL, 10, '00000003', NULL, 'takeout', 1, 1, 1, NULL, '1575', '1575', NULL, NULL, '0', '2018-06-25 09:41:49', '2018-06-25 09:43:02', 1, NULL, NULL, 1, 0, NULL, 0, 0, 1, NULL);
INSERT INTO trans_sales (`sales_id`, `mobile_sales_id`, `type_id`, `trans_ref`, `void_ref`, `type`, `user_id`, `shift_id`, `terminal_id`, `customer_id`, `total_amount`, `total_paid`, `memo`, `table_id`, `guest`, `datetime`, `update_date`, `paid`, `reason`, `void_user_id`, `printed`, `inactive`, `waiter_id`, `split`, `serve_no`, `billed`, `sync_id`) VALUES (5, NULL, 10, NULL, NULL, 'dinein', 1, 1, 1, NULL, '392.14285714286', '0', NULL, 230, '4', '2018-06-25 09:43:38', '2018-06-25 09:45:07', 0, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL);
INSERT INTO trans_sales (`sales_id`, `mobile_sales_id`, `type_id`, `trans_ref`, `void_ref`, `type`, `user_id`, `shift_id`, `terminal_id`, `customer_id`, `total_amount`, `total_paid`, `memo`, `table_id`, `guest`, `datetime`, `update_date`, `paid`, `reason`, `void_user_id`, `printed`, `inactive`, `waiter_id`, `split`, `serve_no`, `billed`, `sync_id`) VALUES (6, NULL, 10, NULL, NULL, 'retail', 1, 1, 1, NULL, '630', '0', NULL, NULL, '0', '2018-06-25 09:45:24', '2018-06-25 09:45:32', 0, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL);
INSERT INTO trans_sales (`sales_id`, `mobile_sales_id`, `type_id`, `trans_ref`, `void_ref`, `type`, `user_id`, `shift_id`, `terminal_id`, `customer_id`, `total_amount`, `total_paid`, `memo`, `table_id`, `guest`, `datetime`, `update_date`, `paid`, `reason`, `void_user_id`, `printed`, `inactive`, `waiter_id`, `split`, `serve_no`, `billed`, `sync_id`) VALUES (7, NULL, 10, NULL, NULL, 'retail', 1, 1, 1, NULL, '0.63', '0', NULL, NULL, '0', '2018-06-25 09:45:40', '2018-06-25 09:46:31', 0, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL);
INSERT INTO trans_sales (`sales_id`, `mobile_sales_id`, `type_id`, `trans_ref`, `void_ref`, `type`, `user_id`, `shift_id`, `terminal_id`, `customer_id`, `total_amount`, `total_paid`, `memo`, `table_id`, `guest`, `datetime`, `update_date`, `paid`, `reason`, `void_user_id`, `printed`, `inactive`, `waiter_id`, `split`, `serve_no`, `billed`, `sync_id`) VALUES (8, NULL, 10, NULL, NULL, 'retail', 1, 1, 1, NULL, '0.51', '0', NULL, NULL, '0', '2018-06-25 09:46:32', '2018-06-25 09:46:37', 0, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL);
INSERT INTO trans_sales (`sales_id`, `mobile_sales_id`, `type_id`, `trans_ref`, `void_ref`, `type`, `user_id`, `shift_id`, `terminal_id`, `customer_id`, `total_amount`, `total_paid`, `memo`, `table_id`, `guest`, `datetime`, `update_date`, `paid`, `reason`, `void_user_id`, `printed`, `inactive`, `waiter_id`, `split`, `serve_no`, `billed`, `sync_id`) VALUES (9, NULL, 10, NULL, NULL, 'retail', 1, 1, 1, NULL, '1.92', '0', NULL, NULL, '0', '2018-06-25 09:46:38', '2018-06-25 09:46:47', 0, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL);


#
# TABLE STRUCTURE FOR: trans_sales_charges
#

DROP TABLE IF EXISTS trans_sales_charges;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO trans_sales_charges (`sales_charge_id`, `sales_id`, `charge_id`, `charge_code`, `charge_name`, `rate`, `absolute`, `amount`, `sync_id`, `datetime`) VALUES (1, 1, 1, 'SCHG', 'Service Charge', '10', 0, '45.089285714286', NULL, '2018-06-25 09:28:38');
INSERT INTO trans_sales_charges (`sales_charge_id`, `sales_id`, `charge_id`, `charge_code`, `charge_name`, `rate`, `absolute`, `amount`, `sync_id`, `datetime`) VALUES (2, 2, 2, 'DCHG', 'Delivery Charge', '5', 0, '38.169642857143', NULL, '2018-06-25 09:39:41');
INSERT INTO trans_sales_charges (`sales_charge_id`, `sales_id`, `charge_id`, `charge_code`, `charge_name`, `rate`, `absolute`, `amount`, `sync_id`, `datetime`) VALUES (3, 5, 1, 'SCHG', 'Service Charge', '10', 0, '32.142857142857', NULL, '2018-06-25 09:45:07');


#
# TABLE STRUCTURE FOR: trans_sales_discounts
#

DROP TABLE IF EXISTS trans_sales_discounts;

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

#
# TABLE STRUCTURE FOR: trans_sales_items
#

DROP TABLE IF EXISTS trans_sales_items;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO trans_sales_items (`sales_item_id`, `sales_id`, `line_id`, `item_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `sync_id`, `datetime`, `nocharge`) VALUES (1, 7, 0, 3, '0.12', '1', '0', 0, NULL, NULL, '2018-06-25 09:46:31', 0);
INSERT INTO trans_sales_items (`sales_item_id`, `sales_id`, `line_id`, `item_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `sync_id`, `datetime`, `nocharge`) VALUES (2, 7, 1, 2, '0.51', '1', '0', 0, NULL, NULL, '2018-06-25 09:46:31', 0);
INSERT INTO trans_sales_items (`sales_item_id`, `sales_id`, `line_id`, `item_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `sync_id`, `datetime`, `nocharge`) VALUES (3, 8, 0, 2, '0.51', '1', '0', 0, NULL, NULL, '2018-06-25 09:46:37', 0);
INSERT INTO trans_sales_items (`sales_item_id`, `sales_id`, `line_id`, `item_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `sync_id`, `datetime`, `nocharge`) VALUES (4, 9, 0, 2, '0.51', '1', '0', 0, NULL, NULL, '2018-06-25 09:46:47', 0);
INSERT INTO trans_sales_items (`sales_item_id`, `sales_id`, `line_id`, `item_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `sync_id`, `datetime`, `nocharge`) VALUES (5, 9, 1, 1, '1.41', '1', '0', 0, NULL, NULL, '2018-06-25 09:46:47', 0);


#
# TABLE STRUCTURE FOR: trans_sales_local_tax
#

DROP TABLE IF EXISTS trans_sales_local_tax;

CREATE TABLE `trans_sales_local_tax` (
  `sales_local_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_local_tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

#
# TABLE STRUCTURE FOR: trans_sales_loyalty_points
#

DROP TABLE IF EXISTS trans_sales_loyalty_points;

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

#
# TABLE STRUCTURE FOR: trans_sales_menu_modifiers
#

DROP TABLE IF EXISTS trans_sales_menu_modifiers;

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

#
# TABLE STRUCTURE FOR: trans_sales_menus
#

DROP TABLE IF EXISTS trans_sales_menus;

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (1, 1, 0, 1186, '230', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:28:56', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (2, 1, 1, 1346, '180', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:28:56', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (3, 1, 2, 924, '95', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:28:56', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (4, 2, 0, 1186, '230', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:39:51', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (5, 2, 1, 1350, '340', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:39:51', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (6, 2, 2, 1189, '285', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:39:51', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (7, 3, 0, 972, '295', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:40:16', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (8, 3, 1, 1206, '300', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:40:16', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (9, 3, 2, 983, '250', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:40:16', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (10, 4, 0, 1187, '355', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:43:02', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (11, 4, 1, 1189, '285', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:43:02', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (12, 4, 2, 1348, '395', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:43:02', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (13, 4, 3, 1355, '270', '2', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:43:02', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (14, 5, 0, 1346, '180', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:45:14', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (15, 5, 1, 1349, '180', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:45:14', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (16, 6, 0, 1349, '180', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:45:39', 0);
INSERT INTO trans_sales_menus (`sales_menu_id`, `sales_id`, `line_id`, `menu_id`, `price`, `qty`, `discount`, `no_tax`, `remarks`, `kitchen_slip_printed`, `free_user_id`, `sync_id`, `datetime`, `nocharge`) VALUES (17, 6, 1, 975, '450', '1', '0', 0, NULL, 1, NULL, NULL, '2018-06-25 09:45:39', 0);


#
# TABLE STRUCTURE FOR: trans_sales_no_tax
#

DROP TABLE IF EXISTS trans_sales_no_tax;

CREATE TABLE `trans_sales_no_tax` (
  `sales_no_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_no_tax_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO trans_sales_no_tax (`sales_no_tax_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (1, 1, '0', NULL, '2018-06-25 09:28:38');
INSERT INTO trans_sales_no_tax (`sales_no_tax_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (2, 2, '0', NULL, '2018-06-25 09:39:41');
INSERT INTO trans_sales_no_tax (`sales_no_tax_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (3, 3, '0', NULL, '2018-06-25 09:40:10');
INSERT INTO trans_sales_no_tax (`sales_no_tax_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (4, 4, '0', NULL, '2018-06-25 09:42:48');
INSERT INTO trans_sales_no_tax (`sales_no_tax_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (5, 5, '0', NULL, '2018-06-25 09:45:07');
INSERT INTO trans_sales_no_tax (`sales_no_tax_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (6, 6, '0', NULL, '2018-06-25 09:45:32');
INSERT INTO trans_sales_no_tax (`sales_no_tax_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (7, 7, '0', NULL, '2018-06-25 09:46:31');
INSERT INTO trans_sales_no_tax (`sales_no_tax_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (8, 8, '0', NULL, '2018-06-25 09:46:37');
INSERT INTO trans_sales_no_tax (`sales_no_tax_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (9, 9, '0', NULL, '2018-06-25 09:46:47');


#
# TABLE STRUCTURE FOR: trans_sales_payments
#

DROP TABLE IF EXISTS trans_sales_payments;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO trans_sales_payments (`payment_id`, `sales_id`, `payment_type`, `amount`, `to_pay`, `reference`, `card_type`, `card_number`, `approval_code`, `user_id`, `datetime`, `sync_id`) VALUES (1, 2, 'cash', '893.17', '893.17', NULL, NULL, NULL, NULL, 1, '2018-06-25 09:39:46', NULL);
INSERT INTO trans_sales_payments (`payment_id`, `sales_id`, `payment_type`, `amount`, `to_pay`, `reference`, `card_type`, `card_number`, `approval_code`, `user_id`, `datetime`, `sync_id`) VALUES (2, 3, 'credit', '845', '845', NULL, 'AmEx', '123', '123', 1, '2018-06-25 09:41:47', NULL);
INSERT INTO trans_sales_payments (`payment_id`, `sales_id`, `payment_type`, `amount`, `to_pay`, `reference`, `card_type`, `card_number`, `approval_code`, `user_id`, `datetime`, `sync_id`) VALUES (3, 4, 'cash', '1575', '1575', NULL, NULL, NULL, NULL, 1, '2018-06-25 09:42:54', NULL);
INSERT INTO trans_sales_payments (`payment_id`, `sales_id`, `payment_type`, `amount`, `to_pay`, `reference`, `card_type`, `card_number`, `approval_code`, `user_id`, `datetime`, `sync_id`) VALUES (4, 1, 'cash', '550.09', '550.09', NULL, NULL, NULL, NULL, 1, '2018-06-25 09:43:20', NULL);


#
# TABLE STRUCTURE FOR: trans_sales_tax
#

DROP TABLE IF EXISTS trans_sales_tax;

CREATE TABLE `trans_sales_tax` (
  `sales_tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_tax_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO trans_sales_tax (`sales_tax_id`, `sales_id`, `name`, `rate`, `amount`, `sync_id`, `datetime`) VALUES (1, 1, 'VAT', '12', '54.107142857143', NULL, '2018-06-25 09:28:38');
INSERT INTO trans_sales_tax (`sales_tax_id`, `sales_id`, `name`, `rate`, `amount`, `sync_id`, `datetime`) VALUES (2, 2, 'VAT', '12', '91.607142857143', NULL, '2018-06-25 09:39:41');
INSERT INTO trans_sales_tax (`sales_tax_id`, `sales_id`, `name`, `rate`, `amount`, `sync_id`, `datetime`) VALUES (3, 3, 'VAT', '12', '90.535714285714', NULL, '2018-06-25 09:40:10');
INSERT INTO trans_sales_tax (`sales_tax_id`, `sales_id`, `name`, `rate`, `amount`, `sync_id`, `datetime`) VALUES (4, 4, 'VAT', '12', '168.75', NULL, '2018-06-25 09:42:48');
INSERT INTO trans_sales_tax (`sales_tax_id`, `sales_id`, `name`, `rate`, `amount`, `sync_id`, `datetime`) VALUES (5, 5, 'VAT', '12', '38.571428571429', NULL, '2018-06-25 09:45:07');
INSERT INTO trans_sales_tax (`sales_tax_id`, `sales_id`, `name`, `rate`, `amount`, `sync_id`, `datetime`) VALUES (6, 6, 'VAT', '12', '67.5', NULL, '2018-06-25 09:45:32');
INSERT INTO trans_sales_tax (`sales_tax_id`, `sales_id`, `name`, `rate`, `amount`, `sync_id`, `datetime`) VALUES (7, 7, 'VAT', '12', '0.0675', NULL, '2018-06-25 09:46:31');
INSERT INTO trans_sales_tax (`sales_tax_id`, `sales_id`, `name`, `rate`, `amount`, `sync_id`, `datetime`) VALUES (8, 8, 'VAT', '12', '0.054642857142857', NULL, '2018-06-25 09:46:37');
INSERT INTO trans_sales_tax (`sales_tax_id`, `sales_id`, `name`, `rate`, `amount`, `sync_id`, `datetime`) VALUES (9, 9, 'VAT', '12', '0.20571428571429', NULL, '2018-06-25 09:46:47');


#
# TABLE STRUCTURE FOR: trans_sales_zero_rated
#

DROP TABLE IF EXISTS trans_sales_zero_rated;

CREATE TABLE `trans_sales_zero_rated` (
  `sales_zero_rated_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_zero_rated_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO trans_sales_zero_rated (`sales_zero_rated_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (1, 1, '0', NULL, '2018-06-25 09:28:38');
INSERT INTO trans_sales_zero_rated (`sales_zero_rated_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (2, 2, '0', NULL, '2018-06-25 09:39:41');
INSERT INTO trans_sales_zero_rated (`sales_zero_rated_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (3, 3, '0', NULL, '2018-06-25 09:40:10');
INSERT INTO trans_sales_zero_rated (`sales_zero_rated_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (4, 4, '0', NULL, '2018-06-25 09:42:48');
INSERT INTO trans_sales_zero_rated (`sales_zero_rated_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (5, 5, '0', NULL, '2018-06-25 09:45:07');
INSERT INTO trans_sales_zero_rated (`sales_zero_rated_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (6, 6, '0', NULL, '2018-06-25 09:45:32');
INSERT INTO trans_sales_zero_rated (`sales_zero_rated_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (7, 7, '0', NULL, '2018-06-25 09:46:31');
INSERT INTO trans_sales_zero_rated (`sales_zero_rated_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (8, 8, '0', NULL, '2018-06-25 09:46:37');
INSERT INTO trans_sales_zero_rated (`sales_zero_rated_id`, `sales_id`, `amount`, `sync_id`, `datetime`) VALUES (9, 9, '0', NULL, '2018-06-25 09:46:47');


#
# TABLE STRUCTURE FOR: trans_spoilage
#

DROP TABLE IF EXISTS trans_spoilage;

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

#
# TABLE STRUCTURE FOR: trans_spoilage_details
#

DROP TABLE IF EXISTS trans_spoilage_details;

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

#
# TABLE STRUCTURE FOR: trans_types
#

DROP TABLE IF EXISTS trans_types;

CREATE TABLE `trans_types` (
  `type_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `next_ref` varchar(45) DEFAULT NULL,
  `sync_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO trans_types (`type_id`, `name`, `next_ref`, `sync_id`) VALUES (10, 'sales', '00000005', 656);
INSERT INTO trans_types (`type_id`, `name`, `next_ref`, `sync_id`) VALUES (20, 'receivings', 'R000001', NULL);
INSERT INTO trans_types (`type_id`, `name`, `next_ref`, `sync_id`) VALUES (30, 'adjustment', 'A000001', NULL);
INSERT INTO trans_types (`type_id`, `name`, `next_ref`, `sync_id`) VALUES (11, 'sales void', 'V000001', 272);
INSERT INTO trans_types (`type_id`, `name`, `next_ref`, `sync_id`) VALUES (40, 'customer deposit', 'C000001', NULL);
INSERT INTO trans_types (`type_id`, `name`, `next_ref`, `sync_id`) VALUES (50, 'loyalty card', '00000002', NULL);
INSERT INTO trans_types (`type_id`, `name`, `next_ref`, `sync_id`) VALUES (35, 'spoilage', 'S000001', NULL);
INSERT INTO trans_types (`type_id`, `name`, `next_ref`, `sync_id`) VALUES (55, 'menu receiving', 'RM000001', NULL);


#
# TABLE STRUCTURE FOR: trans_voids
#

DROP TABLE IF EXISTS trans_voids;

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

#
# TABLE STRUCTURE FOR: uom
#

DROP TABLE IF EXISTS uom;

CREATE TABLE `uom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(22) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `num` double DEFAULT '0',
  `to` varchar(22) DEFAULT NULL,
  `inactive` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

INSERT INTO uom (`id`, `code`, `name`, `num`, `to`, `inactive`) VALUES (1, 'ml', 'Mililiter', '0', NULL, 0);
INSERT INTO uom (`id`, `code`, `name`, `num`, `to`, `inactive`) VALUES (2, 'gm', 'Gram', '0', NULL, 0);
INSERT INTO uom (`id`, `code`, `name`, `num`, `to`, `inactive`) VALUES (3, 'pc', 'Piece', '0', NULL, 0);
INSERT INTO uom (`id`, `code`, `name`, `num`, `to`, `inactive`) VALUES (4, 'can', 'Can', '0', '0', 0);
INSERT INTO uom (`id`, `code`, `name`, `num`, `to`, `inactive`) VALUES (5, 'bottle', 'Bottle', '0', '0', 0);
INSERT INTO uom (`id`, `code`, `name`, `num`, `to`, `inactive`) VALUES (6, 'kilo', 'Kilo', '0', '0', 0);
INSERT INTO uom (`id`, `code`, `name`, `num`, `to`, `inactive`) VALUES (7, 'pack', 'Pack', '0', '0', 0);
INSERT INTO uom (`id`, `code`, `name`, `num`, `to`, `inactive`) VALUES (8, 'Serving', 'serving', '0', '0', 0);


#
# TABLE STRUCTURE FOR: user_roles
#

DROP TABLE IF EXISTS user_roles;

CREATE TABLE `user_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `access` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO user_roles (`id`, `role`, `description`, `access`) VALUES (1, 'Administrator ', 'System Administrator', 'all');
INSERT INTO user_roles (`id`, `role`, `description`, `access`) VALUES (2, 'Manager', 'Manager', 'dashboard,items,trans,receiving,adjustment,spoilage,inq,item_inv,inv_move,items,list,gcategories,gsubcategories,glocations,gsuppliers,guom,menus,menulist,menucat,menusubcat,menusched,mods,modslist,modgrps,pos_promos,promos,gift_cards,coupons,charges,grecdiscs,gtaxrates,tblmng,denomination,customers,reps,menu_sales_rep,act_receipts,act_logs,drawer_count,rep_history,setup,control,user');
INSERT INTO user_roles (`id`, `role`, `description`, `access`) VALUES (3, 'Employee', 'Employee', 'general_settings,grecdiscs');
INSERT INTO user_roles (`id`, `role`, `description`, `access`) VALUES (4, 'OIC', 'Officer In Charge', 'items,trans,receiving,adjustment,spoilage,inq,item_inv,inv_move,items,list,gcategories,gsubcategories,glocations,gsuppliers,guom');
INSERT INTO user_roles (`id`, `role`, `description`, `access`) VALUES (5, 'jaypee', '', 'items,trans,receiving,adjustment,spoilage,inq,item_inv,inv_move,items,list,gcategories,gsubcategories,glocations,gsuppliers,guom,menus,menulist,menucat,menusubcat,menusched,mods,modslist,modgrps,pos_promos,promos,gift_cards,coupons,charges,grecdiscs,gtaxrates,tblmng,denomination');


#
# TABLE STRUCTURE FOR: users
#

DROP TABLE IF EXISTS users;

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
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', '00001', 'Rey', 'Coloma', 'Tejada', 'Jr.', 1, 'rey.tejada01@gmail.com', 'male', '2014-06-16 14:41:31', 0, 25, '2018-03-29 04:15:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (35, 'jess', '5f4dcc3b5aa765d61d8327deb882cf99', '1234', 'Jess', '', 'Alison', '', 4, 'asd@ac.com', 'male', '2017-02-01 09:08:26', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (45, 'BIM', 'a754369b14a8011f85262e17fa78f278', '1120', 'BIM', 'bim', 'VALENZUELAa', '', 3, '', 'male', '2017-03-12 21:27:56', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (46, 'teresa23m', 'c6f48ca460b0e918529785b70e9e1296', '2625', 'Teresa', 'M', 'Rostoll', '', 1, 'teresa.rostoll@tarraco.com.ph', 'female', '2017-03-14 16:06:14', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (47, 'ramcel.barao', '8f17db11954253bf2f01342423f325b9', '', 'Ramcel', 'Ramos', 'Barao', 'Mr', 1, 'ramcelrbarao@tarraco.com.ph', 'male', '2017-03-14 18:31:17', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (50, 'Ruzel', 'c60d060b946d6dd6145dcbad5c4ccf6f', '1118', 'Ruzel', '', 'Camposano', '', 1, '', 'male', '2017-03-21 12:23:50', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (55, 'Regina', 'd14ceb37e82ddfe68777e8454997ed7d', '0721', 'Regina', '', 'Estrada', '', 3, '', 'female', '2017-07-26 19:26:32', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (56, 'Cha', 'e74c0d42b4433905293aab661fcf8ddb', '1918', 'Charlene', '', 'Ramirez', '', 3, '', 'female', '2017-07-26 19:27:03', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (57, 'Bless', '8c249675aea6c3cbd91661bbae767ff1', '1986', 'Blessie', '', 'Diana', '', 3, '', 'female', '2017-07-26 19:27:59', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (58, 'Daniela', 'd93591bdf7860e1e4ee2fca799911215', '1235', 'Daniela', '', 'Dela Cruz', '', 2, '', 'female', '2017-07-27 15:24:14', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (59, 'Milver', 'fe7ecc4de28b2c83c016b5c6c2acd826', '4242', 'Milver ', '', 'Mababangloob', '', 2, '', 'male', '2017-08-01 18:45:09', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (60, 'Michael ', '7b9dc501afe4ee11c56a4831e20cee71', '9898', 'Michael ', '', 'Vecera ', '', 3, '', 'male', '2017-08-01 18:51:08', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (61, 'Ronnel', '3e323529d2060298979f801772e0340e', '0621', 'Ronnel ', '', 'Bagas', '', 3, '', 'male', '2017-08-01 19:12:55', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (62, 'Rudith', '0026e579a258f1fc012951af08496bd7', '0421', 'Rudith', '', 'Loniza', '', 3, '', 'female', '2017-08-01 19:13:25', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (63, 'John', '856fc81623da2150ba2210ba1b51d241', '1423', 'John', '', 'Carlo', '', 3, '', 'male', '2017-08-01 19:13:50', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (64, 'test', '098f6bcd4621d373cade4e832627b4f6', '111111', 'Jsutin', 'Test', 'test', '', 3, 'test@test.com', 'male', '2018-02-06 09:19:19', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (65, 'test', '098f6bcd4621d373cade4e832627b4f6', '111111', 'Jsutin', 'Test', 'test', '', 3, 'test@test.com', 'male', '2018-02-06 09:19:19', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (66, 'paul', '5f4dcc3b5aa765d61d8327deb882cf99', '333333', 'Paul', 'Test', 'ing', '', 1, 'pass@pas.com', 'male', '2018-02-06 09:20:33', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (67, 'dasd', 'a8f5f167f44f4964e6c998dee827110c', '789456', 'Adsad', 'dadasd', 'dasdad', '', 1, 'addas@dasd.com', 'male', '2018-02-06 09:21:27', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (68, 'rqwrwe', '3729cea53a6e9c5fda24cd6bfcab160a', '666666', 'dfdsdf', 'fdsfdsf', 'hgfh', '', 1, 'rewr@ad.com', 'male', '2018-02-06 09:25:04', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (69, 'adas', '0371efccf094028bd348bef873b5ce90', '5555555', 'asdsa', 'dsa', 'dsa', '', 1, 'dasd@d.com', 'male', '2018-02-06 09:25:45', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (70, 'qwewqe', '0db23143d4a97ef87410700c73775a97', '777777', 'wqeqwe', 'ewqewq', 'ewqe', '', 1, 'kok@a.com', 'male', '2018-02-06 09:26:20', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (71, 'qweqwe', 'd8578edf8458ce06fbc5bb76a58c5ca4', '789789', 'trytry', 'yte', 'yytry', '', 1, 'wqe@a.co', 'male', '2018-02-06 09:28:35', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (72, 'qweqwe', 'd8578edf8458ce06fbc5bb76a58c5ca4', '789785', 'trytry', 'yte', 'yytry', '', 1, 'wqe@a.co', 'male', '2018-02-06 09:29:46', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (73, 'qweqwe', 'd8578edf8458ce06fbc5bb76a58c5ca4', '789785', 'trytry', 'yte', 'yytry', '', 1, 'wqe@a.co', 'male', '2018-02-06 09:29:46', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (74, 'qweqwe', 'd8578edf8458ce06fbc5bb76a58c5ca4', '789787', 'trytry', 'yte', 'yytry', '', 1, 'wqe@a.co', 'male', '2018-02-06 09:30:38', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (75, 'qweqwe', 'd8578edf8458ce06fbc5bb76a58c5ca4', '789780', 'trytry', 'yte', 'yytry', '', 1, 'wqe@a.co', 'male', '2018-02-06 09:31:20', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (76, 'qweqwe', 'd8578edf8458ce06fbc5bb76a58c5ca4', '789780', 'trytry', 'yte', 'yytry', '', 1, 'wqe@a.co', 'male', '2018-02-06 09:31:20', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (77, 'qweqwe', 'd8578edf8458ce06fbc5bb76a58c5ca4', '789780', 'trytry', 'yte', 'yytry', '', 1, 'wqe@a.co', 'male', '2018-02-06 09:31:20', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (78, 'qweqwe', 'd8578edf8458ce06fbc5bb76a58c5ca4', '789780', 'trytry', 'yte', 'yytry', '', 1, 'wqe@a.co', 'male', '2018-02-06 09:31:20', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (79, 'qweqwe', 'd8578edf8458ce06fbc5bb76a58c5ca4', '7897804', 'trytry', 'yte', 'yytry', '', 1, 'wqe@a.co', 'male', '2018-02-06 09:32:57', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (80, 'qweqwe', 'd8578edf8458ce06fbc5bb76a58c5ca4', '7897804', 'trytry', 'yte', 'yytry', '', 1, 'wqe@a.co', 'male', '2018-02-06 09:32:57', 0, 26, '2018-03-29 04:16:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (81, 'nickominaj', '5f4dcc3b5aa765d61d8327deb882cf99', '110110', 'nickos', 'minajaaaa', 'minaj', '', 1, 'nickoss@ma.com', 'male', '2018-02-06 09:33:48', 0, 27, '2018-03-29 04:17:00');
INSERT INTO users (`id`, `username`, `password`, `pin`, `fname`, `mname`, `lname`, `suffix`, `role`, `email`, `gender`, `reg_date`, `inactive`, `sync_id`, `datetime`) VALUES (82, 'justint', '5f4dcc3b5aa765d61d8327deb882cf99', '01234', 'Justin', 'This', 'Test', '', 1, 'justin@test.com', 'male', '2018-02-19 14:01:35', 0, 110, '2018-02-20 06:02:02');


#
# TABLE STRUCTURE FOR: vistamall
#

DROP TABLE IF EXISTS vistamall;

CREATE TABLE `vistamall` (
  `id` int(11) NOT NULL DEFAULT '0',
  `stall_code` varchar(50) DEFAULT NULL,
  `sales_dep` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO vistamall (`id`, `stall_code`, `sales_dep`, `file_path`) VALUES (1, '12345678', '00', 'C:/VISTAMALL');


