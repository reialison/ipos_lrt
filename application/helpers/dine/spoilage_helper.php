<?php
function spoilageFormPage($ref=null){
	$CI =& get_instance();
	$now = $CI->site_model->get_db_now();
	$CI->make->sDivRow();
		$CI->make->sDivCol(3);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("spoilage/add_item",array('id'=>'add_item_form'));
						// $CI->make->input('Item','item-search',null,'Search Item',array('search-url'=>'mods/search_items','class'=>'rOkay'),'',fa('fa-search'));
						$CI->make->itemAjaxDrop('Item','item-search',null,array());						
						$CI->make->hidden('item-id',null);
						$CI->make->hidden('item-uom',null);
						$CI->make->hidden('item-ppack',null);
						$CI->make->hidden('item-pcase',null);
						$CI->make->sDivRow();
							$CI->make->sDivCol(6);
								$CI->make->decimal('Quantity','qty',null,null,2,array('class'=>'rOkay'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(6);
								$CI->make->H(5,'UOM',array('style'=>'margin-top:3px;'));
								$CI->make->span('',array('id'=>'uom-txt'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->locationsDrop('Markout Location','loc_id',null,null,array('shownames'=>true));
						$CI->make->button(fa('fa-plus').' Add Item',array('class'=>'','id'=>'add-item-btn'),'primary');
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
		#FORM
		$CI->make->sDivCol(9);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("spoilage/save",array('id'=>'spoilage_form'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(3);
								$CI->make->input('Reference','reference',$ref,'Supplier reference',array('class'=>'rOkay'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(3);
								$CI->make->date('Date','trans_date',sql2Date($now),null,array('class'=>'rOkay','ro-msg'=>'Date must not be empty'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(3);
							$CI->make->eDivCol();
							$CI->make->sDivCol(6,'left');
								$CI->make->button(fa('fa-save').' Save',array('id'=>'save-btn','style'=>'margin-top:25px;margin-right:10px;'),'success');
								$CI->make->button(fa('fa-reply').' Back',array('id'=>'back-btn','style'=>'margin-top:25px'),'primary');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol();
								$CI->make->sDiv(array('class'=>'table-responsive'));
								$CI->make->sTable(array('class'=>'table table-hover','id'=>'details-tbl','style'=>'border-bottom:2px solid #ddd;border-top:2px solid #ddd;margin-bottom:5px;'));
									$CI->make->sRow();
										$CI->make->th('ITEM');
										$CI->make->th('QTY',array('style'=>'width:60px;'));
										$CI->make->th('UOM',array('style'=>'width:180px;'));
										$CI->make->th('&nbsp;',array('style'=>'width:40px;'));
									$CI->make->eRow();
									$CI->make->sRow(array('class'=>'no-items-row'));
										$CI->make->th('No Items',array('colspan'=>'5','style'=>'text-align:center'));
									$CI->make->eRow();
								$CI->make->eTable();
								$CI->make->eDiv();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(12);
								$CI->make->textarea('','memo',null,'Add Remarks Here...',array());
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();


	$CI->make->eDivRow();

	return $CI->make->code();
}
function receivingSearch($post=array()){
	$CI =& get_instance();
	$CI->make->sForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->input('Reference','trans_ref',null,null);
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function spoilageCheckUploadForm(){
	$CI =& get_instance();
	// $CI->make->H(3,'Warning! THIS WILL REPLACE ALL THE MENUS.',array('class'=>'label label-warning','style'=>'margin-bottom:50px;font-size:24px;'));
	// $CI->make->sForm('menu/upload_excel_db',array('id'=>"upload-form",'enctype'=>'multipart/form-data'));
	$CI->make->sForm('spoilage/upload_excel_db',array('id'=>"upload-form",'enctype'=>'multipart/form-data'));
		$CI->make->sDivRow(array('style'=>''));
			$CI->make->sDivCol(12,'left');
				$CI->make->append('<button id="spoilage_template" class="btn-sm btn blue" type="button" style="margin-right:6px;"><i class="fa fa-download "></i> Download Template</button>');
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		$CI->make->sDivRow(array('style'=>'margin-top:20px;'));

			$CI->make->sDivCol(6);
				$CI->make->file('menu_excel',array());
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
?>