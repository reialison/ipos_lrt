<?php
class Store_order_acc extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->library('Db_manager');
		$this->bir_db = $this->db_manager->get_connection('bir');
		// if(LOCALSYNC){
		// 	$this->load->model('core/sync_model');
		// }

	}
	public function add_item_det_bulk_acc($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->load->library('Db_manager');
		$this->bir_db = $this->db_manager->get_connection('bir');
		$this->bir_db->insert_batch('0_sales_order_details', $items);
		$x=$this->bir_db->insert_id();
		return $x;
	}

	public function add_so_accounting($items){
		// $this->load->library('Db_manager');
		// $this->bir_db = $this->db_manager->get_connection('bir');
		$this->bir_db->trans_start();
		$this->bir_db->insert('0_sales_orders',$items);

		$inserted =  $this->bir_db->insert_id();
		$this->bir_db->trans_complete();

		return $inserted;
	}
 	public function get_date_now($branch_code=null){
		$this->bir_db->select('NOW() as date_now');
		$query = $this->bir_db->get();
		$result = $query->result();
		if(isset($result[0])){
			return $result[0]->date_now;
		}else{
			return null;
		}	
	}
	public function change_status_so($items,$id){
		// $this->db->where('code', $id);
		$this->bir_db->where('sales_order_id', $id);
		$this->bir_db->update('0_sales_orders', $items);
	}
	public function add_to_debtor_trans($items){
		$this->bir_db->trans_start();
		$this->bir_db->set('trans_date', 'NOW()', FALSE);
		$this->bir_db->insert('0_debtor_trans',$items);
		$last_id = $this->bir_db->insert_id();
		// echo $this->bir_db->last_query();
		// die();
		$this->bir_db->trans_complete();
		return $last_id;
	}
	public function get_cust_branch($br_name){
		$this->bir_db->trans_start();
		$this->bir_db->select('0_cust_branch.*,name');
		$this->bir_db->where('br_name', $br_name);
		$this->bir_db->join('0_debtor_masters', '0_debtor_masters.debtor_no = 0_cust_branch.debtor_no');
		$query = $this->bir_db->get('0_cust_branch');
		 // echo $last = $this->bir_db->last_query();
		$this->bir_db->trans_complete();
		return $query->result();
	}

	public function get_cust_tax($branch_code){
		$this->bir_db->start_cache();
			$this->bir_db->select('tax_type_id,rate tax_rate');	
			$this->bir_db->from('tax_group_items');	
			$this->bir_db->join('tax_groups','tax_groups.id = tax_group_items.tax_group_id');
			$this->bir_db->join('cust_branch','cust_branch.tax_group_id = tax_groups.id');
			$this->bir_db->where('cust_branch.branch_code',$branch_code);
		$this->bir_db->stop_cache();
		$q=$this->bir_db->get();		
		$this->bir_db->flush_cache();
		return $q->result();
	}
	public function get_item_column($cols=array(),$item_codes=array(),$from_view=false){
		if(!empty($cols))
			$this->bir_db->select('stock_id,'.implode(",",$cols));

		if($from_view===false)
			$this->bir_db->from('0_stock_masters');
		else
			$this->bir_db->from('0_view_stock_masters');
		
		if(!empty($item_codes)){		
			// $this->bir_db->where_in('stock_id',$item_codes);
			if(is_array($item_codes)){
				$this->bir_db->where_in('stock_id',explode(',', implode("','", $item_codes)));
			}else{
				$this->bir_db->where_in('stock_id',$item_codes);
			}
			
			
		}
		return $this->bir_db->get();
	}
	public function add_to_debtor_trans_details($items){
		$this->bir_db->trans_start();
		$this->bir_db->insert_batch('0_debtor_trans_details',$items);
		$this->bir_db->trans_complete();
	}
	public function get_customer_details($id = ""){
		$this->bir_db->start_cache();
			$this->bir_db->select('debtor_masters.*');	
			$this->bir_db->from('debtor_masters');
			if($id != "")	
				$this->bir_db->where('debtor_masters.debtor_no',$id);	
		$this->bir_db->stop_cache();
		$q=$this->bir_db->get();		
		$this->bir_db->flush_cache();
		return $q->result();
	}
	public function get_branch($branch_id=""){
		# Original
		$this->bir_db->select('*');
		$this->bir_db->from('cust_branch');
		if($branch_id != "")
			$this->bir_db->where('branch_code', $branch_id);
		$query = $this->bir_db->get();

		return $query->result();
	}
	public function get_so_lists($id){
		$this->bir_db->flush_cache();
		$this->bir_db->where('sales_order_id',$id);

		$query = $this->bir_db->get('0_sales_order_details');
	 // echo $this->db->last_query();die();
		return $query->result();
	}
	public function get_tax_group_rate($group_id){
		$this->bir_db->select('tax_type_id,tax_group_items.rate tax_rate, name');
   		$this->bir_db->from('tax_group_items');
   		$this->bir_db->join('tax_types','tax_types.id = tax_group_items.tax_type_id');
    	$this->bir_db->where('tax_group_id',$group_id);

    	$query = $this->bir_db->get();

		return $query->result();
	}
	public function add_trans_tax($items){
		$this->bir_db->trans_start();
		$this->bir_db->insert('trans_tax_details',$items);

		$inserted =  $this->bir_db->insert_id();
		$this->bir_db->trans_complete();
		return $inserted;
	}
	public function update_debtor_trans($sales_order_id,$items){
		$this->bir_db->where('order_', $sales_order_id);
		$this->bir_db->update('debtor_trans',$items);		// echo $this->db->last_query();
		// die();
	}


}
?>