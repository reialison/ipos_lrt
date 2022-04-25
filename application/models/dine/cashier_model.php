<?php
class Cashier_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
		$date = null;
		if($this->input->post('daterange')){
			$date = $this->input->post('daterange');
		}  
		else if($this->input->post('date')){
			$date = $this->input->post('date');
		}
		else if($this->input->post('month')){
			$today = sql2Date(phpNow());
			$year = date('Y',strtotime($today));
        	$date = $year."-".$this->input->post('month')."-01";
		}
		
		if($date != null){
			$dates = explode(" to ",$date);
			$used = $dates[0];
			$today = sql2Date(phpNow());
			if(strtotime($used) < strtotime($today)){
				$this->change_db();
			}
		}
		else{
			if($this->input->post('change_db')){
				$this->change_db();
			}
		}
	}
	function change_db(){
		$this->db = $this->load->database('main', TRUE);
	}
	public function get_pos_settings(){
		$this->db->select('settings.*');
		$this->db->from('settings');
		$this->db->where('settings.id',1);
		$query = $this->db->get();
		$res = $query->result();
		return $res[0];
	}
	public function update_pos_settings($items){
		// $this->db->where('code', $id);
		$this->db->where('id', 1);
		$this->db->update('settings', $items);
	}
	public function update_loyalty_card_points($card_id,$points){
		$this->db->set('points', '`points`+'.$points, FALSE);
		$this->db->where('card_id', $card_id);
		$this->db->update('loyalty_cards');
	}
	public function get_hourly_sales($sales_id=null,$time_from=null,$time_to=null,$date_from=null,$date_to=null){
		$this->db->select(
			'count(sales_id) as sales_count,SUM(IF(!inactive,1,0)) AS "sales_check",
			SUM(IF(ISNULL(guest)||guest = 0, 1, guest)) AS "sales_cover",
		 	SUM(IF(!inactive,total_amount,-total_amount)) "sales_total"
			',false);
		$this->db->from('trans_sales');
		$where = "type_id = ".SALES_TRANS." AND (!inactive OR inactive AND !ISNULL(void_ref))";
		$where .= " AND datetime BETWEEN '$date_from $time_from:00' AND '"
			.($time_from >= $time_to ? date('Y-m-d G:i:s',strtotime($date_from." ".$time_to.":00")) : $date_to." ".$time_to.":00")."'";
		// $this->db->where_between('datetime', $time_from, $time_to);
		$this->db->where($where);

		$query = $this->db->get();
		return $query->row();
	}
	public function get_trans_sales($sales_id=null,$args=array(),$order='desc',$joinTables=null){
		$this->db->select('
			trans_sales.*,
			users.username,
			terminals.terminal_name,
			terminals.terminal_code,
			tables.name as table_name,
			waiter.username as waiterusername,
			waiter.fname as waiterfname,
			waiter.mname as waitermname,
			waiter.lname as waiterlname,
			waiter.suffix as waitersuffix,
			cb.ref_no,
			cb.amount as depo_amount,
			c.lname,c.fname,c.mname
			');
			// trans_sales_payments.payment_type pay_type,
			// trans_sales_payments.amount pay_amount,
			// trans_sales_payments.reference pay_ref,
			// trans_sales_payments.card_type pay_card,
		$this->db->from('trans_sales');
		$this->db->join('users','trans_sales.user_id = users.id');
		$this->db->join('users as waiter','trans_sales.waiter_id = waiter.id','left');
		$this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
		// $this->db->join('shifts','trans_sales.shift_id = shifts.shift_id');
		$this->db->join('tables','trans_sales.table_id = tables.tbl_id','left');
		$this->db->join('customers_bank cb','trans_sales.sales_id = cb.sales_id','left');
		$this->db->join('customers c','cb.cust_id = c.cust_id','left');
		// $this->db->join('trans_sales_payments','trans_sales.sales_id = trans_sales_payments.sales_id','left');

		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
			}
		}
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
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->order_by('trans_sales.datetime',$order);
		$query = $this->db->get();
		// echo $this->db->last_query();die();
		return $query->result();
	}
	public function get_trans_sales_rep($sales_id=null,$args=array(),$order='desc',$joinTables=null){
		$this->db->select('
			trans_sales.*,
			users.username,
			terminals.terminal_name,
			terminals.terminal_code,
			tables.name as table_name,
			waiter.username as waiterusername,
			waiter.fname as waiterfname,
			waiter.mname as waitermname,
			waiter.lname as waiterlname,
			waiter.suffix as waitersuffix
			');
			// trans_sales_payments.payment_type pay_type,
			// trans_sales_payments.amount pay_amount,
			// trans_sales_payments.reference pay_ref,
			// trans_sales_payments.card_type pay_card,
		$this->db->from('trans_sales');
		$this->db->join('users','trans_sales.user_id = users.id');
		$this->db->join('users as waiter','trans_sales.waiter_id = waiter.id','left');
		$this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
		// $this->db->join('shifts','trans_sales.shift_id = shifts.shift_id');
		$this->db->join('tables','trans_sales.table_id = tables.tbl_id','left');
		$this->db->join('trans_sales_payments','trans_sales.sales_id = trans_sales_payments.sales_id','left');

		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
			}
		}
		// if(HIDECHIT){
		// 	$this->db->where('trans_sales_payments.payment_type !=','chit');
		// }
		// if(PRODUCT_TEST){
		// 	$this->db->where('trans_sales_payments.payment_type !=','producttest');
		// }
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
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->order_by('trans_sales.datetime',$order);
		// echo $this->db->last_query();
		$query = $this->db->get();
		return $query->result();
	}
	public function get_just_trans_sales($sales_id=null,$args=array(),$order='desc',$joinTables=null){
		$this->db->select('
			trans_sales.*
			');
		$this->db->from('trans_sales');

		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
			}
		}

		// $this->db->join('trans_sales_payments','trans_sales.sales_id = trans_sales_payments.sales_id','left');
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
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->order_by('trans_sales.datetime',$order);
		// echo $this->db->last_query();
		$query = $this->db->get();
		return $query;
	}
	public function get_trans_sales_entries($entry_id=null,$args=array()){
		$this->db->select('shift_entries.*');
		$this->db->from('shift_entries');
		if (!is_null($entry_id)){
			if (is_array($entry_id))
				$this->db->where_in('shift_entries.entry_id',$entry_id);
			else
				$this->db->where('shift_entries.entry_id',$entry_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->order_by('shift_entries.entry_id asc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_trans_sales_categories($menu_cat_id=null,$args=array()){
		$this->db->select('menu_categories.menu_cat_id,
						   menu_categories.menu_cat_name,
						   trans_sales_menus.*
						   ');
		$this->db->from('trans_sales_menus');
		$this->db->join('menus','trans_sales_menus.menu_id = menus.menu_id','right');
		$this->db->join('menu_categories','menus.menu_cat_id = menu_categories.menu_cat_id','right');
		$this->db->join('trans_sales','trans_sales_menus.sales_id = trans_sales.sales_id');
		if (!is_null($menu_cat_id)){
			if (is_array($menu_cat_id))
				$this->db->where_in('menu_categories.menu_cat_id',$menu_cat_id);
			else
				$this->db->where('menu_categories.menu_cat_id',$menu_cat_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val)>0)
							$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		// $this->db->group_by('menus.menu_cat_id');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_trans_sales_payments_group($sales_id=null,$args=array()){
		$this->db->select('trans_sales_payments.payment_type,trans_sales_payments.sales_id,count(trans_sales_payments.sales_id) as count,
						   sum(trans_sales_payments.amount - trans_sales_payments.to_pay) as total, sum(trans_sales_payments.amount),sum(trans_sales_payments.to_pay) as to_pay,trans_sales.total_paid ');
		$this->db->from('trans_sales_payments');
		$this->db->join('trans_sales','trans_sales_payments.sales_id = trans_sales.sales_id');
		if (!is_null($sales_id)){
			if (is_array($sales_id))
				$this->db->where_in('trans_sales_payments.sales_id',$sales_id);
			else
				$this->db->where('trans_sales_payments.sales_id',$sales_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->group_by('trans_sales_payments.sales_id');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_all_trans_sales_payments($sales_id=null,$args=array()){
		$this->db->select('trans_sales_payments.payment_type,trans_sales_payments.sales_id,trans_sales_payments.amount,trans_sales_payments.to_pay,trans_sales.total_paid,card_type');
		$this->db->from('trans_sales_payments');
		$this->db->join('trans_sales','trans_sales_payments.sales_id = trans_sales.sales_id  and trans_sales_payments.pos_id = trans_sales.pos_id');
		if (!is_null($sales_id)){
			if (is_array($sales_id))
				$this->db->where_in('trans_sales_payments.sales_id',$sales_id);
			else
				$this->db->where('trans_sales_payments.sales_id',$sales_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		// $this->db->group_by('trans_sales_payments.sales_id');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_trans_sales_discounts_group($disc_code=null,$args=array()){
		$this->db->select('trans_sales_discounts.disc_code,sum(trans_sales_discounts.amount) as total');
		$this->db->from('trans_sales_discounts');
		$this->db->join('trans_sales','trans_sales_discounts.sales_id = trans_sales_discounts.sales_id');
		if (!is_null($disc_code)){
			if (is_array($disc_code))
				$this->db->where_in('trans_sales_discounts.disc_code',$disc_code);
			else
				$this->db->where('trans_sales_discounts.disc_code',$disc_code);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->group_by('trans_sales_discounts.disc_code');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_menu_sales($sales_id=null,$args=array(),$order='desc',$joinTables=null){
		$this->db->select('trans_sales_menus.sales_id,
						   menus.menu_name,menus.menu_cat_id,trans_sales_menus.menu_id,menus.menu_sub_cat_id as sub_cat_id,menu_subcategories.menu_sub_cat_name as sub_cat_name,
						   sum(IF(ISNULL(trans_sales_menus.qty) || trans_sales_menus.qty = 0, 1, trans_sales_menus.qty)) as total_qty,
						   sum(IF(ISNULL(trans_sales_menus.qty) || (trans_sales_menus.qty * trans_sales_menus.price) = 0, 1, (trans_sales_menus.qty * trans_sales_menus.price) )) as total_amount',false);

		$this->db->from('menus');
		$this->db->join('trans_sales_menus','trans_sales_menus.menu_id = menus.menu_id');
		$this->db->join('trans_sales','trans_sales.sales_id = trans_sales_menus.sales_id');
		$this->db->join('menu_subcategories','menus.menu_sub_cat_id = menu_subcategories.menu_sub_cat_id','left');
		$this->db->join('users','trans_sales.user_id = users.id');
		$this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
			}
		}
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
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->group_by('trans_sales_menus.menu_id',$order);

		$this->db->order_by('total_qty',$order);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_menu_item_sales($sales_id=null,$args=array(),$order='desc',$joinTables=null){
		$this->db->select('menus.menu_name,sum(cost) as total_per_menu,sum(trans_sales_menus.qty) as menu_qty',false);
		$this->db->from('trans_sales');
		$this->db->join('trans_sales_menus','trans_sales.sales_id = trans_sales_menus.sales_id');
		$this->db->join('menus','menus.menu_id = trans_sales_menus.menu_id');
		// $this->db->join('menus','menus.menu_id = trans_sales_menus.menu_id');
		// $this->db->join('trans_sales_menus','trans_sales_menus.menu_id = menus.menu_id');
		// $this->db->join('trans_sales','trans_sales.sales_id = trans_sales_menus.sales_id');
		// $this->db->join('menus','trans_sales_menus.menu_id = menus.menu_id','right');
		// $this->db->join('users','trans_sales.user_id = users.id');
		// $this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
		// if (!is_null($joinTables) && is_array($joinTables)) {
		// 	foreach ($joinTables as $k => $v) {
		// 		$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
		// 	}
		// }
		// if (!is_null($sales_id)){
		// 	if (is_array($sales_id))
		// 		$this->db->where_in('trans_sales.sales_id',$sales_id);
		// 	else
		// 		$this->db->where('trans_sales.sales_id',$sales_id);
		// }
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->group_by('menus.menu_id');

		// $this->db->order_by('total_qty',$order);
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
	public function get_reasons($trans_id=null,$args=array()){
		$this->db->select('reasons.*,
						   cas.fname,cas.mname,cas.lname,cas.suffix,
						   man.fname,man.mname,man.lname,man.suffix,
						   man.username as man_username,
						   cas.username as cas_username
						   ');
		$this->db->from('reasons');
		$this->db->join('users cas','reasons.user_id=cas.id');
		$this->db->join('users man','reasons.manager_id=man.id');
		if (!is_null($trans_id)){
			if (is_array($trans_id))
				$this->db->where_in('reasons.trans_id',$trans_id);
			else
				$this->db->where('reasons.trans_id',$trans_id);
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
		$this->db->order_by('reasons.datetime desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_just_reasons($trans_id=null,$args=array()){
		$this->db->select('reasons.*');
		$this->db->from('reasons');
		$this->db->join('users cas','reasons.user_id=cas.id');
		$this->db->join('users man','reasons.user_id=man.id');
		if (!is_null($trans_id)){
			if (is_array($trans_id))
				$this->db->where_in('reasons.trans_id',$trans_id);
			else
				$this->db->where('reasons.trans_id',$trans_id);
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
		$this->db->order_by('reasons.datetime desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_reasons($items){
		// $this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('reasons',$items);
		return $this->db->insert_id();
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
	public function get_trans_sales_items($sales_item_id=null,$args=array()){
		$this->db->select('trans_sales_items.*,items.code,items.name');
		$this->db->from('trans_sales_items');
		$this->db->join('items','trans_sales_items.item_id=items.item_id');
		if (!is_null($sales_item_id)){
			if (is_array($sales_item_id))
				$this->db->where_in('trans_sales_items.sales_item_id',$sales_item_id);
			else
				$this->db->where('trans_sales_items.sales_item_id',$sales_item_id);
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
		$this->db->order_by('trans_sales_items.line_id asc');
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
	public function add_trans_sales_items($items){
		$this->db->insert_batch('trans_sales_items',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_items($sales_id){
		$this->db->where('trans_sales_items.sales_id', $sales_id);
		$this->db->delete('trans_sales_items');
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
						if(count($val) > 0)
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
	public function get_trans_sales_menu_submodifiers($sales_submod_id=null,$args=array()){
		$this->db->select('trans_sales_menu_submodifiers.*');
		$this->db->from('trans_sales_menu_submodifiers');
		// $this->db->join('modifiers','trans_sales_menu_modifiers.mod_id=modifiers.mod_id');
		if (!is_null($sales_submod_id)){
			if (is_array($sales_mod_id))
				$this->db->where_in('trans_sales_menu_submodifiers.sales_submod_id',$sales_submod_id);
			else
				$this->db->where('trans_sales_menu_submodifiers.sales_submod_id',$sales_submod_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
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
		$this->db->order_by('trans_sales_menu_submodifiers.mod_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_trans_sales_discounts($sales_disc_id=null,$args=array()){
		$this->db->select('trans_sales_discounts.*,receipt_discounts.disc_name,receipt_discounts.fix');
		$this->db->from('trans_sales_discounts');
		$this->db->join('receipt_discounts','trans_sales_discounts.disc_id = receipt_discounts.disc_id');
		if (!is_null($sales_disc_id)){
			if (is_array($sales_disc_id))
				$this->db->where_in('trans_sales_discounts.sales_disc_id',$sales_disc_id);
			else
				$this->db->where('trans_sales_discounts.sales_disc_id',$sales_disc_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0 )
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
		$this->db->order_by('trans_sales_discounts.sales_disc_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_trans_sales_discounts_sc($sales_disc_id=null,$args=array()){
		$this->db->select('trans_sales_discounts.*,receipt_discounts.disc_name,receipt_discounts.fix');
		$this->db->from('trans_sales_discounts');
		$this->db->join('receipt_discounts','trans_sales_discounts.disc_id = receipt_discounts.disc_id');
		if (!is_null($sales_disc_id)){
			if (is_array($sales_disc_id))
				$this->db->where_in('trans_sales_discounts.sales_disc_id',$sales_disc_id);
			else
				$this->db->where('trans_sales_discounts.sales_disc_id',$sales_disc_id);
		}
		$this->db->where('trans_sales_discounts.disc_code = ','SNDISC');
		$this->db->or_where('trans_sales_discounts.disc_code = ','PWDISC');

		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0 )
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
		$this->db->order_by('trans_sales_discounts.sales_disc_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_sales_menu_modifiers($items){
		$this->db->insert_batch('trans_sales_menu_modifiers',$items);
		return $this->db->insert_id();
	}
	public function add_trans_sales_menu_submodifiers($items){
		$this->db->insert_batch('trans_sales_menu_submodifiers',$items);
		return $this->db->insert_id();
	}
	public function add_trans_sales_discounts($items){
		$this->db->insert_batch('trans_sales_discounts',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_discounts($sales_id){
		$this->db->where('trans_sales_discounts.sales_id', $sales_id);
		$this->db->delete('trans_sales_discounts');
	}
	public function delete_trans_sales_menu_modifiers($sales_id){
		$this->db->where('trans_sales_menu_modifiers.sales_id', $sales_id);
		$this->db->delete('trans_sales_menu_modifiers');
	}
	public function delete_trans_sales_menu_submodifiers($sales_id){
		$this->db->where('trans_sales_menu_submodifiers.sales_id', $sales_id);
		$this->db->delete('trans_sales_menu_submodifiers');
	}
	public function get_trans_sales_charges($sales_charge_id=null,$args=array()){
		$this->db->select('trans_sales_charges.*');
		$this->db->from('trans_sales_charges');
		if (!is_null($sales_charge_id)){
			if (is_array($sales_charge_id))
				$this->db->where_in('trans_sales_charges.sales_charge_id',$sales_charge_id);
			else
				$this->db->where('trans_sales_charges.sales_charge_id',$sales_charge_id);
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
		$this->db->order_by('trans_sales_charges.sales_charge_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_sales_charges($items){
		$this->db->insert_batch('trans_sales_charges',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_charges($sales_id){
		$this->db->where('trans_sales_charges.sales_id', $sales_id);
		$this->db->delete('trans_sales_charges');
	}
	public function get_trans_sales_payments($payment_id=null,$args=array()){
		$this->db->select('trans_sales_payments.*,users.username');
		$this->db->from('trans_sales_payments');
		$this->db->join('users','trans_sales_payments.user_id = users.id');
		if (!is_null($payment_id)){
			if (is_array($payment_id))
				$this->db->where_in('trans_sales_payments.payment_id',$payment_id);
			else
				$this->db->where('trans_sales_payments.payment_id',$payment_id);
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
		$this->db->order_by('trans_sales_payments.payment_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_sales_payments($items){
		$this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('trans_sales_payments',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_payments($payment_id){
		$this->db->where('trans_sales_payments.payment_id', $payment_id);
		$this->db->delete('trans_sales_payments');
	}
	public function get_trans_sales_tax($sales_tax_id=null,$args=array()){
		$this->db->select('trans_sales_tax.*');
		$this->db->from('trans_sales_tax');
		if (!is_null($sales_tax_id)){
			if (is_array($sales_tax_id))
				$this->db->where_in('trans_sales_tax.sales_tax_id',$sales_tax_id);
			else
				$this->db->where('trans_sales_tax.sales_tax_id',$sales_tax_id);
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
		$this->db->order_by('trans_sales_tax.sales_tax_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_sales_tax($items){
		$this->db->insert_batch('trans_sales_tax',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_tax($sales_id){
		$this->db->where('trans_sales_tax.sales_id', $sales_id);
		$this->db->delete('trans_sales_tax');
	}
	public function get_trans_sales_no_tax($sales_no_tax_id=null,$args=array()){
		$this->db->select('trans_sales_no_tax.*');
		$this->db->from('trans_sales_no_tax');
		if (!is_null($sales_no_tax_id)){
			if (is_array($sales_no_tax_id))
				$this->db->where_in('trans_sales_no_tax.sales_no_tax_id',$sales_no_tax_id);
			else
				$this->db->where('trans_sales_no_tax.sales_no_tax_id',$sales_no_tax_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
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
		$this->db->order_by('trans_sales_no_tax.sales_no_tax_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_trans_sales_zero_rated($sales_zero_rated_id=null,$args=array()){
		$this->db->select('trans_sales_zero_rated.*');
		$this->db->from('trans_sales_zero_rated');
		if (!is_null($sales_zero_rated_id)){
			if (is_array($sales_zero_rated_id))
				$this->db->where_in('trans_sales_zero_rated.sales_zero_rated_id',$sales_zero_rated_id);
			else
				$this->db->where('trans_sales_zero_rated.sales_zero_rated_id',$sales_zero_rated_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
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
		$this->db->order_by('trans_sales_zero_rated.sales_zero_rated_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_trans_sales_local_tax($sales_local_tax_id=null,$args=array()){
		$this->db->select('trans_sales_local_tax.*');
		$this->db->from('trans_sales_local_tax');
		if (!is_null($sales_local_tax_id)){
			if (is_array($sales_local_tax_id))
				$this->db->where_in('trans_sales_local_tax.sales_local_tax_id',$sales_local_tax_id);
			else
				$this->db->where('trans_sales_local_tax.sales_local_tax_id',$sales_local_tax_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
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
		$this->db->order_by('trans_sales_local_tax.sales_local_tax_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_sales_no_tax($items){
		$this->db->insert_batch('trans_sales_no_tax',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_no_tax($sales_id){
		$this->db->where('trans_sales_no_tax.sales_id', $sales_id);
		$this->db->delete('trans_sales_no_tax');
	}
	public function add_trans_sales_zero_rated($items){
		$this->db->insert_batch('trans_sales_zero_rated',$items);
		return $this->db->insert_id();
	}
	public function add_trans_sales_local_tax($items){
		$this->db->insert_batch('trans_sales_local_tax',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_zero_rated($sales_id){
		$this->db->where('trans_sales_zero_rated.sales_id', $sales_id);
		$this->db->delete('trans_sales_zero_rated');
	}
	public function delete_trans_sales_local_tax($sales_id){
		$this->db->where('trans_sales_local_tax.sales_id', $sales_id);
		$this->db->delete('trans_sales_local_tax');
	}
	public function get_tables($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('tables');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('tables.tbl_id',$id);
				}else{
					$this->db->where('tables.tbl_id',$id);
				}
			$this->db->order_by('tbl_id desc');
			$this->db->where('tables.inactive',0);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_occupied_tables($tbl_id=null){
		$this->db->trans_start();
			$this->db->select('trans_sales.table_id,tables.name,trans_sales.billed');
			$this->db->from('trans_sales');
			$this->db->join('tables','trans_sales.table_id = tables.tbl_id');
			if($tbl_id != null){
				if(is_array($tbl_id))
				{
					$this->db->where_in('table_id',$tbl_id);
				}else{
					$this->db->where('table_id',$tbl_id);
				}
			}
			$this->db->where('trans_sales.trans_ref is null','',false);
			$this->db->where('trans_sales.inactive',0);
			$this->db->group_by('table_id');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_menu_promos($id=null,$ttype=null){
		$this->db->trans_start();
			$this->db->select('promo_discount_items.*,promo_discounts.promo_code,promo_discounts.promo_name,promo_discounts.value,promo_discounts.absolute');
			$this->db->from('promo_discount_items');
			$this->db->join('promo_discounts','promo_discount_items.promo_id = promo_discounts.promo_id');
			if($id != null){
				if(is_array($id))
				{
					$this->db->where_in('promo_discount_items.item_id',$id);
				}else{
					$this->db->where('promo_discount_items.item_id',$id);
				}
			}
			if($ttype !=null){
				$this->db->where('promo_discounts.trans_type',$ttype);	
			}
			$this->db->where('promo_discounts.inactive',0);
			$this->db->order_by('promo_discount_items.item_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_menu_promo_schedule($id=null,$day=null,$time=null){
		$this->db->trans_start();
			$this->db->select('promo_discount_schedule.*');
			$this->db->from('promo_discount_schedule');
			if($id != null){
				if(is_array($id))
				{
					$this->db->where_in('promo_discount_schedule.promo_id',$id);
				}else{
					$this->db->where('promo_discount_schedule.promo_id',$id);
				}
			}
			if($day != null){
				if(is_array($day))
				{
					$this->db->where_in('promo_discount_schedule.day',$day);
				}else{
					$this->db->where('promo_discount_schedule.day',$day);
				}
			}
			if($time != null){
				$this->db->where('promo_discount_schedule.time_on <= TIME("'.$time.'")',null,false);
				$this->db->where('promo_discount_schedule.time_off >= TIME("'.$time.'")',null,false);
			}
			$this->db->order_by('promo_discount_schedule.id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_reads($id=null,$args=array()){
		$this->db->select('read_details.*');
		$this->db->from('read_details');
		if($id != null)
			if(is_array($id))
			{
				$this->db->where_in('read_details.id',$id);
			}else{
				$this->db->where('read_details.id',$id);
			}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third'])){
							if(isset($val['operator'])){
								$this->db->$func($col." ".$val['operator']." ".$val['val']);
							}
							else
								$this->db->$func($col,$val['val'],$val['third']);
						}
						else{
							$this->db->$func($col,$val['val']);
						}
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$query = $this->db->get();
		return $query->result();
	}
	public function get_x_read_details($date=null)
	{
		$this->db->select('*');
		$this->db->from('read_details');
		$this->db->where('read_type',X_READ);
		$this->db->where('read_date >= ',$date);
		$this->db->order_by('id','asc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_next_x_read_details($datetime=null){
		$this->db->select('*');
		$this->db->from('read_details');
		$this->db->where('read_type',X_READ);
		$this->db->where('scope_from >= ',$datetime);
		$this->db->order_by('id','asc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_read_details($type=X_READ,$date=null)
	{
		$this->db->select('*');
		$this->db->from('read_details');
		$this->db->where('read_type',$type);
		$this->db->where('scope_to <= ',$date);
				$query = $this->db->get();
		return $query->result();
	}
	public function get_last_z_read($type=X_READ,$date=null)
	{
		$this->db->select('*');
		$this->db->from('read_details');
		$this->db->where('read_type',$type);
		if($date != null)
			$this->db->where("DATE(read_date) <= DATE('".$date."')",null,false);
		$this->db->order_by('read_details.id desc');
		$this->db->limit(1);

		$query = $this->db->get();
		return $query->result();
	}
	public function get_x_read_shift($xread_shift_id=null)
	{
		$this->db->select('*');
		$this->db->from('shifts');
		$this->db->where('xread_id',$xread_shift_id);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_last_z_read_on_date($type=X_READ,$date=null,$user_id=null)
	{
		$this->db->select('*');
		$this->db->from('read_details');
		$this->db->where('read_type',$type);
		if($date != null)
			$this->db->where("DATE(read_date) = DATE('".$date."')",null,false);
		if($user_id != null)
			$this->db->where('user_id',$user_id);
		$this->db->order_by('read_details.id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_lastest_z_read($type=X_READ,$date=null)
	{
		$this->db->select('*');
		$this->db->from('read_details');
		$this->db->where('read_type',$type);
		if($date != null)
			$this->db->where("DATE(read_date) < DATE('".$date."')",null,false);
		$this->db->order_by('read_details.id asc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_z_read($id=null,$args=null)
	{
		$this->db->select('*');
		$this->db->from('read_details');
		if($id != null)
			$this->db->where('id',$id);
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$query = $this->db->get();
		return $query->result();
	}
	public function get_last_new_gt($type=X_READ,$scope=null,$read_date=null)
	{
		$this->db->select_max('grand_total','grand_total');
		$this->db->from('read_details');
		$this->db->where('read_type',$type);
		$this->db->where('scope_to <= ',$scope);
		if($read_date != null)
			$this->db->where("DATE(read_date) <= DATE('".$read_date."')",null,false);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_latest_read_date($type)
	{
		$this->db->select_max('scope_to','maxi');
		$this->db->from('read_details');
		$this->db->where('read_type',$type);

		$query = $this->db->get();
		return $query->row();
	}
	public function check_latest_read_date($type,$read_date=null){
		$this->db->select_min('scope_from','today_zread');
		$this->db->from('read_details');
		$this->db->where('read_type',$type);
		if($read_date != null)
			$this->db->where("DATE(read_date) = DATE('".$read_date."')",null,false);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_user_shifts($args)
	{
		$this->db->select('
			shifts.*,
			users.username,
			terminals.terminal_name,
			sum(shift_entries.amount) cash_float
			',false);
		$this->db->from('shifts');
		$this->db->join('users','shifts.user_id = users.id');
		$this->db->join('terminals','shifts.terminal_id = terminals.terminal_id');
		$this->db->join('shift_entries','shifts.shift_id = shift_entries.shift_id','left');
		if (!empty($args))
			$this->db->where($args);

		$this->db->group_by('shifts.shift_id');

		$query = $this->db->get();
		return $query->result();
	}
	public function add_read_details($items)
	{
		$this->db->trans_start();
		$this->db->insert('read_details',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}
	public function get_cashout_header($cashout_id)
	{
		$this->db->select(
			'cashout_entries.*,
			users.username,
			shifts.check_in,
			shifts.check_out,
			terminals.terminal_code,
			terminals.terminal_name');
		$this->db->from('cashout_entries');
		$this->db->join('shifts','cashout_entries.cashout_id = shifts.cashout_id');
		$this->db->join('users','shifts.user_id = users.id');
		$this->db->join('terminals','cashout_entries.terminal_id = terminals.terminal_id');
		$this->db->where('cashout_entries.cashout_id',$cashout_id);

		$query = $this->db->get();
		return $query->row();
	}
	public function get_cashout_details($cashout_id,$id=null)
	{
		$this->db->select('*');
		$this->db->from('cashout_details');
		$this->db->where('cashout_id',$cashout_id);
		if (!is_null($id))
			$this->db->where('id',$id);

		$this->db->order_by('total desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_rob_files($code=null,$id=null){
		$this->db->select('*');
		$this->db->from('rob_files');
		if($code != null)
			$this->db->where('code',$code);
		if(!is_null($id))
			$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_unsent_rob_files(){
		$this->db->select('*');
		$this->db->from('rob_files');
		$this->db->where('inactive >',0);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_rob_path(){
		$this->db->select('rob_tenant_code,rob_username,rob_password,rob_path');
		$this->db->from('branch_details');
		$this->db->where('branch_id',1);
		$query = $this->db->get();
		$res =  $query->result();
		return $res[0];
	}
	public function add_rob_files($items){
		$this->db->trans_start();
		// $this->db->set('rob_files.date_created','NOW()',FALSE);
		$this->db->set('rob_files.last_update','NOW()',FALSE);
		$this->db->insert('rob_files',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}
	public function update_rob_files($items,$file){
		$this->db->set('rob_files.last_update','NOW()',FALSE);
		$this->db->where('rob_files.code',$file);
		$this->db->update('rob_files',$items);
	}
	///////////////Jed////////////////////////////
	public function get_trans_sales_daily($sales_id=null,$args=array(),$order='desc',$joinTables=null){
		$this->db->select('
			trans_sales.*,
			users.username,
			terminals.terminal_name,
			terminals.terminal_code,
			tables.name as table_name,
			waiter.fname as waiterfname,
			waiter.mname as waitermname,
			waiter.lname as waiterlname,
			waiter.suffix as waitersuffix,
			sum(trans_sales_menus.qty * trans_sales_menus.price) as menu_total,
			MIN(trans_sales.trans_ref) as min_ref,
			MAX(trans_sales.trans_ref) as max_ref
			');
			// sum(trans_sales_discounts.amount) as disc_amount
			// trans_sales_charges.amount as charge_amount,
			// trans_sales_payments.payment_type pay_type,
			// trans_sales_payments.amount pay_amount,
			// trans_sales_payments.reference pay_ref,
			// trans_sales_payments.card_type pay_card,
		$this->db->from('trans_sales');
		$this->db->join('users','trans_sales.user_id = users.id');
		$this->db->join('users as waiter','trans_sales.waiter_id = waiter.id','left');
		$this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
		// $this->db->join('shifts','trans_sales.shift_id = shifts.shift_id');
		$this->db->join('tables','trans_sales.table_id = tables.tbl_id','left');

		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
			}
		}
		// $this->db->join('menus','menus.menu_id = trans_sales_menus.menu_id');
		// $this->db->join('menu_categories','menu_categories.menu_cat_id = menus.menu_cat_id');
		// $this->db->join('trans_sales_payments','trans_sales.sales_id = trans_sales_payments.sales_id','left');
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
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->group_by('trans_sales.sales_id');
		$this->db->where('trans_sales.type_id',10);
		$this->db->order_by('trans_sales.datetime',$order);
		// echo $this->db->last_query();
		$query = $this->db->get();
		return $query->result();
	}
	public function table_updater($items,$where, $table){
		$this->db->where($where);
		$this->db->update($table, $items);

		return $this->db->last_query();
	}
	public function get_trans_sales_wcategories($sales_id=null,$args=array(),$order='desc',$joinTables=null){
 		$this->db->select('
 			trans_sales.*,
 			users.username,
 			terminals.terminal_name,
 			terminals.terminal_code,
 			tables.name as table_name,
 			waiter.username as waiterusername,
 			waiter.fname as waiterfname,
 			waiter.mname as waitermname,
 			waiter.lname as waiterlname,
 			waiter.suffix as waitersuffix,
 			sum(trans_sales_menus.qty * trans_sales_menus.price) as menu_total
 			');
 			// trans_sales_charges.amount as charge_amount,
 			// sum(trans_sales_discounts.amount) as disc_amount
 			// trans_sales_payments.payment_type pay_type,
 			// trans_sales_payments.amount pay_amount,
 			// trans_sales_payments.reference pay_ref,
 			// trans_sales_payments.card_type pay_card,
 		$this->db->from('trans_sales');
 		$this->db->join('users','trans_sales.user_id = users.id');
 		$this->db->join('users as waiter','trans_sales.waiter_id = waiter.id','left');
 		$this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
 		// $this->db->join('shifts','trans_sales.shift_id = shifts.shift_id');
 		$this->db->join('tables','trans_sales.table_id = tables.tbl_id','left');
 		// $this->db->join('trans_sales_charges','trans_sales.sales_id = trans_sales_charges.sales_id','left');
 		// $this->db->join('trans_sales_discounts','trans_sales.sales_id = trans_sales_discounts.sales_id','left');
 		$this->db->join('trans_sales_menus','trans_sales.sales_id = trans_sales_menus.sales_id');
 		$this->db->join('menus','menus.menu_id = trans_sales_menus.menu_id');
 		// $this->db->join('menu_categories','menu_categories.menu_cat_id = menus.menu_cat_id');
 
 		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
 			}
 		}
 
 		// $this->db->join('trans_sales_payments','trans_sales.sales_id = trans_sales_payments.sales_id','left');
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
 						if(isset($val['third']))
 							$this->db->$func($col,$val['val'],$val['third']);
 						else
 							$this->db->$func($col,$val['val']);
 					}
 				}
 				else
 					$this->db->where($col,$val);
 			}
 		}
 		$this->db->group_by('trans_sales.sales_id');
 		$this->db->order_by('trans_sales.datetime',$order);
 		echo $this->db->last_query();
 		$query = $this->db->get();
 		return $query->result();
 	}
 	public function get_trans_sales_discounts_group2($disc_code=null,$args=array()){
 		$this->db->select('trans_sales_discounts.disc_code,sum(trans_sales_discounts.amount) as total');
 		$this->db->from('trans_sales_discounts');
 		$this->db->join('trans_sales','trans_sales.sales_id = trans_sales_discounts.sales_id');
 		if (!is_null($disc_code)){
 			if (is_array($disc_code))
 				$this->db->where_in('trans_sales_discounts.disc_code',$disc_code);
 			else
 				$this->db->where('trans_sales_discounts.disc_code',$disc_code);
 		}
 		if(!empty($args)){
 			foreach ($args as $col => $val) {
 				if(is_array($val)){
 					if(!isset($val['use'])){
 						$this->db->where_in($col,$val);
 					}
 					else{
 						$func = $val['use'];
 						if(isset($val['third']))
 							$this->db->$func($col,$val['val'],$val['third']);
 						else
 							$this->db->$func($col,$val['val']);
 					}
 				}
 				else
 					$this->db->where($col,$val);
 			}
 		}
 		$this->db->group_by('trans_sales_discounts.sales_id');
 		$query = $this->db->get();
		return $query->result();
	}
	public function add_transfer_split($items){
		$this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('transfer_split',$items);
		return $this->db->insert_id();
	}
//////////////////////////end Jed/////////////////////////////
//Nicko Q. 3202020
	public function get_dicounts(){//get all active discounts
		$this->db->select('*');
		$this->db->from('receipt_discounts');
		$this->db->where('inactive',0);
		$this->db->order_by('disc_name ASC');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_active_receipt_discounts($id = null)
	{
		$this->db->from('receipt_discounts');
		if (!is_null($id)) {
			if (is_array($id))
				$this->db->where_in('disc_id',$id);
			else
				$this->db->where('disc_id',$id);
		}
		$this->db->where('inactive',0);
		$query = $this->db->get();
		return $query->result();
	}

	//temp db
	public function add_temp_sales($items){
		// $this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('temp_sales',$items);
		return $this->db->insert_id();
	}

	public function add_temp_sales_menus($items){
		$this->db->insert_batch('temp_sales_menus',$items);
		return $this->db->insert_id();
	}

	public function add_temp_sales_items($items){
		$this->db->insert_batch('temp_sales_items',$items);
		return $this->db->insert_id();
	}

	public function add_temp_sales_menu_modifiers($items){
		$this->db->insert_batch('temp_sales_menu_modifiers',$items);
		return $this->db->insert_id();
	}

	public function add_temp_sales_discounts($items){
		$this->db->insert_batch('temp_sales_discounts',$items);
		return $this->db->insert_id();
	}

	public function add_temp_sales_charges($items){
		$this->db->insert_batch('temp_sales_charges',$items);
		return $this->db->insert_id();
	}

	public function add_temp_sales_zero_rated($items){
		$this->db->insert_batch('temp_sales_zero_rated',$items);
		return $this->db->insert_id();
	}

	public function add_temp_sales_no_tax($items){
		$this->db->insert_batch('temp_sales_no_tax',$items);
		return $this->db->insert_id();
	}

	public function add_temp_sales_tax($items){
		$this->db->insert_batch('temp_sales_tax',$items);
		return $this->db->insert_id();
	}

	public function add_temp_sales_local_tax($items){
		$this->db->insert_batch('temp_sales_local_tax',$items);
		return $this->db->insert_id();
	}

	public function update_temp_sales($items,$sales_id){
		$this->db->set('temp_sales.update_date','NOW()',FALSE);
		$this->db->where('temp_sales.sales_id',$sales_id);
		$this->db->update('temp_sales',$items);
	}

	public function delete_temp_all(){
		// $this->db->where('trans_sales_menus.sales_id', $sales_id);
		$this->db->query("DELETE from temp_sales");
		$this->db->query("DELETE from temp_sales_menus");
		$this->db->query("DELETE from temp_sales_items");
		$this->db->query("DELETE from temp_sales_discounts");
		$this->db->query("DELETE from temp_sales_charges");
		$this->db->query("DELETE from temp_sales_payments");
		$this->db->query("DELETE from temp_sales_tax");
		$this->db->query("DELETE from temp_sales_no_tax");
		$this->db->query("DELETE from temp_sales_zero_rated");
		$this->db->query("DELETE from temp_sales_local_tax");
		$this->db->query("DELETE from temp_sales_loyalty_points");
		$this->db->query("DELETE from temp_sales_menu_modifiers");
		// $this->db->delete('temp_sales');
		// $this->db->delete('temp_sales_menus');
		// $this->db->delete('temp_sales_items');
		// $this->db->delete('temp_sales_discounts');
		// $this->db->delete('temp_sales_charges');
		// $this->db->delete('temp_sales_payments');
		// $this->db->delete('temp_sales_tax');
		// $this->db->delete('temp_sales_no_tax');
		// $this->db->delete('temp_sales_zero_rated');
		// $this->db->delete('temp_sales_local_tax');
		// $this->db->delete('temp_sales_loyalty_points');
		// $this->db->delete('temp_sales_menu_modifiers');
	}

	public function get_temp_sales($sales_id=null,$args=array(),$order='desc',$joinTables=null){
		$this->db->select('
			temp_sales.*,
			users.username,
			terminals.terminal_name,
			terminals.terminal_code,
			tables.name as table_name,
			waiter.username as waiterusername,
			waiter.fname as waiterfname,
			waiter.mname as waitermname,
			waiter.lname as waiterlname,
			waiter.suffix as waitersuffix
			');
			// trans_sales_payments.payment_type pay_type,
			// trans_sales_payments.amount pay_amount,
			// trans_sales_payments.reference pay_ref,
			// trans_sales_payments.card_type pay_card,
		$this->db->from('temp_sales');
		$this->db->join('users','temp_sales.user_id = users.id');
		$this->db->join('users as waiter','temp_sales.waiter_id = waiter.id','left');
		$this->db->join('terminals','temp_sales.terminal_id = terminals.terminal_id');
		// $this->db->join('shifts','trans_sales.shift_id = shifts.shift_id');
		$this->db->join('tables','temp_sales.table_id = tables.tbl_id','left');
		// $this->db->join('trans_sales_payments','trans_sales.sales_id = trans_sales_payments.sales_id','left');

		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
			}
		}
		if (!is_null($sales_id)){
			if (is_array($sales_id))
				$this->db->where_in('temp_sales.sales_id',$sales_id);
			else
				$this->db->where('temp_sales.sales_id',$sales_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->order_by('temp_sales.datetime',$order);
		$query = $this->db->get();
		// echo $this->db->last_query();die();
		return $query->result();
	}
	public function get_temp_sales_menus($sales_menu_id=null,$args=array()){
		$this->db->select('temp_sales_menus.*,menus.menu_code,menus.menu_name');
		$this->db->from('temp_sales_menus');
		$this->db->join('menus','temp_sales_menus.menu_id=menus.menu_id');
		if (!is_null($sales_menu_id)){
			if (is_array($sales_menu_id))
				$this->db->where_in('temp_sales_menus.sales_menu_id',$sales_menu_id);
			else
				$this->db->where('temp_sales_menus.sales_menu_id',$sales_menu_id);
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
		$this->db->order_by('temp_sales_menus.line_id asc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_temp_sales_items($sales_item_id=null,$args=array()){
		$this->db->select('temp_sales_items.*,items.code,items.name');
		$this->db->from('temp_sales_items');
		$this->db->join('items','temp_sales_items.item_id=items.item_id');
		if (!is_null($sales_item_id)){
			if (is_array($sales_item_id))
				$this->db->where_in('temp_sales_items.sales_item_id',$sales_item_id);
			else
				$this->db->where('temp_sales_items.sales_item_id',$sales_item_id);
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
		$this->db->order_by('temp_sales_items.line_id asc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_temp_sales_menu_modifiers($sales_mod_id=null,$args=array()){
		$this->db->select('temp_sales_menu_modifiers.*,modifiers.name as mod_name');
		$this->db->from('temp_sales_menu_modifiers');
		$this->db->join('modifiers','temp_sales_menu_modifiers.mod_id=modifiers.mod_id');
		if (!is_null($sales_mod_id)){
			if (is_array($sales_mod_id))
				$this->db->where_in('temp_sales_menu_modifiers.sales_mod_id',$sales_mod_id);
			else
				$this->db->where('temp_sales_menu_modifiers.sales_mod_id',$sales_mod_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
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
		$this->db->order_by('temp_sales_menu_modifiers.menu_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_temp_sales_discounts($sales_disc_id=null,$args=array()){
		$this->db->select('temp_sales_discounts.*,receipt_discounts.disc_name,receipt_discounts.fix');
		$this->db->from('temp_sales_discounts');
		$this->db->join('receipt_discounts','temp_sales_discounts.disc_id = receipt_discounts.disc_id');
		if (!is_null($sales_disc_id)){
			if (is_array($sales_disc_id))
				$this->db->where_in('temp_sales_discounts.sales_disc_id',$sales_disc_id);
			else
				$this->db->where('temp_sales_discounts.sales_disc_id',$sales_disc_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0 )
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
		$this->db->order_by('temp_sales_discounts.sales_disc_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_temp_sales_tax($sales_tax_id=null,$args=array()){
		$this->db->select('temp_sales_tax.*');
		$this->db->from('temp_sales_tax');
		if (!is_null($sales_tax_id)){
			if (is_array($sales_tax_id))
				$this->db->where_in('temp_sales_tax.sales_tax_id',$sales_tax_id);
			else
				$this->db->where('temp_sales_tax.sales_tax_id',$sales_tax_id);
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
		$this->db->order_by('temp_sales_tax.sales_tax_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_temp_sales_payments($payment_id=null,$args=array()){
		$this->db->select('temp_sales_payments.*,users.username');
		$this->db->from('temp_sales_payments');
		$this->db->join('users','temp_sales_payments.user_id = users.id');
		if (!is_null($payment_id)){
			if (is_array($payment_id))
				$this->db->where_in('temp_sales_payments.payment_id',$payment_id);
			else
				$this->db->where('temp_sales_payments.payment_id',$payment_id);
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
		$this->db->order_by('temp_sales_payments.payment_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_temp_sales_no_tax($sales_no_tax_id=null,$args=array()){
		$this->db->select('temp_sales_no_tax.*');
		$this->db->from('temp_sales_no_tax');
		if (!is_null($sales_no_tax_id)){
			if (is_array($sales_no_tax_id))
				$this->db->where_in('temp_sales_no_tax.sales_no_tax_id',$sales_no_tax_id);
			else
				$this->db->where('temp_sales_no_tax.sales_no_tax_id',$sales_no_tax_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
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
		$this->db->order_by('temp_sales_no_tax.sales_no_tax_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_temp_sales_zero_rated($sales_zero_rated_id=null,$args=array()){
		$this->db->select('temp_sales_zero_rated.*');
		$this->db->from('temp_sales_zero_rated');
		if (!is_null($sales_zero_rated_id)){
			if (is_array($sales_zero_rated_id))
				$this->db->where_in('temp_sales_zero_rated.sales_zero_rated_id',$sales_zero_rated_id);
			else
				$this->db->where('temp_sales_zero_rated.sales_zero_rated_id',$sales_zero_rated_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
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
		$this->db->order_by('temp_sales_zero_rated.sales_zero_rated_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_temp_sales_charges($sales_charge_id=null,$args=array()){
		$this->db->select('temp_sales_charges.*');
		$this->db->from('temp_sales_charges');
		if (!is_null($sales_charge_id)){
			if (is_array($sales_charge_id))
				$this->db->where_in('temp_sales_charges.sales_charge_id',$sales_charge_id);
			else
				$this->db->where('temp_sales_charges.sales_charge_id',$sales_charge_id);
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
		$this->db->order_by('temp_sales_charges.sales_charge_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_temp_sales_local_tax($sales_local_tax_id=null,$args=array()){
		$this->db->select('temp_sales_local_tax.*');
		$this->db->from('temp_sales_local_tax');
		if (!is_null($sales_local_tax_id)){
			if (is_array($sales_local_tax_id))
				$this->db->where_in('temp_sales_local_tax.sales_local_tax_id',$sales_local_tax_id);
			else
				$this->db->where('temp_sales_local_tax.sales_local_tax_id',$sales_local_tax_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
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
		$this->db->order_by('temp_sales_local_tax.sales_local_tax_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_tables_other($id=null,$type='dinein'){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('tables');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('tables.tbl_id',$id);
				}else{
					$this->db->where('tables.tbl_id',$id);
				}
			if($type != null)
				$this->db->where('tables.trans_type',$type);
				
			$this->db->order_by('tbl_id desc');
			$this->db->where('tables.inactive',0);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_occupied_tables_other($tbl_id=null,$type='dinein'){
		$this->db->trans_start();
			$this->db->select('trans_sales.table_id,tables.name,trans_sales.billed');
			$this->db->from('trans_sales');
			$this->db->join('tables','trans_sales.table_id = tables.tbl_id');
			if($tbl_id != null){
				if(is_array($tbl_id))
				{
					$this->db->where_in('table_id',$tbl_id);
				}else{
					$this->db->where('table_id',$tbl_id);
				}
			}
			$this->db->where('tables.trans_type',$type);
			$this->db->where('trans_sales.trans_ref is null','',false);
			$this->db->where('trans_sales.inactive',0);
			$this->db->group_by('table_id');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	function get_calendar_events()
	{
		// var_dump($employee_id);die();
		$this->db->select('set_time');
		$this->db->from('customers_bank');
		// $this->db->where('customer_id',$user_id);
		// $this->db->order_by('set_time desc');
		$this->db->limit('1');
		$result = $this->db->get();
		$get_info = $result->result();
		return $get_info;
	}
	function get_locations_solo($branch_code)
	{//get all locations
		$this->db->select('*');
		$this->db->from('branch_details');
		$this->db->where('inactive','0');
		// $this->db->where('location_id !=', 1);
		$this->db->where('branch_code ', $branch_code);
		$result = $this->db->get();
		$get_info = $result->result();
		// $res = $get_info[0];
		return $get_info;
	}
	function get_time_slot($date_selected="") {
		// $start = date('Y-m-d',strtotime($date_selected));
		// $last = date('Y-m-d',strtotime('+23 hour +59 minutes',strtotime($date_selected)));
		// echo "<pre>",print_r($start),"</pre>";die();

		$this->db->select('set_time,set_date');
		$this->db->from('customers_bank');
		// $this->db->join('sales_items', 'sales.sale_id = sales_items.sale_id', 'left');
		// $this->db->join('items', 'sales_items.item_id = items.item_id', 'left');	
		// $this->db->where('sales.schedule_date >=', $start);
		// $this->db->where('sales.schedule_date <=',$last); 
		// $this->db->where('items.interval_sched', $interval_sched);
		$result = $this->db->get();
		// echo $this->db->last_query();die();
		$get_info = $result->result();
		
		return $get_info;

	}

	public function get_trans_sales_reservation($sales_id=null,$args=array(),$order='desc',$joinTables=null,$group_by=null){
		$this->db->select('
			customers_bank.*,trans_sales.sales_id,trans_sales.inactive as trans_inactive,trans_sales.type as ttype,trans_sales.table_id,trans_sales.paid,`tables`.`name` AS table_name,
			customers.fname custfname,
			customers.lname custlname,
			users.username,
			sum(customers_bank.amount) as t_amount
			');
			// trans_sales.*,
			// terminals.terminal_name,
			// terminals.terminal_code,
			// tables.name as table_name,
			// waiter.username as waiterusername,
			// waiter.fname as waiterfname,
			// waiter.mname as waitermname,
			// waiter.lname as waiterlname,
			// waiter.suffix as waitersuffix,
			// customers_bank.cust_id,
			// customers_bank.set_date,
			// customers_bank.set_time,
			// customers_bank.amount cust_amount
			// trans_sales_payments.payment_type pay_type,
			// trans_sales_payments.amount pay_amount,
			// trans_sales_payments.reference pay_ref,
			// trans_sales_payments.card_type pay_card,
		$this->db->from('customers_bank');
		$this->db->join('trans_sales','trans_sales.sales_id = customers_bank.sales_id','left');
		$this->db->join('users','trans_sales.user_id = users.id','left');
		// $this->db->join('users as waiter','trans_sales.waiter_id = waiter.id','left');
		// $this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
		// $this->db->join('shifts','trans_sales.shift_id = shifts.shift_id');
		$this->db->join('tables','trans_sales.table_id = tables.tbl_id','left');
		$this->db->join('customers','customers_bank.cust_id = customers.cust_id','left');

		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
			}
		}
		if (!is_null($sales_id)){
			if (is_array($sales_id))
				$this->db->where_in('trans_sales.sales_id',$sales_id);
			else
				$this->db->where('trans_sales.sales_id',$sales_id);
		}
		$this->db->where('customers_bank.inactive !=',1);
		// $this->db->where('customers_bank.inactive =',"");
		// $this->db->where('customers_bank.inactive =',0);
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->order_by('trans_sales.datetime',$order);
		if(!empty($group_by)){
			$this->db->group_by($group_by);
		}
		$query = $this->db->get();
		// echo $this->db->last_query();die();
		return $query->result();
	}
	public function update_customer_bank($items,$cust_id,$date_schedule,$time_schedule){
		// $this->db->set('trans_sales.update_date','NOW()',FALSE);
		$this->db->where('customers_bank.cust_id',$cust_id);
		$this->db->where('customers_bank.set_date',$date_schedule);
		$this->db->where('customers_bank.set_time',$time_schedule);
		$this->db->update('customers_bank',$items);
	}
	function get_reserveDateTime($date_selected="") {

		$this->db->select('set_time,set_date');
		$this->db->from('customers_bank');
		$this->db->join('trans_sales', 'customers_bank.sales_id = trans_sales.sales_id', 'left');
		// $this->db->join('items', 'sales_items.item_id = items.item_id', 'left');	
		$this->db->where('customers_bank.set_date =', $date_selected);
		// $this->db->where('sales.schedule_date <=',$last); 
		// $this->db->where('items.interval_sched', $interval_sched);
		$result = $this->db->get();
		// echo $this->db->last_query();die();
		$get_info = $result->result();
		
		return $get_info;

	}
	public function get_occupied_rtables($tbl_id=null,$set_date=""){
		$this->db->trans_start();
			$this->db->select('trans_sales.table_id,tables.name,trans_sales.billed,set_date,set_time');
			$this->db->from('trans_sales');
			$this->db->join('tables','trans_sales.table_id = tables.tbl_id');
			$this->db->join('customers_bank','trans_sales.sales_id = customers_bank.sales_id','left');
			if($tbl_id != null){
				if(is_array($tbl_id))
				{
					$this->db->where_in('table_id',$tbl_id);
				}else{
					$this->db->where('table_id',$tbl_id);
				}
			}
			$this->db->where('trans_sales.trans_ref is null','',false);
			// $this->db->where('trans_sales.inactive',0);
			$inactives = array(0,2);
			$this->db->where_in('trans_sales.inactive',$inactives);
			// $this->db->where('customers_bank.set_date =', $set_date);
			if($set_date != ""){
				$from = $set_date." 00:00:01";
				$to = $set_date." 23:59:59";
				$this->db->where("trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'");
			}
			// $this->db->group_by('table_id');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_occupied_rtables_other($tbl_id=null,$type='dinein',$set_date=""){
		$this->db->trans_start();
			$this->db->select('trans_sales.table_id,tables.name,trans_sales.billed,set_date');
			$this->db->from('trans_sales');
			$this->db->join('tables','trans_sales.table_id = tables.tbl_id');
			$this->db->join('customers_bank','trans_sales.sales_id = customers_bank.sales_id');
			if($tbl_id != null){
				if(is_array($tbl_id))
				{
					$this->db->where_in('table_id',$tbl_id);
				}else{
					$this->db->where('table_id',$tbl_id);
				}
			}
			// $this->db->where('tables.trans_type',$type);
			$this->db->where('trans_sales.trans_ref is null','',false);
			$this->db->where('trans_sales.inactive',0);
			$this->db->where('customers_bank.set_date =', $set_date);
			$this->db->group_by('table_id');
			$query = $this->db->get();
			// echo $this->db->last_query();die();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_trans_sales_menu_submodifiers_prints($sales_submod_id=null,$args=array()){
		$this->db->select('trans_sales_menu_submodifiers.*,modifier_sub.mod_sub_id');
		$this->db->from('trans_sales_menu_submodifiers');
		$this->db->join('modifier_sub','modifier_sub.name=trans_sales_menu_submodifiers.submod_name and modifier_sub.mod_id = trans_sales_menu_submodifiers.mod_id');
		if (!is_null($sales_submod_id)){
			if (is_array($sales_submod_id))
				$this->db->where_in('trans_sales_menu_submodifiers.sales_submod_id',$sales_submod_id);
			else
				$this->db->where('trans_sales_menu_submodifiers.sales_submod_id',$sales_submod_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
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
		// $this->db->order_by('trans_sales_menu_submodifiers.menu_id desc');
		$query = $this->db->get();
		return $query->result();
	}

	//for payment methods
	public function add_trans_sales_payment_fields_batch($items)
	{
		$this->db->trans_start();
		$this->db->insert_batch('trans_sales_payment_fields',$items);
		$this->db->trans_complete();
	}

	public function get_trans_sales_payment_fields($id=null,$args=array()){
		$this->db->select('trans_sales_payment_fields.*');
		$this->db->from('trans_sales_payment_fields');
		if (!is_null($id)){
			if (is_array($id))
				$this->db->where_in('trans_sales_payment_fields.id',$id);
			else
				$this->db->where('trans_sales_payment_fields.id',$id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
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
		// $this->db->order_by('trans_sales_local_tax.sales_local_tax_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function update_customer_bank_time($items,$cust_id,$ref_no){
		// $this->db->set('trans_sales.update_date','NOW()',FALSE);
		$this->db->where('customers_bank.cust_id',$cust_id);
		$this->db->where('customers_bank.ref_no',$ref_no);
		// $this->db->where('customers_bank.set_date',$date_schedule);
		// $this->db->where('customers_bank.set_time',$time_schedule);
		$this->db->update('customers_bank',$items);
	}
	public function update_ts_dt($items,$sales_id){
		// $this->db->set('trans_sales.update_date','NOW()',FALSE);
		// $this->db->where('customers_bank.cust_id',$cust_id);
		$this->db->where('trans_sales.sales_id',$sales_id);
		// $this->db->where('customers_bank.set_date',$date_schedule);
		// $this->db->where('customers_bank.set_time',$time_schedule);
		$this->db->update('trans_sales',$items);
	}
	public function get_table_id_ts($id=null,$args=array()){
		$this->db->select('tbl_id,name');
		$this->db->from('tables');
		if (!is_null($id)){
			if (is_array($id))
				$this->db->where_not_in('tables.tbl_id',$id);
			else
				$this->db->where('tables.tbl_id',$id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0)
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
		// $this->db->order_by('trans_sales_local_tax.sales_local_tax_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function update_printer_setup($items,$id){
		$this->db->where('id',$id);
		$this->db->update('printer_setup',$items);
	}
	public function get_printer_setup(){
		$this->db->select('printer_setup.*');
		$this->db->from('printer_setup');
		$this->db->where('printer_setup.id',1);
		$query = $this->db->get();
		$res = $query->result();
		return $res[0];
	}
	public function update_customer_bank_inactive($items,$ref_no){
		// $this->db->set('trans_sales.update_date','NOW()',FALSE);
		$this->db->where('customers_bank.ref_no',$ref_no);
		$this->db->update('customers_bank',$items);
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
	function get_reservation_details($ref_no="") {

		$this->db->select('*');
		$this->db->from('customers_bank');
		$this->db->join('users', 'customers_bank.approver = users.id', 'left');
		// $this->db->join('items', 'sales_items.item_id = items.item_id', 'left');	
		$this->db->where('customers_bank.ref_no =', $ref_no);
		// $this->db->where('sales.schedule_date <=',$last); 
		// $this->db->where('items.interval_sched', $interval_sched);
		$result = $this->db->get();
		// echo $this->db->last_query();die();
		$get_info = $result->result();
		
		return $get_info;

	}
	function get_reservation_sales_id($sales_id="") {

		$this->db->select('ref_no');
		$this->db->from('customers_bank');
		// $this->db->join('users', 'customers_bank.approver = users.id', 'left');
		// $this->db->join('items', 'sales_items.item_id = items.item_id', 'left');	
		$this->db->where('customers_bank.sales_id =', $sales_id);
		// $this->db->where('sales.schedule_date <=',$last); 
		// $this->db->where('items.interval_sched', $interval_sched);
		$result = $this->db->get();
		// echo $this->db->last_query();die();
		$get_info = $result->result();
		
		return $get_info;

	}

	// public function asearch_items($key=null,$ref=0){
	// 	//$this->db->select("debtor_masters.short_desc as deb_name, count(0_views_qoh.stock_id) as total_item");
	// 	$this->db->from("customers");
	// 	if($key != null)
	// 		$this->db->like('value',$key);
	// 	if($ref != 0)
	// 		$this->db->where('is_senior',$ref);


	// 	$query = $this->db->get();
	// 	// echo $this->db->last_query();
	// 	$result = $query->result();
	// 	return $result;
	// }

	public function add_senior_cust($items)
	{
		$this->db->trans_start();
		$this->db->insert('customers',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}

	public function add_store_zread($items)
	{
		$this->db->trans_start();
		$this->db->insert('store_zread',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}

	public function get_brands($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('brands');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('brands.id',$id);
				}else{
					$this->db->where('brands.id',$id);
				}
			$this->db->where('brands.inactive',0);
			$this->db->order_by('id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_promo_free_menu($id=null,$promo_option=null,$promo_menu_id=null,$ttype=null){
		$this->db->trans_start();
			$this->db->select('promo_free_menus.*,promo_free.description,promo_free.name,promo_free.amount,promo_free.sched_id,has_menu_id,has_menu_qty,promo_option,menu_category_id,promo_free.value');
			$this->db->from('promo_free');
			$this->db->join('promo_free_menus','promo_free.pf_id = promo_free_menus.pf_id','left');
			if($id != null){
				if(is_array($id))
				{
					$this->db->like('promo_free.has_menu_id',$id);
				}else{
					$this->db->like('promo_free.has_menu_id',$id);
				}
				// $this->db->where_in('promo_free.has_menu_id',$id);
				// $this->db->or_where('menu_id',$id);
			}

			if($promo_menu_id !=null){
				$this->db->or_where('menu_id',$promo_menu_id);
			}

			if($promo_option !=null){
				$this->db->where('promo_free.promo_option',$promo_option);	
			}

			if($ttype !=null){
				// $this->db->where('promo_free.trans_type',$ttype);	
			}


			$this->db->where('promo_free.inactive',0);
			$this->db->order_by('promo_free_menus.menu_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_promo_free_menu_schedule($id=null,$day=null,$time=null){
		$this->db->trans_start();
			$this->db->select('menu_schedule_details.*');
			$this->db->from('menu_schedule_details');
			if($id != null){
				if(is_array($id))
				{
					$this->db->where_in('menu_schedule_details.menu_sched_id',$id);
				}else{
					$this->db->where('menu_schedule_details.menu_sched_id',$id);
				}
			}
			if($day != null){
				if(is_array($day))
				{
					$this->db->where_in('menu_schedule_details.day',$day);
				}else{
					$this->db->where('menu_schedule_details.day',$day);
				}
			}
			if($time != null){
				$this->db->where('menu_schedule_details.time_on <= TIME("'.$time.'")',null,false);
				$this->db->where('menu_schedule_details.time_off >= TIME("'.$time.'")',null,false);
			}
			$this->db->order_by('menu_schedule_details.id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_trans_sales_dashboard($sales_id=null,$args=array(),$order='desc',$joinTables=null,$limit=0){
		$this->db->select('
			trans_sales.*,
			users.username,
			terminals.terminal_name,
			terminals.terminal_code,
			tables.name as table_name,
			waiter.username as waiterusername,
			waiter.fname as waiterfname,
			waiter.mname as waitermname,
			waiter.lname as waiterlname,
			waiter.suffix as waitersuffix,
			cb.ref_no,
			cb.amount as depo_amount,
			c.lname,c.fname,c.mname
			');
			// trans_sales_payments.payment_type pay_type,
			// trans_sales_payments.amount pay_amount,
			// trans_sales_payments.reference pay_ref,
			// trans_sales_payments.card_type pay_card,
		$this->db->from('trans_sales');
		$this->db->join('users','trans_sales.user_id = users.id');
		$this->db->join('users as waiter','trans_sales.waiter_id = waiter.id','left');
		$this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
		// $this->db->join('shifts','trans_sales.shift_id = shifts.shift_id');
		$this->db->join('tables','trans_sales.table_id = tables.tbl_id','left');
		$this->db->join('customers_bank cb','trans_sales.sales_id = cb.sales_id','left');
		$this->db->join('customers c','cb.cust_id = c.cust_id','left');
		// $this->db->join('trans_sales_payments','trans_sales.sales_id = trans_sales_payments.sales_id','left');

		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
			}
		}
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
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->limit(TRANS_COUNT_BUTTONS,$limit);
		$this->db->order_by('trans_sales.datetime',$order);
		$query = $this->db->get();
		// echo $this->db->last_query();die();
		return $query->result();
	}

	public function update_customer_bank_deposit($items,$ref_no){
		// $this->db->set('trans_sales.update_date','NOW()',FALSE);
		$this->db->where('customers_bank.bank_id',$ref_no);
		$this->db->update('customers_bank',$items);
	}

	function get_cust_bank($ref_no="") {

		$this->db->select('*,customers_bank.datetime as cb_datetime',false);
		$this->db->from('customers_bank');
		$this->db->join('users', 'customers_bank.approver = users.id', 'left');
		$this->db->join('customers', 'customers.cust_id = customers_bank.cust_id', 'left');
		// $this->db->join('items', 'sales_items.item_id = items.item_id', 'left');	
		$this->db->where('customers_bank.bank_id =', $ref_no);
		// $this->db->where('sales.schedule_date <=',$last); 
		// $this->db->where('items.interval_sched', $interval_sched);
		$result = $this->db->get();
		// echo $this->db->last_query();die();
		$get_info = $result->result();
		
		return $get_info;

	}

	public function counter_trans_details(){
            // if(LOCALSYNC){
            //     $this->load->model('core/sync_model');
            //  }
            // echo "<pre>",print_r(sess('customer_name')),"</pre>";die();
            $this->load->model('dine/cashier_model');
            $counter = sess('counter');
            $trans_cart = sess('trans_cart');
            $trans_mod_cart = sess('trans_mod_cart');
            $trans_submod_cart = sess('trans_submod_cart');
            $trans_type_cart = sess('trans_type_cart');
            $trans_disc_cart = sess('trans_disc_cart');
            $trans_charge_cart = sess('trans_charge_cart');
            $loyalty_card = sess('loyalty_card');
            $reasons = sess('reasons');
            $item_discount = sess('item_discount');
            $trans_customer_name = (!empty(sess('customer_name'))) ? sess('customer_name') : '';
            $totals  = $this->total_trans(false,$trans_cart);
            $total_amount = $totals['total'];
            $total_gross = $totals['gross'];
            $charges = $totals['charges'];
            $local_tax = $totals['local_tax'];
            $error = null;
            $act = null;
            $sales_id = null;
            $type = null;
            $type_id = SALES_TRANS;
            $print_echo = array();
            

            $serve_no = 0;
            if(isset($trans_type_cart[0]['serve_no'])){
                $serve_no = $trans_type_cart[0]['serve_no'];
            }

            if(count($trans_cart) <= 0){
                $error = "Error! There are no items.";
            }
            else if(count($counter) <= 0){
                $error = "Error! Shift or User is invalid.";
            }
            // else if(NEED_FOOD_SERVER && !isset($counter['waiter_id']) && $counter['type'] != 'counter' && $counter['type'] != 'takeout'){
            else if(NEED_FOOD_SERVER && !isset($counter['waiter_id']) && $counter['type'] == 'dinein'){
                $error = "Select Food Server";
            }
            // else if(SERVER_NO_SETUP && $serve_no == 0 && $counter['type'] == 'counter' ){
            //     $error = "Please Select Serve No.";
            // }
            // else if(SERVER_NO_SETUP && $serve_no == 0 && $counter['type'] == 'takeout' ){
            //     $error = "Please Select Serve No.";
            // }
            else{
                if(count($trans_disc_cart) > 0){
                    foreach ($trans_disc_cart as $disc_id => $row) {
                        if(!isset($row['disc_type'])){
                            $error = "Select Discount Type. If equally Divided or All Items.";
                        }
                        else{
                            if($row['disc_type'] == "")
                                $error = "Select Discount Type. If equally Divided or All Items.";
                        }
                    }
                    if($error != null){
                        if($asJson){
                            echo json_encode(array('error'=>$error,'act'=>$act,'id'=>$sales_id,'type'=>$type));
                            return false;
                        }
                        else{
                            return array('error'=>$error,'act'=>$act,'id'=>$sales_id,'type'=>$type);
                        }
                    }
                }


                
                $type = $counter['type'];
                #save sa trans_sales
                $table = null;
                $guest = 0;
                
                $customer = null;
                if(isset($trans_type_cart[0]['table'])){
                    $table = $trans_type_cart[0]['table'];
                }
                if(isset($trans_type_cart[0]['guest'])){
                    $guest = $trans_type_cart[0]['guest'];
                }
                
                if(count($trans_disc_cart) > 0){
                    foreach ($trans_disc_cart as $disc_code => $dc) {
                        $guest = $dc['guest'];
                    }
                } 
                if(isset($trans_type_cart[0]['customer_id'])){
                    $customer = $trans_type_cart[0]['customer_id'];
                }
                if(count($loyalty_card) > 0){
                    foreach ($loyalty_card as $code => $row) {
                        $customer = $row['cust_id'];
                        // $loyalty = array(
                        //     "cust_id" => $row['cust_id'],
                        //     "code" => $row['code']
                        // );
                    }

                }
                $waiter = null;
                if(isset($counter['waiter_id'])){
                    $waiter = $counter['waiter_id'];
                }

              

               
                #save sa trans_sales_menus
                $trans_sales_menu = array();
                $trans_sales_items = array();
                $total_gross = 0;
                $alcohol = 0;

                // echo "<pre>",print_r($trans_cart),"</pre>";die();

                foreach ($trans_cart as $trans_id => $v) {

                    if($v['qty'] == 0){ //if qty is zero skip the item to resolve the splitting 0 issue @jx10292019
                        continue;
                    }
                    $remarks = $serial_key = null;
                    $nocharge = 0;
                    $rate = (isset($trans_disc_cart[$trans_id]['disc_percent'])) ?  $trans_disc_cart[$trans_id]['disc_percent'] : 0 ;
                    $percent_disc = ($v['cost'] * $v['qty']) * ($rate / 100);
                    $percent_abs = (isset($trans_disc_cart[$trans_id]['disc_absolute'])) ? $trans_disc_cart[$trans_id]['disc_absolute'] : 0;
                    $total_disc = $percent_disc + $percent_abs;

                   
                    if(isset($v['nocharge']) && $v['nocharge'] != 0){
                        $nocharge = $v['nocharge'];
                    }
                    if(isset($v['remarks']) && $v['remarks'] != ""){
                        $remarks = $v['remarks'];
                    }
                    $kitchen_slip_printed=0;
                    if(isset($v['kitchen_slip_printed']) && $v['kitchen_slip_printed'] != ""){
                        $kitchen_slip_printed = $v['kitchen_slip_printed'];
                    }
                    $free = $free_reason = null;
                    if(isset($v['free_user_id'])){
                        $free = $v['free_user_id'];
                    }

                     if(isset($v['free_reason'])){
                        $free_reason = $v['free_reason'];
                    }
                    

                    if(!isset($v['retail'])){
                        $where = array('menu_id'=>$v['menu_id']);
                        $m_det = $this->site_model->get_details($where,'menus');

                        
                    }
                    else{
                        $where = array('item_id'=>$v['menu_id']);
                        $i_det = $this->site_model->get_details($where,'items');

                        
                    }
                    $total_gross += $v['qty'] *$v['cost'];

                    $where = array('menu_id'=>$v['menu_id']);
                    $men = $this->site_model->get_details($where,'menus');

                    if($men[0]->alcohol == 1){
                        $alcohol += $v['qty'] *$v['cost'];
                    }

                }
                if(count($trans_sales_menu) > 0)
                {
                    // $trans_id = $this->cashier_model->add_trans_sales_menus($trans_sales_menu);
                    //  if(LOCALSYNC){
                    //     $this->sync_model->add_trans_sales_menus($sales_id);
                    // }
                }
                
                if(count($trans_sales_items) > 0)
                {
                    // $this->cashier_model->add_trans_sales_items($trans_sales_items);
                    // if(LOCALSYNC){
                    //     $this->sync_model->add_trans_sales_items($sales_id);
                    // }
                }
                #save sa trans_sales_menu_modifiers
                if(count($trans_mod_cart) > 0){
                    $trans_sales_menu_modifiers = array();
                    foreach ($trans_mod_cart as $trans_mod_id => $m) {
                        $kitchen_slip_printed=0;
                        if(isset($m['kitchen_slip_printed']) && $m['kitchen_slip_printed'] != ""){
                            $kitchen_slip_printed = $m['kitchen_slip_printed'];
                        }
                        if(isset($trans_cart[$m['trans_id']])){
                            //menu
                            $where = array('menu_id'=>$m['menu_id']);
                            $m_det = $this->site_model->get_details($where,'menus');

                            //mod group
                            $where = array('mod_group_id'=>$m['mod_group_id']);
                            $mg_det = $this->site_model->get_details($where,'modifier_groups');

                            //mod
                            $where = array('mod_id'=>$m['mod_id']);
                            $mod_det = $this->site_model->get_details($where,'modifiers');


                           
                            $total_gross += $m['qty'] *$m['cost'];

                            // $where = array('menu_id'=>$v['menu_id']);
                            // $men = $this->site_model->get_details($where,'menus');

                            if($m_det[0]->alcohol == 1){
                                $alcohol += $m['qty'] *$m['cost'];
                            }

                        }
                    }
                    if(count($trans_sales_menu_modifiers) > 0)
                    {
                        // $trans_id = $this->cashier_model->add_trans_sales_menu_modifiers($trans_sales_menu_modifiers);
                        // if(LOCALSYNC){
                        //     $this->sync_model->add_trans_sales_menu_modifiers($sales_id);
                        // }
                    }
                }

                #save sa trans_sales_menu_submodifiers
                if(count($trans_submod_cart) > 0){
                    $trans_sales_menu_submodifiers = array();
                    foreach ($trans_submod_cart as $trans_submod_id => $m) {
                        $kitchen_slip_printed=0;
                        if(isset($m['kitchen_slip_printed']) && $m['kitchen_slip_printed'] != ""){
                            $kitchen_slip_printed = $m['kitchen_slip_printed'];
                        }
                        if(isset($trans_cart[$m['trans_id']])){
                            //menu
                            $where = array('menu_id'=>$trans_cart[$m['trans_id']]['menu_id']);
                            $m_det = $this->site_model->get_details($where,'menus');

                            
                            $total_gross += $m['qty'] *$m['price'];

                            if($m_det[0]->alcohol == 1){
                                $alcohol += $m['qty'] *$m['cost'];
                            }
                        }
                    }
                    if(count($trans_sales_menu_modifiers) > 0)
                    {
                        // $trans_id = $this->cashier_model->add_trans_sales_menu_submodifiers($trans_sales_menu_submodifiers);
                        // if(LOCALSYNC){
                        //     $this->sync_model->add_trans_sales_menu_modifiers($sales_id);
                        // }
                    }
                }

                if(count($item_discount) > 0){
                    //for save sa trans_sales_discounts pero per item
                    $trans_sales_disc_items = array();
                    foreach($item_discount as $id => $dc){
                        $total_disc += $dc['amount'];
                        

                    }
                    
                }else{
                    #save sa trans_sales_discounts
                    $total_disc = 0;
                    if(count($trans_disc_cart) > 0){
                        $trans_sales_disc_cart = array();
                        $total = 0;
                        foreach ($trans_cart as $trans_id => $trans){
                            if(isset($trans['cost']))
                                $cost = $trans['cost'];
                            if(isset($trans['price']))
                                $cost = $trans['price'];

                            if(isset($trans['modifiers'])){
                                foreach ($trans['modifiers'] as $trans_mod_id => $mod) {
                                    if($trans_id == $mod['line_id'])
                                        $cost += $mod['price'];
                                }
                            }

                            else{
                                if(count($trans_mod_cart) > 0){
                                    foreach ($trans_mod_cart as $trans_mod_id => $mod) {
                                        if($trans_id == $mod['trans_id'])
                                            $cost += $mod['cost'];
                                    }
                                }
                            }
                            if(isset($counter['zero_rated']) && $counter['zero_rated'] == 1){
                                $rate = 1.12;
                                $cost = ($cost / $rate);
                                if(isset($zero_rated)){
                                    $zero_rated += $v['qty'] * $cost;
                                }else{
                                    $zero_rated = $v['qty'] * $cost;
                                }
                            }
                            $total += $trans['qty'] * $cost;
                        }

                        foreach ($trans_disc_cart as $disc_id => $dc) {
                            $dit = "";
                            if(isset($dc['items'])){
                                foreach ($dc['items'] as $lines) {
                                    $dit .= $lines.",";
                                }
                                if($dit != "")
                                    $dit = substr($dit,0,-1);                        
                            }
                            

                            $discount = 0;
                            $rate = $dc['disc_rate'];
                            switch ($dc['disc_type']) {
                                case "equal":
                                    
                                    if($dc['disc_code'] == 'SNDISC'){
                                        $divi = ($total-$alcohol)/$dc['guest'];
                                    }else{
                                        $divi = $total/$dc['guest'];
                                    }
                                    $divi_less = $divi;

                                    $where = array('id'=>1);
                                    $set_det = $this->site_model->get_details($where,'settings');

                                    if($counter['type'] != 'dinein' && $counter['type'] != 'mcb' && $dc['disc_code'] == 'SNDISC' && $divi > $set_det[0]->ceiling_amount && $set_det[0]->ceiling_amount > 0){
                                        $divi = $set_det[0]->ceiling_amount;
                                        $divi_less = $set_det[0]->ceiling_amount;
                                    }

                                    if($counter['type'] == 'mcb' && $dc['disc_code'] == 'SNDISC' && $divi > $set_det[0]->ceiling_mcb && $set_det[0]->ceiling_mcb > 0){
                                        $divi = $set_det[0]->ceiling_mcb;
                                        $divi_less = $set_det[0]->ceiling_mcb;
                                    }

                                    if($dc['disc_code'] == ATHLETE_CODE){
                                        // if($dc['no_tax'] == 1){
                                            $divi_less = ($divi / 1.12);
                                        // }
                                        $no_persons = count($dc['persons']);
                                        // foreach ($row['persons'] as $code => $per) {
                                        $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $divi_less);
                                        $discount = ($rate / 100) * $divi_less;
                                    }else{
                                        if($dc['no_tax'] == 1){
                                            $divi_less = ($divi / 1.12);
                                        }
                                        $no_persons = count($dc['persons']);
                                        // foreach ($row['persons'] as $code => $per) {
                                        $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $divi_less);
                                        $discount = ($rate / 100) * $divi_less;
                                    }

                                    // }
                                    // $total = ($divi * $row['guest']) - $discount;

                                    break;
                                default:
                                    // $no_citizens = count($dc['persons']);
                                    // if($dc['no_tax'] == 1)
                                    //     $total = ($total / 1.12);                     
                                    // $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $total);
                                    // $discount = ($rate / 100) * $total;
                                    if($dc['fix'] == 0){
                                        if(DISCOUNT_NET_OF_VAT && $row['disc_code'] != DISCOUNT_NET_OF_VAT_EX){
                                            $no_citizens = count($dc['persons']);
                                            $total_net_vat = ($total / 1.12);                     
                                            foreach ($dc['persons'] as $code => $per) {
                                                $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $total_net_vat);
                                                $discount += ($rate / 100) * $total_net_vat;
                                            }
                                            $total -= $discount; 
                                        }
                                        else{
                                            $no_citizens = count($dc['persons']);

                                            // echo $no_citizens; die();
                                            if($dc['disc_code'] == ATHLETE_CODE){
                                                // if($dc['no_tax'] == 1)
                                                    $total = ($total / 1.12);                     
                                                foreach ($dc['persons'] as $code => $per) {
                                                    $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $total);
                                                    $discount += ($rate / 100) * $total;
                                                }
                                            }else{
                                                if($dc['no_tax'] == 1)
                                                    $total = ($total / 1.12);                     
                                                foreach ($dc['persons'] as $code => $per) {
                                                    $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $total);
                                                    $discount += ($rate / 100) * $total;
                                                }
                                            }

                                            $total -= $discount;                                        
                                        }    
                                    }
                                    else{
                                        if($dc['openamt'] != 0){
                                            $discs[] = array('type'=>$dc['disc_code'],'amount'=>$dc['openamt']);
                                            $discount += $dc['openamt'];
                                            $total -= $discount;
                                        }else{
                                            $discs[] = array('type'=>$dc['disc_code'],'amount'=>$rate);
                                            $discount += $rate;
                                            $total -= $discount;
                                        }

                                    }
                                    // }    
                            }
                            foreach ($dc['persons'] as $pcode => $oper) {
                                
                                $total_disc += $discount;
                            }
                        }
                        
                    }

                }


                
                // save sa trans_sales_charges
                $total_charge = 0;
                if(count($trans_charge_cart) > 0){
                    $trans_sales_charge_cart = array();
                    foreach ($trans_charge_cart as $charge_id => $ch) {
                      
                        $total_charge += $charges[$charge_id]['amount'];
                    }
                    
                }

               
                // echo $total_charge;exit;

               // print_r($charges);exit;
                // echo $total_charge;exit;
                // echo "<pre>",print_r($total_charge),"</pre>";die();
                $tax = $this->get_tax_rates(false);
                $taxable_amount = $total_gross;
                $not_taxable_amount = 0;
                $zero_rated = 0;
                $diplomat_count = 0;
                $g_count = 0;
                if(count($tax) > 0){

                    if(isset($counter['zero_rated']) && $counter['zero_rated'] == 1){
                        $rate = 1.12;
                        // $cost = ($cost / $rate);
                        // $rate = 0.12;
                        $zero_rated += $total_gross / $rate;
                        $taxable_amount -= $total_gross;
                        $not_taxable_amount += $total_gross / $rate;
                    }else{
                        
                        if(count($item_discount)>0){
                            foreach ($trans_cart as $trans_id => $v) {
                                $cost = $v['cost'];
                                $total = $v['qty'] * $cost;

                                if(isset($item_discount[$trans_id])){
                                    
                                    if($item_discount[$trans_id]['disc_code'] == 'DIPLOMAT'){
                                        $zero_rated += $total / 1.12;
                                        $not_taxable_amount += $total / 1.12;
                                        $taxable_amount -= $total;
                                    }else{
                                        $no_tx =  $item_discount[$trans_id]['no_tax'];
                                        if($no_tx == 1){
                                            $not_taxable_amount += $total / 1.12;
                                            $taxable_amount -= $total;
                                            // die('ss');
                                        }else{
                                            // $with_disc = $total - $item_discount[$trans_id]['amount'];
                                            // echo $with_disc; die();
                                            $taxable_amount -= $item_discount[$trans_id]['amount'];
                                        }
                                    }

                                }
                            }
                        }else{
                            foreach ($trans_disc_cart as $disc_id => $dc) {
                                $discount = 0;
                                $rate = $dc['disc_rate'];

                                $divi = $total_gross/$dc['guest'];
                                $no_tax_persons = count($dc['persons']);
                                $g_count = $dc['guest'];
                                foreach($dc['persons'] as $name => $val){
                                    if($dc['fix'] == 0){

                                        $where = array('id'=>1);
                                        $set_det = $this->site_model->get_details($where,'settings');
                                        if($counter['type'] != 'dinein' && $counter['type'] != 'mcb' && $dc['disc_code'] == 'SNDISC' && $divi > $set_det[0]->ceiling_amount && $set_det[0]->ceiling_amount > 0){
                                            $divi = $set_det[0]->ceiling_amount;
                                            $divi_less = $set_det[0]->ceiling_amount;
                                        }

                                        if($counter['type'] == 'mcb' && $dc['disc_code'] == 'SNDISC' && $divi > $set_det[0]->ceiling_mcb && $set_det[0]->ceiling_mcb > 0){
                                            $divi = $set_det[0]->ceiling_mcb;
                                            $divi_less = $set_det[0]->ceiling_mcb;
                                        }

                                        
                                        if($dc['disc_code'] == 'DIPLOMAT'){
                                            $divi_less = ($divi / 1.12);

                                            $zero_rated += $divi_less;
                                            $not_taxable_amount += $divi_less;
                                            $taxable_amount -= $divi;
                                            $diplomat_count++;
                                        }elseif($dc['disc_code'] == ATHLETE_CODE){
                                            $divi_less = ($divi / 1.12);
                                            $disc_per_person = ($rate / 100) * $divi_less;

                                            $taxable_amount -= $disc_per_person;

                                        }else{
                                            if($dc['no_tax'] == 1){
                                                $divi_less = ($divi / 1.12);
                                                $less_vat = $divi - $divi_less;
                                                // $not_taxable_amount += $divi_less * $no_tax_persons;
                                                // $taxable_amount -= $divi + $less_vat;
                                                $disc_per_person = ($rate / 100) * $divi_less;

                                                $no_tax = $divi_less * $no_tax_persons;
                                                $taxable_amount -= $divi;
                                                // echo $not_taxable_amount.'aa';
                                                $not_taxable_amount += $divi - $less_vat;
                                                // echo $not_taxable_amount.'bb';
                                            }else{
                                                $disc_per_person = ($rate / 100) * $divi; 
                                                // $not_taxable_amount = 0;
                                                // $disc_persons = $disc_per_person * $no_tax_persons;
                                                $taxable_amount -= $disc_per_person;
                                            }
                                        }


                                    }else{
                                        // $disc_per_person = $divi - $rate;
                                        $disc_per_person = $rate;
                                        $taxable_amount -= $disc_per_person;
                                        // $taxable_amount -= $discount;
                                        // $not_taxable_amount = 0; 
                                    }
                                }

                                
                            }
                        }


                    }

                    // echo $not_taxable_amount; die();
                    //remove charges 
                   
                        if($g_count == $diplomat_count){
                            $zero_rated -= $total_disc;
                               
                        }

                    
                        if($g_count == $diplomat_count){
                            $not_taxable_amount -= $total_disc;
                            
                        }



                    // if(LOCALSYNC){
                    //     if(count($trans_sales_zero_rated) > 0)
                    //         $this->sync_model->add_trans_sales_zero_rated($sales_id);

                    //     if(count($trans_sales_no_tax) > 0)
                    //         $this->sync_model->add_trans_sales_no_tax($sales_id);
                    // }
                    if($zero_rated != 0 && $total_disc != 0){
                        if($g_count == $diplomat_count){
                            $taxable_amount = 0;
                        }
                    }

                    $am = $taxable_amount;
                    $trans_tax = 0;
                    foreach ($tax as $tax_id => $tx) {
                        $rate = ($tx['rate'] / 100);
                        $tax_value = ($am / ($rate + 1) ) * $rate;
                        // ($am / 1.12) * .12
                        $trans_tax += $tax_value;
                    }
                    
                    


                }
             


                //for update sa trans_Sales ng iba pang details
                $trans_sales_details = array(
                    "total_gross"       => $total_gross,
                    "total_discount" => $total_disc,
                    "total_charges" => $total_charge,
                    "zero_rated" => $zero_rated,
                    "no_tax" => $not_taxable_amount,
                    "tax" => $trans_tax,
                    "local_tax" => $local_tax,
                );               
                
                echo json_encode($trans_sales_details);
                
            }

          
        
    }

    public function add_combine_tables($items){
		// $this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('combine_tables',$items);
		return $this->db->insert_id();
	}

	public function delete_combine_tables($sales_id){
		$this->db->where('combine_tables.main_sales_id', $sales_id);
		$this->db->delete('combine_tables');
	}

	public function get_combined_tables($where=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('combine_tables');
			// $this->db->join('tables','trans_sales.table_id = tables.tbl_id');
			if($where != null){
				$this->db->where($where);
			}
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function update_combine_tables($items,$where){
		$this->db->where($where);
		$this->db->update('combine_tables',$items);
	}

	public function get_last_queue_id()
	{
		$this->db->select_max('queue_id','queue_id');
		$this->db->from('trans_sales');
		$this->db->where('date(datetime)=date(NOW())');		
		
		$query = $this->db->get();
		return $query->result();
	}

	function get_cust_balance($sdate, $edate,$customer_id="") {

		$this->db->select('trans_sales.*,fname,lname');
		$this->db->from('trans_sales');
		$this->db->join('customers', 'customers.cust_id = trans_sales.customer_id', 'left');
		$this->db->join('trans_sales_payments', 'trans_sales_payments.sales_id = trans_sales.sales_id', 'left');
		
		if($customer_id != ''){
			$this->db->where('cust_id',$customer_id);
		}

		// $this->db->where('total_amount != COALESCE(ar_payment_amount,0)',null,false);
		$this->db->where("trans_sales.datetime >=", $sdate);		
		$this->db->where("trans_sales.datetime <", $edate);
		$this->db->where('type_id',10);
		$this->db->where('trans_sales.inactive', 0);
		$this->db->where("payment_type in('arclearingbilling','arclearingpromo')");	
		
		$result = $this->db->get();
		// echo $this->db->last_query();die();
		$get_info = $result->result();
		
		return $get_info;

	}

}