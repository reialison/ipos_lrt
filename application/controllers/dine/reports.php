<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {
	public function __construct(){
		parent::__construct();
	    
        $this->load->helper('dine/reports_helper');
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/menu_model');
        $this->load->model('dine/settings_model');
        $this->load->model('dine/setup_model');
        $this->load->model('dine/clock_model');
        $this->load->model('dine/reports_model');
        $this->load->helper('core/string_helper');
    }
	//#####
    //FRONT END REPORTS
    //#####
        function index(){
    		$data = $this->syter->spawn(null);
            $data['code'] = reportsPage();
            $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/reports.php';
            $data['use_js'] = 'reportsJs';
            $this->load->view('load',$data);
    	}
    	public function form($report_title){
           if ($report_title == "system-sales"){
                $code = build_report("display_system_sales",array(
                    'date' => array('Select Read Date','daterange','','',array('class'=>'rOkay')),
                    // 'input' => array('Date range','daterange','','',array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar')),
                    // 'terminalDrop' => array("Terminal","terminal",null,null),
                    // 'userDrop' => array("Cashier","cashier",null,null),
                ));
            }elseif ($report_title == "store-sales"){
                $code = build_report("display_store_sales",array(
                    'input' => array('Date range','daterange','','',array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),null,fa('fa-calendar')),
                    // 'date' => array('Select Read Date','daterange','','',array('class'=>'rOkay')),
                    'userDrop' => array('Employee','user_id','',''),
                ));    
            }elseif ($report_title == "employee-sales"){
                $code = build_report("display_employee_sales",array(
                    // 'input' => array('Date range','daterange','','',array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar')),
                    'date' => array('Select Read Date','daterange','','',array('class'=>'rOkay')),
                    'userDrop' => array('Employee','server_id','','',array('class'=>'rOkay')),
                ));
            }elseif ($report_title == "z-read"){
                $code = build_report("display_z_read",array(
                    'date' => array('Select Read Date','daterange','','',array('class'=>'rOkay')),
                ));
            }elseif ($report_title == "x-read"){
                $code = build_report("display_x_read",array(
                    'userDrop' => array('Employee','cashier','','',array('class'=>'rOkay')),
                    'date' => array('Select Read Date','daterange','','',array('class'=>'rOkay')),
                ));
            }elseif ($report_title == "daily-sales"){
                $code = build_report("display_daily_sales",array(
                    'userDrop' => array('Employee','cashier','','',array('class'=>'')),
                    'input' => array("Select date","daterange",null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;','ro-msg'=>'Please select a valid date'),null,fa('fa-calendar')),
                ),'excel'); 
            }elseif ($report_title == "monthly-sales"){
                $code = build_report("display_mothly_sales",array(
                    'monthsDrop' => array('Month','month','','',array('class'=>'')),
                ),'excel');               
            }elseif ($report_title == "menu-sales"){
                $code = build_report("display_menu_sales",array(
                    // 'input' => array("Select date","daterange",null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;','ro-msg'=>'Please select a valid date'),null,fa('fa-calendar')),
                    'date' => array('Select Read Date','daterange','','',array('class'=>'rOkay')),
                    'userDrop' => array("Cashier","user_id",null,null)
                ));
            }elseif ($report_title == "hourly-sales"){
                $code = build_report("display_hourly_sales",array(
                    'input' => array('Date range','date','','',array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar')),
                    'userDrop' => array('Cashier','cashier','',''),
                ));
            }elseif ($report_title == "fs-sales") {
                $code = build_report('display_fs_sales',array(
                    'input' => array('Date range','daterange','','',array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar')),
                    'userDropSearch' => array('Food Server','server_id','',''),
                    'yesOrNoDrop' => array("Include inactive?","voided","no",null),
                ));
            }elseif ($report_title == "void-sales"){
                $code = build_report("void_sales",array(
                    'input' => array('Date range','date','','',array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar')),
                    'userDrop' => array('Cashier','cashier','',''),
                ));
            }

            echo json_encode(array('code'=>$code));
        }
        public function rep_header(){
            // Get data for report header
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
            $print_str .= 
             align_center('TIN: '.$branch['tin'],38," ")."\r\n"
            .align_center('ACCRDN: '.$branch['accrdn'],38," ")."\r\n"
            // .$this->align_center('BIR # '.$branch['bir'],42," ")."\r\n"
            .align_center('MIN: '.$branch['machine_no'],38," ")."\r\n"
            // .align_center('SN: '.$branch['serial'],38," ")."\r\n"
            .align_center('PERMIT: '.$branch['permit_no'],38," ")."\r\n\r\n";


            return $print_str;
        }
        public function system_sales_rep($asJson=false){
        	$print_str = $this->rep_header();
        	$userdata = $this->session->userdata('user');
            $date = $this->input->post('daterange');

        	// $date = '07/26/2015';
            // $dates = explode(" to ",$daterange);
            // $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            // $date = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
            $lastRead = $this->cashier_model->get_last_z_read(Z_READ,date2Sql($date));
            $old_gt_amnt = 0;
            $grand_total = 0;
            $read_date = $date;
            $date_to = $date;
            if(count($lastRead) > 0){
    	        foreach ($lastRead as $res) {
    	            $date_from = $res->scope_from;
    	            $date_to = $res->scope_to;
    	            $old_gt_amnt = $res->old_total;
    	            $grand_total = $res->grand_total;
    	            $read_date = $res->read_date;
    	            break;
    	        }        	
            }
            else{
            	$print_str .= "\r\n".align_center('NO SALES FOUND.',38," ")."\r\n\r\n";
            	$print_str .= "================="."\r\n";
    	        $print_str .= append_chars(substrwords('NEW GT',18,""),"right",18," ").align_center(null,5," ")
    	                     .append_chars(number_format(0,2),"left",13," ")."\r\n";
    	        $print_str .= append_chars(substrwords('OLD GT',18,""),"right",18," ").align_center(null,5," ")
    	                     .append_chars(number_format(0,2),"left",13," ")."\r\n";             	             
    	        $print_str .= "================="."\r\n"; 
            	if (!$asJson) {
    	    		// echo "<pre style='background-color:#fff'>$print_str</pre>";
    				echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
    			}
    			return false; 
            }
            $args = array();
            $terminal = $this->input->post('terminal');
            $terminal = TERMINAL_ID;
            $cashier = $this->input->post('cashier');
            if(!empty($cashier)){
                $server = $this->manager_model->get_server_details($cashier);
                $cashier_name = $server[0]->fname.' '.$server[0]->lname;
                $args['trans_sales.user_id'] = $cashier;
            }else{
                $cashier_name = 'All Cashier';
            }
            if(!empty($terminal)){
                $terminal_name = $terminal;
                $args['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }

            $time = $this->site_model->get_db_now();
            $title_name = "System Sales Report";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= append_chars(substrwords('Employee',18,""),"right",17," ")
                       	 .append_chars($cashier_name,"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('PC',18,""),"right",17," ")
                         .append_chars($terminal_name,"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Read Date',18,""),"right",17," ")
                         .append_chars(sql2Date($read_date),"left",8," ")."\r\n";             
            $print_str .= append_chars(substrwords('Date From',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_from),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Date To',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_to),"left",8," ")."\r\n";             
            $print_str .= append_chars(substrwords('Printed on',18,""),"right",17," ")
                         .append_chars(date2SqlDateTime($time),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Printed by',18,""),"right",17," ")
                         .append_chars($userdata['fname'].' '.$userdata['lname'],"left",8," ")."\r\n";
            $print_str .= "======================================"."\r\n";

            $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            $orders = array();
            $orders['cancel'] = array(); 
            $orders['sale'] = array();
            $orders['void'] = array();
            $gross = 0;
            $gross_ids = array();
            $paid = 0;
            $paid_ctr = 0;
            $types = array();
            foreach ($trans_sales as $sale) {
            	if($sale->type_id == 10){
            		if($sale->trans_ref != "" && $sale->inactive == 0){
            			$orders['sale'][$sale->sales_id] = $sale;
            			$gross += $sale->total_amount;
            			$gross_ids[] = $sale->sales_id;
            			if($sale->total_paid > 0){
    	        			$paid += $sale->total_paid;
     	 					$paid_ctr ++;      				
            			}
            			$types[$sale->type][$sale->sales_id] = $sale;
            		}
            		else if($sale->trans_ref == "" && $sale->inactive == 1){
            			$orders['cancel'][$sale->sales_id] = $sale;
            		}
            	}
            	else{
    	        	$orders['void'][$sale->sales_id] = $sale;
    	        	// $gross += $sale->total_amount;
    	        	// $gross_ids[] = $sale->sales_id;
            	}
            }
            $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$gross_ids));
            $total_disc = 0;
            $disc_codes = array();
            foreach ($sales_discs as $discs) {
            	if(!isset($disc_codes[$discs->disc_code])){
            		$disc_codes[$discs->disc_code] = array('qty'=> 1,'amount'=>$discs->amount);
            	}
            	else{
            		$disc_codes[$discs->disc_code]['qty'] += 1;
            		$disc_codes[$discs->disc_code]['amount'] += $discs->amount;
            	}
            	$total_disc += $discs->amount;
            }
            // echo $gross;
            // $gross += $total_disc;
            $total_regular_discs = 0;
            $reg_disc_ctr = 0;
            foreach ($disc_codes as $code => $dc) {
                if($code != "SNDISC" && $code != "PWDISC"){
                    $total_regular_discs += $dc['amount'];
                    $reg_disc_ctr += $dc['qty'];                                    
                }
            }
            $total_regular_discs = numInt($total_regular_discs);
            //SENIOR
            $total_senior_discs = 0;
            $seni_disc_ctr = 0;
            foreach ($disc_codes as $code => $dc) {
                if($code == "SNDISC"){
                    $total_senior_discs += $dc['amount'];
                    $seni_disc_ctr += $dc['qty'];                                    
                }
            }
            $total_senior_discs = numInt($total_senior_discs);
            //PWD
            $total_pwd_discs = 0;
            $pwd_disc_ctr = 0;
            foreach ($disc_codes as $code => $dc) {
                if($code == "PWDISC"){
                    $total_pwd_discs += $dc['amount'];
                    $pwd_disc_ctr += $dc['qty'];                                    
                }
            }
            #TAX
                $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$gross_ids,"trans_sales_zero_rated.amount >"=>0));
                // echo $this->cashier_model->db->last_query();
                // echo var_dump($zero_rated);
                $zrctr = 0;
                $zrtotal = 0;
                foreach ($zero_rated as $zt) {
                    $zrtotal += $zt->amount;
                    $zrctr++;
                }
                $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$gross_ids,"trans_sales_no_tax.amount >"=>0));
                $ntctr = 0;
                $nttotal = 0;
                foreach ($no_tax as $nt) {
                    $nttotal += $nt->amount;
                    $ntctr++;
                }
                $tctr = 0;
                $ttotal = 0;
                if(count($gross_ids) > 0){
                    $tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$gross_ids,"trans_sales_tax.amount >"=>0));
                    foreach ($tax as $t) {
                        $ttotal += $t->amount;
                        $tctr++;
                    }
                }
                // $vat = ((($gross -$total_senior_discs-$total_pwd_discs-$nttotal)/1.12)*0.12);
                


            $cargs["trans_sales_charges.sales_id"] = $gross_ids;
            $cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);   
            $total_service_charges = 0;
            $total_delivery_charges = 0;
            $other_charges = 0;
            $total_charges = 0;
            foreach ($cesults as $ces) {
                if($ces->charge_id == 1){
                    $total_service_charges += $ces->amount;
                }
                if($ces->charge_id == 2){
                    $total_delivery_charges += $ces->amount;
                }
                else{
                    $other_charges += $ces->amount;
                }
                $total_charges += $ces->amount;
            } 
            $targs["trans_sales_local_tax.sales_id"] = $gross_ids;
            $tesults = $this->site_model->get_tbl('trans_sales_local_tax',$targs);   
            $total_local_tax = 0;
            foreach ($tesults as $tes) {
                $total_local_tax += $tes->amount;
            }

            #GROSS SALES
                $net = $gross;
                $total_sales = $net;
                $total_sales += $total_senior_discs;
                $vat = ( ( ($total_sales - $total_local_tax) - ($total_charges)) - ($nttotal)) - $ttotal;

            	// $vat = $gross * .12;
                // $net_sales = $gross - $vat;
    	        $print_str .= append_chars(substrwords('Total Sales',18,""),"right",23," ")
    	                     .append_chars(number_format( $net - ($total_local_tax + $total_charges) - $ttotal ,2),"left",13," ")."\r\n";
    	        $print_str .= append_chars(substrwords('12% VAT',18,""),"right",23," ")
    	                     .append_chars(number_format($ttotal,2),"left",13," ")."\r\n";
    	        $print_str .= append_chars(substrwords('Local Tax',18,""),"right",23," ")
                             .append_chars(number_format($total_local_tax,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Total Charges',18,""),"right",23," ")
    	                     .append_chars(number_format($total_charges,2),"left",13," ")."\r\n";
    	        $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Net Sales',18,""),"right",23," ")
                             .append_chars(number_format($net,2),"left",13," ")."\r\n\r\n";     
                $print_str .= append_chars(substrwords('Total Discounts',18,""),"right",23," ")
                             .append_chars(number_format($total_disc,2),"left",13," ")."\r\n";             
                $print_str .= append_chars(substrwords('Gross W/ Disc',18,""),"right",23," ")
                             .append_chars(number_format($gross+$total_disc,2),"left",13," ")."\r\n\r\n";
    	    #CASH DRAWER
    	        /*$entries_args["shift_entries.trans_date  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);             
    	    	$shift_entries = $this->cashier_model->get_trans_sales_entries(null,$entries_args);
    	    	$deposit = 0;
    	    	$withdraw = 0;
    	    	$dep_ctr = 0;
    	    	foreach ($shift_entries as $ent) {
    	    		if($ent->amount > 0){
    	    			$deposit += $ent->amount;
    	    			$dep_ctr++;
    	    		}
    	    		else
    	    			$withdraw += abs($ent->amount);
    	    	}
    	    	$print_str .= append_chars(substrwords('Carry Over',18,""),"right",18," ").align_center(0,5," ")
                             .append_chars(number_format(0,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Deposit Total',18,""),"right",18," ").align_center($dep_ctr,5," ")
                             .append_chars(number_format($deposit,2),"left",13," ")."\r\n";             
                $print_str .= append_chars(substrwords('Begun Total',18,""),"right",18," ").align_center($paid_ctr,5," ")
                             .append_chars(number_format($paid,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Paid Total',18,""),"right",18," ").align_center($paid_ctr,5," ")
                             .append_chars(number_format($paid,2),"left",13," ")."\r\n";                          
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Outstanding',18,""),"right",23," ")
    	                     .append_chars(number_format($paid - $paid,2),"left",13," ")."\r\n\r\n";*/
    	    #TRANS COUNT
    	        $types_total = array();
    	        $guestCount = 0;
                foreach ($types as $type => $tp) {
    	    		foreach ($tp as $id => $opt){
    		        	if(isset($types_total[$type])){
    			        	$types_total[$type] += $opt->total_amount;
    		        	
                        }
    			        else{
    			        	$types_total[$type] = $opt->total_amount;
    			        }
                        if($opt->guest == 0)
                            $guestCount += 1;
                        else
                            $guestCount += $opt->guest;
    			    }    
    	        }
    	        $print_str .= append_chars(substrwords('Trans Count',18,""),"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $tc_total  = 0;
                $tc_qty = 0;
                foreach ($types_total as $typ => $tamnt) {
    	            $print_str .= append_chars(substrwords($typ,18,""),"right",18," ").align_center(count($types[$typ]),5," ")
    	                         .append_chars(number_format($tamnt,2),"left",13," ")."\r\n";             
               		$tc_total += $tamnt; 
               		$tc_qty += count($types[$typ]);
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('TC Total',18,""),"right",23," ")
                             .append_chars(number_format($tc_total,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Guest Total',18,""),"right",23," ")
    	                     .append_chars(number_format($guestCount,2),"left",13," ")."\r\n";
    	        if($tc_total == 0 || $tc_qty == 0)
                	$avg = 0;
                else
                	$avg = $tc_total/$tc_qty;             
    	        $print_str .= append_chars(substrwords('Average Check',18,""),"right",23," ")
    	                     .append_chars(number_format($avg,2),"left",13," ")."\r\n\r\n";             
            #GUEST COUNT
                /*$print_str .= append_chars(substrwords('Guest Count',18,""),"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                foreach ($types_total as $typ => $tamnt) {
    	            $print_str .= append_chars(substrwords($typ,18,""),"right",18," ").align_center(0,5," ")
    	                         .append_chars(number_format(0,2),"left",13," ")."\r\n";             
                }             
    	       	$print_str .= "-----------------"."\r\n";
    	        $print_str .= append_chars(substrwords('Average Cover',18,""),"right",23," ")
    	                     .append_chars(number_format(0,2),"left",13," ")."\r\n\r\n";*/
    	    #OTHER COLLECTION
    	        /*$print_str .= append_chars(substrwords('Other Collection',18,""),"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Tips',18,""),"right",18," ").align_center(0,5," ")
                             .append_chars(number_format(0,2),"left",13," ")."\r\n";             
    	       	$print_str .= "-----------------"."\r\n";
    	        $print_str .= append_chars(substrwords('Total Collection',18,""),"right",23," ")
    	                     .append_chars(number_format(0,2),"left",13," ")."\r\n\r\n";*/
    	    #MAJOR CATEGORIES
    	        $print_str .= append_chars(substrwords('Major Categories',18,""),"right",18," ").align_center('',5," ")
    	        			 .append_chars('',"left",13," ")."\r\n";
    	        $categories = $this->menu_model->get_menu_categories(null);

    	        $menu_cat_sales = $this->cashier_model->get_trans_sales_categories(null,array("trans_sales_menus.sales_id"=>$gross_ids));
    	        $menu_cat_sale_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$gross_ids));
    	        $cats = array();
    	        foreach ($menu_cat_sales as $cat){
    	        	$cost = $cat->price;
    	        	foreach ($menu_cat_sale_mods as $cod) {
    	        		if($cat->sales_id == $cod->sales_id && $cod->line_id == $cat->line_id){
    	        			$cost += $cod->price;
    	        		}
    	        	}
    	        	$cost = $cost * $cat->qty;
    	        	foreach ($sales_discs as $cis){
    	        		if($cis->sales_id == $cat->sales_id){
    		        		$rate = $cis->disc_rate;
    	               		switch ($cis->type) {
    	               			// case "item":
    	               			// 			$items = explode(',',$cis->items);
    	               			// 			foreach ($items as $lid) {
    	               			// 				if($cat->line_id == $lid){
    	               			// 					$discount = ($rate / 100) * $cost;
    	               			// 					$cost -= $discount;
    	               			// 				}
    	               			// 			}
    	               			// 			break;
    	                    	case "equal":
                                         $divi = $cost/$cis->guest;
                                         if($cis->no_tax == 1)
                                             $divi = ($divi / 1.12);
                                         $discount = ($rate / 100) * $divi;
                                         $cost = ($divi * $cis->guest) - $discount;
                                         break;
                                default:
                                     if($cis->no_tax == 1)
                                         $cost = ($cost / 1.12);                     
                                     $discount = ($rate / 100) * $cost;
                                     $cost -= $discount;  
    	               		}		        			
    	        		}
    	        	}
    	        	if(!isset($cats[$cat->menu_cat_id])){
    					$cats[$cat->menu_cat_id] = array(
    						"cat_name"=>$cat->menu_cat_name,
    						"amount"=>$cost,
    						"qty"=>$cat->qty
    					);						
    	        	}
    	        	else{
    	        		$cats[$cat->menu_cat_id]['amount'] += $cost;
    	        		$cats[$cat->menu_cat_id]['qty'] += $cat->qty;
    	        	}
    	        }
    	        $cat_totals = 0;
    	        $cat_qtys = 0;
    	        foreach ($categories as $opt) {
    	        	if(isset($cats[$opt->menu_cat_id])){
    	        		$cate = $cats[$opt->menu_cat_id];
    	        		$print_str .= append_chars(substrwords($cate['cat_name'],18,""),"right",18," ").align_center($cate['qty'],5," ")
    	                             .append_chars(number_format($cate['amount'],2),"left",13," ")."\r\n";
    		        	$cat_totals += $cate['amount'];
    		        	$cat_qtys += $cate['qty'];
    	        	}
    	        	else{
    		        	$print_str .= append_chars(substrwords($opt->menu_cat_name,18,""),"right",18," ").align_center(0,5," ")
    	                             .append_chars(number_format(0,2),"left",13," ")."\r\n";	        		
    	        	}
    	        }
    		    $print_str .= "-----------------"."\r\n";
    	        $print_str .= append_chars(substrwords('Total',18,""),"right",18," ").align_center($cat_qtys,5," ")
    	                     .append_chars(number_format($cat_totals,2),"left",13," ")."\r\n\r\n";              
    	    #CASH LOANDS ETC.
    	        /*$print_str .= append_chars(substrwords('Loan',18,""),"right",18," ").align_center(0,5," ")
                             .append_chars(number_format(0,2),"left",13," ")."\r\n";             
    	       	$print_str .= "-----------------"."\r\n";
    	       	$print_str .= append_chars(substrwords('Cash Sales',18,""),"right",18," ").align_center(count($gross_ids),5," ")
                             .append_chars(number_format($gross,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Pickup',18,""),"right",18," ").align_center(0,5," ")
                             .append_chars(number_format(0,2),"left",13," ")."\r\n";             
                $print_str .= append_chars(substrwords('Cash In Drawer',18,""),"right",18," ").align_center(count($gross_ids),5," ")
                             .append_chars(number_format($gross,2),"left",13," ")."\r\n\r\n";*/
            #PAYMENT DETAILS
                // $payments = $this->cashier_model->get_trans_sales_payments_group(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                // $pays = array();
                // foreach ($payments as $py) {
                // 	if(!isset($pays[$py->payment_type])){
                // 		$pays[$py->payment_type] = array('qty'=>$py->count,'amount'=>$py->total_paid);
                // 	}
                // 	else{
                // 		$pays[$py->payment_type]['qty'] += $py->count;
                // 		$pays[$py->payment_type]['amount'] += $py->total_paid;
                // 	}
                // }
                // $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                //              .append_chars(null,"left",13," ")."\r\n"; 
                // $pay_total = 0;
                // $pay_qty = 0;
                // foreach ($pays as $type => $pay) {
                // 	$print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                //                  .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
    	           //  $pay_total += $pay['amount'];
    	           //  $pay_qty += $pay['qty'];
                // }
                // $print_str .= "-----------------"."\r\n";
                // $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                //              .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";
                $payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                $pays = array();
                foreach ($payments as $py) {
                    if($py->amount > $py->to_pay)
                        $amount = $py->to_pay;
                    else
                        $amount = $py->amount;
                    if(!isset($pays[$py->payment_type])){
                        $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                    }
                    else{
                        $pays[$py->payment_type]['qty'] += 1;
                        $pays[$py->payment_type]['amount'] += $amount;
                    }
                }
                $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                         .append_chars(null,"left",13," ")."\r\n"; 
                $pay_total = 0;
                $pay_qty = 0;
                foreach ($pays as $type => $pay) {
                    $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                                 .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                    $pay_total += $pay['amount'];
                    $pay_qty += $pay['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                             .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";
            #DISCOUNTS DETAILS
                $print_str .= append_chars(substrwords('Discount Details',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(null,"left",13," ")."\r\n"; 
               	$disc_amount = 0;
               	$disc_qty = 0;
                foreach ($disc_codes as $dodes => $di) {
                	$print_str .= append_chars(substrwords(strtoupper($dodes),18,""),"right",18," ").align_center($di['qty'],5," ")
                                 .append_chars(number_format($di['amount'],2),"left",13," ")."\r\n";
                	$disc_amount += $di['amount'];
                	$disc_qty += $di['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Discount',18,""),"right",18," ").align_center($disc_qty,5," ")
                             .append_chars(number_format($disc_amount,2),"left",13," ")."\r\n";
                $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$gross_ids,"trans_sales_zero_rated.amount >"=>0));
                // echo $this->cashier_model->db->last_query();
                // echo var_dump($zero_rated);
                $zrctr = 0;
                $zrtotal = 0;
                foreach ($zero_rated as $zt) {
                    $zrtotal += $zt->amount;
                    $zrctr++;
                }             
                $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$gross_ids,"trans_sales_no_tax.amount >"=>0));
                $ntctr = 0;
                $nttotal = 0;
                foreach ($no_tax as $nt) {
                    $nttotal += $nt->amount;
                    $ntctr++;
                }             
             #NO TAX DETAILS   
                $print_str .= append_chars(substrwords('VAT EXEMPT',18,""),"right",18," ").align_center(abs($ntctr-$zrctr),5," ")
                             .append_chars(number_format(abs($nttotal-$zrtotal),2),"left",13," ")."\r\n";
             #ZERO RATED DETAILS   
                $print_str .= append_chars(substrwords('VAT ZERO RATED',18,""),"right",18," ").align_center($zrctr,5," ")
                             .append_chars(number_format($zrtotal,2),"left",13," ")."\r\n\r\n";                 
            #TRANS TYPE ANALYS
            	$print_str .= append_chars(substrwords('Trans Typ Analys',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(null,"left",13," ")."\r\n";
                $print_str .= "-----------------"."\r\n";
                foreach ($types_total as $typ => $tamnt) {
    				$print_str .= append_chars(substrwords($typ,18,""),"right",18," ").align_center(count($types[$typ]),5," ")
                                 .append_chars(number_format($tamnt,2),"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords($typ." %",18,""),"right",18," ").align_center(0,5," ")
                                 .append_chars(number_format(($tamnt/$tc_total)*100,2),"left",13," ")."\r\n";             
    			}                            
    			$print_str .= "\r\n";
    			$cancel_total = 0;
    			foreach ($orders['cancel'] as $cnl) {
    				$cancel_total += $cnl->total_amount;

    			}
    			$print_str .= append_chars(substrwords('Void Before Subt',18,""),"right",18," ").align_center(count($orders['cancel']),5," ")
                             .append_chars(number_format($cancel_total,2),"left",13," ")."\r\n";
      			if($cancel_total == 0 || count($orders['cancel']) == 0)
                	$avg = 0;
                else
                	$avg = $cancel_total/count($orders['cancel']);
      			$print_str .= append_chars(substrwords('AVG Void B4 Subt',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(number_format($avg,2),"left",13," ")."\r\n";
      			
                $void_total = 0;
    			foreach ($orders['void'] as $v) {
    				$void_total += $v->total_amount;
    			}            
                $print_str .= append_chars(substrwords('Void After Subt',18,""),"right",18," ").align_center(count($orders['void']),5," ")
                             .append_chars(number_format($void_total,2),"left",13," ")."\r\n";
                if($void_total == 0 || count($orders['void']) == 0)
                	$avg = 0;
                else
                	$avg = $void_total/count($orders['void']);             
      			$print_str .= append_chars(substrwords('AVG Void Aft Subt',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(number_format($avg,2),"left",13," ")."\r\n";
                $print_str .= "-----------------"."\r\n\r\n";
            #display voided
                // if(count($orders['cancel']) > 0){
                //     $print_str .= append_chars(substrwords('Voids B4 Subt',18,""),"right",18," ").align_center(null,5," ")
                //                  .append_chars(null,"left",13," ")."\r\n";
                //     $print_str .= "-----------------"."\r\n";
                //     foreach ($orders['cancel'] as $cnl) {
                //         $print_str .= append_chars(substrwords("Order #".$cnl->sales_id,18,""),"right",18," ").align_center('',5," ")
                //                  .append_chars(number_format($cnl->total_amount,2),"left",13," ")."\r\n";
                //         if($cnl->table_name != ""){
                //             $print_str .= append_chars(substrwords("--".$cnl->table_name,18,""),"right",18," ").align_center('',5," ")
                //                      .append_chars('',"left",13," ")."\r\n";
                            
                //         }
                //         $print_str .= "*************"."\r\n";
                //     }
                //     $print_str .= "-----------------"."\r\n";
                // }
                if(count($orders['void']) > 0){
                    $print_str .= append_chars(substrwords('Voids Aft Subt',18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(null,"left",13," ")."\r\n";
                    $print_str .= "-----------------"."\r\n";
                    foreach ($orders['void'] as $v) {
                        $print_str .= append_chars(substrwords("Ref ".$v->trans_ref,18,""),"right",18," ").align_center('',5," ")
                                 .append_chars(number_format($v->total_amount,2),"left",13," ")."\r\n";
                        if($v->table_name != ""){
                            $print_str .= append_chars(substrwords("--".$v->table_name,18,""),"right",18," ").align_center('',5," ")
                                     .append_chars('',"left",13," ")."\r\n";
                            
                        }
                        $print_str .= "*************"."\r\n";
                    }                
                    $print_str .= "-----------------"."\r\n";
                }

            #GT    
    	        $print_str .= "================="."\r\n";
    	        $print_str .= append_chars(substrwords('NEW GT',18,""),"right",18," ").align_center(null,5," ")
    	                     .append_chars(number_format($grand_total,2),"left",13," ")."\r\n";
    	        $print_str .= append_chars(substrwords('OLD GT',18,""),"right",18," ").align_center(null,5," ")
    	                     .append_chars(number_format($old_gt_amnt,2),"left",13," ")."\r\n";             	             
    	        $print_str .= "================="."\r\n";             

        	if (!$asJson) {
        		// echo "<pre style='background-color:#fff'>$print_str</pre>";
    			echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
    		}  
        }
        public function z_read_rep($asJson=false,$show_zread=false){
            $print_str = $this->rep_header();
            $userdata = $this->session->userdata('user');
            $title_name = "Z READ";
            // $date = '11/28/2014';
            $time = $this->site_model->get_db_now();
            if(!$show_zread){
                $date = $this->input->post('daterange');
                // $date = '2015-06-30';
                // $this->db = $this->load->database('main', TRUE);
                $lastRead = $this->cashier_model->get_last_z_read_on_date(Z_READ,date2Sql($date));
                $old_gt_amnt = 0;
                $grand_total = 0;
                $read_date = $date;
                $date_to = $date;
                if(count($lastRead) > 0){
                    foreach ($lastRead as $res) {
                        $date_from = $res->scope_from;
                        $date_to = $res->scope_to;
                        $old_gt_amnt = $res->old_total;
                        $grand_total = $res->grand_total;
                        $read_date = $res->read_date;
                        break;
                    }           
                }
                else{
                    $print_str .= "\r\n".align_center('NO SALES FOUND.',38," ")."\r\n\r\n";
                    $print_str .= "================="."\r\n";
                    $print_str .= append_chars(substrwords('NEW GT',18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(number_format(0,2),"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords('OLD GT',18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(number_format(0,2),"left",13," ")."\r\n";                             
                    $print_str .= "================="."\r\n"; 
                    if (!$asJson) {
                        // echo "<pre style='background-color:#fff'>$print_str</pre>";
                        echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
                    }
                    return false; 
                }
            }    
            else{
                $read_date = $this->input->post('read_date');
                $date_from = $this->input->post('date_from');
                $max_date = $this->input->post('max_date');
                $date_to = $this->input->post('date_to');
                $old_gt_amnt = 0;
                $grand_total = 0;
                $resultx = $this->cashier_model->get_last_new_gt(Z_READ,$max_date,$date_from);
                if (!empty($resultx[0])){
                    $old_gt_amnt = $resultx[0]->grand_total;
                }
            }

            $terminal = TERMINAL_ID;
            if(!empty($terminal)){
                $terminal_name = $terminal;
                $args['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }
            
            
            $print_str .= align_center($title_name,38," ")."\r\n\r\n";
            // $print_str .= append_chars(substrwords('Terminal',18,""),"right",17," ")
            //              .append_chars($terminal_name,"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Read Date',18,""),"right",17," ")
                         .append_chars(sql2Date($read_date),"left",8," ")."\r\n";             
            $print_str .= append_chars(substrwords('Date From',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_from),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Date To',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_to),"left",8," ")."\r\n";             
            $print_str .= append_chars(substrwords('Printed on',18,""),"right",17," ")
                         .append_chars(date2SqlDateTime($time),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Printed by',18,""),"right",17," ")
                         .append_chars($userdata['full_name'],"left",8," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            // echo $this->cashier_model->db->last_query();
            if(count($trans_sales) == 0){
                $print_str .= "\r\n".align_center('NO SALES FOUND.',38," ")."\r\n\r\n";
                $print_str .= "================="."\r\n";
                $print_str .= append_chars(substrwords('NEW GT',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(number_format($grand_total,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('OLD GT',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(number_format($old_gt_amnt,2),"left",13," ")."\r\n";                             
                $print_str .= "================="."\r\n"; 
                if (!$asJson) {
                    // echo "<pre style='background-color:#fff'>$print_str</pre>";
                    echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
                }
                return false; 
            }
            // echo $this->cashier_model->db->last_query();
            $orders = array();
            $orders['cancel'] = array(); 
            $orders['sale'] = array();
            $orders['void'] = array();
            $gross = 0;
            $gross_ids = array();
            $paid = 0;
            $paid_ctr = 0;
            $types = array();
            $sales_total = 0;
            foreach ($trans_sales as $sale) {
                if($sale->type_id == 10){
                    if($sale->trans_ref != "" && $sale->inactive == 0){
                        $orders['sale'][$sale->sales_id] = $sale;
                        $gross += $sale->total_amount;
                        $gross_ids[] = $sale->sales_id;
                        if($sale->total_paid > 0){
                            $paid += $sale->total_paid;
                            $paid_ctr ++;                   
                        }
                        $types[$sale->type][$sale->sales_id] = $sale;
                        $sales_total += $sale->total_amount;
                    }
                    else if($sale->trans_ref == "" && $sale->inactive == 1){
                        $orders['cancel'][$sale->sales_id] = $sale;
                    }
                    else if($sale->trans_ref != "" && $sale->inactive == 1){
                        $orders['deleted'][$sale->sales_id] = $sale;
                        
                    }
                }
                else{
                    $orders['void'][$sale->sales_id] = $sale;
                    // $gross += $sale->total_amount;
                    // $gross_ids[] = $sale->sales_id;
                }
            }
            if($show_zread){
                $grand_total = $old_gt_amnt + $sales_total;
            }    
            $print_str .= "\r\n";
            #DISOUNTS 
                $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$gross_ids));
                $total_disc = 0;
                $disc_codes = array();
                foreach ($sales_discs as $discs) {
                    if(!isset($disc_codes[$discs->disc_code])){
                        $disc_codes[$discs->disc_code] = array('qty'=> 1,'amount'=>$discs->amount);
                    }
                    else{
                        $disc_codes[$discs->disc_code]['qty'] += 1;
                        $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                    }
                    $total_disc += $discs->amount;
                }
            #GROSS    
                $total_regular_discs = 0;
                $reg_disc_ctr = 0;
                foreach ($disc_codes as $code => $dc) {
                    if($code != "SNDISC" && $code != "PWDISC"){
                        $total_regular_discs += $dc['amount'];
                        $reg_disc_ctr += $dc['qty'];                                    
                    }
                }
                $total_regular_discs = numInt($total_regular_discs);
                //SENIOR
                $total_senior_discs = 0;
                $seni_disc_ctr = 0;
                foreach ($disc_codes as $code => $dc) {
                    if($code == "SNDISC"){
                        $total_senior_discs += $dc['amount'];
                        $seni_disc_ctr += $dc['qty'];                                    
                    }
                }
                $total_senior_discs = numInt($total_senior_discs);
                //PWD
                $total_pwd_discs = 0;
                $pwd_disc_ctr = 0;
                foreach ($disc_codes as $code => $dc) {
                    if($code == "PWDISC"){
                        $total_pwd_discs += $dc['amount'];
                        $pwd_disc_ctr += $dc['qty'];                                    
                    }
                }
                $total_pwd_discs = numInt($total_pwd_discs);
                //LOCAL TAX
                $localTax=0;
                $net = $gross;
                $gross += ($total_regular_discs+$total_senior_discs+$total_pwd_discs+$localTax);
                $gross = numInt($gross);
                $void_total = 0;
                foreach ($orders['void'] as $v) {
                    $void_total += $v->total_amount;
                }            
                $cargs["trans_sales_charges.sales_id"] = $gross_ids;
                $cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);   
                $total_service_charges = 0;
                $total_delivery_charges = 0;
                $other_charges = 0;
                $total_charges = 0;
                foreach ($cesults as $ces) {
                    if($ces->charge_id == 1){
                        $total_service_charges += $ces->amount;
                    }
                    if($ces->charge_id == 2){
                        $total_delivery_charges += $ces->amount;
                    }
                    else{
                        $other_charges += $ces->amount;
                    }
                    $total_charges += $ces->amount;
                } 
                $targs["trans_sales_local_tax.sales_id"] = $gross_ids;
                $tesults = $this->site_model->get_tbl('trans_sales_local_tax',$targs);   
                $total_local_tax = 0;
                foreach ($tesults as $tes) {
                    $total_local_tax += $tes->amount;
                }
                $print_str .= append_chars(substrwords('Gross Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($gross,"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('Regular Discounts: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($total_regular_discs,"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('Senior Discounts: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($total_senior_discs,"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('PWD Discounts: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($total_pwd_discs,"left",13," ")."\r\n"; 
                if($total_charges > 0){
                    $print_str .= append_chars(substrwords('Charges: ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars(num($total_charges),"left",13," ")."\r\n"; 
                }
                if($total_local_tax > 0){
                    $print_str .= append_chars(substrwords('Local Tax: ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars(num($total_local_tax),"left",13," ")."\r\n"; 
                }
                $print_str .= append_chars(substrwords('Net Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(num($net),"left",13," ")."\r\n";                                                        
                $print_str .= append_chars(substrwords('Void Sales: ',18,""),"right",18," ").align_center(count($orders['void']),5," ")
                             .append_chars("(".number_format($void_total,2).")","left",13," ")."\r\n";
            $print_str .= "\r\n";
            #TAX
                $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$gross_ids,"trans_sales_zero_rated.amount >"=>0));
                // echo $this->cashier_model->db->last_query();
                // echo var_dump($zero_rated);
                $zrctr = 0;
                $zrtotal = 0;
                foreach ($zero_rated as $zt) {
                    $zrtotal += $zt->amount;
                    $zrctr++;
                }
                $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$gross_ids,"trans_sales_no_tax.amount >"=>0));
                $ntctr = 0;
                $nttotal = 0;
                foreach ($no_tax as $nt) {
                    $nttotal += $nt->amount;
                    $ntctr++;
                }
                $tctr = 0;
                $ttotal = 0;
                if(count($gross_ids) > 0){
                    $tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$gross_ids,"trans_sales_tax.amount >"=>0));
                    foreach ($tax as $t) {
                        $ttotal += $t->amount;
                        $tctr++;
                    }
                }
                // $vat = ((($gross -$total_senior_discs-$total_pwd_discs-$nttotal)/1.12)*0.12);
                $total_sales = $net;
                $total_sales += $total_senior_discs;
                $vat = ( ( ($total_sales - $total_local_tax) - ($total_charges)) - ($nttotal)) - $ttotal;
                // $vat = ( ( ($total_sales - $total_disc) - ($total_charges)) - ($nttotal)) - $ttotal;
                // echo $net;
                // $vat = ($net)-$nttotal-$ttotal;

                $nttotal = $nttotal-$zrtotal;
                $print_str .= append_chars(substrwords('VAT Exempt Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($nttotal),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($vat),"left",13," ")."\r\n";               
                $print_str .= append_chars(substrwords('VAT: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($ttotal),"left",13," ")."\r\n";  
                $print_str .= append_chars(substrwords('Zero Rated: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($zrtotal),"left",13," ")."\r\n";               
            $print_str .= "\r\n";
            #PAYMENT DETAILS
                //     $payments = $this->cashier_model->get_trans_sales_payments_group(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                //     $pays = array();
                //     foreach ($payments as $py) {
                //         if(!isset($pays[$py->payment_type])){
                //             $pays[$py->payment_type] = array('qty'=>$py->count,'amount'=>$py->total_paid);
                //         }
                //         else{
                //             $pays[$py->payment_type]['qty'] += $py->count;
                //             $pays[$py->payment_type]['amount'] += $py->total_paid;
                //         }
                //     }
                //     $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                //                  .append_chars(null,"left",13," ")."\r\n"; 
                //     $pay_total = 0;
                //     $pay_qty = 0;
                //     foreach ($pays as $type => $pay) {
                //         $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                //                      .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                //         $pay_total += $pay['amount'];
                //         $pay_qty += $pay['qty'];
                //     }
                //     $print_str .= "-----------------"."\r\n";
                //     $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                //                  .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";    
                    $payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                    $pays = array();
                    foreach ($payments as $py) {
                        if($py->amount > $py->to_pay)
                            $amount = $py->to_pay;
                        else
                            $amount = $py->amount;
                        if(!isset($pays[$py->payment_type])){
                            $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                        }
                        else{
                            $pays[$py->payment_type]['qty'] += 1;
                            $pays[$py->payment_type]['amount'] += $amount;
                        }
                    }
                    $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(null,"left",13," ")."\r\n"; 
                    $pay_total = 0;
                    $pay_qty = 0;
                    foreach ($pays as $type => $pay) {
                        $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                                     .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                        $pay_total += $pay['amount'];
                        $pay_qty += $pay['qty'];
                    }
                    $print_str .= "-----------------"."\r\n";
                    $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                                 .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";    

            $print_str .= "\r\n";
            // #INVOICE NUMS
            //     // ksort($orders['sale']);
            //     // $first = array_shift(array_slice($orders['sale'], 0, 1));
            //     // $last = end($orders['sale']);
            //     // $ref_ctr = count($orders['sale']);

            //     $ordsnums = array();
            //     foreach ($orders as $typ => $ord) {
            //         if($typ != 'void'){
            //             foreach ($ord as $sales_id => $sale) {
            //                 $ordsnums[$sales_id] = $sale;
            //             }                    
            //         }
            //     }
            //     ksort($ordsnums);
            //     $first = array_shift(array_slice($ordsnums, 0, 1));
            //     $last = end($ordsnums);
            //     $ref_ctr = count($ordsnums);

            //     $print_str .= append_chars(substrwords('Invoice Start: ',18,""),"right",18," ").align_center('',5," ")
            //                  .append_chars($first->trans_ref,"left",13," ")."\r\n";
            //     $print_str .= append_chars(substrwords('Invoice End: ',18,""),"right",18," ").align_center('',5," ")
            //                  .append_chars($last->trans_ref,"left",13," ")."\r\n";    
            //     $print_str .= append_chars(substrwords('Invoice Ctr: ',18,""),"right",18," ").align_center('',5," ")
            //                  .append_chars($ref_ctr,"left",13," ")."\r\n";

            #INVOICE NUMS
                $ordsnums = array();
                // $ref_ctr = 0;
                foreach ($orders as $typ => $ord) {
                    if($typ != 'void' && $typ != 'cancel'){
                        foreach ($ord as $sales_id => $sale) {
                            // $ordsnums[$sales_id] = $sale;
                            $ordsnums[$sale->trans_ref] = $sale;
                            // $ref_ctr++;
                        }                    
                    }
                }
                ksort($ordsnums);
                // echo var_dump($ordsnums);
                $first = array_shift(array_slice($ordsnums, 0, 1));
                $last = end($ordsnums);
                $ref_ctr = count($ordsnums);
                $print_str .= append_chars(substrwords('Invoice Start: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($first->trans_ref,"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Invoice End: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($last->trans_ref,"left",13," ")."\r\n";    
                $print_str .= append_chars(substrwords('Invoice Ctr: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($ref_ctr,"left",13," ")."\r\n";                      
            $print_str .= "\r\n";
            #READINGS
                $lastRead = $this->cashier_model->get_lastest_z_read(Z_READ,date2Sql($read_date));
                $lastOLDGT=0;
                $lastNEWGT=0;
                $lastGT_ctr=0;
                if(count($lastRead) > 0){
                    foreach ($lastRead as $res) {
                        // $lastOLDGT = $res->old_total;
                        $lastNEWGT = $res->grand_total;
                        $lastGT_ctr++;
                    }           
                }
                $print_str .= "======================================"."\r\n";
                $print_str .= append_chars(substrwords('OLD GT',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(numInt($old_gt_amnt),"left",13," ")."\r\n";                              
                $print_str .= append_chars(substrwords('NEW GT',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(numInt($grand_total),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Z Ctr',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars($lastGT_ctr+1,"left",13," ")."\r\n";
                $print_str .= "======================================"."\r\n";                                                   
            if (!$asJson) {
                // echo "<pre style='background-color:#fff'>$print_str</pre>";
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
            }  
        }
        public function menu_sales_rep_old($asJson=false){
            $print_str = $this->rep_header();
            $userdata = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $daterange = $this->input->post('daterange');
            $daterange = '06/22/2015';
            // $dates = explode(" to ",$daterange);
            // $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            // $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
            $date = $daterange;
            $this->db = $this->load->database('main', TRUE);

            $lastRead = $this->cashier_model->get_last_z_read_on_date(Z_READ,date2Sql($date));
            $old_gt_amnt = 0;
            $grand_total = 0;
            $read_date = $date;
            $date_to = $date;
            if(count($lastRead) == 0){
                $print_str = "no sales found.";
            }
            else{
                foreach ($lastRead as $res) {
                    $date_from = $res->scope_from;
                    $date_to = $res->scope_to;
                    $old_gt_amnt = $res->old_total;
                    $grand_total = $res->grand_total;
                    $read_date = $res->read_date;
                    break;
                }
            }
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $args["trans_sales.inactive"] = 0;
            $args["trans_sales.type_id"] = SALES_TRANS;
            $args["DATE(trans_sales.datetime) BETWEEN DATE('".$date_from."') AND DATE('".$date_to."')"] = array('use'=>'where','val'=>null,'third'=>false);
            // $this->db = $this->load->database('main', TRUE);
            if($this->input->post('user_id')){
                $args['trans_sales.user_id'] = $this->input->post('user_id');
            }
            $terminal = TERMINAL_ID;
            if(!empty($terminal)){
                $terminal_name = $terminal;
                $args['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }

            $sales_menus = $this->cashier_model->get_menu_sales(null,$args);
            $user = $this->session->userdata('user');
            $emp = 'ALL';
            if($this->input->post('user_id')){
                $server = $this->manager_model->get_server_details($this->input->post('user_id'));
                $emp = $server[0]->fname." ".$server[0]->mname." ".$server[0]->lname." ".$server[0]->suffix;
            }
            $title_name = "Menu Sales Report";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= "\r\n";
            $print_str .= append_chars('Terminal','right',14," ").append_chars($terminal_name,'right',19," ")."\r\n";
            $print_str .= append_chars('Employee','right',14," ").append_chars($emp,'right',19," ")."\r\n";
            // $print_str .= append_chars('Transaction Type','right',19," ").append_chars('ALL','right',19," ")."\r\n";
            $print_str .= append_chars('Sales Type','right',14," ").append_chars('ALL','right',19," ")."\r\n";
            $print_str .= append_chars('Date Range','right',14," ").append_chars($date_from.' - '.$date_to,'right',19," ")."\r\n";
            $print_str .= append_chars('Printed On','right',14," ").append_chars(date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',14," ").append_chars($user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";


            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            if(count($trans_sales) == 0){
                $print_str .= "\r\n".align_center('NO SALES FOUND.',38," ")."\r\n\r\n";
                $print_str .= "======================================"."\r\n";

                $print_str .= append_chars('TOTAL GROSS SALES',"right",18," ")
                             .append_chars(num(0),'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL ITEMS DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";

                $print_str .= append_chars('TOTAL NET SALES',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";

                if (!$asJson) {
                    // echo "<pre style='background-color:#fff'>$print_str</pre>";
                    echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
                }
                return false; 
            }
            $orders = array();
            $orders['cancel'] = array(); 
            $orders['sale'] = array();
            $orders['void'] = array();
            $gross = 0;
            $gross_ids = array();
            $paid = 0;
            $paid_ctr = 0;
            $types = array();
            $sales_total = 0;
            foreach ($trans_sales as $sale) {
                if($sale->type_id == 10){
                    if($sale->trans_ref != "" && $sale->inactive == 0){
                        $orders['sale'][$sale->sales_id] = $sale;
                        $gross += $sale->total_amount;
                        $gross_ids[] = $sale->sales_id;
                        if($sale->total_paid > 0){
                            $paid += $sale->total_paid;
                            $paid_ctr ++;                   
                        }
                        $types[$sale->type][$sale->sales_id] = $sale;
                        $sales_total += $sale->total_amount;
                    }
                    else if($sale->trans_ref == "" && $sale->inactive == 1){
                        $orders['cancel'][$sale->sales_id] = $sale;
                    }
                    else if($sale->trans_ref != "" && $sale->inactive == 1){
                        $orders['deleted'][$sale->sales_id] = $sale;
                        
                    }
                }
                else{
                    $orders['void'][$sale->sales_id] = $sale;
                    // $gross += $sale->total_amount;
                    // $gross_ids[] = $sale->sales_id;
                }
            }
            $jargs['trans_sales_menus.sales_id'] = $gross_ids;
            $sales_menus = $this->cashier_model->get_menu_sales(null,$jargs);
            echo $this->cashier_model->db->last_query();
            if(count($sales_menus) > 0){
                $sub_cats = array();
                $sales_menu_cat = $this->menu_model->get_menu_subcategories();
                foreach ($sales_menu_cat as $rc) {
                    $sub_cats[$rc->menu_sub_cat_id] = array('name'=>$rc->menu_sub_cat_name,'qty'=>0,'amount'=>0);
                }

                $cats = array();
                $cat_res = $this->site_model->get_tbl('menu_categories');
                foreach ($cat_res as $ces) {
                    $cats[$ces->menu_cat_id] = array('cat_id'=>$ces->menu_cat_id,'name'=>$ces->menu_cat_name,'qty'=>0,'amount'=>0);
                }
                $oaQty = 0;
                $oaVal = 0;
                $menus = array();
                foreach ($sales_menus as $res) {
                    $oaQty += $res->total_qty;
                    $oaVal += $res->total_amount;
                    if(!isset($menus[$res->menu_id])){
                        $menus[$res->menu_id] = array('cat_id'=>$res->menu_cat_id,'name'=>$res->menu_name,'qty'=>$res->total_qty,'amount'=>$res->total_amount);
                    }
                    else{
                        $row = $menus[$res->menu_id];
                        $row['qty'] += $res->total_qty;
                        $row['amount'] += $res->total_amount;
                        $menus[$res->menu_id] = $row;
                    }
                    $sales_discs[$res->sales_id] = $res->sales_id;
                    if(!isset($sub_cats[$res->sub_cat_id])){
                        $sub_cats[$res->sub_cat_id] = array('name'=>$res->sub_cat_name,'qty'=>$res->total_qty,'amount'=>$res->total_amount);
                    }
                    else{
                        $row = $sub_cats[$res->sub_cat_id];
                        $row['qty'] += $res->total_qty;
                        $row['amount'] += $res->total_amount;
                        $sub_cats[$res->sub_cat_id] = $row;
                    }
                    if(isset($cats[$res->menu_cat_id])){
                        $row = $cats[$res->menu_cat_id];
                        $row['qty'] += $res->total_qty;
                        $row['amount'] += $res->total_amount;
                        $cats[$res->menu_cat_id] = $row;
                    }

                }
                usort($cats, function($a, $b) {
                    return $b['qty'] - $a['qty'];
                });
                foreach ($cats as $cat_id => $ca) {
                    if($ca['qty'] > 0){
                        $print_str .=
                             append_chars($ca['name'],'right',18," ")
                            .append_chars(num($ca['qty']),'right',10," ")
                            .append_chars(num($ca['amount']).'','left',10," ")."\r\n";
                        $print_str .= "======================================"."\r\n";    
                        foreach ($menus as $menu_id => $res) {
                            if($ca['cat_id'] == $res['cat_id']){
                            $print_str .=
                                append_chars($res['name'],'right',18," ")
                                .append_chars(num($res['qty']),'right',10," ")
                                .append_chars(num( ($res['qty'] / $oaQty) * 100).'%','left',10," ")."\r\n";
                            $print_str .=
                                append_chars(null,'right',18," ")
                                .append_chars(num($res['amount']),'right',10," ")
                                .append_chars(num( ($res['amount'] / $oaVal) * 100).'%','left',10," ")."\r\n";
                            }
                        }
                        $print_str .= "======================================"."\r\n";    
                    }
                }

                // $print_str .= "======================================"."\r\n";
                $print_str .= append_chars('Sub Categories',"right",18," ")."\r\n";
                foreach ($sub_cats as $subid => $sc) {
                    $print_str .= append_chars($sc['name'],"right",18," ")
                             .append_chars(num($sc['qty']),'right',10," ")
                             .append_chars(num($sc['amount']),"left",10," ")."\r\n";
                }
                $print_str .= "======================================"."\r\n";
                // $print_str .= append_chars('TOTAL SALES',"right",18," ")
                //              .append_chars(num($oaQty),'right',10," ")
                //              .append_chars(num($oaVal),"left",10," ")."\r\n";

                $menu_cat_sale_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$gross_ids));
                $total_md = 0;
                if($menu_cat_sale_mods > 0){
                    $mods = array();
                    foreach ($menu_cat_sale_mods as $res) {
                        if(!isset($mods[$res->mod_id])){
                            $mods[$res->mod_id] = array(
                                'name'=>$res->mod_name,
                                'price'=>$res->price,
                                'qty'=>$res->qty,
                                'total_amt'=>$res->qty * $res->price,
                            );
                        }
                        else{
                            $mod = $mods[$res->mod_id];
                            $mod['qty'] += $res->qty;
                            $mod['total_amt'] += $res->qty * $res->price;
                            $mods[$res->mod_id] = $mod;
                        }
                    }
                    usort($mods, function($a, $b) {
                        return $b['total_amt'] - $a['total_amt'];
                    });
                    $print_str .= append_chars('Menu Modifiers',"right",18," ")."\r\n";
                    foreach ($mods as $modid => $md) {
                        $print_str .= append_chars($md['name'],"right",18," ")
                                 .append_chars(num($md['qty']),'right',10," ")
                                 .append_chars(num($md['total_amt']),"left",10," ")."\r\n";
                        $total_md += $md['total_amt'];
                    }
                    $print_str .= append_chars('',"right",18," ")
                             .append_chars('','right',10," ")
                             .append_chars('----------',"left",10," ")."\r\n";
                    $print_str .= append_chars('',"right",18," ")
                             .append_chars('','right',10," ")
                             .append_chars(num($total_md),"left",10," ")."\r\n";
                    $print_str .= "======================================"."\r\n";

                }

                $slsd = array();
                foreach ($sales_discs as $sids => $val) {
                    $slsd[] = $sids;
                }

                $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$slsd));
                $items_disc = 0;
                foreach ($sales_discs as $dsc) {
                    $items_disc += $dsc->amount;
                }
                $total_less_vat = ($oaVal+$total_md) - ($gross + $items_disc);
                if($total_less_vat < 0){
                    $total_less_vat = 0;
                }
                $print_str .= append_chars('TOTAL OVERALL',"right",18," ")
                             .append_chars(num($oaQty),'right',10," ")
                             .append_chars(num($oaVal + $total_md),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL Less VAT',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($total_less_vat),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL GROSS SALES',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($gross + $items_disc),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL ITEMS DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($items_disc),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL NET SALES',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($gross),"left",10," ")."\r\n";
            }
            else{
                $print_str .= align_center('NO Sales',38," ")."\r\n";
                $print_str .= "======================================"."\r\n";
                $print_str .= append_chars('TOTAL SALES',"right",18," ")
                             .append_chars(num(0),'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL ITEMS DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL OTHER DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL NET SALES',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
            }
            if (!$asJson) {
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
                echo "<pre style='background-color:#fff'>$print_str</pre>";
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
            }
        }
        public function x_read_rep($asJson=false,$show_xread=false,$autoShiftPrint=false){
            $print_str = $this->rep_header();
            $userdata = $this->session->userdata('user');
            $title_name = "X READ";
            $curr_shift_id = null;
            $time = $this->site_model->get_db_now();
            if(!$show_xread){
                $date = $this->input->post('daterange');
                $cashier = $this->input->post('cashier');
                // $date = '2015-06-09';
                // $cashier = 51;
                // $this->db = $this->load->database('main', TRUE);
                $user_cash = null;
                if(!empty($cashier)){
                    $user_cash = $cashier;
                }    
                $lastRead = $this->cashier_model->get_last_z_read_on_date(X_READ,date2Sql($date),$user_cash);

                $today = sql2Date($this->site_model->get_db_now());
                $useID = 'id';
                if(strtotime($date) < strtotime($today)){
                    $useID = 'read_id';
                }

                $old_gt_amnt = 0;
                $grand_total = 0;
                $read_date = $date;
                $date_to = $date;
                if(count($lastRead) > 0){
                    foreach ($lastRead as $res) {
                        $date_from = $res->scope_from;
                        $date_to = $res->scope_to;
                        // $old_gt_amnt = $res->old_total;
                        // $grand_total = $res->grand_total;
                        $read_date = $res->read_date;
                        
                        $curr_x_read_shift = $this->cashier_model->get_x_read_shift($res->$useID);    
                            
                        if(count($curr_x_read_shift) > 0){
                            foreach ($curr_x_read_shift as $cxrs) {
                               $curr_shift_id = $cxrs->shift_id;
                            }
                        }
                        break;
                    }           
                }
                else{
                    $print_str .= "\r\n".align_center('NO SALES FOUND.',38," ")."\r\n\r\n";
                    $print_str .= "================="."\r\n";
                    $print_str .= append_chars(substrwords('NEW GT',18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(number_format(0,2),"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords('OLD GT',18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(number_format(0,2),"left",13," ")."\r\n";                             
                    $print_str .= "================="."\r\n"; 
                    if (!$asJson) {
                        // echo "<pre style='background-color:#fff'>$print_str</pre>";
                        echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
                    }
                    return false; 
                }
                if(!empty($cashier)){
                    $server = $this->manager_model->get_server_details($cashier);
                    $cashier_name = $server[0]->fname.' '.$server[0]->lname;
                    $args['trans_sales.user_id'] = $cashier;
                }else{
                    $cashier_name = 'All Cashier';
                }
            }
            else{

                $read_date = $this->input->post('read_date');

                $cashier = $this->input->post('cashier');
                $server = $this->manager_model->get_server_details($cashier);
                $cashier_name = $server[0]->fname.' '.$server[0]->lname;
                $args['trans_sales.user_id'] = $cashier;

                if($autoShiftPrint){
                    $lastRead = $this->cashier_model->get_last_z_read_on_date(X_READ,date2Sql($read_date),$cashier);
                    if(count($lastRead) > 0){
                        if(count($lastRead) > 0){
                            foreach ($lastRead as $res) {
                                $curr_x_read_shift = $this->cashier_model->get_x_read_shift($res->id);    
                                if(count($curr_x_read_shift) > 0){
                                    foreach ($curr_x_read_shift as $cxrs) {
                                       $curr_shift_id = $cxrs->shift_id;
                                    }
                                }
                                break;
                            }           
                        }
                    }
                }
                else{
                    $get_curr_shift = $this->clock_model->get_shift_id(date2Sql($read_date),$cashier);
                    if(count($get_curr_shift) > 0){
                        $curr_shift_id = $get_curr_shift[0]->shift_id;
                    }
                }

                $date_from = $this->input->post('date_from');
                $date_to = $this->input->post('date_to');
            }
            $print_str .= align_center($title_name,38," ")."\r\n\r\n";
            // $print_str .= append_chars(substrwords('Terminal',18,""),"right",17," ")
            //              .append_chars($terminal_name,"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Employee',18,""),"right",17," ")
                         .append_chars($cashier_name,"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Read Date',18,""),"right",17," ")
                         .append_chars(sql2Date($read_date),"left",8," ")."\r\n";             
            $print_str .= append_chars(substrwords('Date From',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_from),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Date To',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_to),"left",8," ")."\r\n";             
            $print_str .= append_chars(substrwords('Printed on',18,""),"right",17," ")
                         .append_chars(date2SqlDateTime($time),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Printed by',18,""),"right",17," ")
                         .append_chars($userdata['full_name'],"left",8," ")."\r\n";
            $print_str .= "======================================"."\r\n";

            if($curr_shift_id != "")
                $args["trans_sales.shift_id"] = $curr_shift_id;
            else
                $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);

            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            // echo $this->cashier_model->db->last_query();
            if(count($trans_sales) == 0){
                $print_str .= "\r\n".align_center('NO SALES FOUND.',38," ")."\r\n\r\n";
                if (!$asJson) {
                    // echo "<pre style='background-color:#fff'>$print_str</pre>";
                    echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
                }
                return false; 
            }

            $orders = array();
            $orders['cancel'] = array(); 
            $orders['sale'] = array();
            $orders['void'] = array();
            $gross = 0;
            $gross_ids = array();
            $paid = 0;
            $paid_ctr = 0;
            $types = array();
            foreach ($trans_sales as $sale) {
                if($sale->type_id == 10){
                    if($sale->trans_ref != "" && $sale->inactive == 0){
                        $orders['sale'][$sale->sales_id] = $sale;
                        $gross += $sale->total_amount;
                        $gross_ids[] = $sale->sales_id;
                        if($sale->total_paid > 0){
                            $paid += $sale->total_paid;
                            $paid_ctr ++;                   
                        }
                        $types[$sale->type][$sale->sales_id] = $sale;
                    }
                    else if($sale->trans_ref == "" && $sale->inactive == 1){
                        $orders['cancel'][$sale->sales_id] = $sale;
                    }
                    else if($sale->trans_ref != "" && $sale->inactive == 1){
                        $orders['deleted'][$sale->sales_id] = $sale;
                    }
                }
                else{
                    $orders['void'][$sale->sales_id] = $sale;
                    // $gross += $sale->total_amount;
                    // $gross_ids[] = $sale->sales_id;
                }
            }
            $print_str .= "\r\n";
            #DISOUNTS 
                $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$gross_ids));
                $total_disc = 0;
                $disc_codes = array();
                foreach ($sales_discs as $discs) {
                    if(!isset($disc_codes[$discs->disc_code])){
                        $disc_codes[$discs->disc_code] = array('qty'=> 1,'amount'=>$discs->amount);
                    }
                    else{
                        $disc_codes[$discs->disc_code]['qty'] += 1;
                        $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                    }
                    $total_disc += $discs->amount;
                }
            #GROSS    
                $total_regular_discs = 0;
                $reg_disc_ctr = 0;
                foreach ($disc_codes as $code => $dc) {
                    if($code != "SNDISC" && $code != "PWDISC"){
                        $total_regular_discs += $dc['amount'];
                        $reg_disc_ctr += $dc['qty'];                                    
                    }
                }
                $total_regular_discs = numInt($total_regular_discs);
                //SENIOR
                $total_senior_discs = 0;
                $seni_disc_ctr = 0;
                foreach ($disc_codes as $code => $dc) {
                    if($code == "SNDISC"){
                        $total_senior_discs += $dc['amount'];
                        $seni_disc_ctr += $dc['qty'];                                    
                    }
                }
                $total_senior_discs = numInt($total_senior_discs);
                //PWD
                $total_pwd_discs = 0;
                $pwd_disc_ctr = 0;
                foreach ($disc_codes as $code => $dc) {
                    if($code == "PWDISC"){
                        $total_pwd_discs += $dc['amount'];
                        $pwd_disc_ctr += $dc['qty'];                                    
                    }
                }
                $total_pwd_discs = numInt($total_pwd_discs);
                //LOCAL TAX
                $localTax=0;
                $net = $gross;
                $gross += ($total_regular_discs+$total_senior_discs+$total_pwd_discs+$localTax);
                $gross = numInt($gross);
                $void_total = 0;
                foreach ($orders['void'] as $v) {
                    $void_total += $v->total_amount;
                }            
                $cargs["trans_sales_charges.sales_id"] = $gross_ids;
                $cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);   
                $total_service_charges = 0;
                $total_delivery_charges = 0;
                $other_charges = 0;
                $total_charges = 0;
                foreach ($cesults as $ces) {
                    if($ces->charge_id == 1){
                        $total_service_charges += $ces->amount;
                    }
                    if($ces->charge_id == 2){
                        $total_delivery_charges += $ces->amount;
                    }
                    else{
                        $other_charges += $ces->amount;
                    }
                    $total_charges += $ces->amount;
                } 
                $targs["trans_sales_local_tax.sales_id"] = $gross_ids;
                $tesults = $this->site_model->get_tbl('trans_sales_local_tax',$targs);   
                $total_local_tax = 0;
                foreach ($tesults as $tes) {
                    $total_local_tax += $tes->amount;
                } 
                $print_str .= append_chars(substrwords('Gross Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($gross,"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('Regular Discounts: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($total_regular_discs,"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('Senior Discounts: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($total_senior_discs,"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('PWD Discounts: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($total_pwd_discs,"left",13," ")."\r\n"; 
                if($total_charges > 0 ){
                    $print_str .= append_chars(substrwords('Charges: ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars(num($total_charges),"left",13," ")."\r\n";                     
                }
                if($total_local_tax > 0 ){
                    $print_str .= append_chars(substrwords('Local Tax: ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars(num($total_local_tax),"left",13," ")."\r\n";                     
                }
                
                $print_str .= append_chars(substrwords('Net Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(num($net),"left",13," ")."\r\n";                                                        
                $print_str .= append_chars(substrwords('Void Sales: ',18,""),"right",18," ").align_center(count($orders['void']),5," ")
                             .append_chars("(".number_format($void_total,2).")","left",13," ")."\r\n";
            $print_str .= "\r\n";
            #TAX
                $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$gross_ids,"trans_sales_zero_rated.amount >"=>0));
                // echo $this->cashier_model->db->last_query();
                // echo var_dump($zero_rated);
                $zrctr = 0;
                $zrtotal = 0;
                foreach ($zero_rated as $zt) {
                    $zrtotal += $zt->amount;
                    $zrctr++;
                }
                $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$gross_ids,"trans_sales_no_tax.amount >"=>0));
                $ntctr = 0;
                $nttotal = 0;
                foreach ($no_tax as $nt) {
                    $nttotal += $nt->amount;
                    $ntctr++;
                }
                $tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$gross_ids,"trans_sales_tax.amount >"=>0));
                $tctr = 0;
                $ttotal = 0;
                foreach ($tax as $t) {
                    $ttotal += $t->amount;
                    $tctr++;
                }
                // $vat = ((($gross -$total_senior_discs-$total_pwd_discs-$nttotal)/1.12)*0.12);
                $total_sales = $net;
                // $total_sales += $total_regular_discs+$total_senior_discs+$total_pwd_discs;
                $total_sales += $total_senior_discs;
                // $vat = ( ( ($total_sales - $total_disc) - ($total_charges)) - ($nttotal)) - $ttotal;
                $vat = ( ( ($total_sales - $total_local_tax) - ($total_charges)) - ($nttotal)) - $ttotal;

                // $vat = $net-$nttotal-$ttotal;
                $nttotal = $nttotal-$zrtotal;
                $print_str .= append_chars(substrwords('VAT Exempt Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($nttotal),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($vat),"left",13," ")."\r\n";               
                // $print_str .= append_chars(substrwords('VAT Sales: ',18,""),"right",18," ").align_center('',5," ")
                //              .append_chars(numInt($vat),"left",13," ")."\r\n";               
                $print_str .= append_chars(substrwords('VAT: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($ttotal),"left",13," ")."\r\n";  
                $print_str .= append_chars(substrwords('Zero Rated: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($zrtotal),"left",13," ")."\r\n";               
            $print_str .= "\r\n";
            #PAYMENT DETAILS
                // $payments = $this->cashier_model->get_trans_sales_payments_group(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                //     $pays = array();
                //     foreach ($payments as $py) {
                //         if(!isset($pays[$py->payment_type])){
                //             $pays[$py->payment_type] = array('qty'=>$py->count,'amount'=>$py->total_paid);
                //         }
                //         else{
                //             $pays[$py->payment_type]['qty'] += $py->count;
                //             $pays[$py->payment_type]['amount'] += $py->total_paid;
                //         }
                //     }
                //     $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                //                  .append_chars(null,"left",13," ")."\r\n"; 
                //     $pay_total = 0;
                //     $pay_qty = 0;
                //     foreach ($pays as $type => $pay) {
                //         $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                //                      .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                //         $pay_total += $pay['amount'];
                //         $pay_qty += $pay['qty'];
                //     }
                //     $print_str .= "-----------------"."\r\n";
                //     $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                //                  .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";
                $payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                $pays = array();
                foreach ($payments as $py) {
                    if($py->amount > $py->to_pay)
                        $amount = $py->to_pay;
                    else
                        $amount = $py->amount;
                    if(!isset($pays[$py->payment_type])){
                        $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                    }
                    else{
                        $pays[$py->payment_type]['qty'] += 1;
                        $pays[$py->payment_type]['amount'] += $amount;
                    }
                }
                $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                         .append_chars(null,"left",13," ")."\r\n"; 
                $pay_total = 0;
                $pay_qty = 0;
                foreach ($pays as $type => $pay) {
                    $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                                 .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                    $pay_total += $pay['amount'];
                    $pay_qty += $pay['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                             .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";

            $print_str .= "\r\n";
            #INVOICE NUMS
                $ordsnums = array();
                // $ref_ctr = 0;
                foreach ($orders as $typ => $ord) {
                    if($typ != 'void' && $typ != 'cancel'){
                        foreach ($ord as $sales_id => $sale) {
                            // $ordsnums[$sales_id] = $sale;
                            $ordsnums[$sale->trans_ref] = $sale;
                            // $ref_ctr++;
                        }                    
                    }
                }
                ksort($ordsnums);
                // echo var_dump($ordsnums);
                $first = array_shift(array_slice($ordsnums, 0, 1));
                $last = end($ordsnums);
                $ref_ctr = count($ordsnums);
                $print_str .= append_chars(substrwords('Invoice Start: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($first->trans_ref,"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Invoice End: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($last->trans_ref,"left",13," ")."\r\n";    
                $print_str .= append_chars(substrwords('Invoice Ctr: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($ref_ctr,"left",13," ")."\r\n";     
            $print_str .= "\r\n";
            if (!$asJson) {
                // echo "<pre style='background-color:#fff'>$print_str</pre>";
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
            }  
        }
        public function daily_sales_rep($asJson=false){
            $print_str = $this->rep_header();
            $userdata = $this->session->userdata('user');
            $date = $this->input->post('daterange');
            // $date = '11/23/2014 to 11/26/2014';
            $dates = explode(" to ",$date);
            $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
            $terminal = TERMINAL_ID;
            if(!empty($terminal)){
                $terminal_name = $terminal;
                $args['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }
            $time = $this->site_model->get_db_now();
            $title_name = "Daily Sales Report";
            $cashier = $this->input->post('cashier');
            if(!empty($cashier)){
                $server = $this->manager_model->get_server_details($cashier);
                $cashier_name = $server[0]->fname.' '.$server[0]->lname;
                $args['trans_sales.user_id'] = $cashier;
            }else{
                $cashier_name = 'All Cashier';
            }
            $print_str .= align_center($title_name,38," ")."\r\n\r\n";
            // $print_str .= append_chars(substrwords('Terminal',18,""),"right",17," ")
            //              .append_chars($terminal_name,"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Employee',18,""),"right",17," ")
                         .append_chars($cashier_name,"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Date From',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_from),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Date To',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_to),"left",8," ")."\r\n";             
            $print_str .= append_chars(substrwords('Printed on',18,""),"right",17," ")
                         .append_chars(date2SqlDateTime($time),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Printed by',18,""),"right",17," ")
                         .append_chars($userdata['full_name'],"left",8," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $args["DATE(trans_sales.datetime)  BETWEEN date('".$date_from."') AND date('".$date_to."')"] = array('use'=>'where','val'=>null,'third'=>false);
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            // echo $this->cashier_model->db->last_query();
            if(count($trans_sales) == 0){
                $print_str .= "\r\n".align_center('NO SALES FOUND.',38," ")."\r\n\r\n";
                if (!$asJson) {
                    // echo "<pre style='background-color:#fff'>$print_str</pre>";
                    echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
                }
                return false; 
            }
            $orders = array();
            $orders['cancel'] = array(); 
            $orders['sale'] = array();
            $orders['void'] = array();
            $gross = 0;
            $gross_ids = array();
            $paid = 0;
            $paid_ctr = 0;
            $types = array();
            foreach ($trans_sales as $sale) {
                if($sale->type_id == 10){
                    if($sale->trans_ref != "" && $sale->inactive == 0){
                        $orders['sale'][$sale->sales_id] = $sale;
                        $gross += $sale->total_amount;
                        $gross_ids[] = $sale->sales_id;
                        if($sale->total_paid > 0){
                            $paid += $sale->total_paid;
                            $paid_ctr ++;                   
                        }
                        $types[$sale->type][$sale->sales_id] = $sale;
                    }
                    else if($sale->trans_ref == "" && $sale->inactive == 1){
                        $orders['cancel'][$sale->sales_id] = $sale;
                    }
                }
                else{
                    $orders['void'][$sale->sales_id] = $sale;
                    // $gross += $sale->total_amount;
                    // $gross_ids[] = $sale->sales_id;
                }
            }
            $print_str .= "\r\n";
            $totals = array(
                'Total Sales' => 0,
                'Total VATable' => 0,
                'Total VAT-Exempt' => 0,
                'Total Zero Rated' => 0,
                'Total VAT' => 0,
                'Total Regular Disc' => 0,
                'Total Senior Citizen Disc' => 0,
                'Total PWD Disc' => 0,
                'Total Net Sales'=> 0
            );
            foreach ($orders['sale'] as $sale_id => $sale) {
                
                #NO TAX
                    $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$sale_id,"trans_sales_zero_rated.amount >"=>0));
                    $zrctr = 0;
                    $zrtotal = 0;
                    foreach ($zero_rated as $zt) {
                        $zrtotal += $zt->amount;
                        $zrctr++;
                    }
                    $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$sale_id,"trans_sales_no_tax.amount >"=>0));
                    $ntctr = 0;
                    $nttotal = 0;
                    foreach ($no_tax as $nt) {
                        $nttotal += $nt->amount;
                        $ntctr++;
                    }
                    $tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$sale_id,"trans_sales_tax.amount >"=>0));
                    $tctr = 0;
                    $ttotal = 0;
                    foreach ($tax as $t) {
                        $ttotal += $t->amount;
                        $tctr++;
                    }
                #DISCOUNTS
                    $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$sale_id));
                    $total_disc = 0;
                    $disc_codes = array();
                    foreach ($sales_discs as $discs) {
                        if(!isset($disc_codes[$discs->disc_code])){
                            $disc_codes[$discs->disc_code] = array('qty'=> 1,'amount'=>$discs->amount);
                        }
                        else{
                            $disc_codes[$discs->disc_code]['qty'] += 1;
                            $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                        }
                        $total_disc += $discs->amount;
                    }
                    $total_regular_discs = 0;
                    $reg_disc_ctr = 0;
                    foreach ($disc_codes as $code => $dc) {
                        if($code != "SNDISC" && $code != "PWDISC"){
                            $total_regular_discs += $dc['amount'];
                            $reg_disc_ctr += $dc['qty'];                                    
                        }
                    }
                    $total_regular_discs = numInt($total_regular_discs);
                    //SENIOR
                    $total_senior_discs = 0;
                    $seni_disc_ctr = 0;
                    foreach ($disc_codes as $code => $dc) {
                        if($code == "SNDISC"){
                            $total_senior_discs += $dc['amount'];
                            $seni_disc_ctr += $dc['qty'];                                    
                        }
                    }
                    $total_senior_discs = numInt($total_senior_discs);
                    //PWD
                    $total_pwd_discs = 0;
                    $pwd_disc_ctr = 0;
                    foreach ($disc_codes as $code => $dc) {
                        if($code == "PWDISC"){
                            $total_pwd_discs += $dc['amount'];
                            $pwd_disc_ctr += $dc['qty'];                                    
                        }
                    }
                    $total_pwd_discs = numInt($total_pwd_discs);
                $vat_sales = 0;
                $vat_sales = (($sale->total_amount - $ttotal) - $nttotal) - $zrtotal;
                if($vat_sales < 0){
                    $vat_sales = 0;
                }
                $localTax = 0;
                $gross = $sale->total_amount;
                $gross += ($total_regular_discs+$total_senior_discs+$total_pwd_discs+$localTax);
                $print_str .= align_center($sale->trans_ref,38," ")."\r\n";
                $print_str .= align_center($sale->datetime,38," ")."\r\n";
                $print_str .= append_chars(substrwords('Total Sales',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($gross),"left",13," ")."\r\n";  
                $print_str .= append_chars(substrwords('VATable Sales',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($vat_sales),"left",13," ")."\r\n";  
                $print_str .= append_chars(substrwords('VAT-Exempt Sales',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($nttotal),"left",13," ")."\r\n";  
                $print_str .= append_chars(substrwords('Zero Rated Sales',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($zrtotal),"left",13," ")."\r\n";  
                $print_str .= append_chars(substrwords('VAT',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($ttotal),"left",13," ")."\r\n";  
                $print_str .= append_chars(substrwords('Discounts',18,""),"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('Regular',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($total_regular_discs,"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('Senior Citizen',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($total_senior_discs,"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('PWD',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($total_pwd_discs,"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('Net Sales',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($sale->total_amount),"left",13," ")."\r\n";              

                $totals['Total Sales'] += $gross;
                $totals['Total VATable'] += $vat_sales;
                $totals['Total VAT-Exempt'] += $nttotal;
                $totals['Total Zero Rated'] += $zrtotal;
                $totals['Total VAT'] += $ttotal;
                $totals['Total Regular Disc'] += $total_regular_discs;
                $totals['Total Senior Citizen Disc'] += $total_senior_discs;
                $totals['Total PWD Disc'] += $total_pwd_discs;
                $totals['Total Net Sales'] += $sale->total_amount;
                $print_str .= "\r\n";             
            }
            $print_str .= "======================================"."\r\n";
            foreach ($totals as $txt => $val) {
               $print_str .= append_chars(substrwords($txt,18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($val),"left",13," ")."\r\n";
            }
            if (!$asJson) {
                // echo "<pre style='background-color:#fff'>$print_str</pre>";
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
            }  
        }
        public function monthly_sales_rep($asJson=false){
            $print_str = $this->rep_header();
            $userdata = $this->session->userdata('user');
            $month = $this->input->post('month');
            // $month = 01;
            $time = $this->site_model->get_db_now();
            $year = date('Y',strtotime($time));
            $date_from = $year."-".$month."-01";
            $date_to = date('Y-m-t',strtotime($date_from));
            $title_name = "Monthly Sales Report";
            
            $print_str .= align_center($title_name,38," ")."\r\n\r\n";
            $print_str .= append_chars(substrwords('Date From',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_from),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Date To',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_to),"left",8," ")."\r\n";             
            $print_str .= append_chars(substrwords('Printed on',18,""),"right",17," ")
                         .append_chars(date2SqlDateTime($time),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Printed by',18,""),"right",17," ")
                         .append_chars($userdata['full_name'],"left",8," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $terminal = TERMINAL_ID;
            if(!empty($terminal)){
                $terminal_name = $terminal;
                $args['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }
            $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            if(count($trans_sales) == 0){
                $print_str .= "\r\n".align_center('NO SALES FOUND.',38," ")."\r\n\r\n";
                if (!$asJson) {
                    // echo "<pre style='background-color:#fff'>$print_str</pre>";
                    echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
                }
                return false; 
            }
            $print_str .= "\r\n";
            $b4last = date('t',strtotime($year."-".($month-1)."01"));
            $beforeDate = $year."-".($month-1)."-".$b4last;
            $lastRead = $this->cashier_model->get_last_z_read_on_date(Z_READ,date2Sql($beforeDate));
            $old_gt_amnt = 0;
            $grand_total = 0;
            if(count($lastRead) > 0){
                foreach ($lastRead as $res) {
                    $old_gt_amnt = $res->old_total;
                    $grand_total = $res->grand_total;
                    break;
                }           
            }
            $range = createDateRangeArray($date_from,$date_to);
            $orders = array();
            foreach ($range as $dt) {
                foreach ($trans_sales as $sale) {
                    if(date2Sql($sale->datetime) == $dt){
                        if($sale->type_id == 10 && $sale->trans_ref != "" && $sale->inactive == 0){
                            $orders[$dt][$sale->sales_id] = $sale;
                        }
                    }

                }
            }  
            $totals = array(
                'Total VATable' => 0,
                'Total VAT-Exempt' => 0,
                'Total Zero Rated' => 0,
                'Total VAT' => 0,
                'Total Regular Disc' => 0,
                'Total Senior Citizen Disc' => 0,
                'Total PWD Disc' => 0,
                'Total Net Sales' => 0,
            );     
            foreach ($range as $dt) {
                if(isset($orders[$dt]) && count($orders[$dt]) > 0){
                    $ords = $orders[$dt];
                        $print_str .= align_center($dt,38," ")."\r\n";
                        ksort($ords);
                        $first = array_shift(array_slice($ords, 0, 1));
                        $last = end($ords);
                        $ref_ctr = count($ords);
                        $print_str .= align_center($first->trans_ref." - ".$last->trans_ref."(".$ref_ctr.")",38," ")."\r\n";
                        $gross_ids = array();
                        $gross = 0;
                        foreach ($ords as $sales_id => $sl) {
                            $gross_ids[] = $sl->sales_id;
                            $gross += $sl->total_amount;
                        }
                        #DISOUNTS 
                            $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$gross_ids));
                            $total_disc = 0;
                            $disc_codes = array();
                            foreach ($sales_discs as $discs) {
                                if(!isset($disc_codes[$discs->disc_code])){
                                    $disc_codes[$discs->disc_code] = array('qty'=> 1,'amount'=>$discs->amount);
                                }
                                else{
                                    $disc_codes[$discs->disc_code]['qty'] += 1;
                                    $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                                }
                                $total_disc += $discs->amount;
                            }
                        #GROSS    
                            $total_regular_discs = 0;
                            $reg_disc_ctr = 0;
                            foreach ($disc_codes as $code => $dc) {
                                if($code != "SNDISC" && $code != "PWDISC"){
                                    $total_regular_discs += $dc['amount'];
                                    $reg_disc_ctr += $dc['qty'];                                    
                                }
                            }
                            $total_regular_discs = numInt($total_regular_discs);
                            //SENIOR
                            $total_senior_discs = 0;
                            $seni_disc_ctr = 0;
                            foreach ($disc_codes as $code => $dc) {
                                if($code == "SNDISC"){
                                    $total_senior_discs += $dc['amount'];
                                    $seni_disc_ctr += $dc['qty'];                                    
                                }
                            }
                            $total_senior_discs = numInt($total_senior_discs);
                            //PWD
                            $total_pwd_discs = 0;
                            $pwd_disc_ctr = 0;
                            foreach ($disc_codes as $code => $dc) {
                                if($code == "PWDISC"){
                                    $total_pwd_discs += $dc['amount'];
                                    $pwd_disc_ctr += $dc['qty'];                                    
                                }
                            }
                            $total_pwd_discs = numInt($total_pwd_discs);
                            //LOCAL TAX
                            $localTax=0;
                            $net = $gross;
                            $gross += ($total_regular_discs+$total_senior_discs+$total_pwd_discs+$localTax);
                            $gross = numInt($gross);
                            
                            $print_str .= append_chars(substrwords('Gross Sales: ',18,""),"right",18," ").align_center('',5," ")
                                         .append_chars($gross,"left",13," ")."\r\n"; 
                            $print_str .= append_chars(substrwords('Regular Discounts: ',18,""),"right",18," ").align_center('',5," ")
                                         .append_chars($total_regular_discs,"left",13," ")."\r\n"; 
                            $print_str .= append_chars(substrwords('Senior Discounts: ',18,""),"right",18," ").align_center('',5," ")
                                         .append_chars($total_senior_discs,"left",13," ")."\r\n"; 
                            $print_str .= append_chars(substrwords('PWD Discounts: ',18,""),"right",18," ").align_center('',5," ")
                                         .append_chars($total_pwd_discs,"left",13," ")."\r\n"; 
                            $print_str .= append_chars(substrwords('Net Sales: ',18,""),"right",18," ").align_center('',5," ")
                                         .append_chars($net,"left",13," ")."\r\n";                                                        
                        $print_str .= "\r\n";
                        #TAX
                            $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$gross_ids,"trans_sales_zero_rated.amount >"=>0));
                            // echo $this->cashier_model->db->last_query();
                            // echo var_dump($zero_rated);
                            $zrctr = 0;
                            $zrtotal = 0;
                            foreach ($zero_rated as $zt) {
                                $zrtotal += $zt->amount;
                                $zrctr++;
                            }
                            $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$gross_ids,"trans_sales_no_tax.amount >"=>0));
                            $ntctr = 0;
                            $nttotal = 0;
                            foreach ($no_tax as $nt) {
                                $nttotal += $nt->amount;
                                $ntctr++;
                            }
                            $tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$gross_ids,"trans_sales_tax.amount >"=>0));
                            $tctr = 0;
                            $ttotal = 0;
                            foreach ($tax as $t) {
                                $ttotal += $t->amount;
                                $tctr++;
                            }
                            // $vat = ((($gross -$total_senior_discs-$total_pwd_discs-$nttotal)/1.12)*0.12);
                            $vat = $net-$nttotal-$ttotal;
                            $nttotal = $nttotal-$zrtotal;
                            $print_str .= append_chars(substrwords('VAT Exempt Sales: ',18,""),"right",18," ").align_center('',5," ")
                                         .append_chars(numInt($nttotal),"left",13," ")."\r\n";
                            $print_str .= append_chars(substrwords('VAT Sales: ',18,""),"right",18," ").align_center('',5," ")
                                         .append_chars(numInt($vat),"left",13," ")."\r\n";               
                            $print_str .= append_chars(substrwords('VAT: ',18,""),"right",18," ").align_center('',5," ")
                                         .append_chars(numInt($ttotal),"left",13," ")."\r\n";  
                            $print_str .= append_chars(substrwords('Zero Rated: ',18,""),"right",18," ").align_center('',5," ")
                                         .append_chars(numInt($zrtotal),"left",13," ")."\r\n";               
                        $print_str .= "\r\n";
                        #PAYMENT DETAILS
                        // $payments = $this->cashier_model->get_trans_sales_payments_group(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                        //     $pays = array();
                        //     foreach ($payments as $py) {
                        //         if(!isset($pays[$py->payment_type])){
                        //             $pays[$py->payment_type] = array('qty'=>$py->count,'amount'=>$py->total_paid);
                        //         }
                        //         else{
                        //             $pays[$py->payment_type]['qty'] += $py->count;
                        //             $pays[$py->payment_type]['amount'] += $py->total_paid;
                        //         }
                        //     }
                        //     $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                        //                  .append_chars(null,"left",13," ")."\r\n"; 
                        //     $pay_total = 0;
                        //     $pay_qty = 0;
                        //     foreach ($pays as $type => $pay) {
                        //         $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                        //                      .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                        //         $pay_total += $pay['amount'];
                        //         $pay_qty += $pay['qty'];
                        //     }
                        //     $print_str .= "-----------------"."\r\n";
                        //     $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                        //                  .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";    
                        // $print_str .= "\r\n";
                        $payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                        $pays = array();
                        foreach ($payments as $py) {
                            if($py->amount > $py->to_pay)
                                $amount = $py->to_pay;
                            else
                                $amount = $py->amount;
                            if(!isset($pays[$py->payment_type])){
                                $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                            }
                            else{
                                $pays[$py->payment_type]['qty'] += 1;
                                $pays[$py->payment_type]['amount'] += $amount;
                            }
                        }
                        $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(null,"left",13," ")."\r\n"; 
                        $pay_total = 0;
                        $pay_qty = 0;
                        foreach ($pays as $type => $pay) {
                            $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                                         .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                            $pay_total += $pay['amount'];
                            $pay_qty += $pay['qty'];
                        }
                        $print_str .= "-----------------"."\r\n";
                        $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                                     .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";

                        #READINGS
                            $print_str .= "======================================"."\r\n";
                            $print_str .= append_chars(substrwords('OLD GT',18,""),"right",18," ").align_center(null,5," ")
                                         .append_chars(numInt($grand_total),"left",13," ")."\r\n";  
                            $grand_total += $net;                                        
                            $print_str .= append_chars(substrwords('NEW GT',18,""),"right",18," ").align_center(null,5," ")
                                         .append_chars(numInt($grand_total),"left",13," ")."\r\n";
                            $print_str .= "======================================"."\r\n";   
                        $vat_sales = (($sale->total_amount - $ttotal) - $nttotal) - $zrtotal;
                        if($vat_sales < 0){
                            $vat_sales = 0;
                        }

                        $print_str .= "--------------------------------------"."\r\n";
                        $print_str .= "\r\n";
                        $totals['Total VATable'] += $vat;
                        // echo $totals['Total VATable']."--";
                        $totals['Total VAT-Exempt'] += $nttotal;
                        $totals['Total Zero Rated'] += $zrtotal;
                        $totals['Total VAT'] += $ttotal;
                        $totals['Total Regular Disc'] += $total_regular_discs;
                        $totals['Total Senior Citizen Disc'] += $total_senior_discs;
                        $totals['Total PWD Disc'] += $total_pwd_discs;
                        $totals['Total Net Sales'] += $net;
                    
                }
                else{
                  $print_str .= align_center($dt,38," ")."\r\n";
                  $print_str .= align_center('NO SALES',38," ")."\r\n";
                  $net = 0;
                  #READINGS
                        $print_str .= "======================================"."\r\n";
                        $print_str .= append_chars(substrwords('OLD GT',18,""),"right",18," ").align_center(null,5," ")
                                     .append_chars(numInt($grand_total),"left",13," ")."\r\n";  
                        $grand_total += $net;                                        
                        $print_str .= append_chars(substrwords('NEW GT',18,""),"right",18," ").align_center(null,5," ")
                                     .append_chars(numInt($grand_total),"left",13," ")."\r\n";
                        $print_str .= "======================================"."\r\n";   

                    $print_str .= "--------------------------------------"."\r\n";
                    $print_str .= "\r\n"; 
                }
            }
            foreach ($totals as $txt => $val) {
               $print_str .= append_chars(substrwords($txt,18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($val),"left",13," ")."\r\n";
            }   

            if (!$asJson) {
                // echo "<pre style='background-color:#fff'>$print_str</pre>";
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
            }  
        }
        public function food_server_sales_rep($asJson=false){
        	$print_str = $this->rep_header();
        	$userdata = $this->session->userdata('user');
        	$daterange = $this->input->post('daterange');
            $server_id = $this->input->post('server_id');
            $void_included = $this->input->post('voided');
            $dates = explode(" to ",$daterange);
            $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
            $fs_sums = $this->manager_model->get_fs_daily_sums($date_from,$date_to,$server_id,($void_included == 'no' ? false : true));
            $print_str .=
                align_center("FOOD SERVER REPORT",38)."\r\n";
            if (!empty($server_id) && !empty($fs_sums)) {
                $print_str .= append_chars("Employee",'right',18," ").$fs_sums[0]->waiter_id." - ".$fs_sums[0]->fname." ".$fs_sums[0]->lname."\r\n";
            } else {
                $print_str .= append_chars("Employee",'right',18," ")."ALL\r\n";
            }
            $print_str .= append_chars("Inactive Included",'right',18," ").($void_included == 'no' ? 'NO' : 'YES')."\r\n"
                .append_chars("Starting Date",'right',18," ").$date_from."\r\n"
                .append_chars("Ending Date",'right',18," ").$date_to."\r\n"
                .append_chars("Printed On",'right',18," ").date('Y-m-d H:i:s')."\r\n"
                .append_chars("Printed By",'right',18," ").$userdata['fname']." ".$userdata['lname']."\r\n"
                .append_chars("",'right',38,"-")."\r\n\r\n";

            $total_orders = $sum_orders = 0;
            foreach ($fs_sums as $value) {
                $print_str .=
                    append_chars($value->fname." ".$value->lname,'right',20," ")
                    .append_chars($value->total_orders,'right',8," ")
                    .append_chars(number_format($value->total_amount,2),'left',10," ")."\r\n";
                $total_orders += $value->total_orders;
                $sum_orders += $value->total_amount;
            }
            $print_str .=
                "\r\n\r\n"
                .append_chars("TOTAL SALES COUNT","right",20," ")."  ".number_format($total_orders)."\r\n"
                .append_chars("TOTAL SALES AMOUNT","right",20," ")."P ".number_format($sum_orders,2);
    		if (!$asJson) {
    			echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
    		}  
        }
        public function menu_sales_rep2($asJson=false){
        	$print_str = $this->rep_header();
        	$userdata = $this->session->userdata('user');
        	$time = $this->site_model->get_db_now();
            $daterange = $this->input->post('daterange');
            $dates = explode(" to ",$daterange);
            $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
            $args = array();
            $args['type_id'] = 10;
            $args['trans_sales.inactive'] = 0;
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $args["DATE(trans_sales.datetime) BETWEEN DATE('".$date_from."') AND DATE('".$date_to."')"] = array('use'=>'where','val'=>null,'third'=>false);

            if($this->input->post('user_id')){
                $args['trans_sales.user_id'] = $this->input->post('user_id');
            }
            $terminal = TERMINAL_ID;
            if(!empty($terminal)){
                $terminal_name = $terminal;
                $args['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }

            $sales_menus = $this->cashier_model->get_menu_sales(null,$args);
            $user = $this->session->userdata('user');
            $emp = 'ALL';
            if($this->input->post('user_id')){
                $server = $this->manager_model->get_server_details($this->input->post('user_id'));
                $emp = $server[0]->fname." ".$server[0]->mname." ".$server[0]->lname." ".$server[0]->suffix;
            }
            
            
            $title_name = "Menu Sales Report";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= "\r\n";
            $print_str .= append_chars('Terminal','right',14," ").append_chars($terminal_name,'right',19," ")."\r\n";
            $print_str .= append_chars('Employee','right',14," ").append_chars($emp,'right',19," ")."\r\n";
            // $print_str .= append_chars('Transaction Type','right',19," ").append_chars('ALL','right',19," ")."\r\n";
            $print_str .= append_chars('Sales Type','right',14," ").append_chars('ALL','right',19," ")."\r\n";
            $print_str .= append_chars('Date Range','right',14," ").append_chars($date_from.' - '.$date_to,'right',19," ")."\r\n";
            $print_str .= append_chars('Printed On','right',14," ").append_chars(date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',14," ").append_chars($user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            
            if(count($sales_menus) > 0){
                $sub_cats = array();
                $sales_menu_cat = $this->menu_model->get_menu_subcategories();
                foreach ($sales_menu_cat as $rc) {
                    $sub_cats[$rc->menu_sub_cat_id] = array('name'=>$rc->menu_sub_cat_name,'qty'=>0,'amount'=>0);
                }
                $oaQty = 0;
                $oaVal = 0;
                $menus = array();
                foreach ($sales_menus as $res) {
                    $oaQty += $res->total_qty;
                    $oaVal += $res->total_amount;
                    if(!isset($menus[$res->menu_id])){
                        $menus[$res->menu_id] = array('name'=>$res->menu_name,'qty'=>$res->total_qty,'amount'=>$res->total_amount);
                    }
                    else{
                        $row = $menus[$res->menu_id];
                        $row['qty'] += $res->total_qty;
                        $row['amount'] += $res->total_amount;
                        $menus[$res->menu_id] = $row;
                    }
                    $sales_discs[$res->sales_id] = $res->sales_id;
                    if(!isset($sub_cats[$res->sub_cat_id])){
                        $sub_cats[$res->sub_cat_id] = array('name'=>$res->sub_cat_name,'qty'=>$res->total_qty,'amount'=>$res->total_amount);
                    }
                    else{
                        $row = $sub_cats[$res->sub_cat_id];
                        $row['qty'] += $res->total_qty;
                        $row['amount'] += $res->total_amount;
                        $sub_cats[$res->sub_cat_id] = $row;
                    }

                }
                foreach ($menus as $menu_id => $res) {
                    $print_str .=
                        append_chars($res['name'],'right',18," ")
                        .append_chars(num($res['qty']),'right',10," ")
                        .append_chars(num( ($res['qty'] / $oaQty) * 100).'%','left',10," ")."\r\n";
                    $print_str .=
                        append_chars(null,'right',18," ")
                        .append_chars(num($res['amount']),'right',10," ")
                        .append_chars(num( ($res['amount'] / $oaVal) * 100).'%','left',10," ")."\r\n";
                }
                $print_str .= "======================================"."\r\n";
                $print_str .= append_chars('Sub Categories',"right",18," ")."\r\n";
                foreach ($sub_cats as $subid => $sc) {
                    $print_str .= append_chars($sc['name'],"right",18," ")
                             .append_chars(num($sc['qty']),'right',10," ")
                             .append_chars(num($sc['amount']),"left",10," ")."\r\n";
                }
                $print_str .= "\r\n";
                $print_str .= "======================================"."\r\n";
                $print_str .= append_chars('TOTAL SALES',"right",18," ")
                             .append_chars(num($oaQty),'right',10," ")
                             .append_chars(num($oaVal),"left",10," ")."\r\n";

                $slsd = array();

                foreach ($sales_discs as $sids => $val) {
                    $slsd[] = $sids;
                }

                $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$slsd));
                $items_disc = 0;
                foreach ($sales_discs as $dsc) {
                    $items_disc += $dsc->amount;
                }

                $print_str .= append_chars('TOTAL ITEMS DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($items_disc),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL OTHER DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL NET SALES',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($oaVal-$items_disc),"left",10," ")."\r\n";
            }
            else{
                $print_str .= align_center('NO Sales',38," ")."\r\n";
                $print_str .= "======================================"."\r\n";
                $print_str .= append_chars('TOTAL SALES',"right",18," ")
                             .append_chars(num(0),'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL ITEMS DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL OTHER DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL NET SALES',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
            }
            if (!$asJson) {
    			echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
    		}
        }
        public function hourly_sales_rep($asJson=false){
        	$print_str = $this->rep_header();
        	$userdata = $this->session->userdata('user');
        	// $date = date('Y-m-d',strtotime($this->input->post('date')));
            $date = $this->input->post('date');
            $dates = explode(" to ",$date);
            $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
            // var_dump($dates);
            // return false;
            $print_str .=
                align_center("HOURLY SALES REPORT",38)."\r\n";
            $print_str .= append_chars("Employee",'right',18," ")."ALL\r\n";

            $print_str .= append_chars("Inactive Included",'right',18," ")."NO\r\n"
                .append_chars("Starting Date",'right',18," ").$date_from."\r\n"
                .append_chars("Ending Date",'right',18," ").$date_to."\r\n"
                .append_chars("Printed On",'right',18," ").date('Y-m-d H:i:s')."\r\n"
                .append_chars("Printed By",'right',18," ").$userdata['fname']." ".$userdata['lname']."\r\n"
                .append_chars("",'right',38,"-")."\r\n\r\n";

            $total_cover = $total_check = $total_sales = 0;
            $counter = 1;
            $use_date = date('Y-m-d',strtotime($date_from));
            $date_to = date('Y-m-d',strtotime($date_to));

            while (true) {
                foreach(unserialize(TIMERANGES) as $k=>$v){
                    $net = $this->settings_model->get_hourly_sales(null,$v['FTIME'],$v['TTIME'],$use_date,$use_date);
                    // echo $this->settings_model->db->last_query()."<br/>";
                    // continue;
                    if (!empty($net)) {
                        $cover = (empty($net->sales_cover) ? 0 : $net->sales_cover);
                        $check = (empty($net->sales_check) ? 0 : $net->sales_check);
                        $total = (empty($net->sales_total) ? 0 : $net->sales_total);

                        $avg_cover = round((empty($cover) ? 0 : $total/$cover),2);
                        $avg_check = round((empty($check) ? 0 : $total/$check),2);

                        $print_str .= ($counter)." $use_date ".date('h:i A',strtotime($v['FTIME']))." - ".date('h:i A',strtotime($v['TTIME']))."\r\n"
                            .append_chars("Net Sales Total",'right',27," ").append_chars(number_format($total,2),'left',11," ")."\r\n"
                            .append_chars("Average $/Cover",'right',15," ").append_chars($cover,'left',11," ")
                                .append_chars(number_format($avg_cover,2),'left',12," ")."\r\n"
                            .append_chars("Average $/Check",'right',15," ").append_chars($check,'left',11," ")
                                .append_chars(number_format($avg_check,2),'left',12," ")."\r\n\r\n\r\n";

                        $total_cover += $cover;
                        $total_check += $check;
                        $total_sales += $total;
                    } else {
                        $print_str .= $counter." ".date('Y-m-d',strtotime($use_date))." ".$v['FTIME']." - ".$v['TTIME']."\r\n"
                            .append_chars("Net Sales Total",'right',27," ").append_chars("0.00",'left',11," ")."\r\n"
                            .append_chars("Average $/Cover",'right',15," ").append_chars("0",'left',11," ")
                                .append_chars("0.00",'left',12," ")."\r\n"
                            .append_chars("Average $/Check",'right',15," ").append_chars("0.00",'left',11," ")
                                .append_chars("0.00",'left',12," ")."\r\n\r\n\r\n";
                    }
                    $counter++;
                }
                if (date('Y-m-d',strtotime($use_date)) == date('Y-m-d',strtotime($date_to))) {break;}
                else {
                    $use_date = date('Y-m-d',strtotime($use_date." +1 day"));
                }
            }

            $t_avg_cover = round((empty($total_cover) ? 0 : $total_sales/$total_cover),2);
            $t_avg_check = round((empty($total_check) ? 0 : $total_sales/$total_cover),2);

            $print_str .= "TOTAL\r\n"
                .append_chars("Net Sales Total",'right',27," ").append_chars(number_format($total_sales,2),'left',11," ")."\r\n"
                .append_chars("Average $/Cover",'right',15," ").append_chars($total_cover,'left',11," ")
                    .append_chars(number_format($t_avg_cover,2),'left',12," ")."\r\n"
                .append_chars("Average $/Check",'right',15," ").append_chars($total_check,'left',11," ")
                    .append_chars(number_format($t_avg_check,2),'left',12," ")."\r\n\r\n";
            if (!$asJson) {
    			echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
    		}        
        }
        public function employee_sales_rep_old($asJson=false){
        	$print_str = $this->rep_header();
        	$userdata = $this->session->userdata('user');
            $daterange = $this->input->post('daterange');
        	//$daterange = '06/09/2015';
            $server_id = $this->input->post('server_id');
            // $server_id = 19;
            // $daterange = '11/28/2014 to 11/28/2014';
            //$void_included = $this->input->post('voided');

            // $dates = explode(" to ",$daterange);
            // $date_from = (empty($dates[0]) ? date('Y-m-d')." 00:00" : date('Y-m-d H:i',strtotime($dates[0])));
            // $date_to = (empty($dates[1]) ? date('Y-m-d H:i') : date('Y-m-d H:i',strtotime($dates[1])));

            // if($dates[0] == $dates[1]){
            //     $date_from = date('Y-m-d',strtotime($dates[0]));
            //     $date_to = date('Y-m-d',strtotime($dates[1]));
            // }
            $date = $daterange;
            $lastRead = $this->cashier_model->get_last_z_read_on_date(X_READ,date2Sql($date),$server_id);
            $date_from = $date;
            $date_to = $date;
            if(count($lastRead) > 0){
                foreach ($lastRead as $res) {
                    $date_from = $res->scope_from;
                    break;
                }
                foreach ($lastRead as $res) {
                    $date_to = $res->scope_to;
                }
            }

            $server = $this->manager_model->get_server_details($server_id);
            $print_str .=
            align_center("EMPLOYEE SALES REPORT",38)."\r\n";
            //if (!empty($server_id) && !empty($fs_sums)) {
                $print_str .= append_chars("Employee",'right',18," ").$server[0]->id."  ".$server[0]->fname." ".$server[0]->lname." - ".$server[0]->id."\r\n";
            // } else {
            //     $print_str .= append_chars("Employee",'right',18," ")."ALL\r\n";
            // }
            $time = $this->site_model->get_db_now();
            $print_str .= append_chars("Starting Datetime",'right',18," ").$date_from."\r\n"
                .append_chars("Ending Datetime",'right',18," ").$date_to."\r\n"
                .append_chars("Printed On",'right',18," ").$time."\r\n"
                .append_chars("Printed By",'right',18," ").$userdata['fname']." ".$userdata['lname']."\r\n"
                .append_chars("",'right',38,"-")."\r\n";

            $print_str .=
            align_center($server[0]->id."  ".$server[0]->fname." ".$server[0]->lname,38)."\r\n";

            // $fs_sums = $this->manager_model->get_emp_daily_sums($date_from,$date_to,$server_id);
            // echo $this->db->last_query();
            #QUERY HERE
            $args['trans_sales.user_id'] = $server_id;
            // $args["DATE(trans_sales.datetime)  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            // echo $this->cashier_model->db->last_query();
            if(!empty($server_id) && !empty($trans_sales)){
                #HERE
                $orders = array();
                $orders['cancel'] = array(); 
                $orders['sale'] = array();
                $orders['void'] = array();
                $gross = 0;
                $gross_ids = array();
                $paid = 0;
                $paid_ctr = 0;
                $types = array();
                foreach ($trans_sales as $sale) {
                    if($sale->type_id == 10){
                        if($sale->trans_ref != "" && $sale->inactive == 0){
                            $orders['sale'][$sale->sales_id] = $sale;
                            $gross += $sale->total_amount;
                            $gross_ids[] = $sale->sales_id;
                            if($sale->total_paid > 0){
                                $paid += $sale->total_paid;
                                $paid_ctr ++;                   
                            }
                            $types[$sale->type][$sale->sales_id] = $sale;
                        }
                        else if($sale->trans_ref == "" && $sale->inactive == 1){
                            $orders['cancel'][$sale->sales_id] = $sale;
                        }
                        else if($sale->trans_ref != "" && $sale->inactive == 1){
                            $orders['deleted'][$sale->sales_id] = $sale;
                        }
                    }
                    else{
                        $orders['void'][$sale->sales_id] = $sale;
                        // $gross += $sale->total_amount;
                        // $gross_ids[] = $sale->sales_id;
                    }
                }
                #DISOUNTS 
                    $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$gross_ids));
                    $total_disc = 0;
                    $disc_codes = array();
                    foreach ($sales_discs as $discs) {
                        if(!isset($disc_codes[$discs->disc_code])){
                            $disc_codes[$discs->disc_code] = array('qty'=> 1,'amount'=>$discs->amount);
                        }
                        else{
                            $disc_codes[$discs->disc_code]['qty'] += 1;
                            $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                        }
                        $total_disc += $discs->amount;
                    }
                #CASHIER SALES
                    $total_regular_discs = 0;
                    $reg_disc_ctr = 0;
                    foreach ($disc_codes as $code => $dc) {
                        if($code != "SNDISC" && $code != "PWDISC"){
                            $total_regular_discs += $dc['amount'];
                            $reg_disc_ctr += $dc['qty'];                                    
                        }
                    }
                    $total_regular_discs = numInt($total_regular_discs);
                    //SENIOR
                    $total_senior_discs = 0;
                    $seni_disc_ctr = 0;
                    foreach ($disc_codes as $code => $dc) {
                        if($code == "SNDISC"){
                            $total_senior_discs += $dc['amount'];
                            $seni_disc_ctr += $dc['qty'];                                    
                        }
                    }
                    $total_senior_discs = numInt($total_senior_discs);
                    //PWD
                    $total_pwd_discs = 0;
                    $pwd_disc_ctr = 0;
                    foreach ($disc_codes as $code => $dc) {
                        if($code == "PWDISC"){
                            $total_pwd_discs += $dc['amount'];
                            $pwd_disc_ctr += $dc['qty'];                                    
                        }
                    }
                    $total_pwd_discs = numInt($total_pwd_discs);
                    //LOCAL TAX
                    $localTax=0;
                    $net = $gross;
                    $gross += ($total_regular_discs+$total_senior_discs+$total_pwd_discs+$localTax);
                    $gross = numInt($gross);
                    $void_total = 0;
                    foreach ($orders['void'] as $v) {
                        $void_total += $v->total_amount;
                    }


                    $cargs["trans_sales_charges.sales_id"] = $gross_ids;
                    $cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);   
                    $total_service_charges = 0;
                    $total_delivery_charges = 0;
                    $other_charges = 0;
                    $total_charges = 0;
                    foreach ($cesults as $ces) {
                        if($ces->charge_id == 1){
                            $total_service_charges += $ces->amount;
                        }
                        if($ces->charge_id == 2){
                            $total_delivery_charges += $ces->amount;
                        }
                        else{
                            $other_charges += $ces->amount;
                        }
                        $total_charges += $ces->amount;
                    } 
                    $targs["trans_sales_local_tax.sales_id"] = $gross_ids;
                    $tesults = $this->site_model->get_tbl('trans_sales_local_tax',$targs);   
                    $total_local_tax = 0;
                    foreach ($tesults as $tes) {
                        $total_local_tax += $tes->amount;
                    }

                    $print_str .= append_chars(substrwords('Gross Sales: ',18,""),"right",18," ").align_center('',5," ")
                               .append_chars($gross,"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords('Net Sales: ',18,""),"right",18," ").align_center('',5," ")
                               .append_chars(num($net),"left",13," ")."\r\n";  
                    $print_str .= append_chars(substrwords('Void Sales: ',18,""),"right",18," ").align_center(count($orders['void']),5," ")
                             .append_chars("(".number_format($void_total,2).")","left",13," ")."\r\n"; 
                    $print_str .= append_chars(substrwords('Local Tax: ',18,""),"right",18," ").align_center('',5," ")
                               .append_chars(num($total_local_tax),"left",13," ")."\r\n";  
                    $print_str .= append_chars(substrwords('Total Charges: ',18,""),"right",18," ").align_center('',5," ")
                               .append_chars(num($total_charges),"left",13," ")."\r\n";  
                       
                #PAYMENTS
                    $print_str .= "\r\n";
                    // $print_str .= "\r\nPayment B/Down";
                    // $print_str .= "\r\nCash Payments";
                    // $print_str .= "\r\n=================";
                    // $payments = $this->cashier_model->get_trans_sales_payments_group(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                    // $pays = array();
                    // foreach ($payments as $py) {
                    //     if(!isset($pays[$py->payment_type])){
                    //         $pays[$py->payment_type] = array('qty'=>$py->count,'amount'=>$py->total_paid);
                    //     }
                    //     else{
                    //         $pays[$py->payment_type]['qty'] += $py->count;
                    //         $pays[$py->payment_type]['amount'] += $py->total_paid;
                    //     }
                    // }
                    // $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                    //              .append_chars(null,"left",13," ")."\r\n"; 
                    // $pay_total = 0;
                    // $pay_qty = 0;
                    // foreach ($pays as $type => $pay) {
                    //     $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                    //                  .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                    //     $pay_total += $pay['amount'];
                    //     $pay_qty += $pay['qty'];
                    // }
                    // $print_str .= "-----------------"."\r\n";
                    // $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center("",5," ")
                    //              .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";

                    //=======================================
                    $payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                    $pays = array();
                    foreach ($payments as $py) {
                        if($py->amount > $py->to_pay)
                            $amount = $py->to_pay;
                        else
                            $amount = $py->amount;
                        if(!isset($pays[$py->payment_type])){
                            $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                        }
                        else{
                            $pays[$py->payment_type]['qty'] += 1;
                            $pays[$py->payment_type]['amount'] += $amount;
                        }
                    }
                    $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(null,"left",13," ")."\r\n"; 
                    $pay_total = 0;
                    $pay_qty = 0;
                    foreach ($pays as $type => $pay) {
                        $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                                     .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                        $pay_total += $pay['amount'];
                        $pay_qty += $pay['qty'];
                    }
                    $print_str .= "-----------------"."\r\n";
                    $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                                 .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";             


                #DISCOUNTS
                    $print_str .= append_chars(substrwords('Discount Details',18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(null,"left",13," ")."\r\n"; 
                    
                    $print_str .= append_chars(substrwords('Regular ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars($total_regular_discs,"left",13," ")."\r\n"; 
                    $print_str .= append_chars(substrwords('Senior ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars($total_senior_discs,"left",13," ")."\r\n"; 
                    $print_str .= append_chars(substrwords('PWD ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars($total_pwd_discs,"left",13," ")."\r\n";     
                    
                    $print_str .= "-----------------"."\r\n";

                    $print_str .= append_chars(substrwords('Total Discounts',18,""),"right",18," ").align_center("",5," ")
                                 .append_chars(number_format(($total_regular_discs+$total_senior_discs+$total_pwd_discs),2),"left",13," ")."\r\n\r\n";                                 
            }else{
                $print_str .=
                    "\r\n".align_center("NO SALES",38)."\r\n";
            }
            if (!$asJson) {
                // echo "<pre style='background-color:#fff'>$print_str</pre>";
    			echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
    		} 
        }
        public function void_sales_rep($asJson=false){
            $print_str = $this->rep_header();
            $userdata = $this->session->userdata('user');
            $date = $this->input->post('date');
            $dates = explode(' to ', $date);
            $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
            
            $args = array();
            $terminal = $this->input->post('terminal');
            $cashier = $this->input->post('cashier');
            if(!empty($cashier)){
                $server = $this->manager_model->get_server_details($cashier);
                $cashier_name = $server[0]->fname.' '.$server[0]->lname;
                $args['trans_sales.user_id'] = $cashier;
            }else{
                $cashier_name = 'All Cashier';
            }
            $terminal = TERMINAL_ID;
            if(!empty($terminal)){
                $terminal_name = $terminal;
                $args['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }

            $time = $this->site_model->get_db_now();
            $title_name = "Void Sales Report";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= append_chars(substrwords('Employee',18,""),"right",17," ")
                         .append_chars($cashier_name,"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Date From',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_from),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Date To',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_to),"left",8," ")."\r\n";             
            $print_str .= append_chars(substrwords('Printed on',18,""),"right",17," ")
                         .append_chars(date2SqlDateTime($time),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Printed by',18,""),"right",17," ")
                         .append_chars($userdata['fname'].' '.$userdata['lname'],"left",8," ")."\r\n";
            $print_str .= "======================================"."\r\n";

            $args["DATE(trans_sales.datetime)  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            $orders = array();
            $orders['cancel'] = array(); 
            $orders['sale'] = array();
            $orders['void'] = array();
            $gross = 0;
            $gross_ids = array();
            $paid = 0;
            $paid_ctr = 0;
            $types = array();
            $ids = array();
            foreach ($trans_sales as $sale) {
                if($sale->type_id == 10){
                    if($sale->trans_ref != "" && $sale->inactive == 0){
                        $orders['sale'][$sale->sales_id] = $sale;
                        $gross += $sale->total_amount;
                        $gross_ids[] = $sale->sales_id;
                        if($sale->total_paid > 0){
                            $paid += $sale->total_paid;
                            $paid_ctr ++;                   
                        }
                        $types[$sale->type][$sale->sales_id] = $sale;
                    }
                    else if($sale->trans_ref == "" && $sale->inactive == 1){
                        $orders['cancel'][$sale->sales_id] = $sale;
                    }
                }
                else{
                    $orders['void'][$sale->sales_id] = $sale;
                }
                $ids[] = $sale->sales_id;
            }

            #VOID
                $print_str .= "\r\n".append_chars(substrwords('Voided Receipts',18,""),"right",18," ").align_center(null,5," ")
                           .append_chars(null,"left",13," ")."\r\n";    
                $print_str .= "-----------------"."\r\n";
                $total_void_sales = 0;
                if(count($orders['void']) > 0){
                    foreach ($orders['void'] as $v) {
                        $print_str .= append_chars(substrwords("Ref ".$v->trans_ref,18,""),"right",18," ").align_center('',5," ")
                                 .append_chars(number_format($v->total_amount,2),"left",13," ")."\r\n";
                        if($v->table_name != ""){
                            $print_str .= append_chars(substrwords("--".$v->table_name,18,""),"right",18," ").align_center('',5," ")
                                     .append_chars('',"left",13," ")."\r\n";
                        }
                        $total_void_sales += $v->total_amount;
                    }                
                }  
                else{
                    $print_str .= append_chars(substrwords("No Sales Found.",18,""),"right",18," ").align_center('',5," ")."\r\n";
                }         
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords("Total",18,""),"right",18," ").align_center('',5," ")
                                     .append_chars(numInt($total_void_sales),"left",13," ")."\r\n";
            #Canceled
                $print_str .= "\r\n".append_chars(substrwords('Cancelled Orders',18,""),"right",18," ").align_center(null,5," ")
                           .append_chars(null,"left",13," ")."\r\n";    
                $print_str .= "-----------------"."\r\n";
                $total_void_sales = 0;
                if(count($orders['cancel']) > 0){
                    foreach ($orders['cancel'] as $v) {
                        $print_str .= append_chars(substrwords("Order #".$v->sales_id,18,""),"right",18," ").align_center('',5," ")
                                 .append_chars(number_format($v->total_amount,2),"left",13," ")."\r\n";
                        if($v->table_name != ""){
                            $print_str .= append_chars(substrwords("--".$v->table_name,18,""),"right",18," ").align_center('',5," ")
                                     .append_chars('',"left",13," ")."\r\n";
                        }
                        $total_void_sales += $v->total_amount;
                    }                
                }  
                else{
                    $print_str .= append_chars(substrwords("No Sales Found. ",18,""),"right",18," ").align_center('',5," ")."\r\n";
                }         
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords("Total ",18,""),"right",18," ").align_center('',5," ")
                                     .append_chars(numInt($total_void_sales),"left",13," ")."\r\n";
            #Removed items
                if(count($ids) > 0){
                    $removed_items = $this->cashier_model->get_reasons($ids);
                    $print_str .= "\r\n".append_chars(substrwords('Removed Items',18,""),"right",18," ").align_center(null,5," ")
                               .append_chars(null,"left",13," ")."\r\n";    
                    $print_str .= "-----------------"."\r\n";
                
                    if(count($removed_items) > 0){
                        foreach ($removed_items as $v) {
                            $print_str .= append_chars(substrwords("Order #".$v->trans_id,18,""),"right",18," ").align_center('',5," ")
                                     .append_chars(null,"left",13," ")."\r\n";
                            $print_str .= append_chars(substrwords("*".$v->ref_name,18,""),"right",18," ").align_center('',5," ")
                                     .append_chars("","left",13," ")."\r\n";
                            $print_str .= " ".urldecode($v->reason)."\r\n";         
                        }                
                    }  
                    else{
                        $print_str .= append_chars(substrwords("No Sales Found. ",18,""),"right",18," ").align_center('',5," ");
                    }
                }



            if (!$asJson) {
                // echo "<pre style='background-color:#fff'>$print_str</pre>";
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
            }  
        }
        public function employee_sales_rep($asJson=false){
            $print_str = $this->rep_header();
            $daterange = $this->input->post('daterange');
            $server_id = $this->input->post('server_id');
            $user = $this->session->userdata('user');
            $curr_shift_id = array();
            $time = $this->site_model->get_db_now();
            $date = $daterange;
            
            // $date = '2015-06-23';
            // $server_id = 37;
            $this->db = $this->load->database('main', TRUE);
            
            $today = sql2Date($time);
            $useID = 'id';
            if(strtotime($date) < strtotime($today)){
                $useID = 'read_id';
            }

            $lastRead = $this->cashier_model->get_last_z_read_on_date(X_READ,date2Sql($date),$server_id);
            if(count($lastRead) > 0){
                foreach ($lastRead as $res) {

                    $curr_x_read_shift = $this->cashier_model->get_x_read_shift($res->$useID);  
                    if(count($curr_x_read_shift) > 0){
                        foreach ($curr_x_read_shift as $cxrs) {
                           $curr_shift_id[] = $cxrs->shift_id;
                        }
                    }
                }
                foreach ($lastRead as $res) {
                    $date_from = $res->scope_from;
                    break;
                }
                foreach ($lastRead as $res) {
                    $date_to = $res->scope_to;
                }

            }
            else{
                $print_str .= align_center("No Sales Read Found.",38," ")."\r\n";
                $this->do_print($print_str,$asJson);
                return false;
            }
            $terminal = TERMINAL_ID;
            if(!empty($terminal)){
                $terminal_name = $terminal;
                $args['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }
            // $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args['trans_sales.shift_id'] = $curr_shift_id;
            $args['trans_sales.user_id'] = $server_id;
            $server = $this->manager_model->get_server_details($server_id);
            $emp = $server[0]->fname." ".$server[0]->mname." ".$server[0]->lname." ".$server[0]->suffix;

            $title_name = "Employee Sales Report";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= "\r\n";
            $print_str .= append_chars('Terminal','right',14," ").append_chars($terminal_name,'right',19," ")."\r\n";
            $print_str .= append_chars('Employee','right',14," ").append_chars($emp,'right',19," ")."\r\n";
            $print_str .= append_chars('Sales Type','right',14," ").append_chars('ALL','right',19," ")."\r\n";
            $print_str .= append_chars('Date Range','right',14," ").append_chars($date_from.' - '.$date_to,'right',19," ")."\r\n";
            $print_str .= append_chars('Printed On','right',14," ").append_chars(date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',14," ").append_chars($user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            if(count($trans_sales) > 0){
                $orders = array();
                $orders['cancel'] = array(); 
                $orders['sale'] = array();
                $orders['void'] = array();
                $net_sales = 0;
                $ids = array();
                $paid = 0;
                $paid_ctr = 0;
                $types = array();
                foreach ($trans_sales as $sale) {
                    if($sale->type_id == 10){
                        if($sale->trans_ref != "" && $sale->inactive == 0){
                            $orders['sale'][$sale->sales_id] = $sale;
                            $net_sales += $sale->total_amount;
                            $ids[] = $sale->sales_id;
                            if($sale->total_paid > 0){
                                $paid += $sale->total_paid;
                                $paid_ctr ++;                   
                            }
                            $types[$sale->type][$sale->sales_id] = $sale;
                        }
                        else if($sale->trans_ref == "" && $sale->inactive == 1){
                            $orders['cancel'][$sale->sales_id] = $sale;
                        }
                        else if($sale->trans_ref != "" && $sale->inactive == 1){
                            $orders['deleted'][$sale->sales_id] = $sale;
                        }
                    }
                    else{
                        $orders['void'][$sale->sales_id] = $sale;
                    }
                }
                #DISOUNTS 
                    $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$ids));
                    $total_disc = 0;
                    $disc_codes = array();
                    foreach ($sales_discs as $discs) {
                        if(!isset($disc_codes[$discs->disc_code])){
                            $disc_codes[$discs->disc_code] = array('qty'=> 1,'amount'=>$discs->amount);
                        }
                        else{
                            $disc_codes[$discs->disc_code]['qty'] += 1;
                            $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                        }
                        $total_disc += $discs->amount;
                    }
                    //SENIOR
                        $total_senior_discs = 0;
                        $seni_disc_ctr = 0;
                        foreach ($disc_codes as $code => $dc) {
                            if($code == "SNDISC"){
                                $total_senior_discs += $dc['amount'];
                                $seni_disc_ctr += $dc['qty'];                                    
                            }
                        }
                        $total_senior_discs = numInt($total_senior_discs);
                    //PWD
                        $total_pwd_discs = 0;
                        $pwd_disc_ctr = 0;
                        foreach ($disc_codes as $code => $dc) {
                            if($code == "PWDISC"){
                                $total_pwd_discs += $dc['amount'];
                                $pwd_disc_ctr += $dc['qty'];                                    
                            }
                        }
                        $total_pwd_discs = numInt($total_pwd_discs);
                #CHARGES 
                    $cargs["trans_sales_charges.sales_id"] = $ids;
                    $cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);   
                    $total_service_charges = 0;
                    $total_delivery_charges = 0;
                    $other_charges = 0;
                    $total_charges = 0;
                    foreach ($cesults as $ces) {
                        if($ces->charge_id == 1){
                            $total_service_charges += $ces->amount;
                        }
                        if($ces->charge_id == 2){
                            $total_delivery_charges += $ces->amount;
                        }
                        else{
                            $other_charges += $ces->amount;
                        }
                        $total_charges += $ces->amount;
                    } 
                #LOCAL TAX
                    $targs["trans_sales_local_tax.sales_id"] = $ids;
                    $tesults = $this->site_model->get_tbl('trans_sales_local_tax',$targs);   
                    $total_local_tax = 0;
                    foreach ($tesults as $tes) {
                        $total_local_tax += $tes->amount;
                    }
                #TAXES
                    $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$ids,"trans_sales_zero_rated.amount >"=>0));
                    // echo $this->cashier_model->db->last_query();
                    // echo var_dump($zero_rated);
                    $zrctr = 0;
                    $zrtotal = 0;
                    foreach ($zero_rated as $zt) {
                        $zrtotal += $zt->amount;
                        $zrctr++;
                    }
                    $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$ids,"trans_sales_no_tax.amount >"=>0));
                    $ntctr = 0;
                    $nttotal = 0;
                    foreach ($no_tax as $nt) {
                        $nttotal += $nt->amount;
                        $ntctr++;
                    }
                    $tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$ids,"trans_sales_tax.amount >"=>0));
                    $tctr = 0;
                    $ttotal = 0;
                    foreach ($tax as $t) {
                        $ttotal += $t->amount;
                        $tctr++;
                    }
                $gross_sales = $net_sales + ($total_disc);
                $print_str .= append_chars(substrwords('Gross Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(num($gross_sales),"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('Total Discounts: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(num($total_disc),"left",13," ")."\r\n";                              
                $print_str .= append_chars(substrwords('Local Tax: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(num($total_local_tax),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Total Charges: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(num($total_service_charges),"left",13," ")."\r\n";              
                $print_str .= append_chars(substrwords('Net Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(num($net_sales),"left",13," ")."\r\n"; 
                $print_str .= "\r\n";
                $total_sales = $net_sales;
                $total_sales += $total_senior_discs;
                $vat = ( ( ($total_sales - $total_local_tax) - ($total_charges)) - ($nttotal)) - $ttotal;
                $nttotal = $nttotal-$zrtotal;
                $print_str .= append_chars(substrwords('VAT Exempt Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($nttotal),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT Sales: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($vat),"left",13," ")."\r\n";               
                $print_str .= append_chars(substrwords('VAT: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($ttotal),"left",13," ")."\r\n";  
                $print_str .= append_chars(substrwords('Zero Rated: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($zrtotal),"left",13," ")."\r\n";   
                $print_str .= "\r\n";
                #TRANS COUNT
                    $types_total = array();
                    $guestCount = 0;
                    foreach ($types as $type => $tp) {
                        foreach ($tp as $id => $opt){
                            if(isset($types_total[$type])){
                                $types_total[$type] += $opt->total_amount;
                            
                            }
                            else{
                                $types_total[$type] = $opt->total_amount;
                            }
                            if($opt->guest == 0)
                                $guestCount += 1;
                            else
                                $guestCount += $opt->guest;
                        }    
                    }
                    $print_str .= append_chars(substrwords('Trans Count',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars('',"left",13," ")."\r\n";
                    $tc_total  = 0;
                    $tc_qty = 0;
                    foreach ($types_total as $typ => $tamnt) {
                        $print_str .= append_chars(substrwords($typ,18,""),"right",18," ").align_center(count($types[$typ]),5," ")
                                     .append_chars(number_format($tamnt,2),"left",13," ")."\r\n";             
                        $tc_total += $tamnt; 
                        $tc_qty += count($types[$typ]);
                    }
                    $print_str .= "-----------------"."\r\n";
                    $print_str .= append_chars(substrwords('TC Total',18,""),"right",23," ")
                                 .append_chars(number_format($tc_total,2),"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords('Guest Total',18,""),"right",23," ")
                                 .append_chars(number_format($guestCount,2),"left",13," ")."\r\n";
                    if($tc_total == 0 || $tc_qty == 0)
                        $avg = 0;
                    else
                        $avg = $tc_total/$tc_qty;             
                    $print_str .= append_chars(substrwords('Average Check',18,""),"right",23," ")
                                 .append_chars(number_format($avg,2),"left",13," ")."\r\n";       
                $print_str .= "\r\n";                   
                #PAYMENT DETAILS
                    $payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$ids));
                    $pays = array();
                    foreach ($payments as $py) {
                        if($py->amount > $py->to_pay)
                            $amount = $py->to_pay;
                        else
                            $amount = $py->amount;
                        if(!isset($pays[$py->payment_type])){
                            $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                        }
                        else{
                            $pays[$py->payment_type]['qty'] += 1;
                            $pays[$py->payment_type]['amount'] += $amount;
                        }
                    }
                    $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(null,"left",13," ")."\r\n"; 
                    $pay_total = 0;
                    $pay_qty = 0;
                    foreach ($pays as $type => $pay) {
                        $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                                     .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                        $pay_total += $pay['amount'];
                        $pay_qty += $pay['qty'];
                    }
                    $print_str .= "-----------------"."\r\n";
                    $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                                 .append_chars(number_format($pay_total,2),"left",13," ")."\r\n";
                $print_str .= "\r\n";
                #DISCOUNTS DETAILS
                    $print_str .= append_chars(substrwords('Discount Details',18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(null,"left",13," ")."\r\n"; 
                    $disc_amount = 0;
                    $disc_qty = 0;
                    foreach ($disc_codes as $dodes => $di) {
                        $print_str .= append_chars(substrwords(strtoupper($dodes),18,""),"right",18," ").align_center($di['qty'],5," ")
                                     .append_chars(number_format($di['amount'],2),"left",13," ")."\r\n";
                        $disc_amount += $di['amount'];
                        $disc_qty += $di['qty'];
                    }
                    $print_str .= "-----------------"."\r\n";
                    $print_str .= append_chars(substrwords('Total Discount',18,""),"right",18," ").align_center($disc_qty,5," ")
                                 .append_chars(number_format($disc_amount,2),"left",13," ")."\r\n";
                $print_str .= "\r\n";
                #VOIDS
                    $print_str .= "\r\n";
                    $cancel_total = 0;
                    foreach ($orders['cancel'] as $cnl) {
                        $cancel_total += $cnl->total_amount;

                    }
                    $print_str .= append_chars(substrwords('Void Before Subt',18,""),"right",18," ").align_center(count($orders['cancel']),5," ")
                                 .append_chars(number_format($cancel_total,2),"left",13," ")."\r\n";
                    if($cancel_total == 0 || count($orders['cancel']) == 0)
                        $avg = 0;
                    else
                        $avg = $cancel_total/count($orders['cancel']);
                    $print_str .= append_chars(substrwords('AVG Void B4 Subt',18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(number_format($avg,2),"left",13," ")."\r\n";
                    
                    $void_total = 0;
                    foreach ($orders['void'] as $v) {
                        $void_total += $v->total_amount;
                    }            
                    $print_str .= append_chars(substrwords('Void After Subt',18,""),"right",18," ").align_center(count($orders['void']),5," ")
                                 .append_chars(number_format($void_total,2),"left",13," ")."\r\n";
                    if($void_total == 0 || count($orders['void']) == 0)
                        $avg = 0;
                    else
                        $avg = $void_total/count($orders['void']);             
                    $print_str .= append_chars(substrwords('AVG Void Aft Subt',18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(number_format($avg,2),"left",13," ")."\r\n\r\n";
                    if(count($orders['void']) > 0){
                        $print_str .= append_chars(substrwords('Voids Aft Subt',18,""),"right",18," ").align_center(null,5," ")
                                     .append_chars(null,"left",13," ")."\r\n";
                        $print_str .= "-----------------"."\r\n";
                        foreach ($orders['void'] as $v) {
                            $print_str .= append_chars(substrwords("Ref ".$v->trans_ref,18,""),"right",18," ").align_center('',5," ")
                                     .append_chars(number_format($v->total_amount,2),"left",13," ")."\r\n";
                            if($v->table_name != ""){
                                $print_str .= append_chars(substrwords("--".$v->table_name,18,""),"right",18," ").align_center('',5," ")
                                         .append_chars('',"left",13," ")."\r\n";
                                
                            }
                            $print_str .= "*************"."\r\n";
                        }                
                        $print_str .= "-----------------"."\r\n";
                    }
                $print_str .= "======================================"."\r\n";
                #INVOICE CTR
                    $ordsnums = array();
                    // $ref_ctr = 0;
                    foreach ($orders as $typ => $ord) {
                        if($typ != 'void' && $typ != 'cancel'){
                            foreach ($ord as $sales_id => $sale) {
                                // $ordsnums[$sales_id] = $sale;
                                $ordsnums[$sale->trans_ref] = $sale;
                                // $ref_ctr++;
                            }                    
                        }
                    }
                    ksort($ordsnums);
                    // echo var_dump($ordsnums);
                    $first = array_shift(array_slice($ordsnums, 0, 1));
                    $last = end($ordsnums);
                    $ref_ctr = count($ordsnums);
                    $print_str .= append_chars(substrwords('Invoice Start: ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars($first->trans_ref,"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords('Invoice End: ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars($last->trans_ref,"left",13," ")."\r\n";    
                    $print_str .= append_chars(substrwords('Invoice Ctr: ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars($ref_ctr,"left",13," ")."\r\n";


                $this->do_print($print_str,$asJson);
            }    
            else{
                $print_str .= align_center("No Sales Read Found.",38," ")."\r\n";
                $this->do_print($print_str,$asJson);
                return false;     
            }
        }
        public function menu_sales_rep($asJson=false){
            $print_str = $this->rep_header();
            $daterange = $this->input->post('daterange');
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            // $daterange = '06/23/2015';
            $date = $daterange;
            $this->db = $this->load->database('main', TRUE);
            $lastRead = $this->cashier_model->get_last_z_read_on_date(Z_READ,date2Sql($date));

            if(count($lastRead) == 0){
                $print_str .= align_center("No Sales Read Found.",38," ")."\r\n";
                $this->do_print($print_str,$asJson);
                return false;
            }
            else{
                foreach ($lastRead as $res) {
                    $date_from = $res->scope_from;
                    $date_to = $res->scope_to;
                    break;
                }
            }
            $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args['type_id'] = 10;
            $args['trans_sales.inactive'] = 0;
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            
            if($this->input->post('user_id')){
                $args['trans_sales.user_id'] = $this->input->post('user_id');
            }
            $terminal = TERMINAL_ID;
            if(!empty($terminal)){
                $terminal_name = $terminal;
                $args['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }
            // $sales_menus = $this->cashier_model->get_menu_sales(null,$args);
            $emp = 'ALL';
            if($this->input->post('user_id')){
                $server = $this->manager_model->get_server_details($this->input->post('user_id'));
                $emp = $server[0]->fname." ".$server[0]->mname." ".$server[0]->lname." ".$server[0]->suffix;
            }

            
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            

            $title_name = "Menu Sales Report";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= "\r\n";
            $print_str .= append_chars('Terminal','right',14," ").append_chars($terminal_name,'right',19," ")."\r\n";
            $print_str .= append_chars('Employee','right',14," ").append_chars($emp,'right',19," ")."\r\n";
            $print_str .= append_chars('Sales Type','right',14," ").append_chars('ALL','right',19," ")."\r\n";
            $print_str .= append_chars('Date Range','right',14," ").append_chars($date_from.' - '.$date_to,'right',19," ")."\r\n";
            $print_str .= append_chars('Printed On','right',14," ").append_chars(date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',14," ").append_chars($user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            if(count($trans_sales) > 0){
                $sales_net_total = 0;
                $ids = array();
                foreach ($trans_sales as $res) {
                    $sales_net_total += $res->total_amount;
                    $ids[] = $res->sales_id;
                }
                $select = 'trans_sales_menus.*,menus.menu_name,menus.cost as sell_price,menus.menu_cat_id as cat_id,menus.menu_sub_cat_id as sub_cat_id';
                $join = null;           
                $join['menus'] = array('content'=>'trans_sales_menus.menu_id = menus.menu_id');
                $menu_res = $this->site_model->get_tbl('trans_sales_menus',array('sales_id'=>$ids),array(),$join,true,$select);
                $cats = array();
                $cat_res = $this->site_model->get_tbl('menu_categories');
                foreach ($cat_res as $ces) {
                    $cats[$ces->menu_cat_id] = array('cat_id'=>$ces->menu_cat_id,'name'=>$ces->menu_cat_name,'qty'=>0,'amount'=>0);
                }
                $sub_cats = array();
                $sales_menu_cat = $this->menu_model->get_menu_subcategories();
                foreach ($sales_menu_cat as $rc) {
                    $sub_cats[$rc->menu_sub_cat_id] = array('name'=>$rc->menu_sub_cat_name,'qty'=>0,'amount'=>0);
                }

                $menu_net_total = 0;
                $menu_qty_total = 0;
                $menus = array();
                foreach ($menu_res as $ms) {
                   
                    if(!isset($menus[$ms->menu_id])){
                        $mn = array();
                        $mn['name'] = $ms->menu_name;
                        $mn['cat_id'] = $ms->cat_id;
                        $mn['qty'] = $ms->qty;
                        $mn['amount'] = $ms->price * $ms->qty;
                        $menus[$ms->menu_id] = $mn;
                    }
                    else{
                        $mn = $menus[$ms->menu_id];
                        $mn['qty'] += $ms->qty;
                        $mn['amount'] += $ms->price * $ms->qty;
                        $menus[$ms->menu_id] = $mn;
                    }

                    if(isset($sub_cats[$ms->sub_cat_id])){
                        $sub = $sub_cats[$ms->sub_cat_id];
                        $sub['qty'] += $ms->qty;
                        $sub['amount'] += $ms->price * $ms->qty;
                        $sub_cats[$ms->sub_cat_id] = $sub;
                    }
                    if(isset($cats[$ms->cat_id])){
                        $cat = $cats[$ms->cat_id];
                        $cat['qty'] += $ms->qty;
                        $cat['amount'] += $ms->price * $ms->qty;
                        $cats[$ms->cat_id] = $cat;
                    }
                    $menu_net_total += $ms->price * $ms->qty;
                    $menu_qty_total += $ms->qty;
                }

                usort($cats, function($a, $b) {
                    return $b['amount'] - $a['amount'];
                });
               
                foreach ($cats as $cat_id => $ca) {
                    if($ca['qty'] > 0){
                        $print_str .=
                             append_chars($ca['name'],'right',18," ")
                            .append_chars(num($ca['qty']),'right',10," ")
                            .append_chars(num($ca['amount']).'','left',10," ")."\r\n";
                        $print_str .= "======================================"."\r\n";    
                        foreach ($menus as $menu_id => $res) {
                            if($ca['cat_id'] == $res['cat_id']){
                            $print_str .=
                                append_chars($res['name'],'right',18," ")
                                .append_chars(num($res['qty']),'right',10," ")
                                .append_chars(num( ($res['qty'] / $menu_qty_total) * 100).'%','left',10," ")."\r\n";
                            $print_str .=
                                append_chars(null,'right',18," ")
                                .append_chars(num($res['amount']),'right',10," ")
                                .append_chars(num( ($res['amount'] / $menu_net_total) * 100).'%','left',10," ")."\r\n";
                            }
                        }
                        $print_str .= "======================================"."\r\n";    
                    }
                }
                $print_str .= append_chars('Sub Categories',"right",18," ")."\r\n";
                foreach ($sub_cats as $subid => $sc) {
                    $print_str .= append_chars($sc['name'],"right",18," ")
                             .append_chars(num($sc['qty']),'right',10," ")
                             .append_chars(num($sc['amount']),"left",10," ")."\r\n";
                }
                $print_str .= "======================================"."\r\n";
                $menu_cat_sale_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$ids));
                $total_md = 0;
                if(count($menu_cat_sale_mods) > 0){
                    $mods = array();
                    foreach ($menu_cat_sale_mods as $res) {
                        if(!isset($mods[$res->mod_id])){
                            $mods[$res->mod_id] = array(
                                'name'=>$res->mod_name,
                                'price'=>$res->price,
                                'qty'=>$res->qty,
                                'total_amt'=>$res->qty * $res->price,
                            );
                        }
                        else{
                            $mod = $mods[$res->mod_id];
                            $mod['qty'] += $res->qty;
                            $mod['total_amt'] += $res->qty * $res->price;
                            $mods[$res->mod_id] = $mod;
                        }
                    }
                    usort($mods, function($a, $b) {
                        return $b['total_amt'] - $a['total_amt'];
                    });

                    $print_str .= append_chars('Menu Modifiers',"right",18," ")."\r\n";
                    foreach ($mods as $modid => $md) {
                        $print_str .= append_chars($md['name'],"right",18," ")
                                 .append_chars(num($md['qty']),'right',10," ")
                                 .append_chars(num($md['total_amt']),"left",10," ")."\r\n";
                        $total_md += $md['total_amt'];
                    }
                    $print_str .= append_chars('',"right",18," ")
                             .append_chars('','right',10," ")
                             .append_chars('----------',"left",10," ")."\r\n";
                    $print_str .= append_chars('',"right",18," ")
                             .append_chars('','right',10," ")
                             .append_chars(num($total_md),"left",10," ")."\r\n";
                    $print_str .= "======================================"."\r\n";
                    $menu_net_total += $total_md;
                
                }


                $disc_res = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$ids));
                $discs_total = 0;
                foreach ($disc_res as $dsc) {
                    $discs_total += $dsc->amount;
                }
                
                $charges_res = $this->cashier_model->get_trans_sales_charges(null,array("trans_sales_charges.sales_id"=>$ids));
                $charges_total = 0;
                foreach ($charges_res as $ch) {
                    $charges_total += $ch->amount;
                }
                $lc_tax_res = $this->cashier_model->get_trans_sales_local_tax(null,array("trans_sales_local_tax.sales_id"=>$ids));
                $lc_total = 0;
                foreach ($lc_tax_res as $lc) {
                    $lc_total += $lc->amount;
                }


                $less_vat =  ($menu_net_total - ($sales_net_total + $discs_total));
                if($less_vat < 0){
                    $less_vat = 0;
                }
                $print_str .= append_chars('TOTAL OVERALL',"right",18," ")
                             .append_chars(num($menu_qty_total),'right',10," ")
                             .append_chars(num($menu_net_total),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL Less VAT',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($less_vat),"left",10," ")."\r\n";

                if($charges_total > 0){
                    $print_str .= append_chars('TOTAL CHARGES',"right",18," ")
                                 .append_chars(null,'right',10," ")
                                 .append_chars(num($charges_total),"left",10," ")."\r\n";
                }             
                if($lc_total > 0){
                    $print_str .= append_chars('TOTAL LOCAL TAX',"right",18," ")
                                 .append_chars(null,'right',10," ")
                                 .append_chars(num($lc_total),"left",10," ")."\r\n";                    
                }             
                $print_str .= append_chars('TOTAL GROSS SALES',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($sales_net_total + $discs_total),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL ITEMS DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($discs_total),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL NET SALES',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($sales_net_total),"left",10," ")."\r\n";
                $this->do_print($print_str,$asJson);
            }
            else{
                $print_str .= align_center("No Sales Read Found.",38," ")."\r\n";
                $this->do_print($print_str,$asJson);
                return false;        
            }
        }
        public function store_sales_rep($asJson=false){
            $print_str = $this->rep_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $daterange = $this->input->post('daterange');
            // $daterange ="2015/07/21 7:00 AM to 2015/07/21 1:00 PM";
            $dates = explode(" to ",$daterange);
            $from = date2SqlDateTime($dates[0]);
            $to = date2SqlDateTime($dates[1]);

            $args["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args['type_id'] = 10;
            $args['trans_sales.inactive'] = 0;
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $emp = "All Employee";
            if($this->input->post('user_id')){
                $args['trans_sales.user_id'] = $this->input->post('user_id');
                $server = $this->manager_model->get_server_details($this->input->post('user_id'));
                $emp = $server[0]->fname." ".$server[0]->mname." ".$server[0]->lname." ".$server[0]->suffix;
            }
            $terminal = TERMINAL_ID;
            if(!empty($terminal)){
                $terminal_name = $terminal;
                $args['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }
            #####
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            #####
            $title_name = "Store Sales Report";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= "\r\n";
            $print_str .= append_chars('Terminal','right',14," ").append_chars($terminal_name,'right',19," ")."\r\n";
            $print_str .= append_chars('Employee','right',14," ").append_chars($emp,'right',19," ")."\r\n";
            $print_str .= append_chars('Date From','right',14," ").append_chars($from,'right',19," ")."\r\n";
            $print_str .= append_chars('Date To','right',14," ").append_chars($to,'right',19," ")."\r\n";
            $print_str .= append_chars('Printed On','right',14," ").append_chars(date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',14," ").append_chars($user['full_name'],'right',19," ")."\r\n";
            $print_str .= "===================================="."\r\n";

            if(count($trans_sales) > 0){
                $sales_net_total = 0;
                $ids = array();
                $trans_types = array();
                $ordsnums = array();
                foreach ($trans_sales as $res) {
                    $sales_net_total += $res->total_amount;
                    $ids[] = $res->sales_id;
                    $guest = 1;
                    if($res->guest > 0)
                        $guest = $res->guest;
                    if(!isset($trans_types[$res->type])){
                        $trans_types[$res->type] = array('qty'=>1,'amount'=>$res->total_amount,'guest'=>$guest);
                    }
                    else{
                        $tt = $trans_types[$res->type];
                        $tt['qty'] += 1;
                        $tt['guest'] += $guest;
                        $tt['amount'] += $res->total_amount;
                        $trans_types[$res->type] = $tt;
                    }
                    $ordsnums[$res->trans_ref] = $res;
                }
                ksort($ordsnums);
                #################
                ### GET TAXES
                    $taxes = $this->get_taxes($ids);
                    $total_local_tax = $taxes['local_tax']['amount'];
                    $nttotal = $taxes['no_tax']['amount'];
                    $ttotal = $taxes['tax']['amount'];
                #################
                ### DISCOUNTS
                    $discounts = $this->get_discounts($ids);
                    $discount_total = $discounts['total'];
                    $discount_types = $discounts['types'];
                    $total_senior_discs = 0;
                    if(isset($discount_types['SNDISC']))
                        $total_senior_discs = $discount_types['SNDISC']['amount'];
                #################
                ### CHARGES
                    $charges = $this->get_charges($ids);
                    $charges_total = $charges['total'];
                    $charges_types = $charges['types'];
                #################
                ### PAYMENTS
                    $payments = $this->get_payments($ids);
                    $payments_total = $payments['total'];
                    $payments_types = $payments['types'];
                
                ## COMPUTATION
                    $net = $sales_net_total;
                    $sales_net_total += $total_senior_discs;
                    $vat = ( ( ($sales_net_total - $total_local_tax) - ($charges_total)) - ($nttotal)) - $ttotal;   
                ##########################
                #### TRANS SUMMARY
                    $print_str .= append_chars(substrwords('Total Sales',18,""),"right",23," ")
                                 .append_chars(number_format( $net - ($total_local_tax + $charges_total) - $ttotal ,2),"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords('12% VAT',18,""),"right",23," ")
                                 .append_chars(number_format($ttotal,2),"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords('Local Tax',18,""),"right",23," ")
                                 .append_chars(number_format($total_local_tax,2),"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords('Total Charges',18,""),"right",23," ")
                                 .append_chars(number_format($charges_total,2),"left",13," ")."\r\n";
                    $print_str .= "-----------------"."\r\n";
                    $print_str .= append_chars(substrwords('Net Sales',18,""),"right",23," ")
                                 .append_chars(number_format($net,2),"left",13," ")."\r\n";     
                    $print_str .= append_chars(substrwords('Total Discounts',18,""),"right",23," ")
                                 .append_chars(number_format($discount_total,2),"left",13," ")."\r\n";             
                    $print_str .= append_chars(substrwords('Gross W/ Disc',18,""),"right",23," ")
                                 .append_chars(number_format($net+$discount_total,2),"left",13," ")."\r\n";
                ##########################
                #### TRANSACTION TYPES
                    $print_str .= "\r\n";
                    $tt_qty = 0;
                    $print_str .= append_chars(substrwords('Trans Count',18,""),"right",18," ").align_center(null,5," ")
                                  .append_chars(null,"left",13," ")."\r\n";
                    $tc_total = 0;
                    foreach ($trans_types as $code => $val) {
                       $tc_total += $val['amount'];
                    }
                    foreach ($trans_types as $code => $val) {
                        $print_str .= append_chars(substrwords(strtoupper($code),18,""),"right",18," ").align_center($val['qty'],5," ")
                                      .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                        $print_str .= append_chars(substrwords(strtoupper($code)." %",18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(numInt(($val['amount']/$tc_total)*100),"left",13," ")."\r\n"; 
                        $tt_qty += $val['qty'];
                    }
                    $print_str .= "-----------------"."\r\n";
                    $print_str .= append_chars(substrwords('TC Total',18,""),"right",18," ").align_center($tt_qty,5," ")
                                  .append_chars(numInt($tc_total),"left",13," ")."\r\n"; 
                    $print_str .= append_chars(substrwords('Average Check',18,""),"right",18," ").align_center(null,5," ")
                                  .append_chars(numInt(avg($tc_total,$tt_qty)),"left",13," ")."\r\n";  
                ##########################
                #### GUEST COUNT
                     $print_str .= "\r\n";
                     $total_guest = 0;
                     foreach ($trans_types as $code => $val) {
                        $total_guest += $val['guest'];
                     }                                   
                     $print_str .= append_chars(substrwords('Guest Count',18,""),"right",18," ").align_center(null,5," ")
                                   .append_chars(null,"left",13," ")."\r\n"; 
                     foreach ($trans_types as $code => $val) {
                         $print_str .= append_chars(substrwords(strtoupper($code),18,""),"right",18," ").align_center(null,5," ")
                                       .append_chars(numInt($val['guest']),"left",13," ")."\r\n";
                         $print_str .= append_chars(substrwords(strtoupper($code)." %",18,""),"right",18," ").align_center(null,5," ")
                                  .append_chars(numInt(($val['guest']/$total_guest)*100),"left",13," ")."\r\n";               
                     }
                     $print_str .= "-----------------"."\r\n";
                     $print_str .= append_chars(substrwords('Guest Total',18,""),"right",18," ").align_center(null,5," ")
                                   .append_chars(numInt($total_guest),"left",13," ")."\r\n";               

                ##########################
                #### PAYMENT DETAILS
                    $print_str .= "\r\n";
                    $pay_qty = 0;
                    $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                                  .append_chars(null,"left",13," ")."\r\n"; 
                    foreach ($payments_types as $code => $val) {
                        $print_str .= append_chars(substrwords(ucwords(strtolower($code)),18,""),"right",18," ").align_center($val['qty'],5," ")
                                      .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                        $pay_qty += $val['qty'];
                    }
                    $print_str .= "-----------------"."\r\n";
                    $print_str .= append_chars(substrwords('Total Payments',18,""),"right",18," ").align_center($pay_qty,5," ")
                                  .append_chars(numInt($payments_total),"left",13," ")."\r\n"; 
                ##########################
                #### CHARGE DETAILS
                    $print_str .= "\r\n";
                    $ch_qty = 0;
                    $print_str .= append_chars(substrwords('Charge Details',18,""),"right",18," ").align_center(null,5," ")
                                  .append_chars(null,"left",13," ")."\r\n"; 
                    foreach ($charges_types as $ch_code => $ch) {
                        $print_str .= append_chars(substrwords(ucwords(strtolower($ch['name'])),18,""),"right",18," ").align_center($ch['qty'],5," ")
                                      .append_chars(numInt($ch['amount']),"left",13," ")."\r\n";
                        $ch_qty += $ch['qty'];
                    }
                    $print_str .= "-----------------"."\r\n";
                    $print_str .= append_chars(substrwords('Total Charges',18,""),"right",18," ").align_center($ch_qty,5," ")
                                  .append_chars(numInt($charges_total),"left",13," ")."\r\n"; 
                ##########################
                #### DISCOUNT DETAILS 
                    $print_str .= "\r\n";
                    $disc_qty = 0;
                    $print_str .= append_chars(substrwords('Discount Details',18,""),"right",18," ").align_center(null,5," ")
                                  .append_chars(null,"left",13," ")."\r\n"; 
                    foreach ($discount_types as $disc_code => $dc) {
                        $print_str .= append_chars(substrwords(strtoupper($dc['name']),18,""),"right",18," ").align_center($dc['qty'],5," ")
                                      .append_chars(numInt($dc['amount']),"left",13," ")."\r\n";
                        $disc_qty += $dc['qty'];
                    }
                    $print_str .= "-----------------"."\r\n";
                    $print_str .= append_chars(substrwords('Total Discount',18,""),"right",18," ").align_center($disc_qty,5," ")
                                  .append_chars(numInt($discount_total),"left",13," ")."\r\n";    
                ##########################
                #### INVOICES
                    $print_str .= "\r\n"."===================================="."\r\n";
                    $first = array_shift(array_slice($ordsnums, 0, 1));
                    $last = end($ordsnums);
                    $ref_ctr = count($ordsnums);
                    $print_str .= append_chars(substrwords('Invoice Start: ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars($first->trans_ref,"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords('Invoice End: ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars($last->trans_ref,"left",13," ")."\r\n";    
                    $print_str .= append_chars(substrwords('Invoice Ctr: ',18,""),"right",18," ").align_center('',5," ")
                                 .append_chars($ref_ctr,"left",13," ")."\r\n"; 
                $this->do_print($print_str,$asJson);
                // echo "<pre style='background-color:#fff'>$print_str</pre>";  
            }
            else{
                $print_str .= align_center("No Sales Read Found.",38," ")."\r\n";
                $this->do_print($print_str,$asJson);
                return false;  
            }
        }
        public function do_print($print_str=null,$asJson=false){
            if (!$asJson) {
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
                // echo "<pre style='background-color:#fff'>$print_str</pre>";
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
            }
        }
    //#####
    //EXCEL REPORTS
    //#####
        public function daily_sales_rep_excel(){
            $this->load->library('Excel');
            $sheet = $this->excel->getActiveSheet();
            $userdata = $this->session->userdata('user');
            //$date = $this->input->post('daterange');
            $date = $_GET['daterange'];
            //$date = str_replace('-', '/', $date);
            //echo $date;
            //$date = '11/23/2014';
            $dates = explode(" to ",$date);
            //return false;
            //var_dump($dates);
            $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));

            $time = $this->site_model->get_db_now();
            $title_name = "Daily Sales Report";
            $cashier = $this->input->post('cashier');
            if(!empty($cashier)){
                $server = $this->manager_model->get_server_details($cashier);
                $cashier_name = $server[0]->fname.' '.$server[0]->lname;
                $args['trans_sales.user_id'] = $cashier;
            }else{
                $cashier_name = 'All Cashier';
            }
            $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            $rc = 1;
            if(count($trans_sales) == 0){
                $filename='Daily Sales Report';
                $sheet->getCell('A'.$rc)->setValue('No Sales');
                ob_end_clean();
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                $objWriter->save('php://output');
                return false; 
            }
            
            $filename='Daily Sales Report';

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
                    'email' => $bv->email,
                    'website' => $bv->website,
                    'store_open' => $bv->store_open,
                    'store_close' => $bv->store_close,
                );
            }

            $sheet->mergeCells('A'.$rc.':J'.$rc);
            $sheet->getCell('A'.$rc)->setValue($branch['name']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':J'.$rc);
            $sheet->getCell('A'.$rc)->setValue($branch['address']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':J'.$rc);
            $sheet->getCell('A'.$rc)->setValue('TIN: '.$branch['tin']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':J'.$rc);
            $sheet->getCell('A'.$rc)->setValue('ACCRDN: '.$branch['accrdn']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':J'.$rc);
            $sheet->getCell('A'.$rc)->setValue('PERMIT: '.$branch['permit_no']);
            // $rc++;
            // // $sheet->mergeCells('A'.$rc.':J'.$rc);
            // // $sheet->getCell('A'.$rc)->setValue('SN #'.$branch['serial']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':J'.$rc);
            $sheet->getCell('A'.$rc)->setValue('MIN: '.$branch['machine_no']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':J'.$rc);
            $sheet->getCell('A'.$rc)->setValue('DAILY SALES REPORT');
            $rc++;
            $sheet->mergeCells('A'.$rc.':J'.$rc);
            $sheet->getCell('A'.$rc)->setValue($dates[0]." - ".$dates[1]);
            // $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->setBold(true);
            // $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->getColor()->setRGB('FF0000');
            


            $rc++;
            $sheet->getStyle("A".$rc.":J11")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$rc.':'.'J11')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->getStyle('A'.$rc.':'.'J11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('A'.$rc.':'.'J11')->getFill()->getStartColor()->setRGB('29bb04');
            $sheet->getStyle('A1:'.'J11')->getFont()->setBold(true);

            $sheet->getCell('A'.$rc)->setValue('OR Number');
            $sheet->mergeCells('A'.$rc.':A'.($rc+1));
            $sheet->getCell('B'.$rc)->setValue('Total Sales');
            $sheet->mergeCells('B'.$rc.':B'.($rc+1));
            $sheet->getCell('C'.$rc)->setValue('VATable Sales');
            $sheet->mergeCells('C'.$rc.':C'.($rc+1));
            $sheet->getCell('D'.$rc)->setValue('VAT-Exempt Sales');
            $sheet->mergeCells('D'.$rc.':D'.($rc+1));
            $sheet->getCell('E'.$rc)->setValue('Zero Rated Sales');
            $sheet->mergeCells('E'.$rc.':E'.($rc+1));
            $sheet->getCell('F'.$rc)->setValue('VAT ');
            $sheet->mergeCells('F'.$rc.':F'.($rc+1));
            $sheet->getCell('G'.$rc)->setValue('Discount');
            $sheet->mergeCells('G'.$rc.':I'.$rc);
            $sheet->getCell('G'.($rc+1))->setValue('Senior Citizen');
            $sheet->getCell('H'.($rc+1))->setValue('PWD');
            $sheet->getCell('I'.($rc+1))->setValue('Regular');
            $sheet->getCell('J'.$rc)->setValue('Net Sales');
            $sheet->mergeCells('J'.$rc.':J'.($rc+1));
            $rc++;
            $rc++;
            $orders = array();
            $orders['cancel'] = array(); 
            $orders['sale'] = array();
            $orders['void'] = array();
            $gross = 0;
            $gross_ids = array();
            $paid = 0;
            $paid_ctr = 0;
            $types = array();
            foreach ($trans_sales as $sale) {
                if($sale->type_id == 10){
                    if($sale->trans_ref != "" && $sale->inactive == 0){
                        $orders['sale'][$sale->sales_id] = $sale;
                        $gross += $sale->total_amount;
                        $gross_ids[] = $sale->sales_id;
                        if($sale->total_paid > 0){
                            $paid += $sale->total_paid;
                            $paid_ctr ++;                   
                        }
                        $types[$sale->type][$sale->sales_id] = $sale;
                    }
                    else if($sale->trans_ref == "" && $sale->inactive == 1){
                        $orders['cancel'][$sale->sales_id] = $sale;
                    }
                }
                else{
                    $orders['void'][$sale->sales_id] = $sale;
                    // $gross += $sale->total_amount;
                    // $gross_ids[] = $sale->sales_id;
                }
            }
            
            $totals = array(
                'Total Sales' => 0,
                'Total VATable' => 0,
                'Total VAT-Exempt' => 0,
                'Total Zero Rated' => 0,
                'Total VAT' => 0,
                'Total Regular Disc' => 0,
                'Total Senior Citizen Disc' => 0,
                'Total PWD Disc' => 0,
                'Total Net Sales'=> 0
            );

            $t_tsales = $t_vatsales = $t_vatexempt = $t_zerorated = $t_vat = $t_senior = $t_pwd = $t_emp = $t_netsales = 0;

            foreach ($orders['sale'] as $sale_id => $sale) {
                #NO TAX
                    $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$sale_id,"trans_sales_zero_rated.amount >"=>0));
                    $zrctr = 0;
                    $zrtotal = 0;
                    foreach ($zero_rated as $zt) {
                        $zrtotal += $zt->amount;
                        $zrctr++;
                    }
                    $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$sale_id,"trans_sales_no_tax.amount >"=>0));
                    $ntctr = 0;
                    $nttotal = 0;
                    foreach ($no_tax as $nt) {
                        $nttotal += $nt->amount;
                        $ntctr++;
                    }
                    $tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$sale_id,"trans_sales_tax.amount >"=>0));
                    $tctr = 0;
                    $ttotal = 0;
                    foreach ($tax as $t) {
                        $ttotal += $t->amount;
                        $tctr++;
                    }
                #DISCOUNTS
                    $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$sale_id));
                    $total_disc = 0;
                    $disc_codes = array();
                    foreach ($sales_discs as $discs) {
                        if(!isset($disc_codes[$discs->disc_code])){
                            $disc_codes[$discs->disc_code] = array('qty'=> 1,'amount'=>$discs->amount);
                        }
                        else{
                            $disc_codes[$discs->disc_code]['qty'] += 1;
                            $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                        }
                        $total_disc += $discs->amount;
                    }
                    $total_regular_discs = 0;
                    $reg_disc_ctr = 0;
                    foreach ($disc_codes as $code => $dc) {
                        if($code != "SNDISC" && $code != "PWDISC"){
                            $total_regular_discs += $dc['amount'];
                            $reg_disc_ctr += $dc['qty'];                                    
                        }
                    }
                    $total_regular_discs = numInt($total_regular_discs);
                    //SENIOR
                    $total_senior_discs = 0;
                    $seni_disc_ctr = 0;
                    foreach ($disc_codes as $code => $dc) {
                        if($code == "SNDISC"){
                            $total_senior_discs += $dc['amount'];
                            $seni_disc_ctr += $dc['qty'];                                    
                        }
                    }
                    $total_senior_discs = numInt($total_senior_discs);
                    //PWD
                    $total_pwd_discs = 0;
                    $pwd_disc_ctr = 0;
                    foreach ($disc_codes as $code => $dc) {
                        if($code == "PWDISC"){
                            $total_pwd_discs += $dc['amount'];
                            $pwd_disc_ctr += $dc['qty'];                                    
                        }
                    }
                    $total_pwd_discs = numInt($total_pwd_discs);

                $vat_sales = (($sale->total_amount - $ttotal) - $nttotal) - $zrtotal;
                if($vat_sales < 0){
                    $vat_sales = 0;
                }
                $localTax = 0;
                $gross = $sale->total_amount;
                $gross += ($total_regular_discs+$total_senior_discs+$total_pwd_discs+$localTax);

                $sheet->getStyle("A".$rc.":J".$rc)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('A'.$rc.':'.'J'.$rc)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


                // $sheet
                // ->setCellValueExplicit('A'.$rc, $sale->trans_ref, PHPExcel_Cell_DataType::TYPE_STRING);

                // $sheet->getStyle("A".$rc)
                // ->getNumberFormat()
                // ->setFormatCode(
                //     PHPExcel_Style_NumberFormat::FORMAT_TEXT
                // );

                $sheet->setCellValueExplicit('A'.$rc, $sale->trans_ref, PHPExcel_Cell_DataType::TYPE_STRING);
                //$sheet->getCell('A'.$rc)->setValue("'".$sale->trans_ref);
                $sheet->getCell('B'.$rc)->setValue(number_format($gross,2));
                $sheet->getCell('C'.$rc)->setValue(number_format($vat_sales,2));
                $sheet->getCell('D'.$rc)->setValue(number_format($nttotal,2));
                $sheet->getCell('E'.$rc)->setValue(number_format($zrtotal,2));
                $sheet->getCell('F'.$rc)->setValue(number_format($ttotal,2));
                $sheet->getCell('G'.$rc)->setValue(number_format($total_senior_discs,2));
                $sheet->getCell('H'.$rc)->setValue(number_format($total_pwd_discs,2));
                $sheet->getCell('I'.$rc)->setValue(number_format($total_regular_discs,2));
                $sheet->getCell('J'.$rc)->setValue(number_format($sale->total_amount,2));
                // $range = 'A'.$rc.':J'.$rc;
                // $sheet->getStyle($range)
                //       ->getNumberFormat()
                //       ->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );

                $rc+=1;

                $t_tsales += $gross;
                $t_vatsales += $vat_sales;
                $t_vatexempt += $nttotal;
                $t_zerorated += $zrtotal;
                $t_vat += $ttotal;
                $t_senior += $total_senior_discs;
                $t_pwd += $total_pwd_discs;
                $t_emp += $total_regular_discs;
                $t_netsales += $sale->total_amount;
            }    

            $sheet->getStyle("A".$rc.":J".$rc)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('A'.$rc.':'.'J'.$rc)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->getCell('A'.$rc)->setValue('');
            $sheet->getCell('B'.$rc)->setValue(number_format($t_tsales,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($t_vatsales,2));
            $sheet->getCell('D'.$rc)->setValue(number_format($t_vatexempt,2));
            $sheet->getCell('E'.$rc)->setValue(number_format($t_zerorated,2));
            $sheet->getCell('F'.$rc)->setValue(number_format($t_vat,2));
            $sheet->getCell('G'.$rc)->setValue(number_format($t_senior,2));
            $sheet->getCell('H'.$rc)->setValue(number_format($t_pwd,2));
            $sheet->getCell('I'.$rc)->setValue(number_format($t_emp,2));
            $sheet->getCell('J'.$rc)->setValue(number_format($t_netsales,2));

            if (ob_get_contents()) 
                ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
        }
        public function monthly_sales_rep_excel(){
            $this->load->library('Excel');
            $sheet = $this->excel->getActiveSheet();
            $userdata = $this->session->userdata('user');
            $month = $_GET['month'];
            //$month = 11;
            //echo $month."aaaaaaa";
            //return false;

            $time = $this->site_model->get_db_now();
            $year = date('Y',strtotime($time));
            $date_from = $year."-".$month."-01";
            $date_to = date('Y-m-t',strtotime($date_from));
            $title_name = "Monthly Sales Report";

            $rc = 1;
            $filename='Daily Sales Report';

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
                    'email' => $bv->email,
                    'website' => $bv->website,
                    'store_open' => $bv->store_open,
                    'store_close' => $bv->store_close,
                );
            }

            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue($branch['name']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue($branch['address']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('TIN: '.$branch['tin']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('ACCRDN: '.$branch['accrdn']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('PERMIT: '.$branch['permit_no']);
            // $rc++;
            // $sheet->mergeCells('A'.$rc.':P'.$rc);
            // $sheet->getCell('A'.$rc)->setValue('SN #'.$branch['serial']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('MIN: '.$branch['machine_no']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('MONTHLY SALES REPORT');
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue(date('m/d/Y',strtotime($date_from))." to ".date('m/d/Y',strtotime($date_to)));
            // $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->setBold(true);
            // $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->getColor()->setRGB('FF0000');
            


            $rc++;
            $sheet->getStyle("A".$rc.":P11")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$rc.':'.'P11')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->getStyle('A'.$rc.':'.'P11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('A'.$rc.':'.'P11')->getFill()->getStartColor()->setRGB('29bb04');
            $sheet->getStyle('A1:'.'P11')->getFont()->setBold(true);

            $sheet->getCell('A'.$rc)->setValue('Day');
            $sheet->mergeCells('A'.$rc.':A'.($rc+1));
            $sheet->getCell('B'.$rc)->setValue('Accumulating OR');
            $sheet->mergeCells('B'.$rc.':D'.$rc);
            $sheet->getCell('B'.($rc+1))->setValue('Beg');
            $sheet->getCell('C'.($rc+1))->setValue('End');
            $sheet->getCell('D'.($rc+1))->setValue('Total');
            $sheet->getCell('E'.$rc)->setValue('Accumulating Sales');
            $sheet->mergeCells('E'.$rc.':G'.$rc);
            $sheet->getCell('E'.($rc+1))->setValue('Beg');
            $sheet->getCell('F'.($rc+1))->setValue('End');
            $sheet->getCell('G'.($rc+1))->setValue('Total');
            $sheet->getCell('H'.$rc)->setValue('Z-Read Counter');
            $sheet->mergeCells('H'.$rc.':H'.($rc+1));
            $sheet->getCell('I'.$rc)->setValue('VATable Sales');
            $sheet->mergeCells('I'.$rc.':I'.($rc+1));
            $sheet->getCell('J'.$rc)->setValue('VAT-Exempt Sales');
            $sheet->mergeCells('J'.$rc.':J'.($rc+1));
            $sheet->getCell('K'.$rc)->setValue('Zero Rated Sales');
            $sheet->mergeCells('K'.$rc.':K'.($rc+1));
            $sheet->getCell('L'.$rc)->setValue('VAT 12%');
            $sheet->mergeCells('L'.$rc.':L'.($rc+1));
            $sheet->getCell('M'.$rc)->setValue('Discount');
            $sheet->mergeCells('M'.$rc.':O'.$rc);
            $sheet->getCell('M'.($rc+1))->setValue('Senior Citizen');
            $sheet->getCell('N'.($rc+1))->setValue('PWD');
            $sheet->getCell('O'.($rc+1))->setValue('Employee');
            $sheet->getCell('P'.$rc)->setValue('Net Sales');
            $sheet->mergeCells('P'.$rc.':P'.($rc+1));

            $rc++;
            $rc++;

            $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            // if(count($trans_sales) == 0){
            //     $print_str .= "\r\n".align_center('NO SALES FOUND.',38," ")."\r\n\r\n";
            //     if (!$asJson) {
            //         // echo "<pre style='background-color:#fff'>$print_str</pre>";
            //         echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
            //     }
            //     else{
            //         $filename = "report.txt";
            //         $fp = fopen($filename, "w+");
            //         fwrite($fp,$print_str);
            //         fclose($fp);

            //         $batfile = "print.bat";
            //         $fh1 = fopen($batfile,'w+');
            //         $root = dirname(BASEPATH);

            //         fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
            //         fclose($fh1);
            //         session_write_close();
            //         // exec($filename);
            //         exec($batfile);
            //         session_start();
            //         unlink($filename);
            //         unlink($batfile);  
            //     }
            //     return false; 
            // }
            //$print_str .= "\r\n";
            $b4last = date('t',strtotime($year."-".($month-1)."01"));
            $beforeDate = $year."-".($month-1)."-".$b4last;
            $lastRead = $this->cashier_model->get_last_z_read_on_date(Z_READ,date2Sql($beforeDate));
            $old_gt_amnt = 0;
            $grand_total = 0;
            if(count($lastRead) > 0){
                foreach ($lastRead as $res) {
                    $old_gt_amnt = $res->old_total;
                    $grand_total = $res->grand_total;
                    break;
                }           
            }
            $range = createDateRangeArray($date_from,$date_to);
            $orders = array();
            foreach ($range as $dt) {
                foreach ($trans_sales as $sale) {
                    if(date2Sql($sale->datetime) == $dt){
                        if($sale->type_id == 10 && $sale->trans_ref != "" && $sale->inactive == 0){
                            $orders[$dt][$sale->sales_id] = $sale;
                        }
                    }

                }
            }  
            $totals = array(
                'Total VATable' => 0,
                'Total VAT-Exempt' => 0,
                'Total Zero Rated' => 0,
                'Total VAT' => 0,
                'Total Regular Disc' => 0,
                'Total Senior Citizen Disc' => 0,
                'Total PWD Disc' => 0,
                'Total Net Sales' => 0,
            );

            foreach ($range as $dt) {

                $sheet->getStyle("A".$rc.":P".$rc)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('A'.$rc.':'.'P'.$rc)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


                if(isset($orders[$dt]) && count($orders[$dt]) > 0){
                    $ords = $orders[$dt];
                        //$print_str .= align_center($dt,38," ")."\r\n";
                        //ksort($ords);
                        $first = array_shift(array_slice($ords, 0, 1));
                        $last = end($ords);
                        $ref_ctr = count($ords);
                        //$print_str .= align_center($first->trans_ref." - ".$last->trans_ref."(".$ref_ctr.")",38," ")."\r\n";
                        $sheet->getCell('A'.$rc)->setValue(date('d',strtotime($dt)));
                        $sheet->setCellValueExplicit('B'.$rc, $last->trans_ref, PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet->setCellValueExplicit('C'.$rc, $first->trans_ref, PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet->getCell('D'.$rc)->setValue($ref_ctr);
                        $gross_ids = array();
                        $gross = 0;
                        foreach ($ords as $sales_id => $sl) {
                            $gross_ids[] = $sl->sales_id;
                            $gross += $sl->total_amount;
                        }
                        #DISOUNTS 
                            $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$gross_ids));
                            $total_disc = 0;
                            $disc_codes = array();
                            foreach ($sales_discs as $discs) {
                                if(!isset($disc_codes[$discs->disc_code])){
                                    $disc_codes[$discs->disc_code] = array('qty'=> 1,'amount'=>$discs->amount);
                                }
                                else{
                                    $disc_codes[$discs->disc_code]['qty'] += 1;
                                    $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                                }
                                $total_disc += $discs->amount;
                            }
                        #GROSS    
                            $total_regular_discs = 0;
                            $reg_disc_ctr = 0;
                            foreach ($disc_codes as $code => $dc) {
                                if($code != "SNDISC" && $code != "PWDISC"){
                                    $total_regular_discs += $dc['amount'];
                                    $reg_disc_ctr += $dc['qty'];                                    
                                }
                            }
                            $total_regular_discs = numInt($total_regular_discs);
                            //SENIOR
                            $total_senior_discs = 0;
                            $seni_disc_ctr = 0;
                            foreach ($disc_codes as $code => $dc) {
                                if($code == "SNDISC"){
                                    $total_senior_discs += $dc['amount'];
                                    $seni_disc_ctr += $dc['qty'];                                    
                                }
                            }
                            $total_senior_discs = numInt($total_senior_discs);
                            //PWD
                            $total_pwd_discs = 0;
                            $pwd_disc_ctr = 0;
                            foreach ($disc_codes as $code => $dc) {
                                if($code == "PWDISC"){
                                    $total_pwd_discs += $dc['amount'];
                                    $pwd_disc_ctr += $dc['qty'];                                    
                                }
                            }
                            $total_pwd_discs = numInt($total_pwd_discs);
                            //LOCAL TAX
                            $localTax=0;
                            $net = $gross;
                            $gross += ($total_regular_discs+$total_senior_discs+$total_pwd_discs+$localTax);
                            $gross = numInt($gross);

                        //    $print_str .= append_chars(substrwords('Gross Sales: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars($gross,"left",13," ")."\r\n"; 
                        //     $print_str .= append_chars(substrwords('Regular Discounts: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars($total_regular_discs,"left",13," ")."\r\n"; 
                        //     $print_str .= append_chars(substrwords('Senior Discounts: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars($total_senior_discs,"left",13," ")."\r\n"; 
                        //     $print_str .= append_chars(substrwords('PWD Discounts: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars($total_pwd_discs,"left",13," ")."\r\n"; 
                        //     $print_str .= append_chars(substrwords('Net Sales: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars($net,"left",13," ")."\r\n";                                                        
                        // $print_str .= "\r\n";


                            #TAX
                            $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$gross_ids,"trans_sales_zero_rated.amount >"=>0));
                            // echo $this->cashier_model->db->last_query();
                            // echo var_dump($zero_rated);
                            $zrctr = 0;
                            $zrtotal = 0;
                            foreach ($zero_rated as $zt) {
                                $zrtotal += $zt->amount;
                                $zrctr++;
                            }
                            $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$gross_ids,"trans_sales_no_tax.amount >"=>0));
                            $ntctr = 0;
                            $nttotal = 0;
                            foreach ($no_tax as $nt) {
                                $nttotal += $nt->amount;
                                $ntctr++;
                            }
                            $tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$gross_ids,"trans_sales_tax.amount >"=>0));
                            $tctr = 0;
                            $ttotal = 0;
                            foreach ($tax as $t) {
                                $ttotal += $t->amount;
                                $tctr++;
                            }
                            $vat = ((($gross -$total_senior_discs-$total_pwd_discs-$nttotal)/1.12)*0.12);
                            $vat = $net-$nttotal-$ttotal;
                            $nttotal = $nttotal-$zrtotal;

                            $sheet->getCell('G'.$rc)->setValue($gross);

                            $lastRead = $this->cashier_model->get_lastest_z_read(Z_READ,date2Sql($dt));
                            $lastOLDGT=0;
                            $lastNEWGT=0;
                            $lastGT_ctr=0;
                            if(count($lastRead) > 0){
                                foreach ($lastRead as $res) {
                                    // $lastOLDGT = $res->old_total;
                                    $lastNEWGT = $res->grand_total;
                                    $lastGT_ctr++;
                                }           
                            }

                            $sheet->getCell('H'.$rc)->setValue($lastGT_ctr+1);
                            $sheet->getCell('I'.$rc)->setValue(numInt($vat));
                            $sheet->getCell('J'.$rc)->setValue(numInt($nttotal));
                            $sheet->getCell('K'.$rc)->setValue(numInt($zrtotal));
                            $sheet->getCell('L'.$rc)->setValue(numInt($ttotal));
                            $sheet->getCell('M'.$rc)->setValue($total_senior_discs);
                            $sheet->getCell('N'.$rc)->setValue($total_pwd_discs);
                            $sheet->getCell('O'.$rc)->setValue($total_regular_discs);
                            $sheet->getCell('P'.$rc)->setValue($net);


                        //     $print_str .= append_chars(substrwords('VAT Exempt Sales: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars(numInt($nttotal),"left",13," ")."\r\n";
                        //     $print_str .= append_chars(substrwords('VAT Sales: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars(numInt($vat),"left",13," ")."\r\n";               
                        //     $print_str .= append_chars(substrwords('VAT: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars(numInt($ttotal),"left",13," ")."\r\n";  
                        //     $print_str .= append_chars(substrwords('Zero Rated: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars(numInt($zrtotal),"left",13," ")."\r\n";               
                        // $print_str .= "\r\n";
                        #PAYMENT DETAILS
                        $payments = $this->cashier_model->get_trans_sales_payments_group(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                            $pays = array();
                            foreach ($payments as $py) {
                                if(!isset($pays[$py->payment_type])){
                                    $pays[$py->payment_type] = array('qty'=>$py->count,'amount'=>$py->total_paid);
                                }
                                else{
                                    $pays[$py->payment_type]['qty'] += $py->count;
                                    $pays[$py->payment_type]['amount'] += $py->total_paid;
                                }
                            }
                            // $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                            //              .append_chars(null,"left",13," ")."\r\n"; 
                            $pay_total = 0;
                            $pay_qty = 0;
                            foreach ($pays as $type => $pay) {
                                // $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                                //              .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                                $pay_total += $pay['amount'];
                                $pay_qty += $pay['qty'];
                            }
                        //     $print_str .= "-----------------"."\r\n";
                        //     $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                        //                  .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";    
                        // $print_str .= "\r\n";
                        #READINGS
                            // $print_str .= "======================================"."\r\n";
                            // $print_str .= append_chars(substrwords('OLD GT',18,""),"right",18," ").align_center(null,5," ")
                            //              .append_chars(numInt($grand_total),"left",13," ")."\r\n";  
                            $sheet->getCell('E'.$rc)->setValue($grand_total);
                            $grand_total += $net;                                        
                            $sheet->getCell('F'.$rc)->setValue($grand_total);
                            // $print_str .= append_chars(substrwords('NEW GT',18,""),"right",18," ").align_center(null,5," ")
                            //              .append_chars(numInt($grand_total),"left",13," ")."\r\n";
                            // $print_str .= "======================================"."\r\n";   
                        $vat_sales = (($sale->total_amount - $ttotal) - $nttotal) - $zrtotal;
                        if($vat_sales < 0){
                            $vat_sales = 0;
                        }

                        // $print_str .= "--------------------------------------"."\r\n";
                        // $print_str .= "\r\n";
                        $totals['Total VATable'] += $vat;
                        // echo $totals['Total VATable']."--";
                        $totals['Total VAT-Exempt'] += $nttotal;
                        $totals['Total Zero Rated'] += $zrtotal;
                        $totals['Total VAT'] += $ttotal;
                        $totals['Total Regular Disc'] += $total_regular_discs;
                        $totals['Total Senior Citizen Disc'] += $total_senior_discs;
                        $totals['Total PWD Disc'] += $total_pwd_discs;
                        $totals['Total Net Sales'] += $net;


                        //     $print_str .= append_chars(substrwords('Gross Sales: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars($gross,"left",13," ")."\r\n"; 
                        //     $print_str .= append_chars(substrwords('Regular Discounts: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars($total_regular_discs,"left",13," ")."\r\n"; 
                        //     $print_str .= append_chars(substrwords('Senior Discounts: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars($total_senior_discs,"left",13," ")."\r\n"; 
                        //     $print_str .= append_chars(substrwords('PWD Discounts: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars($total_pwd_discs,"left",13," ")."\r\n"; 
                        //     $print_str .= append_chars(substrwords('Net Sales: ',18,""),"right",18," ").align_center('',5," ")
                        //                  .append_chars($net,"left",13," ")."\r\n";                                                        
                        // $print_str .= "\r\n";


                }else{
                    $sheet->getCell('A'.$rc)->setValue(date('d',strtotime($dt)));
                    // $sheet->setCellValueExplicit('B'.$rc, '', PHPExcel_Cell_DataType::TYPE_STRING);
                    // $sheet->setCellValueExplicit('C'.$rc,'', PHPExcel_Cell_DataType::TYPE_STRING);
                    // $sheet->getCell('D'.$rc)->setValue('');
                }

                $rc++;

            }

            $sheet->getStyle("A".$rc.":P".$rc)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('A'.$rc.':'.'P'.$rc)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $sheet->getCell('I'.$rc)->setValue($totals['Total VATable']);
            $sheet->getCell('J'.$rc)->setValue($totals['Total VAT-Exempt']);
            $sheet->getCell('K'.$rc)->setValue($totals['Total Zero Rated']);
            $sheet->getCell('L'.$rc)->setValue($totals['Total VAT']);
            $sheet->getCell('M'.$rc)->setValue($totals['Total Senior Citizen Disc']);
            $sheet->getCell('N'.$rc)->setValue($totals['Total PWD Disc']);
            $sheet->getCell('O'.$rc)->setValue($totals['Total Regular Disc']);
            $sheet->getCell('P'.$rc)->setValue($totals['Total Net Sales']);



            // $no_days = date('t',strtotime($date_from));

            // for($i=1;$i<=$no_days;$i++){

            //     // $time = $this->site_model->get_db_now();
            //     // $year = date('Y',strtotime($time));
            //     $date_f = $year."-".$month."-".$i." 00:00:00";
            //     $date_t = $year."-".$month."-".$i." 23:59:59";

            //     $args["trans_sales.datetime  BETWEEN '".$date_f."' AND '".$date_t."'"] = array('use'=>'where','val'=>null,'third'=>false);
            //     $trans_sales = $this->cashier_model->get_trans_sales_daily(null,$args);
            //     $trans_sales_2 = $this->cashier_model->get_trans_sales(null,$args);

            //     // echo $date_f."----".$date_t."<br>\n";
            //     // echo $this->db->last_query()."\n";

            //     $sheet->getCell('A'.$rc)->setValue($i);

            //     //if(count($trans_sales) > 0){

            //         foreach ($trans_sales as $sale) {
            //             $sheet->setCellValueExplicit('B'.$rc, $sale->min_ref, PHPExcel_Cell_DataType::TYPE_STRING);
            //             $sheet->setCellValueExplicit('C'.$rc, $sale->max_ref, PHPExcel_Cell_DataType::TYPE_STRING);
            //             $sheet->getCell('D'.$rc)->setValue(count($trans_sales_2));
            //         }

            //     // }else{


            //     //     $sheet->getCell('D'.$rc)->setValue('aaaaa');
                      
            //     // }
                

            //     $rc++;
            //     $args = array();
            // }


            //$sheet->getCell('J'.$rc)->setValue($no_days);
            //echo $no_days;
           if (ob_get_contents()) 
                ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$title_name.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
        }
        public function activity_logs_ui(){
            $this->load->model('dine/reports_model');
            $this->load->helper('dine/reports_helper');
            $data = $this->syter->spawn('act_logs');
            $data['page_title'] = 'Activity Logs';
            $now = $this->site_model->get_db_now();
            $args["Date(logs.datetime) = Date('".date2Sql($now)."')"] = array('use'=>'where','val'=>null,'third'=>false);
            $logs = $this->reports_model->get_logs(null,$args);
            $data['code'] = activity_logs_pg($logs);
            // $data['add_js'] = array('js/plugins/timepicker/bootstrap-timepicker.min.js');
            // $data['add_css'] = array('css/timepicker/bootstrap-timepicker.min.css');
            $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/reports.php';
            $data['use_js'] = 'actLogsJs';
            $this->load->view('page',$data);
        }   
        public function activity_logs_rep_excel(){
            $this->load->library('Excel');
            $sheet = $this->excel->getActiveSheet();
            $branch_details = $this->setup_model->get_branch_details();
            $branch = array();
            $rc=1;
            $title_name = "Activity Logs";
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
                    'email' => $bv->email,
                    'website' => $bv->website,
                    'store_open' => $bv->store_open,
                    'store_close' => $bv->store_close,
                );
            }
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue($branch['name']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue($branch['address']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('TIN #'.$branch['tin']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('ACCRDN #'.$branch['accrdn']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('PERMIT #'.$branch['permit_no']);
            // $rc++;
            // $sheet->mergeCells('A'.$rc.':P'.$rc);
            // $sheet->getCell('A'.$rc)->setValue('SN #'.$branch['serial']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('MIN #'.$branch['machine_no']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue($title_name);

            $rc++;
            $rc++;
            $sheet->getStyle("A".$rc.":D".$rc)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$rc.':'.'D'.$rc)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->getStyle('A'.$rc.':'.'D'.$rc)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('A'.$rc.':'.'D'.$rc)->getFill()->getStartColor()->setRGB('29bb04');
            $sheet->getStyle('A1:'.'D'.$rc)->getFont()->setBold(true);

            $sheet->getCell('A'.$rc)->setValue('Datetime');
            $sheet->getCell('B'.$rc)->setValue('Type');
            $sheet->getCell('C'.$rc)->setValue('User');
            $sheet->getCell('D'.$rc)->setValue('Action');
            
            $rc++;
            #DETAILS
                $date_from = null;
                $date_to = null;
                $user = null;
                if(isset($_GET['daterange']) && $_GET['daterange'] != ""){
                    $dates = explode(" to ",$_GET['daterange']);
                    $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
                    $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
                }
                if(isset($_GET['user']) && $_GET['user'] != ""){
                    $user = $_GET['user'];
                }
                if($date_from != "" && $date_to != "")
                    $args["logs.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                if($user != "")
                    $args["logs.user_id"] = $user;

                $logs = $this->reports_model->get_logs(null,$args);
                foreach ($logs as $res) {
                    // $sheet->getStyle("A".$rc.":D".$rc)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    // $sheet->getStyle('A'.$rc.':'.'D'.$rc)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $sheet->getCell('A'.$rc)->setValue($res->datetime);
                    $sheet->getCell('B'.$rc)->setValue($res->type);
                    $sheet->getCell('C'.$rc)->setValue($res->fname." ".$res->mname." ".$res->lname." ".$res->suffix);
                    $sheet->getCell('D'.$rc)->setValue($res->action);
                    $rc++;
                }
            

            ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$title_name.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
        } 
    //#####
    //BACKEND REPORTS
    //#####
        public function sales_rep_ui(){
            $this->load->model('dine/reports_model');
            $this->load->helper('dine/reports_helper');
            $data = $this->syter->spawn('act_sales');
            $data['page_title'] = 'Sales Report';
            $data['code'] = salesRepUI();
            $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/reports.php';
            $data['use_js'] = 'salesRepJs';
            $this->load->view('page',$data);
        } 
        public function sales_rep($asJson=false){
            $print_str = $this->rep_header();
            $userdata = $this->session->userdata('user');
            $date = $this->input->post('daterange');
            $dates = explode(" to ",$date);
            $date_from = $dates[0];            
            $date_to = $dates[1];            

            $args = array();
            $terminal = $this->input->post('terminal');
            $cashier = $this->input->post('cashier');
            if(!empty($cashier)){
                $server = $this->manager_model->get_server_details($cashier);
                $cashier_name = $server[0]->fname.' '.$server[0]->lname;
                $args['trans_sales.user_id'] = $cashier;
            }else{
                $cashier_name = 'All Cashier';
            }
            $terminal = TERMINAL_ID;
            if(!empty($terminal)){
                $terminal_name = $terminal;
                $args['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }

            $time = $this->site_model->get_db_now();
            $title_name = "Sales Report";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= append_chars(substrwords('Employee',18,""),"right",17," ")
                         .append_chars($cashier_name,"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('PC',18,""),"right",17," ")
                         .append_chars($terminal_name,"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Date From',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_from),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Date To',18,""),"right",17," ")
                         .append_chars(sql2DateTime($date_to),"left",8," ")."\r\n";             
            $print_str .= append_chars(substrwords('Printed on',18,""),"right",17," ")
                         .append_chars(date2SqlDateTime($time),"left",8," ")."\r\n";
            $print_str .= append_chars(substrwords('Printed by',18,""),"right",17," ")
                         .append_chars($userdata['fname'].' '.$userdata['lname'],"left",8," ")."\r\n";
            $print_str .= "======================================"."\r\n";

            $args["date(trans_sales.datetime) BETWEEN '".date('Y-m-d',strtotime($date_from))."' AND '".date('Y-m-d',strtotime($date_to))."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
            // echo $this->cashier_model->db->last_query();
            $orders = array();
            $orders['cancel'] = array(); 
            $orders['sale'] = array();
            $orders['void'] = array();
            $gross = 0;
            $gross_ids = array();
            $paid = 0;
            $paid_ctr = 0;
            $types = array();

            $old_gt_amnt = 0;
            $grand_total = 0;


            $day_before = date('Y-m-d', strtotime('-1 day', strtotime($date_from)));

            $resultx = $this->cashier_model->get_last_z_read(Z_READ,date2Sql($day_before));
            foreach ($resultx as $res) {
                $old_gt_amnt = $res->old_total;
                $grand_total = $res->grand_total;
                break;
            }

            foreach ($trans_sales as $sale) {
                if($sale->type_id == 10){
                    if($sale->trans_ref != "" && $sale->inactive == 0){
                        $orders['sale'][$sale->sales_id] = $sale;
                        $gross += $sale->total_amount;
                        $gross_ids[] = $sale->sales_id;
                        if($sale->total_paid > 0){
                            $paid += $sale->total_paid;
                            $paid_ctr ++;                   
                        }
                        $types[$sale->type][$sale->sales_id] = $sale;
                    }
                    else if($sale->trans_ref == "" && $sale->inactive == 1){
                        $orders['cancel'][$sale->sales_id] = $sale;
                    }
                }
                else{
                    $orders['void'][$sale->sales_id] = $sale;
                    // $gross += $sale->total_amount;
                    // $gross_ids[] = $sale->sales_id;
                }


                // $resultx = $this->cashier_model->get_last_z_read(Z_READ,date2Sql($sale->datetime));
                // echo $this->cashier_model->db->last_query();
                // echo var_dump($resultx);
                // return false;
                // $old_gt_amnt1 = 0;
                // $grand_total1 = 0;
                // foreach ($resultx as $res) {
                //     $old_gt_amnt1 = $res->old_total;
                //     $grand_total1 = $res->grand_total;
                //     break;
                // }

                // $old_gt_amnt += $old_gt_amnt1;
                // $grand_total += $grand_total1;

            }
            $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$gross_ids));
            $total_disc = 0;
            $disc_codes = array();
            foreach ($sales_discs as $discs) {
                if(!isset($disc_codes[$discs->disc_code])){
                    $disc_codes[$discs->disc_code] = array('qty'=> 1,'amount'=>$discs->amount);
                }
                else{
                    $disc_codes[$discs->disc_code]['qty'] += 1;
                    $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                }
                $total_disc += $discs->amount;
            }
            #GROSS SALES
                $vat = $gross * .12;
                $net_sales = $gross - $vat;
                $print_str .= append_chars(substrwords('Net Sales',18,""),"right",23," ")
                             .append_chars(number_format($net_sales,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('12% VAT',18,""),"right",23," ")
                             .append_chars(number_format($vat,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Local Tax',18,""),"right",23," ")
                             .append_chars(number_format(0,2),"left",13," ")."\r\n";
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Gross Sales',18,""),"right",23," ")
                             .append_chars(number_format($gross,2),"left",13," ")."\r\n\r\n";             
                $print_str .= append_chars(substrwords('Gross W/ Disc',18,""),"right",23," ")
                             .append_chars(number_format($gross+$total_disc,2),"left",13," ")."\r\n\r\n";
            #CASH DRAWER
                /*$entries_args["shift_entries.trans_date  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);             
                $shift_entries = $this->cashier_model->get_trans_sales_entries(null,$entries_args);
                $deposit = 0;
                $withdraw = 0;
                $dep_ctr = 0;
                foreach ($shift_entries as $ent) {
                    if($ent->amount > 0){
                        $deposit += $ent->amount;
                        $dep_ctr++;
                    }
                    else
                        $withdraw += abs($ent->amount);
                }
                $print_str .= append_chars(substrwords('Carry Over',18,""),"right",18," ").align_center(0,5," ")
                             .append_chars(number_format(0,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Deposit Total',18,""),"right",18," ").align_center($dep_ctr,5," ")
                             .append_chars(number_format($deposit,2),"left",13," ")."\r\n";             
                $print_str .= append_chars(substrwords('Begun Total',18,""),"right",18," ").align_center($paid_ctr,5," ")
                             .append_chars(number_format($paid,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Paid Total',18,""),"right",18," ").align_center($paid_ctr,5," ")
                             .append_chars(number_format($paid,2),"left",13," ")."\r\n";                          
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Outstanding',18,""),"right",23," ")
                             .append_chars(number_format($paid - $paid,2),"left",13," ")."\r\n\r\n";*/
            #TRANS COUNT
                $types_total = array();
                $guestCount = 0;
                foreach ($types as $type => $tp) {
                    foreach ($tp as $id => $opt){
                        if(isset($types_total[$type])){
                            $types_total[$type] += $opt->total_amount;
                        
                        }
                        else{
                            $types_total[$type] = $opt->total_amount;
                        }
                        $guestCount += $opt->guest;
                    }    
                }
                $print_str .= append_chars(substrwords('Trans Count',18,""),"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $tc_total  = 0;
                $tc_qty = 0;
                foreach ($types_total as $typ => $tamnt) {
                    $print_str .= append_chars(substrwords($typ,18,""),"right",18," ").align_center(count($types[$typ]),5," ")
                                 .append_chars(number_format($tamnt,2),"left",13," ")."\r\n";             
                    $tc_total += $tamnt; 
                    $tc_qty += count($types[$typ]);
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('TC Total',18,""),"right",23," ")
                             .append_chars(number_format($tc_total,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Guest Total',18,""),"right",23," ")
                             .append_chars(number_format($guestCount,2),"left",13," ")."\r\n";
                if($tc_total == 0 || $tc_qty == 0)
                    $avg = 0;
                else
                    $avg = $tc_total/$tc_qty;             
                $print_str .= append_chars(substrwords('Average Check',18,""),"right",23," ")
                             .append_chars(number_format($avg,2),"left",13," ")."\r\n\r\n";             
            #GUEST COUNT
                /*$print_str .= append_chars(substrwords('Guest Count',18,""),"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                foreach ($types_total as $typ => $tamnt) {
                    $print_str .= append_chars(substrwords($typ,18,""),"right",18," ").align_center(0,5," ")
                                 .append_chars(number_format(0,2),"left",13," ")."\r\n";             
                }             
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Average Cover',18,""),"right",23," ")
                             .append_chars(number_format(0,2),"left",13," ")."\r\n\r\n";*/
            #OTHER COLLECTION
                /*$print_str .= append_chars(substrwords('Other Collection',18,""),"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Tips',18,""),"right",18," ").align_center(0,5," ")
                             .append_chars(number_format(0,2),"left",13," ")."\r\n";             
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Collection',18,""),"right",23," ")
                             .append_chars(number_format(0,2),"left",13," ")."\r\n\r\n";*/
            #MAJOR CATEGORIES
                $print_str .= append_chars(substrwords('Major Categories',18,""),"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $categories = $this->menu_model->get_menu_categories(null);

                $menu_cat_sales = $this->cashier_model->get_trans_sales_categories(null,array("trans_sales_menus.sales_id"=>$gross_ids));
                $menu_cat_sale_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$gross_ids));
                $cats = array();
                foreach ($menu_cat_sales as $cat){
                    $cost = $cat->price;
                    foreach ($menu_cat_sale_mods as $cod) {
                        if($cat->sales_id == $cod->sales_id && $cod->line_id == $cat->line_id){
                            $cost += $cod->price;
                        }
                    }
                    $cost = $cost * $cat->qty;
                    foreach ($sales_discs as $cis){
                        if($cis->sales_id == $cat->sales_id){
                            $rate = $cis->disc_rate;
                            switch ($cis->type) {
                                // case "item":
                                //          $items = explode(',',$cis->items);
                                //          foreach ($items as $lid) {
                                //              if($cat->line_id == $lid){
                                //                  $discount = ($rate / 100) * $cost;
                                //                  $cost -= $discount;
                                //              }
                                //          }
                                //          break;
                                case "equal":
                                         $divi = $cost/$cis->guest;
                                         if($cis->no_tax == 1)
                                             $divi = ($divi / 1.12);
                                         $discount = ($rate / 100) * $divi;
                                         $cost = ($divi * $cis->guest) - $discount;
                                         break;
                                default:
                                     if($cis->no_tax == 1)
                                         $cost = ($cost / 1.12);                     
                                     $discount = ($rate / 100) * $cost;
                                     $cost -= $discount;  
                            }                           
                        }
                    }
                    if(!isset($cats[$cat->menu_cat_id])){
                        $cats[$cat->menu_cat_id] = array(
                            "cat_name"=>$cat->menu_cat_name,
                            "amount"=>$cost,
                            "qty"=>$cat->qty
                        );                      
                    }
                    else{
                        $cats[$cat->menu_cat_id]['amount'] += $cost;
                        $cats[$cat->menu_cat_id]['qty'] += $cat->qty;
                    }
                }
                $cat_totals = 0;
                $cat_qtys = 0;
                foreach ($categories as $opt) {
                    if(isset($cats[$opt->menu_cat_id])){
                        $cate = $cats[$opt->menu_cat_id];
                        $print_str .= append_chars(substrwords($cate['cat_name'],18,""),"right",18," ").align_center($cate['qty'],5," ")
                                     .append_chars(number_format($cate['amount'],2),"left",13," ")."\r\n";
                        $cat_totals += $cate['amount'];
                        $cat_qtys += $cate['qty'];
                    }
                    else{
                        $print_str .= append_chars(substrwords($opt->menu_cat_name,18,""),"right",18," ").align_center(0,5," ")
                                     .append_chars(number_format(0,2),"left",13," ")."\r\n";                    
                    }
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total',18,""),"right",18," ").align_center($cat_qtys,5," ")
                             .append_chars(number_format($cat_totals,2),"left",13," ")."\r\n\r\n";              
            #CASH LOANDS ETC.
                /*$print_str .= append_chars(substrwords('Loan',18,""),"right",18," ").align_center(0,5," ")
                             .append_chars(number_format(0,2),"left",13," ")."\r\n";             
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Cash Sales',18,""),"right",18," ").align_center(count($gross_ids),5," ")
                             .append_chars(number_format($gross,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Pickup',18,""),"right",18," ").align_center(0,5," ")
                             .append_chars(number_format(0,2),"left",13," ")."\r\n";             
                $print_str .= append_chars(substrwords('Cash In Drawer',18,""),"right",18," ").align_center(count($gross_ids),5," ")
                             .append_chars(number_format($gross,2),"left",13," ")."\r\n\r\n";*/
            #PAYMENT DETAILS
                // $payments = $this->cashier_model->get_trans_sales_payments_group(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                // $pays = array();
                // foreach ($payments as $py) {
                //     if(!isset($pays[$py->payment_type])){
                //         $pays[$py->payment_type] = array('qty'=>$py->count,'amount'=>$py->total_paid);
                //     }
                //     else{
                //         $pays[$py->payment_type]['qty'] += $py->count;
                //         $pays[$py->payment_type]['amount'] += $py->total_paid;
                //     }
                // }
                // $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                //              .append_chars(null,"left",13," ")."\r\n"; 
                // $pay_total = 0;
                // $pay_qty = 0;
                // foreach ($pays as $type => $pay) {
                //     $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                //                  .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                //     $pay_total += $pay['amount'];
                //     $pay_qty += $pay['qty'];
                // }
                // $print_str .= "-----------------"."\r\n";
                // $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                //              .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";

                $payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$gross_ids));
                $pays = array();
                foreach ($payments as $py) {
                    if($py->amount > $py->to_pay)
                        $amount = $py->to_pay;
                    else
                        $amount = $py->amount;
                    if(!isset($pays[$py->payment_type])){
                        $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                    }
                    else{
                        $pays[$py->payment_type]['qty'] += 1;
                        $pays[$py->payment_type]['amount'] += $amount;
                    }
                }
                $print_str .= append_chars(substrwords('Payment Details',18,""),"right",18," ").align_center(null,5," ")
                         .append_chars(null,"left",13," ")."\r\n"; 
                $pay_total = 0;
                $pay_qty = 0;
                foreach ($pays as $type => $pay) {
                    $print_str .= append_chars(substrwords(strtoupper($type),18,""),"right",18," ").align_center($pay['qty'],5," ")
                                 .append_chars(number_format($pay['amount'],2),"left",13," ")."\r\n";
                    $pay_total += $pay['amount'];
                    $pay_qty += $pay['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                             .append_chars(number_format($pay_total,2),"left",13," ")."\r\n\r\n";             
            #DISCOUNTS DETAILS
                $print_str .= append_chars(substrwords('Discount Details',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(null,"left",13," ")."\r\n"; 
                $disc_amount = 0;
                $disc_qty = 0;
                foreach ($disc_codes as $dodes => $di) {
                    $print_str .= append_chars(substrwords(strtoupper($dodes),18,""),"right",18," ").align_center($di['qty'],5," ")
                                 .append_chars(number_format($di['amount'],2),"left",13," ")."\r\n";
                    $disc_amount += $di['amount'];
                    $disc_qty += $di['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Discount',18,""),"right",18," ").align_center($disc_qty,5," ")
                             .append_chars(number_format($disc_amount,2),"left",13," ")."\r\n";
                $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$gross_ids,"trans_sales_zero_rated.amount >"=>0));
                // echo $this->cashier_model->db->last_query();
                // echo var_dump($zero_rated);
                $zrctr = 0;
                $zrtotal = 0;
                foreach ($zero_rated as $zt) {
                    $zrtotal += $zt->amount;
                    $zrctr++;
                }             
                $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$gross_ids,"trans_sales_no_tax.amount >"=>0));
                $ntctr = 0;
                $nttotal = 0;
                foreach ($no_tax as $nt) {
                    $nttotal += $nt->amount;
                    $ntctr++;
                }             
             #NO TAX DETAILS   
                $print_str .= append_chars(substrwords('VAT EXEMPT',18,""),"right",18," ").align_center(abs($ntctr-$zrctr),5," ")
                             .append_chars(number_format(abs($nttotal-$zrtotal),2),"left",13," ")."\r\n";
             #ZERO RATED DETAILS   
                $print_str .= append_chars(substrwords('VAT ZERO RATED',18,""),"right",18," ").align_center($zrctr,5," ")
                             .append_chars(number_format($zrtotal,2),"left",13," ")."\r\n\r\n";                 
            #TRANS TYPE ANALYS
                $print_str .= append_chars(substrwords('Trans Typ Analys',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(null,"left",13," ")."\r\n";
                $print_str .= "-----------------"."\r\n";
                foreach ($types_total as $typ => $tamnt) {
                    $print_str .= append_chars(substrwords($typ,18,""),"right",18," ").align_center(count($types[$typ]),5," ")
                                 .append_chars(number_format($tamnt,2),"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords($typ." %",18,""),"right",18," ").align_center(0,5," ")
                                 .append_chars(number_format(($tamnt/$tc_total)*100,2),"left",13," ")."\r\n";             
                }                            
                $print_str .= "\r\n";
                $cancel_total = 0;
                foreach ($orders['cancel'] as $cnl) {
                    $cancel_total += $cnl->total_amount;

                }
                $print_str .= append_chars(substrwords('Void Before Subt',18,""),"right",18," ").align_center(count($orders['cancel']),5," ")
                             .append_chars(number_format($cancel_total,2),"left",13," ")."\r\n";
                if($cancel_total == 0 || count($orders['cancel']) == 0)
                    $avg = 0;
                else
                    $avg = $cancel_total/count($orders['cancel']);
                $print_str .= append_chars(substrwords('AVG Void B4 Subt',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(number_format($avg,2),"left",13," ")."\r\n";
                
                $void_total = 0;
                foreach ($orders['void'] as $v) {
                    $void_total += $v->total_amount;
                }            
                $print_str .= append_chars(substrwords('Void After Subt',18,""),"right",18," ").align_center(count($orders['void']),5," ")
                             .append_chars(number_format($void_total,2),"left",13," ")."\r\n";
                if($void_total == 0 || count($orders['void']) == 0)
                    $avg = 0;
                else
                    $avg = $void_total/count($orders['void']);             
                $print_str .= append_chars(substrwords('AVG Void Aft Subt',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(number_format($avg,2),"left",13," ")."\r\n";
                $print_str .= "-----------------"."\r\n\r\n";
            #display voided
                // if(count($orders['cancel']) > 0){
                //     $print_str .= append_chars(substrwords('Voids B4 Subt',18,""),"right",18," ").align_center(null,5," ")
                //                  .append_chars(null,"left",13," ")."\r\n";
                //     $print_str .= "-----------------"."\r\n";
                //     foreach ($orders['cancel'] as $cnl) {
                //         $print_str .= append_chars(substrwords("Order #".$cnl->sales_id,18,""),"right",18," ").align_center('',5," ")
                //                  .append_chars(number_format($cnl->total_amount,2),"left",13," ")."\r\n";
                //         if($cnl->table_name != ""){
                //             $print_str .= append_chars(substrwords("--".$cnl->table_name,18,""),"right",18," ").align_center('',5," ")
                //                      .append_chars('',"left",13," ")."\r\n";
                            
                //         }
                //         $print_str .= "*************"."\r\n";
                //     }
                //     $print_str .= "-----------------"."\r\n";
                // }
                if(count($orders['void']) > 0){
                    $print_str .= append_chars(substrwords('Voids Aft Subt',18,""),"right",18," ").align_center(null,5," ")
                                 .append_chars(null,"left",13," ")."\r\n";
                    $print_str .= "-----------------"."\r\n";
                    foreach ($orders['void'] as $v) {
                        $print_str .= append_chars(substrwords("Ref ".$v->trans_ref,18,""),"right",18," ").align_center('',5," ")
                                 .append_chars(number_format($v->total_amount,2),"left",13," ")."\r\n";
                        if($v->table_name != ""){
                            $print_str .= append_chars(substrwords("--".$v->table_name,18,""),"right",18," ").align_center('',5," ")
                                     .append_chars('',"left",13," ")."\r\n";
                            
                        }
                        $print_str .= "*************"."\r\n";
                    }                
                    $print_str .= "-----------------"."\r\n";
                }
            #GT    
                $print_str .= "================="."\r\n";
                $print_str .= append_chars(substrwords('NEW GT',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(number_format($grand_total,2),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('OLD GT',18,""),"right",18," ").align_center(null,5," ")
                             .append_chars(number_format($old_gt_amnt,2),"left",13," ")."\r\n";                              
                $print_str .= "================="."\r\n";             
            if (!$asJson) {
                // echo "<pre style='background-color:#fff'>$print_str</pre>";
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
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
            }  
        }
        public function drawer_count_ui(){
            $this->load->model('dine/reports_model');
            $this->load->helper('dine/reports_helper');
            $data = $this->syter->spawn('drawer_count');
            $data['page_title'] = 'Drawer Cash Count';
            $data['code'] = drawerCountUI();
            $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/reports.php';
            $data['use_js'] = 'drawerJs';
            $this->load->view('page',$data);
        }

        public function check_scheds(){
            $this->load->helper('dine/reports_helper');

            $date = $this->input->post('date');
            $user = $this->input->post('user');
            $json = $this->input->post('json');

            if($json == 'true'){
                $asjson = true;
            }else{
                $asjson = false;
            }

            $now = sql2Date($this->site_model->get_db_now('sql'));
            if(strtotime($date) < strtotime($now)){
                $this->db = $this->load->database('main', TRUE);
            }
            $get_shift = $this->clock_model->get_old_shift(date2Sql($date),$user);

            // echo $this->clock_model->db->last_query();
            // var_dump($get_shift);
            if(count($get_shift) > 0){
                // $shift = $get_shift[0]->shift_id;
                $shift_out = $get_shift[0]->cashout_id;
            //     // $count = $this->count_totals(null,false);
            //     //echo $shift_out;
            //     // if($shift_out != null){
            //         //$cash_id = $shift_out;
            //         //echo $shift_out;
                    
            //     // }
            //     // else{
            //     //     $cash_id = $this->clock_model->insert_cashout($items);
            //     // }
                $this->print_drawer_details($shift_out,$date,$user,$asjson);
                
            }
            else{
                $error = "There is no shift found.";
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$error</pre>"));
            }
        }

        public function print_drawer_details($cashout_id,$date,$user,$asJson=false){
            // if (!isset($cashout_id)) {
            //     show_404();
            //     return false;
            // }
            $print_str = "";
            // $this->db = $this->load->database('main',true);
            $cashout_header = $this->cashier_model->get_cashout_header($cashout_id); // returns row
            //echo $cashout_id."-".$date."-".$user;
            // echo $this->cashier_model->db->last_query();
            // var_dump($cashout_header);
            $cashout_details = $this->cashier_model->get_cashout_details($cashout_id); // returns rows array
            $totals = $this->get_over_all_total(false,$date,$user);
            $sum_deps = $sum_withs = 0;

            /* Header */
            $branch = $this->get_branch_details(false);
            $wrap = wordwrap($branch['name'],35,"|#|");
            $exp = explode("|#|", $wrap);
            foreach ($exp as $v) {
                $print_str .= align_center($v,38," ")."\r\n";
            }

            $wrap = wordwrap($branch['address'],35,"|#|");
            $exp = explode("|#|", $wrap);
            foreach ($exp as $v) {
                $print_str .= align_center($v,38," ")."\r\n\r\n";
            }
            $accrdn = "";
            if(isset($branch['accrdn'])){
                $accrdn = $branch['accrdn'];
            }

            $print_str .= 
            align_center('TIN: '.$branch['tin'],38," ")."\r\n"
            .align_center('ACCRDN: '.$accrdn,38," ")."\r\n"
            .align_center('MIN:'.$branch['machine_no'],38," ")."\r\n"
            // .align_center('SN: '.$branch['serial'],38," ")."\r\n"
            .align_center('PERMIT: '.$branch['permit_no'],38," ")."\r\n\r\n";

            $print_str .= align_center("CASHOUT DATA",36)."\r\n\r\n".
                "Cashier  : ".$cashout_header->username."\r\n".
                "Terminal : [".$cashout_header->terminal_code."] ".$cashout_header->terminal_name."\r\n".
                "Time in  : ".$cashout_header->check_in."\r\n";
            if($cashout_header->check_out != null)
                $print_str .= "Time out : ".$cashout_header->check_out."\r\n";

            $print_str .= "\r\n";

            /* Cash Deposits */
            $print_str .= "Cash Deposits\r\n";
            foreach ($totals['total_deps'] as $k => $dep) {
                $print_str .= append_chars("   ".($k+1),'right',8," ").append_chars(date('H:i:s',strtotime($dep->trans_date)),"right",13," ")
                    .append_chars(number_format($dep->amount,2),"left",15," ")."\r\n";
                $sum_deps += $dep->amount;
            }
            if ($sum_deps > 0)
                $print_str .= append_chars("------------","left",36," ")."\r\n";
            $print_str .= append_chars("Total Cash Deposits","right",21," ")
                .append_chars(number_format($sum_deps,2),"left",15," ")."\r\n\r\n";

            /* Cash Withdrawals */
            $print_str .= "Cash Withdrawals\r\n";
            foreach ($totals['total_withs'] as $k => $with) {
                $print_str .= append_chars("   ".($k+1)." ".date('H:i:s',strtotime($with->trans_date)),"right",21," ")
                    .append_chars(number_format(abs($with->amount),2),"left",15," ")."\r\n";
                $sum_withs += abs($with->amount);
            }
            if ($sum_withs > 0)
                $print_str .= append_chars("------------","left",36," ")."\r\n";
            $print_str .= append_chars("Total Cash Withdrawals","right",25," ")
                .append_chars(number_format($sum_withs,2),"left",11," ")."\r\n\r\n";


             // Drawer 
            // $print_str .= append_chars("Expected Drawer amount","right",25," ").append_chars(number_format($cashout_header->drawer_amount,2),"left",11," ")."\r\n";
            // $print_str .= append_chars("Actual Drawer amount","right",25," ").append_chars(number_format($cashout_header->count_amount,2),"left",11," ")."\r\n";
            // $print_str .= append_chars("-------------","right",36," ")."\r\n";
            // $print_str .= append_chars("Variance","right",25," ").append_chars(number_format(abs($cashout_header->drawer_amount - $cashout_header->count_amount),2),"left",11," ")."\r\n\r\n";

            $orders = array();

            foreach ($cashout_details as $value) {

                $orders[$value->type][$value->id] = array('denomination'=>$value->denomination,
                                                    'total'=>$value->total,
                                                    'reference'=>$value->reference,
                                                    'type'=>$value->type,

                );

            }

            //var_dump($orders);

            /* Cashout Details */
            // $print_str .= "\r\n\r\n\r\nCashout Breakdown\r\n";
            // foreach ($cashout_details as $value) {
            //     if (!empty($value->denomination))
            //         $mid = $value->denomination." X ".($value->total/$value->denomination);
            //     elseif (!empty($value->reference))
            //         $mid = $value->reference." ";
            //     else $mid = "";

            //     $print_str .= append_chars("[".ucwords($value->type)."] ".$mid,"right",21," ").
            //         append_chars(number_format($value->total,2),"left",15," ")."\r\n";
            // }



            $print_str .= "\r\n\r\n\r\nCashout Breakdown\r\n";
            foreach ($orders as $keys => $vals) {
                $print_str .= append_chars('['.ucwords($keys).']',"right",21," ")."\r\n";

                foreach ($vals as $k => $value) {
                    //echo $value['total'];
                    if (!empty($value['denomination']))
                        $mid = $value['denomination']." X ".($value['total']/$value['denomination']);
                    elseif (!empty($value['reference']))
                        $mid = $value['reference']." ";
                    else $mid = "";

                    $print_str .= append_chars($mid,"right",21," ").
                    append_chars(number_format($value['total'],2),"left",15," ")."\r\n";
                }
            }

            $print_str .= "\r\n".append_chars("","right",36,"-");
            // echo $print_str;
            if (!$asJson) {
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
            }else{
                $filename = "cashout_report.txt";
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
        public function get_over_all_total($asJson=true,$date,$user){
            // $user = $this->session->userdata('user');
            // $user_id = $user['id'];
            // $date = $this->site_model->get_db_now('sql');
            $get_shift = $this->clock_model->get_old_shift(date2Sql($date),$user);
            $shift = null;
            $total_drops = 0;
            $total_deps = $total_withs = array();
            $total_sales = 0;
            $overAllTotal = 0;
            if(count($get_shift) > 0){
                $shift = $get_shift[0]->shift_id;
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
    public function check_sales($asJson=true){
        $print_str = $this->rep_header();
        $daterange = '2015-06-22 07:59:06 to 2015-06-23 00:32:54';
        $dates = explode(" to ",$daterange);
        // $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
        // $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
        $date_from = $dates[0];
        $date_to = $dates[1];
        $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        $args['type_id'] = 10;
        $args['trans_sales.inactive'] = 0;
        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);

        // $args['trans_sales.user_id'] = 1;
        $this->db = $this->load->database('main', TRUE);
        $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
        echo $this->cashier_model->db->last_query();
        $total = 0;
        $menu_total = 0;
        $menu_qty = 0;
        $menus = array();
        
        $sub_cats = array();
        $sales_menu_cat = $this->menu_model->get_menu_subcategories();
        foreach ($sales_menu_cat as $rc) {
            $sub_cats[$rc->menu_sub_cat_id] = array('name'=>$rc->menu_sub_cat_name,'qty'=>0,'amount'=>0);
        }

        foreach ($trans_sales as $res) {
            $total += $res->total_amount;
            $total_amount = $res->total_amount;
            $select = 'trans_sales_menus.*,
                        menus.menu_name,
                        menu_subcategories.menu_sub_cat_id as sub_cat_id,menu_subcategories.menu_sub_cat_name as sub_cat_name
                       ';
            $join = null;           
            $join['menus'] = array('content'=>'trans_sales_menus.menu_id = menus.menu_id');
            $join['menu_subcategories'] = array('content'=>'menus.menu_sub_cat_id = menu_subcategories.menu_sub_cat_id','mode'=>'left');
            $menu_res = $this->site_model->get_tbl('trans_sales_menus',array('sales_id'=>$res->sales_id),array(),$join,true,$select);
            // echo $res->sales_id." AMOUNT = ".$total_amount."<br>";
            $menTotal = 0;
            $menQty = 0;
            foreach ($menu_res as $mes) {
                // echo "&nbsp;&nbsp;&nbsp;".$mes->menu_id." - ".($mes->qty * $mes->price)."<br>";
                $menTotal += ($mes->qty * $mes->price);
                $menQty += $mes->qty;
                if(!isset($menus[$mes->menu_id])){
                    $menus[$mes->menu_id] = array('name'=>$mes->menu_name,'qty'=>$mes->qty,'amount'=>($mes->qty * $mes->price));                    
                }
                else{
                    $row = $menus[$mes->menu_id];
                    $row['qty'] += $mes->qty;
                    $row['amount'] += ($mes->qty * $mes->price);
                    $menus[$mes->menu_id] = $row;
                }
                if(!isset($sub_cats[$mes->sub_cat_id])){
                    $sub_cats[$mes->sub_cat_id] = array('name'=>$mes->sub_cat_name,'qty'=>$mes->qty,'amount'=>($mes->qty * $mes->price));
                }
                else{
                    $row = $sub_cats[$mes->sub_cat_id];
                    $row['qty'] += $mes->qty;
                    $row['amount'] += ($mes->qty * $mes->price);
                    $sub_cats[$mes->sub_cat_id] = $row;
                }    
            }
            $menu_total += $menTotal;
            $menu_qty += $menQty;
            // echo "&nbsp;&nbsp;&nbsp;MENU TOTAL = ".$menTotal;
            // if($total_amount != $menTotal){
            //     echo " -- FALSE FALSE FALSE <br>";
            // }
            // else
            //     echo " -- TRUE TRUE TRUE <br>";
        }
        $menu_names = array();

        foreach ($menus as $menu_id => $men) {
            $print_str .=
                append_chars($men['name'],'right',18," ")
                .append_chars(num($men['qty']),'right',10," ")
                .append_chars(num( ($men['qty'] / $menu_qty) * 100).'%','left',10," ")."\r\n";
            $print_str .=
                append_chars(null,'right',18," ")
                .append_chars(num($men['amount']),'right',10," ")
                .append_chars(num( ($men['amount'] / $menu_total) * 100).'%','left',10," ")."\r\n";
        }
        $print_str .= "======================================"."\r\n";
        $print_str .= append_chars('Sub Categories',"right",18," ")."\r\n";
        foreach ($sub_cats as $subid => $sc) {
            $print_str .= append_chars($sc['name'],"right",18," ")
                     .append_chars(num($sc['qty']),'right',10," ")
                     .append_chars(num($sc['amount']),"left",10," ")."\r\n";
        }
        $print_str .= "\r\n";
        $print_str .= "======================================"."\r\n";
        $print_str .= append_chars('TOTAL SALES',"right",18," ")
                     .append_chars(num($menu_qty),'right',10," ")
                     .append_chars(num($menu_total),"left",10," ")."\r\n";    
            
        echo "<pre style='background-color:#fff'>$print_str</pre>";
    }
    //######
    // GETTERS   
    //######  
        public function get_taxes($ids=array()){
            $taxes = array();
            $taxes['zero_rated'] = array('qty'=>0,'amount'=>0);
            $taxes['no_tax'] = array('qty'=>0,'amount'=>0);
            $taxes['tax'] = array('qty'=>0,'amount'=>0);
            $taxes['local_tax'] = array('qty'=>0,'amount'=>0);
            if(count($ids) > 0){
                $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$ids,"trans_sales_zero_rated.amount >"=>0));
                $zrctr = 0;
                $zrtotal = 0;
                foreach ($zero_rated as $zt) {
                    $zrtotal += $zt->amount;
                    $zrctr++;
                }
                $taxes['zero_rated']['qty'] = $zrctr;
                $taxes['zero_rated']['amount'] = $zrtotal;

                $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$ids,"trans_sales_no_tax.amount >"=>0));
                $ntctr = 0;
                $nttotal = 0;
                foreach ($no_tax as $nt) {
                    $nttotal += $nt->amount;
                    $ntctr++;
                }
                $taxes['no_tax']['qty'] = $ntctr;
                $taxes['no_tax']['amount'] = $nttotal;

                $tctr = 0;
                $ttotal = 0;
                $tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$ids,"trans_sales_tax.amount >"=>0));
                foreach ($tax as $t) {
                    $ttotal += $t->amount;
                    $tctr++;
                }
                $taxes['tax']['qty'] = $tctr;
                $taxes['tax']['amount'] = $ttotal;

                $targs["trans_sales_local_tax.sales_id"] = $ids;
                $tesults = $this->site_model->get_tbl('trans_sales_local_tax',$targs);   
                $total_local_tax = 0;
                $ltctr=0;
                foreach ($tesults as $tes) {
                    $total_local_tax += $tes->amount;
                    $ltctr++;
                }
                $taxes['local_tax']['qty'] = $ltctr;
                $taxes['local_tax']['amount'] = $total_local_tax;
            }
            return $taxes;
        }    
        public function get_payments($ids=array()){
            $ret = array();
            if(count($ids) > 0){
                $payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$ids));
                $total = 0;
                $pays = array();
                foreach ($payments as $py) {
                    if($py->amount > $py->to_pay)
                        $amount = $py->to_pay;
                    else
                        $amount = $py->amount;
                    if(!isset($pays[$py->payment_type])){
                        $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                    }
                    else{
                        $pays[$py->payment_type]['qty'] += 1;
                        $pays[$py->payment_type]['amount'] += $amount;
                    }
                    $total += $amount;
                }
                $ret['total'] = $total;
                $ret['types'] = $pays;
                return $ret;
            }   
            else{
                return array('total'=>0,'types'=>array());
            }
        }    
        public function get_discounts($ids=array()){
            $discounts = array();
            if(count($ids) > 0){
                $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$ids));
                $total_disc = 0;
                $disc_codes = array();
                foreach ($sales_discs as $discs) {
                    if(!isset($disc_codes[$discs->disc_code])){
                        $disc_codes[$discs->disc_code] = array('name'=>$discs->disc_name,'qty'=> 1,'amount'=>$discs->amount);
                    }
                    else{
                        $disc_codes[$discs->disc_code]['qty'] += 1;
                        $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                    }
                    $total_disc += $discs->amount;
                }
                $discounts['total']=$total_disc;
                $discounts['types']=$disc_codes;
                return $discounts;
            }
            else{
                return array('total'=>0,'types'=>array());
            }
        } 
        public function get_charges($ids=array()){
            if(count($ids) > 0){
                $cargs["trans_sales_charges.sales_id"] = $ids;
                $cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);   
                $total_charges = 0;
                $charges = array();
                foreach ($cesults as $ces) {
                    if(!isset($charges[$ces->charge_id])){
                        $charges[$ces->charge_id] = array(
                            'amount'=>$ces->amount,
                            'name'=>$ces->charge_name,
                            'code'=>$ces->charge_code,
                            'qty'=>1
                        );
                    }
                    else{
                        $ch = $charges[$ces->charge_id];
                        $ch['amount'] += $ces->amount;
                        $ch['qty'] += 1;
                        $charges[$ces->charge_id] = $ch;
                    }
                    $total_charges += $ces->amount;
                } 
                return array('total'=>$total_charges,'types'=>$charges);
            }
            else{
                return array('total'=>0,'types'=>array());
            }
        }

         ///////////////JED START
        public function monthly_sales_ui(){
            $this->load->model('dine/reports_model');
            $this->load->helper('dine/reports_helper');
            $data = $this->syter->spawn('monthly');
            $data['page_title'] = 'BIR Monthly Sales';
            $data['code'] = montlySalesUi();
            $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/reports.php';
            $data['use_js'] = 'monthlyJS';
            $this->load->view('page',$data);
        }
        public function get_monthly_reports(){
            ini_set('memory_limit', '-1');
            set_time_limit(3600);
            sess_clear('month_array');
            sess_clear('month_date');
            $this->load->helper('dine/reports_helper');
            $month = $this->input->post('month');
            $year = $this->input->post('year');
            $json = $this->input->post('json');
            $start_month = date($year.'-'.$month.'-01');
            $end_month = date("Y-m-t", strtotime($start_month));
            $month_date = array('text'=>sql2Date($start_month).' to '.sql2Date($end_month),'month_year'=>$start_month);
            update_load(10);
            // sleep(1);
            $load = 10;

            $details = $this->setup_model->get_branch_details();
                
            $open_time = $details[0]->store_open;
            $close_time = $details[0]->store_close;

                    // echo $cmonth.'<br>';
            $month_array = array();
            while (strtotime($start_month) <= strtotime($end_month)) {
                // echo "$start_month\n";
                $post = $this->set_post(null,$start_month);
                $trans = $this->trans_sales($post['args']);
                $sales = $trans['sales'];
                $trans_menus = $this->menu_sales($sales['settled']['ids']);
                $trans_charges = $this->charges_sales($sales['settled']['ids']);
                $trans_discounts = $this->discounts_sales($sales['settled']['ids']);
                $tax_disc = $trans_discounts['tax_disc_total'];
                $no_tax_disc = $trans_discounts['no_tax_disc_total'];
                $trans_local_tax = $this->local_tax_sales($sales['settled']['ids']);
                $trans_tax = $this->tax_sales($sales['settled']['ids']);
                $trans_no_tax = $this->no_tax_sales($sales['settled']['ids']);
                $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids']);
                // $payments = $this->payment_sales($sales['settled']['ids']);
                $gross = $trans_menus['gross'];
                
                $net = $trans['net'];
                $void = $trans['void'];
                $charges = $trans_charges['total'];
                $discounts = $trans_discounts['total'];
                $local_tax = $trans_local_tax['total'];
                $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
                if($less_vat < 0)
                    $less_vat = 0;
                $tax = $trans_tax['total'];
                $no_tax = $trans_no_tax['total'];
                $zero_rated = $trans_zero_rated['total'];
                $no_tax -= $zero_rated;
                $loc_txt = numInt(($local_tax));
                $net_no_adds = $net-($charges+$local_tax);
                // $nontaxable = $no_tax;
                $nontaxable = $no_tax - $no_tax_disc;
                // $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
                $taxable = ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12;
                $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
                $add_gt = $taxable+$nontaxable+$zero_rated;
                $nsss = $taxable +  $nontaxable +  $zero_rated;
                // $net_sales = $gross + $charges - $discounts - $less_vat;
                //pinapaalis ni sir yun charges sa net sales kagaya nun nasa zread 8 20 2018
                $net_sales = $gross - $discounts - $less_vat;
                // $final_gross = $gross;
                $vat_ = $taxable * .12;
                
                $pos_start = date2SqlDateTime($start_month." ".$open_time);
                $oa = date('a',strtotime($open_time));
                $ca = date('a',strtotime($close_time));
                $pos_end = date2SqlDateTime($start_month." ".$close_time);
                if($oa == $ca){
                    $pos_end = date('Y-m-d H:i:s',strtotime($pos_end . "+1 days"));
                }

                $gt = $this->old_grand_net_total($pos_start);

                $types = $trans_discounts['types'];
                // $qty = 0;
                $sndisc = 0;
                $pwdisc = 0;
                $othdisc = 0;
                foreach ($types as $code => $val) {
                    $amount = $val['amount'];
                    if($code == 'PWDISC'){
                        // $amount = $val['amount'] / 1.12;
                        $pwdisc = $val['amount'];
                    }elseif($code == 'SNDISC'){
                        $sndisc = $val['amount'];
                    }else{
                        $othdisc += $val['amount'];
                    }
                    // $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
                    //                      .append_chars('-'.Num($amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    // $qty += $val['qty'];
                }
                // echo $pwdisc; die();
                $month_array[$start_month] = array(
                    'cr_beg'=>iSetObj($trans['first_ref'],'trans_ref'),
                    'cr_end'=>iSetObj($trans['last_ref'],'trans_ref'),
                    'cr_count'=>$trans['ref_count'],
                    'beg'=>$gt['old_grand_total'],
                    'new'=>$gt['old_grand_total']+$net_no_adds,
                    'ctr'=>$gt['ctr'],
                    'vatsales'=>$taxable,
                    'vatex'=>$nontaxable,
                    'zero_rated'=>$zero_rated,
                    'vat'=>$vat_,
                    'net_sales'=>$net_sales,
                    'pwdisc'=>$pwdisc,
                    'sndisc'=>$sndisc,
                    'othdisc'=>$othdisc,
                    'lessvat'=>$less_vat,
                    'gross'=>$gross,
                    'charges'=>$charges,
                    // 'senior'=>
                );
                $load += 2;
                update_load($load);
                // sleep(1);
                $start_month = date("Y-m-d", strtotime("+1 day", strtotime($start_month)));
            }
            // var_dump($month_array);
            // die();
            // $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
            // $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            update_load(75);
            // sleep(1);
            $this->session->set_userdata('month_array',$month_array);
            $this->session->set_userdata('month_date',$month_date);
            // //diretso excel na
            // $this->load->library('Excel');
            // $sheet = $this->excel->getActiveSheet();
            // $sheet->getCell('A1')->setValue('Point One Integrated Solutions Inc.');
            update_load(100);
            // ob_end_clean();
            // header('Content-type: application/vnd.ms-excel');
            // header('Content-Disposition: attachment;filename=monthly_sales.xls"');
            // header('Cache-Control: max-age=0');
            // $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            // $objWriter->save('php://output');
            
        }
        public function monthly_sales_excel(){
            //diretso excel na
            
            $this->load->library('Excel');
            $month_array = $this->session->userData('month_array');
            $month_date = $this->session->userData('month_date');
            $sheet = $this->excel->getActiveSheet();
            $branch_details = $this->setup_model->get_branch_details();
            $branch = array();
            // echo "<pre>",print_r($month_array),"</pre>";die();
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
                );
            }
            $sheet->mergeCells('A1:R1');
            $sheet->mergeCells('A2:R2');
            $sheet->mergeCells('A3:R3');
            $sheet->mergeCells('A4:R4');
            $sheet->mergeCells('A5:R5');
            $sheet->mergeCells('A6:R6');
            $sheet->mergeCells('A7:R7');
            $sheet->mergeCells('A8:R8');
            $sheet->mergeCells('A9:R9');
            $sheet->getCell('A1')->setValue($branch['name']);
            $sheet->getCell('A2')->setValue($branch['address']);
            $sheet->setCellValueExplicit('A3', 'TIN #'.$branch['tin'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A4', 'ACCRDN #'.$branch['accrdn'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A5', 'PERMIT #'.$branch['permit_no'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A6', 'SN #'.$branch['serial'], PHPExcel_Cell_DataType::TYPE_STRING);
            // $sheet->getCell('A7')->setValue($branch['machine_no']);
            $sheet->setCellValueExplicit('A7', 'MIN #'.$branch['machine_no'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->getCell('A8')->setValue('Monthly Sales Report');
            $sheet->getCell('A9')->setValue($month_date['text']);
            $rn = 10;
            $sheet->mergeCells('A10:A11');
            $sheet->getCell('A'.$rn)->setValue('Day');
            $sheet->mergeCells('B'.$rn.':D'.$rn);
            $sheet->getCell('B'.$rn)->setValue('Accumulating OR');
            $sheet->getCell('B11')->setValue('Beg');
            $sheet->getCell('C11')->setValue('End');
            $sheet->getCell('D11')->setValue('Total');
            $sheet->mergeCells('E'.$rn.':G'.$rn);
            $sheet->getCell('E'.$rn)->setValue('Accumulating Sales');
            $sheet->getCell('E11')->setValue('Beg');
            $sheet->getCell('F11')->setValue('End');
            $sheet->getCell('G11')->setValue('Total');
            $sheet->mergeCells('H10:H11');
            $sheet->getCell('H'.$rn)->setValue('Z-Read Counter');
            $sheet->mergeCells('I10:I11');
            $sheet->getCell('I'.$rn)->setValue('VATable Sales');
            $sheet->mergeCells('J10:J11');
            $sheet->getCell('J'.$rn)->setValue('VAT-Exempt Sales');
            $sheet->mergeCells('K10:K11');
            $sheet->getCell('K'.$rn)->setValue('Zero Rated Sales');
            $sheet->mergeCells('L10:L11');
            $sheet->getCell('L'.$rn)->setValue('VAT 12%');
            $sheet->mergeCells('M'.$rn.':O'.$rn);
            $sheet->getCell('M'.$rn)->setValue('Discount');
            $sheet->getCell('M11')->setValue('Senior Citizen');
            $sheet->getCell('N11')->setValue('PWD');
            // $sheet->getCell('O11')->setValue('VAT Disc');
            $sheet->getCell('O11')->setValue('Regular');
            $sheet->mergeCells('P10:P11');
            $sheet->getCell('P'.$rn)->setValue('Service Charge');
            $sheet->mergeCells('Q10:Q11');
            $sheet->getCell('Q'.$rn)->setValue('Net Sales');
             $sheet->mergeCells('R10:R11');
            $sheet->getCell('R'.$rn)->setValue('12% VAT Exempt');
            $sheet->getStyle("A".$rn.":R11")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$rn.':'.'R11')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->getStyle('A'.$rn.':'.'R11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('A'.$rn.':'.'R11')->getFill()->getStartColor()->setRGB('29bb04');
            $sheet->getStyle('A1:'.'R11')->getFont()->setBold(true);
            $rn = 12;
            //     $month_array[$start_month] = array(
            //         'cr_beg'=>iSetObj($trans['first_ref'],'trans_ref'),
            //         'cr_end'=>iSetObj($trans['last_ref'],'trans_ref'),
            //         'cr_count'=>$trans['ref_count'],
            //         'beg'=>$gt['old_grand_total'],
            //         'new'=>$gt['old_grand_total']+$net_no_adds,
            //         'ctr'=>$gt['ctr'],
            //         'vatsales'=>$taxable,
            //         'vatex'=>$nontaxable,
            //         'zero_rated'=>$zero_rated,
            //         'vat'=>$vat_,
            //         'net_sales'=>$net_sales,
            //         'pwdisc'=>$pwdisc,
            //         'sndisc'=>$sndisc,
            //         'othdisc'=>$othdisc,
            //         'lessvat'=>$less_vat,
            //         // 'senior'=>
            //     );
            if($month_array){
                $vatsales_total = 0;
                $vatex_total = 0;
                $zero_rate_total = $vat_total = $net_sales_total = $pwdisc_total = $sndisc_total = $othdisc_total = $lessvat_total = $total_charges = 0;
                foreach($month_array as $date => $vals){
                    $sheet->getCell('A'.$rn)->setValue($date);
                    // $sheet->getCell('B'.$rn)->setValue($vals['cr_beg']);
                    $sheet->setCellValueExplicit('B'.$rn, $vals['cr_beg'], PHPExcel_Cell_DataType::TYPE_STRING);
                    // $sheet->getCell('C'.$rn)->setValue($vals['cr_end']);
                    $sheet->setCellValueExplicit('C'.$rn, $vals['cr_end'], PHPExcel_Cell_DataType::TYPE_STRING);
                    if($vals['vatsales']){
                        $vals_net_sales = $vals['vatsales'] + $vals['vatex'] + $vals['zero_rated'] - $vals['sndisc'] -  $vals['othdisc'] - $vals['pwdisc'];
                        $sheet->getCell('D'.$rn)->setValue($vals['cr_count']);
                        $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('E'.$rn)->setValue($vals['beg']);
                        $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('F'.$rn)->setValue($vals['new']);
                        $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('G'.$rn)->setValue($vals['gross']);
                        $sheet->getCell('H'.$rn)->setValue($vals['ctr']);
                        $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('I'.$rn)->setValue($vals['vatsales']);
                        $sheet->getStyle('J'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('J'.$rn)->setValue($vals['vatex']);
                        $sheet->getStyle('K'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('K'.$rn)->setValue($vals['zero_rated']);
                        $sheet->getStyle('L'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('L'.$rn)->setValue($vals['vat']);
                        $sheet->getStyle('M'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('M'.$rn)->setValue($vals['sndisc']);
                        $sheet->getStyle('N'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('N'.$rn)->setValue($vals['pwdisc']);
                        $sheet->getStyle('O'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('O'.$rn)->setValue($vals['othdisc']);
                        $sheet->getStyle('P'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('P'.$rn)->setValue($vals['charges']);
                        $sheet->getStyle('Q'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('Q'.$rn)->setValue( $vals_net_sales);
                        $sheet->getStyle('R'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('R'.$rn)->setValue($vals['lessvat']);
                        $vatsales_total += $vals['vatsales'];
                        $vatex_total += $vals['vatex'];
                        $zero_rate_total += $vals['zero_rated'];
                        $vat_total += $vals['vat'];
                        $sndisc_total += $vals['sndisc'];
                        $pwdisc_total += $vals['pwdisc'];
                        $lessvat_total += $vals['lessvat'];
                        $othdisc_total += $vals['othdisc'];
                        $net_sales_total +=  $vals_net_sales;
                        $total_charges += $vals['charges'];
                    }
                    $rn++;
                }
                $sheet->getStyle('A'.$rn.':R'.$rn)->getFont()->setBold(true);
                $sheet->getCell('A'.$rn)->setValue('TOTAL');
                $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('I'.$rn)->setValue($vatsales_total);
                $sheet->getStyle('J'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('J'.$rn)->setValue($vatex_total);
                $sheet->getStyle('K'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('K'.$rn)->setValue($zero_rate_total);
                $sheet->getStyle('L'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('L'.$rn)->setValue($vat_total);
                $sheet->getStyle('M'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('M'.$rn)->setValue($sndisc_total);
                $sheet->getStyle('N'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('N'.$rn)->setValue($pwdisc_total);
                $sheet->getStyle('O'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('O'.$rn)->setValue($othdisc_total);
                $sheet->getStyle('P'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('P'.$rn)->setValue($total_charges);
                $sheet->getStyle('Q'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('Q'.$rn)->setValue($net_sales_total);
                $sheet->getStyle('R'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('R'.$rn)->setValue($lessvat_total);
            }
            if (ob_get_contents()) 
                ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.date('F Y',strtotime($month_date['month_year'])).'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
        }
        public function daily_sales_ui(){
            $this->load->model('dine/reports_model');
            $this->load->helper('dine/reports_helper');
            $data = $this->syter->spawn('daily');
            $data['page_title'] = 'BIR Daily Sales';
            $data['code'] = dailySalesUi();
            $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/reports.php';
            $data['use_js'] = 'dailyJS';
            $this->load->view('page',$data);
        }
        public function get_daily_reports(){
            ini_set('memory_limit', '-1');
            set_time_limit(3600);
            sess_clear('daily_array');
            sess_clear('daily_date');
            $this->load->helper('dine/reports_helper');
            $date = $this->input->post('date');
            $json = $this->input->post('json');
            // $start_month = date('Y-'.$month.'-01');
            $date = date("Y-m-d", strtotime($date));
            update_load(2);
            sleep(1);
            $load = 2;
                    // echo $cmonth.'<br>';
            $month_array = array();
            $post = $this->set_post(null,$date);
            $trans = $this->trans_sales($post['args']);
            // echo "<pre>",print_r($trans),"</pre>";die();
            $sales = $trans['sales'];
            $settled = array_merge($trans['sales']['settled']['orders'],$trans['sales']['settled_void']['orders']);
            usort($settled, function($a, $b) {
                return $a->trans_ref - $b->trans_ref;
            });
            $daily_array = array();
            $count_settled = count($settled);

            $adder = round(88/$count_settled,2);
            // echo "<pre>",print_r($settled),"</pre>";die();
            foreach ($settled as $key => $set){
                // $trans_menus = $this->menu_sales($sales_id,$curr);
                $trans_charges = $this->charges_sales($set->sales_id);
                $trans_discounts = $this->discounts_sales($set->sales_id);
                $trans_local_tax = $this->local_tax_sales($set->sales_id);
                $trans_tax = $this->tax_sales($set->sales_id);
                $trans_no_tax = $this->no_tax_sales($set->sales_id);
                $trans_zero_rated = $this->zero_rated_sales($set->sales_id);
                $trans_menus = $this->menu_sales($set->sales_id);
                $gross = $trans_menus['gross'];
                // $print_str .= align_center($set->trans_ref,PAPER_WIDTH," ")."\r\n";
                // $print_str .= align_center($set->datetime,PAPER_WIDTH," ")."\r\n";
                $net = $set->total_amount;
                $charges = $trans_charges['total'];
                $discounts = $trans_discounts['total'];
                $tax_disc = $trans_discounts['tax_disc_total'];
                $no_tax_disc = $trans_discounts['no_tax_disc_total'];
                $local_tax = $trans_local_tax['total'];
                $tax = $trans_tax['total'];
                $no_tax = $trans_no_tax['total'];
                $zero_rated = $trans_zero_rated['total'];
                $no_tax -= $zero_rated;

                $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;

                $loc_txt = numInt(($local_tax));
                $net_no_adds = $net-($charges+$local_tax);
                // $nontaxable = $no_tax;
                $nontaxable = $no_tax - $no_tax_disc;
                // $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
                $taxable = ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12;
                // $taxable = ($gross - $less_vat - $nontaxable - $zero_rated) / 1.12;
                $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
                $add_gt = $taxable+$nontaxable+$zero_rated;
                $nsss = $taxable +  $nontaxable +  $zero_rated;


                // $net_no_adds = ($net)-$charges-$local_tax;
                // $taxable = ( ($net_no_adds + $no_tax_disc) - ($tax + $no_tax));
                $types = $trans_discounts['types'];
                // echo var_dump($types);
                // $qty = 0;
                $sndisc = 0;
                $pwdisc = 0;
                $othdisc = 0;
                $pwddisc_lss = 0;
                $sndisc_lss = 0;
                $no_tax_disc = 0;
                foreach ($types as $code => $val) {
                    // $amount = $val['amount'];
                    if($code == 'PWDISC'){
                        // $amount = $val['amount'] / 1.12;
                        // $pwdisc = $gross - $set->total_amount;
                        $pwdisc = $val['amount'];
                        // if($val['no_tax'] == 1)
                        $pwddisc_lss = $val['qty'];
                    }elseif($code == 'SNDISC'){
                        $sndisc = $val['amount'];
                        $sndisc_lss = $val['qty'];
                    }else{
                        $othdisc += $val['amount'];
                    }
                    // $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
                    //                      .append_chars('-'.Num($amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    // $qty += $val['qty'];
                    if($val['no_tax'] == 1)
                        $no_tax_disc += $val['amount'];
                }
                $no_no_tax = $pwddisc_lss+$sndisc_lss;
                // $less_vat = $gross - $taxable - $no_tax - $tax;
                $lv = $less_vat / $no_no_tax;
                // $no_tax -= $no_tax_disc;

                $vat_ = $taxable * .12;


                // $print_str .= append_chars(substrwords('VAT SALES',18,""),"right",23," ")
                //              .append_chars(numInt(($taxable)),"left",13," ")."\r\n";
                // $print_str .= append_chars(substrwords('VAT SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                //                      .append_chars(numInt($taxable),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $total_net = $taxable + $no_tax + $zero_rated + $tax;
                // $print_str .= append_chars(substrwords('VAT EXEMPT SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                //              .append_chars(numInt(($no_tax)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= append_chars(substrwords('ZERO RATED',13,""),"right",PAPER_TOTAL_COL_1," ")
                //              .append_chars(numInt(($zero_rated)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= append_chars(substrwords('VAT',18,""),"right",PAPER_TOTAL_COL_1," ")
                //                          .append_chars(numInt(($tax)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= append_chars("","right",23," ").append_chars("----------","left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= append_chars(substrwords('Total',18,""),"right",PAPER_TOTAL_COL_1," ")
                //                          .append_chars(numInt(($total_net)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= append_chars(substrwords('Charges',18,""),"right",PAPER_TOTAL_COL_1," ")
                //                          .append_chars(numInt(($charges)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= append_chars(substrwords('Local Tax',18,""),"right",PAPER_TOTAL_COL_1," ")
                //                          .append_chars(numInt(($local_tax)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= append_chars(substrwords('Discounts',18,""),"right",PAPER_TOTAL_COL_1," ")
                //                          .append_chars(numInt(($discounts)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= PAPER_LINE."\r\n";
                // $print_str .= append_chars(substrwords('NET SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                //              .append_chars(numInt(($set->total_amount)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                
                $daily_array[] = array(
                    'or_number'=>$set->trans_ref,
                    'vatable'=>$taxable,
                    'vatex'=>$nontaxable,
                    // 'vatex'=>$nontaxable,
                    'zero_rated'=>$zero_rated,
                    'vat'=>$vat_,
                    'gross'=>$gross,
                    // 'sndisc'=>$sndisc + ($lv * $sndisc_lss),
                    'sndisc'=>$sndisc,
                    'pwdisc'=>$pwdisc,
                    // 'pwdisc'=>$pwdisc + ($lv * $pwddisc_lss),
                    'othdisc'=>$othdisc,
                    'netsales'=>$set->total_amount - $charges,
                    'charges'=>$charges,
                    'lessvat'=>$less_vat,
                    'inactive'=>$set->inactive
                );
                // if($load == 74){
                //     update_load($load);
                //     sleep(1);
                // }else{
                    $load += $adder;
                    update_load($load);
                //     sleep(1);
                // }
            }
            update_load(90);
            // sleep(2);
            $this->session->set_userdata('daily_array',$daily_array);
            $this->session->set_userdata('daily_date',$date);
            // //diretso excel na
            // $this->load->library('Excel');
            // $sheet = $this->excel->getActiveSheet();
            // $sheet->getCell('A1')->setValue('Point One Integrated Solutions Inc.');
            update_load(100);
            // ob_end_clean();
            // header('Content-type: application/vnd.ms-excel');
            // header('Content-Disposition: attachment;filename=monthly_sales.xls"');
            // header('Cache-Control: max-age=0');
            // $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            // $objWriter->save('php://output');
            
        }
        public function daily_sales_excel(){
            //diretso excel na
            
            $this->load->library('Excel');
            $daily_array = $this->session->userData('daily_array');
            $daily_date = $this->session->userData('daily_date');
            $sheet = $this->excel->getActiveSheet();
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
                );
            }
            $sheet->mergeCells('A1:L1');
            $sheet->mergeCells('A2:L2');
            $sheet->mergeCells('A3:L3');
            $sheet->mergeCells('A4:L4');
            $sheet->mergeCells('A5:L5');
            $sheet->mergeCells('A6:L6');
            $sheet->mergeCells('A7:L7');
            $sheet->mergeCells('A8:L8');
            $sheet->mergeCells('A9:L9');
            $sheet->getCell('A1')->setValue($branch['name']);
            $sheet->getCell('A2')->setValue($branch['address']);
            $sheet->setCellValueExplicit('A3', 'TIN #'.$branch['tin'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A4', 'ACCRDN #'.$branch['accrdn'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A5', 'PERMIT #'.$branch['permit_no'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A6', 'SN #'.$branch['serial'], PHPExcel_Cell_DataType::TYPE_STRING);
            // $sheet->getCell('A7')->setValue($branch['machine_no']);
            $sheet->setCellValueExplicit('A7', 'MIN #'.$branch['machine_no'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->getCell('A8')->setValue('Daily Sales Report');
            $sheet->getCell('A9')->setValue(sql2Date($daily_date));
            $rn = 10;
            $sheet->mergeCells('A10:A11');
            $sheet->getCell('A'.$rn)->setValue('OR Number');
            $sheet->mergeCells('B10:B11');
            $sheet->getCell('B'.$rn)->setValue('Total Sales');
            $sheet->mergeCells('C10:C11');
            $sheet->getCell('C'.$rn)->setValue('VATable Sales');
            $sheet->mergeCells('D10:D11');
            $sheet->getCell('D'.$rn)->setValue('VAT-Exempt Sales');
            $sheet->mergeCells('E10:E11');
            $sheet->getCell('E'.$rn)->setValue('Zero Rated Sales');
            $sheet->mergeCells('F10:F11');
            $sheet->getCell('F'.$rn)->setValue('VAT');
            $sheet->mergeCells('G'.$rn.':I'.$rn);
            $sheet->getCell('G'.$rn)->setValue('Discount');
            $sheet->getCell('G11')->setValue('Senior Citizen');
            $sheet->getCell('H11')->setValue('PWD');
            // $sheet->getCell('I11')->setValue('Vat Disc');
            $sheet->getCell('I11')->setValue('Regular');
            $sheet->mergeCells('J10:J11');
            $sheet->getCell('J'.$rn)->setValue('Service Charge');
            $sheet->mergeCells('K10:K11');
            $sheet->getCell('K'.$rn)->setValue('Net Sales');
            $sheet->mergeCells('L10:L11');
            $sheet->getCell('L'.$rn)->setValue('12% VAT Exempt');
            $sheet->getStyle("A".$rn.":L11")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$rn.':'.'L11')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->getStyle('A'.$rn.':'.'L11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('A'.$rn.':'.'L11')->getFill()->getStartColor()->setRGB('29bb04');
            $sheet->getStyle('A1:'.'L11')->getFont()->setBold(true);
            $rn = 12;
            // daily_array[] = array(
            //         'or_number'=>$set->trans_ref,
            //         'vatable'=>$taxable,
            //         'vatex'=>$no_tax,
            //         'vat'=>$tax,
            //         'gross'=>$gross,
            //         'sndisc'=>$sndisc,
            //         'pwdisc'=>$pwdisc,
            //         'othdisc'=>$othdisc,
            //         'netsales'=>$set->total_amount,
            //     );
            if($daily_array){
                $vatsales_total = 0;
                $vatex_total = 0;
                $zero_rate_total = $vat_total = $net_sales_total = $pwdisc_total = $sndisc_total = $othdisc_total = $gross_total = $charges_total = $lessvat_total = 0;
             
                    // echo "<pre>",print_r($daily_array),"</pre>";die();
                foreach($daily_array as $date => $vals){


                    if($vals['inactive'] == '0'){

                        $vals_net_sales = $vals['vatable'] + $vals['vatex'] + $vals['zero_rated'] - $vals['sndisc'] -  $vals['othdisc'] - $vals['pwdisc'];

                        // $sheet->getCell('A'.$rn)->setValue($vals['or_number']);
                        $sheet->setCellValueExplicit('A'.$rn, $vals['or_number'], PHPExcel_Cell_DataType::TYPE_STRING);
                        // $sheet->getCell('B'.$rn)->setValue($vals['cr_beg']);
                        $sheet->getStyle('B'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('B'.$rn)->setValue($vals['gross']);
                        $sheet->getStyle('C'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('C'.$rn)->setValue($vals['vatable']);
                        $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('D'.$rn)->setValue($vals['vatex']);
                        $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('E'.$rn)->setValue($vals['zero_rated']);
                        $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('F'.$rn)->setValue($vals['vat']);
                        $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('G'.$rn)->setValue($vals['sndisc']);
                        $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('H'.$rn)->setValue($vals['pwdisc']);
                        $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('I'.$rn)->setValue($vals['othdisc']);
                        $sheet->getStyle('J'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('J'.$rn)->setValue($vals['charges']);
                        $sheet->getStyle('K'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('K'.$rn)->setValue($vals_net_sales);
                        $sheet->getStyle('L'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('L'.$rn)->setValue($vals['lessvat']);
                        
                            $vatsales_total += $vals['vatable'];
                            $vatex_total += $vals['vatex'];
                            $zero_rate_total += $vals['zero_rated'];
                            $vat_total += $vals['vat'];
                            $sndisc_total += $vals['sndisc'];
                            $pwdisc_total += $vals['pwdisc'];
                            $gross_total += $vals['gross'];
                            $othdisc_total += $vals['othdisc'];
                            $net_sales_total += $vals_net_sales;
                            $charges_total += $vals['charges'];
                            $lessvat_total += $vals['lessvat'];
                    }else{
                          $vals_net_sales = $vals['vatable'] + $vals['vatex'] + $vals['zero_rated'] - $vals['sndisc'] -  $vals['othdisc'] - $vals['pwdisc'];

                        // $sheet->getCell('A'.$rn)->setValue($vals['or_number']);
                        $sheet->setCellValueExplicit('A'.$rn, $vals['or_number'], PHPExcel_Cell_DataType::TYPE_STRING);
                        // $sheet->getCell('B'.$rn)->setValue($vals['cr_beg']);
                        $sheet->getStyle('B'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('B'.$rn)->setValue('('.number_format($vals['gross'], 2, '.', ' ').')');
                        $sheet->getStyle('C'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('C'.$rn)->setValue('('.number_format($vals['vatable'], 2, '.', ' ').')');
                        $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('D'.$rn)->setValue('('.number_format($vals['vatex'], 2, '.', ' ').')');
                        $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('E'.$rn)->setValue('('.number_format($vals['zero_rated'], 2, '.', ' ').')');
                        $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('F'.$rn)->setValue('('.number_format($vals['vat'], 2, '.', ' ').')');
                        $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('G'.$rn)->setValue('('.number_format($vals['sndisc'], 2, '.', ' ').')');
                        $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('H'.$rn)->setValue('('.number_format($vals['pwdisc'], 2, '.', ' ').')');
                        $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('I'.$rn)->setValue('('.number_format($vals['othdisc'], 2, '.', ' ').')');
                        $sheet->getStyle('J'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('J'.$rn)->setValue('('.number_format($vals['charges'], 2, '.', ' ').')');
                        $sheet->getStyle('K'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('K'.$rn)->setValue('('.number_format($vals_net_sales, 2, '.', ' ').')');
                        $sheet->getStyle('L'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->getCell('L'.$rn)->setValue('('.number_format($vals['lessvat'], 2, '.', ' ').')' );
                        
                       
                    }
                    // }
                    $rn++;
                }
                $sheet->getStyle('A'.$rn.':L'.$rn)->getFont()->setBold(true);
                $sheet->setCellValueExplicit('A'.$rn, 'TOTAL', PHPExcel_Cell_DataType::TYPE_STRING);
                $sheet->getStyle('B'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('B'.$rn)->setValue($gross_total);
                $sheet->getStyle('C'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('C'.$rn)->setValue($vatsales_total);
                $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('D'.$rn)->setValue($vatex_total);
                $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('E'.$rn)->setValue($zero_rate_total);
                $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('F'.$rn)->setValue($vat_total);
                $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('G'.$rn)->setValue($sndisc_total);
                $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('H'.$rn)->setValue($pwdisc_total);
                $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('I'.$rn)->setValue($othdisc_total);
                $sheet->getStyle('J'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('J'.$rn)->setValue($charges_total);
                $sheet->getStyle('K'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('K'.$rn)->setValue($net_sales_total);
                $sheet->getStyle('L'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getCell('L'.$rn)->setValue($lessvat_total);
            }
            if (ob_get_contents()) 
                ob_end_clean();

            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=daily_report.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
        }
        public function set_post($set_range=null,$set_calendar=null){
            $args = array();
            $from = "";
            $to = "";
            $date = "";
            $range = $this->input->post('calendar_range');
            $calendar = $this->input->post('calendar');
            if($set_range != null )
                $range = $set_range;
            if($set_calendar != null )
                $calendar = $set_calendar;
            // $range = '2015/10/28 7:00 AM to 2015/10/28 10:00 PM';
            // $calendar = '12/04/2015';
            $title = "";
            if($this->input->post('title'))
                $title = $this->input->post('title');
            // $title = 'ZREAD';
            if($range != ""){
                $daterange = $range;
                $dates = explode(" to ",$daterange);
                $from = date2SqlDateTime($dates[0]);
                $to = date2SqlDateTime($dates[1]);
                $args["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            }
            if($calendar != ""){
                $date = date2Sql($calendar);

                $details = $this->setup_model->get_branch_details();
            
                $open_time = $details[0]->store_open;
                $close_time = $details[0]->store_close;

                $from = date2SqlDateTime($date." ".$open_time);
                $oa = date('a',strtotime($open_time));
                $ca = date('a',strtotime($close_time));
                $to = date2SqlDateTime($date." ".$close_time);
                if($oa == $ca){
                    $to = date('Y-m-d H:i:s',strtotime($to . "+1 days"));
                }

                //old jed
                // $rargs["DATE(read_details.read_date) = DATE('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
                // $select = "read_details.*";
                // $results = $this->site_model->get_tbl('read_details',$rargs,array('scope_from'=>'asc'),"",true,$select);
                // // echo $this->site_model->db->last_query();
                // $args = array();
                // $from = "";
                // $to = "";
                // $datetimes = array();
                // foreach ($results as $res) {
                //     $datetimes[] = $res->scope_from;
                //     $datetimes[] = $res->scope_to;
                //     // break;
                // }
                // usort($datetimes, function($a, $b) {
                //   $ad = new DateTime($a);
                //   $bd = new DateTime($b);
                //   if ($ad == $bd) {
                //     return 0;
                //   }
                //   return $ad > $bd ? 1 : -1;
                // });
                // foreach ($datetimes as $dt) {
                //     $from = $dt;
                //     break;
                // }    
                // foreach ($datetimes as $dt) {
                //     $to = $dt;
                // }
                //end

                // echo $from."-".$to;
                // $rargs2["DATE(read_details.read_date) = DATE('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
                // $select = "read_details.*";
                // $results2 = $this->site_model->get_tbl('read_details',$rargs2,array('scope_to'=>'desc'),"",true,$select);
                // foreach ($results2 as $res) {
                //     $to = $res->scope_to;
                //     break;
                // }
                if($from != "" && $to != ""){
                    $args["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                }
                else{
                    $args["DATE(trans_sales.datetime) = DATE('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
                    $from = date('Y-m-d 00:00',strtotime($date));
                    $to = date('Y-m-d 24:00',strtotime($date));
                }
            }
            $emp = "All";
            if($this->input->post('cashier')){
                $args['trans_sales.user_id'] = $this->input->post('cashier');
                $server = $this->manager_model->get_server_details($this->input->post('cashier'));
                $emp = $server[0]->fname." ".$server[0]->mname." ".$server[0]->lname." ".$server[0]->suffix;
            }
            $shift = $this->input->post('shift_id');
            // $shift = 1;
            if($shift != ""){
                $join['users'] = array('content'=>'shifts.user_id = users.id');
                $jargs['shifts.shift_id'] = $shift;
                $select = "shifts.*,users.fname,users.lname,users.mname,users.suffix,users.username";
                $results = $this->site_model->get_tbl('shifts',$jargs,array('check_in'=>'desc'),$join,true,$select);
                $res = $results[0];
                $server = $this->manager_model->get_server_details($res->user_id);
                $emp = $server[0]->fname." ".$server[0]->mname." ".$server[0]->lname." ".$server[0]->suffix;
                $from = date2SqlDateTime($res->check_in);
                if($res->check_out == ""){
                    $today = $this->site_model->get_db_now();
                    $to = date2SqlDateTime($today);
                }
                else
                    $to = date2SqlDateTime($res->check_out);
                $args = array();
                $args['trans_sales.shift_id'] = $shift;
            }
            $terminal = TERMINAL_ID;
            $args['trans_sales.terminal_id'] = $terminal;
            return array('args'=>$args,'from'=>$from,'to'=>$to,'date'=>$date,'terminal'=>$terminal,"employee"=>$emp,"title"=>$title,"shift_id"=>$shift);
        }
        public function trans_sales($args=array(),$curr=false){
            $total_chit = $total_producttest = 0;
            $chit = array();
            $producttest = array();
            $n_results = array();
            // if($curr){
            //     $this->cashier_model->db = $this->load->database('default', TRUE);
            //     $n_results  = $this->cashier_model->get_trans_sales(null,$args);
            // }
            
            $this->cashier_model->db = $this->load->database('main', TRUE);
            $results = $this->cashier_model->get_trans_sales_rep(null,$args);
            // echo "<pre>",print_r($results),"</pre>";die();
            // echo $this->cashier_model->db->last_query();
            $orders = array();
            if(HIDECHIT){
                // if(count($n_results) > 0){
                //     foreach ($n_results as $nres) {

                //         if($nres->type_id == 10){
                //             $this->site_model->db = $this->load->database('default', TRUE);
                //             $where = array('sales_id'=>$nres->sales_id);
                //             $rest = $this->site_model->get_details($where,'trans_sales_payments');
                //             if($rest){
                //                 if($rest[0]->payment_type != 'chit'){

                //                     if(!isset($orders[$nres->sales_id])){
                //                         $orders[$nres->sales_id] = $nres;
                //                     }
                //                 }else{
                //                     $chit[$nres->sales_id] = $nres;
                //                 }
                //             }else{
                //                 if(!isset($orders[$nres->sales_id])){
                //                     $orders[$nres->sales_id] = $nres;
                //                 }
                //             }

                //         }else{
                //             if(!isset($orders[$nres->sales_id])){
                //                 $orders[$nres->sales_id] = $nres;
                //             }
                //         }
                //     }
                // }
                foreach ($results as $res) {
                    
                    if($res->type_id == 10){
                        $this->site_model->db = $this->load->database('main', TRUE);
                        $where = array('sales_id'=>$res->sales_id);
                        $rest = $this->site_model->get_details($where,'trans_sales_payments');
                        if($rest){
                            if($rest[0]->payment_type != 'chit'){
                                if(!isset($orders[$res->sales_id])){
                                    $orders[$res->sales_id] = $res;
                                }
                            }else{
                                $chit[$res->sales_id] = $res;
                            }
                        }else{
                            if(!isset($orders[$res->sales_id])){
                                $orders[$res->sales_id] = $res;
                            }
                        }
                    }else{
                        if(!isset($orders[$res->sales_id])){
                            $orders[$res->sales_id] = $res;
                        }
                    }
                }
            }else{
                // if(count($n_results) > 0){
                //     foreach ($n_results as $nres) {
                //         if(!isset($orders[$nres->sales_id])){
                //             $orders[$nres->sales_id] = $nres;
                //         }
                //     }
                // }
                foreach ($results as $res) {
                    if(!isset($orders[$res->sales_id])){
                        $orders[$res->sales_id] = $res;
                    }
                }
            }
            if(PRODUCT_TEST){
                foreach ($results as $res) {
                    
                    if($res->type_id == 10){
                        $this->site_model->db = $this->load->database('main', TRUE);
                        $where = array('sales_id'=>$res->sales_id);
                        $rest = $this->site_model->get_details($where,'trans_sales_payments');
                        if($rest){
                            if($rest[0]->payment_type != 'producttest'){
                                if(!isset($orders[$res->sales_id])){
                                    $orders[$res->sales_id] = $res;
                                }
                            }else{
                                $producttest[$res->sales_id] = $res;
                            }
                        }else{
                            if(!isset($orders[$res->sales_id])){
                                $orders[$res->sales_id] = $res;
                            }
                        }
                    }else{
                        if(!isset($orders[$res->sales_id])){
                            $orders[$res->sales_id] = $res;
                        }
                    }
                }
            }else{
                // if(count($n_results) > 0){
                //     foreach ($n_results as $nres) {
                //         if(!isset($orders[$nres->sales_id])){
                //             $orders[$nres->sales_id] = $nres;
                //         }
                //     }
                // }
                foreach ($results as $res) {
                    if(!isset($orders[$res->sales_id])){
                        $orders[$res->sales_id] = $res;
                    }
                }
            }

            $sales = array();
            $all_ids = array();
            $sales['void'] = array();
            $sales['cancel'] = array();
            $sales['settled'] = array();
            $sales['settled_void'] = array();


            $sales['void']['ids'] = array();
            $sales['cancel']['ids'] = array();
            $sales['settled']['ids'] = array();
            $sales['settled_void']['ids'] = array();

            $sales['void']['orders'] = array();
            $sales['cancel']['orders'] = array();
            $sales['settled']['orders'] = array();
            $sales['settled_void']['orders'] = array();
            
            $net = 0;
            $void_amount = 0;
            $cancel_amount = 0;
            $types = array();
            $ordsnums = array();
            $all_orders = array();
            
            foreach ($orders as $sales_id => $sale) {
                if($sale->type_id == 10){
                    if($sale->trans_ref != "" && $sale->inactive == 0){
                        $sales['settled']['ids'][] = $sales_id;
                        $net += round($sale->total_amount,2);
                        $types[$sale->type][$sale->sales_id] = $sale;
                        $ordsnums[$sale->trans_ref] = $sale;
                        $sales['settled']['orders'][$sales_id] = $sale;
                    }
                    else if($sale->trans_ref == "" && $sale->inactive == 1){
                        if($sale->void_user_id){
                            $sales['cancel']['ids'][] = $sales_id;
                            $sales['cancel']['orders'][$sales_id] = $sale;
                            $cancel_amount += round($sale->total_amount,2);
                        }
                    }else if(!empty($sale->trans_ref)  && $sale->inactive == 1){
                         $sales['settled_void']['ids'][] = $sales_id;
                         $sales['settled_void']['orders'][$sales_id] = $sale;
                    }
                }
                else{
                    $sales['void']['ids'][] = $sales_id;
                    $sales['void']['orders'][$sales_id] = $sale;
                    $void_amount += round($sale->total_amount,2);
                }
                $all_ids[] = $sales_id;
                $all_orders[$sales_id] = $sale;
            }
            ksort($ordsnums);
            $first = array_shift(array_slice($ordsnums, 0, 1));
            $last = end($ordsnums);
            $ref_ctr = count($ordsnums);

            if(HIDECHIT){
                foreach($chit as $key => $vals){

                    $this->site_model->db = $this->load->database('main', TRUE);
                    $where = array('sales_id'=>$key);
                    $results = $this->site_model->get_details($where,'trans_sales_payments');

                    $total_chit += $results[0]->to_pay;
                }
            }
            if(PRODUCT_TEST){
                foreach($producttest as $key => $vals){

                    $this->site_model->db = $this->load->database('main', TRUE);
                    $where = array('sales_id'=>$key);
                    $results = $this->site_model->get_details($where,'trans_sales_payments');

                    $total_producttest += $results[0]->to_pay;
                }
            }


            return array('all_ids'=>$all_ids,'all_orders'=>$all_orders,'sales'=>$sales,'net'=>$net,'void'=>$void_amount,'types'=>$types,'refs'=>$ordsnums,'first_ref'=>$first,'last_ref'=>$last,'ref_count'=>$ref_ctr,'total_chit'=>$total_chit,'cancel_amount'=>$cancel_amount,'product_test'=>$total_producttest);
        }
        public function discounts_sales($ids=array(),$curr=false){
            $total_disc = 0;
            $discounts = array();
            $disc_codes = array();
            $ids_used = array();
            $taxable_disc = 0;
            $non_taxable_disc = 0;
            $vat_exempt_total = 0;
            $sales_disc_count = 0;
            if(count($ids) > 0){
                $n_sales_discs = array();
                // if($curr){
                //     $this->cashier_model->db = $this->load->database('default', TRUE);
                //     $n_sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$ids));

                // }
                $this->cashier_model->db = $this->load->database('main', TRUE);
                $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$ids,'trans_sales_discounts.pos_id'=>TERMINAL_ID));
                $count_sales_sc = $this->cashier_model->get_trans_sales_discounts_sc(null,array("trans_sales_discounts.sales_id"=>$ids,'trans_sales_discounts.pos_id'=>TERMINAL_ID));
                // echo $this->cashier_model->db->last_query();die();
                $sales_disc_count = count($count_sales_sc);
                // echo "<pre>",print_r($salecs_discs),"</pre>";die();

                foreach ($sales_discs as $discs) {
                    if(!in_array($discs->sales_id, $ids_used)){
                        $ids_used[] = $discs->sales_id;
                    }
                    if(!isset($disc_codes[$discs->disc_code])){
                        $disc_codes[$discs->disc_code] = array('name'=>$discs->disc_name,'qty'=> 1,'amount'=>round($discs->amount,2));
                    }
                    else{
                        $disc_codes[$discs->disc_code]['qty'] += 1;
                        $disc_codes[$discs->disc_code]['amount'] += round($discs->amount,2);
                    }
                    $total_disc += $discs->amount;
                    if($discs->no_tax == 1){
                        $non_taxable_disc += round($discs->amount,2);
                        // $vat_exempt_total += $discs->vat_ex;
                    }
                    else{
                        $taxable_disc += round($discs->amount,2);
                    }
                }
                // if(count($n_sales_discs) > 0){
                //     foreach ($n_sales_discs as $discs) {
                //         if(!in_array($discs->sales_id, $ids_used)){
                //             if(!isset($disc_codes[$discs->disc_code])){
                //                 $disc_codes[$discs->disc_code] = array('name'=>$discs->disc_name,'qty'=> 1,'amount'=>round($discs->amount,2));
                //             }
                //             else{
                //                 $disc_codes[$discs->disc_code]['qty'] += 1;
                //                 $disc_codes[$discs->disc_code]['amount'] += round($discs->amount,2);
                //             }
                //             $total_disc += $discs->amount;
                //             if($discs->no_tax == 1){
                //                 $non_taxable_disc += round($discs->amount,2);
                //             }
                //             else{
                //                 $taxable_disc += round($discs->amount,2);
                //             }
                //         }
                //     }
                // }
            }
            $discounts['total']=$total_disc;
            $discounts['types']=$disc_codes;
            $discounts['tax_disc_total']=$taxable_disc;
            $discounts['no_tax_disc_total']=$non_taxable_disc;
            $discounts['vat_exempt_total']=$vat_exempt_total;
            $discounts['sales_disc_count']=$sales_disc_count;
            return $discounts;
        }
        public function local_tax_sales($ids=array(),$curr=false){
            $total_local_tax = 0;
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_local_tax.sales_id"] = $ids;
                $cargs["trans_sales_local_tax.pos_id"] = TERMINAL_ID;
                $n_cesults = array();
                // if($curr){
                //     $this->site_model->db = $this->load->database('default', TRUE);
                //     $n_cesults = $this->site_model->get_tbl('trans_sales_local_tax',$cargs);
                // }
                $this->site_model->db = $this->load->database('main', TRUE);
                $cesults = $this->site_model->get_tbl('trans_sales_local_tax',$cargs);

                foreach ($cesults as $ces) {
                    if(!in_array($ces->sales_id, $ids_used)){
                        $ids_used[] = $ces->sales_id;
                    }
                    $total_local_tax += $ces->amount;
                }
                // if(count($n_cesults) > 0){
                //     foreach ($n_cesults as $ces) {
                //         if(!in_array($ces->sales_id, $ids_used)){
                //             $total_local_tax += $ces->amount;
                //         }
                //     }
                // }
            }
            return array('total'=>$total_local_tax);
        }
        public function tax_sales($ids=array(),$curr=false){
            $total_tax = 0;
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_tax.sales_id"] = $ids;
                $cargs["trans_sales_tax.pos_id"] = TERMINAL_ID;
                $n_cesults = array();
                // if($curr){
                //     $this->site_model->db = $this->load->database('default', TRUE);
                //     $n_cesults = $this->site_model->get_tbl('trans_sales_tax',$cargs);
                // }
                $this->site_model->db = $this->load->database('main', TRUE);
                $cesults = $this->site_model->get_tbl('trans_sales_tax',$cargs);
                foreach ($cesults as $ces) {
                    if(!in_array($ces->sales_id, $ids_used)){
                        $ids_used[] = $ces->sales_id;
                    }
                    $total_tax += $ces->amount;
                }
                // if(count($n_cesults) > 0){
                //     foreach ($n_cesults as $ces) {
                //         if(!in_array($ces->sales_id, $ids_used)){
                //             $total_tax += $ces->amount;
                //         }
                //     }
                // }
            }
            return array('total'=>$total_tax);
        }
        public function no_tax_sales($ids=array(),$curr=false){
            $total_no_tax = 0;
            $total_no_tax_round = 0;
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_no_tax.sales_id"] = $ids;
                $cargs["trans_sales_no_tax.pos_id"] = TERMINAL_ID;
                $n_cesults = array();
                // if($curr){
                //     $this->site_model->db = $this->load->database('default', TRUE);
                //     $n_cesults = $this->site_model->get_tbl('trans_sales_no_tax',$cargs);
                // }
                $this->site_model->db = $this->load->database('main', TRUE);
                $cesults = $this->site_model->get_tbl('trans_sales_no_tax',$cargs);
                foreach ($cesults as $ces) {
                    if(!in_array($ces->sales_id, $ids_used)){
                        $ids_used[] = $ces->sales_id;
                    }
                    $total_no_tax += $ces->amount;
                    $total_no_tax_round += numInt($ces->amount);
                }
                // if(count($n_cesults) > 0){
                //     foreach ($n_cesults as $ces) {
                //         if(!in_array($ces->sales_id, $ids_used)){
                //             $total_no_tax += $ces->amount;
                //             $total_no_tax_round += numInt($ces->amount);
                //         }
                //     }
                // }
            }
            return array('total'=>$total_no_tax);
        }
        public function zero_rated_sales($ids=array(),$curr=false){
            $total = 0;
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_zero_rated.sales_id"] = $ids;
                $cargs["trans_sales_zero_rated.pos_id"] = TERMINAL_ID;
                $n_cesults = array();
                // if($curr){
                //     $this->site_model->db = $this->load->database('default', TRUE);
                //     $n_cesults = $this->site_model->get_tbl('trans_sales_zero_rated',$cargs);
                // }
                $this->site_model->db = $this->load->database('main', TRUE);
                $cesults = $this->site_model->get_tbl('trans_sales_zero_rated',$cargs);

                foreach ($cesults as $ces) {
                    if(!in_array($ces->sales_id, $ids_used)){
                        $ids_used[] = $ces->sales_id;
                    }
                    $total += $ces->amount;
                }
                // if(count($n_cesults) > 0){
                //     foreach ($n_cesults as $ces) {
                //         if(!in_array($ces->sales_id, $ids_used)){
                //             $total += $ces->amount;
                //         }
                //     }
                // }
            }
            return array('total'=>$total);
        }
        public function menu_sales($ids=array(),$curr=false){
            $cats = array();
            $this->site_model->db = $this->load->database('default', TRUE);
            $cat_res = $this->site_model->get_tbl('menu_categories');
            foreach ($cat_res as $ces) {
                $cats[$ces->menu_cat_id] = array('cat_id'=>$ces->menu_cat_id,'name'=>$ces->menu_cat_name,'qty'=>0,'amount'=>0);
            }
            $sub_cats = array();
            // $sales_menu_cat = $this->menu_model->get_menu_subcategories(null,true);
            // foreach ($sales_menu_cat as $rc) {
            //     $sub_cats[$rc->menu_sub_cat_id] = array('name'=>$rc->menu_sub_cat_name,'qty'=>0,'amount'=>0);
            // }

            //sabi ni sir hardcode na food and bev lang ang menu sub cat
            $sub_cats[1] = array('name'=>'FOOD','qty'=>0,'amount'=>0);
            $sub_cats[2] = array('name'=>'BEVERAGE','qty'=>0,'amount'=>0);

            $menu_net_total = 0;
            $menu_qty_total = 0;
            $item_net_total = 0;
            $item_qty_total = 0;
            $menus = array();
            $free_menus = array();
            $ids_used = array();
            if(count($ids) > 0){
                $select = 'trans_sales_menus.*,menus.menu_code,menus.menu_name,menus.cost as sell_price,menus.menu_cat_id as cat_id,menus.menu_sub_cat_id as sub_cat_id, menus.costing';
                $join = null;
                $join['menus'] = array('content'=>'trans_sales_menus.menu_id = menus.menu_id');
                $n_menu_res = array();
                // if($curr){
                //     $this->site_model->db = $this->load->database('default', TRUE);
                //     $n_menu_res = $this->site_model->get_tbl('trans_sales_menus',array('sales_id'=>$ids),array(),$join,true,$select);
                // }
                $this->site_model->db= $this->load->database('main', TRUE);
                $menu_res = $this->site_model->get_tbl('trans_sales_menus',array('sales_id'=>$ids,'pos_id'=>TERMINAL_ID),array(),$join,true,$select);
                // echo $this->site_model->db->last_query(); die();
                foreach ($menu_res as $ms) {
                    if(!in_array($ms->sales_id, $ids_used)){
                        $ids_used[] = $ms->sales_id;
                    }
                    if(!isset($menus[$ms->menu_id])){
                        $mn = array();
                        $mn['name'] = $ms->menu_name;
                        $mn['cat_id'] = $ms->cat_id;
                        $mn['menu_id'] = $ms->menu_id;
                        $mn['qty'] = $ms->qty;
                        $mn['sell_price'] = $ms->price;
                        $mn['cost_price'] = $ms->costing;
                        $mn['code'] = $ms->menu_code;
                        $mn['amount'] = $ms->price * $ms->qty;
                        $menus[$ms->menu_id] = $mn;
                    }
                    else{
                        $mn = $menus[$ms->menu_id];
                        $mn['qty'] += $ms->qty;
                        $mn['amount'] += $ms->price * $ms->qty;
                        $menus[$ms->menu_id] = $mn;
                    }

                    //pinabago ni sir dapat daw food and bev lang
                    if($ms->sub_cat_id != 2){
                        //food
                        $sub = $sub_cats[1];
                        $sub['qty'] += $ms->qty;
                        $sub['amount'] += $ms->price * $ms->qty;
                        $sub_cats[1] = $sub;
                    }else{
                        //bev
                        $sub = $sub_cats[2];
                        $sub['qty'] += $ms->qty;
                        $sub['amount'] += $ms->price * $ms->qty;
                        $sub_cats[2] = $sub;
                    }
                    // if(isset($sub_cats[$ms->sub_cat_id])){
                    //     $sub = $sub_cats[$ms->sub_cat_id];
                    //     $sub['qty'] += $ms->qty;
                    //     $sub['amount'] += $ms->price * $ms->qty;
                    //     $sub_cats[$ms->sub_cat_id] = $sub;
                    // }
                    if(isset($cats[$ms->cat_id])){
                        $cat = $cats[$ms->cat_id];
                        $cat['qty'] += $ms->qty;
                        $cat['amount'] += $ms->price * $ms->qty;
                        $cats[$ms->cat_id] = $cat;
                    }
                    $menu_net_total += $ms->price * $ms->qty;
                    $menu_qty_total += $ms->qty;
                    if($ms->free_user_id != "" && $ms->free_user_id != 0){
                        $free_menus[] = $ms;
                    }
                }
                // if(count($n_menu_res) > 0){
                //     foreach ($n_menu_res as $ms) {
                //         if(!in_array($ms->sales_id, $ids_used)){
                //             if(!isset($menus[$ms->menu_id])){
                //                 $mn = array();
                //                 $mn['name'] = $ms->menu_name;
                //                 $mn['cat_id'] = $ms->cat_id;
                //                 $mn['qty'] = $ms->qty;
                //                 $mn['amount'] = $ms->price * $ms->qty;
                //                 $mn['sell_price'] = $ms->sell_price;
                //                 $mn['cost_price'] = $ms->costing;
                //                 $mn['code'] = $ms->menu_code;
                //                 $menus[$ms->menu_id] = $mn;
                //             }
                //             else{
                //                 $mn = $menus[$ms->menu_id];
                //                 $mn['qty'] += $ms->qty;
                //                 $mn['amount'] += $ms->price * $ms->qty;
                //                 $menus[$ms->menu_id] = $mn;
                //             }
                //             //pinabago ni sir dapat daw food and bev lang
                //             if($ms->sub_cat_id != 2){
                //                 //food
                //                 $sub = $sub_cats[1];
                //                 $sub['qty'] += $ms->qty;
                //                 $sub['amount'] += $ms->price * $ms->qty;
                //                 $sub_cats[1] = $sub;
                //             }else{
                //                 //bev
                //                 $sub = $sub_cats[2];
                //                 $sub['qty'] += $ms->qty;
                //                 $sub['amount'] += $ms->price * $ms->qty;
                //                 $sub_cats[2] = $sub;
                //             }
                //             // if(isset($sub_cats[$ms->sub_cat_id])){
                //             //     $sub = $sub_cats[$ms->sub_cat_id];
                //             //     $sub['qty'] += $ms->qty;
                //             //     $sub['amount'] += $ms->price * $ms->qty;
                //             //     $sub_cats[$ms->sub_cat_id] = $sub;
                //             // }
                //             if(isset($cats[$ms->cat_id])){
                //                 $cat = $cats[$ms->cat_id];
                //                 $cat['qty'] += $ms->qty;
                //                 $cat['amount'] += $ms->price * $ms->qty;
                //                 $cats[$ms->cat_id] = $cat;
                //             }
                //             $menu_net_total += $ms->price * $ms->qty;
                //             $menu_qty_total += $ms->qty;
                //             if($ms->free_user_id != "" && $ms->free_user_id != 0){
                //                 $free_menus[] = $ms;
                //             }
                //         }
                //     }
                // }
            }
            $total_md = 0;
            $total_smd = 0;
            $mids_used = array();
            $mods = array();
            $sub_mods = array();
            if(count($ids) > 0){
                $n_menu_cat_sale_mods=array();
                // if($curr){
                //     $this->cashier_model->db = $this->load->database('default', TRUE);
                //     $n_menu_cat_sale_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$ids));
                // }
                $this->cashier_model->db = $this->load->database('main', TRUE);
                $menu_cat_sale_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$ids));
                foreach ($menu_cat_sale_mods as $res) {
                    if(!in_array($res->sales_id, $mids_used)){
                        $mids_used[] = $res->sales_id;
                    }
                    if(!isset($mods[$res->menu_id][$res->mod_id])){
                        $mods[$res->menu_id][$res->mod_id] = array(
                            'name'=>$res->mod_name,
                            'menu_id'=>$res->menu_id,
                            'price'=>$res->price,
                            'qty'=>$res->qty,
                            'total_amt'=>$res->qty * $res->price,
                        );
                    }
                    else{
                        $mod = $mods[$res->menu_id][$res->mod_id];
                        $mod['qty'] += $res->qty;
                        $mod['total_amt'] += $res->qty * $res->price;
                        $mods[$res->menu_id][$res->mod_id] = $mod;
                    }

                }
                // if(count($n_menu_cat_sale_mods) > 0){
                //     foreach ($n_menu_cat_sale_mods as $res) {
                //         if(!in_array($res->sales_id, $mids_used)){
                //             if(!isset($mods[$res->mod_id])){
                //                 $mods[$res->mod_id] = array(
                //                     'name'=>$res->mod_name,
                //                     'price'=>$res->price,
                //                     'qty'=>$res->qty,
                //                     'total_amt'=>$res->qty * $res->price,
                //                 );
                //             }
                //             else{
                //                 $mod = $mods[$res->mod_id];
                //                 $mod['qty'] += $res->qty;
                //                 $mod['total_amt'] += $res->qty * $res->price;
                //                 $mods[$res->mod_id] = $mod;
                //             }
                //         }
                //     }
                // }
                foreach ($mods as $menu_ids => $vv) {
                    foreach ($vv as $modid => $md) {
                        $total_md += $md['total_amt'];

                        // if(isset($md['submodifiers'])){
                        //     foreach ($md['submodifiers'] as $skey => $svalue) {
                        //         // foreach ($svalue as $mod_sub_id => $vals) {
                        //             $total_smd += $svalue['total_amt'];
                        //         // }
                        //     }
                        // }

                    }
                }



                $menu_cat_sale_submods = $this->cashier_model->get_trans_sales_menu_submodifiers_prints(null,array("trans_sales_menu_submodifiers.sales_id"=>$ids));
                // $sub_mods = array();
                // echo "<pre>",print_r($menu_cat_sale_submods),"</pre>";die();
                foreach ($menu_cat_sale_submods as $subm) {
                    // if($res->mod_id == $subm->mod_id){
                        
                        if(isset($sub_mods[$subm->mod_id][$subm->mod_sub_id])){
                            $row = $sub_mods[$subm->mod_id][$subm->mod_sub_id];
                            $row['total_amt'] += $subm->price * $subm->qty;
                            $row['qty'] += $subm->qty;

                            $sub_mods[$subm->mod_id][$subm->mod_sub_id] = $row;

                        }else{

                            $sub_mods[$subm->mod_id][$subm->mod_sub_id] = array(
                                'name'=>$subm->submod_name,
                                'price'=>$subm->price,
                                'qty'=>$subm->qty,
                                'mod_id'=>$subm->mod_id,
                                'total_amt'=>$subm->price * $subm->qty,
                                // 'qty'=>$subm->qty,
                            );
                        }


                    // }
                }

                foreach ($sub_mods as $mod_ids => $vv) {
                    foreach ($vv as $smodid => $smd) {
                        $total_smd += $smd['total_amt'];

                        // if(isset($md['submodifiers'])){
                        //     foreach ($md['submodifiers'] as $skey => $svalue) {
                        //         // foreach ($svalue as $mod_sub_id => $vals) {
                        //             $total_smd += $svalue['total_amt'];
                        //         // }
                        //     }
                        // }

                    }
                }


            }

            // echo "<pre>",print_r($sub_mods),"</pre>";die();

            #ITEMS
            if(count($ids) > 0){
                $select = 'trans_sales_items.*,items.code as item_code,items.name as item_name,items.cost as item_cost';
                $join = null;
                $join['items'] = array('content'=>'trans_sales_items.item_id = items.item_id');
                $n_item_res = array();
                // if($curr){
                //     $this->site_model->db = $this->load->database('default', TRUE);
                //     $n_item_res = $this->site_model->get_tbl('trans_sales_items',array('sales_id'=>$ids),array(),$join,true,$select);
                // }
                $this->site_model->db= $this->load->database('main', TRUE);
                $item_res = $this->site_model->get_tbl('trans_sales_items',array('sales_id'=>$ids),array(),$join,true,$select);
                $items = array();
                $itids_used = array();
                
                foreach ($item_res as $ms) {
                    if(!in_array($ms->sales_id, $itids_used)){
                        $itids_used[] = $ms->sales_id;
                    }
                    // if(!isset($menus[$ms->item_id])){
                        $mn = array();
                        $mn['name'] = $ms->item_name;
                        $mn['qty'] = $ms->qty;
                        $mn['price'] = $ms->price;
                        $mn['code'] = $ms->item_code;
                        $mn['amount'] = $ms->price * $ms->qty;
                        $items[$ms->item_id] = $mn;
                    // }
                    // else{
                        $mn = $items[$ms->item_id];
                        $mn['qty'] += $ms->qty;
                        $mn['amount'] += $ms->price * $ms->qty;
                        $items[$ms->item_id] = $mn;
                    // }
                    $item_net_total += $ms->price * $ms->qty;
                    $item_qty_total += $ms->qty;
                }
                // if(count($n_item_res) > 0){
                //     foreach ($n_item_res as $ms) {
                //         if(!in_array($ms->sales_id, $itids_used)){
                //             // if(!isset($items[$ms->item_id])){
                //                 $mn = array();
                //                 $mn['name'] = $ms->item_name;
                //                 $mn['qty'] = $ms->qty;
                //                 $mn['price'] = $ms->price;
                //                 $mn['code'] = $ms->item_code;
                //                 $mn['amount'] = $ms->price * $ms->qty;
                //                 $items[$ms->item_id] = $mn;
                //             // }
                //             // else{
                //                 $mn = $items[$ms->item_id];
                //                 $mn['qty'] += $ms->qty;
                //                 $mn['amount'] += $ms->price * $ms->qty;
                //                 $items[$ms->item_id] = $mn;
                //             // }
                //             $item_net_total += $ms->price * $ms->qty;
                //             $item_qty_total += $ms->qty;
                //         }
                //     }
                // }    

            }  
            // var_dump($menu_net_total+$total_md+$item_net_total); die();
            // echo "<pre>",print_r($menus),"</pre>";die();
            return array('gross'=>$menu_net_total+$total_md+$item_net_total+$total_smd,'menu_total'=>$menu_net_total,'total_qty'=>$menu_qty_total,'menus'=>$menus,'cats'=>$cats,'sub_cats'=>$sub_cats,'mods_total'=>$total_md,'mods'=>$mods,'free_menus'=>$free_menus,'item_total'=>$item_net_total,'item_total_qty'=>$item_qty_total,'submods_total'=>$total_smd,'submods'=>$sub_mods);
        }
        public function charges_sales($ids=array(),$curr=false){
            $total_charges = 0;
            $charges = array();
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_charges.sales_id"] = $ids;
                $cargs["trans_sales_charges.pos_id"] = TERMINAL_ID;
                $n_cesults = array();
                // if($curr){
                //     $this->site_model->db = $this->load->database('default', TRUE);
                //     $n_cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);
                // }
                $this->site_model->db = $this->load->database('main', TRUE);
                $cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);

                foreach ($cesults as $ces) {
                    if(!in_array($ces->sales_id, $ids_used)){
                        $ids_used[] = $ces->sales_id;
                    }
                    if(!isset($charges[$ces->charge_id])){
                        $charges[$ces->charge_id] = array(
                            'amount'=>round($ces->amount,2),
                            'name'=>$ces->charge_name,
                            'code'=>$ces->charge_code,
                            'qty'=>1
                        );
                    }
                    else{
                        $ch = $charges[$ces->charge_id];
                        $ch['amount'] += round($ces->amount,2);
                        $ch['qty'] += 1;
                        $charges[$ces->charge_id] = $ch;
                    }
                    $total_charges += round($ces->amount,2);
                }
                // if(count($n_cesults) > 0){
                //     foreach ($n_cesults as $ces) {
                //         if(!in_array($ces->sales_id, $ids_used)){
                //             if(!isset($charges[$ces->charge_id])){
                //                 $charges[$ces->charge_id] = array(
                //                     'amount'=>round($ces->amount,2),
                //                     'name'=>$ces->charge_name,
                //                     'code'=>$ces->charge_code,
                //                     'qty'=>1
                //                 );
                //             }
                //             else{
                //                 $ch = $charges[$ces->charge_id];
                //                 $ch['amount'] += round($ces->amount,2);
                //                 $ch['qty'] += 1;
                //                 $charges[$ces->charge_id] = $ch;
                //             }
                //             $total_charges += round($ces->amount,2);
                //         }
                //     }
                // }
            }
            return array('total'=>$total_charges,'types'=>$charges);
        }
        // public function old_grand_net_total($date=""){
        //     $old_grand_total = 0;
        //     $ctr = 0;
        //     $args['trans_sales.datetime < '] = $date;
        //     $args['trans_sales.type_id'] = SALES_TRANS;
        //     $args['trans_sales.inactive'] = 0;
        //     $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        //     $args['trans_sales.terminal_id'] = TERMINAL_ID;
        //     $this->site_model->db = $this->load->database('main', TRUE);
        //     $result = $this->site_model->get_tbl('trans_sales',$args,array(),null,true,'sum(trans_sales.total_amount) as total');
        //     if(count($result) > 0){
        //         $old_grand_total += $result[0]->total;
        //     }
        //     $true_grand_total = $old_grand_total;
        //     $hargs['trans_sales.datetime <= '] = $date;
        //     $hargs['trans_sales.type_id'] = SALES_TRANS;
        //     $hargs['trans_sales.inactive'] = 0;
        //     $hargs["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        //     $hargs['trans_sales.pos_id'] = TERMINAL_ID;
        //     $joinh['trans_sales'] = array('content'=>'trans_sales_charges.sales_id = trans_sales.sales_id','mode'=>'left');
        //     $this->site_model->db = $this->load->database('main', TRUE);
        //     $result = $this->site_model->get_tbl('trans_sales_charges',$hargs,array(),$joinh,true,'sum(trans_sales_charges.amount) as total_charges');
        //     // echo $this->site_model->db->last_query();
        //     if(count($result) > 0){
        //         $old_grand_total -= $result[0]->total_charges;
        //     }
        //     $largs['trans_sales.datetime <= '] = $date;
        //     $largs['trans_sales.type_id'] = SALES_TRANS;
        //     $largs['trans_sales.inactive'] = 0;
        //     $largs["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        //     $largs['trans_sales.pos_id'] = TERMINAL_ID;
        //     $joinl['trans_sales'] = array('content'=>'trans_sales_local_tax.sales_id = trans_sales.sales_id','mode'=>'left');
        //     $this->site_model->db  = $this->load->database('main', TRUE);
        //     $result = $this->site_model->get_tbl('trans_sales_local_tax',$largs,array(),$joinl,true,'sum(trans_sales_local_tax.amount) as total_lt');
        //     if(count($result) > 0){
        //         $old_grand_total -= $result[0]->total_lt;
        //     }
        //     $cargs = array('read_type'=>Z_READ,'DATE(read_details.read_date) <= '=>date2Sql($date));
        //     // if($this->site_model->db->database == "dinemain")
        //     //     $cargs['read_details.pos_id'] = TERMINAL_ID;
        //     $ctrresult = $this->site_model->get_tbl('read_details',$cargs,array(),null,true,'id','read_date',null);
        //     foreach ($ctrresult as $res) {
        //         $ctr++;
        //     }
        //     return array('old_grand_total'=>$old_grand_total,'true_grand_total'=>$true_grand_total,'ctr'=>$ctr);
        // }
        public function old_grand_net_total($date="",$add_void=false){
            $old_grand_total = 0;
            $ctr = 0;

            $args['trans_sales.datetime < '] = $date;
            if(!$add_void)
                $args['trans_sales.type_id'] = SALES_TRANS;
            else{
                $args['trans_sales.type_id'] = array(SALES_TRANS,SALES_VOID_TRANS);
            }
            $args['trans_sales.inactive'] = 0;
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $args['trans_sales.terminal_id'] = TERMINAL_ID;
            if(HIDECHIT){    
                $args['trans_sales.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = "chit")'] = array('use'=>'where','val'=>null,'third'=>false);
            }
            if(PRODUCT_TEST){    
                $args['trans_sales.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = "producttest")'] = array('use'=>'where','val'=>null,'third'=>false);
            }
            // $joinp['trans_sales_payments'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id','mode'=>'left');
            $joinp = null;
            $this->site_model->db = $this->load->database('main', TRUE);
            $result = $this->site_model->get_tbl('trans_sales',$args,array(),$joinp,true,'sum(trans_sales.total_amount) as total');
            // echo $this->site_model->db->last_query(); die();
            if(count($result) > 0){
                $old_grand_total += $result[0]->total;
            }
            $true_grand_total = $old_grand_total;
            $hargs['trans_sales.datetime <= '] = $date;
            $hargs['trans_sales.type_id'] = SALES_TRANS;
            $hargs['trans_sales.inactive'] = 0;
            $hargs["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            // $hargs['trans_sales.pos_id'] = TERMINAL_ID;
            if(HIDECHIT){
                $hargs['trans_sales.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = "chit")'] = array('use'=>'where','val'=>null,'third'=>false);
            }
            if(PRODUCT_TEST){    
                $hargs['trans_sales.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = "producttest")'] = array('use'=>'where','val'=>null,'third'=>false);
            }
            // $hargs['trans_sales_payments.payment_type != "chit"'] = array('use'=>'where','val'=>null,'third'=>false);
            $joinh['trans_sales'] = array('content'=>'trans_sales_charges.sales_id = trans_sales.sales_id','mode'=>'left');
            // $joinh['trans_sales_payments'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id','mode'=>'left');

            $this->site_model->db = $this->load->database('main', TRUE);
            $result = $this->site_model->get_tbl('trans_sales_charges',$hargs,array(),$joinh,true,'sum(trans_sales_charges.amount) as total_charges');
            if(count($result) > 0){
                $old_grand_total -= $result[0]->total_charges;
            }
            $largs['trans_sales.datetime <= '] = $date;
            $largs['trans_sales.type_id'] = SALES_TRANS;
            $largs['trans_sales.inactive'] = 0;
            $largs["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            // $largs['trans_sales.pos_id'] = TERMINAL_ID;
            if(HIDECHIT){
                $largs['trans_sales.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = "chit")'] = array('use'=>'where','val'=>null,'third'=>false);
            }
            if(PRODUCT_TEST){    
                $largs['trans_sales.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = "producttest")'] = array('use'=>'where','val'=>null,'third'=>false);
            }
            $joinl['trans_sales'] = array('content'=>'trans_sales_local_tax.sales_id = trans_sales.sales_id','mode'=>'left');
            // $joinl['trans_sales_payments'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id','mode'=>'left');
            $this->site_model->db  = $this->load->database('main', TRUE);
            $result = $this->site_model->get_tbl('trans_sales_local_tax',$largs,array(),$joinl,true,'sum(trans_sales_local_tax.amount) as total_lt');
            if(count($result) > 0){
                $old_grand_total -= $result[0]->total_lt;
            }

            if(MALL_ENABLED && MALL == 'megaworld'){
                //for gc excess
                $gcargs['trans_sales.datetime <= '] = $date;
                $gcargs['trans_sales.type_id'] = SALES_TRANS;
                $gcargs['trans_sales.inactive'] = 0;
                $gcargs["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
                // $gcargs['trans_sales.pos_id'] = TERMINAL_ID;
                $gcargs['trans_sales_payments.payment_type'] = 'gc';
                if(HIDECHIT){
                    $gcargs['trans_sales.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = "chit")'] = array('use'=>'where','val'=>null,'third'=>false);
                }
                if(PRODUCT_TEST){    
                    $gcargs['trans_sales.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = "producttest")'] = array('use'=>'where','val'=>null,'third'=>false);
                }
                $joingc['trans_sales'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id','mode'=>'left');
                // $joinl['trans_sales_payments'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id','mode'=>'left');
                $this->site_model->db  = $this->load->database('main', TRUE);
                $result = $this->site_model->get_tbl('trans_sales_payments',$gcargs,array(),$joingc,true,'sum(trans_sales_payments.amount) as total_amount, sum(trans_sales_payments.to_pay) as total_topay');
                $gc_excess = 0;
                if(count($result) > 0){
                    $gc_excess = $result[0]->total_amount - $result[0]->total_topay;
                }
                // $old_grand_total += $gc_excess;
                $true_grand_total += $gc_excess;
            }



            $cargs = array('read_type'=>Z_READ,'DATE(read_details.read_date) <= '=>date2Sql($date));
            // if($this->site_model->db->database == "dinemain")
            //     $cargs['read_details.pos_id'] = TERMINAL_ID;

            $ctrresult = $this->site_model->get_tbl('read_details',$cargs,array(),null,true,'id','read_date',null);
            foreach ($ctrresult as $res) {
                $ctr++;
            }
            return array('old_grand_total'=>$old_grand_total,'true_grand_total'=>$true_grand_total,'ctr'=>$ctr);
        }
        //// END JED

        public function event_logs_ui(){
            $this->load->model('dine/reports_model');
            $this->load->helper('dine/reports_helper');
            $data = $this->syter->spawn('act_logs');
            $data['page_title'] = 'Event Logs';
            $now = $this->site_model->get_db_now();
            $args["Date(event_logs.datetime) = Date('".date2Sql($now)."')"] = array('use'=>'where','val'=>null,'third'=>false);
            $logs = $this->reports_model->get_event_logs(null,$args);
            $data['code'] = event_logs_pg($logs);
            // $data['add_js'] = array('js/plugins/timepicker/bootstrap-timepicker.min.js');
            // $data['add_css'] = array('css/timepicker/bootstrap-timepicker.min.css');
            $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/reports.php';
            $data['use_js'] = 'eventLogsJs';
            $this->load->view('page',$data);
        }
        public function event_logs_rep_excel(){
            $this->load->library('Excel');
            $sheet = $this->excel->getActiveSheet();
            $branch_details = $this->setup_model->get_branch_details();
            $branch = array();
            $rc=1;
            $title_name = "Event Logs";
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
                    'email' => $bv->email,
                    'website' => $bv->website,
                    'store_open' => $bv->store_open,
                    'store_close' => $bv->store_close,
                );
            }
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue($branch['name']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue($branch['address']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('TIN #'.$branch['tin']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('ACCRDN #'.$branch['accrdn']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('PERMIT #'.$branch['permit_no']);
            // $rc++;
            // $sheet->mergeCells('A'.$rc.':P'.$rc);
            // $sheet->getCell('A'.$rc)->setValue('SN #'.$branch['serial']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue('MIN #'.$branch['machine_no']);
            $rc++;
            $sheet->mergeCells('A'.$rc.':P'.$rc);
            $sheet->getCell('A'.$rc)->setValue($title_name);

            $rc++;
            $rc++;
            $sheet->getStyle("A".$rc.":D".$rc)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$rc.':'.'D'.$rc)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->getStyle('A'.$rc.':'.'D'.$rc)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('A'.$rc.':'.'D'.$rc)->getFill()->getStartColor()->setRGB('29bb04');
            $sheet->getStyle('A1:'.'D'.$rc)->getFont()->setBold(true);

            $sheet->getCell('A'.$rc)->setValue('Datetime');
            $sheet->getCell('B'.$rc)->setValue('Username');
            $sheet->getCell('C'.$rc)->setValue('Module/Report');
            $sheet->getCell('D'.$rc)->setValue('Action Done');
            
            $rc++;
            #DETAILS
                $date_from = null;
                $date_to = null;
                $user = null;
                if(isset($_GET['daterange']) && $_GET['daterange'] != ""){
                    $dates = explode(" to ",$_GET['daterange']);
                    $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
                    $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
                }
                if(isset($_GET['user']) && $_GET['user'] != ""){
                    $user = $_GET['user'];
                }
                if($date_from != "" && $date_to != "")
                    $args["event_logs.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                if($user != "")
                    $args["event_logs.user_id"] = $user;

                $logs = $this->reports_model->get_event_logs(null,$args);
                foreach ($logs as $res) {
                    // $sheet->getStyle("A".$rc.":D".$rc)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    // $sheet->getStyle('A'.$rc.':'.'D'.$rc)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $sheet->getCell('A'.$rc)->setValue($res->datetime);
                    $sheet->getCell('B'.$rc)->setValue($res->fname." ".$res->mname." ".$res->lname." ".$res->suffix);
                    $sheet->getCell('C'.$rc)->setValue($res->module_report);
                    $sheet->getCell('D'.$rc)->setValue($res->action_done);
                    $rc++;
                }
            

            ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$title_name.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
        } 

        public function event_logs_rep_pdf(){
        //diretso excel na
        ini_set('memory_limit', '-1');
        set_time_limit(3600);
        

        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Event Logs');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');
        $date = $this->input->get('daterange');
        $datesx = explode(" to ",$date);
        $start_month = $datesx[0];
        $end_month = $datesx[1];


        $details = $this->setup_model->get_branch_details();
            
        $open_time = $details[0]->store_open;
        $close_time = $details[0]->store_close;

        // set font
        $pdf->SetFont('helvetica', 'B', 12);

        // add a page
        $pdf->AddPage();

        $pdf->ln(2);
        $pdf->SetFont('helvetica', 'B', 15);
        $pdf->cell(150,0,'Event Logs',0,0,'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->cell(150,0,'Print Datetime '.date('m/d/Y H:i:s A'),0,0,'L');

        $pdf->ln(13);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->cell(50,0,'Datetime','B',0,'L');
        $pdf->cell(50,0,'Username','B',0,'L');
        $pdf->cell(50,0,'Module/Report','B',0,'R');
        $pdf->cell(50,0,'Action Done','B',0,'C');
        

        $pdf->ln(10);
        $date_from = null;
        $date_to = null;
        $user = null;
        if(isset($_GET['daterange']) && $_GET['daterange'] != ""){
            $dates = explode(" to ",$_GET['daterange']);
            $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
        }
        if(isset($_GET['user']) && $_GET['user'] != ""){
            $user = $_GET['user'];
        }
        if($date_from != "" && $date_to != "")
            $args["event_logs.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        if($user != "")
            $args["event_logs.user_id"] = $user;

        $logs = $this->reports_model->get_event_logs(null,$args);
        foreach ($logs as $res) {
            $pdf->SetFont('helvetica', '', 9);
            $pdf->cell(50,0,$res->datetime,0,0,'L');
            $pdf->cell(50,0,$res->fname." ".$res->mname." ".$res->lname." ".$res->suffix,0,0,'L');
            $pdf->cell(50,0,$res->module_report,0,0,'R');
            $pdf->cell(50,0,$res->action_done,0,0,'C');
            $pdf->ln(5);
        }

        //Close and output PDF document
        $pdf->Output('Event logs.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+          
    }

}