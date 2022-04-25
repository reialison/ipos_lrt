<?php
function freePromoForm($obj=array(),$menus=array(),$mms=array(),$fms=array(),$fmq=array(),$mqty=array(),$fmp=array()){
	$CI =& get_instance();
	// print_r($obj);exit;
		$CI->make->sForm("promo/free_menu_db",array('id'=>'main-form'));
		$CI->make->hidden('pf_id',iSetObj($obj,'pf_id'));
			$CI->make->sDiv(array('style'=>'margin:5px;'));
			$CI->make->sDivRow(array('style'=>'margin-top:5px;padding:0px;margin-bottom:5px;'));
				$CI->make->sDivCol(12,'right');
					$CI->make->button(fa('fa-save').' Submit',array('id'=>'save-btn','style'=>'margin-right:5px;'),'success');
					$CI->make->button(fa('fa-reply').' Back',array('id'=>'back-btn'),'primary');
				$CI->make->eDivCol();
			$CI->make->eDivRow();
			$CI->make->sDivRow();
				$CI->make->sDivCol(3);
					$CI->make->sBox('primary');						
							$CI->make->sBoxBody();
								$CI->make->H(4,fa('fa-info-circle').' General Details');
								$CI->make->input('Promo Name','name',iSetObj($obj,'name'),'',array('class'=>'rOkay'));
								$CI->make->textarea('Description','description',iSetObj($obj,'description'),'',array('class'=>'rOkay','style'=>'height:60px;'));
								$CI->make->menuSchedulesDrop('Schedule','sched_id',iSetObj($obj,'sched_id'),'Select Schedule',array('class'=>'rOkay'));
								$CI->make->sDivRow();
								$CI->make->sDivCol(9);
											$CI->make->radio('','promo_option',1,array('style'=>'margin-left:0px','class'=>'radio-btn promo-option'),iSetObj($obj,'promo_option') == 1 ? true : false);
											 $CI->make->span(' OPTION 1',array('class'=>'text'));
								$CI->make->eDivCol();

								$display=iSetObj($obj,'promo_option') == 1 ? 'block' : 'none';
								$CI->make->sDivCol(12,"",0,array('class'=>'option1','style'=>'display:'.$display ));
								$CI->make->decimal('Total Amount is Greater or Equal to','amount',iSetObj($obj,'amount'),'',2,array('class'=>'','style'=>'text-align:right;'));
								$CI->make->menuCategoriesDrop('Category','menu_category_id',iSetObj($obj,'menu_category_id'),'Select Category',array('class'=>''));
								$CI->make->eDivCol();
								$CI->make->eDivRow();
								
								$display=iSetObj($obj,'promo_option') == 2 ? 'block' : 'none';
								$CI->make->sDivRow();
									$CI->make->sDivCol(9);
											$CI->make->radio('','promo_option',2,array('style'=>'margin-left:0px','class'=>'radio-btn promo-option'),iSetObj($obj,'promo_option') == 2 ? true : false);
											$CI->make->span(' OPTION 2',array('class'=>'text'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(9,'',0,array('class'=>'option2','style'=>'display:'.$display));
										$CI->make->menuAjaxDrop('Must Have Menu(s)','must-menu');
									$CI->make->eDivCol();
									$CI->make->sDivCol(2,'',0,array('class'=>'option2','style'=>'display:'.$display));
											$display = '';
											if(count($mms) == 1){
												// $display = 'display:none;';
											}
										$CI->make->A(fa('fa-lg fa-plus'),'#',array('class'=>'btn btn-primary','id'=>'add-must-menu','style'=>'margin-top:23px;'.$display));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->sUl(array('class'=>'vertical-list option2','id'=>'must-menu-list','style'=>'display:'.$display));
									foreach ($mms as $i=>$mm) {
										if(isset($menus[$mm])){
											$menu = $menus[$mm];
											$li = $CI->make->li(
								                $CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
								                $CI->make->span($menu['name'],array('class'=>'text','return'=>true))." ".
								                $CI->make->hidden('must_menus[]',$menu['id'],array('return'=>true))." ".
								                
								                $CI->make->A(fa('fa-lg fa-times'),'#',array('return'=>true,'class'=>'del','ref'=>$menu['id'])).
								                $CI->make->decimal('','must_qty[]',$mqty[$i],'',2,array('class'=>'','style'=>'text-align:right;','return'=>true)),
								                array('id'=>'must-menu-'.$menu['id'])
								             );
										}
									}
								$CI->make->eUl();

								$display=iSetObj($obj,'promo_option') == 3 ? 'block' : 'none';
								$CI->make->sDivRow(array('class'=>'hidden'));
									$CI->make->sDivCol(9);
											$CI->make->radio('','promo_option',3,array('style'=>'margin-left:0px','class'=>'radio-btn promo-option'),iSetObj($obj,'promo_option') == 3 ? true : false);
											$CI->make->span(' OPTION 3',array('class'=>'text'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(9,'',0,array('class'=>'option3','style'=>'display:'.$display));
										// $CI->make->menuAjaxDrop('Must Have Menu(s)','must-menu');
										$CI->make->decimal('Total Amount is Greater or Equal to','amount2',iSetObj($obj,'amount'),'',2,array('class'=>'','style'=>'text-align:right;'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(2,'',0,array('class'=>'option3','style'=>'display:'.$display));
											$display = '';
											if(count($mms) == 1){
												// $display = 'display:none;';
											}
										// $CI->make->A(fa('fa-lg fa-plus'),'#',array('class'=>'btn btn-primary','id'=>'add-must-menu','style'=>'margin-top:23px;'.$display));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eBoxBody();						
					$CI->make->eBox();
				$CI->make->eDivCol();
				$CI->make->sDivCol(9);
					$CI->make->sBox('primary',array('id'=>'menu-promo-div'));
						$CI->make->sBoxBody();
							$CI->make->H(4,fa('fa-archive').' Free Menus');
							$CI->make->sDivRow();
								$CI->make->sDivCol(9);
									$CI->make->menuAjaxDrop('','free-menu');
								$CI->make->eDivCol();
								$CI->make->sDivCol(2);
									$display = '';
									if(count($mms) == 1){
										$display = 'display:none;';
									}
									$CI->make->A(fa('fa-lg fa-plus'),'#',array('class'=>'btn btn-primary','id'=>'add-free-menu','style'=>$display));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow(array('id'=>'free-menus-div','style'=>'height:450px;background-color:#f1f1f1;margin:0px;padding:5px;overflow:auto;'));
								foreach ($fms as $fm) {
									if(isset($menus[$fm])){
										$menu = $menus[$fm];
										$CI->make->sDivCol('6','left',0,array('class'=>'fmns','id'=>'fmns-'.$menu['id']));
											$img = '<img src="'.base_url().'img/noimage.png" style="height:100%;width:100%">';
											if(isset($menu['image']) && $menu['img'] != ""){
												$img = '<img src="'.base_url().$menu['image'].'" style="height:100%;width:100%">';
											}

											$readonly= '';
											if($fmp[$fm] > 0){
												$readonly = 'readonly';
											}

											$CI->make->append('<div class="info-box">'.
													     '<span class="info-box-icon" style="line-height:0px">'.
													     	$img.
													     '</span>'.
													     '<div class="info-box-content">'.
														     '<h5>'.$menu['title'].'</h5>'.
														     '<h5 style="font-size:12px;">'.$menu['subtitle'].'</h5>'.
														     '<div>'.
														     '<input type="hidden" value="'.$menu['id'].'" name="free_menus[]">'.
														     'Qty:<input type="text" name="free_qty[]" class="form-control input-sm free_qty" style="height:22px;padding:0px;padding-left:5px;padding-right:5px;text-align:right" value="'.$fmq[$fm].'" '.$readonly.'>'.
														     'Price:<input type="text" name="promo_amount[]" class="form-control input-sm promo_amount" style="height:22px;padding:0px;padding-left:5px;padding-right:5px;text-align:right" value="'. $fmp[$fm]  .'">'.
														     '<a href="#" class="btn btn-sm btn-danger btn-block fdel" ref="'.$menu['id'].'"  style="height:22px;padding:0px;margin-top:5px;"><i class="fa fa-times"></i> Remove</a>'.
														     '</div>'.
													     '</div>'.
												     '</div>');
										$CI->make->eDivCol();
									}
								}		
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('primary',array('id'=>'coupon-promo-div','style'=>'display:none'));
						$CI->make->sBoxBody();
							$CI->make->H(4,fa('fa-archive').' Coupon');
							$CI->make->sDivRow();
								$CI->make->textarea('','coupon-promo-txt');
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();
				$CI->make->eDivCol();
			$CI->make->eDivRow();
			$CI->make->eDiv();
		$CI->make->eForm();
	return $CI->make->code();	
}
function freePromoSearch($post=array()){
	$CI =& get_instance();
	$CI->make->sForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->input('Name','name',null,null);
				$CI->make->input('Description','description',null,null);
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->inactiveDrop('Is Inactive','inactive',null,null,array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
?>