<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/cashier.php");

class Endofday extends Cashier {
    public function __construct(){
        parent::__construct();
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/menu_model');
        $this->load->model('dine/settings_model');
        $this->load->model('dine/setup_model');
        $this->load->model('third_party/Migrator_model');
        $this->load->helper('core/string_helper');
        $this->load->helper('dine/endofday_helper');
        $this->load->model("dine/reports_model",'reports_model');

        if(MASTERMIGRATION){    
                        $this->load->model('core/master_model');
        }

    }
    public function index(){
        $data = $this->syter->spawn(null);
        $data['code'] = endOfDayPage();
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/endofday.php';
        $data['use_js'] = 'endofdayJs';
        $this->load->view('load',$data);
    }
    public function summary(){
        $data = $this->syter->spawn(null);
        $user = $this->session->userdata('user');
        $role_id = $user['role_id'];

        $data['code'] = summaryPage($role_id);
        $data['add_css'] = array('css/morris/morris.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js');
        $data['load_js'] = 'dine/endofday.php';
        $data['use_js'] = 'summaryJs';
        $this->load->view('load',$data);
    }
    public function summary_orders(){
        $today = $this->site_model->get_db_now(null,true);
        $args = array();
        $args["DATE(trans_sales.datetime)"] = $today;
        $orders = array();
        $ords = $this->cashier_model->get_trans_sales(null,$args);
        $types = unserialize(SALE_TYPES);
        $set = $this->cashier_model->get_pos_settings();
        if(count($set) > 0){
            $types = array();
            $ids = explode(',',$set->controls);
        // echo "<pre>",print_r($ords),"</pre>";die();
            foreach($ids as $value){
                $text = explode('=>',$value);
                if($text[0] == 1){
                    $types[]='dinein';
                }elseif($text[0] == 7){
                    $types[]='drivethru';
                }else{
                    $types[]=$text[1];
                }
            }
            if(App == 1){
                $types[] = 'App Order'; // for mobile app
            }
        }
        // echo "<pre>",print_r($types),"</pre>";die();
        $status = array('Open'=>'blue','Settled'=>'green','Cancel'=>'yellow','Void'=>'red');
        foreach ($types as $typ) {
            $open = 0;
            $settled = 0;
            $cancel = 0;
            $void = 0;
            foreach ($ords as $res) {
                if(strtolower($res->type) == strtolower($typ)){
                    if($res->type_id == 10){
                        if($res->trans_ref != "" && $res->inactive == 0){
                            $settled += $res->total_amount;
                        }
                        elseif($res->trans_ref == ""){
                            if($res->inactive == 0){
                                $open += $res->total_amount;
                            }
                            else{
                                $cancel += $res->total_amount;
                            }
                        }
                    }
                    else{
                        $void += $res->total_amount;
                    }
                }
            }
            $orders[$typ] = array('label'=>$typ,'open'=>$open,'settled'=>$settled,'cancel'=>$cancel,'void'=>$void);
        }
        $total_trans = 0;
        $stat = array();
        $total_sales = 0;
        foreach ($orders as $type => $opt) {
            foreach ($opt as $txt => $val) {
                if($txt != 'label'){
                    if(isset($stat[strtolower($txt)]))
                        $stat[strtolower($txt)] += $val;
                    else
                        $stat[strtolower($txt)] = $val;
                    $total_trans += $val;

                    if($txt == 'open' || $txt == 'settled')
                        $total_sales += $val;
                }
            }
        }
        foreach ($status as $txt => $color) {
            $this->make->sDiv(array('class'=>'clearfix'));
                $this->make->span($txt,array('class'=>'pull-left'));
                $this->make->span(small($stat[strtolower($txt)]."/".$total_trans),array('class'=>'pull-right'));
            $this->make->eDiv();
            $this->make->sDiv(array('style'=>'margin-bottom:10px;'));
                $this->make->progressBar($total_trans,$stat[strtolower($txt)],null,0,$color,array());
            $this->make->eDiv();
        }
        $this->make->sDiv(array('class'=>'clearfix'));
            $this->make->H(3,'Total',array('class'=>'pull-left'));
            $this->make->H(3,num($total_sales),array('class'=>'pull-right'));
           
        $this->make->eDiv();
        $code = $this->make->code();

        echo json_encode(array("orders"=>$orders,'types'=>$types,'code'=>$code));
    }
    ///////////////////////////////////////
    ////// END SHIFT
    ///////////////////////////////////////
        public function end_shift(){
            $data = $this->syter->spawn(null);
            // $receipts = "";
            // $receipts = $this->show_xread(false);
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
            $data['code'] = endShift($read_details,$role_id);
            $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/endofday.php';
            $data['use_js'] = 'endShiftJs';
            $this->load->view('load',$data);
        }
        public function read_shift_sales(){
            $user = $this->session->userdata('user');
            $user_id = $user['id'];
            // $user_id = 22;
            start_load(0);
            $now = $this->site_model->get_db_now('sql');
            $shift = $this->clock_model->get_curr_shift(date2Sql($now),$user_id);
            $error = "";
            if(empty($shift)){
                echo json_encode(array('error'=>'There is no shift.'));
                return false;
            }
            update_load(40);
            sleep(1);
            if($shift->cashout_id != ""){
                $in = $shift->check_in;
                $out = $now;
                $read_details = array(
                    'read_type' => X_READ,
                    'read_date' => date2Sql($in),
                    'user_id'   => $user_id,
                    'scope_from'=> $in,
                    'scope_to'  => $out
                );
                $id = $this->cashier_model->add_read_details($read_details);
                update_load(60);
                $log_user = $this->session->userdata('user');
                $this->logs_model->add_logs('Read',$log_user['id'],$log_user['full_name']." Read Shift.",null);
                $this->clock_model->update_clockout(array('xread_id'=>$id,'check_out'=>$out),$shift->shift_id);
                $this->logs_model->add_logs('Shift',$log_user['id'],$log_user['full_name']." Ended Shift.",$shift->shift_id);
                site_alert('Last Shift has successfully ended.','success');
                update_load(70);
                sleep(1);
                $this->backup_shift($shift->check_in,$shift->shift_id);


                // die();
                
                $this->load->library('../controllers/dine/main');
                update_load(90);
                sleep(1);
                //not transfer main
                // $this->main->shifts_to_main($shift->shift_id);

                session_start();
                $check = sql2Date($shift->check_in);
                $time = $this->site_model->get_db_now();
                $today = sql2Date($time);
                if(strtotime($check) < strtotime($today)){
                    unset($_SESSION['load']);
                    unset($_SESSION['problem']);
                }    
                update_load(100);

                if(MIGRATION_VERSION == '2'){
                    run_main_exec();
                    
                }
            }
            else{
                $error = "You need to count the cash drawer first before ending the shift.";
                update_load(100);
            }

            if(AUTOLOCALSYNC){ // run the syncing
               // run_main_exec();
            }
            
            echo json_encode(array('error'=>$error));
        }
        public function backup_shift($date,$id){
            $year = date('Y',strtotime($date));
            $month = date('M',strtotime($date));
            $set = $this->cashier_model->get_pos_settings();
            $backup_folder = "C:/xampp/htdocs/dine/backup";
            $extra_bu_foler = "";
            // echo $set->backup_path.'<br>';
            if(iSetObj($set,'backup_path')){
                $extra_bu_foler = iSetObj($set,'backup_path');
                // $backup_folder = iSetObj($set,'backup_path');
            }
            // if (!file_exists($backup_folder)) { 
            //     $backup_folder = "C:/xampp/htdocs/dine/backup";
            // }    
            $file_path = $backup_folder."/xreads"."/".$year."/".$month;
            if (!file_exists($file_path)) {   
                mkdir($file_path, 0777, true);
            }

            if($extra_bu_foler != ""){
                $exfile_path = $extra_bu_foler."/xreads"."/".$year."/".$month;
                if (!file_exists($exfile_path)) {   
                    mkdir($exfile_path, 0777, true);
                }
            }
            $fileB = date('Ymd',strtotime($date))."-".$id.".sql";
            $this->load->dbutil();
            $tables = array('trans_sales','trans_sales_charges','trans_sales_items','trans_sales_discounts','trans_sales_menu_modifiers','trans_sales_menus',
                            'trans_sales_no_tax','trans_sales_payments','trans_sales_tax','trans_sales_zero_rated','trans_sales_local_tax',
                            'reasons','cashout_details','cashout_entries','shift_entries','shifts','users','menus','menu_categories','menu_modifiers',
                            'menu_subcategories','modifier_group_details','modifier_groups','modifiers','item_moves','categories','items','subcategories');
            $prefs = array(
                "format" => 'txt',
                'tables' => $tables
            );
            $backup =& $this->dbutil->backup($prefs); 
            $this->load->helper('file');
            write_file($file_path.'/'.$fileB, $backup);
            if($extra_bu_foler != ""){
                write_file($exfile_path.'/'.$fileB, $backup);
            }
        }
    ///////////////////////////////////////
    ////// END DAY
    ///////////////////////////////////////
        public function end_day(){
            $data = $this->syter->spawn(null);
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
            $data['code'] = endDay($details,$error,$role_id);
            $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/endofday.php';
            $data['use_js'] = 'endDayJs';
            $this->load->view('load',$data);
        }
        public function read_end_day_sales(){
            $details = array();
            start_load(0);
            $datetime = $this->site_model->get_db_now('sql');
            $end = $datetime;
            $start = null;
            $result = $this->cashier_model->get_latest_read_date(Z_READ);
            $got_z_read = true;
            if(!empty($result)){
                $start = $result->maxi;
                $max_date = $result->maxi;
                if($result->maxi == null)
                    $got_z_read = false;
            }
            else{
                $max_date = $end;
                $got_z_read = false;
            }

            // $this->sls();
            // $this->sdet();
            
            update_load(30);
            sleep(1);
            // $check = $this->cashier_model->check_latest_read_date(Z_READ,date2Sql($datetime));
            // $increment = true;
            // if(!empty($check) && $check->today_zread != null){
            //     $start = $check->today_zread;
            //     $max_date = $check->today_zread;
            //     $increment = false;
            // }
            ########### PANSAMANTALA
            // $increment = true;
            // if($start != null){
            //     $check = $this->cashier_model->check_latest_read_date(Z_READ,date2Sql($start));
            //     if(!empty($check) && $check->today_zread != null){
            //         $start = $check->today_zread;
            //         $increment = false;
            //         // $max_date = $check->today_zread;
            //     }
            // }
            // var_dump($start);die();
            update_load(40);
            
            //FOR OVERNIGHT
            if($start != null){
                $shifts_today = $this->cashier_model->get_next_x_read_details($start);
                foreach ($shifts_today as $res) {
                    $start = $res->scope_from;
                    break;
                }
            }
            else{
                if($got_z_read){
                    $shifts_today = $this->cashier_model->get_next_x_read_details(date2Sql($datetime));
                    foreach ($shifts_today as $res) {
                        $start = $res->scope_from;
                        break;
                    }
                }
                else{
                    $first_shift = $this->site_model->get_tbl('read_details',array('read_type'=>1),array('scope_from'=>'asc'),null,true,'*',null,1); 
                    if(count($first_shift) > 0){
                        $start = $first_shift[0]->scope_from;
                    }
                }
            }
            // if($start == null){
            //     $shifts_today = $this->cashier_model->get_x_read_details(date2Sql($datetime));
            //     foreach ($shifts_today as $res) {
            //         $start = $res->scope_from;
            //         break;
            //     }
            // }
            $read_date = date2Sql($start);

            $z_read_id = $this->go_zread(false,$start,$end,$read_date);

            $this->load->model('site/api_model');
            $this->load->model('core/sync_model');
            $this->sync_model->execute_sync();

            if($z_read_id){
                if(EXECUTE_MASTER_WITH_ZREADING){
                     // $this->master_model->execute_migration();
                    $this->api_model->execute_migration_v2();
                }
               site_alert("Z Read for ".$read_date." successfully processed.",'success');   
               site_alert("Sales transaction sync to HQ completed.",'success');                
            }

            /**RUN MIGRATION**/
            if(MIGRATION_VERSION == '2'){
                run_main_exec();
                
            }


                // echo "asd";die();
            /**FOR accounting bir textfile auto upload 06252019 @jx**/
            if(BIR_TEXT_ENABLED){
                $this->item_inventory_gen(); // extract inventory to csv
                $success= $this->bir_textfile_gen(); // extract sales to csv
           
                if(empty($success['error'])){
            
                    run_accounting_sales_exec();
                }

            }
            /**END FOR accounting bir textfile auto upload **/

            update_load(70);
            sleep(1);
            if(MALL_ENABLED){
                if(MALL == "robinsons"){
                    $increment = true;
                    $rob = $this->send_to_rob($z_read_id,$increment);
                    if($rob['error'] == ""){
                        site_alert("File:".$rob['file']." Sales File successfully sent to RLC server.",'success');
                    }
                    else{
                        site_alert($rob['error'],'error');
                    }
                }
                else if(MALL == "ortigas"){
                    $this->ortigas_file($z_read_id);
                }
                else if(MALL == "araneta"){
                    $this->araneta_trans_file($read_date,$z_read_id,true);
                    // $this->araneta_file($z_read_id);
                    // $last_date = date("Y-m-t", strtotime($read_date));
                    // $now_date = date("Y-m-d", strtotime($read_date));
                    // if($last_date == $now_date){
                    //     $this->araneta_month_file($now_date);
                    // }
                }
                else if (MALL == 'megamall') {
                    $this->sm_file($read_date,$z_read_id);
                }
                else if (MALL == 'stalucia') {
                    $this->stalucia_file($z_read_id);
                }
                else if (MALL == 'ayala') {
                    $this->ayala_file($z_read_id);
                }
                else if (MALL == 'eton') {
                    $this->eton_file($z_read_id);
                }
                else if (MALL == 'vistamall') {
                    $this->vista_file($z_read_id);
                }
                else if (MALL == 'cbmall') {
                    $this->cbmall_file($read_date,$z_read_id);
                }
                else if (MALL == 'megaworld') {
                    $this->megaworld_file($z_read_id);
                }
                else if (MALL == 'rockwell') {
                    $this->rockwell_file($z_read_id);
                }
                else if (MALL == 'miaa') {
                    $this->miaa_file_regen($read_date,false);
                    $this->miaa_xfile($z_read_id);
                }
                else if (MALL == 'shangrila') {
                    $this->shangrila_file($z_read_id);
                }
            }
            update_load(100);
            // echo var_dump($rob);
        }
    function csv_extractor()
    {   
        $headers = array(array('invoice_number','qty','menu_code','price','disccode','cashier_id','invoice_time','guest',   'invoice_date', 'table_num',    'discname', 'discpercent',  'menu_name','page_type',    'added',    'odiscount',    'disc2',    'taxes',    'service_charge'));
        $sales_data = $this->Migrator_model->get_sales()->result();
        
        $output = array_merge($headers,$sales_data);
        // echo "<pre>",print_r($output),"</pre>";die();
        $file_name  = restograph_folder.'restograph_'.date('Y-m-d').'.csv';


        $fp = fopen($file_name, 'w+');
        foreach ($headers as $fields) {
            fputcsv($fp, $fields);
        }

        foreach ($sales_data as $fields) {
            $fields = (array) $fields;
            fputcsv($fp, $fields);
        } 


        fclose($fp); 

        return true;
    }

    public function bir_textfile_gen(){
      
        $get_trans_sales =  $this->reports_model->get_bir_trans_sales();
        $date_now = date('Ymd');
        $file_name = 'generated_txtfile_'.$date_now.'.csv';
        $file_path = BIR_TEXT_PATH.$file_name;
        // update_load(80);        
        if(!empty($get_trans_sales)){

            $fp = fopen( $file_path, 'w');
            fputcsv($fp, array('Date','Trans No','VAT Sales','Exempt Sales','Zero Rated Sales','VAT','Discount','Charges','Net Sales'));
            foreach ($get_trans_sales as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);
        }

        // $content = file_put_contents($file_path, $txt_contents);
        // update_load(100);     
        return  array('file_path'=>$file_path,"error"=>"");
           
    }

     public function item_inventory_gen(){
      
        $get_trans_inventory =  $this->reports_model->get_inventory_movement();
        $date_now = date('Ymd');
        $file_name = 'generated_inventory_'.$date_now.'.csv';
        $file_path = BIR_TEXT_PATH.$file_name;
        // update_load(80);        
        // echo "<pre>",print_r($get_trans_inventory),"</pre>";die();
        if(!empty($get_trans_inventory)){

            $fp = fopen( $file_path, 'w');
            fputcsv($fp, array('Trans Ref','Loc ID','Item ID','Qty','UOM','Cost','Reg Date'));
            foreach ($get_trans_inventory as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);
        }

        // $content = file_put_contents($file_path, $txt_contents);
        // update_load(100);     
        return  array('file_path'=>$file_path,"error"=>"");
           
    }

    function dbf_download(){
        $prev_file1 = "dbf/sls". date('md',strtotime("-1 day")).'.dbf';
        $prev_file2 = "dbf/sdet". date('md',strtotime("-1 day")).'.dbf';

        if(file_exists($prev_file1)){
            unlink($prev_file1);
        }

        if(file_exists($prev_file2)){
            unlink($prev_file2);
        }

        if(file_exists('dbf/ipos_dbase.zip')){
            unlink('dbf/ipos_dbase.zip');
        }
            
        $dbf1_file ="dbf/sls". date('md').'.dbf';
        $dbf2_file ="dbf/sdet". date('md').'.dbf';
        // header("Location:" . base_url() . 'dbf/' . $dbf_file);

        $zip = new ZipArchive;
        $zipname = 'dbf/ipos_dbase.zip';

        $zip->open($zipname, ZipArchive::CREATE);
        
        $zip->addFile($dbf1_file);
        $zip->addFile($dbf2_file);
        
        $zip->close();

        header("Location:" . base_url() . 'dbf/' . 'ipos_dbase.zip');

    }

    function sls($sales_ids=array()){
        $select = 'trans_sales.*,payment_type,trans_sales_discounts.type as disc_type,disc_rate';
        $join = null;
        $join['trans_sales_payments'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id');
        $join['trans_sales_discounts'] = array('content'=>'trans_sales_discounts.sales_id = trans_sales.sales_id','mode'=>'left');
        $n_item_res = array();
        $args['date(trans_sales.datetime) = '] = date('Y-m-d');
        // if($curr){
        //     $this->site_model->db = $this->load->database('default', TRUE);
        //     $n_item_res = $this->site_model->get_tbl('trans_sales_items',array('sales_id'=>$ids),array(),$join,true,$select);
        // }

        $ids = $sales_ids;//array(1,2,3);
        $this->site_model->db= $this->load->database('default', TRUE);
        $item_res = $this->site_model->get_tbl('trans_sales',$args,array(),$join,true,$select);

        $printH = array();
        foreach($item_res as $row){
           $printH[] = array($row->trans_ref,
                              '',
                              '',
                              $row->table_id,
                              '',
                              $row->waiter_id,
                              $row->guest,
                              $row->payment_type,
                              $row->disc_type,
                              '',

                              date('m/d/Y',strtotime($row->datetime)),
                              date('H:i:s',strtotime($row->datetime)),
                              date('m/d/Y',strtotime($row->datetime)),
                              $row->printed == 1 ? 'TRUE':'FALSE',
                              '',
                              $row->total_amount,
                              $row->total_paid,
                              $row->tax,
                              '',
                              '',

                              '',
                              $row->type_id,
                              '',
                              '',
                              $row->total_discount,
                              0,
                              $row->tax > 0 ? 'TRUE' : 'FALSE',
                              '',
                              date('m/d/Y', strtotime($row->datetime)),
                              $row->disc_rate,
                              
                              '',
                              '',
                              '',
                              '',
                              '',
                              $row->customer_id,
                              '',
                              '',
                              '',                              
                              $row->memo,

                              '',
                              '',
                              $row->sales_id,
                              '',
                              '',
                              $row->waiter_id,
                              '',
                              '',
                              '',
                              $row->tax > 0 ? 0 : $row->total_amount,

                              '',
                              '',
                              '',
                              '',
                              '',
                              '',
                              '',
                              '',
                        );
        }


        //http://www.dbase.com/KnowledgeBase/int/db7_file_fmt.htm
        // array('BILL_NO','C',8) -> text, C(N-Numeric,C-Character), legnth, decimal
         $dbfheaders = array(array('BILL_NO','C',8),
                                    array('FACT_NO','C',15),
                                    array('SESSION_NO','N',15,4),
                                    array('TABLE','N',11,3),
                                    array('SEAT_NO','N',11,3),
                                    array('WAITER','N',11,3),
                                    array('PEOPLE_NO','C',11),
                                    array('PAY_TYPE','N',11,0),
                                    array('DISC_TYPE','C',15),
                                    array('OPEN_TIME','C',15),

                                    array('DATE','C',15),
                                    array('BILL_TIME','C',15),
                                    array('BILL_DATE','C',15),
                                    array('PRINTED','C',9),
                                    array('POSTED','C',11),
                                    array('TOTAL','C',11),
                                    array('RECEIVED','N',11,3),
                                    array('TAXES','N',11,3),
                                    array('AUTO_GRAT','N',11,3),
                                    array('LOGIN_REF','N',11,3),

                                    array('CASH_DRAW','C',11),
                                    array('SALE_TYPE','C',11),
                                    array('SALE_AREA','C',11),
                                    array('ACC_POSTED','C',11),
                                    array('DISCOUNT','C',11),
                                    array('DOLLARDISC','C',11),
                                    array('TAXABLE','C',11),
                                    array('PRT_TIME','C',11),
                                    array('PRT_DATE','C',11),
                                    array('DISC_PCT','C',11),

                                    array('HIDDEN','C',11),
                                    array('TAX_TABLE','C',11),
                                    array('ACCOUNT_NO','C',11),
                                    array('REV_CENTER','C',11),
                                    array('PHONE','C',11),
                                    array('CUST_ID','C',11),
                                    array('PHONE_ID','C',11),
                                    array('GRATOVRPCT','C',11),
                                    array('GRATOVRAMT','C',11),
                                    array('NOTE','C',11),

                                    array('SLS_NAME','C',11),
                                    array('CASH_BACK','C',11),
                                    array('TRANS_ID','C',11),
                                    array('START_STN','C',11),
                                    array('SETTLE_STN','C',11),
                                    array('WAITER0','C',11),
                                    array('SEND_TIME','C',11),
                                    array('ASSG_TIME','C',11),
                                    array('TRAY_REF','C',11),
                                    array('NOTAXAMT','C',11),

                                    array('RETN_TIME','C',11),
                                    array('ADDRESS_ID','C',11),
                                    array('ADDR_MODE','C',11),
                                    array('ADVCONVERT','C',11),
                                    array('READY_TIME','C',11),
                                     array('DLV_TIME','C',11),
                                      array('HASH_TOTAL','C',11),
                                       array('SQLSTATE','C',11),

                                );

                $dbf_file ="dbf/sls". date('md').'.dbf';
// print_r($printH);exit;
                // $printH[] = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33);

                if(file_exists($dbf_file)){
                    unlink($dbf_file);
                }

                if (dbase_create($dbf_file, $dbfheaders)) {
                    $dbase = dbase_open($dbf_file,2);
                    if($dbase){
                        $row = array();
                        foreach ($printH as $key => $row_val) {
                           
                                $dadd = dbase_add_record($dbase,$row_val);
                        }
                        
                        // if($dadd){
                        //     $txt_file = $file_path.$mall->contract_no.(date('md',strtotime($zread['read_date']) ) ).".txt";
                        //     $this->write_file(array($txt_file),$print_str);
                        // }
                        dbase_close($dbase);

                        // header("Location:" . base_url() . $dbf_file);
                    }
                }
    }

    function sdet($sales_ids=array()){
        $curr = $this->search_current();
        // $trans = $this->trans_sales($post['args'],$curr);

        // if(count($sales_ids) == 0){
        //     return false;
        // }
        
        $select = 'trans_sales.*,trans_sales_menus.*,menu_code,menus.cost as menu_cost';
        $join = null;
        $join['menus'] = array('content'=>'trans_sales_menus.menu_id = menus.menu_id');
        $join['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_menus.sales_id');
        $n_item_res = array();
        // if($curr){
        //     $this->site_model->db = $this->load->database('default', TRUE);
        //     $n_item_res = $this->site_model->get_tbl('trans_sales_items',array('sales_id'=>$ids),array(),$join,true,$select);
        // }
        $args['date(trans_sales.datetime) = '] = date('Y-m-d');
        $ids = $sales_ids;//array(1,2,3);
        $this->site_model->db= $this->load->database('default', TRUE);
        // $item_res = $this->site_model->get_tbl('trans_sales_menus',array('trans_sales_menus.sales_id'=>$ids),array(),$join,true,$select);
        $item_res = $this->site_model->get_tbl('trans_sales_menus',$args,array(),$join,true,$select);

        $printH = array();
        foreach($item_res as $row){
           $printH[] = array($row->trans_ref,
                              '',//$row->trans_ref,
                              $row->qty,
                              round($row->qty*$row->price,3),
                              round($row->qty*$row->price,3),
                              0,
                              'FALSE',
                              $row->user_id,
                              0,
                              'FALSE',
                              'FALSE',
                              0,
                              '',
                              '',
                              'FALSE',
                              '',
                              $row->tax * -1,
                              $row->total_discount * -1,
                              '',
                              '',
                              '',
                              date('H:i:s', strtotime($row->datetime)),
                              '',
                              'FALSE',
                              '',
                              'FALSE',
                              '',
                              date('m/d/Y', strtotime($row->datetime)),
                              date('H:i:s', strtotime($row->datetime)),
                              $row->memo,
                              '',
                              '',
                              '',
                        );
        }


        //http://www.dbase.com/KnowledgeBase/int/db7_file_fmt.htm
        // array('BILL_NO','C',8) -> text, C(N-Numeric,C-Character), legnth, decimal
         $dbfheaders = array(array('BILL_NO','C',8),
                                    array('REF_NO','C',15),
                                    array('QUANTY','N',15,4),
                                    array('PRICE_PAID','N',11,3),
                                    array('RAW_PRICE','N',11,3),
                                    array('COST','N',11,3),
                                    array('POSTED','C',11),
                                    array('EMP_NO','N',11,0),
                                    array('DEL_CODE','C',15),
                                    array('PRC_ADJ','C',15),
                                    array('TWO4ONE','C',15),
                                    array('DISC_NO','C',15),
                                    array('PRC_LVL','C',15),
                                    array('PRC_LVL0','C',9),
                                    array('ISCOUPON','C',11),
                                    array('ITEM_ADJ','C',11),
                                    array('VAT_ADJ','N',11,3),
                                    array('DISC_ADJ','N',11,3),
                                    array('PRICEMULT','N',11,3),
                                    array('INVMULT','N',11,3),
                                    array('GD_NO','C',11),
                                    array('SEND_TIME','C',11),
                                    array('ADJ_NO','C',11),
                                    array('REFUNDFLAG','C',11),
                                    array('COU_REC','C',11),
                                    array('COUPITEM','C',11),
                                    array('HASH_STAT','C',11),
                                    array('ORD_DATE','C',11),
                                    array('ORD_TIME','C',11),
                                    array('SPEC_INST','C',11),
                                    array('MODOF','C',11),
                                    array('MODSCREEN','C',11),
                                    array('SQLSTATE','C',11));

                $dbf_file ="dbf/sdet". date('md').'.dbf';

                // $printH[] = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33);

                if(file_exists($dbf_file)){
                    unlink($dbf_file);
                }

                if (dbase_create($dbf_file, $dbfheaders)) {
                    $dbase = dbase_open($dbf_file,2);
                    if($dbase){
                        $row = array();
                        foreach ($printH as $key => $row_val) {
                           
                                $dadd = dbase_add_record($dbase,$row_val);
                        }
                        
                        // if($dadd){
                        //     $txt_file = $file_path.$mall->contract_no.(date('md',strtotime($zread['read_date']) ) ).".txt";
                        //     $this->write_file(array($txt_file),$print_str);
                        // }
                        dbase_close($dbase);

                        // header("Location:" . base_url() . $dbf_file);
                    }
                }
    }

}