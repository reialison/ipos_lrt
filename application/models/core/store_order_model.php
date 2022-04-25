<?php
class Store_order_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->library('Db_manager');
		$this->db_manager->get_connection('default');
		// if(LOCALSYNC){
		// 	$this->load->model('core/sync_model');
		// }

	}

	public function add_item_det_bulk_main($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert_batch('sales_order_details', $items);
		$x=$this->db->insert_id();
		return $x;
	}

	public function add_so_main($items){
		$this->db->trans_start();
		$this->db->insert('sales_orders',$items);
		$inserted =  $this->db->insert_id();
		// echo $this->db->last_query();die();
		$this->db->trans_complete();

		return $inserted;
	}

	public function add_so_details($items){
		$this->db->trans_start();
		$this->db->insert('sales_order_details',$items);

		$inserted =  $this->db->insert_id();
		$this->db->trans_complete();
		return $inserted;
	}

 	public function get_date_now($branch_code=null){
		$this->db->select('NOW() as date_now');
		$query = $this->db->get();
		$result = $query->result();
		if(isset($result[0])){
			return $result[0]->date_now;
		}else{
			return null;
		}	
	}

 	public function item_details($id){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('items');
			$this->db->where('item_id',$id);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function check_store_order($ref){
		$this->db->flush_cache();
		$this->db->trans_start();
		$this->db->select('trans_receivings.*');
		$this->db->where('reference', $ref);
		$query = $this->db->get('trans_receivings');
		 // echo $last = $this->bir_db->last_query();
		$this->db->trans_complete();
		return $query->result();
	}

	public function get_dr_details($dr_ref=""){
		$result = array();
		$this->bir_db = $this->db_manager->get_connection('bir');

		// $dr_ref = 'COGS-000009';
		if(!empty($dr_ref)){
			$this->bir_db->trans_start();
				$this->bir_db->select('sales_orders.*, sales_order_details.*, sales_order_details.name as item_name');
				$this->bir_db->from('sales_orders');
				$this->bir_db->where('sales_orders.reference',$dr_ref);
				$this->bir_db->where('sales_orders.branch_name',BRANCH_CODE);

				$this->bir_db->join('sales_order_details', 'sales_orders.sales_order_id = sales_order_details.sales_order_id','LEFT');
				// $this->db->join('items', 'sales_order_details.item_code = items.item_id','LEFT');

				$query = $this->bir_db->get();
				// echo $this->bir_db->last_query();die();
				$result = $query->result();

				if(isset($result) && !empty($result)){
					$store_trans_ref = $result[0]->store_trans_ref ;
					$items = array('reference'=>$result[0]->reference,'type_id'=>$result[0]->type_id, 'debtor_no'=>$result[0]->debtor_no,'debtor_name'=>$result[0]->debtor_name,'approve_status'=>$result[0]->approve_status ,'del_date'=>$result[0]->del_date ,'approve_by'=>$result[0]->approve_by,'approve_date'=>$result[0]->approve_date  );
					$this->db->where('store_trans_ref', $store_trans_ref);
					$this->db->update('sales_orders', $items);
				}
				// echo "<pre>",print_r($result),"</pre>";die();

			$this->bir_db->trans_complete();
		return $result;
		}
	}

}
?>