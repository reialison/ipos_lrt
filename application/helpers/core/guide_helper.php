<?php

function makeguideform($cat=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/menu/subcategories_form_db",array('id'=>'subcategories_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDiv(array("class"=>"mt-element-step"));
			$CI->make->sDiv(array("class"=>"row step-line"));
			$CI->make->sDiv(array("class"=>"mt-step-desc"));
				$CI->make->sDivCol(3,"",0,array('class'=>"mt-step-col first done"));
					$CI->make->append('<a href="javascript:;" id="end_shift_g">');
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white","style"=>"padding:5px!important;color:#015d56!important;border-color:#015d56!important;"));
						$CI->make->append("<i class='fa fa-fw fa-laptop fa-lg fa-fw'></i>");
					$CI->make->eDiv();
					$CI->make->append('<div class=" uppercase " style="font-size:15px;font-weight:400;color:#015d56!important;">PROCESS<br>SHIFT CLOSE<br>(X-READING)</div>');
					// $CI->make->append('<div class="mt-step-content" style="color:#015d56!important;">How to End Shift</div>');
					$CI->make->append('</a>');
				$CI->make->eDiv();
				$CI->make->eDivCol();

				$CI->make->sDivCol(3,"",0,array('class'=>"mt-step-col first done"));
					$CI->make->append('<a href="javascript:;" id="end_day_g">');
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white","style"=>"padding:5px!important;color:#015d56!important;border-color:#015d56!important;"));
						$CI->make->append("<i class='fa fa-fw fa-flag-checkered fa-lg fa-fw'></i>");
					$CI->make->eDiv();
					$CI->make->append('<div class=" uppercase " style="font-size:15px;font-weight:400;color:#015d56!important;">PROCESS<br>END OF DAY<br>(Z-READING)</div>');
					// $CI->make->append('<div class="mt-step-content" style="color:#015d56!important;">How to End Day</div>');
					$CI->make->append('</a>');
				$CI->make->eDiv();
				// $CI->make->eDivCol();

				$CI->make->sDivCol(3,"",0,array('class'=>"mt-step-col first done"));
					$CI->make->append('<a href="javascript:;" id="m-cash-dept" class="bootbox-close-button">');
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white","style"=>"padding:5px!important;color:#015d56!important;border-color:#015d56!important;"));
						$CI->make->append("<i class='fa fa-fw fa-download fa-lg fa-fw'></i>");
					$CI->make->eDiv();
					$CI->make->append('<div class=" uppercase " style="font-size:15px;font-weight:400;color:#015d56!important;">CASH<br>DEPOSIT</div>');
					// $CI->make->append('<div class="mt-step-content" style="color:#015d56!important;">How to End Day</div>');
					$CI->make->append('</a>');
				$CI->make->eDiv();

				$CI->make->sDivCol(3,"",0,array('class'=>"mt-step-col first done"));
					$CI->make->append('<a href="javascript:;" id="m-withdraw" class="bootbox-close-button">');
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white","style"=>"padding:5px!important;color:#015d56!important;border-color:#015d56!important;"));
						$CI->make->append("<i class='fa fa-fw fa-upload fa-lg fa-fw'></i>");
					$CI->make->eDiv();
					$CI->make->append('<div class=" uppercase " style="font-size:15px;font-weight:400;color:#015d56!important;">CASH<br>WITHDRAW</div>');
					// $CI->make->append('<div class="mt-step-content" style="color:#015d56!important;">How to End Day</div>');
					$CI->make->append('</a>');
				$CI->make->eDiv();
				$CI->make->sDivCol(12);
				$CI->make->eDiv();

				//lower buttons
				$CI->make->sDivCol(3,"",0,array('class'=>"mt-step-col first done"));
					$CI->make->append('<a href="javascript:;"  id="m-reports" class="bootbox-close-button">');
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white","style"=>"padding:5px!important;color:#015d56!important;border-color:#015d56!important;"));
						$CI->make->append("<i class='fa fa-fw fa-print fa-lg fa-fw'></i>");
					$CI->make->eDiv();
					$CI->make->append('<div class=" uppercase " style="font-size:15px;font-weight:400;color:#015d56!important;">VIEW<br>REPORTS</div>');
					// $CI->make->append('<div class="mt-step-content" style="color:#015d56!important;">How to End Day</div>');
					$CI->make->append('</a>');
				$CI->make->eDiv();

				$CI->make->sDivCol(3,"",0,array('class'=>"mt-step-col first done" ));
					$CI->make->append('<a href="javascript:;" id="m-reports" class="bootbox-close-button">');
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white","style"=>"padding:5px!important;color:#015d56!important;border-color:#015d56!important;"));
						$CI->make->append("<i class='fa fa-fw fa-building-o fa-lg fa-fw'></i>");
					$CI->make->eDiv();
					$CI->make->append('<div class=" uppercase " style="font-size:15px;font-weight:400;color:#015d56!important;">TEXT FILE <br> MAINTENANCE</div>');
					// $CI->make->append('<div class="mt-step-content" style="color:#015d56!important;">How to End Day</div>');
					$CI->make->append('</a>');
				$CI->make->eDiv();

				$CI->make->sDivCol(3,"",0,array('class'=>"mt-step-col first done"));
					$CI->make->append('<a href="javascript:;" id="end_day_g">');
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white","style"=>"padding:5px!important;color:#015d56!important;border-color:#015d56!important;"));
						$CI->make->append("<i class='fa fa-fw icon-refresh fa-lg fa-fw'></i>");
					$CI->make->eDiv();
					$CI->make->append('<div class=" uppercase " style="font-size:15px;font-weight:400;color:#015d56!important;">SYSTEM SYNCHRONIZE</div>');
					// $CI->make->append('<div class="mt-step-content" style="color:#015d56!important;">How to End Day</div>');
					$CI->make->append('</a>');
				$CI->make->eDiv();

				$CI->make->sDivCol(3,"",0,array('class'=>"mt-step-col first done"));
					$CI->make->append('<a href="javascript:;" id="end_day_g">');
					$CI->make->sDiv(array("class"=>"mt-step-number bg-white","style"=>"padding:5px!important;color:#015d56!important;border-color:#015d56!important;"));
						$CI->make->append("<i class='fa fa-fw fa-television fa-lg fa-fw'></i>");
					$CI->make->eDiv();
					$CI->make->append('<div class=" uppercase " style="font-size:15px;font-weight:400;color:#015d56!important;">SYSTEM<br>BACK UP</div>');
					// $CI->make->append('<div class="mt-step-content" style="color:#015d56!important;">How to End Day</div>');
					$CI->make->append('</a>');
				$CI->make->eDiv();
				// $CI->make->eDivCol();



    	$CI->make->eDivRow();
	$CI->make->eForm();
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