<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shift extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('site/site_model');
        $this->load->model('dine/clock_model');
        $this->load->helper('dine/clock_helper');
        $this->load->helper('dine/shift_helper');
        $this->load->helper('core/on_screen_key_helper');
    }
    public function index(){
        $data = $this->syter->spawn(null);
        $now = $this->site_model->get_db_now();
        $data['code'] = shiftPage($now);
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/shift.php';
        $data['use_js'] = 'shiftJs';
        $data['noNavbar'] = true; /*displays the navbar. Uncomment this line to hide the navbar.*/
        $this->load->view('cashier',$data);
    }
    public function time(){
        $data = $this->syter->spawn(null);
        $now = $this->site_model->get_db_now();
        $user = $this->session->userdata('user');
        $user_id = $user['id'];
        $shift = $this->clock_model->get_curr_shift(date2Sql($now),$user_id);
        $get_dtr =  $this->clock_model->get_user_dtrs($user_id,date('Y-m-01'),date('Y-m-t'));
        $data['code'] = timePage($shift,$get_dtr,$now);
        $data['load_js'] = 'dine/shift.php';
        $data['use_js'] = 'timeJs';
        $this->load->view('load',$data);
    }
    public function start_amount(){
        if(PRINT_VERSION == 'V2'){
           try{
            shell_exec("cmd /c ".restart_printer."");  // restart printer here
            
           }catch (Exception $e) {
            echo "Couldn't restart printers , please make sure printers are turned on. ";
            }
        }
        $data = $this->syter->spawn();
        $data['code'] = startAmountPage();
        $data['add_css'] = array('css/onscrkeys.css','css/virtual_keyboard.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/shift.php';
        $data['use_js'] = 'startAmountJs';
        $this->load->view('load',$data);   
    }
    public function timeIn(){
        $amount = 0;
        $error  = "";
        $amt1 = $amt2 = $amt3 = $amt4 = $amt5 = $amt6 = $amt7 = $amt8 = $amt9 = $amt10 = $amt11 = 0;
        // echo "<pre>",print_r(number_format($this->input->post('amt1'))),"</pre>";die();
        if($this->input->post('amount')){
            $amount = $this->input->post('amount');
        }
        if($this->input->post('amt1')){
            $amt1 = number_format($this->input->post('amt1'));
        }
        if($this->input->post('amt2')){
            $amt2 = number_format($this->input->post('amt2'));
        }
        if($this->input->post('amt3')){
            $amt3 = number_format($this->input->post('amt3'));
        }
        if($this->input->post('amt4')){
            $amt4 = number_format($this->input->post('amt4'));
        }
        if($this->input->post('amt5')){
            $amt5 = number_format($this->input->post('amt5'));
        }
        if($this->input->post('amt6')){
            $amt6 = number_format($this->input->post('amt6'));
        }
        if($this->input->post('amt7')){
            $amt7 = number_format($this->input->post('amt7'));
        }
        if($this->input->post('amt8')){
            $amt8 = number_format($this->input->post('amt8'));
        }
        if($this->input->post('amt9')){
            $amt9 = number_format($this->input->post('amt9'));
        }
        if($this->input->post('amt10')){
            $amt10 = number_format($this->input->post('amt10'));
        }
        if($this->input->post('amt11')){
            $amt11 = number_format($this->input->post('amt11'));
        }

        $user = $this->session->userdata('user');
        $user_id = $user['id'];
        $now = $this->site_model->get_db_now('sql');
        $items = array(
            'user_id'=>$user_id,
            'check_in'=>$now,
            'terminal_id'=>TERMINAL_ID
        );
        $log_user = $this->session->userdata('user');
        $shift_id = $this->clock_model->insert_clockin($items);
        $items_cash = array(
            'shift_id'=>$shift_id,
            'amount'=>$amount,
            'user_id'=>$user_id,
            'trans_date'=>$now
        );
        $cash_id = $this->clock_model->insert_cashin($items_cash);

        if($amt1 != 0){
            $amt1_arr = array(
                'shift_id'=>$shift_id,
                'amount'=>1000,
                'qty'=>$amt1,
                'trans_date'=>$now
            );
            $this->clock_model->insert_shift_deno($amt1_arr);
        }
        if($amt2 != 0){
            $amt2_arr = array(
                'shift_id'=>$shift_id,
                'amount'=>500,
                'qty'=>$amt2,
                'trans_date'=>$now
            );
            $this->clock_model->insert_shift_deno($amt2_arr);
        }
        if($amt3 != 0){
            $amt3_arr = array(
                'shift_id'=>$shift_id,
                'amount'=>200,
                'qty'=>$amt3,
                'trans_date'=>$now
            );
            $this->clock_model->insert_shift_deno($amt3_arr);
        }
        if($amt4 != 0){
            $amt4_arr = array(
                'shift_id'=>$shift_id,
                'amount'=>100,
                'qty'=>$amt4,
                'trans_date'=>$now
            );
            $this->clock_model->insert_shift_deno($amt4_arr);
        }
        if($amt5 != 0){
            $amt5_arr = array(
                'shift_id'=>$shift_id,
                'amount'=>50,
                'qty'=>$amt5,
                'trans_date'=>$now
            );
            $this->clock_model->insert_shift_deno($amt5_arr);
        }
        if($amt6 != 0){
            $amt6_arr = array(
                'shift_id'=>$shift_id,
                'amount'=>20,
                'qty'=>$amt6,
                'trans_date'=>$now
            );
            $this->clock_model->insert_shift_deno($amt6_arr);
        }
        if($amt7 != 0){
            $amt7_arr = array(
                'shift_id'=>$shift_id,
                'amount'=>10,
                'qty'=>$amt7,
                'trans_date'=>$now
            );
            $this->clock_model->insert_shift_deno($amt7_arr);
        }
        if($amt8 != 0){
            $amt8_arr = array(
                'shift_id'=>$shift_id,
                'amount'=>5,
                'qty'=>$amt8,
                'trans_date'=>$now
            );
            $this->clock_model->insert_shift_deno($amt8_arr);
        }
        if($amt9 != 0){
            $amt9_arr = array(
                'shift_id'=>$shift_id,
                'amount'=>1,
                'qty'=>$amt9,
                'trans_date'=>$now
            );
            $this->clock_model->insert_shift_deno($amt9_arr);
        }
        if($amt10 != 0){
            $amt10_arr = array(
                'shift_id'=>$shift_id,
                'amount'=>0.25,
                'qty'=>$amt10,
                'trans_date'=>$now
            );
            $this->clock_model->insert_shift_deno($amt10_arr);
        }
        if($amt11 != 0){
            $amt11_arr = array(
                'shift_id'=>$shift_id,
                'amount'=>0.1,
                'qty'=>$amt11,
                'trans_date'=>$now
            );
            $this->clock_model->insert_shift_deno($amt11_arr);
        }

        $this->logs_model->add_logs('Shift',$log_user['id'],$log_user['full_name']." Started Shift.",$shift_id);
        $this->logs_model->add_logs('Drawer',$log_user['id'],$log_user['full_name']." Cash in ".$amount,null);
        echo json_encode(array('error'=>$error));
    }
    public function shift_show_denominations(){
        $this->load->model('dine/settings_model');
        $deno = $this->settings_model->get_denominations();
        $ids = array();
        $cart = sess('count_cart');
        foreach ($deno as $res) {
        // echo "<pre>",print_r($res),"</pre>";die();
            $qty = 0;
            foreach ($cart as $key => $row) {
                if($row['type'] == 'cash'){
                    if($row['ref'] == 'cash'){
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
}