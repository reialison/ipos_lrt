<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/reads.php");
class Miaa extends Reads {
	public function __construct(){
		parent::__construct();
		$this->load->helper('dine/miaa_helper');
		$this->load->model('dine/cashier_model');
		$this->load->model('core/trans_model');
	}
	public function index(){
        $data = $this->syter->spawn(null);
        $data['code'] = miaaPage();
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/miaa.php';
        $data['use_js'] = 'mainPageJs';
        $this->load->view('load',$data);
	}
	public function daily_files(){
		$data = $this->syter->spawn(null);
		$data['code'] = dailyFilesPage();
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/miaa.php';
		$data['use_js'] = 'dailyFilesJS';
		$this->load->view('load',$data);
	}
	public function settings(){
		$data = $this->syter->spawn(null);
		$objs = $this->site_model->get_tbl('miaa');
		$obj = array();
		if(count($objs) > 0){
			$obj = $objs[0];			
		}
		$data['code'] = settingsPage($obj);
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/miaa.php';
		$data['use_js'] = 'settingsJS';
		$this->load->view('load',$data);	
	}
	public function settings_db(){
		$this->load->model('dine/main_model');
		$items = array(
			'tenant_code' => $this->input->post('tenant_code'),
			'sales_type' => $this->input->post('sales_type'),
			'file_path'   => $this->input->post('file_path'),
		);
		$id = $this->input->post('miaa');
		$this->site_model->update_tbl('miaa','id',$items,$id);
		$this->main_model->update_tbl('miaa','id',$items,$id);
		$msg = "Updated Settings.";
		echo json_encode(array('id'=>$id,'msg'=>$msg));
	}
	public function get_file(){
		$date = $this->input->post('file_date');
		
		$rargs["DATE(read_details.read_date) = DATE('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
		$rargs["read_type"] = 2;
		$select = "read_details.*";
		$this->site_model->db = $this->load->database('default', TRUE);
		$results = $this->site_model->get_tbl('read_details',$rargs,array('scope_from'=>'asc'),"",true,$select);
		$ctr = 1;
		foreach ($results as $res) {
			$zread = $res->id;
			$ctr = $res->ctr;
		}

		$mon = array('01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'A','11'=>'B','12'=>'C');
		$mall_db = $this->site_model->get_tbl('miaa');
		$mall = array('tenant_code'=>$mall_db[0]->tenant_code,'sales_type'=>$mall_db[0]->sales_type);
        $file_path = filepathisize('C:/MIAA/');
		// $file_path = filepathisize($mall_db[0]->file_path);
		$year = date('Y',strtotime($date));
        // $file_path .= $year."/";
        $eod = $this->old_grand_net_total($date);
		$sales_file = $file_path."S".substr($mall['tenant_code'],0,4).TERMINAL_NUMBER.$eod['ctr'].".".$mon[date('m',strtotime($date))].date('d',strtotime($date));
        $hourly_file = $file_path."H".substr($mall['tenant_code'],0,4).TERMINAL_NUMBER.$eod['ctr'].".".$mon[date('m',strtotime($date))].date('d',strtotime($date));
		// $disc_file = $file_path."D".substr($mall['tenant_code'],0,4).TERMINAL_NUMBER.$ctr.".".$mon[date('m',strtotime($date))].date('d',strtotime($date));
		$disc= "";
		if(file_exists($sales_file)){
			$fh = fopen($sales_file, 'r');
			$theData = fread($fh, filesize($sales_file));
			fclose($fh);
			$sales = "<pre>".$theData."</pre>";
		}
		else{
			$sales = "<center> file not found. </center>";
		}
		if(file_exists($hourly_file)){
			$fh = fopen($hourly_file, 'r');
			$theData = fread($fh, filesize($hourly_file));
			fclose($fh);
			$hour = "<pre>".$theData."</pre>";
		}
		else{
			$hour = "<center> file not found. </center>";
		}
		// if(file_exists($disc_file)){
		// 	if(filesize($disc_file) > 0){
		// 		$fh = fopen($disc_file, 'r');
		// 		$theData = fread($fh, filesize($disc_file));
		// 		fclose($fh);
		// 		$disc = "<pre>".$theData."</pre>";
		// 	}else{
		// 		$disc = "<pre>  </pre>";
		// 	}


			
		// }
		// else{
		// 	$disc = "<center> file not found. </center>";
		// }
		echo json_encode(array('sales'=>$sales,'hour'=>$hour,'disc'=>$disc));
	}
	public function regen_file(){
		$date = $this->input->post('file_date');
		
		$today = date('m/d/Y');


        if($date == $today){
            $curr = true;
        }else{
            $curr = false;
        }

        $this->miaa_file_regen($date,$curr);
            

        // echo $date.'---ssss';

		$rargs["DATE(read_details.read_date) = DATE('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
		$rargs["read_type"] = 2;
		$select = "read_details.*";
		$this->site_model->db = $this->load->database('default', TRUE);
		$results = $this->site_model->get_tbl('read_details',$rargs,array('scope_from'=>'asc'),"",true,$select);
		$zread = 0;
		foreach ($results as $res) {
			$zread = $res->id;
			$read_date = $res->read_date;
		}
		if($zread != 0){
			$reg = $this->miaa_xfile_regen($zread,true,$date);
			echo json_encode(array('msg'=>$reg['msg'],'error'=>$reg['error']));
		}
		else{
			echo json_encode(array('msg'=>'No zread found on this date.','error'=>1));
		}
	}

	public function get_order_miaa($asJson=true,$sales_id=null){
            /*
             * -------------------------------------------
             *   Load receipt data
             * -------------------------------------------
            */
            $this->load->model('dine/cashier_model');
            $orders = $this->cashier_model->get_trans_sales($sales_id);
            $order = array();
            $details = array();
            $details2 = array();
            foreach ($orders as $res) {
                $order = array(
                    "sales_id"=>$res->sales_id,
                    'ref'=>$res->trans_ref,
                    "type"=>$res->type,
                    "table_id"=>$res->table_id,
                    "table_name"=>$res->table_name,
                    "guest"=>$res->guest,
                    "serve_no"=>$res->serve_no,
                    "user_id"=>$res->user_id,
                    "customer_id"=>$res->customer_id,
                    "name"=>$res->username,
                    "terminal_id"=>$res->terminal_id,
                    "terminal_name"=>$res->terminal_name,
                    "terminal_code"=>$res->terminal_code,
                    "shift_id"=>$res->shift_id,
                    "datetime"=>$res->datetime,
                    "update_date"=>$res->update_date,
                    "amount"=>$res->total_amount,
                    "balance"=>round($res->total_amount,2) - round($res->total_paid,2),
                    "paid"=>$res->paid,
                    "printed"=>$res->printed,
                    "inactive"=>$res->inactive,
                    "waiter_id"=>$res->waiter_id,
                    "void_ref"=>$res->void_ref,
                    "reason"=>$res->reason,
                    "waiter_name"=>ucwords(strtolower($res->waiterfname." ".$res->waitermname." ".$res->waiterlname." ".$res->waitersuffix)),
                    "waiter_username"=>ucwords(strtolower($res->waiterusername))
                    // "pay_type"=>$res->pay_type,
                    // "pay_amount"=>$res->pay_amount,
                    // "pay_ref"=>$res->pay_ref,
                    // "pay_card"=>$res->pay_card,
                );
            }
            $order_menus = $this->cashier_model->get_trans_sales_menus(null,array("trans_sales_menus.sales_id"=>$sales_id));
            $order_items = $this->cashier_model->get_trans_sales_items(null,array("trans_sales_items.sales_id"=>$sales_id));
            $order_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$sales_id));
            $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$sales_id));
            $sales_tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$sales_id));
            $sales_payments = $this->cashier_model->get_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$sales_id));
            $sales_no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$sales_id));
            $sales_zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$sales_id));
            $sales_charges = $this->cashier_model->get_trans_sales_charges(null,array("trans_sales_charges.sales_id"=>$sales_id));
            $sales_local_tax = $this->cashier_model->get_trans_sales_local_tax(null,array("trans_sales_local_tax.sales_id"=>$sales_id));
            $pays = array();
            foreach ($sales_payments as $py) {
                $pays[$py->payment_id] = array(
                        "sales_id"      => $py->sales_id,
                        "payment_type"  => $py->payment_type,
                        "amount"        => $py->amount,
                        "to_pay"        => $py->to_pay,
                        "reference"     => $py->reference,
                        "card_type"     => $py->card_type,
                        "card_number"   => $py->card_number,
                        "approval_code"   => $py->approval_code,
                        "user_id"       => $py->user_id,
                        "datetime"      => $py->datetime,
                    );
            }
            foreach ($order_menus as $men) {
                $details[$men->line_id] = array(
                    "id"=>$men->sales_menu_id,
                    "menu_id"=>$men->menu_id,
                    "name"=>$men->menu_name,
                    "code"=>$men->menu_code,
                    "price"=>$men->price,
                    "qty"=>$men->qty,
                    "no_tax"=>$men->no_tax,
                    "discount"=>$men->discount,
                    "remarks"=>$men->remarks,
                    "free_user_id"=>$men->free_user_id,
                    "nocharge"=>$men->nocharge,
                    "kitchen_slip_printed"=>$men->kitchen_slip_printed
                );
                $mods = array();
                foreach ($order_mods as $mod) {
                    if($mod->line_id == $men->line_id){
                        $mods[$mod->sales_mod_id] = array(
                            "id"=>$mod->mod_id,
                            "sales_mod_id"=>$mod->sales_mod_id,
                            "mod_group_id"=>$mod->mod_group_id,
                            "line_id"=>$mod->line_id,
                            "name"=>$mod->mod_name,
                            "price"=>$mod->price,
                            "qty"=>$mod->qty,
                            "discount"=>$mod->discount,
                            "kitchen_slip_printed"=>$mod->kitchen_slip_printed
                        );
                    }
                }
                $details[$men->line_id]['modifiers'] = $mods;
            }

            //for new os print
            foreach ($order_menus as $men) {

                if(isset($details2[$men->menu_id])){
                    // $details2[$men->menu_id]['qty'] += $men->qty;
                    // if($details2[$men->menu_id]['remarks'] != ""){
                    //     $details2[$men->menu_id]['remarks'] .= ', '.$men->remarks;
                    // }else{
                    //     $details2[$men->menu_id]['remarks'] = $men->remarks;   
                    // }
                    $dets = $details2[$men->menu_id]['dets'];
                    $dets[$men->line_id] = array(
                        "qty"=>$men->qty,
                        "remarks"=>$men->remarks,
                        "line_id"=>$men->line_id,
                        "id"=>$men->sales_menu_id,
                        "kitchen_slip_printed"=>$men->kitchen_slip_printed 
                    );
                    $details2[$men->menu_id]['dets'] = $dets;

                }else{
                    $details2[$men->menu_id] = array(
                        "menu_id"=>$men->menu_id,
                        "name"=>$men->menu_name,
                        "code"=>$men->menu_code,
                        "price"=>$men->price
                    );
                    $dets = array();
                    $dets[$men->line_id] = array(
                        "qty"=>$men->qty,
                        "remarks"=>$men->remarks,
                        "line_id"=>$men->line_id,
                        "id"=>$men->sales_menu_id,
                        "kitchen_slip_printed"=>$men->kitchen_slip_printed 
                    );
                    $details2[$men->menu_id]['dets'] = $dets;
                }

                $mods = array();
                foreach ($order_mods as $mod) {
                    if($mod->line_id == $men->line_id){
                        $mods[$mod->sales_mod_id] = array(
                            "id"=>$mod->mod_id,
                            "sales_mod_id"=>$mod->sales_mod_id,
                            "mod_group_id"=>$mod->mod_group_id,
                            "line_id"=>$mod->line_id,
                            "name"=>$mod->mod_name,
                            "price"=>$mod->price,
                            "qty"=>$mod->qty,
                            "discount"=>$mod->discount,
                            "kitchen_slip_printed"=>$mod->kitchen_slip_printed
                        );
                    }
                }
                // $details[$men->line_id]['modifiers'] = $mods;
                $details2[$men->menu_id]['dets'][$men->line_id]['modifiers'] = $mods;
            }


            // echo "<pre>",print_r($details2),"</pre>";die();


            foreach ($order_items as $men){
                $details[$men->line_id] = array(
                    "id"=>$men->sales_item_id,
                    "menu_id"=>$men->item_id,
                    "name"=>$men->name,
                    "code"=>$men->code,
                    "price"=>$men->price,
                    "qty"=>$men->qty,
                    "no_tax"=>$men->no_tax,
                    "discount"=>$men->discount,
                    "remarks"=>$men->remarks,
                    "nocharge"=>$men->nocharge,
                    "retail"=>1
                );
            }
            ### CHANGED #############
            $discounts = array();
            $persons = array();
            foreach ($sales_discs as $dc) {
                $discounts[$dc->disc_code] = array(
                        "no_tax"  => $dc->no_tax,
                        "guest" => $dc->guest,
                        "disc_rate" => $dc->disc_rate,
                        "disc_id" => $dc->disc_id,
                        "disc_code" => $dc->disc_code,
                        "disc_type" => $dc->type,
                        "fix" => $dc->fix,
                        "persons" => array()
                );
            }
            foreach ($sales_discs as $dc) {
                $pcode = $dc->code;
                $bday = "";
                if($dc->bday != "")
                    $bday = sql2Date($dc->bday);
                $person = array(
                    "name"  => $dc->name,
                    "code"  => $dc->code,
                    "bday"  => $bday,
                    "amount" => $dc->amount,
                    "disc_rate" => $dc->disc_rate,
                );
                if(isset($discounts[$dc->disc_code])){
                    $dscp =  $discounts[$dc->disc_code]['persons'];
                    $dscp[$pcode] = $person;
                    $discounts[$dc->disc_code]['persons'] = $dscp;
                }
            }
            ### CHANGED #############
            $tax = array();
            foreach ($sales_tax as $tx) {
                $tax[$tx->sales_tax_id] = array(
                        "sales_id"  => $tx->sales_id,
                        "name"  => $tx->name,
                        "rate" => $tx->rate,
                        "amount" => $tx->amount
                    );
            }
            $no_tax = array();
            foreach ($sales_no_tax as $nt) {
                $no_tax[$nt->sales_no_tax_id] = array(
                    "sales_id" => $nt->sales_id,
                    "amount" => $nt->amount,
                );
            }
            $zero_rated = array();
            foreach ($sales_zero_rated as $zt) {
                $zero_rated[$zt->sales_zero_rated_id] = array(
                    "sales_id" => $zt->sales_id,
                    "amount" => $zt->amount,
                );
            }
            $local_tax = array();
            foreach ($sales_local_tax as $lt) {
                $local_tax[$lt->sales_local_tax_id] = array(
                    "sales_id" => $lt->sales_id,
                    "amount" => $lt->amount,
                );
            }
            $charges = array();
            foreach ($sales_charges as $ch) {
                $charges[$ch->charge_id] = array(
                        "name"  => $ch->charge_name,
                        "code"  => $ch->charge_code,
                        "amount"  => $ch->rate,
                        "absolute" => $ch->absolute,
                        "total_amount" => $ch->amount
                    );
            }
            if($asJson)
                echo json_encode(array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"taxes"=>$tax,"no_tax"=>$no_tax,"zero_rated"=>$zero_rated,"payments"=>$pays,"charges"=>$charges,"local_tax"=>$local_tax,"details2"=>$details2));
            else
                return array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"taxes"=>$tax,"no_tax"=>$no_tax,"zero_rated"=>$zero_rated,"payments"=>$pays,"charges"=>$charges,"local_tax"=>$local_tax,"details2"=>$details2);
        }
}