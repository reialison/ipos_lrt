<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custs_bank extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('dine/customers_model');
		$this->load->model('site/site_model');
		$this->load->helper('dine/custs_bank_helper');
		$this->load->model('dine/main_model');
		$this->load->model('dine/setup_model');
		$this->load->helper('dine/print_helper');
		$this->load->helper('core/string_helper');
	}
    public function index(){
    	$data = $this->syter->spawn('customers');
    	$data['code'] = custsBankPage();
    	$data['load_js'] = 'dine/custs_bank.php';
    	$data['use_js'] = 'indexJs';

    	$data['add_css'] = array('css/cashier.css','css/virtual_keyboard.css');
    	$data['add_js'] = array('js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');

    	$data['noNavbar'] = true;
    	$this->load->view('cashier',$data);
    }
    public function customers_money(){
    	$this->load->helper('site/site_forms_helper');
    	$data = $this->syter->spawn('customers');
    	$th = array('ID','Customer','Total Amount','Last Deposit Date','');
    	$code = create_rtable('customers','cust_id','customers-tbl',$th,'custs_bank/search_custs_deposit_form');
    	$data['code'] = $code;
    	$data['load_js'] = 'dine/custs_bank.php';
    	$data['use_js'] = 'customersMoneyList';
    	// $data['add_css'] = 'css/cashier.css';
    	// $data['noNavbar'] = true; /*Hides the navbar. Comment-out this line to display the navbar.*/
    	$this->load->view('load',$data);
    }	
    public function get_custs_deposits(){
    	$this->load->helper('site/pagination_helper');
	    $pagi = null;
	    $args = array();
	    $total_rows = 30;
	    if($this->input->post('pagi'))
	        $pagi = $this->input->post('pagi');
	    $post = array();
	    if(count($this->input->post()) > 0){
	        $post = $this->input->post();
	    }
	    
	    if($this->input->post('cust_name')){
	        $lk  =$this->input->post('cust_name');
	        $args["(customers.fname like '%".$lk."%' OR customers.mname like '%".$lk."%' OR customers.lname like '%".$lk."%' OR customers.suffix like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
	    }
	    $args['customers.inactive'] = 0;
        $args['customers_bank.type_id'] = CUST_DEPOSIT_TRANS;
        $args['customers_bank.sales_id is null'] = array('use'=>'where','val'=>"",'third'=>false);
	    $select = " customers.cust_id,customers.fname,customers.mname,customers.lname,customers.suffix,
					SUM(customers_bank.amount) AS cust_money,
					MAX(customers_bank.datetime) AS last_deposit,bank_id,ref_no,customers_bank.inactive,type_id,sales_id";
	    $join["customers_bank"] = array('content'=>"customers.cust_id = customers_bank.cust_id");
	    $order['last_deposit'] = 'desc';
	    // $group = 'customers_bank.cust_id';
        $group = 'customers_bank.bank_id';

	    $count = $this->site_model->get_tbl('customers',$args,null,$join,true,$select,$group,null,true);
	    $page = paginate('pos_customers/get_customers',$count,$total_rows,$pagi);
	    $items = $this->site_model->get_tbl('customers',$args,$order,$join,true,$select,$group,$page['limit']);
	    // echo $this->site_model->db->last_query();
	    $json = array();
	    if(count($items) > 0){
	        $ids = array();
	        foreach ($items as $res) {
	        	$link = "";
	        	// if($termninal == 1){
		        // 	$link = $this->make->A(fa('fa-edit fa-lg'),base_url().'pos_customers/customer_terminal_form/'.$res->cust_id,array('return'=>'true'));	        		
	        	// }
	        	// else{
		        // 	$link = $this->make->A(fa('fa-edit fa-lg'),base_url().'pos_customers/form/'.$res->cust_id,array('return'=>'true'));	        		
	        	// }

                if($res->inactive == 1){
                    $link = 'Voided';  
                }else{
                    $link = $this->make->A(fa('fa-ban fa-lg'),'#',array('return'=>'true','class'=>'cancel-deposit-reason-btn','ref'=>$res->bank_id,'amount'=>$res->cust_money));  
                }
                
	            $json[$res->bank_id] = array(
	                "id"=>$res->bank_id,   
	                "title"=>$res->fname." ".$res->mname." ".$res->lname." ".$res->suffix,   
	                "desc"=>num($res->cust_money),   
	                "date_reg"=>sql2Date($res->last_deposit),
	                "link"=>$link
	            );
	        }
	    }
	    echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function search_custs_deposit_form(){
        $data['code'] = customerDepositSearchForm();
        $this->load->view('load',$data);
    }	
    public function deposit(){
    	$data['code'] = depositPage();
    	$data['load_js'] = 'dine/custs_bank.php';
    	$data['use_js'] = 'depositJs';
    	$this->load->view('load',$data);
    }
    public function deposit_db(){
    	$this->load->model('core/trans_model');
        $this->load->model('core/sync_model');

    	$next_ref = $this->trans_model->get_next_ref(CUST_DEPOSIT_TRANS);
    	$user = sess('user');
    	$card_type = $this->input->post('card_type');
    	if(!$this->input->post('card_type')){
    		$card_type = "";
    	}
		$items = array(
			'trans_ref' 			=> $next_ref,
			'type_id' 				=> CUST_DEPOSIT_TRANS,
			"cust_id"      			=> $this->input->post('cust_id'),
			"amount"       			=> $this->input->post('amount'),
			"amount_type"       	=> $this->input->post('amount_type'),
			"card_no"      			=> $this->input->post('card_no'),
			"card_type"      		=> $card_type,
			"approval_code"      	=> $this->input->post('approval_code'),
			"user_id"    		  	=> $user['id'],
			"pos_id"	    		=> TERMINAL_ID,
			"remarks"	    		=> $this->input->post('remarks')
		);
		$this->trans_model->db->trans_start();
			$id = $this->site_model->add_tbl('customers_bank',$items,array('datetime'=>'NOW()'));

            // if(LOCALSYNC){
            //     $this->sync_model->add_customers_bank($id);
            // }
			$this->trans_model->save_ref(CUST_DEPOSIT_TRANS,$next_ref);
		$this->trans_model->db->trans_complete();
		site_alert("Deposit Success",'success');
		$this->print_deposit($id,true);
    }
    public function print_deposit($id=null,$asJson=false){
    	$header = $this->print_header();
    	$print_str = $header['print_str'];
    	$branch = $header['branch'];
    	$select = "customers_bank.*,users.username,customers.fname,customers.mname,customers.lname,customers.suffix,terminals.terminal_code";
    	$args['bank_id'] = $id;
    	$join["users"] = array('content'=>"customers_bank.user_id = users.id");
    	$join["customers"] = array('content'=>"customers_bank.cust_id = customers.cust_id");
    	$join["terminals"] = array('content'=>"customers_bank.pos_id = terminals.terminal_id");
    	$result = $this->site_model->get_tbl('customers_bank',$args,array(),$join,true,$select);
    	if(count($result) > 0 ){
    		$trans = $result[0];
    		$print_str .= append_chars(strtoupper($trans->username),'right',19," ").append_chars(date2SqlDateTime($trans->datetime),'left',19," ")."\r\n";
    		$print_str .= "Terminal ID : ".($trans->terminal_code)."\r\n";
    		$print_str .= "======================================"."\r\n";
    		$print_str .= align_center('Acknowledgement Receipt - Deposit',38," ")."\r\n";
    		$print_str .= align_center("Reference # ".$trans->trans_ref,38," ")."\r\n";
    		$print_str .= "======================================"."\r\n";
    		$print_str .= "\r\n";
    		$customer_name = ucwords(strtolower($trans->fname." ".$trans->mname." ".$trans->lname." ".$trans->suffix));
    		$print_str .= append_chars(substrwords("Customer: ",18,""),"right",12," ").$customer_name;
    		$print_str .= "\r\n";
    		$print_str .= "\r\n";
    		$print_str .= "Amount Details \r\n";
    		$print_str .= append_chars(strtoupper($trans->amount_type),"right",27," ").append_chars("P ".num($trans->amount,2),"right",10," ")."\r\n";
    		if($trans->card_type != "")
	    		$print_str .= append_chars("  Card Type    : ".$trans->card_type,"right",38," ")."\r\n";
    		if($trans->card_no != "")
	    		$print_str .= append_chars("  Card No.     : ".$trans->card_no,"right",38," ")."\r\n";
    		if($trans->approval_code != "")
	    		$print_str .= append_chars("  Approval Code: ".$trans->approval_code,"right",38," ")."\r\n";
    		$print_str .= "\r\n";
    		$print_str .= "======================================"."\r\n";
    		$print_str .= "\r\n";
    		if($branch['contact_no'] != ""){
    		    $print_str .= align_center("For feedback, please call us at",38," ")."\r\n"
    		                 .align_center($branch['contact_no'],38," ")."\r\n";
    		}
    		if($branch['email'] != ""){
    		    $print_str .= align_center("Or Email us at",38," ")."\r\n" 
    		                 .align_center($branch['email'],38," ")."\r\n";
    		}
    		if($branch['website'] != "")
    		    $print_str .= align_center("Please visit us at \r\n".$branch['website'],38," ")."\r\n";

    	}
    	$this->do_print($print_str,$asJson);
    }
    public function print_rdeposit($id=null,$asJson=false,$date_schedule=null,$time_schedule=null){
        $header = $this->print_header();
        $print_str = $header['print_str'];
        $branch = $header['branch'];
        $select = "customers_bank.*,users.username,customers.fname,customers.mname,customers.lname,customers.suffix,terminals.terminal_code";
        $args['bank_id'] = $id;
        $join["users"] = array('content'=>"customers_bank.user_id = users.id");
        $join["customers"] = array('content'=>"customers_bank.cust_id = customers.cust_id");
        $join["terminals"] = array('content'=>"customers_bank.pos_id = terminals.terminal_id");
        $result = $this->site_model->get_tbl('customers_bank',$args,array(),$join,true,$select);
        // echo $this->site_model->db->last_query();die();
        // echo "<pre>",print_r($result),"</pre>";die();
        if(count($result) > 0 ){
            $trans = $result[0];
            $print_str .= append_chars(strtoupper($trans->username),'right',19," ").append_chars(date2SqlDateTime($trans->datetime),'left',19," ")."\r\n";
            $print_str .= "Terminal ID : ".($trans->terminal_code)."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= align_center('Acknowledgement Receipt - Deposit',38," ")."\r\n";
            $print_str .= align_center("Transaction # ".$trans->trans_ref,38," ")."\r\n";
            $print_str .= align_center("Reference # ".$trans->ref_no,38," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= "\r\n";
            $customer_name = ucwords(strtolower($trans->fname." ".$trans->mname." ".$trans->lname." ".$trans->suffix));
            $print_str .= append_chars(substrwords("Customer: ",18,""),"right",12," ").$customer_name;
            $print_str .= "\r\n";
            $print_str .= append_chars(substrwords("Reservation Date: ",18,""),"right",12," ").$date_schedule;
            $print_str .= "\r\n";
            $print_str .= append_chars(substrwords("Reservation Time: ",18,""),"right",12," ").$time_schedule;
            $print_str .= "\r\n";
            $print_str .= "\r\n";
            $print_str .= "Amount Details \r\n";
            $print_str .= append_chars(strtoupper($trans->amount_type),"right",27," ").append_chars("P ".num($trans->amount,2),"right",10," ")."\r\n";
            if($trans->card_type != "")
                $print_str .= append_chars("  Card Type    : ".$trans->card_type,"right",38," ")."\r\n";
            if($trans->card_no != "")
                $print_str .= append_chars("  Card No.     : ".$trans->card_no,"right",38," ")."\r\n";
            if($trans->approval_code != "")
                $print_str .= append_chars("  Approval Code: ".$trans->approval_code,"right",38," ")."\r\n";
            $print_str .= "\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= "\r\n";
            if($branch['contact_no'] != ""){
                $print_str .= align_center("For feedback, please call us at",38," ")."\r\n"
                             .align_center($branch['contact_no'],38," ")."\r\n";
            }
            if($branch['email'] != ""){
                $print_str .= align_center("Or Email us at",38," ")."\r\n" 
                             .align_center($branch['email'],38," ")."\r\n";
            }
            if($branch['website'] != "")
                $print_str .= align_center("Please visit us at \r\n".$branch['website'],38," ")."\r\n";

        }
        // echo $print_str;die();
        $this->do_print($print_str,$asJson);
    }
    public function do_print($print_str=null,$asJson=false){
        if (!$asJson) {
            // echo "<pre style='background-color:#fff'>$print_str</pre>";
            echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
        }else if(PRINT_VERSION && PRINT_VERSION=='V3'){
            $js_rcp = $this->html_print_deposit($print_str);
            $js_rcps[] = array('printer'=>BILLING_PRINTER,'value'=>$js_rcp);
            echo json_encode(array('js_rcps'=>$js_rcps));
        }
        else{
            $filename = "report.txt";
            $fp = fopen($filename, "w+");
            fwrite($fp,$print_str);
            fclose($fp);

            $batfile = "print.bat";
            $fh1 = fopen($batfile,'w+');
            $root = dirname(BASEPATH);

            fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
            fclose($fh1);
            session_write_close();
            // exec($filename);
            exec($batfile);
            session_start();
            unlink($filename);
            unlink($batfile);  

            echo json_encode(array('code'=>''));
        }
    }
    public function print_header(){
        $branch_details = $this->setup_model->get_branch_details();
        $branch = array();
        foreach ($branch_details as $bv) {
            $branch = array(
                'id' => $bv->branch_id,
                'res_id' => $bv->res_id,
                'branch_code' => $bv->branch_code,
                'name' => $bv->branch_name,
                'branch_desc' => $bv->branch_desc,
                'contact_no' => $bv->contact_no,
                'delivery_no' => $bv->delivery_no,
                'address' => $bv->address,
                'base_location' => $bv->base_location,
                'currency' => $bv->currency,
                'inactive' => $bv->inactive,
                'tin' => $bv->tin,
                'machine_no' => $bv->machine_no,
                'bir' => $bv->bir,
                'permit_no' => $bv->permit_no,
                'serial' => $bv->serial,
                'accrdn' => $bv->accrdn,
                'email' => $bv->email,
                'website' => $bv->website,
                'store_open' => $bv->store_open,
                'store_close' => $bv->store_close,
                "pos_footer"=>$bv->pos_footer,
                "rec_footer"=>$bv->rec_footer,
            );
        }
        $userdata = $this->session->userdata('user');
        $print_str = "\r\n\r\n";
        $wrap = wordwrap($branch['name'],35,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= align_center($v,38," ")."\r\n";
        }
        $wrap = wordwrap($branch['address'],35,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= align_center($v,38," ")."\r\n";
        }
        // $print_str .= 
        //  align_center('TIN: '.$branch['tin'],38," ")."\r\n"
        // .align_center('ACCRDN: '.$branch['accrdn'],38," ")."\r\n"
        // // .$this->align_center('BIR # '.$branch['bir'],42," ")."\r\n"
        // .align_center('MIN: '.$branch['machine_no'],38," ")."\r\n"
        // // .align_center('SN: '.$branch['serial'],38," ")."\r\n"
        // .align_center('PERMIT: '.$branch['permit_no'],38," ")."\r\n";
        $print_str .= "======================================"."\r\n";
        return array("print_str"=>$print_str,"branch"=>$branch);
    }
    public function reservation_cust_bank(){

        $date_schedule = $this->session->userdata('date_schedule');
        $time_schedule = $this->session->userdata('time_schedule');
        // $cust_idaa = $this->session->userdata('cust_id');
        // echo "<pre>",print_r($cust_id),"</pre>";die();
        // echo print_r($this->session->userdata());die();
        $data = $this->syter->spawn('customers');
        $data['code'] = custsBankPage();
        $data['load_js'] = 'dine/custs_bank.php';
        $data['use_js'] = 'reservationIndexJs';

        $data['add_css'] = array('css/cashier.css','css/virtual_keyboard.css');
        $data['add_js'] = array('js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');

        $data['noNavbar'] = true;
        $this->load->view('cashier',$data);
    }
    public function deposit_reservation(){
        $date_schedule = $this->session->userdata('date_schedule');
        $time_schedule = $this->session->userdata('time_schedule');
        // echo $time_schedule;die();
        // $cust_idee = $this->session->userdata('cust_id');
        // $this->session->set_userData("cust_idee", $cust_idee );
        $data['code'] = reservationDepositPage($date_schedule,$time_schedule);
        $data['load_js'] = 'dine/custs_bank.php';
        $data['use_js'] = 'rdepositJs';
        $this->load->view('load',$data);
    }
    public function reservation_deposit_db(){
        $this->load->model('core/trans_model');
        $this->load->model('core/sync_model');

        $next_ref = $this->trans_model->get_next_ref(CUST_DEPOSIT_TRANS);
        $user = sess('user');
        $card_type = $this->input->post('card_type');
        if(!$this->input->post('card_type')){
            $card_type = "";
        }
        // $date_schedule = $this->session->userdata('date_schedule');
        // $time_schedule = $this->session->userdata('time_schedule');
        $date_schedule = $this->input->post('date_schedule');
        $time_schedule = $this->input->post('time_schedule');
        $time = date('H:i', strtotime($time_schedule));
        $cust_id = $this->input->post('cust_id');
        $no_btn = $this->input->post('no_btn');
        $this->session->set_userData("cust_id", $cust_id );
        $ds            = date_create($date_schedule);
        $set_date      = date_format($ds," Y-m-d ");
        // echo $set_date;die();
        $items = array(
            'trans_ref'             => $next_ref,
            'type_id'               => CUST_DEPOSIT_TRANS,
            "cust_id"               => $this->input->post('cust_id'),
            "amount"                => $this->input->post('amount'),
            "amount_type"           => $this->input->post('amount_type'),
            "card_no"               => $this->input->post('card_no'),
            "ref_no"                => $this->input->post('ref_no'),
            "card_type"             => $card_type,
            "approval_code"         => $this->input->post('approval_code'),
            "user_id"               => $user['id'],
            "pos_id"                => TERMINAL_ID,
            "remarks"               => $this->input->post('remarks'),
            "set_date"               => $set_date,
            "set_time"               => $time,
        );
        $this->trans_model->db->trans_start();
            $id = $this->site_model->add_tbl('customers_bank',$items,array('datetime'=>'NOW()'));

            // if(LOCALSYNC){
            //     $this->sync_model->add_customers_bank($id);
            // }
        $this->trans_model->save_ref(CUST_DEPOSIT_TRANS,$next_ref);
        $this->trans_model->db->trans_complete();
        site_alert("Deposit Success",'success');
        // echo "<pre>",print_r($no_btn),"</pre>";die();
        // $this->session->set_userData("date_schedule", $date_selected );
        // $this->session->set_userData("time_schedule", $time_selected );
        // $this->session->set_userData("cust_id", $cust_id );
        if($no_btn = 1){
            $this->print_rdeposit($cust_id,true,$date_schedule,$time_schedule);
        }
        echo json_encode(array('date_schedule'=>$date_schedule,'time_schedule'=>$time_schedule,'cust_id'=>$cust_id));
    }
    function set_reservation_data()
    {
        $date_selected = $this->input->post("date_schedule");
        $time_selected = $this->input->post("time_schedule");
        $cust_id = $this->input->post("cust_id");
        // $antigen = $this->input->post("antigen");
        // $this->session->set_userData("date_schedule", $date_selected );
        // $this->session->set_userData("time_schedule", $time_selected );
        // $this->session->set_userData("cust_id", $cust_id );
        // $this->session->set_userData("antigen", $antigen );
        echo json_encode(array('date_schedule'=>$date_selected,'time_schedule'=>$time_selected,'cust_id'=>$cust_id));
        // echo '<pre>', print_r($this->input->post());die();
    }

    function get_reservation_date(){
        // echo json_encode(array(array('title'=>'test','start'=>'2021-08-06'))) . '<br />';
        // exit;

        $this->load->model('dine/cashier_model'); 

         $args = array();
        //  $args = array(
        //     // "trans_sales.type"=>'reservation',
        //     "trans_sales.inactive"=>0,
        // );

        // $args["trans_sales.type = 'reservation' || (trans_sales.type='dinein' && paid=0)"] = array('use'=>'where','val'=>null,'third'=>false);

         $group = 'customers_bank.cust_id,ref_no';
        // $args['FORMAT(trans_sales.total_paid,2) < FORMAT(trans_sales.total_amount,2)'] = array('use'=>'where','val'=>null,'third'=>false);
        $reservations = $this->cashier_model->get_trans_sales_reservation(null,$args,'desc',null,$group);
        // echo $this->cashier_model->db->last_query();die();
        $status = "";
        $status2 = "";
        foreach($reservations as $idd => $v){
            if(empty($v->sales_id) && $v->inactive == 0){
                $status = "<label class='label label-primary'>Open</label>";
                $status2 = "open";
            }else if(empty($v->sales_id) && $v->inactive == 1){
                $status = "<label class='label label-danger'>Void</label>";
                $status2 = "void";
            }else if(!empty($v->sales_id) && $v->paid == 0 && $v->trans_inactive == 0){
                $status = "<label class='label label-warning'>Pending</label>";
                $status2 = "pending";
            }else if (!empty($v->sales_id) && $v->paid == 1 && $v->trans_inactive == 0){
                $status = "<label class='label label-success'>Close</label>";
                $status2 = "close";
            }else if (!empty($v->sales_id) && $v->trans_inactive == 1){
                $status = "<label class='label label-danger'>Void</label>";
                $status2 = "void";
            }

            if(!empty($v->table_name) && $v->paid == 0 && $v->trans_inactive == 0){
                $tname = $v->table_name;
            }else{
                $tname = "";
            }
            if(!empty($v->table_id) && $v->paid == 0 && $v->trans_inactive == 0){
                $tid = $v->table_id;
            }else{
                $tid = "";
            }
            // $total_amount = 0;
            // $total_amount += $v->amount;
            $ds_1            = date_create($v->set_date);
            $set_date_1      = date_format($ds_1,"Y-m-d");
            $time_1          = date('H:i', strtotime($v->set_time));
            $dt = $set_date_1." ".$time_1;
            $timestamp = date('H:i', strtotime($v->set_time) + 60*60);
            $dt2 = $set_date_1." ".$timestamp;
            // echo $dt;die();
            // $time_1          = date('H:i', strtotime($v->set_time));
            $final_datas[] = array('title'=>ucwords($v->custfname . ' ' . $v->custlname). " | " .date('h:i A', strtotime($v->set_time)),
                                   'start'=>$dt,
                                   'end'=>$dt2,
                                   'istart'=>date('Y-m-d',strtotime($v->set_date)),
                                   'sdate'=>date('m-d-Y',strtotime($v->set_date)),
                                   'tdate'=>date('m-d-Y',strtotime($v->datetime)),
                                   'name'=>ucwords($v->custfname . ' ' . $v->custlname),
                                   'sales_id'=>$v->sales_id,
                                   'trans_ref'=>$v->trans_ref,
                                   'ref_no'=>$v->ref_no,
                                   'status'=>$status,
                                   'table_name'=>$tname,
                                   'table_id'=>$tid,
                                   'ttype'=>$v->ttype,
                                   'stats'=>$status2,
                                   // 'username'=>$v->username,
                                   // 'terminal_name'=>$v->terminal_name,
                                   'total_amount'=>number_format($v->t_amount,2),
                                   'cust_id'=>$v->cust_id,
                                   'cust_amount'=>$v->amount,
                );
        }
        // echo "<pre>",print_r($final_datas),"</pre>";die();
// print_r($final_datas);exit;
        echo json_encode($final_datas);

        // print_r($reservations);
    }

    public function html_print_deposit($print_str='',$is_bill = false){
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
                    margin: 0;
                    padding: 0;
                    border: 0;
                    font-size: 100%;
                    font: inherit;
                    vertical-align: baseline;
                }
                  .media-cashier-panel, .cashier-wrapper, #noty_topRight_layout_container{ display:none; }
                  #print-rcp{ display:block;  }
                  @page { margin: 0; }
                  body { margin: 0px; padding:0px }
                  div { margin: 0px; padding:0px }
                }
                pre {
                    font-family:Lucida Console;
                    font-size:11.5px;
                    overflow-x: auto;
                    // text-align:justify;
                    white-space: pre-wrap;
                    white-space: -moz-pre-wrap;
                    white-space: -pre-wrap;
                    white-space: -o-pre-wrap;
                    word-wrap: break-word;
                    word-break:keep-all;
                 }
                </style>';
        $js_rcp .= '<script>print()</script>';

        return $js_rcp;
    }
}