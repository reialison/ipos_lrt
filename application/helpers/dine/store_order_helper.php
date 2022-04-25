<?php
function storeOrderForm($next_ref){
	$CI =& get_instance();
		$CI->make->sForm("store_order/so_save",array('id'=>'so_form'));
			// $CI->make->hidden('form_menu_id',$menu_id);
	    	// $CI->make->H(3,fa('icon-info').'General Details',array('class'=>'page-header'));
			$CI->make->sDivRow();
				// $CI->make->sDivCol(3);
				// 	$CI->make->input('Payment','payment',iSetObj($menu,'menu_barcode'),'Type Payment',array('class'=>'rOkay'));
				// $CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->input('Reference','reference',$next_ref,'Type Reference',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->date('Date','ord_date',date("m/d/Y"));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();

	  //   	$CI->make->sDivRow();
			// 	$CI->make->sDivCol(3);
			// 		$CI->make->input('Current Credit','curr_credit',"",'Type Current Credit',array('class'=>'rOkay'));
			// 	$CI->make->eDivCol();
			// 	$CI->make->sDivCol(3);
			// 		$CI->make->input('Customer Discount','cus_disc',iSetObj($menu,'menu_cat_id'),'Select Sales Person',array('class'=>'rOkay'));
			// $CI->make->eDivRow();

            $CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
           		$CI->make->hidden('item-hid-id',null);
                $CI->make->sRow();
                    // $CI->make->th('DAY');
                    $CI->make->th('Item Code - Name',array('class'=>'text-center','style'=>'width:60px;background-color:#bfced8!important;color:black;'));
                    $CI->make->th('Item Description',array('class'=>'text-center','style'=>'width:60px;background-color:#bfced8!important;color:black;'));
                    $CI->make->th('Qty',array('class'=>'text-center','style'=>'width:60px;background-color:#bfced8!important;color:black;'));
                    // $CI->make->th('Demand',array('class'=>'text-center','style'=>'width:60px;'));
                    $CI->make->th('Unit',array('class'=>'text-center','style'=>'width:60px;background-color:#bfced8!important;color:black;'));
                    $CI->make->th('Unit Price',array('class'=>'text-center','style'=>'width:60px;background-color:#bfced8!important;color:black;'));
                    // $CI->make->th('Price After Tax',array('class'=>'text-center','style'=>'width:53px;'));
                    // $CI->make->th('Discount %',array('class'=>'text-center','style'=>'width:45px;'));
                    // $CI->make->th('Subtotal (₱)',array('class'=>'text-center','style'=>'width:30px;'));
                    $CI->make->th('',array('class'=>'text-center','style'=>'width:20px;background-color:#bfced8!important'));
                    // $CI->make->th('&nbsp;',array('style'=>'width:40px;'));
                $CI->make->eRow();
	            $CI->make->sRow(array('id'=>'row-1'));
	                $CI->make->sTd(array('align'=>'center','colspan'=>'100%',"id"=>"addbtnRow"));
						$CI->make->sDivCol(4,'center',4);
							$CI->make->button(fa('fa-plus').' Add an Item',array('id'=>'add_item','class'=>'btn-block'),'primary');
						$CI->make->eDivCol();
	                $CI->make->eTd(array('align'=>'center','colspan'=>'100%'));
	            $CI->make->eRow();
	            // $CI->make->sRow(array('id'=>'row-2'));
	            //     $CI->make->td("b",array('align'=>'center','colspan'=>'100%'));
	            // $CI->make->eRow();

                $total = 0;
                // echo "</pre>",print_r($menu),"</pre>";
                // if(count($menu) > 0){ 
                // }
            $CI->make->eTable();


			$CI->make->sDivRow();
				// $CI->make->sDivCol(3,"",0,array("style"=>"margin-left:10px"));
				// 	$CI->make->date('Delivery Date','delivery_date');
				// 	$CI->make->input('Deliver To','deliver_to',iSetObj($menu,'menu_barcode'),'Deliver To',array('class'=>'rOkay'));
				// 	$CI->make->textarea('Delivery Address','delivery_address',"",'Type Reference',array('class'=>'rOkay'));
				// $CI->make->eDivCol();
				$CI->make->sDivCol(7);
					// $CI->make->input('Contact Phone Number','phone_num',"",'Type Phone Number',array('class'=>'rOkay'));
					// $CI->make->input('Customer Reference','cust_ref',iSetObj($menu,'menu_barcode'),'Type Payment',array('class'=>'rOkay'));
					$CI->make->textarea('Comments','comments',"",'Comments',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				// $CI->make->sDivCol(3);
				// 	$CI->make->input('Shipping Charge','ship_cost',"",'0.00',array('class'=>'rOkay'));
				// 	// $CI->make->input('Customer Reference','cust_ref',iSetObj($menu,'menu_barcode'),'Type Payment',array('class'=>'rOkay'));
				// 	// $CI->make->textarea('Comments','comments',"",'Type Reference',array('class'=>'rOkay'));
				// $CI->make->eDivCol();
	    	$CI->make->eDivRow();

	    	$CI->make->sDivRow();
				$CI->make->sDivCol(3);
				$CI->make->eDivCol();
				$CI->make->sDivCol(3);
				$CI->make->eDivCol();
			$CI->make->eDivRow();



		$CI->make->eForm();
    	$CI->make->H(3,"",array('class'=>'page-header'));
		$CI->make->sDivRow();
			if(REMOVE_MASTER_BUTTON == FALSE){
				$CI->make->sDivCol(3,'left',3);
					$CI->make->button(fa('fa-save').' Save Details',array('id'=>'save-so','class'=>'btn-block'),'success');
				$CI->make->eDivCol();
			}
			// $CI->make->sDivCol(3,'left');
			// 	$CI->make->button(fa('fa-save').' Save As New',array('id'=>'save-new-menu','class'=>'btn-block'),'info');
			// $CI->make->eDivCol();
	    $CI->make->eDivRow();
	return $CI->make->code();
}
function get_item_form($row=0){
	$CI =& get_instance();
			// echo $row;die();
	            $CI->make->sRow(array('id'=>'row-1'));
	                $CI->make->sTd(array('align'=>'center','colspan'=>''));
							$CI->make->input("","item_name[]",null,null,array("readonly"=>"readonly","class"=>"item_name-".$row));
	                $CI->make->eTd();
	                $CI->make->sTd(array('align'=>'center','colspan'=>''));
							$CI->make->itemsDrop("","item_id[]",null,"Select Item",array("class"=>"getdet_item","ref"=>$row));
	                $CI->make->eTd();
	                $CI->make->sTd(array('align'=>'center','colspan'=>''));
							$CI->make->input("","item_qty[]",null,null,array("style"=>"width:50%;","qty_textfield"=>"qty_textfield"));
	                $CI->make->eTd();
	                $CI->make->sTd(array('align'=>'center','colspan'=>''));
							$CI->make->input("","item_uom[]",null,null,array("readonly"=>"readonly","class"=>"item_uom-".$row));
	                $CI->make->eTd();
	                $CI->make->sTd(array('align'=>'center','colspan'=>''));
							$CI->make->input("","item_price[]",null,null,array("readonly"=>"readonly","class"=>"item_price-".$row));
	                $CI->make->eTd();
	                $CI->make->sTd(array('align'=>'center','colspan'=>''));
						$CI->make->button(fa('fa-check'),array('id'=>'lock_item','class'=>'btn btn-circle blue btn-outline',"style"=>"width:34px;border-radius: 100%!important;padding:6px 4px;","ref"=>$row));
						$CI->make->button(fa('fa-pencil'),array('id'=>'edit_item','class'=>'btn btn-circle blue btn-outline',"style"=>"width:34px;border-radius: 100%!important;padding:6px 4px;display:none","ref"=>$row));
						$CI->make->button(fa('fa-close'),array('id'=>'remove_item','class'=>'btn btn-circle red btn-outline',"style"=>"width:34px;border-radius: 100%!important;padding:6px 4px;","ref"=>$row));
					$CI->make->eTd();
	            $CI->make->eRow();

	return $CI->make->code();
}

?>