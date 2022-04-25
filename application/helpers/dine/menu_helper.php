<?php
function menuListPage($list=array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12,'right');
							 $CI->make->A(fa('fa-plus').' Add New Menu',base_url().'menu/form',array('class'=>'btn btn-primary'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
                	$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array('Menu Code'=>'',
									'Barcode'=>'',
									'Short Description'=>'',
									'Name'=>'',
									'Category'=>'',
									'Schedule'=>'',
									'Price'=>'',
									' '=>array('width'=>'12%','align'=>'right'));
							$rows = array();
							foreach($list as $v){
								$links = "";
								$links .= $CI->make->A(fa('fa-edit fa-2x fa-fw'),base_url().'menu/form/'.$v->menu_id,array("return"=>true));
								$rows[] = array(
											  $v->menu_code,
											  $v->menu_barcode,
											  $v->menu_name,
											  $v->menu_short_desc,
											  $v->category_name,
											  $v->menu_schedule_name,
											  $v->cost,
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
function menuUploadForm(){
	$CI =& get_instance();
	$CI->make->H(3,'Warning! THIS WILL REPLACE ALL THE MENUS.',array('class'=>'label label-warning','style'=>'margin-bottom:50px;font-size:24px;'));
	// $CI->make->sForm('menu/upload_excel_db',array('id'=>"upload-form",'enctype'=>'multipart/form-data'));
	$CI->make->sForm('menu/upload_excel_db',array('id'=>"upload-form",'enctype'=>'multipart/form-data'));
		$CI->make->sDivRow(array('style'=>'margin-top:30px;'));
			$CI->make->sDivCol(6);
				$CI->make->file('menu_excel',array());
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function menuSearchForm($post=array()){
	$CI =& get_instance();
	$CI->make->sForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->input('Menu Name','menu_name',null,null);
				$CI->make->menuCategoriesDrop('Categories','menu_cat_id',null,'- select category -');
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->inactiveDrop('Is Inactive','inactive',null,null,array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function menuCatSearchForm($post=array()){
	$CI =& get_instance();
	$CI->make->sForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->input('Menu Category Name','menu_cat_name',null,null);
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->inactiveDrop('Is Inactive','inactive',null,null,array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function menuFormPage($menu_id=null,$img=null){
	$CI =& get_instance();
	$CI->make->hidden('menu_id',$menu_id);
	$CI->make->sDivRow(array('style'=>'margin-bottom:5px;'));
			$CI->make->sDivCol(12,'right',0,array('style'=>'margin-top:0px;'));
				$CI->make->A(fa('fa-reply').' Go back to list',base_url().'menu',array('class'=>'btn btn-primary','style'=>'margin-top:0px;'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol(3);
			$CI->make->sBox('solid',array('style'=>'margin-bottom:5px;'));
				$CI->make->sBoxBody();
					$src = base_url().'img/noimage.png';
					if($menu_id != null && $img != ""){
						$src = base_url().$img;
					}
					$CI->make->sDiv(array('style'=>'position:relative;width:100%;background-color:#ddd;'));
						$CI->make->img($src,array('style'=>'width:100%;max-height:250px;','id'=>'item-pic'));
						if($menu_id != null){
							$CI->make->sDiv(array('style'=>'position:absolute;bottom:0;left:0;width:100%;height:30px;text-align:right;padding-right:5px;color:#fff'));
								$CI->make->A(fa('fa-camera fa-2x'),'#',array('style'=>'color:#fff;','id'=>'target','title'=>'Upload Picture'));
								$CI->make->sForm("menu/images_db",array('id'=>'pic-form'));
									$CI->make->file('fileUpload',array('style'=>'display:none;'));
									$CI->make->hidden('upload_menu_id',$menu_id);	
								$CI->make->eForm();
							$CI->make->eDiv();
						}
					$CI->make->eDiv();
				$CI->make->eBoxBody();
			$CI->make->eBox();
					$list[fa('icon-info').' General Details'] = array('id'=>'details_link','class'=>'tab_link',
																		   'load'=>'menu/details_load/','href'=>'#details',	
					                                        			   'style'=>'cursor:pointer;padding:10px;text-align:left;');
					if(MASTER_BUTTON_EDIT){
						if($menu_id != null){
							$list[fa('fa-book').' Recipe'] = array('id'=>'recipe_link','class'=>'tab_link',
																				   'load'=>'menu/recipe_load/','href'=>'#details',	
							                                        			   'style'=>'cursor:pointer;padding:10px;text-align:left;');
							$list[fa('fa-asterisk').' Modifiers'] = array('id'=>'modifier_link','class'=>'tab_link',
																				   'load'=>'menu/modifier_load/','href'=>'#details',	
							                                        			   'style'=>'cursor:pointer;padding:10px;text-align:left;');
							$list[fa('fa-money').' Prices'] = array('id'=>'price_link','class'=>'tab_link',
																				   'load'=>'menu/price_load/','href'=>'#details',	
							                                        			   'style'=>'cursor:pointer;padding:10px;text-align:left;');
						}
					}
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
function menuFormPage2($menu_id=null){
	$CI =& get_instance();
	// $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
	// 		$CI->make->sDivCol(12,'right');
	// 			$CI->make->A(fa('fa-reply').' Go back to list',base_url().'menu',array('class'=>'btn btn-primary'));
	// 		$CI->make->eDivCol();
	// 	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sTab();
					$tabs = array(
						"tab-title"=>$CI->make->a(fa('fa-reply')." Back To List",base_url().'menu',array('return'=>true)),
						fa('icon-info')." General Details"=>array('href'=>'#details','class'=>'tab_link','load'=>'menu/details_load/','id'=>'details_link'),
						fa('fa-book')." Recipe"=>array('href'=>'#recipe','class'=>'tab_link load-tab','load'=>'menu/recipe_load/','id'=>'recipe_link'),
						fa('fa-asterisk')." Modifiers"=>array('href'=>'#modifiers','class'=>'tab_link load-tab','load'=>'menu/modifier_load/','id'=>'modifier_link'),
						fa('fa-picture-o')." Image Upload"=>array('href'=>'#image','class'=>'tab_link load-tab','load'=>'menu/upload_image_load/','id'=>'image_link'),
					);
					$CI->make->hidden('menu_id',$menu_id);
					$CI->make->tabHead($tabs,null,array());
					$CI->make->sTabBody(array('style'=>'min-height:202px;'));
						$CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'recipe','class'=>'tab-pane'));
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'modifiers','class'=>'tab-pane'));
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'image','class'=>'tab-pane'));
						$CI->make->eTabPane();
					$CI->make->eTabBody();
				$CI->make->eTab();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}
function menuImagesLoad($menu_id=null,$res=null){
	$CI =& get_instance();
		$CI->make->sForm("menu/images_db",array('id'=>'images_form','enctype'=>'multipart/form-data'));
			$CI->make->hidden('form_menu_id',$menu_id);
			$CI->make->sDivRow();
				$img = base_url().'img/no_image.jpg';
				if(iSetObj($res,'img_path') != ""){					
					$img = base_url().$res->img_path;
				}
				$CI->make->sDivCol(12,'center');
					$CI->make->img($img,array('class'=>'media-object thumbnail','id'=>'target','style'=>'max-height:220px;margin:0 auto;'));
					$CI->make->file('fileUpload',array('style'=>'display:none;'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
		$CI->make->sDiv(array('style'=>'margin-top:10px;'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(12,'center');
					$CI->make->button(fa('fa-save').' Save Image',array('id'=>'save-image'),'primary');
				$CI->make->eDivCol();
		    $CI->make->eDivRow();
	    $CI->make->eDiv();
	return $CI->make->code();
}
function menuDetailsLoad($menu=null,$menu_id=null){
	$CI =& get_instance();
		$CI->make->sForm("menu/details_db",array('id'=>'details_form'));
			$CI->make->hidden('form_menu_id',$menu_id);
	    	$CI->make->H(3,fa('icon-info').'General Details',array('class'=>'page-header'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->input('Code','menu_code',iSetObj($menu,'menu_code'),'Type Code',array('class'=>'rOkay noSpecial','maxlength'=>'15'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->input('Barcode','menu_barcode',iSetObj($menu,'menu_barcode'),'Type Barcode',array('class'=>'rOkay noSpecial'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->input('Short Description','menu_name',iSetObj($menu,'menu_name'),'Type Short Desc',array('class'=>'rOkay noSpecial','maxlength'=>40 ));
				$CI->make->eDivCol();
				// $CI->make->sDivCol(4);
				// 	$CI->make->brandDrop('Brand','brand',iSetObj($menu,'brand'),'Select Brand',array('class'=>'rOkay'));
				// $CI->make->eDivCol();
	    	$CI->make->eDivRow();
	    	$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->textarea('Description','menu_short_desc',iSetObj($menu,'menu_short_desc'),'Type Name',array('class'=>'rOkay noSpecial','maxlength'=>'50'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->menuCategoriesDrop('Category','menu_cat_id',iSetObj($menu,'menu_cat_id'),'Select Category',array('class'=>'rOkay'));
					$CI->make->menuSubCategoriesDrop('Menu Type','menu_sub_cat_id',iSetObj($menu,'menu_sub_cat_id'),'Select Type',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->menuSchedulesDrop('Schedule','menu_sched_id',iSetObj($menu,'menu_sched_id'),'Select Schedule',array());
					$CI->make->brandDbDrop('Brand','brand',iSetObj($menu,'brand'),'Select Brand',array('class'=>'rOkay'));
				$CI->make->eDivCol();
			$CI->make->eDivRow();
			$CI->make->sDivRow();
					$CI->make->sDivCol(4);
						$CI->make->inactiveDrop('Alcohol','alcohol',iSetObj($menu,'alcohol'),null,array('style'=>'width:85px;'));
					$CI->make->eDivCol();
					$CI->make->sDivCol(4);
						if(SHOW_NEW_SUBCATEGORY){
							$CI->make->menuSubCategoriesNewDrop('Sub Category','menu_sub_id',iSetObj($menu,'menu_sub_id'),'Select Sub Category',array('class'=>''));
						}
					$CI->make->eDivCol();
					$CI->make->sDivCol(2);
						$CI->make->inactiveDrop('Inactive','inactive',iSetObj($menu,'inactive'),null,array('style'=>'width:85px;'));
					$CI->make->eDivCol();
					$CI->make->sDivCol(2);
						$CI->make->inactiveDrop('Unavailable','unavailable',iSetObj($menu,'unavailable'),null,array('style'=>'width:85px;'));
					$CI->make->eDivCol();
			$CI->make->eDivRow();
	    	$CI->make->H(3,fa('icon-calculator').' Pricing Details',array('class'=>'page-header'));
	    	$CI->make->sDivRow();
				$CI->make->sDivCol(3);
					$CI->make->input('Cost','costing',iSetObj($menu,'costing'),'Price',array('class'=>'rOkay','style'=>'width:120px;'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(3);
					$CI->make->input('Selling Price','cost',iSetObj($menu,'cost'),'Price',array('class'=>'rOkay','style'=>'width:120px;'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(3);
					$CI->make->inactiveDrop('Is Tax Exempt','no_tax',(iSetObj($menu,'no_tax')),null,array('style'=>'width:85px;'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(3);
					$CI->make->inactiveDrop('Free','free',(iSetObj($menu,'free')),null,array('style'=>'width:85px;'));
				$CI->make->eDivCol();
			$CI->make->eDivRow();
			$CI->make->H(3,'Inventory',array('class'=>'page-header'));
	    	$CI->make->sDivRow();
				$CI->make->sDivCol(3);
					$CI->make->input('Reorder Quantity','reorder_qty',iSetObj($menu,'reorder_qty'),'Qty',array('class'=>'rOkay','style'=>'width:120px;'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(3);
					$CI->make->input('Quantity','qty',iSetObj($menu,'menu_qty'),'Qty',array('class'=>'rOkay','style'=>'width:120px;'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(3);
					$CI->make->uomDropId('UOM','uom_id',(iSetObj($menu,'uom_id')),null,array('style'=>'width:120px;'));
				$CI->make->eDivCol();
			$CI->make->eDivRow();
			if(MALL_ENABLED){
            	if(MALL == 'miaa'){
					$CI->make->H(3,fa('fa fa-info-circle').' MIAA Details',array('class'=>'page-header'));
			    	$CI->make->sDivRow();
						$CI->make->sDivCol(6);
							$CI->make->miaaCategoriesDrop('MIAA Category','miaa_cat',(iSetObj($menu,'miaa_cat')),'Select Category',array('style'=>''));
						$CI->make->eDivCol();
					$CI->make->eDivRow();
                }
            }
		$CI->make->eForm();
    	$CI->make->H(3,"",array('class'=>'page-header'));
		$CI->make->sDivRow();
			if(REMOVE_MASTER_BUTTON == FALSE){
				$CI->make->sDivCol(3,'left',3);
					$CI->make->button(fa('fa-save').' Submit',array('id'=>'save-menu','class'=>'btn-block'),'success');
				$CI->make->eDivCol();
			}
			// $CI->make->sDivCol(3,'left');
			// 	$CI->make->button(fa('fa-save').' Save As New',array('id'=>'save-new-menu','class'=>'btn-block'),'info');
			// $CI->make->eDivCol();
	    $CI->make->eDivRow();
	return $CI->make->code();
}
function menuRecipeLoad($menu_id,$recipe=null,$det=array()){
	$CI =& get_instance();
	$CI->make->H(3,fa('fa fa-book').' Recipe',array('class'=>'page-header'));
	$CI->make->sDivRow();
		$CI->make->sDivCol(4);
			$CI->make->sForm('menu/recipe_details_db',array('id'=>'recipe-details-form'));
				$CI->make->hidden('menu-id-hid',$menu_id);
				$CI->make->sDivRow();
					$CI->make->sDivCol();
						// $CI->make->input('Search Item','item-search',null,'Search for item',array('search-url'=>'menu/recipe_search_item'),'',fa('fa-search'));
						// $CI->make->input('Unit of Measurement','item-uom',null,'',array('readOnly'=>'readOnly'));
						$CI->make->itemAjaxDrop('Item','item-search',null,array());
						$uomTxt = $CI->make->span('&nbsp;&nbsp;',array('return'=>true,'id'=>'uom-txt'));
						$CI->make->input('Cost','item-cost',null,null,array('readOnly'=>'readOnly'));
						$CI->make->input('Quantity','qty',null,null,array(),'',$uomTxt);
						$CI->make->hidden('item-id-hid',null,array('class'=>'rOkay','ro-msg'=>'Please select an item'));
						$CI->make->hidden('item-uom-hid',0);
						$CI->make->button(fa('fa-plus').' Add item to recipe',array('id'=>'add-btn'),'primary btn-block');
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eForm();
		$CI->make->eDivCol();
    	$CI->make->sDivCol(8);
    		$CI->make->sDiv(array('class'=>'table-responsive','style'=>'margin-top:23px'));
    			$CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
    					$CI->make->sRow();
	    					$CI->make->th('Item');
	    					$CI->make->th('UOM');
	    					$CI->make->th('Unit Price');
	    					$CI->make->th('Quantity');
	    					$CI->make->th('Line Total');
	    					$CI->make->th();
	    				$CI->make->eRow();
    					$total = 0;
    					foreach ($det as $val) {
    						$CI->make->sRow(array('id'=>'row-'.$val->recipe_id));
    							$CI->make->td($val->item_name);
    							$CI->make->td($val->uom);
    							$CI->make->td($val->item_cost);
    							$CI->make->td($val->qty);
    							$CI->make->td(num($val->item_cost * $val->qty));
    							$a = $CI->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$val->recipe_id,'ref'=>$val->recipe_id,'class'=>'del-item','return'=>true));
            					$CI->make->td($a);
    						$CI->make->eRow();
    						$total += $val->item_cost * $val->qty;
    					}
    					$CI->make->sRow();
	    					$CI->make->td('');
	    					$CI->make->td('');
	    					$CI->make->td('');
	    					$CI->make->td('');
	    					$CI->make->td('Total');
	    					$CI->make->td(num($total),array('id'=>'total'));
	    				$CI->make->eRow();
    			$CI->make->eTable();
    		$CI->make->eDiv();
    	$CI->make->eDivCol();
	$CI->make->eDivRow();
	// $CI->make->sDivRow(array("style"=>"margin-top:20px;"));
	// 	$CI->make->sDivCol(4,'left','8');
	// 		$pop = $CI->make->A(fa('fa-save fa-fw '),'#',array('id'=>'override-price','return'=>true));
	// 		$sell_price = iSetObj((!empty($det[0]) ? $det[0] : null),'menu_cost');
	// 		$CI->make->input('Selling Price','total',num($sell_price),null,array(),null,$pop);
	// 	$CI->make->eDivCol();
	// $CI->make->eDivRow();

	return $CI->make->code();
}
function menuModifierLoad($menu_id,$det = null){
	$CI =& get_instance();
	$CI->make->H(3,fa('fa fa-asterisk').' Modifiers',array('class'=>'page-header'));
	$CI->make->sDivRow();
		$CI->make->sDivCol(8);
			$CI->make->sForm('menu/menu_modifier_db',array('id'=>'menu-modifier-form'));
				$CI->make->hidden('menu-id-hid',$menu_id);
				$CI->make->sDivRow();
					$CI->make->sDivCol(8);
						// $CI->make->modifiersAjaxDrop('Search Item','item-search',null,array());
						// $CI->make->input('Search Item','item-search',null,'Search for item',array('search-url'=>'menu/modifier_search_item'),'',fa('fa-plus'));
						// $CI->make->button(fa('fa-plus').' Add Modifier Group',array('id'=>'add-btn'),'primary btn-block');
						$CI->make->modifiersGroupAjaxDrop('Search Group Modifier','item-search');
						$CI->make->hidden('mod-group-id-hid',null,array('class'=>'rOkay','ro-msg'=>'Please select a modifier'));
					$CI->make->eDivCol();
					$CI->make->sDivCol(4);
		                $CI->make->A(fa('fa-lg fa-plus')." ADD",'#',array('class'=>'btn btn-primary','id'=>'add-grp-modifier','style'=>'margin-top:23px;'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eForm();
		$CI->make->eDivCol();
	$CI->make->eDivRow();	
	$CI->make->sDivRow();	
		$CI->make->sDivCol(7);
			$CI->make->sDiv(array('class'=>'table-responsive','style'=>'margin-top:23px'));
    			$CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
    					$total = 0;
    					foreach ($det as $val) {
    						$CI->make->sRow(array('id'=>'row-'.$val->id));
    							$CI->make->td(fa('fa-asterisk')." ".$val->mod_group_name);
    							$a = $CI->make->A(fa('fa-lg fa-times fa-fw'),'#',array('id'=>'del-'.$val->id,'ref'=>$val->id,'class'=>'del-item','return'=>true));
            					$CI->make->td($a,array('style'=>'text-align:right'));
    						$CI->make->eRow();
    					}
    			$CI->make->eTable();
    		$CI->make->eDiv();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}

function menuRecipeForm($menu_id=null,$recipe=null,$det=array())
{
	$CI =& get_instance();

	$CI->make->sDivRow(array('style'=>'margin-bottom:10px'));
		$CI->make->sDivCol(12,'right');
			$CI->make->A(" ".fa('fa-reply')." Go back",base_url().'menu/form/'.$menu_id,array('class'=>'btn btn-default'));
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxHead();
					$CI->make->boxTitle(4,fa('fa-archive').' Recipe Details');
				$CI->make->eBoxHead();
				$CI->make->sBoxBody();
					$CI->make->sForm('menu/recipe_details_db',array('id'=>'recipe-details-form'));
						$CI->make->hidden('recipe-id-hid',iSetObj($recipe,'recipe_id'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(4);
								$CI->make->input(null,'item-search',null,'Search for item',array('search-url'=>'menu/search_items','add-data'=>'menu_id='+$menu_id));
								$CI->make->hidden('item-id-hid',null,array('class'=>'rOkay','ro-msg'=>'Please select an item'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->H(4,'',array('id'=>'item-uom'));
								$CI->make->hidden('item-uom-hid',0);
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->H(4,'0.00',array('id'=>'item-price'));
								$CI->make->hidden('item-price-hid',0);
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->input(null,'qty',null,'Add quantity',array('class'=>'rOkay','ro-msg'=>'Please add quantity'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->button(fa('fa-plus').' Add item to recipe',array('id'=>'add-btn'),'primary');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
    $CI->make->eDivRow();
    $CI->make->sDivRow();
    	$CI->make->sDivCol();
    		$CI->make->sDiv(array('class'=>'table-responsive'));
    			$CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
    				$CI->make->sTablehead();
    					$CI->make->sRow();
	    					$CI->make->th('Item');
	    					$CI->make->th('UOM');
	    					$CI->make->th('Unit Price');
	    					$CI->make->th('Quantity');
	    					$CI->make->th('Line Total');
	    				$CI->make->eRow();
    				$CI->make->eTableHead();
    				$CI->make->sTableBody();
    					$total = 0;
    					foreach ($det as $val) {
    						$CI->make->sRow();
    							$CI->make->td();
    						$CI->make->eRow();
    					}
    				$CI->make->eTableBody();
    			$CI->make->eTable();
    		$CI->make->eDiv();
    	$CI->make->eDivCol();
    $CI->make->eDivRow();


	return $CI->make->code();
}

function makeMenuCategoriesForm($cat=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/menu/categories_form_db",array('id'=>'categories_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('menu_cat_id',iSetObj($cat,'menu_cat_id'));
				$CI->make->input('Name','menu_cat_name',iSetObj($cat,'menu_cat_name'),'Type Category Name',array('class'=>'rOkay noSpecial'));
				$CI->make->brandDbDrop('Brand','brand',iSetObj($cat,'brand'),'Select Brand',array('class'=>'rOkay'));
				$CI->make->menuSchedulesDrop('Default Schedule','menu_sched_id',iSetObj($cat,'menu_sched_id'),'Select Schedule',array('class'=>''));
				$arr = '0';
				if($cat){
					$arr = $cat->arrangement;
				}
				$CI->make->number('Arrangement No.','arrangement',$arr,'Type Arrangement No.',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Unli Category','unli',iSetObj($cat,'unli'),'',array('style'=>'width: 85px;'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($cat,'inactive'),'',array('style'=>'width: 85px;'));
				$CI->make->button(fa('fa-save').' Submit',array('id'=>'save-menu-cat','class'=>'btn-block'),'success');
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
function makeMenuCategoriesFormPop($cat=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/menu/categories_form_db",array('id'=>'categories_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('menu_cat_id',iSetObj($cat,'menu_cat_id'));
				$CI->make->input('Name','menu_cat_name',iSetObj($cat,'menu_cat_name'),'Type Category Name',array('class'=>'rOkay noSpecial'));
				$CI->make->brandDbDrop('Brand','brand',iSetObj($cat,'brand'),'Select Brand',array('class'=>'rOkay'));
				$CI->make->menuSchedulesDrop('Default Schedule','menu_sched_id',iSetObj($cat,'menu_sched_id'),'Select Schedule',array('class'=>''));
				$arr = '0';
				if($cat){
					$arr = $cat->arrangement;
				}
				$CI->make->number('Arrangement No.','arrangement',$arr,'Type Arrangement No.',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Unli Category','unli',iSetObj($cat,'unli'),'',array('style'=>'width: 85px;'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($cat,'inactive'),'',array('style'=>'width: 85px;'));
				// $CI->make->button(fa('fa-save').' Submit',array('id'=>'save-menu-cat','class'=>'btn-block'),'success');
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
function makeMenuSubCategoriesForm($cat=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/menu/subcategories_form_db",array('id'=>'subcategories_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('menu_sub_cat_id',iSetObj($cat,'menu_sub_cat_id'));
				$CI->make->input('Name','menu_sub_cat_name',iSetObj($cat,'menu_sub_cat_name'),'Type Sub Category Name',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($cat,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function makeMenuSchedulesForm($cat=array(),$dets=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/menu/menu_sched_db",array('id'=>'schedules_form'));
		$CI->make->sDivRow(array('style'=>''));
			$CI->make->sDivCol(6);
				$CI->make->hidden('menu_sched_id',iSetObj($cat,'menu_sched_id'));
				$CI->make->input('Description','desc',iSetObj($cat,'desc'),'Type Description',array('class'=>'rOkay'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($cat,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
    $CI->make->eForm();
	$CI->make->sForm("dine/menu/menu_sched_details_db",array('id'=>'schedules_details_form'));
    	$CI->make->sDivRow();
            $CI->make->sDivCol(3);
            	// $CI->make->hidden('menu_sched_id',iSetObj($cat,'menu_sched_id'));
            	$CI->make->hidden('sched_id',iSetObj($cat,'menu_sched_id'));
                $CI->make->time('Time On','time_on','07:00 AM','Time On');
            $CI->make->eDivCol();
            $CI->make->sDivCol(3);
                $CI->make->time('Time Off','time_off','10:00 PM','Time Off');
            $CI->make->eDivCol();
            $CI->make->sDivCol(3);
                $CI->make->dayDrop('Day','day',null,'',array('style'=>'width: inherit;'));
            $CI->make->eDivCol();
            $CI->make->sDivCol(3);
            if(REMOVE_MASTER_BUTTON == FALSE)
                $CI->make->button(fa('fa-plus').' Add Schedule',array('id'=>'add-schedule','style'=>'margin-top:23px;'),'primary');
            $CI->make->eDivCol();
        $CI->make->eDivRow();
        $CI->make->sDivRow();
            $CI->make->sDivCol();
                $CI->make->sDiv(array('class'=>'table-responsive'));
                    $CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
                        $CI->make->sRow();
                            // $CI->make->th('DAY');
                            $CI->make->th('DAY',array('style'=>'width:60px;'));
                            $CI->make->th('TIME ON',array('style'=>'width:60px;'));
                            $CI->make->th('TIME OFF',array('style'=>'width:60px;'));
                            if(REMOVE_MASTER_BUTTON == FALSE)
                            $CI->make->th('&nbsp;',array('style'=>'width:40px;'));
                        $CI->make->eRow();
                        $total = 0;
                        // echo var_dump($dets);
                        if(count($dets) > 0){
                            foreach ($dets as $res) {
                                $CI->make->sRow(array('id'=>'row-'.$res->id));
                                    $CI->make->td(date('l',strtotime($res->day)));
                                    $CI->make->td(date('h:i A',strtotime($res->time_on)));
                                    $CI->make->td(date('h:i A',strtotime($res->time_off)));
                                    $a = $CI->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-sched-'.$res->id,'class'=>'del-sched','ref'=>$res->id,'return'=>true));
                                    if(REMOVE_MASTER_BUTTON == FALSE)
                                    $CI->make->td($a);
                                $CI->make->eRow();
                            //     $total += $price * $res->qty;
                            }
                        }
                    $CI->make->eTable();
                $CI->make->eDiv();
            $CI->make->eDivCol();
        $CI->make->eDivRow();
        $CI->make->sDivRow();
		    $CI->make->sDivCol(4,'left',3);
		        $CI->make->button(fa('fa-save')." Save Details",array('id'=>'save-btn','class'=>''),'success');
		    $CI->make->eDivCol();
		    $CI->make->sDivCol(2);
		        $CI->make->A(fa('fa-reply')." Go Back",base_url().'menu/schedules',array('class'=>'btn btn-primary'));
		    $CI->make->eDivCol();
		$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}

//for new subcategory
function makeMenuSubCategoriesNewForm($cat=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/menu/subcategories_form_new_db",array('id'=>'subcategories_form_new'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('menu_sub_id',iSetObj($cat,'menu_sub_id'));
				$CI->make->input('Name','menu_sub_name',iSetObj($cat,'menu_sub_name'),'Type Sub Category Name',array('class'=>'rOkay'));
				$CI->make->menuCategoriesDrop('Under Category','category_id',iSetObj($cat,'category_id'),'',array());
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($cat,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}

//menu prices
function menuPricesLoad($menu_id,$det = null){
	$CI =& get_instance();
	$CI->make->H(3,fa('fa fa-asterisk').' Prices',array('class'=>'page-header'));
	$CI->make->sDivRow();
		$CI->make->sDivCol(8);
			$CI->make->sForm('menu/menu_prices_db',array('id'=>'menu-price-form'));
				$CI->make->hidden('menu-id-hid',$menu_id);
				$CI->make->sDivRow();
					$CI->make->sDivCol(4);
						// $CI->make->modifiersAjaxDrop('Search Item','item-search',null,array());
						// $CI->make->button(fa('fa-plus').' Add Modifier Group',array('id'=>'add-btn'),'primary btn-block');
						$CI->make->transTypeDrop('Transaction Type','trans_type',null,'Select a Type');
						// $CI->make->hidden('mod-group-id-hid',null,array('class'=>'rOkay','ro-msg'=>'Please select a modifier'));
					$CI->make->eDivCol();
					$CI->make->sDivCol(4);
						$CI->make->input('Price','price',null,'Price',array());
					$CI->make->eDivCol();
					$CI->make->sDivCol(4);
		                $CI->make->A(fa('fa-lg fa-plus')." ADD",'#',array('class'=>'btn btn-primary','id'=>'add-price','style'=>'margin-top:23px;'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eForm();
		$CI->make->eDivCol();
	$CI->make->eDivRow();	
	$CI->make->sDivRow();	
		$CI->make->sDivCol(7);
			$CI->make->sDiv(array('class'=>'table-responsive','style'=>'margin-top:23px'));
    			$CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
    					$total = 0;

    					$CI->make->sRow();
							// $CI->make->td(fa('fa-asterisk')." ".$val->mod_group_name);
							// $a = $CI->make->A(fa('fa-lg fa-times fa-fw'),'#',array('id'=>'del-'.$val->id,'ref'=>$val->id,'class'=>'del-item','return'=>true));
       //  					$CI->make->td($a,array('style'=>'text-align:right'));
        					$CI->make->td("Transaction Type",array('style'=>'text-align:left'));
        					$CI->make->td("Price",array('style'=>'text-align:right'));
        					$CI->make->td("",array('style'=>'text-align:right'));
						$CI->make->eRow();


    					foreach ($det as $val) {
    						$CI->make->sRow(array('id'=>'row-'.$val->id));
    							$CI->make->td($val->trans_type);
    							$CI->make->td(num($val->price),array('style'=>'text-align:right'));
    							$a = $CI->make->A(fa('fa-lg fa-times fa-fw'),'#',array('id'=>'del-'.$val->id,'ref'=>$val->id,'class'=>'del-item','return'=>true));
            					$CI->make->td($a,array('style'=>'text-align:right'));
    						$CI->make->eRow();
    					}
    			$CI->make->eTable();
    		$CI->make->eDiv();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}

function quickEditPage($type=null,$time=null,$loaded=null,$order=array(),$typeCN=array(),$local_tax=0,$kitchen_printer=null,$app_type=null){
	$CI =& get_instance();
		$user = $CI->session->userdata('user');
		$CI->make->sDiv(array('id'=>'counter'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(12,'left',0,array('id'=>'mods_mandatory_div'));
			 	 	// $CI->make->hidden('mods-mandatory',"0",array("class"=>"mods-mandatory"));
			 	 	// $CI->make->hidden('mod-group-name',null,array("class"=>"mod-group-name"));
		 	 	$CI->make->eDivCol();
				$CI->make->hidden('ttype',$app_type);
				if(BUY2TAKE1){
					$time_now = date('h:i A',strtotime($time));
					if(strtotime(BUY2TAKE1_STARTTIME) <= strtotime($time_now) &&  strtotime(BUY2TAKE1_ENDTIME) >= strtotime($time_now)){
						$CI->make->hidden('buy2take1',1);
					}
				}
				
				#CATEGORIES
				$CI->make->sDivCol(2,'left',0,array('class'=>'media-qme-col-3'));
					$CI->make->button(fa('fa-chevron-circle-up fa-2x fa-fw'),array('id'=>'menu-cat-scroll-up','class'=>'btn-block counter-btn double','style'=>'width:130px;height:60px'));
					$CI->make->sDiv(array('class'=>'menu-cat-container',"style"=>"105px;"));
					$CI->make->eDiv();
					$CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-cat-scroll-down','class'=>'btn-block counter-btn double','style'=>'width:130px;height:60px'));
				$CI->make->eDivCol();
				#ITEMS
				$CI->make->sDivCol(10,'left',0,array('class'=>'media-qme-col-9'));
					// $CI->make->button(fa('fa-chevron-circle-up fa-2x fa-fw'),array('id'=>'menu-item-scroll-up','class'=>'btn-block counter-btn double'));
							// $CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-item-scroll-down','class'=>'btn-block counter-btn double'));
					$CI->make->sDiv(array('class'=>'counter-right media-counter-padding','style'=>''));
						#MENU
						$CI->make->sDiv(array('class'=>'menus-div loads-div media-menu-div','style'=>'display:none'));
							$CI->make->sDiv(array('class'=>'items-search','style'=>'background-color:#fff;height:55px;overflow:hidden;'));
								$CI->make->input(null,'search-menu',null,null,array(),fa('fa fa-search')." Menu");
							$CI->make->eDiv();
							// $CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>''));
							$CI->make->sDiv(array('class'=>'items-lists media-menu-itemlists','style'=>'overflow:hidden;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
												
						#zero
						$CI->make->sDiv(array('class'=>'zero-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->H(3,'ZERO-RATED<span id="rate-txt"></span>',array('class'=>'receipt text-center','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'zero-lists'));
								 $CI->make->sForm("",array('id'=>'zero-form'));
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
									 	 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'zero-cust-name',null,'Customer Name',array('class'=>'rOkay','ro-msg'=>'Add Customer Name for Discount'),fa('fa-user'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'zero-cust-code',null,'Card Number',array('class'=>'','ro-msg'=>'Add Customer Code for Discount'),fa('fa-credit-card'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
										 	$CI->make->button(' PROCESS ZERO RATED ',array('id'=>'prcss-zero','class'=>'btn-block counter-btn-green'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow();
										 $CI->make->sDivCol(12);
										 	$CI->make->sDiv(array('class'=>'zero-persons-list-div listings','style'=>'height:280px;overflow:auto;'));
											$CI->make->eDiv();
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
								 $CI->make->eForm();
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();
					// $CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-item-scroll-down','class'=>'btn-block counter-btn double'));
				$CI->make->eDivCol();
			$CI->make->eDivRow();

			$CI->make->sDiv();
			$CI->make->eDiv();

		$CI->make->eDiv();
	return $CI->make->code();
}

function quickEditForm($data){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'counter'));
			$CI->make->sDivRow();
				
				$CI->make->sForm("",array('id'=>'menu-form'));
				#ITEMS
				$CI->make->sDivCol(12,'left',0);
					$CI->make->sDivCol(6,'left');
						$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
							 	$CI->make->hidden('form_menu_id',$data->menu_id);
							 	 $CI->make->radio('','inactive',1,array('style'=>'margin-left:18px','class'=>'radio-btn'),$data->inactive == 1 ? true : false);
							 	 $CI->make->span('Inactive',array('style'=>'margin-left:2px'));							 	 
						$CI->make->eDivRow();

						$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
							 	 $CI->make->radio('','unavailable',1,array('style'=>'margin-left:18px','class'=>'radio-btn'),$data->unavailable == 1 ? true : false);
							 	 $CI->make->span('Not Available',array('style'=>'margin-left:2px'));							 	 
						$CI->make->eDivRow();
					$CI->make->eDivCol();

					$CI->make->sDivCol(6,'left');
						$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
							 	 $CI->make->radio('','set-qty',1,array('style'=>'margin-left:18px','class'=>'radio-btn'),false);
							 	 $CI->make->span('Set QTY',array('style'=>'margin-left:2px'));							 	 
						$CI->make->eDivRow();

						$CI->make->sDivRow(array('id'=>'div-qty','style'=>'margin-bottom:10px;','class'=>'hidden'));
							 $CI->make->number(null,'qty',$data->menu_qty,'Quantity',array('class'=>'','ro-msg'=>'Set Quantity'));							 	 
						$CI->make->eDivRow();
					$CI->make->eDivCol();
				$CI->make->eDivCol();

				$CI->make->sDivCol(6,'right',0);
			 	 $CI->make->eDivCol();

				 $CI->make->sDivCol(6,'right',0);
				 	$CI->make->button(' Submit ',array('id'=>'prcss-menu-edit','class'=>'btn-block counter-btn-green double btn-block counter-btn-green double btn btn-default ','style'=>''));

				 	// $CI->make->button(' Submit ',array('id'=>'prcss-menu-edit','class'=>'btn-block counter-btn-green','style'=>'background-color:#91bd09;color:#fff'));
			 	 $CI->make->eDivCol();

			 	$CI->make->eForm();
					
			$CI->make->eDivRow();


		$CI->make->eDiv();

		// // $CI->make->button(fa('fa-chevron-circle-up fa-2x fa-fw'),array('id'=>'menu-item-scroll-up','class'=>'btn-block counter-btn double'));
		// 					// $CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-item-scroll-down','class'=>'btn-block counter-btn double'));
		// 			$CI->make->sDiv(array('class'=>'counter-right','style'=>'height:300px;'));
																	
		// 				#zero
		// 				$CI->make->sDiv(array('class'=>'','style'=>''));
		// 					// $CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
		// 					// $CI->make->H(3,'ZERO-RATED<span id="rate-txt"></span>',array('class'=>'receipt text-center','style'=>'margin-bottom:10px'));
		// 					$CI->make->sDiv(array('class'=>'menu-form'));
		// 						 $CI->make->sForm("",array('id'=>'menu-form'));
		// 						 	$CI->make->sDivCol(2,'left',0);
		// 						 		$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
		// 								 $chck=true;
		// 								 	 $CI->make->checkbox('','inactive',1,array('style'=>'margin-left:18px'),$data->inactive == 1 ? true : false);
		// 								 	 $CI->make->span('Inactive',array('style'=>'margin-left:2px'));
		// 								 	 $CI->make->eDivCol();
		// 								 $CI->make->eDivRow();

		// 								 // $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
		// 								 // $chck=true;
		// 								 // 	 $CI->make->checkbox('','inactive',1,array('style'=>'margin-left:18px'),$data->inactive == 1 ? true : false);
		// 								 // 	 $CI->make->span('Inactive',array('style'=>'margin-left:2px'));
		// 								 // 	 $CI->make->eDivCol();
		// 								 // $CI->make->eDivRow();
		// 						 	$CI->make->eDivCol();

		// 						 	$CI->make->sDivCol(2,'left',0);
		// 						 		$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
		// 								 $chck=true;
		// 								 	 $CI->make->checkbox('','inactive',1,array('style'=>'margin-left:18px'),$data->inactive == 1 ? true : false);
		// 								 	 $CI->make->span('Inactive',array('style'=>'margin-left:2px'));
		// 								 	 $CI->make->eDivCol();
		// 								 $CI->make->eDivRow();

		// 								 // $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
		// 								 // $chck=true;
		// 								 // 	 $CI->make->checkbox('','inactive',1,array('style'=>'margin-left:18px'),$data->inactive == 1 ? true : false);
		// 								 // 	 $CI->make->span('Inactive',array('style'=>'margin-left:2px'));
		// 								 // 	 $CI->make->eDivCol();
		// 								 // $CI->make->eDivRow();
		// 						 	$CI->make->eDivCol();

									 
		// 							 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
		// 								 $CI->make->sDivCol(12);
		// 							 	 	$CI->make->input(null,'zero-cust-code',null,'Card Number',array('class'=>'','ro-msg'=>'Add Customer Code for Discount'),fa('fa-credit-card'));
		// 							 	 $CI->make->eDivCol();
		// 							 $CI->make->eDivRow();
		// 							 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
		// 								 $CI->make->sDivCol(12);
		// 								 	$CI->make->button(' PROCESS ZERO RATED ',array('id'=>'prcss-zero','class'=>'btn-block counter-btn-green'));
		// 							 	 $CI->make->eDivCol();
		// 							 $CI->make->eDivRow();
		// 							 $CI->make->sDivRow();
		// 								 $CI->make->sDivCol(12);
		// 								 	$CI->make->sDiv(array('class'=>'zero-persons-list-div listings','style'=>'height:280px;overflow:auto;'));
		// 									$CI->make->eDiv();
		// 							 	 $CI->make->eDivCol();
		// 							 $CI->make->eDivRow();
		// 						 $CI->make->eForm();
		// 					$CI->make->eDiv();
		// 				$CI->make->eDiv();
		// 			$CI->make->eDiv();
		// 		$CI->make->eDivCol();

	return $CI->make->code();
}

function menuUpdateUploadForm(){
	$CI =& get_instance();
	$CI->make->H(3,'Warning! THIS WILL ADD OR UPDATE MENUS.',array('class'=>'label label-warning','style'=>'margin-bottom:50px;font-size:24px;'));
	// $CI->make->sForm('menu/upload_excel_db',array('id'=>"upload-form",'enctype'=>'multipart/form-data'));
	$CI->make->sForm('menu/update_upload_excel_db',array('id'=>"update-upload-form",'enctype'=>'multipart/form-data'));
		$CI->make->sDivRow(array('style'=>'margin-top:30px;'));
			$CI->make->sDivCol(6);
				$CI->make->file('menu_excel',array());
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
?>