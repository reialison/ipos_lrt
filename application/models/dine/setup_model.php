<?php
class Setup_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_branch_details(){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('branch_details');
			$this->db->where('branch_details.branch_code',BRANCH_CODE);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	//-----------Categories-----start-----allyn
	public function get_details($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('branch_details');
			$this->db->where('branch_details.branch_id',$id);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function update_details($items,$id){
		// $this->db->where('code', $id);
		$this->db->where('branch_id', $id);
		$this->db->update('branch_details', $items);
	}
	//-----------Categories-----end-----allyn

	public function get_terminals(){
		$this->mb = $this->load->database('default',true);
		$this->mb->trans_start();
			$this->mb->select('*');
			$this->mb->from('terminals');
			// $this->mb->where('branch_details.branch_code',BRANCH_CODE);
			$query = $this->mb->get();
			$result = $query->result();
		$this->mb->trans_complete();
		return $result;
	}

	public function get_printers($id = null, $inactive= null)
	{
		$this->db->from('printers');
		if (!is_null($id)) {
			if (is_array($id))
				$this->db->where_in('id',$id);
			else
				$this->db->where('id',$id);
		}
		if($inactive != null){
			$this->db->where('inactive','0');
		}
		$query = $this->db->get();
		return $query->result();
	}
	public function add_printer_setup($items)
	{
		$this->db->trans_start();
		$this->db->insert('printers',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}
	public function update_printer_setup($items,$id)
	{
		$this->db->trans_start();
		$this->db->where('id',$id);
		$this->db->update('printers',$items);
		$this->db->trans_complete();
	}
}
?>