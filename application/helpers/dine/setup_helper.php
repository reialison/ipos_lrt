<?php
//-----------Branch Details-----start-----allyn
function makeDetailsForm($det=array(),$set=array(),$splashes=array(),$background=array(),$endtrans=array(),$list_trans=array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			// $CI->make->sBox('primary');
				// $CI->make->sBoxBody();
					$CI->make->sTab();
						$tabs = array(
							fa('fa-info-circle')." Details"=>array('href'=>'#details'),
							fa('fa-cogs')." POS"=>array('href'=>'#setup'),
							fa('fa-image')." Images"=>array('href'=>'#image'),
							fa('fa-database')." Database"=>array('href'=>'#database'),
							fa('fa-database')." Sales Transaction"=>array('href'=>'#sales_trans'),
							// fa('fa-download')." Download Update"=>array('href'=>'#Dlupdate'),
						);
					$CI->make->tabHead($tabs,null,array());
					$CI->make->sTabBody();
						$CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
							$CI->make->sForm("setup/details_db",array('id'=>'details_form'));
								$CI->make->sDivRow(array('style'=>'margin:10px;'));
									$CI->make->sDivCol(6);
										$CI->make->hidden('tax_id',iSetObj($det,'tax_id'));
										$CI->make->input('Code','branch_code',iSetObj($det,'branch_code'),'Type Code',array('class'=>'rOkay', 'readonly'=>'readonly'));
										$CI->make->input('Name','branch_name',iSetObj($det,'branch_name'),'Type Name',array('class'=>'rOkay'));
										$CI->make->textarea('Description','branch_desc',iSetObj($det,'branch_desc'),'Type Description',array('class'=>'rOkay'));
										$CI->make->sDivRow();
											$CI->make->sDivCol(6);
												$CI->make->sDiv(array('class'=>'bootstrap-timepicker'));
													$opening_time = date('h:i A',strtotime($det->store_open));
													$CI->make->input('Opening Time','store_open',$opening_time,'',array('class'=>'rOkay timepicker'),null,fa('fa-clock-o'));
												$CI->make->eDiv();
											$CI->make->eDivCol();
											$CI->make->sDivCol(6);
												$CI->make->sDiv(array('class'=>'bootstrap-timepicker'));
													// $CI->make->input('Closing Time','store_close',iSetObj($det,'store_close'),'',array('class'=>'rOkay timepicker'),null,fa('fa-clock-o'));
													$closing_time = date('h:i A',strtotime($det->store_close));
													$CI->make->input('Closing Time','store_close',$closing_time,'',array('class'=>'rOkay timepicker'),null,fa('fa-clock-o'));
												$CI->make->eDiv();
											$CI->make->eDivCol();
										$CI->make->eDivRow();
										// $CI->make->input('TIN','tin',iSetObj($det,'tin'),'TIN',array('class'=>'rOkay'));
										// $CI->make->input('BIR #','bir',iSetObj($det,'bir'),'BIR',array());
										// $CI->make->input('Serial #','serial',iSetObj($det,'serial'),'Serial Number',array());
										$CI->make->input('Accreditation #','accrdn',iSetObj($det,'accrdn'),'Accreditation Number',array('readonly'=>'readonly'));
										$CI->make->input('Machine No.','machine_no',iSetObj($det,'machine_no'),'Machine Number',array());
										$CI->make->input('Permit#','permit_no',iSetObj($det,'permit_no'),'Permit Number',array());
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->input('Contact No.','contact_no',iSetObj($det,'contact_no'),'Type Contact Number',array());
										$CI->make->input('Delivery No.','delivery_no',iSetObj($det,'delivery_no'),'Type Delivery Number',array());
										$CI->make->textarea('Address','address',iSetObj($det,'address'),'Type Branch Address',array('class'=>'rOkay'));
										$CI->make->sDivRow();
											$CI->make->sDivCol(6);
												// $CI->make->sDiv(array('class'=>'bootstrap-timepicker'));
												// 	$CI->make->input('Opening Time','store_open',iSetObj($det,'store_open'),'',array('class'=>'rOkay timepicker'),null,fa('fa-clock-o'));
												// $CI->make->eDiv();
											$CI->make->input('TIN','tin',iSetObj($det,'tin'),'TIN',array('class'=>'rOkay'));
											$CI->make->eDivCol();
											$CI->make->sDivCol(6);
												// $CI->make->sDiv(array('class'=>'bootstrap-timepicker'));
												// 	$CI->make->input('Opening Time','store_open',iSetObj($det,'store_open'),'',array('class'=>'rOkay timepicker'),null,fa('fa-clock-o'));
												// $CI->make->eDiv();
											// $CI->make->input('BIR #','bir',iSetObj($det,'bir'),'BIR',array());
											$CI->make->input('Serial #','serial',iSetObj($det,'serial'),'Serial Number',array());
											$CI->make->eDivCol();
										$CI->make->eDivRow();
										$CI->make->input('Website','website',iSetObj($det,'website'),'Website',array());
										$CI->make->input('Email','email',iSetObj($det,'email'),'Email Address',array());
										$CI->make->input('Branch Color','branch_color',iSetObj($det,'branch_color'),'Branch Color',array());
										$CI->make->sDivRow();
											$CI->make->sDivCol(6);
												// $CI->make->sDiv(array('class'=>'bootstrap-timepicker'));
												// 	$CI->make->input('Opening Time','store_open',iSetObj($det,'store_open'),'',array('class'=>'rOkay timepicker'),null,fa('fa-clock-o'));
												// $CI->make->eDiv();
												$CI->make->inactiveDrop('Is Multibrand','is_multibrand',(iSetObj($det,'is_multibrand')),null,array('style'=>''));
											$CI->make->eDivCol();
											$CI->make->sDivCol(6);
												// $CI->make->sDiv(array('class'=>'bootstrap-timepicker'));
												// 	$CI->make->input('Opening Time','store_open',iSetObj($det,'store_open'),'',array('class'=>'rOkay timepicker'),null,fa('fa-clock-o'));
												// $CI->make->eDiv();
											// $CI->make->input('BIR #','bir',iSetObj($det,'bir'),'BIR',array());
												$CI->make->brandDbDrop('Default Brand','brand',iSetObj($det,'brand'),'Select Brand',array('class'=>'rOkay'));
											$CI->make->eDivCol();
										$CI->make->eDivRow();
										
										// $CI->make->input('RLC Path','rob_path',iSetObj($det,'rob_path'),'RLC PATH',array());
										// $CI->make->input('RLC Username','rob_username',iSetObj($det,'rob_username'),'RLC Username',array());
										// $CI->make->input('RLC Password','rob_password',iSetObj($det,'rob_password'),'RLC Password',array());
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->sDivRow(array('style'=>'margin:10px;'));
									$CI->make->sDivCol(12, 'right');
											$CI->make->button(fa('fa-save fa-fw').' Save Details',array('id'=>'save-btn','class'=>''),'primary');
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								// $CI->make->sDivRow(array('style'=>'margin:10px;'));
								// 	$CI->make->sDivCol(6);
								// 		$CI->make->currenciesDrop('Currency','currency',iSetObj($det,'currency'),'',array());
								// 	$CI->make->eDivCol();
							$CI->make->eForm();
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'setup','class'=>'tab-pane'));
							$CI->make->sForm("setup/pos_settings_db",array('id'=>'settings_form'));
								$CI->make->H(3,'Printing');
								$CI->make->append('<hr style="margin-top:0px;">');
								$CI->make->sDivRow();
									$CI->make->sDivCol(3);
										$CI->make->number('No. of Receipt Prints on Settled','no_of_receipt_print',numInt(iSetObj($set,'no_of_receipt_print')),'',array('class'=>'rOkay'));
										$CI->make->input('Kitchen Printer Name','kitchen_printer_name',iSetObj($set,'kitchen_printer_name'),'');
										$CI->make->input('Beverage Printer Name','kitchen_beverage_printer_name',iSetObj($set,'kitchen_beverage_printer_name'),'');
										$CI->make->input('Printer With Open Cashdrawer','open_drawer_printer',iSetObj($set,'open_drawer_printer'),'');
									$CI->make->eDivCol();
									$CI->make->sDivCol(3);
										$CI->make->number('No. of Prints of Order Slip on Settled','no_of_order_slip_print',numInt(iSetObj($set,'no_of_order_slip_print')),'',array('class'=>'rOkay'));
										$CI->make->number('No. Of Kitchen Prints','kitchen_printer_name_no',iSetObj($set,'kitchen_printer_name_no'),'');
										$CI->make->number('No. Of Beverage Prints','kitchen_beverage_printer_name_no',iSetObj($set,'kitchen_beverage_printer_name_no'),'');
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$foot_rec = iSetObj($det,'rec_footer');
										if($foot_rec != "")
											$foot_rec = str_replace ("<br>","\r\n", $foot_rec );
										$CI->make->textarea('Restaurant Footer','rec_footer',$foot_rec,null);
										// $foot_pos = iSetObj($det,'pos_footer');
										// if($foot_pos != "")
										// 	$foot_pos = str_replace ("<br>","\r\n", $foot_pos );
										// $CI->make->textarea('POS Provider Footer','pos_footer',$foot_pos,null);
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->H(3,'Restart Printer Setup');
								$CI->make->p('This will remove the network use list and reinitialize the setup');

								$CI->make->button(fa('fa-refresh fa-fw').' Restart Printer Setup',array('id'=>'restart-printer-setup-btn','class'=>'','type'=>'button'),'warning');

								$CI->make->H(3,'Add On Charges');
								$CI->make->append('<hr style="margin-top:0px;">');
								$CI->make->sDivRow();
									$CI->make->sDivCol(3);
										$CI->make->decimal('Local Tax Percent','local_tax',numInt(iSetObj($set,'local_tax')),'',2,array('class'=>'rOkay'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->H(3,'Loyalty Card Settings');
								$CI->make->append('<hr style="margin-top:0px;">');
								$CI->make->sDivRow();
									$CI->make->sDivCol(3);
										$CI->make->decimal('For Every Amount','loyalty_for_amount',numInt(iSetObj($set,'loyalty_for_amount')),'',2,array('class'=>'rOkay'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(3);
										$CI->make->decimal('To Points','loyalty_to_points',numInt(iSetObj($set,'loyalty_to_points')),'',2,array('class'=>'rOkay'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->H(3,'Controls');
								$CI->make->append('<hr style="margin-top:0px;">');
								$CI->make->sDivRow();
									$ids = explode(',',$set->controls);
									for($i=1;$i<=1;$i++){

										$falser = false;
										foreach ($ids as $value) {

											$text = explode('=>',$value);
											
												if($text[0] == $i){
													$CI->make->sDivCol(3);
														$CI->make->checkbox(strtoupper($text[1]),'chk['.$text[0].']',$text[0]."=>".$text[1],array(),true);
													$CI->make->eDivCol();
													$falser = true;
													break;
												}
											

										}
										if(!$falser){

											if($i == 1){
												$txt = "DINE IN";
												// $txt = DINEINTEXT;
											}
											// elseif($i == 2){
											// 	$txt = "TAKEOUT";
											// }
											// elseif($i == 3){
											// 	$txt = "MCB";
											
											// // elseif($i == 3){
											// // 	// $txt = "COUNTER";
											// // 	$txt = COUNTERTEXT;
											// // }elseif($i == 4){
											// // 	$txt = "RETAIL";
											// }elseif($i == 4){
											// 	$txt = "PICKUP";
											// }
											// elseif($i == 5){
											// 	$txt = "DELIVERY";
											// }
											// 	// $txt = "TAKEOUT";
											// 	$txt = TAKEOUTTEXT;
											// }elseif($i == 7){
											// 	$txt = "DRIVE-THRU";
											// }elseif($i == 8){
											// 	$txt = "FOOD PANDA";
											// }elseif($i == 9){
											// 	$txt = "EATIGO";
											// }elseif($i == 10){
											// 	$txt = "BIGDISH";
											// }elseif($i == 11){
											// 	$txt = "HONESTBEE";
											// }elseif($i == 12){
											// 	$txt = "ZOMATO";
											// }elseif($i == 13){
											// 	$txt = "PICC";
											// }elseif($i == 14){
											// 	$txt = "AMORSOLO";
											// }elseif($i == 15){
											// 	$txt = "CASHBAR";
											// }elseif($i == 16){
											// 	$txt = "MENUGO";
											// }elseif($i == 17){
											// 	$txt = "ZOMATO-DELIVERY";
											// }elseif ($i == 18) {
											// 	$txt = "GRABFOOD";
											// }elseif ($i == 19) {
											// 	$txt = "LALAFOOD";
											// }elseif ($i == 20) {
											// 	$txt = "PICKAROO";
											// }elseif ($i == 21) {
											// 	$txt = "ECOM";
											// }elseif ($i == 22) {
											// 	$txt = "RESERVATION";
											// }

											$CI->make->sDivCol(3);
												$CI->make->checkbox($txt,'chk['.$i.']',$i."=>".strtolower($txt),array());
											$CI->make->eDivCol();
										}
									}

									foreach($list_trans as $t_id => $val){
										$falser = false;
										foreach ($ids as $value) {

											$text = explode('=>',$value);
											
												if($text[0] == $val->trans_name){
													$CI->make->sDivCol(3);
														$CI->make->checkbox(strtoupper($text[1]),'chk['.$text[0].']',$text[0]."=>".$text[1],array(),true);
													$CI->make->eDivCol();
													$falser = true;
													break;
												}
											

										}
										if(!$falser){

											// if($i == 1){
											// 	// $txt = "DINE IN";
											// 	$txt = DINEINTEXT;
											// }elseif($i == 2){
											// 	$txt = "DELIVERY";
											// }elseif($i == 3){
											// 	// $txt = "COUNTER";
											// 	$txt = COUNTERTEXT;
											// }elseif($i == 4){
											// 	$txt = "RETAIL";
											// }elseif($i == 5){
											// 	$txt = "PICKUP";
											// }elseif($i == 6){
											// 	// $txt = "TAKEOUT";
											// 	$txt = TAKEOUTTEXT;
											// }elseif($i == 7){
											// 	$txt = "DRIVE-THRU";
											// }elseif($i == 8){
											// 	$txt = "FOOD PANDA";
											// }elseif($i == 9){
											// 	$txt = "EATIGO";
											// }elseif($i == 10){
											// 	$txt = "BIGDISH";
											// }elseif($i == 11){
											// 	$txt = "HONESTBEE";
											// }elseif($i == 12){
											// 	$txt = "ZOMATO";
											// }elseif($i == 13){
											// 	$txt = "PICC";
											// }elseif($i == 14){
											// 	$txt = "AMORSOLO";
											// }elseif($i == 15){
											// 	$txt = "CASHBAR";
											// }elseif($i == 16){
											// 	$txt = "MENUGO";
											// }elseif($i == 17){
											// 	$txt = "ZOMATO-DELIVERY";
											// }elseif ($i == 18) {
											// 	$txt = "GRABFOOD";
											// }
											$txt = $val->trans_name;

											$CI->make->sDivCol(3);
												$CI->make->checkbox($txt,'chk['.$val->trans_name.']',$val->trans_name."=>".strtolower($txt),array());
											$CI->make->eDivCol();
										}
									}
								$CI->make->eDivRow();
								$CI->make->H(3,'Inventory');
								$CI->make->append('<hr style="margin-top:0px;">');
								$CI->make->sDivRow();
									$CI->make->sDivCol(3);
										$CI->make->inactiveDrop('Allow Negative Inventory','neg_inv',(iSetObj($set,'neg_inv')),null,array('style'=>''));
									$CI->make->eDivCol();
									$CI->make->sDivCol(3);
										$CI->make->inactiveDrop('Show Menu Images','show_image',(iSetObj($set,'show_image')),null,array('style'=>''));
									$CI->make->eDivCol();
								$CI->make->eDivRow();


								$CI->make->H(3,'Senior Citizen Discount Ceiling');
								$CI->make->append('<hr style="margin-top:0px;">');
								$CI->make->sDivRow();
									$CI->make->sDivCol(3);
										// $CI->make->inactiveDrop('Allow Negative Inventory','neg_inv',(iSetObj($set,'neg_inv')),null,array('style'=>''));
										$CI->make->input('Ceiling Amount','ceiling_amount',iSetObj($set,'ceiling_amount'),'Ceiling Amount',array());
									$CI->make->eDivCol();
									$CI->make->sDivCol(3);
										$CI->make->input('Ceiling Amount MCB','ceiling_mcb',iSetObj($set,'ceiling_mcb'),'Ceiling Amount MCB',array());
										// $CI->make->inactiveDrop('Show Menu Images','show_image',(iSetObj($set,'show_image')),null,array('style'=>''));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								// $CI->make->sDivRow();
								// 	$CI->make->sDivCol(3);
								// 		$CI->make->checkbox('DINE IN','dinein',1,array());
								// 	$CI->make->eDivCol();
								// 	$CI->make->sDivCol(3);
								// 		$CI->make->checkbox('DELIVERY','dinein',2,array());
								// 	$CI->make->eDivCol();
								// $CI->make->eDivRow();
								$CI->make->sDivRow(array('style'=>'margin:10px;'));
									$CI->make->sDivCol(12, 'right');
											$CI->make->button(fa('fa-save fa-fw').' Save',array('id'=>'save-pos-btn','class'=>''),'primary');
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eForm();
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'image','class'=>'tab-pane'));
							$CI->make->H(3,'Slider Images');
							$CI->make->append('<hr style="margin-top:0px;">');
							$CI->make->sDivRow();
								$CI->make->sDivCol(12,'right');
									$btnMsg = fa('fa-upload').' Upload Image / Video';
									$CI->make->A($btnMsg,'setup/upload_splash_images/',array(
																				'id'=>'upload-splsh-img',
																				'rata-title'=>'Splash Image Upload',
																				'rata-pass'=>'setup/upload_splash_images_db/splash_images',
																				'rata-form'=>'upload_image_form',
																				'class'=>'btn btn-primary upload-popup'
																			));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow();
								// echo "<pre>",print_r($det),"</pre>";die();
								$CI->make->hidden('img_vid',iSetObj($set,'img_vid'));
								foreach ($splashes as $res) {
							        $file_type = strtolower(end(explode('.',$res->img_path)));
							        $img = array("jpeg","jpg","png");

									// echo "<pre>",print_r($file_type),"</pre>";die();
									$CI->make->sDivCol(4);
										// $src ="data:image/jpeg;base64,".base64_encode($res->img_blob);
										if(in_array($file_type, $img) === true){
											$src = base_url().$res->img_path;
											$CI->make->img($src,array('style'=>'width:250px;margin-bottom:0px;margin-top:10px;','class'=>'thumbnail'));
											$CI->make->A(fa('fa-trash').'Delete','#',array('class'=>'del-spl-btn btn btn-danger ','ref'=>$res->img_id,'style'=>'margin:0px !important;'));
										}
										else{
											$src = base_url().$res->img_path;
											$CI->make->append('
												<video width="320" height="240" loop autoplay>
												  <source src="'.$src.'" type="video/'.$file_type.'">
												</video>
												');
											$CI->make->A(fa('fa-trash').'Delete','#',array('class'=>'del-spl-btn btn btn-danger ','ref'=>$res->img_id,'style'=>'margin:0px !important;'));
										}
									$CI->make->eDivCol();
								}
							$CI->make->eDivRow();
							$CI->make->H(3,'Background Images');
							$CI->make->append('<hr style="margin-top:0px;">');
							$CI->make->sDivRow();
								$CI->make->sDivCol(12,'right');
									$btnMsg = fa('fa-upload').' Upload Image / Video';
									$CI->make->A($btnMsg,'setup/upload_splash_images/',array(
																				'id'=>'upload-background-img',
																				'rata-title'=>'Splash Image Upload',
																				'rata-pass'=>'setup/upload_splash_images_db/background_images',
																				'rata-form'=>'upload_image_form',
																				'class'=>'btn btn-primary upload-popup'
																			));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow();
								// echo "<pre>",print_r($det),"</pre>";die();
								$CI->make->hidden('img_vid',iSetObj($set,'img_vid'));
								foreach ($background as $res) {
							        $file_type = strtolower(end(explode('.',$res->img_path)));
							        $img = array("jpeg","jpg","png");

									// echo "<pre>",print_r($file_type),"</pre>";die();
									$CI->make->sDivCol(4);
										// $src ="data:image/jpeg;base64,".base64_encode($res->img_blob);
										if(in_array($file_type, $img) === true){
											$src = base_url().$res->img_path;
											$CI->make->img($src,array('style'=>'width:250px;margin-bottom:0px;margin-top:10px;','class'=>'thumbnail'));
											$CI->make->A(fa('fa-trash').'Delete','#',array('class'=>'del-spl-btn btn btn-danger ','ref'=>$res->img_id,'style'=>'margin:0px !important;'));
										}
										else{
											$src = base_url().$res->img_path;
											$CI->make->append('
												<video width="320" height="240" loop autoplay>
												  <source src="'.$src.'" type="video/'.$file_type.'">
												</video>
												');
											$CI->make->A(fa('fa-trash').'Delete','#',array('class'=>'del-spl-btn btn btn-danger ','ref'=>$res->img_id,'style'=>'margin:0px !important;'));
										}
									$CI->make->eDivCol();
								}
							$CI->make->eDivRow();
							$CI->make->H(3,'End Transaction Images');
							$CI->make->append('<hr style="margin-top:0px;">');
							$CI->make->sDivRow();
								$CI->make->sDivCol(12,'right');
									$btnMsg = fa('fa-upload').' Upload Image / Video';
									$CI->make->A($btnMsg,'setup/upload_splash_images/',array(
																				'id'=>'upload-endtrans-img',
																				'rata-title'=>'Splash Image Upload',
																				'rata-pass'=>'setup/upload_splash_images_db/endtrans_images',
																				'rata-form'=>'upload_image_form',
																				'class'=>'btn btn-primary upload-popup'
																			));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow();
								// echo "<pre>",print_r($det),"</pre>";die();
								$CI->make->hidden('img_vid',iSetObj($set,'img_vid'));
								foreach ($endtrans as $res) {
							        $file_type = strtolower(end(explode('.',$res->img_path)));
							        $img = array("jpeg","jpg","png");

									// echo "<pre>",print_r($file_type),"</pre>";die();
									$CI->make->sDivCol(4);
										// $src ="data:image/jpeg;base64,".base64_encode($res->img_blob);
										if(in_array($file_type, $img) === true){
											$src = base_url().$res->img_path;
											$CI->make->img($src,array('style'=>'width:250px;margin-bottom:0px;margin-top:10px;','class'=>'thumbnail'));
											$CI->make->A(fa('fa-trash').'Delete','#',array('class'=>'del-spl-btn btn btn-danger ','ref'=>$res->img_id,'style'=>'margin:0px !important;'));
										}
										else{
											$src = base_url().$res->img_path;
											$CI->make->append('
												<video width="320" height="240" loop autoplay>
												  <source src="'.$src.'" type="video/'.$file_type.'">
												</video>
												');
											$CI->make->A(fa('fa-trash').'Delete','#',array('class'=>'del-spl-btn btn btn-danger ','ref'=>$res->img_id,'style'=>'margin:0px !important;'));
										}
									$CI->make->eDivCol();
								}
							$CI->make->eDivRow();

						$CI->make->eTabPane();

						$CI->make->sTabPane(array('id'=>'database','class'=>'tab-pane'));
							$CI->make->H(3,'Database');
							$CI->make->append('<hr style="margin-top:0px;">');
							$CI->make->sForm("setup/pos_database_db",array('id'=>'database_form'));
								$CI->make->sDivRow();
									$CI->make->sDivCol(5,'left');
										$CI->make->input('Backup Path','backup_path',iSetObj($set,'backup_path'),'',array('class'=>'rOkay'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->sDivRow();
									$CI->make->sDivCol(12,'left');
											$CI->make->button(fa('fa-save fa-fw').' Save Details',array('id'=>'save-db-btn','class'=>'','style'=>'margin-right:6px;'),'primary');
									// $CI->make->sDivCol(6,'right');
											$CI->make->button(fa('fa-download fa-fw').' Manual Back Up',array('id'=>'backup-db-btn','class'=>''),'success');
									// $CI->make->eDivCol();
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->sDivRow(array('style'=>'margin: 20px 0 15px;'));
							$CI->make->eDivRow();
							$CI->make->eForm();
								$CI->make->sDiv(array('class'=>'note note-warning'));
									$CI->make->H(4,'Migrate Data to Master Server',array('class'=>'block','style'=>'color:red;'));
									$CI->make->P('This action will consolidate the data to the master server and will do price update on the menus based on master data. Please wait for the consolidation to finish. ');
									$CI->make->button('  SUBMIT ',array('id'=>'warning-db-button','class'=>'btn green-haze','style'=>'margin: 20px 0 15px;'));
								$CI->make->eDiv();

								$CI->make->sDiv(array('class'=>'note note-success'));
									$CI->make->H(4,'Fast Download Menus from HQ',array('class'=>'block','style'=>'color:blue;'));
									$CI->make->P('This will download menus, categories and items from HQ ');
									$CI->make->button('  DOWNLOAD UPDATE ',array('id'=>'download-db-button','class'=>'btn green-haze','style'=>'margin: 20px 0 15px;'));
								$CI->make->eDiv();

								$CI->make->sDiv(array('class'=>'note note-success'));
									$CI->make->H(4,'Sync Maintenance setup to Dine',array('class'=>'block','style'=>'color:blue;'));
									$CI->make->P('This will sync menus and other maintenance setup from main database');
									$CI->make->button('  SYNC UPDATE ',array('id'=>'sync-db-button','class'=>'btn green-haze','style'=>'margin: 20px 0 15px;'));
								$CI->make->eDiv();
						$CI->make->eTabPane();

						$CI->make->sTabPane(array('id'=>'sales_trans','class'=>'tab-pane'));
							$CI->make->sDiv(array('class'=>'note note-warning'));
									$CI->make->H(4,'Download Sales Transaction for HQ',array('class'=>'block','style'=>'color:red;'));
									$CI->make->P('This action will download all sales transaction not available in HQ');
									$CI->make->button('SUBMIT ',array('id'=>'warning-sales-trans-button','class'=>'btn green-haze','style'=>'margin: 20px 0 15px;'));
							$CI->make->eDiv();

							$CI->make->sDiv(array('class'=>'note note-success'));
									$CI->make->H(4,'Download Sales Transaction',array('class'=>'block','style'=>'color:blue;'));
									$CI->make->P('This will download sales transaction.');
									$CI->make->date('Date','date',date('m/d/Y'),null,array('class'=>'rOkay','style'=>'position:initial;'),null,fa('fa-calendar'));
									$CI->make->button('  DOWNLOAD ',array('id'=>'download-sales-trans-button','class'=>'btn green-haze','style'=>'margin: 20px 0 15px;'));
							$CI->make->eDiv();

						$CI->make->eTabPane();

						// $CI->make->sTabPane(array('id'=>'Dlupdate','class'=>'tab-pane'));
						// 	$CI->make->sDiv(array('class'=>'note note-warning'));
						// 			$CI->make->H(4,'Migrate Data to Master Server',array('class'=>'block'));
						// 			$CI->make->P('This action will consolidate the data to the master server and will do price update on the menus based on master data. Please proceed with caution. ');
						// 			$CI->make->button(fa('fa fa-warning').'  WARNING! ',array('id'=>'warning-db-button','class'=>'btn green-haze','style'=>'margin: 20px 0 15px;'));
						// 		$CI->make->eDiv();
						// $CI->make->eTabPane();
						
					$CI->make->eTabBody();
				$CI->make->eTab();
				// $CI->make->eBoxBody();
			// $CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function makeImageUploadForm($det=null){
	$CI =& get_instance();
		$CI->make->sForm("setup/upload_splash_images_db",array('id'=>'upload_image_form','enctype'=>'multipart/form-data'));
			$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
				$CI->make->sDivCol();
					$CI->make->A(fa('fa-picture-o').' Select an Image','#',array(
															'id'=>'select-img',
															'class'=>'btn btn-primary'
														));
					$CI->make->append('<br>');
				$CI->make->eDivCol();
			$CI->make->eDivRow();
			$CI->make->sDivRow();
				$CI->make->sDivCol();
					$thumb = base_url().'img/noimage.png';
					// if(iSetObj($det,'image')  != ""){
					// 	$thumb = base_url().'uploads/'.iSetObj($det,'image');
					// }
					$CI->make->img('',array('class'=>'media-object thumbnail','id'=>'target','style'=>'width:100%;'));
					$CI->make->file('fileUpload',array('style'=>'display:none;'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
//-----------Branch References-----end-----allyn

function makePrinterForm($det=array(),$set=array(),$splashes=array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			// $CI->make->sBox('primary');
				// $CI->make->sBoxBody();
					$CI->make->sTab();
						$tabs = array(
							fa('fa-print')." Printer"=>array('href'=>'#printer'),
						);
					$CI->make->tabHead($tabs,null,array());
					$CI->make->sTabBody();
						$CI->make->sTabPane(array('id'=>'printer','class'=>'tab-pane active'));
							$CI->make->H(3,'Printer');
							$CI->make->append('<hr style="margin-top:0px;">');
								$CI->make->sDiv(array('class'=>'note note-danger'));
									$CI->make->H(4,'Restart Printer',array('class'=>'block','style'=>'color:red;'));
									$CI->make->P('This action will restart all printers. Please proceed with caution. ');
									$CI->make->button(fa('fa fa-warning').'  WARNING! ',array('id'=>'warning-db-button','class'=>'btn green-haze','style'=>'margin: 20px 0 15px;'));
								$CI->make->eDiv();
						$CI->make->eTabPane();	
					$CI->make->eTabBody();
				$CI->make->eTab();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function makePrinterSetupForm($printers = null)
{
	$CI =& get_instance();
	$CI->make->sForm("setup/printer_setup_db",array('id'=>'printer_setup_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px'));
			$CI->make->sDivCol(6);
				$CI->make->hidden('id',iSetObj($printers,'id'));
				// $CI->make->input('Code','disc_code',iSetObj($receipt_disc,'disc_code'),'Discount Code',array('class'=>'rOkay'));
				$CI->make->input('Printer Name','printer_name',iSetObj($printers,'printer_name'),'Printer Name',array('class'=>'rOkay'));
				$CI->make->input('Printer Name Assigned','printer_name_assigned',iSetObj($printers,'printer_name_assigned'),'Printer Name Assigned',array('class'=>'rOkay'));
				$CI->make->input('No. Of Prints','no_prints',iSetObj($printers,'no_prints'),'Number of Prints',array('class'=>'rOkay'));
				$CI->make->menuSubCategoriesDrop('Menu Type','sub_cat',iSetObj($printers,'sub_cat'),'Select Type',array('class'=>'rOkay'));
				// $CI->make->input('Rate'
				// 	, 'disc_rate'
				// 	, iSetObj($receipt_disc,'disc_rate')
				// 	, 'Rate'
				// 	, array('class'=>'rOkay','style'=>'width:85px')
				// );
				// $CI->make->inactiveDrop('Absolute','fix',iSetObj($receipt_disc,'fix'),'',array('style'=>'width: 85px;'));
				// $CI->make->inactiveDrop('Make No Tax','no_tax',iSetObj($receipt_disc,'no_tax'),'',array('style'=>'width: 85px;'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($printers,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		$CI->make->sDivRow();
		    $CI->make->sDivCol(4,'left',3);
		        $CI->make->button(fa('fa-save')." Save Details",array('id'=>'save-btn','class'=>''),'success');
		    $CI->make->eDivCol();
		    $CI->make->sDivCol(2);
		        $CI->make->A(fa('fa-reply')." Go Back",base_url().'user',array('class'=>'btn btn-primary'));
		    $CI->make->eDivCol();
		$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
?>