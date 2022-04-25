<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (realpath(dirname(__FILE__) . '/..')."/dine/prints.php");
class Member_Dashboard extends Prints {
	var $data = null;
    public function __construct(){
        parent::__construct();
        $this->load->helper('core/member_dashboard_helper');  
    }
    public function index(){
        $data = $this->syter->spawn('member_dashboard');
        $today = $this->site_model->get_db_now();
        
        $select = 'sum(total_amount) as total_sales,sum(coalesce(ar_payment_amount,0)) as collected';
        $args = array();
        $args["type_id"] = 10;      
        $args["inactive"] = 0;
        // $args["YEAR(trans_sales.datetime)"] = date('Y');
        $args["trans_sales_payments.payment_type IN('arclearingbilling','arclearingpromo')"] = array('use'=>'where','val'=>null,'third'=>false);
        $joinTables['trans_sales_payments'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id');
        
        $sales = $this->site_model->get_tbl('trans_sales',$args,array(),$joinTables,true,$select);
        $total_sales = $sales[0]->total_sales;
        $collected = $sales[0]->collected;
        $balance = $total_sales - $collected;


        $select = 'count(*) as total_member';
        $args = array();
        $args["YEAR(date_join) = YEAR(CURRENT_DATE())"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["MONTH(date_join) = MONTH(CURRENT_DATE())"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["is_ar"] = 1;
        $args["inactive"] = 0;
        
        $member = $this->site_model->get_tbl('customers',$args,array(),null,true,$select);
        $member_join_curr_month = $member[0]->total_member;

        $select = 'count(*) as active_member';
        $args = array();      
        $args["is_ar"] = 1;  
        $args["inactive"] = 0;
        
        $member = $this->site_model->get_tbl('customers',$args,array(),null,true,$select);
        $active_member = $member[0]->active_member;

        $select = 'count(*) as inactive_member';
        $args = array();      
        $args["is_ar"] = 1;  
        $args["inactive"] = 1;
        
        $member = $this->site_model->get_tbl('customers',$args,array(),null,true,$select);
        $inactive_member = $member[0]->inactive_member;

        $avg_spend = $total_sales/$active_member;

        $select = 'count(*) as dl_trans';
        $args = array();
        $args["type_id"] = 10;      
        $args["inactive"] = 0;
        $args["DATEDIFF(CURRENT_DATE(), trans_sales.datetime) >30"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["(trans_sales.total_amount != trans_sales.ar_payment_amount || trans_sales.ar_payment_amount is null)"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales_payments.payment_type IN('arclearingbilling','arclearingpromo')"] = array('use'=>'where','val'=>null,'third'=>false);
        $joinTables['trans_sales_payments'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id');
        
        $sales = $this->site_model->get_tbl('trans_sales',$args,array(),$joinTables,true,$select);
        $dl_trans = $sales[0]->dl_trans;
        // echo $this->db->last_query();exit;
        $data['code'] = dashboardMain($total_sales,$collected,$balance,$dl_trans,$avg_spend,$active_member,$member_join_curr_month,$inactive_member);
        $data['sideBarHide'] = true;
        $data['add_css'] = array('css/morris/morris.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/jqueryKnob/jquery.knob.js','js/plugins/sparkline/jquery.sparkline.min.js');
        $data['page_no_padding'] = true;
        $data['load_js'] = 'dine/dashboard.php';
        $data['use_js'] = 'memberDashboardJs';
        // $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function get_member_payment(){
        $select = 'sum(coalesce(ar_payment_amount,0)) as total_paid,trans_sales.datetime';
        $args = array();
        $args["type_id"] = 10;      
        $args["inactive"] = 0;
        $args["YEAR(trans_sales.datetime)"] = date('Y');
        $args["trans_sales_payments.payment_type IN('arclearingbilling','arclearingpromo')"] = array('use'=>'where','val'=>null,'third'=>false);
        $joinTables['trans_sales_payments'] = array('content'=>'trans_sales_payments.sales_id = trans_sales.sales_id');
        
        $payments = $this->site_model->get_tbl('trans_sales',$args,array(),$joinTables,true,$select,'MONTH(trans_sales.datetime)');
        // echo $this->db->last_query();
        $final_datas = array();
        $months = array();

        foreach($payments as $idd => $v){
            $final_datas[] = array('date'=>date('Y-m',strtotime($v->datetime)),
                                    'value'=>$v->total_paid
                );
            $months[] = date('m',strtotime($v->datetime));
        }

        $x = 0;
        $exist = false;
        while($x++ <= 11) { 
            foreach($final_datas as $each){
                if(sprintf("%02d", $x) == date('m',strtotime($each['date']))){
                    $exist = true;
                    // break;
                }
            }

            if(!$exist){
              $final_datas[] = array('date'=>date('Y-').sprintf("%02d", $x),
                                    'value'=>0
                );  
            }

            $exist = false;
        }





        echo json_encode(array('datas'=>$final_datas));
    }

    public function get_top_menus(){
        $calendar= $this->site_model->get_db_now();
        $curr = true;
        $args["DATE(trans_sales.datetime) = DATE('".date2Sql($calendar)."') "] = array('use'=>'where','val'=>null,'third'=>false);
        $trans = $this->trans_sales($args,$curr);
        $sales = $trans['sales'];
        $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        $menus = $trans_menus['menus'];
        usort($menus, function($a, $b) {
            return $b['amount'] - $a['amount'];
        });
        $this->make->sTable(array('class'=>'table table-striped table-responsive','style'=>'display: inline-block;'));
            $ctr = 1;
            $this->make->sRow();
                $this->make->th('#',array('width'=>'10'));
                $this->make->th('Name');
                $this->make->th('QTY',array('width'=>'10'));
                $this->make->th('Amount',array('width'=>'25'));
            $this->make->eRow();
            foreach ($menus as $res) {
                $this->make->sRow();
                    $this->make->td($ctr.".");
                    $this->make->td($res['name']);
                    $this->make->td($res['qty']);
                    $this->make->td(num($res['amount']) );
                $this->make->eRow();                
                if($ctr == 10)
                    break;
                $ctr++;
            }
        $this->make->eTable();
        echo $this->make->code();
    }
    public function summary_orders(){
        $today = $this->site_model->get_db_now(null,true);
        $args = array();
        $args["DATE(trans_sales.datetime)"] = $today;
        if(HIDECHIT){
            $args['trans_sales.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = "chit")'] = array('use'=>'where','val'=>null,'third'=>false);
        }
        if(PRODUCT_TEST){
            $args['trans_sales.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = "producttest")'] = array('use'=>'where','val'=>null,'third'=>false);
        }
        $orders = array();
        $ords = $this->cashier_model->get_trans_sales(null,$args);
        $types = unserialize(SALE_TYPES);
        $set = $this->cashier_model->get_pos_settings();
        if(count($set) > 0){
            $types = array();
            $ids = explode(',',$set->controls);
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
        }

        $status = array('Open'=>'blue','Settled'=>'green','Cancel'=>'yellow','Void'=>'red');
        
        foreach ($types as $typ) {
            $open = 0;
            $settled = 0;
            $cancel = 0;
            $void = 0;
            $open_count = 0;
            $settled_count = 0;
            $cancel_count = 0;
            $void_count = 0;
            foreach ($ords as $res) {
                if(strtolower($res->type) == strtolower($typ)){
                    if($res->type_id == 10){
                        if($res->trans_ref != "" && $res->inactive == 0){
                            $settled += $res->total_amount;
                            $settled_count++;
                        }
                        elseif($res->trans_ref == ""){
                            if($res->inactive == 0){
                                $open += $res->total_amount;
                                $open_count++;
                            }
                            else{
                                $cancel += $res->total_amount;
                                $cancel_count++;
                            }
                        }
                    }
                    else{
                        $void += $res->total_amount;
                        $void_count++;
                    }
                }
            }
            $orders[$typ] = array('label'=>$typ,'open'=>$open,'settled'=>$settled,'cancel'=>$cancel,'void'=>$void,'open_count'=>$open_count,'settled_count'=>$settled_count,'cancel_count'=>$cancel_count,'void_count'=>$void_count);
        }
        $shift_sales = array();
        foreach ($ords as $res) {
            if($res->type_id == 10){
                if($res->trans_ref != "" && $res->inactive == 0){
                    if(isset($shift_sales[$res->shift_id])){
                        $shift_sales[$res->shift_id] += $res->total_amount;
                    }
                    else
                        $shift_sales[$res->shift_id] = $res->total_amount;
                }    
            }
        }
        $shifts = array();
        foreach ($shift_sales as $shift_id => $total) {
            if(!in_array($shift_id, $shifts))
                $shifts[] = $shift_id;
        }
        $shs = array();
        if(count($shifts) > 0){
            $select = "shifts.shift_id,users.username,users.fname,users.mname,users.lname,users.suffix";
            $joinTables['users'] = array('content'=>'shifts.user_id = users.id');
            $sh = $this->site_model->get_tbl('shifts',array('shift_id'=>$shifts),array(),$joinTables,true,$select);
            foreach ($sh as $res) {
                // $shs[$res->shift_id] = array('label'=>$res->fname." ".$res->mname." ".$res->lname." ".$res->suffix,'value'=>numInt($shift_sales[$res->shift_id]) );
                $shs[$res->shift_id] = array('label'=>$res->username,'value'=>numInt($shift_sales[$res->shift_id]) );
            }
        }
        if(count($shs) == 0){
            $shs[]=array('label'=>'No Sales Found','value'=>numInt(0));
        }

        $total_trans = 0;
        $stat = array();
        $total_sales = 0;
        foreach ($orders as $type => $opt) {
            foreach ($opt as $txt => $val) {
                if($txt != 'label'){
                    if($txt == 'open' || $txt == 'settled' || $txt == 'cancel' || $txt == 'void'){
                        if(isset($stat[strtolower($txt)]))
                            $stat[strtolower($txt)] += $val;
                        else
                            $stat[strtolower($txt)] = $val;
                        $total_trans += $val;

                        if($txt == 'open' || $txt == 'settled')
                            $total_sales += $val;
                    }else{
                        if(isset($counts[strtolower($txt)]))
                            $counts[strtolower($txt)] += $val;
                        else
                            $counts[strtolower($txt)] = $val;
                    }
                }
            }
        }

        // <div class="row">
        //     <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
        //         <input type="text" class="knob" data-readonly="true" value="80" data-width="60" data-height="60" data-fgColor="#f56954"/>
        //         <div class="knob-label">CPU</div>
        //     </div><!-- ./col -->
        //     <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
        //         <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60" data-fgColor="#00a65a"/>
        //         <div class="knob-label">Disk</div>
        //     </div><!-- ./col -->
        //     <div class="col-xs-4 text-center">
        //         <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60" data-fgColor="#3c8dbc"/>
        //         <div class="knob-label">RAM</div>
        //     </div><!-- ./col -->
        // </div><!-- /.row -->
        $this->make->sDivRow(); 
        foreach ($status as $txt => $color) {
            $this->make->sDivCol(6,'center');
                if($total_trans == 0 || $stat[strtolower($txt)] == 0)
                    $percent = 0;
                else
                    $percent = ($stat[strtolower($txt)]/$total_trans) * 100;
                $this->make->append(
                     '
                         <div class="easy-pie-chart">
                            <div class=" number '.$color.'" data-percent="'.num($percent,0).'">
                                <span>'.num($percent,0).'</span>%  </div>
                                <div>'.$txt.'</div>
                            <a class="title" href="javascript:;">'.small( $counts[strtolower($txt.'_count')].'/'.num($stat[strtolower($txt)]) ."/".num($total_trans) ).'
                                <i class="icon-arrow-right"></i>
                            </a>
                        </div>         
                    '
                        // <div class="knob">
                        //     <div class="'.$color.'" data-percent="'.num($percent,0).'"  data-scale-color="#000000"></div>
                        //     <span>'.num($percent,0).'</span>% 
                        //     <div>'.$txt.'</div>
                        //     <div class="knob-label">'.small( num($stat[strtolower($txt)]) ."/".num($total_trans) ).'</div>
                        // </div>

                     // <div class="knob" data-percent="98" data-scale-color="#000000"></div>'.$txt.'
                     // <div class="knob-label">'.small( num($stat[strtolower($txt)]) ."/".num($total_trans) ).'</div>
                        // <div class="knob-label">'.$txt.'</div>
                        // <div class="knob" data-readonly="true" value="'.num($percent,0).'" data-skin="tron" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" data-width="100" data-height="100" data-fgColor="'.$color.'"/>
                        // <div class="knob-label">'.small( num($stat[strtolower($txt)]) ."/".num($total_trans) ).'</div>
                 );
            $this->make->eDivCol();
            // $this->make->sDiv(array('class'=>'clearfix'));
            //     $this->make->span($txt,array('class'=>'pull-left'));
            //     $this->make->span(small( num($stat[strtolower($txt)]) ."/".num($total_trans) ),array('class'=>'pull-right'));
            // $this->make->eDiv();
            // $this->make->sDiv(array('style'=>'margin-bottom:10px;'));
            //     $this->make->progressBar($total_trans,$stat[strtolower($txt)],null,0,$color,array());
            // $this->make->eDiv();
        }
        $this->make->eDivRow(); 
        $code = $this->make->code();
        
        echo json_encode(array("orders"=>$orders,'shift_sales'=>$shs,'types'=>$types,'code'=>$code));
    }
}