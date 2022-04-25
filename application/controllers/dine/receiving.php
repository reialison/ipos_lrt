<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Receiving extends CI_Controller {
    public function index(){
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('icon-arrow-down')." Receiving";
        $th = array('Reference','Supplier','Received By','Received Date','');
        $data['code'] = create_rtable('trans_receivings','receiving_id','main-tbl',$th,null,false,'list');
        // $data['code'] = create_rtable('trans_receivings','receiving_id','main-tbl',$th,'receiving/search',false,'list');
        $data['load_js'] = 'dine/receive.php';
        $data['use_js'] = 'receiveListJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function get_receiving($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 30;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();      
        $url    =  'receiving/get_receiving';
        $table  =  'trans_receivings';
        $select =  'trans_receivings.*,suppliers.name as supplier_name,users.username as username';
        $join['suppliers'] = 'trans_receivings.supplier_id=suppliers.supplier_id';
        $join['users'] = 'trans_receivings.user_id=users.id';
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if(isset($post['trans_ref'])){
            $lk = $post['trans_ref'];
            $args["(".$table.".trans_ref like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if(isset($post['supplier_id'])){
            $args["trans_receivings.supplier_id"] = $post['supplier_id'];
        }
        if(isset($post['void'])){
            $args["trans_receivings.inactive"] = $post['void'];
        }
        $count = $this->site_model->get_tbl($table,$args,array(),$join,true,$select,null,null,true);
        $page = paginate($url,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,array(),$join,true,$select,null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                // $view = $this->make->A(fa('fa fa-eye fa-lg').' View','#',array('title'=>'View '.$res->trans_ref,
                //                                                        'ref'=>$res->receiving_id,
                //                                                        'class'=>'btn blue btn-sm btn-outline void',
                //                                                        'return'=>true));

                $view = $this->make->A(fa('fa fa-eye fa-lg').' View','receiving/view_receiving_details/'.$res->receiving_id,array(
                                        'class'=>'btn blue btn-sm btn-outline view',
                                        'rata-title'=>'View '.$res->trans_ref,
                                        // 'rata-pass'=>'sales/view_sales_order_details',
                                        // 'rata-form'=>'inv_adjustment_form',
                                        'ref'=>$res->receiving_id,
                                        // 'id'=>'view-item-'.$val->counter,
                                        'return'=>'false'
                                    ));

                $void = "";
                if($res->inactive == 0){
                    $inactive = "No";

                    $void = $this->make->A(fa('fa fa-times fa-lg').' Delete','#',array('title'=>'Void Trans '.$res->trans_ref,
                                                                       'ref'=>$res->receiving_id,
                                                                       'class'=>'btn red btn-sm btn-outline void',
                                                                       'return'=>true));
                }
                else
                    $inactive = "Yes";

                $json[] = array(
                    "id"=>$res->trans_ref,   
                    "title"=>ucwords(strtolower($res->supplier_name)),   
                    "desc"=>ucwords(strtolower($res->username)),   
                    "date"=>sql2Date($res->trans_date),  
                    "void"=>$void.' '.$view, 
                    "inactive"=>$inactive
                );
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function search(){
        $this->load->helper('dine/receiving_helper');
        $data['code'] = receivingSearch();
        $this->load->view('load',$data);
    }
    public function form(){
        $this->load->model('core/trans_model');
        $this->load->model('dine/receiving_model');
        $this->load->helper('dine/receiving_helper');
        sess_clear('rec_cart');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('icon-arrow-down')." Receiving";
        $ref = $this->trans_model->get_next_ref(RECEIVE_TRANS);
        $data['code'] = receivingFormPage($ref);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/receive.php';
        $data['use_js'] = 'receiveJs';
        $this->load->view('page',$data);
    }
    public function get_item_details($item_id=null,$asJson=true){
        $this->load->model('dine/receiving_model');
        $this->load->model('dine/items_model');
        $json = array();
        $items = $this->items_model->get_item($item_id);
        $item = $items[0];

        $json['item_id'] = $item->item_id;
        $json['uom'] = $item->uom;

        $opts = array();
        $opts[$item->uom] = $item->uom;
        if($item->no_per_pack > 0)
            $opts[$item->no_per_pack_uom.'(@'.$item->no_per_pack.' '.$item->uom.')'] = $item->uom."-".'pack-'.$item->no_per_pack;
        if($item->no_per_case > 0)
            $opts['Case(@'.$item->no_per_case.' Packs)'] = $item->uom."-".'case-'.$item->no_per_case;

        $json['opts'] =  $opts;
        $json['ppack'] = $item->no_per_pack;
        $json['ppack_uom'] = $item->no_per_pack_uom;
        $json['pcase'] = $item->no_per_case;
        echo json_encode($json);
    }
    public function save(){
        $this->load->model('dine/receiving_model');
        $this->load->model('dine/items_model');
        $this->load->model('core/trans_model');
        $user = $this->session->userdata('user');
        $rec_cart = $this->session->userdata('rec_cart');
        // echo "<pre>",print_r($rec_cart),"</pre>";die();
        // $next_ref = $this->trans_model->get_next_ref(RECEIVE_TRANS);
        $next_ref = $this->input->post('reference');
        $trans_time = date('H:i:s',strtotime($this->input->post('trans_time')));
        $items = array(
            "reference"=>$next_ref,
            "memo"=>$this->input->post('memo'),
            "trans_date"=>date2Sql($this->input->post('trans_date'))." ".$trans_time,
            "trans_ref"=>$next_ref,
            "type_id"=>RECEIVE_TRANS,
            "user_id"=>$user['id'],
            "delivered_by"=>$this->input->post('delivered_by'),
            "supplier_id"=>$this->input->post('suppliers')
        );
        $errors = "";
        if (empty($rec_cart)) {
            $errors = 'no item';
            echo json_encode(array('msg'=>"Please select an item first before proceeding",'error'=>$errors));
            return false;
        }
        $this->trans_model->db->trans_start();
            $count = $this->site_model->get_tbl('trans_receivings',array('trans_ref'=>$next_ref),array(),array(),true,'*',null,null,true);
            if($count){
                $errors = 'Reference used';
                echo json_encode(array('msg'=>"Reference ".$next_ref." is already used.",'error'=>$errors));
                return false;
            }

            $id = $this->receiving_model->add_trans_receivings($items);
            $prepared = $prepared_moves = array();
            $total = 0;
            $now = $this->site_model->get_db_now();
            // $datetime = date('Y-m-d H:i:s');
            $datetime = date2SqlDateTime($now);
            foreach ($rec_cart as $val) {
                $prepare = array(
                    'receiving_id' => $id,
                    'item_id'      => (int) $val['item-id'],
                    'case'         => null,
                    'pack'         => null,
                    'uom'          => $val['item-uom'],
                    'price'        => 0 //$val['cost']
                );
                $prepare_moves = array(
                    'type_id'  => RECEIVE_TRANS,
                    'trans_id' => $id,
                    'trans_ref'=> $next_ref,
                    'item_id'  => $val['item-id'],
                    'uom'      => $val['item-uom'],
                    'pack_qty' => null,
                    'case_qty' => null,
                    'reg_date' => date2Sql($this->input->post('trans_date'))." ".$trans_time,
                );
                $loc_id = explode('-', $val['loc_id']);
                $prepare_moves['loc_id'] = $loc_id[0];
                $last_stock = 0;
                $stocks = $this->items_model->get_latest_item_move(array('loc_id'=>$val['loc_id'],'item_id'=>$val['item-id']));
                if (!empty($stocks->curr_item_qty))
                    $last_stock = $stocks->curr_item_qty;
                if (strpos($val['select-uom'],'pack') !== false) {
                    $converted_qty = $val['qty'] * $val['item-ppack'];
                    $prepare['qty'] = (double) $converted_qty;
                    $prepare['pack'] = (double) $val['qty'];
                    $prepare_moves['qty'] = $converted_qty;
                    $prepare_moves['pack_qty'] = (double) $val['qty'];
                    $prepare_moves['curr_item_qty'] = $last_stock + $converted_qty;
                } elseif (strpos($val['select-uom'],'case') !== false) {
                    $converted_qty = $val['qty'] * $val['item-ppack'] * $val['item-pcase'];
                    $prepare['qty'] = (double) $converted_qty;
                    $prepare['case'] = (double) $val['qty'];
                    $prepare_moves['qty'] = $converted_qty;
                    $prepare_moves['case_qty'] = (double) $val['qty'];
                    $prepare_moves['curr_item_qty'] = $last_stock + $converted_qty;
                } else {
                    $prepare['qty'] = (double)$val['qty'];
                    $prepare_moves['qty'] = (double)$val['qty'];
                    $prepare_moves['curr_item_qty'] = (double)$val['qty'] + $last_stock;
                }
                $prepared[] = $prepare;
                $prepared_moves[] = $prepare_moves;
                $total += 0; //$val['cost'];
            }
            $this->receiving_model->add_trans_receiving_batch($prepared);
            $this->receiving_model->update_trans_receivings(array('amount'=>$total),$id);
            $this->items_model->add_item_moves_batch($prepared_moves);
            $this->trans_model->save_ref(RECEIVE_TRANS,$next_ref);
        $this->trans_model->db->trans_complete();
        $this->session->unset_userdata('rec_cart');
        site_alert($next_ref." processed",'success');

        echo json_encode(array('msg'=>$next_ref." processed",'error'=>$errors));
    }
    public function void($trans_id){
        $this->load->model('core/trans_model');
        $user = $this->session->userdata('user');
        $trans_type = RECEIVE_TRANS;
        $reason = $this->input->post('reason');
        $now = $this->site_model->get_db_now('sql');
        $this->trans_model->db->trans_start();
            $void = array(
                'trans_type'=>$trans_type,
                'trans_id'  =>$trans_id,
                'reason'    =>$reason,
                'reg_user'  =>$user['id'],
                'reg_date'  =>$now,
            );
            $this->site_model->add_tbl('trans_voids',$void);
            $this->site_model->update_tbl('trans_receivings','receiving_id',array('inactive'=>1,'update_date'=>$now),$trans_id);
            $this->site_model->update_tbl('item_moves',array('type_id'=>$trans_type,'trans_id'=>$trans_id),array('inactive'=>1));
        $this->trans_model->db->trans_complete();
        // echo json_encode(array('msg'=>"Transaction Voided"));
        site_alert("Transaction Voided",'success');
    }

    ///////////receiving menu
    public function receiving_menu(){
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('fa-download')." Item Receiving";
        $th = array('Reference','Received By','Received Date Time','');
        $data['code'] = create_rtable('trans_receiving_menu','receiving_id','main-tbl',$th,null,false,'list');
        $data['load_js'] = 'dine/receive.php';
        $data['use_js'] = 'receiveMenuListJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function get_receiving_menu($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 30;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();      

        $url    =  'receiving/get_receiving_menu';
        $table  =  'trans_receiving_menu';
        $select =  'trans_receiving_menu.*,users.username as username';
        // $join['suppliers'] = 'trans_receivings.supplier_id=suppliers.supplier_id';
        $join['users'] = 'trans_receiving_menu.user_id=users.id';
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if(isset($post['trans_ref'])){
            $lk = $post['trans_ref'];
            $args["(".$table.".trans_ref like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        // if(isset($post['supplier_id'])){
        //     $args["trans_receivings.supplier_id"] = $post['supplier_id'];
        // }
        if(isset($post['void'])){
            $args["trans_receiving_menu.inactive"] = $post['void'];
        }
        $count = $this->site_model->get_tbl($table,$args,array(),$join,true,$select,null,null,true);
        $page = paginate($url,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,array(),$join,true,$select,null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $view = $this->make->A(fa('fa fa-eye fa-lg'),'receiving/view_receiving_menu_details/'.$res->receiving_id,array(
                                        'class'=>'view',
                                        'rata-title'=>'View '.$res->trans_ref,
                                        // 'rata-pass'=>'sales/view_sales_order_details',
                                        // 'rata-form'=>'inv_adjustment_form',
                                        'ref'=>$res->receiving_id,
                                        // 'id'=>'view-item-'.$val->counter,
                                        'title'=>'View '.$res->trans_ref,
                                        'return'=>'false'
                                    ));

                $void = "";
                if($res->inactive == 0){
                    $inactive = "No";
                    $void = $this->make->A(fa('fa fa-times fa-lg'),'#',array('title'=>'Void Trans '.$res->trans_ref,
                                                                       'ref'=>$res->receiving_id,
                                                                       'class'=>'void',
                                                                       'return'=>true));
                }
                else
                    $inactive = "Yes";
                $json[] = array(
                    "id"=>$res->trans_ref,   
                    // "title"=>ucwords(strtolower($res->supplier_name)),   
                    "desc"=>ucwords(strtolower($res->username)),   
                    "date"=>date('m/d/Y h:i A',strtotime($res->trans_date)),  
                    "void"=>$void." ".$view, 
                    "inactive"=>$inactive
                );
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function search_menu(){
        $this->load->helper('dine/receiving_helper');
        $data['code'] = receivingMenuSearch();
        $this->load->view('load',$data);
    }
    public function form_menu(){
        $this->load->model('core/trans_model');
        $this->load->model('dine/receiving_model');
        $this->load->helper('dine/receiving_helper');
        sess_clear('rec_cart');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('fa-download')." Item Receiving";
        $ref = $this->trans_model->get_next_ref(RECEIVE_MENU_TRANS);
        $data['code'] = receivingMenuFormPage($ref);
        // $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        // $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css','js/plugins/typeaheadmap/typeaheadmap.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js','js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/receive.php';
        $data['use_js'] = 'receiveMenuJs';
        $this->load->view('page',$data);
    }
    public function save_menu(){
        $this->load->model('dine/receiving_model');
        $this->load->model('dine/items_model');
        $this->load->model('core/trans_model');
        $user = $this->session->userdata('user');
        $rec_cart = $this->session->userdata('rec_cart');
        // $next_ref = $this->trans_model->get_next_ref(RECEIVE_TRANS);
        $next_ref = $this->input->post('reference');
        $trans_time = date('H:i:s',strtotime($this->input->post('trans_time')));
        $items = array(
            "reference"=>$next_ref,
            "memo"=>$this->input->post('memo'),
            "trans_date"=>date2Sql($this->input->post('trans_date'))." ".$trans_time,
            "trans_ref"=>$next_ref,
            "type_id"=>RECEIVE_MENU_TRANS,
            "user_id"=>$user['id'],
            "supplier_id"=>null
        );
        $errors = "";
        if (empty($rec_cart)) {
            $errors = 'no menu';
            echo json_encode(array('msg'=>"Please select a menu first before proceeding",'error'=>$errors));
            return false;
        }
        $this->trans_model->db->trans_start();
            $count = $this->site_model->get_tbl('trans_receiving_menu',array('trans_ref'=>$next_ref),array(),array(),true,'*',null,null,true);
            if($count){
                $errors = 'Reference used';
                echo json_encode(array('msg'=>"Reference ".$next_ref." is already used.",'error'=>$errors));
                return false;
            }

            $id = $this->receiving_model->add_trans_receivings_menu($items);
            $prepared = $prepared_moves = array();
            $total = 0;
            $now = $this->site_model->get_db_now();
            // $datetime = date('Y-m-d H:i:s');
            $datetime = date2SqlDateTime($now);
            foreach ($rec_cart as $val) {
                $prepare = array(
                    'receiving_id' => $id,
                    'item_id'      => (int) $val['menu-search'],
                    'case'         => null,
                    'pack'         => null,
                    'uom'          => null,
                    'price'        => $val['cost'],
                    'qty'        => $val['qty']
                );
                $prepare_moves = array(
                    'type_id'  => RECEIVE_MENU_TRANS,
                    'trans_id' => $id,
                    'trans_ref'=> $next_ref,
                    'item_id'  => $val['menu-search'],
                    'qty'  => $val['qty'],
                    'uom'      => null,
                    'pack_qty' => null,
                    'case_qty' => null,
                    'reg_date' => date2Sql($this->input->post('trans_date'))." ".$trans_time,
                    'curr_item_qty' => '0'
                );
                // $loc_id = explode('-', $val['loc_id']);
                $prepare_moves['loc_id'] = 1;
                // $prepare_moves['loc_id'] = $loc_id[0];
                $last_stock = 0;
                // $stocks = $this->items_model->get_latest_item_move(array('loc_id'=>$val['loc_id'],'item_id'=>$val['item-id']));
                // if (!empty($stocks->curr_item_qty))
                //     $last_stock = $stocks->curr_item_qty;
                // if (strpos($val['select-uom'],'pack') !== false) {
                //     $converted_qty = $val['qty'] * $val['item-ppack'];
                //     $prepare['qty'] = (double) $converted_qty;
                //     $prepare['pack'] = (double) $val['qty'];
                //     $prepare_moves['qty'] = $converted_qty;
                //     $prepare_moves['pack_qty'] = (double) $val['qty'];
                //     $prepare_moves['curr_item_qty'] = $last_stock + $converted_qty;
                // } elseif (strpos($val['select-uom'],'case') !== false) {
                //     $converted_qty = $val['qty'] * $val['item-ppack'] * $val['item-pcase'];
                //     $prepare['qty'] = (double) $converted_qty;
                //     $prepare['case'] = (double) $val['qty'];
                //     $prepare_moves['qty'] = $converted_qty;
                //     $prepare_moves['case_qty'] = (double) $val['qty'];
                //     $prepare_moves['curr_item_qty'] = $last_stock + $converted_qty;
                // } else {
                //     $prepare['qty'] = (double)$val['qty'];
                //     $prepare_moves['qty'] = (double)$val['qty'];
                //     $prepare_moves['curr_item_qty'] = (double)$val['qty'] + $last_stock;
                // }
                $prepared[] = $prepare;
                $prepared_moves[] = $prepare_moves;
                $total += $val['cost'];
            }
            $this->receiving_model->add_trans_receiving_batch_menu($prepared);
            $this->receiving_model->update_trans_receivings_menu(array('amount'=>$total),$id);
            $this->items_model->add_menu_moves_batch($prepared_moves);
            $this->trans_model->save_ref(RECEIVE_MENU_TRANS,$next_ref);
        $this->trans_model->db->trans_complete();
        $this->session->unset_userdata('rec_cart');
        site_alert($next_ref." processed",'success');

        echo json_encode(array('msg'=>$next_ref." processed",'error'=>$errors));
        if(AUTOLOCALSYNC){ // run the syncing
               run_main_exec();
            }
    }

    public function void_menu($trans_id){
        $this->load->model('core/trans_model');
        $user = $this->session->userdata('user');
        $trans_type = RECEIVE_MENU_TRANS;
        $reason = $this->input->post('reason');
        $now = $this->site_model->get_db_now('sql');
        $this->trans_model->db->trans_start();
            $void = array(
                'trans_type'=>$trans_type,
                'trans_id'  =>$trans_id,
                'reason'    =>$reason,
                'reg_user'  =>$user['id'],
                'reg_date'  =>$now,
            );
            $this->site_model->add_tbl('trans_voids',$void);
            $this->site_model->update_tbl('trans_receiving_menu','receiving_id',array('inactive'=>1,'update_date'=>$now),$trans_id);
            $this->site_model->update_tbl('menu_moves',array('type_id'=>$trans_type,'trans_id'=>$trans_id),array('inactive'=>1));
        $this->trans_model->db->trans_complete();
        // echo json_encode(array('msg'=>"Transaction Voided"));
        site_alert("Transaction Voided",'success');

        if(AUTOLOCALSYNC){ // run the syncing
           run_main_exec();
        }
    }

    //for viewing of receving of items
    public function view_receiving_details($reference=null){
        $this->load->helper('dine/receiving_helper');
        $table  = "trans_receivings";
        $select = "trans_receivings.*, suppliers.name as supp_name";
        $args = array();
        $join = array();
        $args['trans_receivings.receiving_id'] = $reference;
        $join["suppliers"] = array('content'=>"suppliers.supplier_id = trans_receivings.supplier_id");
        $group = null;
        $order = array();
        $headers = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);


        $table  = "trans_receiving_details";
        $select = "trans_receiving_details.*, items.name as item_name";
        $args = array();
        $join = array();
        $args['trans_receiving_details.receiving_id'] = $reference;
        $join["items"] = array('content'=>"trans_receiving_details.item_id = items.item_id");
        $group = null;
        $order = array();
        $details = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);


        $data['code'] = viewOrderInfo($details,$headers);
        $data['load_js'] = 'dine/receive.php';
        $data['use_js'] = 'receiveDetJs';
        $this->load->view('load',$data);
    }
    public function receiving_pdf()
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
        $this->menu_model->db = $this->load->database('main', TRUE);
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $rec_id = $_GET['rec_id'];

        $table  = "trans_receivings";
        $select = "trans_receivings.*, suppliers.name as supp_name";
        $args = array();
        $join = array();
        $args['trans_receivings.receiving_id'] = $rec_id;
        $join["suppliers"] = array('content'=>"suppliers.supplier_id = trans_receivings.supplier_id");
        $group = null;
        $order = array();
        $headers = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);


        $table  = "trans_receiving_details";
        $select = "trans_receiving_details.*, items.name as item_name";
        $args = array();
        $join = array();
        $args['trans_receiving_details.receiving_id'] = $rec_id;
        $join["items"] = array('content'=>"trans_receiving_details.item_id = items.item_id");
        $group = null;
        $order = array();
        $details = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);     
       
        // $trans_payment = $this->menu_model->get_payment_date($from, $to);
        // echo $this->db->last_query();die();                  


        $pdf->Write(0, 'Receiving Details', '', 0, 'L', true, 0, false, false, 0);
        $pdf->ln(3);              
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(180, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Supplier:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $headers[0]->supp_name, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(130);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Date & Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, date('m/d/Y H:i:s',strtotime($headers[0]->trans_date)), '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(130);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Delivered by:    '.$headers[0]->delivered_by, '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(180, 0, '', 'T', 0, 'C');
        $pdf->ln(3);              

        // echo "<pre>", print_r($trans), "</pre>";die();

        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(130, 0, 'Item', 'B', 0, 'L');        
        $pdf->Cell(25, 0, 'Quantity', 'B', 0, 'R');       
        $pdf->Cell(25, 0, 'UOM', 'B', 0, 'C');        
        // $pdf->Cell(25, 0, 'Cost', 'B', 0, 'R');        
        // $pdf->Cell(25, 0, 'Total', 'B', 0, 'R');               
        $pdf->ln(6); 

        $pdf->SetFont('helvetica', '', 9);
        $total = 0;

        // for($i=1;$i<=60;$i++){

        //     $pdf->Cell(80, 0, 'TOTAL', 'T', 0, 'L');  
        //     $pdf->ln();
        //     // $i++;
        // }


        foreach($details as $val){
            $pdf->Cell(130, 0, $val->item_name, '', 0, 'L');        
            $pdf->Cell(25, 0, $val->qty, '', 0, 'R');          
            $pdf->Cell(25, 0, $val->uom, '', 0, 'C');        
            // $pdf->Cell(25, 0, num($val->price), '', 0, 'R');        
            // $tot = $val->qty * $val->price;
            // $pdf->Cell(25, 0, num($tot), '', 0, 'R');               
            $pdf->ln(); 

            // $total += $tot;
        } 

        // $pdf->ln(2);              
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(80, 0, 'TOTAL', 'T', 0, 'L');        
        // $pdf->Cell(25, 0, '', 'T', 0, 'R');       
        // $pdf->Cell(25, 0, '', 'T', 0, 'C');        
        // $pdf->Cell(25, 0, '', 'T', 0, 'R'); 
        // $pdf->Cell(25, 0, num($total), 'T', 0, 'R');               
        // $pdf->ln();              

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



        //Close and output PDF document
        $pdf->Output('sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function receiving_excel(){
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $user = $this->session->userdata('user');
        //$date = $this->input->post('daterange');
        $rec_id = $_GET['rec_id'];

        $table  = "trans_receivings";
        $select = "trans_receivings.*, suppliers.name as supp_name";
        $args = array();
        $join = array();
        $args['trans_receivings.receiving_id'] = $rec_id;
        $join["suppliers"] = array('content'=>"suppliers.supplier_id = trans_receivings.supplier_id");
        $group = null;
        $order = array();
        $headers = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);


        $table  = "trans_receiving_details";
        $select = "trans_receiving_details.*, items.name as item_name";
        $args = array();
        $join = array();
        $args['trans_receiving_details.receiving_id'] = $rec_id;
        $join["items"] = array('content'=>"trans_receiving_details.item_id = items.item_id");
        $group = null;
        $order = array();
        $details = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);  
        
        $filename='Receiving Report';
        $rc = 1;
        $sheet->mergeCells('A'.$rc.':E'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Receiving Details');
        $rc++;
        $sheet->mergeCells('A'.$rc.':B'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Supplier : ');
        $sheet->mergeCells('C'.$rc.':E'.$rc);
        $sheet->getCell('C'.$rc)->setValue($headers[0]->supp_name);
        $rc++;
        $sheet->mergeCells('A'.$rc.':B'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Date & Time :');
        $sheet->mergeCells('C'.$rc.':E'.$rc);
        $sheet->getCell('C'.$rc)->setValue(date('m/d/Y H:i:s',strtotime($headers[0]->trans_date)));
        $rc++;
        $sheet->mergeCells('A'.$rc.':B'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Delivered by : ');
        $sheet->mergeCells('C'.$rc.':E'.$rc);
        $sheet->getCell('C'.$rc)->setValue($headers[0]->delivered_by);
        // $rc++;
        // // $sheet->mergeCells('A'.$rc.':J'.$rc);
        // // $sheet->getCell('A'.$rc)->setValue('SN #'.$branch['serial']);
        $rc++;
        $rc++;

        // $sheet->getStyle("A".$rc.":J11")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('A'.$rc.':'.'J11')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        // $sheet->getStyle('A'.$rc.':'.'J11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        // $sheet->getStyle('A'.$rc.':'.'J11')->getFill()->getStartColor()->setRGB('29bb04');

        $sheet->getStyle('A'.$rc.':E'.$rc)->getFont()->setBold(true);
        $sheet->getCell('A'.$rc)->setValue('Item');
        $sheet->getCell('B'.$rc)->setValue('Quantity');
        $sheet->getCell('C'.$rc)->setValue('UOM');
        // $sheet->getCell('D'.$rc)->setValue('Cost');
        // $sheet->getCell('E'.$rc)->setValue('Total');
        
        $rc++;
        $total = 0;
        foreach($details as $val){
            $sheet->getCell('A'.$rc)->setValue($val->item_name);
            $sheet->getCell('B'.$rc)->setValue($val->qty);
            $sheet->getCell('C'.$rc)->setValue($val->uom);
            // $sheet->getCell('D'.$rc)->setValue(num($val->price));
            // $tot = $val->qty * $val->price;
            // $sheet->getCell('E'.$rc)->setValue(num($tot));
            $rc++;

            // $total += $tot;
        } 

        // $sheet->getStyle('A'.$rc.':E'.$rc)->getFont()->setBold(true);
        // $sheet->getCell('A'.$rc)->setValue('TOTAL');
        // $sheet->getCell('E'.$rc)->setValue(num($total));

        if (ob_get_contents()) 
            ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }
    public function upload_excel_form(){
        $this->load->helper('dine/receiving_helper');

        $data['code'] = receivingCheckUploadForm();
        $this->load->view('load',$data);
    }

    public function upload_menu_form(){
        $this->load->helper('dine/receiving_helper');

        $data['code'] = receivingMenuUploadForm();
        $this->load->view('load',$data);
    }

    public function upload_temp($temp_file_name,$upload_file='menu_excel'){
        $error = "";
        $file  = "";
        $path  = './uploads/temp/';
        $config['upload_path']          = $path;
        // $config['allowed_types']        = 'xls|xlsx';
        $config['allowed_types']        = '*';
        $config['file_name']            = $temp_file_name;
        $config['overwrite']            = true;
        $this->load->library('upload', $config);
        $allowed_files = array('.xls','.xlsx','.csv');
        if (!$this->upload->do_upload($upload_file)){
            $error = $this->upload->display_errors();
        }
        else{
            $fileData = $this->upload->data('file_name');
            if(in_array($fileData['file_ext'],$allowed_files)){
                $file = $fileData['file_name'];
            }
            else{
                $error = 'File is not allowed';
                unlink($path.$fileData['file_name']);
            }
        }           
        return array('file'=>$path."".$file,'error'=>$error);    
    }

     public function upload_excel_db(){
        $this->load->model('core/trans_model');
        $this->load->model('dine/main_model');

        $this->load->model('dine/items_model');
        $temp = $this->upload_temp('menu_excel_temp');
        $next_ref = $this->trans_model->get_next_ref(RECEIVE_TRANS);
        $next_id = ltrim(ltrim($next_ref, 'R'), '0');

        // echo $next_id;die();
        // echo $temp;die();
        // echo $this->trans_model->on_next_ref(SPOIL_TRANS);
        $user = $this->session->userdata('user');
        $user_id = $user['id']; 
        $item_list_raw = $this->items_model->get_item();
        $locations_list_raw = $this->items_model->get_locations();
        $item_list = $locations_list = $trans_refs = array();
        foreach($item_list_raw as $item){
            $item_list[strtolower($item->code)] = array('item_id'=>$item->item_id,'uom'=>$item->uom);
        }

         foreach($locations_list_raw as $loc){
            $locations_list[strtolower($loc->loc_code)] = $loc->loc_id;
        }

        // echo "<pre>",print_r($item_list),"</pre>";die();
        // echo $user['id'];
        // echo $next_ref;die();
        if($temp['error'] == ""){
            // echo 'dasda';die();
            $now = $this->site_model->get_db_now('sql');
            // $receiving_id = $this->site_model->get_last_receiving('sql');
            $this->load->library('excel');
            $obj = PHPExcel_IOFactory::load($temp['file']);
            $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $start = 1;
            $item_moves = $trans_receiving = array();
            $memo = "";
            $reg_date = date('Y-m-d h:i:s');
            $trans_date = date('Y-m-d');
            for($i=$start;$i<=$count;$i++){
                $item_code = strtolower($sheet[$i]["B"]);
                if(!isset($item_list[$item_code])){
                    continue;
                }
                if($sheet[$i]["B"] != ""){
                    // echo strtolower($sheet[$i]["B"]);
                    $item_id =  $item_list[$item_code]['item_id'];
                    $uom =  $item_list[$item_code]['uom'];
                    $qty = $sheet[$i]["C"];
                    $price = 0;//$sheet[$i]["E"];
                    $supplier_raw = $sheet[$i]["E"];//$sheet[$i]["F"];
                    $supplier = $this->items_model->get_supplier();
                    if(isset($locations_list[$sheet[$i]["A"]])){
                        $location_id = $locations_list[$sheet[$i]["A"]];
                    }else{
                        $location_id = NULL;
                    }
               
                    $rows[] = array(
                        "receiving_id" => $next_id,
                        "item_id" => $item_id,
                        "qty" => $qty,
                        "uom" => $uom,
                        "price"=>$price
                    );


                    $item_moves[] = array("type_id"=>RECEIVE_TRANS,"trans_id"=>$next_id,"trans_ref"=>$next_ref,"loc_id"=>$location_id,"item_id"=>$item_id,"qty"=>$qty,'uom'=>$uom,'reg_date'=>$reg_date);
                }
                if($sheet[$i]["D"] != ""){
                    $memo = $sheet[$i]["D"];
                }
            }
            $trans_receiving = array('type_id'=>RECEIVE_TRANS,'trans_ref'=>$next_ref,'reference'=>$next_ref,'amount'=>$price,'supplier_id'=>$supplier,'receiving_id'=>$next_id,'memo'=>$memo,'user_id'=>$user_id,'trans_date'=>$trans_date,'reg_date'=>$reg_date);
            $trans_refs = array('type_id'=>RECEIVE_TRANS,'trans_ref'=>$next_ref,'user_id'=>$user_id);

            // echo "<pre>",print_r($rows),"</pre>";die();
            if(count($rows) > 0){
                $this->db->trans_start();

                     $this->trans_model->save_ref(RECEIVE_TRANS,$next_ref);
                    // $dflt_schedule = 1;                
                    #################################################################################################################################
                    // ### INACTIVE ALL
                    //     $this->site_model->update_tbl('menu_categories',array(),array('inactive'=>1));
                    //     $this->main_model->update_trans_tbl('menu_categories',array(),array('inactive'=>1));
                    //     $this->site_model->update_tbl('menu_subcategories',array(),array('inactive'=>1));
                    //     $this->main_model->update_trans_tbl('menu_subcategories',array(),array('inactive'=>1));
                    //     $this->site_model->update_tbl('menus',array(),array('inactive'=>1));
                    //     $this->main_model->update_trans_tbl('menus',array(),array('inactive'=>1));
                    //     $this->site_model->update_tbl('modifier_groups',array(),array('inactive'=>1));
                    //     $this->main_model->update_trans_tbl('modifier_groups',array(),array('inactive'=>1));
                    //     $this->site_model->update_tbl('modifiers',array(),array('inactive'=>1));
                    //     $this->main_model->update_trans_tbl('modifiers',array(),array('inactive'=>1));
                    // #################################################################################################################################
                    // ### INSERT CATEGORIES
                        // $ins_gift_cards = array();
                        // foreach ($rows as $ctr => $row) {
                        //     if(!isset($ins_categories[$row['card_no']]) && $row['card_no'] !='gift check no'){
                        //         $card_no = $row['card_no'];
                        //         $ins_gift_cards[$row['card_no']] = array(
                        //             'card_no' => strtoupper($card_no),
                        //             'amount' => $row['amount'],
                        //             // 'reg_date'      => $now,
                        //         );
                        //     }
                        // }
                     // echo "<pre>",print_r($trans_refs),"</pre>";die();
                        $this->site_model->add_tbl('trans_receivings',$trans_receiving);
                        $this->site_model->add_tbl('trans_refs',$trans_refs);
                     // echo "asd";die();  

                        $this->site_model->add_tbl_batch_ignore('trans_receiving_details',$rows);
                        $this->site_model->add_tbl_batch_ignore('item_moves',$item_moves);
                $this->db->trans_complete();

                #################################################################################################################################
            }
            unlink($temp['file']);
            site_alert('Markouts successfully uploaded','success');
        }
        else{
            site_alert($temp['error'],"error");
        }
        redirect(base_url()."receiving", 'refresh'); 
    }

    public function upload_menu_db(){
        $this->load->model('core/trans_model');
        $this->load->model('dine/main_model');

        $this->load->model('dine/menu_model');
        $this->load->model('dine/items_model');

        $temp = $this->upload_temp('menu_excel_temp');
        $next_ref = $this->trans_model->get_next_ref(RECEIVE_MENU_TRANS);
        $next_id = ltrim(ltrim($next_ref, 'RM'), '0');

        $user = $this->session->userdata('user');
        $user_id = $user['id']; 
        $menu_list_raw = $this->menu_model->get_menus();
        $locations_list_raw = $this->items_model->get_locations();
        $menu_list = $locations_list = $trans_refs = array();
        foreach($menu_list_raw as $menu){
            $menu_list[strtolower($menu->menu_code)] = array('menu_id'=>$menu->menu_id,'menu_code'=>$menu->menu_code);
        }

        foreach($locations_list_raw as $loc){
            $locations_list[strtolower($loc->loc_code)] = $loc->loc_id;
        }

        if($temp['error'] == ""){
            $now = $this->site_model->get_db_now('sql');
            $this->load->library('excel');
            $obj = PHPExcel_IOFactory::load($temp['file']);
            $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $start = 1;
            $menu_moves = $trans_receiving = array();
            $memo = "";
            $reg_date = date('Y-m-d h:i:s');
            $trans_date = date('Y-m-d h:i:s');
            for($i=$start;$i<=$count;$i++){
                $menu_code = strtolower($sheet[$i]["B"]);
                if(!isset($menu_list[$menu_code])){
                    continue;
                }
                if($sheet[$i]["B"] != ""){
                    $menu_id =  $menu_list[$menu_code]['menu_id'];
                    $qty = $sheet[$i]["C"];
                    $price = $sheet[$i]["E"];
                    if(isset($locations_list[$sheet[$i]["A"]])){
                        $location_id = $locations_list[$sheet[$i]["A"]];
                    }else{
                        $location_id = NULL;
                    }
               
                    $rows[] = array(
                        "receiving_id" => $next_id,
                        "item_id" => $menu_id,
                        "qty" => $qty,
                        "price"=>$price
                    );


                    $menu_moves[] = array("type_id"=>RECEIVE_MENU_TRANS,"trans_id"=>$next_id,"trans_ref"=>$next_ref,"loc_id"=>$location_id,"item_id"=>$menu_id,"qty"=>$qty,'reg_date'=>$reg_date);
                }
                if($sheet[$i]["D"] != ""){
                    $memo = $sheet[$i]["D"];
                }
            }

            $trans_receiving = array('type_id'=>RECEIVE_MENU_TRANS,'trans_ref'=>$next_ref,'reference'=>$next_ref,'amount'=>$price,'receiving_id'=>$next_id,'memo'=>$memo,'user_id'=>$user_id,'trans_date'=>$trans_date,'reg_date'=>$reg_date);
            $trans_refs = array('type_id'=>RECEIVE_MENU_TRANS,'trans_ref'=>$next_ref,'user_id'=>$user_id);

            if(count($rows) > 0){
                $this->db->trans_start();

                    $this->trans_model->save_ref(RECEIVE_MENU_TRANS,$next_ref);
                    $this->site_model->add_tbl('trans_receiving_menu',$trans_receiving);
                    $this->site_model->add_tbl('trans_refs',$trans_refs);

                    $this->site_model->add_tbl_batch_ignore('trans_receiving_menu_details',$rows);
                    $this->site_model->add_tbl_batch_ignore('menu_moves',$menu_moves);
                    $this->db->trans_complete();


            }
            unlink($temp['file']);
            site_alert('Markouts successfully uploaded','success');
        }
        else{
            site_alert($temp['error'],"error");
        }
        redirect(base_url()."receiving_menu", 'refresh'); 
    }

    public function download_template(){
    //   $data = file_get_contents('php://output'); 
    // $name = 'data.csv';
        // $this->load->helper('download');
        $file = FCPATH.'uploads/temp/receiving_template.csv';
        // echo $file;//die();
        $name = 'receiving_template.csv';
        // $test = force_download($file, NULL);
        // var_dump($test);
        // echo filesize($file);die();
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename='.$name);
        header('Expires: 0');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file); // push it out
    }

    public function download_menu_template(){
        $file = FCPATH.'uploads/temp/receiving_menu_template.csv';
        
        $name = 'receiving_menu_template.csv';
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename='.$name);
        header('Expires: 0');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file); // push it out
    }

    //for viewing of receving of menus
    public function view_receiving_menu_details($reference=null){
        $this->site_model->db = $this->load->database('main', TRUE);
        $this->load->helper('dine/receiving_helper');
        $this->load->helper('site/site_forms_helper');
        // $data = $this->syter->spawn('trans');
        $table  = "trans_receiving_menu";
        $select = "trans_receiving_menu.*";
        $args = array();
        $join = array();
        $args['trans_receiving_menu.receiving_id'] = $reference;
        // $join["suppliers"] = array('content'=>"suppliers.supplier_id = trans_receiving_menu.supplier_id");
        // $join = null;
        $group = null;
        $order = array();
        $headers = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);

        // echo "<pre>", print_r($headers), "</pre>";die();

        $table  = "trans_receiving_menu_details";
        $select = "trans_receiving_menu_details.*, menus.menu_name as menu_name";
        $args = array();
        $join = array();
        $args['trans_receiving_menu_details.receiving_id'] = $reference;
        $join["menus"] = array('content'=>"trans_receiving_menu_details.item_id = menus.menu_id");
        $group = null;
        $order = array();
        $details = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);


        $data['code'] = viewOrderMenuInfo($details,$headers);
        $data['load_js'] = 'dine/receive.php';
        $data['use_js'] = 'receiveDetMenuJs';
        $this->load->view('load',$data);
    }

    public function receiving_menu_pdf()
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
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->site_model->db = $this->load->database('main', TRUE);

        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $rec_id = $_GET['rec_id'];

        $table  = "trans_receiving_menu";
        $select = "trans_receiving_menu.*";
        $args = array();
        $join = array();
        $args['trans_receiving_menu.receiving_id'] = $rec_id;
        // $join["suppliers"] = array('content'=>"suppliers.supplier_id = trans_receiving_menu.supplier_id");
        // $join = null;
        $group = null;
        $order = array();
        $headers = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);


        $table  = "trans_receiving_menu_details";
        $select = "trans_receiving_menu_details.*, menus.menu_name as menu_name";
        $args = array();
        $join = array();
        $args['trans_receiving_menu_details.receiving_id'] = $rec_id;
        $join["menus"] = array('content'=>"trans_receiving_menu_details.item_id = menus.menu_id");
        $group = null;
        $order = array();
        $details = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);     
       
        // $trans_payment = $this->menu_model->get_payment_date($from, $to);
        // echo $this->db->last_query();die();                  


        $pdf->Write(0, 'Receiving Menu Details', '', 0, 'L', true, 0, false, false, 0);
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

        // for($i=1;$i<=60;$i++){

        //     $pdf->Cell(80, 0, 'TOTAL', 'T', 0, 'L');  
        //     $pdf->ln();
        //     // $i++;
        // }


        foreach($details as $val){
            $pdf->Cell(80, 0, $val->menu_name, '', 0, 'L');        
            $pdf->Cell(25, 0, $val->qty, '', 0, 'R');        
            // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
            // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
            $pdf->Cell(25, 0, $val->uom, '', 0, 'C');        
            $pdf->Cell(25, 0, num($val->price), '', 0, 'R');        
            $tot = $val->qty * $val->price;
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



        //Close and output PDF document
        $pdf->Output('sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function receiving_menu_excel(){
        $this->site_model->db = $this->load->database('main', TRUE);
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $user = $this->session->userdata('user');
        //$date = $this->input->post('daterange');
        $rec_id = $_GET['rec_id'];

        $table  = "trans_receiving_menu";
        $select = "trans_receiving_menu.*";
        $args = array();
        $join = array();
        $args['trans_receiving_menu.receiving_id'] = $rec_id;
        // $join["suppliers"] = array('content'=>"suppliers.supplier_id = trans_receiving_menu.supplier_id");
        // $join = null;
        $group = null;
        $order = array();
        $headers = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);


        $table  = "trans_receiving_menu_details";
        $select = "trans_receiving_menu_details.*, menus.menu_name as menu_name";
        $args = array();
        $join = array();
        $args['trans_receiving_menu_details.receiving_id'] = $rec_id;
        $join["menus"] = array('content'=>"trans_receiving_menu_details.item_id = menus.menu_id");
        $group = null;
        $order = array();
        $details = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group); 
        
        $filename='Receiving Menu Report';
        $rc = 1;
        $sheet->mergeCells('A'.$rc.':E'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Receiving Menu Details');
        // $rc++;
        // $sheet->mergeCells('A'.$rc.':B'.$rc);
        // $sheet->getCell('A'.$rc)->setValue('Supplier : ');
        // $sheet->mergeCells('C'.$rc.':E'.$rc);
        // $sheet->getCell('C'.$rc)->setValue($headers[0]->supp_name);
        $rc++;
        $sheet->mergeCells('A'.$rc.':B'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Date & Time :');
        $sheet->mergeCells('C'.$rc.':E'.$rc);
        $sheet->getCell('C'.$rc)->setValue(date('m/d/Y H:i:s',strtotime($headers[0]->trans_date)));
        $rc++;
        $sheet->mergeCells('A'.$rc.':B'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Generated by : ');
        $sheet->mergeCells('C'.$rc.':E'.$rc);
        $sheet->getCell('C'.$rc)->setValue($user["full_name"]);
        // $rc++;
        // // $sheet->mergeCells('A'.$rc.':J'.$rc);
        // // $sheet->getCell('A'.$rc)->setValue('SN #'.$branch['serial']);
        $rc++;
        $rc++;

        // $sheet->getStyle("A".$rc.":J11")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('A'.$rc.':'.'J11')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        // $sheet->getStyle('A'.$rc.':'.'J11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        // $sheet->getStyle('A'.$rc.':'.'J11')->getFill()->getStartColor()->setRGB('29bb04');

        $sheet->getStyle('A'.$rc.':E'.$rc)->getFont()->setBold(true);
        $sheet->getCell('A'.$rc)->setValue('Item');
        $sheet->getCell('B'.$rc)->setValue('Quantity');
        $sheet->getCell('C'.$rc)->setValue('UOM');
        $sheet->getCell('D'.$rc)->setValue('Cost');
        $sheet->getCell('E'.$rc)->setValue('Total');
        
        $rc++;
        $total = 0;
        foreach($details as $val){
            $sheet->getCell('A'.$rc)->setValue($val->menu_name);
            $sheet->getCell('B'.$rc)->setValue($val->qty);
            $sheet->getCell('C'.$rc)->setValue($val->uom);
            $sheet->getCell('D'.$rc)->setValue(num($val->price));
            $tot = $val->qty * $val->price;
            $sheet->getCell('E'.$rc)->setValue(num($tot));
            $rc++;

            $total += $tot;
        } 

        $sheet->getStyle('A'.$rc.':E'.$rc)->getFont()->setBold(true);
        $sheet->getCell('A'.$rc)->setValue('TOTAL');
        $sheet->getCell('E'.$rc)->setValue(num($total));

        if (ob_get_contents()) 
            ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    //adjustment menu/item
    public function adjustment_menu(){
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('fa-download')." Item Adjustment";
        $th = array('Reference','Adjusted By','Adjustment Date Time','Inactive','');
        $data['code'] = create_rtable('trans_adjustment_menu','adjustment_id','main-tbl',$th,null,false,'list');
        $data['load_js'] = 'dine/receive.php';
        $data['use_js'] = 'adjustMenuListJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function get_adjustment_menu($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 30;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();      

        $url    =  'receiving/get_adjustment_menu';
        $table  =  'trans_adjustment_menu';
        $select =  'trans_adjustment_menu.*,users.username as username';
        // $join['suppliers'] = 'trans_receivings.supplier_id=suppliers.supplier_id';
        $join['users'] = 'trans_adjustment_menu.user_id=users.id';
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if(isset($post['trans_ref'])){
            $lk = $post['trans_ref'];
            $args["(".$table.".trans_ref like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        // if(isset($post['supplier_id'])){
        //     $args["trans_receivings.supplier_id"] = $post['supplier_id'];
        // }
        if(isset($post['void'])){
            $args["trans_adjustment_menu.inactive"] = $post['void'];
        }
        $count = $this->site_model->get_tbl($table,$args,array(),$join,true,$select,null,null,true);
        $page = paginate($url,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,array(),$join,true,$select,null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $view = $this->make->A(fa('fa fa-eye fa-lg'),'receiving/view_adjustment_menu_details/'.$res->adjustment_id,array(
                                        'class'=>'view',
                                        'rata-title'=>'View '.$res->trans_ref,
                                        // 'rata-pass'=>'sales/view_sales_order_details',
                                        // 'rata-form'=>'inv_adjustment_form',
                                        'ref'=>$res->adjustment_id,
                                        // 'id'=>'view-item-'.$val->counter,
                                        'title'=>'View '.$res->trans_ref,
                                        'return'=>'false'
                                    ));

                $void = "";
                if($res->inactive == 0){
                    $inactive = "No";
                    $void = $this->make->A(fa('fa fa-times fa-lg'),'#',array('title'=>'Void Trans '.$res->trans_ref,
                                                                       'ref'=>$res->adjustment_id,
                                                                       'class'=>'void',
                                                                       'return'=>true));
                }
                else
                    $inactive = "Yes";
                $json[] = array(
                    "id"=>$res->trans_ref,   
                    // "title"=>ucwords(strtolower($res->supplier_name)),   
                    "desc"=>ucwords(strtolower($res->username)),   
                    "date"=>date('m/d/Y h:i A',strtotime($res->trans_date)),  
                    "notactive"=>$inactive,
                    "void"=>$void." ".$view
                );
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function form_menu_adjustment(){
        $this->load->model('core/trans_model');
        $this->load->model('dine/receiving_model');
        $this->load->helper('dine/receiving_helper');
        sess_clear('adj_cart');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('fa-download')." Item Adjsutment";
        $ref = $this->trans_model->get_next_ref(ADJUSTMENT_MENU_TRANS);
        $data['code'] = adjustmentMenuFormPage($ref);
        // $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        // $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css','js/plugins/typeaheadmap/typeaheadmap.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js','js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/receive.php';
        $data['use_js'] = 'adjustMenuJs';
        $this->load->view('page',$data);
    }
    public function save_menu_adjustment(){
        $this->load->model('dine/receiving_model');
        $this->load->model('dine/items_model');
        $this->load->model('core/trans_model');
        $user = $this->session->userdata('user');
        $rec_cart = $this->session->userdata('adj_cart');
        // $next_ref = $this->trans_model->get_next_ref(RECEIVE_TRANS);
        $next_ref = $this->input->post('reference');
        $trans_time = date('H:i:s',strtotime($this->input->post('trans_time')));
        $items = array(
            "reference"=>$next_ref,
            "memo"=>$this->input->post('memo'),
            "trans_date"=>date2Sql($this->input->post('trans_date'))." ".$trans_time,
            "trans_ref"=>$next_ref,
            "type_id"=>ADJUSTMENT_MENU_TRANS,
            "user_id"=>$user['id'],
            "type"=>$this->input->post('type')
        );
        $errors = "";
        if (empty($rec_cart)) {
            $errors = 'no menu';
            echo json_encode(array('msg'=>"Please select a menu first before proceeding",'error'=>$errors));
            return false;
        }
        $this->trans_model->db->trans_start();
            $count = $this->site_model->get_tbl('trans_adjustment_menu',array('trans_ref'=>$next_ref),array(),array(),true,'*',null,null,true);
            if($count){
                $errors = 'Reference used';
                echo json_encode(array('msg'=>"Reference ".$next_ref." is already used.",'error'=>$errors));
                return false;
            }

            $id = $this->receiving_model->add_trans_adjustment_menu($items);
            $prepared = $prepared_moves = array();
            $total = 0;
            $now = $this->site_model->get_db_now();
            // $datetime = date('Y-m-d H:i:s');
            $datetime = date2SqlDateTime($now);
            foreach ($rec_cart as $val) {
                $qty = $val['qty'];
                if($this->input->post('type') != 1){
                    $qty = $val['qty'] * -1;
                }

                $prepare = array(
                    'adjustment_id' => $id,
                    'item_id'      => (int) $val['menu-search'],
                    'case'         => null,
                    'pack'         => null,
                    'uom'          => null,
                    // 'price'        => $val['cost'],
                    'qty'        => $qty
                );
                $prepare_moves = array(
                    'type_id'  => ADJUSTMENT_MENU_TRANS,
                    'trans_id' => $id,
                    'trans_ref'=> $next_ref,
                    'item_id'  => $val['menu-search'],
                    'qty'  => $qty,
                    'uom'      => null,
                    'pack_qty' => null,
                    'case_qty' => null,
                    'reg_date' => date2Sql($this->input->post('trans_date'))." ".$trans_time,
                    'curr_item_qty' => '0'
                );
                // $loc_id = explode('-', $val['loc_id']);
                $prepare_moves['loc_id'] = 1;
                // $prepare_moves['loc_id'] = $loc_id[0];
                $last_stock = 0;
                // $stocks = $this->items_model->get_latest_item_move(array('loc_id'=>$val['loc_id'],'item_id'=>$val['item-id']));
                // if (!empty($stocks->curr_item_qty))
                //     $last_stock = $stocks->curr_item_qty;
                // if (strpos($val['select-uom'],'pack') !== false) {
                //     $converted_qty = $val['qty'] * $val['item-ppack'];
                //     $prepare['qty'] = (double) $converted_qty;
                //     $prepare['pack'] = (double) $val['qty'];
                //     $prepare_moves['qty'] = $converted_qty;
                //     $prepare_moves['pack_qty'] = (double) $val['qty'];
                //     $prepare_moves['curr_item_qty'] = $last_stock + $converted_qty;
                // } elseif (strpos($val['select-uom'],'case') !== false) {
                //     $converted_qty = $val['qty'] * $val['item-ppack'] * $val['item-pcase'];
                //     $prepare['qty'] = (double) $converted_qty;
                //     $prepare['case'] = (double) $val['qty'];
                //     $prepare_moves['qty'] = $converted_qty;
                //     $prepare_moves['case_qty'] = (double) $val['qty'];
                //     $prepare_moves['curr_item_qty'] = $last_stock + $converted_qty;
                // } else {
                //     $prepare['qty'] = (double)$val['qty'];
                //     $prepare_moves['qty'] = (double)$val['qty'];
                //     $prepare_moves['curr_item_qty'] = (double)$val['qty'] + $last_stock;
                // }
                $prepared[] = $prepare;
                $prepared_moves[] = $prepare_moves;
                // $total += $val['cost'];
            }
            $this->receiving_model->add_trans_adjustment_batch_menu($prepared);
            // $this->receiving_model->update_trans_adjustment_menu(array('amount'=>$total),$id);
            $this->items_model->add_menu_moves_batch($prepared_moves);
            $this->trans_model->save_ref(ADJUSTMENT_MENU_TRANS,$next_ref);
        $this->trans_model->db->trans_complete();
        $this->session->unset_userdata('rec_cart');
        site_alert($next_ref." processed",'success');

        echo json_encode(array('msg'=>$next_ref." processed",'error'=>$errors));
        if(AUTOLOCALSYNC){ // run the syncing
           run_main_exec();
        }
    }

    public function void_menu_adjustment($trans_id){
        $this->load->model('core/trans_model');
        $user = $this->session->userdata('user');
        $trans_type = ADJUSTMENT_MENU_TRANS;
        $reason = $this->input->post('reason');
        $now = $this->site_model->get_db_now('sql');
        $this->trans_model->db->trans_start();
            $void = array(
                'trans_type'=>$trans_type,
                'trans_id'  =>$trans_id,
                'reason'    =>$reason,
                'reg_user'  =>$user['id'],
                'reg_date'  =>$now,
            );
            $this->site_model->add_tbl('trans_voids',$void);
            $this->site_model->update_tbl('trans_adjustment_menu','adjustment_id',array('inactive'=>1,'update_date'=>$now),$trans_id);
            $this->site_model->update_tbl('menu_moves',array('type_id'=>$trans_type,'trans_id'=>$trans_id),array('inactive'=>1));
        $this->trans_model->db->trans_complete();
        // echo json_encode(array('msg'=>"Transaction Voided"));
        site_alert("Transaction Voided",'success');

        if(AUTOLOCALSYNC){ // run the syncing
           run_main_exec();
        }
    }

     //for viewing of receving of menus
    public function view_adjustment_menu_details($reference=null){
        $this->site_model->db = $this->load->database('main', TRUE);
        $this->load->helper('dine/receiving_helper');
        $this->load->helper('site/site_forms_helper');
        // $data = $this->syter->spawn('trans');
        $table  = "trans_adjustment_menu";
        $select = "trans_adjustment_menu.*";
        $args = array();
        $join = array();
        $args['trans_adjustment_menu.adjustment_id'] = $reference;
        // $join["suppliers"] = array('content'=>"suppliers.supplier_id = trans_receiving_menu.supplier_id");
        // $join = null;
        $group = null;
        $order = array();
        $headers = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);

        // echo "<pre>", print_r($headers), "</pre>";die();

        $table  = "trans_adjustment_menu_details";
        $select = "trans_adjustment_menu_details.*, menus.menu_name as menu_name";
        $args = array();
        $join = array();
        $args['trans_adjustment_menu_details.adjustment_id'] = $reference;
        $join["menus"] = array('content'=>"trans_adjustment_menu_details.item_id = menus.menu_id");
        $group = null;
        $order = array();
        $details = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);


        $data['code'] = viewAdjMenuInfo($details,$headers);
        $data['load_js'] = 'dine/receive.php';
        $data['use_js'] = 'adjustDetMenuJs';
        $this->load->view('load',$data);
    }

    public function adjustment_menu_pdf()
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
        $pdf->SetTitle('Adjustment Details');
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
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->site_model->db = $this->load->database('main', TRUE);

        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $adj_id = $_GET['adj_id'];

        $table  = "trans_adjustment_menu";
        $select = "trans_adjustment_menu.*";
        $args = array();
        $join = array();
        $args['trans_adjustment_menu.adjustment_id'] = $adj_id;
        // $join["suppliers"] = array('content'=>"suppliers.supplier_id = trans_receiving_menu.supplier_id");
        // $join = null;
        $group = null;
        $order = array();
        $headers = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);


        $table  = "trans_adjustment_menu_details";
        $select = "trans_adjustment_menu_details.*, menus.menu_name as menu_name, menus.uom_id";
        $args = array();
        $join = array();
        $args['trans_adjustment_menu_details.adjustment_id'] = $adj_id;
        $join["menus"] = array('content'=>"trans_adjustment_menu_details.item_id = menus.menu_id");
        $group = null;
        $order = array();
        $details = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);     
       
        // $trans_payment = $this->menu_model->get_payment_date($from, $to);
        // echo $this->db->last_query();die();                  


        $pdf->Write(0, 'Adjustment Item Details', '', 0, 'L', true, 0, false, false, 0);
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
        $pdf->Cell(105, 0, 'Item', 'B', 0, 'L');        
        $pdf->Cell(50, 0, 'Quantity', 'B', 0, 'R');        
        // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(25, 0, 'UOM', 'B', 0, 'C');        
        // $pdf->Cell(25, 0, 'Cost', 'B', 0, 'R');        
        // $pdf->Cell(25, 0, 'Total', 'B', 0, 'R');               
        $pdf->ln(6); 

        $pdf->SetFont('helvetica', '', 9);
        $total = 0;

        // for($i=1;$i<=60;$i++){

        //     $pdf->Cell(80, 0, 'TOTAL', 'T', 0, 'L');  
        //     $pdf->ln();
        //     // $i++;
        // }


        foreach($details as $val){
            $pdf->Cell(105, 0, $val->menu_name, '', 0, 'L');        
            $pdf->Cell(50, 0, $val->qty, '', 0, 'R');        
            // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
            // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');   
            $uom = "";
            $wheres = array('id'=>$val->uom_id);
            $detc = $this->site_model->get_details($wheres,'uom');   
            if($detc){
                $uom = $detc[0]->code;
            } 

            $pdf->Cell(25, 0, $uom, '', 0, 'C');        
            // $pdf->Cell(25, 0, num($val->price), '', 0, 'R');        
            // $tot = $val->qty * $val->price;
            // $pdf->Cell(25, 0, num($tot), '', 0, 'R');               
            $pdf->ln(); 

            // $total += $tot;
        } 

        // $pdf->ln(2);              
        // $pdf->SetFont('helvetica', 'B', 9);
        // $pdf->Cell(80, 0, 'TOTAL', 'T', 0, 'L');        
        // $pdf->Cell(25, 0, '', 'T', 0, 'R');        
        // // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
        // // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
        // $pdf->Cell(25, 0, '', 'T', 0, 'C');        
        // $pdf->Cell(25, 0, '', 'T', 0, 'R');        
        // // $tot = $val->qty * $val->price;
        // // $pdf->Cell(25, 0, num($total), 'T', 0, 'R');               
        // $pdf->ln();              

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



        //Close and output PDF document
        $pdf->Output('adjustment_item_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function adjustment_menu_excel(){
        $this->site_model->db = $this->load->database('main', TRUE);
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $user = $this->session->userdata('user');
        //$date = $this->input->post('daterange');
        $adj_id = $_GET['adj_id'];

        $table  = "trans_adjustment_menu";
        $select = "trans_adjustment_menu.*";
        $args = array();
        $join = array();
        $args['trans_adjustment_menu.adjustment_id'] = $adj_id;
        // $join["suppliers"] = array('content'=>"suppliers.supplier_id = trans_receiving_menu.supplier_id");
        // $join = null;
        $group = null;
        $order = array();
        $headers = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);


        $table  = "trans_adjustment_menu_details";
        $select = "trans_adjustment_menu_details.*, menus.menu_name as menu_name, menus.uom_id";
        $args = array();
        $join = array();
        $args['trans_adjustment_menu_details.adjustment_id'] = $adj_id;
        $join["menus"] = array('content'=>"trans_adjustment_menu_details.item_id = menus.menu_id");
        $group = null;
        $order = array();
        $details = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group); 
        
        $filename='Adjustment Item Report';
        $rc = 1;
        $sheet->mergeCells('A'.$rc.':E'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Adjustment Item Details');
        // $rc++;
        // $sheet->mergeCells('A'.$rc.':B'.$rc);
        // $sheet->getCell('A'.$rc)->setValue('Supplier : ');
        // $sheet->mergeCells('C'.$rc.':E'.$rc);
        // $sheet->getCell('C'.$rc)->setValue($headers[0]->supp_name);
        $rc++;
        $sheet->mergeCells('A'.$rc.':B'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Date & Time :');
        $sheet->mergeCells('C'.$rc.':E'.$rc);
        $sheet->getCell('C'.$rc)->setValue(date('m/d/Y H:i:s',strtotime($headers[0]->trans_date)));
        $rc++;
        $sheet->mergeCells('A'.$rc.':B'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Generated by : ');
        $sheet->mergeCells('C'.$rc.':E'.$rc);
        $sheet->getCell('C'.$rc)->setValue($user["full_name"]);
        // $rc++;
        // // $sheet->mergeCells('A'.$rc.':J'.$rc);
        // // $sheet->getCell('A'.$rc)->setValue('SN #'.$branch['serial']);
        $rc++;
        $rc++;

        // $sheet->getStyle("A".$rc.":J11")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('A'.$rc.':'.'J11')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        // $sheet->getStyle('A'.$rc.':'.'J11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        // $sheet->getStyle('A'.$rc.':'.'J11')->getFill()->getStartColor()->setRGB('29bb04');

        $sheet->getStyle('A'.$rc.':E'.$rc)->getFont()->setBold(true);
        $sheet->getCell('A'.$rc)->setValue('Item');
        $sheet->getCell('B'.$rc)->setValue('Quantity');
        $sheet->getCell('C'.$rc)->setValue('UOM');
        // $sheet->getCell('D'.$rc)->setValue('Cost');
        // $sheet->getCell('E'.$rc)->setValue('Total');
        
        $rc++;
        $total = 0;
        foreach($details as $val){
            $sheet->getCell('A'.$rc)->setValue($val->menu_name);
            $sheet->getCell('B'.$rc)->setValue($val->qty);

            $uom = "";
            $wheres = array('id'=>$val->uom_id);
            $detc = $this->site_model->get_details($wheres,'uom');   
            if($detc){
                $uom = $detc[0]->code;
            } 

            $sheet->getCell('C'.$rc)->setValue($uom);
            // $sheet->getCell('D'.$rc)->setValue(num($val->price));
            // $tot = $val->qty * $val->price;
            // $sheet->getCell('E'.$rc)->setValue(num($tot));
            $rc++;

            // $total += $tot;
        } 

        // $sheet->getStyle('A'.$rc.':E'.$rc)->getFont()->setBold(true);
        // $sheet->getCell('A'.$rc)->setValue('TOTAL');
        // $sheet->getCell('E'.$rc)->setValue(num($total));

        if (ob_get_contents()) 
            ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }


}