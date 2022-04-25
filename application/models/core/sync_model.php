<?php
/**
add and update functions were separated from each of the tables to migrate for easier maintenance and understanding. @Justin 11/2017
**/

class Sync_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->library('Db_manager');
		$this->main_db = $this->db_manager->get_connection(MIGRATED_MAIN_DB);
		$this->db = $this->db_manager->get_connection('default');
	}
	public function add_tbl($table_name,$items,$set=array(),$db="main_db"){
		if(!empty($set)){
			foreach ($set as $key => $val) {
				$this->$db->set($key, $val, FALSE);
			}
		}
		$this->$db->insert($table_name,$items);
		return $this->$db->insert_id();
	}

	public function add_tbl_batch($table_name,$items,$db='main_db'){
		$this->$db->insert_ignore_batch($table_name,$items);
		return $this->$db->insert_id();
	}

	public function update_tbl($table_name,$table_key,$items,$id=null,$set=array(),$db="main_db",$or = false){
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

	public function update_tbl_batch($table_name,$items,$key,$db="main_db"){

		$this->$db->update_batch($table_name,$items,$key);
			
		return $this->$db->affected_rows();
	}


	public function update_trans_tbl_batch($table_name,$items,$key,$db="main_db"){
		
		// $branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		if(!empty($terminal_id)) {
			// $this->$db->where('branch_code',BRANCH_CODE);
			$this->$db->where('pos_id',TERMINAL_ID);
			$this->$db->update_batch($table_name,$items,$key);
			
		}
		// $this->$db->update_batch($table_name,$items,$key);
		
		// echo $this->$db->last_query();die();
		return $this->$db->affected_rows();
	}

	public function delete_tbl_batch($table_name=null,$args=null,$db="main_db"){
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

	public function delete_trans_misc_batch($table_name=null,$args=null,$db="main_db"){
		// $branch_code = BRANCH_CODE;
		$terminal_id = TERMINAL_ID;
		 // $data = $rec->src_id;
        // $args_decoded = json_decode($args,false);
        // $args = $this->formulate_object($args_decoded,array(),true,false);
		// echo "<pre>",print_r($args),"</pre>";
		// echo $branch_code;
		// echo $terminal_id;	
		if( !empty($terminal_id)) {
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
				if($db == 'main_db'){

					// $this->$db->where('branch_code',BRANCH_CODE);
					$this->$db->where('pos_id',TERMINAL_ID);
				}
				$this->$db->delete($table_name);
				// echo "<pre>",print_r($args),"</pre>";
				// echo "delete batch query: " . $this->$db->last_query()."<br>";//die();
				
			}


		}

	}

	public function delete_tbl($table_name=null,$args=null,$db="main_db"){
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





	// TO MAIN DB

	// migrate add trans sales  @Justin
	public function add_trans_sales($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
			$type="add";
			$table_name = 'trans_sales';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			if(empty($migration_id)){

				$trans_raw = $this->db->select('`sales_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`terminal_id` ,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed`, `total_gross`, `total_discount`, `total_charges`, `zero_rated`, `no_tax`, `tax`, `local_tax`,`queue_id`,`ar_payment_amount`')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select('sales_id')->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('`sales_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`terminal_id` ,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed`, `total_gross`, `total_discount`, `total_charges`, `zero_rated`, `no_tax`, `tax`, `local_tax`,`queue_id`,`ar_payment_amount`')->where('sync_id',$migrate_local_id)->get($table_name)->result();
						// echo "<pre> automated: ",print_r($this->db->last_query()),"</pre>";

				}else{
					$trans_raw = $this->db->select('`sales_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`terminal_id` ,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed`, `total_gross`, `total_discount`, `total_charges`, `zero_rated`, `no_tax`, `tax`, `local_tax`,`queue_id`,`ar_payment_amount`')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select('sales_id')->where('sync_id' ,NULL)->get($table_name)->result();
					// echo "<pre>",print_r($this->db->last_query()),"</pre>";die();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
		
			// echo $this->db->last_query();
			// echo "<pre>trans_raw: ",print_r($trans_raw),"</pre>";
			// 			echo "<pre>trans_raw id: ",print_r($trans_id_raw),"</pre>";

			// die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
					// if(empty($migrate_id)){
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');
						// $this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');
					// 	// $this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					// echo "add trans sales: ".$this->db->last_query()."<br>";die();
					// echo "<pre>",print_r($this->db->last_query()),"</pre>";die();
					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id,'branch_code'=> BRANCH_CODE, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				// var_dump($this->main_db->trans_status());
				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}



			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_charge_id','db');
					// if(empty($migrate_id)){
						// $this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
						// $this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();
				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_disc_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id',$migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
					    $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_item_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id',$migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->or_where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_local_tax_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id',$migration_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					}else{
						$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					}

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_mod_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				if(count($trans_id_raw) > 10000) // records that exceeds 20000 clogs the migration , temporary solution @justinx02022018
						$json_encode  = "";// json_encode($trans_id_raw);
				else
					$json_encode =  json_encode($trans_id_raw);
						
			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
					if(count($trans_id_raw) > 10000) // records that exceeds 20000 clogs the migration , temporary solution @justinx02022018
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_menu_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
				
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_no_tax_id','db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'payment_id','db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

	// migrate add trans_sales_payments  @Justin
	public function add_trans_sales_payment_fields($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_sales_payment_fields';
		    $selected_field = 'payment_id,sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'payment_id','db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					

						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_tax_id','db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id',$migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_zero_rated_id','db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id',$migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('`datetime` > ',$update_date)->or_where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->or_where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
					    $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'id','db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					// echo $this->main_db->last_query();
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id ),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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
							`terminal_id` ,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`, `update_date`, 
							`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed`, `total_gross`, `total_discount`, `total_charges`, `zero_rated`, `no_tax`, `tax`, `local_tax`,`queue_id`,`ar_payment_amount`';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
			}else{
				$trans_raw = $this->db->select($selected_field)->where('`update_date` > ',$update_date)->where('sync_id is not null',NULL,false)->get($table_name)->result();
				$trans_id_raw = $this->object_flat($trans_raw,'sales_id');

				// $trans_id_raw = $this->db->select($selected_field)->where('`update_date` > ',$update_date)->where('sync_id is not null',NULL,false)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed		
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,"src_id"=>$json_encode,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');				
						// $this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'",'sync_id is not null'=> NULL ),array('sync_id'=>$migration_id),NULL,NULL,'db',false);
				// echo "	update tbl: ".$this->db->last_query()."<br>";
					
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// echo $update_date."<br>"; 
					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sales_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

	// migrate update trans_sales_charges  -  @Justin
	public function update_trans_sales_charges($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_charges';
		 // $table_join = 'trans_sales'
		 $selected_field = 'trans_sales_charges.sales_charge_id, trans_sales_charges.sales_id';
		 $based_delete_id = 'sales_id';
		 $based_delete_id_select = 'trans_sales.sales_id';
	     $user = $this->session->userdata('user');
	     $user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select('trans_sales_charges.*')->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id','left')->where('trans_sales_charges.sync_id',$migrate_local_id)->where('trans_sales_charges.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id','left')->where('trans_sales_charges.sync_id', $migrate_local_id)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id','left')->where('trans_sales_charges.sync_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
				$trans_raw = $this->db->select('trans_sales_charges.*')->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->where('trans_sales_charges.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat(array_merge($trans_raw_bd,$trans_raw),$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_charges.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->group_by($based_delete_id)->get('trans_sales')->result();
				// // echo $this->main_db->last_query();die();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
	// echo "<pre>",print_r($trans_id_raw),"</pre>";///die();
	// 		echo "<pre>",print_r($trans_based_id_raw),"</pre>";die();


			if(!empty($trans_based_id_raw) && isset($trans_based_id_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed

						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						// echo "<pre>",print_r($update_trans_wrapped),"</pre>";die();

						if(!empty($update_trans_wrapped)){
								$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');
							
						}						
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
			// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; die();
					

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// echo $this->main_db->last_query();die();
					// delete_trans_misc_batch($table_name=null,$args=null
					if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
					}

					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if(!empty($trans_based_id_raw)){ // if whole items deleted from the trans_sales_items
					if(!empty($trans_based_id_raw)){
						$delete_args = array();
						foreach($trans_based_id_raw as $b){
							$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
						}

						$this->delete_trans_misc_batch($table_name,$delete_args);
					}
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
				$trans_raw = $this->db->select('trans_sales_menus.*')->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id','left')->where('trans_sales_menus.sync_id', $migrate_local_id)->where('trans_sales_menus.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id','left')->where('trans_sales_menus.sync_id', $migrate_local_id)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id','left')->where('trans_sales_menus.sync_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_sales')->result();

				// // $trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				// // $trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				// // $trans_based_id_raw = $this->db->select($based_delete_id)->where('sync_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result(); // check those items that were completely deleted in the trans_sales_items based on trans_sales

				$trans_raw = $this->db->select('trans_sales_menus.*')->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->where('trans_sales_menus.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat(array($trans_raw_bd,$trans_raw),$based_delete_id);

				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menus.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->group_by($based_delete_id)->get('trans_sales')->result();

				// // $trans_raw = $this->db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
				// // $trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				// // $trans_based_id_raw = $this->db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// // echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre>trans_raw: ",print_r($trans_based_id_raw),"</pre>";
			// echo "<pre>trans raw based id: ",print_r($trans_based_id_raw),"</pre>";//die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');
	

						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
					
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
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{

				if(!empty($trans_based_id_raw)){ // if whole items deleted from the trans_sales_items
					if(!empty($trans_based_id_raw)){
						$delete_args = array();
						foreach($trans_based_id_raw as $b){
							$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
						}

						$this->delete_trans_misc_batch($table_name,$delete_args);
					}
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
				$trans_raw = $this->db->select('trans_sales_discounts.*')->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id','left')->where('trans_sales_discounts.sync_id', $migrate_local_id)->where('trans_sales_discounts.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id','left')->where('trans_sales_discounts.sync_id', $migrate_local_id)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id','left')->where('trans_sales_discounts.sync_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result(); // check those items that were completely deleted in the trans_sales_items based on trans_sales

				$trans_raw = $this->db->select('trans_sales_discounts.*')->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->where('trans_sales_discounts.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat(array_merge($trans_raw_bd,$trans_raw),$based_delete_id);

			// 	$trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
			// 	$trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_discounts.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->group_by($based_delete_id)->get('trans_sales')->result();
			// // echo "<pre>",print_r($trans_raw),"</pre>";die();
				// echo $this->db->last_query();die();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						// echo "<pre>",print_r($update_trans_wrapped),"</pre>";//die();
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');
						// echo $this->db->last_query();die();
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
					
			// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// echo $this->main_db->last_query();die();
					// delete_trans_misc_batch($table_name=null,$args=null
				 	if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
					}
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if(!empty($trans_based_id_raw)){ // if whole items deleted from the trans_sales_items
					if(!empty($trans_based_id_raw)){
						$delete_args = array();
						foreach($trans_based_id_raw as $b){
							$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
						}

						$this->delete_trans_misc_batch($table_name,$delete_args);
					}
				}
				return false;
			}
				
				
	}


	// migrate update trans_sales_items  -  @Justin
	public function update_trans_sales_items($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_items';
		 $selected_field = 'trans_sales_items.sales_item_id, trans_sales_items.sales_id';
		 $select_stmt = 'trans_sales_items.sales_id, trans_sales_items.line_id, trans_sales_items.item_id, trans_sales_items.price, trans_sales_items.qty, trans_sales_items.discount, trans_sales_items.no_tax, trans_sales_items.remarks, trans_sales_items.serial_key, trans_sales_items.datetime, trans_sales_items.nocharge';

		  $based_delete_id = 'sales_id';
		  $based_delete_id_select = 'trans_sales.sales_id';
		  $user = $this->session->userdata('user');
		  $user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select('trans_sales_items.*')->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id','left')->where('trans_sales_items.sync_id', $migrate_local_id)->where('trans_sales_items.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id','left')->where('trans_sales_items.sync_id', $migrate_local_id)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id','left')->where('trans_sales_items.sync_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_sales')->result();

				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result(); // check those items that were completely deleted in the trans_sales_items based on trans_sales
				$trans_raw = $this->db->select('trans_sales_items.*')->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->where('trans_sales_items.sales_id is not null',null,false)->get('trans_sales')->result();		
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat(array_merge($trans_raw_bd,$trans_raw),$based_delete_id);

				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_items.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->group_by($based_delete_id)->get('trans_sales')->result();				
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						// echo "<pre>",print_r($update_trans_wrapped),"</pre>";die();
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);				

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
					
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
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if(!empty($trans_based_id_raw)){ // if whole items deleted from the trans_sales_items
					if(!empty($trans_based_id_raw)){
						$delete_args = array();
						foreach($trans_based_id_raw as $b){
							$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
						}

						$this->delete_trans_misc_batch($table_name,$delete_args);
					}
				}
				return false;
			}
				
				
	}


	// migrate update trans_sales_local_tax  -  @Justin
	public function update_trans_sales_local_tax($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_local_tax';
		    $selected_field = 'sales_local_tax_id, sales_id';
		    $based_delete_id = 'sales_local_tax_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				$trans_based_id_raw = $this->db->select($based_delete_id)->where('sync_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
			
				$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_based_id_raw = $this->db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
			
			// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}


	// migrate update trans_sales_loyalty_points  -  @Justin
	public function update_trans_sales_loyalty_points($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_loyalty_points';
		    $selected_field = 'loyalty_point_id, sales_id';
		    $based_delete_id = 'loyalty_point_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id)->where('sync_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				// $trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed

						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'loyalty_point_id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
					
			// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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
				$trans_raw = $this->db->select('trans_sales_menu_modifiers.*')->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('trans_sales_menu_modifiers.sync_id', $migrate_local_id)->where('trans_sales_menu_modifiers.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('trans_sales_menu_modifiers.sync_id', $migrate_local_id)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('trans_sales_menu_modifiers.sync_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
				$trans_raw = $this->db->select('trans_sales_menu_modifiers.*')->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->where('trans_sales_menu_modifiers.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat(array_merge($trans_raw_bd,$trans_raw),$based_delete_id);


				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->group_by($based_delete_id)->get('trans_sales')->result();
				// // echo $this->db->last_query();die();

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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');
						// echo "<pre>",print_r($update_trans_wrapped),"</pre>";die();
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
					
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
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if(!empty($trans_based_id_raw)){ // if whole items deleted from the trans_sales_items
					if(!empty($trans_based_id_raw)){
						$delete_args = array();
						foreach($trans_based_id_raw as $b){
							$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
						}

						$this->delete_trans_misc_batch($table_name,$delete_args);
					}
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
				$trans_raw = $this->db->select('trans_sales_no_tax.*')->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id','left')->where('trans_sales_no_tax.sync_id', $migrate_local_id)->where('trans_sales_no_tax.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id','left')->where('trans_sales_no_tax.sync_id', $migrate_local_id)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id','left')->where('trans_sales_no_tax.sync_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->db->select('trans_sales_no_tax.*')->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->where('trans_sales_no_tax.sales_id is not null',null,false)->get('trans_sales')->result();		
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_no_tax.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->group_by($based_delete_id)->get('trans_sales')->result();
				// echo $this->main_db->last_query();die();
				$json_encode  = json_encode($trans_id_raw);
					
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						// echo "<pre>",print_r($update_trans_wrapped),"</pre>";die();
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');


						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
					
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
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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
				$trans_raw = $this->db->select('trans_sales_payments.*')->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id','left')->where('trans_sales_payments.sync_id', $migrate_local_id)->where('trans_sales_payments.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id','left')->where('trans_sales_payments.sync_id', $migrate_local_id)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id','left')->where('trans_sales_payments.sync_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->db->select('trans_sales_payments.*')->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->where('trans_sales_payments.sales_id is not null',null,false)->get('trans_sales')->result();		
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_payments.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->group_by($based_delete_id)->get('trans_sales')->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						// echo "<pre>",print_r($update_trans_wrapped),"</pre>";die();
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');

						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
					
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
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

// migrate update trans_sales_tax  -  @Justin
	public function update_trans_sales_tax($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_tax';
		    $selected_field = 'sales_tax_id, sales_id';
		    $based_delete_id = 'sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id)->where('sync_id', $migrate_local_id)->group_by($based_delete_id)->get($table_name)->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->db->select('*')->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id)->where('`datetime` > ',$update_date)->group_by($based_delete_id)->get($table_name)->result();
				// // echo $this->main_db->last_query();die();					
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);

						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');

						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
					
				// echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// delete_trans_misc_batch($table_name=null,$args=null
				 	$this->add_tbl_batch($table_name,$add_trans_wrapped);
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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
				$trans_raw = $this->db->select('trans_sales_zero_rated.*')->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id','left')->where('trans_sales_zero_rated.sync_id', $migrate_local_id)->where('trans_sales_zero_rated.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id','left')->where('trans_sales_zero_rated.sync_id', $migrate_local_id)->get('trans_sales')->result();
				// // echo $this->db->last_query();die();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id','left')->where('trans_sales_zero_rated.sync_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->db->select('trans_sales_zero_rated.*')->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->where('trans_sales_zero_rated.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_zero_rated.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// $this->tbl
			// echo "<pre> trans raw:",print_r($trans_raw),"</pre>";
			// echo "<pre> trans raw based raw:",print_r($trans_based_id_raw),"</pre>";
			//die();


			if(!empty($trans_based_id_raw) && isset($trans_based_id_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
					
			//echo "<pre> trans based id raw:"  , print_r($trans_based_id_raw),"</pre>"; //die();

				if(!empty($trans_based_id_raw)){
					$delete_args = array();
					foreach($trans_based_id_raw as $b){
						$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
					}

					$this->delete_trans_misc_batch($table_name,$delete_args);
					// echo "last query: ". $this->main_db->last_query();
					// delete_trans_misc_batch($table_name=null,$args=null
				 	if(!empty($add_trans_header)){
				 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
					}

					// echo "last query add: ". $this->main_db->last_query();
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
				
	}




	// migrate add trans_sales_no_tax  @Justin
	public function add_users($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'users';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,$migrate_local_id)->get($table_name)->result();

				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'id','db');

					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id,'branch_code'=> BRANCH_CODE) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

		// migrate add trans_sales_no_tax  @Justin
	public function add_receipt_discounts($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'receipt_discounts';
		    $selected_field = 'disc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,$migrate_local_id)->get($table_name)->result();

				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					

						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}


	public function add_tables($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'tables';
		    $selected_field = 'tbl_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();

				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					

						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,$selected_field,'db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}


	// migrate add logs  @Justin
	public function add_logs($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'logs';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();

				}else{
					$trans_raw = $this->db->select('*')->where('`datetime` > ',$update_date)->or_where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->or_where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
					$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
					$this->update_tbl_batch($table_name,$update_trans_wrapped,$selected_field,'db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

		// migrate customers bank  @Justin
	public function add_customers_bank($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'customers_bank';
		    $selected_field = 'bank_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();

				}else{
					$trans_raw = $this->db->select('*')->where('`datetime` > ',$update_date)->or_where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->or_where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'bank_id','db');
					
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

		// migrate item moves @Justin
	public function add_item_moves($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'item_moves';
		    $selected_field = 'move_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,$selected_field,'db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array('`reg_date` > ' => "'".$update_date."'" , 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

	// migrate read details @Justin
	public function add_read_details($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'read_details';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('`id` as read_id , read_type ,  read_date , user_id ,
				 old_total , grand_total , reg_date, scope_from , scope_to')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('`id` as read_id , read_type ,  read_date , user_id ,
				 old_total , grand_total , reg_date, scope_from , scope_to')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('`id` as read_id , read_type ,  read_date , user_id ,
				 old_total , grand_total , reg_date, scope_from , scope_to')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

	// migrate coupons @Justin
	public function add_coupons($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'coupons';
		    $selected_field = 'coupon_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					}else{
						$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					}

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

	// migrate add cashout_entries  @Justin
	public function add_cashout_entries($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'cashout_entries';
		    $selected_field = 'cashout_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
					    $update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'cashout_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

	// migrate add cashout_details  @Justin
	public function add_cashout_details($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'cashout_details';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

	// migrate add shifts  @Justin
	public function add_shifts($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'shifts';
		    $selected_field = 'shift_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'shift_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

// migrate add shift_entries  @Justin
	public function add_shift_entries($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'shift_entries';
		    $selected_field = 'entry_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'entry_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

	// migrate add trans_receiving_menu  @Justin
	public function add_trans_receiving_menu($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'trans_receiving_menu';
		    $selected_field = 'receiving_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					// echo "<pre>",print_r($trans_raw),"</pre>";die();	
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'receiving_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

		// migrate add trans_receiving_menu_details  @Justin
	public function add_trans_receiving_menu_details($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'trans_receiving_menu_details';
		    $selected_field = 'receiving_detail_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();

				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'receiving_detail_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

	// migrate add trans_receiving_menu  @Justin
	public function add_trans_adjustment_menu($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'trans_adjustment_menu';
		    $selected_field = 'adjustment_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					// echo "<pre>",print_r($trans_raw),"</pre>";die();	
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'adjustment_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

		// migrate add trans_receiving_menu_details  @Justin
	public function add_trans_adjustment_menu_details($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'trans_adjustment_menu_details';
		    $selected_field = 'adjustment_detail_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();

				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'adjustment_detail_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

	// migrate add menu_moves @Justin
	public function add_menu_moves($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'menu_moves';
		    $selected_field = 'move_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					

						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'move_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

	// migrate add reasons @Justin
	public function add_reasons($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'reasons';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,"pos_id"=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}


	// sync_menu  -  @Justin
	public function sync_menus($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		    $table_name = 'menus';
		    $selected_field = 'menu_id,menu_code,menu_barcode,menu_name,menu_short_desc,
		    					menu_cat_id,menu_sub_cat_id,menu_sched_id,cost,reg_date, update_date,no_tax,
		    					free,inactive,costing';
		    $based_field = 'menu_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			//check first if the menu on this terminal id and branch code already exist on master DB if not copy all menus and details
			// echo "<pre>",print_r($check_if_menu_exists),"</pre>";die();
			$check_if_menu_exists = $this->main_db->select($selected_field)->get($table_name)->result();
			// var_dump(count($check_if_menu_exists));die();
		// echo count($check_if_menu_exists);die();
			if(count($check_if_menu_exists) <= '0'){
				// echo "d";
				$this->upload_menus();
				$this->upload_menu_subcategories();
				$this->upload_menu_modifiers();
				// $this->upload_modifier_group_details();
				$this->upload_menu_categories();
				return true;
				exit; // exit since there will be no action to take if the master has no copy of menu of main db
			}
		

			return true;
	}


	// upload menus from main to master  @Justin
	public function upload_menus($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		
			$table_name = 'menus';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
		    $based_field = 'menu_id';
		    $type = 'upload';

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get($table_name)->result();
				$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
					
						// $this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'main_db');


					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array() , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				// var_dump($this->main_db->trans_status());
				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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
			$check_if_menu_exists = $this->main_db->select('*')->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}
			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get($table_name)->result();
				$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
					
						// $this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'main_db');


					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array() , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				// var_dump($this->main_db->trans_status());
				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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
			$check_if_menu_exists = $this->main_db->select('*')->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get($table_name)->result();
				$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
					
						// $this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'main_db');


					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array() , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				// var_dump($this->main_db->trans_status());
				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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
			$check_if_menu_exists = $this->main_db->select('*')->get($table_name)->result();


			if(count($check_if_menu_exists) > 0){
				return true;
			}

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get($table_name)->result();
				$trans_id_raw = $this->db->select($based_field)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
					
						// $this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'main_db');


					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array() , true,false);
				
					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				// var_dump($this->main_db->trans_status());
				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				

	}

	// migrate update cashout_details  @Justin
	public function update_cashout_details($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'cashout_details';
		    $selected_field = 'cashout_details.id, cashout_details.cashout_id';
		    $based_delete_id = 'cashout_id';
		    $based_delete_id_select = 'cashout_entries.cashout_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select('cashout_details.*')->join($table_name,'cashout_entries.cashout_id = cashout_details.cashout_id','left')->where('cashout_details.sync_id', $migrate_local_id)->where('cashout_details.cashout_id is not null',null,false)->get('cashout_entries')->result();
				$trans_id_raw = $this->db->select($selected_field)->join($table_name,'cashout_entries.cashout_id = cashout_details.cashout_id','left')->where('cashout_details.sync_id', $migrate_local_id)->get('cashout_entries')->result();
				$trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'cashout_entries.cashout_id = cashout_details.cashout_id','left')->where('cashout_details.sync_id', $migrate_local_id)->get('cashout_entries')->result();
				$json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw = $this->db->select('cashout_details.*')->join($table_name,'cashout_entries.cashout_id = cashout_details.cashout_id','left')->where('cashout_entries.datetime > ', $update_date)->where('cashout_details.cashout_id is not null',null,false)->get('cashout_entries')->result();
			// echo $this->db->last_query();
				$trans_id_raw = $this->db->select($selected_field)->join($table_name,'cashout_entries.cashout_id = cashout_details.cashout_id','left')->where('cashout_entries.datetime >', $update_date)->get('cashout_entries')->result();
						// echo "trans id raw ".$this->db->last_query();

				$trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'cashout_entries.cashout_id = cashout_details.cashout_id','left')->where('cashout_entries.datetime >', $update_date)->get('cashout_entries')->result();

				// $trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				// $trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
// 			echo $update_date;

// 			echo "<pre>",print_r($trans_raw),"</pre>";
// echo "<pre>trans id raw: ",print_r($trans_id_raw),"</pre>";
// echo "<pre>trans based id raw: ",print_r($trans_based_id_raw),"</pre>";

// 			die();
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						// echo "<pre>",print_r($update_trans_wrapped),"</pre>";
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'id','db');
						// echo $this->db->last_query();
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);

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
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
					}
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

	// migrate update cashout_entries @Justin
	public function update_cashout_entries($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'cashout_entries';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
			}else{
				$trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'cashout_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

	// migrate update shifts @Justin
	public function update_shifts($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'shifts';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
			}else{
				$trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'shift_id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'shift_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}



	// migrate update shift_entries @Justin
	public function update_shift_entries($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'shift_entries';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();

			}else{
				$trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'entry_id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'entry_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}


	// migrate update trans_receiving_update_trans_receiving_menumenu  @Justin
	public function update_trans_receiving_menu($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'trans_receiving_menu';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();

			}else{
				$trans_raw = $this->db->select($selected_field)->where('`update_date` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`update_date` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'receiving_id','db');
						// $this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_tbl_batch($table_name,$add_trans_wrapped,'receiving_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

	// migrate update trans_receiving_menu  @Justin
	public function update_trans_receiving_menu_details($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'trans_receiving_menu_details';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();

			}else{
				$trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'receiving_detail_id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_tbl_batch($table_name,$add_trans_wrapped,'receiving_detail_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

	// migrate update trans_receiving_update_trans_receiving_menumenu  @Justin
	public function update_trans_adjustment_menu($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'trans_adjustment_menu';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();

			}else{
				$trans_raw = $this->db->select($selected_field)->where('`update_date` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`update_date` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'adjustment_id','db');
						// $this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_tbl_batch($table_name,$add_trans_wrapped,'adjustment_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

	// migrate update trans_receiving_menu  @Justin
	public function update_trans_adjustment_menu_details($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'trans_adjustment_menu_details';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();

			}else{
				$trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'adjustment_detail_id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_tbl_batch($table_name,$add_trans_wrapped,'adjustment_detail_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}
	// migrate update menu_moves  @Justin
	public function update_menu_moves($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'menu_moves';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();

			}else{
				$trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'move_id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'move_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

		// migrate update reasons  @Justin 08072018
	public function update_reasons($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'reasons';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
			}else{
				$trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}


	// migrate add locations @Justin
		public function add_locations($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

			    $table_name = 'locations';
			    $selected_field = 'loc_id';
				$user = $this->session->userdata('user');
				$user_id = $user['id'];
				$type = "add";

				if(empty($migration_id)){

					$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
					$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
					$json_encode  = json_encode($trans_id_raw);

				}else{

					if($automated){
						$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					}else{
						$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
						$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
						$this->main_db->trans_start(); // start update the local record with migration_id

							$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
							$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						

							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'loc_id','db');
						// if(empty($migrate_id)){
						// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
						// }else{
						// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

						// }

						$this->main_db->trans_complete();
						
					}else{
						$migration_sync_id = $migration_id;
						$is_automated = 1;
					}

					$this->main_db->trans_start(); // start the mmigration if failed it will rollback

						$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
					// echo "<pre>",print_r($trans_raw),"</pre>";
					// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

						$this->add_tbl_batch($table_name,$add_trans_wrapped);
					
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

					$this->main_db->trans_complete();

					if($this->main_db->trans_status()){
						return true;
					}else{
						return false;
					}
				
					
				}else{
					return false;
				}
					
					
		}


	
	// migrate update locations  @Justin 08072018
	public function update_locations($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'locations';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
			}else{
				$trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'loc_id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_tbl_batch($table_name,$add_trans_wrapped,'loc_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

	// migrate add locations @Justin
		public function add_suppliers($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

			    $table_name = 'suppliers';
			    $selected_field = 'supplier_id';
				$user = $this->session->userdata('user');
				$user_id = $user['id'];
				$type = "add";

				if(empty($migration_id)){

					$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
					$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
					$json_encode  = json_encode($trans_id_raw);

				}else{

					if($automated){
						$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
						$trans_id_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();

					}else{
						$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
						$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
						$this->main_db->trans_start(); // start update the local record with migration_id

							$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
							$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'supplier_id','db');
						// if(empty($migrate_id)){
						// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
						// }else{
						// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

						// }

						$this->main_db->trans_complete();
						
					}else{
						$migration_sync_id = $migration_id;
						$is_automated = 1;
					}

					$this->main_db->trans_start(); // start the mmigration if failed it will rollback

						$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
					// echo "<pre>",print_r($trans_raw),"</pre>";
					// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

						$this->add_tbl_batch($table_name,$add_trans_wrapped);
					
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

					$this->main_db->trans_complete();

					if($this->main_db->trans_status()){
						return true;
					}else{
						return false;
					}
				
					
				}else{
					return false;
				}
					
					
		}


	
	// migrate update locations  @Justin 09042018
	public function update_suppliers($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'suppliers';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
			}else{
				$trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'supplier_id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_tbl_batch($table_name,$add_trans_wrapped,'supplier_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

	// migrate add terminals @Justin
	public function add_terminals($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

			    $table_name = 'terminals';
			    $selected_field = 'terminal_id';
				$user = $this->session->userdata('user');
				$user_id = $user['id'];
				$type = "add";

				if(empty($migration_id)){

					$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
					$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
					$json_encode  = json_encode($trans_id_raw);

				}else{

					if($automated){
						$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
						$trans_id_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					}else{
						$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
						$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
						$this->main_db->trans_start(); // start update the local record with migration_id

							$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
							$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
							$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
							$this->update_tbl_batch($table_name,$update_trans_wrapped,'terminal_id','db');
						// if(empty($migrate_id)){
						// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
						// }else{
						// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

						// }

						$this->main_db->trans_complete();
						
					}else{
						$migration_sync_id = $migration_id;
						$is_automated = 1;
					}

					$this->main_db->trans_start(); // start the mmigration if failed it will rollback

						$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
					// echo "<pre>",print_r($trans_raw),"</pre>";
					// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

						$this->add_tbl_batch($table_name,$add_trans_wrapped);
					
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

					$this->main_db->trans_complete();

					if($this->main_db->trans_status()){
						return true;
					}else{
						return false;
					}
				
					
				}else{
					return false;
				}
					
					
		}


	
	// migrate update terminals  @Justin 09042018
	public function update_terminals($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'terminals';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
			}else{
				$trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_tbl_batch($table_name,$add_trans_wrapped,'terminal_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}


		// migrate add trans_receivings  @09172018 @Justin
	public function add_trans_receivings($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'trans_receivings';
		    $selected_field = 'receiving_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					// echo "<pre>",print_r($trans_raw),"</pre>";die();	
					
				}				
			}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'receiving_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

		// migrate add trans_receiving_menu_details  @Justin
	public function add_trans_receiving_details($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'trans_receiving_details';
		    $selected_field = 'receiving_detail_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'receiving_detail_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}


		public function update_trans_receivings($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'trans_receivings';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();

			}else{
				$trans_raw = $this->db->select($selected_field)->where('`update_date` > ',$update_date)->get($table_name)->result();
				$trans_id_raw = $this->db->select($selected_field)->where('`update_date` > ',$update_date)->get($table_name)->result();
				$json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'receiving_id','db');
						// $this->update_tbl($table_name,array('`update_date` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 	$this->update_tbl_batch($table_name,$add_trans_wrapped,'receiving_id');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

	// migrate update trans_receiving_menu  @Justin
	public function update_trans_receiving_details($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){
		
		    $table_name = 'trans_receiving_details';
			$selected_field = 'trans_receiving_details.receiving_detail_id, trans_receivings.receiving_id';
		 	$based_delete_id = 'receiving_id';
		 	$based_delete_id_select = 'trans_receivings.receiving_id';
		    $selected_field = '*';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				// $trans_raw = $this->db->select($selected_field)->where('sync_id',$migrate_local_id)->get($table_name)->result();
				$trans_raw = $this->db->select('trans_receiving_details.*')->join($table_name,'trans_receivings.receiving_id = trans_receiving_details.receiving_id','left')->where('trans_receiving_details.sync_id',$migrate_local_id)->where('trans_receiving_details.receiving_id is not null',null,false)->get('trans_receivings')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_receivings.receiving_id = trans_receiving_details.receiving_id','left')->where('trans_receiving_details.sync_id', $migrate_local_id)->get('trans_receivings')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_receivings.receiving_id = trans_receiving_details.receiving_id','left')->where('trans_receiving_details.sync_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_receivings')->result();

			}else{
				// $trans_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
				// $trans_id_raw = $this->db->select($selected_field)->where('`datetime` > ',$update_date)->get($table_name)->result();
			    $trans_raw = $this->db->select('trans_receiving_details.*')->join($table_name,'trans_receivings.receiving_id = trans_receiving_details.receiving_id','left')->where('`trans_receivings.update_date` > ',$update_date)->where('trans_receiving_details.receiving_id is not null',null,false)->get('trans_receivings')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_receivings.receiving_id = trans_receiving_details.receiving_id','left')->where('`trans_receivings.update_date` > ',$update_date)->get('trans_receivings')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_receivings.receiving_id = trans_receiving_details.receiving_id','left')->where('`trans_receivings.update_date` > ',$update_date)->group_by($based_delete_id)->get('trans_receivings')->result();

				// $json_encode  = json_encode($trans_id_raw);
					
			}				
			
			// echo $this->db->last_query();die();
			// echo "<pre>",print_r($trans_raw),"</pre>";die();


			if(!empty($trans_based_id_raw) && isset($trans_based_id_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					// echo "aa";die();
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'receiving_id','db');
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
						// $this->update_tbl($table_name,array('`receiving_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
								// echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					// $this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'sync_id','main_db');

				 // 	$this->update_trans_tbl_batch($table_name,$add_trans_wrapped,'receiving_detail_id');
					// $this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					// $this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
					if(!empty($trans_based_id_raw)){
						$delete_args = array();
						foreach($trans_based_id_raw as $b){
							$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
						}

						$this->delete_trans_misc_batch($table_name,$delete_args);
						// echo $this->main_db->last_query();die();
						// delete_trans_misc_batch($table_name=null,$args=null
						if(!empty($add_trans_header)){
					 		$this->add_tbl_batch($table_name,$add_trans_wrapped);
						}

						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
					}
				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

	// migrate add shifts  @Justin
	public function add_transfer_split($migration_id=NULL, $update_date = NULL, $automated=false,  $migrate_local_id=NULL){

		    $table_name = 'transfer_split';
		    $selected_field = 'id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
					$trans_id_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id,"pos_id"=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id,'pos_id'=>TERMINAL_ID),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}



	/***
	** check the last log of migration if no logs it will return false @ Justin
	*/ 


	public function check_last_log(){
		$last_logs = $this->db->order_by('sync_id desc')->limit('1')->get_where('sync_logs')->result();
		$last_log = false;
		// echo $this->main_db->last_query();die();
		// echo "<pre>",print_r($last_logs),"</pre>";die();

		if(isset($last_logs[0]) && !empty($last_logs)){
			$last_log = $last_logs[0];
		}

		return $last_log;


	}



	/***
	** executes the sync by running the functions listed in $functions_array @ Justin
	*/ 
	public function execute_sync(){
		$last_log = $this->check_last_log();
		$finish_log =  $this->get_finish_log();

		// add records from db to main
		$add_functions_array = array('add_trans_sales','add_trans_sales_charges','add_trans_sales_discounts','add_trans_sales_items','add_trans_sales_local_tax',
			'add_trans_sales_loyalty_points','add_trans_sales_menu_modifiers','add_trans_sales_menus','add_trans_sales_no_tax','add_trans_sales_payments',
			'add_trans_sales_tax','add_trans_sales_zero_rated','add_trans_refs'	,'add_users','add_tables','add_item_moves','add_read_details','add_customers_bank','add_logs','add_coupons',
			'add_cashout_details','add_cashout_entries','add_shifts','add_shift_entries','add_trans_receiving_menu','add_trans_receiving_menu_details','add_trans_receivings','add_trans_receiving_details','add_menu_moves','add_reasons','add_locations','add_suppliers','add_terminals','add_transfer_split','add_trans_sales_menu_submodifiers','add_trans_sales_payment_fields'
			,'add_trans_gc','add_trans_gc_charges','add_trans_gc_discounts','add_trans_gc_local_tax',
			'add_trans_gc_loyalty_points','add_trans_gc_gift_cards','add_trans_gc_no_tax','add_trans_gc_payments','add_trans_gc_tax','add_trans_gc_zero_rated','add_trans_gc_payment_fields','add_gift_cards'
			,'add_store_zread','add_trans_adjustment_menu','add_trans_adjustment_menu_details'
		);


		// update records from db to main
			// $update_functions_array = array('update_trans_sales');
		$update_functions_array = array('update_trans_sales','update_trans_sales_charges','update_trans_sales_discounts',
										'update_trans_sales_items','update_trans_sales_local_tax','update_trans_sales_loyalty_points',
										'update_trans_sales_menu_modifiers','update_trans_sales_menus','update_trans_sales_menu_submodifiers','update_trans_sales_no_tax',
										'update_trans_sales_payments','update_trans_sales_tax','update_trans_sales_zero_rated',
										'update_cashout_details','update_cashout_entries','update_shifts','update_shift_entries',
										'update_trans_receiving_menu','update_trans_receiving_menu_details','update_trans_receivings','update_trans_receiving_details','update_menu_moves','update_reasons','update_locations','update_suppliers','update_terminals','update_trans_adjustment_menu','update_trans_adjustment_menu_details');

		// sync records of menu from db to main
		$menu_functions_array = array('sync_menus');


		// $add_functions_array = array('add_read_details');

		// $update_functions_array = array();

		// $menu_functions_array = array();


		//check back log before migrating new data
		// echo "<pre>",print_r($this->check_backlogs()),"</pre>";die();
		if($this->check_backlogs()) {
			foreach($add_functions_array as $func){

				if($last_log){
					$migrate_id = $last_log->sync_id;
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
							$migrate_id = $finish_log->sync_id;
							$last_update = $finish_log->migrate_date;
							$this->$func($migrate_id,$last_update);
						}else{
					
							$migrate_id = $last_log->sync_id;
							$last_update = '2018-12-01 00:00:00';//$last_log->migrate_date;
							$this->$func($migrate_id,$last_update);
						}

					}else{
						$this->$func();
					}
				}
			}


			foreach($menu_functions_array as $func){

				if($last_log){
					// $migrate_id = $last_log->sync_id;
					$migrate_id = $last_log->sync_id;
					$last_update = $last_log->migrate_date;
					$this->$func($migrate_id,$last_update);

				}else{
					$this->$func();
				}
			}

			$this->finish_log();
			$this->cleanup_logs();
		}

		return json_encode($last_log);

		// echo "<pre>",print_r($last_log),"</pre>";die();
	}

	/** if there are pending transactions process them through automation - Justin 11/9/2017**/
	public function check_backlogs(){
		// echo "d";die();
			$pending_logs = $this->main_db->order_by('migrate_date','desc')->get_where('sync_logs',array('status'=>'0'),10)->result(); // limit to 10
			// echo $this->main_db->last_query();die();
			if(!empty($pending_logs)){
				foreach ($pending_logs as $key => $logs) {
			// echo "<pre>",print_r($logs),"</pre>";die();
					$trans_type = $logs->transaction;
					$trans_action = $logs->type;
					$migrate_local_id = $logs->sync_id;
					$migrate_id = $logs->sync_id;
					$migrate_date = $logs->migrate_date;
					$function = $trans_action."_".$trans_type;

					if(method_exists($this , $function)){ // check if the function class is existing in this model if yes call the function
							// echo $function;die();
						$this->$function($migrate_id,$migrate_date,true,$migrate_local_id );
					}
				
				}
			}

			return true;
	}


	public function finish_log(){
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$migration_id = $this->add_tbl('sync_logs',array("status"=>"1","type"=>'finish',"transaction"=> 'sync_logs','user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
			$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"1","type"=>'finish',"transaction"=> 'sync_logs','user_id'=>$user_id,'pos_id'=>TERMINAL_ID,'sync_id'=>$migration_id)); // status 0 pending ,  1 success , 3 failed
						
	}

	public function get_finish_log(){
			$last_logs = $this->db->order_by('sync_id desc')->limit('1')->get_where('sync_logs',array('type'=>"finish"))->result();
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



	public function test(){
		$pending_logs = $this->main_db->get_where('sync_logs',array('sync_id'=>'18'))->result();

		return $pending_logs;
	}


	/**
		@jx
		cleanup_logs() - clean up sync_logs(ipos to main) and master_logs(main to cloud) for logs older than 3 months from date now , to not clog the database size 
		02272019
	**/
	public function cleanup_logs(){
		$this->main_db->where('migrate_date <=', "NOW() - INTERVAL 3 MONTH",FALSE);
  		$this->main_db->delete('master_logs');

  		$this->main_db->where('migrate_date <=', "NOW() - INTERVAL 3 MONTH",FALSE);
  		$this->main_db->delete('sync_logs');

  		$this->db->where('migrate_date <=', "NOW() - INTERVAL 3 MONTH",FALSE);
  		$this->db->delete('sync_logs');

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

	// migrate update trans_sales_menu_submodifiers  -  @Justin
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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_submod_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					// $add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

	// migrate update trans_sales_menu_submodifiers  -  @Justin
	public function update_trans_sales_menu_submodifiers($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		 $table_name = 'trans_sales_menu_submodifiers';
		   $selected_field = 'trans_sales_menu_submodifiers.sales_submod_id, trans_sales_menu_submodifiers.sales_id';
		    $based_delete_id = 'sales_id';
		    $based_delete_id_select = 'trans_sales.sales_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

		

			if($automated){
				$trans_raw = $this->db->select('trans_sales_menu_submodifiers.*')->join($table_name,'trans_sales.sales_id = trans_sales_menu_submodifiers.sales_id','left')->where('trans_sales_menu_submodifiers.sync_id', $migrate_local_id)->where('trans_sales_menu_submodifiers.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat($trans_raw,$based_delete_id);

				$json_encode  = json_encode($trans_id_raw);
				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('trans_sales_menu_modifiers.sync_id', $migrate_local_id)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('trans_sales_menu_modifiers.sync_id', $migrate_local_id)->group_by($based_delete_id)->get('trans_sales')->result();
				// $json_encode  = json_encode($trans_id_raw);
			}else{
				$trans_raw_bd = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_submodifiers.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
				$trans_raw = $this->db->select('trans_sales_menu_submodifiers.*')->join($table_name,'trans_sales.sales_id = trans_sales_menu_submodifiers.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->where('trans_sales_menu_submodifiers.sales_id is not null',null,false)->get('trans_sales')->result();
				$trans_id_raw = $this->object_flat($trans_raw,$based_delete_id);
				$trans_based_id_raw = $this->object_flat(array_merge($trans_raw_bd,$trans_raw),$based_delete_id);


				// $trans_id_raw = $this->db->select($selected_field)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->get('trans_sales')->result();
				// $trans_based_id_raw = $this->db->select($based_delete_id_select)->join($table_name,'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id','left')->where('`trans_sales.update_date` > ',$update_date)->group_by($based_delete_id)->get('trans_sales')->result();
				// // echo $this->db->last_query();die();

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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>"update","transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed					
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');
						// echo "<pre>",print_r($update_trans_wrapped),"</pre>";die();
						// $this->update_tbl($table_name,array('`datetime` > ' => "'".$update_date."'" ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);
						// $this->update_tbl($table_name,array('`sales_id`  ' => "'".$update_date."'" ,'`branch_code`' => "'".BRANCH_CODE."'", '`terminal_id`' => TERMINAL_ID ),array('sync_id'=>$migration_id),NULL,NULL,'main_db',true);

					

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}



				$this->main_db->trans_start(); // start the migration if failed it will rollback

					// $add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id) , true,false);
					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id, 'pos_id'=>TERMINAL_ID) , true,false);
					
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
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');
				}

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				if(!empty($trans_based_id_raw)){ // if whole items deleted from the trans_sales_items
					if(!empty($trans_based_id_raw)){
						$delete_args = array();
						foreach($trans_based_id_raw as $b){
							$delete_args[$based_delete_id][]  = $b->$based_delete_id ;
						}

						$this->delete_trans_misc_batch($table_name,$delete_args);
					}
				}
				return false;
			}
				
				
	}

	public function add_trans_gc($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
			$type="add";
			$table_name = 'trans_gc';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];

			if(empty($migration_id)){

				$trans_raw = $this->db->select('`gc_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`terminal_id` ,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed`, `total_gross`, `total_discount`, `total_charges`, `zero_rated`, `no_tax`, `tax`, `local_tax`')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select('gc_id')->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('`gc_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`terminal_id` ,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed`, `total_gross`, `total_discount`, `total_charges`, `zero_rated`, `no_tax`, `tax`, `local_tax`')->where('sync_id',$migrate_local_id)->get($table_name)->result();
						// echo "<pre> automated: ",print_r($this->db->last_query()),"</pre>";

				}else{
					$trans_raw = $this->db->select('`gc_id` , `mobile_sales_id` ,`type_id` ,`trans_ref` ,`void_ref` ,`type` ,`user_id` ,`shift_id` ,
							`terminal_id` ,`customer_id` ,`total_amount` ,`total_paid` ,`memo` ,`table_id` ,`guest` ,`datetime`,
							`update_date` ,`paid` ,`reason` ,`void_user_id` ,`printed` ,`inactive` ,`waiter_id` ,`split` ,
							`serve_no` ,`billed`, `total_gross`, `total_discount`, `total_charges`, `zero_rated`, `no_tax`, `tax`, `local_tax`')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select('gc_id')->where('sync_id' ,NULL)->get($table_name)->result();
					// echo "<pre>",print_r($this->db->last_query()),"</pre>";die();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}
		
			// echo $this->db->last_query();
			// echo "<pre>trans_raw: ",print_r($trans_raw),"</pre>";
			// 			echo "<pre>trans_raw id: ",print_r($trans_id_raw),"</pre>";

			// die();


			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=>$table_name,"src_id"=>$json_encode,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
					// if(empty($migrate_id)){
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_id','db');
						// $this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_id','db');
					// 	// $this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					// echo "add trans sales: ".$this->db->last_query()."<br>";die();
					// echo "<pre>",print_r($this->db->last_query()),"</pre>";die();
					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_id,'branch_code'=> BRANCH_CODE, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				// var_dump($this->main_db->trans_status());
				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
		// }

	}

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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
					$json_encode  = json_encode($trans_id_raw);
					
				}				
			}



			if(!empty($trans_raw) && isset($trans_raw)){

				$add_trans_header = $trans_raw; // get the record
				
				$is_automated = 0;
				$record_count = count($trans_raw);

				// if not automated add to logs else don't add since we already have migration_id
				if(!$automated){
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_charge_id','db');
					// if(empty($migrate_id)){
						// $this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
						// $this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

			// echo "<pre>",print_r($trans_raw),"</pre>";die();
				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_disc_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id',$migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->or_where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'sales_local_tax_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id',$migration_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
					if(empty($migrate_id)){
						$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					}else{
						$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					}

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				if(count($trans_id_raw) > 10000) // records that exceeds 20000 clogs the migration , temporary solution @justinx02022018
						$json_encode  = "";// json_encode($trans_id_raw);
				else
					$json_encode =  json_encode($trans_id_raw);
						
			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
					if(count($trans_id_raw) > 10000) // records that exceeds 20000 clogs the migration , temporary solution @justinx02022018
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_gift_card_id','db');
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
				
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_no_tax_id','db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_payment_id','db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					

						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_tax_id','db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
				
	}

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

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id',$migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_zero_rated_id','db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
				return false;
			}
				
	}

	public function add_trans_gc_payment_fields($migration_id=NULL, $update_date = NULL, $automated=false, $migrate_local_id=NULL){
		// var_dump($migration_id);
		// var_dump($update_date);
		// var_dump($automated);

		// die();
		    $table_name = 'trans_gc_payment_fields';
		    $selected_field = 'payment_id,gc_id';
			$user = $this->session->userdata('user');
			$user_id = $user['id'];
			$type = "add";

			if(empty($migration_id)){

				$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
				$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
				$json_encode  = json_encode($trans_id_raw);

			}else{

				if($automated){
					$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
				}else{
					$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
					$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
					$this->main_db->trans_start(); // start update the local record with migration_id

						$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
						$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
					
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'payment_id','db');	
					// if(empty($migrate_id)){
					// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
					// }else{
					// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);
					// }

					$this->main_db->trans_complete();
					
				}else{
					$migration_sync_id = $migration_id;
					$migration_id = $migrate_local_id;
					$is_automated = 1;
				}

				$this->main_db->trans_start(); // start the mmigration if failed it will rollback

					$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id, 'pos_id'=>TERMINAL_ID) , true,false);
				// echo "<pre>",print_r($trans_raw),"</pre>";
				// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

					$this->add_tbl_batch($table_name,$add_trans_wrapped);
				
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
					$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

				$this->main_db->trans_complete();

				if($this->main_db->trans_status()){
					return true;
				}else{
					return false;
				}
			
				
			}else{
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

					$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
					$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
					$json_encode  = json_encode($trans_id_raw);

				}else{

					if($automated){
						$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
						$trans_id_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();

					}else{
						$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
						$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
						$this->main_db->trans_start(); // start update the local record with migration_id

							$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
							$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'gc_id','db');
						// if(empty($migrate_id)){
						// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
						// }else{
						// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

						// }

						$this->main_db->trans_complete();
						
					}else{
						$migration_sync_id = $migration_id;
						$is_automated = 1;
					}

					$this->main_db->trans_start(); // start the mmigration if failed it will rollback

						$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id,'pos_id'=>TERMINAL_ID) , true,false);
					// echo "<pre>",print_r($trans_raw),"</pre>";
					// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

						$this->add_tbl_batch($table_name,$add_trans_wrapped);
					
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

					$this->main_db->trans_complete();

					if($this->main_db->trans_status()){
						return true;
					}else{
						return false;
					}
				
					
				}else{
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

					$trans_raw = $this->db->select('*')->get_where($table_name,array('sync_id' => NULL))->result();
					$trans_id_raw = $this->db->select($selected_field)->get_where($table_name,array('sync_id' => NULL))->result();
					$json_encode  = json_encode($trans_id_raw);

				}else{

					if($automated){
						$trans_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();
						$trans_id_raw = $this->db->select('*')->where('sync_id', $migrate_local_id)->get($table_name)->result();

					}else{
						$trans_raw = $this->db->select('*')->where('sync_id' ,NULL)->get($table_name)->result();
						$trans_id_raw = $this->db->select($selected_field)->where('sync_id' ,NULL)->get($table_name)->result();
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
						$this->main_db->trans_start(); // start update the local record with migration_id

							$migration_sync_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id)); // status 0 pending ,  1 success , 3 failed
							$migration_id = $this->add_tbl('sync_logs',array("status"=>"0","type"=>$type,"transaction"=> $table_name,'user_id'=>$user_id),NULL,'db'); // status 0 pending ,  1 success , 3 failed
						
						$update_trans_wrapped = $this->formulate_object($trans_id_raw , array('sync_id' => $migration_id) , true,false);
						$this->update_tbl_batch($table_name,$update_trans_wrapped,'zread_id','db');
						// if(empty($migrate_id)){
						// 	$this->update_tbl($table_name,array('sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db');
						// }else{
						// 	$this->update_tbl($table_name,array( 'sync_id' => NULL),array('sync_id'=>$migration_id),NULL,NULL,'db',true);

						// }

						$this->main_db->trans_complete();
						
					}else{
						$migration_sync_id = $migration_id;
						$is_automated = 1;
					}

					$this->main_db->trans_start(); // start the mmigration if failed it will rollback

						$add_trans_wrapped = $this->formulate_object($add_trans_header , array('sync_id' => $migration_sync_id,'pos_id'=>TERMINAL_ID) , true,false);
					// echo "<pre>",print_r($trans_raw),"</pre>";
					// 				echo "<pre>",print_r($add_trans_wrapped),"</pre>";die();

						$this->add_tbl_batch($table_name,$add_trans_wrapped);
					
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_sync_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'main_db');
						$this->update_tbl('sync_logs',array("sync_id"=>$migration_id),array('status'=>"1","is_automated"=>$is_automated),NULL,NULL,'db');

					$this->main_db->trans_complete();

					if($this->main_db->trans_status()){
						return true;
					}else{
						return false;
					}
				
					
				}else{
					return false;
				}
					
					
		}


}

?>