<?php
class Admin_model extends CI_Model{

	public function __construct(){
		parent::__construct();
	}
	public function get_user_roles($id=null,$exclude_admin=true){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('user_roles');
			if($id != null){
				$this->db->where('user_roles.id',$id);
			}
			if($exclude_admin)
				$this->db->where('user_roles.id !=', 1);
			$this->db->order_by('id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_user_roles($items){
		$this->db->insert('user_roles',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_user_roles($user,$id){
		$this->db->where('id', $id);
		$this->db->update('user_roles', $user);

		return $this->db->last_query();
	}

	public function restart(){
		$this->db->trans_start();
			$this->db->query('truncate table `cashout_details`');
			$this->db->query('truncate table `cashout_entries`');
			$this->db->query('truncate table `ci_sessions`');
			$this->db->query('truncate table `item_moves`');
			$this->db->query('truncate table `logs`');
			$this->db->query('truncate table `read_details`');
			$this->db->query('truncate table `reasons`');
			$this->db->query('truncate table `rob_files`');
			$this->db->query('truncate table `shift_entries`');
			$this->db->query('truncate table `shifts`');
			$this->db->query('truncate table `trans_adjustment_details`');
			$this->db->query('truncate table `trans_adjustments`');
			$this->db->query('truncate table `trans_receiving_details`');
			$this->db->query('truncate table `trans_receivings`');
			$this->db->query('truncate table `trans_spoilage_details`');
			$this->db->query('truncate table `trans_spoilage`');
			$this->db->query('truncate table `trans_voids`');
			$this->db->query('truncate table `trans_refs`');
			$this->db->query('truncate table `trans_sales`');
			$this->db->query('truncate table `trans_sales_charges`');
			$this->db->query('truncate table `trans_sales_discounts`');
			$this->db->query('truncate table `trans_sales_items`');
			$this->db->query('truncate table `trans_sales_menu_modifiers`');
			$this->db->query('truncate table `trans_sales_menu_submodifiers`');
			$this->db->query('truncate table `trans_sales_menus`');
			$this->db->query('truncate table `trans_sales_no_tax`');
			$this->db->query('truncate table `trans_sales_payments`');
			$this->db->query('truncate table `trans_sales_payment_fields`');
			$this->db->query('truncate table `trans_sales_tax`');
			$this->db->query('truncate table `trans_sales_zero_rated`');
			$this->db->query('truncate table `trans_sales_local_tax`');
			$this->db->query('truncate table `trans_sales_loyalty_points`');
			$this->db->query('truncate table `ortigas_read_details`');
			$this->db->query('truncate table `customers_bank`');
			$this->db->query('truncate table `sync_logs`');
			$this->db->query('truncate table `event_logs`');
			$this->db->query('truncate table `transfer_split`');
			// $this->db->query('truncate table `master_logs`');
			$this->db->query('truncate table `trans_receiving_menu`');
			$this->db->query('truncate table `trans_receiving_menu_details`');
			$this->db->query('truncate table `trans_adjustment_menu`');
			$this->db->query('truncate table `trans_adjustment_menu_details`');
			$this->db->query('truncate table `menu_moves`');
			$this->db->query('truncate table `shift_denominations`');
			$this->db->query('truncate table `combine_tables`');
			$this->db->query("UPDATE users set sync_id = NULL");
			// $this->db->query("UPDATE menus set sync_id = NULL");
			// $this->db->query("UPDATE items set sync_id = NULL");

			$this->db->query('truncate table `trans_gc`');
			$this->db->query('truncate table `trans_gc_charges`');
			$this->db->query('truncate table `trans_gc_discounts`');
			$this->db->query('truncate table `trans_gc_gift_cards`');
			$this->db->query('truncate table `trans_gc_local_tax`');
			$this->db->query('truncate table `trans_gc_loyalty_points`');
			$this->db->query('truncate table `trans_gc_no_tax`');
			$this->db->query('truncate table `trans_gc_payment_fields`');
			$this->db->query('truncate table `trans_gc_payments`');
			$this->db->query('truncate table `trans_gc_tax`');
			$this->db->query('truncate table `trans_gc_zero_rated`');

			$this->db->query('truncate table `gift_cards`');

			$this->db->query('truncate table `store_zread`');

			$this->db->query('truncate table `event_logs`');
		

			$this->db->query("UPDATE trans_types set next_ref = '00000001' WHERE type_id = 10");
			$this->db->query("UPDATE trans_types set next_ref = 'R000001' WHERE type_id = 20");
			$this->db->query("UPDATE trans_types set next_ref = 'A000001' WHERE type_id = 30");
			$this->db->query("UPDATE trans_types set next_ref = 'V000001' WHERE type_id = 11");
			$this->db->query("UPDATE trans_types set next_ref = 'C000001' WHERE type_id = 40");
			$this->db->query("UPDATE trans_types set next_ref = 'S000001' WHERE type_id = 35");
			$this->db->query("UPDATE trans_types set next_ref = 'RM000001' WHERE type_id = 55");
			$this->db->query("UPDATE trans_types set next_ref = 'AJ00001' WHERE type_id = 70");

			$this->db->query("UPDATE trans_types set next_ref = 'GC00000001' WHERE type_id = 12");
		$this->db->trans_complete();
	}

	public function master_restart(){

		$this->load->library('Db_manager');
		$this->master_db = $this->db_manager->get_connection('master');

		$this->master_db->trans_start();
			$this->master_db->query('truncate table `cashout_details`');
			$this->master_db->query('truncate table `cashout_entries`');
			$this->master_db->query('truncate table `ci_sessions`');
			$this->master_db->query('truncate table `item_moves`');
			$this->master_db->query('truncate table `logs`');
			$this->master_db->query('truncate table `read_details`');
			$this->master_db->query('truncate table `reasons`');
			$this->master_db->query('truncate table `rob_files`');
			$this->master_db->query('truncate table `shift_entries`');
			$this->master_db->query('truncate table `shifts`');
			$this->master_db->query('truncate table `trans_adjustment_details`');
			$this->master_db->query('truncate table `trans_adjustments`');
			$this->master_db->query('truncate table `trans_receiving_details`');
			$this->master_db->query('truncate table `trans_receivings`');
			$this->master_db->query('truncate table `trans_spoilage_details`');
			$this->master_db->query('truncate table `trans_spoilage`');
			$this->master_db->query('truncate table `trans_voids`');
			$this->master_db->query('truncate table `trans_refs`');
			$this->master_db->query('truncate table `trans_sales`');
			$this->master_db->query('truncate table `trans_sales_charges`');
			$this->master_db->query('truncate table `trans_sales_discounts`');
			$this->master_db->query('truncate table `trans_sales_items`');
			$this->master_db->query('truncate table `trans_sales_menu_modifiers`');
			$this->master_db->query('truncate table `trans_sales_menus`');
			$this->master_db->query('truncate table `trans_sales_no_tax`');
			$this->master_db->query('truncate table `trans_sales_payments`');
			$this->master_db->query('truncate table `trans_sales_tax`');
			$this->master_db->query('truncate table `trans_sales_zero_rated`');
			$this->master_db->query('truncate table `trans_sales_local_tax`');
			$this->master_db->query('truncate table `trans_sales_loyalty_points`');
			$this->master_db->query('truncate table `ortigas_read_details`');
			$this->master_db->query('truncate table `customers_bank`');
			// $this->master_db->query('truncate table `sync_logs`');
			$this->master_db->query('truncate table `master_logs`');

			$this->master_db->query('truncate table `trans_gc`');
			$this->master_db->query('truncate table `trans_gc_charges`');
			$this->master_db->query('truncate table `trans_gc_discounts`');
			$this->master_db->query('truncate table `trans_gc_gift_cards`');
			$this->master_db->query('truncate table `trans_gc_local_tax`');
			$this->master_db->query('truncate table `trans_gc_loyalty_points`');
			$this->master_db->query('truncate table `trans_gc_no_tax`');
			$this->master_db->query('truncate table `trans_gc_payment_fields`');
			$this->master_db->query('truncate table `trans_gc_payments`');
			$this->master_db->query('truncate table `trans_gc_tax`');
			$this->master_db->query('truncate table `trans_gc_zero_rated`');

			$this->master_db->query('truncate table `gift_cards`');

			$this->master_db->query('truncate table `store_zread`');

			$this->master_db->query("UPDATE trans_types set next_ref = '00000001' WHERE type_id = 10");
			$this->master_db->query("UPDATE trans_types set next_ref = 'R000001' WHERE type_id = 20");
			$this->master_db->query("UPDATE trans_types set next_ref = 'A000001' WHERE type_id = 30");
			$this->master_db->query("UPDATE trans_types set next_ref = 'V000001' WHERE type_id = 11");
			$this->master_db->query("UPDATE trans_types set next_ref = 'C000001' WHERE type_id = 40");
			$this->master_db->query("UPDATE trans_types set next_ref = 'S000001' WHERE type_id = 35");
		$this->db->trans_complete();
	}
}
?>