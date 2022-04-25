<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('dine/items_model');
		$this->load->model('site/site_model');
		$this->load->helper('dine/items_helper');        
	}
	public function index(){
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('items');
        $data['page_title'] = fa('icon-social-dropbox')." Component List"; 
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Category','SRP','Register Date','Inactive','');
        }else{
            $th = array('ID','Name','Category','SRP','Register Date','Inactive'); 
        }
        $data['code'] = create_rtable('items','item_id','items-tbl',$th,'items/search_items_form',true,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/items.php';
        $data['use_js'] = 'listFormJs';
        $data['page_no_padding'] = true;
        // $data['sideBarHide'] = true;
        $this->load->view('page',$data);
    }
    public function get_items($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 2000;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(items.name like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['items.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        if($this->input->post('cat_id')){
            $args['items.cat_id'] = array('use'=>'where','val'=>$this->input->post('cat_id'));
        }
        $join["categories"] = array('content'=>"items.cat_id = categories.cat_id");
        $count = $this->site_model->get_tbl('items',$args,array(),$join,true,'items.*,categories.name as cat_name',null,null,true);
        $page = paginate('items/get_items',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('items',$args,array(),$join,true,'items.*,categories.name as cat_name',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                if(MASTER_BUTTON_EDIT){
                    $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'items/setup/'.$res->item_id,array('class'=>'btn btn-sm blue btn-outline','return'=>'true'));                    
                    $json[$res->item_id] = array(
                        "id"=>$res->item_id,   
                        "title"=>"[".$res->code."] ".ucwords(strtolower($res->name)),   
                        "desc"=>ucwords(strtolower($res->cat_name)),   
                        // "subtitle"=>ucwords(strtolower($res->menu_cat_name)),   
                        "caption"=>"PHP ".num($res->cost),
                        "date_reg"=>sql2Date($res->reg_date),
                        "status"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                }else{
                    $json[$res->item_id] = array(
                        "id"=>$res->item_id,   
                        "title"=>"[".$res->code."] ".ucwords(strtolower($res->name)),   
                        "desc"=>ucwords(strtolower($res->cat_name)),   
                        // "subtitle"=>ucwords(strtolower($res->menu_cat_name)),   
                        "caption"=>"PHP ".num($res->cost),
                        "date_reg"=>sql2Date($res->reg_date),
                        "status"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        
                    );
                }
                // $link = $this->make->A(fa('fa-home'),base_url().'items/setup/'.$res->item_id,array('class'=>'btn btn-block btn-primary','return'=>'true'));
                $ids[] = $res->item_id;
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function search_items_form(){
        $data['code'] = itemsSearchForm();
        $this->load->view('load',$data);
    }
	public function get_subcategories($cat_id = null){
		$results = $this->site_model->get_custom_val('subcategories',
			array('sub_cat_id,name,code'),
			(is_null($cat_id) ? null : 'cat_id'),
			(is_null($cat_id) ? null : $cat_id),
			true);
		$echo_array = array();
		foreach ($results as $val) {
			$echo_array[$val->sub_cat_id] = "[ ".$val->code." ] ".$val->name;
		}
		echo json_encode($echo_array);
	}
	public function setup($item_id = null){
		$data = $this->syter->spawn();
        $img = "";
		if (is_null($item_id))
			$data['page_title'] = fa('icon-social-dropbox')." Add New Component";
		else {
			$item = $this->items_model->get_item($item_id);
			$item = $item[0];
			if (!empty($item->code)) {
				$data['page_title'] = fa('icon-social-dropbox')." ".iSetObj($item,'name');
				if (!empty($item->update_date))
					$data['page_subtitle'] = "Last updated ".$item->update_date;

			} else {
				header('Location:'.base_url().'items/setup');
			}
		    $images = $this->site_model->get_image(null,null,'items',array('images.img_ref_id'=>$item_id)); 
            foreach ($images as $res) {
                $img = $res->img_path;
            }
        }

		$data['code'] = items_form_container($item_id,$img);
		$data['load_js'] = "dine/items.php";
		$data['use_js'] = "itemFormContainerJs";
		$this->load->view('page',$data);
	}
	public function setup_load($item_id = null){
		$details = array();
		if (!is_null($item_id))
			$item = $this->items_model->get_item($item_id);
		if (!empty($item))
			$details = $item[0];

		$data['code'] = items_details_form($details,$item_id);
		$data['load_js'] = "dine/items.php";
		$data['use_js'] = "itemDetailsJs";
		$this->load->view('load',$data);
	}
	public function item_details_db(){
        $this->load->model('dine/main_model');
        $error = "";
        $msg = "";
        $items = $this->site_model->get_custom_val('items',array('item_id'),'code',$this->input->post('code'));
        if($items){
            if ($this->input->post('item_id')) {
                if($this->input->post('item_id') != $items->item_id) 
                    $error = "Item Code is already Taken";
            }
            else
                $error = "Item Code is already Taken";
        }
        if($error == ""){
            $items = array(
                'barcode' => $this->input->post('barcode'),
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'desc' => $this->input->post('desc'),
                'cat_id' => $this->input->post('cat_id'),
                'subcat_id' => $this->input->post('subcat_id'),
                'supplier_id' => $this->input->post('supplier_id'),
                'uom' => $this->input->post('uom'),
                'cost' => $this->input->post('cost'),
                'type' => $this->input->post('type'),
                'no_per_pack' => $this->input->post('no_per_pack'),
                'no_per_pack_uom' => $this->input->post('no_per_pack_uom'),
                'no_per_case' => $this->input->post('no_per_case'),
                'reorder_qty' => $this->input->post('reorder_qty'),
                'max_qty' => $this->input->post('max_qty'),
                'brand' => $this->input->post('brand'),
                'costing' => $this->input->post('costing'),
                'inactive' => (int)$this->input->post('inactive'),
            );
            if ($this->input->post('item_id')) {
                $id = $this->input->post('item_id');
                $this->items_model->update_item($items,$id);
                $msg = "Updated item: ".$items['name'];
                $this->main_model->update_tbl('items','item_id',$items,$id);
            } else {
                $id = $this->items_model->add_item($items);
                $msg = "Added new item: ".$items['name'];
                $this->main_model->add_trans_tbl('items',$items);
            }
        }
        if($error == "")
            site_alert($msg,'success');

		echo json_encode(array('msg'=>$msg,'error'=>$error));
	}
    public function image_db(){
        $image = null;
        $ext = null;
        $msg = "";
        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){
            $info = pathinfo($_FILES['fileUpload']['name']);
            if(isset($info['extension']))
            $ext = $info['extension'];
            $menu = $this->input->post('upid');
            $newname = $menu.".".$ext;
            if (!file_exists("uploads/items/")) {
                mkdir("uploads/items/", 0777, true);
            }
            $target = 'uploads/items/'.$newname;
            if(!move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
                $msg = "Image Upload failed";
            }
            else{
                $new_image = $target;
                $result = $this->site_model->get_image(null,$this->input->post('upid'),'items');
                $items = array(
                    "img_file_name" => $newname,
                    "img_path" => $new_image,
                    "img_ref_id" => $this->input->post('upid'),
                    "img_tbl" => 'items',
                );
                if(count($result) > 0){
                    $this->site_model->update_tbl('images','img_id',$items,$result[0]->img_id);
                }
                else{
                    $id = $this->site_model->add_tbl('images',$items,array('datetime'=>'NOW()'));
                }
            }
        }
        echo json_encode(array('msg'=>$msg));
    }
    ########################################################################
    ### INQURIES
    	public function inventory(){
            $data = $this->syter->spawn('items');
            $this->items_model->db = $this->load->database('default', TRUE);
            $query = $this->items_model->get_curr_item_inv_and_locs();
            // echo $this->items_model->db->last_query();
            $records = array();
            if($query)
                $records = $query->result_array();

            // echo "<pre>", print_r($records), "</pre>"; die();
            $loc_fields = array();
            // if (!empty($records)) {
            //     // $xx = $records[0];
            //     foreach ($records as $k => $v) {
            //         // if (strpos($k, "!!Loc-") === false)
            //         //     continue;
            //         if(isset($loc_fields[$v['loc_name']])){
            //             $row = $loc_fields[$v['loc_name']];
            //             $row['qoh'] += $v['qoh'];
            //         }else{
            //             $loc_fields[$v['loc_name']] = array('qoh'=>$v['qoh']);
            //         }
            //     }
            // }


            $data['code'] = item_inventory_and_location_container($records, $loc_fields);
            // $data['page_title'] = fa('fa-random')." Items Inventory";
            $data['page_title'] = fa('fa-archive').' Quantity On Hand';
            // $data['page_subtitle'] = "Current item count and location";
            $data['load_js'] = "dine/items.php";
            $data['use_js'] = "inventoryJS";
            $this->load->view('page',$data);
        }
        public function inv_move(){
            $data = $this->syter->spawn('items');
            $data['page_title'] = fa('icon-shuffle').' Inventory Movements';
            $data['code'] = invMovePage();           
            $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js','js/jspdf.js');
            $data['load_js'] = "dine/items.php";
            $data['use_js'] = "invMoveJS";
            $this->load->view('page',$data);
        }
        public function get_inv_move($id=null,$asJson=true){
            $this->load->helper('site/pagination_helper');
            $pagi = null;
            // $total_rows = 100;
            if($this->input->post('pagi'))
                $pagi = $this->input->post('pagi');
            $post = array();
            if(count($this->input->post()) > 0){
                $post = $this->input->post();
            }
            $table  = "item_moves";
            $select = "item_moves.*,items.name,items.code,trans_types.name as particular,items.no_per_pack as it_per_pack,items.no_per_pack_uom as it_per_pack_uom";
            $join["items"] = array('content'=>"item_moves.item_id = items.item_id");
            $join["trans_types"] = array('content'=>"item_moves.type_id = trans_types.type_id");
            $args = array();
            $args2 = array();
            $args3 = array();
            if(isset($post['item-search']) && $post['item-search'] != ""){
                $args['item_moves.item_id'] = $post['item-search'];
                $args2['item_moves.item_id'] = $post['item-search'];
                $args3['item_moves.item_id'] = $post['item-search'];
            }
            if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
                $daterange = $post['calendar_range'];
                $dates = explode(" to ",$daterange);
                $from = date2SqlDateTime($dates[0]);
                $to = date2SqlDateTime($dates[1]);
                $args["item_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                $args2["item_moves.reg_date  < '".$from."'"] = array('use'=>'where','val'=>null,'third'=>false);
                $args3["item_moves.reg_date  > '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            }

            // echo constant($post['type']);die();
            if(isset($post['type']) && $post['type'] != ""){
                $args['item_moves.type_id'] = constant($post['type']);
                $args2['item_moves.type_id'] = constant($post['type']);
                $args3['item_moves.type_id'] = constant($post['type']);
            }
            $args['item_moves.inactive'] = 0;
            $args2['item_moves.inactive'] = 0;
            $args3['item_moves.inactive'] = 0;
            $order = array('item_moves.reg_date'=>'asc','items.name'=>'asc');
            $group = null;
            // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
            // $page = paginate('items/get_items',$count,$total_rows,$pagi);
            $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
            // echo $this->db->last_query();
            // echo "<pre>",print_r($items),"</pre>";die();
            // echo $this->site_model->db->last_query();
            $item_moves_sum = $this->items_model->get_item_moves_total(null,$args2);
            // echo $this->site_model->db->last_query();
            $item_moves_after = $this->items_model->get_item_moves_total(null,$args3);
            // return false;

            $before_m_item = 0;
            if($item_moves_sum){
                $before_m_item = $item_moves_sum[0]->in_item_qty;
            }

            $after_m_item = 0;
            if($item_moves_after){
                $after_m_item = $item_moves_after[0]->in_item_qty;
            }

            $json = array();
            $html = "";
            if(count($items) > 0){
                $ctr = 1;
                $last = count($items);
                $colspan = 5;
                $curr = $before_m_item;
                foreach ($items as $res) {
                    if($ctr == 1){
                        $this->make->sRow(array('class'=>'tdhd'));
                            $this->make->sTd(array('colspan'=>$colspan));
                                $this->make->span("Quantity on Hand Before ".sql2Date($from)." ".toTimeW($from));
                            $this->make->eTd();
                            $this->make->td("");
                            $this->make->td("");
                            $this->make->td($before_m_item,array('class'=>'text-right','style'=>'border-right:5px solid #fff !important;'));
                            $this->make->td("");
                            $this->make->td("");
                            $this->make->td("");
                            $c = "";
                            if($res->it_per_pack > 0){
                                $c = $before_m_item/$res->it_per_pack;
                            }    
                            $this->make->td($c,array('class'=>'text-right'));
                        $this->make->eRow();
                    }
                    $this->make->sRow();
                        $this->make->td(ucwords(strtolower($res->particular)));
                        $this->make->td($res->trans_ref);
                        $this->make->td(sql2Date($res->reg_date)." ".toTimeW($res->reg_date));
                        $this->make->td(ucwords(strtolower($res->name)));
                        $this->make->td($res->uom,array('class'=>'text-center'));
                        $in = "";
                        $out = "";
                        // if($res->qty >= 0)
                        //     $in = num($res->qty);
                        // else
                        //     $out = num($res->qty);

                        if($res->qty >= 0){
                            $in = $res->qty;
                            $curr += $res->qty;
                            $out = "";
                        }
                        else{
                            $out = $res->qty;
                            $curr += $res->qty;
                            $out = $out * -1;
                        }

                        $this->make->td($in,array('class'=>'text-right'));
                        $this->make->td($out,array('class'=>'text-right'));
                        $this->make->td($curr,array('class'=>'text-right','style'=>'border-right:1px solid #fff;'));

                        $this->make->td($res->it_per_pack_uom,array('class'=>'text-center'));
                        $in = "";
                        $out = "";
                        $curr2 = "";
                        if($res->it_per_pack > 0){
                            $curr2 = $curr/$res->it_per_pack;
                            if($res->qty >= 0){
                                $val = $res->qty/$res->it_per_pack;
                                $in = num($val);
                            }
                            else{
                                $val = $res->qty/$res->it_per_pack;
                                $out = num($val);
                            }
                        }
                        $this->make->td($in,array('class'=>'text-right'));
                        $this->make->td($out,array('class'=>'text-right'));
                        $this->make->td($curr2,array('class'=>'text-right'));
                    
                    $this->make->eRow();

                    // if($ctr == $last){
                    //     $this->make->sRow(array('class'=>'tdhd'));
                    //         $this->make->sTd(array('colspan'=>$colspan));
                    //             $this->make->span("Quantity on Hand Before ".sql2Date($res->reg_date)." ".toTimeW($res->reg_date));
                    //         $this->make->eTd();
                    //         $this->make->td("");
                    //         $this->make->td("");
                    //         $this->make->td(num($res->curr_item_qty)." ".$res->uom,array('class'=>'text-right','style'=>'border-right:5px solid #fff !important;'));
                    //         $this->make->td("");
                    //         $this->make->td("");
                    //         $this->make->td("");
                    //         $c = "";
                    //         if($res->it_per_pack > 0){
                    //             $c = $res->curr_item_qty/$res->it_per_pack;
                    //         } 
                    //         $this->make->td(num($c)." ".$res->it_per_pack_uom,array('class'=>'text-right'));
                    //     $this->make->eRow();
                    // }
                    $ctr++;
                }

                $this->make->sRow(array('class'=>'tdhd'));
                    $this->make->sTd(array('colspan'=>$colspan));
                        $this->make->span("Quantity on Hand After ".sql2Date($res->reg_date)." ".toTimeW($res->reg_date));
                    $this->make->eTd();
                    $this->make->td("");
                    $this->make->td("");
                    $this->make->td($curr + $after_m_item,array('class'=>'text-right','style'=>'border-right:5px solid #fff !important;'));
                    $this->make->td("");
                    $this->make->td("");
                    $this->make->td("");
                    $c = "";
                    if($res->it_per_pack > 0){
                        $c = ($curr + $after_m_item)/$res->it_per_pack;
                    } 
                    $this->make->td($c,array('class'=>'text-right'));
                $this->make->eRow();


                $json['html'] = $this->make->code();
            }
            echo json_encode($json);
        }

        //menu mocvement
        public function menu_move(){
            $data = $this->syter->spawn('inq');
            $data['page_title'] = fa('fa-random').' Item Movements';
            $data['code'] = menuMovePage();           
            $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js','js/jspdf.js');
            $data['load_js'] = "dine/items.php";
            $data['use_js'] = "menuMoveJS";
            $this->load->view('page',$data);
        }

        public function get_menu_move($id=null,$asJson=true){
            sess_clear('menu_moves_cart');
            $this->load->helper('site/pagination_helper');
            $pagi = null;
            // $total_rows = 100;
            if($this->input->post('pagi'))
                $pagi = $this->input->post('pagi');
            $post = array();
            if(count($this->input->post()) > 0){
                $post = $this->input->post();
            }

            $daterange = $post['calendar_range'];
            $dates = explode(" to ",$daterange);
            // $from = date2SqlDateTime($dates[0]);
            // $to = date2SqlDateTime($dates[1]);

            $from_date = date2Sql($dates[0]); 
            $to_date = date2Sql($dates[1]);

            $strtt =  strtotime($from_date);
            $moves_array = array();
            $curr_date = date('Y-m-d');
            do{
                $this->site_model->db = $this->load->database('main', TRUE);

                $datefrom = date('Y-m-d', $strtt);

                $table  = "menu_moves";
                $select = "menu_moves.*,menus.menu_name,menus.menu_code,trans_types.name as particular";
                $join["menus"] = array('content'=>"menu_moves.item_id = menus.menu_id");
                $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
                $args = array();
                // $args2 = array();
                if(isset($post['menu-search']) && $post['menu-search'] != ""){
                    $args['menu_moves.item_id'] = $post['menu-search'];
                }

            // if(isset($post['type']) && $post['type'] != ""){
                
            //         $args['menu_moves.type_id'] = constant($post['type']);
            //         $args2['menu_moves.type_id'] = constant($post['type']);
            //         $args3['menu_moves.type_id'] = constant($post['type']);
            //         $args4['menu_moves.type_id'] = constant($post['type']);
            // }
                // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
                    // $daterange = $post['calendar_range'];
                    // $dates = explode(" to ",$daterange);
                    // $from = date2SqlDateTime($dates[0]);
                    // $to = date2SqlDateTime($dates[1]);
                    // $args["menu_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                $args["DATE(menu_moves.reg_date)  = '".$datefrom."'"] = array('use'=>'where','val'=>null,'third'=>false);
                // }
                $args['menu_moves.inactive'] = 0;
                $order = array('menu_moves.reg_date'=>'desc','menus.menu_name'=>'asc');
                $group = null;
                // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
                // $page = paginate('items/get_items',$count,$total_rows,$pagi);
                $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
                // echo $this->site_model->db->last_query();die();
                // return false;
                // $args['items.barcode'] = array('use'=>'where','val'=>$barcode);
                // echo $this->db->last_query();
                // echo "<pre>",print_r($items),"</pre>";die();
                if($items){
                    foreach($items as $res){
                        $ids = strtotime($res->reg_date);
                        if(isset($moves_array[$res->item_id][$res->trans_ref])){
                            $moves_array[$res->item_id][$res->trans_ref]['qty'] += $res->qty;
                        }else{
                            $moves_array[$res->item_id][$res->trans_ref] = array(
                                'particular'=>$res->particular,
                                'trans_ref'=>$res->trans_ref,
                                'reg_date'=>$res->reg_date,
                                'code'=>$res->menu_code,
                                'name'=>$res->menu_name,
                                'qty'=>$res->qty,
                            );
                        }
                    }
                }

            //     $tablem = "trans_sales_menus";
            //     $selectm = "trans_sales_menus.*,menus.menu_name,menus.menu_code,trans_types.name as particular,trans_sales.datetime, trans_sales.trans_ref";
            //     // $selectm_s = "sum(qty) as menu_sales_qty";
            //     $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menus.sales_id");
            //     $joinm["menus"] = array('content'=>"menus.menu_id = trans_sales_menus.menu_id");
            //     $joinm["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
            //     $args3 = array();
            //     $args4 = array();
            //     // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
            //     // $args3["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            //     // $args3["trans_sales.type_id"] = 10;
            //     // $args3["trans_sales.inactive"] = '0';

            //     $args4["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            //     $args4["trans_sales.type_id"] = 10;
            //     $args4["trans_sales.inactive"] = '0';

            //  if(isset($post['type']) && $post['type'] != ""){
                
            //         $args4['trans_sales.type_id'] = constant($post['type']);
            //         // $args2['menu_moves.type_id'] = constant($post['type']);
            //         // $args3['menu_moves.type_id'] = constant($post['type']);
            //         // $args4['menu_moves.type_id'] = constant($post['type']);
            // }

            //     if(isset($post['menu-search']) && $post['menu-search'] != ""){
            //         // $args2['menu_moves.item_id'] = $post['menu-search'];
            //         // $args3['trans_sales_menus.menu_id'] = $post['menu-search'];
            //         $args4['trans_sales_menus.menu_id'] = $post['menu-search'];
            //     }
            //     // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
            //     //     $daterange = $post['calendar_range'];
            //     //     $dates = explode(" to ",$daterange);
            //     //     $from = date2SqlDateTime($dates[0]);
            //     //     $to = date2SqlDateTime($dates[1]);
            //     //     $fromD = date2Sql($dates[0]);
            //     // $args2["DATE(menu_moves.reg_date)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            //     // $args3["DATE(trans_sales.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            //     $args4["DATE(trans_sales.datetime) = '".$datefrom."'"] = array('use'=>'where','val'=>null,'third'=>false);
            //     // }

            //     $ordermm = null;
            //     $groupmm = null;
            //     if($curr_date == $datefrom){
            //         $this->site_model->db = $this->load->database('default', TRUE);
            //         $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm); 
            //     }else{
            //         $this->site_model->db = $this->load->database('main', TRUE);
            //         $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm); 
            //     }

            //     // echo $this->db->last_query().'<br><br>';
            //     if($get_menus_sales){
            //         foreach($get_menus_sales as $res){
            //             $ids = strtotime($res->datetime);
            //             if(isset($moves_array[$ids][$res->trans_ref])){
            //                 $moves_array[$ids][$res->trans_ref]['qty'] -= $res->qty;
            //             }else{
            //                 $moves_array[$ids][$res->trans_ref] = array(
            //                     'particular'=>$res->particular,
            //                     'trans_ref'=>$res->trans_ref,
            //                     'reg_date'=>$res->datetime,
            //                     'code'=>$res->menu_code,
            //                     'name'=>$res->menu_name,
            //                     'qty'=>-$res->qty,
            //                 );
            //             }
            //         }
            //     }


                $strtt = strtotime($datefrom . ' + 1 day');

            }while($datefrom != $to_date);


                
            // echo "<pre>",print_r($moves_array),"</pre>";die();





            // $table  = "menu_moves";
            // $select = "menu_moves.*,menus.menu_name,menus.menu_code,trans_types.name as particular";
            // $join["menus"] = array('content'=>"menu_moves.item_id = menus.menu_id");
            // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
            // $args = array();
            // $args2 = array();
            // if(isset($post['menu-search']) && $post['menu-search'] != ""){
            //     $args['menu_moves.item_id'] = $post['menu-search'];
            // }
            // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
            //     $daterange = $post['calendar_range'];
            //     $dates = explode(" to ",$daterange);
            //     $from = date2SqlDateTime($dates[0]);
            //     $to = date2SqlDateTime($dates[1]);
            //     $args["menu_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // }
            // $args['menu_moves.inactive'] = 0;
            // $order = array('menu_moves.reg_date'=>'desc','menus.menu_name'=>'asc');
            // $group = null;
            // // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
            // // $page = paginate('items/get_items',$count,$total_rows,$pagi);
            // $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
            // // echo $this->site_model->db->last_query();
            // // return false;
            // // $args['items.barcode'] = array('use'=>'where','val'=>$barcode);
            // // echo $this->db->last_query();
            // // echo "<pre>",print_r($items),"</pre>";die();

            // $args2 = array();

            // $tablem = "trans_sales_menus";
            // $selectm = "trans_sales_menus.*,menus.menu_name,menus.menu_code,trans_types.name as particular,trans_sales.datetime, trans_sales.trans_ref";
            // $selectm_s = "sum(qty) as menu_sales_qty";
            // $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menus.sales_id");
            // $joinm["menus"] = array('content'=>"menus.menu_id = trans_sales_menus.menu_id");
            // $joinm["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
            // $args3 = array();
            // // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
            // $args3["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args3["trans_sales.type_id"] = 10;
            // $args3["trans_sales.inactive"] = '0';


            // if(isset($post['menu-search']) && $post['menu-search'] != ""){
            //     $args2['menu_moves.item_id'] = $post['menu-search'];
            //     $args3['trans_sales_menus.menu_id'] = $post['menu-search'];
            // }
            // // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
            //     // $daterange = $post['calendar_range'];
            //     // $dates = explode(" to ",$daterange);
            //     // $from = date2SqlDateTime($dates[0]);
            //     // $to = date2SqlDateTime($dates[1]);
            //     $fromD = date2Sql($dates[0]);
            //     $args2["DATE(menu_moves.reg_date)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            //     $args3["DATE(trans_sales.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            //     // $args4["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // // }
            // $menu_moves_sum = $this->items_model->get_menu_moves_total(null,$args2);

            // $orderm = null;
            // $groupm = 'trans_sales_menus.menu_id';
            // // $menus_sales_total = $this->site_model->get_tbl($tablem,$args3,$orderm,$joinm,true,$selectm_s,$groupm);
            // $menus_sales_total = array();

            // $before_s_menu = 0;
            // if($menus_sales_total){
            //     $before_s_menu = $menus_sales_total[0]->menu_sales_qty;
            // }
            // $before_m_menu = 0;
            // if($menu_moves_sum){
            //     $before_m_menu = $menu_moves_sum[0]->in_menu_qty;
            // }

            // $before_qty = (int) $before_m_menu - (int) $before_s_menu;

            // $ordermm = null;
            // $groupmm = null;
            // $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm);

            // // echo $this->db->last_query();
            // // echo "<pre>",print_r($items),"</pre>";die();

            // $moves_array = array();
            // foreach($items as $res){
            //     if(isset($moves_array[$res->reg_date][$res->trans_ref])){
            //         $moves_array[$res->reg_date][$res->trans_ref]['qty'] += $res->qty;
            //     }else{
            //         $moves_array[$res->reg_date][$res->trans_ref] = array(
            //             'particular'=>$res->particular,
            //             'trans_ref'=>$res->trans_ref,
            //             'reg_date'=>$res->reg_date,
            //             'code'=>$res->menu_code,
            //             'name'=>$res->menu_name,
            //             'qty'=>$res->qty,
            //         );
            //     }
            // }

            // foreach($get_menus_sales as $res){
            //     if(isset($moves_array[$res->datetime][$res->trans_ref])){
            //         $moves_array[$res->datetime][$res->trans_ref]['qty'] -= $res->qty;
            //     }else{
            //         $moves_array[$res->datetime][$res->trans_ref] = array(
            //             'particular'=>$res->particular,
            //             'trans_ref'=>$res->trans_ref,
            //             'reg_date'=>$res->datetime,
            //             'code'=>$res->menu_code,
            //             'name'=>$res->menu_name,
            //             'qty'=>-$res->qty,
            //         );
            //     }
            // }

            ksort($moves_array);
            // echo $this->db->last_query();
            // echo "<pre>",print_r($moves_array),"</pre>";die();

            $json = array();
            $html = "";
            // $colspan = 5;
            // $this->make->sRow(array('class'=>'tdhd'));
            //     $this->make->sTd(array('colspan'=>$colspan));
            //         $this->make->span("Quantity on Hand Before ".sql2Date($fromD));
            //     $this->make->eTd();
            //     $this->make->td("");
            //     $this->make->td(num($before_qty),array('class'=>'text-right','style'=>'border-right:5px solid #fff !important;'));
            //     $c = "";
            //     // if($res->it_per_pack > 0){
            //     //     $c = $res->curr_item_qty/$res->it_per_pack;
            //     // }    
            //     // $this->make->td(num($c)." ".$res->it_per_pack_uom,array('class'=>'text-right'));
            // $this->make->eRow();
            if(count($moves_array) > 0){
                $this->session->set_userdata('menu_moves_cart',$moves_array);
                $ctr = 1;
                $last = count($moves_array);
                // $curr = $before_qty;
                foreach ($moves_array as $dt =>$value) {

                    $args2 = array();
                    // if(isset($post['menu-search']) && $post['menu-search'] != ""){
                        // $args2['menu_moves.item_id'] = $post['menu-search'];
                        $args2['menu_moves.item_id'] = $dt;
                    // }
                    // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
                        // $daterange = $post['calendar_range'];
                        // $dates = explode(" to ",$daterange);
                        // $from = date2SqlDateTime($dates[0]);
                        // $to = date2SqlDateTime($dates[1]);
                    $fromD = date2Sql($dates[0]);
                    $args2["DATE(menu_moves.reg_date)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
                        // $args3["DATE(trans_sales.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
                        // $args4["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                    // }
                    $menu_moves_sum = $this->items_model->get_menu_moves_total(null,$args2);

                    $before_m_menu = 0;
                    if($menu_moves_sum){
                        $before_m_menu = $menu_moves_sum[0]->in_menu_qty;
                    }

                    $before_qty = (int) $before_m_menu;

                    $curr = $before_qty;

                    $colspan = 5;

                    $where = array('menu_id'=>$dt);
                    $menudet = $this->site_model->get_details($where,'menus');

                    $this->make->sRow(array('class'=>'tdhd'));
                        $this->make->sTd(array('colspan'=>$colspan));
                            $this->make->span($menudet[0]->menu_name." Quantity on Hand Before ".sql2Date($fromD));
                        $this->make->eTd();
                        $this->make->td("");
                        $this->make->td(num($before_qty),array('class'=>'text-right','style'=>'border-right:5px solid #fff !important;'));
                        $c = "";
                        // if($res->it_per_pack > 0){
                        //     $c = $res->curr_item_qty/$res->it_per_pack;
                        // }    
                        // $this->make->td(num($c)." ".$res->it_per_pack_uom,array('class'=>'text-right'));
                    $this->make->eRow();

                    foreach ($value as $key => $res) {
                    

                        $this->make->sRow();
                            $this->make->td(ucwords(strtolower($res['particular'])));
                            $this->make->td($res['trans_ref']);
                            $this->make->td(sql2Date($res['reg_date'])." ".toTimeW($res['reg_date']));
                            $this->make->td("[".$res['code']."] ".ucwords(strtolower($res['name'])));
                            // $this->make->td($res->uom,array('class'=>'text-center'));
                            $in = "0";
                            $out = "0";
                            if($res['qty'] >= 0){
                                $in = $res['qty'];
                                $curr += $res['qty'];
                            }
                            else{
                                $out = $res['qty'];
                                $curr += $res['qty'];
                            }

                            $this->make->td($in,array('class'=>'text-right'));
                            $this->make->td($out * -1,array('class'=>'text-right'));
                            // $this->make->td(num($res->curr_item_qty),array('class'=>'text-right','style'=>'border-right:1px solid #fff;'));
                            $this->make->td($curr,array('class'=>'text-right','style'=>'border-right:1px solid #fff;'));

                            // $this->make->td($res->it_per_pack_uom,array('class'=>'text-center'));
                            // $in = "";
                            // $out = "";
                            // $curr2 = "";
                            // if($res->it_per_pack > 0){
                            //     $curr2 = $res->curr_item_qty/$res->it_per_pack;
                            //     if($res->qty >= 0){
                            //         $val = $res->qty/$res->it_per_pack;
                            //         $in = num($val);
                            //     }
                            //     else{
                            //         $val = $res->qty/$res->it_per_pack;
                            //         $out = num($val);
                            //     }
                            // }
                            // $this->make->td($in,array('class'=>'text-right'));
                            // $this->make->td($out,array('class'=>'text-right'));
                            // $this->make->td(num($curr2),array('class'=>'text-right'));
                        
                        $this->make->eRow();

                    }

                }
            }
            $json['html'] = $this->make->code();
            // if(count($items) > 0){
            //     $ctr = 1;
            //     $last = count($items);
            //     $colspan = 5;
            //     foreach ($items as $res) {
            //         if($ctr == 1){
            //             $this->make->sRow(array('class'=>'tdhd'));
            //                 $this->make->sTd(array('colspan'=>$colspan));
            //                     $this->make->span("Quantity on Hand After ".sql2Date($res->reg_date)." ".toTimeW($res->reg_date));
            //                 $this->make->eTd();
            //                 $this->make->td("");
            //                 $this->make->td("");
            //                 $this->make->td(num($res->curr_item_qty)." ".$res->uom,array('class'=>'text-right','style'=>'border-right:5px solid #fff !important;'));
            //                 $this->make->td("");
            //                 $this->make->td("");
            //                 $this->make->td("");
            //                 $c = "";
            //                 if($res->it_per_pack > 0){
            //                     $c = $res->curr_item_qty/$res->it_per_pack;
            //                 }    
            //                 $this->make->td(num($c)." ".$res->it_per_pack_uom,array('class'=>'text-right'));
            //             $this->make->eRow();
            //         }
            //         $this->make->sRow();
            //             $this->make->td(ucwords(strtolower($res->particular)));
            //             $this->make->td($res->trans_ref);
            //             $this->make->td(sql2Date($res->reg_date)." ".toTimeW($res->reg_date));
            //             $this->make->td("[".$res->code."] ".ucwords(strtolower($res->name)));
            //             $this->make->td($res->uom,array('class'=>'text-center'));
            //             $in = "";
            //             $out = "";
            //             if($res->qty >= 0)
            //                 $in = num($res->qty);
            //             else
            //                 $out = num($res->qty);
            //             $this->make->td($in,array('class'=>'text-right'));
            //             $this->make->td($out,array('class'=>'text-right'));
            //             $this->make->td(num($res->curr_item_qty),array('class'=>'text-right','style'=>'border-right:1px solid #fff;'));

            //             $this->make->td($res->it_per_pack_uom,array('class'=>'text-center'));
            //             $in = "";
            //             $out = "";
            //             $curr2 = "";
            //             if($res->it_per_pack > 0){
            //                 $curr2 = $res->curr_item_qty/$res->it_per_pack;
            //                 if($res->qty >= 0){
            //                     $val = $res->qty/$res->it_per_pack;
            //                     $in = num($val);
            //                 }
            //                 else{
            //                     $val = $res->qty/$res->it_per_pack;
            //                     $out = num($val);
            //                 }
            //             }
            //             $this->make->td($in,array('class'=>'text-right'));
            //             $this->make->td($out,array('class'=>'text-right'));
            //             $this->make->td(num($curr2),array('class'=>'text-right'));
                    
            //         $this->make->eRow();

            //         if($ctr == $last){
            //             $this->make->sRow(array('class'=>'tdhd'));
            //                 $this->make->sTd(array('colspan'=>$colspan));
            //                     $this->make->span("Quantity on Hand Before ".sql2Date($res->reg_date)." ".toTimeW($res->reg_date));
            //                 $this->make->eTd();
            //                 $this->make->td("");
            //                 $this->make->td("");
            //                 $this->make->td(num($res->curr_item_qty)." ".$res->uom,array('class'=>'text-right','style'=>'border-right:5px solid #fff !important;'));
            //                 $this->make->td("");
            //                 $this->make->td("");
            //                 $this->make->td("");
            //                 $c = "";
            //                 if($res->it_per_pack > 0){
            //                     $c = $res->curr_item_qty/$res->it_per_pack;
            //                 } 
            //                 $this->make->td(num($c)." ".$res->it_per_pack_uom,array('class'=>'text-right'));
            //             $this->make->eRow();
            //         }
            //         $ctr++;
            //     }
            //     $json['html'] = $this->make->code();
            // }
            echo json_encode($json);
        }

        
    ########################################################################
    ### PRINTING    
    	public function print_inventory(){
    			$this->load->library('Excel');
                $sheet = $this->excel->getActiveSheet();
    			$this->load->model('dine/items_model');

    			$get_inventory = $this->items_model->get_inventory_moves();
    			$fields = $get_inventory->result_array();

    			$fields = array();
    			if (!empty($get_inventory)) {
    				$row = $get_inventory[0];
    				foreach ($row as $k => $v) {
    					if (strpos($k, "!!Trans-") === false)
    						continue;

    					$fields[$k] = str_replace("!!Trans-", "", $k);
    				}
    			}

    			/*Print Headers*/
    			// Print "Item Code"
    			// Print "Item Name"
    			foreach ($fields as $i => $iv) {
    				// Print $iv
    			}

    			foreach ($get_inventory as $inv) {
    				// set initial column (eg. A) to be used as $sheet->getColumnDimension('A')

    				// Print $inv['code']
    				// Print $inv['name']

    				$initial_column = "C";
    				foreach ($fields as $kf => $kv) {
    					// Print $inv[$kf]
    					++$initial_column;
    				}
    			}



                //$date = $this->input->get('date_to');
                //$date_param = (is_null($date) ? date('Y-m-d') : $date);
                //$terminal = $this->input->get('terminal');
                //$cashier = $this->input->get('cashier');

                // $sheet->getColumnDimension('A')->setWidth(15);
                // $sheet->getColumnDimension('B')->setWidth(5);
                // $sheet->getColumnDimension('C')->setWidth(15);
                // //-----------------------------------------------------------------------------
                // //START HEADER
                // //-----------------------------------------------------------------------------
                // $rc = 1;
                // $filename='Hourly Sales Report';
                // $sheet->getCell('A'.$rc)->setValue($filename);
                // $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->setBold(true);
                // $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->getColor()->setRGB('FF0000');

                // if (!empty($cashier)){
                //     $cashier_name = $cashier;
                // }else{
                //     $cashier_name = 'All Cashier';
                // }
                // $rc++;
                // $sheet->getCell('A'.$rc)->setValue('Employee');
                // $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
                // $sheet->getCell('B'.$rc)->setValue($cashier_name);

                // if (!empty($terminal)){
                //     $terminal_name = $terminal;
                // }else{
                //     $terminal_name = 'All Terminal';
                // }
                // $rc++;
                // $sheet->getCell('A'.$rc)->setValue('PC');
                // $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
                // $sheet->getCell('B'.$rc)->setValue($terminal_name);

                // $rc++;
                // $sheet->getCell('A'.$rc)->setValue('Date');
                // $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
                // // $sheet->getCell('B'.$rc)->setValue(date("Y-m-d H:i:s"));
                // $sheet->getCell('B'.$rc)->setValue($date_param);

                // $rc++;
                // $sheet->getCell('A'.$rc)->setValue('Printed on');
                // $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
                // // $sheet->getCell('B'.$rc)->setValue(date("Y-m-d H:i:s"));
                // $sheet->getCell('B'.$rc)->setValue(date("d M y g:i:s A"));
                // $rc++;
                // $sheet->getStyle('A'.$rc.':'.'D'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);

                // //-----------------------------------------------------------------------------
                // //END HEADER
                // //-----------------------------------------------------------------------------

                // $rc++;

                // $ctr=1;
                // $gtotal_net_sales = 0;
                // foreach(unserialize(TIMERANGES) as $k=>$v){
                //     $rc++;
                //     $sheet->getCell('B'.$rc)->setValue($ctr.' '.$v['FTIME'].' - '.$v['TTIME']);
                //     $rc++;
                //     $sheet->getCell('A'.$rc)->setValue('Net Sales Total');

                //     $net_sales_total = $this->settings_model->get_hourly_sales(null,$v['FTIME'],$v['TTIME'],$date);
                //     $net_sales_total = $net_sales_total[0];
                //     $col_a = $col_b = 0;
                //     // $sheet->getCell('C'.$rc)->setValue($col_a);
                //     $col_b = $net_sales_total;
                //     // $sheet->getCell('A'.$rc)->setValue('-->'.$col_b->total_per_hour);
                //     // $sheet->getCell('A'.$rc)->setValue($this->db->last_query());
                //     $sheet->getCell('D'.$rc)->setValue(number_format($col_b->total_per_hour,2));
                //     $gtotal_net_sales += $col_b->total_per_hour;

                //     $rc++;
                //     $sheet->getCell('A'.$rc)->setValue('Average $/Cover');

                //     $col_a = $col_b = 0;
                //     $sheet->getCell('C'.$rc)->setValue($col_a);
                //     $sheet->getCell('D'.$rc)->setValue(number_format($col_b,2));

                //     $rc++;
                //     $sheet->getCell('A'.$rc)->setValue('Average $/Check');

                //     $col_a = $col_b = 0;
                //     $sheet->getCell('C'.$rc)->setValue($col_a);
                //     $sheet->getCell('D'.$rc)->setValue(number_format($col_b,2));

                //     $ctr++;
                // }

                // $rc++;
                // $sheet->getCell('A'.$rc)->setValue('TOTAL');
                // $rc++;
                // $sheet->getCell('A'.$rc)->setValue('Net Sales Total');
                // $sheet->getCell('D'.$rc)->setValue(number_format($gtotal_net_sales,2));


                // Redirect output to a client’s web browser (Excel2007)
                //clean the output buffer
                // ob_end_clean();

                // header('Content-type: application/vnd.ms-excel');
                // header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
                // header('Cache-Control: max-age=0');
                // $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                // $objWriter->save('php://output');

                $filename='inventory'.phpNow().'.xls';
    	        header('Content-Type: application/vnd.ms-excel');
    	        header('Content-Disposition: attachment;filename="'.$filename.'"');
    	        header('Cache-Control: max-age=0');
    	        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    	        $objWriter->save('php://output');
        }
        public function list_excel(){
            $this->load->library('Excel');
            $sheet = $this->excel->getActiveSheet();

            $table = "items";
            $join = array(
                'categories'    => 'items.cat_id = categories.cat_id',
                'subcategories' => 'items.subcat_id = subcategories.cat_id',
            );
            $select = 'items.*,categories.code as cat_code,subcategories.code as sub_cat_code';
            $args = array();
            $order = array('categories.code'=>'asc','subcategories.code'=>'asc','items.name'=>'asc');
            $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select);

            $rows = array();
            foreach ($items as $res) {
                $row = array(
                    'CODE' => $res->code,
                    'BARCODE' => $res->barcode,
                    'NAME' => $res->name,
                    'CATEGORY' => $res->cat_code,
                    'SUBCATEGORY' => $res->sub_cat_code,
                    'UOM' => $res->uom,
                    'COST' => $res->cost,
                );
                $rows[] = $row;
            }
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
            $rc = 1;
            $sheet->getColumnDimension('A')->setWidth(40);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(30);
            $sheet->getColumnDimension('D')->setWidth(30);
            $sheet->getColumnDimension('E')->setWidth(30);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(20);
            $headers = array('A'=>'CODE','B'=>'BARCODE','C'=>'NAME','D'=>'CATEGORY','E'=>'SUBCATEGORY','F'=>'UOM','G'=>'COST');
            foreach ($headers as $let => $text) {
                $sheet->getCell($let.$rc)->setValue($text);
                // $sheet->getStyle($let.$rc)->getFont()->setBold(true);
                // $sheet->getStyle($let.$rc)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CCFFFF');
                $sheet->getStyle($let.$rc)->applyFromArray($styleHeaderCell);
            }
            $rc+=1;
            foreach ($rows as $row) {
                foreach ($headers as $let => $text) {
                    $sheet->getCell($let.$rc)->setValue($row[$text]);
                    $sheet->getStyle($let.$rc)->applyFromArray($styleTxt);
                }
                $rc++;
            }
            $filename = 'Item List.xls';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            $objWriter->save('php://output');
    	}
    ########################################################################    
    ### UPLOADING
    public function upload_sub_cat_items(){
        $this->load->library('excel');
        $obj = PHPExcel_IOFactory::load('barcino_items2.xlsx');
        $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
        $count = count($sheet);
        $start = 2;
        $rows = array();
        $query = "";
        for($i=$start;$i<=$count;$i++){
            $rows[] = array(
                // "item_code"         => $sheet[$i]["A"],
                // "barcode"           => $sheet[$i]["B"],
                // "name"              => $sheet[$i]["C"],
                "category"          => $sheet[$i]["F"],
                "subcategory"       => $sheet[$i]["G"],
                // "uom"               => $sheet[$i]["G"],
                // "cost"              => $sheet[$i]["H"],
            );
        }
        $cats = array();
        foreach ($rows as $row) {
            if(!isset($cats[$row['category']]) && $row['category'] != ""){
                $cats[$row['category']] = $row['category'];
            }
        }
        $item_categories = $this->site_model->get_tbl('categories');
        $db_cats = array();
        foreach ($item_categories as $res) {
            $db_cats[$res->code] = $res->code;
        }
        foreach ($cats as $cat) {
            if(!in_array($cat, $db_cats)){
                echo "walang ".$cat."<br>";
            }
        }

        $item_categories = $this->site_model->get_tbl('categories');
        $db_cats = array();
        foreach ($item_categories as $res) {
            $db_cats[$res->code] = $res->cat_id;
        }

        $items = array();
        $ctr = 0;
        foreach ($rows as $row) {
            if(!isset($items[$row['subcategory']]) && $row['subcategory'] != ""){
                $det =  array(
                    'cat_id'            => $db_cats[$row['category']],
                    'code'              => $row['subcategory'],
                    'name'              => $row['subcategory'],
                );
                $query .= $this->db->insert_string('subcategories',$det).";\r\n";
                $items[$row['subcategory']] = $det;
                $ctr++;
            }
        }
        $query .= "#".$ctr;
        echo "<pre>".$query."</pre>";
    }    
    public function upload_items(){
        $this->load->library('excel');
        $obj = PHPExcel_IOFactory::load('barcino_items2.xlsx');
        $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
        $count = count($sheet);
        $start = 2;
        $rows = array();
        $query = "";
        for($i=$start;$i<=$count;$i++){
            // if($sheet[$i]["B"] != ""){
                $rows[] = array(                        
                    "item_code"         => $sheet[$i]["B"],
                    "barcode"           => $sheet[$i]["C"],
                    "name"              => $sheet[$i]["D"],
                    "category"          => $sheet[$i]["F"],
                    "subcategory"       => $sheet[$i]["G"],
                    "uom"               => $sheet[$i]["H"],
                    "cost"              => $sheet[$i]["I"],
                );
            // }
        }
        // $uoms = array();
        // foreach ($rows as $row) {
        //     if(!isset($uoms[$row['uom']]) && $row['uom'] != ""){
        //         $items =  array(
        //             'code' => $row['uom'],
        //             'name' => $row['uom'],
        //         );
        //         $query .= $this->db->insert_string('uom',$items).";\r\n";
        //         $uoms[$row['uom']] = $items;
        //     }
        // }

        $cats = array();
        foreach ($rows as $row) {
            if(!isset($cats[$row['category']]) && $row['category'] != ""){
                $cats[$row['category']] = $row['category'];
            }
        }
        $item_categories = $this->site_model->get_tbl('categories');
        $db_cats = array();
        foreach ($item_categories as $res) {
            $db_cats[$res->code] = $res->code;
        }
        foreach ($cats as $cat) {
            if(!in_array($cat, $db_cats)){
                echo "walang ".$cat."<br>";
            }
        }

        $sub = array();
        foreach ($rows as $row) {
            if(!isset($sub[$row['subcategory']]) && $row['subcategory'] != ""){
                $sub[$row['subcategory']] = $row['subcategory'];
            }
        }
        $sub_categories = $this->site_model->get_tbl('subcategories');
        $db_sub = array();
        foreach ($sub_categories as $res) {
            $db_sub[$res->code] = $res->code;
        }
        foreach ($sub as $cat) {
            if(!in_array($cat, $db_sub)){
                echo "walang ".$cat."<br>";
            }
        }


        $item_categories = $this->site_model->get_tbl('categories');
        $db_cats = array();
        foreach ($item_categories as $res) {
            $db_cats[$res->code] = $res->cat_id;
        }
        $sub_categories = $this->site_model->get_tbl('subcategories');
        $db_sub = array();
        foreach ($sub_categories as $res) {
            $db_sub[$res->code] = $res->sub_cat_id;
        }
        $uoms = $this->site_model->get_tbl('uom');
        $db_uom = array();
        foreach ($uoms as $res) {
            $db_uom[strtoupper($res->code)] = $res->code;
        }

        $items = array();
        $ctr = 1;
        foreach ($rows as $row) {
            if(!isset($items[$row['item_code']]) && $row['item_code'] != ""){
                $sub = "";
                if(isset($db_sub[$row['subcategory']]))
                    $sub = $db_sub[$row['subcategory']];
                $det =  array(
                    'code'              => $row['item_code'],
                    'name'              => $row['name'],
                    'desc'              => $row['name'],
                    "barcode"           => $row['barcode'],
                    "cat_id"            => $db_cats[$row['category']],
                    "subcat_id"         => $sub,
                    "uom"               => $db_uom[$row['uom']],
                    "cost"              => $row['cost'],
                );
                $query .= $this->db->insert_string('items',$det).";\r\n";
                $items[$row['item_code']] = $det;
                $ctr++;
            }
        }

        $query .= "#".$ctr;
        echo "<pre>".$query."</pre>";
    }
    public function upload_menu_recipe(){
        $this->load->library('excel');
        $obj = PHPExcel_IOFactory::load('barcino_recipe.xlsx');
        $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
        $count = count($sheet);
        $start = 2;
        $rows = array();
        $query = "";
        for($i=$start;$i<=$count;$i++){
            $rows[] = array(
                "menu_code"         => $sheet[$i]["A"],
                "item_code"         => $sheet[$i]["B"],
                "qty"               => $sheet[$i]["C"],
                "uom"               => $sheet[$i]["D"],
                "cost"              => $sheet[$i]["E"],
            );
        }
        
        $sub = array();
        foreach ($rows as $row) {
            if(!isset($sub[$row['item_code']]) && $row['item_code'] != ""){
                $sub[$row['item_code']] = $row['item_code'];
            }
        }
        $sub_categories = $this->site_model->get_tbl('items');
        $db_sub = array();
        foreach ($sub_categories as $res) {
            $db_sub[$res->code] = $res->code;
        }
        foreach ($sub as $cat) {
            if(!in_array($cat, $db_sub)){
                echo "walang ".$cat."<br>";
            }
        }
        return false;


        $menus = $this->site_model->get_tbl('menus');
        $db_menus = array();
        foreach ($menus as $res) {
            $db_menus[$res->menu_code] = $res->menu_id;
        }
        

        $items = $this->site_model->get_tbl('items');
        $db_items = array();
        foreach ($items as $res) {
            $db_items[$res->code] = $res->item_id;
        }
        $query ="";

        foreach ($rows as $row) {
            $det = array(
                'menu_id'   => $db_menus[preg_replace('/\s+/', '', $row['menu_code'])],
                'item_id'   => $db_items[preg_replace('/\s+/', '', $row['item_code'])],
                'uom'       => $row['uom'],
                'qty'       => $row['qty'],
                'cost'      => $row['cost'],
            );
            $query .= $this->db->insert_string('menu_recipe',$det).";\r\n";
        }
        echo "<pre>".$query."</pre>";
    }


    public function upload_excel_form(){
        $data['code'] = menuUploadForm();
        $this->load->view('load',$data);
    }
    public function upload_excel_db(){
        $this->load->model('dine/main_model');
        $temp = $this->upload_temp('item_excel_temp');
        if($temp['error'] == ""){
            // echo "das";die();
            $now = $this->site_model->get_db_now('sql');
            $this->load->library('excel');
            $obj = PHPExcel_IOFactory::load($temp['file']);
            $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $start = 1;
            $rows = array();
            for($i=$start;$i<=$count;$i++){
                if($sheet[$i]["A"] != ""){
                    $rows[] = array(
                         "item_id"            => str_replace("!@#$%^&*()_+", "", $sheet[$i]["A"]),
                         "item_code"          => str_replace("!@#$%^&*()_+", "", $sheet[$i]["B"]),
                         "barcode"            => str_replace("!@#$%^&*()_+", "", $sheet[$i]["C"]),
                         "name"               => str_replace("!@#$%^&*()_+", "", $sheet[$i]["D"]),
                         "category"           => str_replace("!@#$%^&*()_+", "", $sheet[$i]["E"]),
                         "category_name"      => str_replace("!@#$%^&*()_+", "", $sheet[$i]["F"]),
                         "subcategory_code"   => str_replace("!@#$%^&*()_+", "", $sheet[$i]["G"]),
                         "subcategories_name" => str_replace("!@#$%^&*()_+", "", $sheet[$i]["H"]),
                         "uom"                => str_replace("!@#$%^&*()_+", "", $sheet[$i]["I"]),
                         "cat_id"             => str_replace("!@#$%^&*()_+", "", $sheet[$i]["J"]),
                         "subcat_id"          => str_replace("!@#$%^&*()_+", "", $sheet[$i]["K"]),
                         "type"               => str_replace("!@#$%^&*()_+", "", $sheet[$i]["L"]),
                         "cost"               => str_replace("!@#$%^&*()_+", "", $sheet[$i]["M"]),
                    );
                }
            }
            // echo "<pre>",print_r($rows),"</pre>";die();
            if(count($rows) > 0){
                $dflt_schedule = 1;                
                #################################################################################################################################
                ### INACTIVE ALL
                    // $this->site_model->update_tbl('categories',array(),array('inactive'=>1));
                    // $this->main_model->update_trans_tbl('categories',array(),array('inactive'=>1));
                    // $this->site_model->update_tbl('subcategories',array(),array('inactive'=>1));
                    // $this->main_model->update_trans_tbl('subcategories',array(),array('inactive'=>1));
                    // $this->site_model->update_tbl('items',array(),array('inactive'=>1)); - Approved by Justin -- Date: 2018-08-09 3:37 PM
                    // $this->main_model->update_trans_tbl('items',array(),array('inactive'=>1)); - Approved by Justin -- Date: 2018-08-09 3:37 PM
                    // $this->site_model->update_tbl('modifier_groups',array(),array('inactive'=>1));
                    // $this->main_model->update_trans_tbl('modifier_groups',array(),array('inactive'=>1));
                    // $this->site_model->update_tbl('modifiers',array(),array('inactive'=>1));
                    // $this->main_model->update_trans_tbl('modifiers',array(),array('inactive'=>1));
                #################################################################################################################################
                ### INSERT CATEGORIES
                    $ins_categories = array();
                    $result_cat = $this->site_model->get_tbl('categories');
                    foreach ($rows as $ctr => $row) {
                        if(!isset($ins_categories[$row['category']])){
                            $cat_name = $row['category'];
                            $category_name = $row['category_name'];
                            $cat_id = $row['cat_id'];
                        }
                        // echo "<pre>",print_r($result_cat),"</pre>";die();
                        if(!empty($result_cat)){
                            foreach ($result_cat as $cat_key => $cat_val) {
                                $ins_categories = array(
                                    'code' => strtoupper($cat_name),
                                    'name' => strtoupper($category_name)
                                );
                                if($row['cat_id'] == $cat_val->cat_id){
                                    $this->site_model->update_tbl('categories','cat_id',$ins_categories,$cat_val->cat_id);
                                    $this->main_model->update_trans_tbl('categories','cat_id',$ins_categories,$cat_val->cat_id);
                                    
                                }
                            }
                        }else{
                            $ins_categories = array(
                                'cat_id' => $cat_id,
                                'code' => strtoupper($cat_name),
                                'name' => strtoupper($category_name)
                            );
                            $this->site_model->add_tbl('categories',$ins_categories);
                            $this->main_model->add_trans_tbl('categories',$ins_categories);
                        }
                    }

                #################################################################################################################################
                ### INSERT SUBCATEGORIES
                    $ins_subcategories = array();
                    $result_subcat = $this->site_model->get_tbl('subcategories');
                    foreach ($rows as $ctr => $row) {
                        if(!isset($ins_subcategories[$row['subcategory_code']])){
                            $subcategory_code = $row['subcategory_code'];
                            $subcategories_name = $row['subcategories_name'];
                            $cat_id = $row['cat_id'];
                            $subcat_id = $row['subcat_id'];
                        }
                        if(!empty($result_subcat)){
                            foreach ($result_subcat as $subcat_key => $subcat_val) {
                                $ins_subcategories = array(
                                    'cat_id' => $cat_id,
                                    'code' => strtoupper($subcategory_code),
                                    'name' => strtoupper($subcategories_name)
                                );
                                if($row['subcat_id'] == $subcat_val->sub_cat_id){
                                    $this->site_model->update_tbl('subcategories','sub_cat_id',$ins_subcategories,$subcat_val->sub_cat_id);
                                    $this->main_model->update_trans_tbl('subcategories','sub_cat_id',$ins_subcategories,$subcat_val->sub_cat_id);
                                    
                                }
                            }
                        }else{
                            $ins_subcategories = array(
                                'sub_cat_id' => $subcat_id,
                                'cat_id' => $cat_id,
                                'code' => strtoupper($subcategory_code),
                                'name' => strtoupper($subcategories_name)
                            );
                            $this->site_model->add_tbl('subcategories',$ins_subcategories);
                            $this->main_model->add_trans_tbl('subcategories',$ins_subcategories);
                        }
                    }
                    // $this->site_model->add_tbl_batch('subcategories',$ins_subcategories);
                    // $this->main_model->add_trans_tbl_batch('subcategories',$ins_subcategories);
                #################################################################################################################################                ### GET ALL CATEGORIES AND SUBCATEGORIES
        //             $result = $this->site_model->get_tbl('categories',array('inactive'=>0));
        //             $categories = array();
        //             foreach ($result as $res) {
        //                 $categories[strtolower($res->code)] = $res;
        // // echo "<pre>",print_r($categories[strtolower($res->code)]),"</pre>";die();
        //             }
        //             $result = $this->site_model->get_tbl('subcategories',array('inactive'=>0));
        //             $subcategories = array();
        //             foreach ($result as $res) {
        //                 $subcategories[strtolower($res->code)] = $res;
        //             }
                    // echo $categories[strtolower($res->code)];die();
                #################################################################################################################################
                ### INSERT MENUS
                    $items = array();
                    $all_items = $this->site_model->get_tbl('items');    
                    foreach ($rows as $ctr => $row) {
                        // if(!isset($items[$row['name']])){
                        //     $items[$row['name']] = array(                                
                        //         'code' => $row['item_code'],
                        //         'name' => $row['name'],
                        //         'desc' => $row['name'],
                        //         'cat_id' => $cat_id,
                        //         'subcat_id' => $subcat_id,
                        //         'uom' => $row['uom'],
                        //         'type' => $row['type'],
                        //         'cost' => $row['cost'],
                        //          'reg_date' => $now,
                        //     ); 

                        //     $items_for_update = array(                                
                        //         'code' => $row['item_code'],
                        //         'name' => $row['name'],
                        //         'desc' => $row['name'],
                        //         'cat_id' => $cat_id,
                        //         'subcat_id' => $subcat_id,
                        //         'uom' => $row['uom'],
                        //         'type' => $row['type'],
                        //         'cost' => $row['cost'],
                        //          'reg_date' => $now,
                        //     );                            
                        // }
                        if(!empty($all_items)){
                            foreach ($all_items as $ikey => $ival) {
                                $items = array(
                                    'code' => $row['item_code'],
                                    'name' => $row['name'],
                                    'desc' => $row['name'],
                                    'cat_id' => $cat_id,
                                    'subcat_id' => $subcat_id,
                                    'uom' => $row['uom'],
                                    'type' => $row['type'],
                                    'cost' => $row['cost'],
                                     'reg_date' => $now,
                                );
                                if($row['item_id'] == $ival->item_id){
                                    $this->site_model->update_tbl('items','item_id',$items,$ival->item_id);
                                    $this->main_model->update_trans_tbl('items','item_id',$items,$ival->item_id);
                                    
                                }
                            }
                        }else{
                            $items = array(
                                'code' => $row['item_code'],
                                'name' => $row['name'],
                                'desc' => $row['name'],
                                'cat_id' => $cat_id,
                                'subcat_id' => $subcat_id,
                                'uom' => $row['uom'],
                                'type' => $row['type'],
                                'cost' => $row['cost'],
                                 'reg_date' => $now,
                            );
                            $this->site_model->add_tbl('items',$items);
                            $this->main_model->add_trans_tbl('items',$items);
                        }
                        // $item_count = count($this->items_model->get_item($row['item_id']));
                        // if($item_count > 0)
                        // {                                
                        //     $this->items_model->update_item($items_for_update, $row['item_id']);
                        // }else{
                        //     $this->site_model->add_tbl('items',$items);           
                        // }
                    }
                    // $this->site_model->add_tbl_batch('items',$items); -- Removed due to item_id is not maintained if all items will be uploaded. It needs to check if it is already exists.
                    // $this->main_model->add_trans_tbl_batch('items',$items); - Approved by Justin -- Date: 2018-08-09 3:37 PM
                #################################################################################################################################
            }
            unlink($temp['file']);
        }
        else{
            site_alert($temp['error'],"error");
        }
        // echo "dasdas";die();
        // return false;
        redirect(base_url()."items", 'refresh'); 
    }
    public function upload_temp($temp_file_name,$upload_file='item_excel'){
        $error = "";
        $file  = "";
        $path  = './uploads/temp/';
        $config['upload_path']          = $path;
        // $config['allowed_types']        = 'xls|xlsx';
        $config['allowed_types']        = '*';
        $config['file_name']            = $temp_file_name;
        $config['overwrite']            = true;
        $this->load->library('upload', $config);
        // $allowed_files = array('.xls','.xlsx','.csv', '.p1');
        $allowed_files = array('.p1');
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

    //excel
    public function print_item_movement_excel()
    {
        // $this->load->database('main', TRUE);
        
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Item Movement Report';
        $rc=1;
        #GET VALUES
        // start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
        $this->load->model('dine/setup_model');
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        // update_load(10);
        // sleep(1);
        
        $item_id = $_GET['item_id'];        
        // $daterange = $_GET['daterange'];      
        // $dates = explode(" to ",$daterange);
        // // $from = date2SqlDateTime($dates[0]);
        // // $to = date2SqlDateTime($dates[1]);
        // $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        $table  = "item_moves";
        $select = "item_moves.*,items.name,items.code,trans_types.name as particular,items.no_per_pack as it_per_pack,items.no_per_pack_uom as it_per_pack_uom";
        $join["items"] = array('content'=>"item_moves.item_id = items.item_id");
        $join["trans_types"] = array('content'=>"item_moves.type_id = trans_types.type_id");
        $args = array();
        $args2 = array();
        $args3 = array();
        // if(isset($item_id) && $item_id != ""){
        //     $args['item_moves.item_id'] = $item_id;
        //     $args2['item_moves.item_id'] = $item_id;
        //     $args3['item_moves.item_id'] = $item_id;
        // }
        if(isset($_GET['daterange']) && $_GET['daterange'] != ""){
            $daterange = $_GET['daterange'];
            $dates = explode(" to ",$daterange);
            $from = date2SqlDateTime($dates[0]);
            $to = date2SqlDateTime($dates[1]);
            $args["item_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args2["item_moves.reg_date  < '".$from."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args3["item_moves.reg_date  > '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        }
        $args['item_moves.inactive'] = 0;
        $args2['item_moves.inactive'] = 0;
        $args3['item_moves.inactive'] = 0;
        $order = array('item_moves.reg_date'=>'asc','items.name'=>'asc');
        $group = null;
        // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        // $page = paginate('items/get_items',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
        // echo $this->site_model->db->last_query(); die();

        $item_moves_sum = $this->items_model->get_item_moves_total(null,$args2);
        $item_moves_after = $this->items_model->get_item_moves_total(null,$args3);
        
        $before_m_item = 0;
        if($item_moves_sum){
            $before_m_item = $item_moves_sum[0]->in_item_qty;
        }

        $after_m_item = 0;
        if($item_moves_after){
            $after_m_item = $item_moves_after[0]->in_item_qty;
        }

        // echo "<pre>",print_r($items),"</pre>";die();


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
                'size' => 12,
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
        $styleCenter = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
        );
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldLeft = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldRight = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldCenter = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        
        $headers = array('Description','Reference #', 'Trans Date','Item Name','UOM','Qty IN','Qty OUT', 'QOH','UOM','Qty IN','Qty OUT', 'QOH');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':L'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':L'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Item Movement Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('H'.$rc.':L'.$rc);
        $sheet->getCell('H'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('H'.$rc.':L'.$rc);
        $sheet->getCell('H'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);
        $rc++;


        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++;               
                
        if(count($items) > 0){
            $ctr = 1;
            $last = count($items);
            $colspan = 5;
            $curr = $before_m_item;
            foreach ($items as $res) {
                if($ctr == 1){

                    $sheet->mergeCells('A'.$rc.':G'.$rc);
                    $sheet->getCell('A'.$rc)->setValue("Quantity on Hand Before ".sql2Date($from)." ".toTimeW($from));
                    $sheet->getCell('H'.$rc)->setValue($before_m_item);
                    $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);
                    $c = "";
                    if($res->it_per_pack > 0){
                        $c = $before_m_item/$res->it_per_pack;
                    }
                    $sheet->getCell('L'.$rc)->setValue($c);
                    $sheet->getStyle('L'.$rc)->applyFromArray($styleNum);
                    $rc++;
                }

                $sheet->getCell('A'.$rc)->setValue(ucwords(strtolower($res->particular)));
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->setCellValueExplicit('B'.$rc, $res->trans_ref,PHPExcel_Cell_DataType::TYPE_STRING);
                // $sheet->getCell('B'.$rc)->setValue($res->trans_ref);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('C'.$rc)->setValue(sql2Date($res->reg_date)." ".toTimeW($res->reg_date));
                $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('D'.$rc)->setValue(ucwords(strtolower($res->name)));
                $sheet->getStyle('D'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('E'.$rc)->setValue($res->uom);
                $sheet->getStyle('E'.$rc)->applyFromArray($styleTxt);

                $in = "";
                $out = "";
                if($res->qty >= 0){
                    $in = num($res->qty);
                    $curr += $res->qty;
                    $out = "";
                }
                else{
                    $out = num($res->qty);
                    $curr += $res->qty;
                    $out = $out * -1;
                }

                $sheet->getCell('F'.$rc)->setValue($in);
                $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('G'.$rc)->setValue($out);
                $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('H'.$rc)->setValue($curr);
                $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('I'.$rc)->setValue($res->it_per_pack_uom);
                $sheet->getStyle('I'.$rc)->applyFromArray($styleTxt);

                // $this->make->td($res->it_per_pack_uom,array('class'=>'text-center'));
                $in = "";
                $out = "";
                $curr2 = "";
                if($res->it_per_pack > 0){
                    $curr2 = $curr/$res->it_per_pack;
                    if($res->qty >= 0){
                        $val = $res->qty/$res->it_per_pack;
                        $in = num($val);
                    }
                    else{
                        $val = $res->qty/$res->it_per_pack;
                        $out = num($val);
                    }
                }

                $sheet->getCell('J'.$rc)->setValue($in);
                $sheet->getStyle('J'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('K'.$rc)->setValue($out);
                $sheet->getStyle('K'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('L'.$rc)->setValue($curr2);
                $sheet->getStyle('L'.$rc)->applyFromArray($styleNum);
                // $this->make->td($in,array('class'=>'text-right'));
                // $this->make->td($out,array('class'=>'text-right'));
                // $this->make->td($curr2,array('class'=>'text-right'));

                $rc++;
                $ctr++;
            }
        }

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue("Quantity on Hand After ".sql2Date($to)." ".toTimeW($to));
        $sheet->getCell('H'.$rc)->setValue($curr + $after_m_item);
        $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);
        $c = "";
        if($res->it_per_pack > 0){
            $c = ($curr + $after_m_item)/$res->it_per_pack;
        } 
        $sheet->getCell('L'.$rc)->setValue($c);
        $sheet->getStyle('L'.$rc)->applyFromArray($styleNum);
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
        // $trans_count = count($trans) + 10;
        // foreach ($trans as $val) {
        //     $tot_gross += $val->gross;
        //     $tot_cost += $val->cost;
        //     $tot_margin += $val->gross - $val->cost;
        // }
        // foreach ($trans_mod as $vv) {
        //     $tot_mod_gross += $vv->mod_gross;
        // }

        // foreach ($trans as $k => $v) {
        //     // $pdf->Cell(55, 0, $v->menu_name, '', 0, 'L');        
        //     // $pdf->Cell(38, 0, $v->menu_cat_name, '', 0, 'L');        
        //     // $pdf->Cell(25, 0, num($v->qty), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->vat_sales), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->vat), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->gross), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num(0), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->cost), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num(0), '', 0, 'R');                    
        //     // $pdf->ln(); 

        //     $sheet->getCell('A'.$rc)->setValue($v->menu_name);
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue($v->menu_cat_name);
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('C'.$rc)->setValue(num($v->qty));
        //     $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        //     // $sheet->getCell('D'.$rc)->setValue(num($v->vat_sales));     
        //     // $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //     // $sheet->getCell('E'.$rc)->setValue(num($v->vat));     
        //     // $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('D'.$rc)->setValue(num($v->gross));     
        //     $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //     if($tot_gross != 0){
        //         $sheet->getCell('E'.$rc)->setValue(num($v->gross / $tot_gross * 100)."%");                     
        //     }else{
        //         $sheet->getCell('E'.$rc)->setValue("0.00%");                                     
        //     }
        //     $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('F'.$rc)->setValue(num($v->cost));     
        //     $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //     if($tot_cost != 0){
        //         $sheet->getCell('G'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
        //     }else{
        //         $sheet->getCell('G'.$rc)->setValue("0.00%");                     
        //     }
        //     $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('H'.$rc)->setValue(num($v->gross - $v->cost));     
        //     $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);               

        //     // Grand Total
        //     $tot_qty += $v->qty;
        //     // $tot_vat_sales += $v->vat_sales;
        //     // $tot_vat += $v->vat;
        //     // $tot_gross += $v->gross;
        //     $tot_sales_prcnt = 0;
        //     // $tot_cost += $v->cost;
        //     $tot_cost_prcnt = 0;

        //     $counter++;
        //     $progress = ($counter / $trans_count) * 100;
        //     update_load(num($progress)); 
        //     $rc++;             
        // }
        // $sheet->getCell('A'.$rc)->setValue('Grand Total');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue("");
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('C'.$rc)->setValue(num($tot_qty));
        // $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
        // // $sheet->getCell('D'.$rc)->setValue(num($tot_vat_sales));     
        // // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        // // $sheet->getCell('E'.$rc)->setValue(num($tot_vat));     
        // // $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('D'.$rc)->setValue(num($tot_gross));     
        // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('E'.$rc)->setValue();     
        // $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('F'.$rc)->setValue(num($tot_cost));     
        // $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('G'.$rc)->setValue();     
        // $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('H'.$rc)->setValue(num($tot_margin));     
        // $sheet->getStyle('H'.$rc)->applyFromArray($styleBoldRight);
        // $rc++; 
        

        // ///////////fpr payments
        // $this->cashier_model->db = $this->load->database('main', TRUE);
        // $args = array();
        // // if($user)
        // //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        // $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        // // if($menu_cat_id != 0){
        // //     $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        // // }


        // $post = $this->set_post();
        // // $curr = $this->search_current();
        // $curr = false;
        // $trans = $this->trans_sales($args,$curr);
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

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('GROSS');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($tot_gross + $tot_mod_gross));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('VAT SALES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($taxable));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('VAT');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($vat_));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('VAT EXEMPT SALES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($nontaxable-$zero_rated));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('ZERO RATED');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($zero_rated));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // // //MENU SUB CAT
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('SUB CATEGORIES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $subcats = $trans_menus['sub_cats'];
        // $total = 0;
        // foreach ($subcats as $id => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        //     $total += $val['amount'];
        // }
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('SubTotal'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($total));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // $rc++;
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('MODIFIERS TOTAL'));
        // $sheet->getCell('B'.$rc)->setValue(num($trans_menus['mods_total']));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('TOTAL'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($total + $trans_menus['mods_total']));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // //DISCOUNTS
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('DISCOUNT');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $types = $trans_discounts['types'];
        // foreach ($types as $code => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        // }

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($discounts));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('VAT EXEMPT'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($less_vat));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // // //CAHRGES
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('CHARGES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $types = $trans_charges['types'];
        // foreach ($types as $code => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        // }
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($charges));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // // //PAYMENTS
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('PAYMENT MODE');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('PAYMENT AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $payments_types = $payments['types'];
        // $payments_total = $payments['total'];
        // foreach ($payments_types as $code => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($code));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // }

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($payments_total));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // if($trans['total_chit']){
        //     $rc++; $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper('Total Chit'));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        //     $sheet->getCell('B'.$rc)->setValue(num($trans['total_chit']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
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
        // $rc++;
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Trans Count'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $tc_total  = 0;
        // $tc_qty = 0;
        // foreach ($types_total as $typ => $tamnt) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($typ));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(count($types[$typ]));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('C'.$rc)->setValue(num($tamnt));
        //     $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        //     $tc_total += $tamnt;
        //     $tc_qty += count($types[$typ]);
        // }
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('TC TOTAL'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('C'.$rc)->setValue(num($tc_total));
        // $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);



        update_load(100);        
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
        

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    //pdf
    public function print_item_movement_pdf()
    {
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Sales Report');
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
        
        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $item_id = $_GET['item_id'];        
        // $daterange = $_GET['daterange'];      
        // $dates = explode(" to ",$daterange);
        // // $from = date2SqlDateTime($dates[0]);
        // // $to = date2SqlDateTime($dates[1]);
        // $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        $table  = "item_moves";
        $select = "item_moves.*,items.name,items.code,trans_types.name as particular,items.no_per_pack as it_per_pack,items.no_per_pack_uom as it_per_pack_uom";
        $join["items"] = array('content'=>"item_moves.item_id = items.item_id");
        $join["trans_types"] = array('content'=>"item_moves.type_id = trans_types.type_id");
        $args = array();
        $args2 = array();
        $args3 = array();
        // if(isset($item_id) && $item_id != ""){
        //     $args['item_moves.item_id'] = $item_id;
        //     $args2['item_moves.item_id'] = $item_id;
        //     $args3['item_moves.item_id'] = $item_id;
        // }
        if(isset($_GET['daterange']) && $_GET['daterange'] != ""){
            $daterange = $_GET['daterange'];
            $dates = explode(" to ",$daterange);
            $from = date2SqlDateTime($dates[0]);
            $to = date2SqlDateTime($dates[1]);
            $args["item_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args2["item_moves.reg_date  < '".$from."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args3["item_moves.reg_date  > '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        }
        $args['item_moves.inactive'] = 0;
        $args2['item_moves.inactive'] = 0;
        $args3['item_moves.inactive'] = 0;
        $order = array('item_moves.reg_date'=>'asc','items.name'=>'asc');
        $group = null;
        // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        // $page = paginate('items/get_items',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
        // echo $this->site_model->db->last_query(); die();

        $item_moves_sum = $this->items_model->get_item_moves_total(null,$args2);
        $item_moves_after = $this->items_model->get_item_moves_total(null,$args3);
        
        $before_m_item = $curr = 0;
        if($item_moves_sum){
            $before_m_item = $item_moves_sum[0]->in_item_qty;
        }

        $after_m_item = 0;
        if($item_moves_after){
            $after_m_item = $item_moves_after[0]->in_item_qty;
        }

        // echo "<pre>",print_r($items),"</pre>";die();
        $pdf->Write(0, 'Item Movement Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();
        $pdf->SetFont('helvetica', 'B', 9);
        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(25, 0, 'Description', 'B', 0, 'L');        
        $pdf->Cell(25, 0, 'Reference #', 'B', 0, 'L');        
        $pdf->Cell(35, 0, 'Trans Date', 'B', 0, 'L');        
        // $pdf->Cell(25, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(25, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(92, 0, 'Item Name', 'B', 0, 'L');        
        $pdf->Cell(15, 0, 'UOM', 'B', 0, 'C');        
        $pdf->Cell(25, 0, 'Qty In', 'B', 0, 'R');        
        $pdf->Cell(25, 0, 'Qty Out', 'B', 0, 'R');        
        $pdf->Cell(25, 0, 'QOH', 'B', 0, 'R');        
        $pdf->ln();   
        
        $pdf->SetFont('helvetica', '', 9);     
                
        if(count($items) > 0){
            $ctr = 1;
            $last = count($items);
            $colspan = 5;
            $curr = $before_m_item;
            foreach ($items as $res) {
                if($ctr == 1){

                    $pdf->Cell(242, 0, "Quantity on Hand Before ".sql2Date($from)." ".toTimeW($from), 'B', 0, 'L');       
                    $pdf->Cell(25, 0, $before_m_item, 'B', 0, 'R'); 
                    $pdf->ln();
                }

                $pdf->Cell(25, 0, ucwords(strtolower($res->particular)), '', 0, 'L');        
                $pdf->Cell(25, 0, $res->trans_ref, '', 0, 'L');        
                $pdf->Cell(35, 0, sql2Date($res->reg_date)." ".toTimeW($res->reg_date), '', 0, 'L');        
                // $pdf->Cell(25, 0, 'VAT Sales', 'B', 0, 'C');        
                // $pdf->Cell(25, 0, 'VAT', 'B', 0, 'C');

                $pdf->SetFont('helvetica', '', 9);

                if (strlen($res->name)) {
                    $width = 92;
                    $font_size = 9;
                    do {
                        $font_size -= 0.5;
                        $string_font_width = $pdf->GetStringWidth(ucwords(strtolower($res->name)),'helvetica','',$font_size) + 3; # +4 Not exactly sure but works
                    } while ($string_font_width > $width);

                    // $pdf->SetFont('dejavb','',$font_size);
                    $pdf->SetFont('helvetica', '', $font_size);
                   
                }
                $pdf->Cell(92, 0, ucwords(strtolower($res->name)), '', 0, 'L'); 
                $pdf->SetFont('helvetica', '', 9);       
                $pdf->Cell(15, 0, $res->uom, '', 0, 'C');   

                $in = "";
                $out = "";
                if($res->qty >= 0){
                    $in = num($res->qty);
                    $curr += $res->qty;
                    $out = "";
                }
                else{
                    $out = num($res->qty);
                    $curr += $res->qty;
                    $out = $out * -1;
                }

                $pdf->Cell(25, 0, $in, '', 0, 'R');        
                $pdf->Cell(25, 0, $out, '', 0, 'R');        
                $pdf->Cell(25, 0, $curr, '', 0, 'R');        
                $pdf->ln();   

                $ctr++;
            }
        }

        $pdf->Cell(242, 0, "Quantity on Hand Before ".sql2Date($to)." ".toTimeW($to), 'T', 0, 'L');       
        $pdf->Cell(25, 0, $curr + $after_m_item, 'T', 0, 'R'); 
        $pdf->ln();
        //Close and output PDF document
        $pdf->Output('sales_report.pdf', 'I');
        

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    //menu history //JED 9/15/2018
    public function menu_history(){
        $data = $this->syter->spawn('inq');
        $data['page_title'] = fa('fa-random').' Menu Transaction History';
        $data['code'] = menuHistoryPage();           
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js','js/jspdf.js');
        $data['load_js'] = "dine/items.php";
        $data['use_js'] = "menuHistoryJS";
        $this->load->view('page',$data);
    }
    public function get_menu_history($id=null,$asJson=true){
        sess_clear('menu_history');
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        // $total_rows = 100;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }

        $daterange = $post['calendar_range'];
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);

        $from_date = date2Sql($dates[0]); 
        $to_date = date2Sql($dates[1]);

        $strtt =  strtotime($from_date);
        $moves_array = array();
        $curr_date = date('Y-m-d');
        do{
            $this->site_model->db = $this->load->database('main', TRUE);

            $datefrom = date('Y-m-d', $strtt);
            $join = array();
            $table  = "menu_moves";
            $select = "menu_moves.*,b.menu_name,b.menu_code,c.name as particular, d.fname, d.lname";
            $join["trans_receiving_menu a"] = array('content'=>"a.receiving_id = menu_moves.trans_id");
            $join["menus b"] = array('content'=>"menu_moves.item_id = b.menu_id");
            $join["trans_types c"] = array('content'=>"menu_moves.type_id = c.type_id");
            $join["users d"] = array('content'=>"a.user_id = d.id");
            $args = array();
            // $args2 = array();
            if(isset($post['menu-search']) && $post['menu-search'] != ""){
                $args['menu_moves.item_id'] = $post['menu-search'];
            }
            // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
                // $daterange = $post['calendar_range'];
                // $dates = explode(" to ",$daterange);
                // $from = date2SqlDateTime($dates[0]);
                // $to = date2SqlDateTime($dates[1]);
                // $args["menu_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args["DATE(menu_moves.reg_date)  = '".$datefrom."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // }
            $args['menu_moves.inactive'] = 0;
            $order = array('menu_moves.reg_date'=>'desc','b.menu_name'=>'asc');
            $group = null;
            // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
            // $page = paginate('items/get_items',$count,$total_rows,$pagi);
            $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
            // echo $this->site_model->db->last_query();
            // return false;
            // $args['items.barcode'] = array('use'=>'where','val'=>$barcode);
            // echo $this->db->last_query();
            // echo "<pre>",print_r($items),"</pre>";die();
            if($items){
                foreach($items as $res){
                    $ids = strtotime($res->reg_date);
                    if(isset($moves_array[$ids][$res->trans_ref])){
                        $moves_array[$ids][$res->trans_ref]['qty'] += $res->qty;
                    }else{
                        $moves_array[$ids][$res->trans_ref] = array(
                            'particular'=>$res->particular,
                            'trans_type'=>$res->type_id,
                            'trans_ref'=>$res->trans_ref,
                            'reg_date'=>$res->reg_date,
                            'code'=>$res->menu_code,
                            'name'=>$res->menu_name,
                            'qty'=>$res->qty,
                            'memo'=>'',
                            'action'=>'Menu Receiving from reference '.$res->trans_ref,
                            'user'=>$res->fname." ".$res->lname
                        );
                    }
                }
            }


            // for sales and voids
            $joinm = array();
            $tablem = "trans_sales_menus";
            $selectm = "trans_sales_menus.*,menus.menu_name,menus.menu_code,trans_types.name as particular,trans_sales.datetime,trans_sales.update_date, trans_sales.trans_ref, trans_sales.type_id, trans_sales.void_ref, users.fname, users.lname, trans_sales.inactive, trans_sales.void_user_id";
            // $selectm_s = "sum(qty) as menu_sales_qty";
            $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menus.sales_id");
            $joinm["menus"] = array('content'=>"menus.menu_id = trans_sales_menus.menu_id");
            $joinm["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
            $joinm["users"] = array('content'=>"trans_sales.user_id = users.id");
            $args3 = array();
            $args4 = array();

            $args4["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $args4["DATE(trans_sales.datetime) = '".$datefrom."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args4["trans_sales.type_id"] = 10;
            // $args4["trans_sales.inactive"] = '0';


            if(isset($post['menu-search']) && $post['menu-search'] != ""){
                // $args2['menu_moves.item_id'] = $post['menu-search'];
                // $args3['trans_sales_menus.menu_id'] = $post['menu-search'];
                $args4['trans_sales_menus.menu_id'] = $post['menu-search'];
            }
            

            $ordermm = null;
            $groupmm = null;
            // if($curr_date == $datefrom){
            //     $this->site_model->db = $this->load->database('default', TRUE);
            //     $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm); 
            // }else{
                $this->site_model->db = $this->load->database('main', TRUE);
                $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm); 
            // }

            // echo $this->db->last_query().'<br><br>';
            if($get_menus_sales){
                foreach($get_menus_sales as $res){
                    $action = "";
                    if($res->type_id == 11){
                        $where = array('sales_id'=>$res->void_ref);
                        $vd = $this->site_model->get_details($where,'trans_sales');
                        $action = "Void Reference ".$res->trans_ref." from Sales Reference ".$vd[0]->trans_ref;
                        $tdate = $res->update_date;
                    }else{
                        $action = "Sales from reference ".$res->trans_ref;
                        if($res->inactive == 1 && $res->void_user_id){
                            $tdate = $res->datetime;
                        }else{
                            $tdate = $res->update_date;
                        }
                    }

                    $ids = strtotime($tdate);
                    if(isset($moves_array[$ids][$res->trans_ref])){
                        $moves_array[$ids][$res->trans_ref]['qty'] += $res->qty;
                    }else{
                        $moves_array[$ids][$res->trans_ref] = array(
                            'particular'=>$res->particular,
                            'trans_type'=>$res->type_id,
                            'trans_ref'=>$res->trans_ref,
                            'reg_date'=>$tdate,
                            'code'=>$res->menu_code,
                            'name'=>$res->menu_name,
                            'qty'=>$res->qty,
                            'memo'=>$res->void_ref,
                            'action'=>$action,
                            'user'=>$res->fname." ".$res->lname
                        );
                    }
                }
            }

            // for cancelled orders
            $joinm = array();
            $tablem = "trans_sales_menus";
            $selectm = "trans_sales_menus.*,menus.menu_name,menus.menu_code,trans_types.name as particular,trans_sales.datetime,trans_sales.update_date, trans_sales.trans_ref, trans_sales.type_id, trans_sales.void_ref, users.fname, users.lname, trans_sales.inactive, trans_sales.void_user_id";
            // $selectm_s = "sum(qty) as menu_sales_qty";
            $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menus.sales_id");
            $joinm["menus"] = array('content'=>"menus.menu_id = trans_sales_menus.menu_id");
            $joinm["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
            $joinm["users"] = array('content'=>"trans_sales.user_id = users.id");
            $args3 = array();
            $args4 = array();

            $args4["trans_sales.trans_ref"] = null;
            $args4["trans_sales.inactive"] = 1;
            $args4["trans_sales.void_user_id IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $args4["DATE(trans_sales.datetime) = '".$datefrom."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args4["trans_sales.type_id"] = 10;

            if(isset($post['menu-search']) && $post['menu-search'] != ""){
                $args4['trans_sales_menus.menu_id'] = $post['menu-search'];
            }
            

            $ordermm = null;
            $groupmm = null;
            // if($curr_date == $datefrom){
            //     $this->site_model->db = $this->load->database('default', TRUE);
            //     $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm); 
            // }else{
                $this->site_model->db = $this->load->database('main', TRUE);
                $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm); 
            // }

            // echo $this->db->last_query().'<br><br>';
            if($get_menus_sales){
                foreach($get_menus_sales as $res){
                    $action = "Cancelled Order #".$res->sales_id;
                    $tdate = $res->update_date;
                        

                    $ids = strtotime($tdate);
                    if(isset($moves_array[$ids][$res->trans_ref])){
                        $moves_array[$ids][$res->trans_ref]['qty'] += $res->qty;
                    }else{
                        $moves_array[$ids][$res->trans_ref] = array(
                            'particular'=>'Cancelled',
                            'trans_type'=>$res->type_id,
                            'trans_ref'=>$res->trans_ref,
                            'reg_date'=>$tdate,
                            'code'=>$res->menu_code,
                            'name'=>$res->menu_name,
                            'qty'=>$res->qty,
                            'memo'=>$res->void_ref,
                            'action'=>$action,
                            'user'=>$res->fname." ".$res->lname
                        );
                    }
                }
            }


            //for cancelled menus
            $joinc = array();
            $tablem = "reasons";
            $selectm = "reasons.*,menus.menu_name,menus.menu_code,trans_types.name as particular, trans_sales.trans_ref , trans_sales.type_id, trans_sales.void_ref, users.fname, users.lname";
            // $selectm_s = "sum(qty) as menu_sales_qty";
            $joinc["trans_sales"] = array('content'=>"trans_sales.sales_id = reasons.trans_id");
            $joinc["menus"] = array('content'=>"menus.menu_id = reasons.ref_id");
            $joinc["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
            $joinc["users"] = array('content'=>"trans_sales.user_id = users.id");
            $args3 = array();
            $args4 = array();
            // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
            // $args3["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args3["trans_sales.type_id"] = 10;
            // $args3["trans_sales.inactive"] = '0';

            // $args4["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args4["trans_sales.type_id"] = 10;
            // $args4["trans_sales.inactive"] = '0';


            if(isset($post['menu-search']) && $post['menu-search'] != ""){
                // $args2['menu_moves.item_id'] = $post['menu-search'];
                // $args3['trans_sales_menus.menu_id'] = $post['menu-search'];
                $args4['reasons.ref_id'] = $post['menu-search'];
            }
           
            $args4["DATE(trans_sales.datetime) = '".$datefrom."'"] = array('use'=>'where','val'=>null,'third'=>false);

            $ordermm = null;
            $groupmm = null;
            // if($curr_date == $datefrom){
            //     $this->site_model->db = $this->load->database('default', TRUE);
            //     $get_menus_reasons = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinc,true,$selectm,$groupmm); 
            // }else{
                $this->site_model->db = $this->load->database('main', TRUE);
                $get_menus_reasons = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinc,true,$selectm,$groupmm); 
            // }

            $total_cancel = 0;
            if($get_menus_reasons){
                foreach($get_menus_reasons as $res){

                    $exp = explode(' - ', $res->ref_name);
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

                    $total_cancel += $price;

                    $action = "Removed from Order # ".$res->trans_id;

                    $ids = strtotime($res->datetime);
                    if(isset($moves_array[$ids][$res->trans_ref])){
                        $moves_array[$ids][$res->trans_ref]['qty'] += $qty;
                    }else{
                        $moves_array[$ids][$res->trans_ref] = array(
                            'particular'=>'Remove Menu',
                            'trans_type'=>$res->type_id,
                            'trans_ref'=>$res->trans_ref,
                            'reg_date'=>$res->datetime,
                            'code'=>$res->menu_code,
                            'name'=>$res->menu_name,
                            'qty'=>$qty,
                            'memo'=>$res->reason,
                            'action'=>$action,
                            'user'=>$res->fname." ".$res->lname
                        );
                    }
                }
            }


            //for transfer and split and combine
            // $this->site_model->db = $this->load->database('default', TRUE);
            $tablet = "transfer_split";
            $selectt = "transfer_split.*";
            $args4 = array();


            if(isset($post['menu-search']) && $post['menu-search'] != ""){
                $args4["transfer_split.menus like '%|".$post['menu-search']."-%'"] = array('use'=>'where','val'=>null,'third'=>false);
            }
            
            $args4["DATE(transfer_split.datetime) = '".$datefrom."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args4["type"] = "Transfer";

            $ordermm = null;
            $groupmm = null;
            $get_transfer = $this->site_model->get_tbl($tablet,$args4,$ordermm,null,true,$selectt,$groupmm); 
            // echo $this->site_model->db->last_query();
            // echo "<pre>",print_r($get_transfer),"</pre>"; die();
            // $total_cancel = 0;
            if($get_transfer){
                foreach($get_transfer as $res){

                    // if($curr_date == $datefrom){
                    //     $this->site_model->db = $this->load->database('default', TRUE);
                    // }else{
                    //     $this->site_model->db = $this->load->database('main', TRUE);
                    // }


                    $action = $res->details;
                    $args= array();
                    $args['trans_sales.sales_id'] = $res->sales_id;
                    // die('ssss');
                    $join=array();
                    $join['users'] = array('content'=>"trans_sales.user_id = users.id");
                    // die('abot pa');
                    $result = $this->site_model->get_tbl('trans_sales',$args,array(),$join,true,'trans_sales.*, users.fname, users.lname');
                    // echo $this->site_model->db->last_query(); die();
                    // var_dump($result); die();
                    $rmenus = $res->menus;
                    $exp = explode('|', $rmenus);
                    $qty = 0;
                    foreach($exp as $key){
                        $exp2 = explode('-', $key);
                        if($exp2[0] == $post['menu-search']){
                            $qty += $exp2[1];

                            $where = array('menu_id'=>$post['menu-search']);
                            $vd = $this->site_model->get_details($where,'menus');
                            $menu_code = $vd[0]->menu_code;
                            $menu_name = $vd[0]->menu_name;
                        }
                    }

                    if($result){
                        $ref = $res->sales_id;
                        if($result[0]->trans_ref){
                            $ref = $result[0]->trans_ref;
                        }else{
                            $ref = $res->sales_id;
                        }

                        $ids = strtotime($res->datetime);
                        if(isset($moves_array[$ids][$ref])){
                            $moves_array[$ids][$ref]['qty'] += $qty;
                        }else{
                            $moves_array[$ids][$ref] = array(
                                'particular'=>$res->type,
                                'trans_type'=>$result[0]->type_id,
                                'trans_ref'=>'',
                                'reg_date'=>$res->datetime,
                                'code'=>$menu_code,
                                'name'=>$menu_name,
                                'qty'=>$qty,
                                'memo'=>'',
                                'action'=>$action,
                                'user'=>$result[0]->fname." ".$result[0]->lname
                            );
                        }
                    }
                    // echo 'asdfasdf1111'; die();
                }
            }


            $strtt = strtotime($datefrom . ' + 1 day');

        }while($datefrom != $to_date);


            
        // echo "<pre>",print_r($moves_array),"</pre>";die();


        // echo $this->db->last_query();
        // echo "<pre>",print_r($moves_array),"</pre>";die();

        $json = array();
        $html = "";
        // $colspan = 5;
        // $this->make->sRow(array('class'=>'tdhd'));
        //     $this->make->sTd(array('colspan'=>$colspan));
        //         $this->make->span("Quantity on Hand Before ".sql2Date($fromD));
        //     $this->make->eTd();
        //     $this->make->td("");
        //     $this->make->td(num($before_qty),array('class'=>'text-right','style'=>'border-right:5px solid #fff !important;'));
        //     $c = "";
        //     // if($res->it_per_pack > 0){
        //     //     $c = $res->curr_item_qty/$res->it_per_pack;
        //     // }    
        //     // $this->make->td(num($c)." ".$res->it_per_pack_uom,array('class'=>'text-right'));
        // $this->make->eRow();
        if(count($moves_array) > 0){
            ksort($moves_array);
            $this->session->set_userdata('menu_history',$moves_array);
            $ctr = 1;
            $last = count($moves_array);
            // $curr = $before_qty;
            foreach ($moves_array as $dt =>$value) {

                foreach ($value as $key => $res) {
                

                    $this->make->sRow();
                        $this->make->td(ucwords(strtolower($res['particular'])));
                        $this->make->td($res['trans_ref']);
                        $this->make->td(sql2Date($res['reg_date'])." ".toTimeW($res['reg_date']));
                        $this->make->td(ucwords(strtolower($res['user'])));
                        $this->make->td($res['action']);
                        // $this->make->td($res->uom,array('class'=>'text-center'));
                        // $in = "0";
                        // $out = "0";
                        // if($res['qty'] >= 0){
                        //     $in = $res['qty'];
                        //     $curr += $res['qty'];
                        // }
                        // else{
                        //     $out = $res['qty'];
                        //     $curr += $res['qty'];
                        // }

                        $this->make->td($res['qty'],array('class'=>'text-right'));
                        // $this->make->td($out * -1,array('class'=>'text-right'));
                        // $this->make->td(num($res->curr_item_qty),array('class'=>'text-right','style'=>'border-right:1px solid #fff;'));
                        // $this->make->td($curr,array('class'=>'text-right','style'=>'border-right:1px solid #fff;'));

                        // $this->make->td($res->it_per_pack_uom,array('class'=>'text-center'));
                        // $in = "";
                        // $out = "";
                        // $curr2 = "";
                        // if($res->it_per_pack > 0){
                        //     $curr2 = $res->curr_item_qty/$res->it_per_pack;
                        //     if($res->qty >= 0){
                        //         $val = $res->qty/$res->it_per_pack;
                        //         $in = num($val);
                        //     }
                        //     else{
                        //         $val = $res->qty/$res->it_per_pack;
                        //         $out = num($val);
                        //     }
                        // }
                        // $this->make->td($in,array('class'=>'text-right'));
                        // $this->make->td($out,array('class'=>'text-right'));
                        // $this->make->td(num($curr2),array('class'=>'text-right'));
                    
                    $this->make->eRow();

                }

            }
        }
        $json['html'] = $this->make->code();
        
        echo json_encode($json);
    }

    public function menu_history_excel(){
        $this->load->library('Excel');
        $moves_array = $this->session->userData('menu_history');
        ksort($moves_array);
        // $total_rows = 100;
        $sheet = $this->excel->getActiveSheet();

        start_load(0);

        $this->load->model('dine/setup_model');
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

        update_load(10);
        sleep(1);
        
        // echo $this->db->last_query();
        // echo "<pre>",print_r($moves_array),"</pre>";die();
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->getCell('A1')->setValue($branch['name']);
        $sheet->getCell('A2')->setValue('Menu History Report');

        $sheet->getStyle('A4:'.'F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:'.'F4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A4:'.'F4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A4:'.'F4')->getFill()->getStartColor()->setRGB('29bb04');
        $sheet->getStyle('A1:'.'F4')->getFont()->setBold(true);
        $sheet->getCell('A4')->setValue('Type');
        $sheet->getCell('B4')->setValue('Reference #');
        $sheet->getCell('C4')->setValue('Trans Date');
        $sheet->getCell('D4')->setValue('User');
        $sheet->getCell('E4')->setValue('Action');
        $sheet->getCell('F4')->setValue('Qunatity');
        $rn = 5;

        update_load(15);
        if(count($moves_array) > 0){

            foreach ($moves_array as $dt =>$value) {
                foreach ($value as $key => $res) {
                    $sheet->getCell('A'.$rn)->setValue(ucwords(strtolower($res['particular'])));
                    // $sheet->getCell('B'.$rn)->setValue($vals['cr_beg']);
                    $sheet->setCellValueExplicit('B'.$rn, $res['trans_ref'], PHPExcel_Cell_DataType::TYPE_STRING);
                    $sheet->getCell('C'.$rn)->setValue(sql2Date($res['reg_date'])." ".toTimeW($res['reg_date']));
                    $sheet->getCell('D'.$rn)->setValue(ucwords(strtolower($res['user'])));
                    $sheet->getCell('E'.$rn)->setValue($res['action']);
                    $sheet->getCell('F'.$rn)->setValue($res['qty']);
                    $rn++;
                }
            }
        }else{
            $sheet->getCell('A'.$rn)->setValue('No Data Found.');
        }

        update_load(70);

        update_load(100);
        if (ob_get_contents()) 
            ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=menu_history.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
        
    }

    public function print_menu_movement_pdf()
    {
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Sales Report');
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
        
        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $item_id = $_GET['item_id'];     
        $type = $_GET['type'];        
   
        $daterange = $_GET['daterange'];      
        $dates = explode(" to ",$daterange);
        $from = date2SqlDateTime($dates[0]);
        $to = date2SqlDateTime($dates[1]);
        // $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        // $table  = "menu_moves";
        // $select = "menu_moves.*,menus.menu_name as name,menus.menu_code,trans_types.name as particular";
        // $join["menus"] = array('content'=>"menu_moves.item_id = menus.menu_id");
        // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
        // $args = array();
        // $args2 = array();
        // $args3 = array();
        // if(isset($item_id) && $item_id != "" && $item_id != "undefined"){
        //     $args['menu_moves.item_id'] = $item_id;
        //     $args2['menu_moves.item_id'] = $item_id;
        //     $args3['menu_moves.item_id'] = $item_id;
        // }

        // if(isset($type) && $type != "" && $type != "undefined"){
        //     $args['menu_moves.type_id'] = constant($type);
        //     $args2['menu_moves.type_id'] =  constant($type);
        //     $args3['menu_moves.type_id'] =  constant($type);
        // }

        // if(isset($_GET['daterange']) && $_GET['daterange'] != ""){
        //     $daterange = $_GET['daterange'];
        //     $dates = explode(" to ",$daterange);
        //     $from = date2SqlDateTime($dates[0]);
        //     $to = date2SqlDateTime($dates[1]);
        //     $args["menu_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        //     $args2["menu_moves.reg_date  < '".$from."'"] = array('use'=>'where','val'=>null,'third'=>false);
        //     $args3["menu_moves.reg_date  > '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // }
        // $args['menu_moves.inactive'] = 0;
        // $args2['menu_moves.inactive'] = 0;
        // $args3['menu_moves.inactive'] = 0;
        // $order = array('menu_moves.reg_date'=>'asc','menus.menu_name'=>'asc');
        // $group = null;
        // // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        // // $page = paginate('items/get_items',$count,$total_rows,$pagi);
        // $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
        // echo $this->site_model->db->last_query(); die();
        $moves_array = array();
        if($this->session->userData('menu_moves_cart')){
            $moves_array = $this->session->userData('menu_moves_cart');
        }

        $args2 = array();

        $tablem = "trans_sales_menus";
        $selectm = "trans_sales_menus.*,menus.menu_name,menus.menu_code,trans_types.name as particular,trans_sales.datetime, trans_sales.trans_ref";
        $selectm_s = "sum(qty) as menu_sales_qty";
        $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menus.sales_id");
        $joinm["menus"] = array('content'=>"menus.menu_id = trans_sales_menus.menu_id");
        $joinm["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
        $args3 = array();
        // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
        $args3["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args3["trans_sales.type_id"] = 10;
        $args3["trans_sales.inactive"] = '0';


        if(isset($post['menu-search']) && $post['menu-search'] != ""){
            $args2['menu_moves.item_id'] = $post['menu-search'];
            $args3['trans_sales_menus.menu_id'] = $post['menu-search'];
        }
        // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
            // $daterange = $post['calendar_range'];
            // $dates = explode(" to ",$daterange);
            // $from = date2SqlDateTime($dates[0]);
            // $to = date2SqlDateTime($dates[1]);
            $fromD = date2Sql($dates[0]);
            $args2["DATE(menu_moves.reg_date)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args3["DATE(trans_sales.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args4["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // }
        $menu_moves_sum = $this->items_model->get_menu_moves_total(null,$args2);

        $orderm = null;
        $groupm = 'trans_sales_menus.menu_id';
        $menus_sales_total = $this->site_model->get_tbl($tablem,$args3,$orderm,$joinm,true,$selectm_s,$groupm);

        $before_s_menu = 0;
        if($menus_sales_total){
            $before_s_menu = $menus_sales_total[0]->menu_sales_qty;
        }
        $before_m_menu = 0;
        if($menu_moves_sum){
            $before_m_menu = $menu_moves_sum[0]->in_menu_qty;
        }

        $before_qty = (int) $before_m_menu - (int) $before_s_menu;

        // echo "<pre>",print_r($items),"</pre>";die();
        $pdf->Write(0, 'Menu Movement Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();
        $pdf->SetFont('helvetica', 'B', 9);
        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(25, 0, 'Description', 'B', 0, 'L');        
        $pdf->Cell(25, 0, 'Reference #', 'B', 0, 'L');        
        $pdf->Cell(35, 0, 'Trans Date', 'B', 0, 'L');        
        // $pdf->Cell(25, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(25, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(92, 0, 'Menu Name', 'B', 0, 'L');        
        // $pdf->Cell(15, 0, 'UOM', 'B', 0, 'C');        
        $pdf->Cell(25, 0, 'Qty In', 'B', 0, 'R');        
        $pdf->Cell(25, 0, 'Qty Out', 'B', 0, 'R');        
        $pdf->Cell(25, 0, 'QOH', 'B', 0, 'R');        
        $pdf->ln();   
        
        $pdf->SetFont('helvetica', '', 9);     
                
        $pdf->Cell(227, 0, "Quantity on Hand Before ".sql2Date($fromD), 'B', 0, 'L');       
        $pdf->Cell(25, 0, $before_qty, 'B', 0, 'R'); 
        $pdf->ln();


        if(count($moves_array) > 0){
            // $ctr = 1;
            // $last = count($items);
            // $colspan = 5;
            // $curr = $before_m_item;
            $ctr = 1;
            $last = count($moves_array);
            $curr = $before_qty;
            foreach ($moves_array as $dt =>$value) {

                foreach ($value as $key => $res) {
                    // if($ctr == 1){

                    // }

                    $pdf->Cell(25, 0, ucwords(strtolower($res['particular'])), '', 0, 'L');        
                    $pdf->Cell(25, 0, $res['trans_ref'], '', 0, 'L');        
                    $pdf->Cell(35, 0, sql2Date($res['reg_date'])." ".toTimeW($res['reg_date']), '', 0, 'L');        
                    // $pdf->Cell(25, 0, 'VAT Sales', 'B', 0, 'C');        
                    // $pdf->Cell(25, 0, 'VAT', 'B', 0, 'C');

                    $pdf->SetFont('helvetica', '', 9);

                    if (strlen($res['name'])) {
                        $width = 92;
                        $font_size = 9;
                        do {
                            $font_size -= 0.5;
                            $string_font_width = $pdf->GetStringWidth(ucwords(strtolower($res['name'])),'helvetica','',$font_size) + 3; # +4 Not exactly sure but works
                        } while ($string_font_width > $width);

                        // $pdf->SetFont('dejavb','',$font_size);
                        $pdf->SetFont('helvetica', '', $font_size);
                       
                    }
                    $pdf->Cell(92, 0, ucwords(strtolower($res['name'])), '', 0, 'L'); 
                    // $pdf->SetFont('helvetica', '', 9);       
                    // $pdf->Cell(15, 0, $res->uom, '', 0, 'C');   

                    $in = "";
                    $out = "";
                    if($res['qty'] >= 0){
                        $in = num($res['qty']);
                        $curr += $res['qty'];
                        $out = "";
                    }
                    else{
                        $out = num($res['qty']);
                        $curr += $res['qty'];
                        $out = $out * -1;
                    }

                    $pdf->Cell(25, 0, $in, '', 0, 'R');        
                    $pdf->Cell(25, 0, $out, '', 0, 'R');        
                    $pdf->Cell(25, 0, $curr, '', 0, 'R');        
                    $pdf->ln();   

                    $ctr++;

                }
            }
        }

        // $pdf->Cell(227, 0, "Quantity on Hand Before ".sql2Date($to)." ".toTimeW($to), 'T', 0, 'L');       
        // $pdf->Cell(25, 0, $curr + $after_m_item, 'T', 0, 'R'); 
        // $pdf->ln();
        //Close and output PDF document
        $pdf->Output('sales_report.pdf', 'I');
        

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

     //excel
    public function print_menu_movement_excel()
    {
        // $this->load->database('main', TRUE);
        
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Menu Movement Report';
        $rc=1;
        #GET VALUES
        // start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
        $this->load->model('dine/setup_model');
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        // update_load(10);
        // sleep(1);
        
        $item_id = $_GET['item_id'];        
        $type = $_GET['type'];  
        
        $daterange = $_GET['daterange'];      
        $dates = explode(" to ",$daterange);
        $from = date2SqlDateTime($dates[0]);
        $to = date2SqlDateTime($dates[1]);
        // $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        // $table  = "menu_moves";
        // $select = "menu_moves.*,menus.menu_name as name,menus.menu_code,trans_types.name as particular";
        // $join["menus"] = array('content'=>"menu_moves.item_id = menus.menu_id");
        // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
        // $args = array();
        // $args2 = array();
        // $args3 = array();
        // if(isset($item_id) && $item_id != "" && $item_id != "undefined"){
        //     $args['menu_moves.item_id'] = $item_id;
        //     $args2['menu_moves.item_id'] = $item_id;
        //     $args3['menu_moves.item_id'] = $item_id;
        // }

        // if(isset($type) && $type != "" && $type != "undefined"){
        //     $args['menu_moves.type_id'] = constant($type);
        //     $args2['menu_moves.type_id'] =  constant($type);
        //     $args3['menu_moves.type_id'] =  constant($type);
        // }

        // if(isset($_GET['daterange']) && $_GET['daterange'] != ""){
        //     $daterange = $_GET['daterange'];
        //     $dates = explode(" to ",$daterange);
        //     $from = date2SqlDateTime($dates[0]);
        //     $to = date2SqlDateTime($dates[1]);
        //     $args["menu_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        //     $args2["menu_moves.reg_date  < '".$from."'"] = array('use'=>'where','val'=>null,'third'=>false);
        //     $args3["menu_moves.reg_date  > '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // }
        // $args['menu_moves.inactive'] = 0;
        // $args2['menu_moves.inactive'] = 0;
        // $args3['menu_moves.inactive'] = 0;
        // $order = array('menu_moves.reg_date'=>'asc','menus.menu_name'=>'asc');
        // $group = null;
        // // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        // // $page = paginate('items/get_items',$count,$total_rows,$pagi);
        // $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
        // echo $this->site_model->db->last_query(); die();
        $moves_array = array();
        if($this->session->userData('menu_moves_cart')){
            $moves_array = $this->session->userData('menu_moves_cart');
        }

        $args2 = array();

        $tablem = "trans_sales_menus";
        $selectm = "trans_sales_menus.*,menus.menu_name,menus.menu_code,trans_types.name as particular,trans_sales.datetime, trans_sales.trans_ref";
        $selectm_s = "sum(qty) as menu_sales_qty";
        $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menus.sales_id");
        $joinm["menus"] = array('content'=>"menus.menu_id = trans_sales_menus.menu_id");
        $joinm["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
        $args3 = array();
        // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
        $args3["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args3["trans_sales.type_id"] = 10;
        $args3["trans_sales.inactive"] = '0';


        if(isset($post['menu-search']) && $post['menu-search'] != ""){
            $args2['menu_moves.item_id'] = $post['menu-search'];
            $args3['trans_sales_menus.menu_id'] = $post['menu-search'];
        }
        // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
            // $daterange = $post['calendar_range'];
            // $dates = explode(" to ",$daterange);
            // $from = date2SqlDateTime($dates[0]);
            // $to = date2SqlDateTime($dates[1]);
            $fromD = date2Sql($dates[0]);
            $args2["DATE(menu_moves.reg_date)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args3["DATE(trans_sales.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args4["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // }
        $menu_moves_sum = $this->items_model->get_menu_moves_total(null,$args2);

        $orderm = null;
        $groupm = 'trans_sales_menus.menu_id';
        $menus_sales_total = $this->site_model->get_tbl($tablem,$args3,$orderm,$joinm,true,$selectm_s,$groupm);

        $before_s_menu = 0;
        if($menus_sales_total){
            $before_s_menu = $menus_sales_total[0]->menu_sales_qty;
        }
        $before_m_menu = 0;
        if($menu_moves_sum){
            $before_m_menu = $menu_moves_sum[0]->in_menu_qty;
        }

        $before_qty = (int) $before_m_menu - (int) $before_s_menu;


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
                'size' => 12,
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
        $styleCenter = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
        );
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldLeft = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldRight = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldCenter = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        
        $headers = array('Description','Reference #', 'Trans Date','Menu Name','Qty IN','Qty OUT', 'QOH');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        // $sheet->getColumnDimension('H')->setWidth(20);
        // $sheet->getColumnDimension('I')->setWidth(20);
        // $sheet->getColumnDimension('J')->setWidth(20);
        // $sheet->getColumnDimension('K')->setWidth(20);
        // $sheet->getColumnDimension('L')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Menu Movement Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;


        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++; 

        $sheet->mergeCells('A'.$rc.':F'.$rc);
        $sheet->getCell('A'.$rc)->setValue("Quantity on Hand Before ".sql2Date($from));
        $sheet->getCell('G'.$rc)->setValue($before_qty);
        $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
        $rc++;               
                
        if(count($moves_array) > 0){
            $ctr = 1;
            $last = count($moves_array);
            $curr = $before_qty;
            foreach ($moves_array as $dt =>$value) {

                foreach ($value as $key => $res) {
                    // if($ctr == 1){

                    //     $c = "";
                    //     if($res->it_per_pack > 0){
                    //         $c = $before_m_item/$res->it_per_pack;
                    //     }
                    //     $sheet->getCell('L'.$rc)->setValue($c);
                    //     $sheet->getStyle('L'.$rc)->applyFromArray($styleNum);
                    //     $rc++;
                    // }

                    $sheet->getCell('A'.$rc)->setValue(ucwords(strtolower($res['particular'])));
                    $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                    $sheet->setCellValueExplicit('B'.$rc, $res['trans_ref'],PHPExcel_Cell_DataType::TYPE_STRING);
                    // $sheet->getCell('B'.$rc)->setValue($res->trans_ref);
                    $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                    $sheet->getCell('C'.$rc)->setValue(sql2Date($res['reg_date'])." ".toTimeW($res['reg_date']));
                    $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
                    $sheet->getCell('D'.$rc)->setValue(ucwords(strtolower($res['name'])));
                    $sheet->getStyle('D'.$rc)->applyFromArray($styleTxt);
                    // $sheet->getCell('E'.$rc)->setValue($res['uom']);
                    // $sheet->getStyle('E'.$rc)->applyFromArray($styleTxt);

                    $in = "";
                    $out = "";
                    if($res->qty >= 0){
                        $in = num($res['qty']);
                        $curr += $res['qty'];
                        $out = "";
                    }
                    else{
                        $out = num($res['qty']);
                        $curr += $res['qty'];
                        $out = $out * -1;
                    }

                    $sheet->getCell('E'.$rc)->setValue($in);
                    $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                    $sheet->getCell('F'.$rc)->setValue($out);
                    $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                    $sheet->getCell('G'.$rc)->setValue($curr);
                    $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
                    // $sheet->getCell('I'.$rc)->setValue($res->it_per_pack_uom);
                    // $sheet->getStyle('I'.$rc)->applyFromArray($styleTxt);

                    // $this->make->td($res->it_per_pack_uom,array('class'=>'text-center'));
                    // $in = "";
                    // $out = "";
                    // $curr2 = "";
                    // if($res->it_per_pack > 0){
                    //     $curr2 = $curr/$res->it_per_pack;
                    //     if($res->qty >= 0){
                    //         $val = $res->qty/$res->it_per_pack;
                    //         $in = num($val);
                    //     }
                    //     else{
                    //         $val = $res->qty/$res->it_per_pack;
                    //         $out = num($val);
                    //     }
                    // }

                    // $sheet->getCell('J'.$rc)->setValue($in);
                    // $sheet->getStyle('J'.$rc)->applyFromArray($styleNum);
                    // $sheet->getCell('K'.$rc)->setValue($out);
                    // $sheet->getStyle('K'.$rc)->applyFromArray($styleNum);
                    // $sheet->getCell('L'.$rc)->setValue($curr2);
                    // $sheet->getStyle('L'.$rc)->applyFromArray($styleNum);
                    // $this->make->td($in,array('class'=>'text-right'));
                    // $this->make->td($out,array('class'=>'text-right'));
                    // $this->make->td($curr2,array('class'=>'text-right'));

                    $rc++;
                    $ctr++;
                }
            }
        }

        // $sheet->mergeCells('A'.$rc.':G'.$rc);
        // $sheet->getCell('A'.$rc)->setValue("Quantity on Hand After ".sql2Date($to)." ".toTimeW($to));
        // $sheet->getCell('H'.$rc)->setValue($curr + $after_m_item);
        // $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);
        // $c = "";
        // if($res->it_per_pack > 0){
        //     $c = ($curr + $after_m_item)/$res->it_per_pack;
        // } 
        // $sheet->getCell('L'.$rc)->setValue($c);
        // $sheet->getStyle('L'.$rc)->applyFromArray($styleNum);
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
        // $trans_count = count($trans) + 10;
        // foreach ($trans as $val) {
        //     $tot_gross += $val->gross;
        //     $tot_cost += $val->cost;
        //     $tot_margin += $val->gross - $val->cost;
        // }
        // foreach ($trans_mod as $vv) {
        //     $tot_mod_gross += $vv->mod_gross;
        // }

        // foreach ($trans as $k => $v) {
        //     // $pdf->Cell(55, 0, $v->menu_name, '', 0, 'L');        
        //     // $pdf->Cell(38, 0, $v->menu_cat_name, '', 0, 'L');        
        //     // $pdf->Cell(25, 0, num($v->qty), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->vat_sales), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->vat), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->gross), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num(0), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->cost), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num(0), '', 0, 'R');                    
        //     // $pdf->ln(); 

        //     $sheet->getCell('A'.$rc)->setValue($v->menu_name);
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue($v->menu_cat_name);
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('C'.$rc)->setValue(num($v->qty));
        //     $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        //     // $sheet->getCell('D'.$rc)->setValue(num($v->vat_sales));     
        //     // $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //     // $sheet->getCell('E'.$rc)->setValue(num($v->vat));     
        //     // $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('D'.$rc)->setValue(num($v->gross));     
        //     $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //     if($tot_gross != 0){
        //         $sheet->getCell('E'.$rc)->setValue(num($v->gross / $tot_gross * 100)."%");                     
        //     }else{
        //         $sheet->getCell('E'.$rc)->setValue("0.00%");                                     
        //     }
        //     $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('F'.$rc)->setValue(num($v->cost));     
        //     $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //     if($tot_cost != 0){
        //         $sheet->getCell('G'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
        //     }else{
        //         $sheet->getCell('G'.$rc)->setValue("0.00%");                     
        //     }
        //     $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('H'.$rc)->setValue(num($v->gross - $v->cost));     
        //     $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);               

        //     // Grand Total
        //     $tot_qty += $v->qty;
        //     // $tot_vat_sales += $v->vat_sales;
        //     // $tot_vat += $v->vat;
        //     // $tot_gross += $v->gross;
        //     $tot_sales_prcnt = 0;
        //     // $tot_cost += $v->cost;
        //     $tot_cost_prcnt = 0;

        //     $counter++;
        //     $progress = ($counter / $trans_count) * 100;
        //     update_load(num($progress)); 
        //     $rc++;             
        // }
        // $sheet->getCell('A'.$rc)->setValue('Grand Total');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue("");
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('C'.$rc)->setValue(num($tot_qty));
        // $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
        // // $sheet->getCell('D'.$rc)->setValue(num($tot_vat_sales));     
        // // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        // // $sheet->getCell('E'.$rc)->setValue(num($tot_vat));     
        // // $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('D'.$rc)->setValue(num($tot_gross));     
        // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('E'.$rc)->setValue();     
        // $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('F'.$rc)->setValue(num($tot_cost));     
        // $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('G'.$rc)->setValue();     
        // $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('H'.$rc)->setValue(num($tot_margin));     
        // $sheet->getStyle('H'.$rc)->applyFromArray($styleBoldRight);
        // $rc++; 
        

        // ///////////fpr payments
        // $this->cashier_model->db = $this->load->database('main', TRUE);
        // $args = array();
        // // if($user)
        // //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        // $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        // // if($menu_cat_id != 0){
        // //     $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        // // }


        // $post = $this->set_post();
        // // $curr = $this->search_current();
        // $curr = false;
        // $trans = $this->trans_sales($args,$curr);
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

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('GROSS');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($tot_gross + $tot_mod_gross));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('VAT SALES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($taxable));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('VAT');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($vat_));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('VAT EXEMPT SALES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($nontaxable-$zero_rated));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('ZERO RATED');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($zero_rated));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // // //MENU SUB CAT
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('SUB CATEGORIES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $subcats = $trans_menus['sub_cats'];
        // $total = 0;
        // foreach ($subcats as $id => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        //     $total += $val['amount'];
        // }
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('SubTotal'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($total));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // $rc++;
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('MODIFIERS TOTAL'));
        // $sheet->getCell('B'.$rc)->setValue(num($trans_menus['mods_total']));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('TOTAL'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($total + $trans_menus['mods_total']));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // //DISCOUNTS
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('DISCOUNT');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $types = $trans_discounts['types'];
        // foreach ($types as $code => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        // }

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($discounts));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('VAT EXEMPT'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($less_vat));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // // //CAHRGES
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('CHARGES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $types = $trans_charges['types'];
        // foreach ($types as $code => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        // }
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($charges));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // // //PAYMENTS
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('PAYMENT MODE');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('PAYMENT AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $payments_types = $payments['types'];
        // $payments_total = $payments['total'];
        // foreach ($payments_types as $code => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($code));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // }

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($payments_total));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // if($trans['total_chit']){
        //     $rc++; $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper('Total Chit'));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        //     $sheet->getCell('B'.$rc)->setValue(num($trans['total_chit']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
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
        // $rc++;
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Trans Count'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $tc_total  = 0;
        // $tc_qty = 0;
        // foreach ($types_total as $typ => $tamnt) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($typ));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(count($types[$typ]));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('C'.$rc)->setValue(num($tamnt));
        //     $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        //     $tc_total += $tamnt;
        //     $tc_qty += count($types[$typ]);
        // }
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('TC TOTAL'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('C'.$rc)->setValue(num($tc_total));
        // $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);



        update_load(100);        
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
        

        //============================================================+
        // END OF FILE
        //============================================================+   
    }
    //menu mocvement
    public function mod_inv_moves(){
        $data = $this->syter->spawn('inq');
        $data['page_title'] = fa('fa-random').'Modifiers Inventory Movements';
        $data['code'] = modInvMovesPage();           
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js','js/jspdf.js');
        $data['load_js'] = "dine/items.php";
        $data['use_js'] = "modInvMovesJS";
        $this->load->view('page',$data);
    }

    public function get_mod_inv_moves($id=null,$asJson=true){
        sess_clear('menu_moves_cart');
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        // $total_rows = 100;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }

        $daterange = $post['calendar_range'];
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);

        $from_date = date2Sql($dates[0]); 
        $to_date = date2Sql($dates[1]);

        $strtt =  strtotime($from_date);
        $moves_array = array();
        $curr_date = date('Y-m-d');
        do{
            $this->site_model->db = $this->load->database('default', TRUE);

            $datefrom = date('Y-m-d', $strtt);

            // $table  = "menu_moves";
            // $select = "menu_moves.*,menus.menu_name,menus.menu_code,trans_types.name as particular";
            // $join["menus"] = array('content'=>"menu_moves.item_id = menus.menu_id");
            // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
            $table  = "trans_sales_menu_modifiers";
            $select = "trans_sales_menu_modifiers.*,trans_types.name as particular, trans_sales.trans_ref";
            $join["modifiers"] = array('content'=>"modifiers.name = trans_sales_menu_modifiers.mod_name");
            $join["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menu_modifiers.sales_id");
            $join["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
            $args = array();
            // $args2 = array();
            if(isset($post['menu-search']) && $post['menu-search'] != ""){
                $args['modifiers.mod_id'] = $post['menu-search'];
            }

        //  if(isset($post['type']) && $post['type'] != ""){
            
        //         $args['menu_moves.type_id'] = constant($post['type']);
        //         $args2['menu_moves.type_id'] = constant($post['type']);
        //         $args3['menu_moves.type_id'] = constant($post['type']);
        //         $args4['menu_moves.type_id'] = constant($post['type']);
        // }
            // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
                // $daterange = $post['calendar_range'];
                // $dates = explode(" to ",$daterange);
                // $from = date2SqlDateTime($dates[0]);
                // $to = date2SqlDateTime($dates[1]);
                // $args["menu_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args["DATE(trans_sales_menu_modifiers.datetime)  = '".$datefrom."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // }
            // $args['menu_moves.inactive'] = 0;
            $order = array('trans_sales_menu_modifiers.datetime'=>'desc','modifiers.name'=>'asc');
            $group = null;
            // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
            // $page = paginate('items/get_items',$count,$total_rows,$pagi);
            $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
            // echo $this->site_model->db->last_query();die();
            // return false;
            // $args['items.barcode'] = array('use'=>'where','val'=>$barcode);
            // echo $this->db->last_query();
            // echo "<pre>",print_r($items),"</pre>";die();
            if($items){
                foreach($items as $res){
                    $ids = strtotime($res->datetime);
                    if(isset($moves_array[$ids][$res->trans_ref])){
                        $moves_array[$ids][$res->trans_ref]['qty'] += $res->qty;
                    }else{
                        $moves_array[$ids][$res->trans_ref] = array(
                            'particular'=>$res->particular,
                            'trans_ref'=>$res->trans_ref,
                            'reg_date'=>$res->datetime,
                            'code'=>$res->mod_name,
                            'name'=>$res->mod_name,
                            'qty'=>$res->qty,
                        );
                    }
                }
            }

            $tablem = "trans_sales_menus";
            $selectm = "trans_sales_menus.*,menus.menu_name,menus.menu_code,trans_types.name as particular,trans_sales.datetime, trans_sales.trans_ref";
            // $selectm_s = "sum(qty) as menu_sales_qty";
            $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menus.sales_id");
            $joinm["menus"] = array('content'=>"menus.menu_id = trans_sales_menus.menu_id");
            $joinm["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
            $args3 = array();
            $args4 = array();
            // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
            // $args3["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args3["trans_sales.type_id"] = 10;
            // $args3["trans_sales.inactive"] = '0';

            $args4["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $args4["trans_sales.type_id"] = 10;
            $args4["trans_sales.inactive"] = '0';

         if(isset($post['type']) && $post['type'] != ""){
            
                $args4['trans_sales.type_id'] = constant($post['type']);
                // $args2['menu_moves.type_id'] = constant($post['type']);
                // $args3['menu_moves.type_id'] = constant($post['type']);
                // $args4['menu_moves.type_id'] = constant($post['type']);
        }

            if(isset($post['menu-search']) && $post['menu-search'] != ""){
                // $args2['menu_moves.item_id'] = $post['menu-search'];
                // $args3['trans_sales_menus.menu_id'] = $post['menu-search'];
                $args4['trans_sales_menus.menu_id'] = $post['menu-search'];
            }
            // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
            //     $daterange = $post['calendar_range'];
            //     $dates = explode(" to ",$daterange);
            //     $from = date2SqlDateTime($dates[0]);
            //     $to = date2SqlDateTime($dates[1]);
            //     $fromD = date2Sql($dates[0]);
            // $args2["DATE(menu_moves.reg_date)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args3["DATE(trans_sales.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args4["DATE(trans_sales.datetime) = '".$datefrom."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // }

            $ordermm = null;
            $groupmm = null;
            if($curr_date == $datefrom){
                $this->site_model->db = $this->load->database('default', TRUE);
                $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm); 
            }else{
                $this->site_model->db = $this->load->database('main', TRUE);
                $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm); 
            }

            // echo $this->db->last_query().'<br><br>';
            // if($get_menus_sales){
            //     foreach($get_menus_sales as $res){
            //         $ids = strtotime($res->datetime);
            //         if(isset($moves_array[$ids][$res->trans_ref])){
            //             $moves_array[$ids][$res->trans_ref]['qty'] -= $res->qty;
            //         }else{
            //             $moves_array[$ids][$res->trans_ref] = array(
            //                 'particular'=>$res->particular,
            //                 'trans_ref'=>$res->trans_ref,
            //                 'reg_date'=>$res->datetime,
            //                 'code'=>$res->menu_code,
            //                 'name'=>$res->menu_name,
            //                 'qty'=>-$res->qty,
            //             );
            //         }
            //     }
            // }


            $strtt = strtotime($datefrom . ' + 1 day');

        }while($datefrom != $to_date);


            
        // echo "<pre>",print_r($moves_array),"</pre>";die();





        // $table  = "menu_moves";
        // $select = "menu_moves.*,menus.menu_name,menus.menu_code,trans_types.name as particular";
        // $join["menus"] = array('content'=>"menu_moves.item_id = menus.menu_id");
        // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
        // $args = array();
        // $args2 = array();
        // if(isset($post['menu-search']) && $post['menu-search'] != ""){
        //     $args['menu_moves.item_id'] = $post['menu-search'];
        // }
        // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
        //     $daterange = $post['calendar_range'];
        //     $dates = explode(" to ",$daterange);
        //     $from = date2SqlDateTime($dates[0]);
        //     $to = date2SqlDateTime($dates[1]);
        //     $args["menu_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // }
        // $args['menu_moves.inactive'] = 0;
        // $order = array('menu_moves.reg_date'=>'desc','menus.menu_name'=>'asc');
        // $group = null;
        // // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        // // $page = paginate('items/get_items',$count,$total_rows,$pagi);
        // $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
        // // echo $this->site_model->db->last_query();
        // // return false;
        // // $args['items.barcode'] = array('use'=>'where','val'=>$barcode);
        // // echo $this->db->last_query();
        // // echo "<pre>",print_r($items),"</pre>";die();

        $args2 = array();
        $joinm = array();

        $tablem = "trans_sales_menu_modifiers";
        $selectm = "trans_sales_menu_modifiers.*,trans_types.name as particular,trans_sales.datetime, trans_sales.trans_ref";
        $selectm_s = "sum(qty) as menu_sales_qty";
        $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menu_modifiers.sales_id");
        // $joinm["menus"] = array('content'=>"menus.menu_id = trans_sales_menus.menu_id");
        $joinm["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
        $args3 = array();
        // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
        $args3["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args3["trans_sales.type_id"] = 10;
        $args3["trans_sales.inactive"] = '0';


        if(isset($post['menu-search']) && $post['menu-search'] != ""){
            $args2['trans_sales_menu_modifiers.menu_id'] = $post['menu-search'];
            $args3['trans_sales_menu_modifiers.menu_id'] = $post['menu-search'];
        }
        // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
            // $daterange = $post['calendar_range'];
            // $dates = explode(" to ",$daterange);
            // $from = date2SqlDateTime($dates[0]);
            // $to = date2SqlDateTime($dates[1]);
            $fromD = date2Sql($dates[0]);
            $args2["DATE(trans_sales_menu_modifiers.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args3["DATE(trans_sales.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args4["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // }
        // $menu_moves_sum = $this->items_model->get_menu_moves_total(null,$args2);
        $menu_moves_sum = $this->items_model->get_mod_inv_moves_total(null,$args2);

        $orderm = null;
        $groupm = 'trans_sales_menu_modifiers.menu_id';
        $menus_sales_total = $this->site_model->get_tbl($tablem,$args3,$orderm,$joinm,true,$selectm_s,$groupm);
        // echo $this->site_model->db->last_query();die();
        // echo "<pre>",print_r($menus_sales_total),"<,/pre>";die();

        $before_s_menu = 0;
        // if($menus_sales_total){
        //     $before_s_menu = $menus_sales_total[0]->menu_sales_qty;
        // }
        $before_m_menu = 0;
        if($menu_moves_sum){
            $before_m_menu = $menu_moves_sum[0]->in_menu_qty;
        }

        // $before_qty = (int) $before_m_menu - (int) $before_s_menu;
        $bq=0;
        $bq = (int) $before_m_menu;
        $before_qty =  -1 * abs($bq);

        // $ordermm = null;
        // $groupmm = null;
        // $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm);

        // // echo $this->db->last_query();
        // // echo "<pre>",print_r($items),"</pre>";die();

        // $moves_array = array();
        // foreach($items as $res){
        //     if(isset($moves_array[$res->reg_date][$res->trans_ref])){
        //         $moves_array[$res->reg_date][$res->trans_ref]['qty'] += $res->qty;
        //     }else{
        //         $moves_array[$res->reg_date][$res->trans_ref] = array(
        //             'particular'=>$res->particular,
        //             'trans_ref'=>$res->trans_ref,
        //             'reg_date'=>$res->reg_date,
        //             'code'=>$res->menu_code,
        //             'name'=>$res->menu_name,
        //             'qty'=>$res->qty,
        //         );
        //     }
        // }

        // foreach($get_menus_sales as $res){
        //     if(isset($moves_array[$res->datetime][$res->trans_ref])){
        //         $moves_array[$res->datetime][$res->trans_ref]['qty'] -= $res->qty;
        //     }else{
        //         $moves_array[$res->datetime][$res->trans_ref] = array(
        //             'particular'=>$res->particular,
        //             'trans_ref'=>$res->trans_ref,
        //             'reg_date'=>$res->datetime,
        //             'code'=>$res->menu_code,
        //             'name'=>$res->menu_name,
        //             'qty'=>-$res->qty,
        //         );
        //     }
        // }

        ksort($moves_array);
        // echo $this->db->last_query();
        // echo "<pre>",print_r($moves_array),"</pre>";die();

        $json = array();
        $html = "";
        $colspan = 5;
        $this->make->sRow(array('class'=>'tdhd'));
            $this->make->sTd(array('colspan'=>$colspan));
                $this->make->span("Quantity on Hand Before ".sql2Date($fromD));
            $this->make->eTd();
            $this->make->td("");
            $this->make->td(num($before_qty),array('class'=>'text-right','style'=>'border-right:5px solid #fff !important;'));
            $c = "";
            // if($res->it_per_pack > 0){
            //     $c = $res->curr_item_qty/$res->it_per_pack;
            // }    
            // $this->make->td(num($c)." ".$res->it_per_pack_uom,array('class'=>'text-right'));
        $this->make->eRow();
        if(count($moves_array) > 0){
            $this->session->set_userdata('menu_moves_cart',$moves_array);
            $ctr = 1;
            $last = count($moves_array);
            $curr = $before_qty;
            foreach ($moves_array as $dt =>$value) {

                foreach ($value as $key => $res) {
                

                    $this->make->sRow();
                        $this->make->td(ucwords(strtolower($res['particular'])));
                        $this->make->td($res['trans_ref']);
                        $this->make->td(sql2Date($res['reg_date'])." ".toTimeW($res['reg_date']));
                        $this->make->td("[".$res['code']."] ".ucwords(strtolower($res['name'])));
                        // $this->make->td($res->uom,array('class'=>'text-center'));
                        $in = "0";
                        $out = "0";
                        // if($res['qty'] >= 0){
                        //     $in = $res['qty'];
                        //     $curr += $res['qty'];
                        // }
                        // else{
                            $out = $res['qty'];
                            $curr += $res['qty'];
                        // }

                        $this->make->td($in,array('class'=>'text-right'));
                        $this->make->td($out * -1,array('class'=>'text-right'));
                        // $this->make->td(num($res->curr_item_qty),array('class'=>'text-right','style'=>'border-right:1px solid #fff;'));
                        $this->make->td($curr * -1,array('class'=>'text-right','style'=>'border-right:1px solid #fff;'));

                        // $this->make->td($res->it_per_pack_uom,array('class'=>'text-center'));
                        // $in = "";
                        // $out = "";
                        // $curr2 = "";
                        // if($res->it_per_pack > 0){
                        //     $curr2 = $res->curr_item_qty/$res->it_per_pack;
                        //     if($res->qty >= 0){
                        //         $val = $res->qty/$res->it_per_pack;
                        //         $in = num($val);
                        //     }
                        //     else{
                        //         $val = $res->qty/$res->it_per_pack;
                        //         $out = num($val);
                        //     }
                        // }
                        // $this->make->td($in,array('class'=>'text-right'));
                        // $this->make->td($out,array('class'=>'text-right'));
                        // $this->make->td(num($curr2),array('class'=>'text-right'));
                    
                    $this->make->eRow();

                }

            }
        }
        $json['html'] = $this->make->code();
        // if(count($items) > 0){
        //     $ctr = 1;
        //     $last = count($items);
        //     $colspan = 5;
        //     foreach ($items as $res) {
        //         if($ctr == 1){
        //             $this->make->sRow(array('class'=>'tdhd'));
        //                 $this->make->sTd(array('colspan'=>$colspan));
        //                     $this->make->span("Quantity on Hand After ".sql2Date($res->reg_date)." ".toTimeW($res->reg_date));
        //                 $this->make->eTd();
        //                 $this->make->td("");
        //                 $this->make->td("");
        //                 $this->make->td(num($res->curr_item_qty)." ".$res->uom,array('class'=>'text-right','style'=>'border-right:5px solid #fff !important;'));
        //                 $this->make->td("");
        //                 $this->make->td("");
        //                 $this->make->td("");
        //                 $c = "";
        //                 if($res->it_per_pack > 0){
        //                     $c = $res->curr_item_qty/$res->it_per_pack;
        //                 }    
        //                 $this->make->td(num($c)." ".$res->it_per_pack_uom,array('class'=>'text-right'));
        //             $this->make->eRow();
        //         }
        //         $this->make->sRow();
        //             $this->make->td(ucwords(strtolower($res->particular)));
        //             $this->make->td($res->trans_ref);
        //             $this->make->td(sql2Date($res->reg_date)." ".toTimeW($res->reg_date));
        //             $this->make->td("[".$res->code."] ".ucwords(strtolower($res->name)));
        //             $this->make->td($res->uom,array('class'=>'text-center'));
        //             $in = "";
        //             $out = "";
        //             if($res->qty >= 0)
        //                 $in = num($res->qty);
        //             else
        //                 $out = num($res->qty);
        //             $this->make->td($in,array('class'=>'text-right'));
        //             $this->make->td($out,array('class'=>'text-right'));
        //             $this->make->td(num($res->curr_item_qty),array('class'=>'text-right','style'=>'border-right:1px solid #fff;'));

        //             $this->make->td($res->it_per_pack_uom,array('class'=>'text-center'));
        //             $in = "";
        //             $out = "";
        //             $curr2 = "";
        //             if($res->it_per_pack > 0){
        //                 $curr2 = $res->curr_item_qty/$res->it_per_pack;
        //                 if($res->qty >= 0){
        //                     $val = $res->qty/$res->it_per_pack;
        //                     $in = num($val);
        //                 }
        //                 else{
        //                     $val = $res->qty/$res->it_per_pack;
        //                     $out = num($val);
        //                 }
        //             }
        //             $this->make->td($in,array('class'=>'text-right'));
        //             $this->make->td($out,array('class'=>'text-right'));
        //             $this->make->td(num($curr2),array('class'=>'text-right'));
                
        //         $this->make->eRow();

        //         if($ctr == $last){
        //             $this->make->sRow(array('class'=>'tdhd'));
        //                 $this->make->sTd(array('colspan'=>$colspan));
        //                     $this->make->span("Quantity on Hand Before ".sql2Date($res->reg_date)." ".toTimeW($res->reg_date));
        //                 $this->make->eTd();
        //                 $this->make->td("");
        //                 $this->make->td("");
        //                 $this->make->td(num($res->curr_item_qty)." ".$res->uom,array('class'=>'text-right','style'=>'border-right:5px solid #fff !important;'));
        //                 $this->make->td("");
        //                 $this->make->td("");
        //                 $this->make->td("");
        //                 $c = "";
        //                 if($res->it_per_pack > 0){
        //                     $c = $res->curr_item_qty/$res->it_per_pack;
        //                 } 
        //                 $this->make->td(num($c)." ".$res->it_per_pack_uom,array('class'=>'text-right'));
        //             $this->make->eRow();
        //         }
        //         $ctr++;
        //     }
        //     $json['html'] = $this->make->code();
        // }
        echo json_encode($json);
    }
    public function print_mod_inv_movement_pdf()
    {
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Modifier Inventory Movements Report');
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
        
        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $item_id = $_GET['item_id'];     
        $type = $_GET['type'];        
   
        $daterange = $_GET['daterange'];      
        $dates = explode(" to ",$daterange);
        $from = date2SqlDateTime($dates[0]);
        $to = date2SqlDateTime($dates[1]);
        // $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        // $table  = "menu_moves";
        // $select = "menu_moves.*,menus.menu_name as name,menus.menu_code,trans_types.name as particular";
        // $join["menus"] = array('content'=>"menu_moves.item_id = menus.menu_id");
        // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
        // $args = array();
        // $args2 = array();
        // $args3 = array();
        // if(isset($item_id) && $item_id != "" && $item_id != "undefined"){
        //     $args['menu_moves.item_id'] = $item_id;
        //     $args2['menu_moves.item_id'] = $item_id;
        //     $args3['menu_moves.item_id'] = $item_id;
        // }

        // if(isset($type) && $type != "" && $type != "undefined"){
        //     $args['menu_moves.type_id'] = constant($type);
        //     $args2['menu_moves.type_id'] =  constant($type);
        //     $args3['menu_moves.type_id'] =  constant($type);
        // }

        // if(isset($_GET['daterange']) && $_GET['daterange'] != ""){
        //     $daterange = $_GET['daterange'];
        //     $dates = explode(" to ",$daterange);
        //     $from = date2SqlDateTime($dates[0]);
        //     $to = date2SqlDateTime($dates[1]);
        //     $args["menu_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        //     $args2["menu_moves.reg_date  < '".$from."'"] = array('use'=>'where','val'=>null,'third'=>false);
        //     $args3["menu_moves.reg_date  > '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // }
        // $args['menu_moves.inactive'] = 0;
        // $args2['menu_moves.inactive'] = 0;
        // $args3['menu_moves.inactive'] = 0;
        // $order = array('menu_moves.reg_date'=>'asc','menus.menu_name'=>'asc');
        // $group = null;
        // // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        // // $page = paginate('items/get_items',$count,$total_rows,$pagi);
        // $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
        // echo $this->site_model->db->last_query(); die();
        $moves_array = array();
        if($this->session->userData('menu_moves_cart')){
            $moves_array = $this->session->userData('menu_moves_cart');
        }

        $args2 = array();

        $tablem = "trans_sales_menu_modifiers";
        $selectm = "trans_sales_menu_modifiers.*,trans_types.name as particular,trans_sales.datetime, trans_sales.trans_ref";
        $selectm_s = "sum(qty) as menu_sales_qty";
        $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menu_modifiers.sales_id");
        // $joinm["menus"] = array('content'=>"menus.menu_id = trans_sales_menus.menu_id");
        $joinm["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
        $args3 = array();
        // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
        $args3["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args3["trans_sales.type_id"] = 10;
        $args3["trans_sales.inactive"] = '0';


        if(isset($post['menu-search']) && $post['menu-search'] != ""){
            $args2['trans_sales_menu_modifiers.menu_id'] = $post['menu-search'];
            $args3['trans_sales_menu_modifiers.menu_id'] = $post['menu-search'];
        }
        // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
            // $daterange = $post['calendar_range'];
            // $dates = explode(" to ",$daterange);
            // $from = date2SqlDateTime($dates[0]);
            // $to = date2SqlDateTime($dates[1]);
            $fromD = date2Sql($dates[0]);
            $args2["DATE(trans_sales_menu_modifiers.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args3["DATE(trans_sales.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args4["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // }
        // $menu_moves_sum = $this->items_model->get_menu_moves_total(null,$args2);
        $menu_moves_sum = $this->items_model->get_mod_inv_moves_total(null,$args2);

        $orderm = null;
        $groupm = 'trans_sales_menu_modifiers.menu_id';
        $menus_sales_total = $this->site_model->get_tbl($tablem,$args3,$orderm,$joinm,true,$selectm_s,$groupm);

        // $before_s_menu = 0;
        // if($menus_sales_total){
        //     $before_s_menu = $menus_sales_total[0]->menu_sales_qty;
        // }
        // $before_m_menu = 0;
        // if($menu_moves_sum){
        //     $before_m_menu = $menu_moves_sum[0]->in_menu_qty;
        // }

        // $before_qty = (int) $before_m_menu - (int) $before_s_menu;

        $before_m_menu = 0;
        if($menu_moves_sum){
            $before_m_menu = $menu_moves_sum[0]->in_menu_qty;
        }

        // $before_qty = (int) $before_m_menu - (int) $before_s_menu;
        $bq=0;
        $bq = (int) $before_m_menu;
        $before_qty =  -1 * abs($bq);

        // echo "<pre>",print_r($items),"</pre>";die();
        $pdf->Write(0, 'Modifier Inventory Movements Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();
        $pdf->SetFont('helvetica', 'B', 9);
        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(25, 0, 'Description', 'B', 0, 'L');        
        $pdf->Cell(25, 0, 'Reference #', 'B', 0, 'L');        
        $pdf->Cell(35, 0, 'Trans Date', 'B', 0, 'L');        
        // $pdf->Cell(25, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(25, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(92, 0, 'Menu Name', 'B', 0, 'L');        
        // $pdf->Cell(15, 0, 'UOM', 'B', 0, 'C');        
        $pdf->Cell(25, 0, 'Qty In', 'B', 0, 'R');        
        $pdf->Cell(25, 0, 'Qty Out', 'B', 0, 'R');        
        $pdf->Cell(25, 0, 'QOH', 'B', 0, 'R');        
        $pdf->ln();   
        
        $pdf->SetFont('helvetica', '', 9);     
                
        $pdf->Cell(227, 0, "Quantity on Hand Before ".sql2Date($fromD), 'B', 0, 'L');       
        $pdf->Cell(25, 0, $before_qty, 'B', 0, 'R'); 
        $pdf->ln();


        if(count($moves_array) > 0){
            // $ctr = 1;
            // $last = count($items);
            // $colspan = 5;
            // $curr = $before_m_item;
            $ctr = 1;
            $last = count($moves_array);
            $curr = $before_qty;
            foreach ($moves_array as $dt =>$value) {

                foreach ($value as $key => $res) {
                    // if($ctr == 1){

                    // }

                    $pdf->Cell(25, 0, ucwords(strtolower($res['particular'])), '', 0, 'L');        
                    $pdf->Cell(25, 0, $res['trans_ref'], '', 0, 'L');        
                    $pdf->Cell(35, 0, sql2Date($res['reg_date'])." ".toTimeW($res['reg_date']), '', 0, 'L');        
                    // $pdf->Cell(25, 0, 'VAT Sales', 'B', 0, 'C');        
                    // $pdf->Cell(25, 0, 'VAT', 'B', 0, 'C');

                    $pdf->SetFont('helvetica', '', 9);

                    if (strlen($res['name'])) {
                        $width = 92;
                        $font_size = 9;
                        do {
                            $font_size -= 0.5;
                            $string_font_width = $pdf->GetStringWidth(ucwords(strtolower($res['name'])),'helvetica','',$font_size) + 3; # +4 Not exactly sure but works
                        } while ($string_font_width > $width);

                        // $pdf->SetFont('dejavb','',$font_size);
                        $pdf->SetFont('helvetica', '', $font_size);
                       
                    }
                    $pdf->Cell(92, 0, ucwords(strtolower($res['name'])), '', 0, 'L'); 
                    // $pdf->SetFont('helvetica', '', 9);       
                    // $pdf->Cell(15, 0, $res->uom, '', 0, 'C');   

                    $in = "";
                    $out = "";
                    // if($res['qty'] >= 0){
                    //     $in = num($res['qty']);
                    //     $curr += $res['qty'];
                    //     $out = "";
                    // }
                    // else{
                        $out = num($res['qty']);
                        $curr += $res['qty'];
                        $out = $out * -1;
                    // }

                    $pdf->Cell(25, 0, $in, '', 0, 'R');        
                    $pdf->Cell(25, 0, $out, '', 0, 'R');        
                    $pdf->Cell(25, 0, $curr * -1, '', 0, 'R');        
                    $pdf->ln();   

                    $ctr++;

                }
            }
        }

        // $pdf->Cell(227, 0, "Quantity on Hand Before ".sql2Date($to)." ".toTimeW($to), 'T', 0, 'L');       
        // $pdf->Cell(25, 0, $curr + $after_m_item, 'T', 0, 'R'); 
        // $pdf->ln();
        //Close and output PDF document
        $pdf->Output('Modifier Inventory Movements.pdf', 'I');
        

        //============================================================+
        // END OF FILE
        //============================================================+   
    }
    public function print_mod_inv_movement_excel()
    {
        // $this->load->database('main', TRUE);
        
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Modifier Inventory Movements Report';
        $rc=1;
        #GET VALUES
        // start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
        $this->load->model('dine/setup_model');
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        // update_load(10);
        // sleep(1);
        
        $item_id = $_GET['item_id'];        
        $type = $_GET['type'];  
        
        $daterange = $_GET['daterange'];      
        $dates = explode(" to ",$daterange);
        $from = date2SqlDateTime($dates[0]);
        $to = date2SqlDateTime($dates[1]);
        // $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        // $table  = "menu_moves";
        // $select = "menu_moves.*,menus.menu_name as name,menus.menu_code,trans_types.name as particular";
        // $join["menus"] = array('content'=>"menu_moves.item_id = menus.menu_id");
        // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
        // $args = array();
        // $args2 = array();
        // $args3 = array();
        // if(isset($item_id) && $item_id != "" && $item_id != "undefined"){
        //     $args['menu_moves.item_id'] = $item_id;
        //     $args2['menu_moves.item_id'] = $item_id;
        //     $args3['menu_moves.item_id'] = $item_id;
        // }

        // if(isset($type) && $type != "" && $type != "undefined"){
        //     $args['menu_moves.type_id'] = constant($type);
        //     $args2['menu_moves.type_id'] =  constant($type);
        //     $args3['menu_moves.type_id'] =  constant($type);
        // }

        // if(isset($_GET['daterange']) && $_GET['daterange'] != ""){
        //     $daterange = $_GET['daterange'];
        //     $dates = explode(" to ",$daterange);
        //     $from = date2SqlDateTime($dates[0]);
        //     $to = date2SqlDateTime($dates[1]);
        //     $args["menu_moves.reg_date  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        //     $args2["menu_moves.reg_date  < '".$from."'"] = array('use'=>'where','val'=>null,'third'=>false);
        //     $args3["menu_moves.reg_date  > '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // }
        // $args['menu_moves.inactive'] = 0;
        // $args2['menu_moves.inactive'] = 0;
        // $args3['menu_moves.inactive'] = 0;
        // $order = array('menu_moves.reg_date'=>'asc','menus.menu_name'=>'asc');
        // $group = null;
        // // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        // // $page = paginate('items/get_items',$count,$total_rows,$pagi);
        // $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
        // echo $this->site_model->db->last_query(); die();
        $moves_array = array();
        if($this->session->userData('menu_moves_cart')){
            $moves_array = $this->session->userData('menu_moves_cart');
        }

        $args2 = array();

        // $tablem = "trans_sales_menus";
        // $selectm = "trans_sales_menus.*,menus.menu_name,menus.menu_code,trans_types.name as particular,trans_sales.datetime, trans_sales.trans_ref";
        // $selectm_s = "sum(qty) as menu_sales_qty";
        // $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menus.sales_id");
        // $joinm["menus"] = array('content'=>"menus.menu_id = trans_sales_menus.menu_id");
        // $joinm["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
        // $args3 = array();
        // // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
        // $args3["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args3["trans_sales.type_id"] = 10;
        // $args3["trans_sales.inactive"] = '0';



        if(isset($post['menu-search']) && $post['menu-search'] != ""){
            $args2['menu_moves.item_id'] = $post['menu-search'];
            $args3['trans_sales_menus.menu_id'] = $post['menu-search'];
        }
        // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
            // $daterange = $post['calendar_range'];
            // $dates = explode(" to ",$daterange);
            // $from = date2SqlDateTime($dates[0]);
            // $to = date2SqlDateTime($dates[1]);
            $fromD = date2Sql($dates[0]);
            // $args2["DATE(menu_moves.reg_date)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args3["DATE(trans_sales.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args4["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // }
        // $menu_moves_sum = $this->items_model->get_menu_moves_total(null,$args2);

        // $orderm = null;
        // $groupm = 'trans_sales_menus.menu_id';
        // $menus_sales_total = $this->site_model->get_tbl($tablem,$args3,$orderm,$joinm,true,$selectm_s,$groupm);

        // $before_s_menu = 0;
        // if($menus_sales_total){
        //     $before_s_menu = $menus_sales_total[0]->menu_sales_qty;
        // }
        // $before_m_menu = 0;
        // if($menu_moves_sum){
        //     $before_m_menu = $menu_moves_sum[0]->in_menu_qty;
        // }

        // $before_qty = (int) $before_m_menu - (int) $before_s_menu;
            $tablem = "trans_sales_menu_modifiers";
        $selectm = "trans_sales_menu_modifiers.*,trans_types.name as particular,trans_sales.datetime, trans_sales.trans_ref";
        $selectm_s = "sum(qty) as menu_sales_qty";
        $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menu_modifiers.sales_id");
        // $joinm["menus"] = array('content'=>"menus.menu_id = trans_sales_menus.menu_id");
        $joinm["trans_types"] = array('content'=>"trans_sales.type_id = trans_types.type_id");
        $args3 = array();
        // $join["trans_types"] = array('content'=>"menu_moves.type_id = trans_types.type_id");
        $args3["trans_sales.trans_ref IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args3["trans_sales.type_id"] = 10;
        $args3["trans_sales.inactive"] = '0';


        if(isset($post['menu-search']) && $post['menu-search'] != ""){
            $args2['trans_sales_menu_modifiers.menu_id'] = $post['menu-search'];
            $args3['trans_sales_menu_modifiers.menu_id'] = $post['menu-search'];
        }
        // if(isset($post['calendar_range']) && $post['calendar_range'] != ""){
            // $daterange = $post['calendar_range'];
            // $dates = explode(" to ",$daterange);
            // $from = date2SqlDateTime($dates[0]);
            // $to = date2SqlDateTime($dates[1]);
            $fromD = date2Sql($dates[0]);
            $args2["DATE(trans_sales_menu_modifiers.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            $args3["DATE(trans_sales.datetime)  < '".$fromD."'"] = array('use'=>'where','val'=>null,'third'=>false);
            // $args4["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // }
        // $menu_moves_sum = $this->items_model->get_menu_moves_total(null,$args2);
        $menu_moves_sum = $this->items_model->get_mod_inv_moves_total(null,$args2);

        $orderm = null;
        $groupm = 'trans_sales_menu_modifiers.menu_id';
        $menus_sales_total = $this->site_model->get_tbl($tablem,$args3,$orderm,$joinm,true,$selectm_s,$groupm);

        // $before_s_menu = 0;
        // if($menus_sales_total){
        //     $before_s_menu = $menus_sales_total[0]->menu_sales_qty;
        // }
        // $before_m_menu = 0;
        // if($menu_moves_sum){
        //     $before_m_menu = $menu_moves_sum[0]->in_menu_qty;
        // }

        // $before_qty = (int) $before_m_menu - (int) $before_s_menu;
        
        $before_m_menu = 0;
        if($menu_moves_sum){
            $before_m_menu = $menu_moves_sum[0]->in_menu_qty;
        }

        // $before_qty = (int) $before_m_menu - (int) $before_s_menu;
        $bq=0;
        $bq = (int) $before_m_menu;
        $before_qty =  -1 * abs($bq);

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
                'size' => 12,
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
        $styleCenter = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
        );
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldLeft = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldRight = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldCenter = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        
        $headers = array('Description','Reference #', 'Trans Date','Menu Name','Qty IN','Qty OUT', 'QOH');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        // $sheet->getColumnDimension('H')->setWidth(20);
        // $sheet->getColumnDimension('I')->setWidth(20);
        // $sheet->getColumnDimension('J')->setWidth(20);
        // $sheet->getColumnDimension('K')->setWidth(20);
        // $sheet->getColumnDimension('L')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Modifier Inventory Movement Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;


        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++; 

        $sheet->mergeCells('A'.$rc.':F'.$rc);
        $sheet->getCell('A'.$rc)->setValue("Quantity on Hand Before ".sql2Date($from));
        $sheet->getCell('G'.$rc)->setValue($before_qty);
        $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
        $rc++;               
                
        if(count($moves_array) > 0){
            $ctr = 1;
            $last = count($moves_array);
            $curr = $before_qty;
            foreach ($moves_array as $dt =>$value) {

                foreach ($value as $key => $res) {
                    // if($ctr == 1){

                    //     $c = "";
                    //     if($res->it_per_pack > 0){
                    //         $c = $before_m_item/$res->it_per_pack;
                    //     }
                    //     $sheet->getCell('L'.$rc)->setValue($c);
                    //     $sheet->getStyle('L'.$rc)->applyFromArray($styleNum);
                    //     $rc++;
                    // }

                    $sheet->getCell('A'.$rc)->setValue(ucwords(strtolower($res['particular'])));
                    $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                    $sheet->setCellValueExplicit('B'.$rc, $res['trans_ref'],PHPExcel_Cell_DataType::TYPE_STRING);
                    // $sheet->getCell('B'.$rc)->setValue($res->trans_ref);
                    $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                    $sheet->getCell('C'.$rc)->setValue(sql2Date($res['reg_date'])." ".toTimeW($res['reg_date']));
                    $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
                    $sheet->getCell('D'.$rc)->setValue(ucwords(strtolower($res['name'])));
                    $sheet->getStyle('D'.$rc)->applyFromArray($styleTxt);
                    // $sheet->getCell('E'.$rc)->setValue($res['uom']);
                    // $sheet->getStyle('E'.$rc)->applyFromArray($styleTxt);

                    $in = "";
                    $out = "";
                    // if($res->qty >= 0){
                    //     $in = num($res['qty']);
                    //     $curr += $res['qty'];
                    //     $out = "";
                    // }
                    // else{
                        $out = num($res['qty']);
                        $curr += $res['qty'];
                        $out = $out * -1;
                    // }

                    $sheet->getCell('E'.$rc)->setValue($in);
                    $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                    $sheet->getCell('F'.$rc)->setValue($out);
                    $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                    $sheet->getCell('G'.$rc)->setValue($curr * -1);
                    $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
                    // $sheet->getCell('I'.$rc)->setValue($res->it_per_pack_uom);
                    // $sheet->getStyle('I'.$rc)->applyFromArray($styleTxt);

                    // $this->make->td($res->it_per_pack_uom,array('class'=>'text-center'));
                    // $in = "";
                    // $out = "";
                    // $curr2 = "";
                    // if($res->it_per_pack > 0){
                    //     $curr2 = $curr/$res->it_per_pack;
                    //     if($res->qty >= 0){
                    //         $val = $res->qty/$res->it_per_pack;
                    //         $in = num($val);
                    //     }
                    //     else{
                    //         $val = $res->qty/$res->it_per_pack;
                    //         $out = num($val);
                    //     }
                    // }

                    // $sheet->getCell('J'.$rc)->setValue($in);
                    // $sheet->getStyle('J'.$rc)->applyFromArray($styleNum);
                    // $sheet->getCell('K'.$rc)->setValue($out);
                    // $sheet->getStyle('K'.$rc)->applyFromArray($styleNum);
                    // $sheet->getCell('L'.$rc)->setValue($curr2);
                    // $sheet->getStyle('L'.$rc)->applyFromArray($styleNum);
                    // $this->make->td($in,array('class'=>'text-right'));
                    // $this->make->td($out,array('class'=>'text-right'));
                    // $this->make->td($curr2,array('class'=>'text-right'));

                    $rc++;
                    $ctr++;
                }
            }
        }

        // $sheet->mergeCells('A'.$rc.':G'.$rc);
        // $sheet->getCell('A'.$rc)->setValue("Quantity on Hand After ".sql2Date($to)." ".toTimeW($to));
        // $sheet->getCell('H'.$rc)->setValue($curr + $after_m_item);
        // $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);
        // $c = "";
        // if($res->it_per_pack > 0){
        //     $c = ($curr + $after_m_item)/$res->it_per_pack;
        // } 
        // $sheet->getCell('L'.$rc)->setValue($c);
        // $sheet->getStyle('L'.$rc)->applyFromArray($styleNum);
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
        // $trans_count = count($trans) + 10;
        // foreach ($trans as $val) {
        //     $tot_gross += $val->gross;
        //     $tot_cost += $val->cost;
        //     $tot_margin += $val->gross - $val->cost;
        // }
        // foreach ($trans_mod as $vv) {
        //     $tot_mod_gross += $vv->mod_gross;
        // }

        // foreach ($trans as $k => $v) {
        //     // $pdf->Cell(55, 0, $v->menu_name, '', 0, 'L');        
        //     // $pdf->Cell(38, 0, $v->menu_cat_name, '', 0, 'L');        
        //     // $pdf->Cell(25, 0, num($v->qty), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->vat_sales), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->vat), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->gross), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num(0), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num($v->cost), '', 0, 'R');        
        //     // $pdf->Cell(25, 0, num(0), '', 0, 'R');                    
        //     // $pdf->ln(); 

        //     $sheet->getCell('A'.$rc)->setValue($v->menu_name);
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue($v->menu_cat_name);
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('C'.$rc)->setValue(num($v->qty));
        //     $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        //     // $sheet->getCell('D'.$rc)->setValue(num($v->vat_sales));     
        //     // $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //     // $sheet->getCell('E'.$rc)->setValue(num($v->vat));     
        //     // $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('D'.$rc)->setValue(num($v->gross));     
        //     $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //     if($tot_gross != 0){
        //         $sheet->getCell('E'.$rc)->setValue(num($v->gross / $tot_gross * 100)."%");                     
        //     }else{
        //         $sheet->getCell('E'.$rc)->setValue("0.00%");                                     
        //     }
        //     $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('F'.$rc)->setValue(num($v->cost));     
        //     $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //     if($tot_cost != 0){
        //         $sheet->getCell('G'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
        //     }else{
        //         $sheet->getCell('G'.$rc)->setValue("0.00%");                     
        //     }
        //     $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('H'.$rc)->setValue(num($v->gross - $v->cost));     
        //     $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);               

        //     // Grand Total
        //     $tot_qty += $v->qty;
        //     // $tot_vat_sales += $v->vat_sales;
        //     // $tot_vat += $v->vat;
        //     // $tot_gross += $v->gross;
        //     $tot_sales_prcnt = 0;
        //     // $tot_cost += $v->cost;
        //     $tot_cost_prcnt = 0;

        //     $counter++;
        //     $progress = ($counter / $trans_count) * 100;
        //     update_load(num($progress)); 
        //     $rc++;             
        // }
        // $sheet->getCell('A'.$rc)->setValue('Grand Total');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue("");
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('C'.$rc)->setValue(num($tot_qty));
        // $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
        // // $sheet->getCell('D'.$rc)->setValue(num($tot_vat_sales));     
        // // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        // // $sheet->getCell('E'.$rc)->setValue(num($tot_vat));     
        // // $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('D'.$rc)->setValue(num($tot_gross));     
        // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('E'.$rc)->setValue();     
        // $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('F'.$rc)->setValue(num($tot_cost));     
        // $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('G'.$rc)->setValue();     
        // $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('H'.$rc)->setValue(num($tot_margin));     
        // $sheet->getStyle('H'.$rc)->applyFromArray($styleBoldRight);
        // $rc++; 
        

        // ///////////fpr payments
        // $this->cashier_model->db = $this->load->database('main', TRUE);
        // $args = array();
        // // if($user)
        // //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        // $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        // // if($menu_cat_id != 0){
        // //     $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        // // }


        // $post = $this->set_post();
        // // $curr = $this->search_current();
        // $curr = false;
        // $trans = $this->trans_sales($args,$curr);
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

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('GROSS');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($tot_gross + $tot_mod_gross));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('VAT SALES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($taxable));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('VAT');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($vat_));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('VAT EXEMPT SALES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($nontaxable-$zero_rated));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('ZERO RATED');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($zero_rated));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // // //MENU SUB CAT
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('SUB CATEGORIES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $subcats = $trans_menus['sub_cats'];
        // $total = 0;
        // foreach ($subcats as $id => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        //     $total += $val['amount'];
        // }
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('SubTotal'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($total));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // $rc++;
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('MODIFIERS TOTAL'));
        // $sheet->getCell('B'.$rc)->setValue(num($trans_menus['mods_total']));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('TOTAL'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($total + $trans_menus['mods_total']));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // //DISCOUNTS
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('DISCOUNT');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $types = $trans_discounts['types'];
        // foreach ($types as $code => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        // }

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($discounts));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('VAT EXEMPT'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($less_vat));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // // //CAHRGES
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('CHARGES');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $types = $trans_charges['types'];
        // foreach ($types as $code => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        // }
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($charges));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // // //PAYMENTS
        // $rc++; $rc++;
        // $sheet->getCell('A'.$rc)->setValue('PAYMENT MODE');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue('PAYMENT AMOUNT');
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        // $payments_types = $payments['types'];
        // $payments_total = $payments['total'];
        // foreach ($payments_types as $code => $val) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($code));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // }

        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($payments_total));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // if($trans['total_chit']){
        //     $rc++; $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper('Total Chit'));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        //     $sheet->getCell('B'.$rc)->setValue(num($trans['total_chit']));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
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
        // $rc++;
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('Trans Count'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $tc_total  = 0;
        // $tc_qty = 0;
        // foreach ($types_total as $typ => $tamnt) {
        //     $rc++;
        //     $sheet->getCell('A'.$rc)->setValue(strtoupper($typ));
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //     $sheet->getCell('B'.$rc)->setValue(count($types[$typ]));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('C'.$rc)->setValue(num($tamnt));
        //     $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        //     $tc_total += $tamnt;
        //     $tc_qty += count($types[$typ]);
        // }
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue(strtoupper('TC TOTAL'));
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('C'.$rc)->setValue(num($tc_total));
        // $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);



        update_load(100);        
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
        

        //============================================================+
        // END OF FILE
        //============================================================+   
    }


}