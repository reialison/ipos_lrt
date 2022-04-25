<?php

function makediscountform($data = array(),$total=0,$type=array(),$disc_cart=array(),$guest=1){
	$CI =& get_instance();
	// echo "<pre>",print_r($data),"</pre>";die();
	// $CI->make->sForm("dine/menu/subcategories_form_db",array('id'=>'subcategories_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(7,"",0,array('class'=>''));
				$CI->make->input(null,'search-disc',null,null,array(),fa('fa fa-search')." Search Discount");
				$CI->make->sDivCol(12,"",0,array('class'=>'padding_topbot border_sides custom_scroll','id'=>'blue_scroll','style'=>'height:240px;important;'));
					$CI->make->sDiv(array('id'=>'search-disc-div','style'=>''));
					$CI->make->eDiv();
					$CI->make->sDiv(array('style'=>'min-height:100px!important;'));
						
						$disc_session = array();
						foreach ($data as $value) {
							$CI->make->sDivCol(6,"",0);
								// $CI->make->button($value->disc_name,array('id'=>'disc-'.$value->disc_id,'ref'=>$value->disc_id,'class'=>'btn-lg ellipsis_txt show_form','style'=>'margin-bottom:10px!important;width:175px!important;'),'primary');
								$CI->make->button($value->disc_name,array('id'=>'disc-'.$value->disc_id,'ref'=>$value->disc_id,'fix'=>$value->fix,'rate'=>$value->disc_rate,'disc_code'=>$value->disc_code,'class'=>'btn-block show_form disc-buttons','style'=>'margin-bottom:10px!important;'),'primary');
							$CI->make->eDiv();

							$disc_session[$value->disc_id] = array('text'=>$value->disc_name,'fix'=>$value->fix,'rate'=>$value->disc_rate,'disc_code'=>$value->disc_code);

						}
						$CI->session->set_userData('disc_session',$disc_session);
						$CI->make->hidden('guest_count',$guest);
						$CI->make->hidden('disc_count',0);
						$CI->make->hidden('disc_ctr',0);
						$CI->make->hidden('running_count',count($disc_cart));
					$CI->make->eDiv();
				$CI->make->eDiv();
				$CI->make->sDivCol(12,"",0,array("style"=>"margin-top:14px!important;",'id'=>'div_form_disc','class'=>'border_sides padding_topbot','hidden'=>'hidden'));
								 // $CI->make->sForm("",array('id'=>'disc-form'));
								 	$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
									 	 $CI->make->sDivCol(6);
									 	 	$CI->make->input(null,'ndisc-cust-code',null,'Card Number',array('class'=>'','ro-msg'=>'Add Customer Code for Discount'),fa('fa-credit-card'));
									 	 	$CI->make->seniorIdDrop('','ids_number',null,null,array('class'=>'','style'=>'display:none;'));
									 	 $CI->make->eDivCol();
									 	 $CI->make->sDivCol(6);
							 	 			$CI->make->hidden('ndiscount-id',null);
							 	 			$CI->make->hidden('nguest',$guest);
							 	 			$CI->make->hidden('ntotal',$total);
									 	 	$CI->make->input(null,'ndisc-cust-name',null,'Customer Name',array('class'=>'rOkay','ro-msg'=>'Add Customer Name for Discount'),fa('fa-user'));
									 	 	$CI->make->seniorDrop('','seniors',null,null,array('class'=>'','style'=>'display:none;'));
									 	 	$CI->make->hidden('cust-disc-code',null);
									 	 	$CI->make->hidden('disc-disc-id',null);
									 	 	$CI->make->hidden('disc-disc-rate',null);
									 	 	$CI->make->hidden('disc-disc-code',null);
									 	 	$CI->make->hidden('disc-no-tax',null);
									 	 	$CI->make->hidden('disc-fix',null);
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 // $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										//  $CI->make->sDivCol(12);
									 // 	 	$CI->make->input(null,'ndisc-cust-code',null,'Card Number',array('class'=>'','ro-msg'=>'Add Customer Code for Discount'),fa('fa-credit-card'));
									 // 	 $CI->make->eDivCol();
									 // $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(6);
									 	 	$CI->make->input(null,'ndisc-cust-bday',null,'Birthday',array('ro-msg'=>'Add Customer Birthdate for Discount'),fa('fa-calendar'));
									 	 $CI->make->eDivCol();
										 $CI->make->sDivCol(6);
									 	 	$CI->make->input(null,'ndisc-remark',null,'Remarks',array('class'=>'','ro-msg'=>'Add Remarks'),fa('fa-credit-card'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;display:none;','id'=>'opendiv'));
									 	$CI->make->sDivCol(6);
									 	 	$CI->make->input(null,'ndisc-openamt',null,'Amount',array('class'=>'','ro-msg'=>''),fa('fa-credit-card'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(2);
										 $CI->make->eDivCol();
										 $CI->make->sDivCol(3);
											$CI->make->button("CANCEL",array('id'=>'cancel-disc-person-btn','class'=>'btn-lg round_btn'),'danger');
										 	// $CI->make->button(fa('fa-plus fa-lg fa-fw').' ADD ',array('id'=>'add-disc-person-btn','class'=>'btn-block counter-btn-green'));
									 	 $CI->make->eDivCol();
									 	 $CI->make->sDivCol(1);
										 $CI->make->eDivCol();
										 $CI->make->sDivCol(3);
										 	$CI->make->button("SUBMIT",array('id'=>'submit-discount-btn','class'=>'btn-lg round_btn'),'warning');
									 	 	// $CI->make->button(fa('fa fa-times fa-lg fa-fw').'REMOVE',array('id'=>'remove-disc-btn','class'=>'btn-block counter-btn-red'));
									 	 $CI->make->eDivCol();
									 	 $CI->make->sDivCol(2);
										 $CI->make->eDivCol();
									 $CI->make->eDivRow();
								 // $CI->make->eForm();
				$CI->make->eDiv();
				// $CI->make->sDivCol(12,"",0,array("style"=>"margin-top:14px!important;",'id'=>'div_form_disc_senior','class'=>'border_sides padding_topbot','hidden'=>'hidden'));
				// 				 // $CI->make->sForm("",array('id'=>'disc-form'));
				// 				 	$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
				// 					 	 $CI->make->sDivCol(6);
				// 			 	 			$CI->make->hidden('ndiscount-id',null);
				// 			 	 			$CI->make->hidden('nguest',$guest);
				// 			 	 			$CI->make->hidden('ntotal',$total);
				// 					 	 	$CI->make->isSeniorDrop('','sndisc-cust-name','','Customer Name',array('class'=>'rOkay','ro-msg'=>'Add Customer Name for Discount'),fa('fa-user'));
				// 					 	 	$CI->make->hidden('disc-disc-id',null);
				// 					 	 	$CI->make->hidden('disc-disc-rate',null);
				// 					 	 	$CI->make->hidden('disc-disc-code',null);
				// 					 	 	$CI->make->hidden('disc-no-tax',null);
				// 					 	 	$CI->make->hidden('disc-fix',null);
				// 					 	 $CI->make->eDivCol();
				// 					 	 $CI->make->sDivCol(6);
				// 					 	 	$CI->make->input(null,'sndisc-cust-code',null,'Card Number',array('class'=>'','ro-msg'=>'Add Customer Code for Discount'),fa('fa-credit-card'));
				// 					 	 $CI->make->eDivCol();
				// 					 $CI->make->eDivRow();
				// 					 // $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
				// 						//  $CI->make->sDivCol(12);
				// 					 // 	 	$CI->make->input(null,'ndisc-cust-code',null,'Card Number',array('class'=>'','ro-msg'=>'Add Customer Code for Discount'),fa('fa-credit-card'));
				// 					 // 	 $CI->make->eDivCol();
				// 					 // $CI->make->eDivRow();
				// 					 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
				// 						 $CI->make->sDivCol(6);
				// 					 	 	$CI->make->input(null,'sndisc-cust-bday',null,'Birthday',array('ro-msg'=>'Add Customer Birthdate for Discount'),fa('fa-calendar'));
				// 					 	 $CI->make->eDivCol();
				// 						 $CI->make->sDivCol(6);
				// 					 	 	$CI->make->input(null,'sndisc-remark',null,'Remarks',array('class'=>'','ro-msg'=>'Add Remarks'),fa('fa-credit-card'));
				// 					 	 $CI->make->eDivCol();
				// 					 $CI->make->eDivRow();
				// 					 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
				// 						 $CI->make->sDivCol(5);
				// 						 $CI->make->eDivCol();
				// 						 $CI->make->sDivCol(3);
				// 							$CI->make->button("CANCEL",array('id'=>'cancel-sdisc-person-btn','class'=>'btn-lg round_btn'),'danger');
				// 						 	// $CI->make->button(fa('fa-plus fa-lg fa-fw').' ADD ',array('id'=>'add-disc-person-btn','class'=>'btn-block counter-btn-green'));
				// 					 	 $CI->make->eDivCol();
				// 						 $CI->make->sDivCol(3);
				// 						 	$CI->make->button("SUBMIT",array('id'=>'submit-sdiscount-btn','class'=>'btn-lg round_btn'),'warning');
				// 					 	 	// $CI->make->button(fa('fa fa-times fa-lg fa-fw').'REMOVE',array('id'=>'remove-disc-btn','class'=>'btn-block counter-btn-red'));
				// 					 	 $CI->make->eDivCol();
				// 					 $CI->make->eDivRow();
				// 				 // $CI->make->eForm();
				// $CI->make->eDiv();
			$CI->make->eDiv();
			
			// $CI->make->sDivCol(1,"",0);
			// $CI->make->eDiv();
			$CI->make->sDivCol(5,"",0,array('class'=>'padding_topbot border_sides'));
				$CI->make->sDivCol(12,"",0);
					$CI->make->span("<strong><font size='6'>DISCOUNT PREVIEW</font></strong><br><br>");
				$CI->make->eDiv();	

				$CI->make->sDivCol(4,"",0);

					$CI->make->span("<strong><font size='3'>Guest Count :</font><br>",array('style'=>'white-space:nowrap;'));//TOTAL AMOUNT
				$CI->make->eDiv();
				$CI->make->sDivCol(8,"",0,array('style'=>'text-align: center;'));
						$CI->make->span("<strong><font size='3'>".$guest."</font><br></strong>");
				$CI->make->eDiv();
				$CI->make->sDivCol(4,"",0);
					$CI->make->span("<strong><font size='3'>Total Amount :</font><br>",array('style'=>'white-space:nowrap;'));//TOTAL AMOUNT
				$CI->make->eDiv();
				$CI->make->sDivCol(8,"",0,array('style'=>'text-align: center;'));
						$CI->make->span("<strong><font size='3'>".num($total)."</font><br><br></strong>");
				$CI->make->eDiv();


				$CI->make->sDiv(array('id'=>'added-disc-div','class'=>'','style'=>'height:300px;'));
					$disc_count = 0;
					$disc_total = 0;
					$disc_lv = 0;
					if($disc_cart){

						// $disc_count = count($disc_cart);
						foreach($disc_cart as $cid => $vals){
            				$disc_count += isset($vals['persons']) ? count($vals['persons']) : 1;
				            $CI->make->sDivRow();
				                $CI->make->sDivCol(7);
				                    $CI->make->span("<font size='3'>".$vals['disc_name']."</font>");
				                $CI->make->eDivCol();
				                $CI->make->sDivCol(2);
				                    // $CI->make->span("<font size='3'>1</font>");
				                	$CI->make->span("<font size='3'>".$vals['disc_count']."</font>");
				                $CI->make->eDivCol();
				                $CI->make->sDivCol(3);
				                    $CI->make->span("<font size='3'>".num($vals['disc_amount'])."</font>");
				                $CI->make->eDivCol();
				                //  $CI->make->sDivCol(1);
				                //     $CI->make->span(fa('fa-times fa-2x'));
				                // $CI->make->eDivCol();
				            $CI->make->eDivRow();

				            $disc_total += $vals['disc_amount'];
				            $disc_lv += $vals['less_vat'];
				        }


					}

					// $CI->make->sDivCol(4,"",0);
					// 	$CI->make->span("<font size='3'>SC :</font> ");
					// $CI->make->eDiv();
					// $CI->make->sDivCol(8,"",0,array('style'=>'height:32px;'));
					// 	$CI->make->sDivCol(8,"",0);
					// 		$CI->make->input('','name',null,'input...',array('class'=>'rOkay','type'=>'number','style'=>'width:100px;height:28px;'));
					// 	$CI->make->eDiv();
					// 	$CI->make->sDivCol(4,"",0);
					// 		$CI->make->span("<font size='3'>?????????</font>");
					// 	$CI->make->eDiv();
					// $CI->make->eDiv();

					// $CI->make->sDivCol(4,"",0);
					// 	$CI->make->span("<font size='3'>PWD :</font> ");
					// $CI->make->eDiv();
					// $CI->make->sDivCol(8,"",0,array('style'=>'height:32px;'));
					// 	$CI->make->sDivCol(8,"",0);
					// 		$CI->make->input('','name',null,'input...',array('class'=>'rOkay','type'=>'number','style'=>'width:100px;height:28px'));
					// 	$CI->make->eDiv();
					// 	$CI->make->sDivCol(4,"",0);
					// 		$CI->make->span("<font size='3'>?????????</font>");
					// 	$CI->make->eDiv();
					// $CI->make->eDiv();

					// $CI->make->sDivCol(4,"",0);
					// 	$CI->make->span("<font size='3'>Diplomat :</font> ");
					// $CI->make->eDiv();
					// $CI->make->sDivCol(8,"",0);
					// 	$CI->make->sDivCol(8,"",0,array('style'=>'height:32px;'));
					// 		$CI->make->input('','name',null,'input...',array('class'=>'rOkay','type'=>'number','style'=>'width:100px;height:28px'));
					// 	$CI->make->eDiv();
					// 	$CI->make->sDivCol(4,"",0);
					// 		$CI->make->span("<font size='3'>?????????</font>");
					// 	$CI->make->eDiv();
					// $CI->make->eDiv();

					// $CI->make->sDivCol(4,"",0);
					// 	$CI->make->span("<font size='3'>REG :</font> ");
					// $CI->make->eDiv();
					// $CI->make->sDivCol(8,"",0);
					// 	$CI->make->sDivCol(8,"",0,array('style'=>'height:32px;'));
					// 		$CI->make->input('','name',null,'input...',array('class'=>'rOkay','type'=>'number','style'=>'width:100px;height:28px'));
					// 	$CI->make->eDiv();
					// 	$CI->make->sDivCol(4,"",0);
					// 		$CI->make->span("<font size='3'>?????????</font>");
					// 	$CI->make->eDiv();
					// $CI->make->eDiv();

				
				$CI->make->eDiv();
				
				$CI->make->sDivCol(12,"",0,array("style"=>"height:15px;"));
				$CI->make->eDiv();

				$CI->make->sDivCol(4,"",0);
					$CI->make->span("<font size='4'>Total Discount :</font> ");
				$CI->make->eDiv();
				$CI->make->sDivCol(8,"",0);
					$CI->make->sDivCol(8,"",0,array('style'=>'height:32px;'));
						$CI->make->span("<font size='4'>".$disc_count."</font>",array('id'=>'disc-count-span'));
					$CI->make->eDiv();
					$CI->make->sDivCol(4,"",0);
						$CI->make->span("<font size='4'>".num($disc_total)."</font>",array('id'=>'disc-total-span'));
					$CI->make->eDiv();
				$CI->make->eDiv();

				$CI->make->sDivCol(4,"",0);
					$CI->make->span("<font size='4'>Less VAT :</font> ");
				$CI->make->eDiv();
				$CI->make->sDivCol(8,"",0);
					$CI->make->sDivCol(8,"",0,array('style'=>'height:32px;'));
						// $CI->make->span("<br><font size='4'>?????????</font>");
					$CI->make->eDiv();
					$CI->make->sDivCol(4,"",0);
						$CI->make->span("<font size='4'>".num($disc_lv)."</font>",array('id'=>'disc-lv-span'));
					$CI->make->eDiv();
				$CI->make->eDiv();
				
					
				// $CI->make->sDivCol(4,"",0);
				// 	$CI->make->span("<font size='4'>VAT Sales :</font> ");
				// $CI->make->eDiv();
				// $CI->make->sDivCol(8,"",0);
				// 	$CI->make->sDivCol(8,"",0,array('style'=>'height:32px;'));
				// 		// $CI->make->span("<font size='4'>?????????</font>");
				// 	$CI->make->eDiv();
				// 	$CI->make->sDivCol(4,"",0);
				// 		$CI->make->span("<font size='4'>?????????</font>");
				// 	$CI->make->eDiv();
				// $CI->make->eDiv();

				$CI->make->sDivCol(12,"",0,array("style"=>"height:15px;"));
				$CI->make->eDiv();

				// $CI->make->sDivCol(4,"",0);
				// 	$CI->make->span("<font size='5'>Amount Payable. :</font> ",array('style'=>'white-space:nowrap;'));
				// $CI->make->eDiv();
				// $CI->make->sDivCol(8,"",0);
				// 	$CI->make->sDivCol(4,"",0,array('style'=>'height:32px;'));
				// 		// $CI->make->span("<br><br><font size='5'>5</font>");
				// 	$CI->make->eDiv();
				// 	$CI->make->sDivCol(8,"",0);
				// 		$CI->make->span("<font size='5' id='amount_payable_text'>?????????</font>");
				// 	$CI->make->eDiv();
				// $CI->make->eDiv();
				$CI->make->sDivCol(12,"",0,array("style"=>"height:90px;"));
					$CI->make->sDivCol(12,"",0,array("style"=>"height:45px;"));
					$CI->make->eDiv();
					$CI->make->sDivCol(6,"",0);
						$CI->make->button("RESET",array('id'=>'reset','class'=>'btn-lg','style'=>'margin-bottom:10px!important;width:150px!important;border-radius:12px!important;'),'danger');
					$CI->make->eDiv();
					$CI->make->sDivCol(6,"",0);
						$CI->make->button("PROCESS",array('id'=>'process','class'=>'btn-lg','style'=>'margin-bottom:10px!important;width:150px!important;border-radius:12px!important;','disabled'=>'disabled'),'success');
					$CI->make->eDiv();
				$CI->make->eDiv();
			$CI->make->eDiv();
		

		$CI->make->eDiv();
		$CI->make->eDiv();
		$CI->make->eDiv();

    	$CI->make->eDivRow();
	// $CI->make->eForm
	return $CI->make->code();
}
function makeguidecashcount($cat=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/menu/subcategories_form_db",array('id'=>'subcategories_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(12);
				$CI->make->sDiv(array("class"=>"mt-element-step"));
				$CI->make->sDiv(array("class"=>"row step-line"));
				$CI->make->sDiv(array("class"=>"mt-step-desc"));


				$CI->make->sDivCol(6,"",0,array('class'=>"mt-step-col first active"));
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white"));
						$CI->make->span("1");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-title uppercase"));
						$CI->make->span("Cash Count");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-content"));
						$CI->make->span("Go to Cash count");
					$CI->make->eDiv();
				$CI->make->eDivCol();

				$CI->make->sDivCol(6,"",0,array('class'=>"mt-step-col last"));
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white"));
						$CI->make->span("2");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-title uppercase"));
						$CI->make->span("End Shift");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-content"));
						$CI->make->span("");
					$CI->make->eDiv();
				$CI->make->eDiv();
				$CI->make->eDivCol();
				$CI->make->eDivCol();
				$CI->make->eDivCol();



				$CI->make->sDivCol(12);
					$CI->make->sDiv(array("class"=>"row step-line"));
						$CI->make->sDiv(array("class"=>"mt-step-desc"));
							$CI->make->sDiv(array("class"=>"font-dark bold uppercase"));
								$CI->make->h(2,"STEP 1: CASH COUNT");
							$CI->make->eDiv();
							$CI->make->sDiv(array("class"=>"caption-desc"));
								// $CI->make->span("Description here");
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();	
				$CI->make->sDiv(array('class'=>'manager-content'));

				$CI->make->eDiv();	
				$CI->make->eDivCol();

    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function makeguideendshift($read_details=array(),$role_id=null){
	$CI =& get_instance();
	// $CI->make->sForm("dine/menu/subcategories_form_db",array('id'=>'subcategories_form'));
		if(!empty($read_details)){
			// $CI->make->sForm(base_url()."reports/x_read_rep",array('id'=>'eod_form'));
			$CI->make->sForm(base_url()."prints/system_sales_rep",array('id'=>'eod_form'));
				foreach ($read_details as $key => $val) {
					// echo "<pre>",print_r($key),"<pre>";
					$CI->make->hidden($key,$val);
				}
			$CI->make->eForm();
		}
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(12);
				$CI->make->sDiv(array("class"=>"mt-element-step"));
				$CI->make->sDiv(array("class"=>"row step-line"));
				$CI->make->sDiv(array("class"=>"mt-step-desc"));


				$CI->make->sDivCol(6,"",0,array('class'=>"mt-step-col first done"));
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white"));
						$CI->make->span("1");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-title uppercase"));
						$CI->make->span("Cash Count");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-content"));
						$CI->make->span("Go to Cash count");
					$CI->make->eDiv();
				$CI->make->eDivCol();

				$CI->make->sDivCol(6,"",0,array('class'=>"mt-step-col last active"));
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white"));
						$CI->make->span("2");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-title uppercase"));
						$CI->make->span("End Shift");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-content"));
						$CI->make->span("");
					$CI->make->eDiv();
				$CI->make->eDiv();
				$CI->make->eDivCol();
				$CI->make->eDivCol();
				$CI->make->eDivCol();



				$CI->make->sDivCol(12);
					$CI->make->sDiv(array("class"=>"row step-line"));
						$CI->make->sDiv(array("class"=>"mt-step-desc"));
							$CI->make->sDiv(array("class"=>"font-dark bold uppercase"));
								$CI->make->h(2,"STEP 2: END OF SHIFT (X-READING)");
							$CI->make->eDiv();
							$CI->make->sDiv(array("class"=>"caption-desc"));
								// $CI->make->span("Description here");
								$CI->make->append('<button id="end-shift-print-btn" class="btn btn-lg blue btn-block btn-outline " style="width:400px;">END OF SHIFT (X-READING)</button>');
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();	
				$CI->make->eDivCol();

    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function makeguideendshift2($cat=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/menu/subcategories_form_db",array('id'=>'subcategories_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(12);
				$CI->make->sDiv(array("class"=>"mt-element-step"));
				$CI->make->sDiv(array("class"=>"row step-line"));
				$CI->make->sDiv(array("class"=>"mt-step-desc"));


				$CI->make->sDivCol(6,"",0,array('class'=>"mt-step-col first active"));
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white"));
						$CI->make->span("1");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-title uppercase"));
						$CI->make->span("End Shift");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-content"));
						$CI->make->span("description here");
					$CI->make->eDiv();
				$CI->make->eDivCol();

				$CI->make->sDivCol(6,"",0,array('class'=>"mt-step-col last"));
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white"));
						$CI->make->span("2");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-title uppercase"));
						$CI->make->span("End Day");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-content"));
						$CI->make->span("");
					$CI->make->eDiv();
				$CI->make->eDiv();
				$CI->make->eDivCol();
				$CI->make->eDivCol();
				$CI->make->eDivCol();



				$CI->make->sDivCol(12);
					$CI->make->sDiv(array("class"=>"row step-line"));
						$CI->make->sDiv(array("class"=>"mt-step-desc"));
							$CI->make->sDiv(array("class"=>"font-dark bold uppercase"));
								$CI->make->h(2,"STEP 1: END SHIFT");
							$CI->make->eDiv();
							$CI->make->sDiv(array("class"=>"caption-desc"));
								$CI->make->span("Description here");
								$CI->make->append('<button id="end-shift-btn" class="btn blue btn-block btn-outline ">END SHIFT</button>');
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();	
				$CI->make->eDivCol();

    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function makeguideendday($read_details=array(),$error=null,$role_id=null){
	$CI =& get_instance();

	// $CI->make->sForm("dine/menu/subcategories_form_db",array('id'=>'subcategories_form'));
		if(!empty($read_details)){
			// $CI->make->sForm(base_url()."reports/x_read_rep",array('id'=>'eod_form'));
			$CI->make->sForm(base_url()."prints/system_sales_rep",array('id'=>'eod_form'));
				foreach ($read_details as $key => $val) {
					// echo "<pre>",print_r($val),"</pre>";die();
					$CI->make->hidden($key,$val);
				}
			$CI->make->eForm();
		}
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(12);
				$CI->make->sDiv(array("class"=>"mt-element-step"));
				$CI->make->sDiv(array("class"=>"row step-line"));
				$CI->make->sDiv(array("class"=>"mt-step-desc"));


				$CI->make->sDivCol(12,"",0,array('class'=>"mt-step-col first done"));
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white"));
						$CI->make->span("1");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"uppercase",'style'=>"color:#26c281;font-size:15px;font-weight: 400;"));
						$CI->make->span("END OF DAY");
					$CI->make->eDiv();
					$CI->make->sDiv(array("class"=>"mt-step-content"));
						$CI->make->span("(Z-READING)");
					$CI->make->eDiv();
				$CI->make->eDivCol();

				// $CI->make->sDivCol(6,"",0,array('class'=>"mt-step-col last active"));
				// 	$CI->make->sDiv(array("class"=>"mt-step-number bg-white"));
				// 		$CI->make->span("2");
				// 	$CI->make->eDiv();
				// 	$CI->make->sDiv(array("class"=>"mt-step-title uppercase"));
				// 		$CI->make->span("End Day");
				// 	$CI->make->eDiv();
				// 	$CI->make->sDiv(array("class"=>"mt-step-content"));
				// 		$CI->make->span("");
				// 	$CI->make->eDiv();
				// $CI->make->eDiv();
				// $CI->make->eDivCol();
				$CI->make->eDivCol();
				$CI->make->eDivCol();


				$disabled = "";
				$CI->make->sDivCol(12);
					$CI->make->sDiv(array("class"=>"row step-line"));
						$CI->make->sDiv(array("class"=>"mt-step-desc"));
							$CI->make->sDiv(array("class"=>"font-dark bold uppercase"));
								$CI->make->h(2,"END OF DAY (Z-READING)");
							$CI->make->eDiv();
							// echo "<pre>",print_r($error),"</pre>";die();
							if(!empty($error)){
								$CI->make->append('
								            <div class="note note-danger">
	                                        	<h3 class="block"><font color="red"><b>ALERT!!!</b></font></h3>
	                                        <p><font size="4">'.$error.'</font></p>
	                                    </div>');
								$disabled = 'disabled';
							}
							$CI->make->sDiv(array("class"=>"caption-desc"));
								// $CI->make->span("Description here");
								$CI->make->append('<button id="end-day-print-btn" guide="true" class="btn blue btn-block btn-outline btn-lg '.$disabled.' " style="width:400px;">END OF DAY (Z-READING)</button>');
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();	
				$CI->make->eDivCol();

    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
?>