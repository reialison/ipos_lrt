<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	var $data = null;
    public function roles(){
        $this->load->model('core/admin_model');
        $this->load->helper('site/site_forms_helper');
        $role_list = $this->admin_model->get_user_roles();
        $th = array('Name','Description');
        $data = $this->syter->spawn('roles');
        // $data['page_title'] = fa('icon-user').' User Roles';
        // $data['code'] = site_list_form("admin/roles_form","roles_form","Roles List",$role_list,array('role'),"id",REMOVE_MASTER_BUTTON);
        // $data['add_js'] = 'js/site_list_forms.js';
        $data['page_title'] = fa('icon-user').' User Roles';
        $data['code'] = create_rtable('roles_form','id','user-roles-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'site/admin';
        $data['use_js'] = 'userRolesJs';
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $this->load->view('page',$data);
    }
    public function roles_form($ref=null){
        $this->load->helper('core/admin_helper');
        $this->load->model('core/admin_model');
        $role = array();
        $access = array();
        if($ref != null){
            $roles = $this->admin_model->get_user_roles($ref);
            $role = $roles[0];
            $access = explode(',',$role->access);
        }
        $navs = $this->syter->get_navs();
        $this->data['code'] = rolesForm($role,$access,$navs);
        $this->data['load_js'] = 'site/admin';
        $this->data['use_js'] = 'rolesJs';
        $this->load->view('load',$this->data);
    }
    public function get_user_role($id=null,$asJson=true){
        $post = array();
        $page = "";
        // die('haha');
        // $joinTables['user_roles'] = array('content'=>'users.role = user_roles.id');
        $joinTables = array();
        $select = 'user_roles.*';
        $args["user_roles.id != 1"] = array('use'=>'where','val'=>null,'third'=>false);
        $items = $this->site_model->get_tbl('user_roles',$args,array('user_roles.id'=>'desc'),$joinTables,true,$select);
        // echo "<pre>",print_r($items),"</pre>";die();
        $json = array();
        if(count($items) > 0){
            foreach ($items as $res) {
                $json[$res->id] = array(
                    // "id"=>$res->id,   
                    "role"=>$res->role,     
                    "description"=>$res->description   
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
    public function user_roles_form($ref=null){
        // die($ref);
        $this->load->model('core/admin_model');
        $this->load->helper('core/admin_helper');
        $data = $this->syter->spawn('user_roles');
        $user = array();
        $img = array();
        $data['page_title'] = fa('icon-user').' User Roles';
        $role = array();
        $access = array();
        if($ref != null){
            $roles = $this->admin_model->get_user_roles($ref);
            if($roles){
            $role = $roles[0];
            // echo "<pre>",print_r($role),"</pre>";
            $access = explode(',',$role->access);
            }
        }
        $navs = $this->syter->get_navs();
        $data['code'] = rolesForm($role,$access,$navs);
        // $this->data['code'] = rolesForm($role,$access,$navs);
        $data['load_js'] = 'site/admin';
        $data['use_js'] = 'userRoleFormJs';
        $this->load->view('page',$data);
    }
    public function roles_db(){
        // echo 'haha';die();
        $this->load->model('core/admin_model');
        $links = $this->input->post('roles');
        $role = $this->input->post('role');
        $desc = $this->input->post('description');
        $access = "";

        if($links){
            foreach ($links as $li) {
                $access .= $li.",";
            }
        }
        
        $access = substr($access,0,-1);
        $items = array(
            "role"=>$role,
            "description"=>$desc,
            "access"=>$access
        );
        if($this->input->post('role_id')){
            $this->admin_model->update_user_roles($items,$this->input->post('role_id'));
            $id = $this->input->post('role_id');
            $act = 'update';
            $msg = 'Updated role '.$role;
        }
        else{
            $id = $this->admin_model->add_user_roles($items);
            $act = 'add';
            $msg = 'Added  new role '.$role;   
        }
        echo json_encode(array("id"=>$id,"desc"=>$role,"act"=>$act,'msg'=>$msg));
    }
    public function asdf456qwer789zxcv123(){
        $this->load->helper('core/admin_helper');
        $data = $this->syter->spawn('restart');
        $data['page_title'] = fa('fa-refresh')." Restart POS";
        $data['code'] = restartPage();
        $data['load_js'] = 'site/admin.php';
        $data['use_js'] = 'restartJs';
        $this->load->view('page',$data);
    }
    public function go_restart(){
        $this->load->model('core/admin_model');
        $this->db = $this->load->database('default', TRUE);
        $this->admin_model->restart();
        $this->db = $this->load->database('main', TRUE);
        $this->admin_model->restart();
        session_start();
        unset($_SESSION['load']);
        unset($_SESSION['problem']);
        $this->session->sess_destroy();
        
    }

    public function admin_master_restart(){
        $this->load->model('core/admin_model');
        $restart = $this->admin_model->master_restart();
        var_dump($restart);
    }

    public function check_conn(){       

        try { 

            $this->load->database();

        } catch (Exception $e) {
            echo 'error';

        // Connect to secondary DB.
        }
    }
}