<?php
// use Restserver\Libraries\REST_Controller;
// require(APPPATH.'/libraries/REST_Controller.php');

// use armscor_pos\Libraries\REST_Controller;

class Api extends CI_Controller{

    var $environment = master_envi;
    var $prod_url = master_api . 'api/';
    var $dev_url = 'http://localhost/ipos_max_hq/api/';
 
    public function test(){
        // $this->load->model('site/site_model');
        // $data = array('Name' => 'John', 'Age' => '24'); 

        // $opts = array( 
        //     'http' => array( 
        //     'method' => 'POST', 
        //     'content' => http_build_query($data)) 
        // ); 

        // $context  = stream_context_create($opts);

        // $result = file_get_contents('http://localhost:8000/test', true, $context);

        // print_r($result);
    }

    public function execute_migration_v2(){
        date_default_timezone_set('Asia/Manila');
        $this->load->model('site/api_model');

        $has_ic = $this->has_ic(); // check if has internet connection

        $this->load->model('core/master_model');

        $go_migrate = true;

        $last_log = $this->master_model->check_last_log();
        if(!empty($last_log)){
            if($last_log->type != 'finish'){ // check if last migration is not yet finished
                $date_today = new DateTime();
                $time_diff = $date_today->diff(new DateTime($last_log->migrate_date));
                
                if($time_diff->i < 10){ // if last migration is within last 10 minutes don't migrate  , since it might be still migrating
                    $go_migrate = false;
                }
            
            }
        }
// $this->api_model->execute_migration_v2();
        if(MASTERMIGRATION  && $go_migrate) {   
            $exec = $this->api_model->execute_migration_v2();

            if( $exec){
                if(isset($_POST['ajax'])){
                    echo true;
                }else{
                    echo "<pre>",print_r($exec),"</pre>";die();
                    // return true;
                }
            }
        }

        // if(!$has_ic){
        //     echo "Please check your internet connection and try again.";
        // }

        
    }

    // check if has internet connection
    public function has_ic()
    {
        $connected = @fsockopen("www.example.com", 80); 
                                            //website, port  (try 80 or 443)
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }

        // var_dump($is_conn);die();
        return $is_conn;

    }

    function json(){
        $result = $this->db->limit(1)->get('trans_sales')->result();
        $result2 = $this->db->limit(1)->get('trans_sales_menus')->result();

        echo json_encode(array('trans_sales'=>$result,'trans_sales_menus'=>$result2));
    }

    public function download_masterfile($ajax=false){
        // var_dump(MASTERMIGRATION);die();
        $has_ic = $this->has_ic(); // check if has internet connection 
          $this->load->model('site/api_model');

         // $go_migrate = true;

        // $last_log = $this->master_model->check_last_log();
        // // var_dump($last_log);
        // if(!empty($last_log)){
        //  if($last_log->type != 'finish_'){ // check if last migration is not yet finished
        //      $date_today = new DateTime();
        //      $time_diff = $date_today->diff(new DateTime($last_log->migrate_date));

        //      if($time_diff->i < 5){ // if last migration is within last 5 minutes don't migrate  , since it might be still migrating
        //          $go_migrate = false;
        //      }
            
        //  }
        // }

        //  $go_migrate = true;
        // echo "
        if(MASTERMIGRATION){    

            $exec = $this->api_model->execute_migration_download_items();

            if( $exec){
                if(isset($_POST['ajax'])){
                    echo true;
                }else{
                    echo "<pre>",print_r($exec),"</pre>";die();
                    // return true;
                }
            }
        }

        if(!$has_ic){
            echo "Please check your internet connection and try again.";
        }
    }

    public function json_api(){
        $tables = array('trans_sales','trans_sales_charges','trans_sales_discounts','trans_sales_items',
                        'trans_sales_local_tax','trans_sales_loyalty_points','trans_sales_menu_modifiers',
                        'trans_sales_menu_submodifiers','trans_sales_menus','trans_sales_no_tax','trans_sales_payments',
                        'trans_sales_tax','trans_sales_zero_rated'
                    );
        $post=array();
        foreach($tables as $tbl){
            $post[$tbl] = $this->db->get($tbl)->result();
            // $post[$tbl] = $this->db->list_fields($tbl);
        }

        $dev_url = 'http://localhost/ipos_max_hq/api/migrate_data';
        // $dev_url = 'https://point1solution.net/max_hq_api/api/migrate_trans';
        // if($this->environment == 'dev'){
            $url = $dev_url;
        // }else{
            // $url = $this->prod_url.'migrate_data';
        // }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
            )
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            
            $result = json_decode($response);
            print_r($response);exit;
            
        }
    }

    public function set_key(){
        $key = md5($this->input->post('key'));

        // $dev_url = 'https://point1solution.net/max_hq_api/api/migrate_trans';
        if($this->environment == 'dev'){
            $url = $this->dev_url.'check_product_key';
        }else{
            $url = $this->prod_url.'check_product_key';
        }

        $post = array();
        $post['key'] = $key;
        $post['branch_code'] = BRANCH_CODE;
        $post['machine_id'] = $this->get_machine();
        $post['serial_number'] = $this->get_serial_number();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "post",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array('Content-Type:application/json',
            CURLOPT_SSL_VERIFYPEER=>false
            )
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'error';
        } else {
            // return 'success';

            
            $result = json_decode($response);
            // print_r($response);exit;
            if($result == ''){
                echo 'Product key is invalid.';
                return false;
            }else{
                if(!empty($result) && $result->status == 'valid'){
                    $myfile = fopen("application/config/constants.php", "a") or die("Unable to open file!");

                    // $ip = getHostByName(getHostName());
                    $machine_id = $this->get_machine();
                    $serial_number = $this->get_serial_number();

                    if(ENCRYPTED){                        
                        $txt = "define(base64_decode('".base64_encode('PRODUCT_KEY')."'),'".base64_encode($key)."');";
                    }else{
                        $txt = "define('PRODUCT_KEY','".base64_encode($key)."');";
                    }                     

                    fwrite($myfile, "\n". $txt);
                    fclose($myfile);

                    $myfile = fopen("pk.txt", "w");

                    fwrite($myfile, base64_encode($key).'p1pos'.base64_encode($machine_id).'p1pos'.base64_encode($serial_number));
                    fclose($myfile);

                }else{
                    echo 'Product key is invalid.';
                }
            }
            
        }

        
    }

    public function check_key(){
        if(!defined('PRODUCT_KEY')){
            echo 1;
            return false;
        }

        $has_ic = $this->has_ic();

        if($has_ic){
            if($this->environment == 'dev'){
                $url = $this->dev_url.'check_product_key';
            }else{
                $url = $this->prod_url.'check_product_key';
            }

            $post = array();
            $post['key'] = base64_decode(PRODUCT_KEY);
            $post['branch_code'] = BRANCH_CODE;
            $post['machine_id'] = $this->get_machine();
            $post['serial_number'] = $this->get_serial_number();

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "post",
                CURLOPT_POSTFIELDS => json_encode($post),
                CURLOPT_HTTPHEADER => array('Content-Type:application/json',
                CURLOPT_SSL_VERIFYPEER=>false
                )
                
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return 'error';
            } else {
                // return 'success';

                
                $result = json_decode($response);
                // print_r($response);exit;
                if(!empty($result) && $result->status == 'invalid'){
                    $fl = fopen("application/controllers/site.php", "w") or die("Unable to open file!");           

                    fwrite($fl, "");
                    fclose($fl);

                    echo 2;
                }
            }
        }else{
            $fl = "pk.txt";

            if(file_exists($fl)){
                $fn = fopen($fl,"r");

                $lcode = explode('p1pos',fgets($fn));
                $code = base64_decode($lcode[0]);
                $machine_id  =  base64_decode($lcode[1]);
                $serial_number  =  base64_decode($lcode[2]);
                $current_machine_id = $this->get_machine();
                $current_serial_number = $this->get_serial_number();

                if($code != base64_decode(PRODUCT_KEY) || $machine_id != $current_machine_id || $serial_number != $current_serial_number){
                    $fl = fopen("application/controllers/site.php", "w") or die("Unable to open file!");           

                    fwrite($fl, "");
                    fclose($fl);

                    echo 2;
                }
            
            }else{
                $fl = fopen("application/controllers/site.php", "w") or die("Unable to open file!");           

                fwrite($fl, "");
                fclose($fl);

                echo 2;
            } 
        }
        
    }

    function get_machine($salt = "") { 
       // get machine id
        ob_start(); // Turn on output buffering
            system('ipconfig /all'); //Execute external program to display output
            $mycom=ob_get_contents(); // Capture the output into a variable
        ob_clean(); // Clean (erase) the output buffer
         
        // $findme = "Physical";
        // $pmac = strpos($mycom, $findme); // Find the position of Physical text
        // $machine_id=substr($mycom,($pmac+36),17); // Get Physical Address


        // $findme = "Ethernet";
        // $pmac = strpos($mycom, $findme); // Find the position of Physical text
        // $machine_id=substr($mycom,($pmac+253),17); // Get Physical Address

        $lines = explode("\n", $mycom);
        $find = "Ethernet";
        $is_eth = false;

        $ethernet = '';
        foreach($lines as $num => $line){
          $pos = strpos($line, $find);

          if($pos !== false){
             $count = 0;
             foreach($lines as $ctr => $ctr_line){
              if($count < 6 && $ctr >= $num){
                $ethernet .= $ctr_line . "\n";
                $count++;
              }                
            }
          }
            
        }
        
        $findme = "Physical";
        $pmac = strpos($ethernet, $findme); // Find the position of Physical text
        $machine_id=substr($ethernet,($pmac+36),17); // Get Physical Address
        
        return trim($machine_id);    
    }

    function get_serial_number($salt = "") {       

        //get serial number
        ob_start(); // Turn on output buffering
          // $system = system('wmic DISKDRIVE GET SerialNumber 2>&1');
        $system = system('wmic diskdrive get serialNumber,size,mediaType');
        $mycom=ob_get_contents(); // Capture the output into a variable
        ob_clean(); // Clean (erase) the output buffer
        $findme = "Fixed hard disk";
        $pmac = strpos($mycom, $findme); // Find the position of Physical text
        $serial_number=substr($mycom,($pmac+22),40); // Get Physical Address
       
        return trim(explode(' ',trim($serial_number))[0]);
    }
    
}