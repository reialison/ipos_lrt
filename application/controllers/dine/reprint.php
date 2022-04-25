<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/cashier.php");
class Reprint extends Cashier {
    public function __construct(){
        parent::__construct();
        $this->load->helper('dine/reprint_helper');
        // $this->load->model('dine/cashier_model');
    }
    public function index(){
        $data = $this->syter->spawn('act_receipts');
        $data['page_title'] = 'Receipts';
        $data['code'] = printsPage();
        $data['load_js'] = 'dine/reprint';
        $data['use_js'] = 'printReceiptJs';
        $this->load->view('page',$data);
    }
    public function results(){
        $ref = $this->input->post('receipt');
        $args['sales_id'] = array('use'=>'like','val'=>$ref); 
        $args['trans_ref'] = array('use'=>'or_like','val'=>$ref); 
        // $args['terminal_id'] = array('use'=>'where','val'=>TERMINAL_ID,'third'=>false); 
        $results = $this->site_model->get_tbl('trans_sales',$args,array('trans_sales.datetime'=>'desc'),null,true,'trans_ref,sales_id,datetime,total_amount,terminal_id');
        $code = "";
        $ids = array();

        $this->make->sDiv(array('class'=>'list-group'));
        foreach ($results as $res) {
            if($res->terminal_id == TERMINAL_ID){
                $this->make->append('<a href="#" id="rec-'.$res->sales_id.'" class="rec list-group-item">');
                    $this->make->sDiv();
                        $this->make->H(6,'Order No. <span class="pull-right"> total: '.$res->total_amount.'</span> '.$res->sales_id,array('style'=>'font-size:14px;margin:2px;'));
                    $this->make->eDiv();
                        $this->make->p('Receipt No. '.$res->trans_ref.'<span class="pull-right">'.sql2Datetime($res->datetime).'</span>',array('style'=>'font-size:12px;margin:2px;'));
                $this->make->append('</a>');
                $ids[] = $res->sales_id;
            }
        }
        $this->make->eDiv();
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$ids));
    }
    public function view($sales_id=null,$noPrint=true){
        if($noPrint)
            $reprint = false;
        else
            $reprint = true;

        $print = $this->print_sales_receipt_justin($sales_id,false,$noPrint,$reprint,null,true,1,0,null,false);   
        // var_dump($print);die();
        echo "<pre style='background-color:#fff'>";
            echo $print;
        echo "</pre>"; 
    }
    public function allPrint(){
        $ref = $this->input->post('receipt');
        $sales = '2265';
        // $sales = '2314,2347,2339,2364';
        // $sales = '112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,157,159,160,161,162,164,165,166,167,169,170,171,172,173,174,175,176,177,178';
        $ids = explode(',', $sales);
        $args['sales_id'] = $ids; 
        // $args['sales_id'] = array('use'=>'like','val'=>$ref); 
        // $args['trans_ref'] = array('use'=>'or_like','val'=>$ref); 
         $this->db = $this->load->database('main', TRUE);
        $results = $this->site_model->get_tbl('trans_sales',$args,array('trans_sales.datetime'=>'desc'),null,true,'trans_ref,sales_id,datetime,total_amount');
        $code = "";
        $ids = array();

        $this->make->sDiv(array('class'=>'list-group'));
        foreach ($results as $res) {
            // $this->make->append('<a href="#" id="rec-'.$res->sales_id.'" class="rec list-group-item">');
            //     $this->make->sDiv();
            //         $this->make->H(6,'Order No. <span class="pull-right"> total: '.$res->total_amount.'</span> '.$res->sales_id,array('style'=>'font-size:14px;margin:2px;'));
            //     $this->make->eDiv();
            //         $this->make->p('Receipt No. '.$res->trans_ref.'<span class="pull-right">'.sql2Datetime($res->datetime).'</span>',array('style'=>'font-size:12px;margin:2px;'));
            // $this->make->append('</a>');
            // $ids[] = $res->sales_id;
            $this->view($res->sales_id);
        }
        $this->make->eDiv();
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$ids));
    }

    public function printReport(){
        $data = $this->syter->spawn('act_receipts_all');
        $data['page_title'] = 'Electronic Journal';
        $data['code'] = printAllPage();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/reprint';
        $data['use_js'] = 'printReceiptAllJs';
        $this->load->view('page',$data);
    }

    public function resultsAll(){
        // $date = $this->input->post('calendar_range');
        $this->db = $this->load->database('main', TRUE);
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        $dates = explode(" to ",$this->input->post('calendar_range'));
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);

        // $args['sales_id'] = array('use'=>'like','val'=>$ref); 
        // $args['trans_ref'] = array('use'=>'or_like','val'=>$ref);
        $args['type_id'] = array('use'=>'where','val'=>10,'third'=>false); 
        $args['trans_sales.terminal_id'] = array('use'=>'where','val'=>TERMINAL_ID,'third'=>false); 
        // $args['trans_ref'] = array('use'=>'or_like','val'=>$ref);
        $args["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false); 
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false); 
        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        $results = $this->site_model->get_tbl('trans_sales',$args,array('trans_sales.datetime'=>'desc'),null,true,'trans_ref,sales_id,datetime,total_amount');
        $code = "";
        $ids = array();

        // echo $this->site_model->db->last_query(); die();
        // var_dump($results); die();

        // $this->make->sDiv(array('class'=>'list-group'));
        foreach($results as $val){
            // $this->view($val->sales_id);
            $print = $this->print_sales_receipt_justin($val->sales_id,false,true,false,null,true,1,0,null,true);   
            echo "<pre style='background-color:#fff'>";
                echo $print;
            echo "</pre>"; 
        }

    }

    public function printAll(){
        // $date = $this->input->post('calendar_range');
        // start_load(0);
        $this->db = $this->load->database('main', TRUE);
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        $dates = explode(" to ",$this->input->post('calendar_range'));
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);

        $args['type_id'] = array('use'=>'where','val'=>10,'third'=>false); 
        // $args['trans_ref'] = array('use'=>'or_like','val'=>$ref);
        $args["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false); 
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false); 
        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false); 
        $results = $this->site_model->get_tbl('trans_sales',$args,array('trans_sales.datetime'=>'desc'),null,true,'trans_ref,sales_id,datetime,total_amount');
        $code = "";
        $ids = array();

        // update_load(5);
        // sleep(1);

        // echo $this->db->last_query(); //die();
        // var_dump($results); die();

        // $this->make->sDiv(array('class'=>'list-group'));
        $filepath = 'C:/RECEIPT';
        if (!file_exists($filepath)) {   
            mkdir($filepath, 0777, true);
        }
        $ctr = 5;     
        // echo count($results);die();


        foreach($results as $val){
            set_time_limit(30);
            // sleep(29);
            // echo "<pre>",print_r($val),"</pre>";die();
            // echo $val->trans_ref."\r\n";
            // $this->view($val->sales_id,false);

            // $ctr++;
            // if($ctr > 99){
            //     update_load(99);
            // }else{
            //     update_load($ctr);
            // }
            $filename =   str_replace(':', '_', $from."-".$to.".jrn") ; //$val->trans_ref."-".date('Y-m-d',strtotime($val->datetime)).".jrn";
            $text = $filepath."/".$filename;

            $print = $this->print_sales_receipt_btrack($val->sales_id,false,true,false,null,true,1,0,null,true);
// var_dump($print);die();
            $fp = fopen($text, "a+");
            fwrite($fp,$print);
            fclose($fp);

            // update_load($ctr);



            // die();
        }
        // die();
        update_load(100);

    }

    //////new reprint
    public function reprint_receipt(){
        $data = $this->syter->spawn('act_receipts2');
        $data['page_title'] = 'Receipts';
        $data['code'] = printsPage2();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/reprint';
        $data['use_js'] = 'printReceipt2Js';
        $this->load->view('page',$data);
    }
    public function results_reprint(){
        // $ref = $this->input->post('receipt');
        // $args['sales_id'] = array('use'=>'like','val'=>$ref); 
        // $args['trans_ref'] = array('use'=>'or_like','val'=>$ref); 
        // $results = $this->site_model->get_tbl('trans_sales',$args,array('trans_sales.datetime'=>'desc'),null,true,'trans_ref,sales_id,datetime,total_amount');

        $payment_type = $this->input->post('payment_type');
        $dates = explode(" to ",$this->input->post('calendar_range'));
        $from = date2Sql($dates[0]);        
        $to = date2Sql($dates[1]);
        
        $args["DATE(trans_sales.datetime) >='".$from."' and DATE(trans_sales.datetime) <= '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        if($payment_type){
            $args['trans_sales_payments.payment_type'] = $payment_type;
        }
        $args['trans_sales.type_id'] = 10;
        $args['trans_sales.inactive'] = 0;
        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $select = 'trans_ref,trans_sales.sales_id,trans_sales.datetime,total_amount';
        $join['trans_sales_payments'] = 'trans_sales_payments.sales_id=trans_sales.sales_id';
        $results = $this->site_model->get_tbl('trans_sales',$args,array('trans_sales.datetime'=>'desc'),$join,true,$select);

        // echo $this->site_model->db->last_query(); die();
        // echo "<pre>", print_r($results), "</pre>";die();

        $code = "";
        $ids = array();

        $this->make->sDiv(array('class'=>'list-group'));
        foreach ($results as $res) {
            $this->make->append('<a href="#" id="rec-'.$res->sales_id.'" class="rec list-group-item">');
                $this->make->sDiv();
                    $this->make->H(6,'Order No. <span class="pull-right"> total: '.$res->total_amount.'</span> '.$res->sales_id,array('style'=>'font-size:14px;margin:2px;'));
                $this->make->eDiv();
                    $this->make->p('Receipt No. '.$res->trans_ref.'<span class="pull-right">'.sql2Datetime($res->datetime).'</span>',array('style'=>'font-size:12px;margin:2px;'));
            $this->make->append('</a>');
            $ids[] = $res->sales_id;
        }
        $this->make->eDiv();
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$ids));
    }

    public function view_receipt($sales_id=null){
        $this->data['code'] = makeViewform($sales_id);
        $this->data['load_js'] = 'dine/reprint';
        $this->data['use_js'] = 'viewReceiptJS';
        $this->load->view('load',$this->data);

        // echo $sales_id; die();

        // $this->load->library('make');

        // $this->make->sDivRow();
        //     $this->make->sDivCol(12);
        //         $this->make->sBox('default',array('class'=>'box-solid'));
        //             // $CI->make->sBoxHead();
        //             //  $CI->make->button(fa('fa-print').' Print',array('id'=>'print-btn','class'=>'pull-right','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'),'success');
        //             // $CI->make->eBoxHead();
        //             $this->make->hidden('sales_id',$sales_id);
        //             $this->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
        //                 $this->make->sDiv(array('id'=>'print-div','style'=>'margin:0 auto;position:relative;width:310px;'));
        //                     $print = $this->print_sales_receipt($sales_id,false,true,false,null,true,1,0,null,false);   
        //                     echo "<pre style='background-color:#fff'>";
        //                         echo $print;
        //                     echo "</pre>"; 
        //                 $this->make->eDiv();
        //             $this->make->eBoxBody();
        //         $this->make->eBox();
        //     $this->make->eDivCol();
        // $this->make->eDivRow();

        // $this->make->code();

    }

    public function view_receipt_paid($sales_id=null,$noPrint=true){
        
        $reprint = false;
        // $noPrint = true;

        $print = $this->print_sales_receipt_temp($sales_id,false,$noPrint,$reprint,null,true,1,0,null,false);   
        echo "<pre style='background-color:#fff'>";
            echo $print;
        echo "</pre>"; 
    }

}