<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	var $data = null;
	public function index(){
        $this->load->helper('site/site_forms_helper');   
		$result = $this->site_model->get_tbl('user_roles');
        $th = array('Username','Name','Role','Date Registered');
        $data = $this->syter->spawn('user');
        $data['page_title'] = fa('icon-user').' Users';
        $data['code'] = create_rtable('users','id','users-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'site/admin';
        $data['use_js'] = 'usersListJs';
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $this->load->view('page',$data);
	}
    public function get_users($id=null,$asJson=true){
        $post = array();
        $page = "";

        $joinTables['user_roles'] = array('content'=>'users.role = user_roles.id');
        $select = 'users.*, user_roles.role';
        $items = $this->site_model->get_tbl('users',array(),array('users.id'=>'asc'),$joinTables,true,$select);
        $json = array();
        if(count($items) > 0){
            foreach ($items as $res) {
                $json[$res->id] = array(
                    // "id"=>$res->id,   
                    "username"=>$res->username,   
                    "name"=>$res->fname." ".$res->mname." ".$res->lname." ".$res->suffix,   
                    "role"=>$res->role,   
                    "reg_date"=>sql2Date($res->reg_date),
                    "inactive"=>($res->inactive == 0 ? 'No' : 'Yes')
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
    }
	public function users_form($ref=null){
        $this->load->helper('core/user_helper');
        $this->load->model('core/user_model');
        $data = $this->syter->spawn('user');
        $user = array();
        $img = array();
        $data['page_title'] = fa('icon-user').' Add New User';
        if($ref != null){
            $users = $this->user_model->get_users($ref);
            $user = $users[0];
            $data['page_title'] = fa('icon-user').' '.ucwords(strtolower($user->fname." ".$user->mname." ".$user->lname." ".$user->suffix));
            $result = $this->site_model->get_image(null,$ref,'users');
            if(count($result) > 0){
                $img = $result[0];
            }
        }
        // echo var_dump($user);
        $data['code'] = makeUserForm($user,$img);
        $data['load_js'] = 'site/admin';
        $data['use_js'] = 'userFormJs';
        $this->load->view('page',$data);
    }
    public function users_db(){
        $this->load->model('core/user_model');
        $this->load->model('dine/main_model');
        $items = array();
        $noError = true;
        $pin_check = "";
        $pass_check = "";
        $check_pin = $this->site_model->get_tbl('users',array('pin'=>$this->input->post('pin')));

        if($this->input->post('id')){
            $check_pass = $this->site_model->get_tbl('users',array('id'=>$this->input->post('id')));
            if($check_pass){
               $pin_check =  $check_pass[0]->pin;
               $pass_check =  $check_pass[0]->password;
            }
        }
        // echo $pass_check. " " .$this->input->post('password');die();
        
        // if(LOCALSYNC){
        //     $this->load->model('core/sync_model');
        // }

        if(count($check_pin) > 0){
            if($this->input->post('id')){
                if($check_pin[0]->id == $this->input->post('id')){                
                    $noError = true;
                }
                else{
                    $noError = false;
                    $msg = "Invalid Pin.";
                }
            }
            else{
                $noError = false;
                $msg = "Invalid Pin.";
            }    
        }
        if(!$noError){
            echo json_encode(array("id"=>"","desc"=>"","act"=>"error",'msg'=>$msg));
            return false;    
        }
        
        $pins = $this->input->post('pin');

        if($this->input->post('id')){
            if($pin_check == $this->input->post('pin') && $pass_check != $this->input->post('password')){
             $items = array(
                    "fname"=>$this->input->post('fname'),
                    "mname"=>$this->input->post('mname'),
                    "lname"=>$this->input->post('lname'),
                    "role"=>$this->input->post('role'),
                    "suffix"=>$this->input->post('suffix'),
                    "gender"=>$this->input->post('gender'),
                    "email"=>$this->input->post('email'),
                    "password"=>md5($this->input->post('password')),
                    "inactive"=>(int)$this->input->post('inactive'),
                ); 
            }else if($pass_check == $this->input->post('password') && $pin_check != $this->input->post('pin')){
             $items = array(
                    "fname"=>$this->input->post('fname'),
                    "mname"=>$this->input->post('mname'),
                    "lname"=>$this->input->post('lname'),
                    "role"=>$this->input->post('role'),
                    "suffix"=>$this->input->post('suffix'),
                    "gender"=>$this->input->post('gender'),
                    "email"=>$this->input->post('email'),
                    "pin"=>md5($pins),
                    "inactive"=>(int)$this->input->post('inactive'),
                ); 
            }else{
                $items = array(
                    "fname"=>$this->input->post('fname'),
                    "mname"=>$this->input->post('mname'),
                    "lname"=>$this->input->post('lname'),
                    "role"=>$this->input->post('role'),
                    "suffix"=>$this->input->post('suffix'),
                    "gender"=>$this->input->post('gender'),
                    "email"=>$this->input->post('email'),
                    "pin"=> $pin_check == $pins ? $pin_check : md5($pins),
                    "password"=> $pass_check == $this->input->post('password') ?  $pass_check : md5($this->input->post('password')),
                    "inactive"=>(int)$this->input->post('inactive'),
                );
            }
            // echo "<pre>",print_r($items),"</pre>";die();
            $this->user_model->update_users($items,$this->input->post('id'));
            $id = $this->input->post('id');
            $act = 'update';
            $msg = 'Updated User '.$this->input->post('fname').' '.$this->input->post('lname');
            // $this->main_model->add_trans_tbl('users',$items);
            // $this->main_model->update_tbl('users','id',$items,$id);
            // $this->sync_model->update_users($id);

        }
        else{
            $items = array(
                "username"=>$this->input->post('uname'),
                "password"=>md5($this->input->post('password')),
                "fname"=>$this->input->post('fname'),
                "mname"=>$this->input->post('mname'),
                "lname"=>$this->input->post('lname'),
                "role"=>$this->input->post('role'),
                "suffix"=>$this->input->post('suffix'),
                "gender"=>$this->input->post('gender'),
                "email"=>$this->input->post('email'),
                "pin"=>md5($pins),
                "inactive"=>(int)$this->input->post('inactive'),
            );

            $id = $this->user_model->add_users($items);
            $act = 'add';
            $msg = 'Added  new User '.$this->input->post('fname').' '.$this->input->post('lname');
            // $users_id = $this->main_model->add_trans_tbl('users',$items);
            // $this->sync_model->add_users($users_id);

        }
        $image = null;
        $ext = null;
        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){
            $this->site_model->delete_tbl('images',array('img_tbl'=>'users','img_ref_id'=>$id));
            $info = pathinfo($_FILES['fileUpload']['name']);
            if(isset($info['extension']))
                $ext = $info['extension'];
            $newname = $id.".png";            
            $res_id = $id;
            if (!file_exists("uploads/".$res_id."/")) {
                mkdir("uploads/users/", 0777, true);
            }
            $target = 'uploads/users/'.$newname;
            if(!move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
                $msg = "Image Upload failed";
            }
            else{
                $new_image = $target;
                $result = $this->site_model->get_image(null,$this->input->post('id'),'users');
                $items = array(
                    "img_path" => $new_image,
                    "img_file_name" => $newname,
                    "img_ref_id" => $id,
                    "img_tbl" => 'users',
                );
                if(count($result) > 0){
                    $this->site_model->update_tbl('images','id',$items,$result[0]->img_id);
                }
                else{
                    $imgid = $this->site_model->add_tbl('images',$items,array('datetime'=>'NOW()'));
                }
            }
            ####
        }
        site_alert($msg,'success');
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('fname').' '.$this->input->post('lname'),"act"=>$act,'msg'=>$msg));
    }
    /*
        public function index(){
            $this->load->model('core/user_model');
            $this->load->helper('site/site_forms_helper');
            $user_list = $this->user_model->get_users();
            $data = $this->syter->spawn('user');
            $data['code'] = site_list_form("user/users_form","users_form","Users",$user_list,array('fname','mname','lname','suffix'),"id");
            $data['add_js'] = 'js/site_list_forms.js';
            $this->load->view('page',$data);
        }
        public function users_form($ref=null){
            $this->load->helper('core/user_helper');
            $this->load->model('core/user_model');
            $user = array();
            if($ref != null){
                $users = $this->user_model->get_users($ref);
                $user = $users[0];
            }
            // echo var_dump($user);
            $this->data['code'] = makeUserForm($user);
            $this->load->view('load',$this->data);
        }
        public function users_db(){
            $this->load->model('core/user_model');
            $this->load->model('dine/main_model');
            $items = array();

            if($this->input->post('id')){
                $items = array(
                    "fname"=>$this->input->post('fname'),
                    "mname"=>$this->input->post('mname'),
                    "lname"=>$this->input->post('lname'),
                    "role"=>$this->input->post('role'),
                    "suffix"=>$this->input->post('suffix'),
                    "gender"=>$this->input->post('gender'),
                    "email"=>$this->input->post('email'),
                    "pin"=>$this->input->post('pin'),
                );

                $this->user_model->update_users($items,$this->input->post('id'));
                $id = $this->input->post('id');
                $act = 'update';
                $msg = 'Updated User '.$this->input->post('fname').' '.$this->input->post('lname');
                // $this->main_model->add_trans_tbl('users',$items);
                $this->main_model->update_tbl('users','id',$items,$id);
            }
            else{
                $items = array(
                    "username"=>$this->input->post('uname'),
                    "password"=>md5($this->input->post('password')),
                    "fname"=>$this->input->post('fname'),
                    "mname"=>$this->input->post('mname'),
                    "lname"=>$this->input->post('lname'),
                    "role"=>$this->input->post('role'),
                    "suffix"=>$this->input->post('suffix'),
                    "gender"=>$this->input->post('gender'),
                    "email"=>$this->input->post('email'),
                    "pin"=>$this->input->post('pin'),
                );

                $id = $this->user_model->add_users($items);
                $act = 'add';
                $msg = 'Added  new User '.$this->input->post('fname').' '.$this->input->post('lname');
                $this->main_model->add_trans_tbl('users',$items);
            }
            echo json_encode(array("id"=>$id,"desc"=>$this->input->post('fname').' '.$this->input->post('lname'),"act"=>$act,'msg'=>$msg));
        }
     */

    public function upload_excel_form(){
        $this->load->helper('core/user_helper');
        $data['code'] = userUploadForm();
        $this->load->view('load',$data);
    }

    public function download_users(){
        $joinTables['user_roles'] = array('content'=>'users.role = user_roles.id');
        $select = 'users.id,username,pin as password, user_roles.role';
        $items = $this->site_model->get_tbl('users',array(),array('users.id'=>'asc'),$joinTables,true,$select);
        
        
        // $headers = array(array('user_id','username','password','access_level'));
         $headers = array('user_id','username','password','access_level');
        // $sales_data = $this->Migrator_model->get_sales()->result();
        
        // $output = array_merge($headers,$sales_data);
        // echo "<pre>",print_r($output),"</pre>";die();
        $file_name  = 'uploads/txtfile/pos_users.csv';


        $fp = fopen($file_name, 'w+');
        // foreach ($headers as $fields) {
        //     fputcsv($fp, $fields);
        // }
        fputcsv($fp, array(base64_encode(implode(',',$headers))));

        foreach ($items as $fields) {
            // $fields = (array) $fields;
            // fputcsv($fp, $fields);
            // $fields = array(base64_encode($fields->id),
            //                 base64_encode($fields->username),
            //                 base64_encode($fields->password),
            //                 base64_encode($fields->role),
            //             );
            // fputcsv($fp, $fields);

            $fields = array($fields->id,
                            $fields->username,
                            $fields->password,
                            $fields->role,
                        );
            fputcsv($fp, array(base64_encode(implode(',', $fields))));
        } 


        fclose($fp); 

        header("Location:" . base_url(). $file_name);
    }

    public function upload_excel_db(){
        $this->load->model('dine/main_model');
        $this->load->model('core/user_model');

        $temp = $this->upload_temp('user_excel_temp');
        if($temp['error'] == ""){
            // echo 'dasda';die();
            $now = $this->site_model->get_db_now('sql');
            $this->load->library('excel');
            $obj = PHPExcel_IOFactory::load($temp['file']);
            $sheet = $obj->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $start = 2;
            $rows = array();

            $dup_username = false;
            $dup_pin = false;

            $usernames = array();
            $pins = array();
            $items = array();

            for($i=$start;$i<=$count;$i++){
                if($sheet[$i]["A"] != ""){
                    if(ENCRYPT_TXT_FILE){
                        //uncomment below to if encrypted
                        $line = explode(',',base64_decode($sheet[$i]["A"]));

                        $sheet[$i]["A"] = isset($line[0]) ? $line[0] : '';
                        $sheet[$i]["B"] = isset($line[1]) ? $line[1] : '';
                        $sheet[$i]["C"] = isset($line[2]) ? $line[2] : '';
                        $sheet[$i]["D"] = isset($line[3]) ? $line[3] : '';
                        $sheet[$i]["E"] = isset($line[4]) ? $line[4] : '';
                        $sheet[$i]["F"] = isset($line[5]) ? $line[5] : ''; 
                        $sheet[$i]["G"] = isset($line[6]) ? $line[6] : '';
                        $sheet[$i]["H"] = isset($line[7]) ? $line[7] : '';   
                        //comment end
                    }                 

                    $role = $this->site_model->get_tbl('user_roles',array('role'=>$sheet[$i]["E"]));
                                       

                    if(in_array($sheet[$i]["A"], $usernames)){
                        $dup_username = true;
                    }

                    if(in_array($sheet[$i]["B"], $pins)){
                        $dup_pin = true;
                    }

                    if($sheet[$i]["H"] == '' || $sheet[$i]["H"] == 0){
                        $usernames[] = $sheet[$i]["A"];
                        $pins[] = $sheet[$i]["B"];

                        $rows[] = array(
                            // "id"                => $sheet[$i]["A"],
                            "username"          => $sheet[$i]["A"],
                            "password"          => $sheet[$i]["B"],
                            "pin"               => $sheet[$i]["B"],
                            "fname"             => ucwords($sheet[$i]["C"]),
                            "lname"             => ucwords($sheet[$i]["D"]),
                            "role"              => $role ? $role[0]->id:3,
                            "inactive"          => $sheet[$i]["F"] == 1 ? 0 : 1,
                            "reg_date"          => date('Y-m-d H:i:s'),
                            "gender"            => strtolower($sheet[$i]["G"]) == 'm' ? 'male' : 'female',
                        ); 
                    }else{

                        $items[] = array(
                            "id"                => $sheet[$i]["H"],
                            // "username"          => $sheet[$i]["A"],
                            "password"          => $sheet[$i]["B"],
                            "pin"               => $sheet[$i]["B"],
                            "fname"             => ucwords($sheet[$i]["C"]),
                            "lname"             => ucwords($sheet[$i]["D"]),
                            "role"              => $role ? $role[0]->id:3,
                            "inactive"          => $sheet[$i]["F"] == 1 ? 0 : 1,
                            "reg_date"          => date('Y-m-d H:i:s'),
                            "gender"            => strtolower($sheet[$i]["G"]) == 'm' ? 'male' : 'female',
                        ); 

                        if($this->check_user_import(array(),$sheet[$i]["B"],$sheet[$i]["H"])){
                            $dup_pin = true;
                        }                        
                    }
                    
                }
            }

            if($dup_username || $dup_pin || $this->check_user_import($usernames,$pins)){
                site_alert('Upload failed. Duplicate username or password is not allowed.',"error");

                redirect(base_url()."user", 'refresh'); 
            }

            if(count($rows) > 0){
                $dflt_schedule = 1;                
                
                ### INSERT USERS
                
                $this->site_model->add_tbl_batch('users',$rows);
            }

            if(count($items) > 0){
                foreach($items as $item){
                    $this->user_model->update_users($item,$item['id']);
                }
            }

            site_alert('User(s) successfully updated.','success');
            unlink($temp['file']);
        }
        else{
            site_alert($temp['error'],"error");
        }
        redirect(base_url()."user", 'refresh'); 
    }

    public function upload_temp($temp_file_name,$upload_file='user_excel'){
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

    public function check_user_import($usernames,$pins,$user_id=null){
        if($usernames){
            if($this->user_model->get_users('',array('username'=>$usernames))){
                return true;
            }
        }
        
        if($pins){
            $args = array();
            $args["pin"] = $pins;
            if($user_id != ''){
                $args["id != " . $user_id] = array('use'=>'where','val'=>null,'third'=>false);
            }           

            if($this->user_model->get_users('',$args)){
                return true;
            }
        }        

        return false;
    }
}