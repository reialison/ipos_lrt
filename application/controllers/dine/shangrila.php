<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/reads.php");
class Shangrila extends Reads {
	public function __construct(){
		parent::__construct();
		$this->load->helper('dine/shangrila_helper');
		$this->load->model('dine/cashier_model');
		$this->load->model('core/trans_model');
	}
	public function index(){
        $data = $this->syter->spawn(null);
        $data['code'] = shangrilaPage();
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/shangrila.php';
        $data['use_js'] = 'mainPageJs';
        $this->load->view('load',$data);
	}
	public function daily_files(){
		$data = $this->syter->spawn(null);
		$data['code'] = dailyFilesPage();
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/shangrila.php';
		$data['use_js'] = 'dailyFilesJS';
		$this->load->view('load',$data);
	}
	public function settings(){
		$data = $this->syter->spawn(null);
		$objs = $this->site_model->get_tbl('shangrila');
		$obj = array();
		if(count($objs) > 0){
			$obj = $objs[0];			
		}
		$data['code'] = settingsPage($obj);
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/shangrila.php';
		$data['use_js'] = 'settingsJS';
		$this->load->view('load',$data);	
	}
	public function settings_db(){
		$this->load->model('dine/main_model');
		$items = array(
			'tenant_code' => $this->input->post('tenant_code'),
			'sales_dep' => $this->input->post('sales_dep'),
			'file_path'   => $this->input->post('file_path'),
		);
		$id = $this->input->post('shangrila_id');
		$this->site_model->update_tbl('shangrila','id',$items,$id);
		$this->main_model->update_tbl('shangrila','id',$items,$id);
		$msg = "Updated Settings.";
		echo json_encode(array('id'=>$id,'msg'=>$msg));
	}
	public function get_file(){
		$date = $this->input->post('date');
		$mall_res = $this->site_model->get_tbl('shangrila');
		$daily_str = "";
		if(count($mall_res) > 0){
			$tenant_code = $mall_res[0]->tenant_code;
			$sales_dep = $mall_res[0]->sales_dep;
			$file_path = filepathisize($mall_res[0]->file_path);	
			$year = date('Y',strtotime($date));
			// $file_path .= $year."/";
			$file = $file = date('mdY',strtotime($date));
			$find_file = $file_path.$file;
			// // echo $file_path.'br-----';
			// // print_r(glob($file_path."*.*")); die();

			// $ctrs = array();
			// foreach (glob($find_file."*") as $filefound) {
			// 	if($filefound){
			// 		// echo $filefound.'---';
			// 		$ctr_str = str_replace($find_file,'',$filefound);
			// 		$ctr_str = str_replace('.sal','',$ctr_str);
			// 		$ctrs[] = $ctr_str;
			// 	}
			// }
			// var_dump($ctrs); die();
			// if(count($ctrs) > 0){
				// usort($ctrs, function($a, $b) {
			 //    	return intval($b) - intval($a);
				// });
				// $ctr = $ctrs[0];
				$file = $find_file.".txt";
				if(file_exists($file)){
					$fh = fopen($file, 'r');
					$print = fread($fh, filesize($file));
					fclose($fh);
					$daily_str = "<pre style='margin:10px;'>".$print."</pre>";
				}
				else{
					$daily_str = "<center style='margin-top:10px;'>Mall File not found</center>";
				}
			// }	
			// else{
			// 	$daily_str = "<center style='margin-top:10px;'>Mall File not found</center>";
			// }
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
		$this->site_model->db = $this->load->database('default', TRUE);
		$results = $this->site_model->get_tbl('read_details',$rargs,array('scope_from'=>'asc'),"",true,$select);
		$zread = 0;
		foreach ($results as $res) {
			$zread = $res->id;
			$read_date = $res->read_date;
		}

		// echo $zread; die();
		if($zread != 0){
			$reg = $this->shangrila_file($zread,true,$date);
			echo json_encode(array('msg'=>$reg['msg'],'error'=>$reg['error']));
		}
		else{
			echo json_encode(array('msg'=>'No zread found on this date.','error'=>1));
		}
	}
}