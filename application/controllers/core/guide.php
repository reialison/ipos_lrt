<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (realpath(dirname(__FILE__) . '/..')."/dine/prints.php");
class Guide extends Prints {
    var $data = null;
    public function __construct(){
        parent::__construct();
        $this->load->helper('core/guide_helper');  
        $this->load->model('dine/cashier_model'); 
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/menu_model');
        $this->load->model('dine/clock_model');
        $this->load->model('dine/settings_model');
        $this->load->model('dine/setup_model');
        $this->load->model('third_party/Migrator_model');
        $this->load->helper('core/string_helper');     
    }
    public function guide_form($ref=null){
        $this->data['code'] = makeguideform();
        $this->load->view('load',$this->data);
    }
    public function guide_cash_count(){
        $data = $this->syter->spawn('Guide');
        // $today = $this->site_model->get_db_now();
        $data['page_title'] = fa(' icon-question').' How to process end of Shift (X-Reading)';
        $data['code'] = makeguidecashcount();
        // $data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/guide.php';
        $data['use_js'] = 'managerJs';
        $this->load->view('directions',$data);
        $user = $this->session->userdata('user');
        $this->manager_model->add_event_logs($user['id'],"PROCESS SHIFT CLOSE","X_READ");
    }
    public function guide_end_shift(){
        $data = $this->syter->spawn('Guide');
        // $today = $this->site_model->get_db_now();
            $user = $this->session->userdata('user');
            $user_id = $user['id'];
            $role_id = $user['role_id'];
            $now = $this->site_model->get_db_now('sql');
            $read_details = array();
            $shift = $this->clock_model->get_curr_shift(date2Sql($now),$user_id);
            if(!empty($shift)){
                $in = $shift->check_in;
                $out = $now;
                $read_details = array(
                    'read_type'  => X_READ,
                    'read_date'  => date2Sql($in),
                    'cashier'    => $user_id,
                    'date_from'  => $in,
                    'date_to'    => $out,
                    'shift_id'    => $shift->shift_id,
                    // 'calendar_range'=> $in." to ".$out,
                    "title"=>"XREAD"
                );
                if($shift->cashout_id != ""){
                    $read_details['cashout_id'] = $shift->cashout_id;
                }
            }
            // echo "<pre>",print_r($read_details),"</pre>";die();
        $data['page_title'] = fa(' icon-question').' How to process end of Shift (X-Reading)';
        $data['code'] = makeguideendshift($read_details,$role_id);
        // $data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/endofday.php';
        $data['use_js'] = 'endShiftJs';
        $this->load->view('directions',$data);
    }
    // public function guide_end_shift_2(){
    //     $data = $this->syter->spawn('Guide');
    //     // $today = $this->site_model->get_db_now();
    //     $data['page_title'] = fa(' icon-question').' How To End Shift';
    //     $data['code'] = makeguideendshift2();
    //     // $data['add_css'] = 'css/cashier.css';
    //     $data['load_js'] = 'dine/endofday.php';
    //     $data['use_js'] = 'endShiftJs';
    //     $this->load->view('directions',$data);
    // }
    public function guide_end_day(){
        $data = $this->syter->spawn('Guide');
        // $today = $this->site_model->get_db_now();
            $details = array();
            $datetime = $this->site_model->get_db_now('sql');
            $date_to = $datetime;
            $date_from = null;
            $error = null;
            $result = $this->cashier_model->get_latest_read_date(Z_READ);
            $got_z_read = true;
            if(!empty($result)){
                
                if($result->maxi != null){
                    if(date2Sql($result->maxi) == date2Sql($datetime)){
                        //nag zread ng madaling araw
                        $date_from = $result->maxi;
                        $max_date = $result->maxi;
                        if($result->maxi == null)
                            $got_z_read = false;
                    }else{

                        if($result->maxi != null){
                            $date_from = $date_to;
                        }else{
                            $got_z_read = false;
                        }
                        $max_date = $date_to;
                    }
                }else{
                    $max_date = $date_to;
                    $got_z_read = false;    
                }

            }
            else{
                $max_date = $date_to;
                $got_z_read = false;
            }
            
            //FOR OVERNIGHT
            if($date_from != null){
                $shifts_today = $this->cashier_model->get_next_x_read_details($date_from);
                foreach ($shifts_today as $res) {
                    $date_from = $res->scope_from;
                    break;
                }
            }
            else{
                if($got_z_read){
                    $shifts_today = $this->cashier_model->get_next_x_read_details(date2Sql($datetime));
                    foreach ($shifts_today as $res) {
                        $date_from = $res->scope_from;
                        break;
                    }
                }
                else{
                    $first_shift = $this->site_model->get_tbl('read_details',array('read_type'=>1),array('scope_from'=>'asc'),null,true,'*',null,1); 
                    if(count($first_shift) > 0){
                        $date_from = $first_shift[0]->scope_from;
                    }
                }
            }
            $argss = array(
                    'trans_sales.inactive' => 0,
                    'trans_sales.type_id' => SALES_TRANS,
                    // "trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'" => array('use'=>'where','val'=>null,'third'=>false)
                );
            if($date_from == null){
                $shift_open = $this->site_model->get_tbl('shifts',array('check_out'=>null),array('check_in'=>'asc'),null,true,'*',null,1); 

                if(count($shift_open) > 0){
                    $date_from = $shift_open[0]->check_in;
                    $date_in = date2Sql($date_from);
                }else{
                    $date_from = $datetime;
                    $date_in = date2Sql($date_from);
                }
                $argss["date(trans_sales.datetime) = date('".$datetime."')"] = array('use'=>'where','val'=>null,'third'=>false);
            }
            else{
                $date_in = date2Sql($date_from);
                $argss["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            }
            $argss['FORMAT(trans_sales.total_paid,2) < FORMAT(trans_sales.total_amount,2)'] = array('use'=>'where','val'=>null,'third'=>false);
            $argss['trans_sales.type != "reservation"'] = array('use'=>'where','val'=>null,'third'=>false);
            $unsettled_sales = $this->cashier_model->get_trans_sales(null,$argss);
            
            $first_unclosed_shift = null;
            $unclosed_xread = $this->clock_model->get_shifts('DATE(check_in) = \''.(is_null($date_in) ? date('Y-m-d') : $date_in).'\' AND (check_out IS NULL OR check_out = \'\' OR cashout_id IS NULL OR cashout_id =\'\')');
            if (!empty($unclosed_xread)){
                $error = 'There are still shifts open. You need to close it all before proceeding to end of day';
                if($date_from == $datetime){
                    $first_unclosed_shift = $unclosed_xread[0]->check_in;
                }
            }
            if (!empty($unsettled_sales)){
                $error = 'There are still unsettled transactions. You need to settle it all before proceeding to end of day';
            }
            
            if($first_unclosed_shift != null)
                $date_from = $first_unclosed_shift;

            $read_date = date2Sql($date_from);
            $details = array(
                "date_to"=>$date_to,
                "max_date"=>$max_date,
                "date_from"=>$date_from,
                "calendar"=>$date_from,
                "read_date"=>$read_date,
                "use_curr"=>1,
                "title"=>"ZREAD"
            );
            $user = $this->session->userdata('user');
            $user_id = $user['id'];
            $role_id = $user['role_id'];
            // echo "<pre>",print_r($details),"</pre>";die();
        $data['code'] = makeguideendday($details,$error,$role_id);
        // $data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/endofday.php';
        $data['use_js'] = 'endDayJs';
        $this->load->view('directions',$data);
        $user = $this->session->userdata('user');
        $this->manager_model->add_event_logs($user['id'],"PROCESS END OF DAY","Z_READ");
    }
}