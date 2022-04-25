<?php
class Gift_cards_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_gift_cards($id=null,$getInactive=true)
	{
		$this->db->select('*');
		$this->db->from('gift_cards');
		// $this->db->join('categories','items.cat_id = categories.cat_id');
		// $this->db->join('subcategories','items.subcat_id = subcategories.sub_cat_id');
		// $this->db->join('item_types','items.type = item_types.id');
		// $this->db->join('suppliers','items.supplier_id = suppliers.supplier_id');
		if (!is_null($id)) {
			if (is_array($id))
				$this->db->where_in('gift_cards.gc_id',$id);
			else
				$this->db->where('gift_cards.gc_id',$id);
		}
		if (!$getInactive)
			$this->db->where('inactive',0);

		$this->db->order_by('gift_cards.gc_id ASC');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}
	public function get_gift_card_info($cardno=null,$getInactive=true,$description_id=null,$brand_id='')
	{
		$sql = "SELECT * FROM `gift_cards` WHERE '$cardno' = replace(card_no,'-','') ";
		if (!$getInactive) {
			$sql .= " AND inactive = 0 ";
		}

		if($description_id != ''){
			$sql .= " AND description_id = '" . $description_id . "'";
		}

		if($brand_id != ''){
			$sql .= " AND brand_id = '" . $brand_id . "'";
		}

		$sql .= " ORDER BY gift_cards.gc_id ASC";
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	public function get_all_gift_card_count($cardno=null){
		$sql = "SELECT COUNT(*) as total_count FROM `gift_cards` WHERE '$cardno' = replace(card_no,'-','')";
		$query = $this->db->query($sql);
		// // echo $this->db->last_query();
		// // $total=$this->db->count_all_results();
		return $query->result();
	}
	public function add_gift_cards($items)
	{
		// $this->db->set('reg_date','NOW()',FALSE);
		$this->db->insert('gift_cards',$items);
		return $this->db->insert_id();
	}
	public function update_gift_cards($items,$id)
	{
		// $this->db->set('update_date','NOW()',FALSE);
		$this->db->where('gc_id',$id);
		$this->db->update('gift_cards',$items);
	}

	//for gift card brand
	public function get_gift_card_brand($id=null,$notAll=false){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('gift_cards');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('gift_cards.gc_id',$id);
				}else{
					$this->db->where('gift_cards.gc_id',$id);
				}
			if($notAll){
				$this->db->where('gift_cards.inactive',0);
			}

			$this->db->where('gift_cards.description_id is not null');
			$this->db->order_by('gift_cards.description_id asc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_cashier_gift_card($id=null,$cat_id=null,$notAll=false,$search=null){
		$this->db->trans_start();
			$this->db->select('count(*) as qty,gift_cards.*',false);
			$this->db->from('gift_cards');
			
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('gift_cards.gc_id',$id);
				}else{
					$this->db->where('gift_cards.gc_id',$id);
				}
			if($cat_id != null){
				$this->db->where('gift_cards.brand_id',$cat_id);
			}
			if($notAll){
				$this->db->where('gift_cards.inactive',0);
			}
			if($search != null){
				$this->db->like('card_no', $search);
				$this->db->or_like('description_id', $search); 
			}

			$this->db->group_by('description_id');
			$this->db->order_by('gift_cards.description_id asc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_gift_cards_rep_retail($sdate, $edate, $gc_type)
	{
		$this->db->select("ts.trans_ref as ref,tsm.description_id,tsm.price, sum(tsm.qty) as qty, sum(tsm.qty*tsm.price/1.12) as vat_sales, sum(tsm.qty*tsm.price/1.12*.12) as vat, sum(tsm.qty*tsm.price) as gross,tsm.brand_id,tsm.gc_from,tsm.gc_to",false);
		$this->db->from("trans_gc_gift_cards tsm");
		$this->db->join("trans_gc ts", "ts.gc_id = tsm.gc_id");
		// $this->db->join("(select * from gift_cards group by description_id,brand_id) gc", "gc.description_id = tsm.description_id",'left');
		if($gc_type != "")
		{
			$this->db->where("tsm.brand_id", $gc_type);					
		}
		$this->db->where("ts.datetime >=", $sdate);		
		$this->db->where("ts.datetime <", $edate);
		$this->db->where("ts.type_id", 12);
		$this->db->where("ts.trans_ref is not null");
 		$this->db->where("ts.inactive", 0);
 		if(HIDECHIT){
 			$this->db->where("ts.gc_id NOT IN (SELECT gc_id from trans_gc_payments where payment_type = 'chit')");
 		}
 		if(PRODUCT_TEST){
 			$this->db->where("ts.gc_id NOT IN (SELECT gc_id from trans_gc_payments where payment_type = 'producttest')");
 		}
		$this->db->group_by("tsm.description_id,ts.trans_ref");		
		$this->db->order_by("tsm.description_id ASC");
		$q = $this->db->get();
		$result = $q->result();
		// echo $this->db->last_query();
		return $result;
	}
}