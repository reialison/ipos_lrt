<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (realpath(dirname(__FILE__) . '/..')."/dine/cashier.php");
class Discount extends cashier {
    var $data = null;
    public function __construct(){
        parent::__construct();
        $this->load->helper('core/discount_helper');  
        $this->load->model('dine/cashier_model'); 
        $this->load->model('site/site_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/menu_model');
        $this->load->model('dine/clock_model');
        $this->load->model('dine/settings_model');
        $this->load->model('dine/setup_model');
        $this->load->model('third_party/Migrator_model');
        $this->load->helper('core/string_helper');     
    }
    public function discount_pop(){
        $discounts = $this->cashier_model->get_dicounts();
        sess_clear('disc_session');

        $trans_cart = array();
        if($this->session->userData('trans_cart')){
            $trans_cart = $this->session->userData('trans_cart');
        }
        // echo "<pre>",print_r($trans_cart),"</pre>";die();

        $trans_mod_cart = array();
        if($this->session->userData('trans_mod_cart')){
            $trans_mod_cart = $this->session->userData('trans_mod_cart');
            // echo var_dump($trans_mod_cart);
            // return false;
        }

        $trans_submod_cart = array();
        if($this->session->userData('trans_submod_cart')){
            $trans_submod_cart = $this->session->userData('trans_submod_cart');
            
        }
        $total = 0;
        $alcohol=0;
        if(count($trans_cart) > 0){
            foreach ($trans_cart as $trans_id => $trans){
                if(isset($trans['cost']))
                    $cost = $trans['cost'];
                if(isset($trans['price']))
                    $cost = $trans['price'];

                if(isset($trans['modifiers'])){
                    foreach ($trans['modifiers'] as $trans_mod_id => $mod) {
                        if($trans_id == $mod['line_id']){
                            $cost += $mod['price'];
                            
                            if(isset($mod['submodifiers'])){
                                foreach($mod['submodifiers'] as $sub_id => $smod){
                                    if($smod['mod_line_id'] == $mod['mod_line_id']){
                                        $cost += $smod['price'];
                                    }
                                }
                            }
                        }


                    }
                    // echo '1';
                }else{
                    if(count($trans_mod_cart) > 0){
                        // echo '2';
                        foreach ($trans_mod_cart as $trans_mod_id => $mod) {
                            if($trans_id == $mod['trans_id']){
                                $cost += $mod['cost'];
                            
                                if(isset($trans_submod_cart)){
                                    foreach ($trans_submod_cart as $trans_submod_id => $submod) {
                                        if($trans_mod_id == $submod['mod_line_id'])
                                            $cost += $submod['price'];
                                    }
                                }

                            }
                        }
                    }
                }

                // if(isset($trans['modifiers'])){
                //     foreach ($trans['modifiers'] as $trans_mod_id => $mod) {
                //         if($trans_id == $mod['line_id'])
                //             $cost += $mod['price'];
                //     }
                // }

                // else{
                //     if(count($trans_mod_cart) > 0){
                //         foreach ($trans_mod_cart as $trans_mod_id => $mod) {
                //             if($trans_id == $mod['trans_id'])
                //                 $cost += $mod['cost'];
                //         }
                //     }
                // }
                // if(isset($counter['zero_rated']) && $counter['zero_rated'] == 1){
                //     $rate = 0.12;
                //     // $cost = num(($cost / $rate),2);
                //     // $cost = ($cost / $rate);
                //     $zr = ($cost * $rate);
                //     $cost = $cost-$zr;
                //     $zero_rated += $trans['qty'] * $cost;
                //     $zero_r = 1;
                // }
                $total += $trans['qty'] * $cost;

                $where = array('menu_id'=>$trans['menu_id']);
                $men = $this->site_model->get_details($where,'menus');

                if(isset($men[0]->alcohol) && $men[0]->alcohol == 1){
                    $alcohol += $cost * $trans['qty'];
                }
                // echo $trans['nocharge'].'BRBR';
                // $cart_total_qty += $trans['qty'];
            }
        }

        $typeCN = sess('trans_type_cart');
        $disc_cart = array();
        if($this->session->userData('trans_multi_disc_cart')){
            $disc_cart = $this->session->userData('trans_multi_disc_cart');
        }
        $guest = 1;
        if($typeCN){
            if(isset($typeCN[0]['guest']))
                $guest = $typeCN[0]['guest'];
        }
        // echo "<pre>",print_r($disc_cart),"</pre>";die();
        foreach($disc_cart as $id => $vals){
            $row = $disc_cart[$id];
            $disc_count = isset($vals['persons']) ? count($vals['persons']) : 1;
            // $discounts = $this->settings_model->get_receipt_discounts($disc_id);
            if($vals['disc_code'] == 'SNDISC'){
                $divi = ($total-$alcohol)/$guest;
            }else{
                $divi = $total/$guest;
            }
            // $divi = $total/$row['guest'];
            $divi_less = $divi;
            $discount = 0;
            $lv = 0;

            if($vals['disc_code'] == ATHLETE_CODE){
                // if($vals['no_tax'] == 1){
                    $divi_less = ($divi / 1.12);
                    $lv = $divi - $divi_less;
                // }
                // $no_persons = count($row['persons']);
                // foreach ($row['persons'] as $code => $per) {
                    // $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $divi_less);
                $discount = ($vals['disc_rate'] / 100) * $divi_less;
                $less_vat = 0;
            }else{

                if($vals['fix'] != 1){
                    if($vals['no_tax'] == 1){
                        $divi_less = ($divi / 1.12);
                        $lv = $divi - $divi_less;
                    }
                    // $no_persons = count($row['persons']);
                    // foreach ($row['persons'] as $code => $per) {
                        // $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $divi_less);
                    $discount = ($vals['disc_rate'] / 100) * $divi_less;
                    $less_vat = $lv;
                }else{

                    if($vals['disc_rate'] == 0){
                        $discount = $vals['openamt'];
                    }else{
                        $discount = $vals['disc_rate'];
                    }

                    $less_vat = 0;
                }


            }

            $row['disc_amount'] = $discount * $disc_count;
            $row['less_vat'] = $less_vat * $disc_count;
            $row['disc_count'] = $disc_count;
            $d_det = $this->site_model->get_tbl('receipt_discounts',array('disc_code'=>$vals['disc_code']),array(),null,true);
            $disc_name = $d_det[0]->disc_name;
            $row['disc_name'] = $disc_name;

            $disc_cart[$id] = $row;

        }

        sess_initialize('trans_multi_disc_cart',$disc_cart);

        $data['code'] = makediscountform($discounts,$total,$typeCN,$disc_cart,$guest);
        $data['add_css'] = array('css/cashier.css','css/onscrkeys.css','css/virtual_keyboard.css','js/typeaheadmap/typeaheadmap.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js','js/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/cashier.php';
        $data['use_js'] = 'discountJS';
        $this->load->view('load',$data);
    }

    public function discount_settle_pop(){
        $discounts = $this->cashier_model->get_dicounts();
        sess_clear('disc_session');

        $trans_cart = array();
        if($this->session->userData('trans_cart')){
            $trans_cart = $this->session->userData('trans_cart');
        }
        // echo "<pre>",print_r($trans_cart),"</pre>";die();

        $trans_mod_cart = array();
        if($this->session->userData('trans_mod_cart')){
            $trans_mod_cart = $this->session->userData('trans_mod_cart');
            // echo var_dump($trans_mod_cart);
            // return false;
        }
        $total = 0;
        $alcohol=0;
        if(count($trans_cart) > 0){
            foreach ($trans_cart as $trans_id => $trans){
                if(isset($trans['cost']))
                    $cost = $trans['cost'];
                if(isset($trans['price']))
                    $cost = $trans['price'];

                if(isset($trans['modifiers'])){
                    foreach ($trans['modifiers'] as $trans_mod_id => $mod) {
                        if($trans_id == $mod['line_id']){
                            $cost += $mod['price'];
                            
                            if(isset($mod['submodifiers'])){
                                foreach($mod['submodifiers'] as $sub_id => $smod){
                                    if($smod['mod_line_id'] == $mod['mod_line_id']){
                                        $cost += $smod['price'];
                                    }
                                }
                            }
                        }


                    }
                    // echo '1';
                }else{
                    if(count($trans_mod_cart) > 0){
                        // echo '2';
                        foreach ($trans_mod_cart as $trans_mod_id => $mod) {
                            if($trans_id == $mod['trans_id']){
                                $cost += $mod['cost'];
                            
                                if(isset($trans_submod_cart)){
                                    foreach ($trans_submod_cart as $trans_submod_id => $submod) {
                                        if($trans_mod_id == $submod['mod_line_id'])
                                            $cost += $submod['price'];
                                    }
                                }

                            }
                        }
                    }
                }

                // if(isset($trans['modifiers'])){
                //     foreach ($trans['modifiers'] as $trans_mod_id => $mod) {
                //         if($trans_id == $mod['line_id'])
                //             $cost += $mod['price'];
                //     }
                // }

                // else{
                //     if(count($trans_mod_cart) > 0){
                //         foreach ($trans_mod_cart as $trans_mod_id => $mod) {
                //             if($trans_id == $mod['trans_id'])
                //                 $cost += $mod['cost'];
                //         }
                //     }
                // }
                // if(isset($counter['zero_rated']) && $counter['zero_rated'] == 1){
                //     $rate = 0.12;
                //     // $cost = num(($cost / $rate),2);
                //     // $cost = ($cost / $rate);
                //     $zr = ($cost * $rate);
                //     $cost = $cost-$zr;
                //     $zero_rated += $trans['qty'] * $cost;
                //     $zero_r = 1;
                // }
                $total += $trans['qty'] * $cost;

                $where = array('menu_id'=>$trans['menu_id']);
                $men = $this->site_model->get_details($where,'menus');

                if(isset($men[0]->alcohol) && $men[0]->alcohol == 1){
                    $alcohol += $cost * $trans['qty'];
                }
                // echo $trans['nocharge'].'BRBR';
                // $cart_total_qty += $trans['qty'];
            }
        }

        $typeCN = sess('trans_type_cart');
        $disc_cart = array();
        if($this->session->userData('trans_multi_disc_cart')){
            $disc_cart = $this->session->userData('trans_multi_disc_cart');
        }
        $guest = 1;
        if($typeCN){
            if(isset($typeCN[0]['guest']))
                $guest = $typeCN[0]['guest'];
        }
        // echo "<pre>",print_r($disc_cart),"</pre>";die();
        foreach($disc_cart as $id => $vals){
            $row = $disc_cart[$id];
            $disc_count = isset($vals['persons']) ? count($vals['persons']) : 1;
            // $discounts = $this->settings_model->get_receipt_discounts($disc_id);
            if($vals['disc_code'] == 'SNDISC'){
                $divi = ($total-$alcohol)/$guest;
            }else{
                $divi = $total/$guest;
            }
            // $divi = $total/$row['guest'];
            $divi_less = $divi;
            $discount = 0;
            $lv = 0;

            if($vals['disc_code'] == ATHLETE_CODE){
                // if($vals['no_tax'] == 1){
                    $divi_less = ($divi / 1.12);
                    $lv = $divi - $divi_less;
                // }
                // $no_persons = count($row['persons']);
                // foreach ($row['persons'] as $code => $per) {
                    // $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $divi_less);
                $discount = ($vals['disc_rate'] / 100) * $divi_less;
                $less_vat = 0;
            }else{

                if($vals['fix'] != 1){
                    if($vals['no_tax'] == 1){
                        $divi_less = ($divi / 1.12);
                        $lv = $divi - $divi_less;
                    }
                    // $no_persons = count($row['persons']);
                    // foreach ($row['persons'] as $code => $per) {
                        // $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $divi_less);
                    $discount = ($vals['disc_rate'] / 100) * $divi_less;
                    $less_vat = $lv;
                }else{

                    if($vals['disc_rate'] == 0){
                        $discount = $vals['openamt'];
                    }else{
                        $discount = $vals['disc_rate'];
                    }

                    $less_vat = 0;
                }


            }

            $row['disc_amount'] = $discount * $disc_count;
            $row['less_vat'] = $less_vat * $disc_count;
            $row['disc_count'] = $disc_count;
            $d_det = $this->site_model->get_tbl('receipt_discounts',array('disc_code'=>$vals['disc_code']),array(),null,true);
            $disc_name = $d_det[0]->disc_name;
            $row['disc_name'] = $disc_name;

            $disc_cart[$id] = $row;

        }

        sess_initialize('trans_multi_disc_cart',$disc_cart);

        $data['code'] = makediscountform($discounts,$total,$typeCN,$disc_cart,$guest);
        $data['add_css'] = array('css/cashier.css','css/onscrkeys.css','css/virtual_keyboard.css','js/typeaheadmap/typeaheadmap.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js','js/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/cashier.php';
        $data['use_js'] = 'discountSettleJS';
        $this->load->view('load',$data);
    }

    public function codes_json(){
        $key = $this->input->post('key');
        $ref = $this->input->post('ref');

        $results = $this->cashier_model->asearch_items($key,$ref);
        $codes = array();
        foreach ($results as $res) {
            $codes[] = array("id"=>$res->id,"value"=>$res->value);
        }
        echo json_encode($codes);
    }
    
}