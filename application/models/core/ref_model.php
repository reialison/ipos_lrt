<?php
class Ref_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->library('Db_manager');
		$this->db = $this->db_manager->get_connection('bir');

	}

	public function get_trans_types($selected=array()){
		$this->db->from('trans_types');
		if(!empty($selected))
			$this->db->where_in('type_id',$selected);
		$this->db->order_by('description');
		return $this->db->get();
	}

	public function get_next_id($trans_type){
		$this->db->select('ifnull(MAX(id),0)+1 as id',false);
		$this->db->from('refs');
		$this->db->where('trans_type',$trans_type);
		$query=$this->db->get();
		$row = $query->row();
		return $row->id;
	}

	public function get_next_ref($trans_type){
		$this->db->select('next_ref');
		$this->db->from('trans_types');
		$this->db->where('type_id',$trans_type);
		$query=$this->db->get();
		$row = $query->row();
		return $row->next_ref;
	}

	public function get_all(){
		$this->db->from('trans_types');
		$this->db->order_by('type_id','ASC');
		$q=$this->db->get();
		$forms=array();
		foreach($q->result() as $k=>$v)
			$forms[$v->type_id]=array('description'=>$v->description,'next_ref'=>$v->next_ref,'from'=>$v->from,'to'=>$v->to);

		return $forms;
	}

	function check_unique($trans_type,$ref){
		$this->db->from('refs');
		$this->db->where('trans_type',$trans_type);
		$this->db->where('reference',$ref);
		$query=$this->db->get();
		return ($query->num_rows()>0)?false:true;
	}

	public function save_next($trans_type,$ref=null,$auto=false){
		$refs=$this->write_ref($trans_type,$ref);

		if(!$auto)
		$this->prepare_next($trans_type,$refs['ref']);

		return $refs;

	}

	public function save_next_length($trans_type,$ref=null,$len=12){

		$refs=$this->write_ref($trans_type,$ref);

		$ref = substr($refs['ref'],0,$len);
		$this->prepare_next($trans_type,$ref);

		return $refs;
	}

	public function set_next($trans_type,$ref){
		$this->db->update('trans_types', array('next_ref'=>$ref),array('type_id'=>$trans_type));
	}

	public function write_ref($trans_type,$ref=null){
		$this->db->trans_start();

		$next=$this->get_next_id($trans_type);

		if($ref==null)
			$ref=$this->get_next_ref($trans_type);

		$items = array(
			'id'=>$next,
			'trans_type'=>$trans_type,
			'reference'=>$ref
		);

		$this->db->insert('refs',$items);

		return array('id'=>$next,'ref'=>$ref);
	}

	public function save_next_id_length($trans_type,$ref=null,$next_id,$len=12){

		$refs=$this->write_ref_id($trans_type,$ref,$next_id);

		$ref = substr($refs['ref'],0,$len);
		$this->prepare_next($trans_type,$ref);

		return $refs;
	}
	public function write_ref_id($trans_type,$ref=null,$next_id=null){
		$this->db->trans_start();

		$next=$this->get_next_id($trans_type);


		if($ref==null)
			$ref=$this->get_next_ref($trans_type);

		$items = array(
			'id'=>$next_id,
			'trans_type'=>$trans_type,
			'reference'=>$ref
		);

		$this->db->insert('refs',$items);
		return array('id'=>$next,'ref'=>$ref);
	}

	public function prepare_next($trans_type,$ref){

        if (preg_match('/^(\D*?)(\d+)(.*)/', $ref, $result) == 1)
        {
			list($all, $prefix, $number, $postfix) = $result;
			$dig_count = strlen($number); // How many digits? eg. 0003 = 4
			$fmt = '%0' . $dig_count . 'd'; // Make a format string - leading zeroes
			$nextval =  sprintf($fmt, intval($number + 1)); // Add one on, and put prefix back on

			$new_ref=$prefix.$nextval.$postfix;
        }
        else
            $new_ref=$ref;

		$this->db->update('trans_types',array('next_ref'=>$new_ref),array('type_id'=>$trans_type));
	}

	public function increment_ref($ref)
	{
		if (preg_match('/^(\D*?)(\d+)(.*)/', $ref, $result) == 1)
        {
			list($all, $prefix, $number, $postfix) = $result;
			$dig_count = strlen($number); // How many digits? eg. 0003 = 4
			$fmt = '%0' . $dig_count . 'd'; // Make a format string - leading zeroes
			$nextval =  sprintf($fmt, intval($number + 1)); // Add one on, and put prefix back on

			$new_ref=$prefix.$nextval.$postfix;
        }
        else
            $new_ref=$ref;

        return $new_ref;
	}

	public function get_used_ref($type,$type_no){
		$this->db->select('reference');
		$this->db->from('refs');
		$this->db->where('trans_type',$type);
		$this->db->where('id',$type_no);
		return $this->db->get();
	}

	public function get_if_dupref($ref,$table,$col){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($col,$ref);

		$query=$this->db->get();
		return $query->num_rows();
	}

	public function to_ean13($ref){
		if(strlen($ref) > 12)
		  	$ref = substr($ref,0,12);

		$ref = str_pad($ref,12,'0',STR_PAD_LEFT);// 12 for the meantime
		$sum = 0;
		$temp = str_split($ref);

		foreach ($temp as $key => $value) {
			if($key%2 == 0)
				$sum+=$value * 1;
			else
				$sum+=$value*3;
		}

		$ref.=(10-($sum%10))%10;
		return $ref;
	}


	function db_start(){
		$this->db->trans_start();
	}
	function db_end(){
		$this->db->trans_complete();
	}

	public function get_refs($trans_type, $id = "")
	{
		$where_array['trans_type'] = $trans_type;
		if ($id != "")
			$where_array['id'] = $id;

		$query = $this->db->get_where('refs',$where_array);
		unset($where_array);
		return $query->result();
	}

	public function get_rate($stock_id)
	{
		$this->db->select('rate');
		$this->db->from('tax_types');
		$this->db->join('stock_masters','stock_masters.tax_type_id = tax_types.id');
		$this->db->where('stock_id',$stock_id);
		
		$query = $this->db->get();

		return $query->result();

	}

}

/* End of file gl_bank_model.php */
/* Location: ./applications/models/general_ledger/gl_bank_model.php */