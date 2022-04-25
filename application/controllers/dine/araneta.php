<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/reads.php");
class Araneta extends Reads {
	public function __construct(){
		parent::__construct();
		$this->load->helper('dine/araneta_helper');
		$this->load->model('dine/cashier_model');
		$this->load->model('core/trans_model');
	}
	public function index(){
        $data = $this->syter->spawn(null);
        $data['code'] = aranetaPage();
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/araneta.php';
        $data['use_js'] = 'aranetaPageJs';
        $this->load->view('load',$data);
	}
	public function files(){
		$data = $this->syter->spawn(null);
		$data['code'] = filesPage();
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/araneta.php';
		$data['use_js'] = 'fileJs';
		$this->load->view('load',$data);
	}
	public function daily_files(){
		$data = $this->syter->spawn(null);
		$data['code'] = dailyFilesPage();
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/araneta.php';
		$data['use_js'] = 'dailyFilesJS';
		$this->load->view('load',$data);
	}
	// public function daily_files(){
	// 	$date = $this->input->post('file_date');
	// 	$mon = array('01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'A','11'=>'B','12'=>'C');
	// 	$mall_db = $this->site_model->get_tbl('araneta');
	// 	$mall = array('lessee_name'=>$mall_db[0]->lessee_name,'lessee_no'=>$mall_db[0]->lessee_no,'space_code'=>$mall_db[0]->space_code);
	// 	$file_path = filepathisize($mall_db[0]->file_path);
	// 	$year = date('Y',strtotime($date));
 //        $file_path .= $year."/";
	// 	$trans_list_filename = $file_path.substr($mall['lessee_name'],0,4).substr($mall['space_code'],0,3)."L.".$mon[date('m',strtotime($date))].date('d',strtotime($date));
	// 	$summary_filename = $file_path.substr($mall['lessee_name'],0,4).substr($mall['space_code'],0,3)."S.".$mon[date('m',strtotime($date))].date('d',strtotime($date));
	// 	$monthly_filename = $file_path.substr($mall['lessee_name'],0,4).substr($mall['space_code'],0,3)."C.".$mon[date('m',strtotime($date))]."00";
	// 	if(file_exists($summary_filename)){
	// 		$fh = fopen($summary_filename, 'r');
	// 		$theData = fread($fh, filesize($summary_filename));
	// 		fclose($fh);
	// 		$sum = "<pre>".$theData."</pre>";
	// 	}
	// 	else{
	// 		$sum = "<center> file not found. </center>";
	// 	}

	// 	if(file_exists($trans_list_filename)){
	// 		$fh = fopen($trans_list_filename, 'r');
	// 		$theData = fread($fh, filesize($trans_list_filename));
	// 		fclose($fh);
	// 		$list = "<pre>".$theData."</pre>";
	// 	}
	// 	else{
	// 		$list = "<center> file not found. </center>";
	// 	}

	// 	if(file_exists($monthly_filename)){
	// 		$fh = fopen($monthly_filename, 'r');
	// 		$theData = fread($fh, filesize($monthly_filename));
	// 		fclose($fh);
	// 		$mod = "<pre>".$theData."</pre>";
	// 	}
	// 	else{
	// 		$mod = "<center> file not found. </center>";
	// 	}

	// 	echo json_encode(array('list'=>$list,'sum'=>$sum,'month'=>$mod));
	// }
	public function settings(){
		$data = $this->syter->spawn(null);
		$objs = $this->site_model->get_tbl('araneta');
		$obj = array();
		if(count($objs) > 0){
			$obj = $objs[0];			
		}
		$data['code'] = aranetaSettingsPage($obj);
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/araneta.php';
		$data['use_js'] = 'settingsJs';
		$this->load->view('load',$data);
	}
	public function settings_db(){
		$this->load->model('dine/main_model');
		$items = array(
			'lessee_name'=>$this->input->post('lessee_name'),
			'lessee_no'=>$this->input->post('lessee_no'),
			'space_code'=>$this->input->post('space_code'),
			'contract_no'=>$this->input->post('contract_no'),
			'def1'=>$this->input->post('def1'),
			'of2'=>$this->input->post('of2'),
			'sales_type'=>$this->input->post('sales_type'),
			'outlet_no'=>$this->input->post('outlet_no'),
		);
		if ($this->input->post('araneta_id')) {
			$id = $this->input->post('araneta_id');
			$this->site_model->update_tbl('araneta','id',$items,$id);
			$msg = "Updated Settings.";
			$this->main_model->update_tbl('araneta','id',$items,$id);
		} else {
			$id = $this->site_model->add_tbl('araneta',$items);
			$msg = "Updated Settings";
			$this->main_model->add_trans_tbl('araneta',$items);
		}
		echo json_encode(array('id'=>$id,'msg'=>$msg));
	}
	// public function regen_file(){
	// 	$date = $this->input->post('date');
	// 	$rargs["DATE(read_details.read_date) = DATE('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
	// 	$rargs["read_type"] = 2;
	// 	$select = "read_details.*";
	// 	$this->site_model->db = $this->load->database('default', TRUE);
	// 	$results = $this->site_model->get_tbl('read_details',$rargs,array('scope_from'=>'asc'),"",true,$select);
	// 	$zread = 0;
	// 	foreach ($results as $res) {
	// 		$zread = $res->id;
	// 		$read_date = $res->read_date;
	// 	}
	// 	if($zread != 0){
	// 		$reg = $this->araneta_file($zread,true);
	// 		echo json_encode(array('msg'=>$reg['msg'],'error'=>$reg['error']));
	// 	}
	// 	else{
	// 		echo json_encode(array('msg'=>'No zread found on this date.','error'=>1));
	// 	}
	// }

	public function get_file(){
		$date = $this->input->post('date');
		$mall_res = $this->site_model->get_tbl('araneta');
		$daily_str = "";
		if(count($mall_res) > 0){
			// $file_path = filepathisize($mall_res[0]->file_path);
			$filepath = 'C:/ARANETA_HAP/';

			// $file_txt = "Z".date('ymd',strtotime($date))."".TERMINAL_NUMBER.".EOD";
			$file_txt = 'Z'.date('mdY',strtotime($date)).'.FLG';
            // $text = $filepath."/".$file_txt;


			// $year = date('Y',strtotime($date));
			
			$file = $filepath.$file_txt;
			// $ctrs = array();
			// foreach (glob($find_file."*") as $filefound) {
			// 	if($filefound){
			// 		$ctr_str = str_replace($find_file,'',$filefound);
			// 		$ctr_str = str_replace('.EOD','',$ctr_str);
			// 		$ctrs[] = $ctr_str;
			// 	}
			// }
				
			if(file_exists($file)){
				$fh = fopen($file, 'r');
				$print = fread($fh, filesize($file));
				fclose($fh);
				$daily_str = "<pre style='margin:10px;'>".$print."</pre>";
			}
			else{
				$daily_str = "<center style='margin-top:10px;'>Mall File not found</center>";
			}
			
		}
		else{
			$daily_str = "<center style='margin-top:10px;'>Mall Settings not found</center>";
		}
		echo json_encode(array('daily'=>$daily_str));
	}
	public function regen_file(){
		$date = $this->input->post('date');
		$rargs["DATE(read_details.read_date) = DATE('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
		$rargs["read_type"] = 2;
		$select = "read_details.*";
		// $this->site_model->db = $this->load->database('default', TRUE);
		$results = $this->site_model->get_tbl('read_details',$rargs,array('scope_from'=>'asc'),"",true,$select);
		// echo $this->site_model->db->last_query();
		// die();
		$zread = 0;
		foreach ($results as $res) {
			$zread = (int)$res->id;
			$read_date = $res->read_date;
		}
		// echo $zread; die();
		if($zread != 0){
			// $reg = $this->araneta_end_file($read_date,$zread,true);
			$this->araneta_trans_file($read_date,$zread);
			$msg = 'Araneta File successfully created';
			echo json_encode(array('msg'=>$msg,'error'=>0));
		}
		else{
			echo json_encode(array('msg'=>'No zread found on this date.','error'=>1));
		}
	}
}