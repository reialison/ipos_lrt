<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller {
    
	//-----------Branch Details-----start-----allyn
	public function details(){
        $this->load->model('dine/setup_model');
        $this->load->model('dine/cashier_model');
        $this->load->helper('dine/setup_helper');
        $this->load->model('dine/settings_model');
        $details = $this->setup_model->get_details(1);
		$det = $details[0];
        $set = $this->cashier_model->get_pos_settings();
        $splashes = $this->site_model->get_image(null,null,'splash_images');
        $background = $this->site_model->get_image(null,null,'background_images');
        $endtrans = $this->site_model->get_image(null,null,'endtrans_images');

        $list_trans = $this->settings_model->get_transaction_type();
        // echo "<pre>",print_r($set),"</pre>";die();
        $data = $this->syter->spawn('setup');
        $data['page_subtitle'] = 'Edit Branch Setup';
        $data['code'] = makeDetailsForm($det,$set,$splashes,$background,$endtrans,$list_trans);
        // $data['add_js'] = array('js/plugins/timepicker/bootstrap-timepicker.min.js');
        // $data['add_css'] = array('css/timepicker/bootstrap-timepicker.min.css');
		$data['load_js'] = 'dine/setup.php';
		$data['use_js'] = 'detailsJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function upload_splash_images(){
        $this->load->helper('dine/setup_helper');
        $this->load->model('dine/settings_model');
        // $data['code'] = makeTableUploadForm($branch);
        
        $data['code'] = makeImageUploadForm();
        $data['load_js'] = 'dine/setup.php';
        $data['use_js'] = 'uploadSplashImagePopJs';
        $this->load->view('load',$data);
    }
    public function delete_splash_img($img_id=null){
        $error = "";
        $splashes = $this->site_model->get_image($img_id,null,'splash_images');
        if($img_id != ""){
            $this->site_model->delete_tbl('images',array('img_id'=>$img_id));
            unlink($splashes[0]->img_path);
        }
        else{
            $error = "No Image selected";
        }
        echo json_encode(array('error'=>$error));
    }    
    public function upload_splash_images_db($img_type){
        // $this->load->model('dine/settings_model');
        // $image = null;
        // $upload = 'success';
        // $msg = "";
        // $src ="";
        // if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
        //     $file = file_get_contents($_FILES['fileUpload']['tmp_name']);
        //     $items = array(
        //         "img_blob"=>$file,
        //         "img_tbl"=>'splash_images',
        //     );
        //     $id = $this->site_model->add_tbl('images',$items);
        //     $msg =  "Image uploaded";
        //     site_alert($msg,'success');
        // }
        // else{
        //     $msg =  "Invalid Image";
        // }
        // echo json_encode(array('msg'=>$msg));
        $msg = '';
        $path = "uploads/splash/";
        if (!file_exists($path)) {   
            mkdir($path, 0777, true);
        }
        $file_name = $_FILES['fileUpload']['name'];
        $file_size =$_FILES['fileUpload']['size'];
        $file_tmp =$_FILES['fileUpload']['tmp_name'];
        $file_type=$_FILES['fileUpload']['type'];
        $file_ext=strtolower(end(explode('.',$_FILES['fileUpload']['name'])));
        $expensions= array("jpeg","jpg","png","mp4");         
        if(in_array($file_ext,$expensions)=== false){
           $msg = 'extension not allowed, please choose a JPEG, PNG and MP4 file.';
        }          
        if($msg == ''){
            move_uploaded_file($file_tmp,$path.$file_name);
            $items = array(
                "img_file_name"=>$file_name,
                "img_path"=>$path.$file_name,
                "img_tbl"=>$img_type,
            );
            $id = $this->site_model->add_tbl('images',$items);
            $msg =  "Image uploaded";
            site_alert($msg,'success');
        }
        echo json_encode(array('msg'=>$msg));
    }
    public function details_db(){
        $this->load->model('dine/setup_model');
        $this->load->model('dine/main_model');

        // $img = '';
        // $img = $_FILES['complogo']['tmp_name'];
            // $img = file_get_contents($tmp_name);
        // if(is_uploaded_file($_FILES['complogo']['tmp_name'])){
        //     $tmp_name = $_FILES['complogo']['tmp_name'];
        //     $img = file_get_contents($tmp_name);
        // }
        // echo 'IMAGE : '.$img;
        $items = array(
            "branch_code"=>$this->input->post('branch_code'),
            "branch_name"=>$this->input->post('branch_name'),
            "branch_desc"=>$this->input->post('branch_desc'),
            "contact_no"=>$this->input->post('contact_no'),
            "delivery_no"=>$this->input->post('delivery_no'),
            "address"=>$this->input->post('address'),
            "tin"=>$this->input->post('tin'),
            "machine_no"=>$this->input->post('machine_no'),
            "bir"=>$this->input->post('bir'),
            "serial"=>$this->input->post('serial'),
            "permit_no"=>$this->input->post('permit_no'),
            // "serial"=>$this->input->post('serial'),
            "accrdn"=>$this->input->post('accrdn'),
            "email"=>$this->input->post('email'),
            "website"=>$this->input->post('website'),
            "branch_color"=>$this->input->post('branch_color'),
            "is_multibrand"=>$this->input->post('is_multibrand'),
            "brand"=>$this->input->post('brand'),
            "store_open" => date("H:i:s",strtotime($this->input->post('store_open'))),
            "store_close" => date("H:i:s",strtotime($this->input->post('store_close'))),
            // "rob_path" => $this->input->post('rob_path'),
            // "rob_username" => $this->input->post('rob_username'),
            // "rob_password" => $this->input->post('rob_password'),
            // "img"=>$img
            // "currency"=>$this->input->post('currency')
        );

            $this->setup_model->update_details($items, 1);
            $this->main_model->update_tbl('branch_details','branch_id',$items,1);
            // $id = $this->input->post('cat_id');
            $act = 'update';
            $msg = 'Updated Branch Details';

        echo json_encode(array('msg'=>$msg));
    }
    public function pos_settings_db(){
        $this->load->model('dine/setup_model');
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/main_model');
        $ctrl = "";
        foreach($this->input->post('chk') as $val){
            $ctrl .= $val.','; 
        }

        $ctrl = substr($ctrl, 0, -1);
        //echo $ctrl;

        $items = array(
            "no_of_receipt_print" => (int)$this->input->post('no_of_receipt_print'),
            "no_of_order_slip_print" => (int)$this->input->post('no_of_order_slip_print'),
            "kitchen_printer_name" => $this->input->post('kitchen_printer_name'),
            "kitchen_printer_name_no" => (int)$this->input->post('kitchen_printer_name_no'),
            "kitchen_beverage_printer_name" => $this->input->post('kitchen_beverage_printer_name'),
            "kitchen_beverage_printer_name_no" => (int)$this->input->post('kitchen_beverage_printer_name_no'),
            "open_drawer_printer" => $this->input->post('open_drawer_printer'),
            "local_tax" => $this->input->post('local_tax'),
            "controls"=> $ctrl,
            "loyalty_for_amount" => $this->input->post('loyalty_for_amount'),
            "loyalty_to_points" => $this->input->post('loyalty_to_points'),
            "neg_inv" => $this->input->post('neg_inv'),
            "show_image" => $this->input->post('show_image'),
            "ceiling_amount" => $this->input->post('ceiling_amount'),
            "ceiling_mcb" => $this->input->post('ceiling_mcb'),
        );
        $this->cashier_model->update_pos_settings($items, 1);
        $this->main_model->update_tbl('settings','id',$items,1);
        $act = 'update';
        $msg = 'Updated Branch Details';

        $items2 = array(
            "rec_footer"=>str_replace ("\r\n", "<br>", $this->input->post('rec_footer')),
            "pos_footer"=>str_replace ("\r\n", "<br>", $this->input->post('pos_footer')),
        );
        $this->setup_model->update_details($items2, 1);
        $this->main_model->update_tbl('branch_details','branch_id',$items2,1);

        echo json_encode(array('msg'=>$msg));
    }
    public function pos_database_db(){
        $this->load->model('dine/setup_model');
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/main_model');
        $items = array(
            "backup_path" => $this->input->post('backup_path'),
        );
        $this->cashier_model->update_pos_settings($items, 1);
        $this->main_model->update_tbl('settings','id',$items,1);
        $act = 'update';
        $msg = 'Updated Database Details';
        echo json_encode(array('msg'=>$msg));
    }
    public function download_backup_db(){
        $this->load->model('dine/cashier_model');
        $set = $this->cashier_model->get_pos_settings();
        $backup_folder = "C:/xampp/htdocs/dine/backup";
        if(iSetObj($set,'backup_path')){
            $backup_folder = iSetObj($set,'backup_path');
        }
        if (!file_exists($backup_folder)) { 
            $backup_folder = "C:/xampp/htdocs/dine/backup";
        }    
        $file_path = $backup_folder."/main";
        if (!file_exists($file_path)) {   
            mkdir($file_path, 0777, true);
        }
        $fileB = "main_db.sql";
        $this->db = $this->load->database('main', TRUE);        
        $this->load->dbutil();
        $prefs = array(
            "format" => 'txt',
            'ignore' => array('ci_sessions','logs')
        );
        $backup =& $this->dbutil->backup($prefs); 
        $this->db = $this->load->database('default', TRUE);     
        $this->load->helper('file');
        write_file($file_path.'/'.$fileB, $backup);
        if(file_exists($file_path.'/'.$fileB)){
            site_alert("Backed Up Successfully",'success');
            $this->load->helper('download');
            force_download('main_db.sql', $backup);
        }
        else{
            site_alert("Back Up failed",'error');
        }
        // redirect(base_url()."setup/details",'refresh');
        header("Location:".base_url()."setup/details");
        // header("Location:".base_url()."shift");
    }
	//-----------Branch Details-----end-----allyn
    public function printer(){
        $this->load->model('dine/setup_model');
        $this->load->model('dine/cashier_model');
        $this->load->helper('dine/setup_helper');
        // $details = $this->setup_model->get_details(1);
        // $det = $details[0];
        // $set = $this->cashier_model->get_pos_settings();
        // $splashes = $this->site_model->get_image(null,null,'splash_images');

        $data = $this->syter->spawn('setup');
        $data['page_subtitle'] = 'Edit Printer Setup';
        $data['code'] = makePrinterForm();
        // $data['add_js'] = array('js/plugins/timepicker/bootstrap-timepicker.min.js');
        // $data['add_css'] = array('css/timepicker/bootstrap-timepicker.min.css');
        $data['load_js'] = 'dine/setup.php';
        $data['use_js'] = 'printerJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
    }
    public function printer_setup(){
        $this->load->model('dine/setup_model');
        $this->load->model('dine/cashier_model');
        $this->load->helper('dine/setup_helper');
        $this->load->helper('site/site_forms_helper');
        $printers = $this->setup_model->get_printers();
        $data = $this->syter->spawn('setup');
        $data['page_title'] = fa('icon-doc')." Printer Setup";
        $th = array('Printer Name');
        // $data['page_subtitle'] = 'Receipt Discounts Management';
        // $data['code'] = site_list_form(
        //                     "setup/printers_form"
        //                     , "printer_setup_form"
        //                     , "Printer Setup"
        //                     , $printers
        //                     , 'printer_name'
        //                     , 'id'
        //                     ,REMOVE_MASTER_BUTTON);
        // $data['add_js'] = 'js/site_list_forms.js';
        // $this->load->view('page',$data);
        // $data['page_title'] = fa('icon-user').' User Roles';
        $data['code'] = create_rtable('printer_setup_form','id','printer-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'site/admin';
        $data['use_js'] = 'printerJs';
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $this->load->view('page',$data);

    }
    public function printers_form($ref=null){
        $this->load->helper('dine/setup_helper');
        $this->load->model('dine/setup_model');
        $data = $this->syter->spawn('setup');
        $printers = array();
        if($ref != null){
            $printers = $this->setup_model->get_printers($ref);
            $printers = $printers[0];
        }
        $data['code'] = makePrinterSetupForm($printers);
        $data['load_js'] = 'site/admin';
        $data['use_js'] = 'printerFormJs';
        $this->load->view('page',$data);
    }
    public function printer_setup_db()
    {
        $this->load->model('dine/setup_model');
        $this->load->model('dine/main_model');
        $items = array(
            "printer_name"=>$this->input->post('printer_name'),
            "printer_name_assigned"=>$this->input->post('printer_name_assigned'),
            "sub_cat"=>$this->input->post('sub_cat'),
            "no_prints"=>$this->input->post('no_prints'),
            // "no_tax"=>(int)$this->input->post('no_tax'),
            // "fix"=>(int)$this->input->post('fix'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('id')){
            $this->setup_model->update_printer_setup($items, $this->input->post('id'));
            $id = $this->input->post('id');
            $act = 'update';
            $msg = 'Updated Receipt Discount: '.$items['printer_name'];
            $this->main_model->update_tbl('printers','id',$items,$id);
        }else{
            $id = $this->setup_model->add_printer_setup($items);
            $act = 'add';
            $msg = 'Added New Receipt Discount: '.$items['printer_name'];
            $this->main_model->add_trans_tbl('printers',$items);
        }
        echo json_encode(array("id"=>$id,"desc"=>$items['printer_name'],"act"=>$act,'msg'=>$msg));
    }
    public function get_printers($id=null,$asJson=true){
        $post = array();
        $page = "";
        // die('haha');
        // $joinTables['user_roles'] = array('content'=>'users.role = user_roles.id');
        $joinTables = array();
        $select = 'printers.*';
        // $args["user_roles.id != 1"] = array('use'=>'where','val'=>null,'third'=>false);
        $items = $this->site_model->get_tbl('printers',array(),array('printers.id'=>'desc'),$joinTables,true,$select);
        // echo "<pre>",print_r($items),"</pre>";die();
        $json = array();
        if(count($items) > 0){
            foreach ($items as $res) {
                $json[$res->id] = array(
                    // "id"=>$res->id,   
                    // "role"=>$res->role,     
                    "printer_name"=>$res->printer_name   
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
    public function download_printer(){
        // $joinTables['user_roles'] = array('content'=>'users.role = user_roles.id');
        $joinTables = array();
        $select = 'printer_name';
        $items = $this->site_model->get_tbl('printers',array(),array('printers.id'=>'asc'),$joinTables,true,$select);
        
        
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

}