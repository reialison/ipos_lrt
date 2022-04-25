<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
###############################################################################################
# POS SETTINGS
	define('LOADER',					false);#LOADING AT START OF PAGE

	define('BRANCH_CODE',				'MAX_MAIN');
	define('RECEIPT_ADDITIONAL_HEADER_BELOW_BRANCH', 'MAX');


	define('TERMINAL_ID',				'1');
	define('TERMINAL_NUMBER',			'01');
	define('PC_ID',						'1');
	##########################################
	define('ORDERING_STATION',			false);
	##########################################
	define('NEED_FOOD_SERVER',			false);
	define('AUTO_ADD_SERVICE_CHARGE',	false);
	define('AUTO_ADD_SERVICE_CHARGE_DELIVERY',	false);
	define('AUTO_ADD_SERVICE_CHARGE_TAKEOUT',	false);
	define('AUTO_ADD_HANDLING_CHARGE_PANDA',	false);
	define('ORDER_SLIP_PRINTER_SETUP',	true);
	define('SERVER_NO_SETUP',			false);
	
	define('SERVICE_CHARGE_ID',			'1');
	define('SERVICE_CHARGE_DELIVERY_ID','2');
	define('SERVICE_CHARGE_TAKEOUT_ID', '3');
	define('SERVICE_CHARGE_PANDA_ID', '3');
	define('CHECK_OIC_ID',				false);
	define('OIC_ID',					'4');
	define('MANAGER_CALL_IDS',			serialize(array(1,2,4)));

	define('BASE_TAX',					0.12); // Please use base tax form (eg. 0.12) not num form (12)
	define('SALE_TYPES',				serialize(array('DINEIN','COUNTER','DELIVERY','PICKUP','TAKEOUT','DRIVETHRU','RETAIL','FOODPANDA')));
	define('BACKUP_FOLDER',				'C:/xampp/htdocs/dine/backup/xreads'); // BACKUP FOLDER
	
	define('HIDE_DRAWER_AMOUNT',		false);
	define('BATTERY_DATE',		true); ##TRUE PARA ICHECHECK NYA YUN DATE KUNG BUMALIK SA DATI
	define('HIDECHIT',		true); ##TRUE PARA MAHIDE YUN CHIT SA SALES
	define('PRODUCT_TEST',		true); ##TRUE PARA MAHIDE YUN PRODUCT TEST SA SALES
	define('SHOW_NEW_SUBCATEGORY',		true); ##TRUE PARA LUMABAS YUN NEW CATEGORY SA MAINTE AND SA SELLING UI
	define('LOCAVORE',      false); ##TRUE IF LOCAVORE STORE
	define('KERMIT',      false); ##TRUE IF KERMIT STORE
###############################################################################################
# PRINTERS
	// define('BILLING_PRINTER',			false);
	define('CHECKLIST_PRINTER',			'LPT1');
	define('CHECKLIST_PRINTER_NUM',			1);
	define('DISPATCH_PRINTER',			'LPT1');
	define('DISPATCH_PRINTER_NUM',			1);
	define('DISPATCH_PRINTER_NAME',			'DISPATCH');
	define('RECEIPT_PRINTER',			'LPT1');
	define('RECEIPT_PRINTER_NUM',			1);


	define('BILLING_PRINTER',			'DEFAULT');
	#---------------------------------------------------------------------------------------------------------------------------------------------
	define('FOOD_ID',				'1');       # ID SA SUBCATEGORIES
	define('KITCHEN_PRINTER_NAME',		'KITCHEN');# TITLE NA LALABAS SA ORDER SLIP
	// define('KITCHEN_PRINTER_NUM',		false);# PAG NAKA FALSE, 0 YUNG NUMBER NG PRINT
	define('KITCHEN_PRINTER_NUM',		1);        # KUNG ILAN YUNG IPRIPRINT
	// define('KITCHEN_PRINTER',			false);# PAG NAKA FALSE, SUSUNDIN YUNG NAME NUNG NASA SETUP
	define('KITCHEN_PRINTER',			'DEFAULT');# NAME NG PRINTER SA DEVICES AND PRINTERS, PAG NAKA DEFAULT, YUNG NAKA CHECK NA DEFAULT PRINTER 
	#---------------------------------------------------------------------------------------------------------------------------------------------
	define('BEVERAGE_ID',				'2');       # ID SA SUBCATEGORIES
	define('BEVERAGE_PRINTER_NAME',		'BEVERAGE');# TITLE NA LALABAS SA ORDER SLIP
	// define('BEVERAGE_PRINTER_NUM',		false); # PAG NAKA FALSE, 0 YUNG NUMBER NG PRINT
	define('BEVERAGE_PRINTER_NUM',		1);			# KUNG ILAN YUNG IPRIPRINT
	// define('BEVERAGE_PRINTER',			false); # PAG NAKA FALSE, SUSUNDIN YUNG NAME NUNG NASA SETUP
	define('BEVERAGE_PRINTER',			'DEFAULT'); # NAME NG PRINTER SA DEVICES AND PRINTERS, PAG NAKA DEFAULT, YUNG NAKA CHECK NA DEFAULT PRINTER 
	#---------------------------------------------------------------------------------------------------------------------------------------------	
	define('PRINT1_ID',					'3');		# ID SA SUBCATEGORIES
	define('PRINT1_PRINTER_NAME',		'PASTRIES');# TITLE NA LALABAS SA ORDER SLIP
	define('PRINT1_PRINTER_NUM',		1);			# KUNG ILAN YUNG IPRIPRINT
	define('PRINT1_PRINTER',			'DEFAULT');
###############################################################################################
# MALL SETTINGS
	// define('MALL_ENABLED',				false);
	define('MALL_ENABLED',				true);
	// define('MALL',						false);
	// define('MALL',						'ortigas');
	// define('MALL',						'robinsons');
	//define('MALL',						'araneta');
	// define('MALL',						'megamall');
	// define('MALL',						'stalucia');
	// define('MALL',						'ayala');
	// define('MALL',						'eton');
	// define('MALL',						'vistamall');
	// define('MALL',						'cbmall');
 	// define('MALL',						'megaworld');
 	// define('MALL',						'rockwell');
 	define('MALL',						'miaa');
 	// define('MALL',						'shangrila');
###############################################################################################
# FOR BAKER's STUDIO PROMO
	define('BUY2TAKE1'				,	false);
	define('BUY2TAKE1_STARTTIME'	,	'08:30 PM');
	define('BUY2TAKE1_ENDTIME'		,	'11:00 PM');	
###############################################################################################
# TRANS TYPES
	define('GC',						'8');
	define('FREE_SALE',			    	'99');
	define('SALES_TRANS',				'10');
	define('SALES_VOID_TRANS',			'11');
	define('GC_TRANS',					'12');
	define('RECEIVE_TRANS',				'20');
	define('SPOIL_TRANS',				'35');
	define('ADJUSTMENT_TRANS',			'30');
	define('CUST_DEPOSIT_TRANS',		'40');
	define('LOYALTY_CARD',				'50');
	define('RECEIVE_MENU_TRANS',		'55');
	define('STORE_ORDER',				'60');
	define('EXPENSE_ENTRY',				'65');
	define('ADJUSTMENT_MENU_TRANS',		'70');
	define('T_SSO',27);
	define('X_READ',					1);
	define('Z_READ',					2);
###############################################################################################
# FOR I FOODS DISCOUNTS  AND LOCAL TAX
	define('DISCOUNT_NET_OF_VAT'	,	false);
	define('DISCOUNT_NET_OF_VAT_EX'	,	'PWDISC');
	define('ADD_CHARGES_NET_OF_VAT'	,	false);
	define('PWDDISC'				,	'PWDISC');
################################################################################################
# PRINTER PAPER SETTINGS
	### FOR 80 MM
		// define('PAPER_WIDTH',			38);
		// define('PAPER_LINE',				"======================================");
		// define('PAPER_LINE_SINGLE',		"--------------------------------------");
		// define('PAPER_DET_COL_1',		4);
		// define('PAPER_DET_COL_2',		26);
		// define('PAPER_DET_SUBCOL',		18);
		// define('PAPER_DET_COL_3',		8);
		// define('PAPER_TOTAL_COL_1',		28);
		// define('PAPER_TOTAL_COL_2',		10);
		// define('PAPER_RD_COL_1',		18);
		// define('PAPER_RD_COL_2',		7);
		// define('PAPER_RD_COL_3',		13);
		// define('PAPER_RD_COL_3_3',		13);
		// define('PAPER_RD_COL_MID',		18);
		// define('PAPER_RD_COL_1_2',		4);
		// define('PAPER_RD_COL_1_4',		12);
		// define('PAPER_RECEIPT_TEXT',	10);
		// define('PAPER_RECEIPT_INPUT',	28);
		// define('PAPER_RECEIPT_TEXT_FT',	14);
		// define('PAPER_RECEIPT_INPUT_FT',30);
		// define('PAPER_RECEIPT_INPUT_FT24',24);


	### FOR 76 MM
		define('PAPER_WIDTH',			30);
		define('PAPER_LINE',			"=================================");
		define('PAPER_LINE_SINGLE',		"---------------------------------");
		define('PAPER_DET_COL_1',		2);
		define('PAPER_DET_COL_2',		23);
		define('PAPER_DET_SUBCOL',		17);
		define('PAPER_DET_COL_3',		8);
		define('PAPER_TOTAL_COL_1',		23);
		define('PAPER_TOTAL_COL_2',		10);
		define('PAPER_RD_COL_1',		18);
		define('PAPER_RD_COL_2',		5);
		define('PAPER_RD_COL_3',		8);
		define('PAPER_RD_COL_3_3',		10);
		define('PAPER_RD_COL_1_4',		12);
		define('PAPER_RD_COL_1_2',		3);
		define('PAPER_RD_COL_MID',		13);
		define('PAPER_RECEIPT_TEXT',	10);
		define('PAPER_RECEIPT_INPUT',	23);
		define('PAPER_RECEIPT_TEXT_FT',	14);
		define('PAPER_RECEIPT_INPUT_FT',26);
################################################################################################
/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);
/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


#DEFINE TIME RANGES
$time = array();
$time[] = array("FTIME"=>"0:00","TTIME"=>"1:00");
$time[] = array("FTIME"=>"1:00","TTIME"=>"2:00");
$time[] = array("FTIME"=>"2:00","TTIME"=>"3:00");
$time[] = array("FTIME"=>"3:00","TTIME"=>"4:00");
$time[] = array("FTIME"=>"4:00","TTIME"=>"5:00");
$time[] = array("FTIME"=>"5:00","TTIME"=>"6:00");
$time[] = array("FTIME"=>"6:00","TTIME"=>"7:00");
$time[] = array("FTIME"=>"7:00","TTIME"=>"8:00");
$time[] = array("FTIME"=>"8:00","TTIME"=>"9:00");
$time[] = array("FTIME"=>"9:00","TTIME"=>"10:00");
$time[] = array("FTIME"=>"10:00","TTIME"=>"11:00");
$time[] = array("FTIME"=>"11:00","TTIME"=>"12:00");
$time[] = array("FTIME"=>"12:00","TTIME"=>"13:00");
$time[] = array("FTIME"=>"13:00","TTIME"=>"14:00");
$time[] = array("FTIME"=>"14:00","TTIME"=>"15:00");
$time[] = array("FTIME"=>"15:00","TTIME"=>"16:00");
$time[] = array("FTIME"=>"16:00","TTIME"=>"17:00");
$time[] = array("FTIME"=>"17:00","TTIME"=>"18:00");
$time[] = array("FTIME"=>"18:00","TTIME"=>"19:00");
$time[] = array("FTIME"=>"19:00","TTIME"=>"20:00");
$time[] = array("FTIME"=>"20:00","TTIME"=>"21:00");
$time[] = array("FTIME"=>"21:00","TTIME"=>"22:00");
$time[] = array("FTIME"=>"22:00","TTIME"=>"23:00");
$time[] = array("FTIME"=>"23:00","TTIME"=>"0:00");
define('TIMERANGES', serialize($time));

/* End of file constants.php */
/* Location: ./application/config/constants.php */

define('menu_folder','uploads/menus/');
define('restograph_folder','restograph/');

// define('LOCALSYNC',false); // first version of syncing commented out 

define('AUTOLOCALSYNC',true); // flag for auto run on syncing db to db main sync via batch file
define('MASTERMIGRATION',true); 
define('MIGRATED_MAIN_DB','main');  // name of main database connection based on database.php
define('MIGRATED_MASTER_DB','master'); // name of master database connection based on database.php
define('CUSTOMIZED_POS','custom'); // name of customized pos database connection based on database.php

define('EXECUTE_MASTER_WITH_ZREADING',false); // enable if the system will migrate the data on the server when zreading was executed
// define('EXECUTE_MASTER_WITH_EVERY_SALES',true); // enable if the system will migrate the data on the server after settlemenr of 1 order


/**
PRINT_VERSION
 V1 - Usual print with bat file and notepad 
 V2 - Using WindowsPrintConnector Class - no bat file and notepad needed
 V3 - Using HTML print method
**/

define('PRINT_VERSION','V1');
define('OPERATING_SYSTEM','Windows');

/** font sizes **/

	//order slip
	define('f_order_slip_w',2); // font size of order slip in terms of width
	define('f_order_slip_h',1); // font size of order slip in terms of height , 0 is the default normal font height like 12px
	define('dot_matrix_os_font_size',16); // font size of order slip if printer is dot matrix instead of thermal

	define('dot_matrix_amount_due_font_size',16); // font size of receipt "Amount due" if printer is dot matrix instead of thermal

	//receipt logo 
	define('RECEIPT_LOGO_ENABLE',false);
	define('RECEIPT_LOGO',FCPATH."img/receipt_logo.jpg");


	define('UPLOAD_BEFORE_MIGRATE',true); // upload master files (menu, items, categories,recipe, etc) from local POS   , 
										   //if no migrated master files the migration of master file from master to main will not take place


	define('MIGRATION_VERSION','2'); /**1: VERSION 1 with Task Scheduler , 2: VERSION 2 direct transfer via transfer **/

	//START == for MIGRATION VERSION 2
		define('main_bat_path','C:/xampp/htdocs/ipos_max/mainsynccall.bat');
		define('master_bat_path','C:/xampp/htdocs/ipos_max/mainsynccall.bat');
		define('main_cron_path','C:/xampp/htdocs/ipos_max/auto_run_sync_main.php');
		define('master_cron_path','C:/xampp/htdocs/ipos_max/auto_run_sync_master.php');
		define('printer_cron_path','C:/xampp/htdocs/ipos_max/auto_run_printer_setup.php');
		define('printer_ping_cron_path','C:/xampp/htdocs/ipos_max/auto_run_ping_printer.php');

	//END == for MIGRATION VERSION 2

	define("MINI_POS",false); // check if other menus are disabled , if true some menus are not available


	//for restarting printer
	define('ping_printer','C:/xampp/htdocs/ipos_max/setup/ping.bat'); 

	define('restart_printer','C:/xampp/htdocs/ipos_max/setup/portmap.bat'); 
	define('has_restart_printer',TRUE);
	define('total_number_of_printers',1); // total number of printers assigned to LPT -- this is needed for printer version 2 for autmomatic restart it will count if current LPT connection is equal to total number of LPT found

	define("DINEINTEXT", "DINE IN");
    define("COUNTERTEXT","FOR HERE");
	define("TAKEOUTTEXT","TAKEOUT");
	define("BACK_OFFICE", false);
	define("LOGIN_KEYPAD", true);
	define("GIFTCHEQUE_VALIDATION", false);
	define('App',0);

	define('qrs_folder','qrs/');

	/**BIR ACCOUNTING TEXT FILE EXTRACTION 06242019 @jx**/
	define('BIR_TEXT_ENABLED',FALSE);
	define('BIR_TEXT_PATH','C:/xampp/htdocs/one_accounting/uploads/auto/');
	define('BIR_ONE_ACCTG_PATH','C:\xampp\htdocs\one_accounting');
	define('accounting_cron_path','C:/xampp/htdocs/one_accounting/auto_run_accounting_sales.php');
	/**BIR ACCOUNTING TEXT FILE EXTRACTION 06242019 @jx**/

	/**FOR BACKOFFICE MAINTENANCE @paul 062019**/
	define('REMOVE_MASTER_BUTTON',FALSE); //button for create/add FALSE if show
	define('MASTER_BUTTON_EDIT',TRUE); //button for edit TRUE if show
	/**END FOR BACKOFFICE MAINTENANCE @paul 062019**/

	/**FOR INVENTORY REORDER CHECKING @jed 062019**/
	define('CHECK_REORDER',false); 
	/**END FOR INVENTORY REORDER CHECKING @jed 062019**/



	/**FOR BEVERAGE BARCODE PRINTER**/
	define('BEVERAGE_BARCODE_ENABLED',false);


	/**FOR BEVERAGE BARCODE PRINTER**/


	/**DR LOOKUP IN RECEIVING**/
	define('DR_LOOKUP',true);
	/**DR LOOKUP IN RECEIVING**/

	define('DASHBOARD_TIME', false );//Date and time dashboard screen
	

	define('REQUIRE_CUSTOMER', false );//Date and time dashboard screen

	define('SERVER_ID',  25);//id ng user role na server
	define('SUBCAT_FOOD',  1);//id ng food na subcategory
	define('SUBCAT_BEV',  2);//id ng bev na subcategory
	define('RAMEN_ID',  100);//id ng ramen na subcategory


	define('ENABLE_CHECKLIST',false);
	define('ENABLE_BARCODE_ON_OR',true);
	
	define('MANANGS',false);
	define('ATHLETE_CODE','D1006');
	define('ZERO_REV','zerorev100');
	define('RESERVATION',true);
	define('MCB_CAT_ID',4);
	define('IS_RETAIL',false);


	//FOR AYALA TEXTFILE
	define('EMPDISC_CODE','EMPDISC');
	define('AYALADISC_CODE','AYALADISC');
	define('STOREDISC_CODE','STOREDISC');

	//FOR PRINTER IPS
	define('KITCHEN_IP','192.168.1.203');
	define('PRINTER_DB',false);

	//for online delivery
	define('APP_DB','app');
	define('APP_LOCATION','Max Alabang Town Center');

	define('MENU_COUNT_BUTTONS',21);
	define('TRANS_COUNT_BUTTONS',18);
	define('TRANS_TYPE_COUNT_BUTTONS',14);

	/**PAYMAYA**/
	// define('pym_public_key','pk-Z0OSzLvIcOI2UIvDhdTGVVfRSSeiGStnceqwUE7n0Ah:');
	// define('pym_secret_key','sk-X8qolYjy62kIzEbr0QRK1h4b4KDVHaNcwMYk39jInSl');
	define('pym_public_key','pk-eo4sL393CWU5KmveJUaW8V730TTei2zY8zE4dHJDxkF:');
	define('pym_secret_key','sk-KfmfLJXFdV5t1inYN8lIOwSrueC1G27SCAklBqYCdrU');
	define('pym_environment','SANDBOX');

	//IF CONSOLIDATOR TRUE
	define('CONSOLIDATOR',false);

	//for master api migration with end / (ex.: https://www.example.com/)
	define('master_envi','dev'); // dev or prod
	define('master_api',''); // url

	//for encryption
	define('ENCRYPTED',false);
	define('ENCRYPT_TXT_FILE',false); //TRUE para kay PasalubonganExpress

	//for testing restart printer asdf456qwer789zxcv123
	// define('restart_printer','C:/xampp/htdocs/ipos_viamare_evia_ramen_hakata/setup/portmap_test.bat');	

	define('PRINT_ORDER_NO',false); //true for printing customer order number

define('PRODUCT_KEY',false); //'MjAyY2I5NjJhYzU5MDc1Yjk2NGIwNzE1MmQyMzRiNzA='