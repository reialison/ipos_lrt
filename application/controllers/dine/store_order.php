<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Store_order extends CI_Controller {
    public function index(){
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('icon-arrow-down')." Store Order";
        $th = array('Reference','Deliver To','Delivery Date','Approved By','Date Approved','Status','');
        $data['code'] = create_rtable('trans_receivings','receiving_id','store_order-tbl',$th,null,false,'list');
        // $data['code'] = create_rtable('trans_receivings','receiving_id','main-tbl',$th,'receiving/search',false,'list');
        $data['load_js'] = 'dine/store_order.php';
        $data['use_js'] = 'storeOrderListJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }

    public function get_store_order($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $this->load->model('core/store_order_model');
        $this->site_model->db = $this->load->database('default', TRUE);
        $status = null;
        $pagi = null;
        $args = array();
        $total_rows = 30;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();      
        $url    =  'store_order/get_store_order';
        $table  =  'sales_orders';
        $select =  '*';
        // $join['suppliers'] = 'trans_receivings.supplier_id=suppliers.supplier_id';
        // $join['users'] = 'trans_receivings.user_id=users.id';
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        // if(isset($post['trans_ref'])){
        //     $lk = $post['trans_ref'];
        //     $args["(".$table.".trans_ref like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        // }
        // if(isset($post['supplier_id'])){
        //     $args["trans_receivings.supplier_id"] = $post['supplier_id'];
        // }
        if(isset($post['void'])){
            $args["sales_order.inactive"] = $post['void'];
        }
        $count = $this->site_model->get_tbl($table,$args,array(),null,true,$select,"store_trans_ref",null,true);
        $page = paginate($url,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,array(),null,true,$select,"store_trans_ref",$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            // echo "<pre>",print_r($items),"</pre>";die();
            foreach ($items as $res) {
                $view = $this->make->A(fa('fa fa-eye fa-lg').' View','receiving/view_receiving_details/'.$res->sales_order_id,array(
                                        'class'=>'btn blue btn-sm btn-outline view',
                                        'rata-title'=>'View '.$res->store_trans_ref,
                                        'ref'=>$res->sales_order_id,
                                        // 'id'=>'view-item-'.$val->counter,
                                        'return'=>'false'
                                    ));
                $void = "";
                $status = "Pending";
                
                $reference_check = $this->store_order_model->check_store_order($res->reference);
                if(isset($reference_check[0]->trans_ref) && !empty($reference_check[0]->trans_ref)){
                    $status = "Received";
                }


                $approve = $this->make->A(fa('fa-file-pdf-o fa-lg').' PDF','#',array('class'=>'btn blue btn-sm btn-outline print_pdf','id'=>'print_pdf-'.$res->sales_order_id,'ref'=>$res->sales_order_id,'return'=>'true','action'=>1) );
                $deny = $this->make->A(fa('fa-close fa-lg').' Deny','#',array('class'=>'btn red btn-sm btn-outline deny','id'=>'deny-'.$res->sales_order_id,'ref'=>$res->sales_order_id,'return'=>'true','action'=>2));
                $json[] = array(
                    "id"=>$res->store_trans_ref,   
                    "del_to"=>$res->deliver_to, 
                    "del_date"=>( (empty($res->del_date) || $res->del_date=='1970-01-01' || $res->del_date=='0000-00-00')? "":sql2Date($res->del_date)),
                    "approve_by"=>$res->approve_by,
                    "approve_date"=>(empty($res->approve_date)?"":sql2Date($res->approve_date)),
                    "status"=>$status,
                    "action"=> $approve
                    // "inactive"=>""
                );
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }

    public function change_status($id,$action = 0){
        // $this->site_model->db = $this->load->database('bir', TRUE);
        $this->load->model('core/store_order_acc');
        $user = $this->session->userdata('user');
        $dateNow = $this->store_order_acc->get_date_now();
        $items = array(
            "sales_order_id"=>$id,
            "approve_status"=>$action,
            "approve_by"=>$user['full_name'],
            "approve_date"=>$dateNow
        );
        $this->store_order_acc->change_status_so($items, $id);
        if($action == 1 OR $action == 2){
            $msg = "Succesfully Update The status";
            $src = $target;
            site_alert($msg,'success');
        }
        else{
            $mg = "Something went wrong.";
            $upload = "fail";
            site_alert($mg,'error');
        }
        // $this->site_model->db = $this->load->database('default', TRUE);
        echo json_encode("");
    }

    public function form(){
        // die("aaa");
        $this->load->model('core/trans_model');
        $this->load->helper('dine/store_order_helper');
        sess_clear('rec_cart');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('icon-arrow-down')." Store Order Entry";
        $next_ref = $this->trans_model->get_next_ref(STORE_ORDER);
        $data['code'] = storeOrderForm($next_ref);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/store_order.php';
        $data['use_js'] = 'storeFormJS';
        $this->load->view('page',$data);
    }
    public function get_items($row=0){
        // die("aaa");
        $this->load->helper('dine/store_order_helper');
        $codes = get_item_form($row);
         echo json_encode($codes);
    }
    public function get_details_item($item_id=null){
        // die("aaa");
        $this->load->model('core/store_order_model');
        $item_det = $this->store_order_model->item_details($item_id);
        echo json_encode(array("item_code"=>$item_det[0]->code,"item_name"=>$item_det[0]->name,"item_uom"=>$item_det[0]->uom,"item_price"=>$item_det[0]->cost));
        // echo json_encode($item_det);
    }
    public function so_save(){
        $this->load->model('core/store_order_model');
        $this->load->model('core/trans_model');
        $this->load->model('core/ref_model');

        $this->store_order_model->db->trans_start();
        $dateNow = $this->store_order_model->get_date_now();
        $user = $this->session->userdata('user');
        // echo "<pre>",print_r($next_ref),"</pre>";die();
        $next_ref = $this->trans_model->get_next_ref(STORE_ORDER);
        // $refs = $this->ref_model->save_next($form_type,$this->input->post('reference'));
        $ord_date  = date2Sql($this->input->post('ord_date'));
        $del_date  = date2Sql($this->input->post('delivery_date'));
        
        $this->load->model('core/store_order_acc');
        // $this->trans_model->db = $this->load->database('bir', TRUE);
        $ref  = $this->ref_model->get_next_ref(T_SSO);
        $refs = $this->ref_model->save_next(T_SSO,$ref);

        $cust_branch = $this->store_order_acc->get_cust_branch(BRANCH_CODE);
        $cust_details = $this->store_order_acc->get_customer_details($cust_branch[0]->branch_code);
        // echo "<pre>",print_r($cust_branch),"</pre>";die();


        $items=array(               
            'debtor_no'         => $cust_branch[0]->debtor_no, // fetch
            'debtor_name'       => $cust_branch[0]->name,   // fetch
            'branch_code'       => $cust_branch[0]->branch_code, // fetch
            // 'branch_name'       => $cust_branch[0]->name,
            'reference'         => $ref,
            'trans_ref'         => $refs['id'],
            'payment_term_id'   => $cust_details[0]->payment_terms,
            'sales_type'        => 1,
            'salesman'          => $cust_branch[0]->salesman,
            'ord_date'          => $ord_date,
            'from_loc'          => $cust_branch[0]->default_location,
            'del_date'          => null,
            'deliver_to'        => $cust_branch[0]->br_name,
            'delivery_address'  => $cust_details[0]->address,
            // 'contact_phone'     => ,
            'cust_ref'          => "NA",
            'comments'          => $this->input->post('comments'),
            'ship_via'          => $cust_branch[0]->default_ship_via,
            'ship_cost'         => 0,
            'subtotal'          => $this->get_total_amount($this->input->post('item_price'),$this->input->post('item_qty')),
            'total_amount'      => $this->get_total_amount($this->input->post('item_price'),$this->input->post('item_qty')),
            'total_disc'        => 0,
            'type_id'           => 27,//T_INVOICE
            'store_trans_ref'   => $next_ref,
            // 'user_id'           => $user_id,
            'create_date'       => $dateNow,
            'request_name'      => $user['full_name'],
            'request_id'        => $user['id']
        );          

        $this->trans_model->db = $this->load->database('main', TRUE);
        $id_main = $this->store_order_model->add_so_main($items);//saving of SO in main
        
        unset($items['request_id']);
        $this->trans_model->db = $this->load->database('bir', TRUE);
        $items['branch_name'] = $cust_branch[0]->name;
        $id_acc = $this->store_order_acc->add_so_accounting($items);//Saving of SO in accounting
        $items['sales_order_id'] = $id_acc;
        $item_det = array(
            "item_name"     =>$this->input->post('item_name'),
            "item_id"       =>$this->input->post('item_id'),
            "item_qty"      =>$this->input->post('item_qty'),
            "item_uom"      =>$this->input->post('item_uom'),
            "item_price"    =>$this->input->post('item_price'),
            );
        // echo "<pre>",print_r($item_det),"</pre>";die();
        $this->add_debtor_trans($items,$item_det);
        $this->item_det_save($item_det,$id_acc,$id_main);
        $this->set_taxes($items);


        $this->store_order_model->db->trans_complete();
        $this->trans_model->db = $this->load->database('default', TRUE);
        $this->trans_model->save_ref(STORE_ORDER,$next_ref);


    }

    public function item_det_save($item_det_raw=array(),$id_acc=null,$id_main=null){

        $this->load->model('core/store_order_model');
        // $this->load->model('core/store_order_acc');
        $this->load->model('core/trans_model');
        
        // echo "<pre>",print_r($item_det_raw["item_name"]),"</pre>";die();
        $a = count($item_det_raw["item_name"]);
        $n = 0;

        // echo $a;
        for ($x = 1; $x <= $a; $x++) {
            // echo $x."a";
            $item_det[] = array(
                "sales_order_id"    => $id_acc,
                "sales_kit"         =>0,
                "item_code"         =>$item_det_raw['item_id'][$n],
                "name"              =>$item_det_raw['item_name'][$n],
                "uom"               =>$item_det_raw['item_uom'][$n],
                "unit_price"        =>$item_det_raw['item_price'][$n],
                "qty"               =>$item_det_raw['item_qty'][$n],
                "subtotal"          =>$item_det_raw['item_qty'][$n] * $item_det_raw['item_price'][$n]
                );

             $item_det_main[] = array(
                "sales_order_id"    => $id_main,
                "sales_kit"         =>0,
                "item_code"         =>$item_det_raw['item_id'][$n],
                "name"              =>$item_det_raw['item_name'][$n],
                "uom"               =>$item_det_raw['item_uom'][$n],
                "unit_price"        =>$item_det_raw['item_price'][$n],
                "qty"               =>$item_det_raw['item_qty'][$n],
                "subtotal"          =>$item_det_raw['item_qty'][$n] * $item_det_raw['item_price'][$n]
                );
            $n++;//for the index of the array
        }
        // die();
        $this->store_order_model->db->trans_start();
        $dateNow = $this->store_order_model->get_date_now();
        $user = $this->session->userdata('user');

        $this->store_order_acc->add_item_det_bulk_acc($item_det);
        $this->trans_model->db = $this->load->database('default', TRUE);
     
        $this->store_order_model->add_item_det_bulk_main($item_det_main);


        $this->store_order_model->db->trans_complete();
    }
    public function add_debtor_trans($items=array(),$item_det=array()){
        // echo "<pre>",print_r($items),"</pre>";die();
        $this->load->model('core/store_order_model');
        // $this->load->model('core/store_order_acc');
        $this->load->model('core/trans_model');
        
        // echo "<pre>",print_r($item_det_raw["item_name"]),"</pre>";die();

        // echo $a;
        $debtor_trans = array(
            // "trans_no"   => $so->trans_ref,
            "type_id"       => $items['type_id'],
            "trans_ref"     => $items['trans_ref'],
            "debtor_no"     => $items['debtor_no'],
            "order_"        => $items['sales_order_id'],
            "reference"     => $items['reference'],
            "amount"        => $items['total_amount'] - $items['ship_cost'],
            "tax"           => 0,//$this->get_total_tax(),
            "cwt"           => 0,
            "ship_cost"     => $items['ship_cost'],
            "discount"      => $items['total_disc'],
            "memo_"         => $items['comments'],
            "trans_date"    => $items['ord_date'],
            "due_date"      => $items['del_date'],
            "staff_id"      => 1,//$this->session->userdata('user_id'),
            "src_id"        => "" //$debtor_trans_ref
        );
        // die();
        $debtor_id = $this->store_order_acc->add_to_debtor_trans($debtor_trans);

        $debtor_details = array();
        $moveBatch      = array();

        $tr = 0;
        $taxRates = 0;
        $taxRates = $this->store_order_acc->get_cust_tax($items['branch_code']);
        if($taxRates){
            $tr = $taxRates[0]->tax_rate;
        }

        $item_posting = 0;          
        $total_amount = 0;
        $tax_amount = 0;
    
        $tax = ($tr * 0.01) + 1;



        $a = count($item_det["item_name"]);
        $n = 0;
        
        for ($x = 1; $x <= $a; $x++) {
            $matCosts = $this->store_order_acc->get_item_column(array("material_cost","unit","description"),$item_det['item_id'][$n]);
            $mat = $matCosts->result();

            $unit_price = 0;
            $unit_price = $item_det['item_price'][$n];
            $unit_price     = $unit_price;
            $qty            = num($item_det['item_qty'][$n],2,'.','');                
            $amount         = $qty * $unit_price;
            $discount       = 0;
            $subtotal       = $amount;
            $tax_val        = ($amount / $tax) * ($tr * 0.01);
            $unit_tax       = ($unit_price/$tax) * ($tr * 0.01);
            $unit_discount  = 0;    

            $item_posting   = $amount / $tax;
            $total_amount   += $amount;
            $tax_amount     += round($tax_val,2);

            $dets = array(
                'debtor_trans_no'   => $debtor_id,
                'stock_id'          => $item_det['item_id'][$n],
                'unit_price'        => $unit_price,
                'unit_tax'          => $unit_tax,
                'cwt'               => 0,
                'discount'          => $discount,
                'quantity'          => $qty
            );
            $dets["std_cost"] = $mat[0]->material_cost;
            $debtor_details[]=$dets;

 
            $n++;//for the index of the array
        }
        $this->store_order_acc->add_to_debtor_trans_details($debtor_details);

    }
    public function set_taxes($sales_order){
        $br_code = $sales_order['branch_code'];     

        $cust_branch = $this->store_order_acc->get_branch($br_code);
        $items = $this->store_order_acc->get_so_lists($sales_order['sales_order_id']);
// echo "<pre>",print_r($items),"</pre>";die();

        $total_tax  = 0;
        $total_amount = 0;
        $subtotal = 0;
        $net_amount = 0;
// print_r($items);exit;
        if($cust_branch){
            $taxRates = $this->store_order_acc->get_tax_group_rate($cust_branch[0]->tax_group_id);

            foreach($taxRates as $each){
                $each_tax = 0;
                $tax = $each->tax_rate / 100;
                $subtotal = 0;
                foreach($items as $item){
                    $each_tax += round(($item->subtotal/ ($tax+1)) * $tax,2);
                    $net_amount += round($item->subtotal / ($tax+1),2);

                    $total_tax += round(($item->subtotal/ ($tax+1)) * $tax,2);

                    $total_amount = $item->subtotal;
                    $subtotal = $item->subtotal;
                }

                $items = array(
                    'trans_type' => $sales_order['type_id'],
                    'trans_no'   => $sales_order['trans_ref'],
                    'tran_date'  => $sales_order['ord_date'],
                    'tax_type_id'=> $each->tax_type_id,
                    'rate'       => $each->tax_rate ,
                    'net_amount' => $net_amount,
                    'amount'     => $each_tax 
                );
                //need to upddate debtor_trans total_tax;
                $this->store_order_acc->add_trans_tax($items);
            }
        }

        $debtor_trans = array('tax'=>$total_tax);
        $this->store_order_acc->update_debtor_trans($sales_order['sales_order_id'],$debtor_trans);
        // return $total_tax;
    }

    public function get_total_amount($item_det,$qty){
        $total = 0;
        $i=0;
        // echo "<pre>",print_r($item_det),"</pre>";
        //         echo "<pre>qty: ",print_r($qty),"</pre>";

        foreach ($item_det as $key=>$values) {
            // $qty[$i];

        //die();
            if(isset($qty[$key])){
                $val_qty = $qty[$key];
            }else{
                $val_qty = 1;
            }
            $total += $values * $val_qty;
            // $n++;//for the index of the array
        }

        return $total;
    }
    public function get_dr_details(){
        $this->load->model('core/store_order_model');
        $post_values = $this->input->post();
        $item_details = array();
        if(isset($post_values['ref']) && !empty($post_values['ref'])) {
            $dr_ref = $post_values['ref'];
         // $dr_ref = 'COGS-000009';
             $item_details = $this->store_order_model->get_dr_details($dr_ref);
        }

        echo json_encode(array('item_details'=>$item_details , 'item_ctr'=>count($item_details)));
        // echo "<pre>",print_r($post_values),"</pre>";die();
    }
    public function store_menu_pdf($rec_id)
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
        $pdf->SetTitle('Receving Details');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');

        // set default header data
        // $setup = $this->setup_model->get_details(1);
        // $set = $setup[0];
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $set->branch_name, $set->address);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
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
        $this->load->model("dine/menu_model");
        $this->menu_model->db = $this->load->database('default', TRUE);
        $this->site_model->db = $this->load->database('default', TRUE);

        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        // $rec_id = $_GET['rec_id'];

        $table  = "sales_orders";
        $select = "sales_orders.*";
        $args = array();
        $join = array();
        $args['sales_orders.sales_order_id'] = $rec_id;
        // $join["suppliers"] = array('content'=>"suppliers.supplier_id = trans_receiving_menu.supplier_id");
        // $join = null;
        $group = null;
        $order = array();
        $headers = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
        // echo $this->db->last_query();die();                  


        $table  = "sales_order_details";
        $select = "sales_order_details.*";
        $args = array();
        $join = array();
        $args['sales_order_details.sales_order_id'] = $rec_id;
        // $join["menus"] = array('content'=>"trans_receiving_menu_details.item_id = menus.menu_id");
        $group = null;
        $order = array();
        $details = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);     
       
        // $trans_payment = $this->menu_model->get_payment_date($from, $to);
        // echo "<pre>",print_r($headers),"</pre>";die();


        $pdf->Write(0, 'Store Order Details', '', 0, 'L', true, 0, false, false, 0);
        $pdf->ln(3);              
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(180, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        // $pdf->Write(0, 'Supplier:    ', '', 0, 'L', false, 0, false, false, 0);
        // $pdf->Write(0, $headers[0]->supp_name, '', 0, 'L', false, 0, false, false, 0);
        // $pdf->setX(130);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Date & Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, date('m/d/Y H:i:s',strtotime($headers[0]->create_date)), '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(130);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(180, 0, '', 'T', 0, 'C');
        $pdf->ln(3);              

        // echo "<pre>", print_r($trans), "</pre>";die();

        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(80, 0, 'Item', 'B', 0, 'L');        
        $pdf->Cell(25, 0, 'Quantity', 'B', 0, 'R');        
        // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(25, 0, 'UOM', 'B', 0, 'C');        
        $pdf->Cell(25, 0, 'Cost', 'B', 0, 'R');        
        $pdf->Cell(25, 0, 'Total', 'B', 0, 'R');               
        $pdf->ln(6); 

        $pdf->SetFont('helvetica', '', 9);
        $total = 0;


        foreach($details as $val){
            $pdf->Cell(80, 0, $val->name, '', 0, 'L');        
            $pdf->Cell(25, 0, $val->qty, '', 0, 'R');        
            // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
            // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
            $pdf->Cell(25, 0, $val->uom, '', 0, 'C');        
            $pdf->Cell(25, 0, num($val->unit_price), '', 0, 'R');        
            $tot = $val->qty * $val->unit_price;
            $pdf->Cell(25, 0, num($tot), '', 0, 'R');               
            $pdf->ln(); 

            $total += $tot;
        } 

        $pdf->ln(2);              
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(80, 0, 'TOTAL', 'T', 0, 'L');        
        $pdf->Cell(25, 0, '', 'T', 0, 'R');        
        // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(25, 0, '', 'T', 0, 'C');        
        $pdf->Cell(25, 0, '', 'T', 0, 'R');        
        // $tot = $val->qty * $val->price;
        $pdf->Cell(25, 0, num($total), 'T', 0, 'R');               
        $pdf->ln();              


        //Close and output PDF document
        $pdf->Output('sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }
}