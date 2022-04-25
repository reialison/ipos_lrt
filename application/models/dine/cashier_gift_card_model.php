<?php
class Cashier_Gift_Card_model extends CI_Model{

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
	public function get_trans_gc($gc_id=null,$args=array(),$order='desc',$joinTables=null){
		$this->db->select('
			trans_gc.*,
			users.username,
			terminals.terminal_name,
			terminals.terminal_code,
			tables.name as table_name,
			waiter.username as waiterusername,
			waiter.fname as waiterfname,
			waiter.mname as waitermname,
			waiter.lname as waiterlname,
			waiter.suffix as waitersuffix,
			"" as ref_no,
			"" as depo_amount,
			"" as lname,"" as fname, "" as mname
			',false);


		// gc
		 /*,
			cb.ref_no,
			cb.amount as depo_amount,
			c.lname,c.fname,c.mname*/
		// gc end
			// trans_sales_payments.payment_type pay_type,
			// trans_sales_payments.amount pay_amount,
			// trans_sales_payments.reference pay_ref,
			// trans_sales_payments.card_type pay_card,
		$this->db->from('trans_gc');
		$this->db->join('users','trans_gc.user_id = users.id');
		$this->db->join('users as waiter','trans_gc.waiter_id = waiter.id','left');
		$this->db->join('terminals','trans_gc.terminal_id = terminals.terminal_id');
		// $this->db->join('shifts','trans_sales.shift_id = shifts.shift_id');
		$this->db->join('tables','trans_gc.table_id = tables.tbl_id','left');
// -- gc
		// $this->db->join('customers_bank cb','trans_gc.gc_id = cb.sales_id','left');
		// $this->db->join('customers c','cb.cust_id = c.cust_id','left');
// -- gc end
		// $this->db->join('trans_sales_payments','trans_sales.sales_id = trans_sales_payments.sales_id','left');

		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
			}
		}
		if (!is_null($gc_id)){
			if (is_array($gc_id))
				$this->db->where_in('trans_gc.gc_id',$gc_id);
			else
				$this->db->where('trans_gc.gc_id',$gc_id);
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
		$this->db->order_by('trans_gc.datetime',$order);
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
		$this->db->join('trans_sales','trans_sales_payments.sales_id = trans_sales.sales_id and trans_sales_payments.pos_id = trans_sales.pos_id');
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
	public function add_trans_gc($items){
		// $this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('trans_gc',$items);
		return $this->db->insert_id();
	}
	public function update_trans_gc($items,$sales_id){
		$this->db->set('trans_gc.update_date','NOW()',FALSE);
		$this->db->where('trans_gc.gc_id',$sales_id);
		$this->db->update('trans_gc',$items);
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
	public function get_trans_gc_gift_cards($gc_gift_card_id=null,$args=array()){
		$this->db->select('trans_gc_gift_cards.*');
		$this->db->from('trans_gc_gift_cards');
		if (!is_null($gc_gift_card_id)){
			if (is_array($gc_gift_card_id))
				$this->db->where_in('trans_gc_gift_cards.gc_gift_card_id',$gc_gift_card_id);
			else
				$this->db->where('trans_gc_gift_cards.gc_gift_card_id',$gc_gift_card_id);
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
		$this->db->order_by('trans_gc_gift_cards.line_id asc');
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
	public function add_trans_gc_gift_cards($items){
		$this->db->insert_batch('trans_gc_gift_cards',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_gc_menus($gc_id){
		$this->db->where('trans_gc_menus.gc_id', $gc_id);
		$this->db->delete('trans_gc_menus');
	}
	public function add_trans_sales_items($items){
		$this->db->insert_batch('trans_sales_items',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_items($gc_id){
		$this->db->where('trans_gc_items.gc_id', $gc_id);
		$this->db->delete('trans_gc_items');
	}
	public function delete_trans_gc_gift_cards($gc_id){
		$this->db->where('trans_gc_gift_cards.gc_id', $gc_id);
		$this->db->delete('trans_gc_gift_cards');
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
	public function get_trans_gc_discounts($gc_disc_id=null,$args=array()){
		$this->db->select('trans_gc_discounts.*,receipt_discounts.disc_name,receipt_discounts.fix');
		$this->db->from('trans_gc_discounts');
		$this->db->join('receipt_discounts','trans_gc_discounts.disc_id = receipt_discounts.disc_id');
		if (!is_null($gc_disc_id)){
			if (is_array($gc_disc_id))
				$this->db->where_in('trans_gc_discounts.gc_disc_id',$gc_disc_id);
			else
				$this->db->where('trans_gc_discounts.gc_disc_id',$gc_disc_id);
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
		$this->db->order_by('trans_gc_discounts.gc_disc_id desc');
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
	public function add_trans_gc_discounts($items){
		$this->db->insert_batch('trans_gc_discounts',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_gc_discounts($gc_id){
		$this->db->where('trans_gc_discounts.gc_id', $gc_id);
		$this->db->delete('trans_gc_discounts');
	}
	public function delete_trans_sales_menu_modifiers($sales_id){
		$this->db->where('trans_sales_menu_modifiers.sales_id', $sales_id);
		$this->db->delete('trans_sales_menu_modifiers');
	}
	public function delete_trans_sales_menu_submodifiers($sales_id){
		$this->db->where('trans_sales_menu_submodifiers.sales_id', $sales_id);
		$this->db->delete('trans_sales_menu_submodifiers');
	}
	public function get_trans_gc_charges($gc_charge_id=null,$args=array()){
		$this->db->select('trans_gc_charges.*');
		$this->db->from('trans_gc_charges');
		if (!is_null($gc_charge_id)){
			if (is_array($gc_charge_id))
				$this->db->where_in('trans_gc_charges.gc_charge_id',$gc_charge_id);
			else
				$this->db->where('trans_gc_charges.gc_charge_id',$gc_charge_id);
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
		$this->db->order_by('trans_gc_charges.gc_charge_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_gc_charges($items){
		$this->db->insert_batch('trans_gc_charges',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_gc_charges($gc_id){
		$this->db->where('trans_gc_charges.gc_id', $gc_id);
		$this->db->delete('trans_gc_charges');
	}
	public function get_trans_gc_payments($gc_payment_id=null,$args=array()){
		$this->db->select('trans_gc_payments.*,users.username');
		$this->db->from('trans_gc_payments');
		$this->db->join('users','trans_gc_payments.user_id = users.id');
		if (!is_null($gc_payment_id)){
			if (is_array($gc_payment_id))
				$this->db->where_in('trans_gc_payments.gc_payment_id',$gc_payment_id);
			else
				$this->db->where('trans_gc_payments.gc_payment_id',$gc_payment_id);
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
		$this->db->order_by('trans_gc_payments.gc_payment_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_gc_payments($items){
		$this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('trans_gc_payments',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_payments($payment_id){
		$this->db->where('trans_sales_payments.payment_id', $payment_id);
		$this->db->delete('trans_sales_payments');
	}
	public function get_trans_gc_tax($gc_tax_id=null,$args=array()){
		$this->db->select('trans_gc_tax.*');
		$this->db->from('trans_gc_tax');
		if (!is_null($gc_tax_id)){
			if (is_array($gc_tax_id))
				$this->db->where_in('trans_gc_tax.gc_tax_id',$gc_tax_id);
			else
				$this->db->where('trans_gc_tax.gc_tax_id',$gc_tax_id);
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
		$this->db->order_by('trans_gc_tax.gc_tax_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_gc_tax($items){
		$this->db->insert_batch('trans_gc_tax',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_gc_tax($gc_id){
		$this->db->where('trans_gc_tax.gc_id', $gc_id);
		$this->db->delete('trans_gc_tax');
	}
	public function get_trans_gc_no_tax($gc_no_tax_id=null,$args=array()){
		$this->db->select('trans_gc_no_tax.*');
		$this->db->from('trans_gc_no_tax');
		if (!is_null($gc_no_tax_id)){
			if (is_array($gc_no_tax_id))
				$this->db->where_in('trans_gc_no_tax.gc_no_tax_id',$gc_no_tax_id);
			else
				$this->db->where('trans_gc_no_tax.gc_no_tax_id',$gc_no_tax_id);
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
		$this->db->order_by('trans_gc_no_tax.gc_no_tax_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_trans_gc_zero_rated($gc_zero_rated_id=null,$args=array()){
		$this->db->select('trans_gc_zero_rated.*');
		$this->db->from('trans_gc_zero_rated');
		if (!is_null($gc_zero_rated_id)){
			if (is_array($gc_zero_rated_id))
				$this->db->where_in('trans_gc_zero_rated.gc_zero_rated_id',$gc_zero_rated_id);
			else
				$this->db->where('trans_gc_zero_rated.gc_zero_rated_id',$gc_zero_rated_id);
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
		$this->db->order_by('trans_gc_zero_rated.gc_zero_rated_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_trans_gc_local_tax($gc_local_tax_id=null,$args=array()){
		$this->db->select('trans_gc_local_tax.*');
		$this->db->from('trans_gc_local_tax');
		if (!is_null($gc_local_tax_id)){
			if (is_array($gc_local_tax_id))
				$this->db->where_in('trans_gc_local_tax.gc_local_tax_id',$gc_local_tax_id);
			else
				$this->db->where('trans_gc_local_tax.gc_local_tax_id',$gc_local_tax_id);
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
		$this->db->order_by('trans_gc_local_tax.gc_local_tax_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_gc_no_tax($items){
		$this->db->insert_batch('trans_gc_no_tax',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_gc_no_tax($gc_id){
		$this->db->where('trans_gc_no_tax.gc_id', $gc_id);
		$this->db->delete('trans_gc_no_tax');
	}
	public function add_trans_gc_zero_rated($items){
		$this->db->insert_batch('trans_gc_zero_rated',$items);
		return $this->db->insert_id();
	}
	public function add_trans_gc_local_tax($items){
		$this->db->insert_batch('trans_gc_local_tax',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_gc_zero_rated($gc_id){
		$this->db->where('trans_gc_zero_rated.gc_id', $gc_id);
		$this->db->delete('trans_gc_zero_rated');
	}
	public function delete_trans_gc_local_tax($gc_id){
		$this->db->where('trans_gc_local_tax.gc_id', $gc_id);
		$this->db->delete('trans_gc_local_tax');
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
			customers_bank.*,trans_sales.sales_id,trans_sales.inactive,trans_sales.type as ttype,trans_sales.table_id,trans_sales.paid,`tables`.`name` AS table_name,
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
			$this->db->where('trans_sales.inactive',0);
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
	public function add_trans_gc_payment_fields_batch($items)
	{
		$this->db->trans_start();
		$this->db->insert_batch('trans_gc_payment_fields',$items);
		$this->db->trans_complete();
	}

	public function get_trans_gc_payment_fields($id=null,$args=array()){
		$this->db->select('trans_gc_payment_fields.*');
		$this->db->from('trans_gc_payment_fields');
		if (!is_null($id)){
			if (is_array($id))
				$this->db->where_in('trans_gc_payment_fields.id',$id);
			else
				$this->db->where('trans_gc_payment_fields.id',$id);
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

}