<?php
function items_display($list = array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('success');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12,'right');
							$CI->make->A(fa('fa-plus').' Add New Item',base_url().'items/setup',array('class'=>'btn btn-primary'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array(
								'Code'=>'',
								'Name'=>'',
								'Category'=>'',
								'Subcategory'=>'',
								'Supplier'=>'',
								'Type'=>'',
								''=>array('width'=>'10%','align'=>'right')
							);
							$rows = array();
							foreach ($list as $val) {
								$link = "";
								$link .= $CI->make->A(fa('fa-pencil fa-lg fa-fw'),base_url().'items/setup/'.$val->item_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								// $link .= $CI->make->A(fa('fa-penci fa-lg fa-fw'),base_url().'items/setup/'.$val->item_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								// $link .= $CI->make->A(fa('fa-penci fa-lg fa-fw'),base_url().'items/setup/'.$val->item_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								$rows[] = array(
									$val->code,
									$val->name,
									$val->category,
									$val->subcategory,
									$val->supplier,
									$val->item_type,
									$link
								);
							}
							$CI->make->listLayout($th,$rows);
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function itemsSearchForm($post=array()){
	$CI =& get_instance();
	$CI->make->sForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->input('Item Name','name',null,null);
				$CI->make->categoriesDrop('Categories','cat_id',null,'- select category -');
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->inactiveDrop('Is Inactive','inactive',null,null,array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function items_form_container($item_id=null,$img=null){
	$CI =& get_instance();
		$CI->make->hidden('item_idx',$item_id);
		$CI->make->sDivRow();
			$CI->make->sDivCol(3);
				$CI->make->sBox('solid',array('style'=>'margin-bottom:5px;'));
					$CI->make->sBoxBody();
						$src = base_url().'img/noimage.png';
						if($item_id != null && $img != ""){
							$src = base_url().$img;
						}
						$CI->make->sDiv(array('style'=>'position:relative;width:100%;background-color:#ddd;'));
							$CI->make->img($src,array('style'=>'width:100%;max-height:250px;','id'=>'item-pic'));
							if($item_id != null){
								$CI->make->sDiv(array('style'=>'position:absolute;bottom:0;left:0;width:100%;height:30px;text-align:right;padding-right:5px;color:#fff'));
									$CI->make->A(fa('fa-camera fa-2x'),'#',array('style'=>'color:#fff;','id'=>'target','title'=>'Upload Picture'));
									$CI->make->sForm("items/image_db",array('id'=>'pic-form'));
										$CI->make->file('fileUpload',array('style'=>'display:none;'));
										$CI->make->hidden('upid',$item_id);	
									$CI->make->eForm();
								$CI->make->eDiv();
							}
						$CI->make->eDiv();
					$CI->make->eBoxBody();
				$CI->make->eBox();
						$list[fa('icon-info').' General Details'] = array('id'=>'details_link','class'=>'tab_link','load'=>'items/setup_load',
																			   'href'=>'#details',	
						                                        			   'style'=>'cursor:pointer;padding:10px;text-align:left;');
						$CI->make->listGroup($list);
			$CI->make->eDivCol();
			$CI->make->sDivCol(9,'left',0,array('style'=>'margin-bottom:50px;'));
				$CI->make->sBox('solid',array('style'=>'margin-bottom:5px;'));
				$CI->make->sBoxBody(array('id'=>'details'));
				$CI->make->eBoxBody();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();
}	
function items_form_container2($item_id){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->hidden('item_idx',$item_id);
		$CI->make->sDivCol(12);
			$CI->make->sTab();
				$tabs = array(
					"tab-title"=>$CI->make->a(fa('fa-reply')." Back To List",base_url().'items',array('return'=>true)),
					fa('icon-info')." General Details" => array('href'=>'#details','class'=>'tab_link','load'=>'items/setup_load','id'=>'details_link'),
				);
				$CI->make->tabHead($tabs,null,array());
				$CI->make->sTabBody();
					$CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
					$CI->make->eTabPane();
				$CI->make->eTabBody();
			$CI->make->eTab();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function items_details_form($info, $item_id){
	$CI =& get_instance();
	$CI->make->sForm("items/item_details_db",array('id'=>'item_details_form'));
		if (!empty($item_id)) {
			$CI->make->hidden('item_id',$item_id);
		}
		$back = $CI->make->A(fa('fa fa-reply')." Back ",base_url().'items',array('class'=>'btn pull-right btn-primary','style'=>'margin-top:-5px;','return'=>true));
		$CI->make->H(3,fa('icon-info').' General Details '.$back,array('class'=>'page-header'));
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->input('Item Name','name',iSetObj($info,'name'),'Item Name',array('class'=>'rOkay noSpecial'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->input('Item Code','code',iSetObj($info,'code'),'Item Code',array('class'=>'rOkay  noSpecial'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->input('Item Barcode','barcode',iSetObj($info,'barcode'),'Barcode',array('class'=>' noSpecial'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->textarea('Description','desc',iSetObj($info,'desc'),'Description',array('style'=>'height:110px;','class'=>'noSpecial'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->categoriesDrop('Category','cat_id',iSetObj($info,'cat_id'),'Select Category',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Inactive?','inactive',iSetObj($info,'inactive'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->itemSubcategoryDrop('Sub-category','subcat_id',iSetObj($info,'subcat_id'),'Select Subcategory');
				$CI->make->input('Brand','brand',iSetObj($info,'brand'),'Brand',array('class'=>'noSpecial'));
				// $CI->make->suppliersDrop('Supplier','supplier_id',iSetObj($info,'supplier_id'),'Select Supplier');
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		$CI->make->H(3,fa('icon-calculator').' Pricing Details',array('class'=>'page-header'));
		$CI->make->sDivRow();
			$CI->make->sDivCol(2);
				$CI->make->decimal('SRP','cost',iSetObj($info,'cost'),'Item Cost',3,array('class'=>'rOkay'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->itemTypeDrop('Item Type','type',iSetObj($info,'type'),'Item Type');
			$CI->make->eDivCol();
			$CI->make->sDivCol(2);
				$CI->make->decimal('Cost','costing',iSetObj($info,'costing'),'Item Cost',3,array('class'=>'rOkay'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		$CI->make->H(3,fa('icon-social-dropbox').' Inventory Details',array('class'=>'page-header'));
		$CI->make->sDivRow();
			$CI->make->sDivCol(3);
				$CI->make->uomDrop('UOM','uom',iSetObj($info,'uom'),"Select UOM",array('class'=>'rOkay'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(2);
				$CI->make->input('Reorder quantity','reorder_qty',iSetObj($info,'reorder_qty'),'Reorder point');
				// $CI->make->input('Packs per case','no_per_case',iSetObj($info,'no_per_case'),'Packs per case');
			$CI->make->eDivCol();
			$CI->make->sDivCol(2);
				$CI->make->input('Max quantity','max_qty',iSetObj($info,'max_qty'),'Maximum item count');
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		$CI->make->sDivRow(array('style'=>'margin-top:20px;'));			
			$CI->make->sDivCol(3);
				$CI->make->decimal('','no_per_pack',iSetObj($info,'no_per_pack'),'',2,array(),'',"<span id='uom-txt'></span>");
			$CI->make->eDivCol();
			$CI->make->sDivCol(1);
				$CI->make->H(3," = ",array('style'=>'margin:0px;margin-top:5px;'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(1);
				$CI->make->H(3," 1 ",array('style'=>'margin:0px;margin-top:5px;','class'=>'text-right'));
				// $CI->make->input('','max_qty',iSetObj($info,'max_qty'),'Maximum item count');
			$CI->make->eDivCol();
			$CI->make->sDivCol(2);
				// $CI->make->H(3,"<span id='uom-txt'></span> ",array('style'=>'margin:0px;margin-top:5px;'));
				$CI->make->uomDrop('','no_per_pack_uom',iSetObj($info,'no_per_pack_uom'),"Select UOM",array('class'=>'rOkay'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		$CI->make->H(3,"",array('class'=>'page-header'));
		$CI->make->sDivRow();
			$CI->make->sDivCol(4);
			$CI->make->eDivCol();
			$CI->make->sDivCol(4);
				$CI->make->button(fa('fa-save').' Submit',array('id'=>'save-btn','class'=>''),'primary');
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
function item_inventory_and_location_container($records, $loc_fields){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol(9);
		$CI->make->eDivCol();
		$CI->make->sDivCol(3);
			// $CI->make->button(fa('fa-print').' Download Excel',array('id'=>'print-btn','class'=>'btn-block'),'primary');
		$CI->make->eDivCol();
    $CI->make->eDivRow();
    $CI->make->append('<br>');
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('info');
				$CI->make->sBoxBody();
						$th = array(
							'Item Code'=>'',
							'Item Name'=>'',
							'Category'=>'',
							'Sub Category'=>'',
							'Qty Onhand'=>'',
						);
						$rows = array();

						// if (!empty($loc_fields)) {
						// 	foreach ($loc_fields as $unf => $frm) {
						// 		$th[$frm] = '';
						// 	}
						// 	// $th[''] = array('width'=>'10%','align'=>'right');
						// } else {
						// 	$rows[] = array('No records found','');
						// }


						foreach ($records as $val) {
							$item_array = array($val['code'],$val['name'],$val['cat_name'],$val['sub_cat_name'],$val['qoh'],);

							// foreach ($loc_fields as $unfx => $frmx) {
							// 	$item_array[] = (!empty($val[$unfx]) ? num($val[$unfx])." ".$val['uom'] : null);
							// }

							// $item_array[] = '';
							$rows[] = $item_array;
						}
						$CI->make->listLayout($th,$rows);
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function invMovePage(){
	$CI =& get_instance();
	$CI->make->sBox('solid');
		$CI->make->sBoxBody();
			$CI->make->sDivRow();
				$CI->make->sForm("items/get_inv_move",array('id'=>'general-form'));
				$CI->make->sDivCol(4);
					$now = $CI->site_model->get_db_now();
					$start = sql2Date($now)." 5:00 AM";
					$to = sql2Date($now)." 11:00 PM";
					$range = $start." to ".$to;
					$CI->make->input('Date & Time Range','calendar_range',"",null,array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),fa('fa-calendar'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->invTypeDrop('Type','type',null,array());
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->itemAjaxDrop('Item','item-search',null,array());
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					// $CI->make->button(fa('fa-search').' Search',array('id'=>'search-btn','style'=>'margin-top:23px;margin-right:10px;'),'primary');
					$CI->make->button(fa('fa-search').' Search',array('id'=>'search-btn','style'=>'margin-top:23px;margin-right:10px;'),'primary');
					$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'print-btn','style'=>'margin-top:23px;;margin-right:10px;'),'success');
					$CI->make->button(fa('fa-file-pdf-o').' PDF',array('id'=>'print-btn-pdf','style'=>'margin-top:23px;'),'success');
					// $CI->make->button(fa('fa-file-pdf-o').' PDF',array('id'=>'print-btn','style'=>'margin-top:23px;'),'success');
				$CI->make->eDivCol();
				$CI->make->eForm();
			$CI->make->eDivRow();	
		$CI->make->eBoxBody();
	$CI->make->eBox();
	$CI->make->sBox('solid');
		$CI->make->sBoxBody(array('class'=>'no-padding','id'=>'general-div'));
			$CI->make->sTable(array('class'=>'table ','id'=>'general-tbl'));
			    $CI->make->sTableHead();
			        $CI->make->sRow();
			            $CI->make->th('Description',array('style'=>'width:100px;'));
			            $CI->make->th('Reference #',array('style'=>'width:100px;'));
			            $CI->make->th('Trans Date',array('style'=>'width:150px;'));
			            $CI->make->th('Item Name',array());
			            $CI->make->th('UOM',array('class'=>'text-center','style'=>'width:50px;'));
			            $CI->make->th('Qty In',array('class'=>'text-right','style'=>'width:80px;'));
			            $CI->make->th('Qty Out',array('class'=>'text-right','style'=>'width:80px;'));
			            $CI->make->th('QOH',array('class'=>'text-right','style'=>'width:80px;border-right:5px solid #fff !important;'));
			            $CI->make->th('UOM',array('class'=>'text-center','style'=>'width:50px;'));
			            $CI->make->th('Qty In',array('class'=>'text-right','style'=>'width:80px;'));
			            $CI->make->th('Qty Out',array('class'=>'text-right','style'=>'width:80px;'));
			            $CI->make->th('QOH',array('class'=>'text-right','style'=>'width:80px;'));
			        $CI->make->eRow();
			    $CI->make->eTableHead();
			    $CI->make->sTableBody();
			    $CI->make->eTableBody();
			$CI->make->eTable();    
		$CI->make->eBoxBody();
		$CI->make->append('<div id="editor"></div>');
	$CI->make->eBox();	
	return $CI->make->code();
}
function menuUploadForm(){
	$CI =& get_instance();
	$CI->make->H(3,'Warning! THIS WILL REPLACE ALL THE ITEMS.',array('class'=>'label label-warning','style'=>'margin-bottom:50px;font-size:24px;'));
	// $CI->make->sForm('menu/upload_excel_db',array('id'=>"upload-form",'enctype'=>'multipart/form-data'));
	$CI->make->sForm('items/upload_excel_db',array('id'=>"upload-form",'enctype'=>'multipart/form-data'));
		$CI->make->sDivRow(array('style'=>'margin-top:30px;'));
			$CI->make->sDivCol(6);
				$CI->make->file('item_excel',array());
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}


// for menu movement
function menuMovePage(){
	$CI =& get_instance();
	$CI->make->sBox('solid');
		$CI->make->sBoxBody();
			$CI->make->sDivRow();
				$CI->make->sForm("items/get_menu_move",array('id'=>'general-form'));
				$CI->make->sDivCol(4);
					$now = $CI->site_model->get_db_now();
					$start = sql2Date($now)." 5:00 AM";
					$to = sql2Date($now)." 11:00 PM";
					$range = $start." to ".$to;
					$CI->make->input('Date Range','calendar_range',"",null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
				$CI->make->eDivCol();
				// $CI->make->sDivCol(4);
				// 	$CI->make->invTypeDrop('Type','type',null,array());
				// $CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->menuAjaxDrop('Item','menu-search',null,array());
				$CI->make->eDivCol();
				$CI->make->sDivCol(12,'center');
					$CI->make->button(fa('fa-search').' Search',array('id'=>'search-btn','style'=>'margin-top:23px;margin-right:10px;'),'primary');
					// $CI->make->button(fa('fa-file-pdf-o').' PDF',array('id'=>'print-btn','style'=>'margin-top:23px;'),'success');
					$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'menu-print-btn','style'=>'margin-top:23px;;margin-right:10px;'),'success');
					$CI->make->button(fa('fa-file-pdf-o').' PDF',array('id'=>'menu-print-btn-pdf','style'=>'margin-top:23px;'),'success');
				$CI->make->eDivCol();
				$CI->make->eForm();
			$CI->make->eDivRow();	
		$CI->make->eBoxBody();
	$CI->make->eBox();
	$CI->make->sBox('solid');
		$CI->make->sBoxBody(array('class'=>'no-padding','id'=>'general-div'));
			$CI->make->sTable(array('class'=>'table reportTBL','id'=>'general-tbl'));
			    $CI->make->sTableHead();
			        $CI->make->sRow();
			            $CI->make->th('Description',array('style'=>'width:100px;'));
			            $CI->make->th('Reference #',array('style'=>'width:100px;'));
			            $CI->make->th('Trans Date',array('style'=>'width:150px;'));
			            $CI->make->th('Item Name',array());
			            // $CI->make->th('UOM',array('class'=>'text-center','style'=>'width:50px;'));
			            $CI->make->th('Qty In',array('class'=>'text-right','style'=>'width:80px;'));
			            $CI->make->th('Qty Out',array('class'=>'text-right','style'=>'width:80px;'));
			            $CI->make->th('QOH',array('class'=>'text-right','style'=>'width:80px;border-right:5px solid #fff !important;'));
			            // $CI->make->th('UOM',array('class'=>'text-center','style'=>'width:50px;'));
			            // $CI->make->th('Qty In',array('class'=>'text-right','style'=>'width:80px;'));
			            // $CI->make->th('Qty Out',array('class'=>'text-right','style'=>'width:80px;'));
			            // $CI->make->th('QOH',array('class'=>'text-right','style'=>'width:80px;'));
			        $CI->make->eRow();
			    $CI->make->eTableHead();
			    $CI->make->sTableBody();
			    $CI->make->eTableBody();
			$CI->make->eTable();    
		$CI->make->eBoxBody();
		$CI->make->append('<div id="editor"></div>');
	$CI->make->eBox();	
	return $CI->make->code();
}
function menuHistoryPage(){
	$CI =& get_instance();
	$CI->make->sBox('solid');
		$CI->make->sBoxBody();
			$CI->make->sDivRow();
				$CI->make->sForm("items/get_menu_history",array('id'=>'general-form'));
				$CI->make->sDivCol(4);
					$now = $CI->site_model->get_db_now();
					$start = sql2Date($now)." 5:00 AM";
					$to = sql2Date($now)." 11:00 PM";
					$range = $start." to ".$to;
					$CI->make->input('Date Range','calendar_range',"",null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->menuAjaxDrop('Menu','menu-search',null,array());
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->button(fa('fa-search').' Search',array('id'=>'search-btn','style'=>'margin-top:23px;margin-right:10px;'),'primary');
					$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'print-excel','style'=>'margin-top:23px;'),'success');
				$CI->make->eDivCol();
				$CI->make->eForm();
			$CI->make->eDivRow();	
		$CI->make->eBoxBody();
	$CI->make->eBox();
	$CI->make->sBox('solid');
		$CI->make->sBoxBody(array('class'=>'no-padding','id'=>'general-div'));
			$CI->make->sTable(array('class'=>'table reportTBL','id'=>'general-tbl'));
			    $CI->make->sTableHead();
			        $CI->make->sRow();
			            $CI->make->th('Type',array('style'=>'width:100px;'));
			            $CI->make->th('Reference #',array('style'=>'width:100px;'));
			            $CI->make->th('Trans Date',array('style'=>'width:150px;'));
			            $CI->make->th('User',array('style'=>'width:110px;'));
			            $CI->make->th('Action',array());
			            // $CI->make->th('UOM',array('class'=>'text-center','style'=>'width:50px;'));
			            $CI->make->th('Qty',array('class'=>'text-right','style'=>'width:80px;'));
			            // $CI->make->th('Remarks',array('class'=>'text-right','style'=>'width:80px;'));
			            // $CI->make->th('QOH',array('class'=>'text-right','style'=>'width:80px;border-right:5px solid #fff !important;'));
			            // $CI->make->th('UOM',array('class'=>'text-center','style'=>'width:50px;'));
			            // $CI->make->th('Qty In',array('class'=>'text-right','style'=>'width:80px;'));
			            // $CI->make->th('Qty Out',array('class'=>'text-right','style'=>'width:80px;'));
			            // $CI->make->th('QOH',array('class'=>'text-right','style'=>'width:80px;'));
			        $CI->make->eRow();
			    $CI->make->eTableHead();
			    $CI->make->sTableBody();
			    $CI->make->eTableBody();
			$CI->make->eTable();    
		$CI->make->eBoxBody();
		$CI->make->append('<div id="editor"></div>');
	$CI->make->eBox();	
	return $CI->make->code();
}
// for modifier inventory movement
function modInvMovesPage(){
	$CI =& get_instance();
	$CI->make->sBox('solid');
		$CI->make->sBoxBody();
			$CI->make->sDivRow();
				$CI->make->sForm("items/get_mod_inv_moves",array('id'=>'general-form'));
				$CI->make->sDivCol(4);
					$now = $CI->site_model->get_db_now();
					$start = sql2Date($now)." 5:00 AM";
					$to = sql2Date($now)." 11:00 PM";
					$range = $start." to ".$to;
					$CI->make->input('Date Range','calendar_range',"",null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),fa('fa-calendar'));
				$CI->make->eDivCol();
					$CI->make->sDivCol(4);
					$CI->make->invTypeDrop('Type','type',null,array());
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					// $CI->make->menuAjaxDrop('Menu','menu-search',null,array());
					$CI->make->modifiersAjaxDrop('Modifier','menu-search',null,array());
				$CI->make->eDivCol();
				$CI->make->sDivCol(12,'center');
					$CI->make->button(fa('fa-search').' Search',array('id'=>'search-btn','style'=>'margin-top:23px;margin-right:10px;'),'primary');
					// $CI->make->button(fa('fa-file-pdf-o').' PDF',array('id'=>'print-btn','style'=>'margin-top:23px;'),'success');
					$CI->make->button(fa('fa-file-excel-o').' Excel',array('id'=>'menu-print-btn','style'=>'margin-top:23px;;margin-right:10px;'),'success');
					$CI->make->button(fa('fa-file-pdf-o').' PDF',array('id'=>'menu-print-btn-pdf','style'=>'margin-top:23px;'),'success');
				$CI->make->eDivCol();
				$CI->make->eForm();
			$CI->make->eDivRow();	
		$CI->make->eBoxBody();
	$CI->make->eBox();
	$CI->make->sBox('solid');
		$CI->make->sBoxBody(array('class'=>'no-padding','id'=>'general-div'));
			$CI->make->sTable(array('class'=>'table reportTBL','id'=>'general-tbl'));
			    $CI->make->sTableHead();
			        $CI->make->sRow();
			            $CI->make->th('Description',array('style'=>'width:100px;'));
			            $CI->make->th('Reference #',array('style'=>'width:100px;'));
			            $CI->make->th('Trans Date',array('style'=>'width:150px;'));
			            $CI->make->th('Item Name',array());
			            // $CI->make->th('UOM',array('class'=>'text-center','style'=>'width:50px;'));
			            $CI->make->th('Qty In',array('class'=>'text-right','style'=>'width:80px;'));
			            $CI->make->th('Qty Out',array('class'=>'text-right','style'=>'width:80px;'));
			            $CI->make->th('QOH',array('class'=>'text-right','style'=>'width:80px;border-right:5px solid #fff !important;'));
			            // $CI->make->th('UOM',array('class'=>'text-center','style'=>'width:50px;'));
			            // $CI->make->th('Qty In',array('class'=>'text-right','style'=>'width:80px;'));
			            // $CI->make->th('Qty Out',array('class'=>'text-right','style'=>'width:80px;'));
			            // $CI->make->th('QOH',array('class'=>'text-right','style'=>'width:80px;'));
			        $CI->make->eRow();
			    $CI->make->eTableHead();
			    $CI->make->sTableBody();
			    $CI->make->eTableBody();
			$CI->make->eTable();    
		$CI->make->eBoxBody();
		$CI->make->append('<div id="editor"></div>');
	$CI->make->eBox();	
	return $CI->make->code();
}