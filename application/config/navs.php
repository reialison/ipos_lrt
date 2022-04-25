<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//////////////////////////////////////////////////
/// SIDE BAR LINKS                            ///
////////////////////////////////////////////////
// echo 
$nav = array();

if(!MINI_POS){

	$nav['dashboard'] = array('title'=>'<i class="icon-speedometer"></i> <span class="title">Sales Dashboard</span>','path'=>'dashboard','exclude'=>0);
	$nav['member_dashboard'] = array('title'=>'<i class="icon-users"></i> <span class="title">Member Dashboard</span>','path'=>'member_dashboard','exclude'=>0);
	$inq['cust_inq'] = array('title'=>'Customer Inquiry','path'=>'customers/customer_inquiry','exclude'=>0);
	//item list
	$inq['inv_move'] = array('title'=>'Recipe Inventory Movements','path'=>'items/inv_move','exclude'=>0);
}

	$inq['receiving_inq'] = array('title'=>'Item Inventory Movement','path'=>'items/menu_move','exclude'=>0);
	$inq['mod_inv_move'] = array('title'=>'Modifier Inventory Movement','path'=>'items/mod_inv_moves','exclude'=>0);
	$inq['item_inv'] = array('title'=>' Recipe Quantity On Hand','path'=>'items/inventory','exclude'=>0);
$nav['inq'] = array('title'=>'<i class="fa fa-commenting-o"></i><span class="title">Inquiries</span>','path'=>$inq,'exclude'=>0);
	$menus['brands'] = array('title'=>'<span class="title">Brands</span>','path'=>'settings/brands','exclude'=>0);
	$menus['charges'] = array('title'=>'<span class="title">Charges</span>','path'=>'charges','exclude'=>0);
	$menus['coupons'] = array('title'=>'<span class="title">Coupons</span>','path'=>'coupons','exclude'=>0);
	if(!MINI_POS){

		$menus['customers'] = array('title'=>'<span class="title">Customers</span>','path'=>'pos_customers','exclude'=>0);
    	$menus['denomination'] = array('title'=>'Denominations','path'=>'settings/denomination','exclude'=>0);
	}

	if(!CONSOLIDATOR){
		$menus['gift_cards'] = array('title'=>'<span class="title">Gift Cheque</span>','path'=>'gift_cards','exclude'=>0);
	}
	
	$menus['gcategories'] = array('title'=>'Component Categories','path'=>'settings/categories','exclude'=>0);
	$menus['list'] = array('title'=>'Component List','path'=>'items','exclude'=>0);
	$menus['gsubcategories'] = array('title'=>'Component Sub Categories','path'=>'settings/subcategories','exclude'=>0);
	// $menus['items'] = array('title'=>'<span class="title">Items</span>','path'=>$items,'exclude'=>0);
	$menus['glocations'] = array('title'=>'Locations','path'=>'settings/locations','exclude'=>0);
	$menus['menucat'] = array('title'=>'Item Categories','path'=>'menu/categories','exclude'=>0);
	$menus['menulist'] = array('title'=>'Item List','path'=>'menu','exclude'=>0);
	if(SHOW_NEW_SUBCATEGORY){
		$menus['menusubcat'] = array('title'=>'Item Types','path'=>'menu/subcategories','exclude'=>0);
		$menus['menusubcat2'] = array('title'=>'Item Sub Categories','path'=>'menu/subcategories_new','exclude'=>0);
	}else{
		$menus['menusubcat'] = array('title'=>'Item Types','path'=>'menu/subcategories','exclude'=>0);
	}

	if(!MINI_POS){

		$menus['modslist'] = array('title'=>'Modifiers List','path'=>'mods','exclude'=>0);
		$menus['modgrps'] = array('title'=>'Modifiers Groups','path'=>'mods/groups','exclude'=>0);
		$menus['promos'] = array('title'=>'Promo Discount','path'=>'settings/promos','exclude'=>0);
		$menus['promos_menu'] = array('title'=>'Promo Item','path'=>'promo/free_menu','exclude'=>0);
		$menus['menusched'] = array('title'=>'Schedules','path'=>'menu/schedules','exclude'=>0);
	}
	// $menus['mods'] = array('title'=>'<span class="title">Modifiers</span>','path'=>$mods,'exclude'=>0);
	// $menus['pos_promos'] = array('title'=>'<span class="title">Promos</span>','path'=>$pos_promos,'exclude'=>0);
	$menus['grecdiscs'] = array('title'=>'Receipt Discounts','path'=>'settings/receipt_discounts','exclude'=>0);
	$menus['rsvtblmng'] = array('title'=>'Reservation Table Management','path'=>'settings/reserve_seat_management','exclude'=>0);
	$menus['menusched'] = array('title'=>'Schedules','path'=>'menu/schedules','exclude'=>0);
	$menus['gsuppliers'] = array('title'=>'Suppliers','path'=>'settings/suppliers2','exclude'=>0);
	$menus['tblmng'] = array('title'=>'Table Management','path'=>'settings/seat_management','exclude'=>0);
	// $menus['tblmngoth'] = array('title'=>'Takeout Table','path'=>'settings/seat_management_other','exclude'=>0);
	$menus['gtaxrates'] = array('title'=>'Tax Rates','path'=>'settings/tax_rates','exclude'=>0);
	$menus['guom'] = array('title'=>'UOM','path'=>'settings/uom','exclude'=>0);
	$menus['trans_type'] = array('title'=>'Transaction Types','path'=>'settings/trans_type','exclude'=>0);
	$menus['payment_group'] = array('title'=>'Payment Group','path'=>'settings/payment_group','exclude'=>0);
	$menus['payment'] = array('title'=>'Payment Types','path'=>'settings/payment_mode','exclude'=>0);
$nav['menus'] = array('title'=>'<i class="icon-equalizer"></i> <span class="title">Maintenance</span>','path'=>$menus,'exclude'=>0);

	$nav['manager'] = array('title'=>'<i class="icon-user"></i> <span class="title">Manager Setup</span>','path'=>'manager','exclude'=>0);

    if(!MINI_POS){

		$reps['act_logs'] = array('title'=>'Activity Logs','path'=>'reports/activity_logs_ui','exclude'=>0);
    }
	$reps['daily_be'] = array('title'=>'BIR Daily Sales','path'=>'reports/daily_sales_ui','exclude'=>0);
	$reps['monthly_be'] = array('title'=>'BIR Monthly Sales','path'=>'reports/monthly_sales_ui','exclude'=>0);
	$reps['e_sales'] = array('title'=>'BIR E-Sales','path'=>'reporting/ejournal_rep','exclude'=>0);
	$reps['brand_sales_rep'] = array('title'=>'Brand Sales Report','path'=>'reporting/brand_sales_rep','exclude'=>0);
	// $reps['Cashier Report'] = array('title'=>'Cashier Report','path'=>'reporting/cashier_report','exclude'=>0); // for viamare
    // $reps['Charge Sales Summary Report'] = array('title'=>'Charge Sales Summary Report','path'=>'reporting/charge_sales_summary_report','exclude'=>0); // for viamare
	$reps['cust_balance_rep'] = array('title'=>'Customer Balance Report','path'=>'reporting/customer_balance_rep','exclude'=>0);
	if(!MINI_POS){
		$reps['drawer_count'] = array('title'=>'Drawer Count','path'=>'reports/drawer_count_ui','exclude'=>0);

		$reps['dtr_rep'] = array('title'=>'DTR','path'=>'reporting/dtr_rep','exclude'=>0);
	}
	$reps['act_receipts_all'] = array('title'=>'Electronic Journal','path'=>'reprint/printReport','exclude'=>0);
	$reps['event_logs'] = array('title'=>'Event Logs','path'=>'reports/event_logs_ui','exclude'=>0);
	$reps['gc_sales_rep'] = array('title'=>'Gift Cheque Sales Report','path'=>'reporting/gc_sales_rep','exclude'=>0);
	$reps['hourly_rep'] = array('title'=>'Hourly Sales','path'=>'reporting/hourly_rep','exclude'=>0);
	$reps['inv_rep'] = array('title'=>'Item Sales','path'=>'reporting/item_sales_ui','exclude'=>0);
	// $reps['menu_history_rep'] = array('title'=>'Menu History','path'=>'items/menu_history','exclude'=>0);
	if(!MINI_POS){
		$reps['menu_sales_rep1'] = array('title'=>'Menus Report','path'=>'reporting/menus_rep','exclude'=>0);
	    // $reps['rep_history'] = array('title'=>'Read History','path'=>'history','exclude'=>0);
	}
	$reps['menu_sales_rep_hrly'] = array('title'=>'Hourly Menu Report','path'=>'reporting/menus_rep_hourly','exclude'=>0);
	$reps['menu_sales_rep'] = array('title'=>'Menu Item Sales','path'=>'prints/menu_item_sales','exclude'=>0);
	$reps['menu_extract'] = array('title'=>'Menu Extraction','path'=>'reporting/menus_extract','exclude'=>0);
	// $reps['monthly_sales_report'] = array('title'=>'Monthly Sales Breakdown','path'=>'reporting/monthly_sales_breakdown','exclude'=>0);
	// $reps['promo_rep'] = array('title'=>'Promo Report','path'=>'reporting/promo_rep','exclude'=>0);
	$reps['act_receipts'] = array('title'=>'Receipts','path'=>'reprint','exclude'=>0);
	// $reps['act_receipts2'] = array('title'=>'Receipts','path'=>'reprint/reprint_receipt','exclude'=>0);
	$reps['sales_rep'] = array('title'=>'Sales Report','path'=>'reporting/sales_rep','exclude'=>0);
	// $reps['summary_sales_invoiced_c'] = array('title'=>'Summary Of Invoiced Sales','path'=>'reporting/summary_sales_invoiced_c','exclude'=>0);
	// $reps['summary_sales'] = array('title'=>'Summary Sales','path'=>'reporting/summary_sales','exclude'=>0);
	$reps['top_items'] = array('title'=>'Top Items','path'=>'reporting/top_items_rep','exclude'=>0);
	$reps['void_sales_rep'] = array('title'=>'Voided Sales Report','path'=>'reporting/void_sales_rep','exclude'=>0);
	$reps['void_res_rep'] = array('title'=>'Voided Reservation Report','path'=>'reporting/void_res_rep','exclude'=>0);
	$reps['zero_rev'] = array('title'=>'Zero Revenue','path'=>'prints/zero_rev_rep','exclude'=>0);
$nav['reps'] = array('title'=>'<i class="icon-bar-chart"></i> <span class="title">Reports</span>','path'=>$reps,'exclude'=>0);

$nav['reps'] = array('title'=>'<i class="icon-bar-chart"></i> <span class="title">Reports</span>','path'=>$reps,'exclude'=>0);
	$setup['roles'] = array('title'=>'Roles Setup','path'=>'admin/	roles','exclude'=>0);
	$setup['sys_setup'] = array('title'=>'System Setup','path'=>'setup/details','exclude'=>0);
	$setup['user'] = array('title'=>'Users Setup','path'=>'user','exclude'=>0);
	// $setup['printer'] = array('title'=>'Printer Setup','path'=>'setup/printer','exclude'=>0);
	$setup['printer'] = array('title'=>'Printer Setup','path'=>'setup/printer_setup','exclude'=>0);
// $nav['control'] = array('title'=>'<i class="icon-user"></i> <span class="title">Admin Control</span>','path'=>$controlSettings,'exclude'=>0);
$nav['setup'] = array('title'=>'<i class="icon-settings"></i> <span class="title">Setup</span>','path'=>$setup,'exclude'=>0);
if(!MINI_POS){
		if(!IS_RETAIL){
			$trans['adjustment'] = array('title'=>'Adjustment','path'=>'adjustment','exclude'=>0);
			$trans['expenses_entry'] = array('title'=>'Expenses Entry','path'=>'expenses_entry','exclude'=>0);
			$trans['expenses_items'] = array('title'=>'Expenses Items','path'=>'expenses_entry/expenses_items','exclude'=>0);
			$trans['spoilage'] = array('title'=>'Markout','path'=>'spoilage','exclude'=>0);
			$trans['receiving'] = array('title'=>'Receiving','path'=>'receiving','exclude'=>0);
			$trans['store_order'] = array('title'=>'Store Order','path'=>'store_order','exclude'=>0);
		}

		$trans['receiving_menu'] = array('title'=>'Item Receiving','path'=>'receiving_menu','exclude'=>0);
		$trans['adj_menu'] = array('title'=>'Item Adjustment','path'=>'receiving/adjustment_menu','exclude'=>0);
		// $menus['trans'] = array('title'=>'Transactions','path'=>$transm,'exclude'=>0);
	$nav['trans'] = array('title'=>'<i class="icon-shuffle"></i><span class="title">Transactions</span>','path'=>$trans,'exclude'=>0);
}
// $inventory['report'] = array('title'=>'<span>Report</span>','path'=>$rep,'exclude'=>0);
// $nav['items'] = array('title'=>'<i class="icon-social-dropbox"></i> <span class="title">Inventory</span></span>','path'=>$inventory,'exclude'=>0);	
	// $menus['inq'] = array('title'=>'Inquiries','path'=>$inqm,'exclude'=>0);
	// $controlSettings['restart'] = array('title'=>'Restart','path'=>'admin/restart','exclude'=>0);

// $nav['cashier'] = array('title'=>'<i class="fa fa-desktop"></i> <span>Cashier</span>','path'=>'cashier','exclude'=>0);
// 	$trans['receiving'] = array('title'=>'Receiving','path'=>'receiving','exclude'=>0);
// 	$trans['adjustment'] = array('title'=>'Adjustment','path'=>'adjustment','exclude'=>0);
// $nav['trans'] = array('title'=>'<i class="fa fa-random"></i> <span>Transactions</span>','path'=>$trans,'exclude'=>0);
// 	$items['list'] = array('title'=>'List','path'=>'items','exclude'=>0);
// 	$items['gcategories'] = array('title'=>'Categories','path'=>'settings/categories','exclude'=>0);
// 	$items['gsubcategories'] = array('title'=>'Sub Categories','path'=>'settings/subcategories','exclude'=>0);
// 	$items['item_inv'] = array('title'=>'Inventory','path'=>'items/inventory','exclude'=>0);
// $nav['items'] = array('title'=>'<i class="fa fa-flask"></i> <span>Items</span>','path'=>$items,'exclude'=>0);
// 	$menus['menulist'] = array('title'=>'List','path'=>'menu','exclude'=>0);
// 	$menus['menucat'] = array('title'=>'Categories','path'=>'menu/categories','exclude'=>0);
// 	$menus['menusubcat'] = array('title'=>'Sub Categories','path'=>'menu/subcategories','exclude'=>0);
// 	$menus['menusched'] = array('title'=>'Schedules','path'=>'menu/schedules','exclude'=>0);
// $nav['menu'] = array('title'=>'<i class="fa fa-cutlery"></i> <span>Menu</span>','path'=>$menus,'exclude'=>0);
// 	$mods['modslist'] = array('title'=>'List','path'=>'mods','exclude'=>0);
// 	$mods['modgrps'] = array('title'=>'Groups','path'=>'mods/groups','exclude'=>0);
// $nav['mods'] = array('title'=>'<i class="fa fa-tags"></i> <span>Modifiers</span>','path'=>$mods,'exclude'=>0);
	// $pos_promos['promo_free'] = array('title'=>'Free','path'=>'promo/free_menu','exclude'=>0);
	// $pos_promos['promos'] = array('title'=>'Promos','path'=>'settings/promos','exclude'=>0);
// 	$pos_promos['gift_cards'] = array('title'=>'<span>Gift Cards</span>','path'=>'gift_cards','exclude'=>0);
// 	$pos_promos['coupons'] = array('title'=>'<span>Coupons</span>','path'=>'coupons','exclude'=>0);
// $nav['pos_promos'] = array('title'=>'<i class="fa fa-tags"></i> <span>Promos</span>','path'=>$pos_promos,'exclude'=>0);

	// $resSettings['types'] = array('title'=>'Restaurants','path'=>'restaurant/','exclude'=>0);
// $nav['restaurant'] = array('title'=>'<i class="fa fa-cutlery"></i> <span>Restaurants</span>','path'=>'restaurants','exclude'=>0);
	
	//$dtr['schedules'] = array('title'=>'Schedules','path'=>'dtr/dtr_schedules','exclude'=>0);
	
// 	$dtr['shifts'] = array('title'=>'Shifts','path'=>'dtr/dtr_shifts','exclude'=>0);
// 	$dtr['scheduler'] = array('title'=>'Scheduler','path'=>'dtr/scheduler','exclude'=>0);
// $nav['dtr'] = array('title'=>'<i class="fa fa-clock-o"></i> <span>DTR</span>','path'=>$dtr,'exclude'=>0);
	// <i class="fa fa-gift"></i>
	// <i class="fa fa-tag"></i>
	// $reps['act_sales'] = array('title'=>'Sales','path'=>'reports/sales_rep_ui','exclude'=>0);

	// <i class="fa fa-asterisk"></i>

	
	
// $nav['general_settings'] = array('title'=>'<i class="fa fa-cogs"></i> <span>General Settings</span>','path'=>$generalSettings,'exclude'=>0);
	

	
	
	
	
// $nav['maintenance'] = array('title'=>'<i class="fa fa-cogs"></i> <span>Maintenance</span>','path'=>$maintenance,'exclude'=>0);


///ADMIN CONTROL////////////////////////////////

// $nav['messages'] = array('title'=>'<i class="fa fa-envelope-o"></i> <span>Messages</span>','path'=>'messages','exclude'=>1);
// $nav['messages'] = array('title'=>'<i class="fa fa-envelope-o"></i> <span>Messages</span>','path'=>'messages','exclude'=>1);
// $nav['preferences'] = array('title'=>'<i class="fa fa-wrench"></i> <span>Preferences</span>','path'=>'preference','exclude'=>1);
// $nav['profile'] = array('title'=>'<i class="fa fa-folder-o"></i> <span>Profile</span>','path'=>'profile','exclude'=>1);
///LOGOUT///////////////////////////////////////
// $nav['send_to_rob'] = array('title'=>'<i class="fa fa-envelope-o"></i> <span>RLC Server Files</span>','path'=>'reads/manual_send_to_rob','exclude'=>0);
$nav['logout'] = array('title'=>'<i class="icon-logout"></i> <span class="title">Logout</span>','path'=>'site/go_logout','exclude'=>1);
$config['sideNav'] = $nav;
