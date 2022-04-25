<?php
/**
add and update functions were separated from each of the tables to migrate for easier maintenance and understanding. @Justin 11/2017
**/

class Master_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->library('Db_manager');
		$this->main_db = $this->db_manager->get_connection(MIGRATED_MAIN_DB);
		$this->migrate_db = $this->db_manager->get_connection(MIGRATED_MASTER_DB);
		$this->db = $this->db_manager->get_connection('default');

		// $this->app_db = $this->db_manager->get_connection(APP_DB);
		// echo "<pre>",print_r($this->migrate_db->select('*')->where('menu_id','1')->get('menus')->result()),"</pre>";
		// echo MIGRATED_MASTER_DB; 
		// var_dump($this->migrate_db);die();
	}
	public function add_tbl($table_name,$items,$set=array(),$db="migrate_db"){
		if(!empty($set)){
			foreach ($set as $key => $val) {
				$this->$db->set($key, $val, FALSE);
			}
		}
		$this->$db->insert($table_name,$items);
		return $this->$db->insert_id();
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

	public function update_tbl_batch($table_name,$items,$key,$db="migrate_db"){
		if($items){
			// $branch_code = BRANCH_CODE;
			if($table_name == "trans_sales" || $table_name == "trans_sales_charges" || $table_name == "trans_sales_discounts" || $table_name == "trans_sales_items" || $table_name == "trans_sales_local_tax" || $table_name == "trans_sales_loyalty_points" || $table_name == "trans_sales_menu_modifiers" || $table_name == "trans_sales_menus" || $table_name == "trans_sales_no_tax" || $table_name == "trans_sales_payments" || $table_name == "trans_sales_tax" || $table_name == "trans_sales_zero_rated" || $table_name == "trans_sales_menu_submodifiers"){
				$terminal_id = TERMINAL_ID;
				// $this->$db->where('branch_code',BRANCH_CODE);
				$this->$db->where('pos_id',TERMINAL_ID);
			}
			$this->$db->update_batch($table_name,$items,$key);
			
			return $this->$db->affected_rows();
		}
		
	}


	public function update_trans_tbl_batch($table_name,$items,$key,$db="migrate_db"){
		// echo $table_name;
		// echo "<pre>",print_r($items),"</pre>";
		// echo "<br>";
		// echo $key;
		// die();
		// $this->$db->where('BRANCH_CODE',$name);
		$branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		if( !empty($branch_code) && !empty($terminal_id)) {
			$this->$db->where('branch_code',BRANCH_CODE);
			$this->$db->where('terminal_id',TERMINAL_ID);
			$this->$db->update_batch($table_name,$items,$key);
			
		}
		// echo $this->$db->last_query();die();
		return $this->$db->affected_rows();
	}

	public function delete_tbl_batch($table_name=null,$args=null,$db="migrate_db"){
		if(!empty($args)){
			foreach ($args as $col => $val) {
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
		}
		$this->$db->delete($table_name);
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

	public function delete_tbl($table_name=null,$args=null,$db="migrate_db"){
		if(!empty($args)){
			foreach ($args as $col => $val) {
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
		}
		$this->$db->delete($table_name);
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





	// TO MASTER DB

	// migrate add trans sales  @Justin
	public function add_trans_sales($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		// if(!empty($trans_id)){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
			$type="add";
			$table_name = 'trans_sales';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('`sales_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id`,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed`, `sync_id`,`terminal_id`,`branch_code`,`pos_id`')->get_where($table_name,array('master_id' => NULL,'pos_id' => TERMINAL_ID))->result();
				$trans_id_raw = $this->object_flat($trans_raw,"sales_id");
				$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('`sales_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed` , `sync_id`,`terminal_id`,`branch_code`,`pos_id`')->where('master_id',$migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select('sales_id')->where('master_id',$migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();

				}else{
					$trans_raw = $this->main_db->select('`sales_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed` , `sync_id`,`terminal_id`,`branch_code`,`pos_id`')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->object_flat($trans_raw,"sales_id");
					$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));

					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
					// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"sales_id");
					$hq_trans_raw = $this->migrate_db->select('`sales_id`')->where_in('sales_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"sales_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
		// }

	}


	// migrate add trans sales_charges  @Justin
	public function add_trans_sales_charges($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
			$type = 'add';
		    $table_name = 'trans_sales_charges';
		    $selected_field = 'sales_charge_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id' => TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_charge_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"sales_charge_id");
					$hq_trans_raw = $this->migrate_db->select('`sales_charge_id`')->where_in('sales_charge_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"sales_charge_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_charge_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


// migrate add trans sales_discounts  @Justin
	public function add_trans_sales_discounts($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_discounts';
		    $selected_field = 'sales_disc_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL, 'pos_id' => TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
				
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_disc_id','main_db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"sales_disc_id");
					$hq_trans_raw = $this->migrate_db->select('`sales_disc_id`')->where_in('sales_disc_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"sales_disc_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_disc_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	

		// migrate add trans sales_items  @Justin
	public function add_trans_sales_items($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_items';
		    $selected_field = 'sales_item_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL, 'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id',$migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_item_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"sales_item_id");
					$hq_trans_raw = $this->migrate_db->select('`sales_item_id`')->where_in('sales_item_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"sales_item_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_item_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

		// migrate add trans sales local_tax  @Justin
	public function add_trans_sales_local_tax($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_local_tax';
		    $selected_field = 'sales_local_tax_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL, 'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id',$migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_local_tax_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"sales_local_tax_id");
					$hq_trans_raw = $this->migrate_db->select('`sales_local_tax_id`')->where_in('sales_local_tax_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"sales_local_tax_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_local_tax_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	// migrate add trans sales loyalty points  @Justin
	public function add_trans_sales_loyalty_points($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_loyalty_points';
		    $selected_field = 'loyalty_point_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id',$migration_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'loyalty_point_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"loyalty_point_id");
					$hq_trans_raw = $this->migrate_db->select('`loyalty_point_id`')->where_in('loyalty_point_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"loyalty_point_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'loyalty_point_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

		// migrate add trans_sales_menu_modifiers  @Justin
	public function add_trans_sales_menu_modifiers($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_menu_modifiers';
		    $selected_field = 'sales_mod_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->or_where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					

						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_mod_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"sales_mod_id");
					$hq_trans_raw = $this->migrate_db->select('`sales_mod_id`')->where_in('sales_mod_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"sales_mod_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_mod_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	public function add_trans_sales_menu_submodifiers($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_menu_submodifiers';
		    $selected_field = 'sales_submod_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->or_where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					

						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_submod_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"sales_submod_id");
					$hq_trans_raw = $this->migrate_db->select('`sales_submod_id`')->where_in('sales_submod_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"sales_submod_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_submod_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	// migrate add trans_sales_menu_modifiers  @Justin
	public function add_trans_sales_menus($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_menus';
		    $selected_field = 'sales_menu_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				if(count($trans_id_raw) > 5000) // records that exceeds 20000 clogs the migration , temporary solution @justinx02022018
						$json_encode  = "";// json_encode($trans_id_raw);
				else
					$json_encode =  json_encode($trans_id_raw);
						
			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					if(count($trans_id_raw) > 5000) // records that exceeds 20000 clogs the migration , temporary solution @justinx02022018
						$json_encode  = "";// json_encode($trans_id_raw);
					else
					$json_encode =  json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_menu_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"sales_menu_id");
					$hq_trans_raw = $this->migrate_db->select('`sales_menu_id`')->where_in('sales_menu_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"sales_menu_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_menu_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

		// migrate add trans_sales_no_tax  @Justin
	public function add_trans_sales_no_tax($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_no_tax';
		    $selected_field = 'sales_no_tax_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_no_tax_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"sales_no_tax_id");
					$hq_trans_raw = $this->migrate_db->select('`sales_no_tax_id`')->where_in('sales_no_tax_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"sales_no_tax_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_no_tax_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate add trans_sales_payments  @Justin
	public function add_trans_sales_payments($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_payments';
		    $selected_field = 'payment_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id
						$this->main_db->trans_start();
							$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
							$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
						
							// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							// $this->update_tbl_batch($table_name,$update_trans_wrapped,'payment_id','main_db');
							// if(empty($migrate_id)){
							// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
							// }else{
							// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
							// }
						$this->main_db->trans_complete();
					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"payment_id");
					$hq_trans_raw = $this->migrate_db->select('`payment_id`')->where_in('payment_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"payment_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'payment_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate add trans_sales_tax  @Justin
	public function add_trans_sales_tax($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_tax';
		    $selected_field = 'sales_tax_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
					// $this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_tax_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"sales_tax_id");
					$hq_trans_raw = $this->migrate_db->select('`sales_tax_id`')->where_in('sales_tax_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"sales_tax_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_tax_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate add trans_sales_zero_rated  @Justin
	public function add_trans_sales_zero_rated($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_zero_rated';
		    $selected_field = 'sales_zero_rated_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id',$migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
					// $this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_zero_rated_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"sales_zero_rated_id");
					$hq_trans_raw = $this->migrate_db->select('`sales_zero_rated_id`')->where_in('sales_zero_rated_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"sales_zero_rated_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_zero_rated_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
	}


	// migrate add trans_sales_zero_rated  @Justin
	public function add_trans_refs($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_refs';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL,'pos_id'=>TERMINAL_ID))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id',$migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					

						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					// echo $this->migrate_db->last_query();
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id ),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
	}


	// migrate update trans_sales  @Justin
	public function update_trans_sales($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales';
		    $selected_field = '`sales_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed` , `sync_id`,`terminal_id`,`branch_code`,`pos_id`';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select($selected_field)->where('master_id',$migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id',$migrate_local_id)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();

			}else{
				$trans_raw = $this->main_db->select($selected_field)->where('`update_date` > ',$update_date)->where('`master_id` is not null',NULL,false)->where('pos_id' ,TERMINAL_ID)->get($table_name)->result();
				$trans_id_raw = $this->object_flat($trans_raw,"sales_id");
				// $trans_id_raw = $this->main_db->select($selected_field)->where('`update_date` > ',$update_date)->where('`master_id` is not null',NULL,false)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
				// echo "<pre>", $this->main_db->last_query(),"</pre>";die();		
			}				
			
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						// $this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'`master_id` is not null'  => NULL ),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";//die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				 	$this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sales_id');
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();
				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
	}

	// migrate update trans_sales_charges  -  @Justin
	public function update_trans_sales_charges($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_charges';
		    $selected_field = 'trans_sales_charges.sales_charge_id, trans_sales_charges.sales_id';
		    $based_delete_id = 'sales_id';
		    $based_delete_id_select = 'trans_sales.sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

				
			
			if($automated){
				$trans_raw = $this->main_db->select('trans_sales_charges.*')->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id and trans_sales.pos_id = trans_sales_charges.pos_id','left')->where('trans_sales_charges.master_id',$migrate_local_id)->where('trans_sales_charges.sales_id is not null',null,false)->where('trans_sales_charges.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id','left')->where('trans_sales_charges.master_id', $migrate_local_id)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id','left')->where('trans_sales_charges.master_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));

			}else{
				$trans_raw_bd = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id and trans_sales.pos_id = trans_sales_charges.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_charges.master_id IS NULL)',NULL, false)->where('trans_sales_charges.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_raw = $this->main_db->select('trans_sales_charges.*')->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id and trans_sales.pos_id = trans_sales_charges.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_charges.master_id IS NULL)',NULL, false)->where('trans_sales_charges.sales_id is not null',null,false)->where('trans_sales_charges.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat(array_merge($trans_raw_bd, $trans_raw),$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->group_by($based_delete_id)->get('trans_sales')->result();
				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl

			// 	echo $this->main_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";
			// 	echo "<pre>",print_r($trans_based_id_raw),"</pre>";die();


			if(!empty($trans_based_id_raw) && isset($trans_based_id_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// // $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						if(!empty($add_trans_header)){
							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



					// echo "<pre> trans based id raw:"  , print_r($add_trans_header),"</pre>"; die();
				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_based_id_raw),"</pre>";die();

				// echo "<pre>based_raw: ",print_r($trans_based_id_raw),"</pre>"."<br>";
				if(!empty($trans_based_id_raw) ){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}
				// echo "<pre>",print_r($delete_args),"</pre>";die();
					$this->delete_trans_misc_batch($table_name,$delete_args);
					// echo $this->migrate_db->last_query()."<br>";
					// delete_trans_misc_batch($table_name=null,$args=null
					if(!empty($add_trans_header)){

				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
					}
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();
				// die();
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{

				if(!empty($trans_based_id_raw) ){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}
				// echo "<pre>",print_r($delete_args),"</pre>";die();
					$this->delete_trans_misc_batch($table_name,$delete_args);
				}

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	// migrate update trans_sales_charges  -  @Justin
	public function update_trans_sales_menus($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_menus';
		    $selected_field = 'trans_sales_menus.sales_menu_id, trans_sales_menus.sales_id';
		    $based_delete_id = 'sales_id';
		    $based_delete_id_select = 'trans_sales.sales_id';

			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('trans_sales_menus.*')->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id and trans_sales.pos_id = trans_sales_menus.pos_id','left')->where('trans_sales_menus.master_id',$migrate_local_id)->where('trans_sales_menus.sales_id is not null',null,false)->where('trans_sales_menus.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id','left')->where('trans_sales_menus.master_id', $migrate_local_id)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id','left')->where('trans_sales_menus.master_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id and trans_sales.pos_id = trans_sales_menus.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_menus.master_id IS NULL)',NULL, false)->where('trans_sales_menus.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();			
				$trans_raw = $this->main_db->select('trans_sales_menus.*')->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id and trans_sales.pos_id = trans_sales_menus.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_menus.master_id IS NULL)',NULL, false)->where('trans_sales_menus.sales_id is not null',null,false)->where('trans_sales_menus.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();			
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat(array_merge($trans_raw_bd,$trans_raw),$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));	
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->group_by($based_delete_id)->get('trans_sales')->result();
				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>trans raw",print_r($trans_raw),"</pre>";

			// echo "<pre>trans id raw",print_r($trans_id_raw),"</pre>";
			// echo "<pre>trans based id raw",print_r($trans_based_id_raw),"</pre>";
			// echo $this->main_db->last_query();//die();
			// echo "<pre>",print_r($trans_raw),"</pre>";//die();


			if(!empty($trans_based_id_raw) && isset($trans_based_id_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						
						if(!empty($add_trans_header)){
							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						}
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
					
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	if(!empty($add_trans_header)){

				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
					}
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
				}

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
	}



	// migrate update trans_sales_discounts  -  @Justin
	public function update_trans_sales_discounts($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_discounts';
		    $selected_field = 'trans_sales_discounts.sales_disc_id, trans_sales_discounts.sales_id';
		    $based_delete_id = 'sales_id';
		    $based_delete_id_select = 'trans_sales.sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('trans_sales_discounts.*')->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id and trans_sales.pos_id = trans_sales_discounts.pos_id','left')->where('trans_sales_discounts.master_id', $migrate_local_id)->where('trans_sales_discounts.sales_id is not null',null,false)->where('trans_sales_discounts.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id','left')->where('trans_sales_discounts.master_id', $migrate_local_id=NULL)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id','left')->where('trans_sales_discounts.master_id', $migrate_local_id=NULL)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id and trans_sales.pos_id = trans_sales_discounts.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL  OR trans_sales_discounts.master_id IS NULL)',NULL, false)->where('trans_sales_discounts.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_raw = $this->main_db->select('trans_sales_discounts.*')->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id and trans_sales.pos_id = trans_sales_discounts.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL  OR trans_sales_discounts.master_id IS NULL)',NULL, false)->where('trans_sales_discounts.sales_id is not null',null,false)->where('trans_sales_discounts.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat(array_merge($trans_raw_bd,$trans_raw),$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));	

				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->group_by($based_delete_id)->get('trans_sales')->result();
				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_based_id_raw) && isset($trans_based_id_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						if(!empty($add_trans_header)){
							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						}
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// // $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
					
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
					}
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
				}

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	// migrate update trans_sales_items  -  @Justin
	public function update_trans_sales_items($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_items';
		    $selected_field = 'trans_sales_items.sales_item_id, trans_sales_items.sales_id';
		    $based_delete_id = 'sales_id';
		    $based_delete_id_select = 'trans_sales.sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('trans_sales_items.*')->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id and trans_sales.pos_id = trans_sales_items.pos_id','left')->where('trans_sales_items.master_id', $migrate_local_id)->where('trans_sales_items.sales_id is not null',null,false)->where('trans_sales_items.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id','left')->where('trans_sales_items.master_id', $migrate_local_id=NULL)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id','left')->where('trans_sales_items.master_id', $migrate_local_id=NULL)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id and trans_sales.pos_id = trans_sales_items.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_items.master_id IS NULL)',NULL, false)->where('trans_sales_items.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_raw = $this->main_db->select('trans_sales_items.*')->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id and trans_sales.pos_id = trans_sales_items.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_items.master_id IS NULL)',NULL, false)->where('trans_sales_items.sales_id is not null',null,false)->where('trans_sales_items.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat(array_merge($trans_raw_bd,$trans_raw),$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));	
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->group_by($based_delete_id)->get('trans_sales')->result();
				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						if(!empty($add_trans_header)){
							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						}
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// // $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);				

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
					
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

						$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
					if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
					}
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

						$this->delete_trans_misc_batch($table_name,$delete_args);
				}

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	// migrate update trans_sales_local_tax  -  @Justin
	public function update_trans_sales_local_tax($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_local_tax';
		    $selected_field = 'sales_local_tax_id, sales_id';
		    $based_delete_id = 'sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->where('trans_sales_local_tax.pos_id' ,TERMINAL_ID)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->where('trans_sales_local_tax.pos_id' ,TERMINAL_ID)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->where('trans_sales_local_tax.pos_id' ,TERMINAL_ID)->get($table_name)->result();
			
				$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->where('trans_sales_local_tax.pos_id' ,TERMINAL_ID)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
			
			// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	// migrate update trans_sales_loyalty_points  -  @Justin
	public function update_trans_sales_loyalty_points($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_loyalty_points';
		    $selected_field = 'trans_sales_loyalty_points.loyalty_point_id, trans_sales_loyalty_points.sales_id';
		    $based_delete_id = 'sales_id';
		    $based_delete_id_select = 'trans_sales.sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('trans_sales_loyalty_points.*')->join($table_name,'trans_sales.sales_id = trans_sales_loyalty_points.sales_id and trans_sales.pos_id = trans_sales_loyalty_points.pos_id','left')->where('trans_sales_loyalty_points.master_id', $migrate_local_id)->where('trans_sales_loyalty_points.sales_id is not null',null,false)->where('trans_sales_loyalty_points.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_loyalty_points.sales_id','left')->where('trans_sales_loyalty_points.master_id', $migrate_local_id=NULL)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_loyalty_points.sales_id','left')->where('trans_sales_loyalty_points.master_id', $migrate_local_id=NULL)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('trans_sales_loyalty_points.*')->join($table_name,'trans_sales.sales_id = trans_sales_loyalty_points.sales_id and trans_sales.pos_id = trans_sales_loyalty_points.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_loyalty_points.master_id IS NULL)',NULL, false)->where('trans_sales_loyalty_points.sales_id is not null',null,false)->where('trans_sales_loyalty_points.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));	
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_loyalty_points.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_loyalty_points.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->group_by($based_delete_id)->get('trans_sales')->result();

				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
					
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
					if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
					}
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate update trans_sales_menu_modifiers  -  @Justin
	public function update_trans_sales_menu_modifiers($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_menu_modifiers';
		    $selected_field = 'trans_sales_menu_modifiers.sales_mod_id, trans_sales_menu_modifiers.sales_id';
		    $based_delete_id = 'sales_id';
		    $based_delete_id_select = 'trans_sales.sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('trans_sales_menu_modifiers.*')->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id and trans_sales.pos_id = trans_sales_menu_modifiers.pos_id','left')->where('trans_sales_menu_modifiers.master_id', $migrate_local_id)->where('trans_sales_menu_modifiers.sales_id is not null',null,false)->where('trans_sales_menu_modifiers.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('trans_sales_menu_modifiers.master_id', $migrate_local_id=NULL)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('trans_sales_menu_modifiers.master_id', $migrate_local_id=NULL)->group_by($based_delete_id)->get('trans_sales')->result();

				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id and trans_sales.pos_id = trans_sales_menu_modifiers.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_menu_modifiers.master_id IS NULL)',NULL, false)->where('trans_sales_menu_modifiers.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_raw = $this->main_db->select('trans_sales_menu_modifiers.*')->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id and trans_sales.pos_id = trans_sales_menu_modifiers.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_menu_modifiers.master_id IS NULL)',NULL, false)->where('trans_sales_menu_modifiers.sales_id is not null',null,false)->where('trans_sales_menu_modifiers.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat(array($trans_raw_bd, $trans_raw) ,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));	
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->group_by($based_delete_id)->get('trans_sales')->result();

				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						if(!empty($add_trans_header)){
							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						}
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// // $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
					
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
					if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
				 	}

					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
				}

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	public function update_trans_sales_menu_submodifiers($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_menu_submodifiers';
		    $selected_field = 'trans_sales_menu_submodifiers.sales_submod_id, trans_sales_menu_submodifiers.sales_id';
		    $based_delete_id = 'sales_id';
		    $based_delete_id_select = 'trans_sales.sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('trans_sales_menu_submodifiers.*')->join($table_name,'trans_sales.sales_id = trans_sales_menu_submodifiers.sales_id and trans_sales.pos_id = trans_sales_menu_submodifiers.pos_id','left')->where('trans_sales_menu_submodifiers.master_id', $migrate_local_id)->where('trans_sales_menu_submodifiers.sales_id is not null',null,false)->where('trans_sales_menu_submodifiers.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('trans_sales_menu_modifiers.master_id', $migrate_local_id=NULL)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('trans_sales_menu_modifiers.master_id', $migrate_local_id=NULL)->group_by($based_delete_id)->get('trans_sales')->result();

				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_submodifiers.sales_id and trans_sales.pos_id = trans_sales_menu_submodifiers.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_menu_submodifiers.master_id IS NULL)',NULL, false)->where('trans_sales_menu_submodifiers.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_raw = $this->main_db->select('trans_sales_menu_submodifiers.*')->join($table_name,'trans_sales.sales_id = trans_sales_menu_submodifiers.sales_id and trans_sales.pos_id = trans_sales_menu_submodifiers.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_menu_submodifiers.master_id IS NULL)',NULL, false)->where('trans_sales_menu_submodifiers.sales_id is not null',null,false)->where('trans_sales_menu_submodifiers.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat(array($trans_raw_bd, $trans_raw) ,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));	
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->group_by($based_delete_id)->get('trans_sales')->result();

				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						if(!empty($add_trans_header)){
							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						}
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// // $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
					
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
					if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
				 	}

					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
				}

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate update trans_sales_no_tax  -  @Justin
	public function update_trans_sales_no_tax($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_no_tax';
		    $selected_field = 'trans_sales_no_tax.sales_no_tax_id, trans_sales_no_tax.sales_id';
		    $based_delete_id = 'sales_id';
		     $based_delete_id_select = 'trans_sales.sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('trans_sales_no_tax.*')->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id and trans_sales.pos_id = trans_sales_no_tax.pos_id','left')->where('trans_sales_no_tax.master_id', $migrate_local_id)->where('trans_sales_no_tax.sales_id is not null',null,false)->where('trans_sales_no_tax.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
		      	$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));

				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id','left')->where('trans_sales_no_tax.master_id', $migrate_local_id=NULL)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id','left')->where('trans_sales_no_tax.master_id', $migrate_local_id=NULL)->group_by($based_delete_id)->get('trans_sales')->result();

				// $trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				// $trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('trans_sales_no_tax.*')->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id and trans_sales.pos_id = trans_sales_no_tax.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_no_tax.master_id IS NULL)',NULL, false)->where('trans_sales_no_tax.sales_id is not null',null,false)->where('trans_sales_no_tax.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));	
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->group_by($based_delete_id)->get('trans_sales')->result();

				// // $trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
				// // $trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				// // $trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						if(!empty($add_trans_header)){
							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						}
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// // $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
					
			// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
					if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
				 	}
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate update trans_sales_payments  -  @Justin
	public function update_trans_sales_payments($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_payments';
		    $selected_field = 'trans_sales_payments.payment_id, trans_sales_payments.sales_id';
		    $based_delete_id = 'sales_id';
		    $based_delete_id_select = 'trans_sales.sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('trans_sales_payments.*')->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id and trans_sales.pos_id = trans_sales_payments.pos_id','left')->where('trans_sales_payments.master_id', $migrate_local_id)->where('trans_sales_payments.sales_id is not null',null,false)->where('trans_sales_payments.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id','left')->where('trans_sales_payments.master_id', $migrate_local_id=NULL)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id','left')->where('trans_sales_payments.master_id', $migrate_local_id=NULL)->group_by($based_delete_id)->get('trans_sales')->result();

				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('trans_sales_payments.*')->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id and trans_sales.pos_id = trans_sales_payments.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_payments.master_id IS NULL)',NULL, false)->where('trans_sales_payments.sales_id is not null',null,false)->where('trans_sales_payments.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));	
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->group_by($based_delete_id)->get('trans_sales')->result();

				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						if(!empty($add_trans_header)){
							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						}
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db');
						// // $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
					
			// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				    if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
					}
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

// migrate update trans_sales_tax  -  @Justin
	public function update_trans_sales_tax($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_tax';
		    $selected_field = 'trans_sales_tax.sales_tax_id, trans_sales_tax.sales_id';
		    $based_delete_id = 'sales_id';
		     $based_delete_id_select = 'trans_sales.sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('trans_sales_tax.*')->join($table_name,'trans_sales.sales_id = trans_sales_tax.sales_id and trans_sales.pos_id = trans_sales_tax.pos_id','left')->where('trans_sales_tax.master_id', $migrate_local_id)->where('trans_sales_tax.sales_id is not null',null,false)->where('trans_sales_tax.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_tax.sales_id','left')->where('trans_sales_tax.master_id', $migrate_local_id=NULL)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_tax.sales_id','left')->where('trans_sales_tax.master_id', $migrate_local_id=NULL)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_tax.sales_id and trans_sales.pos_id = trans_sales_tax.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_tax.master_id IS NULL)',NULL, false)->where('trans_sales_tax.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_raw = $this->main_db->select('trans_sales_tax.*')->join($table_name,'trans_sales.sales_id = trans_sales_tax.sales_id and trans_sales.pos_id = trans_sales_tax.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_tax.master_id IS NULL)',NULL, false)->where('trans_sales_tax.sales_id is not null',null,false)->where('trans_sales_tax.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat(array_merge($trans_raw_bd, $trans_raw),$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));	
				// $trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 	// 	$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_tax.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_tax.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->group_by($based_delete_id)->get('trans_sales')->result();
				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						if(!empty($add_trans_header)){
							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						}
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// // $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
					
			// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
					if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
				 	}
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
				}

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


// migrate update trans_sales_tax  -  @Justin
	public function update_trans_sales_zero_rated($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_zero_rated';
		    $selected_field = 'trans_sales_zero_rated.sales_zero_rated_id, trans_sales_zero_rated.sales_id';
		    $based_delete_id = 'sales_id';
		    $based_delete_id_select = 'trans_sales.sales_id';

			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('trans_sales_zero_rated.*')->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id and trans_sales.pos_id = trans_sales_zero_rated.pos_id','left')->where('trans_sales_zero_rated.master_id', $migrate_local_id)->where('trans_sales_zero_rated.sales_id is not null',null,false)->where('trans_sales_zero_rated.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id','left')->where('trans_sales_zero_rated.master_id', $migrate_local_id=NULL)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id','left')->where('trans_sales_zero_rated.master_id', $migrate_local_id=NULL)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id and trans_sales.pos_id = trans_sales_zero_rated.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_zero_rated.master_id IS NULL)',NULL, false)->where('trans_sales_zero_rated.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_raw = $this->main_db->select('trans_sales_zero_rated.*')->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id and trans_sales.pos_id = trans_sales_zero_rated.pos_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL OR trans_sales_zero_rated.master_id IS NULL)',NULL, false)->where('trans_sales_zero_rated.sales_id is not null',null,false)->where('trans_sales_zero_rated.pos_id' ,TERMINAL_ID)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat(array_merge($trans_raw_bd , $trans_raw),$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));	
				// $trans_id_raw = $this->main_db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id','left')->where('(trans_sales.update_date > "'.$update_date.'" OR trans_sales.update_date IS NULL)',NULL, false)->group_by($based_delete_id)->get('trans_sales')->result();

				// $trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
				// $trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						if(!empty($add_trans_header)){
							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');
						}

						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
					
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
					if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
				 	}
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				}

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
				
	}



// migrate update trans_voids  -  @Justin
	public function update_trans_voids($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_voids';
		    $selected_field = 'sales_zero_rated_id, sales_id';
		    $based_delete_id = 'sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->where('trans_voids.pos_id' ,TERMINAL_ID)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			
				$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
					
					// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate update trans_sales_local_tax  -  @Justin
	public function update_trans_receiving_menu($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_receiving_menu';
		    $selected_field = 'receiving_id';
		    $based_delete_id = 'receiving_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`update_date` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);
		 		$json_encode  = json_encode(array('sales_id'=>$this->array_flat($trans_raw,"sales_id")));
				// $trans_id_raw = $this->main_db->select($selected_field)->where('`update_date` > ',$update_date)->get($table_name)->result();
				// $trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`update_date` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','main_db');

						// $this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
			
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	public function update_trans_receiving_menu_details($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_receiving_menu_details';
		    $selected_field = 'receiving_detail_id';
		    $based_delete_id = 'receiving_detail_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			
				$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
			
			// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	public function update_menu_moves($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'menu_moves';
		    $selected_field = 'move_id';
		    $based_delete_id = 'move_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			
				$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
			
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// download changes in menu  -  @Justin
	public function download_menus($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menus';
		    $selected_field = 'menu_id,menu_code,menu_barcode,menu_name,menu_short_desc,
		    					menu_cat_id,menu_sub_cat_id, menu_sub_id ,menu_sched_id,cost,reg_date, update_date,no_tax,miaa_cat,
		    					free,inactive,costing,brand';
		    $based_field = 'branch_code,menu_code';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();

			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_menus();
				//	// $this->upload_menu_subcategories();
				// // 	$this->upload_menu_modifiers();
					// $this->upload_modifier_group_details();
				// // 	$this->upload_menu_categories();
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){
						// echo "asd";die();
							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}
				// echo "<pre>",print_r($trans_raw),"</pre>";

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in menu  -  @Justin
	public function download_update_menus($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menus';
		    $selected_field = 'menu_id,menu_code,menu_barcode,menu_name,menu_short_desc,
		    					menu_cat_id,menu_sub_cat_id, menu_sub_id ,menu_sched_id,cost,reg_date, update_date,no_tax,miaa_cat,
		    					free,inactive,costing,brand';
		    $based_field = 'menu_id';
		    $update_based_field = 'menu_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			// echo "asd";die();
			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					// $trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// $trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$trans_raw = $this->migrate_db->select($selected_field)->where('`date_effective` = ', 'CURDATE()',false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`date_effective` = ','CURDATE()',false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();die();

			// echo "<pre>",print_r($trans_raw),"</pre>";die();
			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'" ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					// $migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";die();
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				// echo "";die();
			// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped,$update_based_field,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default,$update_based_field,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

		// download changes in items  -  @Justin
	public function download_update_items($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'items';
		    $selected_field = 'item_id,barcode,code,name,desc,
		    					cat_id,subcat_id,supplier_id,uom,cost,type,reg_date, update_date,
		    					no_per_pack,  no_per_pack_uom , no_per_case,  reorder_qty, 	max_qty, memo,
		    					inactive';
		    $based_field = 'item_id';
		    $update_based_field = 'item_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{


				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`date_effective` = ', 'CURDATE()',false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`date_effective` = ', 'CURDATE()',false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped,$update_based_field,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default,$update_based_field,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download items additional  -  @Justin
	public function download_items($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'items';
		    $selected_field = 'item_id,barcode,code,name,desc,
		    					cat_id,subcat_id,supplier_id,uom,cost,type,reg_date, update_date,
		    					no_per_pack,  no_per_pack_uom , no_per_case,  reorder_qty, 	max_qty, memo,
		    					inactive';
		    $based_field = 'branch_code,code';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();

			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_items();
					// $this->upload_categories();
					// $this->upload_subcategories();
					// $this->upload_modifiers();
					// $this->upload_modifier_group_details();
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ',NULL,false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null  ',NULL,false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
					
						// if(!empty($update_date)){

						$this->update_tbl($table_name,array('master_id' => NULL  ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);
				// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->main_db->last_query()."<br>";
				// echo $this->db->last_query();die();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// upload menus from main to master  @Justin
	public function upload_items($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'items';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'item_id';
		    $type = 'upload';

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}


	// upload menu modifiers from main to master  @Justin
	public function upload_modifiers($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'modifiers';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'mod_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}
			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}
	public function upload_modifier_prices($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'modifier_prices';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'mod_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}
			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}


	// upload menu categories from main to master  @Justin
	public function upload_categories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'categories';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'cat_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	// upload menu subcategories from main to master  @Justin
	public function upload_subcategories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'subcategories';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'sub_cat_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	// upload menus from main to master  @Justin
	public function upload_menus($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'menus';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'menu_id';
		    $type = 'upload';

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}


	// upload menu modifiers from main to master  @Justin
	public function upload_menu_modifiers($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'menu_modifiers';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'id,menu_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}
			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}


	// upload menu categories from main to master  @Justin
	public function upload_menu_categories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'menu_categories';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'menu_cat_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	// upload menu subcategories from main to master  @Justin
	public function upload_menu_subcategories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'menu_subcategories';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'menu_sub_cat_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_id,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	// upload menu subcategory from main to master  @Justin
	public function upload_menu_subcategory($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'menu_subcategory';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'menu_sub_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_id,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	// upload menu schedules from main to master  @Justin
	public function upload_menu_schedules($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'menu_schedules';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'menu_sched_id';
		    $type = 'upload';

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	// upload menu recipe from main to master  @Justin
	public function upload_menu_recipe($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'menu_recipe';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'recipe_id';
		    $type = 'upload';

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}


	// upload modifier recipe from main to master  @Justin
	public function upload_modifier_recipe($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'modifier_recipe';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'mod_recipe_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}
			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}
	public function upload_menu_prices($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'menu_prices';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'menu_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}
			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}
	public function upload_modifier_sub($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'modifier_sub';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'mod_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}
			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}
	public function upload_transaction_types($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'transaction_types';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'trans_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}
			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}
	public function upload_modifier_sub_prices($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'modifier_sub_prices';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'mod_sub_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}
			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	// upload modifier groups from main to master  @Justin
	public function upload_modifier_groups($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'modifier_groups';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'mod_group_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}
			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	// upload modifier group details from main to master  @Justin
	public function upload_modifier_group_details($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'modifier_group_details';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'id,mod_group_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}
			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	public function upload_receipt_discounts($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'receipt_discounts';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'disc_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_id,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	public function upload_charges($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'charges';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'charge_id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_id,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	public function upload_brands($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'brands';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'id';
		    $type = 'upload';
			$check_if_menu_exists = $this->migrate_db->select('*')->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_id,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}




		// migrate add tradns_sales_no_tax  @Justin
	public function add_users($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'users';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
					}
		 // echo $this->main_db->last_query();
			// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate add coupons  @Justin
	public function add_coupons($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'coupons';
		    $selected_field = 'coupon_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	public function add_receipt_discounts($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'receipt_discounts';
		    $selected_field = 'disc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
		 // echo $this->main_db->last_query();
			// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	public function add_tables($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'tables';
		    $selected_field = 'tbl_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
		 // echo $this->main_db->last_query();
			// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	public function add_trans_receiving_menu($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_receiving_menu';
		    $selected_field = 'receiving_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
		 // echo $this->main_db->last_query();
			// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	public function add_trans_receiving_menu_details($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'trans_receiving_menu_details';
		    $selected_field = 'receiving_detail_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	public function add_menu_moves($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'menu_moves';
		    $selected_field = 'move_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
		 // echo $this->main_db->last_query();
			// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}



	// download changes in menu  -  @Justin
	public function migrate_branch_details($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'branch_details';
		    $selected_field = 'res_id, branch_code, branch_name, branch_desc, contact_no, delivery_no, address,
		     base_location, currency, image, inactive, tin, machine_no, bir , permit_no, serial, email, website,
		      store_open, store_close, accrdn, rec_footer';
		    $based_field = 'branch_code';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "migrate";

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_branch_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			 // echo count($check_if_menu_exists);die();
			if(count($check_if_branch_exists) <= 0){
				
		
				// if(empty($migration_id)){

					$trans_raw = $this->main_db->select($selected_field)->get_where($table_name)->result();
					// $trans_id_raw = $this->main_db->select($based_field)->get_where($table_name)->result();
					$json_encode  = json_encode($trans_raw);

				// }else{

				// 	if($automated){
				// 		$trans_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				// 	}else{
				// 		$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
				// 		$json_encode  = json_encode($trans_raw);
						
				// 	}				
				// }

				// echo $this->migrate_db->last_query();
				// echo "<pre>",print_r($trans_raw),"</pre>";die();


				if(!empty($trans_raw) && isset($trans_raw)){

					$add_trans_header = $trans_raw; // get the record
				
					$is_automated = 0;
					$record_count = count($trans_raw);

					// if not automated add to logs else don't add since we already have migration_id
					if(!$automated){
						$this->migrate_db->trans_start(); // start update the local record with migration_id

							$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
							$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
						
						
							$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'migrate_db');


						$this->migrate_db->trans_complete();
						
					}else{
						$migration_sync_id = $migration_id;
                   
						$is_automated = 1;
					}

					$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

						$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_id,'branch_code'=> BRANCH_CODE) , true,false);
					// echo "<pre>",print_r($trans_raw),"</pre>";
					// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

						$this->add_tbl_batch($table_name,$add_trans_wrapped);
					
						$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');

					$this->migrate_db->trans_complete();

					// var_dump($this->migrate_db->trans_status());
					if($this->migrate_db->trans_status()){
						return true;
					}else{
						return false;
					}
				
					
				}else{
					if($automated){
						$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
						$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
					}
					return false;
				}
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	/**add item moves 08012018 - @Justin**/
	public function add_item_moves($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'item_moves';
		    $selected_field = 'move_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	/**Update item moves - 08012018**/
	public function update_item_moves($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'item_moves';
		    $selected_field = 'move_id';
		    $based_delete_id = 'move_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			
				$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
			
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	/**add reasons 08072018 - @Justin**/
	public function add_reasons($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'reasons';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
				  	
				   	    $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	/**Update reasons - 08072018**/
	public function update_reasons($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'reasons';
		    $selected_field = 'id';
		    $based_delete_id = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			
				$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
			
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	// download changes in item categories  -  @Justin
	public function download_categories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'categories';
		    $selected_field = 'code, name, image, inactive';
		    $based_field = 'branch_code,code';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();


			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_categories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
		
			}

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();
					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					
					// $this->db->trans_complete();
				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in categories update  -  @Justin
	public function download_update_categories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'categories';
		    $selected_field = 'cat_id , code, name, image, inactive';
		    $based_field = 'branch_code,cat_id';
		    $update_based_field = 'cat_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}


	// download additional in categories  -  @Justin
	public function download_menu_categories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menu_categories';
		    $selected_field = 'menu_cat_id, menu_cat_name, menu_sched_id, inactive, arrangement, reg_date, brand, unli';
		    $based_field = 'branch_code, menu_cat_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();

			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_menu_categories();
					// $this->upload_menus();
					// $this->upload_menu_subcategories();
					// $this->upload_menu_modifiers();
					// $this->upload_modifier_group_details();
					// $this->upload_menu_categories();
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
					$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');
				 	

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in menu categories update  -  @Justin
	public function download_update_menu_categories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menu_categories';
		    $selected_field = 'menu_cat_id , menu_cat_name, menu_sched_id, inactive, arrangement, brand, unli';
		    $based_field = 'branch_code, menu_cat_id';
		    $update_based_field = 'menu_cat_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

				
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}



	// download additional in menu subcategories  -  @Justin
	public function download_menu_subcategories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menu_subcategories';
		    $selected_field = 'menu_sub_cat_id, menu_sub_cat_name, reg_date, inactive';
		    $based_field = 'branch_code, menu_sub_cat_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					
					$this->upload_menu_subcategories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

						$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in menu categories update  -  @Justin
	public function download_update_menu_subcategories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'menu_subcategories';
		   $selected_field = 'menu_sub_cat_id,menu_sub_cat_name, reg_date, inactive';
		   $based_field = 'branch_code, menu_sub_cat_id';
		    $update_based_field = 'menu_sub_cat_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download addition in item subcategories  -  @Justin
	public function download_subcategories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'subcategories';
		    $selected_field = 'cat_id, code, name, image, inactive';
		    $based_field = 'branch_code, code';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					
					$this->upload_subcategories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in  subcategories update  -  @Justin
	public function download_update_subcategories($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'subcategories';
		    $selected_field = 'sub_cat_id, cat_id, code, name, image, inactive';
		    $based_field = 'branch_code, sub_cat_id';
		    $update_based_field = 'sub_cat_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download addition in  menu schedules  -  @Justin
	public function download_menu_schedules($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menu_schedules';
		    $selected_field = 'menu_sched_id, desc, inactive';
		    $based_field = 'branch_code, menu_sched_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					
					$this->upload_menu_schedules();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}



	// download changes in  menu schedules update  -  @Justin
	public function download_update_menu_schedules($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menu_schedules';
		    $selected_field = 'menu_sched_id, desc, inactive';
		    $based_field = 'branch_code, menu_sched_id';
		    $update_based_field = 'menu_sched_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download addition in  menu schedules  -  @Justin
	public function download_menu_recipe($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menu_recipe';
		    $selected_field = 'recipe_id, menu_id, item_id, uom, qty, cost';
		    $based_field = 'branch_code, recipe_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					
					$this->upload_menu_recipe();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		
 			// echo "d";die();
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";//die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL  ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

		// download changes in  menu recipes update  -  @Justin
	public function download_update_menu_recipe($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 // echo "d";die();
		    $table_name = 'menu_recipe';
		    $selected_field = 'recipe_id, menu_recipe.menu_id, item_id, uom, qty, menu_recipe.cost';
		    $based_field = 'menu_recipe.branch_code, menu_recipe.menu_id';
		    $update_based_field = 'recipe_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$based_delete_id_ = 'menu_id';
			$based_delete_id = 'menu_recipe.menu_id';

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->join('menus','menu_recipe.menu_id = menus.menu_id AND menu_recipe.branch_code = menus.branch_code','left')->where('`menus.update_date` > ',$update_date)->where('menu_recipe.branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->join('menus','menu_recipe.menu_id = menus.menu_id AND menu_recipe.branch_code = menus.branch_code','left')->where('`menus.update_date` > ',$update_date)->where('menu_recipe.branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_based_id_raw = $this->migrate_db->select($based_delete_id)->join('menus','menu_recipe.menu_id = menus.menu_id AND menu_recipe.branch_code = menus.branch_code','left')->where('`menus.update_date` > ',$update_date)->where('menu_recipe.branch_code',BRANCH_CODE)->group_by($based_delete_id)->get($table_name)->result();

					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_based_id_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');
				if(!empty($trans_based_id_raw)){
					$delete_args = array();

					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id_ ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args,'main_db');
					// echo $this->main_db->last_query();die();
					$this->delete_trans_misc_batch($table_name,$delete_args,'db');
					// echo "<pre>",print_r($add_trans_header),"</pre>";
					// echo "<pre>wrapped: ",print_r($add_trans_wrapped),"</pre>";

					// die();
					if(!empty($add_trans_header)){

				 		$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

					}

				 	// $this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

					// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}


	// download additional in menu subcategories  -  @Justin
	public function download_menu_modifiers($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menu_modifiers';
		    $selected_field = 'id, menu_id, mod_group_id';
		    $based_field = 'branch_code, id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_menu_modifiers();
					// $this->upload_menu_subcategories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
			

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
			$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in menu categories update  -  @Justin
	public function download_update_menu_modifiers($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'menu_modifiers';
		   $selected_field = 'id, menu_modifiers.menu_id, mod_group_id';
		   $based_field = 'menu_modifiers.branch_code, menu_modifiers.id';

		   $update_based_field = 'id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];
		   $based_delete_id_ = 'menu_id';
		   $based_delete_id = 'menu_id';

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					// $trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// $trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();

					$trans_raw = $this->migrate_db->select($selected_field)->join('menus','menu_modifiers.menu_id = menus.menu_id AND menu_modifiers.branch_code = menus.branch_code','left')->where('`menus.update_date` > ',$update_date)->where('menu_modifiers.branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->join('menus','menu_modifiers.menu_id = menus.menu_id AND menu_modifiers.branch_code = menus.branch_code','left')->where('`menus.update_date` > ',$update_date)->where('menu_modifiers.branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_based_id_raw = $this->migrate_db->select('menus.menu_id as menu_id')->join('menu_modifiers','menu_modifiers.menu_id = menus.menu_id AND menu_modifiers.branch_code = menus.branch_code','left')->where('`menus.update_date` > ',$update_date)->where('menus.branch_code',BRANCH_CODE)->group_by($based_delete_id)->get('menus')->result();

					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $update_date." <br>";
			// echo $this->migrate_db->last_query();
			// echo "<pre>trans raw: ",print_r($trans_raw),"</pre>";
			// echo "<pre>based field: ",print_r($trans_id_raw),"</pre>";

			// echo "<pre>based deleted id: ",print_r($trans_based_id_raw),"</pre>";
			// die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'" ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

					if(!empty($trans_based_id_raw)){
						$delete_args = array();

						foreach($trans_based_id_raw as $b){
							$delete_args[$based_delete_id][]  = $b->$based_delete_id_ ;
						}

						$this->delete_trans_misc_batch($table_name,$delete_args,'main_db');
						// echo $this->main_db->last_query();die();
						$this->delete_trans_misc_batch($table_name,$delete_args,'db');
						// // echo "<pre>",print_r($add_trans_header),"</pre>";
						// echo $this->db->last_query()."<br>";
						// echo "<pre>wrapped: ",print_r($add_trans_wrapped),"</pre>";
						// echo "<pre>wrapped def: ",print_r($add_trans_wrapped_default),"</pre>";


						// die();
						if(!empty($add_trans_header)){

					 		$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
					 		$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						}

					 	// $this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
					 	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

						// echo $this->db->last_query();
						$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
						$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					}
				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download additional in modifiers  -  @Justin
	public function download_modifiers($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'modifiers';
		    $selected_field = 'mod_id, name, cost, has_recipe, reg_date, inactive, mod_code,mod_sub_cat_id';
		    $based_field = 'branch_code, mod_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					
					$this->upload_modifiers();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL  ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}
	public function download_modifier_prices($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'modifier_prices';
		   $selected_field = 'id, mod_id, trans_type, price';
		   $based_field = 'branch_code, mod_id';

		   $update_based_field = 'mod_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];
			
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_modifier_recipe();
					// $this->upload_menu_subcategories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		
 			// echo "d";die();
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";//die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL  ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}
	public function download_menu_prices($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menu_prices';
		   $selected_field = 'id, menu_id, trans_type, price';
		   $based_field = 'branch_code, menu_id';

		   $update_based_field = 'menu_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];
			
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_menu_prices();
					// $this->upload_menu_subcategories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		
 			// echo "d";die();
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";//die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL  ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');


				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}
	public function download_modifier_sub($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'modifier_sub';
		   $selected_field = 'mod_sub_id, mod_id, name, cost';
		   $based_field = 'branch_code, mod_sub_id';

		   $update_based_field = 'mod_sub_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];
			
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_modifier_sub();
					// $this->upload_menu_subcategories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		
 			// echo "d";die();
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL  ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}
	public function download_transaction_types($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'transaction_types';
		   $selected_field = 'trans_id, trans_name, inactive';
		   $based_field = 'branch_code, trans_id';

		   $update_based_field = 'trans_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];
			
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_transaction_types();
					// $this->upload_menu_subcategories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		
 			// echo "d";die();
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL  ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}
	public function download_modifier_sub_prices($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'modifier_sub_prices';
		   $selected_field = 'id, mod_sub_id, trans_type, price';
		   $based_field = 'branch_code, mod_sub_id';

		   $update_based_field = 'mod_sub_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];
			
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_modifier_sub_prices();
					// $this->upload_menu_subcategories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		
 			// echo "d";die();
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL  ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}
	// download changes in menu categories update  -  @Justin
	public function download_update_modifier_prices($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'modifier_prices';
		   $selected_field = 'mod_id, trans_type, price';
		   $based_field = 'branch_code, mod_id';

		   $update_based_field = 'mod_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}
	public function download_update_menu_prices($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'menu_prices';
		   $selected_field = 'menu_id, trans_type, price';
		   $based_field = 'branch_code, menu_id';

		   $update_based_field = 'menu_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}
	public function download_update_modifier_sub_prices($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'modifier_sub_prices';
		   $selected_field = 'mod_sub_id, trans_type, price';
		   $based_field = 'branch_code, mod_sub_id';

		   $update_based_field = 'mod_sub_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}
	public function download_update_transaction_types($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'transaction_types';
		   $selected_field = 'trans_id, trans_name, inactive';
		   $based_field = 'branch_code, trans_id';

		   $update_based_field = 'trans_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}
	public function download_update_modifier_sub($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'modifier_sub';
		   $selected_field = 'mod_id, name, cost';
		   $based_field = 'branch_code, mod_id';

		   $update_based_field = 'mod_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in menu categories update  -  @Justin
	public function download_update_modifiers($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'modifiers';
		   $selected_field = 'mod_id, name, cost, has_recipe, reg_date, inactive, mod_code,mod_sub_cat_id';
		   $based_field = 'branch_code, mod_id';

		   $update_based_field = 'mod_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}


		// download addition in  modifier recipe  -  @Justin
	public function download_modifier_recipe($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'modifier_recipe';
		    $selected_field = 'mod_recipe_id, mod_id, item_id, uom, qty, cost';
		    $based_field = 'branch_code, mod_recipe_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_modifier_recipe();
					// $this->upload_menu_subcategories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		
 			// echo "d";die();
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";//die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL  ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

		// download modifier recipe update  -  @Justin
	public function download_update_modifier_recipe($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 // echo "d";die();
		    $table_name = 'modifier_recipe';
		    $selected_field = 'mod_recipe_id, modifier_recipe.mod_id, item_id, uom, qty, modifier_recipe.cost';
		    $based_field = 'modifier_recipe.branch_code, modifier_recipe.mod_id';
		    $update_based_field = 'mod_recipe_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$based_delete_id_ = 'mod_id';
			$based_delete_id = 'modifier_recipe.mod_id';
			$table_name_main = 'modifiers';
			
			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->join('modifiers','modifier_recipe.mod_id = modifiers.mod_id AND modifier_recipe.branch_code = modifiers.branch_code','left')->where('`modifiers.update_date` > ',$update_date)->where('modifier_recipe.branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->join('modifiers','modifier_recipe.mod_id = modifiers.mod_id AND modifier_recipe.branch_code = modifiers.branch_code','left')->where('`modifiers.update_date` > ',$update_date)->where('modifier_recipe.branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_based_id_raw = $this->migrate_db->select($based_delete_id_)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name_main)->result();

					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_based_id_raw),"</pre>";die();


			if(!empty($trans_based_id_raw) && isset($trans_based_id_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');
				if(!empty($trans_based_id_raw)){
					$delete_args = array();

					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id_ ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args,'main_db');
					// echo $this->main_db->last_query();die();
					$this->delete_trans_misc_batch($table_name,$delete_args,'db');
					// echo "<pre>",print_r($add_trans_header),"</pre>";
					// echo "<pre>wrapped: ",print_r($add_trans_wrapped),"</pre>";

					// die();
					if(!empty($add_trans_header)){

				 		$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

					}

				 	// $this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

					// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}


		// download additional in modifiers  -  @Justin
	public function download_modifier_groups($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'modifier_groups';
		    $selected_field = 'mod_group_id, name, mandatory, multiple, inactive';
		    $based_field = 'branch_code, mod_group_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_modifier_groups();
					// $this->upload_menu_subcategories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

						$this->update_tbl($table_name,array('master_id' => NULL  ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in modifier groups update  -  @Justin
	public function download_update_modifier_groups($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'modifier_groups';
		    $selected_field = 'mod_group_id, name, mandatory, multiple, inactive';
		    $based_field = 'branch_code, mod_group_id';

		   $update_based_field = 'mod_group_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

		// download addition in  modifier group details  -  @Justin
	public function download_modifier_group_details($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'modifier_group_details';
		    $selected_field = 'id, mod_group_id, mod_id, default';
		    $based_field = 'branch_code, id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_modifier_group_details();
					// $this->upload_menu_subcategories();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		
			 // echo "d";die();
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";//die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL  ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

		// download modifier group details update  -  @Justin
	public function download_update_modifier_group_details($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 // echo "d";die();
		    $table_name = 'modifier_group_details';
		    $selected_field = 'id,  modifier_group_details.mod_group_id, mod_id, default';
		    $based_field = 'modifier_group_details.branch_code, modifier_group_details.id';
		    $update_based_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$based_delete_id_ = 'mod_group_id';
			$based_delete_id = 'modifier_group_details.mod_group_id';
			$table_name_main = 'modifier_groups';
			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->join('modifier_groups','modifier_group_details.mod_group_id = modifier_groups.mod_group_id AND modifier_group_details.branch_code = modifier_groups.branch_code','left')->where('`modifier_groups.update_date` > ',$update_date)->where('modifier_group_details.branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->join('modifier_groups','modifier_group_details.mod_group_id = modifier_groups.mod_group_id AND modifier_group_details.branch_code = modifier_groups.branch_code','left')->where('`modifier_groups.update_date` > ',$update_date)->where('modifier_group_details.branch_code',BRANCH_CODE)->get($table_name)->result();
					// $trans_based_id_raw = $this->migrate_db->select($based_delete_id)->join('modifier_groups','modifier_group_details.mod_group_id = modifier_groups.mod_group_id AND modifier_group_details.branch_code = modifier_groups.branch_code','left')->where('`modifier_groups.update_date` > ',$update_date)->where('modifier_group_details.branch_code',BRANCH_CODE)->group_by($based_delete_id)->get($table_name)->result();
					$trans_based_id_raw = $this->migrate_db->select($based_delete_id_)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name_main)->result();

					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_based_id_raw),"</pre>";die();


			if(!empty($trans_based_id_raw) && isset($trans_based_id_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_based_id_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');
				if(!empty($trans_based_id_raw)){
					$delete_args = array();

					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id_ ;
					}
					// echo "<pre>",print_r($delete_args),"</pre>";die();
					$this->delete_trans_misc_batch($table_name,$delete_args,'main_db');
					// echo $this->main_db->last_query();die();
					$this->delete_trans_misc_batch($table_name,$delete_args,'db');
					// echo "<pre>",print_r($add_trans_header),"</pre>";
					// echo "<pre>wrapped: ",print_r($add_trans_wrapped),"</pre>";

					// die();
					if(!empty($add_trans_header)){

				 		$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

					}

				 	// $this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	// $this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

					// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download additional in menu subcategory  -  @Justin
	public function download_menu_subcategory($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menu_subcategory';
		    $selected_field = 'menu_sub_id, menu_sub_name, category_id, reg_date, inactive';
		    $based_field = 'branch_code, menu_sub_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					
					$this->upload_menu_subcategory();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');


				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in menu categories update  -  @Justin
	public function download_update_menu_subcategory($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   	$table_name = 'menu_subcategory';
		    $selected_field = 'menu_sub_id, menu_sub_name, category_id,	 reg_date, inactive';
		    $based_field = 'branch_code, menu_sub_id';
		    $update_based_field = 'menu_sub_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	/**add locations 08232018 - @Justin**/
	public function add_locations($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'locations';
		    $selected_field = 'loc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	/**Update locations - 08232018**/
	public function update_locations($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'locations';
		    $selected_field = 'loc_id';
		    $based_delete_id = 'loc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			
				$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
			
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


		/**add suppliers 09042018 - @Justin**/
	public function add_suppliers($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'suppliers';
		    $selected_field = 'supplier_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	/**Update suppliers - 09042018**/
	public function update_suppliers($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'suppliers';
		    $selected_field = 'supplier_id';
		    $based_delete_id = 'supplier_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			
				$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
			
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

		/**add suppliers 09042018 - @Justin**/
	public function add_terminals($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'terminals';
		    $selected_field = 'terminal_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	/**Update suppliers - 09042018**/
	public function update_terminals($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'terminals';
		    $selected_field = 'terminal_id';
		    $based_delete_id = 'terminal_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			
				$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
			
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	public function add_trans_receivings($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);
		// echo "ad";
		// die();
		    $table_name = 'trans_receivings';
		    $selected_field = 'receiving_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
		 // echo $this->main_db->last_query();
			// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'receiving_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	public function add_trans_receiving_details($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'trans_receiving_details';
		    $selected_field = 'receiving_detail_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate update trans_receivings  -  @Justin
	public function update_trans_receivings($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_receivings';
		    $selected_field = 'receiving_id';
		    $based_delete_id = 'receiving_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`update_date` > ',$update_date)->get($table_name)->result();
			
				$trans_id_raw = $this->main_db->select($selected_field)->where('`update_date` > ',$update_date)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`update_date` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
			
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}


	public function update_trans_receiving_details($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_receiving_menu_details';
		    $selected_field = 'receiving_detail_id';
		    $based_delete_id = 'receiving_detail_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->main_db->select($selected_field)->where('master_id', $migrate_local_id)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('master_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->main_db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			
				$trans_id_raw = $this->main_db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_based_id_raw = $this->main_db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}



				$this->migrate_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
			
			// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
				}

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	public function add_menu_prices($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		$table_name = 'menu_prices';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
	}

	public function add_modifier_prices($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		$table_name = 'modifier_prices';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
	}

	public function add_modifier_sub($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		$table_name = 'modifier_sub';
		    $selected_field = 'mod_sub_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
	}

	public function add_modifier_sub_prices($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		$table_name = 'modifier_sub_prices';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					}else{
						$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
	}

		// download changes in menu  -  @Justin
	public function download_receipt_discounts($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'receipt_discounts';
		    $selected_field = 'disc_id, disc_code, disc_name, disc_rate, no_tax, fix , inactive,  datetime';
		    $based_field = 'disc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();

			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					
					$this->upload_receipt_discounts();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){
						// echo "asd";die();
							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}
				// echo "<pre>",print_r($trans_raw),"</pre>";

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');


				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in menu  -  @Justin
	public function download_update_receipt_discounts($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'receipt_discounts';
		    $selected_field = 'disc_id, disc_code, disc_name, disc_rate, no_tax, fix , inactive, datetime';
		    $based_field = 'branch_code,disc_id';
		    $update_based_field = 'disc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`datetime` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`datetime` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
			
	}

	public function download_charges($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'charges';
		    $selected_field = 'charge_id, charge_code, charge_name, charge_amount, absolute, no_tax , inactive';
		    $based_field = 'charge_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					
					$this->upload_charges();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){
						// echo "asd";die();
							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}
				// echo "<pre>",print_r($trans_raw),"</pre>";

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	public function download_update_charges($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'charges';
		    $selected_field = 'charge_id, charge_code, charge_name, charge_amount, absolute, no_tax , inactive';
		    $based_field = 'branch_code,charge_id';
		    $update_based_field = 'charge_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`datetime` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`datetime` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
			
	}

	public function download_brands($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'brands';
		    $selected_field = 'id, brand_code, brand_name , inactive';
		    $based_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();
			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					
					$this->upload_brands();
				
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){
						// echo "asd";die();
							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'"),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}
				// echo "<pre>",print_r($trans_raw),"</pre>";

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	public function download_update_brands($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		   $table_name = 'brands';
		    $selected_field = 'id, brand_code, brand_name , inactive';
		    $based_field = 'branch_code,id';
		    $update_based_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`datetime` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`datetime` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
			
	}



		/***
		** insert a row which serves as a flag that the migration has been successful@ Justin
		*/ 


		public function finish_log(){
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"1","type"=>'finish',"transaction"=> 'master_logs','user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
			$migration_id = $this->add_tbl('master_logs',array("status"=>"1","type"=>'finish',"transaction"=> 'master_logs','user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
						
		}

		public function get_finish_log(){
			$last_logs = $this->main_db->order_by('master_id desc')->limit('1')->get_where('master_logs',array('type'=>"finish"))->result();
			$last_log = false;
			// echo "<pre>",print_r($last_logs),"</pre>";die();
			// if(isset($last_logs[2]) && !empty($last_logs)){ // get second to the last log if available , to consider some lapse time in last finish migration
			// 	$last_log = $last_logs[2];
			// }else
			if(isset($last_logs[1]) && !empty($last_logs)){ // get second to the last log if available , to consider some lapse time in last finish migration
				$last_log = $last_logs[1];
			}elseif(isset($last_logs[0]) && !empty($last_logs)){
				$last_log = $last_logs[0];
			}

			return $last_log;


		}


		/***
		** insert a row which serves as a flag that the migration has been successful@ Justin
		*/ 


		public function finish_download_log(){
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"1","type"=>'finish_download',"transaction"=> 'master_logs','user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
			$migration_id = $this->add_tbl('master_logs',array("status"=>"1","type"=>'finish_download',"transaction"=> 'master_logs','user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
						
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


	/***
	** check the last log of migration if no logs it will return false @ Justin
	*/ 


	public function check_last_log(){
		$last_logs = $this->main_db->order_by('master_id desc')->limit('1')->get_where('master_logs')->result();
		$last_log = false;
		// echo "<pre>",print_r($last_logs),"</pre>";die();

		if(isset($last_logs[0]) && !empty($last_logs)){
			$last_log = $last_logs[0];
		}

		return $last_log;


	}

		/***
	** check the last log of migration if no logs it will return false @ Justin
	*/ 


	public function check_last_download_log(){
		$last_logs = $this->main_db->order_by('master_id desc')->limit('1')->get_where('master_logs',array('type'=>'finish_download'))->result();
		$last_log = false;
		// echo "<pre>",print_r($last_logs),"</pre>";die();

		if(isset($last_logs[0]) && !empty($last_logs)){
			$last_log = $last_logs[0];
		}

		return $last_log;


	}






	/***
	** executes the migration by running the functions listed in $functions_array @ Justin
	*/ 
	public function execute_migration(){
		$last_log = $this->check_last_log();
		$finish_log =  $this->get_finish_log();
			// echo "<pre>",print_r($finish_log),"</pre>";die();
			// // add records from main to master

			 if(empty($finish_log)){

				$add_functions_array = array('add_trans_sales','add_trans_sales_local_tax',
					'add_trans_sales_charges','add_trans_sales_discounts','add_trans_sales_items','add_trans_sales_local_tax',
					'add_trans_sales_loyalty_points','add_trans_sales_menus','add_trans_sales_menu_modifiers',
					'add_trans_sales_no_tax','add_trans_sales_payments','add_trans_sales_tax','add_trans_sales_local_tax', 'add_trans_sales_zero_rated',
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
				);
			 }else{

				$add_functions_array = array('add_trans_sales','add_trans_sales_local_tax',
					'add_trans_refs'	,'add_users',
					// 'add_receipt_discounts',
					'add_tables','add_coupons','migrate_branch_details',
					'add_trans_receiving_menu','add_trans_receiving_menu_details','add_trans_receivings','add_trans_receiving_details','add_menu_moves','add_item_moves','add_reasons','add_locations','add_suppliers','add_terminals','add_menu_prices','add_modifier_prices','add_modifier_sub','add_modifier_sub_prices'
				,'add_trans_gc','add_trans_gc_local_tax',
					'add_trans_gc_charges','add_trans_gc_discounts',
					'add_trans_gc_loyalty_points','add_trans_gc_gift_cards',
					'add_trans_gc_no_tax','add_trans_gc_payments','add_trans_gc_tax', 'add_trans_gc_zero_rated',
					// 'add_gift_cards',
					'add_store_zread'
				);
			}

				// $add_functions_array = array('add_users');

			// update records from main to master
				// $update_functions_array = array();
			$update_functions_array = array('update_trans_sales','update_trans_sales_charges','update_trans_sales_discounts',
											'update_trans_sales_items','update_trans_sales_local_tax','update_trans_sales_loyalty_points',
											'update_trans_sales_menu_modifiers','update_trans_sales_menus','update_trans_sales_no_tax',
											'update_trans_sales_payments','update_trans_sales_tax','update_trans_sales_zero_rated',
											'update_trans_receiving_menu','update_trans_receiving_menu_details','update_trans_receivings','update_trans_receiving_details',
											'update_menu_moves','update_item_moves','update_locations','update_suppliers','update_terminals','update_trans_sales_menu_submodifiers');

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
											  'download_charges','download_update_charges','download_modifier_prices','download_modifier_sub','download_transaction_types','download_update_transaction_types','download_modifier_sub_prices','download_update_modifier_prices','download_update_modifier_sub','download_update_modifier_sub_prices','download_menu_prices','download_update_menu_prices',
											  'download_brands','download_update_brands',
											  'download_payment_group','download_update_payment_group','download_payment_types','download_update_payment_types','download_payment_type_fields','download_update_payment_type_fields',
											  'download_gift_cards','download_update_gift_cards'

						);
			// $download_functions_array = array()
				// echo "a";die();
			//check back log before migrating new data
			// $this->update_tbl('trans_sales',array('`datetime` > ' => "2018-03-20 16:26:38" ,'`master_id` is not null'  => NULL),array('migration_id'=>N),NULL,NULL,'main_db',true);


			// echo $this->main_db->last_query();die();

			// echo "<pre>",print_r($this->check_backlogs()),"</pre>";die();
			if($this->check_backlogs()) {
				foreach($add_functions_array as $func){

					if($last_log){
						$migrate_id = $last_log->master_sync_id;
						$last_update = $last_log->migrate_date;
						$this->$func($migrate_id,$last_update);

					}else{
						$this->$func();
					}
				}

				if(!empty($last_log)){
					foreach($update_functions_array as $func){

						if($last_log){

							if($finish_log && !empty($finish_log)){ // if finish log is retrieved get the date as the basis of migration
								$migrate_id = $finish_log->master_sync_id;
								$last_update = $finish_log->migrate_date;
								$this->$func($migrate_id,$last_update);
							}else{ // this means there is no migration yet
								$migrate_id = $last_log->master_sync_id;
								$last_update = '2018-12-01 00:00:00';//$last_log->migrate_date;
								$this->$func($migrate_id,$last_update);
							}

						}else{
							$this->$func();
						}
					}
				}


				// run download array()
				foreach($download_functions_array as $func){

					if($last_log){
						// $migrate_id = $last_log->master_id;
						$migrate_id = $finish_log->master_sync_id;
						$last_update = $finish_log->migrate_date;
						$this->$func($migrate_id,$last_update);

					}else{
						$this->$func();
					}
				}

				if(CONSOLIDATOR){
					$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

	                foreach($terminals as $ctr => $vals){
	                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

	                    foreach($download_functions_array as $func){

							if($last_log){
								// $migrate_id = $last_log->master_id;
								$migrate_id = $finish_log->master_sync_id;
								$last_update = $finish_log->migrate_date;
								$this->$func($migrate_id,$last_update);

							}else{
								$this->$func();
							}
						}
	                }
				}	


				// add a new finish log()

				$this->finish_log();

			}

			return json_encode($last_log);

			// echo "<pre>",print_r($last_log),"</pre>";die();
	}

	/** if there are pending transactions process them through automation - Justin 11/9/2017**/
	public function check_backlogs(){
		// echo "d";die();
			$pending_logs = $this->main_db->order_by('migrate_date','desc')->get_where('master_logs',array('status'=>'0'),10)->result(); // limit to 10
			// echo $this->main_db->last_query();die();
			if(!empty($pending_logs)){
			// echo "<pre>",print_r($pending_logs),"</pre>";die(); 
				foreach ($pending_logs as $key => $logs) {
					$trans_type = $logs->transaction;
					$trans_action = $logs->type;
					$migrate_local_id = $logs->master_id;
					$migrate_id = $logs->master_sync_id;
					$migrate_date = $logs->migrate_date;
					$function = $trans_action."_".$trans_type;

					if(method_exists($this , $function) && $trans_type !='finish'){ // check if the function class is existing in this model if yes call the function
						$this->$function($migrate_id,$migrate_date,true,$migrate_local_id);
					}
				}
			}

			return true;
	}



	public function test(){
		$pending_logs = $this->main_db->get_where('master_logs',array('master_id'=>'18'))->result();
		return $pending_logs;
	}


	public function array_flat($trans_id_raw=array(), $var=""){

  		$return = array();
  		$property_name = $var;
 		array_walk_recursive($trans_id_raw, function($a) use (&$return,$var) { $return[] = $a->$var; });

 		return $return;
	}

	public function object_flat($trans_id_raw=array(), $var=""){

  		$return = array();
  		$property_name = $var;

 		array_walk_recursive($trans_id_raw, function($a) use (&$return,$var) { $return[] = (object) array($var => $a->$var);  });

 		return $return;
	}

	/***
	** executes the migration by running the functions listed in $functions_array @ Justin
	*/ 
	public function execute_migration_download_items(){
		$last_log = $this->check_last_download_log();
		$finish_log =  $this->get_finish_download_log();
			// echo "<pre>",print_r($finish_log),"</pre>";die();
			// // add records from main to master

			
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
											  'download_charges','download_update_charges','download_modifier_prices','download_modifier_sub','download_modifier_sub_prices','download_update_modifier_prices','download_update_modifier_sub','download_update_modifier_sub_prices','download_transaction_types','download_update_transaction_types','download_menu_prices','download_update_menu_prices','download_brands','download_update_brands'

						);

				// $download_functions_array = array('download_modifier_sub');
			

				// run download array()

				foreach($download_functions_array as $func){

					if($last_log){
						// $migrate_id = $last_log->master_id;
						$migrate_id = $finish_log->master_sync_id;
						$last_update = $finish_log->migrate_date;
						$this->$func($migrate_id,$last_update);

					}else{
						$this->$func();
					}
				}


				// add a new finish log()

				$this->finish_download_log();


			return json_encode($last_log);

			// echo "<pre>",print_r($last_log),"</pre>";die();
	}

	function download_trans_for_hq($tables){
		$user = $this->session->userdata('user');
		$user_id = $user['id'];

		$type="add";

		$return = '';

		$counter = 0;

		foreach ($tables as $table) {
			$select = $this->trans_sales_fields($table);
			$query = $this->main_db->select($select)->where('master_id is null')->get($table);
			$result = $query->result_array();
			// print_r($this->main_db->last_query());
			$num_fields = $query->num_fields();

			if($select == '*'){
				$fields = $this->main_db->list_fields($table);

				$selected_field = $this->json_master_logs($table);
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$fields = explode(',', $select);

				// $trans_id_raw = $this->object_flat($result,"sales_id");
				// print_r((object) $result);exit;
				// $trans_id_raw = (object) $result;
				$json_encode  = json_encode(array('sales_id'=>$this->array_flat($query->result(),"sales_id")));
			}

			$fields[] = 'branch_code';
			
			$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>''),NULL,'main_db');

			// print_r($fields);exit;
			$new_fields = array();
			foreach ($fields as $field)
			{
				$new_fields[] = '`'.trim($field).'`';

				if($counter % 100 == 0){
                    set_time_limit(60);  
                }

                $counter++;
			}


			foreach($result as $row){
				$new_row = array();
				// print_r($row[$fields[0]]);exit;
				$return .= 'INSERT INTO '.$table.' (' . implode(',', $new_fields) . ') ';

				foreach ($fields as $field)
				{
					if($field == 'master_id'){
						$new_row[] = '"'.$migration_id.'"';
					}
					elseif($field == 'branch_code'){
						$new_row[] = '"'.BRANCH_CODE.'"';
					}
					else{
						$new_row[] = '"'.$row[$field].'"';
					}
					
				}				

				$return .= 'VALUES(' . implode(',', $new_row) . ');';
				
				$return .= "\r\n";

				if($counter % 100 == 0){
                    set_time_limit(60);  
                }

                $counter++;
			}

			$return .= "\r\n";

			$this->update_tbl($table,array('master_id'=>null),array("master_id"=>$migration_id),NULL,NULL,'main_db');
			
		}


		return $return;
	}

	public function trans_sales_fields($table){
		switch ($table) {
			case 'trans_sales':
				$fields = 'sales_id,mobile_sales_id,type_id,trans_ref,void_ref,type,user_id,shift_id,customer_id,total_amount,total_paid,memo,table_id,guest,datetime,
							update_date,paid,reason,void_user_id,printed,inactive,waiter_id,split,
							serve_no,billed,sync_id,terminal_id,pos_id,queue_id,ar_payment_amount,master_id';
				break;

			case 'trans_gc':
				$fields = 'gc_id,mobile_sales_id,type_id,trans_ref,void_ref,type,user_id,shift_id,
							customer_id,total_amount,total_paid,memo,table_id,guest,datetime,
							update_date,paid,reason,void_user_id,printed,inactive,waiter_id,split,
							serve_no,billed,sync_id,terminal_id,pos_id,master_id';
				break;
			
			default:
				$fields = '*';
				break;
		}
		

		return $fields;
	}

	public function json_master_logs($table_name){
		$select = array('trans_sales'=>'sales_id','trans_sales_charges'=>'sales_charge_id,sales_id',
						'trans_sales_discounts'=>'sales_disc_id,sales_id','trans_sales_items'=>'sales_item_id,sales_id',
						'trans_sales_local_tax'=>'sales_local_tax_id,sales_id','trans_sales_loyalty_points'=>'loyalty_point_id,sales_id',
						'trans_sales_menu_modifiers'=>'sales_mod_id,sales_id','trans_sales_menu_submodifiers'=>'sales_submod_id,sales_id',
						'trans_sales_menus'=>'sales_menu_id','trans_sales_no_tax'=>'sales_no_tax_id,sales_id',
						'trans_sales_payments'=>'payment_id,sales_id','trans_sales_tax'=>'sales_tax_id,sales_id',
						'trans_sales_zero_rated'=>'sales_zero_rated_id,sales_id'
						,
						'trans_gc'=>'gc_id','trans_gc_charges'=>'gc_charge_id,gc_id',
						'trans_gc_discounts'=>'gc_disc_id,gc_id',
						'trans_gc_local_tax'=>'gc_local_tax_id,gc_id','trans_gc_loyalty_points'=>'gc_loyalty_point_id,gc_id',
						
						'trans_gc_gift_cards'=>'gc_gift_card_id,gc_id','trans_gc_no_tax'=>'gc_no_tax_id,gc_id',
						'trans_gc_payments'=>'gc_payment_id,gc_id','trans_gc_tax'=>'gc_tax_id,gc_id',
						'trans_gc_zero_rated'=>'gc_zero_rated_id,gc_id'
					);

		return $select[$table_name];
	}

	// migrate add trans gc  
	public function add_trans_gc($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		// if(!empty($trans_id)){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
			$type="add";
			$table_name = 'trans_gc';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('`gc_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed`, `sync_id`,`terminal_id`,`branch_code`,`pos_id`')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->object_flat($trans_raw,"gc_id");
				$json_encode  = json_encode(array('gc_id'=>$this->array_flat($trans_raw,"gc_id")));

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('`gc_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed` , `sync_id`,`terminal_id`,`branch_code`')->where('master_id',$migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select('gc_id')->where('master_id',$migrate_local_id)->get($table_name)->result();

				}else{
					$trans_raw = $this->main_db->select('`gc_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed` , `sync_id`,`terminal_id`,`branch_code`')->where('master_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->object_flat($trans_raw,"gc_id");
					$json_encode  = json_encode(array('gc_id'=>$this->array_flat($trans_raw,"gc_id")));

					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"gc_id");
					$hq_trans_raw = $this->migrate_db->select('`gc_id`')->where_in('gc_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"gc_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_id','main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{

				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
		// }

	}

	// migrate add trans gc local_tax  
	public function add_trans_gc_local_tax($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_gc_local_tax';
		    $selected_field = 'gc_local_tax_id,gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id',$migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_local_tax_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"gc_local_tax_id");
					$hq_trans_raw = $this->migrate_db->select('`gc_local_tax_id`')->where_in('gc_local_tax_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"gc_local_tax_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_local_tax_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate add trans gc_charges 
	public function add_trans_gc_charges($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
			$type = 'add';
		    $table_name = 'trans_gc_charges';
		    $selected_field = 'gc_charge_id,gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_charge_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"gc_charge_id");
					$hq_trans_raw = $this->migrate_db->select('`gc_charge_id`')->where_in('gc_charge_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"gc_charge_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_charge_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate add trans sales_discounts 
	public function add_trans_gc_discounts($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_gc_discounts';
		    $selected_field = 'gc_disc_id,gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
				
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_disc_id','main_db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"gc_disc_id");
					$hq_trans_raw = $this->migrate_db->select('`gc_disc_id`')->where_in('gc_disc_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"gc_disc_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_disc_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	

	// migrate add trans gc loyalty points 
	public function add_trans_gc_loyalty_points($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_gc_loyalty_points';
		    $selected_field = 'gc_loyalty_point_id,gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id',$migration_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_loyalty_point_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"gc_loyalty_point_id");
					$hq_trans_raw = $this->migrate_db->select('`gc_loyalty_point_id`')->where_in('gc_loyalty_point_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"gc_loyalty_point_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_loyalty_point_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate add trans_gc_gift_cards  
	public function add_trans_gc_gift_cards($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_gc_gift_cards';
		    $selected_field = 'gc_gift_card_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				if(count($trans_id_raw) > 5000) // records that exceeds 20000 clogs the migration , temporary solution @justinx02022018
						$json_encode  = "";// json_encode($trans_id_raw);
				else
					$json_encode =  json_encode($trans_id_raw);
						
			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->get($table_name)->result();
					if(count($trans_id_raw) > 5000) // records that exceeds 20000 clogs the migration , temporary solution @justinx02022018
						$json_encode  = "";// json_encode($trans_id_raw);
					else
					$json_encode =  json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_gift_card_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"gc_gift_card_id");
					$hq_trans_raw = $this->migrate_db->select('`gc_gift_card_id`')->where_in('gc_gift_card_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"gc_gift_card_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_gift_card_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

		// migrate add trans_gc_no_tax  
	public function add_trans_gc_no_tax($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_gc_no_tax';
		    $selected_field = 'gc_no_tax_id,gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
						// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
						// $this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_no_tax_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"gc_no_tax_id");
					$hq_trans_raw = $this->migrate_db->select('`gc_no_tax_id`')->where_in('gc_no_tax_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"gc_no_tax_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_no_tax_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate add trans_sales_payments  
	public function add_trans_gc_payments($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_gc_payments';
		    $selected_field = 'gc_payment_id,gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id
						$this->main_db->trans_start();
							$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
							$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
						
							// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
							// $this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_payment_id','main_db');
							// if(empty($migrate_id)){
							// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
							// }else{
							// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
							// }
						$this->main_db->trans_complete();
					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"gc_payment_id");
					$hq_trans_raw = $this->migrate_db->select('`gc_payment_id`')->where_in('gc_payment_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"gc_payment_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_payment_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate add trans_gc_tax 
	public function add_trans_gc_tax($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_gc_tax';
		    $selected_field = 'gc_tax_id,gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
					// $this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_tax_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"gc_tax_id");
					$hq_trans_raw = $this->migrate_db->select('`gc_tax_id`')->where_in('gc_tax_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"gc_tax_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_tax_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// migrate add trans_gc_zero_rated  
	public function add_trans_gc_zero_rated($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_gc_zero_rated';
		    $selected_field = 'gc_zero_rated_id,gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id',$migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					// $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('master_id' => $migration_id) , true,false);
					// $this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_zero_rated_id','main_db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"gc_zero_rated_id");
					$hq_trans_raw = $this->migrate_db->select('`gc_zero_rated_id`')->where_in('gc_zero_rated_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"gc_zero_rated_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_zero_rated_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
	}

	public function add_gift_cards($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'gift_cards';
		    $selected_field = 'gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"gc_id");
					$hq_trans_raw = $this->migrate_db->select('`gc_id`')->where_in('gc_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"gc_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	public function add_store_zread($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'store_zread';
		    $selected_field = 'zread_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($selected_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$trans_id_raw = $this->main_db->select($selected_field)->where('master_id is null' ,NULL,false)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
			 // echo $this->main_db->last_query();
				// 		echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count,'sender_ip_address'=>$this->getRealIpAddr())); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');
					// }else{
					// 	$this->update_tbl($table_name,array('master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db',true);

					// }

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'terminal_id'=>TERMINAL_ID,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

					$main_trans_id_raw = $this->array_flat($trans_raw,"zread_id");
					$hq_trans_raw = $this->migrate_db->select('`zread_id`')->where_in('zread_id' ,$main_trans_id_raw)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$hq_trans_id_raw = $this->object_flat($hq_trans_raw,"zread_id");

					$update_trans_wrapped = $this->formulate_object($hq_trans_id_raw , array('master_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,'zread_id','main_db');

				$this->migrate_db->trans_complete();

				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				
				
	}

	// for online delivery

	function check_app_sales(){
	    $selected_field = '*';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
		// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
		$sales = $this->app_db->select($selected_field)->join('locations','locations.location_id=sales.location_id')->where(array('locations.`name`'=>APP_LOCATION,'received'=>1))->where('`pos_sale_id` is null ', NULL , false)->get('sales')->result();

		foreach($sales as $row){
			$sale_items = $this->app_db->select('sales_items.*,items.name')->join('items','items.item_id=sales_items.item_id')->where('sale_id',$row->sale_id)->get('sales_items')->result();
			$sale_modifiers = $this->app_db->select('*')->where('sale_id',$row->sale_id)->get('sales_item_modifiers')->result();

			$this->app_db->trans_start();

			$trans=array('type_id'=>10,
					 'type'=>$row->transaction_type,
					 'total_amount'=>round($row->total_amount,2),
					 'total_paid'=>0,
					 'paid'=>0,
					 'printed'=>0,
					 'inactive'=>0,
					 'billed'=>0,
					 'user_id'=>$user_id,
					 'shift_id'=>1,
					 'terminal_id'=>1,
					 'datetime'=>$row->sale_time,
					 'total_charges'=>round($row->delivery_fee,2),
					 'app_sale_id'=>$row->sale_id,
					 'app_reference'=>$row->order_no
					 );
			$this->db->insert('trans_sales',$trans);

			// print_r($trans);exit;
			$sale_id = $this->db->insert_id();

			$this->update_tbl('sales',array('sale_id'=>$row->sale_id),array('pos_sale_id'=>$sale_id),NULL,NULL,'app_db');

			if($row->delivery_fee > 0){
				$delivery_charge = array('sales_id'=>$sale_id,
								 'charge_id'=>4,//charge_id_ipos,
								  'charge_code'=>'Delivery Charge',
								  'charge_name'=>'Delivery Charge',
								  'rate'=>round($row->delivery_fee,2),
								  'amount'=>round($row->delivery_fee,2),
								  'absolute'=>1,
								  'datetime'=>$row->sale_time
								  	);

				$this->db->insert('trans_sales_charges',$delivery_charge);
			}

			if($row->promo_amount > 0){
				$trans_discount = array('sales_id'=>$sale_id,
								 'disc_id'=>75,//charge_id_ipos,
								  'disc_code'=>'OPENDISC1',
								  'name'=>'Open Discount',
								  'rate'=>round($row->promo_amount,2),
								  'amount'=>round($row->promo_amount,2),
								  'datetime'=>$row->sale_time
								  	);
				$this->db->insert('trans_sales_discounts',$trans_discounts);
			}

			$menu_list = array();
			$mod_list = array();

			$i=0;				

			foreach($sale_items as $item){
				$item_detail = $this->menu_model->get_app_menu_name($item->name)[0];

				$menu=array('sales_id'=>$sale_id,
					    'menu_id'=>$item->item_id,
					    'price'=>round($item->item_unit_price,2),
					    'qty'=>$item->quantity_purchased,
						'menu_name'=>$item_detail->menu_name,
					    'is_takeout'=>1,
						'line_id'=>$i);
				$menu_list[]=$menu;

				foreach($sale_modifiers as $modifier){
						
					if($item->line == $modifier->item_line){
						// $cost = $modifier->price;
						
						$modifier_detail =  $this->mods_model->get_modifiers($modifier->mod_id)[0];
						$modifiergroup_detail =  $this->mods_model->get_modifier_groups($modifier->mod_group_id)[0];
						// echo $this->db->last_query();
						$mod = array('sales_id'=>$sale_id,
						    'menu_id'=>$item->item_id,
						    'mod_group_id'=>$modifier->mod_group_id,
						    'mod_id'=>$modifier->mod_id,
						    'price'=>round($modifier_detail->cost,2),
						    'qty'=>$modifier->qty,
							'menu_name'=>$item_detail->menu_name,
							'mod_group_name'=>$modifier_detail->name,
							'mod_name'=>$modifier_detail->name,
							'line_id'=>$i);

						$mod_list[]=$mod;
					}
				}

				$i++;

			}

			$this->db->insert_batch('trans_sales_menus', $menu_list); 

			if($mod_list)
				$this->db->insert_batch('trans_sales_menu_modifiers', $mod_list); 

			$this->app_db->trans_complete();
		}

		return count($sales);
		

	}

	// download additional in payment group  -  
	public function download_payment_group($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'payment_group';
		    $selected_field = 'payment_group_id, code, description, inactive, reg_date';
		    $based_field = 'branch_code, payment_group_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();

			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_payment_group();
					// $this->upload_menus();
					// $this->upload_menu_subcategories();
					// $this->upload_menu_modifiers();
					// $this->upload_modifier_group_details();
					// $this->upload_menu_categories();
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in payment group  -  
	public function download_update_payment_group($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'payment_group';
		    $selected_field = 'payment_group_id, code, description, inactive, reg_date';
		    $based_field = 'branch_code, payment_group_id';
		    $update_based_field = 'payment_group_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

				
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// upload menu categories from main to master  @Justin
	public function upload_payment_group($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'payment_group';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'payment_group_id';
		    $type = 'upload';
			// $check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			$check_if_menu_exists = $this->migrate_db->select('*')->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id ,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	// download additional in payment types  -  
	public function download_payment_types($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'payment_types';
		    $selected_field = 'payment_id, payment_code, description, payment_group_id, inactive, reg_date';
		    $based_field = 'branch_code, payment_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();

			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_payment_types();
					// $this->upload_menus();
					// $this->upload_menu_subcategories();
					// $this->upload_menu_modifiers();
					// $this->upload_modifier_group_details();
					// $this->upload_menu_categories();
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in payment types  -  
	public function download_update_payment_types($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'payment_types';
		    $selected_field = 'payment_id, payment_code, description, payment_group_id, inactive, reg_date';
		    $based_field = 'branch_code, payment_id';
		    $update_based_field = 'payment_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

				
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// upload menu categories from main to master  @Justin
	public function upload_payment_types($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'payment_types';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'payment_id';
		    $type = 'upload';
			// $check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			$check_if_menu_exists = $this->migrate_db->select('*')->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	// download additional in payment types  -  
	public function download_payment_type_fields($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'payment_type_fields';
		    $selected_field = 'field_id,payment_id, field_name, inactive';
		    $based_field = 'branch_code, field_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();

			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_payment_type_fields();
					// $this->upload_menus();
					// $this->upload_menu_subcategories();
					// $this->upload_menu_modifiers();
					// $this->upload_modifier_group_details();
					// $this->upload_menu_categories();
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in payment types  -  
	public function download_update_payment_type_fields($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'payment_type_fields';
		    $selected_field = 'field_id,payment_id, field_name, inactive';
		    $based_field = 'branch_code, field_id';
		    $update_based_field = 'field_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

				
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// upload menu categories from main to master  @Justin
	public function upload_payment_type_fields($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'payment_type_fields';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'field_id';
		    $type = 'upload';
			// $check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			$check_if_menu_exists = $this->migrate_db->select('*')->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}

	// download additional in payment types  -  
	public function download_gift_cards($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'gift_cards';
		    $selected_field = 'gc_id,card_no, amount, description_id, brand_id, inactive';
		    $based_field = 'branch_code, gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->migrate_db->select($selected_field)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			// echo count($check_if_menu_exists);die();

			if(UPLOAD_BEFORE_MIGRATE){
				if(count($check_if_menu_exists) <= '0'){
					$this->upload_gift_cards();
					// $this->upload_menus();
					// $this->upload_menu_subcategories();
					// $this->upload_menu_modifiers();
					// $this->upload_modifier_group_details();
					// $this->upload_menu_categories();
					return true;
					exit; // exit since there will be no action to take if the master has no copy of menu of main db
				}
			}
		

			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{
				$trans_raw = $this->migrate_db->select($selected_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				$trans_id_raw = $this->migrate_db->select($based_field)->where('`master_id` is null ', NULL , false)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
				// echo $this->migrate_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);		
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						// if(!empty($update_date)){

							$this->update_tbl($table_name,array('master_id' => NULL ,'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);
						// }

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped,'main_db');
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

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

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// download changes in gift cards  -  
	public function download_update_gift_cards($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'gift_cards';
		    $selected_field = 'gc_id,card_no, amount, description_id, brand_id, inactive';
		    $based_field = 'branch_code, gc_id';
		    $update_based_field = 'gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

				
			if($automated){
				$trans_raw = $this->migrate_db->select($selected_field)->where('master_id', $migrate_local_id)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			}else{

				if(!empty($update_date)){

					$trans_raw = $this->migrate_db->select($selected_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					$trans_id_raw = $this->migrate_db->select($based_field)->where('`update_date` > ',$update_date)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
					// echo $this->migrate_db->last_query();die();
					$json_encode  = json_encode($trans_id_raw);		
				}
			}				
			// echo $this->migrate_db->last_query();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>"download_update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed					
						$this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'branch_code'=> "'".BRANCH_CODE ."'" ),array('master_id'=>$migration_sync_id),NULL,NULL,'migrate_db',false);

				 		// $this->update_tbl_batch($table_name,$add_trans_wrapped,'menu_id','main_db');

					$this->migrate_db->trans_complete();
					
				}else{
					$migration_id = $migrate_local_id;
					$migration_sync_id = $migration_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the migration if failed it will rollback
					$this->db->trans_start();

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'master_id','main_db');

				
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped, $update_based_field ,'main_db');
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				// echo $this->db->last_query();
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->db->trans_complete();
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
			
	}

	// upload gift cards from main to master  @Justin
	public function upload_gift_cards($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'gift_cards';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'gc_id';
		    $type = 'upload';
			// $check_if_menu_exists = $this->migrate_db->select('*')->where('terminal_id',TERMINAL_ID)->where('branch_code',BRANCH_CODE)->get($table_name)->result();
			$check_if_menu_exists = $this->migrate_db->select('*')->where('branch_code',BRANCH_CODE)->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->main_db->select('*')->get_where($table_name,array('master_id' => NULL))->result();
				$trans_id_raw = $this->main_db->select($based_field)->get_where($table_name,array('master_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->main_db->select('*')->where('master_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->main_db->select('*')->get($table_name)->result();
					$json_encode  = json_encode($trans_raw);
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->migrate_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'terminal_id'=> TERMINAL_ID ,'branch_code'=> BRANCH_CODE,'record_count'=> $record_count)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('master_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'master_sync_id'=>$migration_sync_id),NULL,'main_db'); // status 0 pending ,  1 success , 3 failed
					
					
						$this->update_tbl($table_name,array( 'master_id' => NULL),array('master_id'=>$migration_id),NULL,NULL,'main_db');


					$this->migrate_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
                   
					$is_automated = 1;
				}

				$this->migrate_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('master_id' => $migration_sync_id,'branch_code'=> BRANCH_CODE) , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('master_logs',array("master_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');

				$this->migrate_db->trans_complete();

				// var_dump($this->migrate_db->trans_status());
				if($this->migrate_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if($automated){
					$this->update_tbl('master_logs',array("master_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'migrate_db');
					$this->update_tbl('master_logs',array("master_sync_id"=>$migration_id),array('status'=>"1","is_automated"=>"1"),NULL,NULL,'main_db');
				}
				return false;
			}
				

	}



	public function get_data_ex(){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('yc_data');
			
			
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function add_menu_price_data($items){
		$this->db->insert('menu_prices',$items);
		$x=$this->db->insert_id();
		return $x;
	}

	public function sync_maintenance(){
		$sync_functions_array = array('sync_categories','sync_menu_categories','sync_menu_subcategories','sync_subcategories','sync_menu_subcategory','sync_menus','sync_items','sync_menu_schedules','sync_menu_recipe','sync_modifier_recipe','sync_modifier_groups','sync_modifier_group_details','sync_modifiers','sync_menu_modifiers','sync_receipt_discounts','sync_charges','sync_modifier_prices','sync_modifier_sub','sync_transaction_types','sync_modifier_sub_prices','sync_menu_prices','sync_brands','sync_payment_group','sync_payment_types','sync_payment_type_fields','sync_gift_cards');
		// run download array()
		update_load(30);

		$loader = 31;
		foreach($sync_functions_array as $func){
			update_load($loader);
			$this->$func();

			$loader++;
		}
	}

	public function sync_categories(){
		$table_name = 'categories';
	    $selected_field = 'cat_id, code, name, image, inactive';
	    $based_field = 'cat_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();	
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_menu_categories(){
		$table_name = 'menu_categories';
	    $selected_field = 'menu_cat_id, menu_cat_name, menu_sched_id, inactive, arrangement, reg_date, brand, unli';
	    $based_field = 'menu_cat_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();	
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_menu_subcategories(){
		$table_name = 'menu_subcategories';
	    $selected_field = 'menu_sub_cat_id, menu_sub_cat_name, reg_date, inactive';
	    $based_field = 'menu_sub_cat_id';
		$user = $this->session->userdata('user');
		$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_subcategories(){
		$table_name = 'subcategories';
		    $selected_field = 'sub_cat_id, code, name, image, inactive';
		    $based_field = 'sub_cat_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw

			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}
			

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();	
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_menu_subcategory(){
		$table_name = 'menu_subcategory';
		    $selected_field = 'menu_sub_id, menu_sub_name, category_id, reg_date, inactive';
		    $based_field = 'menu_sub_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_menus(){
		$table_name = 'menus';
		    $selected_field = 'menu_id,menu_code,menu_barcode,menu_name,menu_short_desc,
		    					menu_cat_id,menu_sub_cat_id, menu_sub_id ,menu_sched_id,cost,reg_date, update_date,no_tax,miaa_cat,
		    					free,inactive,costing,brand';
		    $based_field = 'menu_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

			//for update
			$update_based_field = 'menu_id';
			$update_date = 'update_date';

			$trans_raws_m = $this->main_db->select($selected_field)->get($table_name)->result();

			if(!empty($trans_raws_m) && isset($trans_raws_m)){
				foreach($trans_raws_m as $i => $trans_raw_m){
					if($trans_raw_m->update_date == ''){
						$trans_raw_m->update_date='null';
					}
					$trans_raw2 = $this->db->select($selected_field)->where($update_based_field,$trans_raw_m->menu_id)->where($update_date .' != ',$trans_raw_m->update_date)->get($table_name)->result();

					if(!$trans_raw2){
						unset($trans_raws_m[$i]);
					}
				}

				$add_trans_header = $trans_raws_m; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raws_m);

				// echo "<pre>",print_r($trans_raws_m),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				$this->db->trans_complete();	
				
			}

			if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}

					//for update
					$update_based_field = 'menu_id';
					$update_date = 'update_date';

					$trans_raws_m = $this->main_db->select($selected_field)->get($table_name)->result();

					if(!empty($trans_raws_m) && isset($trans_raws_m)){
						foreach($trans_raws_m as $i => $trans_raw_m){
							if($trans_raw_m->update_date == ''){
								$trans_raw_m->update_date='null';
							}
							$trans_raw2 = $this->db->select($selected_field)->where($update_based_field,$trans_raw_m->menu_id)->where($update_date .' != ',$trans_raw_m->update_date)->get($table_name)->result();

							if(!$trans_raw2){
								unset($trans_raws_m[$i]);
							}
						}

						$add_trans_header = $trans_raws_m; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raws_m);

						// echo "<pre>",print_r($trans_raws_m),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);
						 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

						$this->db->trans_complete();	
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}		
	}

	public function sync_items(){
		$table_name = 'items';
		    $selected_field = 'item_id,barcode,code,name,desc,
		    					cat_id,subcat_id,supplier_id,uom,cost,type,reg_date, update_date,
		    					no_per_pack,  no_per_pack_uom , no_per_case,  reorder_qty, 	max_qty, memo,
		    					inactive';
		    $based_field = 'item_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();	
				
			}

			//for update
			$update_based_field = 'item_id';
			$update_date = 'update_date';

			$trans_raws_m = $this->main_db->select($selected_field)->get($table_name)->result();

			if(!empty($trans_raws_m) && isset($trans_raws_m)){
				foreach($trans_raws_m as $i => $trans_raw_m){
					if($trans_raw_m->update_date == ''){
						$trans_raw_m->update_date='null';
					}
					$trans_raw2 = $this->db->select($selected_field)->where($update_based_field,$trans_raw_m->$update_based_field)->where($update_date .' != ',$trans_raw_m->update_date)->get($table_name)->result();

					if(!$trans_raw2){
						unset($trans_raws_m[$i]);
					}
				}

				$add_trans_header = $trans_raws_m; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raws_m);

				// echo "<pre>",print_r($trans_raws_m),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				$this->db->trans_complete();	
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}

					//for update
					$update_based_field = 'item_id';
					$update_date = 'update_date';

					$trans_raws_m = $this->main_db->select($selected_field)->get($table_name)->result();

					if(!empty($trans_raws_m) && isset($trans_raws_m)){
						foreach($trans_raws_m as $i => $trans_raw_m){
							if($trans_raw_m->update_date == ''){
								$trans_raw_m->update_date='null';
							}
							$trans_raw2 = $this->db->select($selected_field)->where($update_based_field,$trans_raw_m->$update_based_field)->where($update_date .' != ',$trans_raw_m->update_date)->get($table_name)->result();

							if(!$trans_raw2){
								unset($trans_raws_m[$i]);
							}
						}

						$add_trans_header = $trans_raws_m; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raws_m);

						// echo "<pre>",print_r($trans_raws_m),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);
						 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

						$this->db->trans_complete();	
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_menu_schedules(){
		$table_name = 'menu_schedules';
		    $selected_field = 'menu_sched_id, desc, inactive';
		    $based_field = 'menu_sched_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();	
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_menu_recipe(){
		$table_name = 'menu_recipe';
		    $selected_field = 'recipe_id, menu_id, item_id, uom, qty, cost';
		    $based_field = 'recipe_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_modifier_recipe(){
		$table_name = 'modifier_recipe';
		    $selected_field = 'mod_recipe_id, mod_id, item_id, uom, qty, cost';
		    $based_field = 'mod_recipe_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_modifier_groups(){
		$table_name = 'modifier_groups';
		    $selected_field = 'mod_group_id, name, mandatory, multiple, inactive';
		    $based_field = 'mod_group_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_modifier_group_details(){
		$table_name = 'modifier_group_details';
		    $selected_field = 'id, mod_group_id, mod_id, default';
		    $based_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_modifiers(){
		$table_name = 'modifiers';
		    $selected_field = 'mod_id, name, cost, has_recipe, reg_date, inactive, mod_code,mod_sub_cat_id,update_date';
		    $based_field = 'mod_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

			//for update
			$update_based_field = 'mod_id';
			$update_date = 'update_date';

			$trans_raws_m = $this->main_db->select($selected_field)->get($table_name)->result();

			if(!empty($trans_raws_m) && isset($trans_raws_m)){
				foreach($trans_raws_m as $i => $trans_raw_m){
					if($trans_raw_m->update_date == ''){
						$trans_raw_m->update_date='null';
					}
					$trans_raw2 = $this->db->select($selected_field)->where($update_based_field,$trans_raw_m->$update_based_field)->where($update_date .' != ',$trans_raw_m->update_date)->get($table_name)->result();

					if(!$trans_raw2){
						unset($trans_raws_m[$i]);
					}
				}

				$add_trans_header = $trans_raws_m; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raws_m);

				// echo "<pre>",print_r($trans_raws_m),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);
				 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

				$this->db->trans_complete();	
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}

					//for update
					$update_based_field = 'mod_id';
					$update_date = 'update_date';

					$trans_raws_m = $this->main_db->select($selected_field)->get($table_name)->result();

					if(!empty($trans_raws_m) && isset($trans_raws_m)){
						foreach($trans_raws_m as $i => $trans_raw_m){
							if($trans_raw_m->update_date == ''){
								$trans_raw_m->update_date='null';
							}
							$trans_raw2 = $this->db->select($selected_field)->where($update_based_field,$trans_raw_m->$update_based_field)->where($update_date .' != ',$trans_raw_m->update_date)->get($table_name)->result();

							if(!$trans_raw2){
								unset($trans_raws_m[$i]);
							}
						}

						$add_trans_header = $trans_raws_m; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raws_m);

						// echo "<pre>",print_r($trans_raws_m),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);
						 	$this->update_tbl_batch($table_name,$add_trans_wrapped_default, $update_based_field ,'db');

						$this->db->trans_complete();	
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_menu_modifiers(){
		$table_name = 'menu_modifiers';
		    $selected_field = 'id, menu_id, mod_group_id';
		    $based_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_receipt_discounts(){
		$table_name = 'receipt_discounts';
		    $selected_field = 'disc_id, disc_code, disc_name, disc_rate, no_tax, fix , inactive,  datetime';
		    $based_field = 'disc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();	
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_charges(){
		$table_name = 'charges';
		    $selected_field = 'charge_id, charge_code, charge_name, charge_amount, absolute, no_tax , inactive';
		    $based_field = 'charge_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();	
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_modifier_prices(){
		$table_name = 'modifier_prices';
		   $selected_field = 'id,mod_id, trans_type, price';
		   $based_field = 'id';

		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_modifier_sub(){
		$table_name = 'modifier_sub';
		   $selected_field = 'mod_sub_id,mod_id, name, cost';
		   $based_field = 'mod_sub_id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();	
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_transaction_types(){
		$table_name = 'transaction_types';
		   $selected_field = 'trans_id, trans_name, inactive';
		   $based_field = 'trans_id';

		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_modifier_sub_prices(){
		$table_name = 'modifier_sub_prices';
		   $selected_field = 'id,mod_sub_id, trans_type, price';
		   $based_field = 'id';
		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_menu_prices(){
		$table_name = 'menu_prices';
		   $selected_field = 'id,menu_id, trans_type, price';
		   $based_field = 'id';

		   $user = $this->session->userdata('user');
		   $user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_brands(){
		$table_name = 'brands';
		    $selected_field = 'id, brand_code, brand_name , inactive';
		    $based_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_payment_group(){
		$table_name = 'payment_group';
		    $selected_field = 'payment_group_id, code, description, inactive, reg_date';
		    $based_field = 'payment_group_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();	
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_payment_types(){
		$table_name = 'payment_types';
		    $selected_field = 'payment_id, payment_code, description, payment_group_id, inactive, reg_date';
		    $based_field = 'payment_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_payment_type_fields(){
		$table_name = 'payment_type_fields';
		    $selected_field = 'field_id,payment_id, field_name, inactive';
		    $based_field = 'field_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	public function sync_gift_cards(){
		$table_name = 'gift_cards';
		    $selected_field = 'gc_id,card_no, amount, description_id, brand_id, inactive';
		    $based_field = 'gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
			$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
		// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
		// echo $trans_id_raw
			if($trans_id_raw){
				$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->get($table_name)->result();
			}else{
				$trans_raw = $this->main_db->select($selected_field)->get($table_name)->result();
			}

			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// echo "<pre>",print_r($trans_raw),"</pre>";

			   $this->db->trans_start();
					$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

				 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

				$this->db->trans_complete();		
				
			}

		if(CONSOLIDATOR){
		 		$terminals = $this->main_db->get_where('terminals',array('inactive'=>'0'))->result();

                foreach($terminals as $ctr => $vals){
                    $this->db = $this->db_manager->get_connection($vals->terminal_code);

                    $trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
					$trans_id_raw = $this->array_flat($trans_id_raw,$based_field);
				// 	$trans_id_raw = implode(',', $this->array_flat($trans_id_raw,$based_field));			
				// echo $trans_id_raw
					if($trans_id_raw){
						$trans_raw = $this->main_db->select($selected_field)->where_not_in($based_field,$trans_id_raw)->where('pos_id',$vals->pos_id)->get($table_name)->result();
					}else{
						$trans_raw = $this->main_db->select($selected_field)->where('pos_id',$vals->pos_id)->get($table_name)->result();
					}

					if(!empty($trans_raw) && isset($trans_raw)){

						$add_trans_header = $trans_raw; // get the record
						
						$is_automated = 0;
						$record_count = count($trans_raw);

						// echo "<pre>",print_r($trans_raw),"</pre>";

					   $this->db->trans_start();
							$add_trans_wrapped_default = $this->formulate_object($add_trans_header , array() , true,false);

						 	$this->add_tbl_batch($table_name,$add_trans_wrapped_default,'db');

						$this->db->trans_complete();		
						
					}
                }

                $this->db = $this->db_manager->get_connection('default');
		 	}	
	}

	function download_trans_sales_csv($date =''){
		$table_name = 'trans_sales_menus';
		$trans_raw = 
		// $this->main_db->select('trans_sales_menus.pos_id,sales_menu_id,trans_sales_menus.sales_id,trans_sales.datetime,menu_code,qty,price,if(type_id=11,trans_sales.datetime,"") as void_date,if(line_id = 0, total_discount,0) as discount,if(line_id = 0, disc_code,"") as disc_type,if(line_id = 0, tax,0) as vat,trans_sales.user_id,line_id,',false)
		$this->main_db->select('trans_sales.*,if(type_id=11,trans_sales.datetime,"") as void_date,if(line_id = 0, disc_code,"") as disc_type,sales_menu_id,menu_code,qty,price,line_id,',false)
			->join('trans_sales','trans_sales.sales_id = trans_sales_menus.sales_id and trans_sales.pos_id = trans_sales_menus.pos_id','left')
			->join('menus','menus.menu_id = trans_sales_menus.menu_id','left')
			->join('(select group_concat(disc_code SEPARATOR "**") as disc_code ,sales_id,pos_id FROM trans_sales_discounts group by sales_id,pos_id)trans_sales_discounts','trans_sales_discounts.sales_id = trans_sales.sales_id && trans_sales_discounts.pos_id = trans_sales.pos_id','left')
			// ->join('trans_sales tsv','tsv.trans_ref = trans_sales.sales_id','left')
			->get_where($table_name,array('date(trans_sales.datetime)'=>$date));
// echo $this->main_db->last_query();exit;
		return $trans_raw;
	}

}

?>