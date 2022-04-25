<?php
function menusRep($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),fa('fa-calendar'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate Report',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
							$CI->make->button(fa('fa-file-excel-o').' Generate as Excel',array('id'=>'excel-btn','style'=>'margin-top:24px;'),'success');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
					$CI->make->eDiv();
					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
						$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					$CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}

function hourlyRep(){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					//$CI->make->sForm("reprint/results",array('id'=>'search-form'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(3);
								// $CI->make->date('Date','date',date('m/d/Y'),null,array('class'=>'rOkay','style'=>'position:initial;'),null,fa('fa-calendar'));
								$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),fa('fa-calendar'));
							$CI->make->eDivCol();
							// $CI->make->sDivCol(3);
							// 	$CI->make->userDrop('User','user',null,null);
							// $CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button(fa('fa-search').' Submit',array('style'=>'margin-top:24px;','id'=>'search-btn'),'primary');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol(12);
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();
					$CI->make->button(fa('fa-print').' Print',array('id'=>'print-btn','class'=>'pull-right','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'),'success');
				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					$CI->make->sDiv(array('id'=>'print-div','style'=>'margin:0 auto;position:relative;width:700px;'));
						
					//$CI->make->append('asdfafasdfasdfsadf');
					$CI->make->eDiv();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}

function salesRep($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
							// $CI->make->date("Date","date",null,"Select Date",array("class"=>"rOkay"));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
							// $CI->make->button(fa('fa-file-excel-o').' Export to Excel',array('id'=>'excel-btn','style'=>'margin-top:24px;'),'success');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
					// $CI->make->sDivRow();
					// 	$CI->make->sDivCol(2);							
					// 		 $CI->make->time('Start Time','start_time',null,'Start Time');
					// 	$CI->make->eDivCol();
					// 	$CI->make->sDivCol(2);
					// 		$CI->make->time('End Time','end_time',null,'End Time');
					// 	$CI->make->eDivCol();
     //            	$CI->make->eDivRow();
                	$CI->make->sDivRow();
						$CI->make->sDivCol(4);							
							$CI->make->reportTypeDrop('Report Type','report_type',null,'Select Type',array("id"=>"report_type", 'class'=>'rOkay'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
					$CI->make->sDivRow(array('id'=>'category-div'));
						$CI->make->sDivCol(4);							
							$CI->make->menuCategoriesDrop('Category','menu_cat_id',null,'Select Category',array("id"=>"menu_cat_id", 'class'=>'rOkay'));
							// $CI->make->brandDrop('Brand','brand',null,'Select Brand',array("id"=>"brand", 'class'=>'rOkay'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
      //           	$CI->make->sDivRow();
						// $CI->make->sDivCol(4);							
						// 	$CI->make->brandDrop('Brand','brand',null,'Select Brand',array("id"=>"brand", 'class'=>'rOkay'));
						// $CI->make->eDivCol();
      //           	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();					
					$CI->make->sDivCol(4);
						$CI->make->input("","search",null,"Search...",array("id"=>"search"),fa("fa-search"));
					$CI->make->eDivCol();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						// $CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'tcpdf-btn'),'warning');
						$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'excel-btn'),'success');
					$CI->make->eDiv();
					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
						$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					$CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}

function dtrRep($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
							// $CI->make->date("Date","date",null,"Select Date",array("class"=>"rOkay"));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
							// $CI->make->button(fa('fa-file-excel-o').' Export to Excel',array('id'=>'excel-btn','style'=>'margin-top:24px;'),'success');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol(2);							
							 $CI->make->time('Start Time','start_time',null,'Start Time');
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->time('End Time','end_time',null,'End Time');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();					
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						// $CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'tcpdf-btn'),'warning');
						$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'excel-btn'),'success');
					$CI->make->eDiv();
					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
						$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					$CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function itemRep($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
							// $CI->make->date("Date","date",null,"Select Date",array("class"=>"rOkay"));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
							// $CI->make->button(fa('fa-file-excel-o').' Export to Excel',array('id'=>'excel-btn','style'=>'margin-top:24px;'),'success');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
					// $CI->make->sDivRow();
					// 	$CI->make->sDivCol(2);							
					// 		 $CI->make->time('Start Time','start_time',null,'Start Time');
					// 	$CI->make->eDivCol();
					// 	$CI->make->sDivCol(2);
					// 		$CI->make->time('End Time','end_time',null,'End Time');
					// 	$CI->make->eDivCol();
     //            	$CI->make->eDivRow();
     //            	$CI->make->sDivRow();
					// 	$CI->make->sDivCol(4);							
					// 		$CI->make->reportTypeDrop('Report Type','report_type',null,'Select Type',array("id"=>"report_type", 'class'=>'rOkay'));
					// 	$CI->make->eDivCol();
     //            	$CI->make->eDivRow();
					// $CI->make->sDivRow(array('id'=>'category-div'));
					// 	$CI->make->sDivCol(4);							
					// 		$CI->make->menuCategoriesDrop('Category','menu_cat_id',null,'Select Category',array("id"=>"menu_cat_id", 'class'=>'rOkay'));
					// 	$CI->make->eDivCol();
     //            	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();					
					// $CI->make->sDivCol(4);
					// 	$CI->make->input("","search",null,"Search...",array("id"=>"search"),fa("fa-search"));
					// $CI->make->eDivCol();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						// $CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'tcpdf-btn'),'warning');
						$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'excel-btn'),'success');
					$CI->make->eDiv();
					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
						$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					$CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}

function voidedSalesRep($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
                
					$CI->make->sDivRow(array('id'=>'category-div'));
						$CI->make->sDivCol(4);							
							$CI->make->menuCategoriesDrop('Category','menu_cat_id',null,'Select Category',array("id"=>"menu_cat_id", 'class'=>'rOkay'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();					
					$CI->make->sDivCol(4);
						$CI->make->input("","search",null,"Search...",array("id"=>"search"),fa("fa-search"));
					$CI->make->eDivCol();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						// $CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'tcpdf-btn'),'warning');
						$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'excel-btn'),'success');
					$CI->make->eDiv();
					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
						$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					$CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function voidedSalesRes($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
                
					// $CI->make->sDivRow(array('id'=>'category-div'));
					// 	$CI->make->sDivCol(4);							
					// 		$CI->make->menuCategoriesDrop('Category','menu_cat_id',null,'Select Category',array("id"=>"menu_cat_id", 'class'=>'rOkay'));
					// 	$CI->make->eDivCol();
     //            	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();					
					$CI->make->sDivCol(4);
						$CI->make->input("","search",null,"Search...",array("id"=>"search"),fa("fa-search"));
					$CI->make->eDivCol();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						// $CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'tcpdf-btn'),'warning');
						$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'excel-btn'),'success');
					$CI->make->eDiv();
					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
						$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					$CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}

function promoRep($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
							// $CI->make->yearDrop($label="Year",$nameID="year",$value=date("Y"),$placeholder=null,$params=array('class'=>'rOkay','style'=>'position:initial;'),null,fa('fa-calendar'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();					
					$CI->make->sDivCol(4);
						$CI->make->input("","search",null,"Search...",array("id"=>"search"),fa("fa-search"));
					$CI->make->eDivCol();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						// $CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'tcpdf-btn'),'warning');
						$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'excel-btn'),'success');
					$CI->make->eDiv();
					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
						$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					$CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function makeMonthlyBreakdown($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->yearDrop("Year","year",date("Y"),null,array('class'=>'rOkay','style'=>'position:initial;'),null,fa('fa-calendar'));
							// $CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),fa('fa-calendar'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate Report',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
							$CI->make->button(fa('fa-file-excel-o').' Generate as Excel',array('id'=>'excel-btn','style'=>'margin-top:24px;'),'success');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
					$CI->make->eDiv();
					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
						$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					$CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function makeSummaryCredit($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							// $CI->make->yearDrop("Year","year",date("Y"),null,array('class'=>'rOkay','style'=>'position:initial;'),null,fa('fa-calendar'));
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),fa('fa-calendar'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate Report',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
							$CI->make->button(fa('fa-file-excel-o').' Generate as Excel',array('id'=>'excel-btn','style'=>'margin-top:24px;'),'success');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
					$CI->make->eDiv();
					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
						$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					$CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function esalesRep($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range_2',null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate Report',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
							$CI->make->button(fa('fa-file-excel-o').' Generate as Excel',array('id'=>'excel-btn','style'=>'margin-top:24px;'),'success');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
					$CI->make->eDiv();
					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
						// $CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					$CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function TopItemsRep($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),fa('fa-calendar'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'excel-btn'),'success');
					$CI->make->eDiv();
				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function brandsalesRep($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
                	$CI->make->sDivRow();
						$CI->make->sDivCol(4);							
							// $CI->make->reportTypeDrop('Report Type','report_type',null,'Select Type',array("id"=>"report_type", 'class'=>'rOkay'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
					$CI->make->sDivRow(array('id'=>'category-div'));
						$CI->make->sDivCol(4);							
							$CI->make->menuCategoriesDrop('Category','menu_cat_id',null,'Select Category',array("id"=>"menu_cat_id", 'class'=>'rOkay'));
							$CI->make->brandDrop('Brand','brand',null,'Select Brand',array("id"=>"brand", 'class'=>'rOkay'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();					
					$CI->make->sDivCol(4);
						$CI->make->input("","search",null,"Search...",array("id"=>"search"),fa("fa-search"));
					$CI->make->eDivCol();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						// $CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'tcpdf-btn'),'warning');
						$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'excel-btn'),'success');
					$CI->make->eDiv();
					
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
						$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					$CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}

function gcSalesRep($list = null,$gc_brands){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
							// $CI->make->date("Date","date",null,"Select Date",array("class"=>"rOkay"));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
							// $CI->make->button(fa('fa-file-excel-o').' Export to Excel',array('id'=>'excel-btn','style'=>'margin-top:24px;'),'success');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();

                	$CI->make->sDivRow(array('id'=>'category-div'));
						$CI->make->sDivCol(4);							
							$gc_list=array();
									if(count($gc_brands) > 0){
										foreach($gc_brands as $gc_type){
											$gc_list[$gc_type->brand_id]=$gc_type->brand_id;
										}
									}
									

									$CI->make->select('GC Type','gc-type',$gc_list,null,array());
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
					// $CI->make->sDivRow();
					// 	$CI->make->sDivCol(2);							
					// 		 $CI->make->time('Start Time','start_time',null,'Start Time');
					// 	$CI->make->eDivCol();
					// 	$CI->make->sDivCol(2);
					// 		$CI->make->time('End Time','end_time',null,'End Time');
					// 	$CI->make->eDivCol();
     //            	$CI->make->eDivRow();
     //            	$CI->make->sDivRow();
					// 	$CI->make->sDivCol(4);							
					// 		$CI->make->reportTypeDrop('Report Type','report_type',null,'Select Type',array("id"=>"report_type", 'class'=>'rOkay'));
					// 	$CI->make->eDivCol();
     //            	$CI->make->eDivRow();
					// $CI->make->sDivRow(array('id'=>'category-div'));
					// 	$CI->make->sDivCol(4);							
					// 		$CI->make->menuCategoriesDrop('Category','menu_cat_id',null,'Select Category',array("id"=>"menu_cat_id", 'class'=>'rOkay'));
					// 	$CI->make->eDivCol();
     //            	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();					
					// $CI->make->sDivCol(4);
					// 	$CI->make->input("","search",null,"Search...",array("id"=>"search"),fa("fa-search"));
					// $CI->make->eDivCol();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						// $CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'tcpdf-btn'),'warning');
						$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'excel-btn'),'success');
					$CI->make->eDiv();
					
					// $CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
					// 	$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
					// 	$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
					// $CI->make->eDiv();

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function extractMenuPage($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						// $CI->make->sDivCol(4);
						// 	$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),fa('fa-calendar'));
						// $CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-file-excel-o').' Generate Excel',array('id'=>'gen-excel','style'=>''),'primary');
						$CI->make->eDivCol();
						// $CI->make->sDivCol(4);
						// 	$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),fa('fa-calendar'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	// $CI->make->sDivRow();
	// 	$CI->make->sDivCol();
	// 		$CI->make->sBox('default',array('class'=>'box-solid'));
	// 			$CI->make->sBoxHead();					
	// 				$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
	// 					$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'excel-btn'),'success');
	// 				$CI->make->eDiv();
	// 			$CI->make->eBoxHead();
	// 			$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
	// 				$CI->make->sBox('solid',array('id'=>'print-box'));
	// 				    $CI->make->sBoxBody(array('class'=>'no-padding'));
	// 				        $CI->make->sDivRow();
	// 				            $CI->make->sDivCol(12);
	// 								$CI->make->sDiv(array('id'=>'print-div'));
	// 								$CI->make->eDiv();
	// 				            $CI->make->eDivCol();
	// 				        $CI->make->eDivRow();
	// 				    $CI->make->eBoxBody();
	// 				$CI->make->eBox();
									
	// 			$CI->make->eBoxBody();
	// 		$CI->make->eBox();
	// 	$CI->make->eDivCol();
	// $CI->make->eDivRow();

	return $CI->make->code();
}
function menusRepHourly($list = null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),fa('fa-calendar'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(7);
							$CI->make->button(fa('fa-refresh').' Generate Report',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
							$CI->make->button(fa('fa-file-excel-o').' Generate as Excel',array('id'=>'excel-btn','style'=>'margin-top:24px;'),'success');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				// $CI->make->sBoxHead();
				// 	$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
				// 		$CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
				// 	$CI->make->eDiv();
					
				// 	$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
				// 		$CI->make->button(fa('fa-table fa-lg'),array('id'=>'view-list','class'=>'listyle-btns'));
				// 		$CI->make->button(fa('fa-bar-chart fa-lg'),array('id'=>'view-grid','class'=>'listyle-btns'));
				// 	$CI->make->eDiv();

				// $CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					// $CI->make->sBox('solid',array('id'=>'print-box'));
					//     $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					//     $CI->make->eBoxBody();
					// $CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}

function customerBalanceRep(){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->input('Date Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
							// $CI->make->date("Date","date",null,"Select Date",array("class"=>"rOkay"));
						$CI->make->eDivCol();

						$CI->make->sDivCol(4);							
							$CI->make->arCustomerDrop('Customer Name','customer_id',"",null,array('class'=>' selectpicker','style'=>'position:initial;','data-live-search'=>"true",'all'=>1));
						$CI->make->eDivCol();

						$CI->make->sDivCol(4);
							$CI->make->button(fa('fa-refresh').' Generate',array('id'=>'gen-rep','style'=>'margin-top:24px;margin-right:10px;'),'primary');
							// $CI->make->button(fa('fa-file-excel-o').' Export to Excel',array('id'=>'excel-btn','style'=>'margin-top:24px;'),'success');
						$CI->make->eDivCol();
                	$CI->make->eDivRow();

                	$CI->make->sDivRow(array('id'=>'category-div'));
						
                	$CI->make->eDivRow();
			
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();					
					// $CI->make->sDivCol(4);
					// 	$CI->make->input("","search",null,"Search...",array("id"=>"search"),fa("fa-search"));
					// $CI->make->eDivCol();
					$CI->make->sDiv(array('class'=>'btn-group pull-right','role'=>'group','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'));
						// $CI->make->button(fa('fa-print').' PDF',array('id'=>'pdf-btn'),'warning');
						$CI->make->button(fa('fa-print').' PDF',array('id'=>'tcpdf-btn'),'warning');
						$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'excel-btn'),'success');
					$CI->make->eDiv();
					

				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					
					$CI->make->sBox('solid',array('id'=>'print-box'));
					    $CI->make->sBoxBody(array('class'=>'no-padding'));
					        $CI->make->sDivRow();
					            $CI->make->sDivCol(12);
									$CI->make->sDiv(array('id'=>'print-div'));
									$CI->make->eDiv();
					            $CI->make->eDivCol();
					        $CI->make->eDivRow();
					    $CI->make->eBoxBody();
					$CI->make->eBox();
									
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}