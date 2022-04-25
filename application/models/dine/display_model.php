<?php
class Display_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_for_kitchen_display($status=0){
		$this->db->trans_start();
			$this->db->select('sales_id,queue_id');
			$this->db->from('trans_sales');
			$this->db->where('kitchen_display_status',$status);
			$this->db->where('dispatch_display_status IS NULL','',FALSE);
			$this->db->where('completed_display_status IS NULL','',FALSE);
			$this->db->where("datetime BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()");
			$this->db->order_by("sales_id DESC");
			$query = $this->db->get();
			$result = $query->result();

			// echo $this->db->last_query();die();
		$this->db->trans_complete();
		return $result;
	}

	public function update_trans_status($ref=NULL,$items=array()){
		// $this->db->where('code', $id);
		$this->db->where('sales_id', $ref);
		$return = $this->db->update('trans_sales', $items);

		return $return;
	}

	public function update_trans_menus_check($ref=NULL,$menu_id=NULL,$line_id=null,$items=array()){
		// $this->db->where('code', $id);
		$this->db->where('sales_id', $ref);
		$this->db->where('menu_id', $menu_id);

		if($line_id != ''){
			$this->db->where('line_id', $line_id);
		}
		
		$return = $this->db->update('trans_sales_menus', $items);

		return $return;
	}

	public function get_for_dispatch_display(){
		$this->db->trans_start();
			$this->db->select('sales_id');
			$this->db->from('trans_sales');
			$this->db->where('kitchen_display_status','1');
			$this->db->where('dispatch_display_status IS NULL','',FALSE);
			$this->db->where('completed_display_status IS NULL','',FALSE);
			$this->db->where("datetime BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()");
			$this->db->order_by("sales_id DESC");
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_ready_to_dispatch_display(){
		$this->db->trans_start();
			$this->db->select('trans_sales.sales_id,queue_id');
			$this->db->from('trans_sales');
			$this->db->join('trans_sales_menus','trans_sales_menus.sales_id=trans_sales.sales_id');
			$this->db->where('kitchen_display_status','1');
			$this->db->where('is_checked','1');
			// $this->db->where('dispatch_display_status','1');
			$this->db->where('completed_display_status IS NULL','',FALSE);
			$this->db->where("trans_sales.datetime BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()");
			$this->db->group_by('trans_sales.sales_id');
			$this->db->order_by("trans_sales.sales_id DESC");
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();

		// echo $this->db->last_query();die();
		return $result;
	}

	public function get_completed_display(){
		$this->db->trans_start();
			$this->db->select('sales_id');
			$this->db->from('trans_sales');
			// $this->db->where('kitchen_display_status','1');
			$this->db->where('completed_display_status','1');
			$this->db->where("datetime BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()");
			$this->db->order_by("sales_id DESC");
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}


	public function get_completed_kitchen_display(){
		$this->db->trans_start();
			$this->db->select('sales_id');
			$this->db->from('trans_sales');
			$this->db->where('kitchen_display_status','1');
			$this->db->where('dispatch_display_status','1');
			$this->db->where('completed_display_status','1');
			$this->db->where("datetime BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()");
			$this->db->order_by("sales_id DESC");
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();

		// echo $this->db->last_query();die();
		return $result;
	}

	function get_trans_sales_menus($sales_id,$is_checked=null){
		$this->db->where('sales_id',$sales_id);

		if($is_checked != ''){
			$this->db->where('is_checked',$is_checked);
		}

		return $this->db->get('trans_sales_menus')->result();
	}

}
?>