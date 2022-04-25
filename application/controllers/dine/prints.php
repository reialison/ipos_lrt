<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Prints extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('dine/print_helper');
        $this->load->helper('core/string_helper');

        $this->load->model('dine/cashier_model');
        $this->load->model('dine/setup_model');
        $this->load->model('dine/menu_model');
        $this->load->model('dine/manager_model');
    }
    public function index(){
        $data['code'] = mainPage();
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/prints';
        $data['use_js'] = 'mainPageJS';
        $this->load->view('load',$data);
    }
    ##############
    #### PAGES
        public function date_and_time(){
            $data['code'] = datetimePage();
            $data['load_js'] = 'dine/prints';
            $data['use_js'] = 'datetimeJS';
            $this->load->view('load',$data);
        }
        public function shift_sales(){
            $today = $this->site_model->get_db_now();
            $data['code'] = shiftsPage($today);
            $data['load_js'] = 'dine/prints';
            $data['use_js'] = 'sfhitJS';
            $this->load->view('load',$data);
        }
        public function get_shifts(){
            $date = $this->input->post('calendar');

            $details = $this->setup_model->get_branch_details();
            
            $open_time = $details[0]->store_open;
            $close_time = $details[0]->store_close;

            $pos_start = date2SqlDateTime($date." ".$open_time);
            $oa = date('a',strtotime($open_time));
            $ca = date('a',strtotime($close_time));
            $pos_end = date2SqlDateTime($date." ".$close_time);
            if($oa == $ca){
                $pos_end = date('Y-m-d H:i:s',strtotime($pos_end . "+1 days"));
            }
            //JEDN
            // $args["DATE(shifts.check_in) = DATE('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
            $args["shifts.check_in >= '".$pos_start."' and shifts.check_in <= '".$pos_end."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $join['users'] = array('content'=>'shifts.user_id = users.id');
            $select = "shifts.*,users.fname,users.lname,users.mname,users.suffix,users.username";
            $results = $this->site_model->get_tbl('shifts',$args,array('check_in'=>'desc'),$join,true,$select);
            $shifts = array();
            foreach ($results as $res) {
                $in = "";
                if($res->check_in != "")
                    $in = toTime($res->check_in);

                $out = "";
                if($res->check_out != "")
                    $out = toTime($res->check_out);
                $shifts[$res->shift_id] = array(
                                                // 'name'=>$res->fname." ".$res->mname." ".$res->lname." ".$res->suffix,
                                                'name'=>$res->username,
                                                'in'=>$in,
                                                'out'=>$out,
                                                );
            }
            $code = "";
            $this->make->sDiv(array('class'=>'listings'));
                $this->make->sUl();
                foreach ($shifts as $id => $sh) {
                    // $color = 'teal';
                    // if($sh['out'] == "")
                    //     $color = 'orange';
                    // $this->make->sDivCol(6);
                    //     $this->make->sDiv(array('style'=>'margin:5px;'));
                    //     $this->make->sBox('solid',array('ref'=>$id,'id'=>'shift-box-'.$id,'class'=>'shift-box bg-'.$color,'style'=>'cursor:pointer;-webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;padding:0px;'));
                    //         $this->make->sBoxBody();
                    //             $this->make->H(5,fa('fa-user')." ".$sh['name']);
                    //             $txt = $sh['in'];
                    //             if($sh['out'] != "")
                    //                 $txt.= " - ".$sh['out'];
                    //             $this->make->H(5,fa('fa-clock-o')." ".$txt);
                    //         $this->make->eBoxBody();
                    //     $this->make->eBox();
                    //     $this->make->eDiv();
                    // $this->make->eDivCol();
                    $this->make->sLi(array('ref'=>$id,'id'=>'shift-box-'.$id,'class'=>'shift-box bg-white','style'=>'cursor:pointer;padding:5px;padding-bottom:15px;padding-top:15px;border-bottom:1px solid #ddd;'));
                        $txt = fa('fa-clock-o')." ".$sh['in'];
                        if($sh['out'] != "")
                            $txt.= " - ".$sh['out'];
                        $this->make->H(5,fa('fa-user')." ".strtoupper($sh['name'])."<span class='pull-right'>".$txt."</span>",array('class'=>'name','style'=>'margin:0;padding:0;'));
                    $this->make->eLi();
                }
                $this->make->eUl();
            $this->make->eDiv();
            $code = $this->make->code();
            echo json_encode(array('code'=>$code,'shifts'=>$shifts));
        }
        public function end_day_sales(){
            $today = date('m/d/Y',strtotime($this->site_model->get_db_now()." -1 days") );
            $data['code'] = dayReadsPage($today);
            $data['load_js'] = 'dine/prints';
            $data['use_js'] = 'dayReadsJS';
            $this->load->view('load',$data);
        }
    ##############
    #### REPORTS
        public function payment_sales_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();

            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $breakdown = $this->payment_breakdown_sales($sales['settled']['ids'],$curr);

            $title_name = "Payment Breakdown Report";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],38," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),38," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],38," ")."\r\n";

            // $print_str .= "\r\n";
            $all_total = 0;
            $all_ctr = 0;
            foreach ($breakdown['types'] as $ctr => $type) {
                if($type != 'cash'){
                    $ctr = 0;
                    $total = 0;
                    foreach ($breakdown['payments'] as $key => $row) {
                        if($row->payment_type == $type){
                            $ctr++;
                            if($row->amount > $row->to_pay)
                                $amount = $row->to_pay;
                            else
                                $amount = $row->amount;
                            $total += $amount;
                        }
                    }
                    $all_total += $total;
                    $all_ctr += $ctr;
                    $print_str .= "======================================"."\r\n";
                    $print_str .= append_chars(strtoupper($type),'right',18," ").append_chars($ctr,'right',10," ").append_chars(numInt($total).'','left',10," ")."\r\n";
                    $print_str .= "======================================"."\r\n";
                    foreach ($breakdown['payments'] as $key => $row) {
                        if($row->payment_type == $type){
                            $card_num = $row->card_number;
                            $card_type = $row->card_type;
                            if($row->payment_type == 'gc'){
                                $card_type = 'Gift Card';
                                $card_num = $row->reference;
                            }
                            $total_amt = $row->amount;
                            $to_pay_amt = $row->to_pay;
                            $approval_code = $row->approval_code;
                            $order_ref = $row->trans_ref;
                            $print_str .= append_chars(substrwords("Receipt: ",18,""),"right",12," ").$order_ref
                                         .append_chars('',"left",13," ")."\r\n";

                            $print_str .= append_chars(substrwords("Card: ",18,""),"right",12," ").$card_type
                                         .append_chars($card_num,"left",13," ")."\r\n";
                            if($approval_code != ""){
                                $print_str .= append_chars(substrwords("Approval:",18,""),"right",12," ").$approval_code
                                             .append_chars('',"left",13," ")."\r\n";
                            }

                            $print_str .= append_chars(substrwords("Amount: ",18,""),"right",12," ").numInt($total_amt)
                                         .append_chars('',"left",13," ")."\r\n";

                            $print_str .= append_chars(substrwords("To Pay: ",18,""),"right",12," ").numInt($to_pay_amt)
                                         .append_chars('',"left",13," ")."\r\n";
                            $print_str .= "\r\n";
                        }
                    }
                }

            }
            foreach ($breakdown['types'] as $ctr => $type) {
                if($type == 'cash'){
                    $ctr = 0;
                    $total = 0;
                    foreach ($breakdown['payments'] as $key => $row) {
                        if($row->payment_type == $type){
                            $ctr++;
                            if($row->amount > $row->to_pay)
                                $amount = $row->to_pay;
                            else
                                $amount = $row->amount;
                            $total += $amount;
                        }
                    }
                    $all_total += $total;
                    $all_ctr += $ctr;
                    $print_str .= "======================================"."\r\n";
                    $print_str .= append_chars(strtoupper($type),'right',18," ").append_chars($ctr,'right',10," ").append_chars(numInt($total).'','left',10," ")."\r\n";
                    $print_str .= "======================================"."\r\n";
                }
            }
            $print_str .= append_chars(strtoupper('total payments:'),'right',18," ").append_chars($all_ctr,'right',10," ").append_chars(numInt($all_total).'','left',10," ")."\r\n";

            // echo "<pre>".$print_str."</pre>";
            if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else{
                $this->do_print($print_str,$asJson);
            }
            // $this->do_print($print_str,$asJson);
        }
        public function gift_card_sales_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();

            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $breakdown = $this->payment_breakdown_sales($sales['settled']['ids'],$curr);

            $title_name = "Gift Cards Breakdown Report";
            $print_str .= align_center($title_name,PAPER_WIDTH," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],PAPER_WIDTH," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),38," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],38," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            // $print_str .= "\r\n";
            $all_total = 0;
            $all_ctr = 0;
            foreach ($breakdown['types'] as $ctr => $type) {
                if($type == 'gc'){
                    $ctr = 0;
                    $total = 0;
                    $ov_total = 0;
                    foreach ($breakdown['payments'] as $key => $row) {
                        if($row->payment_type == $type){
                            $ctr++;
                            if($row->amount > $row->to_pay)
                                $amount = $row->to_pay;
                            else
                                $amount = $row->amount;
                            $total += $amount;
                        }
                    }
                    foreach ($breakdown['payments'] as $key => $row) {
                        if($row->payment_type == $type){
                            $amount = $row->amount;
                            $ov_total += $amount;
                        }
                    }

                    $all_total += $total;
                    $all_ctr += $ctr;

                    foreach ($breakdown['payments'] as $key => $row) {
                        if($row->payment_type == $type){
                            $card_num = $row->card_number;
                            $card_type = $row->card_type;
                            if($row->payment_type == 'gc'){
                                $card_type = 'Gift Card';
                                $card_num = $row->reference;
                            }
                            $total_amt = $row->amount;
                            $to_pay_amt = $row->to_pay;
                            $approval_code = $row->approval_code;
                            $order_ref = $row->trans_ref;
                            $print_str .= append_chars(substrwords("Receipt: ",18,""),"right",12," ").$order_ref
                                         .append_chars('',"left",13," ")."\r\n";
                            $print_str .= append_chars(substrwords("Card No.: ",18,""),"right",12," ").$card_num
                                         .append_chars('',"left",13," ")."\r\n";

                            if($approval_code != ""){
                                $print_str .= append_chars(substrwords("Approval:",18,""),"right",12," ").$approval_code
                                             .append_chars('',"left",13," ")."\r\n";
                            }

                            $print_str .= append_chars(substrwords("Card Amount: ",18,""),"right",12," ").numInt($total_amt)
                                         .append_chars('',"left",13," ")."\r\n";

                            $print_str .= append_chars(substrwords("Amount Due: ",18,""),"right",12," ").numInt($to_pay_amt)
                                         .append_chars('',"left",13," ")."\r\n";
                            $print_str .= "\r\n";
                        }
                    }
                    $print_str .= PAPER_LINE."\r\n";
                    // $print_str .= append_chars(strtoupper("TOTAL"),'right',18," ").append_chars($ctr,'right',10," ").append_chars(numInt($ov_total).'','left',10," ")."\r\n";
                    $print_str .= append_chars(substrwords('TOTAL',18,""),"right",PAPER_RD_COL_1," ").align_center($ctr,PAPER_RD_COL_2," ")
                        .append_chars(numInt($ov_total),"left",PAPER_RD_COL_3_3," ")."\r\n\r\n";
                    $print_str .= PAPER_LINE."\r\n";
                }

            }

            if($asJson == false){
                $this->manager_model->add_event_logs($user['id'],"Gift Cards","View");    
            }else{
                $this->manager_model->add_event_logs($user['id'],"Gift Cards","Print");                    
            }

            // echo "<pre>".$print_str."</pre>";
            // $this->do_print($print_str,$asJson);
            if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else if(PRINT_VERSION && PRINT_VERSION == 'V3' && $asJson){
                echo $this->html_print($print_str);  
            }else{
                $this->do_print($print_str,$asJson);
            }
        }
        public function list_sales_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $settled = $trans['sales']['settled']['orders'];
            usort($settled, function($a, $b) {
                return $a->trans_ref - $b->trans_ref;
            });

            $title_name = "Transactions List";
            if($post['title'] != "")
                $title_name = $post['title'];

            $print_str .= align_center($title_name,PAPER_WIDTH," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],PAPER_WIDTH," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),PAPER_WIDTH," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],PAPER_WIDTH," ")."\r\n";

            $print_str .= "\r\n";
            // $total_vat = 0;
            // $total_taxable = 0;
            foreach ($settled as $key => $set){
                // $trans_menus = $this->menu_sales($sales_id,$curr);
                $trans_charges = $this->charges_sales($set->sales_id,$curr);
                $trans_discounts = $this->discounts_sales($set->sales_id,$curr);
                $trans_local_tax = $this->local_tax_sales($set->sales_id,$curr);
                $trans_tax = $this->tax_sales($set->sales_id,$curr);
                $trans_no_tax = $this->no_tax_sales($set->sales_id,$curr);
                $trans_zero_rated = $this->zero_rated_sales($set->sales_id,$curr);

                $print_str .= align_center($set->trans_ref,PAPER_WIDTH," ")."\r\n";
                $print_str .= align_center($set->datetime,PAPER_WIDTH," ")."\r\n";

                $net = $set->total_amount;
                $charges = $trans_charges['total'];
                $discounts = $trans_discounts['total'];
                $tax_disc = $trans_discounts['tax_disc_total'];
                $no_tax_disc = $trans_discounts['no_tax_disc_total'];
                $local_tax = $trans_local_tax['total'];

                $tax = $trans_tax['total'];
                $no_tax = $trans_no_tax['total'];
// echo $no_tax;die();
                $zero_rated = $trans_zero_rated['total'];
                $no_tax -= $zero_rated;
                $net_no_adds = ($net)-$charges-$local_tax;

                $taxable = ( ($net_no_adds + $no_tax_disc) - ($tax + $no_tax));
                // $print_str .= append_chars(substrwords('VAT SALES',18,""),"right",23," ")
                //              .append_chars(numInt(($taxable)),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                                     .append_chars(numInt($taxable),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $total_net = $taxable + $no_tax + $zero_rated + $tax;
                $print_str .= append_chars(substrwords('VAT EXEMPT SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                             .append_chars(numInt(($no_tax)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                             // $print_str .= append_chars($trans_no_tax['total']);
                $print_str .= append_chars(substrwords('ZERO RATED',13,""),"right",PAPER_TOTAL_COL_1," ")
                             .append_chars(numInt(($zero_rated)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(($tax)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars("","right",23," ").append_chars("----------","left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('Total',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(($total_net)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('Charges',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(($charges)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('Local Tax',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(($local_tax)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('Discounts',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(($discounts)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= PAPER_LINE."\r\n";
                $print_str .= append_chars(substrwords('NET SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                             .append_chars(numInt(($set->total_amount)),"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= "\r\n";
                // $this->manager_model->add_event_logs($user['id'],"Transactions List","View");
                // $total_vat += $tax;
                // $total_taxable += $taxable;
            }
            // $print_str .= append_chars(substrwords('VAT',18,""),"right",23," ")
            //  .append_chars(numInt(($total_vat)),"left",13," ")."\r\n";
            // $print_str .= append_chars(substrwords('TAXABLE',18,""),"right",23," ")
            //  .append_chars(numInt(($total_taxable)),"left",13," ")."\r\n";

            ###########
            // $this->do_print($print_str,$asJson);
            // $user = $this->session->userdata('user');
            // $this->manager_model->add_event_logs($user['id'],"Transactions List","Print");
            if($asJson == false){
                $this->manager_model->add_event_logs($user['id'],"Transactions List","View");    
            }else{
                $this->manager_model->add_event_logs($user['id'],"Transactions List","Print");                    
            }
             if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else if(PRINT_VERSION && PRINT_VERSION == 'V3' && $asJson){
                echo $this->html_print($print_str);  
            }else{
                $this->do_print($print_str,$asJson);
            }
        }
        public function void_sales_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $trans_removes = $this->removed_menu_sales($trans['all_ids'],$curr);
            $void = array();
            $cancel = array();
            if(isset($trans['sales']['void']['orders']) && count($trans['sales']['void']['orders']) > 0)
                $void = $trans['sales']['void']['orders'];
            if(isset($trans['sales']['cancel']['orders']) && count($trans['sales']['cancel']['orders']) > 0)
                $cancel = $trans['sales']['cancel']['orders'];

            $title_name = "VOID SALES REPORT";
            $print_str .= align_center($title_name,PAPER_WIDTH," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],PAPER_WIDTH," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),38," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],38," ")."\r\n";


            $print_str .= "\r\n".append_chars(substrwords('Voided Receipts',18,""),"right",18," ").align_center(null,5," ")
                       .append_chars(null,"left",13," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            $total_void_sales = 0;
            if(count($void) > 0){
                foreach ($void as $v) {
                    $order = $trans['all_orders'];
                    #TATLO
                     //  $print_str .= append_chars(substrwords('TOTAL PAYMENTS',18,""),"right",PAPER_RD_COL_1," ").align_center($pay_qty,PAPER_RD_COL_2," ")
                     //      .append_chars(numInt($payments_total),"left",PAPER_RD_COL_3_3," ")."\r\n\r\n";
                    // $print_str .= append_chars(substrwords($v->trans_ref,18,""),"right",18," ").align_center('',5," ")
                    //          .append_chars(numInt($v->total_amount),"left",13," ")."\r\n";
                    #DALAWA
                    $print_str .= append_chars(substrwords($v->trans_ref,18,""),"right",PAPER_TOTAL_COL_1," ")
                                     .append_chars(numInt($v->total_amount),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    if(isset($order[$v->void_ref])){
                        $ord = $order[$v->void_ref];
                        // $print_str .= append_chars(substrwords("Receipt: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").align_center($ord->trans_ref,PAPER_RD_COL_3_3," ")
                        //          .append_chars('',"left",13," ")."\r\n";
                        $print_str .= append_chars(substrwords("Receipt: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").append_chars($ord->trans_ref,'right',PAPER_TOTAL_COL_2," ")."\r\n";
                    }
                    if($v->table_name != ""){
                        // $print_str .= append_chars(substrwords("Table: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").align_center($v->table_name,PAPER_RD_COL_3_3," ")
                        //          .append_chars('',"left",13," ")."\r\n";
                        $print_str .= append_chars(substrwords("Table: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").append_chars($v->table_name,'right',PAPER_TOTAL_COL_2," ")."\r\n";
                    }
                    $server = $this->manager_model->get_server_details($v->user_id);
                    $cashier = $server[0]->username;
                    // $print_str .= append_chars(substrwords("Cashier: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").align_center($cashier,PAPER_RD_COL_3_3," ")
                    //          .append_chars('',"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords("Cashier: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").append_chars($cashier,'right',PAPER_TOTAL_COL_2," ")."\r\n";
                    if(isset($order[$v->void_ref])){
                        $ord = $order[$v->void_ref];
                        $print_str .= append_chars(substrwords("Reason: ",18,""),"right",12," ").align_center('',5," ")."\r\n";
                        $print_str .= append_chars("--".$ord->reason,"right",PAPER_RD_COL_1," ").align_center('',5," ")."\r\n";
                        if($ord->void_user_id != ""){
                            $server = $this->manager_model->get_server_details($ord->void_user_id);
                            $voider = $server[0]->username;
                            // $print_str .= append_chars(substrwords("Approved By: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").align_center($voider,PAPER_RD_COL_3_3," ")
                            //          .append_chars('',"left",13," ")."\r\n";
                            $print_str .= append_chars(substrwords("Approved By: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").append_chars($voider,'right',PAPER_TOTAL_COL_2," ")."\r\n\r\n";
                        }
                    }
                    $total_void_sales += $v->total_amount;
                }
            }
            else{
                $print_str .= append_chars(substrwords("No Sales Found.",PAPER_RD_COL_1,""),"right",18," ").align_center('',5," ")."\r\n";
            }
            $print_str .= "-----------------"."\r\n";
            $print_str .= append_chars(substrwords('Total',18,""),"right",PAPER_TOTAL_COL_1," ")
                                     .append_chars(numInt($total_void_sales),"left",PAPER_TOTAL_COL_2," ")."\r\n";

            $print_str .= "\r\n".append_chars(substrwords('Cancelled Transactions',18,""),"right",18," ").align_center(null,5," ")
                       .append_chars(null,"left",13," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            $total_void_sales = 0;
            if(count($cancel) > 0){
                foreach ($cancel as $v) {
                    $print_str .= append_chars(substrwords('Order #'.$v->sales_id,18,""),"right",PAPER_TOTAL_COL_1," ")
                                     .append_chars(numInt($v->total_amount),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    $server = $this->manager_model->get_server_details($v->user_id);
                    $cashier = $server[0]->username;
                    // $print_str .= append_chars(substrwords("Cashier: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").align_center($cashier,PAPER_RD_COL_3_3," ")
                    //          .append_chars('',"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords("Cashier: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").append_chars($cashier,'right',PAPER_TOTAL_COL_2," ")."\r\n";

                    if($v->table_name != ""){
                        // $print_str .= append_chars(substrwords("Table: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").align_center($v->table_name,PAPER_RD_COL_3_3," ")
                        //          .append_chars('',"left",13," ")."\r\n";
                        $print_str .= append_chars(substrwords("Table: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").append_chars($v->table_name,'right',PAPER_TOTAL_COL_2," ")."\r\n";
                    }
                    $print_str .= append_chars(substrwords("Reason: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").align_center('',5," ")."\r\n";
                    $print_str .= append_chars("--".$v->reason,"right",PAPER_RD_COL_1," ").align_center('',5," ")."\r\n";
                    if($v->void_user_id != ""){
                        $server = $this->manager_model->get_server_details($v->void_user_id);
                        $voider = $server[0]->username;
                        // $print_str .= append_chars(substrwords("Approved By: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").align_center($voider,10," ")
                        //          .append_chars('',"left",13," ")."\r\n";
                        $print_str .= append_chars(substrwords("Approved By: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").append_chars($voider,'right',PAPER_TOTAL_COL_2," ")."\r\n\r\n";
                    }

                    $total_void_sales += $v->total_amount;
                }
            }
            else{
                $print_str .= append_chars(substrwords("No Sales Found. ",PAPER_RD_COL_1,""),"right",18," ").align_center('',5," ")."\r\n";
            }
            $print_str .= "-----------------"."\r\n";
            $print_str .= append_chars(substrwords('Total',18,""),"right",PAPER_TOTAL_COL_1," ")
                                     .append_chars(numInt($total_void_sales),"left",PAPER_TOTAL_COL_2," ")."\r\n";

            $print_str .= "\r\n".append_chars(substrwords('Removed Items',18,""),"right",18," ").align_center(null,5," ")
                       .append_chars(null,"left",13," ")."\r\n";
            $print_str .= "-----------------"."\r\n";

            $total_cancel_order = 0;
            if(count($trans_removes) > 0){
                foreach ($trans_removes as $v) {
                    $print_str .= append_chars(substrwords("Order #".$v['trans_id'],18,""),"right",PAPER_TOTAL_COL_1," ")
                                     .append_chars(null,"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    $print_str .= append_chars(substrwords("Cashier: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").append_chars($v['cashier'],'right',PAPER_TOTAL_COL_2," ")."\r\n";
                             // .append_chars('',"left",13," ")."\r\n";
                    
                    $exp = explode(' - ', $v['item']);
                    // $type = $rea['type'];
                    $name = $exp[1];
                    $qtys = explode(':', $exp[2]);
                    $qty = $qtys[1];
                    if (array_key_exists(3,$exp)){
                        $price = $exp[3];
                    }
                    else{
                        $price = 0; 
                    }
                    $on_id = null;
                    $menuLine = explode(' ',$exp[0]);

                    $total_cancel_order += $price;

                    // $print_str .= append_chars("*".(string)$v['item'],"right",PAPER_RD_COL_1," ").align_center($v['cashier'],PAPER_RD_COL_3_3," ")
                    //          .append_chars('',"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords($name,16,""),"right",16," ").align_center($qty,4," ")
                                 .append_chars(num($price,2),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $print_str .= " ".urldecode($v['reason'])."\r\n";
                    $print_str .= append_chars(substrwords("Approved By: ",PAPER_RD_COL_1,""),"right",PAPER_RD_COL_1," ").align_center($v['manager'],PAPER_RD_COL_3_3," ")
                             .append_chars('',"left",13," ")."\r\n";

                }
            }
            else{
                $print_str .= append_chars(substrwords("No Menus Found. ",18,""),"right",18," ").align_center('',5," ");
            }
            $print_str .= "-----------------"."\r\n";
            $print_str .= append_chars(substrwords('Total',18,""),"right",PAPER_TOTAL_COL_1," ")
                                     .append_chars(numInt($total_cancel_order),"left",PAPER_TOTAL_COL_2," ")."\r\n";

            $print_str .= PAPER_LINE."\r\n";                          

            if($asJson == false){
                $this->manager_model->add_event_logs($user['id'],"Void and Cancelled Sales","View");    
            }else{
                $this->manager_model->add_event_logs($user['id'],"Void and Cancelled Sales","Print");                    
            }

            if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else if(PRINT_VERSION && PRINT_VERSION == 'V3' && $asJson){
                echo $this->html_print($print_str);  
            }else{
                $this->do_print($print_str,$asJson);
            }
            // $this->do_print($print_str,$asJson);
        }
        public function menu_sales_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
            $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
            $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
            $payments = $this->payment_sales($sales['settled']['ids'],$curr);
            $gross = $trans_menus['gross'];
            $net = $trans['net'];
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

            $title_name = "MENU ITEM SALES REPORT";
            $print_str .= align_center($title_name,PAPER_WIDTH," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],PAPER_WIDTH," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),PAPER_WIDTH," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],PAPER_WIDTH," ")."\r\n";

            $print_str .= "\r\n";
            #CATEGORIES
                $cats = $trans_menus['cats'];
                $menus = $trans_menus['menus'];
                $menu_total = $trans_menus['menu_total'];
                $total_qty = $trans_menus['total_qty'];
                usort($cats, function($a, $b) {
                    return $b['amount'] - $a['amount'];
                });
                foreach ($cats as $cat_id => $ca) {
                    if($ca['qty'] > 0){
                        // $print_str .=
                        //      append_chars($ca['name'],'right',18," ")
                        //     .append_chars(num($ca['qty']),'right',10," ")
                        //     .append_chars(num($ca['amount']).'','left',10," ")."\r\n";
                        $print_str .= append_chars($ca['name'],"right",PAPER_RD_COL_1," ").align_center(num($ca['qty']),PAPER_RD_COL_2," ")
                            .append_chars(num($ca['amount']).'',"left",9," ")."\r\n";
                        $print_str .= PAPER_LINE."\r\n";
                        foreach ($menus as $menu_id => $res) {
                            if($ca['cat_id'] == $res['cat_id']){
                            $print_str .=
                                append_chars($res['name'],'right',PAPER_RD_COL_1," ")
                                .append_chars(num($res['qty']),'right',PAPER_RD_COL_2," ")
                                .append_chars(num($res['amount']),'left',9," ")
                                ."\r\n";
                            // $print_str .=
                            //     append_chars(null,'right',PAPER_RD_COL_1," ")
                            //     .append_chars(num($res['amount']),'right',PAPER_RD_COL_2," ")
                            //     // .append_chars(num( ($res['amount'] / $menu_total) * 100).'%','left',9," ")
                            //     ."\r\n";

                                foreach ($trans_menus['mods'] as $mnu_id => $mods) {
                                    if($mnu_id == $res['menu_id']){
                                        foreach ($mods as $mod_id => $val) {
                                            $print_str .= append_chars('*'.$val['name'],"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
                                                   .append_chars(numInt($val['total_amt']),"left",PAPER_RD_COL_3_3," ")."\r\n";

                                            // if(isset($val['submodifiers'])){
                                                foreach($trans_menus['submods'] as $m_id => $mval){

                                                    if($mod_id == $m_id){

                                                        foreach ($mval as $sub_id => $sval) {
                                                            # code...
                                                            $print_str .= append_chars('     '.$sval['name'],"right",PAPER_RD_COL_1," ").align_center($sval['qty'],PAPER_RD_COL_2," ")
                                                            .append_chars(numInt($sval['total_amt']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                                                        }

                                                    }

                                                }
                                            // }
                                        }
                                    } 
                                 }
                            }
                        }
                    $print_str .= PAPER_LINE."\r\n";
                    }
                }
            #SUBCATEGORIES
            $print_str .= "\r\n";
                $subcats = $trans_menus['sub_cats'];
                $print_str .= PAPER_LINE."\r\n";
                $print_str .= append_chars('SUBCATEGORIES:',"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $print_str .= PAPER_LINE."\r\n";
                $qty = 0;
                $total = 0;
                foreach ($subcats as $id => $val) {
                    $print_str .= append_chars($val['name'],"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
                               .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $qty += $val['qty'];
                    $total += $val['amount'];
                 }
            // #MODIFIERS
            // $print_str .= "\r\n";
            //     $mods = $trans_menus['mods'];
            //     $print_str .= PAPER_LINE."\r\n";
            //     $print_str .= append_chars('MODIFIERS:',"right",18," ").align_center('',5," ")
            //                  .append_chars('',"left",13," ")."\r\n";
            //     $print_str .= PAPER_LINE."\r\n";
            //     $qty = 0;
            //     $total = 0;
            //     foreach ($mods as $id => $val) {
            //         $print_str .= append_chars($val['name'],"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
            //                    .append_chars(numInt($val['total_amt']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $qty += $val['qty'];
            //         $total += $val['total_amt'];
            //      }

            $print_str .= "\r\n".PAPER_LINE."\r\n";
            $net_no_adds = $net-$charges-$local_tax;
            $print_str .= append_chars(substrwords('TOTAL SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                         .append_chars(numInt(($net)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            $txt = numInt(($charges));
            if($charges > 0)
                $txt = "(".numInt(($charges)).")";
            $print_str .= append_chars(substrwords('Charges',18,""),"right",PAPER_TOTAL_COL_1," ")
                         .append_chars($txt,"left",PAPER_TOTAL_COL_2," ")."\r\n";

            $txt = numInt(($local_tax));
            if($local_tax > 0)
                $txt = "(".numInt(($local_tax)).")";
            // $print_str .= append_chars(substrwords('Local Tax',18,""),"right",25," ")
            //              .append_chars($txt,"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('Local Tax',18,""),"right",PAPER_TOTAL_COL_1," ")
                            .append_chars(numInt($txt),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            $print_str .= append_chars(substrwords('Discounts',18,""),"right",PAPER_TOTAL_COL_1," ")
                         .append_chars(numInt(($discounts)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            $print_str .= append_chars(substrwords('LESS VAT',18,""),"right",PAPER_TOTAL_COL_1," ")
                         .append_chars(numInt(($less_vat)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            $print_str .= append_chars(substrwords('GROSS SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                         .append_chars(numInt(($gross)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";

            if($asJson == false){
                $this->manager_model->add_event_logs($user['id'],"Menu Item Sales","View");    
            }else{
                $this->manager_model->add_event_logs($user['id'],"Menu Item Sales","Print");                    
            }

            if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else if(PRINT_VERSION && PRINT_VERSION == 'V3' && $asJson){
                echo $this->html_print($print_str);
            }else{
                $this->do_print($print_str,$asJson);
            }

        }
        public function system_sales_rep_peri($asJson=false){
            //periperi
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            // $curr = true;
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
            $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
            $tax_disc = $trans_discounts['tax_disc_total'];
            $no_tax_disc = $trans_discounts['no_tax_disc_total'];
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
            $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
            $payments = $this->payment_sales($sales['settled']['ids'],$curr);



            // $gross = $trans_menus['gross'] - $trans['void'];
            $gross = $trans_menus['gross'];
            $net = $trans['net'];
            $void = $trans['void'];
            $charges = $trans_charges['total'];
            $discounts = $trans_discounts['total'];
            $local_tax = $trans_local_tax['total'];
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            // $less_vat  = $no_tax_disc * 0.60;
            if($less_vat < 0)
                $less_vat = 0;

            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;

            $title_name = "SYSTEM SALES REPORT";
            if($post['title'] != "")
                $title_name = $post['title'];

            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],38," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),38," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],38," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= append_chars('SETTLEMENT','right',11," ")."\r\n";
            $print_str .= "--------------------------------------"."\r\n";

            $loc_txt = numInt(($local_tax));
            // if($local_tax > 0)
            //     $loc_txt = "(".numInt(($local_tax)).")";
            $net_no_adds = $net-($charges+$local_tax);
            $nontaxable = $no_tax - $no_tax_disc;
            // $taxable =   ($net_no_adds - ($tax + ($nontaxable+$zero_rated))  );
            $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
            // $taxable =   $discounts;
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
            $add_gt = $taxable+$nontaxable+$zero_rated;
            // $taxable = ($net_no_adds - ($tax + $no_tax + $zero_rated));
            $nsss = $taxable +  $nontaxable +  $zero_rated;
            $print_str .= append_chars(substrwords('GROSS SALES',18,""),"right",23," ")
                                     .append_chars(numInt(($gross)),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('LESS : TOTAL DISCOUNTS',24,""),"right",24," ")
                                     .append_chars('',"left",13," ")."\r\n";
            #Discounts
            $types = $trans_discounts['types'];
            $qty = 0;
            // $print_str .= append_chars(substrwords('Discounts:',18,""),"right",18," ").align_center(null,5," ")
            //               .append_chars(null,"left",13," ")."\r\n";
            foreach ($types as $code => $val) {
                $print_str .= append_chars(substrwords(ucwords(strtoupper($val['name'])),18,""),"left",18," ").align_center('',5," ")
                              .append_chars('('.Num($val['amount'],2).')',"left",13," ")."\r\n";
                $qty += $val['qty'];
            }
            $print_str .= append_chars(substrwords(ucwords(strtoupper('SEN. VAT EXEMPT')),18,""),"left",18," ").align_center('',5," ")
                              .append_chars('('.numInt($less_vat).")","left",13," ")."\r\n";
            $print_str .= "--------------------------------------"."\r\n";
            $final_gross = $gross + $charges;
            $print_str .= append_chars(substrwords('GROSS NET OF DISCOUNT',23,""),"right",23," ")
                                     .append_chars(numInt(($gross - $discounts - $less_vat)),"left",13," ")."\r\n";
            $print_str .= "--------------------------------------"."\r\n";
            #PAYMENTS
            // $payments_types = $payments['types'];
            // $payments_total = $payments['total'];
            // $pay_qty = 0;
            // // $print_str .= append_chars(substrwords('Payment Breakdown:',18,""),"right",18," ").align_center(null,5," ")
            // //               .append_chars(null,"left",13," ")."\r\n";
            // foreach ($payments_types as $code => $val) {
            //     $print_str .= append_chars(substrwords(ucwords(strtoupper($code)),18,""),"right",18," ").align_center($val['qty'],5," ")
            //                   .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
            //     $pay_qty += $val['qty'];
            // }
            // //$print_str .= "--------------------------------------"."\r\n";
            // $print_str .= append_chars("","right",18," ").align_center("",5," ")
            //               .append_chars('-----------',"left",13," ")."\r\n";
            // $print_str .= append_chars(substrwords('TOTAL PAYMENTS',18,""),"right",18," ").align_center($pay_qty,5," ")
            //               .append_chars(numInt($payments_total),"left",13," ")."\r\n";
            $payments_types = $payments['ov_types'];
            $payments_total = $payments['ov_total'];
            $net_payments_total = $payments['total'];
            $pay_qty = 0;
            $print_str .= append_chars(substrwords('Payment Breakdown:',18,""),"right",18," ").align_center(null,5," ")
                          .append_chars(null,"left",13," ")."\r\n";
            foreach ($payments_types as $code => $val) {
                $print_str .= append_chars(substrwords(ucwords(strtolower($code)),18,""),"right",18," ").align_center($val['qty'],5," ")
                              .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                $pay_qty += $val['qty'];
            }
            $print_str .= "-----------------"."\r\n";
            $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                          .append_chars(numInt($payments_total),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords("GC Excess Amount",18,""),"right",18," ").align_center("",5," ")
                          .append_chars("(".numInt($payments_total - $net_payments_total).")","left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('Net Payment',18,""),"right",18," ").align_center("",5," ")
                          .append_chars(numInt($net_payments_total),"left",13," ")."\r\n";


            $print_str .= "======================================"."\r\n";
            $print_str .= append_chars('SALES ACCOUNTING','right',11," ")."\r\n\r\n";
            //$print_str .= "--------------------------------------"."\r\n";

            $print_str .= append_chars(substrwords('GROSS NET OF DISCOUNT',23,""),"right",23," ")
                                     .append_chars(numInt(($gross - $discounts - $less_vat)),"left",13," ")."\r\n";
            // $print_str .= append_chars(substrwords('ADD : TOTAL CHARGES',23,""),"right",23," ")
            //                          .append_chars('',"left",13," ")."\r\n";
            // $print_str .= append_chars("","right",18," ").align_center("",5," ")
            //               .append_chars('-----------',"left",13," ")."\r\n";
            // $print_str .= append_chars(substrwords('GROSS LESS DISCOUNT',23,""),"right",23," ")
            //                          .append_chars(numInt(($final_gross - $discounts - $less_vat)),"left",13," ")."\r\n";
            //$print_str .= "--------------------------------------"."\r\n";
            // $print_str .= append_chars("","right",18," ").align_center("",5," ")
            //               .append_chars('-----------',"left",13," ")."\r\n";

            #CHARGES
            $types = $trans_charges['types'];
            $qty = 0;
            // $print_str .= append_chars(substrwords('Charges:',18,""),"right",18," ").align_center(null,5," ")
            //               .append_chars(null,"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('ADD : TOTAL CHARGES',23,""),"right",23," ").align_center('',5," ")
                          .append_chars('',"left",13," ")."\r\n";
            foreach ($types as $code => $val) {
                $print_str .= append_chars(substrwords(ucwords(strtoupper($val['name'])),18,""),"left",18," ").align_center('',5," ")
                              .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                $qty += $val['qty'];
            }
            $print_str .= append_chars(substrwords(ucwords(strtoupper('Local Tax')),18,""),"left",18," ").align_center('',5," ")
                          .append_chars(numInt($local_tax),"left",13," ")."\r\n";
            // $print_str .= "-----------------"."\r\n";
            // $print_str .= "\r\n";

            $vat_ = $taxable * .12;
            // $print_str .= append_chars(substrwords('VAT',18,""),"right",18," ").align_center('',5," ")
            //               .append_chars(numInt($vat_),"left",13," ")."\r\n";
            $print_str .= "--------------------------------------"."\r\n";
            // $print_str .= append_chars("","right",18," ").align_center("",5," ")
            //               .append_chars('-----------',"left",13," ")."\r\n";
            $gross_net_disc = $gross - $discounts - $less_vat;
            $print_str .= append_chars(substrwords('GROSS NET OF DISCOUNT',23,""),"right",23," ")
                                     .append_chars(numInt(($gross_net_disc + $charges + $local_tax)),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('WITH CHARGES',23,""),"right",23," ")
                                     .append_chars('',"left",13," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= append_chars(substrwords('VAT SALES',23,""),"right",23," ")
                                     .append_chars(numInt($taxable),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('VAT',23,""),"right",23," ")
                                     .append_chars(numInt($vat_),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('VAT EXEMPT SALES',23,""),"right",23," ")
                                     .append_chars(numInt($nontaxable-$zero_rated),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('ZERO RATED',23,""),"right",23," ")
                                     .append_chars(numInt($zero_rated),"left",13," ")."\r\n";
            $print_str .= "--------------------------------------"."\r\n";
            $gross_less_disc = $final_gross - $discounts - $less_vat;
            $print_str .= append_chars(substrwords('NET SALES',23,""),"right",23," ")
                                     .append_chars(numInt(($taxable + $nontaxable + $vat_)),"left",13," ")."\r\n";
                                     // .append_chars(numInt(($taxable + $nontaxable + $zero_rated + $vat_)),"left",13," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= append_chars(substrwords('VOID SALES',18,""),"right",23," ")
                         .append_chars(numInt(($void)),"left",13," ")."\r\n";


            // $print_str .= "-----------------"."\r\n";
            // $print_str .= append_chars(substrwords('Total Discounts',18,""),"right",18," ").align_center($qty,5," ")
            //               .append_chars(numInt($discounts),"left",13," ")."\r\n";
            // $print_str .= append_chars(substrwords('VAT EXEMPT',18,""),"right",23," ")
            //                          .append_chars(numInt($less_vat),"left",13," ")."\r\n";
            // $print_str .= "\r\n";


            // $print_str .= append_chars(substrwords('NET SALES',18,""),"right",23," ")
            //              .append_chars(numInt(($nsss)),"left",13," ")."\r\n";
            // $print_str .= append_chars(substrwords('VAT',18,""),"right",23," ")
            //                          .append_chars(numInt($tax),"left",13," ")."\r\n";
            // $print_str .= append_chars(substrwords('Local Tax',18,""),"right",23," ")
            //              .append_chars($loc_txt,"left",13," ")."\r\n";
            // $print_str .= append_chars("","right",23," ").append_chars("-----------","left",13," ")."\r\n";
            // $print_str .= append_chars(substrwords('VOID SALES',18,""),"right",23," ")
            //              .append_chars(numInt(($void)),"left",13," ")."\r\n";



            $print_str .= "\r\n";
            #TRANS COUNT
                $types = $trans['types'];
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
                $print_str .= append_chars(substrwords('Trans Count:',18,""),"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $tc_total  = 0;
                $tc_qty = 0;
                foreach ($types_total as $typ => $tamnt) {
                    $print_str .= append_chars(substrwords($typ,18,""),"right",18," ").align_center(count($types[$typ]),5," ")
                                 .append_chars(numInt($tamnt),"left",13," ")."\r\n";
                    $tc_total += $tamnt;
                    $tc_qty += count($types[$typ]);
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('TC Total',18,""),"right",23," ")
                             .append_chars(numInt($tc_total),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('GUEST Total',18,""),"right",23," ")
                             .append_chars(numInt($guestCount),"left",13," ")."\r\n";
                if($tc_total == 0 || $tc_qty == 0)
                    $avg = 0;
                else
                    $avg = $tc_total/$tc_qty;
                $print_str .= append_chars(substrwords('AVG Check',18,""),"right",23," ")
                             .append_chars(numInt($avg),"left",13," ")."\r\n";
            $print_str .= "\r\n";
            #CHARGES
                $types = $trans_charges['types'];
                $qty = 0;
                $print_str .= append_chars(substrwords('Charges:',18,""),"right",18," ").align_center(null,5," ")
                              .append_chars(null,"left",13," ")."\r\n";
                foreach ($types as $code => $val) {
                    $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",18," ").align_center($val['qty'],5," ")
                                  .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    $qty += $val['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Charges',18,""),"right",18," ").align_center($qty,5," ")
                              .append_chars(numInt($charges),"left",13," ")."\r\n";
            $print_str .= "\r\n";
            #Discounts
                $types = $trans_discounts['types'];
                $qty = 0;
                $print_str .= append_chars(substrwords('Discounts:',18,""),"right",18," ").align_center(null,5," ")
                              .append_chars(null,"left",13," ")."\r\n";
                foreach ($types as $code => $val) {
                    $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",18," ").align_center($val['qty'],5," ")
                                  .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    $qty += $val['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Discounts',18,""),"right",18," ").align_center($qty,5," ")
                              .append_chars(numInt($discounts),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT EXEMPT',18,""),"right",23," ")
                                         .append_chars(numInt($less_vat),"left",13," ")."\r\n";
            $print_str .= "\r\n";
            #PAYMENTS
                ####### OLD ##########
                    // $payments_types = $payments['types'];
                    // $payments_total = $payments['total'];
                    // $pay_qty = 0;
                    // $print_str .= append_chars(substrwords('Payment Breakdown:',18,""),"right",18," ").align_center(null,5," ")
                    //               .append_chars(null,"left",13," ")."\r\n";
                    // foreach ($payments_types as $code => $val) {
                    //     $print_str .= append_chars(substrwords(ucwords(strtolower($code)),18,""),"right",18," ").align_center($val['qty'],5," ")
                    //                   .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    //     $pay_qty += $val['qty'];
                    // }
                    // $print_str .= "-----------------"."\r\n";
                    // $print_str .= append_chars(substrwords('Total Payments',18,""),"right",18," ").align_center($pay_qty,5," ")
                    //               .append_chars(numInt($payments_total),"left",13," ")."\r\n";
                ####### OLD ##########
                $payments_types = $payments['ov_types'];
                $payments_total = $payments['ov_total'];
                $net_payments_total = $payments['total'];
                $pay_qty = 0;
                $print_str .= append_chars(substrwords('Payment Breakdown:',18,""),"right",18," ").align_center(null,5," ")
                              .append_chars(null,"left",13," ")."\r\n";
                foreach ($payments_types as $code => $val) {
                    $print_str .= append_chars(substrwords(ucwords(strtolower($code)),18,""),"right",18," ").align_center($val['qty'],5," ")
                                  .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    $pay_qty += $val['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Payment',18,""),"right",18," ").align_center($pay_qty,5," ")
                              .append_chars(numInt($payments_total),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords("GC Excess Amount",18,""),"right",18," ").align_center("",5," ")
                              .append_chars("(".numInt($payments_total - $net_payments_total).")","left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Net Payment',18,""),"right",18," ").align_center("",5," ")
                              .append_chars(numInt($net_payments_total),"left",13," ")."\r\n";
            $print_str .= "\r\n";
            #CATEGORIES
                $cats = $trans_menus['cats'];
                $print_str .= append_chars('Menu Categories:',"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $qty = 0;
                $total = 0;
                foreach ($cats as $id => $val) {
                    $print_str .= append_chars(substrwords($val['name'],18,""),"right",18," ").align_center($val['qty'],5," ")
                               .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    $qty += $val['qty'];
                    $total += $val['amount'];
                 }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars("SubTotal","right",18," ").align_center($qty,5," ")
                              .append_chars(numInt($total),"left",13," ")."\r\n";
                $print_str .= append_chars("Modifiers Total","right",18," ").align_center('',5," ")
                              .append_chars(numInt($trans_menus['mods_total']),"left",13," ")."\r\n";
                $print_str .= append_chars("Total","right",18," ").align_center('',5," ")
                              .append_chars(numInt($total+$trans_menus['mods_total']),"left",13," ")."\r\n";
            $print_str .= "\r\n";
            #SUBCATEGORIES
                $subcats = $trans_menus['sub_cats'];
                $print_str .= append_chars('Menu Subcategories:',"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $qty = 0;
                $total = 0;
                foreach ($subcats as $id => $val) {
                    $print_str .= append_chars($val['name'],"right",18," ").align_center($val['qty'],5," ")
                               .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    $qty += $val['qty'];
                    $total += $val['amount'];
                 }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars("SubTotal","right",18," ").align_center($qty,5," ")
                              .append_chars(numInt($total),"left",13," ")."\r\n";
                $print_str .= append_chars("Modifiers Total","right",18," ").align_center('',5," ")
                              .append_chars(numInt($trans_menus['mods_total']),"left",13," ")."\r\n";
                $print_str .= append_chars("Total","right",18," ").align_center('',5," ")
                              .append_chars(numInt($total+$trans_menus['mods_total']),"left",13," ")."\r\n";


            // $print_str .= "\r\n";
            // $print_str .= "======================================"."\r\n";
            // $print_str .= append_chars(substrwords('VATABLE SALES',18,""),"right",23," ")
            //              .append_chars(numInt(($taxable)),"left",13," ")."\r\n";
            // $print_str .= append_chars(substrwords('VAT EXEMPT SALES',18,""),"right",23," ")
            //              .append_chars(numInt(($nontaxable)),"left",13," ")."\r\n";
            // $print_str .= append_chars(substrwords('ZERO RATED',13,""),"right",23," ")
            //              .append_chars(numInt(($zero_rated)),"left",13," ")."\r\n";
            // $print_str .= append_chars("","right",23," ").append_chars("-----------","left",13," ")."\r\n";
            // $print_str .= append_chars(substrwords('NET SALES',18,""),"right",23," ")
            //                          .append_chars(numInt(($nsss)),"left",13," ")."\r\n";
            $print_str .= "\r\n";
            $print_str .= append_chars(substrwords('Invoice Start: ',18,""),"right",18," ").align_center('',5," ")
                         .append_chars(iSetObj($trans['first_ref'],'trans_ref'),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('Invoice End: ',18,""),"right",18," ").align_center('',5," ")
                         .append_chars(iSetObj($trans['last_ref'],'trans_ref'),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('Invoice Ctr: ',18,""),"right",18," ").align_center('',5," ")
                         .append_chars($trans['ref_count'],"left",13," ")."\r\n";
            if($title_name == "ZREAD"){
                // $gt = $this->old_grand_total($post['from']);
                $gt = $this->old_grand_net_total($post['from']);
                $print_str .= "\r\n";
                $print_str .= append_chars(substrwords('OLD GT: ',18,""),"right",18," ").align_center('',5," ")
                             // .append_chars(numInt( $gt['old_grand_total'] - ($charges + $local_tax) ),"left",13," ")."\r\n";
                             .append_chars(numInt( $gt['old_grand_total']),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('NEW GT: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars( numInt($gt['old_grand_total']+$net_no_adds)  ,"left",13," ")."\r\n";
                             // .append_chars( numInt($gt['old_grand_total']+$net)  ,"left",13," ")."\r\n";
                             // .append_chars( numInt($gt['old_grand_total']+$gross)  ,"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Z READ CTR: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars( $gt['ctr'] ,"left",13," ")."\r\n";
            }

            $print_str .= "======================================"."\r\n";


            
            if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else{
                $this->do_print($print_str,$asJson);
            }
        }
        public function system_sales_rep($asJson=false){
            ////hapchan
            ini_set('memory_limit', '-1');
            set_time_limit(3600);
            
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            // var_dump($trans['net']); die();
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
            $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
            $tax_disc = $trans_discounts['tax_disc_total'];
            $no_tax_disc = $trans_discounts['no_tax_disc_total'];
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
            $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
            $payments = $this->payment_sales($sales['settled']['ids'],$curr);

            $gross = $trans_menus['gross'];
            
            $net = $trans['net'];
            $void = $trans['void'];
            $cancelled = $trans['cancel_amount'];
            $charges = $trans_charges['total'];
            $discounts = $trans_discounts['total'];
            $local_tax = $trans_local_tax['total'];
            // echo $gross.' - '.$charges.' - '.$discounts.' - '.$net; die();
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            // $less_vat = $trans_discounts['vat_exempt_total'];

            // echo $gross.'+'.$charges.'+'.$local_tax.' - '.$discounts.' - '.$net;
            // die();

            if($less_vat < 0)
                $less_vat = 0;
            // var_dump($less_vat);

            //para mag tugmam yun payments and netsale
            // $net_sales2 = $gross + $charges - $discounts - $less_vat;
            // $diffs = $net_sales2 - $payments['total'];
            // if($diffs < 1){
            //     $less_vat = $less_vat + $diffs;
            // }
            

            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;

            $title_name = "SYSTEM SALES REPORT";
            if($post['title'] != "")
                $title_name = $post['title'];

            $print_str .= align_center($title_name,PAPER_WIDTH," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],PAPER_WIDTH," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),PAPER_WIDTH," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],PAPER_WIDTH," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";

            $loc_txt = numInt(($local_tax));
            $net_no_adds = $net-($charges+$local_tax);
            // $nontaxable = $no_tax;
            //binago 9/25/2018 for zreading adjustment of vat exempt equal to the receipt vat exempt
            $nontaxable = $no_tax - $no_tax_disc;
            // echo $gross.' - '.$less_vat.' - '.$nontaxable.' - '.$zero_rated; die();
            // $taxable = ($gross - $less_vat - $nontaxable - $zero_rated) / 1.12;
            // 1.12; binago din para sa adjustment of vat exempt equal to the receipt vat exempt
            // $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
            $taxable =   ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12; //change computation conflict for zero rated 10 17 2018
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
            $add_gt = $taxable+$nontaxable+$zero_rated;
            $nsss = $taxable +  $nontaxable +  $zero_rated;

            #GENERAL
                $print_str .= append_chars(substrwords('TOTAL SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(num($gross + $charges,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords(ucwords(strtoupper('SC/PWD VAT EXEMPT')),18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('-'.num($less_vat,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars('',"right",12," ").align_center('',PAPER_TOTAL_COL_2," ")
                                  .append_chars('----------',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= append_chars(substrwords('GROSS SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(num($gross + $charges - $less_vat,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";

                // $types = $trans_charges['types'];
                // $qty = 0;
                // foreach ($types as $code => $val) {
                //     $amount = $val['amount'];
                //     $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
                //                          .append_chars('-'.num($amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                //     $qty += $val['qty'];
                // }
                $types = $trans_discounts['types'];
                $qty = 0;
                foreach ($types as $code => $val) {
                    if($code != 'DIPLOMAT'){
                        $amount = $val['amount'];
                        // if(MALL == 'megamall' && $code == PWDDISC){
                        //     $amount = $val['amount'] / 1.12;
                        // }
                        $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
                                             .append_chars('-'.Num($amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                        $qty += $val['qty'];
                    }
                }
                $print_str .= append_chars('',"right",12," ").align_center('',PAPER_TOTAL_COL_2," ")
                                  .append_chars('----------',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $net_sales = $gross + $charges - $discounts - $less_vat;
                $print_str .= append_chars(substrwords(ucwords(strtoupper('NET SALES')),18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(num($net_sales,2),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";
            #PAYMENTS
                $payments_types = $payments['types'];
                $payments_total = $payments['total'];
                $pay_qty = 0;
            #SUMMARY
                $final_gross = $gross;
                $vat_ = $taxable * .12;
                $print_str .= append_chars(substrwords('VAT SALES',23,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($taxable),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT',23,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($vat_),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT EXEMPT SALES',23,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($nontaxable),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                                         // .append_chars(numInt($nontaxable-$zero_rated),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('ZERO RATED',23,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($zero_rated),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";
                $print_str .= append_chars(substrwords('Payment Breakdown:',18,""),"right",PAPER_RD_COL_1," ").align_center(null,PAPER_RD_COL_2," ")
                              .append_chars(null,"left",PAPER_RD_COL_3," ")."\r\n";
                foreach ($payments_types as $code => $val) {
                    $print_str .= append_chars(substrwords(ucwords(strtoupper($code)),18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
                                  .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $pay_qty += $val['qty'];
                }
                $print_str .= append_chars('',"right",18," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars('----------',"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords('TOTAL PAYMENTS',18,""),"right",PAPER_RD_COL_1," ").align_center($pay_qty,PAPER_RD_COL_2," ")
                              .append_chars(numInt($payments_total),"left",PAPER_RD_COL_3_3," ")."\r\n\r\n";
                $print_str .= PAPER_LINE_SINGLE."\r\n";
                $gross_less_disc = $final_gross - $discounts - $less_vat;
                // $print_str .= append_chars(substrwords('NET SALES',23,""),"right",PAPER_TOTAL_COL_1," ")
                //                          // .append_chars(numInt(($taxable + $nontaxable + $zero_rated + $vat_)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                //                          .append_chars(numInt(($taxable + $nontaxable + $vat_)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= PAPER_LINE."\r\n";
                $print_str .= append_chars(substrwords('VOID SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                             .append_chars(num(($void),2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('CANCELLED TRANS',18,""),"right",PAPER_TOTAL_COL_1," ")
                             .append_chars(num(($cancelled),2),"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $cancelled_order = $this->cancelled_orders();
                $co = $cancelled_order['cancelled_order'];
                $print_str .= append_chars(substrwords('CANCELLED ORDERS',18,""),"right",PAPER_TOTAL_COL_1," ")
                             .append_chars(num(($co),2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('Local Tax',18,""),"right",PAPER_TOTAL_COL_1," ")
                             .append_chars($loc_txt,"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= "\r\n";
            #TRANS COUNT
                $types = $trans['types'];
                $types_total = array();
                $guestCount = 0;
                foreach ($types as $type => $tp) {
                    foreach ($tp as $id => $opt){
                        if(isset($types_total[$type])){
                            $types_total[$type] += round($opt->total_amount,2);

                        }
                        else{
                            $types_total[$type] = round($opt->total_amount,2);
                        }

                        // if($opt->type == 'dinein'){
                        //     $guestCount += $opt->guest;
                        // }
                        if($opt->guest == 0)
                            $guestCount += 1;
                        else
                            $guestCount += $opt->guest;
                    }
                }
                $print_str .= append_chars(substrwords('Trans Count:',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                             .append_chars('',"left",PAPER_RD_COL_3_3," ")."\r\n";
                $tc_total  = 0;
                $tc_qty = 0;
                foreach ($types_total as $typ => $tamnt) {
                    $print_str .= append_chars(substrwords($typ,18,""),"right",PAPER_RD_COL_1," ").align_center(count($types[$typ]),PAPER_RD_COL_2," ")
                                 .append_chars(numInt($tamnt),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $tc_total += $tamnt;
                    $tc_qty += count($types[$typ]);
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('TC Total',18,""),"right",PAPER_TOTAL_COL_1," ")
                             .append_chars(numInt($tc_total),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('GUEST Total',18,""),"right",PAPER_TOTAL_COL_1," ")
                             .append_chars($guestCount,"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // if($tc_total == 0 || $tc_qty == 0)
                //     $avg = 0;
                // else
                //     $avg = $tc_total/$tc_qty;
                if($net_sales){
                    if($guestCount == 0){
                        $avg = 0;
                    }else{
                        $avg = $net_sales/$guestCount;
                    }
                }else{
                    $avg = 0;
                }


                $print_str .= append_chars(substrwords('AVG Check',18,""),"right",PAPER_TOTAL_COL_1," ")
                             .append_chars(numInt($avg),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= "\r\n";
            #CHARGES
                $types = $trans_charges['types'];
                $qty = 0;
                $print_str .= append_chars(substrwords('Charges:',18,""),"right",18," ").align_center(null,5," ")
                              .append_chars(null,"left",13," ")."\r\n";
                foreach ($types as $code => $val) {
                    $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
                                  .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $qty += $val['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Charges',18,""),"right",PAPER_RD_COL_1," ").align_center($qty,PAPER_RD_COL_2," ")
                              .append_chars(numInt($charges),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= "\r\n";
            #Discounts
                $types = $trans_discounts['types'];
                $qty = 0;
                $print_str .= append_chars(substrwords('Discounts:',18,""),"right",PAPER_RD_COL_1," ").align_center(null,PAPER_RD_COL_2," ")
                              .append_chars(null,"left",PAPER_RD_COL_3," ")."\r\n";
                foreach ($types as $code => $val) {
                    if($code != 'DIPLOMAT'){
                        $amount = $val['amount'];
                        // if(MALL == 'megamall' && $code == PWDDISC){
                        //     $amount = $val['amount'] / 1.12;
                        // }
                        $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
                                      .append_chars(numInt($amount),"left",PAPER_RD_COL_3_3," ")."\r\n";
                        $qty += $val['qty'];
                    }
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Discounts',18,""),"right",PAPER_RD_COL_1," ").align_center($qty,PAPER_RD_COL_2," ")
                              .append_chars(numInt($discounts),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT EXEMPT',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($less_vat),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= "\r\n";
            #PAYMENTS
                $payments_types = $payments['types'];
                $payments_total = $payments['total'];
                $pay_qty = 0;
                $print_str .= append_chars(substrwords('Payment Breakdown:',18,""),"right",PAPER_RD_COL_1," ").align_center(null,PAPER_RD_COL_2," ")
                              .append_chars(null,"left",PAPER_RD_COL_3," ")."\r\n";
                foreach ($payments_types as $code => $val) {
                    $print_str .= append_chars(substrwords(ucwords(strtolower($code)),18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
                                  .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $pay_qty += $val['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Payments',18,""),"right",PAPER_RD_COL_1," ").align_center($pay_qty,PAPER_RD_COL_2," ")
                              .append_chars(numInt($payments_total),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= "\r\n";

                //card breakdown
                if($payments['cards']){
                    $cards = $payments['cards'];
                    $card_total = 0;
                    $count_total = 0;
                    $print_str .= append_chars(substrwords('Card Breakdown:',18,""),"right",PAPER_RD_COL_1," ").align_center(null,PAPER_RD_COL_2," ")
                              .append_chars(null,"left",PAPER_RD_COL_3," ")."\r\n";
                    foreach($cards as $key => $val){
                        $print_str .= append_chars(substrwords($key,18,""),"right",PAPER_RD_COL_1," ").align_center($val['count'],PAPER_RD_COL_2," ")
                                  .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                        $card_total += $val['amount'];
                        $count_total += $val['count'];
                    }
                    $print_str .= "-----------------"."\r\n";
                    $print_str .= append_chars(substrwords('Total',18,""),"right",PAPER_RD_COL_1," ").align_center($count_total,PAPER_RD_COL_2," ")
                              .append_chars(numInt($card_total),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    
                    $print_str .= "\r\n";
                }

                //get all gc with excess
                if($payments['gc_excess']){
                    $print_str .= append_chars(substrwords('GC EXCESS',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($payments['gc_excess']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $print_str .= "\r\n";
                }

                //show all sign chit
                // $trans['sales']
                if($trans['total_chit']){
                    $print_str .= append_chars(substrwords('TOTAL CHIT',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($trans['total_chit']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $print_str .= "\r\n";
                }
            #CATEGORIES
                $cats = $trans_menus['cats'];
                $print_str .= append_chars('Menu Categories:',"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                             .append_chars('',"left",PAPER_RD_COL_3," ")."\r\n";
                $qty = 0;
                $total = 0;
                foreach ($cats as $id => $val) {
                    if($val['qty'] > 0){
                        $print_str .= append_chars(substrwords($val['name'],18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
                                   .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                        $qty += $val['qty'];
                        $total += $val['amount'];
                    }
                 }
                $print_str .= "-----------------"."\r\n";
                $cat_total_qty = $qty;
                $print_str .= append_chars("SubTotal","right",PAPER_RD_COL_1," ").align_center($qty,PAPER_RD_COL_2," ")
                              .append_chars(numInt($total),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars("Modifiers Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                              .append_chars(numInt($trans_menus['mods_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                 $print_str .= append_chars("SubModifier Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                              .append_chars(numInt($trans_menus['submods_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                if($trans_menus['item_total'] > 0){
                 $print_str .= append_chars("Retail Items Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                               .append_chars(numInt($trans_menus['item_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                }

                $print_str .= append_chars("Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                              .append_chars(numInt($total+$trans_menus['mods_total']+$trans_menus['item_total']+$trans_menus['submods_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= "\r\n";
            #SUBCATEGORIES
                $subcats = $trans_menus['sub_cats'];
                // $print_str .= append_chars('Menu Subcategories:',"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                $print_str .= append_chars('Menu Types:',"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                             .append_chars('',"left",PAPER_RD_COL_3," ")."\r\n";
                $qty = 0;
                $total = 0;
                foreach ($subcats as $id => $val) {
                    $print_str .= append_chars($val['name'],"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
                               .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $qty += $val['qty'];
                    $total += $val['amount'];
                 }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars("Total","right",PAPER_RD_COL_1," ").align_center($qty,PAPER_RD_COL_2," ")
                              .append_chars(numInt($total),"left",PAPER_RD_COL_3_3," ")."\r\n";
                // $print_str .= append_chars("Modifiers Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                //               .append_chars(numInt($trans_menus['mods_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                // $print_str .= append_chars("SubModifier Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                //               .append_chars(numInt($trans_menus['submods_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                // $print_str .= append_chars("Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                //               .append_chars(numInt($total+$trans_menus['mods_total']+$trans_menus['submods_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= "\r\n";
            #FREE MENUS
                $free = $trans_menus['free_menus'];
                $print_str .= append_chars('Free Menus:',"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                             .append_chars('',"left",PAPER_RD_COL_3," ")."\r\n";
                $fm = array();
                foreach ($free as $ms) {
                    if(!isset($fm[$ms->menu_id])){
                        $mn = array();
                        $mn['name'] = $ms->menu_name;
                        $mn['cat_id'] = $ms->cat_id;
                        $mn['qty'] = $ms->qty;
                        $mn['amount'] = $ms->sell_price * $ms->qty;
                        $mn['sell_price'] = $ms->sell_price;
                        $mn['code'] = $ms->menu_code;
                        // $mn['free_user_id'] = $ms->free_user_id;
                        $fm[$ms->menu_id] = $mn;
                    }
                    else{
                        $mn = $fm[$ms->menu_id];
                        $mn['qty'] += $ms->qty;
                        $mn['amount'] += $ms->sell_price * $ms->qty;
                        $fm[$ms->menu_id] = $mn;
                    }
                }
                $qty = 0;
                $total = 0;
                foreach ($fm as $menu_id => $val) {
                    $print_str .= append_chars($val['name'],"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                               .append_chars(($val['qty']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $qty += $val['qty'];
                    $total += $val['amount'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars("Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                              .append_chars(($qty),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= "\r\n";
                $print_str .= "\r\n";    
            #FOOTER
                $print_str .= append_chars(substrwords('Invoice Start: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                             .append_chars(iSetObj($trans['first_ref'],'trans_ref'),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords('Invoice End: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                             .append_chars(iSetObj($trans['last_ref'],'trans_ref'),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords('Invoice Ctr: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                             .append_chars($trans['ref_count'],"left",PAPER_RD_COL_3_3," ")."\r\n";
                if($title_name == "ZREAD"){
                    $gt = $this->old_grand_net_total($post['from']);
                    $print_str .= "\r\n";
                    $print_str .= append_chars(substrwords('OLD GT: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                 .append_chars(numInt( $gt['old_grand_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $print_str .= append_chars(substrwords('NEW GT: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                 .append_chars( numInt($gt['old_grand_total']+$net_no_adds)  ,"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $print_str .= append_chars(substrwords('Z READ CTR: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                 .append_chars( $gt['ctr'] ,"left",PAPER_RD_COL_3_3," ")."\r\n";
                }
                $print_str .= PAPER_LINE."\r\n";
            #MALLS
                if(MALL_ENABLED){
                    ####################################
                    # AYALA
                        if(MALL == 'ayala'){
                            
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


                            $print_str .= align_center("FOR AYALA",PAPER_WIDTH," ")."\r\n";
                            $print_str .= align_center($branch['name'],PAPER_WIDTH," ")."\r\n";
                            $print_str .= align_center($branch['address'],PAPER_WIDTH," ")."\r\n\r\n";
                            $print_str .= align_center("CONSOLIDATED REPORT Z-READ",PAPER_WIDTH," ")."\r\n\r\n";


                            $total_daily_sales = $total_vatA = $total_rawgrossA = $total_discount = $total_refund = $total_void = $total_charge = $total_non_tax = $total_trans_count = $total_guest = 0;


                            $paytype = array();
                            foreach ($payments_types as $code => $val) {
                                if($code != 'credit'){
                                    if(!isset($paytype[$code])){
                                        $paytype[$code] = array('amount'=>$val['amount']);
                                    }else{
                                        $row = $paytype[$code];
                                        $row['amount'] += $val['amount'];
                                        $paytype[$code] = $row;
                                    }
                                }
                                // $print_str .= append_chars(substrwords(ucwords(strtoupper($code)),12,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
                                //               .append_chars(num($val['amount'],2),"left",PAPER_RD_COL_3_3," ")."\r\n";
                                // $pay_qty += $val['qty'];
                            }
                            $paycards = array();
                            if($payments['cards']){
                                $cards = $payments['cards'];
                                foreach($cards as $key => $val){
                                    if(!isset($paycards[$key])){
                                        $paycards[$key] = array('amount'=>$val['amount']);
                                    }else{
                                        $row = $paycards[$key];
                                        $row['amount'] += $val['amount'];
                                        $paycards[$key] = $row;
                                    }
                                }
                            }


                            // for server
                            $rawgrossA = numInt($gross + $charges + $void + $local_tax);
                            $vatA = numInt(($rawgrossA  - $discounts - $void  -  $charges - $nontaxable - $local_tax - numInt($less_vat)) * (1/9.333333));
                            $dlySaleA = numInt($rawgrossA - $discounts - $void - $charges - $vatA - $less_vat + $local_tax);
                            // $t_discounts = $discounts+$less_vat;
                            $rawgrossA =  $rawgrossA - $less_vat;
                            $t_discounts = $discounts;



                            $trans_count = 0;
                            $begor = 0;
                            $endor = 0;
                            $first_inv = array();
                            $last_inv = array();
                            $first_ref = 0;
                            $last_ref = 0;
                            $first_val = 0;
                            $last_val = 0;
                            $invs = array();
                            foreach ($trans['all_orders'] as $ord) {
                                if($ord->type_id == SALES_TRANS && $ord->trans_ref != ""){
                                    $ref = $ord->trans_ref;
                                    if (preg_match('/^(\D*?)(\d+)(.*)/', $ref, $result) == 1){
                                        // if($ord->inactive != 1){
                                            list($all, $prefix, $number, $postfix) = $result;
                                            $ref_val = intval($number);
                                            $invs[$ref_val] = array("ref"=>$ord->trans_ref,"val"=>$ref_val);
                                        // }
                                    }
                                }
                            }
                            ksort($invs);
                            // echo "<pre>",print_r($invs),"</pre>";die();
                            $first_inv = reset($invs);
                            $last_inv = end($invs);
                            if(count($first_inv) > 0){
                                $first_ref = $first_inv['ref'];
                                $first_val = $first_inv['val'];
                            }
                            if(count($last_inv) > 0){
                                $last_ref = $last_inv['ref'];
                                $last_val = $last_inv['val'];
                            }
                            if(count($invs) > 0){
                                $trans_count = ($last_val - $first_val) + 1; 
                            }

                            // echo $trans_count; die();
                            //add yun mga value ng server sa totals
                            $total_daily_sales += $dlySaleA;
                            $total_vatA += $vatA;
                            $total_rawgrossA += $rawgrossA;
                            $total_discount += $t_discounts;
                            $total_void += $void;
                            $total_charge += $charges;
                            $total_non_tax += $nontaxable;
                            // $total_trans_count += $tc_qty;
                            $total_trans_count += $trans_count;
                            $total_guest += $guestCount;
                             // echo $total_trans_count;

                            $terminals = $this->setup_model->get_terminals();


                            $print_str .= append_chars(substrwords('Daily Sales',25,""),"right",22," ").align_center('',2," ")
                                         .append_chars(num($total_daily_sales),"left",10," ")."\r\n";
                            $print_str .= append_chars(substrwords('Total Discount',25,""),"right",22," ").align_center('',2," ")
                                         .append_chars(num($total_discount),"left",10," ")."\r\n";
                            $print_str .= append_chars(substrwords('Total Refund',25,""),"right",22," ").align_center('',2," ")
                                         .append_chars(num(0),"left",10," ")."\r\n";
                            $print_str .= append_chars(substrwords('Total Void',25,""),"right",22," ").align_center('',2," ")
                                         .append_chars(num($total_void),"left",10," ")."\r\n";
                            $print_str .= append_chars(substrwords('Total Vat',25,""),"right",22," ").align_center('',2," ")
                                         .append_chars(num($total_vatA),"left",10," ")."\r\n";
                            $print_str .= append_chars(substrwords('Total Service Charge',25,""),"right",22," ").align_center('',2," ").append_chars(num($total_charge),"left",10," ")."\r\n";  


                            // $print_str .= append_chars(substrwords('Vatable Sales',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                            //              .append_chars(num($dlySaleA-$nontaxable),"left",PAPER_RD_COL_3_3," ")."\r\n";             
                            $print_str .= append_chars(substrwords('Total Non Taxable',22,""),"right",22," ").align_center('',2," ")
                                         .append_chars(num($total_non_tax),"left",10," ")."\r\n";
                            $print_str .= append_chars(substrwords('Row Gross',22,""),"right",22," ").align_center('',2," ")
                                         .append_chars(num($total_rawgrossA),"left",10," ")."\r\n";             
                            $print_str .= append_chars(substrwords('Transaction Count',22,""),"right",22," ").align_center('',2," ")
                                         .append_chars($total_trans_count,"left",10," ")."\r\n";
                            $print_str .= append_chars(substrwords('Customer Count',22,""),"right",22," ").align_center('',2," ")
                                         .append_chars($total_guest,"left",10," ")."\r\n";
                            foreach ($paytype as $k => $v) {
                                $print_str .= append_chars(strtoupper($k),"right",22," ").align_center('',2," ")
                                     .append_chars(num($v['amount']),"left",10," ")."\r\n";       
                            }
                            foreach ($paycards as $k => $v) {
                                $print_str .= append_chars(strtoupper($k),"right",22," ").align_center('',2," ")
                                     .append_chars(num($v['amount']),"left",10," ")."\r\n";       
                            }
                            $terminals = $this->setup_model->get_terminals();
                            // echo "<pre>",print_r($terminals),"</pre>";die();
                            foreach ($terminals as $k => $val) {
                                $print_str .= append_chars('BIR PERMIT '.$val->terminal_id,"right",15," ").align_center('',2," ")
                                     .append_chars($val->permit,"left",17," ")."\r\n";
                                $print_str .= append_chars('SERIAL NO. '.$val->terminal_id,"right",15," ").align_center('',2," ")
                                     .append_chars($val->serial,"left",17," ")."\r\n";
                            }           
                            // $print_str .= append_chars(substrwords('Less SC Disc',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                            //              .append_chars(num($discounts),"left",PAPER_RD_COL_3_3," ")."\r\n";             
                            // $print_str .= append_chars(substrwords('Vat Exempt',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                            //              .append_chars(num($less_vat),"left",PAPER_RD_COL_3_3," ")."\r\n";             
                            // $print_str .= append_chars(substrwords('Zero Rated',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                            //              .append_chars(num($zero_rated),"left",PAPER_RD_COL_3_3," ")."\r\n";             
                            // $print_str .= PAPER_LINE_SINGLE."\r\n";
                            // $print_str .= append_chars(substrwords('Net Sales',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                            //              .append_chars(num($dlySaleA+$vatA),"left",PAPER_RD_COL_3_3," ")."\r\n";             
                            // $print_str .= append_chars(substrwords('Total Qty Sold',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                            //              .append_chars(num($cat_total_qty),"left",PAPER_RD_COL_3_3," ")."\r\n";             
                            $print_str .= PAPER_LINE."\r\n";
                            $print_str .= align_center(sql2Date($post['from']),PAPER_WIDTH," ")."\r\n";
                            $print_str .= align_center("END OF REPORT",PAPER_WIDTH," ")."\r\n";


                        }
                    ####################################
                }    

            if($title_name == "ZREAD"){
                if($asJson == false){
                    $this->manager_model->add_event_logs($user['id'],"ZREAD","View");    
                }else{
                    $this->manager_model->add_event_logs($user['id'],"ZREAD","Print");                    
                } 
            }elseif($title_name == "XREAD"){
                if($asJson == false){
                    $this->manager_model->add_event_logs($user['id'],"XREAD","View");    
                }else{
                    $this->manager_model->add_event_logs($user['id'],"XREAD","Print");                    
                } 
            }else{
                if($asJson == false){
                    $this->manager_model->add_event_logs($user['id'],"System Sales","View");    
                }else{
                    $this->manager_model->add_event_logs($user['id'],"System Sales","Print");                    
                }
            }  
            if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else if(PRINT_VERSION && PRINT_VERSION == 'V3' && $asJson){
                echo $this->html_print($print_str);
            }else{
                $this->do_print($print_str,$asJson);
            }
            // $this->do_print($print_str,$asJson);
        }
        public function system_sales_rep_moment($asJson=false){
            ////hapchan
            ini_set('memory_limit', '-1');
            set_time_limit(3600);
            
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            // var_dump($trans['net']); die();
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
            $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
            $tax_disc = $trans_discounts['tax_disc_total'];
            $no_tax_disc = $trans_discounts['no_tax_disc_total'];
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
            $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
            $payments = $this->payment_sales($sales['settled']['ids'],$curr);

            $gross = $trans_menus['gross'];
            
            $net = $trans['net'];
            $void = $trans['void'];
            $cancelled = $trans['cancel_amount'];
            $charges = $trans_charges['total'];
            $discounts = $trans_discounts['total'];
            $local_tax = $trans_local_tax['total'];
            // echo $gross.' - '.$charges.' - '.$discounts.' - '.$net; die();
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            // $less_vat = $trans_discounts['vat_exempt_total'];

            // echo $gross.'+'.$charges.'+'.$local_tax.' - '.$discounts.' - '.$net;
            // die();

            if($less_vat < 0)
                $less_vat = 0;
            // var_dump($less_vat);

            //para mag tugmam yun payments and netsale
            // $net_sales2 = $gross + $charges - $discounts - $less_vat;
            // $diffs = $net_sales2 - $payments['total'];
            // if($diffs < 1){
            //     $less_vat = $less_vat + $diffs;
            // }
            

            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;

            $title_name = "SYSTEM SALES REPORT";
            if($post['title'] != "")
                $title_name = $post['title'];

            $print_str .= align_center($title_name,PAPER_WIDTH," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],PAPER_WIDTH," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),PAPER_WIDTH," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],PAPER_WIDTH," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";

            $loc_txt = numInt(($local_tax));
            $net_no_adds = $net-($charges+$local_tax);
            // $nontaxable = $no_tax;
            //binago 9/25/2018 for zreading adjustment of vat exempt equal to the receipt vat exempt
            $nontaxable = $no_tax - $no_tax_disc;
            // echo $gross.' - '.$less_vat.' - '.$nontaxable.' - '.$zero_rated; die();
            // $taxable = ($gross - $less_vat - $nontaxable - $zero_rated) / 1.12;
            // 1.12; binago din para sa adjustment of vat exempt equal to the receipt vat exempt
            // $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
            $taxable =   ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12; //change computation conflict for zero rated 10 17 2018
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
            $add_gt = $taxable+$nontaxable+$zero_rated;
            $nsss = $taxable +  $nontaxable +  $zero_rated;


            #CONTROL

                $print_str .= append_chars(substrwords('CONTROL NO. :',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(iSetObj($trans['first_ref'],'trans_ref'),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";

                $print_str .= append_chars(substrwords('TRANS #',18,""),"right",8," ").align_center('(START)',15," ")
                                 .append_chars(iSetObj($trans['first_ref'],'trans_ref'),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords('',18,""),"right",8," ").align_center('(END)',15," ")
                                 .append_chars(iSetObj($trans['last_ref'],'trans_ref'),"left",PAPER_RD_COL_3_3," ")."\r\n\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n";

             #PREVIOUS
                // echo $post['from']; die();
                //get olds payments
                $select1 = 'sum(trans_sales_payments.to_pay) as tpay, sum(trans_sales_payments.amount) as tamount, payment_type';
                $select2 = 'sum(trans_sales_payments.amount) as tamount, sum(trans_sales_payments.to_pay) as tpay, payment_type';
                $pjoin['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_payments.sales_id');

                $this->site_model->db= $this->load->database('main', TRUE);
                $pargs["amount >= to_pay"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs["trans_sales.type_id"] = 10;
                $pargs["trans_sales.inactive"] = '0';
                $pargs["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs['trans_sales.datetime <= '] = $post['from'];

                $pargs2["amount < to_pay"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs2["trans_sales.type_id"] = 10;
                $pargs2["trans_sales.inactive"] = '0';
                $pargs2["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs2['trans_sales.datetime <= '] = $post['from'];
                $pgroup = 'trans_sales_payments.payment_type';
                $old_payment_exact = $this->site_model->get_tbl('trans_sales_payments',$pargs,array(),$pjoin,true,$select1,$pgroup);
                $old_payment_not_ex = $this->site_model->get_tbl('trans_sales_payments',$pargs2,array(),$pjoin,true,$select2,$pgroup);

                //get olds charges
                $selectc = 'sum(amount) as tcharges';
                $cjoin['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_charges.sales_id');
                $cargs["trans_sales.type_id"] = 10;
                $cargs["trans_sales.inactive"] = '0';
                $cargs["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $cargs['trans_sales.datetime <= '] = $post['from'];
                $get_old_charges = $this->site_model->get_tbl('trans_sales_charges',$cargs,array(),$cjoin,true,$selectc);

                $old_charges = 0;
                $old_net_sales = 0;
                $old_total_payment = 0;
                foreach ($get_old_charges as $ch => $val) {
                    $old_charges += $val->tcharges;
                }


                //get olds discounts
                $selectd = 'sum(amount) as tdisc, disc_code';
                $djoin['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_discounts.sales_id');
                $dargs["trans_sales.type_id"] = 10;
                $dargs["trans_sales.inactive"] = '0';
                $dargs["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $dargs['trans_sales.datetime <= '] = $post['from'];
                $dgroup = 'trans_sales_discounts.disc_id';
                $get_old_disc = $this->site_model->get_tbl('trans_sales_discounts',$dargs,array(),$djoin,true,$selectd,$dgroup);

                $old_sc_disc = 0;
                $old_line_disc = 0;
                foreach ($get_old_disc as $dc => $val) {
                    $amount = $val->tdisc;
                    if($val->disc_code == 'SNDISC' || $val->disc_code == 'PWDISC'){
                        $old_sc_disc += $amount;
                    }else{
                        $old_line_disc += $amount;
                    }
                    
                }

                // echo $this->site_model->db->last_query(); die();

                $this->site_model->db= $this->load->database('default', TRUE);


                $print_str .= append_chars(substrwords('PREVIOUS GRAND TOTALS:',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";


                #PAYMENTS
                $payments_array = array();
                $ptype = array('cash','credit','gc','debit','foodpanda','alipay','wechat');
                $old_forfeited_gc = 0;

                foreach ($ptype as $key => $value) {
                    $amount_p = 0;
                    foreach ($old_payment_exact as $kkk => $val) {
                        if($value == $val->payment_type){
                            $amount_p += $val->tpay;
                            if($value == 'gc'){
                                $old_forfeited_gc = $val->tamount - $val->tpay;
                                $amount_p += $old_forfeited_gc;
                            }
                        }

                    }
                    foreach ($old_payment_not_ex as $kkk => $val) {
                        if($value == $val->payment_type){
                            $amount_p += $val->tamount;
                        }
                    }
                    $old_total_payment += $amount_p;
                    $print_str .= append_chars(substrwords(strtoupper($value),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($amount_p),"left",PAPER_RD_COL_3_3," ")."\r\n";


                    $payments_array[$value] = array('amount'=>$amount_p);
                }

                $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= append_chars(substrwords(strtoupper('FORFEITED GC'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars('('.numInt($old_forfeited_gc).")","left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('SERVICE CHARGE'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars('('.numInt($old_charges).')',"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  .append_chars('-------------',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $old_net_sales = $old_total_payment - $old_charges; 
                $print_str .= append_chars(substrwords(strtoupper('OLD NET SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($old_net_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n";

                // $types = $trans_discounts['types'];
                // $sc_disc = 0;
                // $line_disc = 0;
                // foreach ($types as $code => $val) {
                //     $amount = $val['amount'];
                //     if($code == 'SNDISC' || $code == 'PWDISC'){
                //         $sc_disc += $amount;
                //     }else{
                //         $line_disc += $amount;
                //     }
                    
                // }

                $print_str .= append_chars(substrwords(strtoupper('ADD: LINE DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($old_line_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('SC DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($old_sc_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $old_gross_sales = $old_net_sales + $old_sc_disc + $old_line_disc;
                $print_str .= append_chars(substrwords(strtoupper('OLD GROSS SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($old_gross_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n\r\n";


                $print_str .= append_chars(substrwords('OLD SERVICE CHARGE',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($old_charges),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n";


            #TODAY

                $print_str .= append_chars(substrwords('TODAY REGISTER TOTAL SALES:',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";


                #PAYMENTS
                $payments_types = $payments['types'];
                $payments_total = $payments['total'];
                $pay_qty = 0;
                $t_payment = 0;
                $forfeited_gc = 0;
                $ptype = array('cash','credit','gc','debit','foodpanda','alipay','wechat');

                foreach ($ptype as $key => $value) {
                    $amount_p = 0;
                    foreach ($payments_types as $code => $val) {
                        if($value == $code){
                            $amount_p += $val['amount'];
                        }
                    }
                    if($value == 'gc'){
                        if($payments['gc_excess']){
                            $amount_p += $payments['gc_excess'];
                            $forfeited_gc += $payments['gc_excess'];
                        }
                    }
                    $t_payment += $amount_p;
                    $print_str .= append_chars(substrwords(strtoupper($value),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($amount_p),"left",PAPER_RD_COL_3_3," ")."\r\n";


                    if(isset($payments_array[$value])){
                        $row = $payments_array[$value];
                        $row['amount'] += $amount_p;
                        $payments_array[$value] = $row;
                    }else{
                        $payments_array[$value] = array('amount'=>$amount_p);
                    }
                }

                $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= append_chars(substrwords(strtoupper('FORFEITED GC'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars('('.numInt($forfeited_gc).")","left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('SERVICE CHARGE'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars('('.numInt($charges).')',"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  .append_chars('-------------',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $n_sales = $t_payment - $charges - $forfeited_gc; 
                $print_str .= append_chars(substrwords(strtoupper('NET SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($n_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n";

                $types = $trans_discounts['types'];
                $sc_disc = 0;
                $line_disc = 0;
                foreach ($types as $code => $val) {
                    $amount = $val['amount'];
                    if($code == 'SNDISC' || $code == 'PWDISC'){
                        $sc_disc += $amount;
                    }else{
                        $line_disc += $amount;
                    }
                    
                }

                $print_str .= append_chars(substrwords(strtoupper('ADD: LINE DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($line_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('SC DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($sc_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $g_sales = $n_sales + $sc_disc + $line_disc;
                $print_str .= append_chars(substrwords(strtoupper('GROSS SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($g_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n\r\n";


                $print_str .= append_chars(substrwords('TODAY SERVICE CHARGE',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($charges),"left",PAPER_TOTAL_COL_2," ")."\r\n";


                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";


            #NEW

                $print_str .= append_chars(substrwords('NEW GRAND TOTALS:',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";


                #PAYMENTS
                $payments_types = $payments['types'];
                $payments_total = $payments['total'];
                $pay_qty = 0;

                $ptype = array('cash','credit','gc','debit','foodpanda','alipay','wechat');

                foreach ($ptype as $key => $value) {
                    $amount_p = 0;
                    foreach ($payments_array as $code => $val) {
                        if($value == $code){
                            $amount_p += $val['amount'];
                        }
                    }
                    $print_str .= append_chars(substrwords(strtoupper($value),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($amount_p),"left",PAPER_RD_COL_3_3," ")."\r\n";
                }

                $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= append_chars(substrwords(strtoupper('FORFEITED GC'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars('('.numInt($forfeited_gc +  $old_forfeited_gc).")","left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('SERVICE CHARGE'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars('('.numInt($charges + $old_charges).')',"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  .append_chars('-------------',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $new_net_sales = $n_sales + $old_net_sales; 
                $print_str .= append_chars(substrwords(strtoupper('NET SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($new_net_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n";

                // $types = $trans_discounts['types'];
                // $sc_disc = 0;
                // $line_disc = 0;
                // foreach ($types as $code => $val) {
                //     $amount = $val['amount'];
                //     if($code == 'SNDISC' || $code == 'PWDISC'){
                //         $sc_disc += $amount;
                //     }else{
                //         $line_disc += $amount;
                //     }
                    
                // }
                $new_line_disc = $line_disc + $old_line_disc;
                $new_sc_disc = $sc_disc + $old_sc_disc;

                $print_str .= append_chars(substrwords(strtoupper('ADD: LINE DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($new_line_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('SC DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($new_sc_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $new_gross_sales = $new_net_sales + $new_sc_disc + $new_line_disc;
                $print_str .= append_chars(substrwords(strtoupper('GROSS SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($new_gross_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n\r\n";


                $print_str .= append_chars(substrwords('TODAY SERVICE CHARGE',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($charges + $old_charges),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n\r\n";




                // foreach ($payments_types as $code => $val) {
                //     $print_str .= append_chars(substrwords(ucwords(strtoupper($code)),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                //                   .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                //     // $pay_qty += $val['qty'];
                // }



            // #GENERAL
            //     $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";
            //     $print_str .= append_chars(substrwords('TOTAL SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
            //                              .append_chars(num($gross + $charges,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";

            //     $types = $trans_charges['types'];
            //     $qty = 0;
            //     foreach ($types as $code => $val) {
            //         $amount = $val['amount'];
            //         $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
            //                              .append_chars('-'.num($amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //         $qty += $val['qty'];
            //     }
            //     $types = $trans_discounts['types'];
            //     $qty = 0;
            //     foreach ($types as $code => $val) {
            //         $amount = $val['amount'];
            //         // if(MALL == 'megamall' && $code == PWDDISC){
            //         //     $amount = $val['amount'] / 1.12;
            //         // }
            //         $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
            //                              .append_chars('-'.Num($amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //         $qty += $val['qty'];
            //     }
            //     $print_str .= append_chars(substrwords(ucwords(strtoupper('SC/PWD VAT EXEMPT')),18,""),"right",PAPER_TOTAL_COL_1," ")
            //                              .append_chars('-'.num($less_vat,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     $print_str .= append_chars('',"right",12," ").align_center('',PAPER_TOTAL_COL_2," ")
            //                       .append_chars('----------',"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     $net_sales = $gross - $discounts - $less_vat;
            //     $print_str .= append_chars(substrwords(ucwords(strtoupper('GROSS SALES')),18,""),"right",PAPER_TOTAL_COL_1," ")
            //                              .append_chars(num($net_sales,2),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";
            // #PAYMENTS
            //     $payments_types = $payments['types'];
            //     $payments_total = $payments['total'];
            //     $pay_qty = 0;
            // #SUMMARY
            //     $final_gross = $gross;
            //     $vat_ = $taxable * .12;
            //     $print_str .= append_chars(substrwords('VAT SALES',23,""),"right",PAPER_TOTAL_COL_1," ")
            //                              .append_chars(numInt($taxable),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     $print_str .= append_chars(substrwords('VAT',23,""),"right",PAPER_TOTAL_COL_1," ")
            //                              .append_chars(numInt($vat_),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     $print_str .= append_chars(substrwords('VAT EXEMPT SALES',23,""),"right",PAPER_TOTAL_COL_1," ")
            //                              .append_chars(numInt($nontaxable),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //                              // .append_chars(numInt($nontaxable-$zero_rated),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     $print_str .= append_chars(substrwords('ZERO RATED',23,""),"right",PAPER_TOTAL_COL_1," ")
            //                              .append_chars(numInt($zero_rated),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";
            //     $print_str .= append_chars(substrwords('Payment Breakdown:',18,""),"right",PAPER_RD_COL_1," ").align_center(null,PAPER_RD_COL_2," ")
            //                   .append_chars(null,"left",PAPER_RD_COL_3," ")."\r\n";
            //     foreach ($payments_types as $code => $val) {
            //         $print_str .= append_chars(substrwords(ucwords(strtoupper($code)),18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
            //                       .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $pay_qty += $val['qty'];
            //     }
            //     $print_str .= append_chars('',"right",18," ").align_center('',PAPER_RD_COL_2," ")
            //                       .append_chars('----------',"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= append_chars(substrwords('TOTAL PAYMENTS',18,""),"right",PAPER_RD_COL_1," ").align_center($pay_qty,PAPER_RD_COL_2," ")
            //                   .append_chars(numInt($payments_total),"left",PAPER_RD_COL_3_3," ")."\r\n\r\n";
            //     $print_str .= PAPER_LINE_SINGLE."\r\n";
            //     $gross_less_disc = $final_gross - $discounts - $less_vat;
            //     // $print_str .= append_chars(substrwords('NET SALES',23,""),"right",PAPER_TOTAL_COL_1," ")
            //     //                          // .append_chars(numInt(($taxable + $nontaxable + $zero_rated + $vat_)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     //                          .append_chars(numInt(($taxable + $nontaxable + $vat_)),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     // $print_str .= PAPER_LINE."\r\n";
            //     $print_str .= append_chars(substrwords('VOID SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
            //                  .append_chars(num(($void),2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     $print_str .= append_chars(substrwords('CANCELLED TRANS',18,""),"right",PAPER_TOTAL_COL_1," ")
            //                  .append_chars(num(($cancelled),2),"left",PAPER_TOTAL_COL_2," ")."\r\n";

            //     $cancelled_order = $this->cancelled_orders();
            //     $co = $cancelled_order['cancelled_order'];
            //     $print_str .= append_chars(substrwords('CANCELLED ORDERS',18,""),"right",PAPER_TOTAL_COL_1," ")
            //                  .append_chars(num(($co),2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     $print_str .= append_chars(substrwords('Local Tax',18,""),"right",PAPER_TOTAL_COL_1," ")
            //                  .append_chars($loc_txt,"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     $print_str .= "\r\n";
            // #TRANS COUNT
            //     $types = $trans['types'];
            //     $types_total = array();
            //     $guestCount = 0;
            //     foreach ($types as $type => $tp) {
            //         foreach ($tp as $id => $opt){
            //             if(isset($types_total[$type])){
            //                 $types_total[$type] += round($opt->total_amount,2);

            //             }
            //             else{
            //                 $types_total[$type] = round($opt->total_amount,2);
            //             }
            //             if($opt->guest == 0)
            //                 $guestCount += 1;
            //             else
            //                 $guestCount += $opt->guest;
            //         }
            //     }
            //     $print_str .= append_chars(substrwords('Trans Count:',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                  .append_chars('',"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $tc_total  = 0;
            //     $tc_qty = 0;
            //     foreach ($types_total as $typ => $tamnt) {
            //         $print_str .= append_chars(substrwords($typ,18,""),"right",PAPER_RD_COL_1," ").align_center(count($types[$typ]),PAPER_RD_COL_2," ")
            //                      .append_chars(numInt($tamnt),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $tc_total += $tamnt;
            //         $tc_qty += count($types[$typ]);
            //     }
            //     $print_str .= "-----------------"."\r\n";
            //     $print_str .= append_chars(substrwords('TC Total',18,""),"right",PAPER_TOTAL_COL_1," ")
            //                  .append_chars(numInt($tc_total),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     $print_str .= append_chars(substrwords('GUEST Total',18,""),"right",PAPER_TOTAL_COL_1," ")
            //                  .append_chars(numInt($guestCount),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     // if($tc_total == 0 || $tc_qty == 0)
            //     //     $avg = 0;
            //     // else
            //     //     $avg = $tc_total/$tc_qty;
            //     if($net_sales){
            //         $avg = $net_sales/$guestCount;
            //     }else{
            //         $avg = 0;
            //     }


            //     $print_str .= append_chars(substrwords('AVG Check',18,""),"right",PAPER_TOTAL_COL_1," ")
            //                  .append_chars(numInt($avg),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     $print_str .= "\r\n";
            // #CHARGES
            //     $types = $trans_charges['types'];
            //     $qty = 0;
            //     $print_str .= append_chars(substrwords('Charges:',18,""),"right",18," ").align_center(null,5," ")
            //                   .append_chars(null,"left",13," ")."\r\n";
            //     foreach ($types as $code => $val) {
            //         $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
            //                       .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $qty += $val['qty'];
            //     }
            //     $print_str .= "-----------------"."\r\n";
            //     $print_str .= append_chars(substrwords('Total Charges',18,""),"right",PAPER_RD_COL_1," ").align_center($qty,PAPER_RD_COL_2," ")
            //                   .append_chars(numInt($charges),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= "\r\n";
            // #Discounts
            //     $types = $trans_discounts['types'];
            //     $qty = 0;
            //     $print_str .= append_chars(substrwords('Discounts:',18,""),"right",PAPER_RD_COL_1," ").align_center(null,PAPER_RD_COL_2," ")
            //                   .append_chars(null,"left",PAPER_RD_COL_3," ")."\r\n";
            //     foreach ($types as $code => $val) {
            //         $amount = $val['amount'];
            //         // if(MALL == 'megamall' && $code == PWDDISC){
            //         //     $amount = $val['amount'] / 1.12;
            //         // }
            //         $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
            //                       .append_chars(numInt($amount),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $qty += $val['qty'];
            //     }
            //     $print_str .= "-----------------"."\r\n";
            //     $print_str .= append_chars(substrwords('Total Discounts',18,""),"right",PAPER_RD_COL_1," ").align_center($qty,PAPER_RD_COL_2," ")
            //                   .append_chars(numInt($discounts),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= append_chars(substrwords('VAT EXEMPT',18,""),"right",PAPER_TOTAL_COL_1," ")
            //                              .append_chars(numInt($less_vat),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            //     $print_str .= "\r\n";
            // #PAYMENTS
            //     $payments_types = $payments['types'];
            //     $payments_total = $payments['total'];
            //     $pay_qty = 0;
            //     $print_str .= append_chars(substrwords('Payment Breakdown:',18,""),"right",PAPER_RD_COL_1," ").align_center(null,PAPER_RD_COL_2," ")
            //                   .append_chars(null,"left",PAPER_RD_COL_3," ")."\r\n";
            //     foreach ($payments_types as $code => $val) {
            //         $print_str .= append_chars(substrwords(ucwords(strtolower($code)),18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
            //                       .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $pay_qty += $val['qty'];
            //     }
            //     $print_str .= "-----------------"."\r\n";
            //     $print_str .= append_chars(substrwords('Total Payments',18,""),"right",PAPER_RD_COL_1," ").align_center($pay_qty,PAPER_RD_COL_2," ")
            //                   .append_chars(numInt($payments_total),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= "\r\n";

            //     //card breakdown
            //     if($payments['cards']){
            //         $cards = $payments['cards'];
            //         $card_total = 0;
            //         $count_total = 0;
            //         $print_str .= append_chars(substrwords('Card Breakdown:',18,""),"right",PAPER_RD_COL_1," ").align_center(null,PAPER_RD_COL_2," ")
            //                   .append_chars(null,"left",PAPER_RD_COL_3," ")."\r\n";
            //         foreach($cards as $key => $val){
            //             $print_str .= append_chars(substrwords($key,18,""),"right",PAPER_RD_COL_1," ").align_center($val['count'],PAPER_RD_COL_2," ")
            //                       .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //             $card_total += $val['amount'];
            //             $count_total += $val['count'];
            //         }
            //         $print_str .= "-----------------"."\r\n";
            //         $print_str .= append_chars(substrwords('Total',18,""),"right",PAPER_RD_COL_1," ").align_center($count_total,PAPER_RD_COL_2," ")
            //                   .append_chars(numInt($card_total),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    
            //         $print_str .= "\r\n";
            //     }

            //     //get all gc with excess
            //     if($payments['gc_excess']){
            //         $print_str .= append_chars(substrwords('GC EXCESS',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                       .append_chars(numInt($payments['gc_excess']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $print_str .= "\r\n";
            //     }

            //     //show all sign chit
            //     // $trans['sales']
            //     if($trans['total_chit']){
            //         $print_str .= append_chars(substrwords('TOTAL CHIT',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                       .append_chars(numInt($trans['total_chit']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $print_str .= "\r\n";
            //     }
            // #CATEGORIES
            //     $cats = $trans_menus['cats'];
            //     $print_str .= append_chars('Menu Categories:',"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                  .append_chars('',"left",PAPER_RD_COL_3," ")."\r\n";
            //     $qty = 0;
            //     $total = 0;
            //     foreach ($cats as $id => $val) {
            //         if($val['qty'] > 0){
            //             $print_str .= append_chars(substrwords($val['name'],18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
            //                        .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //             $qty += $val['qty'];
            //             $total += $val['amount'];
            //         }
            //      }
            //     $print_str .= "-----------------"."\r\n";
            //     $cat_total_qty = $qty;
            //     $print_str .= append_chars("SubTotal","right",PAPER_RD_COL_1," ").align_center($qty,PAPER_RD_COL_2," ")
            //                   .append_chars(numInt($total),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= append_chars("Modifiers Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                   .append_chars(numInt($trans_menus['mods_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     if($trans_menus['item_total'] > 0){
            //      $print_str .= append_chars("Retail Items Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                    .append_chars(numInt($trans_menus['item_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     }

            //     $print_str .= append_chars("Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                   .append_chars(numInt($total+$trans_menus['mods_total']+$trans_menus['item_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= "\r\n";
            // #SUBCATEGORIES
            //     $subcats = $trans_menus['sub_cats'];
            //     // $print_str .= append_chars('Menu Subcategories:',"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //     $print_str .= append_chars('Menu Types:',"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                  .append_chars('',"left",PAPER_RD_COL_3," ")."\r\n";
            //     $qty = 0;
            //     $total = 0;
            //     foreach ($subcats as $id => $val) {
            //         $print_str .= append_chars($val['name'],"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
            //                    .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $qty += $val['qty'];
            //         $total += $val['amount'];
            //      }
            //     $print_str .= "-----------------"."\r\n";
            //     $print_str .= append_chars("SubTotal","right",PAPER_RD_COL_1," ").align_center($qty,PAPER_RD_COL_2," ")
            //                   .append_chars(numInt($total),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= append_chars("Modifiers Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                   .append_chars(numInt($trans_menus['mods_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= append_chars("Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                   .append_chars(numInt($total+$trans_menus['mods_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= "\r\n";
            // #FREE MENUS
            //     $free = $trans_menus['free_menus'];
            //     $print_str .= append_chars('Free Menus:',"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                  .append_chars('',"left",PAPER_RD_COL_3," ")."\r\n";
            //     $fm = array();
            //     foreach ($free as $ms) {
            //         if(!isset($fm[$ms->menu_id])){
            //             $mn = array();
            //             $mn['name'] = $ms->menu_name;
            //             $mn['cat_id'] = $ms->cat_id;
            //             $mn['qty'] = $ms->qty;
            //             $mn['amount'] = $ms->sell_price * $ms->qty;
            //             $mn['sell_price'] = $ms->sell_price;
            //             $mn['code'] = $ms->menu_code;
            //             // $mn['free_user_id'] = $ms->free_user_id;
            //             $fm[$ms->menu_id] = $mn;
            //         }
            //         else{
            //             $mn = $fm[$ms->menu_id];
            //             $mn['qty'] += $ms->qty;
            //             $mn['amount'] += $ms->sell_price * $ms->qty;
            //             $fm[$ms->menu_id] = $mn;
            //         }
            //     }
            //     $qty = 0;
            //     $total = 0;
            //     foreach ($fm as $menu_id => $val) {
            //         $print_str .= append_chars($val['name'],"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                    .append_chars(($val['qty']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $qty += $val['qty'];
            //         $total += $val['amount'];
            //     }
            //     $print_str .= "-----------------"."\r\n";
            //     $print_str .= append_chars("Total","right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                   .append_chars(($qty),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= "\r\n";
            //     $print_str .= "\r\n";    
            // #FOOTER
            //     $print_str .= append_chars(substrwords('Invoice Start: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                  .append_chars(iSetObj($trans['first_ref'],'trans_ref'),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= append_chars(substrwords('Invoice End: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                  .append_chars(iSetObj($trans['last_ref'],'trans_ref'),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     $print_str .= append_chars(substrwords('Invoice Ctr: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                  .append_chars($trans['ref_count'],"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     if($title_name == "ZREAD"){
            //         $gt = $this->old_grand_net_total($post['from']);
            //         $print_str .= "\r\n";
            //         $print_str .= append_chars(substrwords('OLD GT: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                      .append_chars(numInt( $gt['old_grand_total']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $print_str .= append_chars(substrwords('NEW GT: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                      .append_chars( numInt($gt['old_grand_total']+$net_no_adds)  ,"left",PAPER_RD_COL_3_3," ")."\r\n";
            //         $print_str .= append_chars(substrwords('Z READ CTR: ',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                      .append_chars( $gt['ctr'] ,"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     }
            //     $print_str .= PAPER_LINE."\r\n";
            // #MALLS
            //     if(MALL_ENABLED){
            //         ####################################
            //         # AYALA
            //             if(MALL == 'ayala'){
            //                 $rawgrossA = numInt($gross + $charges + $void + $local_tax);
            //                 $vatA = numInt(($rawgrossA  - $discounts - $void  -  $charges - $nontaxable - $local_tax - numInt($less_vat)) * (1/9.333333));
            //                 $dlySaleA = numInt($rawgrossA - $discounts - $void - $charges - $vatA - $less_vat + $local_tax);

            //                 $print_str .= align_center("FOR AYALA",PAPER_WIDTH," ")."\r\n";
            //                 $print_str .= PAPER_LINE_SINGLE."\r\n";
            //                 $print_str .= append_chars(substrwords('Description',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                              .append_chars("Qty/Amount","left",PAPER_RD_COL_3_3," ")."\r\n";
            //                 $print_str .= PAPER_LINE_SINGLE."\r\n";
            //                 $print_str .= append_chars(substrwords('Daily Sales',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                              .append_chars(numInt($dlySaleA),"left",PAPER_RD_COL_3_3," ")."\r\n";             
            //                 $print_str .= append_chars(substrwords('Vat',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                              .append_chars(numInt($vatA),"left",PAPER_RD_COL_3_3," ")."\r\n";             
            //                 $print_str .= append_chars(substrwords('Vatable Sales',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                              .append_chars(numInt($dlySaleA-$nontaxable),"left",PAPER_RD_COL_3_3," ")."\r\n";             
            //                 $print_str .= append_chars(substrwords('Non-Vatable SALES',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                              .append_chars(numInt($nontaxable),"left",PAPER_RD_COL_3_3," ")."\r\n";             
            //                 $print_str .= append_chars(substrwords('Less SC Disc',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                              .append_chars(numInt($discounts),"left",PAPER_RD_COL_3_3," ")."\r\n";             
            //                 $print_str .= append_chars(substrwords('Vat Exempt',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                              .append_chars(numInt($less_vat),"left",PAPER_RD_COL_3_3," ")."\r\n";             
            //                 $print_str .= append_chars(substrwords('Zero Rated',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                              .append_chars(numInt($zero_rated),"left",PAPER_RD_COL_3_3," ")."\r\n";             
            //                 $print_str .= PAPER_LINE_SINGLE."\r\n";
            //                 $print_str .= append_chars(substrwords('Net Sales',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                              .append_chars(numInt($dlySaleA+$vatA),"left",PAPER_RD_COL_3_3," ")."\r\n";             
            //                 $print_str .= append_chars(substrwords('Total Qty Sold',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                              .append_chars(numInt($cat_total_qty),"left",PAPER_RD_COL_3_3," ")."\r\n";             
            //                 $print_str .= append_chars(substrwords('Trans Count',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //                              .append_chars(numInt($tc_qty),"left",PAPER_RD_COL_3_3," ")."\r\n";             
            //                 $print_str .= PAPER_LINE."\r\n";
            //             }
            //         ####################################
            //     }    

             if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else{
                $this->do_print($print_str,$asJson);
            }
            // $this->do_print($print_str,$asJson);
        }
        public function hourly_sales_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $net = $trans['net'];

            $title_name = "HOURLY SALES REPORT";
            $print_str .= align_center($title_name,PAPER_WIDTH," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],PAPER_WIDTH," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),38," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],38," ")."\r\n";



            $ranges = array();
            foreach (unserialize(TIMERANGES) as $ctr => $time) {
                $key = date('H',strtotime($time['FTIME']));
                $ranges[$key] = array('start'=>$time['FTIME'],'end'=>$time['TTIME'],'tc'=>0,'guest'=>0,'net'=>0);
            }

            $dates = array();
            if(count($sales['settled']['orders']) > 0){
                foreach ($sales['settled']['orders'] as $sales_id => $val) {
                    $dates[date2Sql($val->datetime)]['ranges'] = $ranges;
                }
                // echo "<pre>",print_r($sales['settled']['orders']),"</pre>";die();
                foreach ($sales['settled']['orders'] as $sales_id => $val) {
                    if(isset($dates[date2Sql($val->datetime)])){
                        $date_arr = $dates[date2Sql($val->datetime)];
                        $range = $date_arr['ranges'];
                        $H = date('H',strtotime($val->datetime));
                        if(isset($range[$H])){
                            $r = $range[$H];
                             $r['tc'] +=  1;

                            if($val->guest == 0 || $val->guest == null || $val->guest == '')
                                $r['guest'] += 1;
                            else
                                $r['guest'] += $val->guest;

                            // $r['guest'] +=  $val->guest;
                            $r['net'] += $val->total_amount;
                            $range[$H] = $r;
                        }
                        $dates[date2Sql($val->datetime)]['ranges'] = $range;
                    }
                }
            }

            $ctr = 0;
            foreach ($dates as $date => $val) {
                $print_str .= align_center(sql2Date($date),38," ")."\r\n";
                $ranges = $val['ranges'];
                $print_str .= append_chars(substrwords("",PAPER_RD_COL_1_4,""),"right",PAPER_RD_COL_1_4," ")
                             .append_chars(substrwords('Guest',5,""),"right",PAPER_RD_COL_3_3," ")
                             .append_chars(substrwords('NET',10,""),"right",PAPER_RD_COL_3_3," ")
                             .append_chars(substrwords('AVG',10,""),"right",PAPER_RD_COL_3_3," ")."\r\n";
                foreach ($ranges as $key => $ran) {
                    if($ran['tc'] == 0 || $ran['net'] == 0)
                        $avg = 0;
                    else
                        $avg = $ran['net']/$ran['tc'];
                    $ctr += $ran['guest'];
                    $print_str .= append_chars(substrwords($ran['start']."-".$ran['end'],PAPER_RD_COL_1_4,""),"right",PAPER_RD_COL_1_4," ")
                                 .append_chars(substrwords($ran['guest'],10,""),"right",PAPER_RD_COL_3_3," ")
                                 .append_chars(substrwords(numInt($ran['net']),10,""),"right",PAPER_RD_COL_3_3," ")
                                 .append_chars(substrwords(numInt($avg),10,""),"right",PAPER_RD_COL_3_3," ")."\r\n";
                }
                $print_str .= "\r\n";
            }
            $print_str .= PAPER_LINE."\r\n";
            if($ctr == 0 || $net == 0)
                $tavg = 0;
            else
                $tavg = $net/$ctr;
            $print_str .= append_chars(substrwords("TOTAL",18,""),"right",PAPER_RD_COL_1_4," ")
                         .append_chars(substrwords($ctr,12,""),"right",PAPER_RD_COL_1_2," ")
                         .append_chars(substrwords(numInt($net),12,""),"right",PAPER_RD_COL_3_3," ")
                         .append_chars(substrwords(numInt($tavg),12,""),"right",PAPER_RD_COL_3_3," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";

            if($asJson == false){
                $this->manager_model->add_event_logs($user['id'],"Hourly Sales","View");    
            }else{
                $this->manager_model->add_event_logs($user['id'],"Hourly Sales","Print");                    
            }

            if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else if(PRINT_VERSION && PRINT_VERSION == 'V3' && $asJson){
                echo $this->html_print($print_str);  
            }else{
                $this->do_print($print_str,$asJson);
            }
            // $this->do_print($print_str,$asJson);
        }
        public function cash_count_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();

            $title_name = "Cash Count";
            $print_str .= align_center($title_name,PAPER_WIDTH," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],PAPER_WIDTH," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],PAPER_WIDTH," ")."\r\n";
            $shit_id = $post['shift_id'];
            $totals = $this->shift_entries($shit_id);
            $cashout_id = $this->shift_cashout($shit_id);
            $print_str = $this->print_cashout_details($print_str,$totals,$cashout_id);

            // echo "<pre style='background-color:#fff'>$print_str</pre>";
            // $this->do_print($print_str,$asJson);
            if($asJson == false){
                $this->manager_model->add_event_logs($user['id'],"Cash Count","View");    
            }else{
                $this->manager_model->add_event_logs($user['id'],"Cash Count","Print");                    
            }
             if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else if(PRINT_VERSION && PRINT_VERSION == 'V3' && $asJson){
                echo $this->html_print($print_str);
            }else{
                $this->do_print($print_str,$asJson);
            }
        }
        public function print_cashout_details($print_str="",$totals,$cashout_id){
            $cashout_header = $this->cashier_model->get_cashout_header($cashout_id); // returns row
            $cashout_details = $this->cashier_model->get_cashout_details($cashout_id); // returns rows array

            $sum_deps = $sum_withs = 0;

            /* Cash Deposits */
            $print_str .= "Cash Deposits\r\n";
            foreach ($totals['total_deps'] as $k => $dep) {
                $print_str .= append_chars("   ".($k+1),'right',PAPER_DET_COL_3," ").append_chars(date('H:i:s',strtotime($dep->trans_date)),"right",PAPER_RD_COL_MID," ")
                    .append_chars(number_format($dep->amount,2),"left",PAPER_RD_COL_1_4," ")."\r\n";
                $sum_deps += $dep->amount;
            }
            if ($sum_deps > 0)
                $print_str .= append_chars("------------","left",PAPER_WIDTH," ")."\r\n";
            // $print_str .= append_chars("Total Cash Deposits","right",21," ")
            //     .append_chars(number_format($sum_deps,2),"left",15," ")."\r\n\r\n";
            $print_str .= append_chars(substrwords('Total Cash Deposits',18,""),"right",PAPER_TOTAL_COL_1," ")
                                     .append_chars(number_format($sum_deps,2),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";

            /* Cash Withdrawals */
            $print_str .= "Cash Withdrawals\r\n";
            foreach ($totals['total_withs'] as $k => $with) {
                // $print_str .= append_chars("   ".($k+1)." ".date('H:i:s',strtotime($with->trans_date)),"right",21," ")
                //     .append_chars(number_format(abs($with->amount),2),"left",15," ")."\r\n";
                $print_str .= append_chars("   ".($k+1),'right',PAPER_DET_COL_3," ").append_chars(date('H:i:s',strtotime($with->trans_date)),"right",PAPER_RD_COL_MID," ")
                    .append_chars(number_format(abs($with->amount),2),"left",PAPER_RD_COL_1_4," ")."\r\n";
                $sum_withs += abs($with->amount);
            }
            if ($sum_withs > 0)
                $print_str .= append_chars("------------","left",PAPER_WIDTH," ")."\r\n";
            // $print_str .= append_chars("Total Cash Withdrawals","right",25," ")
            //     .append_chars(number_format($sum_withs,2),"left",11," ")."\r\n\r\n";
            $print_str .= append_chars(substrwords('Total Cash Withdrawals',18,""),"right",PAPER_TOTAL_COL_1," ")
                                     .append_chars(number_format($sum_withs,2),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";


            /* Drawer */
            $print_str .= append_chars("Expected Drawer amount","right",PAPER_TOTAL_COL_1," ").append_chars(number_format($cashout_header->drawer_amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            $print_str .= append_chars("Actual Drawer amount","right",PAPER_TOTAL_COL_1," ").append_chars(number_format($cashout_header->count_amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
            $print_str .= append_chars("-------------","right",PAPER_WIDTH," ")."\r\n";
            $print_str .= append_chars("Variance","right",PAPER_TOTAL_COL_1," ").append_chars(number_format(abs($cashout_header->drawer_amount - $cashout_header->count_amount),2),"left",PAPER_TOTAL_COL_2," ")."\r\n";


            /* Cashout Details */
            $print_str .= "\r\nCashout Breakdown\r\n";
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
                    // $print_str .= append_chars("[".ucwords($value->type)."] ".$mid,"right",21," ").
                    //     append_chars(number_format($value->total,2),"left",15," ")."\r\n";
                    $print_str .= append_chars("[".ucwords($value->type)."] ".$mid,"right",PAPER_TOTAL_COL_1," ")
                                     .append_chars(number_format($value->total,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                }
            }

            $print_str .= "\r\n".append_chars("","right",PAPER_WIDTH,"-");
            return $print_str;
        }
        public function do_print($print_str=null,$asJson=false){
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
                );
            }
            $userdata = $this->session->userdata('user');
            $print_str = "\r\n\r\n";
            $wrap = wordwrap($branch['name'],35,"|#|");
            $exp = explode("|#|", $wrap);
            foreach ($exp as $v) {
                $print_str .= align_center($v,PAPER_WIDTH," ")."\r\n";
            }
            $wrap = wordwrap($branch['address'],35,"|#|");
            $exp = explode("|#|", $wrap);
            foreach ($exp as $v) {
                $print_str .= align_center($v,PAPER_WIDTH," ")."\r\n";
            }
            $print_str .=
             align_center('TIN: '.$branch['tin'],PAPER_WIDTH," ")."\r\n"
            .align_center('ACCRDN: '.$branch['accrdn'],PAPER_WIDTH," ")."\r\n"
            // .$this->align_center('BIR # '.$branch['bir'],42," ")."\r\n"
            .align_center('MIN: '.$branch['machine_no'],PAPER_WIDTH," ")."\r\n"
            // .align_center('SN: '.$branch['serial'],38," ")."\r\n"
            .align_center('PERMIT: '.$branch['permit_no'],PAPER_WIDTH," ")."\r\n";
            $print_str .= PAPER_LINE."\r\n";
            return $print_str;
        }
    ##############
    #### RETURNS
        public function order_box(){
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            $orders = $trans['all_orders'];
            echo count($orders);
        }    
    ##############
    #### FUNCTIONS
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

                //OLD GET TRANSSALES
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

                ///////END

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
                
                 // echo "<pre>",print_r($results),"</pre>";die();
                if(isset($results[0])){
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

                }

                $args = array();
                $args['trans_sales.shift_id'] = $shift;
            }
            $terminal = TERMINAL_ID;
            $args['trans_sales.terminal_id'] = $terminal;
            return array('args'=>$args,'from'=>$from,'to'=>$to,'date'=>$date,'terminal'=>$terminal,"employee"=>$emp,"title"=>$title,"shift_id"=>$shift);
        }
        public function search_current(){
            $today = sql2Date($this->site_model->get_db_now());
            $use = false;
            if($this->input->post('calendar_range')){
                $daterange = $this->input->post('calendar_range');
                $dates = explode(" to ",$daterange);
                $from = sql2Date($dates[0]);
                $to = sql2Date($dates[1]);
                $s_date = strtotime($from);
                $e_date = strtotime($to);
                $date = strtotime($today);
                if($date >= $s_date && $date <= $e_date)
                    $use = true;
            }
            if($this->input->post('calendar')){
                $date = $this->input->post('calendar');
                $from = sql2Date($date);
                if($from == $today){
                    $use = true;
                }
                if($this->input->post('use_curr')){
                    $use = $this->input->post('use_curr');
                }
            }
            $shift = $this->input->post('shift_id');
            if($shift != ""){
               $use = true;
            }
            return $use;
        }
        public function trans_sales($args=array(),$curr=false){
            $this->load->model('core/trans_model');
            $total_chit = $total_producttest = 0;
            $chit = array();
            $producttest = array();
            $n_results = array();
            // if($curr){
            //     $this->cashier_model->db = $this->load->database('default', TRUE);
            //     $n_results  = $this->cashier_model->get_trans_sales(null,$args);
            // }
            // echo "<pre>",print_r($args),"</pre>";die();
            
            $this->cashier_model->db = $this->load->database('main', TRUE);
            $results = $this->cashier_model->get_trans_sales_rep(null,$args);
            $trans_count = count($results);
            // echo count($results);die();
            // echo "<pre>",print_r($results),"</pre>";die();
            // echo $this->cashier_model->db->last_query(); die();
            $orders = array();
            // if(HIDECHIT){
            //     // if(count($n_results) > 0){
            //     //     foreach ($n_results as $nres) {

            //     //         if($nres->type_id == 10){
            //     //             $this->site_model->db = $this->load->database('default', TRUE);
            //     //             $where = array('sales_id'=>$nres->sales_id);
            //     //             $rest = $this->site_model->get_details($where,'trans_sales_payments');
            //     //             if($rest){
            //     //                 if($rest[0]->payment_type != 'chit'){

            //     //                     if(!isset($orders[$nres->sales_id])){
            //     //                         $orders[$nres->sales_id] = $nres;
            //     //                     }
            //     //                 }else{
            //     //                     $chit[$nres->sales_id] = $nres;
            //     //                 }
            //     //             }else{
            //     //                 if(!isset($orders[$nres->sales_id])){
            //     //                     $orders[$nres->sales_id] = $nres;
            //     //                 }
            //     //             }

            //     //         }else{
            //     //             if(!isset($orders[$nres->sales_id])){
            //     //                 $orders[$nres->sales_id] = $nres;
            //     //             }
            //     //         }
            //     //     }
            //     // }
            //     foreach ($results as $res) {
                    
            //         if($res->type_id == 10){
            //             $this->site_model->db = $this->load->database('main', TRUE);
            //             $where = array('sales_id'=>$res->sales_id);
            //             $rest = $this->site_model->get_details($where,'trans_sales_payments');
            //             if($rest){
            //                 if($rest[0]->payment_type != 'chit'){
            //                     if(!isset($orders[$res->sales_id])){
            //                         $orders[$res->sales_id] = $res;
            //                     }
            //                 }else{
            //                     // echo 'aa-';
            //                     $chit[$res->sales_id] = $res;
            //                 }
            //             }else{
            //                 if(!isset($orders[$res->sales_id])){
            //                     $orders[$res->sales_id] = $res;
            //                 }
            //             }
            //         }else{
            //             if(!isset($orders[$res->sales_id])){
            //                 $orders[$res->sales_id] = $res;
            //             }
            //         }
            //     }
            // }else{
            //     // if(count($n_results) > 0){
            //     //     foreach ($n_results as $nres) {
            //     //         if(!isset($orders[$nres->sales_id])){
            //     //             $orders[$nres->sales_id] = $nres;
            //     //         }
            //     //     }
            //     // }
            //     foreach ($results as $res) {
            //         if(!isset($orders[$res->sales_id])){
            //             $orders[$res->sales_id] = $res;
            //         }
            //     }
            // }
            // if(PRODUCT_TEST){
            //     foreach ($results as $res) {
                    
            //         if($res->type_id == 10){
            //             $this->site_model->db = $this->load->database('main', TRUE);
            //             $where = array('sales_id'=>$res->sales_id);
            //             $rest = $this->site_model->get_details($where,'trans_sales_payments');
            //             if($rest){
            //                 if($rest[0]->payment_type != 'producttest'){
            //                     if(!isset($orders[$res->sales_id])){
            //                         $orders[$res->sales_id] = $res;
            //                     }
            //                 }else{
            //                     $producttest[$res->sales_id] = $res;
            //                 }
            //             }else{
            //                 if(!isset($orders[$res->sales_id])){
            //                     $orders[$res->sales_id] = $res;
            //                 }
            //             }
            //         }else{
            //             if(!isset($orders[$res->sales_id])){
            //                 $orders[$res->sales_id] = $res;
            //             }
            //         }
            //     }
            // }else{
            //     // if(count($n_results) > 0){
            //     //     foreach ($n_results as $nres) {
            //     //         if(!isset($orders[$nres->sales_id])){
            //     //             $orders[$nres->sales_id] = $nres;
            //     //         }
            //     //     }
            //     // }
            //     foreach ($results as $res) {
            //         if(!isset($orders[$res->sales_id])){
            //             $orders[$res->sales_id] = $res;
            //         }
            //     }
            // }


            foreach ($results as $res) {
                if(!isset($orders[$res->sales_id])){
                    $orders[$res->sales_id] = $res;
                }
            }

            $sales = array();
            $all_ids = array();
            $sales['void'] = array();
            $sales['cancel'] = array();
            $sales['settled'] = array();

            $sales['void']['ids'] = array();
            $sales['cancel']['ids'] = array();
            $sales['settled']['ids'] = array();

            $sales['void']['orders'] = array();
            $sales['cancel']['orders'] = array();
            $sales['settled']['orders'] = array();

            $net = 0;
            $void_amount = 0;
            $cancel_amount = 0;
            $all_guest = 0;
            $types = array();
            $ordsnums = array();
            $all_orders = array();
            $salesnums = array();
            $customer_count = 0;
            
            foreach ($orders as $sales_id => $sale) {
                if($sale->type_id == 10){
                    if($sale->trans_ref != "" && $sale->inactive == 0){
                        $sales['settled']['ids'][] = $sales_id;
                        $net += round($sale->total_amount,2);
                        $types[$sale->type][$sale->sales_id] = $sale;
                        $ordsnums[$sale->trans_ref] = $sale;
                        $salesnums[$sale->trans_ref] = $sale;
                        $sales['settled']['orders'][$sales_id] = $sale;
                        $customer_count += $sale->guest;
                    }
                    else if($sale->trans_ref == "" && $sale->inactive == 1){
                        if($sale->void_user_id){
                            $sales['cancel']['ids'][] = $sales_id;
                            $sales['cancel']['orders'][$sales_id] = $sale;
                            $cancel_amount += round($sale->total_amount,2);
                        }
                    }
                }
                else{
                    $sales['void']['ids'][] = $sales_id;
                    $sales['void']['orders'][$sales_id] = $sale;
                    $void_amount += round($sale->total_amount,2);
                }
                $all_ids[] = $sales_id;
                $all_orders[$sales_id] = $sale;
                $all_guest += $sale->guest;
            }
                // echo "<pre>",print_r($all_guest),"</pre>";die();
            ksort($ordsnums);
            $first = array_shift(array_slice($ordsnums, 0, 1));
            $last = end($ordsnums);
            $ref_ctr = count($ordsnums);

            if($orders){
                ksort($ordsnums);
                ksort($salesnums);
                // $first = array_shift(array_slice($ordsnums, 0, 1));
                // $last = end($ordsnums);
                $first_id = array_shift(array_slice($salesnums, 0, 1));
                $last_id = end($salesnums);
                // $ref_ctr = count($ordsnums);
                $id_ctr = count($salesnums);
            }else{
                $n_ref = $this->trans_model->get_next_ref(10);
                $l_id = $this->trans_model->get_last_sales_id();
                $first = $n_ref;
                $last = $n_ref;
                $first_id = $l_id[0]->sales_id + 1;
                $last_id = $l_id[0]->sales_id + 1;
                $ref_ctr = 0;
                $id_ctr = 0;
            }


            if(HIDECHIT){
                // if($curr){
                //     foreach($chit as $key => $vals){

                //         $this->site_model->db = $this->load->database('default', TRUE);
                //         $where = array('sales_id'=>$key);
                //         $results = $this->site_model->get_details($where,'trans_sales_payments');

                //         $total_chit += $results[0]->to_pay;
                //     }
                // }else{

                $chit_args = $args;
                $chit_args['trans_sales_payments.payment_type'] = 'chit';
                $chit_args['trans_sales.type_id'] = SALES_TRANS;
                $chit_args['trans_sales.inactive'] = 0;
                $chit_args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
                // echo "<pre>",print_r($chit_args),"</pre>";die();

                $p_res = $this->cashier_model->get_all_trans_sales_payments(null,$chit_args);
                // echo $this->cashier_model->db->last_query(); die();

                if($p_res){
                    foreach($p_res as $vals){

                        $this->site_model->db = $this->load->database('main', TRUE);
                        $where = array('sales_id'=>$vals->sales_id);
                        $results = $this->site_model->get_details($where,'trans_sales_payments');

                        $total_chit += $results[0]->to_pay;
                    }
                }
                // }
            }
            // echo $total_chit; die();
            if(PRODUCT_TEST){
                $pt_args = $args;
                $pt_args['trans_sales_payments.payment_type'] = 'producttest';
                $pt_args['trans_sales.type_id'] = SALES_TRANS;
                $pt_args['trans_sales.inactive'] = 0;
                $pt_args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
                // echo "<pre>",print_r($pt_args),"</pre>";die();
                $pa_res = $this->cashier_model->get_all_trans_sales_payments(null,$pt_args);

                if($pa_res){
                    foreach($pa_res as $vals){

                        $this->site_model->db = $this->load->database('main', TRUE);
                        $where = array('sales_id'=>$vals->sales_id);
                        $results = $this->site_model->get_details($where,'trans_sales_payments');

                        $total_producttest += $results[0]->to_pay;
                    }
                }
            }

            // echo $total_chit; die('sss');

            return array('all_ids'=>$all_ids,'all_orders'=>$all_orders,'sales'=>$sales,'net'=>$net,'void'=>$void_amount,'types'=>$types,'refs'=>$ordsnums,'first_ref'=>$first,'last_ref'=>$last,'ref_count'=>$ref_ctr,'total_chit'=>$total_chit,'cancel_amount'=>$cancel_amount,'product_test'=>$total_producttest,'all_guest'=>$all_guest,'trans_count'=>$trans_count,'customer_count'=>$customer_count,'first_id'=>$first_id,'last_id'=>$last_id,'id_ctr'=>$id_ctr);
        }
        public function trans_sales_cred($args=array(),$curr=false){
            $total_chit = 0;
            $chit = array();
            $n_results = array();
            
            $this->cashier_model->db = $this->load->database('main', TRUE);
            // echo "xxadads";die();
            $results = $this->cashier_model->get_trans_sales_via_credit_card(null,$args);

            // echo "<pre>",print_r($results),"</pre>";die();

            $orders = array();
            // if(HIDECHIT || PRODUCT_TEST){
            //     foreach ($results as $res) {
                    
            //         if($res->type_id == 10){
            //             $this->site_model->db = $this->load->database('main', TRUE);
            //             $where = array('sales_id'=>$res->sales_id,"payment_type"=>"credit");
            //             $rest = $this->site_model->get_details($where,'trans_sales_payments');
            //             // echo $this->site_model->db->last_query();
            //             if($rest){
            //                 if($rest[0]->payment_type == 'credit'){
            //                     if(!isset($orders[$res->sales_id])){
            //                         $orders[$res->sales_id] = $res;
            //                     }
            //                 }else{
            //                     $chit[$res->sales_id] = $res;
            //                 }
            //             }
            //             // else{
            //             //     if(!isset($orders[$res->sales_id])){
            //             //         $orders[$res->sales_id] = $res;
            //             //         echo "<pre>", print_r($orders),"</pre>";die();   
            //             //     }
            //             // }
            //         }else{
            //             if(!isset($orders[$res->sales_id])){
            //                 $orders[$res->sales_id] = $res;
            //             }
            //         }
            //     }
            // }else{
            //     foreach ($results as $res) {
            //         if(!isset($orders[$res->sales_id])){
            //             $orders[$res->sales_id] = $res;
            //         }
            //     }
            // }
            // // echo "<pre>",print_r($orders),"</pre>";die();
            // // die();
            // $sales = array();
            // $all_ids = array();
            // $sales['void'] = array();
            // $sales['cancel'] = array();
            // $sales['settled'] = array();

            // $sales['void']['ids'] = array();
            // $sales['cancel']['ids'] = array();
            // $sales['settled']['ids'] = array();

            // $sales['void']['orders'] = array();
            // $sales['cancel']['orders'] = array();
            // $sales['settled']['orders'] = array();

            // $net = 0;
            // $void_amount = 0;
            // $cancel_amount = 0;
            // $types = array();
            // $ordsnums = array();
            // $all_orders = array();
            
            // foreach ($orders as $sales_id => $sale) {
            //     if($sale->type_id == 10){
            //         if($sale->trans_ref != "" && $sale->inactive == 0){
            //             $sales['settled']['ids'][] = $sales_id;
            //             $net += round($sale->total_amount,2);
            //             $types[$sale->type][$sale->sales_id] = $sale;
            //             $ordsnums[$sale->trans_ref] = $sale;
            //             $sales['settled']['orders'][$sales_id] = $sale;
            //         }
            //         else if($sale->trans_ref == "" && $sale->inactive == 1){
            //             if($sale->void_user_id){
            //                 $sales['cancel']['ids'][] = $sales_id;
            //                 $sales['cancel']['orders'][$sales_id] = $sale;
            //                 $cancel_amount += round($sale->total_amount,2);
            //             }
            //         }
            //     }
            //     else{
            //         $sales['void']['ids'][] = $sales_id;
            //         $sales['void']['orders'][$sales_id] = $sale;
            //         $void_amount += round($sale->total_amount,2);
            //     }
            //     $all_ids[] = $sales_id;
            //     $all_orders[$sales_id] = $sale;
            // }
            // ksort($ordsnums);
            // $first = array_shift(array_slice($ordsnums, 0, 1));
            // $last = end($ordsnums);
            // $ref_ctr = count($ordsnums);

            // if(HIDECHIT || PRODUCT_TEST){
            //     // if($curr){
            //     //     foreach($chit as $key => $vals){

            //     //         $this->site_model->db = $this->load->database('default', TRUE);
            //     //         $where = array('sales_id'=>$key);
            //     //         $results = $this->site_model->get_details($where,'trans_sales_payments');

            //     //         $total_chit += $results[0]->to_pay;
            //     //     }
            //     // }else{
            //     foreach($chit as $key => $vals){

            //         $this->site_model->db = $this->load->database('main', TRUE);
            //         $where = array('sales_id'=>$key,"payment_type"=>"credit");
            //         $results = $this->site_model->get_details($where,'trans_sales_payments');

            //         $total_chit += $results[0]->to_pay;
            //     }
            //     // }
            // }


            // return array('all_ids'=>$all_ids,'all_orders'=>$all_orders,'sales'=>$sales,'net'=>$net,'void'=>$void_amount,'types'=>$types,'refs'=>$ordsnums,'first_ref'=>$first,'last_ref'=>$last,'ref_count'=>$ref_ctr,'total_chit'=>$total_chit,'cancel_amount'=>$cancel_amount);
            return array('sales'=>$results);
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
                $menu_cat_sale_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$ids,'pos_id'=>TERMINAL_ID));
                foreach ($menu_cat_sale_mods as $res) {
                    if(!in_array($res->sales_id, $mids_used)){
                        $mids_used[] = $res->sales_id;
                    }
                    if(!isset($mods[$res->menu_id][$res->mod_id])){
                        $mod_sub_cat = $this->site_model->get_tbl('modifiers',array('mod_id'=>$res->mod_id),array(),null,true,'mod_sub_cat_id');
                        $mod_sc = $mod_sub_cat[0];

                        $mods[$res->menu_id][$res->mod_id] = array(
                            'name'=>$res->mod_name,
                            'menu_id'=>$res->menu_id,
                            'price'=>$res->price,
                            'qty'=>$res->qty,
                            'total_amt'=>$res->qty * $res->price,
                            'sub_cat'=>$mod_sc->mod_sub_cat_id,
                        );

                        $sub_cat = $mod_sc->mod_sub_cat_id;
                        
                    }
                    else{
                        $mod = $mods[$res->menu_id][$res->mod_id];
                        $mod['qty'] += $res->qty;
                        $mod['total_amt'] += $res->qty * $res->price;
                        $mods[$res->menu_id][$res->mod_id] = $mod;

                        $sub_cat = $mod['sub_cat'];
                    }

                    if($sub_cat != 2){
                        //food
                        $sub_cats[1]['amount'] += $res->qty * $res->price;
                    }else{
                        //bev
                        $sub_cats[2]['amount'] += $res->qty * $res->price;
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



                $menu_cat_sale_submods = $this->cashier_model->get_trans_sales_menu_submodifiers_prints(null,array("trans_sales_menu_submodifiers.sales_id"=>$ids,'pos_id'=>TERMINAL_ID));
                // $sub_mods = array();
                // echo "<pre>",print_r($menu_cat_sale_submods),"</pre>";die();
                foreach ($menu_cat_sale_submods as $subm) {
                    // if($res->mod_id == $subm->mod_id){
                        
                        if(isset($sub_mods[$subm->mod_id][$subm->mod_sub_id])){
                            $row = $sub_mods[$subm->mod_id][$subm->mod_sub_id];
                            $row['total_amt'] += $subm->price * $subm->qty;
                            $row['qty'] += $subm->qty;

                            $sub_mods[$subm->mod_id][$subm->mod_sub_id] = $row;

                            $sub_cat = $row['sub_cat'];

                        }else{
                            $mod_sub_cat = $this->site_model->get_tbl('modifiers',array('mod_id'=>$subm->mod_id),array(),null,true,'mod_sub_cat_id');
                            $mod_sc = $mod_sub_cat[0];

                            $sub_mods[$subm->mod_id][$subm->mod_sub_id] = array(
                                'name'=>$subm->submod_name,
                                'price'=>$subm->price,
                                'qty'=>$subm->qty,
                                'mod_id'=>$subm->mod_id,
                                'total_amt'=>$subm->price * $subm->qty,
                                'sub_cat'=>$mod_sc->mod_sub_cat_id,
                                // 'qty'=>$subm->qty,
                            );

                            $sub_cat = $mod_sc->mod_sub_cat_id;
                        }

                        if($sub_cat != 2){
                            //food
                            $sub_cats[1]['amount'] += $subm->qty * $subm->price;
                        }else{
                            //bev
                            $sub_cats[2]['amount'] += $subm->qty * $subm->price;
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
        public function payment_breakdown_sales($ids=array(),$curr=false){
            $ret = array();
            $total = 0;
            $pays = array();
            $ids_used = array();
            $n_payments=array();
            if(count($ids) > 0){
                $n_payments=array();
            }

            $args = array();
            if(count($ids) > 0){
                $args['trans_sales_payments.sales_id'] = $ids;
                $args['trans_sales_payments.pos_id'] = TERMINAL_ID;
            }
            
            $join['trans_sales'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id','mode'=>'left');
            $select = "trans_sales_payments.*,trans_sales.sales_id as order_num,trans_sales.trans_ref";
            if($curr){
                $this->site_model->db = $this->load->database('default', TRUE);
                $n_payments = $this->site_model->get_tbl('trans_sales_payments',$args,array('payment_type'=>'asc'),$join,true,$select);
            }
            $this->site_model->db = $this->load->database('main', TRUE);
            $payments = $this->site_model->get_tbl('trans_sales_payments',$args,array('payment_type'=>'asc'),$join,true,$select);
            foreach ($payments as $py) {
                if(!in_array($py->payment_id, $ids_used)){
                    $ids_used[] = $py->payment_id;
                    $pays[$py->payment_id] = $py;
                }
            }
            if(count($n_payments)){
                foreach ($n_payments as $py) {
                    if(!in_array($py->payment_id, $ids_used)){
                        $pays[] = $py;
                    }
                }
            }
            $pay_types = array();
            foreach ($pays as $ctr => $row) {
                if(!in_array($row->payment_type,$pay_types)){
                    $pay_types[] = $row->payment_type;
                }
            }
            return array("payments"=>$pays,"types"=>$pay_types);
        }
        public function payment_sales($ids=array(),$curr=false){
            $ret = array();
            $total = 0;
            $pays = array();

            $ov_total = 0;
            $ov_pays = array();

            $ids_used = array();
            $gc_excess = 0;
            $cards = array();
            if(count($ids) > 0){
                $n_payments=array();
                // if($curr){
                //     $this->cashier_model->db = $this->load->database('default', TRUE);
                //     $n_payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$ids));

                // }
                $this->cashier_model->db = $this->load->database('main', TRUE);
                $payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$ids,"trans_sales_payments.pos_id"=>TERMINAL_ID));
                foreach ($payments as $py) {
                    if(!in_array($py->sales_id, $ids_used)){
                        $ids_used[] = $py->sales_id;
                    }
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

                    if($py->payment_type == 'gc'){
                        if($py->amount > $py->to_pay){
                            $excess = $py->amount - $py->to_pay;
                            $gc_excess += $excess;
                        }
                    }

                    if($py->payment_type == 'credit' || $py->payment_type == 'debit'){
                        if(isset($cards[$py->card_type])){
                            $cards[$py->card_type]['amount'] += $amount;
                            $cards[$py->card_type]['count'] += 1;
                        }else{
                            $cards[$py->card_type] = array('amount'=>$amount,'count'=>1);
                        }
                    }

                }
                // if(count($n_payments) > 0){
                //     foreach ($n_payments as $py) {
                //         if(!in_array($py->sales_id, $ids_used)){
                //             if($py->amount > $py->to_pay)
                //                 $amount = $py->to_pay;
                //             else
                //                 $amount = $py->amount;
                //             if(!isset($pays[$py->payment_type])){
                //                 $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                //             }
                //             else{
                //                 $pays[$py->payment_type]['qty'] += 1;
                //                 $pays[$py->payment_type]['amount'] += $amount;
                //             }
                //             $total += $amount;

                //             if($py->payment_type == 'gc'){
                //                 if($py->amount > $py->to_pay){
                //                     $excess = $py->amount - $py->to_pay;
                //                     $gc_excess += $excess;
                //                 }
                //             }

                //             if($py->payment_type == 'credit'){
                //                 if(isset($cards[$py->card_type])){
                //                     $cards[$py->card_type]['amount'] += $amount;
                //                     $cards[$py->card_type]['count'] += 1;
                //                 }else{
                //                     $cards[$py->card_type] = array('amount'=>$amount,'count'=>1);
                //                 }
                //             }
                //         }
                //     }
                // }

                //FOR PERI PERI OVER GC
                foreach ($payments as $py) {
                    if(!in_array($py->sales_id, $ids_used)){
                        $ids_used[] = $py->sales_id;
                    }

                    if($py->payment_type == 'gc'){
                        $amount = $py->amount;
                    }
                    else{
                        if($py->amount > $py->to_pay)
                            $amount = $py->to_pay;
                        else
                            $amount = $py->amount;
                    }
                    if(!isset($ov_pays[$py->payment_type])){
                        $ov_pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                    }
                    else{
                        $ov_pays[$py->payment_type]['qty'] += 1;
                        $ov_pays[$py->payment_type]['amount'] += $amount;
                    }
                    $ov_total += $amount;
                }
                if(count($n_payments) > 0){
                    foreach ($n_payments as $py) {
                        if(!in_array($py->sales_id, $ids_used)){

                            if($py->payment_type == 'gc'){
                                $amount = $py->amount;
                            }
                            else{
                                if($py->amount > $py->to_pay)
                                    $amount = $py->to_pay;
                                else
                                    $amount = $py->amount;
                            }
                            if(!isset($ov_pays[$py->payment_type])){
                                $ov_pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                            }
                            else{
                                $ov_pays[$py->payment_type]['qty'] += 1;
                                $ov_pays[$py->payment_type]['amount'] += $amount;
                            }
                            $ov_total += $amount;
                        }
                    }
                }
            }
            $ret['total'] = $total;
            $ret['types'] = $pays;
            $ret['gc_excess'] = $gc_excess;
            $ret['cards'] = $cards;
            $ret['ov_total'] = $ov_total;
            $ret['ov_types'] = $ov_pays;

            return $ret;
        }
        public function removed_menu_sales($ids=array(),$curr=false){
            $reasons = array();
            if(count($ids) > 0){
                $n_remove_sales = array();
                // if($curr){
                //     $this->cashier_model->db = $this->load->database('default', TRUE);
                //     $n_remove_sales = $this->cashier_model->get_reasons(null,array("reasons.trans_id"=>$ids));
                // }
                $this->cashier_model->db = $this->load->database('main', TRUE);
                $remove_sales = $this->cashier_model->get_reasons(null,array("reasons.trans_id"=>$ids));

                $ids_used = array();
                foreach ($remove_sales as $res) {
                    if(!in_array($res->id, $ids_used)){
                        $ids_used[] = $res->id;
                    }
                    if(!isset($reasons[$res->id])){
                        $reasons[$res->id] = array('item'=>$res->ref_name,'reason'=>$res->reason,'trans_id'=>$res->trans_id,'manager'=>$res->man_username,'cashier'=>$res->cas_username);
                    }
                }
                // if(count($n_remove_sales) > 0){
                //     foreach ($n_remove_sales as $res) {
                //         if(!in_array($res->id, $ids_used)){
                //             if(!isset($reasons[$res->id])){
                //                 $reasons[$res->id] = array('item'=>$res->ref_name,'reason'=>$res->reason,'trans_id'=>$res->trans_id,'manager'=>$res->man_username,'cashier'=>$res->cas_username);
                //             }
                //         }
                //     }
                // }
            }
            return $reasons;
        }
        public function old_grand_total($date=""){
            $old_grand_total = 0;
            $ctr = 0;
            $this->site_model->db = $this->load->database('main', TRUE);
            $args['trans_sales.datetime <= '] = $date;
            $args['trans_sales.type_id'] = SALES_TRANS;
            $args['trans_sales.inactive'] = 0;
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $args['trans_sales.pos_id'] = TERMINAL_ID;
            $join['trans_sales'] = array('content'=>'trans_sales_menus.sales_id = trans_sales.sales_id','mode'=>'left');
            // $join['trans_sales_menu_modifiers'] = array('content'=>'trans_sales_menu_modifiers.sales_id = trans_sales.sales_id','mode'=>'left');
            $result = $this->site_model->get_tbl('trans_sales_menus',$args,array(),$join,true,'sum(trans_sales_menus.qty * trans_sales_menus.price) as total');
            if(count($result) > 0){

                $old_grand_total += $result[0]->total;
            }


            $gargs['trans_sales.datetime <= '] = $date;
            $gargs['trans_sales.type_id'] = SALES_TRANS;
            $gargs['trans_sales.inactive'] = 0;
            $gargs["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $gargs['trans_sales.pos_id'] = TERMINAL_ID;
            // $join['trans_sales'] = array('content'=>'trans_sales_menus.sales_id = trans_sales.sales_id','mode'=>'left');
            $join['trans_sales'] = array('content'=>'trans_sales_menu_modifiers.sales_id = trans_sales.sales_id','mode'=>'left');
            $result = $this->site_model->get_tbl('trans_sales_menu_modifiers',$gargs,array(),$join,true,'sum(trans_sales_menu_modifiers.qty * trans_sales_menu_modifiers.price) as total');
            // echo $this->site_model->db->last_query();
            if(count($result) > 0){
                $old_grand_total += $result[0]->total;
            }
            $cargs = array('read_type'=>Z_READ,'DATE(read_details.read_date) <= '=>date2Sql($date));
            $cargs['read_details.pos_id'] = TERMINAL_ID;
            $ctrresult = $this->site_model->get_tbl('read_details',$cargs,array(),null,true,'id','read_date',null);
            foreach ($ctrresult as $res) {
                $ctr++;
            }
            // echo var_dump($old_grand_total);
            return array('old_grand_total'=>$old_grand_total,'ctr'=>$ctr);
        }
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
                $result = $this->site_model->get_tbl('trans_sales_payments',$gcargs,array(),$joingc,true,'trans_sales_payments.amount as total_amount, trans_sales_payments.to_pay as total_topay');
                $gc_excess = 0;
                if(count($result) > 0){
                    foreach ($result as $value) {
                        if($value->total_amount > $value->total_topay){
                            $gc_excess += $value->total_amount - $value->total_topay;

                        }
                    }
                }
                // $old_grand_total += $gc_excess;
                $true_grand_total += $gc_excess;
            }
            // echo $this->site_model->db->last_query(); die();



            $cargs = array('read_type'=>Z_READ,'DATE(read_details.read_date) <= '=>date2Sql($date));
            // if($this->site_model->db->database == "dinemain")
            //     $cargs['read_details.pos_id'] = TERMINAL_ID;

            $ctrresult = $this->site_model->get_tbl('read_details',$cargs,array(),null,true,'id','read_date',null);
            foreach ($ctrresult as $res) {
                $ctr++;
            }
            return array('old_grand_total'=>$old_grand_total,'true_grand_total'=>$true_grand_total,'ctr'=>$ctr);
        }
        public function shift_cashout($shift_id=""){
            $cashout_id = "";
            $shift = $this->site_model->get_tbl('shifts',array('shift_id'=>$shift_id));
            if(count($shift) > 0){
                $sh = $shift[0];
                if($sh->cashout_id != ""){
                    $cashout_id = $sh->cashout_id;
                }
            }
            ####
            return $cashout_id;
        }
        public function shift_entries($shift_id=""){
            $this->load->model('dine/clock_model');

            $shift = null;
            $total_drops = 0;
            $total_deps = $total_withs = array();
            $total_sales = 0;
            $overAllTotal = 0;

            $entries = $this->clock_model->get_shift_entries(null,array("shift_entries.shift_id"=>$shift_id));
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
                "trans_sales.shift_id"=>$shift_id,
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
            return array(
                'total_drops'=>$total_drops,
                'total_deps'=>$total_deps,
                'total_withs'=>$total_withs,
                'total_sales'=>$total_sales,
                'overAllTotal'=>$overAllTotal
            );
        }

        /////for hourlysales report with categories
        public function trans_sales_cat($args=array(),$curr=false){
            $n_results = array();
            // if($curr){
            //     $this->cashier_model->db = $this->load->database('default', TRUE);
            //     $n_results  = $this->cashier_model->get_trans_sales_wcategories(null,$args);
            //     // echo $this->cashier_model->db->last_query();
            // }
            $this->cashier_model->db = $this->load->database('main', TRUE);
            $results = $this->cashier_model->get_trans_sales_wcategories(null,$args);
            // die();
            $orders = array();
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

            $sales = array();
            $all_ids = array();
            $sales['void'] = array();
            $sales['cancel'] = array();
            $sales['settled'] = array();

            $sales['void']['ids'] = array();
            $sales['cancel']['ids'] = array();
            $sales['settled']['ids'] = array();

            $sales['void']['orders'] = array();
            $sales['cancel']['orders'] = array();
            $sales['settled']['orders'] = array();

            $net = 0;
            $void_amount = 0;
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
                        $sales['cancel']['ids'][] = $sales_id;
                        $sales['cancel']['orders'][$sales_id] = $sale;
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
            // echo "<pre>",print_r($net),"</pre>";die();
            $first = array_shift(array_slice($ordsnums, 0, 1));
            $last = end($ordsnums);
            $ref_ctr = count($ordsnums);
            return array('all_ids'=>$all_ids,'all_orders'=>$all_orders,'sales'=>$sales,'net'=>$net,'void'=>$void_amount,'types'=>$types,'refs'=>$ordsnums,'first_ref'=>$first,'last_ref'=>$last,'ref_count'=>$ref_ctr);
        }

        // for cancelled orders
        public function cancelled_orders(){
            // $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $trans_removes = $this->removed_menu_sales($trans['all_ids'],$curr);
            $total_cancel = 0;
            if(count($trans_removes) > 0){
                foreach ($trans_removes as $v) {
                    
                    $exp = explode(' - ', $v['item']);
                    // $type = $rea['type'];
                    // $name = $exp[1];
                    // $qtys = explode(':', $exp[2]);
                    // $qty = $qtys[1];
                    if (array_key_exists(3,$exp)){
                        $price = $exp[3];
                    }
                    else{
                        $price = 0; 
                    }
                    $on_id = null;
                    $menuLine = explode(' ',$exp[0]);

                    $total_cancel += $price;

                }
            }

            return array('cancelled_order'=>$total_cancel);


        }

        /**@Justin 07042018

            do_print_v2 - function which prints without bat file

        **/

        public function do_print_v2($print_str=null,$asJson=false){
            if (!$asJson) {
                // echo "<pre style='background-color:#fff'>$print_str</pre>";
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
            }
            else{
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
                    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";exit;
                }
              
            }
        }

        public function trans_sales_miaa($args=array(),$curr=false){
            $total_chit = $total_producttest = 0;

            $chit = array();
            $producttest = array();
            $n_results = array();
            if($curr){
                $this->cashier_model->db = $this->load->database('default', TRUE);
                $n_results  = $this->cashier_model->get_trans_sales(null,$args);
            }
            
            $this->cashier_model->db = $this->load->database('main', TRUE);
            $results = $this->cashier_model->get_trans_sales(null,$args);
            // echo "<pre>",print_r($results),"</pre>";die();
            // echo $this->cashier_model->db->last_query();
            $orders = array();
            if(HIDECHIT){
                if(count($n_results) > 0){
                    foreach ($n_results as $nres) {

                        if($nres->type_id == 10){
                            $this->site_model->db = $this->load->database('default', TRUE);
                            $where = array('sales_id'=>$nres->sales_id);
                            $rest = $this->site_model->get_details($where,'trans_sales_payments');
                            if($rest){
                                if($rest[0]->payment_type != 'chit'){

                                    if(!isset($orders[$nres->sales_id])){
                                        $orders[$nres->sales_id] = $nres;
                                    }
                                }else{
                                    $chit[$nres->sales_id] = $nres;
                                }
                            }else{
                                if(!isset($orders[$nres->sales_id])){
                                    $orders[$nres->sales_id] = $nres;
                                }
                            }

                        }else{
                            if(!isset($orders[$nres->sales_id])){
                                $orders[$nres->sales_id] = $nres;
                            }
                        }
                    }
                }
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
                if(count($n_results) > 0){
                    foreach ($n_results as $nres) {
                        if(!isset($orders[$nres->sales_id])){
                            $orders[$nres->sales_id] = $nres;
                        }
                    }
                }
                foreach ($results as $res) {
                    if(!isset($orders[$res->sales_id])){
                        $orders[$res->sales_id] = $res;
                    }
                }
            }
            if(PRODUCT_TEST){
                if(count($n_results) > 0){
                    foreach ($n_results as $nres) {

                        if($nres->type_id == 10){
                            $this->site_model->db = $this->load->database('default', TRUE);
                            $where = array('sales_id'=>$nres->sales_id);
                            $rest = $this->site_model->get_details($where,'trans_sales_payments');
                            if($rest){
                                if($rest[0]->payment_type != 'producttest'){

                                    if(!isset($orders[$nres->sales_id])){
                                        $orders[$nres->sales_id] = $nres;
                                    }
                                }else{
                                    $producttest[$nres->sales_id] = $nres;
                                }
                            }else{
                                if(!isset($orders[$nres->sales_id])){
                                    $orders[$nres->sales_id] = $nres;
                                }
                            }

                        }else{
                            if(!isset($orders[$nres->sales_id])){
                                $orders[$nres->sales_id] = $nres;
                            }
                        }
                    }
                }
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
                if(count($n_results) > 0){
                    foreach ($n_results as $nres) {
                        if(!isset($orders[$nres->sales_id])){
                            $orders[$nres->sales_id] = $nres;
                        }
                    }
                }
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

            $sales['void']['ids'] = array();
            $sales['cancel']['ids'] = array();
            $sales['settled']['ids'] = array();

            $sales['void']['orders'] = array();
            $sales['cancel']['orders'] = array();
            $sales['settled']['orders'] = array();

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
                if($curr){
                    foreach($chit as $key => $vals){

                        $this->site_model->db = $this->load->database('default', TRUE);
                        $where = array('sales_id'=>$key);
                        $results = $this->site_model->get_details($where,'trans_sales_payments');

                        $total_chit += $results[0]->to_pay;
                    }
                }else{
                    foreach($chit as $key => $vals){

                        $this->site_model->db = $this->load->database('main', TRUE);
                        $where = array('sales_id'=>$key);
                        $results = $this->site_model->get_details($where,'trans_sales_payments');

                        $total_chit += $results[0]->to_pay;
                    }
                }
            }
            if(PRODUCT_TEST){
                if($curr){
                    foreach($producttest as $key => $vals){

                        $this->site_model->db = $this->load->database('default', TRUE);
                        $where = array('sales_id'=>$key);
                        $results = $this->site_model->get_details($where,'trans_sales_payments');

                        $total_producttest += $results[0]->to_pay;
                    }
                }else{
                    foreach($producttest as $key => $vals){

                        $this->site_model->db = $this->load->database('main', TRUE);
                        $where = array('sales_id'=>$key);
                        $results = $this->site_model->get_details($where,'trans_sales_payments');

                        $total_producttest += $results[0]->to_pay;
                    }
                }
            }


            return array('all_ids'=>$all_ids,'all_orders'=>$all_orders,'sales'=>$sales,'net'=>$net,'void'=>$void_amount,'types'=>$types,'refs'=>$ordsnums,'first_ref'=>$first,'last_ref'=>$last,'ref_count'=>$ref_ctr,'total_chit'=>$total_chit,'cancel_amount'=>$cancel_amount,'product_test'=>$total_producttest);
        }

        public function old_grand_net_total_miaa($date="",$add_void=false){
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
            $this->site_model->db = $this->load->database('default', TRUE);
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

            $this->site_model->db = $this->load->database('default', TRUE);
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
            $this->site_model->db  = $this->load->database('default', TRUE);
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
                $this->site_model->db = $this->load->database('default', TRUE);
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

        public function item_sales_cat($ids=array(),$cat_id=''){
            #ITEMS

             $item_amount_total = 0;
             $item_qty_total = 0;
             $item_discount = 0;;
             $item_net_total = 0;

             $nv_item_qty_total = 0;
             $nv_item_discount = 0;
             $nv_item_net_total = 0;
             $nv_item_amount_total = 0;

            if(count($ids) > 0){
                $select = 'trans_sales_items.*,items.code as item_code,items.name as item_name,items.cost as item_cost, disc_rate as discount, disc_code';
                $join = array();
                $join['items'] = array('content'=>'trans_sales_items.item_id = items.item_id');
                $join['trans_sales_discounts'] = array('content'=>'trans_sales_items.sales_id = trans_sales_discounts.sales_id', 'mode'=>'left');
                $n_item_res = array();
            
                $args["trans_sales_items.sales_id"] = $ids;
                $args["cat_id"] = $cat_id;

                $this->site_model->db= $this->load->database('main', TRUE);
                $item_res = $this->site_model->get_tbl('trans_sales_items',$args,array(),$join,true,$select);
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

                    $discount = ($ms->price * $ms->qty)*($ms->discount/100);
                    
                    if(!in_array($ms->disc_code, array('PWDISC','SNDISC'))){
                        $item_qty_total += $ms->qty;
                        $item_discount += $discount;
                        $item_net_total += $ms->price * $ms->qty - $discount;
                        $item_amount_total += $ms->price * $ms->qty;
                    }else{
                        $nv_item_qty_total += $ms->qty;
                        $nv_item_discount += round($discount/1.12,2);
                        $nv_item_net_total += round(($ms->price * $ms->qty - $discount)/1.12,2);
                        $nv_item_amount_total += $ms->price * $ms->qty;
                    }
                    
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
            
             return array('gross'=>$item_net_total, 'amount'=>$item_amount_total, 'item_total_qty'=>$item_qty_total, 'discount'=>$item_discount,'nv_gross'=>$nv_item_net_total,'nv_amount'=>$nv_item_amount_total,'nv_item_total_qty'=>$nv_item_qty_total, 'nv_discount'=>$nv_item_discount);
        }

        public function cashier_sales_rep($asJson=false){
            ////hapchan
            ini_set('memory_limit', '-1');
            set_time_limit(3600);
            
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            // echo print_r($post['shift_id']);die();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            // var_dump($trans['net']); die();
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
            $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
            $tax_disc = $trans_discounts['tax_disc_total'];
            $no_tax_disc = $trans_discounts['no_tax_disc_total'];
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
            $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
            $payments = $this->payment_sales($sales['settled']['ids'],$curr);
            $change_fund =    $this->shift_entries($post['shift_id']);
            // echo print_r($trans_discounts['sales_disc_count']);die();
            // $cashout_details = $this->cashier_model->get_cashout_details($post['shift_id']);
            $gross = $trans_menus['gross'];
            
            $net = $trans['net'];
            $void = $trans['void'];
            $cancelled = $trans['cancel_amount'];
            $charges = $trans_charges['total'];
            $discounts = $trans_discounts['total'];
            $local_tax = $trans_local_tax['total'];
            // echo $gross.' - '.$charges.' - '.$discounts.' - '.$net; die();
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            // $less_vat = $trans_discounts['vat_exempt_total'];

            // echo $gross.'+'.$charges.'+'.$local_tax.' - '.$discounts.' - '.$net;
            // die();

            if($less_vat < 0)
                $less_vat = 0;
            // var_dump($less_vat);

            //para mag tugmam yun payments and netsale
            // $net_sales2 = $gross + $charges - $discounts - $less_vat;
            // $diffs = $net_sales2 - $payments['total'];
            // if($diffs < 1){
            //     $less_vat = $less_vat + $diffs;
            // }
            

            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;

            $title_name = "SYSTEM SALES REPORT";
            if($post['title'] != "")
                $title_name = $post['title'];

            // $print_str .= align_center($title_name,PAPER_WIDTH," ")."\r\n";
            // $print_str .= align_center("TERMINAL ".$post['terminal'],PAPER_WIDTH," ")."\r\n";
            // $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            // $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            // $print_str .= PAPER_LINE."\r\n";
            // $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),PAPER_WIDTH," ")."\r\n";
            // if($post['employee'] != "All")
            //     $print_str .= align_center($post['employee'],PAPER_WIDTH," ")."\r\n";
            // $print_str .= PAPER_LINE."\r\n";

            $loc_txt = numInt(($local_tax));
            $net_no_adds = $net-($charges+$local_tax);
            // $nontaxable = $no_tax;
            //binago 9/25/2018 for zreading adjustment of vat exempt equal to the receipt vat exempt
            $nontaxable = $no_tax - $no_tax_disc;
            // echo $gross.' - '.$less_vat.' - '.$nontaxable.' - '.$zero_rated; die();
            // $taxable = ($gross - $less_vat - $nontaxable - $zero_rated) / 1.12;
            // 1.12; binago din para sa adjustment of vat exempt equal to the receipt vat exempt
            // $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
            $taxable =   ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12; //change computation conflict for zero rated 10 17 2018
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
            $add_gt = $taxable+$nontaxable+$zero_rated;
            $nsss = $taxable +  $nontaxable +  $zero_rated;


            #CONTROL

                // $print_str .= append_chars(substrwords('CASH :',18,""),"right",PAPER_TOTAL_COL_1," ")
                //                          .append_chars(iSetObj($trans['first_ref'],'trans_ref'),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";

                // $print_str .= append_chars(substrwords('TRANS #',18,""),"right",8," ").align_center('(START)',15," ")
                //                  .append_chars(iSetObj($trans['first_ref'],'trans_ref'),"left",PAPER_RD_COL_3_3," ")."\r\n";
                // $print_str .= append_chars(substrwords('',18,""),"right",8," ").align_center('(END)',15," ")
                //                  .append_chars(iSetObj($trans['last_ref'],'trans_ref'),"left",PAPER_RD_COL_3_3," ")."\r\n\r\n";

                // $print_str .= PAPER_LINE_SINGLE."\r\n";

             #PREVIOUS
                // echo $post['from']; die();
                //get olds payments
                $select1 = 'sum(trans_sales_payments.to_pay) as tpay, sum(trans_sales_payments.amount) as tamount, payment_type';
                $select2 = 'sum(trans_sales_payments.amount) as tamount, sum(trans_sales_payments.to_pay) as tpay, payment_type';
                $pjoin['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_payments.sales_id');

                $this->site_model->db= $this->load->database('main', TRUE);
                $pargs["amount >= to_pay"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs["trans_sales.type_id"] = 10;
                $pargs["trans_sales.inactive"] = '0';
                $pargs["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs['trans_sales.datetime <= '] = $post['from'];

                $pargs2["amount < to_pay"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs2["trans_sales.type_id"] = 10;
                $pargs2["trans_sales.inactive"] = '0';
                $pargs2["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs2['trans_sales.datetime <= '] = $post['from'];
                $pgroup = 'trans_sales_payments.payment_type';
                $old_payment_exact = $this->site_model->get_tbl('trans_sales_payments',$pargs,array(),$pjoin,true,$select1,$pgroup);
                $old_payment_not_ex = $this->site_model->get_tbl('trans_sales_payments',$pargs2,array(),$pjoin,true,$select2,$pgroup);

                //get olds charges
                $selectc = 'sum(amount) as tcharges';
                $cjoin['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_charges.sales_id');
                $cargs["trans_sales.type_id"] = 10;
                $cargs["trans_sales.inactive"] = '0';
                $cargs["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $cargs['trans_sales.datetime <= '] = $post['from'];
                $get_old_charges = $this->site_model->get_tbl('trans_sales_charges',$cargs,array(),$cjoin,true,$selectc);

                $old_charges = 0;
                $old_net_sales = 0;
                $old_total_payment = 0;
                foreach ($get_old_charges as $ch => $val) {
                    $old_charges += $val->tcharges;
                }


                //get olds discounts
                $selectd = 'sum(amount) as tdisc, disc_code';
                $djoin['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_discounts.sales_id');
                $dargs["trans_sales.type_id"] = 10;
                $dargs["trans_sales.inactive"] = '0';
                $dargs["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $dargs['trans_sales.datetime <= '] = $post['from'];
                $dgroup = 'trans_sales_discounts.disc_id';
                $get_old_disc = $this->site_model->get_tbl('trans_sales_discounts',$dargs,array(),$djoin,true,$selectd,$dgroup);

                $old_sc_disc = 0;
                $old_line_disc = 0;
                foreach ($get_old_disc as $dc => $val) {
                    $amount = $val->tdisc;
                    if($val->disc_code == 'SNDISC' || $val->disc_code == 'PWDISC'){
                        $old_sc_disc += $amount;
                    }else{
                        $old_line_disc += $amount;
                    }
                    
                }

                // echo $this->site_model->db->last_query(); die();

                $this->site_model->db= $this->load->database('default', TRUE);


                // $print_str .= append_chars(substrwords('PREVIOUS GRAND TOTALS:',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         // .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";


                #PAYMENTS
                $payments_array = array();
                $ptype = array('cash','credit','gc','debit','foodpanda','alipay','wechat');
                $old_forfeited_gc = 0;

                foreach ($ptype as $key => $value) {
                    $amount_p = 0;
                    foreach ($old_payment_exact as $kkk => $val) {
                        if($value == $val->payment_type){
                            $amount_p += $val->tpay;
                            if($value == 'gc'){
                                $old_forfeited_gc = $val->tamount - $val->tpay;
                                $amount_p += $old_forfeited_gc;
                            }
                        }

                    }
                    foreach ($old_payment_not_ex as $kkk => $val) {
                        if($value == $val->payment_type){
                            $amount_p += $val->tamount;
                        }
                    }
                    $old_total_payment += $amount_p;
                    // $print_str .= append_chars(substrwords(strtoupper($value),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($amount_p),"left",PAPER_RD_COL_3_3," ")."\r\n";


                    $payments_array[$value] = array('amount'=>$amount_p);
                }

                // $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  // .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                // $print_str .= append_chars(substrwords(strtoupper('FORFEITED GC'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars('('.numInt($old_forfeited_gc).")","left",PAPER_RD_COL_3_3," ")."\r\n";
                // $print_str .= append_chars(substrwords(strtoupper('SERVICE CHARGE'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars('('.numInt($old_charges).')',"left",PAPER_RD_COL_3_3," ")."\r\n";

                // $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  // .append_chars('-------------',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $old_net_sales = $old_total_payment - $old_charges; 
                // $print_str .= append_chars(substrwords(strtoupper('OLD NET SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($old_net_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                // $print_str .= PAPER_LINE_SINGLE."\r\n";


                // $print_str .= append_chars(substrwords(strtoupper('ADD: LINE DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($old_line_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";
                // $print_str .= append_chars(substrwords(strtoupper('SC DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($old_sc_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";

                // $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  // .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $old_gross_sales = $old_net_sales + $old_sc_disc + $old_line_disc;
                // $print_str .= append_chars(substrwords(strtoupper('OLD GROSS SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($old_gross_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                // $print_str .= PAPER_LINE_SINGLE."\r\n\r\n";


                // $print_str .= append_chars(substrwords('OLD SERVICE CHARGE',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         // .append_chars(numInt($old_charges),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";

                // $print_str .= PAPER_LINE_SINGLE."\r\n";


            #TODAY

                // $print_str .= append_chars(substrwords('TODAY REGISTER TOTAL SALES:',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         // .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";


                #PAYMENTS
                $payments_types = $payments['types'];
                $payments_total = $payments['total'];
                $pay_qty = 0;
                $t_payment = 0;
                $forfeited_gc = 0;
                $ptype = array('cash','credit','gc','debit','foodpanda','alipay','wechat','redeemed points','cheque','on account','claimed deposit');

                foreach ($ptype as $key => $value) {
                    $amount_p = 0;
                    foreach ($payments_types as $code => $val) {
                        if($value == $code){
                            $amount_p += $val['amount'];
                        }
                    }
                    if($value == 'gc'){
                        if($payments['gc_excess']){
                            $amount_p += $payments['gc_excess'];
                            $forfeited_gc += $payments['gc_excess'];
                        }
                    }
                    $t_payment += $amount_p;
                    $print_str .= append_chars(substrwords(strtoupper($value),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($amount_p),"left",PAPER_RD_COL_3_3," ")."\r\n";


                    if(isset($payments_array[$value])){
                        $row = $payments_array[$value];
                        $row['amount'] += $amount_p;
                        $payments_array[$value] = $row;
                    }else{
                        $payments_array[$value] = array('amount'=>$amount_p);
                    }
                }

                $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= append_chars(substrwords(strtoupper('FORFEITED GC'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars('('.numInt($forfeited_gc).")","left",PAPER_RD_COL_3_3," ")."\r\n";
               $t_gross_receipt = $t_payment  - $forfeited_gc; 
                $print_str .= append_chars(substrwords(strtoupper('TOTAL GROSS RECEIPTS'),20,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($t_gross_receipt),"left",PAPER_RD_COL_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('LESS:SERVICE CHARGE'),20,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars('('.numInt($charges).')',"left",PAPER_RD_COL_3_3," ")."\r\n";
                $reg_net_sales = $t_gross_receipt - $charges;
                // $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  // .append_chars('-------------',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= PAPER_LINE_SINGLE."\r\n";
                $n_sales = $t_payment - $charges - $forfeited_gc; 
                // $print_str .= append_chars(substrwords(strtoupper('NET SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($n_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                // $print_str .= PAPER_LINE_SINGLE."\r\n";

                $types = $trans_discounts['types'];
                $sc_disc = 0;
                $line_disc = 0;
                foreach ($types as $code => $val) {
                    $amount = $val['amount'];
                    if($code == 'SNDISC' || $code == 'PWDISC'){
                        $sc_disc += $amount;
                    }else{
                        $line_disc += $amount;
                    }
                    
                }

                $print_str .= append_chars(substrwords(strtoupper('REGISTERED NET SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($reg_net_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('ADD: LINE DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($line_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('SC DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($sc_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n\r\n";
                // $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  // .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $g_sales = $n_sales + $sc_disc + $line_disc;
                $reg_gross_sales = $reg_net_sales + $line_disc + $sc_disc;
                $print_str .= append_chars(substrwords(strtoupper('REGISTERED GROSS SALES'),20,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($reg_gross_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";
                // $print_str .= append_chars(substrwords(strtoupper('GROSS SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($g_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";



                // $print_str .= append_chars(substrwords('TODAY SERVICE CHARGE',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         // .append_chars(numInt($charges),"left",PAPER_TOTAL_COL_2," ")."\r\n";


                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";


            #NEW

                $print_str .= append_chars(substrwords('REGISTER ACCOUNTABILITY ',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('REGISTER NO. 1 ',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= append_chars(substrwords('NEW GRAND TOTALS:',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         // .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";


                #PAYMENTS
                $payments_types = $payments['types'];
                $payments_total = $payments['total'];
                $pay_qty = 0;


                $print_str .= append_chars(substrwords('DEPOSIT DETAILS ',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";

                $print_str .= append_chars(substrwords('CASH ',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('CREDIT CARD',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('DEBIT CARD',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('CHEQUE ',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";

                $print_str .= append_chars(substrwords('TOTAL DEPOSIT ',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";

                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";
                $ptype = array('cash');
                // foreach ($ptype as $key => $value) {
                    $amount_p = 0;
                    $amount_t = 0;
                    $t = 0;
                    $payments_types = 0;
                    if(isset($payments['types']['cash'])){
                        $payments_types = $payments['types']['cash'];
                    }
                    // $amount_p = 0;
                    // foreach ($payments_types as $code => $val) {
                        // if($value == 'cash'){
                            // echo print_r($payments_types);die();
                            // $amount_p = $val['amount'];
                        // }
                    // if($value == 'cash'){
                        // $amount_p += $val['amount'];
                    // }
                         $print_str .= append_chars(substrwords('NET CASH SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($payments_types['amount']),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    // }
                    // $amount_t = $amount_p;
                // }
                         $print_str .= append_chars(substrwords('CHANGE FUND',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($change_fund['total_drops']),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                         
                         $print_str .= append_chars(substrwords('CASH DEPOSIT',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    // $t_payment += $amount_p;
                    // $print_str .= append_chars(substrwords(strtoupper($value),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($amount_p),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";
                    $t = $payments_types['amount'] + $change_fund['total_drops'];
                     $print_str .= append_chars(substrwords('TOTAL CASH IN DRAWER',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($t),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                     $print_str .= append_chars(substrwords('ADD: RECEIVABLE PAYMENT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                     $print_str .= append_chars(substrwords('ADD: EXPENSE RETURN',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                     $print_str .= append_chars(substrwords('LESS: TOTAL EXPENSE',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                     $print_str .= append_chars(substrwords('PICK-UPS',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($t),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                
                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";

                    $print_str .= append_chars(substrwords('OVER/SHORT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                
                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";
            
                     $print_str .= append_chars(substrwords('CUSTOMER COUNT',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";
                    $reg_guest = 0;
                    $print_str .= append_chars(substrwords(' HEADCOUNT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars($trans['all_guest'],"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    $reg_guest = $trans['all_guest'] - $trans_discounts['sales_disc_count'];
                    $print_str .= append_chars(substrwords(' REGULAR COUNT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars($reg_guest,"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    $print_str .= append_chars(substrwords(' SC COUNT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars($trans_discounts['sales_disc_count'],"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";

                    $print_str .= append_chars(substrwords(' REGISTER AUDIT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars("","left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n";
                $subcats = $trans_menus['sub_cats'];
                $qty = 0;
                $total = 0;
                foreach ($subcats as $id => $val) {
                    // $print_str .= append_chars(substrwords('NO. OF ITEMS SOLD',20,""),"right",PAPER_TOTAL_COL_1," ")
                               // .append_chars($val['qty'],"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $qty += $val['qty'];
                    $total += $val['amount'];
                 }
                  $print_str .= append_chars(substrwords('NO. OF ITEMS SOLD',20,""),"right",PAPER_TOTAL_COL_1," ")
                               .append_chars($qty,"left",PAPER_RD_COL_3_3," ")."\r\n";
                            // echo print_r($trans['types']);die();
                // $types = $trans['types'];
                
                 $print_str .= append_chars(substrwords('NO. OF TRANSACTIONS',20,""),"right",PAPER_TOTAL_COL_1," ")
                               .append_chars($trans['trans_count'],"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars(substrwords('NO. OF PREV. VOID',20,""),"right",PAPER_TOTAL_COL_1," ")
                               .append_chars(0,"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars(substrwords('NO. OF ITEMS VOIDED',20,""),"right",PAPER_TOTAL_COL_1," ")
                               .append_chars(0,"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars(substrwords('NO. OF ITEMS RETURNED',20,""),"right",PAPER_TOTAL_COL_1," ")
                               .append_chars(0,"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars(substrwords('',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= append_chars(substrwords('DISCOUNT BREAKDOWN',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";  

                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";
                
                $types = $trans_discounts['types'];
                $qty = 0;
                // $print_str .= append_chars(substrwords('Discounts:',18,""),"right",18," ").align_center(null,5," ")
                              // .append_chars(null,"left",13," ")."\r\n";
                // $get_all_receipt_disc = $this->cashier_model->get_active_receipt_discounts();
                // foreach ($get_all_receipt_disc as $all_disc_key => $all_disc_val) {
                   foreach ($types as $code => $val) {
                        // if($val['name'] == $all_disc_val->disc_name){
                            $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
                                          .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                        // }
                            $qty += $val['qty'];
                    }
                // }
                
                $print_str .= PAPER_LINE_SINGLE."\r\n\r\n"; 
                $print_str .= append_chars(substrwords('Total',20,""),"right",PAPER_TOTAL_COL_1," ")
                              .append_chars(numInt($discounts),"left",13," ")."\r\n";
                // $print_str .= append_chars(substrwords('VAT EXEMPT',18,""),"right",23," ")
                                         // .append_chars(numInt($less_vat),"left",13," ")."\r\n";
                $print_str .= "\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n\r\n"; 
            if($asJson == false){
                $this->manager_model->add_event_logs($user['id'],"Cashier Reading","View");    
            }else{
                $this->manager_model->add_event_logs($user['id'],"Cashier Reading","Print");                    
            }
            if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else if(PRINT_VERSION && PRINT_VERSION == 'V3' && $asJson){
                echo $this->html_print($print_str);
            }else{
                $this->do_print($print_str,$asJson);
            }
            // $this->do_print($print_str,$asJson);
        }

        public function register_sales_rep($asJson=false){
            ////hapchan
            ini_set('memory_limit', '-1');
            set_time_limit(3600);
            
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            // echo print_r($post['shift_id']);die();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            // var_dump($trans['net']); die();
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
            $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
            $tax_disc = $trans_discounts['tax_disc_total'];
            $no_tax_disc = $trans_discounts['no_tax_disc_total'];
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
            $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
            $payments = $this->payment_sales($sales['settled']['ids'],$curr);
            $change_fund =    $this->shift_entries($post['shift_id']);
            // echo print_r($trans_discounts['sales_disc_count']);die();
            // $cashout_details = $this->cashier_model->get_cashout_details($post['shift_id']);
            $gross = $trans_menus['gross'];
            
            $net = $trans['net'];
            $void = $trans['void'];
            $cancelled = $trans['cancel_amount'];
            $charges = $trans_charges['total'];
            $discounts = $trans_discounts['total'];
            $local_tax = $trans_local_tax['total'];
            // echo $gross.' - '.$charges.' - '.$discounts.' - '.$net; die();
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            // $less_vat = $trans_discounts['vat_exempt_total'];

            // echo $gross.'+'.$charges.'+'.$local_tax.' - '.$discounts.' - '.$net;
            // die();

            if($less_vat < 0)
                $less_vat = 0;
            // var_dump($less_vat);

            //para mag tugmam yun payments and netsale
            // $net_sales2 = $gross + $charges - $discounts - $less_vat;
            // $diffs = $net_sales2 - $payments['total'];
            // if($diffs < 1){
            //     $less_vat = $less_vat + $diffs;
            // }
            

            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;

            $title_name = "SYSTEM SALES REPORT";
            if($post['title'] != "")
                $title_name = $post['title'];

            // $print_str .= align_center($title_name,PAPER_WIDTH," ")."\r\n";
            // $print_str .= align_center("TERMINAL ".$post['terminal'],PAPER_WIDTH," ")."\r\n";
            // $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            // $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            // $print_str .= PAPER_LINE."\r\n";
            // $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),PAPER_WIDTH," ")."\r\n";
            // if($post['employee'] != "All")
            //     $print_str .= align_center($post['employee'],PAPER_WIDTH," ")."\r\n";
            // $print_str .= PAPER_LINE."\r\n";

            $loc_txt = numInt(($local_tax));
            $net_no_adds = $net-($charges+$local_tax);
            // $nontaxable = $no_tax;
            //binago 9/25/2018 for zreading adjustment of vat exempt equal to the receipt vat exempt
            $nontaxable = $no_tax - $no_tax_disc;
            // echo $gross.' - '.$less_vat.' - '.$nontaxable.' - '.$zero_rated; die();
            // $taxable = ($gross - $less_vat - $nontaxable - $zero_rated) / 1.12;
            // 1.12; binago din para sa adjustment of vat exempt equal to the receipt vat exempt
            // $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
            $taxable =   ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12; //change computation conflict for zero rated 10 17 2018
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
            $add_gt = $taxable+$nontaxable+$zero_rated;
            $nsss = $taxable +  $nontaxable +  $zero_rated;


            #CONTROL

                // $print_str .= append_chars(substrwords('CASH :',18,""),"right",PAPER_TOTAL_COL_1," ")
                //                          .append_chars(iSetObj($trans['first_ref'],'trans_ref'),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";

                // $print_str .= append_chars(substrwords('TRANS #',18,""),"right",8," ").align_center('(START)',15," ")
                //                  .append_chars(iSetObj($trans['first_ref'],'trans_ref'),"left",PAPER_RD_COL_3_3," ")."\r\n";
                // $print_str .= append_chars(substrwords('',18,""),"right",8," ").align_center('(END)',15," ")
                //                  .append_chars(iSetObj($trans['last_ref'],'trans_ref'),"left",PAPER_RD_COL_3_3," ")."\r\n\r\n";

                // $print_str .= PAPER_LINE_SINGLE."\r\n";

             #PREVIOUS
                // echo $post['from']; die();
                //get olds payments
                $select1 = 'sum(trans_sales_payments.to_pay) as tpay, sum(trans_sales_payments.amount) as tamount, payment_type';
                $select2 = 'sum(trans_sales_payments.amount) as tamount, sum(trans_sales_payments.to_pay) as tpay, payment_type';
                $pjoin['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_payments.sales_id');

                $this->site_model->db= $this->load->database('main', TRUE);
                $pargs["amount >= to_pay"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs["trans_sales.type_id"] = 10;
                $pargs["trans_sales.inactive"] = '0';
                $pargs["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs['trans_sales.datetime <= '] = $post['from'];

                $pargs2["amount < to_pay"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs2["trans_sales.type_id"] = 10;
                $pargs2["trans_sales.inactive"] = '0';
                $pargs2["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $pargs2['trans_sales.datetime <= '] = $post['from'];
                $pgroup = 'trans_sales_payments.payment_type';
                $old_payment_exact = $this->site_model->get_tbl('trans_sales_payments',$pargs,array(),$pjoin,true,$select1,$pgroup);
                $old_payment_not_ex = $this->site_model->get_tbl('trans_sales_payments',$pargs2,array(),$pjoin,true,$select2,$pgroup);

                //get olds charges
                $selectc = 'sum(amount) as tcharges';
                $cjoin['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_charges.sales_id');
                $cargs["trans_sales.type_id"] = 10;
                $cargs["trans_sales.inactive"] = '0';
                $cargs["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $cargs['trans_sales.datetime <= '] = $post['from'];
                $get_old_charges = $this->site_model->get_tbl('trans_sales_charges',$cargs,array(),$cjoin,true,$selectc);

                $old_charges = 0;
                $old_net_sales = 0;
                $old_total_payment = 0;
                foreach ($get_old_charges as $ch => $val) {
                    $old_charges += $val->tcharges;
                }


                //get olds discounts
                $selectd = 'sum(amount) as tdisc, disc_code';
                $djoin['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_discounts.sales_id');
                $dargs["trans_sales.type_id"] = 10;
                $dargs["trans_sales.inactive"] = '0';
                $dargs["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
                $dargs['trans_sales.datetime <= '] = $post['from'];
                $dgroup = 'trans_sales_discounts.disc_id';
                $get_old_disc = $this->site_model->get_tbl('trans_sales_discounts',$dargs,array(),$djoin,true,$selectd,$dgroup);

                $old_sc_disc = 0;
                $old_line_disc = 0;
                foreach ($get_old_disc as $dc => $val) {
                    $amount = $val->tdisc;
                    if($val->disc_code == 'SNDISC' || $val->disc_code == 'PWDISC'){
                        $old_sc_disc += $amount;
                    }else{
                        $old_line_disc += $amount;
                    }
                    
                }

                // echo $this->site_model->db->last_query(); die();

                $this->site_model->db= $this->load->database('default', TRUE);


                // $print_str .= append_chars(substrwords('PREVIOUS GRAND TOTALS:',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         // .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";


                #PAYMENTS
                $payments_array = array();
                $ptype = array('cash','credit','gc','debit','foodpanda','alipay','wechat');
                $old_forfeited_gc = 0;

                foreach ($ptype as $key => $value) {
                    $amount_p = 0;
                    foreach ($old_payment_exact as $kkk => $val) {
                        if($value == $val->payment_type){
                            $amount_p += $val->tpay;
                            if($value == 'gc'){
                                $old_forfeited_gc = $val->tamount - $val->tpay;
                                $amount_p += $old_forfeited_gc;
                            }
                        }

                    }
                    foreach ($old_payment_not_ex as $kkk => $val) {
                        if($value == $val->payment_type){
                            $amount_p += $val->tamount;
                        }
                    }
                    $old_total_payment += $amount_p;
                    // $print_str .= append_chars(substrwords(strtoupper($value),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($amount_p),"left",PAPER_RD_COL_3_3," ")."\r\n";


                    $payments_array[$value] = array('amount'=>$amount_p);
                }

                // $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  // .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                // $print_str .= append_chars(substrwords(strtoupper('FORFEITED GC'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars('('.numInt($old_forfeited_gc).")","left",PAPER_RD_COL_3_3," ")."\r\n";
                // $print_str .= append_chars(substrwords(strtoupper('SERVICE CHARGE'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars('('.numInt($old_charges).')',"left",PAPER_RD_COL_3_3," ")."\r\n";

                // $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  // .append_chars('-------------',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $old_net_sales = $old_total_payment - $old_charges; 
                // $print_str .= append_chars(substrwords(strtoupper('OLD NET SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($old_net_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                // $print_str .= PAPER_LINE_SINGLE."\r\n";


                // $print_str .= append_chars(substrwords(strtoupper('ADD: LINE DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($old_line_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";
                // $print_str .= append_chars(substrwords(strtoupper('SC DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($old_sc_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";

                // $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  // .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $old_gross_sales = $old_net_sales + $old_sc_disc + $old_line_disc;
                // $print_str .= append_chars(substrwords(strtoupper('OLD GROSS SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($old_gross_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                // $print_str .= PAPER_LINE_SINGLE."\r\n\r\n";


                // $print_str .= append_chars(substrwords('OLD SERVICE CHARGE',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         // .append_chars(numInt($old_charges),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";

                // $print_str .= PAPER_LINE_SINGLE."\r\n";


            #TODAY

                // $print_str .= append_chars(substrwords('TODAY REGISTER TOTAL SALES:',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         // .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";


                #PAYMENTS
                $payments_types = $payments['types'];
                $payments_total = $payments['total'];
                $pay_qty = 0;
                $t_payment = 0;
                $forfeited_gc = 0;
                $ptype = array('cash','credit','gc','debit','foodpanda','alipay','wechat','redeemed points','cheque','on account','claimed deposit');

                foreach ($ptype as $key => $value) {
                    $amount_p = 0;
                    foreach ($payments_types as $code => $val) {
                        if($value == $code){
                            $amount_p += $val['amount'];
                        }
                    }
                    if($value == 'gc'){
                        if($payments['gc_excess']){
                            $amount_p += $payments['gc_excess'];
                            $forfeited_gc += $payments['gc_excess'];
                        }
                    }
                    $t_payment += $amount_p;
                    $print_str .= append_chars(substrwords(strtoupper($value),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($amount_p),"left",PAPER_RD_COL_3_3," ")."\r\n";


                    if(isset($payments_array[$value])){
                        $row = $payments_array[$value];
                        $row['amount'] += $amount_p;
                        $payments_array[$value] = $row;
                    }else{
                        $payments_array[$value] = array('amount'=>$amount_p);
                    }
                }

                $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= append_chars(substrwords(strtoupper('FORFEITED GC'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars('('.numInt($forfeited_gc).")","left",PAPER_RD_COL_3_3," ")."\r\n";
               $t_gross_receipt = $t_payment  - $forfeited_gc; 
                $print_str .= append_chars(substrwords(strtoupper('TOTAL GROSS RECEIPTS'),20,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($t_gross_receipt),"left",PAPER_RD_COL_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('LESS:SERVICE CHARGE'),20,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars('('.numInt($charges).')',"left",PAPER_RD_COL_3_3," ")."\r\n";
                $reg_net_sales = $t_gross_receipt - $charges;
                // $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  // .append_chars('-------------',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= PAPER_LINE_SINGLE."\r\n";
                $n_sales = $t_payment - $charges - $forfeited_gc; 
                // $print_str .= append_chars(substrwords(strtoupper('NET SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($n_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                // $print_str .= PAPER_LINE_SINGLE."\r\n";

                $types = $trans_discounts['types'];
                $sc_disc = 0;
                $line_disc = 0;
                foreach ($types as $code => $val) {
                    $amount = $val['amount'];
                    if($code == 'SNDISC' || $code == 'PWDISC'){
                        $sc_disc += $amount;
                    }else{
                        $line_disc += $amount;
                    }
                    
                }

                $print_str .= append_chars(substrwords(strtoupper('REGISTERED NET SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($reg_net_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('ADD: LINE DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($line_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= append_chars(substrwords(strtoupper('SC DISCOUNT'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($sc_disc),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n";
                // $print_str .= append_chars('',"right",12," ").align_center('',8," ")
                                  // .append_chars('=============',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $g_sales = $n_sales + $sc_disc + $line_disc;
                $reg_gross_sales = $reg_net_sales + $line_disc + $sc_disc;
                $print_str .= append_chars(substrwords(strtoupper('REGISTERED GROSS SALES'),20,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  .append_chars(numInt($reg_gross_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n\r\n";
                // $print_str .= append_chars(substrwords(strtoupper('GROSS SALES'),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($g_sales),"left",PAPER_RD_COL_3_3," ")."\r\n";

                $vat_ = $taxable * .12;
                $total_esales = $taxable + $nontaxable + $zero_rated + $vat_;
                $print_str .= append_chars(substrwords('VATABLE SALES',23,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($taxable),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT-EXEMPT',23,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($nontaxable),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('ZERO RATED SALES',23,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($zero_rated),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT AMOUNT',23,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($vat_),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('TOTAL E-SALES',23,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($total_esales),"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n\r\n";
                                         // .append_chars(numInt($nontaxable-$zero_rated),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= append_chars(substrwords('TODAY SERVICE CHARGE',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         // .append_chars(numInt($charges),"left",PAPER_TOTAL_COL_2," ")."\r\n";


                // $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";


            #NEW

                $print_str .= append_chars(substrwords('REGISTER ACCOUNTABILITY ',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('REGISTER NO. 1 ',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $print_str .= append_chars(substrwords('NEW GRAND TOTALS:',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         // .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";


                #PAYMENTS
                $payments_types = $payments['types'];
                $payments_total = $payments['total'];
                $pay_qty = 0;


                $print_str .= append_chars(substrwords('DEPOSIT DETAILS ',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";

                $print_str .= append_chars(substrwords('CASH ',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('CREDIT CARD',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('DEBIT CARD',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('CHEQUE ',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";

                $print_str .= append_chars(substrwords('TOTAL DEPOSIT ',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";

                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";
                $ptype = array('cash');
                // foreach ($ptype as $key => $value) {
                    $amount_p = 0;
                    $amount_t = 0;
                    $t = 0;
                    $payments_types = 0;
                    if(isset($payments['types']['cash'])){
                        $payments_types = $payments['types']['cash'];
                    }
                    // $amount_p = 0;
                    // foreach ($payments_types as $code => $val) {
                        // if($value == 'cash'){
                            // echo print_r($payments_types);die();
                            // $amount_p = $val['amount'];
                        // }
                    // if($value == 'cash'){
                        // $amount_p += $val['amount'];
                    // }
                         $print_str .= append_chars(substrwords('NET CASH SALES',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($payments_types['amount']),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    // }
                    // $amount_t = $amount_p;
                // }
                         $print_str .= append_chars(substrwords('CHANGE FUND',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($change_fund['total_drops']),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                         
                         $print_str .= append_chars(substrwords('CASH DEPOSIT',18,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    // $t_payment += $amount_p;
                    // $print_str .= append_chars(substrwords(strtoupper($value),18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                                  // .append_chars(numInt($amount_p),"left",PAPER_RD_COL_3_3," ")."\r\n";
                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";
                    $t = $payments_types['amount'] + $change_fund['total_drops'];
                     $print_str .= append_chars(substrwords('TOTAL CASH IN DRAWER',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($t),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                     $print_str .= append_chars(substrwords('ADD: RECEIVABLE PAYMENT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                     $print_str .= append_chars(substrwords('ADD: EXPENSE RETURN',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                     $print_str .= append_chars(substrwords('LESS: TOTAL EXPENSE',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                     $print_str .= append_chars(substrwords('PICK-UPS',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt($t),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                
                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";

                    $print_str .= append_chars(substrwords('OVER/SHORT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars(numInt(0),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                
                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";
            
                     $print_str .= append_chars(substrwords('CUSTOMER COUNT',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";
                    $reg_guest = 0;
                    $print_str .= append_chars(substrwords(' HEADCOUNT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars($trans['all_guest'],"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    $reg_guest = $trans['all_guest'] - $trans_discounts['sales_disc_count'];
                    $print_str .= append_chars(substrwords(' REGULAR COUNT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars($reg_guest,"left",PAPER_TOTAL_COL_2," ")."\r\n";
                    $print_str .= append_chars(substrwords(' SC COUNT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars($trans_discounts['sales_disc_count'],"left",PAPER_TOTAL_COL_2," ")."\r\n\r\n";

                    $print_str .= append_chars(substrwords(' REGISTER AUDIT',20,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars("","left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n";
                $subcats = $trans_menus['sub_cats'];
                $qty = 0;
                $total = 0;
                foreach ($subcats as $id => $val) {
                    // $print_str .= append_chars(substrwords('NO. OF ITEMS SOLD',20,""),"right",PAPER_TOTAL_COL_1," ")
                               // .append_chars($val['qty'],"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $qty += $val['qty'];
                    $total += $val['amount'];
                 }
                  $print_str .= append_chars(substrwords('NO. OF ITEMS SOLD',20,""),"right",PAPER_TOTAL_COL_1," ")
                               .append_chars($qty,"left",PAPER_RD_COL_3_3," ")."\r\n";
                            // echo print_r($trans['types']);die();
                // $types = $trans['types'];
                
                 $print_str .= append_chars(substrwords('NO. OF TRANSACTIONS',20,""),"right",PAPER_TOTAL_COL_1," ")
                               .append_chars($trans['trans_count'],"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars(substrwords('NO. OF PREV. VOID',20,""),"right",PAPER_TOTAL_COL_1," ")
                               .append_chars(0,"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars(substrwords('NO. OF ITEMS VOIDED',20,""),"right",PAPER_TOTAL_COL_1," ")
                               .append_chars(0,"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars(substrwords('NO. OF ITEMS RETURNED',20,""),"right",PAPER_TOTAL_COL_1," ")
                               .append_chars(0,"left",PAPER_RD_COL_3_3," ")."\r\n";

                $print_str .= append_chars(substrwords('',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";
                $print_str .= append_chars(substrwords('',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";

                $print_str .= append_chars(substrwords('DISCOUNT BREAKDOWN',30,""),"right",PAPER_TOTAL_COL_1," ")
                                         .append_chars('',"left",PAPER_TOTAL_COL_2," ")."\r\n";  

                $print_str .= "\r\n".PAPER_LINE_SINGLE."\r\n";
                
                $types = $trans_discounts['types'];
                $qty = 0;
                // $print_str .= append_chars(substrwords('Discounts:',18,""),"right",18," ").align_center(null,5," ")
                              // .append_chars(null,"left",13," ")."\r\n";
                // $get_all_receipt_disc = $this->cashier_model->get_active_receipt_discounts();
                // foreach ($get_all_receipt_disc as $all_disc_key => $all_disc_val) {
                   foreach ($types as $code => $val) {
                        // if($val['name'] == $all_disc_val->disc_name){
                            $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
                                          .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                        // }
                            $qty += $val['qty'];
                    }
                // }
                
                $print_str .= PAPER_LINE_SINGLE."\r\n\r\n"; 
                $print_str .= append_chars(substrwords('Total',20,""),"right",PAPER_TOTAL_COL_1," ")
                              .append_chars(numInt($discounts),"left",13," ")."\r\n";
                // $print_str .= append_chars(substrwords('VAT EXEMPT',18,""),"right",23," ")
                                         // .append_chars(numInt($less_vat),"left",13," ")."\r\n";
                $print_str .= "\r\n";

                $print_str .= PAPER_LINE_SINGLE."\r\n\r\n"; 
            if($asJson == false){
                $this->manager_model->add_event_logs($user['id'],"Register Reading","View");    
            }else{
                $this->manager_model->add_event_logs($user['id'],"Register Reading","Print");                    
            }
            if(PRINT_VERSION && PRINT_VERSION == 'V2'){
                $this->do_print_v2($print_str,$asJson);  
            }else if(PRINT_VERSION && PRINT_VERSION == 'V3' && $asJson){
                echo $this->html_print($print_str);
            }else{
                $this->do_print($print_str,$asJson);
            }
            // $this->do_print($print_str,$asJson);
        }

    public function menu_item_sales()
    {
        $data = $this->syter->spawn('menu_item_sales');        
        $data['page_title'] = fa('fa-money')." Menu Item Sales Report";
        $data['code'] = menuItemSalesRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/prints';
        $data['use_js'] = 'menuItemSalesRepJS';
        $this->load->view('page',$data);
    }
    public function menuitem_sales_rep_gen()
    {
        
        $user = $this->session->userdata('user');
        $time = $this->site_model->get_db_now();
        $post = $this->set_post();
        $curr = $this->search_current();
        $trans = $this->trans_sales($post['args'],$curr);
        $sales = $trans['sales'];
        $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        $payments = $this->payment_sales($sales['settled']['ids'],$curr);
        $gross = $trans_menus['gross'];
        $net = $trans['net'];
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

        $cats = $trans_menus['cats'];
        $menus = $trans_menus['menus'];
        $menu_total = $trans_menus['menu_total'];
        $total_qty = $trans_menus['total_qty'];
        usort($cats, function($a, $b) {
            return $b['amount'] - $a['amount'];
        });

         // echo "<pre>", print_r($cats), "</pre>"; die();
        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Menu Name');
                        $this->make->th('Qty');
                        // $this->make->th('VAT Sales');
                        // $this->make->th('VAT');
                        $this->make->th('Total');
                        // $this->make->th('Sales (%)');
                        // $this->make->th('Cost');
                        // $this->make->th('Cost (%)');
                        // $this->make->th('Margin');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    foreach ($cats as $cat_id => $ca) {
                        if($ca['qty'] > 0){
                            // $print_str .=
                            //      append_chars($ca['name'],'right',18," ")
                            //     .append_chars(num($ca['qty']),'right',10," ")
                            //     .append_chars(num($ca['amount']).'','left',10," ")."\r\n";
                            // $print_str .= append_chars($ca['name'],"right",PAPER_RD_COL_1," ").align_center(num($ca['qty']),PAPER_RD_COL_2," ")
                            //     .append_chars(num($ca['amount']).'',"left",9," ")."\r\n";
                            // $print_str .= PAPER_LINE."\r\n";

                            $this->make->sRow(array("style"=>"background-color:yellow;"));
                                $this->make->td($ca['name']);
                                $this->make->td($ca['qty'], array("style"=>"text-align:right"));                            
                                // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                                // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                                $this->make->td(num($ca['amount']), array("style"=>"text-align:right"));                            
                            $this->make->eRow();


                            foreach ($menus as $menu_id => $res) {
                                if($ca['cat_id'] == $res['cat_id']){
                                
                                // echo "<pre>", print_r($menus), "</pre>"; die();
                                // $print_str .=
                                    // append_chars($res['name'],'right',PAPER_RD_COL_1," ")
                                    // .append_chars(num($res['qty']),'right',PAPER_RD_COL_2," ")
                                    // .append_chars(num($res['amount']),'left',9," ")
                                    // ."\r\n";

                                    $this->make->sRow();
                                        $this->make->td('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;['.$res['code'].']'.$res['name']);
                                        $this->make->td($res['qty'], array("style"=>"text-align:right"));                            
                                        // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                                        // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                                        $this->make->td(num($res['amount']), array("style"=>"text-align:right"));                            
                                    $this->make->eRow();

                                // $print_str .=
                                //     append_chars(null,'right',PAPER_RD_COL_1," ")
                                //     .append_chars(num($res['amount']),'right',PAPER_RD_COL_2," ")
                                //     // .append_chars(num( ($res['amount'] / $menu_total) * 100).'%','left',9," ")
                                //     ."\r\n";

                                    foreach ($trans_menus['mods'] as $mnu_id => $mods) {
                                        if($mnu_id == $res['menu_id']){
                                            foreach ($mods as $mod_id => $val) {
                                                // $print_str .= append_chars('*'.$val['name'],"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
                                                //        .append_chars(numInt($val['total_amt']),"left",PAPER_RD_COL_3_3," ")."\r\n";

                                                $where = array('mod_id'=>$mod_id);
                                                $dd = $this->site_model->get_details($where,'modifiers');

                                                $this->make->sRow();
                                                    $this->make->td('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*['.$dd[0]->mod_code.']'.$val['name']);
                                                    $this->make->td($val['qty'], array("style"=>"text-align:right"));                            
                                                    // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                                                    // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                                                    $this->make->td(num($val['total_amt']), array("style"=>"text-align:right"));                            
                                                $this->make->eRow();

                                                foreach($trans_menus['submods'] as $m_id => $mval){

                                                    if($mod_id == $m_id){

                                                        foreach ($mval as $sub_id => $sval) {
                                                            $this->make->sRow();
                                                                $this->make->td('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*['.$dd[0]->mod_code.']'.$sval['name']);
                                                                $this->make->td($sval['qty'], array("style"=>"text-align:right"));                            
                                                                // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                                                                // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                                                                $this->make->td(num($sval['total_amt']), array("style"=>"text-align:right"));                            
                                                            $this->make->eRow();
                                                    //         $this->make->sRow();
                                                    //             $this->make->td('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*['.$sval[0]->mod_code.']'.$sval['name']);
                                                    //             $this->make->td($sval['qty'], array("style"=>"text-align:right"));                            
                                                    //             // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                                                    //             // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                                                    //             $this->make->td(num(123), array("style"=>"text-align:right"));                            
                                                    //         $this->make->eRow();
                                                        }

                                                    }

                                                }

                                            }
                                        } 
                                     }
                                }
                            }
                        // $print_str .= PAPER_LINE."\r\n";
                        }
                    }
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        $this->make->sDiv();
            $this->make->sTable(array("id"=>"mains-tbl", 'class'=>'table reportTBL sortable'));
                $subcats = $trans_menus['sub_cats'];
                $qty = 0;
                $total = 0;
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('SUBCATEGORIES', array("style"=>"text-align:center","colspan"=>2));
                        // $this->make->th('Qty');
                        // // $this->make->th('VAT Sales');
                        // // $this->make->th('VAT');
                        // $this->make->th('Total');
                        // $this->make->th('Sales (%)');
                        // $this->make->th('Cost');
                        // $this->make->th('Cost (%)');
                        // $this->make->th('Margin');
                    $this->make->eRow();
                $this->make->eTableHead();
                foreach ($subcats as $id => $val) {
                    // $print_str .= append_chars($val['name'],"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
                    //            .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                    $this->make->sRow();
                        $this->make->td($val['name']);
                        $this->make->td(numInt($val['amount']), array("style"=>"text-align:right"));                            
                        // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                        // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                        // $this->make->td(num($ca['amount']), array("style"=>"text-align:right"));                            
                    $this->make->eRow();
                    $qty += $val['qty'];
                    $total += $val['amount'];
                }
            $this->make->eTable();
        $this->make->eDiv();


        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = $trans;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }

    public function menuitem_sales_rep_gen_excel(){
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $dates = explode(" to ",$_GET['calendar_range']);
        $ddate = sql2Date($dates[0]);
        $filename = 'Menu Item Sales Report '.$ddate;
        $rc=1;
        #GET VALUES
            start_load(0);
            $post = $this->set_post($_GET['calendar_range']);
            $curr = false;
            update_load(10);
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            update_load(15);
            $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
            update_load(20);
            $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            update_load(25);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
            update_load(30);
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
            update_load(35);
            $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
            update_load(40);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
            update_load(45);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
            update_load(50);
            $payments = $this->payment_sales($sales['settled']['ids'],$curr);
            update_load(53);
            $gross = $trans_menus['gross']; 
            $net = $trans['net'];
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
            update_load(55);
            $cats = $trans_menus['cats'];                 
            $menus = $trans_menus['menus'];
            $menu_total = $trans_menus['menu_total'];
            $total_qty = $trans_menus['total_qty'];
            update_load(60);
            usort($cats, function($a, $b) {
                return $b['amount'] - $a['amount'];
            });
            update_load(80);
        $styleHeaderCell = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '3C8DBC')
            ),
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 14,
                'color' => array('rgb' => 'FFFFFF'),
            )
        );
        $styleNum = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
        );
        $styleTxt = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
        );
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 16,
            )
        );
        $styleNumC = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'f5f755')
            ),
        );
        $styleTxtC = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'f5f755')
            ),
        );
        
        $headers = array('Menu Name','Quantity','Total');
        $sheet->getColumnDimension('A')->setWidth(60);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(30);
        // $sheet->getColumnDimension('D')->setWidth(20);
        // $sheet->getColumnDimension('E')->setWidth(20);
        // $sheet->getColumnDimension('F')->setWidth(20);
        // $sheet->getColumnDimension('G')->setWidth(20);
        // $sheet->getColumnDimension('H')->setWidth(20);
        // $sheet->getColumnDimension('I')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Menu Item Sales Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;
        
        $dates = explode(" to ",$_GET['calendar_range']);
        $from = sql2DateTime($dates[0]);
        $to = sql2DateTime($dates[1]);
        $sheet->getCell('A'.$rc)->setValue('Date From: '.$from);
        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $rc++;

        $sheet->getCell('A'.$rc)->setValue('Date To: '.$to);
        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $rc++;
        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }

        $rc++;

        foreach ($cats as $cat_id => $ca) {
            if($ca['qty'] > 0){
                $sheet->getCell('A'.$rc)->setValue($ca['name']);
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxtC);
                $sheet->getCell('B'.$rc)->setValue($ca['qty']);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleNumC);
                $sheet->getCell('C'.$rc)->setValue($ca['amount']);
                $sheet->getStyle('C'.$rc)->applyFromArray($styleNumC);
                $rc++;
                foreach ($menus as $menu_id => $res) {
                    if($ca['cat_id'] == $res['cat_id']){
                        $sheet->getCell('A'.$rc)->setValue('        ['.$res['code'].']'.$res['name']);
                        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                        $sheet->getCell('B'.$rc)->setValue($res['qty']);
                        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
                        $sheet->getCell('C'.$rc)->setValue($res['amount']);
                        $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                        $rc++;

                        foreach ($trans_menus['mods'] as $mnu_id => $mods) {
                            if($mnu_id == $res['menu_id']){
                                foreach ($mods as $mod_id => $val) {

                                    $where = array('mod_id'=>$mod_id);
                                    $dd = $this->site_model->get_details($where,'modifiers');

                                    $sheet->getCell('A'.$rc)->setValue('               *['.$dd[0]->mod_code.']'.$val['name']);
                                    // ['.$dd[0]->mod_code.']'.$val['name']);
                                    $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                                    $sheet->getCell('B'.$rc)->setValue($val['qty']);
                                    $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
                                    $sheet->getCell('C'.$rc)->setValue($val['total_amt']);
                                    $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                                    $rc++;

                                    foreach($trans_menus['submods'] as $m_id => $mval){

                                        if($mod_id == $m_id){

                                            foreach ($mval as $sub_id => $sval) {
                                                $sheet->getCell('A'.$rc)->setValue('               *['.$dd[0]->mod_code.']'.$sval['name']);
                                                // ['.$dd[0]->mod_code.']'.$val['name']);
                                                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                                                $sheet->getCell('B'.$rc)->setValue($sval['qty']);
                                                $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
                                                $sheet->getCell('C'.$rc)->setValue($sval['total_amt']);
                                                $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                                                $rc++;
                                            }

                                        }

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $rc++;
        $rc++;

        $subcats = $trans_menus['sub_cats'];
        $qty = 0;
        $total = 0;
        $sheet->mergeCells('A'.$rc.':B'.$rc);
        $sheet->getCell('A'.$rc)->setValue('SUBCATEGORIES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleHeaderCell);
        $rc++;
        foreach ($subcats as $id => $val) {
            $sheet->getCell('A'.$rc)->setValue($val['name']);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue($val['amount']);
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $rc++;
        }
        // foreach ($menus as $res) {
        //         $sheet->getCell('A'.$rc)->setValue($res['code']);
        //         $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //         $sheet->getCell('B'.$rc)->setValue($res['name']);
        //         $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
        //         $sheet->getCell('C'.$rc)->setValue($res['sell_price']);
        //         $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('D'.$rc)->setValue($res['qty']);     
        //         $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('E'.$rc)->setValue(num( ($res['qty'] / $total_qty) * 100 ).'%');     
        //         $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('F'.$rc)->setValue(num($res['amount']));     
        //         $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('G'.$rc)->setValue(num( ($res['amount'] / $menu_total) * 100 ).'%');
        //         $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('H'.$rc)->setValue($res['cost_price']);
        //         $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('I'.$rc)->setValue($res['cost_price'] * $res['qty']);
        //         $sheet->getStyle('I'.$rc)->applyFromArray($styleNum);

        //     $rc++;
        // } 
        $rc++;
        $net_no_adds = $net-$charges-$local_tax;
        $sheet->getCell('A'.$rc)->setValue('TOTAL SALES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->getCell('B'.$rc)->setValue($net);
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $sheet->getStyle('A'.$rc.':B'.$rc)->applyFromArray(array('font'=>array('bold' => true,'size'=>13)));

        $rc++;
        $txt = $charges;
        if($charges > 0)
            $txt = "(".$charges.")";
        $net_no_adds = $net-$charges-$local_tax;
        $sheet->getCell('A'.$rc)->setValue('Charges');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->getCell('B'.$rc)->setValue($txt);
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $sheet->getStyle('A'.$rc.':B'.$rc)->applyFromArray(array('font'=>array('bold' => true,'size'=>13)));

        $rc++;
        $txt = $local_tax;
        if($local_tax > 0)
            $txt = "(".$local_tax.")";
        $sheet->getCell('A'.$rc)->setValue('Local Tax');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->getCell('B'.$rc)->setValue($txt);
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $sheet->getStyle('A'.$rc.':B'.$rc)->applyFromArray(array('font'=>array('bold' => true,'size'=>13)));

        $rc++;
        $sheet->getCell('A'.$rc)->setValue('Discounts');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->getCell('B'.$rc)->setValue($discounts);
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $sheet->getStyle('A'.$rc.':B'.$rc)->applyFromArray(array('font'=>array('bold' => true,'size'=>13)));

        $rc++;
        $sheet->getCell('A'.$rc)->setValue('LESS VAT');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->getCell('B'.$rc)->setValue($less_vat);
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $sheet->getStyle('A'.$rc.':B'.$rc)->applyFromArray(array('font'=>array('bold' => true,'size'=>13)));

        $rc++;
        $sheet->getCell('A'.$rc)->setValue('GROSS SALES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->getCell('B'.$rc)->setValue($gross);
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $sheet->getStyle('A'.$rc.':B'.$rc)->applyFromArray(array('font'=>array('bold' => true,'size'=>13)));


        // $mods_total = $trans_menus['mods_total'];
        // if($mods_total > 0){
        //     $sheet->getCell('A'.$rc)->setValue('Total Modifiers Sale: ');
        //     $sheet->getCell('B'.$rc)->setValue(num($mods_total));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        //     $rc++;
        // }
        // $net_no_adds = $net-$charges-$local_tax;
        // $sheet->getCell('A'.$rc)->setValue('Total Sales: ');
        // $sheet->getCell('B'.$rc)->setValue(num($net));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $txt = numInt(($charges));
        // if($charges > 0)
        //     $txt = "(".numInt(($charges)).")";
        // $sheet->getCell('A'.$rc)->setValue('Total Charges: ');
        // $sheet->getCell('B'.$rc)->setValue($txt);
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $txt = numInt(($local_tax));
        // if($local_tax > 0)
        //     $txt = "(".numInt(($local_tax)).")";
        // $sheet->getCell('A'.$rc)->setValue('Total Local Tax: ');
        // $sheet->getCell('B'.$rc)->setValue($txt);
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('Total Discounts: ');
        // $sheet->getCell('B'.$rc)->setValue(num($discounts));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('Total VAT EXEMPT: ');
        // $sheet->getCell('B'.$rc)->setValue(num($less_vat));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('Total Gross Sales: ');
        // $sheet->getCell('B'.$rc)->setValue(num($gross));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        
        update_load(100);
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }
    public function menuitem_sales_rep_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Menu Item Sales Report');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');

        // set default header data
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $set->branch_name, $set->address);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        // $menu_cat_id = $_GET['menu_cat_id'];        
        // $daterange = $_GET['calendar_range'];        
        // $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        // $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        // $trans = $this->menu_model->get_cat_sales_rep($from, $to, $menu_cat_id);
        // $trans_ret = $this->menu_model->get_cat_sales_rep_retail($from, $to, "");
        // $trans_mod = $this->menu_model->get_mod_cat_sales_rep($from, $to, $menu_cat_id);
        // $trans_payment = $this->menu_model->get_payment_date($from, $to);
        // echo $this->db->last_query();die();     

        $post = $this->set_post($_GET['calendar_range']);
        $curr = false;
        update_load(10);
        $trans = $this->trans_sales($post['args'],$curr);
        $sales = $trans['sales'];
        update_load(15);
        $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        update_load(20);
        $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        update_load(25);
        $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        update_load(30);
        $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        update_load(35);
        $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        update_load(40);
        $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        update_load(45);
        $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        update_load(50);
        $payments = $this->payment_sales($sales['settled']['ids'],$curr);
        update_load(53);
        $gross = $trans_menus['gross']; 
        $net = $trans['net'];
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
        update_load(55);
        $cats = $trans_menus['cats'];                 
        $menus = $trans_menus['menus'];
        $menu_total = $trans_menus['menu_total'];
        $total_qty = $trans_menus['total_qty'];
        update_load(60);
        usort($cats, function($a, $b) {
            return $b['amount'] - $a['amount'];
        });



        $pdf->Write(0, 'Menu Item Sales Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        // $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $_GET['calendar_range'], '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(120);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        // $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        // $pdf->setX(120);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(180, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();

        // -----------------------------------------------------------------------------
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(100, 0, 'Menu Name', 'B', 0, 'L');        
        $pdf->Cell(40, 0, 'Quantity', 'B', 0, 'R');        
        // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(40, 0, 'Total', 'B', 0, 'R');        
        // $pdf->Cell(32, 0, 'Sales (%)', 'B', 0, 'R');        
        // $pdf->Cell(32, 0, 'Cost', 'B', 0, 'R');        
        // $pdf->Cell(32, 0, 'Cost (%)', 'B', 0, 'R');        
        // $pdf->Cell(32, 0, 'Margin', 'B', 0, 'R'); 
        $pdf->ln();        

        $pdf->SetFont('helvetica', '', 9);       
        foreach ($cats as $cat_id => $ca) {
            if($ca['qty'] > 0){
                // $sheet->getCell('A'.$rc)->setValue($ca['name']);
                // $sheet->getStyle('A'.$rc)->applyFromArray($styleTxtC);
                // $sheet->getCell('B'.$rc)->setValue($ca['qty']);
                // $sheet->getStyle('B'.$rc)->applyFromArray($styleNumC);
                // $sheet->getCell('C'.$rc)->setValue($ca['amount']);
                // $sheet->getStyle('C'.$rc)->applyFromArray($styleNumC);
                // $rc++;

                $pdf->Cell(100, 0, $ca['name'], '', 0, 'L');
                $pdf->Cell(40, 0, $ca['qty'], '', 0, 'R');
                $pdf->Cell(40, 0, num($ca['amount']), '', 0, 'R');
                $pdf->ln();

                foreach ($menus as $menu_id => $res) {
                    if($ca['cat_id'] == $res['cat_id']){
                        // $sheet->getCell('A'.$rc)->setValue('        '.$res['name']);
                        // $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                        // $sheet->getCell('B'.$rc)->setValue($res['qty']);
                        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
                        // $sheet->getCell('C'.$rc)->setValue($res['amount']);
                        // $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                        // $rc++;

                        $pdf->Cell(100, 0, '        ['.$res['code'].']'.$res['name'], '', 0, 'L');
                        $pdf->Cell(40, 0, $res['qty'], '', 0, 'R');
                        $pdf->Cell(40, 0, num($res['amount']), '', 0, 'R');
                        $pdf->ln();

                        foreach ($trans_menus['mods'] as $mnu_id => $mods) {
                            if($mnu_id == $res['menu_id']){
                                foreach ($mods as $mod_id => $val) {
                                    // $sheet->getCell('A'.$rc)->setValue('               *'.$val['name']);
                                    // $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                                    // $sheet->getCell('B'.$rc)->setValue($val['qty']);
                                    // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
                                    // $sheet->getCell('C'.$rc)->setValue($val['total_amt']);
                                    // $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                                    // $rc++;

                                    $where = array('mod_id'=>$mod_id);
                                    $dd = $this->site_model->get_details($where,'modifiers');

                                    $pdf->Cell(100, 0, '               *['.$dd[0]->mod_code.']'.$val['name'], '', 0, 'L');
                                    $pdf->Cell(40, 0, $val['qty'], '', 0, 'R');
                                    $pdf->Cell(40, 0, num($val['total_amt']), '', 0, 'R');
                                    $pdf->ln();

                                    foreach($trans_menus['submods'] as $m_id => $mval){

                                        if($mod_id == $m_id){

                                            foreach ($mval as $sub_id => $sval) {
                                                $pdf->Cell(100, 0, '               *['.$dd[0]->mod_code.']'.$sval['name'], '', 0, 'L');
                                                $pdf->Cell(40, 0, $sval['qty'], '', 0, 'R');
                                                $pdf->Cell(40, 0, num($sval['total_amt']), '', 0, 'R');
                                                $pdf->ln();
                                            }

                                        }

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }  

        $pdf->ln(10);

        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(180, 0, 'SUBCATEGORIES', 'B', 0, 'L');         
        $pdf->ln(); 
        $pdf->SetFont('helvetica', '', 9);

        $subcats = $trans_menus['sub_cats'];
        $qty = 0;
        $total = 0;
        foreach ($subcats as $id => $val) {
            // $sheet->getCell('A'.$rc)->setValue($val['name']);
            // $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            // $sheet->getCell('B'.$rc)->setValue($val['amount']);
            // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            // $rc++;
            $pdf->Cell(90, 0, $val['name'], '', 0, 'L');
            $pdf->Cell(90, 0, num($val['amount']), '', 0, 'R');
            // $pdf->Cell(40, 0, num($ca['amount']), '', 0, 'R');
            $pdf->ln();             
        }

        $pdf->ln(9);  
        $pdf->SetFont('helvetica', 'B', 10);
        
        $net_no_adds = $net-$charges-$local_tax;
        $pdf->Cell(90, 0, 'TOTAL SALES', '', 0, 'L');
        $pdf->Cell(90, 0, num($net), '', 0, 'R');
        // $pdf->Cell(40, 0, num($ca['amount']), '', 0, 'R');
        $pdf->ln(); 

        $txt = num($charges);
        if($charges > 0)
            $txt = "(".$charges.")";
        $net_no_adds = $net-$charges-$local_tax;
        $pdf->Cell(90, 0, 'Charges', '', 0, 'L');
        $pdf->Cell(90, 0, $txt, '', 0, 'R');
        $pdf->ln(); 
    

        $txt = num($local_tax);
        if($local_tax > 0)
            $txt = "(".$local_tax.")";
        $pdf->Cell(90, 0, 'Local Tax', '', 0, 'L');
        $pdf->Cell(90, 0, $txt, '', 0, 'R');
        $pdf->ln(); 

        $pdf->Cell(90, 0, 'Discounts', '', 0, 'L');
        $pdf->Cell(90, 0, num($discounts), '', 0, 'R');
        $pdf->ln();

        $pdf->Cell(90, 0, 'LESS VAT', '', 0, 'L');
        $pdf->Cell(90, 0, num($less_vat), '', 0, 'R');
        $pdf->ln();

        $pdf->Cell(90, 0, 'GROSS SALES', '', 0, 'L');
        $pdf->Cell(90, 0, num($gross), '', 0, 'R');
        $pdf->ln();
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('GROSS SALES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        // $sheet->getCell('B'.$rc)->setValue($gross);
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $sheet->getStyle('A'.$rc.':B'.$rc)->applyFromArray(array('font'=>array('bold' => true,'size'=>13)));           


        // GRAND TOTAL VARIABLES
        // $tot_qty = 0;
        // $tot_vat_sales = 0;
        // $tot_vat = 0;
        // $tot_gross = 0;
        // $tot_mod_gross = 0;
        // $tot_sales_prcnt = 0;
        // $tot_cost = 0;
        // $tot_cost_prcnt = 0; 
        // $tot_margin = 0;
        // $counter = 0;
        // $progress = 0;
        // $trans_count = count($trans);
        // // echo print_r($trans);die();
        // foreach ($trans as $val) {
        //     $tot_gross += $val->gross;
        //     $tot_cost += $val->cost;
        // }
        // foreach ($trans_mod as $vv) {
        //     $tot_mod_gross += $vv->mod_gross;
        // }
        // foreach ($trans as $k => $v) {
        //     $pdf->Cell(75, 0, $v->menu_cat_name, '', 0, 'L');        
        //     $pdf->Cell(32, 0, num($v->qty), '', 0, 'R');        
        //     // $pdf->Cell(32, 0, num($v->vat_sales), '', 0, 'R');        
        //     // $pdf->Cell(32, 0, num($v->vat), '', 0, 'R');        
        //     $pdf->Cell(32, 0, num($v->gross), '', 0, 'R');        
        //     $pdf->Cell(32, 0, num($v->gross / $tot_gross * 100)."%", '', 0, 'R');        
        //     $pdf->Cell(32, 0, num($v->cost), '', 0, 'R');                    
        //     if($tot_cost != 0){
        //         $pdf->Cell(32, 0, num($v->cost / $tot_cost * 100)."%", '', 0, 'R');                        
        //     }else{
        //         $pdf->Cell(32, 0, "0.00%", '', 0, 'R');                                        
        //     }
        //     $pdf->Cell(32, 0, num($v->gross - $v->cost), '', 0, 'R');        
        //     $pdf->ln();                

        //     // Grand Total
        //     $tot_qty += $v->qty;
        //     // $tot_vat_sales += $v->vat_sales;
        //     // $tot_vat += $v->vat;
        //     // $tot_gross += $v->gross;
        //     $tot_sales_prcnt = 0;
        //     // $tot_cost += $v->cost;
        //     $tot_margin += $v->gross - $v->cost;
        //     $tot_cost_prcnt = 0;

        //     $counter++;
        //     $progress = ($counter / $trans_count) * 100;
        //     update_load(num($progress));              
        // }

        // update_load(100);

        // // update_load(100);        
        // $pdf->Cell(75, 0, "Grand Total", 'T', 0, 'L');        
        // $pdf->Cell(32, 0, num($tot_qty), 'T', 0, 'R');        
        // // $pdf->Cell(32, 0, num($tot_vat_sales), 'T', 0, 'R');        
        // // $pdf->Cell(32, 0, num($tot_vat), 'T', 0, 'R');        
        // $pdf->Cell(32, 0, num($tot_gross), 'T', 0, 'R');        
        // $pdf->Cell(32, 0, "", 'T', 0, 'R');        
        // $pdf->Cell(32, 0, num($tot_cost), 'T', 0, 'R');        
        // $pdf->Cell(32, 0, "", 'T', 0, 'R'); 
        // $pdf->Cell(32, 0, num($tot_margin), 'T', 0, 'R'); 



        // //retail
        // $tot_gross_ret = 0;
        // if(count($trans_ret) > 0){
        //     $pdf->ln(7);                
        //     $pdf->Cell(267, 0, 'Retail Items', '', 0, 'C');
        //     $pdf->ln(); 

        //     $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        //     $pdf->Cell(75, 0, 'Category', 'B', 0, 'L');        
        //     $pdf->Cell(32, 0, 'Qty', 'B', 0, 'R');        
        //     // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
        //     // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
        //     $pdf->Cell(32, 0, 'Gross', 'B', 0, 'R');        
        //     $pdf->Cell(32, 0, 'Sales (%)', 'B', 0, 'R');        
        //     $pdf->Cell(32, 0, 'Cost', 'B', 0, 'R');        
        //     $pdf->Cell(32, 0, 'Cost (%)', 'B', 0, 'R');        
        //     $pdf->Cell(32, 0, 'Margin', 'B', 0, 'R');        
        //     $pdf->ln();               

        //     $tot_qty = 0;
        //     $tot_vat_sales = 0;
        //     $tot_vat = 0;
        //     $tot_mod_gross = 0;
        //     $tot_sales_prcnt = 0;
        //     $tot_cost = 0;
        //     $tot_cost_prcnt = 0; 
        //     $tot_margin = 0;
        //     $counter = 0;
        //     $progress = 0;
        //     $trans_count = count($trans_ret);
        //     // echo print_r($trans);die();
        //     foreach ($trans_ret as $val) {
        //         $tot_gross_ret += $val->gross;
        //         // $tot_cost += $val->cost;
        //     }
        //     foreach ($trans_ret as $k => $v) {
        //         $pdf->Cell(75, 0, $v->name, '', 0, 'L');        
        //         $pdf->Cell(32, 0, num($v->qty), '', 0, 'R');        
        //         // $pdf->Cell(32, 0, num($v->vat_sales), '', 0, 'R');        
        //         // $pdf->Cell(32, 0, num($v->vat), '', 0, 'R');        
        //         $pdf->Cell(32, 0, num($v->gross), '', 0, 'R');        
        //         $pdf->Cell(32, 0, num($v->gross / $tot_gross_ret * 100)."%", '', 0, 'R');        
        //         $pdf->Cell(32, 0, num(0), '', 0, 'R');                    
        //         if($tot_cost != 0){
        //             $pdf->Cell(32, 0, num($v->cost / $tot_cost * 100)."%", '', 0, 'R');                        
        //         }else{
        //             $pdf->Cell(32, 0, "0.00%", '', 0, 'R');                                        
        //         }
        //         $pdf->Cell(32, 0, num($v->gross - 0), '', 0, 'R');        
        //         $pdf->ln();                

        //         // Grand Total
        //         $tot_qty += $v->qty;
        //         // $tot_vat_sales += $v->vat_sales;
        //         // $tot_vat += $v->vat;
        //         // $tot_gross += $v->gross;
        //         $tot_sales_prcnt = 0;
        //         // $tot_cost += $v->cost;
        //         $tot_margin += $v->gross - 0;
        //         $tot_cost_prcnt = 0;

        //         $counter++;
        //         $progress = ($counter / $trans_count) * 100;
        //         update_load(num($progress));              
        //     }

        //     update_load(100);        
        //     $pdf->Cell(75, 0, "Grand Total", 'T', 0, 'L');        
        //     $pdf->Cell(32, 0, num($tot_qty), 'T', 0, 'R');        
        //     // $pdf->Cell(32, 0, num($tot_vat_sales), 'T', 0, 'R');        
        //     // $pdf->Cell(32, 0, num($tot_vat), 'T', 0, 'R');        
        //     $pdf->Cell(32, 0, num($tot_gross_ret), 'T', 0, 'R');        
        //     $pdf->Cell(32, 0, "", 'T', 0, 'R');        
        //     $pdf->Cell(32, 0, num($tot_cost), 'T', 0, 'R');        
        //     $pdf->Cell(32, 0, "", 'T', 0, 'R'); 
        //     $pdf->Cell(32, 0, num($tot_margin), 'T', 0, 'R');
        // }


        // ///////////fpr payments
        // $this->cashier_model->db = $this->load->database('main', TRUE);
        // $args = array();
        // // if($user)
        // //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        // $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // // $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        // // $terminal = TERMINAL_ID;
        // // $args['trans_sales.terminal_id'] = $terminal;
        // // if($menu_cat_id != 0){
        // //     $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        // // }


        // // $post = $this->set_post();
        // // $curr = $this->search_current();
        // $curr = false;
        // $trans = $this->trans_sales($args,$curr);
        // // $trans = $this->trans_sales_cat($args,false);
        // $sales = $trans['sales'];

        // $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        // $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        // $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        // $tax_disc = $trans_discounts['tax_disc_total'];
        // $no_tax_disc = $trans_discounts['no_tax_disc_total'];
        // $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        // $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        // $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        // $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        // $payments = $this->payment_sales($sales['settled']['ids'],$curr);

        // $gross = $trans_menus['gross'];

        // $net = $trans['net'];
        // $void = $trans['void'];
        // $charges = $trans_charges['total'];
        // $discounts = $trans_discounts['total'];
        // $local_tax = $trans_local_tax['total'];
        // $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;

        // if($less_vat < 0)
        //     $less_vat = 0;


        // $tax = $trans_tax['total'];
        // $no_tax = $trans_no_tax['total'];
        // $zero_rated = $trans_zero_rated['total'];
        // $no_tax -= $zero_rated;

        // $loc_txt = numInt(($local_tax));
        // $net_no_adds = $net-($charges+$local_tax);
        // $nontaxable = $no_tax - $no_tax_disc;
        // $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
        // $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
        // $add_gt = $taxable+$nontaxable+$zero_rated;
        // $nsss = $taxable +  $nontaxable +  $zero_rated;

        // $vat_ = $taxable * .12;

        // $pdf->ln(7);
        // $pdf->Cell(30, 0, 'GROSS', '', 0, 'L');
        // $pdf->Cell(35, 0, num($tot_gross + $tot_mod_gross + $tot_gross_ret), '', 0, 'R');
        // $pdf->ln();
        // $pdf->Cell(30, 0, 'VAT SALES', '', 0, 'L');
        // $pdf->Cell(35, 0, num($taxable), '', 0, 'R');
        // $pdf->ln();
        // $pdf->Cell(30, 0, 'VAT', '', 0, 'L');
        // $pdf->Cell(35, 0, num($vat_), '', 0, 'R');
        // $pdf->ln();
        // $pdf->Cell(30, 0, 'VAT EXEMPT SALES', '', 0, 'L');
        // $pdf->Cell(35, 0, num($nontaxable-$zero_rated), '', 0, 'R');
        // $pdf->ln();
        // $pdf->Cell(30, 0, 'ZERO RATED', '', 0, 'L');
        // $pdf->Cell(35, 0, num($zero_rated), '', 0, 'R');


        // //MENU SUB CAT
        // $pdf->ln(7);
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(30, 0, strtoupper('Sub Categories'), '', 0, 'L');
        // $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        // $pdf->SetFont('helvetica', '', 9);

        // $subcats = $trans_menus['sub_cats'];
        // $qty = 0;
        // $total = 0;
        // foreach ($subcats as $id => $val) {
        //     $pdf->ln();
        //     $pdf->Cell(30, 0, strtoupper($val['name']), '', 0, 'L');
        //     $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
        //     $total += $val['amount'];
        // }
        // if($tot_gross_ret != 0){
        //     $pdf->ln();
        //     $pdf->Cell(30, 0, 'RETAIL', '', 0, 'L');
        //     $pdf->Cell(35, 0, num($tot_gross_ret), '', 0, 'R');
        //     $total += $tot_gross_ret;
        // }

        // $pdf->ln();
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(30, 0,'SUBTOTAL ', 'T', 0, 'L');
        // $pdf->SetFont('helvetica', '', 9);
        // $pdf->Cell(35, 0, num($total), 'T', 0, 'R');

        // // numInt($trans_menus['mods_total'])
        // $pdf->ln();
        // // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(30, 0,'MODIFIERS TOTAL ', '', 0, 'L');
        // $pdf->SetFont('helvetica', '', 9);
        // $pdf->Cell(35, 0, num($trans_menus['mods_total']), '', 0, 'R');

        // $pdf->ln();
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        // $pdf->SetFont('helvetica', '', 9);
        // $pdf->Cell(35, 0, num($total + $trans_menus['mods_total']), 'T', 0, 'R');


        // //DISCOUNTS
        // $pdf->ln(7);
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(60, 0, strtoupper('Discount'), '', 0, 'L');
        // $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        // $pdf->SetFont('helvetica', '', 9);

        // $types = $trans_discounts['types'];
        // foreach ($types as $code => $val) {
        //     $pdf->ln();
        //     $pdf->Cell(60, 0, strtoupper($val['name']), '', 0, 'L');
        //     $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        // }

        // $pdf->ln();
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(60, 0,'TOTAL ', 'T', 0, 'L');
        // $pdf->SetFont('helvetica', '', 9);
        // $pdf->Cell(35, 0, num($discounts), 'T', 0, 'R');
        // $pdf->ln();
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(60, 0,'VAT EXEMPT ', '', 0, 'L');
        // $pdf->SetFont('helvetica', '', 9);
        // $pdf->Cell(35, 0, num($less_vat), '', 0, 'R');


        // //CAHRGES
        // $pdf->ln(7);
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(30, 0, strtoupper('Charges'), '', 0, 'L');
        // $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        // $pdf->SetFont('helvetica', '', 9);
        // // $pdf->ln();

        // $types = $trans_charges['types'];
        // foreach ($types as $code => $val) {
        //     $pdf->ln();
        //     $pdf->Cell(30, 0, strtoupper($val['name']), '', 0, 'L');
        //     $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        // }
           
        // $pdf->ln();
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        // $pdf->SetFont('helvetica', '', 9);
        // $pdf->Cell(35, 0, num($charges), 'T', 0, 'R');


        // //PAYMENTS
        // $pdf->ln(7);
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(30, 0, strtoupper('Payment Mode'), '', 0, 'L');
        // $pdf->Cell(35, 0, strtoupper('Payment Amount'), '', 0, 'R');
        // $pdf->SetFont('helvetica', '', 9);
        // // $pdf->ln();


        // $payments_types = $payments['types'];
        // $payments_total = $payments['total'];
        // foreach ($payments_types as $code => $val) {
        // $pdf->ln();
        //     $pdf->Cell(30, 0, strtoupper($code), '', 0, 'L');
        //     $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        // }
           
        // $pdf->ln();
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        // $pdf->SetFont('helvetica', '', 9);
        // $pdf->Cell(35, 0, num($payments_total), 'T', 0, 'R');


        // if($trans['total_chit']){
        //     // $print_str .= append_chars(substrwords('TOTAL CHIT',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
        //     //               .append_chars(numInt($trans['total_chit']),"left",PAPER_RD_COL_3_3," ")."\r\n";
        //     // $print_str .= "\r\n";
        //     $pdf->ln(7);
        //     $pdf->SetFont('helvetica', 'B', 9);
        //     $pdf->Cell(30, 0,'TOTAL CHIT ', '', 0, 'L');
        //     $pdf->SetFont('helvetica', '', 9);
        //     $pdf->Cell(35, 0, num($trans['total_chit']), '', 0, 'R');
        // }



        // $types = $trans['types'];
        // $types_total = array();
        // $guestCount = 0;
        // foreach ($types as $type => $tp) {
        //     foreach ($tp as $id => $opt){
        //         if(isset($types_total[$type])){
        //             $types_total[$type] += round($opt->total_amount,2);

        //         }
        //         else{
        //             $types_total[$type] = round($opt->total_amount,2);
        //         }
        //         if($opt->guest == 0)
        //             $guestCount += 1;
        //         else
        //             $guestCount += $opt->guest;
        //     }
        // }

        // $pdf->ln(7);
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(30, 0,'TRANS COUNT ', '', 0, 'L');
        // $tc_total  = 0;
        // $tc_qty = 0;
        // foreach ($types_total as $typ => $tamnt) {
        //     $pdf->SetFont('helvetica', '', 9);
        //     $pdf->ln();
        //     $pdf->Cell(30, 0, strtoupper($typ), '', 0, 'L');
        //     $pdf->Cell(20, 0, count($types[$typ]), '', 0, 'L');
        //     $pdf->Cell(25, 0, num($tamnt), '', 0, 'R');
        //     $tc_total += $tamnt;
        //     $tc_qty += count($types[$typ]);
        // }
        // $pdf->ln();
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(50, 0, 'TC TOTAL', 'T', 0, 'L');
        //     $pdf->SetFont('helvetica', '', 9);
        // // $pdf->Cell(20, 0, count($types[$typ]), '', 0, 'L');
        // $pdf->Cell(25, 0, num($tc_total), 'T', 0, 'R');


        

        // -----------------------------------------------------------------------------

        // $tbl = '<table cellspacing="0" cellpadding="1">';
        // $tbl .= "<tr>";
        // $tbl .= "<th style='width:60px;'>Category</th>"; 
        // $tbl .= "<th>Qty</th>";
        // $tbl .= "<th>VAT Sales</th>";
        // $tbl .= "<th>VAT</th>";
        // $tbl .= "<th>Gross</th>";
        // $tbl .= "<th>Sales (%)</th>";
        // $tbl .= "<th>Cost</th>";
        // $tbl .= "<th>Cost (%)</th>";
        // $tbl .= "</tr>";
        // foreach ($trans as $k => $v) {
        //     $tbl .= "<tr>";
        //         $tbl .= '<td>'.$v->menu_cat_name."</td>";             
        //         $tbl .= "<td style='text-align:right;'>".num($v->qty)."</td>";             
        //         $tbl .= "<td style='text-align:right;'>".num($v->vat_sales)."</td>"; 
        //         $tbl .= "<td style='text-align:right;'>".num($v->vat)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num($v->gross)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num(0)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num($v->cost)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num(0)."</td>";                 
        //     $tbl .= "</tr>";         
        // }
        // $tbl .= "</table>";
        
        // $pdf->writeHTML($tbl, true, false, false, false, '');

        // -----------------------------------------------------------------------------
         update_load(100);
        //Close and output PDF document
        $pdf->Output('sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function zero_rev_rep()
    {
        $data = $this->syter->spawn('zero_rev_rep');        
        $data['page_title'] = fa('fa-money')." Zero Revenue Report";
        $data['code'] = zeroRevRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/prints';
        $data['use_js'] = 'zeroRevRepJS';
        $this->load->view('page',$data);
    }

    public function zero_rev_rep_gen()
    {
        
        $user = $this->session->userdata('user');
        $time = $this->site_model->get_db_now();

        $daterange = $this->input->post('calendar_range');
        $dates = explode(" to ",$daterange);
        $from = date('Y-m-d H:i:s',strtotime($dates[0]));
        $to = date('Y-m-d H:i:s',strtotime($dates[1]));

        // echo $from." - ".$to; die();


        $select = 'trans_sales_discounts.*, trans_sales.trans_ref, trans_sales.datetime as tdate';
        $join = array();
        $join['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_discounts.sales_id and trans_sales.pos_id = trans_sales_discounts.pos_id');
        // $join['trans_sales_menus'] = array('content'=>'trans_sales_menus.sales_id = trans_sales_discounts.sales_id');
        // $join['menus'] = array('content'=>'menus.menu_id = trans_sales_menus.menu_id');
        $n_item_res = array();
    
        $args["trans_sales_discounts.disc_code"] = ZERO_REV;
        $args["trans_sales.terminal_id"] = TERMINAL_ID;
        $args["trans_sales.type_id"] = 10;
        $args["trans_sales.inactive"] = '0';
        $args["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
        $args['trans_sales.datetime >= '] = $from;
        $args['trans_sales.datetime <= '] = $to;

        $this->site_model->db= $this->load->database('main', TRUE);
        $details = $this->site_model->get_tbl('trans_sales_discounts',$args,array(),$join,true,$select);

        // echo $this->site_model->db->last_query(); die();


        $det_array = array();

        foreach ($details as $value) {

            $tdate = date('Y-m-d',strtotime($value->tdate));
            
            // echo $value->items.'----';
            if($value->items==''){
                //all menu
                $select = 'trans_sales_menus.*, menus.menu_code';
                $join = array();
                $join['menus'] = array('content'=>'trans_sales_menus.menu_id = menus.menu_id');
                // $join['trans_sales_menus'] = array('content'=>'trans_sales_menus.sales_id = trans_sales_discounts.sales_id');
                // $join['menus'] = array('content'=>'menus.menu_id = trans_sales_menus.menu_id');
                $n_item_res = array();
                $args = array();
                $args["trans_sales_menus.sales_id"] = $value->sales_id;
                // $args["trans_sales_menus.line_id"] = $value->items;
                $dm = $this->site_model->get_tbl('trans_sales_menus',$args,array(),$join,true,$select);

                foreach ($dm as $vv) {
                    # code...
                    if($value->remarks != ""){
                        $where = array('code'=>$value->remarks);
                        $dd = $this->site_model->get_details($where,'discount_reasons');

                        if(count($dd) > 0){
                            $det_array[$tdate][] = array(
                                'date'=>$tdate,
                                'trans_ref'=>$value->trans_ref,
                                'menu_code'=>$vv->menu_code,
                                'menu_name'=>$vv->menu_name,
                                'qty'=>$vv->qty,
                                'orig_price'=>$vv->price,
                                'ext_price'=>$vv->price,
                                'unit_price'=>0,
                                'code'=>$dd[0]->code,
                                'reason'=>$dd[0]->reason,
                            );
                        }else{
                            $det_array[$tdate][] = array(
                                'date'=>$tdate,
                                'trans_ref'=>$value->trans_ref,
                                'menu_code'=>$vv->menu_code,
                                'menu_name'=>$vv->menu_name,
                                'qty'=>$vv->qty,
                                'orig_price'=>$vv->price,
                                'ext_price'=>$vv->price,
                                'unit_price'=>0,
                                'code'=>'OTHER',
                                'reason'=>$value->remarks,
                            );
                        }

                    }else{
                        $det_array[$tdate][] = array(
                            'date'=>$tdate,
                            'trans_ref'=>$value->trans_ref,
                            'menu_code'=>$vv->menu_code,
                            'menu_name'=>$vv->menu_name,
                            'qty'=>$vv->qty,
                            'orig_price'=>$vv->price,
                            'ext_price'=>$vv->price,
                            'unit_price'=>0,
                            'code'=>"",
                            'reason'=>"",
                        );
                    }
                }



            }else{
                
                //per item
                $select = 'trans_sales_menus.*, menus.menu_code';
                $join = array();
                $join['menus'] = array('content'=>'trans_sales_menus.menu_id = menus.menu_id');
                // $join['trans_sales_menus'] = array('content'=>'trans_sales_menus.sales_id = trans_sales_discounts.sales_id');
                // $join['menus'] = array('content'=>'menus.menu_id = trans_sales_menus.menu_id');
                $n_item_res = array();
                $args = array();
                $args["trans_sales_menus.sales_id"] = $value->sales_id;
                $args["trans_sales_menus.line_id"] = $value->items;
                $dm = $this->site_model->get_tbl('trans_sales_menus',$args,array(),$join,true,$select);

                foreach ($dm as $vv) {

                    $code = "";
                    $rson = "";

                    if($value->remarks != ""){
                        $where = array('code'=>$value->remarks);
                        $dd = $this->site_model->get_details($where,'discount_reasons');

                        if(count($dd) > 0){
                            $code = $dd[0]->code;
                            $rson = $dd[0]->reason;
                        }else{
                            $code = 'OTHER';
                            $rson = $value->remarks;
                        }

                    }

                    $where = array('code'=>$value->remarks);
                    $dd = $this->site_model->get_details($where,'discount_reasons');
                    # code...
                    $det_array[$tdate][] = array(
                        'date'=>$tdate,
                        'trans_ref'=>$value->trans_ref,
                        'menu_code'=>$vv->menu_code,
                        'menu_name'=>$vv->menu_name,
                        'qty'=>$vv->qty,
                        'orig_price'=>$vv->price,
                        'ext_price'=>$vv->price,
                        'unit_price'=>0,
                        'code'=>$code,
                        'reason'=>$rson,
                    );
                }

            }
            
        }
        // echo "<pre>", print_r($det_array), "</pre>"; die();
        // die();


        // $post = $this->set_post();
        // $curr = $this->search_current();
        // $trans = $this->trans_sales($post['args'],$curr);
        // $sales = $trans['sales'];
        // $datas = $this->menu_sales($sales['settled']['ids'],$curr);
        // $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        // $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        // $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        // $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        // $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        // $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        // $payments = $this->payment_sales($sales['settled']['ids'],$curr);
        // $gross = $trans_menus['gross'];
        // $net = $trans['net'];
        // $charges = $trans_charges['total'];
        // $discounts = $trans_discounts['total'];
        // $local_tax = $trans_local_tax['total'];
        // $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
        // if($less_vat < 0)
        //     $less_vat = 0;

        // $tax = $trans_tax['total'];
        // $no_tax = $trans_no_tax['total'];
        // $zero_rated = $trans_zero_rated['total'];
        // $no_tax -= $zero_rated;

        // $cats = $trans_menus['cats'];
        // $menus = $trans_menus['menus'];
        // $menu_total = $trans_menus['menu_total'];
        // $total_qty = $trans_menus['total_qty'];
        // usort($cats, function($a, $b) {
        //     return $b['amount'] - $a['amount'];
        // });

        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('TRX NUMBER');
                        $this->make->th('ITEM CODE');
                        $this->make->th('DESCRIPTION');
                        $this->make->th('QUANTITY');
                        $this->make->th('ORIGINAL UNIT PRICE');
                        $this->make->th('EXTENDED AMOUNT');
                        $this->make->th('UNIT PRICE SOLD');
                        $this->make->th('CODE');
                        $this->make->th('REASON');
                        // $this->make->th('Cost');
                        // $this->make->th('Cost (%)');
                        // $this->make->th('Margin');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    foreach ($det_array as $date => $vals) {
                        
                        $this->make->sRow(array("style"=>"background-color:yellow;"));
                            $this->make->td('DATE : '.sql2Date($date));
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            // $this->make->td($ca['qty'], array("style"=>"text-align:right"));                            
                            // // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                            // // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                            // $this->make->td(num($ca['amount']), array("style"=>"text-align:right"));                            
                        $this->make->eRow();
                        $this->make->sRow(array("style"=>"background-color:yellow;"));
                            $this->make->td(RECEIPT_ADDITIONAL_HEADER_BELOW_BRANCH);
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                            // $this->make->td($ca['qty'], array("style"=>"text-align:right"));                            
                            // // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                            // // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                            // $this->make->td(num($ca['amount']), array("style"=>"text-align:right"));                            
                        $this->make->eRow();
                        $total_qty = 0;
                        $total_amt = 0;
                        foreach ($vals as $id => $vvv) {
                            $this->make->sRow();
                                $this->make->td($vvv['trans_ref']);
                                $this->make->td($vvv['menu_code']);
                                $this->make->td($vvv['menu_name']);
                                $this->make->td(num($vvv['qty']), array("style"=>"text-align:right"));                            
                                $this->make->td(num($vvv['orig_price']), array("style"=>"text-align:right"));                            
                                $this->make->td(num($vvv['ext_price']), array("style"=>"text-align:right"));                            
                                $this->make->td(num($vvv['unit_price']), array("style"=>"text-align:right"));                            
                                $this->make->td($vvv['code']);
                                $this->make->td($vvv['reason']);
                                // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                                // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                                // $this->make->td(num($res['amount']), array("style"=>"text-align:right"));                            
                            $this->make->eRow();
                            $total_qty += $vvv['qty'];
                            $total_amt += $vvv['ext_price'];


                        }
                        $this->make->sRow(array("style"=>""));
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('SUBTOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', array("style"=>"text-align:right"));
                            // $this->make->td($ca['qty'], array("style"=>"text-align:right"));                            
                            // // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                            // // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                            $this->make->td(num($total_qty), array("style"=>"text-align:right"));                            
                            $this->make->td('');
                            $this->make->td(num($total_amt), array("style"=>"text-align:right"));                            
                            $this->make->td('');
                            $this->make->td('');
                            $this->make->td('');
                        $this->make->eRow();
                    }
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        // $this->make->sDiv();
        //     $this->make->sTable(array("id"=>"mains-tbl", 'class'=>'table reportTBL sortable'));
        //         $subcats = $trans_menus['sub_cats'];
        //         $qty = 0;
        //         $total = 0;
        //         $this->make->sTableHead();
        //             $this->make->sRow();
        //                 $this->make->th('SUBCATEGORIES', array("style"=>"text-align:center","colspan"=>2));
        //                 // $this->make->th('Qty');
        //                 // // $this->make->th('VAT Sales');
        //                 // // $this->make->th('VAT');
        //                 // $this->make->th('Total');
        //                 // $this->make->th('Sales (%)');
        //                 // $this->make->th('Cost');
        //                 // $this->make->th('Cost (%)');
        //                 // $this->make->th('Margin');
        //             $this->make->eRow();
        //         $this->make->eTableHead();
        //         foreach ($subcats as $id => $val) {
        //             // $print_str .= append_chars($val['name'],"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
        //             //            .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
        //             $this->make->sRow();
        //                 $this->make->td($val['name']);
        //                 $this->make->td(numInt($val['amount']), array("style"=>"text-align:right"));                            
        //                 // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
        //                 // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
        //                 // $this->make->td(num($ca['amount']), array("style"=>"text-align:right"));                            
        //             $this->make->eRow();
        //             $qty += $val['qty'];
        //             $total += $val['amount'];
        //         }
        //     $this->make->eTable();
        // $this->make->eDiv();


        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = array();
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }

    public function zero_rev_excel(){
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $dates = explode(" to ",$_GET['calendar_range']);
        $ddate = sql2Date($dates[0]);
        $filename = 'Zero Revenue Report '.$ddate;
        $rc=1;
        #GET VALUES
            start_load(0);
        $daterange = $_GET['calendar_range'];
        $dates = explode(" to ",$daterange);
        $from = date('Y-m-d H:i:s',strtotime($dates[0]));
        $to = date('Y-m-d H:i:s',strtotime($dates[1]));

        // echo $from." - ".$to; die();


        $select = 'trans_sales_discounts.*, trans_sales.trans_ref, trans_sales.datetime as tdate';
        $join = array();
        $join['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_discounts.sales_id');
        // $join['trans_sales_menus'] = array('content'=>'trans_sales_menus.sales_id = trans_sales_discounts.sales_id');
        // $join['menus'] = array('content'=>'menus.menu_id = trans_sales_menus.menu_id');
        $n_item_res = array();
    
        $args["trans_sales_discounts.disc_code"] = ZERO_REV;
        $args["trans_sales.type_id"] = 10;
        $args["trans_sales.inactive"] = '0';
        $args["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
        $args['trans_sales.datetime >= '] = $from;
        $args['trans_sales.datetime <= '] = $to;
        update_load(10);

        $this->site_model->db= $this->load->database('main', TRUE);
        $details = $this->site_model->get_tbl('trans_sales_discounts',$args,array(),$join,true,$select);
        update_load(30);

        $det_array = array();

        foreach ($details as $value) {

            $tdate = date('Y-m-d',strtotime($value->tdate));
            
            // echo $value->items.'----';
            if($value->items==''){
                //all menu
                $select = 'trans_sales_menus.*, menus.menu_code';
                $join = array();
                $join['menus'] = array('content'=>'trans_sales_menus.menu_id = menus.menu_id');
                // $join['trans_sales_menus'] = array('content'=>'trans_sales_menus.sales_id = trans_sales_discounts.sales_id');
                // $join['menus'] = array('content'=>'menus.menu_id = trans_sales_menus.menu_id');
                $n_item_res = array();
                $args = array();
                $args["trans_sales_menus.sales_id"] = $value->sales_id;
                // $args["trans_sales_menus.line_id"] = $value->items;
                $dm = $this->site_model->get_tbl('trans_sales_menus',$args,array(),$join,true,$select);

                foreach ($dm as $vv) {
                    # code...
                    if($value->remarks != ""){
                        $where = array('code'=>$value->remarks);
                        $dd = $this->site_model->get_details($where,'discount_reasons');

                        if(count($dd) > 0){
                            $det_array[$tdate][] = array(
                                'date'=>$tdate,
                                'trans_ref'=>$value->trans_ref,
                                'menu_code'=>$vv->menu_code,
                                'menu_name'=>$vv->menu_name,
                                'qty'=>$vv->qty,
                                'orig_price'=>$vv->price,
                                'ext_price'=>$vv->price,
                                'unit_price'=>0,
                                'code'=>$dd[0]->code,
                                'reason'=>$dd[0]->reason,
                            );
                        }else{
                            $det_array[$tdate][] = array(
                                'date'=>$tdate,
                                'trans_ref'=>$value->trans_ref,
                                'menu_code'=>$vv->menu_code,
                                'menu_name'=>$vv->menu_name,
                                'qty'=>$vv->qty,
                                'orig_price'=>$vv->price,
                                'ext_price'=>$vv->price,
                                'unit_price'=>0,
                                'code'=>'OTHER',
                                'reason'=>$value->remarks,
                            );
                        }

                    }else{
                        $det_array[$tdate][] = array(
                            'date'=>$tdate,
                            'trans_ref'=>$value->trans_ref,
                            'menu_code'=>$vv->menu_code,
                            'menu_name'=>$vv->menu_name,
                            'qty'=>$vv->qty,
                            'orig_price'=>$vv->price,
                            'ext_price'=>$vv->price,
                            'unit_price'=>0,
                            'code'=>"",
                            'reason'=>"",
                        );
                    }
                }



            }else{
                
                //per item
                $select = 'trans_sales_menus.*, menus.menu_code';
                $join = array();
                $join['menus'] = array('content'=>'trans_sales_menus.menu_id = menus.menu_id');
                // $join['trans_sales_menus'] = array('content'=>'trans_sales_menus.sales_id = trans_sales_discounts.sales_id');
                // $join['menus'] = array('content'=>'menus.menu_id = trans_sales_menus.menu_id');
                $n_item_res = array();
                $args = array();
                $args["trans_sales_menus.sales_id"] = $value->sales_id;
                $args["trans_sales_menus.line_id"] = $value->items;
                $dm = $this->site_model->get_tbl('trans_sales_menus',$args,array(),$join,true,$select);

                foreach ($dm as $vv) {

                    $code = "";
                    $rson = "";

                    if($value->remarks != ""){
                        $where = array('code'=>$value->remarks);
                        $dd = $this->site_model->get_details($where,'discount_reasons');

                        if(count($dd) > 0){
                            $code = $dd[0]->code;
                            $rson = $dd[0]->reason;
                        }else{
                            $code = 'OTHER';
                            $rson = $value->remarks;
                        }

                    }

                    $where = array('code'=>$value->remarks);
                    $dd = $this->site_model->get_details($where,'discount_reasons');
                    # code...
                    $det_array[$tdate][] = array(
                        'date'=>$tdate,
                        'trans_ref'=>$value->trans_ref,
                        'menu_code'=>$vv->menu_code,
                        'menu_name'=>$vv->menu_name,
                        'qty'=>$vv->qty,
                        'orig_price'=>$vv->price,
                        'ext_price'=>$vv->price,
                        'unit_price'=>0,
                        'code'=>$code,
                        'reason'=>$rson,
                    );
                }

            }
            
        }

        update_load(70);

        $styleHeaderCell = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '3C8DBC')
            ),
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 14,
                'color' => array('rgb' => 'FFFFFF'),
            )
        );
        $styleNum = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
        );
        $styleTxt = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
        );
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 20,
            )
        );
        $styleNumC = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'f5f755')
            ),
        );
        $styleTxtC = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'font' => array(
                'bold' => true,
                // 'size' => 20,
            )
            // 'fill' => array(
            //     'type' => PHPExcel_Style_Fill::FILL_SOLID,
            //     'color' => array('rgb' => 'f5f755')
            // ),
        );
        
        $headers = array('TRX NUMBER','ITEM CODE','DESCRIPTION','QUANTITY','ORIGINAL UNIT PRICE','EXTENDED AMOUNT','UNIT PRICE SOLD','CODE','REASON');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(40);
        // $sheet->getColumnDimension('I')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $sheet->getCell('A'.$rc)->setValue('ZERO REVENUE Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;
        
        $dates = explode(" to ",$_GET['calendar_range']);
        $from = sql2DateTime($dates[0]);
        $to = sql2DateTime($dates[1]);
        $sheet->getCell('A'.$rc)->setValue('Date From: '.$from);
        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $rc++;

        $sheet->getCell('A'.$rc)->setValue('Date To: '.$to);
        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $rc++;

        $sheet->getCell('A'.$rc)->setValue('Print Date : '.date('m/d/Y h:i:s A'));
        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $rc++;
        $rc++;
        $rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleTxt);
            $col++;
        }

        $rc++;
        $rc++;

        foreach ($det_array as $date => $vals) {

            $sheet->getCell('A'.$rc)->setValue('DATE : '.sql2Date($date));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxtC);
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(RECEIPT_ADDITIONAL_HEADER_BELOW_BRANCH);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxtC);
            $rc++;
            $total_qty = 0;
            $total_amt = 0;

            foreach ($vals as $id => $vvv) {
                $sheet->setCellValueExplicit('A'.$rc, $vvv['trans_ref'], PHPExcel_Cell_DataType::TYPE_STRING);
                // $sheet->getCell('A'.$rc)->setValue('`'.$vvv['trans_ref']);
                // $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('B'.$rc)->setValue($vvv['menu_code']);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('C'.$rc)->setValue($vvv['menu_name']);
                $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('D'.$rc)->setValue(num($vvv['qty']));
                $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('E'.$rc)->setValue(num($vvv['orig_price']));
                $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('F'.$rc)->setValue(num($vvv['ext_price']));
                $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('G'.$rc)->setValue(num($vvv['unit_price']));
                $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('H'.$rc)->setValue($vvv['code']);
                $sheet->getStyle('H'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('I'.$rc)->setValue($vvv['reason']);
                $sheet->getStyle('I'.$rc)->applyFromArray($styleTxt);
                // $sheet->getCell('B'.$rc)->setValue($vvv['menu_code']);
                // $sheet->getStyle('B'.$rc)->applyFromArray($styleNumC);
                // $sheet->getCell('C'.$rc)->setValue($ca['amount']);
                // $sheet->getStyle('C'.$rc)->applyFromArray($styleNumC);
                $total_qty += $vvv['qty'];
                $total_amt += $vvv['ext_price'];
                $rc++;
                // foreach ($menus as $menu_id => $res) {
                //     if($ca['cat_id'] == $res['cat_id']){
                //         $sheet->getCell('A'.$rc)->setValue('        ['.$res['code'].']'.$res['name']);
                //         $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                //         $sheet->getCell('B'.$rc)->setValue($res['qty']);
                //         $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
                //         $sheet->getCell('C'.$rc)->setValue($res['amount']);
                //         $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                //         $rc++;

                //         foreach ($trans_menus['mods'] as $mnu_id => $mods) {
                //             if($mnu_id == $res['menu_id']){
                //                 foreach ($mods as $mod_id => $val) {
                //                     $sheet->getCell('A'.$rc)->setValue('               *'.$val['name']);
                //                     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                //                     $sheet->getCell('B'.$rc)->setValue($val['qty']);
                //                     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
                //                     $sheet->getCell('C'.$rc)->setValue($val['total_amt']);
                //                     $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                //                     $rc++;
                //                 }
                //             }
                //         }
                //     }
                // }
            }

            $sheet->getCell('C'.$rc)->setValue('SUBTOTAL');
            $sheet->getStyle('C'.$rc)->applyFromArray($styleTxtC);
            $sheet->getCell('D'.$rc)->setValue(num($total_qty));
            $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('F'.$rc)->setValue(num($total_amt));
            $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);

            $rc++;
            $rc++;
        }
       
       
        // $rc++;
        
        update_load(100);
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }



    ////for ayala
    public function trans_sales_terminal($args=array(),$curr=false){
            $total_chit = $total_producttest = 0;
            $chit = array();
            $producttest = array();
            $n_results = array();
            // if($curr){
            //     $this->cashier_model->db = $this->load->database('default', TRUE);
            //     $n_results  = $this->cashier_model->get_trans_sales(null,$args);
            // }
            // echo "<pre>",print_r($args),"</pre>";die();
            
            $this->cashier_model->db = $this->load->database('main', TRUE);
            $results = $this->cashier_model->get_trans_sales_rep(null,$args);
            $trans_count = count($results);
            // echo count($results);die();
            // echo "<pre>",print_r($results),"</pre>";die();
            // echo $this->cashier_model->db->last_query(); die();
            $orders = array();
            // if(HIDECHIT){
            //     // if(count($n_results) > 0){
            //     //     foreach ($n_results as $nres) {

            //     //         if($nres->type_id == 10){
            //     //             $this->site_model->db = $this->load->database('default', TRUE);
            //     //             $where = array('sales_id'=>$nres->sales_id);
            //     //             $rest = $this->site_model->get_details($where,'trans_sales_payments');
            //     //             if($rest){
            //     //                 if($rest[0]->payment_type != 'chit'){

            //     //                     if(!isset($orders[$nres->sales_id])){
            //     //                         $orders[$nres->sales_id] = $nres;
            //     //                     }
            //     //                 }else{
            //     //                     $chit[$nres->sales_id] = $nres;
            //     //                 }
            //     //             }else{
            //     //                 if(!isset($orders[$nres->sales_id])){
            //     //                     $orders[$nres->sales_id] = $nres;
            //     //                 }
            //     //             }

            //     //         }else{
            //     //             if(!isset($orders[$nres->sales_id])){
            //     //                 $orders[$nres->sales_id] = $nres;
            //     //             }
            //     //         }
            //     //     }
            //     // }
            //     foreach ($results as $res) {
                    
            //         if($res->type_id == 10){
            //             $this->site_model->db = $this->load->database('main', TRUE);
            //             $where = array('sales_id'=>$res->sales_id);
            //             $rest = $this->site_model->get_details($where,'trans_sales_payments');
            //             if($rest){
            //                 if($rest[0]->payment_type != 'chit'){
            //                     if(!isset($orders[$res->sales_id])){
            //                         $orders[$res->sales_id] = $res;
            //                     }
            //                 }else{
            //                     // echo 'aa-';
            //                     $chit[$res->sales_id] = $res;
            //                 }
            //             }else{
            //                 if(!isset($orders[$res->sales_id])){
            //                     $orders[$res->sales_id] = $res;
            //                 }
            //             }
            //         }else{
            //             if(!isset($orders[$res->sales_id])){
            //                 $orders[$res->sales_id] = $res;
            //             }
            //         }
            //     }
            // }else{
            //     // if(count($n_results) > 0){
            //     //     foreach ($n_results as $nres) {
            //     //         if(!isset($orders[$nres->sales_id])){
            //     //             $orders[$nres->sales_id] = $nres;
            //     //         }
            //     //     }
            //     // }
            //     foreach ($results as $res) {
            //         if(!isset($orders[$res->sales_id])){
            //             $orders[$res->sales_id] = $res;
            //         }
            //     }
            // }
            // if(PRODUCT_TEST){
            //     foreach ($results as $res) {
                    
            //         if($res->type_id == 10){
            //             $this->site_model->db = $this->load->database('main', TRUE);
            //             $where = array('sales_id'=>$res->sales_id);
            //             $rest = $this->site_model->get_details($where,'trans_sales_payments');
            //             if($rest){
            //                 if($rest[0]->payment_type != 'producttest'){
            //                     if(!isset($orders[$res->sales_id])){
            //                         $orders[$res->sales_id] = $res;
            //                     }
            //                 }else{
            //                     $producttest[$res->sales_id] = $res;
            //                 }
            //             }else{
            //                 if(!isset($orders[$res->sales_id])){
            //                     $orders[$res->sales_id] = $res;
            //                 }
            //             }
            //         }else{
            //             if(!isset($orders[$res->sales_id])){
            //                 $orders[$res->sales_id] = $res;
            //             }
            //         }
            //     }
            // }else{
            //     // if(count($n_results) > 0){
            //     //     foreach ($n_results as $nres) {
            //     //         if(!isset($orders[$nres->sales_id])){
            //     //             $orders[$nres->sales_id] = $nres;
            //     //         }
            //     //     }
            //     // }
            //     foreach ($results as $res) {
            //         if(!isset($orders[$res->sales_id])){
            //             $orders[$res->sales_id] = $res;
            //         }
            //     }
            // }


            foreach ($results as $res) {
                if(!isset($orders[$res->sales_id])){
                    $orders[$res->sales_id] = $res;
                }
            }

            $sales = array();
            $all_ids = array();
            $sales['void'] = array();
            $sales['cancel'] = array();
            $sales['settled'] = array();

            $sales['void']['ids'] = array();
            $sales['cancel']['ids'] = array();
            $sales['settled']['ids'] = array();

            $sales['void']['orders'] = array();
            $sales['cancel']['orders'] = array();
            $sales['settled']['orders'] = array();

            $net = 0;
            $void_amount = 0;
            $cancel_amount = 0;
            $all_guest = 0;
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
                    }
                }
                else{
                    $sales['void']['ids'][] = $sales_id;
                    $sales['void']['orders'][$sales_id] = $sale;
                    $void_amount += round($sale->total_amount,2);
                }
                $all_ids[] = $sales_id;
                $all_orders[$sales_id] = $sale;
                $all_guest += $sale->guest;
            }
                // echo "<pre>",print_r($all_guest),"</pre>";die();
            ksort($ordsnums);
            $first = array_shift(array_slice($ordsnums, 0, 1));
            $last = end($ordsnums);
            $ref_ctr = count($ordsnums);

            if(HIDECHIT){
                // if($curr){
                //     foreach($chit as $key => $vals){

                //         $this->site_model->db = $this->load->database('default', TRUE);
                //         $where = array('sales_id'=>$key);
                //         $results = $this->site_model->get_details($where,'trans_sales_payments');

                //         $total_chit += $results[0]->to_pay;
                //     }
                // }else{

                $chit_args = $args;
                $chit_args['trans_sales_payments.payment_type'] = 'chit';
                $chit_args['trans_sales.type_id'] = SALES_TRANS;
                $chit_args['trans_sales.inactive'] = 0;
                $chit_args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
                // echo "<pre>",print_r($chit_args),"</pre>";die();

                $p_res = $this->cashier_model->get_all_trans_sales_payments(null,$chit_args);
                // echo $this->cashier_model->db->last_query(); die();

                if($p_res){
                    foreach($p_res as $vals){

                        $this->site_model->db = $this->load->database('main', TRUE);
                        $where = array('sales_id'=>$vals->sales_id);
                        $results = $this->site_model->get_details($where,'trans_sales_payments');

                        $total_chit += $results[0]->to_pay;
                    }
                }
                // }
            }
            // echo $total_chit; die();
            if(PRODUCT_TEST){
                $pt_args = $args;
                $pt_args['trans_sales_payments.payment_type'] = 'producttest';
                $pt_args['trans_sales.type_id'] = SALES_TRANS;
                $pt_args['trans_sales.inactive'] = 0;
                $pt_args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
                // echo "<pre>",print_r($pt_args),"</pre>";die();
                $pa_res = $this->cashier_model->get_all_trans_sales_payments(null,$pt_args);

                if($pa_res){
                    foreach($pa_res as $vals){

                        $this->site_model->db = $this->load->database('main', TRUE);
                        $where = array('sales_id'=>$vals->sales_id);
                        $results = $this->site_model->get_details($where,'trans_sales_payments');

                        $total_producttest += $results[0]->to_pay;
                    }
                }
            }

            // echo $total_chit; die('sss');

            return array('all_ids'=>$all_ids,'all_orders'=>$all_orders,'sales'=>$sales,'net'=>$net,'void'=>$void_amount,'types'=>$types,'refs'=>$ordsnums,'first_ref'=>$first,'last_ref'=>$last,'ref_count'=>$ref_ctr,'total_chit'=>$total_chit,'cancel_amount'=>$cancel_amount,'product_test'=>$total_producttest,'all_guest'=>$all_guest,'trans_count'=>$trans_count);
        }

        public function menu_sales_terminal($ids=array(),$curr=false,$terminal=1){
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
                $menu_res = $this->site_model->get_tbl('trans_sales_menus',array('sales_id'=>$ids,'pos_id'=>$terminal),array(),$join,true,$select);
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
                $menu_cat_sale_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$ids,'pos_id'=>$terminal));
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
                $item_res = $this->site_model->get_tbl('trans_sales_items',array('sales_id'=>$ids,'pos_id'=>$terminal),array(),$join,true,$select);
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

        public function discounts_sales_terminal($ids=array(),$curr=false,$terminal=1){
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
                $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$ids,'pos_id'=>$terminal));
                $count_sales_sc = $this->cashier_model->get_trans_sales_discounts_sc(null,array("trans_sales_discounts.sales_id"=>$ids,'pos_id'=>$terminal));
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
        public function charges_sales_terminal($ids=array(),$curr=false,$terminal=1){
            $total_charges = 0;
            $charges = array();
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_charges.sales_id"] = $ids;
                $cargs["trans_sales_charges.pos_id"] = $terminal;
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
        public function tax_sales_terminal($ids=array(),$curr=false,$terminal=1){
            $total_tax = 0;
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_tax.sales_id"] = $ids;
                $cargs["trans_sales_tax.pos_id"] = $terminal;
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
        public function no_tax_sales_terminal($ids=array(),$curr=false,$terminal=1){
            $total_no_tax = 0;
            $total_no_tax_round = 0;
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_no_tax.sales_id"] = $ids;
                $cargs["trans_sales_no_tax.pos_id"] = $terminal;
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
        public function zero_rated_sales_terminal($ids=array(),$curr=false,$terminal=1){
            $total = 0;
            $ids_used = array();
            $tcount =0;
            if(count($ids) > 0){
                $cargs["trans_sales_zero_rated.sales_id"] = $ids;
                $cargs["trans_sales_zero_rated.pos_id"] = $terminal;
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
                    if($ces->amount > 0){
                        $tcount++;
                    }
                }
                // if(count($n_cesults) > 0){
                //     foreach ($n_cesults as $ces) {
                //         if(!in_array($ces->sales_id, $ids_used)){
                //             $total += $ces->amount;
                //         }
                //     }
                // }
            }
            return array('total'=>$total,'tcount'=>$tcount);
        }
        public function local_tax_sales_terminal($ids=array(),$curr=false,$terminal=1){
            $total_local_tax = 0;
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_local_tax.sales_id"] = $ids;
                $cargs["trans_sales_local_tax.pos_id"] = $terminal;
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
        public function payment_sales_terminal($ids=array(),$curr=false,$terminal=1){
            $ret = array();
            $total = 0;
            $pays = array();

            $ov_total = 0;
            $ov_pays = array();

            $ids_used = array();
            $gc_excess = 0;
            $cards = array();
            if(count($ids) > 0){
                $n_payments=array();
                // if($curr){
                //     $this->cashier_model->db = $this->load->database('default', TRUE);
                //     $n_payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$ids));

                // }
                // die('we');


                $this->cashier_model->db = $this->load->database('main', TRUE);
                $payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$ids,"trans_sales_payments.pos_id"=>$terminal));

                // echo $this->db->last_query(); die();

                foreach ($payments as $py) {
                    if(!in_array($py->sales_id, $ids_used)){
                        $ids_used[] = $py->sales_id;
                    }
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

                    if($py->payment_type == 'gc'){
                        if($py->amount > $py->to_pay){
                            $excess = $py->amount - $py->to_pay;
                            $gc_excess += $excess;
                        }
                    }

                    if($py->payment_type == 'credit' || $py->payment_type == 'debit'){
                        if(isset($cards[$py->card_type])){
                            $cards[$py->card_type]['amount'] += $amount;
                            $cards[$py->card_type]['count'] += 1;
                        }else{
                            $cards[$py->card_type] = array('amount'=>$amount,'count'=>1);
                        }
                    }

                }
                // if(count($n_payments) > 0){
                //     foreach ($n_payments as $py) {
                //         if(!in_array($py->sales_id, $ids_used)){
                //             if($py->amount > $py->to_pay)
                //                 $amount = $py->to_pay;
                //             else
                //                 $amount = $py->amount;
                //             if(!isset($pays[$py->payment_type])){
                //                 $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                //             }
                //             else{
                //                 $pays[$py->payment_type]['qty'] += 1;
                //                 $pays[$py->payment_type]['amount'] += $amount;
                //             }
                //             $total += $amount;

                //             if($py->payment_type == 'gc'){
                //                 if($py->amount > $py->to_pay){
                //                     $excess = $py->amount - $py->to_pay;
                //                     $gc_excess += $excess;
                //                 }
                //             }

                //             if($py->payment_type == 'credit'){
                //                 if(isset($cards[$py->card_type])){
                //                     $cards[$py->card_type]['amount'] += $amount;
                //                     $cards[$py->card_type]['count'] += 1;
                //                 }else{
                //                     $cards[$py->card_type] = array('amount'=>$amount,'count'=>1);
                //                 }
                //             }
                //         }
                //     }
                // }

                //FOR PERI PERI OVER GC
                foreach ($payments as $py) {
                    if(!in_array($py->sales_id, $ids_used)){
                        $ids_used[] = $py->sales_id;
                    }

                    if($py->payment_type == 'gc'){
                        $amount = $py->amount;
                    }
                    else{
                        if($py->amount > $py->to_pay)
                            $amount = $py->to_pay;
                        else
                            $amount = $py->amount;
                    }
                    if(!isset($ov_pays[$py->payment_type])){
                        $ov_pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                    }
                    else{
                        $ov_pays[$py->payment_type]['qty'] += 1;
                        $ov_pays[$py->payment_type]['amount'] += $amount;
                    }
                    $ov_total += $amount;
                }
                if(count($n_payments) > 0){
                    foreach ($n_payments as $py) {
                        if(!in_array($py->sales_id, $ids_used)){

                            if($py->payment_type == 'gc'){
                                $amount = $py->amount;
                            }
                            else{
                                if($py->amount > $py->to_pay)
                                    $amount = $py->to_pay;
                                else
                                    $amount = $py->amount;
                            }
                            if(!isset($ov_pays[$py->payment_type])){
                                $ov_pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                            }
                            else{
                                $ov_pays[$py->payment_type]['qty'] += 1;
                                $ov_pays[$py->payment_type]['amount'] += $amount;
                            }
                            $ov_total += $amount;
                        }
                    }
                }
            }
            $ret['total'] = $total;
            $ret['types'] = $pays;
            $ret['gc_excess'] = $gc_excess;
            $ret['cards'] = $cards;
            $ret['ov_total'] = $ov_total;
            $ret['ov_types'] = $ov_pays;

            return $ret;
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