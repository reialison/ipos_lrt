<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
    }
	public function index(){
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('menu');
        $data['page_title'] = fa(' icon-book-open').' Items';
        // $menus = $this->menu_model->get_menus();
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Description','Category','Cost','Register Date','Inactive','');
        }else{
            $th = array('ID','Name','Description','Category','Cost','Register Date','Inactive');
        }
        $data['code'] = create_rtable('menus','menu_id','menus-tbl',$th,'menu/search_menus_form',true,'list',REMOVE_MASTER_BUTTON);

        // $data['code'] = menuListPage($menus);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'listFormJs';
        $data['page_no_padding'] = true;
        // $data['sideBarHide'] = true;
        $this->load->view('page',$data);
    }
    public function get_menus($id=null,$asJson=true,$resultOnly=false){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 10000;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('menu_name')){
            $lk  =$this->input->post('menu_name');
            $args["(menus.menu_name like '%".$lk."%' OR menus.menu_short_desc like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['menus.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        else{
            $args['menus.inactive'] = 0;
        }
        if($this->input->post('menu_cat_id')){
            $args['menus.menu_cat_id'] = array('use'=>'where','val'=>$this->input->post('menu_cat_id'));
        }
        if($id != null){
            $args = array();
            $args['menus.menu_id'] = $id;
        }
        $join["menu_categories"] = array('content'=>"menus.menu_cat_id = menu_categories.menu_cat_id");
        $join["menu_subcategories"] = array('content'=>"menus.menu_sub_cat_id = menu_subcategories.menu_sub_cat_id",'mode'=>'LEFT');
        $count = $this->site_model->get_tbl('menus',$args,array(),$join,true,'menus.*,menu_categories.menu_cat_name,menu_subcategories.menu_sub_cat_name',null,null,true);
        $page = paginate('menu/get_menus',$count,$total_rows,$pagi);
        if(!$resultOnly)
            $items = $this->site_model->get_tbl('menus',$args,array(),$join,true,'menus.*,menu_categories.menu_cat_name,menu_subcategories.menu_sub_cat_name',null,$page['limit']);
        else{
            $items = $this->site_model->get_tbl('menus',$args,array(),$join,true,'menus.*,menu_categories.menu_cat_name,menu_subcategories.menu_sub_cat_name',null);
            return $items;
        }
        $json = array();
        if(count($items) > 0){
            $ids = array();
             if(MASTER_BUTTON_EDIT){
                foreach ($items as $res) {
                    $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'menu/form/'.$res->menu_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));
                    $json[$res->menu_id] = array(
                        "id"=>$res->menu_id,   
                        "title"=>"[".$res->menu_code."] ".ucwords(strtolower($res->menu_name)),   
                        "description"=>ucwords(strtolower($res->menu_short_desc)),   
                        "subtitle"=>ucwords(strtolower($res->menu_cat_name)),   
                        "caption"=>"PHP ".num($res->cost),
                        "date_reg"=>sql2Date($res->reg_date),
                        "status"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                    $ids[] = $res->menu_id;
                }
            }else{
                 foreach ($items as $res) {
                    $json[$res->menu_id] = array(
                        "id"=>$res->menu_id,   
                        "title"=>"[".$res->menu_code."] ".ucwords(strtolower($res->menu_name)),   
                        "description"=>ucwords(strtolower($res->menu_short_desc)),   
                        "subtitle"=>ucwords(strtolower($res->menu_cat_name)),   
                        "caption"=>"PHP ".num($res->cost),
                        "date_reg"=>sql2Date($res->reg_date),
                        "status"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                    $ids[] = $res->menu_id;
                }

            }
            $images = $this->site_model->get_image(null,null,'menus',array('images.img_ref_id'=>$ids)); 
            foreach ($images as $res) {
                if(isset($json[$res->img_ref_id])){
                    $js = $json[$res->img_ref_id];
                    $js['image'] = $res->img_path;
                    $json[$res->img_ref_id] = $js;
                }
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function search_menus_form(){
        $data['code'] = menuSearchForm();
        $this->load->view('load',$data);
    }
    public function form($menu_id=null){
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
        $data = $this->syter->spawn('menu');
        $img = "";
        $data['page_title'] = fa('icon-book-open').' Add New Item';
        if($menu_id != null){
            $result = $this->site_model->get_tbl('menus',array('menu_id'=>$menu_id));
            $menu = $result[0];
            $data['page_title'] = fa('icon-book-open').' '.$menu->menu_name;
            $images = $this->site_model->get_image(null,null,'menus',array('images.img_ref_id'=>$menu_id)); 
            foreach ($images as $res) {
                $img = $res->img_path;
            }
        }
        $data['code'] = menuFormPage($menu_id,$img);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'menuFormJs';
        // $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function upload_image_load($menu_id=null){
        $res = array();
        if($menu_id != null){
            $result = $this->site_model->get_image(null,$menu_id,'menus');
            if(count($result) > 0)
                $res = $result[0];
        }
        $data['code'] = menuImagesLoad($menu_id,$res);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'menuImageJs';
        $this->load->view('load',$data);
    }
    public function images_db(){
        $image = null;
        // if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
        //     $image = file_get_contents($_FILES['fileUpload']['tmp_name']);
        // }
        $ext = null;
        $msg = "";
        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){
            $info = pathinfo($_FILES['fileUpload']['name']);
            if(isset($info['extension']))
            $ext = $info['extension'];
            $menu = $this->input->post('upload_menu_id');
            $newname = $menu.".".$ext;
            if (!file_exists("uploads/menus/")) {
                mkdir("uploads/menus/", 0777, true);
            }
            $target = 'uploads/menus/'.$newname;
            if(!move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
                $msg = "Image Upload failed";
            }
            else{
                $new_image = $target;
                $result = $this->site_model->get_image(null,$this->input->post('upload_menu_id'),'menus');
                $items = array(
                    "img_file_name" => $newname,
                    "img_path" => $new_image,
                    "img_ref_id" => $this->input->post('upload_menu_id'),
                    "img_tbl" => 'menus',
                );
                if(count($result) > 0){
                    $this->site_model->update_tbl('images','img_id',$items,$result[0]->img_id);
                }
                else{
                    $id = $this->site_model->add_tbl('images',$items,array('datetime'=>'NOW()'));
                }
            }
            ####
        }

        echo json_encode(array('msg'=>$msg));
    }
    public function details_load($menu_id=null){
        $this->load->helper('dine/menu_helper');
        $this->load->model('dine/menu_model');
        $menu=array();
        if($menu_id != null){
            $menus = $this->menu_model->get_menus($menu_id);
            $menu=$menus[0];
        }
        $data['code'] = menuDetailsLoad($menu,$menu_id);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'detailsLoadJs';
        $this->load->view('load',$data);
    }
    public function details_db(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');

       
        $menu_sub_id = null;
        if($this->input->post('menu_sub_id')){
            $menu_sub_id = $this->input->post('menu_sub_id');
        }

        $miaa = null;
        if($this->input->post('miaa_cat')){
            $miaa = $this->input->post('miaa_cat');
        }

        $items = array(
            "menu_code"=>$this->input->post('menu_code'),
            "menu_cat_id"=>$this->input->post('menu_cat_id'),
            "menu_sub_cat_id"=>$this->input->post('menu_sub_cat_id'),
            "menu_barcode"=>$this->input->post('menu_barcode'),
            "menu_sched_id"=>$this->input->post('menu_sched_id'),
            "menu_short_desc"=>$this->input->post('menu_short_desc'),
            "menu_name"=>$this->input->post('menu_name'),
            "brand"=>$this->input->post('brand'),
            "cost"=>$this->input->post('cost'),
            "costing"=>$this->input->post('costing'),
            "no_tax"=>(int)$this->input->post('no_tax'),
            "free"=>(int)$this->input->post('free'),
            "menu_sub_id"=>$menu_sub_id,
            "miaa_cat"=>$miaa,
            "reorder_qty"=>$this->input->post('reorder_qty'),
            "inactive"=>(int)$this->input->post('inactive'),
            "unavailable"=>(int)$this->input->post('unavailable'),
            "menu_qty"=>$this->input->post('qty'),
            "alcohol"=>$this->input->post('alcohol'),
            "uom_id"=>$this->input->post('uom_id'),
        );
        if($this->input->post('new')){
            
            $where = array('menu_code'=>$this->input->post('menu_code'));
            $det = $this->site_model->get_details($where,'menus');

            if($det){
                $msg = "Duplicate Menu Code.";
                echo json_encode(array("id"=>'dup','msg'=>$msg));
            }else{
                $menu_id = $this->menu_model->get_last_menu_id();
                $items['menu_id'] = $menu_id+1;
                $id = $this->menu_model->add_menus($items);
                $act = 'add';
                $msg = 'Added  new Menu '.$this->input->post('menu_name');
                $this->main_model->add_trans_tbl('menus',$items);
                site_alert($msg,'success');
                echo json_encode(array("id"=>$id,"desc"=>$this->input->post('menu_name'),"act"=>$act,'msg'=>$msg));

                if(CONSOLIDATOR){
                    $terminals = $this->manager_model->get_terminals();

                    foreach($terminals as $ctr => $vals){
                        // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                        $this->menu_model->add_menus($items,$vals->terminal_code);
                    }

                }

            }

        }
        else{
            if($this->input->post('form_menu_id')){
                $this->menu_model->update_menus($items,$this->input->post('form_menu_id'));
                $id = $this->input->post('form_menu_id');
                $act = 'update';
                $msg = 'Updated Menu '.$this->input->post('menu_name');
                $this->main_model->update_tbl('menus','menu_id',$items,$id);
                site_alert($msg,'success');
                echo json_encode(array("id"=>$id,"desc"=>$this->input->post('menu_name'),"act"=>$act,'msg'=>$msg));

                if(CONSOLIDATOR){
                    $terminals = $this->manager_model->get_terminals();

                    foreach($terminals as $ctr => $vals){

                        //check if meron
                        $where = array('menu_id'=>$this->input->post('form_menu_id'));
                        $chk = $this->manager_model->get_details($where,'menus',$vals->terminal_code);

                        if(count($chk) > 0){
                            $this->menu_model->update_menus($items,$this->input->post('form_menu_id'),$vals->terminal_code);
                        }else{
                            $items['menu_id'] = $this->input->post('form_menu_id');
                            $this->menu_model->add_menus($items,$vals->terminal_code);
                        }

                    }

                }


            }else{

                $where = array('menu_code'=>$this->input->post('menu_code'));
                $det = $this->site_model->get_details($where,'menus');

                if($det){
                    $msg = "Duplicate Menu Code.";
                    echo json_encode(array("id"=>'dup','msg'=>$msg));
                }else{
                    $menu_id = $this->menu_model->get_last_menu_id();
                    $items['menu_id'] = $menu_id+1;
                    $id = $this->menu_model->add_menus($items);
                    $act = 'add';
                    $msg = 'Added  new Menu '.$this->input->post('menu_name');
                    // $items['menu_id'] = $id;
                    $this->main_model->add_trans_tbl('menus',$items);
                    site_alert($msg,'success');
                    echo json_encode(array("id"=>$menu_id+1,"desc"=>$this->input->post('menu_name'),"act"=>$act,'msg'=>$msg));

                    if(CONSOLIDATOR){
                        $terminals = $this->manager_model->get_terminals();

                        foreach($terminals as $ctr => $vals){
                            // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                            $this->menu_model->add_menus($items,$vals->terminal_code);
                        }

                    }


                }
            }
        }        
         
    }
    public function recipe_load($menu_id=null){
        $det = $this->menu_model->get_recipe_items($menu_id);
        $data['code'] = menuRecipeLoad($menu_id,null,$det);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'recipeLoadJs';
        $this->load->view('load',$data);
    }
    public function recipe_search_item(){
        $search = $this->input->post('search');
        $results = $this->menu_model->search_items($search);
        $items = array();
        if(count($results) > 0 ){
            foreach ($results as $res) {
                $items[] = array('key'=>$res->code." ".$res->name,'value'=>$res->item_id);
            }
        }
        echo json_encode($items);
    }
    public function recipe_item_details($item_id=null){
        $this->load->model('dine/items_model');
        $items = $this->items_model->get_item($item_id);
        $item = $items[0];
        $det['cost'] = $item->costing;
        $det['uom'] = $item->uom;
        echo json_encode($det);
    }
    public function recipe_details_db(){
        $items = array(
            'menu_id' => $this->input->post('menu-id-hid'),
            'item_id' => $this->input->post('item-id-hid'),
            'uom' => $this->input->post('item-uom-hid'),
            'qty' => $this->input->post('qty'),
            'cost' => $this->input->post('item-cost')
        );

        $recipe_det = $this->menu_model->get_recipe_items($items['menu_id'],$items['item_id']);
        if (count($recipe_det) > 0) {
            $det = $recipe_det[0];
            $this->menu_model->update_recipe_item($items,$items['menu_id'],$items['item_id']);
            $id = $det->recipe_id;
            $item_name = $det->item_name;
            $act = "update";
            $msg = "Updated item: ".$item_name;
        } else {
            $this->load->model('dine/items_model');
            $detx = $this->items_model->get_item($items['item_id']);
            $detx = $detx[0];

            $item_name = $detx->name;
            $id = $this->menu_model->add_recipe_item($items);
            $act = "add";
            $msg = "Add new item: ".$this->input->post('item-search');
        }

        $this->make->sRow(array('id'=>'row-'.$id));
            $this->make->td($item_name);
            $this->make->td($items['uom']);
            $this->make->td($items['cost']);
            $this->make->td($items['qty']);
            $this->make->td($items['qty'] * $items['cost']);
            $a = $this->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$id,'ref'=>$id,'class'=>'del-item','return'=>true));
            $this->make->td($a);
        $this->make->eRow();
        $row = $this->make->code();

        echo json_encode(array('id'=>$id,'row'=>$row,'msg'=>$msg,'act'=>$act));
    }
    public function override_price_total($asJson=true,$updateDB=true){
        $this->load->model('resto/menu_model');
        $total = $this->input->post('total');
        $menu_id = $this->input->post('menu_id');
        $a = $total;
        $b = str_replace( ',', '', $a );

        if( is_numeric( $b ) ) {
            $a = $b;
        }
        $this->menu_model->update_menus(array('cost'=>$a),$menu_id);
    }
    public function get_recipe_total(){
        $menu_id = $this->input->post('menu_id');
        $recipe_det = $this->menu_model->get_recipe_items($menu_id);
        $total = 0;
        foreach ($recipe_det as $val) {
            $total += ($val->item_cost * $val->qty);
        }
        echo json_encode(array('total'=>num($total)));
    }
    public function remove_recipe_item(){
        $recipe_id = $this->input->post('recipe_id');
        $this->menu_model->remove_recipe_item($recipe_id);
        $json['msg'] = "Recipe Item Deleted.";
        echo json_encode($json);
    }
    /**********     Menu Modifier Groups   **********/
    public function modifier_load($menu_id=null)
    {
        $det = $this->menu_model->get_menu_modifiers($menu_id);
        $data['code'] = menuModifierLoad($menu_id,$det);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'menuModifierJs';
        $this->load->view('load',$data);
    }
    public function modifier_search_item()
    {
        $search = $this->input->post('search');
        $results = $this->menu_model->search_modifier_groups($search);
        $items = array();
        if(count($results) > 0 ){
            foreach ($results as $res) {
                $items[] = array('key'=>$res->mod_group_id." ".$res->name,'value'=>$res->mod_group_id);
            }
        }
        echo json_encode($items);
    }
    public function menu_modifier_db()
    {
        $this->load->model('dine/manager_model');
        $this->load->model('dine/main_model');
        if (!$this->input->post())
            header('Location:'.base_url().'menu');

        $items = array(
            'menu_id' => $this->input->post('menu-id-hid'),
            'mod_group_id' => $this->input->post('mod-group-id-hid'),
        );

        $det = $this->menu_model->get_menu_modifiers($items['menu_id'],$items['mod_group_id']);

        if (count($det) == 0) {
            $id = $this->menu_model->add_menu_modifier($items);

            $items['id'] = $id;
            $this->main_model->add_trans_tbl('menu_modifiers',$items);
            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    $this->menu_model->add_menu_modifier($items,$vals->terminal_code);
                }

            }

            $this->menu_model->db = $this->load->database('default',true);
            $mod_group = $this->menu_model->get_modifier_groups(array('mod_group_id'=>$items['mod_group_id']));

            // echo "<pre>",print_r($mod_group),"</pre>";die();
            // echo $this->menu_model->db->last_query(); die();
            $mod_group = $mod_group[0];

            $this->make->sRow(array('id'=>'row-'.$id));
                $this->make->td(fa('fa-asterisk')." ".$mod_group->name);
                $a = $this->make->A(fa('fa-lg fa-times fa-fw'),'#',array('id'=>'del-'.$id,'ref'=>$id,'class'=>'del-item','return'=>true));
                $this->make->td($a,array('style'=>'text-align:right'));
            $this->make->eRow();

            $row = $this->make->code();

            echo json_encode(array('result'=>'success','msg'=>'Modifier group has been added','row'=>$row));
        } else
            echo json_encode(array('result'=>'error','msg'=>'Menu already has modifier group'));

    }
    public function remove_menu_modifier()
    {
        $this->load->model('dine/manager_model');
        $id = $this->input->post('id');
        $this->menu_model->remove_menu_modifier($id);
        $this->menu_model->remove_menu_modifier($id,'main');
        if(CONSOLIDATOR){
            $terminals = $this->manager_model->get_terminals();

            foreach($terminals as $ctr => $vals){
                // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                $this->menu_model->remove_menu_modifier($id,$vals->terminal_code);
            }

        }
        $json['msg'] = 'Removed modifier group';
        echo json_encode($json);
    }
    //menu prices
     public function price_load($menu_id=null)
    {
        $det = $this->menu_model->get_menu_prices($menu_id);
        $data['code'] = menuPricesLoad($menu_id,$det);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'menuPricesJs';
        $this->load->view('load',$data);
    }

    public function menu_prices_db()
    {
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/settings_model');
        if (!$this->input->post())
            header('Location:'.base_url().'menu');

        $items = array(
            'menu_id' => $this->input->post('menu-id-hid'),
            'trans_type' => $this->input->post('trans_type'),
            'price' => $this->input->post('price'),
        );

        $det = $this->menu_model->get_menu_prices($items['menu_id'],$items['trans_type']);

        // echo 'asdfasdf'; die();

        if (count($det) == 0) {
            $dets = $this->settings_model->get_last_ids('id','menu_prices');
            if($dets){
                $id = $dets[0]->id + 1;
            }else{
                $id = 1;
            }
            $items['id'] = $id;
            
            $this->menu_model->add_menu_price($items);

            $this->main_model->add_trans_tbl('menu_prices',$items);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                   $this->menu_model->add_menu_price($items,$vals->terminal_code);
                }

            }

            // $mod_group = $this->menu_model->get_modifier_groups(array('mod_group_id'=>$items['mod_group_id']));
            // $mod_group = $mod_group[0];

            $this->make->sRow(array('id'=>'row-'.$id));
                $this->make->td($items['trans_type']);
                $this->make->td(num($items['price']));
                $a = $this->make->A(fa('fa-lg fa-times fa-fw'),'#',array('id'=>'del-'.$id,'ref'=>$id,'class'=>'del-item','return'=>true));
                $this->make->td($a,array('style'=>'text-align:right'));
            $this->make->eRow();

            $row = $this->make->code();

            echo json_encode(array('result'=>'success','msg'=>'Price has been added','row'=>$row));
        } else
            echo json_encode(array('result'=>'error','msg'=>'Transaction Type already has a price'));

    }

    public function remove_menu_price()
    {
        $this->load->model('dine/manager_model');

        $id = $this->input->post('id');
        $this->menu_model->remove_menu_price($id);

        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->menu_model->remove_menu_price($id);

        if(CONSOLIDATOR){
            $terminals = $this->manager_model->get_terminals();

            foreach($terminals as $ctr => $vals){
                // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
               $this->menu_model->remove_menu_price($id,$vals->terminal_code);
            }

        }
        
        $json['msg'] = 'Price Removed.';
        echo json_encode($json);
    }

    /*******   End of  Menu Modifier Groups   *******/
    public function categories(){
        $this->load->model('dine/menu_model');
        $this->load->helper('site/site_forms_helper');
        $menu_categories = $this->menu_model->get_menu_categories();
        $data = $this->syter->spawn('menu');
        $data['page_title'] = fa('fa-cutlery')."Menu Categories";
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Reg Date','Arrangement No.','Inactive','');
        }else{
            $th = array('ID','Name','Reg Date','Arrangement No.','Inactive');
        }
        $data['code'] = create_rtable('menu_categories','menu_cat_id','categories-tbl',$th,'menu/search_cat_menus_form',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'categoryListJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function get_menu_categories($id=null,$asJson=true){
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
        if($this->input->post('menu_cat_name')){
            $lk  =$this->input->post('menu_cat_name');
            $args["(menu_categories.menu_cat_name like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['menu_categories.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $count = $this->site_model->get_tbl('menu_categories',$args,array(),null,true,'menu_categories.*',null,null,true);
        $page = paginate('menu/get_menu_categories',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('menu_categories',$args,array(),null,true,'menu_categories.*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            if(MASTER_BUTTON_EDIT){
                foreach ($items as $res) {
                    $link = "";
                    $link = $this->make->A(fa('fa-edit fa-lg') .'Edit',base_url().'menu/categories_form/'.$res->menu_cat_id,array('class'=>'edit btn btn-sm blue btn-outline','id'=>'edit-'.$res->menu_cat_id,'ref'=>$res->menu_cat_id,'return'=>'true'));

                    // $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'charges/form/'.$res->charge_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));  

                    $json[$res->menu_cat_id] = array(
                        "id"=>$res->menu_cat_id,   
                        "title"=>ucwords(strtolower($res->menu_cat_name)),   
                        "date_reg"=>sql2Date($res->reg_date),
                        "arrangement"=>$res->arrangement,
                        "inact"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                }
            }else{
                foreach ($items as $res) {
                    $link = "";
                    $json[$res->menu_cat_id] = array(
                        "id"=>$res->menu_cat_id,   
                        "title"=>ucwords(strtolower($res->menu_cat_name)),   
                        "date_reg"=>sql2Date($res->reg_date),
                        "arrangement"=>$res->arrangement,
                        "inact"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                }
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }

    public function categories_form_pop($ref=null){
        $data = $this->syter->spawn('menu');
        $this->load->helper('dine/menu_helper');
        $this->load->model('dine/menu_model');

        $cat = array();
        if($ref != null){
            $cats = $this->menu_model->get_menu_categories2($ref);
            $cat = $cats[0];
        }
        $this->data['code'] = makeMenuCategoriesFormPop($cat);
        // $data['code'] = makeMenuCategoriesForm($cat);
        $this->load->view('load',$this->data);
        // $data['load_js'] = 'dine/menu.php';
        // $data['use_js'] = 'categoryListJs';
        // $this->load->view('page',$data);
    }

    public function categories_form($ref=null){
        $data = $this->syter->spawn('menu');
        $this->load->helper('dine/menu_helper');
        $this->load->model('dine/menu_model');

        $cat = array();
        if($ref != null){
            $cats = $this->menu_model->get_menu_categories2($ref);
            $cat = $cats[0];
        }
        // $this->data['code'] = makeMenuCategoriesForm($cat);
        $data['code'] = makeMenuCategoriesForm($cat);
        // $this->load->view('load',$this->data);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'categoryListJs';
        $this->load->view('page',$data);
    }
    public function categories_form_db(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $items = array();
        $error = "";

        $items = array(
            "menu_cat_name"=>$this->input->post('menu_cat_name'),
            "menu_sched_id"=>$this->input->post('menu_sched_id'),
            "arrangement"=>(int)$this->input->post('arrangement'),
            "brand"=>(int)$this->input->post('brand'),
            "unli"=>(int)$this->input->post('unli'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('menu_cat_id')){
            $result = $this->site_model->get_tbl('menu_categories',array('arrangement > '=>'0','arrangement'=>$this->input->post('arrangement'),'menu_cat_id !='=>$this->input->post('menu_cat_id')));
            // echo $this->site_model->db->last_query(); die();

            if($result){
                $error = "Duplicate Arrangement No.";
            }else{
                $this->menu_model->update_menu_categories($items,$this->input->post('menu_cat_id'));
                $id = $this->input->post('menu_cat_id');
                $act = 'update';
                $msg = 'Updated Menu Category  '.$this->input->post('menu_cat_name');
                $this->main_model->update_tbl('menu_categories','menu_cat_id',$items,$id);
                site_alert($msg,'success');


                if(CONSOLIDATOR){
                    $terminals = $this->manager_model->get_terminals();

                    foreach($terminals as $ctr => $vals){

                        //check if meron
                        $where = array('menu_cat_id'=>$id);
                        $chk = $this->manager_model->get_details($where,'menu_categories',$vals->terminal_code);

                        if(count($chk) > 0){
                            // $this->settings_model->update_brands_termi($items, $this->input->post('id'),$vals->terminal_code);
                            $this->menu_model->update_menu_categories($items,$this->input->post('menu_cat_id'),$vals->terminal_code);
                        }else{
                            $items = array(
                                "menu_cat_id"=>$this->input->post('menu_cat_id'),
                                "menu_cat_name"=>$this->input->post('menu_cat_name'),
                                "menu_sched_id"=>$this->input->post('menu_sched_id'),
                                "arrangement"=>(int)$this->input->post('arrangement'),
                                "brand"=>(int)$this->input->post('brand'),
                                "unli"=>(int)$this->input->post('unli'),
                                "inactive"=>(int)$this->input->post('inactive')
                            );
                            $this->menu_model->add_menu_categories($items,$vals->terminal_code);
                        }

                    }

                }

            }

        }else{
            $result = $this->site_model->get_tbl('menu_categories',array('arrangement > '=>'0','arrangement'=>$this->input->post('arrangement')));

            if($result){
                $error = "Duplicate Arrangement No.";
            }else{
                $dets = $this->manager_model->get_last_ids('menu_cat_id','menu_categories');
                if($dets){
                    $id = $dets[0]->menu_cat_id + 1;
                }else{
                    $id = 1;  
                }
                $items['menu_cat_id'] = $id;


                $this->menu_model->add_menu_categories($items);
                $act = 'add';
                $msg = 'Added  new Menu Category '.$this->input->post('menu_cat_name');
                // $items['menu_cat_id'] = $id;
                $this->main_model->add_trans_tbl('menu_categories',$items);
                site_alert($msg,'success');

                if(CONSOLIDATOR){
                    $terminals = $this->manager_model->get_terminals();

                    foreach($terminals as $ctr => $vals){
                        $this->menu_model->add_menu_categories($items,$vals->terminal_code);
                    }

                }
            }
        }
        // echo json_encode(array("id"=>$id,"addOpt"=>$items['menu_cat_name'],"desc"=>$this->input->post('menu_cat_name'),"act"=>$act,'msg'=>$msg,'error'=>$error));
        echo json_encode(array('error'=>$error));

    }
    public function categories_form_db_pop(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $items = array();
        $error = "";

        $items = array(
            "menu_cat_name"=>$this->input->post('menu_cat_name'),
            "menu_sched_id"=>$this->input->post('menu_sched_id'),
            "arrangement"=>(int)$this->input->post('arrangement'),
            "brand"=>(int)$this->input->post('brand'),
            "unli"=>(int)$this->input->post('unli'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('menu_cat_id')){
            $result = $this->site_model->get_tbl('menu_categories',array('arrangement > '=>'0','arrangement'=>$this->input->post('arrangement'),'menu_cat_id !='=>$this->input->post('menu_cat_id')));
            // echo $this->site_model->db->last_query(); die();

            if($result){
                $error = "Duplicate Arrangement No.";
            }else{
                $this->menu_model->update_menu_categories($items,$this->input->post('menu_cat_id'));
                $id = $this->input->post('menu_cat_id');
                $act = 'update';
                $msg = 'Updated Menu Category  '.$this->input->post('menu_cat_name');
                $this->main_model->update_tbl('menu_categories','menu_cat_id',$items,$id);
                site_alert($msg,'success');


                if(CONSOLIDATOR){
                    $terminals = $this->manager_model->get_terminals();

                    foreach($terminals as $ctr => $vals){

                        //check if meron
                        $where = array('menu_cat_id'=>$id);
                        $chk = $this->manager_model->get_details($where,'menu_categories',$vals->terminal_code);

                        if(count($chk) > 0){
                            // $this->settings_model->update_brands_termi($items, $this->input->post('id'),$vals->terminal_code);
                            $this->menu_model->update_menu_categories($items,$this->input->post('menu_cat_id'),$vals->terminal_code);
                        }else{
                            $items = array(
                                "menu_cat_id"=>$this->input->post('menu_cat_id'),
                                "menu_cat_name"=>$this->input->post('menu_cat_name'),
                                "menu_sched_id"=>$this->input->post('menu_sched_id'),
                                "arrangement"=>(int)$this->input->post('arrangement'),
                                "brand"=>(int)$this->input->post('brand'),
                                "unli"=>(int)$this->input->post('unli'),
                                "inactive"=>(int)$this->input->post('inactive')
                            );
                            $this->menu_model->add_menu_categories($items,$vals->terminal_code);
                        }

                    }

                }

            }

        }else{
            $result = $this->site_model->get_tbl('menu_categories',array('arrangement > '=>'0','arrangement'=>$this->input->post('arrangement')));

            if($result){
                $error = "Duplicate Arrangement No.";
            }else{
                $dets = $this->manager_model->get_last_ids('menu_cat_id','menu_categories');
                if($dets){
                    $id = $dets[0]->menu_cat_id + 1;
                }else{
                    $id = 1;  
                }
                $items['menu_cat_id'] = $id;


                $this->menu_model->add_menu_categories($items);
                $act = 'add';
                $msg = 'Added  new Menu Category '.$this->input->post('menu_cat_name');
                // $items['menu_cat_id'] = $id;
                $this->main_model->add_trans_tbl('menu_categories',$items);
                site_alert($msg,'success');

                if(CONSOLIDATOR){
                    $terminals = $this->manager_model->get_terminals();

                    foreach($terminals as $ctr => $vals){
                        $this->menu_model->add_menu_categories($items,$vals->terminal_code);
                    }

                }
            }
        }
        echo json_encode(array("id"=>$id,"addOpt"=>$items['menu_cat_name'],"desc"=>$this->input->post('menu_cat_name'),"act"=>$act,'msg'=>$msg,'error'=>$error));
        // echo json_encode(array('error'=>$error));

    }
    public function search_cat_menus_form(){
        $data['code'] = menuCatSearchForm();
        $this->load->view('load',$data);
    }
    public function subcategories(){
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('menu');
        if(SHOW_NEW_SUBCATEGORY){
            $data['page_title'] = fa(' icon-book-open')."Item Types";
        }else{
            $data['page_title'] = fa(' icon-book-open')."Item Types";
        }
        if(MASTER_BUTTON_EDIT){
             $th = array('ID','Name','Inactive','');
        }else{
             $th = array('ID','Name','Inactive');
        }       
        $data['code'] = create_rtable('menu_subcategories','menu_sub_cat_id','subcategories-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'subcategoryListJs';
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
        if($this->input->post('menu_sub_cat_name')){
            $lk  =$this->input->post('menu_sub_cat_name');
            $args["(menu_subcategories.menu_sub_cat_name like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['menu_subcategories.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('menu_subcategories',$args,array(),$join,true,'menu_subcategories.*',null,null,true);
        $page = paginate('menu/get_subcategories',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('menu_subcategories',$args,array(),$join,true,'menu_subcategories.*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                foreach ($items as $res) {
                    $link = $this->make->A(fa('fa-edit fa-lg').' Edit','#',array('class'=>'btn blue btn-sm btn-outline edit','id'=>'edit-'.$res->menu_sub_cat_id,'ref'=>$res->menu_sub_cat_id,'return'=>'true'));
                    // $link = "";
                    $json[$res->menu_sub_cat_id] = array(
                        "id"=>$res->menu_sub_cat_id,   
                        "title"=>ucwords(strtolower($res->menu_sub_cat_name)),   
                        "inact"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                    $ids[] = $res->menu_sub_cat_id;
                }
            }else{
                 foreach ($items as $res) {
                    $link = "";
                    $json[$res->menu_sub_cat_id] = array(
                        "id"=>$res->menu_sub_cat_id,   
                        "title"=>ucwords(strtolower($res->menu_sub_cat_name)),   
                        "inact"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                    $ids[] = $res->menu_sub_cat_id;
                }

            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function subcategories_form($ref=null){
        $this->load->helper('dine/menu_helper');
        $this->load->model('dine/menu_model');
        $cat = array();
        if($ref != null){
            $cats = $this->menu_model->get_menu_subcategories($ref);
            $cat = $cats[0];
        }
        $this->data['code'] = makeMenuSubCategoriesForm($cat);
        $this->load->view('load',$this->data);
    }
    public function subcategories_form_db(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $items = array();
        $items = array(
            "menu_sub_cat_name"=>$this->input->post('menu_sub_cat_name'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('menu_sub_cat_id')){
            $this->menu_model->update_menu_subcategories($items,$this->input->post('menu_sub_cat_id'));
            $id = $this->input->post('menu_sub_cat_id');
            $act = 'update';
            $msg = 'Updated Menu Sub Category . '.$this->input->post('menu_sub_cat_name');
            $this->main_model->update_tbl('menu_subcategories','menu_sub_cat_id',$items,$id);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){

                    //check if meron
                    $where = array('menu_sub_cat_id'=>$this->input->post('menu_sub_cat_id'));
                    $chk = $this->manager_model->get_details($where,'menu_subcategories',$vals->terminal_code);

                    if(count($chk) > 0){
                        $this->menu_model->update_menu_subcategories($items,$this->input->post('menu_sub_cat_id'),$vals->terminal_code);
                    }else{
                        $items['menu_sub_cat_id'] = $this->input->post('menu_sub_cat_id');
                        $this->menu_model->add_menu_subcategories($items,$vals->terminal_code);
                    }

                }

            }


        }else{
            $dets = $this->manager_model->get_last_ids('menu_sub_cat_id','menu_subcategories');
            if($dets){
                $id = $dets[0]->menu_sub_cat_id + 1;
            }else{
                $id = 1;
            }
            $items['menu_sub_cat_id'] = $id;
            $this->menu_model->add_menu_subcategories($items);
            $act = 'add';
            $msg = 'Added  new Menu Sub Category '.$this->input->post('menu_sub_cat_name');
            // $items['menu_sub_cat_id'] = $id;
            $this->main_model->add_trans_tbl('menu_subcategories',$items);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    $this->menu_model->add_menu_subcategories($items,$vals->terminal_code);
                }

            }

        }
        echo json_encode(array("id"=>$id,"addOpt"=>$items['menu_sub_cat_name'],"desc"=>$this->input->post('menu_sub_cat_name'),"act"=>$act,'msg'=>$msg));
    }
    public function schedules(){
        $this->load->model('dine/menu_model');
        $this->load->helper('site/site_forms_helper');
        $menu_schedules = $this->menu_model->get_menu_schedules();
        $data = $this->syter->spawn('menu');
        $data['page_title'] = fa('icon-calendar')." Schedules";
        // $data['code'] = site_list_form("menu/schedules_form","schedules_form","Schedules",$menu_schedules,'desc',"menu_sched_id",REMOVE_MASTER_BUTTON);
        // $data['add_js'] = 'js/site_list_forms.js';
        // $this->load->view('page',$data);
        $th = array('ID','Description','Inactive','');
        $data['code'] = create_rtable('schedules_form','menu_sched_id','schedules-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'site/admin';
        $data['use_js'] = 'schedulesJs';
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $this->load->view('page',$data);

    }
    public function schedules_form($ref=null){
        $this->load->helper('dine/menu_helper');
        $this->load->model('dine/menu_model');
        $data = $this->syter->spawn('menu');
        $sch = array();
        // if($ref == null)    $ref = $this->input->post('menu_sched_id');
        if($ref != null){
            $schs = $this->menu_model->get_menu_schedules($ref);
            // echo 'REF :: '.$ref;
            if($schs){
                $sch = $schs[0];
            }
        }
        $dets = $this->menu_model->get_menu_schedule_details($ref);

        $data['code'] = makeMenuSchedulesForm($sch,$dets);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'schedulesFormJs';
        $this->load->view('page',$data);
    }
    public function menu_sched_db(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $items = array();
        $items = array("desc"=>$this->input->post('desc'),
                        "inactive"=>(int)$this->input->post('inactive')
            );
        $id = $this->input->post('menu_sched_id');
        $add = "add";
        if($id != ''){
            $this->menu_model->update_menu_schedules($items,$id);
            $add = "upd";
            $this->main_model->update_tbl('menu_schedules','menu_sched_id',$items,$id);
        }else{
            $id = $this->menu_model->add_menu_schedules($items);
            $items['menu_sched_id'] = $id;
            $this->main_model->add_trans_tbl('menu_schedules',$items);
        }

        echo json_encode(array("id"=>$id,"act"=>$add,"desc"=>$this->input->post('desc')));
    }
    public function menu_sched_details_db(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $items = array();
        $items = array("day"=>$this->input->post('day'),
                        "time_on"=>date('H:i:s',strtotime($this->input->post('time_on'))),
                        "time_off"=>date('H:i:s',strtotime($this->input->post('time_off'))),
                        "menu_sched_id"=>$this->input->post('sched_id')
                        );
        // $id = $this->input->post('sched_id');
        $day = $this->input->post('day');

        $count = $this->menu_model->validate_menu_schedule_details($this->input->post('sched_id'),$day);
        if($count == 0){
            // if($id != '')    $this->menu_model->update_menu_schedule_details($items,$id);
            // else             $this->menu_model->add_menu_schedule_details($items);
            $id = $this->menu_model->add_menu_schedule_details($items);
            $items['id'] = $id;
            $this->main_model->add_trans_tbl('menu_schedule_details',$items);
            // echo json_encode(array("msg"=>'success'));
            echo json_encode(array("msg"=>'Successfully Added',"id"=>$this->input->post('sched_id')));
        }else{
            echo json_encode(array("msg"=>'error',"id"=>$this->input->post('sched_id')));
            // echo json_encode(array("msg"=>$count));
            // echo json_encode(array("msg"=>$this->db->last_query()));
        }
    }
    public function remove_schedule_promo_details(){
        $this->load->model('dine/main_model');
        $id = $this->input->post('pr_sched_id');
        $this->menu_model->delete_menu_schedule_details($id);
        $this->main_model->delete_trans_tbl('menu_schedule_details',array('id'=>$id));
        echo json_encode(array("msg"=>'Successfully Deleted'));
    }
    public function print_excel(){
        $menus = $this->get_menus(null,false,true);
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = "Menu List";
        $title = "Menu List";
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
        $headers = array('MENU CODE','SHORT NAME','FULL NAME','CATEGORY','SUBCATEGORY','SRP');
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(55);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(10);
        $rc=1;
        // $sheet->mergeCells('A'.$rc.':F'.$rc);
        // $sheet->getCell('A'.$rc)->setValue($title);
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        // $rc++;
        // $rc++;
        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++;
        foreach ($menus as $res) {
            $sheet->getCell('A'.$rc)->setValue($res->menu_code);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue($res->menu_name);
            $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('C'.$rc)->setValue($res->menu_short_desc);
            $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('D'.$rc)->setValue($res->menu_cat_name);     
            $sheet->getStyle('D'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('E'.$rc)->setValue($res->menu_sub_cat_name);     
            $sheet->getStyle('E'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('F'.$rc)->setValue($res->cost);     
            $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
            $rc++;
        }
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }
    public function upload_excel_form(){
        $data['code'] = menuUploadForm();
        $this->load->view('load',$data);
    }
    public function upload_excel_db1(){
        $this->load->model('dine/main_model');
        $temp = $this->upload_temp('menu_excel_temp');
        if($temp['error'] == ""){
            // echo 'dasda';die();
            $now = $this->site_model->get_db_now('sql');
            $this->load->library('excel');
            $obj = PHPExcel_IOFactory::load($temp['file']);
            $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $start = 1;
            $rows = array();
            for($i=$start;$i<=$count;$i++){
                if($sheet[$i]["B"] != ""){
                    $rows[] = array(
                        "menu_code"         => $sheet[$i]["B"],
                        "full_name"         => $sheet[$i]["C"],
                        "short_name"        => $sheet[$i]["D"],
                        "category"          => $sheet[$i]["E"],
                        "menu_cat_id"       => $sheet[$i]["F"],
                        "subcategory"       => $sheet[$i]["G"],
                        "menu_sub_cat_id"   => $sheet[$i]["H"],
                        "price"             => $sheet[$i]["I"],
                    );
                }
            }
            if(count($rows) > 0){
                $dflt_schedule = 1;                
                #################################################################################################################################
                ### INACTIVE ALL
                    $this->site_model->update_tbl('menu_categories',array(),array('inactive'=>1));
                    $this->main_model->update_trans_tbl('menu_categories',array(),array('inactive'=>1));
                    $this->site_model->update_tbl('menu_subcategories',array(),array('inactive'=>1));
                    $this->main_model->update_trans_tbl('menu_subcategories',array(),array('inactive'=>1));
                    $this->site_model->update_tbl('menus',array(),array('inactive'=>1));
                    $this->main_model->update_trans_tbl('menus',array(),array('inactive'=>1));
                    $this->site_model->update_tbl('modifier_groups',array(),array('inactive'=>1));
                    $this->main_model->update_trans_tbl('modifier_groups',array(),array('inactive'=>1));
                    $this->site_model->update_tbl('modifiers',array(),array('inactive'=>1));
                    $this->main_model->update_trans_tbl('modifiers',array(),array('inactive'=>1));
                #################################################################################################################################
                ### INSERT CATEGORIES
                    $ins_categories = array();
                    foreach ($rows as $ctr => $row) {
                        if(!isset($ins_categories[$row['category']])){
                            $cat_name = $row['category'];
                            $ins_categories[$row['category']] = array(
                                'menu_cat_id' => $row['menu_cat_id'],
                                'menu_cat_name' => strtoupper($cat_name),
                                'menu_sched_id' => $dflt_schedule,
                                'reg_date'      => $now,
                            );
                        }
                    }
                    $this->site_model->add_tbl_batch('menu_categories',$ins_categories);
                    $this->main_model->add_trans_tbl_batch('menu_categories',$ins_categories);
                #################################################################################################################################
                ### INSERT SUBCATEGORIES
                    $ins_subcategories = array();
                    foreach ($rows as $ctr => $row) {
                        if(!isset($ins_subcategories[$row['subcategory']])){
                            $subcat_name = $row['subcategory'];
                            $ins_subcategories[$row['subcategory']] = array(
                                'menu_sub_cat_id' => $row['menu_sub_cat_id'],
                                'menu_sub_cat_name' => strtoupper($subcat_name),
                                'reg_date'          => $now,
                            );
                        }
                    }
                    $this->site_model->add_tbl_batch('menu_subcategories',$ins_subcategories);
                    $this->main_model->add_trans_tbl_batch('menu_subcategories',$ins_subcategories);
                #################################################################################################################################                ### GET ALL CATEGORIES AND SUBCATEGORIES
                    $result = $this->site_model->get_tbl('menu_categories',array('inactive'=>0));
                    $categories = array();
                    foreach ($result as $res) {
                        $categories[strtolower($res->menu_cat_name)] = $res;
                    }
                    $result = $this->site_model->get_tbl('menu_subcategories',array('inactive'=>0));
                    $subcategories = array();
                    foreach ($result as $res) {
                        $subcategories[strtolower($res->menu_sub_cat_name)] = $res;
                    }
                #################################################################################################################################
                ### INSERT MENUS
                    $menus = array();    
                    $last_menu_id = $this->menu_model->get_last_menu_id();
                    foreach ($rows as $ctr => $row) {
                        if(!isset($menus[$row['short_name']])){
                            $cat_id = 0;
                            if(isset($categories[strtolower($row['category'])])){
                                $cat = $categories[strtolower($row['category'])];
                                $cat_id = $cat->menu_cat_id;
                            }
                            $subcat_id = 0; 
                            if(isset($subcategories[strtolower($row['subcategory'])])){
                                $subcat = $subcategories[strtolower($row['subcategory'])];
                                $subcat_id = $subcat->menu_sub_cat_id;
                            }
                            $menus[] = array(
                                'menu_id'   => $last_menu_id,
                                'menu_code' => $row['menu_code'],
                                'menu_barcode' => $row['menu_code'],
                                'menu_name' => $row['short_name'],
                                'menu_short_desc' => $row['full_name'],
                                'menu_cat_id' => $cat_id,
                                'menu_sub_cat_id' => $subcat_id,
                                'menu_sched_id' => $dflt_schedule,
                                'cost' => $row['price'],
                                'reg_date' => $now,
                            );     

                            $last_menu_id++;                       
                        }
                    }
                    $this->site_model->add_tbl_batch('menus',$menus);
                    $this->main_model->add_trans_tbl_batch('menus',$menus);
                #################################################################################################################################
            }
            unlink($temp['file']);
        }
        else{
            site_alert($temp['error'],"error");
        }
        redirect(base_url()."menu", 'refresh'); 
    }

    //upload encrypted file
    public function upload_excel_db2(){
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/settings_model');

        $temp = $this->upload_temp('menu_excel_temp');
        if($temp['error'] == ""){
            // echo 'dasda';die();
            $now = $this->site_model->get_db_now('sql');
            $this->load->library('excel');
            $obj = PHPExcel_IOFactory::load($temp['file']);
            $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $start = 2;
            $rows = array();
            for($i=$start;$i<=$count;$i++){
                if($sheet[$i]["A"] != ""){

                    if(ENCRYPT_TXT_FILE){
                    //uncomment below to if encrypted
                        $line = explode(',', base64_decode($sheet[$i]["A"]));

                        $sheet[$i]["A"] = isset($line[0]) ? $line[0] : '';
                        $sheet[$i]["B"] = isset($line[1]) ? $line[1] : '';
                        $sheet[$i]["C"] = isset($line[2]) ? $line[2] : '';
                        $sheet[$i]["D"] = isset($line[3]) ? $line[3] : '';
                        $sheet[$i]["E"] = isset($line[4]) ? $line[4] : '';
                        $sheet[$i]["F"] = isset($line[5]) ? $line[5] : '';
                        $sheet[$i]["G"] = isset($line[6]) ? $line[6] : '';

                        $sheet[$i]["H"] = isset($line[7]) ? $line[7] : ''; //brand
                        $sheet[$i]["I"] = isset($line[8]) ? $line[8] : ''; //miaa cat
                        $sheet[$i]["J"] = isset($line[9]) ? $line[9] : ''; //no tax
                        $sheet[$i]["K"] = isset($line[10]) ? $line[10] : ''; //uom
                    //comment end
                    }

                    $rows[] = array(
                        "menu_code"         => $sheet[$i]["A"],
                        "menu_barcode"      => $sheet[$i]["B"],
                        "full_name"         => $sheet[$i]["C"],
                        "short_name"        => $sheet[$i]["D"],
                        "category"          => $sheet[$i]["E"],
                        "menu_cat_id"       => 0,
                        "subcategory"       => $sheet[$i]["F"],
                        "menu_sub_cat_id"   => 0,
                        "price"             => $sheet[$i]["G"],
                        "brand"             => $sheet[$i]["H"],
                        "miaa_cat"          => $sheet[$i]["I"],
                        "no_tax"            => $sheet[$i]["J"],
                        "uom"               => $sheet[$i]["K"],
                    );
                }
            }
            if(count($rows) > 0){
                $dflt_schedule = 0;                
                #################################################################################################################################
                ### INACTIVE ALL
                    $this->site_model->update_tbl('menu_categories',array(),array('inactive'=>1));
                    $this->main_model->update_trans_tbl('menu_categories',array(),array('inactive'=>1));
                    $this->site_model->update_tbl('menu_subcategories',array(),array('inactive'=>1));
                    $this->main_model->update_trans_tbl('menu_subcategories',array(),array('inactive'=>1));
                    $this->site_model->update_tbl('menus',array(),array('inactive'=>1));
                    $this->main_model->update_trans_tbl('menus',array(),array('inactive'=>1));
                    $this->site_model->update_tbl('modifier_groups',array(),array('inactive'=>1));
                    $this->main_model->update_trans_tbl('modifier_groups',array(),array('inactive'=>1));
                    $this->site_model->update_tbl('modifiers',array(),array('inactive'=>1));
                    $this->main_model->update_trans_tbl('modifiers',array(),array('inactive'=>1));
                    $this->site_model->update_tbl('uom',array(),array('inactive'=>1));
                    $this->main_model->update_trans_tbl('uom',array(),array('inactive'=>1));
                #################################################################################################################################
                ### INSERT CATEGORIES
                    foreach ($rows as $ctr => $row) {
                        $brand_code = $row['brand'];
                        $brand = $this->site_model->get_tbl('brands',array('brand_code'=>strtoupper($brand_code))); 

                        $brand_id = 1;
                        if($brand){
                            $brand_id = $brand[0]->id;
                        }

                        $cat_name = trim($row['category']);
                        $menu_category_exist = $this->site_model->get_tbl('menu_categories',array('menu_cat_name'=>strtoupper($cat_name),'brand'=>$brand_id,'inactive'=>0));

                        if(!$menu_category_exist && $cat_name !=''){                                                     

                            $dets = $this->manager_model->get_last_ids('menu_cat_id','menu_categories');
                            if($dets){
                                $id = $dets[0]->menu_cat_id + 1;
                            }else{
                                $id = 1;  
                            }

                            $ins_categories = array(
                                'menu_cat_id' => $id,
                                'menu_cat_name' => strtoupper($cat_name),
                                'menu_sched_id' => $dflt_schedule,
                                'reg_date'      => $now,
                                'brand'         => $brand_id,
                            );
                            $cat_id = $this->menu_model->add_menu_categories($ins_categories);
                            $this->main_model->add_trans_tbl('menu_categories',$ins_categories);

                            if(CONSOLIDATOR){
                                $terminals = $this->manager_model->get_terminals();

                                foreach($terminals as $ctr => $vals){
                                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                                    $this->menu_model->add_menu_categories($ins_categories,$vals->terminal_code);
                                }

                            }
                        }else{
                            $cat_id = $menu_category_exist[0]->menu_cat_id;
                        }

                        $subcat_name = trim($row['subcategory']);
                        $submenu_category_exist = $this->site_model->get_tbl('menu_subcategories',array('menu_sub_cat_name'=>strtoupper($subcat_name),'inactive'=>0));
                        if(!$submenu_category_exist && $subcat_name  != ''){
                            $dets = $this->manager_model->get_last_ids('menu_sub_cat_id','menu_subcategories');
                            if($dets){
                                $id = $dets[0]->menu_sub_cat_id + 1;
                            }else{
                                $id = 1;  
                            }

                            $ins_subcategories = array(
                                'menu_sub_cat_id' => $id,
                                'menu_sub_cat_name' => strtoupper($subcat_name),
                                // 'category_id' => $cat_id,
                                'reg_date'          => $now,
                            );

                            $this->menu_model->add_menu_subcategories($ins_subcategories);
                            $this->main_model->add_trans_tbl('menu_subcategories',$ins_subcategories);

                            if(CONSOLIDATOR){
                                $terminals = $this->manager_model->get_terminals();

                                foreach($terminals as $ctr => $vals){
                                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                                    $this->menu_model->add_menu_subcategories($ins_subcategories,$vals->terminal_code);
                                }

                            }
                        }

                        $uom_code = trim($row['uom']);
                        $uom_exist = $this->site_model->get_tbl('uom',array('code'=>strtoupper($uom_code),'inactive'=>0));
                        if(!$uom_exist && $uom_code  != ''){
                            // $dets = $this->manager_model->get_last_ids('menu_sub_cat_id','menu_subcategories');
                            // if($dets){
                            //     $id = $dets[0]->menu_sub_cat_id + 1;
                            // }else{
                            //     $id = 1;  
                            // }

                            $ins_uom = array(
                                // 'menu_sub_cat_id' => $id,
                                'code' => strtoupper($uom_code),
                                'name' => strtoupper($uom_code),
                                // 'reg_date'          => $now,
                            );

                            $this->settings_model->add_uom($ins_uom);
                            $this->main_model->add_trans_tbl('uom',$ins_uom);

                            if(CONSOLIDATOR){
                                $terminals = $this->manager_model->get_terminals();

                                foreach($terminals as $ctr => $vals){
                                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                                    $this->settings_model->add_uom($ins_uom,$vals->terminal_code);
                                }

                            }
                        }
                        
                    }
      
                #################################################################################################################################                ### GET ALL CATEGORIES AND SUBCATEGORIES
                    $result = $this->site_model->get_tbl('menu_categories',array('inactive'=>0));
                    $categories = array();
                    foreach ($result as $res) {
                        $categories[strtolower($res->menu_cat_name)] = $res;
                    }
                    $result = $this->site_model->get_tbl('menu_subcategories',array('inactive'=>0));
                    $subcategories = array();
                    foreach ($result as $res) {
                        $subcategories[strtolower($res->menu_sub_cat_name)] = $res;
                    }

                    $result = $this->site_model->get_tbl('brands',array('inactive'=>0));
                    $brands = array();
                    foreach ($result as $res) {
                        $brands[strtolower($res->brand_code)] = $res;
                    }

                    $result = $this->site_model->get_tbl('uom',array('inactive'=>0));
                    $uoms = array();
                    foreach ($result as $res) {
                        $uoms[strtolower($res->code)] = $res;
                    }
                #################################################################################################################################
                ### INSERT MENUS
                    $menus = array();    
                    $last_menu_id = $this->menu_model->get_last_menu_id();
                    foreach ($rows as $ctr => $row) {
                        if(!isset($menus[$row['short_name']])){
                            $cat_id = 0;
                            if(isset($categories[strtolower($row['category'])])){
                                $cat = $categories[strtolower($row['category'])];
                                $cat_id = $cat->menu_cat_id;
                            }
                            $subcat_id = 0; 
                            if(isset($subcategories[strtolower($row['subcategory'])])){
                                $subcat = $subcategories[strtolower($row['subcategory'])];
                                $subcat_id = $subcat->menu_sub_cat_id;
                            }

                            $brand_id = 1;
                            if(isset($brands[strtolower($row['brand'])])){
                                $brand = $brands[strtolower($row['brand'])];
                                $brand_id = $brand->id;
                            }

                            $uom_id = 0;
                            if(isset($uoms[strtolower($row['uom'])])){
                                $uom = $uoms[strtolower($row['uom'])];
                                $uom_id = $uom->id;
                            }

                            $last_menu_id++;
                            
                            $menus[] = array(
                                'menu_id'   => $last_menu_id,
                                'menu_code' => $row['menu_code'],
                                'menu_barcode' => $row['menu_barcode'],
                                'menu_name' => $row['short_name'],
                                'menu_short_desc' => $row['full_name'],
                                'menu_cat_id' => $cat_id,
                                'menu_sub_cat_id' => $subcat_id,
                                'menu_sched_id' => $dflt_schedule,
                                'cost' => $row['price'],
                                'reg_date' => $now,
                                'brand'=>$brand_id,
                                'miaa_cat'=>$row['miaa_cat'],
                                'no_tax'=>$row['no_tax'],
                                'uom_id'=>$uom_id
                            ); 
                                                   
                        }
                    }
                    $this->site_model->add_tbl_batch('menus',$menus);
                    $this->main_model->add_trans_tbl_batch('menus',$menus);
                #################################################################################################################################
            }
            unlink($temp['file']);
        }
        else{
            site_alert($temp['error'],"error");
        }
        redirect(base_url()."menu", 'refresh'); 
    }

    public function upload_excel_db(){
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/settings_model');

        $temp = $this->upload_temp('menu_excel_temp');
        if($temp['error'] == ""){
            // echo 'dasda';die();
            $now = $this->site_model->get_db_now('sql');
            $this->load->library('excel');
            $obj = PHPExcel_IOFactory::load($temp['file']);
            $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $start = 2;
            $rows = array();
            $menu_ids = array();
            $menu_dup = false;
            for($i=$start;$i<=$count;$i++){
                if($sheet[$i]["A"] != ""){

                    if(ENCRYPT_TXT_FILE){
                    //uncomment below to if encrypted
                        $line = explode(',', base64_decode($sheet[$i]["A"]));

                        $sheet[$i]["A"] = isset($line[0]) ? $line[0] : '';
                        $sheet[$i]["B"] = isset($line[1]) ? $line[1] : '';
                        $sheet[$i]["C"] = isset($line[2]) ? $line[2] : '';
                        $sheet[$i]["D"] = isset($line[3]) ? $line[3] : '';
                        $sheet[$i]["E"] = isset($line[4]) ? $line[4] : '';
                        $sheet[$i]["F"] = isset($line[5]) ? $line[5] : '';
                        $sheet[$i]["G"] = isset($line[6]) ? $line[6] : '';

                        $sheet[$i]["H"] = isset($line[7]) ? $line[7] : ''; //brand
                        $sheet[$i]["I"] = isset($line[8]) ? $line[8] : ''; //miaa cat
                        $sheet[$i]["J"] = isset($line[9]) ? $line[9] : ''; //no tax
                        $sheet[$i]["K"] = isset($line[10]) ? $line[10] : ''; //uom
                        $sheet[$i]["L"] = isset($line[11]) ? $line[11] : ''; //menu_id
                        $sheet[$i]["M"] = isset($line[12]) ? $line[12] : ''; //cat_id
                        $sheet[$i]["N"] = isset($line[13]) ? $line[13] : ''; //sub_cat_id
                        $sheet[$i]["O"] = isset($line[14]) ? $line[14] : ''; //uom_id
                    //comment end
                    }

                    $rows[] = array(
                        "menu_code"         => $sheet[$i]["A"],
                        "menu_barcode"      => $sheet[$i]["B"],
                        "full_name"         => $sheet[$i]["C"],
                        "short_name"        => $sheet[$i]["D"],
                        "category"          => $sheet[$i]["E"],
                        "menu_cat_id"       => 0,
                        "subcategory"       => $sheet[$i]["F"],
                        "menu_sub_cat_id"   => 0,
                        "price"             => $sheet[$i]["G"],
                        "brand"             => $sheet[$i]["H"],
                        "miaa_cat"          => $sheet[$i]["I"],
                        "no_tax"            => $sheet[$i]["J"],
                        "uom"               => $sheet[$i]["K"],
                        "menu_id"           => $sheet[$i]["L"],
                        "menu_cat_id"       => $sheet[$i]["M"],
                        "menu_sub_cat_id"   => $sheet[$i]["N"],
                        "uom_id"            => $sheet[$i]["O"],
                    );

                    if(in_array($sheet[$i]["L"], $menu_ids)){
                        $menu_dup = true;
                    }else{
                        $menu_ids[] = $sheet[$i]["L"];
                    }
                }
            }
            if(count($rows) > 0 && !$menu_dup){
                $dflt_schedule = 0;                
                #################################################################################################################################
                ### INACTIVE ALL
                    $this->db->query('truncate table `menu_categories`');
                    $this->db->query('truncate table `menu_subcategories`');
                    $this->db->query('truncate table `menus`');
                    $this->db->query('truncate table `modifier_groups`');
                    $this->db->query('truncate table `modifiers`');
                    $this->db->query('truncate table `uom`');
                    
                    $this->load->library('Db_manager');
                    $this->main_db = $this->db_manager->get_connection('main');

                    $this->main_db->trans_start();
                        $this->main_db->query('truncate table `menu_categories`');
                        $this->main_db->query('truncate table `menu_subcategories`');
                        $this->main_db->query('truncate table `menus`');
                        $this->main_db->query('truncate table `modifier_groups`');
                        $this->main_db->query('truncate table `modifiers`');
                        $this->main_db->query('truncate table `uom`');
                    $this->main_db->trans_complete();                    
                #################################################################################################################################
                ### INSERT CATEGORIES
                    foreach ($rows as $ctr => $row) {
                        $brand_code = $row['brand'];
                        $brand = $this->site_model->get_tbl('brands',array('brand_code'=>strtoupper($brand_code))); 

                        $brand_id = 1;
                        if($brand){
                            $brand_id = $brand[0]->id;
                        }

                        $cat_name = trim($row['category']);
                        // $menu_category_exist = $this->site_model->get_tbl('menu_categories',array('menu_cat_name'=>strtoupper($cat_name),'brand'=>$brand_id,'inactive'=>0));
                        $menu_category_exist = $this->site_model->get_tbl('menu_categories',array('menu_cat_id'=>$row['menu_cat_id'],'brand'=>$brand_id,'inactive'=>0));

                        if(!$menu_category_exist && $cat_name !=''){                                                     

                            // $dets = $this->manager_model->get_last_ids('menu_cat_id','menu_categories');
                            // if($dets){
                            //     $id = $dets[0]->menu_cat_id + 1;
                            // }else{
                            //     $id = 1;  
                            // }

                            $ins_categories = array(
                                // 'menu_cat_id' => $id,
                                'menu_cat_id'   => $row['menu_cat_id'],
                                'menu_cat_name' => strtoupper($cat_name),
                                'menu_sched_id' => $dflt_schedule,
                                'reg_date'      => $now,
                                'brand'         => $brand_id,
                            );
                            $cat_id = $this->menu_model->add_menu_categories($ins_categories);
                            $this->main_model->add_trans_tbl('menu_categories',$ins_categories);

                            if(CONSOLIDATOR){
                                $terminals = $this->manager_model->get_terminals();

                                foreach($terminals as $ctr => $vals){
                                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                                    $this->menu_model->add_menu_categories($ins_categories,$vals->terminal_code);
                                }

                            }
                        }else{
                            $cat_id = $menu_category_exist[0]->menu_cat_id;
                        }

                        $subcat_name = trim($row['subcategory']);
                        // $submenu_category_exist = $this->site_model->get_tbl('menu_subcategories',array('menu_sub_cat_name'=>strtoupper($subcat_name),'inactive'=>0));
                        $submenu_category_exist = $this->site_model->get_tbl('menu_subcategories',array('menu_sub_cat_id'=>$row['menu_sub_cat_id'],'inactive'=>0));

                        if(!$submenu_category_exist && $subcat_name  != ''){
                            // $dets = $this->manager_model->get_last_ids('menu_sub_cat_id','menu_subcategories');
                            // if($dets){
                            //     $id = $dets[0]->menu_sub_cat_id + 1;
                            // }else{
                            //     $id = 1;  
                            // }

                            $ins_subcategories = array(
                                // 'menu_sub_cat_id' => $id,
                                'menu_sub_cat_id' => $row['menu_sub_cat_id'],
                                'menu_sub_cat_name' => strtoupper($subcat_name),
                                // 'category_id' => $cat_id,
                                'reg_date'          => $now,
                            );

                            $this->menu_model->add_menu_subcategories($ins_subcategories);
                            $this->main_model->add_trans_tbl('menu_subcategories',$ins_subcategories);

                            if(CONSOLIDATOR){
                                $terminals = $this->manager_model->get_terminals();

                                foreach($terminals as $ctr => $vals){
                                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                                    $this->menu_model->add_menu_subcategories($ins_subcategories,$vals->terminal_code);
                                }

                            }
                        }

                        $uom_code = trim($row['uom']);
                        // $uom_exist = $this->site_model->get_tbl('uom',array('code'=>strtoupper($uom_code),'inactive'=>0));
                        $uom_exist = $this->site_model->get_tbl('uom',array('id'=>$row['uom_id'],'inactive'=>0));
                        if(!$uom_exist && $uom_code  != ''){
                            // $dets = $this->manager_model->get_last_ids('menu_sub_cat_id','menu_subcategories');
                            // if($dets){
                            //     $id = $dets[0]->menu_sub_cat_id + 1;
                            // }else{
                            //     $id = 1;  
                            // }

                            $ins_uom = array(
                                'id'   => $row['uom_id'],
                                'code' => strtoupper($uom_code),
                                'name' => strtoupper($uom_code),
                                // 'reg_date'          => $now,
                            );

                            $this->settings_model->add_uom($ins_uom);
                            $this->main_model->add_trans_tbl('uom',$ins_uom);

                            if(CONSOLIDATOR){
                                $terminals = $this->manager_model->get_terminals();

                                foreach($terminals as $ctr => $vals){
                                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                                    $this->settings_model->add_uom($ins_uom,$vals->terminal_code);
                                }

                            }
                        }
                        
                    }
      
                #################################################################################################################################                ### GET ALL CATEGORIES AND SUBCATEGORIES

                    $result = $this->site_model->get_tbl('brands',array('inactive'=>0));
                    $brands = array();
                    foreach ($result as $res) {
                        $brands[strtolower($res->brand_code)] = $res;
                    }                    
                #################################################################################################################################
                ### INSERT MENUS
                    $menus = array();    
                    // $last_menu_id = $this->menu_model->get_last_menu_id();
                    foreach ($rows as $ctr => $row) {
                        if(!isset($menus[$row['short_name']])){                           
                            $brand_id = 1;
                            if(isset($brands[strtolower($row['brand'])])){
                                $brand = $brands[strtolower($row['brand'])];
                                $brand_id = $brand->id;
                            }
                            
                            $menus[] = array(
                                'menu_id'   => $row['menu_id'],
                                'menu_code' => $row['menu_code'],
                                'menu_barcode' => $row['menu_barcode'],
                                'menu_name' => $row['short_name'],
                                'menu_short_desc' => $row['full_name'],
                                'menu_cat_id' => $row['menu_cat_id'],
                                'menu_sub_cat_id' => $row['menu_sub_cat_id'],
                                'menu_sched_id' => $dflt_schedule,
                                'cost' => $row['price'],
                                'reg_date' => $now,
                                'brand'=>$brand_id,
                                'miaa_cat'=>$row['miaa_cat'],
                                'no_tax'=>$row['no_tax'],
                                'uom_id'=>$row['uom_id']
                            ); 
                                                   
                        }
                    }
                    $this->site_model->add_tbl_batch('menus',$menus);
                    $this->main_model->add_trans_tbl_batch('menus',$menus);
                #################################################################################################################################
            }else{
                site_alert('Upload failed. Duplicate menu id.',"error");
            }
            unlink($temp['file']);
        }
        else{
            site_alert($temp['error'],"error");
        }
        redirect(base_url()."menu", 'refresh'); 
    }

    public function upload_excel_string(){
        $this->load->model('dine/main_model');
        $temp = $this->upload_temp('menu_excel_temp');
        if($temp['error'] == ""){
            $now = $this->site_model->get_db_now('sql');
            $this->load->library('excel');
            $obj = PHPExcel_IOFactory::load($temp['file']);
            $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $start = 4;
            $rows = array();
            for($i=$start;$i<=$count;$i++){
                if($sheet[$i]["B"] != ""){
                    $rows[] = array(
                        "menu_code"         => $sheet[$i]["A"],
                        "short_name"        => $sheet[$i]["B"],
                        "full_name"         => $sheet[$i]["C"],
                        "category"          => $sheet[$i]["D"],
                        "subcategory"       => $sheet[$i]["E"],
                        "price"             => $sheet[$i]["F"],
                    );
                }
            }
            if(count($rows) > 0){
                $dflt_schedule = 1;                
                $query = "";
                #################################################################################################################################
                ### INACTIVE ALL
                    $query .= $this->db->update_string('menu_categories',array('inactive'=>1),array()).";\r\n";
                    $query .= $this->db->update_string('menu_subcategories',array('inactive'=>1),array()).";\r\n";
                    $query .= $this->db->update_string('menus',array('inactive'=>1),array()).";\r\n";
                    $query .= $this->db->update_string('modifier_groups',array('inactive'=>1),array()).";\r\n";
                    $query .= $this->db->update_string('modifiers',array('inactive'=>1),array()).";\r\n";
                    // echo "<pre>".$query."</pre>";
                    // return false;
                #################################################################################################################################
                ### INSERT CATEGORIES
                    $ct = array(
                        '101'=>'DIMSUM',
                        '102'=>'APPETIZER',
                        '103'=>'CONGEE ',
                        '104'=>'NOODLE SOUP',
                        '105'=>'ROASTING',
                        '106'=>'FRESH VEGETABLE',
                        '107'=>'SOUP',
                        '108'=>'HOTPOT',
                        '109'=>'CHINESE CLASSIC',
                        '110'=>'RICE/NOODLE',
                        '111'=>'DESSERT',
                        '112'=>'ADDONS',
                        '113'=>'SET MEAL(6PAX)',
                        '114'=>'DRINKS',
                        '115'=>'PROMO',
                        '116'=>'OTHERS',
                        '117'=>'SET MEAL(12PAX)',
                        '118'=>'BREAKFAST MEAL',
                        '119'=>'SET MEAL(10PAX)',
                    );
                    foreach ($ct as $id => $c) {
                        $items = array(
                            'menu_cat_id' => $id,
                            'menu_cat_name' => strtoupper($c),
                            'menu_sched_id' => $dflt_schedule,
                            'reg_date'      => $now,
                        );
                        $query .= $this->db->insert_string('menu_categories',$items).";\r\n";
                    }

                #################################################################################################################################
                ### INSERT SUBCATEGORIES
                    $sb = array(
                        '11'=>'FOOD',
                        '12'=>'BEVERAGES',
                        '13'=>'NON FOOD',
                        '14'=>'PROMO',
                        '15'=>'OTH',
                    );
                    foreach ($sb as $id => $c) {
                        $items = array(
                            'menu_sub_cat_id' => $id,
                            'menu_sub_cat_name' => strtoupper($c),
                            'reg_date'      => $now,
                        );
                        $query .= $this->db->insert_string('menu_subcategories',$items).";\r\n";
                    }
                    
                #################################################################################################################################
                ### INSERT MENUS
                    $menus = array();    
                    foreach ($rows as $ctr => $row) {
                        if(!isset($menus[$row['short_name']])){
                            $cat_id = 0;
                            foreach ($ct as $id => $c) {
                                if(strtolower($row['category']) == strtolower($c)){
                                    $cat_id = $id;
                                    break;
                                }
                            }
                            $subcat_id = 0; 
                            foreach ($sb as $id => $c) {
                                if(strtolower($row['subcategory']) == strtolower($c)){
                                    $subcat_id = $id;
                                    break;
                                }
                            }
                            $menus[$row['short_name']] = array(
                                'menu_code' => $row['menu_code'],
                                'menu_barcode' => $row['menu_code'],
                                'menu_name' => $row['short_name'],
                                'menu_short_desc' => $row['full_name'],
                                'menu_cat_id' => $cat_id,
                                'menu_sub_cat_id' => $subcat_id,
                                'menu_sched_id' => $dflt_schedule,
                                'cost' => $row['price'],
                                'reg_date' => $now,
                            );                            
                        }
                    }
                    foreach ($menus as $code => $row) {
                        $query .= $this->db->insert_string('menus',$row).";\r\n";
                    }
                    echo "<pre>".$query."</pre>";
                    return false;
                    // $this->site_model->add_tbl_batch('menus',$menus);
                    // $this->main_model->add_trans_tbl_batch('menus',$menus);
                #################################################################################################################################
            }
            unlink($temp['file']);
        }
        else{
            site_alert($temp['error'],"error");
        }
        redirect(base_url()."menu", 'refresh'); 
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
    public function menu_test(){
        $this->load->library('excel');
        $obj = PHPExcel_IOFactory::load('menu_codes_bluesmith.xlsx');
        $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
        $count = count($sheet);
        $start = 1;
        $menus = array();
        for($i=$start;$i<=$count;$i++){
            $menus[] = $sheet[$i]["A"];
        }
        $result = $this->site_model->get_tbl('menus',array('inactive'=>0));
        $db_menus = array();
        foreach ($result as $res) {
            $db_menus[] = strtolower($res->menu_code);
            
        }
        foreach ($menus as $val) {
            if(!in_array(strtolower($val),$db_menus)){
                echo $val."<br>";
            }
        }
    }
    public function menu_up(){
        $this->load->library('excel');
        $obj = PHPExcel_IOFactory::load('barcino_beverage.xlsx');
        $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
        $count = count($sheet);
        $start = 2;
        $rows = array();
        $query = "";
        $now = $this->site_model->get_db_now('sql');
        for($i=$start;$i<=$count;$i++){
            $rows[] = array(
                "menu_code"         => $sheet[$i]["A"],
                "menu_barcode"      => $sheet[$i]["B"],
                "menu_name"         => $sheet[$i]["C"],
                "menu_short_desc"   => $sheet[$i]["D"],
                "category"          => strtoupper($sheet[$i]["E"]),
                "subcategory"       => strtoupper($sheet[$i]["F"]),
                "cost"              => $sheet[$i]["G"],
            );
        }
        $categories = $this->site_model->get_tbl('menu_categories');
        $db_cats = array();
        foreach ($categories as $res) {
            $db_cats[$res->menu_cat_name] = $res->menu_cat_id;
        }
        $subcategories = $this->site_model->get_tbl('menu_subcategories');
        $db_subs = array();
        foreach ($subcategories as $res) {
            $db_subs[strtoupper($res->menu_sub_cat_name)] = $res->menu_sub_cat_id;
        }
        $menus = array();
        foreach ($rows as $ctr => $row) {
            if(!isset($menus[$row['menu_code']])){
                // $cat_id = 0;
                // foreach ($ct as $id => $c) {
                //     if(strtolower($row['category']) == strtolower($c)){
                //         $cat_id = $id;
                //         break;
                //     }
                // }
                // $subcat_id = 0; 
                // foreach ($sb as $id => $c) {
                //     if(strtolower($row['subcategory']) == strtolower($c)){
                //         $subcat_id = $id;
                //         break;
                //     }
                // }
                $cat_id = null;
                if(isset($db_cats[$row['category']]))
                    $cat_id = $db_cats[$row['category']];
                else{
                    echo "CAT - ".var_dump($row['category']);
                }
                // $subcat_id = $db_subs[$row['subcategory']];
                $subcat_id = null;
                if(isset($db_subs[$row['subcategory']]))
                    $subcat_id = $db_subs[$row['subcategory']];
                else{
                    echo "subCAT - ".var_dump($row['subcategory']);
                }

                $menus[$row['menu_code']] = array(
                    'menu_code' => $row['menu_code'],
                    'menu_barcode' => '',
                    'menu_name' => $row['menu_code'],
                    'menu_short_desc' => $row['menu_short_desc'],
                    'menu_cat_id' => $cat_id,
                    'menu_sub_cat_id' => $subcat_id,
                    'menu_sched_id' => 1,
                    'cost' => $row['cost'],
                    'reg_date' => $now,
                );            
            }
        }
        foreach ($menus as $code => $row) {
            $query .= $this->db->insert_string('menus',$row).";\r\n";
        }
        echo "<pre>".$query."</pre>";                
        
    }
    public function find_not_in($rows,$exl_col,$tbl,$tbl_col){
        $sub = array();
        foreach ($rows as $row) {
            if(!isset($sub[$row[$exl_col]]) && $row[$exl_col] != ""){
                $sub[$row[$exl_col]] = $row[$exl_col];
            }
        }
        $sub_categories = $this->site_model->get_tbl($tbl);
        $db_sub = array();
        foreach ($sub_categories as $res) {
            $db_sub[$res->$tbl_col] = $res->$tbl_col;
        }
        $not_in = array();
        foreach ($sub as $cat) {
            if(!in_array($cat, $db_sub)){
                $not_in[] = $cat;
            }
        }
        return $not_in;
    }

    //subcategories for pinlkberry
    public function subcategories_new(){
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('menu');
        $data['page_title'] = fa(' icon-book-open')."Subcategories";
        $th = array('ID','Name','Under Category','Inactive','');
        $data['code'] = create_rtable('menu_subcategory','menu_sub_id','subcategory-tbl',$th,null,false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'subcategoryListNewJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function get_subcategories_new($id=null,$asJson=true){
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
        if($this->input->post('menu_sub_name')){
            $lk  =$this->input->post('menu_sub_name');
            $args["(menu_subcategory.menu_sub_name like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['menu_subcategory.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        // $join = null;
        $join["menu_categories"] = array('content'=>"menu_subcategory.category_id = menu_categories.menu_cat_id");
        $count = $this->site_model->get_tbl('menu_subcategory',$args,array(),$join,true,'menu_subcategory.*',null,null,true);
        $page = paginate('menu/get_subcategories_new',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('menu_subcategory',$args,array(),$join,true,'menu_subcategory.*,menu_categories.menu_cat_name',null,$page['limit']);
        // echo $this->site_model->db->last_query();
        // echo "<pre>",print_r($items),"</pre>";die();
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = "";
                if(MASTER_BUTTON_EDIT){
                    $link = $this->make->A(fa('fa-edit fa-lg').' Edit','#',array('class'=>'btn blue btn-sm btn-outline edit','id'=>'edit-'.$res->menu_sub_id,'ref'=>$res->menu_sub_id,'return'=>'true'));
                }
                // $link = "";
                $json[$res->menu_sub_id] = array(
                    "id"=>$res->menu_sub_id,   
                    "title"=>ucwords(strtolower($res->menu_sub_name)),   
                    "cat"=>ucwords(strtolower($res->menu_cat_name)),   
                    "inact"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    "link"=>$link
                );
                $ids[] = $res->menu_sub_id;
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function subcategories_form_new($ref=null){
        $this->load->helper('dine/menu_helper');
        $this->load->model('dine/menu_model');
        $cat = array();
        if($ref != null){
            $cats = $this->menu_model->get_menu_subcategory($ref);
            $cat = $cats[0];
        }
        $this->data['code'] = makeMenuSubCategoriesNewForm($cat);
        $this->load->view('load',$this->data);
    }
    public function subcategories_form_new_db(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $items = array();
        // var_dump($this->input->post('inactive')); die();
        $items = array(
            "menu_sub_name"=>$this->input->post('menu_sub_name'),
            "inactive"=>(int)$this->input->post('inactive'),
            "category_id"=>(int)$this->input->post('category_id')
        );

        if($this->input->post('menu_sub_id')){
            $this->menu_model->update_menu_subcategory($items,$this->input->post('menu_sub_id'));
            $id = $this->input->post('menu_sub_id');
            $act = 'update';
            $msg = 'Updated Menu Sub Category . '.$this->input->post('menu_sub_name');
            $this->main_model->update_tbl('menu_subcategory','menu_sub_id',$items,$id);
            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){

                    //check if meron
                    $where = array('menu_sub_id'=>$this->input->post('menu_sub_id'));
                    $chk = $this->manager_model->get_details($where,'menu_subcategory',$vals->terminal_code);

                    if(count($chk) > 0){
                        $this->menu_model->update_menu_subcategory($items,$this->input->post('menu_sub_id'),$vals->terminal_code);
                    }else{
                        $items['menu_sub_id'] = $this->input->post('menu_sub_id');
                        $this->menu_model->add_menu_subcategory($items,$vals->terminal_code);
                    }

                }

            }
        }else{
            $dets = $this->manager_model->get_last_ids('menu_sub_id','menu_subcategory');
            if($dets){
                $id = $dets[0]->menu_sub_id + 1;
            }else{
                $id = 1;
            }
            $items['menu_sub_id'] = $id;
            // $items['menu_sub_cat_id'] = $id;
            $this->menu_model->add_menu_subcategory($items);
            $act = 'add';
            $msg = 'Added  new Menu Sub Category '.$this->input->post('menu_sub_name');
            $this->main_model->add_trans_tbl('menu_subcategory',$items);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    $this->menu_model->add_menu_subcategory($items,$vals->terminal_code);
                }

            }
        }
        echo json_encode(array("id"=>$id,"addOpt"=>$items['menu_sub_name'],"desc"=>$this->input->post('menu_sub_name'),"act"=>$act,'msg'=>$msg));
    }

    public function quick_edit($type=null,$sales_id=null){
            $this->load->model('site/site_model');
            $this->load->model('dine/cashier_model');
            $this->load->helper('dine/menu_helper');
            $data = $this->syter->spawn(null);
            $loaded = null;
            $order = array();
            $app_type = $type;
            $loc_res = $this->site_model->get_tbl('settings',array(),array(),null,true,'*',null,1);
            $local_tax = $loc_res[0]->local_tax;
            $kitchen_printer = "";
            if(iSetObj($loc_res[0],'kitchen_printer_name') != ""){
                $kitchen_printer = iSetObj($loc_res[0],'kitchen_printer_name');
            }

           
           

            $data['code'] = quickEditPage($type,null,$loaded);
            // $data['add_css'] = 'css/cashier.css';
            $data['add_css'] = array('css/virtual_keyboard.css', 'css/cashier.css');
            $data['add_js'] = array('js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js','js/jquery.scannerdetection.js');
            // $data['add_js'] = array('js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
            $data['load_js'] = 'dine/menu.php';
            $data['use_js'] = 'menuQuickEditJS';
            $data['noNavbar'] = true;
            $this->load->view('load',$data);
    }

    public function get_menus_search_quick_edit($cat_id=null,$item_id=null,$asJson=true){
            $this->load->model('dine/menu_model');
            $this->load->model('dine/cashier_model');
            $this->load->model('site/site_model');
            $search = $this->input->post('search');
            $ttype = $this->input->post('ttype');
            $menus = $this->menu_model->get_menus($item_id,$cat_id,false,$search);
            // echo $this->db->last_query(); die();
            $json = array();
            $now = $this->site_model->get_db_now();
            $day = strtolower(date('D',strtotime($now)));
            $time = strtolower(date('H:i:s',strtotime($now)));

            $branch_color = $this->menu_model->get_branch_color(null);
            $b_color = "";
            foreach ($branch_color as $bc => $bc_val) {
                $b_color = $bc_val->branch_color;
                $branch_text_color = $bc_val->branch_text_color;
                
            }

            if(count($menus) > 0){
                $ids = array();
                $mnschds = array();
                foreach ($menus as $res) {
                    // if($res->inactive == 0){
                        $check_reorder = 0;
                        if(CHECK_REORDER){
                            $check_reorder = 1;
                        }

                        $menu_oh = 0;
                        //get total qty sales
                        if($check_reorder == 1){
                            $joinm = array();
                            $tablem = "trans_sales_menus";
                            $selectm = "sum(trans_sales_menus.qty) as total_qty";
                            // $selectm_s = "sum(qty) as menu_sales_qty";
                            $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menus.sales_id");

                            $ordermm = null;
                            $groupmm = null;
                            $to = date('Y-m-d');
                            $args4['trans_sales.type_id'] = 10;
                            $args4['trans_sales.inactive'] = '0';
                            // $args4['trans_sales.trans_ref is not null'] = null;
                            $args4["DATE(trans_sales.datetime) = '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                            $args4['trans_sales_menus.menu_id'] = $res->menu_id;
                            // $this->site_model->db = $this->load->database('main', TRUE);
                            $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm); 
                            $qty_sales = 0;
                            if($get_menus_sales){
                                $qty_sales = $get_menus_sales[0]->total_qty;
                            }

                            // echo $qty_sales;
                            // for menu move total qty
                            $argst = array();
                            $join = array();
                            $table  = "menu_moves";
                            $select = "sum(menu_moves.qty) as total_qty_moves";
                            $argst["DATE(menu_moves.reg_date) = '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                            $argst['menu_moves.item_id'] = $res->menu_id;

                            $argst['menu_moves.inactive'] = 0;
                            $order = null;
                            $group = null;
                            
                            $this->site_model->db = $this->load->database('default', TRUE);
                            $items = $this->site_model->get_tbl($table,$argst,$order,$join,true,$select,$group);
                            $qty_moves = 0;
                            if($items){
                                $qty_moves = $items[0]->total_qty_moves;
                            }

                            $menu_oh = $qty_moves - $qty_sales;
                        }
                        //get settings if allow negative inv
                        $set = $this->cashier_model->get_pos_settings();

                        $price = $res->cost;
                        if($ttype != 'dinein' && $ttype != 'reservation'){
                            $where = array('menu_id'=>$res->menu_id,'trans_type'=>$ttype);
                            $cost_det = $this->site_model->get_details($where,'menu_prices');

                            if(count($cost_det) > 0){
                                $price = $cost_det[0]->price;
                            }
                        }

                        $json[$res->menu_id] = array(
                            "id"=>$res->menu_id,
                            "name"=>$res->menu_name,
                            "category"=>$res->menu_cat_id,
                            // "cost"=>$res->cost,
                            "cost"=>$price,
                            "no_tax"=>$res->no_tax,
                            "free"=>$res->free,
                            "sched"=>$res->menu_sched_id,
                            "menu_oh"=>$menu_oh,
                            "neg_inv"=>$set->neg_inv,
                            "reorder_qty"=>$res->reorder_qty,
                            "check_reorder"=>$check_reorder,
                            'bcolor'=>$b_color,
                            'branch_text_color'=>$branch_text_color,
                        );
                        $ids[] = $res->menu_id;
                        if($res->menu_sched_id != 0 || $res->menu_sched_id != "")
                            $mnschds[] = $res->menu_sched_id;
                    // }
                }
                $ntfnd = array();
                if(count($mnschds) > 0){
                    $args['menu_schedule_details.menu_sched_id'] = $mnschds;
                    $args['menu_schedule_details.day'] = $day;
                    $sched = $this->site_model->get_tbl('menu_schedule_details',$args);
                    $mn_sched = array();
                    foreach ($json as $menu_id => $mn) {
                        foreach ($sched as $res) {
                            if($mn['sched'] == $res->menu_sched_id){
                                $mn_sched[$menu_id] = array('time_on'=>$res->time_on,'time_off'=>$res->time_off);
                            }
                        }                           
                    }
                    $found = array();
                    if(count($mn_sched) > 0){
                        foreach ($mn_sched as $menu_id => $mnsc) {
                            $tm  = strtotime($time);
                            $stm = strtotime($mnsc['time_on']);
                            $etm = strtotime($mnsc['time_off']);
                            if($etm > $stm ){
                                if($tm <= $stm){
                                    unset($json[$menu_id]);
                                    
                                }   
                                if($tm >= $etm){
                                    unset($json[$menu_id]);
                                                                             
                                }
                            }
                            $found[] = $menu_id;
                        }
                    }
                    if(count($mn_sched) > 0){
                        foreach ($json as $menu_id => $mn) {
                            if($mn['sched'] != "" && $mn['sched'] != 0){
                               if(!in_array($menu_id,$found)){
                                unset($json[$menu_id]);
                               }
                            }
                        }
                    }
                }  
                $promos = $this->cashier_model->get_menu_promos($ids,$ttype);
                $prs = array();
                $prm = array();
                foreach ($promos as $pr) {
                    $prs[] = $pr->promo_id;
                    $prm[$pr->item_id][] = array('id'=>$pr->promo_id,'val'=>$pr->value,'abs'=>(int)$pr->absolute);
                }
                $time = $this->site_model->get_db_now();
                $day = strtolower(date('D',strtotime($time)));
                $sched = $this->cashier_model->get_menu_promo_schedule($prs,$day,date2SqlDateTime($time));
                $schs = array();
                foreach ($sched as $sc) {
                    $schs[] = $sc->promo_id;
                }
                foreach ($json as $menu_id => $opt) {
                    if(isset($prm[$menu_id])){
                        foreach ($prm[$menu_id] as $p) {
                            if(in_array($p['id'], $schs)){
                                if($p['abs'] == 1){
                                    $opt['cost'] -= $pr->value;
                                }
                                else{
                                    $opt['cost'] -=  ($pr->value / 100) * $opt['cost'];
                                }
                                $json[$menu_id] = $opt;
                                break;
                            }
                        }####
                    }
                }
            }
            usort($json, function($a, $b) {
                return strcmp($a["name"], $b["name"]);
            });
            echo json_encode($json);
        }

    public function get_menus_quick_edit($cat_id=null,$item_id=null,$asJson=true){
            $this->load->model('dine/menu_model');
            $this->load->model('dine/cashier_model');
            $this->load->model('site/site_model');
            $ttype = $this->input->post('ttype');
            $branch_color = $this->menu_model->get_branch_color(null);
            $b_color = "";
            foreach ($branch_color as $bc => $bc_val) {
                $b_color = $bc_val->branch_color;
                if(empty($bc_val->branch_color)){
                    $branch_text_color = '#333';
                }else{
                    $branch_text_color = $bc_val->branch_text_color;
                }
            }
            // echo $ttype; die();
            // if(SHOW_NEW_SUBCATEGORY){
            //     $menus = $this->menu_model->get_menus_new($item_id,$cat_id,false);
            // }else{
                $menus = $this->menu_model->get_menus($item_id,$cat_id,false);
            // }
            $now = $this->site_model->get_db_now();
            $day = strtolower(date('D',strtotime($now)));
            $time = strtolower(date('H:i:s',strtotime($now)));
            $json = array();
            if(count($menus) > 0){
                $ids = array();
                $mnschds = array();
                foreach ($menus as $res) {
                    $check_reorder = 0;
                    if(CHECK_REORDER){
                        $check_reorder = 1;
                    }

                    $menu_oh = 0;
                    if($check_reorder == 1){
                        //get total qty sales
                        $joinm = array();
                        $tablem = "trans_sales_menus";
                        $selectm = "sum(trans_sales_menus.qty) as total_qty";
                        // $selectm_s = "sum(qty) as menu_sales_qty";
                        $joinm["trans_sales"] = array('content'=>"trans_sales.sales_id = trans_sales_menus.sales_id");

                        $ordermm = null;
                        $groupmm = null;
                        $to = date('Y-m-d');
                        $args4['trans_sales.type_id'] = 10;
                        $args4['trans_sales.inactive'] = '0';
                        // $args4['trans_sales.trans_ref is not null'] = null;
                        $args4["DATE(trans_sales.datetime) = '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                        $args4['trans_sales_menus.menu_id'] = $res->menu_id;
                        // $this->site_model->db = $this->load->database('main', TRUE);
                        $get_menus_sales = $this->site_model->get_tbl($tablem,$args4,$ordermm,$joinm,true,$selectm,$groupmm); 
                        $qty_sales = 0;
                        if($get_menus_sales){
                            $qty_sales = $get_menus_sales[0]->total_qty;
                        }

                        // echo $qty_sales;
                        // for menu move total qty
                        $argst = array();
                        $join = array();
                        $table  = "menu_moves";
                        $select = "sum(menu_moves.qty) as total_qty_moves";
                        $argst["DATE(menu_moves.reg_date) = '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                        $argst['menu_moves.item_id'] = $res->menu_id;

                        $argst['menu_moves.inactive'] = 0;
                        $order = null;
                        $group = null;
                        
                        $this->site_model->db = $this->load->database('default', TRUE);
                        $items = $this->site_model->get_tbl($table,$argst,$order,$join,true,$select,$group);
                        $qty_moves = 0;
                        if($items){
                            $qty_moves = $items[0]->total_qty_moves;
                        }

                        $menu_oh = $qty_moves - $qty_sales;
                    }

                    //get settings if allow negative inv
                    $set = $this->cashier_model->get_pos_settings();

                    // $this->site_model->db = $this->load->database('default', TRUE);
                    if($res->img_path){
                        $img_path = $res->img_path;
                    }else{
                        $img_path = 'uploads/menus/noimage.png';
                    }

                    $price = $res->cost;
                    if($ttype != 'dinein' && $ttype != 'reservation'){
                        $where = array('menu_id'=>$res->menu_id,'trans_type'=>$ttype);
                        $cost_det = $this->site_model->get_details($where,'menu_prices');

                        if(count($cost_det) > 0){
                            $price = $cost_det[0]->price;
                        }
                    }

                    $json[$res->menu_id] = array(
                        "id"=>$res->menu_id,
                        "name"=>$res->menu_name,
                        "category"=>$res->menu_cat_id,
                        // "cost"=>$res->cost,
                        "cost"=>$price,
                        "no_tax"=>$res->no_tax,
                        "free"=>$res->free,
                        "sched"=>$res->menu_sched_id,
                        "menu_oh"=>$menu_oh,
                        "neg_inv"=>$set->neg_inv,
                        "reorder_qty"=>$res->reorder_qty,
                        "check_reorder"=>$check_reorder,
                        "img_path"=>$img_path,
                        "bcolor"=>$b_color,
                        'branch_text_color'=>$branch_text_color
                    );

                    // echo "<pre>",print_r($json),"</pre>";

                    $ids[] = $res->menu_id;
                    if($res->menu_sched_id != 0 || $res->menu_sched_id != "")
                        $mnschds[] = $res->menu_sched_id;
                }
                #########################
                ### SCHEDULE
                #########################
                    $ntfnd = array();
                    if(count($mnschds) > 0){
                        $args['menu_schedule_details.menu_sched_id'] = $mnschds;
                        $args['menu_schedule_details.day'] = $day;
                        $sched = $this->site_model->get_tbl('menu_schedule_details',$args);
                        $mn_sched = array();
                        foreach ($json as $menu_id => $mn) {
                            foreach ($sched as $res) {
                                if($mn['sched'] == $res->menu_sched_id){
                                    $mn_sched[$menu_id] = array('time_on'=>$res->time_on,'time_off'=>$res->time_off);
                                }
                            }                           
                        }
                        $found = array();
                        if(count($mn_sched) > 0){
                            foreach ($mn_sched as $menu_id => $mnsc) {
                                $tm  = strtotime($time);
                                $stm = strtotime($mnsc['time_on']);
                                $etm = strtotime($mnsc['time_off']);
                                if($etm > $stm ){
                                    if($tm <= $stm){
                                        unset($json[$menu_id]);
                                        
                                    }   
                                    if($tm >= $etm){
                                        unset($json[$menu_id]);
                                                                                 
                                    }
                                }
                                $found[] = $menu_id;
                            }
                        }
                        if(count($mn_sched) > 0){
                            foreach ($json as $menu_id => $mn) {
                                if($mn['sched'] != "" && $mn['sched'] != 0){
                                   if(!in_array($menu_id,$found)){
                                    unset($json[$menu_id]);
                                   }
                                }
                            }
                        }
                    }                    
                #########################
                ### PROMO
                #########################
                    $promos = $this->cashier_model->get_menu_promos($ids,$ttype);
                    $prs = array();
                    $prm = array();
                    foreach ($promos as $pr) {
                        $prs[] = $pr->promo_id;
                        $prm[$pr->item_id][] = array('id'=>$pr->promo_id,'val'=>$pr->value,'abs'=>(int)$pr->absolute);
                    }
                    $time = $this->site_model->get_db_now();
                    $day = strtolower(date('D',strtotime($time)));
                    $sched = $this->cashier_model->get_menu_promo_schedule($prs,$day,date2SqlDateTime($time));
                    $schs = array();
                    foreach ($sched as $sc) {
                        $schs[] = $sc->promo_id;
                    }
                    foreach ($json as $menu_id => $opt) {
                        if(isset($prm[$menu_id])){
                            foreach ($prm[$menu_id] as $p) {
                                if(in_array($p['id'], $schs)){
                                    if($p['abs'] == 1){
                                        $opt['cost'] -= $pr->value;
                                    }
                                    else{
                                        $opt['cost'] -=  ($pr->value / 100) * $opt['cost'];
                                    }
                                    $json[$menu_id] = $opt;
                                    break;
                                }
                            }####
                        }
                    }
            }
            usort($json, function($a, $b) {
                return strcmp($a["name"], $b["name"]);
            });

            $setting = $this->cashier_model->get_pos_settings();
            // echo "<pre>",print_r($json),"</pre>";die();
            echo json_encode(array('json'=>$json,'show_img'=>$setting->show_image));
        }

     public function menu_quick_edit_form($menu_id){
            $this->load->model('site/site_model');
            $this->load->model('dine/menu_model');
            $this->load->helper('dine/menu_helper');
            $data = $this->syter->spawn(null);
            
            $menu = $this->menu_model->get_menus($menu_id);
          
           
            $data['code'] = quickEditForm($menu[0]);
            $data['add_css'] = 'css/cashier.css';
           
            $data['load_js'] = 'dine/menu.php';
            $data['use_js'] = '';
            $data['noNavbar'] = true;
            $this->load->view('load',$data);
    }

    public function menu_quick_edit_db(){
            $qty = $this->input->post('qty');
            $items = array(            
                "inactive"=>(int)$this->input->post('inactive'),
                "unavailable"=>(int)$this->input->post('unavailable'),
            );

            if((int)$this->input->post('set-qty') && is_numeric($qty)){
                $items["menu_qty"]= (int) $qty; 
            }

        $this->menu_model->update_menus($items,$this->input->post('form_menu_id'));
    }

    public function download_menus(){
        $joinTables["menu_categories"] = array('content'=>"menus.menu_cat_id = menu_categories.menu_cat_id");
        $joinTables["menu_subcategories"] = array('content'=>"menus.menu_sub_cat_id = menu_subcategories.menu_sub_cat_id",'mode'=>'LEFT');

        // $select = 'menu_id, menu_code,menu_name,menu_short_desc,menu_cat_name,menus.menu_cat_id,menu_sub_cat_name, menus.menu_sub_cat_id,cost,menus.brand';
        // $headers = array(array('Menu ID','Menu Code','Menu Name','Menu Short Description','Category','Category ID','Sub Category(Food,Beverage,Pastry)','Sub Category ID','Cost','Brand'));

        $select = 'menu_code,menu_name,if(no_tax=0,"Y","N") as no_tax,menu_barcode';
        // $headers = array(array('Menu Code','Menu Name','Vatable','Barcode'));
        $headers = array('Menu Code','Menu Name','Vatable','Barcode');
        
        $items = $this->site_model->get_tbl('menus',array(),array('menus.menu_id'=>'asc'),$joinTables,true,$select);
        
        
        $file_name  = 'uploads/txtfile/menus.csv';


        $fp = fopen($file_name, 'w+');
        foreach ($headers as $fields) {
            fputcsv($fp, $fields);
        }
        fputcsv($fp, array(base64_encode(implode(',',$headers))));

        foreach ($items as $fields) {
            // $fields = (array) $fields;
            // fputcsv($fp, $fields);

            // $fields = array(
            //                 base64_encode($fields->menu_code),
            //                 base64_encode($fields->menu_name),
            //                 base64_encode($fields->no_tax),
            //                 base64_encode($fields->menu_barcode),
            //             );
            // fputcsv($fp, $fields);

            $fields = array(
                            $fields->menu_code,
                            $fields->menu_name,
                            $fields->no_tax,
                            $fields->menu_barcode,
                        );
            fputcsv($fp, array(base64_encode(implode(',', $fields))));
        } 


        fclose($fp); 

        header("Location:" . base_url().$file_name);
    }
    public function get_schedules($id=null,$asJson=true){
        $post = array();
        $page = "";
        $joinTables = array();
        $select = 'menu_schedules.*';
        $items = $this->site_model->get_tbl('menu_schedules',array(),array('menu_schedules.menu_sched_id'=>'desc'),$joinTables,true,$select);
        // echo "<pre>",print_r($items),"</pre>";die();
        $json = array();
        if(count($items) > 0){
            foreach ($items as $res) {
            $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'menu/schedules_form/'.$res->menu_sched_id,array('class'=>'btn btn-sm blue btn-outline','return'=>'true')); 
                $json[$res->menu_sched_id] = array(  
                    "id"=>$res->menu_sched_id,   
                    "desc"=>$res->desc,
                    "status"=>($res->inactive == 0 ? 'No' : 'Yes'),
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

    public function update_upload_excel_form(){
        $data['code'] = menuUpdateUploadForm();
        $this->load->view('load',$data);
    }

    public function update_upload_excel_db(){
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/settings_model');

        $temp = $this->upload_temp('menu_excel_temp');
        if($temp['error'] == ""){
            // echo 'dasda';die();
            $now = $this->site_model->get_db_now('sql');
            $this->load->library('excel');
            $obj = PHPExcel_IOFactory::load($temp['file']);
            $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $start = 2;
            $rows = array();
            for($i=$start;$i<=$count;$i++){
                if($sheet[$i]["A"] != ""){

                    if(ENCRYPT_TXT_FILE){
                    //uncomment below to if encrypted
                        $line = explode(',', base64_decode($sheet[$i]["A"]));

                        $sheet[$i]["A"] = isset($line[0]) ? $line[0] : '';
                        $sheet[$i]["B"] = isset($line[1]) ? $line[1] : '';
                        $sheet[$i]["C"] = isset($line[2]) ? $line[2] : '';
                        $sheet[$i]["D"] = isset($line[3]) ? $line[3] : '';
                        $sheet[$i]["E"] = isset($line[4]) ? $line[4] : '';
                        $sheet[$i]["F"] = isset($line[5]) ? $line[5] : '';
                        $sheet[$i]["G"] = isset($line[6]) ? $line[6] : '';

                        $sheet[$i]["H"] = isset($line[7]) ? $line[7] : ''; //brand
                        $sheet[$i]["I"] = isset($line[8]) ? $line[8] : ''; //miaa cat
                        $sheet[$i]["J"] = isset($line[9]) ? $line[9] : ''; //no tax
                        $sheet[$i]["K"] = isset($line[10]) ? $line[10] : ''; //uom
                        $sheet[$i]["L"] = isset($line[11]) ? $line[11] : ''; //menu_id
                    //comment end
                    }

                    $rows[] = array(
                        "menu_code"         => $sheet[$i]["A"],
                        "menu_barcode"      => $sheet[$i]["B"],
                        "full_name"         => $sheet[$i]["C"],
                        "short_name"        => $sheet[$i]["D"],
                        "category"          => $sheet[$i]["E"],
                        "menu_cat_id"       => 0,
                        "subcategory"       => $sheet[$i]["F"],
                        "menu_sub_cat_id"   => 0,
                        "price"             => $sheet[$i]["G"],
                        "brand"             => $sheet[$i]["H"],
                        "miaa_cat"          => $sheet[$i]["I"],
                        "no_tax"            => $sheet[$i]["J"],
                        "uom"               => $sheet[$i]["K"],
                        "menu_id"           => $sheet[$i]["L"],
                    );
                }
            }
            if(count($rows) > 0){
                $dflt_schedule = 0;                
               
                ### INSERT CATEGORIES
                    foreach ($rows as $ctr => $row) {
                        $brand_code = $row['brand'];
                        $brand = $this->site_model->get_tbl('brands',array('brand_code'=>strtoupper($brand_code))); 

                        $brand_id = 1;
                        if($brand){
                            $brand_id = $brand[0]->id;
                        }

                        $cat_name = trim($row['category']);
                        $menu_category_exist = $this->site_model->get_tbl('menu_categories',array('menu_cat_name'=>strtoupper($cat_name),'brand'=>$brand_id,'inactive'=>0));

                        if(!$menu_category_exist && $cat_name !=''){                                                     

                            $dets = $this->manager_model->get_last_ids('menu_cat_id','menu_categories');
                            if($dets){
                                $id = $dets[0]->menu_cat_id + 1;
                            }else{
                                $id = 1;  
                            }

                            $ins_categories = array(
                                'menu_cat_id' => $id,
                                'menu_cat_name' => strtoupper($cat_name),
                                'menu_sched_id' => $dflt_schedule,
                                'reg_date'      => $now,
                                'brand'         => $brand_id,
                            );
                            $cat_id = $this->menu_model->add_menu_categories($ins_categories);
                            $this->main_model->add_trans_tbl('menu_categories',$ins_categories);

                            if(CONSOLIDATOR){
                                $terminals = $this->manager_model->get_terminals();

                                foreach($terminals as $ctr => $vals){
                                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                                    $this->menu_model->add_menu_categories($ins_categories,$vals->terminal_code);
                                }

                            }
                        }else{
                            $cat_id = $menu_category_exist[0]->menu_cat_id;
                        }

                        $subcat_name = trim($row['subcategory']);
                        $submenu_category_exist = $this->site_model->get_tbl('menu_subcategories',array('menu_sub_cat_name'=>strtoupper($subcat_name),'inactive'=>0));
                        if(!$submenu_category_exist && $subcat_name  != ''){
                            $dets = $this->manager_model->get_last_ids('menu_sub_cat_id','menu_subcategories');
                            if($dets){
                                $id = $dets[0]->menu_sub_cat_id + 1;
                            }else{
                                $id = 1;  
                            }

                            $ins_subcategories = array(
                                'menu_sub_cat_id' => $id,
                                'menu_sub_cat_name' => strtoupper($subcat_name),
                                // 'category_id' => $cat_id,
                                'reg_date'          => $now,
                            );

                            $this->menu_model->add_menu_subcategories($ins_subcategories);
                            $this->main_model->add_trans_tbl('menu_subcategories',$ins_subcategories);

                            if(CONSOLIDATOR){
                                $terminals = $this->manager_model->get_terminals();

                                foreach($terminals as $ctr => $vals){
                                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                                    $this->menu_model->add_menu_subcategories($ins_subcategories,$vals->terminal_code);
                                }

                            }
                        }

                        $uom_code = trim($row['uom']);
                        $uom_exist = $this->site_model->get_tbl('uom',array('code'=>strtoupper($uom_code),'inactive'=>0));
                        if(!$uom_exist && $uom_code  != ''){
                            // $dets = $this->manager_model->get_last_ids('menu_sub_cat_id','menu_subcategories');
                            // if($dets){
                            //     $id = $dets[0]->menu_sub_cat_id + 1;
                            // }else{
                            //     $id = 1;  
                            // }

                            $ins_uom = array(
                                // 'menu_sub_cat_id' => $id,
                                'code' => strtoupper($uom_code),
                                'name' => strtoupper($uom_code),
                                // 'reg_date'          => $now,
                            );

                            $this->settings_model->add_uom($ins_uom);
                            $this->main_model->add_trans_tbl('uom',$ins_uom);

                            if(CONSOLIDATOR){
                                $terminals = $this->manager_model->get_terminals();

                                foreach($terminals as $ctr => $vals){
                                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                                    $this->settings_model->add_uom($ins_uom,$vals->terminal_code);
                                }

                            }
                        }
                        
                    }
      
                #################################################################################################################################                ### GET ALL CATEGORIES AND SUBCATEGORIES
                    $result = $this->site_model->get_tbl('menu_categories',array('inactive'=>0));
                    $categories = array();
                    foreach ($result as $res) {
                        $categories[strtolower($res->menu_cat_name)] = $res;
                    }
                    $result = $this->site_model->get_tbl('menu_subcategories',array('inactive'=>0));
                    $subcategories = array();
                    foreach ($result as $res) {
                        $subcategories[strtolower($res->menu_sub_cat_name)] = $res;
                    }

                    $result = $this->site_model->get_tbl('brands',array('inactive'=>0));
                    $brands = array();
                    foreach ($result as $res) {
                        $brands[strtolower($res->brand_code)] = $res;
                    }

                    $result = $this->site_model->get_tbl('uom',array('inactive'=>0));
                    $uoms = array();
                    foreach ($result as $res) {
                        $uoms[strtolower($res->code)] = $res;
                    }
                #################################################################################################################################
                ### INSERT MENUS
                    $menus = array();    
                    $last_menu_id = $this->menu_model->get_last_menu_id();
                    foreach ($rows as $ctr => $row) {
                        if(!isset($menus[$row['short_name']])){
                            $cat_id = 0;
                            if(isset($categories[strtolower($row['category'])])){
                                $cat = $categories[strtolower($row['category'])];
                                $cat_id = $cat->menu_cat_id;
                            }
                            $subcat_id = 0; 
                            if(isset($subcategories[strtolower($row['subcategory'])])){
                                $subcat = $subcategories[strtolower($row['subcategory'])];
                                $subcat_id = $subcat->menu_sub_cat_id;
                            }

                            $brand_id = 1;
                            if(isset($brands[strtolower($row['brand'])])){
                                $brand = $brands[strtolower($row['brand'])];
                                $brand_id = $brand->id;
                            }

                            $uom_id = 0;
                            if(isset($uoms[strtolower($row['uom'])])){
                                $uom = $uoms[strtolower($row['uom'])];
                                $uom_id = $uom->id;
                            }

                            if($row['menu_id'] == '' || $row['menu_id'] == 0){
                                $last_menu_id++;
                            
                                $menus[] = array(
                                    'menu_id'   => $last_menu_id,
                                    'menu_code' => $row['menu_code'],
                                    'menu_barcode' => $row['menu_barcode'],
                                    'menu_name' => $row['short_name'],
                                    'menu_short_desc' => $row['full_name'],
                                    'menu_cat_id' => $cat_id,
                                    'menu_sub_cat_id' => $subcat_id,
                                    'menu_sched_id' => $dflt_schedule,
                                    'cost' => $row['price'],
                                    'reg_date' => $now,
                                    'brand'=>$brand_id,
                                    'miaa_cat'=>$row['miaa_cat'],
                                    'no_tax'=>$row['no_tax'],
                                    'uom_id'=>$uom_id
                                ); 
                            }else{                            
                                $items = array(
                                    'menu_code' => $row['menu_code'],
                                    'menu_barcode' => $row['menu_barcode'],
                                    'menu_name' => $row['short_name'],
                                    'menu_short_desc' => $row['full_name'],
                                    'menu_cat_id' => $cat_id,
                                    'menu_sub_cat_id' => $subcat_id,
                                    'menu_sched_id' => $dflt_schedule,
                                    'cost' => $row['price'],
                                    'reg_date' => $now,
                                    'brand'=>$brand_id,
                                    'miaa_cat'=>$row['miaa_cat'],
                                    'no_tax'=>$row['no_tax'],
                                    'uom_id'=>$uom_id
                                ); 

                                $this->menu_model->update_menus($items,$row['menu_id']);
                                $this->main_model->update_tbl('menus','menu_id',$items,$row['menu_id']);
                                
                            }
                            
                                                   
                        }
                    }

                    if($menus){
                        $this->site_model->add_tbl_batch('menus',$menus);
                        $this->main_model->add_trans_tbl_batch('menus',$menus);   
                    }
                    
                #################################################################################################################################
            }

            site_alert('Menu(s) successfully updated.','success');
            // echo json_encode(array("id"=>'',"addOpt"=>'',"desc"=>'',"act"=>'update','msg'=>"Menu(s) successfully updated."));
            unlink($temp['file']);
        }
        else{
            site_alert($temp['error'],"error");
        }
        redirect(base_url()."menu", 'refresh'); 
    }
}