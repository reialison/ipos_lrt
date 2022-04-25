<?php
class Menu_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_menus($id=null,$cat_id=null,$notAll=false,$search=null,$page=0){
		$this->db->trans_start();
			$this->db->select('menus.*,menu_categories.menu_cat_name as category_name,menu_schedules.desc as menu_schedule_name, img_path');
			$this->db->from('menus');
			$this->db->join('menu_categories','menus.menu_cat_id = menu_categories.menu_cat_id');
			$this->db->join('menu_schedules','menus.menu_sched_id = menu_schedules.menu_sched_id','left');
			$this->db->join('images','menus.menu_id = images.img_ref_id and images.img_tbl = "menus"','left');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menus.menu_id',$id);
				}else{
					$this->db->where('menus.menu_id',$id);
				}
			if($cat_id != null){
				$this->db->where('menus.menu_cat_id',$cat_id);
			}
			if($notAll){
				$this->db->where('menus.inactive',0);
			}
			if($search != null){
				$this->db->like('menu_short_desc', $search);
				$this->db->or_like('menu_name', $search); 
			}
			if($page != 0){
				if($page == 1){
	                $this->db->limit(MENU_COUNT_BUTTONS,0);
	            }else{
	                $limit = ($page - 1) * MENU_COUNT_BUTTONS;
	                $this->db->limit(MENU_COUNT_BUTTONS,$limit);
	                // $limit = ($cat_page - 1) * 28;
	            }
			}
			$this->db->order_by('menus.menu_name asc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_menus($items,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('menus',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_menus($user,$id,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->where('menu_id', $id);
		$this->db->update('menus', $user);

		return $this->db->last_query();
	}
	public function get_menu_categories($id=null,$notAll=false,$brand=1,$page=0){

		// die($limit.'sss');

		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('menu_categories');
			// $this->db->join('menus','menus.menu_cat_id=menu_categories.menu_cat_id');
			$this->db->where('menu_categories.brand',$brand);
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_categories.menu_cat_id',$id);
				}else{
					$this->db->where('menu_categories.menu_cat_id',$id);
				}
			if($notAll){
				$this->db->where('menu_categories.inactive',0);
			}
			$this->db->order_by('menu_categories.menu_cat_name asc');
			if($page != 0){
				if($page == 1){
	                $this->db->limit(MENU_COUNT_BUTTONS,0);
	            }else{
	                $limit = ($page - 1) * MENU_COUNT_BUTTONS;
	                $this->db->limit(MENU_COUNT_BUTTONS,$limit);
	                // $limit = ($cat_page - 1) * 28;
	            }
			}
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_menu_categories2($id=null,$notAll=false,$brand=1,$page=0){

		// die($limit.'sss');

		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('menu_categories');
			// $this->db->join('menus','menus.menu_cat_id=menu_categories.menu_cat_id');
			// $this->db->where('menu_categories.brand',$brand);
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_categories.menu_cat_id',$id);
				}else{
					$this->db->where('menu_categories.menu_cat_id',$id);
				}
			if($notAll){
				$this->db->where('menu_categories.inactive',0);
			}
			$this->db->order_by('menu_categories.menu_cat_name asc');
			if($page != 0){
				if($page == 1){
	                $this->db->limit(MENU_COUNT_BUTTONS,0);
	            }else{
	                $limit = ($page - 1) * MENU_COUNT_BUTTONS;
	                $this->db->limit(MENU_COUNT_BUTTONS,$limit);
	                // $limit = ($cat_page - 1) * 28;
	            }
			}
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_menu_categories_unli($id=null,$notAll=false,$brand=1){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('menu_categories');
			// $this->db->join('menus','menus.menu_cat_id=menu_categories.menu_cat_id');
			$this->db->where('menu_categories.brand',$brand);
			$this->db->where('menu_categories.unli',1);
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_categories.menu_cat_id',$id);
				}else{
					$this->db->where('menu_categories.menu_cat_id',$id);
				}
			if($notAll){
				$this->db->where('menu_categories.inactive',0);
			}
			$this->db->order_by('menu_categories.menu_cat_name asc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_menu_categories($items,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('menu_categories',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_menu_categories($user,$id,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->where('menu_cat_id', $id);
		$this->db->update('menu_categories', $user);

		return $this->db->last_query();
	}
	public function get_menu_subcategories($id=null,$notAll=false){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('menu_subcategories');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_subcategories.menu_sub_cat_id',$id);
				}else{
					$this->db->where('menu_subcategories.menu_sub_cat_id',$id);
				}
			if($notAll){
				$this->db->where('menu_subcategories.inactive',0);
			}
			$this->db->order_by('menu_sub_cat_id asc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_menu_subcategories($items,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('menu_subcategories',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_menu_subcategories($user,$id,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->where('menu_sub_cat_id', $id);
		$this->db->update('menu_subcategories', $user);

		return $this->db->last_query();
	}
	public function get_menu_schedules($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('menu_schedules');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_schedules.menu_sched_id',$id);
				}else{
					$this->db->where('menu_schedules.menu_sched_id',$id);
				}
			$this->db->order_by('menu_sched_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_menu_schedules($items){
		$this->db->insert('menu_schedules',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_menu_schedules($item,$id){
		$this->db->where('menu_sched_id', $id);
		$this->db->update('menu_schedules', $item);

		return $this->db->last_query();
	}
	public function add_menu_schedule_details($items){
		$this->db->insert('menu_schedule_details',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_menu_schedule_details($item,$id){
		$this->db->where('id', $id);
		$this->db->update('menu_schedule_details', $item);

		return $this->db->last_query();
	}
	public function get_menu_schedule_details($id){
		$this->db->from('menu_schedule_details');
		// if($id != '')
			$this->db->where('menu_sched_id',$id);
		$query = $this->db->get();
		$result = $query->result();

		return $result;
	}
	public function validate_menu_schedule_details($id,$day){
		$this->db->from('menu_schedule_details');
		$this->db->where('menu_sched_id',$id);
		$this->db->where('day',$day);

		// $query = $this->db->get();
		// $result = $query->result();
		return $this->db->count_all_results();
	}
	public function delete_menu_schedule_details($id){
		$this->db->where('id', $id);
		$this->db->delete('menu_schedule_details');
	}
	// public function get_recipe_items($menu_id=null,$item_id=null,$id=null){
	// 	$this->db->trans_start();
	// 		$this->db->select('menu_recipe.*,menus.menu_name as item_name,menus.cost as item_cost');
	// 		$this->db->from('menu_recipe');
	// 		$this->db->join('menus','menu_recipe.menu_id=menus.menu_id');
	// 		$this->db->join('items','items.item_id=menus.menu_id');
	// 		if($id != null)
	// 			if(is_array($id))
	// 			{
	// 				$this->db->where_in('menu_recipe.recipe_id',$id);
	// 			}else{
	// 				$this->db->where('menu_recipe.recipe_id',$id);
	// 			}
	// 		if($menu_id != null)
	// 			$this->db->where_in('menu_recipe.menu_id',$menu_id);
	// 		if($item_id != null)
	// 			$this->db->where_in('menu_recipe.item_id',$item_id);
	// 		$this->db->order_by('recipe_id desc');
	// 		$query = $this->db->get();
	// 		$result = $query->result();
	// 	$this->db->trans_complete();
	// 	return $result;
	// }
	public function get_recipe_items($menu_id,$item_id = null,$id=null)
	{
		$this->db->select('
			menus.menu_code,
			menus.menu_barcode,
			menus.menu_name,
			menus.cost "menu_cost",
			items.item_id,
			items.name "item_name",
			items.barcode "item_barcode",
			items.code "item_code",
			items.cost "item_cost",
			items.costing "item_costing",
			menu_recipe.recipe_id,
			menu_recipe.uom,
			menu_recipe.qty,
			menu_recipe.menu_id
			');
		$this->db->from('menu_recipe');
		$this->db->join('menus','menu_recipe.menu_id = menus.menu_id');
		$this->db->join('items','menu_recipe.item_id = items.item_id');

		if (is_array($menu_id))
			$this->db->where_in('menu_recipe.menu_id',$menu_id);
		else
			$this->db->where('menu_recipe.menu_id',$menu_id);

		if(!is_null($id)) {
			if(is_array($id))
				$this->db->where_in('menu_recipe.recipe_id',$id);
			else
				$this->db->where('menu_recipe.recipe_id',$id);
		}

		if (!is_null($item_id))
			$this->db->where('menu_recipe.item_id',$item_id);

		$this->db->order_by('menus.menu_name ASC, items.name ASC');
		$query = $this->db->get();

		return $query->result();
	}
	// public function add_recipe_item($items){
	// 	$this->db->insert('menu_recipe',$items);
	// 	$x=$this->db->insert_id();
	// 	return $x;
	// }
	public function add_recipe_item($items)
	{
		$this->db->trans_start();
		$this->db->insert('menu_recipe',$items);
		$this->db->trans_complete();
		$id = $this->db->insert_id();
		return $id;
	}
	// public function update_recipe_item($menu_id=null,$item_id=null){
	// 	$this->db->where('menu_id', $menu_id);
	// 	$this->db->where('item_id', $item_id);
	// 	$this->db->update('menu_recipe', $item);

	// 	return $this->db->last_query();
	// }
	public function update_recipe_item($items,$menu_id,$item_id)
	{
		$this->db->trans_start();
		$this->db->where(array('menu_id'=>$menu_id,'item_id'=>$item_id));
		$this->db->update('menu_recipe',$items);
		$this->db->trans_complete();
	}
	// public function remove_recipe_item($id){
	// 	$this->db->where('recipe_id', $id);
	// 	$this->db->delete('menu_recipe');
	// }
	public function remove_recipe_item($recipe_id)
	{
		$this->db->trans_start();
		$this->db->where('recipe_id',$recipe_id);
		$this->db->delete('menu_recipe');
		$this->db->trans_complete();
	}
	// public function search_items($search=""){
	// 	$this->db->trans_start();
	// 		$this->db->select('items.item_id,items.code,items.barcode,items.name');
	// 		$this->db->from('items');
	// 		if($search != ""){
	// 			$this->db->like('items.name', $search);
	// 			$this->db->or_like('items.code', $search);
	// 			$this->db->or_like('items.barcode', $search);
	// 		}
	// 		$this->db->order_by('items.name');
	// 		$query = $this->db->get();
	// 		$result = $query->result();
	// 	$this->db->trans_complete();
	// 	return $result;
	// }
	public function search_items($search=""){
		$this->db->trans_start();
			$this->db->select('items.item_id,items.code,items.barcode,items.name');
			$this->db->from('items');
			if($search != ""){
				$this->db->like('items.name', $search);
				$this->db->or_like('items.code', $search);
				$this->db->or_like('items.barcode', $search);
			}
			$this->db->order_by('items.name');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	/********	 	Menu Modifiers 		********/
	public function get_menu_modifiers($menu_id=null,$mod_group_id=null,$id=null){
			$this->db->select('menu_modifiers.*,modifier_groups.name as mod_group_name,modifier_groups.mandatory,modifier_groups.multiple,modifier_groups.min_no');			$this->db->from('menu_modifiers');
			$this->db->join('modifier_groups','menu_modifiers.mod_group_id=modifier_groups.mod_group_id');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_modifiers.id',$id);
				}else{
					$this->db->where('menu_modifiers.id',$id);
				}
			if($menu_id != null)
				$this->db->where_in('menu_modifiers.menu_id',$menu_id);
			if($mod_group_id != null)
				$this->db->where_in('menu_modifiers.mod_group_id',$mod_group_id);
			$this->db->order_by('id desc');
			$query = $this->db->get();
			$result = $query->result();
		return $result;
	}
	public function get_modifier_groups($constraints = null)
	{
		$this->db->from('modifier_groups');
		if (!empty($constraints))
			$this->db->where($constraints);
		$this->db->order_by('name ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function search_modifier_groups($search="")
	{
		$this->db->from('modifier_groups');
		if ($search != "")
			$this->db->like('name',$search);
		$query = $this->db->get();
		return $query->result();
	}
	public function add_menu_modifier($items,$terminal=null)
	{
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->trans_start();
		$this->db->insert('menu_modifiers',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}
	public function remove_menu_modifier($id,$terminal=null)
	{
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->trans_start();
		$this->db->where('id',$id);
		$this->db->delete('menu_modifiers');
		$this->db->trans_complete();
	}
	/********	 End of	Menu Modifiers 	********/
	public function get_cat_sales_rep($sdate, $edate, $menu_cat_id,$brand= "")
	{
		$this->db->select("mc.menu_cat_name, sum(tsm.qty) as qty, sum(tsm.qty*tsm.price/1.12)+COALESCE(sum(modprice/1.12),0)+COALESCE(sum(submodprice/1.12),0) as vat_sales, sum(tsm.qty*tsm.price/1.12*.12) + COALESCE(sum(modprice/1.12*.12),0) + COALESCE(sum(submodprice/1.12*.12),0) as vat, sum(tsm.qty*tsm.price) + COALESCE(sum(modprice),0) + COALESCE(sum(submodprice),0) as gross, sum(tsm.qty*menu.costing) as cost,menu.brand,tsm.menu_name",false);
		$this->db->from("trans_sales_menus tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id and ts.pos_id = tsm.pos_id");

		$this->db->join("(select sum(tsm.qty * tsm.price) modprice,tsm.sales_id,menu_id, tsm.pos_id, sum(tssubmod.qty * tssubmod.price) submodprice from trans_sales_menu_modifiers tsm
			left join trans_sales_menu_submodifiers tssubmod on tsm.mod_line_id = tssubmod.mod_line_id and tsm.sales_id = tssubmod.sales_id and tsm.pos_id = tssubmod.pos_id
			group by sales_id,menu_id, tsm.pos_id) tsmod", "tsmod.sales_id = tsm.sales_id and tsmod.menu_id = tsm.menu_id",'left');

		$this->db->join("menus menu", "menu.menu_id = tsm.menu_id");		
		$this->db->join("menu_categories mc", "mc.menu_cat_id = menu.menu_cat_id");		
		if($menu_cat_id != "")
		{
			$this->db->where("mc.menu_cat_id", $menu_cat_id);					
		}
		if($brand != "")
		{
			$this->db->where("menu.brand", $brand);					
		}
		$this->db->where("ts.datetime >=", $sdate);		
		$this->db->where("ts.datetime <", $edate);
		$this->db->where("ts.type_id", 10);
		$this->db->where("ts.trans_ref is not null");
 		$this->db->where("ts.inactive", 0);
 		$this->db->where("ts.terminal_id", TERMINAL_ID);
 		if(HIDECHIT){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
 		}
 		if(PRODUCT_TEST){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
 		}
		// $this->db->group_by("mc.menu_cat_id");	
		$this->db->group_by("tsm.menu_name,menu.brand");	
		$this->db->order_by("mc.menu_cat_name ASC");	
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();die();
		return $result;
	}
	public function get_menu_sales_rep($sdate, $edate, $menu_cat_id)
	{
		$this->db->select("menu.menu_name, mc.menu_cat_name, sum(tsm.qty) as qty, sum(tsm.qty*tsm.price/1.12)+COALESCE(sum(modprice/1.12),0)+COALESCE(sum(submodprice/1.12),0) as vat_sales, sum(tsm.qty*tsm.price/1.12*.12) + COALESCE(sum(modprice/1.12*.12),0) + COALESCE(sum(submodprice/1.12*.12),0) as vat, sum(tsm.qty*tsm.price) + COALESCE(sum(modprice),0) + COALESCE(sum(submodprice),0) as gross, sum(tsm.qty*menu.costing) as cost",false);
		$this->db->from("trans_sales_menus tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id and ts.pos_id = tsm.pos_id");		
		$this->db->join("menus menu", "menu.menu_id = tsm.menu_id");		
		$this->db->join("menu_categories mc", "mc.menu_cat_id = menu.menu_cat_id");

		$this->db->join("(select sum(tsm.qty * tsm.price) modprice,tsm.sales_id,menu_id, tsm.pos_id, sum(tssubmod.qty * tssubmod.price) submodprice from trans_sales_menu_modifiers tsm
			left join trans_sales_menu_submodifiers tssubmod on tsm.mod_line_id = tssubmod.mod_line_id and tsm.sales_id = tssubmod.sales_id and tsm.pos_id = tssubmod.pos_id
			group by sales_id,menu_id, tsm.pos_id) tsmod", "tsmod.sales_id = tsm.sales_id and tsmod.menu_id = tsm.menu_id and tsmod.pos_id = tsm.pos_id",'left');

		if($menu_cat_id != "")
		{
			$this->db->where("mc.menu_cat_id", $menu_cat_id);					
		}
		$this->db->where("ts.datetime >=", $sdate);		
		$this->db->where("ts.datetime <", $edate);
		$this->db->where("ts.type_id", 10);
		$this->db->where("ts.trans_ref is not null");
 		$this->db->where("ts.inactive", 0);
 		if(HIDECHIT){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
 		}
 		if(PRODUCT_TEST){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
 		}
		$this->db->group_by("menu.menu_id");		
		$this->db->order_by("menu.menu_name ASC");
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();
		return $result;
	}
	public function get_payment_date($sdate, $edate)
 	{
		$this->db->select("tsm.*");
		$this->db->from("trans_sales_payments tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id");		
		// $this->db->join("menus menu", "menu.menu_id = tsm.menu_id");		
		// $this->db->join("menu_categories mc", "mc.menu_cat_id = menu.menu_cat_id");		
		// if($menu_cat_id != "")
		// {
			// $this->db->where("mc.menu_cat_id", $menu_cat_id);					
 		// }
 		$this->db->where("ts.datetime >=", $sdate);		
 		$this->db->where("ts.datetime <=", $edate);
 		$this->db->where("ts.type_id", 10);
 		$this->db->where("ts.trans_ref is not null");
 		$this->db->where("ts.inactive", 0);
 		// $this->db->group_by("tsm.payment_type");	
 		// $this->db->order_by("mc.menu_cat_name ASC");	
 		$q = $this->db->get();
 		$result = $q->result();
 		// echo $this->db->last_query();
 		return $result;
 	}

 	////
	public function get_mod_cat_sales_rep($sdate, $edate, $menu_cat_id)
	{
		$this->db->select("sum(tsm.qty*tsm.price) as mod_gross");
		$this->db->from("trans_sales_menu_modifiers tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id");		
		$this->db->join("menus menu", "menu.menu_id = tsm.menu_id");		
		$this->db->join("menu_categories mc", "mc.menu_cat_id = menu.menu_cat_id");		
		if($menu_cat_id != "")
		{
			$this->db->where("mc.menu_cat_id", $menu_cat_id);					
		}
		$this->db->where("ts.datetime >=", $sdate);		
		$this->db->where("ts.datetime <", $edate);
		$this->db->where("ts.type_id", 10);
		$this->db->where("ts.trans_ref is not null");
		$this->db->where("ts.inactive", 0);
		if(HIDECHIT){
			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
		}
		if(PRODUCT_TEST){
			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
		}
		$this->db->group_by("mc.menu_cat_id");	
		$this->db->order_by("mc.menu_cat_name ASC");	
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();
		return $result;
	}
	public function get_mod_menu_sales_rep($sdate, $edate, $menu_cat_id)
	{
		$this->db->select("sum(tsm.qty*tsm.price) as mod_gross");
		$this->db->from("trans_sales_menu_modifiers tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id");		
		$this->db->join("menus menu", "menu.menu_id = tsm.menu_id");		
		$this->db->join("menu_categories mc", "mc.menu_cat_id = menu.menu_cat_id");		
		if($menu_cat_id != "")
		{
			$this->db->where("mc.menu_cat_id", $menu_cat_id);					
		}
		$this->db->where("ts.datetime >=", $sdate);		
		$this->db->where("ts.datetime <", $edate);
		$this->db->where("ts.type_id", 10);
		$this->db->where("ts.trans_ref is not null");
		$this->db->where("ts.inactive", 0);
		if(HIDECHIT){
			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
		}
		if(PRODUCT_TEST){
			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
		}
		$this->db->group_by("menu.menu_id");		
		$this->db->order_by("menu.menu_name ASC");
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();
		return $result;
	}

	////for retail
	public function get_cat_sales_rep_retail($sdate, $edate, $item_cat_id)
	{
		$this->db->select("mc.name, sum(tsm.qty) as qty, sum(tsm.qty*tsm.price/1.12) as vat_sales, sum(tsm.qty*tsm.price/1.12*.12) as vat, sum(tsm.qty*tsm.price) as gross");
		$this->db->from("trans_sales_items tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id and ts.pos_id = tsm.pos_id");		
		$this->db->join("items item", "item.item_id = tsm.item_id");		
		$this->db->join("categories mc", "mc.cat_id = item.cat_id");		
		if($item_cat_id != "")
		{
			$this->db->where("mc.cat_id", $item_cat_id);					
		}
		$this->db->where("ts.datetime >=", $sdate);		
		$this->db->where("ts.datetime <", $edate);
		$this->db->where("ts.type_id", 10);
		$this->db->where("ts.trans_ref is not null");
 		$this->db->where("ts.inactive", 0);
 		$this->db->where("ts.terminal_id", TERMINAL_ID);
 		if(HIDECHIT){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
 		}
 		if(PRODUCT_TEST){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
 		}
		$this->db->group_by("mc.cat_id");	
		$this->db->order_by("mc.name ASC");	
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();die();
		return $result;
	}
	public function get_menu_sales_rep_retail($sdate, $edate, $menu_cat_id)
	{
		$this->db->select("item.name as item_name, mc.name as cat_name, sum(tsm.qty) as qty, sum(tsm.qty*tsm.price/1.12) as vat_sales, sum(tsm.qty*tsm.price/1.12*.12) as vat, sum(tsm.qty*tsm.price) as gross");
		$this->db->from("trans_sales_items tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id");		
		$this->db->join("items item", "item.item_id = tsm.item_id");		
		$this->db->join("categories mc", "mc.cat_id = item.cat_id");		
		if($menu_cat_id != "")
		{
			$this->db->where("mc.cat_id", $menu_cat_id);					
		}
		$this->db->where("ts.datetime >=", $sdate);		
		$this->db->where("ts.datetime <", $edate);
		$this->db->where("ts.type_id", 10);
		$this->db->where("ts.trans_ref is not null");
 		$this->db->where("ts.inactive", 0);
 		if(HIDECHIT){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
 		}
 		if(PRODUCT_TEST){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
 		}
		$this->db->group_by("item.item_id");		
		$this->db->order_by("item.name ASC");
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();
		return $result;
	}

	//retail report
	public function get_item_sales($from, $to)
	{
		$select_array = array("DATE_FORMAT(trans_sales.datetime,'%Y-%m-%d') as date", "sum(trans_sales_items.qty) tot_qty", "trans_sales_items.price", "items.code","items.name as item_name", "categories.name as cat_name", "subcategories.name as sub_cat_name", "sum(trans_sales_items.qty * trans_sales_items.price) as item_gross");
		$this->db->select($select_array);
		$this->db->from("trans_sales");
		$this->db->join("trans_sales_items", "trans_sales.sales_id = trans_sales_items.sales_id");
		$this->db->join("items", "items.item_id = trans_sales_items.item_id","left");
		$this->db->join("categories", "categories.cat_id = items.cat_id","left");
		$this->db->join("subcategories", "subcategories.sub_cat_id = items.subcat_id","left");
		$this->db->where("trans_sales.datetime >=", $from);
		$this->db->where("trans_sales.datetime <=", $to);
		$this->db->where("trans_sales.type_id", 10);
		$this->db->where("trans_sales.trans_ref is not null");
		$this->db->where("trans_sales.inactive", 0);
		$group_array = array("DATE_FORMAT(trans_sales.datetime,'%Y-%m-%d')", "trans_sales_items.item_id", "trans_sales_items.price");
		$this->db->group_by($group_array);
		$this->db->order_by("items.name ASC");

		$get = $this->db->get();
		return $get->result();
	}

	public function get_subcategories($id=null,$notAll=false){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('subcategories');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('subcategories.sub_cat_id',$id);
				}else{
					$this->db->where('subcategories.sub_cat_id',$id);
				}
			if($notAll){
				$this->db->where('subcategories.inactive',0);
			}
			$this->db->order_by('sub_cat_id asc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	//for new maintenance
	public function get_menu_subcategory($id=null,$notAll=false){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('menu_subcategory');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_subcategory.menu_sub_id',$id);
				}else{
					$this->db->where('menu_subcategory.menu_sub_id',$id);
				}
			if($notAll){
				$this->db->where('menu_subcategory.inactive',0);
			}
			$this->db->order_by('menu_sub_id asc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_menu_subcategory($items,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('menu_subcategory',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_menu_subcategory($user,$id,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->where('menu_sub_id', $id);
		$this->db->update('menu_subcategory', $user);

		return $this->db->last_query();
	}
	public function get_menus_new($id=null,$cat_id=null,$notAll=false,$search=null,$page=0){
		$this->db->trans_start();
			$this->db->select('menus.*,menu_categories.menu_cat_name as category_name,menu_schedules.desc as menu_schedule_name, img_path');
			$this->db->from('menus');
			$this->db->join('menu_categories','menus.menu_cat_id = menu_categories.menu_cat_id');
			$this->db->join('menu_schedules','menus.menu_sched_id = menu_schedules.menu_sched_id','left');
			$this->db->join('images','menus.menu_id = images.img_ref_id and images.img_tbl = "menus"','left');
			$this->db->where("menus.menu_sub_id is null");
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menus.menu_id',$id);
				}else{
					$this->db->where('menus.menu_id',$id);
				}
			if($cat_id != null){
				$this->db->where('menus.menu_cat_id',$cat_id);
			}
			if($notAll){
				$this->db->where('menus.inactive',0);
			}
			if($search != null){
				$this->db->like('menu_short_desc', $search);
				$this->db->or_like('menu_name', $search); 
			}
			if($page != 0){
				if($page == 1){
	                $this->db->limit(MENU_COUNT_BUTTONS,0);
	            }else{
	                $limit = ($page - 1) * MENU_COUNT_BUTTONS;
	                $this->db->limit(MENU_COUNT_BUTTONS,$limit);
	                // $limit = ($cat_page - 1) * 28;
	            }
			}
			$this->db->order_by('menus.menu_name asc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_subcategory($id=null,$cat_id=null,$notAll=false,$search=null,$page=0){
		$this->db->trans_start();
			$this->db->select('menu_subcategory.*');
			$this->db->from('menu_subcategory');
			$this->db->join('menu_categories','menu_subcategory.category_id = menu_categories.menu_cat_id');
			// $this->db->join('menu_schedules','menus.menu_sched_id = menu_schedules.menu_sched_id','left');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_subcategory.menu_sub_id',$id);
				}else{
					$this->db->where('menu_subcategory.menu_sub_id',$id);
				}
			if($cat_id != null){
				$this->db->where('menu_subcategory.category_id',$cat_id);
			}
			// if($notAll){
			$this->db->where('menu_subcategory.inactive',0);
			// }
			// if($search != null){
			// 	$this->db->like('menu_short_desc', $search);
			// 	$this->db->or_like('menu_name', $search); 
			// }
			if($page != 0){
				if($page == 1){
	                $this->db->limit(MENU_COUNT_BUTTONS,0);
	            }else{
	                $limit = ($page - 1) * MENU_COUNT_BUTTONS;
	                $this->db->limit(MENU_COUNT_BUTTONS,$limit);
	                // $limit = ($cat_page - 1) * 28;
	            }
			}
			$this->db->order_by('menu_subcategory.menu_sub_name asc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_menu_subcat($id=null,$subcat_id=null,$notAll=false,$search=null,$page=0){
		$this->db->trans_start();
			$this->db->select('menus.*,menu_subcategory.menu_sub_name as subcategory_name,menu_schedules.desc as menu_schedule_name, img_path,menu_categories.menu_cat_name as category_name');
			$this->db->from('menus');
			$this->db->join('menu_subcategory','menus.menu_sub_id = menu_subcategory.menu_sub_id');
			$this->db->join('menu_categories','menus.menu_cat_id = menu_categories.menu_cat_id');
			$this->db->join('menu_schedules','menus.menu_sched_id = menu_schedules.menu_sched_id','left');
			$this->db->join('images','menus.menu_id = images.img_ref_id and images.img_tbl = "menus"','left');

			// $this->db->where("menus.menu_sub_id is null");
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menus.menu_id',$id);
				}else{
					$this->db->where('menus.menu_id',$id);
				}
			if($subcat_id != null){
				$this->db->where('menus.menu_sub_id',$subcat_id);
			}
			if($notAll){
				$this->db->where('menus.inactive',0);
			}
			if($search != null){
				$this->db->like('menu_short_desc', $search);
				$this->db->or_like('menu_name', $search); 
			}
			if($page != 0){
				if($page == 1){
	                $this->db->limit(MENU_COUNT_BUTTONS,0);
	            }else{
	                $limit = ($page - 1) * MENU_COUNT_BUTTONS;
	                $this->db->limit(MENU_COUNT_BUTTONS,$limit);
	                // $limit = ($cat_page - 1) * 28;
	            }
			}
			$this->db->order_by('menus.menu_name asc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_voided_cat_sales_rep($sdate, $edate, $menu_cat_id)
	{
		$this->db->select("mc.menu_cat_name, sum(tsm.qty) as qty, sum(tsm.qty*tsm.price/1.12) as vat_sales, sum(tsm.qty*tsm.price/1.12*.12) as vat, sum(tsm.qty*tsm.price) as gross, sum(tsm.qty*menu.costing) as cost");
		$this->db->from("trans_sales_menus tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id and ts.pos_id = tsm.pos_id");		
		$this->db->join("menus menu", "menu.menu_id = tsm.menu_id");		
		$this->db->join("menu_categories mc", "mc.menu_cat_id = menu.menu_cat_id");	

		if($menu_cat_id != "")
		{
			$this->db->where("mc.menu_cat_id", $menu_cat_id);					
		}
		$this->db->where("ts.update_date >=", $sdate);		
		$this->db->where("ts.update_date <", $edate);
		$this->db->where("ts.type_id", 11);
		$this->db->where("ts.trans_ref is not null");
 		$this->db->where("ts.inactive", 0);
 		$this->db->where("ts.terminal_id", TERMINAL_ID);
 		if(HIDECHIT){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
 		}
 		if(PRODUCT_TEST){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
 		}
		$this->db->group_by("mc.menu_cat_id");	
		$this->db->order_by("mc.menu_cat_name ASC");	
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();die();
		return $result;
	}
	public function get_voided_sales_res($sdate, $edate)
	{
		$this->db->select("ts.*,cb.ref_no,cb.trans_ref,c.fname,c.mname,c.lname");
		$this->db->from("trans_sales ts");
		$this->db->join("customers_bank cb", "cb.sales_id = ts.sales_id");		
		$this->db->join("customers c", "c.cust_id = cb.cust_id");		
		// $this->db->join("menus menu", "menu.menu_id = tsm.menu_id");		
		// $this->db->join("menu_categories mc", "mc.menu_cat_id = menu.menu_cat_id");	

		// if($menu_cat_id != "")
		// {
		// 	$this->db->where("mc.menu_cat_id", $menu_cat_id);					
		// }
		$this->db->where("ts.datetime >=", $sdate);		
		$this->db->where("ts.datetime <", $edate);
		// $this->db->where("ts.type_id", 11);
		// $this->db->where("ts.trans_ref is not null");
 	// 	$this->db->where("ts.inactive", 0);
 		$this->db->where("ts.type", "reservation");
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();die();
		return $result;
	}

	////for retail
	public function get_voided_cat_sales_rep_retail($sdate, $edate, $item_cat_id)
	{
		$this->db->select("mc.name, sum(tsm.qty) as qty, sum(tsm.qty*tsm.price/1.12) as vat_sales, sum(tsm.qty*tsm.price/1.12*.12) as vat, sum(tsm.qty*tsm.price) as gross");
		$this->db->from("trans_sales_items tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id and ts.pos_id = tsm.pos_id");		
		$this->db->join("items item", "item.item_id = tsm.item_id");		
		$this->db->join("categories mc", "mc.cat_id = item.cat_id");

		if($item_cat_id != "")
		{
			$this->db->where("mc.cat_id", $item_cat_id);					
		}
		$this->db->where("ts.update_date >=", $sdate);		
		$this->db->where("ts.update_date <", $edate);
		$this->db->where("ts.type_id", 11);
		$this->db->where("ts.trans_ref is not null");
 		$this->db->where("ts.inactive", 0);
 		$this->db->where("ts.terminal_id", TERMINAL_ID);
 		if(HIDECHIT){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
 		}
 		if(PRODUCT_TEST){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
 		}
		$this->db->group_by("mc.cat_id");	
		$this->db->order_by("mc.name ASC");	
		$q = $this->db->get();
		$result = $q->result();
		 // echo $this->db->last_query();die();
		
		return $result;
	}
	public function get_voided_cat_sales_res_retail($sdate, $edate, $item_cat_id)
	{
		$this->db->select("mc.name, sum(tsm.qty) as qty, sum(tsm.qty*tsm.price/1.12) as vat_sales, sum(tsm.qty*tsm.price/1.12*.12) as vat, sum(tsm.qty*tsm.price) as gross");
		$this->db->from("trans_sales_items tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id");		
		$this->db->join("items item", "item.item_id = tsm.item_id");		
		$this->db->join("categories mc", "mc.cat_id = item.cat_id");

		if($item_cat_id != "")
		{
			$this->db->where("mc.cat_id", $item_cat_id);					
		}
		$this->db->where("ts.update_date >=", $sdate);		
		$this->db->where("ts.update_date <", $edate);
		$this->db->where("ts.type_id", 11);
		$this->db->where("ts.trans_ref is not null");
 		$this->db->where("ts.inactive", 0);
 		$this->db->where("ts.type", "reservation");
 		if(HIDECHIT){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
 		}
 		if(PRODUCT_TEST){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
 		}
		$this->db->group_by("mc.cat_id");	
		$this->db->order_by("mc.name ASC");	
		$q = $this->db->get();
		$result = $q->result();
		 // echo $this->db->last_query();die();
		
		return $result;
	}

	public function get_promo($from,$to)
	{
		$data = array();

		// $tbl = array('trans_sales_items','trans_sales_menus');	
		$tbl = array('trans_sales_menus');

		// $field_name = array('name','menu_name');
		$field_name = array('menu_name');

		foreach($tbl as $i=>$each){
			$select_array = array($field_name[$i] . " as name", 
							  "remarks",
							  "DATE_FORMAT(trans_sales.datetime,'%m/%d/%Y') as date", 
							  "{$each}.qty",
							  "{$each}.qty * {$each}.price amount");

			$this->db->select($select_array);
			$this->db->from("trans_sales");
			$this->db->join("{$each}", "trans_sales.sales_id = {$each}.sales_id");

			if($each == 'trans_sales_items'){
				$this->db->join("items", "items.item_id = {$each}.item_id");
			}else{
				$this->db->join("menus", "menus.menu_id = {$each}.menu_id");
				$this->db->where("menus.menu_cat_id = 9");
			}
			
			$this->db->where("trans_sales.datetime >=", $from);
			$this->db->where("trans_sales.datetime <=", $to);
			$this->db->where("trans_sales.type_id", 10);
			$this->db->where("trans_sales.trans_ref is not null");
			// $this->db->where("{$each}.price = 0");
		
			$this->db->where("trans_sales.inactive", 0);

			$get = $this->db->get();
			$result = $get->result();

			if($result){
				foreach ($result as $v) {
					array_push($data, $v);
				}
				
			}			
		}

		return $data;
	}
	public function get_menu_brand()
	{
		$this->db->select("brand, b.brand_name");
		$this->db->from("menus menu");
		$this->db->join("brands b", "menu.brand = b.id");
		$this->db->group_by("menu.brand");	
		$this->db->order_by("menu.brand ASC");	
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();die();
		return $result;
	}
	public function get_brand_cat_sales_rep($sdate, $edate, $menu_cat_id, $brand= "")
	{
		$this->db->select("mc.menu_cat_name, sum(tsm.qty) as qty, sum(tsm.qty*tsm.price/1.12) + COALESCE(sum(modprice/1.12),0) + COALESCE(sum(submodprice/1.12),0) as vat_sales, sum(tsm.qty*tsm.price/1.12*.12) + COALESCE(sum(modprice/1.12*.12),0) + COALESCE(sum(submodprice/1.12*.12),0) as vat, sum(tsm.qty*tsm.price) + COALESCE(sum(modprice),0) + COALESCE(sum(submodprice),0) as gross, sum(tsm.qty*menu.costing) as cost,menu.brand,tsm.menu_name",false);
		$this->db->from("trans_sales_menus tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id and ts.pos_id = tsm.pos_id");		
		$this->db->join("menus menu", "menu.menu_id = tsm.menu_id");		
		$this->db->join("menu_categories mc", "mc.menu_cat_id = menu.menu_cat_id");

		$this->db->join("(select sum(tsm.qty * tsm.price) modprice,tsm.sales_id,menu_id, tsm.pos_id, sum(tssubmod.qty * tssubmod.price) submodprice from trans_sales_menu_modifiers tsm
			left join trans_sales_menu_submodifiers tssubmod on tsm.mod_line_id = tssubmod.mod_line_id and tsm.sales_id = tssubmod.sales_id and tsm.pos_id = tssubmod.pos_id
			group by sales_id,menu_id, tsm.pos_id) tsmod", "tsmod.sales_id = tsm.sales_id and tsmod.menu_id = tsm.menu_id",'left');

		if($menu_cat_id != "")
		{
			$this->db->where("mc.menu_cat_id", $menu_cat_id);					
		}
		if($brand != "")
		{
			$this->db->where("menu.brand", $brand);					
		}
		$this->db->where("ts.datetime >=", $sdate);		
		$this->db->where("ts.datetime <", $edate);
		$this->db->where("ts.type_id", 10);
		$this->db->where("ts.trans_ref is not null");
 		$this->db->where("ts.inactive", 0);
 		$this->db->where("ts.terminal_id", TERMINAL_ID);
 		if(HIDECHIT){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
 		}
 		if(PRODUCT_TEST){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
 		}
		// $this->db->group_by("menu.brand,tsm.menu_name");	
		$this->db->group_by("menu.brand");	
		$this->db->order_by("mc.menu_cat_name ASC");	
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();die();
		return $result;
	}
	public function get_brand_menu_rep($sdate, $edate, $menu_cat_id, $brand= "")
	{
		$this->db->select("mc.menu_cat_name, sum(tsm.qty) as qty, sum(tsm.qty*tsm.price/1.12) + COALESCE(sum(modprice/1.12),0) + COALESCE(sum(submodprice/1.12),0) as vat_sales, sum(tsm.qty*tsm.price/1.12*.12) + COALESCE(sum(modprice/1.12*.12),0) + COALESCE(sum(submodprice/1.12*.12),0) as vat, sum(tsm.qty*tsm.price) + COALESCE(sum(modprice),0) + COALESCE(sum(submodprice),0) as gross, sum(tsm.qty*menu.costing) as cost,menu.brand,tsm.menu_name",false);
		$this->db->from("trans_sales_menus tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id and ts.pos_id = tsm.pos_id");		
		$this->db->join("menus menu", "menu.menu_id = tsm.menu_id");		
		$this->db->join("menu_categories mc", "mc.menu_cat_id = menu.menu_cat_id");

		$this->db->join("(select sum(tsm.qty * tsm.price) modprice,tsm.sales_id,menu_id, tsm.pos_id, sum(tssubmod.qty * tssubmod.price) submodprice from trans_sales_menu_modifiers tsm
			left join trans_sales_menu_submodifiers tssubmod on tsm.mod_line_id = tssubmod.mod_line_id and tsm.sales_id = tssubmod.sales_id and tsm.pos_id = tssubmod.pos_id
			group by sales_id,menu_id, tsm.pos_id) tsmod", "tsmod.sales_id = tsm.sales_id and tsmod.menu_id = tsm.menu_id",'left');
		// $this->db->join("(select sum(qty * price) submodprice,sales_id,mod_id from trans_sales_menu_submodifiers group by sales_id,mod_id) tssubmod", "tssubmod.sales_id = tsm.sales_id and tssubmod.mod_id = tsmod.mod_id",'left');		
		if($menu_cat_id != "")
		{
			$this->db->where("mc.menu_cat_id", $menu_cat_id);					
		}
		if($brand != "")
		{
			$this->db->where("menu.brand", $brand);					
		}
		$this->db->where("ts.datetime >=", $sdate);		
		$this->db->where("ts.datetime <", $edate);
		$this->db->where("ts.type_id", 10);
		$this->db->where("ts.trans_ref is not null");
 		$this->db->where("ts.inactive", 0);
 		$this->db->where("ts.terminal_id", TERMINAL_ID);
 		if(HIDECHIT){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
 		}
 		if(PRODUCT_TEST){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
 		}
		$this->db->group_by("menu.brand,tsm.menu_name");	
		// $this->db->group_by("menu.brand");	
		$this->db->order_by("mc.menu_cat_name ASC");	
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();die();
		return $result;
	}

	/********	 	Menu Prices 		********/
	public function get_menu_prices($menu_id=null,$id=null){
			$this->db->select('menu_prices.*,transaction_types.trans_name');			
			$this->db->from('menu_prices');
			$this->db->join('transaction_types','transaction_types.trans_name=menu_prices.trans_type');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_prices.trans_type',$id);
				}else{
					$this->db->where('menu_prices.trans_type',$id);
				}
			if($menu_id != null)
				$this->db->where_in('menu_prices.menu_id',$menu_id);
			// if($mod_group_id != null)
			// 	$this->db->where_in('menu_modifiers.mod_group_id',$mod_group_id);
			// $this->db->order_by('id desc');
			$query = $this->db->get();
			$result = $query->result();
		return $result;
	}
	public function add_menu_price($items,$terminal=null)
	{
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->trans_start();
		$this->db->insert('menu_prices',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}
	public function remove_menu_price($id,$terminal=null)
	{
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->trans_start();
		$this->db->where('id',$id);
		$this->db->delete('menu_prices');
		$this->db->trans_complete();
	}

	public function get_menu_submodifiers($submod_id=null,$mod_id=null){
			$this->db->select('*');	
			$this->db->from('modifier_sub');
			// $this->db->join('modifier_groups','menu_modifiers.mod_group_id=modifier_groups.mod_group_id');
			if($mod_id != null)
				if(is_array($mod_id))
				{
					$this->db->where_in('modifier_sub.mod_id',$mod_id);
				}else{
					$this->db->where('modifier_sub.mod_id',$mod_id);
				}
			if($submod_id != null)
				$this->db->where_in('modifier_sub.mod_sub_id',$submod_id);
			// if($mod_group_id != null)
			// 	$this->db->where_in('menu_modifiers.mod_group_id',$mod_group_id);
			$this->db->order_by('mod_sub_id desc');
			$query = $this->db->get();
			$result = $query->result();
		return $result;
	}
	public function get_branch_color($id){
		$this->db->select('branch_color,branch_text_color');
		$this->db->from('branch_details');
		// if($id != '')
			// $this->db->where('menu_sched_id',$id);
		$query = $this->db->get();
		$result = $query->result();

		return $result;
	}

	function get_app_menu_name($menu_name){
		$this->db->where('menu_name',$menu_name);
		$query = $this->db->get('menus');
		$result = $query->result();

		return $result;
	}

	///for no primary ket maintenance
	public function get_last_menu_id(){
		$this->db->trans_start();
			$this->db->select('menu_id');
			$this->db->from('menus');
			$this->db->order_by('menu_id desc');
			$this->db->limit('1');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		if(isset($result[0])){
			return $result[0]->menu_id;
		}else{
			return 0;
		}
	}

	public function get_menu_sales_rep_hourly($sdate, $edate)
	{
		$this->db->select("menu.menu_name,menu.menu_code, menu.cost as mprice, mc.menu_cat_name, sum(tsm.qty) as qty, sum(tsm.qty*tsm.price/1.12)+COALESCE(sum(modprice/1.12),0)+COALESCE(sum(submodprice/1.12),0) as vat_sales, sum(tsm.qty*tsm.price/1.12*.12) + COALESCE(sum(modprice/1.12*.12),0) + COALESCE(sum(submodprice/1.12*.12),0) as vat, sum(tsm.qty*tsm.price) + COALESCE(sum(modprice),0) + COALESCE(sum(submodprice),0) as gross, sum(tsm.qty*menu.costing) as cost, ts.trans_ref, ts.datetime, ts.type as trans_type, ts.guest, tsm.remarks as mremarks, tsm.line_id, tsm.sales_id as sales_id, ts.inactive, tt.terminal_code",false);
		$this->db->from("trans_sales_menus tsm");
		$this->db->join("trans_sales ts", "ts.sales_id = tsm.sales_id and ts.pos_id = tsm.pos_id");		
		$this->db->join("menus menu", "menu.menu_id = tsm.menu_id");		
		$this->db->join("menu_categories mc", "mc.menu_cat_id = menu.menu_cat_id");
		$this->db->join("terminals tt", "tt.terminal_id = ts.terminal_id");

		$this->db->join("(select sum(tsm.qty * tsm.price) modprice,tsm.sales_id,menu_id, tsm.pos_id, sum(tssubmod.qty * tssubmod.price) submodprice from trans_sales_menu_modifiers tsm
			left join trans_sales_menu_submodifiers tssubmod on tsm.mod_line_id = tssubmod.mod_line_id and tsm.sales_id = tssubmod.sales_id and tsm.pos_id = tssubmod.pos_id
			group by sales_id,menu_id, tsm.pos_id) tsmod", "tsmod.sales_id = tsm.sales_id and tsmod.menu_id = tsm.menu_id and tsmod.pos_id = tsm.pos_id",'left');

		// if($menu_cat_id != "")
		// {
		// 	$this->db->where("mc.menu_cat_id", $menu_cat_id);					
		// }
		$this->db->where("ts.datetime >=", $sdate);		
		$this->db->where("ts.datetime <", $edate);
		$this->db->where("ts.type_id", 10);
		$this->db->where("ts.trans_ref is not null");
 		// $this->db->where("ts.inactive", 0);
 		$this->db->where("ts.pos_id", TERMINAL_ID);
 		if(HIDECHIT){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'chit')");
 		}
 		if(PRODUCT_TEST){
 			$this->db->where("ts.sales_id NOT IN (SELECT sales_id from trans_sales_payments where payment_type = 'producttest')");
 		}
		$this->db->group_by("ts.sales_id, tsm.line_id");		
		$this->db->order_by("ts.datetime ASC");
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();
		return $result;
	}
}
?>