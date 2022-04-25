<?php
class Expenses_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->library('Db_manager');
		$this->db_manager->get_connection('default');
		// if(LOCALSYNC){
		// 	$this->load->model('core/sync_model');
		// }

	}

	public function add_trans_sales_batch($items){
		$this->db->insert_batch('trans_sales',$items);
		return $this->db->insert_id();
	}
	public function add_trans_tbl_batch($table_name,$items){
		$this->db->insert_batch($table_name,$items);
		return $this->db->insert_id();
	}
	public function add_trans_tbl($table_name,$items){
		$this->db->insert($table_name,$items);
		return $this->db->insert_id();
	}
	public function update_tbl($table_name,$table_key,$items,$id,$truelse=true){
		$this->db->where($table_key,$id);
		if(!$truelse)
			$this->db->update($table_name,$items,$truelse);
		else
			$this->db->update($table_name,$items);
		return $this->db->last_query();
	}
	public function update_trans_tbl($table_name,$table_key,$items,$id=null,$set=array()){
		if(is_array($table_key)){
			foreach ($table_key as $key => $val) {
				if(is_array($val)){
					$this->db->where_in($key,$val);
				}
				else
					$this->db->where($key,$val);
			}
		}
		else{
			if(is_array($id)){
				$this->db->where_in($table_key,$id);
			}
			else
				$this->db->where($table_key,$id);
		}
		if(!empty($set)){
			foreach ($set as $key => $val) {
				$this->db->set($key, $val, FALSE);
			}
		}
		$this->db->update($table_name,$items);
		return $this->db->last_query();
	}
 	public function item_details($id){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('expenses_items');
			$this->db->where('id',$id);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_expenses_db($items){
		$this->db->trans_start();
		$this->db->insert('expenses_entry',$items);
		$inserted =  $this->db->insert_id();
		// echo $this->db->last_query();die();
		$this->db->trans_complete();

		return $inserted;
	}
	public function add_expenses_det($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert_batch('expenses_details', $items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function change_status_ex($items,$id){
		// $this->db->where('code', $id);
		$this->db->where('id', $id);
		$this->db->update('expenses_items', $items);
	}
}
?>