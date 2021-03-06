<?php
class Manager_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_trans_sales($sales_id=null,$args=array()){
		$this->db->select('trans_sales.*,users.username,terminals.terminal_name');
		$this->db->from('trans_sales');
		$this->db->join('users','trans_sales.user_id = users.id');
		$this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
		if (!is_null($sales_id)){
			if (is_array($sales_id))
				$this->db->where_in('trans_sales.sales_id',$sales_id);
			else
				$this->db->where('trans_sales.sales_id',$sales_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->order_by('trans_sales.datetime desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_sales($items){
		// $this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('trans_sales',$items);
		return $this->db->insert_id();
	}
	public function update_trans_sales($items,$sales_id){
		$this->db->set('trans_sales.update_date','NOW()',FALSE);
		$this->db->where('trans_sales.sales_id',$sales_id);
		$this->db->update('trans_sales',$items);
	}
	public function get_trans_sales_menus($sales_menu_id=null,$args=array()){
		$this->db->select('trans_sales_menus.*,menus.menu_code,menus.menu_name');
		$this->db->from('trans_sales_menus');
		$this->db->join('menus','trans_sales_menus.menu_id=menus.menu_id');
		if (!is_null($sales_menu_id)){
			if (is_array($sales_menu_id))
				$this->db->where_in('trans_sales_menus.sales_menu_id',$sales_menu_id);
			else
				$this->db->where('trans_sales_menus.sales_menu_id',$sales_menu_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->order_by('trans_sales_menus.line_id asc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_sales_menus($items){
		$this->db->insert_batch('trans_sales_menus',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_menus($sales_id){
		$this->db->where('trans_sales_menus.sales_id', $sales_id);
		$this->db->delete('trans_sales_menus');
	}
	public function get_trans_sales_menu_modifiers($sales_mod_id=null,$args=array()){
		$this->db->select('trans_sales_menu_modifiers.*,modifiers.name as mod_name');
		$this->db->from('trans_sales_menu_modifiers');
		$this->db->join('modifiers','trans_sales_menu_modifiers.mod_id=modifiers.mod_id');
		if (!is_null($sales_mod_id)){
			if (is_array($sales_mod_id))
				$this->db->where_in('trans_sales_menu_modifiers.sales_mod_id',$sales_mod_id);
			else
				$this->db->where('trans_sales_menu_modifiers.sales_mod_id',$sales_mod_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->order_by('trans_sales_menu_modifiers.menu_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_sales_menu_modifiers($items){
		$this->db->insert_batch('trans_sales_menu_modifiers',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_menu_modifiers($sales_id){
		$this->db->where('trans_sales_menu_modifiers.sales_id', $sales_id);
		$this->db->delete('trans_sales_menu_modifiers');
	}

	/////////////////////////////////JED///////////////////////////

	public function get_payment_type($date,$ptype){
		$this->db->select('*');
		$this->db->from('trans_sales_payments');
		$this->db->join('trans_sales','trans_sales.sales_id = trans_sales_payments.sales_id');
		$this->db->where('payment_type', $ptype);
		$this->db->where("DATE_FORMAT(trans_sales_payments.datetime,'%Y-%m-%d')",$date);
		$this->db->where("trans_sales.inactive",0);

		$query = $this->db->get();
		return $query->result();

	}

	public function get_payment_count($date,$ptype){
		$this->db->select('*');
		$this->db->from('trans_sales_payments');
		$this->db->join('trans_sales','trans_sales.sales_id = trans_sales_payments.sales_id');
		$this->db->where('payment_type', $ptype);
		$this->db->where("DATE_FORMAT(trans_sales_payments.datetime,'%Y-%m-%d')",$date);
		$this->db->where("trans_sales.inactive",0);
		$this->db->group_by('trans_sales_payments.sales_id');

		$query = $this->db->get();
		return $query->result();

	}

	public function get_summary_type($date,$stype){
		$this->db->select('*');
		$this->db->from('trans_sales');
		$this->db->where('type', $stype);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);
		$this->db->where('type_id', 10);
		$this->db->where('inactive', 0);
		$this->db->where('trans_ref IS NOT NULL', null, false);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_summary_count($date,$stype){
		$this->db->select('*');
		$this->db->from('trans_sales');
		$this->db->where('type', $stype);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);
		$this->db->where('type_id', 10);
		$this->db->where('inactive', 0);
		// $this->db->where('trans_ref is not', null);
		$this->db->where('trans_ref IS NOT NULL', null, false);
		// $this->db->group_by('sales_id');

		$query = $this->db->get();
		return $query->result();

	}
	public function get_terminal_total($date,$terminal){
		$this->db->select('*');
		$this->db->from('trans_sales');
		$this->db->where('terminal_id', $terminal);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);
		$this->db->where('type_id', 10);
		$this->db->where('inactive', 0);
		$this->db->where('trans_ref IS NOT NULL', null, false);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_terminals(){
		$this->db->select('*');
		$this->db->from('terminals');
		$this->db->where('inactive', 0);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_open_order($date){
		$this->db->select('*');
		$this->db->from('trans_sales');
		$this->db->where('inactive', 0);
		$this->db->where('trans_ref', null);
		$this->db->where('type_id', 10);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_open_order_total($date){
		$this->db->select_sum('total_amount');
		$this->db->from('trans_sales');
		$this->db->where('inactive', 0);
		$this->db->where('trans_ref', null);
		$this->db->where('type_id', 10);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_settled_order($date){
		$this->db->select('*');
		$this->db->from('trans_sales');
		$this->db->where('inactive', 0);
		$this->db->where('type_id', 10);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);
		// $this->db->where('trans_ref', null);
		$this->db->where('trans_ref IS NOT NULL', null, false);
		$this->db->where('total_amount = total_paid', null, false);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_transactions($date){
		$this->db->select('*');
		$this->db->from('trans_sales');
		$this->db->where('inactive', 0);
		$this->db->where('type_id', 10);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_subtotal($date){
		$this->db->select_sum('total_amount');
		$this->db->from('trans_sales');
		$this->db->where('inactive', 0);
		$this->db->where('type_id', 10);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_taxtotal($date){
		$this->db->select_sum('amount');
		$this->db->from('trans_sales_tax');
		$this->db->join('trans_sales','trans_sales.sales_id = trans_sales_tax.sales_id');
		$this->db->where('trans_sales.inactive', 0);
		$this->db->where('type_id', 10);
		$this->db->where("DATE_FORMAT(trans_sales.datetime,'%Y-%m-%d')",$date);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_disctotal($date){
		$this->db->select_sum('amount');
		$this->db->from('trans_sales_tax');
		$this->db->join('trans_sales','trans_sales.sales_id = trans_sales_tax.sales_id');
		$this->db->where('trans_sales.inactive', 0);
		$this->db->where('type_id', 10);
		$this->db->where("DATE_FORMAT(trans_sales.datetime,'%Y-%m-%d')",$date);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_voids($date){
		$this->db->select('*');
		$this->db->from('trans_sales');
		$this->db->where_in('type_id', array('10','11'));
		$this->db->where('inactive', 1);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_void_total($date){
		$this->db->select_sum('total_amount');
		$this->db->from('trans_sales');
		$this->db->where_in('type_id', array('10','11'));
		$this->db->where('inactive', 1);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);

		$query = $this->db->get();
		return $query->result();

	}

	public function get_void_open($date){
		$this->db->select('*');
		$this->db->from('trans_sales');
		$this->db->where('type_id', 10);
		$this->db->where('inactive', 1);
		$this->db->where('trans_ref', null);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_void_settled($date){
		$this->db->select('*');
		$this->db->from('trans_sales');
		$this->db->where('type_id', 11);
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);

		$query = $this->db->get();
		return $query->result();

	}
	public function get_all_sales_today($date,$terminal=null){
		$this->db->select('*');
		$this->db->from('trans_sales');
		$this->db->where("DATE_FORMAT(datetime,'%Y-%m-%d')",$date);
		if($terminal != null){
			$this->db->where('terminal_id', $terminal);
		}
		$this->db->where('trans_ref IS NOT NULL', null, false);
		$this->db->where('type_id', 10);

		$query = $this->db->get();
		return $query->result();

	}
	/////////////////////////////////////////////////////////////
	///////////////////End Jed/////////////////////////////////////
	public function get_manager_by_pin($pin)
	{
		$this->db->select('*');
		$this->db->from('users');

		$this->db->where('pin',md5($pin));
		$this->db->where_in('role',unserialize(MANAGER_CALL_IDS));
		// $this->db->where('pin = '.$pin.' AND (role = 1 OR role = 2)');

		$query = $this->db->get();
		return $query->row();
	}







	/* MANAGER REPORT FUNCTIONS */
	public function get_daily_sales($date) {
		$this->db->select('date(datetime) "date", sum(total_amount) "total"');
		$this->db->from('trans_sales');
		$this->db->where('date(datetime)',date('Y-m-d',$date));
		$this->db->where('type_id',SALES_TRANS);
		$this->db->where('inactive',0);
		$this->db->group_by('date(datetime)');

		$query = $this->db->get();
		return $query->result();
	}
	public function get_terminal_daily_sales($date,$terminal_id)
	{
		$this->db->select(
			'date(datetime) "date",
			terminals.terminal_code,
			terminals.terminal_name,
			sum(total_amount) "total",
			');
		$this->db->from('trans_sales');
		$this->db->join('terminals','trans_sales.terminal_id=terminals.terminal_id');
		$this->db->where('date(trans_sales.datetime)',date('Y-m-d',strtotime($date)));
		$this->db->where('trans_sales.type_id',SALES_TRANS);
		$this->db->where('trans_sales.inactive',0);
		$this->db->group_by('date(datetime),trans_sales.terminal_id');

		$query = $this->db->get();
		return $query->result();
	}
	public function get_cashier_daily_sales($date,$cashier_id=null)
	{
		$this->db->select(
			'DATE(datetime) "date",
			users.username,
			users.fname,
			users.mname,
			users.lname,
			SUM(total_amount) "total"');
		$this->db->from('trans_sales');
		$this->db->join('users','trans_sales.user_id=users.id');
		$this->db->where('DATE(datetime)',date('Y-m-d',strtotime($date)));
		$this->db->where('trans_sales.type_id',SALES_TRANS);
		$this->db->where('trans_sales.inactive',0);
		$this->db->group_by('DATE(datetime),trans_sales.user_id');

		$query = $this->db->get();
		return $query->result();
	}
	public function get_customer_daily_count($date)
	{
		$this->db->select('
				COUNT(*)- COUNT(customer_id) "registered",
				COUNT(customer_id) "unregistered"');
		$this->db->from('trans_sales');
		$this->db->where('type_id',SALES_TRANS);
		$this->db->where('inactive',0);

		$query = $this->db->get();
		return $query->row();
	}
	public function get_top_menu_daily($date)
	{
		$this->db->select('menus.menu_code, menus.menu_name, count(*) "item_count"',false);
		$this->db->from('trans_sales');
		$this->db->join('trans_sales_menus','trans_sales.sales_id = trans_sales_menus.sales_id');
		$this->db->join('menus','trans_sales_menus.menu_id = menus.menu_id');
		$this->db->where('date(trans_sales.datetime)',date('Y-m-d',strtotime($date)));
		$this->db->group_by('trans_sales_menus.menu_id');
		$this->db->order_by('count(*) DESC');
		$this->db->limit(10);

		$query = $this->db->get();
		return $query->result();
	}
	public function get_fs_daily_sums($date_from,$date_to,$server_id = null,$include_void = false)
	{
		$this->db->select('
			trans_sales.waiter_id,
			waiters.fname,
			waiters.mname,
			waiters.lname,
			count(trans_sales.sales_id) "total_orders",
			sum(trans_sales.total_amount) "total_amount"
		',false);
		$this->db->from('trans_sales');
		$this->db->join('users as waiters','trans_sales.waiter_id = waiters.id');
		$this->db->where('DATE(trans_sales.datetime) >=',$date_from);
		$this->db->where('DATE(trans_sales.datetime) <=',$date_to);
		if (!empty($server_id))
			$this->db->where('waiter_id',$server_id);
		if (!$include_void){
			// $this->db->where('trans_sales.void_res IS NOT NULL');
			$this->db->where('trans_sales.inactive',0);
		}
		$this->db->group_by('trans_sales.waiter_id');
		$this->db->order_by('count(trans_sales.sales_id) DESC');

		$query = $this->db->get();

		return $query->result();
	}
	public function get_server_details($server_id = null)
	{
		$this->db->select('*');
		$this->db->from('users');
		// $this->db->join('users as waiters','trans_sales.waiter_id = waiters.id');
		// $this->db->where('DATE(trans_sales.datetime) >=',$date_from);
		// $this->db->where('DATE(trans_sales.datetime) <=',$date_to);
		if (!empty($server_id))
			$this->db->where('id',$server_id);


		$query = $this->db->get();

		return $query->result();
	}
	public function get_sales_sum_type($date_from,$date_to,$server_id = null,$type)
	{
		$this->db->select('
			count(trans_sales.sales_id) "count_type",
			sum(trans_sales.total_amount) "total_amount"
		',false);
		$this->db->from('trans_sales');
		$this->db->join('users as waiters','trans_sales.user_id = waiters.id');
		$this->db->join('trans_sales_payments as payment','trans_sales.sales_id = payment.sales_id');
		$this->db->where('DATE(trans_sales.datetime) >=',$date_from);
		$this->db->where('DATE(trans_sales.datetime) <=',$date_to);
		$this->db->where('payment.payment_type',$type);
		$this->db->where('trans_sales.type_id',10);
		$this->db->where('trans_sales.inactive',0);
		$this->db->where('trans_sales.trans_ref IS NOT NULL', null, false);
		if (!empty($server_id))
			$this->db->where('trans_sales.user_id',$server_id);


		$this->db->group_by('trans_sales.user_id');
		//$this->db->order_by('count(trans_sales.sales_id) DESC');

		$query = $this->db->get();

		return $query->result();
	}
	public function get_sales_sum_credit($date_from,$date_to,$server_id = null,$type)
	{
		$this->db->select('payment.*, sum(amount) as total_amount, count(payment_id) as pay_count',false);
		$this->db->from('trans_sales');
		$this->db->join('users as waiters','trans_sales.user_id = waiters.id');
		$this->db->join('trans_sales_payments as payment','trans_sales.sales_id = payment.sales_id');
		$this->db->where('DATE(trans_sales.datetime) >=',$date_from);
		$this->db->where('DATE(trans_sales.datetime) <=',$date_to);
		$this->db->where('payment.payment_type',$type);
		$this->db->where('trans_sales.type_id',10);
		$this->db->where('trans_sales.inactive',0);
		$this->db->where('trans_sales.trans_ref IS NOT NULL', null, false);
		if (!empty($server_id))
			$this->db->where('trans_sales.user_id',$server_id);

		$this->db->group_by('payment.card_type');
		//$this->db->order_by('count(trans_sales.sales_id) DESC');

		$query = $this->db->get();

		return $query->result();
	}
	public function get_sales_sum_gc($date_from,$date_to,$server_id = null,$type)
	{
		$this->db->select('payment.*, sum(payment.amount) as total_amount, count(payment_id) as pay_count, gift_cards.amount as gc_amount',false);
		$this->db->from('trans_sales');
		$this->db->join('users as waiters','trans_sales.user_id = waiters.id');
		$this->db->join('trans_sales_payments as payment','trans_sales.sales_id = payment.sales_id');
		$this->db->join('gift_cards','gift_cards.card_no = payment.reference');
		$this->db->where('DATE(trans_sales.datetime) >=',$date_from);
		$this->db->where('DATE(trans_sales.datetime) <=',$date_to);
		$this->db->where('payment.payment_type',$type);
		$this->db->where('trans_sales.type_id',10);
		$this->db->where('trans_sales.inactive',0);
		$this->db->where('trans_sales.trans_ref IS NOT NULL', null, false);
		if (!empty($server_id))
			$this->db->where('trans_sales.user_id',$server_id);

		$this->db->group_by('gift_cards.amount');
		//$this->db->order_by('count(trans_sales.sales_id) DESC');

		$query = $this->db->get();

		return $query->result();
	}
	public function get_emp_daily_sums($date_from,$date_to,$server_id = null)
	{
		$this->db->select('
			waiters.fname,
			waiters.mname,
			waiters.lname,
			count(trans_sales.sales_id) "total_orders",
			sum(trans_sales.total_amount) "total_amount"
		',false);
		$this->db->from('trans_sales');
		$this->db->join('users as waiters','trans_sales.user_id = waiters.id');
		$this->db->where('DATE(trans_sales.datetime) >=',$date_from);
		$this->db->where('DATE(trans_sales.datetime) <=',$date_to);
		$this->db->where('trans_sales.type_id',10);
		$this->db->where('trans_sales.inactive',0);
		$this->db->where('trans_sales.trans_ref IS NOT NULL', null, false);
		if (!empty($server_id))
			$this->db->where('trans_sales.user_id',$server_id);


		$this->db->group_by('trans_sales.user_id');
		$this->db->order_by('count(trans_sales.sales_id) DESC');

		$query = $this->db->get();

		return $query->result();
	}
	public function get_void_total_all($date_from,$date_to,$server_id = null){
		$this->db->select('count(trans_sales.sales_id) "total_orders",
							sum(trans_sales.total_amount) "total_amount"',false);
		$this->db->from('trans_sales');
		$this->db->join('users as waiters','trans_sales.user_id = waiters.id');
		$this->db->where('trans_sales.type_id', 11);
		// $this->db->where('trans_sales.inactive', 1);
		$this->db->where('DATE(trans_sales.datetime) >=',$date_from);
		$this->db->where('DATE(trans_sales.datetime) <=',$date_to);
		if (!empty($server_id))
			$this->db->where('trans_sales.user_id',$server_id);

		$this->db->group_by('trans_sales.user_id');

		$query = $this->db->get();
		return $query->result();

	}
	public function get_open_total_all($date_from,$date_to,$server_id = null){
		$this->db->select('count(trans_sales.sales_id) "total_orders",
							sum(trans_sales.total_amount) "total_amount"',false);
		$this->db->from('trans_sales');
		$this->db->join('users as waiters','trans_sales.user_id = waiters.id');
		$this->db->where('trans_sales.type_id', 10);
		$this->db->where('trans_sales.inactive', 0);
		$this->db->where('DATE(trans_sales.datetime) >=',$date_from);
		$this->db->where('DATE(trans_sales.datetime) <=',$date_to);
		$this->db->where('trans_sales.trans_ref', null);
		if (!empty($server_id))
			$this->db->where('trans_sales.user_id',$server_id);

		$this->db->group_by('trans_sales.user_id');

		$query = $this->db->get();
		return $query->result();

	}
	public function get_open_voided_all($date_from,$date_to,$server_id = null){
		$this->db->select('count(trans_sales.sales_id) "total_orders",
							sum(trans_sales.total_amount) "total_amount"',false);
		$this->db->from('trans_sales');
		$this->db->join('users as waiters','trans_sales.user_id = waiters.id');
		$this->db->where('trans_sales.type_id', 10);
		$this->db->where('trans_sales.inactive', 1);
		$this->db->where('DATE(trans_sales.datetime) >=',$date_from);
		$this->db->where('DATE(trans_sales.datetime) <=',$date_to);
		$this->db->where('trans_sales.trans_ref', null);
		if (!empty($server_id))
			$this->db->where('trans_sales.user_id',$server_id);

		$this->db->group_by('trans_sales.user_id');

		$query = $this->db->get();
		return $query->result();

	}
	public function get_discount_all($date_from,$date_to,$server_id = null){
		$this->db->select('disc_code, disc_rate, sum(amount) as disc_amount, count(sales_disc_id) as count_disc');
		$this->db->from('trans_sales');
		$this->db->join('users as waiters','trans_sales.user_id = waiters.id');
		$this->db->join('trans_sales_discounts as disc','trans_sales.sales_id = disc.sales_id');
		$this->db->where('trans_sales.type_id', 10);
		$this->db->where('trans_sales.inactive', 0);
		$this->db->where('DATE(trans_sales.datetime) >=',$date_from);
		$this->db->where('DATE(trans_sales.datetime) <=',$date_to);
		$this->db->where('trans_sales.trans_ref IS NOT NULL', null, false);
		if (!empty($server_id))
			$this->db->where('trans_sales.user_id',$server_id);

		$this->db->group_by('disc_code');

		$query = $this->db->get();
		return $query->result();
	}
	public function get_inventory_moves($date)
	{
		$prepare ='
			SELECT
				GROUP_CONCAT(
					\'SUM(IF(item_moves.type_id =\',
					trans_types.type_id,
					\',qty,NULL)) as "!!Trans-\',
					trans_types.name,
				\'"\') as msql
			FROM trans_types
		';

		$query = $this->db->query($prepare);
		$query = $query->result();

		$prepped = $query[0]->msql;

		if (empty($prepped))
			return null;

		$sql = '
			SELECT
				items.code,
				items.name,
				items.uom,
				'.$prepped.'
			FROM
				item_moves
			JOIN items ON item_moves.item_id = items.item_id
			JOIN trans_types ON item_moves.type_id = trans_types.type_id
			WHERE DATE(item_moves.reg_date) = \''.date('Y-m-d',strtotime($date)).'\'
			GROUP BY item_moves.item_id
		';

		$r_query = $this->db->query($r_query);
		return $r_query;
	}

	public function get_details($where,$table,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from($table);
			if($where)
				$this->db->where($where);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_last_ids($id,$table){
		$this->db->trans_start();
			$this->db->select($id);
			$this->db->from($table);
			$this->db->order_by($id.' desc');
			$this->db->limit('1');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		// if(isset($result[0])){
		// 	return $result[0]->menu_id;
		// }else{
		// 	return 0;
		// }
		return $result;
	}
	public function add_event_logs($user_id=null,$module_report=null,$action_done=null){
		$this->db->set('datetime', 'NOW()', FALSE);
		$items = array(
			"module_report" => $module_report,
			"action_done" => $action_done,
			"user_id" => $user_id,
			// "reference" => $reference
		);
		$this->db->insert('event_logs',$items);
		$x=$this->db->insert_id();
		return $x;
	}
}