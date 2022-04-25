<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Spoilage extends CI_Controller {
    public function index(){
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa(' icon-trash')." Markout";
        $th = array('Reference','Trans by','Trans Date','');
        $data['code'] = create_rtable('trans_spoilage','spoil_id','main-tbl',$th,'spoilage/search',false,'list');
        $data['load_js'] = 'dine/spoilage.php';
        $data['use_js'] = 'spoilListJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function get_spoilage($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 30;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();      

        $url    =  'spoilage/get_spoilage';
        $table  =  'trans_spoilage';
        $select =  'trans_spoilage.*,users.username as username';
        $join['users'] = 'trans_spoilage.user_id=users.id';
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if(isset($post['trans_ref'])){
            $lk = $post['trans_ref'];
            $args["(".$table.".trans_ref like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        $count = $this->site_model->get_tbl($table,$args,array(),$join,true,$select,null,null,true);
        $page = paginate($url,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,array(),$join,true,$select,null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $void = "";
                if($res->inactive == 0){
                    $inactive = "No";
                    $void = $this->make->A(fa('fa fa-times fa-lg').' Delete','#',array('title'=>'Void Trans '.$res->trans_ref,
                                                                       'ref'=>$res->spoil_id,
                                                                       'class'=>'btn red btn-sm btn-outline void',
                                                                       'return'=>true));
                }
                else
                    $inactive = "Yes";
                $json[] = array(
                    "id"=>$res->trans_ref,   
                    "desc"=>ucwords(strtolower($res->username)),   
                    "date"=>sql2Date($res->reg_date),   
                    "void"=>$void, 
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
        $this->load->helper('dine/spoilage_helper');
        sess_clear('spoil_cart');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = fa('icon-trash')." Markout";
        
        $ref = $this->trans_model->get_next_ref(SPOIL_TRANS);
        $data['code'] = spoilageFormPage($ref);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/spoilage.php';
        $data['use_js'] = 'spoliageJs';
        $this->load->view('page',$data);
    }
    public function save(){
        $this->load->model('dine/items_model');
        $this->load->model('core/trans_model');
        $user = $this->session->userdata('user');
        $spoil_cart = $this->session->userdata('spoil_cart');
        $next_ref = $this->input->post('reference');
        $now = $this->site_model->get_db_now();
        $datetime = date2SqlDateTime($now);
        $items = array(
            "memo"=>$this->input->post('memo'),
            "trans_date"=>date2Sql($this->input->post('trans_date')),
            "trans_ref"=>$next_ref,
            "type_id"=>SPOIL_TRANS,
            "user_id"=>$user['id'],
            "reg_date"=>$datetime
        );
        $errors = "";
        if (empty($spoil_cart)) {
            $errors = 'no item';
            echo json_encode(array('msg'=>"Please select an item first before proceeding",'error'=>$errors));
            return false;
        }

        $this->trans_model->db->trans_start();
            $count = $this->site_model->get_tbl('trans_spoilage',array('trans_ref'=>$next_ref),array(),array(),true,'*',null,null,true);
            if($count){
                $errors = 'Reference used';
                echo json_encode(array('msg'=>"Reference ".$next_ref." is already used.",'error'=>$errors));
                return false;
            }

            $id = $this->site_model->add_tbl('trans_spoilage',$items);
            $prepared = array();
            foreach ($spoil_cart as $val) {
                $prepared[] = array(
                    'spoil_id' => $id,
                    'item_id'  => (int) $val['item-id'],
                    'qty'      => (double)$val['qty'],
                    'uom'      => $val['item-uom']
                );
            }
            $this->site_model->add_tbl_batch('trans_spoilage_details',$prepared);
            $moves = array();
            foreach ($spoil_cart as $val) {
                $stocks = $this->items_model->get_latest_item_move(array('loc_id'=>$val['loc_id'],'item_id'=>$val['item-id']));
                if (!empty($stocks->curr_item_qty))
                    $last_stock = $stocks->curr_item_qty;
                else
                    $last_stock = 0;

                $moves[] = array(
                    'type_id'  => SPOIL_TRANS,
                    'trans_id' => (int)$id,
                    'trans_ref'=> $next_ref,
                    'loc_id'   => $val['loc_id'],
                    'item_id'  => (int) $val['item-id'],
                    'qty'      => ($val['qty'] * -1),
                    'uom'      => $val['item-uom'],
                    'curr_item_qty'=> $last_stock + ($val['qty'] * -1),
                    'reg_date' => $datetime,
                );
            }
            $this->site_model->add_tbl_batch('item_moves',$moves);
            
            $this->trans_model->save_ref(SPOIL_TRANS,$next_ref);
        $this->trans_model->db->trans_complete();
        $this->session->unset_userdata('spoil_cart');
        site_alert($next_ref." processed",'success');
        echo json_encode(array('msg'=>$next_ref." processed",'error'=>$errors));
    }
    public function void($trans_id){
        $this->load->model('core/trans_model');
        $user = $this->session->userdata('user');
        $trans_type = SPOIL_TRANS;
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
            $this->site_model->update_tbl('trans_spoilage','spoil_id',array('inactive'=>1,'update_date'=>$now),$trans_id);
            $this->site_model->update_tbl('item_moves',array('type_id'=>$trans_type,'trans_id'=>$trans_id),array('inactive'=>1));
        $this->trans_model->db->trans_complete();
        // echo json_encode(array('msg'=>"Transaction Voided"));
        site_alert("Transaction Voided",'success');
    }

    public function upload_excel_form(){
        $this->load->helper('dine/spoilage_helper');

        $data['code'] = spoilageCheckUploadForm();
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
        $this->load->model('dine/menu_model');

        $temp = $this->upload_temp('menu_excel_temp');
        $next_ref = $this->trans_model->get_next_ref(SPOIL_TRANS);
        $next_id = ltrim(ltrim($next_ref, 'S'), '0');

        // echo $next_id;die();
        // echo $next_ref;die();
        // echo $this->trans_model->on_next_ref(SPOIL_TRANS);
        $user = $this->session->userdata('user');
        $user_id = $user['id']; 
        $item_list_raw = $this->items_model->get_item();
        $menu_list_raw = $this->menu_model->get_menus();

        $locations_list_raw = $this->items_model->get_locations();
        $menu_list = $locations_list = $trans_refs = array();
        foreach($menu_list_raw as $item){
            $menu_list[strtolower($item->menu_code)] = array('item_id'=>$item->menu_id);
        }

         foreach($locations_list_raw as $loc){
            $locations_list[strtolower($loc->loc_code)] = $loc->loc_id;
        }

        // echo "<pre>",print_r($menu_list),"</pre>";die();
        // echo $user['id'];
        // echo $next_ref;die();
        if($temp['error'] == ""){
            // echo 'dasda';die();
            $now = $this->site_model->get_db_now('sql');
            $this->load->library('excel');
            $obj = PHPExcel_IOFactory::load($temp['file']);
            $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $start = 1;
            $item_moves = $trans_spoilage  = $rows = array();
            $memo = "";
            $reg_date = date('Y-m-d h:i:s');
            $trans_date = date('Y-m-d');
            for($i=$start;$i<=$count;$i++){
                $item_code = strtolower($sheet[$i]["B"]);
                if(!isset($menu_list[$item_code])){
                    continue;
                }
                if($sheet[$i]["B"] != ""){
                    // echo strtolower($sheet[$i]["B"]);
                    $item_id =  $menu_list[$item_code]['item_id'];
                    $uom =  NULL;//$menu_list[$item_code]['uom'];
                    $qty = $sheet[$i]["C"];
                    if(isset($locations_list[$sheet[$i]["A"]])){
                        $location_id = $locations_list[$sheet[$i]["A"]];
                    }else{
                        $location_id = NULL;
                    }
               
                    $rows[] = array(
                        "spoil_id" => $next_id,
                        "item_id" => $item_id,
                        "qty" => $qty,
                    );


                    $item_moves[] = array("type_id"=>SPOIL_TRANS,"trans_id"=>$next_id,"trans_ref"=>$next_ref,"loc_id"=>$location_id,"item_id"=>$item_id,"qty"=>$qty*-1,'uom'=>$uom,'reg_date'=>$reg_date);
                }
                if($sheet[$i]["D"] != ""){
                    $memo = $sheet[$i]["D"];
                }
            }
            $trans_spoilage = array('type_id'=>SPOIL_TRANS,'trans_ref'=>$next_ref,'spoil_id'=>$next_id,'memo'=>$memo,'user_id'=>$user_id,'trans_date'=>$trans_date);
             $trans_refs = array('type_id'=>SPOIL_TRANS,'trans_ref'=>$next_ref,'user_id'=>$user_id);

            // echo "<pre>",print_r($rows),"</pre>";die();
            if(count($rows) > 0){
                $this->db->trans_start();

                     $this->trans_model->save_ref(SPOIL_TRANS,$next_ref);
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
                        $this->site_model->add_tbl('trans_spoilage',$trans_spoilage);
                        $this->site_model->add_tbl('trans_refs',$trans_refs);
                     // echo "asd";die();  

                        $this->site_model->add_tbl_batch_ignore('trans_spoilage_details',$rows);
                        $this->site_model->add_tbl_batch_ignore('menu_moves',$item_moves);
                $this->db->trans_complete();

                #################################################################################################################################
            }
            unlink($temp['file']);
            site_alert('Markouts successfully uploaded','success');
        }
        else{
            site_alert($temp['error'],"error");
        }
        redirect(base_url()."spoilage", 'refresh'); 
    }

      public function upload_excel_item_db(){
        $this->load->model('core/trans_model');
        $this->load->model('dine/main_model');

        $this->load->model('dine/items_model');
        $temp = $this->upload_temp('menu_excel_temp');
        $next_ref = $this->trans_model->get_next_ref(SPOIL_TRANS);
        $next_id = ltrim(ltrim($next_ref, 'S'), '0');

        // echo $next_id;die();
        // echo $next_ref;die();
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

        // echo "<pre>",print_r($item_list),"</pre>";//die();
        // echo $user['id'];
        // echo $next_ref;die();
        if($temp['error'] == ""){
            // echo 'dasda';die();
            $now = $this->site_model->get_db_now('sql');
            $this->load->library('excel');
            $obj = PHPExcel_IOFactory::load($temp['file']);
            $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $start = 1;
            $item_moves = $trans_spoilage = array();
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
                    if(isset($locations_list[$sheet[$i]["A"]])){
                        $location_id = $locations_list[$sheet[$i]["A"]];
                    }else{
                        $location_id = NULL;
                    }
               
                    $rows[] = array(
                        "spoil_id" => $next_id,
                        "item_id" => $item_id,
                        "qty" => $qty,
                    );


                    $item_moves[] = array("type_id"=>SPOIL_TRANS,"trans_id"=>$next_id,"trans_ref"=>$next_ref,"loc_id"=>$location_id,"item_id"=>$item_id,"qty"=>$qty*-1,'uom'=>$uom,'reg_date'=>$reg_date);
                }
                if($sheet[$i]["D"] != ""){
                    $memo = $sheet[$i]["D"];
                }
            }
            $trans_spoilage = array('type_id'=>SPOIL_TRANS,'trans_ref'=>$next_ref,'spoil_id'=>$next_id,'memo'=>$memo,'user_id'=>$user_id,'trans_date'=>$trans_date);
             $trans_refs = array('type_id'=>SPOIL_TRANS,'trans_ref'=>$next_ref,'user_id'=>$user_id);

            // echo "<pre>",print_r($rows),"</pre>";die();
            if(count($rows) > 0){
                $this->db->trans_start();

                     $this->trans_model->save_ref(SPOIL_TRANS,$next_ref);
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
                        $this->site_model->add_tbl('trans_spoilage',$trans_spoilage);
                        $this->site_model->add_tbl('trans_refs',$trans_refs);
                     // echo "asd";die();  

                        $this->site_model->add_tbl_batch_ignore('trans_spoilage_details',$rows);
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
        redirect(base_url()."spoilage", 'refresh'); 
    }

    public function download_template(){
    //   $data = file_get_contents('php://output'); 
    // $name = 'data.csv';
        // $this->load->helper('download');
        $file = FCPATH.'uploads/temp/spoilage_template.csv';
        // echo $file;//die();
        $name = 'markout_template.csv';
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
}