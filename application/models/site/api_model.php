<?php
class Api_model extends CI_Model{

	//tables with unequal records: debtor_trans, comments
	var $item_limit = 1000;
	// important: prod is in trial stage do not use
	var $environment = master_envi;
	var $prod_url = master_api.'migrate/';
	var $dev_url = 'http://localhost/ipos_max_hq/migrate/';
	var $has_update = false;
	//INSERT ON DUPLICATE KEY UPDATE
	// https://github.com/kfirba/import-query-generator
	public function __construct()
	{
		$this->load->library('Db_manager');
		$this->main_db = $this->db_manager->get_connection('main');
	
		parent::__construct();
	}

	public function execute_migration_v2(){
		set_time_limit(0);
		// ini_set('MAX_EXECUTION_TIME', 86400);
		// ini_set('MAX_EXECUTION_TIME', -1);

		$last_log = $this->check_last_log();
		$finish_log =  $this->get_finish_log();

		$this->update_last_table_log();

		// if(!$this->check_trans_sales()){			
		// 	return false;
		// }
		// if(empty($finish_log)){
			$add_functions_array = array('add_trans_sales','add_trans_sales_local_tax',
					'add_trans_sales_charges','add_trans_sales_discounts','add_trans_sales_items','add_trans_sales_local_tax',
					'add_trans_sales_loyalty_points','add_trans_sales_menus','add_trans_sales_menu_modifiers',
					'add_trans_sales_no_tax','add_trans_sales_payments','add_trans_sales_tax', 'add_trans_sales_zero_rated',
					'add_trans_refs'	,'add_users',
					// 'add_receipt_discounts',
					'add_tables','add_coupons','migrate_branch_details',
					'add_trans_receiving_menu','add_trans_receiving_menu_details','add_trans_receivings','add_trans_receiving_details','add_menu_moves','add_item_moves','add_reasons','add_locations','add_suppliers','add_terminals','add_menu_prices','add_modifier_prices','add_modifier_sub','add_modifier_sub_prices','add_trans_sales_menu_submodifiers'
					,'add_trans_gc','add_trans_gc_local_tax',
					'add_trans_gc_charges','add_trans_gc_discounts',
					'add_trans_gc_loyalty_points','add_trans_gc_gift_cards',
					'add_trans_gc_no_tax','add_trans_gc_payments','add_trans_gc_tax', 'add_trans_gc_zero_rated',
					'add_gift_cards',
					'add_store_zread'
					,'add_trans_adjustment_menu','add_trans_adjustment_menu_details'
		);
		// }else{
		// 	$add_functions_array = array('add_trans_sales','add_trans_sales_local_tax',
		// 			'add_trans_refs'	,'add_users',
		// 			// 'add_receipt_discounts',
		// 			'add_tables','add_coupons','migrate_branch_details',
		// 			'add_trans_receiving_menu','add_trans_receiving_menu_details','add_trans_receivings','add_trans_receiving_details','add_menu_moves','add_item_moves','add_reasons','add_locations','add_suppliers','add_terminals','add_menu_prices','add_modifier_prices','add_modifier_sub','add_modifier_sub_prices'
		// 		,'add_trans_gc','add_trans_gc_local_tax',
		// 			'add_trans_gc_charges','add_trans_gc_discounts',
		// 			'add_trans_gc_loyalty_points','add_trans_gc_gift_cards',
		// 			'add_trans_gc_no_tax','add_trans_gc_payments','add_trans_gc_tax', 'add_trans_gc_zero_rated',
		// 			// 'add_gift_cards',
		// 			'add_store_zread');
		// }

		
		// $add_functions_array = array('add_trans_sales');

		// $loader = 21;
		foreach($add_functions_array as $each){
				// update_load($loader);
				$this->$each();
				set_time_limit(300);
				// $loader++;
		}

		$update_functions_array = array('update_trans_sales','update_trans_sales_charges','update_trans_sales_discounts',
											'update_trans_sales_items','update_trans_sales_local_tax','update_trans_sales_loyalty_points',
											'update_trans_sales_menu_modifiers','update_trans_sales_menus','update_trans_sales_no_tax',
											'update_trans_sales_payments','update_trans_sales_tax','update_trans_sales_zero_rated',
											'update_trans_receiving_menu','update_trans_receiving_menu_details','update_trans_receivings','update_trans_receiving_details',
											'update_menu_moves','update_item_moves','update_locations','update_suppliers','update_terminals','update_trans_sales_menu_submodifiers'
											,'update_trans_adjustment_menu','update_trans_adjustment_menu_details'
											);

		// $update_functions_array = array('update_trans_sales');

		if($last_log){
			foreach($update_functions_array as $each){
				$this->$each($finish_log ? $finish_log->migrate_date : $last_log->migrate_date);
				set_time_limit(300);
			}
		}

		$download_functions_array = array('download_categories','download_update_categories',
			'download_menu_categories', 'download_update_menu_categories',
			'download_menu_subcategories','download_update_menu_subcategories',
			'download_subcategories' , 'download_update_subcategories',
			'download_menu_subcategory' , 'download_update_menu_subcategory',
			'download_menus','download_update_menus',
			'download_items','download_update_items',
			'download_menu_schedules', 'download_update_menu_schedules',
			'download_menu_recipe','download_update_menu_recipe',
			'download_modifier_recipe','download_update_modifier_recipe', 
			'download_modifier_groups','download_update_modifier_groups',
			'download_modifier_group_details','download_update_modifier_group_details',
			'download_modifiers','download_update_modifiers',
			'download_menu_modifiers','download_update_menu_modifiers',
			'download_receipt_discounts','download_update_receipt_discounts',
			'download_charges','download_update_charges',
			'download_modifier_prices',
			'download_modifier_sub',
			'download_transaction_types','download_update_transaction_types',
			'download_modifier_sub_prices',
			'download_update_modifier_prices',
			'download_update_modifier_sub',
			'download_update_modifier_sub_prices',
			'download_menu_prices','download_update_menu_prices',
			'download_brands','download_update_brands',
			'download_payment_group','download_update_payment_group',
			'download_payment_types','download_update_payment_types',
			'download_payment_type_fields','download_update_payment_type_fields',
			'download_gift_cards','download_update_gift_cards','download_transaction_type_categories','download_update_transaction_type_categories'
		);

		// run download array()
		foreach($download_functions_array as $func){
			// update_load($loader);
			if($last_log && $finish_log){
				// $migrate_id = $last_log->master_id;
				$migrate_id = $finish_log->master_sync_id;
				$last_update = $finish_log->migrate_date;
				$this->$func($migrate_id,$last_update);

			}else{
				$this->$func();
			}
			set_time_limit(300);
			// $loader++;
		}

		//to be executed after update_functions_array
		if($finish_log){
			$add_functions_array = array('add_trans_sales_charges','add_trans_sales_discounts','add_trans_sales_items','add_trans_sales_menu_modifiers','add_trans_sales_menus','add_trans_sales_no_tax','add_trans_sales_payments','add_trans_sales_tax','add_trans_sales_zero_rated','add_trans_sales_menu_submodifiers');

			foreach($add_functions_array as $each){
					$this->$each();
			}			
		}
		

		if($this->check_last_log() && $this->has_update){
			$this->finish_log('finish');
		}

		update_load(100);	

	}

	public function migrate_branch_details(){
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$type="add";
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		$api = 'branch_details';
		$tbl_id = 'branch_code';
		$post['branch_code'] = $branch_code;
			/* API URL */

		// $url = 'http://localhost/migrate_api/public/index.php/branch_details';
		if($this->environment == 'dev'){
			$url = $this->dev_url.'branch_details';
		}else{
			$url = $this->prod_url.'branch_details';
		}
		

		$curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
            CURLOPT_SSL_VERIFYPEER=>false
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if($err){
        	return 'error';
        }else{
        	$result = json_decode($response);

        	if($result == ''){
        		return false;
        	}

        	$branch_details = $result->branch_details;
        	$table_name = 'branch_details';

        	$trans_raw = $this->main_db->select('res_id, branch_code, branch_name, branch_desc, contact_no, delivery_no, address,
		     base_location, currency, image, inactive, tin, machine_no, bir , permit_no, serial, email, website,
		      store_open, store_close, accrdn, rec_footer')->get_where($table_name)->result();

        	$trans_raw[0]->branch_id = $branch_details ? $branch_details->branch_id : 0;        	
	        	
        	$this->migrate_branch($trans_raw[0]);        	
        	// print_r($response);
        	// $this->
        }
	}

	public function migrate_branch($branch_details){
				/* API URL */

		// $url = 'http://localhost/migrate_api/public/index.php/save_branch_details';

		if($this->environment == 'dev'){
			$url = $this->dev_url.'save_branch_details';
		}else{
			$url = $this->prod_url .'save_branch_details';
		}
		
		$curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($branch_details),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
            CURLOPT_SSL_VERIFYPEER=>false
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if($err)
        	return 'error';

        // print_r($response);
	}

	public function add_trans_sales(){
		$table_name = 'trans_sales';

		do {
		  $trans_raw = $this->main_db->select('`sales_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`terminal_id` ,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed`, `sync_id`,`terminal_id`,`branch_code`,pos_id,`queue_id`,`ar_payment_amount`')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'sales_id');
		} while ($trans_raw);
		
	}

	public function add_trans_sales_charges(){
		$table_name = 'trans_sales_charges';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();
			$this->pass_to_hq($trans_raw,$table_name,'sales_charge_id');
		} while ($trans_raw);		
	}

	public function add_trans_sales_discounts(){
		$table_name = 'trans_sales_discounts';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'sales_disc_id');
		} while ($trans_raw);
		
	}

	public function add_trans_sales_items(){
		$table_name = 'trans_sales_items';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'sales_item_id');
		} while ($trans_raw);
		
	}

	public function add_trans_sales_loyalty_points(){
		$table_name = 'trans_sales_loyalty_points';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'loyalty_point_id');
		} while ($trans_raw);
		
	}

	public function add_trans_sales_menus(){
		$table_name = 'trans_sales_menus';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'sales_menu_id');
		} while ($trans_raw);
		
	}

	public function add_trans_sales_menu_modifiers(){
		$table_name = 'trans_sales_menu_modifiers';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();		

			$this->pass_to_hq($trans_raw,$table_name,'sales_mod_id');
		} while ($trans_raw);
		
	}

	public function add_trans_sales_no_tax(){
		$table_name = 'trans_sales_no_tax';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();
		
			$this->pass_to_hq($trans_raw,$table_name,'sales_no_tax_id');
		} while ($trans_raw);
		
	}

	public function add_trans_sales_payments(){
		$table_name = 'trans_sales_payments';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();		

			$this->pass_to_hq($trans_raw,$table_name,'payment_id');
		} while ($trans_raw);
		
	}

	public function add_trans_sales_tax(){
		$table_name = 'trans_sales_tax';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();		

			$this->pass_to_hq($trans_raw,$table_name,'sales_tax_id');
		} while ($trans_raw);
		
	}

	public function add_trans_sales_local_tax(){
		$table_name = 'trans_sales_local_tax';

		do {			
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();		

			$this->pass_to_hq($trans_raw,$table_name,'sales_local_tax_id');
		} while ($trans_raw);
		
	}

	public function add_trans_sales_zero_rated(){
		$table_name = 'trans_sales_zero_rated';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();		

			$this->pass_to_hq($trans_raw,$table_name,'sales_zero_rated_id');
		} while ($trans_raw);
		
	}

	public function add_trans_refs(){
		$table_name = 'trans_refs';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'id');
		} while ($trans_raw);
		
	}

	public function add_users(){
		$table_name = 'users';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'id');
		} while ($trans_raw);
		
	}

	public function add_receipt_discounts(){
		$table_name = 'receipt_discounts';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'disc_id');
		} while ($trans_raw);
		
	}

	public function add_tables(){
		$table_name = 'tables';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'tbl_id');
		} while ($trans_raw);
		
	}

	public function add_coupons(){
		$table_name = 'coupons';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'coupon_id');
		} while ($trans_raw);
		
	}


	public function add_trans_receiving_menu(){
		$table_name = 'trans_receiving_menu';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'receiving_id');
		} while ($trans_raw);
		
	}

	public function add_trans_receiving_menu_details(){
		$table_name = 'trans_receiving_menu_details';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'receiving_detail_id');
		} while ($trans_raw);
		
	}

	public function add_trans_adjustment_menu(){
		$table_name = 'trans_adjustment_menu';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'adjustment_id');
		} while ($trans_raw);
		
	}

	public function add_trans_adjustment_menu_details(){
		$table_name = 'trans_adjustment_menu_details';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'adjustment_detail_id');
		} while ($trans_raw);
		
	}

	public function add_trans_receivings(){
		$table_name = 'trans_receivings';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'trans_id');
		} while ($trans_raw);
		
	}

	public function add_trans_receiving_details(){
		$table_name = 'trans_receiving_details';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'receiving_detail_id');
		} while ($trans_raw);
		
	}

	public function add_trans_adjustments(){
		$table_name = 'trans_adjustments';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'adjustment_id');
		} while ($trans_raw);
		
	}

	public function add_trans_adjustment_details(){
		$table_name = 'trans_adjustment_details';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'adjustment_detail_id');
		} while ($trans_raw);
		
	}

	public function add_menu_moves(){
		$table_name = 'menu_moves';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'move_id');
		} while ($trans_raw);
		
	}

	public function add_item_moves(){
		$table_name = 'item_moves';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'move_id');
		} while ($trans_raw);
		
	}

	public function add_reasons(){
		$table_name = 'reasons';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'id');
		} while ($trans_raw);
		
	}

	public function add_locations(){
		$table_name = 'locations';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'loc_id');
		} while ($trans_raw);
		
	}

	public function add_terminals(){
		$table_name = 'terminals';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'terminal_id');
		} while ($trans_raw);
		
	}

	public function add_0_debtors_master(){
		$table_name = '0_debtors_master';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'debtor_no');
		} while ($trans_raw);
		
	}

	public function add_0_debtor_trans(){
		$table_name = '0_debtor_trans';

		do {
			$trans_raw = $this->main_db->select('`trans_no`,`type`,`version`,`debtor_no`,`tran_date`,`due_date`,
													`reference`,`tpe`,`order_`,`ov_amount`,`ov_gst`,`ov_freight`,`ov_freight_tax`,`ov_discount`,
													`alloc`,`rate`,`ship_via`,`dimension_id`,`dimension2_id`,`payment_terms`,`u_salesman_code`,
													`from_dr`,`user_id`,`datetime`,`is_cr`,`sales_id`,`terminal_number`,`receipt_type`,`to_sales_type`,
													`in_payment_of`,`zero_rated`')->get_where($table_name,array('master_id' => NULL,'type !='=>13),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'trans_no');
		} while ($trans_raw);
		
	}

	public function add_0_cust_branch(){
		$table_name = '0_cust_branch';

		do {
			$trans_raw = $this->main_db->select('`branch_code`,`debtor_no`,`br_name`,`branch_ref`,`br_address`,`area`,`salesman`,`contact_name`,
													`default_location`,`tax_group_id`,`sales_account`,`sales_discount_account`,`receivables_account`,
													`payment_discount_account`,`default_ship_via`,`disable_trans`,`br_post_address`,`group_no`,`notes`,`inactive`,`start_date`,
													`end_date`, `default_shipper`,`branch_gen_info`,`update_date`')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'branch_code');
		} while ($trans_raw);
		
	}

	public function add_0_bank_trans(){
		$table_name = '0_bank_trans';

		do {
			$trans_raw = $this->main_db->select('`id`,0_bank_trans.`type`,0_bank_trans.`trans_no`,`bank_act`,`ref`,`trans_date`,`amount`,0_bank_trans.`dimension_id`
					,0_bank_trans.`dimension2_id`,`person_type_id`,`person_id`,`reconciled`,`pay_type`,`amount_pay`,0_bank_trans.`update_date`')->join('0_debtor_trans',"0_bank_trans.trans_no = 0_debtor_trans.trans_no AND 0_bank_trans.type = 0_debtor_trans.type")->get_where($table_name,array('0_bank_trans.master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'id');
		} while ($trans_raw);
		
	}

	public function add_0_comments(){
		$table_name = '0_comments';

		do {
			$trans_raw = $this->main_db->select('0_comments.`id`,0_comments.`type`,date_,memo_,0_comments.`update_date`')->join('0_debtor_trans',"0_comments.id = 0_debtor_trans.trans_no AND 0_comments.type = 0_debtor_trans.type")->get_where($table_name,array('0_comments.master_id' => NULL,'0_debtor_trans.is_cr'=>'1'),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'id');
		} while ($trans_raw);
		
	}

	public function add_0_voided(){
		$table_name = '0_voided';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'id');
		} while ($trans_raw);
		
	}

	public function add_0_cust_allocations(){
		$table_name = '0_cust_allocations';

		do {
				$trans_raw = $this->main_db->select('0_cust_allocations.`id`, 0_cust_allocations.`amt`
				 , 0_cust_allocations.`date_alloc`, 0_cust_allocations.trans_no_from, 0_cust_allocations.trans_type_from, 0_cust_allocations.trans_no_to, 0_cust_allocations.trans_type_to, 0_cust_allocations.update_date ')->join('0_bank_trans',"0_cust_allocations.trans_no_from = 0_bank_trans.trans_no AND 0_cust_allocations.trans_type_from = 0_bank_trans.type")->group_by('0_cust_allocations.id')->get_where($table_name,array('0_cust_allocations.master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'id');
		} while ($trans_raw);
		
	}

	public function add_item_serials(){
		$table_name = 'item_serials';

		do {
				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'id');
		} while ($trans_raw);
		
	}

	public function add_suppliers(){
		$table_name = 'suppliers';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'supplier_id');
		} while ($trans_raw);
		
	}

	public function add_menu_prices(){
		$table_name = 'menu_prices';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'id');
		} while ($trans_raw);
		
	}

	public function add_modifier_prices(){
		$table_name = 'modifier_prices';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'id');
		} while ($trans_raw);
		
	}

	public function add_modifier_sub(){
		$table_name = 'modifier_sub';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'mod_sub_id');
		} while ($trans_raw);
		
	}

	public function add_modifier_sub_prices(){
		$table_name = 'modifier_sub_prices';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'id');
		} while ($trans_raw);
		
	}

	public function add_trans_sales_menu_submodifiers(){
		$table_name = 'trans_sales_menu_submodifiers';

		do {
			$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

			$this->pass_to_hq($trans_raw,$table_name,'sales_submod_id');
		} while ($trans_raw);
		
	}

	public function add_trans_gc(){
		$table_name = 'trans_gc';

		do {
		  $trans_raw = $this->main_db->select('`gc_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`terminal_id` ,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed`, `sync_id`,`terminal_id`,`branch_code`,pos_id')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'gc_id');
		} while ($trans_raw);
		
	}

	public function add_trans_gc_local_tax(){
		$table_name = 'trans_gc_local_tax';

		do {
		  $trans_raw = $trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'gc_local_tax_id');
		} while ($trans_raw);
		
	}

	public function add_trans_gc_charges(){
		$table_name = 'trans_gc_charges';

		do {
		  $trans_raw = $trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'gc_charge_id');
		} while ($trans_raw);
		
	}

	public function add_trans_gc_discounts(){
		$table_name = 'trans_gc_discounts';

		do {
		  $trans_raw = $trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'gc_disc_id');
		} while ($trans_raw);
		
	}

	public function add_trans_gc_loyalty_points(){
		$table_name = 'trans_gc_loyalty_points';

		do {
		  $trans_raw = $trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'gc_loyalty_point_id');
		} while ($trans_raw);
		
	}

	public function add_trans_gc_gift_cards(){
		$table_name = 'trans_gc_gift_cards';

		do {
		  $trans_raw = $trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'gc_gift_card_id');
		} while ($trans_raw);
		
	}

	public function add_trans_gc_no_tax(){
		$table_name = 'trans_gc_no_tax';

		do {
		  $trans_raw = $trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'gc_no_tax_id');
		} while ($trans_raw);
		
	}

	public function add_trans_gc_payments(){
		$table_name = 'trans_gc_payments';

		do {
		  $trans_raw = $trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'gc_payment_id');
		} while ($trans_raw);
		
	}

	public function add_trans_gc_tax(){
		$table_name = 'trans_gc_tax';

		do {
		  $trans_raw = $trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'gc_tax_id');
		} while ($trans_raw);
		
	}

	public function add_trans_gc_zero_rated(){
		$table_name = 'trans_gc_zero_rated';

		do {
		  $trans_raw = $trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'gc_zero_rated_id');
		} while ($trans_raw);
		
	}

	public function add_gift_cards(){
		$table_name = 'gift_cards';

		do {
		  $trans_raw = $trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'gc_id');
		} while ($trans_raw);
		
	}

	public function add_store_zread(){
		$table_name = 'store_zread';

		do {
		  $trans_raw = $trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();

		$this->pass_to_hq($trans_raw,$table_name,'zread_id');
		} while ($trans_raw);
		
	}
	
	public function pass_to_hq($data,$api,$tbl_id){
		// echo $api.'<br />';
		if(!$data)
			return false;

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$type="add";
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;

		$post['user_id'] = $user_id;
		$post['type'] = $type;
		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $data;
		$post['table'] = $api;
		$post['tbl_id'] = $tbl_id;
			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'migrate_data';
		}else{
			$url = $this->prod_url.'migrate_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            
            $result = json_decode($response);
            // print_r($response);exit;
            if($result == ''){
            	return false;
            }
            
            $migration_sync_id = $result->master_id;

            // $json_encode  = json_encode(array('sales_id'=>$this->array_flat($data,"sales_id")));

            $json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->result,$tbl_id)));
            // print_r($json_encode);exit;

            // $trans_id_raw = $this->object_flat($data,"sales_id");
            $trans_id_raw = $result->result;
            $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_sync_id) , true,false);
			
			if(in_array($api, array('0_debtor_trans','0_comments','0_voided')))
			{
				$this->table_update($api,$result->result,array('master_id'=>$migration_sync_id),array($tbl_id,'type'));
				
			}else{
				$this->update_tbl_batch($api,$update_trans_wrapped,array($tbl_id,'pos_id'));
			}

            $this->add_tbl('master_logs',array("status"=>"1","type"=>$type,"transaction"=>$api,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db');

            $this->has_update = true;

            set_time_limit(60);
            
            // echo $migration_sync_id;

            $last_log = $this->check_last_log();
        }
	}

	public function add_tbl($table_name,$items,$set=array(),$db="migrate_db"){
		if($db == "main_db" && $table_name != "0_debtors_master" && $table_name != "0_debtor_trans" && $table_name != "0_cust_branch" && $table_name != "0_bank_trans" && $table_name != "0_comments" && $table_name != "0_prices" && $table_name != "0_item_codes" && $table_name != "0_stock_category" && $table_name != "0_stock_master" && $table_name != '0_suppliers' && $table_name != '0_voided' && $table_name != '0_cust_allocations') {

			if(!empty($set)){
				foreach ($set as $key => $val) {
					$this->main_db->set($key, $val, FALSE);
				}
			}

			$this->main_db->insert($table_name,$items);
		}else{
			if(!empty($set)){
				foreach ($set as $key => $val) {
					$this->$db->set($key, $val, FALSE);
				}
			}

			$this->$db->insert($table_name,$items);
		}
		
		return $this->$db->insert_id();
	}

	public function update_tbl_batch($table_name,$items,$key,$db="main_db"){
		if($items){
			// if(CONSOLIDATOR){
			if(is_array($key)){
				foreach($items as $each){
					foreach ($key as $k) {
						if(isset($each[$k])){
							$this->$db->where($k,$each[$k]);
						}						
					}
					$this->$db->update($table_name,$each);
				}
			}else{
				$this->$db->update_batch($table_name,$items,$key);
			}
				
				
			// }else{
			// 	$this->$db->update_batch($table_name,$items,$key);
			// }
			
			
			return $this->$db->affected_rows();
		}
		
	}

	public function array_flat($trans_id_raw=array(), $var=""){

  		$return = array();
  		$property_name = $var;
 		array_walk_recursive($trans_id_raw, function($a) use (&$return,$var) { if(isset($a->$var)) $return[] = $a->$var; });

 		return $return;
	}

	public function object_flat($trans_id_raw=array(), $var=""){

  		$return = array();
  		$property_name = $var;

 		array_walk_recursive($trans_id_raw, function($a) use (&$return,$var) { $return[] = (object) array($var => $a->$var);  });

 		return $return;
	}

	public function formulate_object($object=NULL,$add_element=array(),$multi=false,$return_obj = true){
			$new_array = json_decode(json_encode($object), true);

			foreach($add_element as $key => $value){
				if($multi){
					foreach($new_array as $obj => &$elm){

						$new_array[$obj][$key] = $value;
					}
				}else{
					$new_array[$key] = $value;

				}
			}

			if($return_obj){
				return json_decode(json_encode($new_array));
				
			}else{
				return $new_array;
			}

	}

	public function check_last_log(){
		$last_logs = $this->main_db->order_by('master_id desc')->limit('1')->get_where('master_logs')->result();
		$last_log = false;
		// echo "<pre>",print_r($last_logs),"</pre>";die();

		if(isset($last_logs[0]) && !empty($last_logs)){
			$last_log = $last_logs[0];
		}

		return $last_log;


	}

	public function table_update($table,$data,$set=array(),$where = array()){
        // this for update master_id with no primary key on table

		$ctr = 1;
        foreach($data as $each){
        	// print_r($each);
            foreach($where as $w){
                $this->main_db->where($w,$each->$w);
                $ctr++;
            }
            
            $this->main_db->update($table,$set);

            if($ctr % 100 == 0){
                set_time_limit(60);  
            }

            $ctr++;
            // echo $this->local_main_db->last_query();exit;
        }
    }

    public function get_finish_log(){
			$last_logs = $this->main_db->order_by('master_id desc')->limit('3')->get_where('master_logs',array('type'=>"finish"))->result();
			$last_log = false;
			// echo "<pre>",print_r($last_logs),"</pre>";die();
			// if(isset($last_logs[2]) && !empty($last_logs)){ // get second to the last log if available , to consider some lapse time in last finish migration
			// 	$last_log = $last_logs[2];
			// }else
			// if(isset($last_logs[1]) && !empty($last_logs)){ // get second to the last log if available , to consider some lapse time in last finish migration
			// 	$last_log = $last_logs[1];
			// }elseif(isset($last_logs[0]) && !empty($last_logs)){
			// 	$last_log = $last_logs[0];
			// }
			if(isset($last_logs[0]) && !empty($last_logs)){
				$last_log = $last_logs[0];
			}
			return $last_log;


		}

	public function update_trans_sales($update_date){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
	    $table_name = 'trans_sales';
	    $selected_field = '`sales_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`terminal_id` ,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed` , `sync_id`,`terminal_id`,`branch_code`,pos_id,`queue_id`,`ar_payment_amount`';

	
		$trans_raw = $this->main_db->select($selected_field)->where('`update_date` > ',$update_date)->where('`master_id` is not null',NULL,false)->get($table_name)->result();


		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'sales_id','sales_id');
		}		
	}

	public function update_trans_sales_charges($update_date = NULL){
		 	$table_name = 'trans_sales_charges';
		    $selected_field = 'trans_sales_charges.sales_charge_id, trans_sales_charges.sales_id';
		    $based_delete_id = 'sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			// do {
				$trans_raw = $this->main_db->select('trans_sales_charges.*')->join('trans_sales','trans_sales.sales_id = trans_sales_charges.sales_id && trans_sales.pos_id = trans_sales_charges.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR (trans_sales.update_date IS NULL AND trans_sales.update_date not like "%P"))',NULL, false)->where('trans_sales_charges.sales_id is not null',null,false)->get('trans_sales_charges')->result();
			// 	$this->pass_update_to_hq($trans_raw,$table_name,'sales_charge_id','sales_id');
			// } while ($trans_raw);
		

			$chunks = array_chunk($trans_raw, $this->item_limit);

			foreach($chunks as $chunk){
				$this->pass_update_to_hq($chunk,$table_name,'sales_charge_id','sales_id');
			}		
						
	}

	public function update_trans_sales_discounts($update_date = NULL){
		 $table_name = 'trans_sales_discounts';
		    $selected_field = 'trans_sales_discounts.sales_disc_id, trans_sales_discounts.sales_id';
		    $based_delete_id = 'sales_id';
		    $based_delete_id_select = 'trans_sales.sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			// do {
				$trans_raw = $this->main_db->select('trans_sales_discounts.*')->join('trans_sales','trans_sales.sales_id = trans_sales_discounts.sales_id && trans_sales.pos_id = trans_sales_discounts.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR (trans_sales.update_date IS NULL AND trans_sales.update_date not like "%P"))',NULL, false)->where('trans_sales_discounts.sales_id is not null',null,false)->get('trans_sales_discounts')->result();
			// 	$this->pass_update_to_hq($trans_raw,$table_name,'sales_disc_id','sales_id');
			// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'sales_disc_id','sales_id');
		}	
	
	}

	function update_trans_sales_items($update_date){
		$table_name = 'trans_sales_items';
	    $selected_field = 'trans_sales_items.sales_item_id, trans_sales_items.sales_id';

		// do {
			$trans_raw = $this->main_db->select('trans_sales_items.*')->join('trans_sales','trans_sales.sales_id = trans_sales_items.sales_id && trans_sales.pos_id = trans_sales_items.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR (trans_sales.update_date IS NULL AND trans_sales.update_date not like "%P"))',NULL, false)->where('trans_sales_items.sales_id is not null',null,false)->group_by('trans_sales_items.sales_item_id,trans_sales_items.pos_id')->get('trans_sales_items')->result();

		// 	$this->pass_update_to_hq($trans_raw,$table_name,'sales_item_id','sales_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'sales_item_id','sales_id');
		}	
	}

	public function update_trans_sales_local_tax($update_date = NULL){
		 $table_name = 'trans_sales_local_tax';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
		// 	$this->pass_update_to_hq($trans_raw,$table_name,'sales_local_tax_id','sales_id');
		// } while ($trans_raw);	
	

		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'sales_local_tax_id','sales_id');
		}	
				
	}

	public function update_trans_sales_loyalty_points($update_date = NULL){
		 $table_name = 'trans_sales_loyalty_points';

		// do {
			$trans_raw = $this->main_db->select('trans_sales_loyalty_points.*')->join('trans_sales','trans_sales.sales_id = trans_sales_loyalty_points.sales_id && trans_sales.pos_id = trans_sales_loyalty_points.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->where('trans_sales_loyalty_points.sales_id is not null',null,false)->get('trans_sales_loyalty_points')->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'loyalty_point_id','sales_id');
		// } while ($trans_raw);	
	

		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'loyalty_point_id','sales_id');
		}	
				
	}

	public function update_trans_sales_menu_modifiers($update_date = NULL){
		 $table_name = 'trans_sales_menu_modifiers';

		// do {
			$trans_raw = $this->main_db->select('trans_sales_menu_modifiers.*')->join('trans_sales','trans_sales.sales_id = trans_sales_menu_modifiers.sales_id && trans_sales.pos_id = trans_sales_menu_modifiers.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR (trans_sales.update_date IS NULL AND trans_sales.update_date not like "%P"))',NULL, false)->where('trans_sales_menu_modifiers.sales_id is not null',null,false)->group_by('trans_sales_menu_modifiers.sales_mod_id,trans_sales_menu_modifiers.pos_id')->get('trans_sales_menu_modifiers')->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'sales_mod_id','sales_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'sales_mod_id','sales_id');
		}			
				
	}

	public function update_trans_sales_menu_submodifiers($update_date = NULL){
		 $table_name = 'trans_sales_menu_submodifiers';

		// do {
			$trans_raw = $this->main_db->select('trans_sales_menu_submodifiers.*')->join('trans_sales','trans_sales.sales_id = trans_sales_menu_submodifiers.sales_id && trans_sales.pos_id = trans_sales_menu_submodifiers.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR (trans_sales.update_date IS NULL AND trans_sales.update_date not like "%P"))',NULL, false)->where('trans_sales_menu_submodifiers.sales_id is not null',null,false)->group_by('trans_sales_menu_submodifiers.sales_submod_id,trans_sales_menu_submodifiers.pos_id')->get('trans_sales_menu_submodifiers')->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'sales_mod_id','sales_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'sales_submod_id','sales_id');
		}			
				
	}

	public function update_trans_sales_menus($update_date = NULL){
		$table_name = 'trans_sales_menus';

		// do {
			$trans_raw = $this->main_db->select('trans_sales_menus.*')->join('trans_sales','trans_sales.sales_id = trans_sales_menus.sales_id && trans_sales.pos_id = trans_sales_menus.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR (trans_sales.update_date IS NULL AND trans_sales.update_date not like "%P"))',NULL, false)->where('trans_sales_menus.sales_id is not null',null,false)->group_by('trans_sales_menus.sales_menu_id,trans_sales_menus.pos_id')->get('trans_sales_menus')->result();	
			// $this->pass_update_to_hq($trans_raw,$table_name,'sales_menu_id','sales_id');
		// } while ($trans_raw);
	

		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'sales_menu_id','sales_id');
		}	
				
	}

	public function update_trans_sales_no_tax($update_date = NULL){
		$table_name = 'trans_sales_no_tax';

		// do {
			$trans_raw = $this->main_db->select('trans_sales_no_tax.*')->join('trans_sales','trans_sales.sales_id = trans_sales_no_tax.sales_id && trans_sales.pos_id = trans_sales_no_tax.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR (trans_sales.update_date IS NULL AND trans_sales.update_date not like "%P"))',NULL, false)->where('trans_sales_no_tax.sales_id is not null',null,false)->get('trans_sales_no_tax')->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'sales_no_tax_id','sales_id');
		// } while ($trans_raw);
	

		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'sales_no_tax_id','sales_id');
		}		
				
	}

	public function update_trans_sales_payments($update_date = NULL){
		$table_name = 'trans_sales_payments';

		// do {
			$trans_raw = $this->main_db->select('trans_sales_payments.*')->join('trans_sales','trans_sales.sales_id = trans_sales_payments.sales_id && trans_sales.pos_id = trans_sales_payments.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR (trans_sales.update_date IS NULL AND trans_sales.update_date not like "%P"))',NULL, false)->where('trans_sales_payments.sales_id is not null',null,false)->group_by('trans_sales_payments.payment_id,trans_sales_payments.pos_id')->get('trans_sales_payments')->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'payment_id','sales_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'payment_id','sales_id');
		}	
				
	}

	public function update_trans_sales_tax($update_date = NULL){
		$table_name = 'trans_sales_tax';

		// do {
			$trans_raw = $this->main_db->select('trans_sales_tax.*')->join('trans_sales','trans_sales.sales_id = trans_sales_tax.sales_id && trans_sales.pos_id = trans_sales_tax.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR (trans_sales.update_date IS NULL AND trans_sales.update_date not like "%P"))',NULL, false)->where('trans_sales_tax.sales_id is not null',null,false)->group_by('trans_sales_tax.sales_tax_id,trans_sales_tax.pos_id')->get('trans_sales_tax')->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'sales_tax_id','sales_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'sales_tax_id','sales_id');
		}	
				
	}

	public function update_trans_sales_zero_rated($update_date = NULL){
		$table_name = 'trans_sales_zero_rated';

		// do {
			$trans_raw = $this->main_db->select('trans_sales_zero_rated.*')->join('trans_sales','trans_sales.sales_id = trans_sales_zero_rated.sales_id && trans_sales.pos_id = trans_sales_zero_rated.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR (trans_sales.update_date IS NULL AND trans_sales.update_date not like "%P"))',NULL, false)->where('trans_sales_zero_rated.sales_id is not null',null,false)->get('trans_sales_zero_rated')->result();
		// 	$this->pass_update_to_hq($trans_raw,$table_name,'sales_zero_rated_id','sales_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'sales_zero_rated_id','sales_id');
		}	
				
	}

	public function update_trans_receiving_menu($update_date = NULL){
		$table_name = 'trans_receiving_menu';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`update_date` > ',$update_date)->get($table_name)->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'receiving_id','receiving_id');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'receiving_id','receiving_id');
		}	
				
	}

	public function update_trans_receiving_menu_details($update_date = NULL){
		$table_name = 'trans_receiving_menu_details';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'receiving_detail_id','receiving_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'receiving_detail_id','receiving_id');
		}
				
	}

	public function update_trans_adjustment_menu($update_date = NULL){
		$table_name = 'trans_adjustment_menu';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`update_date` > ',$update_date)->get($table_name)->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'receiving_id','receiving_id');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'adjustment_id','adjustment_id');
		}	
				
	}

	public function update_trans_adjustment_menu_details($update_date = NULL){
		$table_name = 'trans_adjustment_menu_details';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'receiving_detail_id','receiving_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);

		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'adjustment_detail_id','adjustment_id');
		}
				
	}

	public function update_trans_receivings($update_date = NULL){
		$table_name = 'trans_receivings';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`update_date` > ',$update_date)->get($table_name)->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'trans_id','receiving_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'trans_id','receiving_id');
		}
				
	}

	public function update_trans_receiving_details($update_date = NULL){
		$table_name = 'trans_receiving_menu_details';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
		// 	$this->pass_update_to_hq($trans_raw,$table_name,'receiving_detail_id','receiving_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'receiving_detail_id','receiving_id');
		}
				
	}

	public function update_trans_adjustments($update_date = NULL){
		$table_name = 'trans_adjustments';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`update_date` > ',$update_date)->get($table_name)->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'trans_id','trans_id');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'trans_id','trans_id');
		}
				
	}

	public function update_menu_moves($update_date = NULL){
		$table_name = 'menu_moves';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
		// 	$this->pass_update_to_hq($trans_raw,$table_name,'move_id','move_id');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);

		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'move_id','move_id');
		}
				
	}

	public function update_item_moves($update_date = NULL){
		$table_name = 'item_moves';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'move_id','move_id');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);

		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'move_id','move_id');
		}
				
	}

	public function update_locations($update_date = NULL){
		$table_name = 'locations';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
		// 	$this->pass_update_to_hq($trans_raw,$table_name,'loc_id','loc_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'loc_id','loc_id');
		}
				
	}

	public function update_suppliers($update_date = NULL){
		$table_name = 'suppliers';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
		// 	$this->pass_update_to_hq($trans_raw,$table_name,'loc_id','loc_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'supplier_id','supplier_id');
		}
				
	}

	public function update_terminals($update_date = NULL){
		$table_name = 'terminals';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'terminal_id','terminal_id');
		// } while ($trans_raw);


		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'terminal_id','terminal_id');
		}
				
	}

	public function update_0_debtors_master($update_date = NULL){
		$table_name = '0_debtors_master';

		// do {
			$trans_raw = $this->main_db->select('*')->where('`update_date` > ',$update_date)->get($table_name)->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'debtor_no','debtor_no');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'debtor_no','debtor_no');
		}
				
	}

	public function update_0_debtor_trans($update_date = NULL){
		$table_name = '0_debtor_trans';
		$selected_field = '`trans_no`,`type`,`version`,`debtor_no`,`tran_date`,`due_date`,
													`reference`,`tpe`,`order_`,`ov_amount`,`ov_gst`,`ov_freight`,`ov_freight_tax`,`ov_discount`,
													`alloc`,`rate`,`ship_via`,`dimension_id`,`dimension2_id`,`payment_terms`,`u_salesman_code`,
													`from_dr`,`user_id`,`datetime`,`is_cr`,`sales_id`,`terminal_number`,`receipt_type`,`to_sales_type`,
													`in_payment_of`,`zero_rated`';

		// do {
			$trans_raw = $this->main_db->select($selected_field)->where('`update_date` > ',$update_date)->where('`master_id` is not null',NULL,false)->where('is_cr','1')->get($table_name)->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'trans_no','trans_no');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'trans_no','trans_no');
		}

				
	}

	public function update_0_cust_branch($update_date = NULL){
		$table_name = '0_cust_branch';
		$selected_field = '`branch_code` ,`debtor_no`,`br_name`,`branch_ref`,`br_address`,`area`,`salesman`,`contact_name`,
													`default_location`,`tax_group_id`,`sales_account`,`sales_discount_account`,`receivables_account`,
													`payment_discount_account`,`default_ship_via`,`disable_trans`,`br_post_address`,`group_no`,`notes`,`inactive`,`start_date`,
													`end_date`, `default_shipper`,`branch_gen_info`,`update_date`';
		// do {
			$trans_raw = $this->main_db->select($selected_field)->where('`update_date` > ',$update_date)->where('`master_id` is not null',NULL,false)->get($table_name)->result();
		// 	$this->pass_update_to_hq($trans_raw,$table_name,'branch_code','branch_code');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'branch_code','branch_code');
		}
				
	}

	public function update_0_bank_trans($update_date = NULL){
		$table_name = '0_bank_trans';
		$selected_field = '`id`,0_bank_trans.`type`,0_bank_trans.`trans_no`,`bank_act`,`ref`,`trans_date`,`amount`, 0_bank_trans.`dimension_id`
					,0_bank_trans.`dimension2_id`,`person_type_id`,`person_id`,`reconciled`,`pay_type`,`amount_pay`,0_bank_trans.`update_date`';
		// do {
			$trans_raw = $this->main_db->select($selected_field)->join('0_debtor_trans',"0_bank_trans.trans_no = 0_debtor_trans.trans_no AND 0_bank_trans.type = 0_debtor_trans.type")->where('0_bank_trans.`update_date` > ',$update_date)->where('0_bank_trans.`master_id` is not null',NULL,false)->get($table_name)->result();
		// 	$this->pass_update_to_hq($trans_raw,$table_name,'id','id');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'id','id');
		}
				
	}

	public function update_0_comments($update_date = NULL){
		$table_name = '0_comments';
		$selected_field = '0_comments.`id`,0_comments.`type`,date_,memo_,0_comments.`update_date`';
		// do {
			$trans_raw = $this->main_db->select($selected_field)->join('0_debtor_trans',"0_comments.id = 0_debtor_trans.trans_no AND 0_comments.type = 0_debtor_trans.type")->where('0_comments.`update_date` > ',$update_date)->where('0_comments.`master_id` is not null',NULL,false)->where('0_debtor_trans.is_cr','1')->get($table_name)->result();
		// 	$this->pass_update_to_hq($trans_raw,$table_name,'id','id');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'id','id');
		}
				
	}

	public function update_0_cust_allocations($update_date = NULL){
		$table_name = '0_cust_allocations';
		 $selected_field = '0_cust_allocations.`id`, 0_cust_allocations.`amt`
				 , 0_cust_allocations.`date_alloc`, 0_cust_allocations.trans_no_from, 0_cust_allocations.trans_type_from, 0_cust_allocations.trans_no_to, 0_cust_allocations.trans_type_to, 0_cust_allocations.update_date';
		// do {
			$trans_raw = $this->main_db->select($selected_field)->join($table_name,'0_cust_allocations.trans_no_from = 0_bank_trans.trans_no AND 0_cust_allocations.trans_type_from = 0_bank_trans.type','left')->where('(0_bank_trans.update_date > "'.$update_date.'" OR (0_bank_trans.update_date IS NULL)  OR 0_cust_allocations.master_id IS NULL) AND 0_cust_allocations.id is not null',NULL, false)->get('0_bank_trans')->result();
		// 	$this->pass_update_to_hq($trans_raw,$table_name,'id','id');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'id','id');
		}

				
	}

	public function update_item_serials($update_date = NULL){
		$table_name = 'item_serials';
		 $selected_field = '*';
		// do {
			$trans_raw = $this->main_db->select($selected_field)->where('`update_date` > ',$update_date)->where('`master_id` is not null',NULL,false)->get($table_name)->result();
			// $this->pass_update_to_hq($trans_raw,$table_name,'id','id');
		// } while ($trans_raw);

		$chunks = array_chunk($trans_raw, $this->item_limit);
		
		foreach($chunks as $chunk){
			$this->pass_update_to_hq($chunk,$table_name,'id','id');
		}
				
	}

	public function pass_update_to_hq($data,$api,$tbl_id,$based_delete_id){

		if(!$data)
			return false;

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$type="update";
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;

		$post['user_id'] = $user_id;
		$post['type'] = $type;
		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $data;
		$post['table'] = $api;
		$post['tbl_id'] = $tbl_id;
		$post['based_delete_id'] = $based_delete_id;
			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'update_migrate_data';
		}else{
			$url = $this->prod_url.'update_migrate_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
            CURLOPT_SSL_VERIFYPEER=>false
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            
            $result = json_decode($response);
            // print_r($response);

            if($result == ''){
            	exit;
            }
            $migration_sync_id = $result->master_id;

            // $json_encode  = json_encode(array('sales_id'=>$this->array_flat($data,"sales_id")));

            $json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->result,$tbl_id)));
            // print_r($json_encode);exit;

            // $trans_id_raw = $this->object_flat($data,"sales_id");
            $trans_id_raw = $result->result;
            $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_sync_id) , true,false);
			
			if(empty($update_trans_wrapped)){
				return false;
			}

			if(in_array($api, array('0_debtor_trans','0_comments','0_voided')))
			{
				$this->table_update($api,$result->result,array('master_id'=>$migration_sync_id),array($tbl_id,'type'));
				
			}else{
				$this->update_tbl_batch($api,$update_trans_wrapped,array($tbl_id,'pos_id'));
			}

            $this->add_tbl('master_logs',array("status"=>"1","type"=>$type,"transaction"=>$api,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db');

            $this->has_update = true;

            set_time_limit(60);
            
            // echo $migration_sync_id;

            $last_log = $this->check_last_log();
        }
	}

	function update_last_table_log(){
				
		$branch_code = BRANCH_CODE;
		
		$post['branch_code'] = $branch_code;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'get_latest_logs';
		}else{
			$url = $this->prod_url.'get_latest_logs';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response); 
            $data = !empty($result) ? $result[0]->result : array();
            if(!empty($data) && !empty(json_decode($data->src_id))){            	
	            $api = $data->transaction;
	            $migration_sync_id = $data->master_id;
	            $tbl_id = $data->tbl_id;

	            $trans_id_raw = json_decode($data->src_id);
	            $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_sync_id) , true,false);
				
				if(in_array($api, array('0_debtor_trans','0_comments','0_voided')))
				{
					$this->table_update($api,json_decode($data->src_id),array('master_id'=>$migration_sync_id),array($tbl_id,'type'));
					
				}else{
					// $this->update_tbl_batch($api,$update_trans_wrapped,$tbl_id);
					
					$this->update_tbl_batch($api,$update_trans_wrapped,array($tbl_id,'pos_id'));
				}            

	            set_time_limit(60);
            }
            
        }

	}

	function download_categories($migrate_id=null,$last_update=null){
		$table_name = 'categories';
	    $selected_field = 'cat_id, code, name, image, inactive';
	    $based_field = 'branch_code,code';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'cat_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_categories($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'categories';
	    $selected_field = 'cat_id,code, name, image, inactive';
	    $based_field = 'branch_code,code';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'cat_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_menu_categories($migrate_id=null,$last_update=null){
		$table_name = 'menu_categories';
	    $selected_field = 'menu_cat_id, menu_cat_name, menu_sched_id, inactive, arrangement, reg_date, brand, unli';
	    $based_field = 'branch_code, menu_cat_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_cat_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_menu_categories($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'menu_categories';
	    $selected_field = 'menu_cat_id, menu_cat_name, menu_sched_id, inactive, arrangement, reg_date, brand, unli';
	    $based_field = 'branch_code, menu_cat_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_cat_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_menu_subcategories($migrate_id=null,$last_update=null){
		$table_name = 'menu_subcategories';
	    $selected_field = 'menu_sub_cat_id, menu_sub_cat_name, reg_date, inactive';
	    $based_field = 'branch_code, menu_sub_cat_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_sub_cat_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_menu_subcategories($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'menu_subcategories';
	    $selected_field = 'menu_sub_cat_id, menu_sub_cat_name, reg_date, inactive';
	    $based_field = 'branch_code, menu_sub_cat_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_sub_cat_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_subcategories($migrate_id=null,$last_update=null){
		$table_name = 'subcategories';
	    $selected_field = 'sub_cat_id, cat_id, code, name, image, inactive';
	    $based_field = 'branch_code, code';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'sub_cat_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_subcategories($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'subcategories';
	    $selected_field = 'sub_cat_id, cat_id, code, name, image, inactive';
	    $based_field = 'branch_code, code';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'sub_cat_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_menu_subcategory($migrate_id=null,$last_update=null){
		$table_name = 'menu_subcategory';
	    $selected_field = 'menu_sub_id, menu_sub_name, category_id, reg_date, inactive';
	    $based_field = 'branch_code, menu_sub_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_sub_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_menu_subcategory($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'menu_subcategory';
	    $selected_field = 'menu_sub_id, menu_sub_name, category_id, reg_date, inactive';
	    $based_field = 'branch_code, menu_sub_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_sub_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_menus($migrate_id=null,$last_update=null){
		$table_name = 'menus';
	    $selected_field = 'menu_id,menu_code,menu_barcode,menu_name,menu_short_desc,
	    					menu_cat_id,menu_sub_cat_id, menu_sub_id ,menu_sched_id,cost,reg_date, update_date,no_tax,miaa_cat,
	    					free,inactive,costing,brand';
	    $based_field = 'branch_code,menu_code';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_menus($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'menus';
	    $selected_field = 'menu_id,menu_code,menu_barcode,menu_name,menu_short_desc,
	    					menu_cat_id,menu_sub_cat_id, menu_sub_id ,menu_sched_id,cost,reg_date, update_date,no_tax,miaa_cat,
	    					free,inactive,costing,brand';
	    $based_field = 'branch_code,menu_code';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_items($migrate_id=null,$last_update=null){
		$table_name = 'items';
	    $selected_field = 'item_id,barcode,code,name,desc,
	    					cat_id,subcat_id,supplier_id,uom,cost,type,reg_date, update_date,
	    					no_per_pack,  no_per_pack_uom , no_per_case,  reorder_qty, 	max_qty, memo,
	    					inactive';
	    $based_field = 'branch_code,code';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'item_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_items($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'items';
	    $selected_field = 'item_id,barcode,code,name,desc,
	    					cat_id,subcat_id,supplier_id,uom,cost,type,reg_date, update_date,
	    					no_per_pack,  no_per_pack_uom , no_per_case,  reorder_qty, 	max_qty, memo,
	    					inactive';
	    $based_field = 'branch_code,code';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'item_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_menu_schedules($migrate_id=null,$last_update=null){
		$table_name = 'menu_schedules';
	    $selected_field = 'menu_sched_id, desc, inactive';
	    $based_field = 'branch_code, menu_sched_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_sched_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_menu_schedules($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'menu_schedules';
	    $selected_field = 'menu_sched_id, desc, inactive';
	    $based_field = 'branch_code, menu_sched_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_sched_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_menu_recipe($migrate_id=null,$last_update=null){
		$table_name = 'menu_recipe';
	    $selected_field = 'recipe_id, menu_id, item_id, uom, qty, cost';
	    $based_field = 'branch_code, recipe_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'recipe_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_menu_recipe($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'menu_recipe';
	    $selected_field = 'recipe_id, menu_id, item_id, uom, qty, cost';
	    $based_field = 'branch_code, recipe_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'recipe_id';

		$based_delete_id_ = 'menu_id';
		$based_delete_id = 'menu_recipe.menu_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$delete_args = array();

				foreach($data as $b){
					$delete_args[$based_delete_id][]  = $b->$based_delete_id_ ;
				}

				$this->delete_trans_misc_batch($table_name,$delete_args,'main_db');
				$this->delete_trans_misc_batch($table_name,$delete_args,'db');

				if(CONSOLIDATOR){
					$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->delete_trans_misc_batch($table_name,$delete_args,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
				}

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	// $this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
		 		$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_modifier_recipe($migrate_id=null,$last_update=null){
		$table_name = 'modifier_recipe';
	    $selected_field = 'mod_recipe_id, mod_id, item_id, uom, qty, cost';
	    $based_field = 'branch_code, mod_recipe_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_recipe_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_modifier_recipe($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'modifier_recipe';
	    $selected_field = 'mod_recipe_id, mod_id, item_id, uom, qty, cost';
	    $based_field = 'branch_code, mod_recipe_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_recipe_id';

		$based_delete_id_ = 'mod_id';
		$based_delete_id = 'modifier_recipe.mod_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);
            	$delete_args = array();

				foreach($data as $b){
					$delete_args[$based_delete_id][]  = $b->$based_delete_id_ ;
				}

				$this->delete_trans_misc_batch($table_name,$delete_args,'main_db');
				$this->delete_trans_misc_batch($table_name,$delete_args,'db');

				if(CONSOLIDATOR){
					$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->delete_trans_misc_batch($table_name,$delete_args,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
				}

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	// $this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_modifier_groups($migrate_id=null,$last_update=null){
		$table_name = 'modifier_groups';
	    $selected_field = 'mod_group_id, name, mandatory, multiple, inactive';
	    $based_field = 'branch_code, mod_group_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_group_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_modifier_groups($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'modifier_groups';
	    $selected_field = 'mod_group_id, name, mandatory, multiple, inactive';
	    $based_field = 'branch_code, mod_group_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_group_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;

            }
            
        }

	}

	function download_modifier_group_details($migrate_id=null,$last_update=null){
		$table_name = 'modifier_group_details';
	    $selected_field = 'id, mod_group_id, mod_id, default';
	    $based_field = 'branch_code, id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_modifier_group_details($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'modifier_group_details';
	    $selected_field = 'id, mod_group_id, mod_id, default';
	    $based_field = 'branch_code, id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'id';

		$based_delete_id_ = 'mod_group_id';
		$based_delete_id = 'modifier_group_details.mod_group_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$delete_args = array();

				foreach($trans_based_id_raw as $b){
					$delete_args[$based_delete_id][]  = $b->$based_delete_id_ ;
				}

				$this->delete_trans_misc_batch($table_name,$delete_args,'main_db');
				$this->delete_trans_misc_batch($table_name,$delete_args,'db');

				if(CONSOLIDATOR){
					$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->delete_trans_misc_batch($table_name,$delete_args,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
				}

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	// $this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_modifiers($migrate_id=null,$last_update=null){
		$table_name = 'modifiers';
	    $selected_field = 'mod_id, name, cost, has_recipe, reg_date, inactive, mod_code,mod_sub_cat_id';
	    $based_field = 'branch_code, mod_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_modifiers($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'modifiers';
	    $selected_field = 'mod_id, name, cost, has_recipe, reg_date, inactive, mod_code,mod_sub_cat_id';
	    $based_field = 'branch_code, mod_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_menu_modifiers($migrate_id=null,$last_update=null){
		$table_name = 'menu_modifiers';
	    $selected_field = 'id, menu_id, mod_group_id';
	    $based_field = 'branch_code, id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_menu_modifiers($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'menu_modifiers';
	    $selected_field = 'id, menu_id, mod_group_id';
	    $based_field = 'branch_code, id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'id';

		$based_delete_id_ = 'menu_id';
		$based_delete_id = 'menu_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$delete_args = array();

				foreach($trans_based_id_raw as $b){
					$delete_args[$based_delete_id][]  = $b->$based_delete_id_ ;
				}

				$this->delete_trans_misc_batch($table_name,$delete_args,'main_db');
				$this->delete_trans_misc_batch($table_name,$delete_args,'db');

				if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->delete_trans_misc_batch($table_name,$delete_args,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	// $this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;

            }
            
        }

	}

	function download_receipt_discounts($migrate_id=null,$last_update=null){
		$table_name = 'receipt_discounts';
	    $selected_field = 'disc_id, disc_code, disc_name, disc_rate, no_tax, fix , inactive,  datetime';
	    $based_field = 'disc_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'disc_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_receipt_discounts($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'receipt_discounts';
	    $selected_field = 'disc_id, disc_code, disc_name, disc_rate, no_tax, fix , inactive,  datetime';
	    $based_field = 'disc_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'disc_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;

            }
            
        }

	}

	function download_charges($migrate_id=null,$last_update=null){
		$table_name = 'charges';
	    $selected_field = 'charge_id, charge_code, charge_name, charge_amount, absolute, no_tax , inactive';
	    $based_field = 'charge_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'charge_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_charges($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'charges';
	    $selected_field = 'charge_id, charge_code, charge_name, charge_amount, absolute, no_tax , inactive';
	    $based_field = 'charge_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'charge_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_modifier_prices($migrate_id=null,$last_update=null){
		$table_name = 'modifier_prices';
	    $selected_field = 'id, mod_id, trans_type, price';
	    $based_field = 'branch_code, mod_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_modifier_sub($migrate_id=null,$last_update=null){
		$table_name = 'modifier_sub';
	   $selected_field = 'mod_sub_id, mod_id, name, cost';
	   $based_field = 'branch_code, mod_sub_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_sub_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_transaction_types($migrate_id=null,$last_update=null){
		$table_name = 'transaction_types';
	    $selected_field = 'trans_id, trans_name, inactive';
	    $based_field = 'branch_code, trans_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'trans_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_transaction_types($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'transaction_types';
	    $selected_field = 'trans_id, trans_name, inactive';
	    $based_field = 'branch_code, trans_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'trans_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_modifier_sub_prices($migrate_id=null,$last_update=null){
		$table_name = 'modifier_sub_prices';
	    $selected_field = 'id, mod_sub_id, trans_type, price';
	    $based_field = 'branch_code, mod_sub_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_sub_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_modifier_prices($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'modifier_prices';
	    $selected_field = 'mod_id, trans_type, price';
	    $based_field = 'branch_code, mod_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_update_modifier_sub($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'modifier_sub_prices';
	    $selected_field = 'mod_sub_id, trans_type, price';
	    $based_field = 'branch_code, mod_sub_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_sub_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_update_modifier_sub_prices($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'modifier_sub_prices';
	    $selected_field = 'mod_sub_id, trans_type, price';
	    $based_field = 'branch_code, mod_sub_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'mod_sub_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_menu_prices($migrate_id=null,$last_update=null){
		$table_name = 'menu_prices';
	    $selected_field = 'id, menu_id, trans_type, price';
	    $based_field = 'branch_code, menu_id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_menu_prices($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'menu_prices';
	    $selected_field = 'id, menu_id, trans_type, price';
	    $based_field = 'branch_code, menu_id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'menu_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_brands($migrate_id=null,$last_update=null){
		$table_name = 'brands';
	    $selected_field = 'id, brand_code, brand_name , inactive';
	    $based_field = 'id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_brands($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'brands';
	    $selected_field = 'id, brand_code, brand_name , inactive';
	    $based_field = 'id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_payment_group($migrate_id=null,$last_update=null){
		$table_name = 'payment_group';
	    $selected_field = 'payment_group_id, code, description, inactive, reg_date';
	    $based_field = 'branch_code, payment_group_id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'payment_group_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_payment_group($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'payment_group';
	    $selected_field = 'payment_group_id, code, description, inactive, reg_date';
	    $based_field = 'branch_code, payment_group_id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'payment_group_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_payment_types($migrate_id=null,$last_update=null){
		$table_name = 'payment_types';
	    $selected_field = 'payment_id, payment_code, description, payment_group_id, inactive, reg_date';
	    $based_field = 'branch_code, payment_id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'payment_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_payment_types($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'payment_types';
	    $selected_field = 'payment_id, payment_code, description, payment_group_id, inactive, reg_date';
	    $based_field = 'branch_code, payment_id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'payment_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_payment_type_fields($migrate_id=null,$last_update=null){
		$table_name = 'payment_type_fields';
	    $selected_field = 'field_id,payment_id, field_name, inactive';
	    $based_field = 'branch_code, field_id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'field_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_payment_type_fields($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'payment_type_fields';
	    $selected_field = 'field_id,payment_id, field_name, inactive';
	    $based_field = 'branch_code, field_id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'field_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_gift_cards($migrate_id=null,$last_update=null){
		$table_name = 'gift_cards';
	    $selected_field = 'gc_id,card_no, amount, description_id, brand_id, inactive,pos_id';
	    $based_field = 'branch_code, gc_id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'gc_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id,'sync_id'=>0) , true,false);

            	$ndata = array();
            	foreach($data as $dbdata){            		
            		unset($dbdata->pos_id);
            		$ndata[] = $dbdata;
            	}
				// $add_trans_wrapped_default = $this->formulate_object($data , array('sync_id'=>0) , true,false);

				$add_trans_wrapped_default = $this->formulate_object($ndata , array('sync_id'=>0) , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                	$ndata = array();
		            	foreach($data as $dbdata){
		            		if($dbdata->pos_id == $vals->pos_id){
		            			unset($dbdata->pos_id);
		            			$ndata[] = $dbdata;
		            		}
		            		
		            	}

		            	$add_trans_wrapped_default = $this->formulate_object($ndata , array('sync_id'=>0) , true,false);

	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_gift_cards($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'gift_cards';
	    $selected_field = 'gc_id,card_no, amount, description_id, brand_id, inactive,pos_id';
	    $based_field = 'branch_code, gc_id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'gc_id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);

            	$ndata = array();
            	foreach($data as $dbdata){            		
            		unset($dbdata->pos_id);
            		$ndata[] = $dbdata;
            	}

            	// $add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$add_trans_wrapped_default = $this->formulate_object($ndata , array('sync_id'=>0) , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                	$ndata = array();
		            	foreach($data as $dbdata){
		            		if($dbdata->pos_id == $vals->pos_id){
		            			unset($dbdata->pos_id);
		            			$ndata[] = $dbdata;
		            		}
		            		
		            	}

		            	$add_trans_wrapped_default = $this->formulate_object($ndata , array('sync_id'=>0) , true,false);

	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	function download_transaction_type_categories($migrate_id=null,$last_update=null){
		$table_name = 'transaction_type_categories';
	    $selected_field = 'id, trans_id, menu_cat_id';
	    $based_field = 'branch_code, id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download';

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if(!empty($data)){ 
            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
				$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
			 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

			 	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

			 	set_time_limit(60);
            }
            
        }

	}

	function download_update_transaction_type_categories($migrate_id=null,$last_update=null){
		// $update_date = date('Y-m-d H:i:s');

		$table_name = 'transaction_type_categories';
	    $selected_field = 'id, trans_id, menu_cat_id';
	    $based_field = 'branch_code, id';

		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		$tbl_id = 'id';
				
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();

		$post['branch_code'] = $branch_code;
		$post['terminal_id'] = $terminal_id;
		$post['results'] = $trans_raw;
		$post['selected_field'] = $selected_field;
		$post['based_field'] = $based_field;
		$post['table_name'] = $table_name;
		$post['transaction'] = $table_name;
		$post['tbl_id'] = $tbl_id;
		$post['user_id'] = $user_id;
		$post['type'] = 'download_update';
		$post['update_date']=$last_update;

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'download_data';
		}else{
			$url = $this->prod_url.'download_data';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            // print_r($response);
            $result = json_decode($response);
            $data = !empty($result) ? $result->result : array();
            // print_r($result);exit;
            if(!empty($result->src_id)){  
            	$is_automated = 0;

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($result->src_id,$tbl_id)));

	           $migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');     

	           $this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$result->master_id),NULL,NULL,'main_db');

	           $this->has_update = true;

	           // echo 123;
	           // $this->update_tbl('master_logs',array("master_sync_id"=>$data->master_id)),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
	            set_time_limit(60);
            }else if($data){
            	// $nresult = $this->object_flat($data,$tbl_id);

            	$add_trans_wrapped = $this->formulate_object($data , array('master_id' => $result->master_id) , true,false);
            	$add_trans_wrapped_default = $this->formulate_object($data , array() , true,false);

            	$this->update_tbl_batch($table_name,$add_trans_wrapped, $tbl_id ,'main_db');
            	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');

            	if(CONSOLIDATOR){
			 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);
	                    $this->db->trans_start();
	                    	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $tbl_id ,'db');
	                    $this->db->trans_complete();
	                }

	                $this->db = $this->db_manager->get_connection('default');
			 	}

            	$json_encode  = json_encode(array($tbl_id=>$this->array_flat($data,$tbl_id)));

            	$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db');

            	$this->has_update = true;
            }
            
        }

	}

	public function update_tbl($table_name,$table_key,$items,$id=null,$set=array(),$db="migrate_db",$or = false){
		if(is_array($table_key)){
			foreach ($table_key as $key => $val) {
				if($or){

					if(is_array($val)){
						$this->$db->or_where_in($key,$val);
					}
					else
						$this->$db->or_where($key,$val,FALSE);
				}else{

					if(is_array($val)){
						$this->$db->where_in($key,$val);
					}
					else
						$this->$db->where($key,$val,FALSE);
				}
			}
		}
		else{
			if(is_array($id)){
				$this->$db->where_in($table_key,$id);
			}
			else
				$this->$db->where($table_key,$id);
		}
		if(!empty($set)){
			foreach ($set as $key => $val) {
				$this->$db->set($key, $val, FALSE);
			}
		}
		$this->$db->update($table_name,$items);
		return $this->$db->last_query();
	}

	public function add_tbl_batch($table_name,$items,$db='migrate_db'){
		
		if($db == 'migrate_db'){
			$this->$db->insert_ignore_batch($table_name,$items);
			return $this->$db->insert_id();
		}else{
			$counter = 0;
			foreach($items as $item){
				$this->$db->insert($table_name,$item);

				if($counter % 100 == 0){
		            set_time_limit(60);  
		        }

	            $counter++;
			}

			return $this->$db->insert_id();	
		}
			
	}

	public function delete_trans_misc_batch($table_name=null,$args=null,$db="migrate_db"){
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		 // $data = $rec->src_id;
        // $args_decoded = json_decode($args,false);
        // $args = $this->formulate_object($args_decoded,array(),true,false);
		// echo "<pre>",print_r($args),"</pre>";
		// echo $branch_code;
		// echo $terminal_id;	
		if( !empty($branch_code) && !empty($terminal_id)) {
			if(!empty($args)){

				// foreach($args as $a){

					foreach ($args as $col => $val) {
						// foreach($val)
						if(is_array($val)){
							if(!isset($val['use'])){
								$this->$db->where_in($col,$val);
							}
							else{
								$func = $val['use'];
								$this->$db->$func($col,$val['val']);
							}
						}
						else
							$this->$db->where($col,$val);

					
					}
				// }
				// search for specific branch_code and terminal_id
				if($db == 'migrate_db'){

					$this->$db->where('branch_code',BRANCH_CODE);
					$this->$db->where('terminal_id',TERMINAL_ID);
				}
				$this->$db->delete($table_name);
				// echo "<pre>",print_r($args),"</pre>";
				// echo "delete batch query: " . $this->$db->last_query()."<br>";//die();
				
			}


		}
	}

	public function execute_migration_download_items(){
		$last_log = $this->check_last_download_log();
		$finish_log =  $this->get_finish_download_log();
			// echo "<pre>",print_r($finish_log),"</pre>";die();
			// // add records from main to master
			update_load(30);
			
			// download records including update from master to main
			$download_functions_array = array(  'download_categories','download_update_categories', 'download_menu_categories', 'download_update_menu_categories',
											  'download_menu_subcategories','download_update_menu_subcategories', 'download_subcategories' , 'download_update_subcategories',
											  'download_menu_subcategory' , 'download_update_menu_subcategory',
											  'download_menus','download_update_menus','download_items','download_update_items',
											  'download_menu_schedules', 'download_update_menu_schedules','download_menu_recipe','download_update_menu_recipe',
											  'download_modifier_recipe','download_update_modifier_recipe', 
											  'download_modifier_groups','download_update_modifier_groups',
											  'download_modifier_group_details','download_update_modifier_group_details',
											  'download_modifiers','download_update_modifiers',
											  'download_menu_modifiers','download_update_menu_modifiers',
											  'download_receipt_discounts','download_update_receipt_discounts',
											  'download_charges','download_update_charges','download_modifier_prices','download_modifier_sub','download_modifier_sub_prices','download_update_modifier_prices','download_update_modifier_sub','download_update_modifier_sub_prices','download_transaction_types','download_update_transaction_types','download_menu_prices','download_update_menu_prices','download_brands','download_update_brands',
											  'download_payment_types','download_update_payment_types','download_transaction_type_categories','download_update_transaction_type_categories',

						);

				// $download_functions_array = array('download_modifier_sub');
			

				// run download array()
				$loader = 31;
				foreach($download_functions_array as $func){
					set_time_limit(300);
					update_load($loader);
					if($last_log){
						// $migrate_id = $last_log->master_id;
						$migrate_id = $finish_log->master_sync_id;
						$last_update = $finish_log->migrate_date;
						$this->$func($migrate_id,$last_update);

					}else{
						$this->$func();
					}

					$loader++;
				}


				// add a new finish log()

				$this->finish_log('finish_download');


			return json_encode($last_log);

			// echo "<pre>",print_r($last_log),"</pre>";die();
	}

	public function check_last_download_log(){
		$last_logs = $this->main_db->order_by('master_id desc')->limit('1')->get_where('master_logs',array('type'=>'finish_download'))->result();
		$last_log = false;
		// echo "<pre>",print_r($last_logs),"</pre>";die();

		if(isset($last_logs[0]) && !empty($last_logs)){
			$last_log = $last_logs[0];
		}

		return $last_log;


	}

	public function get_finish_download_log(){
		$last_logs = $this->main_db->order_by('master_id desc')->limit('2')->get_where('master_logs',array('type'=>"finish_download"))->result();
		$last_log = false;
		// echo "<pre>",print_r($last_logs),"</pre>";die();
		if(isset($last_logs[2]) && !empty($last_logs)){ // get second to the last log if available , to consider some lapse time in last finish migration
			$last_log = $last_logs[2];
		}elseif(isset($last_logs[1]) && !empty($last_logs)){ // get second to the last log if available , to consider some lapse time in last finish migration
			$last_log = $last_logs[1];
		}elseif(isset($last_logs[0]) && !empty($last_logs)){
			$last_log = $last_logs[0];
		}

		return $last_log;


	}

	public function finish_log($type){
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		// $migration_sync_id = $this->add_tbl('master_logs',array("status"=>"1","type"=>'finish_download',"transaction"=> 'master_logs','user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'sender_ip_address'=>$this->getRealIpAddr()));

		$post['branch_code'] = BRANCH_CODE;
		$post['terminal_id'] = TERMINAL_ID;
		$post['status'] = 1;
		$post['type'] = $type;
		$post['transaction'] = 'master_logs';
		$post['user_id'] = $user_id;
		$post['sender_ip_address']=$this->getRealIpAddr();

			/* API URL */
        
        // $url = 'http://hqtest1.point1solution.net/migrate_'.$api;
        // $url = 'http://localhost/test/';

        if($this->environment == 'dev'){
			$url = $this->dev_url.'finish_log';
		}else{
			$url = $this->prod_url.'finish_log';
		}

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
        	)
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
        		$result = json_decode($response);
        		 // status 0 pending ,  1 success , 3 failed
				$migration_id = $this->add_tbl('master_logs',array("status"=>"1","type"=>$type,"transaction"=> 'master_logs','user_id'=>$user_id,'master_sync_id'=>$result->master_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
        }
		
					
	}

	public function getRealIpAddr()
		{
		    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		    {
		      $ip=$_SERVER['HTTP_CLIENT_IP'];
		    }
		    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		    {
		      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		    }
		    else
		    {
		      $ip= $_SERVER['REMOTE_ADDR'];
		    }
		    return $ip;
		}

	//initial master download
	public function download_to_hq(){
		$last_log = $this->check_last_log();
		$finish_log =  $this->get_finish_log();

		$download_functions_array = array('download_categories',
			'download_menu_categories',
			'download_menu_subcategories',
			'download_subcategories' ,
			'download_menu_subcategory' ,
			'download_menus',
			'download_items',
			'download_menu_schedules',
			'download_menu_recipe',
			'download_modifier_recipe', 
			'download_modifier_groups',
			'download_modifier_group_details',
			'download_modifiers',
			'download_menu_modifiers',
			'download_receipt_discounts',
			'download_charges',
			'download_modifier_prices',
			'download_modifier_sub',
			'download_transaction_types',
			'download_modifier_sub_prices',
			'download_menu_prices',
			'download_brands',
			'download_payment_group',
			'download_payment_types',
			'download_payment_type_fields',
			'download_gift_cards',
		);

		// run download array()
		foreach($download_functions_array as $func){
			set_time_limit(300);
			if($last_log && $finish_log){
				// $migrate_id = $last_log->master_id;
				$migrate_id = $finish_log->master_sync_id;
				$last_update = $finish_log->migrate_date;
				$this->$func($migrate_id,$last_update);

			}else{
				$this->$func();
			}
		}
		

	}

	public function check_trans_sales(){
		$table_name = 'trans_sales';

		
		$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL),$this->item_limit)->result();
		return $trans_raw;
	}
		
}
?>