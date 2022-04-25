<?php
// require_once ("secure_area.php");
class Pos extends CI_controller
{
	function __construct()
	{
		parent::__construct('pos');
		$this->load->helper('url');
	    $this->load->model('app/Pos_app');

		// if(!isset($_SESSION['person_id'])){
		// 	 redirect('/', 'refresh');
		// }
		// echo "<pre>",print_r($_SESSION),"</pre>";die();
		// $this->has_profit_permission = $this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id);
		// $this->has_cost_price_permission = $this->Employee->has_module_action_permission('reports','show_cost_price',$this->Employee->get_logged_in_employee_info()->person_id);
	}

	function index()
	{

		// echo "tesa";die();
		$this->load->view('pos/parts/head');	
		$this->load->view('pos/parts/foot');	
		$this->load->view("pos/home");
	}

	function index2()
	{
		// echo "tesa";
		$this->load->view('parts/head2');	
		$this->load->view('parts/foot');	
		$this->load->view("pos/home2");
	}

	function shop($category="")
	{
		// session_start();
		// echo "<pre>",print_r($_SESSION)		,"</pre>";die();
		// session_destroy();
		$category = strtolower(urldecode($category));
		// temporary constant for demo
		$menu_cat = $this->Pos_app->get_menu_categories();
		$data['menu_cats'] = $menu_cat;
		foreach ($menu_cat as $menu_cat_key => $menucat_val) {
			$items = $this->Pos_app->get_all_by_category($menucat_val->menu_cat_id);
			$menu_items[$menucat_val->menu_cat_id] = $items->result();
		}
		// echo "<pre>",print_r($menu_items),"</pre>";die();
		$data['items'] = $menu_items;
		$data['category'] = $category;
		$data['menus'] = $this->Pos_app->get_menu_info();

		$data['item_cart'] = $this->get_cart_items();

		$data['finish'] = false;
		// echo "<pre>",print_r($_SESSION),"</pre>";die();
		$file_ads = './'. menu_folder;
		$data['image_compiled'] = array();
		if ($handle = opendir($file_ads)) {

   			 while (false !== ($entry = readdir($handle))) {

        		if ($entry != "." && $entry != "..") {
        			$data['image_compiled'][] = array("path"=>base_url().menu_folder.''.$entry,"image_name"=>$entry);
       			}
 	   		}

    		closedir($handle);
		}
		// echo "<pre>",print_r($data),"</pre>";die();

		$this->load->view('pos/parts/head');	
		$this->load->view("pos/shop_list",$data);	
		$this->load->view('pos/parts/foot');	
	}

	function search($search="")
	{
		// echo "sss";die();
		$search = urldecode($search);

		// echo $search;die();
		$menu_cat = $this->Pos_app->get_menu_categories();
		$this->data['menu_cats'] = $menu_cat;

		$this->data['menus'] = $this->Pos_app->get_menu_info();
		// echo "<pre>",print_r($menus),"</pre>";die();
		// foreach ($menu_cat as $menu_cat_key => $menucat_val) {
			// $items = $this->Pos_app->get_all_by_category($search);
		$search_items = $this->Pos_app->get_all_by_search($search);
		$this->data['items'] = $search_items->result();
		// $data['items'] = $menu_items;
			// $menu_id[] = $menucat_val->menu_cat_id;
		// }
		// if($category != "$1"){
		$this->data['category'] = $search;
		// echo $this->data['category'];die();
		// echo $this->db->last_query();die();
		// $this->data['items'] = $items->result();


		$this->data['item_cart'] = $this->get_cart_items();
		$this->data['finish'] = false;
		// echo "<pre>",print_r($_SESSION),"</pre>";die();

		$this->load->view('pos/parts/head');	
		$this->load->view("pos/search_shop_list",$this->data);	
		$this->load->view('pos/parts/foot');	
	}
	

	function add_to_cart($item_info=array()){
		if(session_id() == '') {
				session_start();
		}
		// unset($_SESSION['cart_items']);die();
		$post = $this->input->post();
		// echo 'hahaha';
		// echo "<pre>",print_r($post),"</pre>";die();
		// echo count($post);die();
		// if(count($post) > 1){

		// }else{
			$item_info = array('item_id' => $post['ref'] , 'file_id' => $post['imref'] ,'qty'=>1,'modifiers'=>$post['bref'],'modname'=>$post['brefname']);
		// }
			// echo "<pre>",print_r($item_info),"</pre>";die();
// echo "<pre>before:",print_r($_SESSION),"</pre>";
		$_SESSION['cart_items'][$post['ref']] =  $item_info; 
// echo "<pre>",print_r($_SESSION),"</pre>";die();
		
		echo true;
	}


	function update_to_cart($item_info=array()){
		if(session_id() == '') {
				session_start();
		}
		// unset($_SESSION['cart_items']);die();
		$post = $this->input->post();
		// echo "<pre>",print_r($post),"</pre>";die();
    	// echo 'dasda';

		if(!empty($post)){
			if(isset($_SESSION['cart_items'][$post['ref']]) ){

				$_SESSION['cart_items'][$post['ref']]['qty'] = $post['qty'];
			
			}else{

				$_SESSION['cart_items'][$post['ref']] = array('qty' => $post['qty'] ,'item_id'=> $post['ref'],'remarks'=> $post['remarks']);

			}
			// $br = (isset($item['bref']) ? $item['bref'] : '');
			// $brn = (isset($item['brefname']) ? $item['brefname'] : '');
			// foreach($post['item_list'] as $item){
			// 	$_SESSION['cart_items'][$item['ref']]['qty'] = $item['qty']; 
			// 	// $_SESSION['cart_items'][$item['ref']]['modifiers'] = $br; 
			// 	// $_SESSION['cart_items'][$item['ref']]['modname'] = $brn; 
			// }
		}
		// echo "<pre>",print_r($_SESSION),"</pre>";die();
		
		echo true;
	}

	function update_to_cart_queue($item_info=array()){
		if(session_id() == '') {
				session_start();
		}
		// unset($_SESSION['cart_items']);die();
		$post = $this->input->post();
		// echo "<pre>",print_r($post),"</pre>";die();
    	// echo 'dasda';

		if(!empty($post)){
			// $br = (isset($item['bref']) ? $item['bref'] : '');
			// $brn = (isset($item['brefname']) ? $item['brefname'] : '');
			if(isset($_SESSION['cart_items'][$post['ref']]) ){
				$_SESSION['cart_items'][$post['ref']]['qty'] = $post['qty'];
				$_SESSION['cart_items'][$post['ref']]['modifiers'] = $post['bref'];
				$_SESSION['cart_items'][$post['ref']]['modname'] = $post['brefname'];
				$_SESSION['cart_items'][$post['ref']]['remarks'] = $post['remarks'];

				// foreach($post['item_list'] as $item){

				// 	$_SESSION['cart_items'][$item['ref']]['qty'] = $item['qty']; 
				// 	$_SESSION['cart_items'][$item['ref']]['modifiers'] = $item['bref']; 
				// 	$_SESSION['cart_items'][$item['ref']]['modname'] = $item['brefname']; 
				// }
			}else{
				$_SESSION['cart_items'][$post['ref']] = array('qty' => $post['qty'] , 'modifiers' => $post['bref'], 'modname' => $post['brefname'],'item_id'=>$post['ref'],'remarks'=>$post['remarks']);
			}
			
		}
		// echo "<pre>",print_r($_SESSION),"</pre>";die();
		
		echo true;
	}

	function remove_to_cart($item_info=array()){
		if(session_id() == '') {
				session_start();
		}
		// unset($_SESSION['cart_items']);die();
		$post = $this->input->post();
		// session_start();

		// echo count($post);die();
		// if(count($post) > 1){

		// }else{

		if(isset($_SESSION['cart_items'][$post['ref']])){

			unset($_SESSION['cart_items'][$post['ref']]);
		}
		// }
		
		echo true;
	}

	function get_cart_items($img_included=true,$calculate_total = false){
		if(session_id() == '') {
		session_start();
		}
		$cart_items = array();
		// $total = 0;
		// $total_tax = 0;
		if(!empty($_SESSION['cart_items'])){
			$cart_items = $_SESSION['cart_items'];
			foreach($cart_items as $c_id => &$c_items){
				$item_id = $c_id;
				$item_data = $this->Pos_app->get_item_info($item_id);
				$itemmodi = (isset($c_items['modifiers']) ? $c_items['modifiers'] : 0);
				$tableinfo = (isset($c_items['tableinfo']) ? $c_items['tableinfo'] : 0);
				$item_mod_data = $this->Pos_app->get_modifier($itemmodi);
				// $table_mod_data = $this->Pos_app->get_table_id($tableinfo);
				// $modifiers = $item_mod_data->result();
				// echo "<pre>",print_r($cart_items),"</pre>";die();
				if(!empty($item_mod_data[0]) && isset($item_mod_data[0])){
					if(!empty($item_data[0]) && isset($item_data[0])){
						$cart_items[$c_id]['item_name'] = $item_data[0]->menu_short_desc;
						$cart_items[$c_id]['remarks'] = $item_data[0]->remarks;
						$cart_items[$c_id]['modifier_name'] = $item_mod_data[0]->name;
						$cart_items[$c_id]['mod_id'] = $item_mod_data[0]->mod_id;
						$cart_items[$c_id]['cost'] = $item_mod_data[0]->cost;
						$cart_items[$c_id]['mod_group_id'] = $item_mod_data[0]->mod_group_id;
						$cart_items[$c_id]['tableinfo'] = $tableinfo;
						$cart_items[$c_id]['unit_price_label'] = number_format($item_data[0]->cost,2,'.',',');
						$cart_items[$c_id]['unit_price'] = number_format($item_data[0]->cost,2);
						$cart_items[$c_id]['tax_included'] = '1'; 

						if($calculate_total){
							if(isset( $cart_items['summary']['total'])){

							 $cart_items['summary']['total']  += $item_data[0]->cost * $cart_items[$c_id]['qty'] ;
							  // $cart_items['summary']['total']
							}else{
								$cart_items['summary']['total']  = $item_data[0]->cost * $cart_items[$c_id]['qty'] ;
							}
						}
						// $cart_items[$c_id]['img_path'] = $
						if(!empty($c_items['file_id'])){
							
							$file_id = (int) $c_items['file_id'];

							$img_data = $this->Pos_app->get_image($file_id);
							// echo $img_data;die();

							// if($img_included)
							if(!empty($img_data)){
								// print_r($img_data);die();
								$cart_items[$c_id]['item_img'] = $img_data;
							}
						}
					}
				}else{
					if(!empty($item_data[0]) && isset($item_data[0])){
						$cart_items[$c_id]['item_name'] = $item_data[0]->menu_short_desc;
						$cart_items[$c_id]['modifier_name'] = '';
						$cart_items[$c_id]['unit_price_label'] = number_format($item_data[0]->cost,2,'.',',');
						$cart_items[$c_id]['unit_price'] = number_format($item_data[0]->cost,2);
						$cart_items[$c_id]['tax_included'] = '1'; 
						// $cart_items[$c_id]['remarks'] = $item_data[0]->remarks; 

						if($calculate_total){
							if(isset( $cart_items['summary']['total'])){

							 $cart_items['summary']['total']  += $item_data[0]->cost * $cart_items[$c_id]['qty'] ;
							  // $cart_items['summary']['total']
							}else{
								$cart_items['summary']['total']  = $item_data[0]->cost * $cart_items[$c_id]['qty'] ;
							}
						}
						// $cart_items[$c_id]['img_path'] = $
						if(!empty($c_items['file_id'])){
							
							$file_id = (int) $c_items['file_id'];

							$img_data = $this->Pos_app->get_image($file_id);
							// echo $img_data;die();

							// if($img_included)
							if(!empty($img_data)){
								// print_r($img_data);die();
								$cart_items[$c_id]['item_img'] = $img_data;
							}
						}
					}

				}


			}



		}
			// echo "<pre>",print_r($cart_items),"</pre>";die();

		return $cart_items;

	}


	function cart()
	{
		if(session_id() == '') {
				session_start();
		}
	    $this->load->model('dine/cashier_model');
       	
        $occ_tbls = $this->cashier_model->get_occupied_tables();
        $occupied_arr = array();
            $this->load->helper('form_helper');

		$cart_items = array();
		// echo "<pre>dd",print_r($occ_tbls),"</pre>";die();
		if(!empty($occ_tbls)){
			foreach($occ_tbls as $oc_tbls){
				$occupied_arr[$oc_tbls->table_id] = $oc_tbls;
			}
		}

		if(!empty($_SESSION['cart_items'])){
			$cart_items = $_SESSION['cart_items'];
		}



		$data['payment_options']=array(
				lang('sales_cash') => lang('sales_cash'),
				lang('sales_credit') => lang('sales_credit')
				);

		$tableinfo = $this->Pos_app->get_table_info();
		$data['tableinfo'] = $tableinfo;
		$data['occupied_table'] = $occupied_arr;
		$data['food_server'] = $this->Pos_app->get_food_server_info();

		$data['item_cart'] = $this->get_cart_items(); 
		$data['checkout_details'] =  (isset($_SESSION['cart_details'])) ?  $_SESSION['cart_details'] : array();
		// echo "<pre>",print_r($data),"</pre>";die();
		$this->load->view('pos/parts/head');	
		$this->load->view("pos/cart",$data);	
		$this->load->view('pos/parts/foot');	

	}



	function checkout()
	{
		include_once (realpath(dirname(__FILE__) . '/..') . "\dine\cashier.php");

        $this->load->model('dine/clock_model');
		$cashier = New Cashier();
		if(session_id() == '') {
				session_start();
		}
		// echo 'fasfas';die();
		$cart_items = array();
		$employee_id = isset($this->session->userdata('user')['id'])  ? $this->session->userdata('user')['id'] :1;
		$checkout_details = isset($_SESSION['cart_details']) ?  $_SESSION['cart_details'] : array();
	    $get_shift = $this->clock_model->get_shift_id(null,$employee_id);
	    $shift_id =  isset($get_shift[0]->shift_id) ? $get_shift[0]->shift_id : 9999999;
	    

	    $cart_items =  $this->get_cart_items(false); 
		$get_total = $this->get_cart_items(false,true); 


		// echo "<pre>",print_r($checkout_details),"</pre>";die();

		$payment_type = (isset($_SESSION['cart_details']['payment_type'])) ? $checkout_details['payment_type'] : '';
		$comment = (isset($_SESSION['cart_details']['comment'])) ? $checkout_details['comment'] : '';
		$payment_amount = (isset($_SESSION['cart_details']['payment_amount'])) ? $checkout_details['payment_amount'] : '';
		$tableinfo = (isset($_SESSION['cart_details']['tableinfo'])) ? $checkout_details['tableinfo'] : '';
		$foodserver = (isset($_SESSION['cart_details']['foodserver'])) ? $checkout_details['foodserver'] : '';
		$amount_due = (isset($get_total['summary']['total'])) ? $get_total['summary']['total'] : 0;
		$tax_rate = 12 ; //temporary constant
		$dividend = "1.".$tax_rate;
		 				$srp = $amount_due / floatval($dividend);
		 				$srp_tax =  $srp * floatval('.'.$tax_rate);
		
		// echo $amount_due. " : " .$srp_tax;die();
		// echo $employee_id;die();
 		// $employee_location = $this->Pos_app->get_employee_location($employee_id);
 		// $tax_rate = $this->Pos_app->get_tax_rate();

		$sales = $sales_items  = $sales_item_taxes = $menu_modifier =   array();

		$error = false;
		// echo $tax_rate;

		// echo "<pre>",print_r($cart_items),"</pre>";die();

		if(!empty($cart_items)) {

			// $cart_items = array('type_id' => SALES_TRANS,
			// 					'type' => 'takeout',
			// 					'user_id'=> $employee_id,
			// 					'shift_id' => $shift_id,
			// 					'terminal_id' => 1, //temporary constant
			// 					'total_amount' => '',
			// 					'total_paid' => $payment_amount,
			// 					'memo'=>$comment

			// 	);
			$now = $this->site_model->get_db_now();
			$strtotime_now = date('Y-m-d H:i:s',strtotime($now));
		// 			echo $strtotime_now . "<br>";
		// 			echo date('Y-m-d H:i:s');
		// echo date('Y-m-d h:i:s');
		// 			die();

			$sales = array('type_id' => SALES_TRANS,
								'type' => 'App Order',
								'user_id'=> $employee_id,
								'shift_id' => $shift_id,
								'terminal_id' => 1, //temporary constant
								'guest' => 1, //temporary constant
								'total_amount' => $amount_due,
								'total_paid' => $payment_amount,
								'table_id' => $tableinfo,
								'waiter_id' => $foodserver,
								'total_paid' => $payment_amount,
								'memo'=>$comment,
								'datetime' => $strtotime_now,

				);
	 		$sales_id = $this->Pos_app->insert_sales($sales);
	 		// echo $sales_id;die();
	 			// echo "<pre>",print_r($cart_items),"</pre>";die();
	 		foreach($cart_items as $item){

	 			if(!empty($item['modifiers'])){
	 				$t = explode(' ', $item['modifiers']);
	 				foreach ($t as $modkey => $mod) {
	 					if($mod != ""){
	 						$menu_modifier = array('menu_id' => $item['item_id'],
	 										'sales_id'=> $sales_id,
	 										'mod_id' => $mod,
	 										'line_id' => 1,
	 										'mod_group_id' => $item['mod_group_id'],
	 										'price' => $item['cost'],
	 										'qty' => 1);

	 						$modifier_id = $this->Pos_app->insert_modifier($menu_modifier);
	 					}
 					}
	 			}
	 		}
	 		// echo "<pre>",print_r($cart_items),"</pre>";die();
	 		// $sales = array('type_id' => SALES_TRANS,
				// 				'type' => 'App Order',
				// 				'user_id'=> $employee_id,
				// 				'shift_id' => $shift_id,
				// 				'terminal_id' => 1, //temporary constant
				// 				'guest' => 1, //temporary constant
				// 				'total_amount' => $amount_due,
				// 				'total_paid' => $payment_amount,
				// 				'memo'=>$comment,
				// 				'datetime' => $strtotime_now,

				// );
			
			

	 		// $payment = array('sale_id'=>$sales_id,
				// 			'payment_amount'=> $payment_amount,
				// 			'payment_type'=> $payment_type,

				// 			);
	 		//  // echo "<pre>",print_r($payment),"</pre>";die();
	 		//  $this->Pos_app->insert_payment_amount($payment);
	 		// // echo $this->db->last_query();die();
	 		$ctr = 1;


	 		if($sales_id){
	 			$sales_item_taxes = array('sales_id'=>$sales_id,
		 								'name'=> 'VAT',
		 								'rate'=> $tax_rate,
		 								'amount'=> $srp_tax);
	 			$this->Pos_app->insert_sales_tax($sales_item_taxes);

		 		foreach($cart_items as $item){
		 			if(empty($item['item_id'])){
		 				continue;
		 			}
		 			$srp = $item['unit_price'];
		 			$srp_tax = 0;
		 			

		 			// var_dump($srp);
		 			// var_dump(" ".$srp_tax);
		 			// die();
		 			$sales_items[] = array('sales_id'=>$sales_id,
		 									'menu_id'=> $item['item_id'],
		 									'line_id' => $ctr , 
		 									'qty'=> $item['qty'],
		 									'remarks'=> $item['remarks'],
		 									// 'item_cost_price'=> $item['cost_price'],
		 									'price'=> $srp);

		 			$ctr++;
		 		}

		 		 $sales_resp = $this->Pos_app->batch_insert('trans_sales_menus',$sales_items);

		 		

	 		}else{
	 			$error = true;
	 		}

		 		 $cashier->print_os($sales_id,false);
		}else{
			 redirect('/app', 'refresh');
		}
		// echo 'haha';die(); 
		unset($_SESSION['cart_items']);
		unset($_SESSION['cart_details']);

		//add to session pending orders

		$_SESSION['pending_orders'][$sales_id] = $sales_id;
		$_SESSION['unreceived_orders'][$sales_id] = $sales_id;

		$this->data['finish'] = true;
		$this->load->view('pos/parts/head');	
		$this->load->view("pos/home",$this->data);	
		$this->load->view('pos/parts/foot');	


	}

	function add_checkout_details($item_info=array()){
		if(session_id() == '') {
				session_start();
		}
		// unset($_SESSION['cart_items']);die();
		$post = $this->input->post();
		// echo 'haha';
		// echo "<pre>",print_r($post),"</pre>";die();
		$checkout_info = array('comment'=>$post['comment'],'tableinfo' => $post['tableinfo'],'foodserver' => $post['foodserver'] );
		$_SESSION['cart_details'] =  $checkout_info; 

		// echo "<pre>",print_r($_SESSION),"</pre>";die();
		echo true;
	}


	function check_order_status()
	{

		if(session_id() == '') {
				session_start();
		}
					// echo "<pre>",print_r($_SESSION),"</pre>";

		if(isset($_SESSION['pending_orders']) && !empty($_SESSION['pending_orders'])){

			$pending_orders = $_SESSION['pending_orders'];
			$get_ids = array();
			foreach($pending_orders as $sale_id => $p_o){
				$get_ids[] = $sale_id;
			}
			$check_orders = $this->Pos_app->get_sales_status($get_ids);
	// echo "<pre>",print_r($_SESSION),"</pre>";
			foreach($check_orders as $c_o){
				if(isset($_SESSION['pending_orders'][$c_o->sales_id]))
					unset($_SESSION['pending_orders'][$c_o->sales_id]);
			}

			// echo "<pre>",print_r($check_orders),"</pre>";die();
			echo json_encode($check_orders);
		}else{
			echo json_encode(array());
		}

	}

	function check_order_received_status()
	{
		if(isset($_SESSION['unreceived_orders']) && !empty($_SESSION['unreceived_orders'])){

			$pending_orders = $_SESSION['unreceived_orders'];
			$get_ids = array();
			foreach($pending_orders as $sale_id => $p_o){
				$get_ids[] = $sale_id;
			}
			$check_orders = $this->Pos_app->get_sales_received_status($get_ids);
	// echo "<pre>",print_r($_SESSION),"</pre>";
			foreach($check_orders as $c_o){
				if(isset($_SESSION['unreceived_orders'][$c_o->sale_id]))
					unset($_SESSION['unreceived_orders'][$c_o->sale_id]);
			}

			// echo "<pre>",print_r($check_orders),"</pre>";die();
			echo json_encode($check_orders);
		}else{
			echo json_encode(array());
		}

	}

	

	function image($file_id){
		ob_clean();
		$data['image'] = $this->Pos_app->get_image($file_id);
		$this->load->view('pos/image',$data);	
	}
	function tabledata()
	{
		// $sales_id = $this->Pos_app->insert_sales($sales);
		$data = array();

		$data['datatable_sales'] = $this->Pos_app->get_sales_payments();
		// echo $this->db->last_query();die();
		// $test['get_total'] = $this->Pos_app->get_total_();
		// $data['amount_change'] = $this->sale_lib->is_sale_cash_payment();
		// echo "<pre>",print_r($data),"</pre>";die();
		$this->load->view('pos/parts/head');	
		$this->load->view("pos/table",$data);	
		$this->load->view('pos/parts/foot');
	}
	function check_new_orders()
	{
		$check_orders = $this->Pos_app->get_pending_order_count();
		$status = false;
	// echo "<pre>",print_r($check_orders),"</pre>";die();
		if(isset($check_orders[0]->counter)) {
			$pending_order_count = $check_orders[0]->counter;

			// echo $pending_order_count;die();
			// $_SESSION['pending_order_count'] = ;

			if(!isset($_SESSION['pending_order_count'])){
				$_SESSION['pending_order_count'] = $pending_order_count;
			}

// echo $_SESSION['pending_order_count']. " ". $pending_order_count;
			if($_SESSION['pending_order_count'] != $pending_order_count){
				// echo "ss";
				$status = true;
			}
// 			echo  $pending_order_count;
// echo $_SESSION['pending_order_count'];
			$_SESSION['pending_order_count'] = $pending_order_count ;
		
		}

	 echo $status;
	}

	function recall()
	{

		$data = array();
		$this->load->view('pos/parts/head');	
		$this->load->view("pos/recall",$data);	
		$this->load->view('pos/parts/foot');	

	}

	public function get_branch_details($asJson=true){
       $this->load->model('dine/setup_model');
       $details = $this->setup_model->get_branch_details();
       $det = array();
       foreach ($details as $res) {
           $det = array(
                    "id"=>$res->branch_id,
                    "code"=>$res->branch_code,
                    "name"=>$res->branch_name,
                    "desc"=>$res->branch_desc,
                    "contact_no"=>$res->contact_no,
                    "delivery_no"=>$res->delivery_no,
                    "address"=>$res->address,
                    "base_location"=>$res->base_location,
                    "currency"=>$res->currency,
                    "tin"=>$res->tin,
                    "machine_no"=>$res->machine_no,
                    "bir"=>$res->bir,
                    "permit_no"=>$res->permit_no,
                    "serial"=>$res->serial,
                    "accrdn"=>$res->accrdn,
                    "email"=>$res->email,
                    "website"=>$res->website,
                    "pos_footer"=>$res->pos_footer,
                    "rec_footer"=>$res->rec_footer,
                    "layout"=>base_url().'uploads/static_layout.png'
                  );
       }
       if($asJson)
            echo json_encode($det);
        else
            return $det;
    }

    public function get_tables($asJson=true,$tbl_id=null){
        $this->load->model('dine/cashier_model');
        $tbl = array();
        $occ = array();
        $occ_tbls = $this->cashier_model->get_occupied_tables();
        $billed = array();
        foreach ($occ_tbls as $det) {
          $occ[] = $det->table_id;
        }
        foreach ($occ_tbls as $det) {
          if($det->billed == 1)
            $billed[] = $det->table_id;
        }
        $tables = $this->cashier_model->get_tables();
        foreach ($tables as $res) {
            $status = 'green';
            if(in_array($res->tbl_id, $occ)){
                if(in_array($res->tbl_id, $billed)){
                    $status = 'orange';
                }
                else
                    $status = 'red';
            }
            $tbl[$res->tbl_id] = array(
                "name"=> $res->name,
                "top"=> $res->top,
                "left"=> $res->left,
                "stat"=> $status
            );
        }
        if($asJson)
            echo json_encode($tbl);
        else
            return $tbl;
    }

    public function check_tbl_activity($tbl_id=null,$asJson=true){
        $error = "";
        $res = $this->site_model->get_tbl('table_activity',array('tbl_id'=>$tbl_id));
        if(count($res)>0){
            $error = "TERMINAL ".$res[0]->pc_id." is currently editing this table";            
        }
        else{
            $error = "";
        }
        if($asJson){
            echo json_encode(array('error'=>$error));
        }
        else{
            return $error;
        }
    }
    public function get_tbl_status($asJson=true){
        $tbls = $this->get_tables(false);
        if($asJson)
            echo json_encode($tbls);
        else
            return $tbl;
    } 

    function get_table_orders($asJson=true,$tbl_id=null){
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $args = array();
        $args["trans_sales.trans_ref  IS NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive"] = 0;
        $args["trans_sales.table_id"] = $tbl_id;
        $orders = $this->cashier_model->get_trans_sales(null,$args);
        $time = $this->site_model->get_db_now();
        $this->make->sDivRow();
        $ord=array();
        foreach ($orders as $res) {
            $status = "open";
            if($res->trans_ref != "")
                $status = "settled";
            $ord[$res->sales_id] = array(
                "type"=>$res->type,
                "status"=>$status,
                "user_id"=>$res->user_id,
                "name"=>$res->username,
                "terminal_id"=>$res->terminal_id,
                "terminal_name"=>$res->terminal_name,
                "shift_id"=>$res->shift_id,
                "datetime"=>$res->datetime,
                "amount"=>$res->total_amount
            );
            $this->make->sDivCol(4,'left',0);
                    $this->make->sDiv(array('class'=>'order-btn','id'=>'order-btn-'.$res->sales_id,'ref'=>$res->sales_id));
                        if($res->trans_ref == null){
                            $this->make->sBox('default',array('class'=>'box-solid'));
                        }else{
                            $this->make->sBox('default',array('class'=>'box-solid bg-green'));
                        }
                            $this->make->sBoxBody();
                                $this->make->sDivRow();
                                    $this->make->sDivCol(6);
                                        $this->make->sDiv(array('style'=>'margin-left:20px;'));
                                            $this->make->H(5,strtoupper($res->type)." #".$res->sales_id,array("style"=>'font-weight:700;'));
                                            if($res->trans_ref == null){
                                                $this->make->H(5,strtoupper($res->username),array("style"=>'color:#888'));
                                                $this->make->H(5,strtoupper($res->terminal_name),array("style"=>'color:#888'));
                                            }else{
                                                $this->make->H(5,strtoupper($res->username),array("style"=>'color:#fff'));
                                                $this->make->H(5,strtoupper($res->terminal_name),array("style"=>'color:#fff'));
                                            }
                                            $this->make->H(5,tagWord(strtoupper(ago($res->datetime,$time) ) ) );
                                        $this->make->eDiv();
                                    $this->make->eDivCol();
                                    $this->make->sDivCol(6);
                                        $this->make->H(4,'Order Total',array('class'=>'text-center'));
                                        $this->make->H(3,num($res->total_amount),array('class'=>'text-center'));
                                    $this->make->eDivCol();
                                $this->make->eDivRow();
                                $this->make->sDivRow();
                                    if(ORDER_SLIP_PRINTER_SETUP){
                                        $this->make->sDivCol(4);
                                            $this->make->button(fa('fa-print fa-lg fa-fw').' RePrint Order Slip',array('id'=>'print-os-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-teal'));
                                        $this->make->eDivCol();
                                        if(ORDERING_STATION){
                                            if(LOCAVORE){
                                                $this->make->sDivCol(4);
                                                    $this->make->button(fa('fa-exchange fa-lg fa-fw').' Transfer Table',array('id'=>'transfer-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-orange disabled'));
                                                $this->make->eDivCol();
                                            }else{
                                                $this->make->sDivCol(4);
                                                    $this->make->button(fa('fa-exchange fa-lg fa-fw').' Transfer Table',array('id'=>'transfer-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-orange','locavore'=>'no'));
                                                $this->make->eDivCol();
                                            }
                                        }else{
                                            if(LOCAVORE){
                                                $this->make->sDivCol(4);
                                                    $this->make->button(fa('fa-exchange fa-lg fa-fw').' Transfer Table',array('id'=>'transfer-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-orange','locavore'=>'yes'));
                                                $this->make->eDivCol();
                                            }else{
                                                $this->make->sDivCol(4);
                                                    $this->make->button(fa('fa-exchange fa-lg fa-fw').' Transfer Table',array('id'=>'transfer-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-orange','locavore'=>'no'));
                                                $this->make->eDivCol();
                                            }
                                        }
                                        $this->make->sDivCol(4);
                                            $this->make->button(fa('fa-print fa-lg fa-fw').' Print Billing',array('id'=>'print-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-green'));
                                        $this->make->eDivCol();
                                    }
                                    else{
                                        if(ORDERING_STATION){
                                            if(LOCAVORE){
                                                $this->make->sDivCol(6);
                                                    $this->make->button(fa('fa-exchange fa-lg fa-fw').' Transfer Table',array('id'=>'transfer-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-orange disabled'));
                                                $this->make->eDivCol();
                                            }else{
                                                $this->make->sDivCol(6);
                                                    $this->make->button(fa('fa-exchange fa-lg fa-fw').' Transfer Table',array('id'=>'transfer-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-orange','locavore'=>'no'));
                                                $this->make->eDivCol();
                                            }
                                        }else{
                                            if(LOCAVORE){
                                                $this->make->sDivCol(6);
                                                    $this->make->button(fa('fa-exchange fa-lg fa-fw').' Transfer Table',array('id'=>'transfer-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-orange','locavore'=>'yes'));
                                                $this->make->eDivCol();
                                            }else{
                                                $this->make->sDivCol(6);
                                                    $this->make->button(fa('fa-exchange fa-lg fa-fw').' Transfer Table',array('id'=>'transfer-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-orange','locavore'=>'no'));
                                                $this->make->eDivCol();
                                            }
                                        }
                                        $this->make->sDivCol(6);
                                            $this->make->button(fa('fa-print fa-lg fa-fw').' Print Billing',array('id'=>'print-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-green'));
                                        $this->make->eDivCol();
                                    }
                                $this->make->eDivRow();
                            $this->make->eBoxBody();
                        $this->make->eBox();
                    $this->make->eDiv();
            $this->make->eDivCol();
        }
        $this->make->eDivRow();
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$ord));
    }
    public function check_trans_settled($id=null,$tbl_id=null){
    	$this->load->model('site/site_model');
        $error = 0;
        // echo $id;die();
        $where = array('sales_id'=>$id);
        $rest = $this->site_model->get_details($where,'trans_sales');
        // echo "<pre>",print_r($rest),"</pre>";die();
        if($rest[0]->paid == 1){
            $error = 1;          
        }else{
        	$trans_sales_id = $this->Pos_app->get_table_trans($id);
        	$error = 0;
        	// echo "<pre>",print_r($trans_sales_id),"</pre>";die();
        	// $res = $this->site_model->get_tbl('table_activity',array('tbl_id'=>$tbl_id));
	        
        }
        
        echo json_encode(array('table_trans'=>$trans_sales_id,'error'=>$error));
    }

    function print_billing($sales_id)
	{
		include_once (realpath(dirname(__FILE__) . '/..') . "\dine\cashier.php");

        $this->load->model('dine/clock_model');
		$cashier = New Cashier();

		$cashier->print_sales_receipt($sales_id,false);
		// $data = array();
		// $data['id']= $id; 
		redirect('/app/recall', 'refresh');
		// echo $id;die();
		// $this->load->view('pos/parts/head');	
		// $this->load->view("pos/single_recall",$data);	
		// $this->load->view('pos/parts/foot');	

	}

	public function check_tbl_with_trans($tbl_id=null,$trans_id=null,$asJson=true){
        // echo 'dasddqweqw';
        $error = "";
        // echo $res;die();
        $res = $this->site_model->get_tbl('table_activity',array('tbl_id'=>$tbl_id));
        if(count($res)>0){
            $error = "TERMINAL ".$res[0]->pc_id." is currently editing this table";            
        }
        else{
        	// echo 'das';
        	// $trans_sales_id = $this->Pos_app->get_table_trans($trans_id);
            $error = "";
        }
        if($asJson){
            echo json_encode(array('error'=>$error));
        }
        else{
            return $error;
        }
    }   

    public function get_qty(){
    	if(session_id() == '') {
				session_start();
		}
		// unset($_SESSION['cart_items']);die();
		$post = $this->input->post();
		$qty = 1;
		$remarks = ""; 
		// echo "<pre>",print_r($post),"</pre>";die();
    	// echo 'dasda';

		if(!empty($post['ref'])){
			// $br = (isset($item['bref']) ? $item['bref'] : '');
			// $brn = (isset($item['brefname']) ? $item['brefname'] : '');
			if(isset($_SESSION['cart_items'][$post['ref']]) ){
				$qty = $_SESSION['cart_items'][$post['ref']]['qty'] ;
				$remarks = $_SESSION['cart_items'][$post['ref']]['remarks'] ;

				// foreach($post['item_list'] as $item){

				// 	$_SESSION['cart_items'][$item['ref']]['qty'] = $item['qty']; 
				// 	$_SESSION['cart_items'][$item['ref']]['modifiers'] = $item['bref']; 
				// 	$_SESSION['cart_items'][$item['ref']]['modname'] = $item['brefname']; 
				// }
			}
		}
		// echo "<pre>",print_r($_SESSION),"</pre>";die();
		
		echo json_encode(array('qty'=>$qty,'remarks'=>$remarks));
    }

}
?>