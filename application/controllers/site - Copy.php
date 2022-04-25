<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/dine/reads.php");

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\TsplPrinter;
   
class Site extends Reads {
	public function loader($method=null,$params=array()){
        session_start();
        $load = false;
        if(isset($_SESSION['load'])){
	        $load = true;        	
        }
        if($load == false && $method != 'load' || $method != 'get_load' || $method != 'go_load'){
        	if(LOADER)
        		header("Location:".base_url()."site/load");
        	else
        		header("Location:".base_url()."site/login");
        }
        else
	        call_user_func_array(array($this,$method), $params);
    }
	public function index(){
		$data = $this->syter->spawn('dashboard');
		$data['code'] = "";
		$this->load->view('page',$data);
	}
	public function load(){
		$this->load->helper('site/site_load_helper');
		$data = $this->syter->spawn('load',false,false);
		$data['code'] = makeLoader();
		$data['noNavbar'] = true; /*displays the navbar. Uncomment this line to hide the navbar.*/
		$data['html_bg_class'] = 'lockscreen';
		$data['load_js'] = 'site/load';
		$data['use_js'] = 'loadJs';
		$this->load->view('cashier',$data);
	}
	public function backup(){
		$time = $this->site_model->get_db_now();
		$got_backup=false;
		if (!file_exists("backup/")) {
            mkdir("backup/", 0777, true);
        }
        $fileB = date('Ymd',strtotime($time)).".sql";
        if(!file_exists('backup/'.$fileB)){
			$this->load->dbutil();
			$prefs = array(
				"format" => 'txt',
				'ignore' => array('ci_sessions','logs')
			);
			$backup =& $this->dbutil->backup($prefs); 
			$this->load->helper('file');
			write_file('backup/'.$fileB, $backup);
			$got_backup = true; 
        }
        return $got_backup;
	}
	public function backup_main(){
		$set = $this->cashier_model->get_pos_settings();
		$backup_folder = "C:/xampp/htdocs/dine/backup";
		$extra_backup_folder = "";
		if(iSetObj($set,'backup_path')){
		    $extra_backup_folder = iSetObj($set,'backup_path');
		}
		// if (!file_exists($backup_folder)) { 
		//     $backup_folder = "C:/xampp/htdocs/dine/backup";
		// }    
		$file_path = $backup_folder."/main";
		if (!file_exists($file_path)) {   
		    mkdir($file_path, 0777, true);
		}

		if($extra_backup_folder != ""){
			$exfile_path = $extra_backup_folder."/main";
			if (!file_exists($exfile_path)) {   
			    mkdir($exfile_path, 0777, true);
			}
		}


		$fileB = "main_db.sql";
		$this->db = $this->load->database('main', TRUE);		
		$this->load->dbutil();
		$prefs = array(
		    "format" => 'txt',
		    'ignore' => array('ci_sessions','logs')
		);
		$backup =& $this->dbutil->backup($prefs); 
		$this->db = $this->load->database('default', TRUE);		
		$this->load->helper('file');
		write_file($file_path.'/'.$fileB, $backup);
		if($extra_backup_folder != ""){
			write_file($exfile_path.'/'.$fileB, $backup);
		}
		$backedUp = false;
		if(!file_exists($file_path.'/'.$fileB)){
			$backedUp = true;
		}
		return $backedUp;	
	}	
	public function go_load(){
		session_start();
		$this->load->model('dine/clock_model');
		$this->load->model('site/site_model');
		$this->load->model('dine/cashier_model');
		$this->load->model('dine/setup_model');

		start_load(0,'Loading Database...');
			sleep(1);
			$error = "";
			$time = $this->site_model->get_db_now();
			$details = $this->setup_model->get_branch_details();
			
			$open_time = $details[0]->store_open;
			$close_time = $details[0]->store_close;

			$datenow = date('Y-m-d',strtotime($time));
			// echo $datenow; die();
			$exp_date = '2022-07-01';

			// $expire_date = '2020-01-23 10:12:13'; //Output of the date we mentioned above 
			$warning_date = date('Y-m-d', strtotime('-30 days', strtotime($exp_date)));
			// echo $a; //Correct result of 7 days past, 2020-01-16 10:12:13
			// die();
			update_load(5,'Cheking...');
			if($datenow >= $exp_date){

				$root = dirname(BASEPATH);
		        $root = $root.'/application';
				$this->delete_directory($root);

				// echo 'asdfasfsdaf'; die();
			}

			if($datenow >= $warning_date){
				update_load(100,'Redirecting...');
				echo json_encode(array('error'=>''));        		
				$_SESSION['load'] = true;
				// $_SESSION['problem'] = 'unclosed_shifts';
				$_SESSION['problem_code'] = 'subs_expiry';
    //     		return false;				


				$_SESSION['problem'] = 'POS system require maintenance. Please contact your provider to avoid further issues.';
				return false;
			}

			$last_zread_res = $this->cashier_model->get_latest_read_date(Z_READ);
			// echo $this->db->last_query(); die();
			$got_z_read = true;
			$check_date = null;
			if(!empty($last_zread_res)){
			    $check_date = $last_zread_res->maxi;
			    if($last_zread_res->maxi == null)
			        $got_z_read = false;
			}
			else{
			    $got_z_read = false;
			}
			if($check_date != null){
	        	$first_shift = $this->site_model->get_tbl('shifts',array('check_in >'=>$check_date),array('check_in'=>'asc'),null,true,'*',null,1);
	            if(count($first_shift) > 0){
	                $check_date = $first_shift[0]->check_in;
	            }
	            else{
	    		    $shifts_today = $this->cashier_model->get_next_x_read_details($check_date);
	    		    if(count($shifts_today) > 0){
		    		    foreach ($shifts_today as $res) {
		    		        $check_date = $res->scope_from;
		    		        break;
		    		    }		        	
	    		    }
	    		    else{
	    		    	$yesterday = date('Y-m-d',strtotime($time . "-1 days"));
	    		    	$check_date = date('Y-m-d',strtotime($check_date ));
	    		    	$date1 = strtotime($yesterday);
	    		    	$date2 = strtotime($check_date);
	    		    	if($date1 == $date2){
	    		    		$check_date = $time;
	    		    	}
	    		    }
	            }

			}
			else{
			    if($got_z_read){
			        $shifts_today = $this->cashier_model->get_next_x_read_details(date2Sql($time));
			        foreach ($shifts_today as $res) {
			            $check_date = $res->scope_from;
			            break;
			        }
			    }
			    else{
			        $first_shift = $this->site_model->get_tbl('read_details',array('read_type'=>1),array('scope_from'=>'asc'),null,true,'*',null,1);
			        if(count($first_shift) > 0){
			            $check_date = $first_shift[0]->scope_from;
			        }
			    }
			}
			if($check_date == null){
				$first_shift = $this->site_model->get_tbl('shifts',array(),array('check_in'=>'asc'),null,true,'*',null,1);
			    if(count($first_shift) > 0){
			        $check_date = $first_shift[0]->check_in;
			    }
			}
			if($check_date != null){
				$check_date_time = $check_date;
				$check_date = date2Sql($check_date);
				if(BATTERY_DATE){
					// echo $check_date_time.' >= '.date('m/d/Y H:i:s',strtotime($time)); die();
					if(date('m/d/Y H:i:s',strtotime($check_date_time)) > date('m/d/Y H:i:s',strtotime($time))){
						// echo 'pasok'; die();
						update_load(100,'Redirecting...');
						echo json_encode(array('error'=>'battery'));        		
						$_SESSION['load'] = true;
						$_SESSION['problem_code'] = 'battery_prob';
						$_SESSION['problem'] = 'Your current date was less than your last transaction date ('.$check_date.'). Change your date and restart the application before you can start.';
		        		return false;
					}
				}
			}
			else{
				#######################################
				## MEANS FIRST TIME THE POS IS OPENED
				update_load(100,'Redirecting...');
				echo json_encode(array('error'=>$error));
				$_SESSION['load'] = true;
				return false;		
			}
			$pos_start = date2SqlDateTime($check_date." ".$open_time);
			$oa = date('a',strtotime($open_time));
			$ca = date('a',strtotime($close_time));
			$pos_end = date2SqlDateTime($check_date." ".$close_time);
			if($oa == $ca){
				$pos_end = date('Y-m-d H:i:s',strtotime($pos_end . "+1 days"));
			}

			$right_now = $time;
			$str_pos_end = strtotime($pos_end);
			$str_pos_now = strtotime($right_now);
			$go_check = false;
			if($str_pos_now > $str_pos_end){
				$go_check = true;
			}
			$got_back_up = false;
			if($go_check){
				update_load(20,'Checking Shifts...');
					sleep(1);
		        	$time2 = $this->site_model->get_db_now();
                    $yesterday = date('Y-m-d',strtotime($time2. "-1 days"));
		        	$unclosed_shifts = $this->clock_model->get_shift_id(null,null,$yesterday);
		        	if(count($unclosed_shifts) > 0){
        				update_load(100,'Redirecting...');
						echo json_encode(array('error'=>'unclosed'));        		
						$_SESSION['load'] = true;
						$_SESSION['problem'] = 'unclosed_shifts';
						$_SESSION['problem_code'] = 'unclosed_shifts';
		        		return false;				
		        	}

					update_load(40,'Loading...');
					// update_load(40,'Processing End Of Day...');
					sleep(1);
					$range = createDateRangeArray($check_date,date2Sql($time));
					$ctr = 1;

					$date_wo_read = array();
					foreach ($range as $rd) {
						if( date2Sql($time) != date2Sql($rd)){
							if($ctr == 1){
								update_load(50,'Backing Up Database...');
									sleep(1);
									// $backup = $this->backup();
									$got_back_up = true;
							}

			        		$read_date = $rd;
			        		$start = date2SqlDateTime($read_date." ".$open_time);
			        		$end = date2SqlDateTime($read_date." ".$close_time);
			        		$oa = date('a',strtotime($open_time));
							$ca = date('a',strtotime($close_time));
			        		if($oa == $ca){
			        			$end = date('Y-m-d H:i:s',strtotime($end . "+1 days"));
			        		}
			        		
			        		$this->cashier_model->db = $this->load->database('default', TRUE);
			        		$this->site_model->db = $this->load->database('default', TRUE);

			        		$args2 = array();
				        	$args2["read_date = DATE('".date2Sql($read_date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
				        	$args2["read_type = 2"] = array('use'=>'where','val'=>null,'third'=>false);
				        	$getzread = $this->cashier_model->get_z_read(null,$args2);

				        	if(count($getzread) == 0){
				        		$date_wo_read[] = array('date'=>$read_date);
				        	}

			      //   		$zread_id = $this->go_zread($asJson=false,$start,$end,$read_date);
			        		
			      //   		if(MALL_ENABLED){
				     //            if(MALL == "robinsons"){
				     //                $rob = $this->send_to_rob($zread_id,$increment);
				     //                if($rob['error'] == ""){
				     //                    site_alert("File:".$rob['file']." Sales File successfully sent to RLC server.",'success');
				     //                }
				     //                else{
				     //                    site_alert($rob['error'],'error');
				     //                }
				     //            }
				     //            else if(MALL == "ortigas"){
				     //                $this->ortigas_file($zread_id);
				     //            }
				     //            else if(MALL == "araneta"){
				     //                $this->araneta_file($zread_id);
				     //                $last_date = date("Y-m-t", strtotime($read_date));
				     //                $now_date = date("Y-m-d", strtotime($read_date));
				     //                if($last_date == $now_date){
				     //                    $this->araneta_month_file($now_date);
				     //                }
				     //            }
				     //            else if (MALL == 'megamall') {
				     //                $this->sm_file($read_date,$zread_id);
				     //            }
				     //            else if (MALL == 'stalucia') {
				     //                $this->stalucia_file($zread_id);
				     //            }
				     //            else if (MALL == 'ayala'){
				     //                $this->ayala_file($zread_id);
 								// }
 				    //             else if (MALL == 'cbmall') {
				     //                $this->cbmall_file($read_date,$zread_id);
				     //            }
				     //            else if (MALL == 'megaworld') {
				     //                $this->megaworld_file($zread_id);
				     //            }
				     //        }

						}	
						$ctr++;
					}

					if(count($date_wo_read) != 0){
		        		update_load(100,'Redirecting...');
		        		$last_id = count($date_wo_read) - 1;
		        		$datestxt = $date_wo_read[0]['date'];
		        		if(count($date_wo_read) > 1){
		        			$datestxt .= ' to '.$date_wo_read[$last_id]['date'];
		        		}
						// echo $datestxt; die();
						echo json_encode(array('error'=>'unclosed_day'));        		
						$_SESSION['load'] = true;
						$_SESSION['problem'] = 'Process End of Day for date/s ('.$datestxt.') and then restart the application before you can start a shift.';
						$_SESSION['problem_code'] = 'unclosed_day';
						$_SESSION['unread_dates'] = $date_wo_read;
		        		return false;

		        	}
				
			}
        	$read_from = $check_date;
	    //     if(strtotime(sql2Date($read_from)) < strtotime(sql2Date($time))){
		   //      update_load(60,'Refreshing Database...');
					// sleep(1);
					// if(!$got_back_up){
					// 	update_load(70,'Backing Up Database Before Clearing...');
					// 		sleep(1);
					// 		$backup = $this->backup();
					// }
					// else{
					// 	$backup = $got_back_up;
					// }
					// if($backup){
					// 	$this->load->library('../controllers/dine/main');
					// 	$this->main->remove_recent_data(null,$read_from);
					// }		
	    //     }
		sleep(1);
		$this->session->unset_userdata('user');

		if(MALL_ENABLED){
			if(MALL == "robinsons"){
				update_load(75,'Checking Unset Robinson Files...');	
				sleep(1);
				// $rlc = $this->cashier_model->get_rob_path();
		  //       $path =  $rlc->rob_path;
		  //       if($path != ""){
		  //       	$ftp_server = $path;
		  //           $can = (pingAddress($ftp_server));
		  //           if($can){
			 //            $unsent = $this->cashier_model->get_unsent_rob_files();
				//         if(count($unsent) > 0){
				// 			$ftp_conn = ftp_connect($ftp_server) ;
				// 			if($ftp_conn){
							   update_load(95,'Sending Unsent Files...');
							   sleep(1);
				// 			   foreach ($unsent as $res) {
				// 			        $reads = $this->cashier_model->get_last_z_read(Z_READ,date2Sql($res->date_created));
				// 			        foreach ($reads as $red) {
				// 			            $unsent_id = $red->id;
				// 			        }
				// 			        $rob = $this->send_to_rob($unsent_id,false);
				// 			   }#FOREACH
				// 			}
			 //        	}
		  //           }
		  //       }
	    	}
	    }
	    update_load(80,'Backing Up Database...');
		// $backed_up = $this->backup_main();
		update_load(100,'Redirecting...');
		sleep(1);
		$_SESSION['load'] = true;
		echo json_encode(array('error'=>$error));
	}
	public function get_load(){
		$load = sess('site_load');
		$text = sess('site_load_text');
		echo json_encode(array('load'=>$load,'text'=>$text));
	}
	public function login($shift=false,$end_shift=false){
		$this->load->model('site/site_model');
		$this->load->model('dine/cashier_model');
		$this->load->model('dine/clock_model');
		$this->load->helper('core/on_screen_key_helper');
		$this->load->helper('dine/login_helper');		
		
		$data = $this->syter->spawn(null,false);
		
		if(!defined('PRODUCT_KEY')){
			$data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css');
			$data['add_js'] = array('js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js','js/virtual_keyboard.js','js/on_screen_keys.js');
			$data['code'] = makePosKey();
			$data['load_js'] = 'site/login';
			$data['use_js'] = 'PosKeyJs';

			$this->load->view('login',$data);
			return false;
		}

		$this->site_model->delete_tbl('table_activity',array('pc_id'=>PC_ID));

		if(isset($data['problem']) && $data['problem'] == 'unclosed_shifts'){
			$shift =  true;
		}
        $unclosed_shifts = $this->clock_model->get_shift_id();
		$error_code = null;
        $error = "";
        $rot_shifts = array();
        $shifts_open = array();
        $users = array();
        $rot_users = array();
        $time = $this->site_model->get_db_now();
        $today = sql2Date($time);

        if(isset($data['problem_code']) && $data['problem_code'] == 'battery_prob'){
			$error = $data['problem'];
			$error_code = 'battery_prob';
		}

		if(isset($data['problem_code']) && $data['problem_code'] == 'unclosed_day'){
			$error = $data['problem'];
			$error_code = 'unclosed_day';
		}

        if(count($unclosed_shifts) > 0){
	    	foreach ($unclosed_shifts as $res) {
	    		$check = sql2Date($res->check_in);
    			if(strtotime($check) < strtotime($today)){
    				$rot_users[] = $res->user_id;
    			}
    			else	
		    		$users[] = $res->user_id;
	    	}
	    	if($shift != false){
		    	if(count($rot_users) > 0){
		    		$error = "You must first close the old shifts before starting.";
		    		$rot_shifts = $this->clock_model->get_user_details($rot_users);
		    	}
	    	}
	    	if(count($users) > 0)
		    	$shifts_open = $this->clock_model->get_user_details($users);	    		
		}

        // unset($_SESSION['load']);
		$splashes = $this->site_model->get_image(null,null,'splash_images');

		// if(MALL_ENABLED){
		// 	if(MALL == "robinsons"){
		// 		$now = $this->site_model->get_db_now();
		// 		$reads = $this->site_model->get_tbl('read_details',array('read_type'=>2,'read_date'=>date2Sql($now)));
		// 		if(count($reads) > 0){
		// 			$error = "You cant Transact Because you already Have END OF DAY(ZREAD) for this day. Please Contact Robinsons Admin.";
		// 			$_SESSION['problem'] = 'Already Have ZREAD';
		// 		}
		// 	}
		// }

		$data['code'] = makeLoginPage($error,$shifts_open,$rot_shifts,$end_shift,$splashes,$error_code);
		$data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css');
		$data['add_js'] = array('js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js','js/virtual_keyboard.js','js/on_screen_keys.js');
		$data['load_js'] = 'site/login';
		$data['use_js'] = 'loginJs';
		$this->load->view('login',$data);
	}
	public function get_login_unclosed_shifts(){
		$this->load->model('dine/clock_model');
		$unclosed_shifts = $this->clock_model->get_shift_id();
		$unclosed = array();
		$users = array();
		foreach ($unclosed_shifts as $res) {
			if(!isset($unclosed[$res->user_id])){
				$unclosed[$res->user_id] = array(
					'shift_id' => $res->shift_id,
					'check_in' => $res->check_in,
				);
				$users[] = $res->user_id;
			}
		}
		$users_result = $this->clock_model->get_user_details($users);
		foreach ($users_result as $res) {
			if(isset($unclosed[$res->id])){
				$shf = $unclosed[$res->id];
				$shf['username'] = substr($res->username,0,10).'...';
				$shf['name'] = ucwords($res->fname." ".$res->mname." ".$res->lname." ".$res->suffix);
				$unclosed[$res->id] = $shf;
			}
		}
		echo json_encode($unclosed);
	}
	public function go_login(){
		$this->load->model('site/site_model');
		$this->load->model('dine/clock_model');
		$now = $this->site_model->get_db_now();
		$time = date('H:i',strtotime($now) );

		$open = "9:00";
		$close = "4:00";

		$error_msg = "";
		$send_redirect = "";
		
		$error = 0;
		// if(MALL_ENABLED){
		// 	if(MALL == 'robinsons'){
		// 		if(strtotime($open) >= strtotime($time) && strtotime($close) < strtotime($time)){
		// 			$error_msg = "POS is locked in this time. You can open the POS  from 9:00 AM to 4:00 AM next day";
		// 			$error = 1;
		// 		}
				
		// 	}
		// }

		if($error == 0){
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$pin = $this->input->post('pin');
			$pin_id = $this->input->post('pin_id');
			$bra = $this->input->post('branch');
			if($pin == ""){
				$error_msg = "Error! Wrong login!";
				echo json_encode(array('error_msg'=>$error_msg));
				return false;
			}
			$user = $this->site_model->get_user_details($pin_id,$username,$password,$pin);
			$error_msg = null;
			$path = null;
			$send_redirect = null;
			if(!isset($user->id)){
				$error_msg = "Wrong login!";
			}
			else{
				$goSign = true;
				if(CHECK_OIC_ID){
					if($user->user_role_id == OIC_ID){
						$goSign = false;
					}
				}

				if(!$goSign){
					$error_msg = "User Role has no previlage to login.";
					echo json_encode(array('error_msg'=>$error_msg));
					return false;
				}

				$img = base_url().'img/avatar.jpg';
				$result = $this->site_model->get_image(null,$user->id,'users');
	            if(count($result) > 0){
	                $img = base_url().$result[0]->img_path;
	            }
				$session_details['user'] = array(
					"id"=>$user->id,
					"username"=>$user->username,
					"fname"=>$user->fname,
					"lname"=>$user->lname,
					"mname"=>$user->mname,
					"suffix"=>$user->suffix,
					"full_name"=>$user->fname." ".$user->mname." ".$user->lname." ".$user->suffix,
					"role_id"=>$user->user_role_id,
					"role"=>$user->user_role,
					"access"=>$user->access,
					"img"=>$img,
				);
				if(BACK_OFFICE == true){
					$send_redirect = base_url()."dashboard";
				}
				else{
					$send_redirect = base_url()."cashier";
				}
				if ($user->user_role_id > 2 && $user->user_role_id != 6 ) {
					$send_redirect = base_url()."shift";				
				}
				if(ORDERING_STATION){
					$send_redirect = base_url()."cashier/tables";				
				}else{
					if($user->user_role_id == 6){
						$send_redirect = base_url()."cashier/tables";
					}
				}
				//JEDN
				session_start();
				if(isset($_SESSION['problem_code']) && $_SESSION['problem_code'] == "unclosed_day"){
					// $setup['problem_code'] = $_SESSION['problem_code'];
					$send_redirect = base_url()."reads/recover_zread";
				}
				
				$this->session->set_userdata($session_details);
				$this->logs_model->add_logs('login',$user->id,$user->fname." ".$user->mname." ".$user->lname." ".$user->suffix." Logged In.",null);
			}
		}
		echo json_encode(array('error_msg'=>$error_msg,'redirect_address'=>$send_redirect));
	}
	public function go_logout(){
		$user = $this->session->userdata('user');
		$this->logs_model->add_logs('logout',$user['id'],$user['full_name']." Logged Out.",null);
		$this->session->sess_destroy();
		redirect(base_url()."login",'refresh');
	}
	public function end_shift(){
		$user = $this->session->userdata('user');
		$this->logs_model->add_logs('logout',$user['id'],$user['full_name']." Logged Out.",null);
		$this->session->sess_destroy();

		redirect(base_url()."site/login/0/1",'refresh');
	}
	public function site_alerts(){
		$site_alerts = array();
		$alerts = array();
		if($this->session->userdata('site_alerts')){
			$site_alerts = $this->session->userdata('site_alerts');
		}

		foreach ($site_alerts as $alert) {
			$alerts[] = $alert;
		}
		echo json_encode(array("alerts"=>$alerts));
	}
	public function clear_site_alerts(){
		if($this->session->userdata('site_alerts'))
			$this->session->unset_userdata('site_alerts');
	}

	public function execute_migration($ajax=false){
		ini_set('memory_limit', '-1');
        set_time_limit(3600);
        // start_load(0);
		
		// var_dump(MASTERMIGRATION);die();
		$has_ic = $this->has_ic(); // check if has internet connection 
         $this->load->model('core/master_model');
         $this->load->model('site/api_model');

		 $go_migrate = true;

        $last_log = $this->master_model->check_last_log();
        // var_dump($last_log);
		// update_load(10);
        if(!empty($last_log)){
        	if($last_log->type != 'finish'){ // check if last migration is not yet finished
        		$date_today = new DateTime();
        		$time_diff = $date_today->diff(new DateTime($last_log->migrate_date));

        		if($time_diff->i < 5){ // if last migration is within last 5 minutes don't migrate  , since it might be still migrating
        			$go_migrate = false;
        		}
        	
        	}
        }

         // $go_migrate = true;
		// echo "
		if(MASTERMIGRATION && $go_migrate){	
			// update_load(20);

            // $exec = $this->master_model->execute_migration();
            // $exec = $this->api_model->execute_migration_v2();

            // $exec = $this->master_model->execute_migration();
            $exec = $this->api_model->execute_migration_v2();

			if( $exec){
				if(isset($_POST['ajax'])){
					echo true;
				}else{
					echo "<pre>",print_r($exec),"</pre>";die();
					// return true;
				}
			}
        }

        if(!$has_ic){
        	echo "Please check your internet connection and try again.";
        }
	}


	public function master_restartxxx(){
		 $this->load->model('core/admin_model');
         $exec = $this->admin_model->master_restart();
	}

	public function test(){
		// var_dump(MASTERMIGRATION);die();
		// if(MASTERMIGRATION){	

            $this->load->model('core/master_model');
            $rec = $this->master_model->test()[0];
            $data = $rec->src_id;
            $t= json_decode($data,false);
            echo "<pre>",print_r($t),"</pre>";die();
			if($this->master_model->execute_migration()){

			}
        // }
	}


	public function testing_batch(){
		run_master_exec();
		                             // $str = exec('mastercall.bat'); 
		// var_dump($str);
		// echo "asdf";die();
	}


	// check if has internet connection
	public function has_ic()
	{
	    $connected = @fsockopen("www.example.com", 80); 
	                                        //website, port  (try 80 or 443)
	    if ($connected){
	        $is_conn = true; //action when connected
	        fclose($connected);
	    }else{
	        $is_conn = false; //action in connection failure
	    }

	    // var_dump($is_conn);die();
	    return $is_conn;

	}

	public function execute_sync($ajax=false){
		// echo 'aaaaaa'; die();
		if(AUTOLOCALSYNC){	

            $this->load->model('core/sync_model');

			$go_migrate = true;

	        $last_log = $this->sync_model->check_last_log();
	        // var_dump($last_log);
	        if(!empty($last_log)){
	        	if($last_log->type != 'finish'){ // check if last migration is not yet finished
	        		$date_today = new DateTime();
	        		$time_diff = $date_today->diff(new DateTime($last_log->migrate_date));

	        		if($time_diff->i < 10){ // if last migration is within last 10 minutes don't migrate  , since it might be still migrating
	        			$go_migrate = false;
	        		}
	        	
	        	}
	        }
	        if($go_migrate){

	            $exec = $this->sync_model->execute_sync();
				if( $exec){
					$has_ic = $this->has_ic(); 
					if(MASTERMIGRATION && $has_ic){ // run the master syncing
						run_master_exec();
					}
					if(isset($_POST['ajax'])){
						echo true;
					}else{
						echo "<pre>",print_r($exec),"</pre>";
						// return true;
					}

				}
	        }

			die();
        }
	}

	public function download_masterfile($ajax=false){
		// var_dump(MASTERMIGRATION);die();
		start_load(0);

		$has_ic = $this->has_ic(); // check if has internet connection 
         $this->load->model('core/master_model');
         $this->load->model('site/api_model');

		 // $go_migrate = true;
         update_load(10);
        // $last_log = $this->master_model->check_last_log();
        // // var_dump($last_log);
        // if(!empty($last_log)){
        // 	if($last_log->type != 'finish_'){ // check if last migration is not yet finished
        // 		$date_today = new DateTime();
        // 		$time_diff = $date_today->diff(new DateTime($last_log->migrate_date));

        // 		if($time_diff->i < 5){ // if last migration is within last 5 minutes don't migrate  , since it might be still migrating
        // 			$go_migrate = false;
        // 		}
        	
        // 	}
        // }

        //  $go_migrate = true;
		// echo "
		if(MASTERMIGRATION){	
			update_load(20);
            // $exec = $this->master_model->execute_migration_download_items();
            // $exec = $this->api_model->execute_migration_download_items();
            // $exec = $this->master_model->execute_migration_download_items();
            $exec = $this->api_model->execute_migration_download_items();

			if( $exec){
				if(isset($_POST['ajax'])){
					echo true;
				}else{
					echo "<pre>",print_r($exec),"</pre>";die();
					// return true;
				}
			}

			// update_load(50);
			update_load(100);
        }

        if(!$has_ic){
        	echo "Please check your internet connection and try again.";
        }
	}

	public function test_autoprint(){
		try {
		    // Enter the share name for your USB printer here
		    // $connector = null;
		    // $profile = CapabilityProfile::load("simple");

		    $connector = new WindowsPrintConnector('LPT2');

		    // $profile =  CapabilityProfile::load("simple");
		    /* Print a "Hello world" receipt" */
		    $printer = new Printer($connector);
// echo FCPATH ;die();
		    $printer -> setJustification(Printer::JUSTIFY_CENTER);
		     // $tux = EscposImage::load(FCPATH."img/clickLogo.png", false);
		    
		    // $printer -> graphics($tux);
		     // $printer -> bitImage($tux);        
		    // $c = pow(2, 4) * (15- 1) + (15 - 1);

		 $printer -> setEmphasis(true);
	// $printer -> setTextSize(7, 7);
		 $printer -> selectPrintMode(14);

	$printer -> text("FOO CORP Ltd.\n ");
	$printer -> setEmphasis(false);
	// $printer -> feed();
	$printer -> text("Receipt for whatever\n");
	// var_dump($printer);die();
	$printer -> feed(4);
		    $printer -> cut();
		    // echo "asd";die()
		    /* Close printer */
		    $printer -> close();
		    echo "success";
		} catch (Exception $e) {
		    echo "Couldn't print to this printer: ".KITCHEN_PRINTER ." ". $e -> getMessage() . "\n";exit;
		}

	}

	// PRINT FOR XPRINT BARCODE PRINTER(TSPL) @jx07122019
	public function test_autoprint2(){
		try {
		    // Enter the share name for your USB printer here
		    // $connector = null;
		    // $connector = new WindowsPrintConnector(KITCHEN_PRINTER);
//   $printer = new TsplPrinter($connector);
//   var_dump($printer);
// die();
			// echo PRINT1_PRINTER;die();
			// $connector = "LABEL_PRINTERUSB";
		    $connector = new WindowsPrintConnector('LABEL_PRINTERUSB');

		    /* Print a "Hello world" receipt" */
		   // $printer = new Printer($connector);
		    
		    $printer = new TsplPrinter($connector);

		    $printer->beep();
		$printer->getSizeCommand();
		$printer->getGapCommad();
		$printer->getReferenceCommand();
		 $printer->getDirectionCommand();
 // $this->getShiftCommand();
		    // $printer->setSize(3,1);
		    // $printer->setGap(1,0);
		 $printer->CLS();
		    $printer->text("Hello WORLDDD!!!!!!",20,20,"3");
		     $printer->text("Hello WORLDDD!!!!!!",20,60,"3");
		    		    $printer->text("Hello WORLDDD!!!!!!",20,100,"2");
		    		    $printer->go_print();
		      // $printer->beep();
		      // $printer->print(1);
		     $printer->close();

		    // $printer -> setJustification(Printer::JUSTIFY_CENTER);
		     // $tux = EscposImage::load(FCPATH."img/clickLogo.png", false);
		     // $printer -> bitImage($tux);
		      // $printer -> setSize(1,1);
		     // $printer -> setFont(Printer::FONT_A);
		    // $printer -> graphics($tux);
		    //  $print = "JUSTIN FONT A SIZE 1, 1 \r\n";
		    // $printer->text($print);
		    //   $printer->close();
		        // $printer->close(1);
		      // $printer -> setTextSize(1, 1);
		     // $printer -> setFont(Printer::FONT_B);
		    // $printer -> graphics($tux);
		    //  $print = "FONT B SIZE 1, 1 \r\n";
		    // $printer -> text($print);
		    //   $printer -> setTextSize(1, 1);
		    //  $printer -> setFont(Printer::FONT_C);
		    // // $printer -> graphics($tux);
		    //  $print = "FONT C SIZE 1, 1 \r\n";
		    // $printer -> text($print);

		    //  $printer -> setTextSize(2, 2);
		    //  $printer -> setFont(Printer::FONT_A);
		    // // $printer -> graphics($tux);
		    //  $print = "FONT A SIZE 2, 2 \r\n";
		    // $printer -> text($print);
		    //   $printer -> setTextSize(2, 2);
		    //  $printer -> setFont(Printer::FONT_B);
		    // // $printer -> graphics($tux);
		    //  $print = "FONT B SIZE 2, 2 \r\n";
		    // $printer -> text($print);
		    //   $printer -> setTextSize(2, 2);
		    //  $printer -> setFont(Printer::FONT_C);
		    // // $printer -> graphics($tux);
		    //  $print = "FONT C SIZE 2, 2 \r\n";
		    // $printer -> text($print);

		    //  $printer -> setTextSize(2, 1);
		    //  $printer -> setFont(Printer::FONT_A);
		    // // $printer -> graphics($tux);
		    //  $print = "FONT A SIZE 2, 1 \r\n";
		    // $printer -> text($print);
		    //   $printer -> setTextSize(2, 1);
		    //  $printer -> setFont(Printer::FONT_B);
		    // // $printer -> graphics($tux);
		    //  $print = "FONT B SIZE 2, 1 \r\n";
		    // $printer -> text($print);
		    //   $printer -> setTextSize(2, 2);
		    //  $printer -> setFont(Printer::FONT_C);
		    // // $printer -> graphics($tux);
		    //  $print = "FONT C SIZE 2, 1 \r\n";
		    // $printer -> text($print);
		    //  //  $printer -> setTextSize(2, 2);
		    //  // $printer -> setFont(Printer::FONT_D);
		    // // $printer -> graphics($tux);
		    //  $print = "FONT D SIZE 2, 1 \r\n";
		    // $printer -> text($print);
		    // $printer -> cut();
		    // echo "asd";die()
		    /* Close printer */
		    // $printer -> close();
		} catch (Exception $e) {
		    echo "Couldn't print to this printer:   ". $e -> getMessage() . "\n";exit;
		}

	}

	public function restart_printer(){

           // system("cmd /c ".remove_printer.""); 
           system("cmd /c ".restart_printer.""); 
	}

	public function ping_printer(){

           // system("cmd /c ".remove_printer.""); 
           system("cmd /c ".ping_printer.""); 
	}
	

	public function shell_ex(){
		   system("cmd /c net use LPT1 /delete"); 
		   system('cmd /c net use LPT1 "\\192.168.254.109\JUSTINPRINT" /p:y /user:POINTONE-715 2>&1'); 

		 shell_exec("net use LPT1 /delete");
		$shell_exec = shell_exec('net use LPT1: "\\192.168.254.109\JUSTINPRINT" /p:y /user:POINTONE-715 2>&1');
		// $shell_exec = shell_exec("net use LPT1");

		echo $shell_exec;
	}

	public function net_use(){
		// echo "a";die();
		// var_dump(is_writable("//LPT1"));
		// var_dump(is_readable("\\LPT1"));
		// var_dump(file_exists("\\LPT1"));die();
		$check_net_use = shell_exec("net use 2>&1");
		// var_dump($check_net_use);die();
		$expr_raw = explode("\t", $check_net_use);
		if(isset($expr_raw[0])){

			$expr_raw_2 = explode(" ", $expr_raw[0]);
			$expr = array_filter($expr_raw_2);
			$filterable = implode(" ", $expr);
			// var_dump($filterable);die();
			// foreach ($expr as $key => $value) {
			// 	# code...
			// 	// echo $key;		
			// 	echo "<pre> $key: ",var_dump($value),"</pre>";//die();
			// }die();
		}

		 if(stripos($filterable, "OK lpt1") !== false ){
		 	// echo "a";die();
		 }
		// var_dump($check_net_use);die();
        if(stripos($check_net_use, "disconnected") !== false || stripos($check_net_use, "unavailable") !== false ||  stripos($check_net_use, "there are no entries in the list") !== false || $occurence_count != total_number_of_printers){
			echo "disconnected";
		}else{
			echo "connected";
		}
		// var_dump($check_net_use);
	}

	public function test_split(){
		   $trans_cart = sess('trans_cart');
		    $trans_split_cart = sess('trans_split_cart');
          // $trans_mod_cart = sess('trans_mod_cart');
		   echo "<pre>trans cart: ",print_r($trans_cart),"</pre>";
		   echo "<pre>trans split cart: ",print_r($trans_split_cart),"</pre>";

		   die();
	}

	function delete_directory($dirname) {
        if (is_dir($dirname)){
        	// echo 'aw';
           $dir_handle = opendir($dirname);
        }
		if (!$dir_handle){
		     // return false;
		}
		while($file = readdir($dir_handle)) {
		    if ($file != "." && $file != "..") {
		        if (!is_dir($dirname."/".$file)){
		            unlink($dirname."/".$file);
		            // echo $dirname."/".$file.'<br><br>';
		        }
		        else{
		            $this->delete_directory($dirname.'/'.$file);
		        }
		    }
	 	}
		closedir($dir_handle);
		if($dirname != BASEPATH){
			rmdir($dirname);	
		}
		// echo $dirname.'ssssssssssss<br>';
	 	return true;
	}

	function download_trans_for_hq(){
		$this->load->model('core/master_model');

		// $file_name = 'sales/tran_sales_upto_'. date('m_d_Y').'.sql';
		$file_name = 'sales/'.BRANCH_CODE.'_'. date('m_d_Y').'.sql';

		$salefile = fopen("sales/sales.txt", "w+") or die("Unable to open file!");
		while ($line = fgets($salefile)) {
		 	if(file_exists($line)){
		 		unlink(trim($line));
		 	}
		}
		fwrite($salefile, $file_name);

		fclose($salefile);

		$tables = array('trans_sales','trans_sales_charges','trans_sales_discounts','trans_sales_items',
						'trans_sales_local_tax','trans_sales_loyalty_points','trans_sales_menu_modifiers',
						'trans_sales_menu_submodifiers','trans_sales_menus','trans_sales_no_tax','trans_sales_payments',
						'trans_sales_tax','trans_sales_zero_rated'
						,'trans_gc','trans_gc_charges','trans_gc_discounts',
						'trans_gc_local_tax','trans_gc_loyalty_points','trans_gc_gift_cards',
						'trans_gc_no_tax','trans_gc_payments',
						'trans_gc_tax','trans_gc_zero_rated'
					);
		// $tables = $tables = array('trans_sales');
		$return = $this->master_model->download_trans_for_hq($tables);

		// print_r($return);exit;
		
		$new_file = fopen($file_name, 'w+');
		fwrite($new_file, $return);
		fclose($new_file);
		
		header("Location:" . base_url() . $file_name);
	}

	public function execute_pricelevel($ajax=false){
		ini_set('memory_limit', '-1');
        set_time_limit(3600);
		
		// var_dump(MASTERMIGRATION);die();
		// $has_ic = $this->has_ic(); // check if has internet connection 
     	$this->load->model('core/master_model');
     	$this->load->model('site/site_model');

		 // $go_migrate = true;

        $alldata = $this->master_model->get_data_ex();

        // echo count($alldata);
        // die();

        foreach($alldata as $id => $vv){

        	$desc = $vv->description;
        	$price3 = $vv->price3;

        	//serach sa menus
        	$where = array('menu_name'=>$desc,'inactive'=>'0','brand'=>2);
            $gdet = $this->site_model->get_details($where,'menus');

            if($gdet){

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'PICAROO',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'FOODPANDA',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'ZOMATO',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'DELIVERY',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'LALAFOOD',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'FOODPANDAPICKUP',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'BOOKY',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'GRAB',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'ONLINE',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'GRABPICKUP',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'LALAFOODPICKUP',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            	$arr = array('menu_id'=>$gdet[0]->menu_id,
            				'trans_type'=>'CURBSIDE',
            				'price'=>$price3
        		);
            	$this->master_model->add_menu_price_data($arr);

            }



        }



        echo 'done';

	}

	function sync_maintenance(){
		start_load(0);
		$this->load->model('core/master_model');
		update_load(10);
		$this->master_model->sync_maintenance();


		update_load(100);

		echo true;
	}

	function download_to_hq(){
		$this->load->model('site/api_model');
		$this->api_model->download_to_hq();

		echo true;
	}


}
