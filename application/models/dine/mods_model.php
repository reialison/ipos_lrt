<?php
class Mods_model extends CI_Model{

	public function __construct(){
		parent::__construct();
	}
	public function get_modifiers($id=null){
		$this->db->trans_start();
			$this->db->select('modifiers.*');
			$this->db->from('modifiers');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('modifiers.mod_id',$id);
				}else{
					$this->db->where('modifiers.mod_id',$id);
				}
			$this->db->order_by('modifiers.name desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_modifiers($items,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('modifiers',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_modifiers($user,$id,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->where('mod_id', $id);
		$this->db->update('modifiers', $user);

		return $this->db->last_query();
	}
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
	public function get_modifier_recipe($id=null,$mod_id=null,$item_id=null){
		$this->db->trans_start();
			$this->db->select('modifier_recipe.*,items.code,items.name');
			$this->db->from('modifier_recipe');
			$this->db->join('items','modifier_recipe.item_id=items.item_id');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('modifier_recipe.mod_recipe_id',$id);
				}else{
					$this->db->where('modifier_recipe.mod_recipe_id',$id);
				}
			if($mod_id != null){
				$this->db->where_in('modifier_recipe.mod_id',$mod_id);
			}
			if($item_id != null){
				$this->db->where('modifier_recipe.item_id',$item_id);
			}
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_modifier_recipe($items){
		$this->db->insert('modifier_recipe',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_modifier_recipe($user,$id){
		$this->db->where('mod_recipe_id', $id);
		$this->db->update('modifier_recipe', $user);

		return $this->db->last_query();
	}
	public function delete_modifier_recipe_item($id){
		$this->db->where('mod_recipe_id', $id);
		$this->db->delete('modifier_recipe'); 
	}
	public function get_modifier_recipe_prices($id=null,$mod_id=null,$item_id=null){
		$this->db->trans_start();
			$this->db->select('modifier_recipe.item_id,modifier_recipe.qty,modifier_recipe.cost');
			$this->db->from('modifier_recipe');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('modifier_recipe.mod_recipe_id',$id);
				}else{
					$this->db->where('modifier_recipe.mod_recipe_id',$id);
				}
			if($mod_id != null){
				$this->db->where('modifier_recipe.mod_id',$mod_id);
			}
			if($item_id != null){
				$this->db->where('modifier_recipe.item_id',$item_id);
			}
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_modifier_groups($id=null){
		$this->db->trans_start();
			$this->db->select('modifier_groups.*');
			$this->db->from('modifier_groups');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('modifier_groups.mod_group_id',$id);
				}else{
					$this->db->where('modifier_groups.mod_group_id',$id);
				}
			$this->db->order_by('modifier_groups.name desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_modifier_groups($items,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->insert('modifier_groups',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_modifier_groups($user,$id,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->where('mod_group_id', $id);
		$this->db->update('modifier_groups', $user);

		return $this->db->last_query();
	}
	public function get_modifier_group_details($id=null,$mod_group_id=null,$mod_id=null){
		$this->db->trans_start();
			$this->db->select('modifier_group_details.*,modifiers.name as mod_name,modifiers.cost as mod_cost,modifiers.inactive as mod_inactive');
			$this->db->from('modifier_group_details');
			$this->db->join('modifiers','modifier_group_details.mod_id = modifiers.mod_id');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('modifier_group_details.id',$id);
				}else{
					$this->db->where('modifier_group_details.id',$id);
				}
			if($mod_id != null)	
				$this->db->where('modifier_group_details.mod_id',$mod_id);
			if($mod_group_id != null)	
				if(is_array($mod_group_id))
					$this->db->where_in('modifier_group_details.mod_group_id',$mod_group_id);
				else
					$this->db->where('modifier_group_details.mod_group_id',$mod_group_id);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_modifier_group_details($items){
		$this->db->insert('modifier_group_details',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_modifier_group_details($user,$id){
		$this->db->where('id', $id);
		$this->db->update('modifier_group_details', $user);

		return $this->db->last_query();
	}
	public function delete_modifier_group_details($id){
		$this->db->where('id', $id);
		$this->db->delete('modifier_group_details'); 
	}
	public function search_modifiers($search=""){
		$this->db->trans_start();
			$this->db->select('modifiers.mod_id,modifiers.name');
			$this->db->from('modifiers');
			if($search != ""){
				$this->db->like('modifiers.name', $search); 
			}
			$this->db->order_by('modifiers.name');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_modifier_sub($id=null,$mod_id=null){
		$this->db->trans_start();
			$this->db->select('modifier_sub.*');
			$this->db->from('modifier_sub');
			
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('modifier_sub.mod_sub_id',$id);
				}else{
					$this->db->where('modifier_sub.mod_sub_id',$id);
				}
			if($mod_id != null){
				$this->db->where_in('modifier_sub.mod_id',$mod_id);
			}
			if($mod_id != null){
				$this->db->where('modifier_sub.mod_id',$mod_id);
			}
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function add_modifier_sub($items,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->insert('modifier_sub',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_modifier_sub($items,$id){
		$this->db->where('mod_sub_id', $id);
		$this->db->update('modifier_sub', $items);

		return $this->db->last_query();
	}
	public function delete_modifier_sub($id,$terminal=null){
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->where('mod_sub_id', $id);
		$this->db->delete('modifier_sub'); 
	}

	public function get_mod_prices($mod_id=null,$id=null){
			$this->db->select('modifier_prices.*,transaction_types.trans_name');			
			$this->db->from('modifier_prices');
			$this->db->join('transaction_types','transaction_types.trans_name=modifier_prices.trans_type');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('modifier_prices.trans_type',$id);
				}else{
					$this->db->where('modifier_prices.trans_type',$id);
				}
			if($mod_id != null)
				$this->db->where_in('modifier_prices.mod_id',$mod_id);
			// if($mod_group_id != null)
			// 	$this->db->where_in('menu_modifiers.mod_group_id',$mod_group_id);
			// $this->db->order_by('id desc');
			$query = $this->db->get();
			$result = $query->result();
		return $result;
	}

	public function add_mod_price($items,$terminal=null)
	{
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->trans_start();
		$this->db->insert('modifier_prices',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}
	public function remove_mod_price($id,$terminal=null)
	{
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->trans_start();
		$this->db->where('id',$id);
		$this->db->delete('modifier_prices');
		$this->db->trans_complete();
	}

	public function get_mod_sub_prices($mod_sub_id=null,$id=null){
			$this->db->select('modifier_sub_prices.*,transaction_types.trans_name');			
			$this->db->from('modifier_sub_prices');
			$this->db->join('transaction_types','transaction_types.trans_name=modifier_sub_prices.trans_type');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('modifier_sub_prices.trans_type',$id);
				}else{
					$this->db->where('modifier_sub_prices.trans_type',$id);
				}
			if($mod_sub_id != null)
				$this->db->where_in('modifier_sub_prices.mod_sub_id',$mod_sub_id);
			// if($mod_group_id != null)
			// 	$this->db->where_in('menu_modifiers.mod_group_id',$mod_group_id);
			// $this->db->order_by('id desc');
			$query = $this->db->get();
			$result = $query->result();
		return $result;
	}

	public function add_mod_sub_price($items,$terminal=null)
	{
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->trans_start();
		$this->db->insert('modifier_sub_prices',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}
	public function remove_mod_sub_price($id=null,$mod_sub_id=null,$terminal=null)
	{
		if($terminal != null){
			$this->db = $this->load->database($terminal,true);
		}
		$this->db->trans_start();

		if($id != null){
			$this->db->where('id',$id);
		}

		if($mod_sub_id != null){
			$this->db->where('mod_sub_id',$mod_sub_id);
		}
		
		$this->db->delete('modifier_sub_prices');
		$this->db->trans_complete();
	}

}
?>