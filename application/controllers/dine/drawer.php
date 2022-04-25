<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Drawer extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('dine/drawer_helper');
		$this->load->helper('core/on_screen_key_helper');
		$this->load->helper('core/string_helper');
		$this->load->model('dine/clock_model');
		$this->load->model('site/site_model');
		$this->load->model('dine/cashier_model');
	}
	public function index(){
		$drawer = array();
     	$data = $this->syter->spawn(null);
		sess_initialize('count_cart');
		$totals = $this->get_over_all_total(false);
		$overAllTotal = $totals['overAllTotal'];
		$get_shift = $this->clock_model->get_shift_id(null,null);
		if(count($get_shift) > 0){
			
			$shift = $get_shift[0]->shift_id;
			$args['trans_sales.shift_id'] = $shift;
			$args['trans_sales.inactive'] = 0;
			$args['trans_sales_payments.payment_type != '] = 'cash';
			$joinTables['trans_sales'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id');
			$result = $this->site_model->get_tbl('trans_sales_payments',$args,array(),$joinTables,true,'trans_sales_payments.*',null,null);
			foreach ($result as $value) {
				$drawer[$value->payment_type] = $value->amount;
			}
			// echo "<pre>",print_r($drawer),"</pre>";die();
		}

        $data['code'] = drawerMain($overAllTotal,$drawer);
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
		$data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/drawer.php';
        $data['use_js'] = 'drawerJs';
        $this->load->view('load',$data);
	}
	public function guide_cash_count(){
		$drawer = array();
     	$data = $this->syter->spawn(null);
		sess_initialize('count_cart');
		$totals = $this->get_over_all_total(false);
		$overAllTotal = $totals['overAllTotal'];
		$get_shift = $this->clock_model->get_shift_id(null,null);
		if(count($get_shift) > 0){
			
			$shift = $get_shift[0]->shift_id;
			$args['trans_sales.shift_id'] = $shift;
			$args['trans_sales.inactive'] = 0;
			$args['trans_sales_payments.payment_type != '] = 'cash';
			$joinTables['trans_sales'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id');
			$result = $this->site_model->get_tbl('trans_sales_payments',$args,array(),$joinTables,true,'trans_sales_payments.*',null,null);
			foreach ($result as $value) {
				$drawer[$value->payment_type] = $value->amount;
			}
			// echo "<pre>",print_r($drawer),"</pre>";die();
		}

        $data['code'] = drawerMain($overAllTotal,$drawer,true);
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
		$data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/drawer.php';
        $data['use_js'] = 'drawerJs';
        $this->load->view('load',$data);
	}
	public function deposit($amount=0){
		$memo = $this->input->post('memo');
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
        $date = $this->site_model->get_db_now('sql');
        $get_shift = $this->clock_model->get_shift_id(null,$user_id);
        $error = "";
        $code = "";
        $id = "";
        if(count($get_shift) > 0){
        	$shift = $get_shift[0]->shift_id;
            if($amount > 0){
	            $items = array(
	                'shift_id'=>$shift,
	                'amount'=>$amount,
	                'user_id'=>$user_id,
	                'trans_date'=>$date,
	                'memo'=>$memo
	            );
	            $id = $this->clock_model->insert_cashin($items);

	            $log_user = $this->session->userdata('user');
                $this->logs_model->add_logs('Drawer',$log_user['id'],$log_user['full_name']." Cash in ".$amount,null);

	            $this->make->sDivRow(array('class'=>'orders-list-div-btnish','style'=>'margin-left:1px;margin-right:1px;'));
	            	$this->make->sDivCol(10,'left',0);
	            		$this->make->sDiv(array('style'=>'margin-left:20px;'));
		            		$this->make->H(3,'Amount: '.num($amount),array('style'=>'margin-top:10px;'));
		            		$this->make->H(5,strtoupper($user['username'])." ".sql2DateTime($date)." ".$memo );
	            		$this->make->eDiv();
	            	$this->make->eDivCol();
	            	$this->make->sDivCol(2);
	            		// $this->make->button(fa('fa-times'),array('id'=>'del-'.$id,'class'=>'btn-block manager-btn-red','style'=>'margin-top:10px;'),'primary');
	            	$this->make->eDivCol();
	            $this->make->eDivRow();
	            $code = $this->make->code();

	            $this->print_shift_entries($get_shift[0]->shift_id,'deposit');
            }
            else
	            $error = "Invalid Amount.";
        }
        else{
            $error = "There is no shift.";
        }
        echo json_encode(array('error'=>$error,'code'=>$code,'id'=>$id));
	}
	public function withdraw($amount=0){
		$memo = $this->input->post('memo');
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
        $date = $this->site_model->get_db_now('sql');
        $get_shift = $this->clock_model->get_shift_id(null,$user_id);
        $error = "";
        $code = "";
        $id = "";
        if(count($get_shift) > 0){
        	$shift = $get_shift[0]->shift_id;
            if($amount > 0){
	            $items = array(
	                'shift_id'=>$shift,
	                'amount'=>$amount * -1,
	                'user_id'=>$user_id,
	                'trans_date'=>$date,
	                'memo'=>$memo
	            );
	            $id = $this->clock_model->insert_cashin($items);

	            $log_user = $this->session->userdata('user');
                $this->logs_model->add_logs('Drawer',$log_user['id'],$log_user['full_name']." Cash Out ".($amount * -1),null);

	            $this->make->sDivRow(array('class'=>'orders-list-div-btnish','style'=>'margin-left:1px;margin-right:1px;'));
	            	$this->make->sDivCol(10,'left',0);
	            		$this->make->sDiv(array('style'=>'margin-left:20px;'));
		            		$this->make->H(3,'Amount: '.num($amount),array('style'=>'margin-top:10px;'));
		            		$this->make->H(5,strtoupper($user['username'])." ".sql2DateTime($date)." ".$memo );
	            		$this->make->eDiv();
	            	$this->make->eDivCol();
	            	$this->make->sDivCol(2);
	            		// $this->make->button(fa('fa-times'),array('id'=>'del-'.$id,'class'=>'btn-block manager-btn-red','style'=>'margin-top:10px;'),'primary');
	            	$this->make->eDivCol();
	            $this->make->eDivRow();
	            $code = $this->make->code();

	            $this->print_shift_entries($get_shift[0]->shift_id,'withdraw');
            }
            else
	            $error = "Invalid Amount.";
        }
        else{
            $error = "There is no shift.";
        }
        echo json_encode(array('error'=>$error,'code'=>$code,'id'=>$id));
	}
	public function print_shift_entries($shift_id,$mode='deposit'){
		$this->load->helper('core/string_helper');

		$constraint_array = array(
			'shifts.shift_id' => $shift_id,
			($mode == 'deposit' ? 'amount >= ' : 'amount <') => 0,
		);
		$shift_entries = $this->clock_model->get_shift_entries(null,$constraint_array);

		$print_str = align_center('CASH DRAWER '.strtoupper($mode)."S",36," ")."\r\n"
			."Printed at ".date('Y-m-d H:i:s')."\r\n\r\n";

		if (!empty($shift_entries)) {
			$print_str .= "Cashier   : ".$shift_entries[0]->username."\r\n"
						 ."Terminal  : [".$shift_entries[0]->terminal_code."] ".$shift_entries[0]->terminal_name."\r\n"
				         ."Check-in  : ".$shift_entries[0]->check_in."\r\n"
				         ."Check-out : ".$shift_entries[0]->check_out."\r\n\r\n";

			$sums = 0;
			foreach ($shift_entries as $k => $v) {
				$print_str .= append_chars("   ".($k+1),'right',8," ").append_chars(date('H:i:s',strtotime($v->trans_date)),"right",13," ")
    				.append_chars(number_format(abs($v->amount),2),"left",15," ")."\r\n";
    			if($v->memo){
    				$print_str .= append_chars("",'right',4," ").append_chars($v->memo,"right",30," ")."\r\n";
    			}
    			$sums += $v->amount;
			}

			$print_str .= "\r\n".append_chars("Total Cash ".ucwords($mode)."s","right",21," ")
    			.append_chars(number_format(abs($sums),2),"left",15," ")."\r\n\r\n";
		} else {
			$print_str .= "No information available\r\n\r\n";
		}

		$print_str .= append_chars("","right",36,"-")."\r\n\r\n";

		if($mode == 'withdraw'){
			$print_str .= append_chars("Received by :","right",36,"_")."\r\n\r\n";
			$print_str .= append_chars("Manager :","right",36,"_")."\r\n\r\n";
		}

		$filename = "shift_entries.txt";
        $fp = fopen($filename, "w+");
        fwrite($fp,$print_str);
        fclose($fp);

        $batfile = "print.bat";
        $fh1 = fopen($batfile,'w+');
        $root = dirname(BASEPATH);

        // fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
        $battxt =  "NOTEPAD /P \"".realpath($root."/".$filename)."\"";
        $pet = $this->cashier_model->get_pos_settings();
        $open_drawer_printer = $pet->open_drawer_printer;
        if($open_drawer_printer != ""){
            $battxt = "NOTEPAD /PT \"".realpath($root."/".$filename)."\" \"".$open_drawer_printer."\"  ";   
        } 
        fwrite($fh1,$battxt);
        fclose($fh1);
        session_write_close();
        exec($batfile);
        session_start();
        unlink($filename);
        unlink($batfile);
	}
	public function delete_entry($entry_id=null){
		$this->clock_model->delete_shift_entries($entry_id);
	}
	public function drops($type='deposit'){
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
        $date = $this->site_model->get_db_now('sql');
        $get_shift = $this->clock_model->get_shift_id(null,$user_id);
        $code = "";
        $id = array();
        if(count($get_shift) > 0){
        	$shift = $get_shift[0]->shift_id;
        	$args = array(
        		"shift_entries.shift_id"=>$shift
        		// 'shift_entries.amount' => array('operator'=>(string)'>','use'=>'where','val'=>0,'third'=>false),
        	);
        	if($type == 'withdraw'){
        		$args['shift_entries.amount'] = array('operator'=>(string)'<','use'=>'where','val'=>0,'third'=>false);
        	}
        	elseif($type == 'deposit'){
        		$args['shift_entries.amount'] = array('operator'=>(string)'>','use'=>'where','val'=>0,'third'=>false);
        	}

        	$entries = $this->clock_model->get_shift_entries(null,$args);
        	$ctr = 1;
        	foreach ($entries as $res) {
        		$this->make->sDivRow(array('class'=>'orders-list-div-btnish','style'=>'margin-left:1px;margin-right:1px;'));
	            	$this->make->sDivCol(10,'left',0);
	            		$this->make->sDiv(array('style'=>'margin-left:20px;'));
		            		if($ctr == 1){
		            			if($type == 'deposit'){
		            				$this->make->H(3,'Starting Amount: '.num($res->amount),array('style'=>'margin-top:10px;'));
			            		}
				            	else{
				            		if($type == 'curr-shift')
			            				$this->make->H(3,'Starting Amount: '.num($res->amount),array('style'=>'margin-top:10px;'));
				            		else
			            				$this->make->H(3,'Amount: '.num($res->amount),array('style'=>'margin-top:10px;'));
				            	}
			            	}
			            	else{
			            		if($type == 'deposit' || $type == 'withdraw')
			            			$this->make->H(3,'Amount: '.num($res->amount),array('style'=>'margin-top:10px;'));
			            		else{
			            			$txt = 'Deposit';
			            			if($res->amount < 0)
			            				$txt = 'Withdraw';
			            			$this->make->H(3,$txt.' Amount: '.num($res->amount),array('style'=>'margin-top:10px;'));
			            		}
			            	}

		            		$this->make->H(5,strtoupper($res->username)." ".sql2DateTime($res->trans_date)." ".$res->memo);
	            		$this->make->eDiv();
	            	$this->make->eDivCol();
	            	$this->make->sDivCol(2);
	            		// if($type == 'deposit'){
		            	// 	if($ctr > 1){
			            // 		$this->make->button(fa('fa-times'),array('id'=>'del-'.$res->entry_id,'class'=>'btn-block manager-btn-red','style'=>'margin-top:10px;'),'primary');
		            	// 	}
		            	// }
		            	// else{
		            	// 	if($type == 'deposit' || $type == 'withdraw')
			            // 		$this->make->button(fa('fa-times'),array('id'=>'del-'.$res->entry_id,'class'=>'btn-block manager-btn-red','style'=>'margin-top:10px;'),'primary');
		            	// }
	            	$this->make->eDivCol();
	            $this->make->eDivRow();
	            $id[] = $res->entry_id;
	            $ctr++;
        	}
            $code = $this->make->code();
        }
        echo json_encode(array('code'=>$code,'ids'=>$id));
	}
	public function count_totals($type=null,$asJson=true){
		$cart = sess('count_cart');
		$amt = 0;
		$overAll = 0;
		if(count($cart) > 0){
			foreach ($cart as $key => $row) {
				if($type != null){
					if($row['type'] == $type){
						$amt += $row['amount'];
					}
				}
				$overAll += $row['amount'];
			}
		}
		if($asJson)
			echo json_encode(array('total'=>$amt,'overall'=>$overAll));
		else
			return array('total'=>$amt,'overall'=>$overAll);
	}
	public function load_payments(){
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
		// $date = $this->site_model->get_db_now('sql');
		// $get_shift = $this->clock_model->get_shift_id(date2Sql($date),$user_id);
		$get_shift = $this->clock_model->get_shift_id(null,$user_id);
		$row = array();
		if(count($get_shift) > 0){
			
			$shift = $get_shift[0]->shift_id;
			$args['trans_sales.shift_id'] = $shift;
			$args['trans_sales.inactive'] = 0;
			$args['trans_sales_payments.payment_type != '] = 'cash';
			$joinTables['trans_sales'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id');
			$result = $this->site_model->get_tbl('trans_sales_payments',$args,array(),$joinTables,true,'trans_sales_payments.*',null,null);
			foreach ($result as $res) {
				if($res->payment_type != 'chit' && $res->payment_type != 'cash'){
					$type = $res->payment_type;
					if($res->payment_type == 'gc')
						$type = 'gift';
					else if($res->payment_type == 'deposit')
						$type = 'cust-deposits';
				}
				else{
					if($res->payment_type == 'chit'){
						$type = 'chit';
					}
					else
						$type = null;
				}
				if($type != null){
					if($type == 'chit'){
						$row[] = array('type'=>'chit','amount'=>$res->amount);	
					}
					else if($type == 'cust-deposits'){
						$cust_id = $res->reference;
						$cargs['cust_id'] = $cust_id;
						$select = 'fname,mname,lname,suffix';
						$cresult = $this->site_model->get_tbl('customers',$cargs,array(),'',true,$select);
						if(count($cresult) > 0){
							$cres = $cresult[0];
							$full_name = ucwords(mb_strtolower($cres->fname." ".$cres->mname." ".$cres->lname." ".$cres->suffix));
							$row[] = array('type'=>$type,'amount'=>$res->amount,'ref'=>$full_name);
						}
					}
					else{
						if($type == 'cod'){
							$ref="";
							if($res->amount > $res->to_pay){
								$row[] = array('type'=>$type,'amount'=>$res->to_pay,'ref'=>$ref);
							}else{
								$row[] = array('type'=>$type,'amount'=>$res->amount,'ref'=>$ref);
							}
						}else{
							$ref = $res->reference;
							if($type == 'credit' || $type == 'debit')
								$ref = $res->approval_code;
							$row[] = array('type'=>$type,'amount'=>$res->amount,'ref'=>$ref);
						}
					}
					if(count($row) > 0)
						sess_initialize('count_cart',$row);				
				}
			}
		}
		echo json_encode($row);
	}
	public function save_count($overall=0,$print=false){
        $user = $this->session->userdata('user');
		$user_id = $user['id'];
        $date = $this->site_model->get_db_now('sql');
        // $get_shift = $this->clock_model->get_shift_id(date2Sql($date),$user_id);
        $get_shift = $this->clock_model->get_shift_id(null,$user_id);
        $shift = null;
        $error = "";
        $js_rcp = "";

        if(count($get_shift) > 0){
        	 if(MIGRATION_VERSION == '2'){
                    // run_main_exec();
                    // run_master_exec();
                 
            }
	        $shift = $get_shift[0]->shift_id;
	        $shift_out = $get_shift[0]->cashout_id;
	        $count = $this->count_totals(null,false);
	        $cart = sess('count_cart');
         	$items = array(
                'user_id'=>$user_id,
                'terminal_id'=>TERMINAL_ID,
                'count_amount'=>$count['overall'],
                'drawer_amount'=>$overall,
                'trans_date'=>$date
            );
            if($shift_out != null){
            	$cash_id = $shift_out;
		        $this->clock_model->update_cashout($items,$cash_id);
		        $this->clock_model->delete_cashout_details($cash_id);
            }
            else{
		        $cash_id = $this->clock_model->insert_cashout($items);
            }

            $log_user = $this->session->userdata('user');
            $this->logs_model->add_logs('Drawer',$log_user['id'],$log_user['full_name']." Cash Count. Drawer Amount: ".$overall." Count Amount: ".$count['overall'],null);

	        if(count($cart) > 0){
		        $det = array();
		        foreach ($cart as $id => $row) {
		        	$deno = null;
		        	$ref = "";
		        	if(isset($row['ref'])){
		        		$ref = $row['ref'];
		        	}
		        	if($row['type'] == 'cash')
		        		$deno = $ref ;
		        	$det[] = array(
		        		"cashout_id" => $cash_id,
		        		"type" => $row['type'],
		        		"reference" => $ref ,
		        		"total" => $row['amount'],
		        		"denomination" => $deno,

		        	);
		        }
		        if(count($det) > 0 )
		        	$this->clock_model->insert_cashout_details($det);
	        }
	        #UPDATE SHIFT OUT
			$items_update = array(
				'cashout_id'=>$cash_id,
				// 'check_out'=>$date
			);
			$this->clock_model->update_clockout($items_update,$shift);
	        $print = $print === 'true'? true: false;
	        if($print){
				$js_rcp = $this->print_cashout_details($cash_id);
	        }
	    }
	    else{
	    	$error = "There is no shift found. Clock in first.";
	    }
        echo json_encode(array('error'=>$error,'js_rcp'=>$js_rcp));
	}
	public function show_denominations(){
		$this->load->model('dine/settings_model');
		$deno = $this->settings_model->get_denominations();
		$ids = array();
		$cart = sess('count_cart');
		foreach ($deno as $res) {
			$qty = 0;
			foreach ($cart as $key => $row) {
				if($row['type'] == 'cash'){
					if($row['ref'] == $res->val){
						$qty += $row['amount'] / $res->val;
					}
				}
			}
			$this->make->sDivRow(array('ref'=>$res->id,'val'=>$res->value,'id'=>'deno-btn-'.$res->id,'class'=>'orders-list-div-btnish','style'=>'margin-left:1px;margin-right:1px;cursor:pointer;'));
	        	$this->make->sDivCol(2,'left',0);
	        		$this->make->img(base_url().'img/money-icon.png',array('style'=>'height:80px;margin:5px;'));
	        	$this->make->eDivCol();
	        	$this->make->sDivCol(4,'left',0);
	        		$this->make->sDiv(array('style'=>'margin-left:20px;'));
	            		$this->make->H(3,num($res->value),array('style'=>'margin-top:10px;'));
	            		$this->make->H(3,$res->desc,array('style'=>'margin-top:10px;'));
	        		$this->make->eDiv();
	        	$this->make->eDivCol();
	        	$this->make->sDivCol(4,'left',0);
            		$this->make->H(3,'QTY',array('style'=>'margin-top:10px;'));
            		$this->make->H(3,num($qty),array('class'=>'deno-qty','style'=>'margin-top:10px;'));
	        	$this->make->eDivCol();
	        	$this->make->sDivCol(2,'right',0);
	        		$this->make->button(fa('fa-times'),array('val'=>$res->value,'id'=>'del-cash-'.$res->id,'class'=>'btn-block manager-btn-red','style'=>'margin-top:10px;'),'primary');
	        	$this->make->eDivCol();
	        $this->make->eDivRow();
	        $ids[] = $res->id;
		}
		$code = $this->make->code();
		echo json_encode(array('code'=>$code,'ids'=>$ids));
	}
	public function merge_count(){
		$cart = sess('count_cart');
		$last_type = null;
		$new_cart = array();
		foreach ($cart as $key => $row) {
			if($row['type'] != 'check' && $row['type'] != 'gift'){
				$id = max(array_keys($cart)) + 1;
				if(count($new_cart) > 0){
					$id = max(array_keys($new_cart)) + 1;
				}
				$new_cart[$id] = $row;
			}
			else{
				$new_cart[$key] = $row;
			}
		}
		sess_initialize("count_cart",$new_cart);
		return $new_cart;
	}
	public function del_cash_in_count_cart($val){
		$cart = sess('count_cart');
		$ids = array();
		foreach ($cart as $key => $row) {
			if($row['type'] == 'cash'){
				if($row['ref'] == $val){
					sess_delete('count_cart',$key);
					$ids[] = $key;
				}
			}
		}
		echo json_encode(array('ids'=>$ids));
	}
	public function get_over_all_total($asJson=true){
		$user = $this->session->userdata('user');
		$user_id = $user['id'];
        // $date = $this->site_model->get_db_now('sql');
        // $get_shift = $this->clock_model->get_shift_id(date2Sql($date),$user_id);
        $get_shift = $this->clock_model->get_shift_id(null,$user_id);
        $shift = null;
        $total_drops = 0;
        $total_deps = $total_withs = array();
        $total_sales = 0;
        $overAllTotal = 0;


        if(count($get_shift) > 0){
	        $shift = $get_shift[0]->shift_id;

	        //check if theres a 0 shift id in trans_sales to update it instantly
	        $tdate = $get_shift[0]->check_in;
	        $args2 = array(
	        	"trans_sales.type_id"=>SALES_TRANS,
	        	"trans_sales.shift_id"=>'0',
	        	"trans_sales.inactive"=>0
	        );
	        $args2["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
	        $args2["trans_sales.datetime >= '".$tdate."'"] = array('use'=>'where','val'=>null,'third'=>false);
	        $trans2 = $this->cashier_model->get_trans_sales(null,$args2);

	        foreach($trans2 as $val){
	        	$where = array('sales_id'=>$val->sales_id);
	            $items = array('shift_id'=>$shift);
	            $this->cashier_model->table_updater($items, $where, 'trans_sales');
	        }
	        /////////////////////////////////


	        $entries = $this->clock_model->get_shift_entries(null,array("shift_entries.shift_id"=>$shift));
	        if(count($entries) > 0){
		        foreach ($entries as $res) {
		        	$total_drops += $res->amount;

		        	if ($res->amount > 0)
		        		$total_deps[] = $res;
		        	else
		        		$total_withs[] = $res;
		        }
		        $overAllTotal += $total_drops;
	        }
	        $args = array(
	        	"trans_sales.type_id"=>SALES_TRANS,
	        	"trans_sales.shift_id"=>$shift,
	        	"trans_sales.inactive"=>0
	        );
	        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
	        $trans = $this->cashier_model->get_trans_sales(null,$args);

	        if(count($trans) > 0){
	        	foreach ($trans as $res) {
		        	$total_sales += $res->total_paid;
		        }
		        $overAllTotal += $total_sales;
	        }
        }
        if($asJson) {
        	echo json_encode(array(
        		'total_drops'=>$total_drops,
        		'total_deps'=>$total_deps,
        		'total_withs'=>$total_withs,
        		'total_sales'=>$total_sales,
        		'overAllTotal'=>$overAllTotal
        	));
		}
        else {
        	return array(
        		'total_drops'=>$total_drops,
        		'total_deps'=>$total_deps,
        		'total_withs'=>$total_withs,
        		'total_sales'=>$total_sales,
        		'overAllTotal'=>$overAllTotal
        	);
        }
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
                    "email"=>$res->email,
                    "website"=>$res->website,
                    "layout"=>base_url().'uploads/'.$res->image
                  );
       }
       if($asJson)
            echo json_encode($det);
        else
            return $det;
    }
	public function print_cashout_details($cashout_id){
    	if (!isset($cashout_id)) {
    		show_404();
    		return false;
    	}
    	$print_str = "";
    	$cashout_header = $this->cashier_model->get_cashout_header($cashout_id); // returns row
    	$cashout_details = $this->cashier_model->get_cashout_details($cashout_id); // returns rows array
    	$totals = $this->get_over_all_total(false);
    	$sum_deps = $sum_withs = 0;

    	/* Header */
    	$branch = $this->get_branch_details(false);
    	$wrap = wordwrap($branch['name'],35,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= align_center($v,PAPER_WIDTH," ")."\r\n";
        }

        $wrap = wordwrap($branch['address'],35,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= align_center($v,PAPER_WIDTH," ")."\r\n\r\n";
        }
        $accrdn = "";
        if(isset($branch['accrdn'])){
        	$accrdn = $branch['accrdn'];
        }

        $print_str .= 
        align_center('TIN: '.$branch['tin'],PAPER_WIDTH," ")."\r\n"
        .align_center('ACCRDN: '.$accrdn,PAPER_WIDTH," ")."\r\n"
        .align_center('MIN:'.$branch['machine_no'],PAPER_WIDTH," ")."\r\n"
        // .align_center('SN: '.$branch['serial'],38," ")."\r\n"
        .align_center('PERMIT: '.$branch['permit_no'],PAPER_WIDTH," ")."\r\n\r\n";

    	$print_str .= align_center("CASHOUT DATA",PAPER_WIDTH)."\r\n\r\n".
    		"Cashier  : ".$cashout_header->username."\r\n".
    		"Terminal : [".$cashout_header->terminal_code."] ".$cashout_header->terminal_name."\r\n".
    		"Time in  : ".$cashout_header->check_in."\r\n";
    	if($cashout_header->check_out != null)
    		$print_str .= "Time out : ".$cashout_header->check_out."\r\n";

    	$print_str .= "\r\n";

    	/* Cash Deposits */
    	$print_str .= "Cash Deposits\r\n";
    	foreach ($totals['total_deps'] as $k => $dep) {
    		$print_str .= append_chars("   ".($k+1),'right',PAPER_DET_COL_1," ").append_chars(date('H:i:s',strtotime($dep->trans_date)),"right",PAPER_DET_COL_2," ")
    			.append_chars(number_format($dep->amount,2),"left",PAPER_DET_COL_3," ")."\r\n";
    		$sum_deps += $dep->amount;
    	}
    	if ($sum_deps > 0)
    		$print_str .= append_chars("------------","left",PAPER_WIDTH," ")."\r\n";
    	$print_str .= append_chars("Total Cash Deposits","right",PAPER_WIDTH," ")
    		.append_chars(number_format($sum_deps,2),"left",PAPER_WIDTH," ")."\r\n\r\n";

    	/* Cash Withdrawals */
    	$print_str .= "Cash Withdrawals\r\n";
    	foreach ($totals['total_withs'] as $k => $with) {
    		$print_str .= append_chars("   ".($k+1)." ".date('H:i:s',strtotime($with->trans_date)),"right",PAPER_TOTAL_COL_1," ")
    			.append_chars(number_format(abs($with->amount),2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
    		$sum_withs += abs($with->amount);
    	}
    	if ($sum_withs > 0)
    		$print_str .= append_chars("------------","left",PAPER_WIDTH," ")."\r\n";
    	$print_str .= append_chars("Total Cash Withdrawals","right",PAPER_TOTAL_COL_1," ")
    		.append_chars(number_format($sum_withs,2),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";


    	/* Drawer */
    	$print_str .= append_chars("Expected Drawer amount","right",PAPER_TOTAL_COL_1," ").append_chars(number_format($cashout_header->drawer_amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
    	$print_str .= append_chars("Actual Drawer amount","right",PAPER_TOTAL_COL_1," ").append_chars(number_format($cashout_header->count_amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
    	$print_str .= append_chars("-------------","right",PAPER_WIDTH," ")."\r\n";
    	$print_str .= append_chars("Variance","right",PAPER_TOTAL_COL_1," ").append_chars(number_format(abs($cashout_header->drawer_amount - $cashout_header->count_amount),2),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";


    	/* Cashout Details */
    	$ptypes = array();
    	$print_str .= "\r\n\r\nCashout Breakdown\r\n";
    	foreach ($cashout_details as $value) {
    		if (!empty($value->denomination))
    			$mid = $value->denomination." X ".($value->total/$value->denomination);
    		elseif (!empty($value->reference))
    			$mid = $value->reference." ";
    		else $mid = "";

    		if($value->type == 'cust-deposits'){
	    		$print_str .= append_chars("[".ucwords('deposit')."] ","right",PAPER_TOTAL_COL_1," ").
	    			append_chars(number_format($value->total,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";    			
	    		$print_str .= append_chars("   ".ucwords(mb_strtolower($mid)),"right",PAPER_TOTAL_COL_1," ").
	    			append_chars("","left",PAPER_TOTAL_COL_2," ")."\r\n";    			
    		}
    		else{			
	    		$print_str .= append_chars("[".ucwords($value->type)."] ".$mid,"right",PAPER_TOTAL_COL_1," ").
	    			append_chars(number_format($value->total,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
    		}
    		
     		if(!isset($ptypes[$value->type])){
     			 $ptypes[$value->type] = array('total'=>$value->total);
     		}else{
     			$row = $ptypes[$value->type];
                $row['total'] += $value->total;
 
                $ptypes[$value->type] = $row;         
     		}
    	}


    	$print_str .= "\r\n".append_chars("","right",PAPER_WIDTH,"-");

    	$print_str .= "\r\nTOTAL\r\n";
     	foreach ($ptypes as $k => $v) {
     		$mid = "";
     		$print_str .= append_chars("[".ucwords($k)."] ".$mid,"right",PAPER_TOTAL_COL_1," ").
 	    			append_chars(number_format($v['total'],2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
     	}
 

 		if(PRINT_VERSION && PRINT_VERSION == 'V2'){
 			// echo "asd";die();
 			   try {
                    // Enter the share name for your USB printer here
                    $connector = new WindowsPrintConnector(BILLING_PRINTER);

                    $printer = new Printer($connector);
                    $printer->setJustification(Printer::JUSTIFY_CENTER);

                    if(RECEIPT_LOGO_ENABLE){
                        $logo = EscposImage::load(RECEIPT_LOGO, false);
                        $printer->bitImage($logo);
                    }
                    $printer->text($print_str);
                    $printer->cut();

                        // $printer->text("TEST");
                        //  $printer->cut();
                    /* Close printer */
                    $printer->close();
                } catch (Exception $e) {
                    echo json_encode(array( 'error'=>"Couldn't print to this printer: " . $e -> getMessage() . "\n"));exit;
                }
 		}else if(PRINT_VERSION && PRINT_VERSION=='V3'){
 			return $this->html_print($print_str);
 		}else{
	    	$filename = "cashout.txt";
	        $fp = fopen($filename, "w+");
	        fwrite($fp,$print_str);
	        fclose($fp);

	        $batfile = "print.bat";
	        $fh1 = fopen($batfile,'w+');
	        $root = dirname(BASEPATH);

	        fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
	        fclose($fh1);
	        session_write_close();
	        exec($batfile);
	        // exec($filename);
	        session_start();
	        unlink($filename);
	        unlink($batfile);
 		}
    }
    public function open_drawer(){
    	$log_user = $this->session->userdata('user');
        $this->logs_model->add_logs('Drawer',$log_user['id'],$log_user['full_name']." Open Drawer",null);
        $time = $this->site_model->get_db_now();


    	$print_str = $log_user['full_name']." Open Drawer \r\n";
    	$print_str .= $time;

    	if(PRINT_VERSION && PRINT_VERSION == 'V3'){
    		echo $this->html_print($print_str);
    	}else{
    		$filename = "open.txt";
	        $fp = fopen($filename, "w+");
	        fwrite($fp,$print_str);
	        fclose($fp);

	        $batfile = "print.bat";
	        $fh1 = fopen($batfile,'w+');
	        $root = dirname(BASEPATH);

	        $battxt =  "NOTEPAD /P \"".realpath($root."/".$filename)."\"";
	        $pet = $this->cashier_model->get_pos_settings();
	        $open_drawer_printer = $pet->open_drawer_printer;
	        if($open_drawer_printer != ""){
	            $battxt = "NOTEPAD /PT \"".realpath($root."/".$filename)."\" \"".$open_drawer_printer."\"  ";   
	        }   


	        fwrite($fh1,$battxt);
	        fclose($fh1);
	        session_write_close();
	        exec($batfile);
	        // exec($filename);
	        session_start();
	        unlink($filename);
	        unlink($batfile);
    	}    	
    }

    public function html_print($print_str='',$is_bill = false){
        $js_rcp = '<div style="width:270px;"><pre>'.$print_str.'</pre></div>';

        if($is_bill){
            $js_rcp .= $this->align_center('<img src="'.base_url().'qrs/01DE48QL1.png""  style="display: block;
                      margin-left: auto;
                      margin-right: auto;
                      width: 50%;">',PAPER_WIDTH," ")."\r\n";
            // $js_rcp .= '<img src="'.base_url().'qrs/01DE48QL1.png" >';
        }

        $js_rcp .= '<style>
                @media print {
                html, body, div, span, applet, object, iframe,
                h1, h2, h3, h4, h5, h6, p, blockquote, pre,
                a, abbr, acronym, address, big, cite, code,
                del, dfn, em, img, ins, kbd, q, s, samp,
                small, strike, strong, sub, sup, tt, var,
                b, u, i, center,
                dl, dt, dd, ol, ul, li,
                fieldset, form, label, legend,
                table, caption, tbody, tfoot, thead, tr, th, td,
                article, aside, canvas, details, embed, 
                figure, figcaption, footer, header, hgroup, 
                menu, nav, output, ruby, section, summary,
                time, mark, audio, video {
                    margin: 0 !important;
                    padding: 0 !important;
                    border: 0 !important;
                    font-size: 100% !important;
                    font: inherit !important;
                    vertical-align: baseline !important;
                }
                  .page-header, .wrapper, #noty_topRight_layout_container, .progress-bar{ display:none; }
                  #print-rcp{ display:block;  }
                  @page { margin: 0; !important }
                  body { margin: 0px !important ;
                         padding:0px !important ;
                          }
                  div { margin: 0px !important ; 
                        padding:0px !important ;
                    }
                }
                pre {
                    font-family:Lucida Console !important ;
                    font-size:11.5px !important ;
                    overflow-x: auto !important ;
                    // text-align:justify;
                    white-space: pre-wrap !important ;
                    white-space: -moz-pre-wrap !important ;
                    white-space: -pre-wrap !important ;
                    white-space: -o-pre-wrap !important ;
                    word-wrap: break-word !important ;
                    word-break:keep-all !important ;
                 }
                </style>';
        $js_rcp .= '<script>print()</script>';

        return $js_rcp;
    }

}