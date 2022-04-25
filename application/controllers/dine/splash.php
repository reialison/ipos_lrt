<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Splash extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('dine/splash_helper');
    }
    public function index(){
        $data = $this->syter->spawn(null,false);
        $data['code'] = splashPage();
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        //$data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/splash.php';
        $data['use_js'] = 'splashJs';
        $data['noNavbar'] = true; /*displays the navbar. Uncomment this line to hide the navbar.*/
        $this->load->view('cashier',$data);
    }
    public function end_trans(){
        $data = $this->syter->spawn(null,false);
        $data['code'] = splashPage();
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        //$data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/splash.php';
        $data['use_js'] = 'endtransJs';
        $data['noNavbar'] = true; /*displays the navbar. Uncomment this line to hide the navbar.*/
        $this->load->view('cashier',$data);
    }
    public function check_trans(){
        $trans_cart = array();
        if($this->session->userData('trans_cart')){
            $trans_cart = $this->session->userData('trans_cart');
        }
        echo json_encode(array('ctr'=>count($trans_cart)));
    }
    public function commercial(){
        $data = $this->syter->spawn(null,false);
        $splashes = $this->site_model->get_image(null,null,'background_images');
        $data['code'] = commercialPage($splashes);
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        //$data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/splash.php';
        $data['use_js'] = 'splashComJs';
        $this->load->view('load',$data);
    }
    public function end_trans_images(){
        $data = $this->syter->spawn(null,false);
        $splashes = $this->site_model->get_image(null,null,'endtrans_images');
        $data['code'] = commercialPage($splashes);
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        //$data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/splash.php';
        $data['use_js'] = 'splashComJs';
        $this->load->view('load',$data);
    }

    public function transactions_old(){
        $this->load->model('dine/cashier_model');
        $data = $this->syter->spawn(null,false);
        $splashes = $this->site_model->get_image(null,null,'splash_images');
        $user = $this->session->userdata('user');
        $set = $this->cashier_model->get_pos_settings();
        // echo "<pre>",print_r($bat),"</pre>";die();
        $data['code'] = transactionPage($splashes,$user,$set);
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        //$data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/splash.php';
        $data['use_js'] = 'splashTransJs';
        $data['noNavbar'] = true; /*displays the navbar. Uncomment this line to hide the navbar.*/
        $this->load->view('cashier',$data);
    }
    public function get_counter(){
        $counter = sess('counter');
        $counter['type'] = strtoupper($counter['type']);
        $trans_cart = sess('trans_cart');
        $charges = sess('trans_charge_cart');
        $trans_mod_cart = sess('trans_mod_cart');
        // echo var_dump($trans_cart);
        $code = "";
        // echo "<pre>",print_r($trans_mod_cart),"<pre>";die();
        $this->make->sUl();
        $modifiers = array();

        foreach($trans_mod_cart as $mod){
            $modifiers[$mod['trans_id']][] = $mod;
        }

        foreach ($trans_cart as $line_id => $opt) {
            $qty = $this->make->span($opt['qty'],array('class'=>'qty','return'=>true));
            $name = $this->make->span($opt['name'],array('class'=>'name','return'=>true));
            $cost = $this->make->span(number_format($opt['cost'],2),array('class'=>'cost splash-item-breakdown-cost','return'=>true));
            $price = $opt['cost'];
            $this->make->li($qty."&nbsp×&nbsp&nbsp ".$name." ".$cost);
            if(isset($opt['remarks']) && $opt['remarks'] != ""){
                $remarks = $this->make->span(fa('fa-text-width').' '.ucwords($opt['remarks']),array('class'=>'name','style'=>'','return'=>true));
                $this->make->li($remarks);
            }
            if(isset($modifiers[$line_id]) && count($modifiers[$line_id]) > 0){
                $this->make->sDiv(array('class'=>'modifiers-div',));
                foreach ($modifiers[$line_id] as $mmeu_id => $mod) {
                    $mod_id = $mod['menu_id'];
                    $name = $this->make->span($mod['name'],array('class'=>'name modifiers-breakdown','style'=>'','return'=>true));
                    $cost = "";
                    if($mod['cost'] > 0 )
                        $cost = $this->make->span(number_format($mod['cost'],2),array('class'=>'cost modifiers-breakdown-cost','return'=>true));
                        $this->make->li($name." ".$cost,array('class'=>'modifiers-li'));
                    $price += $mod['cost'];
                }
                $this->make->eDiv();
            }

        }
        if(count($charges) > 0){
            foreach ($charges as $charge_id => $ch) {
                $qty = $this->make->span(fa('fa fa-tag'),array('class'=>'qty','return'=>true));
                $name = $this->make->span($ch['name'],array('class'=>'name','return'=>true));
                $tx = $ch['amount'];
                if($ch['absolute'] == 0)
                    $tx = $ch['amount']."%";
                $cost = $this->make->span($tx,array('class'=>'cost','return'=>true));
                $this->make->li($qty." ".$name." ".$cost);
            }
        }
        $this->make->eUl();
        $code = $this->make->code();
        echo json_encode(array('counter'=>$counter,'code'=>$code) );
    }
    public function transactions(){
        $this->load->model('dine/cashier_model');
        $data = $this->syter->spawn(null,false);
        $splashes = $this->site_model->get_image(null,null,'splash_images');
        $user = $this->session->userdata('user');
        $set = $this->cashier_model->get_pos_settings();
        // echo "<pre>",print_r($bat),"</pre>";die();
        $data['code'] = new_splashview($splashes,$user,$set);
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css','css/momentsplash.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        //$data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/splash.php';
        $data['use_js'] = 'momentsplashJs';
        $data['noNavbar'] = true; /*displays the navbar. Uncomment this line to hide the navbar.*/
        $this->load->view('moment_splash',$data);

    }
    public function get_change(){
        $changes = sess('payment_change');
        $tendered = 0;
        $change = 0;
        // echo "<pre>",print_r($changes),"</pre>";
        if($changes){
            // foreach ($changes as $key => $value) {
            if($changes['type'] == 'cash' || $changes['type'] == 'cod'){
                $tendered = $changes['tendered'];
                $change = $changes['change'];
            }
            // }
        }
        // echo $tendered;
        echo json_encode(array('tendered'=>$tendered,'change'=>$change) );
    }

}