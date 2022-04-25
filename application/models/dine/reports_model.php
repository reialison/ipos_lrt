<?php
class Reports_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_logs($user_id=null,$args=array(),$limit=0)
	{
		$this->db->select('
			logs.*,
			users.username,users.fname,users.mname,users.lname,users.suffix
			');
		$this->db->from('logs');
		$this->db->join('users','logs.user_id = users.id','left');
		if (!is_null($user_id)) {
			if (is_array($user_id))
				$this->db->where_in('logs.user_id',$user_id);
			else
				$this->db->where('logs.user_id',$user_id);
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
		$this->db->order_by('logs.datetime desc');
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
	public function get_dtr($from, $to)
	{
		$this->db->select("logs.*, users.username, users.fname, users.mname, users.lname");
		$this->db->from("logs");
		$this->db->join("users", "logs.user_id = users.id");
		$this->db->where("logs.datetime >=", $from);
		$this->db->where("logs.datetime <=", $to);
		$this->db->where_in("type", array("login", "logout"));
		$this->db->order_by("logs.datetime ASC");
		$q = $this->db->get();
		// echo $this->db->last_query();
		return $q->result();
	}
	public function get_total_hours($from, $to)
	{
		// $this->db->select("logs.action,time_to_sec(timediff(max(datetime), min(datetime) )) / 3600");
		// $this->db->from("logs");
		// $this->db->join("users", "logs.user_id = users.id");
		// $this->db->where("datetime >=", $from);
		// $this->db->where("datetime <=", $to);
		// $this->db->where_in("type", array("login", "logout"));
		// $this->db->group_by("DATE_FORMAT(datetime,'%Y-%m-%d'), user_id");
		// $q = $this->db->get();
		$sql = "SELECT `logs`.`action`, users.fname, users.mname, users.lname,
							  time_to_sec(timediff(max(logs.datetime), min(logs.datetime) )) / 3600 as time_to_sec
				FROM (`logs`) 
				JOIN `users` ON `logs`.`user_id` = `users`.`id` 
				WHERE `logs`.`datetime` >= '".$from."' AND `logs`.`datetime` <= '".$to."' 
				AND `type` IN ('login', 'logout') 
				GROUP BY DATE_FORMAT(`logs`.`datetime`, '%Y-%m-%d'), `user_id` 
				ORDER BY `logs`.`action` ASC";
		$q = $this->db->query($sql);
		// echo $this->db->last_query();
		return $q->result_array();
	}

	public function get_monthly_breakdown($year){
		if(empty($year)){
			
			$year = date('Y');
		}
		// echo $year;die();
		$sql = 'SELECT MAIN.year_, MAIN.month_,
			MAIN.trans_ref,
			MAIN.trans_zero_rated,
			MAIN.vat_exempt,
			MAIN.vatable_sales,

			COALESCE ( MENU.menu_sum, 0 ) + COALESCE ( MENU_MOD.modifier_sum, 0 ) + COALESCE ( ITEM.item_sum, 0 ) + MAIN.total_charge AS gross 
			FROM
				(
				SELECT
				MAX(trans_ref) as trans_ref,
				YEAR(ts.datetime) AS year_,
				MONTH(ts.datetime) AS month_,
				ts.sales_id AS sales_id,
				COALESCE ( sum( tsz.amount ), 0 ) AS zero_rated,
				CONCAT( YEAR(ts.datetime), " ", MONTH(ts.datetime ) ) AS trans_date,
				sum( tsd_no_tax.amount ) AS no_tax_disc,
				sum( tsd_tax.amount ) AS tax_disc,
				COALESCE ( sum( tsc.amount ), 0 ) AS total_charge,
				COALESCE ( SUM( tsn.amount ), 0 ) AS trans_no_tax,
				COALESCE ( sum( tsz.amount ), 0 ) AS trans_zero_rated,
				sum( ts.total_amount ) AS net,
				(
				(
				COALESCE ( sum( ts.total_amount ), 0 ) - COALESCE ( sum( tsc.amount ), 0 ) - COALESCE ( SUM( tsl.amount ), 0 ) 
				) + COALESCE ( SUM( tsd_no_tax.amount ), 0 ) 
				) - ( COALESCE ( SUM( tst.amount ), 0 ) + COALESCE ( SUM( tsn.amount ), 0 ) ) AS vatable_sales,
				COALESCE ( SUM( tsn.amount ), 0 ) - COALESCE ( sum( tsz.amount ), 0 ) - COALESCE ( sum( tsd_no_tax.amount ), 0 ) AS vat_exempt 
				
			FROM
				trans_sales ts
				LEFT JOIN trans_sales_zero_rated tsz ON ts.sales_id = tsz.sales_id
				LEFT JOIN trans_sales_charges tsc ON ts.sales_id = tsc.sales_id
				LEFT JOIN trans_sales_discounts tsd_no_tax ON ts.sales_id = tsd_no_tax.sales_id 
				AND tsd_no_tax.no_tax = 1
				LEFT JOIN trans_sales_discounts tsd_tax ON ts.sales_id = tsd_tax.sales_id 
				AND tsd_tax.no_tax = 0
				LEFT JOIN trans_sales_local_tax tsl ON ts.sales_id = tsl.sales_id
				LEFT JOIN trans_sales_no_tax tsn ON ts.sales_id = tsn.sales_id
				LEFT JOIN trans_sales_tax tst ON ts.sales_id = tst.sales_id 
			WHERE
				ts.inactive = 0 
				AND ts.trans_ref IS NOT NULL 
				AND void_ref IS NULL
				
				
				GROUP BY
				YEAR ( ts.datetime ),
				MONTH ( ts.datetime ) 
				
				) MAIN
				LEFT JOIN (
				SELECT
					CONCAT( YEAR(ts.datetime), " ", MONTH(ts.datetime) ) AS trans_date,
					COALESCE ( SUM( tsm.price * tsm.qty ), 0 ) AS menu_sum 
				FROM
					trans_sales ts
					LEFT JOIN trans_sales_menus tsm ON ts.sales_id = tsm.sales_id 
				WHERE
					ts.inactive = 0 
					AND ts.trans_ref IS NOT NULL 
					AND void_ref IS NULL 
				GROUP BY
					trans_date 
				) MENU ON MAIN.trans_date = MENU.trans_date
				LEFT JOIN (
				SELECT
					CONCAT( YEAR ( ts.datetime ), " ", MONTH ( ts.datetime ) ) AS trans_date,
					COALESCE ( SUM( tsmm.price * tsmm.qty ), 0 ) AS modifier_sum 
				FROM
					trans_sales ts
					LEFT JOIN trans_sales_menu_modifiers tsmm ON ts.sales_id = tsmm.sales_id 
				WHERE
					ts.inactive = 0 
					AND ts.trans_ref IS NOT NULL 
					AND void_ref IS NULL 
				GROUP BY
					trans_date 
				) MENU_MOD ON MAIN.trans_date = MENU_MOD.trans_date
				LEFT JOIN (
				SELECT
					CONCAT( YEAR ( ts.datetime ), " ", MONTH ( ts.datetime ) ) AS trans_date,
					COALESCE ( SUM( tsi.price * tsi.qty ), 0 ) AS item_sum 
				FROM
					trans_sales ts
					LEFT JOIN trans_sales_items tsi ON ts.sales_id = tsi.sales_id 
				WHERE
					ts.inactive = 0 
					AND ts.trans_ref IS NOT NULL 
					AND void_ref IS NULL 
				GROUP BY
				trans_date 
			) ITEM ON MAIN.trans_date = ITEM.trans_date
			';

// echo $sql;die();
		$q = $this->db->query($sql);
		// echo $this->db->last_query();
		return $q->result_array();
	}

	/**get trans sales via credit card - join menus and items for vatable and non vatables sales of wine and liquor **/
	public function get_trans_sales_via_credit_card($args = array()){
		$from_date_range = $to_date_range = NULL;
		if(!empty($args)){
			$arg_raw = explode(" to ", $args['year']);
			$from_date_range = date('Y-m-d H:i:s', strtotime($arg_raw[0]));
			$to_date_range = date('Y-m-d H:i:s', strtotime($arg_raw[1]));

		}
		// echo $from_date_range;die();
		$sql = 'SELECT
	MAIN.card_number,
	MAIN.trans_ref,
	MAIN.num_discount,
	IF(MAIN.total_discount> 0 ,MAIN.total_discount + (
	(
	(
	COALESCE ( WSL.wl_amount, WSL.wl_amount, 0 ) + COALESCE ( FSLm.fl_amount, FSLm.fl_amount, 0 ) + COALESCE ( FSLi.fli_amount, FSLi.fli_amount, 0 ) 
	) / MAIN.guest / 1.12 *.12 
	) * MAIN.num_discount 
	) , 0) AS trans_discount,
	IF(MAIN.num_discount > 0 , MAIN.service_charge / MAIN.num_discount , MAIN.service_charge) AS service_charge,
IF
	( MAIN.num_discount > 0, MAIN.vat / MAIN.num_discount, MAIN.vat ) AS vat,
	MAIN.guest,
	MAIN.disc_rate,
	COALESCE ( WSL.wl_amount, WSL.wl_amount, 0 ) AS wl_amount,
	COALESCE ( FSLm.fl_amount, FSLm.fl_amount, 0 ) AS fl_amount,
	COALESCE ( FSLi.fli_amount, FSLi.fli_amount, 0 ) AS fli_amount,
IF
	(
	WSL.wl_amount > 0,
	( WSL.wl_amount / 1.12 ) - ( ( WSL.wl_amount / MAIN.guest / 1.12 ) * MAIN.num_discount ),
	0 
	) AS wl_vatable,
		 ( ( WSL.wl_amount/ MAIN.guest / 1.12 ) - ( ( WSL.wl_amount / MAIN.guest / 1.12 ) *  (disc_rate/100)    ) ) * MAIN.num_discount  as wl_non_vatable,

	(
IF
	(
	FSLm.fl_amount > 0,
	( ( FSLm.fl_amount / 1.12 ) - ( ( FSLm.fl_amount / MAIN.guest / 1.12 ) * MAIN.num_discount ) ),
	0 
	) +
IF
	(
	FSLi.fli_amount > 0,
	( ( FSLi.fli_amount / 1.12 ) - ( ( FSLi.fli_amount / MAIN.guest / 1.12 ) * MAIN.num_discount ) ),
	0 
	) 
	) AS fl_vatable,
IF
	(
	FSLm.fl_amount > 0,
	 ( ( FSLm.fl_amount/ MAIN.guest / 1.12 ) - ( ( FSLm.fl_amount / MAIN.guest / 1.12 ) *  (disc_rate/100)    ) ) * MAIN.num_discount,
	0 
	)
	+
IF
	(
	FSLi.fli_amount > 0,
	 ( (FSLi.fli_amount/ MAIN.guest / 1.12 ) - ( ( FSLi.fli_amount / MAIN.guest / 1.12 ) *  (disc_rate/100)    ) ) * MAIN.num_discount,
	0 
	) 
	AS fl_non_vatable 
FROM
	(
SELECT
	tsp.card_number,
	ts.trans_ref,
	ts.sales_id,
	COUNT( tsd.sales_id ) AS num_discount,
	SUM( tsd.amount ) AS total_discount,
	tsd.disc_rate AS disc_rate,
	SUM( tsc.amount ) AS service_charge,
	SUM( tst.amount ) AS vat,
IF
	( ts.guest > 0, ts.guest, 1 ) AS guest 
FROM
	trans_sales ts 
	LEFT JOIN trans_sales_charges tsc ON ts.sales_id = tsc.sales_id
	LEFT JOIN trans_sales_discounts tsd ON ts.sales_id = tsd.sales_id 
	LEFT JOIN trans_sales_tax tst ON ts.sales_id = tst.sales_id
	LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
	
WHERE
	tsp.payment_type = "credit" ';
	
	if(!empty($from_date_range)){
		$sql .= " AND ts.datetime BETWEEN '".$from_date_range. "' and '".$to_date_range."'";
	}
$sql .= ' GROUP BY
	ts.trans_ref 
	) MAIN
	LEFT JOIN (
	SELECT
		ts.sales_id,
		tsp.card_number,
		ts.trans_ref,
		SUM( tsi.price * tsi.qty ) AS wl_amount 
	FROM
		trans_sales ts
		LEFT JOIN trans_sales_zero_rated tsz ON ts.sales_id = tsz.sales_id 
		LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
		LEFT JOIN trans_sales_items tsi ON ts.sales_id = tsi.sales_id
		LEFT JOIN items i ON i.item_id = tsi.item_id 
	WHERE
		tsp.payment_type = "credit" 
		AND i.cat_id = "6" 
	GROUP BY
		ts.trans_ref 
	) WSL ON MAIN.sales_id = WSL.sales_id
	LEFT JOIN (
	SELECT
		ts.sales_id,
		tsp.card_number,
		ts.trans_ref,
		SUM( tsi.price * tsi.qty ) AS fli_amount 
	FROM
		trans_sales ts
		LEFT JOIN trans_sales_zero_rated tsz ON ts.sales_id = tsz.sales_id 
		LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
		LEFT JOIN trans_sales_items tsi ON ts.sales_id = tsi.sales_id
		LEFT JOIN items i ON i.item_id = tsi.item_id 
	WHERE
		tsp.payment_type = "credit" 
		AND i.cat_id != "6" 
	GROUP BY
		ts.trans_ref 
	) FSLi ON MAIN.sales_id = FSLi.sales_id
	LEFT JOIN (
	SELECT
		ts.sales_id,
		tsp.card_number,
		ts.trans_ref,
		SUM( tsm.price * tsm.qty ) AS fl_amount 
	FROM
		trans_sales ts
		LEFT JOIN trans_sales_zero_rated tsz ON ts.sales_id = tsz.sales_id 
		LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
		LEFT JOIN trans_sales_menus tsm ON ts.sales_id = tsm.sales_id 
	WHERE
		tsp.payment_type = "credit" 
	GROUP BY
	ts.trans_ref 
) FSLm ON MAIN.sales_id = FSLm.sales_id
			';

// echo $sql;die();
		$q = $this->db->query($sql);
		// echo $this->db->last_query();
		return $q->result_array();
	}

	/**get trans sales via credit card - join menus and items for vatable and non vatables sales of wine and liquor **/
	public function get_trans_sales_per_cashier($args = array(),$is_payment = false){
		$date = $cashier = NULL;
		// echo "<pre>",print_r($args),"</pre>";die();
		// $args['date'] = '2019-09-03';
		// $args['cashier'] = '1';
		if(!empty($args)){
			$from_date_range = date('Y-m-d H:i:s', strtotime($args['date']));
			$to_date_range = date('Y-m-d 23:59:59', strtotime($args['date']));
			// $date = date('Y-m-d',strtotime($args['date']));
			$cashier = $args['cashier'];

		}
		// echo $from_date_range;die();
		$sql = 'SELECT
			MAIN.payment_type,
			MAIN.card_type,
			MAIN.card_number,
			MAIN.trans_ref,
			MAIN.num_discount,
			MAIN.user_id,
				SUM(IF( trans_sales_no_tax.amount > 0,
			COALESCE(FSLm.fl_amount,0) +  COALESCE(FSLi.fli_amount,0) + COALESCE(WSL.wl_amount,0) - trans_sales_no_tax.amount - IF(MAIN.num_discount > 0 , MAIN.service_charge / MAIN.num_discount , MAIN.service_charge),0)) as vat_exempt,
			SUM(IF(MAIN.total_discount> 0 ,MAIN.total_discount + (
			(
			(
			COALESCE ( WSL.wl_amount, WSL.wl_amount, 0 ) + COALESCE ( FSLm.fl_amount, FSLm.fl_amount, 0 ) + COALESCE ( FSLi.fli_amount, FSLi.fli_amount, 0 ) 
			) / MAIN.guest / 1.12 *.12 
			) * MAIN.num_discount 
			) , 0)) AS trans_discount,
			SUM(IF(MAIN.num_discount > 0 , MAIN.service_charge / MAIN.num_discount , MAIN.service_charge)) AS service_charge,
		SUM(IF
			( MAIN.num_discount > 0, MAIN.vat / MAIN.num_discount, MAIN.vat )) AS vat,
			MAIN.guest,
			MAIN.disc_rate,
			SUM(COALESCE ( WSL.wl_amount, WSL.wl_amount, 0 )) AS wl_amount,
			SUM(COALESCE ( FSLm.fl_amount, FSLm.fl_amount, 0 )) AS fl_amount,
			SUM(COALESCE ( FSLi.fli_amount, FSLi.fli_amount, 0 )) AS fli_amount,
			COALESCE(SUM(IF
			(
			WSL.wl_amount > 0,
			( WSL.wl_amount / 1.12 ) - ( ( WSL.wl_amount / MAIN.guest / 1.12 ) * MAIN.num_discount ),
			0 
			)),0) AS wl_vatable,
			COALESCE(SUM(( ( WSL.wl_amount/ MAIN.guest / 1.12 ) - ( ( WSL.wl_amount / MAIN.guest / 1.12 ) *  (disc_rate/100)    ) ) * MAIN.num_discount),0)  as wl_non_vatable,

		COALESCE(SUM(
		IF
			(
			(FSLm.fl_amount > 0 AND ( MAIN.is_no_tax = 0 or MAIN.is_no_tax is null ) ),
			( (FSLm.fl_amount / 1.12 ) - ( (FSLm.fl_amount / MAIN.guest / 1.12 ) * (COALESCE(disc_rate,0)/100) ) ),
			0 
			)),0)
		+
		COALESCE(SUM(IF
			(
			FSLi.fli_amount > 0 AND ( MAIN.is_no_tax = 0 or MAIN.is_no_tax is null ) ,
			( ( FSLi.fli_amount / 1.12 ) - ( (FSLi.fli_amount / MAIN.guest / 1.12 ) * (COALESCE(disc_rate,0)/100) )

				),
			0 
			) ),0
			) AS fl_vatable,
		COALESCE(SUM(IF
			(
			FSLm.fl_amount > 0 AND ( MAIN.is_no_tax = 1),
			 ( ( FSLm.fl_amount/ MAIN.guest / 1.12 ) - ( ( FSLm.fl_amount / MAIN.guest / 1.12 ) *  (disc_rate/100)    ) ) * MAIN.num_discount,
			0 
			)
			+
		IF
			(
			FSLi.fli_amount > 0 AND ( MAIN.is_no_tax = 1),
			 ( (FSLi.fli_amount/ MAIN.guest / 1.12 ) - ( ( FSLi.fli_amount / MAIN.guest / 1.12 ) *  (disc_rate/100)    ) ) * MAIN.num_discount,
			0 
			) ),0)
			AS fl_non_vatable ,
			SUM(
		IF
			(
			FSLm.fl_amount > 0,
			( ( FSLm.fl_amount / 1.12 ) - ( ( FSLm.fl_amount / MAIN.guest / 1.12 ) * MAIN.num_discount ) ) * .12,
			0 
			) +
		IF
			(
			FSLi.fli_amount > 0,
			( ( FSLi.fli_amount / 1.12 ) - ( ( FSLi.fli_amount / MAIN.guest / 1.12 ) * MAIN.num_discount ) ) * .12,
			0 
			) 
			)  AS fl_tax,
			SUM(IF
			(
			WSL.wl_amount > 0,
			(( WSL.wl_amount / 1.12 ) - ( ( WSL.wl_amount / MAIN.guest / 1.12 ) * MAIN.num_discount )) * .12,
			0 
			)) AS wl_tax
		FROM
			(
		SELECT
			tsp.card_number,
			tsp.payment_type,
			tsp.card_type,
			ts.trans_ref,
			ts.sales_id,
			ts.user_id,
			ts.total_amount,
			COUNT( tsd.sales_id ) AS num_discount,
			SUM( tsd.amount ) AS total_discount,
			tsd.disc_rate AS disc_rate,
			COALESCE(SUM( tsc.amount ),0) AS service_charge,
			SUM( tst.amount ) AS vat,
			rd.no_tax as is_no_tax,
		IF
			( ts.guest > 0, ts.guest, 1 ) AS guest 
		FROM
			trans_sales ts 
			LEFT JOIN trans_sales_charges tsc ON ts.sales_id = tsc.sales_id
			LEFT JOIN trans_sales_discounts tsd ON ts.sales_id = tsd.sales_id 
			LEFT JOIN trans_sales_tax tst ON ts.sales_id = tst.sales_id
			LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
		  LEFT JOIN receipt_discounts rd ON rd.disc_id = tsd.disc_id 
			
		WHERE
			ts.user_id = "'.$cashier.'" ';
	
	if(!empty($from_date_range)){
		$sql .= " AND ts.datetime BETWEEN '".$from_date_range. "' and '".$to_date_range."'";
	}
		$sql .= ' GROUP BY
			ts.trans_ref 
			) MAIN
			LEFT JOIN (
			SELECT
				ts.sales_id,
				tsp.card_number,
				ts.trans_ref,
				SUM( tsi.price * tsi.qty ) AS wl_amount 
			FROM
				trans_sales ts
				LEFT JOIN trans_sales_zero_rated tsz ON ts.sales_id = tsz.sales_id 
				LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
				LEFT JOIN trans_sales_items tsi ON ts.sales_id = tsi.sales_id
				LEFT JOIN items i ON i.item_id = tsi.item_id 
			WHERE
				 i.cat_id = "6" 
			GROUP BY
				ts.trans_ref 
			) WSL ON MAIN.sales_id = WSL.sales_id
			LEFT JOIN (
			SELECT
				ts.sales_id,
				tsp.card_number,
				ts.trans_ref,
				SUM( tsi.price * tsi.qty ) AS fli_amount 
			FROM
				trans_sales ts
				LEFT JOIN trans_sales_zero_rated tsz ON ts.sales_id = tsz.sales_id 
				LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
				LEFT JOIN trans_sales_items tsi ON ts.sales_id = tsi.sales_id
				LEFT JOIN items i ON i.item_id = tsi.item_id 
			WHERE
				i.cat_id != "6" 
			GROUP BY
				ts.trans_ref 
			) FSLi ON MAIN.sales_id = FSLi.sales_id
			LEFT JOIN (
			SELECT
				ts.sales_id,
				tsp.card_number,
				ts.trans_ref,
				SUM( tsm.price * tsm.qty ) AS fl_amount 
			FROM
				trans_sales ts
				LEFT JOIN trans_sales_zero_rated tsz ON ts.sales_id = tsz.sales_id 
				LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
				LEFT JOIN trans_sales_menus tsm ON ts.sales_id = tsm.sales_id 
			
			GROUP BY
			ts.trans_ref 
		) FSLm ON MAIN.sales_id = FSLm.sales_id
			LEFT JOIN trans_sales_no_tax on MAIN.sales_id = trans_sales_no_tax.sales_id

			';

		if(!$is_payment){
			$sql .= "		GROUP BY MAIN.user_id";
		}else{
			$sql .= " GROUP BY MAIN.payment_type, MAIN.card_type";
		}

// echo $sql;die();
		$q = $this->db->query($sql);
		// echo $this->db->last_query();die();
		return $q->result_array();
	}


  /**06242019 text file for bir csv @jx***/

public function get_bir_trans_sales($args = array()){
		$date = $cashier = NULL;
		// echo "<pre>",print_r($args),"</pre>";die();
		$date  = date('Y-m-d');
		if(!empty($args)){
			$from_date_range = date('Y-m-d H:i:s', strtotime($args['date']));
			$to_date_range = date('Y-m-d 23:59:59', strtotime($args['date']));
			// $date = date('Y-m-d',strtotime($args['date']));
			$cashier = $args['cashier'];

		}
		// echo $from_date_range;die();
		$sql = 'SELECT 
			DATE_FORMAT(MAIN.trans_sales_date,"%Y/%m/%d") as trans_date,
	  	CONCAT("\'",MAIN.trans_ref,"\'") as trans_ref,
			FORMAT(ROUND(MAIN.vatable_sales,2),2) as vatable_sales,
			FORMAT(ROUND(MAIN.vat_exempt,2),2) as vat_exempt,
			FORMAT(ROUND(MAIN.trans_zero_rated,2),2) as trans_zero_rated,
			FORMAT(ROUND(MAIN.total_tax,2),2)  as vat,
			FORMAT(ROUND(MAIN.no_tax_disc,2),2) as total_discount,
			FORMAT(ROUND(MAIN.total_charge,2),2) as total_charge,
			FORMAT(ROUND(MENU.menu_sum + ITEM.item_sum+ MENU_MOD.modifier_sum - COALESCE(MAIN.no_tax_disc,0) - COALESCE(	IF
	(
	MAIN.net > 0,
	 ( ( (MENU.menu_sum + ITEM.item_sum+ MENU_MOD.modifier_sum) / MAIN.guest / 1.12 ) *.12 ) * MAIN.num_discount,
	0 
	),0),2),2) as net
		
		
			FROM
				(
				SELECT
				ts.trans_ref as trans_ref,
				ts.datetime as trans_sales_date,

				ts.guest,
				ts.sales_id AS sales_id,
				COALESCE ( sum( tsz.amount ), 0 ) AS zero_rated,
				CONCAT( YEAR(ts.datetime), " ", MONTH(ts.datetime ) ) AS trans_date,
				sum( tsd_no_tax.amount ) AS no_tax_disc,
				sum( tsd_tax.amount ) AS tax_disc,
				tsd_no_tax.disc_rate,
				COUNT( tsd_no_tax.sales_id ) AS num_discount,
				COALESCE ( sum( tsc.amount ), 0 ) AS total_charge,
				COALESCE ( sum( tst.amount ), 0 ) AS total_tax,
				COALESCE ( SUM( tsn.amount ), 0 ) AS trans_no_tax,
				COALESCE ( sum( tsz.amount ), 0 ) AS trans_zero_rated,
				sum( ts.total_amount ) AS net,
				(
				(
				COALESCE ( sum( ts.total_amount ), 0 ) - COALESCE ( sum( tsc.amount ), 0 ) - COALESCE ( SUM( tsl.amount ), 0 ) 
				) + COALESCE ( SUM( tsd_no_tax.amount ), 0 ) 
				) - ( COALESCE ( SUM( tst.amount ), 0 ) + COALESCE ( SUM( tsn.amount ), 0 ) ) AS vatable_sales,
				COALESCE ( SUM( tsn.amount ), 0 ) - COALESCE ( sum( tsz.amount ), 0 ) - COALESCE ( sum( tsd_no_tax.amount ), 0 ) AS vat_exempt 
				
			FROM
				trans_sales ts
				LEFT JOIN trans_sales_zero_rated tsz ON ts.sales_id = tsz.sales_id
				LEFT JOIN trans_sales_charges tsc ON ts.sales_id = tsc.sales_id
				LEFT JOIN trans_sales_discounts tsd_no_tax ON ts.sales_id = tsd_no_tax.sales_id 
				AND tsd_no_tax.no_tax = 1
				LEFT JOIN trans_sales_discounts tsd_tax ON ts.sales_id = tsd_tax.sales_id 
				AND tsd_tax.no_tax = 0
				LEFT JOIN trans_sales_local_tax tsl ON ts.sales_id = tsl.sales_id
				LEFT JOIN trans_sales_no_tax tsn ON ts.sales_id = tsn.sales_id
				LEFT JOIN trans_sales_tax tst ON ts.sales_id = tst.sales_id 
			WHERE
				ts.inactive = 0 
				AND ts.trans_ref IS NOT NULL 
				AND void_ref IS NULL
				
				
				GROUP BY
				ts.trans_ref

				
				) MAIN
				LEFT JOIN (
				SELECT
					ts.trans_ref,
					ts.sales_id,
					CONCAT( YEAR(ts.datetime), " ", MONTH(ts.datetime) ) AS trans_date,
					COALESCE ( SUM( tsm.price * tsm.qty ), 0 ) AS menu_sum 
				FROM
					trans_sales ts
					LEFT JOIN trans_sales_menus tsm ON ts.sales_id = tsm.sales_id 
				WHERE
					ts.inactive = 0 
					AND ts.trans_ref IS NOT NULL 
					AND void_ref IS NULL 
				GROUP BY
					ts.trans_ref
				) MENU ON MAIN.sales_id = MENU.sales_id
				LEFT JOIN (
				SELECT
				  ts.trans_ref,
					ts.sales_id,
					CONCAT( YEAR ( ts.datetime ), " ", MONTH ( ts.datetime ) ) AS trans_date,
					COALESCE ( SUM( tsmm.price * tsmm.qty ), 0 ) AS modifier_sum 
				FROM
					trans_sales ts
					LEFT JOIN trans_sales_menu_modifiers tsmm ON ts.sales_id = tsmm.sales_id 
				WHERE
					ts.inactive = 0 
					AND ts.trans_ref IS NOT NULL 
					AND void_ref IS NULL 
				GROUP BY
					ts.trans_ref
				) MENU_MOD ON MAIN.sales_id = MENU_MOD.sales_id
				LEFT JOIN (
				SELECT
				  ts.trans_ref,
					ts.sales_id,
					CONCAT( YEAR ( ts.datetime ), " ", MONTH ( ts.datetime ) ) AS trans_date,
					COALESCE ( SUM( tsi.price * tsi.qty ), 0 ) AS item_sum 
				FROM
					trans_sales ts
					LEFT JOIN trans_sales_items tsi ON ts.sales_id = tsi.sales_id 
				WHERE
					ts.inactive = 0 
					AND ts.trans_ref IS NOT NULL 
					AND void_ref IS NULL 
				GROUP BY
				ts.trans_ref
			) ITEM ON MAIN.sales_id = ITEM.sales_id
			WHERE DATE(MAIN.trans_sales_date) = "'.$date.'";
			';

// echo $sql;die();
		$q = $this->db->query($sql);
		// echo $this->db->last_query();
		return $q->result_array();
	}


    /**06252019 text file for bir csv @jx***/

	public function get_inventory_movement($args = array()){
		$date = $cashier = NULL;
		// echo "<pre>",print_r($args),"</pre>";die();
		$date  = date('Y-m-d');
		if(!empty($args)){
			$from_date_range = date('Y-m-d H:i:s', strtotime($args['date']));
			$to_date_range = date('Y-m-d 23:59:59', strtotime($args['date']));
			// $date = date('Y-m-d',strtotime($args['date']));
			$cashier = $args['cashier'];

		}
		// echo $from_date_range;die();
		$sql = 'SELECT trans_ref, `loc`.loc_code, item_id, qty, uom, cost , reg_date from item_moves im LEFT JOIN locations loc ON `im`.loc_id = `loc`.loc_id WHERE im.inactive="0" AND
			DATE(reg_date) = "'.$date.'"';

// echo $sql;die();
		$q = $this->db->query($sql);
		// echo $this->db->last_query();
		return $q->result_array();
	}

	 /**09022019 get_receipt_discounts @jx***/

	public function get_receipt_discounts($args = array()){
		$date = $cashier = NULL;
		// echo "<pre>",print_r($args),"</pre>";die();
		$date  = date('Y-m-d');
		if(!empty($args)){
			$from_date_range = date('Y-m-d H:i:s', strtotime($args['date']));
			$to_date_range = date('Y-m-d 23:59:59', strtotime($args['date']));
			// $date = date('Y-m-d',strtotime($args['date']));
			$cashier = $args['cashier'];

		}
		// echo $from_date_range;die();
		$sql = 'SELECT disc_name,disc_id,no_tax ,disc_code FROM receipt_discounts where inactive=0 order by disc_id';

		// echo $sql;die();
		$q = $this->db->query($sql);
		$result_raw = $q->result_array();
		$row_result = array();

		if(!empty($result_raw)){
			foreach($result_raw as $res_raw){
				$row_result[$res_raw['disc_id']] = $res_raw;
			}
			
		}
		// echo $this->db->last_query();
		return $row_result; 
		
	}


	public function get_receipt_discount_sales($args = array(),$per_trans = false){
		$date = $cashier = NULL;
		// echo "<pre>",print_r($args),"</pre>";die();
		$date  = date('Y-m-d');
		if(!empty($args)){
			$from_date_range = date('Y-m-d H:i:s', strtotime($args['date']));
			$to_date_range = date('Y-m-d 23:59:59', strtotime($args['date']));
			// $date = date('Y-m-d',strtotime($args['date']));
			$cashier = $args['cashier'];

		}
		// echo $from_date_range;die();
		$sql = 'SELECT
	receipt_discounts.disc_name,
	receipt_discounts.disc_id,
	COALESCE((SELECT SUM(trans_sales_discounts.amount) from trans_sales_discounts 	LEFT JOIN trans_sales ON trans_sales_discounts.sales_id = trans_sales.sales_id  WHERE 
 trans_sales.datetime BETWEEN "'.$from_date_range. '" and "'.$to_date_range.'" ';


 		if(!empty($cashier)){
 			$sql .= ' AND(trans_sales.user_id = "'.$cashier.'" ) ';
 		}

 		$sql .=' AND trans_sales_discounts.disc_id = receipt_discounts.disc_id ) , 0
 ) as disc_amount FROM receipt_discounts WHERE inactive=0 
			 ';

		if(!$per_trans){
			 $sql .= ' GROUP BY receipt_discounts.disc_id';
		}else{
			$sql .= ' GROUP BY trans_sales.trans_ref , receipt_discounts.disc_id';
		}



		$q = $this->db->query($sql);
		$result_raw = $q->result_array();
		$result_array = array();

		if(!empty($result_raw)){
			foreach($result_raw as $res_raw){
				$result_array[$res_raw['disc_id']] = $res_raw;
			}
			
		}
		// echo $this->db->last_query();
		return $result_array;
		
	}

	public function get_receipt_discount_sales_per_payment($args = array(),$per_trans = false){
		$date = $cashier = NULL;
		// $args['date'] = '2019-09-03';
		// $args['cashier'] = '1';
		// echo "<pre>",print_r($args),"</pre>";die();
		$date  = date('Y-m-d');
		if(!empty($args)){
			$from_date_range = date('Y-m-d H:i:s', strtotime($args['date']));
			$to_date_range = date('Y-m-d 23:59:59', strtotime($args['date']));
			// $date = date('Y-m-d',strtotime($args['date']));
			$cashier = $args['cashier'];

		}
		// echo $from_date_range;die();
		$sql = 'SELECT trans_sales.trans_ref,
			COALESCE(SUM(trans_sales_discounts.amount),0) as disc_amount, trans_sales_discounts.disc_id,if(trans_sales_payments.payment_type is not null, CONCAT(trans_sales_payments.payment_type,"-",COALESCE(trans_sales_payments.card_type,"")) , trans_sales_payments.payment_type) as trans_payment_type,trans_sales.datetime
		FROM
			trans_sales_payments
			JOIN trans_sales ON  trans_sales.sales_id = trans_sales_payments.sales_id

			LEFT JOIN trans_sales_discounts ON trans_sales.sales_id = trans_sales_discounts.sales_id 
		AND trans_sales.datetime BETWEEN "'.$from_date_range.'" 
			AND "'.$to_date_range.'" ';

		if(!empty($cashier)){

			$sql .=' AND trans_sales.user_id = "'.$cashier.'" ';
		}


		$sql .=' AND trans_sales.inactive="0" AND trans_sales_payments.payment_type !="cash" ';

		if(!$per_trans){

			$sql .=' GROUP BY
				trans_sales_discounts.disc_id,
				trans_sales_payments.payment_type';

		}else{
			$sql .=' GROUP BY
				trans_sales.trans_ref,
				trans_sales_discounts.disc_id,
				trans_sales_payments.payment_type';
		}
			$sql .='	
				HAVING 
				disc_amount > 0
				 ';

// echo $sql;die();
		$q = $this->db->query($sql);
		$result_raw = $q->result_array();
		$result_array = array();

		if(!empty($result_raw)){
			foreach($result_raw as $res_raw){
				$result_array[$res_raw['trans_ref']][$res_raw['trans_payment_type']][$res_raw['disc_id']] = $res_raw;
			}
			
		}
		// echo "<pre>",print_r($result_array),"</pre>";die();
		// echo $this->db->last_query();
		return $result_array;
		
	}

	public function get_user($id)
	{
		$this->db->select("*");
		$this->db->from("users");
		$this->db->where("users.id", $id);
		$q = $this->db->get();
		$result = $q->result();


		if(isset($result[0])){
			return $result[0]->fname." ".$result[0]->lname;
		}else{
			return null;
		}	
	}

	/**get trans sales of credit card - join menus and items for vatable and non vatables sales of wine and liquor **/
	public function get_trans_sales_credit_charges($args = array(),$is_payment = false){
		$date = $cashier = NULL;
		// echo "<pre>",print_r($args),"</pre>";die();
		// $args['date'] = '2019-09-03';
		// $args['cashier'] = '1';
		if(!empty($args)){
			$from_date_range = date('Y-m-d H:i:s', strtotime($args['date']));
			$to_date_range = date('Y-m-d 23:59:59', strtotime($args['date']));
			// $date = date('Y-m-d',strtotime($args['date']));
			$cashier = $args['cashier'];

		}
		// echo $from_date_range;die();
		$sql = 'SELECT
			MAIN.payment_type,
			MAIN.card_type,
			MAIN.card_number,
			MAIN.trans_ref,
			MAIN.num_discount,
			MAIN.user_id,
				SUM(IF( trans_sales_no_tax.amount > 0,
			COALESCE(FSLm.fl_amount,0) +  COALESCE(FSLi.fli_amount,0) + COALESCE(WSL.wl_amount,0) - trans_sales_no_tax.amount - IF(MAIN.num_discount > 0 , MAIN.service_charge / MAIN.num_discount , MAIN.service_charge),0)) as vat_exempt,
			SUM(IF(MAIN.total_discount> 0 ,MAIN.total_discount + (
			(
			(
			COALESCE ( WSL.wl_amount, WSL.wl_amount, 0 ) + COALESCE ( FSLm.fl_amount, FSLm.fl_amount, 0 ) + COALESCE ( FSLi.fli_amount, FSLi.fli_amount, 0 ) 
			) / MAIN.guest / 1.12 *.12 
			) * MAIN.num_discount 
			) , 0)) AS trans_discount,
			SUM(IF(MAIN.num_discount > 0 , MAIN.service_charge / MAIN.num_discount , MAIN.service_charge)) AS service_charge,
		SUM(IF
			( MAIN.num_discount > 0, MAIN.vat / MAIN.num_discount, MAIN.vat )) AS vat,
			MAIN.guest,
			MAIN.disc_rate,
			SUM(COALESCE ( WSL.wl_amount, WSL.wl_amount, 0 )) AS wl_amount,
			SUM(COALESCE ( FSLm.fl_amount, FSLm.fl_amount, 0 )) AS fl_amount,
			SUM(COALESCE ( FSLi.fli_amount, FSLi.fli_amount, 0 )) AS fli_amount,
			COALESCE(SUM(IF
			(
			WSL.wl_amount > 0,
			( WSL.wl_amount / 1.12 ) - ( ( WSL.wl_amount / MAIN.guest / 1.12 ) * MAIN.num_discount ),
			0 
			)),0) AS wl_vatable,
			COALESCE(SUM(( ( WSL.wl_amount/ MAIN.guest / 1.12 ) - ( ( WSL.wl_amount / MAIN.guest / 1.12 ) *  (disc_rate/100)    ) ) * MAIN.num_discount),0)  as wl_non_vatable,

		COALESCE(SUM(
		IF
			(
			(FSLm.fl_amount > 0 AND ( MAIN.is_no_tax = 0 or MAIN.is_no_tax is null ) ),
			( (FSLm.fl_amount / 1.12 ) - ( (FSLm.fl_amount / MAIN.guest / 1.12 ) * (COALESCE(disc_rate,0)/100) ) ),
			0 
			)),0)
		+
		COALESCE(SUM(IF
			(
			FSLi.fli_amount > 0 AND ( MAIN.is_no_tax = 0 or MAIN.is_no_tax is null ) ,
			( ( FSLi.fli_amount / 1.12 ) - ( (FSLi.fli_amount / MAIN.guest / 1.12 ) * (COALESCE(disc_rate,0)/100) )

				),
			0 
			) ),0
			) AS fl_vatable,
		COALESCE(SUM(IF
			(
			FSLm.fl_amount > 0 AND ( MAIN.is_no_tax = 1),
			 ( ( FSLm.fl_amount/ MAIN.guest / 1.12 ) - ( ( FSLm.fl_amount / MAIN.guest / 1.12 ) *  (disc_rate/100)    ) ) * MAIN.num_discount,
			0 
			)
			+
		IF
			(
			FSLi.fli_amount > 0 AND ( MAIN.is_no_tax = 1),
			 ( (FSLi.fli_amount/ MAIN.guest / 1.12 ) - ( ( FSLi.fli_amount / MAIN.guest / 1.12 ) *  (disc_rate/100)    ) ) * MAIN.num_discount,
			0 
			) ),0)
			AS fl_non_vatable ,
			SUM(
		IF
			(
			FSLm.fl_amount > 0,
			( ( FSLm.fl_amount / 1.12 ) - ( ( FSLm.fl_amount / MAIN.guest / 1.12 ) * MAIN.num_discount ) ) * .12,
			0 
			) +
		IF
			(
			FSLi.fli_amount > 0,
			( ( FSLi.fli_amount / 1.12 ) - ( ( FSLi.fli_amount / MAIN.guest / 1.12 ) * MAIN.num_discount ) ) * .12,
			0 
			) 
			)  AS fl_tax,
			SUM(IF
			(
			WSL.wl_amount > 0,
			(( WSL.wl_amount / 1.12 ) - ( ( WSL.wl_amount / MAIN.guest / 1.12 ) * MAIN.num_discount )) * .12,
			0 
			)) AS wl_tax
		FROM
			(
		SELECT
			tsp.card_number,
			tsp.payment_type,
			tsp.card_type,
			ts.trans_ref,
			ts.sales_id,
			ts.user_id,
			ts.total_amount,
			COUNT( tsd.sales_id ) AS num_discount,
			SUM( tsd.amount ) AS total_discount,
			tsd.disc_rate AS disc_rate,
			COALESCE(SUM( tsc.amount ),0) AS service_charge,
			SUM( tst.amount ) AS vat,
			rd.no_tax as is_no_tax,
		IF
			( ts.guest > 0, ts.guest, 1 ) AS guest 
		FROM
			trans_sales ts 
			LEFT JOIN trans_sales_charges tsc ON ts.sales_id = tsc.sales_id
			LEFT JOIN trans_sales_discounts tsd ON ts.sales_id = tsd.sales_id 
			LEFT JOIN trans_sales_tax tst ON ts.sales_id = tst.sales_id
			LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
		  LEFT JOIN receipt_discounts rd ON rd.disc_id = tsd.disc_id ';
	if(!empty($from_date_range)){
		$sql .= " WHERE ts.datetime BETWEEN '".$from_date_range. "' and '".$to_date_range."'";
	}

	if(!empty($cashier)){

    	$sql.=' AND
			ts.user_id = "'.$cashier.'" ';
	}
	
		$sql .= ' GROUP BY
			ts.trans_ref 
			) MAIN
			LEFT JOIN (
			SELECT
				ts.sales_id,
				tsp.card_number,
				ts.trans_ref,
				SUM( tsi.price * tsi.qty ) AS wl_amount 
			FROM
				trans_sales ts
				LEFT JOIN trans_sales_zero_rated tsz ON ts.sales_id = tsz.sales_id 
				LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
				LEFT JOIN trans_sales_items tsi ON ts.sales_id = tsi.sales_id
				LEFT JOIN items i ON i.item_id = tsi.item_id 
			WHERE
				 i.cat_id = "6" 
			GROUP BY
				ts.trans_ref 
			) WSL ON MAIN.sales_id = WSL.sales_id
			LEFT JOIN (
			SELECT
				ts.sales_id,
				tsp.card_number,
				ts.trans_ref,
				SUM( tsi.price * tsi.qty ) AS fli_amount 
			FROM
				trans_sales ts
				LEFT JOIN trans_sales_zero_rated tsz ON ts.sales_id = tsz.sales_id 
				LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
				LEFT JOIN trans_sales_items tsi ON ts.sales_id = tsi.sales_id
				LEFT JOIN items i ON i.item_id = tsi.item_id 
			WHERE
				i.cat_id != "6" 
			GROUP BY
				ts.trans_ref 
			) FSLi ON MAIN.sales_id = FSLi.sales_id
			LEFT JOIN (
			SELECT
				ts.sales_id,
				tsp.card_number,
				ts.trans_ref,
				SUM( tsm.price * tsm.qty ) AS fl_amount 
			FROM
				trans_sales ts
				LEFT JOIN trans_sales_zero_rated tsz ON ts.sales_id = tsz.sales_id 
				LEFT JOIN trans_sales_payments tsp ON ts.sales_id = tsp.sales_id
				LEFT JOIN trans_sales_menus tsm ON ts.sales_id = tsm.sales_id 
			
			GROUP BY
			ts.trans_ref 
		) FSLm ON MAIN.sales_id = FSLm.sales_id
			LEFT JOIN trans_sales_no_tax on MAIN.sales_id = trans_sales_no_tax.sales_id

			';

		// if(!$is_payment){
		// 	$sql .= "		GROUP BY MAIN.user_id";
		// }else

		// {
			$sql .= "  GROUP BY MAIN.trans_ref, MAIN.payment_type, MAIN.card_type ORDER BY  MAIN.payment_type, MAIN.card_type";
		// }

// echo $sql;die();
		$q = $this->db->query($sql);
		// echo $this->db->last_query();die();
		return $q->result_array();
	}	

	public function get_event_logs($user_id=null,$args=array(),$limit=0)
	{
		$this->db->select('
			event_logs.*,
			users.username,users.fname,users.mname,users.lname,users.suffix
			');
		$this->db->from('event_logs');
		$this->db->join('users','event_logs.user_id = users.id','left');
		if (!is_null($user_id)) {
			if (is_array($user_id))
				$this->db->where_in('event_logs.user_id',$user_id);
			else
				$this->db->where('event_logs.user_id',$user_id);
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
		$this->db->order_by('event_logs.datetime desc');
		$query = $this->db->get();
		return $query->result();
	}


}