<?php
function receivingListPage($list=array()){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12,'right');
							 $CI->make->A(fa('fa-plus').' Create Receive',base_url().'receiving/form',array('class'=>'btn btn-success'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
                	$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array(
									'Date'=>'',
									'Reference'=>'',
									'Supplier'=>'',
									'Supplier Reference'=>'',
									'Received By'=>'',
									' '=>array('width'=>'12%','align'=>'right'));
							$rows = array();
							foreach($list as $res){
								$links = "";
								// $links .= $CI->make->A(fa('fa-edit fa-2x fa-fw'),base_url().'menu/form/'.$v->menu_id,array("return"=>true));
								$rows[] = array(
											  sql2Date($res->reg_date),
											  $res->trans_ref,
											  $res->supplier_name,
											  $res->reference,
											  $res->username,
											  $links
									);
							}
							$CI->make->listLayout($th,$rows);
						$CI->make->eDivCol();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}
function receivingFormPage($ref=null){
	$CI =& get_instance();
	$now = $CI->site_model->get_db_now();
	$CI->make->sDivRow();
		$CI->make->sDivCol(3);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("receiving/add_item",array('id'=>'add_item_form'));
						// $CI->make->input('Item','item-search',null,'Search Item',array('search-url'=>'mods/search_items','class'=>'rOkay'),'',fa('fa-search'));
						$CI->make->itemAjaxDrop('Item','item-search',null,array());						
						$CI->make->hidden('item-id',null);
						$CI->make->hidden('item-uom',null);
						$CI->make->hidden('item-ppack',null);
						$CI->make->hidden('item-ppack-uom',null);
						$CI->make->hidden('item-pcase',null);
						$CI->make->sDivRow();
							$CI->make->sDivCol(6);
								$CI->make->decimal('Quantity','qty',null,null,2,array('class'=>'rOkay'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(6);
								$CI->make->select('&nbsp;','select-uom',array(),null,array('class'=>'rOkay'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();

						/* hide field */
						// $CI->make->decimal('Total Cost','cost',null,null,2,array());
						$CI->make->locationsDrop('Receiving Location','loc_id',null,null,array('shownames'=>true));
						$CI->make->button(fa('fa-plus').' Add Item',array('class'=>'btn-block','id'=>'add-item-btn'),'primary');
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
		#FORM
		$CI->make->sDivCol(9);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("receiving/save",array('id'=>'receive_form'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'right');
								$CI->make->button(fa('fa-download').' Receive',array('id'=>'save-btn','style'=>'margin-right:10px;'),'success');
								$CI->make->button(fa('fa-reply').' Back',array('id'=>'back-btn','style'=>''),'primary');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();

							$CI->make->sDivCol(3);
								$CI->make->append('
                                    <label class="control-label col-md-12 col-lg-12 col-sm-12" style="margin-left:-14px;">Reference </label>
                                    <div class="form-group">
                                        <div class="input-group input-icon right">
                                            <span id="reference_icon" class="input-group-addon primary" style="color: #27a4b0;background-color: #337ab7;">
                                            <i class="fa fa-search font-white"></i>
                                            </span>
                                            <input type="text" class="form-control" placeholder="0000001" name="reference" id="reference" value="" > 
                                        </div>
                                    </div>
									');
							$CI->make->eDivCol();

							// $CI->make->sDivCol(3);
							// 	$CI->make->input('Reference','reference',$ref,'Supplier reference',array('class'=>'rOkay'));
							// $CI->make->eDivCol();
							$CI->make->sDivCol(3);
								$CI->make->suppliersDrop('Supplier','suppliers',null,null,array());
							$CI->make->eDivCol();
							$CI->make->sDivCol(3);
								$CI->make->date('Date','trans_date',sql2Date($now),null,array('class'=>'rOkay','ro-msg'=>'Date must not be empty'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(3);
								$CI->make->time('Time','trans_time',null,null,array('class'=>'rOkay','ro-msg'=>'Time must not be empty'));
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
										// $CI->make->th('Cost',array('style'=>'width:60px;'));
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
							$CI->make->sDivCol(8);
								$CI->make->input('','delivered_by',"",'Delivered By',array('class'=>'rOkay'));
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
				$CI->make->suppliersDrop('Supplier','supplier_id',null,null,array());
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->inactiveDrop('Voided','void',null,null,array());
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function receivingMenuFormPage($ref=null){
	$CI =& get_instance();
	$now = $CI->site_model->get_db_now();
	$CI->make->sDivRow();
		$CI->make->sDivCol(3);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("receiving/add_menu",array('id'=>'add_menu_form'));
						// $CI->make->input('Item','item-search',null,'Search Item',array('search-url'=>'mods/search_items','class'=>'rOkay'),'',fa('fa-search'));
						$CI->make->menuAjaxDrop('Item','menu-search',null,array('class'=>'rOkay'));						
						// $CI->make->hidden('item-id',null);
						// $CI->make->hidden('item-uom',null);
						// $CI->make->hidden('item-ppack',null);
						// $CI->make->hidden('item-ppack-uom',null);
						// $CI->make->hidden('item-pcase',null);
						$CI->make->sDivRow();
							$CI->make->sDivCol(12);
								$CI->make->decimal('Quantity','qty',null,null,2,array('class'=>'rOkay'));
							$CI->make->eDivCol();
							// $CI->make->sDivCol(6);
							// 	$CI->make->select('&nbsp;','select-uom',array(),null,array('class'=>'rOkay'));
							// $CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->decimal('Total Cost','cost',null,null,2,array('class'=>'rOkay'));
						// $CI->make->locationsDrop('Receiving Location','loc_id',null,null,array('shownames'=>true));
						$CI->make->button(fa('fa-plus').' Add Menu',array('class'=>'btn-block','id'=>'add-item-btn'),'primary');
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
		#FORM
		$CI->make->sDivCol(9);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("receiving/save_menu",array('id'=>'receive_form'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(6);
							$CI->make->eDivCol();
							$CI->make->sDivCol(6,'right');
								$CI->make->button(fa('fa-download').' Receive',array('id'=>'save-btn','style'=>'margin-top:25px;margin-right:10px;'),'success');
								$CI->make->button(fa('fa-reply').' Back',array('id'=>'back-btn','style'=>'margin-top:25px'),'primary');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(3);
								$CI->make->input('Reference','reference',$ref,'Supplier reference',array('class'=>'rOkay'));
							$CI->make->eDivCol();
							// $CI->make->sDivCol(3);
							// 	$CI->make->suppliersDrop('Supplier','suppliers',null,null,array());
							// $CI->make->eDivCol();
							$CI->make->sDivCol(3);
								$CI->make->date('Date','trans_date',sql2Date($now),null,array('class'=>'rOkay datepicker','ro-msg'=>'Date must not be empty'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(3);
								$CI->make->time('Time','trans_time',null,null,array('class'=>'rOkay','ro-msg'=>'Time must not be empty'));
							// $CI->make->time('Time','trans_time',null,'Start Time');
							$CI->make->eDivCol();
							// $CI->make->sDivCol(3,'right');
							// 	$CI->make->button(fa('fa-download').' Receive',array('id'=>'save-btn','style'=>'margin-top:25px;margin-right:10px;'),'success');
							// 	$CI->make->button(fa('fa-reply').' Back',array('id'=>'back-btn','style'=>'margin-top:25px'),'primary');
							// $CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol();
								$CI->make->sDiv(array('class'=>'table-responsive'));
								$CI->make->sTable(array('class'=>'table table-hover','id'=>'details-tbl','style'=>'border-bottom:2px solid #ddd;border-top:2px solid #ddd;margin-bottom:5px;'));
									$CI->make->sRow();
										$CI->make->th('MENU');
										$CI->make->th('QTY',array('style'=>'width:60px;'));
										// $CI->make->th('UOM',array('style'=>'width:180px;'));
										$CI->make->th('Cost',array('style'=>'width:60px;'));
										$CI->make->th('&nbsp;',array('style'=>'width:40px;'));
									$CI->make->eRow();
									$CI->make->sRow(array('class'=>'no-items-row'));
										$CI->make->th('No Menu',array('colspan'=>'5','style'=>'text-align:center'));
									$CI->make->eRow();
								$CI->make->eTable();
								$CI->make->eDiv();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(8);
								$CI->make->textarea('','memo',null,'Add Remarks Here...',array());
							$CI->make->eDivCol();
							$CI->make->sDivCol(4);
								$CI->make->sDivRow();
									$CI->make->sDivCol(6);
										$CI->make->H(3,'Total');
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->H(3,'0.00',array('id'=>'grand-total','style'=>'text-align:right;margin-right:10px;'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();


	$CI->make->eDivRow();

	return $CI->make->code();
}
function receivingMenuSearch($post=array()){
	$CI =& get_instance();
	$CI->make->sForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->input('Reference','trans_ref',null,null);
				// $CI->make->suppliersDrop('Supplier','supplier_id',null,null,array());
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->inactiveDrop('Voided','void',null,null,array());
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function viewOrderInfo($details=null,$head=null){
	$CI =& get_instance();
	$CI->make->H(4,"Receiving Information",array('class'=>''));
	$CI->make->sBox('success',array('div-form'));
    	$CI->make->sDivRow();
    		$CI->make->sDivCol(3);
				$CI->make->H(5,'Transaction Date & TIme:');
			$CI->make->eDivCol();
			$CI->make->sDivCol(2);
				$CI->make->H(5,date('m/d/Y H:i:s',strtotime($head[0]->trans_date)));
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->H(5,'Supplier:');
			$CI->make->eDivCol();
			$CI->make->sDivCol(2);
				$CI->make->H(5,$head[0]->supp_name);
			$CI->make->eDivCol();
		
    	$CI->make->eDivRow();
    	$CI->make->sDivRow();
    			$CI->make->sDivCol(3);
				$CI->make->H(5,'Delivered By:');
			$CI->make->eDivCol();
			$CI->make->sDivCol(2);
				$CI->make->H(5,$head[0]->delivered_by);
			$CI->make->eDivCol();
    	// $CI->make->eDivRow();
    	// $CI->make->sDivRow();
    		$CI->make->sDivCol(2);
				$CI->make->H(5,'Remarks:');
			$CI->make->eDivCol();
			$CI->make->sDivCol(4);
				$CI->make->H(5,$head[0]->memo);
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eBox();

	$CI->make->H(4,"Details",array('class'=>''));
	$CI->make->sBox('success',array('div-form'));
		$CI->make->sDiv(array('class'=>'table-responsive'));
			$CI->make->sTable(array('class'=>'table table-hover','id'=>'details-tbl'));
				$CI->make->sRow();
					$CI->make->th('Item');
					$CI->make->th('Quantity');
					$CI->make->th('UOM');
					// $CI->make->th('Cost');
					// $CI->make->th('Total');
				$CI->make->eRow();
				$dn_type_no = array();
				foreach($details as $val){

					$CI->make->sRow();
						$CI->make->td($val->item_name);
						$CI->make->td($val->qty);
				 		$CI->make->td($val->uom);
				 		// $CI->make->td(num($val->price));
				 		// $CI->make->td(num($val->qty * $val->price));
					$CI->make->eRow();

					// $DNgrand_total += $val->t_amount;
					// $dn_type_no[] = $val->type_no;
				}
				// $CI->make->sRow();
				// $CI->make->th('');
				// $CI->make->th('');
				// $CI->make->th('Total Amount');
				// $CI->make->th(Num($DNgrand_total));

				// $CI->make->eRow();
			$CI->make->eTable();
		$CI->make->eDiv();
	$CI->make->eBox();
	$CI->make->sDivRow(array('style'=>'margin:10px'));
		$CI->make->sDivCol(12,'center');
			$print = $CI->make->A(fa('fa-file-pdf-o fa-lg fa-fw').' PDF','#',array('return'=>'true','title'=>'Print','id'=>'printPreview','ref'=>$head[0]->receiving_id));
			$print2 = $CI->make->A(fa('fa-file-excel-o fa-lg fa-fw').' Excel','#',array('return'=>'true','title'=>'Print','id'=>'printExcel','ref'=>$head[0]->receiving_id));
			$CI->make->H(5,$print." &nbsp;&nbsp;&nbsp;".$print2);
		$CI->make->eDivCol();
	$CI->make->eDivRow();


	return $CI->make->code();
}
function receivingCheckUploadForm(){
	$CI =& get_instance();
	// $CI->make->H(3,'Warning! THIS WILL REPLACE ALL THE MENUS.',array('class'=>'label label-warning','style'=>'margin-bottom:50px;font-size:24px;'));
	// $CI->make->sForm('menu/upload_excel_db',array('id'=>"upload-form",'enctype'=>'multipart/form-data'));
	$CI->make->sForm('receiving/upload_excel_db',array('id'=>"upload-form",'enctype'=>'multipart/form-data'));
		$CI->make->sDivRow(array('style'=>''));
			$CI->make->sDivCol(12,'left');
				$CI->make->append('<button id="receiving_template" class="btn-sm btn blue" type="button" style="margin-right:6px;"><i class="fa fa-download "></i> Download Template</button>');
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

function receivingMenuUploadForm(){
	$CI =& get_instance();
	$CI->make->sForm('receiving_menu/upload_menu_db',array('id'=>"upload-form",'enctype'=>'multipart/form-data'));
		$CI->make->sDivRow(array('style'=>''));
			$CI->make->sDivCol(12,'left');
				$CI->make->append('<button id="receiving_template" class="btn-sm btn blue" type="button" style="margin-right:6px;"><i class="fa fa-download "></i> Download Template</button>');
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

function viewOrderMenuInfo($details=null,$head=null){
	$CI =& get_instance();
	$CI->make->H(4,"Receiving Menu Information",array('class'=>''));
	$CI->make->sBox('success',array('div-form'));
    	$CI->make->sDivRow();
    		$CI->make->sDivCol(3);
				$CI->make->H(5,'Transaction Date & TIme:');
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->H(5,date('m/d/Y H:i:s',strtotime($head[0]->reg_date)));
			$CI->make->eDivCol();
			// $CI->make->sDivCol(2);
			// 	$CI->make->H(5,'Supplier:');
			// $CI->make->eDivCol();
			// $CI->make->sDivCol(3);
			// 	$CI->make->H(5,$head[0]->supp_name);
			// $CI->make->eDivCol();
    	$CI->make->eDivRow();
    	$CI->make->sDivRow();
    		$CI->make->sDivCol(3);
				$CI->make->H(5,'Remarks:');
			$CI->make->eDivCol();
			$CI->make->sDivCol(9);
				$CI->make->H(5,$head[0]->memo);
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eBox();

	$CI->make->H(4,"Details",array('class'=>''));
	$CI->make->sBox('success',array('div-form'));
		$CI->make->sDiv(array('class'=>'table-responsive'));
			$CI->make->sTable(array('class'=>'table table-hover','id'=>'details-tbl'));
				$CI->make->sRow();
					$CI->make->th('Item');
					$CI->make->th('Quantity');
					$CI->make->th('UOM');
					$CI->make->th('Cost');
					$CI->make->th('Total');
				$CI->make->eRow();
				$dn_type_no = array();
				foreach($details as $val){

					$CI->make->sRow();
						$CI->make->td($val->menu_name);
						$CI->make->td($val->qty);
				 		$CI->make->td($val->uom);
				 		$CI->make->td(num($val->price));
				 		$CI->make->td(num($val->qty * $val->price));
					$CI->make->eRow();

					// $DNgrand_total += $val->t_amount;
					// $dn_type_no[] = $val->type_no;
				}
				// $CI->make->sRow();
				// $CI->make->th('');
				// $CI->make->th('');
				// $CI->make->th('Total Amount');
				// $CI->make->th(Num($DNgrand_total));

				// $CI->make->eRow();
			$CI->make->eTable();
		$CI->make->eDiv();
	$CI->make->eBox();
	$CI->make->sDivRow(array('style'=>'margin:10px'));
		$CI->make->sDivCol(12,'center');
			$print = $CI->make->A(fa('fa-file-pdf-o fa-lg fa-fw').' PDF','#',array('return'=>'true','title'=>'Print','id'=>'printPreview','ref'=>$head[0]->receiving_id));
			$print2 = $CI->make->A(fa('fa-file-excel-o fa-lg fa-fw').' Excel','#',array('return'=>'true','title'=>'Print','id'=>'printExcel','ref'=>$head[0]->receiving_id));
			$CI->make->H(5,$print." &nbsp;&nbsp;&nbsp;".$print2);
		$CI->make->eDivCol();
	$CI->make->eDivRow();


	return $CI->make->code();
}
function adjustmentMenuFormPage($ref=null){
	$CI =& get_instance();
	$now = $CI->site_model->get_db_now();
	$CI->make->sDivRow();
		$CI->make->sDivCol(3);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("receiving/add_menu_adjustment",array('id'=>'add_menu_form'));
						// $CI->make->input('Item','item-search',null,'Search Item',array('search-url'=>'mods/search_items','class'=>'rOkay'),'',fa('fa-search'));
						$CI->make->menuAjaxDrop('Item','menu-search',null,array('class'=>'rOkay'));						
						// $CI->make->hidden('item-id',null);
						// $CI->make->hidden('item-uom',null);
						// $CI->make->hidden('item-ppack',null);
						// $CI->make->hidden('item-ppack-uom',null);
						// $CI->make->hidden('item-pcase',null);
						$CI->make->sDivRow();
							$CI->make->sDivCol(12);
								$CI->make->decimal('Quantity','qty',null,null,2,array('class'=>'rOkay'));
							$CI->make->eDivCol();
							// $CI->make->sDivCol(6);
							// 	$CI->make->select('&nbsp;','select-uom',array(),null,array('class'=>'rOkay'));
							// $CI->make->eDivCol();
						$CI->make->eDivRow();
						// $CI->make->decimal('Total Cost','cost',null,null,2,array('class'=>'rOkay'));
						// $CI->make->locationsDrop('Receiving Location','loc_id',null,null,array('shownames'=>true));
						$CI->make->button(fa('fa-plus').' Add Item',array('class'=>'btn-block','id'=>'add-item-btn'),'primary');
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
		#FORM
		$CI->make->sDivCol(9);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("receiving/save_menu_adjustment",array('id'=>'adjust_form'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(3);
								// $CI->make->input('Reference','reference',$ref,'Supplier reference',array('class'=>'rOkay'));
							$CI->make->eDivCol();
							// $CI->make->sDivCol(3);
							// 	$CI->make->suppliersDrop('Supplier','suppliers',null,null,array());
							// $CI->make->eDivCol();
							$CI->make->sDivCol(3);
								// $CI->make->date('Date','trans_date',sql2Date($now),null,array('class'=>'rOkay datepicker','ro-msg'=>'Date must not be empty'));
							$CI->make->eDivCol();
							
							$CI->make->sDivCol(6,'right');
								$CI->make->button(fa('fa-download').' Adjust',array('id'=>'save-btn','style'=>'margin-bottom:10px;margin-right:10px;'),'success');
								$CI->make->button(fa('fa-reply').' Back',array('id'=>'back-btn','style'=>'margin-bottom:10px'),'primary');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(3);
								$CI->make->input('Reference','reference',$ref,'Supplier reference',array('class'=>'rOkay'));
							$CI->make->eDivCol();
							// $CI->make->sDivCol(3);
							// 	$CI->make->suppliersDrop('Supplier','suppliers',null,null,array());
							// $CI->make->eDivCol();
							$CI->make->sDivCol(3);
								$CI->make->date('Date','trans_date',sql2Date($now),null,array('class'=>'rOkay datepicker','ro-msg'=>'Date must not be empty'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(3);
								$CI->make->time('Time','trans_time',null,null,array('class'=>'rOkay','ro-msg'=>'Time must not be empty'));
							// $CI->make->time('Time','trans_time',null,'Start Time');
							$CI->make->eDivCol();
							$CI->make->sDivCol(3,'left');
								$CI->make->adjustmentTypeDrop('Adjustment Type','type',null,null,array());
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol();
								$CI->make->sDiv(array('class'=>'table-responsive'));
								$CI->make->sTable(array('class'=>'table table-hover','id'=>'details-tbl','style'=>'border-bottom:2px solid #ddd;border-top:2px solid #ddd;margin-bottom:5px;'));
									$CI->make->sRow();
										$CI->make->th('ITEM');
										$CI->make->th('QTY',array('style'=>'width:60px;'));
										// $CI->make->th('UOM',array('style'=>'width:180px;'));
										// $CI->make->th('Cost',array('style'=>'width:60px;'));
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
							$CI->make->sDivCol(8);
								$CI->make->textarea('','memo',null,'Add Remarks Here...',array());
							$CI->make->eDivCol();
							$CI->make->sDivCol(4);
								$CI->make->sDivRow();
									$CI->make->sDivCol(6);
										$CI->make->H(3,'Total');
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->H(3,'0.00',array('id'=>'grand-total','style'=>'text-align:right;margin-right:10px;'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();


	$CI->make->eDivRow();

	return $CI->make->code();
}
function viewAdjMenuInfo($details=null,$head=null){
	$CI =& get_instance();
	$CI->make->H(4,"Adjustment Item Information",array('class'=>''));
	$CI->make->sBox('success',array('div-form'));
    	$CI->make->sDivRow();
    		$CI->make->sDivCol(6);
				$CI->make->H(5,'Transaction Date & TIme:');
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->H(5,date('m/d/Y H:i:s',strtotime($head[0]->reg_date)));
			$CI->make->eDivCol();
			// $CI->make->sDivCol(2);
			// 	$CI->make->H(5,'Supplier:');
			// $CI->make->eDivCol();
			// $CI->make->sDivCol(3);
			// 	$CI->make->H(5,$head[0]->supp_name);
			// $CI->make->eDivCol();
    	$CI->make->eDivRow();
    	$CI->make->sDivRow();
    		$CI->make->sDivCol(6);
				$CI->make->H(5,'Remarks:');
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->H(5,$head[0]->memo);
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eBox();

	$CI->make->H(4,"Details",array('class'=>''));
	$CI->make->sBox('success',array('div-form'));
		$CI->make->sDiv(array('class'=>'table-responsive'));
			$CI->make->sTable(array('class'=>'table table-hover','id'=>'details-tbl'));
				$CI->make->sRow();
					$CI->make->th('Item');
					$CI->make->th('Quantity');
					// $CI->make->th('UOM');
					// $CI->make->th('Cost');
					// $CI->make->th('Total');
				$CI->make->eRow();
				$dn_type_no = array();
				foreach($details as $val){

					$CI->make->sRow();
						$CI->make->td($val->menu_name);
						$CI->make->td($val->qty);
				 		// $CI->make->td($val->uom);
				 		// $CI->make->td(num($val->price));
				 		// $CI->make->td(num($val->qty * $val->price));
					$CI->make->eRow();

					// $DNgrand_total += $val->t_amount;
					// $dn_type_no[] = $val->type_no;
				}
				// $CI->make->sRow();
				// $CI->make->th('');
				// $CI->make->th('');
				// $CI->make->th('Total Amount');
				// $CI->make->th(Num($DNgrand_total));

				// $CI->make->eRow();
			$CI->make->eTable();
		$CI->make->eDiv();
	$CI->make->eBox();
	$CI->make->sDivRow(array('style'=>'margin:10px'));
		$CI->make->sDivCol(12,'center');
			$print = $CI->make->A(fa('fa-file-pdf-o fa-lg fa-fw').' PDF','#',array('return'=>'true','title'=>'Print','id'=>'printPreview','ref'=>$head[0]->adjustment_id));
			$print2 = $CI->make->A(fa('fa-file-excel-o fa-lg fa-fw').' Excel','#',array('return'=>'true','title'=>'Print','id'=>'printExcel','ref'=>$head[0]->adjustment_id));
			$CI->make->H(5,$print." &nbsp;&nbsp;&nbsp;".$print2);
		$CI->make->eDivCol();
	$CI->make->eDivRow();


	return $CI->make->code();
}

?>