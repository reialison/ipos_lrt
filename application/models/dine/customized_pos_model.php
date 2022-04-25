<?php
/**
 * Customized POS Model
 */
class Customized_pos_model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Db_manager');
		$this->custom_db = $this->db_manager->get_connection(CUSTOMIZED_POS);
	}

	public function save_debtor_trans($debtor_trans, $debtor_trans_details)
	{
		$this->custom_db->insert("debtor_trans", $debtor_trans);
		$this->custom_db->insert("debtor_trans_details", $debtor_trans_details);

		// 0 = "Success"
		// 1 = "Error"
		return 0;
	}
}
