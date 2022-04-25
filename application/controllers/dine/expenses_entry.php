<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Expenses_entry extends CI_Controller {
    public function index(){
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('icon-arrow-down')." Expenses Entry";
        $th = array('Reference',"Memo",'Date','Status','');
        $data['code'] = create_rtable('expenses_entry','receiving_id','expenses_entry-tbl',$th,null,false,'list');
        // $data['code'] = create_rtable('trans_receivings','receiving_id','main-tbl',$th,'receiving/search',false,'list');
        $data['load_js'] = 'dine/expenses_entry.php';
        $data['use_js'] = 'expensesEntryJS';
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
        $table  =  'expenses_entry';
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
        $count = $this->site_model->get_tbl($table,$args,array(),null,true,$select,"trans_ref",null,true);
        $page = paginate($url,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,array(),null,true,$select,"trans_ref",$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            // echo "<pre>",print_r($items),"</pre>";die();
            foreach ($items as $res) {
                $view = $this->make->A(fa('fa fa-eye fa-lg').' View','receiving/view_receiving_details/'.$res->id,array(
                                        'class'=>'btn blue btn-sm btn-outline view',
                                        'rata-title'=>'View '.$res->id,
                                        'ref'=>$res->id,
                                        // 'id'=>'view-item-'.$val->counter,
                                        'return'=>'false'
                                    ));
                $void = "";
                $status = "Pending";
                
                // $reference_check = $this->store_order_model->check_store_order($res->reference);
                // if(isset($reference_check[0]->trans_ref) && !empty($reference_check[0]->trans_ref)){
                //     $status = "Received";
                // }


                $pdf = $this->make->A(fa('fa-file-pdf-o fa-lg').' PDF','#',array('class'=>'btn blue btn-sm btn-outline print_pdf','id'=>'print_pdf-'.$res->id,'ref'=>$res->id,'return'=>'true','action'=>1) );
                $deny = $this->make->A(fa('fa-close fa-lg').' Deny','#',array('class'=>'btn red btn-sm btn-outline deny','id'=>'deny-'.$res->id,'ref'=>$res->id,'return'=>'true','action'=>2));
                $json[] = array(
                    "id"=>$res->reference,
                    "memo"=>$res->memo, 
                    "date"=>$res->trans_date, 
                    "status"=>($res->inactive == 1? "INACTIVE":"ACTIVE"),
                    "view"=>$pdf
                    // "approve_date"=>(empty($res->reg_date)?"":sql2Date($res->reg_date)),
                );
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }

    public function change_status($id,$action = 0){
        // $this->site_model->db = $this->load->database('bir', TRUE);
        $this->load->model('core/store_order_acc');
        $this->load->model('core/expenses_model');
        $user = $this->session->userdata('user');
        $dateNow = $this->store_order_acc->get_date_now();
        $items = array(
            "id"=>$id,
            "inactive"=>$action,
            "date_updated"=>$dateNow

        );
        $this->expenses_model->db = $this->load->database('default', TRUE);
        $this->expenses_model->change_status_ex($items, $id);
        $this->expenses_model->db = $this->load->database('main', TRUE);
        $this->expenses_model->change_status_ex($items, $id);
        if($action == 0 OR $action == 1){
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
        $this->load->model('core/trans_model');
        $this->load->helper('dine/expenses_helper');
        sess_clear('rec_cart');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('icon-arrow-down')." Expenses Entry Form";
        $next_ref = $this->trans_model->get_next_ref(EXPENSE_ENTRY);
        // echo "<pre>",print_r($next_ref),"</pre>";die();
        $data['code'] = expensesForm($next_ref);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/expenses_entry.php';
        $data['use_js'] = 'expenseFormJS';
        $this->load->view('page',$data);
    }
    public function get_expense_items($row=0){
        $this->load->helper('dine/expenses_helper');
        $codes = expense_item($row);
         echo json_encode($codes);
    }

    public function item_form($ref=null){//when adding items in expenses
        // die();
        $this->load->helper('dine/expenses_helper');
        $this->load->model('core/expenses_model');
        $data_raw = $this->expenses_model->item_details($ref);
        $data = null;
        if(!empty($data_raw)){
            $data = $data_raw[0];
        }
        // echo "<pre>",print_r($data),"</pre>";
        // die();
        $this->data['code'] = expenses_item_form($data);
        $this->load->view('load',$this->data);
    }
    public function add_item_db(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $this->load->model('core/store_order_model');
        $this->load->model('core/expenses_model');
        $user = $this->session->userdata('user');
        $dateNow = $this->store_order_model->get_date_now();
        $items = array();
        $error = "";

        $items = array(
            "expenses_code"=>$this->input->post('code'),
            "expenses_name"=>$this->input->post('name'),
            "expenses_desc"=>$this->input->post('desc'),    
            "expenses_unit"=>$this->input->post('uom'),
            "expenses_price"=>$this->input->post('price'),
            "added_by"=>$user['id'],
            "inactive"=>0,
            "date_added"=>$dateNow,

        );
        // echo "<pre>",print_r($items),"</pre>";die();

        if($this->input->post('expense_id')){
                // die("aaa");
                $id = $this->input->post('expense_id');
                $act = 'update';
                $msg = 'Updated Expenses Item '.$this->input->post('expense_code');

                $this->expenses_model->db = $this->load->database('default', TRUE);
                $this->expenses_model->update_tbl('expenses_items','id',$items,$id);
                $this->expenses_model->db = $this->load->database('main', TRUE);
                $this->expenses_model->update_tbl('expenses_items','id',$items,$id);
                site_alert($msg,'success');
        }else{
                $act = 'add';
                $msg = 'Added  new Expenses item '.$this->input->post('code');

                $this->expenses_model->db = $this->load->database('default', TRUE);
                $this->expenses_model->add_trans_tbl('expenses_items',$items);
                $this->expenses_model->db = $this->load->database('main', TRUE);
                $this->expenses_model->add_trans_tbl('expenses_items',$items);
                site_alert($msg,'success');
        }
        // echo json_encode(array("id"=>$id,"addOpt"=>$items['menu_cat_name'],"desc"=>$this->input->post('menu_cat_name'),"act"=>$act,'msg'=>$msg,'error'=>$error));
        // echo json_encode(array('error'=>$error));

    }
    public function add_expenses(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $this->load->model('core/trans_model');
        $this->load->model('core/store_order_model');
        $this->load->model('core/expenses_model');
        $user = $this->session->userdata('user');
        $dateNow = $this->store_order_model->get_date_now();
        $next_ref = $this->trans_model->get_next_ref(EXPENSE_ENTRY);
        $items = array();
        $error = "";

        // echo "<pre>",print_r($this->input->post('item_price')),"</pre>";die();
        $items = array(
            "type_id"=>EXPENSE_ENTRY,
            "trans_ref"=>$next_ref,
            "reference"=>$next_ref,
            "user_id"=>$user['id'],    
            // "user_name"=>$user['id'],
            "unit_price"=>$this->get_total_amount($this->input->post('item_price'),$this->input->post('item_qty')),
            "memo"=>$this->input->post('comments'),
            "trans_date"=>date2Sql($this->input->post('ord_date')),//days set in UI
            "reg_date"=>$dateNow,//regitered day 
            "inactive"=>0,

        );

        $item_det = array(
            "item_name"     =>$this->input->post('item_name'),
            "item_id"       =>$this->input->post('item_id'),
            "item_qty"      =>$this->input->post('item_qty'),
            "item_uom"      =>$this->input->post('item_uom'),
            "item_price"    =>$this->input->post('item_price'),
            );
        $this->expenses_model->db = $this->load->database('default', TRUE);
        $this->expenses_model->add_expenses_db($items);
        $this->expenses_model->db = $this->load->database('main', TRUE);
        $id = $this->expenses_model->add_expenses_db($items);
        $this->item_det_save($item_det,$id);

        $this->trans_model->db = $this->load->database('default', TRUE);
        $this->trans_model->save_ref(EXPENSE_ENTRY,$next_ref);

    }

    public function item_det_save($item_det_raw=array(),$id_main=null){

        $this->load->model('core/store_order_model');
        $this->load->model('core/trans_model');
        $a = count($item_det_raw["item_name"]);
        $this->load->model('core/expenses_model');

        $dateNow = $this->store_order_model->get_date_now();
        $n = 0;

        for ($x = 1; $x <= $a; $x++) {
             $item_det_main[] = array(
                "expenses_id"       => $id_main,
                "expenses_item_id"  =>$item_det_raw['item_id'][$n],
                "expenses_qty"      =>$item_det_raw['item_qty'][$n],
                "price"             =>$item_det_raw['item_qty'][$n] * $item_det_raw['item_price'][$n],
                "date_added"        =>$dateNow
                );
            $n++;//for the index of the array
        }
        // die();
        $this->expenses_model->db->trans_start();
        $this->expenses_model->db = $this->load->database('default', TRUE);
        $this->expenses_model->add_expenses_det($item_det_main);
        $this->expenses_model->db = $this->load->database('main', TRUE);
        $this->expenses_model->add_expenses_det($item_det_main);


        $this->expenses_model->db->trans_complete();
    }
    public function get_details_item($item_id=null){
        // die("aaa");
        $this->load->model('core/expenses_model');
        $item_det = $this->expenses_model->item_details($item_id);
        echo json_encode(array("item_code"=>$item_det[0]->expenses_code,"item_name"=>$item_det[0]->expenses_name,"item_uom"=>$item_det[0]->expenses_unit,"item_price"=>$item_det[0]->expenses_price));
        // echo json_encode($item_det);
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
    public function expenses_pdf($rec_id){
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Expenses Details');
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

        $table = "expenses_entry";
        $select = "expenses_entry.*";
        $args = array();
        $join = array();
        $args['expenses_entry.id'] = $rec_id;
        // $join["suppliers"] = array('content'=>"suppliers.supplier_id = trans_receiving_menu.supplier_id");
        // $join = null;
        $group = null;
        $order = array();
        $headers = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
        // echo $this->db->last_query();die();                  


        $table  = "expenses_details";
        $select = "expenses_details.*,expenses_items.expenses_name as item_name,expenses_items.expenses_code,expenses_items.expenses_price";
        $args = array();
        $join = array();
        $args['expenses_details.expenses_id'] = $rec_id;
        $join["expenses_items"] = array('content'=>"expenses_items.id = expenses_details.expenses_item_id");
        $group = null;
        $order = array();
        $details = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);     
       
        // $trans_payment = $this->menu_model->get_payment_date($from, $to);
        // echo "<pre>",print_r($headers),"</pre>";die();


        $pdf->Write(0, 'Expenses Details', '', 0, 'L', true, 0, false, false, 0);
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
        $pdf->Write(0, date('m/d/Y H:i:s',strtotime($headers[0]->reg_date)), '', 0, 'L', false, 0, false, false, 0);
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
        $pdf->Cell(40, 0, 'Code', 'B', 0, 'L');        
        $pdf->Cell(65, 0, 'Item', 'B', 0, 'L');       
        $pdf->Cell(25, 0, 'Quantity', 'B', 0, 'C');        
        $pdf->Cell(25, 0, 'Price', 'B', 0, 'R');        
        $pdf->Cell(25, 0, 'Subtotal', 'B', 0, 'R');               
        $pdf->ln(6); 

        $pdf->SetFont('helvetica', '', 9);
        $total = 0;


        foreach($details as $val){
            $pdf->Cell(40, 0,$val->expenses_code , '', 0, 'L');        
            $pdf->Cell(65, 0, $val->item_name, '', 0, 'L');
            // $pdf->Cell(25, 0, $val->expenses_qty, '', 0, 'C');        
            $pdf->Cell(25, 0, $val->expenses_qty, '', 0, 'C');        
            $pdf->Cell(25, 0, num($val->expenses_price), '', 0, 'R');        
            // $tot = $val->qty * $val->unit_price;
            $pdf->Cell(25, 0, num($val->price), '', 0, 'R');               
            $pdf->ln(); 

            $total += $val->price;
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
        $pdf->Output('expenses_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }
    public function expenses_items(){
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('icon-arrow-down')." Expenses Items";
        $th = array('Code',"Name",'UOM','Price','Status','');
        $data['code'] = create_rtable('expenses_entry','receiving_id','expenses_entry-tbl',$th,null,false,'list');
        // $data['code'] = create_rtable('trans_receivings','receiving_id','main-tbl',$th,'receiving/search',false,'list');
        $data['load_js'] = 'dine/expenses_entry.php';
        $data['use_js'] = 'expensesItemlistJS';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }

    public function get_expenses_items($id=null,$asJson=true){
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
        $table  =  'expenses_items';
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
        $count = $this->site_model->get_tbl($table,$args,array(),null,true,$select,"",null,true);
        $page = paginate($url,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,array(),null,true,$select,"",$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            // echo "<pre>",print_r($items),"</pre>";die();
            foreach ($items as $res) {
                $view = $this->make->A(fa('fa fa-eye fa-lg').' View','receiving/view_receiving_details/'.$res->id,array(
                                        'class'=>'btn blue btn-sm btn-outline view',
                                        'rata-title'=>'View '.$res->id,
                                        'ref'=>$res->id,
                                        // 'id'=>'view-item-'.$val->counter,
                                        'return'=>'false'
                                    ));
                $void = "";
                $status = "Pending";

                $edit = $this->make->A(fa('fa-edit fa-lg').' Edit','#',array('class'=>'btn blue btn-sm btn-outline edit','id'=>'edit-'.$res->id,'ref'=>$res->id,'return'=>'true','action'=>1) );
                if($res->inactive == 1){
                    $status_btn = $this->make->A(fa('fa-check fa-lg').' Activate','#',array('class'=>'btn blue btn-sm btn-outline deny','id'=>'deny-'.$res->id,'ref'=>$res->id,'return'=>'true','action'=>0));
                }else{
                    $status_btn = $this->make->A(fa('fa-close fa-lg').' Deactivate','#',array('class'=>'btn red btn-sm btn-outline deny','id'=>'deny-'.$res->id,'ref'=>$res->id,'return'=>'true','action'=>1));
                }

                $json[] = array(
                    "code"=>$res->expenses_code,
                    "name"=>$res->expenses_name, 
                    "UOM"=>$res->expenses_unit, 
                    "price"=>$res->expenses_price,
                    "status"=>($res->inactive == 1? "INACTIVE":"ACTIVE"),
                    "approve_date"=>$edit.' '.$status_btn
                );
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
}