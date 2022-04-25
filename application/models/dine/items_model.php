<?php
class Items_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
		// if(LOCALSYNC){
  //           $this->load->model('core/sync_model');
  //       }
	}
	public function get_item($item_id=null,$args=array())
	{
		$this->db->select('
			items.*,
			categories.name as category,
			subcategories.name as subcategory,
			item_types.type as item_type,
			suppliers.name as supplier
			');
		$this->db->from('items');
		$this->db->join('categories','items.cat_id = categories.cat_id');
		$this->db->join('subcategories','subcategories.sub_cat_id = items.subcat_id','left');
		$this->db->join('item_types','items.type = item_types.id');
		$this->db->join('suppliers','items.supplier_id = suppliers.supplier_id','left');
		if (!is_null($item_id)) {
			if (is_array($item_id))
				$this->db->where_in('items.item_id',$item_id);
			else
				$this->db->where('items.item_id',$item_id);
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
		$this->db->order_by('items.name ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_customers($item_id=null,$args=array())
	{
		$this->db->select('customers.*');
		$this->db->from('customers');
		if (!is_null($item_id)) {
			if (is_array($item_id))
				$this->db->where_in('customers.id',$item_id);
			else
				$this->db->where('customers.id',$item_id);
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
		$this->db->order_by('customers.fname ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_item_brief($item_id=null)
	{
		$this->db->select('
				items.item_id,items.barcode,items.code,items.name,items.uom
			');
		$this->db->from('items');
		if (!is_null($item_id)) {
			if (is_array($item_id))
				$this->db->where_in('items.item_id',$item_id);
			else
				$this->db->where('items.item_id',$item_id);
		}
		$this->db->order_by('items.name ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_item($items)
	{
		$this->db->set('reg_date','NOW()',FALSE);
		$this->db->insert('items',$items);
		return $this->db->insert_id();
	}
	public function update_item($items,$item_id)
	{
		$this->db->set('update_date','NOW()',FALSE);
		$this->db->where('item_id',$item_id);
		$this->db->update('items',$items);
	}
	public function get_latest_item_move($constraints=array())
	{
		$this->db->select('*');
		$this->db->from('item_moves');
		if (!empty($constraints))
			$this->db->where($constraints);
		$this->db->order_by('reg_date DESC, move_id DESC');
		$query = $this->db->get();
		$row = $query->row();
		$query->free_result();
		return $row;
	}
	public function get_last_item_qty($loc_id=null,$item_id=null){
		$this->db->select('curr_item_qty,item_id,loc_id');
		$this->db->from('item_moves');
		if($loc_id != null){
			$this->db->where('item_moves.loc_id',$loc_id);
		}
		if (!is_null($item_id)) {
			$this->db->where('item_moves.item_id',$item_id);
		}
		$this->db->order_by('reg_date DESC, move_id DESC');
		$this->db->limit(1);
		$query = $this->db->get();
		$row = $query->row();
		$query->free_result();
		return $row;
	}
	public function move_items($loc_id,$items,$opts=array()){
		#items must be an array with qty and UOM
		$batch = array();
		foreach ($items as $item_id => $opt) {
			$last = $this->get_last_item_qty($loc_id,$item_id);
			$curr_qty = 0;
			if(count($last) > 0){
				$curr_qty = $last->curr_item_qty;
			}
			$opts['item_id'] = $item_id;
			$opts['qty'] = $opt['qty'];
			if(isset($opt['case_qty']))
				$opts['case_qty'] = $opt['case_qty'];
			if(isset($opt['pack_qty']))
				$opts['pack_qty'] = $opt['pack_qty'];

			$opts['uom'] = $opt['uom'];
			$opts['loc_id'] = $loc_id;
			$opts['curr_item_qty'] = $curr_qty + $opt['qty'];
			$now = $this->site_model->get_db_now();
			$datetime = date2SqlDateTime($now);
			$opts['reg_date'] = $datetime;
			$batch[] = $opts;
		}
		$this->add_item_moves_batch($batch);

		// if(LOCALSYNC){
  //           $this->sync_model->add_item_moves_batch($loc_id);
  //       }
		// echo var_dump($batch);
	}
	public function add_item_moves_batch($items)
	{
		$this->db->trans_start();
		$this->db->insert_batch('item_moves',$items);
		$this->db->trans_complete();
	}
	public function get_curr_item_inv_and_locs()
	{
		$prepare = '
			SELECT
				GROUP_CONCAT(
					\'SUM(IF(item_moves.loc_id =\',
					locations.loc_id,
					\', qty, NULL)) as "!!Loc-\',
					locations.loc_name, \'"\'
				) as msql
			FROM locations;
			';
		$query = $this->db->query($prepare);
		$query = $query->result();

		$prepped = $query[0]->msql;

		if (empty($prepped))
			return null;

		$sql = '
			SELECT
				item_moves.item_id,
				items.code,
				items.name,
				items.uom,
				SUM(item_moves.qty) as qoh,
				locations.loc_name,
				categories.name as cat_name,
				subcategories.name as sub_cat_name
			FROM item_moves
			JOIN items ON item_moves.item_id = items.item_id
			LEFT JOIN locations ON item_moves.loc_id = locations.loc_id
			LEFT JOIN categories ON categories.cat_id = items.cat_id
			LEFT JOIN subcategories ON subcategories.sub_cat_id = items.subcat_id
			WHERE item_moves.inactive = "0"
			GROUP BY item_moves.item_id;
		';
		$r_query = $this->db->query($sql);

		return $r_query;
	}

	public function get_inventory_moves($date=null)
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
			GROUP BY item_moves.item_id
		';
			// WHERE DATE(item_moves.reg_date) = \''.date('Y-m-d',strtotime($date)).'\'

		$r_query = $this->db->query($sql);
		return $r_query;
	}

	public function add_menu_moves_batch($items)
	{
		$this->db->trans_start();
		$this->db->insert_batch('menu_moves',$items);
		$this->db->trans_complete();
	}

	public function get_menu_moves_total($item_id=null,$args=array())
	{
		$this->db->select('
			sum(menu_moves.qty) as in_menu_qty
			');
		$this->db->from('menu_moves');
		if (!is_null($item_id)) {
			if (is_array($item_id))
				$this->db->where_in('menu_moves.item_id',$item_id);
			else
				$this->db->where('menu_moves.item_id',$item_id);
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
		$this->db->group_by('menu_moves.item_id');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_item_moves_total($item_id=null,$args=array())
	{
		$this->db->select('
			sum(item_moves.qty) as in_item_qty
			');
		$this->db->from('item_moves');
		if (!is_null($item_id)) {
			if (is_array($item_id))
				$this->db->where_in('item_moves.item_id',$item_id);
			else
				$this->db->where('item_moves.item_id',$item_id);
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
		$this->db->group_by('item_moves.item_id');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_locations($loc_id=null,$args=array())
	{
		$this->db->select('locations.*');
		$this->db->from('locations');
		if (!is_null($loc_id)) {
			if (is_array($loc_id))
				$this->db->where_in('loc_id.id',$loc_id);
			else
				$this->db->where('loc_id.id',$loc_id);
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
		$this->db->order_by('locations.loc_id ASC');
		$query = $this->db->get();
		return $query->result();
	}
 	public function get_supplier($supplier_code=null){
		$this->db->trans_start();
			$this->db->select('supplier_id,supplier_code');
			$this->db->from('suppliers');
			if($supplier_code){
				$this->db->where("supplier_code",$supplier_code);
			}
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		if(isset($result[0])){
			return $result[0]->supplier_id;
		}else{
			return 0;
		}
	}
	public function get_mod_inv_moves_total($item_id=null,$args=array())
	{
		$this->db->select('
			sum(trans_sales_menu_modifiers.qty) as in_menu_qty
			');
		$this->db->from('trans_sales_menu_modifiers');
		if (!is_null($item_id)) {
			if (is_array($item_id))
				$this->db->where_in('trans_sales_menu_modifiers.menu_id',$item_id);
			else
				$this->db->where('trans_sales_menu_modifiers.menu_id',$item_id);
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
		$this->db->group_by('trans_sales_menu_modifiers.menu_id');
		$query = $this->db->get();
		return $query->result();
	}

}