<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/cashier.php");

class Display extends Cashier {
    public function __construct(){
        parent::__construct();
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/menu_model');
        $this->load->model('dine/settings_model');
        $this->load->model('dine/setup_model');
        $this->load->model('dine/display_model');
        $this->load->helper('core/string_helper');
        $this->load->helper('dine/display_helper');
    

    }
	public function kitchen(){
        $data = array();
        $data['head_title'] = 'Kitchen Display';
        $this->load->view('display/parts/head',$data);    
        $this->load->view("display/kitchen");   
        $this->load->view('display/parts/foot');    
    }

    public function get_kitchen_display_data(){
        $get_for_kitchen = $this->display_model->get_for_kitchen_display();
        $get_for_dispatch = $this->display_model->get_for_dispatch_display();
        // echo "<pre>",print_r($get_for_kitchen),"</pre>";die();
        echo json_encode(array('kitchen_sales_id'=> array_merge($get_for_kitchen,$get_for_dispatch)));
    }

     public function get_order_view($sales_id=null,$prev=false){
            if($prev)
                $this->db = $this->load->database('default',true);

            $order = $this->get_order(false,$sales_id);
            $ord = $order['order'];
            $det = $order['details'];
            $discs = $order['discounts'];
            $charges = $order['charges'];
            $kitchen_display_status = $ord['kitchen_display_status'];
            $dispatch_display_status = $ord['dispatch_display_status'];

            $c_det = count($det);

            // var_dump($kitchen_display_status);
            // echo "<pre>",print_r(count($det)),"</pre>";die();

            $total = 0;
            // $totals = $this->total_trans(false,$det,$discs,$charges,$zero_rated);
            if( $kitchen_display_status == '1' && $dispatch_display_status == '1'){
                 $this->make->sDiv(array('class'=>'portlet box green'));
            }else{

                $this->make->sDiv(array('class'=>'portlet box red-soft'));
            }
                if($ord['type'] == 'counter'){
                   $this->make->sDiv(array('class'=>'portlet-title'));//(3,strtoupper(COUNTERTEXT)." #".$ord['sales_id'],array('class'=>'receipt text-center'));
                        $this->make->append("<div class='caption'><i class='fa fa-desktop'></i>".strtoupper(COUNTERTEXT)." #".$ord['queue_id']."</div>");
                   $this->make->eDiv();

                   // $this->make->H(3,strtoupper(COUNTERTEXT)." #".$ord['sales_id'],array('class'=>'receipt text-center'));
                }elseif($ord['type'] == 'takeout'){
                    $this->make->sDiv(array('class'=>'portlet-title')); 
                        $this->make->append("<div class='caption'><i class='fa fa-external-link-square'></i>".strtoupper(TAKEOUTTEXT)." #".$ord['queue_id']."</div>");
                        // $this->make->H(3,strtoupper(TAKEOUTTEXT)." #".$ord['sales_id'],array('class'=>'receipt text-center'));
                    $this->make->eDiv();
                }else{
                    $this->make->sDiv(array('class'=>'portlet-title')); //(3,strtoupper(COUNTERTEXT)." #".$ord['sales_id'],array('class'=>'receipt text-center'));
                        $this->make->append("<div class='caption'><i class='fa fa-cutlery'></i>".strtoupper($ord['type'])." #".$ord['queue_id']."</div>");
                    $this->make->eDiv();
                    // $this->make->H(3,strtoupper($ord['type'])." #".$ord['sales_id'],array('class'=>'receipt text-center'));
                }

                $this->make->sDiv(array('class'=>'portlet-body')); 
                    // $this->make->H(5,sql2DateTime($ord['datetime']),array('class'=>'receipt text-right'));
                $this->make->append("<div class='row'><div class='col-lg-6 col-md-3 col-sm-3'></div><div class='col-lg-6 col-md-9 col-sm-9'><div class='ribbon ribbon-color-warning uppercase ribbon_blue'><h5 class='receipt text-right'>".$ord['datetime']."</h5></div></div></div>");
                    $waiter_name = trim($ord['waiter_name']);
                    $this->make->hidden('c_det',$c_det);
                    if($waiter_name != "")
                        $this->make->H(5,'Food Server: '.$ord['waiter_name'],array('class'=>'receipt text-right'));
                    $this->make->append('<div class="ribbon ribbon-color-warning uppercase ribbon_yellow">ORDERS</div>');

                    $this->make->sDiv(array('class'=>''));
                        $this->make->checkbox('Check All','','',array('class'=>'check-all'));
                    $this->make->eDiv();

                    $this->make->sDiv(array('class'=>'body','style'=>'height:150px;overflow:auto'));
                        $this->make->sUl(array("class"=>'list_order'));
                            foreach ($det as $menu_id => $opt) {
                                if($opt['is_checked'] == 1){
                                    $check = true;
                                }else{
                                    $check = false;
                                }

                                    // $name=$this->make->span($opt['name']);
                                
                                    $name = $this->make->checkbox($opt['qty']." ".$opt['name'],'menu_name[]',$opt['menu_id'],array('id'=>'ord'.$ord['sales_id'],'ref2'=>$opt['menu_id'],'lnid'=>$menu_id,'class'=>'check_class'.$ord['sales_id']),$check);
                                

                                
                                // $qty = $this->make->span($opt['qty'],array('class'=>'qty','return'=>true));
                                // if($opt['nocharge'] == 1){
                                //     $name = $this->make->span(fa('fa-magnet').$opt['name'],array('class'=>'name','return'=>true));
                                // }else{
                                //     $name = $this->make->span($opt['name'],array('class'=>'name','return'=>true));
                                //     // $name = $CI->make->span($opt['name'],array('class'=>'name','return'=>true));
                                // }
                                // $name = $this->make->span($opt['qty']." ".$opt['name'],array('class'=>'name','return'=>true));
                                // $cost = $this->make->span($opt['price'],array('class'=>'cost','return'=>true));
                                $price = $opt['price'];
                                $this->make->li($name,array('style'=>'list-style: none;'));
                                if($opt['remarks'] != ""){
                                    $remarks = $this->make->span(fa('fa-text-width').' '.ucwords($opt['remarks']),array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
                                    $this->make->li($remarks);
                                }
                                if(isset($opt['modifiers']) && count($opt['modifiers']) > 0){
                                    foreach ($opt['modifiers'] as $mod_id => $mod) {
                                        $name = $this->make->span($mod['name'],array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
                                        $cost = "";
                                        // if($mod['price'] > 0 )
                                            // $cost = $this->make->span($mod['price'],array('class'=>'cost','return'=>true));
                                        $this->make->li($name." ");//.$cost);
                                        // $price += $mod['price'];

                                        if(isset($mod['submodifiers']) && count($mod['submodifiers']) > 0){
                                            foreach ($mod['submodifiers'] as $submod_id => $submod) {
                                                $this->make->li($submod['name'] . " ",array('style'=>'margin-left:50px'));
                                            }
                                            
                                        }
                                    }
                                }
                                // $total += $opt['qty'] * $price  ;
                            }

                            // if(count($charges) > 0){
                            //     foreach ($charges as $charge_id => $ch) {
                            //         $qty = $this->make->span(fa('fa fa-tag'),array('class'=>'qty','return'=>true));
                            //         $name = $this->make->span($ch['name'],array('class'=>'name','return'=>true));
                            //         $tx = $ch['amount'];
                            //         if($ch['absolute'] == 0)
                            //             $tx = $ch['amount']."%";
                            //         $cost = $this->make->span($tx,array('class'=>'cost','return'=>true));
                            //         $this->make->li($qty." ".$name." ".$cost);
                            //     }
                            // }

                        $this->make->eUl();
                    $this->make->eDiv();
                    // var_dump($kitchen_display_status);die();
                    if((empty($kitchen_display_status) || $kitchen_display_status == '0') && $kitchen_display_status !='2') {

                        $this->make->sDiv(array('class'=>'row pending'));
                            $this->make->append("<div class='col-md-6'><button class='btn green-jungle btn-outline w100 accept' ref='".$sales_id."'><i class='fa fa-check-circle'></i> Accept</button></div><div class='col-md-6'><button class='btn red btn-outline w100 reject_btn' ref='".$sales_id."'><i class='fa fa-times-circle'></i> Reject</button></div>");
                        $this->make->eDiv();
                        $this->make->sDiv(array('class'=>'row for_dispatch' , 'style'=>'display:none;'));
                            $this->make->append("<div class='col-md-12'><button class='btn yellow-casablanca btn-outline w100 dispatch' ref='".$sales_id."'><i class='fa fa-thumbs-up'></i> Dispatch</button></div>");
                        $this->make->eDiv();
                        $this->make->sDiv(array('class'=>'row completed' , 'style'=>'display:none;'));
                            $this->make->append("<div class='col-md-12'></div>");
                        $this->make->eDiv();
                    }

                    if( $kitchen_display_status == '1' && (empty($dispatch_display_status) || $dispatch_display_status == '0')){
                        $this->make->sDiv(array('class'=>'row for_dispatch' , 'style'=>''));
                            $this->make->append("<div class='col-md-12'><button class='btn yellow-casablanca btn-outline w100 dispatch' ref='".$sales_id."'><i class='fa fa-thumbs-up'></i> Dispatch</button></div>");
                        $this->make->eDiv();
                        $this->make->sDiv(array('class'=>'row completed' , 'style'=>'display:none;'));
                            $this->make->append("<div class='col-md-12'></div>");
                        $this->make->eDiv();
                    }

                    if( $kitchen_display_status == '1' && $dispatch_display_status == '1'){
                        $this->make->sDiv(array('class'=>'row completed' , 'style'=>''));
                            $this->make->append("<div class='col-md-12'></div>");
                        $this->make->eDiv();
                    }



                    // $this->make->append('<hr>');
                    // $this->make->H(3,'TOTAL: '.num($totals['total']),array('class'=>'receipt text-center'));
                    // $this->make->H(4,'DISCOUNT: '.num($totals['discount']),array('class'=>'receipt text-center'));
                $this->make->eDiv();
            $this->make->eDiv();
            $code = $this->make->code();
            echo json_encode(array('code'=>$code,'kitchen_display_status'=>$kitchen_display_status));
        }
   
    public function accept_order(){
        $post = $this->input->post();
        $items = array();
        $status = false;
        // echo "<pre>",print_r($post),"</pre>";die();
        if(isset($post['ref']) && !empty($post['ref'])) {
            $items['kitchen_display_status'] = '1';
            $status = $this->display_model->update_trans_status($post['ref'],$items);
            
        }

        echo json_encode(array('status'=>$status));
        // echo "<pre>",print_r($post),"</pre>";die();
    }
    public function is_checked_trans_menu(){
        $post = $this->input->post();
        $items = array();
        $status = false;
        // echo "<pre>",print_r($post),"</pre>";die();
        if(isset($post['ref']) && !empty($post['ref'])) {
            $items['is_checked'] = '1';
            $status = $this->display_model->update_trans_menus_check($post['ref'],$post['menu_id'],$post['lnid'],$items);
            
        }

        echo json_encode(array('status'=>$status));
        // echo "<pre>",print_r($post),"</pre>";die();
    }

    public function reject_order(){
        $post = $this->input->post();
        $items = array();
        $status = false;
        // echo "<pre>",print_r($post),"</pre>";die();
        if(isset($post['ref']) && !empty($post['ref'])) {
            $items['kitchen_display_status'] = '2';
            $status = $this->display_model->update_trans_status($post['ref'],$items);
            
        }

        echo json_encode(array('status'=>$status));
        // echo "<pre>",print_r($post),"</pre>";die();
    }

    public function dispatch_order(){
        $post = $this->input->post();
        $items = array();
        $status = false;
        // echo "<pre>",print_r($post),"</pre>";die();
        if(isset($post['ref']) && !empty($post['ref'])) {
            $items['dispatch_display_status'] = '1';
            $status = $this->display_model->update_trans_status($post['ref'],$items);
            
        }

        echo json_encode(array('status'=>$status));
        // echo "<pre>",print_r($post),"</pre>";die();
    }

     public function get_kitchen_completed_data(){
        $get_for_kitchen = $this->display_model->get_completed_kitchen_display();

        echo json_encode(array('kitchen_sales_id'=>$get_for_kitchen));
        // echo "<pre>",print_r($get_for_kitchen),"</pre>";die();
    }

     public function get_for_dispatch_display(){
        $get_for_kitchen = $this->display_model->get_for_dispatch_display();

        echo json_encode(array('kitchen_sales_id'=>$get_for_kitchen));
        // echo "<pre>",print_r($get_for_kitchen),"</pre>";die();
    }

    public function get_ready_to_dispatch_display(){
        $get_ready_to_dispatch_display = $this->display_model->get_ready_to_dispatch_display();
        // echo $this->db->last_query();die();

        echo json_encode(array('kitchen_sales_id'=>$get_ready_to_dispatch_display));
    }

    public function get_for_completed_display(){
        $get_for_kitchen = $this->display_model->get_completed_display();
        // echo $this->db->last_query();die();
        echo json_encode(array('kitchen_sales_id'=>$get_for_kitchen));
        // echo "<pre>",print_r($get_for_kitchen),"</pre>";die();
    }


    public function dispatch(){
        $data = array();
        $data['head_title'] = 'Dispatch Display';
        $this->load->view('display/parts/head',$data);    
        $this->load->view("display/dispatch");   
        $this->load->view('display/parts/foot');    
    }

    public function get_dispatch_view($sales_id=null,$prev=false){
            if($prev)
                $this->db = $this->load->database('default',true);

            $order = $this->get_order(false,$sales_id);
            $ord = $order['order'];
            $det = $order['details'];
            $discs = $order['discounts'];
            $charges = $order['charges'];
            $kitchen_display_status = $ord['kitchen_display_status'];
            $dispatch_display_status = $ord['dispatch_display_status'];
            $completed_display_status = $ord['completed_display_status'];

            // var_dump($ord);

            // echo "<pre>",print_r($ord),"</pre>";die();
            // echo "<pre>kds",print_r($kitchen_display_status),"</pre>";
            // echo "<pre>dds",print_r($dispatch_display_status),"</pre>";

            // die();

            $total = 0;
            // $totals = $this->total_trans(false,$det,$discs,$charges,$zero_rated);
            if($completed_display_status == '1'){
                $this->make->sDiv(array('class'=>'portlet box green-meadow'));
            }else{
                $this->make->sDiv(array('class'=>'portlet box green-sharp'));
                
            }
                if($ord['type'] == 'counter'){
                   $this->make->sDiv(array('class'=>'portlet-title'));//(3,strtoupper(COUNTERTEXT)." #".$ord['sales_id'],array('class'=>'receipt text-center'));
                        $this->make->append("<div class='caption'><i class='fa fa-desktop'></i>".strtoupper(COUNTERTEXT)." #".$ord['queue_id']."</div>");
                   $this->make->eDiv();

                   // $this->make->H(3,strtoupper(COUNTERTEXT)." #".$ord['sales_id'],array('class'=>'receipt text-center'));
                }elseif($ord['type'] == 'takeout'){
                    $this->make->sDiv(array('class'=>'portlet-title')); 
                        $this->make->append("<div class='caption'><i class='fa fa-external-link-square'></i>".strtoupper(TAKEOUTTEXT)." #".$ord['queue_id']."</div>");
                        // $this->make->H(3,strtoupper(TAKEOUTTEXT)." #".$ord['sales_id'],array('class'=>'receipt text-center'));
                    $this->make->eDiv();
                }else{
                    $this->make->sDiv(array('class'=>'portlet-title')); //(3,strtoupper(COUNTERTEXT)." #".$ord['sales_id'],array('class'=>'receipt text-center'));
                        $this->make->append("<div class='caption'><i class='fa fa-cutlery'></i>".strtoupper($ord['type'])." #".$ord['queue_id']." - Table ".$ord['table_name']."</div>");
                    $this->make->eDiv();
                    // $this->make->H(3,strtoupper($ord['type'])." #".$ord['sales_id'],array('class'=>'receipt text-center'));
                }

                $this->make->sDiv(array('class'=>'portlet-body')); 
                    // $this->make->H(5,sql2DateTime($ord['datetime']),array('class'=>'receipt text-right'));
                $this->make->append("<div class='row'><div class='col-lg-6 col-md-3 col-sm-3'></div><div class='col-lg-6 col-md-9 col-sm-9'><div class='ribbon ribbon-color-warning uppercase ribbon_blue'><h5 class='receipt text-right'>".$ord['datetime']."</h5></div></div></div>");
                    $waiter_name = trim($ord['waiter_name']);
                    if($waiter_name != "")
                        $this->make->H(5,'Food Server: '.$ord['waiter_name'],array('class'=>'receipt text-right'));
                    $this->make->append('<div class="ribbon ribbon-color-warning uppercase ribbon_yellow">ORDERS</div>');
                    $this->make->sDiv(array('class'=>'body','style'=>'height:150px;overflow:auto'));
                        $this->make->sUl(array("class"=>'list_order'));
                            foreach ($det as $menu_id => $opt) {
                                if($opt['is_checked']==1){
                                    $qty = $this->make->span($opt['qty'],array('class'=>'qty','return'=>true));
                                    if($opt['nocharge'] == 1){
                                        $name = $this->make->span(fa('fa-magnet').$opt['name'],array('class'=>'name','return'=>true));
                                    }else{
                                        $name = $this->make->span($opt['name'],array('class'=>'name','return'=>true));
                                        // $name = $CI->make->span($opt['name'],array('class'=>'name','return'=>true));
                                    }
                                    // $cost = $this->make->span($opt['price'],array('class'=>'cost','return'=>true));
                                    $price = $opt['price'];
                                    $this->make->li($qty." ".$name);
                                    if($opt['remarks'] != ""){
                                        $remarks = $this->make->span(fa('fa-text-width').' '.ucwords($opt['remarks']),array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
                                        $this->make->li($remarks);
                                    }
                                    if(isset($opt['modifiers']) && count($opt['modifiers']) > 0){
                                        foreach ($opt['modifiers'] as $mod_id => $mod) {
                                            $name = $this->make->span($mod['name'],array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
                                            $cost = "";
                                            // if($mod['price'] > 0 )
                                                // $cost = $this->make->span($mod['price'],array('class'=>'cost','return'=>true));
                                            $this->make->li($name." ");//.$cost);
                                            // $price += $mod['price'];

                                            if(isset($mod['submodifiers']) && count($mod['submodifiers']) > 0){
                                                foreach ($mod['submodifiers'] as $submod_id => $submod) {
                                                    $this->make->li($submod['name'] . " ",array('style'=>'margin-left:50px'));
                                                }
                                                
                                            }
                                        }
                                    }
                                    // $total += $opt['qty'] * $price  ;
                                    }                                
                            }

                            // if(count($charges) > 0){
                            //     foreach ($charges as $charge_id => $ch) {
                            //         $qty = $this->make->span(fa('fa fa-tag'),array('class'=>'qty','return'=>true));
                            //         $name = $this->make->span($ch['name'],array('class'=>'name','return'=>true));
                            //         $tx = $ch['amount'];
                            //         if($ch['absolute'] == 0)
                            //             $tx = $ch['amount']."%";
                            //         $cost = $this->make->span($tx,array('class'=>'cost','return'=>true));
                            //         $this->make->li($qty." ".$name." ".$cost);
                            //     }
                            // }

                        $this->make->eUl();
                    $this->make->eDiv();
                    // var_dump($kitchen_display_status);die();
                    if(($kitchen_display_status == '1') && $dispatch_display_status == '1' && (empty($completed_display_status) || $completed_display_status == '0')){

                        $this->make->sDiv(array('class'=>'row pending'));
                            $this->make->append("<div class='col-md-12'><button class='btn blue btn-outline w100 completed' ref='".$sales_id."'><i class='fa fa-thumbs-up'></i> Mark Complete</button></div>");
                            // $this->make->append("<div class='col-md-6'><button class='btn green-jungle btn-outline w100 accept' ref='".$sales_id."'><i class='fa fa-check-circle'></i> Accept</button></div><div class='col-md-6'><button class='btn red btn-outline w100 reject' ref='".$sales_id."'><i class='fa fa-times-circle'></i> Reject</button></div>");
                        $this->make->eDiv();
                        $this->make->sDiv(array('class'=>'row for_dispatch' , 'style'=>'display:none;'));
                        $this->make->eDiv();
                        $this->make->sDiv(array('class'=>'row completed' , 'style'=>'display:none;'));
                            $this->make->append("<div class='col-md-12'></div>");
                        $this->make->eDiv();
                    }

                    // if( $kitchen_display_status == '1' && (!empty($dispatch_display_status) && $dispatch_display_status == '1')){
                    //     $this->make->sDiv(array('class'=>'row for_dispatch' , 'style'=>''));
                    //         $this->make->append("<div class='col-md-12'><button class='btn yellow-casablanca btn-outline w100 dispatch' ref='".$sales_id."'><i class='fa fa-thumbs-up'></i> Dispatch</button></div>");
                    //     $this->make->eDiv();
                    //     $this->make->sDiv(array('class'=>'row completed' , 'style'=>'display:none;'));
                    //         $this->make->append("<div class='col-md-12'></div>");
                    //     $this->make->eDiv();
                    // }

                    if( $kitchen_display_status == '1' && $dispatch_display_status == '1' && $completed_display_status == '1'){
                        $this->make->sDiv(array('class'=>'row completed' , 'style'=>''));
                            $this->make->append("<div class='col-md-12'></div>");
                        $this->make->eDiv();
                    }



                    // $this->make->append('<hr>');
                    // $this->make->H(3,'TOTAL: '.num($totals['total']),array('class'=>'receipt text-center'));
                    // $this->make->H(4,'DISCOUNT: '.num($totals['discount']),array('class'=>'receipt text-center'));
                $this->make->eDiv();
            $this->make->eDiv();
            $code = $this->make->code();
            echo json_encode(array('code'=>$code));
    }


    public function get_dispatch_display_data(){
        // $get_for_kitchen = $this->display_model->get_for_kitchen_display();
        $get_for_dispatch = $this->display_model->get_for_dispatch_display();
        $get_completed_dispatch = $this->display_model->get_completed_display();
        echo json_encode(array('kitchen_sales_id'=> array_merge($get_for_dispatch,$get_completed_dispatch)));
        // echo "<pre>",print_r($get_for_kitchen),"</pre>";die();
    }

    public function complete_order(){
        $post = $this->input->post();
        $items = array();
        $status = false;
        // echo "<pre>",print_r($post),"</pre>";die();
        if(isset($post['ref']) && !empty($post['ref'])) {
            $items['completed_display_status'] = '1';
            $status = $this->display_model->update_trans_status($post['ref'],$items);
            
        }

        echo json_encode(array('status'=>$status));
        // echo "<pre>",print_r($post),"</pre>";die();
    }


    public function complete_for_dispatch_order(){
        $post = $this->input->post();
        $items = array();
        $status = false;
        // echo "<pre>",print_r($post),"</pre>";die();
        if(isset($post['ref']) && !empty($post['ref'])) {
            $items['dispatch_display_status'] = '1';
            $status = $this->display_model->update_trans_status($post['ref'],$items);
            
        }

        echo json_encode(array('status'=>$status));
        // echo "<pre>",print_r($post),"</pre>";die();
    }

    function customer(){
        $data = array();
        $data['head_title'] = 'Customer Display';
        $this->load->view('display/parts/head',$data);    
        $this->load->view("display/customer");   
        $this->load->view('display/parts/foot');   
    }

    function customer_order_status($type){
        if($type == 'kitchen'){
            $data = $this->display_model->get_for_kitchen_display(1);
        
        }else{
            $data = $this->display_model->get_ready_to_dispatch_display();
        }
        $html = '';

        // $data = array(1,2,3,4,5,6,7,8,9);
        if($data){
            // $ctr = 0;
            foreach($data as $i=>$each){
                $tot_menus = $this->display_model->get_trans_sales_menus($each->sales_id);
                $pending_menus = $this->display_model->get_trans_sales_menus($each->sales_id,'0');
                
                $pd_order = '';
                if($type == 'kitchen' && count($tot_menus) !=  count($pending_menus)){
                    // $pd_order = "<span class='pull-right caption-subject bold uppercase em_title' style='font-size:18px !important;line-height: 60px; '>(Pending order for serving(".count($pending_menus)."))</span>";
                    $pd_order = "<span class='pull-right caption-subject bold uppercase em_title' style='font-size:18px !important;line-height: 60px; '>(With Pending Order)</span>";
                }

                $html .= "<div class='row' style='margin: 0px'><span class='caption-subject bold uppercase em_title' style='font-size:40px !important'>#". $each->queue_id ."</span>".$pd_order."</div>";
                // $menus = $this->display_model->get_trans_sales_menus($each->sales_id,true);
                // if($type == 'kitchen' && !$menus){
                //     $html .= "<div class='row' style='margin: 0px'><span class='caption-subject bold uppercase em_title'>#". $each->queue_id ."</span></div>";
                // }else if($type == 'dispatch'){
                //     $html .= "<div class='row' style='margin: 0px'><span class='caption-subject bold uppercase em_title'>#". $each->queue_id ."</span></div>";
                // }
                

                
            }
        }
// echo $html;exit;
        echo json_encode(array('code'=>$html));
    }
}