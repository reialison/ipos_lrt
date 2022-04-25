<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {
    public function promos(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $promo_list = $this->settings_model->get_promo_discounts();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Promos';
        // $data['code'] = promo_list_form("settings/promo_form","promo_form","Promo",$promo_list,array('promo_name','promo_code'),'promo_code');

        // $data['add_js'] = 'js/site_list_forms.js';
        // $data['load_js'] = "dine/promos.php";
        // $data['use_js'] = "promosJs";
        // $this->load->view('page',$data);
        $th = array('Promo Code', 'Promo Name' , 'Value','Absolute','Inactive', 'Transaction Type',' ');
        $data['code'] = create_rtable('promo-form','promo_id','promos-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = "dine/promos.php";
        $data['use_js'] = "promosJs";
        // $this->load->view('page',$data);
        // $data['load_js'] = 'site/admin';
        // $data['use_js'] = 'printerJs';
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $this->load->view('page',$data);
    }
    public function get_promos($id=null,$asJson=true){
        $post = array();
        $page = "";
        // die('haha');
        // $joinTables['user_roles'] = array('content'=>'users.role = user_roles.id');
        $joinTables = array();
        $select = '*';
        // $args["user_roles.id != 1"] = array('use'=>'where','val'=>null,'third'=>false);
        $items = $this->site_model->get_tbl('promo_discounts',array(),array('promo_discounts.promo_id'=>'desc'),$joinTables,true,$select);
        // echo "<pre>",print_r($items),"</pre>";die();
        $json = array();
        if(count($items) > 0){
            foreach ($items as $res) {
                $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/promo_details_load/'.$res->promo_id,array('class'=>'btn btn-sm blue btn-outline','return'=>'true')); 
                $json[$res->promo_id] = array(
                    // "id"=>$res->id,   
                    // "role"=>$res->role,     
                    "promo_code"=>$res->promo_code,   
                    "promo_name"=>$res->promo_name,   
                    "value"=>$res->value,   
                    "absolute"=>($res->absolute == 0 ? 'No' : 'Yes'),
                    "status"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    "trans_type"=>$res->trans_type, 
                    "link"=> $link  
                );
            }
        }
        if($asJson){
            // echo json_encode($json);
            echo json_encode(array('rows'=>$json,'page'=>"",'post'=>$post));
        }
        else{
            return array('rows'=>$json,'page'=>"",'post'=>$post);   
            // return $json;
        }
        // echo "<pre>",print_r($json),"</pre>";die();
    }
    public function promo_details_load($promo_id=null){
        $this->load->helper('site/site_forms_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('general_settings');
        $promo = array();
        if($promo_id != null && $promo_id != 'add'){
            $promos = $this->settings_model->get_promo_discounts($promo_id);
            $promo = $promos[0];
        }

        $promo_scheds = array();
        if($promo_id != null && $promo_id != 'add'){
            $promo_scheds = $this->settings_model->get_promo_discount_schedules($promo_id);
        }
        $data['code'] = makePromoDetailsLoad($promo,$promo_id,$promo_scheds);
        $data['load_js'] = 'dine/promos.php';
        $data['use_js'] = 'promoDetailsJs';
        $this->load->view('page',$data);
    }
    public function promo_discount_sched_db(){
        $this->load->model('dine/settings_model');
        $promo_id = $this->input->post('promo_id');
        $day = $this->input->post('day');
        $items = array("time_on"=>date('H:i:s',strtotime($this->input->post('time-on'))  ),
                        "time_off"=>date('H:i:s',strtotime($this->input->post('time-off'))  ),
                        "day"=>$day,
                        "promo_id"=>$promo_id
                );
        $count = $this->settings_model->validate_discount_schedules($promo_id,$day);
        //echo 
        if(count($count) == 0){
            $id = $this->settings_model->add_promo_discount_schedules($items,$promo_id);
            echo json_encode(array('msg'=>'success'));
        }else{
            echo json_encode(array('msg'=>'error'));
        }
    }
    public function remove_promo_details(){
        $this->load->model('dine/settings_model');
        $promo_sched_id = $this->input->post('pr_sched_id');

        $this->settings_model->delete_promo_discount_schedule($promo_sched_id);
        echo json_encode(array('msg'=>'success'));
    }
    public function remove_promo_items(){
        $this->load->model('dine/settings_model');
        $promo_item_id = $this->input->post('pr_item_id');

        $this->settings_model->delete_promo_discount_item($promo_item_id);
        echo json_encode(array('msg'=>'success'));
    }
    public function promo_details_db(){
        $this->load->model('dine/settings_model');
        $promo_id = $this->input->post('promo_id');
        if($promo_id != null){
            $items = array("promo_code"=>$this->input->post('promo_code'),
                            "promo_name"=>$this->input->post('promo_name'),
                            "value"=>$this->input->post('value'),
                            "absolute"=>$this->input->post('absolute'),
                            "inactive"=>$this->input->post('inactive'),
                            "trans_type"=>$this->input->post('trans_type'),
                            // "price"=>$this->input->post('price'),
                    );

            $id = $this->settings_model->update_promo_details($items,$promo_id);
        }else{
            $items = array("promo_code"=>$this->input->post('promo_code'),
                            "promo_name"=>$this->input->post('promo_name'),
                            "value"=>$this->input->post('value'),
                            "absolute"=>$this->input->post('absolute'),
                            "inactive"=>$this->input->post('inactive'),
                            "trans_type"=>$this->input->post('trans_type'),
                            // "price"=>$this->input->post('price'),
                    );

            $id = $this->settings_model->add_promo_details($items);
        }
        echo json_encode(array('msg'=>'success'));
    }
    public function promo_detail_db(){
        $this->load->model('dine/settings_model');
        $items = array("item_id"=>$this->input->post('item'),
                        "promo_id"=>$this->input->post('promo_id')
                );
        $id = $this->settings_model->add_promo_item($items);
    }
    public function assign_load($promo_id){
        $this->load->helper('site/site_forms_helper');
        $this->load->model('dine/settings_model');
        $promo_items = array();
        if($promo_id != null && $promo_id != 'add'){
            $promo_items = $this->settings_model->get_promo_discount_items($promo_id);
        }

        $data['code'] = makeAssignItemsLoad($promo_items,$promo_id);
        $data['load_js'] = 'dine/promos.php';
        $data['use_js'] = 'assignedItemPromoJs';
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $this->load->view('load',$data);
    }
    public function assigned_item_db(){
        $this->load->model('dine/settings_model');
        $itm = $this->input->post('item');
        $promo_id = $this->input->post('promo_id');
        $items = array("item_id"=>$this->input->post('item'),
                        "promo_id"=>$this->input->post('promo_id'),
                        "promo_qty"=>1,
                        "disc_qty"=>1,
                );
        // $id = $this->settings_model->add_promo_item($items);
        // echo json_encode(array('msg'=>'success'));

        $count = $this->settings_model->validate_promo_discount_items($promo_id,$itm);
        if(count($count) == 0){
            $id = $this->settings_model->add_promo_item($items,$promo_id);
            echo json_encode(array('msg'=>'success'));
        }else{
            echo json_encode(array('msg'=>'error'));
        }
    }
    public function uom(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $uom_list = $this->settings_model->get_uom();
        $data = $this->syter->spawn('uom');
        $data['page_title'] = fa('icon-chemistry')." UOM";
        // $data['page_subtitle'] = 'UOM Management';
        // $data['code'] = site_list_form("settings/uom_form","uom_form","UOM",$uom_list,array('name','code'),'code',REMOVE_MASTER_BUTTON);
        // $data['code'] = "";
        // $data['add_js'] = 'js/site_list_forms.js';
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Code','Num','To','Inactive','');
        }else{
            $th = array('ID','Name','Code','Num','To','Inactive');
        }
        $data['code'] = create_rtable('uom','id','uom-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'uomlistFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function uom_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('uom');
        $uom = array();
        if($ref != null){
            $uoms = $this->settings_model->get_uom($ref);
            $uom = $uoms[0];
        }
        $data['code'] = makeUOMForm($uom);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'uomlistFormJs';
        $this->load->view('page',$data);
    }
    public function uom_db(){
        $this->load->model('dine/settings_model');
        $this->load->model('dine/main_model');
        $items = array(
            "name"=>$this->input->post('name'),
            "code"=>$this->input->post('code'),
            "num"=>$this->input->post('num'),
            "to"=>$this->input->post('to'),
        );
        if($this->input->post('id')){
            $this->settings_model->update_uom($items,$this->input->post('code'));
            $id = $this->input->post('code');
            $act = 'update';
            $msg = 'Updated UOM. '.$this->input->post('name');
            $this->main_model->update_tbl('uom','code',$items,$id);
        }else{
            $id = $this->settings_model->add_uom($items);
            $id = $this->input->post('code');
            $act = 'add';
            $msg = 'Added  new UOM'.$this->input->post('name');
            $this->main_model->add_trans_tbl('uom',$items);
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name')." ".$this->input->post('code'),"act"=>$act,'msg'=>$msg));
    }
    public function discounts(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $discount_list = $this->settings_model->get_receipt_discount();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Discounts';
        $data['code'] = site_list_form("settings/discount_form","discount_form","Discounts",$discount_list,array('disc_name','disc_code'),'disc_id');
        // $data['code'] = "";
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function discount_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $disc = array();
        if($ref != null){
            $discounts = $this->settings_model->get_receipt_discount($ref);
            $disc = $discounts[0];
        }
        $data['code'] = makeDiscountForm($disc);
        $this->load->view('load',$data);
    }
    public function discount_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "disc_name"=>$this->input->post('name'),
            "disc_rate"=>$this->input->post('rate'),
            "disc_code"=>$this->input->post('code'),
        );
        if($this->input->post('disc_id')){
            $this->settings_model->update_receipt_discount($items, $this->input->post('disc_id'));
            $id = $this->input->post('disc_id');
            $act = 'update';
            $msg = 'Updated Discount: '.$this->input->post('name');
        }else{
            $id = $this->settings_model->add_receipt_discount($items);
            $act = 'add';
            $msg = 'Added New Discount: '.$this->input->post('name');
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name')." ".$this->input->post('code'),"act"=>$act,'msg'=>$msg));
    }
    public function categories(){
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('general_settings');
        $data['page_title'] = fa('icon-social-dropbox')." Item Categories";
        // $data['page_subtitle'] = 'Item Category Management';
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','');
        }else{
            $th = array('ID','Name');
        }
        $data['code'] = create_rtable('categories','cat_id','categories-tbl',$th,'settings/search_categories_form',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'categoryListFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function get_categories($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 1000;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(categories.name like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['categories.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('categories',$args,array(),$join,true,'categories.*',null,null,true);
        $page = paginate('settings/get_categories',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('categories',$args,array(),$join,true,'categories.*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                if(MASTER_BUTTON_EDIT){
                    $link = $this->make->A(fa('fa-edit fa-lg').' Edit','#',array('class'=>'btn blue btn-sm btn-outline edit','id'=>'edit-'.$res->cat_id,'ref'=>$res->cat_id,'return'=>'true'));
                    $json[$res->cat_id] = array(
                        "id"=>$res->cat_id,   
                        "title"=>"[".$res->code."] ".ucwords(strtolower($res->name)),   
                        "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                );
                }else{
                    $json[$res->cat_id] = array(
                    "id"=>$res->cat_id,   
                    "title"=>"[".$res->code."] ".ucwords(strtolower($res->name)),   
                    "inactive"=>($res->inactive == 0 ? 'No' : 'Yes')
                );

                }
                $ids[] = $res->cat_id;
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function search_categories_form(){
        $this->load->helper('dine/settings_helper');
        $data['code'] = itemCatSearchForm();
        $this->load->view('load',$data);
    }
    public function category_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $category = array();
        if($ref != null){
            $categories = $this->settings_model->get_category($ref);
            $category = $categories[0];
        }
        $data['code'] = makeCategoryForm($category);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'itemCatJS';
        $this->load->view('load',$data);
    }
    public function category_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "name"=>$this->input->post('name'),
            "code"=>$this->input->post('code'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('cat_id')){
            $this->settings_model->update_category($items, $this->input->post('cat_id'));
            $id = $this->input->post('cat_id');
            $act = 'update';
            $msg = 'Updated Category: '.$this->input->post('name');
        }else{
            $id = $this->settings_model->add_category($items);
            $act = 'add';
            $msg = 'Added New Category: '.$this->input->post('name');
		}
        echo json_encode(array("id"=>$id,"addOpt"=>"[".$items['code']."] ".$items['name'],"desc"=>$this->input->post('name')." ".$this->input->post('code'),"act"=>$act,'msg'=>$msg));
    }
    public function subcategories(){
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('general_settings');
        $data['page_title'] = fa(' icon-social-dropbox')." Item Subcategories";
        // $data['page_subtitle'] = 'Item Subcategory Management';
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','');
        }else{
            $th = array('ID','Name');
        }
        $data['code'] = create_rtable('subcategories','sub_cat_id','subcategories-tbl',$th,'settings/search_subcategories_form',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'subcategoryListFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function get_subcategories($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 1000;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(subcategories.name like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['subcategories.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('subcategories',$args,array(),$join,true,'subcategories.*',null,null,true);
        $page = paginate('settings/get_categories',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('subcategories',$args,array(),$join,true,'subcategories.*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                foreach ($items as $res) {
                    $link = $this->make->A(fa('fa-edit fa-lg').' Edit','#',array('class'=>'btn blue btn-sm btn-outline edit','id'=>'edit-'.$res->sub_cat_id,'ref'=>$res->sub_cat_id,'return'=>'true'));
                    $json[$res->sub_cat_id] = array(
                        "id"=>$res->sub_cat_id,   
                        "title"=>"[".$res->code."] ".ucwords(strtolower($res->name)),   
                        "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                    $ids[] = $res->sub_cat_id;
                }
            }else{
                foreach ($items as $res) {
                    $json[$res->sub_cat_id] = array(
                        "id"=>$res->sub_cat_id,   
                        "title"=>"[".$res->code."] ".ucwords(strtolower($res->name)),   
                        "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                    $ids[] = $res->sub_cat_id;
                }

            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function search_subcategories_form(){
        $this->load->helper('dine/settings_helper');
        $data['code'] = itemSubCatSearchForm();
        $this->load->view('load',$data);
    }
    public function subcategory_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $subcategory = array();
        if($ref != null){
            $subcategories = $this->settings_model->get_subcategory($ref);
            $subcategory = $subcategories[0];
        }
        $data['code'] = makeSubCategoryForm($subcategory);
        $this->load->view('load',$data);
    }
    public function subcategory_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "cat_id"=>$this->input->post('cat_id'),
            "name"=>$this->input->post('name'),
            "code"=>$this->input->post('code'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('sub_cat_id')){
            $this->settings_model->update_subcategory($items, $this->input->post('sub_cat_id'));
            $id = $this->input->post('sub_cat_id');
            $act = 'update';
            $msg = 'Updated Sub Category: '.$this->input->post('name');
        }else{
            $id = $this->settings_model->add_subcategory($items);
            $act = 'add';
            $msg = 'Added New Sub Category: '.$this->input->post('name');
		}
        echo json_encode(array("id"=>$id,"addOpt"=>"[".$items['code']."] ".$items['name'],"desc"=>$this->input->post('name')." ".$this->input->post('code'),"act"=>$act,'msg'=>$msg));
    }
    public function suppliers2(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('menu');
        $data['page_title'] = fa('icon-globe')." Suppliers";
        // $menus = $this->menu_model->get_menus();
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Code','Name','');
        }else{
            $th = array('ID','Code','Name');
        }
        $data['code'] = create_rtable('suppliers','suppliers','suppliers-tbl',$th,'settings/search_supplier_form',false,'list',REMOVE_MASTER_BUTTON);

        // $data['code'] = menuListPage($menus);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'supplierListFormJs';
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $this->load->view('page',$data);
    }
    public function search_supplier_form(){
        $this->load->helper('dine/settings_helper');
        $data['code'] = suppSearchForm();
        $this->load->view('load',$data);
    }
    public function get_supplier($id=null,$asJson=true,$resultOnly=false){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 30;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(suppliers.name like '%".$lk."%' OR suppliers.supplier_code like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['suppliers.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        // else{
        //     $args['suppliers.inactive'] = 0;
        // }
        $count = $this->site_model->get_tbl('suppliers',$args,array(),null,true,'suppliers.*',null,null,true);
        $page = paginate('menu/get_menus',$count,$total_rows,$pagi);
        if(!$resultOnly)
            $items = $this->site_model->get_tbl('suppliers',$args,array(),null,true,'suppliers.*',null,$page['limit']);
        else{
            $items = $this->site_model->get_tbl('suppliers',$args,array(),null,true,'suppliers.*',null);
            return $items;
        }
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                foreach ($items as $res) {
                    $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/supplier_form2/'.$res->supplier_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));
                    $json[$res->supplier_id] = array(
                        "id"=>$res->supplier_id,   
                        "code"=>$res->supplier_code,   
                        "desc"=>ucwords(strtolower($res->name)),   
                        "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                    $ids[] = $res->supplier_id;
                }
            }else{
                foreach ($items as $res) {
                    // $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/supplier_form2/'.$res->supplier_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));
                    $json[$res->supplier_id] = array(
                        "id"=>$res->supplier_id,   
                        "code"=>$res->supplier_code,   
                        "desc"=>ucwords(strtolower($res->name)),   
                        "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        // "link"=>$link
                    );
                    $ids[] = $res->supplier_id;
                }
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function supplier_form2($ref=null){
        $this->load->model('dine/settings_model');
        $this->load->helper('dine/settings_helper');
        $data = $this->syter->spawn('menu');
        $supplier = array();
        if($ref != null){
            $suppliers = $this->settings_model->get_supplier($ref);
            $supplier = $suppliers[0];
        }
        $data['code'] = makeSupplierForm($supplier);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'supplierListFormJs';
        // $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
	public function suppliers(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $supplier_list = $this->settings_model->get_supplier();
        $data = $this->syter->spawn('general_settings');
        $data['page_title'] = fa('icon-globe')." Suppliers";
        // $data['page_subtitle'] = 'Supplier Management';
        $data['code'] = site_list_form("settings/supplier_form","supplier_form","Suppliers",$supplier_list,'name','supplier_id',true);
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function supplier_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $supplier = array();
        if($ref != null){
            $suppliers = $this->settings_model->get_supplier($ref);
            $supplier = $suppliers[0];
        }
        $data['code'] = makeSupplierForm($supplier);
        $this->load->view('load',$data);
    }
    public function supplier_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "name"=>$this->input->post('name'),
            "address"=>$this->input->post('address'),
            "supplier_code"=>$this->input->post('code'),
            "contact_no"=>$this->input->post('contact_no'),
            "memo"=>$this->input->post('memo'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('supplier_id')){
            $this->settings_model->update_supplier($items, $this->input->post('supplier_id'));
            $id = $this->input->post('supplier_id');
            $act = 'update';
            $msg = 'Updated Supplier: '.$this->input->post('name');
        }else{
            $id = $this->settings_model->add_supplier($items);
            $act = 'add';
            $msg = 'Added New Supplier: '.$this->input->post('name');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name')." ".$this->input->post('code'),"act"=>$act,'msg'=>$msg));
    }
	public function tax_rates(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $tax_rates_list = $this->settings_model->get_tax_rates();
        $data = $this->syter->spawn('tax');
        $data['page_title'] = fa('icon-doc')." Tax Rates";
        // $data['page_subtitle'] = 'Tax Rate Management';
        // $data['code'] = site_list_form("settings/tax_rate_form","tax_rate_form","Tax Rates",$tax_rates_list,'name','tax_id',REMOVE_MASTER_BUTTON);
        // $data['add_js'] = 'js/site_list_forms.js';
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Rate','Inactive','');
        }else{
            $th = array('ID','Name','Rate','Inactive');
        }
        $data['code'] = create_rtable('tax_rates','tax_id','tax_rate-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'taxlistFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function tax_rate_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
         $data = $this->syter->spawn('tax');
        $tax_rate = array();
        if($ref != null){
            $tax_rates = $this->settings_model->get_tax_rates($ref);
            $tax_rate = $tax_rates[0];
        }
        $data['code'] = makeTaxRateForm($tax_rate);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'taxlistFormJs';
        $this->load->view('page',$data);
    }
    public function tax_rate_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "name"=>$this->input->post('name'),
            "rate"=>$this->input->post('rate'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('tax_id')){
            $this->settings_model->update_tax_rates($items, $this->input->post('tax_id'));
            $id = $this->input->post('tax_id');
            $act = 'update';
            $msg = 'Updated Tax Rate: '.$this->input->post('name');
        }else{
            $id = $this->settings_model->add_tax_rates($items);
            $act = 'add';
            $msg = 'Added New Tax Rate: '.$this->input->post('name');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
    public function receipt_discounts()
    {
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('receipt_discount');
        // $receipt_discounts = $this->settings_model->get_receipt_discounts();
        // $data = $this->syter->spawn('general_settings');
        // $data['page_title'] = fa('icon-doc')." Receipt Discounts";
        // // $data['page_subtitle'] = 'Receipt Discounts Management';
        // $data['code'] = site_list_form(
        //                     "settings/receipt_discount_form"
        //                     , "receipt_discount_form"
        //                     , "Receipt Discounts"
        //                     , $receipt_discounts
        //                     , 'disc_name'
        //                     , 'disc_id'
        //                     ,REMOVE_MASTER_BUTTON);
        $data['add_js'] = 'js/site_list_forms.js';

        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Code','Rate','Absolute','Inactive','');
        }else{
            $th = array('ID','Name','Code','Rate','Absolute','Inactive');
        }
        $data['code'] = create_rtable('receipt_discounts','disc_id','discount-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'receiptlistFormJs';
        $data['page_no_padding'] = true;

        $this->load->view('page',$data);
    }
    public function search_rdiscount_form(){
        $this->load->helper('dine/settings_helper');
        $data['code'] = RdiscountSearchForm();
        $this->load->view('load',$data);
    }
    public function receipt_discount_form($ref=null){
        // echo $ref;
        $data = $this->syter->spawn('receipt_discount');
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $receipt_discount = array();
        if($ref != null){
            $receipt_discount = $this->settings_model->get_receipt_discounts($ref);
            $receipt_discount = $receipt_discount[0];
        }
        $data['code'] = makeReceiptDiscountForm($receipt_discount);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'receiptlistFormJs';
        $this->load->view('page',$data);
    }
    public function receipt_discount_db()
    {
        $this->load->model('dine/settings_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $items = array(
            "disc_code"=>$this->input->post('disc_code'),
            "disc_name"=>$this->input->post('disc_name'),
            "disc_rate"=>$this->input->post('disc_rate'),
            "no_tax"=>(int)$this->input->post('no_tax'),
            "fix"=>(int)$this->input->post('fix'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('disc_id')){
            $this->settings_model->update_receipt_discount($items, $this->input->post('disc_id'));
            $id = $this->input->post('disc_id');
            $act = 'update';
            $msg = 'Updated Receipt Discount: '.$items['disc_name'];
            $this->main_model->update_tbl('receipt_discounts','disc_id',$items,$id);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){

                    //check if meron
                    $where = array('disc_id'=>$this->input->post('disc_id'));
                    $chk = $this->manager_model->get_details($where,'receipt_discounts',$vals->terminal_code);

                    if(count($chk) > 0){
                        $this->settings_model->update_receipt_discount($items, $this->input->post('disc_id'),$vals->terminal_code);
                    }else{
                        $items['disc_id'] = $this->input->post('disc_id');
                        $this->settings_model->add_receipt_discount($items,$vals->terminal_code);
                    }

                }

            }

        }else{
            $dets = $this->manager_model->get_last_ids('disc_id','receipt_discounts');
            if($dets){
                $id = $dets[0]->disc_id + 1;
            }else{
                $id = 1;  
            }
            $items['disc_id'] = $id;

            $this->settings_model->add_receipt_discount($items);
            $act = 'add';
            $msg = 'Added New Receipt Discount: '.$items['disc_name'];
            $this->main_model->add_trans_tbl('receipt_discounts',$items);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    $this->settings_model->add_receipt_discount($items,$vals->terminal_code);
                }

            }


        }
        echo json_encode(array("id"=>$id,"desc"=>$items['disc_name'],"act"=>$act,'msg'=>$msg));
    }
    public function seat_management(){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('general_settings');
        
        $data['page_title'] = fa('icon-equalizer').' Table Management';
        $branches = $this->settings_model->get_table_layout(1);
        $branch = $branches[0];
        // if($branch_id != null && $branch_id != 'add'){
        //     // $staffs = $this->branches_model->get_restaurant_branch_staffs(null,$branch_id);
        // }
        $data['code'] = makeTablesPage($branch);
        $data['add_css'] = 'css/rtag.css';
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] =  'seatingJs';
        $data['sideBarHide'] = true;
        $this->load->view('page',$data);
    }
    //nicko 04062020
    public function create_seat(){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('general_settings');
        
        $data['page_title'] = fa('icon-equalizer').' Create Table Image';
        $branches = $this->settings_model->get_table_layout(1);
        $branch = $branches[0];
        // if($branch_id != null && $branch_id != 'add'){
        //     // $staffs = $this->branches_model->get_restaurant_branch_staffs(null,$branch_id);
        // }
        $data['code'] = maketableImg($branch);
        $data['add_css'] = 'css/rtag.css';
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] =  'seatingJs';
        $data['sideBarHide'] = true;
        $this->load->view('page',$data);
    }
    public function upload_image_seat_form(){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');

        $branches = $this->settings_model->get_table_layout(1);
        $branch = $branches[0];

        $data['code'] = makeTableUploadForm($branch);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'uploadImageSeatJs';

        $this->load->view('load',$data);
    }
    public function upload_image_seat_db(){
        $this->load->model('dine/settings_model');
        $image = null;
        $upload = 'success';
        $msg = "";
        $src ="";

        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
            $info = pathinfo($_FILES['fileUpload']['name']);
            $ext = $info['extension'];
            $branch_id = $this->input->post('branch_id');
            $res_id = $this->input->post('res_id');
            $newname = "layout.".$ext;
            $target = "uploads/".$newname;
            if(move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
                $items = array(
                    'image'=>$newname
                );
                $this->settings_model->update_table_layout($items,1);
                $this->settings_model->delete_all_tables();
                // $id = 1;
                $msg = "Image Uploaded";
                $src = $target;
                site_alert($msg,'success');
            }
            else{
                $mg = "Something went wrong.";
                $upload = "fail";
                site_alert($msg,'error');
            }
        }
        echo json_encode(array("msg"=>$msg,"src"=>$src) );
    }
    public function get_tables($asJson=true){
        $this->load->model('dine/settings_model');
        $tables=array();
        $table_list = $this->settings_model->get_tables();
        foreach ($table_list as $res) {
            if($res->trans_type == "dinein"){
                $tables[$res->tbl_id] = array(
                    "name"=> $res->name,
                    "top"=> $res->top,
                    "left"=> $res->left
                );
            }
        }
        if($asJson)
            echo json_encode($tables);
        else
            return $tables;
    }
    public function get_tables_other($type,$asJson=true){
        $this->load->model('dine/settings_model');
        $tables=array();
        $table_list = $this->settings_model->get_tables_other(null,$type);
        foreach ($table_list as $res) {
            $tables[$res->tbl_id] = array(
                "name"=> $res->name,
                "top"=> $res->top,
                "left"=> $res->left
            );
        }
        if($asJson)
            echo json_encode($tables);
        else
            return $tables;
    }
    public function tables_form($tbl_id=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $table = array();
        if($tbl_id != null ){
            $tables = $this->settings_model->get_tables($tbl_id);
            $table = $tables[0];
        }
        $data['code'] = makeTableForm($table);
        // $data['load_js'] = 'resto/branches.php';
        // $data['use_js'] = 'branchesTableJs';
        $this->load->view('load',$data);
    }
    public function tables_update_pos(){
        $this->load->model('dine/settings_model');
        $items = array(
            "top"=>$this->input->post('top'),
            "left"=>$this->input->post('left')
        );
        $this->settings_model->update_tables($items,$this->input->post('tbl_id'));
        $id = $this->input->post('tbl_id');
        $act = 'update';
        $msg = 'Updated position';
        echo json_encode(array("id"=>$id,"desc"=>"","act"=>$act,'msg'=>$msg));
    }    
    public function tables_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "capacity"=>$this->input->post('capacity'),
            "name"=>$this->input->post('name'),
            "top"=>$this->input->post('top'),
            "left"=>$this->input->post('left'),
            "trans_type"=>'dinein'
        );
        if($this->input->post('delete')){
            $id = $this->input->post('delete');
            $this->settings_model->delete_tables($id);
            $msg = 'Deleted '.$this->input->post('name');
            $act = 'delete';
        }
        else{
            if($this->input->post('tbl_id')){
                $this->settings_model->update_tables($items,$this->input->post('tbl_id'));
                $id = $this->input->post('tbl_id');
                $act = 'update';
                $msg = 'Updated '.$this->input->post('name');
            }else{
                $id = $this->settings_model->add_tables($items);
                $act = 'add';
                $msg = 'Added '.$this->input->post('name');
            }
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
	public function terminals(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $terminal_list = $this->settings_model->get_terminal();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Terminal Management';
        $data['code'] = site_list_form("settings/terminal_form","terminal_form","Terminals",$terminal_list,array('terminal_name','terminal_code'),'terminal_id');
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function terminal_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $terminal = array();
        if($ref != null){
            $terminals = $this->settings_model->get_terminal($ref);
            $terminal = $terminals[0];
        }
        $data['code'] = makeTerminalForm($terminal);
        $this->load->view('load',$data);
    }
    public function terminal_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "terminal_code"=>$this->input->post('terminal_code'),
            "terminal_name"=>$this->input->post('terminal_name'),
            "ip"=>$this->input->post('ip'),
            "comp_name"=>$this->input->post('comp_name'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('terminal_id')){
            $this->settings_model->update_terminal($items, $this->input->post('terminal_id'));
            $id = $this->input->post('terminal_id');
            $act = 'update';
            $msg = 'Updated Terminal: '.$this->input->post('terminal_name');
        }else{
            $id = $this->settings_model->add_terminal($items);
            $act = 'add';
            $msg = 'Added New Terminal: '.$this->input->post('terminal_name');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('terminal_name')." ".$this->input->post('terminal_code'),"act"=>$act,'msg'=>$msg));
    }
	public function currencies(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $currency_list = $this->settings_model->get_currency();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Currency Management';
        $data['code'] = site_list_form("settings/currency_form","currency_form","Currencies",$currency_list,array('currency_desc','currency'),'id');
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function currency_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $currency = array();
        if($ref != null){
            $currencies = $this->settings_model->get_currency($ref);
            $currency = $currencies[0];
        }
        $data['code'] = makeCurrencyForm($currency);
        $this->load->view('load',$data);
    }
    public function currency_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "currency"=>$this->input->post('currency'),
            "currency_desc"=>$this->input->post('currency_desc'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('id')){
            $this->settings_model->update_currency($items, $this->input->post('id'));
            $id = $this->input->post('id');
            $act = 'update';
            $msg = 'Updated Currency: '.$this->input->post('currency_desc');
        }else{
            $id = $this->settings_model->add_currency($items);
            $act = 'add';
            $msg = 'Added New Currency: '.$this->input->post('currency_desc');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('currency_desc')." ".$this->input->post('currency'),"act"=>$act,'msg'=>$msg));
    }
	public function references(){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $details = $this->settings_model->get_references();
		// $det = $details[0];
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'References';
        // $data['code'] = makeReferencesForm($det);
        $data['code'] = makeReferencesForm($details);
		// $data['code'] = site_list_form("settings/currency_form","currency_form","Currencies",$currency_list,array('currency_desc','currency'),'id');
		$data['load_js'] = 'dine/setup.php';
		$data['use_js'] = 'referencesJs';
        $this->load->view('page',$data);
    }
    public function references_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "next_ref"=>$this->input->post('next_ref')
        );

            $this->settings_model->update_references($items, $this->input->post('type_id'));
            // $id = $this->input->post('cat_id');
            $act = 'update';
            $msg = 'Updated Reference : '.$this->input->post('name');

        echo json_encode(array('msg'=>$msg));
    }
	public function locations(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $location_list = $this->settings_model->get_location();
        $data = $this->syter->spawn('location');
        $data['page_title'] = fa('icon-home')." Item Locations";
        // $data['page_subtitle'] = 'Location Management';
        // $data['code'] = site_list_form("settings/category_form","category_form","Locations",$location_list,array('name','code'),'code');
        // $data['code'] = site_list_form("settings/location_form","location_form","Locations",$location_list,array('loc_name','loc_code'),'loc_id',FALSE);
        // $data['code'] = "";
        // $data['add_js'] = 'js/site_list_forms.js';
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Code','Inactive','');
        }else{
            $th = array('ID','Name','Code','Inactive');
        }
        $data['code'] = create_rtable('locations','loc_id','location-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'locationlistFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function location_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('location');
        $location = array();
        if($ref != null){
            $locations = $this->settings_model->get_location($ref);
            $location = $locations[0];
        }
        $data['code'] = makeLocationForm($location);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'locationlistFormJs';
        $this->load->view('page',$data);
    }
    public function location_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "loc_name"=>$this->input->post('loc_name'),
            "loc_code"=>$this->input->post('loc_code'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('loc_id')){
            $this->settings_model->update_location($items, $this->input->post('loc_id'));
            $id = $this->input->post('loc_id');
            $act = 'update';
            $msg = 'Updated Location: '.$this->input->post('loc_name');
        }else{
            $id = $this->settings_model->add_location($items);
            $act = 'add';
            $msg = 'Added New Location: '.$this->input->post('loc_name');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('loc_name')." ".$this->input->post('loc_code'),"act"=>$act,'msg'=>$msg));
    }
    public function remove_item_assign(){
        $this->load->model('dine/settings_model');

        $ref = $this->input->post('ref');
        $del_item = $this->settings_model->delete_promo_item($ref);
    }
    public function update_item_assign(){
        $this->load->model('dine/settings_model');

        $ref = $this->input->post('ref');
         $items = array(
            "promo_qty"=>$this->input->post('promo_qty'),
            "disc_qty"=>$this->input->post('disc_qty'),
        );

         $this->settings_model->update_promo_item($items,$ref);
    }
    public function remove_schedule(){
        $this->load->model('dine/settings_model');

        $ref = $this->input->post('ref');
        $del_sched = $this->settings_model->delete_promo_schedule($ref);
    }
    public function denomination(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $deno_list = $this->settings_model->get_denomination();
        $data = $this->syter->spawn('denomination');
        $data['page_title'] = fa('icon-calculator').' Denominations';
        // $data['code'] = site_list_form("settings/category_form","category_form","Locations",$location_list,array('name','code'),'code');
        // $data['code'] = site_list_form("settings/denomination_form","denomination_form","Denominations",$deno_list,array('desc'),'id',REMOVE_MASTER_BUTTON);
        // $data['code'] = "";
        // $data['add_js'] = 'js/site_list_forms.js';
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Value','');
        }else{
            $th = array('ID','Name','Value');
        }
        $data['code'] = create_rtable('denominations','id','denomination-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'denominationlistFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function denomination_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('denomination');
        $deno = array();
        if($ref != null){
            $denomination = $this->settings_model->get_denomination($ref);
            $deno = $denomination[0];
        }
        $data['code'] = makeDenominationForm($deno);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'denominationlistFormJs';
        $this->load->view('page',$data);
    }
    public function denomination_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "desc"=>$this->input->post('desc'),
            "value"=>$this->input->post('value'),
            "img"=>null
        );
        if($this->input->post('id')){
            $this->settings_model->update_denomination($items, $this->input->post('id'));
            $id = $this->input->post('id');
            $act = 'update';
            $msg = 'Updated Denomination: '.$this->input->post('desc');
        }else{
            $id = $this->settings_model->add_denomination($items);
            $act = 'add';
            $msg = 'Added New Denomination: '.$this->input->post('desc');
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('desc'),"act"=>$act,'msg'=>$msg));
    }

    public function seat_management_other(){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('general_settings');
        
        $data['page_title'] = fa('icon-equalizer').' Other Table Management';
        $branches = $this->settings_model->get_table_layout(1);
        $branch = $branches[0];
        // if($branch_id != null && $branch_id != 'add'){
        //     // $staffs = $this->branches_model->get_restaurant_branch_staffs(null,$branch_id);
        // }
        $data['code'] = makeTablesOtherPage($branch);
        $data['add_css'] = 'css/rtag.css';
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] =  'seatingOtherJs';
        $data['sideBarHide'] = true;
        $this->load->view('page',$data);
    }

    public function save_table_buttons(){
        $this->load->model('dine/settings_model');

        $type = $this->input->post('type');
        // $from = $this->input->post('from');
        $to = $this->input->post('to');
        $prefix = $this->input->post('prefix');

        $updates = array('inactive'=>1);
        $this->settings_model->update_tables_other($updates,'trans_type',$type);
        $distance = 90;
        $line = 1;
        $row = 1;
        for($i=1; $i <=$to; $i++){
            if($i < 10){
                $items[] = array(
                    "capacity"=>1,
                    "name"=>$prefix.$i,
                    "top"=>(($line * $distance) - $distance) + 30,
                    "left"=>(($row * $distance) - $distance) + 30,
                    "trans_type"=>$type
                );
                $row++;
                if($i == 10){
                    $row = 1;
                    $line = 2;
                }
            }else{
                 
                if ($i % 10 == 0) {
                    $items[] = array(
                        "capacity"=>1,
                        "name"=>$prefix.$i,
                        "top"=>(($line * $distance) - $distance) + 30,
                        "left"=>(($row * $distance) - $distance) + 30,
                        "trans_type"=>$type
                    );
                    $line++;
                    $row = 1;
                }else{
                    $items[] = array(
                        "capacity"=>1,
                        "name"=>$prefix.$i,
                        "top"=>(($line * $distance) - $distance) + 30,
                        "left"=>(($row * $distance) - $distance) + 30,
                        "trans_type"=>$type
                    );
                    $row++;
                }
            }

        }

        // echo "<pre>",print_r($items),"</pre>";die();


        // $items = array(
        //     "capacity"=>$this->input->post('capacity'),
        //     "name"=>$this->input->post('name'),
        //     "top"=>$this->input->post('top'),
        //     "left"=>$this->input->post('left')
        // );
        // if($this->input->post('delete')){
        //     $id = $this->input->post('delete');
        //     $this->settings_model->delete_tables($id);
        //     $msg = 'Deleted '.$this->input->post('name');
        //     $act = 'delete';
        // }
        // else{
            // if($this->input->post('tbl_id')){
            //     $this->settings_model->update_tables($items,$this->input->post('tbl_id'));
            //     $id = $this->input->post('tbl_id');
            //     $act = 'update';
            //     $msg = 'Updated '.$this->input->post('name');
            // }else{
                $id = $this->settings_model->add_tables_other_batch($items);
                // $act = 'add';
                // $msg = 'Added '.$this->input->post('name');
            // }
        // }
        $msg = 'Buttons has been generated';
        echo json_encode(array('msg'=>$msg));
    }

    public function trans_type(){
        $this->load->helper('site/site_forms_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('trans_type');
        $list = $this->settings_model->get_transaction_type();
        $data['page_title'] = fa('icon-equalizer')." Transaction Types";
        // $data['code'] = site_list_form("settings/trans_form","transform","Transactions",$list,array('trans_name'),'trans_id',false);
        // echo 'asdfsadf'; die();
        // $data['add_js'] = 'js/site_list_forms.js';
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Inactive','');
        }else{
            $th = array('ID','Name','Inactive');
        }
        $data['code'] = create_rtable('transaction_types','trans_id','tran-type-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'transtypelistFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }

    public function trans_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('trans_type');

        $data['page_title'] = fa('icon-book-open')." Add New Transaction Type";
        // if($ref != null){
        //     $result = $this->site_model->get_tbl('modifiers',array('mod_id'=>$mod_id));
        //     $mod = $result[0];
        //     $data['page_title'] = fa('icon-book-open').' '.$mod->name;
        // }


        $item = array();
        if($ref != null){
            $items = $this->settings_model->get_transaction_type($ref);
            $item = $items[0];
            $data['page_title'] = fa('icon-book-open').' '.$item->trans_name;
        }

        $data['code'] = transTypeFormPage($ref);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'transtypeFormJs';
        // $data['use_js'] = 'menuPricesJs';
        $this->load->view('page',$data);
    }

    public function transtype_details_load($trans_id=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $item=array();
        if($trans_id != null){
            $items = $this->settings_model->get_transaction_type($trans_id);
            $item = $items[0];
        }
        $data['code'] = makeTransForm($item,$trans_id);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'transtypelistFormJs';
        $this->load->view('load',$data);
    }

    public function transaction_type_db(){
        $this->load->model('dine/settings_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $items = array(
            "trans_name"=>$this->input->post('trans_name'),
            "inactive"=>$this->input->post('inactive'),
        );
        if($this->input->post('trans_id')){
            $this->settings_model->update_trans_type($items,$this->input->post('trans_id'));
            $id = $this->input->post('trans_id');
            $act = 'update';
            $msg = 'Updated Payment Type. '.$this->input->post('trans_name');
            $this->main_model->update_tbl('transaction_types','trans_id',$items,$id);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){

                    //check if meron
                    $where = array('trans_id'=>$this->input->post('trans_id'));
                    $chk = $this->manager_model->get_details($where,'transaction_types',$vals->terminal_code);

                    if(count($chk) > 0){
                        $this->settings_model->update_trans_type($items,$this->input->post('trans_id'),$vals->terminal_code);
                    }else{
                        $items['trans_id'] = $this->input->post('trans_id');
                        $this->settings_model->add_trans_type($items,$vals->terminal_code);
                    }

                }

            }

        }else{
            $dets = $this->manager_model->get_last_ids('trans_id','transaction_types');
            if($dets){
                $id = $dets[0]->trans_id + 1;
            }else{
                $id = 1;  
            }
            $items['trans_id'] = $id;

            $this->settings_model->add_trans_type($items);
            // $id = $this->input->post('trans_id');
            $act = 'add';
            $msg = 'Added  new Payment Type'.$this->input->post('trans_name');
            $this->main_model->add_trans_tbl('transaction_types',$items);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    $this->settings_model->add_trans_type($items,$vals->terminal_code);
                }

            }
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('trans_name'),"act"=>$act,'msg'=>$msg));
    }

    public function trans_type_categories($trans_id){
    // public function mod_sub_price_load(){        
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');

        // $mod_sub_id = $this->input->post('mod_sub_id');
        // $mod_sub_name = $this->input->post('mod_sub_name');
        $trans_name = '';
        $det_trans = $this->settings_model->get_transaction_type($trans_id);

        if($det_trans){
            $trans_name = $det_trans[0]->trans_name;
        }
        $det = $this->settings_model->get_trans_categories($trans_id);
        $data['code'] = transCategoriesLoad($trans_id,$trans_name,$det);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'transCatJs';

        $this->load->view('load',$data);
        
    }

    public function trans_type_categories_db()
    {
        $this->load->model('dine/settings_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $this->load->model('site/site_model');

        if (!$this->input->post())
            header('Location:'.base_url().'settings/trans_type');

        $items = array(
            'menu_cat_id' => $this->input->post('menu_cat_id'),
            'trans_id' => $this->input->post('trans_id_hid'),
            // 'price' => $this->input->post('price'),
        );

        $det = $this->settings_model->get_trans_categories($items['trans_id'],$items['menu_cat_id']);

        // echo 'asdfasdf'; die();

        if (count($det) == 0) {
            $dets = $this->manager_model->get_last_ids('id','transaction_type_categories');
            if($dets){
                $id = $dets[0]->id + 1;
            }else{
                $id = 1;
            }
            $items['id'] = $id;

            $this->settings_model->add_trans_cat($items);

            // $items['id'] = $id;
            $this->main_model->add_trans_tbl('transaction_type_categories',$items);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    $this->settings_model->add_trans_cat($items,$vals->terminal_code);
                }

            }

            // $mod_group = $this->menu_model->get_modifier_groups(array('mod_group_id'=>$items['mod_group_id']));
            // $mod_group = $mod_group[0];

            $this->make->sRow(array('id'=>'trans-cat-row-'.$id));
                // $where = array('trans_id'=>$items['trans_id']);
                // $det = $this->site_model->get_details($where,'transaction_types');
                // $this->make->td($det[0]->trans_name);

                $where = array('menu_cat_id'=>$items['menu_cat_id']);
                $det = $this->site_model->get_details($where,'menu_categories');
                $this->make->td($det[0]->menu_cat_name,array('style'=>'text-align:left'));

                $a = $this->make->A(fa('fa-lg fa-times fa-fw'),'#',array('id'=>'trans-cat-del-'.$id,'ref'=>$id,'class'=>'del-trans-cat','return'=>true));
                $this->make->td($a,array('style'=>'text-align:right'));
            $this->make->eRow();

            $row = $this->make->code();

            echo json_encode(array('result'=>'success','msg'=>'Category has been added','row'=>$row));
        } else
            echo json_encode(array('result'=>'error','msg'=>'Category already added.'));

    }

    public function remove_trans_cat()
    {
        $this->load->model('dine/settings_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $this->load->model('site/site_model');

        $id = $this->input->post('id');
        $this->settings_model->remove_trans_cat($id);

        $this->settings_model->db = $this->load->database('main', TRUE);
        $this->settings_model->remove_trans_cat($id);

        if(CONSOLIDATOR){
            $terminals = $this->manager_model->get_terminals();

            foreach($terminals as $ctr => $vals){
                // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
               $this->settings_model->remove_trans_cat($id,$vals->terminal_code);
            }

        }
        
        $json['msg'] = 'Category has been Removed.';
        echo json_encode($json);
    }


    public function reserve_seat_management(){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('general_settings');
        
        $data['page_title'] = fa('icon-equalizer').'Reservation Table Management';
        $branches = $this->settings_model->get_table_layout(1);
        $branch = $branches[0];
        // if($branch_id != null && $branch_id != 'add'){
        //     // $staffs = $this->branches_model->get_restaurant_branch_staffs(null,$branch_id);
        // }
        $data['code'] = makeTablesPage($branch);
        $data['add_css'] = 'css/rtag.css';
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] =  'rseatingJs';
        $data['sideBarHide'] = true;
        $this->load->view('page',$data);
    }
    public function get_reservation_tables($asJson=true){
        $this->load->model('dine/settings_model');
        $tables=array();
        $table_list = $this->settings_model->get_tables();
        foreach ($table_list as $res) {
            if($res->trans_type == "reservation"){
                $tables[$res->tbl_id] = array(
                    "name"=> $res->name,
                    "top"=> $res->top,
                    "left"=> $res->left
                );
            }
        }
        if($asJson)
            echo json_encode($tables);
        else
            return $tables;
    }
    public function reservation_tables_form($tbl_id=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $table = array();
        if($tbl_id != null ){
            $tables = $this->settings_model->get_tables($tbl_id);
            $table = $tables[0];
        }
        $data['code'] = makerTableForm($table);
        // $data['load_js'] = 'resto/branches.php';
        // $data['use_js'] = 'branchesTableJs';
        $this->load->view('load',$data);
    }
    public function rtables_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "capacity"=>$this->input->post('capacity'),
            "name"=>$this->input->post('name'),
            "top"=>$this->input->post('top'),
            "left"=>$this->input->post('left'),
            "trans_type"=>'reservation'
        );
        if($this->input->post('delete')){
            $id = $this->input->post('delete');
            $this->settings_model->delete_tables($id);
            $msg = 'Deleted '.$this->input->post('name');
            $act = 'delete';
        }
        else{
            if($this->input->post('tbl_id')){
                $this->settings_model->update_tables($items,$this->input->post('tbl_id'));
                $id = $this->input->post('tbl_id');
                $act = 'update';
                $msg = 'Updated '.$this->input->post('name');
            }else{
                $id = $this->settings_model->add_tables($items);
                $act = 'add';
                $msg = 'Added '.$this->input->post('name');
            }
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }


    //for payment methods
    ////JED PAYMENT MODES
    public function payment_mode(){
        $this->load->model('dine/settings_model');
        $this->load->helper('dine/settings_helper');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('payment');
        $data['page_title'] = fa('icon-book-open')." Payment Modes";
        // if(MASTER_BUTTON_EDIT){
            $th = array('Code','Description','Inactive','');
        // }else{
        //     $th = array('Code','Description','Inactive');            
        // }   
        $data['code'] = create_rtable('payment_types','payment_id','payments-tbl',$th,'settings/search_payment_form',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'paymentsListFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }

    public function get_payments($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 50;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(payment_types.description like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['payment_types.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('payment_types',$args,array(),$join,true,'payment_types.*',null,null,true);
        $page = paginate('settings/get_payments',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('payment_types',$args,array(),$join,true,'payment_types.*',null,$page['limit']);
         // echo "<pre>",print_r($items),"</pre>";die();
        $json = array();
        if(count($items) > 0){
            $ids = array();
            // if(MASTER_BUTTON_EDIT){
                foreach ($items as $res) {
                    if(MASTER_BUTTON_EDIT){
                        $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/pay_form/'.$res->payment_id,array('class'=>'btn btn-sm blue btn-outline edit','id'=>'edit-'.$res->payment_id,'ref'=>$res->payment_id,'return'=>'true'));
                    }else{
                        $link = "";
                    }
                    $json[$res->payment_id] = array(
                        // "id"=>$res->payment_id,   
                        "code"=>ucwords(strtolower($res->payment_code)),   
                        "description"=>ucwords(strtolower($res->description)),   
                        // "caption"=>"PHP ".num($res->cost),
                        "inactive_show"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                    $ids[] = $res->payment_id;
                }
            // }else{
                // foreach ($items as $res) {
                //     $json[$res->payment_id] = array(
                //          "code"=>ucwords(strtolower($res->payment_code)),   
                //         "description"=>ucwords(strtolower($res->description)),   
                //         // "caption"=>"PHP ".num($res->cost),
                //         "inactive_show"=>($res->inactive == 0 ? 'No' : 'Yes'),
                //         "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                //         // "link"=>$link
                //     );
                //     $ids[] = $res->payment_id;
                // }
            // }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function search_payment_form(){
        $this->load->helper('dine/settings_helper');
        $data['code'] = paySearchForm();
        $this->load->view('load',$data);
    }
    public function pay_form($pay_id=null){
        $this->load->model('dine/settings_model');
        $this->load->helper('dine/settings_helper');
        $data = $this->syter->spawn('payment');
        $data['page_title'] = fa('icon-book-open')." Add New Payment Mode";
        if($pay_id != null){
            $result = $this->site_model->get_tbl('payment_types',array('payment_id'=>$pay_id));
            $pays = $result[0];
            $data['page_title'] = fa('icon-book-open').' '.$pays->description;
        }
        // echo 'asdfasdfsdaf'; die();
        $data['code'] = payFormPage($pay_id);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'payFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function pay_details_load($pay_id=null){
        $this->load->model('dine/settings_model');
        $this->load->helper('dine/settings_helper');
        $pay=array();
        // echo 'sok'; die();
        if($pay_id != null){
            $pays = $this->settings_model->get_payment_types($pay_id);
            $pay=$pays[0];
        }
        $data['code'] = payDetailsLoad($pay,$pay_id);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'detailsLoadJs';
        $this->load->view('load',$data);
    }
    public function pay_details_db(){
        $this->load->model('dine/settings_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $items = array(
            "payment_code"=>$this->input->post('payment_code'),
            "description"=>$this->input->post('description'),
            "payment_group_id"=>$this->input->post('payment_group_id'),
            "inactive"=>(int)$this->input->post('inactive'),
            // "cost"=>$this->input->post('cost')
        );

        // echo 'sadfsadfsdf'; die();

        if($this->input->post('form_pay_id')){
            $this->settings_model->update_payment_type($items,$this->input->post('form_pay_id'));
            $id = $this->input->post('form_pay_id');
            $act = 'update';
            $msg = 'Updated Payment '.$this->input->post('payment_code');
            $this->main_model->update_tbl('payment_types','payment_id',$items,$id);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){

                    //check if meron
                    $where = array('payment_id'=>$this->input->post('form_pay_id'));
                    $chk = $this->manager_model->get_details($where,'payment_types',$vals->terminal_code);

                    if(count($chk) > 0){
                        $this->settings_model->update_payment_type($items,$this->input->post('form_pay_id'),$vals->terminal_code);
                    }else{
                        $items['payment_id'] = $this->input->post('form_pay_id');
                        $this->settings_model->add_payment_type($items,$vals->terminal_code);
                    }

                }

            }
        }else{
            $dets = $this->manager_model->get_last_ids('payment_id','payment_types');
            if($dets){
                $id = $dets[0]->payment_id + 1;
            }else{
                $id = 1;  
            }
            $items['payment_id'] = $id;

            $this->settings_model->add_payment_type($items);
            $act = 'add';
            $msg = 'Added new Payment '.$this->input->post('payment_code');
            // $items['payment_id'] = $id;
            $this->main_model->add_trans_tbl('payment_types',$items);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    $this->settings_model->add_payment_type($items,$vals->terminal_code);
                }

            }

        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
    //for input fileds
    public function input_fields_load($pay_id=null){
        $this->load->model('dine/settings_model');
        $this->load->helper('dine/settings_helper');
        $details = $this->settings_model->get_payment_type_fields(null,$pay_id);

        $data['code'] = inputFieldsLoad($pay_id,$details);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'inputFieldLoadJs';
        $this->load->view('load',$data);
    }
    public function input_fields_db(){
        $this->load->model('dine/settings_model');
        $this->load->model('dine/manager_model');
        // $mod_group_id = $this->input->post('mod_group_id');
        $payment_id = $this->input->post('payment_id');
        $field_name = $this->input->post('field_name');
        $items = array(
            "payment_id" => $payment_id,
            "field_name" => $field_name,
            "inactive" => '0'
        );
        // $gotDet = $this->mods_model->get_payment_type_fields(null,$payment_id,$field_name);
        // if(count($gotDet) > 0){
        //     $det = $gotDet[0];
        //     $this->mods_model->update_modifier_group_details($items,$det->id);
        //     $this->mods_model->db = $this->load->database('main', TRUE);
        //     $this->mods_model->update_modifier_group_details($items,$det->id);
        //     $this->mods_model->db = $this->load->database('default', TRUE);
        //     $id = $det->id;
        //     $act = "update";
        //     $msg = "Updated Modifier ".$mod_text;
        // }
        // else{
            $id = $this->settings_model->add_payment_fields($items);
            $this->settings_model->db = $this->load->database('main', TRUE);
            
            $items['field_id'] = $id;
            $this->settings_model->add_payment_fields($items);
            $this->settings_model->db = $this->load->database('default', TRUE);
            $act = 'add';
            $msg = 'Added  Field '.$field_name;

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    $this->settings_model->add_payment_fields($items,$vals->terminal_code);
                }

            }


        // }
        $li = $this->make->li(
                $this->make->checkbox(null,'dflt_'.$id,0,array('ref'=>$id,'return'=>true),1)." ".
                $this->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
                $this->make->span($field_name,array('class'=>'text','return'=>true)),
                // $this->make->A(fa('fa-lg fa-times'),'#',array('return'=>true,'class'=>'del','id'=>'del-'.$id,'ref'=>$id)),
                array('return'=>true,'id'=>'li-'.$id)
             );

        // echo $li; die();

        $mod_text = '';
        echo json_encode(array("id"=>$id,"desc"=>$mod_text,"act"=>$act,'msg'=>$msg,'li'=>$li));
    }
    public function inactive_input_filed(){
        $this->load->model('dine/settings_model');
        $this->load->model('dine/manager_model');
        $items = array('inactive'=>$this->input->post('dflt'));
        $this->settings_model->update_payment_fields($items,$this->input->post('field_id'));
        $this->settings_model->db = $this->load->database('main', TRUE);
        $this->settings_model->update_payment_fields($items,$this->input->post('field_id'));

        if(CONSOLIDATOR){
            $terminals = $this->manager_model->get_terminals();

            foreach($terminals as $ctr => $vals){
                // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                $this->settings_model->update_payment_fields($items,$this->input->post('field_id'),$vals->terminal_code);
            }

        }

        // $this->mods_model->delete_modifier_group_details($this->input->post('group_mod_id'));
        if($this->input->post('dflt') == 0){
            $text = 'Active';
        }else{
            $text = 'Inactive';
        }

        $json['msg'] = "Input Field set to ".$text;
        echo json_encode($json);
    }

    //brandss
    public function brands(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('brands');
        $data['page_title'] = fa('icon-equalizer')." Brands";
        // $brand_list = $this->settings_model->get_brands();
        // $data = $this->syter->spawn('general_settings');
        // $data['page_title'] = fa('icon-home')." Brands";
        // $data['page_subtitle'] = 'Location Management';
        // $data['code'] = site_list_form("settings/category_form","category_form","Locations",$location_list,array('name','code'),'code');
        // $data['code'] = site_list_form("settings/brand_form","brand_form","Brands",$brand_list,array('brand_code', 'brand_name'),'id',FALSE);
        // // $data['code'] = "";
        // $data['add_js'] = 'js/site_list_forms.js';
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Code','Inactive','');
        }else{
            $th = array('ID','Name','Code','Inactive');
        }
        $data['code'] = create_rtable('brands','disc_id','brand-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'brandlistFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function brand_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('brands');
        $brand = array();
        if($ref != null){
            $brands = $this->settings_model->get_brands($ref);
            $brand = $brands[0];
        }
        $data['code'] = makeBrandForm($brand);

        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'brandlistFormJs';
        $this->load->view('page',$data);
    }
    public function brand_db(){
        $this->load->model('dine/settings_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/main_model');
        $items = array(
            "brand_name"=>$this->input->post('brand_name'),
            "brand_code"=>$this->input->post('brand_code'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('id')){
            $this->settings_model->update_brands($items, $this->input->post('id'));
            $id = $this->input->post('id');
            $act = 'update';
            $msg = 'Updated Brand: '.$this->input->post('brand_name');
            $this->main_model->update_tbl('brands','id',$items,$this->input->post('id'));
            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){

                    //check if meron
                    $where = array('id'=>$this->input->post('id'));
                    $chk = $this->manager_model->get_details($where,'brands',$vals->terminal_code);

                    if(count($chk) > 0){
                        $this->settings_model->update_brands_termi($items, $this->input->post('id'),$vals->terminal_code);
                    }else{
                        $items = array(
                            "id"=>$this->input->post('id'),
                            "brand_name"=>$this->input->post('brand_name'),
                            "brand_code"=>$this->input->post('brand_code'),
                            "inactive"=>(int)$this->input->post('inactive')
                        );
                        $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    }

                }

            }
        }else{
            $dets = $this->settings_model->get_last_ids('id','brands');
            if($dets){
                $id = $dets[0]->id + 1;
            }else{
                $id = 1;
            }
            $items['id'] = $id;
            $this->settings_model->add_brands($items);
            $act = 'add';
            $msg = 'Added New Brand: '.$this->input->post('brand_name');
            $this->main_model->add_trans_tbl('brands',$items);
            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                }

            }
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('brand_name')." ".$this->input->post('brand_code'),"act"=>$act,'msg'=>$msg));
    }

    //payment group
    public function payment_group(){
        $this->load->model('dine/settings_model');
        $this->load->helper('dine/settings_helper');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('payment');
        $data['page_title'] = fa('icon-book-open')." Payment Group";
        // if(MASTER_BUTTON_EDIT){
            $th = array('ID','Code','Description','Inactive','');
        // }else{
        //     $th = array('Code','Description','Inactive');            
        // }   
        $data['code'] = create_rtable('payment_group_id','code','paymentgroup-tbl',$th,'settings/search_payment_form',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'paymentsGroupListFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }

    public function get_payment_group($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 50;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(payment_group.description like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['payment_group.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('payment_group',$args,array(),$join,true,'payment_group.*',null,null,true);
        $page = paginate('settings/get_payment_group',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('payment_group',$args,array(),$join,true,'payment_group.*',null,$page['limit']);
         // echo "<pre>",print_r($items),"</pre>";die();
        $json = array();
        if(count($items) > 0){
            $ids = array();
            // if(MASTER_BUTTON_EDIT){
                foreach ($items as $res) {
                    if(MASTER_BUTTON_EDIT){
                        $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/payment_group_form/'.$res->payment_group_id,array('class'=>'btn btn-sm blue btn-outline edit','id'=>'edit-'.$res->payment_group_id,'ref'=>$res->payment_group_id,'return'=>'true'));
                    }else{
                        $link = "";
                    }

                    $json[$res->payment_group_id] = array(
                        "id"=>$res->payment_group_id,   
                        "code"=>ucwords(strtolower($res->code)),   
                        "description"=>ucwords(strtolower($res->description)),   
                        // "caption"=>"PHP ".num($res->cost),
                        // "inactive_show"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "inactive_show"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                    $ids[] = $res->payment_group_id;
                }
            // }else{
                // foreach ($items as $res) {
                //     $json[$res->payment_id] = array(
                //          "code"=>ucwords(strtolower($res->payment_code)),   
                //         "description"=>ucwords(strtolower($res->description)),   
                //         // "caption"=>"PHP ".num($res->cost),
                //         "inactive_show"=>($res->inactive == 0 ? 'No' : 'Yes'),
                //         "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                //         // "link"=>$link
                //     );
                //     $ids[] = $res->payment_id;
                // }
            // }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }

    public function payment_group_form($payment_group_id=null){
        $this->load->model('dine/settings_model');
        $this->load->helper('dine/settings_helper');
        $data = $this->syter->spawn('payment_group');
        $data['page_title'] = fa('icon-book-open')." Add New Payment Group";

        

        if($payment_group_id != null){
            $result = $this->site_model->get_tbl('payment_group',array('payment_group_id'=>$payment_group_id));
            $pays = $result[0];
            $data['page_title'] = fa('icon-book-open').' '.$pays->description;

            $pays = $this->settings_model->get_payment_group($payment_group_id);
            $pay=$pays[0];
        }else{
            $pay=new stdClass();
            $fields= $this->db->list_fields('payment_group');
            foreach ($fields as $field)
            {
                $pay->$field='';
            }
        } 
        // echo 'asdfasdfsdaf'; die();
        $data['code'] = paymentGroupFormPage($pay);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'paymentGroupLoadJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }

    public function payment_group_details_db(){
        $this->load->model('dine/settings_model');
        $this->load->model('dine/main_model');
        $items = array(
            "code"=>$this->input->post('code'),
            "description"=>$this->input->post('description'),
            "inactive"=>(int)$this->input->post('inactive'),
            // "cost"=>$this->input->post('cost')
        );

        // echo 'sadfsadfsdf'; die();

        if($this->input->post('payment_group_id')){
            $this->settings_model->update_payment_group($items,$this->input->post('payment_group_id'));
            $id = $this->input->post('payment_group_id');
            $act = 'update';
            $msg = 'Updated Payment Group '.$this->input->post('payment_code');
            $this->main_model->update_tbl('payment_group','payment_group_id',$items,$id);
        }else{
            $id = $this->settings_model->add_payment_group($items);
            $act = 'add';
            $msg = 'Added new Payment Group '.$this->input->post('code');
            $this->main_model->add_trans_tbl('payment_group',$items);

        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
    public function get_rdiscounts($termninal=0){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 200;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        
        if($this->input->post('disc_name')){
            $lk  =$this->input->post('disc_name');
            $args["(receipt_discounts.disc_name like '%".$lk."%' "] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['receipt_discounts.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('receipt_discounts',$args,array(),$join,true,'*',null,null,true);
        $page = paginate('settings/get_rdiscounts',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('receipt_discounts',$args,array(),$join,true,'*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                 foreach ($items as $res) {
                    // if($termninal == 1){
                    //     $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'pos_customers/customer_terminal_form/'.$res->disc_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                   
                    // }
                    // else{
                        $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/receipt_discount_form/'.$res->disc_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                 
                    // }
                    $json[$res->disc_id] = array(
                        "id"=>$res->disc_id,   
                        "title"=>$res->disc_name,   
                        "desc"=>$res->disc_code,   
                        "disc_rate"=>$res->disc_rate,   
                        "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                }
            }else{
                foreach ($items as $res) {
                    
                    $json[$res->disc_id] = array(
                        "id"=>$res->disc_id,   
                        "title"=>$res->disc_name,   
                        "desc"=>$res->disc_code,   
                        "disc_rate"=>$res->disc_rate,   
                        "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                }
            }
           
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function get_cbrands($termninal=0){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 100;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        
        if($this->input->post('brand_name')){
            $lk  =$this->input->post('brand_name');
            $args["(brands.brand_name like '%".$lk."%' "] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['brands.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('brands',$args,array(),$join,true,'*',null,null,true);
        $page = paginate('brands/get_cbrands',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('brands',$args,array(),$join,true,'*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                 foreach ($items as $res) {
                    // if($termninal == 1){
                    //     $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'pos_customers/customer_terminal_form/'.$res->disc_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                   
                    // }
                    // else{
                        $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/brand_form/'.$res->id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                 
                    // }
                    $json[$res->id] = array(
                        "id"=>$res->id,   
                        "title"=>$res->brand_name,   
                        "desc"=>$res->brand_code,   
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                }
            }else{
                foreach ($items as $res) {
                    
                    $json[$res->id] = array(
                        "id"=>$res->id,   
                        "title"=>$res->brand_name,   
                        "desc"=>$res->brand_code,   
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                }
            }
           
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function load_denomination($termninal=0){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 100;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        
        if($this->input->post('desc')){
            $lk  =$this->input->post('desc');
            $args["(denominations.desc like '%".$lk."%' "] = array('use'=>'where','val'=>"",'third'=>false);
        }
        // if($this->input->post('inactive')){
        //     $args['charges.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        // }
        $join = null;
        $count = $this->site_model->get_tbl('denominations',$args,array(),$join,true,'*',null,null,true);
        $page = paginate('settings/load_denomination',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('denominations',$args,array(),$join,true,'*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                 foreach ($items as $res) {
                    // if($termninal == 1){
                    //     $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'pos_customers/customer_terminal_form/'.$res->disc_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                   
                    // }
                    // else{
                        $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/denomination_form/'.$res->id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                 
                    // }
                    $json[$res->id] = array(
                        "id"=>$res->id,   
                        "title"=>$res->desc,   
                        "desc"=>$res->value,   
                        // "charge_amount"=>$res->charge_amount,   
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        // "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                }
            }else{
                foreach ($items as $res) {
                    
                    $json[$res->id] = array(
                        "id"=>$res->id,   
                        "title"=>$res->desc,   
                        "desc"=>$res->value,  
                        // "charge_amount"=>$res->charge_amount,    
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        // "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                }
            }
           
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function load_locations($termninal=0){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 100;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        
        if($this->input->post('loc_name')){
            $lk  =$this->input->post('loc_name');
            $args["(locations.loc_name like '%".$lk."%' "] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['locations.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('locations',$args,array(),$join,true,'*',null,null,true);
        $page = paginate('brands/load_locations',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('locations',$args,array(),$join,true,'*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                 foreach ($items as $res) {
                    // if($termninal == 1){
                    //     $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'pos_customers/customer_terminal_form/'.$res->disc_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                   
                    // }
                    // else{
                        $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/location_form/'.$res->loc_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                 
                    // }
                    $json[$res->loc_id] = array(
                        "id"=>$res->loc_id,   
                        "title"=>$res->loc_name,   
                        "desc"=>$res->loc_code,   
                        // "charge_amount"=>$res->charge_amount,   
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                }
            }else{
                foreach ($items as $res) {
                    
                    $json[$res->loc_id] = array(
                        "id"=>$res->loc_id,   
                        "title"=>$res->loc_name,   
                        "desc"=>$res->loc_code,  
                        // "charge_amount"=>$res->charge_amount,    
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                }
            }
           
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function load_tax_rates($termninal=0){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 100;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(tax_rates.name like '%".$lk."%' "] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['tax_rates.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('tax_rates',$args,array(),$join,true,'*',null,null,true);
        $page = paginate('settings/load_tax_rates',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('tax_rates',$args,array(),$join,true,'*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                 foreach ($items as $res) {
                    // if($termninal == 1){
                    //     $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'pos_customers/customer_terminal_form/'.$res->disc_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                   
                    // }
                    // else{
                        $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/tax_rate_form/'.$res->tax_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                 
                    // }
                    $json[$res->tax_id] = array(
                        "id"=>$res->tax_id,   
                        "title"=>$res->name,   
                        "desc"=>$res->rate,   
                        // "charge_amount"=>$res->charge_amount,   
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                }
            }else{
                foreach ($items as $res) {
                    
                    $json[$res->tax_id] = array(
                        "id"=>$res->tax_id,   
                        "title"=>$res->name,   
                        "desc"=>$res->rate,  
                        // "charge_amount"=>$res->charge_amount,    
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                }
            }
           
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function load_uom($termninal=0){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 100;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(uom.name like '%".$lk."%' "] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['uom.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('uom',$args,array(),$join,true,'*',null,null,true);
        $page = paginate('settings/load_uom',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('uom',$args,array(),$join,true,'*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                 foreach ($items as $res) {
                    // if($termninal == 1){
                    //     $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'pos_customers/customer_terminal_form/'.$res->disc_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                   
                    // }
                    // else{
                        $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/uom_form/'.$res->code,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                 
                    // }
                    $json[$res->id] = array(
                        "id"=>$res->id,   
                        "title"=>$res->name,   
                        "desc"=>$res->code,   
                        "num"=>$res->num,   
                        "to"=>$res->to,   
                        // "charge_amount"=>$res->charge_amount,   
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                }
            }else{
                foreach ($items as $res) {
                    
                    $json[$res->id] = array(
                        "id"=>$res->id,   
                        "title"=>$res->name,   
                        "desc"=>$res->code,  
                        "num"=>$res->num,  
                        "to"=>$res->to,   
                        // "charge_amount"=>$res->charge_amount,    
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                }
            }
           
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function load_tran_types($termninal=0){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 100;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        
        if($this->input->post('trans_name')){
            $lk  =$this->input->post('trans_name');
            $args["(transaction_types.trans_name like '%".$lk."%' "] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['transaction_types.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('transaction_types',$args,array(),$join,true,'*',null,null,true);
        $page = paginate('settings/load_tran_types',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('transaction_types',$args,array(),$join,true,'*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                 foreach ($items as $res) {
                    // if($termninal == 1){
                    //     $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'pos_customers/customer_terminal_form/'.$res->disc_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                   
                    // }
                    // else{
                        $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'settings/trans_form/'.$res->trans_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                 
                    // }
                    $json[$res->trans_id] = array(
                        "id"=>$res->trans_id,   
                        "title"=>$res->trans_name,    
                        // "charge_amount"=>$res->charge_amount,   
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                }
            }else{
                foreach ($items as $res) {
                    
                    $json[$res->trans_id] = array(
                        "id"=>$res->trans_id,   
                        "title"=>$res->trans_name,   
                        // "charge_amount"=>$res->charge_amount,    
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                }
            }
           
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }

}