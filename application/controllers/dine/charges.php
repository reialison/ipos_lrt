<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Charges extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('dine/settings_model');
		$this->load->helper('dine/settings_helper');
		$this->load->helper('site/site_forms_helper');
	}
	public function index(){
     	$data = $this->syter->spawn('charges');
     	$list = $this->settings_model->get_charges();
        $data['page_title'] = fa('icon-equalizer')." Charges";
     	// $data['code'] = site_list_form("charges/form","charge_form","Charges",$list,array('charge_code','charge_name'),'charge_id',REMOVE_MASTER_BUTTON);
     	// $data['add_js'] = 'js/site_list_forms.js';
        if(MASTER_BUTTON_EDIT){
            $th = array('ID','Name','Code','Amount','Inactive','');
        }else{
            $th = array('ID','Name','Code','Amount','Inactive');
        }
        $data['code'] = create_rtable('charges','charge_id','charges-tbl',$th,'',false,'list',REMOVE_MASTER_BUTTON);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'chargeslistFormJs';
        $data['page_no_padding'] = true;
        $this->load->view('page',$data);
	}
    public function load_charges($termninal=0){
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
        
        if($this->input->post('charge_name')){
            $lk  =$this->input->post('charge_name');
            $args["(charges.charge_name like '%".$lk."%' "] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('inactive')){
            $args['charges.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        $join = null;
        $count = $this->site_model->get_tbl('charges',$args,array(),$join,true,'*',null,null,true);
        $page = paginate('brands/load_charges',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('charges',$args,array(),$join,true,'*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            if(MASTER_BUTTON_EDIT){
                 foreach ($items as $res) {
                    // if($termninal == 1){
                    //     $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'pos_customers/customer_terminal_form/'.$res->disc_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                   
                    // }
                    // else{
                        $link = $this->make->A(fa('fa-edit fa-lg').'  Edit',base_url().'charges/form/'.$res->charge_id,array('class'=>'btn blue btn-sm btn-outline','return'=>'true'));                 
                    // }
                    $json[$res->charge_id] = array(
                        "id"=>$res->charge_id,   
                        "title"=>$res->charge_name,   
                        "desc"=>$res->charge_code,   
                        "charge_amount"=>$res->charge_amount,   
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                        "link"=>$link
                    );
                }
            }else{
                foreach ($items as $res) {
                    
                    $json[$res->charge_id] = array(
                        "id"=>$res->charge_id,   
                        "title"=>$res->charge_name,   
                        "desc"=>$res->charge_code,  
                        "charge_amount"=>$res->charge_amount,    
                        // "disc_rate"=>$res->disc_rate,   
                        // "fix"=>$res->fix,   
                        "inactives"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    );
                }
            }
           
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
	public function form($ref=null){
        $data = $this->syter->spawn('charges');
        $item = array();
        if($ref != null){
            $items = $this->settings_model->get_charges($ref);
            $item = $items[0];
        }
        $data['code'] = makeChargeForm($item);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'chargeslistFormJs';
        $this->load->view('page',$data);
    }
    public function db(){
        $this->load->model('dine/main_model');
        $this->load->model('dine/manager_model');
        $items = array(
            "charge_code"=>$this->input->post('charge_code'),
            "charge_name"=>$this->input->post('charge_name'),
            "charge_amount"=>$this->input->post('charge_amount'),
            "no_tax"=>$this->input->post('no_tax'),
            "absolute"=>$this->input->post('absolute'),
            "inactive"=>$this->input->post('inactive'),
        );
        if($this->input->post('charge_id')){
            $this->settings_model->update_charges($items,$this->input->post('charge_id'));
            $id = $this->input->post('charge_id');
            $act = 'update';
            $msg = 'Updated Charge. '.$this->input->post('charge_name');
            $this->main_model->update_tbl('charges','charge_id',$items,$id);
            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();
                foreach($terminals as $ctr => $vals){

                    //check if meron
                    $where = array('charge_id'=>$id);
                    $chk = $this->manager_model->get_details($where,'charges',$vals->terminal_code);

                    if(count($chk) > 0){
                        $this->settings_model->update_charges($items,$this->input->post('charge_id'),$vals->terminal_code);
                    }else{
                        $items = array(
                            "charge_id"=>$id,
                            "charge_code"=>$this->input->post('charge_code'),
                            "charge_name"=>$this->input->post('charge_name'),
                            "charge_amount"=>$this->input->post('charge_amount'),
                            "no_tax"=>$this->input->post('no_tax'),
                            "absolute"=>$this->input->post('absolute'),
                            "inactive"=>$this->input->post('inactive'),
                        );
                        $this->settings_model->add_charges($items,$vals->terminal_code);
                    }

                }

            }
        }else{
            $dets = $this->settings_model->get_last_ids('charge_id','charges');
            if($dets){
                $id = $dets[0]->charge_id + 1;
            }else{
                $id = 1;
            }
            $items['charge_id'] = $id;
            $this->settings_model->add_charges($items);
            // $id = $this->input->post('charge_id');
            $act = 'add';
            $msg = 'Added  new Charge'.$this->input->post('charge_name');
            $this->main_model->add_trans_tbl('charges',$items);
            if(CONSOLIDATOR){
                $terminals = $this->manager_model->get_terminals();

                foreach($terminals as $ctr => $vals){
                    $this->settings_model->add_charges($items,$vals->terminal_code);
                }

            }
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('charge_code')." ".$this->input->post('charge_name'),"act"=>$act,'msg'=>$msg));
    }
}