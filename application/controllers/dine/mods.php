<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mods extends CI_Controller {
	public function index(){
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('mods');
        $data['page_title'] = fa('icon-book-open')." Modifiers";
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Cost','Inactive','');
        }else{
            $th = array('ID','Name','Cost','Inactive');            
        }   
        $data['code'] = create_rtable('modifiers','mod_id','modifiers-tbl',$th,'mods/search_modifiers_form',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'modsListFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function get_modifiers($id=null,$asJson=true){
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
            $args["(modifiers.name like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['modifiers.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('modifiers',$args,array(),$join,true,'modifiers.*',null,null,true);
        $page = paginate('mods/get_modifiers',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('modifiers',$args,array(),$join,true,'modifiers.*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                foreach ($items as $res) {
                    $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'mods/form/'.$res->mod_id,array('class'=>'btn btn-sm blue btn-outline edit','id'=>'edit-'.$res->mod_id,'ref'=>$res->mod_id,'return'=>'true'));
                    $json[$res->mod_id] = array(
                        "id"=>$res->mod_id,   
                        "title"=>ucwords(strtolower($res->name)),   
                        "caption"=>"PHP ".num($res->cost),
                        "inactive_show"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                    $ids[] = $res->mod_id;
                }
            }else{
                foreach ($items as $res) {
                    $json[$res->mod_id] = array(
                        "id"=>$res->mod_id,   
                        "title"=>ucwords(strtolower($res->name)),   
                        "caption"=>"PHP ".num($res->cost),
                        "inactive_show"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                    $ids[] = $res->mod_id;
                }
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function search_modifiers_form(){
        $this->load->helper('dine/mods_helper');
        $data['code'] = modSearchForm();
        $this->load->view('load',$data);
    }
    public function form($mod_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $data = $this->syter->spawn('mods');
        $data['page_title'] = fa('icon-book-open')." Add New Modifiers";
        if($mod_id != null){
            $result = $this->site_model->get_tbl('modifiers',array('mod_id'=>$mod_id));
            $mod = $result[0];
            $data['page_title'] = fa('icon-book-open').' '.$mod->name;
        }
        $data['code'] = modFormPage($mod_id);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'modFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function details_load($mod_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $mod=array();
        if($mod_id != null){
            $mods = $this->mods_model->get_modifiers($mod_id);
            $mod=$mods[0];
        }
        $data['code'] = modDetailsLoad($mod,$mod_id);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'detailsLoadJs';
        $this->load->view('load',$data);
    }
    public function details_db(){
        $this->load->model('dine/mods_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $items = array(
            "mod_code"=>$this->input->post('mod_code'),
            "name"=>$this->input->post('name'),
            "has_recipe"=>(int)$this->input->post('has_recipe'),
            "inactive"=>(int)$this->input->post('inactive'),
            "cost"=>$this->input->post('cost'),
            "mod_sub_cat_id"=>$this->input->post('mod_sub_cat_id')
        );

        
        if($this->input->post('form_mod_id')){
            $this->mods_model->update_modifiers($items,$this->input->post('form_mod_id'));
            $id = $this->input->post('form_mod_id');
            $act = 'update';
            $msg = 'Updated Modifier '.$this->input->post('name');
            $this->main_model->update_tbl('modifiers','mod_id',$items,$id);
            echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){

                    //check if meron
                    $where = array('mod_id'=>$this->input->post('form_mod_id'));
                    $chk = $this->manager_model->get_details($where,'modifiers',$vals->terminal_code);

                    if(count($chk) > 0){
                        $this->mods_model->update_modifiers($items,$this->input->post('form_mod_id'),$vals->terminal_code);
                    }else{
                        $items['mod_id'] = $this->input->post('form_mod_id');
                        $this->mods_model->add_modifiers($items,$vals->terminal_code);
                    }

                }

            }
        }else{

            $where = array('mod_code'=>$this->input->post('mod_code'));
            $det = $this->site_model->get_details($where,'modifiers');

            if($det){
                $msg = "Duplicate Modifier Code.";
                echo json_encode(array("id"=>'dup','msg'=>$msg));
            }else{

                $dets = $this->manager_model->get_last_ids('mod_id','modifiers');
                if($dets){
                    $id = $dets[0]->mod_id + 1;
                }else{
                    $id = 1;
                }
                $items['mod_id'] = $id;

                $this->mods_model->add_modifiers($items);
                $act = 'add';
                $msg = 'Added  new Modifier '.$this->input->post('name');
                // $items['mod_id'] = $id;
                $this->main_model->add_trans_tbl('modifiers',$items);
                echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));

                if(CONSOLIDATOR){
                    $terminals = $this->manager_model->get_terminals();

                    foreach($terminals as $ctr => $vals){
                        // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                        $this->mods_model->add_modifiers($items,$vals->terminal_code);
                    }

                }
            }

        }

    }
    public function recipe_load($mod_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $details = $this->mods_model->get_modifier_recipe(null,$mod_id);

        $mods = $this->mods_model->get_modifiers($mod_id);
        $mod=$mods[0];

        $data['code'] = modRecipeLoad($mod_id,$details,$mod);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'recipeLoadJs';
        $this->load->view('load',$data);
    }
    public function search_items(){
        $search = $this->input->post('search');
        $this->load->model('dine/mods_model');
        $found = $this->mods_model->search_items($search);
        $items = array();
        if(count($found) > 0 ){
            foreach ($found as $res) {
                $items[] = array('key'=>$res->code." ".$res->name,'value'=>$res->item_id);
            }
        }
        echo json_encode($items);
    }
    public function get_item_details($item_id=null){
        $this->load->model('dine/items_model');
        $items = $this->items_model->get_item($item_id);
        $item = $items[0];
        $det['cost'] = $item->cost;
        $det['uom'] = $item->uom;
        echo json_encode($det);
    }
    public function recipe_db(){
        $this->load->model('dine/mods_model');
        $mod_id = $this->input->post('mod_id');
        $item_id = $this->input->post('item-id-hid');
        $gotItem = $this->mods_model->get_modifier_recipe(null,$mod_id,$item_id);
        $items = array(
            "mod_id"=>$mod_id,
            "item_id"=>$item_id,
            "uom"=>$this->input->post('item-uom-hid'),
            "qty"=>$this->input->post('qty'),
            "cost"=>$this->input->post('item-cost')
        );
        if(count($gotItem) > 0){
            $det = $gotItem[0];
            $this->mods_model->update_modifier_recipe($items,$det->mod_recipe_id);
            $id = $det->mod_recipe_id;
            $act = "update";
            $msg = "Updated Item ".$this->input->post('item-search');
        }else{
            $id = $this->mods_model->add_modifier_recipe($items);
            $act = "add";
            $msg = "Added New Item ".$this->input->post('item-search');
        }
        $this->make->sRow(array('id'=>'row-'.$id));
            $this->make->td($this->input->post('item-search'));
            $this->make->td(num($this->input->post('qty')));
            $this->make->td(num($this->input->post('item-cost')));
            $this->make->td(num($this->input->post('item-cost') * $this->input->post('qty')));
            $a = $this->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$id,'return'=>true));
            $this->make->td($a);
        $this->make->eRow();
        $row = $this->make->code();

        echo json_encode(array('row'=>$row,'msg'=>$msg,'act'=>$act,'id'=>$id));
    }
    public function remove_recipe_item(){
        $this->load->model('dine/mods_model');
        $this->mods_model->delete_modifier_recipe_item($this->input->post('mod_recipe_id'));
        $json['msg'] = "Item Deleted.";
        echo json_encode($json);
    }
    public function get_recipe_total($asJson=true,$updateDB=true){
        $this->load->model('dine/mods_model');
        $mod_id = $this->input->post('mod_id');
        $details = $this->mods_model->get_modifier_recipe_prices(null,$mod_id,null);
        $total = 0;
        foreach ($details as $res) {
            $total += $res->cost * $res->qty;
        }
        if($updateDB){
            $this->mods_model->update_modifiers(array('cost'=>$total),$mod_id);
        }

        if($asJson)
            echo json_encode(array('total'=>num($total)));
    }
    public function update_modifier_price($asJson=true,$updateDB=true){
        $this->load->model('dine/mods_model');
        $total = $this->input->post('total');
        $mod_id = $this->input->post('mod_id');
        $a = $total;
        $b = str_replace( ',', '', $a );

        if( is_numeric( $b ) ) {
            $a = $b;
        }
        $this->mods_model->update_modifiers(array('cost'=>$a),$mod_id);
    }
    public function groups(){
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('mods');
        $data['page_title'] = fa('icon-book-open')." Group Modifiers";
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Code','Name','Inactive','');
        }else{
            $th = array('ID','Code','Name','Inactive');
        }
        $data['code'] = create_rtable('modifier_groups','mod_id','modifier_groups-tbl',$th,'mods/search_modifier_groups_form',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'modGroupssListFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function get_modifier_groups($id=null,$asJson=true){
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
            $args["(modifier_groups.name like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['modifier_groups.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('modifier_groups',$args,array(),$join,true,'modifier_groups.*',null,null,true);
        $page = paginate('mods/get_modifier_groups',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('modifier_groups',$args,array(),$join,true,'modifier_groups.*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                foreach ($items as $res) {
                    $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'mods/group_form/'.$res->mod_group_id,array('class'=>'btn btn-sm blue btn-outline edit','id'=>'edit-'.$res->mod_group_id,'ref'=>$res->mod_group_id,'return'=>'true'));
                    // $link = "";
                    $json[$res->mod_group_id] = array(
                        "id"=>$res->mod_group_id,   
                        "code"=>$res->grp_code,   
                        "title"=>ucwords(strtolower($res->name)),
                        "inactive_show"=>($res->inactive == 0 ? 'No' : 'Yes'),   
                        "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                    $ids[] = $res->mod_group_id;
                }
            }else{
                foreach ($items as $res) {
                    // $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'mods/group_form/'.$res->mod_group_id,array('class'=>'btn btn-sm blue btn-outline edit','id'=>'edit-'.$res->mod_group_id,'ref'=>$res->mod_group_id,'return'=>'true'));
                    // $link = "";
                    $json[$res->mod_group_id] = array(
                        "id"=>$res->mod_group_id,
                        "code"=>$res->grp_code,   
                        "title"=>ucwords(strtolower($res->name)),
                        "inactive_show"=>($res->inactive == 0 ? 'No' : 'Yes'),   
                        "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        // "link"=>$link
                    );
                    $ids[] = $res->mod_group_id;
                }
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function search_modifier_groups_form(){
        $this->load->helper('dine/mods_helper');
        $data['code'] = modGroupSearchForm();
        $this->load->view('load',$data);
    }
    // public function groups(){
    //     $this->load->model('dine/mods_model');
    //     $this->load->helper('dine/mods_helper');
    //     $data = $this->syter->spawn('mods');
    //     $data['page_subtitle'] = "Group Management";
    //     $grps = $this->mods_model->get_modifier_groups();
    //     $data['code'] = modGroupListPage($grps);
    //     $this->load->view('page',$data);
    // }
    public function group_form($mod_group_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $data = $this->syter->spawn('mods');
        $data['page_title'] = fa('icon-book-open')." Group Modifiers";
        if($mod_group_id != null){
            $result = $this->site_model->get_tbl('modifier_groups',array('mod_group_id'=>$mod_group_id));
            $mod = $result[0];
            $data['page_title'] = fa('icon-book-open').' '.$mod->name;
        }
        $data['code'] = modGroupFormPage($mod_group_id);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'modGroupFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function group_details_load($mod_group_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $grp=array();
        if($mod_group_id != null){
            $grps = $this->mods_model->get_modifier_groups($mod_group_id);
            $grp=$grps[0];
        }
        $data['code'] = modGroupDetailsLoad($grp,$mod_group_id);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'groupDetailsLoadJs';
        $this->load->view('load',$data);
    }
    public function group_details_db(){
        $this->load->model('dine/mods_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $items = array(
            "grp_code"=>$this->input->post('grp_code'),
            "name"=>$this->input->post('name'),
            "mandatory"=>(int)$this->input->post('mandatory'),
            "multiple"=>(int)$this->input->post('multiple'),
            "inactive"=>(int)$this->input->post('inactive'),
            "min_no"=>(int)$this->input->post('min_no')
        );

        if($this->input->post('form_mod_group_id')){
            $this->mods_model->update_modifier_groups($items,$this->input->post('form_mod_group_id'));
            $id = $this->input->post('form_mod_group_id');
            $act = 'update';
            $msg = 'Updated Modifier Group '.$this->input->post('name');
            $this->main_model->update_tbl('modifier_groups','mod_group_id',$items,$id);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){

                    //check if meron
                    $where = array('mod_group_id'=>$this->input->post('form_mod_group_id'));
                    $chk = $this->manager_model->get_details($where,'modifier_groups',$vals->terminal_code);

                    if(count($chk) > 0){
                        $this->mods_model->update_modifier_groups($items,$this->input->post('form_mod_group_id'),$vals->terminal_code);
                    }else{
                        $items['mod_group_id'] = $this->input->post('form_mod_group_id');
                        $this->mods_model->add_modifier_groups($items,$vals->terminal_code);
                    }

                }
            }
        }else{
            $dets = $this->manager_model->get_last_ids('mod_group_id','modifier_groups');
            if($dets){
                $id = $dets[0]->mod_group_id + 1;
            }else{
                $id = 1;
            }

            $items['mod_group_id'] = $id;

            $id = $this->mods_model->add_modifier_groups($items);
            $act = 'add';
            $msg = 'Added  new Modifier Group '.$this->input->post('name');
            // $items['mod_group_id'] = $id;
            $this->main_model->add_trans_tbl('modifier_groups',$items);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    $this->mods_model->add_modifier_groups($items,$vals->terminal_code);
                }

            }

        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
    public function group_modifiers_load($mod_group_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $details = $this->mods_model->get_modifier_group_details(null,$mod_group_id);

        $data['code'] = groupModifiersLoad($mod_group_id,$details);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'groupRecipeLoadJs';
        $this->load->view('load',$data);
    }
    public function search_modifiers(){
        $search = $this->input->post('search');
        $this->load->model('dine/mods_model');
        $found = $this->mods_model->search_modifiers($search);
        $items = array();
        if(count($found) > 0 ){
            foreach ($found as $res) {
                $items[] = array('key'=>$res->name,'value'=>$res->mod_id);
            }
        }
        echo json_encode($items);
    }
    public function group_modifiers_details_db(){
        $this->load->model('dine/mods_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $mod_group_id = $this->input->post('mod_group_id');
        $mod_id = $this->input->post('mod_id');
        $mod_text = $this->input->post('mod_text');
        $items = array(
            "mod_group_id" => $mod_group_id,
            "mod_id" => $mod_id
        );
        $gotDet = $this->mods_model->get_modifier_group_details(null,$mod_group_id,$mod_id);
        if(count($gotDet) > 0){
            $det = $gotDet[0];
            $this->mods_model->update_modifier_group_details($items,$det->id);
            $this->mods_model->db = $this->load->database('main', TRUE);
            $this->mods_model->update_modifier_group_details($items,$det->id);
            $this->mods_model->db = $this->load->database('default', TRUE);
            $id = $det->id;
            $act = "update";
            $msg = "Updated Modifier ".$mod_text;

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    $this->mods_model->db = $this->load->database($vals->terminal_code, TRUE);
                    $this->mods_model->update_modifier_group_details($items,$det->id);
                }

            }
        }
        else{
            $dets = $this->manager_model->get_last_ids('id','modifier_group_details');
            if($dets){
                $id = $dets[0]->id + 1;
            }else{
                $id = 1;
            }
            $items['id'] = $id;

            $this->mods_model->add_modifier_group_details($items);
            // $this->mods_model->db = $this->load->database('main', TRUE);
            // $id = $this->mods_model->add_modifier_group_details($items);
            // $this->mods_model->db = $this->load->database('default', TRUE);
            // $items['id'] = $id;
            $this->main_model->add_trans_tbl('modifier_group_details',$items);

            $act = 'add';
            $msg = 'Added  Modifier '.$mod_text;

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    // $items['id'] = $id;
                    $this->mods_model->db = $this->load->database($vals->terminal_code, TRUE);
                    $this->mods_model->add_modifier_group_details($items);
                }

            }
            $this->mods_model->db = $this->load->database('default', TRUE);
        }
        $li = $this->make->li(
                $this->make->checkbox(null,'dflt_'.$id,0,array('ref'=>$id,'return'=>true))." ".
                $this->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
                $this->make->span($mod_text,array('class'=>'text','return'=>true))." ".
                $this->make->A(fa('fa-lg fa-times'),'#',array('return'=>true,'class'=>'del','id'=>'del-'.$id,'ref'=>$id)),
                array('return'=>true,'id'=>'li-'.$id)
             );
        echo json_encode(array("id"=>$id,"desc"=>$mod_text,"act"=>$act,'msg'=>$msg,'li'=>$li));
    }
    public function default_group_modifier(){
        $this->load->model('dine/mods_model');
        $this->load->model('dine/manager_model');
        $items = array('default'=>$this->input->post('dflt'));
        $this->mods_model->update_modifier_group_details($items,$this->input->post('group_mod_id'));
        $this->mods_model->db = $this->load->database('main', TRUE);
        $this->mods_model->update_modifier_group_details($items,$this->input->post('group_mod_id'));
        // $this->mods_model->delete_modifier_group_details($this->input->post('group_mod_id'));
        if(CONSOLIDATOR){
            $terminals = $this->manager_model->get_terminals();

            foreach($terminals as $ctr => $vals){
                // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                $this->mods_model->db = $this->load->database($vals->terminal_code, TRUE);
                $this->mods_model->update_modifier_group_details($items,$this->input->post('group_mod_id'));
            }

        }
        // $this->mods_model->db = $this->load->database('default', TRUE);
        $json['msg'] = "Modifier set to default.";
        echo json_encode($json);
    }    
    public function remove_group_modifier(){
        $this->load->model('dine/mods_model');
        $this->load->model('dine/manager_model');
        $this->mods_model->delete_modifier_group_details($this->input->post('group_mod_id'));
        $this->mods_model->db = $this->load->database('main', TRUE);
        $this->mods_model->delete_modifier_group_details($this->input->post('group_mod_id'));

        if(CONSOLIDATOR){
            $terminals = $this->manager_model->get_terminals();

            foreach($terminals as $ctr => $vals){
                // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                $this->mods_model->db = $this->load->database($vals->terminal_code, TRUE);
                $this->mods_model->delete_modifier_group_details($this->input->post('group_mod_id'));
            }

        }

        // $this->mods_model->db = $this->load->database('default', TRUE);
        $json['msg'] = "Modifier Deleted.";
        echo json_encode($json);
    }

    public function mod_sub_load($mod_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');

        $mod_subs = $this->mods_model->get_modifier_sub(null,$mod_id);

        $data['code'] = modSubLoad($mod_id,$mod_subs);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'modsubLoadJs';
        $this->load->view('load',$data);
    }

    public function mod_sub_db(){
        $this->load->model('dine/mods_model');
        $this->load->model('dine/manager_model');
        $mod_id = $this->input->post('mod_id');
        // $gotItem = $this->mods_model->get_modifier_sub(null,$mod_id);
        $items = array(
            "mod_id"=>$mod_id,
            "submod_code"=>$this->input->post('submod_code'),
            "name"=>$this->input->post('name'),
            "group"=>$this->input->post('group'),
            "is_auto"=>$this->input->post('is_auto'),
            "qty"=>$this->input->post('qty'),
            "cost"=>$this->input->post('cost')
        );
        // if(count($gotItem) > 0){
        //     $det = $gotItem[0];
        //     $this->mods_model->update_modifier_sub($items,$det->mod_sub_id);
        //     $id = $det->mod_sub_id;
        //     $act = "update";
        //     $msg = "Updated Modifier ".$this->input->post('item-search');
        // }else{

            $dets = $this->manager_model->get_last_ids('mod_sub_id','modifier_sub');
            if($dets){
                $id = $dets[0]->mod_sub_id + 1;
            }else{
                $id = 1;
            }
            $items['mod_sub_id'] = $id;


            $this->mods_model->add_modifier_sub($items);
            $this->load->model('dine/main_model');

            $items['mod_sub_id'] = $id;
            $this->main_model->add_trans_tbl('modifier_sub',$items);

            $act = "add";
            $msg = "Added New Item ".$this->input->post('item-search');
        // }
        $auto = 'No';
        if($this->input->post('is_auto') == 1){
            $auto = 'Yes';
        }

        if(CONSOLIDATOR){
            $terminals = $this->manager_model->get_terminals();

            foreach($terminals as $ctr => $vals){
                // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                $this->mods_model->add_modifier_sub($items,$vals->terminal_code);
            }

        }


        $this->make->sRow(array('id'=>'row-'.$id,'ref'=>$id,'modsub-name'=>$items['name'],'class'=>'modsub-row'));
            $this->make->td($this->input->post('submod_code'));
            $this->make->td($this->input->post('name'));
            // $this->make->td(num($this->input->post('qty')));
            $this->make->td($this->input->post('group'));
            $this->make->td($this->input->post('qty'));
            $this->make->td($auto);
            $this->make->td(num($this->input->post('cost')),array('style'=>'text-align:right'));
            // $this->make->td(num($this->input->post('item-cost') * $this->input->post('qty')));
            $a = $this->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$id, 'ref'=>$id,'return'=>true));
            $this->make->td($a);
        $this->make->eRow();
        $row = $this->make->code();

        echo json_encode(array('row'=>$row,'msg'=>$msg,'act'=>$act,'id'=>$id));
    }

    public function remove_mod_sub(){
        $this->load->model('dine/mods_model');
        $this->load->model('dine/manager_model');
        $this->mods_model->delete_modifier_sub($this->input->post('mod_sub_id'));
        $this->mods_model->remove_mod_sub_price(null,$this->input->post('mod_sub_id'));

        $this->mods_model->db = $this->load->database('main', TRUE);
        $this->mods_model->delete_modifier_sub($this->input->post('mod_sub_id'));
        $this->mods_model->remove_mod_sub_price(null,$this->input->post('mod_sub_id'));

        if(CONSOLIDATOR){
            $terminals = $this->manager_model->get_terminals();

            foreach($terminals as $ctr => $vals){
                // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                $this->mods_model->delete_modifier_sub($this->input->post('mod_sub_id'),$vals->terminal_code);
                $this->mods_model->remove_mod_sub_price(null,$this->input->post('mod_sub_id'),$vals->terminal_code);
            }

        }

        $json['msg'] = "Modifier sub Deleted.";
        echo json_encode($json);
    }


    public function price_load($mod_id=null)
    {
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');

        $det = $this->mods_model->get_mod_prices($mod_id);
        $data['code'] = modPricesLoad($mod_id,$det);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'modPricesJs';
        $this->load->view('load',$data);
    }

     public function mod_prices_db()
    {
        $this->load->model('dine/mods_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');

        if (!$this->input->post())
            header('Location:'.base_url().'mods');

        $items = array(
            'mod_id' => $this->input->post('mod-id-hid'),
            'trans_type' => $this->input->post('trans_type'),
            'price' => $this->input->post('price')
        );

        $det = $this->mods_model->get_mod_prices($items['mod_id'],$items['trans_type']);

        // echo 'asdfasdf'; die();

        if (count($det) == 0) {

            $dets = $this->manager_model->get_last_ids('id','modifier_prices');
            if($dets){
                $id = $dets[0]->id + 1;
            }else{
                $id = 1;
            }
            $items['id'] = $id;

            $this->mods_model->add_mod_price($items);

            // $items['id'] = $id;
            $this->main_model->add_trans_tbl('modifier_prices',$items);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    $this->mods_model->add_mod_price($items,$vals->terminal_code);
                }

            }

            // $mod_group = $this->menu_model->get_modifier_groups(array('mod_group_id'=>$items['mod_group_id']));
            // $mod_group = $mod_group[0];

            $this->make->sRow(array('id'=>'row-'.$id,'class'=>'modsub-row'));
                $this->make->td($items['trans_type']);
                $this->make->td(num($items['price']),array('style'=>'text-align:right'));
                $a = $this->make->A(fa('fa-lg fa-times fa-fw'),'#',array('id'=>'del-'.$id,'ref'=>$id,'class'=>'del-item','return'=>true));
                $this->make->td($a,array('style'=>'text-align:right'));
            $this->make->eRow();

            $row = $this->make->code();

            echo json_encode(array('result'=>'success','msg'=>'Price has been added','row'=>$row));
        } else
            echo json_encode(array('result'=>'error','msg'=>'Transaction Type already has a price'));

    }

    public function remove_mod_price()
    {
        $this->load->model('dine/mods_model');
        $this->load->model('dine/manager_model');
        
        $id = $this->input->post('id');
        $this->mods_model->remove_mod_price($id);

        $this->mods_model->db = $this->load->database('main', TRUE);
        $this->mods_model->remove_mod_price($id);

        if(CONSOLIDATOR){
            $terminals = $this->manager_model->get_terminals();

            foreach($terminals as $ctr => $vals){
                // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                $this->mods_model->remove_mod_price($id,$vals->terminal_code);
            }

        }

        $json['msg'] = 'Price Removed.';
        echo json_encode($json);
    }

    public function mod_sub_price_load($mod_sub_id){
    // public function mod_sub_price_load(){        
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');

        // $mod_sub_id = $this->input->post('mod_sub_id');
        // $mod_sub_name = $this->input->post('mod_sub_name');
        $mod_sub_name = '';
        $mod_sub = $this->mods_model->get_modifier_sub($mod_sub_id);

        if($mod_sub){
            $mod_sub_name = $mod_sub[0]->name;
        }
        $det = $this->mods_model->get_mod_sub_prices($mod_sub_id);
        $data['code'] = modsubPricesLoad($mod_sub_id,$mod_sub_name,$det);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'modsubPricesJs';

        $this->load->view('load',$data);
        
    }

     public function mod_sub_prices_db()
    {
        $this->load->model('dine/mods_model');
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');

        if (!$this->input->post())
            header('Location:'.base_url().'mods');

        $items = array(
            'mod_sub_id' => $this->input->post('modsub-id-hid'),
            'trans_type' => $this->input->post('trans_type'),
            'price' => $this->input->post('price'),
        );

        $det = $this->mods_model->get_mod_sub_prices($items['mod_sub_id'],$items['trans_type']);

        // echo 'asdfasdf'; die();

        if (count($det) == 0) {
            $dets = $this->manager_model->get_last_ids('id','modifier_sub_prices');
            if($dets){
                $id = $dets[0]->id + 1;
            }else{
                $id = 1;
            }
            $items['id'] = $id;

            $this->mods_model->add_mod_sub_price($items);

            // $items['id'] = $id;
            $this->main_model->add_trans_tbl('modifier_sub_prices',$items);

            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                    $this->mods_model->add_mod_sub_price($items,$vals->terminal_code);
                }

            }

            // $mod_group = $this->menu_model->get_modifier_groups(array('mod_group_id'=>$items['mod_group_id']));
            // $mod_group = $mod_group[0];

            $this->make->sRow(array('id'=>'modsub-price-row-'.$id));
                $this->make->td($items['trans_type']);
                $this->make->td(num($items['price']),array('style'=>'text-align:right'));
                $a = $this->make->A(fa('fa-lg fa-times fa-fw'),'#',array('id'=>'modsub-price-del-'.$id,'ref'=>$id,'class'=>'del-modsub-price','return'=>true));
                $this->make->td($a,array('style'=>'text-align:right'));
            $this->make->eRow();

            $row = $this->make->code();

            echo json_encode(array('result'=>'success','msg'=>'Price has been added','row'=>$row));
        } else
            echo json_encode(array('result'=>'error','msg'=>'Transaction Type already has a price'));

    }

    public function remove_mod_sub_price()
    {
        $this->load->model('dine/mods_model');
        $this->load->model('dine/manager_model');
        
        $id = $this->input->post('id');
        $this->mods_model->remove_mod_sub_price($id);

        $this->mods_model->db = $this->load->database('main', TRUE);
        $this->mods_model->remove_mod_sub_price($id);

        if(CONSOLIDATOR){
            $terminals = $this->manager_model->get_terminals();

            foreach($terminals as $ctr => $vals){
                // $this->settings_model->add_brands_termi($items,$vals->terminal_code);
                $this->mods_model->remove_mod_sub_price($id,null,$vals->terminal_code);
            }

        }

        $json['msg'] = 'Price Removed.';
        echo json_encode($json);
    }

    ////for mod sub group
    // public function modsub_groups(){
    //     $this->load->model('dine/menu_model');
    //     $this->load->helper('dine/menu_helper');
    //     $this->load->helper('site/site_forms_helper');
    //     $data = $this->syter->spawn('mods');
    //     $data['page_title'] = fa('icon-book-open')." Group Submodifiers";
    //     if(MASTER_BUTTON_EDIT){
    //         $th = array('ID','Name','Inactive','');
    //     }else{
    //         $th = array('ID','Name','Inactive');
    //     }
    //     $data['code'] = create_rtable('modifier_groups','mod_id','modifier_groups-tbl',$th,'mods/search_modifier_groups_form',false,'list',REMOVE_MASTER_BUTTON);
    //     $data['load_js'] = 'dine/mod.php';
    //     $data['use_js'] = 'modGroupssListFormJs';
    //     $data['page_no_padding'] = true;
    //     $this->load->view('page',$data);
    // }
    // public function get_modifier_groups($id=null,$asJson=true){
    //     $this->load->helper('site/pagination_helper');
    //     $pagi = null;
    //     $args = array();
    //     $total_rows = 30;
    //     if($this->input->post('pagi'))
    //         $pagi = $this->input->post('pagi');
    //     $post = array();
        
    //     if(count($this->input->post()) > 0){
    //         $post = $this->input->post();
    //     }
    //     if($this->input->post('name')){
    //         $lk  =$this->input->post('name');
    //         $args["(modifier_groups.name like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
    //     }
    //     if($this->input->post('inactive')){
    //         $args['modifier_groups.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
    //     }
    //     $join = null;
    //     $count = $this->site_model->get_tbl('modifier_groups',$args,array(),$join,true,'modifier_groups.*',null,null,true);
    //     $page = paginate('mods/get_modifier_groups',$count,$total_rows,$pagi);
    //     $items = $this->site_model->get_tbl('modifier_groups',$args,array(),$join,true,'modifier_groups.*',null,$page['limit']);
    //     $json = array();
    //     if(count($items) > 0){
    //         $ids = array();
    //         if(MASTER_BUTTON_EDIT){
    //             foreach ($items as $res) {
    //                 $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'mods/group_form/'.$res->mod_group_id,array('class'=>'btn btn-sm blue btn-outline edit','id'=>'edit-'.$res->mod_group_id,'ref'=>$res->mod_group_id,'return'=>'true'));
    //                 // $link = "";
    //                 $json[$res->mod_group_id] = array(
    //                     "id"=>$res->mod_group_id,   
    //                     "title"=>ucwords(strtolower($res->name)),
    //                     "inactive_show"=>($res->inactive == 0 ? 'No' : 'Yes'),   
    //                     "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
    //                     "link"=>$link
    //                 );
    //                 $ids[] = $res->mod_group_id;
    //             }
    //         }else{
    //             foreach ($items as $res) {
    //                 // $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'mods/group_form/'.$res->mod_group_id,array('class'=>'btn btn-sm blue btn-outline edit','id'=>'edit-'.$res->mod_group_id,'ref'=>$res->mod_group_id,'return'=>'true'));
    //                 // $link = "";
    //                 $json[$res->mod_group_id] = array(
    //                     "id"=>$res->mod_group_id,   
    //                     "title"=>ucwords(strtolower($res->name)),
    //                     "inactive_show"=>($res->inactive == 0 ? 'No' : 'Yes'),   
    //                     "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
    //                     // "link"=>$link
    //                 );
    //                 $ids[] = $res->mod_group_id;
    //             }
    //         }
    //     }
    //     echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    // }
}